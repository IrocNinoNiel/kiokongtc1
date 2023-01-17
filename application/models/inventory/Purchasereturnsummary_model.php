<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Hazel Alegbeleye  
 * Module: releasing Summary
 * Date: January 17, 2020
 * Finished: 
 * Description: 
 * DB Tables: 
 * */
class Purchasereturnsummary_model extends CI_Model {

    function getPurchaseReturns(){
        $this->db->distinct();
        $this->db->select("reference.idReference as id, reference.code as name");
        $this->db->where( array("reference.archived" => 0, "reference.idModule" => 29 ));
        $this->db->where("invoices.idAffiliate IN (SELECT idAffiliate FROM employeeaffiliate WHERE idEmployee = {$this->session->userdata('EMPLOYEEID')})", NULL, FALSE);
        
        if( isset( $params['idAffiliate']) ) $this->db->where('invoices.idAffiliate', $params['idAffiliate']);
        if( isset( $params['idCostCenter']) ) $this->db->where('invoices.idCostCenter', $params['idCostCenter'] );

        $this->db->join('invoices', 'invoices.idReference = reference.idReference', 'INNER');

        return $this->db->get('reference')->result_array();
    }

    function getItemClassifications(){
        $this->db->select('idItemClass as id, className as name');
        return $this->db->get('itemclassification')->result_array();
    }

    function getItems( $params ){
        $this->db->distinct();
        $this->db->select('item.idItem as id, item.itemName as name, item.sk');
        if( isset($params['idAffiliate']) && $params['idAffiliate'] != 0 ) $this->db->where('idAffiliate', $params['idAffiliate']);
        $this->db->join('item', 'item.idItem = itemaffiliate.idItem', 'LEFT');

        return $this->db->get('itemaffiliate')->result_array();
    }

    function getReturnList( $params ) {
        $query = array();

        $query['invoices.idModule'] = 29;
        if( isset( $params['Affiliate'] ) && $params['Affiliate'] != 0 ) $query['invoices.idAffiliate'] = $params['Affiliate'];
        //if( isset( $params['idCostCenter'] ) && $params['idCostCenter'] != 0 ) $query['invoices.idCostCenter'] = $params['idCostCenter'];
        if( isset( $params['referenceNum'] ) && $params['referenceNum'] != 0 ) $query['invoices.idReference'] = $params['referenceNum'];
        if( isset( $params['idSupplier'] ) && $params['idSupplier'] != 0 ) $query['invoices.pCode'] = $params['idSupplier'];
        if( isset( $params['idItemClass'] ) && $params['idItemClass'] != 0 ) $query['item.idItemClass'] = $params['idItemClass'];
        if( isset( $params['idItem'] ) && $params['idItem'] != 0 ) $query['releasing.idItem'] = $params['idItem'];

        $this->db->select("
                            affiliate.affiliateName,
                            affiliate.sk as affiliateSK,
                            convert(invoices.date, date) as date,
                            CONCAT(reference.code,
                                    '-',
                                    invoices.referenceNum) AS referenceNum,
                            supplier.name as supplierName,
                            supplier.sk as supplierSK,
                            item.barcode,
                            item.itemName,
                            item.sk as itemSK,
                            itemclassification.className,
                            releasing.cost,
                            releasing.qty,
                            (releasing.cost * releasing.qty) AS amount");
        $this->db->where( $query );

        if( isset( $params['sdate']) && isset( $params['edate']) && isset( $params['timefrom']) && isset( $params['timeto']) ) {
            $this->db->where('convert(invoices.date, datetime) >=', $params['sdate'] . ' ' . $params['timefrom']);
            $this->db->where('convert(invoices.date, datetime) <=', $params['edate'] . ' ' . $params['timeto']);
        }

        $this->db->join('affiliate', 'invoices.idAffiliate = affiliate.idAffiliate', 'LEFT');
        // $this->db->join('costcenter', 'invoices.idCostCenter = costcenter.idCostCenter', 'LEFT');
        $this->db->join('reference', 'invoices.idReference = reference.idReference', 'LEFT');
        $this->db->join('supplier', 'invoices.pCode = supplier.idSupplier', 'LEFT');
        $this->db->join('releasing', 'releasing.idInvoice = invoices.idInvoice', 'LEFT');
        $this->db->join('item', 'releasing.idItem = item.idItem', 'LEFT');
        $this->db->join('itemclassification', 'item.idItemClass = itemclassification.idItemClass', 'LEFT');

        $this->db->where( array(
            'invoices.archived' => 0
            ,'invoices.cancelTag' => 0
            ,'releasing.qty >' => 0 
            )
        );
        $this->db->where("invoices.idAffiliate IN (SELECT idAffiliate FROM employeeaffiliate WHERE idEmployee = {$this->session->userdata('EMPLOYEEID')})", NULL, FALSE);

        $this->db->order_by('invoices.date desc, invoices.referenceNum asc');
        return $this->db->get('invoices')->result_array();
    }
}