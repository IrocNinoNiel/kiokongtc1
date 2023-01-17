<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Mark Reynor D. Magriña
 * Module: User Actions Logs
 * Date: Feb 2, 2020
 * Finished: 
 * Description: 
 * DB Tables: 
 * */
class Userlog_model extends CI_Model {
	
	public function viewAll( $data ){
		
		// {name:'datelog',type:'date'}
						// ,{name:'time',type:'time'}
						// ,'affiliateName'
						// ,'fullname'
						// ,'euName'
						// ,'euTypeName'
						// ,'ref'
						// ,'refnum'
						// ,'actionLogDescription'
						
						
		// ,(SELECT reference.code FROM reference as ref WHERE ref.refID = l.ref) as ref
		
		$this->db->select("
			DATE_FORMAT(l.datelog,'%Y-%m-%d') as datelog
			,DATE_FORMAT(l.time,'%h:%i %p') as time
			,employee.name as fullname
			,employee.sk as employeeSK
			,e.username as euName
			,l.actionLogDescription
			,l.referenceNum
			,l.idReference
			,reference.code
			,affiliate.affiliateName
			,affiliate.sk as affiliateSK
			,( CASE e.userType
					WHEN 0 THEN 'System Administrator'
					WHEN 1 THEN 'Administrator'
					WHEN 2 THEN 'Supervisor'
					WHEN 3 THEN 'Employee'
				END 
			) as euTypeName
		");
		
		$this->db->from('logs as l');
		$this->db->join('eu as e','e.idEu = l.idEu AND e.archived NOT IN ( 1 )','LEFT');
		$this->db->join('employee','employee.idEmployee = e.idEmployee','LEFT');
		$this->db->join('affiliate','affiliate.idAffiliate = l.idAffiliate','LEFT');
		$this->db->join('reference','reference.idReference = l.idReference','LEFT');
		// $this->db->join('reference','reference.affiliateID = l.affiliateID','LEFT');
		
		if(isset($data['idAffiliate']) && $data['idAffiliate'] != 0){
			$this->db->where('l.idAffiliate',$data['idAffiliate']);
		}
		if(isset($data['searchBy']) && $data['searchBy'] != 0){
			$this->db->where('l.idEu',$data['searchBy']);
		}
		if(isset($data['edate']) && isset($data['sdate'])){
			$this->db->where(" datelog BETWEEN '$data[sdate]' AND '$data[edate]' ");
		}
		
		$data['db'] = $this->db;
		$data['order_by'] = 'idLog desc';
		return getGridList($data);
	}
	
	
	// [ 'euID' ,'name' ]
	
	public function getUsers( $data ){
        $this->db->select( 'username as name, idEu', false );
		$this->db->where_not_in( 'eu.archived' , 1 );
		$this->db->order_by( 'username' );
		if( isset( $data['query'] ) ){ $this->db->like( 'username', $data['query']); }
        return $this->db->get( 'eu' )->result_array();
    }
	
	function getAffiliate($rawData){
		$this->db->select('idAffiliate,affiliateName');
		$this->db->from('affiliate');
		if( isset( $rawData['query'] ) ){
			$this->db->like('affiliateName', $rawData['query']);
		}
		$this->db->where_not_in('archived',1);
		return $this->db->get()->result_array();
	}
	
	
}