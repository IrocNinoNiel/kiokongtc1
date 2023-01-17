<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Agingofpayables extends CI_Controller
{
    public function __Construct()
    {
        parent::__Construct();
		$this->load->library( 'encryption' );
        setHeader( 'generalreports/Agingofpayables_model' );
    }

    public function getSupplier()
    {
        $params = getData();
        $view = $this->model->getSupplier( $params );
        $view = decryptSupplier( $view );

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

    public function getAgingofPayables()
    {
        $params = getData();
        $view = $this->model->getAgingofPayables( $params );

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
        
        setLogs(
            array(
               'actionLogDescription' => 'Generates Aging of Payables.'
                ,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
                ,'idEu'                => $this->USERID
               ,'moduleID'            => 55
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
        $data = $this->model->getAgingofPayables( $params );

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
                ,'width'        => '15%'
            )
            ,array(  
                'header'        => 'Supplier'
                ,'dataIndex'    => 'supplierName'
                ,'width'        => '15%'
            )
            ,array(  
                'header'        => 'Current'
                ,'dataIndex'    => 'current_bal'
                ,'width'        => '14%'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
                ,'hasTotal'     => true
            )
            ,array(  
                'header'        => '30-59 Days'
                ,'dataIndex'    => 'days'
                ,'width'        => '14%'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
                ,'hasTotal'     => true
            )
            ,array(  
                'header'        => '60-59 Days'
                ,'dataIndex'    => 'dayss'
                ,'width'        => '14%'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
                ,'hasTotal'     => true
            )
            ,array(  
                'header'        => '90 Days and Above'
                ,'dataIndex'    => 'above'
                ,'width'        => '14%'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
                ,'hasTotal'     => true
            )
            
            ,array(  
                'header'        => 'Total'
                ,'dataIndex'    => 'total'
                ,'width'        => '14%'
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
                    'label'     => 'Supplier'
                    ,'value'    => $params['pdf_idSupplier']
                )
                ,array(
                    'label'     => 'Records As Of'
                    ,'value'    => $params['pdf_dateto']
                )
            )
        );

        setLogs(
            array(
               'actionLogDescription' => 'Exported the generated Aging of Payables (PDF).'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            ,'idEu'                => $this->USERID
               ,'moduleID'            => 55
               ,'time'                => date('H:i:s A')
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
        $data = $this->model->getAgingofPayables( $params );
        
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
        $csvarray[] = array( 'Records As Of', $params['pdf_dateto'] );
        $csvarray[] = array( 'space' => '' );

        $csvarray[] = array(
            'Affiliate'
            ,'Supplier'
            ,'Current'
            ,'30-59 Days'
            ,'60-89 Days'
            ,'90 Days and Above'
            ,'Total'
        );

        foreach( $data as $d ){
            $csvarray[] = array(
                $d['affiliateName']
                ,$d['supplierName']
                ,number_format( $d['current_bal'], 2 )
                ,number_format( $d['days'], 2 )
                ,number_format( $d['dayss'], 2 )
                ,number_format( $d['above'], 2 )
                ,number_format( $d['total'], 2 )
            );
        }

        $csvarray[] = array(
            ''
            ,''
            ,number_format( array_sum( array_column( $data, 'current_bal') ), 2 ) 
            ,number_format( array_sum( array_column( $data, 'days') ), 2 ) 
            ,number_format( array_sum( array_column( $data, 'dayss') ), 2 ) 
            ,number_format( array_sum( array_column( $data, 'above') ), 2 ) 
            ,number_format( array_sum( array_column( $data, 'total') ), 2 ) 
        );

        setLogs(
            array(
               'actionLogDescription' => 'Exported the generated Aging of Payables (Excel).'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            ,'idEu'                => $this->USERID
               ,'moduleID'            => 55
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
