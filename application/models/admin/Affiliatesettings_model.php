<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Hazel Alegbeleye
 * Module: Affiliate Settings
 * Date: Oct 28, 2019
 * Finished: 
 * Description: The Affiliate Settings module allows only authorized users to setup (add, edit, or delete) affiliate details.
 * DB Tables: affiliate, employee
 * */ 
class Affiliatesettings_model extends CI_Model {

    public function getApprovers( $params ) {

        $this->db->select('idEmployee, idNumber, name, sk');
        $this->db->where('archived', 0);
        $this->db->from('employee');
        
        if( isset($params['idEmployee']) ) {
            $this->db->where_not_in('idEmployee', $params['idEmployee']);
        } 
        
        return $this->db->get()->result_array();
    }

    public function getAffiliates( $params ) {
        $this->db->select("idAffiliate, idAffiliate as id, affiliateName, affiliateName as name, address, contactPerson, contactNumber, email, tin, dateStart,
                            (CASE 
                                WHEN status = 1 THEN 'Active'
                                ELSE 'Inactive'
                            END) as status, 
                            mainTag,
                            sk");
        $this->db->where('archived', 0);
        if( isset( $params['filterValue']) ){
            $this->db->where('idAffiliate', $params['filterValue']);
        }

        if( isset( $params['query']) ) $this->db->like('affiliateName', $params['query'], 'after');

        $this->db->from('affiliate');
        $params['db'] = $this->db;
        $params['order_by'] = 'affiliateName asc';
        return getGridList($params);
    }

    public function saveAffiliate( $params ) {
        $this->db->select('*');
        $this->db->where('affiliateName', $params['affiliateName']);
        $nameExists = $this->db->get('affiliate')->num_rows();

        $response = [];
        $response['match'] = 0;

        if( $params['onEdit'] == 1 ){
            $this->db->where( 'idAffiliate', $params['idAffiliate'] );
            $this->db->update( 'affiliate', unsetParams( $params, 'affiliate' ) );
            $response['idAffiliate'] = $params['idAffiliate'];
            
            $this->db->insert('affiliatehistory', unsetParams($params, 'affiliatehistory'));
        } else {
            if(isset( $params['idAffiliate']) ) unset( $params['idAffiliate'] );
            
            if( $nameExists > 0 ){
                $response['match'] = 2;
            } else {
                $this->db->insert('affiliate', unsetParams($params, 'affiliate'));
                $response['idAffiliate'] = $this->db->insert_id();
            }

            /**Creating default Closing Entry Reference for this affiliate**/
            $this->_createDefaultReference( $response['idAffiliate'], $params['dateStart'] );
        }

        return $response;
    }

    public function _createDefaultReference( $_idAffiliate, $_dateStart ){
        $params = array(
            'idAffiliate'   => $_idAffiliate
            ,'idModule'     =>  35
            ,'date'         => $_dateStart
            ,'seriesFrom'   => 1
            ,'seriesTo'     => 999999
            ,'idReference'  => 1
        );

        $this->db->insert('referenceseries', unsetParams($params, 'referenceseries'));
        $_idReferenceSeries = $this->db->insert_id();
    }

    public function deleteAffiliate( $data ){
        $match = 0;

        /* Getting the assigned Main Affiliate */
        $this->db->select("*");
        $this->db->where('mainTag', 1 );
        $mainTagId =  $this->db->get( 'affiliate' )->result_array()[0]['idAffiliate'];

        /* Checking if Affiliate has transactions */
        $this->db->select('*');
        $this->db->where('idAffiliate', $data['idAffiliate']);
        $relatedEmps = $this->db->get('invoices')->num_rows();

        /**Checking if Affiliate has been used in active cost centers**/
        $this->db->select('costcenter.idCostCenter');
        $this->db->join('costcenter', 'costcenter.idCostCenter = costcenteraffiliate.idCostCenter', 'LEFT');
        $this->db->where( array('costcenter.status' => 1, 'costcenter.archived' => 0, 'idAffiliate' => $data['idAffiliate'] ) );
        $this->db->group_by('costcenter.idCostCenter');
        $relatedEmps += $this->db->get('costcenteraffiliate')->num_rows();

        if( (int)$mainTagId == (int)$data['idAffiliate'] ){
            $match = 1;
        } else if( (int)$relatedEmps > 0 ){
            $match = 2;
        } else {
            /* SOFT DELETE ONLY */
            $this->db->set('archived', 1, false );
            $this->db->where('idAffiliate', $data['idAffiliate'] );
            $this->db->update('affiliate');
        }

        return $match;
    }
    
    public function setMainTag( $params ) {
        $this->db->where( 'idAffiliate', $params['idAffiliate'] );
        $this->db->update( 'affiliate', unsetParams( $params, 'affiliate' ) );
    }

    public function getAffiliate( $params ) {
        $this->db->select("*");
        $this->db->where('idAffiliate', $params['idAffiliate'] );
        return $this->db->get( 'affiliate' )->result_array();
    }

    public function checkAffiliateUsage( $_idAffiliate ){
        $this->db->select('count(*) as count');
        $this->db->where('idAffiliate', $_idAffiliate );
        return $this->db->get('invoices')->row();
    }

    public function checkMainTag(){
        $this->db->select('*');
        $this->db->where('mainTag', 1 );
        return $this->db->get('affiliate')->num_rows();
    }

    public function getLogo( $params ){
        $this->db->select( 'logo' );
        $this->db->where('idAffiliate', $params['idAffiliate']);
		return $this->db->get( 'affiliate' )->row_object();
    }
    
    public function getAffiliateApprovers($params){
        if( isset( $params['idAffiliate'] ) ){
            $this->db->select("affiliateapprover.*, employee.name, employee.sk");
            $this->db->join('employee', 'employee.idEmployee = affiliateapprover.idEmployee', 'LEFT');
            $this->db->where('idAffiliate', $params['idAffiliate']);
            return $this->db->get('affiliateapprover')->result_array();
        }
    }

    public function saveAffiliateApprovers( $params ){
        $this->db->delete( 'affiliateapprover', array('idAffiliate' => $params[0]['idAffiliate'] ));
        $this->db->insert_batch( 'affiliateapprover', $params );
    }
}