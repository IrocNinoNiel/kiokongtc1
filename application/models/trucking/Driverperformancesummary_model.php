<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Developer: Christian P. Daohog
 * Module: Driver Performance Summary
 * Date: Jan 21, 2022
 * Finished:
 * Description: This module allows authorized users to generate a performance of the drivers.
 * DB Tables:
 * */

class Driverperformancesummary_model extends CI_Model {

    public function getPerformance($params) {
        $activity   = $this->getDeliveryTicket($params);
        $rental     = $this->getRental($params);

        return $this->db->query($activity . ' UNION ALL ' . $rental . ' ORDER BY `date` DESC')->result_array();
    }

    public function getDeliveryTicket($params) {
        $is_construction = !$params['projectType'];

        $this->db->select('
            CONVERT(invoices.date, date) as date
            ,location.locationName as area
            ,"" as trip
            ,activity.noOfLoads
            ,activity.fuelConsumed
            ,activity.lubricant
        ');

        $this->db->from('deliveryticketactivity as activity');
        $this->db->join('deliveryticket',   'deliveryticket.idDeliveryTicket = activity.idDeliveryTicket',  'LEFT OUTER');
        $this->db->join('trucktype',        'trucktype.idTruckType = deliveryticket.idTruckType',           'LEFT OUTER');
        $this->db->join('truckprofile',     'truckprofile.idTruckProfile = deliveryticket.idTruckProfile',  'LEFT OUTER');
        $this->db->join('invoices',         'invoices.idInvoice = deliveryticket.idInvoice',                'LEFT OUTER');
        $this->db->join('affiliate',        'affiliate.idAffiliate = invoices.idAffiliate',                 'LEFT OUTER');
        $this->db->join('location',         'location.idLocation = activity.idLocation',                    'LEFT OUTER');

        if($is_construction) {
            $this->db->join('constructionproject', 'constructionproject.idConstructionProject = deliveryticket.idProject', 'LEFT OUTER');
        } else {
            $this->db->join('truckproject',        'truckproject.idTruckProject = deliveryticket.idProject', 'LEFT OUTER');
        }

        if(isset($params['idAffiliate']) && $params['idAffiliate'] != 0) $this->db->where('invoices.idAffiliate',        $params['idAffiliate']);
        if(isset($params['truckType'])   && $params['truckType']   != 0) $this->db->where('deliveryticket.idTruckType',  $params['truckType']);
        if(isset($params['plateNumber']) && $params['plateNumber'] != 0) $this->db->where('truckprofile.idTruckProfile', $params['plateNumber']);
        if(isset($params['driversName']) && $params['driversName'] != 0) $this->db->where('deliveryticket.idDriver',     $params['driversName']);

        $projectTable = $is_construction? 'constructionproject.idConstructionProject' : 'truckproject.idTruckProject';
        if(isset($params['projectName']) && $params['projectName'] != 0)  $this->db->where($projectTable, $params['projectName']);

        $this->db->where('deliveryticket.isConstruction', $is_construction);
        $this->db->where( 'invoices.date >=', $params['sdate']);
        $this->db->where( 'invoices.date <', date('Y-m-d', strtotime($params['edate'] . ' +1 day')));
        $this->db->where( 'invoices.archived', 0);

         return $this->db->get_compiled_select();
    }

    public function getRental($params) {
        $is_construction = !$params['projectType'];

        $this->db->select('
            convert(invoices.date, date) as date
            ,"" as area
            ,rental.trip
            ,"" as noOfLoads
            ,(rental.fuelLevel - rental.returnFuelLevel) as fuelConsumed
            ,"" as lubricant
        ');

        $this->db->from('rental');
        $this->db->join('invoices',     'invoices.idInvoice = rental.idInvoice',                'LEFT OUTER');
        $this->db->join('affiliate',    'affiliate.idAffiliate = invoices.idAffiliate',         'LEFT OUTER');
        $this->db->join('truckprofile', 'truckprofile.idTruckProfile = rental.idTruckProfile',  'LEFT OUTER');
        $this->db->join('trucktype',    'trucktype.idTruckType = rental.idTruckType',           'LEFT OUTER');

        if($is_construction) {
            $this->db->join('constructionproject', 'constructionproject.idConstructionProject = rental.idProject', 'LEFT OUTER');
        } else {
            $this->db->join('truckproject',        'truckproject.idTruckProject = rental.idProject', 'LEFT OUTER');
        }

        $projectTable = $is_construction? 'constructionproject.idConstructionProject' : 'truckproject.idTruckProject';
        if(isset($params['projectName']) && $params['projectName'] != 0) $this->db->where( $projectTable,                    $params['projectName'] );
        if(isset($params['idAffiliate']) && $params['idAffiliate'] != 0) $this->db->where( 'invoices.idAffiliate',           $params['idAffiliate'] );
        if(isset($params['truckType'])   && $params['truckType']   != 0) $this->db->where( 'rental.idTruckType',             $params['truckType']   );
        if(isset($params['plateNumber']) && $params['plateNumber'] != 0) $this->db->where( 'truckprofile.idTruckProfile',    $params['plateNumber'] );
        if(isset($params['driversName']) && $params['driversName'] != 0) $this->db->where( 'idDriver.idEmployee',            $params['driversName'] );

        $this->db->where( 'rental.isConstruction', $is_construction);
        $this->db->where( 'rental.dateFrom >=', $params['sdate']);
        $this->db->where( 'rental.dateTo <', date('Y-m-d', strtotime($params['edate'] . ' +1 day')));
        $this->db->where( 'invoices.archived', 0);

        return $this->db->get_compiled_select();
    }

    public function getTruckTypes() {
        $this->db->select('idTruckType as id, truckType as name');
        return $this->db->get('trucktype')->result_array();
    }

    public function getProjectNames($params) {
        if($params['isConstruction']) {
            // Construction
            $this->db->select('idConstructionProject as id, projectName as name');
            $result = $this->db->get('constructionproject')->result_array();
        }  else {
            // Trucking
            $this->db->select('idTruckProject as id, projectName as name');
            $this->db->from('truckproject');
            $this->db->where_not_in('archived',1);
            $result = $this->db->get()->result_array();
        }
        return $result;
    }

    public function getPlateNumbers() {
        $this->db->select('idTruckProfile as id, plateNumber as name');
        $this->db->where_not_in('archived',1);
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


