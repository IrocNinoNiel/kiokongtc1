<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Jayson Dagulo
 * Module       : Disbursement Summary
 * Date         : Jan 30, 2019
 * Finished     : 
 * Description  : This module allows authorized user to manually closes the journal entries.
 * DB Tables    : 
 * */ 

class Disbursementsummary extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->load->library( 'encryption' );
        setHeader( 'accounting/Disbursementsummary_model' );
    }

    public function getSuppliers()
    {
        $params = getData();
        $view   = $this->model->getSuppliers( $params );
        $view   = decryptSupplier( $view );
        
        array_unshift( $view, array(
            'id' => 0
            ,'name' => 'All'
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

    public function getDisbursementSummary()
    {
        $params = getData();
        $view   = $this->model->getDisbursementSummary( $params );

        $_viewHolder = $view;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['affSK'] ) && !empty( $po['affSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affSK'] )));
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
            }
            if( isset( $po['suppSK'] ) && !empty( $po['suppSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['suppSK'] )));
                $_viewHolder[$idx]['supplierName'] = $this->encryption->decrypt( $po['supplierName'] );
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

    function printPDF(){
        $params = getData();
        $data = $this->model->getAdjustmentsummary( $params );

        $_viewHolder = $data;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['affSK'] ) && !empty( $po['affSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affSK'] )));
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
            }
            if( isset( $po['suppSK'] ) && !empty( $po['suppSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['suppSK'] )));
                $_viewHolder[$idx]['supplierName'] = $this->encryption->decrypt( $po['supplierName'] );
            }
        }
        $data = $_viewHolder;

        $col = array(
            array(   
                'header'        => 'Affiliate'
                ,'dataIndex'    => 'affiliateName'
                ,'width'        => '13%'
            )
            ,array(  
                'header'        => 'Date'
                ,'dataIndex'    => 'date'
                ,'width'        => '13%'
            )
            ,array(  
                'header'        => 'Reference'
                ,'dataIndex'    => 'code'
                ,'width'        => '12%'
            )
            ,array(  
                'header'        => 'Name'
                ,'dataIndex'    => 'supplierName'
                ,'width'        => '20%'
            )
            ,array(  
                'header'        => 'Cheque Details'
                ,'dataIndex'    => 'chequeNo'
                ,'width'        => '13%'
            )
            ,array(  
                'header'        => 'Check Date'
                ,'dataIndex'    => 'chequeDate'
                ,'width'        => '13%'
            )
            ,array(  
                'header'        => 'Remarks'
                ,'dataIndex'    => 'remarks'
                ,'width'        => '13%'
            )
            ,array(  
                'header'        => 'Total Payment'
                ,'dataIndex'    => 'amount'
                ,'width'        => '13%'
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
                    'label'     => 'Supplier'
                    ,'value'    => $params['pdf_idSupplier']
                )
                ,array(
                    'label'     => 'View By'
                    ,'value'    => $params['pdf_viewType']
                )
                ,array(
                    'label'     => 'Date & Time From'
                    ,'value'    => $params['pdf_datefrom'] . ' to ' .  $params['pdf_dateto']
                )
            )
        );

        setLogs(
            array(
               'actionLogDescription' => 'Exported the generated Disbursements Summary Report (PDF).'
                ,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
                ,'idEu'                => $this->USERID
               ,'moduleID'            => 37
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
        $data = $this->model->getAdjustmentsummary( $params );

        $_viewHolder = $data;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['affSK'] ) && !empty( $po['affSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affSK'] )));
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
            }
            if( isset( $po['suppSK'] ) && !empty( $po['suppSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['suppSK'] )));
                $_viewHolder[$idx]['supplierName'] = $this->encryption->decrypt( $po['supplierName'] );
            }
        }
        $data = $_viewHolder;
        
        $csvarray = array();

        $csvarray[] = array( 'title' => $params['title'] );
        $csvarray[] = array( 'space' => '' );

        $csvarray[] = array( 'Affiliate', $params['pdf_idAffiliate'] );
        $csvarray[] = array( 'Supplier', $params['pdf_idSupplier'] );
        $csvarray[] = array( 'View By', $params['pdf_viewType'] );
        $csvarray[] = array( 'Date', $params['pdf_datefrom'] . ' to ' . $params['pdf_dateto'] );
        $csvarray[] = array( 'space' => '' );

        $csvarray[] = array(
            'Affiliate'
            ,'Date'
            ,'Reference'
            ,'Name'
            ,'Cheque Details'
            ,'Check Date'
            ,'Remarks'
            ,'Total Payment'
        );

        foreach( $data as $d ){
            $csvarray[] = array(
                $d['affiliateName']
                ,$d['date']
                ,$d['reference']
                ,$d['supplierName']
                ,$d['chequeNo']
                ,$d['chequeDate']
                ,$d['remarks']
                ,number_format( $d['totalPayment'], 2 )
            );
        }

        setLogs(
            array(
               'actionLogDescription' => 'Exported the generated Disbursements Summary Report (Excel).'
                ,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
                ,'idEu'                => $this->USERID
               ,'moduleID'            => 37
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