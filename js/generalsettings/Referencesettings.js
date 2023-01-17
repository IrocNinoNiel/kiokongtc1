/**
 * Developer: Hazel Alegbeleye
 * Module: Reference Settings
 * Date: Nov 25, 2019
 * Finished: December 4, 2019
 * Description: This module allows the authorized user to set (add, edit and delete ) the references that will be used in every transaction in the system.
 * DB Tables: affiliate, module, costcenter, reference, referenceaffiliate, referenceseries, invoices
 * */ 
function Referencesettings(){
    return function(){
        var baseurl, route, module, canDelete, pageTitle, canPrint, isGae, onEdit = 1
        ,configInitial, configSeries, idReference = 0, idAffiliate, idSeries = 0;

        function _init() {
			Ext.getCmp('idAffiliate' + configSeries.module).store.load({
				callback: function() {
					Ext.getCmp('idAffiliate' + configSeries.module).setValue( parseInt(idAffiliate, 10) );
				}
			});
        }
        
        function _mainPanel( config ) {
            configInitial 			= Ext.encode( config );
            configInitial 			= Ext.decode( configInitial );
            configInitial.module	= '_initialRefSetting';

            configSeries 			= Ext.encode( config );
            configSeries 			= Ext.decode( configSeries );
			configSeries.module 	= '_seriesRefSetting';

			var moduleStore = standards.callFunction( '_createRemoteStore', {
                fields      : [ { name: 'idModule', type: 'number' }, 'moduleName' ]
				,url        : route + 'getModules'
				,startAt	: 1
			} ), affiliateStore = standards.callFunction( '_createRemoteStore', {
                fields      : [ { name: 'idAffiliate', type: 'number' }, 'affiliateName',{ name:'chk', type:'bool' }, { name: 'refTag', type: 'number'} ]
                ,url        : route + 'getAffiliates'
			} ), ccStore = standards.callFunction( '_createRemoteStore', {
                fields      :  [ { name: 'idCostCenter', type: 'number' }, 'costCenterName' ]
                ,url        : route + 'getCostCenters'
            } ), refStore = standards.callFunction( '_createRemoteStore', {
                fields      :  [ { name: 'idReference', type: 'number' }, 'name' ]
                ,url        : route + 'getReference'
            } );
			

            return standards.callFunction( '_mainPanel', {
                config      : config
				,isTabChild : true
				,moduleID	: idModule
                ,listeners  : {
                    afterrender : _init
                }
                ,formItems  : [
                    {
                        xtype   : 'tabpanel'
                        ,items  : [
                            {
                                title   : 'Initial'
                                ,layout : {
                                    type            : 'card'
                                    ,deferredRender : true
                                }
                                ,items  : [
                                    standards.callFunction(	'_mainPanel' ,{
										formType        : 'form'
										,config         : configInitial
										,statLabelID 	: 'headerLabel_Initial'
										,asContainer    : true
										,tbar : {
											saveFunc        : _saveFormInitial
											,resetFunc      : _resetFormInitial
											,noFormButton   : true
											,noListButton   : true
										}
										,formItems:[
											{	
												xtype   : 'container'
												,layout : 'column'
												,style  : 'margin-bottom:10px;'
												,items  : [
													{
														xtype 	: 'container'
														// ,style 	: 'margin-right:20px;'
														,columnWidth	: .5
														,items  : [
															{
																xtype   : 'hiddenfield'
																,id     : 'idReference' + configInitial.module
															}
															,standards.callFunction( '_createTextField' ,{
																	id          : 'code' + configInitial.module
																	,fieldLabel : 'Code'
																	,allowBlank : false
																	,maxLength  : 5
																} 
															)
															,standards.callFunction( '_createTextField' ,{
																	id          : 'name' + configInitial.module
																	,fieldLabel : 'Name'
																	,allowBlank : false
																	,maxLength  : 50
																} 
															)
															,standards.callFunction( '_createCombo', {
																id              : 'idModule' + configInitial.module
																,store          : moduleStore
																,fieldLabel     : 'Modules'
																,valueField     : 'idModule'
																,displayField   : 'moduleName'
																,allowBlank     : false
															})
														]
													}
													,{
                                                        xtype           : 'container'
														,columnWidth	: .5
														,layout         : 'hbox'
														// ,style			: 'margin-left: 10px;'
														,items : [
															{
																xtype   : 'label'
                                                                ,html   : 'Affiliate' + Ext.getConstant('REQ') + ':'
                                                                ,width  : 120
															}
															,_affiliateGrid()
														]
													}
												] 
											
											}
										]
										,moduleGrids : _grdInitial( configInitial )
									})
                                ]
                            }
                            ,{
                                title   : 'Series'
                                ,layout : {
                                    type            : 'card'
                                    ,deferredRender :   true
                                }
                                ,items  : [
                                    standards.callFunction(	'_mainPanel' ,{
										formType 		: 'form'
										,config 		: configSeries
										,statLabelID 	: 'headerLabel_Series'
										,asContainer 	: true
										,tbar 			: {
											saveFunc 		: _saveFormSeries
											,resetFunc		: _resetFormSeries
											,noFormButton 	: true
											,noListButton 	: true
										}
										,formItems:[
											{	
												xtype 	: 'container'
												,layout : 'column'
												,style 	: 'margin-bottom:10px;'
												,items 	: [
													{
														xtype 			: 'container'
														,columnWidth	: .5
														,items 	: [
															{
																xtype   : 'hiddenfield'
																,id     : 'idReferenceSeries' + configSeries.module
															}
															,standards.callFunction( '_createDateField', {
																id          : 'date' + configSeries.module
																,fieldLabel : 'Date'
																,allowBlank : false
															} )
															,standards.callFunction( '_createCombo', {
																id              : 'idAffiliate' + configSeries.module
																,store          : affiliateStore
																,fieldLabel     : 'Affiliate'
																,valueField     : 'idAffiliate'
																,displayField   : 'affiliateName'
																,allowBlank     : false
																,listeners		: {
																	select : function( me, record ) {
																		var _module = configSeries.module;
																		_validateCostCenter( _module );

																		Ext.getCmp('idModule'+ _module).reset();
																		Ext.getCmp('idReference'+ _module).reset();
																	}
																	,change : function( me, newVal ){
																		if( newVal != null ) _validateCostCenter( configSeries.module );

																		_getAffiliateRecord(this.getValue()).then( function(record) {
																			var dateStart = Ext.toMoment( record.dateStart ).add(1, 'days').toDate();
																			Ext.getCmp('date' + configSeries.module ).setMinValue(dateStart);
																		}, function(error) {
																			console.log( 'record is null' );
																		});
																	}
																}
															})
															,standards.callFunction( '_createCombo', {
																id              : 'idCostCenter' + configSeries.module
																,store          : ccStore
																,fieldLabel     : 'Cost Center'
																,valueField     : 'idCostCenter'
																,displayField   : 'costCenterName'
																,allowBlank     : true
																,listeners		: {
																	beforeQuery : function() {
																		var idAffiliate = Ext.getCmp( 'idAffiliate' + configSeries.module ).getValue();
																		ccStore.proxy.extraParams.idAffiliate = parseInt( idAffiliate, 10 );
																		ccStore.load({
																			callback: function(){
																				if( ccStore.getCount() < 1 ){
																					standards.callFunction('_createMessageBox',{ msg: 'No cost center was added for this Affiliate.' })
																				} 
																			}
																		});
																	}
																	,afterrender : function() {
																		_validateCostCenter( configSeries.module );
																	}
																}
															})
															,standards.callFunction( '_createCombo', {
																id              : 'idModule' + configSeries.module
																,store          : moduleStore
																,fieldLabel     : 'Modules'
																,valueField     : 'idModule'
																,displayField   : 'moduleName'
																,allowBlank     : false
																,listeners 		: {
																	change : function(){
																		Ext.getCmp('idReference'+configSeries.module).reset();
																	}
																}
															})
														]
													}
													,{
														xtype 			: 'container'
														,columnWidth	: .5
														,items : [
															standards.callFunction( '_createCombo', {
																id              : 'idReference' + configSeries.module
																,store          : refStore
																,fieldLabel     : 'Reference'
																,valueField     : 'idReference'
																,displayField   : 'name'
																,allowBlank     : false
																,width 			: 450
																,listeners      : {
																	beforeQuery : function( qe ) {
																		var affiliate = Ext.getCmp( 'idAffiliate' + configSeries.module )
																			,_module = Ext.getCmp( 'idModule' + configSeries.module );

																		if( typeof affiliate != 'undefined' && affiliate.getValue() != null ){
																			switch( true ){
																				case affiliate.getValue() == null:
																					standards.callFunction( '_createMessageBox', { msg	: 'Invalid action. Please select an affiliate first.' } );
																					return false;
																					break;
																				case _module.getValue() == null:
																					standards.callFunction( '_createMessageBox', { msg	: 'Invalid action. Please select a module first.' } );
																					return false;
																					break;
																				default:
																					refStore.proxy.extraParams = {
																						idAffiliate : parseInt( affiliate.getValue(), 10 )
																						,idModule	: parseInt( _module.getValue(), 10 )
																					}
																					break;
																			}
																		}

																		refStore.load({
																			callback: function(){
																				if( refStore.getCount() <= 0 ) {
																					standards.callFunction( '_createMessageBox', { msg	: 'No reference has been created for the selected affiliate.' } );
																				}
																			}
																		});
																	}
																}
															})
															,{	xtype 	: 'container'
																,layout : 'column'
																,items 	: [
																	standards.callFunction( '_createTextField' ,{
																			id 			: 'seriesFrom' + configSeries.module
																			,fieldLabel : 'Series From'
																			,allowBlank : false
																			,maxLength 	: 20
																			,width 		: 275
																			,style 		: 'margin-right:5px;'
																			,isNumber 	: true
																			,isDecimal 	: false
																			,hasComma 	: false
																		} 
																	)
																	,standards.callFunction( '_createTextField' ,{
																			id 			: 'seriesTo' + configSeries.module
																			,fieldLabel : 'To'
																			,allowBlank : false
																			,maxLength 	: 20
																			,labelWidth : 30
																			,width 		: 170
																			,withREQ 	: false
																			,isNumber 	: true
																			,isDecimal 	:false
																			,hasComma 	: false
																		} 
																	)
																]
															}
														]
													}
												] 
											
											}
										]
										,moduleGrids : _grdSeries( configSeries )
									})
                                ]
                            }
                        ]
                    }
                ]
            });
        }

        function _affiliateGrid() {

            var affiliateStore = standards.callFunction( '_createRemoteStore', {
                fields      : [ 'idAffiliate', 'affiliateName',{ name:'chk', type:'bool' } ]
                ,url        : route + 'getAffiliates'
            } );

            var sm = new Ext.selection.CheckboxModel( {
                checkOnly   : true
            } );

            return standards.callFunction( '_gridPanel', {
                id          : 'grdAffiliates' + configInitial.module
                ,module     : module
                ,store      : affiliateStore
                ,height     : 200
                ,width      : 250
                ,selModel   : sm
                ,plugins    : true
				,noPage     : true
				// ,tbar		: { }
                ,columns    : [
                    {   header      : 'Affiliate Name'
                        ,dataIndex  : 'affiliateName'
                        ,flex       : 1
						,minWidth   : 80
						,renderer 	: function( val, params, record, row_index ){
							if( record.data.chk ){
								sm.select( row_index, true );
							}
							return val;
						}
                    }
                ]
                ,listeners  : {
                    afterrender : function() {
                        affiliateStore.load({});
					}
                }
            } )
        }

        function _grdInitial( configInitial ) {
            var store = standards.callFunction(  '_createRemoteStore' ,{
                fields:[
                    {	name	: 'idReference'
                        ,type	: 'number'
                    }
                    ,'moduleName'
                    ,'code'
                    ,'name'
                ]
                ,url: route + 'getHistory/initial'
			});

			var searchFilter = standards.callFunction( '_createLocalStore',{
				startAt	: 0
				,fields : [ 'id' ,'name' ]
				,data: [
					{ id : 1, name : 'Reference Code' }
					,{ id : 2, name : 'Reference Name' }
				]
			});
			
			function _editRecord( data ){

				configInitial.module.getForm().retrieveData({
					url     : route + 'retrieveData'
					,method	: 'post'
					,params	: {
						idReference	: data.idReference
					}
					,success	: function( response, match ) {
						// if( match > 0 ) Ext.getCmp( 'grdAffiliates' + configInitial.module ).setDisabled(true);

						idReference = response.idReference;

						if( response.idRefAffiliates != null ) {
							var affiliates = response.idRefAffiliates.split(",", response.idRefAffiliates.length );
							
							var gridAffiliate   = Ext.getCmp('grdAffiliates' + configInitial.module)
								,store          = gridAffiliate.getStore()
								,grdSM          = gridAffiliate.getSelectionModel();
					
							store.proxy.extraParams.idAffiliates = Ext.encode( affiliates );
							store.load({
								callback: function(){
									var items = store.data.items;
	
									items.map( (col, i) => {
										affiliates.map( (idAffiliate) => {
											if( idAffiliate == col.data.idAffiliate ){
												grdSM.select( i, true );
											}
											// grdSM.setLocked( true );
										} )
									})
								}
							});
						}
						
					}
				});
			}

			function _deleteRecord( data ) {
				standards.callFunction( '_createMessageBox', {
					msg		: 'DELETE_CONFIRM'
					,action	: 'confirm'
					,fn		: function( btn ){
						if( btn == 'yes' ) {
							Ext.Ajax.request({
								url	: route + 'deleteReference'
								,params	: {
									idReference : data.idReference
								}
								,success : function( response ) {
									var resp = Ext.decode( response.responseText );

									standards.callFunction( '_createMessageBox', {
										msg	: ( resp.match == 0 ) ? 'DELETE_SUCCESS' : ( resp.match == 2 ) ? 'Default reference cannot be deleted.' : 'DELETE_USED'
										,fn	: function(){
											Ext.getCmp( 'gridHistory' + configInitial.module ).store.load({});
										}
									} )
									
								}
							});
						}
					}
				} );

				
			}


			// Grid for Initial
            return standards.callFunction( '_gridPanel',{
				id			: 'gridHistory' + configInitial.module
				,module		: configInitial.module
				,store		: store
				,plugins	: true
				,tbar 		: {
					canPrint 				: canPrint
					,noExcel 				: true
					,route 					: route
					,pageTitle 				: pageTitle
					,customListPDFHandler   : _printPDF
					,extraParams 			: {
						mode 	: 'initial'
					}
					,content: [
                        standards.callFunction( '_createCombo', {
                            id 			: 'searchFilterKey' + configInitial.module
                            ,fieldLabel	: 'Search by'
                            ,module		: module
                            ,store  	: searchFilter
                            ,valueField	: 'id'
							,displayField: 'name'
							,value		: 1
                            ,labelWidth	: 100
							,width		: 300
							,cls 		: 'notIncludeAutoReadOnly'
							,listeners 	: {
								select : function( me, record ) {
									var filterValue = Ext.getCmp( 'searchFilterValue' + configInitial.module );
									filterValue.emptyText  = 'Search by ' + me.rawValue.toLowerCase() + '...';
									filterValue.applyEmptyText();
								}
							}
						} )
						,standards.callFunction( '_createTextField' ,{
								id          : 'searchFilterValue' + configInitial.module
								,allowBlank : true
								,width		: 200
								,emptyText	: 'Search by reference code...'
								,cls 		: 'notIncludeAutoReadOnly'
								,listeners	: {
									change	: function(me, newVal, oldVal) {
										store.proxy.extraParams = {
											filterKey 		: Ext.getCmp( 'searchFilterKey' + configInitial.module ).getValue()
											,filterValue	: newVal
										};
										store.load( {
											callback: function(){
												store.currentPage = 1;
											}
										} );
									}
								}
							} 
						)
                        ,{   xtype 		: 'button'
                            ,iconCls	: 'glyphicon glyphicon-refresh'
                            ,handler	: function(){
								Ext.getCmp( 'searchFilterKey' + configInitial.module ).reset();
								Ext.getCmp( 'searchFilterValue' + configInitial.module ).reset();
                                store.proxy.extraParams = {};
                                store.load( {
                                    callback: function(){
                                        store.currentPage = 1;
                                    }
                                } );
                            }
						}
						,'->'
						,{	xtype		: 'button'
							,iconCls	: 'pdf-icon'
							,tooltip	: 'Export to PDF'
							,handler	: function(){
								_printPDF( 'initial' );
							}
						}
                    ]
				}
				,columns: [
					{	header 			: 'Reference Code'
						,dataIndex 		: 'code'
						,width 			: 150
						,columnWidth 	: 30
					}
					,{	header 			: 'Reference Name'
						,dataIndex 		: 'name'
						,flex 			: 1
						,minWidth 		: 200
						,columnWidth 	: 40
					}
					,{	header 			: 'Module Name'
						,dataIndex 		: 'moduleName'
						,width 			: 250
						,columnWidth 	: 30
					}
					,standards.callFunction( '_createActionColumn', {
						icon 		: 'pencil'
						,tooltip 	: 'Edit record'
						,Func 		: _editRecord
					})
					,standards.callFunction( '_createActionColumn' ,{
						canDelete 	: canDelete
						,icon 		: 'remove'
						,tooltip 	: 'Remove record'
						,Func 		: _deleteRecord
					})
				]
				,listeners	: {
					afterrender : function() {
						store.load({});
					}
				}
			});

        }

        function _grdSeries( configSeries ) {
			var store = standards.callFunction(  '_createRemoteStore' ,{
                fields:[ 'idReferenceSeries', 'idAffiliate' ,'date', 'affiliateName', 'costCenterName', 'moduleName', 'name', 'code', 'seriesFrom', 'seriesTo']
                ,url: route + 'getHistory/series'
			});

			var searchFilter = standards.callFunction( '_createLocalStore',{
				startAt	: 0
				,fields : [ 'id' ,'name' ]
				,data: [
					{ id : 1, name : 'Reference Code' }
					,{ id : 2, name : 'Reference Name' }
				]
			});
			
			function _editRecord( data ){
				
				configSeries.module.getForm().retrieveData({
					url     : route + 'retrieveReferenceSeries'
					,method	: 'post'
					,params	: {
						idReferenceSeries	: data.idReferenceSeries
					}
					,excludes 	: ['idReference', 'idCostCenter']
					,success	: function( response ) {
						setComboValue( 
							'idReference' + configSeries.module
							,{ 
								idAffiliate : parseInt( response.idAffiliate, 10 )
								,idModule 	: parseInt( response.idModule, 10 )
							}
							,response.idReference 
						);

						setComboValue( 
							'idCostCenter' + configSeries.module
							,{ idAffiliate : parseInt( response.idAffiliate, 10 ) }
							,response.idCostCenter 
						);
					}
				});
			}

			function _deleteRecord( data ) {
				standards.callFunction( '_createMessageBox', {
					msg		: 'DELETE_CONFIRM'
					,action	: 'confirm'
					,fn		: function( btn ){
						if( btn == 'yes' ) {
							Ext.Ajax.request({
								url	: route + 'deleteSeries'
								,params	: {
									idReferenceSeries : data.idReferenceSeries
								}
								,success : function( response ) {
									var resp = Ext.decode( response.responseText );

									standards.callFunction( '_createMessageBox', {
										msg	: ( resp.match == 0 ) ? 'DELETE_SUCCESS' : 'DELETE_USED'
										,fn	: function(){
											Ext.getCmp( 'gridHistory' + configSeries.module ).store.load({});
										}
									} )
									
								}
							});
						}
					}
				} );

				
			}

			//Grid for Series
            return standards.callFunction( '_gridPanel',{
				id			: 'gridHistory' + configSeries.module
				,module		: configSeries.module
				,store		: store
				,plugins	: true
				,tbar 		: {
					canPrint 				: canPrint
					,noExcel 				: true
					,route 					: route
					,pageTitle 				: pageTitle
					,customListPDFHandler   : _printPDF
					,extraParams 			: {
						mode 	: 'series'
					}
					,content: [
                        standards.callFunction( '_createCombo', {
                            id 			: 'searchFilterKey' + configSeries.module
                            ,fieldLabel	: 'Search by'
                            ,module		: module
                            ,store  	: searchFilter
                            ,valueField	: 'id'
							,displayField: 'name'
							,value		: 1
                            ,labelWidth	: 100
							,width		: 300
							,cls 		: 'notIncludeAutoReadOnly'
							,listeners 	: {
								select : function( me, record ) {
									var filterValue = Ext.getCmp( 'searchFilterValue' + configSeries.module );
									filterValue.emptyText  = 'Search by ' + me.rawValue.toLowerCase() + '...';
									filterValue.applyEmptyText();
								}
							}
						} )
						,standards.callFunction( '_createTextField' ,{
								id          : 'searchFilterValue' + configSeries.module
								,width		: 200
								,emptyText	: 'Search by reference code...'
								,cls 		: 'notIncludeAutoReadOnly'
								,listeners	: {
									change	: function(me, newVal, oldVal) {
										store.proxy.extraParams = {
											filterKey 		: Ext.getCmp( 'searchFilterKey' + configSeries.module ).getValue()
											,filterValue	: newVal
										};
										store.load( {
											callback: function(){
												store.currentPage = 1;
											}
										} );
									}
								}
							} 
						)
                        ,{   xtype 		: 'button'
							,iconCls	: 'glyphicon glyphicon-refresh'
                            ,handler	: function(){
								Ext.getCmp( 'searchFilterKey' + configSeries.module ).reset();
								Ext.getCmp('searchFilterValue' + configSeries.module ).reset();
                                store.proxy.extraParams = {};
                                store.load( {
                                    callback: function(){
                                        store.currentPage = 1;
                                    }
                                } );
                            }
						}
						,'->'
						,{	xtype		: 'button'
							,iconCls	: 'pdf-icon'
							,tooltip	: 'Export to PDF'
							,handler	: function(){
								_printPDF( 'series' );
							}
						}
                    ]
				}
				,columns: [
					{	header 			: 'Date'
						,dataIndex 		: 'date'
						,width 			: 100
						,columnWidth 	: 30
						,xtype			: 'datecolumn'
					}
					,{	header 			: 'Affiliate'
						,dataIndex 		: 'affiliateName'
						,flex 			: 1
						,minWidth 		: 50
						,columnWidth 	: 40
					}
					,{	header 			: 'Cost Center'
						,dataIndex 		: 'costCenterName'
						,flex 			: 1
						,minWidth 		: 50
						,columnWidth 	: 30
					}
					,{	header 			: 'Module'
						,dataIndex 		: 'moduleName'
						,width 			: 150
						,columnWidth 	: 30
					}
					,{	header 			: 'Reference Name'
						,dataIndex 		: 'name'
						,width 			: 150
						,columnWidth 	: 30
					}
					,{	header 			: 'Reference Code'
						,dataIndex 		: 'code'
						,width 			: 150
						,columnWidth 	: 30
					}
					,{	header 			: 'From'
						,dataIndex 		: 'seriesFrom'
						,width 			: 80
						,columnWidth 	: 30
					}
					,{	header 			: 'To'
						,dataIndex 		: 'seriesTo'
						,width 			: 80
						,columnWidth 	: 30
					}
					,standards.callFunction( '_createActionColumn', {
						icon 			: 'pencil'
						,tooltip 		: 'Edit record'
						,Func 			: _editRecord
					})
					,standards.callFunction( '_createActionColumn' ,{
						canDelete 		: canDelete
						,icon 			: 'remove'
						,tooltip 		: 'Remove record'
						,Func 			: _deleteRecord
					})
				]
				,listeners	: {
					afterrender : function() {
						store.load({});
					}
				}
			});

		
        }

        function _saveFormInitial( form ) {

			var selectedRows = Ext.getCmp('grdAffiliates' + configInitial.module).getSelectionModel().getSelection()
				,refAffiliates = [];

			if( typeof selectedRows != 'undefined' && selectedRows.length > 0  ){
				selectedRows.map( (col, i) => {
					refAffiliates.push( col.data.idAffiliate );
				});

				form.submit({
					url	: route + 'saveInitial'
					,params	: {
						affiliates : Ext.encode(refAffiliates)
					}
					,success : function( me, response ) {
						var resp = Ext.decode( response.response.responseText )
							,msg = (resp.match == 0 ? 'SAVE_SUCCESS' : 'REF_EXISTS');

							standards.callFunction( '_createMessageBox', {
								msg	: msg
								,action : ( resp.match == 2 ? 'confirm' : '' )
								,fn	: function( btn ){
									if( btn == 'no' || btn == 'ok' ) {
										_resetFormInitial( form );
									}
								}
							} )
					}
				})
			} else {
				standards.callFunction( '_createMessageBox', { msg	: 'Please select atleast one affiliate.' } );
			}
		}

        function _resetFormInitial( form ) {
			onEdit = 1;

			form.reset();
			Ext.getCmp('gridHistory' + configInitial.module).store.load({});
			Ext.getCmp('grdAffiliates' + configInitial.module).store.load({});
        }

        function _saveFormSeries( form ) {

			var seriesTo = Ext.getCmp( 'seriesTo' + configSeries.module ).getValue()
				,seriesFrom = Ext.getCmp( 'seriesFrom' + configSeries.module ).getValue();

			if( seriesTo <= seriesFrom ){
				standards.callFunction( '_createMessageBox', { msg	: 'Invalid action. Series To must be greater than Series From.' } )
			} else {
				form.submit({
					url	: route + 'saveSeries'
					,success : function( me, response ) {
						var resp = Ext.decode( response.response.responseText )
							,msg = "";
	
						switch( resp.match ){
							case 0: //SUCCESS
								msg = "SAVE_SUCCESS";
								break;
							case 1: //RECORD_USED
								msg = "EDIT_USED";
								break;
							case 2: //SERIES INVALID
								msg = "Reference series should be greater than the last recorded series for this reference. Would you like to change?";
								break;
						}

						standards.callFunction( '_createMessageBox', {
							msg	: msg
							,action : ( resp.match == 2 ? 'confirm' : '' )
							,fn	: function( btn ){
								if( btn == 'no' || btn == 'ok' ) {
									_resetFormSeries( form );
								}
							}
						} )
					}
				})
			}

		}

        function _resetFormSeries( form ) {
			onEdit = 1;
			idSeries = 0;

			form.reset();
			Ext.getCmp('gridHistory' + configSeries.module).store.load({});
			_init();
		}
		
		function _printPDF( mode ){

            Ext.Ajax.request({
                url			: route + 'generatePDF/' + mode
                ,method		:'post'
                ,params		: {
                    idmodule    : 8
                    ,pageTitle  : pageTitle
                    ,limit      : 50
                    ,start      : 0
                    ,printPDF   : 1
                }
                ,success	: function(response, action){
                    if( isGae == 1 ){
						window.open(route+'viewPDF/Reference Settings','_blank');
					}else{
						window.open('pdf/generalsettings/Reference Settings.pdf');
					}
                }
            });
		}
		
		function setComboValue( id, params, value ){
			var cmp = Ext.getCmp( id );
				cmp.getStore().load({
					params : params
					,callback: function(){
						cmp.setValue( parseInt( value, 10 ) );
					}
				});
		}

		function validateField( id, value  ){
			var cmp = Ext.getCmp( id )
			,label = (!value) ? cmp.fieldLabel + Ext.getConstant('REQ') + ':' : cmp.fieldLabel + ':';

			if( typeof cmp != 'undefined' ){
				cmp.reset();
				cmp.allowBlank = value;
				if( typeof cmp.labelEl != 'undefined' ) cmp.labelEl.update(label);
			}
		}

		function _validateCostCenter( module ){
			let affiliate = Ext.getCmp( 'idAffiliate' + module );
			if( affiliate.getValue() != null ) {
				let record = affiliate.getStore().findRecord(affiliate.valueField, affiliate.getValue() ).data
				,value = ( record.refTag == 1 ) ? false : true;

				validateField( 'idCostCenter' + configSeries.module, value );
			}
		}

		function _getAffiliateRecord( idAffiliate ){
			return new Promise( function(resolve, reject) {
				let args = {
					id          : 'idAffiliate'
					,value      : idAffiliate
					,tableName  : 'affiliate'
				}
	
				Ext.Ajax.request({
					url     : Ext.getConstant('STANDARD_ROUTE2') + 'getRecord'
					,params : args
					,success: function( response ){
						let resp = Ext.decode( response.responseText );
		
						if( typeof resp.view != undefined && resp.view != null ){
							resolve(resp.view);
						} else {
							reject(null);
						}
					}
				});
			});
		}

        return{
			initMethod:function( config ){
				route = config.route;
				baseurl = config.baseurl;
				module = config.module;
				canDelete = config.canDelete;
				canPrint = config.canPrint;
				pageTitle = config.pageTitle;
				isGae = config.isGae;
                canEdit = config.canEdit;
                config['canSave'] = config.canEdit;
				idModule = config.idmodule;
				idAffiliate = config.idAffiliate;
				
				return _mainPanel( config );
			}
		}
    }
}