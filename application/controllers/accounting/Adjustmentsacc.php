<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Jays
 * Date         : Feb. 18, 2020
 * Module       : Adjustment(Accounting)
 * Finished     : Mar. 03, 2020
 * Description  : This module allows authorized user to set (add, edit, and delete) an adjustment transactions.
 * DB Tables    : 
 * */
class Adjustmentsacc extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->library('encryption');
        setHeader('accounting/Adjustmentsacc_model');
    }

    public function getPRReferences(){
        $params     = getData();
        $view       = $this->model->getPRReferences( $params );
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
        $params             = getData();
        $view               = $this->model->viewAll( $params );

         /**Custom Decryption for Inventory Adjustment list**/
         $_viewHolder = $view['view'];
         foreach( $_viewHolder as $idx => $record ){
             /**Decrypting  user [PREPARED BY]**/
             if( isset( $record['preparedBySK'] ) && !empty( $record['preparedBySK'] ) ){
                 $this->encryption->initialize( array( 'key' => generateSKED( $record['preparedBySK'] )));
                 $_viewHolder[$idx]['preparedByName'] = $this->encryption->decrypt( $record['preparedByName'] );
             }
 
             /**Decrypting  user [NOTED BY]**/
             if( isset( $record['notedBySK'] ) && !empty( $record['notedBySK'] ) ){
                 $this->encryption->initialize( array( 'key' => generateSKED( $record['notedBySK'] )));
                 $_viewHolder[$idx]['notedByName'] = $this->encryption->decrypt( $record['notedByName'] );
             }

             /**Decrypting cost center**/
             if( isset( $record['costCenterSK'] ) && !empty( $record['costCenterSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $record['costCenterSK'] )));
                $_viewHolder[$idx]['costCenterName'] = $this->encryption->decrypt( $record['costCenterName'] );
             }
             
         }
 
         $view['view'] = $_viewHolder;
         /**Ends here**/

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

    public function saveAccountingAdjustment(){
        $params                 = getData();
        $jeRecords              = json_decode( $params['jeRecords'], true );
        $idInvoice              = (int)$params['idInvoice'];
        $params['idAffiliate']  = $this->session->userdata('AFFILIATEID');

        if( $idInvoice > 0 ){
            /* check if record still exists */
            if( !_checkData(
                array(
                    'table'     => 'invoices'
                    ,'field'    => 'idInvoice'
                    ,'value'    => $idInvoice
                    ,'exwhere'  => 'archived NOT IN( 1 )'
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

            /* check if record is modified by other user */
            if( $params['modify'] == 0 ){
                $dateModified = $this->standards->getDateModified( $params['idInvoice'], 'idInvoice', 'invoices' );
                if( $params['dateModified'] != $dateModified->dateModified ){
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

            /* check if record is already approved or denied by other user */
            $status = $this->model->getRecordStatus( $idInvoice );
            if( $status > 1 ){
                die(
                    json_encode(
                        array(
                            'success'       => true
                            ,'match'        => 4
                            ,'curStatus'    => ( $status == 2? 'approved' : 'cancelled' )
                        )
                    )
                );
            }
        }
        else{

            if( isset( $params['idCostCenter'] ) && !empty( $params['idCostCenter'] ) ) {

            }

            $_exwhere = "idReference = $params[idReference] AND idInvoice NOT IN( $params[idInvoice] ) AND archived NOT IN( 1 ) AND idAffiliate = $this->AFFILIATEID";
            $_exwhere .= ( isset( $params['idCostCenter'] ) && !empty( $params['idCostCenter'] ) ) ? " and idCostCenter = $params[idCostCenter]" : ' ';

            if( _checkData(
                array(
                    'table'     => 'invoices'
                    ,'field'    => 'referenceNum'
                    ,'value'    => (int)$params['referenceNum']
                    ,'exwhere'  => $_exwhere
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
        }
        
        $this->db->trans_begin();
        $idInvoice              = $this->model->saveAccountingAdjustment( $params );
        $params['idInvoice']    = $idInvoice;
        $idInvoiceHistory       = $this->model->saveAccountingAdjustmentHistory( $params );
        /* delete any existing journal entries */
        $this->model->deleteAccountingAdjustmentJE( $idInvoice );
        for( $i = 0; $i < count( $jeRecords ); $i++ ){
            $jeRecords[$i]['idInvoice']         = $idInvoice;
            $jeRecords[$i]['idInvoiceHistory']  = $idInvoiceHistory;
        }
        /* save posting records */
        $this->model->savePosting( $jeRecords );
        $this->model->savePostingHistory( $jeRecords );

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

    public function getPCodes(){
        $params     = getData();
        $data       = $this->model->getPCodes( $params );
        $data = ( $params['pType'] == 1 ) ? decryptCustomer( $data ) : decryptSupplier( $data );

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $data
                )
            )
        );
    }

    public function retrieveData(){
        $params     = getData();
        /* first check if record still exists */
        if( !_checkData(
            array(
                'table'     => 'invoices'
                ,'field'    => 'idInvoice'
                ,'value'    => (int)$params['idInvoice']
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

        $viewAll    = $this->model->retrieveData( $params );
        $match      = 0;
        /* check if there are closing entry for this record */
        if( $this->model->hasClosingEntry( $viewAll[0] ) ) $match = 2;
        elseif( (int)$viewAll[0]['status'] > 1 ) $match = 3;

        $viewAll = ( $viewAll[0]['pType'] == 1 ) ? decryptCustomer( $viewAll ) : decryptSupplier( $viewAll );
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'match'    => $match
                    ,'view'     => $viewAll
                )
            )
        );

    }

    public function deleteRecord(){
        $params     = getData();

        /* check first if record to delete still exists */
        if( !_checkData(
            array(
                'table'     => 'invoices'
                ,'field'    => 'idInvoice'
                ,'value'    => (int)$params['idInvoice']
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
        if( $this->model->hasClosingEntry( $params ) ){
            die(
                json_encode(
                    array(
                        'success'   => true
                        ,'match'    => 2
                    )
                )
            );
        }

        $this->model->markRecordAsArchived( $params );
        $params['deleting']  = true;
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

    public function changeTransactionStatus(){
        $params     = getData();

        /* first check if record to approve/declined still exists */
        if( !_checkData(
            array(
                'table'     => 'invoices'
                ,'field'    => 'idInvoice'
                ,'value'    => (int)$params['idInvoice']
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

        /* check for record status */
        $status = $this->model->getRecordStatus( (int)$params['idInvoice'] );
        if( $status > 1 ){
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
        $dateModified = $this->standards->getDateModified( $params['idInvoice'], 'idInvoice', 'invoices' );
        if( $params['dateModified'] != $dateModified->dateModified ){
            die(
                json_encode(
                    array(
                        'success'   => true
                        ,'match'    => 2
                    )
                )
            );
        }
        unset( $params['dateModified'] );
        $this->model->saveAccountingAdjustment( $params );
        $params['action']   = ( (int)$params['status'] == 2? 'confirmed a Vouchers Payable Transaction' : 'cancelled a transaction' );
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

    // public function generatePDF(){
        
    //     $params = getData();
    //     $data   = $this->standards->gridJournalEntry( $params );

    //     $TOP    = '<table>
	// 				<tr>
	// 					<td style = "width:50%">
	// 						<table border = "0" style="font-size:1em;font-family:Arial, sans-serif; align:center;">
	// 							<tr>
	// 								<td style = "width:22%"><strong>Affiliate : </strong></td>
	// 								<td style = "width:70%">' . $params['pdf_idAffiliate'] . '</td>
	// 							</tr>
	// 							<tr>
	// 								<td style = "width:22%"><strong>Reference : </strong></td>
	// 								<td style = "width:70%">' . $params['pdf_idReference'] . ' - ' . $params['pdf_referenceNum'] . '</td>
	// 							</tr>
	// 							<tr>
	// 								<td style = "width:22%"><strong>Description : </strong></td>
	// 								<td style = "width:70%">' . $params['pdf_description'] . '</td>
	// 							</tr>
	// 							<tr>
	// 								<td style = "width:22%"><strong>Name : </strong></td>
	// 								<td style = "width:70%">' . $params['pdf_name'] . '</td>
	// 							</tr>
	// 							<tr>
	// 								<td style = "width:22%"><strong>Amount : </strong></td>
	// 								<td style = "width:70%">' . $params['pdf_amount'] . '</td>
	// 							</tr>
	// 						</table>
	// 					</td>
	// 					<td style = "width:50%">
	// 						<table border = "0" style="font-size:1em;font-family:Arial, sans-serif; align:center;">
	// 							<tr>
	// 								<td style = "width:35%"><strong>Cost Center : </strong></td>
	// 								<td style = "width:70%">'.$params['pdf_idCost Center'].'</td>
    //                             </tr>
	// 							<tr>
    //                                 <td style = "width:22%"><strong>Date : </strong></td>
    //                                 <td style = "width:70%">' . $params['pdf_tdate'] . ' - ' . date('h:i a', strtotime( $params['pdf_ttime'] ) ) . '</td>
    //                             </tr>
	// 							<tr>
	// 								<td style = "width:35%"><strong>Remarks : </strong></td>
	// 								<td style = "width:70%">'.$params['pdf_remarks'].'</td>
    //                             </tr>
	// 							<tr>
	// 								<td style = "width:35%"><strong>Invoice : </strong></td>
	// 								<td style = "width:70%">'.$params['pdf_fident'].'</td>
    //                             </tr>
	// 							<tr>
	// 								<td style = "width:35%"><strong>Invoice Amount : </strong></td>
	// 								<td style = "width:70%">'.$params['pdf_amountPR'].'</td>
    //                             </tr>
	// 						</table>
	// 					</td>
	// 				</tr>
    //             </table><br/>';
    //     $table_header   = array(
    //         array(
    //             'header'        => 'Code'
    //             ,'data_index'   => 'code'
    //             ,'width'        => '15%'
    //         )
    //         ,array(
    //             'header'        => 'Name'
    //             ,'data_index'   => 'name'
    //             ,'width'        => '30%'
    //         )
    //         ,array(
    //             'header'        => 'Explanation'
    //             ,'data_index'   => 'explanation'
    //             ,'width'        => '15%'
    //         )
    //         ,array(
    //             'header'        => 'Cost Center'
    //             ,'data_index'   => 'costcenterName'
    //             ,'width'        => '20%'
    //         )
    //         ,array(
    //             'header'        => 'Debit'
    //             ,'data_index'   => 'debit'
    //             ,'width'        => '10%'
    //             ,'type'         => 'numbercolumn'
    //             ,'hasTotal'     => true
    //         )
    //         ,array(
    //             'header'        => 'Credit'
    //             ,'data_index'   => 'credit'
    //             ,'width'        => '10%'
    //             ,'type'         => 'numbercolumn'
    //             ,'hasTotal'     => true
    //         )
    //     );
    //     $mainParams = array(
    //         'title'             => 'Adjustment Form'
    //         ,'file_name'        => 'Adjustment Form'
    //         ,'folder_name'      => 'pdf/accounting/'
    //         ,'grid_font_size'   => 8
    //         ,'generate_total'   => true
    //         ,'total_fields'     => array( 'debit', 'credit' )
    //     );

    //     generate_table( $mainParams, $table_header, $data, $TOP );
    // }

    public function generatePDF(){
        $params = getData();
        $datarec   = $this->standards->gridJournalEntry( $params );
        
        $header_fields = array(
            array(
                array(
                    'label'     => 'Reference'
                    ,'value'    => $params['pdf_idReference'] . '-' .$params['pdf_referenceNum']
                )
                ,array(
                    'label'     => 'Cost Center'
                    ,'value'    => $params['pdf_idCostCenter']
                )
                ,array(
                    'label'     => 'Description'
                    ,'value'    => $params['pdf_description']
                )
                ,array(
                    'label'     => 'Name'
                    ,'value'    => $params['pdf_name']
                )
                ,array(
                    'label'     => 'Amount'
                    ,'value'    => number_format( $params['pdf_amount'], 2 )
                )
            )
            ,array(
                array(
                    'label'     => 'Date'
                    ,'value'    => date( 'm/d/Y', strtotime( $params['pdf_tdate'] ) ) . ' ' . date( 'h:i A', strtotime( $params['pdf_ttime'] ) )
                )
                ,array(
                    'label'     => 'Remarks'
                    ,'value'    => $params['pdf_remarks']
                )
                ,array(
                    'label'     => 'Invoice'
                    ,'value'    => $params['pdf_fident']
                )
                ,array(
                    'label'     => 'Invoice Amount'
                    ,'value'    => number_format( $params['pdf_amountPR'], 2 )
                )
            )
        );


        $table = array(
            array(
                'header'        => 'Code'
                ,'dataIndex'    => 'code'
                ,'width'        => '15'	
            )
            ,array(
                'header'        => 'Name'
                ,'dataIndex'    => 'name'
                ,'width'        => '15'
            )
            ,array(
                'header'        => 'Explanation'
                ,'dataIndex'    => 'explanation'
                ,'width'        => '30'
            )
            ,array(
                'header'        => 'Cost Center'
                ,'dataIndex'    => 'costCenterName'
                ,'width'        => '20'
            )
            ,array(
                'header'        => 'Debit'
                ,'dataIndex'    => 'debit'
                ,'width'        => '10'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
                ,'hasTotal'     => true
            )
            ,array(
                'header'        => 'Credit'
                ,'dataIndex'    => 'credit'
                ,'width'        => '10'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
                ,'hasTotal'     => true
            )
        );

        generateTcpdf(
			array(
				'file_name'         => $params['title']
                ,'folder_name'      => 'accounting'
                ,'header_fields'    => $header_fields
                ,'records'          => $datarec
                ,'header'           => $table
                ,'orientation'      => 'P'
			) 
        );
    }
    
    public function getAdjustmentRef(){
        $params = getData();
        $view   = $this->model->getAdjustmentRef( $params );
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

    public function customListPDF(){
        $params = getData();
        $view   = $this->model->viewAll( $params );

         /**Custom Decryption for Inventory Adjustment list**/
         $_viewHolder = $view['view'];
         foreach( $_viewHolder as $idx => $record ){
             /**Decrypting  user [PREPARED BY]**/
             if( isset( $record['preparedBySK'] ) && !empty( $record['preparedBySK'] ) ){
                 $this->encryption->initialize( array( 'key' => generateSKED( $record['preparedBySK'] )));
                 $_viewHolder[$idx]['preparedByName'] = $this->encryption->decrypt( $record['preparedByName'] );
             }
 
             /**Decrypting  user [NOTED BY]**/
             if( isset( $record['notedBySK'] ) && !empty( $record['notedBySK'] ) ){
                 $this->encryption->initialize( array( 'key' => generateSKED( $record['notedBySK'] )));
                 $_viewHolder[$idx]['notedByName'] = $this->encryption->decrypt( $record['notedByName'] );
             }

              /**Decrypting cost center**/
              if( isset( $record['costCenterSK'] ) && !empty( $record['costCenterSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $record['costCenterSK'] )));
                $_viewHolder[$idx]['costCenterName'] = $this->encryption->decrypt( $record['costCenterName'] );
            }
             
         }
 
         $view['view'] = $_viewHolder;
         /**Ends here**/

        $table = array(
            array(
                'header'        =>'Reference Number'
                ,'dataIndex'    =>'reference'
                ,'width'        =>'14.28'
            ),
            array(
                'header'        =>'Date'
                ,'dataIndex'    =>'tdate'
                ,'width'        =>'14.28'
            ),
            array(
                'header'        =>'Cost Center'
                ,'dataIndex'    =>'costCenterName'
                ,'width'        =>'14.28'
            ),
            array(
                'header'        =>'Description'
                ,'dataIndex'    =>'remarks'
                ,'width'        =>'14.28'
            ),
            array(
                'header'        =>'Prepared By'
                ,'dataIndex'    =>'preparedByName'
                ,'width'        =>'14.28'
            ),
            array(
                'header'        =>'Noted By'
                ,'dataIndex'    =>'notedbyName'
                ,'width'        =>'14.28'
            ),
            array(
                'header'        =>'Status'
                ,'dataIndex'    =>'statusText'
                ,'width'        =>'14.28'
            )
        );

        generateTcpdf(
			array(
				'file_name'         => 'Accounting Adjustment List'
                ,'folder_name'      => 'accounting'
                ,'records'          => $view['view']
                ,'header'           => $table
                ,'orientation'      => 'P'
                ,'idAffiliate'      => $this->session->userdata('AFFILIATEID')
			) 
        );
    }

    function printExcel (){
		$data = getData();
		$view   = $this->model->viewAll( $params );

         /**Custom Decryption for Inventory Adjustment list**/
         $_viewHolder = $view['view'];
         foreach( $_viewHolder as $idx => $record ){
             /**Decrypting  user [PREPARED BY]**/
             if( isset( $record['preparedBySK'] ) && !empty( $record['preparedBySK'] ) ){
                 $this->encryption->initialize( array( 'key' => generateSKED( $record['preparedBySK'] )));
                 $_viewHolder[$idx]['preparedByName'] = $this->encryption->decrypt( $record['preparedByName'] );
             }
 
             /**Decrypting  user [NOTED BY]**/
             if( isset( $record['notedBySK'] ) && !empty( $record['notedBySK'] ) ){
                 $this->encryption->initialize( array( 'key' => generateSKED( $record['notedBySK'] )));
                 $_viewHolder[$idx]['notedByName'] = $this->encryption->decrypt( $record['notedByName'] );
             }

             /**Decrypting cost center**/
             if( isset( $record['costCenterSK'] ) && !empty( $record['costCenterSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $record['costCenterSK'] )));
                $_viewHolder[$idx]['costCenterName'] = $this->encryption->decrypt( $record['costCenterName'] );
             }
             
         }
 
         $view['view'] = $_viewHolder;
         /**Ends here**/
        
		$csvarray[] = array( 'title' => 'Accounting Adjustment List' );
		$csvarray[] = array( 'space' => '' );
		$csvarray[] = array( 'space' => '' );

		$csvarray[] = array(
			'col1'  => 'Reference'
            ,'col2' => 'Date'
            ,'col3' => 'Cost Center Name'
            ,'col4' => 'Description'
            ,'col5' => 'Prepared By'
            ,'col6' => 'Noted By'
            ,'col7' => 'Status'
        );
        

		foreach( $view['view'] as $value ){
			$csvarray[] = array(
				'col1' => $value[ 'reference' ]
                ,'col2' => $value[ 'tdate' ]
                ,'col3' => $value[ 'costCenterName' ]
                ,'col4' => $value[ 'description' ]
                ,'col5' => $value[ 'preparedByName' ]
                ,'col6' => $value[ 'notedByName' ]
                ,'col7' => $value[ 'statusText' ]
			);
        }
        
		$data['description'] = 'Accounting Adjustment List' . ": " .$this->USERNAME. ' printed an Excel report'  ;
		$data['iduser'] = $this->USERID;
		$data['usertype'] = $this->USERTYPEID;
		$data['printExcel'] = true;	
        $data['ident'] = null;

		writeCsvFile(
			array(
				'csvarray' 	 => $csvarray
				,'title' 	 => 'Accounting Adjustment List'
				,'directory' => 'accounting'
			)
		);
		
    }

    function download($title){
		force_download(
			array(
				'title' => $title
				,'directory' => 'accounting'
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
				$action = ( $params['onEdit'] == 1  ? 'edited a transaction' : 'added a new adjustment Transaction' );
		}
        
        $params['actionLogDescription'] = $header . ' ' . $action . '.';
		setLogs( $params );
    }

}