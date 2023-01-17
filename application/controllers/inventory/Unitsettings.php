<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Hazel Alegbeleye
 * Module: Unit Settings
 * Date: December 4, 2019
 * Finished: December 4, 2019
 * Description: This module allows the authorized users to set ( add, edit and delete) the unit
 * DB Tables: itemUnit, item
 * */
class Unitsettings extends CI_Controller {

    public function __construct(){
        parent::__construct();
        setHeader( 'inventory/Unitsettings_model' );
    }

    function getUnitCode() {
        $params = getData();
        $view = $this->model->getUnitCode( $params );

        die(
            json_encode(
                array(
                    'success' => true
                    ,'view' => $view
                )
            )
        );
    }

    function getUnits() {
        $params = getData();
        $view = $this->model->getUnits( $params );

        die(
            json_encode(
                array(
                    'success' => true
                    ,'view' => $view
                )
            )
        );
    }

    function retrieveData() {
        $params = getData();
        $view = $this->model->retrieveData( $params );

        die(
            json_encode(
                array(
                    'success' => true
                    ,'view' => $view
                )
            )
        );
    }

    function saveUnit() {
        $params = getData();
        $match = $this->model->saveUnit( $params );

        $msg = ( !$params['onEdit'] ) ? ' edited unit details.' : ' added a new unit.';

        setLogs(
            array(
                'actionLogDescription' => $this->USERNAME . $msg
                ,'idEu'                => $this->USERID
                ,'moduleID'            => 15
                ,'time'                => date("H:i:s A")
            )
        );

        die(
            json_encode(
                array(
                    'success' => true
                    ,'match' => $match
                )
            )
        );
    }

    function deleteUnit() {
        $params = getData();
        $match = $this->model->deleteUnit( $params );

        setLogs(
            array(
                'actionLogDescription' => $this->USERNAME . ' deleted the unit.'
                ,'idEu'                => $this->USERID
                ,'moduleID'            => 15
                ,'time'                => date("H:i:s A")
            )
        );

        die(
            json_encode(
                array(
                    'success' => true
                    ,'match' => $match
                )
            )
        );
    }

    public function generatePDF(){
        $data = getData();
        $list = $this->model->getUnits( $data );

        $header = array(
            array(
                'header'=>'Unit Code'
                ,'dataIndex'=>'unitCode'
                ,'width'=>'40%'	
            ),
            array(
                'header'=>'Unit Name'
                ,'dataIndex'=>'unitName'
                ,'width'=>'60%'
            ),
        );

        $array = array(
            'file_name'	=> $data['pageTitle']
            ,'folder_name' => 'settings'
            ,'records' =>  $list
            ,'header' => $header
       );
       
       $data['ident'] = null;
       generateTcpdf($array);
    }

    function printExcel (){
		$data = getData();
		$sum = 0;
        $view = $this->model->getUnits( $data );
        
		$csvarray[] = array( 'title' => $data['pageTitle'].'' );
		$csvarray[] = array( 'space' => '' );
		$csvarray[] = array( 'space' => '' );

		$csvarray[] = array(
			'col1' => 'Unit Code'
			,'col2' => 'Unit Name'
		);
		
		foreach( $view as $value ){
			$csvarray[] = array(
				'col1' => $value[ 'unitCode' ]
				,'col2' => $value[ 'unitName' ]
			);
        }
        
		$data['description'] = '' .$data['pageTitle']. ": " .$this->USERNAME. ' printed an Excel report'  ;
		$data['iduser'] = $this->USERID;
		$data['usertype'] = $this->USERTYPEID;
		$data['printExcel'] = true;	
        $data['ident'] = null;

		writeCsvFile(
			array(
				'csvarray' 	 => $csvarray
				,'title' 	 => $data['pageTitle'].''
				,'directory' => 'settings'
			)
		);
		
    }
    
    function download($title){
		force_download(
			array(
				'title' => $title
				,'directory' => 'settings'
			)
		);
    }
}