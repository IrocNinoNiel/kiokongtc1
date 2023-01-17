<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Jays
 * Module       : Receivable Balances, Ledger and SOA
 * Date         : Feb. 12, 2020
 * Finished     : 
 * Description  : his module allows authorized user to generate and print the balances, 
 *                ledger and statement of account per customer on a specified date range.
 * DB Tables    : 
 * */ 
class Receivablebalanceledger_model extends CI_Model{

    public function getSalesReference( $params ){
        $addWhere = '';
        $this->db->select( "idReference as id, CONCAT( code, ' - ', name ) as name" );
        $this->db->where( 'idModule', 18 );
        if( isset( $params['idAffiliate'] ) ){
            if( (int)$params['idAffiliate'] > 0 ){
                $addWhere = "idReference IN( SELECT idReference FROM referenceaffiliate WHERE idAffiliate = " . (int)$params['idAffiliate'] . " )";
            }
        }
        $this->db->where( ( $addWhere != ''? $addWhere : "idReference IN(
            SELECT idReference FROM referenceaffiliate WHERE idAffiliate IN(
                SELECT idAffiliate FROM employeeaffiliate WHERE idEmployee = $this->EMPLOYEEID
            )
        )" ) );
        if( isset( $params['query'] ) ){
            $this->db->like( "CONCAT( code, ' - ', name )", $params['query'], 'both' );
        }
        $this->db->order_by( 'code ASC, name ASC' );
        return $this->db->get( 'reference' )->result_array();
    }

    public function getCustomers( $params ){
        $addWhere = '';
        $this->db->select( 'idCustomer as id, name, sk' );
        if( isset( $params['query'] ) ){
            $this->db->like( 'name', $params['query'], 'both' );
        }
        if( isset( $params['idAffiliate'] ) ){
            if( (int)$params['idAffiliate'] > 0 ){
                $addWhere = "idCustomer IN( SELECT idCustomer FROM customeraffiliate WHERE idAffiliate = " . (int)$params['idAffiliate'] . " )";
            }
        }
        $this->db->where( ( $addWhere != ''? $addWhere : "idCustomer IN(
            SELECT idCustomer FROM customeraffiliate WHERE idAffiliate IN(
                SELECT idAffiliate FROM employeeaffiliate WHERE idEmployee = $this->EMPLOYEEID
            )
        )" ) );
        if( isset( $params['query'] ) ){
            $this->db->like( 'name', $params['query'], 'both' );
        }
        $this->db->order_by( 'name ASC' );
        return $this->db->get( 'customer' )->result_array();
    }

    public function getReceivableBalances( $params ){
        $addWhere = '';
        $addWhere1 = '';
        $addWhere2 = '';
        if( isset( $params['idAffiliate'] ) ){
            if( (int)$params['idAffiliate'] > 0 ) $addWhere .= ( $addWhere != ''? " AND main.idAffiliate = $params[idAffiliate]" : " WHERE main.idAffiliate = $params[idAffiliate] " );
        }
        if( isset( $params['idReference'] ) ){
            if( (int)$params['idReference'] > 0 ){
                $addWhere1 .= " AND invoices.idReference = $params[idReference] ";
                $addWhere2 .= " AND salesinvoice.idReference = $params[idReference] ";
            }
        }
        if( isset( $params['idCustomer'] ) ){
            if( (int)$params['idCustomer'] > 0 ){
                $addWhere .= ( $addWhere != ''? " AND customer.idCustomer = $params[idCustomer] " : " WHERE customer.idCustomer = $params[idCustomer] " );
            }
        }
        if( (int)$params['hideNoBalance'] > 0 ){
            $addWhere .= ( $addWhere != ''? " AND ( IFNULL( main.chargesAmt, 0 ) - IFNULL( main.paymentAmt, 0 ) ) > 0 " : " WHERE ( IFNULL( main.chargesAmt, 0 ) - IFNULL( main.paymentAmt, 0 ) ) > 0 " );
        }
        return $this->db->query( "
            SELECT
                affiliate.affiliateName
                ,affiliate.idAffiliate
                ,customer.idCustomer
                ,customer.name as customerName
                ,customer.email
                ,customer.sk AS custSK
                ,affiliate.sk AS affSK
                ,IFNULL( main.chargesAmt, 0 ) as chargesAmt
                ,IFNULL( main.paymentAmt, 0 ) as paymentAmt
                ,( IFNULL( main.chargesAmt, 0 ) - IFNULL( main.paymentAmt, 0 ) ) as balanceAmt
            FROM(
                SELECT
                    invoices.idAffiliate
                    ,invoices.pCode
                    ,SUM( IFNULL( invoices.amount, 0 ) ) as chargesAmt
                    ,( IFNULL( paymentinvoice.payments, 0 ) + SUM( IFNULL( invoices.downPayment, 0 ) ) ) as paymentAmt
                FROM invoices
                LEFT OUTER JOIN(
                    SELECT
                        SUM( IFNULL( receipts.amount, 0 ) ) as payments
                        ,salesinvoice.idAffiliate
                        ,salesinvoice.pCode
                    FROM receipts
                    JOIN invoices
                        ON( invoices.idInvoice = receipts.idInvoice )
                    JOIN invoices as salesinvoice
                        ON( salesinvoice.idInvoice = receipts.fident AND salesinvoice.idModule = receipts.fIDModule )
                    WHERE
                        invoices.idModule = 28
                            AND invoices.pType = 1
                                AND invoices.status = 2
                                    AND DATE_FORMAT( invoices.date, '%Y-%m-%d' ) <= '$params[date]'
                                        $addWhere2
                                            AND invoices.archived NOT IN( 1 )
                                                AND invoices.cancelTag NOT IN( 1 )
                    GROUP BY salesinvoice.idAffiliate, salesinvoice.pCode
                ) as paymentinvoice
                    ON ( paymentinvoice.idAffiliate =  invoices.idAffiliate
                        AND paymentinvoice.pCode = invoices.pCode )
                WHERE invoices.pType = 1
                    AND invoices.status = 2
                        AND invoices.idModule IN( 18, 58 )
                            AND DATE_FORMAT( invoices.date, '%Y-%m-%d' ) <= '$params[date]'
                                $addWhere1
                                    AND invoices.archived NOT IN( 1 )
                                        AND invoices.cancelTag NOT IN( 1 )
                GROUP BY invoices.idAffiliate, invoices.pCode, paymentinvoice.payments
            ) as main
            LEFT OUTER JOIN affiliate
                ON( affiliate.idAffiliate = main.idAffiliate )
            JOIN customer
                ON( customer.idCustomer = main.pCode )
            $addWhere
        " )->result_array();
    }

    public function getReceivableLedger( $params ){
        $addWhere = '';
        return $this->db->query("
            SELECT
                DATE_FORMAT( main.date, '%m/%d/%Y' ) as date
                ,main.reference
                ,main.remarks
                ,IFNULL( main.chargesAmt, 0 ) as chargesAmt
                ,IFNULL( main.paymentAmt, 01 ) as paymentAmt
                ,( @runningBal := @runningBal + ( IFNULL( main.chargesAmt, 0 ) - IFNULL( main.paymentAmt, 0 ) ) ) as balanceAmt
                ,main.idModule
            FROM(
                -- Beginning Balance
                SELECT
                    '' as date
                    ,'' as reference
                    ,'Beginning Balance' as remarks
                    ,(
                        SELECT
                            ( SUM( IFNULL( invoices.amount, 0 ) ) - ( IFNULL( paymentinvoice.payments, 0 ) + SUM( IFNULL( invoices.downPayment, 0 ) ) ) )
                        FROM invoices
                        LEFT OUTER JOIN(
                            SELECT
                                SUM( IFNULL( receipts.amount, 0 ) ) as payments
                                ,salesinvoice.idAffiliate
                                ,salesinvoice.pCode
                            FROM receipts
                            JOIN invoices
                                ON( invoices.idInvoice = receipts.idInvoice )
                            JOIN invoices as salesinvoice
                                ON( salesinvoice.idInvoice = receipts.fident AND salesinvoice.idModule = receipts.fIDModule )
                            WHERE
                                invoices.idModule = 28
                                    AND invoices.pType = 1
                                        AND invoices.status = 2
                                            AND DATE_FORMAT( invoices.date, '%Y-%m-%d' ) <= '$params[sdate]'
                                                AND invoices.pCode = $params[idCustomer]
                                                    AND invoices.archived NOT IN( 1 )
                                                        AND invoices.idAffiliate = $params[idAffiliate]
                                                            AND invoices.cancelTag NOT IN( 1 )
                            GROUP BY salesinvoice.idAffiliate, salesinvoice.pCode
                        ) as paymentinvoice
                            ON ( paymentinvoice.idAffiliate =  invoices.idAffiliate
                                AND paymentinvoice.pCode = invoices.pCode )
                        WHERE invoices.pType = 1
                            AND invoices.status = 2
                                AND invoices.idModule IN( 18, 58 )
                                    AND DATE_FORMAT( invoices.date, '%Y-%m-%d' ) <= '$params[sdate]'
                                        AND invoices.pCode = $params[idCustomer]
                                            AND invoices.archived NOT IN( 1 )
                                                AND invoices.idAffiliate = $params[idAffiliate]
                                                    AND invoices.cancelTag NOT IN( 1 )
                        GROUP BY invoices.idAffiliate, invoices.pCode, paymentinvoice.payments
                    ) as chargesAmt
                    ,0 as paymentAmt
                    ,0 as sorter
                    ,0 as idModule
                
                UNION ALL

                -- Charges
                SELECT
                    invoices.date
                    ,CONCAT( reference.code, ' - ', invoices.referenceNum ) as reference
                    ,invoices.remarks
                    ,IFNULL( invoices.amount, 10 ) as chargesAmt
                    ,0 as paymentAmt
                    ,1 as sorter
                    ,invoices.idModule
                FROM invoices
                LEFT OUTER JOIN reference
                    ON( reference.idReference = invoices.idReference )
                WHERE
                    invoices.idModule = 18
                        AND invoices.pType = 1
                            AND invoices.status = 2
                                AND ( DATE_FORMAT( invoices.date, '%Y-%m-%d' ) BETWEEN '$params[sdate]' AND '$params[edate]' )
                                    AND invoices.archived NOT IN( 1 )
                                        AND invoices.pCode = $params[idCustomer]
                                            AND invoices.archived NOT IN( 1 )
                                                AND invoices.cancelTag NOT IN( 1 )
                
                UNION ALL

                -- Downpayment
                SELECT
                    invoices.date
                    ,CONCAT( reference.code, ' - ', invoices.referenceNum, ' ( Downpayment ) ' ) as reference
                    ,invoices.remarks
                    ,0 as chargesAmt
                    ,IFNULL( invoices.downPayment, 10 ) as paymentAmt
                    ,2 as sorter
                    ,invoices.idModule
                FROM invoices
                LEFT OUTER JOIN reference
                    ON( reference.idReference = invoices.idReference )
                WHERE
                    invoices.idModule IN( 18, 58 )
                        AND invoices.pType = 1
                            AND invoices.status = 2
                                AND ( DATE_FORMAT( invoices.date, '%Y-%m-%d' ) BETWEEN '$params[sdate]' AND '$params[edate]' )
                                    AND invoices.archived NOT IN( 1 )
                                        AND invoices.pCode = $params[idCustomer]
                                            AND IFNULL( invoices.downPayment, 10 ) > 0
                                                AND invoices.archived NOT IN( 1 )
                                                    AND invoices.cancelTag NOT IN( 1 )
                   
                UNION ALL
                
                -- Receipts( Payments )
                SELECT
                    invoices.date
                    ,CONCAT( reference.code, ' - ', invoices.referenceNum ) as reference
                    ,invoices.remarks
                    ,0 as chargesAmt
                    ,IFNULL( receipts.amount, 0 ) as paymentAmt
                    ,3 as sorter
                    ,invoices.idModule
                FROM invoices
                LEFT OUTER JOIN reference
                    ON( reference.idReference = invoices.idReference )
                JOIN receipts
                    ON( receipts.idInvoice = invoices.idInvoice )
                WHERE
                    invoices.idModule = 28
                        AND invoices.pType = 1
                            AND invoices.status = 2
                                AND ( DATE_FORMAT( invoices.date, '%Y-%m-%d' ) BETWEEN '$params[sdate]' AND '$params[edate]' )
                                    AND invoices.archived NOT IN( 1 )
                                        AND invoices.pCode = $params[idCustomer]
                                            AND invoices.cancelTag NOT IN( 1 )

            ) as main
            JOIN ( SELECT @runningBal := 0 ) as runningBalance
                ON( 1 = 1 )
            ORDER BY main.date ASC, main.sorter ASC
        ")->result_array();
    }

}