<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Jayson Dagulo
 * Module       : Chart of account Settings
 * Date         : Dec 19, 2019
 * Finished     : Feb 03, 2020
 * Description  : This module allows authorized users to set (add, edit and delete) an account that will be used in journal entries.
 * DB Tables    : coa, coahistory, coaaffiliate, coaaffiliatehistory and logs
 * */ 
class Chartofaccounts extends CI_Controller {

    public function __construct(){
        parent::__construct();
        setHeader( 'accounting/Chartofaccounts_model' );
    }

    public function headerAccountStore(){
		$all = $this->model->getHeader( getData() );  
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $all
                )
            )
        );
    }
    
	public function getAccCode(){
		$result   = array();
		$data     = getData();
        $getAccod = $this->model->getAccod( $data );

		if( (int)$data['accountType'] == 1 ){
			$data['accod_c2'] = $getAccod['accod_c2'] + 1;
			$data['sucod_c3'] = 0;
        }
        else{
			$sucod  		  = $this->model->getSucod( $data ); 
			$data['sucod_c3'] = $sucod['sucod_c3'] + 1;
		}
		$result[]   = array(
			'mocod_c1'  => $data['mocod_c1']
			,'chcod_c1' => $data['chcod_c1']
			,'accod_c2' => str_pad ($data['accod_c2'] ,2, "0", STR_PAD_LEFT )
			,'sucod_c3' => str_pad( $data['sucod_c3'] ,3, "0", STR_PAD_LEFT )
			,'code_c7'  => $data['mocod_c1'] . $data['chcod_c1'] . str_pad($data['accod_c2'] , 2, "0", STR_PAD_LEFT ) . str_pad( $data['sucod_c3'] , 3, "0", STR_PAD_LEFT )
		);
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $result
                )
            )
        ); 
    }
    
    public function getCoaAffiliate(){
        $params = getData();
        $view   = $this->model->getCoaAffiliate( $params );
        $view   = decryptAffiliate( $view );
        
        for( $i = 0; $i < count( (array)$view ); $i++ ){
            $coaIsUsed  = $this->model->checkCoaAffiliate( (int)$params['idCoa'], $view[$i]['idAffiliate'] );
            $view[$i]['def'] = ( $coaIsUsed > 0? 1 : 0 );
        }
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    public function getHistory(){
        $params         = getData();
        $params['pdf']  = true;
        $view           = $this->model->getHistory( $params );
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    public function saveForm(){
        $params = getData();
        $onEdit = $params['onEdit'];

        /** check code **/
        if( _checkData(
            array(
                'table'		=> 'coa'
                ,'field'   	=> 'acod_c15'
                ,'value'   	=> $params['acod_c15']
                ,'exwhere'	=> ( $onEdit ) ? 'idCoa != '.$params['idCoaOld'] : null
            )
        ) ){
            die(
                json_encode(
                    array(
                        'success' => true
                        ,'match' => 1
                    )
                )
            );
        }
        if(
            _checkData(
                array(
                    'table'		=> 'coa'
                    ,'field'   	=> 'aname_c30'
                    ,'value'   	=> $params['aname_c30']
                    ,'exwhere'	=> ( $onEdit ) ? 'idCoa != ' . $params['idCoaOld'] . ' AND accountType = ' . $params['accountType'] : 'accountType = ' . $params['accountType']
                )
            )
        ){
            die(
                json_encode(
                    array(
                        'success'   => true 
                        ,'match'    => 2 
                    )
                )
            );
        }

        if( $onEdit ){
            if(
                !_checkData(
                    array(
                        'table'		=> 'coa'
                        ,'field'   	=> 'idCoa'
                        ,'value'   	=> $params['idCoaOld']
                    )
                )
            ){
                die(
                    json_encode(
                        array(
                            'success'   => true
                            ,'match'    => 2 
                        )
                    )
                );
            }

            if( $params['modify'] == 0 ){
                $dateModified = $this->standards->getDateModified( $params['idCoaOld'], 'idCoa', 'coa' );
                if( $params['dateModified'] != $dateModified->dateModified ){
                    die(
                        json_encode(
                            array(
                                'success' => true
                                ,'match' => 4 
                            )
                        )
                    );
                }
            }
        }

        $this->db->trans_begin();
        $params['idCoa']        = $params['mocod_c1'] . $params['chcod_c1'] . $params['accod_c2'] . $params['sucod_c3'];
        $params['new_idCoa']    = $params['idCoa'];
        $idCoa                  = $this->model->saveForm( $params );
        $params['idCoa']        = $idCoa;
        $idCoaHistory           = $this->model->saveFormHistory( $params );
        $params['idCoa']        = $idCoa;

        $this->model->deleteCoaAffiliates( $params );
        foreach( json_decode( $params['coaAffiliate'], true ) as $aff ){
            $aff['idCoa'] = $params['new_idCoa'];
            $this->model->insertCoaAffiliates( $aff );
            $aff['idCoaHistory'] = $idCoaHistory;
            $this->model->insertCoaAffiliatesHistory( $aff );
        }

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
        $params = getData();
		$match  = 0;
		
		if(
            !_checkData(
                array(
                    'table'     => 'coa'
                    ,'field'    => 'idCoa'
                    ,'value'    => $params['idCoa']
                )
            )
        ){
			die( json_encode( array( 'success'=>true, 'match'=>1 ) ) );
        }
        
        $view = $this->model->retrieveData( $params );
        if( $this->model->getRecordsUsed( $params ) ){
			$match = 2;
        }

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

    
	public function deleteRecord(){
        $params = getData();
        if(
            !_checkData(
                array(
                    'table'		=> 'coa'
                    ,'field'   	=> 'idCoa'
                    ,'value'   	=> $params['idCoa']
                )
            )
        ){
            die(
                json_encode(
                    array(
                        'success' => true
                        ,'match' => 1 
                    )
                )
            );
        }
        
        if( $this->model->getRecordsUsed( $params ) ){
            die(
                json_encode(
                    array(
                        'success' => true
                        ,'match' => 2 
                    )
                )
            );
        }

        if( $params['accountType'] == 1 ) {
            if( $this->model->checkIfHasSubsidiary( $params ) > 0){
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
        $this->model->delete( $params );
			
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
			$this->db->trans_commit();
			$params['deleting'] = true;
			$this->setLogs( $params );
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
    
    public function getCategoryRecords(){
        $view   = array(
            0   => array(
                'id'    => -1
                ,'name' => 'All'
            )
            ,1  => array(
                'id'    => 1
                ,'name' => 'Regular Account'
            )
            ,2  => array(
                'id'    => 2
                ,'name' => 'Cash Account'
            )
            ,3  => array(
                'id'    => 3
                ,'name' => 'Receivable Account'
            )
            ,4  => array(
                'id'    => 4
                ,'name' => 'Allowance for Bad Debits'
            )
            ,5  => array(
                'id'    => 5
                ,'name' => 'Inventories'
            )
            ,6  => array(
                'id'    => 6
                ,'name' => 'Raw Materials'
            )
            ,7  => array(
                'id'    => 7
                ,'name' => 'Work in Progress'
            )
            ,8  => array(
                'id'    => 8
                ,'name' => 'Finished Goods'
            )
            ,9  => array(
                'id'    => 9
                ,'name' => 'Properties and Equipments'
            )
            ,10  => array(
                'id'    => 10
                ,'name' => 'Accumulated Depreciation'
            )
            ,11  => array(
                'id'    => 11
                ,'name' => 'Accumulated Amortization'
            )
            ,12  => array(
                'id'    => 12
                ,'name' => 'Payable Account'
            )
            ,13  => array(
                'id'    => 13
                ,'name' => 'Cost of Sales'
            )
            ,14  => array(
                'id'    => 14
                ,'name' => 'Sales'
            )
            ,15  => array(
                'id'    => 15
                ,'name' => 'Sales Debits'
            )
            ,16  => array(
                'id'    => 16
                ,'name' => 'Other Income'
            )
            ,17  => array(
                'id'    => 17
                ,'name' => 'Operating Expenses'
            )
            ,18  => array(
                'id'    => 18
                ,'name' => 'Cost of Goods Manufactured'
            )
            ,19  => array(
                'id'    => 19
                ,'name' => 'Purchase Credits'
            )
            ,20  => array(
                'id'    => 20
                ,'name' => 'Direct Labor'
            )
            ,21  => array(
                'id'    => 21
                ,'name' => 'Manufacturing Overhead'
            )
            ,22  => array(
                'id'    => 22
                ,'name' => 'Applied Factory Overhead'
            )
            ,23  => array(
                'id'    => 23
                ,'name' => 'Other Expenses'
            )
            ,24  => array(
                'id'    => 24
                ,'name' => 'Retained Earnings'
            )
            ,25  => array(
                'id'    => 25
                ,'name' => 'Creditable Income Tax'
            )
            ,26  => array(
                'id'    => 26
                ,'name' => 'Capital'
            )
            ,27  => array(
                'id'    => 27
                ,'name' => 'Withdrawals'
            )
            ,28  => array(
                'id'    => 28
                ,'name' => 'Other Assets'
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

    public function processScript(){
        ini_set('max_execution_time', -1);
        $coa            = $this->model->getAllCOA();
        $affiliate      = $this->model->getAllAffiliate();
        $coaaffiliate   = array();
        foreach( $coa as $rs1 ){
            foreach( $affiliate as $rs2 ){
                $coaaffiliate[] = array(
                    'idCoa'         => $rs1['idCoa']
                    ,'idAffiliate'  => $rs2['idAffiliate']
                );
            }
        }
        
        $this->model->truncateDBCoaAffiliate();
        $this->model->saveCoaAffiliate( $coaaffiliate );
    }

    private function setLogs( $params ){
		$header = 'Chart of Accounts : '.$this->USERFULLNAME;
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
                'actionLogDescription'  => $header . ' ' . $action . ' account code : ' . $params['idCoa'] . '.'
                ,'ident'                => $params['idCoa']
            )
        );
    }
    
    function generateSampleCSV( $mode = '' ){
        $csvarray = array();

        $csvarray[] = array(
            'Account Type'
            ,'Classification'
            ,'Major Account'
            ,'Header Account'
            ,'Account Name'
            ,'Normal Balance'
            ,'Category'
            ,'Cash Flow Classification'
        );

        $data = array(
            array(
                'accountType'       => 'Header Account'
                ,'classification'   => 'Assets'
                ,'majorAccount'     => 'Current Assets'
                ,'headerAccount'    => ''
                ,'accountName'      => 'Accounts Receivable'
                ,'normalBalance'    => 'Debit'
                ,'category'         => 'Receivable Account'
                ,'cashFlow'         => ''
            )
            ,array(
                'accountType'       => 'Header Account'
                ,'classification'   => 'Assets'
                ,'majorAccount'     => 'Current Assets'
                ,'headerAccount'    => ''
                ,'accountName'      => 'Cash in Bank'
                ,'normalBalance'    => 'Debit'
                ,'category'         => 'Cash Account'
                ,'cashFlow'         => ''
            )
            ,array(
                'accountType'       => 'Subsidiary Account'
                ,'classification'   => 'Revenue'
                ,'majorAccount'     => 'Revenue'
                ,'headerAccount'    => 'Sales'
                ,'accountName'      => 'Sales Discount'
                ,'normalBalance'    => 'Debit'
                ,'category'         => 'Regular Account'
                ,'cashFlow'         => ''
            )
        );

        foreach( $data as $d ){
            $csvarray[] = array(
                $d['accountType']
                ,$d['classification']
                ,$d['majorAccount']
                ,$d['headerAccount']
                ,$d['accountName']
                ,$d['normalBalance']
                ,$d['category']
                ,$d['cashFlow']
            );
        }

        writeCsvFile(
            array(
                'csvarray' 	 => $csvarray
                ,'title' 	 => 'Sample COA List'
                ,'directory' => 'accounting'
            )
        );
    }

    function getMajorAccount( $value, $chcod_str ){
        $majoraccount_value = null;

        $majoracc_assets = array(
            '1'     => 'Current Assets'
            ,'2'    => 'Land Property & Equipment'
            ,'3'    =>  'Other Assets'
        );

        $majoracc_liab = array(
            '1'     => 'Current Liabilities'
            ,'2'    => 'Long Term Liabilities'
            ,'3'    =>  'Other Liabilities'
        );

         switch( $value ){
             case 1:
                 $majoraccount_value = array_search( $chcod_str, $majoracc_assets );
                 break;
             case 2:
                 $majoraccount_value = array_search( $chcod_str, $majoracc_liab );
                 break;
         }

         if( $value == 3 || $value == 4 || $value == 5 ) $majoraccount_value = 1;
         return $majoraccount_value;
    }

    function importCOA(){
        $params     = getData();
        $uploadFile = $_FILES['file_import'.$params['module']];
        $coa_items  = array();
        $invalid_rec  = array();
        $record = array();

        $classifications = array(
            '1'     => 'Assets'
            ,'2'    => 'Liabilities'
            ,'3'    => 'Capital'
            ,'4'    => 'Revenue'
            ,'5'    => 'Expenses'
        );

        $categories = array(
            '1' => 'Regular Account'
            ,'2' => 'Cash Account'
            ,'3' => 'Receivable Account'
            ,'4' => 'Allowance for Bad Debts'
            ,'5' => 'Inventories'
            ,'6' => 'Raw Materials'
            ,'7' => 'Work in Process'
            ,'8' => 'Finished Goods'
            ,'9' => 'Properties and Equipment'
            ,'10' => 'Accumulated Depreciation'
            ,'11' => 'Accumulated Amortization'
            ,'12' => 'Payable Account'
            ,'13' => 'Cost of Sales'
            ,'14' => 'Sales'
            ,'15' => 'Sales Debits'
            ,'16' => 'Other Income'
            ,'17' => 'Operating Expenses'
            ,'18' => 'Cost of Goods Manufactured'
            ,'19' => 'Purchase Credits'
            ,'20' => 'Freight In'
            ,'21' => 'Direct Labor'
            ,'22' => 'Manufacturing Overhead'
            ,'23' => 'Applied Factory Overhead'
            ,'24' => 'Other Expenses'
            ,'25' => 'Retained Earnings'
            ,'26' => 'Sales Credits'
            ,'27' => 'Creditable Income Tax'
            ,'28' => 'Capital'
            ,'29' => 'Withdrawals'
            ,'30' => 'Other Assets'
        );

        $cashflowclass = array(
            '1'     => 'Financing'
            ,'2'    => 'Investing'
            ,'3'    =>  'Operating'
        );

        $reckeys = array(
            'accountType'
            ,'classification'
            ,'majorAccount'
            ,'headerAccount'
            ,'accountName'
            ,'normalBalance'
            ,'category'
            ,'cashFlow'
            ,'reason'
        );

        /* Process taken in this section
		 * 1. Read file content
		 * 2. Organize records into arrays in preparation for saving
		 */
        if( $uploadFile['size'] || $params['tempFile'] ){
            if( $uploadFile['size'] ) $fileContents = file_get_contents( $uploadFile['tmp_name'] );
            else $fileContents = file_get_contents( $params['tempFile'] );

            foreach( preg_split( "/((\r?\n)|(\r\n?))/", $fileContents ) as $key => $rs ){
				if( !empty( $rs ) ){ /* check first if the line to be process is empty or not */
                    $lineRecord = explode( "\t", $rs );
                    
                    if( (int)$key != 0 ){
                        $coa_record = explode(",", $lineRecord[0]);
                        array_push( $record, $lineRecord[0] );

                        $coa_data = array(
                            'accountType'               => ( trim(html_entity_decode($coa_record[0]),'"') == 'Header Account' ? 1 : ( trim(html_entity_decode($coa_record[0]),'"') == 'Subsidiary Account' ? 2 : '' ) )
                            ,'aname_c30'                => trim(html_entity_decode($coa_record[4]),'"')
                            ,'mocod_c1'                 => array_search( $coa_record[1], $classifications )
                            ,'norm_c2'                  => ( $coa_record[5] == 'Debit' ? 'DR' : ( $coa_record[5] == 'Credit' ? 'CR': '' ) )
                            ,'accID'                    => array_search( trim(html_entity_decode($coa_record[6]),'"'), $categories )
                            ,'dateModified'             => date( 'Y-m-d H:m:s' )
                            ,'recordedBy'               => $this->USERID
                        );

                        $coa_data['chcod_c1'] = $this->getMajorAccount( $coa_data['mocod_c1'], $coa_record[2] );

                        if( (int)$coa_data['accountType'] == 1 ){
                            $getAccod   = $this->model->getAccod( $coa_data );

                            $accod_c2               = $getAccod['accod_c2'] + 1;
                            $coa_data['accod_c2']   = str_pad ( $accod_c2 ,2, "0", STR_PAD_LEFT );
                            $coa_data['sucod_c3']   = str_pad ( 0, 3, "0", STR_PAD_LEFT );
                        } else {
                            $headerAccounts         = $this->model->getHeader( $coa_data );

                            $coa_data['accod_c2']   = array_search( $coa_record[3], array_column($headerAccounts, 'name', 'id') );
                            $sucod  		        = $this->model->getSucod( $coa_data ); 
                            $coa_data['sucod_c3']   = str_pad ( $sucod['sucod_c3'] + 1, 3, "0", STR_PAD_LEFT );
                        }

                        $coa_data['idCoa']      = $coa_data['mocod_c1'] . $coa_data['chcod_c1'] . $coa_data['accod_c2'] . $coa_data['sucod_c3'];
                        $coa_data['acod_c15']   = $coa_data['idCoa'];

                        // print_r( $coa_data );
                        //Validate record
                        if( !in_array('', $coa_data ) ){
                            $coa_data['cashflow_classification'] = ( !empty( $coa_record[7] ) ? array_search( trim(html_entity_decode($coa_record[7]),'"'), $cashflowclass ) : '' );
                            $validCashFlow      = ( !filter_var($coa_data['cashflow_classification'], FILTER_VALIDATE_BOOLEAN) && !empty( $coa_record[7] ) ? false : true );
                            $validAccountName   = $this->model->chkHeaderName( trim(html_entity_decode($coa_data['aname_c30']),'"') );

                            if( !$validCashFlow || $validAccountName ) {
                                if( $validAccountName ) $coa_record['reason'] = 'Double entry for Account Name: ' . $coa_data['aname_c30'];
                                if( !$validCashFlow ) $coa_record['reason'] = 'Incorrect value for Cash Flow Classification';
                                array_push( $invalid_rec, array_combine($reckeys, $coa_record) );
                            }
                            else $this->model->saveImportedCOA( $coa_data );

                        } else {
                            $count = 0;
                            $fields = array(
                                'Account Type'
                                ,'Classification'
                                ,'Major Account'
                                ,'Header Account'
                                ,'Account Name'
                                ,'Normal Balance'
                                ,'Category'
                                ,'Cash Flow'
                            );

                            $coa_record['reason'] = 'Incorrect value for ';
                            foreach( $coa_data as $ky => $data ){
                                if( empty($data) ) {
                                    switch($ky){
                                        case 'accountType':
                                            $coa_record['reason'] .= ( $count > 0 ? ', ' : '') . $fields[0];
                                        break;
                                        case 'chcod_c1': //Classification
                                            $coa_record['reason'] .= ( $count > 0 ? ', ' : '') . $fields[1];
                                        break;
                                        case 'mocod_c1': //Major Account
                                            $coa_record['reason'] .= ( $count > 0 ? ', ' : '') . $fields[2];
                                        break;
                                        case 'accod_c2': //Header Account
                                            $coa_record['reason'] .= ( $count > 0 ? ', ' : '') . $fields[3];
                                        break;
                                        case 'aname_c30': //Account Name
                                            $coa_record['reason'] .= ( $count > 0 ? ', ' : '') . $fields[4];
                                        break;
                                        case 'norm_c2': //Normal Balance
                                            $coa_record['reason'] .= ( $count > 0 ? ', ' : '') . $fields[5];
                                        break;
                                        case 'accID': //Categories
                                            $coa_record['reason'] .= ( $count > 0 ? ', ' : '') . $fields[6];
                                        break;
                                    }

                                    $count++;
                                }

                            }
                            array_push( $invalid_rec, array_combine($reckeys, $coa_record) );
                        }
                    }
				}
            }

        }

        die(
            json_encode(
                array(
                    'success'       => true
                    ,'invalid_rec'  => $invalid_rec
                )
            )
        );
    }

    function download( $title ){
		force_download(
			array(
				'title'      => $title
				,'directory' => 'accounting'
			)
		);
    }
    
}