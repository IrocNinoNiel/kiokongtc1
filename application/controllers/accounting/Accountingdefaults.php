<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Jayson Dagulo
 * Module       : Accounting Defaults
 * Date         : Dec 26, 2019
 * Finished     : Mar 11, 2020
 * Description  : This module allows authorized users to set the accounting defaults of every transaction module.
 * DB Tables    : 
 * */ 

class Accountingdefaults extends CI_Controller {

	public function __construct(){
		parent::__construct();
		setHeader('accounting/Accountingdefaults_model');
    }

    public function getHistory(){
        $params = getData();
        $view   = $this->model->viewAll( $params );

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view['view']
                    ,'total'    => $view['count']
                )
            )
        );
    }

    public function getAffiliates(){
        $params = getData();
        if( !isset( $params['idDefaultEntry'] ) ) $params['idDefaultEntry'] = 0;
        $view   = $this->model->getAffiliates( $params );
        $view   = decryptAffiliate( $view );
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    public function getModules(){
        $params = getData();
        $view   = $this->model->getModules( $params );
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    public function getReference(){
        $params = getData();
        if( !isset( $params['idModule'] ) ) $params['idModule'] = 0;
        $view   = $this->model->getReference( $params );
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    public function getDefaultEntryAccounts(){
        $params = getData();
        if( !isset( $params['idDefaultEntry'] ) ) $params['idDefaultEntry'] = 0;
        $view   = $this->model->getDefaultEntryAccounts( $params );
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    public function getAccountListing(){
        $params = getData();
        $view   = $this->model->getAccountListing( $params );
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    public function getDefaultAccount(){
        $params = getData();
        $view   = $this->model->getAccountDefaults( $params );
        
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    public function saveFormDefault(){
        $params                 = getData();
        $affiliateRecords       = json_decode( $params['affiliateRecords'], true );
        $journalEntryRecords    = json_decode( $params['journalEntryRecords'], true );
        $idDefaultEntry         = (int)$params['idDefaultEntry'];
        unset( $params['idDefaultEntry'] );

        /* first check if purpose already exists */
        if( _checkData(
            array(
                'table'     => 'defaultentry'
                ,'field'    => 'purpose'
                ,'value'    => $params['purpose']
                ,'exwhere'  => 'idDefaultEntry NOT IN( ' . $idDefaultEntry . ' )'
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

        if( $idDefaultEntry > 0 ){
            if( !_checkData(
                array(
                    'table'     => 'defaultentry'
                    ,'field'    => 'idDefaultEntry'
                    ,'value'    => $idDefaultEntry
                )
            ) ){
                die(
                    json_encode(
                        array(
                            'success'   => true
                            ,'match'    => 2
                        )
                    )
                );
            }
        }
        $this->db->trans_begin();

        /* process saving of header */
        $oldIdDefaultEntry = $idDefaultEntry;
        $idDefaultEntry = $this->model->saveDefaultEntry( $params, $idDefaultEntry );
        $params['idDefaultEntry'] = $idDefaultEntry;
        $idDefaultEntryHistory  = $this->model->saveDefaultEntryHistory( $params );
        
        /* delete all records connected to it defaultentryaffiliate and defaultentryposting */
        $this->model->deleteConnectedRecords( $idDefaultEntry );

        for( $i = 0; $i < count( $affiliateRecords ); $i++ ){
            $affiliateRecords[$i]['idDefaultEntry'] = $idDefaultEntry;
            $affiliateRecords[$i]['idDefaultEntryHistory'] = $idDefaultEntryHistory;
        }
        for( $i = 0; $i < count( $journalEntryRecords ); $i++ ){
            $journalEntryRecords[$i]['idDefaultEntry'] = $idDefaultEntry;
            $journalEntryRecords[$i]['idDefaultEntryHistory'] = $idDefaultEntryHistory;
        }

        $this->model->insertDefaultEntryAffiliate( $affiliateRecords );
        $this->model->insertDefaultEntryAffiliateHistory( $affiliateRecords );
        $this->model->insertDefaultEntryPosting( $journalEntryRecords );
        $this->model->insertDefaultEntryPostingHistory( $journalEntryRecords );

        if( $this->db->trans_status() === FALSE ){
			$this->db->trans_rollback();
			die(
                json_encode(
                    array(
                        'success' => false
                    )
                )
            );
        }
        else{
            $params['action']   = ( $oldIdDefaultEntry > 0? 'modified' : 'added new' ) . ' default journal entry for the purpose of ' . $params['purpose'];
            $params['id']       = $idDefaultEntry;
			$this->setLogs( $params );
			$this->db->trans_commit();
			die(
                json_encode(
                    array(
                        'success' => true
                        ,'match' => 0
                    )
                )
            );
		}

    }

    public function retrieveData(){
        $params     = getData();
        /* first check if record still exists */
        if( !_checkData(
            array(
                'table'     => 'defaultentry'
                ,'field'    => 'idDefaultEntry'
                ,'value'    => $params['idDefaultEntry']
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
        $view   = $this->model->retrieveData( $params );
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'match'    => 0
                    ,'view'     => $view
                )
            )
        );
    }

    public function getCoa(){
        $params     = getData();
        $view       = $this->model->getCoa( $params );

        // LQ();
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    public function deleteRecord(){
        $params = getData();
        /* check first if record still exists */
        if( !_checkData(
            array(
                'table'     => 'defaultentry'
                ,'field'    => 'idDefaultEntry'
                ,'value'    => (int)$params['idDefaultEntry']
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

        // $this->model->deleteConnectedRecords( (int)$params['idDefaultEntry'] );
        $this->model->deleteDefaultEntry( $params );

        $params['action']   = 'deleted default journal entry with purpose : ' . $params['purpose'];
        $params['id']       = $params['idDefaultEntry'];
        $this->setLogs( $params );
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'match'    => 0
                )
            )
        );
    }

    public function saveFormAccount(){
        $params         = getData();
        /* save record tab */
        $params['idDefaultAcc'] = $this->model->saveRecords( $params );
        $this->model->saveRecordsHistory( $params );
        $params['action']       = ' modified default account setting for affiliate : ' . $params['affiliateName'];
        $this->setLogs( $params );

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'match'    => 0
                )
            )
        );
    }

    public function printPDF(){
        $params             = getData();
        $affiliateSelected  = json_decode( $params['affiliateSelected'], true );
        $journalEntry       = json_decode( $params['journalEntry'], true );

		$pdfParams = array(
			'title'             => $params['pageTitle']
			,'file_name'        => $params['pageTitle']
			,'folder_name'      => 'pdf/accounting/'
			,'table_hidden'     => true
			,'addPage'          => false
			,'grid_font_size'   => 8
        );
        
        $html   = '
                <table width="100%">
					<tr>
						<td width="10%">Purpose:</td>
						<td width="35%">' . $params['purpose'] . '</td>
						<td width="10%" rowspan="4">Affiliate:</td>
                        <td width="35%" rowspan="4">
                            <table width = "100%" border = "1" cellpadding = "10" >
                                <tr style="background-color:#f1f1f1;">
                                    <td width="100%" >Affiliate</td>
                                </tr>';
        /* add affiliate records */
        foreach( $affiliateSelected as $rs ){
            $html   .= '
                                <tr>
                                    <td width="100%">' . $rs['affiliateName'] . '</td>
                                </tr>
            ';
        }
        $html   .=          '</table>
                        </td>
					</tr>
					<tr>
						<td width="10%">Module:</td>
						<td width="35%">' . $params['moduleName'] . '</td>
					</tr>
					<tr>
						<td width="10%">Reference:</td>
						<td width="35%">' . $params['referenceName'] . '</td>
					</tr>
					<tr>
						<td width="10%">Remarks : </td>
						<td width="35%">' . $params['remarks'] . '</td>
					</tr>
				</table>
				<br/>
				<br/>
				<table style="width : 100%" border = "1" cellpadding = "10">
					<tr style="background-color:#f1f1f1;">
						<th width="30%" align="center">Code</th>
						<th width="40%" align="center">Name</th>
						<th width="15%" align="center">Debit</th>
						<th width="15%" align="center">Credit</th>
                    </tr>';
        $totalDebit = 0;
        $totalCredit = 0;
        foreach( $journalEntry as $rs ){
            $html   .= '
                    <tr>
                        <td>' . $rs['acod_c15'] . '</td>
                        <td>' . $rs['aname_c30'] . '</td>
                        <td>' . $rs['debit'] . '</td>
                        <td>' . $rs['credit'] . '</td>
                    </tr>
            ';
            $totalDebit += $rs['debit'];
            $totalCredit += $rs['credit'];
        }

        $html   .= '
                    <tr>
                        <td colspan="2">Total</td>
                        <td>' . number_format( $totalDebit, 2 ) . '</td>
                        <td>' . number_format( $totalCredit, 2 ) . '</td>
                    </tr>
                </table>
        ';

        generate_table( $pdfParams, array(), array(), $html );
    }

	public function viewPDF( $title ){
		viewPDF(
			array(
				'file_name' => $title
				,'folder_name' => 'accounting'
			)
		);
	}

    private function setLogs( $params ){
		$header = 'Accounting Defaults : '.$this->USERFULLNAME;
		$action = '';
		
		if( isset( $params['deleting'] ) ){
			$action = 'deleted';
		}
		else{
			if( isset( $params['action'] ) )
				$action = $params['action'];
			else
				$action = ( $params['onEdit'] == 1  ? 'modified' : 'added new' );
		}
		
		setLogs(
            array(
                'actionLogDescription'  => $header . ' ' . $action
            )
        );
    }

}