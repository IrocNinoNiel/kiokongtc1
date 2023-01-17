/**
 * Developer: Hazel Alegbeleye
 * Module: Unit Settings
 * Date: December 4, 2019
 * Finished: December 4, 2019
 * Description: This module allows the authorized users to set ( add, edit and delete) the unit.
 * DB Tables: unit, item
 * */
function Unitsettings(){
    return function(){
        var baseurl, route, module, canDelete, pageTitle, canPrint, isGae, onEdit = 1 , idCostCenter = "";

        function _init( ) {
            // Ext.Ajax.request({
            //     url         : route + 'getUnitCode'
            //     ,success    : function( response ){
            //         var resp = Ext.decode( response.responseText );
            //         Ext.getCmp('unitCode' + module).setValue( resp.view[0].idUnit );
            //     } 
            // });
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
                        ,id     : 'idUnit' + module
                        ,value  : 0
                    }
                    ,standards.callFunction( '_createTextField' ,{
                            id          : 'unitCode' + module
                            ,fieldLabel : 'Unit Code'
                            ,allowBlank : false
                            // ,maskRe     : /[^a-z,A-Z]/
                            ,listeners  : {
                                afterrender : function() {
                                    _init( );
                                }
                            }
                        } 
                    )
                    ,standards.callFunction( '_createTextField' ,{
                            id          : 'unitName' + module
                            ,fieldLabel : 'Unit Name'
                            ,allowBlank : false
                            ,maxLength  : 50
                        } 
                    )
                ]
                ,moduleGrids : _grdUnit ()
            });
        }
        
        function _grdUnit() {

            var store = standards.callFunction(  '_createRemoteStore' ,{
                fields  : [ { type: 'number', name: 'idUnit' }, 'unitCode', 'unitName' ]
                ,url    : route + 'getUnits'
            });

            function _editRecord( data ){
                onEdit = 0;
                
                module.getForm().retrieveData({
					url     : route + 'retrieveData'
					,method	: 'post'
					,params	: {
						idUnit	: data.idUnit
					}
					,success	: function( response ) {
                        
					}
				});
            }

            function _deleteRecord( data ){
                standards.callFunction( '_createMessageBox', {
					msg	    : 'DELETE_CONFIRM'
					,action : 'confirm'
					,fn	    : function( btn ){
						if( btn == 'yes' ) {
							Ext.Ajax.request({
								url	    : route + 'deleteUnit'
								,params	: {
									idUnit : data.idUnit
								}
								,success : function( response ) {
									var resp = Ext.decode( response.responseText );

									standards.callFunction( '_createMessageBox', {
										msg	: ( resp.match == 1 ) ? 'DELETE_USED' : 'DELETE_SUCCESS'
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
								,emptyText	: 'Search unit name...'
								,listeners	: {
									change	: function(me, newVal, oldVal) {
										store.proxy.extraParams = {
											filterValue	: newVal
										};
										store.load( {
											callback    : function(){
												store.currentPage = 1;
											}
										} );
									}
								}
							} 
						)
                        ,{   xtype      : 'button'
                            ,iconCls    : 'glyphicon glyphicon-refresh'
                            ,handler    : function(){
                                Ext.getCmp('searchFilterValue' + module ).reset();
                                store.proxy.extraParams = {};
                                store.load( {
                                    callback    : function(){
                                        store.currentPage = 1;
                                    }
                                } );
                            }
						}
                        ,'->'
                        // ,{	xtype		: 'button'
						// 	,iconCls	: 'excel'
						// 	,tooltip	: 'Export to Excel'
						// 	,handler	: function(){
						// 		_printExcel();
						// 	}
						// }
						// ,{	xtype		: 'button'
						// 	,iconCls	: 'pdf-icon'
						// 	,tooltip	: 'Export to PDF'
						// 	,handler	: function(){
						// 		_printPDF();
						// 	}
						// }
                    ]
				}
				,columns: [
					{	header          : 'Unit Code'
						,dataIndex      : 'unitCode'
						,width          : 200
						,columnWidth    : 30
					}
					,{	header          : 'Unit Name'
						,dataIndex      : 'unitName'
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
						window.open(route+'viewPDF/Unit Settings','_blank');
					}else{
						window.open('pdf/settings/Unit Settings.pdf');
					}
                }
            });
        }

        function _printExcel(){
            if( canPrint ) {
				Ext.Ajax.request({
					url: route + 'printExcel'
					,params: {
						idmodule    : 7	
						,pageTitle  : pageTitle
						,limit      : 50
						,start      : 0
					}
					,success: function(res){
						var path  = route.replace( baseurl, '' );
                        window.open(baseurl + path + 'download' + '/' + pageTitle);
					}
				});
				
			} else {
				standards.callFunction( '_createMessageBox', {
					msg : 'You are currently not authorized to print, please contact the administrator.'
				});
			}
        }

        function _saveForm( form ){
            var params = {
                onEdit          : onEdit
                ,idUnit    : Ext.getCmp('idUnit' + module ).getValue()
                ,unitName      : Ext.getCmp('unitName' + module ).getValue()
                ,unitCode      : Ext.getCmp('unitCode' + module ).getValue().replace(/^0+/, '')
            }

            Ext.Ajax.request({
                url         : route + 'saveUnit'
                ,params     : params
                ,success    : function ( response ){
                    var resp =  Ext.decode( response.responseText );

                    if( resp.match == 1 ) {
                        standards.callFunction( '_createMessageBox', {
                            msg	    : 'Unit code already exist. Would you like to create a new one?'
                            ,action : 'confirm'
                            ,fn	    : function(btn){
                                if( btn == 'yes' ){
                                    _init();
                                }
                            }
                        } )
                    } else {
                        standards.callFunction( '_createMessageBox', {
                            msg	: 'SAVE_SUCCESS'
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