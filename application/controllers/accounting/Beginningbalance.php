<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Beginningbalance extends CI_Controller
{
    public function __Construct()
    {
        parent::__Construct();
		$this->load->library( 'encryption' );
        setHeader( 'accounting/Beginningbalance_model' );
    }

    public function getPCodes(){
        $params     = getData();
        $data       = $this->model->getPCodes( $params );

        $_viewHolder = $data;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['sk'] ) && !empty( $po['sk'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['sk'] )));
                $_viewHolder[$idx]['name'] = $this->encryption->decrypt( $po['name'] );
            }
        }
        $data = $_viewHolder;

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $data
                )
            )
        );
    }

    public function saveBeginningBalance()
    {
        $params = getData();
        $idInvoice = 0;

        // START TRANSACTION
        $this->db->trans_begin();
        
        if ( $params['onEdit'] == 1 ) {
            if ( $params['modify'] == 0 ) {
                $dateModified = $this->standards->getDateModified( $params['idInvoice'] , 'idInvoice' , 'invoices' );
                if ( $params['dateModified'] != $dateModified->dateModified ) {
                    die(
                        json_encode(
                            array(
                                'success'   => true
                                ,'match'    => 2
                            )
                        )
                    );
                }
            }
            $idInvoice = $params['idInvoice'];
            $this->model->update_Invoice_BeginningBalance( $params );
            $this->model->delete_Journal_BeginningBalance( $idInvoice );
            $params['action']   = 'edited a transaction.';
        }

        else {
            $idInvoice = $this->model->save_Invoice_BeginningBalance( $params );
            $params['action']   = 'added a new Beginnning Balance Transaction.';
        }
        
        $idInvHistory = $this->model->save_InvoiceHistory_BeginningBalance( $params , $idInvoice );
        if ( $params['jeLength'] > 1 ) $this->model->save_JournalHistory_BeginningBalance( $params , $idInvoice , $idInvHistory);

        if( $this->db->trans_status() === FALSE ){
            $this->db->trans_rollback();
            die(
                json_encode(
                    array(
                        'success' => false
                    )
                )
            );
        }

        else {
            $this->db->trans_commit();
            $this->setLogs( $params );
            die(
                json_encode(
                    array(
                        'success' => true
                        ,'match'  => 0
                    )
                )
            );
        }
    }

    public function getBeginningBalance()
    {
        $params = getData();
        $view = $this->model->viewAll( $params );

        $_viewHolder = $view;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['sk'] ) && !empty( $po['sk'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['sk'] )));
                $_viewHolder[$idx]['name'] = $this->encryption->decrypt( $po['name'] );
            }
        }
        $view = $_viewHolder;

        die(
            json_encode(
                array(
                    'success' => true 
                    ,'view' => $view
                )
            )
        );
    }

    public function searchHistoryGrid()
    {
        $params = getData();
        $view = $this->model->searchHistoryGrid( $params );

        die(
            json_encode(
                array(
                    'success' => true 
                    ,'view' => $view
                )
            )
        );
    }

    public function retrieveData()
    {
        $match           = 0;
        $params          = getData();
        $view            = $this->model->retrieveData( $params['idInvoice'] );
        $checkIfUsed     = $this->model->checkIfUsed( $params['idInvoice'] );
        $checkIfNotFound = $this->model->checkIfNotFound( $params['idInvoice'] );

        $_viewHolder = $view;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['sk'] ) && !empty( $po['sk'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['sk'] )));
                $_viewHolder[$idx]['name'] = $this->encryption->decrypt( $po['name'] );
            }
        }
        $view = $_viewHolder;

        if ( $checkIfNotFound ) { $match = 1; }
        if ( $checkIfUsed > 0 ) { $match = 2; }

        die(
            json_encode(
                array(
                    'success' => true
                    ,'match'  => $match
                    ,'view'   => $view
                )
            )
        );
    }

    // SOFT DELETE TRANSACTION
    public function archiveInvoice()
    {
        $match              = 0;
        $params             = getData();
        $checkIfUsed        = $this->model->checkIfUsed( $this->input->post( 'idInvoice' )  );
        $checkIfNotFound    = $this->model->checkIfNotFound( $this->input->post( 'idInvoice' ) );
        
        if ( $checkIfNotFound ) { $match = 1; }
        if ( $checkIfUsed > 0 ) { $match = 2; } 
        else {
            $this->model->archiveInvoice( $this->input->post( 'idInvoice' ) );
            $params['action']   = 'deleted a transaction.';
            $this->setLogs( $params );
        }

        die(
            json_encode(
                array(
                    'success' => true
                    ,'match' => $match
                )
            )
        );
    }

    private function setLogs( $params ){
        $header = 'Beginning Balance : '.$this->USERFULLNAME;
        $action = '';
        
        if( isset( $params['deleting'] ) ){
            $action = 'deleted a transaction';
        }
        else{
            if( isset( $params['action'] ) )
                $action = $params['action'];
            else
                $action = ( $params['onEdit'] == 1  ? 'modified' : 'added new' );
        }
        
        setLogs(
            array(
                'actionLogDescription'  => $header . ' ' . $action
                ,'idReference'			=> $params['idReference']
                ,'referenceNum'			=> $params['referenceNum']
                ,'idModule'				=> $params['idmodule']
                ,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            )
        );
    }

    function generatePDF() {
        $data = getData();

        $formDetails = json_decode( $data['form'], true );
        $journalEntries = json_decode( $data['journalEntries'], true );
        
        $header_fields = array(
            array(
                array(
                    'label' => 'Reference'
                    ,'value' => $formDetails['pdf_idReference'] . '-' .$formDetails['pdf_referenceNum']
                )
                ,array(
                    'label' => 'Name'
                    ,'value' => $formDetails['pdf_name']
                )
                ,array(
                    'label' => 'Date'
                    ,'value' => $formDetails['pdf_tdate']. ' ' .$formDetails['pdf_ttime']
                )
                ,array(
                    'label' => 'Amount'
                    ,'value' => $formDetails['pdf_amount']
                    ,'type' => 'numbercolumn'
                    ,'format' => '0,000.00'
                )
            )
            ,array(
                array(
                    'label' => 'Cost Center'
                    ,'value' => $formDetails['pdf_idCostCenter']
                )
                ,array(
                    'label' => 'Remarks'
                    ,'value' => $formDetails['pdf_remarks']
                )
            )
        );

        generateTcpdf(
            array(
                'file_name'         => $data['title']
                ,'folder_name'      => 'accounting'
                ,'header_fields'    => $header_fields
                ,'orientation'      => 'P'
                ,'params'           => $data
                ,'idAffiliate'      => $data['idAffiliate']
                ,'journalEntry'     => $journalEntries
                ,'hasPrintOption'   => $data['hasPrintOption']
                // ,'hasSignatories' => 1
            ) 
        );
    }
}
