<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Hazel Alegbeleye
 * Module: Unit Settings
 * Date: December 4, 2019
 * Finished: 
 * Description: This module allows the authorized users to set ( add, edit and delete) the unit.
 * DB Tables: unit, item
 * */
class Unitsettings_model extends CI_Model {

    function getUnitCode( $params ){
        $this->db->select("LPAD( IFNULL(MAX('idUnit'),0)+1, 5, 0) as idUnit");
        return $this->db->get('unit')->result_array();
    }

    function getUnits( $params ) {
        $this->db->select('idUnit, unitCode, unitName');
        $this->db->where( 'archived', 0 );
        if( isset( $params['filterValue'] ) ) {
            $this->db->like('unitName', $params['filterValue'], 'after');
        }

        $this->db->order_by('unitCode asc');
        return $this->db->get('unit')->result_array();
    }
    function saveUnit( $params ){
        $match = 0;
        $this->db->select('*');
        $this->db->where( array( 'unitCode' => $params['unitCode'], 'archived' => 0 ) );
        $num_rows = $this->db->get('unit')->num_rows();

        if( isset( $params['onEdit'] ) && $params['onEdit'] == 0 ) {
            $this->db->where( 'idUnit' , $params['idUnit'] );
            $historyParams = $params;
            unset( $params['idUnit'] );
            $this->db->update( 'unit', unsetParams( $params, 'unit' ));

            $this->db->insert( 'unithistory', unsetParams( $historyParams, 'unithistory') );
        } else {
            if( $num_rows > 0 ) {
                $match = 1;
            } else {
                $this->db->insert( 'unit', unsetParams( $params, 'unit') );
            }
        }
        
        return $match;
    }

    function retrieveData( $params ){
        $this->db->select('idUnit, unitCode, unitName');
        $this->db->where('idUnit', $params['idUnit']);
        return $this->db->get('unit')->result_array();
    }

    function deleteUnit( $params ){
        $match = 0;
        $this->db->select('*');
        $this->db->where('idUnit', $params['idUnit']);
        $num_rows = $this->db->get('item')->num_rows();

        if( $num_rows > 0 ){
            $match = 1;
        } else {
            /* SOFT DELETE ONLY */
            $this->db->set('archived', 1, false );
            $this->db->where('idUnit', $params['idUnit'] );
            $this->db->update('unit');

            // $this->db->delete( 'unit', array('idUnit' => $params['idUnit'] ));
        }

        return $match;
    }
}