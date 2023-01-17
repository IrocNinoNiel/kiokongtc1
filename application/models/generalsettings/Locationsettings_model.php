<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Hazel Alegbeleye
 * Module: Location Settings
 * Date: December 4, 2019
 * Finished: December 4, 2019
 * Description: This module allows authorized user to set up the location that will be used in transactions.
 * DB Tables: location, affiliate
 * */ 
class Locationsettings_model extends CI_Model {

    function getLocationCode( $params ){
        $this->db->select(' LPAD( max( idLocation)+1, 5, 0) as idLocation');
        return $this->db->get('location')->result_array();
    }

    function getLocations( $params ){
        $this->db->select('idLocation, LPAD( locationCode, 5, 0) as locationCode, locationName');

        if( isset( $params['filterValue'] ) ) {
            $this->db->like('locationName', $params['filterValue'], 'after');
        }

        $this->db->from('location');
        $params['db'] = $this->db;
        $params['order_by'] = 'idLocation asc';
        return getGridList($params);
    }

    function saveLocation( $params ){

        $this->db->select('*');
        $this->db->where('locationName', $params['locationName']);
        $num_rows = $this->db->get('location')->num_rows();

        if( isset( $params['onEdit'] ) && $params['onEdit'] == 0 ) {
            if( $num_rows > 1 ) {
                $msg = $params['locationName'] . ' already exists.';
                $match = 1;
            } else {
                $this->db->where( 'idLocation' , $params['idLocation'] );
                unset( $params['idLocation'] );
                $this->db->update( 'location', unsetParams( $params, 'location' ));
                
                $msg = 'SAVE_SUCCESS';
                $match = 0;
            }
        } else {
            if( $num_rows <= 0 ) {
                $this->db->select('*');
                $this->db->where('idLocation', $params['idLocation']);
                $num_rows = $this->db->get('location')->num_rows();
    
                if( $num_rows > 0 ) {
                    $msg = 'Location code already exists. Would you like to generate a new one?';
                    $match = 2;
                } else {
                    $this->db->insert( 'location', unsetParams( $params, 'location') );
    
                    $msg = 'SAVE_SUCCESS';
                    $match = 0;
                }
            } else {
                $msg = $params['locationName'] . ' already exists.';
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
        $this->db->select('idLocation, LPAD( max( idLocation)+1, 5, 0) as locationCode, locationName');
        $this->db->where('idLocation', $params['idLocation']);
        return $this->db->get('location')->result_array();
    }

    function deleteLocation( $params ){
        $this->db->select('*');
        $this->db->where('location', $params['idLocation']);
        $num_rows = $this->db->get('affiliate')->num_rows();

        if( $num_rows > 0 ){
            $msg = 'DELETE_USED';
            $match = 1;
        } else {
            $this->db->delete( 'location', array('idLocation' => $params['idLocation'] ));

            $match = 0;
            $msg = 'DELETE_SUCCESS';
        }

        return array(
            'match' => $match
            ,'msg' => $msg
        );

    }
}