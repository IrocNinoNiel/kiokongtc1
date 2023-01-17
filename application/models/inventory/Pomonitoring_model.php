<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Hazel Alegbeleye  
 * Module: PO Monitoring
 * Date: January 16, 2020
 * Finished: 
 * Description: 
 * DB Tables: 
 * */
class Pomonitoring_model extends CI_Model {

    function getPOList( $params ) {
        $query = array();
        $statusQuery = array();

        // $query['invoices.idModule'] = 2;
        $query['invoices.archived'] = 0;
        $query['invoices.pType'] = 2;

        if( isset( $params['Affiliate'] ) && $params['Affiliate'] != 0 ) $query['invoices.idAffiliate'] = $params['Affiliate'];
        if( isset( $params['idCostCenter'] ) && $params['idCostCenter'] != 0 ) $query['invoices.idCostCenter'] = $params['idCostCenter'];
        if( isset( $params['filterKey'] ) && $params['filterKey'] != 0 && isset( $params['filterValue'] ) && $params['filterValue'] != ''  ) {
            if( $params['filterKey'] == 1 ) $query['supplier.idSupplier'] = $params['filterValue'];
            if( $params['filterKey'] == 2 ) $query['item.idItem'] = $params['filterValue'];
        }

        if( isset( $params['status'] ) && $params['status'] != 0 ) {
            switch( $params['status'] ) {
                case 1: //Complete
                    $statusQuery['status'] = 'Complete';
                    break;
                case 2: //Incomplete
                    $statusQuery['status'] = 'Incomplete';
                    break;
                case 3: //Not Served
                    $statusQuery['status'] = 'Not Served';
                    break;
                case 4: //Not Served
                    $statusQuery['status'] = 'Cancelled';
                    break;
            }
        }

        if( isset( $params['mode'] ) ) {

            if( $params['mode'] == 'ledger' ) {

                $ledgerQuery = "
                            SELECT 
                                DATE_FORMAT(date, '%Y-%m-%d %h:%u %p') as date,
                                referenceNum,
                                ifnull(expectedQty, 0) as expectedQty,
                                ifnull(receivedQty, 0) as receivedQty,
                                @bal:= @bal + (IFNULL(expectedQty, 0) - IFNULL(receivedQty, 0)) AS balance
                            FROM
                                (
                                
                                    SELECT
                                        '' as idInvoice,
                                        '' as date,
                                        'Beginning Balance' as referenceNum,
                                        (   select
                                                ( IFNULL( SUM(po.qty), 0) -  IFNULL( SUM( receiving.qtyLeft ), 0) ) as qty
                                            from
                                                invoices
                                            LEFT JOIN po ON invoices.idInvoice = po.idInvoice
                                            LEFT JOIN receiving ON po.idPo = receiving.fident AND receiving.idItem = po.idItem
                                            LEFT JOIN reference ON invoices.idReference = reference.idReference
                                            LEFT JOIN costcenter ON invoices.idCostCenter = costcenter.idCostCenter
                                            LEFT JOIN item ON po.idItem = item.idItem
                                            LEFT JOIN itemclassification ON po.idItemClass = itemclassification.idItemClass
                                            LEFT JOIN supplier ON invoices.pCode = supplier.idSupplier
                                            LEFT JOIN affiliate ON invoices.idAffiliate = affiliate.idAffiliate
                                            
                                            WHERE convert(invoices.date, date) < '$params[sdate]'
                                            AND po.idItem = $params[idItem]
                                            AND po.idItemClass = $params[idItemClass]
                                            AND invoices.pCode = $params[idSupplier]
                                            AND invoices.idInvoice = $params[poNumber]
                                            AND invoices.archived = 0
                                            AND invoices.status = 2
                                            " . ( (int)$params['idCostCenter'] > 0? " AND invoices.idCostCenter = $params[idCostCenter] " : "" ) . "
                                        ) as expectedQty,
                                        0 as receivedQty,
                                        0 as sorter
                                
                                UNION ALL
                                
                                SELECT 
                                    invoices.idInvoice,
                                    invoices.date,
                                    CONCAT(reference.code, '-', invoices.referenceNum) AS referenceNum,
                                    po.qty AS expectedQty,
                                    0 AS receivedQty,
                                    1 as sorter
                                FROM
                                    invoices
                                LEFT JOIN po ON invoices.idInvoice = po.idInvoice
                                LEFT JOIN reference ON invoices.idReference = reference.idReference
                                LEFT JOIN costcenter ON invoices.idCostCenter = costcenter.idCostCenter
                                LEFT JOIN item ON po.idItem = item.idItem
                                LEFT JOIN itemclassification ON po.idItemClass = itemclassification.idItemClass
                                LEFT JOIN supplier ON invoices.pCode = supplier.idSupplier
                                LEFT JOIN affiliate ON invoices.idAffiliate = affiliate.idAffiliate

                                WHERE convert(invoices.date, date) >= '$params[sdate]'
                                AND convert(invoices.date, date ) <= '$params[edate]'
                                AND invoices.idAffiliate = $params[Affiliate] 
                                AND po.idItem = $params[idItem] 
                                AND po.idItemClass = $params[idItemClass] 
                                AND invoices.pCode = $params[idSupplier]
                                AND invoices.archived = 0 
                                AND po.idInvoice = $params[poNumber] 
                                " . ( (int)$params['idCostCenter'] > 0? " AND invoices.idCostCenter = $params[idCostCenter] " : "" ) . "
                                UNION ALL 
                                
                                SELECT 
                                    invoices.idInvoice,
                                    invoices.date,
                                    CONCAT(reference.code, '-', invoices.referenceNum) AS referenceNum,
                                    0 AS expectedQty,
                                    receiving.qtyLeft AS receivedQty,
                                    2 as sorter
                                FROM
                                    invoices
                                LEFT JOIN receiving ON receiving.idInvoice = invoices.idInvoice
                                LEFT JOIN reference ON invoices.idReference = reference.idReference
                                LEFT JOIN po ON po.idInvoice = receiving.fident
                                LEFT JOIN costcenter ON invoices.idCostCenter = costcenter.idCostCenter
                                LEFT JOIN item ON receiving.idItem = item.idItem
                                LEFT JOIN itemclassification ON po.idItemClass = itemclassification.idItemClass
                                LEFT JOIN supplier ON invoices.pCode = supplier.idSupplier
                                
                                WHERE convert(invoices.date, date) >= '$params[sdate]'
                                AND convert(invoices.date, date) <= '$params[edate]'
                                AND invoices.idAffiliate = $params[Affiliate]
                                AND receiving.idItem = $params[idItem]
                                AND po.idItemClass = $params[idItemClass]
                                AND invoices.pCode = $params[idSupplier]
                                AND receiving.fident = $params[poNumber]
                                AND invoices.archived = 0
                                AND invoices.status = 2
                                " . ( (int)$params['idCostCenter'] > 0? " AND invoices.idCostCenter = $params[idCostCenter] " : "" ) . "
                                ) AS main
                                    LEFT OUTER JOIN
                                (SELECT @bal:=0) AS runningBalance ON (1 = 1)
                            GROUP BY idInvoice, date, referenceNum, expectedQty, receivedQty, sorter
                            ORDER BY sorter ASC , main.date ASC;";

                return $this->db->query($ledgerQuery)->result_array();

            } elseif( $params['mode'] == 'monitoring' ) {
                $this->db->select("
                                    invoices.idInvoice,
                                    po.idPO,
                                    affiliate.affiliateName,
                                    costcenter.costCenterName,
                                    CAST(invoices.date AS DATE) AS date,
                                    reference.code,
                                    invoices.referenceNum AS referenceNum,
                                    supplier.name,
                                    itemclassification.className,
                                    item.itemName,
                                    unit.unitCode as unit,
                                    po.qty AS expectedQty,
                                    (CASE invoices.status
                                        WHEN 2 THEN IFNULL(SUM(receiving.qtyLeft), 0)
                                        ELSE 0
                                    END) AS actualQty,
                                    (CASE invoices.status
                                        WHEN 2 THEN (po.qty - IFNULL(SUM(receiving.qtyLeft), 0))
                                        ELSE 0
                                    END) AS balance,
                                    (CASE invoices.status
                                        WHEN
                                            2
                                        THEN
                                            (CASE invoices.cancelTag
                                                when 0 THEN (CASE
                                                                WHEN
                                                                    SUM(receiving.qtyLeft) IS NULL
                                                                        OR SUM(receiving.qtyLeft) <= 0
                                                                THEN
                                                                    'Not Served'
                                                                WHEN SUM(receiving.qtyLeft) < po.qty THEN 'Incomplete'
                                                                WHEN SUM(receiving.qtyLeft) >= po.qty THEN 'Complete'
                                                            END)
                                                ELSE 'Cancelled' 
                                            END)
                                        ELSE 'Not Served'
                                    END) AS status,
                                    ,invoices.idAffiliate
                                    ,invoices.pCode as idSupplier
                                    ,invoices.idCostCenter
                                    ,po.idItem
                                    ,item.idUnit
                                    ,po.idItemClass
                                    ,concat(reference.code, '-', invoices.referenceNum) as poNumber
                                    ,affiliate.sk as affiliateSK
                                    ,costcenter.sk as costCenterSK
                                    ,item.sk as itemSK
                                    ,supplier.sk as supplierSK");

                    $this->db->join('po', 'po.idInvoice = invoices.idInvoice', 'INNER');
                    $this->db->join('item', 'item.idItem = po.idItem', 'INNER');
                    $this->db->join('affiliate', 'affiliate.idAffiliate = invoices.idAffiliate', 'LEFT');
                    $this->db->join('costcenter', 'costcenter.idCostCenter = invoices.idCostCenter', 'LEFT');
                    $this->db->join('reference', 'reference.idReference = invoices.idReference', 'LEFT');
                    $this->db->join('supplier', 'supplier.idSupplier = invoices.pCode', 'LEFT');
                    $this->db->join('itemclassification', 'itemclassification.idItemClass = po.idItemClass', 'LEFT');
                    $this->db->join('unit', 'unit.idUnit = item.idUnit', 'LEFT');
                    $this->db->join('receiving', 'receiving.fident = po.idInvoice and receiving.idItem = po.idItem', 'LEFT');

                    $this->db->where( $query );
                    if( isset( $params['sdate']) ) $this->db->where('convert(invoices.date, date) <=', $params['sdate'] );

                    $this->db->where("invoices.idAffiliate IN (SELECT idAffiliate FROM employeeaffiliate WHERE idEmployee = {$this->session->userdata('EMPLOYEEID')})", NULL, FALSE);
                    
                    $this->db->having( $statusQuery );
                    $this->db->group_by('po.idItem , invoices.idInvoice, po.idPo');
                    $this->db->order_by('date desc');
                    return $this->db->get( 'invoices' )->result_array();
            }
        } 
    }

    function getItems( $params ){
        $this->db->distinct();
        $this->db->select('item.idItem as id, item.itemName as name, item.sk');
        $this->db->where('item.archived', 0);
        if( isset($params['idAffiliate']) && $params['idAffiliate'] != 0 ) $this->db->where('idAffiliate', $params['idAffiliate']);
        $this->db->join('item', 'item.idItem = itemaffiliate.idItem', 'LEFT');

        return $this->db->get('itemaffiliate')->result_array();
    }

    function getPO(){
        $this->db->select("invoices.idInvoice as id, concat( reference.code, '-', invoices.referenceNum ) as name");
        $this->db->join('reference', 'reference.idReference = invoices.idReference', 'LEFT');
        $this->db->where( array( 'invoices.idModule' => 2, 'invoices.archived' => 0) );

        return $this->db->get('invoices')->result_array();
    }

    function getItemClassifications(){
        $this->db->select('idItemClass as id, className as name');
        return $this->db->get('itemclassification')->result_array();
    }
}