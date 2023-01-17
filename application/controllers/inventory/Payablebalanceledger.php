<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Jayson Dagulo
 * Module: Payable Balances and Ledger
 * Date: Jan. 17, 2020
 * Finished: 
 * Description: This module allows authorized user to generate and print the payable balances and its ledger for every supplier.
 * */ 
class Payablebalanceledger extends CI_Controller {
    
    public function __construct(){
		parent::__construct();
		$this->load->library('encryption');
        setHeader( 'inventory/Payablebalanceledger_model' );
    }

    public function getBalances(){
        $params = getData();
		$view   = $this->model->getBalances( $params );
		
		 /**Custom decryption **/
		 $_viewHolder = $view;
		 foreach( $_viewHolder as $idx => $record ){
			 
			 /**Decrypting affiliate**/
			 if( isset( $record['affiliateSK'] ) && !empty( $record['affiliateSK'] ) ){
				 $this->encryption->initialize( array('key' => generateSKED( $record['affiliateSK'] ) ) );
				 $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $record['affiliateName'] );
			 }

			 /**Decrypting customer**/
			 if( isset( $record['supplierSK'] ) && !empty( $record['supplierSK'] ) ){
				 $this->encryption->initialize( array( 'key' => generateSKED( $record['supplierSK'] )));
				 $_viewHolder[$idx]['supplierName'] = $this->encryption->decrypt( $record['supplierName'] );
			 }
			 
			 /**Decrypting item**/
			 if( isset( $record['costCenterSK'] ) && !empty( $record['costCenterSK'] ) ){
				 $this->encryption->initialize( array( 'key' => generateSKED( $record['costCenterSK'] )));
				 $_viewHolder[$idx]['costCenterName'] = $this->encryption->decrypt( $record['costCenterName'] );
			 }
			 
		 }

		 $view = $_viewHolder;
        
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    public function getLedger(){
        $params = getData();
        $view   = $this->model->getLedger( $params );

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    public function printPDF( $type = 'Balance' ){
        $data = getData();
        if($type == 'Balance'){
			$list = $this->model->getBalances( $data );
			
			/**Custom decryption **/
			$_viewHolder = $list;
			foreach( $_viewHolder as $idx => $record ){
				
				/**Decrypting affiliate**/
				if( isset( $record['affiliateSK'] ) && !empty( $record['affiliateSK'] ) ){
					$this->encryption->initialize( array('key' => generateSKED( $record['affiliateSK'] ) ) );
					$_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $record['affiliateName'] );
				}
   
				/**Decrypting customer**/
				if( isset( $record['supplierSK'] ) && !empty( $record['supplierSK'] ) ){
					$this->encryption->initialize( array( 'key' => generateSKED( $record['supplierSK'] )));
					$_viewHolder[$idx]['supplierName'] = $this->encryption->decrypt( $record['supplierName'] );
				}
				
				/**Decrypting item**/
				if( isset( $record['costCenterSK'] ) && !empty( $record['costCenterSK'] ) ){
					$this->encryption->initialize( array( 'key' => generateSKED( $record['costCenterSK'] )));
					$_viewHolder[$idx]['costCenterName'] = $this->encryption->decrypt( $record['costCenterName'] );
				}
				
			}
   
			$list = $_viewHolder;

            $params1 = array(
				array(   
					'header'        => 'Affiliate'
					,'dataIndex'    => 'affiliateName'
					,'width'        => '20%'
				)
				,array(  
					'header'        => 'Supplier'
					,'dataIndex'    => 'supplierName'
					,'width'        => '50%'
				)
				,array(  
					'header'        => 'Charges'
					,'dataIndex'    => 'chargesAmt'
					,'width'        => '10%'
					,'type'         => 'numbercolumn'
					,'format'       => '0,000.00'
				)
				,array(  
					'header'        => 'Payments'
					,'dataIndex'    => 'paymentsAmt'
					,'width'        => '10%'
					,'type'         => 'numbercolumn'
					,'format'       => '0,000.00'
				)
				,array(  
					'header'        => 'Balance'
					,'dataIndex'    => 'balanceAmt'
					,'width'        => '10%'
					,'type'         => 'numbercolumn'
					,'format'       => '0,000.00'
					,'hasTotal'     => true
				)
			);
			
			$header_fields = array(
				array(
					array(
						'label'     => 'Affiliate'
						,'value'    => $data['pdf_idAffiliate']
					)
					,array(
						'label'     => 'Supplier'
						,'value'    => $data['pdf_idSupplier']
					)
					,array(
						'label'     => 'As Of'
						,'value'    => $data['pdf_date']
					)
				)
			);
        }
        else{
            $list = $this->model->getLedger( $data );
            $params1 = array(
				array(  
					'header'        => 'Date'
					,'dataIndex'    => 'date'
					,'width'        => '10%'
				)
				,array(  
					'header'        => 'Ref.'
					,'dataIndex'    => 'reference'
					,'width'        => '15%'
				)
				,array(  
					'header'        => 'Payment Type'
					,'dataIndex'    => 'paymentType'
					,'width'        => '15%'
				)
				,array(  
					'header'        => 'Particulars'
					,'dataIndex'    => 'particulars'
					,'width'        => '30%'
				)
				,array(  
					'header'        => 'Amount'
					,'dataIndex'    => 'amt'
					,'width'        => '10%'
					,'type'         => 'numbercolumn'
					,'format'       => '0,000.00'
				)
				,array(  
					'header'        => 'Payments'
					,'dataIndex'    => 'payments'
					,'width'        => '10%'
					,'type'         => 'numbercolumn'
					,'format'       => '0,000.00'
				)
				,array(  
					'header'        => 'Balance'
					,'dataIndex'    => 'balance'
					,'width'        => '10%'
					,'type'         => 'numbercolumn'
					,'format'       => '0,000.00'
					,'hasTotal'     => true
                    ,'isRunning'    => true
				)
			);
			
			$header_fields = array(
				array(
					array(
						'label'     => 'Affiliate'
						,'value'    => $data['pdf_idAffiliate']
					)
					,array(
						'label'     => 'Supplier'
						,'value'    => $data['pdf_idSupplier']
					)
					,array(
						'label'     => 'Date'
						,'value'    => $data['pdf_sdate'] . ' To ' . $data['pdf_edate']
					)
				)
			);
        }

        generateTcpdf(
			array(
				'file_name' => $data['title']
				,'folder_name' => 'inventory'
				,'records' => $list
				,'header' => $params1
				,'orientation' => 'P'
				,'header_fields' => $header_fields
			)
		);
    }

    public function printExcel( $type = 'Balance' ){
		$data = getData();
		$csvarray = array();
		
		$csvarray[] = array( 'title' => $data['title']);
		$csvarray[] = array( 'space' => '' );
		
		if($type == 'Balance'){
			$list = $this->model->getBalances( $data );
			/**Custom decryption **/
			$_viewHolder = $list;
			foreach( $_viewHolder as $idx => $record ){
				
				/**Decrypting affiliate**/
				if( isset( $record['affiliateSK'] ) && !empty( $record['affiliateSK'] ) ){
					$this->encryption->initialize( array('key' => generateSKED( $record['affiliateSK'] ) ) );
					$_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $record['affiliateName'] );
				}
   
				/**Decrypting customer**/
				if( isset( $record['supplierSK'] ) && !empty( $record['supplierSK'] ) ){
					$this->encryption->initialize( array( 'key' => generateSKED( $record['supplierSK'] )));
					$_viewHolder[$idx]['supplierName'] = $this->encryption->decrypt( $record['supplierName'] );
				}
				
				/**Decrypting item**/
				if( isset( $record['costCenterSK'] ) && !empty( $record['costCenterSK'] ) ){
					$this->encryption->initialize( array( 'key' => generateSKED( $record['costCenterSK'] )));
					$_viewHolder[$idx]['costCenterName'] = $this->encryption->decrypt( $record['costCenterName'] );
				}
				
			}
   
			$list = $_viewHolder;
            $csvarray[] = array( 'Affiliate', $data['pdf_idAffiliate'] );
            $csvarray[] = array( 'Supplier', $data['pdf_idSupplier'] );
            $csvarray[] = array( 'As of', $data['pdf_date'] );

            $csvarray[] = array(
                'Affiliate'
                ,'Cost Center'
                ,'Supplier'
                ,'Charges'
                ,'Payments'
                ,'Balance'
            );

            $totalCharges = 0;
            $totalPayments = 0;
            $totalBalance = 0;

            foreach( $list as $rs ){
                $csvarray[] = array(
                    $rs['affiliateName']
                    ,$rs['supplierName']
                    ,number_format( $rs['chargesAmt'], 2 )
                    ,number_format( $rs['paymentsAmt'], 2 )
                    ,number_format( $rs['balanceAmt'], 2 )
                );
                $totalCharges += $rs['chargesAmt'];
                $totalPayments += $rs['paymentsAmt'];
                $totalBalance += $rs['balanceAmt'];
            }
            $csvarray[] = array( 
                ''
                ,''
                ,number_format( $totalCharges, 2 )
                ,number_format( $totalPayments, 2 )
                ,number_format( $totalBalance, 2 )
            );
			
        }
        else{
            $list = $this->model->getLedger( $data );

            
            $csvarray[] = array( 'Affiliate', $data['pdf_idAffiliate'] );
            $csvarray[] = array( 'Supplier', $data['pdf_idSupplier'] );
            $csvarray[] = array( 'Date', $data['pdf_sdate'] . ' to ' . $data['pdf_edate'] );

            $csvarray[] = array(
                'Date'
                ,'Ref.'
                ,'Payment Type'
                ,'Particulars'
                ,'Amount'
                ,'Payments'
                ,'Balance'
            );

            $totalAmt = 0;
            $totalPayments = 0;
            $runningBal = 0;
            foreach( $list as $rs ){
                $csvarray[] = array(
                    $rs['date']
                    ,$rs['reference']
                    ,$rs['paymentType']
                    ,$rs['particulars']
                    ,number_format( $rs['amt'], 2 )
                    ,number_format( $rs['payments'], 2 )
                    ,number_format( $rs['balance'], 2 )
                );
                $totalAmt += $rs['amt'];
                $totalPayments += $rs['payments'];
                $runningBal = $rs['balance'];
            }
            
            $csvarray[] = array(
                ''
                ,''
                ,''
                ,''
                ,number_format( $totalAmt, 2 )
                ,number_format( $totalPayments, 2 )
                ,number_format( $runningBal, 2 )
            );
        }

        writeCsvFile(
			array(
				'csvarray' 	 => $csvarray
				,'title' 	 => $data['title']
				,'directory' => 'inventory'
			)
		);
    }
	
	public function viewPDF( $title ){
		viewPDF(
			array(
				'file_name' => $title['view']
				,'folder_name' => 'inventory'
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