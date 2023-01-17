<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Makmak    
 * Module       : Adjustment Summary
 * Date         : Feb. 19, 2020
 * Finished     : 
 * Description  : 
 * DB Tables    : 
 * */ 
   class Adjustmentsummary_model extends CI_Model
   {
      public function getReferences( $params )
      {
            $this->db->select('
               reference.idReference as id
               ,CONCAT( code, " - ", name ) as name
            ');
            $this->db->where('archived', 0);
            $this->db->where('idModule', 23);

            if ( isset( $params['idAffiliate'] ) && $params['idAffiliate'] != 0 ) {
               $this->db->join('referenceaffiliate' , 'reference.idReference = referenceaffiliate.idReference');
               $this->db->where( 'referenceaffiliate.idAffiliate' , $params['idAffiliate'] ); 
            }

            $this->db->group_by('reference.idReference');
            return $this->db->get('reference')->result_array();
      }

      public function getItemClassifications(){
         $this->db->select('idItemClass as id, className as name');
         $this->db->where('archived', 0);
         return $this->db->get('itemclassification')->result_array();
      }

      public function getItems( $params ) {
         $this->db->select('item.idItem as id, itemName as name, sk');
         $this->db->where( 'archived', 0 );

         if ( isset( $params['idAffiliate'] ) && $params['idAffiliate'] != 0 ) {
            $this->db->join('itemaffiliate' , 'itemaffiliate.idItem=item.idItem');
            $this->db->where( 'itemaffiliate.idAffiliate' , $params['idAffiliate'] ); 
         }

         if ( isset( $params['idItemClass'] ) && $params['idItemClass'] != 0 ) {
            $this->db->where( 'item.idItemClass' , $params['idItemClass'] ); 
         }

         $this->db->group_by('item.idItem');
         return $this->db->get('item')->result_array();
      }

      public function getAdjustmentsummary( $params )
      {
         $this->db->select('
            invoices.idInvoice
            ,invoices.idModule
            ,affiliateName
            ,affiliate.sk as affiliateSK
            ,item.sk as itemSK
            ,date
            ,CONCAT( code, " - ", referenceNum)code
            ,item.itemName
            ,unit.unitCode
            ,qtyBal
            ,qtyActual
            ,cost
            ,short
            ,over
            ,className
         ');

         $this->db->join(     'item'               , 'item.idItem = invadjustment.idItem');
            $this->db->join(  'unit'               , 'unit.idUnit = item.idUnit');
            $this->db->join(  'itemclassification' , 'itemclassification.idItemClass = item.idItemClass');
         $this->db->join(     'invoices'           , 'invoices.idInvoice = invadjustment.idInvoice');
            $this->db->join(  'affiliate'          , 'affiliate.idAffiliate = invoices.idAffiliate', 'LEFT');
            $this->db->join(  'reference'          , 'reference.idReference = invoices.idReference');

         if ($params['idAffiliate'] != 0 ) { $this->db->where( 'affiliate.idAffiliate' , $params['idAffiliate'] ); }
         if ($params['idReference'] != 0 ) { $this->db->where( 'reference.idReference' , $params['idReference'] ); }
         if ($params['idItem'] != 0 ) { $this->db->where( 'item.idItem' , $params['idItem'] ); }

         // $this->db->where( "invoices.status" , 2 );
         $this->db->where_not_in( "invoices.archived" , 1 );
         $this->db->where_not_in( "invoices.cancelTag" , 1 );
         $this->db->where( 'reference.idModule', 23);
         $this->db->where( 'invoices.date >= "'.$params['datefrom'].' '.$params['timefrom'].'" ');
         $this->db->where( 'invoices.date <= "'.$params['dateto'].' '.$params['timeto'].'" ');
         return $this->db->get('invadjustment')->result_array();
      }
   }
    