<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Developer: Christian P. Daohog
 * Module: Project Accomplishment
 * Date: Jan 26, 2021
 * Finished:
 * Description: This allows the authorized users to record an accomplishment report of a project given to them by the contractor.
 * DB Tables:
 * */

class Projectaccomplishment_model extends CI_Model {

    function getAccomplishment($params) {
        $this->db->select('
            affiliate.affiliateName
            ,affiliate.sk as affiliateSK
            ,convert(invoices.date, date) as date
            ,concat(reference.code, "-", invoices.referenceNum) as referenceNum 
            ,project.projectName
            ,"empty" as contractorName
            ,accomplishment.idConstructionProject as idContract
            ,accomplishment.idConsProjectAccomplishment as idAccomplishment
            ,accomplishment.dateFrom
            ,accomplishment.dateTo ');

        $this->db->from('consprojectaccomplishment as accomplishment');
        $this->db->join('constructionproject as project',   'project.idConstructionProject = accomplishment.idConstructionProject', 'LEFT OUTER');
        $this->db->join('invoices',                         'invoices.idInvoice = accomplishment.idInvoice',                        'LEFT OUTER');
        $this->db->join('affiliate',                        'affiliate.idAffiliate = invoices.idAffiliate',                         'LEFT OUTER');
        $this->db->join('reference',                        'reference.idReference = invoices.idReference',                         'LEFT OUTER');
        return $this->db->get()->result_array();
    }

    function retrieveAccomplishment($params) {
        $this->db->select('
            ,accomplishment.idConsProjectAccomplishment as idAccomplishment
            ,accomplishment.dateFrom as sdate
            ,accomplishment.dateTo as edate
            ,project.projectName
            ,project.idConstructionProject
            ,"empty" as contractor
            ,affiliate.affiliateName
            ,affiliate.sk as affiliateSK
            ,invoices.idInvoice
            ,invoices.idCostCenter
            ,invoices.idReference
            ,invoices.idAffiliate
            ,invoices.pCode
            ,invoices.referenceNum
            ,TIME_FORMAT( CONVERT( invoices.date, TIME ), "%h:%i %p") as ttime
            ,CONVERT(invoices.date, DATE) as tdate');

        $this->db->from('consprojectaccomplishment as accomplishment');
        $this->db->join('constructionproject as project',   'project.idConstructionProject = accomplishment.idConstructionProject', 'LEFT OUTER');
        $this->db->join('invoices',                         'invoices.idInvoice = accomplishment.idInvoice',                        'LEFT OUTER');
        $this->db->join('affiliate',                        'affiliate.idAffiliate = invoices.idAffiliate',                         'LEFT OUTER');
        $this->db->join('reference',                        'reference.idReference = invoices.idReference',                         'LEFT OUTER');

        if(isset($params['idAccomplishment'])) $this->db->where('accomplishment.idConsProjectAccomplishment',  $params['idAccomplishment']);

        return $this->db->get()->result_array();
    }

    function getBOQ( $params ) {
        $this->db->select("
        material.description
        ,material.approvedQty
        ,material.previousBillingQty
        ,material.approvedCost
        ,(material.approvedQty * material.approvedCost) as originalAmount 
        ,(material.revisedQty * material.approvedCost) as revisedAmount 
        ,(material.previousBillingQty * material.approvedCost) as costOfPrevBilling 
        ,material.revisedQty
        ,unit.unitCode as approvedUnit");

        $this->db->from('constructionprojectmaterials as material');
        $this->db->join('item', 'item.idItem = material.idItem',    'LEFT OUTER');
        $this->db->join('unit', 'unit.idUnit = item.idUnit',        'LEFT OUTER');

        $this->db->where('material.idConstructionProject',  $params['idConstructionProject']);

        return $this->db->get()->result_array();
    }

    function getTrucking( $params ) {
        $deliveryticketactivity = $this->getTruckingDeliveryTicket($params);
        $rental = $this->getTruckingRental($params);

        return $this->db->query($deliveryticketactivity . ' UNION ALL ' . $rental)->result_array();
    }

    function getDisbursement( $params ) {
        // $this->db->select("");

        // $this->db->from('constructionproject as project');
        // $this->db->join('invoice', 'item.idItem = material.idItem',    'LEFT OUTER');
        // $this->db->join('unit', 'unit.idUnit = item.idUnit',        'LEFT OUTER');

        // $this->db->where('material.idConstructionProject',  $params['idConstructionProject']);

        // return $this->db->get()->result_array();
    }

    function getVouchers( $params ) {
        // $this->db->select("");

        // $this->db->from('constructionproject as project');
        // $this->db->join('invoice', 'item.idItem = material.idItem',    'LEFT OUTER');

        // $this->db->where('material.idConstructionProject',  $params['idConstructionProject']);

        // return $this->db->get()->result_array();
    }

    function getTruckingDeliveryTicket( $params ) {
        $this->db->select("
            truckprofile.plateNumber
            ,trucktype.truckType
            ,employee.name AS driverName
            ,employee.sk AS employeeSK ");

        $this->db->from('deliveryticket as dticket');
        $this->db->join('constructionproject as project',   'project.idConstructionProject = dticket.idProject',    'LEFT OUTER');
        $this->db->join('truckprofile',                     'truckprofile.idTruckProfile = dticket.idTruckProfile', 'LEFT OUTER');
        $this->db->join('trucktype',                        'trucktype.idTruckType = dticket.idTruckType',          'LEFT OUTER');
        $this->db->join('invoices',                         'invoices.idInvoice = dticket.idInvoice',               'LEFT OUTER');
        $this->db->join('employee',                         'employee.idEmployee = dticket.idDriver',               'LEFT OUTER');

        $this->db->where('dticket.idProject', $params['idConstructionProject']);
        $this->db->where('dticket.isConstruction', 1);
        $this->db->where( 'invoices.archived', 0);

        return $this->db->get_compiled_select();
    }

    function getTruckingRental( $params ) {
        $this->db->select("
            truckprofile.plateNumber
            ,trucktype.truckType
            ,employee.name AS driverName
            ,employee.sk AS employeeSK ");

        $this->db->from('rental');
        $this->db->join('constructionproject as project',   'project.idConstructionProject = rental.idProject',     'LEFT OUTER');
        $this->db->join('truckprofile',                     'truckprofile.idTruckProfile = rental.idTruckProfile',  'LEFT OUTER');
        $this->db->join('trucktype',                        'trucktype.idTruckType = rental.idTruckType',           'LEFT OUTER');
        $this->db->join('invoices',                         'invoices.idInvoice = rental.idInvoice',                'LEFT OUTER');
        $this->db->join('employee',                         'employee.idEmployee = rental.idDriver',                'LEFT OUTER');

        $this->db->where('rental.idProject', $params['idConstructionProject']);
        $this->db->where('rental.isConstruction', 1);
        $this->db->where( 'invoices.archived', 0);

        return $this->db->get_compiled_select();
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

    function saveProjectAccomplishment( $params ){
        if( isset($params['onEdit']) && $params['onEdit'] ) {
            $this->db->where('idConsProjectAccomplishment', $params['idConsProjectAccomplishment']);
            $this->db->update('consprojectaccomplishment', unsetParams( $params, 'consprojectaccomplishment' ) );
            $idAccomplishment = $params['idConsProjectAccomplishment'];
        } else { 
            $this->db->insert( 'consprojectaccomplishment', unsetParams( $params, 'consprojectaccomplishment' ) );
            $idAccomplishment = $this->db->insert_id();
        }
        return $idAccomplishment;
    }

    public function deleteProjectAccomplishment( $params ) {
        $this->db->where('idConsProjectAccomplishment', $params['idAccomplishment']);
        $this->db->delete('consprojectaccomplishment');
    }

    function getProjectName( $params ) {
        $this->db->select('idConstructionProject as id, projectName as name');
        $this->db->order_by('projectName ASC');
        return $this->db->get('constructionproject')->result_array();
    }

    function getConsContract( $params ) {
        $this->db->select('contractDuration, dateStart');
        $this->db->where('idConstructionProject', $params['idConstructionProject']);
        return $this->db->get('constructionproject')->result_array();
    }
}