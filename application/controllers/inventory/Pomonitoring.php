<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pomonitoring extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->library('encryption');
        setHeader( 'inventory/Pomonitoring_model' );
    }

    function getLocations(){
        $params = getData();
        $view = $this->model->getLocations();

        if( isset( $params['hasAll']) ) {
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

    function getPOList( $mode = '' ){
        $params = getData();
        $params['mode'] = $mode;

        /* CHECK VALUES IF NULL */
        foreach( array_keys( $params ) as $key ) {
            if(  $params[ $key ] === NULL ||  $params[ $key ] === '' )  $params[ $key ] = 0;
        }

        $view = $this->model->getPOList( $params );

        // LQ();

        /**Custom Decryption for PO List**/
        $_viewHolder = $view;
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
                $_viewHolder[$idx]['name'] = $this->encryption->decrypt( $record['name'] );
            }

            /**Decrypting item**/
            if( isset( $record['itemSK'] ) && !empty( $record['itemSK'] ) ){
                $this->encryption->initialize( array('key' => generateSKED( $record['itemSK'] ) ) );
                $_viewHolder[$idx]['itemName'] = $this->encryption->decrypt( $record['itemName'] );
            }
        }

        $view = $_viewHolder;
        /**Ends here**/

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

    function getItems(){
        $params = getData();
        $view = $this->model->getItems( $params );
        $view = decryptItem( $view );

        if( isset( $params['hasAll'] ) ) {
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

        if( isset( $params['hasAll']) ) {
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

    function printPDF( $mode = '' ){
        $params = getData();
        $params['mode'] = $mode;

        if( $mode == 'monitoring' ) {

            $col = array(
				array(   
					'header'        => 'Affiliate'
					,'dataIndex'    => 'affiliateName'
					,'width'        => '10%'
				)
				,array(  
					'header'        => 'Cost Center'
					,'dataIndex'    => 'costCenterName'
					,'width'        => '10%'
				)
                ,array(  
					'header'        => 'Date'
					,'dataIndex'    => 'date'
                    ,'width'        => '7%'
                    ,'type'         => 'datecolumn'
                    ,'format'       => 'm-d-Y'
                )
                ,array(  
					'header'        => 'PO No.'
					,'dataIndex'    => 'poNumber'
                    ,'width'        => '7%'
				)
				,array(  
					'header'        => 'Supplier'
					,'dataIndex'    => 'name'
					,'width'        => '12%'
                )
                ,array(  
					'header'        => 'Item Classification'
					,'dataIndex'    => 'className'
					,'width'        => '11%'
                )
                ,array(  
					'header'        => 'Item Name'
					,'dataIndex'    => 'itemName'
					,'width'        => '7%'
                )
                ,array(  
					'header'        => 'Unit'
					,'dataIndex'    => 'unit'
					,'width'        => '7%'
                )
                ,array(  
					'header'        => 'Expected Qty'
					,'dataIndex'    => 'expectedQty'
                    ,'width'        => '7%'
                    ,'type'         => 'numbercolumn'
                )
                ,array(  
					'header'        => 'Actual Qty'
					,'dataIndex'    => 'actualQty'
                    ,'width'        => '5%'
                    ,'type'         => 'numbercolumn'
                )
                ,array(  
					'header'        => 'Balance'
					,'dataIndex'    => 'balance'
                    ,'width'        => '7%'
                    ,'type'         => 'numbercolumn'
                )
                ,array(  
					'header'        => 'Status'
					,'dataIndex'    => 'status'
					,'width'        => '7%'
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
						'label'     => 'Status'
						,'value'    => $params['pdf_status']
					)
					,array(
						'label'     => 'Date'
						,'value'    => $params['pdf_sdate']
					)
				)
            );
            
        } else {

            $col = array(
				array(   
					'header'        => 'Date'
					,'dataIndex'    => 'date'
					,'width'        => '25%'
				)
				,array(  
					'header'        => 'Reference'
					,'dataIndex'    => 'referenceNum'
					,'width'        => '25%'
				)
                ,array(  
					'header'        => 'Expected Qty'
					,'dataIndex'    => 'expectedQty'
                    ,'width'        => '15%'
                    ,'type'         => 'numbercolumn'
                )
                ,array(  
					'header'        => 'Received Qty'
					,'dataIndex'    => 'receivedQty'
                    ,'width'        => '15%'
                    ,'type'         => 'numbercolumn'
                )
                ,array(  
					'header'        => 'Balance'
					,'dataIndex'    => 'balance'
                    ,'width'        => '20%'
                    ,'type'         => 'numbercolumn'
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
						,'value'    => $params['pdf_sdate']
					)
				)
            );
        }

        
        $data = $this->model->getPOList( $params );
        /**Custom Decryption for PO List**/
        $_viewHolder = $data;
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
                $_viewHolder[$idx]['name'] = $this->encryption->decrypt( $record['name'] );
            }

            /**Decrypting item**/
            if( isset( $record['itemSK'] ) && !empty( $record['itemSK'] ) ){
                $this->encryption->initialize( array('key' => generateSKED( $record['itemSK'] ) ) );
                $_viewHolder[$idx]['itemName'] = $this->encryption->decrypt( $record['itemName'] );
            }
        }

        $data = $_viewHolder;
        /**Ends here**/
        
        $params['idAffiliate'] = ( $params['Affiliate'] > 0 ) ? $params['Affiliate'] : $this->session->userdata('AFFILIATEID');
        $params['export'] = 1;

        $this->setLogs( $params );

        generateTcpdf(
			array(
				'file_name'         => $params['title']
				,'folder_name'      => 'inventory'
				,'records'          => $data
				,'header'           => $col
				,'orientation'      => ($mode == 'monitoring' ) ? 'L' : 'P'
                ,'header_fields'    => $header_fields
                ,'idAffiliate'      => $params['Affiliate']
			)
		);
    }

    function printExcel( $mode = '' ){
        $params = getData();
        $params['mode'] = $mode;

        $csvarray = array();

		$csvarray[] = array( 'title' => $params['title']);
		$csvarray[] = array( 'space' => '' );
        
        $data = $this->model->getPOList( $params );

        /**Custom Decryption for PO List**/
        $_viewHolder = $data;
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
                $_viewHolder[$idx]['name'] = $this->encryption->decrypt( $record['name'] );
            }

            /**Decrypting item**/
            if( isset( $record['itemSK'] ) && !empty( $record['itemSK'] ) ){
                $this->encryption->initialize( array('key' => generateSKED( $record['itemSK'] ) ) );
                $_viewHolder[$idx]['itemName'] = $this->encryption->decrypt( $record['itemName'] );
            }
        }

        $data = $_viewHolder;
        /**Ends here**/

		if($mode == 'monitoring'){
            $csvarray[] = array( 'Affiliate', $params['pdf_Affiliate'] );
            $csvarray[] = array( 'Cost Center', $params['pdf_idCostCenter'] );
            $csvarray[] = array( 'Status', $params['pdf_status'] );
            $csvarray[] = array( 'Date', $params['pdf_sdate']);

            $csvarray[] = array(
                'Affiliate'
                ,'Cost Center'
                ,'Date'
                ,'PO No.'
                ,'Supplier'
                ,'Item Classification'
                ,'Item Name'
                ,'Unit'
                ,'Expected Qty'
                ,'Actual Qty'
                ,'Balance'
                ,'Status'
            );

            foreach( $data as $d ){
                $csvarray[] = array(
                    $d['affiliateName']
                    ,$d['costCenterName']
                    ,$d['locationName']
                    ,$d['date']
                    ,$d['poNumber']
                    ,$d['supplierName']
                    ,$d['className']
                    ,$d['itemName']
                    ,$d['unit']
                    ,number_format( $d['expectedQty'], 2 )
                    ,number_format( $d['actualQty'], 2 )
                    ,number_format( $d['balance'], 2 )
                    ,$d['status']
                );
            }
			
        }
        else{

            $csvarray[] = array( 'Affiliate', $params['pdf_Affiliate'] );
            $csvarray[] = array( 'Cost Center', $params['pdf_idCostCenter'] );
            $csvarray[] = array( 'PO #', $params['pdf_poNumber'] );
            $csvarray[] = array( 'Item Name', $params['pdf_idItem'] );
            $csvarray[] = array( 'Classification', $params['pdf_idItemClass'] );
            $csvarray[] = array( 'Supplier', $params['pdf_idSupplier'] );
            $csvarray[] = array( 'Date', $params['pdf_sdate']);

            $csvarray[] = array(
                'Date'
                ,'Reference'
                ,'Expected Qty'
                ,'Received Qty'
                ,'Balance'
            );

            foreach( $data as $d ){
                $csvarray[] = array(
                    $d['date']
                    ,$d['referenceNum']
                    ,number_format( $d['expectedQty'], 2 )
                    ,number_format( $d['receivedQty'], 2 )
                    ,number_format( $d['balance'], 2 )
                );
            }
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
        $params['actionLogDescription'] = isset( $params['export'] ) ? 'Exported the generated Purchase Order' : 'Generates Purchase Order Monitoring';
        $params['idModule']             = 30;
		
		setLogs( $params );
    }

}