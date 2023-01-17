<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Jayson Dagulo
 * Module       : General and Subsidiary Ledger
 * Date         : Jan. 30, 2020
 * Finished     : 
 * Description  : This module allows authorized users to set the accounting defaults of every transaction module.
 * DB Tables    : 
 * */ 

class Generalsubsidiaryledger_model extends CI_Model {

    public function getAffiliateDetails( $params ){
        $this->db->select( "accSchedule, month, dateStart" );
        $this->db->where( 'idAffiliate', $params['idAffiliate'] );
        return $this->db->get( 'affiliate' )->row_array();
    }

    public function getGeneralLedger( $params ){
        $exWhere = '';
        if( (int)$params['hideZero'] == 1 ) $exWhere .= "AND (CASE
            WHEN coa.norm_c2 = 'DR' THEN ( IFNULL( beginningRec.yearBeginning, 0 ) + IFNULL( monthDRCR.debit, 0 ) ) - IFNULL( monthDRCR.credit, 0 )
            ELSE ( IFNULL( beginningRec.yearBeginning, 0 ) + IFNULL( monthDRCR.credit, 0 ) ) - IFNULL( monthDRCR.debit, 0 )
        END) <> 0";
        return $this->db->query( "
            SELECT
                coa.idCoa
                ,coa.acod_c15
                ,coa.aname_c30
                ,coaHeader.idCoa as idCoaHeader
                ,coaHeader.aname_c30 as aname_c30Header
                ,IFNULL( beginningRec.yearBeginning, 0 ) as yearBegBal
                ,IFNULL( monthDRCR.debit, 0 ) as monthDR
                ,IFNULL( monthDRCR.credit, 0 ) as monthCR
                ,IFNULL( yearAccumulated.yearDR, 0 ) as yearDR
                ,IFNULL( yearAccumulated.yearCR, 0 ) as yearCR
                ,(CASE
                    WHEN coa.norm_c2 = 'DR' THEN ( IFNULL( beginningRec.yearBeginning, 0 ) + IFNULL( monthDRCR.debit, 0 ) ) - IFNULL( monthDRCR.credit, 0 )
                    ELSE ( IFNULL( beginningRec.yearBeginning, 0 ) + IFNULL( monthDRCR.credit, 0 ) ) - IFNULL( monthDRCR.debit, 0 )
                END) as balance
            FROM coa
            LEFT OUTER JOIN( -- Beginning Balance
                SELECT
                    gl.idCoa
                    ,gl.glAmount as yearBeginning
                FROM gl
                JOIN invoices
                    ON( invoices.idInvoice = gl.idInvoice )
                WHERE
                    gl.idAffiliate = $params[idAffiliate]
                        AND invoices.archived NOT IN( 1 )
                            AND gl.glYear = $params[prevyear]
                                AND gl.month = $params[monthEnd]
            ) as beginningRec
                ON( beginningRec.idCoa = coa.idCoa )
            LEFT OUTER JOIN( -- get Month record
                SELECT
                    gl.idCoa
                    ,gl.debit
                    ,gl.credit
                FROM gl
                JOIN invoices
                    ON( invoices.idInvoice = gl.idInvoice )
                WHERE
                    gl.idAffiliate = $params[idAffiliate]
                        AND invoices.archived NOT IN( 1 )
                            AND gl.glYear = $params[year]
                                AND gl.month = $params[month]
            ) as monthDRCR
                ON( monthDRCR.idCoa = coa.idCoa )
            LEFT OUTER JOIN( -- get year accumulated
                SELECT
                    gl.idCoa
                    ,SUM( IFNULL( gl.debit, 0 ) ) as yearDR
                    ,SUM( IFNULL( gl.credit, 0 ) ) as yearCR
                FROM gl
                JOIN invoices
                    ON( invoices.idInvoice = gl.idInvoice )
                WHERE
                    gl.idAffiliate = $params[idAffiliate]
                        AND invoices.archived NOT IN( 1 )
                            AND ( CAST( CONCAT( gl.glYear, '-', gl.month, '-01' ) AS DATE ) BETWEEN '$params[dateStart]' AND '$params[dateEnd]' )
                GROUP BY gl.idCoa
            ) as yearAccumulated
                ON( yearAccumulated.idCoa = coa.idCoa )
            JOIN coaaffiliate
                ON( coaaffiliate.idCoa = coa.idCoa )
            LEFT OUTER JOIN coa as coaHeader
                ON( coaHeader.mocod_c1 = coa.mocod_c1 AND coaHeader.chcod_c1 = coa.chcod_c1 AND coaHeader.accod_c2 = coa.accod_c2 AND coaHeader.accountType = 1 )
            WHERE coaaffiliate.idAffiliate = $params[idAffiliate]
                $exWhere
            ORDER BY coa.idCoa
        " )->result_array();
    }

    public function getHeaderDetails( $params ){
        $this->db->select( 'mocod_c1, chcod_c1, accod_c2' );
        $this->db->where( 'idCoa', $params['idCoaHeader'] );
        return $this->db->get( 'coa' )->row_array();
    }

    public function getSubsidiaryLedger( $params ){
        $exwhere = '';
        if( (int)$params['idCoaSubsidiary'] > 0 ){
            $exwhere .= " AND posting.idCoa = $params[idCoaSubsidiary]";
        }
        else if( (int)$params['idCoaSubsidiary'] == 0 ){
            $exwhere .= " AND coa.mocod_c1 = $params[mocod_c1] AND coa.chcod_c1 = $params[chcod_c1] AND coa.accod_c2 = $params[accod_c2]";
        }
        return $this->db->query( "
            SELECT
                main.date
                ,main.reference
                ,main.acod_c15
                ,main.aname_c30
                ,main.description
                ,IFNULL( main.debit, 0 ) as debit
                ,IFNULL( main.credit, 0 ) as credit
                ,(CASE
                    WHEN main.norm_c2 = 'DR' then @runningBal := @runningBal + ( IFNULL( main.debit, 0 ) - IFNULL( main.credit, 0 ) )
                    ELSE @runningBal := @runningBal + ( IFNULL( main.credit, 0 ) + IFNULL( main.debit, 0 ) )
                END) as runningBalance
                ,main.idModule
                ,main.idInvoice
                ,main.idBankRecon
                ,main.idAccBegBal
            FROM(
                -- get beginning balance
                (
                    SELECT
                        '' as date
                        ,'' as reference
                        ,'' as acod_c15
                        ,'' as aname_c30
                        ,'Beginning Balance' as description
                        ,(CASE
                            WHEN coa.norm_c2 = 'DR' THEN ( SUM( IFNULL( posting.debit, 0 ) ) - SUM( IFNULL( posting.credit, 0 ) ) )
                            ELSE 0
                        END) as debit
                        ,(CASE
                            WHEN coa.norm_c2 = 'CR' THEN ( SUM( IFNULL( posting.credit, 0 ) ) - SUM( IFNULL( posting.debit, 0 ) ) )
                            ELSE 0
                        END) as credit
                        ,coa.mocod_c1
                        ,coa.chcod_c1
                        ,coa.accod_c2
                        ,1 as sorter
                        ,'' as norm_c2
                        ,0 as idModule
                        ,0 as idInvoice
                        ,0 as idBankRecon
                        ,0 as idAccBegBal
                    FROM posting
                    LEFT OUTER JOIN invoices
                        ON( invoices.idInvoice = posting.idInvoice )
                    LEFT OUTER JOIN bankrecon
                        ON( bankrecon.idBankRecon = posting.idBankRecon )
                    LEFT OUTER JOIN accountbegbal
                        ON( accountbegbal.idAccBegBal = posting.idAccBegBal )
                    LEFT OUTER JOIN coa
                        ON( coa.idCoa = posting.idCoa )
                    WHERE
                        -- make sure it is filtered by affiliate
                        ( invoices.idAffiliate = $params[idAffiliate] OR bankrecon.idAffiliate = $params[idAffiliate] OR accountbegbal.idAffiliate = $params[idAffiliate] )
                        AND
                        -- from invoices make sure that it is all approved transaction, not tagged as cancel and not coming from closing entry module
                        -- from bankrecon just make sure its approved status
                            (
                                ( invoices.status = 2 AND invoices.cancelTag NOT IN( 1 ) AND invoices.idModule NOT IN( 35 ) )
                                OR ( bankrecon.status = 2 AND bankrecon.cancelTag NOT IN( 1 ) )
                                OR IFNULL( accountbegbal.idAccBegBal, 0 ) > 0
                            )
                        AND
                        -- make sure record is not deleted
                            ( invoices.archived NOT IN( 1 ) OR bankrecon.archived NOT IN( 1 ) )
                        AND
                        -- filter transaction date on selected month
                        ( 
                            invoices.date < '$params[sdate]'
                            OR bankrecon.reconDate < '$params[sdate]'
                            OR accountbegbal.date < '$params[sdate]'
                        )
                        $exwhere
                    GROUP BY coa.mocod_c1, coa.chcod_c1, coa.accod_c2
                )

                UNION ALL
                -- retrieve posting records
                (
                    SELECT
                        (CASE
                            WHEN IFNULL( posting.idInvoice, 0 ) > 0 THEN invoices.date
                            WHEN IFNULL( posting.idBankRecon, 0 ) > 0 THEN bankrecon.reconDate
                            WHEN IFNULL( posting.idAccBegBal, 0 ) > 0 THEN accountbegbal.date
                        END) as date
                        ,(CASE
                            WHEN IFNULL( posting.idInvoice, 0 ) > 0 THEN CONCAT( referenceInv.code, '-', invoices.referenceNum )
                            WHEN IFNULL( posting.idBankRecon, 0 ) > 0 THEN CONCAT( referenceBR.code, '-', bankrecon.referenceNum )
                            WHEN IFNULL( posting.idAccBegBal, 0 ) > 0 THEN 'Acount Beginning Balance'
                        END) as reference
                        ,coa.acod_c15
                        ,coa.aname_c30
                        ,(CASE
                            WHEN IFNULL( posting.idInvoice, 0 ) > 0 THEN invoices.remarks
                            WHEN IFNULL( posting.idBankRecon, 0 ) > 0 THEN bankrecon.description
                            WHEN IFNULL( posting.idAccBegBal, 0 ) > 0 THEN 'Acount Beginning Balance'
                        END) as description
                        ,IFNULL( posting.debit, 0 ) as debit
                        ,IFNULL( posting.credit, 0 ) as credit
                        ,coa.mocod_c1
                        ,coa.chcod_c1
                        ,coa.accod_c2
                        ,2 as sorter
                        ,coa.norm_c2
                        ,(CASE
                            WHEN IFNULL( posting.idInvoice, 0 ) > 0 THEN invoices.idModule
                            WHEN IFNULL( posting.idBankRecon, 0 ) > 0 THEN 44
                            WHEN IFNULL( posting.idAccBegBal, 0 ) > 0 THEN 42
                        END) as idModule
                        ,posting.idInvoice
                        ,posting.idBankRecon
                        ,posting.idAccBegBal
                    FROM posting
                    LEFT OUTER JOIN invoices
                        ON( invoices.idInvoice = posting.idInvoice )
                    LEFT OUTER JOIN reference as referenceInv
                        ON( referenceInv.idReference = invoices.idReference )
                    LEFT OUTER JOIN bankrecon
                        ON( bankrecon.idBankRecon = posting.idBankRecon )
                    LEFT OUTER JOIN reference as referenceBR
                        ON( referenceBR.idReference = bankrecon.idReference )
                    LEFT OUTER JOIN accountbegbal
                        ON( accountbegbal.idAccBegBal = posting.idAccBegBal )
                    JOIN coa
                        ON( coa.idCoa = posting.idCoa )
                    WHERE
                        -- make sure it is filtered by affiliate
                        ( invoices.idAffiliate = $params[idAffiliate] OR bankrecon.idAffiliate = $params[idAffiliate] OR accountbegbal.idAffiliate = $params[idAffiliate] )
                        AND
                        -- from invoices make sure that it is all approved transaction, not tagged as cancel and not coming from closing entry module
                        -- from bankrecon just make sure its approved status
                            ( 
                                ( invoices.status = 2 AND invoices.cancelTag NOT IN( 1 ) AND invoices.idModule NOT IN( 35 ) ) 
                                OR ( bankrecon.status = 2 AND bankrecon.cancelTag NOT IN( 1 ) )
                                OR IFNULL( accountbegbal.idAccBegBal, 0 ) > 0
                            )
                        AND
                        -- make sure record is not deleted
                            ( invoices.archived NOT IN( 1 ) OR bankrecon.archived NOT IN( 1 ) )
                        AND
                        -- filter transaction date on selected month
                        ( 
                            ( invoices.date BETWEEN '$params[sdate]' AND '$params[edate]' )
                            OR ( bankrecon.reconDate BETWEEN '$params[sdate]' AND '$params[edate]' )
                            OR ( accountbegbal.date BETWEEN '$params[sdate]' AND '$params[edate]' )
                        )
                        $exwhere
                )
            ) as main
            JOIN( SELECT @runningBal := 0 ) as runBal ON( 1 = 1 )
            ORDER BY main.sorter, main.date
        " )->result_array();
    }

}