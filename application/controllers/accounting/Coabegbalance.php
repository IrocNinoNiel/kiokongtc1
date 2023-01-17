<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Jayson Dagulo
 * Module       : Chart of Accounts Beginning Balance
 * Date         : Jan 28, 2019
 * Finished     : 
 * Description  : This module allows authorized user to manually closes the journal entries.
 * DB Tables    : 
 * */
class Coabegbalance extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        setHeader( 'accounting/Coabegbalance_model' );
    }

    public function getBeginningJournalEntries(){
        $params     = getData();
        $view       = $this->model->getBeginningJournalEntries( $params );
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    public function retrieveData(){
        $params = getData();
        $record = $this->model->retrieveData( $params );
        
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $record
                )
            )
        );
    }

    public function saveForm(){
        $params     = getData();
        $jerecords  = json_decode( $params['jerecords'], true );

        /* first check if record exists */
        if( _checkData(
            array(
                'table'		=> 'accountbegbal'
                ,'field'   	=> 'idAffiliate'
                ,'value'   	=> $params['idAffiliate']
                ,'exwhere'	=> 'idAccBegBal NOT IN( ' . (int)$params['idAccBegBal'] . ' )'
            )
        ) ){
            die(
                json_encode(
                    array(
                        'success'   => true
                        ,'match'    => 1 
                    )
                )
            );
        }

        if( (int)$params['idAccBegBal'] > 0 && $params['modify'] == 0 ){
            $dateModified = $this->standards->getDateModified( $params['idAccBegBal'], 'idAccBegBal', 'accountbegbal' );
            if( $params['dateModified'] != $dateModified->dateModified ){
                die(
                    json_encode(
                        array(
                            'success' => true
                            ,'match' => 2
                        )
                    )
                );
            }
        }

        $this->db->trans_begin();
        $params['idAccBegBal']          = $this->model->saveForm( $params );
        $params['idAccBegBalHistory']   = $this->model->saveFormHistory( $params );
        for( $i = 0; $i < count( $jerecords ); $i++ ){
            $jerecords[$i]['idAccBegBal']           = $params['idAccBegBal'];
            $jerecords[$i]['idAccBegBalHistory']    = $params['idAccBegBalHistory'];
        }
        /* delete first previously saved posting */
        $this->model->deleteExistingPosting( $params );
        /* save new posting records */
        $this->model->savePosting( $jerecords );
        /* save history records */
        $this->model->savePostingHistory( $jerecords );

        $success    = $this->db->trans_status();
        if( $success ){
            $this->setLogs( $params );
            $this->db->trans_commit();
        }
        else $this->db->trans_rollback();
        die(
            json_encode(
                array(
                    'success'   => $success
                    ,'match'    => 0
                )
            )
        );
    }

    public function printPDF(){
        $data = getData();
        $list = $this->model->getBeginningJournalEntries( $data );
        $header_fields = array(
            array(
                array(
                    'label'     => 'Affiliate'
                    ,'value'    => $data['affiliateName']
                )
            )
        );
        $params1 = array(
            array(   
                'header'        => 'Account Code'
                ,'dataIndex'    => 'acod_c15'
                ,'width'        => '20%'
            )
            ,array(  
                'header'        => 'Account Name'
                ,'dataIndex'    => 'aname_c30'
                ,'width'        => '40%'
            )
            ,array(  
                'header'        => 'Beginning DR'
                ,'dataIndex'    => 'debit'
                ,'width'        => '20%'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
            )
            ,array(  
                'header'        => 'Beginning CR'
                ,'dataIndex'    => 'credit'
                ,'width'        => '20%'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
            )
        );
        generateTcpdf(
			array(
				'file_name' => $data['title']
				,'folder_name' => 'accounting'
				,'records' => $list
				,'header' => $params1
				,'orientation' => 'P'
				,'header_fields' => $header_fields
			)
		);
    }

    private function setLogs( $params ){
		$header = $this->USERFULLNAME;
		$action = '';
		
		if( isset( $params['deleting'] ) ){
			$action = 'deleted';
		}
		else{
			if( isset( $params['action'] ) )
				$action = $params['action'];
			else
				$action = ( $params['onEdit'] == 1  ? 'edited the' : 'added a' );
		}
		
		setLogs(
            array(
                'actionLogDescription'  => $header . ' ' . $action . ' beginning balance.'
                ,'ident'                => $params['idAccBegBal']
            )
        );
    }
    
	public function viewPDF( $title ){
		viewPDF(
			array(
				'file_name' => $title['view']
				,'folder_name' => 'accounting'
			)
		);
	}

}