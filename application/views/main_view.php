<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF8" />

<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url(); ?>images/logo/favicon.ico"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>js/ext/theme/ext/packages/ext-theme-classic/build/resources/ext-theme-classic-all.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/bootstrap/css/bootstrap.css" />
<script type="text/javascript" src="<?php echo base_url();?>js/polyfill.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/babel.js"></script>
<style>
	#navigation {
		background: -moz-linear-gradient(center left , #007FE5 ,#007FE5 );	!important;
		background: -webkit-linear-gradient(right , #007FE5 ,#007FE5 );
		background: -o-linear-gradient(right , #007FE5 ,#007FE5 );
		background: -ms-linear-gradient(right , #007FE5 ,#007FE5 );
		background: linear-gradient(to right , #007FE5 ,#007FE5 );
		max-height: 31px;
		min-height: 31px;
		min-width: 880px;
	}
	
	#navigation .menu-1 li{
		background : url(<?php echo base_url();?>css/bootstrap_login/images/theme/navigation-separator.jpg) center right no-repeat;
	}
	
	#navigation .menu-2 li{
		background : url(<?php echo base_url();?>css/bootstrap_login/images/theme/navigation-separator.jpg) center left no-repeat;
	}
	#navigation .comboDepartment .x-btn-arrow-right{
		padding-right:0 !important;
		background-image:none !important;
	}
	#navigation .comboDepartment .x-btn-inner{
		color: #FFF !important;
		/* font-weight:bold !important; */
	}
	#navigation .adjustment .x-btn-arrow-right{
		padding-right:0 !important;
		background-image:none !important;
	}
	#navigation .adjustment .x-btn-inner{
		color: #FFF !important;
		/* font-weight:bold !important; */
	}
	
	#navigation a,#navigation li,#navigation .glyphicon{
		color:#fff;
		font-weight:bold;
	}
	
	#navigation a:hover {
		color: #1F3A93 ;
	}
	
	.visited{
		color: #1F3A93 !important;
	}
	
	#department a {
		background-color: transparent !important;
		background-image: none !important;
	}
	
	#navigation ul li {
		display: table-cell;
		padding-left: 16px;
		padding-right: 16px;
		vertical-align: middle;
		cursor:pointer;
	}
	
	#navigation .menu-2{
		float: right;
	}
	
	#navigation ul {
		display: table;
		float: left;
		height: 31px;
		max-height: 31px;
		min-height: 31px;
	}
	
	.pdf-icon{
		background-image: url(<?php echo base_url(); ?>images/icons/small-icon/pdf.png) !important; 
	}
	.excel {
		background-image: url(<?php echo base_url(); ?>images/icons/small-icon/icon_excel.png) !important;
	}
	.modu {
		background-image: url(<?php echo base_url(); ?>images/icons/small-icon/form.png) !important;
	}
	.list {
		background-image: url(<?php echo base_url(); ?>images/icons/small-icon/payment-sum.png) !important;
	}
	.save {
		background-image: url(<?php echo base_url(); ?>images/icons/small-icon/save.png) !important;
	}
	.reset {
		background-image: url(<?php echo base_url(); ?>images/icons/small-icon/reset.png) !important;
	}
	.details {
		background-image: url(<?php echo base_url(); ?>images/icons/small-icon/view_details.png) !important;
	}
	
	.menuActive  {
		background-color:transparent !important;
		background-image:none !important;
		border-width:0 !important;
	} 
	.menuActive .x-btn-inner{
		color : #374E63;
		font-weight : bold;
	}
	
	.menuInactive{
		background-color:transparent !important;
		background-image:none !important;
		border-width:0 !important;
	} 
	.menuInactive .x-btn-inner{
		color : #467CA3;
		font-weight : bold;
	}
	
	.comboDepartment.x-btn-default-small,.comboDepartment.x-btn-default-small-over{
		background-color : transparent !important;
		background-image : none !important;
		/* margin-top: -4px; */
	}
	.adjustment.x-btn-default-small,.comboDepartment.x-btn-default-small-over{
		background-color : transparent !important;
		background-image : none !important;
		/* margin-top: -4px; */
	}
	.cirlce{
		border-radius:99px;
		-moz-border-radius:99px;
		-webkit-border-radius:99px;
		background-color:#007FE5;
		color:#fff !important;
		font-size: 10px;
		height: 20px;
		margin-right: 5px;
		text-align: center;
		width: 20px;
		display:inline-block;
	}
	#supermenu_mainView .x-tree-elbow-img{
		display:none;
	}
	.x-tree-main{
		background-color:#98BCEA !important;
	}
	.comboDepartment .x-btn-inner-center{
		font-size	: 13px;
		font-family	: inherit !important;
	}
	.adjustment .x-btn-inner-center{
		font-size	: 13px;
		/* font-family	: inherit !important; */
	}
	.comboSubMain .x-btn-arrow{
		background-image: none !important;
	}
	.comboSubMain.x-btn{
		cursor: default !important;
	}
	.x-btn .glyphicon {
		color : #416da3 !important;
	}
	.refreshSearch{
		background-color : transparent !important;
		background-image : none !important;
	}
	
	.difCls .x-form-item-label {
		color : red;
		font-weight : bold;
	}
	
	.difCls .x-form-text {
		color : red;
	}
</style>
</head>
<body>
	<div id="loading">

	</div>
</body>


<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.touch.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/momentjs.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/ext/theme/ext/ext-all.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/mainview/Mainview.js"></script>

<script type = "text/javascript">
	Ext.onReady( function(){
		mainView.initMethod({
			baseurl : "<?php echo site_url(); ?>"
		});
	});
</script>
</html>