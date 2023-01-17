<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Jays
 * Module       : Itemized Profit Loss
 * Date         : Feb. 17, 2020
 * Finished     : 
 * Description  : This module allows the authorized user to generate and print the per-item
 *                transactions and identify the profit or loss of every project.
 * DB Tables    : 
 * */ 
class Itemizedprofitloss extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
		$this->load->library( 'encryption' );
        setHeader( 'inventory/Itemizedprofitloss_model' );
    }

    public function getItems(){
        $params     = getData();
        $view       = $this->model->getItems( $params );
        $view       = decryptItem( $view );
        
        if( !isset( $params['query'] ) ){
            array_unshift(
                $view
                ,array(
                    'id'    => 0
                    ,'name' => 'All'
                )
            );
        }

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    public function getItemizedProfitLossReport(){
        $params     = getData();
        $view       = $this->model->getItemizedProfitLossReport( $params );
    
        $_viewHolder = $view;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['custSK'] ) && !empty( $po['custSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['custSK'] )));
                $_viewHolder[$idx]['name'] = $this->encryption->decrypt( $po['name'] );
            }
            if( isset( $po['itemSK'] ) && !empty( $po['itemSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['itemSK'] )));
                $_viewHolder[$idx]['itemName'] = $this->encryption->decrypt( $po['itemName'] );
            }
            if( isset( $po['affSK'] ) && !empty( $po['affSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affSK'] )));
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
            }            
        }
        $view = $_viewHolder;

        setLogs(
            array(
                'actionLogDescription'  => 'Generates Itemized Profit and Loss Report.'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            ,'ident'                => null
            )
        );
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                    ,'total'    => count( $view )
                )
            )
        );
    }

    

    function printPDF(){
        $params = getData();
        $data = $this->model->getItemizedProfitLossReport( $params );

        $_viewHolder = $data;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['custSK'] ) && !empty( $po['custSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['custSK'] )));
                $_viewHolder[$idx]['name'] = $this->encryption->decrypt( $po['name'] );
            }
            if( isset( $po['itemSK'] ) && !empty( $po['itemSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['itemSK'] )));
                $_viewHolder[$idx]['itemName'] = $this->encryption->decrypt( $po['itemName'] );
            }
            if( isset( $po['affSK'] ) && !empty( $po['affSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affSK'] )));
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
            }            
        }
        $data = $_viewHolder;

        $col = array(
            array(   
                'header'            => 'Affiliate'
                ,'dataIndex'        => 'affiliateName'
                ,'width'            => '10'
            )
            ,array(  
                'header'            => 'Date'
                ,'dataIndex'        => 'date'
                ,'width'            => '7'
                ,'type'             => 'datecolumn'
                ,'format'           => 'm/d/Y h:i A'
            )
            ,array(  
                'header'            => 'Reference'
                ,'dataIndex'        => 'code'
                ,'width'            => '7'
            )
            ,array(  
                'header'            => 'Customer'
                ,'dataIndex'        => 'name'
                ,'width'            => '10'
            )
            ,array(  
                'header'            => 'Code'
                ,'dataIndex'        => 'barcode'
                ,'width'            => '7'
            )
            ,array(  
                'header'            => 'Item Name'
                ,'dataIndex'        => 'itemName'
                ,'width'            => '10'
            )
            ,array(  
                'header'            => 'Unit of Measure'
                ,'dataIndex'        => 'unitName'
                ,'width'            => '7'
            )
            ,array(  
                'header'            => 'Qty'
                ,'dataIndex'        => 'qty'
                ,'width'            => '7'
                ,'format'           => '0,000'
            )
            ,array(  
                'header'            => 'Cost'
                ,'dataIndex'        => 'cost'
                ,'width'            => '7'
                ,'type'             => 'numbercolumn'
                ,'format'           => '0,000.00'
            )
            ,array(  
                'header'            => 'Price'
                ,'dataIndex'        => 'price'
                ,'width'            => '7'
                ,'type'             => 'numbercolumn'
                ,'format'           => '0,000.00'
            )
            ,array(  
                'header'            => 'Cost Amount'
                ,'dataIndex'        => 'costAmount'
                ,'width'            => '7'
                ,'type'             => 'numbercolumn'
                ,'format'           => '0,000.00'
            )
            ,array(  
                'header'            => 'Price Amount'
                ,'dataIndex'        => 'priceAmount'
                ,'width'            => '7'
                ,'type'             => 'numbercolumn'
                ,'format'           => '0,000.00'
            )
            ,array(  
                'header'            => 'Profit/(Loss)'
                ,'dataIndex'        => 'profitLoss'
                ,'width'            => '8'
                ,'type'             => 'numbercolumn'
                ,'format'           => '0,000.00'
                ,'hasTotal'         => true
            )
        );
        
        $header_fields = array(
            array(
                array(
                    'label'     => 'Affiliate'
                    ,'value'    => $params['pdf_idAffiliate']
                )
                ,array(
                    'label'     => 'Item Name'
                    ,'value'    => $params['pdf_idItem']
                )
                ,array(
                    'label'     => 'Date From'
                    ,'value'    => $params['pdf_sdate'] . ' ' . date( 'h:i A', strtotime($params['pdf_stime']) )
                )
                ,array(
                    'label'     => 'Date To'
                    ,'value'    => $params['pdf_edate'] . ' ' . date( 'h:i A', strtotime( $params['pdf_etime'] ) )
                )    
            )
        );

        setLogs(
            array(
                'actionLogDescription'  => 'Exported the generated Itemized Profit and Loss Report(PDF)'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            ,'ident'                => null
            )
        );
        generateTcpdf(
			array(
				'file_name' => $params['title']
				,'folder_name' => 'inventory'
				,'records' => $data
				,'header' => $col
				,'orientation' => 'L'
				,'header_fields' => $header_fields
			)
		);
    }

    function printExcel(){
        $params = getData();

        $csvarray = array();

		$csvarray[] = array( 'title' => $params['title']);
		$csvarray[] = array( 'space' => '' );
        
        $data = $this->model->getItemizedProfitLossReport( $params );

        $_viewHolder = $data;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['custSK'] ) && !empty( $po['custSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['custSK'] )));
                $_viewHolder[$idx]['name'] = $this->encryption->decrypt( $po['name'] );
            }
            if( isset( $po['itemSK'] ) && !empty( $po['itemSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['itemSK'] )));
                $_viewHolder[$idx]['itemName'] = $this->encryption->decrypt( $po['itemName'] );
            }
            if( isset( $po['affSK'] ) && !empty( $po['affSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affSK'] )));
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
            }            
        }
        $data = $_viewHolder;

		$csvarray[] = array( 'Affiliate', $params['pdf_idAffiliate'] );
        $csvarray[] = array( 'Item Name', $params['pdf_idItem'] );
        $csvarray[] = array( 'Date From', $params['pdf_sdate'] . ' ' . date( 'h:i A', strtotime($params['pdf_stime']) ) );
        $csvarray[] = array( 'Date To', $params['pdf_sdate'] . ' ' . date( 'h:i A', strtotime( $params['pdf_etime'] ) ) );

        $csvarray[] = array(
            'Affiliate'
            ,'Date'
            ,'Reference'
            ,'Customer'
            ,'Code'
            ,'Item Name'
            ,'Unit of Measure'
            ,'Qty'
            ,'Cost'
            ,'Price'
            ,'Cost Amount'
            ,'Price Amount'
            ,'Proft/(Loss)'
        );

        $total = 0;
        foreach( $data as $d ){
            $csvarray[] = array(
                $d['affiliateName']
                ,date( 'm/d/Y h:i A', strtotime( $d['date'] ) )
                ,$d['code']
                ,$d['name']
                ,$d['barcode']
                ,$d['itemName']
                ,$d['unitName']
                ,number_format( $d['qty'], 0 )
                ,number_format( $d['cost'], 2)
                ,number_format( $d['price'], 2 )
                ,number_format( $d['costAmount'], 2 )
                ,number_format( $d['priceAmount'], 2 )
                ,number_format( $d['profitLoss'], 2 )
            );
            $total += $d['profitLoss'];
        }
        $csvarray[] = array(
            ''
            ,''
            ,''
            ,''
            ,''
            ,''
            ,''
            ,''
            ,''
            ,''
            ,''
            ,''
            ,number_format( $total, 2 )
        );

        writeCsvFile(
            array(
                'csvarray' 	 => $csvarray
                ,'title' 	 => $params['title']
                ,'directory' => 'inventory'
            )
        );
        setLogs(
            array(
                'actionLogDescription'  => 'Exported the generated Itemized Profit and Loss Report(Excel)'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            ,'ident'                => null
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