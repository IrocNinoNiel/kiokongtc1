<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Standards extends CI_Controller {
	/* Class constructor */
	public function __construct(){
		parent::__construct();
		$this->load->library( 'encryption' );
		setHeader( 'standards/Standards_model' );
	}	
	
	public function getComboSearch(){
		$data = getData();
		$view = $this->model->getComboSearch( $data );

		/**
		 * Added by Mak2x 
		 * Customize searching for encrypted field (Employee/User)
		 * 20-May-2022
		 * */ 
		if ( $data['tableName'] == 'employee' ) {
			$view = decryptUserData( $view );
			
			if ( isset( $data['query'] ) ) {
				$temp = array();

				foreach ($view as $key) {
					$strpos = strpos( strtolower( $key['name'] ), strtolower( $data['query'] ) );
					if ( gettype($strpos) == "integer" )
						array_push( $temp, $key );
				}

				$view = $temp;
			}
		}
		/** End of Customize searching for encrypted field (Employee/User) | 20-May-2022 */

		if( (int)$data['hasAll'] == 1 ){
			array_unshift( $view, array(
				'id'	=> -1
				,'name'	=> 'All'
			) );
		}

		die(
			json_encode(
				array ( 
					'success' 	=> true
					,'view' 	=> $view
				)
			)
		);
	
	}
	
	public function listPDF(){
		$data 		    = getData();
		
		$columnArray    = json_decode( $data['columnArray'], true );

		$params			= array();
		
		if( isset( $data['filters'] ) ){
			$filters        = json_decode( $data['filters'], true );
			$count 			= count( $filters ) / 2;
			for( $x=0; $x<$count; $x++ ){
				for( $y=0; $y<2; $y++ ){
					if( $y%2 == 0 ){
						$params['subFilter'.$x] = $filters[$y + ( 2 * $x )]['v2'];
					}
					else{
						$params['query'.$x]		= $filters[$y + ( 2 * $x )]['v2'];
					}
				}
			}
		}
		
		$extraParams = json_decode( $data['extraParams'], true );
		if(count($extraParams) > 0){
			foreach($extraParams as $kk => $dd){
				$params[$kk] = $dd;
			}
		}
		
		$params['pdf'] = true;
		
		$this->load->model( $data['folder'].'/'.$data['moduleName'].'_model', 'module' );  
		$view = $this->module->viewAll( $params );
		$view = $this->DecryptReportsData( $view, $data['moduleName'] );
		
		$params['actionLogDescription'] = '' .$data['title']. ": " .$this->USERFULLNAME. ' printed a PDF report'  ;
		$params['idEu'] = $this->USERID;
		$params['usertype'] = $this->USERTYPEID;
		$config = array(
					'file_name'		=> $data['title'].' List'
					,'folder_name' 	=> $data['folder']
					,'records' 		=> $view
					,'header' 		=> $columnArray
					,'orientation'  => $data['orientation']
				);

		setLogs( $params );
		generateTcpdf( $config );
	

	}
	
	public function listExcel(){

		$data 		    = getData();
		$headerArray    = json_decode( $data['headerArray'], true );
		$dIndexArray    = json_decode( $data['dIndexArray'], true );

		$params			= array();

		if( isset( $data['filters'] ) ){

			$filters        = json_decode( $data['filters'], true );
			$count 			= count( $filters ) / 2;
			
			for( $x=0; $x<$count; $x++ ){
				for( $y=0; $y<2; $y++ ){
					if( $y%2 == 0 ){
						$params['subFilter'.$x] = $filters[$y + ( 2 * $x )]['v2'];
					}
					else{
						$params['query'.$x]		= $filters[$y + ( 2 * $x )]['v2'];
					}
				}
			}
		}
		
		$extraParams = json_decode( $data['extraParams'], true );

		if(count($extraParams) > 0){
			foreach($extraParams as $kk => $dd){
				$params[$kk] = $dd;
			}
		}
		
		$params['pdf'] = true;
		
		$this->load->model( $data['folder'].'/'.$data['moduleName'].'_model', 'module' );  
		$view = $this->module->viewAll( $params );
		$view = $this->DecryptReportsData( $view, $data['moduleName'] );
		
		$csvarray[] = array( 'title' => $data['title'].' List' );
		$csvarray[] = array( 'space' => '' );

		$params['actionLogDescription'] = '' .$data['title']. ": " .$this->USERNAME. ' printed a Excel report'  ;
		$params['idEu'] = $this->USERID;
		$params['usertype'] = $this->USERTYPEID;
		
		$csvarray[] = $headerArray[0];
		
		foreach( $view as $row ){
			$tempArray = array();
			foreach( $dIndexArray[0] as $key => $index ){
				$tempArray[$key] = $row[$index];
			}
			$csvarray[] = $tempArray;
		}
		
		setLogs( $params );
		
		writeCsvFile(
			array(
				'csvarray' 	 => $csvarray
				,'title' 	 => $data['title'].' List'
				,'directory' => $data['folder']
			)
		);
	}
	
	public function download( $title, $folder ){
		force_download(
			array(
				'title'      => $title
				,'directory' => $folder
			)
		);
	}

	/**
	 * 22-04-2022
	 * Added by Makmak 
	 * function to decrypt data for listPDF and listExcel reports 
	 */
	public function DecryptReportsData( $view, $module )
	{
		switch ( $module ) {
			case 'Inventoryconversion':
				$_viewHolder = $view;
				foreach( $_viewHolder as $idx => $po ){
					if( isset( $po['itemSK'] ) && !empty( $po['itemSK'] ) ){
						$this->encryption->initialize( array( 'key' => generateSKED( $po['itemSK'] )));
						$_viewHolder[$idx]['item'] = $this->encryption->decrypt( $po['item'] );
					}
				}
				$view = $_viewHolder;
				break;
			
			case 'Adjustmentsacc':
				$_viewHolder = $view;
				foreach( $_viewHolder as $idx => $record ){
					if( isset( $record['preparedBySK'] ) && !empty( $record['preparedBySK'] ) ){
						$this->encryption->initialize( array( 'key' => generateSKED( $record['preparedBySK'] )));
						$_viewHolder[$idx]['preparedByName'] = $this->encryption->decrypt( $record['preparedByName'] );
					}
					if( isset( $record['notedBySK'] ) && !empty( $record['notedBySK'] ) ){
						$this->encryption->initialize( array( 'key' => generateSKED( $record['notedBySK'] )));
						$_viewHolder[$idx]['notedByName'] = $this->encryption->decrypt( $record['notedByName'] );
					}
					if( isset( $record['costCenterSK'] ) && !empty( $record['costCenterSK'] ) ){
						$this->encryption->initialize( array( 'key' => generateSKED( $record['costCenterSK'] )));
						$_viewHolder[$idx]['costCenterName'] = $this->encryption->decrypt( $record['costCenterName'] );
					}
				}
				$view = $_viewHolder;
				break;

			default:
				break;
		}

		return $view;
	}
	
	public function printLogs(){
		$this->model->printLogs( getData() );
		echo json_encode( array( 'success'=>true ) );
	}

	public function getSupplier(){
		$params = getData();
		$view	= $this->model->getSupplier( $params );
		$view = decryptSupplier( $view );
		
		if( isset($params['hasAll']) ) {
            array_unshift( $view, array(
                'id' => 0
                ,'name' => 'All'
            ));
        }
		
		die(
			json_encode(
				array(
					'success'	=> true
					,'view'		=> $view
				)
			)
		);
	}

	public function gridJournalEntry(){
		$data = getData();
		$list = $this->model->gridJournalEntry( $data );
		
		
		die(
			json_encode(
				array(
					'success'	=> true
					,'view'		=> $list
					,'total'	=> count( $list )
				)
			)
		);
	}

	public function getDefaultEntries(){
		$params = getData();
		$view	= $this->model->getDefaultEntries( $params );
		
		die(
			json_encode(
				array(
					'success'	=> true
					,'view'		=> $view
					,'total'	=> count( $view )
				)
			)
		);
	}

	public function getDefaultAccounts(){
		$params = getData();
		$view = $this->model->getDefaultAccounts( $params );

		die(
			json_encode(
				array(
					'success'	=> true
					,'view'		=> $view
					,'total'	=> count( $view )
				)
			)
		);
	}
	
	public function getCoa(){
		$params = getData();
		$view	= $this->model->getCoa( $params );
		$cnt	= $this->model->getCoa( $params, true );
		
		die(
			json_encode(
				array(
					'success'	=> true
					,'view'		=> $view
					,'total'	=> $cnt
				)
			)
		);
	}

	public function getCOADetails(){
		$params	= getData();
		$ret	= $this->model->getCOADetails( $params );
		die(
			json_encode(
				array(
					'success'	=> true
					,'view'		=> $ret
				)
			)
		);
	}

	public function getCustomer(){
		$params	= getData();
		$view	= $this->model->getCustomer( $params );
		$view = decryptCustomer( $view );
		
		die(
			json_encode(
				array(
					'success'	=> true
					,'view'		=> $view
				)
			)
		);
	}
}

