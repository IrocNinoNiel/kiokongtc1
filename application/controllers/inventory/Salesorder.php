<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Salesorder extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->library('encryption');
        setHeader( 'inventory/Salesorder_model' );
        $this->load->model('standards/Standards2_model', 'standard2');
    }

    public function getCustomerItem(){
        $params = getData();
        $view  = $this->model->getCustomerItem($params);
        $view = decryptItem( $view );
        
        die(
			json_encode(
				array(
					'success' => true
                    ,'view' => $view
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
                $_viewHolder[$idx]['cost'] = $this->encryption->decrypt( $po['cost'] );
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

    public function getCustomerDetails(){
        $params = getData();
        $view = $this->model->getCustomerDetails($params);
        $view = (object)decryptCustomer( array( 0 => $view ) )[0];

        die(
			json_encode(
				array(
					'success' => true
                    ,'view' => $view
				)
			)
        );
    }

    public function save(){
        $this->db->trans_start();
        $params = getData();
        
        $params['items'] = json_decode($params['items'], true);
        $params['journals'] = json_decode($params['journals'], true);
        $invoice = [
            "idAffiliate" => $this->session->userdata('AFFILIATEID'),
            "idReference" => $params["idReference"],
            "idCostCenter" => $params["idCostCenter"],
            "referenceNum" => $params["referenceNum"],
            "idReferenceSeries" => $params["idReferenceSeries"],
            "idModule" => $params["idmodule"],
            "cancelTag" => $params["cancelTag"],
            "idLocation" => $this->session->userdata('LOCATIONID'),
            "date" => $params["tdate"]." ".$params["ttime"],
            "pickupDate" => $params["pickupdate"]." ".$params["pickuptime"],
            "pType" => 1,
            "pCode" => $params["pCode"],
            "amount" => $params["totalamt"],
            "remarks" => $params["remarks"],
            "dateModified" => $params["dateModified"],
            "hasJournal" => (count($params["journals"]) > 0),
            "preparedBy" => $this->session->userdata('USERID'),
            'status' => APPROVED
        ];

        if( $params['cancelTag'] == 1 ) $invoice['cancelledBy'] = $this->session->userdata('USERID');
        
        if($params['idInvoice'] != NULL){
			$params['action']   = 'edited a transaction';
            $id = $params['idInvoice'];
            $this->model->update($invoice, 'invoices', $id);
            $this->model->deleteAssociateChild(['id'=>$id]);

        }else{
			$params['action']   = 'added a new Sales Order transaction';
            $id = $this->model->insert($invoice, 'invoices'); 
        }
        
        $invoice['idInvoice'] = $id;
		$this->model->insert($invoice, 'invoiceshistory');
        $data = $this->format($params, $id);

        $this->model->insertBulk($data['items'], 'so');
        if(count($data['journals']) > 0){
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
            $this->setLogs( $params );
			die(
				json_encode(
					array(
						'success' => true
					)
				)
			);
		}
    }

    private function format($data, $invoice){
        $items = [];
        $journals = [];

        $data['idLocation'] =  $this->session->userdata('LOCATIONID');
        $data['preparedBy'] =  $this->session->userdata('USERID');

        foreach($data['items'] as $key => $item){
            array_push($items, [
                'idItem' => $item['id'],
                'qty' =>  $item['qty'],
                'qtyLeft' =>  $item['qty'],
                'cost' => $item['cost'],
                'idInvoice' => $invoice
            ]);
        }

        foreach($data['journals'] as $key => $journal){
            array_push($journals, [
                'idInvoice' => $invoice,
                'explanation' =>  $journal['explanation'],
                'idCoa' =>  $journal['idCoa'],
                'idCostCenter' =>  $journal['idCostCenter'],
                'debit' =>  $journal['debit'],
                'credit' =>  $journal['credit'],
                'idSo' => 0
            ]);
        }

        return['items' => $items, 'journals' => $journals];
    }

    public function getSoItems(){
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
					'success' => true
                    ,'view' => $view['view']
					,'total' => $view['count']
				)
			)
        );
    }

    public function delete(){
        $params = getData();
        $match = $this->model->checkMutation('so', ['so.idInvoice'=>$params['id']])[0]['state'];

        //cancelTag
        if ( (int)$this->model->cancelTag( $params['id'] ) == 1 ) {
            $match = 2;
        }
        
        if($match == IS_MUTABLE){
            $this->model->update( ['archived' => POSITIVE], 'invoices',$params['id']);

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

    public function retrieve(){
        $params = getData();
        $view = $this->model->retrieveAll($params);
        $match = $this->model->checkMutation('so', ['so.idInvoice'=>$params['id']])[0]['state'];

        $_viewHolder = $view;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['sk'] ) && !empty( $po['sk'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['sk'] )));
                $_viewHolder[$idx]['customer_address'] = $this->encryption->decrypt( $po['customer_address'] );
                $_viewHolder[$idx]['customer_tin'] = $this->encryption->decrypt( $po['customer_tin'] );
            }
        }
        $view = $_viewHolder;

        $itemHolder = $view['items'];
        foreach( $itemHolder as $idx => $item ){
            if( isset( $item['itemSK'] ) && !empty( $item['itemSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $item['itemSK'] )));
                $itemHolder[$idx]['name'] = $this->encryption->decrypt( $item['name'] );
            }
        }
        $view['items'] = $itemHolder;

        if ( (int)$this->model->cancelTag( $params['id'] ) == 1 ) {
            $match = 2;
        }

        die(
			json_encode(
				array(
                    'success' => true
                    ,'view' => json_encode($view)
                    ,'match' => (int) $match
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

    private function setLogs( $params ){
		$header = 'Sales Order : '.$this->USERFULLNAME;
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
                    'label' => 'Reference'
                    ,'value' => $formDetails['pdf_idReference'] . '-' .$formDetails['pdf_referenceNum']
                )
                ,array(
                    'label' => 'Customer Name'
                    ,'value' => $formDetails['pdf_pCode']
                )
                ,array(
                    'label' => 'Address'
                    ,'value' => $formDetails['pdf_address']
                )
            )
            ,array(
                array(
                    'label' => 'Cost Center'
                    ,'value' => $formDetails['pdf_idCostCenter']
                )
                ,array(
                    'label' => 'Date'
                    ,'value' => $formDetails['pdf_tdate']
                )
                ,array(
                    'label' => 'TIN'
                    ,'value' => $formDetails['pdf_tin']
                )
                ,array(
                    'label' => 'Remarks'
                    ,'value' => $formDetails['pdf_remarks']
                )
                ,array(
                    'label' => 'Pick-up Date'
                    ,'value' => $formDetails['pdf_tdate']
                )
            )
        );


        $table = array(
            array(
                'header'=>'Item Code'
                ,'dataIndex'=>'barcode'
                ,'width'=>'14.28'	
            ),
            array(
                'header'=>'Item Name'
                ,'dataIndex'=>'name'
                ,'width'=>'14.28'
            ),
            array(
                'header'=>'Classification'
                ,'dataIndex'=>'classification'
                ,'width'=>'14.28'
            ),
            array(
                'header'=>'Unit'
                ,'dataIndex'=>'unit'
                ,'width'=>'14.28'
            ),
            array(
                'header'=>'Cost'
                ,'dataIndex'=>'cost'
                ,'width'=>'14.28'
                ,'type' => 'numbercolumn'
                ,'format' => '0,000.00'
            ),
            array(
                'header'=>'Quantity'
                ,'dataIndex'=>'qty'
                ,'width'=>'14.28'
                ,'type' => 'numbercolumn'
            ),
            array(
                'header'=>'Amount'
                ,'dataIndex'=>'amount'
                ,'width'=>'14.28'
                ,'type' => 'numbercolumn'
                ,'format' => '0,000.00'
            )
        );

        generateTcpdf(
			array(
				'file_name'         => $data['title']
                ,'folder_name'      => 'inventory'
                ,'header_fields'    => $header_fields
                ,'records'          =>  $items
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
                'header'        =>'Total Amount'
                ,'dataIndex'    =>'total'
                ,'width'        =>'20'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
            )
        );

        generateTcpdf(
			array(
				'file_name'         => 'Sales Order List'
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
            ,'col9' => 'Total Amount'
        );
        
    

		foreach( $view['view'] as $value ){
			$csvarray[] = array(
				'col1' => $value[ 'reference' ]
                ,'col2' => $value[ 'date' ]
                ,'col5' => $value[ 'customer' ]
                ,'col9' => $value[ 'total' ]
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