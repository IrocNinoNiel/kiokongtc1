<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Chequemonitoring extends CI_Controller
{
    public function __Construct()
    {
        parent::__Construct();
		$this->load->library( 'encryption' );
        setHeader( 'generalreports/Chequemonitoring_model' );
    }

    public function getChequesList()
    {
        $params = getData();
        $view   = $this->model->getChequesList( $params );

        $_viewHolder = $view;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['affSK'] ) && !empty( $po['affSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affSK'] )));
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
            }
            if( isset( $po['baSK'] ) && !empty( $po['baSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['baSK'] )));
                $_viewHolder[$idx]['bankAccount'] = $this->encryption->decrypt( $po['bankAccount'] );
            }
        }
        $view = $_viewHolder;

        setLogs(
            array(
                'actionLogDescription'  => 'Cheque Monitoring : '.$this->USERFULLNAME . ' Generates Cheque Monitoring Report'
			    ,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            )
        );

        die(
            json_encode(
                array(
                    'success' => true 
                    ,'view' => $view
                )
            )
        );
    }

    public function getBankAccounts()
    {
        $params = getData();
        $view   = $this->model->getBankAccounts( $params );

        $_viewHolder = $view;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['baSK'] ) && !empty( $po['baSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['baSK'] )));
                $_viewHolder[$idx]['name'] = $this->encryption->decrypt( $po['name'] );
            }
        }
        $view = $_viewHolder;

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    public function saveChequesChanges()
    {
        $match      = 0;
        $params     = getData();
        $chequeArr  = json_decode( $params['chequeArray'] ); 

        // TRANSACTION BEGINNING
        $this->db->trans_begin();

        //CHECK CHANGES 
        foreach ( $chequeArr as $cheque ) {

            // IF CHEQUE IS OUTSTANDING AND CAN BE EDITED
            if ( $cheque->oldStatus == 1 ) {

                // CHECK IN DB FOR CHANGES
                $status                 = $this->getStatusID( $cheque->status );
                $depositBankAccount     = $this->model->getBankAccount( $cheque->depositTo , $cheque->idAffiliate );
                $depositBankAccountId   = (int)$depositBankAccount['idBankAccount'] ; // to get id of bank account in depositedTo
                $checkChanges           = $this->model->checkChanges( $cheque->idPostdated , $status ,$cheque->statusDate , $depositBankAccountId ); 

                if ( !$checkChanges ) {
                    

                    $this->model->updatePostdated( $cheque->idPostdated , $status , $cheque->statusDate , $depositBankAccountId );
                    
                    $postdated = $this->model->retrievePostdated( $cheque->idPostdated );
                    $this->model->savePostdatedHistory( $postdated );

                    setLogs(
                        array(
                            'actionLogDescription'  => 'Cheque Monitoring : '.$this->USERFULLNAME . ' edited a cheque status and details'
                            ,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
                        )
                    );
                } // ELSE NO CHANGES
                
            } // ELSE CHEQUE IS NOT OUTSTANCDING AND CANNOT BE EDITED
        }

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

            

            die(
                json_encode(
                    array(
                        'success' => true
                        ,'match'  => 0
                        ,'params'  => $params
                    )
                )
            );
        }
    }

    public function getStatusID( $status )
    {
        switch ( $status ) {
            case 'Outstanding'  : return 1; break;
            case 'Cleared'      : return 2; break;
            case 'Cancelled'    : return 3; break;
            case 'Bounced'      : return 4; break;
            default:                        break;
        }
    }

    function printPDF(){
        $params = getData();
        $data = $this->model->getChequesList( $params );

        $_viewHolder = $data;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['affSK'] ) && !empty( $po['affSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affSK'] )));
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
            }
            if( isset( $po['baSK'] ) && !empty( $po['baSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['baSK'] )));
                $_viewHolder[$idx]['bankAccount'] = $this->encryption->decrypt( $po['bankAccount'] );
            }
        }
        $data = $_viewHolder;

        $col = array(
            array(   
                'header'        => 'Affiliate'
                ,'dataIndex'    => 'affiliateName'
                ,'width'        => '10%'
            )
            ,array(  
                'header'        => 'Date'
                ,'dataIndex'    => 'date'
                ,'width'        => '10%'
            )
            ,array(  
                'header'        => 'Reference'
                ,'dataIndex'    => 'reference'
                ,'width'        => '10%'
            )
            ,array(  
                'header'        => 'Description'
                ,'dataIndex'    => 'description'
                ,'width'        => '10%'
            )
            ,array(  
                'header'        => 'Bank Account'
                ,'dataIndex'    => 'bankAccount'
                ,'width'        => '10%'
            )
            ,array(  
                'header'        => 'Cheque Number'
                ,'dataIndex'    => 'chequeNo'
                ,'width'        => '10%'
            )
            ,array(  
                'header'        => 'Amount'
                ,'dataIndex'    => 'amount'
                ,'width'        => '10%'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
            )
            ,array(  
                'header'        => 'Status'
                ,'dataIndex'    => 'status'
                ,'width'        => '10%'
            )
            ,array(  
                'header'        => 'Status Date'
                ,'dataIndex'    => 'statusDate'
                ,'width'        => '10%'
            )
            ,array(
                'header'        => 'Deposited to'
                ,'dataIndex'    => 'depositTo'
                ,'width'        => '10%'
            )
        );
        
        $header_fields = array(
            array(
                array(
                    'label'     => 'Affiliate'
                    ,'value'    => $params['pdf_idAffiliate']
                )
                ,array(
                    'label'     => 'Reference'
                    ,'value'    => $params['pdf_cheque']
                )
                ,array(
                    'label'     => 'Cheque Status'
                    ,'value'    => $params['pdf_chequeStatus']
                )
                ,array(
                    'label'     => 'Date'
                    ,'value'    => $params['pdf_sdate'] . ' to ' .  $params['pdf_edate']
                )
            )
        );

        setLogs(
            array(
                'actionLogDescription'  => 'Cheque Monitoring : '.$this->USERFULLNAME . ' Exported the generated Cheque Monitoring Report (PDF)'
			    ,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            )
        );

        generateTcpdf(
            array(
                'file_name'         => $params['title']
                ,'folder_name'      => 'generalreports'
                ,'records'          => $data
                ,'header'           => $col
                ,'orientation'      => 'P'
                ,'header_fields'    => $header_fields
                ,'idAffiliate'      => $Affiliate
            )
        );
    }

    function printExcel(){
        $params = getData();
        $data = $this->model->getChequesList( $params );

        $_viewHolder = $data;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['affSK'] ) && !empty( $po['affSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affSK'] )));
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
            }
            if( isset( $po['baSK'] ) && !empty( $po['baSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['baSK'] )));
                $_viewHolder[$idx]['bankAccount'] = $this->encryption->decrypt( $po['bankAccount'] );
            }
        }
        $data = $_viewHolder;

        $csvarray = array();

        $csvarray[] = array( 'title' => $params['title'] );
        $csvarray[] = array( 'space' => '' );

        $csvarray[] = array( 'Affiliate', $params['pdf_idAffiliate'] );
        $csvarray[] = array( 'Cheque', $params['pdf_cheque'] );
        $csvarray[] = array( 'Date', $params['pdf_sdate'] . ' to ' . $params['pdf_edate']  );
        $csvarray[] = array( 'Cheque Status', $params['pdf_chequeStatus'] );
        $csvarray[] = array( 'space' => '' );

        $csvarray[] = array(
            'Affiliate'
            ,'Cheque Date'
            ,'Reference'
            ,'Description'
            ,'Bank Account'
            ,'Cheque Number'
            ,'Amount'
            ,'Status'
            ,'Status Date'
            ,'Deposit To'
        );

        foreach( $data as $d ){
            $csvarray[] = array(
                $d['affiliateName']
                ,$d['date']
                ,$d['reference']
                ,$d['description']
                ,$d['bankAccount']
                ,$d['chequeNo']
                ,number_format( $d['amount'], 2 )
                ,$d['status']
                ,$d['statusDate']
                ,$d['depositTo']
            );
        }

        setLogs(
            array(
                'actionLogDescription'  => 'Cheque Monitoring : '.$this->USERFULLNAME . ' Exported the generated Cheque Monitoring Report (Excel)'
			    ,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            )
        );

        writeCsvFile(
            array(
                'csvarray' 	 => $csvarray
                ,'title' 	 => $params['title']
                ,'directory' => 'generalreports'
            )
        );
    }

    function download( $title, $folder ){
        force_download(
            array(
                'title'      => $title
                ,'directory' => $folder
            )
        );
    }
}
