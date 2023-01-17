/**
 * Developer: Hazel Alegbeleye
 * Module: Receiving Summary
 * Date: January 17, 2020
 * Finished: 
 * Description: 
 * DB Tables: 
 * */ 
function Receivingsummary(){
    return function(){
        var baseurl, route, module, canDelete, pageTitle, canPrint, monitoringModule, module, isGae, idAffiliate;

        function _mainPanel(config){

            var supplierStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name']
                ,url: Ext.getConstant( 'STANDARD_ROUTE2' ) + 'getSupplier'
                ,startAt    :  0
                ,autoLoad   : true
            }), poStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name']
                ,url: route + 'getPO'
                ,startAt    :  0
                ,autoLoad   : true
            }), classificationStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name']
                ,url        : route + 'getItemClassifications'
                ,startAt    :  0
                ,autoLoad   : true
            });

            return standards.callFunction(	'_mainPanel' ,{
                config		: config
                ,moduleType	: 'report'
                ,tbar       : {
					noFormButton        : true
                    ,noListButton       : true
                    ,noPDFButton        : false
                    ,PDFHidden          : false
                    ,formPDFHandler     : _printPDF
                    ,formExcelHandler   : _printExcel
                }
                ,formItems      : [
                    standards2.callFunction( '_createAffiliateCombo', {
                        module      : module
                        ,id         : 'Affiliate' + module
                        ,hasAll     : 1
                        ,allowBlank : true
                        ,listeners :{
                            afterrender : function(){
                                var me  = this;
                                me.store.load( {
                                    callback    : function(){
                                        if( me.store.data.length > 1 ) {
                                            me.setValue( 0 );
                                        } else {
                                            me.setValue( parseInt(Ext.getConstant('AFFILIATEID'),10) );
                                        }
                                       
                                    }
                                } );
                            }
                        }
                    })
                    ,standards.callFunction( '_createCombo', {
                        id              : 'referenceNum' + module
                        ,fieldLabel     : 'Reference'
                        ,allowBlank     : true
                        ,store          : poStore
                        ,displayField   : 'name'
                        ,valueField     : 'id'
                        ,value          : 0
                        ,listeners      : {
                            beforeQuery : function(){
                                let affiliate = Ext.getCmp("Affiliate" + module )
                                    ,costCenter = Ext.getCmp("idCostCenter" + module )
                                    ,params = [];

                                if( affiliate.getValue() != 0 ) params['idAffiliate'] = affiliate.getValue();
                                // if( costCenter.getValue() != 0 ) params['idCostCenter'] = costCenter.getValue();
                                
                                if( params != [] ) this.getStore().proxy.extraParams = params;
                                this.getStore().load({});
                            }
                        }
                    } )
                    ,standards.callFunction( '_createCombo', {
                        id              : 'idItemClass' + module
                        ,fieldLabel     : 'Classification'
                        ,allowBlank     : true
                        ,store          : classificationStore
                        ,displayField   : 'name'
                        ,valueField     : 'id'
                        ,value          : 0
                    } )
                    ,standards.callFunction( '_createDateTime', {
                        module          : module
                        ,dFieldLabel    : 'Date From'
                        ,tstyle         : 'margin-left: 5px;'
                        ,tWidth         : 105
                        ,tId            : 'timefrom' + module
                        ,dId            : 'sdate' + module
                        ,dValue         : Ext.date().subtract(1, 'month').toDate()
                        ,tValue         : new Date(new Date().setHours(0,0,0,0))
                    })
                    ,standards.callFunction( '_createDateTime', {
                        module          : module
                        ,dFieldLabel    : 'Date To'
                        ,tstyle         : 'margin-left: 5px;'
                        ,tWidth         : 105
                        ,tId            : 'timeto' + module
                        ,dId            : 'edate' + module
                    })
                ]
                ,moduleGrids    : [ monitoringGrid() ]
                ,listeners  : {
                    afterrender: function(){
                        _setDefaultValue( poStore, 'referenceNum' );

                        // supplierStore.proxy.extraParams.hasAll = 1;
                        // _setDefaultValue( supplierStore, 'idSupplier' );

                        classificationStore.proxy.extraParams.hasAll = 1;
                        _setDefaultValue( classificationStore, 'idItemClass' );
                    }
                }
            })

            function _setDefaultValue( store, id ) {
                store.load({
                    callback: function(){
                        Ext.getCmp( id + module ).setValue(0);
                    }
                });
            }

            function monitoringGrid(){
                var store = standards.callFunction(  '_createRemoteStore' ,{
                    fields:[
                        'affiliateName'
                        ,'costCenterName'
                        ,'locationName'
                        ,'date'
                        ,'supplierName'
                        ,'className'
                        ,'itemName'
                        , { name: 'cost', type: 'number' }
                        , { name: 'qty', type: 'number' }
                        , { name: 'total', type: 'number' }
                        ,'referenceNumber'
                    ], 
                    url: route + "getReceivingList"
                });

                return standards.callFunction( '_gridPanel',{
                    id          : 'poGrid' + module
                    ,module     : module
                    ,store      : store
                    ,tbar       : {}
                    ,noDefaultRow : true
                    ,noPage     : true
                    ,plugins    : true
                    ,style      :'margin-top:10px;'
                    ,columns    : [
                        {	header      : 'Affiliate'
                            ,dataIndex  : 'affiliateName'
                            ,minWidth   : 150
                            ,flex       : 1
                        }
                        ,{	header      : 'Cost Center'
                            ,dataIndex  : 'costCenterName'
                            ,minWidth   : 110
                            ,flex       : 1
                        }
                        ,{	header      : 'Date'
                            ,dataIndex  : 'date'
                            ,width      : 90
                            ,xtype      : 'datecolumn'
                            ,format     : Ext.getConstant('DATE_FORMAT')
                        }
                        ,{	header      : 'Reference'
                            ,dataIndex  : 'referenceNumber'
                            ,width      : 90
                        }
                        ,{	header      : 'Supplier'
                            ,dataIndex  : 'supplierName'
                            ,width      : 90
                        }
                        ,{	header      : 'Item Name'
                            ,dataIndex  : 'itemName'
                            ,width      : 90
                        }
                        ,{	header      : 'Classification'
                            ,dataIndex  : 'className'
                            ,width      : 90
                        }
                        ,{	header      : 'Cost'
                            ,dataIndex  : 'cost'
                            ,xtype      : 'numbercolumn'
                            ,width      : 90
                            ,hasTotal   : true
                        }
                        ,{	header      : 'Quantity'
                            ,dataIndex  : 'qty'
                            ,xtype      : 'numbercolumn'
                            ,width      : 90
                            ,hasTotal   : true
                        }
                        ,{	header      : 'Total'
                            ,dataIndex  : 'total'
                            ,width      : 90
                            ,xtype      : 'numbercolumn'
                            ,format     : '0,000.00'
                            ,hasTotal   : true
                        }
                    ]
                    
                });
            }
        }

        function _printPDF(){
            var _grid               = Ext.getCmp( 'poGrid' + module );

            standards.callFunction( '_listPDF', {
                grid                    : _grid
                ,customListPDFHandler   : function(){
                    var par = standards.callFunction( 'getFormDetailsAsObject', {
                        module          : module
                        ,getSubmitValue : true
                    } );
                    par.title           = pageTitle;
                    
                    Ext.Ajax.request( {
                        url         : route + 'printPDF'
                        ,params     : par
                        ,success    : function(res){
                            if( isGae ){
                                window.open( route + 'viewPDF/' + par.title , '_blank' );
                            }
                            else{
                                window.open( baseurl + 'pdf/inventory/' + par.title + '.pdf');
                            }
                        }
                    } );
                }
            } );
        }

        function _printExcel(){
            var _grid = Ext.getCmp( 'poGrid' + module );

            standards.callFunction( '_listExcel', {
                grid                    : _grid
                ,customListExcelHandler : function(){
                    var par = standards.callFunction( 'getFormDetailsAsObject', {
                        module          : module
                        ,getSubmitValue : true
                    } );
                    par.title = pageTitle;
                    
                    Ext.Ajax.request( {
                        url         : route + 'printExcel'
                        ,params     : par
                        ,success    : function(res){
                            window.open( route + "download/" + par.title + '/inventory');
                        }
                    } );
                }
            } );
        }


        return{
			initMethod:function( config ){
				route		= config.route;
				baseurl		= config.baseurl;
				module		= config.module;
				canPrint	= config.canPrint;
				canDelete	= config.canDelete;
				canEdit		= config.canEdit;
				pageTitle   = config.pageTitle;
				idModule	= config.idmodule
				isGae		= config.isGae;
                idAffiliate = config.idAffiliate
				
				return _mainPanel( config );
			}
		}
    }
}