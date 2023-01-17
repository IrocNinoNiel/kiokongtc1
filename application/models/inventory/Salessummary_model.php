<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Salessummary_model extends CI_Model { 

    public function getCustomer($data){
         $this->db->select('
            customer.idCustomer AS id,
            customer.name AS name,
            customer.sk
        ')
        ->from('customeraffiliate')
        ->join('customer', 'customer.idCustomer = customeraffiliate.idCustomer', 'LEFT');
        
        if ( $data['idAffiliate'] != 0 ) {
            $this->db->where('customeraffiliate.idAffiliate', $data['idAffiliate']);
        }
        
        return $this->db->get()->result_array();
    }

    public function getSalesReference($data){
        $this->db->select("
            reference.idReference AS id,
            reference.name AS name
        ")
        ->from('reference')
        ->join('referenceaffiliate', 'referenceaffiliate.idReference = reference.idReference', 'LEFT')
        ->where('reference.idModule', SALES)
        ->where('reference.archived', NEGATIVE);

        if ( $data['affiliate'] != 0 ) {
            $this->db->where('referenceaffiliate.idAffiliate');
        }

        return $this->db->get()->result_array();
    }

    public function getSales($data){
        $exclusive =  EXCLUSIVE;
        $cancelled = CANCELLED;
        $approved = APPROVED;
        if( $data['customer']       != ALL_ZERO ) $this->db->where( 'invoices.pCode'        , $data['customer'] );
        if( $data['idAffiliate']    != ALL_ZERO ) $this->db->where( 'invoices.idAffiliate'  , $data['idAffiliate'] );
        if( $data['payment']        != ALL      ) $this->db->where( 'invoices.payMode'      , $data['payment'] == 2? 1 : 2);
        if( $data['vat']            != ALL      ) $this->db->where( 'invoices.vatType'      , $data['vat'] == 2? INCLUSIVE : EXCLUSIVE );
        if( $data['reference']      != ALL_ZERO ) $this->db->where( 'invoices.idReference'  , $data['reference'] );
        $this->db->select("
            invoices.idInvoice,
            invoices.idModule,
            affiliateName,
            DATE_FORMAT(invoices.date, '%Y-%m-%d %h:%i %p') AS date,
            CONCAT(reference.code, ' - ', invoices.referenceNum) AS reference,
            customer.name AS customer,
            SUM(releasing.amount) AS sales,
            (SUM(releasing.amount) * affiliate.vatPercent)  AS vat,
            IF(affiliate.vatType = {$exclusive}, ((SUM(releasing.amount) * affiliate.vatPercent) + SUM(releasing.amount)) , SUM(releasing.amount)) AS withvat,
            invoices.discount,
            IF(affiliate.vatType = {$exclusive}, ((SUM(releasing.amount) * affiliate.vatPercent) + SUM(releasing.amount)) , SUM(releasing.amount)) - invoices.discount AS amount,
            CASE
                WHEN invoices.status = {$approved}  THEN 'Approved'
                WHEN invoices.status = {$cancelled} THEN 'Cancelled'
                ELSE 'Pending'
            END AS status,
            employee.name AS salesman,
            IF(affiliate.vatType = {$exclusive}, 'Exclusive','Inclusive') AS vattype,
            affiliate.sk AS affSK,
            employee.sk AS empSK, 
            customer.sk AS custSK
        ")
        ->from('invoices')
        ->join('affiliate','affiliate.idAffiliate = invoices.idAffiliate','LEFT')
        ->join('reference','reference.idReference = invoices.idReference','LEFT')
        ->join('customer','customer.idCustomer = invoices.pCode','LEFT')
        ->join('
        (
            SELECT 
            idInvoice,
            SUM(qty*price) AS amount
            FROM releasing
            GROUP BY idInvoice, idItem
        ) AS releasing','releasing.idInvoice = invoices.idInvoice','LEFT')
        ->join('eu','eu.idEu = invoices.preparedBy','LEFT')
        ->join('employee','employee.idEmployee = eu.idEmployee','LEFT')
        ->where('invoices.idModule', SALES)
        ->where('invoices.archived', NEGATIVE)
        ->where('invoices.status', 2)
        ->where('invoices.cancelTag', 0)
        ->where("invoices.date BETWEEN '{$data['datefrom']} {$data['timeFrom']}' AND '{$data['dateto']} {$data['timeto']}'")
        ->group_by('invoices.idInvoice')
        ->order_by('invoices.date' ,'DESC');
        return $this->db->get()->result_array();
    }

    public function getSalesReturn($data){
        if($data['customer']    != ALL_ZERO ) $this->db->where('invsale.pCode', $data['customer']);
        if($data['vat']         != ALL      ) $this->db->where('invsale.vatType', $data['vat'] == 2? INCLUSIVE : EXCLUSIVE);
        if($data['payment']     != ALL      ) $this->db->where('invsale.payMode',$data['vat'] == 2? INCLUSIVE : EXCLUSIVE);
        if($data['reference']   != ALL_ZERO ) $this->db->where('invsale.idReference', $data['reference']);
        if($data['idAffiliate'] != ALL_ZERO ) $this->db->where('invoices.idAffiliate',$data['idAffiliate']);
        if($data['idAffiliate'] != ALL_ZERO ) $this->db->where('invsale.idAffiliate',$data['idAffiliate']);

        $this->db->select("
            invoices.idModule,
            affiliateName,
            invoices.idInvoice AS id,
            DATE_FORMAT(invoices.date, '%Y-%m-%d %h:%i %p') AS date,
            CONCAT(refsale.code, ' - ', invsale.referenceNum) AS salereference,
            CONCAT(reference.code, ' - ', invoices.referenceNum) AS salereturnreference,
            customer.name AS customer,
            invoices.amount,
            employee.name AS salesman,
            affiliate.sk AS affSK,
            employee.sk AS empSK,
            customer.sk AS custSK
        ")
        ->from('invoices')
        ->join('affiliate','affiliate.idAffiliate = invoices.idAffiliate','LEFT')
        ->join('invoices AS invsale','invsale.idInvoice = invoices.fident','LEFT')
        ->join('reference AS refsale','refsale.idReference = invsale.idReference','LEFT')
        ->join('reference','reference.idReference = invoices.idReference','LEFT')
        ->join('customer','customer.idCustomer = invoices.pCode','LEFT')
        ->join('eu','eu.idEu = invoices.preparedBy','LEFT')
        ->join('employee','employee.idEmployee = eu.idEmployee','LEFT')
        ->where('invoices.idModule', SALES_RETURN)
        ->where('invoices.archived', NEGATIVE)
        ->where('invsale.archived', NEGATIVE)
        ->where('invoices.status', 2)
        ->where('invoices.cancelTag', 0)
        ->where("invoices.idAffiliate IN (SELECT idAffiliate FROM employeeaffiliate WHERE idEmployee = {$this->session->userdata('EMPLOYEEID')})", NULL, FALSE)
        ->where("invsale.idAffiliate IN (SELECT idAffiliate FROM employeeaffiliate WHERE idEmployee = {$this->session->userdata('EMPLOYEEID')})", NULL, FALSE)
        ->where("invoices.date BETWEEN '{$data['datefrom']} {$data['timeFrom']}' AND '{$data['dateto']} {$data['timeto']}'")
        ->where("invsale.date BETWEEN '{$data['datefrom']} {$data['timeFrom']}' AND '{$data['dateto']} {$data['timeto']}'")
        ->order_by('invoices.date' ,'DESC');
        return $this->db->get()->result_array();
    }
}