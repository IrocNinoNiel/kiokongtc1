<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Hazel Alegbeleye
 * Module: Cost Center Settings
 * Date: Oct 29, 2019
 * Finished: 
 * Description: 
 * DB Tables: 
 * */ 
class Costcentersettings_model extends CI_Model {

    public function getAffiliates( $params ) {
        
        if( isset( $params['affiliates'] ) ) {
            $this->db->select("idAffiliate, affiliateName, (case when idAffiliate in (". trim($params['affiliates'], '[]' ).") then 0 else NULL end) as sorter");
        } else {
            $this->db->select("idAffiliate, affiliateName, 0 as sorter");
        }
        
        $this->db->from('affiliate');

        $params['db'] = $this->db;
        $params['order_by'] = 'sorter desc, affiliateName asc';
        return getGridList($params);
    }

    public function getCostCenters( $params ) {
        $this->db->distinct();
        $this->db->select("a.idCostCenter, 
                            a.costCenterName, 
                            a.remarks, 
                            ( case a.status 
                                    when a.status = 1 then 'Active' 
                                    else 'Inactive' 
                            end ) as status
                            ,sk");
        $this->db->where( 'archived', 0 );
        $this->db->from("costcenter as a ");
        
        if( isset( $params['filterValue']) ) {
            $this->db->where( 'a.idCostCenter', $params['filterValue'] );
        }

        $params['db'] = $this->db;
        $params['order_by'] = 'a.costCenterName asc';
        return getGridList($params);
    }

    public function getCostCenter( $params ) {
        $this->db->select("a.idCostCenter, 
                            a.costCenterName, 
                            a.remarks, 
                            ( case a.status 
                                    when a.status = 1 then 'Active' 
                                    else 'Inactive' 
                            end ) as status,
                            group_concat(b.idAffiliate) as idAffiliate
                            ,a.sk ");
        $this->db->from("costcenter as a ");
		$this->db->join('costcenteraffiliate as b','a.idCostCenter = b.idCostCenter','INNER');
        $this->db->where( 'a.idCostCenter', $params['idCostCenter'] );
        return $this->db->get()->result_array();
    }

    public function deleteCostCenter( $data ){
        $match = 0;
        $this->db->select('count(idInvoice) as count');
        $this->db->where('idCostCenter', $data['idCostCenter']);
        $relatedEmps = $this->db->get('invoices')->result_array()[0]['count'];

        if( $relatedEmps > 0 ) {
            $match = 1;
        } else {
            /* SOFT DELETE ONLY */
            $this->db->set('archived', 1, false );
            $this->db->where('idCostCenter', $data['idCostCenter'] );
            $this->db->update('costcenter');
            
            //$this->db->delete( 'costcenter', array( 'idCostCenter' => $data['idCostCenter'] ) );
        }

        return $match;
    }

    public function saveCostCenter( $params ) {

        /* Check if cost center name already exist. */
        $exists = $this->checkExists( $params );
        if( $exists > 0 ){
            return false;
        } else {
            if( isset( $params['onEdit']) && $params['onEdit'] == 1 ){
                $this->db->where( 'idCostCenter', $params['idCostCenter'] );
                $this->db->update( 'costcenter', unsetParams( $params, 'costcenter' ) );
                $idCostCenter = $params['idCostCenter'];
                
                $this->db->insert('costcenterhistory', unsetParams($params, 'costcenterhistory'));
            } else {
                $this->db->insert('costcenter', unsetParams($params, 'costcenter'));
                $idCostCenter = $this->db->insert_id();
            }
        }

        return $idCostCenter;
    }

    public function checkExists( $params ){
        $this->db->select('*');
        if( isset( $params['onEdit']) && $params['onEdit'] == 1 ){
            $this->db->where( array( 'costCenterName' => $params['costCenterName'], 'idCostCenter != ' => $params['idCostCenter']) );
        } else {
            $this->db->where('costCenterName', $params['costCenterName'] );
        }
        
        return $this->db->get('costcenter')->num_rows();
    }

    public function saveCostAffiliate( $params ) {
        $this->db->insert('costcenteraffiliate', unsetParams( $params, 'costcenteraffiliate' ));
    }

    public function getSearchCostCenter( $params ){
        $this->db->select( 'a.idCostCenter as id, LTRIM( RTRIM( a.costCenterName) ) as name, sk');
        $this->db->order_by( 'name', 'asc' );

        $this->db->from("costcenter as a ");
        $this->db->where('archived', 0);
        $this->db->join('costcenteraffiliate as b','a.idCostCenter = b.idCostCenter','INNER');

        if( isset( $params['query']) ) {
            $this->db->like( 'costCenterName', $params['query'], 'both' );
        }

        $this->db->order_by('a.costCenterName ASC');
        return $this->db->get()->result_array();
    }

    public function updateCostCenter( $params ){
        if( array_key_exists( 'costCenterName', $params) ) unset( $params['costCenterName'] );
        $this->db->where( 'idCostCenter', $params['idCostCenter'] );
        $this->db->update( 'costcenter', unsetParams( $params, 'costcenter' ) );

        return $params;
    }
}