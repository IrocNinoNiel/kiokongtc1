<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Hazel Alegbeleye
 * Module: Affiliate Settings
 * Date: Oct 28, 2019
 * Finished: 
 * Description: The Affiliate Settings module allows only authorized users to setup (add, edit, or delete) affiliate details.
 * DB Tables: affiliate, employee, location
 * */ 
class Affiliatesettings extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->library('encryption');
        setHeader( 'admin/Affiliatesettings_model' );
    }

    public function getAffiliates() {
        $params = getData();
        $view = $this->model->getAffiliates( $params );
        $view['view'] = decryptAffiliate( $view['view'] );

        die(
			json_encode(
				array(
					'success' => true
                    ,'view' => $view['view']
					,'total' => $view['count']
				)
			)
		);
    }

    public function getApprovers() {
        $params = getData();
        $view = $this->model->getApprovers( $params );
        $view = decryptUserData( $view );
        die(
			json_encode(
				array(
					'success' => true
                    ,'view' => $view
				)
			)
		);
    }

    public function saveForm() {
        $params     = getData();
        $_approvers = json_decode( $params['approvers'], false );

        /** Encryption of  fields **/
        if( isset( $params['affiliateName'] ) ) {
            $params['sk'] = initializeSalt( $params['affiliateName'] );
            $this->encryption->initialize( array('key' => generateSKED( $params['sk'] )) );

            $params['affiliateName'] = $this->encryption->encrypt( $params['affiliateName'] ); //172 Char
            if( isset($params['accSchedule']) ) $params['accSchedule'] = $this->encryption->encrypt( $params['accSchedule'] );
            if( isset($params['checkedBy']) ) $params['checkedBy'] = $this->encryption->encrypt( $params['checkedBy'] );
            if( isset($params['reviewedBy']) ) $params['reviewedBy'] = $this->encryption->encrypt( $params['reviewedBy'] );
        } else {
            die('AFFILIATE NAME IS REQUIRED.');
        }

        /** Saving the selected affiliate logo to IMAGES directory. **/
        $photo = $_FILES['logo'.$params['module']];

        if( $photo['size'] ){
            $ext = explode( '.', $photo['name'] );
            $ext = end($ext);
            $image = LOGO_PATH . $photo['name'];
            $params['logo'] = $photo['name'];

            $affiliateLogo = $this->model->getLogo( $params );
            
            /** Saving of Affiliate details to database **/
            $view   = $this->model->saveAffiliate( $params );
            $_approvers['idAffiliate'] = $view['idAffiliate'];

            if(!empty( $affiliateLogo )) {
                $oldImg = LOGO_PATH . $affiliateLogo->logo;
                if(@file_exists( $oldImg) ){
                    @unlink( $oldImg );
                }
            }
            resize_image( $photo['tmp_name'],  $photo['size'], $image  );
        } else {
            /** Saving of Affiliate details to database **/
            $view   = $this->model->saveAffiliate( $params );
            $_approvers['idAffiliate'] = $view['idAffiliate'];

            $affiliateLogo = $this->model->getLogo( $params );
            if( !empty( $affiliateLogo )) {
                $params['logo'] = $affiliateLogo->logo;
            }
        }

        $mainTag    = $this->model->checkMainTag();

        /** Saving approvers **/
        if( count( $_approvers ) > 0 ) $this->model->saveAffiliateApprovers( $this->_unsetApprovers( $_approvers ) );
        
        /** Saving logs **/
        if( isset( $view ) && !empty( $view ) ){
            $msg = ($params['onEdit'] == 0 ) ? 'Added new affiliate, ' : 'Modified the affiliate, ';
            
            /** Decrypting fields **/
            $this->encryption->initialize( array( 'key' => generateSKED( $params['sk'] ) ) );
            $_affiliateName = $this->encryption->decrypt( $params['affiliateName'] );

            setLogs(
                array(
                    'actionLogDescription' => $msg . $_affiliateName
                    ,'idAffiliate'         => $this->session->userdata('AFFILIATEID')
                    ,'idEu'                => $this->USERID
                    ,'moduleID'            => 4
                )
            );

            /** Update session to reflect changes on edit. **/
            if( $view['idAffiliate'] == $this->session->userdata('AFFILIATEID') ) {
                $params['idAffiliate'] = $view['idAffiliate'];
                if( $params['onEdit'] == 1 && !empty($params['logo'] )) $this->updateChanges( (object)$params );
            }
            
        }

        die(
            json_encode(
                array(
                    'success'       => true
                    ,'match'        => $view['match']
                    ,'idAffiliate'  => isset( $view['idAffiliate'] ) ? $view['idAffiliate'] : ''
                    ,'mainTag'      => $mainTag
                )
            )
        );
    }

    public function updateChanges( $affiliate ){
        $logoPath	= site_url().LOGO_PATH;
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
    }

    public function deleteAffiliate(){
        $data = getData( false );
        $match = $this->model->deleteAffiliate( $data );
        $affiliate = $this->model->getAffiliate( $data );

        if( !empty($affiliate[0]['sk'] )) {
            $this->encryption->initialize( array( 'key' => generateSKED( initializeSalt( $affiliate[0]['sk'] ) ) ) );
            $_affiliateName = $this->encryption->decrypt( $affiliate[0]['affiliateName'] );
        }
        

        setLogs(
            array(
                'actionLogDescription' => 'Deleted the affiliate, ' . $affiliate[0]['affiliateName']
                ,'idAffiliate'         => $this->session->userdata('AFFILIATEID')
                ,'idEu'                => $this->USERID
                ,'moduleID'            => 4
                ,'time'                => date("H:i:s A")
            )
        );

        die(
            json_encode(
                array(
                    'success' => true
                    ,'view' => $match
                )
            )
        );
    }

    public function setMainTag(){
        $data = getData( false );

        $this->model->setMainTag( $data );

        die(
            json_encode(
                array(
                    'success' => true
                )
            )
        );
    }
    
    public function getAffiliate() {
        $params     = getData();
        $view       = (array) $this->model->getAffiliate( $params );
        $recMatch   = (array) $this->model->checkAffiliateUsage( $params['idAffiliate'] );
        $mainTag    = $this->model->checkMainTag();
        $view = decryptAffiliate( $view );
     

        $logoPath = LOGO_PATH;
        if( isset($view->logo) && !empty($view->logo) ) {
           $logoPath .= $view->logo;
        } else {
            $logoPath .= 'default-no-img.jpg';
        }

        die(
			json_encode(
				array(
                    'success'       => true
                    ,'view'         => $view
                    ,'rec_match'    => ((int)$recMatch['count'] > 0 ? 1 : 0) // Match: 1 = USED && 0 = USED
                    ,'mainTag'      => $mainTag
                    ,'logoExists'   => @file_exists( $logoPath )
				)
			)
		);
    }

    public function printPDF(){
        $params = getData();
        $list = $this->model->getAffiliates( $params );

         /** Decrypt fields **/
         foreach( $list['view'] as $key => $affiliate ){
            if( isset($affiliate['sk']) || !empty($affiliate['sk']) ){
                $this->encryption->initialize( array('key' => generateSKED( $affiliate['sk'] )) );
                $list['view'][$key]['affiliateName']    = $this->encryption->decrypt($affiliate['affiliateName']);
                $list['view'][$key]['name']             = $this->encryption->decrypt($affiliate['name']);
            }
        }

        $header = array(
			array(
				'header'    =>  'Affiliate Name'
				,'dataIndex'=>  'affiliateName'
				,'width'    =>  '16%'	
            ),
            array(
				'header'    =>  'Address'
				,'dataIndex'=>  'address'
				,'width'    =>  '20%'	
            ),
            array(
				'header'    =>  'Contact Person'
				,'dataIndex'=>  'contactPerson'
				,'width'    =>  '16%'	
            ),
            array(
				'header'    =>  'Email'
				,'dataIndex'=>  'email'
				,'width'    =>  '16%'	
            ),
            array(
				'header'    =>  'TIN'
				,'dataIndex'=>  'tin'
				,'width'    =>  '16%'	
            ),
            array(
				'header'    =>  'Status'
				,'dataIndex'=>  'status'
				,'width'    =>  '16%'	
            )
        );
        
        $array = array(
			'file_name'	        => 'Affiliate List'
			,'folder_name'      => 'admin'
			,'records'          => $list['view']
            ,'header'           => $header
            ,'hasSignatories'   => 0
       );
       
       generateTcpdf($array);
    }

    public function getAffiliateApprovers(){
        $params = getData();
        $list = $this->model->getAffiliateApprovers( $params );
        $list = decryptUserData( $list );

        die(
            json_encode(
               array(
                'success'   => true
                ,'view'     => $list
               )
            )
        );
    }

    public function _unsetApprovers( $params ){
        $approvers = [];

        foreach( $params as $par ){
            if( isset( $par->idEmployee ) ){
                $par->idAffiliate = $params['idAffiliate'];
                array_push( $approvers, unsetParams( (array)$par, 'affiliateapprover') );
            }
        }

        return $approvers;
    }
}