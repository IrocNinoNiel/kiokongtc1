<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Salesreturnsummary_model extends CI_Model { 


    public function getItem($data){
        $this->db->select('
            item.idItem AS id,
            item.itemName AS name
            ,item.sk
        ')
            ->from('itemaffiliate')
            ->join('item', 'itemaffiliate.idItem = item.idItem', 'LEFT')
            ->join('itemclassification', 'item.idItemClass = itemclassification.idItemClass', 'LEFT');
            if ( $data['affiliate'] != 0 ) {
                $this->db->where('itemaffiliate.idAffiliate', $data['affiliate']);
            }
            $this->db->where_not_in( 'item.archived' , 1 );
            $this->db->group_by( 'item.idItem' );
        return $this->db->get()->result_array();
    }

    public function getReferences($data){
        $this->db->select("
            reference.idReference AS id,
            reference.name AS name 
        ")
            ->from('reference')
            ->join('referenceaffiliate', 'reference.idReference = referenceaffiliate.idReference', 'LEFT')
            ->where('reference.idModule', SALES_RETURN);
            if ( $data['affiliate'] != 0 ) {
                $this->db->where('referenceaffiliate.idAffiliate', $data['affiliate']);
            }
        return $this->db->get()->result_array();
    }
    public function retriveAll($data){
        if($data['reference'] != ALL_ZERO) $this->db->where('invoices.idReference', $data['reference']);
        if($data['item'] != ALL_ZERO) $this->db->where('receiving.idItem',$data['item']);
        if($data['idAffiliate'] != ALL_ZERO) $this->db->where('invoices.idAffiliate',$data['idAffiliate']);
        $this->db->select("
            DATE_FORMAT(invoices.date, '%Y-%m-%d') AS date,
            CONCAT( reference.code , ' - ' , invoices.referenceNum ) AS reference,
            customer.name AS customer,
            item.barcode AS code,
            item.itemName AS item,
            itemclassification.className AS class,
            receiving.cost AS cost,
            receiving.qty AS qty,
            invoices.amount AS amount,
            
            invoices.idInvoice as idInvoice,
            invoices.idModule as idModule,
            affiliate.affiliateName AS affiliate,
            costcenter.costCenterName AS costcenter,
            customer.sk AS custSK,
            item.sk AS itemSK,
            affiliate.sk AS affSK,
        ")
        ->from('invoices')
        ->join('reference','reference.idReference = invoices.idReference','LEFT')
        ->join('receiving','receiving.idInvoice = invoices.idInvoice','LEFT')
        ->join('item','item.idItem = receiving.idItem','LEFT')
        ->join('itemclassification','itemclassification.idItemClass = item.idItemClass','LEFT')
        ->join('affiliate','affiliate.idAffiliate = invoices.idAffiliate','LEFT')
        ->join('costcenter','costcenter.idCostCenter = invoices.idCostCenter','LEFT')
        ->join('customer','customer.idCustomer = invoices.pCode','LEFT')
        ->where('invoices.idModule', SALES_RETURN)
        ->where('invoices.archived', NEGATIVE)
        ->where('invoices.status', 2)
        ->where('invoices.cancelTag', 0)
        ->where("invoices.idAffiliate IN (SELECT idAffiliate FROM employeeaffiliate WHERE idEmployee = {$this->session->userdata('EMPLOYEEID')})", NULL, FALSE)
        ->where("invoices.date BETWEEN '{$data['datefrom']} {$data['timeFrom']}' AND '{$data['dateto']} {$data['timeto']}'");
        return $this->db->get()->result_array();
    }
}