<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Jayson Dagulo
 * Module: Standards2(Additional file for standards to be applied only on this Project)
 * Date: Oct 21, 2019
 * Finished: 
 * Description: This contains the functions and components that are considered a standard only for this project
 * */ 
class Standards2 extends CI_Controller {
    
    /* Class constructor */
    public function __construct(){
        parent::__construct();
        $this->load->library('encryption');
        setHeader( 'standards/Standards2_model' );
    }

    public function getAffiliate(){
        $params = getData();
        $view   = $this->model->getAffiliate( $params, $this->session->userdata('EMPLOYEEID') );
        $view   = decryptAffiliate( $view );

        // LQ();
        if( isset($params['hasAll']) && count( $view ) > 1 ) {
            array_unshift( $view, array(
                'id' => 0
                ,'name' => ( isset( $params['allValue'] )? $params['allValue'] : 'All' )
            ));
        }

        die(
            json_encode(
                array(
                    'success' => true
                    ,'view' => $view
                )
            )
        );
    }

    public function getReference(){
        $params = getData();
        $view   = $this->model->getReference( $params );

        // LQ();

        die(
            json_encode(
                array(
                    'success' => true
                    ,'view' => $view
                )
            )
        );
    }

    public function getLocationCmb(){
        $params = getData();
        $view   = $this->model->getLocationCmb( $params );

        if( (int)$params['hasAll'] == 1 ){
            array_unshift(
                $view
                ,array(
                    'id'    => 0
                    ,'name' => 'All'
                )
            );
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

    public function getSupplierCmb(){
        $params = getData();
        $view   = $this->model->getSupplierCmb( $params );
        $view = decryptSupplier( $view );

        if( (int)$params['hasAll'] == 1 ){
            array_unshift(
                $view
                ,array(
                    'id'    => 0
                    ,'name' => 'All'
                )
            );
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

    public function getCostCenter(){
        $params = getData();
        $view   = $this->model->getCostCenter( $params );
        $view   = decryptCostCenter( $view );

        if( isset($params['hasAll']) ) {
            array_unshift( $view, array(
                'id' => 0
                ,'name' => 'All'
            ));
        }
        
        die(
            json_encode(
                array(
                    'success' => true
                    ,'view' => $view
                )
            )
        );
    }

    public function getReferenceNum(){
        $params = getData();
        $params['tableName'] = 'invoices';
        $view   = $this->model->getReferenceNum( $params );
        $_invoice = $this->model->_getLatestRefNum( $params );
        $_currentSeries = [];

        foreach( $view as $series ){
            if( $_invoice->referenceNum < $series['seriesTo'] && empty( $_currentSeries ) ){
                $_currentSeries = $series;
            }
        }

        // LQ();

        /*  MATCH
            0 - SUCCESS
            1 - NOT_FOUND
            2 - MAX SERIES EXCEEDED
        */


        // die( json_encode( $view ) );
        
        // print_r( $view );
        
        // if( !empty( $view ) ) {
        //     $currentSeries = [];

        //     foreach( $view as $val ){
        //         $result = $this->model->_getRefNum( $val ); //Used to get the current available reference number

        //         $currentRefNum = (int)$result['referenceNum'];
        //         $seriesFrom = (int)$val['seriesFrom'];
        //         $seriesTo = (int)$val['seriesTo'];
                
        //         die( json_encode(array(
        //             'from' => $seriesFrom
        //             ,'to'  => $seriesTo
        //             ,'refNum' => $currentRefNum
        //         )));

        //         if( $currentRefNum > $seriesFrom && $currentRefNum < $seriesTo ){
        //             $currentSeries['refnum'] = $currentRefNum + 1;
        //             $currentSeries['idRef'] = (int)$val['idReferenceSeries'];
        //         }

        //         // switch( true ){
        //         //     case $currentRefNum > $seriesFrom && $currentRefNum < $seriesTo:
        //         //         $currentSeries['refnum'] = $currentRefNum + 1;
        //         //         $currentSeries['idRef'] = (int)$val['idReferenceSeries'];
        //         //         break;
        //         //     case $currentRefNum == $seriesFrom:
        //         //         $currentSeries['refnum'] = $currentRefNum;
        //         //         $currentSeries['idRef'] = (int)$val['idReferenceSeries'];
        //         //         break;
        //         //     // default:
        //         //     //     $response = 2;
        //         //     //     break;
        //         // }

        //         die( json_encode( $currentSeries ));

                
        //     }

        //     // echo 'Result: </br>';
        //     // print_r( $currentSeries );
            
        //     // var_dump( $currentSeries );

        //     $response = ( !$currentSeries ) ? array('refnum' => (int)$view[0]['seriesFrom'] , 'idRef' => $view[0]['idReferenceSeries']) : 2;

        //     // switch( true ){
        //     //     case empty( $currentSeries ):
                    
        //     //         break;
        //     //     case !empty( $response ):
        //     //         $response = 2;
        //     //         break;
        //     // }

        // } else {
        //     $response = 1;
        // }

        die(
            json_encode(
                array(
                    'success' => true
                    ,'view' => empty( $_currentSeries ) ? array( 'match' => 1) : $_currentSeries
                )
            )
        );
    }

    public function getItem( $pType = '' ){
        $params = getData();
        
        if( isset( $pType ) && $pType != '' ) {
            if( $pType == 1 ) {
                $view   = $this->model->getSupplierItems( $params );
            } elseif( $pType == 2 ) {
                $view   = $this->model->getCustomerItems( $params );
            }
        }

        // LQ();

        die(
            json_encode(
                array(
                    'success' => true
                    ,'view' => $view
                )
            )
        );
    }

    public function getSupplier(){
		$params = getData();
        $view	= $this->model->getSupplier( $params );
        $view   = decryptSupplier( $view );

        if( isset($params['hasAll']) ) {
            array_unshift( $view, array(
                'id' => 0
                ,'name' => 'All'
            ));
        }
        
		die(
			json_encode(
				array(
					'success'	=> true
					,'view'		=> $view
				)
			)
		);
    }
    
    function updateTransactionStatus() {
        $params = getData();
        $params['notedby'] = $this->session->userdata('USERID');
        $result = $this->model->updateTransactionStatus( $params );

        $transType = (int)$params['status'];

        if( isset($params['username']) ) {
            setLogs( array(
                'actionLogDescription' =>  ($transType == 2 ) ? $params['username'].' approved a Cash Receipt Transaction' : $params['username'].' cancelled a Cash Receipt Transaction' 
                ,'idEu' => $params['notedBy']
                ,'moduleID' => 28
                ,'time' => date("H:i:s A")
            ));
        }
		
        die( 
			json_encode(
                array(
                    'success' => true
                    ,'match' => ( $result <= 0 ) ? 0 : 1 
                )
            )
        );
    }

    function getItems(){
        $params = getData();
        $view = $this->model->getItems( $params );
        if( !empty( $view ) ) $view = decryptItem( $view );

        // LQ();

        die(
            json_encode(
                array(
                    'success' => true
                    ,'view'   => $view
                )
            )
        );
    }

    public function getItemsCombo(){
        $params     = getData();
        $hasAll     = ( isset( $params['hasAll'] )? (int)$params['hasAll'] : 0 );
        $view       = $this->model->getItemsCombo( $params );

        if( $hasAll > 0 && !isset( $params['query'] ) ){
            array_unshift(
                $view['view']
                ,array(
                    'idItem'    => 0
                    ,'barcode'  => 'All'
                    ,'itemName' => 'All'
                )
                );
        }

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

    public function getCOACombo(){
        $params     = getData();
        $hasAll     = ( isset( $params['hasAll'] )? (int)$params['hasAll'] : 0 );
        $view       = $this->model->getCOACombo( $params );
        
        if( $hasAll > 0 && !isset( $params['query'] ) ){
            array_unshift(
                $view['view']
                ,array(
                    'idCoa'         => 0
                    ,'acod_c15'     => 'All'
                    ,'aname_c30'    => 'All'
                )
            );
        }

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

    public function generateFormPDF(){
        $params = getData();
        $ci = & get_instance();

        /* Static but will be replaced later on. */
        $params['folder_name'] = 'inventory';
        $params['file_name'] = $params['title'];

		$ci->pdf->setPrintHeader( false );
		$ci->pdf->setPrintFooter( true );
        $ci->pdf->SetMargins( 6, 6 ); 

        /* Logo Header */
        logoHeader( array( 'orientation'=> 'P', 'title'=> $params['title'], 'idAffiliate' => $params['idAffiliate']  ) );

        /* Fields */
        $this->headerFields( $ci->pdf, $params['form'] );

        if(isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'],'Google App Engine') !== false){
			$directoryPath  = 'gs://newProject/';
		}
		else{
			$directoryPath='./';
		}
		
		if(!is_dir($directoryPath.'pdf/')){
            rmkdir($directoryPath.'pdf/');
            if(!is_dir($directoryPath.$params['folder_name'])) rmkdir('./'.$params['folder_name']);  
        }

        $ci->pdf->Output($directoryPath.'pdf/'.$params['folder_name'].'/'.$params['file_name'].'.pdf', 'F');
    }

    function headerFields( $instance, $formFields ){
        $instance->setCellHeightRatio(2);

        

        $fields = '';
        $fields .= '<table><tbody>';
        $fields .= '<tr>';

        foreach((array)$formFields as $key => $field ) {
           $fields .= '<td style="width:10%;">'. $key .'</td>';
           $fields .= '<td style="width:40%;"></td>';
        }
        $fields .= '</tr>';

        
        // $fields .= '<tr>
        //                 <td style="width:10%;">Field:</td>
        //                 <td style="width:40%;">Sample value </td>
        //                 <td style="width:10%;">Field:</td>
        //                 <td style="width:40%;">Sample value </td>
        //             </tr>';
        $fields .= '</tbody></table>';

        $instance->writeHTML($fields, true, false, true, false, '');
        // return $fields;
    }

    public function getRecord(){
        $params = getData();
        $view = $this->model->getRecord( $params );

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    function getAffiliateDetails(){
		$data = getData();
        $ret = $this->model->getAffiliateDetails( $data );
		die(json_encode(array(
			'success' => true
			,'view' => $ret
		)));
	}

	public function getAutomatedEntries(){
        $params = getData();
        $defaultEntries = $this->getEntriesByModule( $params['idModule'] );
        $journalEntries = [];
        
		if( !empty( $defaultEntries ) ){
            $_PARAMS = $params;

            $_PARAMS['default_table'] = $defaultEntries['default_table'];
            unset( $defaultEntries['default_table'] );

            /* Check if the entry table has unique identifier. */
            foreach( $defaultEntries as $entry => $table ) {
                if( $entry == 'supplier' && !isset( $params['idSupplier'] ) ) unset( $defaultEntries[ $entry ] );
                if( $entry == 'customer' && !isset( $params['idCustomer'] ) ) unset( $defaultEntries[ $entry ] );

                if( $entry == 'item' && !isset( $params['idItem'] ) ) { 
                    $_itemCol = $defaultEntries[ $entry ]; 
                    unset( $defaultEntries[ $entry ] );
                } ;
            }

            /* Special algorithm for Inventory Conversion */
            if( $params['idModule'] == 22 ){
                if( isset( $params['itemsToConvert'] ) && !empty( json_decode( $params['itemsToConvert']) ) ){
                    /* All items here are credit */
                    $_itemsToConvert = $this->getItemEntries( array(
                        'idModule'  => $params['idModule']
                        ,'items'    => $params['itemsToConvert']
                        ,'columns'  => $_itemCol
                        ,'itemType' => 'convert'
                    ));

                    foreach( $_itemsToConvert as $entry ){
                        array_push( $journalEntries, $entry );
                    }
                } 
                
                if( isset( $params['outputItems'] ) && !empty( json_decode( $params['outputItems'] )) ) {
                    /* All items here are debit */
                    $_outputItems = $this->getItemEntries( array(
                        'idModule'  => $params['idModule']
                        ,'items'    => $params['outputItems']
                        ,'columns'  => $_itemCol
                        ,'itemType' => 'output'
                    ));

                    foreach( $_outputItems as $entry ){
                        array_push( $journalEntries, $entry );
                    }
                }

            } elseif( $params['idModule'] == 43 ) {
                
                /* For Item Account/s */
                if( isset( $params['items'] ) && !empty( json_decode( $params['items'] )) ) {
                   
                    $params['items'] = json_decode( $params['items'] );

                    $_itemIDs = [];

                    foreach( $params['items'] as $item ){ array_push( $_itemIDs,$item->idItem );}
                    
                    $_items = $this->getItemEntries( array(
                        'idModule'  => $params['idModule']
                        ,'items'    => json_encode($_itemIDs)
                        ,'columns'  => $_itemCol
                    ));


                    foreach( $params['items'] as $idx => $item ){
                        switch( true ){
                            case $item->qtyTransferred > 0:
                                foreach( $_items as $key => $_item ){
                                    if( $_item['idItem'] == $item->idItem ){
                                        $_items[$key]['credit'] = $item->totalAmount;
                                        $_items[$key]['debit'] = 0;
                                    }
                                }
                            break;
                            case $item->qtyReceived > 0:
                                foreach( $_items as $key => $_item ){
                                    if( $_item['idItem'] == $item->idItem ){
                                        $_items[$key]['credit'] = 0;
                                        $_items[$key]['debit'] = $item->totalAmount;
                                    }
                                }
                            break;
                        }
                    }

                    foreach( $_items as $entry ){
                        array_push( $journalEntries, $entry );
                    }
                }
            } else {
                $view = $this->getEntries( $defaultEntries, $_PARAMS );
                if( !empty( $view ) ){
                    /* Get entries for accounting defaults, supplier, and customer */
                    foreach( $view as $key => $entry ){
                        $_balance = $this->getEntryBalance( $key, $params['idModule'] );
                        if( $entry != null && $entry != 0 ){
                            $entry_details = $this->model->getEntryDetails( array('idCoa' => $entry, 'balance' => $_balance, 'account' => $key ) );
                            array_push( $journalEntries, $entry_details );
                        }
                    }
                }

                /* Get entries for items */
                if( isset( $params['items'] ) && !empty( json_decode( $params['items'] ) ) && isset( $_itemCol )){

                    $itemEntries = $this->getItemEntries( array(
                        'idModule'  => $params['idModule']
                        ,'items'    => $params['items']
                        ,'columns'  => $_itemCol
                    ));
    
                    foreach( $itemEntries as $entry ){
                        array_push( $journalEntries, $entry );
                    }
                }
            }
		}

		die(
			json_encode(
				array(
					'success'	=> true
                    ,'view'		=> $journalEntries
				)
			)
		);
    }
    
    private function getEntries( $defaultEntries, $_PARAMS  ){
        $_entryQuery = '';
        $_tableCount = 0;

        if( isset( $defaultEntries['defaultaccounts']) || 
            isset( $defaultEntries['supplier'] ) || 
            isset( $defaultEntries['customer']) ) {

            /* Constructing the [string] query based on the entry values (table, column) returned. */
            foreach( $defaultEntries as $entry => $table) {

                foreach( $table as $_index => $_field ){
                    $_entryQuery .= $entry . '.' . $_field;
                    if( $_index < (count( $table ) - 1 )) $_entryQuery .= ', ';
                }

                if( $_tableCount < (count( $defaultEntries ) - 1 )) $_entryQuery .= ', ';
                $_tableCount++;
            }

            $_PARAMS['query'] = $_entryQuery;
            return $this->model->getEntries( $_PARAMS );
        }
    }

    private function getItemEntries( $params ){
        /* Get entries for items */
        $params['items'] = json_decode( $params['items'] );
        $itemEntries = [];

        if( !empty($params['items']) && isset($params['columns'])){
            $_selQuery = '';
            foreach( $params['columns'] as $_index => $_field ){
                $_balance = ( isset($params['itemType']) ) ? $this->getEntryBalance( $_field, $params['idModule'], $params['itemType'] ) : $this->getEntryBalance( $_field, $params['idModule'] );
                $_defQuery = 'SELECT  idItem, coa.idCoa as idCoa, coa.acod_c15 as code, coa.aname_c30 as name, '
                            . ( $_balance == 0 ? ' 1 as debit, 0 as credit' : ' 0 as debit, 1 as credit') 
                            . ', "' . $_field . '" as account '
                            . ' FROM item ';
                $_selQuery .= $_defQuery . 'LEFT JOIN coa on coa.idCoa = ' . $_field;
                if( $_index < count( $params['columns'] ) -1 ) $_selQuery .= ' UNION ALL ';
            }

            $_PARAMS = [];
            $_PARAMS['selectQuery'] = $_selQuery;
            $_PARAMS['items'] = $params['items'];

            $itemEntries = $this->model->getItemEntries( $_PARAMS );
        }

        return $itemEntries;
    }

	private function getEntriesByModule( $idmodule ){
		if( empty( $idmodule )) die('idModule is required.');
		
		$entries = [];
		switch( $idmodule ){
			case 25:
				//Receiving
				$entries = array(
					'defaultaccounts'	=> array( 'accPay', 'inputTax')
					,'supplier'		    => array('expenseGlAcc', 'discountGlAcc')
                    ,'item'			    => array('inventoryGlAcc')
                    ,'default_table'    => 'defaultaccounts'
				);
			break;
			case 29:
                //Purchase Return
                $entries = array(
					'defaultaccounts'	=> array( 'accRec')
                    ,'item'			    => array('inventoryGlAcc')
                    ,'default_table'    => 'defaultaccounts'
				);
			break;
			case 18:
                //Sales
                $entries = array(
                    'defaultaccounts'	=> array( 'accRec', 'outputTax', 'salesAccount', 'salesDiscount')
                    ,'customer'		    => array('discountGLAcc', 'salesGLAcc')
                    ,'item'			    => array('salesGlAcc', 'inventoryGlAcc')
                    ,'default_table'    => 'defaultaccounts'
				);
			break;
			case 21:
                //Sales Return
                $entries = array(
                    'defaultaccounts'	=> array( 'accPay')
                    ,'item'			    => array('inventoryGlAcc')
                    ,'default_table'    => 'defaultaccounts'
				);
			break;
			case 43:
                //Stock Transfer
                $entries = array(
                    'item'			    => array('salesGlAcc', 'inventoryGlAcc')
                    ,'default_table'    => 'item'
				);
			break;
			case 22:
                //Inventory Conversion
                $entries = array(
                    'item'			    => array('inventoryGlAcc')
                    ,'default_table'    => 'item'
				);
			break;
			case 45:
                //Disbursments
                $entries = array(
					'defaultaccounts'	=> array( 'accPay')
					,'supplier'		    => array('expenseGlAcc', 'discountGlAcc')
                    ,'default_table'    => 'defaultaccounts'
				);
			break;
			case 28:
                //Cash Receipts
                $entries = array(
					'defaultaccounts'	=> array( 'accRec')
					,'customer'		    => array('discountGLAcc', 'salesGLAcc')
                    ,'default_table'    => 'defaultaccounts'
				);
			break;
			case 57:
                //Vouchers Payable
                $entries = array(
					'defaultaccounts'	=> array( 'accPay')
                    ,'default_table'    => 'defaultaccounts'
				);
			break;
			case 58:
                //Vouchers Receivable
                $entries = array(
					'defaultaccounts'	=> array( 'accRec')
                    ,'default_table'    => 'defaultaccounts'
				);
			break;
			case 48:
                //Accounting Adjustment
                $entries = array(
                    'defaultaccounts'	=> array( 'debitMemo', 'creditMemo')
                    ,'default_table'    => 'defaultaccounts'
				);
			break;
		}

		return $entries;
    }
    
    private function getEntryBalance( $entry, $idmodule, $item_type = '' ){
        /* BALANCE: 0 = Debit, 1 = Credit */
        $_BALANCE = 0;

        switch( $entry ){
            /* Accounting Defaults */
            case 'accRec':
                $_ACCREC = array( 18, 29, 58, 28 );
                if(in_array( $idmodule, $_ACCREC )) $_BALANCE = ( $idmodule == 18 || $idmodule == 29 ) ? 0 : 1;
            break;
            case 'accPay':
                $_ACCPAY = array( 25, 21, 45, 57 );
                if(in_array( $idmodule, $_ACCPAY )) $_BALANCE = ( $idmodule == 45 || $idmodule == 57 ) ? 0 : 1;
            break;
            case 'debitMemo':
                $_BALANCE = ( $idmodule == 23 || $idmodule == 48 ) ? 0 : 1;
            break;
            case 'creditMemo':
                $_BALANCE = ( $idmodule == 23 || $idmodule == 48 ) ? 1 : 0;
            break;
            case 'inputTax':
                if( $idmodule == 25 ) $_BALANCE = 1;
            break;
            case 'outputTax':
                if( $idmodule == 18 ) $_BALANCE = 0;
            break;
            case 'salesAccount':
                if( $idmodule == 18 ) $_BALANCE = 0;
            break;
            case 'salesDiscount':
                if( $idmodule == 18 ) $_BALANCE = 1;
            break;
            /* Customer */
            case 'discountGLAcc':
                if( $idmodule == 18 || $idmodule == 28 ) $_BALANCE = 1;
            break;
            case 'salesGLAcc':
                $_BALANCE = ( $idmodule == 18 ) ? 0 : 1;
            break;
            /* Supplier */
            case 'expenseGlAcc':
                $_BALANCE = ( $idmodule == 25 ) ? 1 : 0;
            break;
            case 'discountGlAcc':
                if( $idmodule == 25 || $idmodule == 45 ) $_BALANCE = 1;
            break;
            /* Item */
            case 'salesGlAcc':
                if( $idmodule == 18 ) $_BALANCE = 0;
            break;
            case 'inventoryGlAcc':
                $_INVGLACC = array( 25, 18, 29, 21, 43 );
                if(in_array( $idmodule, $_INVGLACC )) $_BALANCE = ( $idmodule == 25 || $idmodule == 21 ) ? 0 : 1;
                if( $idmodule == 22 ) $_BALANCE = ( $item_type == 'convert' && $item_type != '' ) ? 1 : 0;
            break;
        }

        return $_BALANCE;
    }

    /* This function returns list of all affiliates in [idAffiliate, id, affiliateName, name, sk] */
    public function getAffiliates(){
        $params = getData();
        $view = $this->model->getAffiliates( $params );

        /** Decrypting fields **/
        foreach( $view['view'] as $key => $affiliate ){
            if( isset($affiliate['sk']) || !empty($affiliate['sk']) ){
                $this->encryption->initialize( array('key' => generateSKED( $affiliate['sk'] )) );

                $view['view'][$key]['affiliateName']    = $this->encryption->decrypt($affiliate['affiliateName']);
                $view['view'][$key]['name']             = $this->encryption->decrypt($affiliate['name']);
            }
        }

        die(
			json_encode(
				array(
					'success' => true
                    ,'view' => $view['view']
					,'total' => $view['count']
				)
			)
		);
    }

    public function getApprovers(){
        $params = getData();
        $view = $this->model->getApprovers();

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    /*
     * Added by makmak
     * this.f(x) checks if a transaction period is closed for this month 
     * */
    public function checkIf_journal_isClosed()
    {
        $isClosed   = false;
        $params     = getData();
        $data       = $this->model->checkIf_journal_isClosed( $params );

        if ( $data > 0 ) $isClosed = true;

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'data'     => $params
                    ,'view'     => $isClosed
                )
            )
        );
    }
}