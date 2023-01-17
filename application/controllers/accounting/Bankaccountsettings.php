<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Bankaccountsettings extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->library('encryption');
        setHeader('accounting/Bankaccountsettings_model');
    }

    public function getBanks()
    {
        $view = $this->model->getBanks();
        $view = decryptBank( $view );

        array_unshift( $view, array(
            'id' => 0
            ,'name' => '<span style="color: gray">+ Add New Bank</span>'
        ));

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    public function getCoa()
    {
        $params = getData();
        $view   = $this->model->getCoa( $params );

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    public function getBankAccounts()
    {
        $params = getData();
        $view   = $this->model->getBankAccounts( $params ); 

        /**Custom decryption of fields for this function**/

        foreach ($view as $key => $item) {
            $this->encryption->initialize( array( 'key' => generateSKED( $item['affiliateSK']) ) );
            $view[$key]['affiliateName'] = $this->encryption->decrypt( $item['affiliateName'] );

            $this->encryption->initialize( array( 'key' => generateSKED( $item['bankAccountSK']) ) );
            $view[$key]['bankAccount'] = $this->encryption->decrypt( $item['bankAccount'] );
            $view[$key]['bankAccountNumber'] = $this->encryption->decrypt($item['bankAccountNumber']);
    
            $this->encryption->initialize( array( 'key' => generateSKED( $item['bankSK'] ) ) );
            $view[$key]['bankName'] = $this->encryption->decrypt( $item['bankName'] );
        }

        die( 
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    public function retrieveData()
    {
        $match              = 0;
        $params             = getData();
        $view               = $this->model->retrieveData( $params );
        $view               = decryptBankAccount( $view );
        $checkIfUsed        = $this->model->checkIfUsed( $params['idBankAccount'] );
        $checkIfNotFound    = $this->model->checkIfNotFound( $params['idBankAccount'] );

        if ( $checkIfNotFound ) { $match = 1; }
        if ( $checkIfUsed ) { $match = 2; }

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'match'    => $match
                    ,'view'     => $view
                )
            )
        );
    }

    public function saveBankAccount()
    {
        $params = getData();

        /**Encryption of fields**/
        if( array_key_exists( 'bankAccount', $params ) && !empty( $params['bankAccount'] ) ){
            $params['sk'] = initializeSalt( $params['bankAccount'] );
            $this->encryption->initialize( array( 'key' => generateSKED( $params['sk'] ) ) );
            $params['bankAccount'] = $this->encryption->encrypt( $params['bankAccount'] );
            if( array_key_exists('bankAccountNumber', $params) && !empty( $params['bankAccountNumber'] ) ) $params['bankAccountNumber'] = $this->encryption->encrypt( $params['bankAccountNumber'] );
        }
        
        // SAVING TRANS START
        $this->db->trans_begin();
            // EDIT
            if ( $params['idBankAccount'] > 0 ) {
                $this->model->updateBankAccount( $params , $params['idBankAccount'] );
                $this->model->saveBankAccountHistory( $params , $params['idBankAccount'] );
                $params['action']   = 'edited the bank account of';
            } else {
                // SAVE
                if ( $this->model->checkDuplicaiton( $params ) ) {
                    die(
                        json_encode(
                            array(
                                'success' => true
                                ,'match'  => 1
                            )
                        )
                    );
                }

                $idBankAccount  = $this->model->saveBankAccount( $params );
                $this->model->saveBankAccountHistory( $params , $idBankAccount );
                $params['action']   = 'added a new bank account for';
            }


        if( $this->db->trans_status() === FALSE ){
            $this->db->trans_rollback();
            die(
                json_encode(
                    array(
                        'success' => false
                    )
                )
            );
        }

        else {
            $this->db->trans_commit();
            $this->setLogs( $params );
            die(
                json_encode(
                    array(
                        'success' => true
                        ,'match'  => 0
                    )
                )
            );
        }
    }

    public function archiveBankAccount()
    {
        $match              = 0;
        $params             = getData();
        $checkIfUsed        = $this->model->checkIfUsed( $params['idBankAccount'] );
        $checkIfNotFound    = $this->model->checkIfNotFound( $params['idBankAccount'] );
        
        if ( $checkIfNotFound ) { $match = 1; }
        if ( $checkIfUsed ) { $match = 2; }
        else {
            $this->model->archiveBankAccount( $params['idBankAccount'] );
            $params['action']   = 'deleted the bank account of';
            $this->setLogs( $params );
        }

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'match'    => $match
                    ,'params'   => $params
                )
            )
        );
    }

    public function searchGrid()
    {
        $params = getData();
        $view = $this->model->searchGrid( $params );
        $view = decryptBankAccount( $view );

        die(
            json_encode(
                array(
                    'success' => true 
                    ,'view' => $view
                )
            )
        );
    }

    private function setLogs( $params ){
        $header = 'Bank Account Settings : '.$this->USERFULLNAME;
        $action = '';
        
        if( isset( $params['deleting'] ) ){
            $action = 'deleted the bank account of';
        }
        else{
            if( isset( $params['action'] ) )
                $action = $params['action'];
            else
                $action = ( $params['onEdit'] == 1  ? 'modified' : 'added new' );
        }
        
        setLogs(
            array(
                'actionLogDescription'  => $header . ' ' . $action . ' ' . $params['coaName']
                ,'idAffiliate'			=> $this->session->userdata('AFFILIATEID')
            )
        );
    }
}
