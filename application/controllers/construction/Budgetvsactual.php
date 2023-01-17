<?php if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

class Budgetvsactual extends CI_Controller{

    public function __construct(){
        parent::__construct();
        middleware();
        $this->load->library('encryption');
        setHeader('construction/Budgetvsactual_model');
    }

    function getProjectNames() {
        $params = getData();
        $view   = $this->model->getProjectNames($params);

        die(
            json_encode(
                array(
                     'success' => true
                    ,'view'    => $view
                )
            )
        );
    }

    function getBudgetVsActual() {
        $params = getData();
        
        $totalDirectCost = $this->model->getTotalItemLaborEquip($params);
        $view   = $this->model->getBudgetVsActual($params);
        $view = decryptAffiliate($view);

        die(
            json_encode(
                array(
                     'success' => true
                    ,'view'    => $view
                    ,'totalDirectCost'   => $totalDirectCost
                )
            )
        );
    }

    function printPDF(){
        $params = getData();
        $gridData = json_decode($params['grid'], true);

        $header_fields = array(
            array(
                array(
                    'label'     => 'Affiliate'
                    ,'value'    => $params['pdf_idAffiliate']
                ),
                array(
                    'label'     => 'Project Name'
                    ,'value'    => $params['pdf_projectName']
                )
            )
            ,array(
                array(
                    'label'     => 'Project Status'
                    ,'value'    => $params['pdf_projectStatus']
                ),
                array(
                    'label'     => 'Date'
                    ,'value'    => $params['pdf_asOfDate']
                )
            )
        );

        $col    = array(
            array(
                'header'        => 'Affiliate'
                ,'dataIndex'    => 'affiliateName'
                ,'width'        => '15%'
            ),
            array(
                'header'        => 'Date'
                ,'dataIndex'    => 'date'
                ,'width'        => '8%'
            ),
            array(
                'header'        => 'Project Name'
                ,'dataIndex'    => 'projectName'
                ,'width'        => '23%'
            ),
            array(
                'header'        => 'Contract Duration'
                ,'dataIndex'    => 'contractDuration'
                ,'width'        => '8%'
            ),
            array(
                'header'        => 'Contract Effectivity'
                ,'dataIndex'    => 'contractEffectivity'
                ,'width'        => '8%'
            ),
            array(
                'header'        => 'Contract Expiry'
                ,'dataIndex'    => 'contractExpiry'
                ,'width'        => '8%'
            ),
            array(
                'header'        => 'Contract Amount'
                ,'dataIndex'    => 'contractAmount'
                ,'type'        => 'numbercolumn'
                ,'width'        => '8%'
            ),
            array(
                'header'        => 'Actual Amount Spent'
                ,'dataIndex'    => 'actualAmountSpent'
                ,'type'        => 'numbercolumn'
                ,'width'        => '8%'
            ),
            array(
                'header'        => 'Project Status'
                ,'dataIndex'    => 'projectStatus'
                ,'width'        => '8%'
            ),
            array(
                'header'        => 'Profit/Loss'
                ,'dataIndex'    => 'profitLoss'
                ,'type'        => 'numbercolumn'
                ,'width'        => '8%'
            ),
        );
       
        setLogs(
            array(
                'actionLogDescription' => 'Exported the generated Budget vs. Actual Report (PDF)'
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

    function printExcel() {
        $params = getData();
        $gridData = json_decode($params['grid'], true);
        
        $csvarray = array();
        $csvarray[] = array( 'title'            => $params['title'] );
        $csvarray[] = array( 'Affiliate',       $params['pdf_idAffiliate']  );
        $csvarray[] = array( 'Project Status',  $params['pdf_projectStatus']  );
        $csvarray[] = array( 'Project Name',    $params['pdf_projectName']  );
        $csvarray[] = array( 'Date',            $params['pdf_asOfDate'] );
        $csvarray[] = array( 'space'            => '' );

        $csvarray[] = array(
            'Affiliate',
            'Date',
            'Project Name',
            'Contract Duration',
            'Contract Effectivity',
            'Contract Expiry',
            'Contract Amount',
            'Actual Amount Spent',
            'Project Status',
            'Profit/Loss'
        );

        foreach( $gridData as $d ){
            $csvarray[] = array(
                $d['affiliateName']
                ,$d['date']
                ,$d['projectName']
                ,$d['contractDuration']
                ,$d['contractEffectivity']
                ,$d['contractExpiry']
                ,$d['contractAmount']
                ,$d['actualAmountSpent']
                ,$d['projectStatus']
                ,$d['profitLoss']
            );
        }
        
        setLogs(
            array(
                'actionLogDescription'  => 'Exported the generated Budget vs. Actual Report (EXCEL)'
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
