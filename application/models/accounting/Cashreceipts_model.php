<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Mark Reynor D. Magriña
 * Module: Sales Module
 * Date: Dec 12, 2019
 * Finished: 
 * Description: 
 * DB Tables: 
 * */
class Cashreceipts_model extends CI_Model {

	function getGridListForPayments($rawData)
	{
		// these variables are shortcut var in select query
		$numOfOtherTag 			= 0;
		// $previousPayment		= " SUM( IFNULL( receipts.amount, 0 ) )";
		$receivables_noVAT		= " salesReceipts.balLeft + salesReceipts.discount";
		$receivables_withVAT	= " ( salesReceipts.balLeft - salesReceipts.vatAmount ) + salesReceipts.discount";
		$totalBalance			= " IF ( salesReceipts.vatType = 2 , ( " . $receivables_withVAT . " ) , ( " . $receivables_noVAT . " ) ) ";
		$ewtAmount				= " salesReceipts.balLeft * ( salesReceipts.ewtRate/100 )";
		$penaltyRate 			= " CASE
										WHEN salesReceipts.payMode IN ( 2 ) and salesReceipts.dueDate < CURRENT_DATE 
										THEN salesReceipts.penaltyRate
										ELSE 0
									END ";
		$penaltyAmount 			= " CASE
										WHEN salesReceipts.payMode IN ( 2 ) and salesReceipts.dueDate < CURRENT_DATE
										THEN ( salesReceipts.balLeft * ( salesReceipts.penaltyRate/100 ) )	
										ELSE 0
									END ";
			
			
		/**
		 * IF(), checks if the user wants to retrieve
		 * IF YES - retrieve sales transactions that has receipts 
		 * IF NO - retrieve all sales transactions tagged to the customer
		*		, ( ".$totalBalance." + SUM(IFNULL( receipts.amount, 0 )) ) AS sales
		 */						
		if ( isset( $rawData['idInvoice'] ) && (int)$rawData['idInvoice'] != 0 ) {
			$numOfOtherTag = $this->db->SELECT( "COUNT( * ) AS numOfotherTag " )
				->WHERE( 'receipts.idInvoice' , (int)$rawData['idInvoice'] )
				->WHERE( 'fIDModule != 18' )
				->GET( 'receipts' )->ROW()->numOfotherTag;
			$this->db->reset_query();

			$this->db->SELECT ( 
				" CONCAT( reference.code, ' - ', salesReceipts.referenceNum ) AS reference
				, DATE( salesReceipts.date ) AS date
				, salesReceipts.vatType
				, IF( SUM(receiptsCount)>1, 0, salesReceipts.vatAmount ) AS vatAmount
				, IF( SUM(receiptsCount)>1, 0, salesReceipts.discount  ) AS discount
				, IF( SUM(receiptsCount)>1, 0, salesReceipts.downPayment  ) AS down_payment
				, SUM(IFNULL( receipts.penaltyRate	, 0)) AS penaltyRate
				, SUM(IFNULL( receipts.penaltyAmount, 0)) AS penaltyAmount
				, SUM(IFNULL( receipts.ewtRate		, 0)) AS ewtRate 
				, SUM(IFNULL( receipts.ewtAmount	, 0)) AS ewtAmount
				, SUM(IFNULL( receipts.amount		, 0)) AS collections
				, IF( SUM(receiptsCount)>1
					, bal 
						+ ( SUM(IFNULL( receipts.amount ,0))+SUM(IFNULL( receipts.ewtAmount ,0))-SUM(IFNULL( receipts.penaltyAmount, 0)) ) 
						- ( SUM(IFNULL( previousReceipts.amount ,0))+SUM(IFNULL( previousReceipts.ewtAmount ,0))-SUM(IFNULL( previousReceipts.penaltyAmount, 0)) ) 
					, ( salesReceipts.amount+salesReceipts.discount-IF( salesReceipts.vatType=2,salesReceipts.vatAmount,0 ))
				) as sales
				, IF( SUM(receiptsCount)>1
					, bal 
						+ ( SUM(IFNULL( receipts.amount ,0))+SUM(IFNULL( receipts.ewtAmount ,0))-SUM(IFNULL( receipts.penaltyAmount, 0)) ) 
						- ( SUM(IFNULL( previousReceipts.amount ,0))+SUM(IFNULL( previousReceipts.ewtAmount ,0))-SUM(IFNULL( previousReceipts.penaltyAmount, 0)) ) 
					, bal
				) as receivables
				, IF( SUM(receiptsCount)>1
					, bal - ( SUM(IFNULL( previousReceipts.amount ,0))+SUM(IFNULL( previousReceipts.ewtAmount ,0))-SUM(IFNULL( previousReceipts.penaltyAmount, 0)) ) 
					, ( bal - SUM(IFNULL( receipts.amount ,0)) - SUM(IFNULL( receipts.ewtAmount ,0)) + SUM(IFNULL( receipts.penaltyAmount, 0)) )
				) as balance
				
				, salesReceipts.idInvoice as salesIDInvoice "
			);
			$this->db->JOIN(
				' ( SELECT * FROM receipts WHERE receipts.idInvoice = ' . $rawData['idInvoice'] . ' ) AS receipts'
				, 'receipts.fident = salesReceipts.idInvoice'
			);

			$this->db->JOIN(
				' ( SELECT 
						fident
						, SUM(IFNULL( receipts.amount	, 0)) AS amount
						, SUM(IFNULL( receipts.ewtAmount, 0)) AS ewtAmount
						, SUM(IFNULL( receipts.penaltyAmount, 0)) AS penaltyAmount 
					FROM receipts WHERE receipts.idInvoice <= ' . $rawData['idInvoice'] . '
				GROUP BY fident 
				) AS previousReceipts'
				, 'previousReceipts.fident = receipts.fident'
				, 'LEFT OUTER'
			);
			
			$this->db->JOIN( ' ( 
					SELECT count(*) AS receiptsCount, fident 
					FROM receipts
					WHERE idInvoice <= '.$rawData['idInvoice'].'
					GROUP BY fident	 
				) AS receiptsCount', 'receiptsCount.fident = receipts.fident', 'LEFT'
			);

		} else {
			/**
			 * IF($previousPayment), checks if there are previous collection
			 * IF YES - automatically compute all and place 0 on other columns and total it receivables and sales
			 * IF NO - retrieve details of all sales transaction 
			 */
			$this->db->SELECT( 
				" CONCAT( reference.code , ' - ' , salesReceipts.referenceNum ) AS reference
				, DATE( salesReceipts.date ) AS date
				, salesReceipts.vatType
				, IF( bal=balLeft, salesReceipts.vatAmount	, 0 ) AS vatAmount
				, IF( bal=balLeft, salesReceipts.discount	, 0 ) AS discount
				, IF( bal=balLeft, (".$penaltyRate.") 		, 0 ) AS penaltyRate
				, IF( bal=balLeft, (".$penaltyAmount.") 	, 0 ) AS penaltyAmount
				, IF( bal=balLeft, salesReceipts.ewtRate 	, 0 ) AS ewtRate
				, IF( bal=balLeft, salesReceipts.ewtAmount	, 0 ) AS ewtAmount
				, IF( bal=balLeft, salesReceipts.downPayment, 0 ) AS down_payment
				, IF( bal=balLeft
					, salesReceipts.balLeft
					, ( balLeft - SUM(IFNULL(receipts.ewtAmount,0)) + SUM(IFNULL(receipts.penaltyAmount,0)) )
				) AS receivables
				, IF( bal=balLeft
					, ( salesReceipts.balLeft+salesReceipts.discount- IF( salesReceipts.vatType=2,salesReceipts.vatAmount,0 ) + salesReceipts.downPayment )
					, ( balLeft - SUM(IFNULL(receipts.ewtAmount,0)) + SUM(IFNULL(receipts.penaltyAmount,0)) )
				) AS sales
				, IF( bal=balLeft
					, ( balLeft - salesReceipts.ewtAmount + (".$penaltyAmount.") )
					, ( balLeft - SUM(IFNULL(receipts.ewtAmount,0)) + SUM(IFNULL(receipts.penaltyAmount,0)) )
				) AS balance
				, 0 as collections
				, salesReceipts.idInvoice as salesIDInvoice
				, salesReceipts.idModule as fIDModule "
			);
			$this->db->JOIN(
				' ( SELECT 
						fident
						, SUM(IFNULL( receipts.amount	, 0)) AS amount
						, SUM(IFNULL( receipts.ewtAmount, 0)) AS ewtAmount
						, SUM(IFNULL( receipts.penaltyAmount, 0)) AS penaltyAmount 
					FROM receipts 
				GROUP BY fident 
				) AS receipts'
				, 'receipts.fident = salesReceipts.idInvoice'
				, 'LEFT OUTER'
			);
			$this->db->WHERE( "( balLeft - IFNULL(receipts.ewtAmount,0) + IFNULL(receipts.penaltyAmount,0) ) > 0" );
		}

		$this->db->JOIN( 'customer' , 'customer.idCustomer = salesReceipts.pCode AND salesReceipts.pType = 1' );
		$this->db->JOIN( 'reference' , 'reference.idReference = salesReceipts.idReference' );

		$this->db->WHERE( 'salesReceipts.idAffiliate' 	, $this->session->userdata('AFFILIATEID') );
		$this->db->WHERE( 'salesReceipts.pCode'			, (int)$rawData['idCustomer'] );
		$this->db->WHERE( 'salesReceipts.status' , 2 );
		$this->db->WHERE_NOT_IN( 'salesReceipts.archived' , 1 );
		$this->db->WHERE_NOT_IN( 'salesReceipts.cancelTag' , 1 );

		if ( ( isset( $rawData['otherTag'] ) && $rawData['otherTag'] == 'true' ) || $numOfOtherTag > 0 ) $this->db->WHERE_IN('salesReceipts.idModule', [ 18, 48, 58 ] );
		else $this->db->WHERE( 'salesReceipts.idModule' , 18 );

		$this->db->GROUP_BY( 'salesReceipts.idInvoice' );
		$this->db->ORDER_BY( 'salesReceipts.date' , 'DESC' );
		return $this->db->GET( 'invoices salesReceipts' )->result_array();  
	}
	
	function getCollectionDetails($rawData){
		$this->db->select(
			" idPostdated
			, idInvoice
			, paymentMethod
			, ( IF ( chequeNo > 0 , chequeNo , '' ) ) as chequeNo
			, ( CASE
				WHEN date = '0000-00-00' THEN NULL
				ELSE date
			END ) as date
			, amount,bankaccount.bankAccount as bankName,postdated.idBankAccount,bankaccount.sk
			,(CASE
				WHEN paymentMethod = 1 THEN 'Cash'
				ELSE 'Cheque'	
			END) as type
		");
		$this->db->from('postdated');
		$this->db->where('idInvoice',$rawData['idInvoice']);
		$this->db->join('bankaccount','bankaccount.idBankAccount = postdated.idBankAccount','left outer');
		return $this->db->get()->result_array();		
	}
	
	/*edit here*/
	function getBankAccountDetails($rawData){
		$this->db->select('idBankAccount, bankAccount, bankaccount.sk');
		$this->db->from('bankaccount');
		if( isset($rawData['query'] ) ) $this->db->like('bankAccount',$rawData['query'],'both');
		$this->db->order_by('bankAccount','asc');
		return $this->db->get()->result_array();
	}
	
	function checkExist($idInvoice){
		$this->db->select('idInvoice');
		$this->db->where('idInvoice',$idInvoice);
		$this->db->from('invoices');
		return $this->db->count_all_results();
	}
	function getReceipts($crInvoiceID){
		$this->db->select('receipt.*,invoices.balLeft');
		$this->db->where('receipt.idInvoice', $crInvoiceID);
		$this->db->from ('receipts as receipt');
		$this->db->join('invoices as invoices','invoices.idInvoice = receipt.fident','left outer');
		return $this->db->get()->result_array();
	}
	function viewAll($rawData){
		$this->db->select("
					invoices.idInvoice, DATE_FORMAT(invoices.date,'%Y-%m-%d') as date
					,CONCAT(reference.code, '-', invoices.referenceNum) as referenceNum
					,CONCAT(reference.code, '-', invoices.referenceNum) as name
					, invoices.amount
					,invoices.idInvoice as id
					,affiliate.affiliateName
					,costcenter.costCenterName
					,location.locationName
					,customer.name as customerName
					,customer.sk
					,employeePreparedBy.name as preparedByName
					,employeeNotedBy.name as notedByName
					,(CASE 
						WHEN invoices.status = 1 THEN 'Pending'
						WHEN invoices.status = 2 THEN 'Approved'
						ELSE 'Cancelled'
					END) as status
		");
		$this->db->from( 'invoices' );
		$this->db->join( 'affiliate','affiliate.idAffiliate = invoices.idAffiliate','left outer' );
		$this->db->join( 'costcenter','costcenter.idCostCenter = invoices.idCostCenter','left outer' );
		$this->db->join( 'reference','reference.idReference = invoices.idReference','left outer' );
		$this->db->join( 'location','location.idLocation = invoices.idLocation','left outer' );
		$this->db->join( 'customer','customer.idCustomer = invoices.pCode','left outer' );
		$this->db->join( 'eu as euPreparedBy','euPreparedBy.idEu = invoices.preparedBy','left outer' );
		$this->db->join( 'employee as employeePreparedBy','employeePreparedBy.idEmployee = euPreparedBy.idEmployee','left outer' );
		$this->db->join( 'eu as euNotedBy','euNotedBy.idEu = invoices.notedby','left outer' );
		$this->db->join( 'employee as employeeNotedBy','employeeNotedBy.idEmployee = euNotedBy.idEmployee','left outer' );
		$this->db->where('invoices.idModule',28);
		
		if( isset( $rawData['filterValue'] ) ) { 
			// echo 'Uli nami';
			$this->db->where( 'invoices.idInvoice', $rawData['filterValue']); 
		}
		
		// $this->db->order_by('invoices.idInvoice','desc');
		// $this->db->order_by('invoices.date','desc');
		$this->db->where_not_in('invoices.archived',1);
		$this->db->where( 'invoices.idAffiliate', $this->session->userdata('AFFILIATEID') ); 
		
		if( isset( $params['query'] ) ) $this->db->like("concat(reference.code, '-', invoices.referenceNum)", $params['query'], 'after');
		
		// to be uncommented when using the new database
		// return $this->db->get()->result_array();
		
		$rawData['db'] = $this->db;
		$rawData['order_by'] = 'invoices.date desc';
		return getGridList($rawData);
	}
	
	function getBalLeft($fident){
		$this->db->select('balLeft');
		$this->db->where('idInvoice',$fident);
		return $this->db->get('invoices')->row();
	}
	function updateBalLeft($fident, $newAmount){
		$this->db->set('balLeft', $newAmount);
		$this->db->where('idInvoice', $fident);
		$this->db->update('invoices');
	}
	function saveInvoice( $rawData ){
		$id						= (int)$rawData['idInvoice'];
		$rawData['date']		= $rawData['tdate'].' '.$rawData['ttime'];
		unset($rawData['idInvoice']);
		if ( (int)$rawData['onEdit'] == 0 ){
			$this->db->insert('invoices',unsetParams($rawData, 'invoices' ));
			return $this->db->insert_id();
		} else {
			$this->db->where('idInvoice',$id);
			$this->db->update( 'invoices', unsetParams( $rawData, 'invoices' ) );
		}
	}
	
	function saveCollectionDetails( $collectionDetails, $crInvoiceID ){
		// $this->db->insert_batch( 'receipts', $collectionDetails );
		$this->db->insert_batch( 'receipts', unsetParamsBatch( $collectionDetails, 'receipts') );
	}
	function savePaymentList( $paymentList, $crInvoiceID ){ 
		$this->db->insert_batch( 'postdated', unsetParamsBatch( $paymentList, 'postdated' ) ); 
	}
	function saveJournalDetails( $journalDetails,$crInvoiceID ){
		$this->db->delete( 'posting', array( 'idInvoice' => $crInvoiceID ) );
		$this->db->insert_batch( 'posting', unsetParamsBatch( $journalDetails, 'posting' ) );
	}
	function delReceiptsDetails($crInvoiceID){
		$this->db->delete( 'receipts', array( 'idInvoice' => $crInvoiceID ) );
	}
	function delPostdatedDetails($crInvoiceID){
		$this->db->delete( 'postdated', array( 'idInvoice' => $crInvoiceID ) );
	}
	
	function retrieveData( $idInvoice ){
		$this->db->select('*');
		$this->db->from('invoices');
		$this->db->where('idInvoice', $idInvoice);
		
		// to be uncommented when using the new database
		$this->db->where_not_in('invoices.archived',1);
		
		return $this->db->get()->result_array();
	}
	function deleteCashReceipt($idInvoice){
		$this->db->delete( 'invoices' , array( 'idInvoice' => $idInvoice));
	}
}