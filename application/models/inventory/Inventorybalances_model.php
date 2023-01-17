<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Inventorybalances_model extends CI_Model
{
   public function getItemClassifications(){
      return $this->db->select("
         idItemClass as id
         ,className as name
      ")
      ->where( 'archived' , 0 )
      ->get('itemclassification')->result_array();
   }

   public function getItems( $params ){
      $this->db->select(
         " item.idItem  as id
         , itemName     as name
         , sk "
      );

      if ( isset( $params['idAffiliate'] ) && $params['idAffiliate'] != 0 ) {
         $this->db->join( 'itemaffiliate' , 'itemaffiliate.idItem = item.idItem');
         $this->db->where( 'itemaffiliate.idAffiliate' , $params['idAffiliate'] ); 
      };

      if ( isset( $params['idItemClass'] ) && $params['idItemClass'] != 0 ) {
         $this->db->where( 'item.idItemClass' , $params['idItemClass'] ); 
      };

      $this->db->where( 'archived' , 0 );
      return $this->db->get( 'item' )->result_array();
   }

   public function getInventoryBalances( $params )
   {
      {
         $WHERE = "WHERE invoices.archived NOT IN (1) AND invoices.cancelTag NOT IN (1) AND idModule IN (18 , 21 , 22 , 23 , 25 , 29 , 43) AND date < ( '$params[datefrom]' + INTERVAL 1 DAY ) GROUP BY idItem, idAffiliate";
         $REC_REL = "SELECT item.idItem, idAffiliate, SUM(qty) as qty FROM item 
            LEFT JOIN receiving ON receiving.idItem = item.idItem
            JOIN invoices ON invoices.idInvoice = receiving.idInvoice $WHERE
            UNION ALL SELECT item.idItem, idAffiliate, (SUM(qty)*-1) as qty FROM item 
            LEFT JOIN releasing ON releasing.idItem = item.idItem
            JOIN invoices ON invoices.idInvoice = releasing.idInvoice $WHERE
         ";
      } 

      $this->db->SELECT("
         affiliate.sk AS affSK
         , item.sk AS itemSK
         , affiliateName
         , itemName
         , className
         , unitCode
         , latestReceivingCost.cost
         , reorderLevel
         , SUM(qty) AS balance
      ")->FROM( "item" );

      $this->db->JOIN( "(
         SELECT max( idReceiving ) as idReceiving, idItem 
         FROM receiving GROUP BY idItem 
      ) AS maxReceivingID" , "maxReceivingID.idItem = item.idItem" , "LEFT" )
         ->JOIN( "(
            SELECT idReceiving, cost
            FROM receiving
         ) AS latestReceivingCost" , "latestReceivingCost.idReceiving = maxReceivingID.idReceiving" , "LEFT" );
      
      $this->db->JOIN( "($REC_REL) AS qtyLeft" , "qtyLeft.idItem = item.idItem" );

      $this->db->JOIN( "unit" , "unit.idUnit = item.idUnit" , "LEFT" );
      $this->db->JOIN( "affiliate" , "affiliate.idAffiliate = qtyLeft.idAffiliate" , "LEFT" );
      $this->db->JOIN( "itemclassification" , "itemclassification.idItemClass = item.idItemClass" , "LEFT" );
     
      if ( $params['idAffiliate'] != 0 ) $this->db->WHERE( 'qtyLeft.idAffiliate' , $params['idAffiliate'] );
      if ( $params['idItem'] != 0 ) $this->db->WHERE( 'item.idItem' , $params['idItem'] ); 
      if ( $params['idItemClass'] != 0 ) $this->db->WHERE( 'item.idItemClass' , $params['idItemClass'] ); 
      
      $this->db->WHERE_NOT_IN( "item.archived" , 1 );
      $this->db->GROUP_BY( "item.idItem , qtyLeft.idAffiliate , latestReceivingCost.cost" );
      return $this->db->get()->result_array();   
   }
}
