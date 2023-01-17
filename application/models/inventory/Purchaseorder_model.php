<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: 
 * Module: 
 * Date:
 * Finished: 
 * Description: 
 * DB Tables: 
 * */
class Purchaseorder_model extends CI_Model {

    function getSupplier( $params ) {
        $this->db->distinct();
        $this->db->select("supplier.idSupplier as id,
                            supplier.name,
                            supplieraffiliate.idAffiliate,
                            supplier.tin,
                            supplier.address
                            ,supplier.sk
                            ,supplier.creditLimit");
        $this->db->join('supplieraffiliate', 'supplier.idSupplier = supplieraffiliate.idSupplier', 'inner');
        $this->db->where('supplier.archived', 0);
        if(isset($params['idAffiliate'])) $this->db->where('supplieraffiliate.idAffiliate', $params['idAffiliate']);

        $this->db->order_by('supplier.name asc');
        return $this->db->get('supplier')->result_array();
    }

    function getSupplierDetails( $params ){
        $this->db->select("
                            address, 
                            tin, 
                            creditLimit, 
                            IFNULL( SUM( invoices.balLeft ), 0 ) as apBalance, 
                            ( case
                                when creditLimit > 0 THEN (creditLimit - IFNULL( SUM( invoices.balLeft ), 0 ) )
                                else 0
                            end ) as variance
                            ,supplier.sk
                            ,supplier.terms
                        ");
        $this->db->join("invoices", "invoices.pCode = supplier.idSupplier and invoices.payMode = 2 and invoices.cancelTag = 0", "left");
        $this->db->group_by("supplier.idSupplier");
        $this->db->where('idSupplier', $params['idSupplier']);

        return $this->db->get("supplier")->row();
    }

    function saveInvoice( $params ){
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

    function savePO( $params ){
        if( count( $params['items'] ) > 0 ) {
            if( isset( $params['onEdit']) && $params['onEdit'] == 1 ) {
                $this->db->delete( 'po', array( 'idInvoice' => $params['idInvoice'] ) );
                $this->db->insert_batch( 'po', $params['items'] );
            } else {
                $this->db->insert_batch( 'po',  $params['items'] );
            }

            return $this->db->insert_id();
        }
    }

    function savePosting( $params ) {
        if( isset($params['onEdit']) && $params['onEdit'] == 1 ) {
            $this->db->delete( 'posting', array( 'idInvoice' => $params['idInvoice'] ) );

            $this->db->insert_batch( 'posting', unsetParams( $params['items'], 'posting') );
            $this->db->insert_batch( 'postinghistory', unsetParams( $params['items'], 'postinghistory' ) );
        } else {
            $this->db->insert_batch( 'posting', $params['items'] );
        }
    }

    function viewAll( $params ){
        $this->db->select(" idInvoice as id
                            ,concat(reference.code, '-', invoices.referenceNum)as name
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
                            ,prepEmployee.name as orderedBy
                            ,prepEmployee.sk as orderedBySK
                            ,orderEmployee.name as notedBy
                            ,orderEmployee.sk as notedBySK
                            ,invoices.idReferenceSeries
                            ,affiliate.sk as affiliateSK
                            ,costcenter.sk as costCenterSK
                            ,supplier.sk as supplierSK");
        $this->db->from('invoices');
        $this->db->join('reference', 'invoices.idReference = reference.idReference', 'LEFT');
        $this->db->join('affiliate', 'invoices.idAffiliate = affiliate.idAffiliate', 'LEFT');
        $this->db->join('costcenter', 'invoices.idCostCenter = costcenter.idCostCenter', 'LEFT');
        $this->db->join('supplier', 'invoices.pCode = supplier.idSupplier', 'LEFT');
        $this->db->join('eu as ePrepare', 'invoices.preparedBy = ePrepare.idEu', 'LEFT');
        $this->db->join('eu as eOrder', 'invoices.notedBy = eOrder.idEu', 'LEFT');
        $this->db->join('employee as prepEmployee', 'prepEmployee.idEmployee = ePrepare.idEmployee', 'LEFT');
        $this->db->join('employee as orderEmployee', 'orderEmployee.idEmployee = eOrder.idEmployee', 'LEFT');

        $this->db->where( array( 'invoices.idModule' => 2, 'invoices.archived' => 0, 'invoices.pType' => 2 ) );
        $this->db->where('affiliate.idAffiliate', $this->session->userdata('AFFILIATEID'));
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
                            idAffiliate, 
                            idCostCenter, 
                            idReference, 
                            referenceNum, 
                            CONVERT( date, DATE ) as tdate, 
                            TIME_FORMAT( CONVERT( date, TIME ), '%h:%i %p') as ttime, 
                            CONVERT(dueDate, DATE) as dueDate, 
                            remarks, 
                            pCode, 
                            idReferenceSeries,
                            status,
                            invoices.idInvoice,
                            count(receiving.idReceiving) as transactionIsUsed,
                            cancelTag
                        ");
        $this->db->where('invoices.idInvoice', $params['idInvoice']);
        $this->db->join('receiving', "receiving.fident = {$params['idInvoice']} and invoices.archived = 0", 'LEFT' );
        return $this->db->get('invoices')->result_array();
    }

    function deleteRecord( $params ){
        $match = 0;

        $this->db->select("*");
        $this->db->where( array(
                                "fident" => $params['idInvoice']
                                ,"archived" => 0) );
        $receivingTransactions = $this->db->get('invoices')->num_rows();

        if( $receivingTransactions > 0 ){
            $match = 1;
        } else {
            /* SOFT DELETE ONLY */
            $this->db->set('archived', 1, false );
            $this->db->where('idInvoice', $params['idInvoice'] );
            $this->db->update('invoices');
        }
        
        return $match;
    }

    function checkReferenceNumber( $params ){
        $this->db->select("count(*) as 'match'");
        $this->db->where( array("idReference" => $params['idReference'], "referenceNum" => $params['referenceNum'], "idAffiliate" => $this->session->userdata('AFFILIATEID')));
        return $this->db->get("invoices")->result_array()[0]['match'];
    }
}