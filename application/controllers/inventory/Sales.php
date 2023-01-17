<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Mark Reynor D. Magriña
 * Module: Sales Module
 * Date: Dec 12, 2019
 * Finished: 
 * Description: 
 * DB Tables: 
 * For Password Hashing, please use password_hash( https://www.php.net/manual/en/function.password-hash.php )
 * */
class Sales extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library( 'encryption' );
        setHeader( 'inventory/Sales_model' );
        $this->load->model('standards/Standards2_model', 'standard2');
	}

	public function getCustomer(){
		$params	= getData();
		$view	= $this->model->getCustomer( $params );
		$view = decryptCustomer( $view );
		
		die(
			json_encode(
				array(
					'success'	=> true
					,'view'		=> $view
				)
			)
		);
	}
	
	public function get_drivers()
	{
		$view	= $this->model->get_drivers();
		$view = decryptUserData( $view );

		die(
			json_encode(
				array(
					'success'	=> true
					,'view'		=> $view
				)
			)
		);
	}

	public function soList(){
		$params = getData();
		$view = $this->model->salesOrderList($params);
		if(isset($params['with_id'])){
			$add = $this->model->salesOrder($params['with_id']);
			$view = array_merge($view, $add);
		}
		die(
			json_encode(
				array(
					'success' => true,
					'view' => $view
				)
			)
		);
	}

	public function searchHistoryGrid()
	{
		$params = getData();
		$view = $this->model->searchHistoryGrid( $params );

		die(
			json_encode(
				array(
					'success' => true 
					,'view' => $view
					,'params' => $params
				)
			)
		);
	}

	public function customerInfo(){
		$params = getData();
		$view = $this->model->customerInfo($params['id']);
		die(
			json_encode(
				array(
					'success' => true,
					'view' => $view
				)
			)
		);
	}

	public function affiliateInfo(){
		$params = getData();
		$view = $this->model->affiliateInfo($params['id']);
		die(
			json_encode(
				array(
					'success' => true,
					'view' => $view
				)
			)
		);
	}

	public function getItems(){
		$params = getData();
		$view = $this->model->getItems($params);
		
		$_viewHolder = $view;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['itemSK'] ) && !empty( $po['itemSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['itemSK'] )));
                $_viewHolder[$idx]['name'] = $this->encryption->decrypt( $po['name'] );
                $_viewHolder[$idx]['price'] = $this->encryption->decrypt( $po['price'] );
            }
        }
        $view['view'] = $_viewHolder;

		die(
			json_encode(
				array(
					'success' => true,
					'view' => $view['view']
				)
			)
		);
	}

	public function save(){
		$this->db->trans_start();
		$params = getData();
		
		$items = json_decode($params['items'],true);
		$journals = json_decode($params['journals'],true);
		$invoice = [
			"idAffiliate" 			=> $this->session->userdata('AFFILIATEID'),
            "idReference" 			=> $params["idReference"],
			"referenceNum" 			=> $params["referenceNum"],
			"idCostCenter" 			=> $params["idCostCenter"],
            "idReferenceSeries" 	=> $params["idReferenceSeries"],
            "idModule" 				=> $params["idmodule"],
            "cancelTag" 			=> $params["cancelTag"],
            "date" 					=> $params["tdate"]. ' '. $params["ttime"],
            "remarks" 				=> $params["remarks"],
            "dateModified" 			=> $params["dateModified"],
            "hasJournal" 			=> (count($journals) > 0),
            "status" 				=> APPROVED,
			"preparedBy" 			=> $this->session->userdata('USERID'),
			"amount" 				=> $params["totalamnt"],
			"bal" 					=> $params["balance"],
			"balLeft" 				=> $params["balance"],
			"downPayment" 			=> $params["downpayment"],
			"discount" 				=> $params["discount"],
			"discountRate" 			=> $params["discountper"],
			"dueDate" 				=> $params["duedate"],
			"payMode" 				=> $params["paymentType"],
			"pCode" 				=> $params["customer"],
			"vatType" 				=> $params["r_vatType"],
			"vatPercent" 			=> $params["r_vatPercent"],
			"vatAmount" 			=> $params["vatamount"],
			"penaltyRate"	 		=> $params["penalty"],
			"ewtRate"	 			=> $params["ewtRate"],
			"idDriver"	 			=> $params["idDriver"],
			"plateNumber"	 		=> $params["plateNumber"],
			"ewtAmount"	 			=> ( $params["balance"] * ( $params["ewtRate"]/100 ) ),
			"penaltyAmount"	 		=> ( $params["balance"] * ( $params["penalty"]/100 ) ),
			"pType"					=> 1,
			"deliveryReceiptTag"	=> $params["deliveryReceiptTag"],
			"deliveryReceipt"		=> isset($params["deliveryReceipt"])? $params["deliveryReceipt"] :null,
			"fident"				=> isset($params["refSONumber"])? $params["refSONumber"] :null
		];

		if( $params['cancelTag'] == 1 ) $invoice['cancelledBy'] = $this->session->userdata('USERID');

		$id = $params['idInvoice'];
        if($id == 0 || $id == '0'){
			$id = $this->model->insert($invoice, 'invoices'); 

			//Set Logs
			$params['action']   = 'added a new Sales Order transaction ';
			$this->setLogs( $params );
			
        }else {
            $this->model->returnReleased(['idInvoice'=>$id]);
            $this->model->returnSO(['idInvoice'=>$id]);
			$this->model->deleteAssociateChild(['id'=>$id]);
			$this->model->update('invoices', $invoice, ['idInvoice'=>$id]);

			//Set Logs
			$params['action']   = 'edited a transaction ';
        	$this->setLogs( $params );
		}
		
		$invoice['idInvoice'] = $id;
		$this->model->insert($invoice, 'invoiceshistory'); 
		
		$grids = $this->format($params,['items' => $items, 'journals'=>$journals], $id );
		$this->model->released($grids['items'], $this->session->userdata('AFFILIATEID') , $invoice['fident']);
		if(count($grids['journals']) > 0){
            $this->model->insertBulk($data['journals'], 'posting');
        }
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			die(
				json_encode(
					array(
						'success' => false
					)
				)
			);
		} 
		else {
			$this->db->trans_commit();
			die(
				json_encode(
					array(
						'success' => true
						,'match' => 0
					)
				)
			);
		}
	}

	public function getSOItem(){
		$params = getData();
		$view = [];

		if(!isset($params['id'])) return ;

		$view = $this->model->getAllSOItems($params['id'], $this->session->userdata('AFFILIATEID'));

		$_viewHolder = $view;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['sk'] ) && !empty( $po['sk'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['sk'] )));
                $_viewHolder[$idx]['name'] = $this->encryption->decrypt( $po['name'] );
                $_viewHolder[$idx]['price'] = $this->encryption->decrypt( $po['price'] );
            }
        }
		$view = $_viewHolder;
		
		die(
			json_encode(
				array(
					'success' => true,
					'view' => $view
				)
			)
		);
	}

	public function getListInvoices(){
		$params = getData();
		$view = $this->model->viewAll($params);
		$_viewHolder = $view['view'];
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['customerSK'] ) && !empty( $po['customerSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['customerSK'] )));
                $_viewHolder[$idx]['customer'] = $this->encryption->decrypt( $po['customer'] );
            }
        }
        $view['view'] = $_viewHolder;
		die(
			json_encode(
				array(
					'success' => true,
					'view' => $view['view'],
					'total' => $view['count']
				)
			)
		);
	}
	
	public function retrieve(){
        $params		= getData();
		$view		= $this->model->getInvoices(['idInvoice'=>$params['id']]);
		$released	= $this->model->getReleased(['releasing.idInvoice'=>$params['id']]);
		$match		= $this->model->checkMutation('releasing', ['releasing.idInvoice'=>$params['id']])[0]['state'];

		$released = decryptItem( $released );

		if ( (int)$this->model->cancelTag( $params['id'] ) == 1 ) {
			$match = 2;
		}

        die(
			json_encode(
				array(
					'success' => true
                    ,'view' => $view
					,'match' => (int) $match
					,'released' => $released
				)
			)
        );
	}

	public function updateTransaction(){
        $params = getData();
        
        $this->model->update('invoices', ['status'=>$params['status'], 'notedby' => $this->session->userdata('USERID')], [ 'idInvoice' => $params['idInvoice']]);
        if($params['status'] == CANCELLED){
			$this->model->returnReleased(['idInvoice'=>$params['idInvoice']]);
            $this->model->returnSO(['idInvoice'=>$params['idInvoice']]);
        }
        die(
			json_encode(
				array(
					'success' => true
				)
			)
        );
    }

	public function delete(){
        $params = getData();
		$match	= $this->model->checkMutation('releasing', ['releasing.idInvoice'=>$params['id']])[0]['state'];

		if ( (int)$this->model->cancelTag( $params['id'] ) == 1 ) {
			$match = 2;
		}

        if($match == IS_MUTABLE){
			$this->model->update('invoices', ['archived' => POSITIVE], ['idInvoice' => $params['id']]);
			$this->model->returnReleased(['idInvoice'=>$params['id']]);
			$this->model->returnSO(['idInvoice'=>$params['id']]);

			//Set Logs
			$params['action']   = 'deleted a transaction';
        	$this->setLogs( $params );
		}
		
        die(
			json_encode(
				array(
                    'success' => true
                    ,'match' => (int)$match
				)
			)
        );
	}
	
	public function customerItem(){
		$params = getData();
		
		$view = $this->model->customerItem($params['id'], $params['affiliate']);

		die(
			json_encode(
				array(
                    'success' => true
                    ,'view' => $view
				)
			)
        );
	}
	
	private function format($params, $grid, $id){
		$items = [];
		$journals = [];

		//releasing
		foreach($grid['items'] as $key => $item ){
			array_push($items,[
				'idItem' => $item['id'],
				'qty' =>  $item['qty'],
				'qtyLeft' =>  $item['qty'],
				'price' =>  $item['price'],
				'cost' =>  $item['cost'],
				'expiration' =>  $item['expiration'],
				'lotNumber' =>  $item['lot'],
				'idInvoice' => $id,
			]);
		}

		// journal
		if(isset($grid['journals'])){
			foreach($grid['journals'] as $key => $journal){
				array_push($journals, [
					'idInvoice' => $id,
					'explanation' =>  $journal['explanation'],
					'idCoa' =>  $journal['idCoa'],
					'idCostCenter' =>  $journal['idCostCenter'],
					'debit' =>  $journal['debit'],
					'credit' =>  $journal['credit'],
					'idSo' => 0
				]);
			}
		}

		return ['items' => $items, 'journals' => $journals];
	}

	public function getSearchRef(){
        $params = getData();
        $view   = $this->model->getSearchRef( $params );
        array_unshift(
            $view
            ,array(
                'id'     => 0
                ,'name'    => 'All'
            )
        );
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
	}
	
	private function setLogs( $params ){
		$header = 'Sales : '.$this->USERFULLNAME;
		$action = '';
		
		if( isset( $params['deleting'] ) ){
			$action = 'deleted a transaction';
		}
		else{
			if( isset( $params['action'] ) )
				$action = $params['action'];
			else
				$action = ( $params['onEdit'] == 1  ? 'modified' : 'added new' );
		}
		
		setLogs(
            array(
                'actionLogDescription'  => $header . ' ' . $action
                ,'idReference'			=> $params['idReference']
                ,'referenceNum'			=> $params['referenceNum']
                ,'idModule'				=> $params['idmodule']
                ,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            )
        );
	}
	
	function printPDF() {
        $data = getData();

        $formDetails = json_decode( $data['form'], true );
        $items = json_decode( $data['items'], true );
        
        $header_fields = array(
            array(
                array(
                    'label' => 'Affiliate'
                    ,'value' => $formDetails['pdf_idAffiliate']
				)
				,array(
                    'label' => 'Cost Center'
                    ,'value' => $formDetails['pdf_idCostCenter']
                )
                ,array(
                    'label' => 'Reference'
                    ,'value' => $formDetails['pdf_idReference'] . '-' .$formDetails['pdf_referenceNum']
                ),array(
                    'label' => 'Date'
                    ,'value' => $formDetails['pdf_tdate']
				)
				,array(
                    'label' => 'SO Number'
                    ,'value' => $formDetails['pdf_refSONumber']
				)
				,array(
                    'label' => 'Customer Name'
                    ,'value' => $formDetails['pdf_customer']
				)
				,array(
                    'label' => 'Payment Method'
                    ,'value' => $formDetails['pdf_paymentType']
				)
				,array(
                    'label' => 'Terms'
                    ,'value' => $formDetails['pdf_terms']
                )
            )
            ,array(
				array(
                    'label' => 'Remarks'
                    ,'value' => $formDetails['pdf_remarks']
                )
                ,array(
                    'label' => 'Due Date'
                    ,'value' => $formDetails['pdf_duedate']
				)
				,array(
                    'label' => 'Credit Limit'
                    ,'value' => $formDetails['pdf_creditLimit']
				)
				,array(
                    'label' => 'AR Balance'
                    ,'value' => $formDetails['pdf_arbal']
				)
				,array(
                    'label' => 'Variance'
                    ,'value' => $formDetails['pdf_variance']
                )
            )
        );


        $table = array(
            array(
                'header'		=>'Code'
                ,'dataIndex'	=>'barcode'
                ,'width'		=>'12.5'	
            ),
            array(
                'header'		=>'Item Name'
                ,'dataIndex'	=>'name'
                ,'width'		=>'12.5'
            ),
            array(
                'header'		=>'Classification'
                ,'dataIndex'	=>'classification'
                ,'width'		=>'12.5'
			),
			array(
                'header'		=>'Unit of Measure'
                ,'dataIndex'	=>'unit'
                ,'width'		=>'12.5'
			),
			array(
                'header'		=>'Lot Number'
                ,'dataIndex'	=>'lot'
				,'width'		=>'12.5'
				,'type' 		=> 'numbercolumn'
			),
			array(
                'header'		=>'Expiry Date'
                ,'dataIndex'	=>'expiration'
				,'width'		=>'12.5'
				,'type' 		=>'datecolumn'
				,'format'		=>'m-d-Y'
            ),
            array(
                'header'		=>'Quantity'
                ,'dataIndex'	=>'qty'
                ,'width'		=>'12.5'
                ,'type' 		=> 'numbercolumn'
            ),
            array(
                'header'		=>'Amount'
                ,'dataIndex'	=>'amount'
                ,'width'		=>'12.5'
                ,'type' 		=> 'numbercolumn'
                ,'format' 		=> '0,000.00'
            )
        );

        generateTcpdf(
			array(
				'file_name'         => $data['title']
                ,'folder_name'      => 'inventory'
                ,'header_fields'    => $header_fields
                ,'records'          => $items
                ,'header'           => $table
                ,'orientation'      => 'P'
                ,'params'           => $data
                ,'idAffiliate'      => $data['idAffiliate']
                // ,'hasSignatories' => 1
			) 
        );
    }


	function customListPDF(){
        $params = getData();

        $table = array(
            array(
                'header'        =>'Reference Number'
                ,'dataIndex'    =>'reference'
                ,'width'        =>'20'
            )
            ,array(
                'header'        =>'Date'
                ,'dataIndex'    =>'date'
                ,'width'        =>'20'
            )
            ,array(
                'header'        =>'Customer'
                ,'dataIndex'    =>'customer'
                ,'width'        =>'40'
            )
            ,array(
                'header'        =>'Net Sales'
                ,'dataIndex'    =>'sales'
                ,'width'        =>'20'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
            )
        );


        generateTcpdf(
			array(
				'file_name'         => 'Sales List'
                ,'folder_name'      => 'inventory'
                ,'records'          => json_decode($params['items'], true)
                ,'header'           => $table
                ,'orientation'      => 'P'
                ,'idAffiliate'      => $this->session->userdata('AFFILIATEID')
			) 
        );
    }

    function printExcel (){
		$data = getData();
		$sum = 0;
        $view = $this->model->viewAll( $data );

        $_viewHolder = $view['view'];
    
        foreach( $_viewHolder as $idx => $po ){
            /**Decrypting customer**/
            if( isset( $po['customerSK'] ) && !empty( $po['customerSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['customerSK'] )));
                $_viewHolder[$idx]['customer'] = $this->encryption->decrypt( $po['customer'] );
            }
        }

        $view['view'] = $_viewHolder;
        
		$csvarray[] = array( 'title' => $data['pageTitle'].'' );
		$csvarray[] = array( 'space' => '' );
		$csvarray[] = array( 'space' => '' );

		$csvarray[] = array(
			'col1'  => 'Reference Number'
            ,'col2' => 'Date'
            ,'col5' => 'Customer'
            ,'col9' => 'Net Sales'
        );

		foreach( $view['view'] as $value ){
			$csvarray[] = array(
				'col1' => $value[ 'reference' ]
                ,'col2' => $value[ 'date' ]
                ,'col5' => $value[ 'customer' ]
                ,'col9' => $value[ 'sales' ]
			);
        }
        
		$data['description'] = '' .$data['pageTitle']. ": " .$this->USERNAME. ' printed an Excel report'  ;
		$data['iduser'] = $this->USERID;
		$data['usertype'] = $this->USERTYPEID;
		$data['printExcel'] = true;	
        $data['ident'] = null;

		writeCsvFile(
			array(
				'csvarray' 	 => $csvarray
				,'title' 	 => $data['pageTitle'].''
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