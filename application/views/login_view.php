<!DOCTYPE html>
<html lang="en">
	<style>
		body { 
			background-color: #E4E4E4 !important; 
			-webkit-background-size: 100% 100%;
			-moz-background-size: 100% 100%;
			-o-background-size: 100% 100%;
			background-size: 100% 100%;
			
		}
		.card {
			margin:130px auto 0;
			min-height:311px;
			width:530px;
			border-radius: 5px;
			background: #fff;
			-webkit-box-shadow: 3px 3px 7px rgba(0,0,0,0.3);
			box-shadow: 3px 3px 7px rgba(0,0,0,0.3);
		}
		table{
			border-collapse:inherit !important; 
		}
		#img-lock{
			display: block;
			height: 120px;
			width: 130px;
			margin: 0 auto;
		}
		#img-logo{
			//width:300px;
			// height:90px;
			// height:65px;
			max-height:100px;
		}
		/* body select option:not( [disabled] ) */
		input[type="text"]{
			height:40px;  
			padding: 8px;
			border-radius:5px;
			color:#428BCA !important;
			font-size:15px;
			font-weight:bold;
			border-color:#428BCA !important;
		}
		.input-group,.btn{
			margin-top:5px;
		}
		input[type="password"]{
			height:40px; 
			padding: 8px;
			border-radius:5px;
			color:#428BCA;
			font-size:15px;
			font-weight:bold;
			border-color:#428BCA !important; 
		}
		.btn{
			height:40px !important;
			font-size:15px !important;
			background-color:#428BCA !important;
			border-color:#428BCA !important;
		}
		.form-group{
			width: 420px;
			margin:10px auto;
		}
		.systemName{
			color:#428BCA !important; 
			font-size: 18px !important;
		}
		.input-group-addon{
			padding: 6px 28px !important;
			border-color:#428BCA !important;
			background-color:#428BCA !important;
		}
		.input-group-addon .glyphicon {
			color:#fff;
		}
		.alert {
			background-color:transparent !important;
			border-color:transparent !important;
		}
		select {
			height:40px;
			color:#428BCA;
			font-size:15px;
			font-weight:bold;
			border-color:#428BCA !important;
		}
		option[disabled],
		::-webkit-input-placeholder { /* Edge */
		  color: #B3B3B8 !important;
		}
		option[disabled],
		:-ms-input-placeholder { /* Internet Explorer */
		  color: #B3B3B8 !important;
		}
		body select,
		::placeholder {
		  color: #B3B3B8 ;
		}
	</style>
	<script src="<?php echo base_url();?>js/polyfill.js"></script>
	<script src="<?php echo base_url();?>js/babel.js"></script>
<head>
	<?php
		$logoname = '';
		if( $comp['logo'] ){
			if( isset($_SERVER['SERVER_SOFTWARE'] ) && strpos( $_SERVER['SERVER_SOFTWARE'], 'Google App Engine' ) !== false ){
				$logoname = LOGO_PATH . $comp['logo'];
			}
			else{
				$logoname = base_url() . 'images/logo/' . $comp['logo'];
				//$logoname = base_url() . 'images/logo/' . $comp['logo'];
			}
		}
		
		
		if( !is_url_exist( $logoname ) ){
			$logoname = LOGO_PATH.DEFAULT_EMPTY_IMG;
			if( isset( $_SERVER['SERVER_SOFTWARE'] ) && strpos( $_SERVER['SERVER_SOFTWARE'], 'Google App Engine' ) !== false ){
				$logoname = 'data:image/png;base64,' . base64_encode( file_get_contents( LOGO_PATH . DEFAULT_EMPTY_IMG ) );
			}
			else{
				$logoname = ( !empty($comp['logo']) && is_url_exist( LOGO_PATH . $comp['logo'] ) ) ? LOGO_PATH . $comp['logo']  : LOGO_PATH . DEFAULT_EMPTY_IMG;
			}
		}

		// list($width, $height)= getimagesize($logoname);
		// list($width, $height)= ($logoname) ? getimagesize($logoname): 0;
		list($width, $height)= file_exists($logoname) ? getimagesize($logoname): 0;
		if(  $width ==$height  )$new_width="50%" ;
		$new_width = "100%";

		$systemName = "Trucking and Construction | Accounting System";

	?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url(); ?>images/logo/favicon.ico"/>
    <title><?php echo /* $systemName */'Trucking and Construction Accounting System'; ?></title>
    <link href="<?php echo base_url(); ?>css/bootstrap_login/css/bootstrap.min.css" rel="stylesheet">
    </head>
   <body id="login">
		<div id="container_locationlogin">
			<div id="login-wrapper2">
				<div id="login-bg" style="height:360px;">
					
					<div class="card">
						<table width="100%" height="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 50px;" >
							<tr >
								<td style="padding:10px;" width="100%">
									<table width="100%" height="100%" >
										<tr height="90%">
											<td>
											<table width="100%">
											<tr>
											<td styl="width:20% !important" >
											</td >
											<td  style="width:<?php echo $new_width; ?> !important;text-align: center;">
											<!-- width: 100% !important;  -->
											<img class="text-center" style="max-height: 150px !important; max-width: 465px !important;"  id="img-logo" alt="<?php echo $systemName; ?>" src="<?php echo $logoname; ?>">
											</td>
											<td  styl="width:20% !important" >
											</td>
											</tr>
											</table>
											</td>
										</tr>
									</table>
								</td>
								
							</tr>
							<tr>
								<td colspan="2">
									<table id="login-container" width="100%" height="100%">
										<tr height="90%">
											<td style="color: #428BCA; text-align: center; text-align: center; font-weight: bold; font-size: 18px;"> <span>Trucking and Construction Accounting System</span> </td>
										</tr>
										<tr>
											<td valign="top" style="border-bottom-right-radius:5px; width:75%; height: 100%;">
												<div class="form-group">
													<div class="input-group">
														<div class="input-group-addon">
															<span class="glyphicon glyphicon-user"></span>
														</div>
														<input type="text" class="form-control" name="username" id="username"  placeholder="Username" value="<?php // echo $username; ?>" >
													</div>
													<div class="input-group">
														<div class="input-group-addon">
															<span class="glyphicon glyphicon-lock"></span>
														</div>
														<input type="password" class="form-control"  name="password" id="password" placeholder="Password" value='<?php // echo $password; ?>'>
													</div>
													<div class="input-group" id="affiliateGroup" style="display: none;">
														<div class="input-group-addon">
															<span class="glyphicon glyphicon-home"></span>
														</div>
														<!-- <select class="form-control" id="affiliate" name="affiliate">
															<option value="" selected="" style="" disabled=""> Select Affiliate </option>
														</select> -->
														
														<script>
															function getSelectedAffiliate() {
																var input = document.getElementById("selectedAffliate");
																var affiliateList = document.getElementById('affiliate').options;
																var selectedAffiliate = Array.from( affiliateList ).map( option => { if( input.value == option.value ) return option.id } ).filter(id => typeof id != 'undefined' );
														
																input.idAffiliate = selectedAffiliate[0];
															}
														</script>
														<datalist id="affiliate">
															<option value="Select Affiliate" id="0"></option>
														</datalist>
														<input type="text" list="affiliate" class="form-control" onblur="getSelectedAffiliate()" id="selectedAffliate" placeholder="Select Affiliate"/>
														
													</div>
													<button class="btn btn-primary btn-block" type="submit" id="login_btn"><span class="glyphicon glyphicon-log-out" style="margin-right:10px;"></span><strong style="font-size:18px;">LOGIN</strong></button>
													
													<table width="100%" style="display: block; margin-top: 5px;" id="logStatus">
														<tr>
															<td width="25%">
																<i style="color:#428bca;">Login Status: </i>
															</td>
															<td width="75%">
																<div id="warnmsg" class="alert alert-danger" 
																	style=" display: none;font-size: 12px;
																			margin: -18px auto 0 -9px;
																			padding: 8px;
																			position: absolute;
																			width: 335px;">
																	<div id="warnmsg-text"></div>
																</div>
															</td>
														</tr>
													</table>
												</div>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</body>
	
	
	<script src="<?php echo base_url();?>js/jquery.js"></script>
    <script src="<?php echo base_url();?>css/bootstrap_login/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-ui.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.touch.js"></script>

	<script type="text/babel">
		var baseurl = "<?php echo base_url(); ?>";
		addlocalStorageOpenedBrowser();
		$( document ).ready( function(){	
			let counter = 0;
			
			$('#username').on('blur',function(evnt){
				var affiliateSelect = document.getElementById('affiliate')
				affiliateSelect.innerHTML = ""
				var opt = document.createElement('option')
				// opt.value = ''
				// opt.text = 'Select Affiliate'
				opt.value = 'Select Affiliate'
				opt.id = 0
				affiliateSelect.appendChild(opt)
				$.ajax({
					url			: "<?php echo site_url( 'home/getAffiliateByUser' ) ?>"
					,type		: 'post'
					,data		: {
						username: $("#username").val()
					}
					,success	: function( result ){
						var ret = $.parseJSON( result );
						
						if( parseInt(ret) == 0){
							// alert('The user is inactive or deleted. Please contact your administrator.');
						}else{
							ret.map(option =>{
								// var opt = document.createElement('option')
								// opt.value = option.id
								// opt.text = option.name
								// affiliateSelect.appendChild(opt)

								var opt = document.createElement('option')
								opt.value = option.name
								opt.id= option.id
								affiliateSelect.appendChild(opt)
							})
						}
					}
					// ,error	: function(){
					// 	$( '#warnmsg' ).show();
					// 	document.getElementById( 'warnmsg' ).className = 'alert alert-danger';
					// 	document.getElementById( 'warnmsg-text' ).innerHTML = '<span class="glyphicon glyphicon-remove-sign"></span>&nbsp;&nbsp;Database connectivity error. Please make sure you are connected to the network and try again.';
					// }
				});
			})
			
			$( "#login_btn" ).click( function(){
				document.getElementById( 'warnmsg' ).className = 'alert alert-danger';	
				var user_name = document.getElementById( 'username' );
				var pass_word = document.getElementById( 'password' );
				var affiliateHolder = document.getElementById( 'selectedAffliate' ); /*Original: var affiliateHolder = document.getElementById( 'affiliate' ); */
				
				if( user_name.value.length == 0 ){
					$( '#warnmsg' ).show();
					document.getElementById( 'warnmsg-text' ).innerHTML = '<span class="glyphicon glyphicon-remove-sign"></span>&nbsp;&nbsp;Username is required.';
					return;
				}
				if( pass_word.value.length == 0 ){
					$( '#warnmsg' ).show();
					document.getElementById( 'warnmsg-text' ).innerHTML = '<span class="glyphicon glyphicon-remove-sign"></span>&nbsp;&nbsp;Password is required.';
					return;
				}
				
				// console.log(user_name);
				// console.log(affiliateHolder.value);

				if( affiliateHolder.idAffiliate == 0 ){
					$( '#warnmsg' ).show();
					document.getElementById( 'warnmsg-text' ).innerHTML = '<span class="glyphicon glyphicon-remove-sign"></span>&nbsp;&nbsp;Please select the appropriate affiliate.';
					return;
				}
				
				/* Original 

				if ( affiliateHolder.value == 0 ) {
					$( '#warnmsg' ).show();
					document.getElementById( 'warnmsg-text' ).innerHTML = '<span class="glyphicon glyphicon-remove-sign"></span>&nbsp;&nbsp;Please select the appropriate affiliate.';
					return;
				}

				*/
				
				// return false;
				
				/*adding please wait message*/
				$( '#warnmsg' ).show();
				document.getElementById( 'warnmsg' ).className = 'alert alert-info';
				document.getElementById( 'warnmsg-text' ).innerHTML = '<span class="glyphicon glyphicon-info-sign"></span>&nbsp;&nbsp;Please wait...';
				/*end adding*/
				

				$.ajax({
					url		: '<?php echo site_url('home/verifyUser') ?>'
					,type	: 'post'
					,data	: {
						username 	: user_name.value
						,password	: pass_word.value
					}
					,success	: function( response ){
						var resp = $.parseJSON( response );

						if( resp.view != null ){
							document.getElementById('affiliateGroup').style.display = "";

							/*adding please select affiliate message*/
							$( '#warnmsg' ).show();
							document.getElementById( 'warnmsg' ).className = 'alert alert-info';
							document.getElementById( 'warnmsg-text' ).innerHTML = '<span class="glyphicon glyphicon-info-sign"></span>&nbsp;&nbsp;Select an affiliate...';
							/*end adding*/

							/* Login user function was moved here */
							if( affiliateHolder.idAffiliate != null ){
								$.ajax( {
									url			: "<?php echo site_url( 'home/loginUser' ) ?>"
									,type		: 'post'
									,data		: {
										username		: user_name.value
										,password		: pass_word.value
										,affiliateID	: affiliateHolder.idAffiliate /* Original: affiliateHolder.value */
									}
									,success	: function( result ){
										$( '#logStatus' ).show();
										var ret = $.parseJSON( result );
										
										result = ( typeof ret.trigger != 'undefined' ? ret.trigger : ret.match );
										if( result == 0 ){
											$( '#warnmsg' ).show();
											document.getElementById( 'warnmsg' ).className = 'alert alert-success';
											document.getElementById( 'warnmsg' ).innerHTML = '<span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;Successfully logged in. Redirecting...';
											window.location.href = '<?php echo site_url('home/redirurl') ?>';
											reloadRelatedOtherPages();
										}
										else{
											$( '#warnmsg' ).show();
											document.getElementById( 'warnmsg' ).className = 'alert alert-danger';
											document.getElementById( 'warnmsg-text' ).innerHTML = '<span class="glyphicon glyphicon-remove-sign"></span>&nbsp;&nbsp;Invalid user Username/Password.';
											if( result == 1 ){
												document.getElementById( 'warnmsg-text' ).innerHTML = '<span class="glyphicon glyphicon-remove-sign"></span>&nbsp;&nbsp;Invalid user Username/Password.';
												document.getElementById( 'password' ).value = '';
											}
											else if( result == 2 ){
												document.getElementById( 'warnmsg-text' ).innerHTML = '<span class="glyphicon glyphicon-remove-sign"></span>&nbsp;&nbsp;You don\'t have access to at least 1 module. Please contact your System Administrator.';
												document.getElementById( 'password' ).value = '';
											}
											else if( result == 3 ){
												document.getElementById( 'warnmsg-text' ).innerHTML = '<span class="glyphicon glyphicon-remove-sign"></span>&nbsp;&nbsp;You are not allowed with this Affiliate. Please contact your System Administrator.';
												document.getElementById( 'password' ).value = '';
											}
											else if( result == 4 ){
												document.getElementById( 'warnmsg-text' ).innerHTML = '<span class="glyphicon glyphicon-remove-sign"></span>&nbsp;&nbsp;User is inactive. Please contact your System Administrator.';
												document.getElementById( 'password' ).value = '';
											}
										}
									}
									,error	: function(){
										$( '#warnmsg' ).show();
										document.getElementById( 'warnmsg' ).className = 'alert alert-danger';
										document.getElementById( 'warnmsg-text' ).innerHTML = '<span class="glyphicon glyphicon-remove-sign"></span>&nbsp;&nbsp;Database connectivity error. Please make sure you are connected to the network and try again.';
									}
								} );
							} else {
								if( counter > 1 ){
									$( '#warnmsg' ).show();
									document.getElementById( 'warnmsg' ).className = 'alert alert-danger';
									document.getElementById( 'warnmsg-text' ).innerHTML = '<span class="glyphicon glyphicon-remove-sign"></span>&nbsp;&nbsp;Affiliate is required.';
								}
							}

						} else {
							$( '#warnmsg' ).show();
							document.getElementById( 'warnmsg' ).className = 'alert alert-danger';
							document.getElementById( 'warnmsg-text' ).innerHTML = '<span class="glyphicon glyphicon-remove-sign"></span>&nbsp;&nbsp;Invalid user Username/Password.';
						}
					}
				});

				counter++;
			} );
			
			$( "#username" ).keypress( function( e ){
				$( '#warnmsg' ).hide();
				evalidate( e.which );
			} );
			
			$( "#password" ).keypress( function( e ){
				$( '#warnmsg' ).hide();
				evalidate( e.which );
			} );

			function evalidate( e ){
				switch( e ){
					case 13 :
						$( "#login_btn" ).click();
						break;
				}
			}
		} );
		
		/*	function para dili maka multiple login sa isa ka unit
			addlocalStorageOpenedBrowser,
			removeEmptyArray,
			checkIFwindowExists,
			findMaxIndex,
			reloadRelatedOtherPages
			
			note: naa pod ni sa mainView.js
		*/
		
		function addlocalStorageOpenedBrowser(){
			removeEmptyArray();
			var list = JSON.parse( localStorage.getItem( 'openedWindows_' + baseurl ) ) || [];
			var index = findMaxIndex();
			var added = false;
			
			if( !window.name ){
				var windowName = baseurl+index;
				window.name = windowName;
				added = true;
			}
			var hasDup = false;
			list.forEach( function( data, x ){
				if( data != null && data.window_name == window.name ){
					hasDup = true;
				}
			} );
			
			if( !hasDup && added ){
				list.push( {
					'window_name' : windowName,
					'index'		  : index
				} );
				localStorage.setItem( 'openedWindows_' + baseurl,JSON.stringify( list ) );
			}
			else{
				if( !checkIFwindowExists() ){
					var str = String( window.name ).split( '/' );
					list.push( {
						'window_name' : window.name,
						'index'		  : str[str.length - 1]
					} );
					localStorage.setItem( 'openedWindows_' + baseurl,JSON.stringify( list ) );
				}
			}
		}
		
		function removeEmptyArray(){
			var list	= JSON.parse( localStorage.getItem( 'openedWindows_' + baseurl ) ) || [];
			var newList	= new Array();
			list.forEach( function( data, x ){
				if( data != null ){
					newList.push( data );
				}
			} );
			localStorage.setItem( 'openedWindows_' + baseurl,JSON.stringify( newList ) );
		}
		
		function checkIFwindowExists(){
			var list	= JSON.parse( localStorage.getItem( 'openedWindows_' + baseurl ) ) || [];
			var exists	= false;
			list.forEach( function( data, x ){
				if( data != null && data.window_name == window.name ){
					exists = true;
				}
			} );
			return exists;
		}
		
		function findMaxIndex(){
			var list	= JSON.parse( localStorage.getItem( 'openedWindows_' + baseurl ) ) || [];
			var max		= 1;
			if( list.length == 0 ){
				return max;
			}
			else{
				list.forEach( function( data, x ){
					if( data != null && data.window_name && data.index > 0 ){
						if( data.index > max ){
							max = data.index;
						}
					}
				} );
				return max+1;
			}
		}
		
		function reloadRelatedOtherPages(){
			var list = JSON.parse( localStorage.getItem( 'openedWindows_' + baseurl ) ) || [];
			list.forEach( function( data, x ){
				if( data.window_name != window.name ){
					var win = window.open( '', data.window_name );
					if( win ){
						if( win.location.host ){
							window.open( baseurl + "mainview", data.window_name, '', false );
						}
						else{
							win.close();
						}
					}
				}
			} );
		}
		jQuery("body div.input-group select.form-control option").css('color', '#428BCA')
		jQuery("body div.input-group select.form-control").change(function(){   
		jQuery(this).css('color', '#428BCA')
});

	</script>
  
</html>