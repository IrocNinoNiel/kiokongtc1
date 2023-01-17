<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Beginningbalance_model extends CI_Model
{
    public function getPCodes( $params ){
        $this->db->select( ( (int)$params['pType'] == 1? 'customer.idCustomer' : 'supplier.idSupplier'  ) . ' as id, name, sk' );
        $this->db->join( 
            ( (int)$params['pType'] == 1? 'customeraffiliate' : 'supplieraffiliate' ) . ' as affiliate'
            , ( (int)$params['pType'] == 1? 'affiliate.idCustomer = customer.idCustomer' : 'affiliate.idSupplier = supplier.idSupplier' )
        );
        $this->db->where_not_in( 'archived', 1 );
        $this->db->where( 'idAffiliate', $this->session->userdata('AFFILIATEID') );
        $this->db->order_by( 'name', 'asc' );
        return $this->db->get( ( (int)$params['pType'] == 1? 'customer' : 'supplier' ) )->result_array();
    }

    public function save_Invoice_BeginningBalance( $params )
    {
        $cancelledBy = 0; $hasJournal = 0;
        if ( $params['jeLength'] > 1 ) $hasJournal = 1;
        if ( $params['cancelTag'] != 0 ) $cancelledBy = $this->session->userdata('USERID');

        $data = array (
            'idAffiliate'        => $this->session->userdata('AFFILIATEID')
            ,'idReference'       => $params['idReference']
            ,'referenceNum'      => $params['referenceNum']
            ,'idModule'          => $params['idmodule']
            ,'idCostCenter'      => $params['idCostCenter']
            ,'date'              => $params['tdate'].' '.$params['ttime']
            ,'pType'             => $params['pType']
            ,'pCode'             => $params['pCode']
            ,'amount'            => $params['amount']
            ,'bal'               => $params['amount']
            ,'balLeft'           => $params['amount']
            ,'cancelTag'         => $params['cancelTag']
            ,'remarks'           => $params['remarks']
            ,'dateModified'      => date('Y-m-d h:i:a')
            ,'hasJournal'        => $hasJournal
            ,'cancelledBy'       => $cancelledBy
            ,'preparedBy'        => $this->session->userdata('USERID')
            ,'status'            => 2
            ,'archived'          => 0
            ,'idReferenceSeries' => $params['idReferenceSeries']
        );

        $this->db->insert( 'invoices' , $data );
        return $this->db->insert_id();
    } 
    
    public function save_InvoiceHistory_BeginningBalance( $params , $idInvoice )
    {
        $cancelledBy = 0; $hasJournal = 0;
        if ( $params['jeLength'] > 1 ) $hasJournal = 1;
        if ( $params['cancelTag'] != 0 ) $cancelledBy = $this->session->userdata('USERID');

        $data = array(
            'idInvoice'          => $idInvoice
            ,'idAffiliate'       => $this->session->userdata('AFFILIATEID')
            ,'idReference'       => $params['idReference']
            ,'referenceNum'      => $params['referenceNum']
            ,'idModule'          => $params['idmodule']
            ,'idCostCenter'      => $params['idCostCenter']
            ,'date'              => $params['tdate'].' '.$params['ttime']
            ,'pType'             => $params['pType']
            ,'pCode'             => $params['pCode']
            ,'amount'            => $params['amount']
            ,'bal'               => $params['amount']
            ,'balLeft'           => $params['amount']
            ,'cancelTag'         => $params['cancelTag']
            ,'remarks'           => $params['remarks']
            ,'dateModified'      => date('Y-m-d h:i:a')
            ,'hasJournal'        => $hasJournal
            ,'cancelledBy'       => $cancelledBy
            ,'preparedBy'        => $this->session->userdata('USERID')
            ,'status'            => 2
            ,'idReferenceSeries' => $params['idReferenceSeries']
        );

        $this->db->insert( 'invoiceshistory' , $data );
        return $this->db->insert_id();
    }

    public function save_JournalHistory_BeginningBalance( $params , $idInvoice , $idInvHistory )
    {
        $data = array();
        $journal_entry = json_decode( $params['jeRecords'] );
        
        foreach ( $journal_entry as $je ) {
            $data[] = array(
                'idPosting'         => $this->save_Journal_BeginningBalance( $idInvoice , $je )
                ,'idInvoiceHistory' => $idInvHistory
                ,'idInvoice'        => $idInvoice
                ,'explanation'      => $je->explanation
                ,'debit'            => $je->debit
                ,'credit'           => $je->credit
                ,'idCoa'            => $je->idCoa
                ,'idCostCenter'     => $je->idCostCenter
            );
        }

        return $this->db->insert_batch( 'postinghistory' , $data );
    }

    public function save_Journal_BeginningBalance( $idInvoice , $je )
    {
        $data = array(
            'idInvoice'     => $idInvoice
            ,'explanation'  => $je->explanation
            ,'debit'        => $je->debit
            ,'credit'       => $je->credit
            ,'idCoa'        => $je->idCoa
            ,'idCostCenter' => $je->idCostCenter
        );

        $this->db->insert( 'posting' , $data );
        return $this->db->insert_id();
    }

    public function viewAll( $params )
    {
        $this->db->select('
            invoices.idInvoice
            ,CONCAT( reference.code , " - " , invoices.referenceNum ) as reference
            ,invoices.date
            ,affiliateName
            ,costCenterName
            ,(CASE
                WHEN invoices.pType = 1 THEN customer.name
                WHEN invoices.pType = 2 THEN supplier.name
                ELSE ""
            END) as name
            ,(CASE
                WHEN invoices.pType = 1 THEN customer.sk
                WHEN invoices.pType = 2 THEN supplier.sk
                ELSE ""
            END) as sk
            ,invoices.amount
            ,employee.name as preparedByName
            ,(CASE
                WHEN invoices.status = 1 THEN "Pending"
                WHEN invoices.status = 2 THEN "Approved"
                WHEN invoices.status = 3 THEN "Cancelled"
                ELSE ""
            END) as statusText

            ,invoices.idReference
            ,invoices.referenceNum
            ,invoices.idModule
        ');
        $this->db->from( 'invoices' );
        $this->db->join( 'affiliate'    , 'affiliate.idAffiliate    = invoices.idAffiliate' );
        $this->db->join( 'costcenter'   , 'costcenter.idCostCenter  = invoices.idCostCenter' , 'left' );
        $this->db->join( 'reference'    , 'reference.idReference    = invoices.idReference' );
        $this->db->join( 'customer' , 'customer.idCustomer = invoices.pCode AND invoices.pType = 1' , 'left outer');
        $this->db->join( 'supplier' , 'supplier.idSupplier = invoices.pCode AND invoices.pType = 2' , 'left outer');

        $this->db->join( 'eu'           , 'eu.idEu                  = invoices.preparedBy' );
            $this->db->join( 'employee' , 'employee.idEmployee      = eu.idEmployee' );

        if( isset( $params['filterValue'] ) ) {
            $this->db->where( 'invoices.idInvoice', $params['filterValue']);
        }

        $this->db->where( 'invoices.idAffiliate', $this->session->userdata('AFFILIATEID') );
        $this->db->where( 'invoices.idModule' , 62 );
        $this->db->where( 'invoices.archived' , 0 );
        $this->db->group_by( 'invoices.idInvoice' );
        $this->db->order_by( 'invoices.date' , 'DESC' );

        return $this->db->get()->result_array();
    }

    public function retrieveData( $idInvoice )
    {
        $this->db->select(
            ' * 
            , DATE(date) as tdate 
            , TIME_FORMAT(date, "%h:%i %p") as ttime 
            ,(CASE
                WHEN invoices.pType = 1 THEN customer.name
                WHEN invoices.pType = 2 THEN supplier.name
                ELSE ""
            END) as name 
            ,(CASE
                WHEN invoices.pType = 1 THEN customer.sk
                WHEN invoices.pType = 2 THEN supplier.sk
                ELSE ""
            END) as sk '
        );
        
        $this->db->from( 'invoices' );
        $this->db->JOIN( 'customer' , 'customer.idCustomer = invoices.pCode AND pType = 1' , 'LEFT OUTER' );
        $this->db->JOIN( 'supplier' , 'supplier.idSupplier = invoices.pCode AND pType = 2' , 'LEFT OUTER' );
        $this->db->where( 'invoices.idAffiliate', $this->session->userdata('AFFILIATEID') );
        $this->db->where( 'invoices.idInvoice' , $idInvoice );

        return $this->db->get()->result_array();
    }

    public function isClosedJE( $params )
    {
        $invMonth   = date( 'm' , strtotime( $params['tdate'] ) );
        $invYear    = date( 'Y' , strtotime( $params['tdate'] ) );
        
        return $this->db->select( "count(*) as count" )
        ->where( 'idModule' , 35 )
        ->where( 'month' , $invMonth )
        ->where( 'year' , $invYear )
        ->where_not_in( 'archived' , 1 )
        ->get( "invoices" )->row()->count;
    }

    public function checkIfUsed( $idInvoice )
    {
        $invMonth   = $this->db->select( "MONTH( invoices.date ) as month" )->where( "idInvoice" , $idInvoice )->get( "invoices" )->row()->month;
        $invYear    = $this->db->select( "YEAR( invoices.date ) as year" )->where( "idInvoice" , $idInvoice )->get( "invoices" )->row()->year;

        $closingJECount = $this->db->select( "count(*) as count" )
            ->where( 'idModule' , 35 )
            ->where( 'month' , $invMonth )
            ->where( 'year' , $invYear )
            ->where( 'status' , 2 )
            ->where_not_in( 'archived' , 1 )
            ->get( "invoices" )->row()->count;
        $this->db->reset_query();

        $receiptsCount = $this->db->select( "count(*) as count" )
            ->where( 'receipts.idInvoice' , $idInvoice )
            ->get( "receipts" )->row()->count;
        $this->db->reset_query();

        $dbCount = $this->db->select( "count(*) as count" )
            ->where( 'disbursements.idInvoice' , $idInvoice )
            ->get( "disbursements" )->row()->count;
        $this->db->reset_query();

        $isCancel = $this->db->select( "count(*) as count" )
            ->where( 'invoices.idInvoice' , $idInvoice )
            ->where( 'invoices.cancelTag' , 1 )
            ->get( "invoices" )->row()->count;
        $this->db->reset_query();

        return ( $closingJECount + $receiptsCount + $dbCount + $isCancel );
    }
    
    public function checkIfNotFound( $idInvoice )
    {
        $this->db->where( 'invoices.idInvoice' , $idInvoice );
        $this->db->where( 'invoices.archived' , 1 );
        return $this->db->get( 'invoices' )->result_array();
    }

    public function update_Invoice_BeginningBalance( $params )
    {
        $cancelledBy = 0; $hasJournal = 0;
        if ( $params['jeLength'] > 1 ) $hasJournal = 1;
        if ( $params['cancelTag'] != 0 ) $cancelledBy = $this->session->userdata('USERID');

        $data = array (
            'idAffiliate'        => $this->session->userdata('AFFILIATEID')
            ,'idModule'          => $params['idmodule']
            ,'idCostCenter'      => $params['idCostCenter']
            ,'date'              => $params['tdate'].' '.$params['ttime']
            ,'pType'             => $params['pType']
            ,'pCode'             => $params['pCode']
            ,'amount'            => $params['amount']
            ,'bal'               => $params['amount']
            ,'balLeft'           => $params['amount']
            ,'cancelTag'         => $params['cancelTag']
            ,'remarks'           => $params['remarks']
            ,'dateModified'      => date('Y-m-d h:i:a')
            ,'hasJournal'        => $hasJournal
            ,'cancelledBy'       => $cancelledBy
            ,'preparedBy'        => $this->session->userdata('USERID')
            ,'status'            => 2
            ,'archived'          => 0
            ,'idReferenceSeries' => $params['idReferenceSeries']
        );

        $this->db->where( 'invoices.idInvoice' , $params['idInvoice'] );
        return $this->db->update( 'invoices' , $data );
    } 

    public function delete_Journal_BeginningBalance( $idInvoice )
    {
        $this->db->where( 'idInvoice' , $idInvoice );
        return $this->db->delete( 'posting' );
    }

    public function archiveInvoice( $idInvoice )
    {
        $data = array (
            'archived' => 1
        );

        $this->db->where( 'idInvoice' , $idInvoice );
        return $this->db->update( 'invoices' , $data );
    }

    public function searchHistoryGrid( $params )
    {
        $this->db->select('
            invoices.idInvoice as id
            ,CONCAT( reference.code , " - " , invoices.referenceNum ) as name
        ');

        $this->db->from( 'invoices' );
        $this->db->join( 'reference'    , 'reference.idReference = invoices.idReference' );
        $this->db->where( 'invoices.idModule' , 62 );
        $this->db->where( 'invoices.archived' , 0 );
        $this->db->where( 'invoices.idAffiliate' , $this->session->userdata('AFFILIATEID') );

        if( isset( $params['query'] ) ) {
            $this->db->like("CONCAT( reference.code , ' - ' , invoices.referenceNum )", $params['query'], "both");
        }

        $this->db->group_by( 'invoices.idInvoice' );
        return $this->db->get()->result_array();
    }
}
