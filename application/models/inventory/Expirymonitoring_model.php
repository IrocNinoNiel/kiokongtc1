<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Jayson Dagulo
 * Module       : Expiry Monitoring
 * Date         : Jan. 31, 2020
 * Finished     : 
 * Description  : This module allows authorized user to generate and monitor the expiration date of each item based on the specified date.
 * DB Tables    : 
 * */ 
class Expirymonitoring_model extends CI_Model{

    public function getItemName( $params ){
        $this->db->select( 'idItem, itemName, sk' );
        if( isset( $params['query'] ) ) $this->db->like( 'itemName', $params['query'], 'both' );
        $this->db->order_by( 'itemName', 'ASC' );
        $this->db->where_not_in( 'archived', 1 );
        return $this->db->get( 'item' )->result_array();
    }

    public function getExpiryMonitoring( $params ){
        $curDate = date( 'Y-m-d' );
        $this->db->select( "
            affiliate.affiliateName
            ,affiliate.sk as affiliateSK
            ,invoices.date as dateReceived
            ,invoices.idInvoice
            ,CONCAT( reference.code, ' - ', invoices.referenceNum  ) as reference
            ,itemclassification.className
            ,item.barcode
            ,item.itemName
            ,item.sk as itemSK
            ,unit.unitName
            ,receiving.qtyLeft
            ,(CASE
                WHEN receiving.expiryDate = '0000-00-00' THEN NULL
                ELSE receiving.expiryDate
            END) as expiryDate
            ,DATEDIFF( receiving.expiryDate, '$curDate' ) as remainingDays
            ,invoices.idModule
        " );
        $this->db->from( 'receiving' );
        $this->db->join( 'invoices', 'invoices.idInvoice = receiving.idInvoice', 'left outer' );
        $this->db->join( 'affiliate', 'affiliate.idAffiliate = invoices.idAffiliate', 'left outer' );
        $this->db->join( 'reference', 'reference.idReference = invoices.idReference', 'left outer' );
        $this->db->join( 'item', 'item.idItem = receiving.idItem', 'left outer' );
        $this->db->join( 'itemclassification', 'itemclassification.idItemClass = item.idItemClass', 'left outer' );
        $this->db->join( 'unit', 'unit.idUnit = item.idUnit', 'left outer' );
        $this->db->where( "invoices.idAffiliate IN( SELECT idAffiliate FROM employeeaffiliate WHERE idEmployee = " . $this->EMPLOYEEID . " )" );
        if( isset( $params['idItem'] ) ) $this->db->where( 'receiving.idItem', (int)$params['idItem'] );
        if( isset( $params['date'] ) ) $this->db->where( "invoices.date >= '" . date( 'Y-m-d', strtotime( $params['date'] ) ) . "'" );
        $this->db->where_not_in( 'invoices.archived', 1 );
        $this->db->where_not_in( 'invoices.cancelTag', 1 );

        $params['db'] = $this->db;
        $params['order_by'] = 'affiliate.affiliateName asc, invoices.date asc';

        return getGridList($params);
    }

}