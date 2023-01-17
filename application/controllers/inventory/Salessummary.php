<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Salessummary extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->load->library( 'encryption' );
        setHeader( 'inventory/Salessummary_model' );
    }

    public function getCustomer(){
        $params = getData();
        $view  = $this->model->getCustomer($params);
        $view = decryptCustomer( $view );
        
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
    
    public function getSalesReference(){
        $params = getData();
        $view = $this->model->getSalesReference($params);
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

    public function getSales(){
        $params = getData();
        $view = $this->model->getSales($params);

        $_viewHolder = $view;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['empSK'] ) && !empty( $po['empSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['empSK'] )));
                $_viewHolder[$idx]['salesman'] = $this->encryption->decrypt( $po['salesman'] );
            }
            if( isset( $po['custSK'] ) && !empty( $po['custSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['custSK'] )));
                $_viewHolder[$idx]['customer'] = $this->encryption->decrypt( $po['customer'] );
            }
            if( isset( $po['affSK'] ) && !empty( $po['affSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affSK'] )));
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
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

    public function getSalesReturn(){
        $params = getData();
        $view = $this->model->getSalesReturn($params);

        $_viewHolder = $view;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['empSK'] ) && !empty( $po['empSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['empSK'] )));
                $_viewHolder[$idx]['salesman'] = $this->encryption->decrypt( $po['salesman'] );
            }
            if( isset( $po['custSK'] ) && !empty( $po['custSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['custSK'] )));
                $_viewHolder[$idx]['customer'] = $this->encryption->decrypt( $po['customer'] );
            }
            if( isset( $po['affSK'] ) && !empty( $po['affSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affSK'] )));
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
            }
        }
        $view = $_viewHolder;

        setLogs(
            array(
                'actionLogDescription' => $this->USERFULLNAME . ' Generates Sales Summary Report'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            ,'idEu'                => $this->USERID
                ,'moduleID'            => 24
                ,'time'                => date('H:i:s A')
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

    public function printPDF(){
        $params = getData();
        $sales = $this->model->getSales($params);
        $return = $this->model->getSalesReturn($params);
        $total_net_sale = array_sum(array_column($sales, 'amount'));
        $total_return_amount = array_sum(array_column($return, 'amount'));
        $overall_net_sales = $total_net_sale - $total_return_amount;

        $_viewHolder = $sales;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['empSK'] ) && !empty( $po['empSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['empSK'] )));
                $_viewHolder[$idx]['salesman'] = $this->encryption->decrypt( $po['salesman'] );
            }
            if( isset( $po['custSK'] ) && !empty( $po['custSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['custSK'] )));
                $_viewHolder[$idx]['customer'] = $this->encryption->decrypt( $po['customer'] );
            }
            if( isset( $po['affSK'] ) && !empty( $po['affSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affSK'] )));
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
            }
        }
        $sales = $_viewHolder;

        $_viewHolder = $return;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['empSK'] ) && !empty( $po['empSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['empSK'] )));
                $_viewHolder[$idx]['salesman'] = $this->encryption->decrypt( $po['salesman'] );
            }
            if( isset( $po['custSK'] ) && !empty( $po['custSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['custSK'] )));
                $_viewHolder[$idx]['customer'] = $this->encryption->decrypt( $po['customer'] );
            }
            if( isset( $po['affSK'] ) && !empty( $po['affSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affSK'] )));
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
            }
        }
        $return = $_viewHolder;



        $header = [
            'title' => 'Sales Summary'
            ,'file_name' => 'Sales Summary'
            ,'folder_name' => 'pdf/inventory/'
            ,'addPage' => true
            ,'table_title' => 'Sales'
            ,'orientation' => 'L'
        ];

        $col_sales = [
            $this->pdfColFormat('Affiliate', 'affiliateName', '10%'),
            $this->pdfColFormat('Date', 'date', '10%'),
            $this->pdfColFormat('Reference', 'reference', '7%'),
            $this->pdfColFormat('Customer', 'customer', '14%'),
            $this->pdfColFormat('Sales Amount', 'sales', '8%', 'numbercolumn'),
            $this->pdfColFormat('Vat Amount', 'vat', '8%', 'numbercolumn'),
            $this->pdfColFormat('Sales with VAT', 'withvat', '8%', 'numbercolumn'),
            $this->pdfColFormat('Discount', 'discount', '8%', 'numbercolumn'),
            $this->pdfColFormat('Net Sales', 'amount', '8%', 'numbercolumn'),
            $this->pdfColFormat('Salesman/Cashier', 'salesman', '12%'),
            $this->pdfColFormat('VAT Type', 'vattype', '7%')
        ];
        // add total in sales
        
        array_push($sales, [
            'affiliateName' => ''
            ,'date' => ''
            ,'reference' => ''
            ,'customer' => ''
            ,'sales' => array_sum(array_column($sales, 'sales'))
            ,'vat' => array_sum(array_column($sales, 'vat'))
            ,'withvat' => array_sum(array_column($sales, 'withvat'))
            ,'discount' => array_sum(array_column($sales, 'discount'))
            ,'amount' => $total_net_sale
            ,'status' => ''
            ,'salesman' => ''
            ,'vattype' => ''
        ]);

        $col_return = [
            $this->pdfColFormat('Affiliate', 'affiliateName', '10%'),
            $this->pdfColFormat('Date', 'date', '15%'),
            $this->pdfColFormat('Sales Reference', 'salereference', '10%'),
            $this->pdfColFormat('Sales Return Reference', 'salereturnreference', '10%'),
            $this->pdfColFormat('Customer', 'customer', '20%'),
            $this->pdfColFormat('Amount', 'amount', '15%'),
            $this->pdfColFormat('Salesman', 'salesman', '20%')
        ];
        // add total in sales return
        
        array_push($return, [
            'affiliateName' => ''
            ,'date' => ''
            ,'salereference' => ''
            ,'salereturnreference' => ''
            ,'customer' => ''
            ,'amount' =>  $total_return_amount
            ,'salesman' => ''
        ]);

        $col_overall_total = [
            'totalNetSales' => number_format($overall_net_sales,2,'.',','),
        ];

        $head = generate_table_as_string($header, $col_sales , $sales);
        $header['table_title'] = 'Sales Return';
        $head .= generate_table_as_string($header, $col_return , $return);
        $header['table_hidden'] = TRUE;
        setLogs(
            array(
                'actionLogDescription' => $this->USERFULLNAME . ' Exported the generated Sales Summary Report (PDF)'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            ,'idEu'                => $this->USERID
                ,'moduleID'            => 24
                ,'time'                => date('H:i:s A')
            )
        );
		generate_table( $header, $col_overall_total, [], $head);

    }

    // function printExcel(){
        // $params = getData();
        // $sales = $this->model->getSales($params);
        // $return = $this->model->getSalesReturn($params);
        // $total_net_sale = array_sum(array_column($sales, 'amount'));
        // $total_return_amount = array_sum(array_column($return, 'amount'));
        // $overall_net_sales = $total_net_sale - $total_return_amount;

        // $_viewHolder = $sales;
        // foreach( $_viewHolder as $idx => $po ){
        //     if( isset( $po['empSK'] ) && !empty( $po['empSK'] ) ){
        //         $this->encryption->initialize( array( 'key' => generateSKED( $po['empSK'] )));
        //         $_viewHolder[$idx]['salesman'] = $this->encryption->decrypt( $po['salesman'] );
        //     }
        //     if( isset( $po['custSK'] ) && !empty( $po['custSK'] ) ){
        //         $this->encryption->initialize( array( 'key' => generateSKED( $po['custSK'] )));
        //         $_viewHolder[$idx]['customer'] = $this->encryption->decrypt( $po['customer'] );
        //     }
        // }
        // $sales = $_viewHolder;

        // $_viewHolder = $return;
        // foreach( $_viewHolder as $idx => $po ){
        //     if( isset( $po['empSK'] ) && !empty( $po['empSK'] ) ){
        //         $this->encryption->initialize( array( 'key' => generateSKED( $po['empSK'] )));
        //         $_viewHolder[$idx]['salesman'] = $this->encryption->decrypt( $po['salesman'] );
        //     }
        //     if( isset( $po['custSK'] ) && !empty( $po['custSK'] ) ){
        //         $this->encryption->initialize( array( 'key' => generateSKED( $po['custSK'] )));
        //         $_viewHolder[$idx]['customer'] = $this->encryption->decrypt( $po['customer'] );
        //     }
        // }
        // $return = $_viewHolder;


    //     array_push($sales,[
    //         'date' => ''
    //         ,'reference' => ''
    //         ,'customer' => ''
    //         ,'sales' => array_sum(array_column($sales, 'sales'))
    //         ,'vat' => array_sum(array_column($sales, 'vat'))
    //         ,'withvat' => array_sum(array_column($sales, 'withvat'))
    //         ,'discount' => array_sum(array_column($sales, 'discount'))
    //         ,'amount' => $total_net_sale
    //         ,'salesman' => ''
    //         ,'vattype' => ''
    //     ]);


    //     array_push($return, [
    //         'date' => ''
    //         ,'salereturnreference' => ''
    //         ,'salereference' => ''
    //         ,'customer' => ''
    //         ,'amount' =>  $total_return_amount
    //         ,'salesman' => ''
    //     ]);


    //     $csvarray = [
    //         [ 'title',  $params['title']],
    //         [ 'Customer',  $params['pdf_customer']],
    //         [ 'Payment Method',  $params['pdf_payment']],
    //         [ 'VAT Type',  $params['pdf_vat']],
    //         [ 'Sales Reference',  $params['pdf_reference']],
    //         [ 'Date',  "{$params['pdf_datefrom']} {$params['timeFrom']} to {$params['pdf_dateto']} {$params['timeto']}"],
    //         ['Sales: '],
    //         [ 'Date','Reference','Customer','Sales Amount', 'VAT Amount', 'Sales with VAT', 'Discount', 'Net Sales', ' Salesman/Cashier', 'VAT Type']
    //     ];

        
    //     $sales_fields = (array_map(function($data){ 
    //         return array_values(array_map(function($field){
    //             return is_numeric($field)? number_format($field, 2, '.', ','): $field;
    //         },$data));
    //     }, $sales));

    //     $csvarray = array_merge($csvarray, $sales_fields);
       
    //     array_push($csvarray, ['Sales Return: ']);
    //     array_push($csvarray, [ 'Date', 'Sales Return Reference', 'Sales Reference', 'Customer Name', ' Amount', 'Salesman']);
    //     $return_fields = (array_map(function($data){ 
    //         return array_values(array_map(function($field){
    //             return is_numeric($field)? number_format($field, 2, '.', ','): $field;
    //         },$data));
    //     }, $return));
    //     $csvarray = array_merge($csvarray, $return_fields);
    //     setLogs(
    //         array(
    //             'actionLogDescription' => $this->USERFULLNAME . ' Exported the generated Sales Summary Report (Excel)'
    //             ,'idEu'                => $this->USERID
    //             ,'moduleID'            => 24
    //             ,'time'                => date('H:i:s A')
    //         )
    //     );
    //     writeCsvFile(
    //         array(
    //             'csvarray' 	 => $csvarray
    //             ,'title' 	 => $params['title']
    //             ,'directory' => 'inventory'
    //         )
    //     );
    // }

    // function download($title){
	// 	force_download(
	// 		array(
	// 			'title' => $title
	// 			,'directory' => 'inventory'
	// 		)
	// 	);
	// }	

    private function pdfColFormat($header, $dataIndex, $width = false, $type=false){
        $return = [
            'header' => $header,
            'data_index' => $dataIndex
        ];
        if($width != false) $return['width'] = $width;
        if($type != false) $return['type'] = $type;
        return $return;
        
    }

    function printExcel (){
		$params = getData();
        $sales = $this->model->getSales($params);
        $return = $this->model->getSalesReturn($params);
        $total_net_sale = array_sum(array_column($sales, 'amount'));
        $total_return_amount = array_sum(array_column($return, 'amount'));
        $overall_net_sales = $total_net_sale - $total_return_amount;

        $_viewHolder = $sales;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['empSK'] ) && !empty( $po['empSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['empSK'] )));
                $_viewHolder[$idx]['salesman'] = $this->encryption->decrypt( $po['salesman'] );
            }
            if( isset( $po['custSK'] ) && !empty( $po['custSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['custSK'] )));
                $_viewHolder[$idx]['customer'] = $this->encryption->decrypt( $po['customer'] );
            }
            if( isset( $po['affSK'] ) && !empty( $po['affSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affSK'] )));
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
            }
        }
        $sales['view'] = $_viewHolder;

        $_viewHolder = $return;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['empSK'] ) && !empty( $po['empSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['empSK'] )));
                $_viewHolder[$idx]['salesman'] = $this->encryption->decrypt( $po['salesman'] );
            }
            if( isset( $po['custSK'] ) && !empty( $po['custSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['custSK'] )));
                $_viewHolder[$idx]['customer'] = $this->encryption->decrypt( $po['customer'] );
            }
            if( isset( $po['affSK'] ) && !empty( $po['affSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affSK'] )));
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
            }
        }
        $return['view'] = $_viewHolder;
    
        $csvarray[] = array( 'title' => $params['title'] );
        $csvarray[] = array( 'space' => '' );

        $csvarray[] = array( 'Customer', $params['pdf_customer'] );
        $csvarray[] = array( 'Payment Method', $params['pdf_payment'] );
        $csvarray[] = array( 'VAT Type', $params['pdf_vat'] );
        $csvarray[] = array( 'Sales Reference', $params['pdf_reference'] );
        $csvarray[] = array( 'Date', $params['pdf_datefrom'] . ' ' . date( 'H:i A', strtotime($params['timeFrom']) ) . ' to ' . $params['pdf_dateto'] . ' ' . date( 'H:i A', strtotime( $params['timeto'] ) ) );
        $csvarray[] = array( 'space' => '' );

        $csvarray[] = array( 'title' => 'Sales' );
		$csvarray[] = array(
			'col1'  => 'Affiliate'
			,'col2'  => 'Date'
            ,'col3' => 'Reference'
            ,'col4' => 'Customer'
            ,'col5' => 'Sales Amount'
            ,'col6' => 'VAT Amount'
            ,'col7' => 'Sales with VAT'
            ,'col8' => 'Discount'
            ,'col9' => 'Net Sales'
            ,'col10' => 'Salesman'
            ,'col11' => 'VAT Type'
        );

		foreach( $sales['view'] as $value ){
			$csvarray[] = array(
				'col1' => $value[ 'affiliateName' ]
				,'col2' => $value[ 'date' ]
                ,'col3' => $value[ 'reference' ]
                ,'col4' => $value[ 'customer' ]
                ,'col5' => $value[ 'sales' ]
                ,'col6' => $value[ 'vat' ]
                ,'col7' => $value[ 'withvat' ]
                ,'col8' => $value[ 'discount' ]
                ,'col9' => $value[ 'amount' ]
                ,'col10' => $value[ 'salesman' ]
                ,'col11' => $value[ 'vattype' ]
			);
        }

        $csvarray[] = array(
            ''
            ,''
            ,''
            ,''
            ,array_sum(array_column($sales, 'sales'))
            ,array_sum(array_column($sales, 'vat'))
            ,array_sum(array_column($sales, 'withvat'))
            ,array_sum(array_column($sales, 'discount'))
            ,$total_net_sale
            ,''
            ,''
        );
        
        $csvarray[] = array( 'space' => '' );
        $csvarray[] = array( 'title' => 'Sales Return' );
        $csvarray[] = array(
			'col1'  => 'Affiliate'
			,'col2'  => 'Date'
            ,'col3' => 'Sales Return Reference'
            ,'col4' => 'Sales Reference'
            ,'col5' => 'Customer'
            ,'col6' => 'Amount'
            ,'col7' => 'Salesman'
        );

        foreach( $return['view'] as $value ){
			$csvarray[] = array(
				'col1' => $value[ 'affiliateName' ]
				,'col2' => $value[ 'date' ]
                ,'col3' => $value[ 'salereturnreference' ]
                ,'col4' => $value[ 'salereference' ]
                ,'col5' => $value[ 'customer' ]
                ,'col6' => $value[ 'amount' ]
                ,'col7' => $value[ 'salesman' ]
			);
        }
        
        $csvarray[] = array(
            ''
            ,''
            ,''
            ,''
            ,''
            ,$total_return_amount
            ,''
        );
        
		$params['description'] = '' .$params['pageTitle']. ": " .$this->USERNAME. ' printed an Excel report'  ;
		$params['iduser'] = $this->USERID;
		$params['usertype'] = $this->USERTYPEID;
		$params['printExcel'] = true;	
        $params['ident'] = null;

		writeCsvFile(
			array(
				'csvarray' 	 => $csvarray
				,'title' 	 => $params['title'].''
				,'directory' => 'inventory'
			)
		);
		
    }
    
    function download($title){
		force_download(
			array(
				'title' => $title
				,'directory' => 'inventory'
			)
		);
    }

}