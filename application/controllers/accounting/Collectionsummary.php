<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Jayson Dagulo
 * Module       : Collection Summary
 * Date         : Jan 30, 2019
 * Finished     : 
 * Description  : This module allows authorized user to manually closes the journal entries.
 * DB Tables    : 
 * */ 

class Collectionsummary extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->load->library( 'encryption' );
        setHeader( 'accounting/Collectionsummary_model' );
    }

    public function getCashReceiptReferences(){
        $params = getData();
        $view   = $this->model->getCashReceiptReferences( $params );

        array_unshift(
            $view
            ,array(
                'id'    => 0
                ,'name' => 'All'
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

    public function getCustomer(){
        $params = getData();
        $view   = $this->model->getCustomer( $params );
        $view   = decryptCustomer( $view );
        array_unshift(
            $view
            ,array(
                'id'    => 0
                ,'name' => 'All'
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

    public function getCollectionSummary()
    {
        $params = getData();
        $view   = $this->model->getCollectionSummary( $params );

        $_viewHolder = $view;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['affSK'] ) && !empty( $po['affSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affSK'] )));
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
            }
            if( isset( $po['custSK'] ) && !empty( $po['custSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['custSK'] )));
                $_viewHolder[$idx]['customerName'] = $this->encryption->decrypt( $po['customerName'] );
            }
            if( isset( $po['bankSK'] ) && !empty( $po['bankSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['bankSK'] )));
                $_viewHolder[$idx]['bankName'] = $this->encryption->decrypt( $po['bankName'] );
            }
        }
        $view = $_viewHolder;

        setLogs( array(
			'actionLogDescription' => 'Generates Collection Summary Report.'
            ,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            ,'idEu' => $this->USERID
			,'moduleID' => 36
			,'time' => date("H:i:s A")
        ));
        
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
        $data = $this->model->getCollectionSummary( $params );

        $_viewHolder = $data;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['affSK'] ) && !empty( $po['affSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affSK'] )));
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
            }
            if( isset( $po['custSK'] ) && !empty( $po['custSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['custSK'] )));
                $_viewHolder[$idx]['customerName'] = $this->encryption->decrypt( $po['customerName'] );
            }
            if( isset( $po['bankSK'] ) && !empty( $po['bankSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['bankSK'] )));
                $_viewHolder[$idx]['bankName'] = $this->encryption->decrypt( $po['bankName'] );
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
                ,'width'        => '12%'
            )
            ,array(  
                'header'        => 'Reference'
                ,'dataIndex'    => 'reference'
                ,'width'        => '10%'
            )
            ,array(  
                'header'        => 'Customer'
                ,'dataIndex'    => 'customerName'
                ,'width'        => '14%'
            )
            ,array(  
                'header'        => 'Remarks'
                ,'dataIndex'    => 'remarks'
                ,'width'        => '14%'
            )
            ,array(  
                'header'        => 'Type'
                ,'dataIndex'    => 'payMode'
                ,'width'        => '10%'
            )
            ,array(  
                'header'        => 'Bank'
                ,'dataIndex'    => 'bankName'
                ,'width'        => '10%'
            )
            ,array(  
                'header'        => 'Check #'
                ,'dataIndex'    => 'chequeNo'
                ,'width'        => '10%'
            )
            ,array(  
                'header'        => 'Amount'
                ,'dataIndex'    => 'amount'
                ,'width'        => '10%'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000'
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
                    ,'value'    => $params['pdf_idReference']
                )
                ,array(
                    'label'     => 'Customer'
                    ,'value'    => $params['pdf_idCustomer']
                )
                ,array(
                    'label'     => 'Payment Type'
                    ,'value'    => $params['pdf_payMode']
                )
                ,array(
                    'label'     => 'Date & Time From'
                    ,'value'    => $params['pdf_datefrom'] . ' ' .  date( 'h:i A', strtotime($params['pdf_timefrom']) )
                )
                ,array(
                    'label'     => 'Date & Time To'
                    ,'value'    => $params['pdf_dateto'] . ' ' . date( 'h:i A', strtotime( $params['pdf_timeto'] ) ) 
                )
            )
        );

        setLogs(
            array(
               'actionLogDescription' => 'Exported the generated Collection Summary Report (PDF).'
                ,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
                ,'idEu'                => $this->USERID
               ,'moduleID'            => 36
               ,'time'                => date('H:i:s A')
            )
        );

        generateTcpdf(
            array(
                'file_name' => $params['title']
                ,'folder_name' => 'accounting'
                ,'records' => $data
                ,'header' => $col
                ,'orientation' => 'P'
                ,'header_fields' => $header_fields
            )
        );
    }

    function printExcel(){
        $params = getData();
        $data = $this->model->getCollectionSummary( $params );

        $_viewHolder = $data;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['affSK'] ) && !empty( $po['affSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affSK'] )));
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
            }
            if( isset( $po['custSK'] ) && !empty( $po['custSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['custSK'] )));
                $_viewHolder[$idx]['customerName'] = $this->encryption->decrypt( $po['customerName'] );
            }
            if( isset( $po['bankSK'] ) && !empty( $po['bankSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['bankSK'] )));
                $_viewHolder[$idx]['bankName'] = $this->encryption->decrypt( $po['bankName'] );
            }
        }
        $data = $_viewHolder;

        $csvarray = array();

        $csvarray[] = array( 'title' => $params['title'] );
        $csvarray[] = array( 'space' => '' );

        $csvarray[] = array( 'Affiliate', $params['pdf_idAffiliate'] );
        $csvarray[] = array( 'Reference', $params['pdf_idReference'] );
        $csvarray[] = array( 'Customer', $params['pdf_idCustomer'] );
        $csvarray[] = array( 'Payment Type', $params['pdf_payMode'] );
        $csvarray[] = array( 'Date', $params['pdf_datefrom'] . ' ' . date( 'h:i A', strtotime($params['pdf_timefrom']) ) . ' to ' . $params['pdf_dateto'] . ' ' . date( 'h:i A', strtotime( $params['pdf_timeto'] ) ) );
        $csvarray[] = array( 'space' => '' );

        $csvarray[] = array(
            'Affiliate'
            ,'Date'
            ,'Reference'
            ,'Customer Name'
            ,'Remarks'
            ,'Type'
            ,'Bank'
            ,'Check #'
            ,'Amount'
        );

        foreach( $data as $d ){
            $csvarray[] = array(
                $d['affiliateName']
                ,$d['date']
                ,$d['reference']
                ,$d['customerName']
                ,$d['remarks']
                ,$d['payMode']
                ,$d['bankName']
                ,$d['chequeNo']
                ,number_format( $d['amount'], 2 )
            );
        }

        setLogs(
            array(
               'actionLogDescription' => 'Exported the generated Collection Summary Report (Excel).'
                ,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
                ,'idEu'                => $this->USERID
               ,'moduleID'            => 36
               ,'time'                => date('H:i:s A')
            )
        );

        writeCsvFile(
            array(
                'csvarray' 	 => $csvarray
                ,'title' 	 => $params['title']
                ,'directory' => 'accounting'
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