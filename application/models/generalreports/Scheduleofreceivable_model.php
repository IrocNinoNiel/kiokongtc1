<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Hazel Alegbeleye  
 * Module: Schedule of Receivable
 * Date: March 2, 2020
 * Finished: 
 * Description: 
 * DB Tables: 
 * */
class Scheduleofreceivable_model extends CI_Model {
    function getCustomers($params){
        $this->db->select("customer.idCustomer as id, customer.name, customer.sk");
        $this->db->where("customer.archived", 0);
        if( isset( $params['affiliates'] ) ) $this->db->where_in('idAffiliate', json_decode($params['affiliates'],true));

        $this->db->join('customer', 'customer.idCustomer = customeraffiliate.idCustomer', 'LEFT');
        $this->db->group_by('customer.idCustomer');

        return $this->db->get("customeraffiliate")->result_array();
    }

    function getReceivable($params){
        $this->db->select("
                            idInvoice, 
                            concat(reference.code, '-', referenceNum) as referenceNum, 
                            date as transactionDate, 
                            dueDate,  
                            customer.name as customerName, 
                            pCode, 
                            description, 
                            amount, 
                            bal as balance,
                            customer.sk,
                            affiliate.affiliateName,
                            affiliate.sk as affiliateSK");

        $this->db->where( array("pType" => 1, "invoices.archived" => 0, 'cancelTag' => 0) );
        $this->db->join("reference", "reference.idReference = invoices.idReference", "left");
        $this->db->join("customer", "customer.idCustomer = invoices.pCode", "inner");
        $this->db->join('affiliate', 'affiliate.idAffiliate = invoices.idAffiliate', 'left');

        if( isset($params['idAffiliate']) && $params['idAffiliate'] > 0 ) $this->db->where('invoices.idAffiliate', $params['idAffiliate']);

        if( isset( $params['sdate']) && isset( $params['edate']) ) {
            $this->db->where('convert(invoices.dueDate, date) >=', $params['sdate']);
            $this->db->where('convert(invoices.dueDate, date) <=', $params['edate']);
        }

        return $this->db->get("invoices")->result_array();
    }
}