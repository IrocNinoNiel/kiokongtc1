<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Inventoryledger_model extends CI_Model
{
   //GET ITEM CLASSIFICATION
   public function getItemClassifications(){
      $this->db->select('
         idItemClass as id 
         ,className as name
      ');

      $this->db->where( 'archived' , 0 );
      return $this->db->get( 'itemclassification' )->result_array();
   }

   // GET ITEMS
   public function getItems( $params )
   {
      $this->db->select('
         item.idItem as id
         ,item.itemName as name
         ,className
         ,unitName
         ,item.itemPrice
         ,item.reorderLevel
         ,item.sk
      ');

      $this->db->join( 'unit' , 'unit.idUnit = item.idUnit' , 'left');
      $this->db->join( 'itemclassification' , 'itemclassification.idItemClass = item.idItemClass' , 'left');
      
      if ( isset( $params['idAffiliate'] ) && $params['idAffiliate'] != 0 ) {
         $this->db->join('itemaffiliate' , 'itemaffiliate.idItem=item.idItem');
         $this->db->where( 'itemaffiliate.idAffiliate' , $params['idAffiliate'] ); 
      }

      $this->db->where( 'item.archived', 0 );
      $this->db->group_by( 'item.idItem' );
      return $this->db->get( 'item' )->result_array();
   }

   public function getInventoryLedger( $params )
   {
      //RECEIVING 
      {
         $receiving = $this->db->select("
            receiving.idReceiving as idLedger
            ,receiving.idInvoice as idInvoice
            ,receiving.idItem
            ,receiving.price as price
            ,receiving.cost  as cost
            ,0               as released
            ,receiving.qty   as received
         ")
         ->from( 'receiving' )->get_compiled_select();
         $this->db->reset_query();
      }

      //RELEASING
      { 
         $releasing = $this->db->select("
            releasing.idReleasing as idLedger
            ,releasing.idInvoice as idInvoice
            ,releasing.idItem
            ,releasing.price as price
            ,releasing.cost  as cost
            ,releasing.qty   as released 
            ,0               as received
         ")
         ->from( 'releasing' )->get_compiled_select();
         $this->db->reset_query();
      }

      //BEGINNING BALANCE
      {
         $beginBal = $this->db->select("
            'Beginning Balance'     as name
            ,SUM( ledger.price )		as price
            ,SUM( ledger.cost )   	as cost
            ,SUM( ledger.received ) as received
            ,SUM( ledger.released )	as released
            ,( SUM( ledger.received ) - SUM( ledger.released ) ) as balance
         ", false)
         ->from( 'invoices' )
         ->join( "( {$receiving} UNION ALL {$releasing} ) as ledger" , "ledger.idInvoice = invoices.IdInvoice" )
         ->where_in( "invoices.idModule" , [ 18 , 21 , 22 , 23 , 25 , 29, 43 ] )
         ->where( "invoices.archived" , 0 )
         ->where( "invoices.cancelTag" , 0 )
         ->where( "ledger.idItem" , $params['idItem'] )
         ->where( "invoices.idAffiliate", $params['idAffiliate'] )
         ->where( "DATE( invoices.date ) < '{$params['sdate']}'" )
         ->get()->result_array();
      }

      //LEDGER
      {
         $invLedger = $this->db->select("
            invoices.date
            ,invoices.idInvoice
            ,invoices.idModule
            ,CONCAT( reference.code , ' - ' , invoices.referenceNum ) as code
            ,(CASE
               WHEN invoices.pType = 1 THEN customer.name
               WHEN invoices.pType = 2 THEN supplier.name
               ELSE ''
            END) as name
            ,(CASE
               WHEN invoices.pType = 1 THEN customer.sk
               WHEN invoices.pType = 2 THEN supplier.sk
               ELSE ''
            END) as sk
            ,ledger.price     as price
            ,ledger.cost      as cost
            ,ledger.received  as received
            ,ledger.released  as released
            ,( (
               SELECT ( IFNULL ( SUM( subLedger.received ) , 0 ) - IFNULL ( SUM( subLedger.released ) , 0 ) )
               FROM ( {$receiving} UNION ALL {$releasing} ) AS subLedger
               JOIN invoices subInv ON subInv.idInvoice = subLedger.idInvoice
               WHERE subInv.idInvoice < invoices.idInvoice
               AND subInv.idModule IN ( 18 , 21 , 22 , 23 , 25 , 29 , 43 )
               AND subInv.archived not in ( 1 )
               AND subInv.cancelTag not in ( 1 )
               AND subLedger.idItem = {$params['idItem']}
               AND subInv.idAffiliate = {$params['idAffiliate']}
            ) + ledger.received - ledger.released ) as balance
         ", false)
         ->join( 'reference' , 'reference.idReference = invoices.idReference' )
         ->join( 'customer' , 'customer.idCustomer = invoices.pCode AND invoices.pType = 1' , 'left outer')
         ->join( 'supplier' , 'supplier.idSupplier = invoices.pCode AND invoices.pType = 2' , 'left outer')
         ->join( "( {$receiving} UNION ALL {$releasing} ) as ledger" , "ledger.idInvoice = invoices.IdInvoice" )

         ->where_not_in(   'invoices.archived'     , 1 )
         ->where_not_in(   'invoices.cancelTag'    , 1 )
         ->where(          'ledger.idItem'         , $params['idItem'] )
         ->where(          'invoices.idAffiliate'  , $params['idAffiliate'] )
         ->where_in(       'invoices.idModule'     , [ 18 , 21 , 22 , 23 , 25 , 29 , 43 ] )
         ->where(          "DATE(invoices.date) BETWEEN '{$params['sdate']}' AND '{$params['edate']}'" )

         ->group_by( 'invoices.idInvoice , ledger.price , ledger.cost , ledger.received , ledger.released' )
         ->order_by( 'invoices.idInvoice' , 'ASC' )

         ->get( 'invoices' )
         
         ->result_array();
      }

      

      return array_merge( $beginBal , $invLedger );
   }
}