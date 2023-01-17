<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Jays
 * Module       : Itemized Profit Loss
 * Date         : Feb. 17, 2020
 * Finished     : 
 * Description  : This module allows the authorized user to generate and print the per-item
 *                transactions and identify the profit or loss of every project.
 * DB Tables    : 
 * */ 
class Itemizedprofitloss_model extends CI_Model {

    public function getItems( $params ){
        $this->db->select( 'idItem as id, itemName as name, sk' );
        if( isset( $params['query'] ) ) $this->db->like( 'itemName', $params['query'], 'both' );
        $this->db->where_not_in( 'archived', 1 );
        $this->db->order_by( 'itemName', 'asc' );
        return $this->db->get( 'item' )->result_array();
    }

    public function getItemizedProfitLossReport( $params ){
        $this->db->select( "
            affiliate.affiliateName
            ,invoices.date
            ,CONCAT( reference.code, ' - ', invoices.referenceNum ) as code
            ,customer.name as name
            ,item.barcode
            ,item.itemName
            ,unit.unitName
            ,releasing.qty
            ,receiving.cost
            ,releasing.price
            ,( IFNULL( releasing.qty, 0 ) * IFNULL( receiving.cost, 0 ) ) as costAmount
            ,( IFNULL( releasing.qty, 0 ) * IFNULL( releasing.price, 0 ) ) as priceAmount
            ,( ( IFNULL( releasing.qty, 0 ) * IFNULL( releasing.price, 0 ) ) - ( IFNULL( releasing.qty, 0 ) * IFNULL( receiving.cost, 0 ) ) ) as profitLoss
            ,invoices.idModule
            ,affiliate.sk AS affSK
            ,customer.sk AS custSK
            ,item.sk AS itemSK
        " );
        $this->db->from( 'releasing' );
        $this->db->join( 'invoices', 'invoices.idInvoice = releasing.idInvoice' );
        $this->db->join( 'receiving', 'receiving.idReceiving = releasing.fident', 'left outer' );
        $this->db->join( 'reference', 'reference.idReference = invoices.idReference', 'left outer' );
        $this->db->join( 'item', 'item.idItem = releasing.idItem', 'left outer' );
        $this->db->join( 'unit', 'unit.idUnit = item.idUnit', 'left outer' );
        $this->db->join( 'affiliate', 'affiliate.idAffiliate = invoices.idAffiliate', 'left outer' );
        $this->db->join( 'customer', 'customer.idCustomer = invoices.pCode', 'left outer' );
        $this->db->where( 'invoices.idModule', 18 );
        $this->db->where( 'invoices.status', 2 );
        $this->db->where( 'invoices.pType', 1 );
        $this->db->where_not_in( 'invoices.archived', 1 );
        $this->db->where_not_in( 'invoices.cancelTag', 1 );
        if( isset( $params['idAffiliate'] ) ){
            if( (int)$params['idAffiliate'] > 0 ) $this->db->where( 'invoices.idAffiliate', (int)$params['idAffiliate'] );
        }
        if( isset( $params['idItem'] ) ){
            if( (int)$params['idItem'] > 0 ) $this->db->where( 'item.idItem', (int)$params['idItem'] );
        }
        if( isset( $params['sdate'] ) && isset( $params['edate'] ) ){
            $this->db->where( "( invoices.date BETWEEN '$params[sdate] $params[stime]' AND '$params[edate] $params[etime]' )" );
        }
        
        $params['db'] = $this->db;
        $params['order_by'] = 'affiliate.affiliateName ASC';

        $rec = getGridList($params);
        return $rec['view'];
    }

}