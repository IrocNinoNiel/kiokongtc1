<?php if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * Developer: Niño Niel B. Iroc
 * Module: Logs Summary
 * Date: Jan 11, 2023
 * Finished:
 * Description: 
 * DB Tables:
 * */

class Projectaccomplishmentsummary extends CI_Controller{

    public function __construct(){
        parent::__construct();
        middleware();
        $this->load->library('encryption');
        setHeader('construction/Projectaccomplishmentsummary_model');
    }

    public function getAffiliate(){
        $params = getData();
        $view   = $this->model->getAffiliate( $params, $this->session->userdata('EMPLOYEEID') );
        $view   = decryptAffiliate( $view );

        // LQ();
        if( isset($params['hasAll']) && count( $view ) > 1 ) {
            array_unshift( $view, array(
                'id' => 0
                ,'name' => ( isset( $params['allValue'] )? $params['allValue'] : 'All' )
            ));
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

    function getProjectNames() {
        $params = getData();
        $view = $this->model->getConstructionProject($params);

        array_unshift( $view, array(
            'id' => 0
            ,'name' => 'All'
        ));

        die(
            json_encode(
                array(
                     'success' => true
                    ,'view'    => $view
                )
            )
        );
    }

    function getProjectAccomplishmentSummary() {
        $params = getData();
        $view = $this->model->getProjectAccomplishmentSummary( $params );
        $view = decryptAffiliate($view);
    
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    function printPDF(){ 
        $params = getData();
        $gridData = json_decode($params['grid'], true);

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
                'header'        => 'reference'
                ,'dataIndex'    => 'referenceNum'
                ,'width'        => '10%'
            ),
            array(   
                'header'        => 'Project Name'
                ,'dataIndex'    => 'projectName'
                ,'width'        => '15%'
            ),
            array(   
                'header'        => 'Date From'
                ,'dataIndex'    => 'dateFrom'
                ,'width'        => '10%'
            ),
            array(   
                'header'        => 'Date To'
                ,'dataIndex'    => 'dateTo'
                ,'width'        => '10%'
            ),
            array(   
                'header'        => '% Accomplished'
                ,'dataIndex'    => 'percentAccomplished'
                ,'width'        => '10%'
            ),
            array(   
                'header'        => 'Project Status'
                ,'dataIndex'    => 'projectStatus'
                ,'width'        => '10%'
            ),
            array(   
                'header'        => 'Status Type'
                ,'dataIndex'    => 'statusType'
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
                    'label'     => 'Project Name'
                    ,'value'    => $params['pdf_idProject']
                ),
                array(
                    'label'     => 'As of Date'
                    ,'value'    => $params['asOfDate'] 
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
                ,'folder_name'   => 'construction'
                ,'records'       => $gridData
                ,'header'        => $col
                ,'orientation'   => 'L'
                ,'header_fields' => $header_fields
            )
        );
    }

    public function printExcel() {
        $params = getData();
        $gridData = json_decode($params['grid'], true);

        $csvarray   = array();
        $csvarray[] = array( 'title'         => $params['title'] );
        $csvarray[] = array( 'Affiliate',    $params['pdf_idAffiliate']  );
        $csvarray[] = array( 'Project Name', $params['pdf_idProject']  );
        $csvarray[] = array( 'As of Date',   $params['asOfDate'] );
        $csvarray[] = array( 'space'         => '' );

        $csvarray[] = array(
            'Affiliate'
           ,'Date'
           ,'Reference'
           ,'Project Name'
           ,'Date From'
           ,'Date To'
           ,'% Accomplished'
           ,'Project Status'
           ,'Status Type'
       );

       foreach( $gridData as $d ){
            $csvarray[] = array(
                $d['affiliateName']
                ,$d['date']
                ,$d['referenceNum']
                ,$d['projectName']
                ,$d['dateFrom']
                ,$d['dateTo']
                ,$d['percentAccomplished']
                ,$d['projectStatus']
                ,$d['statusType']
            );
        }

        setLogs(
            array(
                'actionLogDescription'  => 'Exported the generated Project Accomplishment Summary Report (Excel).'
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
                ,'directory' => 'construction'
            )
        );

    }

    function download($title){
		force_download(
			array(
				'title'         => $title
				,'directory'    => 'construction'
			)
		);
	}

}
