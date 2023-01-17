<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Hazel Alegbeleye
 * Module: Classification Settings
 * Date: December 4, 2019
 * Finished: December 4, 2019
 * Description: This module allows authorized users to set (add, edit and delete) an item classification. 
 * DB Tables: itemclassification, item
 * */ 
class Classificationsettings_model extends CI_Model {

    function getClassCode( $params ){
        $this->db->select("LPAD( IFNULL(MAX(idItemClass),0)+1, 5, 0) as idItemClass");
        return $this->db->get('itemclassification')->result_array();
    }

    function getClassCode2( $params ){
        $this->db->select("LPAD( IFNULL(MAX(idItemClass),0)+1, 5, 0) as idItemClass");
        return $this->db->get('itemclassification')->row()->idItemClass;
    }

    function getItemClassifications( $params ) {
        $this->db->select('idItemClass, LPAD( classCode, 5, 0) as classCode, className');
        $this->db->where( 'archived', 0 );
        if( isset( $params['filterValue'] ) ) {
            $this->db->like('className', $params['filterValue'], 'after');
        }

        return $this->db->get('itemclassification')->result_array();
    }
    function saveClassification( $params ){
        $this->db->select('*');
        $this->db->where( array('className' => $params['className'], 'archived' => 0) );
        $num_rows = $this->db->get('itemclassification')->num_rows();

        if( isset( $params['onEdit'] ) && $params['onEdit'] == 0 ) {
            $this->db->where( 'idItemClass' , $params['idItemClass'] );
            $historyParams = $params;
            unset( $params['idItemClass'] );
            $this->db->update( 'itemclassification', unsetParams( $params, 'itemclassification' ));
            
            $this->db->insert( 'itemclassificationhistory', unsetParams( $historyParams, 'itemclassificationhistory') );

            $msg = 'SAVE_SUCCESS';
            $match = 0;
        } else {
            if( $num_rows <= 0 ) {
                $this->db->select('*');
                $this->db->where( array('idItemClass' => $params['idItemClass'], 'archived' => 0) );
                $num_rows = $this->db->get('itemclassification')->num_rows();
    
                if( $num_rows > 0 ) {
                    $msg = 'itemclassification code already exists. Would you like to generate a new one?';
                    $match = 2;
                } else {
                    $this->db->insert( 'itemclassification', unsetParams( $params, 'itemclassification') );
    
                    $msg = 'SAVE_SUCCESS';
                    $match = 0;
                }
            } else {
                $msg = $params['className'] . ' already exists.';
                $match = 1;
            }
        }
        
        // match values
        // 0 = SUCCESS
        // 1 = NAME EXISTS
        // 2 = CODE EXISTS

        return array(
            'msg' => $msg
            ,'match' => $match
        );
    }

    function retrieveData( $params ){
        $this->db->select('idItemClass, LPAD( max( idItemClass), 5, 0) as classCode, className');
        $this->db->where('idItemClass', $params['idItemClass']);
        return $this->db->get('itemclassification')->result_array();
    }

    function deleteClassification( $params ){
        $match = 0;
        $this->db->select('*');
        $this->db->where('idItemClass', $params['idItemClass']);
        $num_rows = $this->db->get('item')->num_rows();

        // print_r( $num_rows );

        if( $num_rows > 0 ){
            $match = 1;
        } else {
            /* SOFT DELETE ONLY */
            $this->db->set('archived', 1, false );
            $this->db->where('idItemClass', $params['idItemClass'] );
            $this->db->update('itemclassification');
            //$this->db->delete( 'itemclassification', array('idItemClass' => $params['idItemClass'] ));
        }

        return $match;

    }
}