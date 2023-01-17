<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Christian P. Daohog
 * Module: Budget vs. Actual
 * Date: May 23, 2022
 * Finished:
 * Description: This module allows authorized users to generate a budget vs. actual monitoring of a tagged project.
 * DB Tables:
 * */

class Budgetvsactual_model extends CI_Model {
	function getProjectNames( $params ) {
		$this->db->select('idConstructionProject as id, projectName as name');
		$this->db->join('invoices', 'invoices.idInvoice = project.idInvoice', 'LEFT OUTER');

        if(isset($params['status']) && $params['status'] != 0) $this->db->where('project.status', $params['status']);
		$this->db->where( 'invoices.archived', 0);
		$this->db->order_by('projectName ASC');
		return $this->db->get('constructionproject as project')->result_array();
	}

    function getBudgetVsActual($params) {
        $this->db->select('
            affiliate.affiliateName as affiliateName
            ,affiliate.sk
            ,project.projectName
            ,DATE_FORMAT(project.dateStart,"%m/%d/%Y") as contractEffectivity
            ,DATE_FORMAT(project.dateCompleted,"%m/%d/%Y") as contractExpiry
            ,( case project.status
                when 1 then "Suspended"
                when 2 then "Ongoing"
                when 3 then "Completed"
            END ) AS projectStatus,
            ,project.contractDuration
            ,invoices.amount as contractAmount
            ,DATE_FORMAT(invoices.date,"%m/%d/%Y") as date
            ,details.ocm
            ,details.contractorsProfit 
            ,details.vat
            ,sum(releasing.qty * releasing.unitCost ) as totalAmount
        ');

        $this->db->join('invoices', 'invoices.idInvoice = project.idInvoice', 'LEFT OUTER');
        $this->db->join('affiliate', 'affiliate.idAffiliate = invoices.idAffiliate', 'LEFT OUTER');
        $this->db->join('conssubactivity as subactivity', 'subactivity.idConstructionProject = project.idConstructionProject', 'LEFT OUTER');
        $this->db->join('consactivitydetails as details', 'details.idConsSubActivity = subactivity.idConsSubActivity', 'LEFT OUTER');
        $this->db->join('consprojectaccomplishment as acc', 'acc.idConstructionProject = project.idConstructionProject', 'LEFT OUTER');
        $this->db->join('constructionreleasing as releasing', 'releasing.idConsprojectAccomplishment = acc.idConsprojectAccomplishment', 'LEFT OUTER');

        $this->db->group_by(array("project.idConstructionProject", "details.idConsActivityDetails"));
        
        if($params['idAffiliate'] != 0)     $this->db->where('invoices.idAffiliate', $params['idAffiliate']);
        if($params['projectName'] != 0)     $this->db->where('project.idConstructionProject', $params['projectName']);
        if($params['projectStatus'] != 0)   $this->db->where('project.status', $params['projectStatus']);
        $this->db->where('invoices.date <', date('Y-m-d', strtotime($params['asOfDate'] . ' +1 day')));
        $this->db->where('invoices.archived', 0);

        return $this->db->get('constructionproject as project')->result_array();
    }

    function getTotalItemLaborEquip($params) {
        $par['idAffiliate'] = isset($params['idAffiliate']) && $params['idAffiliate'] != 0 ? $params['idAffiliate'] : false;
        $par['projectName'] = isset($params['projectName']) && $params['projectName'] != 0 ? $params['projectName'] : false;
        $par['projectStatus'] = isset($params['projectStatus']) && $params['projectStatus'] != 0 ? $params['projectStatus'] : false;
        $par['asOfDate'] = $params['asOfDate'];

        $totalItem = $this->getTotalItem($par);
        $TotalLabor = $this->getTotalLabor($par);
        $TotalEquip = $this->getTotalEquip($par);

        return $this->db->query($totalItem . ' UNION ALL ' . $TotalLabor . ' UNION ALL ' . $TotalEquip)->result_array();
    }

    function getTotalItem($params) {
        $this->db->select('
            details.idConsActivityDetails
            ,SUM(consitem.qty * consitem.unitCost) as total
            ,"item" as type');

        $this->db->from('constructionproject as project');
        $this->db->join('invoices', 'invoices.idInvoice = project.idInvoice', 'LEFT OUTER');
        $this->db->join('affiliate', 'affiliate.idAffiliate = invoices.idAffiliate', 'LEFT OUTER');
        $this->db->join('conssubactivity as subactivity', 'subactivity.idConstructionProject = project.idConstructionProject', 'LEFT OUTER');
        $this->db->join('consactivitydetails as details', 'details.idConsSubActivity = subactivity.idConsSubActivity', 'LEFT OUTER');
        $this->db->join('consactivitydetailsitem as consitem', 'consitem.idConsActivityDetails = details.idConsActivityDetails', 'LEFT OUTER');

        $this->db->group_by(array("details.idConsActivityDetails"));
        
        if($params['idAffiliate'])      $this->db->where('invoices.idAffiliate', $params['idAffiliate']);
        if($params['projectName'])      $this->db->where('project.idConstructionProject', $params['projectName']);
        if($params['projectStatus'])    $this->db->where('project.status', $params['projectStatus']);

        $this->db->where('invoices.date <', date('Y-m-d', strtotime($params['asOfDate'] . ' +1 day')));
        $this->db->where('invoices.archived', 0);

        return $this->db->get_compiled_select();
    }

    function getTotalLabor($params) {
        $this->db->select('
            details.idConsActivityDetails
            ,SUM(labor.qty * labor.unitCost) as total
            ,"labor" as type');

        $this->db->from('constructionproject as project');
        $this->db->join('invoices', 'invoices.idInvoice = project.idInvoice', 'LEFT OUTER');
        $this->db->join('affiliate', 'affiliate.idAffiliate = invoices.idAffiliate', 'LEFT OUTER');
        $this->db->join('conssubactivity as subactivity', 'subactivity.idConstructionProject = project.idConstructionProject', 'LEFT OUTER');
        $this->db->join('consactivitydetails as details', 'details.idConsSubActivity = subactivity.idConsSubActivity', 'LEFT OUTER');
        $this->db->join('consactivitydetailslabor as labor', 'labor.idConsActivityDetails = details.idConsActivityDetails', 'LEFT OUTER');

        $this->db->group_by(array("details.idConsActivityDetails"));
       
        if($params['idAffiliate'])      $this->db->where('invoices.idAffiliate', $params['idAffiliate']);
        if($params['projectName'])      $this->db->where('project.idConstructionProject', $params['projectName']);
        if($params['projectStatus'])    $this->db->where('project.status', $params['projectStatus']);

        $this->db->where('invoices.date <', date('Y-m-d', strtotime($params['asOfDate'] . ' +1 day')));
        $this->db->where('invoices.archived', 0);

        return $this->db->get_compiled_select();
    }

    function getTotalEquip($params) {
        $this->db->select('
            details.idConsActivityDetails
            ,SUM(equip.qty * equip.unitCost) as total
            ,"equip" as type');

        $this->db->from('constructionproject as project');
        $this->db->join('invoices', 'invoices.idInvoice = project.idInvoice', 'LEFT OUTER');
        $this->db->join('affiliate', 'affiliate.idAffiliate = invoices.idAffiliate', 'LEFT OUTER');
        $this->db->join('conssubactivity as subactivity', 'subactivity.idConstructionProject = project.idConstructionProject', 'LEFT OUTER');
        $this->db->join('consactivitydetails as details', 'details.idConsSubActivity = subactivity.idConsSubActivity', 'LEFT OUTER');
        $this->db->join('consactivitydetailsequip as equip', 'equip.idConsActivityDetails = details.idConsActivityDetails', 'LEFT OUTER');

        $this->db->group_by(array("details.idConsActivityDetails"));
        
        if($params['idAffiliate'])      $this->db->where('invoices.idAffiliate', $params['idAffiliate']);
        if($params['projectName'])      $this->db->where('project.idConstructionProject', $params['projectName']);
        if($params['projectStatus'])    $this->db->where('project.status', $params['projectStatus']);

        // if(isset($params['idAffiliate_Budgetvsactual']) && $params['idAffiliate_Budgetvsactual'] != 0)     $this->db->where('invoices.idAffiliate', $params['idAffiliate_Budgetvsactual']);
        // if(isset($params['projectName_Budgetvsactual']) && $params['projectName_Budgetvsactual'] != 0)     $this->db->where('project.idConstructionProject', $params['projectName_Budgetvsactual']);
        // if(isset($params['projectStatus_Budgetvsactual']) && $params['projectStatus_Budgetvsactual'] != 0)   $this->db->where('project.status', $params['projectStatus_Budgetvsactual']);
        // $this->db->where('invoices.date <', date('Y-m-d', strtotime($params['asOfDate_Budgetvsactual'] . ' +1 day')));
        // $this->db->where('invoices.archived', 0);

        $this->db->where('invoices.date <', date('Y-m-d', strtotime($params['asOfDate'] . ' +1 day')));
        $this->db->where('invoices.archived', 0);
        
        return $this->db->get_compiled_select();
    }
}


