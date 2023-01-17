<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Hazel Alegbeleye
 * Module: Cost Center Settings
 * Date: Oct 29, 2019
 * Finished: 
 * Description: 
 * DB Tables: 
 * */ 
class Costcentersettings extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->library('encryption');
        setHeader( 'admin/Costcentersettings_model' );
    }

    public function getAffiliates() {
        $params = getData();
        $view = $this->model->getAffiliates( $params );

        // LQ();

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

    public function getCostCenters() {
        $params = getData();
        $list = $this->model->getCostCenters( $params );

        /** Decrypt fields **/
        foreach( $list['view'] as $key => $costCenter ){
            if( isset($costCenter['sk']) || !empty($costCenter['sk']) ){
                $this->encryption->initialize( array('key' => generateSKED( $costCenter['sk'] )) );
                $list['view'][$key]['costCenterName']    = $this->encryption->decrypt($costCenter['costCenterName']);
            }
        }

        die(
			json_encode(
				array(
					'success' => true
                    ,'view' => $list['view']
					,'total' => $list['count']
				)
			)
		);
    }

    public function getCostCenter() {
        $params = getData();
        $view = $this->model->getCostCenter( $params );

        /**Decryption of fields**/
        if( !empty( $view[0] ) ){
            if( isset( $view[0]['sk'] ) ) {
                $this->encryption->initialize( array('key' => generateSKED( $view[0]['sk'] )) );
                $view[0]['costCenterName']    = $this->encryption->decrypt($view[0]['costCenterName']);
            }
        }


        die(
			json_encode(
				array(
                    'success' => true
                    ,'view' => $view
				)
			)
		);
    }

    public function deleteCostCenter(){
        $data = getData();
        $match = $this->model->deleteCostCenter( $data );

        die(
            json_encode(
                array(
                    'success' => true
                    ,'view' => $match
                )
            )
        );
    }

    public function saveCostCenter() {
        $params = getData( true );

        /** Encryption of  fields **/
        if( isset( $params['costCenterName'] ) ) {
            $params['sk'] = initializeSalt( $params['costCenterName'] );
            $this->encryption->initialize( array('key' => generateSKED( $params['sk'] )) );
            $params['costCenterName'] = $this->encryption->encrypt( $params['costCenterName'] ); //172 Char
        } else {
            die('COST CENTER NAME IS REQUIRED.');
        }

        $idCostCenter = $this->model->saveCostCenter( $params );
        $match = ( empty($idCostCenter) || $idCostCenter < 0 ) ? 1 : 0;

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => array( 'idCostCenter' => $idCostCenter, 'match' => $match )
                )
            )
        );
    }

    
    public function saveCostAffiliate() {
        $params = getData();
        $view = $this->model->saveCostAffiliate( $params );

        die(
            json_encode(
                array(
                    'success' => true
                    ,'view' => $view
                )
            )
        );
    }

    public function updateCostCenter(){
        $data = getData( false );
        $view = [];
        $view = $this->model->updateCostCenter( $data );

        die(
            json_encode(
                array(
                    'success' => true
                    ,'view' => $view
                )
            )
        );
    }

    public function generateCostCenterPDF(){
        $data = getData();
        $list = $this->model->getCostCenters($data);

        /** Decrypt fields **/
        foreach( $list['view'] as $key => $costCenter ){
            if( isset($costCenter['sk']) || !empty($costCenter['sk']) ){
                $this->encryption->initialize( array('key' => generateSKED( $costCenter['sk'] )) );
                $list['view'][$key]['costCenterName']    = $this->encryption->decrypt($costCenter['costCenterName']);
            }
        }

        $header = array(
			array(
				'header'=>'Cost Center Name'
				,'dataIndex'=>'costCenterName'
				,'width'=>'50%'	
			),
			array(
				'header'=>'Status'
				,'dataIndex'=>'status'
                ,'width'=>'50%'
			),
		);

		$array = array(
			'file_name'	=> 'Cost Center List'
			,'folder_name' => 'admin'
			,'records' =>  $list['view']
			,'header' => $header
       );
       
    //    $data['ident'] = null;
       generateTcpdf($array);
    }

    public function printCostCenterExcel (){
		
		$data = getData();
		$sum = 0;
        $list = $this->model->getCostCenters( $data );
        
        /** Decrypt fields **/
        foreach( $list['view'] as $key => $costCenter ){
            if( isset($costCenter['sk']) || !empty($costCenter['sk']) ){
                $this->encryption->initialize( array('key' => generateSKED( $costCenter['sk'] )) );
                $list['view'][$key]['costCenterName']    = $this->encryption->decrypt($costCenter['costCenterName']);
            }
        }


		$csvarray[] = array( 'title' => $data['pageTitle'].'' );
		$csvarray[] = array( 'space' => '' );
		$csvarray[] = array( 'space' => '' );

		$csvarray[] = array(
			'col1' => 'Cost Center Name'
			,'col2' => 'Status'
		);
		
		foreach( $list['view'] as $value ){
			$csvarray[] = array(
				'col1' => $value[ 'costCenterName' ]
				,'col2' => $value[ 'status' ]
			);
        }
        
		$data['description'] = '' .$data['pageTitle']. ": " .$this->USERNAME. ' printed a Excel report'  ;
		$data['iduser'] = $this->USERID;
		$data['usertype'] = $this->USERTYPEID;
		$data['printExcel'] = true;	
        $data['ident'] = null;

		writeCsvFile(
			array(
				'csvarray' 	 => $csvarray
				,'title' 	 => $data['pageTitle'].''
				,'directory' => 'admin'
			)
		);
		
    }
    
    function download($title){
		force_download(
			array(
				'title' => $title
				,'directory' => 'admin'
			)
		);
    }
    
    public function getSearchCostCenter(){
        $params = getData();
        $list = $this->model->getSearchCostCenter( $params );

        foreach( $list as $key => $costCenter ){
            if( isset($costCenter['sk']) ){
                $this->encryption->initialize( array('key' => generateSKED( $costCenter['sk'] )) );
                $list[$key]['name'] = $this->encryption->decrypt ($costCenter['name']); 
            }
        }

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $list
                )
            )
        );
    }
}