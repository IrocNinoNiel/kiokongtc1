<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Makmak    
 * Module       : Conversion Summary
 * Date         : Feb. 20, 2020
 * Finished     : 
 * Description  : Conversions' Summaries
 * DB Tables    : 
 * */
    class Conversionsummary extends CI_Controller
    {
        public function __Construct()
        {
            parent::__construct();
            $this->load->library('encryption');
            setHeader( 'inventory/Conversionsummary_model' );
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

        public function getInvConversions()
        {
            $params = getData();
            $view = $this->model->getInvConversions( $params );

            /**Custom decryption for Conversion Summary**/
            $_viewHolder = $view;
            foreach( $_viewHolder as $idx => $record ){
                /**Decrypting affiliate**/
                if( isset( $record['affiliateSK'] ) && !empty( $record['affiliateSK'] ) ){
                    $this->encryption->initialize( array('key' => generateSKED( $record['affiliateSK'] ) ) );
                    $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $record['affiliateName'] );
                }

                /**Decrypting item**/
                if( isset( $record['itemSK'] ) && !empty( $record['itemSK'] ) ){
                    $this->encryption->initialize( array('key' => generateSKED( $record['itemSK'] ) ) );
                    $_viewHolder[$idx]['itemName'] = $this->encryption->decrypt( $record['itemName'] );
                }
            }    
            
            $view = $_viewHolder;

            setLogs(
                array(
                   'actionLogDescription' => 'Generates Conversions Summary Report.'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            ,'idEu'                => $this->USERID
                   ,'moduleID'            => 53
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

        function generatePDF() {
            $data = getData();
      
            $formDetails = json_decode( $data['form'], true );
            $poItems = json_decode( $data['poItems'], true );
      
            $header_fields = array(
                array(
                    array(
                        'label'     => 'Affiliate'
                        ,'value'    => $formDetails['pdf_idAffiliate']
                    )
                    ,array(
                        'label'     => 'Reference'
                        ,'value'    => $formDetails['pdf_idReference']
                    )
                    ,array(
                        'label'     => 'Item'
                        ,'value'    => $formDetails['pdf_idItem']
                    )
                    ,array(
                        'label'     => 'Date & Time From'
                        ,'value'    => $formDetails['pdf_datefrom'] . ' ' . date( 'h:i A', strtotime($params['pdf_timefrom']) )
                    )
                    ,array(
                        'label'     => 'Date & Time To'
                        ,'value'    => $formDetails['pdf_dateto'] . ' ' . date( 'h:i A', strtotime( $params['pdf_timeto'] ) ) 
                    )
                )
            );
      
            $table = array(
                array(   
                    'header'        => 'Affiliate'
                    ,'dataIndex'    => 'affiliateName'
                    ,'width'        => '12%'
                )
                ,array(  
                    'header'        => 'Date'
                    ,'dataIndex'    => 'date'
                    ,'type'         => 'datecolumn'
                    ,'format'       => 'm/d/Y h:i A'
                    ,'width'        => '14%'
                )
                ,array(  
                    'header'        => 'Reference'
                    ,'dataIndex'    => 'code'
                    ,'width'        => '8%'
                )
                ,array(  
                    'header'        => 'Code'
                    ,'dataIndex'    => 'barcode'
                    ,'width'        => '11%'
                )
                ,array(  
                    'header'        => 'Item Name'
                    ,'dataIndex'    => 'itemName'
                    ,'width'        => '12%'
                )
                ,array(  
                    'header'        => 'Unit'
                    ,'dataIndex'    => 'unitCode'
                    ,'width'        => '5%'
                )
                ,array(  
                    'header'        => 'Cost'
                    ,'dataIndex'    => 'cost'
                    ,'width'        => '9%'
                    ,'type'         => 'numbercolumn'
                    ,'format'       => '0,000.00'
                    ,'hasTotal'     => true
                )
                ,array(  
                    'header'        => 'Received'
                    ,'dataIndex'    => 'received'
                    ,'width'        => '9%'
                    ,'type'         => 'numbercolumn'
                    ,'format'       => '0,000'
                    ,'hasTotal'     => true
                )
                ,array(  
                    'header'        => 'Released'
                    ,'dataIndex'    => 'released'
                    ,'width'        => '10%'
                    ,'type'         => 'numbercolumn'
                    ,'format'       => '0,000'
                    ,'hasTotal'     => true
                )
                ,array(  
                    'header'        => 'Amount'
                    ,'dataIndex'    => 'amount'
                    ,'width'        => '10%'
                    ,'type'         => 'numbercolumn'
                    ,'format'       => '0,000.00'
                    ,'hasTotal'     => true
                )
            );
      
            generateTcpdf(
                array(
                    'file_name'         => 'Conversion Summary'
                    ,'folder_name'      => 'inventory'
                    ,'header_fields'    => $header_fields
                    ,'records'          => $poItems
                    ,'header'           => $table
                    ,'orientation'      => 'P'
                    ,'params'           => $data
                    ,'idAffiliate'      => $Affiliate
                ) 
            );
        }

        function printExcel(){
            $params = getData();
            $data = $this->model->getInvConversions( $params );
            
            /**Custom decryption for Conversion Summary**/
            $_viewHolder = $data;
            foreach( $_viewHolder as $idx => $record ){
                /**Decrypting affiliate**/
                if( isset( $record['affiliateSK'] ) && !empty( $record['affiliateSK'] ) ){
                    $this->encryption->initialize( array('key' => generateSKED( $record['affiliateSK'] ) ) );
                    $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $record['affiliateName'] );
                }

                /**Decrypting item**/
                if( isset( $record['itemSK'] ) && !empty( $record['itemSK'] ) ){
                    $this->encryption->initialize( array('key' => generateSKED( $record['itemSK'] ) ) );
                    $_viewHolder[$idx]['itemName'] = $this->encryption->decrypt( $record['itemName'] );
                }
            }    
            
            $data = $_viewHolder;
            
            $csvarray = array();
    
            $csvarray[] = array( 'title' => $params['title'] );
            $csvarray[] = array( 'space' => '' );
    
            $csvarray[] = array( 'Affiliate', $params['pdf_idAffiliate'] );
            $csvarray[] = array( 'Reference', $params['pdf_idReference'] );
            $csvarray[] = array( 'Item', $params['pdf_idItem'] );
            $csvarray[] = array( 'Date', $params['pdf_datefrom'] . ' ' . date( 'h:i A', strtotime($params['pdf_timefrom']) ) . ' to ' . $params['pdf_dateto'] . ' ' . date( 'H:i', strtotime( $params['pdf_timeto'] ) ) );
            $csvarray[] = array( 'space' => '' );
    
            $csvarray[] = array(
                'Affiliate'
                ,'Date'
                ,'Reference'
                ,'Code'
                ,'Item'
                ,'Unit'
                ,'Cost'
                ,'Received'
                ,'Released'
                ,'Amount'
            );
    
            foreach( $data as $d ){
                $csvarray[] = array(
                    $d['affiliateName']
                    ,$d['date']
                    ,$d['code']
                    ,$d['barcode']
                    ,$d['itemName']
                    ,$d['unitCode']                   
                    ,number_format( $d['cost'], 2 )
                    ,number_format( $d['received'] )
                    ,number_format( $d['released'] )
                    ,number_format( $d['amount'], 2 )
                );
            }

            $csvarray[] = array(
                ''
                ,''
                ,''
                ,''
                ,''
                ,''
                ,number_format( array_sum( array_column( $data, 'cost') ), 2 ) 
                ,number_format( array_sum( array_column( $data, 'received') ) ) 
                ,number_format( array_sum( array_column( $data, 'released') ) ) 
                ,number_format( array_sum( array_column( $data, 'amount') ), 2 ) 
            );

            setLogs(
                array(
                   'actionLogDescription' => 'Exported the generated Conversions Summary Report (Excel).'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            ,'idEu'                => $this->USERID
                   ,'moduleID'            => 53
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
    