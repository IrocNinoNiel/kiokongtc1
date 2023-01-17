<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Developer: Christian P. Daohog
 * Module: Fuel Monitoring
 * Date: Jan 19, 2022
 * Finished:
 * Description: This module allows authorized users to generate a monitoring of fuels of the trucks used.
 * DB Tables: 
 * */ 

class Fuelmonitoring_model extends CI_Model {

    public function getFuel($params) {
        $deliveryticketactivity = $this->getDeliveryTicket($params);
        $rental                 = $this->getRental($params);

        return $this->db->query($deliveryticketactivity . ' UNION ALL ' . $rental . ' ORDER BY `date` DESC')->result_array();
    }

    public function getDeliveryTicket( $params ) {

        $this->db->select("
             affiliate.affiliateName 
            ,affiliate.sk as affiliateSK
            ,convert(invoices.date, date) as date
            ,truckprofile.plateNumber
            ,trucktype.truckType
            ,deliveryticketactivity.fuelConsumed 
        ");

        $this->db->from('deliveryticketactivity');
        $this->db->join('deliveryticket', 'deliveryticket.idDeliveryTicket = deliveryticketactivity.idDeliveryTicket',  'LEFT INNER');
        $this->db->join('trucktype',      'trucktype.idTruckType = deliveryticket.idTruckType',                         'LEFT OUTER');
        $this->db->join('truckprofile',   'truckprofile.idTruckProfile = deliveryticket.idTruckProfile',                'LEFT OUTER');
        $this->db->join('invoices',       'invoices.idInvoice = deliveryticket.idInvoice',                              'LEFT OUTER');
        $this->db->join('affiliate',      'affiliate.idAffiliate = invoices.idAffiliate',                               'LEFT OUTER');

        if(isset($params['idAffiliate']) && (int) $params['idAffiliate'] != 0) $this->db->where('invoices.idAffiliate',        $params['idAffiliate']); 
        if(isset($params['truckType'])   && (int) $params['truckType']   != 0) $this->db->where('deliveryticket.idTruckType',  $params['truckType']);
        if(isset($params['plateNumber']) && (int) $params['plateNumber'] != 0) $this->db->where('truckprofile.idTruckProfile', $params['plateNumber']);
        if(isset($params['driversName']) && (int) $params['driversName'] != 0) $this->db->where('deliveryticket.idDriver',     $params['driversName']);

        $this->db->where( 'invoices.date >=', $params['sdate']);
        $this->db->where( 'invoices.date <', date('Y-m-d', strtotime($params['edate'] . ' +1 day')));
        $this->db->where( 'invoices.archived', 0);

        return $this->db->get_compiled_select();
    }

    public function getRental( $params ) {
        $this->db->select("
             affiliate.affiliateName 
            ,affiliate.sk as affiliateSK
            ,convert(invoices.date, date) as date
            ,truckprofile.plateNumber
            ,trucktype.truckType
            ,(case when rental.returnFuelLevel is null 
                then 0 
                else (rental.fuelLevel - rental.returnFuelLevel) 
            end) as fuelConsumed
        ");

        $this->db->from('rental');
        $this->db->join('trucktype',      'trucktype.idTruckType = rental.idTruckType',             'LEFT OUTER');
        $this->db->join('truckprofile',   'truckprofile.idTruckProfile = rental.idTruckProfile',    'LEFT OUTER');
        $this->db->join('invoices',       'invoices.idInvoice = rental.idInvoice',                  'LEFT OUTER');
        $this->db->join('affiliate',      'affiliate.idAffiliate = invoices.idAffiliate',           'LEFT OUTER');

        if(isset($params['idAffiliate']) && (int) $params['idAffiliate'] != 0) $this->db->where('invoices.idAffiliate',         $params['idAffiliate']); 
        if(isset($params['truckType'])   && (int) $params['truckType']   != 0) $this->db->where('rental.idTruckType',           $params['truckType']);
        if(isset($params['driversName']) && (int) $params['driversName'] != 0) $this->db->where('rental.idDriver',              $params['driversName']);
        if(isset($params['plateNumber']) && (int) $params['plateNumber'] != 0) $this->db->where('truckprofile.idTruckProfile',  $params['plateNumber']);

        $this->db->where( 'invoices.date >=', $params['sdate']);
        $this->db->where( 'invoices.date <', date('Y-m-d', strtotime($params['edate'] . ' +1 day')));
        $this->db->where( 'invoices.archived', 0);

        return $this->db->get_compiled_select();
    }

    public function getTruckTypes() {
        $this->db->select('idTruckType as id, truckType as name');
        return $this->db->get('trucktype')->result_array();
    }

    public function getPlateNumbers() {
        $this->db->select('idTruckProfile as id, plateNumber as name');
        return $this->db->get('truckprofile')->result_array();
    }

    public function getDrivers( $params ){
        $this->db->select("employee.idEmployee AS id, employee.name, employee.sk");
        $this->db->join("employment", "employment.idEmployee = employee.idEmployee", "left");
        $this->db->where('employee.archived', 0 );
        $this->db->where('employment.classification', 42 );
        return $this->db->get('employee')->result_array();
    }

}




