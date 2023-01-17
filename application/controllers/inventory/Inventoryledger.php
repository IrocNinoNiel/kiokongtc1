<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Inventoryledger extends CI_Controller
{
   public function __Construct()
   {
      parent::__Construct();
        $this->load->library('encryption');
        setHeader( 'inventory/Inventoryledger_model' );
   }

   public function getItems()
   {
      $params = getData();
      $view = $this->model->getItems( $params );

      $data['success'] = true;
      $data['view'] = decryptItem( $view );

      die( json_encode($data) );
   }


   public function getInventoryLedger()
   {
      $data['view'] = array();
      $data['success'] = false;
      $params = getData();

      if ( $params['idItem'] && $params['idAffiliate'] ) { 
         $data['success'] = true;
         $data['view'] = $this->model->getInventoryLedger( $params );

         $_viewHolder = $data['view'];
         foreach( $_viewHolder as $idx => $po ){
               if( isset( $po['sk'] ) && !empty( $po['sk'] ) ){
                  $this->encryption->initialize( array( 'key' => generateSKED( $po['sk'] )));
                  $_viewHolder[$idx]['name'] = $this->encryption->decrypt( $po['name'] );
               }
         } 
         $data['view'] = $_viewHolder;
      }

      setLogs(
         array(
            'idEu' => $this->USERID
            ,'time' => date('H:i:s A')
            ,'moduleID' => 59
			   ,'idAffiliate' => $this->session->userdata('AFFILIATEID')
            ,'actionLogDescription' => 'Generates Inventory Ledger Report.'
         )
      );

      die( json_encode($data) );
   }

   function generatePDF() {
      $data = getData();

      $formDetails = json_decode( $data['form'], true );
      $poItems = json_decode( $data['poItems'], true );

      $header_fields = array(
         array(
            array(
                  'label'     => 'Affiliate'
                  ,'value'    => $formDetails['pdf_idAffiliate']
            )
            ,array(
                  'label'     => 'Item Name'
                  ,'value'    => $formDetails['pdf_idItem']
            )
            ,array(
                  'label'     => 'Classification'
                  ,'value'    => $formDetails['pdf_itemClass']
            )
            ,array(
                  'label'     => 'Unit'
                  ,'value'    => $formDetails['pdf_unit']
            )
            ,array(
                  'label'     => 'Price'
                  ,'value'    => $formDetails['pdf_price']
            )
            ,array(
                  'label'     => 'Reorder Point'
                  ,'value'    => $formDetails['pdf_reorderPoint']  
            )
            ,array(
                  'label'     => 'Date'
                  ,'value'    => $formDetails['pdf_sdate'] . ' to ' . $formDetails['pdf_edate']
            )
         )
      );


      $table = array(
         array(      
            'header'        => 'Date'
            ,'dataIndex'    => 'date'
            ,'type'         => 'datecolumn'
            ,'format'       => 'm/d/Y'
            ,'width'        => '15%'
         )
         ,array(  
            'header'        => 'Reference'
            ,'dataIndex'    => 'code'
            ,'width'        => '10%'
         )
         ,array(  
            'header'        => 'Supplier/Customer'
            ,'dataIndex'    => 'name'
            ,'width'        => '25%'
         )
         ,array(  
            'header'        => 'Price.'
            ,'dataIndex'    => 'price'
            ,'width'        => '10%'
            ,'type'         => 'numbercolumn'
            ,'format'       => '0,000.00'
         )
         ,array(  
            'header'        => 'Cost'
            ,'dataIndex'    => 'cost'
            ,'width'        => '10%'
            ,'type'         => 'numbercolumn'
            ,'format'       => '0,000.00'
         )
         ,array(  
            'header'        => 'Received'
            ,'dataIndex'    => 'received'
            ,'width'        => '10%'
            ,'type'         => 'numbercolumn'
            ,'format'       => '0,000'
         )
         ,array(  
            'header'        => 'Released'
            ,'dataIndex'    => 'released'
            ,'width'        => '10%'
            ,'type'         => 'numbercolumn'
            ,'format'       => '0,000'
         )
         ,array(  
            'header'        => 'Balance'
            ,'dataIndex'    => 'balance'
            ,'width'        => '10%'
            ,'type'         => 'numbercolumn'
            ,'format'       => '0,000'
         )
      );

      generateTcpdf(
         array(
            'file_name' => 'Inventory Ledger' //$data['title']
            ,'folder_name' => 'inventory'
            ,'header_fields' => $header_fields
            ,'records' =>  $poItems
            ,'header' => $table
            ,'orientation' => 'P'
            ,'params' => $data
            ,'idAffiliate' => $Affiliate
               // ,'hasSignatories' => 1
         ) 
      );
   }
   
   function printExcel(){
      $params = getData();
      $data = $this->model->getInventoryLedger( $params );
      
      $csvarray = array();

      $csvarray[] = array( 'title' => $params['title'] );
      $csvarray[] = array( 'space' => '' );

      $csvarray[] = array( 'Affiliate', $params['pdf_idAffiliate'] );
      $csvarray[] = array( 'Item Name', $params['pdf_idItem'] );
      $csvarray[] = array( 'Classification', $params['pdf_itemClass'] );
      $csvarray[] = array( 'Unit', $params['pdf_unit'] );
      $csvarray[] = array( 'Price', $params['pdf_price'] );
      $csvarray[] = array( 'Reorder Point', $params['pdf_reorderPoint'] );
      $csvarray[] = array( 'Date', $params['pdf_sdate'] . ' to ' . $params['pdf_edate'] );
      $csvarray[] = array( 'space' => '' );

      $csvarray[] = array(
         'Date'
         ,'Reference'
         ,'Supplier/Customer'
         ,'Price'
         ,'Cost'
         ,'Received'
         ,'Released'
         ,'Balance'
      );

      foreach( $data as $d ){
         $csvarray[] = array(
            $d['date']
            ,$d['code']
            ,$d['name']
            ,number_format( $d['price'], 2 )
            ,number_format( $d['cost'], 2 )
            ,number_format( $d['received'] )
            ,number_format( $d['released'] )
            ,number_format( $d['balance'] )
         );
      }

      setLogs(
         array(
            'actionLogDescription' => 'Exported the generated Inventory Ledger Report (Excel).'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
         ,'idEu'                => $this->USERID
            ,'moduleID'            => 59
            ,'time'                => date('H:i:s A')
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

   function download( $title, $folder ){
      force_download(
         array(
            'title'      => $title
            ,'directory' => $folder
         )
      );
   }
}
