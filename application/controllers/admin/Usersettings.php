<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Mark Reynor D. Magriña
 * Module: User Settings
 * Date: Oct 24, 2019
 * Finished: 
 * Description: The User Setting module allows authorized users to add, edit or delete users in the system.
 * DB Tables: eu, amodules
 * For Password Hashing, please use password_hash( https://www.php.net/manual/en/function.password-hash.php )
 * */
class Usersettings extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->library('encryption');
        setHeader( 'admin/Usersettings_model' );
    }

    public function getEmployeeUserList(){
        $rawData = getData();
		$record   = $this->model->getEmployeeUserList( $rawData,false );
		$record['view'] = decryptUserData( $record['view'] );
		
        // print json_encode( array( 'success' => true, 'view' =>  $record['view'], 'total' => $record['count'] ) ) ;
        die( json_encode( array( 'success' => true, 'total' => $record['count'], 'view' =>  $record['view'] ) ) );
	}
	
	public function retrieveContributionQuickSettingDetails(){
		$rawData = getData();
        $record   = $this->model->retrieveContributionQuickSettingDetails( $rawData,false );
		
        die( json_encode( array( 'success' => true, 'view' =>  $record, 'total' => count($record) ) ) );
	}

	public function editContributionQuickSettingsDetails(){
		$data = getData();
		$idcontribution = $this->input->post('idcontribution');
		$record = $this->model->editContributionQuickSettingsDetails($idcontribution);
		echo json_encode( array( 'success' => true, 'match' =>1, 'view' => $record ));
	}

	public function deleteEmployeeClassificationDetails(){
		$data = getData();
		$this->model->deleteEmployeeClassificationDetails($data['idcontribution']);
		setLogs( array(
			'actionLogDescription' => 'Deleted the contribution, '.$data['contributionName']
			,'moduleID' => 3
			,'idAffiliate' => $this->session->userdata('AFFILIATEID')
			,'time' => date("H:i:s A")
		));
	}

	public function saveContributionQuickSettings(){
		$actioLog = '';
		$rawData = getData();		
		if( $rawData['onEditContributionQuickSettings'] == 0 ) {
			$checkDuplicate = $this->model->checkDupliateContribution($rawData['contributionName']);		
			if( $checkDuplicate > 0 ) die( json_encode( array('success'=> true, 'match'=>1)));
		}else{
			$checkExist = $this->model->checkExistContribution($rawData['idcontribution']);		
			if( $checkExist == 0 ) die( json_encode( array('success'=> true, 'match'=>2)));
		}	
		
		$this->db->trans_begin();// firstline
			$this->model->saveContributionQuickSettings($rawData);		
		if( $this->db->trans_status() === FALSE ){
			$this->db->trans_rollback(); // rollback changes
			//$success = false;
		}
		else{
			$this->db->trans_commit(); // submit changes
			//$success = true;
		}
		/* Set Logs */
		if($rawData['onEditContributionQuickSettings'] == 0){ $actioLog = 'Added new contribution, '.$rawData['contributionName']; }
		else{ $actioLog = 'Modified the contribution, '.$rawData['contributionName']; }		
		setLogs( array(
			'actionLogDescription' => $actioLog
			,'moduleID' => 3
			,'time' => date("H:i:s A")
		));
		die( json_encode( array('success'=> $this->db->trans_status(), 'match'=>0 )));		
		
	}

    public function getAffiliate(){
        $params = getData();
		$view   = $this->model->getAllAffiliate($params);
		$view 	= decryptAffiliate( $view );

        die( json_encode( array( 'success' => true ,'view' => $view ) ) );
    }

    public function getClassification(){
        $params = getData();
        $view   = $this->model->getClassification( $params );
        die( json_encode( array( 'success'   => true ,'view'     => $view ) ) );
    }

    public function getEmployeeBenefits(){
        $params = getData();
		$view   = $this->model->getEmployeeBenefits( $params );
		$view	= decryptUserData( $view );
        die( json_encode( array( 'success' => true ,'view' => $view ) ) );
    }
	
	public function getEmployeeContributionTax(){
		$params = getData();
		$view = $this->model->getEmployeeContributionTax($params);
		$view = decryptUserData( $view );
		
		// lq();
		
		die( json_encode ( array( 'success'=>true, 'view'=>$view ) ) );
	}
	
	public function getEmploymentHistoryDates(){
        $rawData = getData();
		$record   = $this->model->getEmploymentHistoryDates( $rawData,false );
		$record = decryptUserData( $record );
		
        die( json_encode( array( 'success' => true, 'view' =>  $record, 'total' => count($record) ) ) );
    }
	
	public function getEmployeeHistoryCR(){
        $rawData = getData();
		$record   = $this->model->getEmployeeHistoryCR( $rawData,false );
		$record	= decryptUserData( $record );
		
        die( json_encode( array( 'success' => true, 'view' =>  $record, 'total' => count($record) ) ) );
    }
	
	public function getContributionSettings(){
		$rawData = getData();
        $record   = $this->model->getContributionSettings( $rawData );
		array_unshift($record, array( 'idcontribution' => 0, 'contributionName' => "<span style='color:#c3b3b3;'>+Add Contribution</span>") );
        die( json_encode( array( 'success' => true, 'view' =>  $record, 'total' => count($record) ) ) );
	}
	
	function getCOADetails(){
		$data = getData();
		$record = $this->model->getCOADetails( $data );
		die( json_encode( array( 'success' => true ,'view' => $record )));
	}
	
	public function getModules(){
		$rawData = getData();
		$record = $this->model->getModules($rawData);

		die( json_encode( array( 'success'=> true , 'view'=> $record ) ) );
	}
	
	function retrieveViewEmployeeAffiliateDetails(){
		$rawData = getData();
		$record = $this->model->retrieveViewEmployeeAffiliateDetails($rawData);
		$record['view'] = decryptAffiliate( $record['view'] );
		
		print  json_encode( array( 'success' => true, 'total'=>$record['count'], 'view' => $record['view'] ) ) ;
	}

		// print json_encode( array( 'success' => true, 'total' => $result['count'], 'view'=>$result['view'] ));
		// die( json_encode ( array( 'success'=> true, 'view'=> $record ) ) );
	
	function retrieveViewContributionHistory(){
		$rawData = getData();
		$record = $this->model->retrieveViewContributionHistory($rawData);
		die( json_encode( array( 'success'=>true, 'view'=>$record ) ) );
	}
	
	function getUsers(){
		$rawData = getData();
		$record = [];
		$record = $this->model->getUsers($rawData);
		die( json_encode ( array( 'success'=> true, 'view'=> $record ) ) );
	}
	
	public function saveUserForm(){
		$rawData = getData();
		
		/**Encryption of fields**/
		if( array_key_exists( 'name', $rawData ) && !empty( $rawData['name'] ) ){
			$rawData['sk'] = initializeSalt( $rawData['name'] );
			$this->encryption->initialize( array( 'key' => generateSKED( $rawData['sk'] ) ) );

			/**Employee**/
			$rawData['name'] = $this->encryption->encrypt( $rawData['name'] );
			if( array_key_exists( 'address', $rawData ) && !empty( $rawData['address'] ) ) $rawData['address'] = $this->encryption->encrypt( $rawData['address'] );
			if( array_key_exists( 'contactNumber', $rawData ) && !empty( $rawData['contactNumber'] ) ) $rawData['contactNumber'] = $this->encryption->encrypt( $rawData['contactNumber'] );
			if( array_key_exists( 'email', $rawData ) && !empty( $rawData['email'] ) ) $rawData['email'] = $this->encryption->encrypt( $rawData['email'] );
			if( array_key_exists( 'birthdate', $rawData ) && !empty( $rawData['birthdate'] ) ) $rawData['birthdate'] = $this->encryption->encrypt( $rawData['birthdate'] );

			if( array_key_exists( 'age', $rawData ) && !empty( $rawData['age'] ) ) $rawData['age'] = $this->encryption->encrypt( $rawData['age'] );

			/**Employment**/
			if( array_key_exists( 'dateEmployed', $rawData ) && !empty( $rawData['dateEmployed'] ) ) $rawData['dateEmployed'] = $this->encryption->encrypt( $rawData['dateEmployed'] );
			if( array_key_exists( 'dateEffective', $rawData ) && !empty( $rawData['dateEffective'] ) ) $rawData['dateEffective'] = $this->encryption->encrypt( $rawData['dateEffective'] );
			if( array_key_exists( 'endOfContract', $rawData ) && !empty( $rawData['endOfContract'] ) ) $rawData['endOfContract'] = $this->encryption->encrypt( $rawData['endOfContract'] );
			if( array_key_exists( 'monthRate', $rawData ) && !empty( $rawData['monthRate'] ) ) $rawData['monthRate'] = $this->encryption->encrypt( $rawData['monthRate'] );
			
			/**Benefits**/
			$_benefits = json_decode( $rawData['benefitsList'], true );
			if( !empty( $_benefits ) ){

				foreach( $_benefits as $index => $benefit ){
					if( array_key_exists( 'description', $benefit ) && !empty( $benefit['description'] ) ) $_benefits[$index]['description'] = $this->encryption->encrypt( $benefit['description'] );
					if( array_key_exists( 'amount', $benefit ) && !empty( $benefit['amount'] ) ) $_benefits[$index]['amount'] = $this->encryption->encrypt( $benefit['amount'] );	
				}

				$rawData['benefitsList'] = json_encode( $_benefits );
			}
			
			/**Contribution**/
			$_contributions = json_decode( $rawData['contributionsAndTaxContainerList'], true );
			if( !empty( $_contributions ) ){
				foreach( $_contributions as $index => $contribution ){
					if( array_key_exists( 'amount', $contribution ) && !empty( $contribution['amount'] ) ) $_contributions[$index]['amount'] = $this->encryption->encrypt( $contribution['amount'] );
				}

				$rawData['contributionsAndTaxContainerList'] = json_encode(  $_contributions );
			}
		} else {
			die('Encryption: EMPLOYEE NAME IS REQUIRED.');
		}

		// die( json_encode( $rawData ) );
		/**End of Encryption**/

		$rawContributionsAndTaxListHistory = [];
		
		$idEu = 0;
		$idEmployee = 0;
		$employeeIDHolder = [];
		/* Validation */
		if( $rawData['onEdit'] == 0 ) {
			$checkDuplicate = $this->model->checkDupliate($rawData['idNumber']);		
			if( $checkDuplicate > 0 ) die( json_encode( array('success'=> true, 'match'=>1)));
		}else{
			$checkExist = $this->model->checkExist($rawData['idNumber']);		
			if( $checkExist == 0 ) die( json_encode( array('success'=> true, 'match'=>2)));
		}		
	
		$this->db->trans_begin();// firstline					
			$idEmployee=$this->model->saveUserForm($rawData);			
			if( $rawData['onEdit'] == 0 ) { $idEmployee = $idEmployee;}
			else{ $idEmployee = (int)$rawData['idEmployee']; }
			$employeeIDArray = array( 'idEmployee'=> $idEmployee ); // Array to add idEmployee field and value			
			
			/* Prepare affiliate list for batch saving */
			$affiliateList= json_decode($rawData['affiliateList'], true);
			$employeeToAffiliate = array( 'idEmployee'=> $idEmployee );
			for ( $i=0; $i < count($affiliateList); $i++ ) {
				unset($affiliateList[$i]['affiliateName']);
				unset($affiliateList[$i]['chk']);
				$affiliateList[$i] +=  $employeeIDArray;
			}
			$this->model->saveAffiliateList($affiliateList,$idEmployee);	
			// echo(', agi ko save affiliate');
			
			/* Assign employee primary ID to variable */
			$rawData['idEmployee']=$idEmployee; 
			
			/* save employment details */
			$this->model->saveEmploymentDetails($rawData,$idEmployee);	
			// echo(', agi ko save Employment');
			
			/* Save to database employmentHistoryDate for classificaiton and month rate changes */
			if( isset($rawData['classificationAndMonthRateState']) || $rawData['onEdit'] == 0 ){
				$rawData['idEmployee'] = $idEmployee;
				$this->model->saveClassificationAndMonthRateChange($rawData);
				// echo(', agi ko save history for Classificiton and rate');
			}
			/* Save to database employmentHistoryPosition for employment date changes */
			if( isset($rawData['employmentDataHolderChangeState']) || $rawData['onEdit'] == 0 ){
				$rawData['idEmployee'] = $idEmployee;
				$this->model->saveEmploymentDataChange($rawData);
				// echo(', agi ko save history for employment date changes');
			}
			
			// if( isset($rawData['username']) || $rawData['username'] === null ){
			if( isset($rawData['username']) ){
				$idEu = $this->model->saveUserCredentialDetails($rawData,$idEmployee);	
				// echo(', agi ko save User Credential');
			}
			
			/* delete user at eu table if the user is set to be employee only during edit */			
			// if( $onEditHolder == 1 && $isUserHolder == 0 ){
			if( intval($rawData['onEdit']) == 1 && intval($rawData['user']) == 0 ){
				// echo(', agi ko delete User Credential');
				$userDeleteResult = $this->model->checkEmployeeLogs($idEmployee);
				if( intval($userDeleteResult) <= 0 ){
					$this->model->deleteOldUserData($idEmployee);
				}
				else{
					die( json_encode( array ( 'success'=>true, 'match'=>3 ) )  );
				} 
			}
			
			/* Prepare benefits list for batch saving */
			$benefitsList= json_decode($rawData['benefitsList'], true);
			// $employeeToBenefits = array( 'idEmployee'=> $idEmployee );
			if( !empty($benefitsList) ){
				for ( $i=0; $i < count($benefitsList); $i++ ) {
					unset($benefitsList[$i]['idEmpBenefits']); 
					unset($benefitsList[$i]['scheduleName']);
					unset($benefitsList[$i]['selected']);
					// $benefitsList[$i] +=  $employeeToBenefits;
					$benefitsList[$i] += $employeeIDArray;
				}			
				$this->model->savebenefitsList($benefitsList,$idEmployee);
			}
			// echo(', agi ko save User Save benefits');
			
			/* Prepare contributions and taxes for batch saving */
			$contributionsAndTaxContainerList= json_decode($rawData['contributionsAndTaxContainerList'], true);
			if(!empty($contributionsAndTaxContainerList)){
				for ( $i=0; $i < count($contributionsAndTaxContainerList); $i++ ) {
					unset($contributionsAndTaxContainerList[$i]['contributionName']);
					unset($contributionsAndTaxContainerList[$i]['accountName']);
					unset($contributionsAndTaxContainerList[$i]['selected']);
					$contributionsAndTaxContainerList[$i] +=  $employeeIDArray;
					
					if($contributionsAndTaxContainerList[$i]['amount'] != $contributionsAndTaxContainerList[$i]['origAmount'] || $contributionsAndTaxContainerList[$i]['effectivityDate'] != $contributionsAndTaxContainerList[$i]['origEffectivityDate'] || $rawData['onEdit'] == 0 ){
						// $rawContributionsAndTaxListHistory[$i]['idEmployee'] = $idEmployee;
						$rawContributionsAndTaxListHistory[$i] = $employeeIDArray;
						$rawContributionsAndTaxListHistory[$i]['idcontribution'] = $contributionsAndTaxContainerList[$i]['idcontribution'];
						$rawContributionsAndTaxListHistory[$i]['amount'] = $contributionsAndTaxContainerList[$i]['amount'];
						$rawContributionsAndTaxListHistory[$i]['effectivityDate'] = $contributionsAndTaxContainerList[$i]['effectivityDate'];
					}
					unset($contributionsAndTaxContainerList[$i]['origAmount']);
					unset($contributionsAndTaxContainerList[$i]['origEffectivityDate']);
				}			
				$this->model->savecontributionsAndTaxContainerList($contributionsAndTaxContainerList,$idEmployee);
				if( !empty($rawContributionsAndTaxListHistory) ) $this->model->saveRawContributionsAndTaxListHistory($rawContributionsAndTaxListHistory);
			}
			
			/**Decryption of data for logs**/
			$this->encryption->initialize( array('key' => generateSKED( $rawData['sk'] ) ) );
			$rawData['name'] = $this->encryption->decrypt( $rawData['name'] );
			
			if( isset($rawData['userType']) )
			{
				// $userTypeHolder = ( $rawData['userType'] == 0 ) ? 'Super Admin' : ( ($rawData['userType'] == 1) ? 'Administrator' : ($rawData['userType'] == 2) ? 'Supervisor' : 'User');
				switch( $rawData['userType'] ){
					case 0:
						$userTypeHolder = 'Super Admin';
						break;
					case 1:
						$userTypeHolder = 'Administrator';
						break;
					case 2:
						$userTypeHolder = 'Supervisor';
						break;
					case 3:
						$userTypeHolder = 'User';
						break;
				}

				$userModules	= $this->model->getAmodules( $userTypeHolder );

				$this->model->deleteAmodules( $idEu );
				
				foreach ( $userModules as $row ) {
					$this->model->saveAmodules( $row, $idEu, $userTypeHolder );
				}
			}
			if( $rawData['onEdit'] == 0 ){
				if(strval($rawData['username']) == ''){
					$actioLog = 'Added new employee, '.$rawData['name'].'.';
				}else{
					$actioLog = 'Added new employee, '.$rawData['username'].', for '.$rawData['name'].' with usertype '.$userTypeHolder;
				}
			}
			else{
				if( !isset($rawData['username']) ){
					$actioLog = 'Modified the employee, '.$rawData['name'].'.';
				}else{
					$actioLog = 'Modified the employee, '.$rawData['username'].', for '.$rawData['name'].' with usertype '.$userTypeHolder;
				}
			}
			setLogs( array(
				'actionLogDescription' 		=> $actioLog
				,'idEmployee' 					=> $this->EMPLOYEEID
				,'moduleID' 				=> 3
				,'idAffiliate'				=> $this->session->userdata('AFFILIATEID')
				,'time' 					=> date("H:i:s A")
			));
			
		if( $this->db->trans_status() === FALSE ){
			$this->db->trans_rollback(); // rollback changes
		}
		else{
			$this->db->trans_commit(); // submit changes
		}
		die( json_encode( array('success'=> $this->db->trans_status(), 'match'=>0 )));		
	}

	function saveModules(){
		$rawData = getData();
			
		$this->db->trans_begin();// firstline
			$moduleList = json_decode($rawData['moduleList'], true);
			$countedArray = array_filter($moduleList);
			if ( count ($countedArray) != 0 ){
				for($i=0; $i < count($moduleList); $i++ ){
					unset($moduleList[$i]['chk']);
					unset($moduleList[$i]['moduleName']);
					unset($moduleList[$i]['selected']);
					if($moduleList[$i]['moduleType'] == 0){ 
						$moduleList[$i]['moduleType'] = $rawData['moduleType'];	
					}
					$moduleList[$i]['idEu'] = $rawData['idEu'];
				}
				$this->model->deleteAmodulesRecord($rawData);
				$this->model->saveModules($moduleList);	
			}else{
				$this->model->deleteAmodulesRecord($rawData);
			}
		if( $this->db->trans_status() === FALSE ){
			$this->db->trans_rollback(); // rollback changes
		}
		else{
			$this->db->trans_commit(); // submit changes
		}
		$actioLog = 'Modified the module access for the user account, '.$rawData['userName'].' of '.$rawData['fullName'].', with usertype '.$rawData['userTypeName'].'.';
		setLogs( array(
			'actionLogDescription' => $actioLog
			
			,'moduleID' => 3
			,'idAffiliate' => $this->session->userdata('AFFILIATEID')
			,'time' => date("H:i:s A")
		));
		die( json_encode( array( 'success'=> true, 'match'=>0 ) ) );
	}
	
	function retrieveData(){
		$rawData = getData();	
		$record = $this->model->retrieveData($rawData['idNumber']);
		
		/* append manually the Employment Record to Main array $record */
		$employementRecord = $this->model->retrieveemployementRecord($rawData['idEmployee']);
		for ( $i=0; $i < count($employementRecord); $i++ ) {
			$record[0] +=$employementRecord[$i];
		}
		
		/* append manually the User details to Main array $record */
		$euRecord = $this->model->euRecord($rawData['idEmployee']);
		for ( $i=0; $i < count($euRecord); $i++ ) {
			$record[0] +=$euRecord[$i];
		}
		$match =55;

		$record = decryptUserData( $record );
		$record[0]['age'] = (new DateTime($record[0]['birthdate']))->diff(new DateTime(date('Y-m-d')))->y;

		die( json_encode( array( 'success' => true ,'match' => $match,'view' => $record )));
	}

	function computeAge()
	{
		$rawData = getData();	
		die ( json_encode ( (new DateTime($rawData['birthdate']))->diff(new DateTime(date('Y-m-d')))->y ) );
	}

	function getMinDate()
	{
		$year = date('Y') - 10;
		$data['birthdate'] = date( "m/d/$year" );
		$data['age'] = (new DateTime(date("$year-m-d")))->diff(new DateTime(date('Y-m-d')))->y;
		die ( json_encode ( $data ) );
	}
	
	function changPassword(){
		$rawData = getData();
		$this->model->changPassword($rawData);
	}
	
	function deleteEmployeeRecord(){
		$rawData = getData();
		$checkExistingTransaction = $this->model->checkEmployeeLogs($rawData['idEu']);
		$checkExistingTransaction += $this->model->checkEmployeeInvoice($rawData['idEmployee']);

		if( $checkExistingTransaction > 0 ) { die( json_encode ( array( 'success'=>true, 'match'=> 1 ) ) ); }
		else{
			$match = $this->model->deleteEmployeeRecord($rawData);
		} 
		if(strval($rawData['employeeUserName']) == ''){
			$messageLog = 'Deleted the user account of, '.$rawData['employeeName'].'.';
		}else{
			$messageLog = 'Deleted the user account of, '.$rawData['employeeName'].' with username: '.$rawData['employeeUserName'].'.';
		}
		setLogs( array(
			'actionLogDescription' => $messageLog
			
			,'moduleID' => 3
			,'idAffiliate' => $this->session->userdata('AFFILIATEID')
			,'time' => date("H:i:s A")
		));
		die( json_encode ( array( 'success'=>true, 'match'=>$match ) ) );
	}
}