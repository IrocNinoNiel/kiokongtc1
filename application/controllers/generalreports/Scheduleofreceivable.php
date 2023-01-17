<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Developer: Hazel Alegbeleye  
 * Module: Schedule of Receivable
 * Date: March 2, 2020
 * Finished: March 2, 2020
 * Description: 
 * DB Tables: 
 * */

class Scheduleofreceivable extends CI_Controller{
    public function __Construct(){
        parent::__Construct();
        setHeader( 'generalreports/Scheduleofreceivable_model' );
    }

    function  getCustomers(){
        $params = getData();
        $view = $this->model->getCustomers( $params );
        $view   = decryptCustomer( $view );
        
        if( isset($params['hasAll']) && $params['hasAll'] == 1 ){
            array_unshift( $view, array(
                'id' => 0
                ,'name' => 'All'
            ));
        }
        
        die(
            json_encode(
                array(
                    'success' => true
                    ,'view' => $view
                )
            )
        );
    }

    function getReceivable(){
        $params = getData();
        $view = $this->model->getReceivable( $params );
        $view = decryptCustomer( $view );
        $view = decryptAffiliate( $view );

        // LQ();

        setLogs(
            array(
                'actionLogDescription' => 'Generates Receivable Schedule Report'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            ,'idEu'                => $this->USERID
                ,'moduleID'            => 60
                ,'time'                => date("H:i:s A")
            )
        );

        die(
            json_encode(
                array(
                    "success" => true
                    ,"view" => $view
                )
            )
        );
    }

    function printPDF(){
        $params = getData();
        $data = $this->model->getReceivable( $params );
        $data = decryptCustomer( $data );
        $data = decryptAffiliate( $data );

        $col = array(
            array(   
                'header'        => 'Affiliate'
                ,'dataIndex'    => 'affiliateName'
                ,'width'        => '14%'
            ),
            array(   
                'header'        => 'Reference'
                ,'dataIndex'    => 'referenceNum'
                ,'width'        => '12%'
            )
            ,array(  
                'header'        => 'Transaction Date'
                ,'dataIndex'    => 'transactionDate'
                ,'width'        => '14%'
                ,'type'         => 'datecolumn'
                ,'format'       => 'm/d/Y'
            )
            ,array(  
                'header'        => 'Due Date'
                ,'dataIndex'    => 'dueDate'
                ,'width'        => '14%'
                ,'type'         => 'datecolumn'
                ,'format'       => 'm/d/Y'
            )
            ,array(  
                'header'        => 'Customer Name'
                ,'dataIndex'    => 'customerName'
                ,'width'        => '14%'
            )
            ,array(  
                'header'        => 'Description'
                ,'dataIndex'    => 'description'
                ,'width'        => '16%'
            )
            ,array(  
                'header'        => 'Amount'
                ,'dataIndex'    => 'amount'
                ,'width'        => '14%'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
                ,'hasTotal'     => true
            )
            
            ,array(  
                'header'        => 'Balance'
                ,'dataIndex'    => 'balance'
                ,'width'        => '14%'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
                ,'hasTotal'     => true
            )
        );
        
        $header_fields = array(
            array(
                array(
                    'label'     => 'Customer Name'
                    ,'value'    => $params['pdf_idCustomer']
                )
                ,array(
                    'label'     => 'Date From'
                    ,'value'    => $params['pdf_sdate']
                )
                ,array(
                    'label'     => 'Date To'
                    ,'value'    => $params['pdf_edate']
                )
            )
        );

        setLogs(
            array(
                'actionLogDescription' => 'Exported the generated Receivable Schedule Report (PDF)'
                ,'idAffiliate'         => ( isset($params['idAffiliate']) && !empty( $params['idAffiliate'] ) ) ? $params['idAffiliate'] : $this->AFFILIATEID
                ,'idEu'                => $this->USERID
                ,'moduleID'            => 60
                ,'time'                => date("H:i:s A")
            )
        );

        generateTcpdf(
            array(
                'file_name' => $params['title']
                ,'folder_name' => 'generalreports'
                ,'records' => $data
                ,'header' => $col
                ,'orientation' => 'P'
                ,'header_fields' => $header_fields
            )
        );
    }

    function printExcel(){
        $params = getData();
        $data = $this->model->getReceivable( $params );
        $data = decryptCustomer( $data );
        $data = decryptAffiliate( $data );
        
        $csvarray = array();

        $csvarray[] = array( 'title' => $params['title'] );
        $csvarray[] = array( 'space' => '' );

        $csvarray[] = array( 'Customer Name', $params['pdf_idCustomer'] );
        $csvarray[] = array( 'Date From', $params['pdf_sdate'] );
        $csvarray[] = array( 'Date To', $params['pdf_edate'] );
        $csvarray[] = array( 'space' => '' );

        $csvarray[] = array(
            'Affiliate'
            ,'Reference'
            ,'Transaction Date '
            ,'Due Date'
            ,'Customer Name'
            ,'Description'
            ,'Amount'
            ,'Balance'
        );

        foreach( $data as $d ){
            $csvarray[] = array(
                $d['affiliateName']
                ,$d['referenceNum']
                ,$d['transactionDate']
                ,$d['dueDate']
                ,$d['customerName']
                ,$d['description']
                ,number_format( $d['amount'], 2 )
                ,number_format( $d['balance'], 2 )
            );
        }

        $csvarray[] = array(
            ''
            ,'' 
            ,'' 
            ,''
            ,''
            ,''
            ,number_format( array_sum( array_column( $data, 'amount') ), 2 ) 
            ,number_format( array_sum( array_column( $data, 'balance') ), 2 ) 
        );

        setLogs(
            array(
                'actionLogDescription' => 'Exported the generated Receivable Schedule Report (Excel)'
                ,'idEu'                => $this->USERID
                ,'moduleID'            => 60
                ,'idAffiliate'         => ( isset($params['idAffiliate']) && !empty( $params['idAffiliate'] ) ) ? $params['idAffiliate'] : $this->AFFILIATEID
                ,'time'                => date("H:i:s A")
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
