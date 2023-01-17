<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Jayson Dagulo
 * Module: Standards2(Additional file for standards to be applied only on this Project)
 * Date: Oct 21, 2019
 * Finished: 
 * Description: This contains the functions and components that are considered a standard only for this project
 * */ 
class Standards2_model extends CI_Model {

    public function getAffiliate( $params, $id ){
        // if( empty( $params['fieldName'] ) ) $params['fieldName'] = '';
        // if( empty( $params['fieldID'] ) ) $params['fieldID'] = '';
        // if( empty( $params['tableName'] ) ) $params['tableName'] = '';
        $this->db->select( "affiliate.affiliateName as name, affiliate.idAffiliate as id, affiliate.refTag
                            ,CONCAT( 
                                (case 
                                    WHEN affiliate.approvedBy1 IS NULL THEN 0 
                                    ELSE affiliate.approvedBy1 
                                end)
                                , ','
                                ,(case
                                    WHEN affiliate.approvedBy2 IS NULL THEN 0
                                    ELSE affiliate.approvedBy2
                                end) ) as approvers
                                ,affiliate.dateStart
                                ,sk" );
        $this->db->join("employeeaffiliate", "employeeaffiliate.idAffiliate = affiliate.idAffiliate", "LEFT");
        $this->db->where("employeeaffiliate.idEmployee", $id);
        
        if( isset( $params['idAffiliate']) ) $this->db->where('affiliate.idAffiliate', $params['idAffiliate'] );
        if( isset( $params['query'] ) ) $this->db->like( 'affiliate.affiliateName', $params['query'], 'both' );
        $this->db->order_by( 'affiliate.affiliateName', 'asc' );
        return $this->db->get( 'affiliate' )->result_array();
    }

    public function getReference($params){
        if( isset( $params['idAffiliate'] ) && (int)$params['idAffiliate'] ) {

            // if( isset( $params['date'] ) ) $date = date_format( new DateTime( $params['date'] ), 'Y-m-d' );

            // if( empty( $params['fieldName'] ) ) $params['fieldName'] = 'code';
            // if( empty( $params['fieldID'] ) ) $params['fieldID'] = 'idReference';
            // if( empty( $params['tableName'] ) ) $params['tableName'] = 'reference';

            $this->db->select('reference.idReference as id, reference.code as name');
            $this->db->join('referenceaffiliate', 'referenceaffiliate.idReference = reference.idReference', 'left');
            $this->db->join('referenceseries', 'referenceseries.idReference = reference.idReference', 'inner');
            $this->db->join('module', 'module.idModule = referenceseries.idModule', 'inner');
            $this->db->join('costcenter', 'costcenter.idCostCenter = referenceseries.idCostCenter and costcenter.archived = 0', 'LEFT');
            $this->db->join('affiliate', 'affiliate.idAffiliate = referenceseries.idAffiliate and affiliate.archived = 0', 'inner');
            
            
            $query = [];
            if( isset( $params['date'] ) ) $query['referenceseries.date <= '] = date_format( new DateTime( $params['date'] ), 'Y-m-d' );
            
            if( isset( $params['idAffiliate'] ) ) {
                $query['affiliate.idAffiliate'] = $params['idAffiliate'];
            }
            if( isset( $params['idModule'] ) ) $query['module.idModule'] = $params['idModule'];
            if( isset( $params['idCostCenter'] ) && is_numeric( $params['idCostCenter'] )) $query['costcenter.idCostCenter'] = $params['idCostCenter'];

            $this->db->where( $query );
            
            if( isset( $params['query'] ) ) $this->db->like( 'code', $params['query'], 'both' );

            $this->db->order_by( 'code', 'asc' );
            return $this->db->get('reference')->result_array();
        }
    }

    public function getReferenceNum( $params ){
        if( isset( $params['idReference'] ) ) {

            $idModule = ( isset($params['idModule']) ? $params['idModule'] : $params['idmodule'] );

            $this->db->select("
                                IFNULL(MAX(referenceNum)+1, referenceseries.seriesFrom) as referenceNum, 
                                referenceseries.seriesFrom, 
                                referenceseries.seriesTo,
                                referenceseries.idReferenceSeries"
            );
            
            $this->db->join('reference', 'reference.idReference = referenceseries.idReference');

            if( isset( $idModule ) && (int)$idModule == 44 ){
                $this->db->join("bankrecon", "bankrecon.idReference = referenceseries.idReference", "left");
            } else {
                $this->db->join("invoices", "invoices.idReference = reference.idReference and invoices.idReferenceSeries = referenceseries.idReferenceSeries", "left");
            }
            
            $this->db->join('affiliate', 'affiliate.idAffiliate = referenceseries.idAffiliate and affiliate.archived = 0', 'LEFT');
            // if( isset( $params['idCostCenter'] ) && !empty( $params['idCostCenter'] ) ) $this->db->where('referenceseries.idCostCenter', $params['idCostCenter'] );
            // $this->db->where(array("referenceseries.idReference" => $params['idReference'], 'referenceseries.idModule' => $params['idModule'], 'referenceseries.idAffiliate' => $params['idAffiliate'] ));
            
            $this->db->group_by("referenceseries.idReferenceSeries");

            

            $_qry = '(SELECT * FROM referenceseries where ';
            if( isset($params['idCostCenter']) && !empty($params['idCostCenter'])) $_qry .= 'idCostCenter = ' . $params['idCostCenter'] . ' and ';
            $_qry .= ' idAffiliate = ' . $this->AFFILIATEID . ' ';
            $_qry .= ' and idModule = ' . $idModule . ' ';
            $_qry .= ' and idReference = ' . $params['idReference'] . ')';

            return $this->db->get($_qry.' as referenceseries')->result_array();

            /* Code revision 1 */

            // $this->db->select(" referenceseries.idReferenceSeries,
            //                     count(idInvoice) as transactions,
            //                     seriesFrom,
            //                     seriesTo,
            //                     ( case count(idInvoice) 
            //                         when 0 then seriesFrom 
            //                         when count(idInvoice) = seriesTo then max(invoices.referenceNum)
            //                         else max(invoices.referenceNum) + 1
            //                     end ) as refnum,
            //                     (case 
            //                         when referenceseries.idReferenceSeries > 0 and count(idInvoice) < seriesTo then 0
            //                         when referenceseries.idReferenceSeries IS NULL then 1
            //                         when count(idInvoice) = seriesTo then 2
            //                         when referenceseries.idReferenceSeries > 0 and count(idInvoice) > seriesTo then 2
            //                     end) as 'match' ");
            // $this->db->where( array(
            //     "referenceseries.idReference"   => $params['idReference']
            //     ,"referenceseries.archived"     => 0
            // ) );
            // $this->db->join("invoices", "invoices.idReferenceSeries = referenceseries.idReferenceSeries and invoices.archived = 0", "left" );
            // $this->db->group_by("referenceseries.idReferenceSeries, referenceseries.idReference");
            // return $this->db->get('referenceseries')->result_array();

            /* Code Revision 2 */
            
            // $this->db->select("
            //                     IFNULL(MAX(referenceNum)+1, referenceseries.seriesFrom) as referenceNum, 
            //                     referenceseries.seriesFrom, 
            //                     referenceseries.seriesTo,
            //                     referenceseries.idReferenceSeries"
            // );
            
            // if( isset( $params['idModule'] ) && (int)$params['idModule'] == 44 ){
            //     $this->db->join("bankrecon", "bankrecon.idReference = referenceseries.idReference", "left");
            // } else {
            //     $this->db->join("invoices", "invoices.idReference = referenceseries.idReference", "left");
            // }
            
            // $this->db->join('affiliate', 'affiliate.idAffiliate = referenceseries.idAffiliate and affiliate.archived = 0', 'INNER');
            // $this->db->where( array('affiliate.idAffiliate' => $params['idAffiliate'] ) );
            // if( isset( $params['idCostCenter'] ) && !empty( $params['idCostCenter'] ) ) $this->db->where('reference.idCostCenter', $params['idCostCenter'] );
            // $this->db->where(array("referenceseries.idReference" => $params['idReference'], 'referenceseries.idModule' => $params['idModule'] ));
            // $this->db->group_by("referenceseries.idReferenceSeries");

            // return $this->db->get('referenceseries')->result_array();
        }
    }

    function _getLatestRefNum($params){
        $this->db->select('IFNULL(MAX(referenceNum),0) as referenceNum');
        $this->db->where( array(
            'idAffiliate'   => $this->session->userdata('AFFILIATEID')
            ,'idReference'  => $params['idReference']
        ) );
        if( isset( $params['idCostCenter'] ) && !empty( $params['idCostCenter'] ) ) $this->db->where('idCostCenter', $params['idCostCenter']);
        return $this->db->get('invoices')->row();
    }

    function _getRefNum( $params ) {
        $transactions = $this->checkTransactions( $params );


        // var_dump($transactions);

        $result = [];

        // var_dump( $transactions );

        if( (int)$transactions > 0 ) {
            $this->db->select('max(referenceNum) as referenceNum');
            $this->db->where( array('idReferenceSeries' => (int)$params['idReferenceSeries'] ) );
            $result = $this->db->get('invoices')->result_array()[0];
        } else {
            $result['referenceNum'] = $params['seriesFrom'] + 1;
        }

        print_r( $params );
        print_r( $result );

        return $result;
    }

    function checkTransactions( $params ){
        $this->db->select('*');
        $this->db->where('idReferenceSeries', $params['idReferenceSeries']);
        return $this->db->get('invoices')->num_rows();
    }

    public function getCostCenter( $params ){
        if( empty( $params['fieldName'] ) ) $params['fieldName'] = 'costCenterName';
        if( empty( $params['fieldID'] ) ) $params['fieldID'] = 'idCostCenter';
        if( empty( $params['tableName'] ) ) $params['tableName'] = 'costcenter';

        $this->db->select( "a.$params[fieldName] as name, a.$params[fieldID] as id, a.sk" );
        $this->db->from( $params['tableName'] . ' as a');
        if( isset( $params['query'] ) ) $this->db->like( $params['fieldName'], $params['query'], 'both' );
        if( isset( $params['idAffiliate'] ) ) {
            $this->db->join('costcenteraffiliate', 'a.idCostCenter = costcenteraffiliate.idCostCenter', 'inner');
            $this->db->where( array( 'costcenteraffiliate.idAffiliate' => $params['idAffiliate'], 'archived' => 0 ) );
        }
        $this->db->where('a.status', '1');
       
        $this->db->order_by( $params['fieldName'], 'asc' );
        return $this->db->get()->result_array();
    }

    public function getSupplierItems( $params ){
        $idaffiliate = isset( $params['idAffiliate'] ) ? $params['idAffiliate'] : 0;
        $pcode = isset( $params['pCode'] ) ? $params['pCode'] : 0;

        return $this->db->query("
            SELECT DISTINCT
                item.idItem,
                item.barcode,
                item.itemName,
                supplieritems.idSupplier,
                itemclassification.className
            FROM
                item
                    LEFT OUTER JOIN
                supplieritems ON supplieritems.idItem = item.idItem
                    LEFT OUTER JOIN
                itemaffiliate ON itemaffiliate.idItem = item.idItem
                    LEFT OUTER JOIN
                itemclassification on itemclassification.idItemClass = item.idItemClass
            where itemaffiliate.idAffiliate = $idaffiliate AND supplieritems.idSupplier = $pcode")->result_array();
    }

    public function getCustomerItems( $params ){
        $idaffiliate = isset( $params['idAffiliate'] ) ? $params['idAffiliate'] : 0;
        $pcode = isset( $params['pCode'] ) ? $params['pCode'] : 0;

        return $this->db->query("
            SELECT DISTINCT
                item.idItem,
                item.barcode,
                item.itemName,
                customeritems.idCustomer,
                itemclassification.className
            FROM
                item
                    LEFT OUTER JOIN
                customeritems ON customeritems.idItem = item.idItem
                    LEFT OUTER JOIN
                itemaffiliate ON itemaffiliate.idItem = item.idItem
                    LEFT OUTER JOIN
                itemclassification on itemclassification.idItemClass = item.idItemClass
            where itemaffiliate.idAffiliate = $idaffiliate AND customeritems.idCustomer = $pcode")->result_array();
    }

    function updateTransactionStatus( $params ){
        $condition = array( 'idInvoice' => $params['idInvoice'] );
		
		if( $params['status'] == 3 ){ // CANCELLED
			/* To update later when closing tag are available. */
			
			// $this->db->select('*');
            // $this->db->where( $condition );
			// $this->_updateStatus( $condition, $params );
		} else {
			if( isset( $params['checkOnTable'])  );  /*PARA SA USED TRANSACTION.*/
			// $this->_updateStatus( $condition, $params );
		}
		$this->_updateStatus( $condition, $params );
    }

    function _updateStatus( $condition, $params ) {
        $this->db->where( $condition );
        $this->db->update( 'invoices', unsetParams( $params, 'invoices' ) );
    }

    function getSupplier( $params ) {
        
        $this->db->distinct();
        $this->db->select('
                    supplier.idSupplier as id, 
                    supplier.name, 
                    supplieraffiliate.idAffiliate, 
                    supplier.tin, 
                    supplier.address, 
                    supplier.vatType, 
                    supplier.vatPercent, 
                    supplier.discount as discountPercentage,
                    supplier.paymentMethod as payMode,
                    invoices.downPayment,
                    supplier.terms,
                    supplier.sk');
        $this->db->from('supplier');
        $this->db->join('supplieraffiliate', 'supplier.idSupplier = supplieraffiliate.idSupplier', 'inner');
        $this->db->join('invoices', 'invoices.pCode = supplier.idSupplier', 'LEFT');

        $this->db->where('supplier.archived', 0);
        if( isset( $params['idAffiliate'] )) $this->db->where('supplieraffiliate.idAffiliate', $params['idAffiliate']);

        $this->db->order_by('name asc');
        return $this->db->get()->result_array();
    }

    public function getLocationCmb( $params ){
        $this->db->select( 'idLocation as id, locationName as name' );
        if( isset( $params['query'] ) ){
            $this->db->like( 'locationName', $params['query'], 'both' );
        }
        $this->db->order_by( 'locationName asc' );
        return $this->db->get( 'location' )->result_array();
    }

    public function getSupplierCmb( $params ){
        $this->db->select( 'idSupplier as id, name, sk' );
        if( isset( $params['query'] ) ){
            $this->db->like( 'name', $params['query'], 'both' );
        }
        $this->db->order_by( 'name asc' );
        return $this->db->get( 'supplier' )->result_array();
    }

    public function getItems( $params ){
        if( isset( $params['idAffiliate'] ) ) {
            $this->db->distinct();

            if( isset( $params['idInvoice'] ) ) {
                $this->db->select('
                        po.idItem, 
                        item.barcode, 
                        item.itemName, 
                        item.idItemClass, 
                        itemclassification.className, 
                        unit.unitName, 
                        po.cost, 
                        po.qty, 
                        (po.cost * po.qty ) as amount,
                        item.sk');

                $this->db->join('po', 'po.idItem = item.idItem', 'INNER');
                $this->db->where('po.idInvoice', $params['idInvoice']);
            } else {
                $this->db->select('
                        item.idItem, 
                        item.barcode, 
                        item.itemName, 
                        item.idItemClass,
                        itemclassification.className, 
                        unit.unitName, 
                        item.itemPrice as cost, 
                        0 as qty, 
                        (item.itemPrice * 0 ) as amount,
                        item.sk');

                if( isset( $params['pCode'] ) && isset( $params['onGrid'] ) ) {
                    $this->db->join('supplieritems', 'supplieritems.idItem = item.idItem', 'LEFT');
                    $this->db->join('supplier', 'supplier.idSupplier = supplieritems.idSupplier', 'LEFT');
                    $this->db->where('supplier.idSupplier', $params['pCode']);
                }
            }

            $this->db->join('itemaffiliate', 'itemaffiliate.idItem = item.idItem', 'INNER');
            $this->db->join('itemclassification', 'itemclassification.idItemClass = item.idItemClass', 'LEFT');
            $this->db->join('unit', 'unit.idUnit = item.idUnit', 'LEFT');
            $this->db->where( array( 'itemaffiliate.idAffiliate' => $this->session->userdata('AFFILIATEID'), 'item.archived' => 0 ));


            $this->db->order_by('barcode asc');
            return $this->db->get('item')->result_array();
        }
    }

    public function getItemsCombo( $params ){
        $this->db->select( '
            item.idItem
            ,item.barcode
            ,item.itemName
            ,item.idItemClass
            ,item.idUnit
            ,itemclassification.className
            ,unit.unitName
            ,item.itemPrice
            ,item.salesGlAcc
            ,item.inventoryGlAcc
            ,item.costofsalesGlAcc
            ,coaSales.acod_c15 as salesGlAccCode
            ,coaSales.aname_c30 as salesGlAccName
            ,coaInventory.acod_c15 as inventoryGlAccCode
            ,coaInventory.aname_c30 as inventoryGlAccName
            ,coaCostofsales.acod_c15 as costofsalesGlAccCode
            ,coaCostofsales.aname_c30 as costofsalesGlAccName
        ' );
        $this->db->from( 'item' );
        $this->db->join( 'itemclassification', 'itemclassification.idItemClass = item.idItemClass', 'left outer' );
        $this->db->join( 'unit', 'unit.idUnit = item.idUnit', 'left outer' );
        $this->db->join( 'coa as coaSales', 'coaSales.idCoa = item.salesGlAcc', 'left outer' );
        $this->db->join( 'coa as coaInventory', 'coaInventory.idCoa = item.inventoryGlAcc', 'left outer' );
        $this->db->join( 'coa as coaCostofsales', 'coaCostofsales.idCoa = item.costofsalesGlAcc', 'left outer' );
        
        if( isset( $params['idAffiliate'] ) ){
            if( (int)$params['idAffiliate'] > 0 ) $this->db->where( "item.idItem IN( SELECT idItem FROM itemaffiliate WHERE idAffiliate = $params[idAffiliate] )" );
            else $this->db->where( "item.idItem IN( SELECT idItem FROM itemaffiliate JOIN employeeaffiliate ON( employeeaffiliate.idAffiliate = itemaffiliate.idAffiliate ) WHERE employeeaffiliate.idEmployee = $this->EMPLOYEEID )" );
        }
        else $this->db->where( "item.idItem IN( SELECT idItem FROM itemaffiliate LEFT JOIN employeeaffiliate ON( employeeaffiliate.idAffiliate = itemaffiliate.idAffiliate ) WHERE employeeaffiliate.idEmployee = $this->EMPLOYEEID )" );

        if( isset( $params['query'] ) ){
            $this->db->like( 'item.' . $params['displayField'], $params['query'], 'both' );
        }

        $this->db->where_not_in( 'item.archived', 1 );

        $params['db'] = $this->db;
        $params['order_by'] = 'item.' . $params['displayField'] . ' asc';

        $params['limit']    = $params['start'] + $params['limit'];
        $params['start']    = 0;

        return getGridList($params);
    }

    public function getCOACombo( $params ){
        $this->db->select( 'idCoa, acod_c15, aname_c30, mocod_c1, chcod_c1, accod_c2' );
        $this->db->from( 'coa' );
        $this->db->where_not_in( 'archived', 1 );
        if( isset( $params['idAffiliate'] ) ){
            if( (int)$params['idAffiliate'] > 0 ) $this->db->where( "idCoa IN( SELECT idCoa FROM coaaffiliate WHERE idAffiliate = $params[idAffiliate] )" );
            else $this->db->where( "idCoa IN( SELECT idCoa FROM coaaffiliate JOIN employeeaffiliate ON( employeeaffiliate.idAffiliate = coaaffiliate.idAffiliate ) WHERE employeeaffiliate.idEmployee = $this->EMPLOYEEID  )" );
        }
        else $this->db->where( "idCoa IN( SELECT idCoa FROM coaaffiliate JOIN employeeaffiliate ON( employeeaffiliate.idAffiliate = coaaffiliate.idAffiliate ) WHERE employeeaffiliate.idEmployee = $this->EMPLOYEEID  )" );

        if( isset( $params['query'] ) ){
            $this->db->like( $params['displayField'], $params['query'], 'both' );
        }

        if( isset( $params['isHeader'] ) ){
            if( (int)$params['isHeader'] == 1 ) $this->db->where( 'accountType', $params['isHeader'] );
            if( (int)$params['isHeader'] == 2 ){
                $mocod_c1 = ( isset( $params['mocod_c1'] )? $params['mocod_c1'] : 0 );
                $chcod_c1 = ( isset( $params['chcod_c1'] )? $params['chcod_c1'] : 0 );
                $accod_c2 = ( isset( $params['accod_c2'] )? $params['accod_c2'] : 0 );
                $this->db->where( 'mocod_c1', $mocod_c1 );
                $this->db->where( 'chcod_c1', $chcod_c1 );
                $this->db->where( 'accod_c2', $accod_c2 );
            }
        }

        $params['db'] = $this->db;
        $params['order_by'] = $params['displayField'] . ' asc';

        $params['limit']    = $params['start'] + $params['limit'];
        $params['start']    = 0;

        return getGridList($params);
    }

    public function getRecord( $params ){
        $this->db->select('*');
        $this->db->where( $params['id'], $params['value'] );
        return $this->db->get($params['tableName'])->row();
    }

    public function getEntries( $params ){
        if( empty($params['query']) ) die('query is required.');

        $this->db->select( $params['query'] );
        if( isset( $params['idSupplier']) ) $this->db->join('supplier', 'supplier.idSupplier = ' . $params['idSupplier'], 'left');
        if( isset( $params['idCustomer']) ) $this->db->join('customer', 'customer.idCustomer = ' . $params['idCustomer'], 'left');

        return $this->db->get($params['default_table'])->row();
    }

    public function getEntryDetails( $params ){
        if( empty($params['idCoa']) || !isset( $params['idCoa'])) die('idCoa is required.');
        if( !isset( $params['balance'] ) ) die('balance is required');
        if( !isset( $params['account'] ) ) die('account is required');

        $this->db->select('
                            idCoa, 
                            acod_c15 as code, 
                            aname_c30 as name,' 
                            . ( $params['balance'] == 0 ? ' 1 as debit, 0 as credit, "' : ' 0 as debit, 1 as credit, "')
                            . $params['account'] . '" as account');
        $this->db->where('idCoa', $params['idCoa']);
        return $this->db->get('coa')->row();
    }

    public function getItemEntries( $params ){
        return $this->db->query( "SELECT idItem, idCoa, code, name, debit, credit, account FROM (" . $params['selectQuery'] . ") as main WHERE idItem in (" . join(", ", $params['items']) . ")" )->result_array();
    }

    public function getAffiliates( $params ){
        $this->db->select("idAffiliate
                            ,idAffiliate as id
                            ,affiliateName
                            ,affiliateName as name
                            ,sk");
        $this->db->where('archived', 0);

        $this->db->from('affiliate');
        $params['db'] = $this->db;
        $params['order_by'] = 'affiliateName asc';
        return getGridList($params);
    }

    public function getApprovers(){
        $this->db->select('*');
        $this->db->where('idAffiliate', $this->session->userdata('AFFILIATEID'));
        return $this->db->get('affiliateapprover')->result_array();
    }

    /*
     * Added by makmak
     * this.f(x) checks if a transaction period is closed for this month 
     * */
	public function checkIf_journal_isClosed( $params )
    {
        $invMonth   = date( 'm' , strtotime( $params['tdate'] ) );
        $invYear    = date( 'Y' , strtotime( $params['tdate'] ) );
        
        return $this->db->select( "count(*) as count" )
        ->where_not_in( 'archived'  , 1 )
        ->where( 'idModule'         , 35 )
        ->where( 'month'            , (int)$invMonth )
        ->where( 'year'             , (int)$invYear )
        ->where( 'idAffiliate'      , (int)$params['idAffiliate'] )
        ->get( "invoices" )->row()->count; 
	}

}