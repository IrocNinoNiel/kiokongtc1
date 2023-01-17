<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Hazel Alegbeleye
 * Module: Reference Settings
 * Date: Nov 25, 2019
 * Finished: December 4, 2019
 * Description: This module allows the authorized user to set (add, edit and delete ) the references that will be used in every transaction in the system.
 * DB Tables: affiliate, module, costcenter, reference, referenceaffiliate, referenceseries, invoices
 * */ 
class Referencesettings extends CI_Controller {

    public function __construct(){
        parent::__construct();
        setHeader( 'generalsettings/Referencesettings_model' );
    }

    function getAffiliates() {
        $params = getData();
        $view = $this->model->getAffiliates1( $params );
        $view['view']   = decryptAffiliate( $view['view'] );

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

    function getModules() {
        $params = getData();
        $view = $this->model->getModules( $params );

        die(
            json_encode(
                array(
                    'succes' => true
                    ,'view' => $view
                )
            )
        );
    }

    function getCostCenters() {
        $params = getData();
        $view = $this->model->getCostCenters( $params );
        $view   = decryptCostCenter( $view );

        die(
            json_encode(
                array(
                    'succes' => true
                    ,'view' => $view
                )
            )
        );
    }

    function getReference() {
        $params = getData();
        $view = $this->model->getReference( $params );

        // LQ();

        die(
            json_encode(
                array(
                    'succes' => true
                    ,'view' => $view
                )
            )
        );
    }

    function retrieveData() {
        $params = getData();
        $response = $this->model->retrieveData( $params );

        // LQ();

        die(
            json_encode(
                array(
                    'succes' => true
                    ,'view'     => array($response['view'])
                    ,'match'    => ( $response['rec'] > 0 ) ? 2 : 0 
                )
            )
        );
    }

    function retrieveReferenceSeries() {
        $params = getData();
        $response = $this->model->retrieveReferenceSeries( $params );

        die(
            json_encode(
                array(
                    'succes'    => true
                    ,'view'     => array($response['view'])
                    ,'match'    => ( $response['rec'] > 0 ) ? 2 : 0 
                )
            )
        );
    }

    function getHistory( $mode = '' ) {
        $params = getData();
        $params['mode'] = $mode;

        if( isset( $params['filterKey'] ) ) {
            $params['filterKey'] = ( $params['filterKey'] == 1 ? 'code' : 'name' );
        }

        $_viewHolder = $this->model->viewHistory( $params );
        $_view = $_viewHolder['view'];

        foreach( $_view as $idx => $series ){
            /**decrypting affiliate and costcenter**/
            if( !empty( $series['costCenterSK'] ) ) {
                $_costCenter = array( 0 => array('costCenterName' => $series['costCenterName'], 'sk' => $series['costCenterSK'] ) );
                $_view[$idx]['costCenterName'] = decryptCostCenter( $_costCenter )[0]['costCenterName'];
            }

            if( !empty( $series['affiliateSK'] ) ){
                
                $_affiliate = array( 0 => array('affiliateName' => $series['affiliateName'], 'sk' => $series['affiliateSK'] ) );
                $_view[$idx]['affiliateName'] = decryptAffiliate( $_affiliate )[0]['affiliateName'];
            }
        }

        $_viewHolder['view'] = $_view;

        // LQ();
        die(
            json_encode(
                array(
                    'success' => true
                    ,'view' => $_viewHolder['view']
                    ,'total' => $_viewHolder['count']
                )
            )
        );     
    }

    function saveInitial() {
        $params = getData();
        $data['affiliates'] = $params['affiliates'];
        unset( $params['affiliates'] );

        $view = $this->model->saveInitial( $params );

        /* MATCH LEGEND:
        *  0 - SUCCESS [onEdit]
        *  1 - RECORD_USED
        *  2 - CODE ALREADY EXISTS [onEdit]
        */

        $match = ( $view > 0 ) ? 0 : 2;

        if( $match == 0 ){
            $refAffiliates = [];
            $params['idReference'] = $view;

            foreach( json_decode($data['affiliates'], true) as $value ){
                array_push( $refAffiliates, array(
                    'idReference' => $params['idReference']
                    ,'idAffiliate' => $value
                ));
            }

            
            $this->model->saveReferenceAffiliates( $refAffiliates, $params['idReference'] );
            $refCode = $this->model->getReferenceCode( $params )[0]['code'];

            if( !empty( $refCode ) ){
                $msg = ( !$params['onEdit'] ) ? ' added a new initial reference ' : ' edited the details of initial reference ' ;
        
                setLogs(
                    array(
                        'actionLogDescription' => $this->USERNAME . $msg . $refCode
                        ,'idEu'                => $this->USERID
                        ,'moduleID'            => 14
                        ,'time'                => date("H:i:s A")
                    )
                );
            }
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

    function saveSeries() {
        $params = getData();
        $view = $this->model->saveSeries( $params );
        $refCode = $this->model->getReferenceCode( $params )[0]['code'];

        $msg = ( !$params['onEdit'] ) ? ' added a new series reference ' : ' edited the details of series reference ' ;

        setLogs(
            array(
                'actionLogDescription' => $this->USERNAME . $msg . $refCode
                ,'idEu'                => $this->USERID
                ,'moduleID'            => 8
                ,'time'                => date("H:i:s A")
            )
        );
        
        /* MATCH LEGEND:
        *  0 - SUCCESS [onEdit]
        *  1 - RECORD_USED
        *  2 - SERIES FROM IS LESS THAN LATEST SERIES TO [onEdit]
        */

        if( isset($params['onEdit']) && $params['onEdit'] == 1 ) {
            $match = ( $view > 0 ) ? 0 : 1;
        } else {
            $match = ( $view > 0 ) ? 0 : 2;
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

    function saveReferenceAffiliates() {
        $params = getData();
        $view = $this->model->saveReferenceAffiliates( $params );

        die(
            json_encode(
                array(
                    'success' => true
                    ,'view' => $view
                )
            )
        );
    }

    function deleteReference() {
        $params = getData();
        $match = $this->model->deleteReference( $params );
        $refCode = $this->model->getReferenceCode( $params )[0]['code'];

        setLogs(
            array(
                'actionLogDescription' => $this->USERNAME . ' deleted the initial reference ' . $refCode
                ,'idEu'                => $this->USERID
                ,'moduleID'            => 8
                ,'time'                => date("H:i:s A")
            )
        );

        die(
            json_encode(
                array(
                    'success' => true
                    ,'match' => $match
                )
            )
        );
    }

    function deleteSeries() {
        $params = getData();
        $match = $this->model->deleteSeries( $params );
        $refCode = $this->model->getReferenceCode( $params )[0]['code'];

        setLogs(
            array(
                'actionLogDescription' => $this->USERNAME . ' deleted the series reference ' . $refCode
                ,'idEu'                => $this->USERID
                ,'moduleID'            => 8
                ,'time'                => date("H:i:s A")
            )
        );

        die(
            json_encode(
                array(
                    'success' => true
                    ,'match' => $match
                )
            )
        );
    }

    function getRefDetails(){
        $params = getData();
        $view = $this->model->getRefDetails( $params );

        die(
            json_encode(
                array(
                    'success' => true
                    ,'view' => $view
                )
            )
        );
    }

    public function generatePDF( $mode = '' ){
        $data = getData();
        $data['mode'] = $mode;
        $_viewHolder = $this->model->viewHistory( $data );
        $_view = $_viewHolder['view'];

        foreach( $_view as $idx => $series ){
            /**decrypting affiliate and costcenter**/
            if( !empty( $series['costCenterName'] ) ) {
                $_costCenter = array( 0 => array('costCenterName' => $series['costCenterName'], 'sk' => $series['costCenterSK'] ) );
                $_view[$idx]['costCenterName'] = decryptCostCenter( $_costCenter )[$idx]['costCenterName'];
            }

            if( !empty( $series['affiliateName'] ) ){
                $_affiliate = array( 0 => array('affiliateName' => $series['affiliateName'], 'sk' => $series['affiliateSK'] ) );
                $_view[$idx]['affiliateName'] = decryptAffiliate( $_affiliate )[$idx]['affiliateName'];
            }
        }

        $_viewHolder['view'] = $_view;

        if( $data['mode'] == 'initial' ) {
            $header = array(
                array(
                    'header'=>'Reference Code'
                    ,'dataIndex'=>'code'
                    ,'width'=>'20%'	
                ),
                array(
                    'header'=>'Reference Name'
                    ,'dataIndex'=>'name'
                    ,'width'=>'40%'
                ),
                array(
                    'header'=>'Module Name'
                    ,'dataIndex'=>'moduleName'
                    ,'width'=>'40%'
                ),
            );  
        } else {
            $header = array(
                array(
                    'header'=>'Date'
                    ,'dataIndex'=>'date'
                    ,'width'=>'10%'	
                ),
                array(
                    'header'=>'Affiliate'
                    ,'dataIndex'=>'affiliateName'
                    ,'width'=>'20%'
                ),
                array(
                    'header'=>'Cost Center'
                    ,'dataIndex'=>'costCenterName'
                    ,'width'=>'15%'
                ),
                array(
                    'header'=>'Module'
                    ,'dataIndex'=>'moduleName'
                    ,'width'=>'15%'
                ),
                array(
                    'header'=>'Reference Name'
                    ,'dataIndex'=>'name'
                    ,'width'=>'20%'
                ),
                array(
                    'header'=>'Reference Code'
                    ,'dataIndex'=>'code'
                    ,'width'=>'10%'
                ),
                array(
                    'header'=>'From'
                    ,'dataIndex'=>'seriesFrom'
                    ,'width'=>'5%'
                ),
                array(
                    'header'=>'To'
                    ,'dataIndex'=>'seriesTo'
                    ,'width'=>'5%'
                ),
            ); 
        }

        $array = array(
            'file_name'	=> $data['pageTitle']
            ,'folder_name' => 'generalsettings'
            ,'records' =>  $_viewHolder['view']
            ,'header' => $header
       );
       
       $data['ident'] = null;
       generateTcpdf($array);
    }
}