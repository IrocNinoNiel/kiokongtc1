<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deliveryticketmonitoring extends CI_Controller {

    public function __construct(){
        parent::__construct();
        middleware();
        $this->load->library('encryption');
        setHeader( 'trucking/Deliveryticketmonitoring_model' );
    }

    function getHistory(){
        $params = getData();
        $view   = $this->model->getHistory( $params );
        $view   = decryptCustomer( $view );

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                    ,'total'    => count($view)
                )
            )
        );
    }

    function getTruckTypes(){
        $params = getData();
        $view   = $this->model->getTruckTypes( $params );

        array_unshift( $view, array(
            'id' => 0
            ,'name' => 'All'
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

    function getCustomers(){
        $params = getData();
        $view   = $this->model->getCustomers( $params );
        $view	= decryptSupplier( $view );

        array_unshift( $view, 
            array(
                'id' => -1
                ,'name' => 'All'
            )
            ,array(
                'id' => 0
                ,'name' => 'WALK-IN'
            )
        );


        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    function getLocation(){
        $params = getData();
        $view   = $this->model->getLocation( $params );
        // $view	= decryptSupplier( $view );

        array_unshift( $view, 
            array(
                'id' => 0
                ,'name' => 'All'
            )
        );


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
        $view = ( $params['isConstruction'] === "true" || $params['isConstruction'] == 1 ) ? $this->model->getConstructionProjects() : $this->model->getTruckProjects();

        array_unshift(
            $view
            ,array(
                'id'    => 0
                ,'name' => 'All'
            )
        );

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

        array_unshift( $view, array(
            'id' => 0
            ,'name' => 'All'
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

    public function printPDF(){
		$data           = getData();
		$data['pdf']    = true;
        $list           = $this->model->getHistory( $data );
        $list           = decryptCustomer( $list );
		
		$params1 = array(
			array(  
				'header' 		=> 'Date'	
				,'dataIndex' 	=> 'date'		
				,'type'         => 'datecolumn'
                ,'format'       => 'm/d/Y'	
				,'width' 		=> '10%'
			)
			,array(
				'header' 		=> 'Reference No.'		
				,'dataIndex' 	=> 'referenceNum'		
				,'width' 		=> '10%'
			)
			,array(
				'header' 		=> 'Plate Number'		
				,'dataIndex' 	=> 'plateNumber'		
				,'width' 		=> '10%'
			)
            ,array(
				'header' 		=> 'Truck Type'		
				,'dataIndex' 	=> 'truckType'		
				,'width' 		=> '10%'
			)
            ,array(
				'header' 		=> 'Area Source'		
				,'dataIndex' 	=> 'areaSource'		
				,'width' 		=> '10%'
			)
            ,array(
				'header' 		=> 'Customer Name'		
				,'dataIndex' 	=> 'customerName'		
				,'width' 		=> '10%'
			)
            ,array(
				'header' 		=> 'Number'		
				,'dataIndex' 	=> 'number'		
				,'width' 		=> '10%'
			)
            ,array(
				'header' 		=> 'Type'		
				,'dataIndex' 	=> 'deliveryType'		
				,'width' 		=> '10%'
			)
			,array(
				'header' 		=> 'Rate'		
				,'dataIndex' 	=> 'rate'		
				,'width' 		=> '10%'
				,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
                ,'hasTotal'     => true
			)
			,array(
				'header' 		=> 'Total'		
				,'dataIndex' 	=> 'total'		
				,'width' 		=> '10%'
				,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
                ,'hasTotal'     => true
			)
		);
		
		$header_fields = array(
			array(
				array(
					'label' => 'Affiliate'
					,'value' => $data['pdf_idAffiliate']
				)
				,array(
					'label' => 'Project Type'
					,'value' => $data['pdf_isConstruction']
				)
                ,array(
					'label' => 'Project Name'
					,'value' => $data['pdf_idProject']
				)
                ,array(
					'label' => 'Type'
					,'value' => $data['pdf_deliveryTicketType']
				)
                ,array(
					'label' => 'Truck Type'
					,'value' => $data['pdf_idTruckType']
				)
                ,array(
					'label' => 'Plate Number'
					,'value' => $data['pdf_plateNumber']
				)
			)
			,array(
				array(
					'label' => 'Customer Name'
					,'value' => $data['pdf_pCode']
				)
				,array(
					'label' => 'Area Source'
					,'value' => $data['pdf_idLocation']
				)
                ,array(
					'label' => 'Date'
					,'value' => $data['pdf_sdate'] . ' to ' . $data['pdf_edate']
				)
			)
		);
		
		$html = 'Total';

		setLogs(
            array(
                'actionLogDescription'  => 'Exported the generated delivery ticket monitoring (PDF)'
                ,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
                ,'idEu'                 => $this->USERID
                ,'moduleID'             => 50
                ,'time'                 => date("H:i:s A")
                )
        );
		
		generateTcpdf(
			array(
				'file_name'         => $data['title']
				,'folder_name'      => 'trucking'
				,'records'          => $list
				,'header'           => $params1
				,'orientation'      => 'P'
				,'header_fields'    => $header_fields
			)
		);
	}

    function printExcel(){
        $params         = getData();
		$data           = $this->model->getHistory( $params );
        $data           = decryptCustomer( $data );

        $csvarray = array();

        $csvarray[] = array( 'title' => $params['title'] );
        $csvarray[] = array( 'space' => '' );

        $csvarray[] = array( 'Affiliate', $params['pdf_idAffiliate'] );
        $csvarray[] = array( 'Project Type', $params['pdf_isConstruction'] );
        $csvarray[] = array( 'Project Name', $params['pdf_idProject'] );
        $csvarray[] = array( 'Type', $params['pdf_deliveryTicketType'] );
        $csvarray[] = array( 'Truck Type', $params['pdf_idTruckType'] );
        $csvarray[] = array( 'Plate Number', $params['pdf_plateNumber'] );
        $csvarray[] = array( 'Customer Name', $params['pdf_pCode'] );
        $csvarray[] = array( 'Area Source', $params['pdf_idLocation'] );
        $csvarray[] = array( 'Date From', $params['pdf_sdate'] );
        $csvarray[] = array( 'Date To', $params['pdf_edate'] );
        $csvarray[] = array( 'space' => '' );

        $csvarray[] = array(
            'Date'
            ,'Reference No.'
			,'Plate Number'
			,'Truck Type'
            ,'Area Source'
            ,'Customer Name'
            ,'Number'
            ,'Type'
            ,'Rate'
            ,'Total'
        );

        foreach( $data as $d ){
            $csvarray[] = array(
                $d['date']
                ,$d['referenceNum']
                ,$d['plateNumber']
                ,$d['truckType']
                ,$d['areaSource']
                ,$d['customerName']
                ,$d['number']
                ,$d['deliveryType']
                ,number_format( $d['rate'], 2 )
                ,number_format( $d['total'], 2 )
            );
        }

        $csvarray[] = array(
            ''
            ,'' 
            ,''
            ,''
            ,''
            ,''
            ,''
            ,''
            ,number_format( array_sum( array_column( $data, 'amount') ), 2 ) 
            ,number_format( array_sum( array_column( $data, 'balance') ), 2 ) 
        );

        setLogs(
            array(
                'actionLogDescription'  => 'Exported the generated delivery ticket monitoring (Excel)'
                ,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
                ,'idEu'                 => $this->USERID
                    ,'moduleID'         => 50
                    ,'time'             => date("H:i:s A")
                )
        );

        writeCsvFile(
            array(
                'csvarray' 	 => $csvarray
                ,'title' 	 => $params['title']
                ,'directory' => 'trucking'
            )
        );
    }

    function download( $title, $folder ){
        force_download(
            array(
                'title'      => $title
                ,'directory' => $folder
            )
        );
    }
}