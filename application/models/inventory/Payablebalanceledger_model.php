<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Jayson Dagulo
 * Module: Payable Balances and Ledger
 * Date: Jan. 17, 2020
 * Finished: 
 * Description: This module allows authorized user to generate and print the payable balances and its ledger for every supplier.
 * */ 
class Payablebalanceledger_model extends CI_Model {
    
    public function getBalances( $params ){
        $addWhere1 = "";
        $addWhere2 = "";
        if( (int)$params['idAffiliate'] > 0 ) $addWhere1 .= " AND invoices.idAffiliate = " . (int)$params['idAffiliate'];
        if( (int)$params['idSupplier'] > 0 ) $addWhere1 .= " AND invoices.pCode = " . (int)$params['idSupplier'];
        if( (int)$params['hideNoBalances'] == 1 ) $addWhere2 .= " WHERE main.balanceAmt > 0";
        return $this->db->query("
            SELECT
                affiliate.affiliateName
                ,affiliate.sk as affiliateSK
                ,main.idAffiliate
                ,supplier.name as supplierName
                ,supplier.sk as supplierSK
                ,main.idSupplier
                ,main.chargesAmt
                ,main.paymentsAmt
                ,main.balanceAmt
            FROM(
                SELECT
                    invoices.idAffiliate
                    ,invoices.pCode as idSupplier
                    ,( SUM( IFNULL( invoices.bal, 0 ) ) + SUM( IFNULL( invoices.downPayment, 0 ) ) ) as chargesAmt
                    ,( SUM( IFNULL( disbursements.payment, 0 ) ) + SUM( IFNULL( invoices.downPayment, 0 ) ) ) as paymentsAmt
                    ,( SUM( IFNULL( invoices.amount, 0 ) ) - ( SUM( IFNULL( disbursements.payment, 0 ) ) + SUM( IFNULL( invoices.downPayment, 0 ) ) ) ) as balanceAmt
                FROM invoices
                LEFT OUTER JOIN(
                    SELECT
                        SUM( IFNULL( disbursements.paid, 0 ) ) as payment
                        ,disbursements.fident
                    FROM disbursements
                    GROUP BY disbursements.fident
                ) as disbursements
                    ON( disbursements.fident = invoices.idInvoice )
                WHERE invoices.idModule IN( 25, 57 ) AND
                    invoices.status = 2
                        AND invoices.pType = 2
                            AND convert(date, date) <= '$params[date]'
                                AND invoices.idAffiliate IN( 
                                    SELECT
                                        idAffiliate
                                    FROM employeeaffiliate
                                    JOIN eu ON( employeeaffiliate.idEmployee = eu.idEmployee )
                                    WHERE idEu = $this->USERID
                                )
                                    AND invoices.archived NOT IN( 1 )
                                    $addWhere1
                GROUP BY invoices.idAffiliate, invoices.pCode
            ) as main
            LEFT OUTER JOIN affiliate
                ON( affiliate.idAffiliate = main.idAffiliate )
            LEFT OUTER JOIN supplier
                ON( supplier.idSupplier = main.idSupplier )
            $addWhere2
            ORDER BY affiliate.affiliateName, supplier.name
        ")->result_array();
    }

    public function getLedger( $params ){
        return $this->db->query("
            SELECT
                DATE_FORMAT( main.date, '%m/%d/%Y' ) as date
                ,main.reference
                ,main.paymentType
                ,main.particulars
                ,main.amt
                ,main.payments
                ,( @runningBal := @runningBal + ( IFNULL( main.amt, 0 ) - IFNULL( main.payments, 0 ) ) ) as balance
                ,main.idModule
                ,main.idInvoice
            FROM(
                -- Beginning Balance
                SELECT
                    '' as date
                    ,'' as reference
                    ,'' as paymentType
                    ,'Beginning Balance' as particulars
                    ,(
                        SELECT
                            ( SUM( IFNULL( invoices.bal, 0 ) ) -  SUM( IFNULL( disbursements.payment, 0 ) ) )as amt
                        FROM invoices
                        LEFT OUTER JOIN(
                            SELECT
                                SUM( IFNULL( disbursements.paid, 0 ) ) as payment
                                ,disbursements.fident
                            FROM disbursements
                            GROUP BY disbursements.fident
                        ) as disbursements
                            ON( disbursements.fident = invoices.idInvoice )
                        WHERE invoices.idModule IN( 25, 57 )
                            AND invoices.status = 2
                                AND invoices.pType = 2
                                    AND date < '$params[sdate]'
                                        AND invoices.idAffiliate IN( 
                                            SELECT
                                                idAffiliate
                                            FROM employeeaffiliate
                                            JOIN eu ON( employeeaffiliate.idEmployee = eu.idEmployee )
                                            WHERE idEu = $this->USERID
                                        )
                                            AND invoices.idAffiliate = $params[idAffiliate]
                                                    AND invoices.pCode = $params[idSupplier]
                                                        AND invoices.archived NOT IN( 1 )
                        GROUP BY invoices.idAffiliate, invoices.pCode
                    ) as amt
                    ,0 as payments
                    ,0 as sorter
                    ,0 as idModule
                    ,0 as idInvoice

                UNION ALL
                
                -- Charges
                SELECT
                    invoices.date
                    ,CONCAT( reference.code, ' - ', invoices.referenceNum ) as reference
                    ,(CASE
                        WHEN invoices.payMode = 1 THEN 'Cash'
                        WHEN invoices.payMode = 2 THEN 'Charge'
                        ELSE ''
                    END) as paymentType
                    ,invoices.remarks as particulars
                    ,IFNULL( invoices.bal, 0 ) + IFNULL( invoices.downPayment, 0 ) as amt
                    ,0 as payments
                    ,1 as sorter
                    ,invoices.idModule
                    ,invoices.idInvoice
                FROM invoices
                LEFT OUTER JOIN reference
                    ON( reference.idReference = invoices.idReference )
                WHERE
                    invoices.idModule IN( 25, 57 )
                        AND invoices.status = 2
                            AND invoices.pType = 2
                                AND invoices.idAffiliate = $params[idAffiliate]
                                        AND invoices.pCode = $params[idSupplier]
                                            AND convert(invoices.date, date) >= '$params[sdate]'
                                                AND convert(invoices.date, date ) <= '$params[edate]'
                                                    AND invoices.archived NOT IN( 1 )
                
                UNION ALL

                -- Downpayment
                SELECT
                    invoices.date
                    ,CONCAT( reference.code, ' - ', invoices.referenceNum, ' ( Downpayment ) ' ) as reference
                    ,(CASE
                        WHEN invoices.payMode = 1 THEN 'Cash'
                        WHEN invoices.payMode = 2 THEN 'Charge'
                        ELSE ''
                    END) as paymentType
                    ,invoices.remarks as particulars
                    ,0 as amt
                    ,IFNULL( invoices.downPayment, 0 ) as payments
                    ,2 as sorter
                    ,invoices.idModule
                    ,invoices.idInvoice
                FROM invoices
                LEFT OUTER JOIN reference
                    ON( reference.idReference = invoices.idReference )
                WHERE
                    invoices.idModule IN( 25, 57 )
                        AND invoices.status = 2
                            AND invoices.pType = 2
                                AND invoices.idAffiliate = $params[idAffiliate]
                                        AND invoices.pCode = $params[idSupplier]
                                            AND convert(invoices.date, date) >= '$params[sdate]'
                                                AND convert(invoices.date, date ) <= '$params[edate]'
                                                    AND IFNULL( invoices.downPayment, 0 ) > 0
                                                        AND invoices.archived NOT IN( 1 )
                
                -- UNION ALL
                -- Get disbursement records( serves as payments for receiving records - to be completed with disbursement module is created. )
            ) as main
            JOIN ( SELECT @runningBal := 0 ) as runningBalance
                ON( 1 = 1 )
            ORDER BY main.date ASC, sorter ASC
        ")->result_array();
    }

}