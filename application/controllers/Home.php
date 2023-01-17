<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Home Module
  * [Developer]
  * Developer: Jayson Dagulo
  * Date Start: 10-11-2019
  * Date Ended: 
  
  * [Database]
  * [Description]
  * 
 * [Modification]
 **/

class Home extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library('encryption');
		setHeader('Home_model');
	}
	
	public function index(){
		header( "cache-Control: no-store, no-cache, must-revalidate" );
		header( "cache-Control: post-check=0, pre-check=0", FALSE );
		
		header( "Pragma: no-cache" );
		header( "Expires: Sat, 26 Jul 1997 05:00:00 GMT" );		
		
		$this->checklog();
		$data = $this->model->getCompany();
		$affiliateData = $this->model->getAllAffiliate();
		
		$this->load->view( 'login_view', array( 'comp' => $data, 'affiliate' => $affiliateData ) );
	}

	public function verifyUser(){
		$params = getData();
		$view = $this->model->verifyUser( $params );

		die(
			json_encode(
				array(
					'success'	=> true
					,'view'		=> $view
				)
			)
		);
	}

	public function loginUser( ){
		$data      = getData( FALSE );
		$logoPath	= site_url().LOGO_PATH;
		$loginUser = $this->model->loginUser( $data );
		$loginUser = (object) decryptUserData( array( 0 => (array)$loginUser ) )[0];
		$loginUser = (object) decryptAffiliate( array( 0 => (array)$loginUser ) )[0];
		$loginUser->companyLogo = ( !empty( $loginUser->companyLogo ) && is_url_exist( $logoPath . $loginUser->companyLogo ) ) ? $loginUser->companyLogo : DEFAULT_EMPTY_IMG;

		if( empty($loginUser) ){
			die( json_encode( array( 'trigger'=> 1, 'success' => true ) )  );
		} else if( $loginUser->module_count == 0 ){
			die( json_encode( array( 'trigger' => 2 ,'success' => true ) ) );
		} else if( $loginUser->affiliate_count == 0 ){
			die( json_encode( array( 'trigger'=> 3, 'success'=> true ) ) );
		} else if( $loginUser->employeeStatus == 1 ){
			die( json_encode( array( 'trigger'=> 4, 'success'=> true ) ) );
		} else{
			if( isset( $_SERVER['SERVER_SOFTWARE'] ) && strpos( $_SERVER['SERVER_SOFTWARE'], 'Google App Engine' ) !== FALSE ){
				$printPath  = 'gs://zealep1/';
				$logoPath 	= "gs://zealep1/images/logo/";
				$server 	= 1;
			} else {
				$printPath  = site_url();
				$logoPath	= site_url().LOGO_PATH;
				$server 	= 0;
			}

			$this->session->set_userdata(
				array(
					'logged_in' 			=> TRUE
					,'INITHEADER' 			=> FALSE
					,'USERID' 				=> $loginUser->userID
					,'EMPLOYEEID' 			=> $loginUser->empID
					// ,'USERID' 			=> 1
					// ,'USERID' 			=> 59
					,'USERFULLNAME' 		=> $loginUser->fullName
					// ,'USERFULLNAME' 		=> 'User Full Name'
					,'USERNAME' 			=> $loginUser->userName
					// ,'USERNAME' 			=> 'Username'
					,'USERTYPEID' 			=> $loginUser->userTypeID
					// ,'USERTYPEID' 		=> 0
					,'USERTYPENAME'			=> $loginUser->userTypeName
					// ,'USERTYPENAME'		=> 'Super Admin'
					,'AFFILIATEID' 			=> $loginUser->idAffiliate
					// ,'IDAFFILIATE' 		=> 1
					,'AFFILIATENAME' 		=> $loginUser->affiliateName
					// ,'AFFILIATENAME' 	=> 'Affiliate Name'
					,'AFFILIATETAGLINE' 	=> $loginUser->affiliateTagLine
					// ,'AFFILIATETAGLINE' 	=> 'Tag Line'
					,'AFFILIATEREFTAG' 		=> $loginUser->reftag
					// ,'AFFILIATEREFTAG' 	=> '1'
					,'ISAPPROVER' 			=> 1
					,'AFFILIATEDATESTART' 	=> $loginUser->dateStart
					,'AFFILIATEACCSCHED' 	=> $loginUser->affiliateAccSched
					// ,'AFFILIATEMONTH' 	=> $loginUser->affiliateMonth
					,'ISMAIN' 				=> $loginUser->maintag
					// ,'ISCURRENTMAIN' 	=> $loginUser->maintag
					// ,'COMPANYNAME' 		=> $loginUser->companyName
					// ,'SYSTEMNAME' 		=> $loginUser->systemName
					,'SYSTEMNAME' 			=> 'Kiokong Trucking and Construction Accounting System'
					,'COMPLOGO' 			=> $loginUser->companyLogo
					// ,'COMPLOGO' 			=>  $loginUser->companyLogo
					// ,'DEFAULT_COMPLOGO' 	=> (is_url_exist($logoPath.$loginUser->default_companyLogo) ? $loginUser->default_companyLogo : DEFAULT_EMPTY_IMG)
					,'DEFAULT_COMPLOGO' 	=> DEFAULT_EMPTY_IMG
					,'LOCATIONID' 			=> '3'
					// ,'PRINTPATH' 		=> $printPath
					,'LOGOPATH' 			=> $logoPath
					// ,'ISGAE' 			=> $server
				)
			);

			die( json_encode( array( 'trigger' => 0 ,'success'	=> true ) ) );
		}
    }
	
	public function redirurl(){
		
		// die('ni abot dre kay');
		
		redirect('mainview');
	}
	
	public function checklog(){
		$logged_in = $this->session->userdata('logged_in');
		if(isset($logged_in) && $logged_in === TRUE){
			redirect('mainview');
		}
	}
	
	public function logout( $proc = 0 ){
		if( $proc == 1 ){
			$logs[ 'idEu' ] = getsession( 'USERID' );
			$logs[ 'actionLogDescription' ] = getsession( 'USERNAME' ) . ' has logged out of the system.';
			$this->saveLogs( $logs );

			$this->session->sess_destroy();
			redirect( '' );
		}
		else{
			redirect( '' );
		}
    }
	
	public function autoLogout(){
		$this->session->sess_destroy();
		setLogs( array(
			'actionLogDescription'	=> getsession( 'USERNAME' ) . ' has logged out of the system.'
			,'idEu' 				=> getsession( 'USERID' )
		) );
		die(
			json_encode(
				array(
					'success' => TRUE
				)
			)
		);
	}
	
	public function checkIfLogin(){
		$login = 0;
		if($this->session->userdata('logged_in') == 1){
			$login = 1;
		}
		die(
			json_encode(
				array(
					'success' => true
					,'login' => $login
				)
			)
		);
	}

	private function saveLogs( $data )
	{
		$data[ 'moduleID' ] = null;
		setLogs( $data );
	}

	public function getAffiliateByUser(){
		$data      		= getData( FALSE );
		$affiliateData 	= $this->model->getAffiliateByusername( $data['username'] );
		
		
		if( count($affiliateData) == 0 ){
			$affiliateData = 0;
		} else {
			$affiliateData = decryptAffiliate( $affiliateData );
		}
		
		
		echo json_encode($affiliateData);
		die;
	}

	public function getAffiliate(){
		$params = getData();
		$affiliateData = ( empty( $params ) ) ? $this->model->getAllAffiliate() : $this->model->getAllAffiliate( $params );
		$affiliateData = decryptAffiliate( $affiliateData );

		echo json_encode(['data' => $affiliateData]);
		die;
	}
	
	public function checkAffiliate(){
		$params = getData();

		$view = [];
		$view = (array) $this->model->checkAffiliate( $params );
		$view['ok'] = ( $view != null ) ? 1 : 0;
			
		die(
			json_encode(
				array(
					'success'	=> true
					,'view'		=> $view
				)
			)
		);
	}

	public function changeAffiliate(){
		$params = getData();
		$logoPath	= site_url().LOGO_PATH;
		$affiliate = $this->model->getAffiliateDetails($params);
		$affiliate = (object) decryptAffiliate( array( 0 => (array)$affiliate ))[0];
		$affiliate->logo = ( !empty( $affiliate->logo ) && is_url_exist( $logoPath . $affiliate->logo ) ) ? $affiliate->logo : DEFAULT_EMPTY_IMG;

		$this->session->set_userdata('AFFILIATEID', $affiliate->idAffiliate);
		$this->session->set_userdata('AFFILIATENAME', $affiliate->affiliateName);
		$this->session->set_userdata('AFFILIATETAGLINE', $affiliate->tagLine);
		$this->session->set_userdata('AFFILIATEREFTAG', $affiliate->refTag);
		$this->session->set_userdata('AFFILIATEDATESTART', $affiliate->dateStart);
		$this->session->set_userdata('AFFILIATEACCSCHED', $affiliate->accSchedule);
		$this->session->set_userdata('ISMAIN', $affiliate->mainTag);
		$this->session->set_userdata('COMPLOGO', $affiliate->logo);

		setLogs( array(
			'actionLogDescription'	=> getsession( 'USERNAME' ) . ' has changed affiliate.'
			,'idEu' 				=> getsession( 'USERID' )
			,'idAffiliate'			=> $affiliate->idAffiliate
		) );

		die(
			json_encode(
				array(
					'success'	=> true
					,'view'		=> $affiliate
				)
			)
		);
	}

	public function getAdjustments(){
		$params = getData();
		$params['idEu'] = $this->session->userdata('USERID');

		$view = $this->model->getAdjustments();

		die(
			json_encode(
				array(
					'success' 	=> true
					,'data'		=> $view
				)
			)
		);
	}

	public function checkAdjustmentConfirm(){
		$params = getData();
		$view = $this->model->getAdjustments();

		// LQ();

		die(
			json_encode(
				array(
					'success'		=> true
					,'cnt'			=> count( $view )
					,'reorderCnt' 	=> count( $this->getItemReorderLevel(1) )
				)
			)
		);
	}

	/** Added by Hazel
	 *  Date: 06-17-2020
	 * 
	 * This for the additional request by the client to add notifications for Reorder level per item.
	 * **/
	
	public function getItemReorderLevel( $isGetter = 0){
		$params = getData();
		$view = $this->model->getItemReorderLvl();
		$view = decryptItem( $view );
		$forReorder = array();

		foreach( $view as $item){
			$_remaining = $item['receivingQty'] - $item['releasingQty'];

			if( (int)$item['reorderLevel'] > $_remaining ) {
				array_push( $forReorder, array(
					'remaining' 	=> $_remaining
					,'idItem' 		=> $item['idItem']
					,'itemName' 	=> $item['itemName']
					,'reorderLevel'	=> $item['reorderLevel']
					,'idModule'		=> 16 //ADDED BY CHRISTIAN
				));
			}
		}

		if( $isGetter ) {
			return $forReorder;
		} else {
			die(
				json_encode(
					array(
						'success' 	=> true
						,'data'		=> $forReorder
					)
				)
			);
		}
	}

}