var mainView = function(){
	var _baseurl, _userName, _logo, _companyName,  _sysTime, _logoPath, _userID, _affiliateName, _affiliateID , _affiliateTagLine, _config, _hasMainAffiliate, _version, _isGae;
	var loadModulesProgressBar, loadStandardProgressBar;
	var loadModulesProgressWin, loadStandardProcessWin;
	var loadModulesProgressCount = 0, loadStandardProgressCount = 0;
	
	var exts 		  = '_mainView';
	var lock = true;
	var menuTypeNames = [
		'Dashboard'
		,'Construction'
		,'Trucking'
		,'Payroll'
		,'Inventory'
		,'Accounting'
		,'General Reports'
		,'General Settings'
		,'Admin'
		
	];
	var subMenu = [
		'Transactions'
		,'Reports'
		,'Settings'
		,'Modules'
	];
	var inventorySubMenu = [
		'Purchase Order'
		,'Receiving'
		,'Releasing'
		,'Inventory'
		,'Settings'
	]
	var menuArray 	  = new Array(menuTypeNames.length);
	var adjustmentText = 'For Approval';
	
	function init( params ){
		var date = new Date();
		var sec  = date.getUTCSeconds();
		var min  = date.getUTCMinutes();
		var hur  = date.getUTCHours();
		var day  = date.getUTCDay();
		var mon  = date.getUTCMonth();
		var yer  = date.getUTCFullYear().toString();
		var initHeader = sec + '' + mon + '' + hur + '' + yer.substring( yer.length - 1, yer.length ) + '' + min + '' + day;
		
		var mask = new Ext.LoadMask( Ext.getBody(), { msg : "Initializing modules. Please wait..." } );
		mask.show();
		Ext.Ajax.request( {
			method		: 'post'
			,url		: params.baseurl + 'mainview/initializeAndLoadModules'
			,headers	: {
				initHeader : initHeader
			}
			,success	: function( response ){
				mask.destroy();
				
				var result      	= Ext.decode( response.responseText );
				var initVar			= result.initVar;
				var modules			= result.modules;
				var standardFiles	= result.standardFiles;
				
				_baseurl 			= params.baseurl;
				_userName  			= initVar.USERNAME;
				if( initVar.imageBin != '' ){
					_logo 			= initVar.imageBin;
				}
				else{
					_logo  			= initVar.LOGOPATH + '' + initVar.COMPLOGO;
				}
				_companyName  		= initVar.COMPANYNAME;
				_sysTime  			= initVar.SYSTIME;
				_userID  			= initVar.USERID;
				_EMPLOYEEID			= initVar.EMPLOYEEID;
				_affiliateName 		= initVar.AFFILIATENAME;
				_affiliateTagLine	= initVar.AFFILIATETAGLINE;
				_hasMainAffiliate	= initVar.hasMainAffiliate;
				_version 			= initVar.version;
				_config				= initVar;
				_isGae				= initVar.isGae;
				_affiliateID		= initVar.AFFILIATEID;
				
				_config['baseurl'] 				= _baseurl;
				_config['initHeader'] 			= initHeader;
				_config['imageBin']				= initVar.imageBin;
				_config['DEFAULT_IMAGE_BIN']	= initVar.DEFAULT_IMAGE_BIN;
				_config['isGae']				= initVar.isGae;
				
				if( typeof initVar.initHeader != 'undefined' ){
					_config['initHeader'] = initVar.initHeader;
				}
				
				loadModulesProgressBar = Ext.create( 'Ext.ProgressBar', {
					text:'Loading...'
					,width:300
				} );
				
				loadModulesProgressWin = Ext.create( 'Ext.window.Window', {
					title		: 'Loading Modules...'
					,closable	: false
					,resizable	: false
					,draggable	: false
					,items		: [
						loadModulesProgressBar
					]
				} );

				loadStandardProgressBar = Ext.create( 'Ext.ProgressBar', {
					text:'Loading...'
					,width:300
				} );

				loadStandardProcessWin = Ext.create( 'Ext.window.Window', {
					title		: 'Loading Initialization files...'
					,closable	: false
					,resizable	: false
					,draggable	: false
					,items		: [
						loadStandardProgressBar
					]
				} );
				loadStandardProcessWin.show();

				if( typeof params.changing != 'undefined' ) loadStandardProcessWin.destroy();
				
				for( var x = 0; x < menuArray.length; x++ ){
					menuArray[x] = new Array();
				}
				
				extLoaderCallBack( {
					modules			: modules
					,standardFiles	: standardFiles
				} ); 
			}
			,failure	: function(){
				console.log('Failure');
				mask.destroy();
				
				standards.callFunction( '_createMessageBox', {
					icon	: 'error'
					,msg	: 'Modules not loaded, Please make sure that you are connected with the network.'	
				} );
			}
		} );
	}
	
	function extLoaderCallBack( params ){
		if( loadStandardProgressCount < params.standardFiles.length ){
			extLoader( params.standardFiles[ loadStandardProgressCount ], true, params.standardFiles.length, function(){
				loadStandardProgressCount++;
				loadStandardProgressBar.updateProgress( loadStandardProgressCount / params.standardFiles.length, 'Loading ' + loadStandardProgressCount + ' of ' + params.standardFiles.length );
				if( loadStandardProgressCount == ( params.standardFiles.length - 1 ) ){
					loadStandardProcessWin.destroy( true );
					loadModulesProgressWin.show();
				}
				extLoaderCallBack( {
					modules			: params.modules
					,standardFiles	: params.standardFiles
				} );
			}, true, params )
		}
		else{
			for( var x = loadModulesProgressCount; x<params.modules.length; x++ ){
				extLoader( params.modules[x], false, params.modules.length );
			}
		}
	}
	
	function extLoader( module, async, length, callback, originalParams ){
		var onLoad;
		
		if( async ){
			onLoad = callback;
		}
		else{
			onLoad = function(){
				if( loadModulesProgressCount == length - 1 ){
					overrides.applied( _config );
					createViewport();
					loadModulesProgressWin.destroy( true );
					Ext.resumeLayouts( true );
				}
				
				loadModulesProgressCount++;
				loadModulesProgressBar.updateProgress( loadModulesProgressCount / length, 'Loading ' + loadModulesProgressCount + ' of ' + length );
			}
			
			/** stores loaded modules into array. **/
			if( typeof module.moduleType != 'undefined' ){
				menuArray[ parseInt( module.moduleType, 10 ) ].push( module );
			}
			
		}
		
		if( module  ){
			Ext.Loader.loadScript( {
				url					: _baseurl + 'js/' + module.moduleLink
				,scriptChainDelay	: true
				,scope				: this
				,onLoad				: onLoad
				,onError			: function(){
					loadModulesProgressWin.destroy( true );
					
					/** cannot call stanrdards yet. **/
					Ext.MessageBox.show( {
						icon			: Ext.MessageBox.INFO
						,buttons		: Ext.MessageBox.OK
						,title			: 'SYSTEM MESSAGE'
						,msg			: 'Some js files are not loaded correctly, system will logout, please try to login again.'	
						,closeAction	: 'destroy'
						,fn				: function( btn ){
							window.location.href = _baseurl+'home/logout/1'
						}
					} );
				}
			} );
		}
		else{
			createViewport();
			loadModulesProgressWin.destroy( true );
			Ext.resumeLayouts( true );
		}
	}
	
	function createViewport(){
		if( _hasMainAffiliate == 0 ){
			var accschedStore = standards.callFunction( '_createLocalStore', {
				data:[
					'Calendar Year'
					,'Fiscal Year'
				]
			} );
			var winModule = 'initialCompanySetup' + exts;
			Ext.create( 'Ext.Window', {
				id			: 'win_' + winModule
				,title		: 'Initial Company Setup'
				,modal		: true
				,resizable	: false
				,frame		: true
				,draggable	: false
				,defaults	: {
					anchor	: '100%'
				}
				,bodyStyle	: {
					"background-color"	: "#fff"
					,"padding"			: "5px"
				}
				,items		: [
					standards.callFunction( '_formPanel', {
						moduleType			: 'form'
						,module				: winModule
						,formButtonAlign	: 'left'
						,formButtons		: [
							{	text		: 'Save'
								,formBind	: true
								,iconCls	: 'glyphicon glyphicon-floppy-disk'
								,handler	: function(){
									var form = Ext.getCmp( 'mainFormPanel' + winModule ).getForm();
									form.submit( {
										url			:  _baseurl + 'home/saveInitialRecords'
										,success	: function( action, response ){
											var match = parseInt( response.result.match, 10 );
											standards.callFunction( '_createMessageBox', {
												msg : 'System successfully configured. Please click the "OK" to reload.'
												,fn : function(){
													window.location.reload();
												}
											} );
										}
									} );
								}
							}
							,{	text		: 'Reset'
								,iconCls	: 'glyphicon glyphicon-refresh'
								,id			: 'btnReset' + winModule
								,handler	: function(){ 
									Ext.getCmp( 'mainFormPanel' + winModule ).getForm().reset();
									Ext.getCmp( 'accountingsched' + winModule ).fireEvent( 'select' );
								}
							}
						]
						,formItems : [
							{	xtype	: 'container'
								,layout	: 'column'
								,style	: 'margin-bottom:4px;'
								,items	: [
									standards.callFunction( '_createTextField' ,{
										id			: 'affiliateName' + winModule
										,fieldLabel	: 'Affiliate Name'
										,allowBlank	: false
										,width		: 442
										,maxLength	: 80
										,style		: 'margin-right: 5px;'
									} )
									,standards.callFunction( '_createCheckField' ,{
										id			: 'maintag' + winModule
										,readOnly	: true
										,width		: 107
										,boxLabel	: "<span style='color: red;'>( Main Affiliate )</span>"
										,checked	: true
									} )
								]
							}
							,standards.callFunction( '_createTextArea' ,{
								id			: 'address' + winModule
								,fieldLabel	: 'Address'
								,width		: 442
							} )
							,standards.callFunction( '_createTextField' ,{
								id			: 'tin' + winModule
								,fieldLabel	: 'TIN'
								,allowBlank	: false
								,width		: 442
								,maxLength	: 50
								,style		: 'margin-right: 5px;'
							} )
							,{	xtype		: 'container'
								,layout		: 'column'
								,style		: 'margin-bottom:4px;'
								,items		: [
									standards.callFunction( '_createCombo', {
										id			: 'accountingsched' + winModule
										,fieldLabel	: 'Accounting Schedule'
										,module		: winModule
										,store 		: accschedStore
										,editable	: false
										,allowBlank	: false
										,value		: 1
										,width		: 300
										,style		: 'margin-right:5px;'
										,listeners	: {
											select	: function(){
												var record = this.findRecord( this.valueField , this.getValue() );
												var index = this.store.indexOf( record );
												record = this.store.getAt( index ).data;

												if( record.id == 1 ){
													Ext.getCmp( 'month' + winModule ).setValue( 12 );
													Ext.getCmp( 'month' + winModule ).setReadOnly( true );
												}
												else{
													Ext.getCmp( 'month' + winModule ).reset();
													Ext.getCmp( 'month' + winModule ).setReadOnly( false );
												}
											}
										}
									} )
									,standards.callFunction( '_cmbMonth', {
										width			: 137
										,module			: winModule
										,labelWidth		: 0
										,fieldLabel		: ''
										,listeners		: {
											afterrender	: function(){
												Ext.getCmp( 'accountingsched' + winModule ).fireEvent( 'select' );
											}
											,select		: function(){
												var record = this.findRecord( this.valueField , this.getValue() )
													,index = this.store.indexOf( record );
												record = this.store.getAt( index ).data;
												
												if( record.id == 12 ){
													Ext.getCmp( 'accountingsched' + winModule ).setValue( 1 );
													this.setReadOnly( true );
												}
											}
										}
									} )
								]
							}
							,standards.callFunction( '_createDateField' , {
								id				: 'datestart' + winModule
								,fieldLabel		: 'Date Start'
								,allowBlank		: false
								,value			: null
								,width			: 442
							} )
							,standards.callFunction( '_createTextField' ,{
								id				: 'version' + winModule
								,fieldLabel		: 'Version'
								,width			: 442
								,readOnly		: true
								,submitValue	: false
								,fieldStyle		: "text-align:right;"
								,value			: _version
							} )
						]
					})
				]
				,listeners	: {
					close	: function(){
						window.location.href = _baseurl + 'home/logout/1'
					}
					,show	: function(){
						window.onresize = function(){ 
							Ext.getCmp( 'win_' + winModule ).center();
						}
					}
				}
			} ).show();
		}
		else{
			
			doCreateViewPort();
		}
		
		function doCreateViewPort(){
			Ext.suspendLayouts();
			try{
				return Ext.create( 'Ext.Viewport', {
					layout	: 'border'
					,items	: [
						{	region	:'north'
							,border	: false
							,items	: [
								{	xtype		: 'box'
									,html		: headerhtml()
									,listeners	: {
										afterrender	:function(){
											menuFunctionality();
										}
									}
								}
							]
						}
						,{	region	: 'north'
							,border	: false
							,items	: [
								{	xtype	: 'box'
									,html	: menuHeader()
								}
							]
						}
						,{	region			: 'west'
							,id				: 'accordion'+exts
							,width			: 210
							,collapsible	: true
							,overflowY		: 'auto'
							,minWidth		: 100
							,maxWidth		: 210
							,layout			: 'fit'
							,items			: [
								moduleFunctionality()
							]
						}
						,{	region		: 'center'
							,xtype		: 'tabpanel'
							,id			: 'mainTabPanel' + exts
							,layout		: 'fit'
							,border		: false
							,listeners	: {
								afterrender : function(){
									this.tabBar.setHeight( 25 );
								}
							}
							,items		: []
						}
					]
					,listeners	: {
						afterrender : function(){
							fncRunTask();
							setDashboardModule();
							addlocalStorageOpenedBrowser();
						}
					}
				} );
			}
			catch( err ){
				console.warn( err );
				// Ext.Ajax.request( {
				// 	url		: _baseurl + 'home/logout/1'
				// 	,method	:'post'
				// 	,noMask	:true
				// } );
			}
		}
	}
	
	function headerhtml(){
		var initTime = new Date( _sysTime );
		var runners  = new Ext.util.TaskRunner();
		runners.start( {
			interval	: 30000
			,run		: function() {
				if( initTime ){
					initTime	= new Date( initTime.valueOf() + 30000 );
					if( document.getElementById( 'systemTime' ) ){
						document.getElementById( 'systemTime' ).innerHTML = Ext.Date.format( initTime,'F j, Y g:i A' );
					}
				}
			}
		} );
		
		return '<table width="100%" height="70px" style="color:#22313F;min-width:1030px;">' +
					'<tr>' +
						'<td style="width:50%">' +
							'<table>' +
								'<tr>' +
									'<td><img style="margin:5px; height: 60px; width: 250px;" id="logo" class="clearfix" src="' + _logo + '" /></td>' +
									'<td><table style="height:60px;">' +
										'<tr height="10%"><td style="font-size:14px;font-weight:bold;vertical-align:top;" id="affHeaderName">' + _affiliateName + '</td></tr>' +
										'<tr height="90%"><td style="font-style:italic;font-size:12px;vertical-align:top;" id="affHeaderTagLine">' + ( _affiliateTagLine || '' ) + '</td></tr>' +
									'</table></td>' +
								'</tr>' +
							'</table>' +
						'</td>' +
						'<td valign="bottom" style="width:50%">' +
							'<table style="float:right;">' +
								'<tr>' +
									'<td valign="bottom" >' +
										'<div style="text-align:right;margin-right:15px;margin-bottom:5px;" id="clock"><p class="user" id="clockdate"><em style="font-size:14px"> '+
										'<span class="cirlce glyphicon glyphicon-user" style="margin-right:5px;"></span><span style="margin-right:10px;">' + _userName + '</span>' +
										'<span class="cirlce glyphicon glyphicon-home" style="margin-right:5px;"></span><span style="margin-right:10px;" id="affDropDown' + exts + '"></span>' +
										'<span class="cirlce glyphicon glyphicon-calendar" style="margin-right:5px;"></span><em class="date-box" id="systemTime">' + Ext.Date.format( initTime, 'F j, Y g:i A' ) + '</em></em> ' +
										'<span class="cirlce" style="margin-left:5px;margin-right:5px;font-weight:bold;font-size:12px;">?</span><span style="margin-right:10px;"><a style="text-decoration:underline;" href="' + _baseurl + 'css/manual/Welcome.html" frameborder="0" width="100%" target="_blank">Help</a></span>' +
									'</td>' +
								'</tr>' +
							'</table>' +
						'</td>' +
					'</tr>' +
			   '</table>';
	}
	
	function menuHeader(){
		var menuItems	= '<ul class="menu-1">';
			menuTypeNames.forEach( function( item, index ){
				if( index > 0 ){
					cls = '';
					if( index == 1 ){
						cls = 'class="visited"';
					}
					menuItems += '<li><a ' + cls + ' onclick="mainView.setActivity(' + index + ')" id="' + item.replace(" ","_") + exts + '" >' + item + '</a></li>';
				}
			} );
		menuItems += '</ul>'
		
		return '<div id="navigation">' +
					menuItems + 
					'<ul class="menu-2">' +
						'<li id="adjustment' + exts + '">' +
						'</li>' +
						'<li id="reorderNotif' + exts + '">' +
						'</li>' +
						'<li id="viewAdjustmentsNotif' + exts + '">' +
						'</li>' +
						'<li id="logoutBox" class="last">' + '<a id="logout_menusLink" onclick="mainView.removeLocalStorageOpenedBrowser()" href="' + _baseurl + 'home/logout/1">Logout</a>' +
						'</li>' +
					'</ul>' +
				'</div>'; 
	}
	
	function menuFunctionality(){
		/** adjustment header **/
		var affiliateHeaderMenu	= {
				xtype		: 'menu'
				,plain		: true
				,maxHeight	: 300
				,items		: []
		};
		Ext.create( 'Ext.Button', {
			text		: '<span role="img" class="glyphicon glyphicon-refresh " unselectable="on" style="color: #FFF !important">&nbsp;</span>'
			,renderTo	: 'viewAdjustmentsNotif' + exts
			,id			: 'viewAdjustmentsNotif' + exts
			,cls		: 'comboDepartment'
			,style		: "border: none; width: 25px"
			,listeners	: {
				click	: function( me, a ){
					checkAdjustmentConfirm();
				}
			}
		} );

		Ext.create( 'Ext.Button', {
			text		: 'For Reorder'
			,renderTo	: 'reorderNotif' + exts
			,id			: 'reorderNotif' + exts
			,cls		: 'adjustment'
			,style		: "border: 0px; margin-top:0px;"
			,menu		: affiliateHeaderMenu
			,listeners	: {
				click	: function( me, a ){
					me.menu.removeAll();
					Ext.Ajax.request( {
						url			: _baseurl + 'home/getItemReorderLevel'
						,async		: false
						,success	: function( response ){
							var ret			= Ext.decode( response.responseText )
								,hasData	= 0;
							if( ret.data.length > 0 ){
								ret.data.map( data => {
									me.menu.add( {
										text		: ( data.itemName.length > 20 ) ? data.itemName.substring(0,20) + '...' : data.itemName
										,icon		: _baseurl + 'images/icons/small-icon/module_bullet.png'
										,data		: data
										// ,handler	: function(){}
										,handler	: returnHandlerAdjustment( data, me ) //ADDED BY CHRISTIAN
									} );

								});

								hasData++;
							}
							
							if( hasData > 0 ){
								me.menu.showBy( me );
							}
							else{
								me.menu.add( {
									text		: '<span style="font-style:italic;color:gray;">No items for reorder</span>'
									,icon		: _baseurl + 'images/icons/small-icon/module_bullet.png'
									,handler	: function(){}
								} );
									
								me.menu.showBy( me );
							}
						}
					}); 
				}
			}
		} );
		
		Ext.create( 'Ext.Button', {
			text		: adjustmentText
			,cls		: 'adjustment'
			,style		: 'border: 0px; margin-top:0px;'
			,renderTo	: 'adjustment' + exts
			,id			: 'adjDropDownButton' + exts
			,menu		: affiliateHeaderMenu
			,listeners	: {
				click	: function( me, a ){
					me.menu.removeAll();
					Ext.Ajax.request( {
						url			: _baseurl + 'home/getAdjustments'
						,async		: false
						,success	: function( response ){
							var ret			= Ext.decode( response.responseText )
								,hasData	= 0;

							for( var x = 0; x < ret.data.length; x++ ){
								var name	= ret.data[x].reference
									,id		= ret.data[x].idInvoice
									,idModule = ret.data[x].idModule;
								
								me.menu.add( {
									text		: name
									,icon		: _baseurl + 'images/icons/small-icon/module_bullet.png'
									,handler	: returnHandlerAdjustment( ret.data[x], me )
								} );
								
								hasData++;
							}
							
							if( hasData > 0 ){
								me.menu.showBy( me );
							}
							else{
								me.menu.add( {
									text		: '<span style="font-style:italic;color:gray;">No adjustment to confirm</span>'
									,icon		: _baseurl + 'images/icons/small-icon/module_bullet.png'
									,handler	: function(){}
								} );
									
								me.menu.showBy( me );
							}
						}
					}); 
				}
			}
		});
		
		function returnHandlerAdjustment( data, me ){
			return function(){
				mainView.openModule( data.idModule, data, me); 
				// mainView.gotoTrans( id, 0, 1 );
			}
		}
		
		/** affiliate Header **/
		var affiliateHeaderMenu = {
				xtype		: 'menu'
				,plain		: true
				,maxHeight	: 300
				,items		: []
		};
							
		Ext.create( 'Ext.Button', {
			text		: _affiliateName
			,cls		: 'comboDepartment'
			,style		: 'border: 0px; font-family: inherit;'
			,renderTo	: 'affDropDown' + exts
			,id			: 'affDropDownButton' + exts
			,menu		: affiliateHeaderMenu
			,listeners	: {
				afterrender	: function(){
					/** if sub affiliate then add class**/
					// if( Ext.getConstant( 'ISMAIN' ) == 0 ){
					// 	this.el.dom.className += ' comboSubMain';
					// }
				}
				,click		: function( me, a ){
					// if( Ext.getConstant( 'ISMAIN' ) == 1 ){}
					me.menu.removeAll();
					Ext.Ajax.request( {
						url			: _baseurl + 'home/getAffiliate'
						,params		: { idEmployee : _EMPLOYEEID }
						,async		: false
						,success	: function(response){
							var ret = Ext.decode( response.responseText );
							var hasData = 0;
							for( var x = 0; x < ret.data.length; x++ ){
								var name = ret.data[x].affiliateName;
								var id = ret.data[x].idAffiliate;
								
								me.menu.add( {
									text		: name
									,icon		: _baseurl + 'images/icons/small-icon/module_bullet.png'
									,handler	: returnHandler( name, id )
								} );
								
								hasData++;
							}
							
							if( hasData > 0 ){
								me.menu.showBy( me );
							}
						}
					} );
				}
			}
		} );
		
		function returnHandler( affiliateName, idAffiliate ){
			return function(){
				myMask	= new Ext.LoadMask( Ext.getBody(), { msg	: "Checking selected affiliate. Please wait..." } );
				myMask.show(); 
				Ext.Ajax.request({
					url			: _baseurl + 'home/checkAffiliate'
					,params		: {
						idAffiliate	: idAffiliate
						,idEmployee	: _EMPLOYEEID
					}
					,success	: function( response ){
						var resp	= Ext.decode( response.responseText )
							,ret	= resp.view;

						myMask.destroy();

						if( ret.ok == 1 ){
							myMask = new Ext.LoadMask( Ext.getBody(), { msg	: "Changing affiliate. Please wait..." } );
							myMask.show(); 
							setTimeout( function(){
								myMask.destroy(); 
								changeAffiliate( idAffiliate );
								// window.location.href = _baseurl + 'home/changeAffiliate/' + idAffiliate;
							}, 1000 );
						} else {
							standards.callFunction( '_createMessageBox', {
								icon	: 'error'
								,msg	: 'Affiliate does not exist. Please select another affiliate.'	
							} );
						}
					}
				} );
			}
		}
	}

	function changeAffiliate( id ){
		return Ext.Ajax.request({
			url	: _baseurl + 'home/changeAffiliate'
			,params	: {
				idAffiliate : id
			}
			,success: function( response ){
				window.location.href = _baseurl;
			}
		});
	}
	
	function setMenuActivity(index){
		var appendIcon = '<img style="width: 13px;margin-right:5px;margin-top:-4px;" src="' + _baseurl + 'images/icons/small-icon/module_bullet.png">';
		Ext.suspendLayouts();
			var _this	= Ext.getCmp( 'supermenu' + exts )
				,root	= _this.getStore().getRootNode();
			root.removeAll();
			
			/** prevent dashboard & task to be shown in module tab **/
			if( index > 0){
				if( menuArray[index].length > 0 ){
					if( index == 4 ){
						inventorySubMenu.forEach( function( sub, isub ){
							var hasItems 	= 0
								,subRoot	= root.insertChild( isub, { text	: '<b>' + sub + '</b>', cls	: 'x-tree-main', expanded	: true, expandable	: false } );
							menuArray[index].forEach( function( dataSub, inx ){
								if( isub == dataSub.moduleSub ){
									var raw = subRoot.insertChild( inx, {
										text	: appendIcon + dataSub.moduleName
										,leaf	: true
										,id		: dataSub.idModule
										,cls	: 'x-tree-noicon'
									} );
									raw.raw.data = dataSub;
									hasItems++;
								}
							} );
							if( hasItems == 0 ){
								subRoot.destroy();
							}
						} )
					}
					else{
						subMenu.forEach( function( sub, isub ){
							var hasItems	= 0
								,subRoot	= root.insertChild( isub, { text	: '<b>' + sub + '</b>', cls	: 'x-tree-main', expanded	: true, expandable	: false } );
							menuArray[index].forEach( function( dataSub, inx ){
								if( isub == dataSub.moduleSub ){
									var raw =  subRoot.insertChild( inx, {
										text	: appendIcon + dataSub.moduleName
										,leaf	: true
										,id		: dataSub.idModule
										,cls	: 'x-tree-noicon'
									} );
									raw.raw.data = dataSub;
									hasItems++;
								}
							} );
							if( hasItems == 0 ){
								subRoot.destroy();
							}
						} );
					}
				}
			}
		
			/** remove all class visited **/
			menuTypeNames.forEach( function( item, ix ){
				if( Ext.get( item.replace( " ", "_" ) + exts ) ) Ext.get( item.replace( " ", "_" ) + exts ).el.dom.className = ( index == ix? 'visited' : '' );
			} );
			
		Ext.resumeLayouts( true );
		checkAdjustmentConfirm();
	}

	
	function fncRunTask(){
		/** IMPORTANT NOTE : if there are  newly added background process. added a "preventSession" as string value of the function 
			this will prevent the setting of session.
			and this will help the session to be destroyed if not the system is not active. **/
		
		
		 Ext.TaskManager.start( { 
			run		: function(){ 

				/**
				 * Check if app is expired
				 * Modified:
				 * 	commented by : Jays 06-18-2019
				 * 	reason : not yet used - resulted to 404
				 * */ 
				
				/* Ext.Ajax.request( {
					url : _baseurl + 'home/checkIfExpired/'
					,noMask : true
					,method : 'POST'
					,success : function( response, options ){
						console.warn(response)
						var ret = Ext.decode( response.responseText )
						// console.warn(ret)
						if( parseInt( ret.match ) == 1 )
						{
							
							Ext.create('Ext.window.Window',{
								id:'winPasss'
								,title:'Your subscription has expired.'
								,width:535
								,autoHeight:true
								,modal:true
								,closable:false
								,resizable:false
								,items: [
									
									Ext.widget('form', {
										id:'formPasss'
										,labelWidth:120
										,width: 520
										,border: false
										,frame : true
										,defaults: {
										 anchor: '100%'
										}
										,items:[
										
										   {
												xtype: 'box'
												,html: "<center><b>Your subscription has expired.</b></center> <font size=1><center>Contact the FIFO Support Team to renew your subscription.</center></font>"
												
										   }
										]
										,buttons:[
											{
												text:'Ok'
												,id:'log'
												,formBind:true
												,style: "margin: 0 auto"
												,handler:function(){
													Ext.getCmp( "winPasss" ).close()
													Ext.get( "logout_menusLink" ).el.dom.click()
												}
											}
										]
									})
								]
							}).show();

						}
					}
				}) */

				Ext.Ajax.request( {
					url					: _baseurl + 'home/checkIfLogin/preventSession'
					,noMask				: true
					,method				: 'post'
					,promptOnFaluire	: false
					,success			: function( response, options ){
						var winModule = 'initialCompanySetup' + exts;
						if( parseInt( response.responseText, 10 ) == 0  && lock ){
							lock = false;

							Ext.create( 'Ext.window.Window', {
								id			: 'winPass' + winModule
								,title		: 'Supervisor Approval Code'
								,width		: 535
								,autoHeight	: true
								,modal		: true
								,closable	: false
								,resizable	: false
								,items		: [
									Ext.widget( 'form', {
										id			: 'formPass' + winModule
										,labelWidth	: 120
										,width		: 520
										,border		: false
										,frame		: true
										,defaults	: {
											anchor	: '100%'
										}
										,items		: [
											{	xtype			: 'form'
												,border			: false
												,buttonAlign	: 'right'
												,bodyPadding	: 5
												,items			: [
													{	xtype	: 'box'
														,html	: "Please re-enter your password."
													}
													,{	xtype				: 'textfield'
														,fieldLabel			: 'Username'
														,name				: 'username'
														,id					: 'username'
														,anchor				: '100%'
														,inputType			: 'text'
														,labelWidth			: 115
														,maxLength			: 50
														,enforceMaxLength	: true
														,emptyText			: ''
														,msgTarget			: 'under'
														,value				: _userName
														,readOnly			: true
													}
													,{	xtype				: 'textfield'
														,fieldLabel			: 'Password'
														,name				: 'password'
														,id					: 'password'
														,anchor				: '100%'
														,inputType			: 'password'
														,labelWidth			: 115
														,maxLength			: 50
														,enforceMaxLength	: true
														,emptyText			: ''
														,msgTarget			: 'under'
													}
												]
												,buttons:[
													{	text		: 'Login'
														,id			: 'uUpdatedBtn' + winModule
														,formBind	: true
														,handler	: function(){
															Ext.getCmp( 'formPass' + winModule ).getForm().submit( {
																waitTitle	: "Please wait"
																,waitMsg	: "Submitting data..."
																,url		: _baseurl + 'home/loginUser'
																,method		: "POST"
																,success	: function ( response, option ){
																	var ret = Ext.decode( option.response.responseText );
																	if( ret.trigger == 0 ){
																		window.close();
																		lock	= true;
																		Ext.getCmp( 'formPass' + winModule ).getForm().reset();
																		Ext.getCmp( 'winPass' + winModule ).close();
																	}
																	else{
																		standards.callFunction( '_createMessageBox', {
																			msg	: 'Incorrect Password.'
																		} );
																	}
																}
																,failure	: function(brokerForm, action ){
																	standards.callFunction( '_createMessageBox', {
																		msg	: 'Network Error! Please try again later.'
																	} );
																}
															} );
														}
													}
													,{	text		: 'Logout'
														,id			: 'logout' + winModule
														,handler	: function(){
															Ext.Ajax.request( {
																url			: _baseurl + 'home/logout/1'
																,noMask		: true
																,success	: function( response, options ){
																	document.location.href  = _baseurl;
																}
															} );
														}
													}
												]
											}
										]
									} )
								]
							} ).show()
						}
					}
				} );
				
			}
			,interval	:60000
		} );
	}
	
	function checkAdjustmentConfirm(){
		Ext.Ajax.request( {
			url					: _baseurl + 'home/checkAdjustmentConfirm/preventSession'
			,noMask				: true
			,method				: 'post'
			,async				: true
			,promptOnFaluire	: false
			,success			: function( response, options ){
				var resp = Ext.decode( response.responseText );
				
				if( resp.cnt > 0 ){
					var text	= adjustmentText + ' <span style="background-color: #e74c3c;border-radius: 100%;padding: 0px 5px;color:#FFF;">' + Ext.util.Format.number( resp.cnt, '0,000' ) + '</span>';
					Ext.getCmp( 'adjDropDownButton' + exts ).setText( text );
				}
				else{
					Ext.getCmp( 'adjDropDownButton' + exts ).setText( adjustmentText );
				}

				if( resp.reorderCnt > 0 ){
					var text	= 'For Reorder <span style="background-color: #e74c3c;border-radius: 100%;padding: 0px 5px;color:#FFF;">' + Ext.util.Format.number( resp.reorderCnt, '0,000' ) + '</span>';
					Ext.getCmp( 'reorderNotif' + exts ).setText( text );
				} else {
					Ext.getCmp( 'reorderNotif' + exts ).setText( 'For Reorder' );
				}
			}
		} );
	}
	
	function moduleFunctionality(){
		var store	= Ext.create( 'Ext.data.TreeStore', {} );
		return Ext.create( 'Ext.tree.Panel', {
			id				: 'supermenu'+exts
			,cls			: 'supermenu'+exts
			,rootVisible	: false
			,lines			: false
			,store			: store
			,border			: false
			,listeners		: {
				afterrender	: function(){
					/** receiving default navigation view **/
					setMenuActivity( 1 );
				}
				,itemclick	: function( _this, record, el, index ) {
					
					// var recordValuesHolder = Ext.Object.getValues(record);
					// console.log(recordValuesHolder);
					
					checkAdjustmentConfirm();
					if( record.data.leaf == false ) return false;
					if( typeof record.raw.data == 'undefined' ) return false;
					
					addModule( {
						data	: record.raw.data							
					} );
				}
			}
		} );
	}
	
	function setDashboardModule(){
		/** dashboard **/
		var data = menuArray[0][0];
		
		if( data ){
			addModule( {
				data	: data
			} );
		}
	}
	
	function addModule( params ){
		var data		= params.data;
		
		var idmodule	= data.idModule;
		var title	 	= data.moduleName;
		var str 	 	= data.moduleLink;
		var slash 	 	= str.indexOf( '/' );
		var period 	 	= str.indexOf( '.' );
		var mlink 	 	= str.substring( slash + 1, period );
		var tabID 	 	= 'mainPanel_' + mlink;
		var mainTab  	= Ext.getCmp( 'mainTabPanel' + exts );
		var module		= '_' + mlink;
		
		var clas		= eval( mlink );
		var clas		= new clas();

		if( mainTab.getComponent( tabID ) ) {
			retrieveDataFromModule( {
				invoiceID		: params.invoiceID || 0
				,bankreconID	: params.bankreconID || 0
				,module			: module
			} );
			mainTab.setActiveTab( tabID );

			notifIdModule = ['16', '23', '48'];
			if(!notifIdModule.includes(data.idModule)) {
				return false;
			}	
		}
		
		Ext.suspendLayouts();
			clas().initMethod( {
				canSave							: !!parseInt( data.amoduleSave, 10 )
				,canEdit						: !!parseInt( data.amoduleEdit, 10 )
				,canDelete						: !!parseInt( data.amoduleDel, 10 )
				,canPrint						: !!parseInt( data.amodulePrint, 10 )
				,canCancel						: !!parseInt( data.amoduleCancel, 10 )
				,hasReceivable					: !!parseInt( data.hasReceivable, 10 )
				,hasPayable 					: !!parseInt( data.hasPayable, 10 )
				,baseurl						: _baseurl
				,idmodule						: idmodule
				,module							: module
				,route							: _baseurl + str.substring( 0, period ) + '/'
				,pageTitle						: title
				,isGae							: _isGae
				,invoiceIDFromOtherTransaction	: (params.invoiceID || params.bankreconID) || 0
				,idAffiliate					: _affiliateID
				,idUserValue					: _userID
				,idEMPLOYEE						: _EMPLOYEEID
				,_userName						: _userName
				,selRec							: ( typeof params.selRec != 'undefined'? params.selRec : null )
				,componentCalling				: ( typeof params.componentCalling != 'undefined'? params.componentCalling : null )
				
			} );
			retrieveDataFromModule( {
				invoiceID		: params.invoiceID || 0
				,bankreconID	: params.bankreconID || 0
				,module			: module
			} );
		Ext.resumeLayouts( true );
	}
	
	function retrieveDataFromModule( params ){
		if( params.invoiceID || params.bankreconID ){
			if( mainListPanel = Ext.getCmp( 'mainListPanel' + params.module ) ){
				if( gridPanel = mainListPanel.down( 'grid' ) ){
					gridPanel.columns.forEach( function( col ){
						if( col.xtype == 'actioncolumn' && col.actionColumnIcon == 'pencil' ){
							
							/** set isOtherTransaction = 1 to identify that this came from other module **/
							if( Ext.getCmp( 'container_referenceID' + params.module ) ){
								Ext.getCmp( 'container_referenceID' + params.module ).isOtherTransaction = 1;
							}
							
							/** added this code to perform changecls standard **/
							if( Ext.getCmp( 'btnForm' + params.module ) ){
								Ext.getCmp( 'btnForm' + params.module ).removeCls( 'menuActive' );
								Ext.getCmp( 'btnForm' + params.module ).cls = 'menuInactive';
							}
							col.actionColumnFunction( {
								invoiceID		: params.invoiceID
								,bankreconID	: params.bankreconID
							} );
						}
					} );
				}
			}
		}
	}
	
	/*	function para dili maka multiple login sa isa ka unit
		addlocalStorageOpenedBrowser,
		removeEmptyArray,
		checkIFwindowExists,
		findMaxIndex,
		removeLocalStorageOpenedBrowser
		
		note: naa pod ni sa login_view.php
	*/
	
	function addlocalStorageOpenedBrowser(){
		removeEmptyArray();
		var list = JSON.parse(localStorage.getItem('openedWindows_'+_baseurl)) || [];
		var index = findMaxIndex();
		var added = false;
		
		if(!window.name){
			var windowName = _baseurl+index;
			window.name = windowName;
			added = true;
		}
		var hasDup = false;
		list.forEach(function(data,x){
			if(data != null && data.window_name == window.name){
				hasDup = true;
			}
		});
		if(!hasDup && added){
			list.push({
				'window_name' : windowName,
				'index'		  : index
			});
			localStorage.setItem('openedWindows_'+_baseurl,JSON.stringify(list));
		}else{
			if(!checkIFwindowExists()){
				var str = String(window.name).split('/');
				list.push({
					'window_name' : window.name,
					'index'		  : str[str.length-1]
				});
				localStorage.setItem('openedWindows_'+_baseurl,JSON.stringify(list));
			}
		}
	}
	
	function removeEmptyArray(){
		var list = JSON.parse(localStorage.getItem('openedWindows_'+_baseurl)) || [];
		var newList = new Array();
		list.forEach(function(data,x){
			if(data != null){
				newList.push(data);
			}
		});
		localStorage.setItem('openedWindows_'+_baseurl,JSON.stringify(newList));
	}
	
	function checkIFwindowExists(){
		var list = JSON.parse(localStorage.getItem('openedWindows_'+_baseurl)) || [];
		var exists = false;
		list.forEach(function(data,x){
			if(data != null && data.window_name == window.name){
				exists = true;
			}
		});
		return exists;
	}
	
	function findMaxIndex(){
		var list = JSON.parse(localStorage.getItem('openedWindows_'+_baseurl)) || [];
		var max = 1;
		if(list.length == 0){
			return max;
		}else{
			list.forEach(function(data,x){
				if(data != null && data.window_name && data.index > 0){
					if(data.index > max){
						max = data.index;
					}
				}
			});
			return max+1;
		}
	}
	
	function removeLocalStorageOpenedBrowser(){
		var list = Ext.decode(localStorage.getItem('openedWindows_'+_baseurl)) || [];
		list.forEach(function(data,x){
			if(data.window_name != window.name){
				var win = window.open('',data.window_name);
				console.log(win);
				if(win.location.host){
					window.open(_baseurl,data.window_name,'',false);
				}else{
					win.close();
				}
			}	
		});
		delete localStorage['openedWindows_'+_baseurl];
	}
	
	function initializeModule( moduleID, selRec, componentCalling ){
		var found = 0;
		menuArray.forEach( function( item, index ){
			item.forEach( function( item2, index2 ){
				if( parseInt( item2['idModule'], 10 ) == parseInt( moduleID, 10 ) ){
					found++;
					addModule( { data: item2, selRec: selRec, componentCalling: componentCalling } );
					return false;
				}
			} )
		} );
		if( found == 0 ) standards.callFunction( '_createMessageBox', {
			msg: 'You do not have access to this module.'
			,icon: 'error'
		} )
	}

	return{
		initMethod: init
		,setActivity:setMenuActivity
		,removeLocalStorageOpenedBrowser:removeLocalStorageOpenedBrowser
		,checkAdjustmentConfirm:checkAdjustmentConfirm
		,openModule:initializeModule
		,gotoTrans :function(iID,bID,fStands){
			if(fStands === 1){
				Ext.Ajax.request({
					url : _baseurl + 'mainview/getTransDetails'
					,msg : 'Redirecting to the specified transaction. please wait...'
					,params : {
						invoiceID : iID
						,bankreconID : bID
					}
					,success : function(response){
						var resp = Ext.decode(response.responseText);
						var data = resp.data;
						
						if(data == null){
							standards.callFunction('_createMessageBox',{
								msg:'The selected record is no longer available. Please select another record.'
							});
						}
						else{
							if(data.moduleType && data.moduleID){
								var modType = menuArray[parseInt(data.moduleType)];
								var moduleData = null;
								modType.forEach(function(mt){
									if(mt.moduleID == data.moduleID){
										moduleData = mt;
									}
								});
								
								if(moduleData != null){
									if(data.affiliateID != Ext.getConstant('AFFILIATEID')){
										standards.callFunction('_createMessageBox',{
											msg:"You can't open this record in this affiliate. Please select another record."   
										});
									}
									else{
										addModule({
											data : moduleData
											,invoiceID : iID
											,bankreconID : bID
										});
									}
								}
								else{
									standards.callFunction('_createMessageBox',{
										msg : 'You dont have access to this module. Please contact your System Administrator.'
									});
								}
							}
							else{
								standards.callFunction('_createMessageBox',{
									msg : 'You dont have access to this module. Please contact your System Administrator.'
								});
							}
						}
					}
				});
			}
		}
	}
}();
