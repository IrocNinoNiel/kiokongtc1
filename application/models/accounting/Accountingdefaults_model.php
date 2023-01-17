<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Jayson Dagulo
 * Module       : Accounting Defaults
 * Date         : Dec 26, 2019
 * Finished     : Mar 11, 2020
 * Description  : This module allows authorized users to set the accounting defaults of every transaction module.
 * DB Tables    : 
 * */ 

class Accountingdefaults_model extends CI_Model {

    public function viewAll( $params ){
        $this->db->select( 'a.idDefaultEntry, a.purpose, b.moduleName, c.name as referenceName' );
        $this->db->from( 'defaultentry as a' );
        $this->db->join( 'module as b', 'b.idModule = a.idModule', 'left outer' );
        $this->db->join( 'reference as c', 'c.idReference = a.idReference', 'left outer' );
        $this->db->where_not_in( 'a.archived', 1 );
        
        $params['db'] = $this->db;
        $params['order_by'] = 'a.purpose asc';

        return getGridList($params);
    }

    public function getAffiliates( $params ){
        $this->db->select( "
            a.idAffiliate
            ,a.affiliateName
            ,(CASE WHEN IFNULL( b.idDefaultAffiliate, 0 ) > 0 THEN 1 ELSE 0 END) as chk
            ,(CASE
                WHEN a.accSchedule = 1 THEN 'Calendar'
                WHEN a.accSchedule = 2 THEN 'Fiscal'
            END) as accSchedule
            ,(CASE
                WHEN a.month = 1 THEN 'January'
                WHEN a.month = 2 THEN 'February'
                WHEN a.month = 3 THEN 'March'
                WHEN a.month = 4 THEN 'April'
                WHEN a.month = 5 THEN 'May'
                WHEN a.month = 6 THEN 'June'
                WHEN a.month = 7 THEN 'July'
                WHEN a.month = 8 THEN 'August'
                WHEN a.month = 9 THEN 'September'
                WHEN a.month = 10 THEN 'October'
                WHEN a.month = 11 THEN 'November'
                WHEN a.month = 12 THEN 'December'
            END) as month
            ,f.idDefaultAcc
            ,a.sk
        " );
        $this->db->join( 'defaultentryaffiliate as b', 'b.idAffiliate = a.idAffiliate AND b.idDefaultEntry = ' . (int)$params['idDefaultEntry'], 'left outer' );
        $this->db->join( 'employeeaffiliate as c', 'c.idAffiliate = a.idAffiliate', 'left outer' );
        $this->db->join( 'employee as d', 'd.idEmployee = c.idEmployee', 'left outer' );
        $this->db->join( 'eu as e', 'e.idEmployee = d.idEmployee', 'left outer' );
        $this->db->join( 'defaultaccounts as f', 'f.idAffiliate = a.idAffiliate', 'left outer' );
        $this->db->where( 'e.idEu', $this->USERID );
        $this->db->where_not_in( 'a.archived', 1 );
        $this->db->order_by( 'a.affiliateName', 'asc' );
        // $this->db->group_by( 'a.idAffiliate' );
        if( isset( $params['query'] ) ) $this->db->like( 'a.affiliateName', $params['query'], 'both' );
        return $this->db->get( 'affiliate as a' )->result_array();
    }

    public function getModules( $params ){
        $this->db->select( 'idModule, moduleName as module' );
        if( isset( $params['query'] ) ) $this->db->like( 'module', $params['query'], 'both' );
        $this->db->where_not_in( 'isTransaction', 1 );
        $this->db->order_by( 'module', 'asc' );
        return $this->db->get( 'module' )->result_array();
    }

    public function getReference( $params ){
        $this->db->select( 'idReference as id, name' );
        $this->db->where( 'idModule', (int)$params['idModule'] );
        $this->db->order_by( 'name', 'asc' );
        return $this->db->get( 'reference' )->result_array();
    }

    public function getDefaultEntryAccounts( $params ){
        $this->db->select( 'b.idCoa, b.acod_c15, b.aname_c30, a.debit, a.credit' );
        $this->db->join( 'coa as b', 'b.idCoa = a.idCoa', 'left outer' );
        $this->db->where_not_in( 'b.archived', 1 );
        $this->db->where( 'idDefaultEntry', (int)$params['idDefaultEntry'] );
        return $this->db->get( 'defaultentryposting as a' )->result_array();
    }

    public function getAccountListing( $params ){
        $this->db->select( 'a.idCoa, a.acod_c15, a.aname_c30' );
        $this->db->join( 'coaaffiliate as b', 'b.idCoa = a.idCoa', 'left outer' );
        $this->db->where( 'b.idAffiliate', (int)$params['idAffiliate'] );
        if( isset( $params['sBy'] ) ){
            if( isset( $params['query'] ) ) $this->db->like( ( $params['sBy'] == 1? 'a.aname_c30' : 'acod_c15' ), $params['query'], 'both' );
        }
        $this->db->where_not_in( 'a.archived', 1 );
        return $this->db->get( 'coa as a' )->result_array();
    }

    public function getAccountDefaults( $params ){
        $this->db->select( "
            a.idDefaultAcc
            ,coaDebitRec.idCoa as debitRec
            ,coaDebitRec.acod_c15 as debitRecCode
            ,coaDebitRec.aname_c30 as debitRecName

            ,coaCreditPay.idCoa as creditPay
            ,coaCreditPay.acod_c15 as creditPayCode
            ,coaCreditPay.aname_c30 as creditPayName

            ,coaAccRec.idCoa as accRec
            ,coaAccRec.acod_c15 as accRecCode
            ,coaAccRec.aname_c30 as accRecName

            ,coaAccPay.idCoa as accPay
            ,coaAccPay.acod_c15 as accPayCode
            ,coaAccPay.aname_c30 as accPayName

            ,coaDebitMemo.idCoa as debitMemo
            ,coaDebitMemo.acod_c15 as debitMemoCode
            ,coaDebitMemo.aname_c30 as debitMemoName

            ,coaCreditMemo.idCoa as creditMemo
            ,coaCreditMemo.acod_c15 as creditMemoCode
            ,coaCreditMemo.aname_c30 as creditMemoName

            ,coaInputTax.idCoa as inputTax
            ,coaInputTax.acod_c15 as inputTaxCode
            ,coaInputTax.aname_c30 as inputTaxName

            ,coaOutputTax.idCoa as outputTax
            ,coaOutputTax.acod_c15 as outputTaxCode
            ,coaOutputTax.aname_c30 as outputTaxName

            ,coaSalesAccount.idCoa as salesAccount
            ,coaSalesAccount.acod_c15 as salesAccountCode
            ,coaSalesAccount.aname_c30 as salesAccountName

            ,coaSalesDiscount.idCoa as salesDiscount
            ,coaSalesDiscount.acod_c15 as salesDiscountCode
            ,coaSalesDiscount.aname_c30 as salesDiscountName

            ,coaOtherIncome.idCoa as otherIncome
            ,coaOtherIncome.acod_c15 as otherIncomeCode
            ,coaOtherIncome.aname_c30 as otherIncomeName

            ,coaRetainedEarnings.idCoa as retainedEarnings
            ,coaRetainedEarnings.acod_c15 as retainedEarningsCode
            ,coaRetainedEarnings.aname_c30 as retainedEarningsName

            ,coaIncomeTaxProv.idCoa as incomeTaxProvision
            ,coaIncomeTaxProv.acod_c15 as incomeTaxProvisionCode
            ,coaIncomeTaxProv.aname_c30 as incomeTaxProvisionName

            ,coaCashEquivalents.idCoa as cashEquivalents
            ,coaCashEquivalents.acod_c15 as cashEquivalentsCode
            ,coaCashEquivalents.aname_c30 as cashEquivalentsName
        " );
        $this->db->where( 'a.idAffiliate', (int)$params['idAffiliate'] );
        $this->db->join( 'affiliate as b', 'b.idAffiliate = a.idAffiliate' );
        $this->db->join( 'coa as coaDebitRec', 'coaDebitRec.idCoa = a.debitRec', 'left outer' );
        $this->db->join( 'coa as coaCreditPay', 'coaCreditPay.idCoa = a.creditPay', 'left outer' );
        $this->db->join( 'coa as coaAccRec', 'coaAccRec.idCoa = a.accRec', 'left outer' );
        $this->db->join( 'coa as coaAccPay', 'coaAccPay.idCoa = a.accPay', 'left outer' );
        $this->db->join( 'coa as coaDebitMemo', 'coaDebitMemo.idCoa = a.debitMemo', 'left outer' );
        $this->db->join( 'coa as coaCreditMemo', 'coaCreditMemo.idCoa = a.creditMemo', 'left outer' );
        $this->db->join( 'coa as coaInputTax', 'coaInputTax.idCoa = a.inputTax', 'left outer' );
        $this->db->join( 'coa as coaOutputTax', 'coaOutputTax.idCoa = a.outputTax', 'left outer' );
        $this->db->join( 'coa as coaSalesAccount', 'coaSalesAccount.idCoa = a.salesAccount', 'left outer' );
        $this->db->join( 'coa as coaSalesDiscount', 'coaSalesDiscount.idCoa = a.salesDiscount', 'left outer' );
        $this->db->join( 'coa as coaOtherIncome', 'coaOtherIncome.idCoa = a.otherIncome', 'left outer' );
        $this->db->join( 'coa as coaRetainedEarnings', 'coaRetainedEarnings.idCoa = a.retainedEarnings', 'left outer' );
        $this->db->join( 'coa as coaIncomeTaxProv', 'coaIncomeTaxProv.idCoa = a.incomeTaxProvision', 'left outer' );
        $this->db->join( 'coa as coaCashEquivalents', 'coaCashEquivalents.idCoa = a.cashEquivalents', 'left outer' );
        return $this->db->get( 'defaultaccounts as a' )->result_array();
    }

    public function saveDefaultEntry( $params, $idDefaultEntry ){
        if( $idDefaultEntry > 0 ){
            $this->db->where( 'idDefaultEntry', $idDefaultEntry );
            $this->db->update( 'defaultentry', unsetParams( $params, 'defaultentry' ) );
            return $idDefaultEntry;
        }
        else{
            $this->db->insert( 'defaultentry', unsetParams( $params, 'defaultentry' ) );
            return $this->db->insert_id();
        }
    }

    public function saveDefaultEntryHistory( $params ){
        $this->db->insert( 'defaultentryhistory', unsetParams( $params, 'defaultentryhistory' ) );
        return $this->db->insert_id();
    }

    public function deleteConnectedRecords( $idDefaultEntry ){
        $this->db->where( 'idDefaultEntry', $idDefaultEntry );
        $this->db->delete( 'defaultentryaffiliate' );

        $this->db->where( 'idDefaultEntry', $idDefaultEntry );
        $this->db->delete( 'defaultentryposting' );
    }

    public function insertDefaultEntryAffiliate( $params ){
        $this->db->insert_batch( 'defaultentryaffiliate', unsetParamsBatch( $params, 'defaultentryaffiliate' ) );
    }

    public function insertDefaultEntryAffiliateHistory( $params ){
        $this->db->insert_batch( 'defaultentryaffiliatehistory', unsetParamsBatch( $params, 'defaultentryaffiliatehistory' ) );
    }

    public function insertDefaultEntryPosting( $params ){
        $this->db->insert_batch( 'defaultentryposting', unsetParamsBatch( $params, 'defaultentryposting' ) );
    }

    public function insertDefaultEntryPostingHistory( $params ){
        $this->db->insert_batch( 'defaultentrypostinghistory', unsetParamsBatch( $params, 'defaultentrypostinghistory' ) );
    }

    public function retrieveData( $params ){
        $this->db->select( 'idDefaultEntry, purpose, idModule, idReference, remarks' );
        $this->db->where( 'idDefaultEntry', (int)$params['idDefaultEntry'] );
        return $this->db->get( 'defaultentry' )->result_array();
    }

    public function getCoa( $params ){
        $this->db->select( 'a.idCoa as id, a.acod_c15 as code, a.aname_c30 as name' );
        $this->db->join( 'coaaffiliate as b', 'b.idCoa = a.idCoa', 'left outer' );
        $affiliates = json_decode( $params['affiliates'], true );
        $this->db->where_in( 'b.idAffiliate', ( count( (array)$affiliates )> 0? $affiliates : 0 ) );
        $this->db->group_by( 'a.idCoa' );
        if( $params['field'] == 'code' ){
            if( isset( $params['query'] ) ) $this->db->like( 'a.acod_c15', $params['query'], 'both' );
            $this->db->order_by( 'a.acod_c15 asc' );
        }
        else if( $params['field'] == 'name' ){
            if( isset( $params['query'] ) ) $this->db->like( 'a.aname_c30', $params['query'], 'both' );
            $this->db->order_by( 'a.aname_c30 asc' );
        }
        // $this->db->where( 'a.accountType', 2 );
        $this->db->where_not_in( 'a.archived', 1 );
        return $this->db->get( 'coa as a' )->result_array();
    }

    public function deleteDefaultEntry( $params ){
        $this->db->set( 'archived', 1 );
        $this->db->where( 'idDefaultEntry', (int)$params['idDefaultEntry'] );
        $this->db->update( 'defaultentry' );
    }

    public function saveRecords( $params ){
        $idDefaultAc    = (int)$params['idDefaultAcc'];
        if( $idDefaultAc > 0 ){
            $this->db->where( 'idDefaultAcc', $idDefaultAc );
            $this->db->update( 'defaultaccounts', unsetParams( $params, 'defaultaccounts' ) );
            return $idDefaultAc;
        }
        else{
            $this->db->insert( 'defaultaccounts', unsetParams( $params, 'defaultaccounts' ) );
            return $this->db->insert_id();
        }
    }

    public function saveRecordsHistory( $params ){
        $this->db->insert( 'defaultaccountshistory', unsetParams( $params, 'defaultaccountshistory' ) );
    }

}