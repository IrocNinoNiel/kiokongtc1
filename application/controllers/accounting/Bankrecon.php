<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Jays
 * Module       : Batch Reconciliation
 * Date         : Mar. 03, 2020
 * Finished     : 
 * Description  : This module allows authorized user to reconcile banks for the cheques to be tagged as cleared.
 * DB Tables    : 
 * */ 
class Bankrecon extends CI_Controller {

    public function __construct(){
        parent::__construct();
        setHeader('accounting/Bankrecon_model');
    }

    public function getHistory(){
        $params     = getData();
        $view       = $this->model->viewAll( $params );
        
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

    public function getBank(){
        $params     = getData();
        $view       = $this->model->getBanks( $params );
        $view       = decryptBank( $view );
        
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    public function getBankAccount(){
        $params     = getData();
        $view       = $this->model->getBankAccount( $params );
        $view       = decryptBankAccount( $view );
        
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    public function getReceipts(){
        $params         = getData();
        $view           = $this->model->getReceipts( $params );
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    public function getDisbursements(){
        $params         = getData();
        $view           = $this->model->getDisbursements( $params );
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    public function getAccBookBalance(){
        $params             = getData();
        $params['sdate']    = $params['reconYear'] . '-' . $params['reconMonth'] . '-01';
        $params['edate']    = date( 'Y-m-t', strtotime( $params['reconYear'] . '-' . $params['reconMonth'] ) );

        /* process unadjusted book balance */
        $unadjustedBookBalance      = 0;
        $bookBeginningBalance       = $this->model->getBookBeginningBalance( $params );
        $bookCurrentBalance         = $this->model->getBookCurrentBalance( $params );
        foreach( $bookBeginningBalance as $recordSet ){
            $unadjustedBookBalance += ( $recordSet['norm_c2'] == 'DR'? ( $recordSet['debit'] - $recordSet['credit'] ) : ( $recordSet['credit'] - $recordSet['debit'] ) );
        }
        foreach( $bookCurrentBalance as $recordSet ){
            $unadjustedBookBalance += ( $recordSet['norm_c2'] == 'DR'? ( $recordSet['debit'] - $recordSet['credit'] ) : ( $recordSet['credit'] - $recordSet['debit'] ) );
        }
        /* end of computing unadjusted book balance */

        /* process account balance retrieving and computation */
        $bankAccountBalance                     = 0;
        
        if( _checkData( 
            array(
                'table'     => 'bankrecon'
				,'field'    => 'idBankAccount'
				,'value'    => $params['idBankAccount']
            )
        ) ){
            $bankAccountBalance = $this->model->getBankAccountBalanceRecon( $params, _checkData(
                array(
                    'table'     => 'bankrecon'
                    ,'field'    => 'idBankAccount'
                    ,'value'    => $params['idBankAccount']
                    ,'exwhere'  => "reconMonth = '$params[reconMonth]' AND reconYear = '$params[reconYear]' AND archived NOT IN( 1 )"
                )
            ) );
        }
        else{
            /* get bank account beginning balance */
            $bankAccountBeginningBalance            = $this->model->getAccountBeginningBalance( $params );
            if( (int)$params['reconMonth'] == 1 ){
                $params['monthPrev'] = 12;
                $params['yearPrev'] = ( (int)$params['reconYear'] - 1 );
            }
            else{
                $params['monthPrev'] = ( (int)$params['reconMonth'] - 1 );
                $params['yearPrev'] = (int)$params['reconYear'];
            }
            /* get all previous months postdated disbursement */
            $bankAccountTotalClearedDisbursements   = $this->model->getBankAccountTotalClearedDisbursements( $params );
            /* get all previous months postdated receipts */
            $bankAccountTotalClearedReceipts        = $this->model->getBankAccountTotalClearedReceipts( $params );

            $bankAccountBalance = ( $bankAccountBeginningBalance + $bankAccountTotalClearedReceipts ) - $bankAccountTotalClearedDisbursements;
        }
        /* end of computing account balance */

        die(
            json_encode(
                array(
                    'success'                   => true
                    ,'bankAccountBalance'       => $bankAccountBalance
                    ,'unadjustedBookBalance'    => $unadjustedBookBalance
                )
            )
        );
    }

    public function saveRecord(){
        $params                 = getData();
        $idBankRecon            = (int)$params['idBankRecon'];
        $params['reconDate']    = date( 'Y-m-d', strtotime( $params['tdate'] ) ) . ' ' . date( 'H:i:s', strtotime( $params['ttime'] ) );
        $cancelTag              = ( isset( $params['canceTag'] )? (int)$params['cancelTag'] : 0  );

        /* first check if reference number already exists */
        if( _checkData(
            array(
                'table'     => 'bankrecon'
                ,'field'    => 'referenceNum'
                ,'value'    => (int)$params['referenceNum']
                ,'exwhere'  => "idBankRecon NOT IN( $params[idBankRecon] ) AND idReference = $params[idReference] AND idAffiliate = $this->AFFILIATEID"
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

        /* if record editing */
        if( $idBankRecon > 0 ){
            /* first check if record still exists */
            if( !_checkData(
                array(
                    'table'     => 'bankrecon'
                    ,'field'    => 'idBankRecon'
                    ,'value'    => $idBankRecon
                    ,'exwhere'  => 'archived NOT IN( 1 )'
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

            /* check if record is modified by other user */
            if( $params['modify'] == 0 ){
                $dateModified = $this->standards->getDateModified( $params['idBankRecon'], 'idBankRecon', 'bankrecon' );
                if( $params['dateModified'] != $dateModified->dateModified ){
                    die(
                        json_encode(
                            array(
                                'success'   => true
                                ,'match'    => 3
                            )
                        )
                    );
                }
            }
        }

        /* check if there are bank recon recorded after the said month and year selected */
        if( _checkData(
            array(
                'table'     => 'bankrecon'
                ,'field'    => 'idBankAccount'
                ,'value'    => $params['idBankAccount']
                ,'exwhere'  => "reconYear > $params[reconYear] AND reconMonth > $params[reconMonth] AND idBankRecon NOT IN( $params[idBankRecon] ) AND archived NOT IN(1) AND idAffiliate = $this->AFFILIATEID"
            )
        ) ){
            $curRec     = $this->model->getLatestMonth( $params );
            die(
                json_encode(
                    array(
                        'success'   => true
                        ,'match'    => 4
                        ,'monDis'   => ( count( (array)$curRec ) > 0? $curRec['monDis'] : '' )
                    )
                )
            );
        }

        /* check if there are existing bank recon recorded on the selected month and year */
        if( _checkData(
            array(
                'table'     => 'bankrecon'
                ,'field'    => 'idBankAccount'
                ,'value'    => $params['idBankAccount']   
                ,'exwhere'  => "reconYear = $params[reconYear] AND reconMonth = $params[reconMonth] AND idBankRecon NOT IN( $params[idBankRecon] ) AND archived NOT IN( 1 ) AND idAffiliate = $this->AFFILIATEID"
            )
        ) ){
            die(
                json_encode(
                    array(
                        'success'   => true
                        ,'match'    => 5
                    )
                )
            );
        }

        $this->db->trans_begin();
        if( $cancelTag == 1 ) $params['cancelledBy']     = $this->USERID;
        $params['idBankRecon']          = $this->model->saveForm( $params );
        $params['idBankReconHistory']   = $this->model->saveFormHistory( $params );

        /* first delete all related records */
        $this->model->deleteRelatedRecords( $params );

        $bankreconadjustment    = json_decode( $params['bankreconadjustment'], true );
        for( $i = 0; $i < count( $bankreconadjustment ); $i++ ){
            $bankreconadjustment[$i]['idBankRecon']         = $params['idBankRecon'];
            $bankreconadjustment[$i]['idBankReconHistory']  = $params['idBankReconHistory'];
            if( floatval( $bankreconadjustment[$i]['addAmount'] ) > 0 ) $bankreconadjustment[$i]['amount']      = $bankreconadjustment[$i]['addAmount'];
            elseif( floatval( $bankreconadjustment[$i]['lessAmount'] ) > 0 ) $bankreconadjustment[$i]['amount'] = ( $bankreconadjustment[$i]['lessAmount'] * -1 );
        }

        /* save bankreconadjustment records( coming from adjustment field in the module(pop out window) ) */
        if( count( (array)$bankreconadjustment ) > 0 ){
            $this->model->saveBankreconadjustment( $bankreconadjustment );
            $this->model->saveBankreconadjustmenthistory( $bankreconadjustment );
        }

        $adjusted   = json_decode( $params['adjusted'], true );
        for( $i = 0; $i < count( $adjusted ); $i++ ){
            $adjusted[$i]['idBankRecon']        = $params['idBankRecon'];
            $adjusted[$i]['idBankReconHistory'] = $params['idBankReconHistory'];
        }

        /* save adjusted records( unrecorded book receipts/disbursements( grids at the right side of the module ) ) */
        if( count( (array)$adjusted ) > 0 ){
            $this->model->saveAdjusted( $adjusted );
            $this->model->saveAdjustedHistory( $adjusted );
        }

        if( $cancelTag == 0 ){
            $postdated  = json_decode( $params['postdated'], true );
            for( $i = 0; $i < count( $postdated ); $i++ ){
                $postdated[$i]['idBankRecon']   = $params['idBankRecon'];
                $this->model->updatePostdated( $postdated[$i] );
            }
        }

        $posting    = json_decode( $params['posting'], true );
        for( $i = 0; $i < count( $posting ); $i++ ){
            $posting[$i]['idBankRecon']         = $params['idBankRecon'];
            $posting[$i]['idBankReconHistory']  = $params['idBankReconHistory'];
        }
        
        /* save posting record */
        if( count( (array)$posting ) > 0 ){
            $this->model->savePosting( $posting );
            $this->model->savePostingHistory( $posting );
        }

        $this->setLogs( $params );
        $success    = $this->db->trans_status();
        if( $success ) $this->db->trans_commit();
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

    public function getAdjusted(){
        $params     = getData();
        $view       = $this->model->getAdjusted( $params );

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    public function getAdjusted2(){
        $params     = getData();
        $view       = $this->model->getAdjusted( $params );

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
        $match      = 0;
        $params     = getData();
        if( !_checkData(
            array(
                'table'     => 'bankrecon'
                ,'field'    => 'idBankRecon'
                ,'value'    => $params['idBankRecon']
                ,'exwhere'  => 'archived NOT IN( 1 )'
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

        /* check if there is a closing entry made of the record month and year */
        if( _checkData(
            array(
                'table'     => 'invoices'
                ,'field'    => 'idModule'
                ,'value'    => 35  /* closing entry */
                ,'exwhere'  => "month = $params[reconMonth] AND year = $params[reconYear] AND archived NOT IN( 1 )"
            )
        ) ){
            $match = 2;
        }

        $view       = $this->model->retrieveData( $params );
        if( count( (array)$view ) > 0 ){
            /* get adjustment records */
            $view[0]['totalAdjustment']     = 0;
            $view[0]['bankreconadjustment'] = $this->model->getBankreconAdjustment( $params );
            for( $i = 0; $i < count( $view[0]['bankreconadjustment'] ); $i++ ){
                $view[0]['totalAdjustment'] += ( $view[0]['bankreconadjustment'][$i]['addAmount'] - $view[0]['bankreconadjustment'][$i]['lessAmount'] );
            }
            if( (int)$view[0]['cancelTag'] == 1 ) $match = 2;
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
        $params     = getData();

        if( !_checkData(
            array(
                'table'     => 'bankrecon'
                ,'field'    => 'idBankRecon'
                ,'value'    => $params['idBankRecon']
                ,'exwhere'  => 'archived NOT IN( 1 )'
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

        if( _checkData(
            array(
                'table'     => 'invoices'
                ,'field'    => 'idModule'
                ,'value'    => 35  /* closing entry */
                ,'exwhere'  => "month = $params[reconMonth] AND year = $params[reconYear] AND archived NOT IN( 1 )"
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

        if( _checkData(
            array(
                'table'     => 'bankrecon'
                ,'field'    => 'idBankAccount'
                ,'value'    => $params['idBankAccount']
                ,'exwhere'  => "reconYear > $params[reconYear] AND reconMonth > $params[reconMonth] AND idBankRecon NOT IN( $params[idBankRecon] ) AND archived NOT IN(1) AND idAffiliate = $this->AFFILIATEID"
            )
        ) ){
            die(
                json_encode(
                    array(
                        'success'   => true
                        ,'match'    => 3
                    )
                )
            );
        }

        $this->db->trans_begin();
        /* untaged postdated records */
        $this->model->untagPostdated( $params );
        /* archive record */
        $this->model->archiveRecord( $params );

        $params['delete']   = true;
        $this->setLogs( $params );

        $success    = $this->db->trans_status();

        if( $success ) $this->db->trans_commit();
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

    public function generatePDF(){
        $params                 = getData();

        $TOP    = '<table cellpadding = "5">
					<tr>
						<td style = "width:50%">
							<table border = "0" style="font-size:1em;font-family:Arial, sans-serif; align:center;">
								<tr>
									<td style = "width:50%"><strong>Reference : </strong></td>
									<td style = "width:50%">' . $params['pdf_idReference'] . ' - ' . $params['pdf_referenceNum'] . '</td>
								</tr>
								<tr>
									<td style = "width:50%"><strong>Cost Center : </strong></td>
									<td style = "width:50%">'. $params['pdf_idCostCenter'].'</td>
								</tr>
								<tr>
									<td style = "width:50%"><strong>Reconciliation For the Month of : </strong></td>
									<td style = "width:50%">' . $params['pdf_reconMonth'] . ' <strong>Year :  </strong>' . $params['pdf_reconYear'] . '</td>
                                </tr>
                                <tr>
									<td style = "width:50%"><strong>Bank : </strong></td>
									<td style = "width:50%">' . $params['pdf_idBank'] . '</td>
								</tr>
                                <tr>
									<td style = "width:50%"><strong>Bank Account : </strong></td>
									<td style = "width:50%">' . $params['pdf_idBankAccount'] . '</td>
								</tr>
                                <tr>
									<td style = "width:50%"><strong>Account Code : </strong></td>
									<td style = "width:50%">' . $params['pdf_bankAccountCode'] . '</td>
								</tr>
							</table>
						</td>
						<td style = "width:50%">
							<table border = "0" style="font-size:1em;font-family:Arial, sans-serif; align:center;">
								<tr>
									<td style = "width:50%"><strong>Date : </strong></td>
									<td style = "width:50%">' . $params['pdf_tdate'] . ' ' . date('h:i a', strtotime( $params['pdf_ttime'] ) ) .'</td>
                                </tr>
                                <tr>
									<td style = "width:50%"><strong>Account Description : </strong></td>
									<td style = "width:50%">'. $params['pdf_bankAccountDescription'].'</td>
								</tr>
                                <tr>
									<td style = "width:50%"><strong>Account Balance : </strong></td>
									<td style = "width:50%">'. number_format( $params['pdf_bankAccountBalance'], 2 ).'</td>
                                </tr>
                                <tr>
									<td style = "width:50%"><strong>Remarks : </strong></td>
									<td style = "width:50%">'. $params['pdf_remark'].'</td>
								</tr>
							</table>
						</td>
					</tr>
                </table><br/>';

        $mainParams = array(
            'title'             => 'Bank Reconciliation Form'
            ,'noTitle'          => true
            ,'file_name'        => 'Bank Reconciliation Form'
            ,'folder_name'      => 'pdf/accounting/'
            ,'grid_font_size'   => 8
            ,'table_hidden'     => true
        );
        
        /*  $params['printJE']
            1 = No JE
            2 = With JE
            3 = JE Only */
        $params['adjustedTag']  = 1;
        $adjustedAdd            = ( (int)$params['printJE'] <= 2 )? $this->model->getAdjusted( $params ) : array();
        $params['adjustedTag']  = 2;
        $adjustedLess           = ( (int)$params['printJE'] <= 2 )? $this->model->getAdjusted( $params ) : array();
        $params['pdf']          = true;
        $receipts               = ( (int)$params['printJE'] <= 2 )? $this->model->getReceipts( $params ) : array();
        $disbursements          = ( (int)$params['printJE'] <= 2 )? $this->model->getDisbursements( $params ) : array();
        $adjustments            = ( (int)$params['printJE'] <= 2 )? $this->model->getBankreconAdjustment( $params ) : array();
        $journalEntry           = ( (int)$params['printJE'] >= 2 )? $this->standards->gridJournalEntry( $params ) : array();
        $tblRecordBody          = '';
        if( count( (array)$adjustedAdd ) > 0
            || count( (array)$$adjustedLess ) > 0
                || count( (array)$receipts )
                    || count( (array)$disbursements ) ){
            $tblRecordBody    .= '
                                        <tr>
                                            <td style = "width:50%"></td>
                                            <td style = "width:50%"></td>
                                        </tr>
                                        <tr>
                                            <td style = "width:50%">&nbsp;&nbsp;<strong>Add : </strong></td>
                                            <td style = "width:50%">&nbsp;&nbsp;<strong>Unrecorded Receipts(Add) : </strong></td>
                                        </tr>
                                        <tr>
                                            <td style = "width:50%">
                                                <table border="1" cellpadding="5">
                                                    <tr>
                                                        <th style = "width:20%; text-align: center;">Ref #</th>
                                                        <th style = "width:30%; text-align: center;">Description</th>
                                                        <th style = "width:25%; text-align: center;">Date</th>
                                                        <th style = "width:25%; text-align: center;">Amount</th>
                                                    </tr>';
            $totalReceipts = 0;
            foreach( $receipts as $recordSet ){
                $tblRecordBody  .=  '
                                                    <tr>
                                                        <td style = "width:20%">' . $recordSet['reference'] . '</td>
                                                        <td style = "width:30%">' . $recordSet['description'] . '</td>
                                                        <td style = "width:25%; text-align: right;">' . date( 'm/d/Y', strtotime( $recordSet['date'] ) ) . '</td>
                                                        <td style = "width:25%; text-align: right;">' . number_format( $recordSet['amount'], 2 ) . '</td>
                                                    </tr>
                ';
                $totalReceipts += $recordSet['amount'];
            }
            $tblRecordBody    .=  '             </table>
                                            </td>
                                            <td style = "width:50%">
                                                <table border="1" cellpadding="5">
                                                    <tr>
                                                        <th style = "width:30%">Description</th>
                                                        <th style = "width:70%">Amount</th>
                                                    </tr>';
            $totalAdjustedAdd = 0;
            foreach( $adjustedAdd as $recordSet ){
                $tblRecordBody  .= '
                                                    <tr>
                                                        <td style = "width:30%">' . $recordSet['description'] . '</td>
                                                        <td style = "width:70%; text-align: right;">' . number_format( $recordSet['amount'], 2 ) . '</td>
                                                    </tr>
                ';
                $totalAdjustedAdd   += $recordSet['amount'];
            }
            $tblRecordBody    .=                '</table>
                                            </td>
                                        </tr>
        ';

            $tblRecordBody    .= '
                                        <tr>
                                            <td style = "width:50%"></td>
                                            <td style = "width:50%"></td>
                                        </tr>
                                        <tr>
                                            <td style = "width:50%">&nbsp;&nbsp;<strong>Less : </strong></td>
                                            <td style = "width:50%">&nbsp;&nbsp;<strong>Unrecorded Disbursements(Less) : </strong></td>
                                        </tr>
                                        <tr>
                                            <td style = "width:50%">
                                                <table border="1" cellpadding="5">
                                                    <tr>
                                                        <th style = "width:20%; text-align: center;">Ref #</th>
                                                        <th style = "width:30%; text-align: center;">Description</th>
                                                        <th style = "width:25%; text-align: center;">Date</th>
                                                        <th style = "width:25%; text-align: center;">Amount</th>
                                                    </tr>';
                $totalDisbursements = 0;
                foreach( $disbursements as $recordSet ){
                    $tblRecordBody  .=  '
                                                    <tr>
                                                        <td style = "width:20%">' . $recordSet['reference'] . '</td>
                                                        <td style = "width:30%">' . $recordSet['description'] . '</td>
                                                        <td style = "width:25%; text-align: right;">' . date( 'm/d/Y', strtotime( $recordSet['date'] ) ) . '</td>
                                                        <td style = "width:25%; text-align: right;">' . number_format( $recordSet['amount'], 2 ) . '</td>
                                                    </tr>
                    ';
                    $totalDisbursements += $recordSet['amount'];
                }
                $tblRecordBody    .=  '         </table>
                                            </td>
                                            <td style = "width:50%">
                                                <table border="1" cellpadding="10">
                                                    <tr>
                                                        <th style = "width:30%">Description</th>
                                                        <th style = "width:70%">Amount</th>
                                                    </tr>';
                $totalAdjustedLess = 0;
                foreach( $adjustedLess as $recordSet ){
                    $tblRecordBody  .= '
                                                    <tr>
                                                        <td style = "width:30%">' . $recordSet['description'] . '</td>
                                                        <td style = "width:70%; text-align: right;">' . number_format( $recordSet['amount'], 2 ) . '</td>
                                                    </tr>
                    ';
                    $totalAdjustedLess   += $recordSet['amount'];
                }
                $tblRecordBody    .= '          </table>
                                            </td>
                                        </tr>
            ';
            $tblRecordBody          .= '
                                        <tr>
                                            <td style = "width:50%">
                                                <table>
                                                    <tr>
                                                        <td style = "width:30%"><strong>Total : </strong></td>
                                                        <td style = "width:70%; text-align: right;">' . number_format( ( $totalReceipts - $totalDisbursements ), 2 ) . '</td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td style = "width:50%">
                                                <table>
                                                    <tr>
                                                        <td style = "width:30%"><strong>Total : </strong></td>
                                                        <td style = "width:70%; text-align: right;">' . number_format( ( $totalAdjustedAdd - $totalAdjustedLess ), 2 ) . '</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
            ';
        $ExtableBottom      = '<br/>
                                <table>
                                    <tr>
                                        <td style = "width:50%">
                                            <table>
                                                <tr>
                                                    <td style = "width:40%"><strong>Unadjusted Bank Balance : </strong></td>
                                                    <td style = "width:60%; text-align: right;">' . number_format( $params['pdf_unAdjustedBankBalance'], 2 ) . '</td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td style = "width:50%">
                                            <table>
                                                <tr>
                                                    <td style = "width:40%"><strong>Unadjusted Book Balance : </strong></td>
                                                    <td style = "width:60%; text-align: right;">' . number_format( $params['pdf_unAdjustedBookBalance'], 2 ) . '</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    ' . $tblRecordBody . '
                                </table>';
        }

        if( count( (array)$adjustments ) > 0 ){
            $ExtableBottom  .= '
                </table>
                <br/>
                <br/>
                <br/>
                <strong>Adjustments:</strong>
                <br/>
                <table border="1" cellpadding="5">
                    <tr>
                        <th style="width:20%;; text-align: center;">Date</th>
                        <th style="width:30%;; text-align: center;">Description</th>
                        <th style="width:25%;; text-align: center;">Add</th>
                        <th style="width:25%;; text-align: center;">Less</th>
                    </tr>
            ';
            $totalAdd = 0;
            $totalLess = 0;
            foreach( $adjustments as $recordSet ){
                $ExtableBottom  .= '
                    <tr>
                        <td>' . date( 'm/d/Y', strtotime( $recordSet['date'] ) ) . '</td>
                        <td>' . $recordSet['description'] . '</td>
                        <td style="text-align: right;">' . number_format( $recordSet['addAmount'], 2 ) . '</td>
                        <td style="text-align: right;">' . number_format( $recordSet['lessAmount'], 2 ) . '</td>
                    </tr>
                ';
                $totalAdd += $recordSet['addAmount'];
                $totalLess += $recordSet['lessAmount'];
            }
            $ExtableBottom  .= '
                    <tr>
                        <td style="width:50%;" colspan="2"><strong>TOTAL</strong></td>
                        <td style="width:25%;text-align:right;">' . number_format( $totalAdd, 2 ) . '</td>
                        <td style="width:25%;text-align:right;">' . number_format( $totalLess, 2 ) . '</td>
                    </tr>
                    <tr>
                        <td style="width:75%;" colspan="3"><strong>Adjustment:</strong></td>
                        <td style="width:25%;text-align:right;">' . number_format( ( $totalAdd - $totalLess ), 2 ) . '</td>
                    </tr>
                ';
            $ExtableBottom  .= '
                </table>';
        }

        if( $tblRecordBody != '' ){
            $ExtableBottom .= '
                <br/>
                <br/>
                <table cellpadding="5">
                    <tr>
                        <td style = "width:50%">
                            <table>
                                <tr>
                                    <td style = "width:40%"><strong>Adjusted Bank Balance : </strong></td>
                                    <td style = "width:60%; text-align: right;">'. number_format( $params['pdf_adjustedBankBalance'], 2 ) .'</td>
                                </tr>
                            </table>
                        </td>
                        <td style = "width:50%">
                            <table>
                                <tr>
                                    <td style = "width:40%"><strong>Adjusted Book Balance : </strong></td>
                                    <td style = "width:60%; text-align: right;">'. number_format( $params['pdf_adjustedbookbal'], 2 ) .'</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>';
        }

        if( count( (array)$journalEntry ) > 0 ){
            $ExtableBottom  .= '
                <br/>
                <br/>
                <strong>Journal Entries:</strong>
                <br/>
                <table border="1" cellpadding="5">
                    <tr>
                        <th style="width:15%; text-align: center;">Code</th>
                        <th style="width:20%; text-align: center;">Name</th>
                        <th style="width:30%; text-align: center;">Explanation</th>
                        <th style="width:15%; text-align: center;">Cost Center</th>
                        <th style="width:10%; text-align: center;">Debit</th>
                        <th style="width:10%; text-align: center;">Credit</th>
                    </tr>';
            $totalDebit = 0;
            $totalCredit = 0;
            foreach( $journalEntry as $recordSet ){
                $ExtableBottom  .= '
                    <tr>
                        <td style="width:15%;">' . $recordSet['code'] . '</td>
                        <td style="width:20%;">' . $recordSet['name'] . '</td>
                        <td style="width:30%;">' . $recordSet['explanation'] . '</td>
                        <td style="width:15%;">' . $recordSet['costCenterName'] . '</td>
                        <td style="width:10%; text-align: right;">' . number_format( $recordSet['debit'], 2 ) . '</td>
                        <td style="width:10%; text-align: right;">' . number_format( $recordSet['credit'], 2 ) . '</td>
                    </tr>
                ';
                $totalDebit += $recordSet['debit'];
                $totalCredit += $recordSet['credit'];
            }
            $ExtableBottom  .= '
                    <tr>
                        <td style="width:80%;" colspan="4"><strong>Total : </strong></td>
                        <td style="width:10%; text-align: right;">' . number_format( $totalDebit, 2 ) . '</td>
                        <td style="width:10%; text-align: right;">' . number_format( $totalCredit, 2 ) . '</td>
                    </tr>
                </table>
            ';
        }

        generate_table( $mainParams, array(), array(), $TOP, $ExtableBottom );
    }

    public function getReferences(){
        $params = getData();
        $view   = $this->model->getReferences( $params );
        if( !isset( $params['query'] ) ) array_unshift(
            $view
            ,array(
                'id'     => 0
                ,'name'    => 'All'
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

    private function setLogs( $params ){
		$header = $this->USERFULLNAME;
		$action = '';
		
		if( isset( $params['deleting'] ) ){
			$action = 'deleted a transaction';
		}
		else{
			if( isset( $params['action'] ) )
				$action = $params['action'];
			else
				$action = ( $params['onEdit'] == 1  ? 'edited a transaction' : 'added a bank reconciliation transaction' );
        }
        $params['actionLogDescription'] = $header . ' ' . $action . '.';
		
		setLogs( $params );
	}

}