<?php if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

class Truckmonitoring extends CI_Controller{

    public function __construct(){
        parent::__construct();
        middleware();
        $this->load->library('encryption');
        setHeader('trucking/Truckmonitoring_model');
    }

    public function getActivity() {
        $params = getData();

        $view   = $this->model->getActivity($params);

        /** DECRYPTING RESULTS **/
        $view = decryptAffiliate( $view );
        $view = decryptUserData( $view );

        die(
            json_encode(
                array(
                     'success' => true
                    ,'view'    => $view
                )
            )
        );
    }

    public function getTruckTypes() {
        $params = getData();
        $view   = $this->model->getTruckTypes($params);
        
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

    public function getPlateNumbers() {
        $params = getData();
        $view   = $this->model->getPlateNumbers($params);

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

    public function getDrivers() {
        $params = getData();
        $view   = $this->model->getDrivers($params);
        $view = decryptUserData( $view );

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
        $data   = $this->model->getActivity( $params );

        $data   = decryptAffiliate( $data );
        $data   = decryptUserData( $data );

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
                'header'        => 'Plate Number'
                ,'dataIndex'    => 'plateNumber'
                ,'width'        => '10%'
            ),
            array(   
                'header'        => 'Truck Type'
                ,'dataIndex'    => 'truckType'
                ,'width'        => '10%'
            ),
            array(   
                'header'        => 'Driver'
                ,'dataIndex'    => 'driverName'
                ,'width'        => '15%'
            ),
            array(   
                'header'        => 'Project Name'
                ,'dataIndex'    => 'projectName'
                ,'width'        => '15%'
            ),
            array(   
                'header'        => 'Activity'
                ,'dataIndex'    => 'activity'
                ,'width'        => '15%'
            ),
            array(   
                'header'        => 'Odometer'
                ,'dataIndex'    => 'odometer'
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
                    'label'     => 'Truck Type'
                    ,'value'    => $params['pdf_truckType']
                ),
                array(
                    'label'     => 'Project Type'
                    ,'value'    => $params['pdf_projectType']
                ),
                array(
                    'label'     => 'Project Name'
                    ,'value'    => $params['pdf_projectName']
                ),
                array(
                    'label'     => 'Plate Number'
                    ,'value'    => $params['pdf_plateNumber']
                ),
                array(
                    'label'     => 'Driver'
                    ,'value'    => $params['pdf_driversName']
                ),
                array(
                    'label'     => 'Date'
                    ,'value'    => $params['sdate'] . ' to ' . $params['edate']
                )
            )
        );

        setLogs(
            array(
                'actionLogDescription' => 'Exported the generated Truck Monitoring Report (PDF)'
                ,'idAffiliate'         => ( isset($params['idAffiliate']) && !empty( $params['idAffiliate'] ) ) ? $params['idAffiliate'] : $this->AFFILIATEID
                ,'idEu'                => $this->USERID
                ,'idModule'            => $params['idModule']
                ,'time'                => date("H:i:s A")
            )
        );

        generateTcpdf(
            array(
                'file_name'      => $params['title']
                ,'folder_name'   => 'trucking'
                ,'records'       => $data
                ,'header'        => $col
                ,'orientation'   => 'P'
                ,'header_fields' => $header_fields
            )
        );
    }

    public function printExcel() {
        $params = getData();

        $data   = $this->model->getActivity( $params );

        $data = decryptAffiliate( $data );
        $data = decryptUserData( $data );

        $csvarray = array();

        $csvarray[] = array( 'title'         => $params['title'] );
        $csvarray[] = array( 'Affiliate',    $params['pdf_idAffiliate']  );
        $csvarray[] = array( 'Truck Type',   $params['pdf_truckType']  );
        $csvarray[] = array( 'Project Type', $params['pdf_projectType']  );
        $csvarray[] = array( 'Project Name', $params['pdf_projectName']  );
        $csvarray[] = array( 'Plate Number', $params['pdf_plateNumber']  );
        $csvarray[] = array( 'Driver',       $params['pdf_driversName']  );
        $csvarray[] = array( 'Date',         $params['sdate'] . ' to ' . $params['edate']  );
        $csvarray[] = array( 'space'         => '' );

        $csvarray[] = array(
            'Affiliate'
            ,'Date'
            ,'Plate Number'
            ,'Truck Type'
            ,'Driver'
            ,'Project Name'
            ,'Activity'
            ,'Odometer'
        );

        foreach( $data as $d ){
            $csvarray[] = array(
                $d['affiliateName']
                ,$d['date']
                ,$d['plateNumber']
                ,$d['truckType']
                ,$d['driverName']
                ,$d['projectName']
                ,$d['activity']
                ,$d['odometer']
            );
        }

        setLogs(
            array(
                'actionLogDescription'  => 'Exported the generated Truck Monitoring Report (Excel).'
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
                ,'directory' => 'trucking'
            )
        );
    }

    function download($title){
		force_download(
			array(
				'title'         => $title
				,'directory'    => 'trucking'
			)
		);
	}
}
