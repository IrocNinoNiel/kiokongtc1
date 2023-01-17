
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Hazel Alegbeleye
 * Module: Bank Settings
 * Date: Dec 3, 2019
 * Finished: December 3, 2019
 * Description: This module allows the authorized user to set (add, edit and delete) a bank
 * DB Tables: bank, bankaccount
 * */ 
class Banksettings_model extends CI_Model {

    function getBanks( $params ) {
        $this->db->select('idBank, bankName, sk');
        $this->db->where( array( 'archived' => 0 ) );
        $this->db->from('bank');

        $params['db'] = $this->db;
        $params['order_by'] = 'bankName';

        return getGridList($params);
    }

    function retrieveData( $params ) {
        $this->db->select('*');
        $this->db->where( array( 'idBank' => $params['idBank'], 'archived' => 0 ) );
        return $this->db->get('bank')->result_array();
    }

    function saveBank( $params ) {
        if( isset( $params['onEdit'] ) && $params['onEdit'] == 0 ) {
            $this->db->where( 'idBank' , $params['idBank'] );
            $idBank = $params['idBank'];
            $historyParams = $params;

            unset( $params['idBank'] );
            $this->db->update( 'bank', unsetParams( $params, 'bank' ));

            $this->db->insert('bankhistory', unsetParams( $historyParams, 'bankhistory' ));
            $msg = 'SAVE_SUCCESS';
            $match = 0;
        } else {
            $this->db->select('*');
            $this->db->where( array( 'bankName' => $params['bankName'], 'archived' => 0 ));
            $num_rows = $this->db->get('bank')->num_rows();

            if( $num_rows > 0 ) {
                $msg = 'Bank ' . $params['bankName'] . ' already exists.' ;
                $idBank = 0;
                $match = 1;
            } else {
                $this->db->insert('bank', unsetParams( $params, 'bank' ));
                $idBank = $this->db->insert_id();
                $msg = 'SAVE_SUCCESS';
                $match = 0;
            }
        }

        return array(
            'msg' => $msg
            ,'idBank' => $idBank
            ,'match' => $match
        );
    }

    function deleteBank( $params ) {
        $match = 0;
        $this->db->select('*');
        $this->db->where('idBankAccount' , $params['idBank']);
        $num_rows = $this->db->get('postdated')->num_rows(); //bank_account

        if( (int)$num_rows > 0 ) {
            $match = 1;
        } else {
            /* SOFT DELETE ONLY */
            $this->db->set('archived', 1, false );
            $this->db->where('idBank', $params['idBank'] );
            $this->db->update('bank');

            // $this->db->delete( 'bank', array('idBank' => $params['idBank'] ));
        }

        return $match;
    }
}