<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Salesorder_model extends CI_Model { 

    public function getCustomerItem($params){
        if(!isset($params['customerid'])) return [];
        $this->db->select("
            item.idItem AS id,
            item.barcode AS barcode,
            item.itemName AS name,
            itemclassification.className AS class,
            unit.unitCode AS unit,
            IFNULL(itempricehistory.itemPrice,0) AS cost,
            itemaffiliate.idAffiliate as add,
            1 AS qty
            ,item.sk
        ");
        $this->db->from("item");
        $this->db->join("customeritems", "customeritems.idItem = item.idItem", "LEFT");
        $this->db->join("unit", "unit.idUnit = item.idUnit", "LEFT");
        $this->db->join("itemaffiliate", "itemaffiliate.idItem = item.idItem", "LEFT");
        $this->db->join("itemclassification", "itemclassification.idItemClass = item.idItemClass", "LEFT");
        $this->db->JOIN( 
			"( 
				SELECT MIN( idPrice ) as idPrice, idItem
				FROM (
					SELECT itemID.idPrice , itemID.idItem
					FROM (
						SELECT	max( itempricehistory.idPrice ) as idPrice
						FROM	itempricehistory
						WHERE	'{$params['tdate']}'  <= itempricehistory.effectivityDate
						group by effectivityDate
					) AS idPrices
					JOIN ( SELECT idPrice, idItem FROM itempricehistory ) AS itemID ON itemID.idPrice = idPrices.idPrice
				) AS idPrices
				GROUP BY idItem
			) AS idPriceForEffDate"
			, "idPriceForEffDate.idItem = item.idItem"
			, "LEFT"
        );
        $this->db->join( "itempricehistory", "itempricehistory.idPrice = idPriceForEffDate.idPrice", "LEFT");
        $this->db->where("customeritems.idCustomer", $params['customerid']);
        $this->db->where('itemaffiliate.idAffiliate', $params['idAffiliate']);
        return $this->db->get()->result_array();
    }

    public function getItems($params){
        $items = json_decode($params['items']);
        $field = isset($params['from'])? $params['from'] : 'barcode';
        $this->db->select("
            item.idItem AS id,
            item.sk AS itemSK,
            item.barcode AS barcode,
            item.itemName AS name,
            itemclassification.className AS class,
            unit.unitCode AS unit,
            IFNULL(itempricehistory.itemPrice, item.itemPrice) AS cost,
            ".$params['qty']." AS qty
        ");
        $this->db->from("item");
        $this->db->join("unit", "unit.idUnit = item.idUnit", "LEFT");
        $this->db->join("itemclassification", "itemclassification.idItemClass = item.idItemClass", "LEFT");
        $this->db->join("itemaffiliate", "itemaffiliate.idItem = item.idItem", "LEFT");
        $this->db->JOIN( 
			"( 
				SELECT MIN( idPrice ) as idPrice, idItem
				FROM (
					SELECT itemID.idPrice , itemID.idItem
					FROM (
						SELECT	max( itempricehistory.idPrice ) as idPrice
						FROM	itempricehistory
						WHERE	'{$params['tdate']}'  <= itempricehistory.effectivityDate
						group by effectivityDate
					) AS idPrices
					JOIN ( SELECT idPrice, idItem FROM itempricehistory ) AS itemID ON itemID.idPrice = idPrices.idPrice
				) AS idPrices
				GROUP BY idItem
			) AS idPriceForEffDate"
			, "idPriceForEffDate.idItem = item.idItem"
			, "LEFT"
        );
        $this->db->join( "itempricehistory", "itempricehistory.idPrice = idPriceForEffDate.idPrice", "LEFT");
        
        if(sizeof($items) > 0){
            $this->db->where_not_in("item.idItem", $items);
        }
        $this->db->where('itemaffiliate.idAffiliate', $params['idAffiliate']);
        if( isset($params['query'])){
            $this->db->like($field, $params['query']);
        }
        
        return $this->db->get()->result_array();
    }

    public function getCustomerDetails($params){
        $this->db->select("
            address,
            tin,
            sk
        ");
        $this->db->from("customer");
        $this->db->where("idCustomer", $params['id']);

        return $this->db->get()->row_array();
    }

    public function insert($data,$table){
        $this->db->insert($table,$data);
        return $this->db->insert_id();
    }

    public function insertBulk($data, $table){
        $this->db->insert_batch($table, $data); 
    }

    public function viewAll($params){
        if( isset( $params['filterBy'] ) ){
            if( isset( $params['filterValue'] ) ){
                if( (int)$params['filterValue'] > 0 ) $this->db->where( 'invoices.'.$params['filterBy'], (int)$params['filterValue'] );
            }
        }
        if( isset( $params['subFilter0'] ) ){
            if( isset( $params['query0'] ) ){
                if( (int)$params['query0'] > 0 ) $this->db->where( 'invoices.'.$params['subFilter0'], (int)$params['query0'] );
            }
            $params['subFilter0'] = NULL;
        }
        $this->db->where( 'invoices.idAffiliate', $this->session->userdata('AFFILIATEID') );
        $this->db->select("
            invoices.idInvoice AS id,
            invoices.amount AS total,
            preparedEmployee.name AS prepared_by,
            customer.name AS customer,
            customer.sk AS customerSK,
            location.locationName AS location,
            costcenter.costCenterName AS cost_center,
            affiliate.affiliateName AS affiliate,
            DATE_FORMAT(invoices.date, '%Y-%m-%d') AS date,
            invoices.amount AS amount,
            CONCAT(reference.code, ' - ', invoices.referenceNum) AS reference
			,invoices.idReference
			,invoices.referenceNum
			,invoices.idModule
        ")
            ->from("so")
            ->join("invoices","invoices.idInvoice = so.idInvoice","LEFT")
            ->join("affiliate","affiliate.idAffiliate = invoices.idAffiliate","LEFT")
            ->join("reference","reference.idReference = invoices.idReference","LEFT")
            ->join("costcenter","costcenter.idCostCenter = invoices.idCostCenter","LEFT")
            ->join("location","location.idLocation = invoices.idLocation","LEFT")
            ->join("customer","customer.idCustomer = invoices.pCode","LEFT")
            ->join("eu AS preparedUser","preparedUser.idEu = invoices.preparedBy","LEFT")
            ->join("eu AS notedUser","notedUser.idEu = invoices.notedby","LEFT")
            ->join("employee AS preparedEmployee","preparedEmployee.idEmployee = preparedUser.idEmployee","LEFT")
            ->join("employee AS notedEmployee","notedEmployee.idEmployee = notedUser.idEmployee","LEFT")
            ->where("invoices.idAffiliate IN (SELECT idAffiliate FROM employeeaffiliate WHERE idEmployee = {$this->session->userdata('EMPLOYEEID')})", NULL, FALSE)
            ->where("invoices.idInvoice IS NOT NULL", NULL, false)
            ->where("invoices.idModule", 17)
            ->where('invoices.archived', NEGATIVE)
            ->group_by("so.idInvoice");
        $params['db'] = $this->db;
        $params['order_by'] = 'invoices.idInvoice desc';
        // if(isset($params['pdf']) && $params['pdf']) return $this->db->get()->result_array();
        return getGridList($params);
    }

    public function deleteAssociate($params){
        $this->db->where_in('idInvoice', $params['id'])
            ->delete(['invoices','so', 'posting']);
    }

    public function deleteAssociateChild($params){
        $this->db->where_in('idInvoice', $params['id'])
            ->delete(['so', 'posting']);
    }

    public function update($params, $table, $id){
        $this->db->where('idInvoice', $id);
        $this->db->update($table, $params);
    }

    public function retrieveAll($params){
        $invoice =  $this->db->select("
            invoices.idInvoice AS id,
            invoices.idAffiliate AS affiliate,
            invoices.idReference AS reference,
            invoices.idReferenceSeries AS series,
            invoices.amount AS amount,
            invoices.referenceNum AS seriesnum,
            invoices.date AS date,
            invoices.idCostCenter AS cost_center,
            invoices.pCode AS customer,
            customer.address AS customer_address,
            customer.tin AS customer_tin,
            customer.sk,
            invoices.remarks AS remarks,
            invoices.pickupDate AS pickup,
            invoices.status,
            invoices.cancelTag
        ")
        ->from('invoices')
        ->join('customer', 'customer.idCustomer = invoices.pCode', 'LEFT')
        ->where('invoices.idInvoice', $params['id'])
        ->get()->result_array()[0];

        $items = $this->db->select("
            so.idItem AS id,
            item.sk AS itemSK,
            item.barcode AS barcode,
            item.itemName AS name,
            itemclassification.className AS class,
            unit.unitCode AS unit,
            so.cost AS cost,
            so.qty AS qty
        ")
        ->from('so')
        ->join('item', 'so.idItem = item.idItem','LEFT')
        ->join('itemclassification', 'itemclassification.idItemClass = item.idItemClass', 'LEFT')
        ->join('unit', 'unit.idUnit = item.idUnit', 'LEFT')
        ->where('so.idInvoice', $params['id'])
        ->get()->result_array();

        $journals = []; //to be implement

        return [
            'invoice' => $invoice,
            'items' => $items,
            'journals' => $journals
        ];
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
        $this->db->where( 'invoices.idModule' , 17 );
        $this->db->where( 'invoices.archived' , 0 );

        if( isset( $params['query'] ) ) {
            $this->db->like("CONCAT( reference.code , ' - ' , invoices.referenceNum )", $params['query'], "both");
        }

        $this->db->group_by( 'invoices.idInvoice' );
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
    
    public function cancelTag( $id )
    {
        return $this->db->select( "cancelTag" )
        ->where( "invoices.idInvoice" , $id )
        ->get( "invoices" )->row()->cancelTag;
    }
}