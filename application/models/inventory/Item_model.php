<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Mark Reynor D. Magriña
 * Module: Item Settings
 * Date: Dec 05, 2019
 * Finished: 
 * Description: 
 * DB Tables: 
 * */
class Item_model extends CI_Model {

	public function getAllAffiliate( $params ){	

		if (isset( $params['idItem'] )){
			$this->db->select( 'affiliate.idAffiliate,affiliate.affiliateName,b.selected,(CASE WHEN b.selected = 1 THEN 1 ELSE 0 end) as chk' );
			$this->db->from('affiliate');
			$this->db->where('affiliate.archived', 0 );
			$this->db->join("( select * from itemaffiliate where idItem = ". $params["idItem"]. " ) as b",'b.idAffiliate=affiliate.idAffiliate','left');
		}else{
			$this->db->select( 'idAffiliate,affiliateName' );
			if( isset( $params['query'] ) ) $this->db->like( 'affiliateName', $params['query'], 'both' );
			$this->db->where('affiliate.archived', 0 );
			$this->db->order_by( 'affiliateName', 'asc' );
			$this->db->from('affiliate');
		}

		
		return $this->db->get()->result_array();
    }
	function getItemClassification( $data ){
		$this->db->select("*");
		$this->db->from("itemclassification");
		return $this->db->get()->result_array();
	}
	function getItemUnit( $data ){
		$this->db->select("*");
		$this->db->from("unit");
		return $this->db->get()->result_array();
	}
	function getItemListDetails( $data ){
		$this->db->distinct(); 
		$this->db->select("
			item.idItem
			,item.barcode
			,item.itemName
			,item.reorderLevel			
			,itemclassification.className,unit.unitName
			,item.itemPrice
			,item.effectivityDate
			,item.sk
		", false);
		$this->db->from("item");
		$this->db->join("itemaffiliate", "itemaffiliate.idItem = item.idItem", 'left');
		// $this->db->where("itemaffiliate.idAffiliate", $this->session->userdata('AFFILIATEID'));
		if( isset($data['itemNameSearch']) ){
			$this->db->like('item.itemName',$data['itemNameSearch'],'both');
		}
		// $this->db->where("itempricehistory.effectivityDate = ( select effectivityDate from itempricehistory where itempricehistory.idItem = `item`.`idItem` ORDER BY effectivityDate DESC limit 1 )");
		// $this->db->where("itempricehistory.effectivityDate =  CURDATE() ");
		
		$this->db->join('itemclassification','itemclassification.idItemClass = item.idItemClass','left outer');
		$this->db->join('unit','unit.idUnit = item.idUnit','left outer');
		$this->db->join("
				(select itempricehistory.idPrice,itempricehistory.idItem, itempricehistory.itemPrice, derivedItemPriceHistory.effectivityDate FROM itempricehistory
				join ( select max( idPrice) as idPrice, max(effectivityDate) as effectivityDate from  itempricehistory WHERE effectivityDate <= CURDATE() group by idItem order by effectivityDate,idPrice DESC ) as derivedItemPriceHistory ON( derivedItemPriceHistory.idPrice = itempricehistory.idPrice AND derivedItemPriceHistory.effectivityDate = itempricehistory.effectivityDate ) ) as itempricehistory", 'itempricehistory.idItem = item.idItem','LEFT' );
		
		
			// (select max( idPrice) as idPrice,itemPrice,idItem,effectivityDate from itempricehistory WHERE effectivityDate <= CURDATE() order by effectivityDate,idPrice DESC) 
			// as itempricehistory" ,'itempricehistory.idItem = item.idItem','LEFT');
			
			
			/* (select itemPrice,idItem,effectivityDate from itempricehistory WHERE effectivityDate <= CURDATE() group by idItem order by effectivityDate,idPrice DESC) 
			as itempricehistory" ,'itempricehistory.idItem = item.idItem','LEFT'); */
		
		/* $this->db->join("
			(select idItem,itemPrice,effectivityDate from itempricehistory WHERE effectivityDate <= CURDATE() group by idItem order by idPrice  DESC) 
			as itempricehistory" ,'itempricehistory.idItem = item.idItem','LEFT');
		 */	
		// $this->db->join("(select idItem,itemPrice,effectivityDate from itempricehistory where effectivityDate = CURDATE() and idItem = `item`.`idItem` order by effectivityDate DESC limit 1) as itempricehistory" ,'itempricehistory.idItem = item.idItem','LEFT');	
		
		// $this->db->order_by('itemName');
		// return $this->db->get()->result_array();
		

		$this->db->where('item.archived', 0 );
		
		$data['db'] = $this->db;
		$data['order_by'] = 'itemName';
		return getGridList($data);
		
	}

	function getSearchedItems( $data ){
		$this->db->select("idItem,itemName, sk");
		$this->db->from("item");
		if( isset($rawData['query'])) $this->db->like('itemName', $rawData['query'],'both'); 
		return $this->db->get()->result_array();
	}

	function getCOADetails( $data ){
		$affiliates = json_decode( $data['affiliates'], true );

		$this->db->select("coa.idCoa,coa.acod_c15,coa.aname_c30");
		$this->db->join( 'coaaffiliate', 'coaaffiliate.idCoa = coa.idCoa', 'left' );
		$this->db->where_in( 'coaaffiliate.idAffiliate', ( count( (array)$affiliates )> 0? $affiliates : 0 ) );
		$this->db->group_by( 'coa.idCoa' );
		return $this->db->get('coa')->result_array();
	}

	function checkDupliate($barcode){		
		$this->db->select('barcode');
		$this->db->where('barcode',$barcode);
		$this->db->where('archived', 0);
		$this->db->from('item');
		return $this->db->count_all_results();		
	}

	function checkExist($idItem){		
		$this->db->select('idItem');
		$this->db->where('idItem',$idItem);
		$this->db->from('item');
		return $this->db->count_all_results();		
	}

	function retrieveData($idItem){
		$this->db->select('a.*
							,b.aname_c30 as salesGlAccName
							,c.aname_c30 as inventoryGlAccName
							,d.aname_c30 as costofsalesGlAccName
							,group_concat( itemaffiliate.idAffiliate ) as affiliates');
		$this->db->where('a.idItem',$idItem);
		$this->db->join('coa as b','b.acod_c15 = a.salesGlAcc','left outer');
		$this->db->join('coa as c','c.acod_c15 = a.inventoryGlAcc','left outer');
		$this->db->join('coa as d','d.acod_c15 = a.costofsalesGlAcc','left outer');
		$this->db->join('itemaffiliate', 'a.idItem = itemaffiliate.idItem', 'INNER');
		$this->db->group_by('itemaffiliate.idItem, b.idCoa, c.idCoa, d.idCoa');
		return $this->db->get('item as a')->result_array();
	}
	// function itemPriceHistoryList($itemPriceHistoryList,$idItem){
		// $this->db->insert_batch('itempricehistory');
		
	// }
	function getItemPriceHistoryDetails($rawData){
		$this->db->select('item.idItem,itempricehistory.itemPrice,itempricehistory.effectivityDate, item.sk');
		$this->db->where('itempricehistory.idItem', (int)$rawData['idItem']);
		$this->db->where('itempricehistory.effectivityDate >=', $rawData['sdate']);
		$this->db->where('itempricehistory.effectivityDate <=', $rawData['edate']);
		$this->db->join('item', 'item.idItem = itempricehistory.idItem', 'LEFT');
		
		$this->db->from('itempricehistory');
		return $this->db->get()->result_array();
	}

	function saveItemForm($rawData){
		$onEdit = (int)$rawData['onEdit'];
		$idItem = 0;
		$id = (int)$rawData['idItem'];
		if( $onEdit == 0 ){
			// die('new data');
			unset($rawData['idItem']);
			$this->db->insert('item',unsetParams( $rawData, 'item' ));
			$id = $this->db->insert_id();
		}else{
			// die('edited ni, mao ni id: '.$id);			
			unset($rawData['idItem']);
			$this->db->where('idItem',$id);
			$this->db->update('item', unsetParams( $rawData, 'item' ));
		}

		$rawData['idItem'] = $id;
		$this->saveFirstPrice( $rawData );
		return $id;
	}

	function saveAffiliateList($affiliateList,$idItem){
		$this->db->delete('itemaffiliate', array('idItem' => $idItem));
		$this->db->insert_batch('itemaffiliate', $affiliateList);		
	}

	function saveFirstPrice($rawData){
		$this->db->insert('itempricehistory', unsetParams( $rawData, 'itempricehistory' ));
	}

	function saveItemPriceHistory($itemPriceHistoryList,$idItem){
		$this->db->delete('itempricehistory', array( 'idItem' => $idItem ));
		$this->db->insert_batch('itempricehistory', unsetParamsBatch($itemPriceHistoryList, 'itempricehistory' ) );
	}

	function checkUsage($idItem){
		$this->db->select("item.idItem");
		$this->db->from('supplieritems as item');
		$this->db->where('item.idItem',$idItem);		
		$this->db->join('customeritems','customeritems.idItem = item.idItem','left');
		$this->db->join('receiving','receiving.idItem = item.idItem','left');
		$this->db->join('releasing','releasing.idItem = item.idItem','left');
		return $this->db->count_all_results();
	}

	function deleteItemRecord($idItem){
		$setUpdateArray = array('archived'=>1);
		$match = 0;
		$this->db->select('archived');
        $this->db->where( 'idItem', $idItem );
		$archived = $this->db->get('item')->result_array()[0]['archived'];
		if( (int)$archived == 0 ) {
            /* SOFT DELETE ONLY */
			$this->db->update('item', $setUpdateArray, array('idItem' => $idItem ));
        } else { $match = 2; }
		return $match;
	}

	function getAffiliates( $params ){
		$this->db->select('affiliate.idAffiliate, affiliate.affiliateName, affiliate.sk, 0 as chk');
		$this->db->where('affiliate.archived', 0 );
		return $this->db->get('affiliate')->result_array();
	}
	
	function getClassID( $className ){
		$this->db->select('*');
		$this->db->where('className', $className );
		$this->db->where('archived', 0 );
		return $this->db->get('itemclassification')->row_array();
	}

	function getUnitID( $unitName ){
		$this->db->select('*');
		$this->db->where('unitName', $unitName );
		$this->db->where('archived', 0 );
		return $this->db->get('unit')->row_array();
	}
	
	function saveUnit( $unitName )
	{
		$data = array(
			'unitCode' => $unitName, 
			'unitName' => $unitName, 
			'archived' => 0
		);
		$this->db->insert( 'unit', $data );
		return $this->db->insert_id();
	}

	function saveClass( $className )
	{
		$data = array(
			'classCode' => $this->getClassCode(), 
			'className' => $className, 
			'archived' => 0
		);
		$this->db->insert( 'itemclassification', $data );
		return $this->db->insert_id();
	}

	function getClassCode(){
        $this->db->select("LPAD( IFNULL(MAX(idItemClass),0)+1, 5, 0) as idItemClass");
        return $this->db->get('itemclassification')->row()->idItemClass;
	}
	
	function saveItem($params)
	{
		$data = array(
			'archived' => 0,
			'sk' => $params['sk'], 
			'barcode' => $params['barcode'], 
			'itemName' => $params['itemName'], 
			'idItemClass' => $params['idItemClass'],
			'itemPrice' => $params['itemPrice'],
			'effectivityDate' => $params['effectivityDate'],
			'reorderLevel' => $params['reorderLevel'],
			'idUnit' => $params['idUnit']
		);
		$this->db->insert( 'item', $data );
		return $this->db->insert_id();
	}

	// check if item is used in a transaction
	function checkItemUsed($idItem){
		$this->db->select("item.idItem");
		$this->db->from('item');
		$this->db->where('item.idItem', $idItem);
		$this->db->where('item.archived', 0);

		$this->db->join('po','po.idItem = item.idItem','RIGHT');
		$this->db->join('so','so.idItem = item.idItem','RIGHT');
		$this->db->join('receiving','receiving.idItem = item.idItem','RIGHT');
		$this->db->join('releasing','releasing.idItem = item.idItem','RIGHT');
		$this->db->join('invadjustment','invadjustment.idItem = item.idItem','RIGHT');
		$this->db->join('stocktransfer','stocktransfer.idItem = item.idItem','RIGHT');
		
		return $this->db->count_all_results() > 0? true : false;
	}
}