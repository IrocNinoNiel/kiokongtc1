<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Christian P. Daohog
 * Module: Material Monitoring
 * Date: Jan 31, 2022
 * Finished: 
 * Description: This module allows authorized users to generate and monitor the materials released per project in construction. 
 * DB Tables: 
 * */ 

class Materialmonitoring_model extends CI_Model {

    function getBalance( $params ) {
        $this->db->select("
            item.itemName 
            ,item.sk
            ,(materials.approvedQty * materials.approvedCost) as approvedAmount
            ,materials.approvedCost
            ,materials.approvedQty
            ,(materials.approvedQty - materials.previousBillingQty) as balanceQty
            ,unit.unitCode as approvedUnit ");

        $this->db->from('constructionprojectmaterials as materials');
        $this->db->join('constructionproject as project',   'project.idConstructionProject = materials.idConstructionProject',  'LEFT OUTER');
        $this->db->join('invoices',                         'invoices.idInvoice = project.idInvoice',                           'LEFT OUTER');
        $this->db->join('affiliate',                        'affiliate.idAffiliate = invoices.idAffiliate',                     'LEFT OUTER');
        $this->db->join('item',                             'item.idItem = materials.idItem',                                   'LEFT OUTER');
        $this->db->join('unit',                             'unit.idUnit = item.idUnit',                                        'LEFT OUTER');

        if(isset($params['idAffiliate']) && $params['idAffiliate'] != 0) $this->db->where('invoices.idAffiliate',           $params['idAffiliate']); 
        if(isset($params['projectName']) && $params['projectName'] != 0) $this->db->where('project.idConstructionProject',  $params['projectName']);

        $this->db->where('invoices.date <', date('Y-m-d', strtotime($params['asOfDateBalance'] . ' +1 day')));
        $this->db->where( 'invoices.archived', 0);

        return $this->db->get()->result_array();
    }

    function getLedger( $params ) {
        $this->db->select('
            affiliate.affiliateName 
            ,affiliate.sk as affiliateSK
            ,convert(invoices.date, date) as date
            ,concat(reference.code, "-", invoices.referenceNum) as referenceNum
            ,materials.approvedCost
            ,(materials.approvedQty - materials.previousBillingQty) as ledgerQty
            ,unit.unitCode as ledgerUnit ');

        $this->db->from('constructionprojectmaterials as materials');
        $this->db->join('constructionproject as project',   'project.idConstructionProject = materials.idConstructionProject',  'LEFT OUTER');
        $this->db->join('invoices',                         'invoices.idInvoice = project.idInvoice',                           'LEFT OUTER');
        $this->db->join('reference',                        'reference.idReference = invoices.idReference',                     'LEFT OUTER');
        $this->db->join('affiliate',                        'affiliate.idAffiliate = invoices.idAffiliate',                     'LEFT OUTER');
        $this->db->join('item',                             'item.idItem = materials.idItem',                                   'LEFT OUTER');
        $this->db->join('unit',                             'unit.idUnit = item.idUnit',                                        'LEFT OUTER');

        if(isset($params['idAffiliate']) && $params['idAffiliate']  != 0) $this->db->where('invoices.idAffiliate',           $params['idAffiliate']); 
        if(isset($params['projectName']) && $params['projectName']  != 0) $this->db->where('project.idConstructionProject',  $params['projectName']);
        if(isset($params['itemName']) && $params['itemName']        != 0) $this->db->where('materials.idItem',               $params['itemName']);

        $this->db->where('invoices.date <', date('Y-m-d', strtotime($params['asOfDateLedger'] . ' +1 day')));
        $this->db->where( 'invoices.archived', 0);

        return $this->db->get()->result_array();
    }

    function getProjectNames( $params ) {
        $this->db->select('idConstructionProject as id, projectName as name');
        $this->db->order_by('projectName ASC');
        return $this->db->get('constructionproject')->result_array();
    }

    function getItems( $params ){
        $this->db->select("item.idItem as id, item.itemName as name, item.sk");
        $this->db->join('unit', 'unit.idUnit = item.idUnit', 'left');
        $this->db->where('item.archived', 0 );

        $this->db->order_by('item.itemName asc');
        return $this->db->get('item')->result_array();
    }
}




