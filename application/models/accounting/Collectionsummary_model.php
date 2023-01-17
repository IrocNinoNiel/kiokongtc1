<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Jayson Dagulo
 * Module       : Collection Summary
 * Date         : Jan 30, 2019
 * Finished     : 
 * Description  : This module allows authorized user to manually closes the journal entries.
 * DB Tables    : 
 * */ 

class Collectionsummary_model extends CI_Model{

    public function getCashReceiptReferences( $params ){
        $this->db->select( "reference.idReference as id , CONCAT( reference.code , ' - ' , reference.name ) as name");
        $this->db->where_not_in( "reference.archived" , 1 );
        $this->db->where( "reference.idModule" , 28 );

        if ( isset( $params['idAffiliate'] ) && $params['idAffiliate'] != 0 ) {
            $this->db->join('referenceaffiliate' , 'referenceaffiliate.idReference = reference.idReference');
            $this->db->where( 'referenceaffiliate.idAffiliate' , $params['idAffiliate'] ); 
        }
        
        $this->db->group_by( "reference.idReference" );
        return $this->db->get( "reference" )->result_array();
    }

    public function getCustomer( $params ){
        $this->db->select( "customer.idCustomer as id , customer.name , customer.paymentMethod, sk" );
        $this->db->where_not_in( "customer.archived" , 1 );

        if ( isset( $params['idAffiliate'] ) && $params['idAffiliate'] != 0 ) {
            $this->db->join('customeraffiliate' , 'customeraffiliate.idCustomer = customer.idCustomer');
            $this->db->where( 'customeraffiliate.idAffiliate' , $params['idAffiliate'] ); 
        }

        $this->db->group_by( "customer.idCustomer" );
        return $this->db->get( "customer" )->result_array();
    }

    public function getCollectionSummary( $params )
    {
        $this->db->select("
            affiliateName
            ,DATE( invoices.date ) as date
            ,concat( reference.code , ' - ' , invoices.referenceNum ) as reference
            ,customer.name as customerName
            ,invoices.remarks
            ,(CASE
                WHEN postdated.paymentMethod NOT IN ( 2 ) THEN 'Cash'
                ELSE 'Charge'
            END) as payMode
            ,bankName
            ,chequeNo
            ,invoices.amount
            ,invoices.idInvoice
            ,invoices.idModule
            ,affiliate.sk AS affSK
            ,customer.sk AS custSK
            ,bank.sk AS bankSK
        ");
        $this->db->join( 'affiliate' , 'affiliate.idAffiliate = invoices.idAffiliate' );
        $this->db->join( 'reference' , 'reference.idReference = invoices.idReference' );
        $this->db->join( 'customer' , 'customer.idCustomer = invoices.pCode' );
        $this->db->join( 'postdated' , 'postdated.idInvoice = invoices.idInvoice' );
            $this->db->join( 'bank' , 'bank.idBank = postdated.idBankAccount' , 'left' );

        if ( isset( $params['idAffiliate'] ) && $params['idAffiliate'] != 0 ) {
            $this->db->where( 'invoices.idAffiliate' , $params['idAffiliate'] ); 
        }

        if ( isset( $params['idReference'] ) && $params['idReference'] != 0 ) {
            $this->db->where( 'invoices.idReference' , $params['idReference'] ); 
        }

        if ( isset( $params['idCustomer'] ) && $params['idCustomer'] != 0 ) {
            $this->db->where( 'invoices.pCode' , $params['idCustomer'] ); 
        }

        if ( isset( $params['payMode'] ) && $params['payMode'] != 0 ) {
            switch ( $params['payMode'] ) {
                case 2:
                    $this->db->where_not_in( 'postdated.idBankAccount' , 0 ); 
                    break; 
                default:
                    $this->db->where_in( 'postdated.idBankAccount' , 0 ); 
                    break; 
            }
        }

        
        $this->db->where( 'invoices.date > "'.$params['datefrom'].'" ');
        $this->db->where( 'invoices.date < ( "'.$params['dateto'].'" + INTERVAL 1 DAY ) ');

        $this->db->where( 'invoices.idModule' , 28 );
        $this->db->where_in( 'invoices.status' , 2 );
        $this->db->where_not_in( 'invoices.cancelTag' , 1 );
        $this->db->where_not_in( 'invoices.archived' , 1 );
        // $this->db->group_by( 'invoices.idInvoice , postdated.idBankAccount , chequeNo' );

        return $this->db->get( 'invoices' )->result_array();
    }

}