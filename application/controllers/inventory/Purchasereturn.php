<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Purchasereturn extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->library('encryption');
        setHeader( 'inventory/Purchasereturn_model' );
    }

    function getReceivingInvoice(){
        $params = getData();
        if( isset( $params['date'] ) ) $params['date'] = date( 'Y-m-d', strtotime( $params['date'] ) );
        $params['onEdit'] = ( empty( $params['onEdit'] ) ) ? 0 : 1;
        $view = $this->model->getReceivingInvoice( $params );

        // LQ();

        die(
            json_encode(
                array(
                    'success' => true
                    ,'view'=> $view
                )
            )
        );
    }

    function getItems() {
        $params = getData();
        $view = $this->model->getItems( $params );
        $view['view'] = decryptItem( $view['view'] );

        // LQ();

        die(
            json_encode(
                array(
                    'success' => true
                    ,'view'=> $view['view']
                )
            )
        );
    }

    function saveForm() {
        $params = getData();
        
        if( isset( $params['onEdit']) && $params['onEdit'] == 0 ){
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
        
        $idInvoice            =  (int)$params['idInvoice'];
        $params['date'] = date( 'Y-m-d', strtotime( $params['date'] ) ) . 
        " " . date( 'H:i:s', strtotime( date( 'Y-m-d', strtotime( $params['date'] ) ) .  $params['time'] ) );

        unset( $params['time'] );

        /* retrieve releasing - put back to receiving - delete releasing */
        $this->model->addReceivingQty( $idInvoice );
        $this->model->deleteReleasing( $idInvoice );

        $invoiceParams = unsetParams( $params, 'invoices' );
        $idInvoice = $this->model->saveInvoice( $invoiceParams );
        
        if( isset( $idInvoice ) && !empty( $idInvoice ) ) {
            $items = json_decode( $params['items'], true );
            $releasingParams['item'] = array();

            foreach( $items as $item) {
                $fident = (isset( $params['onEdit'] ) && $params['onEdit'] == 1 ? $item['fident'] : $item['idReceiving'] );
                $rawItem = $item;

                $item['fIdent'] = $fident;
                $item['idInvoice'] = $idInvoice;
                $item['idAffiliate'] = $params['idAffiliate'];
                $item['idCostCenter'] = $params['idCostCenter'];
                $item['idReference'] = $params['idReference']; 
                $item['idReferenceSeries'] = $params['idReferenceSeries'];
                $item['date'] = $params['date'];
                $item['idSupplier'] = $params['pCode'];
                $item['preparedBy'] = $params['preparedBy'];
                $item['frefnum'] = $item['referenceNum'];
                $item['fref'] = $item['idReference'];
                $item['qtyLeft'] = $item['qty'];

                $releasingParams['item'] = unsetParams( $item , 'releasing' );
                if( isset($params['onEdit']) ) $releasingParams['onEdit'] = $params['onEdit'];

                $this->model->minusReceivingQty( $releasingParams['item'] );
                $idReleasing = $this->model->saveReleasing( $releasingParams );

                //Saving updated qtyLeft in Releasing.
                // if( isset($item['fIdent']) && $item['fIdent'] != null ) $this->model->updateQty( $rawItem, $item['fIdent'], 2, '' );
            }

            
        }

        if( isset( $idReleasing) && !empty( $idReleasing )  && isset( $params['journalEntries']) ) {
            $journalEntries = json_decode( $params['journalEntries'], true );
            $postingParams['items'] = array();
            foreach( $journalEntries as $journalEntry ) {
                $journalEntry['idReleasing'] = $idReleasing;
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
                    ,'match' => ( isset ($idReleasing) && !empty( $idReleasing ) ) ? 0 : 1
                )
            )
        );
    }

    function viewAll(){
        $params = getData();
        $params['idEmployee'] = $this->session->userdata('EMPLOYEEID');
        $view = $this->model->viewAll( $params );

        $_viewHolder = $view['view'];
        /**Custom decryption for Purchase Return**/
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

    function getData() {
        $params = getData();
        $view = $this->model->getRecord( $params );
        $record = $view[0];

        /** match
        **	0 = ok
        **	1 = record not found
        **	2 = record used
        **  3 = record cancelled
        **/
        
        // $match = ( $record['transUsage'] > 0 ) ? 2 : 0;
        $match = 0;
        if( $record['cancelTag'] > 0 ) $match = 3;

        // $match = ( $record['cancelTag'] > 0 ) ? 3 : 2;

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
        $isUsed = $this->model->deleteRecord( $params );

        if( $isUsed > 0 ){
            $match = 1;
        } else {
            $match = 0;

            $params = $this->model->getRecord( $params )[0];
            $params['delete'] = 1;
            $this->setLogs( $params );
        }

        die(
            json_encode(
                array(
                    'success' => true
                    ,'match' => ( $isUsed <= 0 ) ? 0 : 1 
                )
            )
        );
    }

    function updateQty() {
        $params = getData();
        
        $items = json_decode( $params['items'], true );
        foreach( $items as $item ) {
            $this->model->updateQty( $item, $params['fIdent'], $params['status'], $params['idInvoice'] );
        }

        die(
            json_encode(
                array(
                    'success' => true
                )
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
                    $action = ( $params['onEdit'] == 1  ? 'edited a' : 'added a new purchase return' );
            break;
        }
        
        $params['actionLogDescription'] = $header . ' ' . $action . ' transaction.';
        $params['idModule']             = 29;
		
		setLogs( $params );
    }

    function printPDF() {
        $data = getData();

        $formDetails = json_decode( $data['form'], true );
        $itemStore = json_decode( $data['itemStore'], true );
        $journalEntries = json_decode( $data['journalEntries'], true );
        
        $header_fields = array(
            array(
                array(
                    'label' => 'Cost Center'
                    ,'value' => $formDetails['pdf_idCostCenter']
                )
                ,array(
                    'label' => 'Reference'
                    ,'value' => $formDetails['pdf_idReference'] . '-' .$formDetails['pdf_referenceNum']
                )
                ,array(
                    'label' => 'Supplier'
                    ,'value' => $formDetails['pdf_pCode']
                )
                ,array(
                    'label' => 'Invoice'
                    ,'value' => $formDetails['pdf_fident']
                )
            )
            ,array(
                array(
                    'label' => 'Date'
                    ,'value' => $formDetails['pdf_tdate']
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
                ,'width'        => '11'	
            ),
            array(
                'header'        => 'Code'
                ,'dataIndex'    => 'barcode'
                ,'width'        => '6'	
            ),
            array(
                'header'        => 'Item Name'
                ,'dataIndex'    => 'itemName'
                ,'width'        => '21'
            ),
            array(
                'header'        => 'Classification'
                ,'dataIndex'    => 'className'
                ,'width'        => '12'
            ),
            array(
                'header'        => 'Unit'
                ,'dataIndex'    => 'unitName'
                ,'width'        => '6'
            ),
            array(
                'header'        => 'Cost'
                ,'dataIndex'    => 'cost'
                ,'width'        => '11'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
            ),
            array(
                'header'        => 'Expiry Date'
                ,'dataIndex'    => 'expiryDate'
                ,'width'        => '11'
                ,'type'         => 'datecolumn'
                ,'format'       => 'm-d-Y'
            ),
            array(
                'header'        => 'Quantity'
                ,'dataIndex'    => 'qty'
                ,'width'        => '11'
                ,'type'         => 'numbercolumn'
            ),
            array(
                'header'        => 'Amount'
                ,'dataIndex'    => 'amount'
                ,'width'        => '11'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
            )
        );

        generateTcpdf(
			array(
				'file_name'         => $data['title']
                ,'folder_name'      => 'inventory'
                ,'header_fields'    => $header_fields
                ,'records'          => $itemStore
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

    function customListPDF(){
        $params = getData();

        $table = array(
            array(
                'header'        =>'Affiliate'
                ,'dataIndex'    =>'affiliateName'
                ,'width'        =>'14.28'
            ),
            array(
                'header'        =>'Cost Center'
                ,'dataIndex'    =>'costCenterName'
                ,'width'        =>'14.28'
            ),
            array(
                'header'        =>'Date'
                ,'dataIndex'    =>'date'
                ,'width'        =>'14.28'
            ),
            array(
                'header'        =>'Reference Number'
                ,'dataIndex'    =>'name'
                ,'width'        =>'14.28'
            ),
            array(
                'header'        =>'Supplier Name'
                ,'dataIndex'    =>'supplierName'
                ,'width'        =>'14.28'
            ),
            array(
                'header'        =>'Prepared By'
                ,'dataIndex'    =>'preparedBy'
                ,'width'        =>'14.28'
            ),
            array(
                'header'        =>'Total Amount'
                ,'dataIndex'    =>'amount'
                ,'width'        =>'14.28'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
            )
        );

        generateTcpdf(
			array(
				'file_name'         => 'Purchase Return List'
                ,'folder_name'      => 'inventory'
                ,'records'          => json_decode($params['items'], true)
                ,'header'           => $table
                ,'orientation'      => 'P'
                ,'idAffiliate'      => $this->session->userdata('AFFILIATEID')
			) 
        );
    }
}