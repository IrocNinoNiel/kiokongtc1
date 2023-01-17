<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Marie Danie
 * Module       : Adjustment
 * Continued by : Jays
 * Date         : Jan. 23, 2020
 * Finished     : 
 * Description  : This module allows authorized users to record an item adjustment in inventory.
 *              : Edited for commit only
 * DB Tables    : 
 * */ 
class Adjustments extends CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->library('encryption');
        setHeader( 'inventory/Adjustments_model' );
    }

    public function viewAll(){
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
                 $_viewHolder[$idx]['notedbyName'] = $this->encryption->decrypt( $record['notedbyName'] );
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

    public function getItems(){
        $params = getData();
        $viewAll = $this->model->getItems( $params );
        $viewAll = decryptItem( $viewAll );
        
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $viewAll
                )
            )
        );
    }

    public function getItemQuantity(){
        $params = getData();
        $errRec = array();

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'match'    => 0
                    ,'data'     => array()
                )
            )
        );
    }
    
    public function saveAdjustment(){
        $params = getData();

        /* first check if reference number already exists */
        if( (int)$params['onEdit'] == 0 && _checkData(
            array(
                'table'     => 'invoices'
                ,'field'    => 'referenceNum'
                ,'value'    =>  (int)$params['referenceNum']
                ,'exwhere'  => 'idReference = ' . (int)$params['idReference'] . '
                                    AND idAffiliate = ' . $this->AFFILIATEID
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

        $itemAdjusted   = json_decode( $params['itemAdjusted'], true );
        $journalEntry   = json_decode( $params['journalEntry'], true );

        $this->db->trans_begin();

        /* save invoices records */
        $idInvoice  = ( isset($params['idInvoice']) && $params['idInvoice'] > 0 ) ? $params['idInvoice'] : $this->model->saveAdjustmentInvoice( $params );
        
        /* next is to save adjustment records  */
        foreach( $itemAdjusted as $recordSource ){
            if( $recordSource['short'] > 0 ){ /* release process */
                /* get receiving records */
                $getReceiving   = $this->model->getReceivingRecords( $recordSource, $params );
                $remainingQty   = $recordSource['short'];
                if( count( (array)$getReceiving ) > 0 ){
                    $totalQtyLeft   = $getReceiving[0]['totalQtyLeft'];
                    if( $totalQtyLeft < $recordSource['short'] ) $errRec[] = $recordSource['itemName'];
                    else{
                        foreach( $getReceiving as $receivingRecordSource ){
                            if( $remainingQty > 0 ){
                                if( $receivingRecordSource['qtyLeft'] < $remainingQty ){
                                    /* Update receiving */
                                    $this->model->updateReceivingRecord( 0, $receivingRecordSource );
                                    /* save releasing */
                                    $this->model->addReleasingRecord( array(
                                        'idInvoice'     => $idInvoice
                                        ,'idItem'       => $recordSource['idItem']
                                        ,'qty'          => $receivingRecordSource['qtyLeft']
                                        ,'qtyLeft'      => $receivingRecordSource['qtyLeft']
                                        ,'cost'         => $receivingRecordSource['cost']
                                        ,'price'        => $receivingRecordSource['price']
                                        ,'fIDModule'    => $receivingRecordSource['idModule']
                                        ,'fIdent'       => $receivingRecordSource['idInvoice']
                                    ) );
    
                                    $remainingQty -= $receivingRecordSource['qtyLeft'];
                                }
                                else if( $receivingRecordSource['qtyLeft'] >= $remainingQty ){
                                    /* Update receiving */
                                    $this->model->updateReceivingRecord( ( $receivingRecordSource['qtyLeft'] - $remainingQty ), $receivingRecordSource );
                                     /* save releasing */
                                     $this->model->addReleasingRecord( array(
                                        'idInvoice'     => $idInvoice
                                        ,'idItem'       => $recordSource['idItem']
                                        ,'qty'          => $remainingQty
                                        ,'qtyLeft'      => $remainingQty
                                        ,'cost'         => $receivingRecordSource['cost']
                                        ,'price'        => $receivingRecordSource['price']
                                        ,'fIDModule'    => $receivingRecordSource['idModule']
                                        ,'fIdent'       => $receivingRecordSource['idReceiving']
                                    ) );
                                    $remainingQty   = 0;
                                }
                            }
                        }
                    }
                }
                else{
                    $errRec[] = $recordSource['itemName'];
                }
            }
            else if( $recordSource['over'] > 0 ){ /* receive process */
                $this->model->saveReceiving(
                    array(
                        'idItem'        => $recordSource['idItem']
                        ,'idInvoice'    => $idInvoice
                        ,'qty'          => $recordSource['over']
                        ,'qtyLeft'      => $recordSource['over']
                        ,'cost'         => $recordSource['cost']
                        ,'expiryDate'   => $recordSource['expiryDate']
                    )
                );
            }

            $this->model->saveItemAdjustment( array(
                'idInvoice'     => $idInvoice
                ,'idItem'       => $recordSource['idItem']
                ,'qtyBal'       => $recordSource['qtyBal']
                ,'qtyActual'    => $recordSource['qtyActual']
                ,'cost'         => $recordSource['cost']
                ,'short'        => $recordSource['short']
                ,'over'         => $recordSource['over']
                ,'expiryDate'   => $recordSource['expiryDate']
            ) );
        }

        /* save journal entry */
        for( $i = 0; $i < count( (array)$journalEntry ); $i++ ){
            $journalEntry[$i]['idInvoice']  = $idInvoice;
        }

        $this->model->saveTransactionJournal( $journalEntry );

        if( isset( $params['status'] ) ) unset( $params['status'] );
        $this->setLogs( $params );

        $success    = $this->db->trans_status();
        if( !$success ) $this->db->trans_rollback();
        else $this->db->trans_commit();

        die(
            json_encode(
                array(
                    'success'   => $success
                    ,'match'    => 0
                )
            )
        );
    }

    public function changeTransactionStatus(){
        $params     = getData();
        $idInvoice  = $params['idInvoice'];
        
        /* check first if record still exists */
        if( !_checkData(
            array(
                'table'     => 'invoices'
                ,'field'    => 'idInvoice'
                ,'value'    => $idInvoice
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

        if( $this->standards->getDateModified( $idInvoice, 'idInvoice', 'invoices' )->dateModified != $params['dateModified'] ){
            die(
                json_encode(
                    array(
                        'success'   => true
                        ,'match'    => 2
                    )
                )
            );
        }

        $this->db->trans_begin();

        /* process changing of the record status */
        $this->model->updateRecordStatus( $params );
        if( (int)$params['status'] == 3 ){
            /* first delete receiving */
            $this->model->deleteReceiving( $params );
            /* retrieve all released quantity */
            $releasingRecord    = $this->model->getReleasingRecord( $params );
            foreach( $releasingRecord as $recordSource ){
                $this->model->updateReceivingQty( $recordSource );
            }

            /* delete releasing record */
            $this->model->deleteReleasing( $params );
        }

        $this->setLogs( $params );

        $success    = $this->db->trans_status();
        if( !$success ) $this->db->trans_rollback();
        else $this->db->trans_commit();

        die(
            json_encode(
                array(
                    'success'   => $success
                    ,'match'    => 0
                )
            )
        );
    }

    public function getAdjustment(){
        $params = getData();
        $view   = $this->model->getAdjustment( $params );
        $view   = decryptItem( $view );

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
        $view   = $this->model->retrieveData( $params );

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                    // ,'match'    => 2
                    // ,'nomsg'    => 1
                )
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

    public function generatePDF(){
        $params = getData();
        $data   = $this->model->getAdjustment( $params );
        $data   = decryptItem( $data );

        $TOP    = '<table>
					<tr>
						<td style = "width:50%">
							<table border = "0" style="font-size:1em;font-family:Arial, sans-serif; align:center;">
								<tr>
								<tr>
									<td style = "width:22%"><strong>Reference : </strong></td>
									<td style = "width:70%">' . $params['pdf_idReference'] . ' - ' . $params['pdf_referenceNum'] . '</td>
                                </tr>
								<tr>
									<td style = "width:22%"><strong>Cost Center : </strong></td>
									<td style = "width:70%">' . $params['pdf_idCostCenter'] . '</td>
                                </tr>
								<tr>
									<td style = "width:22%"><strong>Date : </strong></td>
									<td style = "width:70%">' . $params['pdf_tdate'] . ' - ' . date('h:i a', strtotime( $params['pdf_ttime'] ) ) . '</td>
								</tr>
							</table>
						</td>
						<td style = "width:50%">
							<table border = "0" style="font-size:1em;font-family:Arial, sans-serif; align:center;">
								<tr>
									<td style = "width:35%"><strong>Remarks : </strong></td>
									<td style = "width:70%">'.$params['pdf_remarks'].'</td>
								</tr>
							</table>
						</td>
					</tr>
                </table><br/>';
        $table_header   = array(
            array(
                'header'        => 'Code'
                ,'data_index'   => 'barcode'
                ,'width'        => '10%'
            )
            ,array(
                'header'        => 'Item Name'
                ,'data_index'   => 'itemName'
                ,'width'        => '10%'
            )
            ,array(
                'header'        => 'Unit'
                ,'data_index'   => 'unitName'
                ,'width'        => '10%'
            )
            ,array(
                'header'        => 'Classification'
                ,'data_index'   => 'className'
                ,'width'        => '10%'
            )
            ,array(
                'header'        => 'Expiry Date'
                ,'data_index'   => 'expiryDate'
                ,'width'        => '10%'
                ,'type'         => 'datecolumn'
            )
            ,array(
                'header'        => 'Balance Qty'
                ,'data_index'   => 'qty'
                ,'width'        => '10%'
                ,'type'         => 'numbercolumn'
                ,'decimalplaces'    => 0
            )
            ,array(
                'header'        => 'Actual Qty'
                ,'data_index'   => 'actualQty'
                ,'width'        => '10%'
                ,'type'         => 'numbercolumn'
                ,'decimalplaces'    => 0
            )
            ,array(
                'header'        => 'Cost'
                ,'data_index'   => 'cost'
                ,'width'        => '10%'
                ,'type'         => 'numbercolumn'
            )
            ,array(
                'header'        => 'Short'
                ,'data_index'   => 'shortQty'
                ,'width'        => '10%'
                ,'type'         => 'numbercolumn'
                ,'decimalplaces'    => 0
            )
            ,array(
                'header'        => 'Over'
                ,'data_index'   => 'overQty'
                ,'width'        => '10%'
                ,'type'         => 'numbercolumn'
                ,'decimalplaces'    => 0
            )
        );
        $mainParams = array(
            'title'         => 'Adjustment Form'
            ,'noTitle'      => true
            ,'file_name'    => 'Adjustment Form'
            ,'folder_name'  => 'pdf/inventory/'
            ,'grid_font_size'   => 8
        );

        $ExtableBottom      = '<br/>
            Journal Entries: <br/>
            <table border = "1" style="font-size: 30px; width: 91%;" cellpadding = "5">
                <tr>
                    <td style = "width:10%;"><strong>Code</strong></td>
                    <td style = "width:25%;"><strong>Name</strong></td>
                    <td style = "width:30%;"><strong>Explanation</strong></td>
                    <td style = "width:25%;"><strong>Cost Center</strong></td>
                    <td style = "width:10%;"><strong>Debit</strong></td>
                    <td style = "width:10%;"><strong>Credit</strong></td>
                </tr>';
        $journalEntry = $this->standards->gridJournalEntry( $params );
        foreach( $journalEntry as $recordSource ){
            $ExtableBottom  .=  '
                <tr>
                    <td>' . $recordSource['code'] . '</td>
                    <td>' . $recordSource['name'] . '</td>
                    <td>' . $recordSource['explanation'] . '</td>
                    <td>' . $recordSource['costcenterName'] . '</td>
                    <td style = "text-align: right;">' . number_format( $recordSource['debit'], 2 ) . '</td>
                    <td style = "text-align: right;">' . number_format( $recordSource['credit'], 2 ) . '</td>
                </tr>
            ';
        }

        $ExtableBottom .= '</table>';

        generate_table( $mainParams, $table_header, $data, $TOP, $ExtableBottom );
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
                 $_viewHolder[$idx]['notedbyName'] = $this->encryption->decrypt( $record['notedbyName'] );
             }
             
         }
 
         $view['view'] = $_viewHolder;
         /**Ends here**/

        $table = array(
            array(
                'header'        =>'Date'
                ,'dataIndex'    =>'date'
                ,'width'        =>'14.28'
            ),
            array(
                'header'        =>'Reference'
                ,'dataIndex'    =>'reference'
                ,'width'        =>'14.28'
            ),
            array(
                'header'        =>'Remarks'
                ,'dataIndex'    =>'remarks'
                ,'width'        =>'20.70'
            ),
            array(
                'header'        =>'Prepared By'
                ,'dataIndex'    =>'preparedByName'
                ,'width'        =>'18'
            ),
            array(
                'header'        =>'Noted By'
                ,'dataIndex'    =>'notedbyName'
                ,'width'        =>'18'
            ),
            array(
                'header'        =>'Status'
                ,'dataIndex'    =>'statusText'
                ,'width'        =>'14.28'
            )
        );

        generateTcpdf(
			array(
				'file_name'         => 'Inventory Adjustment List'
                ,'folder_name'      => 'inventory'
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
                 $_viewHolder[$idx]['notedbyName'] = $this->encryption->decrypt( $record['notedbyName'] );
             }
             
         }
 
         $view['view'] = $_viewHolder;
         /**Ends here**/
        
		$csvarray[] = array( 'title' => 'Inventory Adjustment List' );
		$csvarray[] = array( 'space' => '' );
		$csvarray[] = array( 'space' => '' );

		$csvarray[] = array(
			'col1'  => 'Date'
            ,'col2' => 'Reference'
            ,'col3' => 'Remarks'
            ,'col4' => 'Prepared By'
            ,'col5' => 'Noted By'
            ,'col6' => 'Status'
        );
        

		foreach( $view['view'] as $value ){
			$csvarray[] = array(
				'col1' => $value[ 'date' ]
                ,'col2' => $value[ 'reference' ]
                ,'col3' => $value[ 'remarks' ]
                ,'col4' => $value[ 'preparedByName' ]
                ,'col5' => $value[ 'notedByName' ]
                ,'col6' => $value[ 'statusText' ]
			);
        }
        
		$data['description'] = 'Inventory Adjustment List' . ": " .$this->USERNAME. ' printed an Excel report'  ;
		$data['iduser'] = $this->USERID;
		$data['usertype'] = $this->USERTYPEID;
		$data['printExcel'] = true;	
        $data['ident'] = null;

		writeCsvFile(
			array(
				'csvarray' 	 => $csvarray
				,'title' 	 => 'Inventory Adjustment List'
				,'directory' => 'inventory'
			)
		);
		
    }
    
    function download($title){
		force_download(
			array(
				'title' => $title
				,'directory' => 'inventory'
			)
		);
    }

    function viewPDF($title){		
		viewPDF(
			array(
				'file_name' => 'Adjustment Form'
				,'folder_name' => 'inventory'
			)
		);
	}

    private function setLogs( $params ){
		$header = ucfirst( $this->USERFULLNAME );
        $params['idAffiliate'] = $this->session->userdata('AFFILIATEID');
        $action = '';
		
		if( isset( $params['deleting'] ) ){
			$action = 'deleted a';
        }
        else if( isset( $params['status'] ) ){
            if( (int)$params['status'] == 2 ) $action = 'confirmed an adjustment';
            else $action = 'cancelled a';
        }
		else{
			if( isset( $params['action'] ) )
				$action = $params['action'];
			else
				$action = ( $params['onEdit'] == 1  ? 'edited a' : 'added a new adjustment' );
        }
        
        $params['actionLogDescription']     = $header . ' ' . $action . ' transaction.';
		
		setLogs( $params );
    }

}