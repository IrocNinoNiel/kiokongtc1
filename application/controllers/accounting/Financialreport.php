<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Jayson Dagulo
 * Module       : Financial Report
 * Date         : Jan 30, 2019
 * Finished     : 
 * Description  : This module allows authorized user to manually closes the journal entries.
 * DB Tables    : 
 * */ 

class Financialreport extends CI_Controller {

    public function __construct(){
        parent::__construct();
        setHeader( 'accounting/Financialreport_model' );
    }

    public function generateReport(){
        $params             = getData();
        $idAffiliate        = $params['idAffiliate'];
        $affiliateAddress   = '';
        if( (int)$params['idAffiliate'] == 0 ){ /* for non consolidated report */
            /* first get main affiliate id */
            $idAffiliate    = $this->model->getMainAffiliate();
        }
        /* check affiliate date start */;
        $params['startDate']          = $this->model->getAffiliateInfo( $idAffiliate, 'dateStart' );
        $affiliateAddress   = $this->model->getAffiliateInfo( $idAffiliate, 'address' );
        if( $params['startDate'] ){
            if( (int)$params['year'] <= date( 'Y', strtotime( $params['startDate'] ) ) && (int)$params['month'] < date( 'm', strtotime( $params['startDate'] ) ) ){
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
        /* check previous closing entry if not tagged as final */
        if( _checkData(
            array(
                'table'     => 'invoices'
                ,'field'    => 'idModule'
                ,'value'    => 35
                ,'exwhere'  => "archived NOT IN( 1 ) AND month < $params[month] AND year <= $params[year] AND idAffiliate = $idAffiliate AND status NOT IN( 2 )"
            )
        ) ){
            die(
                json_encode(
                    array(
                        'success'       => true
                        ,'match'        => 3
                    )
                )
            );
        }
        /* first check if there are closing entry for the month */
        if( !_checkData(
            array(
                'table'     => 'invoices'
                ,'field'    => 'idModule'
                ,'value'    => 35
                ,'exwhere'  => "archived NOT IN( 1 ) AND idAffiliate = $idAffiliate AND month = $params[month] AND year = $params[year]"
            )
        ) ){
            die(
                json_encode(
                    array(
                        'success'       => true
                        ,'match'        => 1
                    )
                )
            );
        }

        $data           = array();
        /* evaluate parameters for the selected type of report(widely used for reports other than trial balance)
         * this function  would return an array with dates for:
         * 1 = previous year date range
         * 2 = previous year accumulated date range( accounting schedule start date up to selected month in the module )
         * 3 * current year accumulated date range( accountin schedule start date up to selected month in the module )
         * function could be found @ application/helpers/myfunction_helper.php
         */
        $evalParams     = _evaluateFSParams( $params, $idAffiliate );

		$evalParams['h1']   = $evalParams['affiliateName'];
		$evalParams['h2']   = $affiliateAddress;
        $evalParams['date'] = 'As of ' . date( 'F Y', strtotime( $params['year'] . '-' . $params['month'] . '-1' ) );
        $view = '';
        switch( (int)$evalParams['reportType'] ){
            case 1: /* Trial Balance Process */
                /* all accounts are included here */
                /* its primary purpose is for first level checking, to check if journal entries inputted by the user are balance
                 * which means Total Debit should be equal to Total Credit
                 * Records retrieved are all coming from Closing entry( to optimize report generation )
                 */
                $trialBalanceData           = $this->model->processTrialBalance( $evalParams );
                $evalParams['array_Rows']   = $trialBalanceData;
                
                /* _createTrialBalanceView will return a @string
                 * HTML view of the report
                 * function could be found @ application/helpers/myfunction_helper.php
                 */
                $view   = _createTrialBalanceView( $evalParams );
                break;
            case 2: /* Income Statement */
                /* this only includes 4 = Revenue accounts, 5 = Expenses accounts, and provision for income tax(Default accounts)
                 * this report here shows the financial position of the company for the period selected.
                 */
                $incomeStatement            = $this->model->processIncomeStatement( $evalParams );
                $evalParams['array_Rows']   = $incomeStatement;
                
                /* _createIncomeStatementView will return a @string
                 * HTML view of the report
                 * function could be found @ application/helpers/myfunction_helper.php
                 */
                $view   = _createIncomeStatementView( $evalParams );
                break;
            case 3: /* Balance Sheet */
                /* this only includes 1 = Asset accounts, 2 = Liabilities accounts, and 3 - Owners Equity
                 * this provides a basis for computing rates of return and evaluating its capital structure.
                 */
                $balanceSheet               = $this->model->processBalanceSheet( $evalParams );
                $evalParams['array_Rows']   = $balanceSheet;
                
                /* _createBalanceSheetView will return a @string
                 * HTML view of the report
                 * function could be found @ application/helpers/myfunction_helper.php
                 */
                $view                       = _createBalanceSheetView( $evalParams );
                break;
            case 4: /* Cash Flow */
                /* this only includes 1 = Asset accounts, 2 = Liabilities accounts, and 3 - Owners Equity
                 * this provides a basis for computing rates of return and evaluating its capital structure.
                 */
                $cashFlow                   = $this->model->processCashFlow( $evalParams );
                $evalParams['arrayRows']    = $cashFlow;
                // var_dump( $cashFlow );
                
                $view                       = _createCashFlowView( $evalParams );
                break;
        }
        
        $pdf    = ( isset( $params['pdf'] )? true : false );
        $excel  = ( isset( $params['excel'] )? true : false );

        if( $pdf ){
            $this->generatePDF( $evalParams, $view );
        }
        elseif( $excel ){
            $this->generateExcel( $evalParams );
        }
        else{
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
    }

    public function generatePDF( $params, $view ){
        $mainParams = array(
            'title'             => $params['title']
            ,'noTitle'          => true
            ,'file_name'        => $params['title']
            ,'folder_name'      => 'pdf/accounting/'
            ,'grid_font_size'   => 8
            ,'table_hidden'     => true
            ,'noLogoTitle'      => true
        );
        generate_table( $mainParams, array(), array(), '', $view );
    }

    public function viewPDF( $title ){
		viewPDF(
			array(
				'file_name' => $title
				,'folder_name' => 'accounting'
			)
		);
	}

    public function generateExcel( $params ){
        $csvarray = array();
		
        $csvarray[] = array( $params['h1'] );
        $csvarray[] = array( $params['h2'] );
        $csvarray[] = array( 'space' => '' );
        $csvarray[] = array( 'space' => '' );
        $csvarray[] = array( $params['title'] );
        $csvarray[] = array( $params['date'] );
        
        switch( $params['reportType'] ){
            case 1: /* trial balance */
                $csvarray[] = array(
                    'Code'
                    ,'Account Titles'
                    ,'DR'
                    ,'CR'
                );

                $totalDR = 0;
                $totalCR = 0;
                foreach( $params['array_Rows'] as $rs ){
                    $csvarray[] = array(
                        $rs['acod_c15']
                        ,$rs['aname_c30']
                        ,( $rs['debit'] != 0? $rs['debit'] : '' )
                        ,( $rs['credit'] != 0? $rs['credit'] : '' )
                    );
                    $totalDR += $rs['debit'];
                    $totalCR += $rs['credit'];
                }

                $csvarray[] = array(
                    ''
                    ,'TOTAL'
                    ,$totalDR
                    ,$totalCR
                );
                break;
            case 2: /* income statement */
                $csvarray[] = array(
                    ''
                    ,''
                    ,( count( $params ) > 0? date( 'M Y', strtotime( $params['prevSDate'] ) ) . ' - ' . date( 'M Y', strtotime( $params['prevEDate'] ) ) : '' )
                    ,( count( $params ) > 0? date( 'M Y', strtotime( $params['prevAccSDate'] ) ) . ' - ' . date( 'M Y', strtotime( $params['prevAccEDate'] ) ) : '' )
                    ,( count( $params ) > 0? date( 'M Y', strtotime( $params['curAccSDate'] ) ) . ' - ' . date( 'M Y', strtotime( $params['curAccEDate'] ) ) : '' )
                    ,( count( $params ) > 0? date( 'M Y', strtotime( $params['year'] . '-' . $params['month'] . '-1' ) ) : '' )
                    ,'INC/DEC'
                );
                $csvarray[] = array(
                    ''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                );
                foreach( $params['array_Rows'] as $rs ){
                    $previousYrGLAmount = ( $rs['previousYrGLAmount'] != '' ? ( round($rs['previousYrGLAmount'],2) >= 0 ? number_format( abs( $rs['previousYrGLAmount'] ), 2 ) : "(".number_format( abs( $rs['previousYrGLAmount'] ), 2 ).")" ) : '' );
                    $previousYrAccumulatedGLAmount = ( $rs['previousYrAccumulatedGLAmount'] != '' ? ( round($rs['previousYrAccumulatedGLAmount'],2) >= 0 ? number_format( abs( $rs['previousYrAccumulatedGLAmount'] ), 2 ) : "(".number_format( abs( $rs['previousYrAccumulatedGLAmount'] ), 2 ).")") : '' );
                    $currentYrAccumulatedGLAmount = ( $rs['currentYrAccumulatedGLAmount'] != '' ? ( round($rs['currentYrAccumulatedGLAmount'],2) >= 0 ? number_format( abs( $rs['currentYrAccumulatedGLAmount'] ), 2 ) : "(".number_format( abs( $rs['currentYrAccumulatedGLAmount'] ), 2 ).")") : '' );
                    $currentMonthGLAmount = ( $rs['currentMonthGLAmount'] != '' ? ( round($rs['currentMonthGLAmount'],2) >= 0 ? number_format( abs( $rs['currentMonthGLAmount'] ), 2 ) : "(".number_format( abs( $rs['currentMonthGLAmount'] ), 2 ).")") : '' );
                    $incDec = ( $rs['incDec'] != '' ? ( round($rs['incDec'],2) >= 0 ? number_format( abs( $rs['incDec'] ), 2 ) : "(".number_format( abs( $rs['incDec'] ), 2 ).")" ) : '' );
                    if( $rs['sorter'] == 1.1
					|| $rs['sorter'] == 1.4
						|| $rs['sorter'] == 3.1
							|| $rs['sorter'] == 3.2
								|| $rs['sorter'] == 4.1
									|| $rs['sorter'] == 5.0
										|| $rs['sorter'] == 6.0 ){
                        $csvarray[] = array(
                            $rs['aname_c30']
                            ,''
                            ,$previousYrGLAmount
                            ,$previousYrAccumulatedGLAmount
                            ,$currentYrAccumulatedGLAmount
                            ,$currentMonthGLAmount
                            ,$incDec
                        );
                    }
                    else{
                        if( $rs['sorter'] == 0 || $rs['sorter'] == 1.1 || $rs['sorter'] == 1.5 || $rs['sorter'] == 3.3 || $rs['sorter'] == 1.2 ){
                            $csvarray[] = array(
                                $rs['aname_c30']
                                ,''
                                ,$previousYrGLAmount
                                ,$previousYrAccumulatedGLAmount
                                ,$currentYrAccumulatedGLAmount
                                ,$currentMonthGLAmount
                                ,$incDec
                            );
                        }
                        else{
                            $csvarray[] = array(
                                ''
                                ,$rs['aname_c30']
                                ,$previousYrGLAmount
                                ,$previousYrAccumulatedGLAmount
                                ,$currentYrAccumulatedGLAmount
                                ,$currentMonthGLAmount
                                ,$incDec
                            );
                        }
                    }

                }
                break;
            case 3: /* balance sheet */
                $csvarray[] = array(
                    ''
                    ,''
                    ,date( 'M Y', strtotime( $params['prevEDate'] ) )
                    ,date( 'M Y', strtotime( $params['prevAccEDate'] ) )
                    ,date( 'M Y', strtotime( $params['curAccEDate'] ) )
                );
                $csvarray[] = array(
                    ''
                    ,''
                    ,''
                    ,''
                    ,''
                );
                foreach( $params['array_Rows'] as $data ){
                    $currMonthAmount = ( $data['currentMonthAmount'] != '' ? ( round($data['currentMonthAmount'],2) >= 0 ? number_format( abs( $data['currentMonthAmount'] ), 2 ) : "(".number_format( abs( $data['currentMonthAmount'] ), 2 ).")" ) : '' );
                    $curLastYearAmount = ( $data['prevYearCurrentMonth'] != '' ? ( round($data['prevYearCurrentMonth'],2) >= 0 ? number_format( abs( $data['prevYearCurrentMonth'] ), 2 ) : "(".number_format( abs( $data['prevYearCurrentMonth'] ), 2 ).")" ) : '' );
                    $currStartMonth = ( $data['currentYearStartMonth'] != '' ? ( round($data['currentYearStartMonth'],2) >= 0 ? number_format( abs( $data['currentYearStartMonth'] ), 2 ) : "(".number_format( abs( $data['currentYearStartMonth'] ), 2 ).")" ) : '' );
                    if( $data['sorter'] == 13.50
                        || $data['sorter'] == 23.50
                            || $data['sorter'] == 31.50 ){
                        $csvarray[] = array(
                            $data['aname_c30']
                            ,''
                            ,$currStartMonth
                            ,$curLastYearAmount
                            ,$currMonthAmount
                        );
                    }
                    elseif( $data['sorter'] == 0.05
                        || $data['sorter'] == 11.50
                            || $data['sorter'] == 12.50
                                || $data['sorter'] == 20.00
                                    || $data['sorter'] == 20.40
                                        || $data['sorter'] == 21.50
                                            || $data['sorter'] == 22.50
                                                || $data['sorter'] == 23.40
                                                    || $data['sorter'] == 30.40
                                                        || $data['sorter'] == 0.00 ){
                        $csvarray[] = array(
                            $data['aname_c30']
                            ,''
                            ,''
                            ,''
                            ,''
                        );
                    }
                    else{
                        $csvarray[] = array(
                            ''
                            ,$data['aname_c30']
                            ,$currStartMonth
                            ,$curLastYearAmount
                            ,$currMonthAmount
                        );
                    }
                }
                break;
            case 4: /* cash flow */
                $csvarray[] = array(
                    ''
                    ,''
                    ,date( 'Y', strtotime( $params['prevEDate'] ) )
                    ,date( 'Y', strtotime( $params['curEDate'] ) )
                );
                $csvarray[] = array(
                    ''
                    ,''
                    ,''
                    ,''
                );
                foreach( $params['array_Rows'] as $data ){
                    $prevBeginning	= $data['totalBeginning'];
                    $currentAmount	= ( $data['currentYearRecord'] >= 0? number_format( $data['currentYearRecord'], 2 ) : '(' . number_format( ( $data['currentYearRecord'] * -1 ), 2 ) . ')' );
                    $prevAmount		= ( $data['prevYearRecord'] >= 0? number_format( $data['prevYearRecord'], 2 ) : '(' . number_format( ( $data['prevYearRecord'] * -1 ), 2 ) . ')' );
                    if( $data['sorter'] == 1.2
                        || $data['sorter'] == 2.2
                            || $data['sorter'] == 3.2
                                || $data['sorter'] == 3.3 ){
                        $csvarray[]     = array(
                            $data['aname_c30']
                            ,''
                            ,$prevAmount
                            ,$currentAmount
                        );
                    }
                    elseif( $data['sorter'] == 0.0
                        || $data['sorter'] == 1.3
                            || $data['sorter'] == 2.3 ){
                        $csvarray[]     = array(
                            $data['aname_c30']
                            ,''
                            ,''
                            ,''
                        );
                    }
                    else{
                        if( $data['prevYearRecord'] > 0 || $data['currentYearRecord'] > 0 ){
                            $totalPrev		+= $data['prevYearRecord'];
                            $totalCurrent	+= $data['currentYearRecord'];
                            $csvarray[]     = array(
                                ''
                                ,$data['aname_c30']
                                ,$prevAmount
                                ,$currentAmount
                            );
                        }
                    }
                }
                $prevBeginning	= ( $prevBeginning >= 0? number_format( $prevBeginning, 2 ) : '(' . number_format( ( $prevBeginning * -1 ), 2 ) . ')' );
                $prevEnding		= ( $prevBeginning + $totalPrev );
                $prevEnding		= ( $prevEnding >= 0? number_format( $prevEnding, 2 ) : '(' . number_format( ( $prevEnding * -1 ), 2 ) . ')' );
                $currentEnding	= ( $prevBeginning + $totalPrev + $totalCurrent );
                $currentEnding	= ( $currentEnding >= 0? number_format( $currentEnding, 2 ) : '(' . number_format( ( $currentEnding * -1 ), 2 ) . ')' );
                $csvarray[]     = array(
                    'BEGINNING CASH AND CASH EQUIVALENTS'
                    ,''
                    ,$prevBeginning
                    ,$prevEnding
                );
                $csvarray[]     = array(
                    'ENDING - CASH AND CASH EQUIVALENTS'
                    ,''
                    ,$prevEnding
                    ,$currentEnding
                );
                break;
        }
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