<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Agingofpayables_model extends CI_Model
{
    public function getSupplier( $params )
    {
        $this->db->select('
            supplier.idSupplier as id
            ,supplier.name as name
            ,supplier.sk
        ');
        
        if ( isset( $params['idAffiliate'] ) && $params['idAffiliate'] != 0 ) 
        {
            $this->db->join('supplieraffiliate' , 'supplieraffiliate.idSupplier = supplier.idSupplier');
            $this->db->where( 'supplieraffiliate.idAffiliate' , $params['idAffiliate'] ); 
        }   $this->db->where( 'supplier.archived' , 0 );

        $this->db->group_by( 'supplier.idSupplier' );
        return $this->db->get( 'supplier' )->result_array();
    
    }
    // public function getAgingofPayables( $params )
    // {
    //     $this->db->select("
    //         affiliateName
    //         , supplier.name AS supplierName
    //         , SUM( IFNULL( totalPayable.amount , 0 ) ) as total
    //     ");

    //     $this->db->join( "supplieraffiliate"    , "supplieraffiliate.idSupplier = supplier.idSupplier" );
    //     $this->db->join( "affiliate"            , "affiliate.idAffiliate = supplieraffiliate.idAffiliate" );
    //     $this->db->join( "invoices totalPayable", "
    //         totalPayable.pType = 2 
    //         AND totalPayable.pCode = supplier.idSupplier 
    //         AND totalPayable.idModule = 25	
    //         AND totalPayable.idAffiliate = affiliate.idAffiliate
    //         AND totalPayable.archived NOT IN( 1 )
    //         AND DATE( totalPayable.date ) <= '" . $params['dateto'] . "'
    //     " , "LEFT OUTER" );

    //     if ( isset( $params['hideZero'] ) && $params['hideZero'] != 0 ) {
    //         $this->db->where( "( SUM( IFNULL( totalPayable.amount , 0 ) ) ) != 0 ");
    //     }

    //     $this->db->group_by( "supplier.idSupplier , affiliate.idAffiliate" );
    //     return $this->db->get( 'supplier' )->result_array();
    // }

    public function getAgingofPayables( $params )
    {
        $this->db->select('
            affiliate.affiliateName
            ,supplier.name AS supplierName 
            ,affiliate.sk AS affSK
            ,supplier.sk AS suppSK
            ,(
                SELECT
                    IFNULL( ( sum( invoices.amount ) - IFNULL( SUM( disbursements.paid ) , 0 ) ) , 0 ) AS balance
                FROM invoices
                LEFT JOIN disbursements ON disbursements.idInvoice = invoices.idInvoice
                WHERE invoices.idModule = 25 AND invoices.archived = 0
                AND invoices.idAffiliate = affiliate.idAffiliate
                AND invoices.pCode = supplier.idSupplier
                AND DATE( invoices.date ) >= ( "' . $params['dateto'] . '" - INTERVAL 29 DAY )
                AND DATE( invoices.date ) <= ( "' . $params['dateto'] . '" ) 
            ) AS current_bal
            ,( 
                SELECT
                    IFNULL( ( sum( invoices.amount ) - IFNULL( SUM( disbursements.paid ) , 0 ) ) , 0 ) AS balance
                FROM invoices
                LEFT JOIN disbursements ON disbursements.idInvoice = invoices.idInvoice
                WHERE invoices.idModule = 25 AND invoices.archived = 0
                AND invoices.idAffiliate = affiliate.idAffiliate
                AND invoices.pCode = supplier.idSupplier
                AND DATE( invoices.date ) >= ( "' . $params['dateto'] . '" - INTERVAL 59 DAY )
                AND DATE( invoices.date ) <= ( "' . $params['dateto'] . '" - INTERVAL 30 DAY )
            ) AS days
            ,(
                SELECT
                    IFNULL( ( sum( invoices.amount ) - IFNULL( SUM( disbursements.paid ) , 0 ) ) , 0 ) AS balance
                FROM invoices
                LEFT JOIN disbursements ON disbursements.idInvoice = invoices.idInvoice
                WHERE invoices.idModule = 25 AND invoices.archived = 0
                AND invoices.idAffiliate = affiliate.idAffiliate
                AND invoices.pCode = supplier.idSupplier
                AND DATE( invoices.date ) >= ( "' . $params['dateto'] . '" - INTERVAL 89 DAY )
                AND DATE( invoices.date ) <= ( "' . $params['dateto'] . '" - INTERVAL 60 DAY )
            ) AS dayss
            ,(
                SELECT
                    IFNULL( ( sum( invoices.amount ) - IFNULL( SUM( disbursements.paid ) , 0 ) ) , 0 ) AS balance
                FROM invoices
                LEFT JOIN disbursements ON disbursements.idInvoice = invoices.idInvoice
                WHERE invoices.idModule = 25 AND invoices.archived = 0
                AND invoices.idAffiliate = affiliate.idAffiliate
                AND invoices.pCode = supplier.idSupplier
                AND DATE( invoices.date ) <= ( "' . $params['dateto'] . '" - INTERVAL 90 DAY )
            ) AS above
            ,( 
                SELECT
                    IFNULL( ( sum( invoices.amount ) - IFNULL( SUM( disbursements.paid ) , 0 ) ) , 0 ) AS balance
                FROM invoices
                LEFT JOIN disbursements ON disbursements.idInvoice = invoices.idInvoice
                WHERE invoices.idModule = 25 AND invoices.archived = 0
                AND invoices.idAffiliate = affiliate.idAffiliate
                AND invoices.pCode = supplier.idSupplier
                AND DATE( invoices.date ) <= "' . $params['dateto'] . '"
            ) AS total
        ');
        $this->db->from( 'supplier' );
        $this->db->join( 'supplieraffiliate' , 'supplieraffiliate.idSupplier = supplier.idSupplier');
        $this->db->join( 'affiliate' , 'affiliate.idAffiliate = supplieraffiliate.idAffiliate');

        if ( isset( $params['hideZero'] ) && $params['hideZero'] != 0 ) {
            $this->db->where('(   
                SELECT
                    IFNULL( ( sum( invoices.amount ) - IFNULL( SUM( disbursements.paid ) , 0 ) ) , 0 ) AS balance
                FROM invoices
                LEFT JOIN disbursements ON disbursements.idInvoice = invoices.idInvoice
                WHERE invoices.idModule = 25 AND invoices.archived = 0
                AND invoices.idAffiliate = affiliate.idAffiliate
                AND invoices.pCode = supplier.idSupplier
                AND DATE( invoices.date ) <= "' . $params['dateto'] . '"
            ) != 0 ');
        }

        if ( isset( $params['idAffiliate'] ) && $params['idAffiliate'] != 0 ) {
            $this->db->where( 'affiliate.idAffiliate' , $params['idAffiliate'] ); 
        }
        
        if ( isset( $params['idSupplier'] ) && $params['idSupplier'] != 0 ) {
            $this->db->where( 'supplier.idSupplier' , $params['idSupplier'] ); 
        }
            
        $this->db->group_by( 'supplier.idSupplier , affiliate.idAffiliate' );
        return $this->db->get()->result_array();
    }
}
