/**
 * Developer: Hazel Alegbeleye
 * Module: Classification Settings
 * Date: December 4, 2019
 * Finished: December 4, 2019
 * Description: This module allows authorized users to set (add, edit and delete) an item classification. 
 * DB Tables: itemclassification, item
 * */
function Classificationsettings(){
    return function(){
        var baseurl, route, module, canDelete, pageTitle, canPrint, isGae, onEdit = 1 , idCostCenter = "";

        function _init( ) {
            Ext.Ajax.request({
                url         : route + 'getclassCode'
                ,success    : function( response ){
                    var resp = Ext.decode( response.responseText );
                    Ext.getCmp('classCode' + module).setValue( resp.view[0].idItemClass );
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
                ,listeners:{
                    afterrender: function(){
                        Ext.getCmp('mainTbar'+module).add(2, {
                            xtype:	'button',
                            id:		'import_button_tbar'+module,
                            text:   'Import Item Classifications from Excel',
                            iconCls:'glyphicon glyphicon-upload',
                            handler: function(){
                                IMPORT();
                            }
                        })
                    }
                }
                ,formItems:[
                    {
                        xtype   : 'hiddenfield'
                        ,id     : 'idItemClass' + module
                        ,value  : 0
                    }
                    ,standards.callFunction( '_createTextField' ,{
                            id          : 'classCode' + module
                            ,fieldLabel : 'Classification Code'
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
                            id          : 'className' + module
                            ,fieldLabel : 'Classification Name'
                            ,allowBlank : false
                            ,maxLength  : 20
                        } 
                    )
                ]
                ,moduleGrids : _grdClassification ()
            });
        }

        /*FUNCTION IMPORT RECORD FORM EXCEL*/
	function IMPORT(){
		Ext.create('Ext.window.Window', {
			title: 	'Import Items from Excel',
			id:	'import_window'+module,
			modal:	true,
			autoHeight:true,
			autoWidth: 	true,
			resizable:false,
			bodyPadding:10,
			layout:	'fit',
			items:[
				{	xtype: 	'form',
					id:		'import_form'+module,
					baseCls:'x-plain',
					frame:false,
					border:false,
					buttonAlign:'center',
					items:[
						{	xtype: 'label'
							,html: 'Excel file must follow a specific format. You may download the file <a href="#" id="download_format_excel">here</a>.'
							,listeners:{
								afterrender: function(){
									document.getElementById("download_format_excel").onclick = function(){
										window.open(route+'download_format');
									}
								}
							},
						}
						,{	xtype: 	'fileuploadfield',
							width:  400,
							fieldLabel:'File',
							labelWidth:20,
							name: 	'file_import'+module,
							id:		'file_import'+module,
							style:	'margin-top:3px',
							buttonConfig: {
								text: '',
								iconCls: 'glyphicon glyphicon-folder-open',
							},
							msgTarget:'under',
							validator: function(value){
								try{
									if(value){
										var file = this.getEl().down('input[type=file]').dom.files[0];
										var exp  = /^.*\.(xlsx|XLSX|xls|XLS|csv)$/;
										if(exp.test(value)){
											if(parseInt(file.size) > (2 * (1024 * 1000))){
												return 'Exceed file upload limit.';
											}
											else{
												return true;
											}
										}
										else{
											return 'Invalid file format.';
										}
									}
									else return false;
								}catch(er){	console.log(er);}
							}
						}
					],
					buttons:[
						{	text: 'Import', 
							formBind:true,
							disabled:true,
							handler: function(){
								var form   = Ext.getCmp('import_form'+module).getForm();
								fileName = Ext.getCmp('file_import'+module).getValue();
								form.submit({
									waitTitle: "Please wait",
									waitMsg: "Submitting data...", 
									method:'post',
									url: route + 'IMPORT',
									params:{module:module},
									success:function(res,response){
                                        var resp =  Ext.decode( response.response.responseText );
                                        if( resp.view.match == 2 ) {
                                            standards.callFunction( '_createMessageBox', {
                                                msg	    : resp.view.msg
                                                ,action : 'confirm'
                                            } )
                                        } else {
                                            standards.callFunction( '_createMessageBox', {
												msg	: resp.view.msg
												,style:'height:max-content'
                                                ,fn	: function(){
                                                    _resetForm( form );
                                                }
                                            } )
                                        }
										// var ret = Ext.decode(response.response.responseText);
										// console.log( response );
										// if(ret.not_exist != '0'){
										// 	standards.IN({
										// 		func:'setMessageBox',
										// 		params:{msg: ret.not_exist+' column does not exist.'}
										// 	});
										// 	return;
										// }
										
										// if( ret.error.length > 0 ){
										// 	IMPORT_ERROR_GRID(ret.error);
										// 	Ext.getCmp('import_window'+module).destroy(true); 
										// }else{
										// 		Ext.getCmp('import_window'+module).destroy(true); 
										// 		Ext.getCmp('grid'+module).store.load(); 
										// 		standards.IN({
										// 			func:'setMessageBox',
										// 			params:{msg: 'Item(s) successfully imported.'}
										// 		});
										// }
									},
									failure:function(){
										standards.callFunction( '_createMessageBox', {
                                            msg		: 'Database connectivity error: Failure during restoration of record.'
                                            ,icon	: 'Error'
                                        });
									}
								});
							} 
						}
					]
				}
			]
		}).show();
	}
        
        function _grdClassification() {

            var store = standards.callFunction(  '_createRemoteStore' ,{
                fields  : [ { type: 'number', name: 'idItemClass' }, 'classCode', 'className' ]
                ,url    : route + 'getItemClassifications'
            });

            function _editRecord( data ){
                onEdit = 0;
                
                module.getForm().retrieveData({
					url     : route + 'retrieveData'
					,method	: 'post'
					,params	: {
						idItemClass	: data.idItemClass
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
								url	    : route + 'deleteClassification'
								,params	: {
									idItemClass : data.idItemClass
								}
								,success : function( response ) {
									var resp = Ext.decode( response.responseText );

									standards.callFunction( '_createMessageBox', {
										msg	: ( resp.match == 1 ) ? 'DELETE_USED' : "DELETE_SUCCESS"
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
								,emptyText	: 'Search classification name...'
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
						// 	,iconCls	: 'pdf-icon'
						// 	,tooltip	: 'Export to PDF'
						// 	,handler	: function(){
						// 		_printPDF();
						// 	}
						// }
                    ]
				}
				,columns: [
					{	header          : 'Classification Code'
						,dataIndex      : 'classCode'
						,width          : 200
						,columnWidth    : 30
					}
					,{	header          : 'Classification Name'
						,dataIndex      : 'className'
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
						window.open(route+'viewPDF/Classification Settings','_blank');
					}else{
						window.open('pdf/settings/Classification Settings.pdf');
					}
                }
            });
        }

        function _saveForm( form ){
            var params = {
                onEdit          : onEdit
                ,idItemClass    : Ext.getCmp('idItemClass' + module ).getValue()
                ,className      : Ext.getCmp('className' + module ).getValue()
                ,classCode      : Ext.getCmp('classCode' + module ).getValue().replace(/^0+/, '')
            }

            Ext.Ajax.request({
                url         : route + 'saveClassification'
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