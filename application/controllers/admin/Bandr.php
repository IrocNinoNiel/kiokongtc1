<?php
ini_set('max_execution_time', -1); 
ini_set('memory_limit','2048M'); 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bandr extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		setHeader( 'admin/Bandr_model' );
	}

	public function Retrieve(){
		$path = './backup/';
		if(isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'],'Google App Engine') !== false){
			$path = BUCKET_NAME . 'backup/';
		}
		$arrView = array();
		array_multisort( array_map( 'filemtime', ( $arrFiles = glob( $path.'*.sql', GLOB_BRACE ) ) ), SORT_DESC, $arrFiles );

		$ident = 0;
		foreach( $arrFiles as $key => $val ){
			$ident = $ident + 1;
			$fileName = explode( '/', $val );
			if( pathinfo( $val, PATHINFO_EXTENSION ) == 'sql' ){
				$valexp = explode( '_', $fileName[2] );
				$bdate = substr( $valexp[0], 0, 4 ) . '-' . substr( $valexp[0], 4, 2 ) . '-' . substr( $valexp[0], 6, 2 );
				$ttime = date( 'h:i A', strtotime( $bdate . ' '. ( substr( $valexp[0], 8 , 2 ) . ':' . substr( $valexp[0], 10, 2 ) ) ) );
				$arrView[] = array(
					'bdate'			=> date( 'm/d/Y', strtotime( $bdate ) )
					,'btime'		=> $ttime
					,'filename'		=> $fileName[2]
					,'user'			=> $this->model->getUserFullName( (int)$valexp[2] )
					,'ident'		=> $ident
				);
			}
		}

		die(
			json_encode(
				array(
					'success'	=> true
					,'view'		=> $arrView
					,'total'	=> count( $arrView )
				)
			)
		);
	}
	
	public function getSetting(){
		$viewAll = $this->model->GetBackUp();
		die(
			json_encode(
				array(
					'view'	=> $viewAll
				)
			)
		);
	}
	
	/* This process is used to retrieve all tables in the database.
	 * This will then be used to backup the database per table.
	 */
	public function getDBTables(){
		$tables		= $this->model->getDBTables();
		die(
			json_encode(
				array(
					'success'	=> true
					,'view'		=> $tables
				)
			)
		);
	}

	/* this will process backup for the whole database but the process is divided per table
	 * to make more room for the system to process the entire database.
	 */
	public function backupTable(){
		$params	= getData();

		$path = './backup/';
		if(isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'],'Google App Engine') !== false){
			$path = BUCKET_NAME . 'backup/';
		}

		$fileName = $params['fileName'];
		$sqlStatement = '';

		if( empty( $fileName ) ) $fileName = date( 'YmdHis' ) . '_dbbackup_' . $this->USERID . '.sql';

		if( @file_exists( $path . $fileName ) ){
			$sqlStatement = @file_get_contents( $path . $fileName );
		}

		if( empty( $sqlStatement ) ){
			$sqlStatement .= "-- DB BACK UP Created on: " . date( 'm/d/Y H:i:s' ) . PHP_EOL . PHP_EOL;
		}

		/* This function here is used to analyze the current table and convert the existing table in the database as sql create query string. */
		$createStatement = $this->model->createDBStatement( $params );
		/* Retrieve all records on the current table */
		$data = $this->model->getTableData( $params );

		/* Drop table if this is existing, to be used when restoring database. */
		$sqlStatement .= "DROP TABLE IF EXISTS `$params[tableName]`;";
		$sqlStatement .= ":||:Separator:||:" . PHP_EOL;
		/* Adds 2 empty new line on the string */
		$sqlStatement .= PHP_EOL . PHP_EOL;
		/* result of the createDBStatement function */
		$sqlStatement .= $createStatement['Create Table'] . ";";
		$sqlStatement .= ":||:Separator:||:" . PHP_EOL;
		/* Adds 2 empty new line on the string */
		$sqlStatement .= PHP_EOL . PHP_EOL;
		/* Adds function to lock so that no other function will then be able to use the table */
		$sqlStatement .= "LOCK TABLES `$params[tableName]` WRITE;";
		$sqlStatement .= ":||:Separator:||:" . PHP_EOL;

		/* Process transferring retrieved records from array to insert sql statement. */
		if(count($data) > 0){
			$sqlStatement .= " INSERT INTO `$params[tableName]` VALUES";
			foreach( $data AS $rowData ){
				$sqlStatement .= '(' . implode( ',', $this->flatten( $rowData ) ) . '),';
			}
			$sqlStatement = substr( $sqlStatement, 0, -1 );
			$sqlStatement .= ';';
			$sqlStatement .= ":||:Separator:||:" . PHP_EOL;
		}
		/* Adds 2 empty new line on the string */
		$sqlStatement .= PHP_EOL . PHP_EOL;
		$sqlStatement .= ":||:Separator:||:" . PHP_EOL;
		/* used to unlock table to return it to its normal operational functions */
		$sqlStatement .= "UNLOCK TABLES;" . PHP_EOL;
		$sqlStatement .= ":||:Separator:||:" . PHP_EOL;
		/* open the file where to write the sql statement for backup. */
		$handle = @fopen( $path . $fileName, 'w' );
		/* this process writing the values to the physical file. */
		$fwrite = @fwrite( $handle, $sqlStatement );
		/* this is used to determine if the fwrite function is successful or not. */
		if($fwrite === false){
			die(
				json_encode(
					array(
						'success' => false
					)
				)
			);
		}
		/* closes the file handling functions. */
		@fclose( $handle );
		die(
			json_encode(
				array(
					'success'	=> true
					,'fileName'	=> $fileName
				)
			)
		);

	}

	/* This function is used to make a two dimensional array into a normal array
	 * e.g. Array provided array( 'field1' => value1, 'field2' => value2 )
	 * return will be array( value1, value2 )
	 */
	public function flatten(array $array) {
		$return = array();
		array_walk_recursive( $array, 
			function( $a ) use ( &$return ) {
				$a = ( strlen( $a ) > 0 ) ? ( ( !is_numeric( $a ) ) ? "'".addslashes( $a )."'" : addslashes( $a ) ) : 'null' ;
				$a = preg_replace( "/\n/", "\\n", $a );
				$return[] = $a; 
			}
		);
		return $return;
	}

	public function restoreFile(){
		$params			= getData();
		$file			= $_FILES['restorefile' . $params['module']];
		$match			= 0;
		$filecontents	= '';
		$filename		= '';

		if( $file['size'] ){ /* this means that the process is used to restore a file from the user */
			$filecontents	= @file_get_contents( $file['tmp_name'] );
			$filename		= $file['name'];
		}
		else{ /* this means that the process is used to restore a file from the backup and restore list */
			if( isset( $params['filename'] ) ){
				$filename	= $params['filename'];
				$path		= './backup/';
				if(isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'],'Google App Engine') !== false){
					$path	= BUCKET_NAME . 'backup/';
				}
				$filecontents	= @file_get_contents( $path . $params['filename'] );
			}
			else{
				$match = 2;
			}
		}

		if( !empty($filecontents) ){
			$lineData	= explode( ":||:Separator:||:", $filecontents );
			$this->db->trans_begin();
			foreach( $lineData as $key => $value ){
				$value	= trim( substr( $value, 0, -1 ) );
				if( !empty( $value ) ) $this->model->executeQuery( $value );
			}

			$success	= $this->db->trans_status();
			if( $success ){
				$this->db->trans_commit();
				setLogs( array(
					'actionLogDescription'	=> 'Restored a file: '. $filename
					,'idModule'				=> $params['idModule']
				) );
			}
			else{
				$this->db->trans_rollback();
				$match = 2;
			}
		}
		else{
			$match = 1;
		}

		die(
			json_encode(
				array(
					'success'	=> true
					,'match'	=> $match
				)
			)
		);
	}

	public function checkPassword(){
		$params			= getData();
		$passCorrect	= $this->model->passwordChecking( $params );
		$confirm		= 1;
		if( !$passCorrect ){
			$confirm	= 0;
		}
		die(
			json_encode(
				array(
					'success'	=> true
					,'confirm'	=> $confirm
				)
			)
		);
	}

	public function download($filename,$fileExt=''){
		$path = './backup/';
		if(isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'],'Google App Engine') !== false){
			$path = BUCKET_NAME . 'backup/';
		}
		
		$data = file_get_contents( $path . $filename . '.' . $fileExt );
		$name = '\\' . $filename . '.' . $fileExt;
		force_download2($name, $data);
	}

	public function deleteRecord(){
		$params	= getData();
		$path = './backup/';
		if(isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'],'Google App Engine') !== false){
			$path = BUCKET_NAME . 'backup/';
		}
		setLogs( array(
			'actionLogDescription'	=> 'Deleted a file: '. $params['file']
			,'idModule'				=> $params['idModule']
		) );
		@unlink( $path . $params['file'] );
	}

	public function saveSchedule(){
		$params		= getData();
		var_dump( $params );
		$this->model->saveAutobackSchedule( $params );
		die(
			json_encode(
				array(
					'success'	=> true
				)
			)
		);
	}

	public function processAutoBackUp(){
		$data = $this->model->GetBackUp();
		$path = './backup/';
		if(isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'],'Google App Engine') !== false){
			$path = BUCKET_NAME . 'backup/';
		}

		if( count( (array)$data ) > 0 ){
			$settings			= $data[0];
			$currentDateTime	= strtotime( date( 'Y-m-d H:i:s' ) );
			$setupDateTime		= strtotime( date( 'Y-m-d' ) . ' ' . $settings['abTime'] );
			$dayNumber			= date( 'N', strtotime( $currentDateTime ) );
			$dayNumber			= ( $dayNumber == 7? 1 : ( $dayNumber - 1 ) );
			$curWeek			= getWeeks( date( 'Y-m-d' ) );
			$latestBackupWeek	= getWeeks( $settings['latestBackupDate'] );
			$fileName			= date( 'YmdHis' ) . '_dbbackupauto_0.sql';

			if(
				/* daily */
				( $settings['abType'] == 1 && $setupDateTime <= $currentDateTime && strtotime( $settings['latestBackupDate'] ) != strtotime( date('Y-m-d') ) )
				||
				/* weekly */
				( $settings['abType'] == 2 && $setupDateTime <= $currentDateTime && $dayNumber == $settings['abDay'] && strtotime( $settings['latestBackupDate'] ) != strtotime( date('Y-m-d') ) )
				||
				/* month */
				( $settings['abType'] == 3 && $setupDateTime <= $currentDateTime && $dayNumber == $settings['abDay'] && $curWeek != $latestBackupWeek && $curWeek == $settings['abWeek'] )
			){
				/* process backup */
				$tables		= $this->model->getDBTables();
				if( count( (array)$tables ) > 0 ){
					$tables			= $this->flatten( $tables );
					$sqlStatement	= '';
					foreach( $tables as $key => $value ){
						$value = str_replace("'","",$value);
						if( empty( $sqlStatement ) ){
							$sqlStatement .= "-- DB BACK UP Created on: " . date( 'm/d/Y H:i:s' ) . PHP_EOL . PHP_EOL;
						}

						/* This function here is used to analyze the current table and convert the existing table in the database as sql create query string. */
						$createStatement = $this->model->createDBStatement( array(
							'tableName'	=> $value
						) );
						/* Retrieve all records on the current table */
						$data = $this->model->getTableData( array(
							'tableName'	=> $value
						) );

						/* Drop table if this is existing, to be used when restoring database. */
						$sqlStatement .= "DROP TABLE IF EXISTS `$value`;";
						$sqlStatement .= ":||:Separator:||:" . PHP_EOL;
						/* Adds 2 empty new line on the string */
						$sqlStatement .= PHP_EOL . PHP_EOL;
						/* result of the createDBStatement function */
						$sqlStatement .= $createStatement['Create Table'] . ";";
						$sqlStatement .= ":||:Separator:||:" . PHP_EOL;
						/* Adds 2 empty new line on the string */
						$sqlStatement .= PHP_EOL . PHP_EOL;
						/* Adds function to lock so that no other function will then be able to use the table */
						$sqlStatement .= "LOCK TABLES `$value` WRITE;";
						$sqlStatement .= ":||:Separator:||:" . PHP_EOL;

						/* Process transferring retrieved records from array to insert sql statement. */
						if(count($data) > 0){
							$sqlStatement .= " INSERT INTO `$value` VALUES";
							foreach( $data AS $rowData ){
								$sqlStatement .= '(' . implode( ',', $this->flatten( $rowData ) ) . '),';
							}
							$sqlStatement = substr( $sqlStatement, 0, -1 );
							$sqlStatement .= ';';
							$sqlStatement .= ":||:Separator:||:" . PHP_EOL;
						}
						/* Adds 2 empty new line on the string */
						$sqlStatement .= PHP_EOL . PHP_EOL;
						$sqlStatement .= ":||:Separator:||:" . PHP_EOL;
						/* used to unlock table to return it to its normal operational functions */
						$sqlStatement .= "UNLOCK TABLES;" . PHP_EOL;
						$sqlStatement .= ":||:Separator:||:" . PHP_EOL;
					}

					/* open the file where to write the sql statement for backup. */
					$handle = @fopen( $path . $fileName, 'w' );
					/* this process writing the values to the physical file. */
					$fwrite = @fwrite( $handle, $sqlStatement );
					/* this is used to determine if the fwrite function is successful or not. */
					if($fwrite === false){
						die(
							json_encode(
								array(
									'success'	=> false
								)
							)
						);
					}
					@fclose( $handle );
					/* update autobackup table for latest backup date value */
					$this->model->saveAutobackSchedule( array(
						'idAB'				=> $settings['idAB']
						,'latestBackupDate'	=> date( 'Y-m-d' )
					) );
					die(
						json_encode(
							array(
								'success'	=> true
							)
						)
					);
				}
			}
		}
	}
}
?>
