<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Bankaccountsettings_model extends CI_Model
{
    public function getBanks()
    {
        return $this->db->select( " idBank as id , bankName as name, sk " )
        ->where_not_in( 'archived' , 1 )
        ->get( 'bank' )->result_array();
    }

    public function getCoa( $params )
    {
        $this->db->select( " coa.idCoa as id , aname_c30 as name " );
        $this->db->where_not_in( 'archived' , 1 );

        if ( isset( $params['idAffiliate'] ) && $params['idAffiliate'] != 0 ) {
            $this->db->join('coaaffiliate' , 'coaaffiliate.idCoa = coa.idCoa');
            $this->db->where( 'coaaffiliate.idAffiliate' , $params['idAffiliate'] ); 
        }

        return $this->db->get( 'coa' )->result_array();
    }

    public function getBankAccounts( $params )
    {
        $this->db->select( "
            idBankAccount
            ,affiliateName
            ,bankName
            ,bankAccount
            ,bankAccountNumber
            ,begBal
            ,aname_c30
            ,bankaccount.sk as bankAccountSK
            ,affiliate.sk as affiliateSK
            ,bank.sk as bankSK
        " );
        $this->db->join( 'coa'          , 'coa.idCoa = bankaccount.idCoa' );
        $this->db->join( 'bank'         , 'bank.idBank = bankaccount.idBank' );
        $this->db->join( 'affiliate'    , 'affiliate.idAffiliate = bankaccount.idAffiliate' );
        $this->db->where_not_in( 'bankaccount.archived' , 1 );

        if( isset( $params['filterValue'] ) ) {
            $this->db->where( 'bankaccount.idBankAccount', $params['filterValue']);
        }

        return $this->db->get( 'bankaccount' )->result_array();
    }

    public function retrieveData( $params )
    {
        $this->db->select( ' bankaccount.* , aname_c30 as coaName ');
        $this->db->where( 'idBankAccount' , (int)$params['idBankAccount'] );
        $this->db->join( 'coa' , 'coa.idCoa = bankaccount.idCoa' , 'left' );
        return $this->db->get( 'bankaccount' )->result_array();
    }

    public function saveBankAccount( $params )
    {
        $this->db->insert( 'bankaccount', unsetParams( $params, 'bankaccount' ) );
        return $this->db->insert_id();
    }

    public function saveBankAccountHistory( $params , $idBankAccount )
    {
        $params['idBankAccount'] = $idBankAccount;
        $this->db->insert( 'bankaccounthistory', unsetParams( $params, 'bankaccounthistory' ) );
        return $this->db->insert_id();
    }

    public function checkDuplicaiton( $params )
    {
        $this->db->where( 'idAffiliate' , $params['idAffiliate'] );
        $this->db->where( 'bankAccount' , $params['bankAccount'] );
        $this->db->where( 'bankAccountNumber' , $params['bankAccountNumber'] );
        return $this->db->get( 'bankaccount' )->result_array();
    }
    public function updateBankAccount( $params , $idBankAccount )
    {
        $this->db->where( 'idBankAccount', $idBankAccount );
        $this->db->update( 'bankaccount', unsetParams( $params, 'bankaccount' ) );
        return $this->db->insert_id();
    }

    public function checkIfNotFound( $idBankAccount )
    {
        return $this->db->where( 'idBankAccount', $idBankAccount )
        ->where_not_in( 'archived' , 0 )
        ->get( 'bankaccount' )->result_array();
    }

    public function checkIfUsed( $idBankAccount )
    {
        $this->db->where( 'postdated.idBankAccount' , $idBankAccount );
        return $this->db->get( 'postdated' )->result_array();
    }

    public function archiveBankAccount( $idBankAccount )
    {
        $data = array (
            'archived' => 1
        );

        $this->db->where( 'idBankAccount' , $idBankAccount );
        return $this->db->update( 'bankaccount' , $data );
    }

    public function searchGrid( $params )
    {
        $this->db->select('
            idBankAccount   as id
            ,bankAccount    as name
            ,sk
        ');
        $this->db->from( 'bankaccount' );

        if( isset( $params['query'] ) ) {
            $this->db->like( "bankAccount" , $params['query'], "both");
        }

        $this->db->where_not_in( 'archived' , 1 );
        // $this->db->group_by( 'bankaccount' );
        return $this->db->get()->result_array();
    }
}
    