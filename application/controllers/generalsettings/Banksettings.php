<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Hazel Alegbeleye
 * Module: Bank Settings
 * Date: Dec 3, 2019
 * Finished: December 3, 2019
 * Description: This module allows the authorized user to set (add, edit and delete) a bank
 * DB Tables: bank, bankaccount
 * */ 
class Banksettings extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->library('encryption');
        setHeader( 'generalsettings/Banksettings_model' );
    }

    function getBanks() {
        $params = getData();
        $view = $this->model->getBanks( $params );

         /** Decrypt fields **/
         foreach( $view['view'] as $key => $bank ){
             if( isset($bank['sk']) ){
                $this->encryption->initialize( array('key' => generateSKED( $bank['sk'] )) );
                $view['view'][$key]['bankName'] = $this->encryption->decrypt( $bank['bankName'] ); //172 Char
             }
         }

        die(
            json_encode(
                array(
                    'succes' => true
                    ,'view' => $view['view']
                    ,'total' => $view['count']
                )
            )
        );
    }

    function retrieveData() {
        $params = getData();
        $view = $this->model->retrieveData( $params );

        /** Decrypt fields **/
        $this->encryption->initialize( array('key' => generateSKED( $view[0]['sk'] )) );
        $view[0]['bankName'] = $this->encryption->decrypt( $view[0]['bankName'] ); //172 Char

        die(
            json_encode(
                array(
                    'succes' => true
                    ,'view' => $view
                )
            )
        );
    }

    function saveBank() {
        $params = getData();

        /** Encryption of  fields **/
        if( isset( $params['bankName'] ) ) {
            $params['sk'] = initializeSalt( $params['bankName'] );
            $this->encryption->initialize( array('key' => generateSKED( $params['sk'] )) );
            $params['bankName'] = $this->encryption->encrypt( $params['bankName'] ); //172 Char
        } else {
            die('BANK NAME IS REQUIRED.');
        }

        $view = $this->model->saveBank( $params );

        $msg = ( $params['onEdit'] ) ? ' edited the bank ': ' added a new bank ';

        /** Decrypt fields **/
        $this->encryption->initialize( array('key' => generateSKED( $params['sk'] )) );
        $params['bankName'] = $this->encryption->decrypt( $params['bankName'] ); //172 Char

        setLogs(
            array(
                'actionLogDescription' => $this->USERNAME . $msg . $params['bankName']
                ,'idEu'                => $this->USERID
                ,'moduleID'            => 14
                ,'time'                => date("H:i:s A")
            )
        );

        die(
            json_encode(
                array(
                    'succes' => true
                    ,'view' => $view
                )
            )
        );
    }

    function deleteBank() {
        $params = getData();
        $match = $this->model->deleteBank( $params );

        setLogs(
            array(
                'actionLogDescription' => $this->USERNAME . ' deleted a bank.'
                ,'idEu'                => $this->USERID
                ,'moduleID'            => 14
                ,'time'                => date("H:i:s A")
            )
        );

        die(
            json_encode(
                array(
                    'succes' => true
                    ,'match' => $match
                )
            )
        );
    }
}