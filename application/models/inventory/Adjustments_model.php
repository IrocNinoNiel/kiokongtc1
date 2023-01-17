<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Marie Danie
 * Module       : Adjustment
 * Continued by : Jays
 * Date         : Jan. 23, 2020
 * Finished     : 
 * Description  : This module allows authorized users to record an item adjustment in inventory.
 *              : Edited for commit only
 * DB Tables    : 
 * */ 
class Adjustments_model extends CI_Model{

    // ,CONCAT( DATE_FORMAT( invoices.date, '%m/%d/%Y' ), ' ', TIME_FORMAT( invoices.time, '%h:%i %p' ) ) as date
    public function viewAll( $params ){
        $this->db->select("
            invoices.idInvoice
            ,affiliate.affiliateName
            ,DATE_FORMAT( invoices.date, '%m/%d/%Y' ) as date
            ,CONCAT( reference. code, ' - ', invoices.referenceNum ) as reference
            ,invoices.remarks
            ,employeepreparedby.name as preparedByName
            ,employeepreparedby.sk as preparedBySK
            ,employeenotedby.name as notedbyName
            ,employeenotedby.sk as notedBySK
            ,(CASE
                WHEN invoices.status = 1 THEN 'Pending'
                WHEN invoices.status = 2 THEN 'Approved'
                WHEN invoices.status = 3 THEN 'Cancelled'
            END) as statusText
        ");
        $this->db->from( 'invoices' );
        $this->db->join( 'affiliate', 'affiliate.idAffiliate = invoices.idAffiliate', 'left outer' );
        $this->db->join( 'reference', 'reference.idReference = invoices.idReference', 'left outer' );
        $this->db->join( 'eu as euRby', 'euRby.idEu = invoices.preparedBy', 'left outer' );
        $this->db->join( 'employee as employeepreparedby', 'employeepreparedby.idEmployee = euRby.idEmployee', 'left outer' );
        $this->db->join( 'eu as eunote', 'eunote.idEu = invoices.notedBy', 'left outer' );
        $this->db->join( 'employee as employeenotedby', 'employeenotedby.idEmployee = eunote.idEmployee', 'left outer' );
        $this->db->where( 'invoices.idModule', 23 );
        $this->db->where( 'invoices.idAffiliate', $this->AFFILIATEID );
        if( isset( $params['filterValue'] ) ){
            if( (int)$params['filterValue'] > 0 ) $this->db->where( 'invoices.idInvoice', (int)$params['filterValue'] );
        }

        $params['db'] = $this->db;
        $params['order_by'] = 'invoices.date DESC, invoices.idInvoice DESC';

        return getGridList($params);
    }
    
    public function getItems( $params ){
        return $this->db->query( "
            SELECT
                item.idItem
                ,item.sk
                ,item.barcode
                ,item.itemName
                ,itemclassification.className
                ,item.idItemClass
                ,unit.unitName
                ,item.idUnit
                ,IFNULL( receiving.cost, 0 ) as cost
                ,IF( IFNULL(receiving.expiryDate, '0000-00-00') = '0000-00-00', '', receiving.expiryDate ) AS expiryDate
                ,IFNULL( receivingQty.qtyLeft, 0 ) as qtyLeft
            FROM item
            LEFT OUTER JOIN itemclassification
                ON( itemclassification.idItemClass = item.idItemClass )
            LEFT OUTER JOIN unit
                ON( unit.idUnit = item.idUnit )
            LEFT OUTER JOIN itemaffiliate
                ON( itemaffiliate.idItem = item.idItem )
            LEFT OUTER JOIN (
                SELECT MAX( receiving.idReceiving ) as idReceiving, receiving.idItem
                FROM receiving
                JOIN invoices
                    ON( invoices.idInvoice = receiving.idInvoice )
                WHERE receiving.qtyLeft > 0
                    AND invoices.idAffiliate = $this->AFFILIATEID
                        AND invoices.date <= '" . date( 'Y-m-d', strtotime( $params['date'] ) ) . ' ' . date( 'H:i:s', strtotime( $params['time'] ) ) . "'
                            AND invoices.status = 2
                                AND invoices.archived NOT IN( 1 )
                GROUP BY idItem
            ) as receivingMainInfo
                ON( receivingMainInfo.idItem = item.idItem )
            LEFT OUTER JOIN receiving
                ON( receiving.idReceiving = receivingMainInfo.idReceiving )
            LEFT OUTER JOIN(
                SELECT
                    SUM( IFNULL( receiving.qtyLeft, 0 ) ) as qtyLeft
                    ,receiving.idItem
                FROM receiving
                JOIN invoices
                    ON( invoices.idInvoice = receiving.idInvoice )
                WHERE receiving.qtyLeft > 0
                    AND invoices.idAffiliate = $this->AFFILIATEID
                        AND invoices.date <= '" . date( 'Y-m-d', strtotime( $params['date'] ) ) . ' ' . date( 'H:i:s', strtotime( $params['time'] ) ) . "'
                            AND invoices.status = 2
                                AND invoices.archived NOT IN( 1 )
                GROUP BY receiving.idItem
            ) as receivingQty
                ON( receivingQty.idItem = item.idItem )
            WHERE itemaffiliate.idAffiliate = " . $this->AFFILIATEID . "
                AND item.archived NOT IN( 1 )
                " . ( isset( $params['query'] )? " AND item." . $params['field'] . " LIKE '%" . $params['query'] . "%' " : "" ) . "
            ORDER BY item.$params[field] ASC
        " )->result_array();
    }

    public function saveAdjustmentInvoice( $params ){
        $params['dateModified'] = date( 'Y-m-d H:i:s' );
        $params['preparedBy']   = $this->USERID;
        $params['status']       = 1;
        $params['idAffiliate']  = $this->AFFILIATEID;
        $this->db->insert( 'invoices', unsetParams( $params, 'invoices' ) );
        return $this->db->insert_id();
    }

    public function getReceivingRecords( $params, $params1 ){
        return $this->db->query("
            SELECT
                receiving.qtyLeft, receiving.idItem, receiving.idReceiving, receiving.cost, receiving.price, receiving.expiryDate
                ,IFNULL( receivingTotalQty.qtyLeft, 0 ) as totalQtyLeft
                ,invoices.idInvoice
                ,invoices.idModule
            FROM
                receiving
            JOIN invoices
                ON( invoices.idInvoice = receiving.idInvoice )
            LEFT OUTER JOIN(
                SELECT 
                    SUM( IFNULL( receiving.qtyLeft, 0 ) ) as qtyLeft
                    ,receiving.idItem
                FROM
                    receiving
                JOIN invoices
                    ON( invoices.idInvoice = receiving.idInvoice )
                WHERE receiving.idItem = " . (int)$params['idItem'] . "
                    AND invoices.idAffiliate = " . $this->AFFILIATEID . "
                        AND CONCAT( DATE_FORMAT( invoices.date, '%Y-%m-%d' ), ' ', invoices.time ) <= '" . date( 'Y-m-d', strtotime( $params1['date'] ) ) . ' ' . date( 'H:i:s', strtotime( $params1['ttime'] ) ) . "'
                            AND invoices.status = 2
                                AND invoices.archived NOT IN( 1 )
                GROUP BY receiving.idItem
            ) as receivingTotalQty
                ON( receivingTotalQty.idItem = receiving.idItem )
            WHERE receiving.idItem = " . (int)$params['idItem'] . "
                AND invoices.idAffiliate = " . $this->AFFILIATEID . "
                    AND CONCAT( DATE_FORMAT( invoices.date, '%Y-%m-%d' ), ' ', invoices.time ) <= '" . date( 'Y-m-d', strtotime( $params1['date'] ) ) . ' ' . date( 'H:i:s', strtotime( $params1['ttime'] ) ) . "'
                        AND invoices.status = 2
                            AND invoices.archived NOT IN( 1 )
            ORDER BY receiving.idReceiving ASC
        ")->result_array();
    }

    public function updateReceivingRecord( $qty, $receivingRecordSource ){
        $this->db->set( 'qtyLeft', $qty );
        $this->db->where( 'idReceiving', $receivingRecordSource['idReceiving'] );
        $this->db->update( 'receiving' );
    }

    public function addReleasingRecord( $params ){
        $this->db->insert( 'releasing', unsetParams( $params, 'releasing' ) );
    }

    public function saveReceiving( $params ){
        $this->db->insert( 'receiving', unsetParams( $params, 'receiving' ) );
    }

    public function saveItemAdjustment( $params ){
        $this->db->insert( 'invadjustment', unsetParams( $params, 'invadjustment' ) );
    }

    public function updateRecordStatus( $params ){
        $this->db->where( 'idInvoice', $params['idInvoice'] );
        $this->db->update( 'invoices', unsetParams( $params, 'invoices' ) );
    }

    public function getAdjustment( $params ){
        $idInvoice = 0;
        if( isset( $params['idInvoice'] ) ) $idInvoice = (int)$params['idInvoice'];
        $this->db->select('
            invadjustment.idItem
            ,invadjustment.qtyBal as qty
            ,invadjustment.qtyActual as actualQty
            ,invadjustment.cost
            ,invadjustment.short as shortQty
            ,invadjustment.over as overQty
            ,item.barcode
            ,item.itemName
            ,item.sk
            ,itemclassification.className
            ,unit.unitName
            ,IF( invadjustment.expiryDate = "0000-00-00", "", invadjustment.expiryDate ) as expiryDate
        ');
        $this->db->join( 'item', 'item.idItem = invadjustment.idItem', 'left outer' );
        $this->db->join( 'itemclassification', 'itemclassification.idItemClass = item.idItemClass', 'left outer' );
        $this->db->join( 'unit', 'unit.idUnit = item.idUnit', 'left outer' );
        $this->db->where( 'invadjustment.idInvoice', $idInvoice );
        return $this->db->get( 'invadjustment' )->result_array();
    }

    public function retrieveData( $params ){
        $this->db->select( 'idInvoice, idReference, referenceNum, idReferenceSeries, status, DATE_FORMAT( date, "%m/%d/%Y" ) as tdate, DATE_FORMAT( date, "%h:%i %p" ) as ttime, remarks, dateModified, idCostCenter' );
        $this->db->where( 'idInvoice', (int)$params['idInvoice'] );
        return $this->db->get( 'invoices' )->result_array();
    }

    public function saveTransactionJournal( $journalEntry ){
        if( !empty( $journalEntry ) ) $this->db->insert_batch( 'posting', unsetParamsBatch( $journalEntry, 'posting' ) );
    }

    public function deleteReceiving( $params ){
        $this->db->where( 'idInvoice', (int)$params['idInvoice'] );
        $this->db->delete( 'receiving' );
    }

    public function getReleasingRecord( $params ){
        $this->db->select( '*' );
        $this->db->where( 'idInvoice', (int)$params['idInvoice'] );
        return $this->db->get( 'releasing' )->result_array();
    }

    public function updateReceivingQty( $params ){
        $this->db->set( 'qtyLeft', '( qtyLeft + ' . (int)$params['qty'] . ' )', false );
        $this->db->where( 'idReceiving', $params['fIdent'] );
        $this->db->update( 'receiving' );
    }

    public function deleteReleasing( $params ){
        $this->db->where( 'idInvoice', (int)$params['idInvoice'] );
        $this->db->delete( 'releasing' );
    }

    public function getAdjustmentRef( $params ){
        $this->db->select( "invoices.idInvoice as id, CONCAT( reference.code, '-', invoices.referenceNum ) as name" );
        $this->db->where( 'invoices.idAffiliate', $this->AFFILIATEID );
        $this->db->join( 'reference', 'reference.idReference = invoices.idReference', 'left outer' );
        $this->db->where( 'invoices.idModule', 23 );
        $this->db->order_by( 'invoices.referenceNum', 'asc' );
        $this->db->where_not_in( 'invoices.archived', 1 );
        if( isset( $params['query'] ) ){
            $this->db->like( "CONCAT( reference.code, '-', invoices.referenceNum )", $params['query'], 'both' );
        }
        return $this->db->get( 'invoices' )->result_array();
    }

}