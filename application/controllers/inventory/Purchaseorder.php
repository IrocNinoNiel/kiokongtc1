<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Purchaseorder extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->library('encryption');
        setHeader( 'inventory/Purchaseorder_model' );
    }

    function getSupplier() {
        $params = getData();
        $view = $this->model->getSupplier( $params );
        
        $view = decryptSupplier( $view );
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

    function getSupplierDetails(){
        $params = getData();
        $view = $this->model->getSupplierDetails( $params );
        $view = (object) decryptSupplier( array( 0 => (array)$view ) )[0];
        unset( $view->sk ); //Removing the sk for retrieving.

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    function viewAll(){
        $params = getData();
        $params['idEmployee'] = $this->session->userdata('EMPLOYEEID');
        $view = $this->model->viewAll( $params );

        /**Custom Decryption for Purchase Order**/
        $_viewHolder = $view['view'];
        foreach( $_viewHolder as $idx => $po ){
            
            /**Decrypting affiliate**/
            if( isset( $po['affiliateSK'] ) && !empty( $po['affiliateSK'] ) ){
                $this->encryption->initialize( array('key' => generateSKED( $po['affiliateSK'] ) ) );
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
            }

            /**Decrypting cost center name**/
            if( isset( $po['costCenterSK'] ) && !empty( $po['costCenterSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['costCenterSK'] )));
                $_viewHolder[$idx]['costCenterName'] = $this->encryption->decrypt( $po['costCenterName'] );
            }

            /**Decrypting supplier**/
            if( isset( $po['supplierSK'] ) && !empty( $po['supplierSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['supplierSK'] )));
                $_viewHolder[$idx]['supplierName'] = $this->encryption->decrypt( $po['supplierName'] );
            }
            
            /**Decrypting  user [ORDERED BY]**/
            if( isset( $po['orderedBySK'] ) && !empty( $po['orderedBySK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['orderedBySK'] )));
                $_viewHolder[$idx]['orderedBy'] = $this->encryption->decrypt( $po['orderedBy'] );
            }

            /**Decrypting  user [NOTED BY]**/
            if( isset( $po['notedBySK'] ) && !empty( $po['notedBySK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['notedBySK'] )));
                $_viewHolder[$idx]['notedBy'] = $this->encryption->decrypt( $po['notedBy'] );
            }
            
        }

        $view['view'] = $_viewHolder;
        /**Ends here**/

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

    function saveForm(){
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

        $date = date( 'Y-m-d', strtotime( $params['date'] ) ) . 
                " " . date( 'H:i:s', strtotime( date( 'Y-m-d', strtotime( $params['date'] ) ) .  $params['time'] ) );
                
        $invoiceParams = unsetParams( $params, 'invoices' );
        unset( $invoiceParams['time'] );
        $invoiceParams['date'] = $date;
        if( isset($params['onEdit']) ) $invoiceParams['onEdit'] = $params['onEdit'];
        $idInvoice = $this->model->saveInvoice( $invoiceParams );

        // LQ();

        if( isset( $idInvoice ) && !empty( $idInvoice ) ) {
            $items = json_decode( $params['items'], true );
            $poParams['items'] = array();
            foreach( $items as $item) {
                $item['idInvoice'] = $idInvoice;
                $item['idAffiliate'] = $params['idAffiliate'];
                $item['idCostCenter'] = $params['idCostCenter'];
                $item['idReference'] = $params['idReference']; 
                $item['idReferenceSeries'] = $params['idReferenceSeries'];
                $item['date'] = $date;
                $item['dueDate'] = $params['dueDate'];
                $item['referenceNum'] = $params['referenceNum'];
                $item['idSupplier'] = $params['pCode'];
                $item['preparedBy'] = $params['preparedBy'];
                $item['qtyLeft'] = $item['qty'];
                $item['idItemClass'] = $item['idItemClass'];
                array_push( $poParams['items'], unsetParams( $item, 'po') );
            }

            if( isset($params['onEdit']) ) $poParams['onEdit'] = $params['onEdit'];
            $poParams['idInvoice'] = $idInvoice;
            if( count($poParams['items']) > 0 ) $idpo = $this->model->savePO( $poParams );
        }

        if( isset( $idpo) && !empty( $idpo ) && isset( $params['journalEntries']) ) {
            $journalEntries = json_decode( $params['journalEntries'], true );
            $postingParams['items'] = array();
            foreach( $journalEntries as $journalEntry ) {
                $journalEntry['idPo'] = $idpo;
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
                    ,'match' => ( isset ($idpo) && !empty( $idpo ) ) ? 0 : 1
                )
            )
        );
    }

    function getData(){
        $params = getData();
        $view = $this->model->getData( $params );
        $record = $view[0];

        /* Check if transaction is already used or cancelled */
        $match = ( $record['transactionIsUsed'] > 0 ) ? 2 : 0;
        if( $record['cancelTag'] > 0 ) $match = 3;

        // LQ();

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

    function getPO(){
        $params = getData();
        $view = $this->model->viewAll($params);
        $list = [];

        foreach( $view['view'] as $v) {
            array_push( $list, array(
                'id' => $v['idInvoice']
                ,'name' => $v['PONumber']
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

    function deleteRecord(){
        $params = getData();
        $isUsed = $this->model->deleteRecord( $params );
        
        if( $isUsed > 0 ){
            $match = 1;
        } else {
            $match = 0;

            $params = $this->model->getData( $params )[0];
            $params['delete'] = 1;
            $this->setLogs( $params );
        }
        
       

        die(
            json_encode(
                array(
                    'success' => true
                    ,'match' => !isset( $match ) ? 1 : $match
                )
            )
        );
    }

    function generatePDF() {
        $data = getData();

        $formDetails = json_decode( $data['form'], true );
        $poItems = json_decode( $data['poItems'], true );
        
        $header_fields = array(
            array(
                array(
                    'label' => 'Affiliate'
                    ,'value' => $formDetails['pdf_idAffiliate']
                )
                ,array(
                    'label' => 'Reference'
                    ,'value' => $formDetails['pdf_idReference'] . '-' .$formDetails['pdf_referenceNum']
                )
                ,array(
                    'label' => 'Supplier Name'
                    ,'value' => $formDetails['pdf_pCode']
                )
                ,array(
                    'label' => 'Address'
                    ,'value' => $formDetails['pdf_address']
                )
                ,array(
                    'label' => 'TIN'
                    ,'value' => $formDetails['pdf_tin']
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
                'header'=>'Item Code'
                ,'dataIndex'=>'barcode'
                ,'width'=>'16.6'	
            ),
            array(
                'header'=>'Item Name'
                ,'dataIndex'=>'itemName'
                ,'width'=>'16.6'
            ),
            array(
                'header'=>'Classification'
                ,'dataIndex'=>'className'
                ,'width'=>'16.6'
            ),
            array(
                'header'=>'Cost'
                ,'dataIndex'=>'cost'
                ,'width'=>'16.6'
                ,'type' => 'numbercolumn'
                ,'format' => '0,000.00'
            ),
            array(
                'header'=>'Quantity'
                ,'dataIndex'=>'qty'
                ,'width'=>'16.6'
                ,'type' => 'numbercolumn'
            ),
            array(
                'header'=>'Amount'
                ,'dataIndex'=>'amount'
                ,'width'=>'16.6'
                ,'type' => 'numbercolumn'
                ,'format' => '0,000.00'
            )
        );

        generateTcpdf(
			array(
				'file_name'         => $data['title']
                ,'folder_name'      => 'inventory'
                ,'header_fields'    => $header_fields
                ,'records'          => $poItems
                ,'header'           => $table
                ,'orientation'      => 'P'
                ,'params'           => $data
                ,'idAffiliate'      => $data['idAffiliate']
                // ,'hasJournalEntry'  => ( isset( $data['hasPrintOption']) ) ? 1 : 0
                // ,'hasPrintOption'   => $data['hasPrintOption']
                // ,'hasSignatories' => 1
			) 
        );
    }

    function customListPDF(){
        $params = getData();

        $table = array(
            array(
                'header'        =>'PO Number'
                ,'dataIndex'    =>'name'
                ,'width'        =>'14.28'
            ),
            array(
                'header'        =>'Date'
                ,'dataIndex'    =>'date'
                ,'width'        =>'14.28'
            ),
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
                'header'        =>'Supplier Name'
                ,'dataIndex'    =>'supplierName'
                ,'width'        =>'14.28'
            ),
            array(
                'header'        =>'Ordered By'
                ,'dataIndex'    =>'orderedBy'
                ,'width'        =>'14.28'
            ),
            array(
                'header'        =>'Amount'
                ,'dataIndex'    =>'amount'
                ,'width'        =>'14.28'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
            )
        );

        generateTcpdf(
			array(
				'file_name'         => 'Purchase Order List'
                ,'folder_name'      => 'inventory'
                ,'records'          => json_decode($params['items'], true)
                ,'header'           => $table
                ,'orientation'      => 'P'
                ,'idAffiliate'      => $this->session->userdata('AFFILIATEID')
			) 
        );
    }

    function printExcel (){
		$data = getData();
		$sum = 0;
        $view = $this->model->viewAll( $data );

        /**Custom Decryption for Purchase Order**/
        $_viewHolder = $view['view'];
        foreach( $_viewHolder as $idx => $po ){
            
            /**Decrypting affiliate**/
            if( isset( $po['affiliateSK'] ) && !empty( $po['affiliateSK'] ) ){
                $this->encryption->initialize( array('key' => generateSKED( $po['affiliateSK'] ) ) );
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
            }

            /**Decrypting cost center name**/
            if( isset( $po['costCenterSK'] ) && !empty( $po['costCenterSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['costCenterSK'] )));
                $_viewHolder[$idx]['costCenterName'] = $this->encryption->decrypt( $po['costCenterName'] );
            }

            /**Decrypting supplier**/
            if( isset( $po['supplierSK'] ) && !empty( $po['supplierSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['supplierSK'] )));
                $_viewHolder[$idx]['supplierName'] = $this->encryption->decrypt( $po['supplierName'] );
            }
            
            /**Decrypting  user [ORDERED BY]**/
            if( isset( $po['orderedBySK'] ) && !empty( $po['orderedBySK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['orderedBySK'] )));
                $_viewHolder[$idx]['orderedBy'] = $this->encryption->decrypt( $po['orderedBy'] );
            }

            /**Decrypting  user [NOTED BY]**/
            if( isset( $po['notedBySK'] ) && !empty( $po['notedBySK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $po['notedBySK'] )));
                $_viewHolder[$idx]['notedBy'] = $this->encryption->decrypt( $po['notedBy'] );
            }
            
        }

        $view['view'] = $_viewHolder;
        
		$csvarray[] = array( 'title' => $data['pageTitle'].'' );
		$csvarray[] = array( 'space' => '' );
		$csvarray[] = array( 'space' => '' );

		$csvarray[] = array(
			'col1'  => 'PO Number'
            ,'col2' => 'Date'
            ,'col3' => 'Affiliate'
            ,'col4' => 'Cost Center'
            ,'col5' => 'Supplier Name'
            ,'col6' => 'Ordered By'
            ,'col7' => 'Noted By'
            ,'col8' => 'Status'
            ,'col9' => 'Total Amount'
        );
        
    

		foreach( $view['view'] as $value ){
			$csvarray[] = array(
				'col1' => $value[ 'PONumber' ]
                ,'col2' => $value[ 'date' ]
                ,'col3' => $value[ 'affiliateName' ]
                ,'col4' => $value[ 'costCenterName' ]
                ,'col5' => $value[ 'name' ]
                ,'col6' => $value[ 'orderedBy' ]
                ,'col7' => $value[ 'notedBy' ]
                ,'col8' => $value[ 'status' ]
                ,'col9' => $value[ 'amount' ]
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

    private function setLogs( $params ){
		$header = ucfirst( $this->USERFULLNAME );
		$action = '';
	
        switch( true ){
            case isset( $params['delete'] ):
                $action = 'removed the';
            break;
            case isset( $params['cancelTag'] ) && (int)$params['cancelTag'] == 1:
                $action = 'cancelled the';
            break;
            default:
                if( isset( $params['action'] ) )
                    $action = $params['action'];
                else
                    $action = ( $params['onEdit'] == 1  ? 'modified the' : 'added a new' );
            break;
        }
        
        $params['actionLogDescription'] = $header . ' ' . $action . ' transaction.';
        $params['idModule']             = 2;
		
		setLogs( $params );
    }
}