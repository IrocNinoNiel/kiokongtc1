<?php if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * Developer: Niño Niel B. Iroc
 * Module: Logs Summary
 * Date: Jan 11, 2023
 * Finished:
 * Description: 
 * DB Tables:
 * */

class Logssummary extends CI_Controller{

    public function __construct(){
        parent::__construct();
        middleware();
        $this->load->library('encryption');
        setHeader('payroll/Logssummary_model');
    }


    function getEmployees(){
        $params = getData();
        $view = $this->model->getEmployees( $params );
        $view = decryptUserData( $view );
    
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    function getLogsSummary(){
        $params = getData();
        echo print_r($params);
    
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => []
                )
            )
        );
    }

    function printPDF(){ 
        $params = getData();
        $data   = $this->model->getLogsummary( $params );

        $data   = decryptAffiliate( $data );
        $data   = decryptUserData( $data );

        $col    = array(
            array(   
                'header'        => 'Affiliate'
                ,'dataIndex'    => 'affiliateName'
                ,'width'        => '15%'
            ),
            array(   
                'header'        => 'Date'
                ,'dataIndex'    => 'date'
                ,'width'        => '10%'
            ),
            array(   
                'header'        => 'Name'
                ,'dataIndex'    => 'name'
                ,'width'        => '10%'
            ),
            array(   
                'header'        => 'Position'
                ,'dataIndex'    => 'position'
                ,'width'        => '15%'
            ),
            array(   
                'header'        => 'Time Start'
                ,'dataIndex'    => 'timeStart'
                ,'width'        => '10%'
            ),
            array(   
                'header'        => 'Time End'
                ,'dataIndex'    => 'timeEnd'
                ,'width'        => '10%'
            ),
            array(   
                'header'        => 'Total Regular Hours'
                ,'dataIndex'    => 'totalReguarHours'
                ,'width'        => '10%'
            ),
            array(   
                'header'        => 'Overtime Hours'
                ,'dataIndex'    => 'overtimeHours'
                ,'width'        => '10%'
            ),
            array(   
                'header'        => 'Total Hours'
                ,'dataIndex'    => 'totalHours'
                ,'width'        => '10%'
            )
        );

        $header_fields = array(
            array(
                array(
                    'label'     => 'Affiliate'
                    ,'value'    => $params['pdf_idAffiliate']
                ),
                array(
                    'label'     => 'Date'
                    ,'value'    => $params['pdf_date']
                ),
                array(
                    'label'     => 'Reference'
                    ,'value'    => $params['pdf_reference']
                ),
                array(
                    'label'     => 'Name'
                    ,'value'    => $params['pdf_name']
                ),
                array(
                    'label'     => 'Position'
                    ,'value'    => $params['pdf_position']
                ),
                array(
                    'label'     => 'Time Start'
                    ,'value'    => $params['pdf_timeStart']
                ),
                array(
                    'label'     => 'Time End'
                    ,'value'    => $params['pdf_timeEnd']
                ),
                array(
                    'label'     => 'Total Regular Hours'
                    ,'value'    => $params['pdf_totalRegularHours']
                ),
                array(
                    'label'     => 'Overtime Hours'
                    ,'value'    => $params['pdf_overtimeHours']
                ),
                array(
                    'label'     => 'Total Hours'
                    ,'value'    => $params['pdf_totalHours']
                )
            )
        );

        setLogs(
            array(
                'actionLogDescription' => 'Exported the generated Logs Summary Report (PDF)'
                ,'idAffiliate'         => ( isset($params['idAffiliate']) && !empty( $params['idAffiliate'] ) ) ? $params['idAffiliate'] : $this->AFFILIATEID
                ,'idEu'                => $this->USERID
                ,'idModule'            => $params['idModule']
                ,'time'                => date("H:i:s A")
            )
        );

        generateTcpdf(
            array(
                'file_name'      => $params['title']
                ,'folder_name'   => 'payroll'
                ,'records'       => $data
                ,'header'        => $col
                ,'orientation'   => 'P'
                ,'header_fields' => $header_fields
            )
        );
    }

    public function printExcel() {
        $params = getData();

        $data   = $this->model->getLogsummary( $params );

        $data = decryptAffiliate( $data );
        $data = decryptUserData( $data );

        $csvarray = array();

        $csvarray[] = array( 'title'                => $params['title'] );
        $csvarray[] = array( 'Affiliate',           $params['pdf_idAffiliate']  );
        $csvarray[] = array( 'Date',                $params['sdate'] . ' to ' . $params['edate']  );
        $csvarray[] = array( 'Name',                $params['pdf_name']  );
        $csvarray[] = array( 'Position',            $params['pdf_position']  );
        $csvarray[] = array( 'Time Start',          $params['pdf_timeStart']  );
        $csvarray[] = array( 'Time End',            $params['pdf_timeEnd']  );
        $csvarray[] = array( 'Total Regular Hours', $params['pdf_totalRegularHours']  );
        $csvarray[] = array( 'Overtime Hours',      $params['overtimeHours'] );
        $csvarray[] = array( 'Total Hours',         $params['totalHours'] );
        $csvarray[] = array( 'space'                => '' );

        $csvarray[] = array(
            'Affiliate'
            ,'Date'
            ,'Name'
            ,'Position'
            ,'Time Start'
            ,'Time End'
            ,'Total Regular Hours'
            ,'Overtime Hours'
            ,'Total Hours'
        );

        foreach( $data as $d ){
            $csvarray[] = array(
                $d['affiliateName']
                ,$d['date']
                ,$d['name']
                ,$d['position']
                ,$d['timeEnd']
                ,$d['timeStart']
                ,$d['totalRegularHours']
                ,$d['overtimeHours']
                ,$d['totalHours']
            );
        }

        setLogs(
            array(
                'actionLogDescription'  => 'Exported the generated Logs Summary Report(Excel).'
			    ,'idAffiliate'	        => $this->session->userdata('AFFILIATEID')
                ,'idEu'                 => $this->USERID
                ,'idModule'             => $params['idModule']
                ,'time'                 => date('H:i:s A')
            )
        );

        writeCsvFile(
            array(
                'csvarray' 	 => $csvarray
                ,'title' 	 => $params['title'] 
                ,'directory' => 'payroll'
            )
        );
    }
}
