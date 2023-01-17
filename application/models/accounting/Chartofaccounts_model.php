<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Jayson Dagulo
 * Module       : Chart of account Settings
 * Date         : Dec 19, 2019
 * Finished     : Feb 03, 2020
 * Description  : This module allows authorized users to set (add, edit and delete) an account that will be used in journal entries.
 * DB Tables    : coa, coahistory, coaaffiliate, coaaffiliatehistory and logs
 * */ 
class Chartofaccounts_model extends CI_Model {
    
    public function getHeader($data){
		$this->db->select( "accod_c2 as id, aname_c30 as name, accID" );
		$this->db->where( 'accountType', 1 );
		$this->db->where( 'mocod_c1', $data['mocod_c1'] );
		$this->db->where( 'chcod_c1', $data['chcod_c1'] );
        return $this->db->get ('coa' )->result_array();
    }

    public function getAccod( $data ){
		$this->db->select( "max( accod_c2 ) as accod_c2" );
		$this->db->where( 'mocod_c1', $data['mocod_c1'] );
		$this->db->where( 'chcod_c1', $data['chcod_c1'] );
        return $this->db->get( 'coa' )->row_array();
    }
    
    public function getSucod( $data ){
		$this->db->select( "max( sucod_c3 ) as sucod_c3" );
		$this->db->where( 'mocod_c1', $data['mocod_c1'] );
		$this->db->where( 'chcod_c1', $data['chcod_c1'] );
		$this->db->where( 'accod_c2', $data['accod_c2'] );
        return $this->db->get ('coa' )->row_array();
	}

	public function getCoaAffiliate( $params ){
		$idCoa = ( isset( $params['idCoa'] )? (int)$params['idCoa'] : 0 );
		$this->db->select( "a.idAffiliate, a.affiliateName,(CASE WHEN b.idCoaAffiliate > 0 THEN 1 ELSE 0 END) as chk, a.sk" );
		$this->db->join( 'coaaffiliate as b', 'b.idAffiliate = a.idAffiliate AND b.idCoa = ' . $idCoa, 'left outer' );
		$this->db->where_not_in( 'a.archived', 1 );
		$this->db->order_by( 'a.affiliateName', 'asc' );
		$this->db->group_by("a.idAffiliate, a.affiliateName, b.idCoaAffiliate");
		return $this->db->get( 'affiliate as a' )->result_array();
	}

	public function checkCoaAffiliate( $idCoa, $idAffiliate ){
		$cnt = 0;
		$this->db->select( '*' );
		$this->db->where( 'idCoa', $idCoa );
		$this->db->where( 'idAffiliate', $idAffiliate );
		$cnt += $this->db->count_all_results( 'bankaccount' );

		$this->db->select( '*' );
		$this->db->where( 'idCoa', $idCoa );
		$this->db->where( 'idAffiliate', $idAffiliate );
		$cnt += $this->db->count_all_results( 'gl' );

		$this->db->select( 'a.*' );
		$this->db->join( 'accountbegbal as b', 'b.idAccBegBal = a.idAccBegBal' );
		$this->db->where( 'idCoa', $idCoa );
		$this->db->where( 'idAffiliate', $idAffiliate );
		$cnt += $this->db->count_all_results( 'begbal as a' );

		$this->db->select( '*' );
		$this->db->join( 'defaultentryaffiliate as b', 'b.idDefaultEntry = a.idDefaultEntry' );
		$this->db->join( 'defaultentryposting as c', 'c.idDefaultEntry = a.idDefaultEntry' );
		$this->db->where( 'c.idCoa', $idCoa );
		$this->db->where( 'b.idAffiliate', $idAffiliate );
		$cnt += $this->db->count_all_results( 'defaultentry as a' );

		$this->db->select( '*' );
		$this->db->join( 'posting as b', 'b.idInvoice = a.idInvoice' );
		$this->db->where( 'a.idAffiliate', $idAffiliate );
		$this->db->where( 'b.idCoa', $idCoa );
		$cnt += $this->db->count_all_results( 'invoices as a' );

		$cnt += $this->db->query("SELECT count(*) as cnt FROM defaultaccounts WHERE debitRec = $idCoa AND  idAffiliate = $idAffiliate")->row_object()->cnt;
		$cnt += $this->db->query("SELECT count(*) as cnt FROM defaultaccounts WHERE creditPay = $idCoa AND  idAffiliate = $idAffiliate")->row_object()->cnt;
		$cnt += $this->db->query("SELECT count(*) as cnt FROM defaultaccounts WHERE accRec = $idCoa AND  idAffiliate = $idAffiliate")->row_object()->cnt;
		$cnt += $this->db->query("SELECT count(*) as cnt FROM defaultaccounts WHERE accPay = $idCoa AND  idAffiliate = $idAffiliate")->row_object()->cnt;
		$cnt += $this->db->query("SELECT count(*) as cnt FROM defaultaccounts WHERE debitMemo = $idCoa AND  idAffiliate = $idAffiliate")->row_object()->cnt;
		$cnt += $this->db->query("SELECT count(*) as cnt FROM defaultaccounts WHERE creditMemo = $idCoa AND  idAffiliate = $idAffiliate")->row_object()->cnt;
		$cnt += $this->db->query("SELECT count(*) as cnt FROM defaultaccounts WHERE inputTax = $idCoa AND  idAffiliate = $idAffiliate")->row_object()->cnt;
		$cnt += $this->db->query("SELECT count(*) as cnt FROM defaultaccounts WHERE outputTax = $idCoa AND  idAffiliate = $idAffiliate")->row_object()->cnt;
		$cnt += $this->db->query("SELECT count(*) as cnt FROM defaultaccounts WHERE salesAccount = $idCoa AND  idAffiliate = $idAffiliate")->row_object()->cnt;
		$cnt += $this->db->query("SELECT count(*) as cnt FROM defaultaccounts WHERE salesDiscount = $idCoa AND  idAffiliate = $idAffiliate")->row_object()->cnt;
		$cnt += $this->db->query("SELECT count(*) as cnt FROM defaultaccounts WHERE otherIncome = $idCoa AND  idAffiliate = $idAffiliate")->row_object()->cnt;
		$cnt += $this->db->query("SELECT count(*) as cnt FROM defaultaccounts WHERE retainedEarnings = $idCoa AND  idAffiliate = $idAffiliate")->row_object()->cnt;
		$cnt += $this->db->query("SELECT count(*) as cnt FROM defaultaccounts WHERE incomeTaxProvision = $idCoa AND  idAffiliate = $idAffiliate")->row_object()->cnt;
		return $cnt;
	}

	public function getHistory( $params ){
		$this->db->select( "
			idCoa
			,(CASE 
				WHEN accountType = 1 THEN CONCAT('<b>',acod_c15,'</b>')
				ELSE acod_c15
			END) as acod_c15
			,(CASE 
				WHEN accountType = 1 THEN CONCAT('<b>',aname_c30,'</b>')
				ELSE aname_c30
			END) as aname_c30
			,norm_c2 as normalBalance
			,(CASE
				WHEN accID = 1 THEN 'Regular Account'
				WHEN accID = 2 THEN 'Cash Account'
				WHEN accID = 3 THEN 'Receivable Account'
				WHEN accID = 4 THEN 'Allowance for Bad Debits'
				WHEN accID = 5 THEN 'Inventories'
				WHEN accID = 6 THEN 'Raw Materials'
				WHEN accID = 7 THEN 'Work in Progress'
				WHEN accID = 8 THEN 'Finished Goods'
				WHEN accID = 9 THEN 'Properties and Equipments'
				WHEN accID = 10 THEN 'Accumulated Depreciation'
				WHEN accID = 11 THEN 'Accumulated Amortization'
				WHEN accID = 12 THEN 'Payable Account'
				WHEN accID = 13 THEN 'Cost of Sales'
				WHEN accID = 14 THEN 'Sales'
				WHEN accID = 15 THEN 'Sales Debits'
				WHEN accID = 16 THEN 'Other Income'
				WHEN accID = 17 THEN 'Operating Expenses'
				WHEN accID = 18 THEN 'Cost of Goods Manufactured'
				WHEN accID = 19 THEN 'Purchase Credits'
				WHEN accID = 20 THEN 'Direct Labor'
				WHEN accID = 21 THEN 'Manufacturing Overhead'
				WHEN accID = 22 THEN 'Applied Factory Overhead'
				WHEN accID = 23 THEN 'Other Expenses'
				WHEN accID = 24 THEN 'Retained Earnings'
				WHEN accID = 25 THEN 'Creditable Income Tax'
				WHEN accID = 26 THEN 'Capital'
				WHEN accID = 27 THEN 'Withdrawals'
				WHEN accID = 28 THEN 'Other Assets'
			END) as categoryName
			,accountType
			,mocod_c1
			,chcod_c1
			,accod_c2
		" );
		if( isset( $params['filterBy'] ) ){
			if( isset( $params['filterValue'] ) ){
				if( (int)$params['filterValue'] != -1 ){
					if( $params['filterBy'] == 'idCoa' ){
						$this->db->where( 'idCoa', (int)$params['filterValue'] );
					}
					elseif( $params['filterBy'] == 'accID' ){
						$this->db->where( 'accID', (int)$params['accID'] );
					}
				}
			}
		}
		$this->db->where_not_in( 'archived', 1 );
		$this->db->from('coa');
		
		$params['db'] = $this->db;
		$params['order_by'] = 'idCoa asc';
		
		return getGridList( $params );

	}

	public function saveForm( $params ){
		$edited					= $params['onEdit'];
		$id						= $params['idCoaOld'];
		$params['dateModified']	= date("Y-m-d H:i:s");
		
		if(	$params['norm_c2'] == 1	) $params['norm_c2'] = 'DR';
		else $params['norm_c2'] = 'CR';
		
		if( $edited ) $this->db->update( 'coa', unsetParams( $params, 'coa' ), array( 'idCoa' => $id ) );
		else{
			$this->db->insert( 'coa' ,unsetParams( $params, 'coa' ) );
			$id = $this->db->insert_id();
		}
		return $id;
	}
	
	public function saveFormHistory( $params ){
		$this->db->insert( 'coahistory', unsetParams( $params, 'coahistory' ) );
		return $this->db->insert_id();
	}

	public function deleteCoaAffiliates( $params ){
		$tables	= array( 'coaaffiliate' );
		$this->db->where( 'idCoa', $params['idCoa'] );
		$this->db->delete( $tables );
	}

	public function insertCoaAffiliates( $params ){
		$this->db->insert( 'coaaffiliate', unsetParams( $params, 'coaaffiliate' ) );
	}

	public function insertCoaAffiliatesHistory( $params ){
		$this->db->insert( 'coaaffiliatehistory', unsetParams( $params, 'coaaffiliatehistory' ) );
	}
	
	public function retrieveData( $params ){
		$this->db->select( "a.*, (CASE WHEN a.norm_c2 = 'DR' THEN 1 ELSE 2 END) as norm_c2" );
		$this->db->where( 'a.idCoa', $params['idCoa'] );
		return $this->db->get( 'coa as a' )->result_array();
	}

	public function getRecordsUsed( $params ){
		$this->db->where( "idCoa", $params['idCoa'] );
		$this->db->get( "posting" )->result_array();
		return ($this->db->affected_rows() > 0 ? true : false);
	}
	
	public function checkIfHasSubsidiary( $params ){
		$this->db->select( '*' );
		$this->db->where( 'accountType', 2 );
		$this->db->where( 'mocod_c1', (int)$params['mocod_c1'] );
		$this->db->where( 'chcod_c1', (int)$params['chcod_c1'] );
		$this->db->where( 'accod_c2', (int)$params['accod_c2'] );
		return $this->db->count_all_results( 'coa' );
	}

	public function delete( $params ){
		$this->db->set( 'archived', 1 );
		$this->db->where('idCoa', $params['idCoa']);
		$this->db->update( 'coa' );
	}

	public function getAllCOA(){
		$this->db->select( 'idCoa' );
		$this->db->where_not_in( 'archived', 1 );
		return $this->db->get('coa')->result_array();
	}

	public function getAllAffiliate(){
		$this->db->select( 'idAffiliate' );
		$this->db->where_not_in( 'archived', 1 );
		return $this->db->get( 'affiliate' )->result_array();
	}

	public function truncateDBCoaAffiliate(){
		$this->db->query( 'TRUNCATE TABLE coaaffiliate' );
	}

	public function saveCoaAffiliate( $params ){
		$this->db->insert_batch( 'coaaffiliate', $params );
	}


	public function chkHeaderName( $aname_c30 ){
		$this->db->select( '*' );
		$this->db->where( 'aname_c30', $aname_c30 );
		$this->db->where( 'archived', 0 );
		return ($this->db->count_all_results('coa') > 0 ? true : false);
	}

	public function saveImportedCOA( $params ){
		$this->db->insert( 'coa', unsetParams( $params, 'coa' ) );
		return $this->db->insert_id();
	}
    
}