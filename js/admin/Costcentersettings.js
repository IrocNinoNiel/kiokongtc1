/**
 * Developer: Hazel Alegbeleye
 * Module: Cost Center Settings
 * Date: Oct 29, 2019
 * Finished: 
 * Description: 
 * DB Tables: 
 * */ 
function Costcentersettings(){
    return function(){
        var baseurl, route, module, canDelete, pageTitle, canPrint, isGae, onEdit = 0, idCostCenter = "";

		function _mainPanel( config ){

            var affiliateStore = standards.callFunction( '_createRemoteStore', {
                fields      : [ 'idAffiliate', 'affiliateName',{ name:'chk', type:'bool' } ]
                ,url        : Ext.getConstant('STANDARD_ROUTE2') + 'getAffiliates'
            } );

            var sm = new Ext.selection.CheckboxModel( {
                checkOnly   : true
            } );

            return standards.callFunction(	'_mainPanel' ,{
                config		: config
                ,moduleType	: 'form'
                ,tbar : {
                    saveFunc        : 	_saveForm
                    ,resetFunc      : _resetForm
                    ,noPDFButton    : true
                    ,noExcelButton  : true
                    ,noFormButton   : true
                    ,noListButton   : true
                }
                ,formItems:[
                    {
                        xtype       :'container'
                        ,layout     :'column'
                        ,style      : 'margin-bottom: 10px;'
                        ,items      :[   
                            {
                                xtype   : 'container'
                                ,items  :[
                                    ,standards.callFunction( '_createTextField', {
                                        fieldLabel      : 'Cost Center'
                                        ,id             : 'costCenterName' + module
                                        ,width          : 440
                                        ,maxLength      : 50
                                        ,labelWidth     : 135
                                        ,style          : 'margin-bottom: 5px;'
                                        ,allowBlank     : false
                                    } )
                                    ,{  xtype           : 'container'
                                        ,columnWidth    : .4
                                        ,layout         : 'hbox'
                                        ,items          : [
                                            {   xtype   : 'container'
                                                ,html   : 'Affiliate<span style="color:red;">*</span>:'
                                                ,width  : 140
                                            }
                                            ,standards.callFunction( '_gridPanel', {
                                                id          : 'grdAffiliates' + module
                                                ,module     : module
                                                ,store      : affiliateStore
                                                ,height     : 150
                                                ,width      : 300
                                                ,selModel   : sm
                                                ,plugins    : true
                                                ,noPage     : true
                                                ,tbar       : { }
                                                ,columns    : [
                                                    {   header      : 'Affiliate Name'
                                                        ,dataIndex  : 'affiliateName'
                                                        ,flex       : 1
                                                        ,minWidth   : 100
                                                        ,renderer 	: function( val, params, record, row_index ){
                                                            if( record.data.chk ){
                                                                sm.select( row_index, true );
                                                            }
                                                            return val;
                                                        }
                                                    }
                                                ]
                                                ,listeners : {
                                                    afterrender : function() {
                                                        affiliateStore.load({});
                                                    }
                                                }
                                            } )
                                        ]
                                    }
                                ]
                            }
                            ,standards.callFunction( '_createTextArea', {
                                id          : 'remarks' + module
                                ,fieldLabel : 'Remarks'
                                ,width      : 380
                                ,maxLength  : 50
                                ,labelWidth : 115
                                ,style      : 'margin-left : 200px'
                            } )
                        ]
                    }
                    ,{  xtype   : 'container'
                        ,style  : 'padding:5px;margin-top:10px;'
                        ,items  : [
                            _gridHistory( config )
                        ]   
                    }
                ]
			} );
        }

        function _saveForm( form ){

            var rows = Ext.getCmp('grdAffiliates' + module).getSelectionModel().getSelection();

            var params = {
                costCenterName  : Ext.getCmp('costCenterName' + module).getValue()
                ,remarks        : Ext.getCmp('remarks' + module).getValue()
                ,status         : 1
                ,idCostCenter   : idCostCenter
                ,onEdit         : onEdit 
            }

            if( rows.length > 0 ) {
                Ext.Ajax.request({
                    url     : route + 'saveCostCenter'
                    ,params : params
                    ,method : 'post'
                    ,success: function( response ){
                        var resp = Ext.decode( response.responseText );
                        
                        if( resp.view.match == 0 ){
                            var idCostCenter = resp.view.idCostCenter;
                        
                            if( typeof rows !== 'undefined' ) {
                                rows.map( (col, i) => {
                                    _saveAffiliates( {
                                        idCostCenter    : idCostCenter
                                        ,idAffiliate    : rows[i].data.idAffiliate
                                    });
                                });
                            }
        
                            standards.callFunction( '_createMessageBox', {
                                msg: 'Record has been successfully saved.'
                                ,fn: function(){
                                    _resetForm( form );
        
                                    Ext.getCmp('gridHistory' + module).store.load();
                                }
                            } )
                        } else {
                            standards.callFunction( '_createMessageBox', {
                                msg: 'Cost Center name already exists. Please select a different name.'
                            } )
                        }
                         
                    }
                });
            } else {
                standards.callFunction( '_createMessageBox', {
                    msg: 'Invalid action. Please select atleast one affiliate.'
                } );
            }

            
        }

        function _saveAffiliates( params ) {
            Ext.Ajax.request({
                url     : route + 'saveCostAffiliate'
                ,params : params
                ,method : 'post'
                ,success: function( response ){}
            });
        }

        function _resetForm( form ){
            form.reset();


            /**Affiliate**/
            var affiliateGrd = Ext.getCmp('grdAffiliates' + module);
            var selection = affiliateGrd.getView().getSelectionModel().getSelection()
            affiliateGrd.store.remove(selection); // all selections
            affiliateGrd.store.load({});
            Ext.getCmp('gridHistory' + module).store.load({});

            onEdit = 0;
            idCostCenter = "";
        }

        function _printExcel(){
            if( canPrint ) {
				Ext.Ajax.request({
					url: route + 'printCostCenterExcel'
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

        function _printPDF(){

            Ext.Ajax.request({
                url: route + 'generateCostCenterPDF'
                ,method:'post'
                ,params:{
                    idmodule    : 7
                    ,pageTitle  : pageTitle
                    ,limit      : 50
                    ,start      : 0
                    ,printPDF   : 1
                }
                ,success:function(response, action){
                    if( isGae == 1 ){
						window.open(route+'viewPDF/Cost Center List','_blank');
					}else{
						window.open('pdf/admin/Cost Center List.pdf');
					}
                }
            });
        }

        function _editRecord( data ) {
            module.getForm().retrieveData( {
				url     : route + 'getCostCenter'
				,method : 'post'
				,params : {
                    idCostCenter    : data.idCostCenter
                }
				,success:function( response ){
                    onEdit = 1;
                    idCostCenter = response.idCostCenter;

                    if( response.idAffiliate != null ) {
                        var affiliates = response.idAffiliate.split(",", response.idAffiliate.length );
                        
                        var gridAffiliate   = Ext.getCmp('grdAffiliates' + module)
                            ,store          = gridAffiliate.getStore()
                            ,grdSM          = gridAffiliate.getSelectionModel();
                
                        store.proxy.extraParams.affiliates = Ext.encode( affiliates );
                        store.load({
                            callback: function(){
                                var items = store.data.items;

                                items.map( (col, i) => {
                                    affiliates.map( (idAffiliate) => {
                                        if( idAffiliate == col.data.idAffiliate ){
                                            grdSM.select( i, true );
                                        }
                                    } )
                                })
                            }
                        });
                    }
                    
				}
			} )
        }

        function _deleteRecord( data ) {
            standards.callFunction( '_createMessageBox', {
                msg		: 'DELETE_CONFIRM'
                ,action	: 'confirm'
                ,fn		: function( btn ){
                    if( btn == 'yes' ){
                        Ext.Ajax.request({
                            url: route + 'deleteCostCenter'
                            ,params: {
                                idCostCenter : data.idCostCenter
                            }
                            ,method: 'post'
                            ,success: function( response ){
                                var resp = Ext.decode( response.responseText);
                                standards.callFunction( '_createMessageBox', {
                                    msg : ( resp.view == 0 ) ? 'DELETE_SUCCESS' : 'DELETE_USED'
                                });

                                _resetForm( module.getForm() );
                                Ext.getCmp('gridHistory' + module).store.load();
                            }
                        })
                    }
                }
            })
        }

        function _gridHistory( config ) {

            var costCenterStore = standards.callFunction( '_createRemoteStore', {
                fields      : [ 'idCostCenter','costCenterName', 'status' ]
                ,url        : route + 'getCostCenters'
            } ), statusStore = standards.callFunction( '_createLocalStore' ,{
                data:['Active','Inactive']
                ,startAt: 1
            })

            return standards.callFunction( '_gridPanel',{
                id		: 'gridHistory' + module
                ,module	: module
                ,store	: costCenterStore
                ,style  : 'margin-top:15px'
                ,tbar   : {
                    filter : {
                        searchURL           : route + 'getSearchCostCenter'
                        ,emptyText          : 'Search cost center...'
                        ,config	            : config
                    }
                    ,canPrint               : canPrint
                    ,customListPDFHandler   : _printPDF
                    ,customListExcelHandler : _printExcel
                    ,route                  : route
                    ,pageTitle              : pageTitle
                    
                }
                ,columns: [
                    {	header          : 'Cost Center Name'
                        ,dataIndex      : 'costCenterName'
                        ,flex           : 1
                        ,minwidth       : 80
                        ,columnWidth    : 100
                    }
                    ,{	header: 'Status'
                        ,dataIndex: 'status'
                        ,width: 200
                        ,sortable: false
                        ,editor: standards.callFunction( '_createCombo', {
                            id: 'status' + module
                            ,module	: module
                            ,store  : statusStore
                            ,valueField: 'name'
                            ,displayField: 'name'
                            ,emptyText: ''
                            ,fieldLabel: ''
                            ,labelWidth: 0
                            ,listeners: {
                                change  : function( field, value ) {
                                    var data = Ext.getCmp('gridHistory' + module).selModel.getSelection()[0].data;

                                    Ext.Ajax.request({
                                        url     : route + 'updateCostCenter'
                                        ,params : {
                                            idCostCenter    : data.idCostCenter
                                            ,costCenterName : data.costCenterName
                                            ,status         : ( value == 'Active' ? 1 : 2 )
                                        }
                                        ,method : 'post'
                                        ,success: function( response ){}
                                    });
                                }

                            }
                        } )
                    }
                    ,standards.callFunction( '_createActionColumn', {
                        icon            : 'pencil'
                        ,tooltip        : 'Edit record'
                        ,width          : 30
                        ,canEdit        : canEdit
                        ,Func           : _editRecord
                    })
                    ,standards.callFunction( '_createActionColumn' ,{
                        canDelete       : canDelete
                        ,icon           : 'remove'
                        ,tooltip        : 'Remove record'
                        ,width          : 30
                        ,Func           : _deleteRecord
                    })
                ]
                ,plugins: true
                ,listeners:{
                    afterrender : function(){
                        costCenterStore.load({})
                    }
                }
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
				moduleID = config.idmodule;
				
				return _mainPanel( config );
			}
		}
    }
}