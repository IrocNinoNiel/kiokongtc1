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

    function getIdActivity($params) {
        $this->db->distinct();
        $this->db->select("activity.idActivity, activity.activityName");
        $this->db->where('idConstructionProject', $params['idConstructionProject']);
        $this->db->join('activity', 'activity.idActivity = conssubactivity.idActivity');
        return $this->db->get('conssubactivity')->result_array();
    }

    function getSubActivity($params, $idActivity) {
        $this->db->select("idConsSubActivity, idActivity, subActivityName, idConstructionProject");
        $this->db->where('idActivity', $idActivity);
        $this->db->where('idConstructionProject', $params['idConstructionProject']);
        return $this->db->get('conssubactivity')->result_array();
    }

    function getMaterials($params) {
        $this->db->select("
            consitem.idConsActivityDetailsItem
            ,consitem.idConsActivityDetails
            ,consitem.idItem
            ,consitem.qty
            ,consitem.unitCost
            ,consitem.remainingBalance
            ,item.itemName
            ,item.sk
            ,unit.unitCode
            ,(consitem.unitCost * consitem.qty) as amount");

        if(isset($params['idAccomplishment'])) {
            // $this->db->select("released.releasedQty, released.releasedCost");
            // // $this->db->join('consactivitydetailsreleased as released', 'released.idItemLaborEquip = consitem.idConsActivityDetailsItem', 'LEFT');
            // $this->db->join('consactivitydetailsitemreleased as released', 'released.idConsActivityDetailsItem = consitem.idConsActivityDetailsItem', 'LEFT');
            // // $this->db->where('released.type', 1); // 5/23/22 added
            // $this->db->where('released.idProjectAccomplishment', $params['idAccomplishment']);


            $this->db->select("released.qty as releasedQty, released.unitCost as releasedCost");
            $this->db->join('constructionreleasing as released', 'released.fIdent = consitem.idConsActivityDetailsItem', 'LEFT');
            $this->db->where('released.idConsprojectAccomplishment', $params['idAccomplishment']);
        }

        $this->db->join('consactivitydetails as details', 'details.idConsActivityDetails =  consitem.idConsActivityDetails', 'LEFT');
        $this->db->join('item', 'item.idItem = consitem.idItem', 'LEFT');
        $this->db->join('unit', 'unit.idUnit = item.idUnit', 'LEFT');
        $this->db->where('details.idConsSubActivity', $params['idConsSubActivity']);
        
        return $this->db->get('consactivitydetailsitem as consitem')->result_array();
    }

    function getLabors($params){
        $this->db->select("
            labor.idConsActivityDetailsLabor
            ,labor.idConsActivityDetails
            ,labor.qty
            ,employee.idEmployee
            ,employee.name as employeeName
            ,employee.sk as employeeSK
            ,(case employee.status
                when 0 then 'Active'
                when 1 then 'Inactive'
            end) as status
            ,employeeclass.empClassName as classification
            ,employment.monthRate");

        if(isset($params['idAccomplishment'])) {
            // $this->db->select("history.releasedQty, history.releasedCost");
            // $this->db->join('consactivitydetailslaborreleased as history', 'history.idConsActivityDetailsLabor = labor.idConsActivityDetailsLabor', 'LEFT');
            // $this->db->where('history.idProjectAccomplishment', $params['idAccomplishment']);

            $this->db->select("released.qty as releasedQty, released.unitCost as releasedCostreleasedCost");
            $this->db->join('constructionreleasing as released', 'released.fIdent = labor.idConsActivityDetailsLabor', 'LEFT');
            $this->db->where('released.idConsprojectAccomplishment', $params['idAccomplishment']);
        }

        $this->db->join('employee', 'employee.idEmployee = labor.idEmployee', 'left');
        $this->db->join('employment', 'employment.idEmployee = employee.idEmployee', 'left');
        $this->db->join('employeeclass', 'employeeclass.idEmpClass = employment.classification', 'left');
        $this->db->join('consactivitydetails as details', 'details.idConsActivityDetails =  labor.idConsActivityDetails');

        $this->db->where('employee.archived', 0 );
        $this->db->where('details.idConsSubActivity', $params['idConsSubActivity']);
        $this->db->order_by('employee.name asc');

        return $this->db->get('consactivitydetailslabor as labor')->result_array();
    }

    function getActivityEquip($params) {
        $this->db->select("
            equip.idConsActivityDetailsEquip
            ,equip.idConsActivityDetails
            ,equip.idTruck
            ,equip.qty
            ,equip.unitCost
            ,type.truckType
            ,(equip.qty * equip.unitCost) as amount");

        if(isset($params['idAccomplishment'])) {
            // $this->db->select("released.releasedQty, released.releasedCost");
            // $this->db->join('consactivitydetailsequipreleased as released', 'released.idConsActivityDetailsEquip = equip.idConsActivityDetailsEquip', 'LEFT');
            // $this->db->where('released.idProjectAccomplishment', $params['idAccomplishment']);

            $this->db->select("released.qty as releasedQty, released.unitCost as releasedCostreleasedCost");
            $this->db->join('constructionreleasing as released', 'released.fIdent = equip.idConsActivityDetailsEquip', 'LEFT');
            $this->db->where('released.idConsprojectAccomplishment', $params['idAccomplishment']);
        }
        $this->db->join( 'trucktype as type', 'type.idTruckType = equip.idTruck', 'left outer' );
        $this->db->join('consactivitydetails as details', 'details.idConsActivityDetails =  equip.idConsActivityDetails');
        $this->db->where('details.idConsSubActivity', $params['idConsSubActivity']); 
        return $this->db->get( 'consactivitydetailsequip as equip' )->result_array();
    }

    function getActivityIndirect($id) {
        $this->db->select("
        ,idConsActivityDetails
        ,idConsSubActivity
        ,ocm
        ,contractorsProfit
        ,vat");

        $this->db->where('idConsSubActivity', $id); 

        return $this->db->get( 'consactivitydetails' )->result_array();
    }

    function getAccomplishment($params) {
        $this->db->select('
            affiliate.affiliateName
            ,affiliate.sk as affiliateSK
            ,convert(invoices.date, date) as date
            ,concat(reference.code, "-", invoices.referenceNum) as referenceNum 
            ,project.projectName
            ,"" as contractorName
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
            ,"" as contractor
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

    function saveConstructionReleasing($params) {
        $this->db->insert_batch('constructionreleasing', $params);
    }

    function updateItemRemainingBalance( $params ) {
        unset($params['id']);
        unset($params['releasedCost']);
        unset($params['releasedQty']);

        $this->db->where('idConsActivityDetailsItem', $params['idConsActivityDetailsItem']);
        $this->db->update('consactivitydetailsitem', unsetParams( $params, 'consactivitydetailsitem' ) );
    }

    function saveMaterialReleasedQty( $params ) {
        $this->db->insert( 'consactivitydetailsitemreleased', unsetParams( $params, 'consactivitydetailsitemreleased' ) );
    }

    function saveLaborReleasedQty( $params ) {
        $this->db->insert( 'consactivitydetailslaborreleased', unsetParams( $params, 'consactivitydetailslaborreleased' ) );
    }

    function saveEquipmentReleasedQty( $params ) {
        $this->db->insert( 'consactivitydetailsequipreleased', unsetParams( $params, 'consactivitydetailsequipreleased' ) );
    }

    function deleteConstructionReleasing($idProjectAccomplishment) {
        $this->db->where('idConsprojectAccomplishment', $idProjectAccomplishment);  
        $this->db->delete('constructionreleasing');
    }

    public function deleteProjectAccomplishment( $params ) {
        $this->db->where('idConsProjectAccomplishment', $params['idAccomplishment']);
        $this->db->delete('consprojectaccomplishment');
    }

    function deleteActivityMaterials($idProjectAccomplishment) {
        $this->db->query('
            DELETE item
            from consactivitydetailsitemreleased as item
            where idProjectAccomplishment = ' . $idProjectAccomplishment . '');
    }

    function deleteActivityLabors($idProjectAccomplishment) {
        $this->db->query('
            DELETE labor
            from consactivitydetailslaborreleased as labor
            where idProjectAccomplishment = ' . $idProjectAccomplishment . '');
    }

    function deleteActivityEquip($idProjectAccomplishment) {
        $this->db->query('
            DELETE equip
            from consactivitydetailsequipreleased as equip
            where idProjectAccomplishment = ' . $idProjectAccomplishment . '');
    }

    function getProjectName( $params ) {
        $this->db->select('idConstructionProject as id, projectName as name');
        $this->db->join('invoices', 'invoices.idInvoice = constructionproject.idInvoice');
        $this->db->where('invoices.archived', 0);
        $this->db->order_by('projectName ASC');
        return $this->db->get('constructionproject')->result_array();
    }

    function getContractor( $params ) {
        $this->db->select('team.idEmployee as id, employee.name as employeeName, employee.sk as employeeSK');
        $this->db->join('employee', 'employee.idEmployee = team.idEmployee');
        $this->db->join('employmenthistoryposition as position', 'position.idEmployee = team.idEmployee');
        $this->db->where('team.idConstructionProject', $params['idConstructionProject']);
        $this->db->where('position.classification', 46);
        
        return $this->db->get('constructionprojectteam as team')->result_array();
    }

    function getConsContract( $params ) {
        $this->db->select('contractDuration, dateStart');
        $this->db->where('idConstructionProject', $params['idConstructionProject']);
        return $this->db->get('constructionproject')->result_array();
    }

    function getTotalReleasedAmnt($params) {
        $this->db->select('SUM(releasing.qty * releasing.unitCost ) as totalReleasedAmnt');
        $this->db->from('consprojectaccomplishment AS acc');
        $this->db->join('constructionreleasing as releasing', 'releasing.idConsprojectAccomplishment = acc.idConsprojectAccomplishment', 'LEFT');
        $this->db->where('acc.idConstructionProject', $params['idConstructionProject']);
        return $this->db->get()->result_array();
    }
}
