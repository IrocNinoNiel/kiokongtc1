<?php if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

class Projectaccomplishment extends CI_Controller{

    public function __construct(){
        parent::__construct();
        middleware();
        $this->load->library('encryption');
        setHeader('construction/Projectaccomplishment_model');
    }

    function getAccomplishment() {
        $params = getData();
        $view   = $this->model->getAccomplishment($params);
        $view   = decryptAffiliate( $view );

        die(
            json_encode(
                array(
                     'success' => true
                    ,'view'    => $view
                )
            )
        );
    }

    function retrieveAccomplishment() {
        $params = getData();
        $view   = $this->model->retrieveAccomplishment($params);
        $view   = decryptAffiliate( $view );

        die(
            json_encode(
                array(
                     'success' => true
                    ,'view'    => $view
                )
            )
        );
    }

    function getActivityDetails() {
        $params         = getData();
        $activityIds    = $this->model->getIdActivity($params);

        $activity = [];
        // Get All Sub Activity By ID Activity
        foreach($activityIds as $actId){
            $idActivity = $actId['idActivity'];
            $activity[$idActivity] = [
                'activityName'  => $actId['activityName'],
            ];

            $subActivities = $this->model->getSubActivity($params, $idActivity);

            foreach($subActivities as $subKey => $subAct) {
                $activity[$idActivity]['subactivity'][$subKey] = [
                    'subActivityName' => $subAct['subActivityName'],
                ];

                $params['idConsSubActivity'] = $subAct['idConsSubActivity']; 

                $materials = $this->model->getMaterials($params);
                
                $materials = decryptItem($materials);
                $activity[$idActivity]['subactivity'][$subKey]['materialDetails'] = [];
                foreach($materials as $materialKey => $material) {
                    $activity[$idActivity]['subactivity'][$subKey]['materialDetails'][$materialKey] = [
                        'itemName'                  => $material['itemName'],
                        'unit'                      => $material['unitCode'],
                        'approvedQty'               => (float) $material['qty'],
                        'approvedCost'              => (float) $material['unitCost'],
                        'idConsActivityDetailsItem' => (int) $material['idConsActivityDetailsItem'],
                        'idConsActivityDetails'     => (int) $material['idConsActivityDetails'],
                        'idItem'                    => (int) $material['idItem'],
                        'approvedAmount'            => (float) $material['amount'],
                        'remainingBalance'          => (float) $material['remainingBalance'],  //modified 5/19/22
                        'releasedQty'               => isset($material['releasedQty'])? (float) $material['releasedQty'] : 0,
                        'releasedCost'              => isset($material['releasedCost'])? (float) $material['releasedCost'] : 0,
                        'idConsActivityDetailsItemReleased' => isset($material['idConsActivityDetailsItemReleased'])? (int) $material['idConsActivityDetailsItemReleased'] : '',
                    ];
                }

                // Get Labors By Sub Activity
                $labors = $this->model->getLabors($params);
                $labors = decryptUserData($labors);
                $activity[$idActivity]['subactivity'][$subKey]['laborDetails'] = [];
                foreach($labors as $laborKey => $labor) {
                    $dailyRate = ($labor['monthRate']*12)/313;
                    $activity[$idActivity]['subactivity'][$subKey]['laborDetails'][$laborKey] = [
                        'employeeName'                 => $labor['employeeName'],
                        'approvedQty'                  => (float) $labor['qty'],
                        'approvedCost'                 => (float) $dailyRate,
                        'unit'                         => 'days',
                        'monthRate'                    => (float) $labor['monthRate'],
                        'idConsActivityDetailsLabor'   => (int) $labor['idConsActivityDetailsLabor'],
                        'idConsActivityDetails'        => (int) $labor['idConsActivityDetails'],
                        'idEmployee'                   => (int) $labor['idEmployee'],
                        'approvedAmount'               => (float) $dailyRate * $labor['qty'],
                        'releasedQty'                  => isset($labor['releasedQty'])? (float) $labor['releasedQty'] : 0,
                        'releasedCost'                 => isset($labor['releasedCost'])? (float) $labor['releasedCost'] : 0,
                    ];
                }

                // Get Equipment By Sub Activity
                $equipments = $this->model->getActivityEquip($params);
                $activity[$idActivity]['subactivity'][$subKey]['equipDetails'] = [];
                foreach($equipments as $equipKey => $equip) {
                    $activity[$idActivity]['subactivity'][$subKey]['equipDetails'][$equipKey] = [
                        'idConsActivityDetailsEquip'    => (int) $equip['idConsActivityDetailsEquip'],
                        'idConsActivityDetails'         => (int) $equip['idConsActivityDetails'],
                        'idTruck'                       => (int) $equip['idTruck'],
                        'approvedQty'                   => (float) $equip['qty'],
                        'approvedCost'                  => (float) $equip['unitCost'],
                        'approvedAmount'                => (float) $equip['amount'],
                        'truckType'                     => $equip['truckType'],
                        'releasedQty'                  => isset($equip['releasedQty'])? (float) $equip['releasedQty'] : 0,
                        'releasedCost'                 => isset($equip['releasedCost'])? (float) $equip['releasedCost'] : 0,
                    ];
                }

                // Get Indirect By Sub Activity
                $indirectCosts = $this->model->getActivityIndirect($subAct['idConsSubActivity']);
                foreach($indirectCosts as $indirectKey => $indirect) {
                    $activity[$idActivity]['subactivity'][$subKey]['indirectDetails'][$indirectKey] = [
                        'idConsActivityDetails' => (int) $indirect['idConsActivityDetails'],
                        'idConsSubActivity'     => (int) $indirect['idConsSubActivity'],
                        'ocm'                   => (int) $indirect['ocm'],
                        'contractorsProfit'     => (int) $indirect['contractorsProfit'],
                        'vat'                   => (int) $indirect['vat'],
                    ];
                }
            }
        }

        die(
            json_encode(
                array(
                     'success' => true
                    ,'view'    => json_encode($activity)
                )
            )
        );
    }

    function getBOQ() {
        $params = getData();

        die(
            json_encode(
                array(
                     'success' => true
                )
            )
        );
    }

    function getTrucking() {
        $params = getData();
        $view   = $this->model->getTrucking($params);
        $view   = decryptUserData( $view );

        die(
            json_encode(
                array(
                     'success' => true
                    ,'view'    => $view
                )
            )
        );
    }

    function getDisbursement() {
        $params = getData();
        $view   = $this->model->getDisbursement($params);

        die(
            json_encode(
                array(
                     'success' => true
                    ,'view'    => $view
                )
            )
        );
    }

    function getVouchers() {
        $params = getData();
        $view   = $this->model->getVouchers($params);

        die(
            json_encode(
                array(
                     'success' => true
                    ,'view'    => $view
                )
            )
        );
    }

    function getProjectName() {
        $params = getData();
        $view   = $this->model->getProjectName($params);

        die(
            json_encode(
                array(
                     'success' => true
                    ,'view'    => $view
                )
            )
        );
    }

    function getContractor() {
        $params = getData();
        $view   = $this->model->getContractor($params);
        $view  = decryptUserData( $view );
        
        die(
            json_encode(
                array(
                     'success' => true
                    ,'view'    => $view
                )
            )
        );
    }

    function getConsContract() {
        $params = getData();
        $view   = $this->model->getConsContract($params);

        die(
            json_encode(
                array(
                     'success'  => true
                    ,'view'     => $view
                )
            )
        );
    }

    function getTotalReleasedAmnt() {
        $params = getData();
        $view   = $this->model->getTotalReleasedAmnt($params);

        die(
            json_encode(
                array(
                     'success'  => true
                    ,'view'     => $view
                )
            )
        );
    }

    function deleteProjectAccomplishment() {
        $match  = 0;
        $params = getData();
        $view   = $this->model->deleteProjectAccomplishment($params);

        die(
            json_encode(
                array(
                     'success'  => true
                    ,'view'     => $view
                    ,'match'    => $match
                )
            )
        );
    }

    function saveProjectAccomplishment() {
        $match          = 0;
        $params         = getData();
        $invoices       = json_decode($params['invoices'], true);       unset($params['invoices']);
        $boqData        = json_decode($params['boqData'], true);        unset($params['boqData']);
        $boqDataHolder  = json_decode($params['boqDataHolder'], true);  unset($params['boqDataHolder']);

        $this->db->trans_start();

        /** SAVING FOR INVOICES **/
        $invoices['date']   = date( 'Y-m-d', strtotime( $invoices['date'] ) ) . " " . date( 'H:i:s', strtotime( date( 'Y-m-d', strtotime( $invoices['date'] ) ) .  $invoices['time'] ) );
        $invoices['onEdit'] = (int) $params['onEdit'];
        $idInvoice          = $this->model->saveInvoice( $invoices );

        /** SAVING FOR PROJECT ACCOMPLISHMENT **/
        $params['idInvoice']        = $idInvoice;
        $idProjectAccomplishment    = $this->model->saveProjectAccomplishment( $params );

        /** SAVE RELEASED **/
        $data = [];
        if((int) $params['onEdit']) $this->model->deleteConstructionReleasing( $idProjectAccomplishment );
        foreach($boqDataHolder as $key => $boq) {
            $boqDataHolder[$key]['idInvoice'] = $idInvoice;
            $boqDataHolder[$key]['fIDModule'] = $params['idmodule'];
            $boqDataHolder[$key]['idConsprojectAccomplishment'] = $idProjectAccomplishment;
        }
        
        $this->model->saveConstructionReleasing( $boqDataHolder );
        // if(isset($boqData['materials'])) {
            //     if((int) $params['onEdit']) $this->model->deleteActivityMaterials( $idProjectAccomplishment );
                
            //     foreach($boqData['materials'] as $item) {
            //         $item['idProjectAccomplishment'] = $idProjectAccomplishment;
            //         $item['idConsActivityDetailsItem'] = $item['id'];
            //         $item['type'] = $item['id'];

            //         $this->model->updateItemRemainingBalance( $item );
            //         $this->model->saveMaterialReleasedQty( $item );
            //     }
            // }
            // if(isset($boqData['labors'])) {
            //     if((int) $params['onEdit']) $this->model->deleteActivityLabors( $idProjectAccomplishment );
            //     foreach($boqData['labors'] as $item) {
            //         $item['idProjectAccomplishment'] = $idProjectAccomplishment;
            //         $item['idConsActivityDetailsLabor'] = $item['id'];
            //         $this->model->saveLaborReleasedQty( $item );
            //     }
            // }
            // if(isset($boqData['equipments'])) {
            //     if((int) $params['onEdit']) $this->model->deleteActivityEquip( $idProjectAccomplishment );
            //     foreach($boqData['equipments'] as $item) {
            //         $item['idProjectAccomplishment'] = $idProjectAccomplishment;
            //         $item['idConsActivityDetailsEquip'] = $item['id'];
            //         $this->model->saveEquipmentReleasedQty( $item );
            //     }
        // }

        if($this->db->trans_status()) {
			$this->db->trans_complete();

            $msg = ($params['onEdit']) ? ' edited the project accomplishment details.' : ' added a new project accomplishment.';

            setLogs(
                array(
                    'actionLogDescription' => $this->USERNAME . $msg
                    ,'idAffiliate'         => ( isset($params['idAffiliate']) && !empty( $params['idAffiliate'] ) ) ? $params['idAffiliate'] : $this->AFFILIATEID
                    ,'idEu'                => $this->USERID
                    ,'idModule'            => $invoices['idModule']
                    ,'time'                => date("H:i:s A"),
                )
            );

            die(
                json_encode(
                    array(
                        'success'   => true
                        ,'view'     => $idProjectAccomplishment
                        ,'match'    => $match
                    )
                )
            );

        } else {
            $this->db->trans_rollback();
            die(json_encode(array('success'=>false, 'match'=>0)));
        }
    }

    function printPDFForm() {
        $data           = getData();
        $formDetails    = json_decode($data['form'], true);          unset($data['form']);
        $boq            = json_decode($data['boq'], true);           unset($data['boq']);
        $disbursement   = json_decode($data['disbursement'], true);  unset($data['disbursement']);

        $Main = array(
            'title' => 'Project Accomplishment Form',
            'file_name' => $data['title'] . ' Form',
            'folder_name' => 'pdf/construction/',
            'orientation' => 'L',
            'table_hidden' => true,
            'noTitle' => true,
            'grid_font_size' => 9
        );
        $header_fields = array(
            array(
                array(
                    'label' => 'Reference'
                    ,'value' => $formDetails['pdf_idReference'] . '-' .$formDetails['pdf_referenceNum']
                )
                ,array(
                    'label' => 'Project Name'
                    ,'value' => $formDetails['pdf_projectName']
                )
                ,array(
                    'label' => 'Contractor'
                    ,'value' => $formDetails['pdf_contractor']
                )
                ,array(
                    'label'     => 'Project Percent Accomplished'
                    ,'value'    => $formDetails['pdf_projectPercentAccomplished']
                )
            )
            ,array(
                array(
                    'label' => 'Date'
                    ,'value' => $formDetails['pdf_tdate']
                )
                ,array(
                    'label' => 'Cost Center'
                    ,'value' => $formDetails['pdf_idCostCenter']
                )
                ,array(
                    'label'     => 'Date Range'
                    ,'value'    => $formDetails['pdf_sdate'] . ' - ' . $formDetails['pdf_edate']
                )
            )
        );
        $boq_col = array(
            array(
                'header'        => 'Item No.'
                ,'dataIndex'    => 'itemNo'
            ),
            array(
                'header'        => 'Type'
                ,'dataIndex'    => 'type'
            ),
            array(
                'header'        => 'Work Description'
                ,'dataIndex'    => 'workDescription'
            ),
            array(
                'header'        => 'Unit'
                ,'dataIndex'    => 'unitCode'
            ),
            array(
                'header'        => 'Approved Quantity'
                ,'dataIndex'    => 'approvedQty'
                ,'align'        => 'right'
            ),
            array(
                'header'        => 'Approved UnitCost'
                ,'dataIndex'    => 'approvedCost'
                ,'align'        => 'right'
            ),
            array(
                'header'        => 'Approved Amount'
                ,'dataIndex'    => 'approvedAmount'
                ,'align'        => 'right'
            ),
            array(
                'header'        => 'Released Quantity'
                ,'dataIndex'    => 'releasedQty'
                ,'align'        => 'right'
            ),
            array(
                'header'        => 'Released UnitCost'
                ,'dataIndex'    => 'releasedCost'
                ,'align'        => 'right'
            ),
            array(
                'header'        => 'Released Amount'
                ,'dataIndex'    => 'releasedAmount'
                ,'align'        => 'right'
            ),
        );

        $disbursement_col = array(
            array(
                'header'        => 'Date'
                ,'dataIndex'    => 'date'
            ),
            array(
                'header'        => 'Reference Number'
                ,'dataIndex'    => 'referenceNumber'
            ),
            array(
                'header'        => 'Amount'
                ,'dataIndex'    => 'amount'
            )
        );

        $table_string = $this->createFormHeader($header_fields); //Header
        $table_string .= $this->createTable($boq_col, $boq, 'BOQ'); //BOQ Grid
        $table_string .= $this->createTable($disbursement_col, $disbursement, 'Disbursement'); //Disbursement Grid
        
        generate_table($Main, array(), array(), $table_string);
    }

    function printPDF() {
        $params = getData();
        $data   = $this->model->getAccomplishment($params);;
        $data   = decryptAffiliate( $data );

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
                'header'        => 'Reference'
                ,'dataIndex'    => 'referenceNum'
                ,'width'        => '10%'
            ),
            array(
                'header'        => 'Contract ID'
                ,'dataIndex'    => 'idContract'
                ,'width'        => '10%'
            ),
            array(
                'header'        => 'Project Name'
                ,'dataIndex'    => 'projectName'
                ,'width'        => '20%'
            ),
            array(
                'header'        => 'Contractor'
                ,'dataIndex'    => 'contractorName'
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
            )
        );

        $header_fields = array(
            array(
                array(
                    'label'     => 'Date'
                    ,'value'    => date("Y-m-d")
                )
            )
        );

        setLogs(
            array(
                'actionLogDescription' => 'Exported the generated Project Accomplishment Report (PDF)'
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
                ,'orientation'   => 'P'
                ,'header_fields' => $header_fields
            )
        );
    }

    public function printExcel() {
        $params = getData();

        $data   = $this->model->getAccomplishment( $params );
        $data = decryptAffiliate( $data );

        $csvarray = array();
        $csvarray[] = array( 'title' => $params['title'] );
        $csvarray[] = array( 'space' => '' );
        $csvarray[] = array( 'Date ', date("Y-m-d")  );
        $csvarray[] = array( 'space' => '' );

        $csvarray[] = array(
            'Affiliate'
            ,'Date'
            ,'Reference'
            ,'Contract ID'
            ,'Project Name'
            ,'Contractor'
            ,'Date From'
            ,'Date To'
        );

        foreach( $data as $d ){
            $csvarray[] = array(
                $d['affiliateName']
                ,$d['date']
                ,$d['referenceNum']
                ,$d['idContract']
                ,$d['projectName']
                ,$d['contractorName']
                ,$d['dateFrom']
                ,$d['dateTo']
            );
        }

        setLogs(
            array(
                'actionLogDescription'  => 'Exported the generated Project Accomplishment Report (Excel).'
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

    function createFormHeader($header_fields = []) {
        // Create row data
        $arr1 = $header_fields[0];
        $arr2 = $header_fields[1];
        $max = max(count($arr1), count($arr2));

        for ($row = 0; $row < $max; $row++) {
            $combined[$row][] = isset($arr1[$row])? $arr1[$row] : array();
            $combined[$row][] = isset($arr2[$row])? $arr2[$row] : array();
        }

        $table = '<table cellspacing="10">';
        foreach($combined as $row) {
            $table .= '<tr>';
            foreach($row as $col) {
                $table .= '<td>';
                if(count($col) > 0) {
                    $table .= '<strong>' . $col['label'] . ': </strong>' . $col['value'];
                }
                $table .= '</td>';
            }
            $table .= '</tr>';
        }
        $table .= '</table>';

        return $table;
    }

    function createTable($table_col = [], $table_data = [], $table_title = '') {
        $col_length = count($table_col);
        $table = '<br><br><br>
            <span style="font-weight: bold; font-size: 1.2em;">' . $table_title . '</span><br><hr/>
            <table border="1" cellpadding="6" style="width:100%; border-collapse: collapse;">';
        
        if(!empty($table_col)) {
            $table .= '<tr style="text-align: center; font-weight: bold; background-color:#f1f1f1;">';
            foreach($table_col as $col){
                $table .= '<th>' . $col['header'] . '</th>';
            }
            $table .= '</tr>';
        }
        
        if(!empty($table_data)) {
            foreach($table_data as $data_row){
                $table .= '<tr>';
                foreach($table_col as $data_col){
                    $text_align = isset($data_col['align'])? $data_col['align'] : 'left';
                    $value      = $data_row[$data_col['dataIndex']];

                    if(isset($data_col['columnType']) && ($data_col['columnType'] == 'number' || $data_col['columnType'] == 'float' || $data_col['columnType'] == 'percent')) {
                        $text_align = 'right';

                        if($data_col['columnType'] == 'float') {
                            $value = number_format($value, 2);
                        } else if($data_col['columnType'] == 'percent') {
                            $value = $value . '%';
                        }
                    }
                    $table .= '<td style="text-align: ' . $text_align . ';">' . $value . '</td>';
                }
                $table .= '</tr>';
            }
        } else {
            $table .= '<tr>';
            $table .= '<td colspan="' . $col_length . '" style="text-align: center;">No Records</td>';
            $table .= '</tr>';
        }
        $table .= '</table>';

        return $table;
    }
}