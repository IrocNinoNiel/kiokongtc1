<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Makmak    
 * Module       : Releasing Summary
 * Date         : Feb. 19, 2020
 * Finished     : 
 * Description  : Releasings' Summaries
 * DB Tables    : 
 * */
    class Releasingsummary extends CI_Controller
    {
        public function __Construct()
        {
            parent::__construct();
            $this->load->library('encryption');
            setHeader( 'inventory/Releasingsummary_model' );
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

        public function getItemClass()
        {
            $view = $this->model->getItemClass();

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

        public function getCustomers()
        {
            $params = getData();
            $view = $this->model->getCustomers( $params );
            $view = decryptCustomer( $view );

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

        public function getReleasingsummary()
        {
            $params = getData();
            $view = $this->model->getReleasingsummary( $params );

            /**Custom decryption for Releasing summary**/
            $_viewHolder = $view;
            foreach( $_viewHolder as $idx => $po ){
                
                /**Decrypting affiliate**/
                if( isset( $po['affiliateSK'] ) && !empty( $po['affiliateSK'] ) ){
                    $this->encryption->initialize( array('key' => generateSKED( $po['affiliateSK'] ) ) );
                    $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
                }

                /**Decrypting customer**/
                if( isset( $po['customerSK'] ) && !empty( $po['customerSK'] ) ){
                    $this->encryption->initialize( array( 'key' => generateSKED( $po['customerSK'] )));
                    $_viewHolder[$idx]['name'] = $this->encryption->decrypt( $po['name'] );
                }
                
                /**Decrypting item**/
                if( isset( $po['itemSK'] ) && !empty( $po['itemSK'] ) ){
                    $this->encryption->initialize( array( 'key' => generateSKED( $po['itemSK'] )));
                    $_viewHolder[$idx]['itemName'] = $this->encryption->decrypt( $po['itemName'] );
                }
                
            }

            $view = $_viewHolder;
            
            setLogs(
                array(
                   'actionLogDescription' => 'Generates Releasing Summary Report.'
                   ,'idEu'                => $this->USERID
                   ,'moduleID'            => 54
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
            $data = $this->model->getReleasingsummary( $params );

            /**Custom decryption for Releasing summary**/
            $_viewHolder = $data;
            foreach( $_viewHolder as $idx => $po ){
                
                /**Decrypting affiliate**/
                if( isset( $po['affiliateSK'] ) && !empty( $po['affiliateSK'] ) ){
                    $this->encryption->initialize( array('key' => generateSKED( $po['affiliateSK'] ) ) );
                    $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
                }

                /**Decrypting customer**/
                if( isset( $po['customerSK'] ) && !empty( $po['customerSK'] ) ){
                    $this->encryption->initialize( array( 'key' => generateSKED( $po['customerSK'] )));
                    $_viewHolder[$idx]['name'] = $this->encryption->decrypt( $po['name'] );
                }
                
                /**Decrypting item**/
                if( isset( $po['itemSK'] ) && !empty( $po['itemSK'] ) ){
                    $this->encryption->initialize( array( 'key' => generateSKED( $po['itemSK'] )));
                    $_viewHolder[$idx]['itemName'] = $this->encryption->decrypt( $po['itemName'] );
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
                    ,'type'         => 'datecolumn'
                    ,'format'       => 'm/d/Y h:i A'
                    ,'width'        => '10%'
                )
                ,array(  
                    'header'        => 'Reference'
                    ,'dataIndex'    => 'code'
                    ,'width'        => '10%'
                )
                ,array(  
                    'header'        => 'Customer'
                    ,'dataIndex'    => 'name'
                    ,'width'        => '10%'
                )
                ,array(  
                    'header'        => 'Code'
                    ,'dataIndex'    => 'barcode'
                    ,'width'        => '6%'
                )
                ,array(  
                    'header'        => 'Item Name'
                    ,'dataIndex'    => 'itemName'
                    ,'width'        => '10%'
                )
                ,array(  
                    'header'        => 'Classification'
                    ,'dataIndex'    => 'className'
                    ,'width'        => '11%'
                )
                ,array(  
                    'header'        => 'Unit'
                    ,'dataIndex'    => 'unitName'
                    ,'width'        => '4%'
                )
                ,array(  
                    'header'        => 'Qty.'
                    ,'dataIndex'    => 'qty'
                    ,'width'        => '5%'
                    ,'type'         => 'numbercolumn'
                    ,'format'       => '0,000'
                    ,'hasTotal'     => true
                )
                ,array(  
                    'header'        => 'Cost'
                    ,'dataIndex'    => 'cost'
                    ,'width'        => '8%'
                    ,'type'         => 'numbercolumn'
                    ,'format'       => '0,000.00'
                    ,'hasTotal'     => true
                )
                ,array(  
                    'header'        => 'Price'
                    ,'dataIndex'    => 'price'
                    ,'width'        => '8%'
                    ,'type'         => 'numbercolumn'
                    ,'format'       => '0,000.00'
                    ,'hasTotal'     => true
                )
                ,array(  
                    'header'        => 'Amount'
                    ,'dataIndex'    => 'amount'
                    ,'width'        => '8%'
                    ,'type'         => 'numbercolumn'
                    ,'format'       => '0,000.00'
                    ,'hasTotal'     => true
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
                )
                ,array(
                    array(
                        'label'     => 'Customer'
                        ,'value'    => $params['pdf_idCustomer']
                    )
                    ,array(
                        'label'     => 'Date & Time From'
                        ,'value'    => $params['pdf_datefrom'] . ' ' . date( 'h:i A', strtotime($params['pdf_timefrom']) ) 
                    )
                    ,array(
                        'label'     => 'Date & Time To'
                        ,'value'    => $params['pdf_dateto'] . ' ' . date( 'h:i A', strtotime($params['pdf_timeto']) ) 
                    )
                )
            );

            setLogs(
                array(
                   'actionLogDescription' => 'Exported the generated Releasing Summary Report (PDF).'
                   ,'idEu'                => $this->USERID
                   ,'moduleID'            => 54
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
                )
            );
        }

        function printExcel(){
            $params = getData();
            $data = $this->model->getReleasingsummary( $params );

            /**Custom decryption for Releasing summary**/
            $_viewHolder = $data;
            foreach( $_viewHolder as $idx => $po ){
                
                /**Decrypting affiliate**/
                if( isset( $po['affiliateSK'] ) && !empty( $po['affiliateSK'] ) ){
                    $this->encryption->initialize( array('key' => generateSKED( $po['affiliateSK'] ) ) );
                    $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
                }

                /**Decrypting customer**/
                if( isset( $po['customerSK'] ) && !empty( $po['customerSK'] ) ){
                    $this->encryption->initialize( array( 'key' => generateSKED( $po['customerSK'] )));
                    $_viewHolder[$idx]['name'] = $this->encryption->decrypt( $po['name'] );
                }
                
                /**Decrypting item**/
                if( isset( $po['itemSK'] ) && !empty( $po['itemSK'] ) ){
                    $this->encryption->initialize( array( 'key' => generateSKED( $po['itemSK'] )));
                    $_viewHolder[$idx]['itemName'] = $this->encryption->decrypt( $po['itemName'] );
                }
                
            }

            $data = $_viewHolder;
            
            $csvarray = array();
    
            $csvarray[] = array( 'title' => $params['title'] );
            $csvarray[] = array( 'space' => '' );
    
            $csvarray[] = array( 'Affiliate', $params['pdf_idAffiliate'] );
            $csvarray[] = array( 'Reference', $params['pdf_idReference'] );
            $csvarray[] = array( 'Classification', $params['pdf_idItemClass'] );
            $csvarray[] = array( 'Item', $params['pdf_idItem'] );
            $csvarray[] = array( 'Customer', $params['pdf_idCustomer'] );
            $csvarray[] = array( 'Date', $params['pdf_datefrom'] . ' ' . date( 'H:i A', strtotime($params['pdf_timefrom']) ) . ' to ' . $params['pdf_dateto'] . ' ' . date( 'H:i A', strtotime( $params['pdf_timeto'] ) ) );
            $csvarray[] = array( 'space' => '' );
    
            $csvarray[] = array(
                'Affiliate'
                ,'Date'
                ,'Reference'
                ,'Customer Name'
                ,'Code'
                ,'Item Name'
                ,'Classification'
                ,'Unit'
                ,'Qty'
                ,'Cost'
                ,'Price'
                ,'Amount'
            );
    
            foreach( $data as $d ){
                $csvarray[] = array(
                    $d['affiliateName']
                    ,$d['date']
                    ,$d['code']
                    ,$d['name']
                    ,$d['barcode']
                    ,$d['itemName']
                    ,$d['className']
                    ,$d['unitName']                   
                    ,$d['qty']
                    ,number_format( $d['cost'], 2 )
                    ,number_format( $d['price'], 2 )
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
                ,''
                ,''
                ,array_sum(array_column($data, 'qty'))
                ,array_sum(array_column($data, 'cost'))
                ,array_sum(array_column($data, 'price'))
                ,array_sum(array_column($data, 'amount'))
            );

            setLogs(
                array(
                   'actionLogDescription' => 'Exported the generated Releasing Summary Report (Excel).'
                   ,'idEu'                => $this->USERID
                   ,'moduleID'            => 54
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
    