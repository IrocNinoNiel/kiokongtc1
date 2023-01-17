<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Standards_model extends CI_Model {

    public function getComboSearch( $data ) {
		/**
		 * Added by Mak2x 
		 * Customize searching for encrypted field (Employee/User)
		 * 20-May-2022
		 * */ 
		if ( $data['tableName'] == 'employee')  {
			$data['query'] = "";
			$this->db->select( "sk" );
		}
		/** End of Customize searching for encrypted field (Employee/User) | 20-May-2022 */
			
        $this->db->select( " $data[tableNameColumn] as name, $data[tableIDColumn] as id " );
		$this->db->order_by( $data['tableNameColumn'], 'asc' );

        if( isset( $data['query'] ) ) 
			$this->db->like( $data['tableNameColumn'], $data['query'], 'both' );
		
		if( $data['statusColumn'] ) 
			$this->db->where( $data['statusColumn'], $data['statusValue'] );

        return $this->db->get( $data['tableName'] )->result_array();
    }
	
	public function _checkData($array){
		$table = $array['table'];
		$field = $array['field'];
		$value = $array['value'];
		$where = '';
		if ($this->db->field_exists('archive', $table)){
			$wh[] =  "archive != 1";
		}
		if(isset($array['exwhere'])){
			if( $array['exwhere'] ){
				$wh[] = $array['exwhere'];
			}
		}
		if(isset($wh)) $where = count($wh) > 0 ? " AND ".implode(' AND ',$wh) : "";
		$q =  $this->db->query("SELECT $field FROM $table WHERE $field = '".$value."' $where")->num_rows();
		return ($q > 0 ? true:false);
	}
	
	public function searchCombo( $data ){
		
		if( !$data['noSubfilter'] ){
				if( $data[0]['name'] == 'Status' && isset($data[0]['allowStatus']) && $data[0]['allowStatus'] == 1 ){

					$this->db->select( "DISTINCT(".$data[0][ 'field' ] . ") AS " . $data[0][ 'displayField' ] );
					$this->db->from("(SELECT 
								(CASE
									WHEN " . $data[0][ 'field' ] . " = 1 THEN 'Inactive'
									WHEN " . $data[0][ 'field' ] . " = 0 THEN 'Active'
								END)". " AS " . $data[0][ 'field' ]  . "
							FROM " . $data[0]['table'] . ") AS a");
					$this->db->like($data[0]['field'], $data['queryString'], "right");
					return $this->db->get()->result_array();
							
				}
				else{

					$this->db->select( $data[0][ 'field' ] . " AS " . $data[0][ 'displayField' ] );
					$this->db->like($data[0]['field'], $data['queryString']);
					$this->db->from( $data[0]['table'] );
					if( isset( $data[ 0 ][ 'groupBy' ] ) )
					{
						$this->db->group_by( $data[ 0 ][ 'groupBy' ] );
					}
					return $this->db->get()->result_array();
				}
		}	else{

			$this->db->select( $data[ 'field' ] . " AS " . $data[ 'displayField' ] );
			$this->db->like($data['field'], $data['queryString']);
			$this->db->from( $data['table'] );
			if( isset( $data[ 'groupBy' ] ) )
			{
				$this->db->group_by( $data[ 'groupBy' ] );
			}
			return $this->db->get()->result_array();
		}
		
	}
	
	public function getDateModified( $id, $col, $table ){
		$this->db->select( 'dateModified' );
		$this->db->where( $col, $id );
		return $this->db->get( $table )->row();
	}
	
	public function setLogs( $data ){
		$this->db->insert( 'logs', unsetParams( $data, 'logs' ) );
	}

    public function getSupplier( $params ){
        $idField = 'idSupplier';
        $nameField = 'name';
        $tableFrom = 'supplier';
        if( isset( $params['idField'] ) ){
            if( !empty( $params['idField'] ) ) $idField = $params['idField'];
        }
        if( isset( $params['nameField'] ) ){
            if( !empty( $params['nameField'] ) ) $nameField = $params['nameField'];
        }
        if( isset( $params['tableFrom'] ) ){
            if( !empty( $params['tableFrom'] ) ) $tableFrom = 'supplier';
        }
        $this->db->select( "$tableFrom.$idField as id, $tableFrom.$nameField as name, sk" );
		if( isset( $params['query'] ) ) $this->db->like( $nameField, $params['query'], 'both' );
		
		if( isset( $params['idAffiliate'] ) ) {
			$this->db->join('supplieraffiliate', "$tableFrom.idSupplier = supplieraffiliate.idSupplier", 'LEFT' );
			$this->db->where( 'supplieraffiliate.idAffiliate', $params['idAffiliate'] );
		}
		
        $this->db->order_by( $nameField, 'asc' );
        return $this->db->get( $tableFrom )->result_array();
    }

    
	function gridJournalEntry( $data = array() ){
		if( isset( $data['idInvoice'] ) || isset( $data['idBankRecon'] ) || isset( $data['idClosing'] ) ){
			$this->db->select( "
				p.credit
				,p.debit
				,p.explanation
				,c.idCoa
				,c.acod_c15 as code
				,c.aname_c30 as name
				,cc.idCostCenter as costcenterID
				,cc.costcenterName
			" );
			
			$this->db->from( 'posting as p' );
			$this->db->join( 'coa as c', 'c.idCoa = p.idCoa' );
			$this->db->join( 'costcenter as cc', 'cc.idCostCenter = p.idCostCenter', 'LEFT' );
			$this->db->where_not_in( 'c.archived', 1 );
			
			if( isset( $data['idInvoice'] ) ){
				$this->db->where( 'idInvoice', (int)$data['idInvoice'] );
			}
			elseif( isset( $data['idBankRecon'] ) ){
				$this->db->where( 'idBankRecon', (int)$data['idBankRecon'] );
			}
			elseif( isset( $data['idClosing'] ) ){
				$this->db->where( 'idClosing', (int)$data['idClosing'] );
			}
		}
		else{
			$this->db->select( "
			    0 as credit
			    ,0 as debit
			    ,'' as explanation
			    ,IFNULL( a.idCoa, b.idCoa ) as coaID
			    ,b.acod_c15 as code
			    ,b.aname_c30 as name
			    ,'' as costcenterID
			    ,'' as costcenterName" );
			$this->db->from( "(
					SELECT 
						*,
							( CASE
								WHEN 11 = $data[idModule] || 10 = $data[idModule] 	THEN debitRec
								WHEN 12 = $data[idModule] || 13 = $data[idModule] 	THEN creditPay
								WHEN 11 = $data[idModule] || 10 = $data[idModule] 	THEN accRec
								WHEN 12 = $data[idModule] || 13 = $data[idModule] 	THEN accPay
								WHEN 13 = $data[idModule]							THEN debitMemo
								WHEN 11 = $data[idModule] 							THEN creditMemo
								WHEN 12 = $data[idModule]							THEN inputTax
								WHEN 10 = $data[idModule]							THEN outputTax
								WHEN 10 = $data[idModule] 							THEN salesAccount
								WHEN 10 = $data[idModule] 							THEN salesDiscount
							END ) AS idCoa
					FROM
						defaultaccounts
					WHERE
						idAffiliate = $this->AFFILIATEID
				) AS a" );
			$this->db->join( "coa as b", "b.idCoa = a.idCoa", "RIGHT OUTER" );
			$this->db->join( "posting as p", "p.idCoa = a.idCoa", "LEFT OUTER" );
			$this->db->where_not_in( 'b.archived', 1 );
			$this->db->where( "a.idAffiliate", $this->AFFILIATEID );
		}

		$data['db'] = $this->db;
		$data['order_by'] = 'idPosting';
		$data['filterAffiliate'] = false;
		$data['pdf'] = true;
		
		return getGridList( $data );
    }
    
    public function getDefaultEntries( $params ){
		
		if( isset( $params['idModule'] ) ) {
			$this->db->select( 'idDefaultEntry as defaultentryID, purpose' );

			if( isset( $params['query'] ) ) $this->db->like( 'purpose', $params['query'], 'both' );

			$this->db->where( 'idModule', (int)$params['idModule'] );

			if( isset( $params['idReference'] ) ) $this->db->where( 'idReference', (int)$params['idReference'] );
			$this->db->order_by( 'purpose', 'asc' );

			return $this->db->get( 'defaultentry' )->result_array();
		} else {
			return [];
		}
    }
    
    function getDefaultAccounts( $data = array() ){
		$this->db->select( 'de.credit, de.debit, c.idCoa, c.acod_c15 as code, c.aname_c30 as name' );
		$this->db->from( 'defaultentryposting as de' );
		$this->db->join( 'coa as c','c.idCoa = de.idCoa' );
		$this->db->order_by( 'de.idDefaultPosting' );
		$this->db->where_not_in( 'c.archived', 1 );

		if( isset( $data['idDefaultEntry'] ) ){
			$this->db->where( 'de.idDefaultEntry', $data['idDefaultEntry'] );
		}
		$this->db->get();
		$query1 = $this->db->last_query();
		$this->db->select("
		    0 as credit
		    ,0 as debit
		    ,IFNULL( a.idCoa, b.idCoa ) as idCoa
		    ,b.acod_c15 as code
			,b.aname_c30 as name"
		);
		$this->db->from( "(
			SELECT 
				*,
					(CASE
						WHEN 11 = $data[idModule] OR 10 = $data[idModule]	THEN debitRec
						WHEN 12 = $data[idModule] OR 13 = $data[idModule]	THEN creditPay
						WHEN 11 = $data[idModule] OR 10 = $data[idModule]	THEN accRec
						WHEN 12 = $data[idModule] OR 13 = $data[idModule]	THEN accPay
						WHEN 13 = $data[idModule]							THEN debitMemo
						WHEN 11 = $data[idModule] 							THEN creditMemo
						WHEN 12 = $data[idModule]							THEN inputTax
						WHEN 10 = $data[idModule]							THEN outputTax
						WHEN 10 = $data[idModule] 							THEN salesAccount
						WHEN 10 = $data[idModule] 							THEN salesDiscount
					END) AS idCoa
			FROM
				defaultaccounts
			WHERE
				idAffiliate = $this->AFFILIATEID
		) AS a");
		$this->db->join( "coa as b", "b.idCoa = a.idCoa", "RIGHT OUTER" );
		$this->db->where_not_in( 'b.archived', 1 );
		$this->db->where( "a.idAffiliate", $this->AFFILIATEID );
		$this->db->get();
		$query2 = $this->db->last_query();
		return $this->db->query( "SELECT a.* FROM( $query1 ) as a UNION ALL SELECT b.* FROM( $query2 ) as b" )->result_array();
    }
    
    public function getCoa( $data, $cnt = false ){
        $this->db->select( 'c.aname_c30 as name, c.idCoa as idCoa, c.acod_c15 as code' );
        if( $data['displayField'] == 'name' ) $this->db->order_by( 'c.aname_c30' );
        else $this->db->order_by( 'c.acod_c15' );
        
        $this->db->from( 'coa as c' );
        /** Subsidiary account only **/
        if( isset( $data['accountType'] ) ) $this->db->where( 'c.accountType', (int)$data['accountType'] );
        else $this->db->where_in( 'c.accountType', array( 1, 2 ) );
        
        // if( $this->session->userdata('ISCURRENTMAIN') == 0 ){
            /** only accounts that has access per affiliate **/
            $this->db->join( 'coaaffiliate as ca','ca.idCoa = c.idCoa' );
            $this->db->where( 'ca.idAffiliate', $this->AFFILIATEID );
        // }
        if( !empty( $data['query'] ) ){
            if( $data['displayField'] == 'name' ) $this->db->like( 'c.aname_c30', $data['query'], 'both' );
            else $this->db->like( 'c.acod_c15', $data['query'], 'both' );
		}
		if( !$cnt ) $this->db->limit( ( $data['limit'] + $data['start'] ) );
		// $this->db->where_not_in( 'c.archived', 1 );
        
		if( !$cnt ) return $this->db->get()->result_array(); 
		else return $this->db->count_all_results();
    }

    public function getCOADetails( $params ){
        $this->db->select( 'idCoa, acod_c15, aname_c30' );
        $this->db->where( 'idCoa', $params['idCoa'] );
        return $this->db->get()->row_array();
	}
	
	public function getCustomer( $params ){
        $idField = 'idCustomer';
        $nameField = 'name';
        $tableFrom = 'customer';
        if( isset( $params['idField'] ) ){
            if( !empty( $params['idField'] ) ) $idField = $params['idField'];
        }
        if( isset( $params['nameField'] ) ){
            if( !empty( $params['nameField'] ) ) $nameField = $params['nameField'];
        }
        if( isset( $params['tableFrom'] ) ){
            if( !empty( $params['tableFrom'] ) ) $tableFrom = 'supplier';
        }
		$this->db->select( "customer.$idField as id, customer.$nameField as name, customer.creditLimit, customer.sk" );
		$this->db->join("customeraffiliate", "customeraffiliate.idCustomer = customer.idCustomer", "LEFT");
		$this->db->where("customeraffiliate.idAffiliate", $params['idAffiliate']);
        if( isset( $params['query'] ) ) $this->db->like( $nameField, $params['query'], 'both' );
        $this->db->order_by( $nameField, 'asc' );
        return $this->db->get( $tableFrom )->result_array();
	}

	function getAffiliateDetails( $data ){
		$this->db->select("    
							affiliate.idAffiliate,
							affiliate.affiliateName,
							affiliate.tagLine,
							affiliate.address,
							affiliate.contactPerson,
							contactNumber,
							affiliate.email,
							affiliate.tin,
							affiliate.vatPercent,
							affiliate.vatType,
							affiliate.checkedBy,
							affiliate.reviewedBy,
							a1.username AS approvedBy1,
							a2.username AS approvedBy2,
							affiliate.accSchedule,
							affiliate.month,
							affiliate.remarks,
							affiliate.refTag,
							affiliate.logo,
							affiliate.status,
							affiliate.mainTag,
							affiliate.location,
							affiliate.dateStart,
							affiliate.sk");
		$this->db->from("affiliate");
		$this->db->where("idAffiliate" ,$data['affiliateID']);
		$this->db->join('eu as a1', 'affiliate.approvedBy1 = a1.idEmployee', 'left');
		$this->db->join('eu as a2', 'affiliate.approvedBy2 = a2.idEmployee', 'left');
		return $this->db->get()->row_object();
	}

	public function getLastClosed( $params ){
		$this->db->select( 'idAffiliate, status' );
		$this->db->where( 'idModule', 35 );
		$this->db->where_not_in( 'archived', 1 );
		$this->db->where( 'year <=', $params['year'] );
		$this->db->where( 'month <', (int)$params['month'] );
		$this->db->order_by( 'month DESC, year DESC' );
		$this->db->limit( 1 );
		return $this->db->get( 'invoices' )->row_array();
	}

	public function getAffiliateDetailsValidation( $params ){
		$this->db->select( "DATE_FORMAT( dateStart, '%m' ) as month, DATE_FORMAT( dateStart, '%Y' ) as year, month as affiliateMonth, accSchedule, affiliateName" );
		$this->db->where( 'idAffiliate', (int)$params['idAffiliate'] );
		return $this->db->get( 'affiliate' )->row_array();
	}

}