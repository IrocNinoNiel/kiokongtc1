<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Developer: Niño Niel B. Iroc
 * Module: Cash Advance
 * Date: Jan 10, 2023
 * Finished:
 * Description: 
 * DB Tables:
 * */

class Cashadvance_model extends CI_Model {
	function getProjectNames( $params ) {
		$this->db->select('idConstructionProject as id, projectName as name');
		$this->db->join('invoices', 'invoices.idInvoice = project.idInvoice', 'LEFT OUTER');

        if(isset($params['status']) && $params['status'] != 0) $this->db->where('project.status', $params['status']);
		$this->db->where( 'invoices.archived', 0);
		$this->db->order_by('projectName ASC');
		return $this->db->get('constructionproject as project')->result_array();
	}

    function getDrivers( $params ){
        $this->db->select("employee.idEmployee AS id, employee.name, employee.sk");
        $this->db->join("employment", "employment.idEmployee = employee.idEmployee", "left");
        $this->db->where('employee.archived', 0 ); //Driver
        $this->db->where('employment.classification', 42 ); //Driver
        return $this->db->get('employee')->result_array();
    }

    function saveCashAdvance( $params ){
        if( isset($params['onEdit']) && $params['onEdit'] ) {
            $this->db->where('cashAdvanceID', $params['idCashAdvanceID']);
            $this->db->update('cashadvance', unsetParams( $params, 'cashadvance' ) );
            $idAccomplishment = $params['cashAdvanceID'];
        } else { 
            $this->db->insert( 'cashadvance', unsetParams( $params, 'cashadvance' ) );
            $idCashAdvance = $this->db->insert_id();
        }
        return $idCashAdvance;
    }

    function saveLiquidation( $params ){
        $this->db->insert( 'liquidation', unsetParams( $params, 'liquidation' ) );
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

}


