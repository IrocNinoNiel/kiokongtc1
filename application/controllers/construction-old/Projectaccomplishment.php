<?php if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

class Projectaccomplishment extends CI_Controller{

    public function __construct(){
        parent::__construct();
        middleware();
        $this->load->library('encryption');
        setHeader('construction/Projectaccomplishment_model');
    }

    function getAccomplishment() {
        $params = getData();
        $view   = $this->model->getAccomplishment($params);
        $view   = decryptAffiliate( $view );

        die(
            json_encode(
                array(
                     'success' => true
                    ,'view'    => $view
                )
            )
        );
    }

    function retrieveAccomplishment() {
        $params = getData();
        $view   = $this->model->retrieveAccomplishment($params);
        $view   = decryptAffiliate( $view );

        die(
            json_encode(
                array(
                     'success' => true
                    ,'view'    => $view
                )
            )
        );
    }

    function getBOQ() {
        $params = getData();
        $view   = $this->model->getBOQ($params);
        $view   = decryptItem( $view );

        die(
            json_encode(
                array(
                     'success' => true
                    ,'view'    => $view
                )
            )
        );
    }

    function getTrucking() {
        $params = getData();
        $view   = $this->model->getTrucking($params);
        $view   = decryptUserData( $view );

        die(
            json_encode(
                array(
                     'success' => true
                    ,'view'    => $view
                )
            )
        );
    }

    function getDisbursement() {
        $params = getData();
        $view   = $this->model->getDisbursement($params);

        die(
            json_encode(
                array(
                     'success' => true
                    ,'view'    => $view
                )
            )
        );
    }

    function getVouchers() {
        $params = getData();
        $view   = $this->model->getVouchers($params);

        die(
            json_encode(
                array(
                     'success' => true
                    ,'view'    => $view
                )
            )
        );
    }

    function getProjectName() {
        $params = getData();
        $view   = $this->model->getProjectName($params);

        die(
            json_encode(
                array(
                     'success' => true
                    ,'view'    => $view
                )
            )
        );
    }

    function getConsContract() {
        $params = getData();
        $view   = $this->model->getConsContract($params);

        die(
            json_encode(
                array(
                     'success'  => true
                    ,'view'     => $view
                )
            )
        );
    }

    function deleteProjectAccomplishment() {
        $match  = 0;
        $params = getData();
        $view   = $this->model->deleteProjectAccomplishment($params);

        die(
            json_encode(
                array(
                     'success'  => true
                    ,'view'     => $view
                    ,'match'    => $match
                )
            )
        );
    }

    function saveProjectAccomplishment() {
        $match      = 0;
        $params     = getData();
        $invoices   = json_decode($params['invoices'], true);   unset($params['invoices']);

        $this->db->trans_start();

        /** SAVING FOR INVOICES **/
        $invoices['date']   = date( 'Y-m-d', strtotime( $invoices['date'] ) ) . " " . date( 'H:i:s', strtotime( date( 'Y-m-d', strtotime( $invoices['date'] ) ) .  $invoices['time'] ) );
        $invoices['onEdit'] = $params['onEdit'];
        $idInvoice          = $this->model->saveInvoice( $invoices );

        /** SAVING FOR PROJECT ACCOMPLISHMENT **/
        $params['idInvoice']        = $idInvoice;
        $idProjectAccomplishment    = $this->model->saveProjectAccomplishment( $params );

        if($this->db->trans_status()){
			$this->db->trans_complete();

            $msg = ($params['onEdit']) ? ' edited the project accomplishment details.' : ' added a new project accomplishment.';

            setLogs(
                array(
                    'actionLogDescription' => $this->USERNAME . $msg
                    ,'idAffiliate'         => ( isset($params['idAffiliate']) && !empty( $params['idAffiliate'] ) ) ? $params['idAffiliate'] : $this->AFFILIATEID
                    ,'idEu'                => $this->USERID
                    ,'idModule'            => $invoices['idModule']
                    ,'time'                => date("H:i:s A"),
                )
            );

            die(
                json_encode(
                    array(
                        'success'   => true
                        ,'view'     => $idProjectAccomplishment
                        ,'match'    => $match
                    )
                )
            );

        }else{
            $this->db->trans_rollback();
            die(json_encode(array('success'=>false, 'match'=>0)));
        }
    }

    function printPDFForm() {
        $data       = getData();
        $form_data  = json_decode($data['form'], true);     unset($data['form']);
        $boq        = json_decode($data['boq'], true);      unset($data['boq']);
        $trucking   = json_decode($data['trucking'], true); unset($data['trucking']);

        $Main = array(
            'title' => 'Project Accomplishment Form',
            'file_name' => $data['title'] . ' Form',
            'folder_name' => 'pdf/construction/',
            'orientation' => 'L',
            'table_hidden' => true,
            'noTitle' => true,
            'grid_font_size' => 8
        );

        /** BOQ TABLE **/
        $boq_col    = array(
            array(
                'header'        => 'Item No.'
                ,'dataIndex'    => 'itemNo'
            ),
            array(
                'header'        => 'Description'
                ,'dataIndex'    => 'description'
            ),
            array(
                'header'        => 'Description'
                ,'dataIndex'    => 'approvedQty'
                ,'columnType'   => 'number'
            ),
            array(
                'header'        => 'Description'
                ,'dataIndex'    => 'approvedUnit'
            ),
            array(
                'header'        => 'Description'
                ,'dataIndex'    => 'itemPrice'
                ,'columnType'   => 'number'
                ,'columnType'   => 'float'
            ),
            array(
                'header'        => 'Description'
                ,'dataIndex'    => 'originalAmount'
                ,'columnType'   => 'float'
            ),
            array(
                'header'        => 'Description'
                ,'dataIndex'    => 'revisedQty'
                ,'columnType'   => 'number'
            ),
            array(
                'header'        => 'Description'
                ,'dataIndex'    => 'revisedAmount'
                ,'columnType'   => 'float'
            ),
            array(
                'header'        => 'Description'
                ,'dataIndex'    => 'totalQtyApprovedPreviousBilling'
                ,'columnType'   => 'number'
            ),
            array(
                'header'        => 'Description'
                ,'dataIndex'    => 'quantityApprovedInThisBilling'
                ,'columnType'   => 'number'
            ),
            array(
                'header'        => 'Description'
                ,'dataIndex'    => 'totalQtyApprovedDate'
                ,'columnType'   => 'number'
            ),
            array(
                'header'        => 'Description'
                ,'dataIndex'    => 'balancedQuantity'
                ,'columnType'   => 'number'
            ),
            array(
                'header'        => 'Description'
                ,'dataIndex'    => 'costOfPreviousBilling'
                ,'columnType'   => 'float'
            ),
            array(
                'header'        => 'Description'
                ,'dataIndex'    => 'costOfThisBilling'
                ,'columnType'   => 'float'
            ),
            array(
                'header'        => 'Description'
                ,'dataIndex'    => 'costToDate'
                ,'columnType'   => 'float'
            ),
            array(
                'header'        => 'Description'
                ,'dataIndex'    => 'balanceCost'
                ,'columnType'   => 'float'
            ),
        );

        $boqTable = '';
        $boqTable .= '<table border="1" style="width:100%;">';
        $boqTable .= '<tr style="text-align: center; font-weight: bold; background-color:#f1f1f1;">';
        $boqTable .= '<th rowspan="2" >Item No.</th>';
        $boqTable .= '<th rowspan="2" style="width: 200px;">Description</th>';
        $boqTable .= '<th colspan="4">Approved Original Contract</th>';
        $boqTable .= '<th colspan="2">Revised Due to Approved</th>';
        $boqTable .= '<th rowspan="2">Total Qty Approved in Previous Billing</th>';
        $boqTable .= '<th rowspan="2">Qty Approved in this Billing</th>';
        $boqTable .= '<th rowspan="2">Total Qty Approved in this Date</th>';
        $boqTable .= '<th rowspan="2">Balanced Qty</th>';
        $boqTable .= '<th rowspan="2">Cost of Previous Billing</th>';
        $boqTable .= '<th rowspan="2">Cost of this Billing</th>';
        $boqTable .= '<th rowspan="2">Cost to Date</th>';
        $boqTable .= '<th rowspan="2">Balance Cost</th>';
        $boqTable .= '</tr>';

        $boqTable .= '<tr style="text-align: center; font-weight: bold; background-color:#f1f1f1;">';
        $boqTable .= '<th>Quantity</th>';
        $boqTable .= '<th>Unit</th>';
        $boqTable .= '<th>Unit Cost</th>';
        $boqTable .= '<th>Amount</th>';
        $boqTable .= '<th>Quantity</th>';
        $boqTable .= '<th>Amount</th>';
        $boqTable .= '</tr>';
        $boqTable .= $this->createTableData($boq_col, $boq, false);
        $boqTable .= '</table>';

        /** TRUCKING TABLE **/
        $trucking_col    = array(
            array(
                'header'        => 'Plate Number'
                ,'dataIndex'    => 'plateNumber'
            ),
            array(
                'header'        => 'Truck Type'
                ,'dataIndex'    => 'truckType'
            ),
            array(
                'header'        => 'Driver'
                ,'dataIndex'    => 'driverName'
            ),
        );
        $truckingTable = '';
        $truckingTable .= '<table border="1" style="width:100%;">';
        $truckingTable .= $this->createTableData($trucking_col, $trucking, true);
        $truckingTable .= '</table>';

        $TOP = '<table cellspacing="10">
                    <tr>
                    	<td><span style="font-weight: bold;">Affiliate: </span>' . $this->AFFILIATENAME . '</td>
                        <td><span style="font-weight: bold">Date: </span>' . $form_data['pdf_tdate'] . '</td>
                    </tr>
                    <tr>
                        <td><span style="font-weight: bold;">Reference: </span>' . $form_data['pdf_idReference'] . '-' . $form_data['pdf_referenceNum'] . '</td>
                        <td><span style="font-weight: bold;">Cost Center: </span>' . $form_data['pdf_idCostCenter'] . '</td>
                    </tr>
                    <tr>
                        <td><span style="font-weight: bold;">Project Name: </span>' . $form_data['pdf_projectName'] . '</td>
                        <td ><span style="font-weight: bold;">Date Range: </span>' . $form_data['pdf_sdate'] . ' to ' . $form_data['pdf_edate'] . '</td>
                    </tr>
                    <tr>
                        <td><span style="font-weight: bold;">Contractor: </span>' . $form_data['pdf_contractor'] . '</td>
                        <td ><span style="font-weight: bold;">Project % Accomplishment: </span>' . $form_data['pdf_projectPercentAccomplished'] . '</td>
                    </tr>
                </table>
                <br>

                <br><br>
				<span style="font-weight: bold; font-size: 1.2em;">BOQ</span><br><hr/>'
                . $boqTable . '<br>

                <br><br>
				<span style="font-weight: bold; font-size: 1.2em;">Trucking</span><br><hr/>'
                . $truckingTable ;

        generate_table($Main,array(),array(),$TOP);
    }

    function createTableData($table_col = [], $table_data = [], $addTableHeader = true) {
        $col_length = count($table_col);
        $table = '';
        if($addTableHeader) {
            if(!empty($table_col)) {
                $table .= '<tr style="text-align: center; font-weight: bold; background-color:#f1f1f1;">';
                foreach($table_col as $col){
                    $table .= '<th>' . $col['header'] . '</th>';
                }
                $table .= '</tr>';
            }
        }
        
        if(!empty($table_data)) {
            foreach($table_data as $data_row){
                $data_row = json_decode($data_row, true);
                $table .= '<tr>';
                foreach($table_col as $data_col){
                    $text_align = isset($data_col['align'])? $data_col['align'] : 'left';
                    $value      = $data_row[$data_col['dataIndex']];

                    if(isset($data_col['columnType']) && ($data_col['columnType'] == 'number' || $data_col['columnType'] == 'float')) {
                        $text_align = 'right';

                        if($data_col['columnType'] == 'float') {
                            $value = number_format($value, 2);
                        }
                    }
                    $table .= '<td style="text-align: ' . $text_align . ';">' . $value . '</td>';
                }
                $table .= '</tr>';
            }
        } else {
            $table .= '<tr>';
            $table .= '<td colspan="' . $col_length . '" style="text-align: center;">No Records</td>';
            $table .= '</tr>';
        }
        
        return $table;
    }

    function printPDF() {
        $params = getData();
        $data   = $this->model->getAccomplishment($params);;
        $data   = decryptAffiliate( $data );

        $col    = array(
            array(
                'header'        => 'Affiliate'
                ,'dataIndex'    => 'affiliateName'
                ,'width'        => '15%'
            ),
            array(
                'header'        => 'Date'
                ,'dataIndex'    => 'date'
                ,'width'        => '10%'
            ),
            array(
                'header'        => 'Reference'
                ,'dataIndex'    => 'referenceNum'
                ,'width'        => '10%'
            ),
            array(
                'header'        => 'Contract ID'
                ,'dataIndex'    => 'idContract'
                ,'width'        => '10%'
            ),
            array(
                'header'        => 'Project Name'
                ,'dataIndex'    => 'projectName'
                ,'width'        => '20%'
            ),
            array(
                'header'        => 'Contractor'
                ,'dataIndex'    => 'contractorName'
                ,'width'        => '15%'
            ),
            array(
                'header'        => 'Date From'
                ,'dataIndex'    => 'dateFrom'
                ,'width'        => '10%'
            ),
            array(
                'header'        => 'Date To'
                ,'dataIndex'    => 'dateTo'
                ,'width'        => '10%'
            )
        );

        $header_fields = array(
            array(
                array(
                    'label'     => 'Date'
                    ,'value'    => date("Y-m-d")
                )
            )
        );

        setLogs(
            array(
                'actionLogDescription' => 'Exported the generated Project Accomplishment Report (PDF)'
                ,'idAffiliate'         => ( isset($params['idAffiliate']) && !empty( $params['idAffiliate'] ) ) ? $params['idAffiliate'] : $this->AFFILIATEID
                ,'idEu'                => $this->USERID
                ,'idModule'            => $params['idModule']
                ,'time'                => date("H:i:s A")
            )
        );

        generateTcpdf(
            array(
                'file_name'      => $params['title']
                ,'folder_name'   => 'construction'
                ,'records'       => $data
                ,'header'        => $col
                ,'orientation'   => 'P'
                ,'header_fields' => $header_fields
            )
        );
    }

    public function printExcel() {
        $params = getData();

        $data   = $this->model->getAccomplishment( $params );
        $data = decryptAffiliate( $data );

        $csvarray = array();
        $csvarray[] = array( 'title' => $params['title'] );
        $csvarray[] = array( 'space' => '' );
        $csvarray[] = array( 'Date ', date("Y-m-d")  );
        $csvarray[] = array( 'space' => '' );

        $csvarray[] = array(
            'Affiliate'
            ,'Date'
            ,'Reference'
            ,'Contract ID'
            ,'Project Name'
            ,'Contractor'
            ,'Date From'
            ,'Date To'
        );

        foreach( $data as $d ){
            $csvarray[] = array(
                $d['affiliateName']
                ,$d['date']
                ,$d['referenceNum']
                ,$d['idContract']
                ,$d['projectName']
                ,$d['contractorName']
                ,$d['dateFrom']
                ,$d['dateTo']
            );
        }

        setLogs(
            array(
                'actionLogDescription'  => 'Exported the generated Project Accomplishment Report (Excel).'
			    ,'idAffiliate'	        => $this->session->userdata('AFFILIATEID')
                ,'idEu'                 => $this->USERID
                ,'idModule'             => $params['idModule']
                ,'time'                 => date('H:i:s A')
            )
        );

        writeCsvFile(
            array(
                'csvarray' 	 => $csvarray
                ,'title' 	 => $params['title']
                ,'directory' => 'construction'
            )
        );
    }

    function download($title){
		force_download(
			array(
				'title'         => $title
				,'directory'    => 'construction'
			)
		);
	}
}

// function create_table($table_colrow = [], $table_data = []) {
    //     $table = '';
    //     $table .= '<table border="1" style="width:100%;">';

    //     foreach($table_colrow as $row) {
    //         $table .= '<tr style="text-align: center; font-weight: bold; background-color:#f1f1f1;">';

    //         foreach($row as $header_col) {
    //             $table .= '<th rowspan="' . (isset($header_col['rowspan'])? $header_col['rowspan'] : '1') . '" 
    //                         colspan="' . (isset($header_col['colspan'])? $header_col['colspan'] : '1') . '" 
    //                         style="width: ' . (isset($header_col['width'])? $header_col['width'] : 'auto') . ';">' . (isset($header_col['header'])? $header_col['header'] : '') . '</th>';
    //         }
    //         $table .= '</tr>';
    //     }
        
    //     // if(!empty($boq)) {
    //     //     foreach($boq as $key => $boq_item){
    //     //         $boq_item = json_decode($boq_item, true);
    //     //         $table .= '<tr>';
    //     //         $table .= '<td>'                             . $boq_item['itemNo']                                   . '</td>';
    //     //         $table .= '<td>'                             . $boq_item['description']                              . '</td>';
    //     //         $table .= '<td style="text-align: right;">'  . $boq_item['approvedQty']                              . '</td>';   
    //     //         $table .= '<td>'                             . $boq_item['approvedUnit']                             . '</td>';
    //     //         $table .= '<td style="text-align: right;">'  . number_format($boq_item['approitemPricevedQty'], 2)   . '</td>';
    //     //         $table .= '<td style="text-align: right;">'  . number_format($boq_item['originalAmount'], 2)         . '</td>';
    //     //         $table .= '<td style="text-align: right;">'  . $boq_item['revisedQty']                               . '</td>';   
    //     //         $table .= '<td style="text-align: right;">'  . number_format($boq_item['revisedAmount'], 2)          . '</td>';
    //     //         $table .= '<td style="text-align: right;">'  . $boq_item['totalQtyApprovedPreviousBilling']          . '</td>';
    //     //         $table .= '<td style="text-align: right;">'  . $boq_item['quantityApprovedInThisBilling']            . '</td>';
    //     //         $table .= '<td style="text-align: right;">'  . $boq_item['totalQtyApprovedDate']                     . '</td>';   
    //     //         $table .= '<td style="text-align: right;">'  . $boq_item['balancedQuantity']                         . '</td>';
    //     //         $table .= '<td style="text-align: right;">'  . number_format($boq_item['costOfPreviousBilling'], 2)  . '</td>';
    //     //         $table .= '<td style="text-align: right;">'  . number_format($boq_item['costOfThisBilling'], 2)      . '</td>';
    //     //         $table .= '<td style="text-align: right;">'  . $boq_item['costToDate']                               . '</td>';   
    //     //         $table .= '<td style="text-align: right;">'  . number_format($boq_item['balanceCost'], 2)            . '</td>';
    //     //         $table .= '</tr>';
    //     //     }
    //     // } else {
    //         $table .= '<tr>';
    //         $table .= '<td colspan="16" style="text-align: center;">No Records</td>';
    //         $table .= '</tr>';
    //     // }

    //     $table .= '</table>';

    //     return $table;
    // }