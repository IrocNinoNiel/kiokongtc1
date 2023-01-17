<?php if( ! defined( 'BASEPATH' )) exit( 'No direct script access allowed');
/**
 * Developer: Mark Reynor Magriña
 * Module: Employee Classification Settings
 * Date: October 31, 2019
 * Finished: 
 *  Description: Classification for Employees
 * */ 

class Empclassificationsettings extends CI_Controller {

    public function __construct(){
		parent::__construct();
    setHeader( 'admin/Empclassificationsettings_model','empClassModel' );
    }	
	
	function saveEmployeeClassificationName(){
		$actioLog = '';
		$rawData = getData();		

		/* Validation */
		if( $rawData['onEdit'] == 0 ) {
			$checkDuplicate = $this->empClassModel->checkDupliate( array( 'empClassName' => $rawData['empClassName']) );		
			if( $checkDuplicate > 0 ) die( json_encode( array('success'=> true, 'match'=>1)));
		}else{
			// $checkExist = $this->empClassModel->checkExist($rawData['idEmpClass']);		
			$checkExist	= $this->empClassModel->checkDupliate( $rawData );
			if( $checkExist > 0 ) die( json_encode( array('success'=> true, 'match'=>1)));
		}		
		
		$this->db->trans_begin();// firstline
			$this->empClassModel->saveEmployeeClassificationName($rawData);		
		if( $this->db->trans_status() === FALSE ){
			$this->db->trans_rollback(); // rollback changes
			//$success = false;
		}
		else{
			$this->db->trans_commit(); // submit changes
			//$success = true;
		}
		/* Set Logs */
		if($rawData['onEdit'] == 0){ $actioLog = 'Added new classification, '.$rawData['empClassName']; }
		else{ $actioLog = 'Modified the classification, '.$rawData['empClassName']; }		
		setLogs( array(
			'actionLogDescription' => $actioLog
			,'idEu' => $this->USERID
			,'moduleID' => 5
			,'time' => date("H:i:s A")
		));
		die( json_encode( array('success'=> $this->db->trans_status(), 'match'=>0 )));		
	}
	
	public function retrieveEmployeeClassificationDetails(){
		$data = getData();
		$result = $this->empClassModel->retrieveEmployeeClassificationDetails($data,false);
		print json_encode( array( 'success' => true, 'total' => $result['count'], 'view'=>$result['view'] ));
	}
	
	function editEmployeeClassificationDetails(){
		$data = getData();
		$empID = $this->input->post('idEmpClass');
		$empDetails = $this->model->editEmployeeClassificationDetails($empID);
		echo json_encode( array( 'success' => true, 'match' =>1, 'view' => $empDetails ));
	}
	
	function deleteEmployeeClassificationDetails() {
		$data = getData();
		
		$checkIfUsed = $this->model->checkIfUsed( $data['idEmpClass'] );
		if( $checkIfUsed > 0 ) die( json_encode( array( 'match'=> 3 ) ) );
		$match = $this->empClassModel->deleteEmployeeClassificationDetails($data['idEmpClass']);
		setLogs( array(
			'actionLogDescription' => 'Deleted the classificatin, '.$data['empClassName']
			,'idEu' => $this->USERID
			,'moduleID' => 5
			,'time' => date("H:i:s A")
		));
		die( json_encode( array( 'match'=> $match ) ) );
	}
}