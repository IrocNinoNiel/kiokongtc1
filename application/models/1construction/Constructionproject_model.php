<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Hazel Joy Alegbeleye
 * Module: Construction Project
 * Date: Dec 22, 2021
 * Finished: 
 * Description: This module allows the authorized user to set (add, edit and delete) a Construction Project.
 * DB Tables: 
 * */
class Constructionproject_model extends CI_Model {

    function getProjectID(){
        return $this->db->select("LPAD( IFNULL( MAX( idConstructionProject ), 0 ) + 1, 4, 0 ) as id")->get('constructionproject')->result_array();
	}

    function getLocation( $params){
        if( isset( $params['locations'] ) &&  $params['locations'] != '' ) {
            $locations = json_decode( $params['locations'] );
            $this->db->select(" location.idLocation ,location.locationName
                                ,( case
                                    when location.idLocation IS NULL THEN 0
                                    else 1
                                end ) as chk");
            $this->db->from('location');
            $this->db->join('( SELECT idLocation from location where idLocation in (' . join(", ", $locations) . ') ) as alocation', 'on location.idLocation = alocation.idLocation', 'LEFT');
        } else {
            $this->db->select("location.idLocation, location.locationName");
            $this->db->from('location');
        }

        $params['db'] = $this->db;
        $params['order_by'] = 'location.locationName asc';
        return getGridList($params);
    }

    function getMaterials( $params ){
        if( isset($params['idConstructionProject']) && !empty($params['idConstructionProject']) ){
            $this->db->select("
                                constructionprojectmaterials.idConstructionProjectMaterials, 
                                constructionprojectmaterials.idItem,
                                item.itemPrice,
                                item.itemName,
                                item.barcode,
                                item.sk,
                                unit.unitCode as unit,
                                constructionprojectmaterials.idConstructionProject,
                                constructionprojectmaterials.approvedQty,
                                constructionprojectmaterials.approvedCost as approvedCost,
                                ( IFNULL( constructionprojectmaterials.approvedQty, 0 ) * IFNULL( constructionprojectmaterials.approvedCost, 0 ) ) as approvedAmount,
                                constructionprojectmaterials.revisedQty,
                                ( IFNULL( constructionprojectmaterials.revisedQty, 0 ) * IFNULL( constructionprojectmaterials.approvedCost, 0 ) ) as revisedAmount,
                                constructionprojectmaterials.description
                                ");
            $this->db->join("item", "item.idItem = constructionprojectmaterials.idItem", "left outer");
            $this->db->join("unit", "unit.idUnit = item.idUnit", "left outer");

            $this->db->where("constructionprojectmaterials.idConstructionProject", $params['idConstructionProject']);
            return $this->db->get('constructionprojectmaterials')->result_array();
        } else {
            return array();
        }
    }

    function getOtherDeductions( $params ){
        if( isset($params['idConstructionProject']) && !empty($params['idConstructionProject']) ){
            $this->db->select("
                                constructionprojectdeduction.idConstructionProjectDeduction, 
                                constructionprojectdeduction.description,
                                constructionprojectdeduction.amount");

            $this->db->where("constructionprojectdeduction.idConstructionProject", $params['idConstructionProject']);
            return $this->db->get('constructionprojectdeduction')->result_array();
        } else {
            return array();
        }
    }

    function getProjectTeam( $params ){
        if( isset($params['idConstructionProject']) && !empty($params['idConstructionProject']) ){
            $this->db->select("
                                constructionprojectteam.idConstructionProjectTeam, 
                                constructionprojectteam.idEmployee,
                                employee.name as employeeName,
                                employeeclass.empClassName as classification,
                                employee.sk,
                                ( case employee.status
                                    when 0 then 'Active'
                                    when 1 then 'Inactive'
                                end ) as status");
            $this->db->join("employee", "employee.idEmployee = constructionprojectteam.idEmployee", "left outer");
            $this->db->join("employment", "employment.idEmployee = employee.idEmployee", "left outer");
            $this->db->join("employeeclass", "employeeclass.idEmpClass = employment.classification", "left outer");

            $this->db->where("constructionprojectteam.idConstructionProject", $params['idConstructionProject']);
            return $this->db->get('constructionprojectteam')->result_array();
        } else {
            return array();
        }
    }

    function getConstructionProjectVAT( $params ){
        if( isset($params['idConstructionProject']) && !empty($params['idConstructionProject']) ){
            $this->db->select("
                                constructionprojectvat.idConstructionProjectVAT,
                                ( case constructionprojectvat.vatType
                                    when 1 then 'Inclusive'
                                    when 2 then 'Exclusive'
                                end ) as vatType,
                                constructionprojectvat.vatType as id,
                                constructionprojectvat.vatName,
                                constructionprojectvat.vatPercent,
                                ( case constructionprojectvat.vatType
                                    when 1 then 0
                                    when 2 then IFNULL(( constructionprojectvat.vatPercent / 100 ) * invoices.amount, 0)
                                end ) as totalVATExclusive,
                                ( case constructionprojectvat.vatType
                                    when 1 then IFNULL( invoices.amount, 0 )
                                    when 2 then 0
                                end ) as totalVATInclusive");
            $this->db->join("constructionproject", "constructionproject.idConstructionProject = constructionprojectvat.idConstructionProject", "left outer");
            $this->db->join("invoices", "invoices.idInvoice = constructionproject.idInvoice", "left outer");


            $this->db->where("constructionprojectvat.idConstructionProject", $params['idConstructionProject']);
            return $this->db->get('constructionprojectvat')->result_array();
        } else {
            return array();
        }
    }

    function getItems( $params ){
        $this->db->select("item.idItem, item.barcode, item.itemName, item.sk, item.itemPrice, unit.unitCode as unit");
        $this->db->join('unit', 'unit.idUnit = item.idUnit', 'left');
        $this->db->where('item.archived', 0 );

        $this->db->order_by('item.itemName asc');
        return $this->db->get('item')->result_array();
    }

    function getEmployees( $params ){
        $this->db->select("
                            employee.idEmployee, 
                            employee.name as employeeName, 
                            employee.sk as employeeSK, 
                            (case employee.status
                                when 0 then 'Active'
                                when 1 then 'Inactive'
                            end) as status,
                            employeeclass.empClassName as classification
        ");
        $this->db->join('employment', 'employment.idEmployee = employee.idEmployee', 'left');
        $this->db->join('employeeclass', 'employeeclass.idEmpClass = employment.classification', 'left');
        $this->db->where('employee.archived', 0 );

        $this->db->order_by('employee.name asc');
        return $this->db->get('employee')->result_array();
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

    function saveConstructionProject( $params ){
        $params = (array) $params;

        if( isset($params['onEdit']) && $params['onEdit'] == 1 ) {
            $this->db->where('idConstructionProject', $params['idConstructionProject']);
            $this->db->update('constructionproject', unsetParams( $params, 'constructionproject' ) );
            $idConstructionProject = $params['idConstructionProject'];
        } else {
            if( isset( $params['idConstructionProject'] ) ) unset( $params['idConstructionProject'] );
            $this->db->insert( 'constructionproject', unsetParams( $params, 'constructionproject' ) );
            $idConstructionProject = $this->db->insert_id();
        }

        return $idConstructionProject;
    }

    function saveConstructionProjectMaterialGrid( $params ){
        $params = (array) $params;

        if( count( $params ) > 0 ) {
            if( isset( $params['onEdit']) && $params['onEdit'] == 1 ) {
                $idConstructionProject   = $params['idConstructionProject'];

                unset( $params['idConstructionProject']);
                unset( $params['onEdit']);

                $this->db->delete( 'constructionprojectmaterials', array( 'idConstructionProject' => $idConstructionProject ) );
                $this->db->insert_batch( 'constructionprojectmaterials', $params );
            } else {
                $idConstructionProject   = $params['idConstructionProject'];

                unset( $params['idConstructionProject']);
                unset( $params['onEdit']);

                $this->db->insert_batch( 'constructionprojectmaterials',  $params );
            }

            return $this->db->insert_id();
        }
    }

    function saveConstructionProjectDeduction( $params ){
        $params = (array) $params;

        if( count( $params ) > 0 ) {
            if( isset( $params['onEdit']) && $params['onEdit'] == 1 ) {
                $idConstructionProject   = $params['idConstructionProject'];

                unset( $params['idConstructionProject']);
                unset( $params['onEdit']);

                $this->db->delete( 'constructionprojectdeduction', array( 'idConstructionProject' => $idConstructionProject ) );
                $this->db->insert_batch( 'constructionprojectdeduction', $params );
            } else {
                $idConstructionProject   = $params['idConstructionProject'];

                unset( $params['idConstructionProject']);
                unset( $params['onEdit']);

                $this->db->insert_batch( 'constructionprojectdeduction',  $params );
            }

            return $this->db->insert_id();
        }
    }

    function saveConstructionProjectTeam( $params ){
        $params = (array) $params;

        if( count( $params ) > 0 ) {
            if( isset( $params['onEdit']) && $params['onEdit'] == 1 ) {
                $idConstructionProject   = $params['idConstructionProject'];

                unset( $params['idConstructionProject']);
                unset( $params['onEdit']);

                $this->db->delete( 'constructionprojectteam', array( 'idConstructionProject' => $idConstructionProject ) );
                $this->db->insert_batch( 'constructionprojectteam', $params );
            } else {
                $idConstructionProject   = $params['idConstructionProject'];

                unset( $params['idConstructionProject']);
                unset( $params['onEdit']);

                $this->db->insert_batch( 'constructionprojectteam',  $params );
            }

            return $this->db->insert_id();
        }
    }

    function saveConstructionProjectVAT( $params ){
        $params = (array) $params;

        if( count( $params ) > 0 ) {
            if( isset( $params['onEdit']) && $params['onEdit'] == 1 ) {
                $idConstructionProject   = $params['idConstructionProject'];

                unset( $params['idConstructionProject']);
                unset( $params['onEdit']);

                $this->db->delete( 'constructionprojectvat', array( 'idConstructionProject' => $idConstructionProject ) );
                $this->db->insert_batch( 'constructionprojectvat', $params );
            } else {
                $idConstructionProject   = $params['idConstructionProject'];

                unset( $params['idConstructionProject']);
                unset( $params['onEdit']);

                $this->db->insert_batch( 'constructionprojectvat',  $params );
            }

            return $this->db->insert_id();
        }
    }

    function saveConstructionProjectLocation( $params ){
        $params = (array) $params;

        if( count( $params ) > 0 ) {
            if( isset( $params['onEdit']) && $params['onEdit'] == 1 ) {
                $idConstructionProject   = $params['idConstructionProject'];

                unset( $params['idConstructionProject']);
                unset( $params['onEdit']);

                $this->db->delete( 'constructionprojectlocation', array( 'idConstructionProject' => $idConstructionProject ) );
                $this->db->insert_batch( 'constructionprojectlocation', $params );
            } else {
                $idConstructionProject   = $params['idConstructionProject'];

                unset( $params['idConstructionProject']);
                unset( $params['onEdit']);

                $this->db->insert_batch( 'constructionprojectlocation',  $params );
            }

            return $this->db->insert_id();
        }
    }

    function viewAll( $params ){
        $this->db->select("
                            affiliate.affiliateName,
                            affiliate.sk AS affiliateSK,
                            concat(reference.code, '-', invoices.referenceNum) as referenceNum,
                            convert(invoices.date, date) as date,
                            ( case constructionproject.isManual
                                when 0 then LPAD( constructionproject.idConstructionProject, 4, 0 )
                                when 1 then LPAD( constructionproject.manualIDConstructionProject, 4, 0 )
                            END ) AS idConstructionProject,
                            constructionproject.idConstructionProject as id,
                            constructionproject.projectName,
                            constructionproject.contractDuration,
                            convert(constructionproject.dateStart, date) as contractEffectivity,
                            convert(constructionproject.dateCompleted, date) as contractExpiry,
                            constructionproject.licenseNumber,
                            ( case constructionproject.status
                                when 1 then 'Suspended'
                                when 2 then 'Ongoing'
                                when 3 then 'Completed'
                            END ) AS status,
                            invoices.amount as contractAmount");
        $this->db->join('reference', 'reference.idReference = invoices.idReference', 'LEFT OUTER');
        $this->db->join('affiliate', 'affiliate.idAffiliate = invoices.idAffiliate', 'LEFT OUTER');
        $this->db->join('constructionproject', 'constructionproject.idInvoice = invoices.idInvoice', 'LEFT OUTER');

        $this->db->order_by('invoices.date desc, invoices.referenceNum desc');
        

        if ( isset($params['limit']) && $params['limit'] != '' && isset($params['start']) && $params['start'] != '') {
            $this->db->limit($params['limit'], $params['start']);
        }

        if( isset( $params['filterValue'] ) && $params['filterValue'] != '' ){
            $this->db->where( 'constructionproject.idConstructionProject', $params['filterValue'] );
        } 

        $this->db->where( 'invoices.archived', 0);
        $this->db->where( 'invoices.idModule', 78);

        return $this->db->get('invoices')->result_array();
    }

    function viewHistorySearch( $params ){
        $this->db->select("constructionproject.idConstructionProject as id, concat(reference.code, '-', invoices.referenceNum) as name");
        $this->db->join('invoices', 'invoices.idInvoice = constructionproject.idInvoice', 'LEFT OUTER');
        $this->db->join('reference', 'reference.idReference = invoices.idReference', 'LEFT OUTER');
        $this->db->where( 'invoices.archived', 0 );
        
        $this->db->order_by('invoices.date desc, invoices.referenceNum desc');
        if ($params['limit'] != '' && $params['start'] != '') {
            $this->db->limit($params['limit'], $params['start']);
         }
        return $this->db->get('constructionproject')->result_array();
    }

    function retrieveData( $params ){
        $this->db->select("
                            invoices.idInvoice,
                            invoices.idCostCenter,
                            invoices.idReference,
                            invoices.referenceNum,
                            reference.code,
                            CONVERT( invoices.date, DATE ) as tdate, 
                            TIME_FORMAT( CONVERT( invoices.date, TIME ), '%h:%i %p') as ttime,
                            constructionproject.isManual,
                            ( case  constructionproject.isManual
                                when 0 then LPAD( constructionproject.idConstructionProject, 4, 0 )
                                when 1 then LPAD( constructionproject.manualIDConstructionProject, 4, 0 )
                            END ) AS idConstructionProject,
                            constructionproject.idConstructionProject as id,
                            constructionproject.projectName,
                            constructionproject.contractDuration,
                            invoices.amount as contractAmount,
                            convert(constructionproject.dateStart, date) as dateStart,
                            convert(constructionproject.dateCompleted, date) as dateCompleted,
                            convert(constructionproject.dateAwarded, date) as dateAwarded,
                            constructionproject.status,
                            constructionproject.statusType,
                            constructionproject.licenseNumber,
                            constructionproject.licenseType,
                            constructionproject.licenseName,
                            constructionproject.royaltyPercentage,
                            constructionproject.warrantyDateFrom as sdate,
                            constructionproject.warrantyDateTo as edate,
                            constructionproject.remarks,
                            constructionproject.timeExtension,
                            constructionproject.revisedContractAmount,
                            group_concat( constructionprojectlocation.idLocation ) as locations
                            ");
        $this->db->join('invoices', 'invoices.idInvoice = constructionproject.idInvoice', 'LEFT OUTER');
        $this->db->join('reference', 'reference.idReference = invoices.idReference', 'LEFT OUTER');
        $this->db->join('constructionprojectlocation', 'constructionprojectlocation.idConstructionProject = constructionproject.idConstructionProject', 'INNER');

        $this->db->order_by('invoices.date desc, invoices.referenceNum desc');
        $this->db->where( 'constructionproject.idConstructionProject', $params['id'] );

        return $this->db->get('constructionproject')->result_array();
    }

    function deleteRecord( $params ){
        // $match = 0;

        // $this->db->select("*");
        // $this->db->where( array(
        //                         "fident" => $params['id']
        //                         ,"archived" => 0) );
        // $usedTransactions = $this->db->get('invoices')->num_rows();

        // if( $usedTransactions > 0 ){
        //     $match = 1;
        // } else {
        //     /* SOFT DELETE ONLY */
        //     $this->db->set('archived', 1, false );
        //     $this->db->where('idInvoice', $params['id'] );
        //     $this->db->update('invoices');
        // }
        
        // return $match;
    }

}