<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Receiving extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('encryption');
        setHeader( 'inventory/Receiving_model' );
    }

    function getPO() {
        $params = getData();
        $params['date'] = date( 'Y-m-d', strtotime( $params['date'] ) );
        $view = $this->model->getPO( $params );


        die(
            json_encode(
                array(
                    'success' => true
                    ,'view' => $view
                ) 
            )
        );
    }

    function getPONumber(){
        $params = getData();

        $view = array(
            array(
                'idInvoice' => '0'
                ,'name' => 'None'
            )
        );

        if( !empty( $params['idInvoice'] ) ) array_push( $view, array('idInvoice' => $params['idInvoice'], 'name' => $params['name'] ));

        die(
            json_encode(
                array(
                    'success' => true
                    ,'view' => $view
                )
            )
        );
    }

    function getPOItems(){
        $params = getData();
        $view = $this->model->getPOItems( $params );
        $view['view'] = decryptItem( $view['view'] );

        // LQ();

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

    function getItems() {
        $params = getData();
        $view = $this->model->getItems( $params );
        $view = decryptItem( $view );

        die(
            json_encode(
                array(
                    'success' => true
                    ,'view' => $view
                )
            )
        );
    }

    function getPOItemDetails() {
        $params = getData();
        $view = $this->model->getPOItemDetails( $params );
        
        die(
            json_encode(
                array(
                    'success' => true
                    ,'view' => $view
                )
            )
        );
    }

    function getSupplier(){
        $params = getData();
        $view = $this->model->getSupplier( $params );
        $view = decryptSupplier( $view );

        die(
            json_encode(
                array(
                    'success' => true
                    ,'view' => $view
                )
            )
        );
    }

    function saveForm() {
        $params = getData();

        if( $params['onEdit'] == 0 ){
            /*Checks if the reference num already exists. */
            $refMatch = $this->model->checkReferenceNumber( $params );
            if( $refMatch > 0 ) die(
                json_encode(
                    array(
                        'success'   => true
                        ,'match'    => 1
                    )
                )
            );
        }

        $params['preparedBy'] =  $this->session->userdata('USERID');
        if( $params['cancelTag'] == 1 ) $params['cancelledBy'] = $this->session->userdata('USERID');
        
        $date = date( 'Y-m-d', strtotime( $params['date'] ) ) . 
                " " . date( 'H:i:s', strtotime( date( 'Y-m-d', strtotime( $params['date'] ) ) .  $params['time'] ) );
        
        unset($params['time']);

        $invoiceParams = unsetParams( $params, 'invoices' );
        $invoiceParams['date'] = $date;
        
        if( $params['withPO'] == 1 ) $invoiceParams['fident'] = $params['poNumber'];

        if( isset($params['onEdit']) && $params['onEdit'] == 1 ) {
            $invoiceParams['onEdit'] = $params['onEdit'];
            $idInvoice = $params['idInvoice'];

            $this->model->saveInvoice( $invoiceParams );
        } else {
            $idInvoice = $this->model->saveInvoice( unsetParams( $invoiceParams, 'invoices' ) );
        }

        if( isset( $idInvoice ) && !empty( $idInvoice ) ) {
            $items = json_decode( $params['items'], true );
            $receivingParams['item'] = array();
            
            foreach( $items as $item) {
                if( $params['withPO'] == 1 ) {
                    if ( $item['referenceNum'] != 'None' ) $item['fident'] = $params['poNumber'];
                }
                $rawItem = $item;
                
                $item['idInvoice'] = $idInvoice;
                $item['idAffiliate'] = $params['idAffiliate'];
                $item['idCostCenter'] = $params['idCostCenter'];
                $item['refNum'] = $item['referenceNum'];
                $item['idReference'] = $params['idReference']; 
                $item['date'] = $date;
                $item['preparedBy'] = $params['preparedBy'];
                $item['qtyLeft'] = $item['qty'];
                $item['idSupplier'] = $params['pCode'];
                $item['fIDModule'] = 25 ; //RECEIVING MODULE

                $receivingParams['item'] = unsetParams( $item, 'receiving');
                $receivingParams['cancelTag'] = $params['cancelTag'];
                if( isset($params['onEdit']) ) $receivingParams['onEdit'] = $params['onEdit'];
                $idReceiving  = $this->model->saveReceiving( $receivingParams );
            }
        }

        if( isset( $idReceiving) && !empty( $idReceiving ) && isset( $params['journalEntries']) ) {
            $journalEntries = json_decode( $params['journalEntries'], true );
            $postingParams['items'] = array();
            foreach( $journalEntries as $journalEntry ) {
                $journalEntry['idReceiving'] = $idReceiving;
                $journalEntry['idInvoice'] = $idInvoice;
                array_push( $postingParams['items'], unsetParams( $journalEntry, 'posting' ) );
            }

            if( isset($params['onEdit']) ) $postingParams['onEdit'] = $params['onEdit'];
            $postingParams['idInvoice'] = $idInvoice;
            if( count($postingParams['items']) > 0 ) $idPosting = $this->model->savePosting( $postingParams );
        }

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

    function filterHistory(){
        $params = getData();
        $view = $this->model->filterHistory( $params );

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    function viewAll() {
        $params = getData();
        $params['idEmployee'] = $this->session->userdata('EMPLOYEEID');
        $view = $this->model->viewAll( $params );

        $_viewHolder = $view['view'];
        /**Decryption of Fields**/
        foreach( $_viewHolder as $idx => $record ){
            /**Decrypting affiliate**/
            if( isset( $record['affiliateSK'] ) && !empty( $record['affiliateSK'] ) ){
                $this->encryption->initialize( array('key' => generateSKED( $record['affiliateSK'] ) ) );
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $record['affiliateName'] );
            }

            /**Decrypting cost center**/
            if( isset( $record['costCenterSK'] ) && !empty( $record['costCenterSK'] ) ){
                $this->encryption->initialize( array('key' => generateSKED( $record['costCenterSK'] ) ) );
                $_viewHolder[$idx]['costCenterName'] = $this->encryption->decrypt( $record['costCenterName'] );
            }

            /**Decrypting supplier**/
            if( isset( $record['supplierSK'] ) && !empty( $record['supplierSK'] ) ){
                $this->encryption->initialize( array('key' => generateSKED( $record['supplierSK'] ) ) );
                $_viewHolder[$idx]['supplierName'] = $this->encryption->decrypt( $record['supplierName'] );
            }
        }
        $view['view'] = $_viewHolder;
        /**Ends here**/

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

    function getData(){
        $params = getData();
        $_chkUsage = $this->model->checkUsage( $params['idInvoice'] );
        $view = $this->model->getData( $params );
        $record = $view[0];

        // LQ();
        
        /** match
        **	0 = ok
        **	1 = record not found
        **	2 = record used
        **  3 = record cancelled
        **/
        
        // $match = ( $record['transUsage'] > 0 ) ? 2 : 0;
        // if( $_chkUsage > 0 ) $match = 2 
        $match = ( $_chkUsage > 0 ) ? 2 : 0;
        if( $record['cancelTag'] > 0 ) $match = 3;
        

        die(
            json_encode(
                array(
                    'success' => true
                    ,'match' => $match
                    ,'view' => $view
                )
            )
        );
    }

    function deleteRecord(){
        $params = getData();
        $match = $this->model->deleteRecord( $params );

        if( $match <= 0 ){
            $params = $this->model->getData( $params )[0];
            $params['delete'] = 1;
            $this->setLogs( $params );
        }

        die(
            json_encode(
                array(
                    'success' => true
                    ,'match' => $match
                )
            )
        );
    }

    function getInvoices(){
        $view = $this->model->viewAll([]);
        $list = [];

        foreach( $view['view'] as $v) {
            array_push( $list, array(
                'id' => $v['idInvoice']
                ,'name' => $v['referenceNumber']
            ));
       }
       
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $list
                )
            )
        );
    }

    function updateQty() {
        $params = getData();
        
        $items = json_decode( $params['items'], true );
        foreach( $items as $item ) {
            $this->model->updateQty( $item, $params['fident'], $params['status'], $params['idInvoice'] );
        }

        die(
            json_encode(
                array(
                    'success' => true
                )
            )
        );
    }

    function getSupplierDetails(){
        $params = getData();
        $view = $this->model->getSupplierDetails( $params );

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    function printExcel (){
		$data = getData();
		$sum = 0;
        $view = $this->model->viewAll( $data );
        
		$csvarray[] = array( 'title' => $data['pageTitle'].'' );
		$csvarray[] = array( 'space' => '' );
		$csvarray[] = array( 'space' => '' );

		$csvarray[] = array(
			'col1'  => 'Reference Number'
            ,'col2' => 'Date'
            ,'col3' => 'Affiliate'
            ,'col4' => 'Cost Center'
            ,'col5' => 'Supplier Name'
            ,'col6' => 'Received By'
            ,'col7' => 'Total Amount'
        );
        
    

		foreach( $view['view'] as $value ){
			$csvarray[] = array(
				'col1' => $value[ 'name' ]
                ,'col2' => $value[ 'date' ]
                ,'col3' => $value[ 'affiliateName' ]
                ,'col4' => $value[ 'costCenterName' ]
                ,'col5' => $value[ 'supplierName' ]
                ,'col6' => $value[ 'receivedBy' ]
                ,'col7' => $value[ 'amount' ]
			);
        }
        
		$data['description'] = '' .$data['pageTitle']. ": " .$this->USERNAME. ' printed an Excel report'  ;
		$data['iduser'] = $this->USERID;
		$data['usertype'] = $this->USERTYPEID;
		$data['printExcel'] = true;	
        $data['ident'] = null;

		writeCsvFile(
			array(
				'csvarray' 	 => $csvarray
				,'title' 	 => $data['pageTitle'].''
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

    function generatePDF() {
        $data = getData();

        $formDetails = json_decode( $data['form'], true );
        $receivingItems = json_decode( $data['receivingItems'], true );
        $journalEntries = json_decode( $data['journalEntries'], true );
        
        $header_fields = array(
            array(
                array(
                    'label' => 'Reference'
                    ,'value' => $formDetails['pdf_idReference'] . '-' .$formDetails['pdf_referenceNum']
                )
                ,array(
                    'label' => 'PO Number'
                    ,'value' => $formDetails['pdf_poNumber']
                )
                ,array(
                    'label' => 'Supplier'
                    ,'value' => $formDetails['pdf_pCode']
                )
                ,array(
                    'label' => 'Payment Type'
                    ,'value' => $formDetails['pdf_paymentType']
                )
                ,array(
                    'label' => 'Terms'
                    ,'value' => $formDetails['pdf_terms']
                )
            )
            ,array(
                array(
                    'label' => 'Cost Center'
                    ,'value' => $formDetails['pdf_idCostCenter']
                )
                ,array(
                    'label' => 'Date'
                    ,'value' => $formDetails['pdf_tdate']
                )
                ,array(
                    'label' => 'Due Date'
                    ,'value' => $formDetails['pdf_dueDate']
                )
                ,array(
                    'label' => 'Remarks'
                    ,'value' => $formDetails['pdf_remarks']
                )
            )
        );


        $table = array(
            array(
                'header'        => 'PO Number'
                ,'dataIndex'    => 'referenceNum'
                ,'width'        => '10'	
            ),
            array(
                'header'        => 'Code'
                ,'dataIndex'    => 'barcode'
                ,'width'        => '10'	
            ),
            array(
                'header'        => 'Item Name'
                ,'dataIndex'    => 'itemName'
                ,'width'        => '10'
            ),
            array(
                'header'        => 'Classification'
                ,'dataIndex'    => 'className'
                ,'width'        => '10'
            ),
            array(
                'header'        => 'Unit'
                ,'dataIndex'    => 'unitName'
                ,'width'        => '10'
            ),
            array(
                'header'        => 'Cost'
                ,'dataIndex'    => 'cost'
                ,'width'        => '10'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
            ),
            array(
                'header'        => 'Expiry Date'
                ,'dataIndex'    => 'expiryDate'
                ,'width'        => '10'
                ,'type'         => 'datecolumn'
                ,'format'       => 'm-d-Y'
            ),
            array(
                'header'        => 'Qty Left in PO'
                ,'dataIndex'    => 'qtyLeft'
                ,'width'        => '10'
                ,'type'         => 'numbercolumn'
            ),
            array(
                'header'        => 'Quantity'
                ,'dataIndex'    => 'qty'
                ,'width'        => '10'
                ,'type'         => 'numbercolumn'
            ),
            array(
                'header'        => 'Amount'
                ,'dataIndex'    => 'amount'
                ,'width'        => '10'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
            )
        );

        generateTcpdf(
			array(
				'file_name'         => $data['title']
                ,'folder_name'      => 'inventory'
                ,'header_fields'    => $header_fields
                ,'records'          =>  $receivingItems
                ,'header'           => $table
                ,'orientation'      => 'P'
                ,'params'           => $data
                ,'idAffiliate'      => $data['idAffiliate']
                ,'journalEntry'     => $journalEntries
                ,'hasPrintOption'   => $data['hasPrintOption']
                // ,'hasSignatories' => 1
			) 
        );
    }

    function customListPDF() {
        $data = getData();
        $receivingItems = json_decode( $data['gridHistory'], true );

        $table = array(
            array(
                'header'        => 'Reference Number'
                ,'dataIndex'    => 'name'
                ,'width'        => '14.2'	
            ),
            array(
                'header'        => 'Date'
                ,'dataIndex'    => 'date'
                ,'width'        => '14.2'
                ,'type'         => 'datecolumn'
                ,'format'       => 'm-d-Y'	
            ),
            array(
                'header'        => 'Affiliate'
                ,'dataIndex'    => 'affiliateName'
                ,'width'        => '14.2'
            ),
            array(
                'header'        => 'Cost Center'
                ,'dataIndex'    => 'costCenterName'
                ,'width'        => '14.2'
            ),
            array(
                'header'        => 'Supplier Name'
                ,'dataIndex'    => 'supplierName'
                ,'width'        => '14.2'
            ),
            array(
                'header'        => 'Received By'
                ,'dataIndex'    => 'receivedBy'
                ,'width'        => '14.2'
            ),
            array(
                'header'        => 'Total Amount'
                ,'dataIndex'    => 'amount'
                ,'width'        => '14.2'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
            )
        );

        generateTcpdf(
			array(
				'file_name'         => $data['title']
                ,'folder_name'      => 'inventory'
                ,'records'          =>  $receivingItems
                ,'header'           => $table
                ,'orientation'      => 'P'
                ,'params'           => $data
                // ,'hasSignatories'   => 1
			) 
        );
    }

    private function setLogs( $params ){
		$header = ucfirst( $this->USERFULLNAME );
		$action = '';
	
        switch( true ){
            case isset( $params['delete'] ):
                $action = 'deleted a';
            break;
            case isset( $params['cancelTag'] ) && (int)$params['cancelTag'] == 1:
                $action = 'cancelled a';
            break;
            default:
                if( isset( $params['action'] ) )
                    $action = $params['action'];
                else
                    $action = ( $params['onEdit'] == 1  ? 'edited a' : 'added a new receiving' );
            break;
        }
        
        $params['actionLogDescription'] = $header . ' ' . $action . ' transaction.';
        $params['idModule']             = 2;
		
		setLogs( $params );
    }

}