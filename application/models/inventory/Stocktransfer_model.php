<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Mark Reynor D. Magriña
 * Module: Sales Module
 * Date: Feb 04, 2020
 * Finished:
 * Description:
 * DB Tables:
 * */
class Stocktransfer_model extends CI_Model {

	function getGridListForPayments($rawData){

		/* Edited or saved cash receipts statments here */
		if( (int)$rawData['idInvoice'] != 0 || !$rawData['idInvoice'] ){
			// echo 'sulod ko edited record';

			$this->db->select("
				CONCAT(salesReference.code, ' - ',salesInvoices.referenceNum) as reference
				,salesInvoices.idInvoice as salesIDInvoice
				,DATE_FORMAT(salesInvoices.date,'%Y-%m-%d') as date
				,(IF(salesInvoices.balLeft > 0,salesInvoices.balLeft,0) + receipts.amount) as receivables
				,receipts.amount as collections
				,(( IF(salesInvoices.balLeft > 0,salesInvoices.balLeft,0) + receipts.amount) - receipts.amount ) as balance
			");
			$this->db->where('invoices.idInvoice', $rawData['idInvoice'] );

			$this->db->join('receipts','receipts.idInvoice = invoices.idInvoice','left outer');
			$this->db->join('invoices as salesInvoices','salesInvoices.idInvoice = receipts.fident','left outer');
			$this->db->join('reference as salesReference','salesReference.idReference = salesInvoices.idReference','left outer');
		}
		else{ /* New sales record fetch statements here */
			// echo 'sulod ko new record';

			$this->db->select("
				CONCAT(reference.code, ' - ',invoices.referenceNum) as reference
				,invoices.idInvoice as salesIDInvoice
				,DATE_FORMAT(invoices.date,'%Y-%m-%d') as date
				,invoices.balLeft as receivables
				,0 as collections
				,invoices.balLeft as balance
			");
			$this->db->where('invoices.pCode', $rawData['idCustomer'] );
			$this->db->where('invoices.date <=', $rawData['tDate']);
			$this->db->where('invoices.status',2);
		}
		$this->db->from('invoices');
		$this->db->where('invoices.pType',1);
		$this->db->join('reference','reference.idReference = invoices.idReference','left outer');
		return $this->db->get()->result_array();
	}

	/* function getCollectionDetails($rawData){
		$this->db->select("
			idPostdated,idInvoice,paymentMethod,(IF(chequeNo > 0,chequeNo,'')) as chequeNo,(IFNULL(NULL, date)) as date,amount,bank.bankName,idBankAccount
			,(CASE
				WHEN paymentMethod = 1 THEN 'Cash'
				ELSE 'Cheque'
			END) as type
		");
		$this->db->from('postdated');
		$this->db->where('idInvoice',$rawData['idInvoice']);
		$this->db->join('bank','bank.idBank = postdated.idBankAccount','left outer');
		return $this->db->get()->result_array();
	} */

	function getItems( $rawData ){
		$qty = ( isset($rawData['qty']) ? $rawData['qty'] : 1 );
		$this->db->select(
			' item.idItem,item.itemName,item.barcode
			, item.releaseWithoutQty
			, item.sk as itemSK
			, unit.unitName
			, itemclassification.className
			, latestReceivingCost.cost
			, maxReceivingID.idReceiving
			, item.itemPrice
			, receivingSumQty.qtyLeft as availQty
			, ' . $qty . ' as qty
			,(item.itemPrice * '.$qty.' ) as totalAmount
		');
		$this->db->where('itemaffiliate.idAffiliate', $this->session->userdata('AFFILIATEID') );
		if( isset($rawData['query']) && isset($rawData['itemNameIndicator']) ) $this->db->like('item.itemName', $rawData['query'],'both');
		elseif( isset($rawData['query']) ) $this->db->like('item.barcode', $rawData['query'],'both');
		$this->db->join('itemaffiliate','itemaffiliate.idItem = item.idItem','left outer');
		$this->db->join('itemclassification', 'itemclassification.idItemClass = item.idItemClass', 'LEFT');
		$this->db->join('unit', 'unit.idUnit = item.idUnit', 'LEFT');
		$this->db->JOIN( "(
			SELECT max( idReceiving ) as idReceiving, idItem
			FROM receiving GROUP BY idItem
			) AS maxReceivingID" , "maxReceivingID.idItem = item.idItem" , "LEFT" )
			->JOIN( "(
				SELECT idReceiving, cost
				FROM receiving
			) AS latestReceivingCost" , "latestReceivingCost.idReceiving = maxReceivingID.idReceiving" , "LEFT" );
		$this->db->join("
			(
				select SUM( IFNULL( receiving.qtyLeft, 0) ) as qtyLeft, receiving.idItem, invoices.idAffiliate from receiving
				JOIN invoices on( invoices.idInvoice = receiving.idInvoice ) where invoices.idAffiliate = " . (int)$this->session->userdata('AFFILIATEID') . "
				and DATE_FORMAT(invoices.date,'%Y-%m-%d') <= '". $rawData['date'] ."' and invoices.status = 2 AND invoices.archived NOT IN( 1 )
				GROUP BY receiving.idItem
			) as receivingSumQty
		",'receivingSumQty.idItem = item.idItem','left outer');
		return $this->db->get('item')->result_array();
    }

	// select  SUM( IFNULL( receivingSumQty.qtyLeft, 0) ) as availQty from receiving
	// join invoices on( invoices.idInvoice = receiving.idInvoice )

	public function searchHistoryGrid( $params )
    {
        $this->db->select('
            invoices.idInvoice as id
            ,CONCAT( reference.code , " - " , invoices.referenceNum ) as name
        ');

        $this->db->from( 'invoices' );
        $this->db->join( 'reference'    , 'reference.idReference = invoices.idReference' );
        $this->db->where( 'invoices.idModule' , 43 );
        $this->db->where( 'invoices.status' , 2 );
        $this->db->where( 'invoices.idAffiliate' , $this->session->userdata('AFFILIATEID') );
        $this->db->where_not_in( 'invoices.archived' , 1 );
        $this->db->where_not_in( 'invoices.cancelTag' , 1 );

        if( isset( $params['query'] ) ) {
            $this->db->like("CONCAT( reference.code , ' - ' , invoices.referenceNum )", $params['query'], "both");
        }

        $this->db->group_by( 'invoices.idInvoice' );
        return $this->db->get()->result_array();
    }

	function getItemListDetails($rawData){
		$this->db->select(
			" idstockTransfer
			, idInvoice
			, qtyTransferred
			, qtyReceived
			, item.itemName
			, item.idItem
			, item.sk
			, item.barcode
			, item.itemPrice
			, item.releaseWithoutQty
			, unit.unitName
			, latestReceivingCost.cost
			, maxReceivingID.idReceiving "
		);
		$this->db->from('stocktransfer');
		$this->db->where('idInvoice',$rawData['idInvoice']);
		$this->db->join('item','item.idItem = stocktransfer.idItem','left outer');
		$this->db->join('unit','unit.idUnit = item.idUnit','left outer');
		$this->db->JOIN( "(
			SELECT max( idReceiving ) as idReceiving, idItem
			FROM receiving
			WHERE qtyLeft > 0
			GROUP BY idItem
		 ) AS maxReceivingID" , "maxReceivingID.idItem = item.idItem" , "LEFT" )
			->JOIN( "(
			   SELECT idReceiving, cost
			   FROM receiving
			) AS latestReceivingCost" , "latestReceivingCost.idReceiving = maxReceivingID.idReceiving" , "LEFT" );
		return $this->db->get()->result_array();
	}

	function viewAll($rawData){
		$this->db->select("
			invoices.idInvoice,DATE_FORMAT(invoices.date, '%Y-%m-%d') as date
			,(CASE
					WHEN invoices.status = 1 THEN 'Pending'
					WHEN invoices.status = 2 THEN 'Approved'
					ELSE 'Cancelled'
			END) as status
			,CONCAT( reference.code,'-',invoices.referenceNum ) as reference
			,affiliateSource.affiliateName as sourceAffiliate
			,affiliateSource.sk as affiliateSourceSK
			,affiliateReceiver.affiliateName as receiverAffiliate
			,affiliateReceiver.sk as affiliateReceiverSK
			,empNoted.name as notedByName
			,empPrepare.name as preparedByName
			,empPrepare.sk	as empSK
		");
		$this->db->where('invoices.idModule', 43);
		$this->db->where_not_in('invoices.archived',1);
		$this->db->from('invoices');
		$this->db->order_by('invoices.date','desc');
		$this->db->order_by('idInvoice','desc');
		$this->db->join('reference','reference.idReference = invoices.idReference','left outer');
		$this->db->join('affiliate as affiliateSource','affiliateSource.idAffiliate = invoices.idAffiliate','');
		$this->db->join('affiliate as affiliateReceiver','affiliateReceiver.idAffiliate = invoices.pCode','');
		$this->db->join('eu as euNoted','euNoted.idEu = invoices.notedby','left outer');
		$this->db->join('employee as empNoted','empNoted.idEmployee = euNoted.idEmployee','left outer');
		$this->db->join('eu euPrepared','euPrepared.idEu = invoices.preparedBy','left outer');
		$this->db->join('employee as empPrepare','empPrepare.idEmployee = euPrepared.idEmployee','left outer');

		if( isset( $rawData['filterValue'] ) ) {
            $this->db->where( 'invoices.idInvoice', $rawData['filterValue']);
		}
		$this->db->where( 'invoices.idAffiliate' , $this->session->userdata('AFFILIATEID') );

		$results = $this->db->get()->result_array();

		$_viewHolder = $results;

        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['affiliateSourceSK'] ) && !empty( $po['affiliateSourceSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affiliateSourceSK'] )));
                $_viewHolder[$idx]['sourceAffiliate'] = $this->encryption->decrypt( $po['sourceAffiliate'] );
			}
			if( isset( $po['affiliateReceiverSK'] ) && !empty( $po['affiliateReceiverSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affiliateReceiverSK'] )));
                $_viewHolder[$idx]['receiverAffiliate'] = $this->encryption->decrypt( $po['receiverAffiliate'] );
			}
			if( isset( $po['empSK'] ) && !empty( $po['empSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['empSK'] )));
                $_viewHolder[$idx]['preparedByName'] = $this->encryption->decrypt( $po['preparedByName'] );
			}
        }

		return $_viewHolder;
	}
	function retrieveData($rawData){
		$this->db->select(
			" idInvoice
			, remarks
			, idAffiliate
			, employee.sk
			, idReference,referenceNum,idModule,idCostCenter,date,pCode
			, employee.name as transferredBy
		");
		$this->db->from('invoices');
		$this->db->where('idInvoice',(int)$rawData['idInvoice']);
		$this->db->join('eu','eu.idEu = invoices.preparedBy','left outer');
		$this->db->join('employee','employee.idEmployee = eu.idEmployee','left outer');
		return $this->db->get()->result_array();
	}

	function saveInvoiceStockTransfer($rawData){
		$id 					  = (int)$rawData['idInvoice'];
		$rawData['idAffiliate']   = $this->session->userdata('AFFILIATEID');
		$rawData['transferredBy'] = $this->USERID;
		unset( $rawData['idInvoice'] );

		if( (int)$rawData['onEdit'] == 0 ){
			$this->db->insert( 'invoices', unsetParams($rawData, 'invoices') );
			$id = $this->db->insert_id();
		}else{
			$this->db->where('idInvoice',$id);
			$this->db->update('invoices', unsetParams($rawData, 'invoices') );
		}

		return $id;
	}

	function saveInvoiceReceiving($rawData){
		$id 					  = (int)$rawData['idInvoice'];
		$rawData['transferredBy'] = $this->USERID;
		unset($rawData['idInvoice']);
		if( (int)$rawData['onEdit'] == 0 ){
			$idAffiliate			= $rawData['pCode'];
			$rawData['pCode']		= $this->session->userdata('AFFILIATEID');
			$rawData['idAffiliate'] = $idAffiliate;
			$this->db->insert( 'invoices', unsetParams($rawData, 'invoices') );
			return $this->db->insert_id();
		}else{
			$this->db->where('idInvoice',$id);
			$this->db->update('invoices', unsetParams($rawData, 'invoices') );
			$rawData['idInvoice'] = $id;
			return $id;
		}
	}

	function saveGridItemList($gridItemList,$stInvoiceID){
		$this->db->delete( 'stocktransfer', array('idInvoice'=>$stInvoiceID) );
		$this->db->insert_batch( 'stocktransfer', unsetParamsBatch( $gridItemList, 'stocktransfer' ) );
	}
	function saveJournalDetails($journalDetails,$stInvoiceID){
		$this->db->delete( 'posting', array( 'idInvoice' => $stInvoiceID ) );
		$this->db->insert_batch( 'posting', unsetParamsBatch( $journalDetails, 'posting' ) );
	}

	//ARCHIVE ONLY
	public function deleteStockTransferRecord( $idInvoice )
    {
        $data = array (
            'archived' => 1
        );

        $this->db->where( 'idInvoice' , $idInvoice );
        return $this->db->update( 'invoices' , $data );
    }

	function saveReleasing( $idInvoice, $gridItem ){
		$this->db->delete( 'releasing', array( 'idInvoice' => $idInvoice ) );
		$this->db->insert_batch( 'releasing', unsetParamsBatch( $gridItem, 'releasing' ) );
	}

	function saveReceiving( $idInvoice, $gridItem ){
		$this->db->delete( 'receiving', array( 'idInvoice' => $idInvoice ) );
		$this->db->insert_batch( 'receiving', unsetParamsBatch( $gridItem, 'receiving' ) );
	}

	public function isClosedJE( $params )
    {
        $invMonth   = date( 'm' , strtotime( $params['tdate'] ) );
        $invYear    = date( 'Y' , strtotime( $params['tdate'] ) );

        return $this->db->select( "count(*) as count" )
        ->where( 'idModule' , 35 )
        ->where( 'month' , $invMonth )
        ->where( 'year' , $invYear )
        ->where_not_in( 'archived' , 1 )
        ->get( "invoices" )->row()->count;
    }

    public function checkIfUsed( $idInvoice )
    {
        $invMonth   = $this->db->select( "MONTH( invoices.date ) as month" )->where( "idInvoice" , $idInvoice )->get( "invoices" )->row()->month;
        $invYear    = $this->db->select( "YEAR( invoices.date ) as year" )->where( "idInvoice" , $idInvoice )->get( "invoices" )->row()->year;

        $closingJECount = $this->db->select( "count(*) as count" )
            ->where( 'idModule' , 35 )
            ->where( 'month' , $invMonth )
            ->where( 'year' , $invYear )
            ->where_not_in( 'archived' , 1 )
            ->get( "invoices" )->row()->count;
        $this->db->reset_query();

        $dbCount = $this->db->select( "count(*) as count" )
            ->where( 'disbursements.idInvoice' , $idInvoice )
            ->get( "disbursements" )->row()->count;
        $this->db->reset_query();

        $isCancel = $this->db->select( "count(*) as count" )
            ->where( 'invoices.idInvoice' , $idInvoice )
            ->where( 'invoices.cancelTag' , 1 )
            ->get( "invoices" )->row()->count;
        $this->db->reset_query();

        return ( $closingJECount + $dbCount + $isCancel );
    }

    public function checkIfNotFound( $idInvoice )
    {
        $this->db->where( 'invoices.idInvoice' , $idInvoice );
        $this->db->where( 'invoices.archived' , 1 );
        return $this->db->get( 'invoices' )->result_array();
    }
}