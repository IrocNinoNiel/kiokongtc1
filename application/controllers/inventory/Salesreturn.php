<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Salesreturn extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->load->library( 'encryption' );
        setHeader( 'inventory/Salesreturn_model' );
        $this->load->model('standards/Standards2_model', 'standard2');
    }

    public function getCustomerItem(){
        $params = getData();
        if(!isset($params['idInvoice'])) die(json_encode(['status'=>true, 'view'=>[]]));
        $view  = $this->model->getCustomerItem($params);
		$view = decryptCustomer( $view );
        die(
			json_encode(
				array(
					'success' => true
                    ,'view' => $view
				)
			)
        );
    }
    public function getCustomerInvoice(){
        $params = getData();
        $view  = $this->model->getCustomerInvoice($params);
        if(isset($params['with_id'])){
			$add = $this->model->currentInvoice($params['with_id']);
			$view = array_merge($view, $add);
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
            "cancelTag" => $params["cancelTag"],
            "idModule" => SALES_RETURN,
            "date" => $params["tdate"]." ".$params["ttime"],
            "pType" => 1,
            "pCode" => $params["pCode"],
            "amount" => $params["totalamt"],
            "remarks" => $params["remark"],
            "dateModified" => $params["dateModified"],
            "hasJournal" => (count($params["journals"]) > 0),
            "fident" => $params['invoices'],
            "preparedBy" => $this->session->userdata('USERID'),
            "status" => APPROVED
        ];
        
        if($params['idInvoice'] != NULL){
			$params['action']   = 'edited a transaction';
            $id = $params['idInvoice'];
            $this->model->returnReceived(['idInvoice'=>$id]);
            $this->model->deleteAssociateChild(['id'=>$id]);
            $this->model->update('invoices', $invoice, ['idInvoice'=>$id]);
        }else{
			$params['action']   = 'added a new Sales Return transaction';
            $id = $this->model->insert($invoice, 'invoices'); 
        }
        $invoice['idInvoice'] = $id;
		$this->model->insert($invoice, 'invoiceshistory');
        $data = $this->format($params, $id);
        if(count($data['items']) > 0){
            $this->model->insertBulk($data['items'], 'receiving');
        }
        if(count($data['journals']) > 0){
            $this->model->insertBulk($data['journals'], 'posting');
        }
        if(count($data['items_update']) > 0){
            $this->model->updateBulk($data['items_update'], 'releasing', 'idReleasing');
        }

        if( $params['cancelTag'] == 1 ) {
            $invoice['cancelledBy'] = $this->session->userdata('USERID');
            $this->model->returnReceived( [ 'idInvoice' => $params['idInvoice'] ] );
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

    public function delete() {
        $params = getData();
        $match = $this->model->checkMutation('receiving', ['receiving.idInvoice'=>$params['id']])[0]['state'];

        //cancelTag
        if ( (int)$this->model->cancelTag( $params['id'] ) == 1 ) {
            $match = 2;
        }
        
        if($match == IS_MUTABLE){
			$this->model->update('invoices', ['archived' => POSITIVE], ['idInvoice' => $params['id']]);
            $this->model->returnReceived(['idInvoice'=>$params['id']]);
            
            $params['action']   = 'deleted a transaction';
            $this->setLogs( $params );
        }

        die(
			json_encode(
				array(
                    'success' => true
                    ,'match' => (int) $match
				)
			)
        );
	}

    public function getInvoices(){
        $params = getData();
        $data = $this->model->viewAll($params);

        $_viewHolder = $data['view'];
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
					,'total' => $data['count']
				)
			)
        );
    }

    public function updateTransaction(){
        $params = getData();
        
        $this->model->update('invoices', ['status'=>$params['status'], 'idInvoice' => $params['idInvoice'], 'notedby' => $this->session->userdata('USERID')], [ 'idInvoice' => $params['idInvoice']]);
        if($params['status'] == CANCELLED){
			$this->model->returnReceived(['idInvoice'=>$params['idInvoice']]);
        }
        die(
			json_encode(
				array(
					'success' => true
				)
			)
        );
    }

    private function format($data, $invoice){
        $items = [];
        $items_update = [];
        $journals = [];

        $data['idLocation'] =  $this->session->userdata('LOCATIONID');
        $data['receivedBy'] =  $this->session->userdata('USERID');
        foreach($data['items'] as $key => $item){
            if((int)$item['qty'] == 0) continue;
            array_push($items, [
                'idItem' => $item['id'],
                'qty' =>  $item['qty'],
                'qtyLeft' =>  $item['qty'],
                'cost' => $item['cost'],
                'price' => $item['price'],
                'idInvoice' => $invoice,
                'fident' => $item['releasedID']
            ]);
            
            array_push($items_update,[
                'idReleasing' => $item['releasedID'],
                'qtyLeft' =>  (int) $item['remaining'] -  (int)$item['qty']
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

        return['items' => $items, 'journals' => $journals, 'items_update' => $items_update];
    }

    public function retrieve(){
        $params = getData();
        $view = $this->model->retrieveAll($params);
        $items = $this->model->retrieveItems($params);
        $match = $this->model->checkMutation('receiving', ['receiving.idInvoice'=>$params['id']])[0]['state'];
		$items = decryptItem( $items );

        //cancelTag
        if ( (int)$this->model->cancelTag( $params['id'] ) == 1 ) $match = 2;
        
        die(
			json_encode(
				array(
                    'success' => true
                    ,'view' => $view
                    ,'match' => (int) $match
					,'released' => $items
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
		$header = 'Sales Return : '.$this->USERFULLNAME;
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
                    'label' => 'Invoice'
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
                    'label' => 'Remarks'
                    ,'value' => $formDetails['pdf_remarks']
                )
            )
        );


        $table = array(
            array(
                'header'=>'Code'
                ,'dataIndex'=>'code'
                ,'width'=>'11.11'	
            ),
            array(
                'header'=>'Item Name'
                ,'dataIndex'=>'name'
                ,'width'=>'11.11'
            ),
            array(
                'header'=>'Classification'
                ,'dataIndex'=>'class'
                ,'width'=>'11.11'
            ),
            array(
                'header'=>'Unit'
                ,'dataIndex'=>'unit'
                ,'width'=>'11.11'
            ),
            array(
                'header'=>'Cost'
                ,'dataIndex'=>'cost'
                ,'width'=>'11.11'
                ,'type' => 'numbercolumn'
                ,'format' => '0,000.00'
            ),
            array(
                'header'=>'Price'
                ,'dataIndex'=>'price'
                ,'width'=>'11.11'
                ,'type' => 'numbercolumn'
                ,'format' => '0,000.00'
            ),
            array(
                'header'=>'Qty to Return'
                ,'dataIndex'=>'remaining'
                ,'width'=>'11.11'
                ,'type' => 'numbercolumn'
            ),
            array(
                'header'=>'Quantity'
                ,'dataIndex'=>'qty'
                ,'width'=>'11.11'
                ,'type' => 'numbercolumn'
            ),
            array(
                'header'=>'Amount'
                ,'dataIndex'=>'amount'
                ,'width'=>'11.11'
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
				'file_name'         => 'Sales Return List'
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