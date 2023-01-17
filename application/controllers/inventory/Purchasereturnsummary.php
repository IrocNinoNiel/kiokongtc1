<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Purchasereturnsummary extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->library('encryption');
        setHeader( 'inventory/Purchasereturnsummary_model' );
    }

    function getPurchaseReturns(){
        $params = getData();
        $view = $this->model->getPurchaseReturns( $params );

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

    function getItems(){
        $params = getData();
        $view = $this->model->getItems( $params );
        $view = decryptItem( $view );

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

    function getReturnList(){
        $params = getData();
        $view = $this->model->getReturnList( $params );
        
        $_viewHolder = $view;
        /**Custom decryption for Purchase Return**/
        foreach( $_viewHolder as $idx => $record ){
           /**Decrypting affiliate**/
           if( isset( $record['affiliateSK'] ) && !empty( $record['affiliateSK'] ) ){
                $this->encryption->initialize( array('key' => generateSKED( $record['affiliateSK'] ) ) );
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $record['affiliateName'] );
            }

            /**Decrypting cost center**/
            if( isset( $record['costCenterSK'] ) && !empty( $record['costCenterSK'] ) ){
                $this->encryption->initialize( array('key' => generateSKED( $record['costCenterSK'] ) ) );
                $_viewHolder[$idx]['costCenterName'] = $this->encryption->decrypt( $record['costCenterName'] );
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
        }
        $view = $_viewHolder;

        if( $params['Affiliate'] > 0 ) $params['idAffiliate'] = $params['Affiliate'];
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
        $data = $this->model->getReturnList( $params );

        $_viewHolder = $data;
        /**Custom decryption for Purchase Return**/
        foreach( $_viewHolder as $idx => $record ){
           /**Decrypting affiliate**/
           if( isset( $record['affiliateSK'] ) && !empty( $record['affiliateSK'] ) ){
                $this->encryption->initialize( array('key' => generateSKED( $record['affiliateSK'] ) ) );
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $record['affiliateName'] );
            }

            /**Decrypting cost center**/
            if( isset( $record['costCenterSK'] ) && !empty( $record['costCenterSK'] ) ){
                $this->encryption->initialize( array('key' => generateSKED( $record['costCenterSK'] ) ) );
                $_viewHolder[$idx]['costCenterName'] = $this->encryption->decrypt( $record['costCenterName'] );
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
        }
        $data = $_viewHolder;


        $col = array(
            array(   
                'header'        => 'Affiliate'
                ,'dataIndex'    => 'affiliateName'
                ,'width'        => '10%'
            )
            // ,array(  
            //     'header'        => 'Cost Center'
            //     ,'dataIndex'    => 'costCenterName'
            //     ,'width'        => '10%'
            // )
            ,array(  
                'header'        => 'Date Returned'
                ,'dataIndex'    => 'date'
                ,'width'        => '8%'
            )
            ,array(  
                'header'        => 'Reference'
                ,'dataIndex'    => 'referenceNum'
                ,'width'        => '8%'
            )
            ,array(  
                'header'        => 'Supplier'
                ,'dataIndex'    => 'supplierName'
                ,'width'        => '10%'
            )
            ,array(  
                'header'        => 'Code'
                ,'dataIndex'    => 'barcode'
                ,'width'        => '10%'
            )
            ,array(  
                'header'        => 'Item Name'
                ,'dataIndex'    => 'itemName'
                ,'width'        => '22%'
            )
            ,array(  
                'header'        => 'Classification'
                ,'dataIndex'    => 'className'
                ,'width'        => '11%'
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
                'header'        => 'Qty Returned'
                ,'dataIndex'    => 'qty'
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
                    ,'value'    => $params['pdf_Affiliate']
                )
                // ,array(
                //     'label'     => 'Cost Center'
                //     ,'value'    => $params['pdf_idCostCenter']
                // )
                ,array(
                    'label'     => 'Reference'
                    ,'value'    => $params['pdf_referenceNum']
                )
                ,array(
                    'label'     => 'Location'
                    ,'value'    => $params['pdf_location']
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
            ,array(
                array(
                    'label'     => 'Classification'
                    ,'value'    => $params['pdf_idItemClass']
                )
                ,array(
                    'label'     => 'Item Name'
                    ,'value'    => $params['pdf_idItem']
                )
            )
        );

        if( $params['Affiliate'] > 0 ) $params['idAffiliate'] = $params['Affiliate'];
        $params['export'] = 1;

        $this->setLogs( $params );

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
        
        $data = $this->model->getReturnList( $params );
        $_viewHolder = $data;
        /**Custom decryption for Purchase Return**/
        foreach( $_viewHolder as $idx => $record ){
           /**Decrypting affiliate**/
           if( isset( $record['affiliateSK'] ) && !empty( $record['affiliateSK'] ) ){
                $this->encryption->initialize( array('key' => generateSKED( $record['affiliateSK'] ) ) );
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $record['affiliateName'] );
            }

            /**Decrypting cost center**/
            if( isset( $record['costCenterSK'] ) && !empty( $record['costCenterSK'] ) ){
                $this->encryption->initialize( array('key' => generateSKED( $record['costCenterSK'] ) ) );
                $_viewHolder[$idx]['costCenterName'] = $this->encryption->decrypt( $record['costCenterName'] );
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
        }
        $data = $_viewHolder;

		$csvarray[] = array( 'Affiliate', $params['pdf_Affiliate'] );
        // $csvarray[] = array( 'Cost Center', $params['pdf_idCostCenter'] );
        $csvarray[] = array( 'Reference', $params['pdf_referenceNum'] );
        $csvarray[] = array( 'Location', $params['pdf_location'] );
        $csvarray[] = array( 'Date', $params['pdf_sdate'] . ' to ' . $params['pdf_sdate'] );
        $csvarray[] = array( 'Time', date( 'H:i', strtotime($params['pdf_timefrom']) ) . ' to ' . date( 'H:i', strtotime( $params['pdf_timeto'] ) ) );
        
        $csvarray[] = array( 'Classification', $params['pdf_idItemClass'] );
        $csvarray[] = array( 'Item Name', $params['pdf_idItem'] );
        

        $csvarray[] = array(
            'Affiliate'
            // ,'Cost Center'
            ,'Location'
            ,'Date Returned'
            ,'Reference'
            ,'Supplier'
            ,'Code'
            ,'Item Name'
            ,'Item Classification'
            ,'Cost'
            ,'Qty Returned'
            ,'Amount'
        );

        foreach( $data as $d ){
            $csvarray[] = array(
                $d['affiliateName']
                // ,$d['costCenterName']
                ,$d['locationName']
                ,$d['date']
                ,$d['referenceNum']
                ,$d['supplierName']
                ,$d['barcode']
                ,$d['itemName']
                ,$d['className']
                ,number_format( $d['cost'], 2 )
                ,number_format( $d['qty'], 2 )
                ,number_format( $d['amount'], 2 )
            );
        }
        
        if( $params['Affiliate'] > 0 ) $params['idAffiliate'] = $params['Affiliate'];
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
        $params['actionLogDescription'] = isset( $params['export'] ) ? 'Exported the generated Purchase Return Summary Report' : 'Generates Purchase return summary report';
        $params['idModule']             = 39;
		
		setLogs( $params );
    }
}