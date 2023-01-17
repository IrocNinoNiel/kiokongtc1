<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Hazel Alegbeleye
 * Module: Reference Settings
 * Date: Nov 25, 2019
 * Finished: December 4, 2019
 * Description: This module allows the authorized user to set (add, edit and delete ) the references that will be used in every transaction in the system.
 * DB Tables: affiliate, module, costcenter, reference, referenceaffiliate, referenceseries, invoices
 * */ 
class Referencesettings_model extends CI_Model {

    public function getAffiliates1( $params ) {
        
        if( isset( $params['idAffiliates'] ) ) {
            $this->db->select("idAffiliate, affiliateName, (case when idAffiliate in (". trim($params['idAffiliates'], '[]' ).") then NULL else 0 end) as sorter, refTag, sk");
        } else {
            $this->db->select("idAffiliate, affiliateName, 0 as sorter, refTag, sk");
        }

        $this->db->where('archived', 0);
        $this->db->from('affiliate');

        $params['db'] = $this->db;
        $params['order_by'] = 'sorter desc, affiliateName asc';
        return getGridList($params);
    }

    function getModules( $params ) {
        $this->db->select('idModule, moduleName');
        $this->db->where( array('isTransaction' => 0, 'moduleArchive' => 0 ) );
        $this->db->where_not_in('idModule', 35 );

        $this->db->order_by('moduleName asc');
        return $this->db->get('module')->result_array();
    }

    function getCostCenters( $params ) {
        if( isset( $params['idAffiliate'] ) ) {
            $this->db->distinct();
            $this->db->select('a.idCostCenter, a.costCenterName, b.idAffiliate, a.sk');
            $this->db->from('costcenter as a');
            $this->db->join('costcenteraffiliate as b', 'a.idCostCenter = b.idCostCenter', 'inner');
            $this->db->where( array( 'idAffiliate' => $params['idAffiliate'], 'archived' => 0 ));
    
            return $this->db->get()->result_array();
        }
    }

    function getReference( $params ) {
        if( isset( $params['idAffiliate'] ) && isset( $params['idModule']) ) {
            $this->db->select('a.idReference, a.name, b.idAffiliate');
            $this->db->join('referenceaffiliate as b', 'a.idReference = b.idReference', 'INNER');
            $this->db->where( 
                                array( 
                                    'idAffiliate' => $params['idAffiliate']
                                    ,'idModule' => $params['idModule']
                                    ,'archived' => 0
                                )
                            );
            return $this->db->get('reference as a')->result_array();
        }
    }

    function viewHistory( $params ){
        $values = '';
        switch( $params['mode'] ) {
            case 'initial':
                $this->db->distinct();
                $this->db->select('reference.idReference, reference.code, reference.name, module.moduleName');
                $this->db->join('module', 'module.idModule = reference.idModule', 'LEFT');
                // $this->db->join('referenceaffiliate', 'referenceaffiliate.idReference = reference.idReference', 'LEFT');
                // $this->db->join('affiliate', 'affiliate.idAffiliate = referenceaffiliate.idAffiliate', 'LEFT');
                $this->db->where( array( 'reference.archived' => 0, 'reference.idModule !=' => 35 ) );
                $this->db->from('reference');

                $orderby = 'name asc, code asc, moduleName asc';
                break;
            case 'series':
                $this->db->select(" referenceseries.idReferenceSeries,
                                    referenceseries.date,
                                    affiliate.affiliateName,
                                    costcenter.costCenterName,
                                    module.moduleName,
                                    reference.name,
                                    reference.code,
                                    referenceseries.seriesFrom,
                                    referenceseries.seriesTo,
                                    costcenter.sk as costCenterSK,
                                    affiliate.sk as affiliateSK");
                $this->db->join('affiliate', 'referenceseries.idAffiliate = affiliate.idAffiliate and affiliate.archived = 0', 'LEFT');
                $this->db->join('costcenter', 'costcenter.idCostCenter = referenceseries.idCostCenter and costcenter.archived = 0', 'LEFT');
                $this->db->join('module', 'module.idModule = referenceseries.idModule', 'LEFT');
                $this->db->join('reference', 'reference.idReference = referenceseries.idReference', 'LEFT');
                $this->db->where( array('referenceseries.archived' => 0, 'reference.archived' => 0, 'reference.idModule !=' => 35 ) );
                $this->db->from('referenceseries');
                $orderby = 'date asc, affiliateName asc, code asc';
                break;
        }

        if( isset( $params['filterKey'] ) && isset( $params['filterValue'] )) {
            $this->db->like($params['filterKey'], $params['filterValue'], 'after');
        }

        $params['db'] = $this->db;
        $params['order_by'] = $orderby;

        return getGridList($params);
    }

    function retrieveData( $params ) {
        $this->db->select('reference.idReference
                        ,reference.code
                        ,reference.name
                        ,reference.idModule
                        ,group_concat( referenceaffiliate.idAffiliate ) as idRefAffiliates');
        $this->db->where('reference.idReference', $params['idReference']);
        $this->db->join('referenceaffiliate', 'reference.idReference = referenceaffiliate.idReference', 'INNER');
        // $this->db->from('reference as a');

       $initial = $this->db->get('reference')->row();

       /* CHECK IF THE SELECTED RECORD HAS ALREADY BEEN USED. */
       $this->db->select("*");
       $this->db->where("idReference", $params['idReference']);
       $record = [];

       if( $initial->idModule == 44 ){
           /* Look through bank recon */
           $record = $this->db->get('bankrecon')->num_rows();
       } else {
           /* Look through invoices */
           $record = $this->db->get('invoices')->num_rows();
       }

       return array('view' => $initial, 'rec' => $record );

    }

    function retrieveReferenceSeries( $params ){
        /* RETRIEVE RECORD */
        $this->db->select('*');
        $this->db->where( 'idReferenceSeries', $params['idReferenceSeries']);
        $series = $this->db->get('referenceseries')->row();

        /* CHECK IF THE SELECTED RECORD HAS ALREADY BEEN USED. */
        $this->db->select("*");
        $this->db->where("idReferenceSeries", $params['idReferenceSeries']);
        $record = [];

        if( $series->idModule == 44 ){
            /* Look through bank recon */
            $record = $this->db->get('bankrecon')->num_rows();
        } else {
            /* Look through invoices */
            $record = $this->db->get('invoices')->num_rows();
        }

        return array('view' => $series, 'rec' => $record );
    }

    function saveInitial( $params ) {

        if( empty( $params['idReference'] ) ) unset( $params['idReference'] );

        if( isset( $params['onEdit'] ) && $params['onEdit'] == 1 ){
            $idReference = $params['idReference'];
            unset( $params['idReference'] );

            /* Check if the new code is not yet taken. */
            $this->db->select('*');
            $this->db->where( array( 'code' => $params['code'], 'archived' => 0, 'idReference != ' => $idReference ) );
            $usedCode = $this->db->get('reference')->result_array();

            /* Check if reference is already used. */
            // $this->db->select("*");
            // $this->db->where("idReference", $idReference);

            // if( $params['idModule'] == 44 ){
            //     $usedReference = $this->db->get('bankrecon')->num_rows();
            // } else {
            //     $usedReference = $this->db->get('invoices')->num_rows();
            // }

            if( count($usedCode) > 0 ){
                return false;
            } else {
                $this->db->where( 'idReference' , $idReference );
                $this->db->update( 'reference', unsetParams( $params, 'reference' ));
            }
            
        } else {
            /* Get all reference/s with the same code */
            $this->db->select('*');
            $this->db->where( array( 'code' => $params['code'], 'archived' => 0 ) );
            $existingRef = $this->db->get('reference')->num_rows();

            if( $existingRef > 0 ) {
                return false;
            } else {
                /* Saving new reference */
                $idReference = $this->_saveInitial( $params );
            }
        }

        return $idReference;
    }

    function _checkUsage( $id, $tableName, $fieldName ){
        $this->db->select("*");
        $usage = $this->db->get($tableName)->num_rows();

        return $used = ( $usage > 0 ) ? true : false;
    }

    function saveSeries( $params ){
        $idReferenceSeries = 0;
        
        if( empty( $params['idReferenceSeries'] ) ) unset( $params['idReferenceSeries'] );
        
        if( isset( $params['onEdit'] ) && $params['onEdit'] == 1 ){
            $idReferenceSeries = $params['idReferenceSeries'];
            unset( $params['idReferenceSeries'] );

            // $this->db->select('*');
            // $this->db->where('idReferenceSeries', $idReferenceSeries);
            // $usedSeries = $this->db->get('referenceseries')->num_rows();

            if( $this->_checkUsage( $idReferenceSeries, 'invoices', 'idReferenceSeries' ) ){
                return false;
            } else {
                $this->db->where( 'idReferenceSeries' , $idReferenceSeries );
                $this->db->update( 'referenceseries', unsetParams( $params, 'referenceseries' ));
            }
            
        } else {
            $record = $this->__getLastSeriesRecord( $params );

            if( !empty( $record) ){
                if( $params['seriesFrom'] > $record->seriesTo ){
                    $idReferenceSeries = $this->_saveSeries( $params );
                }
            } else {
                $idReferenceSeries = $this->_saveSeries( $params );
            }

        }

        return $idReferenceSeries;
    }

    function __getLastSeriesRecord( $params ){
        $this->db->select("idReferenceSeries, seriesFrom, seriesTo");
        $this->db->where( array(
            'idReference'   => $params['idReference']
            ,'idModule'     => $params['idModule']
            ,'idAffiliate'  => $params['idAffiliate']
            ,'archived'     => 0
        ) );

        if( isset( $params['idCostCenter'] ) ) $this->db->where( 'idCostCenter', $params['idCostCenter'] );

        $this->db->order_by('idReferenceSeries DESC');
        $this->db->limit(1);
        return $this->db->get('referenceseries')->row();
    }

    function _saveSeries( $params ){
        $this->db->insert('referenceseries', unsetParams( $params, 'referenceseries' ) );
        return $this->db->insert_id();
    }

    function _saveInitial( $params ){
        $this->db->insert('reference', unsetParams( $params, 'reference' ) );
        return $this->db->insert_id();
    }

    function getReferenceCode( $params ){
        if( isset( $params['idReferenceSeries'] ) ) {
            $this->db->select("reference.code");
            $this->db->where("referenceseries.idReferenceSeries", $params['idReferenceSeries']);
            $this->db->join("reference", "reference.idReference = referenceseries.idReference", "INNER");
            $this->db->group_by("reference.idReference");
            return $this->db->get("referenceseries")->result_array();
        } else {
            $this->db->select("code");
            $this->db->where("idReference", $params['idReference']);
            return $this->db->get("reference")->result_array();
        }
    }

    function saveReferenceAffiliates( $params, $idReference ) {
        $this->db->delete( 'referenceaffiliate', array('idReference' => $idReference ));
        $this->db->insert_batch( 'referenceaffiliate', $params );
    }

    function deleteReference( $params ) {
        $match = 0;
        $this->db->select('*');
        $this->db->where( array( 'idReference' => $params['idReference']));
        $num_rows = $this->db->get('invoices')->num_rows();

        if( $num_rows > 0 ) {
            $match = 1;
        } else {

            $this->db->select('*');
            $this->db->where( array( 'idReference' => $params['idReference'], 'isDefault' => 1 ) );
            $_isDefault = $this->db->get('reference')->num_rows();
            if( $_isDefault > 0 ) return $match = 2;
                
            /* SOFT DELETE ONLY */
            $this->db->set('archived', 1, false );
            $this->db->where('idReference', $params['idReference'] );
            $this->db->update('reference');
        }

        // $msg = "This reference is already used and cannot be deleted.";

        return $match;
    }

    function deleteSeries( $params ) {
        $match = 0;
        $this->db->select('*');
        $this->db->where('idReferenceSeries', $params['idReferenceSeries']);
        $num_rows = $this->db->get('invoices')->num_rows();

        if( $num_rows > 0 ) {
            $match = 1;
        } else {
            /* SOFT DELETE ONLY */
            $this->db->set('archived', 1, false );
            $this->db->where('idReferenceSeries', $params['idReferenceSeries'] );
            $this->db->update('referenceseries');
        }

        // $msg = "This reference is already used and cannot be deleted.";

        return $match;
    }

    function getRefDetails( $params ){
        return $this->db->select('idReferenceSeries, idReference, seriesFrom, seriesTo')
            ->where( 'idReference', $params['idReference'] )
            ->order_by('idReferenceSeries ASC')
            ->get('referenceseries')->result_array();
    }

}