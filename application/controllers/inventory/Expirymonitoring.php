<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Jayson Dagulo
 * Module       : Expiry Monitoring
 * Date         : Jan. 31, 2020
 * Finished     : 
 * Description  : This module allows authorized user to generate and monitor the expiration date of each item based on the specified date.
 * DB Tables    : 
 * */ 
class Expirymonitoring extends CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->library('encryption');
        setHeader( 'inventory/Expirymonitoring_model' );
    }

    public function getItemName(){
        $params = getData();
        $view   = $this->model->getItemName( $params );
        $view = decryptItem( $view );

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    public function getExpiryMonitoring(){
        $params = getData();
        setLogs(
            array(
                'actionLogDescription'  => 'Generates Expiry Monitoring.'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            )
        );
        $view   = $this->model->getExpiryMonitoring( $params );

         /**Custom Decryption for Expiry Monitoring**/
         $_viewHolder = $view['view'];
         foreach( $_viewHolder as $idx => $record ){
             
             /**Decrypting affiliate**/
             if( isset( $record['affiliateSK'] ) && !empty( $record['affiliateSK'] ) ){
                 $this->encryption->initialize( array('key' => generateSKED( $record['affiliateSK'] ) ) );
                 $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $record['affiliateName'] );
             }
 
             /**Decrypting item name**/
             if( isset( $record['itemSK'] ) && !empty( $record['itemSK'] ) ){
                 $this->encryption->initialize( array( 'key' => generateSKED( $record['itemSK'] )));
                 $_viewHolder[$idx]['itemName'] = $this->encryption->decrypt( $record['itemName'] );
             }
         }
 
         $view['view'] = $_viewHolder;

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

    public function printPDF(){
        $data           = getData();
        $data['pdf']    = true;
        $list           = $this->model->getExpiryMonitoring( $data );

        /**Custom Decryption for Expiry Monitoring**/
        $_viewHolder = $list;
        foreach( $_viewHolder as $idx => $record ){
            
            /**Decrypting affiliate**/
            if( isset( $record['affiliateSK'] ) && !empty( $record['affiliateSK'] ) ){
                $this->encryption->initialize( array('key' => generateSKED( $record['affiliateSK'] ) ) );
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $record['affiliateName'] );
            }

            /**Decrypting item name**/
            if( isset( $record['itemSK'] ) && !empty( $record['itemSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $record['itemSK'] )));
                $_viewHolder[$idx]['itemName'] = $this->encryption->decrypt( $record['itemName'] );
            }
        }

        $list = $_viewHolder;


        $params1        = array(
            array(   
                'header'        => 'Affiliate'
                ,'dataIndex'    => 'affiliateName'
                ,'width'        => '10%'
            )
            ,array(  
                'header'        => 'Date Received'
                ,'dataIndex'    => 'dateReceived'
                ,'width'        => '10%'
                ,'type'         => 'datecolumn'
                ,'format'       => 'm/d/Y'
            )
            ,array(  
                'header'        => 'Reference'
                ,'dataIndex'    => 'reference'
                ,'width'        => '10%'
            )
            ,array(  
                'header'        => 'Code'
                ,'dataIndex'    => 'barcode'
                ,'width'        => '10%'
            )
            ,array(  
                'header'        => 'Item Name'
                ,'dataIndex'    => 'itemName'
                ,'width'        => '10%'
            )
            ,array(  
                'header'        => 'Classification'
                ,'dataIndex'    => 'className'
                ,'width'        => '10%'
            )
            ,array(  
                'header'        => 'Unit'
                ,'dataIndex'    => 'unitName'
                ,'width'        => '10%'
            )
            ,array(  
                'header'        => 'Qty'
                ,'dataIndex'    => 'qtyLeft'
                ,'width'        => '10%'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
            )
            ,array(  
                'header'        => 'Expiry Date'
                ,'dataIndex'    => 'expiryDate'
                ,'width'        => '10%'
                ,'type'         => 'datecolumn'
                ,'format'       => 'm/d/Y'
            )
            ,array(  
                'header'        => 'Remaining Days'
                ,'dataIndex'    => 'remainingDays'
                ,'width'        => '10%'
                ,'type'         => 'numbercolumn'
                ,'format'       => '0,000.00'
            )
        );

        setLogs(
            array(
                'actionLogDescription'  => 'Exported the generated Expiry Monitoring(PDF).'
			,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            )
        );
        generateTcpdf(
			array(
				'file_name' => $data['title']
				,'folder_name' => 'inventory'
				,'records' => $list
				,'header' => $params1
				,'orientation' => 'P'
				,'header_fields' => array(
                    array(
                        array(
                            'label'     => ''
                            ,'value'    => ''
                        )
                    )
                )
			)
		);
    }

    public function printExcel(){
        $data           = getData();
        $data['pdf']    = true;
        $list           = $this->model->getExpiryMonitoring( $data );


        /**Custom Decryption for Expiry Monitoring**/
        $_viewHolder = $list;
        foreach( $_viewHolder as $idx => $record ){
            
            /**Decrypting affiliate**/
            if( isset( $record['affiliateSK'] ) && !empty( $record['affiliateSK'] ) ){
                $this->encryption->initialize( array('key' => generateSKED( $record['affiliateSK'] ) ) );
                $_viewHolder[$idx]['affiliateName'] = $this->encryption->decrypt( $record['affiliateName'] );
            }

            /**Decrypting item name**/
            if( isset( $record['itemSK'] ) && !empty( $record['itemSK'] ) ){
                $this->encryption->initialize( array( 'key' => generateSKED( $record['itemSK'] )));
                $_viewHolder[$idx]['itemName'] = $this->encryption->decrypt( $record['itemName'] );
            }
        }

        $list = $_viewHolder;


        $csvarray       = array();
		
		$csvarray[] = array( 'title' => $data['title']);
        $csvarray[] = array( 'space' => '' );
        $csvarray[] = array(
            'Affiliate'
            ,'Date Received'
            ,'Reference'
            ,'Code'
            ,'Item Name'
            ,'Classification'
            ,'Unit'
            ,'Qty'
            ,'Expiry Date'
            ,'Remaining Days'
        );
        foreach( $list as $rs ){
            $csvarray[] = array(
                $rs['affiliateName']
                ,date( 'm/d/Y', strtotime( $rs['dateReceived'] ) )
                ,$rs['reference']
                ,$rs['barcode']
                ,$rs['itemName']
                ,$rs['className']
                ,$rs['unitName']
                ,number_format( $rs['qtyLeft'], 0 )
                ,( $rs['expiryDate']? date( 'm/d/Y', strtotime( $rs['expiryDate'] ) ) : '' )
                ,( $rs['remainingDays'] < 0 ? '(' . number_format( ( $rs['remainingDays'] * -1 ), 0 ) . ')' : number_format( $rs['remainingDays'], 0 ) )
            );
        }

        setLogs(
            array(
                'actionLogDescription'  => 'Exported the generated Expiry Monitoring(Excel).'
            )
        );
        writeCsvFile(
			array(
				'csvarray' 	 => $csvarray
				,'title' 	 => $data['title']
				,'directory' => 'inventory'
			)
		);
    }

    public function viewPDF( $title ){
		viewPDF(
			array(
				'file_name' => $title
				,'folder_name' => 'inventory'
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