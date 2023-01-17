<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cancelledtransactions extends CI_Controller
{
    public function __Construct()
    {
        parent::__construct();
		$this->load->library( 'encryption' );
        setHeader( 'inventory/Cancelledtransactions_model' );
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

    public function getModules()
    {
        $params = getData();
        $view = $this->model->getModules( $params );

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

    public function getPNames()
    {
        $params = getData();
        $view = $this->model->getPNames( $params );

        foreach( $view as $idx => $name ){
            $view[$idx] = ( $params['pType'] == 1 ) ? decryptCustomer( array( 0 => $name ) )[0] : decryptSupplier( array( 0 => $name ) )[0];
        }

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

    public function getCancelledtransactions()
    {
        $params = getData();
        $view = $this->model->getCancelledtransactions( $params );

        $_viewHolder = $view;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['nameSK'] ) && !empty( $po['nameSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['nameSK'] )));
                $_viewHolder[$idx]['name'] = $this->encryption->decrypt( $po['name'] );
            }
            if( isset( $po['affSK'] ) && !empty( $po['affSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affSK'] )));
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
            }
            if( isset( $po['empSK'] ) && !empty( $po['empSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['empSK'] )));
                $_viewHolder[$idx]['cancelledBy'] = $this->encryption->decrypt( $po['cancelledBy'] );
            }
        }
        $view = $_viewHolder;

        setLogs(
            array(
               'actionLogDescription' => 'Cancelled Transactions : '.$this->USERFULLNAME . ' generates Cancelled Transactions Report'
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

    function printPDF(){
        $params = getData();
        $data = $this->model->getCancelledtransactions( $params );

        $_viewHolder = $data;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['nameSK'] ) && !empty( $po['nameSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['nameSK'] )));
                $_viewHolder[$idx]['name'] = $this->encryption->decrypt( $po['name'] );
            }
            if( isset( $po['affSK'] ) && !empty( $po['affSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affSK'] )));
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
            }
            if( isset( $po['empSK'] ) && !empty( $po['empSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['empSK'] )));
                $_viewHolder[$idx]['cancelledBy'] = $this->encryption->decrypt( $po['cancelledBy'] );
            }
        }
        $data = $_viewHolder;

        $col = array(
            array(   
                'header'        => 'Affiliate'
                ,'dataIndex'    => 'affiliateName'
                ,'width'        => '15%'
            )
            ,array(  
                'header'        => 'Reference'
                ,'dataIndex'    => 'reference'
                ,'width'        => '10%'
            )
            ,array(  
                'header'        => 'Date'
                ,'dataIndex'    => 'date'
                ,'type'         => 'datecolumn'
                ,'format'       => 'm/d/Y h:i A'
                ,'width'        => '15%'
            )
            ,array(  
                'header'        => 'Name'
                ,'dataIndex'    => 'name'
                ,'width'        => '15%'
            )
            ,array(  
                'header'        => 'Remarks'
                ,'dataIndex'    => 'remarks'
                ,'width'        => '15%'
            )
            ,array(  
                'header'        => 'Cancelled By'
                ,'dataIndex'    => 'cancelledBy'
                ,'width'        => '15%'
            )
            ,array(  
                'header'        => 'Amount'
                ,'dataIndex'    => 'amount'
                ,'width'        => '15%'
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
                    'label'     => 'Module'
                    ,'value'    => $params['pdf_idModule']
                )
                ,array(
                    'label'     => 'Type'
                    ,'value'    => $params['pdf_pType']
                )
                ,array(
                    'label'     => 'Name'
                    ,'value'    => $params['pdf_pCode']
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
               'actionLogDescription' => 'Cancelled Transactions : '.$this->USERFULLNAME . ' exported the generated Cancelled Transactions Report (PDF)'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
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
        $data = $this->model->getCancelledtransactions( $params );
        
        $_viewHolder = $data;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['nameSK'] ) && !empty( $po['nameSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['nameSK'] )));
                $_viewHolder[$idx]['name'] = $this->encryption->decrypt( $po['name'] );
            }
            if( isset( $po['affSK'] ) && !empty( $po['affSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affSK'] )));
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
            }
            if( isset( $po['empSK'] ) && !empty( $po['empSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['empSK'] )));
                $_viewHolder[$idx]['cancelledBy'] = $this->encryption->decrypt( $po['cancelledBy'] );
            }
        }
        $data = $_viewHolder;
        
        $csvarray = array();

        $csvarray[] = array( 'title' => $params['title'] );
        $csvarray[] = array( 'space' => '' );

        $csvarray[] = array( 'Affiliate', $params['pdf_idAffiliate'] );
        $csvarray[] = array( 'Reference', $params['pdf_idReference'] );
        $csvarray[] = array( 'Module', $params['pdf_idModule'] );
        $csvarray[] = array( 'Type', $params['pdf_pType'] );
        $csvarray[] = array( 'Name', $params['pdf_pCode'] );
        $csvarray[] = array( 'Date', $params['pdf_datefrom'] . ' ' . date( 'h:i A', strtotime($params['pdf_timefrom']) ) . ' to ' . $params['pdf_dateto'] . ' ' . date( 'h:i A', strtotime( $params['pdf_timeto'] ) ) );
        $csvarray[] = array( 'space' => '' );

        $csvarray[] = array(
            'Affiliate'
            ,'Reference'
            ,'Date'
            ,'Name'
            ,'Remarks'
            ,'Cancelled By'
            ,'Amount'
        );

        foreach( $data as $d ){
            $csvarray[] = array(
                $d['affiliateName']
                ,$d['reference']
                ,$d['date']
                ,$d['name']
                ,$d['remarks']                   
                ,$d['cancelledBy']
                ,number_format( $d['amount'], 2 )
            );
        }

        setLogs(
            array(
               'actionLogDescription' => 'Cancelled Transactions : '.$this->USERFULLNAME . ' exported the generated Cancelled Transactions Report (Excel)'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
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
