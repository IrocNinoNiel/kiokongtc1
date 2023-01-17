<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Jayson Dagulo
 * Module       : Financial Report
 * Date         : Jan 30, 2019
 * Finished     : 
 * Description  : This module allows authorized user to manually closes the journal entries.
 * DB Tables    : 
 * */ 

class Financialreport_model extends CI_Model {

    public function getAffiliateList(){
        $this->db->select( 'affiliate.idAffiliate, affiliate.affiliateName, affiliate.dateStart' );
        $this->db->join( 'employeeaffiliate', 'employeeaffiliate.idAffiliate = affiliate.idAffiliate', 'inner' );
        $this->db->where( 'employeeaffiliate.idEmployee', $this->EMPLOYEEID );
        $this->db->where_not_in( 'affiliate.archived', 1 );
        return $this->db->get( 'affiliate' )->result_array();
    }

    public function getAffiliateClosingEntryStatus( $params ){
        $this->db->select( 'status' );
        $this->db->where( 'idModule', 35 ); /* closing entry module */
        $this->db->where( 'month', $params['month'] );
        $this->db->where( 'year', $params['year'] );
        $this->db->where_not_in( 'archived', 1 );
        return $this->db->get( 'invoices' )->row_array();
    }

    public function getAffiliateInfo( $idAffiliate, $field ){
        $this->db->select( $field );
        $this->db->where( 'idAffiliate', $idAffiliate );
        $rec    = $this->db->get( 'affiliate' )->row_array();
        if( count( (array)$rec ) > 0 ) return $rec[$field];
        else return false;
    }

    public function getMainAffiliate(){
        $this->db->select( 'idAffiliate' );
        $this->db->where( 'maintag', 1 );
        $rec    = $this->db->get( 'affiliate' )->row_array();
        if( count( (array)$rec ) > 0 ) return $rec['idAffiliate'];
        return 0;
    }

    public function processTrialBalance( $params ){
        $exwhereinner   = "";
        $exwhereouter   = "";
        $groupby        = "";
        if( (int)$params['idAffiliate'] == 0 ){
            $exwhereinner .= "AND invoices.idAffiliate IN( SELECT idAffiliate FROM employeeaffiliate WHERE idEmployee = $this->EMPLOYEEID )";
            $exwhereouter .= "coaaffiliate.idAffiliate IN( SELECT idAffiliate FROM employeeaffiliate WHERE idEmployee = $this->EMPLOYEEID )";
        }
        else{
            $exwhereinner .= "AND invoices.idAffiliate = $params[idAffiliate]";
            $exwhereouter .= "coaaffiliate.idAffiliate = $params[idAffiliate]";
        }

        if( (int)$params['displayType'] == 2 ) $groupby    .= "coaHeader.idCoa, coaHeader.acod_c15, coaHeader.aname_c30";
        else $groupby .= "coa.idCoa, coa.acod_c15, coa.aname_c30";

        return $this->db->query( "
            SELECT
                (CASE
                    WHEN coa.norm_c2 = 'DR' THEN SUM( IFNULL( gl.glAmount, 0 ) )
                    ELSE 0
                END) as debit
                ,(CASE
                    WHEN coa.norm_c2 = 'CR' THEN SUM( IFNULL( gl.glAmount, 0 ) )
                    ELSE 0
                END) as credit
                ,$groupby
            FROM coa
            LEFT OUTER JOIN(
                SELECT
                    SUM( IFNULL( gl.glAmount, 0 ) ) as glAmount
                    ,gl.idCoa
                FROM gl
                JOIN invoices
                    ON( invoices.idInvoice = gl.idInvoice )
                WHERE
                    invoices.month = $params[month]
                        AND invoices.year = $params[year]
                            AND invoices.archived NOT IN( 1 )
                                $exwhereinner
                GROUP BY gl.idCoa
            ) as gl
                ON( gl.idCoa = coa.idCoa )
            JOIN coa as coaHeader
                ON( coaHeader.mocod_c1 = coa.mocod_c1 AND coaHeader.chcod_c1 = coa.chcod_c1 AND coaHeader.accod_c2 = coa.accod_c2 AND coaHeader.accountType = 1 )
            WHERE
                coa.idCoa IN( SELECT idCoa FROM coaaffiliate WHERE $exwhereouter  )
                    AND coa.archived NOT IN( 1 )
            GROUP BY coa.norm_c2, $groupby
            ORDER by $groupby
        " )->result_array();
    }

    public function processIncomeStatement( $params ){
        $exwhereinner   = "";
        $exwhereouter   = "";
        $groupby    = "";
        if( (int)$params['idAffiliate'] == 0 ){
            $exwhereinner .= "AND invoices.idAffiliate IN( SELECT idAffiliate FROM employeeaffiliate WHERE idEmployee = $this->EMPLOYEEID )";
            $exwhereouter .= "coaaffiliate.idAffiliate IN( SELECT idAffiliate FROM employeeaffiliate WHERE idEmployee = $this->EMPLOYEEID )";
        }
        else{
            $exwhereinner .= "AND invoices.idAffiliate = $params[idAffiliate]";
            $exwhereouter .= "coaaffiliate.idAffiliate = $params[idAffiliate]";
        }

        if( (int)$params['displayType'] == 2 ) $groupby    .= "coaHeaders.idCoa, coaHeaders.aname_c30, coaHeaders.mocod_c1";
        else $groupby .= "glRecords.idCoa, glRecords.aname_c30, glRecords.mocod_c1";

        return $this->db->query( "
            SELECT
                -- calculate report total revenues( from previous year up to current )
                IF(main.sorter = 1.0, @totalRevenue := @totalRevenue + IFNULL( main.currentMonthGLAmount, 0 ), @totalRevenue )
			    ,IF(main.sorter = 1.0, @totalRevenue1 := @totalRevenue1 + IFNULL( main.currentYrAccumulatedGLAmount, 0 ), @totalRevenue1 )
			    ,IF(main.sorter = 1.0, @totalRevenue2 := @totalRevenue2 + IFNULL( main.previousYrAccumulatedGLAmount, 0 ), @totalRevenue2 )
			    ,IF(main.sorter = 1.0, @totalRevenue3 := @totalRevenue3 + IFNULL( main.previousYrGLAmount, 0 ), @totalRevenue3 )
                
                -- calculate report total for cost of goods sold( from previous year up to current )
			    ,IF(main.sorter = 1.3, @totalGoodsSold := @totalGoodsSold + IFNULL( main.currentMonthGLAmount, 0 ), @totalGoodsSold )
			    ,IF(main.sorter = 1.3, @totalGoodsSold1 := @totalGoodsSold1 + IFNULL( main.currentYrAccumulatedGLAmount, 0 ), @totalGoodsSold1 )
			    ,IF(main.sorter = 1.3, @totalGoodsSold2 := @totalGoodsSold2 + IFNULL( main.previousYrAccumulatedGLAmount, 0 ), @totalGoodsSold2 )
			    ,IF(main.sorter = 1.3, @totalGoodsSold3 := @totalGoodsSold3 + IFNULL( main.previousYrGLAmount, 0 ), @totalGoodsSold3 )
                
                -- calculate report total operating expense( from previous year up to current )
			    ,IF(main.sorter = 3.0, @totalOperatingExpense := @totalOperatingExpense + IFNULL( main.currentMonthGLAmount, 0 ), @totalOperatingExpense )
			    ,IF(main.sorter = 3.0, @totalOperatingExpense1 := @totalOperatingExpense1 + IFNULL( main.currentYrAccumulatedGLAmount, 0 ), @totalOperatingExpense1 )
			    ,IF(main.sorter = 3.0, @totalOperatingExpense2 := @totalOperatingExpense2 + IFNULL( main.previousYrAccumulatedGLAmount, 0 ), @totalOperatingExpense2 )
			    ,IF(main.sorter = 3.0, @totalOperatingExpense3 := @totalOperatingExpense3 + IFNULL( main.previousYrGLAmount, 0 ), @totalOperatingExpense3 )
                
                -- calculate report total income before tax( from prevous year up to current )
			    ,IF(main.sorter = 4.0, @totalIncomeBeforeTax := @totalIncomeBeforeTax + IFNULL( main.currentMonthGLAmount, 0 ), @totalIncomeBeforeTax )
			    ,IF(main.sorter = 4.0, @totalIncomeBeforeTax1 := @totalIncomeBeforeTax1 + IFNULL( main.currentYrAccumulatedGLAmount, 0 ), @totalIncomeBeforeTax1 )
			    ,IF(main.sorter = 4.0, @totalIncomeBeforeTax2 := @totalIncomeBeforeTax2 + IFNULL( main.previousYrAccumulatedGLAmount, 0 ), @totalIncomeBeforeTax2 )
                ,IF(main.sorter = 4.0, @totalIncomeBeforeTax3 := @totalIncomeBeforeTax3 + IFNULL( main.previousYrGLAmount, 0 ), @totalIncomeBeforeTax3 )
                
                -- identify the value of income tax expense( from previous year up to current )
                ,IF(main.sorter = 5.0, @incomeTaxExpense := IFNULL( main.currentMonthGLAmount, 0 ), @incomeTaxExpense )
                ,IF(main.sorter = 5.0, @incomeTaxExpense1 := IFNULL( main.currentYrAccumulatedGLAmount, 0 ), @incomeTaxExpense1 )
                ,IF(main.sorter = 5.0, @incomeTaxExpense2 := IFNULL( main.previousYrAccumulatedGLAmount, 0 ), @incomeTaxExpense2 )
                ,IF(main.sorter = 5.0, @incomeTaxExpense3 := IFNULL( main.previousYrGLAmount, 0 ), @incomeTaxExpense3 )

                -- from this point
                -- assigning of values each record
                -- previous year coverage( previous year start to end month( base on accounting period of the affiliate/main affiliate ) )
                ,(CASE
                    -- Total Revenue
                    WHEN main.sorter = 1.1 THEN @totalRevenue3
                    -- Total Goods sold
                    WHEN main.sorter = 1.2 THEN @totalGoodsSold3
                    -- Gross Income
                    WHEN main.sorter = 1.4 THEN ( @totalRevenue3 - @totalGoodsSold3 )
                    -- Total operating expense
                    WHEN main.sorter = 3.1 THEN @totalOperatingExpense3
                    -- Operating Income/(Loss)
                    WHEN main.sorter = 3.2 THEN ( @totalRevenue3 - @totalGoodsSold3 - @totalOperatingExpense3 )
                    -- Income before tax
                    WHEN main.sorter = 4.1 THEN ( @totalRevenue3 - @totalGoodsSold3 - @totalOperatingExpense3 ) + @totalIncomeBeforeTax3
                    -- Net Income/(Loss)
                    WHEN main.sorter = 6.0 THEN (@totalRevenue3 - @totalGoodsSold3 - @totalOperatingExpense3) + @totalIncomeBeforeTax3
                    -- Normal value
                    ELSE IFNULL( main.previousYrGLAmount, 0 )
                END) as previousYrGLAmount

                -- previous year accumulated( previous year start month( base on accounting period of the affiliate/main affiliate ) ) to selected month( from the module )
                ,(CASE
                    -- Total Revenue
                    WHEN main.sorter = 1.1 THEN @totalRevenue2
                    -- Total Goods sold
                    WHEN main.sorter = 1.2 THEN @totalGoodsSold2
                    -- Gross Income
                    WHEN main.sorter = 1.4 THEN ( @totalRevenue2 - @totalGoodsSold2 )
                    -- Total operating expense
                    WHEN main.sorter = 3.1 THEN @totalOperatingExpense2
                    -- Operating Income/(Loss)
                    WHEN main.sorter = 3.2 THEN ( @totalRevenue2 - @totalGoodsSold2 - @totalOperatingExpense2 )
                    -- Income before tax
                    WHEN main.sorter = 4.1 THEN ( @totalRevenue2 - @totalGoodsSold2 - @totalOperatingExpense2 ) + @totalIncomeBeforeTax2
                    -- Net Income/(Loss)
                    WHEN main.sorter = 6.0 THEN ( @totalRevenue2 - @totalGoodsSold2 - @totalOperatingExpense2 ) + @totalIncomeBeforeTax2
                    -- Normal value
                    ELSE IFNULL( main.previousYrAccumulatedGLAmount, 0 )
                END) as previousYrAccumulatedGLAmount

                -- current year accumulated( current year start month( base on accounting period of the affiliate/main affiliate ) ) to selected month( from the moduke )
                ,(CASE
                    -- Total Revenue
                    WHEN main.sorter = 1.1 THEN @totalRevenue1
                    -- Total Goods sold
                    WHEN main.sorter = 1.2 THEN @totalGoodsSold1
                    -- Gross Income
                    WHEN main.sorter = 1.4 THEN ( @totalRevenue1 - @totalGoodsSold1 )
                    -- Total operating expense
                    WHEN main.sorter = 3.1 THEN @totalOperatingExpense1
                    -- Operating Income/(Loss)
                    WHEN main.sorter = 3.2 THEN ( @totalRevenue1 - @totalGoodsSold1 - @totalOperatingExpense1 )
                    -- Income before tax
                    WHEN main.sorter = 4.1 THEN ( @totalRevenue1 - @totalGoodsSold1 - @totalOperatingExpense1 ) + @totalIncomeBeforeTax1
                    -- Net Income/(Loss)
                    WHEN main.sorter = 6.0 THEN ( @totalRevenue1 - @totalGoodsSold1 - @totalOperatingExpense1 ) + @totalIncomeBeforeTax1
                    -- Normal value
                    ELSE IFNULL( main.currentYrAccumulatedGLAmount, 0 )
                END) as currentYrAccumulatedGLAmount

                -- current month selected
                ,(CASE
                    -- Total Revenue
                    WHEN main.sorter = 1.1 THEN @totalRevenue
                    -- Total Goods sold
                    WHEN main.sorter = 1.2 THEN @totalGoodsSold
                    -- Gross Income
                    WHEN main.sorter = 1.4 THEN ( @totalRevenue - @totalGoodsSold )
                    -- Total operating expense
                    WHEN main.sorter = 3.1 THEN @totalOperatingExpense
                    -- Operating Income/(Loss)
                    WHEN main.sorter = 3.2 THEN ( @totalRevenue - @totalGoodsSold - @totalOperatingExpense )
                    -- Income before tax
                    WHEN main.sorter = 4.1 THEN ( @totalRevenue - @totalGoodsSold - @totalOperatingExpense ) + @totalIncomeBeforeTax
                    -- Net Income/(Loss)
                    WHEN main.sorter = 6.0 THEN ( @totalRevenue - @totalGoodsSold - @totalOperatingExpense ) + @totalIncomeBeforeTax
                    -- Normal value
                    ELSE IFNULL( main.currentMonthGLAmount, 0 )
                END) as currentMonthGLAmount

                ,(CASE
					WHEN main.sorter = 1.1 THEN
						IFNULL( ( @totalRevenue1 - @totalRevenue2 ) / @totalRevenue2, 0 )
					WHEN main.sorter = 1.4 THEN
						IFNULL( ( ( @totalRevenue1 - @totalGoodsSold1 ) - ( @totalRevenue2 - @totalGoodsSold2 ) ) / ( @totalRevenue2 - @totalGoodsSold2 ), 0 )
					WHEN main.sorter = 3.1 THEN
						IFNULL( ( @totalOperatingExpense1 - @totalOperatingExpense2 ) / @totalOperatingExpense2, 0 )
					WHEN main.sorter = 3.2 THEN
						IFNULL( ( ( @totalRevenue1 - @totalGoodsSold1 - @totalOperatingExpense1 ) - ( @totalRevenue2 - @totalGoodsSold2 - @totalOperatingExpense2 ) ) / ( @totalRevenue2 - @totalGoodsSold2 - @totalOperatingExpense2 ), 0 )
					WHEN main.sorter = 4.1 THEN
						IFNULL( ( ( ( @totalRevenue1 - @totalGoodsSold1 - @totalOperatingExpense1 ) + @totalIncomeBeforeTax1 ) - ( ( @totalRevenue2 - @totalGoodsSold2 - @totalOperatingExpense2 ) + @totalIncomeBeforeTax2 ) ) / ( ( @totalRevenue2 - @totalGoodsSold2 - @totalOperatingExpense2 ) + @totalIncomeBeforeTax2 ), 0 )
					WHEN main.sorter = 6 THEN
						IFNULL( ( ( ( @totalRevenue1 - @totalGoodsSold1 - @totalOperatingExpense1 ) + @totalIncomeBeforeTax1 ) - ( ( @totalRevenue2 - @totalGoodsSold2 - @totalOperatingExpense2 ) + @totalIncomeBeforeTax2 ) ) / ( ( @totalRevenue2 - @totalGoodsSold2 - @totalOperatingExpense2 ) + @totalIncomeBeforeTax2 ), 0 )
					ELSE
						main.incDec
			    END) AS incDec
                ,main.aname_c30
                ,main.sorter
                ,main.mocod_c1
            FROM(
                (
                    SELECT
                        IFNULL( summarizedGLRecords.previousYrGLAmount, 0 ) as previousYrGLAmount
                        ,IFNULL( summarizedGLRecords.previousYrAccumulatedGLAmount, 0 ) as previousYrAccumulatedGLAmount
                        ,IFNULL( summarizedGLRecords.currentYrAccumulatedGLAmount, 0 ) as currentYrAccumulatedGLAmount
                        ,IFNULL( summarizedGLRecords.currentMonthGLAmount, 0 ) as currentMonthGLAmount
                        ,summarizedGLRecords.aname_c30
                        ,(CASE
                            WHEN summarizedGLRecords.incomeTaxProvision > 0 THEN 5.0  -- Income Tax Provisions
                            WHEN summarizedGLRecords.mocod_c1 = 4 AND summarizedGLRecords.accID != 13 -- Cost of Sales
                                AND summarizedGLRecords.accID != 16 -- Other Income
                                THEN 1.0 -- Revenues
                            WHEN summarizedGLRecords.accID = 13 THEN 1.3
                            WHEN summarizedGLRecords.mocod_c1 = 5 AND summarizedGLRecords.accID != 13 -- Cost of Sales
                                AND summarizedGLRecords.accID != 16 -- Other Income
                                THEN 3.0 -- Expenses
                            WHEN summarizedGLRecords.accID = 16 THEN 4.0 -- Other Income
                        END) as sorter
                        ,IFNULL( ( summarizedGLRecords.currentYrAccumulatedGLAmount - summarizedGLRecords.previousYrAccumulatedGLAmount ) / summarizedGLRecords.previousYrAccumulatedGLAmount, 0 ) AS incDec
                        ,summarizedGLRecords.mocod_c1
                    FROM(
                        (  -- group same records and sum up totals(in preparation for report display process)
                            SELECT
                                SUM( IFNULL( glRecords.previousYrGLAmount, 0 ) ) as previousYrGLAmount
                                ,SUM( IFNULL( glRecords.previousYrAccumulatedGLAmount, 0 ) ) as previousYrAccumulatedGLAmount
                                ,SUM( IFNULL( glRecords.currentYrAccumulatedGLAmount, 0 ) ) as currentYrAccumulatedGLAmount
                                ,SUM( IFNULL( glRecords.currentMonthGLAmount, 0 ) ) as currentMonthGLAmount
                                ,glRecords.incomeTaxProvision
                                ,$groupby
                                ,glRecords.accID
                            FROM(
                                -- Previous year records of the GL(based on accounting schedule)
                                (
                                    SELECT
                                        SUM( IFNULL( gl.glAmount, 0 ) ) as previousYrGLAmount
                                        ,0 as previousYrAccumulatedGLAmount
                                        ,0 as currentYrAccumulatedGLAmount
                                        ,0 as currentMonthGLAmount
                                        ,gl.idCoa
                                        ,coa.aname_c30
                                        ,coa.mocod_c1
                                        ,coa.chcod_c1
                                        ,coa.accod_c2
                                        ,coa.accID
                                        ,0 as incomeTaxProvision
                                    FROM gl
                                    JOIN invoices
                                        ON( invoices.idInvoice = gl.idInvoice )
                                    JOIN coa
                                        ON( coa.idCoa = gl.idCoa )
                                    LEFT OUTER JOIN defaultaccounts
                                        ON( defaultaccounts.incomeTaxProvision = gl.idCoa AND " . ( (int)$params['idAffiliate'] > 0? "defaultaccounts.idAffiliate = $params[idAffiliate]" : "defaultaccounts.idAffiliate IN( SELECT idAffiliate FROM employeeaffiliate WHERE idEmployee = $this->EMPLOYEEID )" ) . " )
                                    WHERE
                                        invoices.archived NOT IN( 1 )
                                            $exwhereinner
                                                AND ( invoices.month BETWEEN " . date( 'm', strtotime( $params['prevSDate'] ) ) . " AND " . date( 'm', strtotime( $params['prevEDate'] ) ) . " )
                                                    AND( invoices.year BETWEEN " . date( 'Y', strtotime( $params['prevSDate'] ) ) . " AND " . date( 'Y', strtotime( $params['prevEDate'] ) ) . " )
                                                        -- exclude income tax provision account( need to be on a separate query )
                                                        AND ( coa.mocod_c1 IN( 4, 5 ) AND IFNULL( defaultaccounts.incomeTaxProvision, 0 ) = 0 )
                                    GROUP BY gl.idCoa, defaultaccounts.incomeTaxProvision, coa.aname_c30, coa.mocod_c1, coa.chcod_c1, coa.accod_c2, coa.accID
                                )

                                UNION ALL

                                -- Get previous year record of the GL(Accumulated which means record to get is from last year start month up to selected month of current year )
                                (
                                    SELECT
                                        0 as previousYrGLAmount
                                        ,SUM( IFNULL( gl.glAmount, 0 ) ) as previousYrAccumulatedGLAmount
                                        ,0 as currentUrAccumulatedGLAmount
                                        ,0 as currentMonthGLAmount
                                        ,gl.idCoa
                                        ,coa.aname_c30
                                        ,coa.mocod_c1
                                        ,coa.chcod_c1
                                        ,coa.accod_c2
                                        ,coa.accID
                                        ,0 as incomeTaxProvision
                                    FROM gl
                                    JOIN invoices
                                        ON( invoices.idInvoice = gl.idInvoice )
                                    JOIN coa
                                        ON( coa.idCoa = gl.idCoa )
                                    LEFT OUTER JOIN defaultaccounts
                                        ON( defaultaccounts.incomeTaxProvision = gl.idCoa AND " . ( (int)$params['idAffiliate'] > 0? "defaultaccounts.idAffiliate = $params[idAffiliate]" : "defaultaccounts.idAffiliate IN( SELECT idAffiliate FROM employeeaffiliate WHERE idEmployee = $this->EMPLOYEEID )" ) . " )
                                    WHERE
                                        invoices.archived NOT IN( 1 )
                                            $exwhereinner
                                                AND ( invoices.month BETWEEN " . date( 'm', strtotime( $params['prevAccSDate'] ) ) . " AND " . date( 'm', strtotime( $params['prevAccEDate'] ) ) . " )
                                                    AND( invoices.year BETWEEN " . date( 'Y', strtotime( $params['prevAccSDate'] ) ) . " AND " . date( 'Y', strtotime( $params['prevAccEDate'] ) ) . " )
                                                        -- exclude income tax provision account( need to be on a separate query )
                                                        AND ( coa.mocod_c1 IN( 4, 5 ) AND IFNULL( defaultaccounts.incomeTaxProvision, 0 ) = 0 )
                                    GROUP BY gl.idCoa, defaultaccounts.incomeTaxProvision, coa.aname_c30, coa.mocod_c1, coa.chcod_c1, coa.accod_c2, coa.accID
                                )

                                UNION ALL
                                
                                -- Get current year record of the GL( Accumulated which means record to get is from current period start month up to selected month of the current year )
                                (
                                    SELECT
                                        0 as previousYrGLAmount
                                        ,0 as previousYrAccumulatedGLAmount
                                        ,SUM( IFNULL( gl.glAmount, 0 ) ) as currentUrAccumulatedGLAmount
                                        ,0 as currentMonthGLAmount
                                        ,gl.idCoa
                                        ,coa.aname_c30
                                        ,coa.mocod_c1
                                        ,coa.chcod_c1
                                        ,coa.accod_c2
                                        ,coa.accID
                                        ,0 as incomeTaxProvision
                                    FROM gl
                                    JOIN invoices
                                        ON( invoices.idInvoice = gl.idInvoice )
                                    JOIN coa
                                        ON( coa.idCoa = gl.idCoa )
                                    LEFT OUTER JOIN defaultaccounts
                                        ON( defaultaccounts.incomeTaxProvision = gl.idCoa AND " . ( (int)$params['idAffiliate'] > 0? "defaultaccounts.idAffiliate = $params[idAffiliate]" : "defaultaccounts.idAffiliate IN( SELECT idAffiliate FROM employeeaffiliate WHERE idEmployee = $this->EMPLOYEEID )" ) . " )
                                    WHERE
                                        invoices.archived NOT IN( 1 )
                                            $exwhereinner
                                                AND ( invoices.month BETWEEN " . date( 'm', strtotime( $params['curAccSDate'] ) ) . " AND " . date( 'm', strtotime( $params['curAccEDate'] ) ) . " )
                                                    AND( invoices.year BETWEEN " . date( 'Y', strtotime( $params['curAccSDate'] ) ) . " AND " . date( 'Y', strtotime( $params['curAccEDate'] ) ) . " )
                                                        -- exclude income tax provision account( need to be on a separate query )
                                                        AND ( coa.mocod_c1 IN( 4, 5 ) AND IFNULL( defaultaccounts.incomeTaxProvision, 0 ) = 0 )
                                    GROUP BY gl.idCoa, defaultaccounts.incomeTaxProvision, coa.aname_c30, coa.mocod_c1, coa.chcod_c1, coa.accod_c2, coa.accID
                                )

                                UNION ALL
                                -- Get current month record of the GL
                                (
                                    SELECT
                                        0 as previousYrGLAmount
                                        ,0 as previousYrAccumulatedGLAmount
                                        ,0 as currentUrAccumulatedGLAmount
                                        ,SUM( IFNULL( gl.glAmount, 0 ) ) as currentMonthGLAmount
                                        ,gl.idCoa
                                        ,coa.aname_c30
                                        ,coa.mocod_c1
                                        ,coa.chcod_c1
                                        ,coa.accod_c2
                                        ,coa.accID
                                        ,0 as incomeTaxProvision
                                    FROM gl
                                    JOIN invoices
                                        ON( invoices.idInvoice = gl.idInvoice )
                                    JOIN coa
                                        ON( coa.idCoa = gl.idCoa )
                                    LEFT OUTER JOIN defaultaccounts
                                        ON( defaultaccounts.incomeTaxProvision = gl.idCoa AND " . ( (int)$params['idAffiliate'] > 0? "defaultaccounts.idAffiliate = $params[idAffiliate]" : "defaultaccounts.idAffiliate IN( SELECT idAffiliate FROM employeeaffiliate WHERE idEmployee = $this->EMPLOYEEID )" ) . " )
                                    WHERE
                                        invoices.archived NOT IN( 1 )
                                            $exwhereinner
                                                AND invoices.month = $params[month]
                                                    AND invoices.year = $params[year]
                                                        -- exclude income tax provision account( need to be on a separate query )
                                                        AND ( coa.mocod_c1 IN( 4, 5 ) AND IFNULL( defaultaccounts.incomeTaxProvision, 0 ) = 0 )
                                    GROUP BY gl.idCoa, defaultaccounts.incomeTaxProvision, coa.aname_c30, coa.mocod_c1, coa.chcod_c1, coa.accod_c2, coa.accID
                                )
                            ) as glRecords
                            JOIN coa as coaHeaders
                                ON( coaHeaders.mocod_c1 = glRecords.mocod_c1 AND coaHeaders.chcod_c1 = glRecords.chcod_c1 AND coaHeaders.accod_c2 = glRecords.accod_c2 AND coaHeaders.accountType = 1 )
                            GROUP BY $groupby, glRecords.incomeTaxProvision, glRecords.accID
                        )

                        UNION ALL
                        -- evaluate for income tax provision account
                        (
                            SELECT
                                IFNULL( (
                                    SELECT
                                        SUM( IFNULL( gl.glAmount, 0 ) )
                                    FROM gl
                                    JOIN invoices
                                        ON( invoices.idInvoice = gl.idInvoice )
                                    JOIN defaultaccounts
                                        ON( defaultaccounts.incomeTaxProvision = gl.idCoa )
                                    WHERE
                                        gl.idAffiliate " . ( (int)$params['idAffiliate'] > 0? "= $params[idAffiliate]" : "IN( SELECT idAffiliate FROM employeeaffiliate WHERE idEmployee = $this->EMPLOYEEID )" ) . "
                                            AND ( invoices.month BETWEEN " . date( 'm', strtotime( $params['prevSDate'] ) ) . " AND " . date( 'm', strtotime( $params['prevEDate'] ) ) . " )
                                                AND( invoices.year BETWEEN " . date( 'Y', strtotime( $params['prevSDate'] ) ) . " AND " . date( 'Y', strtotime( $params['prevEDate'] ) ) . " )
                                                    AND invoices.archived NOT IN( 1 )
                                ), 0 ) as previousYrGLAmount
                                ,IFNULL( (
                                    SELECT
                                        SUM( IFNULL( gl.glAmount, 0 ) )
                                    FROM gl
                                    JOIN invoices
                                        ON( invoices.idInvoice = gl.idInvoice )
                                    JOIN defaultaccounts
                                        ON( defaultaccounts.incomeTaxProvision = gl.idCoa )
                                    WHERE
                                        gl.idAffiliate " . ( (int)$params['idAffiliate'] > 0? "= $params[idAffiliate]" : "IN( SELECT idAffiliate FROM employeeaffiliate WHERE idEmployee = $this->EMPLOYEEID )" ) . "
                                            AND ( invoices.month BETWEEN " . date( 'm', strtotime( $params['prevAccSDate'] ) ) . " AND " . date( 'm', strtotime( $params['prevAccEDate'] ) ) . " )
                                                AND( invoices.year BETWEEN " . date( 'Y', strtotime( $params['prevAccSDate'] ) ) . " AND " . date( 'Y', strtotime( $params['prevAccEDate'] ) ) . " )
                                                    AND invoices.archived NOT IN( 1 )
                                ), 0 ) as previousYrAccumulatedGLAmount
                                ,IFNULL( (
                                    SELECT
                                        SUM( IFNULL( gl.glAmount, 0 ) )
                                    FROM gl
                                    JOIN invoices
                                        ON( invoices.idInvoice = gl.idInvoice )
                                    JOIN defaultaccounts
                                        ON( defaultaccounts.incomeTaxProvision = gl.idCoa )
                                    WHERE
                                        gl.idAffiliate " . ( (int)$params['idAffiliate'] > 0? "= $params[idAffiliate]" : "IN( SELECT idAffiliate FROM employeeaffiliate WHERE idEmployee = $this->EMPLOYEEID )" ) . "
                                            AND ( invoices.month BETWEEN " . date( 'm', strtotime( $params['curAccSDate'] ) ) . " AND " . date( 'm', strtotime( $params['prevAccEDate'] ) ) . " )
                                                AND( invoices.year BETWEEN " . date( 'Y', strtotime( $params['curAccSDate'] ) ) . " AND " . date( 'Y', strtotime( $params['prevAccEDate'] ) ) . " )
                                                    AND invoices.archived NOT IN( 1 )
                                ), 0 ) as currentUrAccumulatedGLAmount
                                ,IFNULL( (
                                    SELECT
                                        SUM( IFNULL( gl.glAmount, 0 ) )
                                    FROM gl
                                    JOIN invoices
                                        ON( invoices.idInvoice = gl.idInvoice )
                                    JOIN defaultaccounts
                                        ON( defaultaccounts.incomeTaxProvision = gl.idCoa )
                                    WHERE
                                        gl.idAffiliate " . ( (int)$params['idAffiliate'] > 0? "= $params[idAffiliate]" : "IN( SELECT idAffiliate FROM employeeaffiliate WHERE idEmployee = $this->EMPLOYEEID )" ) . "
                                            AND invoices.month = $params[month]
                                                AND invoices.year = $params[year]
                                                    AND invoices.archived NOT IN( 1 )
                                ), 0 ) as currentMonthGLAmount
                                ,1 as incomeTaxProvision
                                ,0 as idCoa
                                ,'INCOME TAX EXPENSE' as aname_c30
                                ,0 as mocod_c1
                                ,0 as accID
                        )
                    ) as summarizedGLRecords
                )

                -- Revenue
                UNION ALL
                (
                    SELECT
                        0 as previousYrGLAmount
                        ,0 as previousYrAccumulatedGLAmount
                        ,0 as currentYrAccumulatedGLAmount
                        ,0 as currentMonthGLAmount
                        ,'REVENUE' as aname_c30
                        ,0 as sorter
                        ,0 as incDec
                        ,0 as mocod_c1
                )

                -- Total Revenue
                UNION ALL
                (
                    SELECT
                        0 as previousYrGLAmount
                        ,0 as previousYrAccumulatedGLAmount
                        ,0 as currentYrAccumulatedGLAmount
                        ,0 as currentMonthGLAmount
                        ,'TOTAL REVENUE' as aname_c30
                        ,1.1 as sorter
                        ,0 as incDec
                        ,0 as mocod_c1
                )

                -- Cost of Good Sold
                UNION ALL
                (
                    SELECT
                        0 as previousYrGLAmount
                        ,0 as previousYrAccumulatedGLAmount
                        ,0 as currentYrAccumulatedGLAmount
                        ,0 as currentMonthGLAmount
                        ,'COST OF GOODS SOLD' as aname_c30
                        ,1.2 as sorter
                        ,0 as incDec
                        ,0 as mocod_c1
                )

                -- Gross Income
                UNION ALL
                (
                    SELECT
                        0 as previousYrGLAmount
                        ,0 as previousYrAccumulatedGLAmount
                        ,0 as currentYrAccumulatedGLAmount
                        ,0 as currentMonthGLAmount
                        ,'GROSS INCOME' as aname_c30
                        ,1.4 as sorter
                        ,0 as incDec
                        ,0 as mocod_c1
                )

                -- Operating Expenses
                UNION ALL
                (
                    SELECT
                        0 as previousYrGLAmount
                        ,0 as previousYrAccumulatedGLAmount
                        ,0 as currentYrAccumulatedGLAmount
                        ,0 as currentMonthGLAmount
                        ,'OPERATING EXPENSE' as aname_c30
                        ,1.5 as sorter
                        ,0 as incDec
                        ,0 as mocod_c1
                )

                -- Total Operating Expense
                UNION ALL
                (
                    SELECT
                        0 as previousYrGLAmount
                        ,0 as previousYrAccumulatedGLAmount
                        ,0 as currentYrAccumulatedGLAmount
                        ,0 as currentMonthGLAmount
                        ,'TOTAL OPERATING EXPENSE' as aname_c30
                        ,3.1 as sorter
                        ,0 as incDec
                        ,0 as mocod_c1
                )

                -- Operating Income/(Loss)
                UNION ALL
                (
                    SELECT
                        0 as previousYrGLAmount
                        ,0 as previousYrAccumulatedGLAmount
                        ,0 as currentYrAccumulatedGLAmount
                        ,0 as currentMonthGLAmount
                        ,'OPERATING INCOME/(LOSS)' as aname_c30
                        ,3.2 as sorter
                        ,0 as incDec
                        ,0 as mocod_c1
                )

                -- Other Income
                UNION ALL
                (
                    SELECT
                        0 as previousYrGLAmount
                        ,0 as previousYrAccumulatedGLAmount
                        ,0 as currentYrAccumulatedGLAmount
                        ,0 as currentMonthGLAmount
                        ,'OTHER INCOME' as aname_c30
                        ,3.3 as sorter
                        ,0 as incDec
                        ,0 as mocod_c1
                )
                
                -- Income Before Tax
                UNION ALL
                (
                    SELECT
                        0 as previousYrGLAmount
                        ,0 as previousYrAccumulatedGLAmount
                        ,0 as currentYrAccumulatedGLAmount
                        ,0 as currentMonthGLAmount
                        ,'INCOME BEFORE TAX' as aname_c30
                        ,4.1 as sorter
                        ,0 as incDec
                        ,0 as mocod_c1
                )
                
                -- Net Income/(Loss)
                UNION ALL
                (
                    SELECT
                        0 as previousYrGLAmount
                        ,0 as previousYrAccumulatedGLAmount
                        ,0 as currentYrAccumulatedGLAmount
                        ,0 as currentMonthGLAmount
                        ,'NET INCOME/(LOSS)' as aname_c30
                        ,6 as sorter
                        ,0 as incDec
                        ,0 as mocod_c1
                )
            ) as main
            JOIN(
                SELECT
                    @totalRevenue := 0,
                    @totalRevenue1 := 0,
                    @totalRevenue2 := 0,
                    @totalRevenue3 := 0,
                    
                    @totalGoodsSold := 0,
                    @totalGoodsSold1 := 0,
                    @totalGoodsSold2 := 0,
                    @totalGoodsSold3 := 0,
                    
                    @totalOperatingExpense := 0,
                    @totalOperatingExpense1 := 0,
                    @totalOperatingExpense2 := 0,
                    @totalOperatingExpense3 := 0,
                    
                    @totalIncomeBeforeTax := 0,
                    @totalIncomeBeforeTax1 := 0,
                    @totalIncomeBeforeTax2 := 0,
                    @totalIncomeBeforeTax3 := 0,

                    @incomeTaxExpense := 0,
                    @incomeTaxExpense1 := 0,
                    @incomeTaxExpense2 := 0,
                    @incomeTaxExpense3 := 0
            ) as totals
                ON( 1 = 1 )
            ORDER BY main.sorter
        " )->result_array();
    }

    public function processBalanceSheet( $params ){
        $exwhereinner   = "";
        $exwhereouter   = "";
        $groupby        = "";
        $orderBy        = "";
        $sorter         = "";

        if( (int)$params['idAffiliate'] == 0 ) $exwhereinner .= "AND invoices.idAffiliate IN( SELECT idAffiliate FROM employeeaffiliate WHERE idEmployee = $this->EMPLOYEEID )";
        else $exwhereinner .= "AND invoices.idAffiliate = $params[idAffiliate]";

        if( (int)$params['displayType'] == 2 ){
            $groupby    .= "coaHeaders.idCoa, coaHeaders.aname_c30, coaHeaders.mocod_c1, coaHeaders.chcod_c1, coaHeaders.accod_c2, coaHeaders.acod_c15, coaHeaders.norm_c2";
            $sorter     .= "CONCAT( coaHeaders.mocod_c1, coaHeaders.chcod_c1, '.00' )";
            $orderBy    .= "coaHeaders.acod_c15";
        }
        else{
            $groupby    .= "innerMain.idCoa, innerMain.aname_c30, innerMain.mocod_c1, innerMain.chcod_c1, innerMain.accod_c2, innerMain.acod_c15, innerMain.norm_c2";
            $sorter     .= "CONCAT( innerMain.mocod_c1, innerMain.chcod_c1, '.00' )";
            $orderBy    .= "innerMain.acod_c15";
        }

        return $this->db->query( "
            SELECT
                IF( main.sorter = 11, ( @totalCurrentAssets := @totalCurrentAssets + IFNULL( main.currentMonthAmount, 0 ) ) ,@totalCurrentAssets )
                ,IF( main.sorter = 11, ( @totalInvesting := @totalCurrentAssets1 + IFNULL( main.prevYearCurrentMonth, 0 ) ) ,@totalCurrentAssets1 )
                ,IF( main.sorter = 11, ( @totalCurrentAssets2 := @totalCurrentAssets2 + IFNULL( main.currentYearStartMonth, 0 ) ) ,@totalCurrentAssets2 )

                ,IF( main.sorter = 12, ( @totalNonCurrentAssets := @totalNonCurrentAssets + IFNULL( main.currentMonthAmount, 0 ) ) ,@totalNonCurrentAssets )
                ,IF( main.sorter = 12, ( @totalNonCurrentAssets1 := @totalNonCurrentAssets1 + IFNULL( main.prevYearCurrentMonth, 0 ) ) ,@totalNonCurrentAssets1 )
                ,IF( main.sorter = 12, ( @totalNonCurrentAssets2 := @totalNonCurrentAssets2 + IFNULL( main.currentYearStartMonth, 0 ) ) ,@totalNonCurrentAssets2 )

                ,IF( main.sorter = 13, ( @totalOtherAssets := @totalOtherAssets + IFNULL( main.currentMonthAmount, 0 ) ) ,@totalOtherAssets )
                ,IF( main.sorter = 13, ( @totalOtherAssets1 := @totalOtherAssets1 + IFNULL( main.prevYearCurrentMonth, 0 ) ) ,@totalOtherAssets1 )
                ,IF( main.sorter = 13, ( @totalOtherAssets2 := @totalOtherAssets2 + IFNULL( main.currentYearStartMonth, 0 ) ) ,@totalOtherAssets2 )

                ,IF( main.sorter = 21, ( @totalCurrentLiabilities := @totalCurrentLiabilities + IFNULL( main.currentMonthAmount, 0 ) ) ,@totalCurrentLiabilities )
                ,IF( main.sorter = 21, ( @totalCurrentLiabilities1 := @totalCurrentLiabilities1 + IFNULL( main.prevYearCurrentMonth, 0 ) ) ,@totalCurrentLiabilities1 )
                ,IF( main.sorter = 21, ( @totalCurrentLiabilities2 := @totalCurrentLiabilities2 + IFNULL( main.currentYearStartMonth, 0 ) ) ,@totalCurrentLiabilities2 )

                ,IF( main.sorter = 22, ( @totalNonCurrentLiabilities := @totalNonCurrentLiabilities + IFNULL( main.currentMonthAmount, 0 ) ) ,@totalNonCurrentLiabilities )
                ,IF( main.sorter = 22, ( @totalNonCurrentLiabilities1 := @totalNonCurrentLiabilities1 + IFNULL( main.prevYearCurrentMonth, 0 ) ) ,@totalNonCurrentLiabilities1 )
                ,IF( main.sorter = 22, ( @totalNonCurrentLiabilities2 := @totalNonCurrentLiabilities2 + IFNULL( main.currentYearStartMonth, 0 ) ) ,@totalNonCurrentLiabilities2 )

                ,IF( main.sorter = 23, ( @totalOtherLiabilities := @totalOtherLiabilities + IFNULL( main.currentMonthAmount, 0 ) ) ,@totalOtherLiabilities )
                ,IF( main.sorter = 23, ( @totalOtherLiabilities1 := @totalOtherLiabilities1 + IFNULL( main.prevYearCurrentMonth, 0 ) ) ,@totalOtherLiabilities1 )
                ,IF( main.sorter = 23, ( @totalOtherLiabilities2 := @totalOtherLiabilities2 + IFNULL( main.currentYearStartMonth, 0 ) ) ,@totalOtherLiabilities2 )

                ,IF( main.sorter = 31, ( @totalStockHoldersEquity := @totalStockHoldersEquity + IFNULL( main.currentMonthAmount, 0 ) ) ,@totalStockHoldersEquity )
                ,IF( main.sorter = 31, ( @totalStockHoldersEquity1 := @totalStockHoldersEquity1 + IFNULL( main.prevYearCurrentMonth, 0 ) ) ,@totalStockHoldersEquity1 )
                ,IF( main.sorter = 31, ( @totalStockHoldersEquity2 := @totalStockHoldersEquity2 + IFNULL( main.currentYearStartMonth, 0 ) ) ,@totalStockHoldersEquity2 )
                
                ,(CASE 
					WHEN main.sorter = 11.4 THEN
						@totalCurrentAssets
					WHEN main.sorter = 12.4 THEN
						@totalNonCurrentAssets
					WHEN main.sorter = 13.4 THEN
						@totalOtherAssets
					WHEN main.sorter = 13.5 THEN
						@totalCurrentAssets + @totalNonCurrentAssets + @totalOtherAssets
					WHEN main.sorter = 21.4 THEN
						@totalCurrentLiabilities
					WHEN main.sorter = 22.4 THEN
						@totalNonCurrentLiabilities
					WHEN main.sorter = 23.4 THEN
						@totalOtherLiabilities
					WHEN main.sorter = 23.5 THEN
						@totalCurrentLiabilities + @totalNonCurrentLiabilities + @totalOtherLiabilities
					WHEN main.sorter = 31.4 THEN
						@totalStockHoldersEquity
					WHEN main.sorter = 31.5 THEN
						( @totalCurrentLiabilities + @totalNonCurrentLiabilities + @totalOtherLiabilities ) + @totalStockHoldersEquity
					ELSE
                        IFNULL( main.currentMonthAmount, 0 )
				END) AS currentMonthAmount
				,(CASE 
					WHEN main.sorter = 11.4 THEN
						@totalCurrentAssets1
					WHEN main.sorter = 12.4 THEN
						@totalNonCurrentAssets1
					WHEN main.sorter = 13.4 THEN
						@totalOtherAssets1
					WHEN main.sorter = 13.5 THEN
						@totalCurrentAssets1 + @totalNonCurrentAssets1 + @totalOtherAssets1
					WHEN main.sorter = 21.4 THEN
						@totalCurrentLiabilities1
					WHEN main.sorter = 22.4 THEN
						@totalNonCurrentLiabilities1
					WHEN main.sorter = 23.4 THEN
						@totalOtherLiabilities1
					WHEN main.sorter = 23.5 THEN
						@totalCurrentLiabilities1 + @totalNonCurrentLiabilities1 + @totalOtherLiabilities1
					WHEN main.sorter = 31.4 THEN
						@totalStockHoldersEquity1
					WHEN main.sorter = 31.5 THEN
						( @totalCurrentLiabilities1 + @totalNonCurrentLiabilities1 + @totalOtherLiabilities1 ) + @totalStockHoldersEquity1
					ELSE
                        IFNULL( main.prevYearCurrentMonth, 0 )
				END) AS prevYearCurrentMonth
				,(CASE 
					WHEN main.sorter = 11.4 THEN
						@totalCurrentAssets2
					WHEN main.sorter = 12.4 THEN
						@totalNonCurrentAssets2
					WHEN main.sorter = 13.4 THEN
						@totalOtherAssets2
					WHEN main.sorter = 13.5 THEN
						@totalCurrentAssets2 + @totalNonCurrentAssets2 + @totalOtherAssets2
					WHEN main.sorter = 21.4 THEN
						@totalCurrentLiabilities2
					WHEN main.sorter = 22.4 THEN
						@totalNonCurrentLiabilities2
					WHEN main.sorter = 23.4 THEN
						@totalOtherLiabilities2
					WHEN main.sorter = 23.5 THEN
						@totalCurrentLiabilities2 + @totalNonCurrentLiabilities2 + @totalOtherLiabilities2
					WHEN main.sorter = 31.4 THEN
						@totalStockHoldersEquity2
					WHEN main.sorter = 31.5 THEN
						( @totalCurrentLiabilities2 + @totalNonCurrentLiabilities2 + @totalOtherLiabilities2 ) + @totalStockHoldersEquity2
					ELSE
                        IFNULL( main.currentYearStartMonth, 0 )
				END) AS currentYearStartMonth
                ,main.aname_c30
                ,main.acod_c15
                ,main.sorter
            FROM(
                ( -- inner main query used to retrieve records and sum all same record, in preparation for display
                    SELECT
                        (CASE
                            WHEN " . ( (int)$params['displayType'] == 2? "coaHeaders" : "innerMain" ) . ".mocod_c1 = 1 AND " . ( (int)$params['displayType'] == 2? "coaHeaders" : "innerMain" ) . ".norm_c2 = 'CR' THEN
                                 SUM( IFNULL( innerMain.currentMonthAmount, 0 ) ) * -1
                            WHEN ( " . ( (int)$params['displayType'] == 2? "coaHeaders" : "innerMain" ) . ".mocod_c1 = 2 OR " . ( (int)$params['displayType'] == 2? "coaHeaders" : "innerMain" ) . ".mocod_c1 = 3 ) AND " . ( (int)$params['displayType'] == 2? "coaHeaders" : "innerMain" ) . ".norm_c2 = 'DR' THEN
                                 SUM( IFNULL( innerMain.currentMonthAmount, 0 ) ) * -1
                            ELSE
                                 SUM( IFNULL( innerMain.currentMonthAmount, 0 ) )
                        END) AS currentMonthAmount
                        ,(CASE
                            WHEN " . ( (int)$params['displayType'] == 2? "coaHeaders" : "innerMain" ) . ".mocod_c1 = 1 AND " . ( (int)$params['displayType'] == 2? "coaHeaders" : "innerMain" ) . ".norm_c2 = 'CR' THEN
                                 SUM( IFNULL( innerMain.prevYearCurrentMonth, 0 ) ) * -1
                            WHEN ( " . ( (int)$params['displayType'] == 2? "coaHeaders" : "innerMain" ) . ".mocod_c1 = 2 OR " . ( (int)$params['displayType'] == 2? "coaHeaders" : "innerMain" ) . ".mocod_c1 = 3 ) AND " . ( (int)$params['displayType'] == 2? "coaHeaders" : "innerMain" ) . ".norm_c2 = 'DR' THEN
                                 SUM( IFNULL( innerMain.prevYearCurrentMonth, 0 ) ) * -1
                            ELSE
                                 SUM( IFNULL( innerMain.prevYearCurrentMonth, 0 ) )
                        END) AS prevYearCurrentMonth
                        ,(CASE
                            WHEN " . ( (int)$params['displayType'] == 2? "coaHeaders" : "innerMain" ) . ".mocod_c1 = 1 AND " . ( (int)$params['displayType'] == 2? "coaHeaders" : "innerMain" ) . ".norm_c2 = 'CR' THEN
                                 SUM( IFNULL( innerMain.currentYearStartMonth, 0 ) ) * -1
                            WHEN ( " . ( (int)$params['displayType'] == 2? "coaHeaders" : "innerMain" ) . ".mocod_c1 = 2 OR " . ( (int)$params['displayType'] == 2? "coaHeaders" : "innerMain" ) . ".mocod_c1 = 3 ) AND " . ( (int)$params['displayType'] == 2? "coaHeaders" : "innerMain" ) . ".norm_c2 = 'DR' THEN
                                 SUM( IFNULL( innerMain.currentYearStartMonth, 0 ) ) * -1
                            ELSE
                                 SUM( IFNULL( innerMain.currentYearStartMonth, 0 ) )
                        END) AS currentYearStartMonth
                        ,$groupby
                        ,$sorter as sorter
                    FROM(
                        ( -- get the current month results
                            SELECT
                                SUM( IFNULL( gl.glAmount, 0 ) ) as currentMonthAmount
                                ,0 as prevYearCurrentMonth
                                ,0 as currentYearStartMonth
                                ,gl.idCoa
                                ,coa.mocod_c1
                                ,coa.chcod_c1
                                ,coa.accod_c2
                                ,coa.aname_c30
                                ,coa.acod_c15
                                ,coa.norm_c2
                            FROM gl
                            JOIN invoices
                                ON( invoices.idInvoice = gl.idInvoice )
                            LEFT OUTER JOIN coa
                                ON( coa.idCoa = gl.idCoa )
                            WHERE
                                invoices.month = $params[month]
                                    AND invoices.year = $params[year]
                                        AND invoices.archived NOT IN( 1 )
                                            AND coa.mocod_c1 IN( 1, 2, 3 )
                                                $exwhereinner
                            GROUP BY gl.idCoa ,coa.mocod_c1 ,coa.chcod_c1 ,coa.accod_c2, coa.aname_c30, coa.acod_c15, norm_c2
                        )

                        UNION ALL
                        ( -- get the previous year same month result
                            SELECT
                                0 as currentMonthAmount
                                ,SUM( IFNULL( gl.glAmount, 0 ) ) as prevYearCurrentMonth
                                ,0 as currentYearStartMonth
                                ,gl.idCoa
                                ,coa.mocod_c1
                                ,coa.chcod_c1
                                ,coa.accod_c2
                                ,coa.aname_c30
                                ,coa.acod_c15
                                ,coa.norm_c2
                            FROM gl
                            JOIN invoices
                                ON( invoices.idInvoice = gl.idInvoice )
                            LEFT OUTER JOIN coa
                                ON( coa.idCoa = gl.idCoa )
                            WHERE
                                invoices.month = $params[month]
                                    AND invoices.year = ( $params[year] - 1 )
                                        AND invoices.archived NOT IN( 1 )
                                            AND coa.mocod_c1 IN( 1, 2, 3 )
                                                $exwhereinner
                            GROUP BY gl.idCoa ,coa.mocod_c1 ,coa.chcod_c1 ,coa.accod_c2, coa.aname_c30, coa.acod_c15, norm_c2
                        )

                        UNION ALL
                        ( -- get the previous year same month result
                            SELECT
                                0 as currentMonthAmount
                                ,0 as prevYearCurrentMonth
                                ,SUM( IFNULL( gl.glAmount, 0 ) ) as currentYearStartMonth
                                ,gl.idCoa
                                ,coa.mocod_c1
                                ,coa.chcod_c1
                                ,coa.accod_c2
                                ,coa.aname_c30
                                ,coa.acod_c15
                                ,coa.norm_c2
                            FROM gl
                            JOIN invoices
                                ON( invoices.idInvoice = gl.idInvoice )
                            LEFT OUTER JOIN coa
                                ON( coa.idCoa = gl.idCoa )
                            WHERE
                                invoices.month = " . date( 'm', strtotime( $params['prevEDate'] ) ) . "
                                    AND invoices.year = " . date( 'Y', strtotime( $params['prevEDate'] ) ) . "
                                        AND invoices.archived NOT IN( 1 )
                                            AND coa.mocod_c1 IN( 1, 2, 3 )
                                                $exwhereinner
                            GROUP BY gl.idCoa ,coa.mocod_c1 ,coa.chcod_c1 ,coa.accod_c2, coa.aname_c30, coa.acod_c15, norm_c2
                        )
                    ) as innerMain
                    JOIN coa as coaHeaders
                        ON( coaHeaders.mocod_c1 = innerMain.mocod_c1 AND coaHeaders.chcod_c1 = innerMain.chcod_c1 AND coaHeaders.accod_c2 = innerMain.accod_c2 AND coaHeaders.accountType = 1 )
                    GROUP BY $groupby
                    ORDER BY $orderBy
                )

                -- This line here starts the division on based on headers in the report
                UNION ALL
                -- ASSET
                (
                    SELECT
                        0 as currentMonthAmount
                        ,0 as prevYearCurrentMonth
                        ,0 as currentYearStartMonth
                        ,0 as idCoa
                        ,'ASSET' as aname_c30
                        ,0 as mocod_c1
                        ,0 as chcod_c1
                        ,0 as accod_c2
                        ,0 as acod_c15
                        ,'' as norm_c2
                        ,0.00 as sorter
                )

                UNION ALL
                -- Current Asset
                (
                    SELECT
                        0 as currentMonthAmount
                        ,0 as prevYearCurrentMonth
                        ,0 as currentYearStartMonth
                        ,0 as idCoa
                        ,'CURRENT ASSET' as aname_c30
                        ,0 as mocod_c1
                        ,0 as chcod_c1
                        ,0 as accod_c2
                        ,0 as acod_c15
                        ,'' as norm_c2
                        ,0.05 as sorter
                )

                UNION ALL
                -- Total Current Asset
                (
                    SELECT
                        0 as currentMonthAmount
                        ,0 as prevYearCurrentMonth
                        ,0 as currentYearStartMonth
                        ,0 as idCoa
                        ,'TOTAL CURRENT ASSET' as aname_c30
                        ,0 as mocod_c1
                        ,0 as chcod_c1
                        ,0 as accod_c2
                        ,0 as acod_c15
                        ,'' as norm_c2
                        ,11.40 as sorter
                )

                UNION ALL
                -- Non-Current Asset
                (
                    SELECT
                        0 as currentMonthAmount
                        ,0 as prevYearCurrentMonth
                        ,0 as currentYearStartMonth
                        ,0 as idCoa
                        ,'NON-CURRENT ASSETS' as aname_c30
                        ,0 as mocod_c1
                        ,0 as chcod_c1
                        ,0 as accod_c2
                        ,0 as acod_c15
                        ,'' as norm_c2
                        ,11.50 as sorter
                )

                UNION ALL
                -- Total Non-Current Asset
                (
                    SELECT
                        0 as currentMonthAmount
                        ,0 as prevYearCurrentMonth
                        ,0 as currentYearStartMonth
                        ,0 as idCoa
                        ,'TOTAL NON-CURRENT ASSETS' as aname_c30
                        ,0 as mocod_c1
                        ,0 as chcod_c1
                        ,0 as accod_c2
                        ,0 as acod_c15
                        ,'' as norm_c2
                        ,12.40 as sorter
                )

                UNION ALL
                -- Other Asset
                (
                    SELECT
                        0 as currentMonthAmount
                        ,0 as prevYearCurrentMonth
                        ,0 as currentYearStartMonth
                        ,0 as idCoa
                        ,'OTHER ASSETS' as aname_c30
                        ,0 as mocod_c1
                        ,0 as chcod_c1
                        ,0 as accod_c2
                        ,0 as acod_c15
                        ,'' as norm_c2
                        ,12.50 as sorter
                )

                UNION ALL
                -- Total Other Asset
                (
                    SELECT
                        0 as currentMonthAmount
                        ,0 as prevYearCurrentMonth
                        ,0 as currentYearStartMonth
                        ,0 as idCoa
                        ,'TOTAL OTHER ASSETS' as aname_c30
                        ,0 as mocod_c1
                        ,0 as chcod_c1
                        ,0 as accod_c2
                        ,0 as acod_c15
                        ,'' as norm_c2
                        ,13.40 as sorter
                )

                UNION ALL
                -- Total ASSETS
                (
                    SELECT
                        0 as currentMonthAmount
                        ,0 as prevYearCurrentMonth
                        ,0 as currentYearStartMonth
                        ,0 as idCoa
                        ,'TOTAL ASSETS' as aname_c30
                        ,0 as mocod_c1
                        ,0 as chcod_c1
                        ,0 as accod_c2
                        ,0 as acod_c15
                        ,'' as norm_c2
                        ,13.50 as sorter
                )

                UNION ALL
                -- Liabilities and Equity
                (
                    SELECT
                        0 as currentMonthAmount
                        ,0 as prevYearCurrentMonth
                        ,0 as currentYearStartMonth
                        ,0 as idCoa
                        ,'LIABILITIES AND EQUITY' as aname_c30
                        ,0 as mocod_c1
                        ,0 as chcod_c1
                        ,0 as accod_c2
                        ,0 as acod_c15
                        ,'' as norm_c2
                        ,20.00 as sorter
                )

                UNION ALL
                -- Current Liabilities
                (
                    SELECT
                        0 as currentMonthAmount
                        ,0 as prevYearCurrentMonth
                        ,0 as currentYearStartMonth
                        ,0 as idCoa
                        ,'CURRENT LIABILITIES' as aname_c30
                        ,0 as mocod_c1
                        ,0 as chcod_c1
                        ,0 as accod_c2
                        ,0 as acod_c15
                        ,'' as norm_c2
                        ,20.40 as sorter
                )

                UNION ALL
                -- Total Current Liabilities
                (
                    SELECT
                        0 as currentMonthAmount
                        ,0 as prevYearCurrentMonth
                        ,0 as currentYearStartMonth
                        ,0 as idCoa
                        ,'TOTAL CURRENT LIABILITIES' as aname_c30
                        ,0 as mocod_c1
                        ,0 as chcod_c1
                        ,0 as accod_c2
                        ,0 as acod_c15
                        ,'' as norm_c2
                        ,21.40 as sorter
                )

                UNION ALL
                -- Non-Current Liabilities
                (
                    SELECT
                        0 as currentMonthAmount
                        ,0 as prevYearCurrentMonth
                        ,0 as currentYearStartMonth
                        ,0 as idCoa
                        ,'NON-CURRENT LIABILITIES' as aname_c30
                        ,0 as mocod_c1
                        ,0 as chcod_c1
                        ,0 as accod_c2
                        ,0 as acod_c15
                        ,'' as norm_c2
                        ,21.50 as sorter
                )

                UNION ALL
                -- Total Non-Current Liabilities
                (
                    SELECT
                        0 as currentMonthAmount
                        ,0 as prevYearCurrentMonth
                        ,0 as currentYearStartMonth
                        ,0 as idCoa
                        ,'TOTAL NON-CURRENT LIABILITIES' as aname_c30
                        ,0 as mocod_c1
                        ,0 as chcod_c1
                        ,0 as accod_c2
                        ,0 as acod_c15
                        ,'' as norm_c2
                        ,22.40 as sorter
                )

                UNION ALL
                -- Other Liabilities
                (
                    SELECT
                        0 as currentMonthAmount
                        ,0 as prevYearCurrentMonth
                        ,0 as currentYearStartMonth
                        ,0 as idCoa
                        ,'OTHER LIABILITIES' as aname_c30
                        ,0 as mocod_c1
                        ,0 as chcod_c1
                        ,0 as accod_c2
                        ,0 as acod_c15
                        ,'' as norm_c2
                        ,22.50 as sorter
                )

                UNION ALL
                -- Total Other Liabilities
                (
                    SELECT
                        0 as currentMonthAmount
                        ,0 as prevYearCurrentMonth
                        ,0 as currentYearStartMonth
                        ,0 as idCoa
                        ,'TOTAL OTHER LIABILITIES' as aname_c30
                        ,0 as mocod_c1
                        ,0 as chcod_c1
                        ,0 as accod_c2
                        ,0 as acod_c15
                        ,'' as norm_c2
                        ,23.40 as sorter
                )

                UNION ALL
                -- Total Liabilities
                (
                    SELECT
                        0 as currentMonthAmount
                        ,0 as prevYearCurrentMonth
                        ,0 as currentYearStartMonth
                        ,0 as idCoa
                        ,'TOTAL LIABILITIES' as aname_c30
                        ,0 as mocod_c1
                        ,0 as chcod_c1
                        ,0 as accod_c2
                        ,0 as acod_c15
                        ,'' as norm_c2
                        ,23.50 as sorter
                )

                UNION ALL
                -- Stockholders Equity
                (
                    SELECT
                        0 as currentMonthAmount
                        ,0 as prevYearCurrentMonth
                        ,0 as currentYearStartMonth
                        ,0 as idCoa
                        ,'STOCKHOLDERS EQUITY' as aname_c30
                        ,0 as mocod_c1
                        ,0 as chcod_c1
                        ,0 as accod_c2
                        ,0 as acod_c15
                        ,'' as norm_c2
                        ,30.40 as sorter
                )

                UNION ALL
                -- Total Stockholders Equity
                (
                    SELECT
                        0 as currentMonthAmount
                        ,0 as prevYearCurrentMonth
                        ,0 as currentYearStartMonth
                        ,0 as idCoa
                        ,'TOTAL STOCKHOLDERS EQUITY' as aname_c30
                        ,0 as mocod_c1
                        ,0 as chcod_c1
                        ,0 as accod_c2
                        ,0 as acod_c15
                        ,'' as norm_c2
                        ,31.40 as sorter
                )

                UNION ALL
                -- Liabilities AND Stockholders Equity
                (
                    SELECT
                        0 as currentMonthAmount
                        ,0 as prevYearCurrentMonth
                        ,0 as currentYearStartMonth
                        ,0 as idCoa
                        ,'LIABILTIES AND STOCKHOLDERS EQUITY' as aname_c30
                        ,0 as mocod_c1
                        ,0 as chcod_c1
                        ,0 as accod_c2
                        ,0 as acod_c15
                        ,'' as norm_c2
                        ,31.50 as sorter
                )
            ) as main
            LEFT OUTER JOIN(
				SELECT 
					@totalCurrentAssets := 0,
					@totalCurrentAssets1 := 0,
					@totalCurrentAssets2 := 0,

					@totalNonCurrentAssets := 0,
					@totalNonCurrentAssets1 := 0,
					@totalNonCurrentAssets2 := 0,

					@totalOtherAssets := 0,
					@totalOtherAssets1 := 0,
					@totalOtherAssets2 := 0,

					@totalCurrentLiabilities := 0,
					@totalCurrentLiabilities1 := 0,
					@totalCurrentLiabilities2 := 0,

					@totalNonCurrentLiabilities := 0,
					@totalNonCurrentLiabilities1 := 0,
					@totalNonCurrentLiabilities2 := 0,

					@totalOtherLiabilities := 0,
					@totalOtherLiabilities1 := 0,
					@totalOtherLiabilities2 := 0,

					@totalStockHoldersEquity := 0,
					@totalStockHoldersEquity1 := 0,
					@totalStockHoldersEquity2 := 0
			) AS b ON( 1 = 1 )
            ORDER BY main.sorter, main.acod_c15
        " )->result_array();
    }

    public function processCashFlow( $params ){
        
        $groupby        = "";
        $select         = "";
        if( (int)$params['displayType'] == 2 ){
            $select    .= "coaHeaders.idCoa, coaHeaders.aname_c30, coaHeaders.mocod_c1, coaHeaders.chcod_c1, coaHeaders.accod_c2, coaHeaders.acod_c15
                            ,(CASE coaHeaders.cashflow_classification
                                WHEN 3 THEN 1.0
                                WHEN 1 THEN 3.0
                                ELSE 2.0
                            END) as sorter";
            $groupby    .= "coaHeaders.idCoa, coaHeaders.aname_c30, coaHeaders.mocod_c1, coaHeaders.chcod_c1, coaHeaders.accod_c2, coaHeaders.acod_c15, coaHeaders.cashflow_classification";
        }
        else{
            $select    .= "innerMain.idCoa, innerMain.aname_c30, innerMain.mocod_c1, innerMain.chcod_c1, innerMain.accod_c2, innerMain.acod_c15
                            ,(CASE innerMain.cashflow_classification
                                WHEN 3 THEN 1.0
                                WHEN 1 THEN 3.0
                                ELSE 2.0
                            END) as sorter";
            $groupby    .= "innerMain.idCoa, innerMain.aname_c30, innerMain.mocod_c1, innerMain.chcod_c1, innerMain.accod_c2, innerMain.acod_c15, innerMain.cashflow_classification";
        }
        return $this->db->query( "
            SELECT
                -- current year
                IF( main.sorter = 1.0, ( @totalOperating := @totalOperating + IFNULL( main.currentYearRecord, 0 ) ) ,@totalOperating )
                ,IF( main.sorter = 2.0, ( @totalInvesting := @totalInvesting + IFNULL( main.currentYearRecord, 0 ) ) ,@totalInvesting )
                ,IF( main.sorter = 3.0, ( @totalFinancing := @totalFinancing + IFNULL( main.currentYearRecord, 0 ) ) ,@totalFinancing )

                -- previous year
                ,IF( main.sorter = 1.0, ( @totalPrevOperating := @totalPrevOperating + IFNULL( main.prevYearRecord, 0 ) ) ,@totalPrevOperating )
                ,IF( main.sorter = 2.0, ( @totalPrevInvesting := @totalPrevInvesting + IFNULL( main.prevYearRecord, 0 ) ) ,@totalPrevInvesting )
                ,IF( main.sorter = 3.0, ( @totalPrevFinancing := @totalPrevFinancing + IFNULL( main.prevYearRecord, 0 ) ) ,@totalPrevFinancing )

                -- beginning total
                ,@totalBeginning := @totalBeginning + IFNULL( main.beginningBalance, 0 ) as totalBeginning

                ,main.aname_c30
                ,(CASE
                    WHEN main.sorter = 1.2 THEN @totalPrevOperating
                    WHEN main.sorter = 2.2 THEN @totalPrevInvesting
                    WHEN main.sorter = 3.2 THEN @totalPrevFinancing
                    WHEN main.sorter = 3.3 THEN ( @totalPrevOperating + @totalPrevInvesting + @totalPrevFinancing )
                    ELSE IFNULL( main.prevYearRecord, 0 )
                END) as prevYearRecord
                ,(CASE
                    WHEN main.sorter = 1.2 THEN @totalOperating
                    WHEN main.sorter = 2.2 THEN @totalInvesting
                    WHEN main.sorter = 3.2 THEN @totalFinancing
                    WHEN main.sorter = 3.3 THEN ( @totalOperating + @totalInvesting + @totalFinancing )
                    ELSE IFNULL( main.currentYearRecord, 0 )
                END) as currentYearRecord
                ,main.sorter
            FROM(
                ( -- sum all with same account in preparation for report printing
                    SELECT
                        SUM( IFNULL( innerMain.beginningBalance, 0 ) ) as beginningBalance
                        ,SUM( IFNULL( innerMain.prevYearRecord, 0 ) ) as prevYearRecord
                        ,SUM( IFNULL( innerMain.currentYearRecord, 0 ) ) as currentYearRecord
                        ,$select
                    FROM(
                        ( --  get beginning records
                            SELECT
                                SUM( IFNULL( gl.glAmount, 0 ) ) as beginningBalance
                                ,0 as prevYearRecord
                                ,0 as currentYearRecord
                                ,gl.idCoa
                                ,coa.cashflow_classification
                                ,coa.aname_c30
                                ,coa.mocod_c1
                                ,coa.chcod_c1
                                ,coa.accod_c2
                                ,coa.acod_c15
                            FROM gl
                            JOIN invoices
                                ON( invoices.idInvoice = gl.idInvoice )
                            LEFT OUTER JOIN coa
                                ON( coa.idCoa = gl.idCoa )
                            WHERE
                                invoices.archived NOT IN( 1 )
                                    AND IFNULL( coa.cashflow_classification, 0 ) > 0
                                        AND gl.month = MONTH( '$params[prevEDate]' )
                                            AND gl.glYear = ( YEAR( '$params[prevEDate]' ) - 1 )
                                        -- AND ( gl.month < MONTH( '$params[prevSDate]' ) AND gl.glYear <= YEAR( '$params[prevSDate]' ) )
                            GROUP BY gl.idCoa, coa.cashflow_classification, coa.aname_c30, coa.mocod_c1, coa.chcod_c1, coa.accod_c2, coa.acod_c15
                        )

                        UNION ALL
                        -- get previous record
                        (
                            SELECT
                                0 as beginningBalance
                                ,SUM( IFNULL( gl.glAmount, 0 ) ) as prevYearRecord
                                ,0 as currentYearRecord
                                ,gl.idCoa
                                ,coa.cashflow_classification
                                ,coa.aname_c30
                                ,coa.mocod_c1
                                ,coa.chcod_c1
                                ,coa.accod_c2
                                ,coa.acod_c15
                            FROM gl
                            JOIN invoices
                                ON( invoices.idInvoice = gl.idInvoice )
                            LEFT OUTER JOIN coa
                                ON( coa.idCoa = gl.idCoa )
                            WHERE
                                invoices.archived NOT IN( 1 )
                                    AND IFNULL( coa.cashflow_classification, 0 ) > 0
                                        AND gl.month = MONTH( '$params[prevEDate]' )
                                            AND gl.glYear = YEAR( '$params[prevEDate]' )
                                        -- AND ( ( gl.month BETWEEN MONTH( '$params[prevSDate]' ) AND MONTH( '$params[prevEDate]' ) )
                                           -- AND ( gl.glYear BETWEEN YEAR( '$params[prevSDate]' ) AND YEAR( '$params[prevEDate]' ) ) )
                            GROUP BY gl.idCoa, coa.cashflow_classification, coa.aname_c30, coa.mocod_c1, coa.chcod_c1, coa.accod_c2, coa.acod_c15
                        )

                        UNION ALL
                        -- get current records
                        (
                            SELECT
                                0 as beginningBalance
                                ,0 as prevYearRecord
                                ,SUM( IFNULL( gl.glAmount, 0 ) ) as currentYearRecord
                                ,gl.idCoa
                                ,coa.cashflow_classification
                                ,coa.aname_c30
                                ,coa.mocod_c1
                                ,coa.chcod_c1
                                ,coa.accod_c2
                                ,coa.acod_c15
                            FROM gl
                            JOIN invoices
                                ON( invoices.idInvoice = gl.idInvoice )
                            LEFT OUTER JOIN coa
                                ON( coa.idCoa = gl.idCoa )
                            WHERE
                                invoices.archived NOT IN( 1 )
                                    AND IFNULL( coa.cashflow_classification, 0 ) > 0
                                        AND gl.month = $params[month]
                                            AND gl.glYear = $params[year]
                                        -- AND ( ( gl.month BETWEEN MONTH( '$params[curAccSDate]' ) AND MONTH( '$params[curAccEDate]' ) )
                                           -- AND ( gl.glYear BETWEEN YEAR( '$params[curAccSDate]' ) AND YEAR( '$params[curAccEDate]' ) ) )
                            GROUP BY gl.idCoa, coa.cashflow_classification, coa.aname_c30, coa.mocod_c1, coa.chcod_c1, coa.accod_c2, coa.acod_c15
                        )
                    ) as innerMain
                    JOIN coa as coaHeaders
                        ON( coaHeaders.mocod_c1 = innerMain.mocod_c1 AND coaHeaders.chcod_c1 = innerMain.chcod_c1 AND coaHeaders.accod_c2 = innerMain.accod_c2 AND coaHeaders.accountType = 1 )
                    GROUP BY $groupby
                )

                UNION ALL
                -- Cash flow from operating activities
                (
                    SELECT
                        0 as beginningBalance
                        ,0 as prevYearRecord
                        ,0 as currentYearRecord
                        ,0 as idCoa
                        ,'CASH FLOW FROM OPERATING ACTIVITIES' as aname_c30
                        ,0 as mocod_c1
                        ,0 as chcod_c1
                        ,0 as accod_c2
                        ,0 as acod_c15
                        ,0.0 as sorter
                )

                UNION ALL
                -- Total cash flow from operating activities
                (
                    SELECT
                        0 as beginningBalance
                        ,0 as prevYearRecord
                        ,0 as currentYearRecord
                        ,0 as idCoa
                        ,'' as aname_c30
                        ,0 as mocod_c1
                        ,0 as chcod_c1
                        ,0 as accod_c2
                        ,0 as acod_c15
                        ,1.2 as sorter
                )

                UNION ALL
                -- Cash flow from investing activities
                (
                    SELECT
                        0 as beginningBalance
                        ,0 as prevYearRecord
                        ,0 as currentYearRecord
                        ,0 as idCoa
                        ,'CASH FLOW FROM INVESTING ACTIVITIES' as aname_c30
                        ,0 as mocod_c1
                        ,0 as chcod_c1
                        ,0 as accod_c2
                        ,0 as acod_c15
                        ,1.3 as sorter
                )

                UNION ALL
                -- Total cash flow from investing activities
                (
                    SELECT
                        0 as beginningBalance
                        ,0 as prevYearRecord
                        ,0 as currentYearRecord
                        ,0 as idCoa
                        ,'' as aname_c30
                        ,0 as mocod_c1
                        ,0 as chcod_c1
                        ,0 as accod_c2
                        ,0 as acod_c15
                        ,2.2 as sorter
                )

                UNION ALL
                -- Cash flow from financing activities
                (
                    SELECT
                        0 as beginningBalance
                        ,0 as prevYearRecord
                        ,0 as currentYearRecord
                        ,0 as idCoa
                        ,'CASH FLOW FROM FINANCING ACTIVITIES' as aname_c30
                        ,0 as mocod_c1
                        ,0 as chcod_c1
                        ,0 as accod_c2
                        ,0 as acod_c15
                        ,2.3 as sorter
                )

                UNION ALL
                -- Total cash flow from investing activities
                (
                    SELECT
                        0 as beginningBalance
                        ,0 as prevYearRecord
                        ,0 as currentYearRecord
                        ,0 as idCoa
                        ,'' as aname_c30
                        ,0 as mocod_c1
                        ,0 as chcod_c1
                        ,0 as accod_c2
                        ,0 as acod_c15
                        ,3.2 as sorter
                )

                UNION ALL
                -- Net increase/decrease in cash and cash equivalents
                (
                    SELECT
                        0 as beginningBalance
                        ,0 as prevYearRecord
                        ,0 as currentYearRecord
                        ,0 as idCoa
                        ,'NET INCREASE/DECREASE IN CASH AND CASH EQUIVALENTS' as aname_c30
                        ,0 as mocod_c1
                        ,0 as chcod_c1
                        ,0 as accod_c2
                        ,0 as acod_c15
                        ,3.3 as sorter
                )
            ) as main
            JOIN(
			    SELECT 
			        @totalOperating := 0,
			        @totalInvesting := 0,
			        @totalFinancing := 0,
			        @totalPrevOperating := 0,
			        @totalPrevInvesting := 0,
                    @totalPrevFinancing := 0,
                    @totalBeginning := 0
            ) AS b ON 1 = 1
            ORDER BY main.sorter
        " )->result_array();
    }
}