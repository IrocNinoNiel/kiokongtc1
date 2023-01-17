<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Agingofreceivables_model extends CI_Model {
    public function getCustomers( $params )
    {
        $this->db->select('customer.idCustomer as id, customer.name, customer.sk');
        
        if ( isset( $params['idAffiliate'] ) && $params['idAffiliate'] != 0 ) {
            $this->db->join('customeraffiliate' , 'customeraffiliate.idCustomer = customer.idCustomer');
            $this->db->where( 'customeraffiliate.idAffiliate' , $params['idAffiliate'] ); 
        }

        $this->db->where( 'customer.archived' , 0 ); 
        $this->db->group_by('customer.idCustomer');
        return $this->db->get('customer')->result_array();
    }

    public function getAgingofReceivables( $params )
    {
        $this->db->select('
            affiliate.affiliateName 
            ,customer.name as customerName
            ,affiliate.sk AS affSK
            ,customer.sk AS custSK
            ,(
                SELECT 
                    IFNULL( ( sum( invoices.amount ) - IFNULL( SUM( disbursements.paid ) , 0 ) ) , 0 ) AS balance
                FROM invoices
                LEFT JOIN disbursements ON disbursements.idInvoice = invoices.idInvoice
                WHERE invoices.idModule = 18 AND invoices.archived = 0
                AND invoices.idAffiliate = affiliate.idAffiliate
                AND invoices.pCode = customer.idCustomer
                AND DATE( invoices.date ) >= ( "' . $params['dateto'] . '" - INTERVAL 29 DAY )
                AND DATE( invoices.date ) <= ( "' . $params['dateto'] . '" )
            ) AS current_bal
            ,(
                SELECT 
                    IFNULL( ( sum( invoices.amount ) - IFNULL( SUM( disbursements.paid ) , 0 ) ) , 0 ) AS balance
                FROM invoices
                LEFT JOIN disbursements ON disbursements.idInvoice = invoices.idInvoice
                WHERE invoices.idModule = 18 AND invoices.archived = 0
                AND invoices.idAffiliate = affiliate.idAffiliate
                AND invoices.pCode = customer.idCustomer
                AND DATE( invoices.date ) >= ( "' . $params['dateto'] . '" - INTERVAL 59 DAY )
                AND DATE( invoices.date ) <= ( "' . $params['dateto'] . '" - INTERVAL 30 DAY )
            ) AS days
            ,(
                SELECT 
                    IFNULL( ( sum( invoices.amount ) - IFNULL( SUM( disbursements.paid ) , 0 ) ) , 0 ) AS balance
                FROM invoices
                LEFT JOIN disbursements ON disbursements.idInvoice = invoices.idInvoice
                WHERE invoices.idModule = 18 AND invoices.archived = 0
                AND invoices.idAffiliate = affiliate.idAffiliate
                AND invoices.pCode = customer.idCustomer
                AND DATE( invoices.date ) >= ( "' . $params['dateto'] . '" - INTERVAL 89 DAY )
                AND DATE( invoices.date ) <= ( "' . $params['dateto'] . '" - INTERVAL 60 DAY )
            ) AS dayss
            ,(
                SELECT 
                    IFNULL( ( sum( invoices.amount ) - IFNULL( SUM( disbursements.paid ) , 0 ) ) , 0 ) AS balance
                FROM invoices
                LEFT JOIN disbursements ON disbursements.idInvoice = invoices.idInvoice
                WHERE invoices.idModule = 18 AND invoices.archived = 0
                AND invoices.idAffiliate = affiliate.idAffiliate
                AND invoices.pCode = customer.idCustomer
                AND DATE( invoices.date ) <= ( "' . $params['dateto'] . '" - INTERVAL 90 DAY )
            ) AS above
            ,(
                SELECT 
                    IFNULL( ( sum( invoices.amount ) - IFNULL( SUM( disbursements.paid ) , 0 ) ) , 0 ) AS balance
                FROM invoices
                LEFT JOIN disbursements ON disbursements.idInvoice = invoices.idInvoice
                WHERE invoices.idModule = 18 AND invoices.archived = 0
                AND invoices.idAffiliate = affiliate.idAffiliate
                AND invoices.pCode = customer.idCustomer
                AND DATE( invoices.date ) <= ( "' . $params['dateto'] . '" )
            ) AS total
        ');
        $this->db->from( 'customer' );
        $this->db->join( 'customeraffiliate' , 'customeraffiliate.idCustomer = customer.idCustomer');
        $this->db->join( 'affiliate' , 'affiliate.idAffiliate = customeraffiliate.idAffiliate');

        if ( isset( $params['hideZero'] ) && $params['hideZero'] != 0 ) {
            $this->db->where('(
                SELECT 
                    IFNULL( ( sum( invoices.amount ) - IFNULL( SUM( disbursements.paid ) , 0 ) ) , 0 ) AS balance
                FROM invoices
                LEFT JOIN disbursements ON disbursements.idInvoice = invoices.idInvoice
                WHERE invoices.idModule = 18 AND invoices.archived = 0
                AND invoices.idAffiliate = affiliate.idAffiliate
                AND invoices.pCode = customer.idCustomer
                AND DATE( invoices.date ) <= ( "' . $params['dateto'] . '" )
            ) != 0 ');
        }

        if ( isset( $params['idAffiliate'] ) && $params['idAffiliate'] != 0 ) {
            $this->db->where( 'affiliate.idAffiliate' , $params['idAffiliate'] ); 
        }

        if ( isset( $params['idCustomer'] ) && $params['idCustomer'] != 0 ) {
            $this->db->where( 'customer.idCustomer' , $params['idCustomer'] ); 
        }

        $this->db->group_by( 'customer.idCustomer , affiliate.idAffiliate' );
        return $this->db->get()->result_array();
    }
}
