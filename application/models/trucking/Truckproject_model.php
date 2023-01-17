<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Truckproject_model extends CI_Model {

    public function getTruckProjects($params){ 
        $this->db->select('
                LPAD( a.idTruckProject, 4, 0) as idTruckProject, 
                IF(a.isManual = 1, a.idManual, LPAD( a.idTruckProject, 4, 0)) AS idProject, 
                a.idManual, 
                a.projectName, 
                a.idCustomer, 
                a.remarks, 
                a.isManual, 
                a.status, 
                b.name, 
                b.sk ');

        $this->db->where_not_in( 'a.archived', 1 );

        if( isset( $params['filterValue'] ) ) {
            $this->db->like('projectName', $params['filterValue'], 'after');
        }

        $this->db->from( 'truckproject AS a' );
        $this->db->join( 'customer AS b', 'b.idCustomer = a.idCustomer', 'left' );  

        return $this->db->get()->result_array();
	}

    public function getTruckProject($params){ 
        $this->db->select('
                LPAD( a.idTruckProject, 4, 0) as idTruckProject, 
                group_concat(c.idAffiliate) as idAffiliate, 
                a.idManual, 
                a.projectName, 
                a.idCustomer, 
                a.remarks, 
                a.status, 
                a.isManual,
                b.name, 
                b.sk ');

        $this->db->where_not_in( 'a.archived', 1 );
        $this->db->where('a.idTruckProject', $params['idTruckProject']);

        $this->db->from( 'truckproject AS a' );
        $this->db->join( 'customer AS b', 'b.idCustomer = a.idCustomer', 'left' );
        $this->db->join('truckprojectaffiliate as c','a.idTruckProject = c.idTruckProject','INNER');
        return $this->db->get()->result_array();
	}

    function getCustomers($params){
        $this->db->select("customer.idCustomer, customer.name, customer.sk");
        $this->db->where("customer.archived", 0);
        if( isset( $params['affiliates'] ) ) $this->db->where_in('idAffiliate', json_decode($params['affiliates'],true));

        $this->db->join('customer', 'customer.idCustomer = customeraffiliate.idCustomer', 'LEFT');
        $this->db->group_by('customer.idCustomer');

        return $this->db->get("customeraffiliate")->result_array();
    }

    public function getCode(){
        $this->db->select("LPAD( IFNULL(MAX(idTruckProject),0)+1, 4, 0) as idTruckProject");
        return $this->db->get('truckproject')->result_array();
	}

    public function saveTruckProject( $params ) {
        if( isset( $params['onEdit']) && $params['onEdit'] == 1 ){
            $this->db->where( 'idTruckProject', $params['idTruckProject'] );
            $this->db->update( 'truckproject', unsetParams( $params, 'truckproject' ) );
            $id = $params['idTruckProject'];
        } else {
            $this->db->insert( 'truckproject', unsetParams( $params, 'truckproject'));
            $id = $this->db->insert_id(); 
            
        }
        return $id;
    }

    public function saveTruckProjectAffiliate( $params ) {
        $this->db->delete( 'truckprojectaffiliate', array('idTruckProject' => $params[0]['idTruckProject'] ));
        $this->db->insert_batch( 'truckprojectaffiliate', $params );
    }

    public function deleteTruckProject($params){
        $match = 0;

        $num_rows_ticket = $this->is_exist($params['idTruckProject'], 'idProject', 'deliveryticket');
        $num_rows_rental = $this->is_exist($params['idTruckProject'], 'idProject', 'rental');

        if($num_rows_ticket || $num_rows_rental) {
            $match = 1;
        } else {
            $this->db->set( 'archived', 1 );
            $this->db->where( 'idTruckProject', (int)$params['idTruckProject'] );
            $this->db->update( 'truckproject' ); 
        }

        return $match;
	}

    public function is_id_exists( $params ) {
        $this->db->select( 'idTruckProject');
        $this->db->where('idManual', $params['idManual']);
        $this->db->limit(1);
        $this->db->from('truckproject');
        $result = $this->db->get()->result_array();
        $total  = $this->db->count_all_results();

        return $total > 0? $result : false;
    }

    public function is_exist($name, $name_col, $table) {
        $this->db->where($name_col, $name);
		$this->db->limit(1);
		$this->db->from($table);

        $is_archived = $this->db->query("SHOW COLUMNS FROM ".$table." LIKE 'archive'")->result_array();
		if($is_archived)$this->db->where_not_in('archive',1);

		return $this->db->count_all_results();
    }
}