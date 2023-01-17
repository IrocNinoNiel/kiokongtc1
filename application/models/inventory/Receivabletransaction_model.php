<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Makmak    
 * Module       : Receivable Transaction
 * Date         : Feb. 20, 2020
 * Finished     : 
 * Description  : 
 * DB Tables    : 
 * */
    class Receivabletransaction_model extends CI_Model
    {
        public function getReferences( $params )
        {
            $this->db->select('
                reference.idReference as id
                ,CONCAT( code, " - ", name ) as name
            ');

            if ( isset( $params['idAffiliate'] ) && $params['idAffiliate'] != 0 ) {
                $this->db->join('referenceaffiliate' , 'reference.idReference = referenceaffiliate.idReference');
                $this->db->where( 'referenceaffiliate.idAffiliate' , $params['idAffiliate'] ); 
            }   $this->db->where('archived', 0);
                $this->db->where_in('idModule', [ 18 , 58 ] );

            $this->db->group_by('reference.idReference');
            return $this->db->get('reference')->result_array();
        }

        public function getCustomers( $params )
        {
            $this->db->select('customer.idCustomer as id, customer.name, customer.sk');
            
            if ( isset( $params['idAffiliate'] ) && $params['idAffiliate'] != 0 ) {
                $this->db->join('customeraffiliate' , 'customeraffiliate.idCustomer = customer.idCustomer');
                $this->db->where( 'customeraffiliate.idAffiliate' , $params['idAffiliate'] ); 
            }

            $this->db->group_by('customer.idCustomer');
            return $this->db->get('customer')->result_array();
        }

        public function getReceivableTransactions( $params )
        {
            $this->db->select("
                DATE( invoices.date ) as date
                ,affiliateName
                ,CONCAT( reference.code, ' - ', invoices.referenceNum) as code
                ,customer.name as customerName
                ,employee.name as salesMan
                ,invoices.balLeft as amount
                ,invoices.idInvoice
                ,invoices.idModule
                ,affiliate.sk AS affSK
                ,customer.sk AS custSK
                ,employee.sk AS empSK
            ");

            $this->db->from("invoices");
            $this->db->join('reference' , 'reference.idReference = invoices.idReference');
            $this->db->join('affiliate' , 'affiliate.idAffiliate = invoices.idAffiliate');
            $this->db->join('customer' , 'customer.idCustomer = invoices.pCode');
            $this->db->join('eu' , 'eu.idEu = invoices.preparedBy');
                $this->db->join('employee' , 'employee.idEmployee = eu.idEmployee');

            if ( isset( $params['idAffiliate'] ) && $params['idAffiliate'] != 0 )   $this->db->where( 'affiliate.idAffiliate' , $params['idAffiliate'] );
            if ( isset( $params['idReference'] ) && $params['idReference'] != 0 )   $this->db->where( 'reference.idReference' , $params['idReference'] );
            if ( isset( $params['idCustomer'] ) && $params['idCustomer'] != 0 )     $this->db->where( 'customer.idCustomer' , $params['idCustomer'] ); 
            if ( isset( $params['hideZero'] )   && $params['hideZero'] != 0 )       $this->db->where( 'invoices.balLeft != 0' );

            $this->db->where( 'DATE(invoices.date) >= "'.$params['sdate'].'" ');
            $this->db->where( 'DATE(invoices.date) <= "'.$params['edate'].'" ');

            $this->db->where('invoices.pType', 1);
            $this->db->where_in('reference.idModule', [ 18 , 58 ] );
            $this->db->where_not_in('invoices.archived', 1);
            $this->db->where_not_in('invoices.cancelTag', 1);
            $this->db->group_by('invoices.idInvoice');
            $this->db->order_by("invoices.date" , "DESC");
            
            return $this->db->get()->result_array();
        }
    }