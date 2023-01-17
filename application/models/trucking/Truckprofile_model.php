<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Truckprofile_model extends CI_Model {
	
    public function getTruckProfiles($params){ 
        $this->db->select('a.idTruckProfile, a.plateNumber, b.truckType as type, b.idTruckType, a.axle, 
        a.color, a.dateAcquired, a.dateDeployment, a.truckFront, a.truckBack, a.truckOR, a.truckCR, a.truckLTFRB, a.status,
        (CASE WHEN a.status = 1 THEN "Leased" ELSE "Owned" END) as listStatus, 
        a.capacity, a.currentmileage, a.totalWorkingHours, a.model, a.make, a.inactive');

        if( isset( $params['idTruckProfile'] ) ) {
            $this->db->where( 'idTruckProfile', $params['idTruckProfile'] );
        }

        $this->db->from( 'truckprofile AS a' );
        $this->db->join( 'trucktype AS b', 'b.idTruckType = a.idTruckType', 'left inner' );

        return $this->db->get()->result_array();
	}

    public function saveProfile($params) { 
        if( isset( $params['onEdit'] ) && $params['onEdit'] == 1 ){
            $this->db->where( 'idTruckProfile', $params['idTruckProfile'] );
            $this->db->update( 'truckprofile', unsetParams( array_filter($params), 'truckprofile' ) );
            return $params['idTruckProfile'];
            
        } else {
            $this->db->insert( 'truckprofile', unsetParams( $params, 'truckprofile' ));

            return $this->db->insert_id(); 
        }
	}

    public function savePartDetails( $params ) {
        $this->db->insert('truckparts', $params);
    }

    public function deletePartDetails( $params ) {
        $this->db->delete('truckparts',array('idTruckProfile'=>$params));
    }

    public function deleteTruckProfile($params){ 
        if($this->is_exist($params['idTruckProfile'] ,'idTruckProfile' ,'truckregistrationhistory')) return 1;
        if($this->is_exist($params['idTruckProfile'] ,'idTruckProfile' ,'tireprofile'))              return 1;
        if($this->is_exist($params['idTruckProfile'] ,'idProject'      ,'deliveryticket'))           return 1;
        if($this->is_exist($params['idTruckProfile'] ,'idTruckProfile' ,'rental'))                   return 1;

        $this->db->delete( 'truckprofile', unsetParams( $params, 'truckprofile' ));
        return 0;
	}

    function getTruckPartDetails($params) {
        $this->db->select('truckPartName, dueDate, dateInstalled');
        $this->db->where( 'idTruckProfile', $params['id'] );

        return $this->db->get('truckparts')->result_array();
    }

    function getAlbum($data){
		$this->db->select('filename');
		$this->db->where('temp',1);
		$this->db->where('idEu',$this->USERID);
        $temp = $this->db->get('truckalbum')->result_array(); 
		
		$this->db->select('filename');
		$this->db->where('temp',0);
		$this->db->where('position',0);
		$this->db->where('idTruckProfile',$data['idTruckProfile']);
        $photo = $this->db->get('truckalbum')->result_array(); 
		
		return array_merge($temp,$photo);
    }

    public function getTruckTypeItems($params){ 
        $this->db->select();
     
        if( isset( $params['filterValue'] ) ) {
            $this->db->like('truckType', $params['filterValue'], 'after');
        }

        return $this->db->get('trucktype')->result_array();
	}

    function partDetailsGrid($params){
		$this->db->select("truckPartName, dueDate, dateInstalled");
        if( isset( $params['idTruckProfile'] ) ) {
            $this->db->where('idTruckProfile', $params['idTruckProfile']);
        }
        return $this->db->get('truckparts')->result_array();
	}

    function uploadTempPIC($data){
		$this->db->insert('truckalbum', $data);
	}

    function unTemp( $id ) {
        // Get temp album files
		$this->db->select('filename');
		$this->db->where('idTruckProfile',null);
		$this->db->where('temp',1);
		$this->db->where('idEu',$this->USERID);
        $files = $this->db->get('truckalbum')->result_array();

        $base_path = 'images/truck/' . $id . '/';

        // check if folder exist if wla mag create og bag o
        if (!is_dir($base_path)) mkdir($base_path, 0777, TRUE);

		foreach($files as $file){
            
            $image_path = $base_path . $file['filename'];
            $temp_path = 'images/truck/temp/' . $file['filename'];

            if(rename($temp_path, $image_path)){
				$this->db->where('temp',1);
				$this->db->where('idEu',$this->USERID);
				$this->db->update('truckalbum',array('temp'=>0,'idTruckProfile'=>$id));
			}
		}
    }

    function clearTemp(){
		$this->db->select('filename, idPhoto');
		$this->db->where('temp',1);
		$this->db->where('idEu', $this->USERID);
        $file = $this->db->get('truckalbum')->result_array();
		
		foreach($file as $data){
			if($data['idPhoto']){
				unlink('images/truck/temp/'.$data['filename']);
				$this->db->delete('truckalbum',array('idPhoto'=>$data['idPhoto']));
			}
		}
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



