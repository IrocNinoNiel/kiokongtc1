<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Chequereports_model extends CI_Model
{
    public function getChequesList( $params )
    {
        $this->db->select( "
            affiliateName
            ,affiliate.sk AS affSK
            ,bankaccount.sk AS baSK
            ,postdated.date as date
            ,CONCAT( reference.code , ' - ' , invoices.referenceNum ) as reference
            ,invoices.description
            ,bankaccount.bankAccount
            ,postdated.chequeNo
            ,sum(postdated.amount) as amount 
            ,( case
                when  postdated.status = 1 then 'Outstanding'
                when  postdated.status = 2 then 'Cleared'
                when  postdated.status = 3 then 'Cancelled'
                when  postdated.status = 4 then 'Bounced'
                else ''
            end ) as status
            ,statusDate
            ,depositedbank.bankAccount as depositTo

            ,invoices.idReference
            ,invoices.referenceNum
            ,invoices.idModule
            ,invoices.idInvoice
        " );
        $this->db->join( "bankaccount"                  
                        , "bankaccount.idBankAccount = postdated.idBankAccount"             
                        , "left outer"
                    );
        $this->db->join( "bankaccount depositedbank"    
                        , "depositedbank.idBankAccount = postdated.depositBankAccountId"    
                        , "left outer"
                    );
        $this->db->join( "invoices"     , "invoices.idInvoice = postdated.idInvoice");
        $this->db->join( "affiliate"    , "affiliate.idAffiliate = invoices.idAffiliate");
        $this->db->join( "reference"    , "reference.idReference = invoices.idReference");

        if ( $params['idAffiliate'] != 0 ) $this->db->where( 'invoices.idAffiliate' , $params['idAffiliate'] );
        if ( $params['chequeStatus'] != 0 ) $this->db->where( "postdated.status" , $params['chequeStatus'] ); 

        if ( $params['cheque']   != 0 ) {
            switch ( $params['cheque'] ) {
                case 1:     $this->db->where( "invoices.idModule"       , 45 ); break;
                case 2:     $this->db->where( "invoices.idModule"       , 28 ); break;
                default:    $this->db->where_in( "invoices.idModule"    , [ 28 , 45 ] ); break;
            }
        }

        $this->db->where( "DATE(postdated.date) BETWEEN '{$params['sdate']}' AND '{$params['edate']}'" , NULL ,FALSE );
        $this->db->where_not_in( "postdated.chequeNo"   , 0 );
        $this->db->where( "invoices.status" , 2 );
        $this->db->where_not_in( "invoices.archived" , 1 );
        $this->db->where_not_in( "invoices.cancelTag" , 1 );
        $this->db->where_in( "invoices.idModule"        , [ 28 , 45 ] );

        $this->db->group_by( "postdated.idPostdated" );
        return $this->db->get( "postdated" )->result_array();
    }
}
