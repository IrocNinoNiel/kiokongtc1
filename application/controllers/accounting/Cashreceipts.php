<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Mark Reynor D. Magriña
 * Module: Cashreceipts Module
 * Date: Dec 12, 2019
 * Finished: 
 * Description: 
 * DB Tables: 
 * For Password Hashing, please use password_hash( https://www.php.net/manual/en/function.password-hash.php )
 * */
class Cashreceipts extends CI_Controller {

	public function __construct(){
        parent::__construct();
		$this->load->library( 'encryption' );
        setHeader( 'accounting/Cashreceipts_model' );
    }
	
	function getGridListForPayments(){
		$rawData = getData();
		$record = $this->model->getGridListForPayments( $rawData ) ;
		die( json_encode( array( 'success'=>true, 'view'=> $record ) ) );
	}
		
	function getCollectionDetails(){
		$rawData = getData();
		$idInvoice = $rawData['idInvoice'];
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
	
	function getBankAccountDetails(){
		$rawData = getData();
		$record = $this->model->getBankAccountDetails($rawData);

		$_viewHolder = $record;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['sk'] ) && !empty( $po['sk'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['sk'] )));
                $_viewHolder[$idx]['bankAccount'] = $this->encryption->decrypt( $po['bankAccount'] );
            }
        }
		$record = $_viewHolder;
		
		die( json_encode( array( 'success'=>true, 'view'=> $record ) ) );
	}
	function getCashReceiptDetails(){
		$rawData = getData();
		$record = $this->model->viewAll($rawData);
		// $record['view'] = decryptCustomer( $record['view'] );
		
		$_viewHolder = $record['view'];
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['sk'] ) && !empty( $po['sk'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['sk'] )));
                $_viewHolder[$idx]['customerName'] = $this->encryption->decrypt( $po['customerName'] );
            }
        }
		$record['view'] = $_viewHolder;
		
		die( json_encode( array( 'success'=> true, 'total'=> $record['count'], 'view'=> $record['view'] ) ) );
		
	}
	
	function saveCashReceiptsForm(){
		$rawData = getData();
		$cancelledBy = 0; $hasJournal = 0;
        if ( $rawData['jeLength'] > 1 ) $hasJournal = 1;
		if ( $rawData['cancelTag'] != 0 ) $cancelledBy = $this->session->userdata('USERID');

		// $edited = (int)$rawData['onEdit'];
		$rawData['preparedBy'] = $this->session->userdata('USERID');
		$rawData['idAffiliate'] = $this->session->userdata('AFFILIATEID');
		$rawData['idLocation'] = $this->session->userdata('LOCATIONID');
		$rawData['pType'] = 1;
		$rawData['pCode'] = $rawData['customer'];
		$rawData['cancelledBy'] = $cancelledBy;
		$rawData['hasJournal'] = $hasJournal;
		$rawData['amount'] = (float)$rawData['totalAmountCollected'] + (float)$rawData['discount'];
		$rawData['discount'] = (float)$rawData['discount'];
		$rawData['status'] = 2;
		// $rawData['idCostCenter'] = $this->session->userdata('idCostCenter');
		
		/*   Validation */
		if( $rawData['onEdit'] != 0 ){
			$checkExist = $this->model->checkExist( $rawData['idInvoice'] );
			if ( $checkExist == 0 ) die( json_encode( array( 'success'=> true, 'match'=> 2 ) ) );
		}
		
		$this->db->trans_begin();// firstline
		$crInvoiceID = $this->model->saveInvoice($rawData);
		
		// echo 'abot ko dre after invoice save';		
		if( (int)$rawData['onEdit'] == 0 ) { $crInvoiceID = $crInvoiceID;}
		else{ $crInvoiceID = (int)$rawData['idInvoice']; }
		
		/* Return the amount to the balance left in invoices and then delete the related records */
		$getReceipts = $this->model->getReceipts($crInvoiceID);
		foreach( $getReceipts as $rs){
			$getBalLeft = $this->model->getBalLeft($rs['fident']);
			$balLeft = ($getBalLeft) ? (float)$getBalLeft->balLeft : 0;
			$this->model->updateBalLeft( $rs['fident'],(float)$rs['amount'] + $balLeft );
		}
		
		/* Delete cash receipt details in receipt and postdated tables */
		$this->model->delReceiptsDetails($crInvoiceID);
		$this->model->delPostdatedDetails($crInvoiceID);
		
		
		/* Prepare receipt details for batch saving */
		$collectionDetails = json_decode( $rawData['collectionDetails'], true );
		if(!empty( $collectionDetails )){
			for( $i=0; $i < count($collectionDetails); $i++ ){
				$collectionDetails[$i]['idInvoice'] = $crInvoiceID;
				$collectionDetails[$i]['idCustomer'] = $rawData['customer'];
				$collectionDetails[$i]['amount'] = $collectionDetails[$i]['collections'];
				$collectionDetails[$i]['fident'] = $collectionDetails[$i]['salesIDInvoice'];
				// $collectionDetails[$i]['fIDModule'] = $rawData['idmodule'];
				unset($collectionDetails[$i]['balance']);
				unset($collectionDetails[$i]['collections']);
				unset($collectionDetails[$i]['receivables']);
				unset($collectionDetails[$i]['reference']);
				unset($collectionDetails[$i]['date']);
				unset($collectionDetails[$i]['selected']);
				
				/* Get current updated balance left */
				$getBalLeft = $this->model->getBalLeft( $collectionDetails[$i]['salesIDInvoice'] );
				$balLeft = ($getBalLeft) ? (float)$getBalLeft->balLeft : 0;
				/* To add validations incase the balance left is zero */
				// to later for the returned items where bal left is not enough to be deducted.
				
				/* Update the invoices balance left */
				$this->model->updateBalLeft( $collectionDetails[$i]['salesIDInvoice'], $balLeft - (float)$collectionDetails[$i]['amount'] );
				unset($collectionDetails[$i]['salesIDInvoice']);
			}
			$this->model->saveCollectionDetails($collectionDetails,$crInvoiceID);
		}
		
		/* Prepare postdated details for batch saving */
		$paymentList = json_decode( $rawData['paymentList'], true );
		for( $i=0; $i < count($paymentList); $i++ ){
			
			$paymentList[$i]['idInvoice'] = $crInvoiceID;
			$paymentList[$i]['paymentMethod'] = $paymentList[$i]['typeID'];
			$paymentList[$i]['status'] = 1;
			unset($paymentList[$i]['type']);
			unset($paymentList[$i]['typeID']);
			unset($paymentList[$i]['bankName']);
			unset($paymentList[$i]['selected']);
		}
		$this->model->savePaymentList($paymentList,$crInvoiceID);
		
		/* Prepare journal entries for batch saving */
		$journalDetails = json_decode( $rawData['journalDetails'], true );
		if( !empty($journalDetails) ){
			for( $i=0; $i < count($journalDetails); $i++ ){
				$journalDetails[$i]['idInvoice'] = $crInvoiceID;
			}
			$this->model->saveJournalDetails($journalDetails,$crInvoiceID);
		}
		
		/* Set Logs */
		if($rawData['onEdit'] == 0){ $actioLog = $this->USERNAME.' added a new cash Receipt transaction'; }
		else{ $actioLog = $this->USERNAME.' edited a transaction'; }	
		setLogs( array(
			'actionLogDescription' => $actioLog
			,'idEu' 	=> $this->USERID
			,'moduleID' => 28
			,'time' 	=> date( "H:i:s A" )
			,'idAffiliate' => $this->AFFILIATEID
		));
		
		if( $this->db->trans_status() === FALSE ){
			$this->db->trans_rollback(); // rollback
		}
		else{
			$this->db->trans_commit(); // submit changes
		}
		
		die( json_encode( array( 'success'=>$this->db->trans_status(), 'match'=>0 ) ) );
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

	public function generatePDF(){
        $params = getData();
        $datarec	= $this->standards->gridJournalEntry( $params );
		$details	= json_decode( $params['details'], true );
		$collection	= json_decode( $params['collection'], true );
        $overall_net_sales = 0;


        $head = '<table style="font-size:9;"><tr><td><table>
            <tr><td>Reference:</td><td>'.$params['pdf_idReference'] . '-' .$params['pdf_referenceNum'].'</td></tr>
            <tr><td>Cost Center:</td><td>'.$params['pdf_idCostCenter'].'</td></tr>
            <tr><td>Customer Name:</td><td>'.$params['pdf_customer'].'</td></tr>
            <tr><td>Credit Limit:</td><td>'.number_format( $params['pdf_creditLimit'], 2 ).'</td></tr>
        </table></td>
        <td><table>
            <tr><td>Date:</td><td>'.date( 'm/d/Y', strtotime( $params['pdf_tdate'] ) ) . ' ' . date( 'h:i A', strtotime( $params['pdf_ttime'] ) ).'</td></tr>
            <tr><td>Remarks:</td><td>'.$params['pdf_remarks'].'</td></tr>
        </table></td></tr></table><br>';
		
        $header = [
            'title' => ''
            ,'file_name' => 'Cash Receipt Form'
            ,'folder_name' => 'pdf/accounting/'
            ,'addPage' => true
            ,'table_title' => 'Details'
            ,'orientation' => 'P'
        ];

        $col_sales = [
            $this->pdfColFormat('Reference' , 'reference'   , '9%'),
            $this->pdfColFormat('Date'      , 'date'        , '9%', 'datecolumn'),
            $this->pdfColFormat('Sales'     , 'sales'       , '10%', 'numbercolumn'),
            $this->pdfColFormat('VAT'       , 'vatAmount'   , '9%', 'numbercolumn'),
            $this->pdfColFormat('Discount'  , 'discount'    , '9%', 'numbercolumn'),
            $this->pdfColFormat('Down Payment'  , 'down_payment'    , '9%', 'numbercolumn'),
            $this->pdfColFormat('Receivables'   , 'receivables'     , '9%', 'numbercolumn'),
            $this->pdfColFormat('Penalty'       , 'penaltyAmount'   , '9%', 'numbercolumn'),
            $this->pdfColFormat('EWT'           , 'ewtAmount'       , '9%', 'numbercolumn'),
            $this->pdfColFormat('Collections'   , 'collections'     , '9%', 'numbercolumn'),
            $this->pdfColFormat('Balance'       , 'balance'         , '9%', 'numbercolumn')
        ];

        array_push($details, [
            'reference'     => ''
            ,'date'         => ''
            ,'sales'        => array_sum(array_column($details, 'sales'))
            ,'vatAmount'    => array_sum(array_column($details, 'vatAmount'))
            ,'discount'     => array_sum(array_column($details, 'discount'))
            ,'down_payment' => array_sum(array_column($details, 'down_payment'))
            ,'receivables'  => array_sum(array_column($details, 'receivables'))
            ,'penaltyAmount'=> ''
            ,'ewtAmount'    => ''
            ,'collections'  => array_sum(array_column($details, 'collections'))
            ,'balance'      => array_sum(array_column($details, 'balance'))
        ]);

        $col_return = [
            $this->pdfColFormat('Type'      , 'type'        , '20%'),
            $this->pdfColFormat('Bank'      , 'bankName'    , '20%'),
            $this->pdfColFormat('Check #'   , 'chequeNo'    , '20%'),
            $this->pdfColFormat('Effectivity Date', 'date'  , '20%', 'datecolumn'),
            $this->pdfColFormat('Amount'    , 'amount'      , '20%', 'numbercolumn'),
        ];
        
        array_push($collection, [
             'type'     => ''
            ,'bankName' => ''
            ,'chequeNo' => ''
            ,'date'     => ''
            ,'amount'   => array_sum(array_column($collection, 'amount'))
        ]);

        $head .= generate_table_as_string($header, $col_sales , $details);
        $header['table_title'] = 'Collection Details';
        $head .= generate_table_as_string($header, $col_return , $collection);
        $header['table_hidden'] = TRUE;

        setLogs(
            array(
                'actionLogDescription'  => $this->USERFULLNAME . ' Exported the generated Cash Receipt Form (PDF)'
                ,'idAffiliate'		    => $this->session->userdata('AFFILIATEID')
                ,'idEu'                 => $this->USERID
                ,'moduleID'             => 28
                ,'time'                 => date('H:i:s A')
            )
        );
		generate_table( $header, 0, [], $head);
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
				'header'        => 'Customer Name'
				,'dataIndex'    => 'customerName'
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
				'file_name'         => 'Cash Receipts List'
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
		$_viewHolder = $view['view'];
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['sk'] ) && !empty( $po['sk'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['sk'] )));
                $_viewHolder[$idx]['customerName'] = $this->encryption->decrypt( $po['customerName'] );
            }
        }
		$view = $_viewHolder;
		
		$csvarray[] = array( 'title' => $data['pageTitle'].'' );
		$csvarray[] = array( 'space' => '' );
		$csvarray[] = array( 'space' => '' );

		$csvarray[] = array(
			'col1'  => 'Date'
			,'col2' => 'Reference'
			,'col3' => 'Customer'
			,'col4' => 'Amount'
		);

		foreach( $view as $value ){
			$csvarray[] = array(
				'col1' => $value[ 'date' ]
				,'col2' => $value[ 'referenceNum' ]
				,'col3' => $value[ 'customerName' ]
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
	
	function deleteCashReceiptRecord(){
		$rawData = getData();
		$match = 0;
		
		/* Return the amount to the balance left in invoices and then delete the related records */
		$getReceipts = $this->model->getReceipts($rawData['idInvoice']);
		foreach( $getReceipts as $rs){
			$getBalLeft = $this->model->getBalLeft($rs['fident']);
			$balLeft = ($getBalLeft) ? (float)$getBalLeft->balLeft : 0;
			$this->model->updateBalLeft( $rs['fident'],(float)$rs['amount'] + $balLeft );
		}		
		
		$this->model->deleteCashReceipt($rawData['idInvoice']);
		$this->model->delReceiptsDetails($rawData['idInvoice']);
		$this->model->delPostdatedDetails($rawData['idInvoice']);
		
		setLogs( array(
			'actionLogDescription' => $this->USERNAME.' deleted a transaction'
			,'idEu' => $this->USERID
			,'moduleID' => 28
			,'time' => date("H:i:s A")
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
		));
		die( json_encode( array( 'success'=>true, 'match'=> $match ) ) );
	}
	
	function retrieveData(){
		$rawData = getData();
		$match = 0;
		$idInvoice = $rawData['id'];
		$record = $this->model->retrieveData($idInvoice);
		die( json_encode( array( 'success'=>true, 'match'=> $match, 'view'=> $record ) ) );
	}
	

}