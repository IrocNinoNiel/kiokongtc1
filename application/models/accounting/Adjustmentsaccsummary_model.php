<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Adjustmentsaccsummary_model extends CI_Model
{
    public function getReferences( $params ){
        $this->db->select( "reference.idReference as id , CONCAT( reference.code , ' - ' , reference.name ) as name");

        if ( isset( $params['idAffiliate'] ) && $params['idAffiliate'] != 0 ) {
            $this->db->join('referenceaffiliate' , 'referenceaffiliate.idReference = reference.idReference');
            $this->db->where( 'referenceaffiliate.idAffiliate' , $params['idAffiliate'] ); 
        }
        
        $this->db->where_not_in( "reference.archived" , 1 );
        $this->db->where( "reference.idModule" , 48 );
        $this->db->group_by( "reference.idReference" );
        return $this->db->get( "reference" )->result_array();
    }

    public function getAdjustmentsaccSummary( $params )
    {
        $this->db->select( '
            DATE( invoices.date ) as date
            ,affiliateName
            ,affiliate.sk as affiliateSK
            ,CONCAT( reference.code , " " , invoices.referenceNum ) as reference
            ,(CASE
                WHEN invoices.pType = 1 THEN customer.name
                WHEN invoices.pType = 2 THEN supplier.name
                ELSE ""
            END) as name
            ,IF( invoices.pType = 1, customer.sk, supplier.sk  ) AS nameSK
            ,invoices.description
            ,invoices.amount
        ' );

        $this->db->join( 'affiliate' , 'affiliate.idAffiliate = invoices.idAffiliate' );
        $this->db->join( 'reference' , 'reference.idReference = invoices.idReference' );
        $this->db->join( 'customer'  , 'invoices.pType = 1 AND customer.idCustomer = invoices.pCode' , 'left' );
        $this->db->join( 'supplier'  , 'invoices.pType = 2 AND supplier.idSupplier = invoices.pCode' , 'left' );

        if ( isset( $params['idAffiliate'] ) && $params['idAffiliate'] != 0 ) {
            $this->db->where( 'invoices.idAffiliate' , $params['idAffiliate'] ); 
        }
        
        if ( isset( $params['idReference'] ) && $params['idReference'] != 0 ) {
            $this->db->where( 'invoices.idReference' , $params['idReference'] ); 
        }

        if ( $params['viewBy'] != 0 ) {
            $this->db->where( 'invoices.status' , $params['viewBy'] ); 
        }

        switch ( $params['filterBy'] ) {
            case 1: $this->db->where( 'invoices.pType' , null );  break;
            case 2: $this->db->where( 'invoices.pType' , 1 );  break;
            case 3: $this->db->where( 'invoices.pType' , 2 );  break;
            default: break;
        }

        $this->db->where( "DATE(invoices.date) BETWEEN '{$params['sdate']}' AND '{$params['edate']}'" , NULL ,FALSE );
        $this->db->where_in( 'invoices.idModule' , 48 );
        $this->db->where_not_in( 'invoices.archived' , 1 );
        $this->db->group_by( 'invoices.idInvoice' );
        $this->db->order_by( 'invoices.idInvoice' , 'DESC' );
        
        return $this->db->get( 'invoices' )->result_array();
    }
}