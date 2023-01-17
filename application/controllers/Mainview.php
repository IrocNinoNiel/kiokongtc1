<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mainview extends CI_Controller { 
	public function __construct(){
		parent::__construct();
		setHeader( 'Mainview_model' );
	}
	
	function index(){
		if( $this->session->userdata( 'logged_in' ) === TRUE ){
			$this->load->view('main_view');
		}
		else{         
			print "<script type=\"text/javascript\">";
			print "window.location.href = '".site_url()."'";
			print "</script>";
		}				
	}
	
	public function initializeAndLoadModules(){
		
		
		// die('main view nako');
		
		$modules = $this->model->loadModules();
		$standardFiles = array(
			array(  'moduleLink' => 'standards/Constants.js' )
			,array( 'moduleLink' => 'standards/Standards.js' )
			,array( 'moduleLink' => 'standards/Standards2.js' )
			,array( 'moduleLink' => 'standards/Overrides.js' )
		);
		
		/** this is for changing the affiliate **/
		// $newAffID = $this->session->userdata('AFFILIATEID_NEW');
		// if($newAffID){
		// 	$newAffiliate = $this->standards->getAffiliateDetails(array(
		// 		'affiliateID' => $newAffID
		// 	));
		// 	$this->session->set_userdata('AFFILIATEID', $newAffiliate->affiliateID);
		// 	$this->session->set_userdata('AFFILIATENAME', $newAffiliate->affiliateName);
		// 	$this->session->set_userdata('AFFILIATETAGLINE', $newAffiliate->tagLine);
		// 	$this->session->set_userdata('AFFILIATEREFTAG', $newAffiliate->reftag);
		// 	$this->session->set_userdata('COMPANYNAME' ,$newAffiliate->affiliateName);
		// 	$this->session->set_userdata('AFFILIATEDATESTART' ,$newAffiliate->datestart);
		// 	$this->session->set_userdata('AFFILIATEACCSCHED' ,$newAffiliate->accountingsched);
		// 	$this->session->set_userdata('AFFILIATEMONTH' ,$newAffiliate->month);
		// 	$this->session->set_userdata('COMPLOGO' , (is_url_exist($this->session->userdata('LOGOPATH').$newAffiliate->affLogo) ? $newAffiliate->affLogo : DEFAULT_EMPTY_IMG));
		// }
		
		/** loop through all session variables and set as array **/
		foreach($this->session->all_userdata() as $key => $data){
			$initVar[$key] = $data;
		}
		$initVar['SYSTIME'] = date('F j, Y h:i A');
		$initVar['DEFAULT_EMPTY_IMG'] = DEFAULT_EMPTY_IMG;
		$initVar['BASEURL'] = base_url();
		$initVar['STANDARD_ROUTE'] = $initVar['BASEURL'] . 'standards/Standards/';
		$initVar['STANDARD_ROUTE2'] = $initVar['BASEURL'] . 'standards/Standards2/';
		// $initVar['hasMainAffiliate'] = $this->standards->hasMainAffiliate();
		// $initVar['version'] = $this->standards->getLastestSystemDetailsRecord()->versionName;
		
		if(isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'],'Google App Engine') !== false){
			$initVar['imageBin'] = 'data:image/png;base64,' . base64_encode( file_get_contents( 'gs://zealep1/images/logo/' . $initVar['COMPLOGO'] ) );
			$initVar['DEFAULT_IMAGE_BIN'] = 'data:image/png;base64,' . base64_encode( file_get_contents( 'gs://zealep1/images/logo/' . DEFAULT_EMPTY_IMG ) );
			$initVar['isGae'] = 1;
		}
		else{
			$initVar['imageBin'] = '';
			$initVar['DEFAULT_IMAGE_BIN'] = '';
			$initVar['isGae'] = 0;
		}
		
		$headers    = getallheaders();
		$initHeader = $this->session->userdata( 'initHeader' );
		
		if( $initHeader ){
			$initVar['initHeader'] = $initHeader;
		}
		else{
			$this->session->set_userdata( array( 'initHeader'=>$headers['initHeader'] ) );
		}
		
		$hasReceivable = $this->model->checkHasReceivableSchedule();
		$hasPayable = $this->model->checkHasPayableSchedule();

		// print_r( $hasReceivable );
		
		for( $x = 0; $x < count( $modules ); $x++ ){
			if( (int)$modules[$x]['idModule'] == 1 ){
				$modules[$x]['hasReceivable'] = ( !$hasReceivable ) ? 0 : 1;
				$modules[$x]['hasPayable'] = ( !$hasPayable ) ? 0 : 1;
			}
		}
		
		die( json_encode( array( 'success' => true ,'modules' => $modules, 'initVar' => $initVar, 'standardFiles' => $standardFiles ) ) );
	}
	
	public function getTransDetails(){
		$data = getData();
		$inv = $this->model->getTransDetails( $data );
		die(
			json_encode(
				array(
					'data' => $inv
				)
			)
		);
	}
	
	// function checkIfPChange(){
		// $iduser = $this->session->userdata('IDUSER');
		// $passchng = $this->session->userdata('PASSCHANGEDATE');
		// $match = 0; $logouttime = 0;
		// $checking = $this->Dashboard_model->checkIfPChange($iduser,$passchng);
		// $getLogouttime = $this->Dashboard_model->getLogoutTime();
		// if(isset($getLogouttime->logouttime)) $logouttime = $getLogouttime->logouttime;
		// if(isset($checking->iduser)){
			// if($checking->passChangeDate != $passchng){
				// if(isset($checking->verCode)) $match = 1;
				// else $match = 2;
			// }
		// }
		// die(json_encode(array('success'=>true,'match'=>$match,'logouttime'=>$logouttime)));
	// }

}
