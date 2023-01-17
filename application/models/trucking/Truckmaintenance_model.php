<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Developer: Christian P. Daohog
 * Module: Truck Maintenance
 * Date: Dec 04, 2021
 * Finished: 
 * Description: This module allows authorized users to record a maintenance transaction in the system.
 * DB Tables: 
 * */

class Truckmaintenance_model extends CI_Model {

    public function getTruckMaintenance( $params ){
        $this->db->select('
            a.idTruckMaintenance,
            a.idTruckProfile,
            a.date,
            a.remarks,
            b.idInvoice,
            b.idCostCenter,
            b.idReference,
            b.idAffiliate, 
            CONVERT( b.date, DATE ) as tdate, 
            TIME_FORMAT( CONVERT( b.date, TIME ), "%h:%i %p") as ttime, 
            b.pCode,
            b.referenceNum,
            concat(c.code, "-", b.referenceNum) as referenceNum,
            c.code,
            d.idTruckType,
            d.plateNumber,
            d.model,
            e.truckType as type,
            f.odometer 
        ');

        if(isset($params['idTruckMaintenance'])) $this->db->where('idTruckMaintenance', $params['idTruckMaintenance']);
        
        $this->db->from('truckmaintenance as a');
        $this->db->join('invoices         as b', 'b.idInvoice = a.idInvoice',           'left');
        $this->db->join('reference        as c', 'c.idReference = b.idReference',       'left');
        $this->db->join('truckprofile     as d', 'd.idTruckProfile = a.idTruckProfile', 'left');
        $this->db->join('trucktype        as e', 'e.idTruckType = d.idTruckType',       'left');
        $this->db->join('deliveryticket   as f', 'f.idTruckProfile = d.idTruckProfile', 'left');
        
        return $this->db->get()->result_array();
    }

    public function saveTruckMaintenance( $params ) {

        if( isset($params['onEdit']) && $params['onEdit'] ) {
            $this->db->where('idTruckMaintenance', $params['idTruckMaintenance']);
            $this->db->update('truckmaintenance', unsetParams( $params, 'truckmaintenance' ) );
            $idTruckMaintenance = $params['idTruckMaintenance'];

        } else { 
            $this->db->insert( 'truckmaintenance', unsetParams( $params, 'truckmaintenance' ) );
            $idTruckMaintenance = $this->db->insert_id();
        }

        return $idTruckMaintenance;
    }
    
    public function deleteTruckMaintenance( $params ) {
        $tables = array('truckmaintenancefiltersparts' ,'truckmaintenancetires' ,'truckmaintenanceothers' ,'truckmaintenance');
        $this->db->where('idTruckMaintenance', $params['idTruckMaintenance']);
        $this->db->delete($tables);

        return 0;
    }

    public function getTruckType($params){
        $this->db->select('a.idTruckType, a.truckType');
        $this->db->from('trucktype as a');
        
        if( isset( $params['filterValue'] ) ) {
            $this->db->join( 'truckprofile as b', 'b.idTruckType = a.idTruckType', 'left' );
            $this->db->where('b.idTruckProfile', $params['filterValue']);
        }

        return $this->db->get()->result_array();
	}

    public function getFilterParts($params, $type=null, $filterPartName = null){
        $this->db->select();
        $this->db->where('idTruckMaintenance', $params['idTruckMaintenance']);
        if(isset($type))            $this->db->where('filterPartsType', $type);
        if(isset($filterPartName))  $this->db->where('filterPartName', $filterPartName);

        return $this->db->get('truckmaintenancefiltersparts')->result_array();
	}

    public function getTire($params){
        if(!isset($params['idTruckMaintenance']) || empty($params['idTruckMaintenance'])) return array();
        
        $this->db->select(' idTruckMaintenanceTires, mileage, thickness, remarks, damage, original, recap, number, serialNumber, dateInstalled ');
        $this->db->where('idTruckMaintenance', $params['idTruckMaintenance']);
        return $this->db->get('truckmaintenancetires')->result_array();
	}

    public function getOthers($params){
        if(!isset($params['idTruckMaintenance']) || empty($params['idTruckMaintenance'])) return array();

        $this->db->select(' idTruckMaintenanceOthers, maintenanceType, description, dateChangeInstalled, mileage, remarks, damage ');
        $this->db->where('idTruckMaintenance', $params['idTruckMaintenance']);
 
        return $this->db->get('truckmaintenanceothers')->result_array();
	}

    public function getPlateNumber($params){
        $this->db->select('idTruckProfile, plateNumber, idTruckType');
        if( isset( $params['filterValue'] ) ) $this->db->where('idTruckType', $params['filterValue']);
        return $this->db->get('truckprofile')->result_array();
	}

    public function getOdometer($params){
        $this->db->select('odometer');

        if( isset( $params['filterValue'] ) ) $this->db->where('idTruckProfile', $params['filterValue']);

        $this->db->from('deliveryticket');
        $this->db->order_by('idDeliveryTicket', 'DESC');
        $this->db->limit(1);

        return $this->db->get()->result_array();
	}

    function saveInvoice( $params ){
        $params = (array) $params;

        if( isset($params['onEdit']) && $params['onEdit'] == 1 ) {
            $this->db->where('idInvoice', $params['idInvoice']);
            $this->db->update('invoices', unsetParams( $params, 'invoices' ) );
            $idInvoice = $params['idInvoice'];

            $this->db->insert( 'invoiceshistory', unsetParams( $params, 'invoiceshistory' ) );
        } else {
            if( isset( $params['idInvoice'] ) ) unset( $params['idInvoice'] );
            $this->db->insert( 'invoices', unsetParams( $params, 'invoices' ) );
            $idInvoice = $this->db->insert_id();
        }

        return $idInvoice;
    }

    public function saveTire( $params ) {
        if( isset($params['onEdit']) && $params['onEdit'] && !empty($params['idTruckMaintenanceTires'])) {
            $this->db->where('idTruckMaintenanceTires', $params['idTruckMaintenanceTires']);
            $this->db->update('truckmaintenancetires', unsetParams( $params, 'truckmaintenancetires' ) );
        } else {
            $this->db->insert( 'truckmaintenancetires', unsetParams( $params, 'truckmaintenancetires' ) );        
        }

    }
    
    public function saveOthers( $params ) {
        if( isset($params['onEdit']) && $params['onEdit'] && !empty($params['idTruckMaintenanceOthers']) ) {
            $this->db->where('idTruckMaintenanceOthers', $params['idTruckMaintenanceOthers']);
            $this->db->update('truckmaintenanceothers', unsetParams( $params, 'truckmaintenanceothers' ) );
        } else {
            $this->db->insert( 'truckmaintenanceothers', unsetParams( $params, 'truckmaintenanceothers' ) );        
        }
    }

    public function saveFilterParts( $params ) {
        $this->db->insert( 'truckmaintenancefiltersparts', unsetParams( $params, 'truckmaintenancefiltersparts' ) );
    } 

    public function deleteFilterParts($params) {
        $this->db->where('idTruckMaintenance', $params);
        $this->db->delete('truckmaintenancefiltersparts');
    }

    public function is_exist($name, $name_col, $table) {
        $this->db->where($name_col, $name);
		$this->db->limit(1);
		$this->db->from($table);

        $is_archived = $this->db->query("SHOW COLUMNS FROM ".$table." LIKE 'archive'")->result_array();
		if($is_archived) $this->db->where_not_in('archive',1);

		return $this->db->count_all_results();
    }
} 
