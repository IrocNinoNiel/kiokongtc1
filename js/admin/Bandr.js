/**
 * Developer:	Mark Reynor D. Magriña
 * Module:		Back up and Restore
 * Date:		Nov 25, 2019
 * Continued:	Jayson Dagulo
 * Date:		April 16, 2020
 * Finished: 
 * Description:	
 * DB Tables: 
 * */ 
// function bandr(){
var Bandr = function(){

	return function(){
		var baseurl, route, module, canDelete, pageTitle, idModule;
		
		function _init(){
			Ext.apply( Ext.form.field.VTypes, {
				file		: function( val, field ){
					var fileName = /^.*\.(sql)$/i;
					return fileName.test( val );
				}
				,fileMask	: /[a-z_\.]/i
			} );
			
			Ext.Ajax.request( {
				url			: route + 'getSetting'
				,method		: 'post'
				,success	: function( res ){
					var results = Ext.JSON.decode( res.responseText );
					var view    = results.view;
					
					if( view.length > 0 ){
						var time = new Date ( Ext.Date.format( new Date(), 'Y-m-d' ) + ' ' + view[0].abTime );
						var day  = view[0].abDay;
						if( view[0].abType == 1 ){
							Ext.getCmp( 'abTime' + module ).setReadOnly( false )
							
							Ext.getCmp( 'abWeek' + module ).setReadOnly( true )
							Ext.getCmp( 'abDay' + module ).setReadOnly( true )
							
							Ext.getCmp( 'abWeek' + module ).setValue( null )
							Ext.getCmp( 'abDay' + module ).setValue( null )
						}
						else if( view[0].abType == 2 ){
							Ext.getCmp( 'abTime' + module ).setReadOnly( false )
							Ext.getCmp( 'abDay' + module ).setReadOnly( false )
							
							Ext.getCmp( 'abWeek' + module ).setReadOnly( true )
							
							Ext.getCmp( 'abWeek' + module ).setValue( null )
						}
						else{
							Ext.getCmp( 'abTime' + module ).setReadOnly( false );
							Ext.getCmp( 'abTime' + module ).reset();
							Ext.getCmp( 'abWeek' + module ).setReadOnly( false );
							Ext.getCmp( 'abWeek' + module ).reset();
							Ext.getCmp( 'abDay' + module ).setReadOnly( false );
							Ext.getCmp( 'abDay' + module ).reset();
						}
						
						Ext.getCmp( 'idAB' + module ).setValue( view[0].idAB );
						Ext.getCmp( 'abType' + module ).store.load( {
							callback	: function(){
								Ext.getCmp( 'abTime' + module ).setValue( time );
								Ext.getCmp( 'abDay' + module ).setValue( parseInt( day, 10 ) );
								Ext.getCmp( 'abWeek' + module ).setValue( parseInt( view[0].abWeek, 10 ) );
								Ext.getCmp( 'abType' + module ).setValue( parseInt( view[0].abType, 10 ) );
							}
						} );
					}
					else{
						Ext.getCmp( 'abType' + module ).reset();
						
						Ext.getCmp( 'abTime' + module ).setReadOnly( false );
						Ext.getCmp( 'abTime' + module ).reset();
						Ext.getCmp( 'abWeek' + module ).setReadOnly( true );
						Ext.getCmp( 'abWeek' + module ).reset();
						Ext.getCmp( 'abDay' + module ).setReadOnly( true );
						Ext.getCmp( 'abDay' + module ).reset();
					}
					
					Ext.getCmp( 'gridList' + module ).store.load();
				}
			} );
		}
		
		function _mainPanel( config ){
			
			var schedule = standards.callFunction( '_createLocalStore', {
				data	: [ 'Daily' ,'Weekly' ,'Monthly' ]
			} );
			
			var week = standards.callFunction( '_createLocalStore', {
				data	: [ 'Week 1' ,'Week 2' ,'Week 3' ,'Week 4' ]
			} );
			
			var dayStore = standards.callFunction( '_createLocalStore', {
				data	: [ 'Sunday' ,'Monday' ,'Tuesday' ,'Wednesday' ,'Thursday' ,'Friday' ,'Saturday' ]
			} );
			
			return standards.callFunction(	'_mainPanel', {
				config		: config
				,tbar		: {
					noFormButton	: true
					,noListButton	: true
				}
				,moduleType	: 'form'
				,formItems	: [
					{	xtype	: 'container'
						,layout	: 'column'
						,items	: [
							{	xtype		: 'fieldset'
								,title		: 'Weekly Auto-Back Up Schedule'
								,autoHeight	: true
								,items		: [
									{	xtype		: 'container'
										,baseCls	: 'xtype'
										,layout		: 'column'
										,style		: 'margin-top:5px; margin-bottom:5px'
										,items		: [
											{	id		: 'idAB' + module
												,name	: 'idAB' + module
												,value	: 0
												,xtype	: 'hidden'
											}
											,standards.callFunction( '_createCombo', {
												id			: 'abType' + module
												,store		: schedule
												,emptyText	: 'Select type...'
												,fieldLabel	: ''
												,width		: 200
												,style		: 'margin-right:5px'
												,editable	: false
												,listeners	: {
													select	: function( me, result ){
														if( me.getValue( ) == 1 ){
															Ext.getCmp( 'abTime' + module ).setReadOnly( false )
															
															Ext.getCmp( 'abWeek' + module ).setReadOnly( true )
															Ext.getCmp( 'abDay' + module ).setReadOnly( true )
															
															Ext.getCmp( 'abWeek' + module ).setValue( null )
															Ext.getCmp( 'abDay' + module ).setValue( null )
														}
														else if( me.getValue( ) == 2 ){
															Ext.getCmp( 'abTime' + module ).setReadOnly( false )
															Ext.getCmp( 'abDay' + module ).setReadOnly( false )
															
															Ext.getCmp( 'abWeek' + module ).setReadOnly( true )
															
															Ext.getCmp( 'abWeek' + module ).setValue( null )
														}
														else{
															Ext.getCmp( 'abTime' + module ).setReadOnly( false )
															Ext.getCmp( 'abWeek' + module ).setReadOnly( false )
															Ext.getCmp( 'abDay' + module ).setReadOnly( false )
														}
													}
												}
											} )
											,standards.callFunction( '_createCombo', {
												id			: 'abWeek' + module
												,store		: week
												,emptyText	: 'Select week number...'
												,fieldLabel	: ''
												,width		: 200
												,style		: 'margin-right:5px'
												,editable	: false
												,readOnly	: true
											} )
											,standards.callFunction( '_createCombo', {
												id			: 'abDay' + module
												,width		: 150
												,store		: dayStore
												,emptyText	: 'Select day...'
												,fieldLabel	: ''
												,style		: 'margin-right:5px'
												,editable	: false
												,readOnly	: true
											} )
											,standards.callFunction( '_createTimeField', {
												id			: 'abTime' + module
												,fieldLabel	: ''
												,style		: 'margin-right:5px'
												,width		: 100
												,increment	: 60
												,editable	: false
												,readOnly	: true
											} )
											,{	xtype		:'button'
												,text		:''
												,style		: 'margin-right:5px'
												,iconCls	:'glyphicon glyphicon-floppy-disk'
												,handler	:function(){
													_saveForm();
												}
											}
											,{	xtype		: 'button'
												,text		: ''
												,iconCls	: 'glyphicon glyphicon-refresh'
												,handler	: function(){
													_init();
												}
											}
										]
									}
								]
							}
							,{	xtype		: 'fieldset'
								,title		: 'Manual Back-up And Restore'
								,style		: 'margin-left:5px'
								,autoHeight	: true
								,items		: [
									{	xtype	: 'container'
										,style	: 'margin-top:5px; margin-bottom:5px'
										,layout	: 'column'
										,items	: [
											{	xtype			: 'filefield'
												,id				: 'restorefile' + module
												,name			: 'restorefile' + module
												,vtype			: 'file'
												,style			: 'margin-right:5px'
												,buttonOnly		: true
												,buttonConfig	: {
													text		: 'Restore'
													,iconCls	: 'glyphicon glyphicon-import'
												}
												,listeners		: {
													change	: function(){
														if( this.isValid() ) _restore();
														else Ext.MessageBox.alert( 'Error', 'Invalid file format.' );
													}
												}
											}
											,{	xtype		: 'button'
												,text		: 'Backup'
												,width		: 80
												,style		: 'margin-right:5px'
												,iconCls	: 'glyphicon glyphicon-export'
												,handler	: function(){
													_backup();
												}
											}
										]
									}
								]
							}
						]
					}
				]
				,moduleGrids	: [
					_gridList()
				]
				,listeners		: {
					afterrender	: _init
				}
			} );
		}
		
		function _backup( ){
			Ext.Ajax.request( {
				url			: route + 'getDBTables'
				,method		: 'post'
				,success	: function( response ){
					var resp = Ext.decode( response.responseText );
					_processBackUpPerTable( resp.view );
				}
			} );
		}

		function _processBackUpPerTable( tableList, index = 0, fileName = '' ){
			if( index != ( tableList.length - 1 ) ){
				Ext.Ajax.request( {
					url			: route + 'backupTable'
					,method		: 'post'
					,msg		: ( ( index + 1 ) + "/" + tableList.length + " | Backup in progress... " )
					,params		: {
						tableName	: tableList[index]
						,fileName	: fileName
					}
					,success	: function( response ){
						var resp = Ext.decode( response.responseText )
						_processBackUpPerTable( tableList, ( index + 1 ), resp.fileName );
					}
				} );
			}
			else{
				standards.callFunction( '_createMessageBox', {
					msg	: 'Database backup has been successfully created.'
				} );
				Ext.getCmp( 'gridList' + module ).store.load();
			}
		}
		
		function _restore( data ){
			Ext.create( 'Ext.window.Window', {
				title	: 'Confirm Password'
				,layout	: 'fit'
				,id		: 'winPass' + module
				,modal	: true
				,items	: [
					{	xtype			: 'form'
						,bodyPadding	: '3px'
						,items			: [
							{	xtype		: 'textfield'
								,fieldLabel	: 'Password'
								,id			: 'password' + module
								,name		: 'password'
								,inputType	: 'password' 
							}
						]
						,buttons		: [
							{	text		: 'Ok'
								,handler	: function(){
									Ext.Ajax.request( {
										url			: route + 'checkPassword'
										,params		: { 
											pass	: Ext.getCmp( 'password' + module ).value
										}
										,method		: 'post'
										,success	: function( res ){
											var results = Ext.JSON.decode( res.responseText );
											var confirm	= parseInt( results.confirm, 10 );

											if( confirm == 1 ){
												
												if( data == null ){
													data = {
														'idModule'	: idModule
													}
												}
												else{
													data['idModule']	= idModule
												}
												Ext.getCmp( 'winPass' + module ).close();
												
												var form = Ext.getCmp( 'mainFormPanel' + module ).getForm();
												form.submit( {
													waitTitle	: "Please wait"
													,waitMsg	: "Restoring backup..."
													,url		: route + "restoreFile"
													,params		: data
													,success	: function( response, action ){
														var resp =  action.result.trigger;
														
														if( parseInt( resp, 10 )==1 ){
															standards.callFunction( '_createMessageBox', {
																msg	: 'Invalid Contents of file to restore or file is empty.'
															} );
														}
														else if( parseInt( resp, 10 ) == 2 ){
															standards.callFunction( '_createMessageBox', {
																msg : 'An error has occured while restoring database, please try again later.'
															} );
														}
														else{
															standards.callFunction( '_createMessageBox', {
																msg		: 'Database backup restoration has been successful.'
																,icon	: ''
															} );
														}
														
													}
												});
											}
											else{
												standards.callFunction( '_createMessageBox', {
													msg : 'Unable to restore database. Incorrect password. Please contact your system administrator to input the password.'
												} );
											}
										}
										,failure	: function(){
											standards.callFunction( '_createMessageBox', {
												msg		: 'Database connectivity error: Failure during restoration of record.'
												,icon	: 'Error'
											} );
										}
									} );
								}
							}
							,{	text		: 'Cancel' 
								,handler	: function(){
									this.up( 'window' ).close();
								}
							}
						]
					}
				]
			} ).show();
		}
		
		function _delete( form ){
			var store = Ext.getCmp( 'gridList' + module ).store;
			if( form.ident > 5 ){
				form.confirmDelete( {
					url			: route + 'deleteRecord'
					,params		: {
						file		: form.filename
						,idModule	: idModule
					}
					,success	:function( action, response ){
						standards.callFunction( '_createMessageBox', {
							msg		: 'Record has been successfully deleted.'
							,icon	: ''
						} );
						Ext.getCmp( 'gridList' + module ).store.load();
					}
				} );
			}
			else{
				standards.callFunction( '_createMessageBox', {
					msg		: 'Cannot delete this record.'
					,icon	: ''
				} );
			}
		}
		
		function _download( data, row ){
			var fileName	= ''
				,fileExt	= ''
				,spFN		= data.filename.split( '.' );
			for( var i = 0; i < spFN.length - 1; i++ ){
				if( i != spFN.length - 2 ){
					fileName += spFN[i] + '.';
				}
				else{
					fileName += spFN[i];
				}
			}
			fileExt = spFN[spFN.length - 1];
			window.open( route + 'download/' + fileName + '/' + fileExt );
		}
		
		function _saveForm( form ){
			var type	= Ext.getCmp( 'abType' + module ).getValue();
			var day		= Ext.getCmp( 'abDay' + module ).getValue();
			var time	= Ext.getCmp( 'abTime' + module ).getValue();
			var week	= Ext.getCmp( 'abWeek' + module ).getValue();
			var idAB	= Ext.getCmp( 'idAB' + module ).getValue();
			
			if( type == 1 ){
				if( time == null ){
					standards.callFunction( '_createMessageBox', { msg : 'Time is required.' });
					return
				}
			}
			else if( type == 2 ){
				if( time == null || day == null ){
					standards.callFunction( '_createMessageBox', { msg : 'Day and Time are required.' });
					return
				}
			}
			else{
				if( time == null || week == null || day == null ){
					standards.callFunction( '_createMessageBox', { msg : 'Week, Day and Time are required.' });
					return
				}
			}
			
			Ext.Ajax.request( {
				url			: route + 'saveSchedule'
				,params		: {
					abType 	: type
					,abDay	: day
					,abTime	: Ext.Date.format( time, 'H:i:s' )
					,abWeek	: week
					,idAB	: idAB
				}
				,success	: function( response ){
					standards.callFunction('_createMessageBox', { 
						msg	: 'SAVE_SUCCESS'
						,fn	: function(){
							_init();
						}
					} )
				}
			} );
		}
		
		function _gridList( ){
			var store =  standards.callFunction(  '_createRemoteStore' ,{
				fields	: [ 'ident', 'bdate', 'btime', 'filename', 'user', 'selected' ]
				,url	: route + "Retrieve"
			} );
			
			return standards.callFunction( '_gridPanel',{
				id				: 'gridList' + module
				,module			: module
				,noDefaultRow	: true
				,store			: store
				,style			: 'margin-top: 10px'
				,noPage			: true
				,columns		: [
					{	header		: 'Date'
						,width		: 100
						,dataIndex	: 'bdate'
						,sortable	: false
					}
					,{	header		: 'Time'
						,width		: 100
						,dataIndex	: 'btime'
						,sortable	: false}
					,{	header		: 'Filename'
						,flex		: 1
						,dataIndex	: 'filename'
						,sortable	: false
					}
					,{	header		: 'Username'
						,width		: 100
						,dataIndex	: 'user'
						,sortable	: false
					}
					,standards.callFunction( '_createActionColumn' ,{
						icon		: 'import'
						,tooltip	: 'Restore backup'
						,Func		: _restore
					} )
					,standards.callFunction( '_createActionColumn' ,{
						icon		: 'download'
						,tooltip	: 'Download backup'
						,Func		: _download
					} )
					,standards.callFunction( '_createActionColumn' ,{
						canDelete	: canDelete
						,icon		: 'remove'
						,tooltip	: 'Remove backup'
						,Func		: _delete
					} )
				]
				
			} );
		}
		
		return{
			initMethod	: function( config ){
				route		= config.route;
				baseurl		= config.baseurl;
				module		= config.module;
				canDelete	= config.canDelete;
				pageTitle   = config.pageTitle;
				idModule	= config.idmodule;
				
				return _mainPanel( config );
			}
		}
	}
}