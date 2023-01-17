<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deliveryticket extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->library('encryption');
        setHeader( 'trucking/Deliveryticket_model' );
    }

    function getConstructionProjects(){
        if( validSession() ){
            $params = getData();
            $view = $this->model->getConstructionProjects( $params );

            die(
                json_encode(
                    array(
                        'success'   => true
                        ,'view'     => $view
                    )
                )
            );
        } else {
            die(
                json_encode(
                    array(
                        'status'    => false,
                        'msg'       => 'You are not authorized to perform this action.'
                    )
                )
            );
        }
    }

    function getDrivers(){
        if( validSession() ){
            $params = getData();
            $view   = $this->model->getDrivers( $params );
            $view	= decryptUserData( $view );


            die(
                json_encode(
                    array(
                        'success'   => true
                        ,'view'     => $view
                    )
                )
            );
        } else {
            die(
                json_encode(
                    array(
                        'status'    => false,
                        'msg'       => 'You are not authorized to perform this action.'
                    )
                )
            );
        }
    }

    function getTruckTypes(){
        if( validSession() ){
            $params = getData();
            $view   = $this->model->getTruckTypes( $params );


            die(
                json_encode(
                    array(
                        'success'   => true
                        ,'view'     => $view
                    )
                )
            );
        } else {
            die(
                json_encode(
                    array(
                        'status'    => false,
                        'msg'       => 'You are not authorized to perform this action.'
                    )
                )
            );
        }
    }

    function getCustomers(){
        if( validSession() ){
            $params = getData();
            $view   = $this->model->getCustomers( $params );
            $view	= decryptSupplier( $view );

            array_unshift( $view, array(
                'id' => 0
                ,'name' => 'WALK-IN'
            ));


            die(
                json_encode(
                    array(
                        'success'   => true
                        ,'view'     => $view
                    )
                )
            );
        } else {
            die(
                json_encode(
                    array(
                        'status'    => false,
                        'msg'       => 'You are not authorized to perform this action.'
                    )
                )
            );
        }
    }

    function getClassifications(){
        if( validSession() ){
            $params = getData();
            $view = $this->model->getClassifications( $params );

            die(
                json_encode(
                    array(
                        'success'   => true
                        ,'view'     => $view
                    )
                )
            );
        } else {
            die(
                json_encode(
                    array(
                        'status'    => false,
                        'msg'       => 'You are not authorized to perform this action.'
                    )
                )
            );
        }
    }

    function getItems(){
        if( validSession() ){
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
        } else {
            die(
                json_encode(
                    array(
                        'status'    => false,
                        'msg'       => 'You are not authorized to perform this action.'
                    )
                )
            );
        }
    }

    function getProjects(){
        if( validSession() ){
            $params = getData();
            // var_dump( $params['isConstruction'] );
            $view = ( $params['isConstruction'] === "true" || $params['isConstruction'] == 1 ) ? $this->model->getConstructionProjects() : $this->model->getTruckProjects();

            die(
                json_encode(
                    array(
                        'success'   => true
                        ,'view'     => $view
                    )
                )
            );
        } else {
            die(
                json_encode(
                    array(
                        'status'    => false,
                        'msg'       => 'You are not authorized to perform this action.'
                    )
                )
            );
        }
    }

    function getPlateNumber(){
        if( validSession() ){
            $params = getData();
            $view = $this->model->getPlateNumber( $params );

            die(
                json_encode(
                    array(
                        'success'   => true
                        ,'view'     => $view
                    )
                )
            );
        } else {
            die(
                json_encode(
                    array(
                        'status'    => false,
                        'msg'       => 'You are not authorized to perform this action.'
                    )
                )
            );
        }
    }

    function saveForm(){
        if( validSession() ){
            $params = getData();
            $params['loadActivityGrid'] = json_decode( $params['loadActivityGrid'] );
            $params['dailyActivityGrid'] = json_decode( $params['dailyActivityGrid'] );
            $params['invoices'] = json_decode( $params['invoices'] );
            $params['deliveryTickets'] = json_decode( $params['deliveryTickets'] );

            $match = 0;

            $this->db->trans_begin();
            /** SAVING FOR INVOICES **/
            $params['invoices'] = (array) $params['invoices'];
            $date = date( 'Y-m-d', strtotime( $params['invoices']['date'] ) ) . " " . date( 'H:i:s', strtotime( date( 'Y-m-d', strtotime( $params['invoices']['date'] ) ) .  $params['invoices']['time'] ) );
            $params['invoices']['date'] = $date;
            $params['invoices']['onEdit'] = $params['onEdit'];

            $idInvoice = $this->model->saveInvoice( $params['invoices'] );

            $params['deliveryTickets'] = (array) $params['deliveryTickets'];
            $params['deliveryTickets']['idInvoice'] = $idInvoice;
            $params['deliveryTickets']['onEdit'] = $params['onEdit'];

            /** SAVING FOR DELIVERY TICKETS **/
            $idDeliveryTicket = $this->model->saveDeliveryTickets( $params['deliveryTickets'] );

            if( $params['deliveryTickets']['deliveryTicketType'] == 1 && !empty( $params['loadActivityGrid'] ) ){
                foreach ($params['loadActivityGrid'] as $key => $value) {
                    $value = (array) $value;

                    $value['idDeliveryTicket']  = $idDeliveryTicket;
                    $value['total']             = $value['totalForLoads'];
                    unset( $value['totalForLoads'] );
                    unset( $value['selected'] );
                    unset( $value['area'] );
                    $params['loadActivityGrid'][$key] = $value;
                }

                $params['loadActivityGrid']['onEdit'] = $params['onEdit'];
                $params['loadActivityGrid']['idDeliveryTicket'] = $idDeliveryTicket;
                $this->model->saveDeliveryTicketActivity( $params['loadActivityGrid'] );
            } 
            if( $params['deliveryTickets']['deliveryTicketType'] == 2 && !empty( $params['dailyActivityGrid'] ) ) {
                foreach ($params['dailyActivityGrid'] as $key => $value) {
                    $value = (array) $value;

                    $value['idDeliveryTicket'] = $idDeliveryTicket;
                    $value['total']             = $value['totalForDays'];
                    unset( $value['totalForDays'] );
                    unset( $value['selected'] );
                    unset( $value['area'] );
                    $params['dailyActivityGrid'][$key] = $value;
                }

                $params['dailyActivityGrid']['onEdit'] = $params['onEdit'];
                $params['dailyActivityGrid']['idDeliveryTicket'] = $idDeliveryTicket;
                $this->model->saveDeliveryTicketActivity( $params['dailyActivityGrid'] );
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
        } else {
            die(
                json_encode(
                    array(
                        'status'    => false,
                        'msg'       => 'You are not authorized to perform this action.'
                    )
                )
            );
        }
    }

    function viewAll(){
        if( validSession() ){
            $params = getData();
            $view = $this->model->viewAll( $params );

            /** DECRYPTING RESULTS **/
            $view = decryptAffiliate( $view );
            $view = decryptCustomer( $view );
            $view = decryptUserData( $view );

            die(
                json_encode(
                    array(
                        'success'   => true
                        ,'view'     => $view
                    )
                )
            );
        } else {
            die(
                json_encode(
                    array(
                        'status'    => false,
                        'msg'       => 'You are not authorized to perform this action.'
                    )
                )
            );
        }
    }

    function viewHistorySearch(){
        if( validSession() ){
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
        } else {
            die(
                json_encode(
                    array(
                        'status'    => false,
                        'msg'       => 'You are not authorized to perform this action.'
                    )
                )
            );
        }
    }

    function retrieveData(){
        if( validSession() ){
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
        } else {
            die(
                json_encode(
                    array(
                        'status'    => false,
                        'msg'       => 'You are not authorized to perform this action.'
                    )
                )
            );
        }
    }

    function getActivity(){
        if( validSession() ){
            $params = getData();
            $view = $this->model->getActivity( $params );

            die(
                json_encode(
                    array(
                        'success'   => true
                        ,'view'     => $view
                    )
                )
            );
        } else {
            die(
                json_encode(
                    array(
                        'status'    => false,
                        'msg'       => 'You are not authorized to perform this action.'
                    )
                )
            );
        }
    }

    function getLocation(){
        if( validSession() ){
            $params = getData();
            $view = $this->model->getLocation( $params );

            die(
                json_encode(
                    array(
                        'success'   => true
                        ,'view'     => $view
                    )
                )
            );
        } else {
            die(
                json_encode(
                    array(
                        'status'    => false,
                        'msg'       => 'You are not authorized to perform this action.'
                    )
                )
            );
        }
    }

    function deleteRecord(){
        if( validSession() ){
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
        } else {
            die(
                json_encode(
                    array(
                        'status'    => false,
                        'msg'       => 'You are not authorized to perform this action.'
                    )
                )
            );
        }
    }

    private function setLogs( $params ){
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
        $params['idModule']             = 70;
		
		setLogs( $params );
    }

    function generatePDF() {
        $data = getData();

        $formDetails = json_decode( $data['form'], true );
        $dailyActivityGridRecords = json_decode( $data['dailyActivityGrid'], true );
        $loadActivityGridRecords = json_decode( $data['loadActivityGrid'], true );

        print_r($formDetails['pdf_deliveryTicketType']);
        
        $header_fields = array(
            array(
                array(
                    'label' => 'Affiliate'
                    ,'value' => $this->AFFILIATENAME
                )
                ,array(
                    'label' => 'Reference'
                    ,'value' => $formDetails['pdf_idReference'] . '-' .$formDetails['pdf_referenceNum']
                )
                ,array(
                    'label' => 'Project Name'
                    ,'value' => $formDetails['pdf_idProject']
                )
                ,array(
                    'label' => "Driver's Name"
                    ,'value' => $formDetails['pdf_idDriver']
                )
                ,array(
                    'label' => "Truck Type"
                    ,'value' => $formDetails['pdf_idTruckType']
                )
                ,array(
                    'label' => "Plate Number"
                    ,'value' => $formDetails['pdf_plateNumber']
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
                    'label' => 'Customer Name'
                    ,'value' => $formDetails['pdf_pCode']
                )
                ,array(
                    'label' => 'Remarks'
                    ,'value' => $formDetails['pdf_remarks']
                )
                ,array(
                    'label' => 'Type'
                    ,'value' => $formDetails['pdf_deliveryTicketType']
                )
                ,array(
                    'label' => 'Odometer'
                    ,'value' => $formDetails['pdf_odometer']
                )
            )
        );

        $dailyActivityGrid = array(
            array(
                'header'        => 'Activity Name'
                ,'dataIndex'    => 'activityName'
                ,'width'        => '12.5'	
            ),
            array(
                'header'        => 'No. of Days'
                ,'dataIndex'    => 'noOfDays'
                ,'width'        => '12.5'
                ,'type'         => 'numbercolumn'
            ),
            array(
                'header'        => 'Description'
                ,'dataIndex'    => 'description'
                ,'width'        => '12.5'
            ),
            array(
                'header'        => 'Area'
                ,'dataIndex'    => 'area'
                ,'width'        => '12.5'
            ),
            array(
                'header'        => 'Fuel Consumed'
                ,'dataIndex'    => 'fuelConsumed'
                ,'width'        => '12.5'
                ,'type'         => 'numbercolumn'
            ),
            array(
                'header'        => 'Lubricant'
                ,'dataIndex'    => 'lubricant'
                ,'width'        => '12.5'
            ),
            array(
                'header'        => 'Rate'
                ,'dataIndex'    => 'rate'
                ,'width'        => '12.5'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
            ),
            array(
                'header'        => 'Amount'
                ,'dataIndex'    => 'totalForDays'
                ,'width'        => '12.5'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
                ,'hasTotal'     => true
            )
        );

        $loadActivityGrid = array(
            array(
                'header'        => 'Activity Name'
                ,'dataIndex'    => 'activityName'
                ,'width'        => '14.2'	
            ),
            array(
                'header'        => 'Area'
                ,'dataIndex'    => 'area'
                ,'width'        => '14.2'
            ),
            array(
                'header'        => 'No. of Loads'
                ,'dataIndex'    => 'noOfLoads'
                ,'width'        => '14.2'
                ,'type'         => 'numbercolumn'
            ),
            array(
                'header'        => 'Fuel Consumed'
                ,'dataIndex'    => 'fuelConsumed'
                ,'width'        => '14.2'
                ,'type'         => 'numbercolumn'
            ),
            array(
                'header'        => 'Lubricant'
                ,'dataIndex'    => 'lubricant'
                ,'width'        => '14.2'
            ),
            array(
                'header'        => 'Rate'
                ,'dataIndex'    => 'rate'
                ,'width'        => '14.2'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
            ),
            array(
                'header'        => 'Total'
                ,'dataIndex'    => 'totalForLoads'
                ,'width'        => '14.2'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
                ,'hasTotal'     => true
            )
        );

        generateTcpdf(
			array(
				'file_name'         => $data['title']
                ,'folder_name'      => 'trucking'
                ,'header_fields'    => $header_fields
                ,'records'          => ( $formDetails['pdf_deliveryTicketType'] == 'Per Day' ) ? $dailyActivityGridRecords : $loadActivityGridRecords
                ,'header'           => ( $formDetails['pdf_deliveryTicketType'] == 'Per Day' ) ? $dailyActivityGrid : $loadActivityGrid
                ,'orientation'      => 'P'
                ,'params'           => $data
                ,'idAffiliate'      => $data['idAffiliate']
			) 
        );
    }

    function customListPDF(){
        $params = getData();

        $table = array(
            array(
                'header'        =>'Affiliate'
                ,'dataIndex'    =>'affiliateName'
                ,'width'        =>'11'
            ),
            array(
                'header'        =>'Date'
                ,'dataIndex'    =>'date'
                ,'width'        =>'11'
            ),
            array(
                'header'        =>'Reference'
                ,'dataIndex'    =>'referenceNum'
                ,'width'        =>'11'
            ),
            array(
                'header'        =>'Project Name'
                ,'dataIndex'    =>'projectName'
                ,'width'        =>'11'
            ),
            array(
                'header'        =>'Driver Name'
                ,'dataIndex'    =>'driverName'
                ,'width'        =>'11'
            ),
            array(
                'header'        =>'Plate Number'
                ,'dataIndex'    =>'plateNumber'
                ,'width'        =>'11'
            ),
            array(
                'header'        =>'Truck Type'
                ,'dataIndex'    =>'truckType'
                ,'width'        =>'11'
            ),
            array(
                'header'        =>'Customer Name'
                ,'dataIndex'    =>'customerName'
                ,'width'        =>'11'
            ),
            array(
                'header'        =>'Total Amount'
                ,'dataIndex'    =>'totalAmount'
                ,'width'        =>'11'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
            )
        );

        generateTcpdf(
			array(
				'file_name'         => 'Delivery Ticket List'
                ,'folder_name'      => 'trucking'
                ,'records'          => json_decode( $params['items'], true )
                ,'header'           => $table
                ,'orientation'      => 'P'
                ,'idAffiliate'      => $this->session->userdata('AFFILIATEID')
			) 
        );
    }

    function printExcel (){
		$data = getData();
        $view = json_decode( $data['items'], true );
        $view = (array) $view;
        
		$csvarray[] = array( 'title' => $data['pageTitle'].'' );
		$csvarray[] = array( 'space' => '' );
		$csvarray[] = array( 'space' => '' );

		$csvarray[] = array(
			'col1'  => 'Affiliate'
            ,'col2' => 'Date'
            ,'col3' => 'Reference'
            ,'col4' => 'Project Name'
            ,'col5' => 'Driver Name'
            ,'col6' => 'Plate Number'
            ,'col7' => 'Truck Type'
            ,'col8' => 'Customer Name'
            ,'col9' => 'Total Amount'
        );

		foreach( $view as $value ){
			$csvarray[] = array(
				'col1' => $value[ 'affiliateName' ]
                ,'col2' => $value[ 'date' ]
                ,'col3' => $value[ 'referenceNum' ]
                ,'col4' => $value[ 'projectName' ]
                ,'col5' => $value[ 'driverName' ]
                ,'col6' => $value[ 'plateNumber' ]
                ,'col7' => $value[ 'truckType' ]
                ,'col8' => $value[ 'customerName' ]
                ,'col9' => $value[ 'totalAmount' ]
			);
        }

		$data['description'] = '' .$data['pageTitle']. ": " .$this->USERNAME. ' printed an Excel report'  ;
		$data['iduser'] = $this->USERID;
		$data['usertype'] = $this->USERTYPEID;
		$data['printExcel'] = true;	
        $data['ident'] = null;

		writeCsvFile(
			array(
				'csvarray' 	 => $csvarray
				,'title' 	 => $data['pageTitle']
				,'directory' => 'trucking'
			)
		);
		
    }
    
    function download($title){
		force_download(
			array(
				'title' => $title
				,'directory' => 'trucking'
			)
		);
    }
}