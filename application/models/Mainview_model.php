<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mainview_model extends CI_Model {
	
	public function loadModules(){
		$this->db->select("
			mod.moduleName
			,mod.idModule
			,mod.moduleLink
			,mod.moduleType
			,mod.moduleSub
			,amod.canSave as amoduleSave
			,amod.canEdit as amoduleEdit
			,amod.canDelete as amoduleDel
			,amod.canPrint as amodulePrint
			,amod.canCancel as amoduleCancel
		");
		$this->db->from("amodule as amod");
		$this->db->join("module as mod","mod.idModule = amod.idModule");
		$this->db->where("mod.moduleArchive = 0");
		$this->db->where("amod.idEu = $this->USERID");
		$this->db->where("mod.moduleLink IS NOT NULL");
		$this->db->order_by("mod.moduleType");
		$this->db->order_by("mod.sorter asc");
		
		/** remove modules if sub affiliate **/
		// if($this->session->userdata('ISMAIN') == 0){
		// 	$this->db->where('mod.moduleID NOT IN (' . EXCLUDE_MODULES_SUB_AFFILIATE . ') ');
		// }
		
		return $this->db->get()->result_array();
	}
	
	public function checkHasReceivableSchedule(){
		$this->db->where("idEu = $this->USERID");
		$this->db->where("idModule", 60);
		return $this->db->get( 'amodule' )->row();
	}
	
	public function checkHasPayableSchedule(){
		$this->db->where("idEu = $this->USERID");
		$this->db->where("idModule", 50);
		return $this->db->get( 'amodule' )->row();
	}
	
	public function getTransDetails( $data ){
		$this->db->select("
			inv.affiliateID
			,inv.moduleID
			,(SELECT moduleType FROM module as m WHERE m.moduleID = inv.moduleID) as moduleType
		",false);
		
		if($data['invoiceID'] != 0){
			$this->db->where('inv.invoiceID', $data['invoiceID']);
			return $this->db->get('invoices as inv')->row_array();
		}
		else{
			$this->db->where('inv.bankreconID', $data['bankreconID']);
			return $this->db->get('bankrecon as inv')->row_array();
		}
	}
}