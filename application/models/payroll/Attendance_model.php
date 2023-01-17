<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attendance_model extends CI_Model {

    function getConstructionProject( $params ) {
        $this->db->select('idConstructionProject as id, projectName as name');
        $this->db->order_by('projectName ASC');
        return $this->db->get('constructionproject')->result_array();
    }

    function getTruckingProject( $params ) {
        $this->db->select('idTruckProject as id, projectName as name');
        $this->db->order_by('projectName ASC');
        return $this->db->get('truckproject')->result_array();
    }

    function getEmployees( $params ){
        $this->db->select("idEmployee, name as employeeName, sk as employeeSK");
        $this->db->where('archived', 0 );
        $this->db->order_by('name asc');
        return $this->db->get('employee')->result_array();
    }

    function getClassCode2( $params ){
        $this->db->select("LPAD( IFNULL(MAX(idItemClass),0)+1, 5, 0) as idItemClass");
        return $this->db->get('itemclassification')->row()->idItemClass;
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

}




