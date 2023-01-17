<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Adjustmentsaccsummary extends CI_Controller
{
    public function __Construct()
    {
        parent::__Construct();
        $this->load->library('encryption');
        setHeader('accounting/Adjustmentsaccsummary_model');
    }

    public function getReferences()
    {
        $params = getData();
        $view   = $this->model->getReferences( $params );

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

    public function getAdjustmentsaccSummary()
    {
        $params = getData();
        $view   = $this->model->getAdjustmentsaccSummary( $params );

        $_viewHolder = $view;
        foreach( $_viewHolder as $idx => $record ){
            if( isset( $record['affiliateSK'] ) && !empty( $record['affiliateSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $record['affiliateSK'] )));
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $record['affiliateName'] );
            }
            
            if( isset( $record['nameSK'] ) && !empty( $record['nameSK'] ) ){
               $this->encryption->initialize( array( 'key' => generateSKED( $record['nameSK'] )));
               $_viewHolder[$idx]['name'] = $this->encryption->decrypt( $record['name'] );
            }
        }
        $view = $_viewHolder;

        setLogs(
            array(
                'actionLogDescription' => 'Generate Accounting Adjustment Summary Report.'
                ,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
                ,'idEu'                => $this->USERID
                ,'moduleID'            => 66
                ,'time'                => date('H:i:s A')
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

    function printPDF(){
        $params = getData();
        $data = $this->model->getAdjustmentsaccSummary( $params );

        $col = array(
            array(  
                'header'        => 'Date'
                ,'dataIndex'    => 'date'
                ,'width'        => '15%'
            )
            ,array(   
                'header'        => 'Affiliate'
                ,'dataIndex'    => 'affiliateName'
                ,'width'        => '15%'
            )
            ,array(  
                'header'        => 'Reference'
                ,'dataIndex'    => 'reference'
                ,'width'        => '15%'
            )
            ,array(  
                'header'        => 'Name'
                ,'dataIndex'    => 'name'
                ,'width'        => '25%'
            )
            ,array(  
                'header'        => 'Description'
                ,'dataIndex'    => 'description'
                ,'width'        => '15%'
            )
            ,array(  
                'header'        => 'Amount'
                ,'dataIndex'    => 'amount'
                ,'width'        => '15%'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000'
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
                    'label'     => 'View By'
                    ,'value'    => $params['pdf_viewBy']
                )
                ,array(
                    'label'     => 'Reference'
                    ,'value'    => $params['pdf_idReference']
                )
                ,array(
                    'label'     => 'Filter By'
                    ,'value'    => $params['pdf_filterBy']
                )
                ,array(
                    'label'     => 'Date'
                    ,'value'    => $params['pdf_sdate'] . ' to ' . $params['pdf_edate']
                )
            )
        );

        setLogs(
            array(
               'actionLogDescription' => 'Exported the generated Accounting Adjustment Summary Report (PDF).'
                ,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
                ,'idEu'                => $this->USERID
               ,'moduleID'            => 66
               ,'time'                => date('H:i:s A')
            )
        );

        generateTcpdf(
            array(
                'file_name' => $params['title']
                ,'folder_name' => 'accounting'
                ,'records' => $data
                ,'header' => $col
                ,'orientation' => 'P'
                ,'header_fields' => $header_fields
            )
        );
    }

    function printExcel(){
        $params = getData();
        $data = $this->model->getAdjustmentsaccSummary( $params );

        $csvarray = array();

        $csvarray[] = array( 'title' => $params['title'] );
        $csvarray[] = array( 'space' => '' );

        $csvarray[] = array( 'Affiliate', $params['pdf_idAffiliate'] );
        $csvarray[] = array( 'View By', $params['pdf_viewBy'] );
        $csvarray[] = array( 'Reference', $params['pdf_idReference'] );
        $csvarray[] = array( 'filterBy', $params['pdf_filterBy'] );
        $csvarray[] = array( 'Date', $params['pdf_sdate'] . ' to ' . $params['pdf_edate'] );
        $csvarray[] = array( 'space' => '' );

        $csvarray[] = array(
            'Date'
            ,'Affiliate'
            ,'Reference'
            ,'Name'
            ,'Description'
            ,'Amount'
        );

        foreach( $data as $d ){
            $csvarray[] = array(
                $d['date']
                ,$d['affiliateName']
                ,$d['reference']
                ,$d['name']
                ,$d['description']
                ,number_format( $d['amount'], 2 )
            );
        }

        $csvarray[] = array(
            ''
            ,''
            ,''
            ,''
            ,''
            ,number_format( array_sum( array_column( $data, 'amount') ), 2 ) 
        );

        setLogs(
            array(
               'actionLogDescription' => 'Exported the generated Accounting Adjustment Summary Report (Excel).'
                ,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
                ,'idEu'                => $this->USERID
               ,'moduleID'            => 66
               ,'time'                => date('H:i:s A')
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
