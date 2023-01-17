<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer    : Jayson Dagulo
 * Module       : General and Subsidiary Ledger
 * Date         : Jan. 30, 2020
 * Finished     : 
 * Description  : This module allows authorized users to set the accounting defaults of every transaction module.
 * DB Tables    : 
 * */ 

class Generalsubsidiaryledger extends CI_Controller {

	public function __construct(){
		parent::__construct();
		setHeader('accounting/Generalsubsidiaryledger_model');
	}
	
	public function getGeneralLedger(){
		$params		= getData();

		/* first get affiliate accounting period and start date */
		$affiliateDetails	= $this->model->getAffiliateDetails( $params );
		if( count( (array)$affiliateDetails ) > 0 ){
			/* variables below are used to get beginning and YEAR CR/DR */
			if( $affiliateDetails['accSchedule'] == 1 ){ /* calendar */
				$params['monthStart']	= 1;
				$params['monthEnd']		= 12;
				$params['prevyear']		= (int)$params['year'] - 1;
			}
			else{ /* fiscal */
				$params['monthStart']	= $affiliateDetails['month'] + 1; /* fiscal year accounting period starts at month + 1 of the end month */
				$params['monthEnd']		= $affiliateDetails['month']; /* settings month indicates the end month of the accounting period */
				if( (int)$params['month'] < $affiliateDetails['month'] ){ /* if selected month is lesser than the end month of the affiliate go back 2 years for its beginning amount */
					$params['prevyear']	= (int)$params['year'] - 2;
				}
				else{
					$params['prevyear']	= (int)$params['year'] - 1;
				}
			}
			
			/* variables below is the basis for the month DR and CR column */
			$params['dateStart'] = $params['year'] . '-' . $params['monthStart'] . '-1';
			$params['dateEnd'] = (new DateTime( ( (int)$params['year'] ) . '-' . $params['monthEnd'] . '-1' ) )->format( 'Y-m-t' );
		}
		else{ /* treat default as calendar */
			/* variables below are used to get beginning and YEAR CR/DR */
			if( $params['month'] == 12 ){
				$params['prevyear']	= $params['year'] - 1;
			}
			else{
				$params['prevyear']	= $params['year'];
			}
			$params['monthEnd']		= $params['month'];

			/* variables below is the basis for the month DR and CR column */
			$params['dateStart']	= $params['year'] . '-' . $params['month'] . '-1';
			$params['dateEnd']		= (new DateTime( ( (int)$params['year'] ) . '-' . $params['month'] . '-1' ) )->format( 'Y-m-t' );
		}

		/* check for record validity before generating report */
		_validateReport( $params );

		$viewAll	= $this->model->getGeneralLedger( $params );
		
		die(
			json_encode(
				array(
					'success'	=> true
					,'view'		=> $viewAll
					,'match'	=> 0
				)
			)
		);
	}

	public function getSubsidiaryLedger(){
		$params		= getData();
		/* get header account information */
		$headerDet	= $this->model->getHeaderDetails( $params );
		if( count( (array)$headerDet ) > 0 ){
			$params['mocod_c1']	= $headerDet['mocod_c1'];
			$params['chcod_c1']	= $headerDet['chcod_c1'];
			$params['accod_c2']	= $headerDet['accod_c2'];
		}
		else{
			$params['mocod_c1']	= 0;
			$params['chcod_c1']	= 0;
			$params['accod_c2']	= 0;
		}
		$view		= $this->model->getSubsidiaryLedger( $params );
		if( count( (array)$view ) == 1 ){
			if( $view[0]['description'] != 'Beginning Balance' ){
				array_unshift( $view, array(
					'date'			=> ''
					,'reference'	=> ''
					,'acod_c15'		=> ''
					,'aname_c30'	=> ''
					,'description'	=> 'Beginning Balance'
					,'debit'		=> 0
					,'credit'		=> 0
					,'mocod_c1'		=> ''
					,'chcod_c1'		=> ''
					,'accod_c2'		=> ''
					,'sorter'		=> 1
					,'norm_c2'		=> ''
					,'idModule'		=> 0
					,'idInvoice'	=> 0
					,'idBankRecon'	=> 0
					,'idAccBegBal'	=> 0
				) );
			}
		}
		die(
			json_encode(
				array(
					'success'	=> true
					,'view'		=> $view
				)
			)
		);
	}

}