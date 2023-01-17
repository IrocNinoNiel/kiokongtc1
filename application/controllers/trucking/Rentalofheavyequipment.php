<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rentalofheavyequipment extends CI_Controller {

    public function __construct(){
        parent::__construct();
        middleware();
        $this->load->library('encryption');
        setHeader( 'trucking/Rentalofheavyequipment_model' );
    }

    function getConstructionProjects(){
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
    }

    function getDrivers(){
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
    }

    function getTruckTypes(){
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
    }

    function getCustomers(){
        $params = getData();
        $view   = $this->model->getCustomers( $params );
        $view	= decryptCustomer( $view );

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
    }

    function getSuppliers(){
        $params = getData();
        $view   = $this->model->getSuppliers( $params );
        $view	= decryptSupplier( $view );

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

    function getProjects(){
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
    }

    function getPlateNumber(){
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
    }

    function getReferences(){
        $params = getData();
        $view = $this->model->getReferences( $params );

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

        /** DECRYPTING RESULTS **/
        $view = decryptItem( $view );
        // $view = decryptCustomer( $view );
        // $view = decryptUserData( $view );

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    function getJournalEntries(){
        $params = getData();
        $view = $this->model->getJournalEntries( $params );

        /** DECRYPTING RESULTS **/
        // $view = decryptItem( $view );
        // $view = decryptCustomer( $view );
        // $view = decryptUserData( $view );

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
        $params['otherDeductions']  = json_decode( $params['otherDeductions'] );
        $params['gridJournalEntry'] = json_decode( $params['gridJournalEntry'] );
        $params['invoices']         = json_decode( $params['invoices'] );
        $params['rental']           = json_decode( $params['rental'] );

        $match = 0;

        $this->db->trans_begin();
        /** SAVING FOR INVOICES **/
        $params['invoices']             = (array) $params['invoices'];
        $date                           = date( 'Y-m-d', strtotime( $params['invoices']['date'] ) ) . " " . date( 'H:i:s', strtotime( date( 'Y-m-d', strtotime( $params['invoices']['date'] ) ) .  $params['invoices']['time'] ) );
        $params['invoices']['date']     = $date;
        $params['invoices']['onEdit']   = $params['onEdit'];

        $idInvoice = $this->model->saveInvoice( $params['invoices'] );

        /** SAVING FOR RENTAL **/
        $params['rental']                       = (array) $params['rental'];
        $params['rental']['onEdit']             = $params['onEdit'];
        $params['rental']['idInvoice']          = $idInvoice;
        $params['rental']['striker']            = ( $params['rental']['striker'] == true ) ? 1 : 0;
        $params['rental']['isConstruction']     = ( $params['rental']['isConstruction'] == true ) ? 1 : 0;
        $params['rental']['idEu']               = $this->USERID;
        $params['rental']['dateFrom']           = date( 'Y-m-d', strtotime( $params['rental']['dateFrom'] ) );
        $params['rental']['dateTo']             = date( 'Y-m-d', strtotime( $params['rental']['dateTo'] ) );

        $idRental   = $this->model->saveRental( $params['rental'] );
        
        if( count( $params['otherDeductions'] ) > 0 ){
            foreach ($params['otherDeductions'] as $key => $value) {
                $value = (array) $value;

                $value['idRental'] = $idRental;
                unset( $value['selected'] );
                unset( $value['itemName'] );
                unset( $value['refNum'] );
                $params['otherDeductions'][$key] = $value;
            }

            $params['otherDeductions']['onEdit'] = $params['onEdit'];
            $params['otherDeductions']['idRental'] = $idRental;

            $this->model->saveRentalDeduction( $params['otherDeductions'] );
        }

        if( count( $params['gridJournalEntry'] ) > 0 ){
            foreach ($params['gridJournalEntry'] as $key => $value) {
                $value = (array) $value;

                $value['idInvoice'] = $idInvoice;
                unset( $value['selected'] );
                unset( $value['code'] );
                unset( $value['costcenterName'] );
                unset( $value['name'] );
                $params['gridJournalEntry'][$key] = $value;
            }

            $params['gridJournalEntry']['onEdit'] = $params['onEdit'];
            $params['gridJournalEntry']['idInvoice'] = $idInvoice;

            $this->model->saveJournalEntries( $params['gridJournalEntry'] );
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

    function getReturnDetails(){
        $params = getData();
        $view   = $this->model->getReturnDetails( $params );

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    function saveReturn(){
        $params = getData();
        $params = json_decode( $params['rental'] );
        $view   = $this->model->saveReturn( $params );

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'match'     => !empty( $view ) ? 0 : 1
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
        $params['idModule']             = 71;
		
		setLogs( $params );
    }

    function generatePDF() {
        $data               = getData();
        $other_deductions   = $this->model->getOtherDeductions($data);
        $other_deductions   = decryptItem( $other_deductions );
        $formDetails        = json_decode( $data['form'], true );
        $journalEntries = json_decode( $data['journalEntries'], true );
        
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
                    'label' => $formDetails['pdf_striker']? 'Supplier Name' : 'Customer Name'
                    ,'value' => $formDetails['pdf_striker']? $formDetails['pdf_idSupplier'] : $formDetails['pdf_idCustomer']
                )
                ,array(
                    'label' => 'Contact Number'
                    ,'value' => $formDetails['pdf_contactNumber']
                )
                ,array(
                    'label' => 'Address'
                    ,'value' => $formDetails['pdf_address']
                )
                ,array(
                    'label' => 'Project Name'
                    ,'value' => $formDetails['pdf_idProject']
                )
                ,array(
                    'label' => 'Remarks'
                    ,'value' => $formDetails['pdf_remarks']
                )
                ,array(
                    'label' => 'Driver\'s Name'
                    ,'value' => $formDetails['pdf_idDriver']
                )
                ,array(
                    'label' => 'Rental Date'
                    ,'value' => $formDetails['pdf_sdate'] . ' to ' . $formDetails['pdf_edate']
                )
                ,array(
                    'label' => 'Truck Type'
                    ,'value' => $formDetails['pdf_idTruckType']
                )
                ,array(
                    'label' => 'Plate Number'
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
                    'label' => 'Model'
                    ,'value' => $formDetails['pdf_model']
                )
                ,array(
                    'label' => 'Status'
                    ,'value' => $formDetails['pdf_status']
                )
                ,array(
                    'label' => 'Rate Type'
                    ,'value' => $formDetails['pdf_idRateType']
                )
                ,array(
                    'label' => 'Rate'
                    ,'value' => $formDetails['pdf_rate']
                )
                ,array(
                    'label' => 'Hours'
                    ,'value' => $formDetails['pdf_rateType']
                )
                ,array(
                    'label' => 'Total Rate'
                    ,'value' => $formDetails['pdf_totalRate']
                )
                ,array(
                    'label' => 'Mileage'
                    ,'value' => $formDetails['pdf_mileage']
                )
                ,array(
                    'label' => 'Fuel Level'
                    ,'value' => $formDetails['pdf_fuelLevel']
                )
                ,array(
                    'label' => 'Fuel Usage'
                    ,'value' => $formDetails['pdf_fuelUsage']
                )
            )
        );

        $header_col = array(
            array(
                'header'        => 'Refence Number'
                ,'dataIndex'    => 'refNum'
                ,'width'        => '20'
            )
            ,array(
                'header'        => 'Item Name'
                ,'dataIndex'    => 'itemName'
                ,'width'        => '25'
            )
            ,array(
                'header'        => 'Description'
                ,'dataIndex'    => 'description'
                ,'width'        => '25'
            )
            ,array(
                'header'        => 'Quantity'
                ,'dataIndex'    => 'qty'
                ,'type'         => 'numbercolumn'
                ,'width'        => '10'
            )
            ,array(
                'header'        => 'Price'
                ,'dataIndex'    => 'price'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
                ,'width'        => '10'
            )
            ,array(
                'header'        => 'Amount'
                ,'dataIndex'    => 'amount'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
                ,'width'        => '10'
            )
        );

        generateTcpdf(
			array(
				'file_name'         => $data['title']
                ,'folder_name'      => 'trucking'
                ,'header_fields'    => $header_fields
                ,'header'           => $header_col
                ,'records'          => $other_deductions
                ,'orientation'      => 'P'
                ,'params'           => $data
                ,'idAffiliate'      => $data['idAffiliate']
                ,'journalEntry'     => $journalEntries
                ,'hasPrintOption'   => $data['hasPrintOption']
			) 
        );

    }

    function customListPDF(){
        $params = getData();

        $table = array(
            array(
                'header'        =>'Date'
                ,'dataIndex'    =>'date'
                ,'width'        =>'12.5'
            ),
            array(
                'header'        =>'Reference'
                ,'dataIndex'    =>'referenceNum'
                ,'width'        =>'12.5'
            ),
            array(
                'header'        =>'Project Name'
                ,'dataIndex'    =>'projectName'
                ,'width'        =>'12.5'
            ),
            array(
                'header'        =>'Customer Name'
                ,'dataIndex'    =>'customerName'
                ,'width'        =>'12.5'
            ),
            array(
                'header'        =>'Contact Number'
                ,'dataIndex'    =>'customerContactNumber'
                ,'width'        =>'12.5'
            ),
            array(
                'header'        =>'Plate Number'
                ,'dataIndex'    =>'plateNumber'
                ,'width'        =>'12.5'
            ),
            array(
                'header'        =>'Driver Name'
                ,'dataIndex'    =>'driverName'
                ,'width'        =>'12.5'
            ),
            array(
                'header'        =>'Status'
                ,'dataIndex'    =>'status'
                ,'width'        =>'12.5'
            )
        );

        // $data['action'] = " Rental of Heavy Equipment List: " .$this->USERNAME. ' printed a PDF report.';
        // $this->setLogs( $data );

        generateTcpdf(
			array(
				'file_name'         => 'Rental of Heavy Equipment List'
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
			'col1'  => 'Date'
            ,'col2' => 'Reference'
            ,'col3' => 'Project Name'
            ,'col4' => 'Customer Name'
            ,'col5' => 'Contact Number'
            ,'col6' => 'Plate Number'
            ,'col7' => 'Driver Name'
            ,'col8' => 'Status'
        );

		foreach( $view as $value ){
			$csvarray[] = array(
				'col1' => $value[ 'date' ]
                ,'col2' => $value[ 'referenceNum' ]
                ,'col3' => $value[ 'projectName' ]
                ,'col4' => $value[ 'customerName' ]
                ,'col5' => $value[ 'customerContactNumber' ]
                ,'col6' => $value[ 'plateNumber' ]
                ,'col7' => $value[ 'driverName' ]
                ,'col8' => $value[ 'status' ]
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