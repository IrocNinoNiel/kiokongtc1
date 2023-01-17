<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Nojereport extends CI_Controller
{
    public function __Construct()
    {
        parent::__construct();
        setHeader( 'accounting/Nojereport_model' );
    }

    public function getModules()
    {
        $params = getData();
        $view = $this->model->getModules( $params );

        array_unshift( $view, array(
            'id' => 0
            ,'name' => 'All'
        ));

        die(
            json_encode(
                array(
                    'success' => true 
                    ,'view' => $view
                )
            )
        );
    }

    public function getNojereport()
    {
        $params = getData();
        $view = $this->model->getNojereport( $params );

        die(
            json_encode(
                array(
                    'success' => true 
                    ,'view' => $view
                )
            )
        );
    }

    function printPDF(){
        $params = getData();
        $data = $this->model->getNojereport( $params );

        $col = array(
            array(  
                'header'        => 'Date'
                ,'dataIndex'    => 'date'
                ,'type'         => 'datecolumn'
                ,'format'       => 'm/d/Y'
                ,'width'        => '20%'
            )
            ,array(   
                'header'        => 'Module'
                ,'dataIndex'    => 'moduleName'
                ,'width'        => '40%'
            )
            ,array(  
                'header'        => 'Reference'
                ,'dataIndex'    => 'reference'
                ,'width'        => '40%'
            )
        );
        
        $header_fields = array(
            array(
                array(
                    'label'     => 'Affiliate'
                    ,'value'    => $params['pdf_idAffiliate']
                )
                ,array(
                    'label'     => 'Date'
                    ,'value'    => $params['pdf_sdate'] . ' to ' .  $params['pdf_edate']
                )
                ,array(
                    'label'     => 'Module Name'
                    ,'value'    => $params['pdf_idModule']
                )
            )
        );

        setLogs(
            array(
               'actionLogDescription' => $this->USERFULLNAME . ' exported the generated No Journal Entry Report (PDF).'
			    ,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            )
        );

        generateTcpdf(
            array(
                'file_name'         => $params['title']
                ,'folder_name'      => 'accounting'
                ,'records'          => $data
                ,'header'           => $col
                ,'orientation'      => 'P'
                ,'header_fields'    => $header_fields
                ,'idAffiliate'      => $Affiliate
            )
        );
    }
     
    function printExcel(){
        $params = getData();
        $data = $this->model->getNojereport( $params );
        
        $csvarray = array();
  
        $csvarray[] = array( 'title' => $params['title'] );
        $csvarray[] = array( 'space' => '' );
  
        $csvarray[] = array( 'Affiliate', $params['pdf_idAffiliate'] );
        $csvarray[] = array( 'Date'     , $params['pdf_sdate'] . ' to ' . $params['pdf_edate'] );
        $csvarray[] = array( 'Module'   , $params['pdf_idModule'] );
        $csvarray[] = array( 'space' => '' );
  
        $csvarray[] = array(
           'Date'
           ,'Module Name'
           ,'Reference'
        );
  
        foreach( $data as $d ){
           $csvarray[] = array(
              $d['date']
              ,$d['moduleName']
              ,$d['reference']
           );
        }
  
        setLogs(
            array(
                'actionLogDescription' => $this->USERFULLNAME . ' exported the generated No Journal Entry Report (Excel).'
			    ,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            )
        );
  
        writeCsvFile(
            array(
                'csvarray' 	 => $csvarray
                ,'title' 	 => $params['title']
                ,'directory' => 'accounting'
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