/**
 * Developer: Hazel Alegbeleye
 * Module: Location Settings
 * Date: December 4, 2019
 * Finished: December 4, 2019
 * Description: This module allows authorized user to set up the location that will be used in transactions.
 * DB Tables: location, affiliate
 * */ 
function Locationsettings(){
    return function(){
        var baseurl, route, module, canDelete, pageTitle, canPrint, isGae, onEdit = 1 , idCostCenter = "";

        function _init( ) {
            Ext.Ajax.request({
                url         : route + 'getLocationCode'
                ,success    : function( response ){
                    var resp = Ext.decode( response.responseText );
                    Ext.getCmp('locationCode' + module).setValue( resp.view[0].idLocation );
                } 
            });
        }

		function _mainPanel( config ){

            return standards.callFunction(	'_mainPanel' ,{
                moduleType	    : 'form'
                ,config         : config
                ,tbar           : {
                    saveFunc        : _saveForm
                    ,resetFunc      : _resetForm
                    ,noFormButton   : true
                    ,noListButton   : true
                }
                ,formItems:[
                    {
                        xtype   : 'hiddenfield'
                        ,id     : 'idLocation' + module
                        ,value  : 0
                    }
                    ,standards.callFunction( '_createTextField' ,{
                            id          : 'locationCode' + module
                            ,fieldLabel : 'Location Code'
                            ,allowBlank : true
                            ,readOnly   : true
                            ,listeners  : {
                                afterrender : function() {
                                    _init( );
                                }
                            }
                        } 
                    )
                    ,standards.callFunction( '_createTextField' ,{
                            id          : 'locationName' + module
                            ,fieldLabel : 'Location Name'
                            ,allowBlank : false
                            ,maxLength  : 50
                        } 
                    )
                ]
                ,moduleGrids : _grdLocation ()
            });
        }
        
        function _grdLocation() {

            var store = standards.callFunction(  '_createRemoteStore' ,{
                fields:[ { type: 'number', name: 'idLocation' }, 'locationCode', 'locationName' ]
                ,url: route + 'getLocations'
            });

            function _editRecord( data ){
                onEdit = 0;
                
                module.getForm().retrieveData({
					url     : route + 'retrieveData'
					,method	: 'post'
					,params	: {
						idLocation	: data.idLocation
					}
					,success	: function( response ) {
                        
					}
				});
            }

            function _deleteRecord( data ){
                standards.callFunction( '_createMessageBox', {
					msg	: 'DELETE_CONFIRM'
					,action: 'confirm'
					,fn	: function( btn ){
						if( btn == 'yes' ) {
							Ext.Ajax.request({
								url	: route + 'deleteLocation'
								,params	: {
									idLocation : data.idLocation
								}
								,success : function( response ) {
									var resp = Ext.decode( response.responseText );

									standards.callFunction( '_createMessageBox', {
										msg	: resp.view.msg
										,fn	: function(){
											Ext.getCmp( 'gridHistory' + module ).store.load({});
										}
									} );
									
								}
							});
						}
					}
				} );
            }

            return standards.callFunction( '_gridPanel',{
                id			: 'gridHistory' + module
                ,style      : 'margin-top: 20px;'
				,module		: module
				,store		: store
				,plugins	: true
				,tbar 		: {
					canPrint 				: canPrint
					,noExcel 				: true
					,route 					: route
					,pageTitle 				: pageTitle
					,customListPDFHandler   : _printPDF
					,content: [
						standards.callFunction( '_createTextField' ,{
								id          : 'searchFilterValue' + module
								,allowBlank : true
								,width		: 200
								,emptyText	: 'Search location name...'
								,listeners	: {
									change	: function(me, newVal, oldVal) {
										store.proxy.extraParams = {
											filterValue	: newVal
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
                        ,{   xtype : 'button'
                            ,iconCls: 'glyphicon glyphicon-refresh'
                            ,handler: function(){
                                Ext.getCmp('searchFilterValue' + module ).reset();
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
								_printPDF();
							}
						}
                    ]
				}
				,columns: [
					{	header          : 'Location Code'
						,dataIndex      : 'locationCode'
						,width          : 200
						,columnWidth    : 30
					}
					,{	header          : 'Location Name'
						,dataIndex      : 'locationName'
						,flex           : 1
						,minWidth       : 50
						,columnWidth    : 40
					}
					,standards.callFunction( '_createActionColumn', {
						icon            : 'pencil'
						,tooltip        : 'Edit record'
						,Func        : _editRecord
					})
					,standards.callFunction( '_createActionColumn' ,{
						canDelete       : canDelete
						,icon           : 'remove'
						,tooltip        : 'Remove record'
						,Func : _deleteRecord
					})
				]
				,listeners	: {
					afterrender : function() {
						store.load({});
					}
				}
			});
        }

        function _printPDF( ){

            Ext.Ajax.request({
                url			: route + 'generatePDF'
                ,method		:'post'
                ,params		: {
                    idmodule    : 7
                    ,pageTitle  : pageTitle
                    ,limit      : 50
                    ,start      : 0
                    ,printPDF   : 1
                }
                ,success	: function(response, action){
                    if( isGae == 1 ){
						window.open(route+'viewPDF/Location Settings','_blank');
					}else{
						window.open('pdf/generalsettings/Location Settings.pdf');
					}
                }
            });
        }

        function _saveForm( form ){
            var params = {
                onEdit              : onEdit
                ,idLocation         : Ext.getCmp('idLocation' + module ).getValue()
                ,locationCode       : Ext.getCmp('locationCode' + module ).getValue().replace(/^0+/, '')
                ,locationName       : Ext.getCmp('locationName' + module ).getValue()
            }

            Ext.Ajax.request({
                url         : route + 'saveLocation'
                ,params     : params
                ,success    : function ( response ){
                    var resp =  Ext.decode( response.responseText );

                    if( resp.view.match == 2 ) {
                        standards.callFunction( '_createMessageBox', {
                            msg	    : resp.view.msg
                            ,action : 'confirm'
                            ,fn	    : function(btn){
                                if( btn == 'yes' ){
                                    _init();
                                }
                            }
                        } )
                    } else {
                        standards.callFunction( '_createMessageBox', {
                            msg	: resp.view.msg
                            ,fn	: function(){
                                _resetForm( form );
                            }
                        } )
                    }

                }
            });
        }

        function _resetForm( form ) {
            onEdit = 1;

            _init();
            form.reset();
            Ext.getCmp('gridHistory' + module).store.load({});
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
				moduleID = config.idmodule;
				
				return _mainPanel( config );
			}
		}
    }
}