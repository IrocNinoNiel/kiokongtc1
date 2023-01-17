<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Makmak    
 * Module       : Adjustment Summary
 * Date         : Feb. 18, 2020
 * Finished     : 
 * Description  : Adjustments' Summaries
 * DB Tables    : 
 * */

    class Adjustmentsummary extends CI_Controller
    {
        public function __construct(){
            parent::__construct();
		    $this->load->library( 'encryption' );
            setHeader( 'inventory/Adjustmentsummary_model' );
        }

        public function getReferences()
        {
            $params = getData();
            $view = $this->model->getReferences( $params );

            array_unshift( $view, array(
                'id' => 0
                ,'name' => 'All'
            ));

            die(
                json_encode(
                    array(
                        'success' => true 
                        ,'view' => $view
                    )
                )
            );
        }

        public function getItemClassifications()
        {
            $view = $this->model->getItemClassifications();

            array_unshift( $view, array(
                'id' => 0
                ,'name' => 'All'
            ));

            die(
                json_encode(
                    array(
                        'success' => true 
                        ,'view' => $view
                    )
                )
            );
        }

        public function getItems()
        {
            $params = getData();
            $view = $this->model->getItems( $params );
            $view = decryptItem( $view );

            array_unshift( $view, array(
                'id' => 0
                ,'name' => 'All'
            ));

            die(
                json_encode(
                    array(
                        'success' => true 
                        ,'view' => $view
                    )
                )
            );
        }

        public function getAdjustmentsummary()
        {
            $params = getData();
            $view = $this->model->getAdjustmentsummary( $params );

            $_viewHolder = $view;
            foreach( $_viewHolder as $idx => $po ){
                if( isset( $po['itemSK'] ) && !empty( $po['itemSK'] ) ){
                    $this->encryption->initialize( array( 'key' => generateSKED( $po['itemSK'] )));
                    $_viewHolder[$idx]['itemName'] = $this->encryption->decrypt( $po['itemName'] );
                }
                if( isset( $po['affiliateSK'] ) && !empty( $po['affiliateSK'] ) ){
                    $this->encryption->initialize( array( 'key' => generateSKED( $po['affiliateSK'] )));
                    $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
                }
            }
            $view = $_viewHolder;
            
            setLogs(
                array(
                   'actionLogDescription' => 'Generates Adjustment Summary Report.'
                    ,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
                    ,'idEu'                => $this->USERID
                   ,'moduleID'            => 51
                   ,'time'                => date('H:i:s A')
                )
            );

            die(
                json_encode(
                    array(
                        'success' => true
                        ,'view' => $view
                        ,'params' => $params
                    )
                )
            );
        }

        function printPDF(){
            $params = getData();
            $data = $this->model->getAdjustmentsummary( $params );
    
            $col = array(
                array(   
                    'header'        => 'Affiliate'
                    ,'dataIndex'    => 'affiliateName'
                    ,'width'        => '11%'
                )
                ,array(  
                    'header'        => 'Date'
                    ,'dataIndex'    => 'date'
                    ,'type'         => 'datecolumn'
                    ,'format'       => 'm/d/Y h:i A'
                    ,'width'        => '11%'
                )
                ,array(  
                    'header'        => 'Reference'
                    ,'dataIndex'    => 'code'
                    ,'width'        => '9%'
                )
                ,array(  
                    'header'        => 'Item Name'
                    ,'dataIndex'    => 'itemName'
                    ,'width'        => '11%'
                )
                ,array(  
                    'header'        => 'Unit'
                    ,'dataIndex'    => 'unitCode'
                    ,'width'        => '5%'
                )
                ,array(  
                    'header'        => 'Classification'
                    ,'dataIndex'    => 'className'
                    ,'width'        => '11%'
                )
                ,array(  
                    'header'        => 'Balance Qty.'
                    ,'dataIndex'    => 'qtyBal'
                    ,'width'        => '10%'
                    ,'type'         => 'numbercolumn'
                    ,'format'       => '0,000'
                )
                ,array(  
                    'header'        => 'Actual Qty'
                    ,'dataIndex'    => 'qtyActual'
                    ,'width'        => '8%'
                    ,'type'         => 'numbercolumn'
                    ,'format'       => '0,000'
                )
                ,array(  
                    'header'        => 'Cost'
                    ,'dataIndex'    => 'cost'
                    ,'width'        => '8%'
                    ,'type'         => 'numbercolumn'
                    ,'format'       => '0,000.00'
                )
                ,array(  
                    'header'        => 'Short'
                    ,'dataIndex'    => 'short'
                    ,'width'        => '8%'
                    ,'type'         => 'numbercolumn'
                    ,'format'       => '0,000'
                )
                ,array(  
                    'header'        => 'Over'
                    ,'dataIndex'    => 'over'
                    ,'width'        => '8%'
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
                        'label'     => 'Classification'
                        ,'value'    => $params['pdf_idItemClass']
                    )
                    ,array(
                        'label'     => 'Item'
                        ,'value'    => $params['pdf_idItem']
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
                   'actionLogDescription' => 'Exported the generated Adjustment Summary Report (PDF).'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            ,'idEu'                => $this->USERID
                   ,'moduleID'            => 51
                   ,'time'                => date('H:i:s A')
                )
            );

            generateTcpdf(
                array(
                    'file_name' => $params['title']
                    ,'folder_name' => 'inventory'
                    ,'records' => $data
                    ,'header' => $col
                    ,'orientation' => 'P'
                    ,'header_fields' => $header_fields
                    ,'idAffiliate' => $Affiliate
                )
            );
        }

        function printExcel(){
            $params = getData();
            $data = $this->model->getAdjustmentsummary( $params );
    
            $csvarray = array();
    
            $csvarray[] = array( 'title' => $params['title'] );
            $csvarray[] = array( 'space' => '' );
    
            $csvarray[] = array( 'Affiliate', $params['pdf_idAffiliate'] );
            $csvarray[] = array( 'Reference', $params['pdf_idReference'] );
            $csvarray[] = array( 'Classification', $params['pdf_idItemClass'] );
            $csvarray[] = array( 'Item', $params['pdf_idItem'] );
            $csvarray[] = array( 'Date', $params['pdf_datefrom'] . ' ' . date( 'H:i', strtotime($params['pdf_timefrom']) ) . ' to ' . $params['pdf_dateto'] . ' ' . date( 'H:i', strtotime( $params['pdf_timeto'] ) ) );
            $csvarray[] = array( 'space' => '' );
    
            $csvarray[] = array(
                'Affiliate'
                ,'Date'
                ,'Reference'
                ,'Item Name'
                ,'Unit'
                ,'Classification'
                ,'Balance Qty'
                ,'Actual Qty'
                ,'Cost'
                ,'Short'
                ,'Over'
            );
    
            foreach( $data as $d ){
                $csvarray[] = array(
                    $d['affiliateName']
                    ,$d['date']
                    ,$d['code']
                    ,$d['itemName']
                    ,$d['unitCode']
                    ,$d['className']
                    ,number_format( $d['qtyBal'], 2 )
                    ,number_format( $d['qtyActual'], 2 )
                    ,number_format( $d['cost'], 2 )
                    ,number_format( $d['short'], 2 )
                    ,number_format( $d['over'], 2 )
                );
            }

            setLogs(
                array(
                   'actionLogDescription' => 'Exported the generated Adjustment Summary Report (Excel).'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            ,'idEu'                => $this->USERID
                   ,'moduleID'            => 51
                   ,'time'                => date('H:i:s A')
                )
            );

            writeCsvFile(
                array(
                    'csvarray' 	 => $csvarray
                    ,'title' 	 => $params['title']
                    ,'directory' => 'inventory'
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
    