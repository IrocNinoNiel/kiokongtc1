<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Mark Reynor D. Magriña
 * Module: User Settings
 * Date: Oct 24, 2019
 * Finished: 
 * Description: The User Setting module allows authorized users to add, edit or delete users in the system.
 * DB Tables: eu, amodules
 * */
class Usersettings_model extends CI_Model {

    public function getHistory( $params ){
        return array(
            'view'      => array()
            ,'count'    => 0
        );
    }
	
	function getEmployeeUserList($rawData,$pdf){
		$this->db->select(" 
			a.idEmployee,a.idNumber,a.name
			,b.classification
			,d.empClassName
			,c.username
			,c.idEu
			,c.userType as userTypeNum
			,(CASE
				WHEN c.userType = 0 THEN 'Super Admin'
				WHEN c.userType = 1 THEN 'Administrator'
				WHEN c.userType = 2 THEN 'Supervisor'
				WHEN c.userType = 3 THEN 'User'
				ELSE ''
			END) as userType,
			(CASE
				WHEN a.status = 0 THEN 'Active'
				ELSE 'Inactive'
			END) as status
			,a.sk
		");
		
		if( isset($rawData['filterBy']) && $rawData['filterBy'] == 'idEmployee' ){
			// echo 'filter by Emp ID';
			$this->db->where('a.idEmployee', $rawData['filterValue']);
		}
		if( isset($rawData['filterBy']) && $rawData['filterBy'] == 'name' ){
			// echo 'filter by Emp Name';
			$this->db->like('a.name',$rawData['filterValue'],'both');
		}
		if( isset($rawData['filterBy']) && $rawData['filterBy'] == 'idEmpClass' ){
			// echo 'filter by Emp Class';
			$this->db->where('b.classification', $rawData['filterValue'] );
		}
		
		$this->db->join("employment as b",'b.idEmployee = a.idEmployee','left outer');
		$this->db->join('eu as c', 'c.idEmployee = a.idEmployee','left outer');
		$this->db->join("employeeclass as d",'d.idEmpClass = b.classification','left outer');
		$this->db->where_not_in('a.archived',1);
		$this->db->from('employee as a');
		// $this->db->order_by('name');
		
		$rawData['db'] = $this->db;
		$rawData['order_by'] = 'a.name';
		return getGridList($rawData);
		// return $this->db->get('employee as a')->result_array();
		
		
	}
	
	/* test code */
	
	function retrieveEmployeeClassificationDetails($data,$pdf){
		$this->db->select('idEmpClass,empClassName');
		$this->db->where_not_in('archived', 1);
		$this->db->from('employeeclass');
		$data['db'] = $this->db;
		$data['order_by'] = 'empClassName';
		return getGridList($data);
	}	
	
	
	
	function retrieveContributionQuickSettingDetails($data,$pdf){
			$this->db->select('idcontribution,contributionName');
		if(!$pdf){
			$this->db->limit($data['limit'],$data['start']);
		}
		$this->db->order_by('contributionName');
		return $this->db->get('contribution')->result_array();
	}
	function getEmploymentHistoryDates($rawData,$pdf){	
		$this->db->select('a.*,b.empClassName, employee.sk');
		$this->db->from('employmenthistorydate as a');
		$this->db->where('a.idEmployee',$rawData['idEmployee']);
		$this->db->join('employeeclass as b','b.idEmpClass=a.classification','left outer');
		$this->db->join('employee', 'employee.idEmployee = a.idEmployee', 'inner');
		return $this->db->get()->result_array();
	}
	function getEmployeeHistoryCR($rawData,$pdf){
		$this->db->select('a.*,b.empClassName, employee.sk');
		$this->db->from('employmenthistoryposition as a');
		$this->db->where('a.idEmployee',$rawData['idEmployee']);
		$this->db->join('employeeclass as b','b.idEmpClass=a.classification','left outer');
		$this->db->join('employee', 'employee.idEmployee = a.idEmployee', 'inner');
		return $this->db->get()->result_array();
	}
	
	function getContributionSettings($rawData){
		$this->db->select('*');
		$this->db->from('contribution');
		if( isset($rawData['query'])) $this->db->like('contributionName', $rawData['query'],'both'); 
		$this->db->order_by('contributionName','asc');
		return $this->db->get()->result_array();
	}
	function getModules($params){
		$this->db->select( '(CASE WHEN b.idAmodule IS NOT NULL THEN 1 ELSE 0 END) as chk, 
							b.idModule, b.canSave, b.canEdit, b.canDelete, b.canPrint, b.canCancel, a.idModule, a.moduleType, a.moduleName, b.idEu' );
        $this->db->join( 'amodule as b', 'b.idModule = a.idModule AND b.idEu = ' . ( int )$params['idEu'], 'left outer' );
        $this->db->where( 'a.moduleType', ( int )$params['moduleType'] );
        $this->db->order_by( 'a.moduleName', 'asc' );
        return $this->db->get( 'module as a' )->result_array();
	}
    public function getAllAffiliate( $params ){	

		if (isset( $params['idEmployee'] )){
			$this->db->select( 'a.idAffiliate,a.affiliateName,b.selected,(CASE WHEN b.selected = 1 THEN 1 ELSE 0 end) as chk, a.sk' );
			$this->db->from('affiliate as a');
			$this->db->join("( select * from employeeaffiliate where idEmployee = ". $params["idEmployee"]. " ) as b",'b.idAffiliate=a.idAffiliate','left outer');
			$this->db->where_not_in('a.archived',1);
		}else{
			$this->db->select( 'idAffiliate,affiliateName, sk' );
			if( isset( $params['query'] ) ) $this->db->like( 'affiliateName', $params['query'], 'both' );
			$this->db->order_by( 'affiliateName', 'asc' );
			$this->db->from('affiliate');
			$this->db->where_not_in('archived',1);
		}
		return $this->db->get()->result_array();
    }
    public function getClassification( $params ){
        $this->db->select( 'idEmpClass, empClassName' );
        if( isset( $params['query'] ) ) $this->db->like( 'empClassName', $params['query'], 'both' );
        $this->db->order_by( 'empClassName', 'asc' );
        return $this->db->get( 'employeeclass' )->result_array();
    }
	/* retrive main records */
	public function retrieveData($idNumber){
		$this->db->select('*');
		$this->db->where('idNumber',$idNumber);
		$this->db->from('employee');
		return $this->db->get()->result_array();
	}
	/* retrieve employment records */
	public function retrieveemployementRecord($idEmployee){
		$this->db->select('dateEmployed, dateEffective, endOfContract,classification, monthRate');
		$this->db->where('idEmployee',$idEmployee);
		$this->db->from('employment');
		return $this->db->get()->result_array();
	}
	public function euRecord($idEmployee){
		$this->db->select('idEu,username,userType');
		$this->db->where('idEmployee',$idEmployee);
		$this->db->from('eu');
		return $this->db->get()->result_array();
	}
	
    public function getEmployeeBenefits( $params ){
        $this->db->select( "idEmpBenefits, employee.idEmployee, description, amount, schedule
            , (CASE
                WHEN schedule = 1 THEN 'Daily'
                WHEN schedule = 2 THEN 'Monthly (1st Half)'
                WHEN schedule = 3 THEN 'Monthly (2nd Half)'
                WHEN schedule = 4 THEN 'Semi-Monthly'
			END) as scheduleName, employee.sk" );
		$this->db->join('employee', 'employee.idEmployee = empbenefits.idEmployee', 'INNER');
        $this->db->where( 'empbenefits.idEmployee', (int)$params['idEmployee'] );
        $this->db->order_by( 'idEmpBenefits', 'asc' );
        return $this->db->get( 'empbenefits' )->result_array();
    }	
	public function getEmployeeContributionTax($params){
		$this->db->select('a.idEmpContribution,a.idEmployee,a.idcontribution,b.contributionName, a.amount, a.amount as origAmount, a.effectivityDate, a.effectivityDate as origEffectivityDate, a.idCoa,c.aname_c30 as accountName');
		$this->db->where('a.idEmployee', (int)$params['idEmployee'] );
		$this->db->order_by('idEmpContribution','asc');
		$this->db->join('contribution as b','b.idcontribution=a.idcontribution','left outer');
		$this->db->join('coa as c','c.acod_c15=a.idCoa','left outer');
		return $this->db->get('empcontribution as a')->result_array();
	}
	
	// idcontribution
	
	function checkDupliate($idNumber){		
		$this->db->select('idNumber');
		$this->db->where('idNumber',$idNumber);
		$this->db->from('employee');
		return $this->db->count_all_results();		
	}	
	function checkExist($idNumber){		
		$this->db->select('idNumber');
		$this->db->where('idNumber',$idNumber);
		$this->db->from('employee');
		return $this->db->count_all_results();		
	}
	function checkDupliateContribution($idNumber){		
		$this->db->select('contributionName');
		$this->db->where('contributionName',$idNumber);
		$this->db->from('contribution');
		return $this->db->count_all_results();		
	}	
	function checkExistContribution($idNumber){		
		$this->db->select('idcontribution');
		$this->db->where('idcontribution',$idNumber);
		$this->db->from('contribution');
		return $this->db->count_all_results();		
	}
	function getCOADetails( $data ){
		// $this->db->select("idCoa,acod_c15,aname_c30");
		// $this->db->from("coa");
		// return $this->db->get()->result_array();

		$affiliates = json_decode( $data['affiliates'], true );

		$this->db->select("coa.idCoa,coa.acod_c15,coa.aname_c30");
		$this->db->join( 'coaaffiliate', 'coaaffiliate.idCoa = coa.idCoa', 'left' );
		$this->db->where_in( 'coaaffiliate.idAffiliate', ( count( (array)$affiliates )> 0? $affiliates : 0 ) );
		$this->db->group_by( 'coa.idCoa' );
		return $this->db->get('coa')->result_array();
	}
	/* retrive all the Affiliates that the user is associated with */
	function retrieveViewEmployeeAffiliateDetails($rawData){
		
		// print_r($rawData);
		
		// idEmployee
		// idEmployee
		
		$this->db->select('a.idEmployee,b.affiliateName, b.sk');
		
		$this->db->where('a.idEmployee', $rawData['idEmployee'] );
		
		$this->db->from('employeeaffiliate as a');
		$this->db->join("affiliate as b",'b.idAffiliate = a.idAffiliate','left outer');
		// return $this->db->get()->result_array();
		
		
		$rawData['db'] = $this->db;
		$rawData['order_by'] = 'b.affiliateName';
		return getGridList($rawData);
		
	}
	
		// $data['db'] = $this->db;
		// $data['order_by'] = 'empClassName';
		// return getGridList($data);
	
	
	function retrieveViewContributionHistory($rawData){
		$this->db->select('a.*,b.contributionName');
		$this->db->where('a.idEmployee',$rawData['idEmployee']);
		$this->db->where('a.idcontribution',$rawData['idcontribution']);
		$this->db->from('empcontributionhistory as a');
		$this->db->join('contribution as b','b.idcontribution=a.idcontribution','left outer');
		return $this->db->get()->result_array();
	}
	/* retrive all users */
	function getUsers($rawData){
		$this->db->select("a.idEu,a.idEmployee,a.username,a.userType, b.name
			,(CASE
                WHEN userType = 0 THEN 'Super Administrator'
                WHEN userType = 1 THEN 'Administrator'
                WHEN userType = 2 THEN 'Supervisor'
                else 'User'
            END) as userTypeName
		");
		$this->db->from('eu as a');
		$this->db->join('employee as b','b.idEmployee=a.idEmployee','left outer');
		return $this->db->get()->result_array();
	}
	/* check if Employee don't have logs yet */
	function checkEmployeeLogs($idEu){
		$this->db->select('idEu');
		$this->db->where('idEu',$idEu);
		$this->db->from('logs');
		return $this->db->count_all_results();	
	}
	
	function checkEmployeeInvoice($idEmployee) {
		return $this->db->select('count(*) as count')->where('invoices.idDriver', $idEmployee)->get("invoices")->row()->count;
	}

	function editContributionQuickSettingsDetails($idcontribution){
		$this->db->Select('*');
		$this->db->where('idcontribution',$idcontribution);
		return $this->db->get('contribution')->result_array();
	}
	function deleteEmployeeClassificationDetails($idcontribution){
		$this->db->delete( 'contribution', array( 'idcontribution' => $idcontribution ) );
	}
	function saveContributionQuickSettings($rawData){
		$onEdit = (int)$rawData['onEditContributionQuickSettings'];
		$id = (int)$rawData['idcontribution'];
		if( $onEdit == 0 ){
			$this->db->insert('contribution',unsetParams( $rawData, 'contribution' ));		
		}else{
			unset($rawData['idcontribution']);
			$this->db->where('idcontribution',$id);
			$this->db->update('contribution', unsetParams( $rawData, 'contribution' ));
		}
	}
	function checkHistoryChange($empID,$empClass,$empMonthRate){
		$this->db->select('classification,monthRate');
		$this->db->where('idEmployee',$empID);
		$this->db->where('classification',$empClass);
		$this->db->where('monthRate',$empMonthRate);
		$this->db->from('employment');
		return $this->db->count_all_results();	
	}
	function saveUserForm($rawData){
		$onEdit = (int)$rawData['onEdit'];
		$idEmployee = 0;
		$id = (int)$rawData['idEmployee'];
		if( $onEdit == 0 ){
			// die('new data');
			unset($rawData['idEmployee']);
			$this->db->insert('employee',unsetParams( $rawData, 'employee' ));		
			// $idEmployee = $this->db->insert_id(); //return current primary key ID for other saving purposes
			return $this->db->insert_id(); //return current primary key ID for other saving purposes
		}else{
			// die('edited ni, mao ni id: '.$id);
			// $idEmployee = $rawData['idEmployee'];
			unset($rawData['idNumber']);
			unset($rawData['idEmployee']);
			$this->db->where('idEmployee',$id);
			$this->db->update('employee', unsetParams( $rawData, 'employee' ));
		}
		// return $idEmployee;
	}
	function saveAffiliateList($affiliateList,$idEmployee){
		$this->db->delete('employeeaffiliate', array('idEmployee' => $idEmployee));
		$this->db->insert_batch('employeeaffiliate', $affiliateList);		
	}
	function saveEmploymentDetails($rawData,$idEmployee){
		$onEdit = (int)$rawData['onEdit'];
		$id = (int)$rawData['idEmployee'];
		if( $onEdit == 0 ){
			$this->db->insert('employment',unsetParams( $rawData, 'employment' ));
		}else{
			unset($rawData['idEmployee']);
			$this->db->where('idEmployee',$id);
			$this->db->update('employment', unsetParams( $rawData, 'employment' ));
		}
	}
	function saveUserCredentialDetails($rawData,$idEmployee){
		$onEdit = (int)$rawData['onEdit'];
		$id = (int)$rawData['idEmployee'];
		/* 
			if $onEdit == 0 and $rawData['user'] == 1; Its new Saving of Employee
			if $onEdit != 0 and $rawData['user'] == 1 and $rawData['idEu'] == 0; Edit employee with new user details
		*/

		if( ($onEdit == 0 && $rawData['user'] == 1 ) || ( $onEdit != 0 && $rawData['user'] == 1 && $rawData['idEu'] == 0  ) ){
			$rawData['password'] = md5($rawData['password']);
			$this->db->insert('eu',unsetParams( $rawData, 'eu' ));
			return $this->db->insert_id();
		}
		else{
			/* else for Edit employee, password is unset */
			/* else for $onEdit != 0 && $rawData['user'] == 1 && $rawData['idEu'] != 0; edited employee with updated user credentials*/
			$this->db->select( 'idEu' , $id );
			$this->db->where( 'idEmployee' , $id );
			$idEu = $this->db->get( 'eu' )->row()->idEu;
			$this->db->reset_query();
			 
			unset($rawData['idEmployee']);
			unset( $rawData['password'] ); 
			$this->db->where('idEmployee',$id);
			$this->db->update('eu', unsetParams( $rawData, 'eu' ));

			return $idEu;
		}
	}
	function savebenefitsList($benefitsList,$idEmployee){
		$this->db->delete('empbenefits', array('idEmployee' => $idEmployee));
		$this->db->insert_batch('empbenefits', $benefitsList );		
	}	
	function savecontributionsAndTaxContainerList($contributionsAndTaxContainerList,$idEmployee){
		$this->db->delete('empcontribution', array('idEmployee' => $idEmployee));
		$this->db->insert_batch('empcontribution', $contributionsAndTaxContainerList );		
	}
	function saveRawContributionsAndTaxListHistory($rawContributionsAndTaxListHistory){
		$this->db->insert_batch('empcontributionhistory', $rawContributionsAndTaxListHistory );		
	}
	function saveClassificationAndMonthRateChange($rawData){
		$this->db->insert('employmenthistoryposition',unsetParams( $rawData, 'employmenthistoryposition' ));
	}
	function saveEmploymentDataChange($rawData){
		$this->db->insert('employmenthistorydate',unsetParams( $rawData, 'employmenthistorydate' ));
	}	
	function getAmodules( $userType ) {
		if ( $userType == 'User' ) $this->db->WHERE_NOT_IN( 'moduleType', [ 0, 5 ] );
		return $this->db->GET( 'module' )->result_array();
	}	
	function saveAmodules( $row, $idEu, $userType ) 
	{
		if ( $userType == 'User' ) {
			$canSave = 1; $canEdit = 0; $canDelete = 0; $canPrint = 1;
		} else {
			$canSave = 1; $canEdit = 1; $canDelete = 1; $canPrint = 1;
		}
		$data = array(
			'idModule'		=> $row['idModule'], 
			'idEu'			=> $idEu, 
			'moduleType'	=> $row['moduleType'], 
			'canSave' 		=> $canSave, 
			'canEdit' 		=> $canEdit, 
			'canDelete' 	=> $canDelete, 
			'canPrint' 		=> $canPrint, 
		);

		return $this->db->insert( 'amodule', $data );
	}
	function deleteAmodules( $idEu )  {
		$this->db->where('idEu', $idEu);
		$this->db->delete('amodule');
	}
	function saveModules($moduleList){
		$this->db->insert_batch('amodule',$moduleList);
	}
	function changPassword($rawData) {
		$rawData['password'] = md5($rawData['password']);
		$id = (int)$rawData['idEmployee'];
		unset($rawData['idEmployee']);
		$this->db->where('idEmployee',$id);
		$this->db->update('eu',unsetParams($rawData,'eu'));
	}
	function deleteAmodulesRecord($rawData){
		$this->db->where('moduleType',$rawData['moduleType']);
		$this->db->where('idEu',$rawData['idEu']);
		$this->db->delete('amodule');
	}
	// function deleteEmployeeRecord($idEmployee){
	function deleteEmployeeRecord($rawData){
		$setUpdateArray = array('archived'=>1);
		$match = 0;
		$this->db->select('archived');
        $this->db->where( 'idEmployee', $rawData['idEmployee'] );
        $archived = $this->db->get('employee')->result_array()[0]['archived'];
		
		if( (int)$archived == 0 ) {
			/* SOFT DELETE ONLY */
			// $this->db->set($setUpdateArray);
			// $this->db->update
			$this->db->update('employee', $setUpdateArray, array('idEmployee' => $rawData['idEmployee'] ));
			$this->db->update('employeeaffiliate', $setUpdateArray, array('idEmployee' => $rawData['idEmployee'] ));
			$this->db->update('employment', $setUpdateArray, array('idEmployee' => $rawData['idEmployee'] ));
			$this->db->update('empbenefits', $setUpdateArray, array('idEmployee' => $rawData['idEmployee'] ));
			$this->db->update('empcontribution', $setUpdateArray, array('idEmployee' => $rawData['idEmployee'] ));
			$this->db->update('eu', $setUpdateArray, array('idEmployee' => $rawData['idEmployee'] ));
		}else { $match = 2; }
        return $match;
		
		/* 
		$this->db->update('mytable', $data, array('id' => $id));
		
		$this->db->delete( 'employee', array( 'idEmployee' => $idEmployee ) ); 
		$this->db->delete( 'employeeaffiliate', array( 'idEmployee' => $idEmployee ) ); 
		$this->db->delete( 'employment', array( 'idEmployee' => $idEmployee ) ); 
		$this->db->delete( 'empBenefits', array( 'idEmployee' => $idEmployee ) ); 
		$this->db->delete( 'empcontribution', array( 'idEmployee' => $idEmployee ) ); 
		
		$this->db->delete( 'eu', array( 'idEmployee' => $idEmployee ) ); 
		*/
	}
	
	
	
	// function deleteEmployeeClassificationDetails( $idEmpClass ){
        // $match = 0;
		// $this->db->select('archived');
        // $this->db->where( 'idEmpClass', $idEmpClass );
        // $archived = $this->db->get('employeeclass')->result_array()[0]['archived'];

        // if( (int)$archived == 0 ) {
            // /* SOFT DELETE ONLY */
            // $this->db->set('archived', 1, false );
            // $this->db->where('idEmpClass', $idEmpClass );
            // $this->db->update('employeeclass');
        // } else { $match = 2; }
        // return $match;
    // }
	
	
	function deleteOldUserData($idEmployee){
		$this->db->delete( 'eu', array( 'idEmployee' => $idEmployee ) ); 
	}
	
}