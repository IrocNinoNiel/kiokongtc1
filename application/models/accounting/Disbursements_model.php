<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Mark Reynor D. Magriña
 * Module: Disbursements Module
 * Date: Feb 10, 2020
 * Finished: 
 * Description: 
 * DB Tables: 
 * */
class Disbursements_model extends CI_Model {
	function getPayablesList( $rawData ){
		/* Edited or saved cash receipts statments here */
		if( isset( $rawData['idInvoice'] ) && (int)$rawData['idInvoice'] != 0 ){
			$this->db->SELECT(
				" receivingInvoice.idInvoice AS recIdInvoice
				, CONCAT( reference.code , ' - ' , receivingInvoice.referenceNum ) as reference
				, DATE( receivingInvoice.date ) as date
				, receivingInvoice.vatType
				, IF( SUM(disbCount) > 1, 0, receivingInvoice.vatAmount ) AS vat
				, IF( SUM(disbCount) > 1, 0, receivingInvoice.discount ) AS discount
				, IF( SUM(disbCount) > 1, 0, receivingInvoice.downPayment ) AS down_payment
				, SUM(disbursements.ewtRate) AS ewtRate    
				, SUM(disbursements.ewtAmount) AS ewtAmount
				, SUM(IFNULL(disbursements.paid, 0)) AS paid

				, IF( SUM(disbCount) > 1
					, ( SUM(IFNULL(disbursements.balance, 0)) + SUM(IFNULL(disbursements.paid, 0)) )
					, ( bal + receivingInvoice.discount - IF(receivingInvoice.vatType=2,vatAmount,0) + receivingInvoice.downPayment )
				) AS receiving
				, IF( SUM(disbCount) > 1
					, ( SUM(IFNULL(disbursements.balance, 0)) + SUM(IFNULL(disbursements.paid, 0)) )
					, receivingInvoice.bal
				) AS payables
				, ( SUM(IFNULL(disbursements.balance, 0)) - SUM(IFNULL(disbursements.ewtAmount, 0)) ) AS balance "
			);

			$this->db->JOIN(
				' ( SELECT * FROM disbursements WHERE disbursements.idInvoice = ' . $rawData['idInvoice'] . ' ) AS disbursements'
				, 'disbursements.fIdent = receivingInvoice.idInvoice'
			);

			$this->db->JOIN( ' ( 
					SELECT count(*) AS disbCount, fIdent 
					FROM disbursements
					WHERE idInvoice <= '.$rawData['idInvoice'].'
					GROUP BY fIdent	 
				) AS disbCount', 'disbCount.fIdent = disbursements.fIdent', 'LEFT'
			);
		}else{
			$this->db->SELECT(
				" receivingInvoice.idInvoice AS recIdInvoice
				, CONCAT( reference.code , ' - ' , receivingInvoice.referenceNum ) as reference
				, DATE( receivingInvoice.date ) as date

				, IF( bal>balLeft 
					, ( balLeft - SUM(IFNULL(disbursements.ewtAmount,0)) )
					, ( balLeft + receivingInvoice.discount + receivingInvoice.downPayment - IF( receivingInvoice.vatType=2, receivingInvoice.vatAmount, 0 )  ) 
				) AS receiving

				, receivingInvoice.vatType
				, IF(bal>balLeft, 0, receivingInvoice.vatAmount) AS vat
				, IF(bal>balLeft, 0, receivingInvoice.discount) AS discount
				, IF(bal>balLeft, 0, receivingInvoice.downPayment) AS down_payment

				, IF( bal>balLeft 
					, ( balLeft - SUM(IFNULL(disbursements.ewtAmount,0)) )
					, balLeft
				) AS payables

				, IF(bal>balLeft, 0, receivingInvoice.ewtRate) AS ewtRate    
				, IF(bal>balLeft, 0, (balLeft * (ewtRate/100))) AS ewtAmount    
				, 0 AS paid

				, IF( bal>balLeft 
					, ( balLeft - SUM(IFNULL(disbursements.ewtAmount,0)) )
					, ( balLeft - (balLeft*(ewtRate/100)) )
				) AS balance "
			);
			$this->db->JOIN(
				' ( SELECT 
						fIdent
						, SUM(IFNULL( disbursements.paid	, 0)) AS amount
						, SUM(IFNULL( disbursements.ewtAmount, 0)) AS ewtAmount
					FROM disbursements 
				GROUP BY fIdent 
				) AS disbursements'
				, 'disbursements.fIdent = receivingInvoice.idInvoice'
				, 'LEFT OUTER'
			);
			$this->db->WHERE( '( balLeft - IFNULL(disbursements.ewtAmount,0) ) > 0' );
		}

		$this->db->JOIN( 'reference'	, 'reference.idReference = receivingInvoice.idReference' );
		$this->db->JOIN( 'supplier'		, 'supplier.idSupplier = receivingInvoice.pCode AND pType = 2' );
		
		$this->db->WHERE_IN( 'receivingInvoice.idAffiliate' , $this->session->userdata('AFFILIATEID') );
		$this->db->WHERE_IN( 'receivingInvoice.idModule' , 25 );
		$this->db->WHERE_NOT_IN( 'receivingInvoice.archived' , 1 );
		$this->db->WHERE_NOT_IN( 'receivingInvoice.cancelTag' , 1 );
		$this->db->WHERE( 'receivingInvoice.pCode' , (int)$rawData['idSupplier'] );

		$this->db->GROUP_BY( 'receivingInvoice.idInvoice' );
		$this->db->ORDER_BY( 'receivingInvoice.date DESC , receivingInvoice.idInvoice DESC');
		
		return $this->db->GET( 'invoices receivingInvoice' )->RESULT_ARRAY();
	}
	
	
	function getItems( $params ){
        if( isset( $params['idAffiliate']) ) {
            $qty = ( isset($params['qty']) ? $params['qty'] : 1 );
            $this->db->select(' item.idItem,itemName,itemclassification.className,barcode,unit.unitName, 
                                itemPrice as cost, ' . $qty . ' as qty, 
                                ( itemPrice * ' . $qty . ' ) as amount ');
            $this->db->where('idAffiliate', $params['idAffiliate']);
            $this->db->join('itemclassification', 'item.idItemClass = itemclassification.idItemClass', 'LEFT');
            $this->db->join('unit', 'item.idUnit = unit.idUnit', 'LEFT');
            $this->db->join('itemaffiliate', 'item.idItem = itemaffiliate.idItem', 'LEFT');
            return $this->db->get('item')->result_array();
        }
    }
	function getSupplier($rawData){
		$this->db->distinct();
		$this->db->select('supplier.idSupplier as id, supplier.name, creditLimit, sk');
		$this->db->order_by('name asc');
		$this->db->join('supplieraffiliate','supplieraffiliate.idSupplier = supplier.idSupplier','left outer');
		$this->db->where('idAffiliate', $this->session->userdata('AFFILIATEID') );
		return $this->db->get( 'supplier' )->result_array();
	}
	function getPayables($dsInvoiceID){
		$this->db->select('disbursement.*,invoices.balLeft');
		$this->db->where('disbursement.idInvoice', $dsInvoiceID);
		$this->db->from ('disbursements as disbursement');
		$this->db->join('invoices as invoices','invoices.idInvoice = disbursement.fIdent','left outer');
		return $this->db->get()->result_array();
	}
	function viewAll( $rawData ){
		$this->db->select(
			" invoices.idInvoice, DATE_FORMAT(invoices.date,'%Y-%m-%d') as date
			, CONCAT(reference.code, '-', invoices.referenceNum) as referenceNum
			, CONCAT(reference.code, '-', invoices.referenceNum) as name
			, invoices.idInvoice as id
			, invoices.amount
			, affiliate.affiliateName
			, costcenter.costCenterName
			, supplier.name as supplierName
			, supplier.sk
			, employeePreparedBy.name as preparedByName
			, employeeNotedBy.name as notedByName
			, (CASE 
				WHEN invoices.status = 1 THEN 'Pending'
				WHEN invoices.status = 2 THEN 'Approved'
				ELSE 'Cancelled'
			END) as status"
		);
		$this->db->from( 'invoices' );
		$this->db->join( 'affiliate','affiliate.idAffiliate = invoices.idAffiliate','left outer' );
		$this->db->join( 'costcenter','costcenter.idCostCenter = invoices.idCostCenter','left outer' );
		$this->db->join( 'reference','reference.idReference = invoices.idReference','left outer' );
		$this->db->join( 'supplier','supplier.idSupplier = invoices.pCode','left outer' );
		$this->db->join( 'eu as euPreparedBy','euPreparedBy.idEu = invoices.preparedBy','left outer' );
		$this->db->join( 'employee as employeePreparedBy','employeePreparedBy.idEmployee = euPreparedBy.idEmployee','left outer' );
		$this->db->join( 'eu as euNotedBy','euNotedBy.idEu = invoices.notedby','left outer' );
		$this->db->join( 'employee as employeeNotedBy','employeeNotedBy.idEmployee = euNotedBy.idEmployee','left outer' );
		$this->db->where('invoices.idModule',45);
		$this->db->order_by('invoices.date','desc');
		$this->db->order_by('invoices.idInvoice','desc');
		$this->db->where_not_in('invoices.archived',1);
		
		if( isset( $rawData['filterValue'] ) ) { 
			$this->db->where( 'invoices.idInvoice	', $rawData['filterValue']); 
		}

		// to be uncommented when using the new database
		
		return $this->db->get()->result_array();
	}
	function getBankDetails($rawData){
		$this->db->select('idBankAccount as idBank , bankAccount as bankName , bankaccount.sk');
		$this->db->from('bankaccount');
		if( isset($rawData['query'] ) ) $this->db->like('bankAccount',$rawData['query'],'both');
		$this->db->order_by('bankAccount','asc');
		return $this->db->get()->result_array();
	}
	function getCollectionDetails( $rawData ){
		$this->db->select("
			idPostdated,idInvoice,paymentMethod,(IF(chequeNo > 0,chequeNo,'')) as chequeNo
			, ( CASE
				WHEN date = '0000-00-00' THEN NULL
				ELSE date
			END ) as date
			,amount,bankaccount.sk,bankaccount.bankAccount as bankName,postdated.idBankAccount
			,(CASE
				WHEN paymentMethod = 0 THEN 'Cash'
				ELSE 'Cheque'	
			END) as type
		");
		$this->db->from('postdated');
		$this->db->where('idInvoice',$rawData['idInvoice']);
		$this->db->join('bankaccount','bankaccount.idBankAccount = postdated.idBankAccount','left outer');
		return $this->db->get()->result_array();		
	}
	function getBalLeft($fident){
		$this->db->select('balLeft');
		$this->db->where('idInvoice',$fident);
		return $this->db->get('invoices')->row()->balLeft;
	}
	function updateBalLeft($fident, $newAmount){
		$this->db->set('balLeft', $newAmount);
		$this->db->where('idInvoice', $fident);
		$this->db->update('invoices');
	}
	function saveInvoiceDisbursements($rawData){
		$id					= (int)$rawData['idInvoice'];
		$rawData['date']	= $rawData['tdate'].' '.$rawData['ttime'];
		unset($rawData['idInvoice']);
		if( (int)$rawData['onEdit'] == 0 ){
			$this->db->insert('invoices', unsetParams($rawData, 'invoices') );
			return $this->db->insert_id();
		} else {
			$this->db->where('idInvoice', $id);
			$this->db->update('invoices', unsetParams($rawData, 'invoices') );
		}
	}
	function saveDisbursementDetails($paidDetails,$dsInvoiceID){
		$this->db->insert_batch( 'disbursements', unsetParamsBatch( $paidDetails, 'disbursements' ) );
	}
	function savePaymentList($paymentList,$dsInvoiceID){
		$this->db->insert_batch( 'postdated', unsetParamsBatch( $paymentList, 'postdated' )  );
	}
	function saveJournalDetails( $journalDetails,$crInvoiceID ){
		$this->db->delete( 'posting', array( 'idInvoice' => $crInvoiceID ) );
		$this->db->insert_batch( 'posting', unsetParamsBatch( $journalDetails, 'posting' ) );
	}
	function retrieveData( $idInvoice ){
		$this->db->select('invoices.*,supplier.creditLimit');
		$this->db->from('invoices');
		$this->db->join('supplier', 'supplier.idSupplier = invoices.pCode and pType = 2');
		$this->db->where('idInvoice', $idInvoice);
		return $this->db->get()->result_array();
	}
	function delReceiptsDetails($dsInvoiceID){
		$this->db->delete( 'disbursements', array( 'idInvoice' => $dsInvoiceID ) );
	}
	function delPostdatedDetails($dsInvoiceID){
		$this->db->delete( 'postdated', array( 'idInvoice' => $dsInvoiceID ) );
	}
	
	function deleteDisbursement($idInvoice){
		$this->db->delete( 'invoices', array( 'idInvoice' => $idInvoice ) );
	}
	

	// function getGridListForPayments($rawData){
	// 	/* Edited or saved cash receipts statments here */
	// 	if( (int)$rawData['idInvoice'] != 0 || !$rawData['idInvoice'] ){
	// 		// echo 'sulod ko edited record';
	// 		$this->db->select("
	// 			CONCAT(salesReference.code, ' - ',salesInvoices.referenceNum) as reference	
	// 			,salesInvoices.idInvoice as salesIDInvoice
	// 			,DATE_FORMAT(salesInvoices.date,'%Y-%m-%d') as date
	// 			,(IF(salesInvoices.balLeft > 0,salesInvoices.balLeft,0) + receipts.amount) as receivables 
	// 			,receipts.amount as collections
	// 			,(( IF(salesInvoices.balLeft > 0,salesInvoices.balLeft,0) + receipts.amount) - receipts.amount ) as balance
	// 		");
	// 		$this->db->where('invoices.idInvoice', $rawData['idInvoice'] );
	// 		$this->db->join('receipts','receipts.idInvoice = invoices.idInvoice','left outer');		
	// 		$this->db->join('invoices as salesInvoices','salesInvoices.idInvoice = receipts.fident','left outer');
	// 		$this->db->join('reference as salesReference','salesReference.idReference = salesInvoices.idReference','left outer');
	// 	}
	// 	else{ /* New sales record fetch statements here */
	// 		// echo 'sulod ko new record';
	// 		$this->db->select("
	// 			CONCAT(reference.code, ' - ',invoices.referenceNum) as reference
	// 			,invoices.idInvoice as salesIDInvoice
	// 			,DATE_FORMAT(invoices.date,'%Y-%m-%d') as date
	// 			,invoices.balLeft as receivables 
	// 			,0 as collections
	// 			,invoices.balLeft as balance
	// 		");
	// 		$this->db->where('invoices.pCode', $rawData['idCustomer'] );
	// 		$this->db->where('invoices.date <=', $rawData['tDate']);
	// 		$this->db->where('invoices.status',2);
	// 	}
	// 	$this->db->from('invoices');
	// 	$this->db->where('invoices.pType',1);
	// 	$this->db->join('reference','reference.idReference = invoices.idReference','left outer');
	// 	return $this->db->get()->result_array();
	// }
}