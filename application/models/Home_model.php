<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home_model extends CI_Model {
	
	public function getCompany(){
		return $this->db->query("
			SELECT
				*
			FROM affiliate
			WHERE mainTag = 1
		")->row_array();
	}
	
	public function getAllAffiliate( $params = [] ){

		$query = '';
		if( $params != [] ){
			$query = "
						SELECT 
							affiliate.idAffiliate, affiliate.affiliateName, affiliate.sk
						FROM
							employeeaffiliate
								LEFT JOIN
							employee ON employee.idEmployee = employeeaffiliate.idEmployee
								LEFT JOIN
							eu ON eu.idEmployee = employee.idEmployee
								LEFT JOIN
							affiliate ON affiliate.idAffiliate = employeeaffiliate.idAffiliate
								AND affiliate.archived = 0 AND affiliate.status = 1
						WHERE
							employee.idEmployee = '$params[idEmployee]'
						GROUP BY idAffiliate";
		} else {
			$query = "
						SELECT
							idAffiliate
							,affiliateName
						FROM affiliate
						WHERE archived = 0";
		}

		return $this->db->query( $query )->result_array();
	}

	public function verifyUser( $params ){
		if( isset( $params['username'] ) && isset( $params['password'] )){
			$this->db->select('*');
			$this->db->where( array(
					'username' 	=> $params['username'], 
					'password' 	=> md5($params['password'])
					,'archived' => 0 )
				);
			return $this->db->get('eu')->row();
		}
	}

	public function loginUser( $data ){
		$this->db->select("
			us.idEu as userID
			,emp.idEmployee as empID
			,us.username as userName
			,emp.status as employeeStatus
			,emp.name as fullName
			,emp.sk as employeeSK
			,(CASE us.userType
				WHEN 0 THEN 'System Administrator'
				WHEN 1 THEN 'Administrator'
				WHEN 2 THEN 'Supervisor'
				WHEN 3 THEN 'Staff'
			END) as userTypeName
			,us.userType as userTypeID
			,empaff.idAffiliate
			,aff.affiliateName
			,aff.tagLine as affiliateTagLine
			,aff.maintag
			,aff.reftag
			,aff.status as statusAffiliate
			,aff.logo as companyLogo
			,aff.accSchedule as affiliateAccSched
			,aff.month as affiliateMonth
			,aff.dateStart
			,aff.sk as affiliateSK
			,(SELECT COUNT(*) FROM amodule as am WHERE am.idEu = us.idEu) as module_count
			,(SELECT COUNT(*) FROM employeeaffiliate as abb where abb.idEmployee=us.idEmployee and abb.idAffiliate =". $data['affiliateID']." ) as affiliate_count
		");
		$this->db->from("eu as us");
		$this->db->join("employee as emp","emp.idEmployee = us.idEmployee","left outer");
		$this->db->join("employeeaffiliate as empaff","empaff.idEmployee = us.idEmployee","left outer");
		$this->db->join("affiliate as aff","aff.idAffiliate = empaff.idAffiliate","left outer");
		// $this->db->join("companyaffiliate as comp","comp.companyaffiliateID = 1");
		
		// $this->db->where("emp.status",0);
		if( isset($data['config_idEu']) ){
			$this->db->where("us.idEu", $data['config_euID']);
		}
		else{
			$this->db->where("us.username",$data['username']);
			$this->db->where("us.password",md5($data['password']));
			$this->db->where("empaff.idAffiliate",$data['affiliateID']);
		}
		
		return $this->db->get()->row_object();
		// return $user;
		// $user = $this->db->get()->row_object();
	}
	
	public function getAffiliate(){
		$this->db->select("affiliateID,affiliateName");
		$this->db->where("status",1);
		$this->db->where("affiliateID != " . $this->AFFILIATEID);
		$this->db->from("affiliate");
		$this->db->order_by("affiliateName");
		return $this->db->get()->result_array();
	}
	
	public function getAdjustments(){
		// $this->db->select("inv.invoiceID,CONCAT(ref.code,' - ',inv.referenceNo) as reference");
		// $this->db->from("invoices as inv");
		// $this->db->join("reference as ref","ref.refID = inv.referenceID");
		// $this->db->where("inv.adjustmentconfirmed", 0);
		// $this->db->where("inv.moduleID", 30);
		// $this->db->where("inv.affiliateID = " . $this->AFFILIATEID);
		// $this->db->order_by("inv.invoiceID desc");


		$this->db->select("invoices.idInvoice, CONCAT(reference.code,'-',invoices.referenceNum) as reference, invoices.idModule");
		$this->db->from("invoices");
		$this->db->join("reference", "reference.idReference = invoices.idReference", "LEFT");
		$this->db->join("affiliateapprover", "affiliateapprover.idAffiliate = " . $this->AFFILIATEID, "LEFT");
		// $this->db->join("affiliate as approver1", "approver1.idAffiliate = $this->AFFILIATEID", "LEFT");
		// $this->db->join("affiliate as approver2", "approver2.idAffiliate = $this->AFFILIATEID", "LEFt");
		$this->db->where("invoices.status", 1);
		$this->db->where_in("invoices.idModule", [48, 23]);
		$this->db->where("invoices.idAffiliate", $this->AFFILIATEID );
		$this->db->where("affiliateapprover.idEmployee", $this->EMPLOYEEID );
		// $this->db->where("approver1.approvedBy1", $this->EMPLOYEEID );
		// $this->db->or_where("approver2.approvedBy2", $this->EMPLOYEEID);
		$this->db->order_by("invoices.idInvoice desc");
		return $this->db->get()->result_array();
	}



	
	public function saveInitialRecords( $data ){
		
		/** company details **/
		$record = array(
			'companyName' => $data['affiliateName']
			,'companyAddress' => $data['address']
			,'TIN' => $data['tin']
			,'datestart' => $data['datestart']
			,'accountingsched' => $data['accountingsched']
			,'month' => $data['month']
			,'versionID' => $this->standards->getLastestSystemDetailsRecord()->versionID
		);
		$this->db->insert( 'companydetails' ,unsetParams( $record, 'companydetails' ) );
		
		/** company affiliate **/
		$record = array(
			'companyaffiliateName' => $data['affiliateName']
			,'companyaffiliateTagLine' => ''
		);
		$this->db->update( 
			'companyaffiliate'
			,unsetParams( $record, 'companyaffiliate' )
			,array( 'companyaffiliateID' => 1 )
		);
		
		/** affiliate **/
		$data['status'] = 1;
		$data['affLogo'] = $this->DEFAULT_COMPLOGO;
		$this->db->insert( 'affiliate' ,unsetParams( $data, 'affiliate' ) );
		$affiliateID =  $this->db->insert_id();
		
		/** update system admin user affilaiteID **/
		$record = array(
			'affiliateID' => $affiliateID
		);
		$this->db->update( 
			'eu'
			,unsetParams( $record, 'eu' )
			,array( 'euID' => $this->USERID )
		);
	}

	// check user credentials
	function checkUserLogin( $params ){
		$this->db->select( '*' );
		$this->db->from('eu');
		$this->db->where(  'euName', $params[ 'adminUsernameinitialCompanySetup_mainView' ]);
		$this->db->where( 'eupword', md5( $params[ 'adminPassinitialCompanySetup_mainView' ] ) );
		
		return $this->db->get()->result_array();
		// LQ();
		
	}
	
	function getAffiliateDetI( $affID ){
		$this->db->where( 'affiliateID', $affID );
		return $this->db->get( 'affiliate' )->row();
	}

	function getAffiliateByusername($username){
		$this->db->select('affiliate.idAffiliate AS id, affiliate.affiliateName as name, affiliate.sk');
		$this->db->from('eu');
		$this->db->join('employeeaffiliate', 'employeeaffiliate.idEmployee = eu.idEmployee', 'LEFT');
		$this->db->join('affiliate', 'employeeaffiliate.idAffiliate = affiliate.idAffiliate', 'LEFT');
		$this->db->where( array( 'eu.username' => $username, 'affiliate.status' => 1, 'affiliate.archived' => 0 ) );
		$this->db->where_not_in('eu.archived',1);
		return $this->db->get()->result_array();
	}

	function checkAffiliate( $params ){
		$this->db->select('affiliate.idAffiliate, employee.idEmployee');
		$this->db->join('employee', 'employee.idEmployee = employeeaffiliate.idEmployee', 'LEFT');
		$this->db->join('eu', 'eu.idEmployee = employee.idEmployee', 'LEFT');
		$this->db->join('affiliate', 'affiliate.idAffiliate = employeeaffiliate.idAffiliate and affiliate.archived = 0', 'LEFT');
		$this->db->where( array('employee.idEmployee' => $params['idEmployee'], 'affiliate.idAffiliate' => $params['idAffiliate'] ) );
		return $this->db->get('employeeaffiliate')->row();
	}

	function getAffiliateDetails( $params ){
		$this->db->select("*");
		$this->db->where('idAffiliate', $params['idAffiliate']);
		return $this->db->get('affiliate')->row();
	}

	/** Added by Hazel
	 *  Date: 06-17-2020
	 * 
	 * This for the additional request by the client to add notifications for Reorder level per item.
	 * **/
	function getItemReorderLvl(){
		$this->db->select(' item.idItem
							,item.itemName
							,item.sk
							,item.reorderLevel
							,IFNULL(SUM( receiving.qty ),0) AS receivingQty
							,IFNULL(SUM( releasing.qty ),0) AS releasingQty');
		$this->db->join('(	SELECT receiving.*  
							FROM receiving 
							JOIN invoices ON invoices.idInvoice = receiving.idInvoice
							WHERE idModule IN (21 , 22 , 23 , 25 , 43 )
							AND archived = 0
							AND cancelTag = 0) AS receiving', 'receiving.idItem = item.idItem', 'LEFT');
		$this->db->join('(	SELECT releasing.*
							FROM releasing 
							JOIN invoices ON invoices.idInvoice = releasing.idInvoice
							WHERE idModule IN (18 , 22 , 23 , 29 , 43)
							AND archived = 0
							AND cancelTag = 0) AS releasing', 'releasing.idItem = item.idItem', 'LEFT');
		$this->db->join('itemaffiliate', 'itemaffiliate.idItem = item.idItem', 'LEFT');
		$this->db->where('itemaffiliate.idAffiliate', $this->AFFILIATEID );
		$this->db->group_by('item.idItem');
		return $this->db->get('item')->result_array();
	}

	function CHECK_EXISTS($name, $name_col, $table, $ref = null){
		$this->db->where($name_col, $name);
		$this->db->limit(1);
		$this->db->from($table);
		if($ref) 					$this->db->where('ref', $ref);
		if($this->Carchive($table))$this->db->where_not_in('archive',1);
		return $this->db->count_all_results();
	}

	function Carchive($table){
		return $this->db->query("SHOW COLUMNS FROM ".$table." LIKE 'archive'")->result_array();
	}
}