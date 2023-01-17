<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tireprofile_model extends CI_Model {

    function gridRecap( $params ){
		$this->db->select('recapDate, recapValue');
		$this->db->where('idTireProfile', $params['id']);
		return $this->db->get('tireadditional')->result_array();
	}

	function getTireProfiles( $params ) {
		$this->db->select( 'a.idTireProfile as id, 
							a.dateAcquired, 
							a.serialNumber, 
							a.remarks, 
							a.tireSize, 
							b.plateNumber, 
							c.recapValue');
		
        if(isset( $params['filterValue'] )) $this->db->like('a.serialNumber', $params['filterValue'], 'both');
        
		$this->db->where_not_in('a.archived',1);
		$this->db->order_by('a.dateAcquired');
		
		$this->db->from( 'tireprofile AS a' );
        $this->db->join( 'truckprofile AS b', 'b.idTruckProfile = a.idTruckProfile', 'left inner' );
		$this->db->join( 'tireadditional AS c', 'c.idTireProfile = a.idTireProfile', 'left' );
		return $this->db->get()->result_array();
	}

	function getTireProfile( $params ){
		$this->db->select('	a.idTireProfile, 
							a.serialNumber, 
							a.brandName, 
							a.tireSize, 
							a.remarks, 
							a.dateAcquired,
							b.idTruckProfile as code,
							b.plateNumber as name');
		
		$this->db->from( 'tireprofile AS a' );
        $this->db->join( 'truckprofile AS b', 'b.idTruckProfile = a.idTruckProfile', 'left inner' );
		$this->db->where('a.idTireProfile', $params['idTireProfile']);
        $this->db->where_not_in('a.archived',1);
		return $this->db->get()->result_array();
	}

    function getTruckProfile($params){
		$this->db->select('plateNumber as name, idTruckProfile as code, axle');
		
        if(isset( $params['filterValue'] )) $this->db->like('plateNumber', $params['filterValue'],'both');
		
        $this->db->where_not_in('archived',1);
		$this->db->order_by('plateNumber');

		return $this->db->get('truckprofile')->result_array();
	}

    function saveTire($params) {
			if( isset( $params['onEdit']) && $params['onEdit'] == 1 ){
				$this->db->where( 'idTireProfile', $params['idTireProfile'] );
				$this->db->update( 'tireprofile', unsetParams( $params, 'tireprofile' ) );
				return $params['idTireProfile'];
			} else {
				$this->db->insert( 'tireprofile', unsetParams( $params, 'tireprofile' ));
	
				return $this->db->insert_id(); 
			}
    }

	function saveRecap($params) {
		$this->db->insert_batch('tireadditional',$params);
	}

	function deleteRecap($params) {
		$this->db->delete('tireadditional',array('idTireProfile'=>$params));
	}

	function deleteTireProfile($params) {
        $this->db->set( 'archived', 1 );
        $this->db->where( 'idTireProfile', (int)$params['idTireProfile'] );
        $this->db->update( 'tireProfile' );
	}

	function isSerialExist($params) {
		$isExist = false;
		$this->db->select('idTireProfile');
		$this->db->where('serialNumber', $params['serialNumber']);

		$result = $this->db->get('tireprofile')->result_array();
		if($result) {
			$isExist = $result[0]['idTireProfile'] != $params['idTireProfile'];
		}

		return $isExist;
	}
}