<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Salesreturnsummary extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->load->library( 'encryption' );
        setHeader( 'inventory/Salesreturnsummary_model' );
    }

    public function getCostCenter(){
        $params = getData();
        $view  = $this->model->getCostCenter($params);
        array_unshift($view, ['id'=>0,'name'=>'All']);
        die(
			json_encode(
				array(
					'success' => true
                    ,'view' => $view
				)
			)
        );
    }

    public function getLocation(){
        $view  = $this->model->getLocation();
        array_unshift($view, ['id'=>0,'name'=>'All']);
        die(
			json_encode(
				array(
					'success' => true
                    ,'view' => $view
				)
			)
        );
    }

    public function getClassification(){
        $view  = $this->model->getClassification();
        array_unshift($view, ['id'=>0,'name'=>'All']);
        die(
			json_encode(
				array(
					'success' => true
                    ,'view' => $view
				)
			)
        );
    }

    public function getItem(){
        $params = getData();
        $view = $this->model->getItem($params);
        $view = decryptItem( $view );
        
        array_unshift($view, ['id'=>0,'name'=>'All']);
        die(
			json_encode(
				array(
					'success' => true
                    ,'view' => $view
				)
			)
        );
    }

    public function getReference(){
        $params = getData();
        $view = $this->model->getReferences($params);
        array_unshift($view, ['id'=>0,'name'=>'All']);
        die(
			json_encode(
				array(
					'success' => true
                    ,'view' => $view
				)
			)
        );
    }

    public function getSalesReturn(){
        $params = getData();
        $view = $this->model->retriveAll($params);

        $_viewHolder = $view;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['itemSK'] ) && !empty( $po['itemSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['itemSK'] )));
                $_viewHolder[$idx]['item'] = $this->encryption->decrypt( $po['item'] );
            }
            if( isset( $po['custSK'] ) && !empty( $po['custSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['custSK'] )));
                $_viewHolder[$idx]['customer'] = $this->encryption->decrypt( $po['customer'] );
            }
            if( isset( $po['affSK'] ) && !empty( $po['affSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affSK'] )));
                $_viewHolder[$idx]['affiliate'] = $this->encryption->decrypt( $po['affiliate'] );
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

    public function printExcel(){
        $params = getData();
        $params['export'] = true;
        $csvarray = [];
        $view = $this->model->retriveAll($params);
        $total_return_amount = array_sum(array_column($view, 'amount'));

        $_viewHolder = $view;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['itemSK'] ) && !empty( $po['itemSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['itemSK'] )));
                $_viewHolder[$idx]['item'] = $this->encryption->decrypt( $po['item'] );
            }
            if( isset( $po['custSK'] ) && !empty( $po['custSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['custSK'] )));
                $_viewHolder[$idx]['customer'] = $this->encryption->decrypt( $po['customer'] );
            }
            if( isset( $po['affSK'] ) && !empty( $po['affSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affSK'] )));
                $_viewHolder[$idx]['affiliate'] = $this->encryption->decrypt( $po['affiliate'] );
            }
        }
        $view['view'] = $_viewHolder;

        $csvarray = [
            [ 'title',  $params['title']],
            [ 'Affiliate',  $params['pdf_idAffiliate']],
            [ 'Reference Number',  $params['pdf_reference']],
            [ 'Item',  $params['pdf_item']],
            [ 'Date From',  "{$params['pdf_datefrom']} {$params['timeFrom']}"],
            [ 'Date To',  "{$params['pdf_dateto']} {$params['timeto']}"],
        ];

        $csvarray[] = array( 'space' => '' );
        $csvarray[] = array(
			'col1'  => 'Affiliate'
			,'col2'  => 'Date'
            ,'col3' => 'Reference'
            ,'col4' => 'Customer'
            ,'col5' => 'Code'
            ,'col6' => 'Item'
            ,'col7' => 'Classification'
            ,'col8' => 'Cost'
            ,'col9' => 'Qty'
            ,'col10' => 'Amount'
        );

        foreach( $view['view'] as $value ){
			$csvarray[] = array(
				'col1' => $value[ 'affiliate' ]
				,'col2' => $value[ 'date' ]
                ,'col3' => $value[ 'reference' ]
                ,'col4' => $value[ 'customer' ]
                ,'col5' => $value[ 'code' ]
                ,'col6' => $value[ 'item' ]
                ,'col7' => $value[ 'class' ]
                ,'col8' => $value[ 'cost' ]
                ,'col9' => $value[ 'qty' ]
                ,'col10' => $value[ 'amount' ]
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
            ,''
            ,$total_return_amount
        );
        
        setLogs(
            array(
                'actionLogDescription' => $this->USERNAME . 'export excel'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            ,'idEu'                => $this->USERID
                ,'moduleID'            => 21
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

    public function printPDF(){
        $params = getData();
        $col = [
            $this->pdfColFormat('Affiliate', 'affiliate', '10%'),
            $this->pdfColFormat('Date', 'date', '10%'),
            $this->pdfColFormat('Reference', 'reference', '10%'),
            $this->pdfColFormat('Customer', 'customer', '15%'),
            $this->pdfColFormat('Code', 'code', '8%'),
            $this->pdfColFormat('Item', 'item', '10%'),
            $this->pdfColFormat('Classification', 'class', '11%'),
            $this->pdfColFormat('Cost', 'cost', '8%', 'numbercolumn'),
            $this->pdfColFormat('QTY', 'qty', '9%', 'numbercolumn'),
            $this->pdfColFormat('Amount', 'amount', '9%', 'numbercolumn')
        ];
        $header_fields = [
            [
                $this->pdfHeaderFormat('Reference Number', $params['pdf_reference']),
                $this->pdfHeaderFormat('Item',  $params['pdf_item']),
                $this->pdfHeaderFormat('Date From', "{$params['pdf_datefrom']} {$params['timeFrom']}"),
                $this->pdfHeaderFormat('Date To', "{$params['pdf_dateto']} {$params['timeto']}")
            ]
        ];
        $view = $this->model->retriveAll($params);
        $total_return_amount = array_sum(array_column($view, 'amount'));

        $_viewHolder = $view;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['itemSK'] ) && !empty( $po['itemSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['itemSK'] )));
                $_viewHolder[$idx]['item'] = $this->encryption->decrypt( $po['item'] );
            }
            if( isset( $po['custSK'] ) && !empty( $po['custSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['custSK'] )));
                $_viewHolder[$idx]['customer'] = $this->encryption->decrypt( $po['customer'] );
            }
            if( isset( $po['affSK'] ) && !empty( $po['affSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affSK'] )));
                $_viewHolder[$idx]['affiliate'] = $this->encryption->decrypt( $po['affiliate'] );
            }
        }
        $view = $_viewHolder;

        array_push($field,[
            'affiliate' => ''
            ,'date' => ''
            ,'reference' => ''
            ,'customer' => ''
            ,'code' => ''
            ,'item' => ''
            ,'class' => ''
            ,'cos' => ''
            ,'qty' => ''
            ,'amount' =>  $total_return_amount
        ]);
        setLogs(
            array(
                'actionLogDescription' => $this->USERNAME . 'export pdf'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            ,'idEu'                => $this->USERID
                ,'moduleID'            => 21
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