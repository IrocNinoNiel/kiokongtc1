<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Constructionproject extends CI_Controller {

    public function __construct(){
        parent::__construct();
        middleware();
        $this->load->library('encryption');
        setHeader( 'construction/Constructionproject_model' );
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

    function saveForm(){
        $params = getData();
        $params['VATGrid']              = json_decode( $params['VATGrid'] );
        $params['locationGrid']         = json_decode( $params['locationGrid'] );
        $params['materialGrid']         = json_decode( $params['materialGrid'] );
        $params['otherDeductionGrid']   = json_decode( $params['otherDeductionGrid'] );
        $params['projectTeamGrid']      = json_decode( $params['projectTeamGrid'] );
        $params['invoices']             = json_decode( $params['invoices'] );
        $params['constructionproject']  = json_decode( $params['constructionproject'] );

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

        // Saving for Material Grid
        if( count( $params['materialGrid'] ) > 0 ){
            foreach ($params['materialGrid'] as $key => $value) {
                $value = (array) $value;

                $value['idConstructionProject'] = $idConstructionProject;
                $value['approvedCost']          = $value['itemPrice'];

                unset( $value['selected'] );
                unset( $value['itemPrice'] );
                unset( $value['unit'] );
                unset( $value['approvedAmount'] );
                unset( $value['revisedAmount'] );
                unset( $value['barcode'] );
                unset( $value['itemName'] );

                $params['materialGrid'][$key] = $value;
            }

            $params['materialGrid']['onEdit']                   = $params['onEdit'];
            $params['materialGrid']['idConstructionProject']    = $idConstructionProject;

            $this->model->saveConstructionProjectMaterialGrid( $params['materialGrid'] );
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
                    ,'match'     => $match
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

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    function deleteRecord(){
        $params = getData();
        $isUsed = $this->model->deleteRecord( $params );
        
        if( $isUsed > 0 ){
            $match = 1;
        } else {
            $match = 0;

            $this->setLogs( array( 'delete' => 1 ) );
        }
        
    

        die(
            json_encode(
                array(
                    'success' => true
                    ,'match' => !isset( $match ) ? 1 : $match
                )
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
        $params['idModule']             = 71;
		
		setLogs( $params );
    }

    // function customListPDF(){
    //     $params = getData();

    //     $table = array(
    //         array(
    //             'header'        =>'Date'
    //             ,'dataIndex'    =>'date'
    //             ,'width'        =>'12.5'
    //         ),
    //         array(
    //             'header'        =>'Reference'
    //             ,'dataIndex'    =>'referenceNum'
    //             ,'width'        =>'12.5'
    //         ),
    //         array(
    //             'header'        =>'Project Name'
    //             ,'dataIndex'    =>'projectName'
    //             ,'width'        =>'12.5'
    //         ),
    //         array(
    //             'header'        =>'Customer Name'
    //             ,'dataIndex'    =>'customerName'
    //             ,'width'        =>'12.5'
    //         ),
    //         array(
    //             'header'        =>'Contact Number'
    //             ,'dataIndex'    =>'customerContactNumber'
    //             ,'width'        =>'12.5'
    //         ),
    //         array(
    //             'header'        =>'Plate Number'
    //             ,'dataIndex'    =>'plateNumber'
    //             ,'width'        =>'12.5'
    //         ),
    //         array(
    //             'header'        =>'Driver Name'
    //             ,'dataIndex'    =>'driverName'
    //             ,'width'        =>'12.5'
    //         ),
    //         array(
    //             'header'        =>'Status'
    //             ,'dataIndex'    =>'status'
    //             ,'width'        =>'12.5'
    //         )
    //     );

    //     // $data['action'] = " Rental of Heavy Equipment List: " .$this->USERNAME. ' printed a PDF report.';
    //     // $this->setLogs( $data );

    //     generateTcpdf(
	// 		array(
	// 			'file_name'         => 'Rental of Heavy Equipment List'
    //             ,'folder_name'      => 'trucking'
    //             ,'records'          => json_decode( $params['items'], true )
    //             ,'header'           => $table
    //             ,'orientation'      => 'P'
    //             ,'idAffiliate'      => $this->session->userdata('AFFILIATEID')
	// 		) 
    //     );
        
    // }

    // function printExcel (){
	// 	$data = getData();
    //     $view = json_decode( $data['items'], true );
    //     $view = (array) $view;
        
	// 	$csvarray[] = array( 'title' => $data['pageTitle'].'' );
	// 	$csvarray[] = array( 'space' => '' );
	// 	$csvarray[] = array( 'space' => '' );

	// 	$csvarray[] = array(
	// 		'col1'  => 'Date'
    //         ,'col2' => 'Reference'
    //         ,'col3' => 'Project Name'
    //         ,'col4' => 'Customer Name'
    //         ,'col5' => 'Contact Number'
    //         ,'col6' => 'Plate Number'
    //         ,'col7' => 'Driver Name'
    //         ,'col8' => 'Status'
    //     );

	// 	foreach( $view as $value ){
	// 		$csvarray[] = array(
	// 			'col1' => $value[ 'date' ]
    //             ,'col2' => $value[ 'referenceNum' ]
    //             ,'col3' => $value[ 'projectName' ]
    //             ,'col4' => $value[ 'customerName' ]
    //             ,'col5' => $value[ 'customerContactNumber' ]
    //             ,'col6' => $value[ 'plateNumber' ]
    //             ,'col7' => $value[ 'driverName' ]
    //             ,'col8' => $value[ 'status' ]
	// 		);
    //     }

	// 	$data['description'] = '' .$data['pageTitle']. ": " .$this->USERNAME. ' printed an Excel report'  ;
	// 	$data['iduser'] = $this->USERID;
	// 	$data['usertype'] = $this->USERTYPEID;
	// 	$data['printExcel'] = true;	
    //     $data['ident'] = null;

	// 	writeCsvFile(
	// 		array(
	// 			'csvarray' 	 => $csvarray
	// 			,'title' 	 => $data['pageTitle']
	// 			,'directory' => 'trucking'
	// 		)
	// 	);
		
    // }
    
    // function download($title){
	// 	force_download(
	// 		array(
	// 			'title' => $title
	// 			,'directory' => 'trucking'
	// 		)
	// 	);
    // }
}