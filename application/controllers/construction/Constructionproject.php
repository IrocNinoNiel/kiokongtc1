<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Constructionproject extends CI_Controller {

    public function __construct(){
        parent::__construct();
        middleware();
        $this->load->library('encryption');
        setHeader( 'construction/Constructionproject_model' );
    }

    function getConSubactivity() {
        $params = getData();

        // Get All ID Activity
        $activityIds = $this->model->getIdActivity($params);
        
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

                 // Get Materials By Sub Activity
                $materials = $this->model->getMaterials($subAct['idConsSubActivity']);
                $materials = decryptItem($materials);
                $activity[$idActivity]['subactivity'][$subKey]['materialDetails'] = [];
                foreach($materials as $materialKey => $material) {
                    $activity[$idActivity]['subactivity'][$subKey]['materialDetails'][$materialKey] = [
                        'itemName'                  => $material['itemName'],
                        'unit'                      => $material['unitCode'],
                        'qty'                       => (float) $material['qty'],
                        'unitCost'                  => (float) $material['unitCost'],
                        'idConsActivityDetailsItem' => (int) $material['idConsActivityDetailsItem'],
                        'idConsActivityDetails'     => (int) $material['idConsActivityDetails'],
                        'idItem'                    => (int) $material['idItem'],
                        'amount'                    => (float) $material['amount'],
                    ];
                }

                // Get Labors By Sub Activity
                $labors = $this->model->getLabors($subAct['idConsSubActivity']);
                $labors = decryptUserData($labors);
                $activity[$idActivity]['subactivity'][$subKey]['laborDetails'] = [];
                foreach($labors as $laborKey => $labor) {
                    $dailyRate = ($labor['monthRate']*12)/313;
                    $activity[$idActivity]['subactivity'][$subKey]['laborDetails'][$laborKey] = [
                        'employeeName'                 => $labor['employeeName'],
                        'qty'                          => (float) $labor['qty'],
                        'unitCost'                     => (float) $dailyRate,
                        'unit'                         => 'days',
                        'monthRate'                    => (float) $labor['monthRate'],
                        'idConsActivityDetailsLabor'   => (int) $labor['idConsActivityDetailsLabor'],
                        'idConsActivityDetails'        => (int) $labor['idConsActivityDetails'],
                        'idEmployee'                   => (int) $labor['idEmployee'],
                        'amount'                       => (float) $dailyRate * $labor['qty'],
                    ];
                }

                // Get Equipment By Sub Activity
                $equipments = $this->model->getActivityEquip($subAct['idConsSubActivity']);
                $activity[$idActivity]['subactivity'][$subKey]['equipDetails'] = [];
                foreach($equipments as $equipKey => $equip) {
                    $activity[$idActivity]['subactivity'][$subKey]['equipDetails'][$equipKey] = [
                        'idConsActivityDetailsEquip'    => (int) $equip['idConsActivityDetailsEquip'],
                        'idConsActivityDetails'         => (int) $equip['idConsActivityDetails'],
                        'idTruckType'                   => (int) $equip['idTruck'],
                        'qty'                           => (float) $equip['qty'],
                        'unitCost'                      => (float) $equip['unitCost'],
                        'amount'                        => (float) $equip['amount'],
                        'truckType'                     => $equip['truckType'],
                    ];
                }

                // Get Indirect By Sub Activity
                $indirectCosts = $this->model->getActivityIndirect($subAct['idConsSubActivity']);
                $isIndirectSet = isset($indirectCosts[0]);
                $activity[$idActivity]['subactivity'][$subKey]['indirectDetails'][0] = [
                    'idConsActivityDetails'  => $isIndirectSet? (int) $indirectCosts[0]['idConsActivityDetails'] : 0
                    ,'idConsSubActivity'     => $isIndirectSet? (int) $indirectCosts[0]['idConsSubActivity'] : 0
                    ,'ocm'                   => $isIndirectSet? (int) $indirectCosts[0]['ocm'] : 0
                    ,'contractorsProfit'     => $isIndirectSet? (int) $indirectCosts[0]['contractorsProfit'] : 0
                    ,'vat'                   => $isIndirectSet? (int) $indirectCosts[0]['vat'] : 0
                ];
            }
        }

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $activity
                )
            )
        );
    }

    function activityGrd() {
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => ''
                )
            )
        );
    }

    function getActivityMaterials() {
        $params = getData();
        $view = null;
        if(isset($params['onEdit']) && $params['onEdit'] == 1) {
            $view = $this->model->getActivityMaterials($params);
        }

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    function getActivityEquip() {
        $params = getData();
        $view = null;
        if(isset($params['onEdit']) && $params['onEdit'] == 1) {
            $view = $this->model->getActivityEquip($params);
        }

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    function getActivityLabor() {
        $params = getData();
        $view = null;
        if(isset($params['onEdit']) && $params['onEdit'] == 1) {
            $view = $this->model->getActivityLabor($params);
            $view = decryptUserData($view);
        }

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    function getTrucks() {
        $params = getData();
        $view = null;
        $view = $this->model->getTrucks($params);

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
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

    function getActivityName() {
        $params = getData();
        $view = $this->model->getActivityName($params);

        if(isset($params['isSelect']) && $params['isSelect'] == 'true') {
            array_unshift( $view, array(
                'id' => 0
                ,'name' => '<span style="color: gray">+ Add Activity Name</span>'
            ));
        }

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    function saveActivitySetting() {
        $params = getData();
        $view = $this->model->saveActivitySetting( $params );

        $msg = ( $params['onEdit'] ) ? ' edited the activity name ': ' added a new activity name ';

        setLogs(
            array(
                'actionLogDescription' => $this->USERNAME . $msg . $params['activityName']
                ,'idEu'                => $this->USERID
                ,'moduleID'            => 81
                ,'time'                => date("H:i:s A")
            )
        );

        die(
            json_encode(
                array(
                    'succes'    => true
                    ,'view'     => $view
                    ,'match'    => 0
                )
            )
        );
    }

    function deleteActivityName() {
        $params = getData();
        $match  = $this->model->deleteActivityName( $params );

        setLogs(
            array(
                'actionLogDescription' => $this->USERNAME . ' deleted the activity name ' . $params['activityName']
                ,'idEu'                => $this->USERID
                ,'moduleID'            => 81
                ,'time'                => date("H:i:s A")
            )
        );

        die(
            json_encode(
                array(
                    'succes' => true
                    ,'match' => $match
                )
            )
        );
    }

    function getProjectID(){
        $view = $this->model->getProjectID();

        die(
            json_encode(
                array(
                    'success'   => true
                    , 'view'    => $view
                )
            )
        );
    }

    function getLocation(){
        $params = getData();
        $view = $this->model->getLocation( $params );
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view['view']
					,'total'    => $view['count']
                )
            )
        );
    }

    function getMaterials(){
        $params = getData();
        $view = $this->model->getMaterials( $params );
        $view = decryptItem( $view );

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    function getOtherDeductions(){
        $params = getData();
        $view = $this->model->getOtherDeductions( $params );

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    function getProjectTeam(){
        $params = getData();
        $view = $this->model->getProjectTeam( $params );
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

    function getConstructionProjectVAT(){
        $params = getData();
        $view = $this->model->getConstructionProjectVAT( $params );

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    function getItems(){
        $params = getData();
        $view = $this->model->getItems( $params );
        $view = decryptItem( $view );

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    function saveActivity() {
        $params = getData();
        $view = $this->model->saveActivity( $params );

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    function saveForm(){
        $params = getData();
        $params['VATGrid']              = json_decode( $params['VATGrid'] );
        $params['locationGrid']         = json_decode( $params['locationGrid'] );
        $params['materialGrid']         = json_decode( $params['materialGrid'] );
        $params['otherDeductionGrid']   = json_decode( $params['otherDeductionGrid'] );
        $params['projectTeamGrid']      = json_decode( $params['projectTeamGrid'] );
        $params['invoices']             = json_decode( $params['invoices'] );
        $params['constructionproject']  = json_decode( $params['constructionproject'] );
        $params['activityDetails']      = json_decode( $params['activityDetails'], true);
        $match = 0;

        $this->db->trans_begin();
        /** SAVING FOR INVOICES **/
        $params['invoices']             = (array) $params['invoices'];
        $date                           = date( 'Y-m-d', strtotime( $params['invoices']['date'] ) ) . " " . date( 'H:i:s', strtotime( date( 'Y-m-d', strtotime( $params['invoices']['date'] ) ) .  $params['invoices']['time'] ) );
        $params['invoices']['date']     = $date;
        $params['invoices']['onEdit']   = $params['onEdit'];

        $idInvoice = $this->model->saveInvoice( $params['invoices'] );

        /** SAVING FOR constructionproject **/
        $params['constructionproject']                       = (array) $params['constructionproject'];
        $params['constructionproject']['onEdit']             = $params['onEdit'];
        $params['constructionproject']['idInvoice']          = $idInvoice;
        $params['constructionproject']['isManual']           = ( $params['constructionproject']['isManual'] == true ) ? 1 : 0;
        $params['constructionproject']['idEu']               = $this->USERID;
        $params['constructionproject']['dateAwarded']        = date( 'Y-m-d', strtotime( $params['constructionproject']['dateAwarded'] ) );
        $params['constructionproject']['dateStart']          = date( 'Y-m-d', strtotime( $params['constructionproject']['dateStart'] ) );
        $params['constructionproject']['dateCompleted']      = date( 'Y-m-d', strtotime( $params['constructionproject']['dateCompleted'] ) );
        $params['constructionproject']['warrantyDateFrom']   = date( 'Y-m-d', strtotime( $params['constructionproject']['warrantyDateFrom'] ) );
        $params['constructionproject']['warrantyDateTo']     = date( 'Y-m-d', strtotime( $params['constructionproject']['warrantyDateTo'] ) );

        $idConstructionProject   = $this->model->saveConstructionProject( $params['constructionproject'] );

        // if((int) $params['onEdit']) {
        //     $this->model->deleteActivityDetails( (int)$params['constructionproject']['idConstructionProject'] );
        // }

        // Saving for Material Grid
        if( count( $params['activityDetails'] ) > 0 ){
            // Activity
            foreach ($params['activityDetails'] as $activityKey => $activityValue) {
                // Sub Activity
                if(count($activityValue['subactivity'])) {
                    foreach($activityValue['subactivity'] as $subActivity) {
                        // Save Subactivity
                        $subActivityDetails['idActivity'] = $activityKey;
                        $subActivityDetails['subActivityName'] = $subActivity['subActivityName'];
                        $subActivityDetails['idConstructionProject'] = $idConstructionProject;
                        $subActivityDetails['idConsSubActivity'] = $subActivity['indirectDetails'][0]['idConsSubActivity'];
                        $subActivityDetails['onEdit'] = $params['onEdit'];
                        $idConsSubActivity = $this->model->saveSubActivity($subActivityDetails);
                        
                        // SAVE ACTIVITY DETAILS
                        $indirectDetails = $subActivity['indirectDetails'][0];
                        $activityDetails['ocm']                 = $indirectDetails['ocm'];
                        $activityDetails['vat']                 = $indirectDetails['vat'];
                        $activityDetails['contractorsProfit']   = $indirectDetails['contractorsProfit'];
                        $activityDetails['onEdit']              = $params['onEdit'];
                        $activityDetails['idConsSubActivity']   = $idConsSubActivity;
                        $idConsActivityDetails = $this->model->saveActivityDetails($activityDetails);

                        // Materials
                        $materials = $subActivity['materialDetails'];
                        if(count($materials)) {
                            $materialDetails = [];
                            foreach($materials as $key => $material) {
                                $materialDetails[$key]['idConsActivityDetails'] = $idConsActivityDetails;
                                $materialDetails[$key]['idItem'] = $material['idItem'];
                                $materialDetails[$key]['qty'] = $material['qty'];
                                $materialDetails[$key]['remainingBalance'] = $material['qty'];
                                $materialDetails[$key]['unitCost'] = $material['unitCost'];
                            }
    
                            $materialDetails['idConsActivityDetails'] = $idConsActivityDetails;
                            $this->model->saveActivityMaterials($materialDetails);
                        }

                        // Labor
                        $labors = $subActivity['laborDetails'];
                        if(count($labors)) {
                            $laborDetails = [];
                            foreach($labors as $key => $labor) {
                                $laborDetails[$key]['idConsActivityDetails'] = $idConsActivityDetails;
                                $laborDetails[$key]['qty'] = $labor['qty'];
                                $laborDetails[$key]['idEmployee'] = $labor['idEmployee'];
                            }
                            $laborDetails['idConsActivityDetails'] = $idConsActivityDetails;
                            $this->model->saveActivityLabors($laborDetails);
                        }

                        // Equipment
                        $equipments = $subActivity['equipDetails'];
                        if(count($equipments)) {
                            $equipDetails = [];
                            foreach($equipments as $key => $equipment) {
                                $equipDetails[$key]['idConsActivityDetails'] = $idConsActivityDetails;
                                $equipDetails[$key]['qty'] = $equipment['qty'];
                                $equipDetails[$key]['idTruck'] = $equipment['idTruckType'];
                                $equipDetails[$key]['unitCost'] = $equipment['unitCost'];
                            }
                            $equipDetails['idConsActivityDetails'] = $idConsActivityDetails;
                            $this->model->saveActivityEquipment($equipDetails);
                        }
                    }
                }
            }
        }

        // Saving for Other Deduction Grid
        if( count( $params['otherDeductionGrid'] ) > 0 ){
            foreach ($params['otherDeductionGrid'] as $key => $value) {
                $value = (array) $value;

                $value['idConstructionProject'] = $idConstructionProject;
                unset( $value['selected'] );
                $params['otherDeductionGrid'][$key] = $value;
            }

            $params['otherDeductionGrid']['onEdit']                 = $params['onEdit'];
            $params['otherDeductionGrid']['idConstructionProject']  = $idConstructionProject;

            $this->model->saveConstructionProjectDeduction( $params['otherDeductionGrid'] );
        }

        // Saving for Project Team Grid
        if( count( $params['projectTeamGrid'] ) > 0 ){
            foreach ($params['projectTeamGrid'] as $key => $value) {
                $value = (array) $value;

                $value['idConstructionProject'] = $idConstructionProject;

                unset( $value['selected'] );
                unset( $value['employeeName'] );
                unset( $value['classification'] );
                unset( $value['status'] );
                unset( $value['idClassification'] );

                $params['projectTeamGrid'][$key] = $value;
            }

            $params['projectTeamGrid']['onEdit']                 = $params['onEdit'];
            $params['projectTeamGrid']['idConstructionProject']  = $idConstructionProject;

            $this->model->saveConstructionProjectTeam( $params['projectTeamGrid'] );
        }

        // Saving for VAT Grid
        if( count( $params['VATGrid'] ) > 0 ){
            foreach ($params['VATGrid'] as $key => $value) {
                $value = (array) $value;

                $value['idConstructionProject'] = $idConstructionProject;
                $value['vatType']               = $value['id'];

                unset( $value['selected'] );
                unset( $value['name'] );
                unset( $value['id'] );
                unset( $value['totalVATExclusive'] );
                unset( $value['totalVATInclusive'] );

                $params['VATGrid'][$key] = $value;
            }

            $params['VATGrid']['onEdit']                 = $params['onEdit'];
            $params['VATGrid']['idConstructionProject']  = $idConstructionProject;

            $this->model->saveConstructionProjectVAT( $params['VATGrid'] );
        }

        // Saving for Location Grid
        if( count( $params['locationGrid'] ) > 0 ){
            foreach ($params['locationGrid'] as $key => $value) {
                $value = (array) $value;
                $locationVal = array();

                $locationVal['idConstructionProject'] = $idConstructionProject;
                $locationVal['idLocation']            = $value['idLocation'];

                $params['locationGrid'][$key] = $locationVal;
            }

            $params['locationGrid']['onEdit']                 = $params['onEdit'];
            $params['locationGrid']['idConstructionProject']  = $idConstructionProject;

            $this->model->saveConstructionProjectLocation( $params['locationGrid'] );
        }

        if( $this->db->trans_status() === FALSE ){
            $this->db->trans_rollback();
            $match = 1;
        } else {
            $this->db->trans_commit();
        }

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'match'    => $match
                    ,'result'   => (int)$params['constructionproject']['idConstructionProject']
                )
            )
        );
    }

    function viewAll(){
        $params = getData();
        $view = $this->model->viewAll( $params );
        
        /** DECRYPTING RESULTS **/
        $view = decryptAffiliate( $view );

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    function viewHistorySearch(){
        $params = getData();
        $view = $this->model->viewHistorySearch( $params );

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    function retrieveData(){
        $params = getData();

        $view = $this->model->retrieveData( $params );
        $view = decryptAffiliate( $view );
        $match = $this->model->checkIfUsed($params);

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                    ,'match'    => $match
                )
            )
        );
    }

    function deleteRecord(){
        $params = getData();
        $match = $this->model->deleteRecord( $params );
        
        if( !$match ){
            $this->setLogs( array( 'delete' => 1 ) );
        }

        die(
            json_encode(
                array(
                    'success' => true
                    ,'match' => $match
                )
            )
        );
    }

    function setLogs( $params ){
		$header = ucfirst( $this->USERFULLNAME );
		$action = '';

        switch( true ){
            case isset( $params['delete'] ):
                $action = 'removed the';
            break;
            case isset( $params['cancelTag'] ) && (int)$params['cancelTag'] == 1:
                $action = 'cancelled the';
            break;
            default:
                if( isset( $params['action'] ) )
                    $action = $params['action'];
                else
                    $action = ( $params['onEdit'] == 1  ? 'modified the' : 'added a new' );
            break;
        }

        $params['actionLogDescription'] = $header . ' ' . $action . ' transaction.';
        $params['idModule']             = 81;

		setLogs( $params );
    }

    function generatePDF() {
        $data = getData();
        $formDetails        = json_decode($data['form'], true);
        $materialGrid       = json_decode($data['materialGrid'], true);
        $otherDeductionGrid = json_decode($data['otherDeductionGrid'], true);
        $projectTeamGrid    = json_decode($data['projectTeamGrid'], true);
        $VATGrid            = json_decode($data['VATGrid'], true);
        $grdLocation        = json_decode($data['grdLocation'], true);
        
        $Main = array(
            'title' => 'Construction Project Form',
            'file_name' => 'Construction Project Form',
            'folder_name' => 'pdf/construction/',
            'orientation' => 'P',
            'table_hidden' => true,
            'noTitle' => true,
            'grid_font_size' => 8
        );
        $activity_col = array(
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
                'header'        => 'Quantity'
                ,'dataIndex'    => 'qty'
                ,'align'        => 'right'
            ),
            array(
                'header'        => 'UnitCost'
                ,'dataIndex'    => 'unitCost'
                ,'align'        => 'right'
            ),
            array(
                'header'        => 'Amount'
                ,'dataIndex'    => 'amount'
                ,'align'        => 'right'
            ),
        );
        $deduction_col = array(
            array(
                'header'        => 'Description'
                ,'dataIndex'    => 'description'
            ),
            array(
                'header'        => 'Amount'
                ,'dataIndex'    => 'amount'
                ,'columnType'   => 'float'
            ),
        );
        $projectteam_col = array(
            array(
                'header'        => 'Employee Name'
                ,'dataIndex'    => 'employeeName'
            ),
            array(
                'header'        => 'Position'
                ,'dataIndex'    => 'classification'
            ),
            array(
                'header'        => 'Status'
                ,'dataIndex'    => 'status'
            ),
        );
        $vat_col = array(
            array(
                'header'        => 'VAT Type'
                ,'dataIndex'    => 'vatType'
            ),
            array(
                'header'        => 'VAT Name'
                ,'dataIndex'    => 'vatName'
            ),
            array(
                'header'        => 'VAT Percent'
                ,'dataIndex'    => 'vatPercent'
                ,'columnType'   => 'percent'
            ),
            array(
                'header'        => 'Total Inclusive'
                ,'dataIndex'    => 'totalVATInclusive'
                ,'columnType'   => 'float'
            ),
            array(
                'header'        => 'Total Exclusive'
                ,'dataIndex'    => 'totalVATExclusive'
                ,'columnType'   => 'float'
            ),
        );
        
        $header_fields = array(
            array(
                array(
                    'label' => 'Affiliate'
                    ,'value' => $data['affiliateName']
                )
                ,array(
                    'label' => 'Reference'
                    ,'value' => $formDetails['pdf_idReference'] . '-' .$formDetails['pdf_referenceNum']
                )
                ,array(
                    'label' => 'Project ID'
                    ,'value' => $formDetails['pdf_idConstructionProject']
                )
                ,array(
                    'label' => 'Project Name'
                    ,'value' => $formDetails['pdf_projectName']
                )
                ,array(
                    'label' => 'Project Status'
                    ,'value' => $formDetails['pdf_status']
                )
                ,array(
                    'label' => 'Status Type'
                    ,'value' => $formDetails['pdf_statusType']
                )
                ,array(
                    'label' => 'Contract Duration'
                    ,'value' => $formDetails['pdf_contractDuration']
                )
                ,array(
                    'label' => 'Contract Amount'
                    ,'value' => $formDetails['pdf_contractAmount']
                )
                ,array(
                    'label' => 'Date Awarded'
                    ,'value' => $formDetails['pdf_dateAwarded']
                )
                ,array(
                    'label' => 'Date Started'
                    ,'value' => $formDetails['pdf_dateStart']
                )
                ,array(
                    'label' => 'Date Completed (as per contract)'
                    ,'value' => $formDetails['pdf_dateCompleted']
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
                    'label' => 'License Name'
                    ,'value' => $formDetails['pdf_licenseName']
                )
                ,array(
                    'label' => 'License Type'
                    ,'value' => $formDetails['pdf_licenseType']
                )
                ,array(
                    'label' => 'License Number'
                    ,'value' => $formDetails['pdf_licenseNumber']
                )
                ,array(
                    'label' => 'Remarks'
                    ,'value' => $formDetails['pdf_remarks']
                )
        
                ,array(
                    'label' => 'Time Extension'
                    ,'value' => $formDetails['pdf_timeExtension']
                )
                ,array(
                    'label' => 'Revised Contract Amount'
                    ,'value' => $formDetails['pdf_revisedContractAmount']
                )
                ,array(
                    'label' => 'Warranty Date'
                    ,'value' => $formDetails['pdf_sdate'] . ' - ' . $formDetails['pdf_edate']
                )
            )
        );

        $header = $this->createFormHeader($header_fields);
        $activityTable = $this->createTable($activity_col, $materialGrid);
        $deductionTable = $this->createTable($deduction_col, $otherDeductionGrid);
        $projectteamTable = $this->createTable($projectteam_col, $projectTeamGrid);
        $vatTable = $this->createTable($vat_col, $VATGrid);
        
        $TOP = $header . 
            '<br><br><br>
            <span style="font-weight: bold; font-size: 1.2em;">Activity</span><br><hr/>' . $activityTable . 
        
            '<br><br><br>
            <span style="font-weight: bold; font-size: 1.2em;">Other Deduction</span><br><hr/>' . $deductionTable . 
        
            '<br><br><br>
            <span style="font-weight: bold; font-size: 1.2em;">Project Team</span><br><hr/>' . $projectteamTable . 
        
            '<br><br><br>
            <span style="font-weight: bold; font-size: 1.2em;">VAT</span><br><hr/>' . $vatTable . '<br>';
        
        generate_table($Main, array(), array(), $TOP);
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

    function createTable($table_col = [], $table_data = []) {
        $col_length = count($table_col);
        $table = '<table border="1" cellpadding="6" style="width:100%; border-collapse: collapse;">';
        
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

    function customListPDF(){
        $params = getData();
        $params = json_decode( $params['items'], true);

        $table = array(
            array(
                'header'        =>'Affiliate'
                ,'dataIndex'    =>'affiliateName'
                ,'width'        =>'12'
            ),
            array(
                'header'        =>'Date'
                ,'dataIndex'    =>'date'
                ,'width'        =>'7'
            ),
            array(
                'header'        =>'Reference'
                ,'dataIndex'    =>'referenceNum'
                ,'width'        =>'7'
            ),
            array(
                'header'        =>'Project ID'
                ,'dataIndex'    =>'idConstructionProject'
                ,'width'        =>'9'
            ),
            array(
                'header'        =>'Project Name'
                ,'dataIndex'    =>'projectName'
                ,'width'        =>'15'
            ),
            array(
                'header'        =>'Contract Duration'
                ,'dataIndex'    =>'contractDuration'
                ,'type'         => 'numbercolumn'
                ,'width'        =>'7'
            ),
            array(
                'header'        =>'Contract Amount'
                ,'dataIndex'    =>'contractAmount'
                ,'type'         => 'numbercolumn'
                ,'width'        =>'9'
            ),
            array(
                'header'        =>'Contract Effectivity'
                ,'dataIndex'    =>'contractEffectivity'
                ,'width'        =>'7'
            ),
            array(
                'header'        =>'Contract Expiry'
                ,'dataIndex'    =>'contractExpiry'
                ,'width'        =>'7'
            ),
            array(
                'header'        =>'License Used'
                ,'dataIndex'    =>'licenseNumber'
                ,'width'        =>'14'
            ),
            array(
                'header'        =>'Status'
                ,'dataIndex'    =>'status'
                ,'width'        =>'9'
            ),
        );

        $data['action'] = " Construction Project List: " .$this->USERNAME. ' printed a PDF report.';
        $this->setLogs( $data );

        generateTcpdf(
			array(
				'file_name'         => 'Construction Project List'
                ,'folder_name'      => 'construction'
                ,'records'          => $params
                ,'header'           => $table
                ,'orientation'      => 'L'
                ,'idAffiliate'      => $this->session->userdata('AFFILIATEID')
			)
        );
    }

    function printExcel (){
		$data = getData();
        $view = json_decode( $data['items'], true );
        $view = (array) $view;

        $csvarray = array();

		$csvarray[] = array( 'title' => $data['pageTitle'].'' );
		$csvarray[] = array( 'space' => '' );
		$csvarray[] = array( 'space' => '' );

		$csvarray[] = array(
            'Affiliate'
			,'Date'
            ,'Reference'
            ,'Project ID'
            ,'Project Name'
            ,'Contract Duration'
            ,'Contact Amount'
            ,'Contract Effectivity'
            ,'Contract Expiry'
            ,'License Used'
            ,'Status'
        );

		foreach( $view as $value ){
			$csvarray[] = array(
				$value[ 'affiliateName' ]
                ,$value[ 'date' ]
                ,$value[ 'referenceNum' ]
                ,$value[ 'idConstructionProject' ]
                ,$value[ 'projectName' ]
                ,$value[ 'contractDuration' ]
                ,$value[ 'contractAmount' ]
                ,$value[ 'contractEffectivity' ]
                ,$value[ 'contractExpiry' ]
                ,$value[ 'licenseNumber' ]
                ,$value[ 'status' ]
			);
        }

		// $data['description'] = '' .$data['pageTitle']. ": " .$this->USERNAME. ' printed an Excel report'  ;
		// $data['iduser'] = $this->USERID;
		// $data['usertype'] = $this->USERTYPEID;
		// $data['printExcel'] = true;
        // $data['ident'] = null;
        // $data['onEdit'] = 0;
        // $this->setLogs( $data );

		writeCsvFile(
			array(
				'csvarray' 	 => $csvarray
				,'title' 	 => $data['pageTitle']
				,'directory' => 'construction'
			)
		);

    }

    function download($title){
		force_download(
			array(
				'title' => $title
				,'directory' => 'construction'
			)
		);
    }

    // function getReturnDetails(){
    //     $params = getData();
    //     $view   = $this->model->getReturnDetails( $params );

    //     die(
    //         json_encode(
    //             array(
    //                 'success'   => true
    //                 ,'view'     => $view
    //             )
    //         )
    //     );
    // }

    // function saveReturn(){
    //     $params = getData();
    //     $params = json_decode( $params['rental'] );
    //     $view   = $this->model->saveReturn( $params );

    //     die(
    //         json_encode(
    //             array(
    //                 'success'   => true
    //                 ,'match'     => !empty( $view ) ? 0 : 1
    //             )
    //         )
    //     );
    // }
}


