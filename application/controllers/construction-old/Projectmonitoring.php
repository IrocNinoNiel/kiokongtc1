<?php if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

class Projectmonitoring extends CI_Controller{

    public function __construct(){
        parent::__construct();
        middleware();
        $this->load->library('encryption');
        setHeader('construction/Projectmonitoring_model');
    }

    public function getProjectActivity() {
        $params = getData();
        $view   = $this->model->getProjectActivity($params);

        $view = decryptAffiliate( $view );

        die(
            json_encode(
                array(
                     'success' => true
                    ,'view'    => $view
                )
            )
        );
    }

    public function getProjectNames() {
        $params = getData();
        $view   = $this->model->getProjectNames($params);

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

    function printPDF(){
        $params = getData();
        $data   = $this->model->getProjectActivity( $params );
        $data   = decryptAffiliate( $data );

        $col    = array(
            array(   
                'header'        => 'Affiliate'
                ,'dataIndex'    => 'affiliateName'
                ,'width'        => '8%'
            ),
            array(   
                'header'        => 'Contract ID'
                ,'dataIndex'    => 'idContract'
                ,'width'        => '7%'
            ),
            array(   
                'header'        => 'Project Name'
                ,'dataIndex'    => 'projectName'
                ,'width'        => '8%'
            ),
            array(   
                'header'        => 'Contract Duration'
                ,'dataIndex'    => 'contractDuration'
                ,'width'        => '7%'
            ),
            array(   
                'header'        => 'Contract Amount'
                ,'dataIndex'    => 'contractAmount'
                ,'width'        => '7%'
            ),
            array(   
                'header'        => 'Contract Effectivity'
                ,'dataIndex'    => 'contractEffectivity'
                ,'width'        => '7%'
            ),
            array(   
                'header'        => 'Contract Expiry'
                ,'dataIndex'    => 'contractExpiry'
                ,'width'        => '7%'
            ),
            array(   
                'header'        => 'License Used'
                ,'dataIndex'    => 'licenseUsed'
                ,'width'        => '7%'
            ),
            array(   
                'header'        => 'License Type'
                ,'dataIndex'    => 'licenseType'
                ,'width'        => '7%'
            ),
            array(   
                'header'        => 'Status'
                ,'dataIndex'    => 'status'
                ,'width'        => '7%'
            ),
            array(   
                'header'        => 'Status Type'
                ,'dataIndex'    => 'statusType'
                ,'width'        => '7%'
            ),
            array(   
                'header'        => '% Accomplished'
                ,'dataIndex'    => 'percentAccomplished'
                ,'width'        => '7%'
            ),
            array(   
                'header'        => 'Warranty Date From'
                ,'dataIndex'    => 'warrantyDateFrom'
                ,'width'        => '7%'
            ),
            array(   
                'header'        => 'Warranty Date To'
                ,'dataIndex'    => 'warrantyDateTo'
                ,'width'        => '7%'
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
                    ,'value'    => $params['pdf_projectName']
                ),
                array(
                    'label'     => 'As of Date'
                    ,'value'    => $params['asOfDate'] 
                )
            )
        );

        setLogs(
            array(
                'actionLogDescription' => 'Exported the generated Project Monitoring Report (PDF)'
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
                ,'records'       => $data
                ,'header'        => $col
                ,'orientation'   => 'L'
                ,'header_fields' => $header_fields
            )
        );
    }

    public function printExcel() {
        $params = getData();
        $data   = $this->model->getProjectActivity( $params );
        $data   = decryptAffiliate( $data );

        $csvarray   = array();
        $csvarray[] = array( 'title'         => $params['title'] );
        $csvarray[] = array( 'Affiliate',    $params['pdf_idAffiliate']  );
        $csvarray[] = array( 'Project Name', $params['pdf_projectName']  );
        $csvarray[] = array( 'As of Date',   $params['asOfDate'] );
        $csvarray[] = array( 'space'         => '' );

        $csvarray[] = array(
             'Affiliate Name'
            ,'Contract ID'
            ,'Project Name'
            ,'Contract Duration'
            ,'Contract Amount'
            ,'Contract Effectivity'
            ,'Contract Expiry'
            ,'License Used'
            ,'License Type'
            ,'Status'
            ,'Status Type'
            ,'Percent Accomplished'
            ,'Warranty Date From'
            ,'Warranty Date To'
        );

        foreach( $data as $d ){
            $csvarray[] = array(
                 $d['affiliateName']
                ,$d['idContract']
                ,$d['projectName']
                ,$d['contractDuration']
                ,$d['contractAmount']
                ,$d['contractEffectivity']
                ,$d['contractExpiry']
                ,$d['licenseUsed']
                ,$d['licenseType']
                ,$d['status']
                ,$d['statusType']
                ,$d['percentAccomplished']
                ,$d['warrantyDateFrom']
                ,$d['warrantyDateTo']
            );
        }

        setLogs(
            array(
                'actionLogDescription'  => 'Exported the generated Project Monitoring Report (Excel).'
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
