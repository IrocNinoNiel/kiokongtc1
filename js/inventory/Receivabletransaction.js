/**
 * Developer    : Makmak
 * Module       : Receivable Transaction
 * Date         : Feb. 20, 2020
 * Finished     : 
 * Description  : 
 * DB Tables    : 
 * */

function Receivabletransaction() {
    return function(){
        var baseurl, route, module, canDelete, pageTitle, canPrint, monitoringModule, module, isGae, idAffiliate;
        
        function _mainPanel(config){
            //MAIN PANEL
            return standards.callFunction(	'_mainPanel' ,{
                config		: config
                ,moduleType	: 'report'
                ,afterResetHandler : _resetForm
                ,tbar       : {
					noFormButton        : true
                    ,noListButton       : true
                    ,noPDFButton        : false
                    ,PDFHidden          : false
                    ,formPDFHandler     : _printPDF
                    ,formExcelHandler   : _printExcel
                }
                ,formItems      : _filters()
                ,moduleGrids    : _moduleGrid()
            } )
        }

        function _filters() {
            // STORE
            let referenceStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name']
                ,url        : route + 'getReferences'
                ,startAt    :  0
                ,autoLoad   : true
            })

            let customerStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name']
                ,url        : route + 'getCustomers'
                ,startAt    :  0
                ,autoLoad   : true
            })

            return [
                //AFFIALTE COMBOBOX
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
                            Ext.getCmp( 'idReference' + module ).store.proxy.extraParams.idAffiliate = me.getValue();
                            Ext.getCmp( 'idCustomer' + module ).store.proxy.extraParams.idAffiliate = me.getValue();

                            Ext.getCmp( 'idReference' + module ).store.load( {
                                callback   : function() {
                                    Ext.getCmp( 'idReference' + module ).setValue( 0 );
                                }
                            } )

                            Ext.getCmp( 'idCustomer' + module ).store.load( {
                                callback   : function() {
                                    Ext.getCmp( 'idCustomer' + module ).setValue( 0 );
                                }
                            } )
                        }
                    }
                } )

                //REFERENCE COMBOBOX
                ,standards.callFunction( '_createCombo', {
                    id              : 'idReference' + module
                    ,hasAll         : 1
                    ,fieldLabel     : 'Sales Reference'
                    ,valueField     : 'id'
                    ,displayField   : 'name'
                    ,store          : referenceStore
                    ,listeners      : {
                        afterrender : function(){
                            var me  = this;
                            me.store.load( {
                                callback    : function(){
                                    me.setValue( 0 );
                                }
                            } )
                        }
                    }
                } )

                // CUSTOMER COMBOBOX
                ,standards.callFunction( '_createCombo', {
                    id              : 'idCustomer' + module
                    ,fieldLabel     : 'Customer'
                    ,allowBlank     : true
                    ,store          : customerStore
                    ,displayField   : 'name'
                    ,valueField     : 'id'
                    ,value          : 0
                    ,listeners      : {
                        afterrender : function(){
                            var me  = this;
                            me.store.load( {
                                callback    : function(){
                                    me.setValue( 0 );
                                }
                            } )
                        }
                    }
                } )

                // DATERANGE COMBOBOX
                ,{
                    xtype : 'container'
                    ,layout: 'column'
                    ,width  : 470
                    ,items: [
                        standards.callFunction( '_createDateField', {
                            id              : 'sdate' + module
                            ,fieldLabel     : 'Date'
                            ,allowBlank     : true
                            ,width          : 230
                            ,value          : Ext.date().subtract(1, 'month').toDate()
                            ,maxValue       : new Date()
                            ,listeners      : {
                                change: function() {
                                    var from = this;
                                    var to = Ext.getCmp( 'edate' + module );
                                    if (from.value > to.value) {
                                        Ext.getCmp( 'edate' + module ).setValue( from.value );
                                    }
                                }
                            }
                        })
                        ,standards.callFunction( '_createDateField', {
                            id              : 'edate' + module
                            ,fieldLabel     : 'to'
                            ,allowBlank     : true
                            ,style          : { margin: '0 0 8px 5px' }
                            ,labelWidth     : 15
                            ,width          : 116
                            ,maxValue       : new Date()
                            ,listeners      : {
                                change: function() {
                                    var to = this;
                                    var from = Ext.getCmp( 'sdate' + module );
                                    if (from.value > to.value) {
                                        Ext.getCmp( 'sdate' + module ).setValue( to.value );
                                    }
                                }
                            }
                        })
                    ]
                }

                // CHECKBOX
                ,standards.callFunction( '_createCheckField', {
                    id          : 'hideZero' + module
                    ,boxLabel   : 'Hide transaction with no balances'
                    ,checked    : 'true'
                })
            ]
        }

        function _moduleGrid() {
            let store   = standards.callFunction( '_createRemoteStore', {
                url     : route + 'getReceivableTransactions'
                ,fields : [
                    'date'
                    ,'affiliateName'
                    ,'code'
                    ,'customerName'
                    ,'salesMan'
                    ,{ name: 'amount'   ,type: 'number' }
                    ,'idModule'
                    ,'idInvoice'
                ]
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
                ,viewConfig: {
                    listeners: {
                        itemdblclick: function(dataview, record, item, index, e) {
                            mainView.openModule( record.data.idModule , record.data, this );
                        }
                    }
                }
                ,noPage         : true
                ,plugins        : true
                ,columns        : [
                    {  header           : 'Date'
                        ,dataIndex      : 'date'
                        ,width          : 100
                        ,sortable       : false
                        ,xtype          : 'datecolumn'
                        ,format         : 'm/d/Y'
                    }
                    ,{   header         : 'Affiliate'
                        ,dataIndex      : 'affiliateName'
                        ,width          : 183
                        ,sortable       : false
                    }
                    ,{  header          : 'Reference'
                        ,dataIndex      : 'code'
                        ,width          : 140
                        ,sortable       : false
                    }
                    ,{  header          : 'Customer'
                        ,dataIndex      : 'customerName'
                        ,minWidth       : 183
                        ,flex           : 1
                        ,sortable       : false
                    }
                    ,{  header          : 'Salesman'
                        ,dataIndex      : 'salesMan'
                        ,width          : 183
                        ,sortable       : false
                    }
                    ,{  header          : 'Amount'
                        ,dataIndex      : 'amount'
                        ,width          : 183
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,sortable       : false
                        ,hasTotal       : true
                    }
                ]
            });
        }

        function _resetForm(){
            Ext.getCmp( 'idAffiliate' + module ).store.load( {
                callback    : function(){
                    Ext.getCmp( 'idAffiliate' + module ).setValue( 0 );
                }
            } );
            Ext.getCmp( 'idReference' + module ).store.proxy.extraParams.idAffiliate = null
            Ext.getCmp( 'idReference' + module ).store.load( {
                callback    : function(){
                    Ext.getCmp( 'idReference' + module ).setValue( 0 );
                }
            } )
            Ext.getCmp( 'idCustomer' + module ).store.proxy.extraParams.idAffiliate = null
            Ext.getCmp( 'idCustomer' + module ).store.load( {
                callback    : function(){
                    Ext.getCmp( 'idCustomer' + module ).setValue( 0 );
                }
            } )
        }

        function _printPDF(){
            var _grid               = Ext.getCmp( 'gridReport' + module );

            standards.callFunction( '_listPDF', {
                grid                    : _grid
                ,customListPDFHandler   : function(){
                    var par = standards.callFunction( 'getFormDetailsAsObject', {
                        module          : module
                        ,getSubmitValue : true
                    } );
                    par.title               = pageTitle;
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