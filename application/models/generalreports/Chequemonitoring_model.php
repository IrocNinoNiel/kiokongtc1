<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Chequemonitoring_model extends CI_Model
{
    public function getChequesList( $params )
    {
        $this->db->select( "
            invoices.idAffiliate
            ,affiliate.sk AS affSK
            ,bankaccount.sk AS baSK
            ,postdated.status as oldStatus
            ,postdated.idPostdated
            ,invoices.idModule
            ,invoices.idInvoice

            ,affiliateName
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

        if ( isset( $params['idAffiliate'] ) && $params['idAffiliate'] ) {
            $this->db->where( 'invoices.idAffiliate' , $params['idAffiliate'] ); 
        }

        if ( isset( $params['chequeMod'] ) && $params['chequeMod'] ) {
            switch ( $params['chequeMod'] ) {
                case 2:     $this->db->where( "invoices.idModule"       , 45 ); break;
                case 3:     $this->db->where( "invoices.idModule"       , 28 ); break;
                default:    $this->db->where_in( "invoices.idModule"    , [ 28 , 45 ] ); break;
            }
        }

        if ( isset( $params['chequeStatus'] ) && $params['chequeStatus'] ) {
            switch ( $params['chequeStatus'] ) {
                case 2: $this->db->where( "postdated.status" , 1 ); break;
                case 3: $this->db->where( "postdated.status" , 2 ); break;
                case 4: $this->db->where( "postdated.status" , 3 ); break;
                case 5: $this->db->where( "postdated.status" , 4 ); break;
                default: break;
            }
        }

        $this->db->where( "DATE(postdated.date) BETWEEN '{$params['sdate']}' AND '{$params['edate']}'" , NULL ,FALSE );
        $this->db->where_not_in( "DATE(postdated.date)"   , "0000-00-00" );
        $this->db->where_not_in( "postdated.chequeNo"   , 0 );
        $this->db->where_not_in( "invoices.archived"    , 1 );
        $this->db->where_not_in( "invoices.cancelTag"   , 1 );
        $this->db->where( "invoices.status" , 2 );
        $this->db->where_in( "invoices.idModule"        , [ 28 , 45 ] );

        $this->db->group_by( "postdated.idPostdated" );
        return $this->db->get( "postdated" )->result_array();
    }

    public function getBankAccounts( $params )
    {
        $this->db->select( "
            idBankAccount   as id
            ,bankAccount    as name
            ,bankaccount.sk AS baSK
        " );

        if( isset( $params['idAffiliate'] ) && $params['idAffiliate'] != 0 ) {
            $this->db->join( 'affiliate'    , 'affiliate.idAffiliate = bankaccount.idAffiliate' );
            $this->db->where( 'bankaccount.idBankAccount' , $params['idAffiliate'] );
        }

        $this->db->where_not_in( 'bankaccount.archived' , 1 );
        $this->db->group_by( 'bankaccount.idBankAccount' );
        return $this->db->get( 'bankaccount' )->result_array();
    }

    public function retrievePostdated( $idPostdated )
    {
        $this->db->where( 'idPostdated' , $idPostdated );
        return $this->db->get( 'postdated' )->row_array();
    }

    public function checkChanges( $idPostdated , $statusDate , $depositBankAccountId )
    {
        $this->db->where( 'idPostdated' , $idPostdated );
        $this->db->where( 'status' , 1 );
        $this->db->where( 'statusDate' , $statusDate );
        $this->db->where( 'depositBankAccountId' , $depositBankAccountId );
        return $this->db->get( 'postdated' )->result_array();
    }

    public function getBankAccount( $bankAccount , $idAffiliate )
    {
        return $this->db->select( '*' )
        ->where( 'bankAccount' , $bankAccount )
        ->where( 'idAffiliate' , $idAffiliate )
        ->get( "bankaccount" )->row_array();
    }

    public function updatePostdated( $idPostdated , $status , $statusDate , $depositTo  )
    {
        $data = array(
            'status'                => $status
            ,'statusDate'           => $statusDate
            ,'depositBankAccountId' => $depositTo
        );

        $this->db->where( 'idPostdated' , $idPostdated );
        return $this->db->update( 'postdated' , $data );
    }

    public function savePostdatedHistory( $postdated )
    {
        $this->db->insert( 'postdatedhistory', unsetParams( $postdated, 'postdatedhistory' ) );
        return $this->db->insert_id();
    }
}
