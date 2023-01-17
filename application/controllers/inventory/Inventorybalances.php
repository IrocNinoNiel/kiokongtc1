<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Inventorybalances extends CI_Controller
{
   public function __Construct()
   {
      parent::__Construct();
		$this->load->library( 'encryption' );
      setHeader( 'inventory/Inventorybalances_model' );
   }

   public function getItemClass()
   {
      $params = getData();
      $view   = $this->model->getItemClassifications();

      array_unshift( $view, array(
         'id' => 0
         ,'name' => 'All'
      ));

      die(
         json_encode(
            array(
               'success' => true 
               ,'view' => $view
            )
         )
      );
   }

   public function getItems()
   {
      $params = getData();
      $view   = $this->model->getItems( $params );
      $view = decryptItem( $view );

      array_unshift( $view, array(
         'id' => 0
         ,'name' => 'All'
      ));
      
      die(
         json_encode(
            array(
               'success' => true 
               ,'view' => $view
            )
         )
      );
   }

   public function getInventoryBalances()
   {
      $params = getData();
      $view = $this->model->getInventoryBalances( $params );
      
      $_viewHolder = $view;
      foreach( $_viewHolder as $idx => $po ){
         if( isset( $po['affSK'] ) && !empty( $po['affSK'] ) ){
            $this->encryption->initialize( array( 'key' => generateSKED( $po['affSK'] )));
            $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
         }
         if( isset( $po['itemSK'] ) && !empty( $po['itemSK'] ) ){
            $this->encryption->initialize( array( 'key' => generateSKED( $po['itemSK'] )));
            $_viewHolder[$idx]['itemName'] = $this->encryption->decrypt( $po['itemName'] );
         }
      }
      $view = $_viewHolder;

      setLogs(
         array(
            'actionLogDescription' => 'Generates Inventory Balances.'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
         ,'idEu'                => $this->USERID
            ,'moduleID'            => 61
            ,'time'                => date('H:i:s A')
         )
      );

      die(
         json_encode(
            array(
               'success' => true
               ,'view' => $view
               ,'params' => $params
            )
         )
      );
   }

   function generatePDF() {
      $data = getData();

      $formDetails = json_decode( $data['form'], true );
      $poItems = json_decode( $data['poItems'], true );
      
      $_viewHolder = $data;
         foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['affSK'] ) && !empty( $po['affSK'] ) ){
               $this->encryption->initialize( array( 'key' => generateSKED( $po['affSK'] )));
               $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
            }
            if( isset( $po['itemSK'] ) && !empty( $po['itemSK'] ) ){
               $this->encryption->initialize( array( 'key' => generateSKED( $po['itemSK'] )));
               $_viewHolder[$idx]['itemName'] = $this->encryption->decrypt( $po['itemName'] );
            }
         }
      $data = $_viewHolder;

      $header_fields = array(
         array(
            array(
               'label' => 'Affiliate'
               ,'value' => $formDetails['pdf_idAffiliate']
            )
            ,array(
               'label' => 'Classification'
               ,'value' => $formDetails['pdf_idItemClass']
            )
            ,array(
               'label' => 'Item Name'
               ,'value' => $formDetails['pdf_idItem']
            )
            ,array(
               'label'  => 'Records As Of'
               ,'value' => $formDetails['pdf_datefrom'] . ' ' .  date( 'h:i A', strtotime($formDetails['pdf_timefrom']) )
            )
         )
      );


      $table = array(
         array(   
            'header'        => 'Affiliate'
            ,'dataIndex'    => 'affiliateName'
            ,'width'        => '20%'
         )
         ,array(  
            'header'        => 'Item Name'
            ,'dataIndex'    => 'itemName'
            ,'width'        => '15%'
         )
         ,array(  
            'header'        => 'Classification'
            ,'dataIndex'    => 'className'
            ,'width'        => '15%'
         )
         ,array(  
            'header'        => 'Unit'
            ,'dataIndex'    => 'unitCode'
            ,'width'        => '10%'
         )
         ,array(  
            'header'        => 'Cost'
            ,'dataIndex'    => 'cost'
            ,'width'        => '15%'
            ,'type'         => 'numbercolumn'
            ,'format'       => '0,000.00'
         )
         ,array(  
            'header'        => 'Reorder Point'
            ,'dataIndex'    => 'reorderLevel'
            ,'width'        => '10%'
            ,'type'         => 'numbercolumn'
            ,'format'       => '0,000'
         )
         ,array(  
            'header'        => 'Balance'
            ,'dataIndex'    => 'balance'
            ,'width'        => '15%'
            ,'type'         => 'numbercolumn'
            ,'format'       => '0,000.00'
         )
      );

      generateTcpdf(
       array(
          'file_name' => 'Inventory Balances' //$data['title']
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
      $data = $this->model->getInventoryBalances( $params );

      $_viewHolder = $data;
         foreach( $_viewHolder as $idx => $po ){
            if( isset( $po['affSK'] ) && !empty( $po['affSK'] ) ){
               $this->encryption->initialize( array( 'key' => generateSKED( $po['affSK'] )));
               $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $po['affiliateName'] );
            }
            if( isset( $po['itemSK'] ) && !empty( $po['itemSK'] ) ){
               $this->encryption->initialize( array( 'key' => generateSKED( $po['itemSK'] )));
               $_viewHolder[$idx]['itemName'] = $this->encryption->decrypt( $po['itemName'] );
            }
         }
      $data = $_viewHolder;

      $csvarray = array();

      $csvarray[] = array( 'title' => $params['title'] );
      $csvarray[] = array( 'space' => '' );

      $csvarray[] = array( 'Affiliate', $params['pdf_idAffiliate'] );
      $csvarray[] = array( 'Classification', $params['pdf_idItemClass'] );
      $csvarray[] = array( 'Item', $params['pdf_idItem'] );
      $csvarray[] = array( 'Records As Of', $params['pdf_datefrom'] . ' ' . date( 'h:i A', strtotime( $params['pdf_timefrom'] ) ) );
      $csvarray[] = array( 'space' => '' );

      $csvarray[] = array(
         'Affiliate'
         ,'Item Name'
         ,'Classification'
         ,'Unit'
         ,'Cost'
         ,'Reorder Point'
         ,'Balance'
      );

      foreach( $data as $d ){
         $csvarray[] = array(
            $d['affiliateName']
            ,$d['itemName']
            ,$d['className']
            ,$d['unitCode']
            ,number_format( $d['cost'], 2 )
            ,number_format( $d['reorderLevel'] )
            ,number_format( $d['balance'] )
         );
      }

      setLogs(
         array(
            'actionLogDescription' => 'Exported the generated Inventory Balances Report (EXCEL).'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
         ,'idEu'                => $this->USERID
            ,'moduleID'            => 61
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
