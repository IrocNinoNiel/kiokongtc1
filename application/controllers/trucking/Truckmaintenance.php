<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Truckmaintenance extends CI_Controller{

    public function __construct(){
        parent::__construct();
        middleware();
        $this->load->library('encryption');
        setHeader('trucking/Truckmaintenance_model');
    }

    function getTruckMaintenance() {
        $params  = getData(); 
        $view    = $this->model->getTruckMaintenance($params);

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                    ,'params'   => $this->AFFILIATEID

                )
            )
        );
    }

    public function saveTruckMaintenance() {
        $match  = 0;
        $params = getData();

        $invoices         = json_decode($params['invoices'], true);         unset($params['invoices']);
        $truckMaintenance = json_decode($params['truckMaintenance'], true); unset($params['truckMaintenance']);
        $filterParts      = json_decode($params['filterParts'], true);      unset($params['filterParts']);
        $tires            = json_decode($params['tires'], true);            unset($params['tires']);
        $others           = json_decode($params['others'], true);           unset($params['others']);

        $this->db->trans_start();
        
        /** SAVING FOR INVOICES **/
        $invoices['date']   = date( 'Y-m-d', strtotime( $invoices['date'] ) ) . " " . date( 'H:i:s', strtotime( date( 'Y-m-d', strtotime( $invoices['date'] ) ) .  $invoices['time'] ) );
        $invoices['onEdit'] = $params['onEdit'];
        $idInvoice          = $this->model->saveInvoice( $invoices );

        /** SAVING FOR TRUCK MAINTENANCE **/
        $truckMaintenance['idInvoice']  = $idInvoice;
        $truckMaintenance['onEdit']     = $params['onEdit'];
        $idTruckMaintenance             = $this->model->saveTruckMaintenance( $truckMaintenance );

        /** SAVING FOR FILTERS, AND PARTS **/
        $this->model->deleteFilterParts($idTruckMaintenance);
        foreach($filterParts as $key => $filterPart){
            $filterPartsType = $key == 'filtersGrid'? 0 : 1;
            foreach($filterPart as $itemKey => $item) {
                
                $components = json_decode($item, true);

                foreach($components as $cmpKey => $component ) {
                    $components[$cmpKey]['onEdit']              = $params['onEdit'];
                    $components[$cmpKey]['filterPartsType']     = $filterPartsType;
                    $components[$cmpKey]['idTruckMaintenance']  = $idTruckMaintenance;
                    $components[$cmpKey]['filterPartName']      = $itemKey;
                    $components[$cmpKey]['dateInstalled']       = empty($components[$cmpKey]['dateInstalled'])? date("Y-m-d") : $components[$cmpKey]['dateInstalled'];
                    $components[$cmpKey]['dueDate']             = empty($components[$cmpKey]['dueDate'])? date("Y-m-d") : $components[$cmpKey]['dueDate'];
                    unset($components[$cmpKey]['selected']); 
                    $this->model->saveFilterParts( $components[$cmpKey] );
                }
            }
        }

        /** SAVING FOR TIRES **/
        foreach($tires as $key => $tire){
            $tire = json_decode($tire, true);

            $tire['onEdit'] = $params['onEdit'];
            $tire['idTruckMaintenance'] = $idTruckMaintenance;
            $this->model->saveTire( $tire );
        }

        /** SAVING FOR OTHERS **/
        foreach($others as $key => $other){
            $other = json_decode($other, true);

            $other['onEdit'] = $params['onEdit'];
            $other['idTruckMaintenance'] = $idTruckMaintenance;
            $this->model->saveOthers( $other );
        }

		if($this->db->trans_status()){
			$this->db->trans_complete();

            $msg = ($params['onEdit']) ? ' edited the truck maintenance details.' : ' added a new truck maintenance.';

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
                        ,'view'     => $idTruckMaintenance
                        ,'match'    => $match
                    )
                )
            );

        }else{
            $this->db->trans_rollback();
            die(json_encode(array('success'=>false, 'match'=>0)));
        }
    }

    public function deleteTruckMaintenance() {
        $params = getData();
        $match = $this->model->deleteTruckMaintenance($params);

        if(!$match) {
            setLogs(
                array(
                    'actionLogDescription' => $this->USERNAME . ' deleted a truck maintenance.'
                    ,'idAffiliate'         => ( isset($params['idAffiliate']) && !empty( $params['idAffiliate'] ) ) ? $params['idAffiliate'] : $this->AFFILIATEID
                    ,'idEu'                => $this->USERID
                    ,'idModule'            => $params['idModule']
                    ,'time'                => date("H:i:s A")
                )
            );  
        }      

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'match'    => $match
                )
            )
        );
    }
 
    public function getTruckType() {
        $params = getData();
        $view   = $this->model->getTruckType($params);
        die(
            json_encode(
                array(
                     'success' => true
                    ,'view'    => $view,
                )
            )
        );
    }

    public function getPlateNumber() {
        $params = getData();
        $view   = $this->model->getPlateNumber($params);
        die(
            json_encode(
                array(
                    'success'   => true
                    , 'view'    => $view,
                )
            )
        );
    }

    public function getOdometer() {
        $params = getData();
        $view   = $this->model->getOdometer($params);
        die(
            json_encode(
                array(
                    'success'   => true
                    , 'view'    => $view,
                )
            )
        );
    }

    public function getFilterParts() {
        $params = getData();
        $filterParts = json_decode($params['filterParts'], true);

        foreach($filterParts['filtersGrid'] as $key => $filterPart) {
            $filters[$key] = json_encode($this->model->getFilterParts($params, 0, $key));
        }

        foreach($filterParts['filtersGrid'] as $key => $filterPart) {
            $parts[$key] = json_encode($this->model->getFilterParts($params, 1, $key));
        }

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'parts'    => $parts
                    ,'filters'  => $filters
                )
            )
        );
    }

    public function getTire() {
        $params = getData();

        if(isset($params['onEdit']) && !$params['onEdit']) die(json_encode(array('success' => true)));

        $view   = $this->model->getTire($params);

        die(
            json_encode(
                array(
                    'success'   => true
                    , 'view'    => $view,
                )
            )
        );
    }

    public function getOthers() {
        $params = getData();

        if(isset($params['onEdit']) && !$params['onEdit']) die(json_encode(array('success' => true)));

        $view   = $this->model->getOthers($params);
        die(
            json_encode(
                array(
                    'success'   => true
                    , 'view'    => $view,
                )
            )
        );
    }

    function printPDF(){
        $params     = getData();
        $data       = $this->model->getTruckMaintenance( $params );

        $col = array(
            array(   
                'header'        => 'Date'
                ,'dataIndex'    => 'date'
                ,'width'        => '10%'
            ),
            array(   
                'header'        => 'Reference Number'
                ,'dataIndex'    => 'idReference'
                ,'width'        => '15%'
            ),
            array(   
                'header'        => 'Type'
                ,'dataIndex'    => 'type'
                ,'width'        => '15%'
            ),
            array(   
                'header'        => 'Plate Number'
                ,'dataIndex'    => 'plateNumber'
                ,'width'        => '20%'
            ),
            array(   
                'header'        => 'Model'
                ,'dataIndex'    => 'model'
                ,'width'        => '10%'
            ),
            array(   
                'header'        => 'Odometer'
                ,'dataIndex'    => 'odometer'
                ,'width'        => '10%'
            ),
            array(   
                'header'        => 'Remarks'
                ,'dataIndex'    => 'remarks'
                ,'width'        => '20%'
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
                'actionLogDescription' => 'Exported the generated Truck Maintenance Report (PDF).'
                ,'idAffiliate'         => ( isset($params['idAffiliate']) && !empty( $params['idAffiliate'] ) ) ? $params['idAffiliate'] : $this->AFFILIATEID
                ,'idEu'                => $this->USERID
                ,'idModule'            => $params['idModule']
                ,'time'                => date("H:i:s A")
            )
        );

        generateTcpdf(
            array(
                'file_name'      => $params['title']
                ,'folder_name'   => 'trucking'
                ,'records'       => $data
                ,'header'        => $col
                ,'orientation'   => 'P'
                ,'header_fields' => $header_fields
            )
        );
    }

    public function printExcel() {
        $params = getData();
        $data   = $this->model->getTruckMaintenance( $params );
        
        $csvarray = array();

        $csvarray[] = array( 'title' => $params['title'] );
        $csvarray[] = array( 'space' => '' );

        $csvarray[] = array( 'Date ', date("Y-m-d")  );
        $csvarray[] = array( 'space' => '' );

        $csvarray[] = array(
             'Date'
            ,'Reference Number'
            ,'Type'
            ,'Plate Number'
            ,'Model'
            ,'Odometer'
            ,'Remarks'
        );

        foreach( $data as $d ){
            $csvarray[] = array(
                 $d['date']
                ,$d['idReference']
                ,$d['type']
                ,$d['plateNumber']
                ,$d['model']
                ,$d['odometer']
                ,$d['remarks']
            );
        }

        setLogs(
            array(
                'actionLogDescription' => 'Exported the generated Truck Maintenance Report (Excel).'
			    ,'idAffiliate'		   => $this->session->userdata('AFFILIATEID')
                ,'idEu'                => $this->USERID
                ,'idModule'            => $params['idModule']
                ,'time'                => date('H:i:s A')
            )
        );

        writeCsvFile(
            array(
                'csvarray' 	 => $csvarray
                ,'title' 	 => $params['title']
                ,'directory' => 'trucking'
            )
        );
    }

    function download($title){
		force_download(
			array(
				'title'         => $title
				,'directory'    => 'trucking'
			)
		);
	}

    function is_damaged($is_damaged) {
        return $is_damaged? 'Yes' : 'No';
    }

    function printPDFForm(){ 
        $data           = getData();
        $form_data      = json_decode($data['form'], true);         unset($data['form']);
        $filterParts    = json_decode($data['filterParts'], true);  unset($data['filterParts']);
        $tires          = json_decode($data['tires'], true);        unset($data['tires']);
        $others         = json_decode($data['others'], true);       unset($data['others']);

        $Main = array(
            'title' => 'Truck Maintenance Form',
            'file_name' => $data['title'] . ' Form',
            'folder_name' => 'pdf/trucking/',
            'orientation' => 'P',
            'table_hidden' => true, 
            'noTitle' => true,
            'grid_font_size' => 9
        );

        // FILTERS TABLE
        $filtersTable = '';
        $filtersTable .= '<table style="width:100%;">';
        foreach($filterParts['filtersGrid'] as $itemKey => $item) {
            $filtersTable .= '<br><br>';
            $filtersTable .= '<tr><span style="font-weight:bold; font-size: 1.1em;">' . $itemKey . '</span><br><table border = "1" style="width:100%;">';

            $filtersTable .= '<tr>';
            $filtersTable .= '<th>Date Installed</th>';
            $filtersTable .= '<th>Mileage</th>';
            $filtersTable .= '<th>Due Date</th>';
            $filtersTable .= '<th>Remarks</th>';
            $filtersTable .= '<th>Damaged</th>';
            $filtersTable .= '</tr>';
                     
            $components = json_decode($item, true);
            if(!empty($components)) {
                foreach($components as $cmpKey => $component ) {
                    $filtersTable .= '<tr>';
                    $filtersTable .= '<td>' . $component['dateInstalled'] . '</td>';
                    $filtersTable .= '<td>' . $component['mileage'] . '</td>';
                    $filtersTable .= '<td>' . $component['dueDate'] . '</td>';   
                    $filtersTable .= '<td>' . $component['remarks'] . '</td>';
                    $filtersTable .= '<td>' . $this->is_damaged($component['damage']) . '</td>';
                    $filtersTable .= '</tr>';
                }
            } else {
                $filtersTable .= '<tr>';
                $filtersTable .= '<td colspan="5" style="text-align: center;">No Records</td>';
                $filtersTable .= '</tr>';
            }
            $filtersTable .= '</table></tr>';
        }
        $filtersTable .= '</table>';

        // PARTS TABLE
        $partsTable = '';
        $partsTable .= '<table style="width:100%;">';
        foreach($filterParts['partsGrid'] as $itemKey => $item) {
            $partsTable .= '<br><br>';
            $partsTable .= '<tr><span style="font-weight:bold; font-size: 1.1em;">' . $itemKey . '</span><br><table border = "1" style="width:100%;">';

            $partsTable .= '<tr>';
            $partsTable .= '<th>Date Installed</th>';
            $partsTable .= '<th>Mileage</th>';
            $partsTable .= '<th>Due Date</th>';
            $partsTable .= '<th>Remarks</th>';
            $partsTable .= '<th>Damaged</th>';
            $partsTable .= '</tr>';
                     
            $components = json_decode($item, true);
            if(!empty($components)) {
                foreach($components as $cmpKey => $component ) {
                    $partsTable .= '<tr>';
                    $partsTable .= '<td>' . $component['dateInstalled'] . '</td>';
                    $partsTable .= '<td>' . $component['mileage'] . '</td>';
                    $partsTable .= '<td>' . $component['dueDate'] . '</td>';   
                    $partsTable .= '<td>' . $component['remarks'] . '</td>';
                    $partsTable .= '<td>' . $this->is_damaged($component['damage']) . '</td>';
                    $partsTable .= '</tr>';
                }
            } else {
                $partsTable .= '<tr>';
                $partsTable .= '<td colspan="5" style="text-align: center;">No Records</td>';
                $partsTable .= '</tr>';
            }
            $partsTable .= '</table></tr>';
        }
        $partsTable .= '</table>';

        /** TIRES TABLE **/
        $tiresTable = '';
        $tiresTable .= '<table border="1" style="width:100%;">';
        $tiresTable .= '<tr>';
        $tiresTable .= '<th>Original</th>';
        $tiresTable .= '<th>Recap</th>';
        $tiresTable .= '<th>Number</th>';
        $tiresTable .= '<th>Serial Number</th>';
        $tiresTable .= '<th>Date Installed</th>';
        $tiresTable .= '<th>Mileage</th>';
        $tiresTable .= '<th>Thickness</th>';
        $tiresTable .= '<th>Remarks</th>';
        $tiresTable .= '<th>Damaged</th>';
        $tiresTable .= '</tr>';
        
        if(!empty($tires)) {
            foreach($tires as $key => $tire){
                $tire = json_decode($tire, true);
                $tiresTable .= '<tr>';
                $tiresTable .= '<td>' . $tire['original'] . '</td>';
                $tiresTable .= '<td>' . $tire['recap'] . '</td>';
                $tiresTable .= '<td>' . $tire['number'] . '</td>';   
                $tiresTable .= '<td>' . $tire['serialNumber'] . '</td>';
                $tiresTable .= '<td>' . $tire['dateInstalled'] . '</td>';
                $tiresTable .= '<td>' . $tire['mileage'] . '</td>';
                $tiresTable .= '<td>' . $tire['thickness'] . '</td>';   
                $tiresTable .= '<td>' . $tire['remarks'] . '</td>';
                $tiresTable .= '<td>' . $this->is_damaged($tire['damage']) . '</td>';
                $tiresTable .= '</tr>';
            }
        } else {
            $tiresTable .= '<tr>';
            $tiresTable .= '<td colspan="9" style="text-align: center;">No Records</td>';
            $tiresTable .= '</tr>';
        }
        $tiresTable .= '</table>';

        /** OTHERS TABLE **/
        $othersTable = '';
        $othersTable .= '<table border="1" style="width:100%;">';
        $othersTable .= '<tr>';
        $othersTable .= '<th>Type of Maintenance</th>';
        $othersTable .= '<th>Description</th>';
        $othersTable .= '<th>Date Change/Date Installed</th>';
        $othersTable .= '<th>Mileage</th>';
        $othersTable .= '<th>Remarks</th>';
        $othersTable .= '<th>Damaged</th>';
        $othersTable .= '</tr>';
        if(!empty($others)) {
            foreach($others as $key => $other){
                $other = json_decode($other, true);
                $othersTable .= '<tr>';
                $othersTable .= '<td>' . $other['maintenanceType'] . '</td>';
                $othersTable .= '<td>' . $other['description'] . '</td>';
                $othersTable .= '<td>' . $other['dateChangeInstalled'] . '</td>';   
                $othersTable .= '<td>' . $other['mileage'] . '</td>';
                $othersTable .= '<td>' . $other['remarks'] . '</td>';
                $othersTable .= '<td>' . $this->is_damaged($other['damage']) . '</td>';
                $othersTable .= '</tr>';
            }
        } else {
            $othersTable .= '<tr>';
            $othersTable .= '<td colspan="6" style="text-align: center;">No Records</td>';
            $othersTable .= '</tr>';
        }
        $othersTable .= '</table>';
        
        $TOP = '<table cellspacing="10">
                    <tr>
                    	<td><span style="font-weight: bold;">Affiliate: </span>' . $form_data['affiliateName'] . '</td>
                        <td><span style="font-weight: bold">Date: </span>' . $form_data['pdf_tdate'] . '</td>
                    </tr>
                    <tr>
                        <td><span style="font-weight: bold;">Reference: </span>' . $form_data['pdf_idReference'] . '-' . $form_data['pdf_referenceNum'] . '</td>
                        <td><span style="font-weight: bold;">Cost Center: </span>' . $form_data['pdf_idCostCenter'] . '</td>
                    </tr>
                    <tr>
                        <td><span style="font-weight: bold;">Truck Type: </span>' . $form_data['pdf_idTruckType'] . '</td>
                        <td ><span style="font-weight: bold;">Remarks: </span>' . $form_data['pdf_remarks'] . '</td>
                    </tr>
                    <tr>
                        <td><span style="font-weight: bold;">Plate Number: </span>' . $form_data['pdf_plateNumber'] . '</td>
                    </tr>
                    <tr>
                        <td><span style="font-weight: bold;">Odometer: </span>' . $form_data['pdf_odometer'] . '</td>
                    </tr>
                </table>
                <br>

                <br><br>
				<span style="font-weight: bold; font-size: 1.2em;">Filters</span><br><hr/>'
                . $filtersTable . '<br>

                <br><br>
				<span style="font-weight: bold; font-size: 1.2em;">Parts</span><br><hr/>'
                . $partsTable . '<br>

                <br><br>
				<span style="font-weight: bold; font-size: 1.2em;">Tires</span><br><hr/>'
                . $tiresTable . '<br>

                <br><br>
				<span style="font-weight: bold; font-size: 1.2em;">Others</span><br><hr/>'
                . $othersTable;
    
        generate_table($Main,array(),array(),$TOP); 
    }
}
