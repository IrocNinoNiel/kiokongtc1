<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Mark Reynor D. Magriña
 * Module: Disbursements Module
 * Date: Feb 10, 2020
 * Finished: 
 * Description: 
 * DB Tables: 
 * For Password Hashing, please use password_hash( https://www.php.net/manual/en/function.password-hash.php )
 * */
class Disbursements extends CI_Controller {

	public function __construct(){
        parent::__construct();
		$this->load->library( 'encryption' );
        setHeader( 'accounting/Disbursements_model' );
    }

	function saveStockTransferForm(){		
		$rawData = getData();
		$rawData['pType'] = 2;
		print_r($rawData);
		die();		
		
		$this->db->trans_begin();// firstline		
		$stInvoiceID = $this->model->saveInvoiceStockTransfer($rawData);
		
		/* Set the invoice id according to transaction state. */
		if( (int)$rawData['onEdit'] == 0 ) { $stInvoiceID = $stInvoiceID;}
		else{ $stInvoiceID = (int)$rawData['idInvoice']; }
		
		/* Prepare stock transfer details for batch saving */
		$gridItemList = json_decode( $rawData['gridItemList'] , true );
		for( $i=0; $i < count($gridItemList); $i++ ){
			$gridItemList[$i]['idInvoice'] = $stInvoiceID;
		}
		$this->model->saveGridItemList($gridItemList,$stInvoiceID);
		
		/* Set the journal details for batch saving */
		$journalDetails = json_decode( $rawData['journalDetails'],true );
		for( $i=0; $i < count($journalDetails); $i++ ){
			$journalDetails[$i]['idInvoice'] = $stInvoiceID;
		}
		$this->model->saveJournalDetails($journalDetails,$stInvoiceID);		
		/* Set Logs */
		if($rawData['onEdit'] == 0){ $actioLog = $this->USERNAME.' transferred stocks to '. $rawData['receiverAffiliate']; }
		else{ $actioLog = $this->USERNAME.' edited a transaction'; }
		setLogs( array(
			'actionLogDescription' => $actioLog
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
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
	
	function saveDisbursements(){
		$rawData = getData();
		$cancelledBy = 0; $hasJournal = 0;
        if ( $rawData['jeLength'] > 1 ) $hasJournal = 1;
		if ( $rawData['cancelTag'] != 0 ) $cancelledBy = $this->session->userdata('USERID');
		
		$rawData['preparedBy'] = $this->session->userdata('USERID');
		$rawData['idAffiliate'] = $this->session->userdata('AFFILIATEID');
		$rawData['idLocation'] = $this->session->userdata('LOCATIONID');
		$rawData['pType'] = 2;
		$rawData['pCode'] = $rawData['supplierCmb'];
		$rawData['cancelledBy'] = $cancelledBy;
		$rawData['hasJournal'] = $hasJournal;
		$rawData['amount'] = (float)$rawData['totalAmountDisbursed'] + (float)$rawData['discount'];
		$rawData['discount'] = (float)$rawData['discount'];
		$rawData['status'] = 2;
		
		
		$this->db->trans_begin();// firstline	
		$dsInvoiceID = $this->model->saveInvoiceDisbursements($rawData);
		
		/* Set the invoice ID according to transaction state */
		if ( (int)$rawData['onEdit'] == 0 ){ $dsInvoiceID = $dsInvoiceID; }
		else{ $dsInvoiceID = (int)$rawData['idInvoice']; }
		
		
		/* Return the amount to the balance left in invoices and then delete the related records */
		$getPayables = $this->model->getPayables($dsInvoiceID);
		foreach( $getPayables as $rs){
			$getBalLeft = $this->model->getBalLeft( $rs['fIdent'] );
			$balLeft = ($getBalLeft) ? (float)$getBalLeft : 0;
			$this->model->updateBalLeft( $rs['fIdent'], (float)$rs['paid'] + $balLeft );
		}
		
		/* Delete disbursement details in receipt and postdated tables */
		$this->model->delReceiptsDetails($dsInvoiceID);
		$this->model->delPostdatedDetails($dsInvoiceID);
		
		/* Prepare disbursement details for batch processing */
		$paidDetails = json_decode( $rawData['paidDetails'], true );
		if( !empty( $paidDetails ) ){
			for( $i=0; $i < count($paidDetails); $i++ ){
				$paidDetails[$i]['idInvoice'] = $dsInvoiceID;
				$paidDetails[$i]['idSupplier'] = $rawData['supplierCmb'];
				$paidDetails[$i]['amount'] = $paidDetails[$i]['paid'];
				$paidDetails[$i]['fIdent'] = $paidDetails[$i]['recIdInvoice'];
				$paidDetails[$i]['balance'] = ($paidDetails[$i]['payables']-$paidDetails[$i]['paid']);
				
				/* Get current updated balance left */
				$getBalLeft = $this->model->getBalLeft( $paidDetails[$i]['recIdInvoice'] );
				$balLeft = ($getBalLeft) ? (float)$getBalLeft : 0;
				/* To add validations incase the balance left is zero */
				// to later for the returned items where bal left is not enough to be deducted.
				
				/* Update the invoices balance left */
				$this->model->updateBalLeft( $paidDetails[$i]['recIdInvoice'], $balLeft - (float)$paidDetails[$i]['paid'] );
			}
			$this->model->saveDisbursementDetails( $paidDetails,$dsInvoiceID );
		}
		
		/* Prepare posdated details for batch saving */
		$paymentList = json_decode( $rawData['paymentList'], true);
		for( $i=0; $i < count($paymentList); $i++ ){
			$paymentList[$i]['idInvoice'] = $dsInvoiceID;
			$paymentList[$i]['paymentMethod'] = $paymentList[$i]['typeID'];
		}
		$this->model->savePaymentList( $paymentList,$dsInvoiceID );
		
		/* Prepare journal entries for batch saving */
		$journalDetails = json_decode( $rawData['journalDetails'], true );
		if( !empty($journalDetails) ){
			for( $i=0; $i < count($journalDetails); $i++ ){
				$journalDetails[$i]['idInvoice'] = $dsInvoiceID;
			}
			$this->model->saveJournalDetails($journalDetails,$crInvoiceID);
		}


		





		// Save	<username> added a new disbursement transaction
		// Edit	<username> edited a transaction
		// Delete	<username> deleted a transaction
		// Cancel	<username> cancelled a transaction
		// Confirm	<username> confirmed a disbursement transaction

		
		/* Set Logs */
		if($rawData['onEdit'] == 0){ $actioLog = $this->USERNAME.' added a new disbursement transaction.'; }
		else{ $actioLog = $this->USERNAME.' edited a transaction'; }
		setLogs( array(
			'actionLogDescription' => $actioLog
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
			,'idEu' => $this->USERID
			,'moduleID' => 45
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
	function getDisbursementDetails(){
		$rawData = getData();
		$record = $this->model->viewAll($rawData);

		$_viewHolder = $record;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['sk'] ) && !empty( $po['sk'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['sk'] )));
                $_viewHolder[$idx]['supplierName'] = $this->encryption->decrypt( $po['supplierName'] );
            }
        }
		$record = $_viewHolder;

		die( json_encode( array( 'success'=>true, 'view'=>$record ) ) );
	}
	function getBankDetails(){
		$rawData = getData();
		$record = $this->model->getBankDetails($rawData);

		$_viewHolder = $record;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['sk'] ) && !empty( $po['sk'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['sk'] )));
                $_viewHolder[$idx]['bankName'] = $this->encryption->decrypt( $po['bankName'] );
            }
        }
		$record = $_viewHolder;

		die( json_encode( array( 'success'=>true, 'view'=> $record ) ) );
	}
	function getSupplier(){
		$rawData = getData();
		$record = $this->model->getSupplier($rawData);
		$record = decryptSupplier( $record );
		die( json_encode( array('success'=> true, 'view'=>$record ) ) );
	}
	function getPayablesList(){
		$rawData = getData();
		$record = $this->model->getPayablesList($rawData);
		die( json_encode( array( 'success'=>true, 'view'=>$record ) ) );
	}
	function getCollectionDetails(){
		$rawData = getData();
		$record = $this->model->getCollectionDetails($rawData);
		$_viewHolder = $record;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['sk'] ) && !empty( $po['sk'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['sk'] )));
                $_viewHolder[$idx]['bankName'] = $this->encryption->decrypt( $po['bankName'] );
            }
        }
		$record = $_viewHolder;
		die( json_encode( array( 'success'=>true, 'view'=>$record ) ) );
	}
	function retrieveData(){
		$rawData = getData();
		$match = 0;
		$idInvoice = $rawData['id'];
		$record = $this->model->retrieveData($idInvoice);
		die( json_encode( array( 'success'=>true, 'match'=> $match, 'view'=> $record ) ) );
	}
	function deleteDisbursementRecord(){
		$rawData = getData();
		$match = 0;
		
		$getPayables = $this->model->getPayables($rawData['idInvoice']);
		foreach( $getPayables as $rs){
			$getBalLeft = $this->model->getBalLeft($rs['fIdent']);
			$balLeft = ($getBalLeft) ? (float)$getBalLeft : 0;
			$this->model->updateBalLeft( $rs['fIdent'],(float)$rs['paid'] + $balLeft );
		}		
		$this->model->deleteDisbursement($rawData['idInvoice']);
		$this->model->delReceiptsDetails($rawData['idInvoice']);
		$this->model->delPostdatedDetails($rawData['idInvoice']);
		
		setLogs( array(
			'actionLogDescription' => $this->USERNAME.' deleted a transaction'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
			,'idEu' => $this->USERID
			,'moduleID' => 45
			,'time' => date("H:i:s A")
		));
		die( json_encode( array( 'success'=>true, 'match'=> $record ) ) );
	}

	public function generatePDF(){
        $params = getData();
        $datarec	= $this->standards->gridJournalEntry( $params );
		$details	= json_decode( $params['details'], true );
		$collection	= json_decode( $params['collection'], true );

        $head = '<table style="font-size:9;"><tr><td><table>
            <tr><td>Reference:</td><td>'.$params['pdf_idReference'] . '-' .$params['pdf_referenceNum'].'</td></tr>
            <tr><td>Cost Center:</td><td>'.$params['pdf_idCostCenter'].'</td></tr>
            <tr><td>Customer Name:</td><td>'.$params['pdf_supplierCmb'].'</td></tr>
            <tr><td>Credit Limit:</td><td>'.number_format( $params['pdf_creditLimit'], 2 ).'</td></tr>
        </table></td>
        <td><table>
            <tr><td>Date:</td><td>'.date( 'm/d/Y', strtotime( $params['pdf_tdate'] ) ) . ' ' . date( 'h:i A', strtotime( $params['pdf_ttime'] ) ).'</td></tr>
            <tr><td>Remarks:</td><td>'.$params['pdf_remarks'].'</td></tr>
        </table></td></tr></table><br>';
		
        $header = [
            'title' => ''
            ,'file_name' => 'Disbursement Form'
            ,'folder_name' => 'pdf/accounting/'
            ,'addPage' => true
            ,'table_title' => 'Details'
            ,'orientation' => 'P'
        ];

        $col_sales = [
            $this->pdfColFormat('Reference' , 'reference'   , '10%'),
            $this->pdfColFormat('Date'      , 'date'        , '10%', 'datecolumn'),
            $this->pdfColFormat('Receiving'	, 'receiving'	, '10%', 'numbercolumn'),
            $this->pdfColFormat('VAT'       , 'vat'   		, '10%', 'numbercolumn'),
            $this->pdfColFormat('Discount'  , 'discount'    , '10%', 'numbercolumn'),
            $this->pdfColFormat('Down Payment',	'down_payment', '10%', 'numbercolumn'),
            $this->pdfColFormat('Payables'	, 'payables'	, '10%', 'numbercolumn'),
            $this->pdfColFormat('EWT'		, 'ewtAmount'	, '10%', 'numbercolumn'),
            $this->pdfColFormat('Paid'   	, 'paid'		, '10%', 'numbercolumn'),
            $this->pdfColFormat('Balance'	, 'balance'     , '10%', 'numbercolumn')
        ];

        array_push($details, [
            'reference'     => ''
            ,'date'         => ''
            ,'receiving'	=> array_sum(array_column($details, 'receiving'))
            ,'vat'    		=> array_sum(array_column($details, 'vat'))
            ,'discount'     => array_sum(array_column($details, 'discount'))
            ,'down_payment' => array_sum(array_column($details, 'down_payment'))
            ,'payables'		=> array_sum(array_column($details, 'payables'))
            ,'ewtAmount'    => ''
            ,'paid'			=> array_sum(array_column($details, 'paid'))
            ,'balance'      => array_sum(array_column($details, 'balance'))
        ]);

        $col_return = [
            $this->pdfColFormat('Type'      	, 'type'	, '20%'),
            $this->pdfColFormat('Bank Account'	, 'bankName', '20%'),
            $this->pdfColFormat('Effectivity Date', 'date'	, '20%', 'datecolumn'),
            $this->pdfColFormat('Check #'   	, 'chequeNo', '20%'),
            $this->pdfColFormat('Amount'    	, 'amount'	, '20%', 'numbercolumn'),
        ];
        
        array_push($collection, [
             'type'     => ''
            ,'bankName' => ''
            ,'chequeNo' => ''
            ,'date'     => ''
            ,'amount'   => array_sum(array_column($collection, 'amount'))
        ]);

        $head .= generate_table_as_string($header, $col_sales , $details);
        $header['table_title'] = 'Payment Details';
        $head .= generate_table_as_string($header, $col_return , $collection);
        $header['table_hidden'] = TRUE;

        setLogs(
            array(
                'actionLogDescription'  => $this->USERFULLNAME . ' Exported the generated Cash Receipt Form (PDF)'
                ,'idAffiliate'		    => $this->session->userdata('AFFILIATEID')
                ,'idEu'                 => $this->USERID
                ,'moduleID'             => 43
                ,'time'                 => date('H:i:s A')
            )
        );
		generate_table( $header, 0, [], $head);
	}

	private function pdfColFormat($header, $dataIndex, $width = false, $type=false){
        $return = [
            'header' => $header,
            'data_index' => $dataIndex
        ];
        if($width != false) $return['width'] = $width;
        if($type != false) $return['type'] = $type;
        return $return;
        
	}
	
	function customListPDF(){
		$params = getData();

		$table = array(
			array(
				'header'        => 'Date'
				,'dataIndex'    => 'date'
				,'width'        => '25'
			), 
			array( 
				'header'        => 'Reference'
				,'dataIndex'    => 'referenceNum'
				,'width'        => '25'
			), 
			array( 
				'header'        => 'Supplier Name'
				,'dataIndex'    => 'supplierName'
				,'width'        => '25'
			), 
			array( 
				'header'        => 'Amount'
				,'dataIndex'    => 'amount'
				,'width'        => '25'
				,'type'         => 'numbercolumn'
				,'format'       => '0,000.00'
			)
		);

		generateTcpdf(
			array(
				'file_name'         => 'Disbursements List'
				,'folder_name'      => 'accounting'
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

		/**Custom Decryption for Purchase Order**/
		$_viewHolder = $view;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['sk'] ) && !empty( $po['sk'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['sk'] )));
                $_viewHolder[$idx]['supplierName'] = $this->encryption->decrypt( $po['supplierName'] );
            }
        }
		$view = $_viewHolder;
		
		$csvarray[] = array( 'title' => $data['pageTitle'].'' );
		$csvarray[] = array( 'space' => '' );
		$csvarray[] = array( 'space' => '' );

		$csvarray[] = array(
			'col1'  => 'Date'
			,'col2' => 'Reference'
			,'col3' => 'Name'
			,'col4' => 'Amount'
		);

		foreach( $view as $value ){
			$csvarray[] = array(
				'col1' => $value[ 'date' ]
				,'col2' => $value[ 'referenceNum' ]
				,'col3' => $value[ 'supplierName' ]
				,'col4' => $value[ 'amount' ]
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
				,'directory' => 'accounting'
			)
		);
	}

	function download($title){
		force_download(
			array(
				'title' => $title
				,'directory' => 'accounting'
			)
		);
	}
}