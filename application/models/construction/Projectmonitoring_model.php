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
        ,( case project.isManual 
                when 1 THEN project.manualIDConstructionProject
                when 0 THEN project.projectID
         end ) as idContract
        ,project.projectName
        ,project.contractDuration
        ,project.idContract,
        ,invoices.amount as contractAmount,
        ,project.dateStart
        ,DATE_FORMAT(project.dateStart,'%m/%d/%Y') as contractEffectivity 
        ,DATE_FORMAT(project.dateCompleted,'%m/%d/%Y') as contractExpiry
        ,project.licenseName as licenseUsed
        ,( case project.licenseType 
                when 1 THEN 'In Royalty'
                when 2 THEN 'Out Royalty'
                when 3 THEN 'In and Out Royalty'
                when 4 THEN 'Admin'
         end ) as licenseType
        ,( case project.status 
                when 1 THEN 'Suspended'
                when 2 THEN 'Ongoing'
                when 3 THEN 'Completed'
         end ) as status
         ,( case project.statusType 
                when 1 THEN 'Slippage'
                when 2 THEN 'Advance'
                when 3 THEN 'On-Time'
        end ) as statusType
        ,DATE_FORMAT(project.warrantyDateFrom,'%m/%d/%Y') as warrantyDateFrom
        ,DATE_FORMAT(project.warrantyDateTo,'%m/%d/%Y') as warrantyDateTo");

        $this->db->from('constructionproject as project');
        $this->db->join('invoices',  'invoices.idInvoice = project.idInvoice',          'LEFT OUTER');
        $this->db->join('affiliate', 'affiliate.idAffiliate = invoices.idAffiliate',    'LEFT OUTER');

        if(isset($params['idAffiliate']) && $params['idAffiliate'] != 0) $this->db->where('invoices.idAffiliate', $params['idAffiliate']); 
        if(isset($params['projectName']) && $params['projectName'] != 0) $this->db->where('project.idConstructionProject', $params['projectName']);

        $this->db->where( 'project.dateStart <', date('Y-m-d H:i:s', strtotime($params['asOfDate'] . ' +1 day')));
        $this->db->where( 'invoices.archived', 0);

        return $this->db->get()->result_array();
    }

    function getProjectNames( $params ) {
        $this->db->select('idConstructionProject as id, projectName as name');
        $this->db->join('invoices', 'invoices.idInvoice = project.idInvoice', 'LEFT OUTER');
        $this->db->where( 'invoices.archived', 0);
        $this->db->order_by('projectName ASC');
        return $this->db->get('constructionproject as project')->result_array();
    }

}