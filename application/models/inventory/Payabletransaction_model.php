<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Mark Reynor D. Magriña
 * Module: Payable Transaction
 * Date: Feb 18, 2020
 * Finished: 
 * Description: 
 * DB Tables: 
 * */
class Payabletransaction_model extends CI_Model {
	public function recordDetails($rawData){
		$this->db->select("
			,affiliate.sk	AS affiliateSK
			,supplier.sk	AS supplierSK
			,affiliate.affiliateName
			,invoices.idInvoice
			,invoices.idModule
			,invoices.date, invoices.dueDate as duedate,invoices.amount,invoices.balLeft as balance
			,CONCAT(reference.code,'-',invoices.referenceNum) as reference
			,supplier.name as supplier
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
		$this->db->order_by('invoices.date','DESC');
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