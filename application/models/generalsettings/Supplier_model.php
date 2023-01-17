<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Mark Reynor D. Magriña
 * Module: Supplier Settings
 * Date: Dec 03, 2019
 * Finished: 
 * Description: 
 * DB Tables: 
 * */
class Supplier_model extends CI_Model {

	public function getAllAffiliate( $params ){	

		if (isset( $params['idSupplier'] )){
			$this->db->select( 'a.idAffiliate,a.affiliateName,b.selected,(CASE WHEN b.selected = 1 THEN 1 ELSE 0 end) as chk, a.sk' );
			$this->db->from('affiliate as a');
			$this->db->where('a.archived', 0 );
			$this->db->join("( select * from supplieraffiliate where idSupplier = ". $params["idSupplier"]. " ) as b",'b.idAffiliate=a.idAffiliate','left outer');
		}else{
			$this->db->select( 'idAffiliate,affiliateName, sk' );
			if( isset( $params['query'] ) ) $this->db->like( 'affiliateName', $params['query'], 'both' );
			$this->db->where('affiliate.archived', 0 );
			$this->db->order_by( 'affiliateName', 'asc' );
			$this->db->from('affiliate');
		}
		return $this->db->get()->result_array();
    }
	function getCOADetails( $data ){
		$affiliates = isset($data['affiliates'])? json_decode( $data['affiliates'] ) : ['0'];
        $this->db->select("
            coa.idCoa,
            coa.acod_c15,
            coa.aname_c30
        ");
        $this->db->join('coaaffiliate', 'ON coaaffiliate.idCoa = coa.idCoa ', 'LEFT');
		$this->db->where_in('coaaffiliate.idAffiliate', $affiliates);
		return $this->db->get('coa')->result_array();
	}
	function getSupplierList($rawData,$pdf){
		$this->db->select('idSupplier as id, idSupplier,name,tin,address,contactNumber, sk');
		$this->db->from('supplier');
		$this->db->order_by('name');
		$this->db->where_not_in('archived',1);

		if( isset( $rawData['filterValue'] ) ) {
            $this->db->where( 'idSupplier', $rawData['filterValue']);
		}
		
		
		return $this->db->get()->result_array();
	}
	function getSearchItemDetails($rawData){
		$affiliates = isset($rawData['affiliates'])? json_decode( $rawData['affiliates'] ) : ['0'];
		
		$this->db->distinct();
		$this->db->select('a.idItem,a.barcode,a.itemName,c.className as itemClassification, a.sk');
		$this->db->from('item as a');
		if( isset($rawData['query'])) $this->db->like('a.itemName', $rawData['query'],'both'); 
		$this->db->order_by('a.itemName','asc');
		$this->db->where_in('b.idAffiliate', $affiliates);
		
		$this->db->join('itemaffiliate as b','b.idItem = a.idItem','left outer');
		$this->db->join('itemclassification as c','a.idItemClass = c.idItemClass','left outer');
		return $this->db->get()->result_array();
	}
	function checkDupliate($name){		
		$this->db->select('name');
		$this->db->where('name',$name);
		$this->db->from('supplier');
		return $this->db->count_all_results();		
	}	
	function checkExist($idSupplier){		
		$this->db->select('idSupplier');
		$this->db->where('idSupplier',$idSupplier);
		$this->db->from('supplier');
		return $this->db->count_all_results();		
	}
	function retrieveData($idSupplier){
		$this->db->select('a.*,b.aname_c30 as expenseGlAccName,c.aname_c30 as discountGlAccName');
		$this->db->where('a.idSupplier',$idSupplier);
		$this->db->join('coa as b','b.acod_c15 = a.expenseGlAcc','left outer');
		$this->db->join('coa as c','c.acod_c15 = a.discountGlAcc','left outer');
		return $this->db->get('supplier as a')->result_array();
	}
	function getSupplierItems($idSupplier){
		$this->db->select('a.idItem,b.barcode,b.itemName,c.idItemClass,c.className as itemClassification, b.sk');
		$this->db->where( array('a.idSupplier' => $idSupplier, 'b.archived' => 0) );
		$this->db->join('item as b','b.idItem = a.idItem','left outer');
		$this->db->join('itemclassification as c','c.idItemClass = b.idItemClass','left outer');
		return $this->db->get('supplieritems as a')->result_array();
	}
	function checkIfUsed($idSupplier){
		$this->db->select('pCode');
		$this->db->from('invoices');
		$this->db->where('pType',2);
		$this->db->where('pCode',$idSupplier);
		return $this->db->count_all_results();
	}
	function saveSupplierForm($rawData){
		$onEdit = (int)$rawData['onEdit'];
		$idSupplier = 0;
		$id = (int)$rawData['idSupplier'];
		if( $onEdit == 0 ){
			// die('new data');
			unset($rawData['idSupplier']);
			$this->db->insert('supplier',unsetParams( $rawData, 'supplier' ));		
			return $this->db->insert_id(); //return current primary key ID for other saving purposes
		}else{
			// die('edited ni, mao ni id: '.$id);			
			unset($rawData['idSupplier']);
			$this->db->where('idSupplier',$id);
			$this->db->update('supplier', unsetParams( $rawData, 'supplier' ));
		}
	}
	function saveAffiliateList($affiliateList,$idSupplier){
		$this->db->delete('supplieraffiliate', array('idSupplier' => $idSupplier));
		$this->db->insert_batch('supplieraffiliate', $affiliateList);		
	}
	function saveItemList($itemList,$idSupplier){
		$this->db->delete('supplieritems', array('idSupplier' => $idSupplier));
		$this->db->insert_batch('supplieritems', $itemList);		
	}	
	function deleteSupplierRecord($rawData){
		$setUpdateArray = array('archived'=>1);
		$match = 0;
		$this->db->select('archived');
        $this->db->where( 'idSupplier', $rawData['idSupplier'] );
        $archived = $this->db->get('supplier')->result_array()[0]['archived'];
		
		if( (int)$archived == 0 ) {
            /* SOFT DELETE ONLY */
            // $this->db->set('archived', 1, false );
            // $this->db->where('idEmpClass', $idEmpClass );
            // $this->db->update('employeeclass');
			
			$this->db->update('supplier', $setUpdateArray, array('idSupplier' => $rawData['idSupplier'] ));
        } else { $match = 2; }
		return $match;
	}
	
}