<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Mark Reynor D. Magriña
 * Module: Sales Module
 * Date: Dec 12, 2019
 * Finished: 
 * Description: 
 * DB Tables: 
 * */
class Sales_model extends CI_Model 
{
	function get_drivers()
	{
		$this->db->select( 'employee.idEmployee as id, employee.name, sk' );
		$this->db->join( 'employee', 'employee.idEmployee = a.idEmployee', 'inner');
		$this->db->join( 'employeeclass as b','b.idEmpClass=a.classification','left outer');
		$this->db->join( 'employeeaffiliate','employeeaffiliate.idEmployee = employee.idEmployee' );
		$this->db->like( 'empClassName', 'driver' );
		$this->db->where( 'employeeaffiliate.idAffiliate', $this->session->userdata('AFFILIATEID') );
		return $this->db->get( 'employmenthistorydate as a' )->result_array();
	}

	public function getCustomer( $params ){
        $idField = 'idCustomer';
        $nameField = 'name';
        $tableFrom = 'customer';
        if( isset( $params['idField'] ) ){
            if( !empty( $params['idField'] ) ) $idField = $params['idField'];
        }
        if( isset( $params['nameField'] ) ){
            if( !empty( $params['nameField'] ) ) $nameField = $params['nameField'];
        }
        if( isset( $params['tableFrom'] ) ){
            if( !empty( $params['tableFrom'] ) ) $tableFrom = 'supplier';
		}
		
		if( isset( $params['idCustomer'] ) && $params['idCustomer'] != 0 ) $this->db->where( "customer.idCustomer" , $params['idCustomer']);
		
		$this->db->select( "customer.$idField as id, customer.$nameField as name, customer.creditLimit, customer.sk" );
		$this->db->join("customeraffiliate", "customeraffiliate.idCustomer = customer.idCustomer", "LEFT");
		$this->db->where("customeraffiliate.idAffiliate", $this->session->userdata('AFFILIATEID') );
        if( isset( $params['query'] ) ) $this->db->like( $nameField, $params['query'], 'both' );
        $this->db->order_by( $nameField, 'asc' );
        return $this->db->get( $tableFrom )->result_array();
	}

	public function salesOrderList($data){
		$this->db->select("
			invoices.idInvoice AS id,
			invoices.pCode AS customer,
			CONCAT(reference.code, ' - ', invoices.referenceNum) AS name
		")
		->from('invoices')
		->join('reference', 'reference.idReference = invoices.idReference', 'LEFT')
		->join('so', 'so.idInvoice = invoices.idInvoice', 'LEFT')
		->where('invoices.idModule',17)
		->where('invoices.idAffiliate',$this->session->userdata('AFFILIATEID'))
		->where('invoices.status', APPROVED)
		->where("invoices.archived", NEGATIVE)
		->where("invoices.cancelTag", 0)
		->where("so.qtyLeft > 0 ", NULL, false)
		->where("invoices.date <= '{$data['transaction_date']}'", NULL, false)
		->group_by("invoices.idInvoice");

		return $this->db->get()->result_array();
	}

	public function salesOrder($id){
		$this->db->select("
			invoices.idInvoice AS id,
			invoices.pCode AS customer,
			CONCAT(reference.code, ' - ', invoices.referenceNum) AS name
		")
		->from('invoices')
		->join('reference', 'reference.idReference = invoices.idReference', 'LEFT')
		->join('so', 'so.idInvoice = invoices.idInvoice', 'LEFT')
		->where('invoices.idInvoice', $id)
		->group_by("invoices.idInvoice");

		return $this->db->get()->result_array();
	}

	public function customerInfo($id){
		$this->db->select("
			customer.paymentMethod,
			customer.terms, 
			customer.withCreditLimit,
			customer.creditLimit,
			customer.discount,
			customer.penalty,
			customer.withHoldingTaxRate AS ewtRate,
			customer.withVAT,
			IF( withVAT = 1, customer.vatType , affiliate.vatType ) AS vatType,
			IF( withVAT = 1, customer.vatPercent , affiliate.vatPercent ) AS vatPercent,
			SUM( IF( payMode=2, IFNULL( invoices.balLeft, 0), 0) ) as balLeft
		")
		->from('customer')
		->join('customeraffiliate' , "customeraffiliate.idCustomer = customer.idCustomer AND customeraffiliate.idAffiliate = '{$this->session->userdata('AFFILIATEID') }' ")
		->join('affiliate' , 'affiliate.idAffiliate = customeraffiliate.idAffiliate')
		->join('invoices',"invoices.pCode = '{$id}' AND invoices.idModule = 18 AND invoices.status != 3 AND invoices.archived = 0",'LEFT')
		->where('customer.idCustomer', $id)
		->group_by('customer.idCustomer');

		return $this->db->get()->result_array();
	}

	public function affiliateInfo($id){
		$this->db->select("
			vatPercent,
			vatType
		")
		->from('affiliate')
		->where('idAffiliate', $id);
		return $this->db->get()->result_array();
	}

	public function getItems($data){
		$items = isset($data['items'])? json_decode($data['items']): [];
		$this->db->select("
			item.idItem AS id,
			item.barcode AS barcode,
			item.itemName AS name,
			item.sk as itemSK,
			unit.unitCode AS unit,
			itemclassification.className AS classification,
			( SUM( IFNULL( receiving.qty , 0 ) ) - SUM( IFNULL( releasing.qty , 0 ) ) ) AS remaining,
			IFNULL(itempricehistory.itemPrice, item.itemPrice) AS price,
			'' AS expiration,
			'' AS lot,
			'{$data['qty']}' AS qty,
			0 AS salesorder,
			'{$data['qty']}' * IFNULL(itempricehistory.itemPrice, item.itemPrice) AS amount
		")
		->from('receiving')
		->JOIN( "
			(  SELECT
				releasing.fIdent 
				, releasing.idItem 
				, sum( ifnull(releasing.qty , 0) ) as qty
				FROM releasing

				JOIN invoices relInvoice 
				ON relInvoice.idInvoice = releasing.idInvoice  
				AND relInvoice.archived NOT IN( 1 )	
				AND relInvoice.cancelTag NOT IN( 1 )	
				
				group by releasing.fIdent , releasing.idItem , releasing.cost
			) AS releasing" 
			, "releasing.fIdent = receiving.idReceiving AND releasing.idItem = receiving.idItem"  
			, "LEFT"
		)
		->join('invoices','receiving.idInvoice = invoices.idInvoice','LEFT')
		->join('item','item.idItem = receiving.idItem','LEFT')
		->join('itemclassification','item.idItemClass = itemclassification.idItemClass','LEFT')
		->join('unit', 'unit.idUnit = item.idUnit', "LEFT")
		->JOIN( 
			"( 
				SELECT MIN( idPrice ) as idPrice, idItem
				FROM (
					SELECT itemID.idPrice , itemID.idItem
					FROM (
						SELECT	max( itempricehistory.idPrice ) as idPrice
						FROM	itempricehistory
						WHERE	'{$data['tdate']}'  <= itempricehistory.effectivityDate
						group by effectivityDate
					) AS idPrices
					JOIN ( SELECT idPrice, idItem FROM itempricehistory ) AS itemID ON itemID.idPrice = idPrices.idPrice
				) AS idPrices
				GROUP BY idItem
			) AS idPriceForEffDate"
			, "idPriceForEffDate.idItem = item.idItem"
			, "LEFT"
		)
		->join(	"itempricehistory", "itempricehistory.idPrice = idPriceForEffDate.idPrice", "LEFT")          
		->where('invoices.status', APPROVED)
		->where('invoices.idAffiliate', $this->session->userdata('AFFILIATEID'))
		->where("invoices.archived", NEGATIVE)
		->where("invoices.cancelTag", 0)
		->where('receiving.qtyLeft > 0', null, false)
		->group_by('item.idItem , itempricehistory.itemPrice');
		if(sizeof($items) > 0){
            $this->db->where_not_in("item.idItem", $items);
		}
		if( isset($data['query'])){
            $this->db->like($data['from'], $data['query']);
        }
		return $this->db->get()->result_array();
	}

	public function insertBulk($data, $table){
        $this->db->insert_batch($table, $data); 
	}
	
	public function update($table, $data, $where){
        $this->db->update($table, $data, $where);
	}
	
	public function insert($data,$table){
        $this->db->insert($table,$data);
        return $this->db->insert_id();
	}

	public function deleteAssociateChild($params){
        $this->db->where_in('idInvoice', $params['id'])
            ->delete(['receiving', 'posting', 'releasing']);
	}
	
	public function released($releasedData, $affiliate, $so){
        foreach ($releasedData as $key => $item){
			$items = $this->db->select('receiving.idReceiving, receiving.qtyLeft, receiving.idItem, receiving.cost')
				->from('receiving')
				->join('invoices', 'invoices.idInvoice = receiving.idInvoice', 'LEFT')
				->where('receiving.qtyLeft > 0',NULL, false)
				->where('receiving.idItem', $item['idItem'])
				->where('invoices.idAffiliate', $this->session->userdata('AFFILIATEID'))
				->where('invoices.status', APPROVED)
				->where("invoices.archived", NEGATIVE)
				->get()->result_array();
			$released = (int) $item['qty'];
			if($so != NULL) 	$this->saveSORelease($released, $so, $item['idItem']);
			foreach($items as $indx => $it){
				$this->db->where('idReceiving', $it['idReceiving']);
				$left = (int) $it['qtyLeft'];
				if($released <= 0 ) break;
				if($released > $left){
					$released = $released - $left;
                    $this->db->update('receiving',['qtyLeft' => 0]);
					$this->saveRelease($item, $it['idReceiving'],  $left, $it['cost']);
				}else{
                    $total = $released;
					$qty = $left - $released;
                    $released = 0;
                    $this->db->update('receiving',['qtyLeft' => $qty]);
                    $this->saveRelease($item, $it['idReceiving'], $total, $it['cost']);
				}
			}
		}
	}

	public function returnReleased($where){
        $items = $this->db->select("fident, qty")->from('releasing')->where($where)->get()->result_array();
        foreach($items as $key => $item){
            $this->db->set( 'qtyLeft', '( qtyLeft + ' . $item['qty'] . ' )', false );
            $this->db->where( 'idReceiving', $item['fident'] );
            $this->db->update( 'receiving' );
        }
	}

	public function returnSO($where){
		$id = $this->db->select('fident')->from('invoices')->where($where)->get()->result_array();
		if(sizeof($id) > 0){
			$id = $id[0]['fident'];
			$items = $this->db->select("fident, qty")->from('releasing')->where($where)->get()->result_array();
			foreach($items as $key => $item){
				$this->db->set( 'qtyLeft', '( qtyLeft + ' . $item['qty'] . ' )', false );
				$this->db->where( 'idSo', $id );
				$this->db->update( 'so' );
			}
		}
		
	}
	
	public function getAllSOItems($id, $affiliate){
		$this->db->select("
			CONCAT( reference.code , ' - ' , soInv.referenceNum ) as reference,
			item.idItem AS id,
			item.sk,
			item.barcode AS barcode,
			item.itemName AS name,
			unit.unitCode AS unit,
			itemclassification.className AS classification,
			'' AS lot,
			'' AS expiry,
			IFNULL(itempricehistory.itemPrice, item.itemPrice) AS price,
			IFNULL(so.qtyLeft,0) AS remaining,
			so.qtyLeft AS salesorder,
			0 AS qty,
			0 AS amount
		")
		->from("so")
		->join('item', 'so.idItem = item.idItem', 'LEFT')
		->join('unit', 'unit.idUnit = item.idUnit', 'LEFT')
		->join('itemclassification', 'item.idItemClass = itemclassification.idItemClass', 'LEFT')
		->join('itempricehistory', 'itempricehistory.idItem = item.idItem AND itempricehistory.effectivityDate = (SELECT MAX(effectivityDate) FROM itempricehistory WHERE itempricehistory.effectivityDate <= CURDATE() AND itempricehistory.idItem = item.idItem)', 'LEFT')
		->join('receiving', 'so.idItem = receiving.idItem', 'LEFT')
		->join('invoices', "receiving.idInvoice = invoices.idInvoice AND invoices.status = '".APPROVED."' AND invoices.idAffiliate = {$this->session->userdata("AFFILIATEID")} AND invoices.archived ='".NEGATIVE."'", 'LEFT')
		->join( 'invoices soInv', "soInv.idInvoice = so.idInvoice" )
		->join( 'reference', "reference.idReference = soInv.idReference" )
		->where('so.idInvoice', $id)
		->where("soInv.cancelTag", 0)
		->where('so.qtyLeft > 0',NULL, false)
		->group_by('item.idItem, itempricehistory.itemPrice, so.qtyLeft');
		return $this->db->get()->result_array();
 
	}
	public function viewAll($params){
		if( isset( $params['filterValue'] ) ) {
            $this->db->where( 'invoices.idInvoice', $params['filterValue']);
		}

		$this->db->where( 'invoices.idAffiliate', $this->session->userdata('AFFILIATEID') );
		$this->db->select("
			invoices.idInvoice AS id,
			CONCAT(reference.code, ' - ',invoices.referenceNum) AS reference,
			DATE_FORMAT(invoices.date, '%Y-%m-%d') AS date,
			affiliate.affiliateName AS affiliate,
			costcenter.costCenterName AS costcenter,
			customer.name AS customer,
			customer.sk	AS customerSK,
			invoices.amount AS sales,
			pEmp.name AS preparedby,
			nEmp.name AS notedby

			,invoices.idReference
			,invoices.referenceNum
			,invoices.idModule
		")
		->from('invoices')
		->join('reference', 'reference.idReference = invoices.idReference', 'LEFT')
		->join('affiliate', 'affiliate.idAffiliate = invoices.idAffiliate', 'LEFT')
		->join('costcenter', 'costcenter.idCostCenter = invoices.idCostCenter', 'LEFT')
		->join('customer', 'customer.idCustomer = invoices.pCode', 'LEFT')
		->join('eu AS pUser', 'pUser.idEu = invoices.preparedBy', 'LEFT')
		->join('employee AS pEmp', 'pEmp.idEmployee = pUser.idEmployee', 'LEFT')
		->join('eu AS nUser', 'nUser.idEu = invoices.notedby', 'LEFT')
		->join('employee AS nEmp', 'nEmp.idEmployee = nUser.idEmployee', 'LEFT')
		->where('invoices.archived', NEGATIVE)
		->where('invoices.idModule', SALES)
		->group_by('invoices.idInvoice');
		$params['db'] = $this->db;
        $params['order_by'] = 'invoices.idInvoice desc';
		// if(isset($params['pdf']) && $params['pdf']) return $this->db->get()->result_array();
		return getGridList($params);
	}
	public function getInvoices($where){
        $this->db->select("
            idInvoice,
            idAffiliate,
            idReference,
			referenceNum,
			idCostCenter,
			idReferenceSeries,
			idModule AS idmodule,
            DATE_FORMAT(date, '%Y-%m-%d') AS tdate,
			DATE_FORMAT(date, '%h:%i %p') AS ttime,
			remarks,
			dateModified,
			status, 
			amount AS totalamnt,
			bal AS  balance,
			downPayment AS downpayment,
			discount, 
			discountRate AS discountper,
			dueDate AS duedate,
			payMode AS paymentType,
			pCode AS customer,
			deliveryReceiptTag,
			deliveryReceipt,
			cancelTag,
			fident,
			idDriver,
			plateNumber
        ")
        ->from('invoices')
        ->where($where);

        return $this->db->get()->result_array();
	}
	
	public function getReleased($where){
		$this->db->select("
			item.idItem AS id,
			item.sk,
			item.barcode AS barcode,
			item.itemName AS name,
			unit.unitCode AS unit,
			itemclassification.className AS classification,
			releasing.lotNumber AS lot,
			releasing.expiration AS expiry,
			releasing.price,
			releasing.qty as rawQty,
			releasing.qty,
			(SUM(IFNULL(so.qtyLeft,0)) + releasing.qty) AS salesorder,
			releasing.qty + SUM(receiving.qtyLeft) AS remaining,
			(releasing.qty * releasing.price) AS amount
		")
		->from('releasing')
		->join('item','item.idItem = releasing.idItem','LEFT')
		->join('unit','unit.idUnit = item.idUnit','LEFT')
		->join('itemclassification','itemclassification.idItemClass = item.idItemClass','LEFT')
		->join('receiving','receiving.idItem = releasing.idItem','LEFT')
		->join('invoices','invoices.idInvoice = receiving.idInvoice','LEFT')
		->join('invoices AS parentInv','parentInv.idInvoice = releasing.idInvoice','LEFT')
		->join('invoices AS soInv','soInv.idInvoice = parentInv.fident','LEFT')
		->join('so','so.idInvoice = soInv.idInvoice','LEFT')
		->where($where)
		->where('invoices.archived', NEGATIVE)
		->where("invoices.cancelTag", 0)
		->group_by('releasing.idItem,
		releasing.lotNumber,
		releasing.expiration,
		releasing.price,
		releasing.qty');
		return $this->db->get()->result_array();
	}

	public function selectInvoices($select, $where){
        return $this->db->select($select)->from('invoices')->where($where)->get()->result_array();
    }
	
	public function customerItem($id, $affiliate){
		return $this->db->select("
			item.idItem AS id,
			item.barcode AS barcode,
			item.itemName AS name,
			unit.unitCode AS unit,
			itemclassification.className AS classification,
			'' AS lot,
			'' AS expiry,
			IFNULL(itempricehistory.itemPrice, item.itemPrice) AS price,
			0 AS qty,
			0 AS salesorder,
			SUM(receiving.qtyLeft) AS remaining,
			0 AS amount
		")
		->from('customer')
		->join('customeritems','customer.idCustomer = customeritems.idCustomer','LEFT')
		->join('item','item.idItem = customeritems.idItem','LEFT')
		->join('unit','unit.idUnit = item.idUnit','LEFT')
		->join('itemclassification','itemclassification.idItemClass = item.idItemClass','LEFT')
		->join('itempricehistory','itempricehistory.idItem = item.idItem AND itempricehistory.effectivityDate = (SELECT MAX(effectivityDate) FROM itempricehistory WHERE itempricehistory.effectivityDate <= CURDATE() AND itempricehistory.idItem = item.idItem)','LEFT')
		->join('itemaffiliate',"itemaffiliate.idItem = item.idItem AND itemaffiliate.idAffiliate = '{$affiliate}'",'LEFT')
		->join('receiving','receiving.idItem = item.idItem','LEFT')
		->join('invoices', "invoices.idInvoice = receiving.idInvoice AND invoices.idAffiliate = '{$affiliate}'",'LEFT')
		->where('item.barcode IS NOT NULL', NULL, FALSE)
		->where('customer.idCustomer', $id)
		->where('invoices.archived', NEGATIVE)
		->where('invoices.status', APPROVED)
		->where("invoices.cancelTag", 0)
		->where("invoices.idAffiliate IN (SELECT idAffiliate FROM employeeaffiliate WHERE idEmployee = {$this->session->userdata('EMPLOYEEID')})", NULL, FALSE)
		->where('receiving.qtyLeft > 0', NULL, FALSE)
		->group_by('item.idItem , itempricehistory.itemPrice')
		->get()->result_array();
	}

	private function saveRelease($item, $fident, $qty, $cost){
		$item['fIdent'] = $fident;
		$item['qty'] = $qty;
		$item['qtyLeft'] = $qty;
		$item['cost'] = $cost;
		$this->db->insert('releasing',$item); 
	}

	public function cancelTag( $id )
    {
        return $this->db->select( "cancelTag" )
        ->where( "invoices.idInvoice" , $id )
        ->get( "invoices" )->row()->cancelTag;
    }
	
	private function saveSORelease($qty, $id, $item){
		if($id != null){
			$this->db->set( 'qtyLeft', '( qtyLeft - ' . $qty . ' )', false );
			$this->db->where( 'idInvoice', $id );
			$this->db->where( 'idItem', $item );
			$this->db->update( 'so' );
		}
		
	}

	public function getSearchRef( $params ){
		$this->db->select( "
			invoices.idInvoice as id, 
			invoices.referenceNum as name
		" )
		->from('invoices')
		->where("invoices.idAffiliate IN (SELECT idAffiliate FROM employeeaffiliate WHERE idEmployee = {$this->session->userdata('EMPLOYEEID')})", NULL, FALSE)
		->where( 'invoices.idModule', SALES )
		->where( 'invoices.archived', NEGATIVE )
		->order_by( 'invoices.referenceNum', 'asc' );
		if( isset( $params['query'] ) ){
            $this->db->like( "invoices.referenceNum", $params['query'], 'both' );
        }
		return $this->db->get()->result_array();
	}
	
	public function checkMutation($table , $where){
		$this->db->select("
			CASE
				WHEN SUM(qtyLeft) != SUM(qty) THEN 2
				ELSE 0
			END AS state
		")
		->from($table)
		->where($where);
		return $this->db->get()->result_array();
	}
	
	public function searchHistoryGrid( $params )
    {
        $this->db->select('
            invoices.idInvoice as id
            ,CONCAT( reference.code , " - " , invoices.referenceNum ) as name
        ');

        $this->db->from( 'invoices' );
        $this->db->join( 'reference'    , 'reference.idReference = invoices.idReference' );
        $this->db->where( 'invoices.idAffiliate', $this->session->userdata('AFFILIATEID') );
        $this->db->where( 'invoices.idModule' , SALES );
        $this->db->where( 'invoices.archived' , 0 );

        if( isset( $params['query'] ) ) {
            $this->db->like("CONCAT( reference.code , ' - ' , invoices.referenceNum )", $params['query'], "both");
        }

        $this->db->group_by( 'invoices.idInvoice' );
        return $this->db->get()->result_array();
    }
}