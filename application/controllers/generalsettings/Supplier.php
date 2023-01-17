<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Mark Reynor D. Magriña
 * Module: Supplier Settings
 * Date: Dec 03, 2019
 * Finished: 
 * Description: 
 * DB Tables: 
 * For Password Hashing, please use password_hash( https://www.php.net/manual/en/function.password-hash.php )
 * */
class Supplier extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->library('encryption');
        setHeader( 'generalsettings/Supplier_model' );
    }
		
	public function getAffiliate(){
        $params = getData();
		$view   = $this->model->getAllAffiliate($params);
		$view	= decryptAffiliate( $view );
        die( json_encode( array( 'success' => true ,'view' => $view ) ) );
	}
	
	function getCOADetails(){
		$data = getData();
		$record = $this->model->getCOADetails( $data );
		die( json_encode( array( 'success' => true ,'view' => $record )));
	}

	function getSupplierList(){
		$rawData = getData();
		$record = $this->model->getSupplierList($rawData,false);
		$record = decryptSupplier( $record );

		die( json_encode( array( 'success'=>true, 'view'=> $record, 'total'=> count($record) ) ) );
	}

	function getSupplierItems(){
		$rawData = getData();
		$record = $this->model->getSupplierItems($rawData['idSupplier']);
		$record = decryptItem( $record );

		die( json_encode( array( 'success'=>true, 'view'=>$record ) ) );
	}

	function getSearchItemDetails(){
		$rawData = getData();
		$record = $this->model->getSearchItemDetails($rawData);
		$record = decryptItem( $record );

		die( json_encode( array( 'success'=> true, 'view'=>$record ) ) );
	}

	public function saveSupplierForm(){
		$rawData = getData();

		/**Encryption of Fields**/
		if( array_key_exists( 'name', $rawData ) && !empty( $rawData['name'] ) ){
			$rawData['sk'] = initializeSalt( $rawData['name'] );
			$this->encryption->initialize( array('key' => generateSKED( $rawData['sk'] ) ) );
			$rawData['name'] = $this->encryption->encrypt( $rawData['name'] );
			if( array_key_exists('email', $rawData) && !empty( $rawData['email'] ) ) $rawData['email'] = $this->encryption->encrypt( $rawData['email'] );
			if( array_key_exists('contactNumber', $rawData) && !empty( $rawData['contactNumber'] ) ) $rawData['contactNumber'] = $this->encryption->encrypt( $rawData['contactNumber'] );
			if( array_key_exists('address', $rawData) && !empty( $rawData['address'] ) ) $rawData['address'] = $this->encryption->encrypt( $rawData['address'] );
			if( array_key_exists('tin', $rawData) && !empty( $rawData['tin'] ) ) $rawData['tin'] = $this->encryption->encrypt( $rawData['tin'] );
			
		} else {
			die('Encryption: SUPPLIER NAME IS REQUIRED.');
		}
		
		if( $rawData['onEdit'] == 0 ) {
			$checkDuplicate = $this->model->checkDupliate($rawData['name']);		
			if( $checkDuplicate > 0 ) die( json_encode( array('success'=> true, 'match'=>1)));
		}else{
			$checkExist = $this->model->checkExist($rawData['idSupplier']);		
			if( $checkExist == 0 ) die( json_encode( array('success'=> true, 'match'=>2)));
		}
		
		$this->db->trans_begin();// firstline	
		$idSupplier=$this->model->saveSupplierForm($rawData);			
		if( $rawData['onEdit'] == 0 ) { $idSupplier = $idSupplier;}
		else{ $idSupplier = (int)$rawData['idSupplier']; }
		$idSupplierField = array( 'idSupplier'=> $idSupplier );
		
		/* Prepare affiliate list for batch saving */
		$affiliateList= json_decode($rawData['affiliateList'], true);
		for ( $i=0; $i < count($affiliateList); $i++ ) {
			unset($affiliateList[$i]['affiliateName']);
			unset($affiliateList[$i]['chk']);
			$affiliateList[$i] +=  $idSupplierField;
		}
		$this->model->saveAffiliateList($affiliateList,$idSupplier);	
		// echo(', agi ko save affiliate');
		
		/* Prepare item list for batch saving */
		$itemList= json_decode($rawData['itemList'], true);
		$countedItemArray = array_filter($itemList);
		if ( count ($countedItemArray) != 0 ){
			for ( $i=0; $i < count($itemList); $i++ ) {
				unset($itemList[$i]['idItemClass']);
				unset($itemList[$i]['itemName']);
				unset($itemList[$i]['itemClassification']);
				unset($itemList[$i]['selected']);
				unset($itemList[$i]['className']);
				unset($itemList[$i]['barcode']);
				$itemList[$i] +=  $idSupplierField;
			}
			$this->model->saveItemList($itemList,$idSupplier);	
		}
		// echo(', agi ko save items');
		
		/** DECRYPT HERE FOR LOGS!**/
		$rawData = decryptSupplier( array(0 => $rawData) )[0];
		// $rawData = decryptSupplier( $rawData );
		/* Set Logs */
		if($rawData['onEdit'] == 0){ $actioLog = 'Added a new supplier, '.$rawData['name']; }
		else{ $actioLog = 'Edited the supplier details of, '.$rawData['name']; }	
		setLogs( array(
			'actionLogDescription' 	=> $actioLog
			,'idEu' 				=> $this->USERID
			,'moduleID' 			=> 12
			,'idAffiliate' 			=> $this->AFFILIATEID
			,'time' 				=> date("H:i:s A")
		));
		
		if( $this->db->trans_status() === FALSE ){
			$this->db->trans_rollback(); // rollback changes
		}
		else{
			$this->db->trans_commit(); // submit changes
		}
		die( json_encode( array( 'success'=>$this->db->trans_status(), 'match'=>0 ) ) );
	}
	
	public function retrieveData(){
		$rawData = getData();
		$record = $this->model->retrieveData($rawData['idSupplier']); 
		$record = decryptSupplier( $record );
		
		die( json_encode( array( 'success'=>true, 'view'=>$record ) ) );
	}
	
	public function deleteSupplierRecord(){
		$rawData = getData();
		
		$checkIfUsed = $this->model->checkIfUsed( $rawData['idSupplier'] );
		if( $checkIfUsed > 0 ) die( json_encode( array( 'match'=> 3 ) ) );
		$match = $this->model->deleteSupplierRecord($rawData);
		$messageLog = 'Deleted the supplier '.$rawData['supplierName'];
		setLogs( array(
			'actionLogDescription' 	=> $messageLog
			,'idEu' 				=> $this->USERID
			,'moduleID' 			=> 12
			,'idAffiliate' 			=> $this->AFFILIATEID
			,'time' 				=> date("H:i:s A")
		));
		die( json_encode( array( 'success'=>true, 'match'=>$match ) ) );
	}
	

}