<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Hazel Alegbeleye
 * Module: Location Settings
 * Date: December 4, 2019
 * Finished: December 4, 2019
 * Description: This module allows authorized user to set up the location that will be used in transactions.
 * DB Tables: location, affiliate
 * */ 
class Locationsettings extends CI_Controller {

    public function __construct(){
        parent::__construct();
        setHeader( 'generalsettings/Locationsettings_model' );
    }

    function getLocationCode() {
        $params = getData();
        $view = $this->model->getLocationCode( $params );

        die(
            json_encode(
                array(
                    'success' => true
                    ,'view' => $view
                )
            )
        );
    }

    function getLocations() {
        $params = getData();
        $view = $this->model->getLocations( $params );

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

    function saveLocation() {
        $params = getData();
        $view = $this->model->saveLocation( $params );

        die(
            json_encode(
                array(
                    'success' => true
                    ,'view' => $view
                )
            )
        );
    }

    function deleteLocation() {
        $params = getData();
        $view = $this->model->deleteLocation( $params );

        die(
            json_encode(
                array(
                    'success' => true
                    ,'view' => $view
                )
            )
        );
    }

    public function generatePDF(){
        $data = getData();
        $list = $this->model->getLocations( $data );

        $header = array(
            array(
                'header'=>'Location Code'
                ,'dataIndex'=>'locationCode'
                ,'width'=>'40%'	
            ),
            array(
                'header'=>'Location Name'
                ,'dataIndex'=>'locationName'
                ,'width'=>'60%'
            ),
        );

        $array = array(
            'file_name'	=> $data['pageTitle']
            ,'folder_name' => 'generalsettings'
            ,'records' =>  $list['view']
            ,'header' => $header
       );
       
       $data['ident'] = null;
       generateTcpdf($array);
    }
}