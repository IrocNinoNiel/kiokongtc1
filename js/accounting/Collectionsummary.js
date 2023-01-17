/**
 * Developer    : Jayson Dagulo
 * Module       : Collection Summary
 * Date         : Jan 30, 2019
 * Finished     : 
 * Description  : This module allows authorized user to manually closes the journal entries.
 * DB Tables    : 
 * */ 

function Collectionsummary(){
    return function(){
        var baseurl, route, module, canDelete, pageTitle, canPrint, balanceModule, ledgerModule, isGae, idAffiliate;
    
        function _mainPanel( config ){
            var CashReceiptReferenceStore = standards.callFunction( '_createRemoteStore', {
                    fields      : [
                        {   name    : 'id'
                            ,type   : 'number'
                        }
                        ,'name'
                    ]
                    ,url        : route + 'getCashReceiptReferences'
                } )
                ,customerStore          = standards.callFunction( '_createRemoteStore', {
                    fields      : [
                        {   name    : 'id'
                            ,type   : 'number'
                        }
                        ,'name'
                        ,'paymentMethod'
                    ]
                    ,url        : route + 'getCustomer'
                } )
                ,paymentTypeStore       = standards.callFunction( '_createLocalStore', {
                    data        : [ 'All', 'Cash', 'Charge' ]
                    ,startAt    : 0
                } );

            return standards.callFunction( '_mainPanel', {
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
                ,formItems          : [
                    {   xtype   : 'container'
                        ,layout : 'column'
                        ,style  : 'margin-bottom : 5px;'
                        ,items  : [
                            {   xtype           : 'container'
                                ,columnWidth    : .5
                                ,minWidth       : 365
                                ,items          : [
                                    // AFFILIATE 
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
                                            ,select     : function() {
                                                var me  = this;
                                                Ext.getCmp( 'idReference' + module ).store.proxy.extraParams.idAffiliate = me.getValue();
                                                Ext.getCmp( 'idReference' + module ).store.load( {
                                                    callback   : function( ){
                                                        Ext.getCmp( 'idReference' + module ).setValue( 0 );
                                                    }
                                                } )
                                                
                                                Ext.getCmp( 'idCustomer' + module ).store.proxy.extraParams.idAffiliate = me.getValue();
                                                Ext.getCmp( 'idCustomer' + module ).store.load( {
                                                    callback   : function( ){
                                                        Ext.getCmp( 'idCustomer' + module ).setValue( 0 );
                                                    }
                                                } )
                                            }
                                        }
                                    } )

                                    // REFERENCE
                                    ,standards.callFunction( '_createCombo', {
                                        id              : 'idReference' + module
                                        ,fieldLabel     : 'Reference'
                                        ,valueField     : 'id'
                                        ,displayField   : 'name'
                                        ,allowBlank     : true
                                        ,store          : CashReceiptReferenceStore
                                        ,listeners      : {
                                            afterrender : function( me ){
                                                me.store.load( {
                                                    callback    : function(){
                                                        me.setValue( 0 )
                                                    }
                                                } )
                                            }
                                        }
                                    } )

                                    //DATE AND TIME RANGE
                                    ,{
                                        xtype : 'container'
                                        ,layout: 'column'
                                        ,width  : 550
                                        ,items: [
                                            {
                                                xtype : 'container'
                                                ,columnWidth : .4
                                                ,items : [
                                                    standards.callFunction( '_createDateField', {
                                                        id              : 'datefrom' + module
                                                        ,fieldLabel     : 'Date and Time From'
                                                        ,allowBlank     : true
                                                        ,width          : 240
                                                        ,value          : Ext.date().subtract(1, 'month').toDate()
                                                        ,maxValue       : new Date()
                                                        ,listeners      : {
                                                            change: function() {
                                                                var from = this;
                                                                var to = Ext.getCmp( 'dateto' + module );
                                                                if (from.value > to.value) {
                                                                    Ext.getCmp( 'dateto' + module ).setValue( from.value );
                                                                }
                                                            }
                                                        }
                                                    })
                                                ]
                                            },
                                            {
                                                xtype : 'container'
                                                ,columnWidth : .6
                                                ,items : [
                                                    standards.callFunction( '_createTimeField', {
                                                        id              : 'timefrom' + module
                                                        ,fieldLabel     : 'to'
                                                        ,allowBlank     : true
                                                        ,labelWidth     : 20
                                                        ,width          : 131
                                                        ,value          : '12:00 AM'
                                                    })
                                                ]
                                            }
                                        ]
                                    }
                                    ,{
                                        xtype : 'container'
                                        ,layout: 'column'
                                        ,width: 550
                                        ,items: [
                                            {
                                                xtype : 'container'
                                                ,columnWidth : .4
                                                ,items : [
                                                    standards.callFunction( '_createDateField', {
                                                        id              : 'dateto' + module
                                                        ,fieldLabel     : 'Date and Time To'
                                                        ,allowBlank     : true
                                                        ,maxValue       : new Date()
                                                        ,width          : 240
                                                        ,listeners      : {
                                                            change: function() {
                                                                var to = this;
                                                                var from = Ext.getCmp( 'datefrom' + module );
                                                                if (from.value > to.value) {
                                                                    Ext.getCmp( 'datefrom' + module ).setValue( to.value );
                                                                }
                                                            }
                                                        }
                                                    })
                                                ]
                                            },
                                            {
                                                xtype : 'container'
                                                ,columnWidth : .6
                                                ,items : [
                                                    standards.callFunction( '_createTimeField', {
                                                        id              : 'timeto' + module
                                                        ,fieldLabel     : 'to'
                                                        ,allowBlank     : true
                                                        ,labelWidth     : 20
                                                        ,width          : 131
                                                })
                                                ]
                                            }
                                        ]
                                    }
                                ]
                            }
                            // RIGHT FILTER : CUSTOMER , PAYMENT METHOD
                            ,{  xtype           : 'container'
                                ,columnWidth    : .5
                                ,minWidth       : 365
                                ,items          : [
                                    standards.callFunction( '_createCombo', {
                                        id              : 'idCustomer' + module
                                        ,fieldLabel     : 'Customer'
                                        ,store          : customerStore
                                        ,valueField     : 'id'
                                        ,displayField   : 'name'
                                        ,listeners      : {
                                            afterrender : function( me ){
                                                me.store.load( {
                                                    callback    : function(){
                                                        me.setValue( 0 )
                                                    }
                                                } )
                                            }
                                            ,select     : function( me , rec ){
                                                Ext.getCmp( 'payMode' + module ).setValue( parseInt( rec[0].data.paymentMethod ) );        
                                            }
                                        }
                                    } )
                                    ,standards.callFunction( '_createCombo', {
                                        id              : 'payMode' + module
                                        ,fieldLabel     : 'Payment Type'
                                        ,store          : paymentTypeStore
                                        ,valueField     : 'id'
                                        ,displayField   : 'name'
                                        ,value          : 0
                                    } )
                                ]
                            }
                        ]
                    }
                ]
                ,moduleGrids    : _gridCollection()
            } );
        }

        function _gridCollection(){
            var store   = standards.callFunction( '_createRemoteStore', {
                fields      : [
                    'affiliateName'
                    ,'date'
                    ,'reference'
                    ,'customerName'
                    ,'remarks'
                    ,'payMode'
                    ,'bankName'
                    ,'chequeNo'
                    ,{  name    : 'amount'
                        ,type   : 'number'
                    }
                    ,'idInvoice'
                    ,'idModule'
                ]
                ,url        : route + 'getCollectionSummary'
            } );
            
            return standards.callFunction( '_gridPanel', {
                id          : 'gridCollectionSummary' + module
                ,module     : module
                ,store      : store
                ,tbar       : {
                    content     : ''
                }
                ,viewConfig: {
                    listeners: {
                        itemdblclick: function(dataview, record, item, index, e) {
                            mainView.openModule( record.data.idModule , record.data, this );
                        }
                    }
                }
                ,noDefaultRow : true
                ,noPage     : true
                ,plugins    : true
                ,columns    : [
                    {   header          : 'Affiliate'
                        ,dataIndex      : 'affiliateName'
                        ,width          : 150
                        ,columnWidth    : 8
                    }
                    ,{  header          : 'Date'
                        ,dataIndex      : 'date'
                        ,xtype          : 'datecolumn'
                        ,format         : 'm/d/Y'
                        ,width          : 100
                        ,columnWidth    : 7
                    }
                    ,{  header          : 'Reference'
                        ,dataIndex      : 'reference'
                        ,width          : 100
                        ,columnWidth    : 8
                    }
                    ,{  header          : 'Customer Name'
                        ,dataIndex      : 'customerName'
                        ,width          : 150
                        ,columnWidth    : 15
                    }
                    ,{  header          : 'Remarks'
                        ,dataIndex      : 'remarks'
                        ,minWidth       : 150
                        ,flex           : 1
                        ,columnWidth    : 15
                    }
                    ,{  header          : 'Type'
                        ,dataIndex      : 'payMode'
                        ,width          : 100
                        ,columnWidth    : 7
                    }
                    ,{  header          : 'Bank'
                        ,dataIndex      : 'bankName'
                        ,width          : 150
                        ,columnWidth    : 8
                    }
                    ,{  header          : 'Cheque No'
                        ,dataIndex      : 'chequeNo'
                        ,width          : 100
                        ,columnWidth    : 8
                    }
                    ,{  header          : 'Amount'
                        ,dataIndex      : 'amount'
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,width          : 100
                        ,columnWidth    : 8
                    }
                ]
            } )
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
            var _grid               = Ext.getCmp( 'gridCollectionSummary' + module );

            standards.callFunction( '_listPDF', {
                grid                    : _grid
                ,customListPDFHandler   : function(){
                    var par = standards.callFunction( 'getFormDetailsAsObject', {
                        module          : module
                        ,getSubmitValue : true
                    } );
                    par.title               = pageTitle;
                    console.log(par);
                    Ext.Ajax.request( {
                        url         : route + 'printPDF'
                        ,params     : par
                        ,success    : function(res){
                            if( isGae ){
                                window.open( route + 'viewPDF/' + par.title , '_blank' );
                            }
                            else{
                                window.open( baseurl + 'pdf/accounting/' + par.title + '.pdf');
                            }
                        }
                    } );
                }
            } );
        }

        function _printExcel(){
            var _grid = Ext.getCmp( 'gridCollectionSummary' + module );

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
                            window.open( route + "download/" + par.title + '/accounting');
                        }
                    } );
                }
            } );
        }

        return{
			initMethod  : function( config ){
				route		    = config.route;
				baseurl		    = config.baseurl;
				module		    = config.module;
				canPrint	    = config.canPrint;
				canDelete	    = config.canDelete;
				canEdit		    = config.canEdit;
				pageTitle       = config.pageTitle;
				idModule	    = config.idmodule;
				
				return _mainPanel( config );
			}
		}
    }
}