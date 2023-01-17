<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Jayson Dagulo
 * Module       : Chart of Accounts Beginning Balance
 * Date         : Jan 28, 2019
 * Finished     : 
 * Description  : This module allows authorized user to manually closes the journal entries.
 * DB Tables    : 
 * */
class Coabegbalance_model extends CI_Model {

    public function retrieveData( $params ){
        $this->db->select( 'idAccBegBal, date, idAffiliate, dateModified' );
        $this->db->where( 'idAffiliate', (int)$params['idAffiliate'] );
        return $this->db->get( 'accountbegbal' )->result_array();
    }

    public function getBeginningJournalEntries( $params ){
        $this->db->select( '
            coa.idCoa
            ,coa.acod_c15
            ,coa.aname_c30
            ,coa.accountType
            ,IFNULL( posting.debit, 0 ) as debit
            ,IFNULL( posting.credit, 0 ) as credit' );
        $this->db->join( '(
            SELECT
                posting.idCoa
                ,posting.debit
                ,posting.credit
            FROM
                posting
            LEFT OUTER JOIN accountbegbal
                ON( accountbegbal.idAccBegBal = posting.idAccBegBal )
            WHERE accountbegbal.idAccBegBal = ' . (int)$params['idAccBegBal'] . '
        ) as posting', 'posting.idCoa = coa.idCoa', 'left outer' );
        $this->db->where( 'coa.idCoa IN(SELECT idCoa FROM coaaffiliate WHERE idAffiliate = ' . (int)$params['idAffiliate'] . ')' );
        $this->db->order_by( 'coa.idCoa', 'ASC' );
        return $this->db->get( 'coa' )->result_array();
    }

    public function saveForm( $params ){
        $idAccBegBal    = (int)$params['idAccBegBal'];
        $params['dateModified'] = date( 'Y-m-d H:i:s' );
        unset( $params['idAccBegBal'] );
        if( $idAccBegBal > 0 ){
            $this->db->where( 'idAccBegBal', $idAccBegBal );
            $this->db->update( 'accountbegbal', unsetParams( $params, 'accountbegbal' ) );
            return $idAccBegBal;
        }else{
            $this->db->insert( 'accountbegbal', unsetParams( $params, 'accountbegbal' ) );
            return $this->db->insert_id();
        }
    }

    public function saveFormHistory( $params ){
        $this->db->insert( 'accountbegbalhistory', unsetParams( $params, 'accountbegbalhistory' ) );
        return $this->db->insert_id();
    }

    public function deleteExistingPosting( $params ){
        $this->db->where( 'idAccBegBal', (int)$params['idAccBegBal'] );
        $this->db->delete( 'posting' );
    }

    public function savePosting( $params ){
        $this->db->insert_batch( 'posting', unsetParamsBatch( $params, 'posting' ) );
    }

    public function savePostingHistory( $params ){
        $this->db->insert_batch( 'postinghistory', unsetParamsBatch( $params, 'postinghistory' ) );
    }

}