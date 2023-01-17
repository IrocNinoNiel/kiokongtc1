<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Mark Reynor D. Magriña
 * Module: Payable Schedule
 * Date: Feb 18, 2020
 * Finished: 
 * Description: 
 * DB Tables: 
 * For Password Hashing, please use password_hash( https://www.php.net/manual/en/function.password-hash.php )
 * */
class Payableschedule extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->library('encryption');
		setHeader('generalreports/Payableschedule_model');
	}
	
	public function getHistory(){
		$rawData = getData();
		$view = $this->model->recordDetails( $rawData );

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
                $_viewHolder[$idx]['supplier'] = $this->encryption->decrypt( $record['supplier'] );
            }
        }
        $view = $_viewHolder;
		
		setLogs(
            array(
                'actionLogDescription' => 'Generates payable schedule report'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            ,'idEu'                => $this->USERID
                ,'moduleID'            => 50
                ,'time'                => date("H:i:s A")
            )
		);
		

		die( json_encode( array( 'success' => true ,'view' => $view ,'total' => Count($view) ) ) );	
		// die( json_encode( array( 'success' => true ,'view' => $list['view'] ,'total' => $list['count'] ) ) );	
	}
	// function getSupplier(){
		// $rawData = getData();
		// $record = $this->model->getSupplier($rawData);
		// die( json_encode( array('success'=> true, 'view'=>$record ) ) );
	// }
	
	
	
	public function printPDF(){
		$data = getData();
		$data['pdf'] = true;
		$list = $this->model->recordDetails( $data );

		$_viewHolder = $list;
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
                $_viewHolder[$idx]['supplier'] = $this->encryption->decrypt( $record['supplier'] );
            }
        }
        $list = $_viewHolder;
		
		$params1 = array(
			array(   
				'header' 		=> 'Affiliate'	
				,'dataIndex'	=> 'affiliateName'				
				,'width' 		=> '14%'
			)
			,array(  
				'header' 		=> 'Transaction Date'	
				,'dataIndex' 	=> 'date'		
				,'type'         => 'datecolumn'
                ,'format'       => 'm/d/Y'	
				,'width' 		=> '14%'
			)
			,array(   
				'header' 		=> 'Due Date' 			
				,'dataIndex' 	=> 'duedate'	
				,'type'         => 'datecolumn'
                ,'format'       => 'm/d/Y'		
				,'width' 		=> '14%'
			)
			,array(
				'header' 		=> 'Reference'		
				,'dataIndex' 	=> 'reference'		
				,'width' 		=> '14%'
			)
			,array(
				'header' 		=> 'Supplier'		
				,'dataIndex' 	=> 'supplier'		
				,'width' 		=> '14%'
			)
			,array(
				'header' 		=> 'Amount'		
				,'dataIndex' 	=> 'amount'		
				,'width' 		=> '14%'
				,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
                ,'hasTotal'     => true
			)
			,array(
				'header' 		=> 'Balance'		
				,'dataIndex' 	=> 'balance'		
				,'width' 		=> '14%'
				,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
                ,'hasTotal'     => true
			)
		);
		
		$header_fields = array(
			array(
				array(
					'label' => 'Affiliate'
					,'value' => $data['pdf_idAffiliate']
				)
				,array(
					'label' => 'Supplier'
					,'value' => $data['pdf_supplierCmb']
				)
			)
			,array(
				array(
					'label' => 'Date'
					,'value' => $data['pdf_sdate']
				)
				,array(
					'label' => 'To'
					,'value' => $data['pdf_edate']
				)
			)
		);
		
		$html = 'Total';

		setLogs(
            array(
                'actionLogDescription' => 'Exported the generated payable schedule report (PDF)'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            ,'idEu'                => $this->USERID
                ,'moduleID'            => 50
                ,'time'                => date("H:i:s A")
            )
        );
		
		generateTcpdf(
			array(
				'file_name' => $data['title']
				,'folder_name' => 'generalreports'
				,'records' => $list
				,'header' => $params1
				,'orientation' => 'P'
				,'header_fields' => $header_fields
			)
		);
	}
	
	public function viewPDF( $title ){
		viewPDF(
			array(
				'file_name' => $title
				,'folder_name' => 'generalreports'
			)
		);
	}

	function printExcel(){
        $params = getData();
		$data = $this->model->recordDetails( $params );

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
                $_viewHolder[$idx]['supplier'] = $this->encryption->decrypt( $record['supplier'] );
            }
        }
        $data = $_viewHolder;
        
        $csvarray = array();

        $csvarray[] = array( 'title' => $params['title'] );
        $csvarray[] = array( 'space' => '' );

        $csvarray[] = array( 'Supplier Name', $params['pdf_supplierCmb'] );
        $csvarray[] = array( 'Date From', $params['pdf_sdate'] );
        $csvarray[] = array( 'Date To', $params['pdf_edate'] );
        $csvarray[] = array( 'space' => '' );

        $csvarray[] = array(
            'Affiliate'
            ,'Transaction Date '
			,'Due Date'
			,'Reference'
            ,'Supplier'
            ,'Amount'
            ,'Balance'
        );

        foreach( $data as $d ){
            $csvarray[] = array(
                $d['affiliateName']
                ,$d['date']
                ,$d['dueDate']
                ,$d['reference']
                ,$d['supplier']
                ,number_format( $d['amount'], 2 )
                ,number_format( $d['balance'], 2 )
            );
        }

        $csvarray[] = array(
            ''
            ,'' 
            ,''
            ,''
            ,''
            ,number_format( array_sum( array_column( $data, 'amount') ), 2 ) 
            ,number_format( array_sum( array_column( $data, 'balance') ), 2 ) 
        );

        setLogs(
            array(
                'actionLogDescription' => 'Exported the generated payable schedule report (Excel)'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            ,'idEu'                => $this->USERID
                ,'moduleID'            => 50
                ,'time'                => date("H:i:s A")
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
