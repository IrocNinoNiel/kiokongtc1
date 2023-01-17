<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Journalizedtransactionsummary_model extends CI_Model
{
    public function getModules( $params )
    {
        $this->db->select(
            " module.idModule as id
            , moduleName as name "
        );
    
        $this->db->join( "invoices" , "invoices.idModule = module.idModule and cancelTag = 0" );
        $this->db->where( "invoices.idAffiliate" , $params['idAffiliate'] );
        $this->db->where_not_in( "invoices.archived" , 1 );

        $this->db->where( "module.isTransaction" , 0 );
        return $this->db->get( "module" )->result_array();
    }

    public function getReference( $params )
    {
        $this->db->select(
            " reference.idReference as id
            , CONCAT( reference.code , ' - ' , reference.name ) as name "
        );
    
        $this->db->join( "referenceaffiliate" , "referenceaffiliate.idReference = reference.idReference" );

        if ( isset( $params['idModule'] ) && $params['idModule'] != 0 ) {
            $this->db->join( "invoices" , "invoices.idReference = reference.idReference" );
            $this->db->where( 'invoices.idModule' , $params['idModule'] );
            $this->db->where_not_in( "invoices.archived" , 1 );
        }   $this->db->where( "referenceaffiliate.idAffiliate" , $params['idAffiliate'] );
        
        $this->db->group_by( "reference.idReference" );
        return $this->db->get( "reference" )->result_array();
    }

    public function getJournalizedtransactionsummary( $params )
    {
        $this->db->select(
            " invoices.idModule as idModule
            , invoices.idInvoice as idInvoice
            , DATE( invoices.date ) as date
            , CONCAT( reference.code , ' - ' , invoices.referenceNum ) AS reference
            , acod_c15 as accountCode
            , aname_c30 as accountName
            , debit
            , credit "
        );
        $this->db->join( "reference" , "reference.idReference = invoices.idReference" );
        $this->db->join( "posting" , "posting.idInvoice = invoices.idInvoice" );
        $this->db->join( "coa" , "coa.idCoa = posting.idCoa" );

        //FILTERS
        if( $params['idModule'] != 0 ) $this->db->where( "invoices.idModule" , $params['idModule'] );
        if( $params['idReference'] != 0 ) $this->db->where( "invoices.idReference" , $params['idReference'] );
        $this->db->where( "invoices.idAffiliate" , $params['idAffiliate'] );
        $this->db->where( "DATE(invoices.date) BETWEEN '{$params['sdate']}' AND '{$params['edate']}'" , NULL ,FALSE );

        $this->db->where( "invoices.status" , 2 );
        $this->db->where_not_in( "invoices.archived" , 1 );
        $this->db->where_not_in( "invoices.cancelTag" , 1 );

        $this->db->order_by( "invoices.date" , "DESC" );
        return $this->db->get( "invoices" )->result_array();
    }
}