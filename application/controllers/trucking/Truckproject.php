<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Truckproject extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        middleware();
        $this->load->library('encryption');
        setHeader('trucking/Truckproject_model');
    }

    public function getTruckProjects() { 
        $params = getData();
        $view = $this->model->getTruckProjects($params);

        $view   = decryptCustomer( $view );
 
        die(
            json_encode(
                array(
                    'success' => true
                    , 'view' => $view,
                )
            )
        );
    }

    public function getTruckProject() {
        $params = getData();
        $view = $this->model->getTruckProject($params);

        $view   = decryptCustomer( $view );
 
        die(
            json_encode(
                array(
                    'success' => true
                    , 'view' => $view,
                )
            )
        );
    }

    public function getCustomers() {
        $params = getData();
        $view = $this->model->getCustomers($params);

        $view   = decryptCustomer( $view );
        
        if( isset($params['hasAll']) && $params['hasAll'] == 1 ){
            array_unshift( $view, array(
                'id' => 0
                ,'name' => 'All'
            ));
        }

        die(
            json_encode(
                array(
                    'success' => true
                    , 'view' => $view,
                )
            )
        );
    }

    public function getCode()
    {
        $view = $this->model->getCode(); 
        die(
            json_encode(
                array(
                    'success' => true
                    , 'view' => $view,
                )
            )
        );
    }

    public function saveTruckProject() {
        $params = getData();
        
        $data['affiliates'] = $params['affiliates'];
        unset( $params['affiliates'] );
        $match = 0;

        if( $params['isManual'] ){
            $isExist = $this->model->is_id_exists($params);

            if( $isExist ) {
                if(  $isExist[0]['idTruckProject'] != $params['idTruckProject']) {
                    $match = 1;
                    die(
                        json_encode(
                            array(
                                'success' => true
                                ,'match' => $match
                            )
                        )
                    );
                }            
            }
        }

        $view = $this->model->saveTruckProject( $params );

        $affiliates = [];
        $id = $view;

        foreach( json_decode($data['affiliates']) as $value ){
            array_push( $affiliates, array(
                'idTruckProject' => $id
                ,'idAffiliate' => $value
            ));
        }

        $this->model->saveTruckProjectAffiliate( $affiliates );

        $msg = (!$params['onEdit']) ? ' edited the truck project details.' : ' added a new truck project.';

        setLogs(
            array(
                'actionLogDescription' => $this->USERNAME . $msg
                , 'idEu' => $this->USERID
                , 'moduleID' => 73
                , 'time' => date("H:i:s A"),
            )
        );

        die(
            json_encode(
                array(
                    'success' => true
                    ,'view' => $view
                    ,'match' => $match
                )
            )
        ); 
    }

    public function deleteTruckProject() {
        $params = getData();

        $match = $this->model->deleteTruckProject($params);

        setLogs(
            array(
                'actionLogDescription' => $this->USERNAME . ' deleted a truck project'
                ,'idEu'                => $this->USERID
                ,'moduleID'            => 73
                ,'time'                => date("H:i:s A")
            )
        );

        die(
			json_encode(
				array(
					'success' => true
                    ,'match' => $match 
				)
			)
        );
    }

    function printPDF(){
        $params     = getData();
        $data       = $this->model->getTruckProjects( $params );
        $data       = decryptCustomer( $data );
        
        $col = array(
            array(   
                'header'        => 'Project ID'
                ,'dataIndex'    => 'idProject'
                ,'width'        => '20%'
            ),
            array(   
                'header'        => 'Project Name'
                ,'dataIndex'    => 'projectName'
                ,'width'        => '30%'
            ),
            array(   
                'header'        => 'Customer'
                ,'dataIndex'    => 'name'
                ,'width'        => '30%'
            ),
            array(   
                'header'        => 'Remarks'
                ,'dataIndex'    => 'remarks'
                ,'width'        => '20%'
            ),
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
                'actionLogDescription' => 'Exported the generated Truck Project Report (PDF)'
                ,'idAffiliate'         => ( isset($params['idAffiliate']) && !empty( $params['idAffiliate'] ) ) ? $params['idAffiliate'] : $this->AFFILIATEID
                ,'idEu'                => $this->USERID
                ,'moduleID'            => 73
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
        $data   = $this->model->getTruckProjects( $params );
        $data   = decryptCustomer( $data );

        $csvarray = array();

        $csvarray[] = array( 'title' => $params['title'] );
        $csvarray[] = array( 'space' => '' );

        $csvarray[] = array( 'Date', date("Y-m-d")  );
        $csvarray[] = array( 'space' => '' );

        $csvarray[] = array(
            'Project ID',
            'Project Name',
            'Customer',
            'Remarks'
        );

        foreach( $data as $d ){
            $csvarray[] = array(
                $d['idTruckProject']
                ,$d['projectName']
                ,$d['name']
                ,$d['remarks']
            );
        }

        setLogs(
            array(
               'actionLogDescription'   => 'Exported the generated Truck Project Report (Excel).'
			,'idAffiliate'			    => $this->session->userdata('AFFILIATEID')
            ,'idEu'                     => $this->USERID
               ,'moduleID'              => 73
               ,'time'                  => date('H:i:s A')
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