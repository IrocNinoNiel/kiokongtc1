<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Hazel Alegbeleye  
 * Module: Purchase Return
 * Date: January 15, 2020
 * Finished: 
 * Description: 
 * DB Tables: 
 * */
class Purchasereturn_model extends CI_Model {
    

    function getReceivingInvoice( $params ) {
        if( isset( $params['idSupplier'] ) && $params['idSupplier'] != '' ){

            $this->db->select("invoices.idInvoice as id, concat(reference.code, '-', invoices.referenceNum) as name");
            $this->db->join('reference', 'reference.idReference = invoices.idReference', 'LEFT');
            $this->db->join('receiving', 'receiving.idInvoice = invoices.idInvoice', 'LEFT');
            $this->db->where(
                array(
                    'invoices.idModule'     => 25
                    ,'invoices.status'      => 2
                    ,'invoices.archived'    => 0
                    // ,'receiving.qtyLeft >'  => 0
                    ,'invoices.pCode'       => $params['idSupplier']
                    ,'invoices.idAffiliate' => $this->session->userdata('AFFILIATEID')
                )
            );

            if( isset( $params['idCostCenter'] ) ) $this->db->where( 'invoices.idCostCenter', $params['idCostCenter'] );
            if( isset( $params['date'] ) ) $this->db->where( 'convert(invoices.date, date) <=', $params['date'] );
            if( isset( $params['onEdit'] ) && $params['onEdit'] == 0 ) $this->db->where('receiving.qtyLeft >', 0 );
            
            return $this->db->get('invoices')->result_array();
        }
    }

    function getItems( $params ) {
        if( !isset( $params['idInvoice'] )) return false;

        if( isset( $params['onEdit'] ) && $params['onEdit'] == 1 ){
            $query['view'] = $this->db->query("SELECT 
                            releasing.idReleasing,
                            releasing.idInvoice,
                            item.barcode,
                            item.itemName,
                            itemclassification.className,
                            unit.unitCode as unitName,
                            releasing.cost,
                            releasing.qtyLeft as qty,
                            receiving.qtyLeft + releasing.qty AS qtyBalance,
                            releasing.idItem,
                            item.idItemClass,
                            (releasing.cost * releasing.qty) AS amount,
                            (case receiving.expiryDate when '0000-00-00' then '' else receiving.expiryDate end) as expiryDate,
                            invoices.idReference,
                            receivingInvoice.referenceNum,   -- receiving.refNum as referenceNum,
                            releasing.fIdent,
                            item.sk
                        FROM
                            releasing
                            LEFT JOIN
                            item ON releasing.idItem = item.idItem
                                LEFT JOIN
                            itemclassification ON item.idItemClass = itemclassification.idItemClass
                                LEFT JOIN
                            unit ON item.idunit = unit.idUnit
                                LEFT JOIN
                            receiving on receiving.idReceiving = releasing.fIdent
                                LEFT JOIN
                            invoices on invoices.idInvoice = releasing.idInvoice
                                LEFT JOIN
                            invoices as receivingInvoice on receivingInvoice.idInvoice = receiving.idInvoice
                        WHERE
                            releasing.idInvoice = $params[idInvoice]
                            ORDER BY idReleasing")->result_array();
            return $query;
            
        } else {
            $this->db->select("
                                receiving.idReceiving,
                                receiving.idInvoice,
                                item.barcode,
                                item.itemName,
                                itemclassification.className,
                                unit.unitCode as unitName,
                                receiving.cost,
                                IFNULL(receiving.qtyLeft,0) as qty,
                                IFNULL(receiving.qtyLeft,0) as qtyBalance,
                                receiving.idItem,
                                item.idItemClass,
                                ( receiving.cost * receiving.qty ) as amount,
                                receiving.expiryDate,
                                invoices.referenceNum,
                                invoices.idReference,
                                item.sk");
                                #,(case when receiving.fident = 0 then 'None' else receiving.fident end) as referenceNum");
            $this->db->join('item', 'receiving.idItem = item.idItem', 'LEFT');
            $this->db->join('itemclassification', 'item.idItemClass = itemclassification.idItemClass', 'LEFT');
            $this->db->join('po', 'po.idInvoice = receiving.idInvoice', 'LEFT');
            $this->db->join('unit', 'item.idunit = unit.idUnit', 'LEFT');
            $this->db->join('invoices', 'invoices.idInvoice = receiving.idInvoice', 'INNER');

            $this->db->from('receiving');
            $this->db->where( "receiving.idInvoice", (int)$params['idInvoice'] );

            $params['db'] = $this->db;
            $params['order_by'] = 'idReceiving';

            return getGridList( $params );
        }
        
    }

    function saveInvoice( $params ){
        $idInvoice = (int)$params['idInvoice'];
        unset( $params['idInvoice'] );
        if( $idInvoice > 0 ){
            $this->db->where( 'idInvoice', $idInvoice );
            $this->db->update( 'invoices', unsetParams( $params, 'invoices' ) );
        }
        else{
            $this->db->insert( 'invoices', unsetParams( $params, 'invoices' ) );
            $idInvoice = $this->db->insert_id();
        }
        return $idInvoice;
    }


    function deleteReleasing( $idInvoice ){
        $this->db->delete( 'releasing', array( 'idInvoice', $idInvoice ) );
    }


    // function saveInvoice( $params ){

    //     if( isset( $params['onEdit'] ) && $params['onEdit'] == 1 ) {
    //         if( isset( $params['idInvoice']) ) {
    //             $idInvoice = $params['idInvoice'];
    //             unset($params['idInvoice']);

    //             $this->db->where('idInvoice', $idInvoice);
    //             $this->db->update('invoices', unsetParams($params, 'invoices'));

    //             $params['idInvoice'] = $idInvoice;
    //             $this->db->insert( 'invoiceshistory', unsetParams( $params, 'invoiceshistory' ) );
    //         }
    //     } else {
    //             $this->db->insert( 'invoices', unsetParams( $params, 'invoices' ) );
    //             $idInvoice = $this->db->insert_id();
    //     }

    //     return $idInvoice;
    // }

    function saveReleasing( $params ){

        if( isset( $params['onEdit']) && $params['onEdit'] == 1 ) {

            if( isset( $params['item']['fident'] )) {
                $this->db->set( 'qtyLeft', '( qtyLeft + ' . $params['item']['qty'] . ')', false );
                $this->db->where('idReceiving', $params['item']['fident'] );
                $this->db->update('receiving');
            }

            $this->db->delete( 'releasing', array( 'idInvoice' => $params['item']['idInvoice'], 'idItem' => $params['item']['idItem'] ) );
            $this->db->insert( 'releasing', $params['item'] );

        } else {
            $this->db->insert( 'releasing', $params['item'] );
        }

        return $this->db->insert_id();
    }

    function savePosting( $params ) {
        if( isset($params['onEdit']) && $params['onEdit'] == 1 ) {
            $this->db->delete( 'posting', array( 'idInvoice' => $params['idInvoice'] ) );

            $this->db->insert_batch( 'posting', $params['items']);
            $this->db->insert_batch( 'postinghistory', $params['items'] );
        } else {
            $this->db->insert_batch( 'posting', $params['items'] );
        }
    }

    function viewAll( $params ){
        $params['affiliates'] = $this->getAuthorizedAffiliate( $params['idEmployee'] );

        $this->db->select(" idInvoice as id
                            ,concat(reference.code, '-', invoices.referenceNum) as name
                            ,convert(invoices.date, date) as date
                            ,affiliate.affiliateName
                            ,costcenter.costCenterName
                            ,supplier.name as supplierName
                            ,invoices.amount
                            ,( case invoices.status 
                                when 1 THEN 'Pending'
                                when 2 THEN 'Approved'
                                when 3 THEN 'Cancelled'
                            end ) as status
                            ,eu.username as preparedBy
                            ,emp.username as approvedBy
                            ,invoices.idReferenceSeries,
                            affiliate.sk as affiliateSK,
                            costcenter.sk as costCenterSK,
                            supplier.sk as supplierSK");
        $this->db->from('invoices');
        $this->db->join('reference', 'invoices.idReference = reference.idReference', 'LEFT');
        $this->db->join('affiliate', 'invoices.idAffiliate = affiliate.idAffiliate', 'LEFT');
        $this->db->join('costcenter', 'invoices.idCostCenter = costcenter.idCostCenter', 'LEFT');
        $this->db->join('supplier', 'invoices.pCode = supplier.idSupplier', 'LEFT');
        $this->db->join('eu', 'invoices.preparedBy = eu.idEu', 'LEFT');
        $this->db->join('eu as emp', 'invoices.notedBy = emp.idEmployee', 'LEFT');
        $this->db->where('invoices.idModule', 29);

        $this->db->where('invoices.archived', 0);
        $this->db->where_in( 'invoices.idAffiliate', explode(",", $params['affiliates'] ) );
        
        if( isset( $params['filterBy']) && isset( $params['filterValue'] ) ) {
            $this->db->where( $params['filterBy'], $params['filterValue']);
        }

        if( isset( $params['filterValue'] ) ) {
            $this->db->where( 'invoices.idInvoice', $params['filterValue']);
        }

        $params['db'] = $this->db;
        $params['order_by'] = 'date desc, idInvoice desc';

        return getGridList($params);
    }

    function getAuthorizedAffiliate( $params ){
        $affiliates = [];

        if( isset($params) ){
            $this->db->select('GROUP_CONCAT(employeeaffiliate.idAffiliate) as affiliates');
            $this->db->where( array( 'employeeaffiliate.idEmployee' => $params));
            $affiliates = $this->db->get('employeeaffiliate')->result_array();
        }

        return $affiliates[0]['affiliates'];
    }

    function getRecord( $params ){
        $this->db->select("
                            invoices.idAffiliate, 
                            invoices.idCostCenter, 
                            invoices.idReference, 
                            invoices. referenceNum, 
                            CONVERT( invoices.date, DATE ) as tdate, 
                            TIME_FORMAT(invoices.date, '%h:%i %p') as ttime,
                            invoices.remarks, 
                            invoices.pCode, 
                            invoices.idReferenceSeries,
                            invoices.status,
                            invoices.idInvoice,
                            invoices.fident,
                            invoices.downPayment,
                            invoices.cancelTag,
                            count(idReleasing) as transUsage");
        $this->db->where('invoices.idInvoice', $params['idInvoice']);
        $this->db->join('releasing', "releasing.fident = {$params['idInvoice']} and invoices.archived = 0", 'LEFT' );
        return $this->db->get('invoices')->result_array();
    }

    function checkUsage( $_idInvoice ){
        $this->db->select("releasing.idReleasing");
        $this->db->join("invoices", "invoices.idInvoice = releasing.idInvoice and invoices.archived = 0 and invoices.cancelTag = 0", "left");
        $this->db->join("receiving", "receiving.idReceiving = releasing.fIdent", "left");
        $this->db->where("receiving.idInvoice", $_idInvoice );

        return $this->db->get("releasing")->num_rows();
    }

    function deleteRecord( $params ){
        $this->db->select('status');
        $this->db->where('idInvoice', $params['idInvoice']);
        $status = $this->db->get('invoices')->result_array()[0]['status'];
        $match = 0;

        if( (int)$status == 1 ) {
            $this->db->select('*');
            $this->db->where( array( 'idInvoice' => $params['idInvoice'] ) );
            $items = $this->db->get('releasing')->result_array();

            foreach( $items as $item ){
                $this->db->set( 'qtyLeft', '( qtyLeft + ' . $item['qty'] . ')', false );
                $this->db->where('idReceiving', $item['fIdent'] );
                $this->db->update('receiving');
            }

            /* SOFT DELETE ONLY */
            $this->db->set('archived', 1, false );
            $this->db->where('idInvoice', $params['idInvoice'] );
            $this->db->update('invoices');

            // $this->db->delete( 'invoices', array('idInvoice' => $params['idInvoice']) );
        } else {
            $match = 1;
        }

        /* match: 1 =  DELETE_USED 2 = DELETE_SUCCESS */
        return $match;
    }

    //adding qty to receiving
    function addReceivingQty( $idInvoice ){


        $this->db->select('*');
        $this->db->where( 'idInvoice', $idInvoice );
        $items = $this->db->get('releasing')->result_array();

        if( count($items) > 0 ) {
            $this->db->set( 'qtyLeft', '( qtyLeft + ' . $items[0]['qty'] . ')', false );
            $this->db->where('idInvoice', $idInvoice );
            $this->db->update('receiving');
        }

        // if( $status == 3 && isset( $idInvoice ) && $idInvoice != '' ) {
        //     $this->db->where( array('idInvoice' => $idInvoice) );
        //     $this->db->update( 'releasing', array( 'qty' => 0, 'qtyLeft' => 0 ) );
        // }
    }

    function minusReceivingQty( $item ){
        $this->db->set( 'qtyLeft', '( qtyLeft - ' . $item['qty'] . ')', false );
        $this->db->where(array( 'idReceiving' => $item['fIdent'], 'idItem' => $item['idItem']) );
        $this->db->update('receiving');
    }

    function checkReferenceNumber( $params ){
        $this->db->select("count(*) as 'match'");
        $this->db->where( array("idReference" => $params['idReference'], "referenceNum" => $params['referenceNum'], "idAffiliate" => $this->session->userdata('AFFILIATEID')));
        return $this->db->get("invoices")->result_array()[0]['match'];
    }
}