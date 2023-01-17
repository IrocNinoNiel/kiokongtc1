<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Vouchersreceivable extends CI_Controller
{
    public function __Construct()
    {
        parent::__Construct();
        $this->load->library('encryption');
        setHeader( 'accounting/Vouchersreceivable_model' );
    }

    function getProjects(){
        if( !validSession() ){
            die(
                json_encode(
                    array(
                        'status'    => false,
                        'msg'       => 'You are not authorized to perform this action.'
                    )
                )
            );
        }

        $params         = getData();
        $isConstruction = isset($params['isConstruction']) &&  filter_var($params['isConstruction'], FILTER_VALIDATE_BOOLEAN);
        $view           = $isConstruction? $this->model->getConstructionProjects() : $this->model->getTruckProjects();

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    public function getCustomers()
    {
        $params = getData();
        $view = $this->model->getCustomers( $params );
        $view = decryptCustomer( $view );

        die(
            json_encode(
                array(
                    'success' => true 
                    ,'view' => $view
                )
            )
        );
    }

    public function getCustomers_inv()
    {
        $params = getData();
        $view = $this->model->getCustomers_inv( $params );

        die(
            json_encode(
                array(
                    'success' => true 
                    ,'view' => $view
                )
            )
        );
    }

    public function save_VouchersReceivable()
    {
        $params = getData();
        $idInvoice = 0;
            
        $this->db->trans_begin();

        if ( $params['onEdit'] == 1 ) {
            $idInvoice = $params['idInvoice'];
            $this->model->update_Invoice_VouchersReceivable( $params );
            $this->model->delete_Journal_VouchersReceivable( $idInvoice );
            $params['action']   = 'edited a transaction.';
        }

        else {
            $idInvoice = $this->model->save_Invoice_VouchersReceivable( $params );
            $params['action']   = 'added a new Vouchers Receivable Transaction.';
        }

        $this->model->save_InvoiceHistory_VouchersReceivable( $params , $idInvoice );
        if ( $params['jeLength'] > 1 ) $this->model->save_JournalHistory_VouchersReceivable( $params , $idInvoice );

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

    public function getVouchersReceivable()
    {
        $params = getData();
        $view = $this->model->viewAll( $params );
        $view = decryptCustomer( $view );

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

    function customListPDF(){
        $params = getData();

        $table = array(
            array(
                'header'        => 'Date'
                ,'dataIndex'    => 'date'
                ,'width'        => '25'
            ), 
            array( 
                'header'        => 'Reference'
                ,'dataIndex'    => 'reference'
                ,'width'        => '25'
            ), 
            array( 
                'header'        => 'Name'
                ,'dataIndex'    => 'customerName'
                ,'width'        => '25'
            ), 
            array( 
                'header'        => 'Amount'
                ,'dataIndex'    => 'amount'
                ,'width'        => '25'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
            )
        );

        $params['idReference'] = null;
        $params['referenceNum'] = null;
        $params['idmodule'] = null;

        $params['action']   = 'printed an PDF report.';
        $this->setLogs( $params );

        generateTcpdf(
            array(
                'file_name'         => 'Vouchers Receivable List'
                ,'folder_name'      => 'accounting'
                ,'records'          => json_decode($params['items'], true)
                ,'header'           => $table
                ,'orientation'      => 'P'
                ,'idAffiliate'      => $this->session->userdata('AFFILIATEID')
            ) 
        );
    }

    function printExcel (){
        $data = getData();
        $sum = 0;
        $view = $this->model->viewAll( $data );

        /**Custom Decryption for Purchase Order**/
        $_viewHolder = $view;
        foreach( $_viewHolder as $idx => $po ){
            /**Decrypting supplier**/
            if( isset( $po['sk'] ) && !empty( $po['sk'] ) ){
                $this->encryption->initialize( array('key' => generateSKED( $po['sk'] ) ) );
                $_viewHolder[$idx]['customerName'] = $this->encryption->decrypt( $po['customerName'] );
            }
        }

        $view = $_viewHolder;
        
        $csvarray[] = array( 'title' => $data['pageTitle'].'' );
        $csvarray[] = array( 'space' => '' );
        $csvarray[] = array( 'space' => '' );

        $csvarray[] = array(
            'col1'  => 'Date'
            ,'col2' => 'Reference'
            ,'col3' => 'Name'
            ,'col4' => 'Amount'
        );

        foreach( $view as $value ){
            $csvarray[] = array(
                'col1' => $value[ 'date' ]
                ,'col2' => $value[ 'reference' ]
                ,'col3' => $value[ 'customerName' ]
                ,'col4' => $value[ 'amount' ]
            );
        }
        
        $data['description'] = '' .$data['pageTitle']. ": " .$this->USERNAME. ' printed an Excel report'  ;
        $data['iduser'] = $this->USERID;
        $data['usertype'] = $this->USERTYPEID;
        $data['printExcel'] = true;	
        $data['ident'] = null;
        $data['idReference'] = null;
        $data['referenceNum'] = null;
        $data['idmodule'] = null;

        $data['action']   = 'printed an Excel report.';
        $this->setLogs( $data );

        writeCsvFile(
            array(
                'csvarray' 	 => $csvarray
                ,'title' 	 => $data['pageTitle'].''
                ,'directory' => 'accounting'
            )
        );
    }

    function download($title){
        force_download(
            array(
                'title' => $title
                ,'directory' => 'accounting'
            )
        );
    }

    private function setLogs( $params ){
        $header = 'Vouchers Receivable: '.$this->USERFULLNAME;
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
                ,'idAffiliate'          => $this->session->userdata('AFFILIATEID')
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
                    'label' => 'Cost Center'
                    ,'value' => $formDetails['pdf_idCostCenter']
                )
                ,array(
                    'label' => 'Reference'
                    ,'value' => $formDetails['pdf_idReference'] . '-' .$formDetails['pdf_referenceNum']
                )
                ,array(
                    'label' => 'Customer'
                    ,'value' => $formDetails['pdf_idCustomer']
                )
                ,array(
                    'label' => 'Invoices'
                    ,'value' => $formDetails['pdf_idInvoicesOfCustomer']
                )
                ,array(
                    'label' => 'Amount'
                    ,'value' => $formDetails['pdf_amount']
                )
            )
            ,array(
                array(
                    'label' => 'Payment Method'
                    ,'value' => $formDetails['pdf_paymentMethod']
                )
                ,array(
                    'label' => 'Terms'
                    ,'value' => $formDetails['pdf_terms']
                )
                ,array(
                    'label' => 'Date'
                    ,'value' => $formDetails['pdf_tdate']
                )
                ,array(
                    'label' => 'Due Date'
                    ,'value' => $formDetails['pdf_duedate']
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
