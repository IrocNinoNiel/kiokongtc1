<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Trucktype extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        middleware();
        $this->load->model('Home_model');
        $this->load->library('encryption');
        setHeader('trucking/Trucktype_model');
    }

    public function getTruckTypeItems() 
    {
        $params = getData();
        $view   = $this->model->getTruckTypeItems($params);
        die(
            json_encode(
                array(
                    'success'   => true
                    , 'view'    => $view,
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
                    'success'   => true
                    , 'view'    => $view,
                )
            )
        );
    }

    public function getTruckType()
    {
        $params = getData();
        $view   = $this->model->getTruckType($params);
        die(
            json_encode(
                array(
                    'success'   => true
                    , 'view'    => $view,
                )
            )
        );
    }

    public function saveTruckType()
    {
        $match  = 0;
        $params = getData();

        $view   = $this->model->saveTruckType($params);

        $msg = ($params['isEdit']) ? ' edited the truck type details.' : ' added a new truck type.';

        setLogs(
            array(
                'actionLogDescription' => $this->USERNAME . $msg
                ,'idAffiliate'         => ( isset($params['idAffiliate']) && !empty( $params['idAffiliate'] ) ) ? $params['idAffiliate'] : $this->AFFILIATEID
                ,'idEu'                => $this->USERID
                ,'idModule'            => $params['idModule']
                ,'time'                => date("H:i:s A"),
            )
        );

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
 
    public function deleteTruckType()
    {
        $params = getData();
        
        $match = $this->model->deleteTruckType($params);

        if(!$match) {
            setLogs(
                array(
                    'actionLogDescription' => $this->USERNAME . ' deleted a truck type.'
                    ,'idAffiliate'         => ( isset($params['idAffiliate']) && !empty( $params['idAffiliate'] ) ) ? $params['idAffiliate'] : $this->AFFILIATEID
                    ,'idEu'                => $this->USERID
                    ,'idModule'            => $params['idModule']
                    ,'time'                => date("H:i:s A")
                )
            );  
        }    

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'match'    => $match
                )
            )
        );
    }

    function printPDF(){
        $params     = getData();
        $data       = $this->model->getTruckTypeItems( $params );

        $col = array(
            array(   
                'header'        => 'Code'
                ,'dataIndex'    => 'idTruckType'
                ,'width'        => '20%'
            ),
            array(   
                'header'        => 'Truck Type'
                ,'dataIndex'    => 'truckType'
                ,'width'        => '80%'
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
                'actionLogDescription' => 'Exported the generated Truck Type Report (PDF).'
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
        $data   = $this->model->getTruckTypeItems( $params );
        
        $csvarray = array();

        $csvarray[] = array( 'title' => $params['title'] );
        $csvarray[] = array( 'space' => '' );

        $csvarray[] = array( 'Date', date("Y-m-d")  );
        $csvarray[] = array( 'space' => '' );

        $csvarray[] = array(
            'Code'
            ,'Truck Type'
        );

        foreach( $data as $d ){
            $csvarray[] = array(
                $d['idTruckType']
                ,$d['truckType']
            );
        }

        setLogs(
            array(
                'actionLogDescription' => 'Exported the generated Truck Type Report (Excel).'
			    ,'idAffiliate'		   => $this->session->userdata('AFFILIATEID')
                ,'idEu'                => $this->USERID
                ,'idModule'            => $params['idModule']
                ,'time'                => date('H:i:s A')
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