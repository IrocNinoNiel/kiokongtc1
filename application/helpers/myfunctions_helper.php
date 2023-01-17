<?php

function viewPDF($parameter){
	if(isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'],'Google App Engine') !== false){
		$directoryPath  = 'gs://newProject/';
		$parameter['file_name'] = str_replace("%20"," ",$parameter['file_name']);
		$filename = $parameter['file_name'].'.pdf';
		header('Content-type: application/pdf');
		header('Content-Disposition: inline; filename="' . $filename . '"');
		header('Content-Transfer-Encoding: binary');
		header('Accept-Ranges: bytes');
		@readfile($directoryPath.'pdf/'.$parameter['folder_name'].'/'.$parameter['file_name'].'.pdf');
	}
}

if(!function_exists('writeCsvFile')){
	function writeCsvFile($parameter){
		/* PARAMETER VALUE
			csvarray -  content sa csv
			directory -  folder name sa csv
			title -  file name sa csv
			extension - file extension its csv / excel
		*/
		$CIsess =& get_instance();
		$CIsess->load->helper('csv');
		$csvarray = array_to_csv($parameter['csvarray']);
		
		if(isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'],'Google App Engine') !== false){
			$path = 'gs://newProject/csv/'.$parameter['directory'].'/'.$parameter['title'].'.' . ( isset( $parameter['ext'] )? $parameter['ext'] : 'csv' ) ;
		}
		else{
			$path = './csv/'.$parameter['directory'].'/'.$parameter['title'].'.' . ( isset( $parameter['ext'] )? $parameter['ext'] : 'csv' );
		}
		write_file($path,$csvarray);
	}
}

if(!function_exists('setsession')){
	function setsession($key=array(),$val=''){
		$CIsess =& get_instance();
		if(is_array($key) && count($key)>0){
			$CIsess->session->set_userdata($key);
		}
		if(is_string($key) && strlen($key)>0 && strlen($val)>0){
			$CIsess->session->set_userdata($key,$val);
		}
	}
}

if(!function_exists('getsession')){
	function getsession($str=''){
		$CIsess =& get_instance();
		if(strlen($str)==0)return;
		return $CIsess->session->userdata($str);
	}
}

if(!function_exists('unsetsession')){
	function unsetsession($key=array(),$val=''){
		$CIsess =& get_instance();
		if(is_array($key) && count($key)>0){
			$CIsess->session->unset_userdata($key);
		}
		if(is_string($key) && strlen($key)>0 && strlen($val)==0){
			$CIsess->session->unset_userdata($key,$val);
		}
	}
}

if(!function_exists('setflash')){
	function setflash($key=array(),$val=''){
		$CIsess =& get_instance();
		if(is_array($key) && count($key)>0){
			$CIsess->session->set_flashdata($key);
		}
		if(is_string($key) && strlen($key)>0 && strlen($val)>0){
			$CIsess->session->set_flashdata($key,$val);
		}
	}
}

if(!function_exists('getflash')){
	function getflash($str=''){
		$CIsess =& get_instance();
		if(strlen($str)==0)return;
		return $CIsess->session->flashdata($str);
	}
}

if(!function_exists('getheader')){
	function getheader(){
		/*
			NOTE: this function is used only for portrait reports
		*/

		$pdfsess =& get_instance();

		$allHeaders = $pdfsess->home->getCompanyDetails( $pdfsess->session->userdata( 'DBNAME' ) );
		$company 	= $allHeaders->companyName;
		$location	= $allHeaders->companyLocation;
		$contact	= $allHeaders->companyContactNumber;
	
		$pdfsess->pdf->SetFont('helvetica', 'B', 8);
		$pdfsess->pdf->SetXY(30,7); $pdfsess->pdf->Cell(0, 0, $company, 0, $ln=1, 'R');
		$pdfsess->pdf->SetXY(30,11); $pdfsess->pdf->Cell(0, 0, $location, 0, $ln=1, 'R');
		$pdfsess->pdf->SetXY(30,15); $pdfsess->pdf->Cell(0, 0, $contact, 0, $ln=1, 'R');
		$pdfsess->pdf->Ln(5);
		
	}
}

if(!function_exists('getlogo')){
	function getlogo(){
		/*
			NOTE: com_logo column in tbl_customers must not be empty, 
			otherwise the 'LOGO NOT FOUND' image will appear on the report
		*/
		/*
			NOTE: this function is used only for portrait reports
		*/
		//assign CI instance to object variable
		$pdfsess =& get_instance();

		$imgurl  = $pdfsess->session->userdata( 'COMPLOGO' );
		
		// $imagefile = file_exists( $pdfsess->session->userdata( 'LOGOPATH' ) . $imgurl )? $pdfsess->session->userdata( 'LOGOPATH' ) .'/'. $imgurl : 'images/default-no-img.jpg'; 
		
		$imagefile = $pdfsess->session->userdata( 'LOGOPATH' ) . $imgurl;
		
		//break string to get the image extension and use as image type in tcpdf
		$ext   = explode('.',$imagefile); 
		
		// Image ($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false, $alt=false, $altimgs=array())
		$pdfsess->pdf->SetFillColor(0, 0, 0);
        $pdfsess->pdf->Image($imagefile, 0, 5, 50, 27, $ext[count($ext)-1], false, 'T', false, 150, 'L', false, false, 0, false, false, false);
		// $pdfsess->pdf->Cell(10, 20, 'try', 1, $ln=0, 'L');
		$pdfsess->pdf->Ln(25);
	}
}

if( !function_exists( 'logoHeader' ) ){
	function logoHeader( $params ){
		$ci        =& get_instance();
		$addPage   = isset( $params['addPage'] )? $params['addPage'] : true;
		
		if( $addPage ){
			$ci->pdf->AddPage( $params['orientation'] );
		}

		/** get company details **/
		$companyDetails = $ci->standards->getAffiliateDetails(array(
			'affiliateID' => ( isset( $params['idAffiliate'] ) && $params['idAffiliate'] > 0 ) ? $params['idAffiliate'] : $ci->AFFILIATEID
		));

		$ci->load->library('encryption');
		$ci->encryption->initialize( array( 'key' => generateSKED( $companyDetails->sk ) ) );
		$companyDetails = (object)decryptAffiliate(array( 0 => (array)$companyDetails ))[0];
		
		$emptyLogo = $ci->LOGOPATH . DEFAULT_EMPTY_IMG;

		if( !empty($companyDetails->logo) ) {
			$imagefile = $ci->LOGOPATH . $companyDetails->logo;
			if(!is_url_exist($imagefile)) $imagefile = $emptyLogo;
		} else {
			$imagefile = $emptyLogo;
		}
		
		$ext = explode( '.', $imagefile );

		/* COMPANY LOGO */
		// $ci->pdf->SetFillColor( 0, 0, 0 );
        // $ci->pdf->Image( '*' . $imagefile, 0, 5, 75,28, $ext[count($ext)-1], 'L', 'T', false, 150, 'L', false, false, 0, false, false, false );

		/* COMPANY HEADER */
		$ci->pdf->SetXY( 7, 4 );
		$missingHeaders = 0;
		$img_width = ( isset( $params['orientation'] ) && $params['orientation'] == 'L' ) ? 800 : 600;
		$header = '<center><table style="width:100%;">';

		/* P: width="600" && 800
			height="300"
		*/
//<td rowspan="6" style="width:40%; text-align: center;"><img src="' . $imagefile . '" width="'.$img_width.'" height="300" /></td>
//<img class="text-center" style="max-height: 150px !important; max-width: 465px !important;"  id="img-logo" alt="<?php echo $systemName;

			if($companyDetails->affiliateName){  //<td rowspan="6" style="width:38%;" ><img src="' . $imagefile . '" width="900px" height="320px;" /></td>
				$header .= '<tr style="font-size:50px;font-family:tahoma;font-weight:bold;">
								<td rowspan="6" style="width:40%; text-align: center;"><img src="' . $imagefile . '" width="'.$img_width.'" height="300" /></td>
								<td style="width:60%;">' . $companyDetails->affiliateName . '</td>
							</tr>';
			}else $missingHeaders++;
			
			if($companyDetails->address){
				$header .= '<tr style="font-size:40px;font-family:tahoma;">
								<td>' . $companyDetails->address . '</td>
							</tr>';
			}else $missingHeaders++;
			
			if($companyDetails->contactNumber){
				$header .= '<tr style="font-size:40px;font-family:tahoma;">
								<td>Tel No. ' . $companyDetails->contactNumber . '</td>
							</tr>';
			}else $missingHeaders++;
			
			if($companyDetails->tin){
				$header .= '<tr style="font-size:40px;font-family:tahoma;">
								<td>VAT REG. TIN ' . $companyDetails->tin . '</td>
							</tr>';
			}else $missingHeaders++;			

		for($h=0;$h<=$missingHeaders;$h++){
			$header .= '<tr><td></td></tr>';
		}
		
		 $header .= '</table></center>';
		 $ci->pdf->writeHTML($header, true, false, false, false, '');
		
		/* PDF TITLE */
		if( $addPage ){
			$ci->pdf->SetFont( 'times', 'B', 12 );
			$ci->pdf->MultiCell( 0, 5, $params['title'], 0, 'C', false, 1, '', '', true, 0, '' );
			$ci->pdf->Ln( 3 );
		}
	}
}


if(!function_exists('printJournalEntry')){
	function printJournalEntry( $data ){
		$ci =& get_instance();

		if( isset( $data['title']) ? $data['title'] : $data['pageTitle'] );
		
		if( !isset($data['hasPrintOption'])){
			return '';
		}
		else{
			if($data['hasPrintOption'] == 1){
				return '';
			}else{
				$grid = ( isset( $data['journalEntries'] ) ) ? json_decode($data['journalEntries']) : $ci->standards->gridJournalEntry( $data );

				$params = array(
					'title' 			=> 'Journal Entries'
					,'file_name' 		=> $data['title']
					,'folder_name' 		=> ''
					,'addPage' 			=> false
					,'returnAsTable' 	=> TRUE
					,'generate_total' 	=> TRUE
					,'total_fields' 	=> array('debit','credit')
					,'table_title' 		=> 'Journal Entries'
					,'noHeader' 		=> TRUE
				);
				
				$params1 = array(
					array(   'header' 		=> 'Code'	
							,'data_index' 	=> 'code'				
							,'width' 		=> '10%'
					),
					array(   'header' 		=> 'Name'	
							,'data_index'	=> 'name'	
							,'width' 		=> '25%'		
					),
					array(   'header' 		=> 'Explanation'	
							,'data_index' 	=> 'explanation'				
							,'width' 		=> '20%'		
					),
					array(   'header' 		=> 'Cost Center'	
							,'data_index' 	=> 'costcenterName'				
							,'width' 		=> '15%'		
					),
					array(   'header' 		=> 'Debit'	
							,'data_index' 	=> 'debit'
							,'type' 		=> 'numbercolumn'					
							,'width' 		=> '15%'	
					),
					array(   'header' 		=> 'Credit'	
							,'data_index' 	=> 'credit'
							,'type' 		=> 'numbercolumn'					
							,'width' 		=> '15%'		
					)
				);

				return generate_table( $params, $params1 , $grid );
			}
		}
	}
}

if(!function_exists('getfooter')){
	function getfooter($index=0, $data = array()){
		$pdfsess =& get_instance();
		$pdfsess->load->helper('html');
		
		$affDet = $pdfsess->standards->getAffiliateDetails(array(
			'affiliateID' => $pdfsess->AFFILIATEID
		));
		
		/** prepared by affected modules
						
			Receivable Charges & Invoices = 10
			Payments & Cash Receipts = 11
			Payable Charges & Invoices = 12
			Cash Disbursements & Debits = 13
			Supplier Beginning Balance = 17
			Client Beginning Balance = 16
			Adjustments = 30
			Bank Reconciliation = 31
			Closing Journal Entries = 18
		// **/
		$data['moduleID'] = isset($data['moduleID']) ? $data['moduleID'] : 0;
		$modulePrep = array(10,11,12,13,16,17,18,30);
		$preparedBy = '';
		$isDisbursement = FALSE;
		/** invoices **/
		if(in_array($data['moduleID'],$modulePrep)){
			$pdfsess->db->select('e.fullName');
			$pdfsess->db->from('invoices as inv');
			$pdfsess->db->join('eu as e','e.euID = inv.preparedBy');
			$pdfsess->db->where('inv.invoiceID', $data['invoiceID']);
			$prep = $pdfsess->db->get()->row_object();
			$preparedBy = isset($prep->fullName) ? $prep->fullName : '';
		}
		
		$modulePrep = array(31);
		/** bank recon **/
		if(in_array($data['moduleID'],$modulePrep)){
			$pdfsess->db->select('e.fullName');
			$pdfsess->db->from('bankrecon as b');
			$pdfsess->db->join('eu as e','e.euID = b.preparedBy');
			$pdfsess->db->where('b.bankreconID', $data['bankreconID']);
			$prep = $pdfsess->db->get()->row_object();
			$preparedBy = isset($prep->fullName) ? $prep->fullName : '';
			
		}
		
		$modulePrep = array(13);
		/** Disbursements **/
		if(in_array($data['moduleID'],$modulePrep)){
			$isDisbursement = TRUE;
		}
		
		$signatories = '<table>
							<tr>
								<td width="50%">
									<table style="padding:45px;">
										'.(
											!empty($preparedBy) ? 
												'<tr>
													<td>
														<table>
															<tr>
																<td width="25%" style="font-weight:bold;">Prepared By: </td>
																<td style="width:55%; border-bottom:1px solid black;font-weight:normal;">'.$preparedBy.'</td>
															</tr>
														</table>
													</td>
												</tr>' : ''
										).'
										
										<tr>
											<td>
												<table>
													<tr>
														<td width="25%" style="font-weight:bold;">Checked By : </td>
														<td style="width:55%; border-bottom:1px solid black;font-weight:normal;">'.$affDet->checkedBy.'</td>
													</tr>
												</table>
											</td>
										</tr>
										<tr>
											<td>
												<table>
													<tr>
														<td width="25%" style="font-weight:bold;">Reviewed By : </td>
														<td style="width:55%; border-bottom:1px solid black;font-weight:normal;">'.$affDet->reviewedBy.'</td>
													</tr>
												</table>
											</td>
										</tr>
										'.(
											$isDisbursement ?
												'<tr>
													<td>
														<table>
															<tr>
																<td width="25%" style="font-weight:bold;">Received By : </td>
																<td style="width:55%; border-bottom:1px solid black;font-weight:normal;"></td>
															</tr>
														</table>
													</td>
												</tr>' : ''
										).'
									</table>
								</td>
								<td width="50%">
									<table style="padding:45px;">
										'.(
											!empty($preparedBy) ? 
												'<tr>
													<td>
														<br>
													</td>
												</tr>' : ''
										).'
										
										<tr>
											<td>
												<table>
													<tr>
														<td width="30%" style="font-weight:bold;">Approved By (1) : </td>
														<td style="width:55%; border-bottom:1px solid black;font-weight:normal;">'.$affDet->approvedBy1.'</td>
													</tr>
												</table>
											</td>
										</tr>
										<tr>
											<td>
												<table>
													<tr>
														<td width="30%" style="font-weight:bold;">Approved By (2) : </td>
														<td style="width:55%; border-bottom:1px solid black;font-weight:normal;">'.$affDet->approvedBy2.'</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>';

		$pdfsess->pdf->SetFont('helvetica','N',7);
		$pdfsess->pdf->Ln(15);
		$pdfsess->pdf->writeHTML($signatories, true, false, false, false, '');
	}
}

if(!function_exists('getData')){
	function getData( $secure = true ){
		$ci     =& get_instance();
		$headers = getallheaderss();
		
		// if( $secure ){
			// if( !isset( $headers['initHeader'] ) ){
				// die();
			// }
			// elseif( $headers['initHeader'] != $ci->session->userdata( 'initHeader' ) ){
				// die();
			// }
		// }
		
		$data   = array();
		$module = $ci->input->post('module');
		if( isset( $_POST ) ){
			foreach($_POST as $key => $val){
				$value         = $ci->input->post($key);
				$without_comma = str_replace(",","",$value);
				$key_module    = str_replace($module,'',$key);
				$key_module    = str_replace("-inputEl",'',$key_module);
				
				if($key_module == 'reset'){
					die(json_encode(array('success'=>true, 'total'=>0, 'view'=>array())));
				}
				else if(is_numeric($without_comma))$value = $without_comma;
				else if($value == '') $value = null;
				
				$data[$key_module] = (trim($value) == '' ? null : trim($value));
			}
			
		}
		else{
			die(json_encode( array('success'=>false) ));
		}
		
		return $data;
	}
}

if(!function_exists('resize_image')){
	function resize_image($file, $size, $output = 'file',$followDefaultWidth=true, $width = 200, $height = 200, $proportional = false, $delete_original = true, $use_linux_commands = false ){
      
		if ( $height <= 0 && $width <= 0 ) return false;

		# Setting defaults and meta
		$info                         = getimagesize($file);
		$image                        = '';
		$final_width                  = 0;
		$final_height                 = 0;
		list($width_old, $height_old) = $info;

		# Calculating proportionality
		if ($proportional) {
			if      ($width  == 0)  $factor = $height/$height_old;
			elseif  ($height == 0)  $factor = $width/$width_old;
			else                    $factor = min( $width / $width_old, $height / $height_old );

			$final_width  = round( $width_old * $factor );
			$final_height = round( $height_old * $factor );
		}
		else {
			// $final_width = ( $width <= 0 ) ? $width_old : $width;
			// $final_height = ( $height <= 0 ) ? $height_old : $height;

			$final_width = $followDefaultWidth ? $width : $width_old;
			$final_height = $followDefaultWidth ?$height : $height_old;
		}

		# Loading image to memory according to type
		switch ( $info[2] ) {
			case IMAGETYPE_GIF:   $image = imagecreatefromgif($file);   break;
			case IMAGETYPE_JPEG:  $image = imagecreatefromjpeg($file);  break;
			case IMAGETYPE_PNG:   $image = imagecreatefrompng($file);   break;
			default: return false;
		}
    
    
		# This is the resizing/resampling/transparency-preserving magic
		$image_resized = imagecreatetruecolor( $final_width, $final_height );
		if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG) ){
			$transparency = imagecolortransparent($image);

			if ($transparency >= 0) {
				$transparent_color  = imagecolorsforindex($image, $transparency);
				$transparency       = imagecolorallocate($image_resized, 255, 255, 255);
				imagefill($image_resized, 0, 0, $transparency);
				imagecolortransparent($image_resized, $transparency);
			}
			elseif ($info[2] == IMAGETYPE_PNG) {
				imagealphablending($image_resized, false);
				$color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
				imagefill($image_resized, 0, 0, $color);
				imagesavealpha($image_resized, true);
			}
		}
		imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $final_width, $final_height, $width_old, $height_old);
    
		# Taking care of original, if needed
		if ( $delete_original ) {
			if ( $use_linux_commands ) exec('rm '.$file);
			else @unlink($file);
		}

		# Preparing a method of providing result
		switch ( strtolower($output) ) {
			case 'browser':
				$mime = image_type_to_mime_type($info[2]);
				header("Content-type: $mime");
				$output = NULL;
				break;
			case 'file':
				$output = $file;
				break;
			case 'return':
				return $image_resized;
				break;
			default:
				break;
		}
    
		# Writing image according to type to the output destination
		switch ( $info[2] ) {
			case IMAGETYPE_GIF:   imagegif($image_resized, $output);    break;
			case IMAGETYPE_JPEG:  imagejpeg($image_resized, $output);   break;
			case IMAGETYPE_PNG:   imagepng($image_resized, $output);    break;
			default: return false;
		}
		
		return true;
	}
}



/** This function will check if image exits by URL **/
if( !function_exists( 'is_url_exist' ) ){
	function is_url_exist($url){
		if(isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'],'Google App Engine') !== false){
			if( !file_exists( $url ) ){
				return false;
			}
			else{
				return true;
			}
		}
		else{
			$ch = curl_init($url);    
			curl_setopt($ch, CURLOPT_NOBODY, true);
			curl_exec($ch);
			$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			if($code == 200){
			   $status = true;
			}else{
			  $status = false;
			}
			curl_close($ch);
			
			return $status;
		}
	}
}

	
function generate_table($table_params=array(),$data=array(),$data1=array(),$extaTables='',$ExtableBottom=''){
	if(file_exists($table_params['folder_name'].$table_params['file_name'].'.pdf')){
		unlink($table_params['folder_name'].$table_params['file_name'].'.pdf');
	}
	
	ob_clean();
	$ci =& get_instance();
	if(!isset($table_params['table_hidden'])) $table_params['table_hidden'] = false;
		
	if((count($table_params) > 0 && count($data) > 0) || $table_params['table_hidden'] == true){
		if(!isset($table_params['title'])) die("title field is required");
		if(!isset($table_params['file_name'])) die("file_name field is required");
		if(!isset($table_params['folder_name'])) die("folder_name field is required");
		
		if(!isset($table_params['title_font_style'])) $table_params['title_font_style'] = 'B';
		if(!isset($table_params['title_font_size'])) $table_params['title_font_size'] = 8;
		if(!isset($table_params['grid_font_size'])) $table_params['grid_font_size'] = 8;
		if(isset($table_params['grid_font_style'])){
			if($table_params['grid_font_style'] == 'N') $table_params['grid_font_style'] = '';
		}else{
			$table_params['grid_font_style'] = '';
		}
		if(!isset($table_params['font_style'])) $table_params['font_style'] = 'helvetica';
		if(!isset($table_params['orientation'])) $table_params['orientation'] = 'P';
		if(!isset($table_params['date'])) $table_params['date'] = false;
		if(!isset($table_params['date_format'])) $table_params['date_format'] = 'm-d-Y';
		if(!isset($table_params['margin_bottom_after_title'])) $table_params['margin_bottom_after_title'] = 5;
		if(!isset($table_params['margin_bottom_after_date'])) $table_params['margin_bottom_after_date'] = 5;
		if(!isset($table_params['generate_total'])) $table_params['generate_total'] = false;
		if(!isset($table_params['table_width'])) $table_params['table_width'] = '100%';
		if($table_params['generate_total'] == true && !isset($table_params['total_fields'])) die("total_fields field is required");
		
		$noHeader = isset( $table_params['noHeader'] )? $table_params['noHeader'] : false;
		$noTitle = isset( $table_params['noTitle'] )? $table_params['noTitle'] : false;
		$noLogoTitle = isset( $table_params['noLogoTitle'] )? $table_params['noLogoTitle'] : false;
		
		$marginFirst = isset( $table_params['marginFirst'] )? $table_params['marginFirst'] : 6;
		$marginSecond = isset( $table_params['marginSecond'] )? $table_params['marginSecond'] : 10;
		$printFooter = isset( $table_params['noFooter'] )? false : true;
		
		$ci->pdf->setPrintHeader(false);
		$ci->pdf->setPrintFooter($printFooter);
		$ci->pdf->SetMargins($marginFirst, $marginSecond); 

		$pageFormat = isset( $table_params['pageFormat'] )? $table_format['pageFormat'] : 'A4';
		
		// $ci->pdf->AddPage($orientation=$table_params['orientation'], $pageFormat);
		
		if( !$noHeader ){
			// if($table_params['orientation']=='P'){
			// 	getlogo();
			// 	getheader(); 
			// }else{
			// 	getlogo();
			// 	getheader(); 
			// }

			// getlogo();

			// if($orientation == 'P')	
			// 	getheader();
			// else
			// 	getheader2();
			
			logoHeader( array( 'orientation'=>$table_params['orientation'], 'title'=>( !$noLogoTitle? $table_params['file_name'] : '' ) ) );
		}
		
		if( !$noTitle ){
			$ci->pdf->SetFont(trim($table_params['font_style']),trim($table_params['title_font_style']), $table_params['title_font_size']);
			$ci->pdf->Cell(0, 0,$table_params['title'], 0,true, 'C');
			$ci->pdf->Ln($table_params['margin_bottom_after_title']);
		}
		
		$ci->pdf->SetFont(trim($table_params['font_style']),trim($table_params['grid_font_style']), $table_params['grid_font_size']);
		if($table_params['date'] == true){
			$ci->pdf->Cell(200, 5,'Date : '.Date($table_params['date_format']), 0,true, 'L');
			$ci->pdf->Ln($table_params['margin_bottom_after_date']);
		}
		$tbl  = $extaTables;
		if($table_params['table_hidden'] == false){
		$tbl .= '<br><table style="width:'.$table_params['table_width'].';border-collapse: collapse;" cellpadding="7" border = "1">';
			 $data_cnt = count($data);
			 $tbl .= '<tr style="background-color:#f1f1f1;">';
				 for($x=0;$x<$data_cnt;$x++){
					if(!isset($data[$x]['header'])) die('header title header is required.');
					if(!isset($data[$x]['data_index'])) die('data_index is required.');
					
					if(!isset($data[$x]['type']))  $data[$x]['type'] = 'text';
					
					if($data[$x]['type'] == 'numbercolumn'){
						if(!isset($data[$x]['decimalplaces'])) $data[$x]['decimalplaces'] = 2;
						 $data[$x]['data_align'] = 'right';
					}else if($data[$x]['type'] == 'datecolumn'){
						if(!isset($data[$x]['format'])) $data[$x]['format'] = 'm/d/Y';
						$data[$x]['data_align'] = 'left';
					}else{
						$data[$x]['data_align'] = 'left';
					}
					
					if(!isset($data[$x]['align'])) $data[$x]['align'] = 'C';
					
					if($data[$x]['align'] == 'L') $data[$x]['align'] = 'left';
					else if($data[$x]['align'] == 'R') $data[$x]['align'] = 'right';
					else if($data[$x]['align'] == 'C') $data[$x]['align'] = 'center';
					
					if(!isset($data[$x]['width'])) $data[$x]['width'] = number_format((100/$data_cnt),2)."%";
					
					$align = $data[$x]['align'];
					$width = $data[$x]['width'];
					$header = $data[$x]['header'];
					
					$tbl .= "<th style=\"width:$width;text-align:$align\"><strong>$header</strong></th>";
				 }
			 $tbl .= '</tr>';
			 $n = 0;
			 $v = 0;
			 $totalamount = array();
			 for($x=0;$x<count($data1);$x++){
				if($n % 2 == 0) $tbl .= '<tr>';
				else $tbl .= '<tr>';
				
				 for($y=0;$y<$data_cnt;$y++){
					foreach($data1[$x] as $key => $v1){
						if($data[$y]['data_index'] == $key){
							if($table_params['generate_total'] == true){
								foreach($table_params['total_fields'] as $val1 => $key1){
									if( is_array($key1) && $val1 == $key ){
										$totalamount[$y] = array($val1=>$v1); 
									}
									if($key1 == $key){
										if(isset($totalamount[$y][$val1])) $v = $totalamount[$y][$val1];
										else $v = 0;
										
										$v += $v1;
										$totalamount[$y] = array($val1=>$v); 
									}
								}
							}
							$align = $data[$y]['data_align'];
							if($data[$y]['type'] == 'numbercolumn'){ 	  
								$tbl .= "<td style=\"text-align:$align\">" .number_format($v1,$data[$y]['decimalplaces']). '</td>';
							}else if($data[$y]['type'] == 'datecolumn'){
								$v1 = str_replace('-', '/', $v1);
								$tbl .= "<td style=\"text-align:$align\">" .(!empty($v1) ? date($data[$y]['format'],strtotime($v1)):''). '</td>';
							}
							else if($data[$y]['type'] == 'autoInc'){
								$numberAuto = $x + 1;
								$tbl .= "<td style=\"text-align:$align\">" .$numberAuto. '</td>';
							}
							else{ 
								$tbl .= "<td style=\"text-align:$align\">" .$v1. '</td>';
							}
						} 
					}
				}
				$tbl .= '</tr>';
				$n++;
			}
		$b = 0;
		$trig = false;
		if(count($totalamount) > 0){	
			$tbl .= "<tr>";
			for($i=1;$i<$data_cnt;$i++){
				if(isset($totalamount[$i])){
					if($trig == false){ 
						$tbl .= "<td style=\"text-align:center;\"><strong>TOTAL :</strong></td>"; $trig = true;
					}
					foreach($totalamount[$i] as $v){
						$tbl .= '<td style="text-align:right;"><strong>'.number_format($v,$data[$i]['decimalplaces']).'</strong></td>';
					}
				}else{
					$tbl .= "<td></td>";
				}
			}
			$tbl .= "</tr>";
		}
		$tbl .= '</table><br/>';
		}
		$tbl .= $ExtableBottom;
		$ci->pdf->writeHTML($tbl, true, false, false, false, '');
	  
	  // getfooter(1);
	  
		
	   if(isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'],'Google App Engine') !== false){
			// $directoryPath  = 'gs://bontilaoapp/';
		}
		else{
			$directoryPath='./';
		}

		$ci->pdf->Output($directoryPath.$table_params['folder_name'].$table_params['file_name'].'.pdf', 'F');
	}
}

function generate_table_as_string($table_params=array(),$data=array(),$data1=array(),$extaTables='',$ExtableBottom=''){
		
	// if(count($data)>0 && count($data1)>0){
	if(count($data)>0){
	
		if(!isset($table_params['table_width'])) $table_params['table_width'] = '100%';
		if(!isset($table_params['grid_font_size'])) $table_params['grid_font_size'] = 10;
		if(!isset($table_params['table_title'])) $table_params['table_title'] = '';
		if(!isset($table_params['generate_total'])) $table_params['generate_total'] = false;
		if($table_params['generate_total'] == true && !isset($table_params['total_fields'])) die("total_fields field is required");
		
		$tbl  = $extaTables;
		$tbl .= '<br>';
		if(!empty($table_params['table_title'])){
			$tbl .= '<table cellpadding = "7" style = "width:'.$table_params['table_width'].';font-size:'.$table_params['grid_font_size'].'"><tr><td><strong>
					 '.$table_params['table_title'].'
					 </strong></td></tr></table>';
		}
		$tbl .= '<table style="width:'.$table_params['table_width'].';border-collapse: collapse;" cellpadding="7" border = "1">';
			$data_cnt = count($data);
			 $tbl .= '<tr style="background-color:#f1f1f1;">';
				 for($x=0;$x<$data_cnt;$x++){
					if(!isset($data[$x]['header'])) die('header title header is required.');
					if(!isset($data[$x]['data_index'])) die('data_index is required.');
					
					if(!isset($data[$x]['type']))  $data[$x]['type'] = 'text';
					
					if($data[$x]['type'] == 'numbercolumn'){
						if(!isset($data[$x]['decimalplaces'])) $data[$x]['decimalplaces'] = 2;
						 $data[$x]['data_align'] = 'right';
					}else if($data[$x]['type'] == 'datecolumn'){
						if(!isset($data[$x]['format'])) $data[$x]['format'] = 'm/d/Y';
						$data[$x]['data_align'] = 'right';
					}else{
						if(!isset($data[$x]['data_align'])) $data[$x]['data_align'] = 'left';
					}
					
					if(!isset($data[$x]['align'])) $data[$x]['align'] = 'C';
					
					if($data[$x]['align'] == 'L') $data[$x]['align'] = 'left';
					else if($data[$x]['align'] == 'R') $data[$x]['align'] = 'right';
					else if($data[$x]['align'] == 'C') $data[$x]['align'] = 'center';
					
					if(!isset($data[$x]['width'])) $data[$x]['width'] = number_format((100/$data_cnt),2)."%";
					
					$align = $data[$x]['align'];
					$width = $data[$x]['width'];
					$header = $data[$x]['header'];
					
					$tbl .= "<th style=\"width:$width;text-align:$align\"><strong>$header</strong></th>";
				 }
			 $tbl .= '</tr>';
			 $n = 0;
			 $v = 0;
			 $totalamount = array();
			 for($x=0;$x<count($data1);$x++){
				if($n % 2 == 0) $tbl .= '<tr>';
				else $tbl .= '<tr>';
				
				 for($y=0;$y<$data_cnt;$y++){
					foreach($data1[$x] as $key => $v1){
						if($data[$y]['data_index'] == $key){
							if($table_params['generate_total'] == true){
								foreach($table_params['total_fields'] as $val1 => $key1){
									if($key1 == $key){
										if(isset($totalamount[$y][$val1])) $v = $totalamount[$y][$val1];
										else $v = 0;
										
										$v += $v1;
										$totalamount[$y] = array($val1=>$v); 
									}
								}
							}
							$align = $data[$y]['data_align'];
							if($data[$y]['type'] == 'numbercolumn'){ 	  
								$tbl .= "<td style=\"text-align:$align\">" .number_format($v1,$data[$y]['decimalplaces']). '</td>';
							}else if($data[$y]['type'] == 'datecolumn'){
								$v1 = str_replace('-', '/', $v1);
								$tbl .= "<td style=\"text-align:$align\">" .( !empty($v1) ? date($data[$y]['format'],strtotime($v1)) : ''). '</td>';
							}else{ 
								$tbl .= "<td style=\"text-align:$align\">" .$v1. '</td>';
							}
						} 
					}
				}
				$tbl .= '</tr>';
				$n++;
			}
		$b = 0;
		$trig = false;
		if(count($totalamount) > 0){	
			$tbl .= "<tr>";
			for($i=1;$i<$data_cnt;$i++){
				if(isset($totalamount[$i])){
					if($trig == false){ 
						$tbl .= "<td style=\"text-align:center;\"><strong>TOTAL :</strong></td>"; $trig = true;
					}
					foreach($totalamount[$i] as $v){
						$tbl .= '<td style="text-align:right;"><strong>'.number_format($v,$data[$i]['decimalplaces']).'</strong></td>';
					}
				}else{
					$tbl .= "<td></td>";
				}
			}
			$tbl .= "</tr>";
		}
		$tbl .= '</table><br>';
		$tbl .= $ExtableBottom;
		
		return $tbl;
	}
}

if(!function_exists('generateTcpdf')){
	function generateTcpdf( $params ){
		$ci =& get_instance();
		
		$ci->pdf->setPrintHeader( false );
		$ci->pdf->setPrintFooter( true );
		$ci->pdf->SetMargins( 6, 6 ); 

		
		
		if( empty( $params['file_name'] ) ){
			echo "file name is required";
		}
		if( empty( $params['folder_name'] ) ){
			echo "folder name is required";
		}
		if( empty( $params['records'] ) ){
			echo "records to be printed is required";
		}
		if( empty( $params['header'] ) ){
			echo "header to be printed is required";
		}
		
		$params['file_name']= str_replace( "%20", " ", $params['file_name'] );
		$orientation 		= isset( $params['orientation'] )? $params['orientation'] : 'P';
		$orientationWidth 	= ($orientation =='P')? 204 : 260;
		
		/* FOR GRID HEADER */
		$header_font_family = isset( $params['header_font_family'] )? trim($params['header_font_family']) : 'freesans';
		$header_font_style	= isset( $params['header_font_style'] )? trim($params['header_font_style']) : 'B';
		$header_font_size 	= isset( $params['header_font_size'] )? trim($params['header_font_size']) : '8';
		
		/* FOR GRID ROW */
		$row_font_family 	= isset( $params['row_font_family'] )? trim($params['row_font_family']) : 'freesans';
		$row_font_style 	= isset( $params['row_font_style'] )? trim($params['row_font_style']) : 'N';
		$row_font_size 		= isset( $params['row_font_size'] )? trim($params['row_font_size']) : '8';
	
		// logoHeader( array( 'orientation'=>$orientation, 'title'=>$params['file_name'] ) );
		// logoHeader( array( 'orientation'=> 'P', 'title'=> $params['title'], 'idAffiliate' => $params['idAffiliate']  ) );

		$logoHeaderParams = array(
			'orientation' 	=> $orientation
			,'title'		=> $params['file_name']
		);

		if( isset( $params['idAffiliate'] )) $logoHeaderParams['idAffiliate'] = $params['idAffiliate'];
		logoHeader( $logoHeaderParams );

		if( isset( $params['header_fields'] ) ){
			$ci->pdf->SetFont($row_font_family,'', 9);

			$header_field_table = '';
			$header_field_table .= '<table><thead><tr>';
			

			foreach ($params['header_fields'] as $key => $fields){
				$header_field_table .= '<td></td>';
			}
			$header_field_table .= '</tr></thead><tbody><tr>';
			foreach ($params['header_fields'] as $key => $fields) {
				$header_field_table .= '<td><tr>';
				foreach ($fields as $idx => $field) {
					$header_field_table .= '<tr><td><strong>'. $field['label'] .': </strong> '. $field['value'] .'</td></tr>';
				}
				$header_field_table .= '</tr></td>';
			}
			$header_field_table .= '</tr></tbody></table>';
			
			$ci->pdf->writeHTML($header_field_table, true, false, false, false, '');
		}

		
		

		//HEADER FILTERS
		//Pls referer implementation on 'controller/po/invpurreport.php' function 'Monitoring_PDF'
		// if(isset($params['header_fields'])){
		// 	$ci->pdf->SetFont($row_font_family,'', 9);

			
		// 	print_r( $params['header_fields'] );
			
		// 	#GETTING THE MAX LENGTH FIELD AND VALUE PER COLUMN
		// 	foreach($params['header_fields'] as $index => $container){
		// 		$columnsWidth[] = array(
		// 							'labelWidth' => isset($container['labelWidth']) ? $container['labelWidth'] : 30, 
		// 							'valueWidth' => isset($container['valueWidth']) ? $container['valueWidth'] : 40
		// 						);
		// 		unset($params['header_fields'][$index]['labelWidth']);			
		// 		unset($params['header_fields'][$index]['valueWidth']);			
		// 	}
			
		// 	$x = 5;	
		// 	$y = $ci->pdf->GetY();	
		// 	$oldY =  $y;
		// 	$maxHeight = 0;
		// 	foreach($params['header_fields'] as $index => $container){
		// 		$stringHeightsValue = 0;
		// 		$stringHeightsLabel = 0;
		// 		$stringHeights = 0;
		// 		$stringWidths = 0;
				
		// 		foreach($container as  $data){
						
		// 				$stringHeightsLabel = $ci->pdf->getStringHeight($columnsWidth[$index]['labelWidth'],$data['label']);
		// 				$stringHeightsValue = $ci->pdf->getStringHeight($columnsWidth[$index]['valueWidth'],$data['value']);
		// 				if($stringHeightsLabel > $stringHeightsValue){
		// 					$stringHeights = $stringHeightsLabel;
		// 				}else 
		// 					$stringHeights = $stringHeightsValue;
						
		// 				$ci->pdf->MultiCell(	
		// 									$columnsWidth[$index]['labelWidth'],
		// 									$stringHeights,
		// 									$data['label'] . ' : ',
		// 									$border = 0,
		// 									$align = 'L',
		// 									$fill = false,
		// 									$ln = 0,
		// 									$x,
		// 									$y,
		// 									$reseth = true,
		// 									$stretch = 0,
		// 									$ishtml = false,
		// 									$autopadding = true,
		// 									$maxh = 0,
		// 									$valign = 'T',
		// 									$fitcell = false 
		// 								);	
		// 				$ci->pdf->MultiCell(	
		// 									$columnsWidth[$index]['valueWidth'],
		// 									$stringHeights,
		// 									$data['value'] ,
		// 									$border = 0,
		// 									$align = 'L',
		// 									$fill = false,
		// 									$ln = 1,
		// 									'',
		// 									'',
		// 									$reseth = true,
		// 									$stretch = 0,
		// 									$ishtml = false,
		// 									$autopadding = true,
		// 									$maxh = 0,
		// 									$valign = 'T',
		// 									$fitcell = false 
		// 								);	
										
		// 				$y += $stringHeights;
		// 				$stringWidths = $columnsWidth[$index]['labelWidth'] + $columnsWidth[$index]['valueWidth'];
		// 		} 
		// 		if($y > $maxHeight){
		// 			$maxHeight = $y;
		// 		}
				
		// 		$y = $oldY; 
		// 		$x += $stringWidths; 
		// 	}
			
		// 	$ci->pdf->setY($maxHeight);
		// 	$ci->pdf->ln(5);
		// }
		
		
		
		$ci->pdf->SetFont($header_font_family,$header_font_style, $header_font_size);
		$headerCnt =  count($params['header']) -1 ;
		$headerInc=0;
		$border='';
		$ln=0;
		$headerHeight=0;
		$totalHeaderWithOutWidth=0;
		$remainingWidth=0;
		$decimalPlaces=0;
		
		$lastColumn = '';
		$ci->pdf->setCellHeightRatio(1.5);
		$ci->pdf->SetFillColor(240,240,240);
		
		
		/*calculate maximum header height*/
		foreach($params['header'] as $key=>$val){
			if( !is_numeric($val['width']) ) $val['width'] = intval( $val['width'] );
			$cntHeaderHeight  =  $ci->pdf->getStringHeight($orientationWidth * ($val['width'] / 100),$val['header'],false,true,'',1);
			if($cntHeaderHeight > $headerHeight)	$headerHeight = $cntHeaderHeight;
			
		}
		
		//======================
		// $h1 = $params['header'];
		// print_r($h1);
		// echo '============ ';
		// array_splice($h1,8,1);
		// print_r($h1);
		//======================
		
		//======================
		$headers;
		$headerHeader_height =0;
		if(isset($params['sub_headers'])){
			
			$headers = $params['header'];
			
			foreach($params['sub_headers'] as $key){
				$FirstColumn = true;
				
				$headerHeaderWidth = 0;
				foreach($key['subheaders'] as $val){
					foreach($headers as $cols){
						if($cols['dataIndex'] == $val)	$headerHeaderWidth += floatval($cols['width']);
					}
				}
				
				foreach($key['subheaders'] as $val){
					for($x=0; $x<count($headers); $x++){
						if(isset($headers[$x])){
							
							if($headers[$x]['dataIndex'] == $val){
								if($FirstColumn){
									echo '============ X = '.$x;
									print_r($headers);
									echo ' ----------- output ';
									
									array_splice($headers,$x,1,array(array('Top'=>$key['header'],'dataIndex'=>'top_header','width'=>$headerHeaderWidth)));
									$FirstColumn = false;
									
									print_r($headers);
									echo '============';
								}
								else{
									echo '************* X = '.$x;
									
									array_splice($headers,$x,1);
									
									print_r($headers);
									echo '*************';
									}
								break;
							}
						}
					}
				}
			}
			
			foreach($headers as $key=>$val){
				$headerHeaderHeight  =  $ci->pdf->getStringHeight($orientationWidth * ($val['width'] / 100),isset($val['Top'])? $val['Top'] : '',false,true,'',1);
				if($headerHeaderHeight > $headerHeader_height)	$headerHeader_height = $headerHeaderHeight;
				
			}
			
			
			for($x=0; $x<count($headers); $x++){
				$width  = $orientationWidth * ($headers[$x]['width'] / 100);
				$text   = isset($headers[$x]['Top'])? $headers[$x]['Top'] : '';
				$border = isset($headers[$x]['Top'])? 'LTRB' : 'LTR';
				$nextln = $x==count($headers)-1? 1 : '';
				$ci->pdf->MultiCell($width,$headerHeaderHeight,$text,$border,'C',1, $nextln,'','', true,0,'');
			}
			
			
		}
		
		/* CHECK WHEATER ANY COLUMN HAS TOTAL OR HAS MAIN HEADER */
		$hasTotal = false;
		foreach($params['header'] as $key=>$val){
			
			if($headerInc==0) {
				$border = 'LRB';
			}
			else if($headerInc==$headerCnt){
				$ln=1;
				$border = 'LRB';
			}
			else{
				$border = 'LRB';
				$x='';$y='';
			}
			
			if(!isset($params['sub_headers']))$border .= 'T';
			
			
			if(isset($val['width'])) {
				if( !is_numeric($val['width']) ) $val['width'] = intval( $val['width'] );
				$width = $orientationWidth * ($val['width'] / 100);
			} else{
				$width=$orientationWidth / count($params['header']);
			}
			
			
			if(isset($val['align'])) $align = $val['align'];
			else $align='C';
			
			
			$ci->pdf->MultiCell($width,$headerHeight,$text = $val['header'],$border,$align,1, $ln,'','', true,0,'');
			
			
			$headerInc++;
			$remainingWidth = $remainingWidth + (int)$width;  
			$lastColumn = $val['dataIndex'];
			
			if( isset( $val['hasTotal']) ){
				if( $val['hasTotal'] ){
					$hasTotal = true;
					$params['header'][$key]['total'] = 0;
				}
			}
		}
		

		/*
			paramsa sa pg kuha og records
		*/
		
		$recordCnt =  count($params['records']) -1;
		$recInc=0;
		$recLn=0;
		$headerInc=0;
		$rowHeight=0;
		

		$ci->pdf->SetFont($row_font_family,$row_font_style, $row_font_size);
		
		foreach($params['records'] as $key=>$val){
			$first_border_nextPage = false;
			
		
			/*calculate maximum record height per row*/
			foreach($params['header'] as $key=>$headerValRow){
				if(isset($headerValRow['width'])) {
					if( !is_numeric($headerValRow['width']) ) $headerValRow['width'] = intval( $headerValRow['width'] );
					$rowWidth = $orientationWidth * ($headerValRow['width'] / 100);
				} else {
					$rowWidth = $orientationWidth / count($params['header']);
				}							
				
				if(isset($val['height'])) $rowHeight = $val['height'];
				else{
					$recordVal = ($val[$headerValRow['dataIndex']])?$val[$headerValRow['dataIndex']]:'';
					$cntRecordHeight  =  $ci->pdf->getStringHeight($rowWidth,$rowTxt =$recordVal ,$reseth = false,$autopadding = true,$cellpadding = '',$border = 1);
					if(floatval($cntRecordHeight)	>= floatval($rowHeight)) $rowHeight = $cntRecordHeight;
				}
				
			}
			
			// echo $headerValRow['dataIndex'];
		
			foreach($params['header'] as $key=>$headerVal){
				if($headerInc==$headerCnt){
					$headerInc=0;
					$recLn=1;
					$recBorder  = 'LRB';
				}else{
					$recLn=0;
					$recBorder = 'LRB';
					$headerInc++;
				}
				
				if(isset($headerVal['width'])) {
					if( !is_numeric($headerVal['width']) ) $headerVal['width'] = intval( $headerVal['width'] );
					$rowWidth = $orientationWidth * ($headerVal['width'] / 100);
				} else {
					$rowWidth=$orientationWidth / count($params['header']);
				}
				
				$value = $val[$headerVal['dataIndex']];
				$align = 'L';

				// rgb colors = for text color;
				$r = 0;
				$g = 0;
				$b = 0;
				
				
				if(isset($headerVal['type'])){
					if($headerVal['type'] == 'numbercolumn' || (isset($headerVal['isRunning']) && $headerVal['isRunning'] == true)){
						$decimal = isset($headerVal['decimalplaces'])? intval($headerVal['decimalplaces']) : 2;
						
						if(isset($headerVal['format'])){
							$exp = explode('.',$headerVal['format']);
							
							if(count($exp) == 2){
								$decimal = strlen($exp[1]);
							}
							else{
								$decimal = 0;
							}
						}else{
							if( isset( $headerVal['noDecimal'] ) && $headerVal['noDecimal'] ){
								$decimal = 0;
							}
						}
						$align   = 'R';
						$value   = (is_numeric($val[$headerVal['dataIndex']]))? $val[$headerVal['dataIndex']] : 0;
						
						if( $value < 0 ){
							$r = 255; // change text color to red;
							$value = '(' . number_format(abs($value),$decimal) . ')';
						}
						else{
							$value 	 = number_format($value,$decimal);
						}
						
						// increment total per column
						if( isset( $headerVal['total'] ) ){
							if(isset($headerVal['isRunning']) && $headerVal['isRunning'] == true){
								$params['header'][$key]['total'] = ( float )$val[$headerVal['dataIndex']];
							}else{
								$params['header'][$key]['total'] += ( float )$val[$headerVal['dataIndex']];
							}
							$params['header'][$key]['decimalFormat'] =  $decimal;
						}
					}
					else if($headerVal['type'] == 'datecolumn'){
						$align   = 'R';
						if(is_date_check($val[$headerVal['dataIndex']])){
							$value = date(( isset( $headerVal['format'] )? $headerVal['format'] : 'm/d/Y' ),strtotime($val[$headerVal['dataIndex']]));
						}
						else{
							$value = $val[$headerVal['dataIndex']];
						}
					}
				}
				
				
				$currentHeight = $ci->pdf->getPageHeight()-10;
				$currentY = $ci->pdf->GetY();
	
				if($rowHeight > ($currentHeight - $currentY) ){
					$ci->pdf->AddPage(isset($params['orientation'])?$params['orientation']:'P');
					$first_border_nextPage = true;
				}
				
				if($first_border_nextPage){
					$recBorder = 'LTRB';
					if($lastColumn == $headerVal['dataIndex'])	$first_border_nextPage = false;
				}
				
				
				$text = $value;
				$html = false;
				
				if($value != strip_tags($value)) {
					$html = true;
				}
				
				$ci->pdf->SetTextColor( $r, $g, $b );
				$ci->pdf->MultiCell($rowWidth,$rowHeight,$text ,$recBorder,$align,false, $recLn,'','', true,0,$html,$autopading=true,$maxh = 0,$valign = 'T',$fitcell = true );
			}
			
			$rowHeight=0;
			$recInc++;
		
		}

		
		/* SUMMATION */
		if( $hasTotal ){
			$ci->pdf->SetFont( $header_font_family, $header_font_style, $header_font_size );
			$sumHeight = 0;
			
			foreach( $params['header'] as $header ){
				if( isset( $header['total'] ) ){
					// print_r($header);
					$cntRecordHeight = $ci->pdf->getStringHeight( $orientationWidth * ($header['width'] / 100) , number_format( $header['total'], $header['decimalFormat'] ), false, true, '', 1 );
					if( $cntRecordHeight > $sumHeight ){
						$sumHeight = $cntRecordHeight;
					}
				}
			}
			
			foreach( $params['header'] as $header ){
				$r = 0;
				$g = 0;
				$b = 0;
				if(isset($header['width'])) $rowWidth = $orientationWidth * ($header['width'] / 100);
				else $rowWidth=$orientationWidth / count($params['header']);
				
				if( isset( $header['total'] ) ){
					$ci->pdf->SetFillColor( 240, 240, 240 );
					if( floatval($header['total']) < 0 ){
						$r = 255;
						$total = '(' . number_format( abs($header['total']), $header['decimalFormat'] ) . ')';
					}
					else{
						$total 	 = number_format( $header['total'], $header['decimalFormat'] );
					}
					$border = 'LTRB';
				}
				else{
					$ci->pdf->SetFillColor(255,255,255);
					$total  = '';
					$border = 'T';
				}
				$ci->pdf->SetTextColor( $r, $g, $b );
				$ci->pdf->MultiCell( $rowWidth, $sumHeight, $total, $border, 'R', true, 0, '', '', true, 0, '', true, 0, 'T', true );
			}
		}
		$ci->pdf->SetTextColor( 0, 0, 0 );

		/* For additional grid/table */
		if( isset( $params['extraHeader'] ) ){
			$divider = '<div style="height: 50px;"></div>';
			$ci->pdf->writeHTML($divider, true, false, false, false, '');
			
			$extraHeader = $params['extraHeader'];
			generate_table( $extraHeader['params'], $extraHeader['headers'] , $extraHeader['records'] );
		}

		if( isset( $params['journalEntry']) ){
			$divider = '<div style="height: 50px;"></div>';
			$ci->pdf->writeHTML($divider, true, false, false, false, '');
			printJournalEntry( $params['params'] );
		}

		if( isset( $params['hasSignatories'] ) && (int)$params['hasSignatories'] == 1 ) getfooter(1, $params);

		
		if(isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'],'Google App Engine') !== false){
			$directoryPath  = 'gs://newProject/';
		}
		else{
			$directoryPath='./';
		}
		
		if(!is_dir($directoryPath.'pdf/')){
			rmkdir($directoryPath.'pdf/');
			if(!is_dir($directoryPath.$params['folder_name'])) rmkdir('./'.$params['folder_name']);
	  }
		
		 if(file_exists($directoryPath.'pdf/'.$params['folder_name'].$params['file_name'].'.pdf')){
			 @unlink($directoryPath.'pdf/'.$params['folder_name'].$params['file_name'].'.pdf');
		 }
		
		ob_end_clean();
		$ci->pdf->Output($directoryPath.'pdf/'.$params['folder_name'].'/'.$params['file_name'].'.pdf', 'F');

		if(file_exists($directoryPath.'pdf/'.$params['folder_name'].'/'.$params['file_name'].'.pdf')){
			die(json_encode(array('success'=>true,'match'=>0)));
		}
		else{
			die(json_encode(array('success'=>true,'match'=>1)));
		}
	}
}

if( !function_exists( 'is_date_check' ) ){
	function is_date_check( $str ){
		// if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$str)){
		// 	return TRUE;
		// }
		// else{
		// 	$stamp = strtotime( $str );
		// 	if ( !is_numeric($stamp) || !preg_match("^\d{1,2}[.-/]\d{2}[.-/]\d{4}^", $str) ){
		// 		return FALSE;
		// 	}
		// 	$month = date( 'm', $stamp );
		// 	$day   = date( 'd', $stamp );
		// 	$year  = date( 'Y', $stamp );
		// 	if (checkdate( $month, $day, $year )){
		// 		return TRUE;
		// 	}
		// 	return FALSE;
		// }
		return (
			DateTime::createFromFormat('Y-m-d', $str) !== FALSE
			||
			DateTime::createFromFormat('Y-m-d H:i:s', $str) !== FALSE
		);
	}
}
	
if( !function_exists( 'sortQuery' ) ){
	function sortQuery( $sortData ){
		$ci   =& get_instance();
		$sort = json_decode( $sortData, true);
		$sort = $sort[0];
		
		if( $ci->db->using_select ){
			$ci->db->order_by( $sort['property'], $sort['direction'] );
		}
		else{
			return "ORDER BY " .$sort['property']. ' ' . $sort['direction'];
		}
		
		
	}
}
	
if( !function_exists( 'LQ' ) ){
	function LQ(){
		$ci  =& get_instance();
		var_dump( $ci->db->last_query() );
	}
}

if(! function_exists( 'setHeader' )){
	function setHeader( $modelPath = "", $model = "" ){
		date_default_timezone_set('Asia/Manila');
		
		header("X-Frame-Options: SAMEORIGIN");
		header("X-Content-Security-Policy: default-src 'self'; script-src 'self';");
		header("X-Content-Type-Options: nosniff");
		header("X-XSS-Protection: 1; mode=block");
		
		$ci  =& get_instance();
		
		if( !empty( $modelPath ) ){
			$ci->load->model( $modelPath, 'model' );
		}
		
		if( $model !== "" && !empty( $modelPath ) )
		{
			$ci->load->model( $modelPath, $model );
		}
		
		$ci->load->model( 'standards/Standards_model', 'standards' );
		$ci->load->model( 'Home_model', 'home' );
		$ci->load->model( 'pdf' );
		$ci->load->helper( 'url' );
		$ci->load->helper( 'download' );
		$ci->load->helper( 'csv' );
		$ci->load->helper( 'file' );
		
		/** loop through all session variables and set as $this->variableName **/
		foreach($ci->session->all_userdata() as $key => $data){
			$ci->{$key} = $data;
		}
		
		/** this will prevent the session to initialize if the ajax process is background process **/
		$functionName = $ci->uri->segment(3);
		// if($functionName != 'preventSession'){
			// resetSessionExpiry();
		// }
	}
}

if( !function_exists( 'unsetParams' ) ){
	function unsetParams( $data, $table ){
		$ci =& get_instance();
		foreach( $data AS $key => $val ){
			if( !$ci->db->field_exists( $key, $table ) ){
				unset( $data[$key] );
			}
		}
		return $data;
	}
}

if( !function_exists( '_checkData' ) ){
	function _checkData( $params ){
		$ci =& get_instance();
		return $ci->standards->_checkData( $params );
	}
}

if( !function_exists( 'setLogs' ) ){
	function setLogs( $data ){
		$ci =& get_instance();
		$data['dateLog'] = date('Y-m-d');
		$data['idEu'] = $ci->session->userdata('USERID');
		$data['time'] = date('H:i:s');
		$ci->standards->setLogs( $data );
	}
}	

// if( !function_exists( 'resetSessionExpiry' ) ){
	// function resetSessionExpiry(){
		// $ci =& get_instance();
		// $ci->session->mark_as_temp(array( 'logged_in' ), DEFAULT_SESSION_TIMEOUT);
	// }
// }

if( !function_exists( 'getList' ) ){
	function getList( $data ){
		if( isset( $data['sqlQuery'] ) ){
			$query 		 = $data['sqlQuery'];
			$result		 = array();
			$pdf 		 = isset( $data['pdf'] )? $data['pdf'] : false;
			$ci 		 =& get_instance();
			
			
			/** ===== WHERE CLAUSE ===== **/
			$where_clause= "";
			$where_field = "";
			$where_tag   = "<-where:";
			$where_syntax= "WHERE";
			$pos   		 = strpos( $query, $where_tag );
			
			if( empty( $pos ) ){
				$where_tag   = "<-and_where:";
				$where_syntax= "AND";
				$pos   		 = strpos( $query, $where_tag );
			}
			
			if( !empty( $pos ) ){
				$start = $pos + strlen( $where_tag );
				while( substr( $query, $start, 2 ) != "->" ){
					$where_field .= substr( $query, $start, 1 );
					$start++;
				}
				
				$query_fields = explode( "&&", $where_field );
				$x = 0;
				foreach( $query_fields as $field ){
					if( isset( $data['query'.$x] ) && $data['query'.$x] ){
						$where_clause .= ( $x == 0? $where_syntax : "AND" )." ".$field." LIKE '%".$data['query'.$x]."%' ";
					}
					$x++;
				}
				
				$query = str_replace( $where_tag ."". $where_field ."->", $where_clause, $query );
			}
			
			
			/** ===== SORT CLAUSE ===== **/
			$sort_clause= "";
			$sort_field = "";
			$sort_tag   = "<-sort:";
			$pos   		 = strpos( $query, $sort_tag );
			
			if( !empty( $pos ) ){
				$start = $pos + strlen( $sort_tag );
				while( substr( $query, $start, 2 ) != "->" ){
					$sort_field .= substr( $query, $start, 1 );
					$start++;
				}
				
				if( isset( $data['sort'] ) ){
					$sort 		 = json_decode( $data['sort'], true);
					$sort_clause = "ORDER BY ".$sort[0]['property']. ' ' . $sort[0]['direction'];
				}
				else{
					$sort_clause = "ORDER BY ".$sort_field;
				}
				
				$query = str_replace( $sort_tag ."". $sort_field ."->", $sort_clause, $query );
			}
			
			
			/** ===== LIMIT CLAUSE ===== **/
			$limit_clause= "";
			$limit_tag   = "<-limit->";
			$pos   		 = strpos( $query, $limit_tag );
			$sqlQuery	 = array();
			
			if( !empty( $pos ) ){
				if( $pdf ){
					$sqlQuery[] = str_replace( $limit_tag, "", $query );
				}
				else{
					$limit_clause = "LIMIT $data[start], $data[limit]";
					$sqlQuery[] = str_replace( $limit_tag, $limit_clause, $query );
					$sqlQuery[] = str_replace( $limit_tag, "", $query );
				}
			}
			
			
			/** ===== EXECUTING QUERY ===== **/
			for( $x=0; $x<count( $sqlQuery ); $x++ ){
				$getQuery = $ci->db->query( $sqlQuery[$x] );
				$result[] = ( $x == 0 )? $getQuery->result_array() : $getQuery->num_rows();
			}
			
			return $pdf? $result[0] : $result;
		}
	}
}

/** This function uses active records for 
	- sorting 
	- returning count
	- returning records
	- set main table
**/
if( !function_exists( 'getGridList' ) ){
	function getGridList( $data ){
		$result		 = array();
		$ci 		 =& get_instance();
		$pdf 		 = isset( $data['pdf'] )? $data['pdf'] : false;
		$filterAffiliate = isset( $data['filterAffiliate'] )? $data['filterAffiliate'] : true; 
		
		/**	get_table_from is a user defined function in database core file
			system/database/DB_query_builder.php
		**/
		$table = $data['db']->get_table_from();
		
		$singleTable = isset($table[0]) ? $table[0] : '';
		$singleTableAlias = '';		
		
		if(isset($table[0]) && strpos(strtolower($table[0]),'as ')){
			if($tbl = explode('as ',strtolower($table[0]))){
				if(count($tbl) == 2){
					$singleTable = $tbl[0];
					$singleTableAlias = $tbl[1].'.';
				}
			}
		}
		/** remove backticks **/
		$singleTable = trim(str_replace(array("`",chr(96)),"", $singleTable));
		
		// add validation if invoices || bankrecon in the main table filter by affiliate.
		if(($ci->session->userdata('ISMAIN') == 0 && $filterAffiliate) 
		|| ($singleTable == 'invoices' || $singleTable == 'bankrecon')){
			/** check if affiliateID exists in selected db
				if yes and sub affiliate then filter record by affiliateID **/
			if($data['db']->field_exists('affiliateID',$singleTable)){
				$data['db']->where($singleTableAlias . 'affiliateID',$ci->session->userdata('AFFILIATEID'));
			}
		}
		
		if(isset($data['isTransaction']) && $data['isTransaction'] == 1){
			/** if transaction then subFilter0 is referenceID 
				if transaction then query0 is referenceNo **/
			if(isset($data['subFilter0']) && $data['subFilter0'] != -1){
				$data['db']->where($singleTableAlias . 'referenceID' ,$data['subFilter0']);
			}
			if(isset($data['query0']) && $data['query0']){
				$data['db']->like($singleTableAlias . 'referenceNo' ,$data['query0']);
			}
		}
		else{
			if(isset($data['subFilter0']) && isset($data['query0'])){
				if($data['db']->field_exists( $data['subFilter0'], $singleTable)){
					$data['db']->where($singleTableAlias . $data['subFilter0'],$data['query0']);
				}
			}
		}
		
		$count = $data['db']->count_all_results('',FALSE);
		
		if( isset( $data['sort'] ) ){
			$sort = json_decode( $data['sort'], true);
			$sort = $sort[0]['property']. ' ' . $sort[0]['direction'];
			$data['db']->order_by($sort);
		}
		else{
			$data['db']->order_by($data['order_by']);
		}
		
		if($pdf){
			return $data['db']->get()->result_array();
		}
		else{
			
			if( isset( $data['limit'] )  || isset( $data['start']) ) $data['db']->limit($data['limit'],$data['start']);
			$view = $data['db']->get()->result_array();
			return array(
				'view' => $view
				,'count' => $count
			);
		}
	}
}

/** This function will check if image exits by URL **/
if( !function_exists( 'is_url_exist' ) ){
	function is_url_exist($url){
		if(isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'],'Google App Engine') !== false){
			if( !file_exists( $url ) ){
				return false;
			}
			else{
				return true;
			}
		}
		else{
			$ch = curl_init($url);    
			curl_setopt($ch, CURLOPT_NOBODY, true);
			curl_exec($ch);
			$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			if($code == 200){
			   $status = true;
			}else{
			  $status = false;
			}
			curl_close($ch);
			
			return $status;
		}
	}
}

if( !function_exists( '_getDateStart' ) ){
	function _getDateStart( $params ){
		
		$ci =& get_instance();
		$affiliateList = $ci->standards->getAffiliateListing( (int)$params['affiliateID'] );
		$sqlFrom = '';
		$filterArrayRet = array();
		$paramsArr = [];
		foreach( $affiliateList as $rs ){
			array_push( $paramsArr, array( 'affiliateID' => $rs['affiliateID'], 'dateStart' => $rs['datestart'] ) );
		}
		return $paramsArr;
	}
}

if(!function_exists('getallheaderss')){
	function getallheaderss(){
		foreach($_SERVER as $name => $value){
			if(substr($name, 0, 5) == 'HTTP_'){
				$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
			}
		}
		return $headers;
	}
}

if( !function_exists( 'unsetParamsBatch' ) ){
	function unsetParamsBatch( $data, $table ){
		$ci =& get_instance();
		$cnt = 0;
		$newData = array();
		
		if( !empty( $data ) ) $fieldChecker = $data[0];
		$tempData = array();

		if( isset( $fieldChecker ) ){
			foreach( $fieldChecker AS $key => $val ){
				if( !$ci->db->field_exists( $key, $table ) ){
					unset( $fieldChecker[$key] );
				}
			}
			
			if(!empty($fieldChecker) && count($fieldChecker) > 0){
				foreach( $data AS $srcData ){
					foreach($fieldChecker AS $key => $val){
						$tempData[$key] = $srcData[$key];
					}
					array_push($newData,$tempData);
					$tempData = array();
				}
			}
		}

		return $newData;
	}
}

// if( !function_exists( 'autoSave' ) ){
// 	function autoSave( $data ){
// 		$ci =& get_instance();
// 		if( isset( $data['isBatch'] ) && $data['isBatch'] ){
// 			$ci->db->insert_batch( $data['table'] ,unsetParamsBatch( $data['fields'], $data['table'] ) );
// 		}else{
// 			$ci->db->insert( $data['table'] ,unsetParams( $data['fields'], $data['table'] ) );
// 		}
// 		return ( $ci->db->affected_rows() > 0 ? true : false);
// 	}
// }

if( !function_exists( 'autoDelete' ) ){
	function autoDelete( $params ){
		$ci =& get_instance();
		if( !isset( $params[ 'table' ] ) ){
			return false;
		}
		if( !isset( $params[ 'compare' ] ) ){
			return false;
		}
		$condType = "";
		foreach( $params[ 'compare' ]  as $key ){
			if( !isset( $key[ 'field' ] ) && !isset($key[ 'concatCondition' ]) ){
				return false;
			}
			if( isset( $key[ 'field' ] ) ){
				if( $key[ 'compType' ] == '=' ){
					if( $condType == "AND" || $condType == "" ){
						$ci->db->where( $key[ 'field' ], $key[ 'value' ] );
					}
					else{
						$ci->db->or_where( $key[ 'field' ], $key[ 'value' ] ); 
					}
				}
				else{
					if( $compType == "AND" || $condType == "" ){
						$ci->db->where( $key[ 'field' ] . $key[ 'compType' ] , $key[ 'value' ] );
					}
					else{
						$ci->db->or_where( $key[ 'field' ] . $key[ 'compType' ] , $key[ 'value' ] ); 
					}
				}
			}
			else{
				$condType = "AND";
				if( $key[ 'concatCondition' ] == "OR" ){
					$condType = "OR";
				}
			}
		}
		$ci->db->delete( $params[ 'table' ] );
		return ( $ci->db->affected_rows() > 0 ? true : false);
	}
}

if( !function_exists( 'autoUpdate' ) ){
	function autoUpdate( $data ){
		$ci =& get_instance();
		if( !isset( $data[ 'table' ] ) ){
			return false;
		}
		if( !isset( $data[ 'fields' ] ) ){
			return false;
		}
		
		foreach( $data[ 'fields' ] as $key ){
			foreach( $key[ 'conditions' ] as $keyConditions ){
				$ci->db->where( $keyConditions[ 'field' ], $keyConditions[ 'value' ] );
			}
			$ci->db->update( $data[ 'table' ], unsetParams( $key[ 'data' ], $data[ 'table' ] ) );
			if( $ci->db->affected_rows() <= 0 ){
				return false;
			}
		}
	}
}

// if( !function_exists( '_doProcess' ) ){
// 	function _doProcess( $data )
// 	{
			
// 		$ci =& get_instance();
// 		$ci->db->trans_begin();

// 		foreach( $data[ 'trans' ] AS $key => $value)
// 		{
// 			$value[ 'data' ][ 'dateModified' ] = date( "Y-m-d H:i:s" );
// 			$ret = $ci->model->$value[ 'function' ]( $value[ 'data' ] );

// 			if( isset( $value[ 'hasReturn' ] ) )
// 			{
// 				return $ret;
// 				exit();
// 			}

// 		}

// 		$success = true;

// 		if( $ci->db->trans_status() === FALSE )
// 		{

// 			$ci->db->trans_rollback();
// 			$success = false;

// 		}
// 		else
// 		{
// 			$ci->db->trans_commit();
// 		}

// 		killProcess(
// 			array(
// 				'success' => $success
// 				,'match' => 0
// 			)
// 		);
		
// 	}
// }

if( !function_exists( 'killProcess' ) ){
	function killProcess( $params )
	{

		die(
			json_encode(
				$params
			)
		);

	}
}

if( !function_exists( 'loadDBConfig' ) ){
	function loadDBConfig( $params ){
		$config_app = [];
		$config_app['hostname'] = $params['hostname'];
		$config_app['username'] = $params['username'];
		$config_app['password'] = $params['password'];
		$config_app['database'] = $params['database'];
		$config_app['dbdriver'] = $params['dbdriver'];
		$config_app['pconnect'] = FALSE;
		return $config_app;
	}
}


// if( !function_exists('_checkPrevNotFinal') ){
	// function _checkPrevNotFinal( $params ){
		// $ci =& get_instance();
		// return $ci->db->query('
			// SELECT
				// invoiceID
			// FROM 
				// invoices
			// WHERE
				// affiliateID = '. (int)$params->affiliateID .'
			// AND
				// month = '. $params->month .'
			// AND
				// year = '. $params->year .'
			// AND 
				// status = 0
			// AND 
				// invoiceID = '. $params->invoiceID .'
			// LIMIT 1
		// ')->row();
	// }
// }

// if( !function_exists('_saveInvoice') ){
	// function _saveInvoice( $data ){
		// $ci =& get_instance();
		// $ci->db->insert( 'invoices', unsetParams( $data, 'invoices' ) );
		// $invoiceID = $ci->db->insert_id();
		// LQ();
        // $data['invoiceID'] = $invoiceID;
        // $ci->db->insert( 'invoiceshistory', unsetParams( $data, 'invoiceshistory' ) );
        // return $invoiceID;
	// }
// }



/* if( !function_exists( '_getCEReferenceNumber' ) ){
	function _getCEReferenceNumber( $params ){
		$ci =& get_instance();
		
		$refID = $ci->db->select("refID,code")->where("moduleID", $params['moduleID'])->get("reference")->row_object();

		$referenceID = isset($refID->refID) ? $refID->refID : 0;
		$refCode = isset($refID->code) ? $refID->code : '';


		$ci->db->select("referenceID AS refID, ifnull(MAX(referenceNo),0)+1 as refNum",false);
		$ci->db->where('referenceID', $referenceID);
		$ci->db->where('affiliateID', $params['affiliateID']);
		$ci->db->where('moduleID', $params['moduleID']);
				
		$ret = $ci->db->get('invoices')->row_object();
		return $ret;
	}
} */

if( !function_exists( 'merge_by_empno' ) ){
	function merge_by_empno( array $arr1, array $arr2, $type = 1 ){
		$keyToRemove = [];
		$final=[];
		foreach ($arr1 as $key1=>$data1){
			foreach ($arr2 as $key2=>$data2){
				if(trim($data1['employeeNo']) == trim($data2['employeeNo'])){
					$final[] = array_replace( $data1 , $data2 );
					unset($arr1[$key1]);
					if( $type == 2 ) $keyToRemove[] = $key2;
				}
			}
		}
		if(!empty($arr1)){
			foreach ($arr1 as $value){
				$final[]=$value;
			}
		}
		if( $type == 2 ){
			if(!empty($arr2)){				
				foreach( $keyToRemove as $val ){
					unset( $arr2[$val] );
				}
				foreach ($arr2 as $value){
					$final[]=$value;
				}
			}
		}
		
		return $final;
	}
}

if( !function_exists( 'merge_by_date' ) ){
	function merge_by_date( array $arr1, array $arr2, $type = 1, $isHeadCount = 0 ){
		$final=[];
		foreach ($arr1 as $key1=>$data1){
			foreach ($arr2 as $key2=>$data2){
				if( $data1['employeeNo'] == $data2['employeeNo'] ){
					if( $type == 1 || $isHeadCount == 1 ){
						if($data1['timelogDate'] == $data2['timelogDate']){
							$final[] = array_replace( $data1 , $data2 );
							unset($arr1[$key1]);
							unset($arr2[$key2]);
						}
					}
					else{
						if( isset( $data1['u_id'] ) && isset( $data2['u_id'] ) ){
							if($data1['timelogDate'] == $data2['timelogDate'] && trim( $data1['u_id'] ) == trim( $data2['u_id'] ) ){
								$final[] = array_replace( $data1 , $data2 );
								unset($arr1[$key1]);
								unset($arr2[$key2]);
							}
						}
						else{
							if( $data1['timelogDate'] == $data2['timelogDate'] ){
								$final[] = array_replace( $data1, $data2 );
								unset( $arr1[$key1] );
								unset( $arr2[$key2] );
							}
						}
					}
				}
			}
		}
		if(!empty($arr1)){
			foreach ($arr1 as $value){
				$final[]=$value;
			}
		}
		if( $type < 3 ){
			if(!empty($arr2)){
				foreach ($arr2 as $value){
					$final[]=$value;
				}
			}
		}
		
		return $final;
	}
	
	function str_compare( $a, $b ){
		if( isset( $a['employeeName'] ) && isset( $b['employeeName'] ) ) return strcmp( $a['employeeName'], $b['employeeName'] );
		else return -1;
	}
	
	function date_compare_v1( $a, $b ){
		$t1 = strtotime($a['timelogDate']);
		$t2 = strtotime($b['timelogDate']);
		return ( $t1 - $t2 );
	}
	function date_compare_v2( $a, $b ){
		$t1 = strtotime($a['timelogDate']);
		$t2 = strtotime($b['timelogDate']);
		if( $t1 == $t2 ){
			if( isset( $a['employeeName'] ) && isset( $b['employeeName'] ) ){
				return strcmp( strtolower( $a['employeeName'] ), strtolower( $b['employeeName'] ) );
			}
			return ( $t1 - $t2 );
		}
		else return ( $t1 - $t2 );
	}
	function order($arr, $key=null, $direction='ASC'){
		if(!is_string($key) && !is_array($key))
			throw new InvalidArgumentException("order() expects the first parameter to be a valid key or array");

		$props = array();

		if(is_string($key)) {
			$props[$key] = strtolower($direction) == 'asc' ? 1 : -1;
		}else{
			$i = count($key);
			foreach($key as $k => $dir){
				$props[$k] = strtolower($dir) == 'asc' ? $i : -($i);
				$i--;
			}
		}

		usort($arr, function($a, $b) use ($props){
			$i = 1;
			foreach( $props as $key => $val ){
				if( $key == 'timelogDate' ) if( strtotime( $a[$key] ) == strtotime( $b[$key] ) ) continue;
				if( $key == 'employeeName' ) return strcmp( strtolower( trim($a[$key]) ), strtolower( trim($b[$key]) ) ) > 0? 1 : -1;
			}
			return 0;
		});

		return $arr;

	}
	
	function remove_by_date( array $arr1, array $arr2 ){
		foreach ($arr1 as $key1=>$data1){
			foreach ($arr2 as $key2=>$data2){
				if( isset( $data1['u_id'] ) && isset( $data2['u_id'] ) ){
					if($data1['timelogDate'] == $data2['timelogDate'] && trim( $data1['u_id'] ) == trim( $data2['u_id'] ) ){
						unset($arr1[$key1]);
						unset($arr2[$key2]);
					}
				}
				else{
					if( $data1['timelogDate'] == $data2['timelogDate'] ){
						unset( $arr1[$key1] );
						unset( $arr2[$key2] );
					}
				}
			}
		}
		return array_values( $arr1 );
	}
}

if( !function_exists( 'send_email' ) ){
	function send_email($arr){
		$ci =& get_instance();
	
		// Notice that $image_content_id is the optional Content-ID header value of the
		// attachment. Must be enclosed by angle brackets (<>)
		if(isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'],'Google App Engine') !== false){
			$image_content_id = '';
			// Pull in the raw file data of the image file to attach it to the message.
			$image_data = ( !isset( $arr['attachment'] ) ? 'no_attachment': file_get_contents( $arr['attachment'] )  );
			
			
			try {
				
				$message = new Message();
				
				$message->setSender('no-reply@syntacticsinc.com');
				$message->addTo('testaccdev76@gmail.com');
				$message->setSubject('FIFO | '.$arr['subject']);
				$message->setTextBody($arr['body']);
			
				if( $image_data != 'no_attachment' ){$message->addAttachment(end(explode('/',$arr['attachment'])) , $image_data);}
				$message->send();
				
			} catch (InvalidArgumentException $e) {
				print_r($e);
				die(json_encode(array('success' => false)));
				
			}
		}
		else{
			try {
				ob_start();
				$ci->load->library ( 'email' );
				
				$ci->email->set_newline( "\r\n" );
				$ci->email->set_mailtype ( "text/html" );
				
				$ci->email->from ( 'no-reply@gmail.com', 'Kiokong Trucking and Construction' );
				$ci->email->to ( $arr[ 'to' ] );
				$ci->email->reply_to ( 'no-reply@gmail.com', 'no-reply' );
				$ci->email->subject ( 'Kiokong Trucking and Construction | '. $arr[ "subject" ] );
				
				$ci->email->message(  $arr['body'] );
				if( $arr['attachment'] ) $ci->email->attach( $arr['attachment'] );
				if($ci->email->send()){ ob_end_flush(); return true; } 
				else show_error($ci->email->print_debugger());
				ob_end_flush();
				// if( $image_data != 'no_attachment' ){$message->addAttachment(end(explode('/',$params['attachment'])) , $image_data);}
				// $message->send();
				return true;
				
			} catch (InvalidArgumentException $e) {
				print_r( $e );
				return false;
			}
		}

	}
}

if( !function_exists( 'connectHURIS' ) ){
	
	function connectHURIS(){
		$ci =& get_instance();
		/* Load HURIS Records */
		$config_app2 = loadDBConfig( array(
			'hostname' => trim($ci->HURISIPADDRESS)
			,'username'=> trim($ci->HURISDBUNAME)
			,'password'=>$ci->encryption->decrypt( $ci->HURISDBKEY )
			,'database'=> trim($ci->HURISDB)
			,'dbdriver'=>'sqlsrv'
		) );
		
		
		$config_app2['stricton'] = FALSE;
		$dbApp2 = $ci->load->database( $config_app2, TRUE );
		if( !$dbApp2->initialize() ) return false;
		else return $dbApp2;
	}
	
}

if( !function_exists( 'connectHeadCount' ) ){
	
	// function connectHeadCount(){ /* commented by jays - moved from Access to MSSQL */
		// $ci =& get_instance();
		// /* get records from HEADCount */
		// $config_app = loadDBConfig( array( /* define access database connection parameters */
			// 'hostname' =>'DRIVER={Microsoft Access Driver (*.mdb, *.accdb)};DBQ=' . $ci->HEADCOUNTDBLOC
			// ,'username'=>''
			// ,'password'=>''
			// ,'database'=>''
			// ,'dbdriver'=>'odbc'
		// ) );
		// $dbApp = $ci->load->database( $config_app, TRUE ); /* load access database */
		// if( !$dbApp->initialize() ) return false;
		// else return $dbApp;
	// }
	
	function connectHeadCount(){
		$ci =& get_instance();
		/* Load HURIS Records */
		$config_app = loadDBConfig( array(
			'hostname' => trim($ci->HEADCOUNTIPADDRESS)
			,'username'=> trim($ci->HEADCOUNTDBUNAME)
			,'password'=>$ci->encryption->decrypt( $ci->HEADCOUNTDBKEY )
			,'database'=> trim($ci->HEADCOUNTDB)
			,'dbdriver'=>'sqlsrv'
		) );
		
		
		$config_app['stricton'] = FALSE;
		$dbApp = $ci->load->database( $config_app, TRUE );
		if( !$dbApp->initialize() ) return false;
		else return $dbApp;
	}
	
}

if( !function_exists( 'personalArraySearch' ) ){
	function personalArraySearch( $array, $searchVal ){
		foreach( $array as $key=>$rep ){
			if( in_array( $searchVal, $rep ) ){
				return true;
			}
		}
		return false;
	}
}

if( !function_exists( '_validateReport' ) ){
	function _validateReport( $params ){
		$ci =& get_instance();
		
		/* check if month and year to generate is lesser than the affiliate date start */
		/* first get affiliate date start */
		$affiliateRec	= $ci->standards->getAffiliateDetailsValidation( $params );
		if( count( (array)$affiliateRec ) > 0 ){
			if( ( $params['year'] < $affiliateRec['year'] && $params['month'] <= $affiliateRec['month'] )
				|| $params['year'] <= $affiliateRec['year'] && $params['month'] < $affiliateRec['month'] ){
				die(
					json_encode(
						array(
							'success'	=> true
							,'match'	=> 3
							,'view'		=> ''
						)
					)
				);
			}
		}

		/* first check if there are closing entry recorded */
		if( !_checkData(
			array(
				'table'		=> 'invoices'
				,'field'	=> 'idModule'
				,'value'	=> 35
				,'exwhere'	=> "archived NOT IN( 1 ) AND idAffiliate = $params[idAffiliate] AND month = $params[month] AND year = $params[year]"
			)
		) ){
			die(
				json_encode(
					array(
						'success'	=> true
						,'match'	=> 1
						,'view'		=> ''
					)
				)
			);
		}

		/* retrieve last closed record */
		$lastRec	= $ci->standards->getLastClosed( $params );
		if( count( (array)$lastRec ) > 0 ){
			/* if has previous record, check if previous record is tagged as final */
			if( (int)$lastRec['status'] != 2 ){
				die(
					json_encode(
						array(
							'success'	=> true
							,'match'	=> 2
							,'view'		=> ''
						)
					)
				);
			}
		}
	}
}

if( !function_exists( '_evaluateFSParams' ) ){
	function _evaluateFSParams( $params, $idAffiliate ){
		$ci =& get_instance();
		/* first get affiliate(main or selected) information */ 
		$affiliateInfo		= $ci->standards->getAffiliateDetailsValidation( array( 'idAffiliate'	=> $idAffiliate ) );
		if( count( (array)$affiliateInfo ) > 0 ){
			if( $params['idAffiliate'] == 0 ){
				$params['affiliateName']	= $affiliateInfo['affiliateName'] . '(Consolidated)';
			}
			if( (int)$affiliateInfo['accSchedule'] == 1 ){ /* calendar */
				$params['prevMonth'] = '12';
				/* audited previous range */
				$params['prevSDate'] = ( (int)$params['year'] - 1 ) . '-1-1';
				$params['prevEDate'] = date( 'Y-m-t', strtotime( ( (int)$params['year'] - 1 ) . '-12-01' ) );
				$params['prevYear'] = (int)$params['year'] - 1;
				/* accumulated previous range  */
				$params['prevAccSDate'] = ( (int)$params['year'] - 1 ) . '-1-1';
				$params['prevAccEDate'] = date( 'Y-m-t', strtotime( ( (int)$params['year'] - 1 ) . '-' . $params['month'] . '-01' ) );
				/* accumulated current year */
				$params['curAccSDate'] = $params['year'] . '-1-1';
				$params['curAccEDate'] = date( 'Y-m-t', strtotime( $params['year'] . '-' . $params['month'] . '-01' ) );
				$params['curYearPrev'] = (int)$params['year'] - 1;
			}
			else{ /* fiscal */
				$month = (int)$params['month'];
				$monthset = (int)$affiliateInfo['affiliateMonth'];
				$monthStart = $monthset;
				if( $monthset == 12 ){
					$monthStart = 1;
					$params['prevMonth']  = 12;
				}
				else{
					$monthStart = $monthset + 1;
					$params['prevMonth'] = (int)$affiliateInfo['affiliateMonth'] + 1;
				}
				if( $month >= $monthStart ){
					/* audited previous range - must be  year - 1 to current year */
					$params['prevSDate'] = ( (int)$params['year'] - 1 ) . '-' . str_pad( $monthStart, 2, '0', STR_PAD_LEFT ) . '-1';
					$params['prevEDate'] = date( 'Y-m-t', strtotime( ( $monthStart == 1? ( (int)$params['year'] - 1 ) : $params['year'] ) . '-' . ( (int)$affiliateInfo['affiliateMonth'] ) . '-1' ) );
					$params['prevYear'] = (int)$params['year'] - 2;
					/* accumulated previous range - must be year - 1 to current year of month selected */
					$params['prevAccSDate'] = ( (int)$params['year'] - 1 ) . '-' . str_pad( $monthStart, 2, '0', STR_PAD_LEFT ) . '-1';
					$params['prevAccEDate'] = date( 'Y-m-t', strtotime( ( (int)$params['year'] - 1 ) . '-' . (int)$params['month'] . '-1' ) );
					/* accumulated current year - must be current year to year + 1 of month selected */
					$params['curAccSDate'] = (int)$params['year'] . '-' . str_pad( $monthStart, 2, '0', STR_PAD_LEFT ) . '-1';
					$params['curAccEDate'] = date( 'Y-m-t', strtotime( ( (int)$params['year'] ) . '-' . (int)$params['month'] . '-1' ) );
					$params['curYearPrev'] = (int)$params['year'];
				}
				elseif( $month < $monthStart ){
					/* audited previous range - must be  year - 2 to current year */
					$params['prevSDate'] = ( (int)$params['year'] - 2 ) . '-' . str_pad( $monthStart, 2, '0', STR_PAD_LEFT ) . '-1';
					$params['prevEDate'] = date( 'Y-m-t', strtotime( ( (int)$params['year'] - 1 ) . '-' . ( (int)$affiliateInfo['affiliateMonth'] ) . '-1' ) );
					$params['prevYear'] = (int)$params['year'] - 2;
					/* accumulated previous range - must be year - 1 to current year of month selected */
					$params['prevAccSDate'] = ( (int)$params['year'] - 2 ) . '-' . str_pad( $monthStart, 2, '0', STR_PAD_LEFT ) . '-1';
					$params['prevAccEDate'] = date( 'Y-m-t', strtotime( ( (int)$params['year'] - 1 ) . '-' . (int)$params['month'] . '-1' ) );
					/* accumulated current year - must be current year to year + 1 of month selected */
					$params['curAccSDate'] = (int)$params['year'] - 1 . '-' . str_pad( $monthStart, 2, '0', STR_PAD_LEFT ) . '-1';
					$params['curAccEDate'] = date( 'Y-m-t', strtotime( ( (int)$params['year'] ) . '-' . (int)$params['month'] . '-1' ) );
					$params['curYearPrev'] = (int)$params['year'];
				}
				else{
					/* audited previous range - must be year - 2 to year - 1 */
					$params['prevSDate'] = ( (int)$params['year'] - 1 ) . '-' . str_pad( $monthStart, 2, '0', STR_PAD_LEFT ) . '-1';
					$params['prevEDate'] = date( 'Y-m-t', strtotime( ( (int)$params['year'] ) . '-' . ( (int)$affiliateInfo['affiliateMonth'] ) . '-1' ) );
					$params['prevYear'] = (int)$params['year'] - 2;
					/* accumulated previous range - must be year - 2 to year - 1 of month selected */
					$params['prevAccSDate'] = ( (int)$params['year'] - 1 ) . '-' . str_pad( $monthStart, 2, '0', STR_PAD_LEFT ) . '-1';
					$params['prevAccEDate'] = date( 'Y-m-t', strtotime( ( (int)$params['year'] ) . '-' . (int)$params['month'] . '-1' ) );
					/* accumulated current range - must be year - 1 to current year of month selected */
					$params['curAccSDate'] = ( (int)$params['year'] ) . '-' . str_pad( $monthStart, 2, '0', STR_PAD_LEFT ) . '-1';
					$params['curAccEDate'] = date( 'Y-m-t', strtotime( ( (int)$params['year'] ) . '-' . (int)$params['month'] . '-1' ) );
					$params['curYearPrev'] = (int)$params['year'];
				}
			}
		}
		else{
			if( $params['idAffiliate'] == 0 ){
				$params['affiliateName']	= 'Consolidated';
			}
		}
		if( (int)$params['month'] == 1 ){
			$params['prevMonthly'] = 12;
			$params['prevMonthlyYr'] = (int)$params['year'] - 1;
		}
		else{
			$params['prevMonthly'] = (int)$params['month'] - 1;
			$params['prevMonthlyYr'] = (int)$params['year'];
		}
		return $params;
	}
}

if( !function_exists( '_createTrialBalanceView' ) ){
	function _createTrialBalanceView( $data ){
		$html = '
			<table style="width:100%; text-align:center;">
				<tr>
					<td><strong>' . $data['h1'] . '</strong></td>
				</tr>
				<tr>
					<td>' . $data['h2'] . '</td>
				</tr>
				<tr>
					<td> &nbsp; </td>
				</tr>
				<tr>
					<td> &nbsp; </td>
				</tr>
				<tr>
					<td><strong>TRIAL BALANCE</strong></td>
				</tr>
				<tr>
					<td><strong>' . $data['date'] . '</strong></td>
				</tr>
			</table>
			<br/>
			<table width="100%" border = "1" cellpadding = "10">
				<tr style = "font-weight:bold;text-align: center;background-color: #CCCCCC;text-align:center; padding: 5px !important;">
					<th style="width:10%; padding: 5px !important;">Code</th>
					<th style="width:60%; padding: 5px !important;">Account Titles</th>
					<th style="width:15%; padding: 5px !important;">DR</th>
					<th style="width:15%; padding: 5px !important;">CR</th>
				</tr>
			';
		$totalDR = 0;
		$totalCR = 0;
		foreach( (array)$data['array_Rows'] as $rs ){
			$html .= '
				<tr>
					<td style="padding: 5px !important;">' . $rs['acod_c15'] . '</td>
					<td style="padding: 5px !important;">' . $rs['aname_c30'] . '</td>
					<td style="text-align:right; padding: 5px !important;">' . ( $rs['debit'] != 0? number_format( $rs['debit'], 2 ) : '' ) . '</td>
					<td style="text-align:right; padding: 5px !important;">' . ( $rs['credit'] != 0? number_format( $rs['credit'], 2 ) : '' ) . '</td>
				</tr>';
			$totalDR += $rs['debit'];
			$totalCR += $rs['credit'];
		}
		$html .= '
				<tr>
					<td style="padding: 5px !important;"></td>
					<td style="font-weight: bold; padding: 5px !important;">Total</td>
					<td style="text-align:right;font-weight: bold; padding: 5px !important;">' . ( $totalDR != 0? number_format( $totalDR, 2 ) : '' ) . '</td>
					<td style="text-align:right;font-weight: bold; padding: 5px !important;">' . ( $totalDR != 0? number_format( $totalDR, 2 ) : '' ) . '</td>
				</tr>
			</table>
		';

		return $html;
	}
}

if( !function_exists( '_createIncomeStatementView' ) ){
	function _createIncomeStatementView( $params ){
		$html	= '
			<table style="width:100%; text-align:center;">
				<tr>
					<td><strong>' . $params['h1'] . '</strong></td>
				</tr>
				<tr>
					<td>' . $params['h2'] . '</td>
				</tr>
				<tr>
					<td> &nbsp; </td>
				</tr>
				<tr>
					<td> &nbsp; </td>
				</tr>
				<tr>
					<td><strong>TRIAL BALANCE</strong></td>
				</tr>
				<tr>
					<td><strong>' . $params['date'] . '</strong></td>
				</tr>
			</table>
			<br/>
			<br/>
			<table width="100%" cellpadding = "10">
				<tr style="font-weight:bold;text-align:center;border-bottom:1px solid #000;">
					<td style = "width:3%;border-bottom:1px solid #000;"></td>
					<td style = "width:37%;border-bottom:1px solid #000;"></td>
					<td style = "width:1%;border-bottom:1px solid #000;"></td>
					<td style = "width:11%;border-bottom:1px solid #000;">' . ( count( $params ) > 0? date( 'M Y', strtotime( $params['prevSDate'] ) ) . ' - ' . date( 'M Y', strtotime( $params['prevEDate'] ) ) : '' ) . '</td>
					<td style = "width:1%;border-bottom:1px solid #000;"></td>
					<td style = "width:11%;border-bottom:1px solid #000;">' . ( count( $params ) > 0? date( 'M Y', strtotime( $params['prevAccSDate'] ) ) . ' - ' . date( 'M Y', strtotime( $params['prevAccEDate'] ) ) : '' ) . '</td>
					<td style = "width:1%;border-bottom:1px solid #000;"></td>
					<td style = "width:11%;border-bottom:1px solid #000;">' . ( count( $params ) > 0? date( 'M Y', strtotime( $params['curAccSDate'] ) ) . ' - ' . date( 'M Y', strtotime( $params['curAccEDate'] ) ) : '' ) . '</td>
					<td style = "width:1%;border-bottom:1px solid #000;"></td>
					<td style = "width:11%;border-bottom:1px solid #000;">' . ( count( $params ) > 0? date( 'M Y', strtotime( $params['year'] . '-' . $params['month'] . '-1' ) ) : '' ) . '</td>
					<td style = "width:1%;border-bottom:1px solid #000;"></td>
					<td style = "width:11%;border-bottom:1px solid #000;">% INC/DEC</td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			';
		foreach( (array)$params['array_Rows'] as $rs ){
				$previousYrGLAmount = ( $rs['previousYrGLAmount'] != '' ? ( round($rs['previousYrGLAmount'],2) >= 0 ? number_format( abs( $rs['previousYrGLAmount'] ), 2 ) : '('.number_format( abs( $rs['previousYrGLAmount'] ), 2 ).')' ) : '' );
				$previousYrAccumulatedGLAmount = ( $rs['previousYrAccumulatedGLAmount'] != '' ? ( round($rs['previousYrAccumulatedGLAmount'],2) >= 0 ? number_format( abs( $rs['previousYrAccumulatedGLAmount'] ), 2 ) : '('.number_format( abs( $rs['previousYrAccumulatedGLAmount'] ), 2 ).')') : '' );
				$currentYrAccumulatedGLAmount = ( $rs['currentYrAccumulatedGLAmount'] != '' ? ( round($rs['currentYrAccumulatedGLAmount'],2) >= 0 ? number_format( abs( $rs['currentYrAccumulatedGLAmount'] ), 2 ) : '('.number_format( abs( $rs['currentYrAccumulatedGLAmount'] ), 2 ).')') : '' );
				$currentMonthGLAmount = ( $rs['currentMonthGLAmount'] != '' ? ( round($rs['currentMonthGLAmount'],2) >= 0 ? number_format( abs( $rs['currentMonthGLAmount'] ), 2 ) : '('.number_format( abs( $rs['currentMonthGLAmount'] ), 2 ).')') : '' );
				$incDec = ( $rs['incDec'] != '' ? ( round($rs['incDec'],2) >= 0 ? number_format( abs( $rs['incDec'] ), 2 ) : '('.number_format( abs( $rs['incDec'] ), 2 ).')' ) : '' );
			if( $rs['sorter'] == 1.1
					|| $rs['sorter'] == 1.4
						|| $rs['sorter'] == 3.1
							|| $rs['sorter'] == 3.2
								|| $rs['sorter'] == 4.1
									|| $rs['sorter'] == 5.0
										|| $rs['sorter'] == 6.0 ){
				$html	.= '
					<tr style="font-weight: bold; ">
						<td colspan="2">' . $rs['aname_c30'] . '</td>
						<td></td>
						<td style="text-align: right;  border-bottom: 1px solid #000;">' . $previousYrGLAmount . '</td>
						<td colspan="2" style="text-align: right;  border-bottom: 1px solid #000;">' . $previousYrAccumulatedGLAmount . '</td>
						<td colspan="2" style="text-align: right;  border-bottom: 1px solid #000;">' . $currentYrAccumulatedGLAmount . '</td>
						<td colspan="2" style="text-align: right;  border-bottom: 1px solid #000;">' . $currentMonthGLAmount . '</td>
						<td colspan="2" style="text-align: right;  border-bottom: 1px solid #000;">' . $incDec . '</td>
					</tr>							
				';
			}
			else{
				$style	= '';
				$name	= '';
				if( $rs['sorter'] == 0 || $rs['sorter'] == 1.1 || $rs['sorter'] == 1.5 || $rs['sorter'] == 3.3 || $rs['sorter'] == 1.2 ){
					$style							= 'font-weight: bold;';
					$previousYrGLAmount				= '';
					$previousYrAccumulatedGLAmount	= '';
					$currentYrAccumulatedGLAmount	= '';
					$currentMonthGLAmount			= '';
					$incDec							= '';
					$name	= '<td colspan="2">' . $rs['aname_c30'] . '</td>';
				}
				else{
					$name	= '<td></td>
								<td>' . $rs['aname_c30'] . '</td>';
				}
				$html	.= '
					<tr style="' . $style . '">
						' . $name . '
						<td></td>
						<td style="text-align: right;">' . $previousYrGLAmount . '</td>
						<td colspan="2" style="text-align: right;">' . $previousYrAccumulatedGLAmount . '</td>
						<td colspan="2" style="text-align: right;">' . $currentYrAccumulatedGLAmount . '</td>
						<td colspan="2" style="text-align: right;">' . $currentMonthGLAmount . '</td>
						<td colspan="2" style="text-align: right;">' . $incDec . '</td>
					</tr>							
				';
			}
		}
		$html	.= '</table>';
		return $html;
	}
}

if( !function_exists( '_createBalanceSheetView' ) ){
	function _createBalanceSheetView( $params ){
		$html	= '
			<table style="width:100%; text-align:center;">
				<tr>
					<td><strong>' . $params['h1'] . '</strong></td>
				</tr>
				<tr>
					<td>' . $params['h2'] . '</td>
				</tr>
				<tr>
					<td> &nbsp; </td>
				</tr>
				<tr>
					<td> &nbsp; </td>
				</tr>
				<tr>
					<td><strong>STATEMENT OF FINANCIAL POSITION</strong></td>
				</tr>
				<tr>
					<td><strong>' . $params['date'] . '</strong></td>
				</tr>
			</table>
			<br/>
			<br/>
			<table border=0 style="width:100%;">
				<tr style="font-weight:bold;text-align:center;border-bottom:1px solid #000;">
					<th style = "width:3%;border-bottom:1px solid #000;"></th>
					<th style = "width:50%;border-bottom:1px solid #000;"></th>
					<th style = "width:14%;border-bottom:1px solid #000;">'. date( 'M Y', strtotime( $params['prevEDate'] ) ) . '</th>
					<th style = "width:1%;border-bottom:1px solid #000;"></th>
					<th style = "width:14%;border-bottom:1px solid #000;">' . date( 'M Y', strtotime( $params['prevAccEDate'] ) ) . '</th>
					<th style = "width:1%;border-bottom:1px solid #000;"></th>
					<th style = "width:14%;border-bottom:1px solid #000;">' . date( 'M Y', strtotime( $params['curAccEDate'] ) ) . '</th>
					<th style = "width:0%;border-bottom:1px solid #000;"></th>
				</tr>';
		foreach( $params['array_Rows'] as $data ){
			$currMonthAmount = ( $data['currentMonthAmount'] != '' ? ( round($data['currentMonthAmount'],2) >= 0 ? number_format( abs( $data['currentMonthAmount'] ), 2 ) : '('.number_format( abs( $data['currentMonthAmount'] ), 2 ).')' ) : '' );
			$curLastYearAmount = ( $data['prevYearCurrentMonth'] != '' ? ( round($data['prevYearCurrentMonth'],2) >= 0 ? number_format( abs( $data['prevYearCurrentMonth'] ), 2 ) : '('.number_format( abs( $data['prevYearCurrentMonth'] ), 2 ).')' ) : '' );
			$currStartMonth = ( $data['currentYearStartMonth'] != '' ? ( round($data['currentYearStartMonth'],2) >= 0 ? number_format( abs( $data['currentYearStartMonth'] ), 2 ) : '('.number_format( abs( $data['currentYearStartMonth'] ), 2 ).')' ) : '' );
			if( $data['sorter'] == 13.50
				|| $data['sorter'] == 23.50
					|| $data['sorter'] == 31.50 ){
				$html	.= '<tr style="font-weight: bold;">
								<td colspan="2">'. $data['aname_c30'] .'</td>
								<td colspan="2" style="text-align:right;  border-top:1px solid #000; border-bottom:thick double; padding-top:10px;">'. $currStartMonth .'</td>
								<td colspan="2" style="text-align:right;  border-top:1px solid #000; border-bottom:thick double; padding-top:10px;">'. $curLastYearAmount .'</td>
								<td colspan="2" style="text-align:right;  border-top:1px solid #000; border-bottom:thick double; padding-top:10px;">'. $currMonthAmount .'</td>
							</tr>';
			}
			elseif( $data['sorter'] == 11.40
				|| $data['sorter'] == 12.40
					|| $data['sorter'] == 13.40
						|| $data['sorter'] == 21.40
							|| $data['sorter'] == 22.40
								|| $data['sorter'] == 23.40
									|| $data['sorter'] == 31.40 ){
				$html	.= '<tr style="font-weight: bold;">
					<td></td>
					<td>'. $data['aname_c30'] .'</td>
					<td colspan="2" style="text-align:right; border-top:1px solid #000;">'. $currStartMonth .'</td>
					<td colspan="2" style="text-align:right; border-top:1px solid #000;">'. $curLastYearAmount .'</td>
					<td colspan="2" style="text-align:right; border-top:1px solid #000;">'. $currMonthAmount .'</td>
				</tr>';
			}
			elseif( $data['sorter'] == 0.05
					|| $data['sorter'] == 11.50
						|| $data['sorter'] == 12.50
							|| $data['sorter'] == 20.00
								|| $data['sorter'] == 20.40
									|| $data['sorter'] == 21.50
										|| $data['sorter'] == 22.50
											|| $data['sorter'] == 23.40
												|| $data['sorter'] == 30.40 ){
				$html	.= '<tr style="font-weight: bold;">
					<td colspan="2">'. $data['aname_c30'] .'</td>
					<td colspan="2"></td>
					<td colspan="2"></td>
					<td colspan="2"></td>
				</tr>';
			}
			elseif( $data['sorter'] == 0.00 ){
				$html	.= '<tr style="font-weight:bold; border-top:2px solid #000;">
					<td colspan="2">'. $data['aname_c30'] .'</td>
					<td colspan="2"></td>
					<td colspan="2"></td>
					<td colspan="2"></td>
				</tr>';
			}
			else{
				$html	.= '<tr>
					<td></td>
					<td>'. $data['aname_c30'] .'</td>
					<td colspan="2" style="text-align:right;">'. $currStartMonth .'</td>
					<td colspan="2" style="text-align:right;">'. $curLastYearAmount .'</td>
					<td colspan="2" style="text-align:right;">'. $currMonthAmount .'</td>
				</tr>';
			}
		}
		$html .= '</table>';
		return $html;
	}
}

if( !function_exists( '_createCashFlowView' ) ){
	function _createCashFlowView( $params ){
        $params['curEDate']     = date( 'Y-m-d', strtotime( '+1 year', strtotime( $params['prevEDate'] ) ) );
		$html = '
			<table style="width:100%; text-align:center;">
				<tr>
					<td><strong>' . $params['h1'] . '</strong></td>
				</tr>
				<tr>
					<td>' . $params['h2'] . '</td>
				</tr>
				<tr>
					<td> &nbsp; </td>
				</tr>
				<tr>
					<td> &nbsp; </td>
				</tr>
				<tr>
					<td><strong>STATEMENT OF CASH FLOW</strong></td>
				</tr>
				<tr>
					<td><strong>' . $params['date'] . '</strong></td>
				</tr>
			</table>
			<br/>
			<table border=0 style="width:100%;">
				<tr style="width:100%; font-weight:bold; text-align: center; border-bottom:2px solid #000;">
					<td colspan="2" style="width:70%; border-bottom:2px solid #000;"></td>
					<td style="text-align: right; border-bottom:2px solid #000;width:15%;">' . date( 'Y', strtotime( $params['prevEDate'] ) ) . '</td>
					<td style="text-align: right; border-bottom:2px solid #000;width:15%;">' . date( 'Y', strtotime( $params['curEDate'] ) ) . '</td>
				</tr>	
				<tr>
					<td style="width:3%;">&nbsp;</td>
					<td style="width:67%;"></td>
					<td style="width:15%;"></td>
					<td style="width:15%;"></td>
				</tr>
		';
		$prevBeginning	= 0;
		$totalPrev		= 0;
		$totalCurrent	= 0;
		foreach( $params['arrayRows'] as $data ){
			$prevBeginning	= $data['totalBeginning'];
			$currentAmount	= ( $data['currentYearRecord'] >= 0? number_format( $data['currentYearRecord'], 2 ) : '(' . number_format( ( $data['currentYearRecord'] * -1 ), 2 ) . ')' );
			$prevAmount		= ( $data['prevYearRecord'] >= 0? number_format( $data['prevYearRecord'], 2 ) : '(' . number_format( ( $data['prevYearRecord'] * -1 ), 2 ) . ')' );
			if( $data['sorter'] == 1.2
				|| $data['sorter'] == 2.2
					|| $data['sorter'] == 3.2
						|| $data['sorter'] == 3.3 ){
				$html	.= '
				<tr style="font-weight: bold;">
					<td colspan="2">' . $data['aname_c30'] . '</td>
					<td style="text-align: right; border-top: 1px solid #000;">' . $prevAmount . '</td>
					<td style="text-align: right; border-top: 1px solid #000;">' . $currentAmount . '</td>
				</tr>
				';
			}
			elseif( $data['sorter'] == 0.0
				|| $data['sorter'] == 1.3
					|| $data['sorter'] == 2.3 ){
				$html	.= '
				<tr style="font-weight: bold;">
					<td colspan="2">' . $data['aname_c30'] . '</td>
					<td></td>
					<td></td>
				</tr>
				';
			}
			else{
				if( $data['prevYearRecord'] > 0 || $data['currentYearRecord'] > 0 ){
					$totalPrev		+= $data['prevYearRecord'];
					$totalCurrent	+= $data['currentYearRecord'];
					$html	.= '
				<tr>
					<td></td>
					<td>' . $data['aname_c30'] . '</td>
					<td style="text-align: right;">' . $prevAmount . '</td>
					<td style="text-align: right;">' . $currentAmount . '</td>
				</tr>';
				}
			}
		}
		$prevBeginning	= ( $prevBeginning >= 0? number_format( $prevBeginning, 2 ) : '(' . number_format( ( $prevBeginning * -1 ), 2 ) . ')' );
		$prevEnding		= ( $prevBeginning + $totalPrev );
		$prevEnding		= ( $prevEnding >= 0? number_format( $prevEnding, 2 ) : '(' . number_format( ( $prevEnding * -1 ), 2 ) . ')' );
		$currentEnding	= ( $prevBeginning + $totalPrev + $totalCurrent );
		$currentEnding	= ( $currentEnding >= 0? number_format( $currentEnding, 2 ) : '(' . number_format( ( $currentEnding * -1 ), 2 ) . ')' );
		$html	.= '
				<tr style="font-weight: bold;">
					<td colspan="2">BEGINNING CASH AND CASH EQUIVALENTS</td>
					<td style="text-align: right; border-top: 1px solid #000; border-bottom: 1px solid #000;">' . $prevBeginning . '</td>
					<td style="text-align: right; border-top: 1px solid #000; border-bottom: 1px solid #000;">' . $prevEnding . '</td>
				</tr>
				<tr style="font-weight: bold;">
					<td colspan="2">ENDING - CASH AND CASH EQUIVALENTS</td>
					<td style="text-align: right; border-bottom: 1px solid #000;">' . $prevEnding . '</td>
					<td style="text-align: right; border-bottom: 1px solid #000;">' . $currentEnding . '</td>
				</tr>
			</table>
		';

		return $html;
	}
}

/* Added by: Hazel
* Date Added: April 13, 2020
* Functions used for the Encryption.
*		initializeSalt() 	- initializes the value (salt) used for generating encryption key.
*		generateSKED()		- generates a unique key that determines the output encrypted/decrypted data.
*		decryptAffiliate()	- returns the decrypted values for affiliate
*		decryptItem()		- returns the decrypted values for item
*/

if( !function_exists( 'getNumEq' ) ){
	function getNumEq(){
		return array(
			'00' ,'01' ,'02' ,'03' ,'04' ,'05'
			,'06' ,'07' ,'08' ,'09' ,'10' ,'11'
			,'12' ,'13' ,'14' ,'15' ,'16' ,'17'
			,'18' ,'19' ,'20' ,'21' ,'22' ,'23'
			,'24' ,'25' ,'26'
		);
	}
}

if( !function_exists( 'getIntEq' ) ){
	function getIntEq(){
		return array(
			' ' ,'1' ,'2' ,'3' ,'4' ,'5'
			,'6' ,'7','8' ,'9' 
		);
	}
}

if( !function_exists( 'getLetEq' ) ){
	function getLetEq(){
		return array(
			' ' ,'a' ,'b' ,'c' ,'d' ,'e'
			,'f' ,'g','h' ,'i' ,'j' ,'k'
			,'l' ,'m' ,'n' ,'o' ,'p' ,'q'
			,'r' ,'s' ,'t' ,'u' ,'v' ,'w'
			,'x' ,'y' ,'z'
		);
	}
}

if( !function_exists( '_getKeyPin' ) ){
	function _getKeyPin( $index ){
		$keyPin = array(
			'000' => "s#)2V�" ,'001' => "(fVXIm" ,'002' => "GQ%gA(" ,'003' => "x*Z%Sc" ,'004' => "Ipo+.8"
			,'005' => "%10Zt/" ,'006' => "Nnd.ga" ,'007' => "MOx[YQ" ,'008' => "x?Cz>!" ,'009' => "�}TcgU"
			,'010' => "dvF7,P" ,'011' => "4dcxJl" ,'012' => ";#I,6v" ,'013' => ")t4Fh:" ,'014' => "9kahmO"
			,'015' => "SQDUoC" ,'016' => "v;7p)+" ,'017' => "&^]C!f" ,'018' => "#Cd~r." ,'019' => "2X[ufa"
			,'020' => "mED8aU" ,'021' => "X2qf7M" ,'022' => "�6k*C@" ,'023' => "XE;Ydl" ,'024' => "[SrsIy"
			,'025' => "%S�O9>" ,'026' => "2cVM6o" ,'027' => "f[F6vQ" ,'028' => ")Sy~Au" ,'029' => "%2+wIN"
			,'030' => "z.8<4P" ,'031' => "uh~&O<" ,'032' => "751�De" ,'033' => "@0(Arv" ,'034' => "4.^)}{"
			,'035' => "bCl�uy" ,'036' => "~5#0VC" ,'037' => "ODW{U6" ,'038' => "~;9C(e" ,'039' => "l0J+v1"
			,'040' => "Me�p#B" ,'041' => "2*1g%:" ,'042' => "\$h]lI?" ,'043' => ")v2QE1" ,'044' => "3WPy8G"
			,'045' => "<on5U8" ,'046' => "m<s~hz" ,'047' => "A@keh�" ,'048' => "7Z#v^r" ,'049' => "�dk{A1"
			,'050' => "%6u\$Sp" ,'051' => "gH0CsD" ,'052' => "+0CHno" ,'053' => "A3W]zv" ,'054' => "&bK\$we"
			,'055' => "ZBG{78" ,'056' => "0hi�\$YZ" ,'057' => "tR[sH/" ,'058' => "@0E~h2" ,'059' => "D5s(1g"
			,'060' => "/FuCmo" ,'061' => "tL:5lO" ,'062' => "a,V2P$" ,'063' => "Sv]m[�" ,'064' => ".B:]uM"
			,'065' => "d+�qoO" ,'066' => "1zSnlb" ,'067' => "Il!Fh%" ,'068' => "SO^BT&" ,'069' => "}T.X(g"
			,'070' => "ho\$MwU" ,'071' => "T3�ge*" ,'072' => "xBFP{M" ,'073' => ":w!>5J" ,'074' => "sRTM�W"
			,'075' => "LZs2O*" ,'076' => "C%2U+k" ,'077' => "(fJB{i" ,'078' => "cY)Gq+" ,'079' => "8]2^H0"
			,'080' => "b<&5V," ,'081' => ":]b/(!" ,'082' => "W^N%()" ,'083' => "VLlr^C" ,'084' => "EoKJ\$U"
			,'085' => "7e4!da" ,'086' => ":phRw7" ,'087' => "dCYBP2" ,'088' => "P*guao" ,'089' => "nl6^Uh"
			,'090' => "Ns(kmL" ,'091' => "D#GB^L" ,'092' => "ij(Z@P" ,'093' => "H[)MuZ" ,'094' => "xDqR2I"
			,'095' => "n}5Qdj" ,'096' => "}3v09B" ,'097' => "w3%}PM" ,'098' => "tg&j3i" ,'099' => "cukS\${"
			,'100' => "Ay^0&/" ,'101' => ".TLSg<" ,'102' => "<lxKhf" ,'103' => "qAT>pc" ,'104' => "naB#0D"
			,'105' => "KG(Veh" ,'106' => "Sl*K2o" ,'107' => "/cvA0u" ,'108' => "L#ED&P" ,'109' => "m%8*v>"
			,'110' => "Pp;d[Q" ,'111' => "7.35nQ" ,'112' => "zCo;FG" ,'113' => "E6wu;T" ,'114' => "@?CKXU"
			,'115' => "FEl~zr" ,'116' => "c(T59>" ,'117' => "drVchx" ,'118' => "+vt%QL" ,'119' => "WEzmt6"
			,'120' => "A2OoMh" ,'121' => "a+DwE#" ,'122' => "u(&!wZ" ,'123' => "�2.+\$x" ,'124' => "[rf9Q?"
			,'125' => "@B%?Nc" ,'126' => "SZAei;" ,'127' => "�.ln3{" ,'128' => ",RO)qf" ,'129' => ":#l%2r"
			,'130' => "4YH?#k" ,'131' => "*9/<km" ,'132' => "H>g�~}" ,'133' => "S+3ZT," ,'134' => "IP/s}k"
			,'135' => "U!{y7I" ,'136' => "ta/}g@" ,'137' => "3d]DBe" ,'138' => "F)pS96" ,'139' => "y;)<M1"
			,'140' => "({>g12" ,'141' => "r15<{l" ,'142' => "6R0B45" ,'143' => "T:3/)J" ,'144' => "Y}[LZg"
			,'145' => "[vDa/j" ,'146' => "CN8RxD" ,'147' => "myT,72" ,'148' => "}�kPjR" ,'149' => "a[(2Z^"
			,'150' => "m>TP[s" ,'151' => "B^A*%R" ,'152' => "hP*@b&" ,'153' => "s}gw�a" ,'154' => "�H�qUV"
			,'155' => "c0A}PI" ,'156' => "Am(ZMg" ,'157' => "JYp!L1" ,'158' => "Q&rVId" ,'159' => "iT^�{f"
			,'160' => "DQf7*G" ,'161' => "gJ0ux~" ,'162' => "*3POSg" ,'163' => "b+fW9Y" ,'164' => "PtR4xd"
			,'165' => "Zx!:]?" ,'166' => "1R%7T8" ,'167' => "tn%iET" ,'168' => "WX*iNJ" ,'169' => "FS6K^k"
			,'170' => "j2HW?@" ,'171' => "rcyD9$" ,'172' => "5xDNk]" ,'173' => "tn8~BO" ,'174' => "D9K[&C"
			,'175' => "WU&asb" ,'176' => "6Rbzgp" ,'177' => "E^yaNS" ,'178' => ",xG7~k" ,'179' => "Ocy2x^"
			,'180' => "Rk8ncz" ,'181' => "lXbnZ}" ,'182' => "+thmMK" ,'183' => "x3]FH~" ,'184' => "ISu>�R"
			,'185' => "H!4RMq" ,'186' => "e4C:TG" ,'187' => "\$zx)gn" ,'188' => "*&Y@�5" ,'189' => "g2�s[�"
			,'190' => "PJ.3n>" ,'191' => "2k]usm" ,'192' => "WlhMXr" ,'193' => "P&amS{" ,'194' => "A:ia7S"
			,'195' => "vbc1WL" ,'196' => "YzO}ra" ,'197' => "UlC8ZD" ,'198' => "RJH3~j" ,'199' => "No;W%E"
			,'200' => "LDiG&$" ,'201' => "RA0v\$r" ,'202' => "t!z27l" ,'203' => "SFl$#~" ,'204' => "�A@H1n"
			,'205' => "&{5#fY" ,'206' => "ej1~?m" ,'207' => "AHwzc~" ,'208' => "(Tc/t$" ,'209' => "613HC>"
			,'210' => "!>UDc7" ,'211' => "�;7?S!" ,'212' => "YE0ON2" ,'213' => "D6!JKZ" ,'214' => "hL(*[+"
			,'215' => "HFQ8hS" ,'216' => ".VLgbw" ,'217' => "?~s/!v" ,'218' => "FU@Myt" ,'219' => "hE.isU"
			,'220' => "1Q�F#R" ,'221' => "ZDtrx%" ,'222' => "*iR/,(" ,'223' => "Tr,q~5" ,'224' => ".t2C?�"
			,'225' => "uSrcCE" ,'226' => "SjOzcH" ,'227' => "v?x:,+" ,'228' => "8Rg)pz" ,'229' => "md:)2%"
			,'230' => "5>ap8y" ,'231' => "oI�R3]" ,'232' => "I5(b1C" ,'233' => ";j12HX" ,'234' => "e)8!S,"
			,'235' => "r$87I1" ,'236' => "a)~wj(" ,'237' => "xAo+8*" ,'238' => "r,&iHJ" ,'239' => "D#@m+N"
			,'240' => "Va}YrE" ,'241' => "Qvm(~z" ,'242' => "B4X/Yp" ,'243' => "0Xm<s]" ,'244' => "~8H>UY"
			,'245' => "V/Etb(" ,'246' => "]%DBs3" ,'247' => ">(l!Pr" ,'248' => ")s7npt" ,'249' => "O<vLNB"
			,'250' => "6,Wu<i" ,'251' => "5IY{U;" ,'252' => "g}!rhC" ,'253' => "Avce:r" ,'254' => "B<7J2P"
			,'255' => "y/+Rek" ,'256' => "mZu6p~" ,'257' => "1Xco�M" ,'258' => "h&}�m]" ,'259' => "i31K8#"
			,'260' => "C{[Z\$v" ,'261' => "{4lXR<" ,'262' => "[lKx?Z" ,'263' => "w�gAkE" ,'264' => "4EFh@x"
			,'265' => "EM0%Tn" ,'266' => "f*/x!P" ,'267' => "wy?/NZ" ,'268' => "'Vy:Rs" ,'269' => "6~)Q8^"
			,'270' => "JA%1Vs" ,'271' => "bW;,z3" ,'272' => "h*9,WM" ,'273' => "usgc&Z" ,'274' => "Q)OzMP"
			,'275' => "LflM6:" ,'276' => "($:n*A" ,'277' => ",tOW*~" ,'278' => "+i^,:u" ,'279' => "ZJ/EF,"
			,'280' => "A8)u6b" ,'281' => "7;�6VY" ,'282' => "x2hJ<z" ,'283' => "tkCQ7f" ,'284' => "eR@aJL"
			,'285' => "%qQLey" ,'286' => "[q2eR7" ,'287' => "Ow[<M4" ,'288' => "/wCPM&" ,'289' => "wqM:Vg"
			,'290' => "+j7x0d" ,'291' => "M{/5B�" ,'292' => "(J<�Um" ,'293' => "Ao4!We" ,'294' => "V*gw7B"
			,'295' => "J>TviF" ,'296' => "#ApC:a" ,'297' => "[^96bi" ,'298' => "Hp?a&�" ,'299' => "ux{MpV"
			,'300' => "8~[+Nc" ,'301' => "K8UJGQ" ,'302' => "eZ5@?:" ,'303' => "05jbr$" ,'304' => "v^e}wh"
			,'305' => "ntTkj$" ,'306' => "{!ni4." ,'307' => "LxG2<~" ,'308' => "R7Star" ,'309' => "}x?gZE"
			,'310' => "{Ak}IN" ,'311' => "iX7c^?" ,'312' => "W5y�k4" ,'313' => "zC75}<" ,'314' => "02Ye\$W"
			,'315' => "vJE�[p" ,'316' => "AT.>u0" ,'317' => "O5cz3{" ,'318' => "?8/]e#" ,'319' => "reT.P}"
			,'320' => "4sb0AF" ,'321' => "tpl2wQ" ,'322' => "%fuxp&" ,'323' => "9F2^5k" ,'324' => "q)cIpK"
			,'325' => "?h@x!g" ,'326' => "#K[Gid" ,'327' => "TQrvCK" ,'328' => "NCsU&5" ,'329' => "loD]W4"
			,'330' => "Kn2[Tl" ,'331' => "[2^as%" ,'332' => "AO)9wS" ,'333' => "6wDR8*" ,'334' => "2#hUdH"
			,'335' => "}&\$f7(" ,'336' => "!p{HG6" ,'337' => "4Cu,[x" ,'338' => "@HTbeV" ,'339' => "dO*{z$"
			,'340' => "(Y>q}w" ,'341' => "Ut^,@;" ,'342' => "q(F%c�" ,'343' => "<E8~^4" ,'344' => "J7fS*r"
			,'345' => "nv~XDY" ,'346' => "Rv!1ZV" ,'347' => "cM#8kI" ,'348' => "0L1,jf" ,'349' => "F�OSDt"
			,'350' => "}ao*F%" ,'351' => "4qdDx�" ,'352' => "{X4Z\$d" ,'353' => "L51j3Y" ,'354' => "eJ{zOi"
			,'355' => "[9+EU8" ,'356' => "N}+�/b" ,'357' => "�3I~Y7" ,'358' => "gCp&{n" ,'359' => "1C2}�S"
			,'360' => "y*jqK{" ,'361' => "Mf0+k?" ,'362' => ",yl%M4" ,'363' => "B.5o%*" ,'364' => "CE,t�9"
			,'365' => "^O!C<m" ,'366' => "G&>wi?" ,'367' => "8<#W�U" ,'368' => "a@}Yfm" ,'369' => "gidV1t"
			,'370' => "u971^X" ,'371' => "o.#y*/" ,'372' => "RYX+�S" ,'373' => "sf?bYi" ,'374' => "XY1~<S"
			,'375' => "!bC&P~" ,'376' => "v<oL/n" ,'377' => "DG\$UN^" ,'378' => "%M[x*;" ,'379' => "K%u738"
			,'380' => "bSYWmO" ,'381' => "'e%^9A" ,'382' => "K48@BM" ,'383' => "k?ZT)1" ,'384' => ";Y)2/}"
			,'385' => "tE]I!F" ,'386' => "VN@m#K" ,'387' => "N+[!\$u" ,'388' => ".XHgf9" ,'389' => "v{tX<]"
			,'390' => "IahqHd" ,'391' => "J$+)Yr" ,'392' => "Ss5>YP" ,'393' => "8SPm#}" ,'394' => "<?8IK5"
			,'395' => ".5AfWN" ,'396' => "Qu)fNB" ,'397' => "2W9$,]" ,'398' => "5/fAXO" ,'399' => ")sx#0."
			,'400' => ")*R54�" ,'401' => "bSsX]*" ,'402' => "1h):f�" ,'403' => "cs7;ya" ,'404' => "jS%J:d"
			,'405' => "mZxtHs" ,'406' => "#nVr~?" ,'407' => "JwI)Ze" ,'408' => "Aro�[I" ,'409' => "bQzDJH"
			,'410' => "0O%rXb" ,'411' => "<+vJ2k" ,'412' => "B2kUyK" ,'413' => ".(g10@" ,'414' => "3v0yDA"
			,'415' => "ECew.Y" ,'416' => "nx2Ot$" ,'417' => "&U3mbO" ,'418' => "p&#ZBn" ,'419' => "&Z^m0y"
			,'420' => ":@#wUY" ,'421' => "\$qgvbP" ,'422' => "uANU+$" ,'423' => "RJFa%c" ,'424' => "S}!^VB"
			,'425' => "@fjM!5" ,'426' => "WG2!+O" ,'427' => "23%g;R" ,'428' => "dg1#;]" ,'429' => "h#zqkb"
			,'430' => ";+4dFH" ,'431' => ".�E4T&" ,'432' => ".U}ufN" ,'433' => "&XIe2k" ,'434' => "3K8FA}"
			,'435' => "&0xf5q" ,'436' => "HM2Dfh" ,'437' => "R[yTmu" ,'438' => "*+W\${[" ,'439' => "WvZS@&"
			,'440' => "zlp~I!" ,'441' => ":o3Dq," ,'442' => "'Ku.RT" ,'443' => "vQ%Y?g" ,'444' => "<#$*Is"
			,'445' => "AO:3a$" ,'446' => "UPp(!D" ,'447' => "xFuVQc" ,'448' => ">7iBOW" ,'449' => "K<4IE+"
			,'450' => "p6rVG]" ,'451' => "K3tyTc" ,'452' => "!GJ4u7" ,'453' => "*>S~U4" ,'454' => "56dfqE"
			,'455' => "BbfC^Q" ,'456' => ".0�RnA" ,'457' => "8cHx(3" ,'458' => "UW#jOy" ,'459' => "f^F1wU"
			,'460' => "?LRw#9" ,'461' => "X0!kTd" ,'462' => "6)5?^Z" ,'463' => "AQ?f3," ,'464' => "'E%I8U"
			,'465' => "c;:18#" ,'466' => "#>kn@9" ,'467' => "GBP^]H" ,'468' => "+v0L#2" ,'469' => "}!dhV^"
			,'470' => "JrNVUE" ,'471' => "$)7ALv" ,'472' => "\$Ya3F6" ,'473' => "YRPf*)" ,'474' => "x1Xe0c"
			,'475' => "?:<]b3" ,'476' => "jz)u@/" ,'477' => "�ZT0Mp" ,'478' => "~</e�B" ,'479' => "x)c;]v"
			,'480' => "@&Pg{F" ,'481' => "gl)BEC" ,'482' => "XnR�*J" ,'483' => "(g*JOF" ,'484' => "pcfeSt"
			,'485' => ";DNg$." ,'486' => "X,5#JD" ,'487' => "BJ]NiZ" ,'488' => "^k:aIB" ,'489' => "sV3;l&"
			,'490' => "p0KON4" ,'491' => "TNE5PQ" ,'492' => "*;Kd<y" ,'493' => "PFde(s" ,'494' => "7PEQh2"
			,'495' => "e0JPw5" ,'496' => "eFaVZ}" ,'497' => "RN}!K(" ,'498' => "$/BKPm" ,'499' => "[G<sze"
			,'500' => "{c)#!/" ,'501' => "y7ae0;" ,'502' => "oWtAU?" ,'503' => "*[pC!R" ,'504' => "%h]f7:"
			,'505' => "vLmA0{" ,'506' => "Z1(Tzj" ,'507' => "S^;H*B" ,'508' => "^aBcMd" ,'509' => "'v�ZB�"
			,'510' => "Ih&<1d" ,'511' => "vRX86c" ,'512' => "hJ7:{u" ,'513' => "QeIBa�" ,'514' => "u<YEa4"
			,'515' => "{:]�fm" ,'516' => "GuX$/;" ,'517' => "X/Wd5h" ,'518' => "o5]k\$J",'519' => "/j@aoK"
			,'520' => "~^/�Wa" ,'521' => "h+@Tc&" ,'522' => "9JWD1b" ,'523' => "1dg2Oq" ,'524' => ")i!XpL"
			,'525' => "l+aeBc" ,'526' => "A/dZm%" ,'527' => "<mrPCF" ,'528' => "V5^wi{" ,'529' => "w�9)FE"
			,'530' => "�d]HVA" ,'531' => "ilLo+T" ,'532' => "bR�eU<" ,'533' => "4bTXxF" ,'534' => "YUbCT}"
			,'535' => "koX)c&" ,'536' => "S3;PNk" ,'537' => "ZjxG]<" ,'538' => "Dl4{uv" ,'539' => "Y?E2I6"
			,'540' => "@oH0�+" ,'541' => "m/I>do" ,'542' => "odp7QI" ,'543' => "iRHrAp" ,'544' => "YGFt,?"
			,'545' => "(uo8Z?" ,'546' => "(e1x+4" ,'547' => "hOE1um" ,'548' => "rRJ@Fh" ,'549' => "KqC.OY"
			,'550' => "g.NdPs" ,'551' => "R2:,D3" ,'552' => "[jkE7y" ,'553' => "Oa(A0u" ,'554' => "Yq*@#S"
			,'555' => "Yv4ODW"
			,'556' => "IiL@Al"
			,'557' => "eBy�dj"
			,'558' => "kt54]J"
			,'559' => "OKCX9u"
			,'560' => "Zgxa(<"
			,'561' => "1iYsb�"
			,'562' => "iEG;lu"
			,'563' => "/%vNDp"
			,'564' => "a�8.?<"
			,'565' => "+fLX1j"
			,'566' => "lm{�,("
			,'567' => "96IWb2"
			,'568' => "6}c]P@"
			,'569' => "QF3SsV"
			,'570' => "�:tiqI"
			,'571' => "5pj]RB"
			,'572' => "KzUpy/"
			,'573' => "HWz(tF"
			,'574' => "t1Vh%r"
			,'575' => "45�,{i"
			,'576' => ":Z2{eM"
			,'577' => "S,4dEj"
			,'578' => "WGP~*�"
			,'579' => "!9ow~s"
			,'580' => "i\$cMK#"
			,'581' => "3n<w]h"
			,'582' => "UE8^eo"
			,'583' => "2STHNK"
			,'584' => "6odwFQ"
			,'585' => "M�TGnB"
			,'586' => "p0HM?w"
			,'587' => "PDG9cF"
			,'588' => "Dkp:jw"
			,'589' => "SQK)D>"
			,'590' => "\$k�cVT"
			,'591' => "!I%RVH"
			,'592' => "\$ST9H1"
			,'593' => "X{a7Sr"
			,'594' => "nJ:W/I"
			,'595' => "lU%tK,"
			,'596' => "�5X7^?"
			,'597' => "q1�v7{"
			,'598' => "0G$6MK"
			,'599' => "K+#3^$"
			,'600' => "9hRzwq"
			,'601' => "~t,TAy"
			,'602' => "vXywSq"
			,'603' => ",<v3X>"
			,'604' => "}&*H%R"
			,'605' => "9yOT*]"
			,'606' => ".PDs>V"
			,'607' => ".T]<mi"
			,'608' => "]&~8hS"
			,'609' => "6McPKs"
			,'610' => "<U~7eE"
			,'611' => "xi7ny6"
			,'612' => "j%E#>5"
			,'613' => "#%,V2e"
			,'614' => "[:q�n0"
			,'615' => ",Jfi[?"
			,'616' => "i/a{9c"
			,'617' => "B]QNLk"
			,'618' => "aihWxn"
			,'619' => "r2!v<u"
			,'620' => "zPG+rR"
			,'621' => "Skn^T9"
			,'622' => "xE!(r,"
			,'623' => "\$^,9(V"
			,'624' => "yLsPGE"
			,'625' => "V2oSzT"
			,'626' => "swNV&K"
			,'627' => "�l%1�,"
			,'628' => "?L:])0"
			,'629' => "ra)1~X"
			,'630' => "OC�@kH"
			,'631' => "xBMW)3"
			,'632' => "@qo<�7"
			,'633' => ">?rSu$"
			,'634' => "qaPyA,"
			,'635' => "zT%5lw"
			,'636' => "0Ksg6�"
			,'637' => "g8OG[W"
			,'638' => "�]n/AM"
			,'639' => "?0u$}D"
			,'640' => "5TinYW"
			,'641' => "d:*cF5"
			,'642' => "0.{/JG"
			,'643' => "As@klR"
			,'644' => "(kTI4N"
			,'645' => "xFb>V&"
			,'646' => "!p%vGi"
			,'647' => "3V&azr"
			,'648' => "%�{zv/"
			,'649' => "HDBtE3"
			,'650' => ",[M�Ev"
			,'651' => "Mz:#cL"
			,'652' => "%ML;kO"
			,'653' => "Urld)D"
			,'654' => "pk�X~I"
			,'655' => "I~h]tO"
			,'656' => "ht.wWg"
			,'657' => "�!y#e8"
			,'658' => "081nzF"
			,'659' => ";VSo+T"
			,'660' => "yc#]V,"
			,'661' => "&E6iug"
			,'662' => "}GOBh5"
			,'663' => "y}cAmV"
			,'664' => "4H(+[c"
			,'665' => "g#haCo"
			,'666' => "&B68m7"
			,'667' => "ZcLrqK"
			,'668' => "(R<s:i"
			,'669' => "R[:tqj"
			,'670' => "2(�QK6"
			,'671' => "X7!gb,"
			,'672' => "B{^?~1"
			,'673' => "BFsfkZ"
			,'674' => "lHC3kD"
			,'675' => "8$[dB4"
			,'676' => "e<a/rw"
			,'677' => "Fv~D@6"
			,'678' => "79mN:�"
			,'679' => "/s+o*S"
			,'680' => "XfgdMB"
			,'681' => ".kcYFp"
			,'682' => "DlF+RS"
			,'683' => "~tko;("
			,'684' => "z3*pWC"
			,'685' => "YuKNb�"
			,'686' => "r]}FLE"
			,'687' => "lZPNv]"
			,'688' => "xV+a5f"
			,'689' => "0sVL>?"
			,'690' => "G9qYWL"
			,'691' => "Kg>1&T"
			,'692' => "nGpheB"
			,'693' => "C]qFf!"
			,'694' => "Wr1Z6�"
			,'695' => "@eVa{i"
			,'696' => "2HVEc$"
			,'697' => ",�]!Kq"
			,'698' => "hYXQUP"
			,'699' => "N^aIdE"
			,'700' => "25M7/y"
			,'701' => "^VTiq6"
			,'702' => "nS{?r�"
			,'703' => "$]~S4a"
			,'704' => "(h,OT&"
			,'705' => "a&>iN^"
			,'706' => "Gp9}/c"
			,'707' => "U8SaXH"
			,'708' => "%}0j1l"
			,'709' => "R�a6mC"
			,'710' => "t}J{9]"
			,'711' => "a/o@BV"
			,'712' => "*WysCM"
			,'713' => "4jaWuZ"
			,'714' => "^2WbqH"
			,'715' => "B49Z?b"
			,'716' => "(h<~3r"
			,'717' => "c@IZ4N"
			,'718' => "sLV,+Q"
			,'719' => "gQ�2�1"
			,'720' => "^%4W.1"
			,'721' => "SV~!Dc"
			,'722' => "osd+X�"
			,'723' => "Ti2dWD"
			,'724' => "j~UTWA"
			,'725' => "f)mR+J"
			,'726' => "$178<+"
			,'727' => "4G}6(l"
			,'728' => "hFU35e"
			,'729' => "x:w!QV"
			,'730' => "eQH8&F"
			,'731' => "K!opOJ"
			,'732' => "eOqw:k"
			,'733' => "bdoyLF"
			,'734' => "Z!O%s8"
			,'735' => "G[&,^("
			,'736' => "0P4X%I"
			,'737' => "4A\$Fnr"
			,'738' => "%ckFWT"
			,'739' => "4$~FwM"
			,'740' => "x.<g9R"
			,'741' => "(,L6Uj"
			,'742' => "VL6i18"
			,'743' => "oRi:Kr"
			,'744' => "hA/tix"
			,'745' => "@)DV0u"
			,'746' => "3*jWP8"
			,'747' => "<8^ivE"
			,'748' => "D6(owM"
			,'749' => "M#bgYB"
			,'750' => "pvi4jx"
			,'751' => "02za.N"
			,'752' => "QmC7s�"
			,'753' => "0y&\$C]"
			,'754' => "(#$1BC"
			,'755' => "R^~yB{"
			,'756' => "WrbOhu"
			,'757' => "F!:8TP"
			,'758' => "xJBPwe"
			,'759' => "jE;w3@"
			,'760' => "tFfATb"
			,'761' => "^u�M/4"
			,'762' => "n}jA9m"
			,'763' => "w�k:(<"
			,'764' => "bS+BWm"
			,'765' => "$%p4US"
			,'766' => "ph(VN%"
			,'767' => "zX]{}F"
			,'768' => "mz0l[y"
			,'769' => "NuF4�g"
			,'770' => "qh{;,?"
			,'771' => ".I&L/)"
			,'772' => "}&b$#Y"
			,'773' => "M>+fpN"
			,'774' => "n<MP�("
			,'775' => "P3&5ZH"
			,'776' => "�h2@1n"
			,'777' => "�T&@5n"
			,'778' => "@<kQPY"
			,'779' => "vYC]/)"
			,'780' => "[%�BQN"
			,'781' => "OFrW~1"
			,'782' => "HvqMns"
			,'783' => "kSUb0>"
			,'784' => "<}YyEl"
			,'785' => "52y�&["
			,'786' => "0az�<~"
			,'787' => "bC[n}N"
			,'788' => "lIUWwb"
			,'789' => "Y8I@Ws"
			,'790' => "2n:m1h"
			,'791' => "s)3m�,"
			,'792' => "U/RMJ,"
			,'793' => "*nrF{1"
			,'794' => "n)fsF5"
			,'795' => ">cG^X;"
			,'796' => "nZ\$R^f"
			,'797' => ">9&c[~"
			,'798' => "KgB!);"
			,'799' => "?z9KXM"
			,'800' => "su1;Vh"
			,'801' => "C~pOVm"
			,'802' => "Y.~g2X"
			,'803' => "o>^8\$�"
			,'804' => "z~?,Z+"
			,'805' => "P42~xH"
			,'806' => "Xsd1Ng"
			,'807' => "(z�Jr!"
			,'808' => "oFXIP."
			,'809' => "8v9my�"
			,'810' => "(+pGO4"
			,'811' => "<jRA8*"
			,'812' => "!Uj^rn"
			,'813' => ">GeR#."
			,'814' => "4a2liM"
			,'815' => "W?3dle"
			,'816' => "><!2[p"
			,'817' => "QwN{94"
			,'818' => "P+Z^X�"
			,'819' => "$^k<Mc"
			,'820' => ":<x�^v"
			,'821' => "/jz52D"
			,'822' => "S,?cHu"
			,'823' => ">LVjBW"
			,'824' => "bHv,rI"
			,'825' => "jcsz(l"
			,'826' => "T!�L�l"
			,'827' => "@B0ge$"
			,'828' => "aL0voH"
			,'829' => "Ra@B4X"
			,'830' => "pG!%<,"
			,'831' => "wkF�/t"
			,'832' => "e8RcUO"
			,'833' => "f93t>W"
			,'834' => "O+Tz/V"
			,'835' => "b(D}i�"
			,'836' => "r0&*ho"
			,'837' => "z\$Z}PU"
			,'838' => "dWt;A,"
			,'839' => "cs[�wu"
			,'840' => "!nCrc{"
			,'841' => "VNzo4&"
			,'842' => "1zd%:f"
			,'843' => "ln+KdF"
			,'844' => "jfLtKq"
			,'845' => "7z6X0�"
			,'846' => "Z*j(5F"
			,'847' => "Dd1Wt^"
			,'848' => "1Ht@>U"
			,'849' => ",t~h46"
			,'850' => "*3K!F~"
			,'851' => "OA8vg%"
			,'852' => "X^a9}."
			,'853' => "fB�?Jm"
			,'854' => "Pm7S6N"
			,'855' => "*(mrwR"
			,'856' => "/*0)fk"
			,'857' => "M!)7+~"
			,'858' => "U4YD(3"
			,'859' => "03b,)O"
			,'860' => "<>hxgb"
			,'861' => "gvS/o,"
			,'862' => "F�k�,}"
			,'863' => "6.NMz+"
			,'864' => "Mqr+AO"
			,'865' => "OtwL+8"
			,'866' => "ONaDMW"
			,'867' => "dk*mpY"
			,'868' => "T.ziM!"
			,'869' => ">0C*Sr"
			,'870' => "/,xK^#"
			,'871' => "V6h.s)"
			,'872' => "iOU)?z"
			,'873' => "xu7Md3"
			,'874' => "p,8qGH"
			,'875' => "ja8R1O"
			,'876' => "9N./!"
			,'877' => "/UuDd�"
			,'878' => "PRYLG5"
			,'879' => "pZG7u<"
			,'880' => "Tn5pa#"
			,'881' => "GKYXM5"
			,'882' => "$+}qh1"
			,'883' => "t/RBNT"
			,'884' => ">ivSRX"
			,'885' => "O~j:eK"
			,'886' => "XYE*Jo"
			,'887' => "(htk72"
			,'888' => "�lWiGv"
			,'889' => "1m^vh6"
			,'890' => "~:{zg("
			,'891' => "{evbF0"
			,'892' => "P%bKY0"
			,'893' => "*>vp;~"
			,'894' => "FyZ<tW"
			,'895' => "t<)fO}"
			,'896' => "u^5.2+"
			,'897' => "6Qs:d9"
			,'898' => "GmRN9Q"
			,'899' => "t3B�Jd"
			,'900' => "hQ0q]I"
			,'901' => "^eitx0"
			,'902' => "6B<&O%"
			,'903' => "rVL\$C"
			,'904' => "r�TAc<"
			,'905' => "#bMzHi"
			,'906' => "<;EG76"
			,'907' => "CIy&v."
			,'908' => "~rLqW."
			,'909' => "f&yC!�"
			,'910' => "QTtRVj"
			,'911' => "(9![MB"
			,'912' => ":K8RZB"
			,'913' => "U/AmV5"
			,'914' => ";{bIB}"
			,'915' => "�a]rZN"
			,'916' => "t(I+#u"
			,'917' => "T�YJtk"
			,'918' => "Jy6v3q"
			,'919' => "s?HVj~"
			,'920' => "vJ�{sP"
			,'921' => "ZM9;0g"
			,'922' => "@We,kK"
			,'923' => "cV�^.F"
			,'924' => "UC2}r^"
			,'925' => "V+<Npk"
			,'926' => "AIOUTh"
			,'927' => "OU:752"
			,'928' => "jZRkhA"
			,'929' => "YDGv/)"
			,'930' => "[6@NUg"
			,'931' => "naN:8^"
			,'932' => "kG96+7"
			,'933' => "}FCb@E"
			,'934' => "D+2\$RZ"
			,'935' => "8r9U~H"
			,'936' => "yeZ�6c"
			,'937' => "h}d{U2"
			,'938' => "#kx1z]"
			,'939' => "b<go&"
			,'940' => "84{c0H"
			,'941' => "CDyo!}"
			,'942' => "&;Au%}"
			,'943' => "\$fycDg"
			,'944' => "j;R?k#"
			,'945' => "72[~K$"
			,'946' => "4<SwPh"
			,'947' => "L&)p4y"
			,'948' => "10RJyP" ,'949' => "&jT<tm" ,'950' => "4$<{>e" ,'951' => "D8�Oxh" ,'952' => "�dKcQ@"
			,'953' => "TiUsGS" ,'954' => "M5U\$Jw" ,'955' => "i;UWrS" ,'956' => "?Hi[/K" ,'957' => "*FUR&~"
			,'958' => "lSE&aU" ,'959' => ">6WzAx" ,'960' => "b8M@O(" ,'961' => "8DUd(k" ,'962' => "Wkwc+!"
			,'963' => ";,3}4." ,'964' => "e>kBtV" ,'965' => "}&v>1)" ,'966' => "D&C1/K" ,'967' => "%QM&.*"
			,'968' => "YjoTqD" ,'969' => "&jh[ZQ" ,'970' => ",u3gL<" ,'971' => "o,+fa;" ,'972' => "[8Vkt)"
			,'973' => "He{vbq" ,'974' => "Qvw@Jl" ,'975' => "QB4zX%" ,'976' => "YhD7.p" ,'977' => "+kJLo("
			,'978' => "aMLP^," ,'979' => "Hyl2v/" ,'980' => "\$U0[fy" ,'981' => "Ate�Mp" ,'982' => "k/+Nx�"
			,'983' => "O�PqJo" ,'984' => "fkVzd~" ,'985' => "8pHJyF" ,'986' => "89%b@E" ,'987' => "JO@<?d"
			,'988' => "mw;7hF" ,'989' => "8t0No;" ,'990' => "rRKa0X" ,'991' => "/iteAD" ,'992' => "x&6P�t"
			,'993' => "{z.]fN" ,'994' => "v{(VZY" ,'995' => "g!n]wQ" ,'996' => ",*w{AZ" ,'997' => "q+*rdb"
		);
		if( isset( $keyPin[$index] ) ) return $keyPin[$index];
		else return "fd6.6<";
	}
}

if( !function_exists( 'processKeyPin' ) ){
	function generateSKED( $val, $type=null, $strSor=null ){
		$vaultOutput = '86s7WSUp!3!C288t4K5Pc73Jz29fdC9A'; /* default encryption key value */
		$arrVaultComb = array();
		if( !empty( $val ) ){
			$type = (int)substr( $val, -2 );
			$strSor = substr( $val, 0, 10 );
		}
		if( $type <= 18  ){
			$arrVaultComb = str_split( $strSor, 2 );
			for( $i = 0; $i < count( $arrVaultComb ); $i++ ){
				$arrVaultComb[$i] = str_pad( $arrVaultComb[$i], 3, '0', STR_PAD_LEFT );
			}
			if( $type == 1 ) $vaultOutput = _getKeyPin( $arrVaultComb[0] ) . _getKeyPin( $arrVaultComb[1] ) ._getKeyPin( $arrVaultComb[2] );
			elseif( $type == 2 ) $vaultOutput = _getKeyPin( $arrVaultComb[2] ) . _getKeyPin( $arrVaultComb[3] ) ._getKeyPin( $arrVaultComb[4] );
			elseif( $type == 3 ) $vaultOutput = _getKeyPin( $arrVaultComb[1] ) . _getKeyPin( $arrVaultComb[2] ) ._getKeyPin( $arrVaultComb[3] );
			elseif( $type == 4 ) $vaultOutput = _getKeyPin( $arrVaultComb[2] ) . _getKeyPin( $arrVaultComb[1] ) ._getKeyPin( $arrVaultComb[0] );
			elseif( $type == 5 ) $vaultOutput = _getKeyPin( $arrVaultComb[4] ) . _getKeyPin( $arrVaultComb[3] ) ._getKeyPin( $arrVaultComb[2] );
			elseif( $type == 6 ) $vaultOutput = _getKeyPin( $arrVaultComb[3] ) . _getKeyPin( $arrVaultComb[2] ) ._getKeyPin( $arrVaultComb[1] );
			elseif( $type == 7 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[0] ) ) . _getKeyPin( $arrVaultComb[1] ) ._getKeyPin( $arrVaultComb[2] );
			elseif( $type == 8 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[0] ) ) . _getKeyPin( strrev( $arrVaultComb[1] ) ) ._getKeyPin( $arrVaultComb[2] );
			elseif( $type == 9 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[0] ) ) . _getKeyPin( strrev( $arrVaultComb[1] ) ) ._getKeyPin( strrev( $arrVaultComb[2] ) );
			elseif( $type == 10 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[2] ) ) . _getKeyPin( $arrVaultComb[3] ) ._getKeyPin( $arrVaultComb[4] );
			elseif( $type == 11 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[2] ) ) . _getKeyPin( strrev( $arrVaultComb[3] ) ) ._getKeyPin( $arrVaultComb[4] );
			elseif( $type == 12 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[2] ) ) . _getKeyPin( strrev( $arrVaultComb[3] ) ) ._getKeyPin( strrev( $arrVaultComb[4] ) );
			elseif( $type == 13 ) $vaultOutput = _getKeyPin( strrev ( $arrVaultComb[1] ) ) . _getKeyPin(  strrev( $arrVaultComb[2] ) ) ._getKeyPin( $arrVaultComb[3] );
			elseif( $type == 14 ) $vaultOutput = _getKeyPin( strrev ( $arrVaultComb[1] ) ) . _getKeyPin(  strrev( $arrVaultComb[2] ) ) ._getKeyPin( strrev( $arrVaultComb[3] ) );
			elseif( $type == 15 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[2] ) ) . _getKeyPin( $arrVaultComb[1] ) ._getKeyPin( $arrVaultComb[0] );
			elseif( $type == 16 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[2] ) ) . _getKeyPin( strrev( $arrVaultComb[1] ) ) ._getKeyPin( $arrVaultComb[0] );
			elseif( $type == 17 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[2] ) ) . _getKeyPin( strrev( $arrVaultComb[1] ) ) ._getKeyPin( strrev( $arrVaultComb[0] ) );
			elseif( $type == 18 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[4] ) ) . _getKeyPin( strrev( $arrVaultComb[3] ) ) ._getKeyPin( strrev( $arrVaultComb[2] ) );
			elseif( $type == 19 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[3] ) ) . _getKeyPin( strrev( $arrVaultComb[2] ) ) ._getKeyPin( strrev( $arrVaultComb[1] ) );
		}
		elseif( $type <= 36 ){
			$arrVaultComb = str_split( strrev( $strSor ), 2 );
			for( $i = 0; $i < count( $arrVaultComb ); $i++ ){
				$arrVaultComb[$i] = str_pad( $arrVaultComb[$i], 3, '0', STR_PAD_LEFT );
			}
			if( $type == 20 ) $vaultOutput = _getKeyPin( $arrVaultComb[0] ) . _getKeyPin( $arrVaultComb[1] ) ._getKeyPin( $arrVaultComb[2] );
			elseif( $type == 21 ) $vaultOutput = _getKeyPin( $arrVaultComb[2] ) . _getKeyPin( $arrVaultComb[3] ) ._getKeyPin( $arrVaultComb[4] );
			elseif( $type == 22 ) $vaultOutput = _getKeyPin( $arrVaultComb[1] ) . _getKeyPin( $arrVaultComb[2] ) ._getKeyPin( $arrVaultComb[3] );
			elseif( $type == 23 ) $vaultOutput = _getKeyPin( $arrVaultComb[2] ) . _getKeyPin( $arrVaultComb[1] ) ._getKeyPin( $arrVaultComb[0] );
			elseif( $type == 24 ) $vaultOutput = _getKeyPin( $arrVaultComb[4] ) . _getKeyPin( $arrVaultComb[3] ) ._getKeyPin( $arrVaultComb[2] );
			elseif( $type == 25 ) $vaultOutput = _getKeyPin( $arrVaultComb[3] ) . _getKeyPin( $arrVaultComb[2] ) ._getKeyPin( $arrVaultComb[1] );
			elseif( $type == 26 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[0] ) ) . _getKeyPin( $arrVaultComb[1] ) ._getKeyPin( $arrVaultComb[2] );
			elseif( $type == 27 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[0] ) ) . _getKeyPin( strrev( $arrVaultComb[1] ) ) ._getKeyPin( $arrVaultComb[2] );
			elseif( $type == 28 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[0] ) ) . _getKeyPin( strrev( $arrVaultComb[1] ) ) ._getKeyPin( strrev( $arrVaultComb[2] ) );
			elseif( $type == 29 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[2] ) ) . _getKeyPin( $arrVaultComb[3] ) ._getKeyPin( $arrVaultComb[4] );
			elseif( $type == 30 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[2] ) ) . _getKeyPin( strrev( $arrVaultComb[3] ) ) ._getKeyPin( $arrVaultComb[4] );
			elseif( $type == 31 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[2] ) ) . _getKeyPin( strrev( $arrVaultComb[3] ) ) ._getKeyPin( strrev( $arrVaultComb[4] ) );
			elseif( $type == 32 ) $vaultOutput = _getKeyPin( strrev ( $arrVaultComb[1] ) ) . _getKeyPin(  strrev( $arrVaultComb[2] ) ) ._getKeyPin( $arrVaultComb[3] );
			elseif( $type == 33 ) $vaultOutput = _getKeyPin( strrev ( $arrVaultComb[1] ) ) . _getKeyPin(  strrev( $arrVaultComb[2] ) ) ._getKeyPin( strrev( $arrVaultComb[3] ) );
			elseif( $type == 34 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[2] ) ) . _getKeyPin( $arrVaultComb[1] ) ._getKeyPin( $arrVaultComb[0] );
			elseif( $type == 35 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[2] ) ) . _getKeyPin( strrev( $arrVaultComb[1] ) ) ._getKeyPin( $arrVaultComb[0] );
			elseif( $type == 36 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[2] ) ) . _getKeyPin( strrev( $arrVaultComb[1] ) ) ._getKeyPin( strrev( $arrVaultComb[0] ) );
			elseif( $type == 37 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[4] ) ) . _getKeyPin( strrev( $arrVaultComb[3] ) ) ._getKeyPin( strrev( $arrVaultComb[2] ) );
			elseif( $type == 38 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[3] ) ) . _getKeyPin( strrev( $arrVaultComb[2] ) ) ._getKeyPin( strrev( $arrVaultComb[1] ) );
		}
		elseif( $type <= 50 ){
			$arrVaultComb = str_split( $strSor, 3 );
			if( $type == 39 ) $vaultOutput = _getKeyPin( $arrVaultComb[3] ) . _getKeyPin( $arrVaultComb[2] ) ._getKeyPin( $arrVaultComb[1] );
			elseif( $type == 40 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[3] ) ) . _getKeyPin( $arrVaultComb[2] ) ._getKeyPin( $arrVaultComb[1] );
			elseif( $type == 41 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[3] ) ) . _getKeyPin( strrev( $arrVaultComb[2] ) ) ._getKeyPin( $arrVaultComb[1] );
			elseif( $type == 42 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[3] ) ) . _getKeyPin( strrev( $arrVaultComb[2] ) ) ._getKeyPin( strrev( $arrVaultComb[1] ) );
			elseif( $type == 43 ) $vaultOutput = _getKeyPin( $arrVaultComb[1] ) . _getKeyPin( $arrVaultComb[2] ) ._getKeyPin(  $arrVaultComb[3] );
			elseif( $type == 44 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[1] ) ) . _getKeyPin( $arrVaultComb[2] ) ._getKeyPin(  $arrVaultComb[3] );
			elseif( $type == 45 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[1] ) ) . _getKeyPin( strrev( $arrVaultComb[2] ) ) ._getKeyPin(  $arrVaultComb[3] );
			elseif( $type == 46 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[1] ) ) . _getKeyPin( strrev( $arrVaultComb[2] ) ) ._getKeyPin(  strrev( $arrVaultComb[3] ) );
			elseif( $type == 47 ) $vaultOutput = _getKeyPin( $arrVaultComb[0] ) . _getKeyPin( $arrVaultComb[1] ) ._getKeyPin(  $arrVaultComb[2] );
			elseif( $type == 48 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[0] ) ) . _getKeyPin( $arrVaultComb[1] ) ._getKeyPin(  $arrVaultComb[2] );
			elseif( $type == 49 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[0] ) ) . _getKeyPin( strrev( $arrVaultComb[1] ) ) ._getKeyPin(  $arrVaultComb[2] );
			elseif( $type == 50 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[0] ) ) . _getKeyPin( strrev( $arrVaultComb[1] ) ) ._getKeyPin( strrev( $arrVaultComb[2] ) );
		}
		elseif( $type <= 62 ){
			$arrVaultComb = str_split( strrev( $strSor ), 3 );
			if( $type == 51 ) $vaultOutput = _getKeyPin( $arrVaultComb[3] ) . _getKeyPin( $arrVaultComb[2] ) ._getKeyPin( $arrVaultComb[1] );
			elseif( $type == 52 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[3] ) ) . _getKeyPin( $arrVaultComb[2] ) ._getKeyPin( $arrVaultComb[1] );
			elseif( $type == 53 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[3] ) ) . _getKeyPin( strrev( $arrVaultComb[2] ) ) ._getKeyPin( $arrVaultComb[1] );
			elseif( $type == 54 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[3] ) ) . _getKeyPin( strrev( $arrVaultComb[2] ) ) ._getKeyPin( strrev( $arrVaultComb[1] ) );
			elseif( $type == 55 ) $vaultOutput = _getKeyPin( $arrVaultComb[1] ) . _getKeyPin( $arrVaultComb[2] ) ._getKeyPin(  $arrVaultComb[3] );
			elseif( $type == 56 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[1] ) ) . _getKeyPin( $arrVaultComb[2] ) ._getKeyPin(  $arrVaultComb[3] );
			elseif( $type == 57 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[1] ) ) . _getKeyPin( strrev( $arrVaultComb[2] ) ) ._getKeyPin(  $arrVaultComb[3] );
			elseif( $type == 58 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[1] ) ) . _getKeyPin( strrev( $arrVaultComb[2] ) ) ._getKeyPin(  strrev( $arrVaultComb[3] ) );
			elseif( $type == 59 ) $vaultOutput = _getKeyPin( $arrVaultComb[0] ) . _getKeyPin( $arrVaultComb[1] ) ._getKeyPin(  $arrVaultComb[2] );
			elseif( $type == 60 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[0] ) ) . _getKeyPin( $arrVaultComb[1] ) ._getKeyPin(  $arrVaultComb[2] );
			elseif( $type == 61 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[0] ) ) . _getKeyPin( strrev( $arrVaultComb[1] ) ) ._getKeyPin(  $arrVaultComb[2] );
			elseif( $type == 62 ) $vaultOutput = _getKeyPin( strrev( $arrVaultComb[0] ) ) . _getKeyPin( strrev( $arrVaultComb[1] ) ) ._getKeyPin( strrev( $arrVaultComb[2] ) );
		}
		return $vaultOutput;
	}
}

if( !function_exists( 'initializeSalt' ) ){
	function initializeSalt( $value ){
		if( !empty( $value ) ){
			if( strlen( $value ) < 5 ) $value = str_pad($value, 5, "A", STR_PAD_RIGHT);
			$value = str_replace( getIntEq(), getLetEq(), $value );
			
			return str_replace( getLetEq(), getNumEq(), substr( strtolower($value), 0, 5 ) ) . mt_rand(10, 99);
		} else {
			die('Initialize Salt: VALUE PASSED IS EMPTY.');
		}
	}
}

if( !function_exists( 'decryptAffiliate' ) ){
	function decryptAffiliate( $record ){
		$ci =& get_instance();
		$ci->load->library('encryption');
		
		foreach( $record as $index => $affiliate ){
			if( isset($affiliate['sk']) && !empty( $affiliate['sk'] ) || isset($affiliate['affiliateSK']) && !empty( $affiliate['affiliateSK'] )){
				if( isset($affiliate['affiliateSK']) ) $affiliate['sk'] = $affiliate['affiliateSK'];

				$ci->encryption->initialize( array( 'key' => generateSKED( $affiliate['sk'] )) );
				if(array_key_exists( 'affiliateName', $affiliate ) && !empty($affiliate['affiliateName'])) $record[$index]['affiliateName'] = $ci->encryption->decrypt( $affiliate['affiliateName']);
				if(array_key_exists( 'accSchedule', $affiliate ) && !empty($affiliate['accSchedule'])) $record[$index]['accSchedule'] = $ci->encryption->decrypt( $affiliate['accSchedule']);
				if(array_key_exists( 'reviewedBy', $affiliate ) && !empty($affiliate['reviewedBy'])) $record[$index]['reviewedBy'] = $ci->encryption->decrypt( $affiliate['reviewedBy']);
				if(array_key_exists( 'checkedBy', $affiliate ) && !empty($affiliate['checkedBy'])) $record[$index]['checkedBy'] = $ci->encryption->decrypt( $affiliate['checkedBy']);

				/**Insert custom [named] fields here**/
				if(array_key_exists( 'name', $affiliate ) && !empty($affiliate['name']) ) $record[$index]['name'] = $ci->encryption->decrypt( $affiliate['name']);
				if(array_key_exists( 'affiliateAccSched', $affiliate ) && !empty($affiliate['affiliateAccSched']) ) $record[$index]['affiliateAccSched'] = $ci->encryption->decrypt( $affiliate['affiliateAccSched']);

				unset( $record[$index]['affiliateSK'] );
			}
		}
		return $record;
	}
}

if( !function_exists( 'decryptItem') ){
	function decryptItem( $record ){
		$ci =& get_instance();
		$ci->load->library('encryption');

		foreach( $record as $index => $item ){
			if( isset( $item['sk'] ) && !empty ( $item['sk'] ) ){
				$ci->encryption->initialize( array( 'key' => generateSKED( $item['sk'] )) );
				if(array_key_exists( 'itemName', $item ) && !empty($item['itemName'])) $record[$index]['itemName'] = $ci->encryption->decrypt( $item['itemName']);
				if(array_key_exists( 'itemPrice', $item ) && !empty($item['itemPrice'])) $record[$index]['itemPrice'] = $ci->encryption->decrypt( $item['itemPrice']);

				/**Insert custom [named] fields here**/
				if(array_key_exists( 'cost', $item ) && !empty($item['cost']) && strlen( $item['cost'] ) > 100 ) $record[$index]['cost'] = $ci->encryption->decrypt( $item['cost']);
				if(array_key_exists( 'price', $item ) && !empty($item['price']) && strlen( $item['price'] ) > 100 ) $record[$index]['price'] = $ci->encryption->decrypt( $item['price']);
				if(array_key_exists( 'name', $item ) && !empty($item['name'])) $record[$index]['name'] = $ci->encryption->decrypt( $item['name']);

				unset( $record[$index]['sk'] );
			}
		}
		return $record;
	}
}

if( !function_exists( 'decryptBank') ){
	function decryptBank( $record ){
		$ci =& get_instance();
		$ci->load->library('encryption');

		foreach( $record as $index => $bank ){
			if( isset( $bank['sk'] ) && !empty ( $bank['sk'] ) ){
				$ci->encryption->initialize( array( 'key' => generateSKED( $bank['sk'] )) );
				if(array_key_exists( 'bankName', $bank ) && !empty($bank['bankName'])) $record[$index]['bankName'] = $ci->encryption->decrypt( $bank['bankName']);
				
				/**Insert custom [named] fields below**/
				if(array_key_exists( 'name', $bank ) && !empty($bank['name'])) $record[$index]['name'] = $ci->encryption->decrypt( $bank['name']);
			}
		}
		return $record;
	}
}

if( !function_exists( 'decryptBankAccount') ){
	function decryptBankAccount( $record ){
		$ci =& get_instance();
		$ci->load->library('encryption');

		foreach( $record as $index => $bankAccount ){
			if( isset( $bankAccount['sk'] ) && !empty ( $bankAccount['sk'] ) ){
				$ci->encryption->initialize( array( 'key' => generateSKED( $bankAccount['sk'] )) );
				if(array_key_exists( 'bankAccount', $bankAccount ) && !empty($bankAccount['bankAccount'])) $record[$index]['bankAccount'] = $ci->encryption->decrypt( $bankAccount['bankAccount']);
				if(array_key_exists( 'bankAccountNumber', $bankAccount ) && !empty($bankAccount['bankAccountNumber'])) $record[$index]['bankAccountNumber'] = $ci->encryption->decrypt( $bankAccount['bankAccountNumber']);
				
				/**Insert custom [named] fields below**/
				if(array_key_exists( 'name', $bankAccount ) && !empty($bankAccount['name'])) $record[$index]['name'] = $ci->encryption->decrypt( $bankAccount['name']);
			}
		}
		return $record;
	}
}

if( !function_exists( 'decryptCustomer') ){
	function decryptCustomer( $record ){
		$ci =& get_instance();
		$ci->load->library('encryption');

		foreach( $record as $index => $customer ){
			if( isset( $customer['sk'] ) && !empty ( $customer['sk'] ) || isset($customer['customerSK']) && !empty( $customer['customerSK'] ) ){
				if( isset( $customer['customerSK'])) $customer['sk'] = $customer['customerSK'];

				$ci->encryption->initialize( array( 'key' => generateSKED( $customer['sk'] )) );
				if( array_key_exists( 'name', $customer ) && !empty($customer['name']) ) $record[$index]['name'] = $ci->encryption->decrypt( $customer['name'] );
				if( array_key_exists( 'email', $customer ) && !empty($customer['email']) ) $record[$index]['email'] = $ci->encryption->decrypt( $customer['email']);
				if( array_key_exists( 'contactNumber', $customer ) && !empty($customer['contactNumber']) ) $record[$index]['contactNumber'] = $ci->encryption->decrypt( $customer['contactNumber'] );
				if( array_key_exists( 'address', $customer ) && !empty($customer['address']) ) $record[$index]['address'] = $ci->encryption->decrypt( $customer['address'] );
				if( array_key_exists( 'tin', $customer ) && !empty($customer['tin']) ) $record[$index]['tin'] = $ci->encryption->decrypt( $customer['tin'] );
				
				/**Insert custom [named] fields below**/
				if(array_key_exists( 'customerName', $customer ) && !empty($customer['customerName'])) $record[$index]['customerName'] = $ci->encryption->decrypt( $customer['customerName']);
				if( array_key_exists( 'customerContactNumber', $customer ) && !empty($customer['customerContactNumber']) ) $record[$index]['customerContactNumber'] = $ci->encryption->decrypt( $customer['customerContactNumber'] );
			}

			unset( $record[$index]['customerSK'] );
		}
		return $record;
	}
}

if( !function_exists( 'decryptSupplier') ){
	function decryptSupplier( $record ){
		$ci =& get_instance();
		$ci->load->library('encryption');

		foreach( $record as $index => $supplier ){
			if( isset( $supplier['sk'] ) && !empty ( $supplier['sk'] ) || isset($supplier['supplierSK']) && !empty( $supplier['supplierSK'] ) ){
				if( isset( $supplier['supplierSK'])) $supplier['sk'] = $supplier['supplierSK'];
				
				$ci->encryption->initialize( array( 'key' => generateSKED( $supplier['sk'] )) );
				if( array_key_exists( 'name', $supplier ) && !empty($supplier['name']) ) $record[$index]['name'] = $ci->encryption->decrypt( $supplier['name'] );
				if( array_key_exists( 'email', $supplier ) && !empty($supplier['email']) ) $record[$index]['email'] = $ci->encryption->decrypt( $supplier['email']);
				if( array_key_exists( 'contactNumber', $supplier ) && !empty($supplier['contactNumber']) ) $record[$index]['contactNumber'] = $ci->encryption->decrypt( $supplier['contactNumber'] );
				if( array_key_exists( 'address', $supplier ) && !empty($supplier['address']) ) $record[$index]['address'] = $ci->encryption->decrypt( $supplier['address'] );
				if( array_key_exists( 'tin', $supplier ) && !empty($supplier['tin']) ) $record[$index]['tin'] = $ci->encryption->decrypt( $supplier['tin'] );
				
				/**Insert custom [named] fields below**/
				if(array_key_exists( 'supplierName', $supplier ) && !empty($supplier['supplierName'])) $record[$index]['supplierName'] = $ci->encryption->decrypt( $supplier['supplierName']);
			}
		}

		return $record;
	}
}

if( !function_exists( 'decryptUserData') ){
	function decryptUserData( $record ){
		$ci =& get_instance();
		$ci->load->library('encryption');

		if( !empty( $record ) ){
			foreach( $record as $index => $user ){

				if( isset( $user['sk'] ) && !empty( $user['sk'] ) || isset($user['employeeSK']) && !empty( $user['employeeSK'] ) ){
					if( isset( $user['employeeSK'])) $user['sk'] = $user['employeeSK'];

					$ci->encryption->initialize( array( 'key' => generateSKED( $user['sk'] ) ) );
					/*Employee*/
					if( array_key_exists( 'name', $user ) && !empty( $user['name'] ) ) $record[$index]['name'] = $ci->encryption->decrypt( $user['name']);
					if( array_key_exists( 'address', $user ) && !empty( $user['address'] ) ) $record[$index]['address'] = $ci->encryption->decrypt( $user['address']);
					if( array_key_exists( 'contactNumber', $user ) && !empty( $user['contactNumber'] ) ) $record[$index]['contactNumber'] = $ci->encryption->decrypt( $user['contactNumber']);
					if( array_key_exists( 'email', $user ) && !empty( $user['email'] ) ) $record[$index]['email'] = $ci->encryption->decrypt( $user['email']);
					if( array_key_exists( 'birthdate', $user ) && !empty( $user['birthdate'] ) ) $record[$index]['birthdate'] = $ci->encryption->decrypt( $user['birthdate']);

					if( array_key_exists( 'driverName', $user ) && !empty( $user['driverName'] ) ) $record[$index]['driverName'] = $ci->encryption->decrypt( $user['driverName']);


					/**Employment**/
					if( array_key_exists( 'dateEmployed', $user ) && !empty( $user['dateEmployed'] ) ) $record[$index]['dateEmployed'] = $ci->encryption->decrypt( $user['dateEmployed']);
					if( array_key_exists( 'dateEffective', $user ) && !empty( $user['dateEffective'] ) ) $record[$index]['dateEffective'] = $ci->encryption->decrypt( $user['dateEffective']);
					if( array_key_exists( 'endOfContract', $user ) && !empty( $user['endOfContract'] ) ) $record[$index]['endOfContract'] = $ci->encryption->decrypt( $user['endOfContract']);
					if( array_key_exists( 'monthRate', $user ) && !empty( $user['monthRate'] ) ) $record[$index]['monthRate'] = $ci->encryption->decrypt( $user['monthRate']);
					

					/**Benefits**/
					if( array_key_exists( 'description', $user ) && !empty( $user['description'] ) ) $record[$index]['description'] = $ci->encryption->decrypt( $user['description']);
					if( array_key_exists( 'amount', $user ) && !empty( $user['amount'] ) ) $record[$index]['amount'] = $ci->encryption->decrypt( $user['amount']);
					
					/**Custom**/
					if( array_key_exists( 'fullName', $user ) && !empty( $user['fullName'] ) ) $record[$index]['fullName'] = $ci->encryption->decrypt( $user['fullName']);
					if( array_key_exists( 'employeeName', $user ) && !empty( $user['employeeName'] ) ) $record[$index]['employeeName'] = $ci->encryption->decrypt( $user['employeeName']);
					
					unset( $record[$index]['sk'] );
					unset( $record[$index]['employeeSK'] );
				}
			}
		}
		return $record;
	}
}

if( !function_exists('decryptCostCenter') ){
	function decryptCostCenter( $record ){
		$ci =& get_instance();
		$ci->load->library('encryption');

		foreach( $record as $index => $costCenter ){
			if( isset( $costCenter['sk'] ) && !empty ( $costCenter['sk'] ) || isset($costCenter['costCenterSK']) && !empty( $costCenter['costCenterSK'] ) ){
				if( isset($costCenter['costCenterSK']) ) $costCenter['sk'] = $costCenter['costCenterSK'];
				
				$ci->encryption->initialize( array( 'key' => generateSKED( $costCenter['sk'] )) );
				if(array_key_exists( 'costCenterName', $costCenter ) && !empty($costCenter['costCenterName'])) $record[$index]['costCenterName'] = $ci->encryption->decrypt( $costCenter['costCenterName']);
				
				/**Insert custom [named] fields below**/
				if(array_key_exists( 'name', $costCenter ) && !empty($costCenter['name'])) $record[$index]['name'] = $ci->encryption->decrypt( $costCenter['name']);
			}
		}
		return $record;
	}
}

// found this function here: https://stackoverflow.com/questions/5853380/php-get-number-of-week-for-month
if( !function_exists('getWeeks') ){
	function getWeeks( $date, $rollover = 'sunday' ){
        $cut = substr( $date, 0, 8 );
        $daylen = 86400;

        $timestamp = strtotime( $date );
        $first = strtotime( $cut . "00" );
        $elapsed = ( $timestamp - $first ) / $daylen;

        $weeks = 1;

        for ($i = 1; $i <= $elapsed; $i++)
        {
            $dayfind = $cut . (strlen($i) < 2 ? '0' . $i : $i);
            $daytimestamp = strtotime($dayfind);

            $day = strtolower(date("l", $daytimestamp));

            if($day == strtolower($rollover))  $weeks ++;
        }

        return $weeks;
    }
}

/** This function is used for validating user session before allowing to access API **/
if( !function_exists( 'validSession' ) ){
	function validSession(){
		$ci =& get_instance();

		if($ci->session->userdata('USERNAME')!=''){
			return ( $ci->session->userdata('logged_in') == 1 ) ? 1 : 0;
		}else{
			return 0;
		}
	}
}

if( !function_exists( 'middleware' ) ){ 
	function middleware($msg = '') {
		$msg = $msg != ''? $msg : 'You are not authorized to perform this action.';
	
		if (!validSession()) {
			die(
				json_encode(
					array(
						'status' => false,
						'msg' => $msg,
					)
				)
			);
		}
	}
}

?>