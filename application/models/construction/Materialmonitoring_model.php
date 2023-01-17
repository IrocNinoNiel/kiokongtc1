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
			item.sk
			,item.itemName
			,item.itemPrice
			,unit.unitCode
			,consitem.qty as approvedQty
			,consitem.unitCost as approvedCost
			,(consitem.qty * consitem.unitCost) as approvedAmount
			,SUM(release.qty) AS totalReleaseQty"); 

		$this->db->join('consactivitydetails as details',   'details.idConsActivityDetails = consitem.idConsActivityDetails',       'LEFT OUTER');
		$this->db->join('conssubactivity as subactivity',   'subactivity.idConsSubActivity = details.idConsSubActivity',            'LEFT OUTER');
		$this->db->join('constructionproject as project',   'project.idConstructionProject = subactivity.idConstructionProject',    'LEFT OUTER');
		$this->db->join('invoices',                         'invoices.idInvoice = project.idInvoice',                               'LEFT OUTER');
		$this->db->join('affiliate',                        'affiliate.idAffiliate = invoices.idAffiliate',                         'LEFT OUTER');
		$this->db->join('item',                             'item.idItem = consitem.idItem',                                        'LEFT OUTER');
		$this->db->join('unit',                             'unit.idUnit = item.idUnit',                                            'LEFT OUTER');

		$this->db->join('constructionreleasing as release',	'release.fIdent = consitem.idConsActivityDetailsItem',                                            'LEFT OUTER');

		$this->db->where('invoices.idAffiliate',           $params['idAffiliate']);
		$this->db->where('project.idConstructionProject',  $params['projectName']);
		$this->db->where('invoices.date <', date('Y-m-d', strtotime($params['asOfDateBalance'] . ' +1 day')));
		$this->db->where( 'invoices.archived', 0);

		$this->db->group_by('consitem.idConsActivityDetailsItem');

		return $this->db->get('consactivitydetailsitem as consitem')->result_array();
	}

	function getLedger( $params ) {
		$this->db->select('
			affiliate.affiliateName
			,affiliate.sk as affiliateSK
			,convert(invoices.date, date) as date
			,concat(reference.code, "-", invoices.referenceNum) as referenceNum
			,releasing.qty as ledgerQty
			,releasing.unitCost as ledgerUnitCost
			,unit.unitCode as ledgerUnit');

		$this->db->join('constructionproject as project', 'project.idConstructionProject = acc.idConstructionProject',    'LEFT OUTER');
		$this->db->join('invoices', 'invoices.idInvoice = acc.idInvoice', 'LEFT OUTER');
		$this->db->join('reference', 'reference.idReference = invoices.idReference', 'LEFT OUTER');
		$this->db->join('affiliate', 'affiliate.idAffiliate = invoices.idAffiliate', 'LEFT OUTER');
		$this->db->join('constructionreleasing as releasing', 'releasing.idConsprojectAccomplishment = acc.idConsprojectAccomplishment', 'LEFT OUTER');
		$this->db->join('consactivitydetailsitem as consitem', 'consitem.idConsActivityDetailsItem = releasing.fIdent', 'LEFT OUTER');
		$this->db->join('item', 'item.idItem = consitem.idItem', 'LEFT OUTER');
		$this->db->join('unit', 'unit.idUnit = item.idUnit', 'LEFT OUTER');						  
		
		if(isset($params['idAffiliate']) && $params['idAffiliate']  != 0) $this->db->where('invoices.idAffiliate', $params['idAffiliate']);
		$this->db->where('project.idConstructionProject',  $params['projectName']);
		$this->db->where('consitem.idItem',               $params['itemName']);
		$this->db->where('invoices.date <', date('Y-m-d', strtotime($params['asOfDateLedger'] . ' +1 day')));
		$this->db->where( 'invoices.archived', 0);
		$this->db->where( 'releasing.type', 1);
		$this->db->order_by('invoices.date desc, invoices.referenceNum desc');
		
		return $this->db->get('consprojectaccomplishment as acc')->result_array();
	}

	function getLedgerOld ( $params ) {
		$this->db->select('
			affiliate.affiliateName
			,affiliate.sk as affiliateSK
			,convert(invoices.date, date) as date
			,concat(reference.code, "-", invoices.referenceNum) as referenceNum
			,consitem.unitCost as approvedCost
			,unit.unitCode as ledgerUnit ');

		$this->db->join('consactivitydetails as details',   'details.idConsActivityDetails = consitem.idConsActivityDetails',       'LEFT OUTER');
		$this->db->join('conssubactivity as subactivity',   'subactivity.idConsSubActivity = details.idConsSubActivity',            'LEFT OUTER');
		$this->db->join('constructionproject as project',   'project.idConstructionProject = subactivity.idConstructionProject',  'LEFT OUTER');
		$this->db->join('invoices',                         'invoices.idInvoice = project.idInvoice',                           'LEFT OUTER');
		$this->db->join('reference',                        'reference.idReference = invoices.idReference',                     'LEFT OUTER');
		$this->db->join('affiliate',                        'affiliate.idAffiliate = invoices.idAffiliate',                     'LEFT OUTER');
		$this->db->join('item',                             'item.idItem = consitem.idItem',                                   'LEFT OUTER');
		$this->db->join('unit',                             'unit.idUnit = item.idUnit',                                        'LEFT OUTER');
		
		if(isset($params['idAffiliate']) && $params['idAffiliate']  != 0) $this->db->where('invoices.idAffiliate', $params['idAffiliate']);
		$this->db->where('project.idConstructionProject',  $params['projectName']);
		$this->db->where('consitem.idItem',               $params['itemName']);
		$this->db->where('invoices.date <', date('Y-m-d', strtotime($params['asOfDateLedger'] . ' +1 day')));
		$this->db->where( 'invoices.archived', 0);

		return $this->db->get('consactivitydetailsitem as consitem')->result_array();
	}

	function getProjectNames( $params ) {
		$this->db->select('idConstructionProject as id, projectName as name');
		$this->db->join('invoices', 'invoices.idInvoice = project.idInvoice', 'LEFT OUTER');
		$this->db->where( 'invoices.archived', 0);
		$this->db->order_by('projectName ASC');
		return $this->db->get('constructionproject as project')->result_array();
	}

	function getItems( $params ){
		$this->db->select("item.idItem as id, item.itemName as name, item.sk");
		$this->db->join('unit', 'unit.idUnit = item.idUnit', 'left');
		$this->db->where('item.archived', 0 );

		$this->db->order_by('item.itemName asc');
		return $this->db->get('item')->result_array();
	}
}

