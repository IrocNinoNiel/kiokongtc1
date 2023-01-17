<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Chequereports extends CI_Controller
{
    public function __Construct()
    {
        parent::__Construct();
		$this->load->library( 'encryption' );
        setHeader( 'generalreports/Chequereports_model' );
    }

    public function getChequesList()
    {
        $params  = getData();
        $view    = $this->model->getChequesList( $params );

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
                'actionLogDescription' => 'Generates cheque summary report.'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            ,'idEu'                => $this->USERID
                ,'moduleID'            => 65
                ,'time'                => date('H:i:s A')
            )
        );
        
        die(
            json_encode(
                array(
                    'success'   => true 
                    ,'view'     => $view
                )
            )
        );
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
               'actionLogDescription' => 'Exported the generated cheque summary report (PDF).'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            ,'idEu'                => $this->USERID
               ,'moduleID'            => 65
               ,'time'                => date('H:i:s A')
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
               'actionLogDescription' => 'Exported the generated cheque summary report (Excel).'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            ,'idEu'                => $this->USERID
               ,'moduleID'            => 65
               ,'time'                => date('H:i:s A')
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
