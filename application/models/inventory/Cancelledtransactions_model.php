<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cancelledtransactions_model extends CI_Model
{
    public function getReferences( $params )
    {
        $this->db->select( "
              reference.idReference as id
            , CONCAT( reference.code , ' - ' , reference.name ) as name
        " );
        $this->db->join( "module" , "module.idModule = reference.idModule AND module.isTransaction = 0" );
        
        if ( isset( $params['idAffiliate'] ) && $params['idAffiliate'] != 0 ) {
            $this->db->join( "referenceaffiliate" , "referenceaffiliate.idReference = reference.idReference" );
            $this->db->where( "referenceaffiliate.idAffiliate" , $params['idAffiliate'] );
        }

        $this->db->where_not_in( "reference.archived" , 1 );
        return $this->db->get( "reference" )->result_array();
    }

    public function getModules( $params )
    {
        $this->db->select("
              idModule as id
            , moduleName as name
        ");
        
        if ( isset( $params['idAffiliate'] ) && $params['idAffiliate'] != 0 ) {
            $this->db->join( "invoices" , "invoices.idModule = module.idModule and cancelTag = 0" );
            $this->db->where( "invoices.idAffiliate" , $params['idAffiliate'] );
            $this->db->where_not_in( "invoices.archived" , 1 );
        }

        $this->db->where( "module.isTransaction" , 0 );
        return $this->db->get( "module" )->result_array();
    }

    public function getPNames( $params )
    {
        $this->db->select( " id , name , invoices.pType, sk" );
        $this->db->from( "invoices" ); 
        $this->db->join("( 
                SELECT idCustomer as id , name , 1 as pType , archived, sk
                FROM customer 

                UNION ALL 

                SELECT idSupplier as id , name , 2 as pType , archived, sk
                FROM supplier
            ) as pCodes"
            , "pCodes.id = invoices.pCode"
        );

        if ( isset( $params['pType'] ) && $params['pType'] != 0 ) {
            $this->db->where_in( "pCodes.pType" , $params['pType'] );
        }

        $this->db->where_not_in( "pCodes.archived" , 1 );
        return $this->db->get()->result_array();
    }

    public function getCancelledtransactions( $params )
    {
        $this->db->select("
            affiliateName
            , CONCAT( reference.code , ' - ' , invoices.referenceNum ) as reference
            , invoices.date
            , (CASE 
                WHEN pType = 1 THEN customer.name
                WHEN pType = 2 THEN supplier.name
            END) as name
            , invoices.remarks
            , invoices.amount
            , invoices.idInvoice
            , invoices.idModule
            , employee.name as cancelledBy
            , employee.sk AS empSK
            , affiliate.sk AS affSK
            , (CASE 
                WHEN pType = 1 THEN customer.sk
                WHEN pType = 2 THEN supplier.sk
            END) as nameSK
        ");
        $this->db->join( "affiliate" , "affiliate.idAffiliate = invoices.idAffiliate" );
        $this->db->join( "reference" , "reference.idReference = invoices.idReference" );
        $this->db->join( "customer" , "customer.idCustomer = invoices.pCode AND invoices.pType = 1" , "left outer" );
        $this->db->join( "supplier" , "supplier.idSupplier = invoices.pCode AND invoices.pType = 2" , "left outer" );
        $this->db->join( "eu" , "eu.idEu = invoices.cancelledBy" , "left outer" );
        $this->db->join( "employee" , "employee.idEmployee = eu.idEu" , "left outer" );

        if ( isset( $params['idAffiliate'] ) && $params['idAffiliate'] != 0 ) $this->db->where( "invoices.idAffiliate" , $params['idAffiliate'] );
        if ( isset( $params['idReference'] ) && $params['idReference'] != 0 ) $this->db->where( "invoices.idReference" , $params['idReference'] );
        if ( isset( $params['pType'] ) && $params['pType'] != 0 ) $this->db->where( "invoices.pType" , $params['pType'] );
        if ( isset( $params['idModule'] ) && $params['idModule'] != 0 ) $this->db->where( "invoices.idModule" , $params['idModule'] );
        if ( isset( $params['pCode'] ) && $params['pCode'] != 0 ) $this->db->where( "invoices.pCode" , $params['pCode'] );
        
        $this->db->where_not_in( "invoices.archived" , 1 );
        $this->db->where( "invoices.cancelTag" , 1 );
        $this->db->where( 'invoices.date >= "'.$params['datefrom'].' '.$params['timefrom'].'" ');
        $this->db->where( 'invoices.date <= "'.$params['dateto'].' '.$params['timeto'].'" ');

        $this->db->group_by( "invoices.idInvoice" );
        $this->db->order_by( "invoices.date" , "DESC" );

        return $this->db->get( "invoices" )->result_array();
    }
}
