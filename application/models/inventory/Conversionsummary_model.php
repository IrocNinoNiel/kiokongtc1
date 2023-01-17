<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Makmak    
 * Module       : Conversion Summary
 * Date         : Feb. 18, 2020
 * Finished     : 
 * Description  : Conversions' Summaries
 * DB Tables    : 
 * */
    class Conversionsummary_model extends CI_Model
    {
        public function getReferences( $params )
        {
            $this->db->select('
                reference.idReference as id
                ,CONCAT( code, " - ", name ) as name
            ');

            if ( isset( $params['idAffiliate'] ) && $params['idAffiliate'] != 0 ) 
            {
                $this->db->join('referenceaffiliate' , 'reference.idReference = referenceaffiliate.idReference');
                $this->db->where( 'referenceaffiliate.idAffiliate' , $params['idAffiliate'] ); 
            }   $this->db->where('archived', 0);
                $this->db->where('idModule', 22 );

            $this->db->group_by('reference.idReference');
            return $this->db->get('reference')->result_array();
        }

        public function getItems( $params ) {
            $this->db->select('item.idItem as id, itemName as name, sk');
            $this->db->where( 'archived', 0 );
   
            if ( isset( $params['idAffiliate'] ) && $params['idAffiliate'] != 0 ) {
                $this->db->join('itemaffiliate' , 'itemaffiliate.idItem=item.idItem');
                $this->db->where( 'itemaffiliate.idAffiliate' , $params['idAffiliate'] ); 
            }
   
            $this->db->group_by('item.idItem');
            return $this->db->get('item')->result_array();
        }

        public function getInvConversions( $params )
        {
            $this->db->select("
                invoices.idInvoice
                ,invoices.idModule
                ,affiliateName
                ,affiliate.sk as affiliateSK
                ,invoices.date
                ,CONCAT( reference.code, ' - ', invoices.referenceNum) as code
                ,item.barcode
                ,item.itemName
                ,item.sk as itemSK
                ,unit.unitCode
                ,inventoryconversion.cost 
                ,inventoryconversion.received 
                ,inventoryconversion.released 
                ,inventoryconversion.amount
            ");
            $this->db->from('invoices');
            $this->db->join("
                ( 
                    SELECT 
                        receiving.idItem
                        ,receiving.cost
                        ,0 as released
                        ,receiving.qty as received
                        ,receiving.idInvoice as idInvoice
                        ,receiving.cost * receiving.qty as amount
                    FROM receiving
                    GROUP BY receiving.idReceiving
                    
                    UNION ALL
                    
                    SELECT
                        releasing.idItem
                        ,releasing.cost
                        ,releasing.qty as released 
                        ,0 as received
                        ,releasing.idInvoice as idInvoice
                        ,releasing.cost * releasing.qty as amount
                    FROM releasing
                    GROUP BY releasing.idReleasing 
                ) as inventoryconversion", "inventoryconversion.idInvoice = invoices.idInvoice
            ");
            $this->db->join("item", "item.idItem = inventoryconversion.idItem");
                $this->db->join("unit", "unit.idUnit= item.idUnit");
            $this->db->join("reference", "reference.idReference = invoices.idReference");
            $this->db->join("affiliate", "affiliate.idAffiliate = invoices.idAffiliate");

            if ( isset( $params['idAffiliate'] ) && $params['idAffiliate'] != 0 ) {
                $this->db->where( 'affiliate.idAffiliate' , $params['idAffiliate'] ); 
            }

            if ( isset( $params['idReference'] ) && $params['idReference'] != 0 ) {
                $this->db->where( 'reference.idReference' , $params['idReference'] ); 
            }

            if ( isset( $params['idItem'] ) && $params['idItem'] != 0 ) {
                $this->db->where( 'item.idItem' , $params['idItem'] ); 
            }
            
            $this->db->where( 'reference.idModule', 22);
            $this->db->where( "invoices.status" , 2 );
            $this->db->where_not_in( "invoices.archived" , 1 );
            $this->db->where_not_in( "invoices.cancelTag" , 1 );
            $this->db->where( 'invoices.date >= "'.$params['datefrom'].' '.$params['timefrom'].'" ');
            $this->db->where( 'invoices.date <= "'.$params['dateto'].' '.$params['timeto'].'" ');
            $this->db->order_by( 'invoices.idInvoice', 'ASC' );
            return $this->db->get()->result_array();
        }
    }