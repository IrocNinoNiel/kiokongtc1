<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventoryconversion extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->load->library( 'encryption' );
        setHeader( 'inventory/Inventoryconversion_model' );
        $this->load->model('standards/Standards2_model', 'standard2');
        $this->load->model('standards/Standards_model', 'standard');
    }

    public function getItems(){
        $params = getData();
        $view = $this->model->getItems( $params );

        // print_r( $view );
        // die();

        // LQ();

        $_viewHolder = $view;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['itemSK'] ) && !empty( $po['itemSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['itemSK'] )));
                $_viewHolder[$idx]['name'] = $this->encryption->decrypt( $po['name'] );
                $_viewHolder[$idx]['cost'] = $this->encryption->decrypt( $po['cost'] );
            }
        }
        $view['view'] = $_viewHolder;
        die(
            json_encode(
                array(
                    'success' => true
                    ,'view'=> $view['view']
                )
            )
        );
    }

    public function getItemsCost(){
        $params = getData();
        $view = $this->model->getItemsCost( $params );

        die(
            json_encode(
                array(
                    'success' => true
                    ,'view'=> $view
                )
            )
        );
    }

    public function save(){
        $this->db->trans_start();
        $params = getData();
        $grids = json_decode($params['grid'], true);
        $grids['items'] = json_decode($grids['items'],true);
        $grids['outitem'] = json_decode($grids['outitem'],true);
        $grids['journals'] = json_decode($grids['journals'],true);

        $invoice  = [
            "idAffiliate"       => $this->session->userdata('AFFILIATEID'),
            "idReference"       => $params["idReference"],
            "cancelTag"         => $params["cancelTag"],
            "referenceNum"      => $params["referenceNum"],
            "idReferenceSeries" => $params["idReferenceSeries"],
            "idModule"          => $params["idmodule"],
            "amount"            => $params['totalamt'],
            "cancelTag"         => $params["cancelTag"],
            "idLocation"        => $this->session->userdata('LOCATIONID'),
            "date"              => $params["tdate"]. ' '. $params["ttime"],
            "remarks"           => $params["remarks"],
            "dateModified"      => $params["dateModified"],
            "hasJournal"        => (count($grids["journals"]) > 0),
            "status"            => APPROVED,
            "preparedBy"        => $this->session->userdata('USERID'),
        ];

        if( $params['cancelTag'] == 1 ) $invoice['cancelledBy'] = $this->session->userdata('USERID');

        $id = $params['idInvoice'];
        if($id == 0 || $id == '0'){
            
            $id = $this->model->insert($invoice, 'invoices'); 
            
        }else {
            $this->model->update('invoices', $invoice, ['idInvoice'=>$id]);
            $this->model->returnReleased(['idInvoice'=>$id]);
            $this->model->deleteAssociateChild(['id'=>$id]);
            
        }

        $invoice['idInvoice'] = $id;
        $this->model->insert($invoice, 'invoiceshistory'); 

        $items = $this->format($params,$grids,$id );
        $this->model->released($items['released'], $items['items'],$this->session->userdata('AFFILIATEID'));
        $this->model->received($items['receiving']);

        if(count($items['journals']) > 0){
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
					)
				)
			);
		}
        
    }

    public function getBulkItems(){
        $params = getData();
        $view = [];
        if(isset($params['idInvoice'])){
            $view = $this->model->retrieveBulk(['releasing.idInvoice' => $params['idInvoice']]);
        }

        if( !empty( $view ) ) $view = decryptItem( $view );
        
        die(
			json_encode(
				array(
					'success' => true
                    ,'view' => $view
				)
			)
        );
    }

    public function getConvertedItems(){
        $params = getData();
        $view = [];
        if(isset($params['idInvoice'])){
            $view = $this->model->retrieveConverted(['receiving.idInvoice' => $params['idInvoice']]);
        }

        if( !empty( $view ) ){
            $view = decryptItem( $view );

            $_viewHolder = $view;
            foreach( $_viewHolder as $idx => $po ){
                $_viewHolder[$idx]['total'] = ($po['qty'] * $po['cost']);
            }
            $view = $_viewHolder;
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

    public function retrieve(){
        $params = getData();
        $view = $this->model->getInvoices(['idInvoice'=>$params['id']]);
        $released = $this->model->checkMutation('releasing', ['releasing.idInvoice'=>$params['id']])[0]['state'];
        $received = $this->model->checkMutation('receiving', ['receiving.idInvoice'=>$params['id']])[0]['state'];
        $match = $received == IS_MUTABLE && $received == IS_MUTABLE? IS_MUTABLE : NOT_MUTABLE;

        if ( (int)$this->model->cancelTag( $params['id'] ) == 1 ) {
            $match = 2;
        }

        die(
			json_encode(
				array(
					'success' => true
                    ,'view' => $view
                    ,'match' => $match
				)
			)
        );
    }

    public function getListItems(){
        $params = getData();
        $view = $this->model->viewAll( $params );
        $_viewHolder = $view['view'];
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['itemSK'] ) && !empty( $po['itemSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['itemSK'] )));
                $_viewHolder[$idx]['item'] = $this->encryption->decrypt( $po['item'] );
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

    public function updateTransaction(){
        $params = getData();
        
        $this->model->update('invoices', ['status'=>$params['status'], 'idInvoice' => $params['idInvoice'], 'notedby' => $this->session->userdata('USERID')], [ 'idInvoice' => $params['idInvoice']]);
        if($params['status'] == CANCELLED){
            $this->model->returnReleased(['idInvoice'=>$params['idInvoice']]);
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
        $released = $this->model->checkMutation('releasing', ['releasing.idInvoice'=>$params['id']])[0]['state'];
        $received = $this->model->checkMutation('receiving', ['receiving.idInvoice'=>$params['id']])[0]['state'];
        $match = $received == IS_MUTABLE && $received == IS_MUTABLE? IS_MUTABLE : NOT_MUTABLE;

        //cancelTag
        if ( (int)$this->model->cancelTag( $params['id'] ) == 1 ) {
            $match = 2;
        }
        
        if($match == IS_MUTABLE){
            $this->model->update('invoices', ['archived' => POSITIVE], ['idInvoice' => $params['id']]);
        }

        die(
			json_encode(
				array(
                    'success' => true
                    ,'match' => $match
				)
			)
        );
    }

    private function format($data, $grids, $invoice){
        $items = [];
        $receiving = [];
        $journals = [];
        $released = [];

        $data['idLocation'] =  $this->session->userdata('LOCATIONID');
		$data['preparedBy'] =  $this->session->userdata('USERID');
        //releasing
        foreach($grids['items'] as $key => $item){
            $item = (array)$item;
			if($item['idItem'] != ""){
				array_push($items, [
					'idItem' => $item['idItem'],
					'qty' =>  $item['qty'],
					'qtyLeft' =>  $item['qty'],
					'cost' => $item['cost'],
					'price' => $item['total'],
					'idInvoice' => $invoice,
				]);
			}
            
        }
        //receiving
        foreach($grids['outitem'] as $key => $item){
            $item = (array)$item;
			if($item['idItem'] != ""){
				array_push($receiving, [
					'idItem' => $item['idItem'],
					'qty' =>  $item['qty'],
					'qtyLeft' =>  $item['qty'],
					'expiryDate' =>  $item['expirydate'],
					'cost' => $item['price'],
					'price' => $item['total'],
                    'idInvoice' => $invoice
                ]);
                array_push( $released, [
					'idItem' => $item['idItem'],
					'qty' =>  $item['qty']
				]);
			}
        }
		if(isset($data['journals'])){
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
		}

        return['items' => $items, 'journals' => $journals, 'receiving' => $receiving, 'released' => $released];
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

    function printPDF() {
        $data = getData();

        $formDetails    = json_decode( $data['form'], true );
        $items          = json_decode( $data['items'], true );
        $outputGrd      = json_decode( $data['outputGrd'], true );

        $header_fields = array(
            array(
                array(
                    'label' => 'Affiliate'
                    ,'value' => $formDetails['pdf_idAffiliate']
				)
                ,array(
                    'label' => 'Reference'
                    ,'value' => $formDetails['pdf_idReference'] . '-' .$formDetails['pdf_referenceNum']
                ),array(
                    'label' => 'Date'
                    ,'value' => $formDetails['pdf_tdate']
                )
                ,array(
                    'label' => 'Time'
                    ,'value' => $formDetails['pdf_ttime']
				)
            )
            ,array(
				array(
                    'label' => 'Remarks'
                    ,'value' => $formDetails['pdf_remarks']
                )
            )
        );

        /* TABLE FOR ITEMS TO BE CONVERTED */
        $table = array(
            array(
                'header'		=>  'Code'
                ,'dataIndex'	=>  'barcode'
                ,'width'		=>  '14.28'	
            ),
            array(
                'header'		=>  'Item Name'
                ,'dataIndex'	=>  'name'
                ,'width'		=>  '14.28'
            ),
            array(
                'header'		=>  'Unit of Measurement'
                ,'dataIndex'	=>  'unit'
                ,'width'		=>  '14.28'
            ),
            array(
                'header'		=>  'Available Qty'
                ,'dataIndex'	=>  'remaining'
                ,'width'		=>  '14.28'
                ,'type' 		=> 'numbercolumn'
			),
			array(
                'header'		=>  'Qty to be Converted'
                ,'dataIndex'	=>  'convertqty'
                ,'width'		=>  '14.28'
                ,'type' 		=> 'numbercolumn'
			),
			array(
                'header'		=>  'Cost'
                ,'dataIndex'	=>  'cost'
				,'width'		=>  '14.28'
				,'type' 		=> 'numbercolumn'
			),
			array(
                'header'		=>  'Total'
                ,'dataIndex'	=>  'total'
                ,'width'		=>  '14.28'
                ,'type' 		=> 'numbercolumn'
            )
        );

        /* TABLE FOR CONVERSION OUTPUT */
        $extraHeader = array(
            array(
                'header'		=>'Code'
                ,'data_index'	=>'barcode'
                ,'width'		=>'14.28%'	
            ),
            array(
                'header'		=>'Item Name'
                ,'data_index'	=>'name'
                ,'width'		=>'14.28%'
            ),
            array(
                'header'		=>'Unit of Measurement'
                ,'data_index'	=>'unit'
                ,'width'		=>'14.28%'
            ),
            array(
                'header'		=>'Expiry Date'
                ,'data_index'	=>'expirydate'
                ,'width'		=>'14.28%'
                ,'type' 		=>'datecolumn'
				,'format'		=>'m-d-Y'
			),
			array(
                'header'		=>'Qty'
                ,'data_index'	=>'qty'
                ,'width'		=>'14.28%'
                ,'type' 		=> 'numbercolumn'
			),
			array(
                'header'		=>'Cost'
                ,'data_index'	=>'price'
				,'width'		=>'14.28%'
				,'type' 		=> 'numbercolumn'
			),
			array(
                'header'		=>'Amount'
                ,'data_index'	=>'total'
                ,'width'		=>'14.28%'
                ,'type' 		=> 'numbercolumn'
            )
        );

        $extraParams = array(
            'title'             => 'Conversion Output'
            ,'file_name'        => $data['title']
            ,'folder_name'      => ''
            ,'addPage'          => false
            ,'returnAsTable'    => TRUE
            ,'generate_total'   => TRUE
            ,'total_fields'     => array('debit','credit')
            ,'table_title'      => 'Conversion Output'
            ,'noHeader'         => TRUE
        );

        generateTcpdf(
			array(
				'file_name'         => $data['title']
                ,'folder_name'      => 'inventory'
                ,'header_fields'    => $header_fields
                ,'records'          => $items
                ,'header'           => $table
                ,'extraHeader'      => array( 'params' => $extraParams, 'headers' => $extraHeader, 'records' => $outputGrd )
                ,'orientation'      => 'P'
                ,'params'           => $data
                ,'idAffiliate'      => $data['idAffiliate']
                // ,'hasSignatories' => 1
			) 
        );
    }
}