<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Jays
 * Module       : Batch Reconciliation
 * Date         : Mar. 03, 2020
 * Finished     : 
 * Description  : This module allows authorized user to reconcile banks for the cheques to be tagged as cleared.
 * DB Tables    : 
 * */ 
class Bankrecon_model extends CI_Model {

    public function viewAll( $params ){
        $this->db->select( "
            CONCAT( reference.code, '-', bankrecon.referenceNum ) as reference
            ,bankrecon.reconDate
            ,(CASE bankrecon.reconMonth
                WHEN 1 THEN 'January'
                WHEN 2 THEN 'February'
                WHEN 3 THEN 'March'
                WHEN 4 THEN 'April'
                WHEN 5 THEN 'May'
                WHEN 6 THEN 'June'
                WHEN 7 THEN 'July'
                WHEN 8 THEN 'August'
                WHEN 9 THEN 'September'
                WHEN 10 THEN 'October'
                WHEN 11 THEN 'November'
                WHEN 12 THEN 'December'
                ELSE ''
            END) as reconMonthDis
            ,bankrecon.reconMonth
            ,bankrecon.reconYear
            ,bankrecon.idBankRecon
            ,affiliate.affiliateName
            ,bankrecon.description
            ,bank.bankName
            ,bankaccount.bankAccount
            ,bankrecon.idAffiliate
            ,IFNULL( bankrecon.unAdjustedBankBalance, 0 ) as unAdjustedBankBalance
            ,bankrecon.idBankAccount
            ,bankrecon.idReference
            ,bankrecon.referenceNum
        " );
        $this->db->from( 'bankrecon' );
        $this->db->join( 'reference', 'reference.idReference = bankrecon.idReference', 'left outer' );
        $this->db->join( 'affiliate', 'affiliate.idAffiliate = bankrecon.idAffiliate', 'left outer' );
        $this->db->join( 'bank', 'bank.idBank = bankrecon.idBank', 'left outer' );
        $this->db->join( 'bankaccount', 'bankaccount.idBankAccount = bankrecon.idBankAccount', 'left outer' );
        $this->db->where_not_in( 'bankrecon.archived',  1 );
        $this->db->where( 'bankrecon.idAffiliate', $this->AFFILIATEID );
        if( isset( $params['filterValue'] ) ){
            if( (int)$params['filterValue'] > 0 ) $this->db->where( 'bankrecon.idBankRecon', (int)$params['filterValue'] );
        }

        $params['db'] = $this->db;
        $params['order_by'] = 'bankrecon.reconDate DESC, bankrecon.idBankRecon DESC';

        return getGridList($params);
    }

    public function getBanks( $params ){
        $this->db->select( 'idBank as id, bankName as name, sk' );
        $this->db->where_not_in( 'archived', 1 );
        if( isset( $params['query'] ) ) $this->db->like( 'bankName', $params['query'], 'both' );
        $this->db->order_by( 'bankName' );
        return $this->db->get( 'bank' )->result_array();
    }

    public function getBankAccount( $params ){
        if( !isset( $params['idBank'] ) ) $params['idBank'] = 0;
        $this->db->select( 'bankaccount.idBankAccount as id, bankaccount.bankAccount as name, bankaccount.idCoa, coa.aname_c30, coa.acod_c15, sk' );
        $this->db->join( 'coa', 'coa.idCoa = bankaccount.idCoa', 'left outer' );
        if( isset( $params['query'] ) ) $this->db->like( 'bankaccount.bankAccount', $params['query'], 'both' );
        $this->db->where( 'bankaccount.idAffiliate', $this->AFFILIATEID );
        $this->db->where( 'bankaccount.idBank', $params['idBank'] );
        $this->db->where_not_in( 'bankaccount.archived', 1 );
        $this->db->order_by( 'bankaccount.bankAccount' );
        return $this->db->get( 'bankaccount' )->result_array();
    }

    public function getReceipts( $params ){
        $this->db->select( "
            postdated.idPostdated
            ,postdated.remarks
            ,CONCAT( reference.code, '-',invoices.referenceNum ) as reference
            ,IF( 
                postdated.date = '0000-00-00' 
                ,DATE_FORMAT( invoices.date, '%m/%d/%Y' )
                ,DATE_FORMAT( postdated.date, '%m/%d/%Y' ) 
            ) as date
            ,postdated.amount
            ,(CASE
                WHEN postdated.idBankRecon = $params[idBankRecon] THEN 1
                ELSE 0
            END) as chk
        " );
        $this->db->join( 'invoices', 'invoices.idInvoice = postdated.idInvoice', 'left outer' );
        $this->db->join( 'reference', 'reference.idReference = invoices.idReference', 'left outer' );
        $this->db->where_not_in( 'invoices.archived', 1 );
        $this->db->where_not_in( 'invoices.cancelTag', 1 );
        $this->db->where( 'postdated.date <=', date( 'Y-m-t', strtotime( $params['reconYear'] . '-' . $params['reconMonth'] ) ) );
        $this->db->where( 'postdated.idBankAccount', (int)$params['idBankAccount'] );
        $this->db->where_not_in( 'postdated.idBankAccount', 0 );
        if( isset( $params['pdf'] ) ) $this->db->where( 'IFNULL( postdated.idBankRecon, 0 ) =', (int)$params['idBankRecon'] );
        else $this->db->where( "( ( postdated.status = 1 AND IFNULL( postdated.idBankRecon, 0 ) = 0 ) OR IFNULL( postdated.idBankRecon, 0 ) = $params[idBankRecon] )" );
        $this->db->where( 'invoices.idModule', 28 ); /* receipts */
        $this->db->where_not_in( 'invoices.cancelTag', 1 ); /* do not include cancelled transactions */
        return $this->db->get( 'postdated' )->result_array();
    }

    public function getDisbursements( $params ){
        $this->db->select( "
            postdated.idPostdated
            ,postdated.remarks
            ,CONCAT( reference.code, '-',invoices.referenceNum ) as reference
            ,IF( 
                postdated.date = '0000-00-00' 
                ,DATE_FORMAT( invoices.date, '%m/%d/%Y' )
                ,DATE_FORMAT( postdated.date, '%m/%d/%Y' ) 
            ) as date
            ,postdated.amount
            ,(CASE
                WHEN postdated.idBankRecon = $params[idBankRecon] THEN 1
                ELSE 0
            END) as chk
        " );
        $this->db->join( 'invoices', 'invoices.idInvoice = postdated.idInvoice', 'left outer' );
        $this->db->join( 'reference', 'reference.idReference = invoices.idReference', 'left outer' );
        $this->db->where_not_in( 'invoices.archived', 1 );
        $this->db->where_not_in( 'invoices.cancelTag', 1 );
        $this->db->where( 'postdated.date <=', date( 'Y-m-t', strtotime( $params['reconYear'] . '-' . $params['reconMonth'] ) ) );
        $this->db->where( 'postdated.idBankAccount', (int)$params['idBankAccount'] );
        $this->db->where_not_in( 'postdated.idBankAccount', 0 );
        if( isset( $params['pdf'] ) ) $this->db->where( 'IFNULL( postdated.idBankRecon, 10 ) =', (int)$params['idBankRecon'] );
        else $this->db->where( "( ( postdated.status = 1 AND IFNULL( postdated.idBankRecon, 0 ) = 0 ) OR IFNULL( postdated.idBankRecon, 0 ) = $params[idBankRecon] )" );
        $this->db->where( 'invoices.idModule', 45 ); /* disbursements */
        $this->db->where_not_in( 'invoices.cancelTag', 1 ); /* do not include cancelled transactions */
        return $this->db->get( 'postdated' )->result_array();
    }

    public function getBookBeginningBalance( $params ){
        return $this->db->query( "
            SELECT
                SUM( IFNULL( posting.debit, 0 ) ) as debit
                ,SUM( IFNULL( posting.credit, 0 ) ) as credit
                ,coa.norm_c2
                ,posting.idCoa
            FROM posting
            LEFT OUTER JOIN invoices
                ON( invoices.idInvoice = posting.idInvoice )
            LEFT OUTER JOIN bankrecon
                ON( bankrecon.idBankRecon = posting.idBankRecon )
            LEFT OUTER JOIN accountbegbal
                ON( accountbegbal.idAccBegBal = posting.idAccBegBal )
            JOIN coa
                ON( coa.idCoa = posting.idCoa )
            WHERE
                ( ( invoices.status = 2 AND invoices.cancelTag NOT IN( 1 ) )
                    OR bankrecon.archived NOT IN( 1 )
                    OR accountbegbal.idAccBegBal > 0 )
                AND (
                    DATE_FORMAT( invoices.date, '%Y-%m-%d' ) < '$params[sdate]'
                    OR DATE_FORMAT( bankrecon.reconDate, '%Y-%m-%d' ) < '$params[sdate]'
                    OR DATE_FORMAT( accountbegbal.date, '%Y-%m-%d' ) < '$params[sdate]'
                )
                AND (
                    invoices.idAffiliate = $this->AFFILIATEID
                    OR bankrecon.idAffiliate = $this->AFFILIATEID
                    OR accountbegbal.idAffiliate = $this->AFFILIATEID
                )
                AND posting.idCoa = $params[idCoa]
            GROUP BY coa.norm_c2, posting.idCoa
        " )->result_array();
    }

    public function getBookCurrentBalance( $params ){
        return $this->db->query( "
            SELECT
                SUM( IFNULL( posting.debit, 0 ) ) as debit
                ,SUM( IFNULL( posting.credit, 0 ) ) as credit
                ,coa.norm_c2
                ,posting.idCoa
            FROM posting
            LEFT OUTER JOIN invoices
                ON( invoices.idInvoice = posting.idInvoice )
            LEFT OUTER JOIN bankrecon
                ON( bankrecon.idBankRecon = posting.idBankRecon )
            LEFT OUTER JOIN accountbegbal
                ON( accountbegbal.idAccBegBal = posting.idAccBegBal )
            JOIN coa
                ON( coa.idCoa = posting.idCoa )
            WHERE
                ( ( invoices.status = 2 AND invoices.cancelTag NOT IN( 1 ) )
                    OR bankrecon.archived NOT IN( 1 )
                    OR accountbegbal.idAccBegBal > 0 )
                AND (
                    ( DATE_FORMAT( invoices.date, '%Y-%m-%d' ) BETWEEN '$params[sdate]' AND '$params[edate]' )
                    OR ( DATE_FORMAT( bankrecon.reconDate, '%Y-%m-%d' ) BETWEEN '$params[sdate]' AND '$params[edate]' )
                    OR ( DATE_FORMAT( accountbegbal.date, '%Y-%m-%d' ) BETWEEN '$params[sdate]' AND '$params[edate]' )
                )
                AND (
                    invoices.idAffiliate = $this->AFFILIATEID
                    OR bankrecon.idAffiliate = $this->AFFILIATEID
                    OR accountbegbal.idAffiliate = $this->AFFILIATEID
                )
                AND posting.idCoa = $params[idCoa]
            GROUP BY coa.norm_c2, posting.idCoa
        " )->result_array();
    }

    public function getBankAccountBalanceRecon( $params, $hasExisting ){
        $this->db->select( "unAdjustedBankBalance" );
        if( $hasExisting ){
            if( $params['reconMonth'] == 1 ){
                $this->db->where( 'reconMonth', 12 );
                $this->db->where( 'reconYear', (int)$params['reconYear'] - 1 );
            }
            else{
                $this->db->where( 'reconMonth', (int)$params['reconMonth'] - 1 );
                $this->db->where( 'reconYear', (int)$params['reconYear'] );
            }
        }
        $this->db->where( 'idBankAccount', $params['idBankAccount'] );
        $this->db->order_by( 'reconMonth DESC, reconYear DESC' );
        $this->db->limit( 1 );
        $rec = $this->db->get( 'bankrecon' )->row_array();
        if( count( (array)$rec ) > 0 ) return $rec['unAdjustedBankBalance'];
        else return 0;
    }

    public function getAccountBeginningBalance( $params ){
        $this->db->select( 'IFNULL( begBal, 0 ) as begBal' );
        $this->db->where( 'idBankAccount', (int)$params['idBankAccount'] );
        $this->db->where( 'idAffiliate', (int)$this->AFFILIATEID );
        $rec = $this->db->get( 'bankaccount' )->row_array();
        if( count( (array)$rec ) > 0 ) return $rec['begBal'];
        else return 0;
    }

    public function getBankAccountTotalClearedDisbursements( $params ){
        $this->db->select( 'SUM( IFNULL( postdated.amount, 0 ) ) as amount, postdated.idBankAccount' );
        $this->db->join( 'invoices', 'invoices.idInvoice = postdated.idInvoice' );
        $this->db->where( 'postdated.idBankAccount', $params['idBankAccount'] );
        $this->db->where( 'invoices.idAffiliate', $this->AFFILIATEID );
        $this->db->where( 'MONTH( invoices.date ) <= ', (int)$params['monthPrev'] );
        $this->db->where( 'MONTH( invoices.date ) <= ', (int)$params['yearPrev'] );
        $this->db->where( 'postdated.idBankRecon > 0' );
        $this->db->where( 'postdated.status', 2 );
        $this->db->where_not_in( 'postdated.idBankRecon', (int)$params['idBankRecon'] );
        $this->db->where_not_in( 'invoices.archived', 1 );
        $this->db->where( 'invoices.status', 2 );
        $this->db->where( 'invoices.idModule', 45 ); /* disbursements */
        $this->db->group_by( 'postdated.idBankAccount' );
        $rec = $this->db->get( 'postdated' )->row_array();
        if( count( (array)$rec ) > 0 ) return $rec['amount'];
        else return 0;
    }

    public function getBankAccountTotalClearedReceipts( $params ){
        $this->db->select( 'SUM( IFNULL( postdated.amount, 0 ) ) as amount, postdated.idBankAccount' );
        $this->db->join( 'invoices', 'invoices.idInvoice = postdated.idInvoice' );
        $this->db->where( 'postdated.idBankAccount', $params['idBankAccount'] );
        $this->db->where( 'invoices.idAffiliate', $this->AFFILIATEID );
        $this->db->where( 'MONTH( invoices.date ) <= ', (int)$params['monthPrev'] );
        $this->db->where( 'MONTH( invoices.date ) <= ', (int)$params['yearPrev'] );
        $this->db->where( 'postdated.idBankRecon > 0' );
        $this->db->where( 'postdated.status', 2 );
        $this->db->where_not_in( 'postdated.idBankRecon', (int)$params['idBankRecon'] );
        $this->db->where_not_in( 'invoices.archived', 1 );
        $this->db->where( 'invoices.status', 2 );
        $this->db->where( 'invoices.idModule', 28 ); /* receipts */
        $this->db->group_by( 'postdated.idBankAccount' );
        $rec = $this->db->get( 'postdated' )->row_array();
        if( count( (array)$rec ) > 0 ) return $rec['amount'];
        else return 0;
    }

    public function getLatestMonth( $params ){
		$this->db->select( "
							CONCAT( (CASE
								WHEN reconMonth = 1 THEN 'January'
								WHEN reconMonth = 2 THEN 'February'
								WHEN reconMonth = 3 THEN 'March'
								WHEN reconMonth = 4 THEN 'April'
								WHEN reconMonth = 5 THEN 'May'
								WHEN reconMonth = 6 THEN 'June'
								WHEN reconMonth = 7 THEN 'July'
								WHEN reconMonth = 8 THEN 'August'
								WHEN reconMonth = 9 THEN 'September'
								WHEN reconMonth = 10 THEN 'October'
								WHEN reconMonth = 11 THEN 'November'
								WHEN reconMonth = 12 THEN 'December'
							END), ' - ', reconYear) as monDis
							", false );
		$this->db->from( 'bankrecon' );
		$this->db->where( 'idAffiliate', (int)$this->AFFILIATEID );
        $this->db->where( 'idBankAccount', (int)$params['idBankAccount'] );
        $this->db->where_not_in( 'archived', 1 );
		$this->db->order_by( 'reconMonth DESC, reconYear DESC' );
		$this->db->limit( 1 );
		return $this->db->get()->row_array();
    }
    
    public function deleteRelatedRecords( $params ){
        $this->db->where( 'idBankRecon', (int)$params['idBankRecon'] );
        $this->db->delete( 'adjusted' );
        
        $this->db->where( 'idBankRecon', (int)$params['idBankRecon'] );
        $this->db->delete( 'bankreconadjustment' );

        $this->db->set( 'idBankRecon', NULL );
        $this->db->set( 'status', 1 );
        $this->db->set( 'statusDate', NULL );
        $this->db->where( 'idBankRecon', $params['idBankRecon'] );
        $this->db->update( 'postdated' );

        $this->db->where( 'idBankRecon', $params['idBankRecon'] );
        $this->db->delete( 'posting' );
    }

    public function saveForm( $params ){
        $params['dateModified'] = date( 'Y-m-d H:i:s' );
        if( (int)$params['idBankRecon'] > 0 ){
            $this->db->where( 'idBankRecon', (int)$params['idBankRecon'] );
            $this->db->update( 'bankrecon', unsetParams( $params, 'bankrecon' ) );
            return $params['idBankRecon'];
        }
        else{
            unset( $params['idBankRecon'] );
            $this->db->insert( 'bankrecon', unsetParams( $params, 'bankrecon' ) );
            return $this->db->insert_id();
        }
    }

    public function saveFormHistory( $params ){
        $this->db->insert( 'bankreconhistory', unsetParams( $params, 'bankreconhistory' ) );
        return $this->db->insert_id();
    }

    public function saveBankreconadjustment( $bankreconadjustment ){
        $this->db->insert_batch( 'bankreconadjustment', unsetParamsBatch( $bankreconadjustment, 'bankreconadjustment' ) );
    }

    public function saveBankreconadjustmenthistory( $bankreconadjustment ){
        $this->db->insert_batch( 'bankreconadjustmenthistory', unsetParamsBatch( $bankreconadjustment, 'bankreconadjustmenthistory' ) );
    }

    public function saveAdjusted( $adjusted ){
        $this->db->insert_batch( 'adjusted', unsetParamsBatch( $adjusted, 'adjusted' ) );
    }

    public function saveAdjustedHistory( $adjusted ){
        $this->db->insert_batch( 'adjustedhistory', unsetParamsBatch( $adjusted, 'adjustedhistory' ) );
    }

    public function updatePostdated( $postdated ){
        $this->db->Where( 'idPostdated', $postdated['idPostdated'] );
        $this->db->update( 'postdated', unsetParams( $postdated, 'postdated' ) );
    }

    public function savePosting( $posting ){
        $this->db->insert_batch( 'posting', unsetParamsBatch( $posting, 'posting' ) );
    }

    public function savePostingHistory( $posting ){
        $this->db->insert_batch( 'postinghistory', unsetParamsBatch( $posting, 'posting' ) );
    }

    public function getAdjusted( $params ){
        $this->db->select( 'description, amount' );
        $this->db->where( 'idBankRecon', ( isset( $params['idBankRecon'] )? $params['idBankRecon'] : 0 ) );
        $this->db->where( 'adjustedTag', ( isset( $params['adjustedTag'] )? $params['adjustedTag'] : 0 ) );
        return $this->db->get( 'adjusted' )->result_array();
    }

    public function retrieveData( $params ){
        $this->db->select( 'bankrecon.*, bankaccount.bankAccount' );
        $this->db->join( 'bankaccount', 'bankaccount.idBankAccount = bankrecon.idBankRecon', 'left outer' );
        $this->db->where( 'bankrecon.idBankRecon', (int)$params['idBankRecon'] );
        return $this->db->get( 'bankrecon' )->result_array();
    }

    public function getBankreconAdjustment( $params ){
        $this->db->select( '
            date
            ,description
            ,(CASE
                WHEN amount > 0 THEN amount
                ELSE 0
            END) as addAmount
            ,(CASE
                WHEN amount < 0 THEN ( amount * -1 )
                ELSE 0
            END) as lessAmount
        ' );
        $this->db->where( 'idBankRecon', $params['idBankRecon'] );
        return $this->db->get( 'bankreconadjustment' )->result_array();
    }

    public function untagPostdated( $params ){
        $this->db->set( 'idBankRecon', NULL );
        $this->db->set( 'status', 1 );
        $this->db->where( 'idBankRecon', $params['idBankRecon'] );
        $this->db->update( 'postdated' );
    }

    public function archiveRecord( $params ){
        $this->db->set( 'archived', 1 );
        $this->db->where( 'idBankRecon', $params['idBankRecon'] );
        $this->db->update( 'bankrecon' );
    }

    public function getReferences( $params ){
        $this->db->select( "bankrecon.idBankRecon as id, CONCAT( reference.code, '-', bankrecon.referenceNum ) as name" );
        $this->db->where( 'bankrecon.idAffiliate', $this->AFFILIATEID );
        $this->db->join( 'reference', 'reference.idReference = bankrecon.idReference', 'left outer' );
        $this->db->order_by( 'bankrecon.referenceNum', 'asc' );
        $this->db->where_not_in( 'bankrecon.archived', 1 );
        if( isset( $params['query'] ) ){
            $this->db->like( "CONCAT( reference.code, '-', bankrecon.referenceNum )", $params['query'], 'both' );
        }
        return $this->db->get( 'bankrecon' )->result_array();
    }

}