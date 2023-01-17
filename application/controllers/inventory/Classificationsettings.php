<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Hazel Alegbeleye
 * Module: Classification Settings
 * Date: December 4, 2019
 * Finished: December 4, 2019
 * Description: This module allows authorized users to set (add, edit and delete) an item classification. 
 * DB Tables: itemclassification, item
 * */
class Classificationsettings extends CI_Controller {

    public function __construct(){
        parent::__construct();
        setHeader( 'inventory/Classificationsettings_model' );
    }

    function getClassCode() {
        $params = getData();
        $view = $this->model->getClassCode( $params );

        die(
            json_encode(
                array(
                    'success' => true
                    ,'view' => $view
                )
            )
        );
    }

    function getItemClassifications() {
        $params = getData();
        $view = $this->model->getItemClassifications( $params );


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

    function saveClassification( $args=false ) {
        $params = $args===false? getData():$args;
        $view = $this->model->saveClassification( $params );

        $msg = ( !$params['onEdit'] ) ? ' edited the classification details.' : ' added a new classification.';

        setLogs(
            array(
                'actionLogDescription' => $this->USERNAME . $msg
                ,'idEu'                => $this->USERID
                ,'moduleID'            => 14
                ,'time'                => date("H:i:s A")
            )
        );

        if ( $args === false ) {
            die(
                json_encode(
                    array(
                        'success' => true
                        ,'view' => $view
                    )
                )
            );
        } else return $view['match'];
    }

    function deleteClassification() {
        $params = getData();
        $match = $this->model->deleteClassification( $params );

        setLogs(
            array(
                'actionLogDescription' => $this->USERNAME . ' deleted a classification'
                ,'idEu'                => $this->USERID
                ,'moduleID'            => 14
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
        $list = $this->model->getItemClassifications( $data );

        $header = array(
            array(
                'header'=>'Classification Code'
                ,'dataIndex'=>'classCode'
                ,'width'=>'40%'	
            ),
            array(
                'header'=>'Classification Name'
                ,'dataIndex'=>'className'
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

    function dieFunc( $success, $msg, $match ) {
        $view['msg']        = $msg;
        $view['match']      = $match;
        $data['success']    = $success;
        $data['view']       = $view;
        die( json_encode($data) );
    }

    function download_format(){
        $data['title'] = 'Import Item Classfications Format';
        $data['directory'] = 'inventory';
        $csvarray[] = array( 'col1' => 'Name' );

        writeCsvFile(
            array(
                'csvarray' 	 => $csvarray
                ,'title' 	 => $data['title']
                ,'directory' => $data['directory']
            )
        );

        force_download($data);
    }

    function IMPORT() {
        require_once APPPATH.'third_party/phpexcel/PHPExcel.php';
        if(isset($_FILES['file_import_Classificationsettings'])) {

            $file = $_FILES['file_import_Classificationsettings'];
            $path = $file['tmp_name'];
            $msg = 'No data!';
            $object = PHPExcel_IOFactory::load($path);

            foreach($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
				$duplicated = array();
				$msg = ''; $addons = '<br></br>';

                if ( $worksheet->getCellByColumnAndRow(0, 1)->getValue() != 'Name' ) $this->dieFunc( true, 'Invalid Format!', 1 );

                for($row=2; $row<=$highestRow; $row++) {

                    if ( !$worksheet->getCellByColumnAndRow(0, $row)->getValue() ) $this->dieFunc( true, 'Incomplete Data!', 1 );
                    
                    $data['onEdit'] = 1;
                    $data['classCode'] = $this->model->getClassCode2('');
                    $data['className'] = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $data['idItemClass'] = 0;
                    $match = $this->saveClassification( $data );

                    if( $match > 0 ) $duplicated[] = array( 'name' => $data['className'] );
                }

                if ( !empty($duplicated) ) {
					$msg = 'The following classifications already exist: <br></br>';
					foreach ($duplicated as $d) $msg .= "-> $d[name] <br></br>";
				} else { $msg = 'SAVE_SUCCESS'; $addons = '';} 
                
                $this->dieFunc( true, $msg.$addons, 1 );
            }
        } 
    }
}