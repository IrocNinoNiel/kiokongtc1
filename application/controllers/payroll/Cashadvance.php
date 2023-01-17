<?php if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * Developer: Niño Niel B. Iroc
 * Module: Cash Advance
 * Date: Jan 10, 2023
 * Finished:
 * Description: 
 * DB Tables:
 * */

class Cashadvance extends CI_Controller{

    public function __construct(){
        parent::__construct();
        middleware();
        $this->load->library('encryption');
        setHeader('payroll/Cashadvance_model');
    }

    function getProjectNames() {

        if( validSession() ){
            $params = getData();
            $view   = $this->model->getProjectNames($params);
    
            die(
                json_encode(
                    array(
                         'success' => true
                        ,'view'    => $view
                    )
                )
            );
        } else {
            die(
                json_encode(
                    array(
                        'status'    => false,
                        'msg'       => 'You are not authorized to perform this action.'
                    )
                )
            );
        }
    }

    function getDrivers(){
        if( validSession() ){
            $params = getData();
            $view   = $this->model->getDrivers( $params );
            $view	= decryptUserData( $view );


            die(
                json_encode(
                    array(
                        'success'   => true
                        ,'view'     => $view
                    )
                )
            );
        } else {
            die(
                json_encode(
                    array(
                        'status'    => false,
                        'msg'       => 'You are not authorized to perform this action.'
                    )
                )
            );
        }
    }

    function saveCashAdvance(){
        if( validSession() ){
            $params             = getData();
            $invoices           = json_decode($params['invoices'], true);           unset($params['invoices']);
            $liquidation        = json_decode($params['liquidation'], true);        unset($params['liquidation']);
            $match              = 0;
          


            $this->db->trans_begin();

            /** SAVING FOR Cash advances Invoice**/
            $invoices['date']   = date( 'Y-m-d', strtotime( $invoices['date'] ) ) . " " . date( 'H:i:s', strtotime( date( 'Y-m-d', strtotime( $invoices['date'] ) ) .  $invoices['time'] ) );
            $invoices['onEdit'] = $params['onEdit'];
            $idInvoice          = $this->model->saveInvoice( $invoices );

            /** SAVING FOR Cash advances **/
            $params['idInvoice']        = $idInvoice;
            $idCashAdvance    = $this->model->saveCashAdvance( $params );

            /** SAVING FOR Cash advances Liquadation**/
            $liquidation['cashAdvanceID']   = $idCashAdvance;
            $liquidation['orNumber']        = $idCashAdvance;
            $this->model->saveLiquidation( $liquidation );

            if( $this->db->trans_status() === FALSE ){
                $this->db->trans_rollback();
                $match = 1;
            } else { 
                $this->db->trans_commit();

                $msg = ($params['onEdit']) ? ' edited the cash advance details.' : ' added a new cash advance.';

                setLogs(
                    array(
                        'actionLogDescription' => $this->USERNAME . $msg
                        ,'idAffiliate'         => ( isset($params['idAffiliate']) && !empty( $params['idAffiliate'] ) ) ? $params['idAffiliate'] : $this->AFFILIATEID
                        ,'idEu'                => $this->USERID
                        ,'idModule'            => $invoices['idModule']
                        ,'time'                => date("H:i:s A"),
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

        } else {
            die(
                json_encode(
                    array(
                        'status'    => false,
                        'msg'       => 'You are not authorized to perform this action.'
                    )
                )
            );
        }
    }
}
