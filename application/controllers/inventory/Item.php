<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Mark Reynor D. Magriña
 * Module: Item Settings
 * Date: Dec 05, 2019
 * Finished: 
 * Description: 
 * DB Tables: 
 * For Password Hashing, please use password_hash( https://www.php.net/manual/en/function.password-hash.php )
 * */
class Item extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->library('encryption');
        setHeader( 'inventory/Item_model' );
    }
		
	public function getAffiliate(){
        $params = getData();
		$view   = $this->model->getAllAffiliate($params);
        die( json_encode( array( 'success' => true ,'view' => $view ) ) );
	}
	
	public function getItemClassification(){
        $params = getData();
		$view   = $this->model->getItemClassification($params);
        die( json_encode( array( 'success' => true ,'view' => $view ) ) );
	}
	
	public function getItemUnit(){
        $params = getData();
		$view   = $this->model->getItemUnit($params);
        die( json_encode( array( 'success' => true ,'view' => $view ) ) );
	}
	
	function getCOADetails(){
		$data = getData();
		$record = $this->model->getCOADetails( $data );
		die( json_encode( array( 'success' => true ,'view' => $record )));
	}

	function getItemListDetails(){
		$data = getData();
		$record = $this->model->getItemListDetails( $data );
		// LQ();
		// die();
		$record['view'] = decryptItem( $record['view'] );
		die( json_encode( array( 'success'=> true, 'total'=> $record['count'], 'view'=> $record['view'] ) ) );
	}

	function getSearchedItems(){
		$data = getData();
		$record = $this->model->getSearchedItems( $data );
		$record = decryptItem( $record );
		die( json_encode( array( 'success' => true ,'view' => $record )));
	}

	public function retrieveData(){
		$rawData = getData();
		$record = $this->model->retrieveData($rawData['idItem']);
		$record = decryptItem( $record ); 		
		die( json_encode( array( 'success'=>true, 'view'=>$record ) ) );
	}

	public function getItemPriceHistoryDetails(){
		$rawData = getData();
		$record = $this->model->getItemPriceHistoryDetails($rawData);
		$record = decryptItem( $record );

		die( json_encode( array( 'success'=>true, 'view'=> $record ) ) );
	}

	public function saveItemForm(){
		$rawData 	= getData();
		$_item 		= $rawData;

		/** Encryption of fields **/
		if( array_key_exists( 'itemName', $rawData ) && !empty( $rawData['itemName'] ) ){

			/**Check if onEdit then retrieve the saved SK instead.**/
			$rawData['sk'] = ( $rawData['onEdit'] == 0 ) ? initializeSalt( $rawData['itemName'] ) : $this->model->retrieveData($rawData['idItem'])[0]['sk'];
			$this->encryption->initialize( array( 'key' => generateSKED( $rawData['sk'] ) ) );

			$rawData['itemName'] = $this->encryption->encrypt( $rawData['itemName'] );
			if( array_key_exists( 'itemPrice', $rawData ) && !empty( $rawData['itemPrice'] ) ) $rawData['itemPrice'] = $this->encryption->encrypt( $rawData['itemPrice'] );
		} else {
			die('Encryption: ITEM NAME IS REQUIRED.');
		}
				
		if( $rawData['onEdit'] == 0 ) {
			$checkDuplicate = $this->model->checkDupliate($rawData['barcode']);		
			if( $checkDuplicate > 0 ) die( json_encode( array('success'=> true, 'match'=>1)));
		}else{
			$checkExist = $this->model->checkExist($rawData['idItem']);		
			if( $checkExist == 0 ) die( json_encode( array('success'=> true, 'match'=>2)));
		}
		
		$this->db->trans_begin();// firstline	
		$idItem	= $this->model->saveItemForm($rawData);	
		$idItemField = array( 'idItem'=> $idItem );
		
		
		/* Prepare affiliate list for batch saving */
		$affiliateList= json_decode($rawData['affiliateList'], true);
		for ( $i=0; $i < count($affiliateList); $i++ ) {
			unset($affiliateList[$i]['id']);
			unset($affiliateList[$i]['affiliateName']);
			unset($affiliateList[$i]['chk']);
			$affiliateList[$i] +=  $idItemField;
		}

		$this->model->saveAffiliateList($affiliateList,$idItem);	
		// echo(', agi ko save affiliate');
		
		/* Set Logs */
		if($_item['onEdit'] == 0){ $actioLog = 'added a new Item, '.$_item['itemName']; }
		else{ $actioLog = 'edited an item details, '.$_item['itemName']; }	
		setLogs( array(
			'actionLogDescription' => $actioLog
			,'idEu' => $this->USERID
			,'idAffiliate' => $this->AFFILIATEID
			,'moduleID' => 16
			,'time' => date("H:i:s A")
		));
		
		if( $this->db->trans_status() === FALSE ){
			$this->db->trans_rollback(); // rollback changes
		}
		else{
			$this->db->trans_commit(); // submit changes
		}
		die( json_encode( array( 'success'=>$this->db->trans_status(), 'match'=>0 ) ) );
	}

	public function saveItemPriceHistory(){
		$rawData = getData();
		$itemPriceHistoryList= json_decode($rawData['itemPriceHistoryList'], true);
		$idItem = array( 'idItem'=> $rawData['idItem'] );

		$_itemRecord = $this->model->retrieveData($rawData['idItem']);

		/**Encrypt itemPrice**/
		foreach( $itemPriceHistoryList as $idx => $item ){
			if( isset(  $_itemRecord[0]['sk'] ) && !empty(  $_itemRecord[0]['sk'] ) ){
				$this->encryption->initialize( array('key' => generateSKED( $_itemRecord[0]['sk'] ) ) );
				$itemPriceHistoryList[$idx]['itemPrice'] = $this->encryption->encrypt( $item['itemPrice'] );
			}
		}
		/**Ends here**/


		for ( $i=0; $i < count($itemPriceHistoryList); $i++ ) {
			$itemPriceHistoryList[$i]['idItem'] =  $rawData['idItem'];
		}
		$this->model->saveItemPriceHistory($itemPriceHistoryList,$rawData['idItem']);	
		
		$this->db->trans_begin();// firstline	
			$this->model->saveItemPriceHistory($itemPriceHistoryList,$rawData['idItem']);
		if( $this->db->trans_status() === FALSE ){
			$this->db->trans_rollback(); // rollback changes
		}
		else{
			$this->db->trans_commit(); // submit changes
		}
		die( json_encode( array( 'success'=>$this->db->trans_status(), 'match'=>0 ) ) );
	}

	public function deleteItemRecord(){
		$rawData = getData();
		$match = 1;
		$checkItemUsed = $this->model->checkItemUsed($rawData['idItem']);
		$checkTransactions = $this->model->checkUsage($rawData['idItem']);
		if( $checkTransactions > 0 || $checkItemUsed >0){ $match = 0; }
		else{
			$this->model->deleteItemRecord( $rawData['idItem'] );
			$match = 1;
		}
		$messageLog = 'deleted an item '.$rawData['itemName'];
		setLogs( array(
			'actionLogDescription' => $messageLog
			,'idEu' => $this->USERID
			,'idAffiliate' => $this->AFFILIATEID
			,'moduleID' => 16
			,'time' => date("H:i:s A")
		));
		die( json_encode( array( 'success'=>true, 'match'=>$match ) ) );
	}
	
	function generateItemSettingPDF(){
		$rawData = getData();
		$record = $this->model->getItemListDetails($rawData);
		
		$header = array(
			array(
				'header'	=> 'Code'
				,'dataIndex'=>'barcode'
				,'width'	=> '10%'
			)
			,array(
				'header'	=> 'Item Name'
				,'dataIndex'=> 'itemName'
				,'width'	=> '42%'
			)
			,array(
				'header'	=> 'Classification'
				,'dataIndex'=> 'className'
				,'width'	=> '10%'
			)
			,array(
				'header'	=> 'Price'
				,'dataIndex'=> 'itemPrice'
				,'width'	=> '10%'
				,'type'		=> 'numbercolumn'
				,'format'	=> '0,000.00'
			)
			,array(
				'header'	=> 'Effectivity Date'
				,'dataIndex'=> 'effectivityDate'
				,'width'	=> '10%'
			)
			,array(
				'header'	=> 'Unit'
				,'dataIndex'=> 'unitName'
				,'width'	=> '10%'
			)
			,array(
				'header'	=> 'Reorder level'
				,'dataIndex'=> 'reorderLevel'
				,'width'	=> '10%'
				,'type'		=> 'numbercolumn'
			)
		);
		
		$array = array(
			'file_name'		=> $rawData['pageTitle']
			,'folder_name'	=> 'inventory'
			,'records'		=> $record['view']
			,'header'		=> $header
			,'orientation'	=> 'L'
		);
		generateTcpdf($array);
	}

	function generateItemSettingExcel(){
		$rawData = getData();
		$sum = 0;
		$record = $this->model->getItemListDetails($rawData);
		$csvarray[] = array( 'title' => $rawData['pageTitle'].'');
		$csvarray[] = array( 'space' => '' );
		$csvarray[] = array( 'space' => '' );
		
		$csvarray[] = array(
			'col1' 	=> 'Code'
			,'col2'	=> 'Item Name'
			,'col3'	=> 'Classification'
			,'col4'	=> 'Price'
			,'col5'	=> 'Effectivity Date'
			,'col6'	=> 'Unit'
			,'col7'	=> 'Reorder level'
		);
		
		foreach( $record['view'] as $value ){
			$csvarray[] = array(
				'col1' 	=> $value['barcode']
				,'col2'	=> $value['itemName']
				,'col3'	=> $value['className']
				,'col4'	=> $value['itemPrice']
				,'col5'	=> $value['effectivityDate']
				,'col6'	=> $value['unitName']
				,'col7'	=> $value['reorderLevel']
			);
		}
		
		$rawData['description'] = ''.$rawData['pageTitle'].": ".$this->USERNAME.' printed a Excel report';
		$rawData['iduser'] = $this->USERID;
		$rawData['usertype'] = $this->USERTYPEID;
		$rawData['printExcel'] = true;
		$rawData['ident'] = null;
		
		writeCsvFile(
			array(
				'csvarray'		=> $csvarray
				,'title'		=> $rawData['pageTitle'].''
				,'directory'	=> 'inventory'
			)
		);
		
	}

	function download($title){
		
		echo 'abot dre kai';
		
		force_download(
			array(
				'title'		=> $title
				,'directory'=> 'inventory'
			)
		);
	}

	function getAffiliates(){
		$params = getData();
		$record = $this->model->getAffiliates( $params );
		$record = decryptAffiliate( $record );

		die(
			json_encode(
				array(
					'success' 	=> true
					,'view'		=> $record 
				)
			)
		);
	}
	
	function dieFunc( $success, $msg, $match ) {
        $view['msg']        = $msg;
        $view['match']      = $match;
        $data['success']    = $success;
        $data['view']       = $view;
        die( json_encode($data) );
    }

	function download_format(){
        $data['title'] = 'Import Items Format';
		$data['directory'] = 'inventory';
		$csvarray[] = array( 
			'col1'  => 'Code',
			'col2'  => 'Name',
			'col3'  => 'Classification',
			'col4'  => 'Item Price',
			'col5'  => 'Effectivity Date',
			'col6'  => 'Item Unit',
			'col7'  => 'Reorder Level'
		);

        writeCsvFile(
            array(
                'csvarray' 	 => $csvarray
                ,'title' 	 => $data['title']
                ,'directory' => $data['directory']
            )
        );

        force_download($data);
    }
	
	function IMPORT() {
        require_once APPPATH.'third_party/phpexcel/PHPExcel.php';
        if(isset($_FILES['file_import_Item'])) {
            $file = $_FILES['file_import_Item'];
			$path = $file['tmp_name'];
			$msg = 'No data!';
            $object = PHPExcel_IOFactory::load($path);            

            foreach($object->getWorksheetIterator() as $worksheet)
            {
                $highestRow = $worksheet->getHighestRow();
				$highestColumn = $worksheet->getHighestColumn();
				$duplicated = array();
				$invalid = array();
				$msg = '';

                if ( 
					$worksheet->getCellByColumnAndRow(0, 1)->getValue() != 'Code' ||
					$worksheet->getCellByColumnAndRow(1, 1)->getValue() != 'Name' ||  
					$worksheet->getCellByColumnAndRow(2, 1)->getValue() != 'Classification' ||
					$worksheet->getCellByColumnAndRow(3, 1)->getValue() != 'Item Price' ||
					$worksheet->getCellByColumnAndRow(4, 1)->getValue() != 'Effectivity Date' ||
					$worksheet->getCellByColumnAndRow(5, 1)->getValue() != 'Item Unit' ||
					$worksheet->getCellByColumnAndRow(6, 1)->getValue() != 'Reorder Level' 
				) $this->dieFunc( true, 'Invalid Format!', 1 );

                for($row=2; $row<=$highestRow; $row++)
                {
					if ( 
						!$worksheet->getCellByColumnAndRow(0, $row)->getValue() || 
						!$worksheet->getCellByColumnAndRow(1, $row)->getValue() || 
						!$worksheet->getCellByColumnAndRow(2, $row)->getValue() || 
						!$worksheet->getCellByColumnAndRow(3, $row)->getValue() || 
						!$worksheet->getCellByColumnAndRow(4, $row)->getValue() || 
						!$worksheet->getCellByColumnAndRow(5, $row)->getValue() || 
						!$worksheet->getCellByColumnAndRow(6, $row)->getValue() 
					) $this->dieFunc( true, 'Incomplete Data!', 1 );

					//Encrypt Item Name
					$data['sk'] = initializeSalt( $worksheet->getCellByColumnAndRow(1, $row)->getValue() );
					$this->encryption->initialize( array( 'key' => generateSKED( $data['sk'] ) ) );
					$data['itemName'] = $this->encryption->encrypt( $worksheet->getCellByColumnAndRow(1, $row)->getValue() );

					$data['itemPrice'] 		= $this->encryption->encrypt( $worksheet->getCellByColumnAndRow(3, $row)->getValue() );
					$data['effectivityDate'] = $this->is_date( $worksheet->getCellByColumnAndRow(4, $row)->getValue() );
					$data['reorderLevel'] 	= $worksheet->getCellByColumnAndRow(6, $row)->getValue();
					
					$data['barcode'] = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
					$checkDuplicate = $this->model->checkDupliate($data['barcode']);	

					if ( $checkDuplicate > 0 )
						$duplicated[] = array( 'code' => $data['barcode'], 'name' => $worksheet->getCellByColumnAndRow(1, $row)->getValue() );
					else if ( !is_numeric( $worksheet->getCellByColumnAndRow(3, $row)->getValue() ) || !is_numeric( $data['reorderLevel'] ) || !$data['effectivityDate'] )
						$invalid[] = array( 'code' => $data['barcode'], 'name' => $worksheet->getCellByColumnAndRow(1, $row)->getValue() );
					else {
						$data['idItemClass'] = $this->getClassID( $worksheet->getCellByColumnAndRow(2, $row)->getValue() );
						$data['idUnit']	= $this->getUnitID( $worksheet->getCellByColumnAndRow(5, $row)->getValue() );
						$this->model->saveItem( $data );
					}
				}

				if ( !empty($duplicated) ) {
					$msg .= 'The following items have duplicate barcode: <br></br>';
					foreach ($duplicated as $d) $msg .= "-> $d[code] - $d[name] <br></br>";
				}
				if ( !empty($invalid) ) {
					$msg .= '<br></br> The following items have invalid inputs: <br></br>';
					foreach ($invalid as $i) $msg .= "-> $i[code] - $i[name] <br></br>";
				}
				if ( $msg == '' ) $msg = 'SAVE_SUCCESS';
				

                $this->dieFunc( true, $msg, 1 );
            }
        } 
	}
	
	function getClassID( $className )
	{
		$class = $this->model->getClassID( $className );
		if( empty($class) ) $classID = $this->model->saveClass( $className );
		else $classID = $class['idItemClass'];
		return $classID;
	}

	function getUnitID( $unitName )
	{
		$unit = $this->model->getUnitID( $unitName );
		if( empty($unit) ) $unitID = $this->model->saveUnit( $unitName );
		else $unitID = $unit['idUnit'];
		return $unitID;
	}

	function is_date($val)
	{
		$count = 0;
		$array = explode("/", $val);
		if ( $array[0] < 1 && $array[0] > 12 ) $count++;
		if ( $array[1] < 1 && $array[1] > 31 ) $count++;
		if ( $array[2] < 1000 && $array[1] > date('Y') ) $count++;
		return $count>0?false:$array[2].'/'.$array[0].'/'.$array[1];
	}

	function phpinfo() {
		var_dump(phpinfo());
	}
}