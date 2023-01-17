<?php if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

class Driverperformancesummary extends CI_Controller{

    public function __construct(){
        parent::__construct();
        middleware();
        $this->load->library('encryption');
        setHeader('trucking/Driverperformancesummary_model');
    }

    public function getPerformance() {
        $params = getData();
        $view   = $this->model->getPerformance($params);

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
        $data   = $this->model->getPerformance( $params );

        $col    = array(
            array(   
                'header'        => 'Date'
                ,'dataIndex'    => 'date'
                ,'width'        => '15%'
            ),
            array(   
                'header'        => 'Destination'
                ,'dataIndex'    => 'area'
                ,'width'        => '25%'
            ),
            array(   
                'header'        => 'No. of Trips'
                ,'dataIndex'    => 'trip'
                ,'width'        => '15%'
                ,'xtype'        => 'numbercolumn'
                ,'hasTotal'     => true
            ),
            array(   
                'header'        => 'No. of Loads'
                ,'dataIndex'    => 'noOfLoads'
                ,'width'        => '15%'
                ,'xtype'        => 'numbercolumn'
                ,'hasTotal'     => true
            ),
            array(   
                'header'        => 'Fuel Consumed'
                ,'dataIndex'    => 'fuelConsumed'
                ,'width'        => '15%'
                ,'xtype'        => 'numbercolumn'
                ,'hasTotal'     => true
            ),
            array(   
                'header'        => 'Lubricant'
                ,'dataIndex'    => 'lubricant'
                ,'width'        => '15%'
                ,'xtype'        => 'numbercolumn'
                ,'hasTotal'     => true
            ),
        );

        $header_fields = array(
            array(
                array(
                    'label'     => 'Affiliate'
                    ,'value'    => $params['pdf_idAffiliate']
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
                    'label'     => 'Truck Type'
                    ,'value'    => $params['pdf_truckType']
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
                'actionLogDescription' => 'Exported the generated Driver Performance Summary Report (PDF)'
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

        $data   = $this->model->getPerformance( $params );

        $csvarray = array();

        $csvarray[] = array( 'title'         => $params['title'] );
        $csvarray[] = array( 'Affiliate',    $params['pdf_idAffiliate']  );
        $csvarray[] = array( 'Project Type', $params['pdf_projectType']  );
        $csvarray[] = array( 'Project Name', $params['pdf_projectName']  );
        $csvarray[] = array( 'Truck Type',   $params['pdf_truckType']  );
        $csvarray[] = array( 'Plate Number', $params['pdf_plateNumber']  );
        $csvarray[] = array( 'Driver',       $params['pdf_driversName']  );
        $csvarray[] = array( 'Date',         $params['sdate'] . ' to ' . $params['edate']  );
        $csvarray[] = array( 'space'         => '' );

        $csvarray[] = array(
            'Date'
            ,'Destination'
            ,'No. of Trips'
            ,'No. of Loads'
            ,'Fuel Consumed'
            ,'Lubricants'
        );
 
        foreach( $data as $d ){
            $csvarray[] = array(
                $d['date']
                ,$d['area']
                ,$d['trip']
                ,$d['noOfLoads']
                ,$d['fuelConsumed']
                ,$d['lubricant']
            );
        }

        setLogs(
            array(
                'actionLogDescription'  => 'Exported the generated Driver Performance Summary Report (Excel).'
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
