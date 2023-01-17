<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Jayson Dagulo
 * Module       : Closing Journal Entry
 * Date         : Jan 28, 2019
 * Finished     : 
 * Description  : This module allows authorized users to set the accounting defaults of every transaction module.
 * DB Tables    : 
 * */ 

class Closingentry extends CI_Controller {

    public function __construct(){
        parent::__construct();
        setHeader( 'accounting/Closingentry_model' );
    }

    public function getClosingEntryRef(){
        $params = getData();
        $view   = $this->model->getClosingEntryRef( $params );

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

    public function getClosingEntries(){
        $params     = getData();
        $view       = $this->model->getClosingEntries( $params );
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

    public function saveClosingEntry(){
        $params     = getData();
        $idInvoice  = (int)$params['idInvoice'];
        if( $idInvoice > 0 ){
            /* check if record still exists */
            if( !_checkData(
                array(
                    'table'         => 'invoices'
                    ,'field'        => 'idInvoice'
                    ,'value'        => $idInvoice
                    ,'exwhere'      => 'archived NOT IN( 1 )'
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

            /* check if record is modified by other users */
            if( $params['modify'] == 0 ){
                $dateModified = $this->standards->getDateModified( $params['idInvoice'], 'idInvoice', 'invoices' );
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
        else{
            /* check if reference number already in used */
            if( _checkData(
                array(
                    'table'     => 'invoices'
                    ,'field'    => 'referenceNum'
                    ,'value'    => $params['referenceNum']
                    ,'exwhere'  => "idInvoice NOT IN( $params[idInvoice] ) AND idReference = $params[idReference] AND idAffiliate = $this->AFFILIATEID AND archived NOT IN( 1 )"
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

        /* check if period to save already closed */
        if( _checkData(
            array(
                'table'     => 'invoices'
                ,'field'    => 'idModule'
                ,'value'    => $params['idModule']
                ,'exwhere'  => "idInvoice NOT IN( $params[idInvoice] ) AND idReference = $params[idReference] AND idAffiliate = $this->AFFILIATEID AND archived NOT IN( 1 ) AND month = $params[month] AND year = $params[year]"
            )
        ) ){
            die(
                json_encode(
                    array(
                        'success'   => true
                        ,'match'    => 4
                    )
                )
            );
        }

        /* check if there are succeeding months that are already closed */
        if( _checkData(
            array(
                'table'     => 'invoices'
                ,'field'    => 'idModule'
                ,'value'    => $params['idModule']
                ,'exwhere'  => "idInvoice NOT IN( $params[idInvoice] ) AND idAffiliate = $this->AFFILIATEID AND ( ( month > $params[month] AND year = $params[year] ) OR ( month < $params[month] AND year > $params[year] ) ) AND archived NOT IN( 1 )"
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

        /* check if there are previous closing entry that is not yet tagged as final */
        if( _checkData(
            array(
                'table'     => 'invoices'
                ,'field'    => 'idModule'
                ,'value'    => $params['idModule']
                ,'exwhere'  => "idInvoice NOT IN( $params[idInvoice] ) AND idAffiliate = $this->AFFILIATEID AND year <= $params[year] AND status NOT IN( 2 ) AND archived NOT IN(1)"
            )
        ) ){
            die(
                json_encode(
                    array(
                        'success'   => true
                        ,'match'    => 6
                    )
                )
            );
        }

        /* check if there are previous transaction that are not yet closed */
        /* first check if chart of account beginning balance period is already closed */
        $coaBegBalRec   = $this->model->getCoaBegBalRec();
        if( $coaBegBalRec != false ){
            $params['prevmonth']    = date( 'm', strtotime( $coaBegBalRec ) );
            $params['prevyear']     = date( 'Y', strtotime( $coaBegBalRec ) );
        }
        if( $coaBegBalRec != false && date( 'm', strtotime( $coaBegBalRec ) ) != $params['month'] && date( 'Y', strtotime( $coaBegBalRec ) ) != $params['year'] ){
            /* check if record date has closing journal entry */
            if( !_checkData(
                array(
                    'table'     => 'invoices'
                    ,'field'    => 'idModule'
                    ,'value'    => $params['idModule']
                    ,'exwhere'  => "archived NOT IN( 1 ) AND month = MONTH( '$coaBegBalRec' ) AND year = YEAR( '$coaBegBalRec' ) AND idAffiliate = $this->AFFILIATEID"
                )
            ) ){
                die(
                    json_encode(
                        array(
                            'success'   => true
                            ,'match'    => 8
                            ,'month'    => date( 'F', strtotime( $coaBegBalRec ) )
                            ,'year'     => date( 'Y', strtotime( $coaBegBalRec ) )
                        )
                    )
                );
            }
        }
        /* get latest previous month that has transaction */
        $prevRec        = $this->model->getPreviousTransactions( $params );
        if( $prevRec != false ){
            $params['prevmonth']    = date( 'm', strtotime( $coaBegBalRec ) );
            $params['prevyear']     = date( 'Y', strtotime( $coaBegBalRec ) );
            /* check if record date has closing journal entry */
            if( !_checkData(
                array(
                    'table'     => 'invoices'
                    ,'field'    => 'idModule'
                    ,'value'    => $params['idModule']
                    ,'exwhere'  => "archived NOT IN( 1 ) AND month = MONTH( '$prevRec' ) AND year = YEAR( '$prevRec' ) AND idAffiliate = $this->AFFILIATEID"
                )
            ) ){
                die(
                    json_encode(
                        array(
                            'success'   => true
                            ,'match'    => 7
                            ,'month'    => date( 'F', strtotime( $prevRec ) )
                            ,'year'     => date( 'Y', strtotime( $prevRec ) )
                        )
                    )
                );
            }
        }

        $this->db->trans_begin();
        /* save transaction header first */
        $params['idInvoice']        = $this->model->saveClosing( $params );
        $params['idInvoiceHistory'] = $this->model->saveClosingHistory( $params );
        /* delete posting and GL records */
        $this->model->deleteRelatedRecords( $params );
        
        /* process saving of record details */
        $closingEntries = json_decode( $params['closingEntries'], true );
        for( $i = 0; $i < count( (array)$closingEntries ); $i++ ){
            $closingEntries[$i]['idInvoice']        = $params['idInvoice'];
            $closingEntries[$i]['idInvoiceHistory'] = $params['idInvoiceHistory'];
        }
        
        /* save posting( Revenue and expenses ) */
        if ( !empty( $closingEntries ) ) {
            $this->model->savePosting( $closingEntries );
            $this->model->savePostingHistory( $closingEntries );
        }

        /* retrieve all accounts for the period and evaluate to be saved for GL */
        $glRecords  = $this->model->getPostingRecords( $params );
        if( count( (array)$glRecords ) > 0 ) $this->model->saveGL( $glRecords );

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
        
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'match'    => 0
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
                ,'value'    => $params['idInvoice']
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

    public function deleteRecord(){
        $params     = getData();

        /* first check if data to delete stil exists */
        if( !_checkData(
            array(
                'table'     => 'invoices'
                ,'field'    => 'idInvoice'
                ,'value'    => $params['idInvoice']
                ,'exwhere'  => "archived NOT IN( 1 )"
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

        $nextmonth  = $params['month'];
        $nextyear   = $params['year'];
        if( $params['month'] == 12 ){
            $nextmonth = 1;
            $nextyear++;
        }
        else $nextmonth++;
        if( _checkData(
            array(
                'table'     => 'invoices'
                ,'field'    => 'idModule'
                ,'value'    => 35
                ,'exwhere'  => "archived NOT IN( 1 ) AND month >= $nextmonth AND year >= $nextyear AND idAffiliate = $this->AFFILIATEID"
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

        /* tag record as archived */
        $this->model->tagRecordAsArchived( $params );
        $params['deleting'] = true;
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

    public function generatePDF(){
        
        $params = getData();
        $datarec   = $this->model->getClosingEntries( $params );
        
        $header_fields = array(
            array(
                array(
                    'label'     => 'Reference'
                    ,'value'    => $params['pdf_idReference'] . '-' .$params['pdf_referenceNum']
                )
                ,array(
                    'label'     => 'Description'
                    ,'value'    => $params['pdf_description']
                )
                ,array(
                    'label'     => 'Month'
                    ,'value'    => $params['pdf_month'] . ' ' . $params['pdf_year']
                )
            )
            ,array(
                array(
                    'label'     => 'Date'
                    ,'value'    => date( 'm/d/Y', strtotime( $params['pdf_tdate'] ) )
                )
                ,array(
                    'label'     => 'Remarks'
                    ,'value'    => $params['pdf_remarks']
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
                ,'width'        => '55'
            )
            ,array(
                'header'        => 'Debit'
                ,'dataIndex'    => 'debit'
                ,'width'        => '15'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
                ,'hasTotal'     => true
            )
            ,array(
                'header'        => 'Credit'
                ,'dataIndex'    => 'credit'
                ,'width'        => '15'
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
				$action = ( $params['onEdit'] == 1  ? 'edited a transaction' : 'Closed the month ' . $params['monthDis'] . ' year ' . $params['year'] );
		}
        
        $params['actionLogDescription'] = $header . ' ' . $action . '.';
		setLogs( $params );
    }

}