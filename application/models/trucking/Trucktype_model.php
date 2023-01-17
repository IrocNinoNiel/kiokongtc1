<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Trucktype_model extends CI_Model {
	
	public function getTruckTypeItems($params){ 
        $this->db->select('LPAD( idTruckType, 4, 0) as idTruckType, truckType');

        if( isset( $params['filterValue'] ) ) {
            $this->db->like('truckType', $params['filterValue'], 'after');
        }

        return $this->db->get('trucktype')->result_array();
	}

    public function getTruckType($params){
        $this->db->select('LPAD( idTruckType, 4, 0) as idTruckType, truckType');
        $this->db->where( 'idTruckType', $params['idTruckType'] );
 
        return $this->db->get('trucktype')->result_array();
	}

    public function saveTruckType($params){
        if( isset( $params['isEdit']) && $params['isEdit'] == 1 ){
            $this->db->where( 'idTruckType', $params['idTruckType'] );
            $this->db->update( 'trucktype', unsetParams( $params, 'trucktype' ) );
            
        } else {
            $this->db->insert( 'trucktype', unsetParams( $params, 'trucktype' ));

            return $this->db->insert_id(); 
        }
	}

    public function deleteTruckType($params){
        if($this->is_exist($params['idTruckType'], 'idTruckType', 'truckprofile'))      return 1;
        if($this->is_exist($params['idTruckType'], 'idTruckType', 'deliveryticket'))    return 1;
        if($this->is_exist($params['idTruckType'], 'idTruckType', 'rental'))            return 1;

        $this->db->delete( 'trucktype', unsetParams( $params, 'trucktype' ));

        return 0;
	}

    public function is_exist($name, $name_col, $table) {
        $this->db->where($name_col, $name);
		$this->db->limit(1);
		$this->db->from($table);

        $is_archived = $this->db->query("SHOW COLUMNS FROM ".$table." LIKE 'archive'")->result_array();
		if($is_archived)$this->db->where_not_in('archive',1);

		return $this->db->count_all_results();
    }

    public function getCode(){
        $this->db->select("LPAD( IFNULL(MAX(idTruckType),0)+1, 4, 0) as idTruckType");
        return $this->db->get('trucktype')->result_array();
	}
} 
