<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Hazel Alegbeleye
 * Module: Receiving 
 * Date: 
 * Finished: 
 * Description: 
 * DB Tables: invoices, receiving, receivinghistory, 
 * */
class Receiving_model extends CI_Model {

    function getPO( $params ) {
        if( isset($params['idAffiliate']) ) {
            $this->db->select("
                                invoices.idInvoice, 
                                pCode, 
                                concat(reference.code, '-', invoices.referenceNum) as name,
                                paymentMethod as paymentType,
                                amount as totalAmount,
                                bal as balance,
                                balLeft,
                                supplier.discount as discountPercentage,
                                (amount * ( supplier.discount / 100 )) as discountAmount,
                                invoices.downPayment");
            $this->db->join('reference', 'reference.idReference = invoices.idReference', 'LEFT');
            $this->db->join('po', 'po.idInvoice = invoices.idInvoice', 'LEFT');
            $this->db->join('supplier', 'supplier.idSupplier = invoices.pCode', 'LEFT');
            $this->db->where( array(
                'invoices.idModule'                 => 2
                ,'invoices.status'                  => 2
                ,'invoices.archived'                => 0
                ,'invoices.pType'                   => 2
                ,'cast(invoices.date as date) <='   => $params['date']
                ,'invoices.idAffiliate'             => $params['idAffiliate']
                ,'invoices.cancelTag'               => 0
            ) );

            if( isset($params['onEdit']) && $params['onEdit'] == 0 ) $this->db->where('po.qtyLeft >', 0 );

            if( isset( $params['pCode'] ) ) $this->db->where( 'pCode', $params['pCode'] );

            $this->db->where("invoices.idAffiliate IN (SELECT idAffiliate FROM employeeaffiliate WHERE idEmployee = {$this->session->userdata('EMPLOYEEID')})", NULL, FALSE);
            if( isset( $params['idCostCenter'] ) ) $this->db->where('invoices.idCostCenter', $params['idCostCenter']);

            $this->db->group_by( 'invoices.idInvoice');
            return $this->db->get('invoices')->result_array();
        }
        
    }

    function getPOItems( $params ) {
        if( isset( $params['idInvoice']) && !empty( $params['idInvoice'])) {
            if( isset( $params['onEdit'] ) && $params['onEdit'] == 1 ) {
                return $this->getReceivingItems( $params );
            } else {
                $this->db->distinct();
                $this->db->select("po.idInvoice,
                                    concat(reference.code, '-', invoices.referenceNum) as referenceNum,
                                    invoices.idReferenceSeries,
                                    item.itemName,
                                    po.idItem,
                                    item.barcode,
                                    item.idItemClass,
                                    itemclassification.className,
                                    unit.unitCode as unitName,
                                    po.cost,
                                    po.qtyLeft as qty,
                                    po.qtyLeft,
                                    ( po.cost * po.qtyLeft ) as amount,
                                    item.sk");
                $this->db->from('item');
                $this->db->join('po', 'po.idItem = item.idItem', 'INNER');
                $this->db->join('itemaffiliate', 'itemaffiliate.idItem = item.idItem', 'INNER');
                $this->db->join('itemclassification', 'itemclassification.idItemClass = item.idItemClass', 'LEFT');
                $this->db->join('unit', 'unit.idUnit = item.idUnit', 'LEFT');
                $this->db->join('invoices', 'invoices.idInvoice = po.idInvoice', 'LEFT');
                $this->db->join('reference', 'reference.idReference = invoices.idReference', 'INNER');

                $this->db->where( array(
                                        'po.idInvoice' => $params['idInvoice']
                                        ,'item.archived' => 0 
                                ) );

                // $this->db->select('
                //                 po.idInvoice,
                //                 invoices.referenceNum,
                //                 invoices.idReferenceSeries,
                //                 item.barcode,
                //                 item.itemName,
                //                 itemclassification.className,
                //                 unit.unitName,
                //                 po.cost,
                //                 po.qtyLeft as qty,
                //                 po.qtyLeft,
                //                 po.idItem,
                //                 item.idItemClass,
                //                 ( po.cost * po.qty ) as amount');
                // $this->db->join('item', 'po.idItem = item.idItem', 'LEFT');
                // $this->db->join('itemclassification', 'item.idItemClass = itemclassification.idItemClass', 'LEFT');
                // $this->db->join('unit', 'item.idunit = unit.idUnit', 'LEFT');
                // $this->db->join('invoices', 'invoices.idInvoice = po.idInvoice', 'LEFT');

                // $this->db->from('po');
                // $this->db->where( "po.idInvoice", (int)$params['idInvoice'] );

                $params['db'] = $this->db;
                $params['order_by'] = 'barcode asc';

                return getGridList( $params );
            }
            
        } else {
            if( isset( $params['idSupplier'] ) ) {
                $this->db->select("
                                0 as idInvoice,
                                'None' as referenceNum,
                                item.barcode,
                                item.itemName,
                                itemclassification.className,
                                unit.unitCode as unitName,
                                item.itemPrice as cost,
                                0 as qty,
                                0 as qtyLeft,
                                supplieritems.idItem,
                                item.idItemClass,
                                ( item.itemPrice * 0 ) as amount,
                                item.sk");
                $this->db->join('item', 'supplieritems.idItem = item.idItem', 'LEFT');
                $this->db->join('itemclassification', 'item.idItemClass = itemclassification.idItemClass', 'LEFT');
                $this->db->join('unit', 'item.idunit = unit.idUnit', 'LEFT');

                $this->db->from('supplieritems');
                $this->db->where('supplieritems.idSupplier', $params['idSupplier']);


                $params['db'] = $this->db;
                $params['order_by'] = 'item.itemName';

                return getGridList( $params );
            }
        }
    }

    function getReceivingItems( $params ) {
        $this->db->select("
                            receiving.idInvoice,
                            item.barcode,
                            item.itemName,
                            itemclassification.className,
                            unit.unitCode as unitName,
                            receiving.cost,
                            receiving.qtyLeft as qty,
                            (CASE
                                WHEN receiving.fident IS NULL THEN 0
                                ELSE (SELECT 
                                        po.qtyLeft
                                    FROM
                                        po
                                    LEFT JOIN
                                        invoices on invoices.idInvoice = po.idInvoice
                                    WHERE
                                        po.idInvoice = receiving.fident
                                            AND po.idItem = receiving.idItem
                                            AND invoices.archived = 0
                                        )
                            END)  as qtyLeft,
                            receiving.idItem,
                            item.idItemClass,
                            ( receiving.cost * receiving.qtyLeft ) as amount,
                            ( case receiving.expiryDate when '0000-00-00' then '' else receiving.expiryDate end) as expiryDate,
                            (case when receiving.fident IS NULL then 'None' else concat(reference.code,'-',invoices.referenceNum) end) as referenceNum,
                            receiving.fident,
                            item.sk,
                            receiving.lotNumber");
        $this->db->join('item', 'receiving.idItem = item.idItem', 'LEFT');
        $this->db->join('itemclassification', 'item.idItemClass = itemclassification.idItemClass', 'LEFT');
        $this->db->join('po', 'po.idPo = receiving.fident', 'LEFT');
        $this->db->join('unit', 'item.idunit = unit.idUnit', 'LEFT');
        $this->db->join('invoices', 'invoices.idInvoice = receiving.fident', 'LEFT');
        $this->db->join('reference', 'reference.idReference = invoices.idReference', 'LEFT');
        $this->db->from('receiving');
        $this->db->where( "receiving.idInvoice", (int)$params['idInvoice'] );

        //concat(reference.code, '-', invoices.referenceNum)

        $params['db'] = $this->db;
        $params['order_by'] = 'idReceiving';

        return getGridList( $params );
    }

    function getItems( $params ){
        if( isset( $params['idAffiliate']) ) {
            $qty = ( isset($params['qty']) ? $params['qty'] : 0 );

            $this->db->select("
                                'None' as referenceNum,
                                item.idItem,
                                itemName, 
                                itemclassification.className, 
                                barcode, 
                                unit.unitCode as unitName, 
                                item.idItemClass,
                                itemPrice as cost, ' . 
                                $qty . ' as qty, 
                                ( itemPrice * ' . $qty . ' ) as amount,
                                item.sk ");
            $this->db->where( array( 
                                    'itemaffiliate.idAffiliate' => $params['idAffiliate']
                                    ,'item.archived'            => 0
                                    // ,'supplieritems.idSupplier' => $params['idSupplier']
                                    ) );
            $this->db->join('itemclassification', 'item.idItemClass = itemclassification.idItemClass', 'LEFT');
            $this->db->join('unit', 'item.idUnit = unit.idUnit', 'LEFT');
            $this->db->join('itemaffiliate', 'item.idItem = itemaffiliate.idItem', 'INNER');
            // $this->db->join('supplieritems', 'supplieritems.idItem = item.idItem', 'INNER');
            return $this->db->get('item')->result_array();
        }
        
    }

    function getPOItemDetails( $params ) {
        $params['item'] = json_decode($params['item'], true);
    
        $this->db->select('*');
        $this->db->where(array( 'idItem' => $params['item']['idItem'], 'referenceNum' => $params['referenceNum'] ));
        return $this->db->get('po')->result_array();
    }

    function getSupplier( $params ) {
        $this->db->distinct();
        $this->db->select(" supplier.idSupplier as id,
                            supplier.idSupplier,
                            supplier.name,
                            supplieraffiliate.idAffiliate,
                            supplier.vatType,
                            supplier.vatPercent,
                            supplier.paymentMethod as paymentType,
                            supplier.terms,
                            supplier.discount as discountPercentage,
                            supplier.withholdingTaxRate AS ewtRate,
                            IF( supplier.vatPercent > 0, supplier.vatPercent, SUM( affiliate.vatPercent ) ) AS vatPercent,  
                            IF( supplier.vatPercent > 0, supplier.vatType   , SUM( affiliate.vatType    ) ) AS vatType,
                            supplier.sk");
        $this->db->join('supplieraffiliate', 'supplier.idSupplier = supplieraffiliate.idSupplier', 'inner');
        $this->db->JOIN( 'affiliate' , 'affiliate.idAffiliate = supplieraffiliate.idAffiliate' ); // ADDDED BY MAKMAK
        $this->db->where('supplier.archived', 0);
        if(isset($params['idAffiliate'])) $this->db->where('supplieraffiliate.idAffiliate', $params['idAffiliate']);

        $this->db->order_by('supplier.name asc');
        $this->db->GROUP_BY('id, idSupplier, idAffiliate');  // ADDDED BY MAKMAK modified by Hazel
        return $this->db->get('supplier')->result_array();
    }

    function saveInvoice( $params ){
        if( isset($params['onEdit']) && $params['onEdit'] == 1 ) {
            $this->db->where( 'idInvoice', $params['idInvoice'] );
            $this->db->update( 'invoices', unsetParams( $params, 'invoices' ) );
            $params['idInvoice'] = $this->db->get('invoices')->result_array()[0]['idInvoice'];
            $idInvoice = $params['idInvoice'];

            $this->db->insert( 'invoiceshistory', unsetParams( $params, 'invoiceshistory' ) );
        } else {
            $this->db->insert( 'invoices', unsetParams( $params, 'invoices' ) );
            $idInvoice = $this->db->insert_id();
        }

        return $idInvoice;
    }

    function saveReceiving( $params ){
        if( isset( $params['onEdit']) && $params['onEdit'] == 1 ) {
            // if( isset($params['item']['fident'] ) ){
            //     /**Get old qty record from receiving**/
            //     $this->db->select('*');
            //     $this->db->where( array( 'idInvoice' => $params['item']['idInvoice'], 'idItem' => $params['item']['idItem'] ));
            //     $_record = $this->db->get('receiving')->row();

            //     /**OnEdit - Return qty to qtyLeft first**/
            //     $this->db->set( 'qtyLeft', '( qtyLeft + ' . $_record->qty . ')', false );
            //     $this->db->where( array(
            //         'idInvoice' => (int)$params['item']['fident']
            //         ,'idItem'     => $params['item']['idItem']
            //     ) );
            //     $this->db->update('po');
            // }

            // $this->db->delete( 'receiving', array( 'idInvoice' => $params['item']['idInvoice'], 'idItem' => $params['item']['idItem'] ) );
            $this->_returnToOriginalQty( $params );
            if( $params['cancelTag'] == 0 ){
                $this->db->delete( 'receiving', array( 'idInvoice' => $params['item']['idInvoice'], 'idItem' => $params['item']['idItem'] ) );
                $this->db->insert( 'receiving', $params['item'] );
                $this->db->insert( 'receivinghistory', $params['item'] );
            }
            
        } else {
            $this->db->insert( 'receiving', $params['item'] );
        }

        $idReceiving = $this->db->insert_id();

        if( $params['cancelTag'] == 0 ){
            if( isset($params['item']['fident'] ) ){
                //Deduct PO upon saving.
                $this->db->set( 'qtyLeft', '( qtyLeft - ' . $params['item']['qty'] . ')', false );
                $this->db->where( array(
                    'idInvoice' => (int)$params['item']['fident']
                    ,'idItem'     => $params['item']['idItem']
                ) );
                $this->db->update('po');
            }
        }

        return $idReceiving;
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

    function viewAll( $params ) {
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
                            ,eu.username as receivedBy
                            ,emp.username as approvedBy
                            ,invoices.idReferenceSeries
                            ,affiliate.sk as affiliateSK
                            ,costcenter.sk as costCenterSK
                            ,supplier.sk as supplierSK");
        $this->db->from('invoices');
        $this->db->join('reference', 'invoices.idReference = reference.idReference', 'LEFT');
        $this->db->join('affiliate', 'invoices.idAffiliate = affiliate.idAffiliate', 'LEFT');
        $this->db->join('costcenter', 'invoices.idCostCenter = costcenter.idCostCenter', 'LEFT');
        $this->db->join('supplier', 'invoices.pCode = supplier.idSupplier', 'LEFT');
        $this->db->join('eu', 'invoices.preparedBy = eu.idEu', 'LEFT');
        $this->db->join('eu as emp', 'invoices.notedby = emp.idEu', 'LEFT');

        $this->db->where( array( 'invoices.idModule' => 25, 'invoices.archived' => 0, 'invoices.pType' => 2 ) );
        if(isset( $params['idAffiliate'])) $this->db->where('affiliate.idAffiliate',$params['idAffiliate']);
        // $this->db->where("invoices.idAffiliate IN (SELECT idAffiliate FROM employeeaffiliate WHERE idEmployee = {$this->session->userdata('EMPLOYEEID')})", NULL, FALSE);

        if( isset( $params['filterValue'] ) ) {
            $this->db->where( 'invoices.idInvoice', $params['filterValue']);
        }

        if( isset( $params['query'] ) ) $this->db->like("concat(reference.code, '-', invoices.referenceNum)", $params['query'], 'after');

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

    function getData( $params ){
        $this->db->select("
                            invoices.idAffiliate, 
                            invoices.idCostCenter, 
                            invoices.idReference, 
                            invoices.referenceNum, 
                            CONVERT( invoices.date, DATE ) as tdate, 
                            TIME_FORMAT( CONVERT( invoices.date, TIME ), '%h:%i %p') as ttime, 
                            CONVERT(invoices.dueDate, DATE) as dueDate, 
                            invoices.remarks, 
                            invoices.pCode, 
                            invoices.idReferenceSeries,
                            invoices.status,
                            invoices.idInvoice,
                            invoices.fident,
                            invoices.downPayment,
                            invoices.payMode as paymentType,
                            concat(reference.code, '-', poInvoice.referenceNum) as name,
                            invoices.discount as discountAmount,
                            invoices.discountRate as discountPercentage,
                            invoices.cancelTag
                        ");
                        //count(idReleasing) as transUsage,
        $this->db->where('invoices.idInvoice', $params['idInvoice']);
        $this->db->join('invoices as poInvoice', 'poInvoice.idInvoice = invoices.idInvoice', 'LEFT');
        $this->db->join('reference', 'reference.idReference = poInvoice.idReference', 'INNER');
        // $this->db->join('releasing', "releasing.fident = {$params['idInvoice']} and invoices.archived = 0", 'LEFT' );
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
        $match = 0;

        $this->db->select("*");
        $this->db->where("fIdent", $params['idInvoice']);
        $releasingTransactions = $this->db->get('releasing')->num_rows();

        if( $releasingTransactions > 0 ){
            $match = 1;
        } else {
            $items = $this->getReceivingItems( $params )['view'];

            /* UPDATE PO QTY LEFT */
            foreach( $items as $item ){
                $this->db->set( 'qtyLeft', '( qtyLeft + ' . $item['qty'] . ')', false );
                $this->db->where( array('idPo' => $item['fident'], 'idItem' => $item['idItem'])); //'idPo', $item['fident'] 
                $this->db->update('po');
            }

            /* DELETE RECEIVING */
            $this->db->delete( 'receiving', array('idInvoice' => $params['idInvoice']) );
    
            /* SOFT DELETE ONLY */
            $this->db->set('archived', 1, false );
            $this->db->where('idInvoice', $params['idInvoice'] );
            $this->db->update('invoices');
        }
        
        return $match;
    }

    function updateQty( $item, $fident, $status, $idInvoice ){
        $qtyLeft = ( $status == 2 ) ? (int)$item['qtyLeft'] - (int)$item['qty'] : (int)$item['qtyLeft'] + (int)$item['qty'];

        $pCondtion = array( 'idPO' => $fident, 'idItem' => $item['idItem']);
        $pValues = array( 'qtyLeft' => $qtyLeft );
        $this->db->where( $pCondtion );
        $this->db->update('po', $pValues);

        if( $status == 3 && isset( $idInvoice ) && $idInvoice != '' ) {
            $this->db->where( array('idInvoice' => $idInvoice) );
            $this->db->update( 'receiving', array( 'qty' => 0, 'qtyLeft' => 0 ) );
        }
    }

    function checkReferenceNumber( $params ){
        $this->db->select("count(*) as 'match'");
        $this->db->where( array("idReference" => $params['idReference'], "referenceNum" => $params['referenceNum'], "idAffiliate" => $this->session->userdata('AFFILIATEID')));
        return $this->db->get("invoices")->result_array()[0]['match'];
    }

    function getSupplierDetails( $params ){
        //supplier.vatType,
        //supplier.vatPercent,
        $this->db->select("
                            creditLimit, 
                            IFNULL( SUM( invoices.balLeft ), 0 ) as apBalance, 
                            ( case
                                when creditLimit > 0 THEN (creditLimit - IFNULL( SUM( invoices.balLeft ), 0 ) )
                                else 0
                            end ) as variance,
                            supplier.paymentMethod as paymentType,
                            supplier.terms,
                            supplier.discount as discountPercentage,
                        ");
        $this->db->join("invoices", "invoices.pCode = supplier.idSupplier and invoices.payMode = 2 and invoices.cancelTag = 0", "left");
        $this->db->group_by("supplier.idSupplier");
        $this->db->where('idSupplier', $params['idSupplier']);

        return $this->db->get("supplier")->row();
    }

    function _returnToOriginalQty( $params ){
        if( isset($params['item']['fident'] ) ){
            /**Get old qty record from receiving**/
            $this->db->select('*');
            $this->db->where( array( 'idInvoice' => $params['item']['idInvoice'], 'idItem' => $params['item']['idItem'] ));
            $_record = $this->db->get('receiving')->row();

            /**OnEdit - Return qty to qtyLeft first**/
            $this->db->set( 'qtyLeft', '( qtyLeft + ' . $_record->qty . ')', false );
            $this->db->where( array(
                'idInvoice' => (int)$params['item']['fident']
                ,'idItem'     => $params['item']['idItem']
            ) );
            $this->db->update('po');
        }

        // $this->db->delete( 'receiving', array( 'idInvoice' => $params['item']['idInvoice'], 'idItem' => $params['item']['idItem'] ) );
    }

}