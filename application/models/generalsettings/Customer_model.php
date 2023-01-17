<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer_model extends CI_Model {

    function getAffiliates( $params ) {
        if( isset( $params['idAffiliates'] ) &&  $params['idAffiliates'] != '' ) {
            $affiliates = json_decode( $params['idAffiliates'] );
            $this->db->select(" a.idAffiliate
                                ,a.affiliateName
                                ,( case
                                    when b.idAffiliate IS NULL THEN 0
                                    else 1
                                end ) as chk
                                ,a.sk");
            $this->db->from('affiliate as a');
            $this->db->join('( SELECT idAffiliate from affiliate where idAffiliate in (' . join(", ", $affiliates) . ') ) as b', 'on a.idAffiliate = b.idAffiliate', 'LEFT');
        } else {
            $this->db->select("a.idAffiliate, a.affiliateName, a.sk");
            $this->db->from('affiliate as a');
        }


        $this->db->where( 'a.archived', 0 );

        $params['db'] = $this->db;
        $params['order_by'] = 'a.affiliateName asc';
        return getGridList($params);
    }

    function getCoa( $params ) {
        $affiliates = isset($params['affiliates'])? json_decode( $params['affiliates'] ) : ['0'];
        $this->db->select("
            coa.idCoa AS id,
            coa.acod_c15 AS code,
            coa.aname_c30 AS name
        ");
        $this->db->from('coa');
        $this->db->join('coaaffiliate', 'ON coaaffiliate.idCoa = coa.idCoa ', 'LEFT');
        $this->db->where_in('coaaffiliate.idAffiliate', $affiliates);
        

        $params['db'] = $this->db;
        $params['order_by'] = 'id asc';
        return getGridList($params);
    }

    function getItems($params){

        $affiliates = isset($params['affiliates'])? json_decode( $params['affiliates'] ) : ['0'];
        $selectedItems = isset($params['selectedItem'])? json_decode( $params['selectedItem'] ) : ['0'];

        $this->db->distinct();
        $this->db->select('a.idItem, b.itemName, b.barcode, c.className, b.sk');
        $this->db->from('itemaffiliate as a');
        $this->db->join('item as b', 'b.idItem = a.idItem', 'inner');
        $this->db->join('itemclassification as c', 'b.idItemClass = c.idItemClass', 'inner');
        $this->db->where_in('a.idAffiliate', $affiliates);

        $this->db->where( 'b.archived', 0 );

        if( isset( $params['selectedItem'] ) ) {
            $this->db->where_not_in('a.idItem', $selectedItems);
        }

        $params['db'] = $this->db;
        $params['order_by'] = 'idItem asc';
        return getGridList($params);
    }

    function getCustomerItem( $params ){
        if( isset( $params['idCustomer'] ) ){
            $this->db->select('
                        a.idCustomerItems,
                        a.idCustomer,
                        a.idItem,
                        b.barcode,
                        b.itemName,
                        c.className,
                        b.sk');
            $this->db->from('customeritems as a');
            $this->db->join('item as b', 'on a.idItem = b.idItem', 'inner');
            $this->db->join('itemclassification as c', 'on b.idItemClass = c.idItemClass', 'inner');
            $this->db->where('a.idCustomer', $params['idCustomer']);

            $this->db->where( 'b.archived', 0 );

            $params['db'] = $this->db;
            $params['order_by'] = 'idCustomer asc';
            return getGridList($params);
        }
    }

    function saveCustomerAffiliates( $params ){
        $this->db->insert( 'customeraffiliate', unsetParams( $params, 'customeraffiliate') );
        return $this->db->insert_id();
    }


    function deleteCustomerDetails( $tableName, $idCustomer ){
        /* SOFT DELETE ONLY */
        // $this->db->set('archived', 1, false );
        // $this->db->where('idCustomer',$idCustomer );
        // $this->db->update('customer');

        $this->db->delete( $tableName, array('idCustomer' => $idCustomer ));
    }

    function saveCustomerAffiliatesHistory( $params ){
        $this->db->insert_batch( 'customeraffiliatehistory', $params );
    }

    function saveCustomerItems( $params, $paramsHistory ){
        $this->db->delete( 'customeritems', array('idCustomer' => $params['idCustomer'] ));
        unset( $params['idCustomer'] );
        $this->db->insert_batch( 'customeritems', $params );

        /* Saving for Customer Items History */
        $this->db->insert_batch( 'customeritemshistory', $paramsHistory );
    }

    function saveCustomerContacts( $params, $paramsHistory ){
        $this->db->delete( 'custcontactperson', array('idCustomer' => $params['idCustomer'] ));
        unset( $params['idCustomer'] );
        $this->db->insert_batch( 'custcontactperson', $params );

        /* Saving for Customer Contacts History */
        $this->db->insert_batch( 'custcontactpersonhistory', $paramsHistory );
    }

    function saveCustomer( $params ){
        if( isset( $params['onEdit']) && $params['onEdit'] == 1 ) {
            $idCustomer = $params['idCustomer'] ;
            unset( $params['idCustomer'] );

            $this->db->where( 'idCustomer' , $idCustomer );
            $this->db->update( 'customer', unsetParams( $params, 'customer' ));
            $result = $idCustomer;
        } else {
            $this->db->insert( 'customer', unsetParams( $params, 'customer' ) );
            $result = $this->db->insert_id();
        }
        
        return $result;
    }

    function saveCustomerHistory( $params ){
        /* Saving in History */
        $this->db->insert( 'customerhistory', unsetParams($params, 'customerhistory') );
        return $this->db->insert_id();
    }

    function getCustomers( $params ){
        $this->db->select('idCustomer as id, idCustomer, name, address, tin, contactNumber, sk');
        $this->db->from('customer');
        $this->db->where('archived', 0);

        if( isset( $params['filterBy']) && isset( $params['filterValue'] ) ) {
            $this->db->like( $params['filterBy'], $params['filterValue'], 'after' );
        }

        if( isset( $params['filterValue'] ) ) {
            $this->db->where( 'idCustomer', $params['filterValue']);
        }


        $params['db'] = $this->db;
        $params['order_by'] = 'idCustomer asc';
        return getGridList($params);
    }

    function retrieveData( $params ) {
        $this->db->select('
                            a.idCustomer, 
                            a.name, 
                            a.email,
                            a.tin, 
                            a.address, 
                            a.contactNumber,
                            ,group_concat( b.idAffiliate ) as idCustAffiliates
                            ,a.paymentMethod
                            ,a.terms
                            ,a.withCreditLimit
                            ,a.creditLimit
                            ,withVAT
                            ,vatType
                            ,vatPercent
                            ,penalty
                            ,discount
                            ,withHoldingTax
                            ,withHoldingTaxRate
                            ,salesGLAcc
                            ,discountGLAcc
                            ,a.sk');
        $this->db->where('a.idCustomer', $params['idCustomer']);
        $this->db->join('customeraffiliate as b', 'a.idCustomer = b.idCustomer', 'INNER');
        $this->db->from('customer as a');
        
        return $this->db->get()->result_array();
    }

    function deleteCustomer( $params ){
        $match = 0;

        $this->db->select('*');
        $this->db->where( array(
            'pCode' => $params['idCustomer']
            ,'pType' => 1
        ));
        $num_rows = $this->db->get('invoices')->num_rows();

        if( $num_rows > 0 ) {
            $match = 1;
        } else {
            // $this->db->delete( 'customer', array('idCustomer' => $params['idCustomer'] ));
            
            /* SOFT DELETE ONLY */
            $this->db->set('archived', 1, false );
            $this->db->where('idCustomer',$params['idCustomer'] );
            $this->db->update('customer');
        }

        return $match;
    }

    function deleteItem( $params ){
        // $this->db->delete( 'customeritems', array('idCustomerItems' => $params['idCustomerItems'] ));

        /* SOFT DELETE ONLY */
        // $this->db->set('archived', 1, false );
        // $this->db->where('idCustomer',$params['idCustomer'] );
        // $this->db->update('customer');
    }

    function getContacts( $params ){
        $this->db->select('*');
        $this->db->where('idCustomer', $params['idCustomer']);

        return $this->db->get('custcontactperson')->result_array();
    }
}