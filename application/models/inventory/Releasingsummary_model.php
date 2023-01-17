<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Makmak    
 * Module       : Adjustment Summary
 * Date         : Feb. 18, 2020
 * Finished     : 
 * Description  : 
 * DB Tables    : 
 * */
   class Releasingsummary_model extends CI_Model
   {
      public function getReferences( $params )
      {
         $this->db->select('reference.idReference as id, CONCAT(code, " - ", name) as name');
         $this->db->join('referenceaffiliate' , 'reference.idReference = referenceaffiliate.idReference');
         $this->db->where('archived', 0);
         $this->db->where_in('idModule', [ 18, 22, 23, 29, 43 ] );

         if ( isset( $params['idAffiliate'] ) ) {
            if ( $params['idAffiliate'] != 0 ) {
               $this->db->where( 'referenceaffiliate.idAffiliate' , $params['idAffiliate'] ); 
            } 
         }

         $this->db->group_by('reference.idReference');
         return $this->db->get('reference')->result_array();
      }

      public function getItemClass(){
         $this->db->select('idItemClass as id, className as name');
         $this->db->where('archived', 0);
         return $this->db->get('itemclassification')->result_array();
      }

      public function getItems( $params ) {
         $this->db->select('item.idItem as id, itemName as name, sk');
         $this->db->join('itemaffiliate' , 'itemaffiliate.idItem=item.idItem');
         $this->db->where( 'archived', 0 );

         if ( isset( $params['idAffiliate'] ) ) {
            if ( $params['idAffiliate'] != 0 ) {
               $this->db->where( 'itemaffiliate.idAffiliate' , $params['idAffiliate'] ); 
            } 
         }

         if ( isset( $params['idItemClass'] ) ) {
            if ( $params['idItemClass'] != 0 ) {
               $this->db->where( 'item.idItemClass' , $params['idItemClass'] ); 
            } 
         }

         $this->db->group_by('item.idItem');
         return $this->db->get('item')->result_array();
      }

      public function getCustomers( $params )
      {
         $this->db->select('customer.idCustomer as id, customer.name, sk');
         
         if ( isset( $params['idAffiliate'] ) && $params['idAffiliate'] != 0 ) {
            $this->db->join( 'customeraffiliate' , 'customeraffiliate.idCustomer = customer.idCustomer');
            $this->db->where( 'customeraffiliate.idAffiliate' , $params['idAffiliate'] ); 
         }

         if ( isset( $params['idItem'] ) && $params['idItem'] != 0 ) {
            $this->db->join( 'customeritems' , 'customeritems.idCustomer = customer.idCustomer');
            $this->db->where( 'customeritems.idItem' , $params['idItem'] ); 
         }

         $this->db->group_by('customer.idCustomer');
         return $this->db->get('customer')->result_array();
      }

      public function getReleasingsummary( $params )
      {
         $this->db->select( 
            ' affiliateName
            , affiliate.sk as affiliateSK
            , invoices.date
            , CONCAT( reference.code, " - ", invoices.referenceNum ) as code
            , customer.name
            , customer.sk as customerSK
            , item.barcode
            , item.itemName
            , item.sk as itemSK
            , className
            , unit.unitCode as unitName
            , qty
            , cost
            , price
            , invoices.amount
            , invoices.idInvoice
            , invoices.idModule'
         );

         $this->db->join( 'releasing' , 'releasing.idInvoice = invoices.idInvoice' , 'left');
            $this->db->join('item' , 'item.idItem = releasing.idItem' , 'left' );
               $this->db->join('itemclassification' , 'itemclassification.idItemClass = item.idItemClass' , 'left' );
               $this->db->join('unit' , 'unit.idUnit = item.idUnit' , 'left' );
         $this->db->join('customer' , 'customer.idCustomer = invoices.pCode' , 'left' );
         $this->db->join('affiliate' , 'affiliate.idAffiliate = invoices.idAffiliate');
         $this->db->join('reference' , 'reference.idReference = invoices.idReference');

         if ( $params['idAffiliate'] != 0 ) {
            $this->db->where( 'affiliate.idAffiliate' , $params['idAffiliate'] ); 
         }

         if ( $params['idReference'] != 0 ) {
            $this->db->where( 'reference.idReference' , $params['idReference'] ); 
         }

         if ( $params['idItemClass'] != 0 ) {
            $this->db->where( 'item.idItemClass' , $params['idItemClass'] ); 
         }

         if ( $params['idItem'] != 0 ) {
            $this->db->where( 'item.idItem' , $params['idItem'] ); 
         }

         if ( $params['idCustomer'] != 0 ) {
            $this->db->where( 'customer.idCustomer' , $params['idCustomer'] ); 
         }

         $this->db->where('invoices.archived', 0 );
         $this->db->where_not_in('invoices.cancelTag', 1 );
         $this->db->where_in('reference.idModule', [ 18, 22, 23, 29, 43 ] );
         $this->db->where( 'invoices.date >= "'.$params['datefrom'].' '.$params['timefrom'].'" ');
         $this->db->where( 'invoices.date <= "'.$params['dateto'].' '.$params['timeto'].'" ');

         $this->db->group_by(
            ' affiliateName 
            , invoices.date 
            , CONCAT( reference.code, " - ", invoices.referenceNum )
            , customer.name
            , invoices.amount
            , idReleasing 
            ,invoices.idInvoice'
         );

         $this->db->order_by( "invoices.date" , "DESC" );
         return $this->db->get( 'invoices' )->result_array();
      }
   }
    

   // SALES
   // INV CONVERSION
   // " ADJUSTMENT
   // PRUCHASE RET
   // sTOCJ TRANSFER
