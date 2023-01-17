<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Mark Reynor D. Magriña
 * Module: Payable Schedule
 * Date: Feb 18, 2020
 * Finished: 
 * Description: 
 * DB Tables: 
 * */
class Payableschedule_model extends CI_Model {
	/* 
	public function viewAll( $data ){
		
		$this->db->select("
			DATE_FORMAT(l.datelog,'%m/%d/%Y') as datelog
			,DATE_FORMAT(l.time,'%h:%i %p') as time
			,e.fullname
			,e.euName
			,l.actionLogDescription
			,l.refnum
			,(SELECT ref.code FROM reference as ref WHERE ref.refID = l.ref) as ref
			,a.affiliateName
			,( 	CASE e.euType
					WHEN 0 THEN 'System Administrator'
					WHEN 1 THEN 'Administrator'
					WHEN 2 THEN 'Supervisor'
					WHEN 3 THEN 'Staff'
				END 
			) as euTypeName
		");
		
		$this->db->from('logs as l');
		$this->db->join('eu as e','e.euID = l.euID','LEFT');
		$this->db->join('affiliate as a','a.affiliateID = l.affiliateID','LEFT');
		
		if(isset($data['affiliateID']) && $data['affiliateID'] != -1){
			$this->db->where('l.affiliateID',$data['affiliateID']);
		}
		if(isset($data['searchBy']) && $data['searchBy'] != -1){
			$this->db->where('l.euID',$data['searchBy']);
		}
		if(isset($data['edate']) && isset($data['sdate'])){
			$this->db->where(" datelog BETWEEN '$data[sdate]' AND '$data[edate]' ");
		}
		
		$data['db'] = $this->db;
		$data['order_by'] = 'logID desc';
		return getGridList($data);
	}
	 */
	
	public function recordDetails($rawData){
		$this->db->select("
			affiliate.affiliateName, affiliate.sk as affiliateSK,
			,invoices.date, invoices.dueDate as duedate,invoices.amount,invoices.balLeft as balance
			,CONCAT(reference.code,'-',invoices.referenceNum) as reference
			,invoices.idInvoice , invoices.idModule
			,supplier.name as supplier, supplier.sk as supplierSK
		");
		$this->db->from('invoices');
		$this->db->join('affiliate','affiliate.idAffiliate = invoices.idAffiliate','left outer');
		$this->db->join('reference','reference.idReference = invoices.idReference','left outer');
		$this->db->join('supplier','supplier.idSupplier = invoices.pCode','left outer');

		if ( isset($rawData['idAffiliate']) && $rawData['idAffiliate'] != 0 ){ 
			$this->db->where('invoices.idAffiliate', (int)$rawData['idAffiliate'] );
		}
		
		if ( isset($rawData['supplierCmb']) && $rawData['supplierCmb'] != 0 ){ 
			$this->db->where('invoices.pCode', (int)$rawData['supplierCmb'] );
		}
		$this->db->where('convert(invoices.date, date) >=',$rawData['sdate']);
		$this->db->where('convert(invoices.date, date) <=',$rawData['edate']);
		$this->db->where('invoices.idModule',25);
		$this->db->where_not_in('invoices.archived',1);
		$this->db->where_not_in('invoices.cancelTag',1);
		return $this->db->get()->result_array();
	}
	
	
	// function getSupplier($rawData){
		// $this->db->distinct();
		// $this->db->select('supplier.idSupplier as id, supplier.name');
		// $this->db->from('supplier');
		// $this->db->order_by('name asc');
		// $this->db->join('supplieraffiliate','supplieraffiliate.idSupplier = supplier.idSupplier','left outer');
		// return $this->db->get()->result_array();
	// }
	
	
	
}