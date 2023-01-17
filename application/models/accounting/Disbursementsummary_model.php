<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Jayson Dagulo
 * Module       : Disbursement Summary
 * Date         : Jan 30, 2019
 * Finished     : 
 * Description  : This module allows authorized user to manually closes the journal entries.
 * DB Tables    : 
 * */ 

class Disbursementsummary_model extends CI_Model {

    public function getSuppliers( $params )
    {
        $this->db->select('supplier.idSupplier as id, supplier.name, paymentMethod, sk');
        
        if ( isset( $params['idAffiliate'] ) && $params['idAffiliate'] != 0 ) {
            $this->db->join( 'supplieraffiliate' , 'supplieraffiliate.idSupplier = supplier.idSupplier');
            $this->db->where( 'supplieraffiliate.idAffiliate' , $params['idAffiliate'] ); 
        }

        $this->db->where( 'supplier.archived' , 0 ); 
        $this->db->group_by('supplier.idSupplier');
        return $this->db->get('supplier')->result_array();
    }

    public function getDisbursementSummary( $params )
    {
        $this->db->select("
            affiliateName
            ,invoices.date
            ,concat( reference.code , ' - ' , invoices.referenceNum ) as reference
            ,supplier.name AS supplierName
            ,IF(chequeNo < 1, '', chequeNo) as chequeNo
            ,IF(postdated.date = '0000-00-00', '', postdated.date) as chequeDate
            ,invoices.remarks
            ,invoices.amount
            ,invoices.idInvoice
            ,invoices.idModule
            ,supplier.sk AS suppSK
            ,affiliate.sk AS affSK
        ");
        $this->db->join( "reference"    , "reference.idReference = invoices.idReference" );
        $this->db->join( "affiliate"    , "affiliate.idAffiliate = invoices.idAffiliate" );
        $this->db->join( "supplier"     , "supplier.idSupplier = invoices.pCode" );
        $this->db->join( "postdated"    , "postdated.idInvoice = invoices.idInvoice" , "left" );
        
        if ( isset( $params['idAffiliate'] ) && $params['idAffiliate'] != 0 ) {
            $this->db->where( 'invoices.idAffiliate' , $params['idAffiliate'] ); 
        }

        if ( isset( $params['idSupplier'] ) && $params['idSupplier'] != 0 ) {
            $this->db->where( 'invoices.pCode' , $params['pCode'] );
        }

        $this->db->where("DATE(invoices.date) BETWEEN '{$params['sdate']}' AND '{$params['edate']}'" , NULL, FALSE);
        $this->db->where_in( "invoices.idModule" , 45 );
        $this->db->where_in( 'invoices.status' , 2 );
        $this->db->where_not_in( 'invoices.cancelTag' , 1 );
        $this->db->where_not_in( 'invoices.archived' , 1 );
        $this->db->group_by( "invoices.idInvoice, chequeNo , postdated.date " );
        return $this->db->get( 'invoices' )->result_array();
    }
}