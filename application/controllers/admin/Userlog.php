<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Mark Reynor D. Magriña
 * Module: User Action Logs
 * Date: Feb 2, 2020
 * Finished: 
 * Description: 
 * DB Tables: 
 * For Password Hashing, please use password_hash( https://www.php.net/manual/en/function.password-hash.php )
 * */
class Userlog extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->library( 'encryption' );
		setHeader('admin/Userlog_model');
	}
	
	public function getHistory(){
		$data = getData();
        $list = $this->model->viewAll( $data );
		
		$_viewHolder = $list['view'];
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['employeeSK'] ) && !empty( $po['employeeSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['employeeSK'] )));
                $_viewHolder[$idx]['fullname'] = $this->encryption->decrypt( $po['fullname'] );
			}

			if( isset( $po['affiliateSK'] ) && !empty( $po['affiliateSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affiliateSK'] )));
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
            }
		}
        $list['view'] = $_viewHolder;
		
		die(json_encode(array(
			'success' => true
			,'view' => $list['view']
			,'total' => $list['count']
		)));	
	}
	
	public function getUsers(){
		$data = getData();
		$ret = $this->model->getUsers( $data );
		array_unshift( $ret ,array(  'name'	=> 'All' ,'euID'	=> 0 ) );
		die( json_encode( array( 'success' => true ,'view' => $ret ,'count' => count($ret) ) ) );
	}
	
	public function getAffiliate(){
		$rawData = getData();
		$record = $this->model->getAffiliate($rawData);		
		die( json_encode( array( 'success'=>true, 'view'=> $record ) )  );
	}
	
	public function printPDF(){
		$data = getData();
		$data['pdf'] = true;
		$list = $this->model->viewAll( $data );

		$_viewHolder = $list;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['employeeSK'] ) && !empty( $po['employeeSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['employeeSK'] )));
                $_viewHolder[$idx]['fullname'] = $this->encryption->decrypt( $po['fullname'] );
			}

			if( isset( $po['affiliateSK'] ) && !empty( $po['affiliateSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affiliateSK'] )));
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
            }
		}
		$list = $_viewHolder;
		
		
		$params1 = array(
			array(   
				'header' => 'Date'	
				,'dataIndex' => 'datelog'				
				,'width' => '8%'
			)
			,array(  
				'header' => 'Time'	
				,'dataIndex' => 'time'			
				,'width' => '8%'		
			)
			,array(  
				'header' => 'Location' 			
				,'dataIndex' => 'affiliateName'				
				,'width' => '10%'		
			)
			,array(   
				'header' => 'User Full Name' 			
				,'dataIndex' => 'fullname'			
				,'width' => '10%'
			)
			,array(   
				'header' => 'User Name'		
				,'dataIndex' => 'euName'		
				,'width' => '10%'
			)
			,array(
				'header' => 'User Type'		
				,'dataIndex' => 'euTypeName'		
				,'width' => '10%'
			)
			,array(
				'header' => 'Ref'		
				,'dataIndex' => 'code'		
				,'width' => '5%'
			)
			,array(
				'header' => 'Number'		
				,'dataIndex' => 'referenceNum'		
				,'width' => '8%'
			)
			,array(
				'header' => 'Description'		
				,'dataIndex' => 'actionLogDescription'		
				,'width' => '33%'
			)
		);
		
		$header_fields = array(
			array(
				array(
					'label' => 'Affiliate'
					,'value' => $data['rawidAffiliate']
				)
				,array(
					'label' => 'Select User'
					,'value' => $data['rawsearchBy']
				)
				,array(
					'label' => 'Date'
					,'value' => $data['rawsdate'] . ' <strong>To:</strong> ' . $data['rawedate']
				)
			)
		);
		
		$html = 'Total';
		
		generateTcpdf(
			array(
				'file_name' => $data['title']
				,'folder_name' => 'admin'
				,'records' => $list
				,'header' => $params1
				,'orientation' => 'L'
				,'header_fields' => $header_fields
			)
		);
	}
	
	public function viewPDF( $title ){
		viewPDF(
			array(
				'file_name' => $title
				,'folder_name' => 'admin'
			)
		);
	}
}
