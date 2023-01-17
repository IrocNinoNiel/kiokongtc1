<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Developer: Christian P. Daohog
 * Module: Logsheet Monitoring
 * Date: Dec 29, 2021
 * Finished: 
 * Description: This module allows the authorized user to generate a Monitoring of drivers activity
 * DB Tables: 
 * */ 

class Truckinglogs_model extends CI_Model {

    public function getHistory($params) {

		$this->db->select("
            DATE_FORMAT(l.datelog,'%Y-%m-%d') as datelog
            ,DATE_FORMAT(l.time,'%h:%i %p') as time
            ,employee.name AS fullName, employee.sk,
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
                END ) as euTypeName ");
    
        $this->db->from('logs as l');
        $this->db->join('eu as e','e.idEu = l.idEu AND e.archived NOT IN ( 1 )','LEFT');
        $this->db->join('employee','employee.idEmployee = e.idEmployee','LEFT');
        $this->db->join('affiliate','affiliate.idAffiliate = l.idAffiliate','LEFT');
        $this->db->join('reference','reference.idReference = l.idReference','LEFT');
        
        if(isset($params['idAffiliate']) && $params['idAffiliate'] != 0) $this->db->where('l.idAffiliate',$params['idAffiliate']);

        if(isset($params['edate']) && isset($params['sdate'])) $this->db->where(" datelog BETWEEN '$params[sdate]' AND '$params[edate]' ");
        
        $this->db->where_in('l.idModule', $params['idModules']);

        $this->db->order_by( 'idLog', 'desc' );
        
        return $this->db->get()->result_array();
    }

    public function getIdTruckModules() {
        $this->db->select('idModule');
        $this->db->where('moduleType', 2);
        return $this->db->get('module')->result_array();
    }

	public function getUsers( $params ){
        $this->db->select( 'username as name, idEu as id', false );
		$this->db->where_not_in( 'eu.archived' , 1 );
		$this->db->order_by( 'username' );

		if( isset( $params['username'] ) ){ $this->db->like( 'username', $params['username']); }

        return $this->db->get( 'eu' )->result_array();
    }
}