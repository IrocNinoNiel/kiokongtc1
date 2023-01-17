<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventoryconversion_model extends CI_Model 
{   
    // SUM( IFNULL(releasing.qty,0) ) as remaining,

    public function getItems($data){
        $this->db->select(
            ' item.idItem,
            item.sk as itemSK,
            item.barcode,
            unit.unitCode as unit,
            itempricehistory.itemPrice as cost,
            item.itemName as name '
        );
        $this->db->join('itempricehistory', 'itempricehistory.idItem = item.idItem and itempricehistory.effectivityDate <= CURDATE()');
        $this->db->join('unit', 'unit.idUnit = item.idUnit');
        $this->db->group_by('item.idItem, itempricehistory.itemPrice');
        $query = $this->db->get('item');
        
        // Added by makmak, get remaining balance while avoiding non aggregated problem
        $data_arr = $query->result_array();
        $data = array();

        foreach ($data_arr as $row ) {
            $tmp = $row;
            $rec = $this->get_rQty( $tmp['idItem'], $this->session->userdata('AFFILIATEID'), 'receiving' );
            $rel = $this->get_rQty( $tmp['idItem'], $this->session->userdata('AFFILIATEID'), 'releasing' );
            $tmp['remaining'] = $rec - $rel;
            array_push( $data, $tmp );
        }

        return $data;

        // $field = isset($data['from'])? $data['from'] : 'barcode';

        //RECEIVING 
        //{
            // if( isset( $data['query'] ) ) $this->db->like($field, $data['query']);
            // $this->db->SELECT( "MAX(idReceiving) AS idReceiving, item.idItem , count( distinct( item.idItem ) ) as numOfItem" );
            // $this->db->FROM( 'receiving' );
            // $this->db->JOIN( 'item' , 'item.idItem = receiving.idItem' );
            // $this->db->WHERE( 'qtyLeft > 0' );
            // $this->db->GROUP_BY( "item.idItem" );
            // $currentReceived = $this->db->GET()->RESULT_ARRAY();
            // $this->db->reset_query();

            // $totalItem = 0;
            // $maxIdReceiving = 0;
            // $idItem = 0;
            // foreach ($currentReceived as $currRec) { 
            //     $totalItem += $currRec['numOfItem']; 
            //     $maxIdReceiving = $currRec['idReceiving'];
            //     $idItem = $currRec['idItem'];
            // }

            // $this->db->SELECT( '( SUM( IFNULL( receiving.qty , 0 ) ) - SUM( IFNULL( releasing.qty , 0 ) ) ) AS remaining' );
            // $this->db->WHERE( 'receiving.idItem' , $idItem );
            // $this->db->JOIN( "
            //     (  SELECT
            //         releasing.fIdent 
            //         , releasing.idItem 
            //         , releasing.idInvoice
            //         , sum( ifnull(releasing.qtyLeft , 0) ) as qty
            //         FROM releasing

            //         LEFT JOIN invoices  
            //         ON invoices.idInvoice = releasing.idInvoice  
            //         WHERE invoices.archived = 0
            //         AND invoices.cancelTag  = 0
                    
            //         group by releasing.fIdent , releasing.idItem , releasing.idInvoice
            //     ) AS releasing" 
            //     , "releasing.fIdent = receiving.idReceiving AND releasing.idItem = receiving.idItem"  
            //     , "LEFT"
            // );

        //     $this->db->select(' releasing.fIdent,
        //                         releasing.idInvoice,
        //                         releasing.idItem,
        //                         sum( ifnull( releasing.qtyLeft, 0 ) ) as qty');
        //     $this->db->join('invoices', 'invoices.idInvoice = releasing.idInvoice', 'left');
        //     $this->db->where( array('invoices.archived' => 0, 'invoices.cancelTag' => 0, 'releasing.idItem' => $idItem ) );
        //     $this->db->group_by('releasing.fIdent, releasing.idItem, releasing.idInvoice');
        //     $remainingQTY = $this->db->get('releasing')->row();

        //     print_r( $idItem );
        //     die();
        //     // $remainingQTY = $this->db->GET( 'receiving' )->ROW()->remaining;
        //     $this->db->RESET_QUERY();
        // }


        // if(isset($data['has_receiving']) && $data['has_receiving'] === 'yes'){
        //     $this->db->select('receiving.cost AS cost, "'.$remainingQTY.'" AS remaining');
        //     $this->db->join('(SELECT MAX(idReceiving) AS idReceiving, idItem FROM receiving GROUP BY idItem) AS currentReceive','currentReceive.idItem = item.idItem');
        //     $this->db->join("(SELECT receiving.* FROM receiving RIGHT JOIN invoices ON invoices.idInvoice = receiving.idInvoice WHERE invoices.status = 2 AND invoices.cancelTag = 0 AND invoices.archived = 0 AND invoices.date <= '{$data['transaction_date']}')  AS receiving", 'receiving.idItem = currentReceive.idItem', 'LEFT');
        //     $this->db->where('receiving.qtyLeft > 0');
        //     $this->db->group_by("receiving.cost");
        // }
        // $this->db->select("
        //     item.idItem,
        //     item.sk AS itemSK,
        //     item.barcode,
        //     item.itemName AS name,
        //     unit.unitCode AS unit,
        //     IFNULL(itempricehistory.itemPrice, item.itemPrice) AS price,
        //     '{$data['qty']}' AS qty,
        //     '0' AS total,
        //     '' AS expirydate
        // ")
        //     ->from('item')
        //     ->join('itemaffiliate', 'item.idItem = itemaffiliate.idItem', 'LEFT')
        //     ->join('unit', 'unit.idUnit = item.idUnit', 'LEFT')
        //     ->where('itemaffiliate.idAffiliate', $this->session->userdata('AFFILIATEID'))
		// 	->join("itempricehistory", "itempricehistory.idItem = item.idItem AND itempricehistory.effectivityDate = (SELECT MAX(effectivityDate) FROM itempricehistory WHERE itempricehistory.effectivityDate <= CURDATE() AND itempricehistory.idItem = item.idItem)", "LEFT")
        //     ->group_by(' item.barcode , item.itemName , unit.unitCode, item.idItem, itempricehistory.itemPrice');

        // if( isset( $data['query'] ) ) $this->db->like($field, $data['query']);
        // if( isset( $data['barcode'] ) ) $this->db->where_not_in('item.barcode',[$data['barcode']]);
        // if ( $totalItem == 1 ) $this->db->WHERE( 'receiving.idReceiving' , $maxIdReceiving );

        // return $this->db->get()->result_array();
    }
    
    function get_rQty( $item_id, $affl_id, $tbl )
    {
        $this->db->select( 'SUM(qty) as qty' );

        if( $tbl == 'receiving' ) {
            $this->db->join( 'invoices', 'invoices.idInvoice = receiving.idInvoice' );
            $this->db->where_in( 'idModule', [ 21 , 22 , 23 , 25 , 29, 43 ] );
        }
        else { 
            $this->db->join( 'invoices', 'invoices.idInvoice = releasing.idInvoice' );
            $this->db->where_in( 'idModule', [ 18 , 22 , 23 , 29 , 43 ] );
        }

        $this->db->where( 'idItem', $item_id );
        $this->db->where( 'idAffiliate', $affl_id );
        $this->db->where_not_in( 'archived', [1] );
        $this->db->where_not_in( 'cancelTag', [1] );
        return $this->db->get( $tbl )->row()->qty;
    }

    public function cancelTag( $id )
    {
        return $this->db->select( "cancelTag" )
        ->where( "invoices.idInvoice" , $id )
        ->get( "invoices" )->row()->cancelTag;
    }

    public function getItemsCost($data){
        $this->db->select("
            IFNULL(SUM(receiving.cost), 0) AS cost,
            IFNULL(SUM(receiving.qtyLeft), 0) AS qtyLeft
        ")
            ->from('item')
            ->join('receiving', 'receiving.idItem = item.idItem', 'LEFT')
            ->where('item.barcode', $data['barcode'])
            ->group_by('item.idItem,receiving.cost');
        if( isset($data['query'])){
            $this->db->like('receiving.cost', $data['query']);
        }

        return $this->db->get()->result_array();
    }

    public function insert($data,$table){
        $this->db->insert($table,$data);
        return $this->db->insert_id();
	}

	public function deleteAssociateChild($params){
        $this->db->where_in('idInvoice', $params['id'])
            ->delete(['receiving', 'posting', 'releasing']);
    }
    
    public function received($data){
        $this->db->insert_batch('receiving', $data); 
    }

    public function released($data, $releaseData, $affiliate){
        foreach ($releaseData as $key => $item){
            $items = $this->db->select('receiving.idReceiving, receiving.qtyLeft, receiving.idItem')
				->from('receiving')
				->join('invoices', 'invoices.idInvoice = receiving.idInvoice', 'LEFT')
				->where('receiving.qtyLeft > 0',NULL, false)
				->where('receiving.idItem', $item['idItem'])
                ->where('invoices.idAffiliate', $affiliate)
                ->where('invoices.status', APPROVED)
		        ->where("invoices.archived", NEGATIVE)
				->get()->result_array();
            $released = (int) $item['qty'];
			foreach($items as $indx => $it){
				$this->db->where('idReceiving', $it['idReceiving']);
				$left = (int) $it['qtyLeft'];
				if($released <= 0 ) break;
				if($released > $left){
					$released = $released - $left;
                    $this->db->update('receiving',['qtyLeft' => 0]);
                    $this->saveRelease($releaseData, $it['idReceiving'],  $left);
				}else{
                    $total = $released;
					$qty = $left - $released;
                    $released = 0;
                    $this->db->update('receiving',['qtyLeft' => $qty]);
                    $this->saveRelease($releaseData, $it['idReceiving'], $total);
				}
			}
		}
    }

    public function viewAll($data){
        if( isset( $data['filterBy'] ) ){
            if( isset( $data['filterValue'] ) ){
                if( (int)$data['filterValue'] > 0 ) $this->db->where(  'invoices.'.$data['filterBy'], (int)$data['filterValue'] );
            }
        }
        if( isset( $data['subFilter0'] ) ){
            if( isset( $data['query0'] ) ){
                if( (int)$data['query0'] > 0 ) $this->db->where( 'invoices.'.$data['subFilter0'], (int)$data['query0'] );
            }
            $data['subFilter0'] = NULL;
        }
        $this->db->where( 'invoices.idAffiliate', $this->session->userdata('AFFILIATEID') );
        $this->db->select("
            invoices.idInvoice AS id,
            affiliate.affiliateName AS affiliate,
            location.locationName AS location,
            DATE_FORMAT(invoices.date, '%Y-%m-%d') AS date,
            CONCAT(reference.code,' - ', invoices.referenceNum) AS reference,
            invoices.remarks AS remarks,
            releasedItem.itemName AS item,
            releasedItem.sk as itemSK,
            releasing.cost AS cost,
            unit.unitCode AS unit,
            SUM(releasing.qty) * releasing.cost AS amount,
            SUM(releasing.qty) AS qty
        ")
            ->from('invoices')
            ->join('releasing','releasing.idInvoice = invoices.idInvoice','LEFT')
            ->join('item AS releasedItem',' releasedItem.idItem = releasing.idItem','LEFT')
            ->join('unit','releasedItem.idUnit = unit.idUnit','LEFT')
            ->join('affiliate','invoices.idAffiliate = affiliate.idAffiliate','LEFT')
            ->join('location','location.idLocation = invoices.idLocation','LEFT')
            ->join('reference','reference.idReference = invoices.idReference','LEFT')
            ->where('invoices.idModule', 22)
            ->where('invoices.archived', NEGATIVE)
            ->where("invoices.idAffiliate IN (SELECT idAffiliate FROM employeeaffiliate WHERE idEmployee = {$this->session->userdata('EMPLOYEEID')})", NULL, FALSE)
            ->group_by('invoices.idInvoice,
            releasedItem.idItem,
            releasing.idReleasing');
        $data['db'] = $this->db;
        $data['order_by'] = 'invoices.idInvoice DESC';
        return getGridList($data);
    }

    public function getInvoices($where){
        $this->db->select("
            idInvoice,
            idAffiliate,
            idReference,
            referenceNum,
            status,
            DATE_FORMAT(date, '%Y-%m-%d') AS date,
            DATE_FORMAT(date, '%h:%i %p') AS time,
            remarks,
            amount
        ")
        ->from('invoices')
        ->where($where);

        return $this->db->get()->result_array();
    }

    public function retrieveBulk($where){
        $this->db->select("
            item.idItem AS idItem,
            item.barcode,
            item.itemName AS name,
            unit.unitCode AS unit,
            SUM(receiving.qtyLeft + releasing.qty) AS remaining,
            SUM(releasing.qty) AS qty,
            releasing.cost AS cost,
            SUM(releasing.cost * releasing.qty) AS total,
            item.sk     
        ")
        ->from('releasing')
        ->join(' (
            SELECT
            SUM(qtyLeft) AS qtyLeft,
            idItem
            FROM receiving
            GROUP BY idItem
        ) AS receiving','receiving.idItem = releasing.idItem','LEFT')
        ->join('item','item.idItem = releasing.idItem','LEFT')
        ->join('unit','item.idUnit = unit.idUnit','LEFT')
        ->group_by('releasing.idItem, releasing.idReleasing')
        ->where($where);

        return $this->db->get()->result_array();
    }
    public function retrieveConverted($where){
        $this->db->select("
            item.idItem,
            item.barcode,
            item.itemName AS name,
            unit.unitCode AS unit,
            receiving.expiryDate AS expirydate,
            receiving.qty AS qty,
            itempricehistory.itemPrice AS cost,
            (receiving.qty * itempricehistory.itemPrice) AS total,
            item.sk
        ")
        ->from('receiving')
        ->join('item','item.idItem = receiving.idItem','LEFT')
        ->join('unit','unit.idUnit = item.idUnit','LEFT')
        ->join('itempricehistory', 'itempricehistory.idItem = item.idItem and itempricehistory.effectivityDate <= CURDATE()')
        ->where($where);

        return $this->db->get()->result_array();
    }

    public function returnReleased($where){
        $items = $this->db->select("fident, qty")->from('releasing')->where($where)->get()->result_array();
        foreach($items as $key => $item){
            $this->db->set( 'qtyLeft', '( qtyLeft + ' . $item['qty'] . ' )', false );
            $this->db->where( 'idReceiving', $item['fident'] );
            $this->db->update( 'receiving' );
        }
    }

    public function update($table, $data, $where){
        $this->db->update($table, $data, $where);
    }

    public function selectInvoices($select, $where){
        return $this->db->select($select)->from('invoices')->where($where)->get()->result_array();
    }

    public function insertBulk($data, $table){
        $this->db->insert_batch($table, $data); 
    }
    
    private function saveRelease($data, $fident, $qty){
        foreach($data as $key => $item){
            $item['fident'] = $fident;
            $item['qty'] = $qty;
            $item['qtyLeft'] = $qty;
            $this->db->insert('releasing',$item); 
        }


        // print_r( $data );
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
        $this->db->where( 'invoices.idModule' , INVENTORY_CONVERSION );
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
}