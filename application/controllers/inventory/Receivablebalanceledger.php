<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Jays
 * Module       : Receivable Balances, Ledger and SOA
 * Date         : Feb. 12, 2020
 * Finished     : 
 * Description  : his module allows authorized user to generate and print the balances, 
 *                ledger and statement of account per customer on a specified date range.
 * DB Tables    : 
 * */ 
class Receivablebalanceledger extends CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->library( 'encryption' );
        setHeader( 'inventory/Receivablebalanceledger_model' );
    }

    public function getSalesReference(){
        $params     = getData();
        $viewAll    = $this->model->getSalesReference( $params );
        if( isset( $params['hasAll'] ) ){
            if( (int)$params['hasAll'] > 0 ){
                if( !isset( $params['query'] ) ){
                    array_unshift(
                        $viewAll
                        ,array(
                            'id'    => 0
                            ,'name' => 'All'
                        )
                    );
                }
            }
        }
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $viewAll
                    ,'total'    => count( $viewAll )
                )
            )
        );
    }

    public function getCustomers(){
        $params     = getData();
        $viewAll    = $this->model->getCustomers( $params );
        $viewAll    = decryptCustomer( $viewAll );
        
        if( isset( $params['hasAll'] ) ){
            if( (int)$params['hasAll'] > 0 ){
                if( !isset( $params['query'] ) ){
                    array_unshift(
                        $viewAll
                        ,array(
                            'id'    => 0
                            ,'name' => 'All'
                        )
                    );
                }
            }
        }
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $viewAll
                    ,'total'    => count( $viewAll )
                )
            )
        );
    }

    public function getReceivableBalances(){
        $params     = getData();
        $viewAll    = $this->model->getReceivableBalances( $params );
        
        $_viewHolder = $viewAll;
        foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['custSK'] ) && !empty( $po['custSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['custSK'] )));
                $_viewHolder[$idx]['customerName'] = $this->encryption->decrypt( $po['customerName'] );
            }
            if( isset( $po['affSK'] ) && !empty( $po['affSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['affSK'] )));
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
            }
        }
        $viewAll = $_viewHolder;

        // LQ();
        setLogs(
            array(
                'actionLogDescription'  => 'Generates Receivable Balance.'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            )
        );
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $viewAll
                    ,'total'    => count( $viewAll )
                )
            )
        );
    }

    public function getReceivableLedger(){
        $params     = getData();
        $viewAll    = $this->model->getReceivableLedger( $params );
        setLogs(
            array(
                'actionLogDescription'  => 'Generates Receivable Ledger.'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            )
        );
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $viewAll
                    ,'total'    => count( $viewAll )
                )
            )
        );
    }

    

    public function printPDF( $type = 'Balance' ){
        $data = getData();
        if($type == 'Balance'){
            $list = $this->model->getReceivableBalances( $data );

            $_viewHolder = $list;
            foreach( $_viewHolder as $idx => $po ){
                if( isset( $po['custSK'] ) && !empty( $po['custSK'] ) ){
                    $this->encryption->initialize( array( 'key' => generateSKED( $po['custSK'] )));
                    $_viewHolder[$idx]['customerName'] = $this->encryption->decrypt( $po['customerName'] );
                }
                if( isset( $po['affSK'] ) && !empty( $po['affSK'] ) ){
                    $this->encryption->initialize( array( 'key' => generateSKED( $po['affSK'] )));
                    $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
                }
            }
            $list = $_viewHolder;
            
            $params1 = array(
				array(   
					'header'        => 'Affiliate'
					,'dataIndex'    => 'affiliateName'
					,'width'        => '20%'
				)
				,array(  
					'header'        => 'Customer'
					,'dataIndex'    => 'customerName'
					,'width'        => '35%'
				)
				,array(  
					'header'        => 'Charges'
					,'dataIndex'    => 'chargesAmt'
					,'width'        => '15%'
					,'type'         => 'numbercolumn'
					,'format'       => '0,000.00'
				)
				,array(  
					'header'        => 'Payments'
					,'dataIndex'    => 'paymentAmt'
					,'width'        => '15%'
					,'type'         => 'numbercolumn'
					,'format'       => '0,000.00'
				)
				,array(  
					'header'        => 'Balance'
					,'dataIndex'    => 'balanceAmt'
					,'width'        => '15%'
					,'type'         => 'numbercolumn'
					,'format'       => '0,000.00'
					,'hasTotal'     => true
				)
			);
			
			$header_fields = array(
				array(
					array(
						'label'     => 'Affiliate'
						,'value'    => $data['pdf_idAffiliate']
					)
					,array(
						'label'     => 'Sales Reference'
						,'value'    => $data['pdf_idReference']
					)
					,array(
						'label'     => 'Customer Name'
						,'value'    => $data['pdf_idCustomer']
					)
					,array(
						'label'     => 'As Of'
						,'value'    => $data['pdf_date']
					)
				)
            );
            setLogs(
                array(
                    'actionLogDescription'  => 'Exported the generated Receivable Balance(PDF).'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            )
            );
        }
        else{
            $list = $this->model->getReceivableLedger( $data );
            $params1 = array(
				array(  
					'header'        => 'Date'
					,'dataIndex'    => 'date'
					,'width'        => '10%'
				)
				,array(  
					'header'        => 'Invoice No.'
					,'dataIndex'    => 'reference'
					,'width'        => '18%'
				)
				,array(  
					'header'        => 'Remarks'
					,'dataIndex'    => 'remarks'
					,'width'        => '27%'
				)
				,array(  
					'header'        => 'Charges'
					,'dataIndex'    => 'chargesAmt'
					,'width'        => '15%'
					,'type'         => 'numbercolumn'
					,'format'       => '0,000.00'
				)
				,array(  
					'header'        => 'Payments'
					,'dataIndex'    => 'paymentAmt'
					,'width'        => '15%'
					,'type'         => 'numbercolumn'
					,'format'       => '0,000.00'
				)
				,array(  
					'header'        => 'Balance'
					,'dataIndex'    => 'balanceAmt'
					,'width'        => '15%'
					,'type'         => 'numbercolumn'
					,'format'       => '0,000.00'
                    ,'hasTotal'     => true
                    ,'isRunning'    => true
				)
			);
			
			$header_fields = array(
				array(
					array(
						'label'     => 'Affiliate'
						,'value'    => $data['pdf_idAffiliate']
					)
					,array(
						'label'     => 'Sales Reference'
						,'value'    => $data['pdf_idReference']
					)
					,array(
						'label'     => 'Customer Name'
						,'value'    => $data['pdf_idCustomer']
					)
					,array(
						'label'     => 'Date'
						,'value'    => $data['pdf_sdate'] . ' To ' . $data['pdf_edate']
					)
				)
            );
            setLogs(
                array(
                    'actionLogDescription'  => 'Exported the generated Receivable Ledger(PDF).'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            )
            );
        }

        generateTcpdf(
			array(
				'file_name' => $data['title']
				,'folder_name' => 'inventory'
				,'records' => $list
				,'header' => $params1
				,'orientation' => 'P'
				,'header_fields' => $header_fields
			)
		);
    }

    public function printExcel( $type = 'Balance' ){
		$data = getData();
		$csvarray = array();
		
		$csvarray[] = array( 'title' => $data['title']);
		$csvarray[] = array( 'space' => '' );
		
		if($type == 'Balance'){
            $list = $this->model->getReceivableBalances( $data );
            
            $_viewHolder = $list;
            foreach( $_viewHolder as $idx => $po ){
                if( isset( $po['custSK'] ) && !empty( $po['custSK'] ) ){
                    $this->encryption->initialize( array( 'key' => generateSKED( $po['custSK'] )));
                    $_viewHolder[$idx]['customerName'] = $this->encryption->decrypt( $po['customerName'] );
                }
                if( isset( $po['affSK'] ) && !empty( $po['affSK'] ) ){
                    $this->encryption->initialize( array( 'key' => generateSKED( $po['affSK'] )));
                    $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
                }
            }
            $list = $_viewHolder;

            $csvarray[] = array( 'Affiliate', $data['pdf_idAffiliate'] );
            $csvarray[] = array( 'Sales Reference', $data['pdf_idReference'] );
            $csvarray[] = array( 'Customer Name', $data['pdf_idCustomer'] );
            $csvarray[] = array( 'As of', $data['pdf_date'] );

            $csvarray[] = array(
                'Affiliate'
                ,'Customer'
                ,'Charges'
                ,'Payments'
                ,'Balance'
            );

            $totalCharges = 0;
            $totalPayments = 0;
            $totalBalance = 0;

            foreach( $list as $rs ){
                $csvarray[] = array(
                    $rs['affiliateName']
                    ,$rs['customerName']
                    ,number_format( $rs['chargesAmt'], 2 )
                    ,number_format( $rs['paymentAmt'], 2 )
                    ,number_format( $rs['balanceAmt'], 2 )
                );
                $totalCharges += $rs['chargesAmt'];
                $totalPayments += $rs['paymentAmt'];
                $totalBalance += $rs['balanceAmt'];
            }
            $csvarray[] = array( 
                ''
                ,''
                ,number_format( $totalCharges, 2 )
                ,number_format( $totalPayments, 2 )
                ,number_format( $totalBalance, 2 )
            );
			setLogs(
                array(
                    'actionLogDescription'  => 'Exported the generated Receivable Balance(Excel).'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            )
            );
        }
        else{
            $list = $this->model->getReceivableLedger( $data );

            
            $csvarray[] = array( 'Affiliate', $data['pdf_idAffiliate'] );
            $csvarray[] = array( 'Sales Reference', $data['pdf_idReference'] );
            $csvarray[] = array( 'Customer Name', $data['pdf_idCustomer'] );
            $csvarray[] = array( 'Date', $data['pdf_sdate'] . ' to ' . $data['pdf_edate'] );

            $csvarray[] = array(
                'Date'
                ,'Invoice No.'
                ,'Remarks'
                ,'Charges'
                ,'Payments'
                ,'Balance'
            );

            $totalAmt = 0;
            $totalPayments = 0;
            $runningBal = 0;
            foreach( $list as $rs ){
                $csvarray[] = array(
                    $rs['date']
                    ,$rs['reference']
                    ,$rs['remarks']
                    ,number_format( $rs['chargesAmt'], 2 )
                    ,number_format( $rs['paymentAmt'], 2 )
                    ,number_format( $rs['balanceAmt'], 2 )
                );
                $totalAmt += $rs['chargesAmt'];
                $totalPayments += $rs['paymentAmt'];
                $runningBal = $rs['balanceAmt'];
            }
            
            $csvarray[] = array(
                ''
                ,''
                ,''
                ,number_format( $totalAmt, 2 )
                ,number_format( $totalPayments, 2 )
                ,number_format( $runningBal, 2 )
            );
            setLogs(
                array(
                    'actionLogDescription'  => 'Exported the generated Receivable Ledger(Excel).'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            )
            );
        }

        writeCsvFile(
			array(
				'csvarray' 	 => $csvarray
				,'title' 	 => $data['title']
				,'directory' => 'inventory'
			)
		);
    }

    public function processSOA(){
        $params         = getData();
        $records        = $this->model->getReceivableLedger( $params );
        $totalCharges   = 0;
        $totalPayments  = 0;
        $runningBalance = 0;
        if( (int)$params['type'] == 2 ){
            $csvarray[] = array( 'Customer', $params['customerName'] );
            $csvarray[] = array( 'Affiliate', $params['affiliateName'] );
            $csvarray[] = array( 'Period Cover', date( 'm/d/Y', strtotime( $params['sdate'] ) ) . ' to ' . date( 'm/d/Y', strtotime( $params['edate'] ) ) );

            $csvarray[] = array(
                'Date'
                ,'Reference'
                ,'Remarks'
                ,'Charges'
                ,'Payments'
                ,'Balance'
            );
            foreach( $records as $recordSet ){
                $totalCharges   += $recordSet['chargesAmt'];
                $totalPayments  += $recordSet['paymentAmt'];
                $runningBalance = $recordSet['balanceAmt'];
                $csvarray[] = array(
                    $recordSet['date']
                    ,$recordSet['reference']
                    ,$recordSet['remarks']
                    ,$recordSet['chargesAmt']
                    ,$recordSet['paymentAmt']
                    ,$recordSet['balanceAmt']
                );
            }
            $csvarray[] = array(
                ''
                ,''
                ,''
                ,'Total Charges'
                ,'Total Payments'
                ,'Ending Balance'
            );
            $csvarray[] = array(
                ''
                ,''
                ,''
                ,$totalCharges
                ,$totalPayments
                ,$runningBalance
            );
            setLogs(
                array(
                    'actionLogDescription'  => 'Exported the generated Receivable SOA(Excel).'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            )
            );
            writeCsvFile(
                array(
                    'csvarray' 	 => $csvarray
                    ,'title' 	 => $params['title']
                    ,'directory' => 'inventory'
                )
            );
        }
        else{
            foreach( $records as $recordSet ){
                $totalCharges   += $recordSet['chargesAmt'];
                $totalPayments  += $recordSet['paymentAmt'];
                $runningBalance = $recordSet['balanceAmt'];
            }
            $TOP = '<table>
					<tr>
						<td width = "80%">
							<table border = "0" style="font-size:1em;font-family:Arial, sans-serif; align:center;">
								<tr>
									<td width="50%">
										<table width="100%">
											<tr>
												<td style = "width:35%"><strong>Customer : </strong></td>
												<td style = "width:65%">' . $params['customerName'] . '</td>
											</tr>
											<tr>
												<td style = "width:35%"><strong>Affiliate : </strong></td>
												<td style = "width:65%">' . $params['affiliateName'] . '</td>
											</tr>
											<tr>
												<td style = "width:35%"><strong>Period Cover : </strong></td>
												<td style = "width:65%">' . date( 'm/d/Y', strtotime( $params['sdate'] ) ) . ' to ' . date( 'm/d/Y', strtotime( $params['edate'] ) ) . '</td>
											</tr>
										</table>
									</td>	
								</tr>
							</table>
						</td>
					</tr>
                </table>
                <br/><br/>';
            /* Total Summary Table */
            $BOTTOM .= '
                <br/><br/>
                <table border = "0" cellpadding="7">
                    <tr>
                        <th style="width:15%;"><strong></strong></th>
                        <th style="width:10%;"><strong></strong></th>
                        <th style="width:30%;"><strong></strong></th>
                        <th style="width:15%;border: 1px solid #000;text-align:center"><strong>Total Charges</strong></th>
                        <th style="width:15%;border: 1px solid #000;text-align:center"><strong>Total Payments</strong></th>
                        <th style="width:15%;border: 1px solid #000;text-align:center"><strong>Ending Balance</strong></th>
                    </tr>
                    <tr>
                        <th style="width:15%;"><strong></strong></th>
                        <th style="width:10%;"><strong></strong></th>
                        <th style="width:30%;"><strong></strong></th>
                        <th style="width:15%;border: 1px solid #000;text-align:right">' . number_format( $totalCharges, 2, '.', ',') . '</th>
                        <th style="width:15%;border: 1px solid #000;text-align:right"><strong>' . number_format( $totalPayments, 2, '.', ',') . '</strong></th>
                        <th style="width:15%;border: 1px solid #000;text-align:right"><strong>' . number_format( $runningBalance, 2, '.', ',' ) . '</strong></th>
                    </tr>
                    
                </table>
                </font></div>
            ';
            $paramsHeader = array(
                'title'         => 'Statement of Account'
                ,'file_name'    =>  $params['title']
                ,'folder_name'  => 'pdf/inventory/'
                ,'noLogoTitle'  => true
            );
            
            $params1 = array(
                array(
                    'header'        => 'Date'
                    ,'data_index'   => 'date'
                    ,'width'        => '20%'
                            
                )
                ,array(
                    'header'        => 'Reference'
                    ,'data_index'   => 'reference'
                    ,'width'        => '15%'
                )
                ,array(
                    'header'        => 'Remarks'
                    ,'data_index'   => 'remarks'
                    ,'width'        => '20%'
                )
                ,array(
                    'header'        => 'Charges'
                    ,'data_index'   => 'chargesAmt'
                    ,'width'        => '15%'
                    ,'type'         => 'numbercolumn'			
                )
                ,array(
                    'header'        => 'Payments'
                    ,'data_index'   => 'paymentAmt'
                    ,'width'        => '15%'
                    ,'type'         => 'numbercolumn'
                )
                ,array(
                    'header'        => 'Balance'
                    ,'data_index'   => 'balanceAmt'
                    ,'width'        => '15%'
                    ,'type'         => 'numbercolumn'
                )
            );
                                                
            generate_table($paramsHeader,$params1, $records,$TOP,$BOTTOM);
            
            if( (int)$params['type'] == 3 ){
                $match = 1;
                if( !empty( $params['email'] ) ){
                    if( !send_email( array(
                        'to'            => $params['email']
                        ,'subject'      => $params['customerName'] . ' Statement of Account'
                        ,'body'         => "
                            Hi $params[customerName],
                            <br/><br/><br/>
                            <div>Attach with this email is your Statement of Account for the period: " . date( 'm/d/Y', strtotime( $params['sdate'] ) ) . " to " . date( 'm/d/Y', strtotime( $params['edate'] ) ) . ".</div>
                            <br/><br/><br/>
                            Kind regards,
                            <br/>
                            Kiokong Trucking and Construction
                        "
                        ,'attachment'   => $paramsHeader['folder_name'] . $params['title'] . '.pdf'
                    ) ) ){
                        $match = 2;
                    }
                }
                else $match = 3;
                setLogs(
                    array(
                        'actionLogDescription'  => 'Exported the generated Receivable SOA(PDF) and sent as email.'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            )
                );
                die(
                    json_encode(
                        array(
                            'success'   => true
                            ,'match'    => $match
                        )
                    )
                );
            }
            else{
                setLogs(
                    array(
                        'actionLogDescription'  => 'Exported the generated Receivable SOA(PDF).'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            )
                );
            }
        }
    }
	
	public function viewPDF( $title ){
		viewPDF(
			array(
				'file_name' => $title
				,'folder_name' => 'inventory'
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