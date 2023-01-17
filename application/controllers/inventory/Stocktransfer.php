<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Mark Reynor D. Magriña
 * Module: Stocktransfer Module
 * Date: Feb 04, 2020
 * Finished:
 * Description:
 * DB Tables:
 * For Password Hashing, please use password_hash( https://www.php.net/manual/en/function.password-hash.php )
 * */
class Stocktransfer extends CI_Controller {

	public function __construct(){
        parent::__construct();
		$this->load->library( 'encryption' );
        setHeader( 'inventory/Stocktransfer_model' );
    }

	function getGridListForPayments(){
		$rawData = getData();
		$record = $this->model->getGridListForPayments( $rawData ) ;
		die( json_encode( array( 'success'=>true, 'view'=> $record ) ) );
	}


	function getItems() {
        $rawData = getData();
        $view = $this->model->getItems( $rawData );

		$_viewHolder = $view;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['itemSK'] ) && !empty( $po['itemSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['itemSK'] )));
                $_viewHolder[$idx]['itemName'] = $this->encryption->decrypt( $po['itemName'] );
                $_viewHolder[$idx]['itemPrice'] = $this->encryption->decrypt( $po['itemPrice'] );
            }
        }
        $view = $_viewHolder;
        die( json_encode( array( 'success' => true ,'view' => $view ) ) );
	}

	function getStockTransferDetails(){
		$rawData = getData();

		$data['view'] = $this->model->viewAll($rawData);
		$data['success'] = true;

		die( json_encode( $data ) );
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

	function saveStockTransferForm(){
		$rawData 				= getData();
		$rawData['status']		= 2;
		$rawData['pType'] 		= 3;
		$rawData['date']		= $rawData['tdate'].' '.$rawData['ttime'];
		$rawData['preparedBy']	= $this->session->userdata('USERID');

		$this->db->trans_begin();// firstline
		$stInvoiceID = $this->model->saveInvoiceStockTransfer($rawData); // save invoices of affiliate releasing/stock transferring item to another affiliate
		$idInvoiceReceiving = $this->model->saveInvoiceReceiving($rawData); // save invoices of affiliate who received a stock transfer item from another affiliate

		/* Set the invoice id according to transaction state. */
		if( (int)$rawData['onEdit'] == 0 ) { $stInvoiceID = $stInvoiceID;}
		else{ $stInvoiceID = (int)$rawData['idInvoice']; }

		/* Prepare stock transfer details for batch saving */
		$gridItemList = json_decode( $rawData['gridItemList'] , true );
		for( $i=0; $i < count($gridItemList); $i++ ){
			$gridItemList[$i]['fIdent'] = (int)$gridItemList[$i]['idReceiving'];
			$gridItemList[$i]['idInvoice'] = $stInvoiceID;
			$gridItemList[$i]['qty'] = $gridItemList[$i]['qtyTransferred'];
		}

        $this->model->saveGridItemList($gridItemList,$stInvoiceID);
		$this->model->saveReleasing( $stInvoiceID, $gridItemList );

		/* Prepare stock transfer details for batch saving */
		$gridItemListReceiving = json_decode( $rawData['gridItemList'] , true );
		for( $i=0; $i < count($gridItemList); $i++ ){
			unset( $gridItemListReceiving[$i]['idReceiving'] );
			$gridItemListReceiving[$i]['idInvoice'] = (int)$idInvoiceReceiving;
			$gridItemListReceiving[$i]['qty'] = $gridItemListReceiving[$i]['qtyTransferred'];
		}
		$this->model->saveReceiving( $idInvoiceReceiving, $gridItemListReceiving );

		/* Set the journal details for batch saving */
		if ( $rawData['journalDetails'] > 1 ) {
			$journalDetails = json_decode( $rawData['journalDetails'],true );
			for( $i=0; $i < count($journalDetails); $i++ ){
				$journalDetails[$i]['idInvoice'] = $stInvoiceID;
			}
			$this->model->saveJournalDetails($journalDetails,$stInvoiceID);
			$this->model->saveJournalDetails($journalDetails,$idInvoiceReceiving);
		}

/*
		Save		<username> transferred stock/s to <location>
		Edit		<username> edited a transaction
		Delete		<username> deleted a transaction
		Cancel		<username> cancelled a transaction
		Confirm		<username> confirmed a stock transfer
 */

		/* Set Logs */
		if($rawData['onEdit'] == 0){ $actioLog = $this->USERNAME.' transferred stocks to '. $rawData['receiverAffiliate']; }
		else{ $actioLog = $this->USERNAME.' edited a transaction'; }
		setLogs( array(
			'actionLogDescription' => $actioLog
			,'idEu' => $this->USERID
			,'moduleID' => 43
			,'time' => date("H:i:s A")
		));

		if( $this->db->trans_status() === FALSE ){
			$this->db->trans_rollback(); // rollback changes
		}
		else{
			$this->db->trans_commit(); // submit changes
		}
		die( json_encode( array( 'success'=>$this->db->trans_status(), 'match'=>0 ) ) );
	}

	function retrieveData(){
		$rawData = getData();
		$record = $this->model->retrieveData($rawData);

		$_viewHolder = $record;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['sk'] ) && !empty( $po['sk'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['sk'] )));
                $_viewHolder[$idx]['transferredBy'] = $this->encryption->decrypt( $po['transferredBy'] );
			}
        }
		$record = $_viewHolder;

		die( json_encode( array( 'status'=>true, 'success'=>true, 'view'=>$record ) ) );
	}
	function getItemListDetails(){
		$rawData = getData();
		$record = $this->model->getItemListDetails($rawData);
		$_viewHolder = $record;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['sk'] ) && !empty( $po['sk'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['sk'] )));
                $_viewHolder[$idx]['itemName'] = $this->encryption->decrypt( $po['itemName'] );
                $_viewHolder[$idx]['itemPrice'] = $this->encryption->decrypt( $po['itemPrice'] );
            }
        }
		$record = $_viewHolder;

		die( json_encode( array( 'success'=>true, 'view'=>$record ) ) );
	}
	function deleteStockTransferRecord(){
		$rawData = getData();
		$match = 0;
		/* Create a filtering process to check if the Items received were still intact if not, prohibit delete */

		/* Add code block here to delete the ST releasing, ST Receiving if not used by releasing module */
		$this->model->deleteStockTransferRecord($rawData['idInvoice']);
		$this->model->deleteStockTransferRecord(($rawData['idInvoice']+1));

		setLogs( array(
			'actionLogDescription' => $this->USERNAME.' deleted a transaction'
			,'idEu' => $this->USERID
			,'moduleID' => 43
			,'time' => date("H:i:s A")
		));
		die( json_encode( array( 'success'=>true, 'match'=> $match ) ) );
	}

	function generatePDF() {
        $data = getData();

        $formDetails = json_decode( $data['form'], true );
        $receivingItems = json_decode( $data['receivingItems'], true );
        $journalEntries = json_decode( $data['journalEntries'], true );

        $header_fields = array(
            array(
                array(
                    'label' => 'Reference'
                    ,'value' => $formDetails['pdf_idReference'] . '-' .$formDetails['pdf_referenceNum']
				)
				,array(
                    'label' => 'Transferred to'
                    ,'value' => $formDetails['pdf_pCode']
				)
				,array(
                    'label' => 'Transferred by'
                    ,'value' => $formDetails['pdf_transferredBy']
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
                'header'        => 'Code'
                ,'dataIndex'    => 'barcode'
                ,'width'        => '10'
            ),
            array(
                'header'        => 'Item Name'
                ,'dataIndex'    => 'itemName'
                ,'width'        => '20'
            ),
            array(
                'header'        => 'Unit of Measure'
                ,'dataIndex'    => 'unitName'
                ,'width'        => '10'
            ),
            array(
                'header'        => 'Expiry Date'
                ,'dataIndex'    => 'date'
                ,'width'        => '12'
                ,'type'         => 'datecolumn'
                ,'format'       => 'm-d-Y'
            ),
            array(
                'header'        => 'Qty Transferred'
                ,'dataIndex'    => 'qtyTransferred'
                ,'width'        => '12'
                ,'type'         => 'numbercolumn'
				,'hasTotal'		=> true
            ),
            array(
                'header'        => 'Qty Received'
                ,'dataIndex'    => 'qtyReceived'
                ,'width'        => '12'
                ,'type'         => 'numbercolumn'
				,'hasTotal'		=> true
			),
            array(
                'header'        => 'Cost'
                ,'dataIndex'    => 'cost'
                ,'width'        => '12'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
				,'hasTotal'		=> true
			),
            array(
                'header'        => 'Amount'
                ,'dataIndex'    => 'totalAmount'
                ,'width'        => '12'
                ,'type'         => 'numbercolumn'
				,'format'       => '0,000.00'
				,'hasTotal'		=> true
            )
        );

        generateTcpdf(
			array(
				'file_name'         => $data['title']
                ,'folder_name'      => 'inventory'
                ,'header_fields'    => $header_fields
                ,'records'          =>  $receivingItems
                ,'header'           => $table
                ,'orientation'      => 'P'
                ,'params'           => $data
                ,'idAffiliate'      => $data['idAffiliate']
                ,'journalEntry'     => $journalEntries
                ,'hasPrintOption'   => $data['hasPrintOption']
                // ,'hasSignatories' => 1
			)
        );
    }
}

