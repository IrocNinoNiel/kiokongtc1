<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Hazel Alegbeleye  
 * Module: Receiving Summary
 * Date: January 17, 2020
 * Finished: 
 * Description: 
 * DB Tables: 
 * */
class Receivingsummary_model extends CI_Model {

    function getReceivingList( $params ) {
        $query = array();

        if( isset( $params['Affiliate'] ) && $params['Affiliate'] != 0 ) $query['invoices.idAffiliate'] = $params['Affiliate'];
        if( isset( $params['idCostCenter'] ) && $params['idCostCenter'] != 0 ) $query['invoices.idCostCenter'] = $params['idCostCenter'];
        if( isset( $params['referenceNum'] ) && $params['referenceNum'] != 0 ) $query['invoices.idReference'] = $params['referenceNum'];
        if( isset( $params['idSupplier'] ) && $params['idSupplier'] != 0 ) $query['invoices.pCode'] = $params['idSupplier'];
        if( isset( $params['idItemClass'] ) && $params['idItemClass'] != 0 ) $query['item.idItemClass'] = $params['idItemClass'];

        $this->db->select("
                            affiliate.affiliateName,
                            affiliate.sk as affiliateSK,
                            costcenter.costCenterName,
                            costcenter.sk as costCenterSK,
                            convert(invoices.date, date) as date,
                            CONCAT(reference.code,
                                    '-',
                                    invoices.referenceNum) AS referenceNumber,
                            supplier.name as supplierName,
                            supplier.sk as supplierSK,
                            item.barcode,
                            item.itemName,
                            item.sk as itemSK,
                            itemclassification.className,
                            receiving.cost,
                            receiving.qty,
                            (receiving.cost * receiving.qty) AS total");
        $this->db->where( $query );

        if( isset( $params['sdate']) && isset( $params['edate']) && isset( $params['timefrom']) && isset( $params['timeto']) ) {
            $this->db->where('convert(invoices.date, datetime) >=', $params['sdate'] . ' ' . $params['timefrom']);
            $this->db->where('convert(invoices.date, datetime) <=', $params['edate'] . ' ' . $params['timeto']);
        }

        $this->db->where("invoices.idAffiliate IN (SELECT idAffiliate FROM employeeaffiliate WHERE idEmployee = {$this->session->userdata('EMPLOYEEID')})", NULL, FALSE);
        
        $this->db->join('invoices', 'invoices.idInvoice = receiving.idInvoice and invoices.archived = 0', 'LEFT');
        $this->db->join('affiliate', 'affiliate.idAffiliate = invoices.idAffiliate and affiliate.archived = 0', 'LEFT');
        $this->db->join('costcenter', 'costcenter.idCostCenter = invoices.idCostCenter and costcenter.archived = 0', 'LEFT');
        $this->db->join('reference', 'reference.idReference = invoices.idReference and reference.archived = 0', 'LEFT');
        $this->db->join('supplier', 'supplier.idSupplier = invoices.pCode and supplier.archived = 0', 'LEFT');
        $this->db->join('item', 'item.idItem = receiving.idItem and item.archived = 0' , 'LEFT');
        $this->db->join('itemclassification', 'itemclassification.idItemClass = item.idItemClass', 'LEFT');

        $this->db->order_by('invoices.date desc');
        return $this->db->get('receiving')->result_array();
    }

    function getItems( $params ){
        $this->db->distinct();
        $this->db->select('item.idItem as id, item.itemName as name');
        if( isset($params['idAffiliate']) && $params['idAffiliate'] != 0 ) $this->db->where('idAffiliate', $params['idAffiliate']);
        $this->db->join('item', 'item.idItem = itemaffiliate.idItem', 'LEFT');

        return $this->db->get('itemaffiliate')->result_array();
    }

    function getPO( $params ) {
        $query = array();
        if( isset( $params['idAffiliate'] ) && $params['idAffiliate'] != 0 ) $query['invoices.idAffiliate'] = $params['idAffiliate'];
        if( isset( $params['idCostCenter'] ) && $params['idCostCenter'] != 0 ) $query['invoices.idCostCenter'] = $params['idCostCenter'];
        if( isset( $params['referenceNum'] ) && $params['referenceNum'] != 0 ) $query['invoices.idReference'] = $params['referenceNum'];
        if( isset( $params['idSupplier'] ) && $params['idSupplier'] != 0 ) $query['invoices.pCode'] = $params['idSupplier'];

        $this->db->select("reference.name as name, reference.idReference as id");
        $this->db->where( $query );

        if( isset( $params['sdate']) && isset( $params['edate']) && isset( $params['timefrom']) && isset( $params['timeto']) ) {
            $this->db->where('convert(invoices.date, datetime) >=', $params['sdate'] . $params['timefrom']);
            $this->db->where('convert(invoices.date, datetime) <=', $params['edate'] . $params['timeto']);
        }

        $this->db->where("invoices.idAffiliate IN (SELECT idAffiliate FROM employeeaffiliate WHERE idEmployee = {$this->session->userdata('EMPLOYEEID')})", NULL, FALSE);
        
        $this->db->join('invoices', 'invoices.idInvoice = receiving.idInvoice and invoices.archived = 0', 'LEFT');
        $this->db->join('affiliate', 'affiliate.idAffiliate = invoices.idAffiliate and affiliate.archived = 0', 'LEFT');
        $this->db->join('costcenter', 'costcenter.idCostCenter = invoices.idCostCenter and costcenter.archived = 0', 'LEFT');
        $this->db->join('reference', 'reference.idReference = invoices.idReference and reference.archived = 0', 'LEFT');
        $this->db->join('supplier', 'supplier.idSupplier = invoices.pCode and supplier.archived = 0', 'LEFT');
        $this->db->join('item', 'item.idItem = receiving.idItem and item.archived = 0' , 'LEFT');
        $this->db->join('itemclassification', 'itemclassification.idItemClass = item.idItemClass', 'LEFT');

        $this->db->order_by('invoices.date desc, invoices.referenceNum asc');
        return $this->db->get('receiving')->result_array();
    }

    function getItemClassifications(){
        $this->db->select('idItemClass as id, className as name');
        return $this->db->get('itemclassification')->result_array();
    }
}