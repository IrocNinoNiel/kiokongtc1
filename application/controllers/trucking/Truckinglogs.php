<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

class Truckinglogs extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        middleware();
        $this->load->library('encryption');
        setHeader('trucking/Truckinglogs_model');
    }

    public function getUsers() {
        $params = getData();

        $view   = $this->model->getUsers($params);

        /** DECRYPTING RESULTS **/
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

    public function getHistory() {
        $params = getData();

        $params['idModules'] = $this->getTruckIdModules();
        $view                = $this->model->getHistory($params); 

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

    function getTruckIdModules() {
        $id = [];

        $idModules = $this->model->getIdTruckModules();
        foreach ($idModules as $idModule) {
            $id[] = (int) $idModule['idModule'];
        } 
        return $id;
    }

    function printPDF(){
        $params = getData();
       
        $params['idModules'] = $this->getTruckIdModules();
        
        $data   = $this->model->getHistory($params);

        $data   = decryptAffiliate( $data );
        $data   = decryptUserData( $data );

        $col = array(
            array(   
                'header'        => 'Date'
                ,'dataIndex'    => 'datelog'
                ,'width'        => '8%'
            ),
            array(   
                'header'        => 'Time'
                ,'dataIndex'    => 'time'
                ,'width'        => '8%'
            ),
            array(   
                'header'        => 'Affiliate'
                ,'dataIndex'    => 'affiliateName'
                ,'width'        => '12%'
            ),
            array(   
                'header'        => 'User Full Name'
                ,'dataIndex'    => 'fullName'
                ,'width'        => '12%'
            ),
            array(   
                'header'        => 'User Name'
                ,'dataIndex'    => 'euName'
                ,'width'        => '10%'
            ),
            array(   
                'header'        => 'User Type'
                ,'dataIndex'    => 'euTypeName'
                ,'width'        => '10%'
            )
            ,            array(   
                'header'        => 'Ref'
                ,'dataIndex'    => 'code'
                ,'width'        => '5%'
            ),
            array(   
                'header'        => 'Number'
                ,'dataIndex'    => 'referenceNum'
                ,'width'        => '10%'
            ),
            array(   
                'header'        => 'Description'
                ,'dataIndex'    => 'actionLogDescription'
                ,'width'        => '25%'
            )
            
        );

        $header_fields = array(
            array(
                array(
                    'label'     => 'Affiliate'
                    ,'value'    => $params['pdf_idAffiliate']
                ),
                array(
                    'label'     => 'User'
                    ,'value'    => $params['pdf_idUser']
                ),
                array(
                    'label'     => 'Date'
                    ,'value'    => $params['sdate'] . ' to ' . $params['edate']
                )
            )
        );

        setLogs(
            array(
                'actionLogDescription' => 'Exported the generated Logsheet Monitoring Report (PDF)'
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
                ,'orientation'   => 'L'
                ,'header_fields' => $header_fields
            )
        );
    }

    public function printExcel() {
        $params = getData();

        $params['idModules'] = $this->getTruckIdModules();
        
        $data   = $this->model->getHistory($params);

        $data   = decryptAffiliate( $data );
        $data   = decryptUserData( $data );
        
        $csvarray = array();

        $csvarray[] = array( 'title' => $params['title'] );
        $csvarray[] = array( 'space' => '' );

        $csvarray[] = array( 'Affiliate', $params['pdf_idAffiliate']  );
        $csvarray[] = array( 'User', $params['pdf_idUser']  );
        $csvarray[] = array( 'Date', $params['sdate'] . ' to ' . $params['edate']  );
        $csvarray[] = array( 'space' => '' );

        $csvarray[] = array(
            'Date',
            'Time',
            'Affiliate',
            'User Full Name',
            'User Name',
            'User Type',
            'Ref',
            'Number',
            'Description',
        );

        foreach( $data as $d ){
            $csvarray[] = array(
                $d['datelog'],
                $d['time'],
                $d['affiliateName'],
                $d['fullName'],
                $d['euName'],
                $d['euTypeName'],
                $d['code'],
                $d['referenceNum'],
                $d['actionLogDescription']
            );
        }

        setLogs(
            array(
                'actionLogDescription' => 'Exported the generated Logsheet Monitoring Report (Excel)'
                ,'idAffiliate'         => $this->session->userdata('AFFILIATEID')
                ,'idEu'                => $this->USERID
                ,'idModule'            => $params['idModule']
                ,'time'                => date("H:i:s A")
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