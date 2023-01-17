<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Developer: Christian P. Daohog
 * Module: Project Monitoring
 * Date: Jan 31, 2022
 * Finished: 
 * Description: This module allows authorized users to generate and monitor the projects registered in the system.
 * DB Tables: 
 * */ 

class Projectmonitoring_model extends CI_Model {

    function getProjectActivity( $params ) {
        $this->db->select("
         affiliate.affiliateName,
        ,affiliate.sk as affiliateSK
        ,( case constructionproject.isManual 
                when 1 THEN constructionproject.manualIDConstructionProject
                when 0 THEN constructionproject.projectID
         end ) as idContract
        ,constructionproject.projectName
        ,constructionproject.contractDuration
        ,constructionproject.contractAmount
        ,constructionproject.dateStart
        ,constructionproject.dateStart as contractEffectivity
        ,constructionproject.dateStart as contractExpiry
        ,constructionproject.licenseName as licenseUsed
        ,( case constructionproject.licenseType 
                when 1 THEN 'In Royalty'
                when 2 THEN 'Out Royalty'
                when 3 THEN 'In and Out Royalty'
                when 4 THEN 'Admin'
         end ) as licenseType
        ,( case constructionproject.status 
                when 1 THEN 'Suspended'
                when 2 THEN 'Ongoing'
                when 3 THEN 'Completed'
         end ) as status
         ,( case constructionproject.statusType 
                when 1 THEN 'Slippage'
                when 2 THEN 'Advance'
                when 3 THEN 'On-Time'
        end ) as statusType
        ,constructionproject.dateStart
        ,constructionproject.warrantyDateFrom
        ,constructionproject.warrantyDateTo ");

        $this->db->from('constructionproject');
        $this->db->join('invoices',  'invoices.idInvoice = constructionproject.idInvoice',  'LEFT OUTER');
        $this->db->join('affiliate', 'affiliate.idAffiliate = invoices.idAffiliate',        'LEFT OUTER');

        if(isset($params['idAffiliate']) && $params['idAffiliate'] != 0) $this->db->where('invoices.idAffiliate',                       $params['idAffiliate']); 
        if(isset($params['projectName']) && $params['projectName'] != 0) $this->db->where('constructionproject.idConstructionProject',  $params['projectName']);

        // $this->db->where( 'constructionproject.dateStart >=', $params['asOfDate']);
        // $this->db->where( 'constructionproject.dateCompleted <=', $params['asOfDate']);
        $this->db->where( 'invoices.archived', 0);

        return $this->db->get()->result_array();
    }

    function getProjectNames( $params ) {
        $this->db->select('idConstructionProject as id, projectName as name');
        $this->db->order_by('projectName ASC');
        return $this->db->get('constructionproject')->result_array();
    }

}