/**
 * Developer    : Makmak
 * Module       : Adjustment Summary
 * Date         : Feb. 19, 2020
 * Finished     : 
 * Description  : 
 * DB Tables    : 
 * */

function Releasingsummary() {
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
                ,formItems      : [{
                    xtype		: 'container'
                    ,layout		: 'column'
                    ,style      : 'margin-bottom : 5px;'
                    ,items		: [
                        {
                            xtype			: 'container'
                            ,columnWidth	: .5
                            ,items			: __filterLeft()
                        }
                        ,{
                            xtype			: 'container'
                            ,columnWidth	: .5
                            ,items			: __filterRight()
                        }
                    ]
                }]
                ,moduleGrids    : __moduleGrid()
            } )
        } 

        function __filterLeft() {
            // STORE
            let referenceStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name']
                ,url        : route + 'getReferences'
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
                            Ext.getCmp( 'idItem' + module ).store.proxy.extraParams.idAffiliate = me.getValue();
                            Ext.getCmp( 'idCustomer' + module ).store.proxy.extraParams.idAffiliate = me.getValue();
                            
                            Ext.getCmp( 'idReference' + module ).store.load( {
                                callback   : function() {
                                    Ext.getCmp( 'idReference' + module ).setValue( 0 );
                                }
                            } )

                            Ext.getCmp( 'idItem' + module ).store.load( {
                                callback   : function() {
                                    Ext.getCmp( 'idItem' + module ).setValue( 0 );
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
                    ,fieldLabel     : 'Reference'
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

                // DATE AND TIME FIELD FOR "FROM"
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

                // DATE AND TIME FIELD FOR "TO"
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
                                    ,width          : 240
                                    ,maxValue       : new Date()
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
                                    ,value          : '11:45 PM'
                            })
                            ]
                        }
                    ]
                }
            ]
        }

        function __filterRight() {
            //STORE
            let classStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name']
                ,url        : route + 'getItemClass'
                ,startAt    :  0
                ,autoLoad   : true
            })

            let itemStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name']
                ,url        : route + 'getItems'
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
                // CLASSIFICATION COMBOBOX
                ,standards.callFunction( '_createCombo', {
                    id              : 'idItemClass' + module
                    ,hasAll         : 1
                    ,fieldLabel     : 'Classification'
                    ,allowBlank     : true
                    ,store          : classStore
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
                        ,select     : function() {
                            var me  = this;
                            Ext.getCmp( 'idItem' + module ).store.proxy.extraParams.idItemClass = me.getValue();
                            Ext.getCmp( 'idItem' + module ).store.load( {
                                callback   : function( ){
                                    Ext.getCmp( 'idItem' + module ).setValue( 0 );
                                }
                            } )
                        }
                    }
                } )

                // ITEM COMBOBOX
                ,standards.callFunction( '_createCombo', {
                    id              : 'idItem' + module
                    ,fieldLabel     : 'Item Name'
                    ,allowBlank     : true
                    ,store          : itemStore
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
                        ,select     : function() {
                            var me  = this;
                            Ext.getCmp( 'idCustomer' + module ).store.proxy.extraParams.idItem = me.getValue();
                            Ext.getCmp( 'idCustomer' + module ).store.load( {
                                callback   : function( ){
                                    Ext.getCmp( 'idCustomer' + module ).setValue( 0 );
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
            ]
        }

        function __moduleGrid() {
            let store   = standards.callFunction( '_createRemoteStore', {
                url     : route + 'getReleasingsummary'
                ,fields : [
                    'affiliateName'
                    ,'date'
                    ,'code'
                    ,'name'
                    ,'barcode'
                    ,'itemName'
                    ,'className'
                    ,'unitName'
                    ,{ name: 'qty'    ,type: 'number' }
                    ,{ name: 'cost'   ,type: 'number' }
                    ,{ name: 'price'  ,type: 'number' }
                    ,{ name: 'amount' ,type: 'number' }
                    ,'idInvoice'
                    ,'idModule'
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
                    {   header          : 'Affiliate'
                        ,dataIndex      : 'affiliateName'
                        ,width          : 140
                    }
                    ,{  header          : 'Date'
                        ,dataIndex      : 'date'
                        ,xtype          : 'datecolumn'
                        ,format         : 'm/d/Y h:i A'
                        ,width          : 130
                    }
                    ,{  header          : 'Reference'
                        ,dataIndex      : 'code'
                        ,width          : 70
                    }
                    ,{  header          : 'Customer'
                        ,dataIndex      : 'name'
                        ,width          : 150
                        ,minWidth       : 140
                    }
                    ,{  header          : 'Code'
                        ,dataIndex      : 'barcode'
                        ,width          : 60
                    }
                    ,{  header          : 'Item Name'
                        ,dataIndex      : 'itemName'
                        ,width          : 100
                    }
                    ,{  header          : 'Classification'
                        ,dataIndex      : 'className'
                        ,width          : 110
                    }
                    ,{  header          : 'Unit'
                        ,dataIndex      : 'unitName'
                        ,width          : 40
                    }
                    ,{  header          : 'Qty'
                        ,dataIndex      : 'qty'
                        ,width          : 50
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000'
                        ,sortable       : false
                        ,hasTotal       : true
                    }
                    ,{  header          : 'Cost'
                        ,dataIndex      : 'cost'
                        ,width          : 90
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,sortable       : false
                        ,hasTotal       : true
                    }
                    ,{  header          : 'Price'
                        ,dataIndex      : 'price'
                        ,width          : 90
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,sortable       : false
                        ,hasTotal       : true
                    }
                    ,{  header          : 'Amount'
                        ,dataIndex      : 'amount'
                        ,width          : 90
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,sortable       : false
                        ,hasTotal       : true
                    }
                ]
            });
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

            Ext.getCmp( 'idItem' + module ).store.proxy.extraParams.idItemClass = null;
            Ext.getCmp( 'idItem' + module ).store.proxy.extraParams.idAffiliate = null;
            Ext.getCmp( 'idItem' + module ).store.load( {
                callback   : function( ){
                    Ext.getCmp( 'idItem' + module ).setValue( 0 );
                }
            } )

            Ext.getCmp( 'idCustomer' + module ).store.proxy.extraParams.idItem = null;
            Ext.getCmp( 'idCustomer' + module ).store.proxy.extraParams.idAffiliate = null;
            Ext.getCmp( 'idCustomer' + module ).store.load( {
                callback   : function( ){
                    Ext.getCmp( 'idCustomer' + module ).setValue( 0 );
                }
            } )
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