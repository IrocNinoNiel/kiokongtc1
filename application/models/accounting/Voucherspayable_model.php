<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Voucherspayable_model extends CI_Model
{
    function getConstructionProjects(){
        $this->db->select("idConstructionProject as id, projectName as name");
        $this->db->order_by('projectName asc');
        return $this->db->get('constructionproject')->result_array();
    }

    function getTruckProjects(){
        $this->db->select("idTruckProject as id, projectName as name");
        $this->db->where('archived', 0 );
        $this->db->order_by('projectName asc');
        return $this->db->get('truckproject')->result_array();
    }
    
    public function getSuppliers( $params )
    {
        $this->db->select('supplier.idSupplier as id, supplier.name, paymentMethod, sk');
        $this->db->join( 'supplieraffiliate' , 'supplieraffiliate.idSupplier = supplier.idSupplier');
        $this->db->where( 'supplieraffiliate.idAffiliate' , $this->session->userdata('AFFILIATEID') ); 
        $this->db->where( 'supplier.archived' , 0 ); 
        $this->db->group_by('supplier.idSupplier');
        return $this->db->get('supplier')->result_array();
    }

    public function getSuppliers_inv( $params )
    {
        $this->db->select(
            ' idInvoice AS id
            , CONCAT( reference.code, " - ", invoices.referenceNum ) AS name
            , invoices.amount ' 
        )
        ->join( 'reference', 'reference.idReference = invoices.idReference' );

        if ( isset( $params['idSupplier'] ) && $params['idSupplier'] != 0 ) 
        $this->db->where( 'invoices.pCode', $params['idSupplier'] ); 
        $this->db->where( 'invoices.pType', 2 ); 
        $this->db->where( 'invoices.idModule', 25 );
        $this->db->where_not_in( 'invoices.archived', 1 );
        $this->db->where_not_in( 'invoices.cancelTag', 1 );

        return $this->db->get( 'invoices' )->result_array();
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
            ->where_not_in( 'archived' , 1 )
            ->get( "invoices" )->row()->count;
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

        return ( $closingJECount + $dbCount + $isCancel );
    }

    public function checkIfNotFound( $idInvoice )
    {
        $this->db->where( 'invoices.idInvoice' , $idInvoice );
        $this->db->where( 'invoices.archived' , 1 );
        return $this->db->get( 'invoices' )->result_array();
    }

    public function retrieveData( $idInvoice )
    {
        $this->db->select(' * , DATE(date) as tdate , TIME_FORMAT(date, "%h:%i %p") as ttime ');
        $this->db->from( 'invoices' );
        $this->db->where( 'invoices.idInvoice' , $idInvoice );

        return $this->db->get()->result_array();
    }

    public function viewAll( $params )
    {
        $this->db->select('
            invoices.idInvoice
            ,affiliateName
            ,costCenterName
            ,date
            ,CONCAT( reference.code , " - " , invoices.referenceNum ) as reference
            ,supplier.name as supplierName
            ,supplier.sk    AS supplierSK
            ,employee.name as preparedByName
            ,(
                CASE
                WHEN invoices.status = 1 THEN "Pending"
                WHEN invoices.status = 2 THEN "Approved"
                WHEN invoices.status = 3 THEN "Cancelled"
                ELSE ""
                END
            ) as statusText
            ,invoices.amount

            ,invoices.idReference
            ,invoices.referenceNum
            ,invoices.idModule
        ');
        $this->db->from( 'invoices' );
        $this->db->join( 'affiliate'    , 'affiliate.idAffiliate = invoices.idAffiliate' );
        $this->db->join( 'costcenter'   , 'costcenter.idCostCenter = invoices.idCostCenter' , 'left' );
        $this->db->join( 'reference'    , 'reference.idReference = invoices.idReference' );
        $this->db->join( 'supplier'     , 'supplier.idSupplier = invoices.pCode' );
        $this->db->join( 'eu'           , 'eu.idEu = invoices.preparedBy' );
            $this->db->join( 'employee' , 'employee.idEmployee = eu.idEmployee' );

        if( isset( $params['filterValue'] ) ) {
            $this->db->where( 'invoices.idInvoice', $params['filterValue']);
        }

        $this->db->where( 'invoices.idAffiliate', $this->session->userdata('AFFILIATEID') );
        $this->db->where( 'invoices.idModule' , 57 );
        $this->db->where( 'invoices.archived' , 0 );
        $this->db->group_by( 'invoices.idInvoice' );
        $this->db->order_by( 'invoices.date', 'DESC' );

        return $this->db->get()->result_array();
    }

    public function searchHistoryGrid( $params )
    {
        $this->db->select('
            invoices.idInvoice as id
            ,CONCAT( reference.code , " - " , invoices.referenceNum ) as name
        ');

        $this->db->from( 'invoices' );
        $this->db->join( 'reference'    , 'reference.idReference = invoices.idReference' );
        $this->db->where( 'invoices.idAffiliate', $this->session->userdata('AFFILIATEID') );
        $this->db->where( 'invoices.idModule' , 57 );
        $this->db->where( 'invoices.archived' , 0 );

        if( isset( $params['query'] ) ) {
            $this->db->like("CONCAT( reference.code , ' - ' , invoices.referenceNum )", $params['query'], "both");
        }

        $this->db->group_by( 'invoices.idInvoice' );
        return $this->db->get()->result_array();
    }

    public function save_Invoice_VouchersPayable( $params )
    {
        $terms = null; $cancelledBy = 0; $hasJournal = 0;
        if ( isset( $params['terms'] ) ) $terms = $params['terms'];
        if ( $params['jeLength'] > 1 ) $hasJournal = 1;
        if ( $params['cancelTag'] != 0 ) $cancelledBy = $this->session->userdata('USERID');

        $data = array (
            'idAffiliate'           => $this->session->userdata('AFFILIATEID')
            ,'idReference'          => $params['idReference']
            ,'referenceNum'         => $params['referenceNum']
            ,'idModule'             => $params['idmodule']
            ,'idCostCenter'         => $params['idCostCenter']
            ,'date'                 => $params['tdate'].' '.$params['ttime']
            ,'pType'                => 2
            ,'pCode'                => $params['idSupplier']
            ,'payMode'              => $params['paymentMethod']
            ,'amount'               => $params['amount']
            ,'bal'                  => $params['amount']
            ,'balLeft'              => $params['amount']
            ,'cancelTag'            => $params['cancelTag']
            ,'remarks'              => $params['remarks']
            ,'duedate'              => $params['duedate']
            ,'fident'               => (isset($params['idInvoicesOfSupplier']) ? $params['idInvoicesOfSupplier'] : null)
            ,'terms'                => $terms
            ,'hasJournal'           => $hasJournal
            ,'preparedBy'           => $this->session->userdata('USERID')
            ,'cancelledBy'          => $cancelledBy
            ,'status'               => 2
            ,'archived'             => 0
            ,'idReferenceSeries'    => $params['idReferenceSeries']
            ,'isConstruction'           => $params['isConstruction']
            ,'idProject'                => $params['idProject']
        );

        $this->db->insert( 'invoices' , $data );
        return $this->db->insert_id();
    } 
    
    public function save_InvoiceHistory_VouchersPayable( $params , $idInvoice )
    {
        $terms = null; $cancelledBy = 0; $hasJournal = 0;
        if ( $params['jeLength'] > 1 ) $hasJournal = 1;
        if ( isset( $params['terms'] ) ) $terms = $params['terms'];
        if ( $params['cancelTag'] != 0 ) $cancelledBy = $this->session->userdata('USERID');

        $data = array(
            'idInvoice'                 => $idInvoice    
            ,'idAffiliate'              => $this->session->userdata('AFFILIATEID')
            ,'idReference'              => $params['idReference']
            ,'idModule'                 => $params['idmodule']
            ,'idCostCenter'             => $params['idCostCenter']
            ,'date'                     => $params['tdate'].' '.$params['ttime']
            ,'pType'                    => 2
            ,'pCode'                    => $params['idSupplier']
            ,'payMode'                  => $params['paymentMethod']
            ,'amount'                   => $params['amount']
            ,'bal'                      => $params['amount']
            ,'balLeft'                  => $params['amount']
            ,'cancelTag'                => $params['cancelTag']
            ,'remarks'                  => $params['remarks']
            ,'duedate'                  => $params['duedate']
            ,'fident'                   => (isset($params['idInvoicesOfSupplier']) ? $params['idInvoicesOfSupplier'] : null)
            ,'terms'                    => $terms
            ,'hasJournal'               => $hasJournal
            ,'preparedBy'               => $this->session->userdata('USERID')
            ,'cancelledBy'          => $cancelledBy
            ,'status'                   => 2
            ,'referenceNum'             => $params['referenceNum']
            ,'idReferenceSeries'        => $params['idReferenceSeries']
            ,'isConstruction'           => $params['isConstruction']
            ,'idProject'                => $params['idProject']
        );

        return $this->db->insert( 'invoiceshistory' , $data );
    }

    public function save_JournalHistory_VouchersPayable( $params , $idInvoice )
    {
        $data = array();
        $journal_entry = json_decode( $this->input->post( 'jeRecords' ) );
        
        foreach ( $journal_entry as $je ) {
            $data[] = array(
                'idPosting'     => $this->save_Journal_VouchersPayable( $idInvoice , $je )
                ,'idInvoice'    => $idInvoice
                ,'explanation'  => $je->explanation
                ,'debit'        => $je->debit
                ,'credit'       => $je->credit
                ,'idCoa'        => $je->idCoa
                ,'idCostCenter' => $je->idCostCenter
            );
        }

        return $this->db->insert_batch( 'postinghistory' , $data );
    }

    public function save_Journal_VouchersPayable( $idInvoice , $je )
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

    public function update_Invoice_VouchersPayable( $params )
    {
        $terms = null; $cancelledBy = 0; $hasJournal = 0;
        if ( $params['jeLength'] > 1 ) $hasJournal = 1;
        if ( isset( $params['terms'] ) ) $terms = $params['terms'];
        if ( $params['cancelTag'] != 0 ) $cancelledBy = $this->session->userdata('USERID');

        $data = array (
            'idAffiliate'           => $this->session->userdata('AFFILIATEID')
            ,'idReference'          => $params['idReference']
            ,'referenceNum'         => $params['referenceNum']
            ,'idModule'             => $params['idmodule']
            ,'idCostCenter'         => $params['idCostCenter']
            ,'date'                 => $params['tdate'].' '.$params['ttime']
            ,'pType'                => 2
            ,'pCode'                => $params['idSupplier']
            ,'payMode'              => $params['paymentMethod']
            ,'amount'               => $params['amount']
            ,'bal'                  => $params['amount']
            ,'balLeft'              => $params['amount']
            ,'cancelTag'            => $params['cancelTag']
            ,'remarks'              => $params['remarks']
            ,'duedate'              => $params['duedate']
            ,'fident'               => (isset($params['idInvoicesOfSupplier']) ? $params['idInvoicesOfSupplier'] : null)
            ,'terms'                => $terms
            ,'hasJournal'           => $hasJournal
            ,'preparedBy'           => $this->session->userdata('USERID')
            ,'cancelledBy'          => $cancelledBy
            ,'status'               => 2
            ,'archived'             => 0
            ,'idReferenceSeries'    => $params['idReferenceSeries']
            ,'isConstruction'       => $params['isConstruction']
            ,'idProject'            => $params['idProject']
        );

        $this->db->where( 'invoices.idInvoice' , $params['idInvoice'] );
        return $this->db->update( 'invoices' , $data );
    } 

    public function delete_Journal_VouchersPayable( $idInvoice )
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
}

