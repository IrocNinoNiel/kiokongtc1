<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Jayson Dagulo
 * Module       : Closing Journal Entry
 * Date         : Jan 28, 2019
 * Finished     : 
 * Description  : This module allows authorized users to set the accounting defaults of every transaction module.
 * DB Tables    : 
 * */ 

class Closingentry_model extends CI_Model {

    public function getClosingEntryRef( $params ){
        $this->db->select( "invoices.idInvoice as id, CONCAT( reference.code, '-', invoices.referenceNum ) as name" );
        $this->db->where( 'invoices.idAffiliate', $this->AFFILIATEID );
        $this->db->join( 'reference', 'reference.idReference = invoices.idReference', 'left outer' );
        $this->db->where( 'invoices.idModule', 35 );
        $this->db->order_by( 'invoices.referenceNum', 'asc' );
        $this->db->where_not_in( 'invoices.archived', 1 );
        if( isset( $params['query'] ) ){
            $this->db->like( "CONCAT( reference.code, '-', invoices.referenceNum )", $params['query'], 'both' );
        }
        return $this->db->get( 'invoices' )->result_array();
    }

    public function viewAll( $params ){
        $this->db->select( "
            invoices.idInvoice
            ,invoices.date
            ,affiliate.affiliateName
            ,CONCAT( reference.code, '-', invoices.referenceNum ) as reference
            ,invoices.description
            ,(CASE invoices.month
                WHEN 1 THEN 'January'
                WHEN 2 THEN 'February'
                WHEN 3 THEN 'March'
                WHEN 4 THEN 'April'
                WHEN 5 THEN 'May'
                WHEN 6 THEN 'June'
                WHEN 7 THEN 'July'
                WHEN 8 THEN 'August'
                WHEN 9 THEN 'September'
                WHEN 10 THEN 'October'
                WHEN 11 THEN 'November'
                WHEN 12 THEN 'December'
            END) as monthDis
            ,invoices.month
            ,invoices.year
            ,(CASE invoices.status
                WHEN 1 THEN 'Draft'
                WHEN 2 THEN 'Final'
                ELSE ''
            END) as statusDis
            ,invoices.idAffiliate
        " );
        $this->db->from( 'invoices' );
        $this->db->join( 'affiliate', 'affiliate.idAffiliate = invoices.idAffiliate', 'left outer' );
        $this->db->join( 'reference', 'reference.idReference = invoices.idReference', 'left outer' );
        $this->db->where( 'invoices.idAffiliate', $this->AFFILIATEID );
        $this->db->where( 'invoices.idModule', 35 ); // Closing Journal Entry
        $this->db->where_not_in( 'invoices.archived', 1 );
        if( isset( $params['filterValue'] ) ){
            if( (int)$params['filterValue'] > 0 ) $this->db->where( 'invoices.idInvoice', (int)$params['filterValue'] );
        }

        $params['db'] = $this->db;
        $params['order_by'] = 'invoices.date DESC, invoices.idInvoice DESC';

        return getGridList($params);
    }

    public function getClosingEntries( $params ){
        $params['motnh']        = ( isset( $params['motnh'] )? (int)$params['motnh'] : 0 );
        $params['year']         = ( isset( $params['year'] )? (int)$params['year'] : 0 );
        return $this->db->query( "
            SELECT
                main.idCoa
                ,main.code
                ,main.name
                ,(CASE
                    WHEN main.totCredit > main.totDebit AND main.mocod_c1 = 6 THEN main.totCredit - main.totDebit
                    ELSE main.debit
                END) as debit
                ,(CASE
                    WHEN main.totDebit > main.totCredit AND main.mocod_c1 = 6 THEN main.totDebit - main.totCredit
                    ELSE main.credit
                END) as credit
                ,main.totCredit
                ,main.totDebit
            FROM(
                SELECT
                    innerMain.idCoa
                    ,innerMain.code
                    ,innerMain.name
                    ,innerMain.mocod_c1
                    ,(CASE
                        WHEN innerMain.norm_c2 = 'CR' THEN ( innerMain.credit - innerMain.debit )
                        ELSE 0
                    END) as debit
                    ,(CASE
                        WHEN innerMain.norm_c2 = 'DR' THEN ( innerMain.debit - innerMain.credit )
                        ELSE 0
                    END) as credit
                    ,@totDebit :=  @totDebit + (CASE
                        WHEN innerMain.mocod_c1 = 4 THEN ( innerMain.credit - innerMain.debit )
                        ELSE 0
                    END) as totDebit
                    ,@totCredit := @totCredit + (CASE
                        WHEN innerMain.mocod_c1 = 5 THEN ( innerMain.debit - innerMain.credit )
                        ELSE 0
                    END) as totCredit
                FROM(
                    -- Get All Revenues account
                    ( SELECT
                        posting.idCoa
                        ,coa.acod_c15 as code
                        ,coa.aname_c30 as name
                        ,SUM( IFNULL( posting.debit, 0 ) ) as debit
                        ,SUM( IFNULL( posting.credit, 0 ) ) as credit
                        ,coa.norm_c2
                        ,coa.mocod_c1
                    FROM posting
                    JOIN coa
                        ON( posting.idCoa = coa.idCoa )
                    LEFT OUTER JOIN invoices
                        ON( invoices.idInvoice = posting.idInvoice )
                    LEFT OUTER JOIN bankrecon
                        ON( bankrecon.idBankRecon = posting.idBankRecon )
                    LEFT OUTER JOIN accountbegbal
                        ON( accountbegbal.idAccBegBal = posting.idAccBegBal )
                    WHERE
                        -- make sure it is filtered by affiliate
                        -- from invoices make sure that it is all approved transaction, not tagged as cancel and not coming from closing entry module
                        -- from bankrecon just make sure its approved status and is not cancelled
                        -- make sure record is not deleted
                            (
                                ( 
                                    invoices.idAffiliate = $this->AFFILIATEID 
                                        AND invoices.status = 2 
                                            AND invoices.cancelTag NOT IN( 1 ) 
                                                AND invoices.idModule NOT IN( 35 ) 
                                                    AND invoices.archived NOT IN( 1 ) )
                                OR ( 
                                    bankrecon.idAffiliate = $this->AFFILIATEID 
                                        AND bankrecon.status = 2 
                                            AND bankrecon.cancelTag NOT IN( 1 ) 
                                                AND bankrecon.archived NOT IN( 1 ) )
                                OR ( 
                                    accountbegbal.idAffiliate = $this->AFFILIATEID 
                                        AND IFNULL( accountbegbal.idAccBegBal, 0 ) > 0 )
                            )
                        AND
                        -- filter only revenue records
                            coa.mocod_c1 = 4
                        AND
                        -- filter transaction date on selected month
                        ( MONTH( invoices.date ) = $params[month] OR MONTH( bankrecon.reconDate ) = $params[month] OR MONTH( accountbegbal.date ) = $params[month] )
                        AND
                        -- filter transaction date on selected year
                        ( YEAR( invoices.date ) = $params[year] OR YEAR( bankrecon.reconDate ) = $params[year] OR YEAR( accountbegbal.date ) = $params[year] )
                    GROUP BY posting.idCoa
                )
                UNION ALL

                -- Get all expenses records
                (    SELECT
                        posting.idCoa
                        ,coa.acod_c15 as code
                        ,coa.aname_c30 as name
                        ,SUM( IFNULL( posting.debit, 0 ) ) as debit
                        ,SUM( IFNULL( posting.credit, 0 ) ) as credit
                        ,coa.norm_c2
                        ,coa.mocod_c1
                    FROM posting
                    JOIN coa
                        ON( posting.idCoa = coa.idCoa )
                    LEFT OUTER JOIN invoices
                        ON( invoices.idInvoice = posting.idInvoice )
                    LEFT OUTER JOIN bankrecon
                        ON( bankrecon.idBankRecon = posting.idBankRecon )
                    LEFT OUTER JOIN accountbegbal
                        ON( accountbegbal.idAccBegBal = posting.idAccBegBal )
                    WHERE
                    -- make sure it is filtered by affiliate
                    -- from invoices make sure that it is all approved transaction, not tagged as cancel and not coming from closing entry module
                    -- from bankrecon just make sure its approved status and is not cancelled
                    -- make sure record is not deleted
                        (
                            ( 
                                invoices.idAffiliate = $this->AFFILIATEID 
                                    AND invoices.status = 2 
                                        AND invoices.cancelTag NOT IN( 1 ) 
                                            AND invoices.idModule NOT IN( 35 ) 
                                                AND invoices.archived NOT IN( 1 ) )
                            OR ( 
                                bankrecon.idAffiliate = $this->AFFILIATEID 
                                    AND bankrecon.status = 2 
                                        AND bankrecon.cancelTag NOT IN( 1 ) 
                                            AND bankrecon.archived NOT IN( 1 ) )
                            OR ( 
                                accountbegbal.idAffiliate = $this->AFFILIATEID 
                                    AND IFNULL( accountbegbal.idAccBegBal, 0 ) > 0 )
                        )
                        AND
                        -- filter only revenue records
                            coa.mocod_c1 = 5
                        AND
                        -- filter transaction date on selected month
                        ( MONTH( invoices.date ) = $params[month] OR MONTH( bankrecon.reconDate ) = $params[month] OR MONTH( accountbegbal.date ) = $params[month] )
                        AND
                        -- filter transaction date on selected year
                        ( YEAR( invoices.date ) = $params[year] OR YEAR( bankrecon.reconDate ) = $params[year] OR YEAR( accountbegbal.date ) = $params[year] )
                    GROUP BY posting.idCoa
                )
                    UNION ALL

                (    SELECT
                        coa.idCoa
                        ,coa.acod_c15 as code
                        ,coa.aname_c30 as name
                        ,0 as debit
                        ,0 as credit
                        ,coa.norm_c2
                        ,6 as mocod_c1
                    FROM
                        defaultaccounts
                    JOIN coa
                        ON( coa.idCoa = defaultaccounts.retainedEarnings )
                    WHERE defaultaccounts.idAffiliate = $this->AFFILIATEID
                    LIMIT 1
                )
                ) as innerMain
                JOIN ( SELECT @totDebit := 0, @totCredit := 0 ) as b ON( 1 = 1 )
            ) as main
            ORDER BY main.mocod_c1, main.idCoa
        " )->result_array();
    }

    public function getPreviousTransactions( $params ){
        $this->db->select( 'date' );
        $this->db->where_not_in( 'archived', 1 );
        $this->db->where_not_in( 'idModule', $params['idModule'] );
        $this->db->where( 'MONTH( date ) <', $params['month'] );
        $this->db->where( 'YEAR( date ) <=', $params['year'] );
        $this->db->where( 'idAffiliate', $this->AFFILIATEID );
        $this->db->where( '( SELECT COUNT( idPosting ) FROM posting WHERE idInvoice = invoices.idInvoice ) > 0' );
        $this->db->order_by( 'date', 'DESC' );
        $this->db->limit( 1 );
        $rec    = $this->db->get( 'invoices' )->row_array();
        if( count( (array)$rec ) > 0 ) return $rec['date'];
        else return false;
    }

    public function saveClosing( $params ){
        $idInvoice              = (int)$params['idInvoice'];
        $params['dateModified'] = date( 'Y-m-d H:i:s' );
        unset( $params['idInvoice'] );
        if( $idInvoice > 0 ){
            $this->db->where( 'idInvoice', $idInvoice );
            $this->db->update( 'invoices', unsetParams( $params, 'invoices' ) );
            return $idInvoice;
        }
        else{
            $this->db->insert( 'invoices', unsetParams( $params, 'invoices' ) );
            return $this->db->insert_id();
        }
    }

    public function saveClosingHistory( $params ){
        $this->db->insert( 'invoiceshistory', unsetParams( $params, 'invoiceshistory' ) );
        return $this->db->insert_id();
    }

    public function deleteRelatedRecords( $params ){
        $this->db->where( 'idInvoice', $params['idInvoice'] );
        $this->db->delete( 'posting' );

        $this->db->where( 'idInvoice', $params['idInvoice'] );
        $this->db->delete( 'gl' );
    }

    public function savePosting( $closingEntries ){
        $this->db->insert_batch( 'posting', unsetParamsBatch( $closingEntries, 'posting' ) );
    }

    public function savePostingHistory( $closingEntries ){
        $this->db->insert_batch( 'postinghistory', unsetParamsBatch( $closingEntries, 'posting' ) );
    }

    public function getPostingRecords( $params ){
        /* commented by jays */
        // if( $params['month'] == 1 ){
        //     $params['prevmonth']    = 12;
        //     $params['prevyear']     = $params['year'] - 1;
        // }else{
        //     $params['prevmonth']    = $params['month'] - 1;
        //     $params['prevyear']     = $params['year'];
        // }
        return $this->db->query( "
                SELECT
                    coa.idCoa
                    ,main.debit
                    ,main.credit
                    ,(CASE
                        WHEN coa.norm_c2 = 'DR' THEN ( ( IFNULL( main.glAmount, 0 ) + IFNULL( main.debit, 0 ) ) - IFNULL( main.credit, 0 ) )
                        ELSE ( ( IFNULL( main.glAmount, 0 ) + IFNULL( main.credit, 0 ) ) - IFNULL( main.debit, 0 ) )
                    END) as glAmount
                    ,$params[month] as month
                    ,$params[year] as glYear
                    ,$params[idInvoice] as idInvoice
                    ,$this->AFFILIATEID as idAffiliate
                FROM(
                    -- get all non-nominal accounts( assets, liabilities and equity )
                    (
                        SELECT
                            posting.idCoa
                            ,SUM( IFNULL( posting.debit, 0 ) ) as debit
                            ,SUM( IFNULL( posting.credit, 0 ) ) as credit
                            ,gl.glAmount
                        FROM posting
                        JOIN coa
                            ON( coa.idCoa = posting.idCoa )
                        LEFT OUTER JOIN invoices
                            ON( invoices.idInvoice = posting.idInvoice )
                        LEFT OUTER JOIN bankrecon
                            ON( bankrecon.idBankRecon = posting.idBankRecon )
                        LEFT OUTER JOIN accountbegbal
                            ON( accountbegbal.idAccBegBal = posting.idAccBegBal )
                        LEFT OUTER JOIN(
                            SELECT
                                SUM( IFNULL( gl.glAmount, 0 ) ) as glAmount
                                ,gl.idCoa
                            FROM gl
                            JOIN invoices
                                ON( invoices.idInvoice = gl.idInvoice )
                            JOIN coa
                                ON( coa.idCoa = gl.idCoa )
                            WHERE
                                -- get only assets, liabilities and equities
                                coa.mocod_c1 IN( 1, 2, 3 )
                                -- make sure to only get active records
                                AND invoices.archived NOT IN( 1 )
                                -- check only records not equal to the current idInvoice
                                AND invoices.idInvoice NOT IN( $params[idInvoice] )
                                -- gl must be the previous months record
                                AND invoices.month = $params[prevmonth] AND invoices.year = $params[prevyear]
                                -- filter on selected affiliate
                                AND invoices.idAffiliate = $this->AFFILIATEID
                                -- do not include cancelled transactions
                                AND invoices.cancelTag NOT IN( 1 )
                            GROUP BY gl.idCoa
                        ) as gl
                            ON( gl.idCoa = posting.idCoa )
                        WHERE
                            -- make sure it is filtered by affiliate
                            -- from invoices make sure that it is all approved transaction, not tagged as cancel
                            -- from bankrecon just make sure its approved status and exclude cancelled records
                            -- make sure record is not deleted
                            (
                                (
                                    invoices.idAffiliate = $this->AFFILIATEID 
                                        AND ( invoices.status = 2 OR invoices.idModule = 35 )
                                            AND invoices.cancelTag NOT IN( 1 ) 
                                                    AND invoices.archived NOT IN( 1 )
                                ) 
                                OR ( 
                                    bankrecon.idAffiliate = $this->AFFILIATEID 
                                        AND bankrecon.status = 2 
                                            AND bankrecon.cancelTag NOT IN( 1 ) 
                                                AND bankrecon.archived NOT IN( 1 ) 
                                ) 
                                OR ( 
                                    accountbegbal.idAffiliate = $this->AFFILIATEID 
                                        AND IFNULL( accountbegbal.idAccBegBal, 0 ) > 0 
                                )
                            )
                            AND
                            -- filter only assists, liabilities and revenue records
                                coa.mocod_c1 IN( 1, 2, 3 )
                            AND
                            -- filter transaction date on selected month
                            ( MONTH( invoices.date ) = $params[month] OR MONTH( bankrecon.reconDate ) = $params[month] OR MONTH( accountbegbal.date ) = $params[month] OR ( invoices.idModule = 35 AND invoices.month = $params[month] ) )
                            AND
                            -- filter transaction date on selected year
                            ( YEAR( invoices.date ) = $params[year] OR YEAR( bankrecon.reconDate ) = $params[year] OR YEAR( accountbegbal.date ) = $params[year] OR ( invoices.idModule = 35 AND invoices.year = $params[year] ) )
                        GROUP BY posting.idCoa, gl.glAmount
                    )

                    UNION ALL
                    -- get all nominal accounts( revenue and expenses )
                    (
                        SELECT
                            posting.idCoa
                            ,SUM( IFNULL( posting.debit, 0 ) ) as debit
                            ,SUM( IFNULL( posting.credit, 0 ) ) as credit
                            ,0 as glAmount
                        FROM posting
                        JOIN coa
                            ON( coa.idCoa = posting.idCoa )
                        LEFT OUTER JOIN invoices
                            ON( invoices.idInvoice = posting.idInvoice )
                        LEFT OUTER JOIN bankrecon
                            ON( bankrecon.idBankRecon = posting.idBankRecon )
                        LEFT OUTER JOIN accountbegbal
                            ON( accountbegbal.idAccBegBal = posting.idAccBegBal )
                        WHERE
                        -- make sure it is filtered by affiliate
                        -- from invoices make sure that it is all approved transaction, not tagged as cancel
                        -- from bankrecon just make sure its approved status and exclude cancelled records
                        -- make sure record is not deleted
                        (
                            (
                                invoices.idAffiliate = $this->AFFILIATEID 
                                    AND invoices.status = 2 
                                        AND invoices.cancelTag NOT IN( 1 ) 
                                           -- AND invoices.idModule NOT IN( 35 ) 
                                                AND invoices.archived NOT IN( 1 )
                            ) 
                            OR ( 
                                bankrecon.idAffiliate = $this->AFFILIATEID 
                                    AND bankrecon.status = 2 
                                        AND bankrecon.cancelTag NOT IN( 1 ) 
                                            AND bankrecon.archived NOT IN( 1 ) 
                            ) 
                            OR ( 
                                accountbegbal.idAffiliate = $this->AFFILIATEID 
                                    AND IFNULL( accountbegbal.idAccBegBal, 0 ) > 0 
                            )
                        )
                            AND
                            -- filter only revenue records
                                coa.mocod_c1 IN( 4, 5 )
                            AND
                            -- filter transaction date on selected month
                            ( MONTH( invoices.date ) = $params[month] OR MONTH( bankrecon.reconDate ) = $params[month] OR MONTH( accountbegbal.date ) = $params[month] )
                            AND
                            -- filter transaction date on selected year
                            ( YEAR( invoices.date ) = $params[year] OR YEAR( bankrecon.reconDate ) = $params[year] OR YEAR( accountbegbal.date ) = $params[year] )

                        GROUP BY posting.idCoa
                    )
                ) as main
                JOIN coa
                    ON( coa.idCoa = main.idCoa )
                ORDER BY coa.idCoa
        " )->result_array();
    }

    public function saveGL( $glRecords ){
        $this->db->insert_batch( 'gl', unsetParamsBatch( $glRecords, 'gl' ) );
    }

    public function retrieveData( $params ){
        $this->db->select( "idInvoice, idAffiliate, month, year, DATE_FORMAT( date, '%Y-%m-%d' ) as date, description, remarks, idReference, referenceNum, dateModified" );
        $this->db->where( 'idInvoice', (int)$params['idInvoice'] );
        return $this->db->get( 'invoices' )->result_array();
    }

    public function tagRecordAsArchived( $params ){
        $this->db->set( 'archived', 1 );
        $this->db->where( 'idInvoice', (int)$params['idInvoice'] );
        $this->db->update( 'invoices' );
    }

    public function getCoaBegBalRec(){
        $this->db->select( 'date' );
        $this->db->where( 'idAffiliate', $this->AFFILIATEID );
        $rec = $this->db->get( 'accountbegbal' )->row_array();
        if( count( (array)$rec ) > 0 ) return $rec['date'];
        else return false;
    }

}