<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Mark Reynor D. Magriña
 * Module: Payable Transaction
 * Date: Feb 18, 2020
 * Finished: 
 * Description: 
 * DB Tables: 
 * For Password Hashing, please use password_hash( https://www.php.net/manual/en/function.password-hash.php )
 * */
class Payabletransaction extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->library( 'encryption' );
		setHeader('inventory/Payabletransaction_model');
	}
	
	public function getHistory(){
		$rawData = getData();
		$record = $this->model->recordDetails( $rawData );

		$_viewHolder = $record;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['affiliateSK'] ) && !empty( $po['affiliateSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affiliateSK'] )));
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
			}

			if( isset( $po['supplierSK'] ) && !empty( $po['supplierSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['supplierSK'] )));
                $_viewHolder[$idx]['supplier'] = $this->encryption->decrypt( $po['supplier'] );
            }
        }
		$record = $_viewHolder;
		
		die( json_encode( array( 'success' => true ,'view' => $record ,'total' => Count($record) ) ) );	
		// die( json_encode( array( 'success' => true ,'view' => $list['view'] ,'total' => $list['count'] ) ) );	
	}
	
	function printPDF(){
		$params = getData();
		$data = $this->model->recordDetails( $params );

		$_viewHolder = $data;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['affiliateSK'] ) && !empty( $po['affiliateSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affiliateSK'] )));
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
			}

			if( isset( $po['supplierSK'] ) && !empty( $po['supplierSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['supplierSK'] )));
                $_viewHolder[$idx]['supplier'] = $this->encryption->decrypt( $po['supplier'] );
            }
        }
		$data = $_viewHolder;

		$col = array(
			array(   
				'header'        => 'Affiliate'
				,'dataIndex'    => 'affiliateName'
				,'width'        => '20%'
			)
			,array(  
				'header'        => 'Transaction Date'
				,'dataIndex'    => 'date'
				,'type'         => 'datecolumn'
				,'format'       => 'm/d/Y'
				,'width'        => '15%'
			)
			,array(  
				'header'        => 'Due Date'
				,'dataIndex'    => 'duedate'
				,'type'         => 'datecolumn'
				,'format'       => 'm/d/Y'
				,'width'        => '10%'
			)
			,array(  
				'header'        => 'Reference'
				,'dataIndex'    => 'reference'
				,'width'        => '10%'
			)
			,array(  
				'header'        => 'Supplier'
				,'dataIndex'    => 'supplier'
				,'width'        => '25%'
			)
			,array(  
				'header'        => 'Amount'
				,'dataIndex'    => 'amount'
				,'type'         => 'numbercolumn'
				,'format'       => '0,000.00'
				,'width'        => '10%'
			)
			,array(  
				'header'        => 'Balance'
				,'dataIndex'    => 'balance'
				,'width'        => '10%'
				,'type'         => 'numbercolumn'
				,'format'       => '0,000.00'
			)
		);
		
		$header_fields = array(
			array(
				array(
					'label'     => 'Affiliate'
					,'value'    => $params['pdf_idAffiliate']
				)
				,array(
					'label'     => 'Customer'
					,'value'    => $params['pdf_supplierCmb']
				)
				,array(
					'label'     => 'Date'
					,'value'    => $params['pdf_sdate'] . ' to ' . $params['pdf_edate'] 
				)
			)
		);

		setLogs(
			array(
			   'actionLogDescription' => 'Exported the generated Payable Transactions Report (PDF).'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
			,'idEu'                => $this->USERID
			   ,'moduleID'            => $params['idModule'] 
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
		$data = $this->model->recordDetails( $params );

		$_viewHolder = $data;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['affiliateSK'] ) && !empty( $po['affiliateSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affiliateSK'] )));
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
			}

			if( isset( $po['supplierSK'] ) && !empty( $po['supplierSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['supplierSK'] )));
                $_viewHolder[$idx]['supplier'] = $this->encryption->decrypt( $po['supplier'] );
            }
        }
		$data = $_viewHolder;
		
		$csvarray = array();

		$csvarray[] = array( 'title' => $params['title'] );
		$csvarray[] = array( 'space' => '' );

		$csvarray[] = array( 'Affiliate', $params['pdf_idAffiliate'] );
		$csvarray[] = array( 'Customer', $params['pdf_supplierCmb'] );
		$csvarray[] = array( 'Date', $params['pdf_sdate'] . ' to ' . $params['pdf_edate'] );
		$csvarray[] = array( 'space' => '' );

		$csvarray[] = array(
			'Affiliate'
			,'Transaction Date'
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
				,$d['duedate']
				,$d['reference']
				,$d['supplier']
				,number_format( $d['amount'], 2 )
				,number_format( $d['balance'], 2 )
			);
		}

		setLogs(
			array(
			   'actionLogDescription' => 'Exported the generated Payable Transactions Report (Excel).'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
			,'idEu'                => $this->USERID
			   ,'moduleID'            => $params['idModule']
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
