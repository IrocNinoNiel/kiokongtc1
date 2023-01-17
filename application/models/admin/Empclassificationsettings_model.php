<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Mark Reynor Magriña
 * Module: Employee Classification Settings
 * Date: October 31, 2019
 * Finished: 
 *  Description: Classification for Employees
 * */ 
class Empclassificationsettings_model extends CI_Model{
	
	function checkDupliate($params){		
		$this->db->select('empClassName');
		$this->db->where( array( 'empClassName' => $params['empClassName'], 'archived' => 0 ));
		if( isset( $params['idEmpClass'] ) ) $this->db->where_not_in( 'idEmpClass', $params['idEmpClass']);
		$this->db->from('employeeclass');
		return $this->db->count_all_results();		
	}
	function checkExist($idEmpClass){		
		$this->db->select('idEmpClass');
		$this->db->where('idEmpClass',$idEmpClass);
		$this->db->where('archived',0);
		$this->db->from('employeeclass');
		return $this->db->count_all_results();		
	}
	function checkIfUsed($idEmpClass){
		$this->db->select('classification');
		$this->db->from('employment');
		$this->db->where('classification',$idEmpClass);
		return $this->db->count_all_results();
	}
	function saveEmployeeClassificationName($rawData){
		$onEdit = (int)$rawData['onEdit'];
		$id = (int)$rawData['idEmpClass'];
		if( $onEdit == 0 ){
			// die('new data');
			$this->db->insert('employeeclass',unsetParams( $rawData, 'employeeclass' ));		
		}else{
			// die('edited ni, mao ni id: '.$id);
			unset($rawData['idEmpClass']);
			$this->db->where('idEmpClass',$id);
			$this->db->update('employeeclass', unsetParams( $rawData, 'employeeclass' ));
		}
	}	
	function retrieveEmployeeClassificationDetails($data,$pdf){
		$this->db->select('idEmpClass,empClassName');
		$this->db->where_not_in('archived', 1);
		$this->db->from('employeeclass');
		$data['db'] = $this->db;
		$data['order_by'] = 'empClassName';
		return getGridList($data);
	}	
	function editEmployeeClassificationDetails($empID) {
		$this->db->Select('*');
		$this->db->where('idEmpClass',$empID);
		return $this->db->get('employeeclass')->result_array();
	}
	function deleteEmployeeClassificationDetails( $idEmpClass ){
        $match = 0;
		$this->db->select('archived');
        $this->db->where( 'idEmpClass', $idEmpClass );
        $archived = $this->db->get('employeeclass')->result_array()[0]['archived'];

        if( (int)$archived == 0 ) {
            /* SOFT DELETE ONLY */
            $this->db->set('archived', 1, false );
            $this->db->where('idEmpClass', $idEmpClass );
            $this->db->update('employeeclass');
        } else { $match = 2; }
        return $match;
    }
	
}