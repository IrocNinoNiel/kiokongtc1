<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Salesordermonitoring extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->library( 'encryption' );
        setHeader( 'inventory/Salesordermonitoring_model' );
    }

    public function getReleasable(){
        $params = getData();
        $view = $this->model->getReleasable($params);

        $_viewHolder = $view;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['itemSK'] ) && !empty( $po['itemSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['itemSK'] )));
                $_viewHolder[$idx]['item'] = $this->encryption->decrypt( $po['item'] );
            }
            if( isset( $po['affiliateSK'] ) && !empty( $po['affiliateSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affiliateSK'] )));
                $_viewHolder[$idx]['affiliate'] = $this->encryption->decrypt( $po['affiliate'] );
            }
            if( isset( $po['customerSK'] ) && !empty( $po['customerSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['customerSK'] )));
                $_viewHolder[$idx]['customer'] = $this->encryption->decrypt( $po['customer'] );
            }
        }
        $view = $_viewHolder;

        die(
			json_encode(
				array(
					'success' => true
                    ,'view' => $view
				)
			)
        );
    }

    public function getFilters(){
        $params = getData();
        switch($params['search_by']){
            case CUSTOMER:
                $return = $this->model->getCustomers($params['affiliate'], $params['query']);
                $return = decryptCustomer( $return );
                break;
            case ITEM:
                if(isset($params['sonumber']) && $params['sonumber'] != null && $params['sonumber'] != ''){
                    $return = $this->model->getItemsByInvoice($params['affiliate'], $params['query'], $params['sonumber']);
                }else{
                    $return = $this->model->getItems($params['affiliate'], $params['query']);
                }

                $return = decryptItem( $return );
                
                break;
            default:
                $customers = $this->model->getCustomers($params['affiliate'], $params['query']);
                $customers = decryptCustomer( $customers );
                $items = $this->model->getItems($params['affiliate'], $params['query']);
                $items = decryptItem( $items );
                $return  = array_merge($customers, $items);
                break;
        }
        
        die(
			json_encode(
				array(
					'success' => true
                    ,'view' => $return
				)
			)
        );
    }

    public function getSOList(){
        $params = getData();
        $return = $this->model->getSOList($params['affiliate'], $params['customer']);
        die(
			json_encode(
				array(
					'success' => true
                    ,'view' => $return
				)
			)
        );
    }

    public function getLedger(){
        $params = getData();
        $return = $this->model->getLedger($params);
        die(
			json_encode(
				array(
					'success' => true
                    ,'view' => $return
				)
			)
        );
    }

    public function printExcel($type){
        
        $params = getData();
        $params['export'] = true;
        $csvarray = [];
        $view = ($type == 'Balance')? $this->model->getReleasable($params): $this->model->getLedger($params);

        $_viewHolder = $view;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['itemSK'] ) && !empty( $po['itemSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['itemSK'] )));
                $_viewHolder[$idx]['item'] = $this->encryption->decrypt( $po['item'] );
            }
            if( isset( $po['customerSK'] ) && !empty( $po['customerSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['customerSK'] )));
                $_viewHolder[$idx]['customer'] = $this->encryption->decrypt( $po['customer'] );
            }
            if( isset( $po['affiliateSK'] ) && !empty( $po['affiliateSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affiliateSK'] )));
                $_viewHolder[$idx]['affiliate'] = $this->encryption->decrypt( $po['affiliate'] );
            }
        }
        $view = $_viewHolder;
        
        if($type == 'Balance'){
            $csvarray = [
                [ 'title',  $params['title']],
                [ 'Affiliate',  $params['pdf_idAffiliate']],
                [ 'Status',  $params['pdf_status']],
                [ 'As of',  $params['pdf_dateto']],
                [ '' ],
                [  'Affiliate', 'Date' ,'SO No.' ,'Customer' ,'Item Name' ,'Unit' ,'Expected Qty' ,'Actual Qty' ,'Balance' ,'Status']
            ];
        }else{
            $csvarray = [
                [ 'title',  $params['title']],
                [ 'Affiliate',  $params['pdf_idAffiliate']],
                [ 'Customer',  $params['pdf_customer']],
                [ 'SO Number',  $params['pdf_sonumber']],
                [ 'Item',  $params['pdf_itemname']],
                [ 'Date',  "{$params['pdf_datefrom']} to {$params['pdf_dateto']}"],
                ['Affiliate', 'Date' ,'Reference','Expected Qty','Received Qty','Balance' ]
            ];
        }

        foreach( $view as $value ){
			$csvarray[] = array(
				'col1' => $value[ 'affiliate' ]
				,'col2' => $value[ 'date' ]
                ,'col3' => $value[ 'sonumber' ]
                ,'col4' => $value[ 'customer' ]
                ,'col5' => $value[ 'item' ]
                ,'col6' => $value[ 'unit' ]
                ,'col7' => $value[ 'expectedqty' ]
                ,'col8' => $value[ 'actualqty' ]
                ,'col9' => $value[ 'balance' ]
                ,'col10' => $value[ 'status' ]
			);
        }
        
        setLogs(
            array(
                'actionLogDescription' => $this->USERNAME . 'export excel'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            ,'idEu'                => $this->USERID
                ,'moduleID'            => 27
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

    public function printPDF($type){
        $params = getData();
        $params['mode'] = $type;
        if($type == 'Balance'){
            $col = [
                $this->pdfColFormat('Affiliate', 'affiliate', '10%'),
                $this->pdfColFormat('Date', 'date', '11%'),
                $this->pdfColFormat('SO Number', 'sonumber', '10%'),
                $this->pdfColFormat('Customer', 'customer', '13%'),
                $this->pdfColFormat('Item Name', 'item', '13%'),
                $this->pdfColFormat('Unit', 'unit', '7%'),
                $this->pdfColFormat('Expected Qty', 'expectedqty', '10%', 'numbercolumn'),
                $this->pdfColFormat('Actual Qty', 'actualqty', '10%', 'numbercolumn'),
                $this->pdfColFormat('Balance', 'balance', '10%', 'numbercolumn'),
                $this->pdfColFormat('Status', 'status', '8%')
            ];
            $header_fields = [
                [
                    $this->pdfHeaderFormat('Affiliate', $params['pdf_idAffiliate']),
                    $this->pdfHeaderFormat('Status', $params['pdf_status']),
                    $this->pdfHeaderFormat('As of', $params['pdf_dateto'])
                ]
            ];
        } else {
            $col = [
                $this->pdfColFormat('Date', 'date', '25%'),
                $this->pdfColFormat('Reference', 'reference', '25%'),
                $this->pdfColFormat('Expected Qty', 'expectedqty', '15%', 'numbercolumn'),
                $this->pdfColFormat('Received Qty', 'deliveredqty', '15%', 'numbercolumn'),
                $this->pdfColFormat('Date', 'balance', '20%', 'numbercolumn')
            ];
            $header_fields = [
                [
                    $this->pdfHeaderFormat('Affiliate', $params['pdf_idAffiliate']),
                    $this->pdfHeaderFormat('Customer', $params['pdf_customer']),
                    $this->pdfHeaderFormat('SO Number', $params['pdf_sonumber']),
                    $this->pdfHeaderFormat('Item', $params['pdf_itemname']),
                    $this->pdfHeaderFormat('Date',  "{$params['pdf_datefrom']} to {$params['pdf_dateto']}"),
                ]
            ];
        }
        $view = ($type == 'Balance')? $this->model->getReleasable($params): $this->model->getLedger($params);
        
        $_viewHolder = $view;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['itemSK'] ) && !empty( $po['itemSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['itemSK'] )));
                $_viewHolder[$idx]['item'] = $this->encryption->decrypt( $po['item'] );
            }
            if( isset( $po['customerSK'] ) && !empty( $po['customerSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['customerSK'] )));
                $_viewHolder[$idx]['customer'] = $this->encryption->decrypt( $po['customer'] );
            }
            if( isset( $po['affiliateSK'] ) && !empty( $po['affiliateSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affiliateSK'] )));
                $_viewHolder[$idx]['affiliate'] = $this->encryption->decrypt( $po['affiliate'] );
            }
        }
        $view = $_viewHolder;

        setLogs(
            array(
                'actionLogDescription' => $this->USERNAME . 'export pdf'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            ,'idEu'                => $this->USERID
                ,'moduleID'            => 27
                ,'time'                => date('H:i:s A')
            )
        );
        generateTcpdf(
			array(
				'file_name' => $params['title']
				,'folder_name' => 'inventory'
				,'records' => $view
				,'header' => $col
				,'orientation' => ($type == 'Balance' ) ? 'L' : 'P'
				,'header_fields' => $header_fields
			)
		);
    }

    public function download( $title, $folder ){
		force_download(
			array(
				'title'      => $title
				,'directory' => $folder
			)
		);
    }
    
    private function pdfColFormat($header, $dataIndex, $width = false, $type=false){
        $return = [
            'header' => $header,
            'dataIndex' => $dataIndex
        ];
        if($width != false) $return['width'] = $width;
        if($type != false) $return['type'] = $type;
        return $return;
        
    }

    private function pdfHeaderFormat($label, $value){
        return[
            'label' => $label,
            'value' => $value
        ];
    }
}