<?php if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

class Materialmonitoring extends CI_Controller{

    public function __construct(){
        parent::__construct();
        middleware();
        $this->load->library('encryption');
        setHeader('construction/Materialmonitoring_model');
    }

    function getBalance() {
        $params = getData();
        $view   = $this->model->getBalance($params);
        $view   = decryptItem( $view );

        die(
            json_encode(
                array(
                     'success' => true
                    ,'view'    => $view
                )
            )
        );
    }

    function getLedger() {
        $params = getData();
        $view   = $this->model->getLedger($params);

        $view   = decryptAffiliate( $view );
        $view   = decryptItem( $view );

        die(
            json_encode(
                array(
                     'success' => true
                    ,'view'    => $view
                )
            )
        );
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

    function getItems() {
        $params = getData();
        $view   = $this->model->getItems($params);
        $view   = decryptItem( $view );

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
        $gridData = json_decode($params['grid'], true);

        if($params['isBalance'] == 1) {
            $orientation = 'L';
            $col    = array(
                array(
                    'header'        => 'Item No.'
                    ,'dataIndex'    => 'itemNo'
                    ,'width'        => '8%'
                ),
                array(
                    'header'        => 'Material'
                    ,'dataIndex'    => 'itemName'
                    ,'width'        => '30%'
                ),
                array(
                    'header'        => 'Approved Quantity'
                    ,'dataIndex'    => 'approvedQty'
                    ,'type'         => 'numbercolumn'
                    ,'width'        => '8%'
                ),
                array(
                    'header'        => 'Approved Unit'
                    ,'dataIndex'    => 'unitCode'
                    ,'width'        => '8%'
                ),
                array(
                    'header'        => 'Approved Unit Cost'
                    ,'dataIndex'    => 'approvedCost'
                    ,'type'         => 'numbercolumn'
                    ,'width'        => '8%'
                ),
                array(
                    'header'        => 'Approved Amount'
                    ,'dataIndex'    => 'approvedAmount'
                    ,'type'         => 'numbercolumn'
                    ,'width'        => '8%'
                ),
                array(
                    'header'        => 'Balance Quantity'
                    ,'dataIndex'    => 'balanceQty'
                    ,'type'         => 'numbercolumn'
                    ,'width'        => '8%'
                ),
                array(
                    'header'        => 'Balance Unit'
                    ,'dataIndex'    => 'unitCode'
                    ,'width'        => '8%'
                ),
                array(
                    'header'        => 'Balance Unit Cost'
                    ,'dataIndex'    => 'approvedCost'
                    ,'type'         => 'numbercolumn'
                    ,'width'        => '8%'
                ),
                array(
                    'header'        => 'Balance Amount'
                    ,'dataIndex'    => 'balanceAmount'
                    ,'type'         => 'numbercolumn'
                    ,'width'        => '8%'
                ),
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
                        'label'     => 'Date'
                        ,'value'    => $params['pdf_asOfDateBalance']
                    )
                )
            );
        } else {
            $orientation = 'P';
            $col    = array(
                array(
                    'header'        => 'Affiliate'
                    ,'dataIndex'    => 'affiliateName'
                    ,'width'        => '30%'
                ),
                array(
                    'header'        => 'Date'
                    ,'dataIndex'    => 'date'
                    ,'width'        => '15%'
                ),
                array(
                    'header'        => 'Reference'
                    ,'dataIndex'    => 'referenceNum'
                    ,'width'        => '15%'
                ),
                array(
                    'header'        => 'Quantity'
                    ,'dataIndex'    => 'ledgerQty'
                    ,'type'         => 'numbercolumn'
                    ,'width'        => '10%'
                ),
                array(
                    'header'        => 'Unit'
                    ,'dataIndex'    => 'ledgerUnit'
                    ,'width'        => '10%'
                ),
                array(
                    'header'        => 'Unit Price'
                    ,'dataIndex'    => 'ledgerUnitCost'
                    ,'type'         => 'numbercolumn'
                    ,'width'        => '10%'
                ),
                array(
                    'header'        => 'Amount'
                    ,'dataIndex'    => 'ledgerAmount'
                    ,'type'         => 'numbercolumn'
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
                        ,'value'    => $params['pdf_projectName']
                    )
                ),
                array(
                    array(
                        'label'     => 'Item'
                        ,'value'    => $params['pdf_itemName']
                    ),
                    array(
                        'label'     => 'Date'
                        ,'value'    => $params['pdf_asOfDateLedger']
                    )
                )
            );
        }

        setLogs(
            array(
                'actionLogDescription' => 'Exported the generated Material Monitoring Report - ' . $params['isBalance'] == 1? 'Balance' : 'Ledger' . ' (PDF)'
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
                ,'orientation'   => $orientation
                ,'header_fields' => $header_fields
            )
        );
    }

    public function printExcel() {
        $params = getData();
        $gridData = json_decode($params['grid'], true);
        
        if($params['isBalance'] == 1) {
            $csvarray = array();
            $csvarray[] = array( 'title'         => $params['title'] );
            $csvarray[] = array( 'Affiliate',    $params['pdf_idAffiliate']  );
            $csvarray[] = array( 'Project Name', $params['pdf_projectName']  );
            $csvarray[] = array( 'Date',         $params['pdf_asOfDateBalance'] );
            $csvarray[] = array( 'space'         => '' );

            $csvarray[] = array(
                'Item No.',
                'Material',
                'Approved Quantity',
                'Approved Unit',
                'Approved Unit Cost',
                'Approved Amount',
                'Balance Quantity',
                'Balance Unit',
                'Balance Unit Cost',
                'Balance Amount'
            );

            foreach( $gridData as $d ){
                $csvarray[] = array(
                    $d['itemNo']
                    ,$d['itemName']
                    ,$d['approvedQty']
                    ,$d['unitCode']
                    ,$d['approvedCost']
                    ,$d['approvedAmount']
                    ,$d['balanceQty']
                    ,$d['unitCode']
                    ,$d['approvedCost']
                    ,$d['balanceAmount']
                );
            }

        } else {
            $csvarray = array();
            $csvarray[] = array( 'title'         => $params['title'] );
            $csvarray[] = array( 'Affiliate',    $params['pdf_idAffiliate']  );
            $csvarray[] = array( 'Project Name', $params['pdf_projectName']  );
            $csvarray[] = array( 'Item',         $params['pdf_itemName']  );
            $csvarray[] = array( 'Date',         $params['pdf_asOfDateLedger'] );
            $csvarray[] = array( 'space'         => '' );

            $csvarray[] = array(
                'Affiliate',
                'Date',
                'Reference',
                'Quantity',
                'Unit',
                'Unit Price',
                'Amount',
            );

            foreach( $gridData as $d ){
                $csvarray[] = array(
                    $d['affiliateName']
                    ,$d['date']
                    ,$d['referenceNum']
                    ,$d['ledgerQty']
                    ,$d['ledgerUnit']
                    ,$d['ledgerUnitCost']
                    ,$d['ledgerAmount']
                );
            }
        }

        setLogs(
            array(
                'actionLogDescription'  => 'Exported the generated Project Monitoring Report - ' . $params['isBalance'] == 1? 'Balance' : 'Ledger' . ' (Excel).'
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




