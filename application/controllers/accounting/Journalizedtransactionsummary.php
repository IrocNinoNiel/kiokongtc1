<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Journalizedtransactionsummary extends CI_Controller
{
    public function __Construct()
    {
        parent::__construct();
        setHeader( 'accounting/Journalizedtransactionsummary_model' );
    }

    public function getModules()
    {
        $params = getData();
        $view   = $this->model->getModules( $params );

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

    public function getReference()
    {
        $params = getData();
        $view   = $this->model->getReference( $params );

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

    public function getJournalizedtransactionsummary()
    {
        $params = getData();
        $view = $this->model->getJournalizedtransactionsummary( $params );

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
        $data = $this->model->getJournalizedtransactionsummary( $params );

        $col = array(
            array(  
                'header'        => 'Date'
                ,'dataIndex'    => 'date'
                ,'type'         => 'datecolumn'
                ,'format'       => 'm/d/Y'
                ,'width'        => '10%'
            )
            ,array(   
                'header'        => 'Reference'
                ,'dataIndex'    => 'reference'
                ,'width'        => '10%'
            )
            ,array(   
                'header'        => 'Account Code'
                ,'dataIndex'    => 'accountCode'
                ,'width'        => '20%'
            )
            ,array(   
                'header'        => 'Account Name'
                ,'dataIndex'    => 'accountName'
                ,'width'        => '40%'
            )
            ,array(  
                'header'        => 'Debit'
                ,'dataIndex'    => 'debit'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
                ,'width'        => '10%'
                ,'hasTotal'     => true
            )
            ,array(  
                'header'        => 'Credit'
                ,'dataIndex'    => 'credit'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
                ,'width'        => '10%'
                ,'hasTotal'     => true
            )
        );
        
        $header_fields = array(
            array(
                array(
                    'label'     => 'Affiliate'
                    ,'value'    => $params['pdf_idAffiliate']
                )
                ,array(
                    'label'     => 'Module Name'
                    ,'value'    => $params['pdf_idModule']
                )
                ,array(
                    'label'     => 'Reference'
                    ,'value'    => $params['pdf_idReference']
                )
                ,array(
                    'label'     => 'Date'
                    ,'value'    => $params['pdf_sdate'] . ' to ' .  $params['pdf_edate']
                )
            )
        );

        setLogs(
            array(
               'actionLogDescription' => $this->USERFULLNAME . ' exported the generated Journalized Transaction Summary Report (PDF).'
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
        $data = $this->model->getJournalizedtransactionsummary( $params );
        
        $csvarray = array();
  
        $csvarray[] = array( 'title' => $params['title'] );
        $csvarray[] = array( 'space' => '' );
  
        $csvarray[] = array( 'Affiliate', $params['pdf_idAffiliate'] );
        $csvarray[] = array( 'Module'   , $params['pdf_idModule'] );
        $csvarray[] = array( 'Reference', $params['pdf_idReference'] );
        $csvarray[] = array( 'Date'     , $params['pdf_sdate'] . ' to ' . $params['pdf_edate'] );
        $csvarray[] = array( 'space' => '' );
  
        $csvarray[] = array(
           'Date'
           ,'Reference'
           ,'Account Code'
           ,'Account Name'
           ,'Debit'
           ,'Credit'
        );
  
        foreach( $data as $d ){
            $csvarray[] = array(
                $d['date']
                ,$d['reference']
                ,$d['accountCode']
                ,$d['accountName']
                ,number_format( $d['debit'], 2 )
                ,number_format( $d['credit'], 2 )
            );
        }

        $csvarray[] = array(
            ''
            ,''
            ,''
            ,''
            ,array_sum(array_column($data, 'debit'))
            ,array_sum(array_column($data, 'credit'))
        );

        setLogs(
            array(
                'actionLogDescription' => $this->USERFULLNAME . ' exported the generated Journalized Transaction Summary Report (Excel).'
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