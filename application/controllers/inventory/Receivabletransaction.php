<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Makmak    
 * Module       : Receivable Transaction
 * Date         : Feb. 20, 2020
 * Finished     : 
 * Description  : 
 * DB Tables    : 
 * */
    class Receivabletransaction extends CI_Controller
    {
        public function __Construct()
        {
            parent::__construct();
            $this->load->library( 'encryption' );
            setHeader( 'inventory/Receivabletransaction_model' );
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

        public function getReceivableTransactions()
        {
            $params = getData();
            $view = $this->model->getReceivableTransactions( $params );
            
            $_viewHolder = $view;
            foreach( $_viewHolder as $idx => $po ){
                if( isset( $po['custSK'] ) && !empty( $po['custSK'] ) ){
                    $this->encryption->initialize( array( 'key' => generateSKED( $po['custSK'] )));
                    $_viewHolder[$idx]['customerName'] = $this->encryption->decrypt( $po['customerName'] );
                }
                if( isset( $po['empSK'] ) && !empty( $po['empSK'] ) ){
                    $this->encryption->initialize( array( 'key' => generateSKED( $po['empSK'] )));
                    $_viewHolder[$idx]['salesMan'] = $this->encryption->decrypt( $po['salesMan'] );
                }
                if( isset( $po['affSK'] ) && !empty( $po['affSK'] ) ){
                    $this->encryption->initialize( array( 'key' => generateSKED( $po['affSK'] )));
                    $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
                }
            }
            $view = $_viewHolder;
            
            setLogs(
                array(
                   'actionLogDescription' => 'Generates Receivable Transactions Report.'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
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
            $data = $this->model->getReceivableTransactions( $params );
    
            $_viewHolder = $data;
            foreach( $_viewHolder as $idx => $po ){
                if( isset( $po['custSK'] ) && !empty( $po['custSK'] ) ){
                    $this->encryption->initialize( array( 'key' => generateSKED( $po['custSK'] )));
                    $_viewHolder[$idx]['customerName'] = $this->encryption->decrypt( $po['customerName'] );
                }
                if( isset( $po['empSK'] ) && !empty( $po['empSK'] ) ){
                    $this->encryption->initialize( array( 'key' => generateSKED( $po['empSK'] )));
                    $_viewHolder[$idx]['salesMan'] = $this->encryption->decrypt( $po['salesMan'] );
                }
                if( isset( $po['affSK'] ) && !empty( $po['affSK'] ) ){
                    $this->encryption->initialize( array( 'key' => generateSKED( $po['affSK'] )));
                    $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
                }
            }
            $data = $_viewHolder;

            $col = array(
                array(  
                    'header'        => 'Date'
                    ,'dataIndex'    => 'date'
                    ,'type'         => 'datecolumn'
                    ,'format'       => 'm/d/Y'
                    ,'width'        => '16%'
                )
                ,array(   
                    'header'        => 'Affiliate'
                    ,'dataIndex'    => 'affiliateName'
                    ,'width'        => '16%'
                )
                ,array(  
                    'header'        => 'Reference'
                    ,'dataIndex'    => 'code'
                    ,'width'        => '16%'
                )
                ,array(  
                    'header'        => 'Customer'
                    ,'dataIndex'    => 'customerName'
                    ,'width'        => '20%'
                )
                ,array(  
                    'header'        => 'Salesman'
                    ,'dataIndex'    => 'salesMan'
                    ,'width'        => '16%'
                )
                ,array(  
                    'header'        => 'Amount'
                    ,'dataIndex'    => 'amount'
                    ,'width'        => '16%'
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
                        'label'     => 'Sales Reference'
                        ,'value'    => $params['pdf_idReference']
                    )
                    ,array(
                        'label'     => 'Customer'
                        ,'value'    => $params['pdf_idCustomer']
                    )
                    ,array(
                        'label'     => 'Date'
                        ,'value'    => $params['pdf_sdate'] . ' to ' . $params['pdf_edate'] 
                    )
                )
            );

            setLogs(
                array(
                   'actionLogDescription' => 'Exported the generated Receivable Transactions Report (PDF).'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
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
            $data = $this->model->getReceivableTransactions( $params );
            
            $_viewHolder = $data;
            foreach( $_viewHolder as $idx => $po ){
                if( isset( $po['custSK'] ) && !empty( $po['custSK'] ) ){
                    $this->encryption->initialize( array( 'key' => generateSKED( $po['custSK'] )));
                    $_viewHolder[$idx]['customerName'] = $this->encryption->decrypt( $po['customerName'] );
                }
                if( isset( $po['empSK'] ) && !empty( $po['empSK'] ) ){
                    $this->encryption->initialize( array( 'key' => generateSKED( $po['empSK'] )));
                    $_viewHolder[$idx]['salesMan'] = $this->encryption->decrypt( $po['salesMan'] );
                }
                if( isset( $po['affSK'] ) && !empty( $po['affSK'] ) ){
                    $this->encryption->initialize( array( 'key' => generateSKED( $po['affSK'] )));
                    $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
                }
            }
            $data = $_viewHolder;
            
            $csvarray = array();
    
            $csvarray[] = array( 'title' => $params['title'] );
            $csvarray[] = array( 'space' => '' );
    
            $csvarray[] = array( 'Affiliate', $params['pdf_idAffiliate'] );
            $csvarray[] = array( 'Sales Reference', $params['pdf_idReference'] );
            $csvarray[] = array( 'Customer', $params['pdf_idCustomer'] );
            $csvarray[] = array( 'Date', $params['pdf_sdate'] . ' to ' . $params['pdf_edate'] );
            $csvarray[] = array( 'space' => '' );
    
            $csvarray[] = array(
                'Date'
                ,'Affiliate'
                ,'Reference'
                ,'Customer'
                ,'Salesman'
                ,'Amount'
            );
    
            foreach( $data as $d ){
                $csvarray[] = array(
                    $d['date']
                    ,$d['affiliateName']
                    ,$d['code']
                    ,$d['customerName']
                    ,$d['salesMan']
                    ,number_format( $d['amount'], 2 )
                );
            }

            $csvarray[] = array(
                ''
                ,''
                ,''
                ,''
                ,''
                ,number_format( array_sum( array_column( $data, 'amount') ), 2 ) 
            );

            setLogs(
                array(
                   'actionLogDescription' => 'Exported the generated Receivable Transactions Report (Excel).'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
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
    