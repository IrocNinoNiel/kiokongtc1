/**
 * Developer    : Jays
 * Module       : Itemized Profit Loss
 * Date         : Feb. 17, 2020
 * Finished     : 
 * Description  : This module allows the authorized user to generate and print the per-item
 *                transactions and identify the profit or loss of every project.
 * DB Tables    : 
 * */ 
function Itemizedprofitloss(){
    return function(){
        var baseurl, route, module, canDelete, pageTitle, idModule, isGae,isSaved = 0
            ,canSave;
        
        function _mainPanel( config ){
            var itemStore       = standards.callFunction( '_createRemoteStore', {
                fields      : [
                    {   name    : 'id'
                        ,type   : 'number'
                    }
                    ,'name'
                ]
                ,url        : route + 'getItems'
            } )
            return standards.callFunction(	'_mainPanel' ,{
				config              : config
				,moduleType         : 'report'
                ,afterResetHandler  : _resetForm
				,tbar               : {
					PDFHidden           : false
					,formPDFHandler     : _printPDF
					,formExcelHandler   : _printEXCEL
				}
				,formItems          : [
                    standards2.callFunction( '_createAffiliateCombo', {
                        hasAll      : 1
                        ,module     : module
                        ,allowBlank : true
                        ,listeners  : {
                            afterrender : function(){
                                var me  = this;
                                me.store.load( {
                                    callback    : function(){
                                        me.setValue( 0 );
                                    }
                                } )
                            }
                            ,select     : function(){
                                var me  = this;
                                Ext.getCmp( 'idItem' + module ).store.proxy.extraParams.idAffiliate = me.getValue();
                                Ext.getCmp( 'idItem' + module ).store.load( {
                                    callback    : function( ){
                                        Ext.getCmp( 'idItem' + module ).setValue( 0 );
                                    }
                                } )
                            }
                        }
                    } )
                    ,standards.callFunction( '_createCombo', {
                        id              : 'idItem' + module
                        ,fieldLabel     : 'Item Name'
                        ,valueField     : 'id'
                        ,displayField   : 'name'
                        ,store          : itemStore
                        ,listeners      : {
                            afterrender : function(){
                                var me  = this;
                                me.store.load( {
                                    callback    : function(){
                                        me.setValue( 0 )
                                    }
                                } )
                            }
                        }
                    } )
                    ,standards.callFunction( '_createDateTime', {
                        module          : module
                        ,dFieldLabel    : 'Date From'
                        ,tWidth         : 105
                        ,tstyle         : ''
                        ,dStyle         : 'margin-right: 5px;'
                        ,tId            : 'stime' + module
                        ,dId            : 'sdate' + module
                        ,dValue         : Ext.date().subtract(1, 'month').toDate()
                        ,tValue         : '12:15 AM'
                    } )
                    ,standards.callFunction( '_createDateTime', {
                        module          : module
                        ,dFieldLabel    : 'Date To'
                        ,tstyle         : ''
                        ,dStyle         : 'margin-right: 5px;'
                        ,tWidth         : 105
                        ,tId            : 'etime' + module
                        ,dId            : 'edate' + module
                        ,tValue         : '11:45 PM'
                    } )
                ]
                ,moduleGrids        : _moduleGrid()
            } )
        }

        function _moduleGrid(){
            var store   = standards.callFunction( '_createRemoteStore', {
                fields      : [
                    'affiliateName'
                    ,'date'
                    ,'code'
                    ,'name'
                    ,'barcode'
                    ,'itemName'
                    ,'unitName'
                    ,{  name    : 'qty'
                        ,type   : 'number'
                    }
                    ,{  name    : 'cost'
                        ,type   : 'number'
                    }
                    ,{  name    : 'price'
                        ,type   : 'number'
                    }
                    ,{  name    : 'costAmount'
                        ,type   : 'number'
                    }
                    ,{  name    : 'priceAmount'
                        ,type   : 'number'
                    }
                    ,{  name    : 'profitLoss'
                        ,type   : 'number'
                    }
                    ,'idModule'
                ]
                ,url        : route + 'getItemizedProfitLossReport'
            } );
            return standards.callFunction( '_gridPanel', {
                id              : 'gridReport' + module
				,module         : module
                ,store          : store
                ,noDefaultRow   : true
                ,tbar           : {
                    content     : ''
                }
                ,features       : {
                    ftype   : 'summary'
                }
                ,noPage         : true
                ,plugins        : true
                ,listeners      : {
                    itemdblclick: function(dataview, record, item, index, e) {
                        if( parseInt( record.data.idModule, 10 ) > 0 ) mainView.openModule( record.data.idModule , record.data, this );
                    }
                }
                ,columns        : [
                    {   header          : 'Affiliate'
                        ,dataIndex      : 'affiliateName'
                        ,width          : 150
                    }
                    ,{  header          : 'Date'
                        ,dataIndex      : 'date'
                        ,width          : 150
                        ,xtype          : 'datecolumn'
                        ,format         : 'm/d/Y h:i A'
                    }
                    ,{  header          : 'Reference'
                        ,dataIndex      : 'code'
                        ,width          : 100
                    }
                    ,{  header          : 'Customer'
                        ,dataIndex      : 'name'
                        ,flex           : 1
                        ,minWidth       : 150
                    }
                    ,{  header          : 'Code'
                        ,dataIndex      : 'barcode'
                        ,width          : 100
                    }
                    ,{  header          : 'Item Name'
                        ,dataIndex      : 'itemName'
                        ,width          : 150
                    }
                    ,{  header          : 'Unit of Measure'
                        ,dataIndex      : 'unitName'
                        ,width          : 120
                    }
                    ,{  header          : 'Qty'
                        ,dataIndex      : 'qty'
                        ,width          : 100
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000'
                        ,sortable       : false
                    }
                    ,{  header          : 'Cost'
                        ,dataIndex      : 'cost'
                        ,width          : 100
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,sortable       : false
                    }
                    ,{  header          : 'Price'
                        ,dataIndex      : 'price'
                        ,width          : 100
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,sortable       : false
                    }
                    ,{  header          : 'Cost Amount'
                        ,dataIndex      : 'costAmount'
                        ,width          : 100
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,sortable       : false
                    }
                    ,{  header          : 'Price Amount'
                        ,dataIndex      : 'priceAmount'
                        ,width          : 100
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,sortable       : false
                    }
                    ,{  header          : 'Profit (Loss)'
                        ,dataIndex      : 'profitLoss'
                        ,width          : 100
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,sortable       : false
                        ,hasTotal       : true
                        ,negativeColor  : 'red'
                    }
                ]
            } );
        }

        function _resetForm(){
            Ext.getCmp( 'idAffiliate' + module ).store.load( {
                callback    : function(){
                    Ext.getCmp( 'idAffiliate' + module ).setValue( 0 );
                }
            } );
            Ext.getCmp( 'idItem' + module ).store.proxy.extraParams.idAffiliate = null
            Ext.getCmp( 'idItem' + module ).store.load( {
                callback    : function(){
                    Ext.getCmp( 'idItem' + module ).setValue( 0 );
                }
            } )
        }

        function _printPDF(){
            var _grid   = Ext.getCmp( 'gridReport' + module );

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

        function _printEXCEL(){
            var _grid = Ext.getCmp( 'gridReport' + module );

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
                canDelete	= config.canDelete;
                pageTitle   = config.pageTitle;
                idModule	= config.idmodule
                isGae		= config.isGae;
                idAffiliate = config.idAffiliate;
                canSave     = config.canSave;
                
                return _mainPanel( config );
            }
        }
    }
}