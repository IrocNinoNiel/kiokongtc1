<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Receivingsummary extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->library('encryption');
        setHeader( 'inventory/Receivingsummary_model' );
    }

    function getLocations(){
        $view = $this->model->getLocations();

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

    function getItems(){
        $params = getData();
        $view = $this->model->getItems( $params );

        if( $params['hasAll'] ) {
            array_unshift( $view, array(
                'id' => 0
                ,'name' => 'All'
            ));
        }

        // LQ();

        die(
            json_encode(
                array(
                    'success' => true
                    ,'view' => $view
                )
            )
        );
    }

    function getPO(){
        $params = getData();
        $view = $this->model->getPO( $params );

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

    function getItemClassifications(){
        $params = getData();
        $view = $this->model->getItemClassifications();

        if( isset( $params['hasAll'] ) ) {
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

    function getReceivingList(){
        $params = getData();
        $view = $this->model->getReceivingList( $params );

        $_viewHolder = $view;
        /**Custom decryption for Purchase Return**/
        foreach( $_viewHolder as $idx => $record ){
           /**Decrypting affiliate**/
           if( isset( $record['affiliateSK'] ) && !empty( $record['affiliateSK'] ) ){
                $this->encryption->initialize( array('key' => generateSKED( $record['affiliateSK'] ) ) );
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $record['affiliateName'] );
            }

            /**Decrypting supplier**/
            if( isset( $record['supplierSK'] ) && !empty( $record['supplierSK'] ) ){
                $this->encryption->initialize( array('key' => generateSKED( $record['supplierSK'] ) ) );
                $_viewHolder[$idx]['supplierName'] = $this->encryption->decrypt( $record['supplierName'] );
            }

            /**Decrypting item**/
            if( isset( $record['itemSK'] ) && !empty( $record['itemSK'] ) ){
                $this->encryption->initialize( array('key' => generateSKED( $record['itemSK'] ) ) );
                $_viewHolder[$idx]['itemName'] = $this->encryption->decrypt( $record['itemName'] );
            }

             /**Decrypting costcenter**/
             if( isset( $record['costCenterSK'] ) && !empty( $record['costCenterSK'] ) ){
                $this->encryption->initialize( array('key' => generateSKED( $record['costCenterSK'] ) ) );
                $_viewHolder[$idx]['costCenterName'] = $this->encryption->decrypt( $record['costCenterName'] );
            }
        }
        $view = $_viewHolder;

        // LQ();

        $params['idAffiliate'] = ( $params['Affiliate'] > 0 ) ? $params['Affiliate'] : $this->session->userdata('AFFILIATEID');
        $this->setLogs( $params );
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
        $data = $this->model->getReceivingList( $params );

        $_viewHolder = $data;
        /**Custom decryption for Purchase Return**/
        foreach( $_viewHolder as $idx => $record ){
           /**Decrypting affiliate**/
           if( isset( $record['affiliateSK'] ) && !empty( $record['affiliateSK'] ) ){
                $this->encryption->initialize( array('key' => generateSKED( $record['affiliateSK'] ) ) );
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $record['affiliateName'] );
            }

            /**Decrypting supplier**/
            if( isset( $record['supplierSK'] ) && !empty( $record['supplierSK'] ) ){
                $this->encryption->initialize( array('key' => generateSKED( $record['supplierSK'] ) ) );
                $_viewHolder[$idx]['supplierName'] = $this->encryption->decrypt( $record['supplierName'] );
            }

            /**Decrypting item**/
            if( isset( $record['itemSK'] ) && !empty( $record['itemSK'] ) ){
                $this->encryption->initialize( array('key' => generateSKED( $record['itemSK'] ) ) );
                $_viewHolder[$idx]['itemName'] = $this->encryption->decrypt( $record['itemName'] );
            }

             /**Decrypting costcenter**/
             if( isset( $record['costCenterSK'] ) && !empty( $record['costCenterSK'] ) ){
                $this->encryption->initialize( array('key' => generateSKED( $record['costCenterSK'] ) ) );
                $_viewHolder[$idx]['costCenterName'] = $this->encryption->decrypt( $record['costCenterName'] );
            }
        }
        $data = $_viewHolder;

        $col = array(
            array(   
                'header'        => 'Affiliate'
                ,'dataIndex'    => 'affiliateName'
                ,'width'        => '12%'
            )
            ,array(  
                'header'        => 'Cost Center'
                ,'dataIndex'    => 'costCenterName'
                ,'width'        => '12%'
            )
            ,array(  
                'header'        => 'Date'
                ,'dataIndex'    => 'date'
                ,'width'        => '10%'
            )
            ,array(  
                'header'        => 'Reference'
                ,'dataIndex'    => 'referenceNumber'
                ,'width'        => '10%'
            )
            ,array(  
                'header'        => 'Supplier'
                ,'dataIndex'    => 'supplierName'
                ,'width'        => '10%'
            )
            ,array(  
                'header'        => 'Item Name'
                ,'dataIndex'    => 'itemName'
                ,'width'        => '10%'
            )
            ,array(  
                'header'        => 'Item Class'
                ,'dataIndex'    => 'className'
                ,'width'        => '10%'
            )
            ,array(  
                'header'        => 'Cost'
                ,'dataIndex'    => 'cost'
                ,'width'        => '10%'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
                ,'hasTotal'     => true
            )
            ,array(  
                'header'        => 'Quantity'
                ,'dataIndex'    => 'qty'
                ,'width'        => '8%'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
                ,'hasTotal'     => true
            )
            ,array(  
                'header'        => 'Total'
                ,'dataIndex'    => 'total'
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
                    ,'value'    => $params['pdf_Affiliate']
                )
                ,array(
                    'label'     => 'Cost Center'
                    ,'value'    => $params['pdf_idCostCenter']
                )
                ,array(
                    'label'     => 'PO No.'
                    ,'value'    => $params['pdf_poNumber']
                )
                ,array(
                    'label'     => 'Item Name'
                    ,'value'    => $params['pdf_idItem']
                )
                ,array(
                    'label'     => 'Classification'
                    ,'value'    => $params['pdf_idItemClass']
                )
                ,array(
                    'label'     => 'Supplier'
                    ,'value'    => $params['pdf_idSupplier']
                )
                ,array(
                    'label'     => 'Date'
                    ,'value'    => $params['pdf_sdate'] . ' to ' . $params['pdf_edate'] 
                )
                ,array(
                    'label'     => 'Time'
                    ,'value'    => date( 'H:i', strtotime($params['pdf_timefrom']) ) . ' to ' . date( 'H:i', strtotime( $params['pdf_timeto'] ) ) 
                )
            )
        );

        $params['idAffiliate'] = ( $params['Affiliate'] > 0 ) ? $params['Affiliate'] : $this->session->userdata('AFFILIATEID');
        $params['export'] = 1;

        $this->setLogs( $params );

        generateTcpdf(
			array(
				'file_name'         => $params['title']
				,'folder_name'      => 'inventory'
				,'records'          => $data
				,'header'           => $col
				,'orientation'      => 'P'
                ,'header_fields'    => $header_fields
                ,'idAffiliate'      => $params['Affiliate']
			)
		);
    }

    function printExcel(){
        $params = getData();

        $csvarray = array();

		$csvarray[] = array( 'title' => $params['title']);
		$csvarray[] = array( 'space' => '' );
        
        $data = $this->model->getReceivingList( $params );

        $_viewHolder = $data;
        /**Custom decryption for Purchase Return**/
        foreach( $_viewHolder as $idx => $record ){
           /**Decrypting affiliate**/
           if( isset( $record['affiliateSK'] ) && !empty( $record['affiliateSK'] ) ){
                $this->encryption->initialize( array('key' => generateSKED( $record['affiliateSK'] ) ) );
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $record['affiliateName'] );
            }

            /**Decrypting supplier**/
            if( isset( $record['supplierSK'] ) && !empty( $record['supplierSK'] ) ){
                $this->encryption->initialize( array('key' => generateSKED( $record['supplierSK'] ) ) );
                $_viewHolder[$idx]['supplierName'] = $this->encryption->decrypt( $record['supplierName'] );
            }

            /**Decrypting item**/
            if( isset( $record['itemSK'] ) && !empty( $record['itemSK'] ) ){
                $this->encryption->initialize( array('key' => generateSKED( $record['itemSK'] ) ) );
                $_viewHolder[$idx]['itemName'] = $this->encryption->decrypt( $record['itemName'] );
            }

             /**Decrypting costcenter**/
             if( isset( $record['costCenterSK'] ) && !empty( $record['costCenterSK'] ) ){
                $this->encryption->initialize( array('key' => generateSKED( $record['costCenterSK'] ) ) );
                $_viewHolder[$idx]['costCenterName'] = $this->encryption->decrypt( $record['costCenterName'] );
            }
        }
        $data = $_viewHolder;

		$csvarray[] = array( 'Affiliate', $params['pdf_Affiliate'] );
        $csvarray[] = array( 'Cost Center', $params['pdf_idCostCenter'] );
        $csvarray[] = array( 'Reference', $params['pdf_referenceNum'] );
        $csvarray[] = array( 'Classification', $params['pdf_idItemClass'] );
        $csvarray[] = array( 'Supplier', $params['pdf_idSupplier'] );
        $csvarray[] = array( 'Date', $params['pdf_sdate'] . ' to ' . $params['pdf_sdate'] );
        $csvarray[] = array( 'Time', date( 'H:i', strtotime($params['pdf_timefrom']) ) . ' to ' . date( 'H:i', strtotime( $params['pdf_timeto'] ) ) );

        $csvarray[] = array(
            'Affiliate'
            ,'Cost Center'
            ,'Date'
            ,'Reference'
            ,'Supplier'
            ,'Item Name'
            ,'Item Classification'
            ,'Cost'
            ,'Quantity'
            ,'Total'
        );

        foreach( $data as $d ){
            $csvarray[] = array(
                $d['affiliateName']
                ,$d['costCenterName']
                ,$d['locationName']
                ,$d['date']
                ,$d['referenceNumber']
                ,$d['supplierName']
                ,$d['itemName']
                ,$d['className']
                ,number_format( $d['cost'], 2 )
                ,number_format( $d['qty'], 2 )
                ,number_format( $d['total'], 2 )
            );
        }

        $params['idAffiliate'] = ( $params['Affiliate'] > 0 ) ? $params['Affiliate'] : $this->session->userdata('AFFILIATEID');
        $params['export'] = 1;

        $this->setLogs( $params );
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
    
    private function setLogs( $params ){
        $params['actionLogDescription'] = isset( $params['export'] ) ? 'Exported the generated Receiving summary Report' : 'Generates Receiving Summary Report';
        $params['idModule']             = 34;
		
		setLogs( $params );
    }

}