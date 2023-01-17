<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Jays
 * Date         : Feb. 18, 2020
 * Module       : Adjustment(Accounting)
 * Finished     : Mar. 03, 2020
 * Description  : This module allows authorized user to set (add, edit, and delete) an adjustment transactions.
 * DB Tables    : 
 * */
class Adjustmentsacc_model extends CI_Model {

    public function getPRReferences( $params ){
        $this->db->select( "
            invoices.idInvoice as id
            ,CONCAT( reference.code, ' - ', invoices.referenceNum ) as name
            ,IFNULL( invoices.amount, 0 ) as amt
        " );
        $this->db->join( 'reference', 'reference.idReference = invoices.idReference', 'left outer' );
        $this->db->where( "invoices.idReference IN( SELECT idReference FROM referenceaffiliate WHERE idAffiliate = " . $this->AFFILIATEID . " )" );
        if( isset( $params['fident'] ) ){
            if( (int)$params['fident'] > 0 ) $this->db->where( "( invoices.idInvoice NOT IN( SELECT fident FROM invoices WHERE idModule = $params[idModule] ) OR invoices.idInvoice = $params[fident] )" );
            else $this->db->where( "invoices.idInvoice NOT IN( SELECT fident FROM invoices WHERE idModule = $params[idModule] )" );
        }else $this->db->where( "invoices.idInvoice NOT IN( SELECT fident FROM invoices WHERE idModule = $params[idModule] )" );

        if ( isset( $params['pType'] ) && $params['pType'] != 0 ) {
            $this->db->where( 'invoices.pType', $params['pType'] );
            $this->db->where( 'invoices.pCode', $params['pCode'] );
        }

        $this->db->where( 'invoices.idAffiliate', $this->AFFILIATEID );
        $this->db->where_in( 'invoices.idModule', [ 18, 25, 21, 29 ] ); // Sales and Receiving
        // $this->db->where( 'invoices.idModule', 29 ); // Purchase return
        $this->db->order_by( 'reference.code asc, invoices.referenceNum asc' );
        return $this->db->get( 'invoices' )->result_array();
    }

    public function viewAll( $params ){
        $this->db->select( "
            CONCAT( reference.code, ' - ', invoices.referenceNum ) as reference
            ,invoices.date as tdate
            ,affiliate.affiliateName
            ,costcenter.costCenterName
            ,costcenter.sk as costCenterSK
            ,invoices.description
            ,preparedby.name as preparedByName
            ,preparedby.sk as preparedBySK
            ,notedby.name as notedByName
            ,notedby.sk as notedBySK
            ,(CASE
                WHEN invoices.status = 1 THEN 'Pending'
                WHEN invoices.status = 2 THEN 'Approved'
                WHEN invoices.status = 3 THEN 'Cancelled'
                ELSE ''
            END) as statusText
            ,invoices.idAffiliate
            ,invoices.idInvoice
            ,invoices.idReference
            ,invoices.referenceNum
        " );
        $this->db->from( 'invoices' );
        $this->db->join( 'reference', 'reference.idReference = invoices.idReference', 'left outer' );
        $this->db->join( 'affiliate', 'affiliate.idAffiliate = invoices.idAffiliate', 'left outer' );
        $this->db->join( 'costcenter', 'costcenter.idCostCenter = invoices.idCostCenter', 'left outer' );
        $this->db->join( 'eu as eupreparedby', 'eupreparedby.idEu = invoices.preparedBy', 'left outer' );
        $this->db->join( 'employee as preparedby', 'preparedby.idEmployee = eupreparedby.idEmployee', 'left outer' );
        $this->db->join( 'eu as eunotedby', 'eunotedby.idEu = invoices.notedby', 'left outer' );
        $this->db->join( 'employee as notedby', 'notedby.idEmployee = eunotedby.idEmployee', 'left outer' );
        $this->db->where( 'invoices.idModule', 48 );
        $this->db->where_not_in( 'invoices.archived', 1 );
        $this->db->where( 'invoices.idAffiliate', $this->AFFILIATEID );
        if( isset( $params['filterValue'] ) ){
            if( (int)$params['filterValue'] > 0 ) $this->db->where( 'invoices.idInvoice', (int)$params['filterValue'] );
        }

        $params['db'] = $this->db;
        $params['order_by'] = 'invoices.date DESC, invoices.idInvoice DESC';

        return getGridList($params);
    }

    public function getPCodes( $params ){
        $this->db->select( ( (int)$params['pType'] == 1? 'idCustomer' : 'idSupplier'  ) . ' as id, name, sk' );
        $this->db->where_not_in( 'archived', 1 );
        $this->db->order_by( 'name', 'asc' );
        return $this->db->get( ( (int)$params['pType'] == 1? 'customer' : 'supplier' ) )->result_array();
    }

    public function getRecordStatus( $idInvoice ){
        $this->db->select( 'status' );
        $this->db->where( 'idInvoice', $idInvoice );
        $this->db->where_not_in( 'archived', 1 );
        $status = $this->db->get( 'invoices' )->row_array();
        if( count( (array)$status ) > 0 ) return $status['status'];
        else return 1;
    }

    public function saveAccountingAdjustment( $params ){
        $params['dateModified'] = date( 'Y-m-d H:i:s' );

        if( (int)$params['idInvoice'] > 0 ){
            $this->db->where( 'idInvoice', $params['idInvoice'] );
            $this->db->update( 'invoices', unsetParams( $params, 'invoices' ) );
            return $params['idInvoice'];
        }
        else{
            $params['preparedBy']   = $this->USERID;
            $params['status']       = 1;
            $this->db->insert( 'invoices', unsetParams( $params, 'invoices' ) );
            return $this->db->insert_id();
        }
    }

    public function saveAccountingAdjustmentHistory( $params ){
        $this->db->insert( 'invoiceshistory', unsetParams( $params, 'invoiceshistory' ) );
        return $this->db->insert_id();
    }

    public function deleteAccountingAdjustmentJE( $idInvoice ){
        $this->db->where( 'idInvoice', $idInvoice );
        $this->db->delete( 'posting' );
    }

    public function savePosting( $params ){
        $this->db->insert_batch( 'posting', unsetParamsBatch( $params, 'posting' ) );
    }

    public function savePostingHistory( $params ){
        $this->db->insert_batch( 'postinghistory', unsetParamsBatch( $params, 'postinghistory' ) );
    }

    public function retrieveData( $params ){
        $this->db->select( '
            invoices.idInvoice
            ,invoices.idReference
            ,invoices.referenceNum
            ,invoices.idReferenceSeries
            ,invoices.idCostCenter
            ,DATE_FORMAT( invoices.date, "%Y-%m-%d" ) as tdate
            ,DATE_FORMAT( invoices.date, "%h:%i %p" ) as ttime
            ,invoices.description
            ,invoices.pType
            ,invoices.pCode
            ,(CASE
                WHEN invoices.pType = 1 THEN customer.name
                WHEN invoices.pType = 2 THEN supplier.name
                ELSE ""
            END) as name
            ,(CASE
                WHEN invoices.pType = 1 THEN customer.sk
                WHEN invoices.pType = 2 THEN supplier.sk
                ELSE ""
            END) as sk
            ,invoices.amount
            ,invoices.remarks
            ,invoices.fident
            ,invoicesFident.amount as amountPR
            ,invoices.status
            ,invoices.dateModified
        ' );
        $this->db->join( 'invoices as invoicesFident', 'invoicesFident.idInvoice = invoices.fident', 'left outer' );
        $this->db->join( 'customer', 'customer.idCustomer = invoices.pCode AND invoices.pType = 1', 'left outer' );
        $this->db->join( 'supplier', 'supplier.idSupplier = invoices.pCode AND invoices.pType = 2', 'left outer' );
        $this->db->where( 'invoices.idInvoice', (int)$params['idInvoice'] );
        $this->db->where_not_in( 'invoices.archived', 1 );
        return $this->db->get( 'invoices' )->result_array();
    }

    public function hasClosingEntry( $params ){
        $this->db->select( 'idInvoice' );
        $this->db->where( 'idModule', 35 );
        $this->db->where( 'month', date( 'n', strtotime( $params['tdate'] ) ) );
        $this->db->where( 'year', date( 'Y', strtotime( $params['tdate'] ) ) );
        $cnt = $this->db->count_all_results( 'invoices' );
        return $cnt > 0;
    }

    public function markRecordAsArchived( $params ){
        $this->db->set( 'archived', 1 );
        $this->db->where( 'idInvoice', (int)$params['idInvoice'] );
        $this->db->update( 'invoices' );
    }

    public function getAdjustmentRef( $params ){
        $this->db->select( "invoices.idInvoice as id, CONCAT( reference.code, '-', invoices.referenceNum ) as name" );
        $this->db->where( 'invoices.idAffiliate', $this->AFFILIATEID );
        $this->db->join( 'reference', 'reference.idReference = invoices.idReference', 'left outer' );
        $this->db->where( 'invoices.idModule', 48 );
        $this->db->order_by( 'invoices.referenceNum', 'asc' );
        $this->db->where_not_in( 'invoices.archived', 1 );
        if( isset( $params['query'] ) ){
            $this->db->like( "CONCAT( reference.code, '-', invoices.referenceNum )", $params['query'], 'both' );
        }
        return $this->db->get( 'invoices' )->result_array();
    }

}