/**
 * Developer: Hazel Alegbeleye
 * Module: PO Monitoring
 * Date: January 16, 2020
 * Finished: 
 * Description: 
 * DB Tables: 
 * */ 
function Pomonitoring(){
    return function(){
        var baseurl, route, module, canDelete, pageTitle, canPrint, monitoringModule, ledgerModule, isGae, idAffiliate;

        function _mainPanel( config ){

            
            return standards.callFunction(	'_mainPanel' ,{
                config		        : config
                ,moduleType	        : 'report'
                ,customPDFHandler   : _pdfHandler
                ,customExcelHandler : _excelHandler
                ,tbar : {
                    noPDFButton    : false
                    ,noExcelButton  : false
                    ,PDFHidden      : false
                }
                ,extraFormTab : [
					{	xtype           : 'button'
						,buttonId       : 'btnMonitoring' + module
                        ,activeButton   : true
                        ,buttonIconCls  : 'modu'
						,buttonLabel    : 'PO Monitoring'
						,items          : poMonitoring()
                    }
					,{	xtype           : 'button'
						,buttonId       : 'btnLedger' + module
						,buttonIconCls  : 'list'
						,buttonLabel    : 'PO Ledger'
						,items          : poLedger()
					}
				]
            } );
            
            function poMonitoring(){

                var groupingFeature = Ext.create('Ext.grid.feature.Grouping',{
                    groupHeaderTpl: '{name} ({rows.length} Item{[values.rows.length > 1 ? "s" : ""]})'
                });

                var filterStore = standards.callFunction( '_createLocalStore' , {
                    data    : [
                        'All'
                        ,'Supplier'
                        ,'Item'
                    ]
                    ,startAt : 0
                } ), statusStore = standards.callFunction( '_createLocalStore' , {
                    data    : [
                        'All'
                        ,'Complete'
                        ,'Incomplete'
                        ,'Not Served'
                        ,'Cancelled'
                    ]
                    ,startAt : 0
                } ), supplierStore = standards.callFunction(  '_createRemoteStore' ,{
                        fields:[ {	name : 'id', type : 'number' }, 'name']
                        ,url: Ext.getConstant( 'STANDARD_ROUTE2' ) + 'getSupplier'
                        ,startAt    :  0
                        ,autoLoad   : true
                }), itemStore = standards.callFunction(  '_createRemoteStore' ,{
                    fields:[ {	name : 'id', type : 'number' }, 'name']
                    ,url: route + 'getItems'
                    ,startAt    :  0
                    ,autoLoad   : true
                })
                
                return standards.callFunction('_formPanel',{
                    moduleType      : 'report'
                    ,panelID        : 'monitoringForm' + module
                    ,noHeader       : true
                    ,module         : module
                    ,formItems      : [
                        {
                            xtype   : 'container'
                            ,style  : 'margin-bottom:10px;'
                            ,items  : [
                                standards2.callFunction( '_createAffiliateCombo', {
                                    module      : module
                                    ,id         : 'Affiliate' + monitoringModule
                                    ,allowBlank : true
                                    ,hasAll     : 1
                                    ,value      : 0
                                    ,listeners      : {
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
                                        }, select : function() {
                                            var filterKey = Ext.getCmp( 'filterKey' + monitoringModule );
                                            filterKey.fireEvent( 'select' );
                                        }
                                    }
                                })
                                ,standards2.callFunction( '_createCostCenter', {
                                    module          : monitoringModule
                                    ,idAffiliate    : parseInt( idAffiliate , 10 )
                                    ,allowBlank     : true
                                    ,hasAll         : 1
                                    ,value          : 0
                                } )
                                ,{
                                    xtype : 'container'
                                    ,layout : 'column'
                                    ,style  : 'margin-bottom: 5px;'
                                    ,items		: [
                                        standards.callFunction( '_createCombo', {
                                            id              : 'filterKey' + monitoringModule
                                            ,fieldLabel     : 'Search By'
                                            ,store          : filterStore
                                            ,emptyText		: 'Search By'
                                            ,displayField   : 'name'
                                            ,valueField     : 'id'
                                            ,value          : 0
                                            ,width          : 210
                                            ,style          : 'margin-right: 5px;'
                                            ,listeners      : {
                                                change : function( combo ) {
                                                    var filterValue = Ext.getCmp( 'filterValue' + monitoringModule )
        
                                                    if( typeof filterValue != 'undefined' ){
                                                        filterValue.reset();
                                                        filterValue.setReadOnly( this.getValue() == 0 );
                                                        if( _getFilterValueStore() !== '' ) filterValue.bindStore( _getFilterValueStore() );
                                                    }

                                                    
                                                }
                                            }
                                        } )
                                        ,standards.callFunction( '_createCombo', {
                                            id              : 'filterValue' + monitoringModule
                                            ,store          : supplierStore
                                            ,emptyText		: 'Select filter...'
                                            ,displayField   : 'name'
                                            ,valueField     : 'id'
                                            // ,value          : 1
                                            ,width          : 135
                                            ,hideTrigger    : false
                                            ,readOnly       : true
                                            ,listeners      : {
                                                beforeQuery : function(){
                                                    var filterKey = Ext.getCmp( 'filterKey' + monitoringModule ).getDisplayValue();
        
                                                    if( this.getStore().getCount() <= 0 ) 
                                                    standards.callFunction('_createMessageBox',{ msg: `No ${filterKey} found for the selected Affiliate.` });
                                                }
                                            }
                                        } )
                                    ]
                                }
                                ,standards.callFunction( '_createCombo', {
                                    id              : 'status' + monitoringModule
                                    ,fieldLabel     : 'Status'
                                    ,store          : statusStore
                                    ,displayField   : 'name'
                                    ,valueField     : 'id'
                                    ,value          : 0
                                } )
                                ,standards.callFunction( '_createDateField', {
                                    id              : 'sdate' + monitoringModule
                                    ,fieldLabel     : 'As of:'
                                } )
                            ]
                        }
                    ]
                    ,moduleGrids    : monitoringGrid()
                    ,listeners      : {
                        afterrender : function(){}
                    }
                });

                function monitoringGrid(){
                    var store = standards.callFunction(  '_createRemoteStore' ,{
                        fields:[
                            'affiliateName'
                            ,'name'
                            ,'costCenterName'
                            ,'date'
                            ,'itemName'
                            ,'unit'
                            ,'expectedQty'
                            ,'actualQty'
                            ,'balance'
                            ,'referenceNum'
                            ,'className'
                            ,'status'
                            ,'idAffiliate'
                            ,'idSupplier'
                            ,'idCostCenter'
                            ,'idItem'
                            ,'idUnit'
                            ,'idInvoice'
                            ,'idItemClass'
                            ,'poNumber'
                        ], 
                        groupField: 'poNumber',
                        url: route + "getPOList/monitoring"
                    });
    
                    return standards.callFunction( '_gridPanel',{
                        id              : 'poGrid' + module
                        ,module         : module
                        ,store          : store
                        ,tbar           : {}
                        ,noDefaultRow   : true
                        ,noPage         : true
                        ,grouping       : [groupingFeature]
                        ,style          :'margin-top:10px;'
                        ,columns    :[
                            {	header      : 'Affiliate'
                                ,dataIndex  : 'affiliateName'
                                ,minWidth   : 150
                                ,flex       : 1
                                ,sortable   : false
                            }
                            ,{	header      : 'Cost Center'
                                ,dataIndex  : 'costCenterName'
                                ,width      : 90
                                ,sortable   : false
                            }
                            ,{	header      : 'Date'
                                ,dataIndex  : 'date'
                                ,width      : 90
                                ,sortable   : false
                                ,xtype      : 'datecolumn'
                                ,format     : Ext.getConstant('DATE_FORMAT')
                            }
                            ,{	header      : 'PO No.'
                                ,dataIndex  : 'poNumber'
                                ,width      : 90
                                ,sortable   : false
                            }
                            ,{	header      : 'Supplier'
                                ,dataIndex  : 'name'
                                ,width      : 90
                                ,sortable   : false
                            }
                            ,{	header      : 'Item Classification'
                                ,dataIndex  : 'className'
                                ,minWidth   : 110
                                ,flex       : 1
                                ,sortable   : false
                            }
                            ,{	header      : 'Item Name'
                                ,dataIndex  : 'itemName'
                                ,width      : 90
                                ,sortable   : false
                            }
                            ,{	header      : 'Unit'
                                ,dataIndex  : 'unit'
                                ,width      : 90
                                ,sortable   : false
                            }
                            ,{	header      : 'Expected Qty'
                                ,dataIndex  : 'expectedQty'
                                ,xtype      : 'numbercolumn'
                                ,format     : '0,000.00'
                                ,width      : 90
                                ,sortable   : false
                            }
                            ,{	header      : 'Actual Qty'
                                ,dataIndex  : 'actualQty'
                                ,xtype      : 'numbercolumn'
                                ,format     : '0,000.00'
                                ,width      : 90
                                ,sortable   : false
                            }
                            ,{	header      : 'Balance'
                                ,dataIndex  : 'balance'
                                ,width      : 90
                                ,xtype      : 'numbercolumn'
                                ,format     : '0,000.00'
                                ,sortable   : false
                            }
                            ,{	header      : 'Status'
                                ,dataIndex  : 'status'
                                ,width      : 90
                                ,sortable   : false
                            }
                            ,standards.callFunction( '_createActionColumn', {
                                icon : 'th-list'
                                ,tooltip : 'View Ledger'
                                ,Func : function(rec){

                                    Ext.getCmp('btnLedger'+module).handler();
                                    
                                    var ledgerFields = [
                                        {  ledgerField : 'Affiliate',       value: 'idAffiliate' }
                                        ,{ ledgerField : 'idCostCenter',    value: 'idCostCenter' }
                                        ,{ ledgerField : 'poNumber',        value: 'idInvoice' }
                                        ,{ ledgerField : 'idItem',          value: 'idItem' }
                                        ,{ ledgerField : 'idItemClass',     value: 'idItemClass' }
                                        ,{ ledgerField : 'idSupplier',      value: 'idSupplier' }
                                    ];

                                    let sdate = Ext.getCmp( 'sdate' + monitoringModule )
                                        ,ledgerEdate = Ext.getCmp( 'edate' + ledgerModule )

                                        ledgerEdate.setValue( sdate.getValue() );
                                    
                                    ledgerFields.map( item => {
                                        var field = Ext.getCmp( item.ledgerField + ledgerModule );

                                        field.getStore().load({
                                            callback: function(){
                                                field.setValue( parseInt(rec[item.value],10) );
                                                Ext.getCmp('viewButton' + ledgerModule).handler();
                                            }
                                        });
                                    })
                                }
                            })
                        ]
                        
                    });
                }

                function _getFilterValueStore(){
                    var filterKey = Ext.getCmp( 'filterKey' + monitoringModule )
                        ,idAffiliate = Ext.getCmp( 'Affiliate' + monitoringModule ).getValue()
                        ,store = '';

                        console.log

                    switch( filterKey.getValue() ) {
                        case 1: //Supplier
                            if( idAffiliate != 0 ) supplierStore.proxy.extraParams.idAffiliate = idAffiliate
                            supplierStore.load({});
                            store = supplierStore;
                            break;
                        case 2: //Item
                            if( idAffiliate != 0 ) itemStore.proxy.extraParams.idAffiliate = idAffiliate
                            itemStore.load({});
                            store = itemStore;
                            break;
                    }

                    return store;
                }

            }

            function poLedger(){
                var supplierStore = standards.callFunction(  '_createRemoteStore' ,{
                    fields      :[ {	name : 'id', type : 'number' }, 'name']
                    ,url: Ext.getConstant( 'STANDARD_ROUTE2' ) + 'getSupplier'
                    ,startAt    :  0
                    ,autoLoad   : true
                }), itemStore = standards.callFunction(  '_createRemoteStore' ,{
                    fields      :[ {	name : 'id', type : 'number' }, 'name']
                    ,url: route + 'getItems'
                    ,startAt    :  0
                    ,autoLoad   : true
                }), poStore = standards.callFunction(  '_createRemoteStore' ,{
                    fields      :[ {	name : 'id', type : 'number' }, 'name']
                    ,url: route + 'getPO'
                    ,startAt    :  0
                    ,autoLoad   : true
                }), classificationStore = standards.callFunction(  '_createRemoteStore' ,{
                    fields      :[ {	name : 'id', type : 'number' }, 'name']
                    ,url: route + 'getItemClassifications'
                    ,startAt    :  0
                    ,autoLoad   : true
                });
                
                return standards.callFunction('_formPanel',{
                    moduleType      : 'report'
                    ,panelID        : 'ledgerForm' + ledgerModule
                    ,noHeader       : true
                    ,module         : ledgerModule
                    ,formItems      : [
                        {
                            xtype : 'container'
                            ,style  : 'margin-bottom:10px;'
                            ,items: [
                                standards2.callFunction( '_createAffiliateCombo', {
                                    module      : ledgerModule
                                    ,id         : 'Affiliate' + ledgerModule
                                    ,allowBlank : true
                                })
                                ,standards2.callFunction( '_createCostCenter', {
                                    module          : ledgerModule
                                    ,idAffiliate    : parseInt( idAffiliate , 10 )
                                    ,allowBlank     : true
                                } )
                                ,standards.callFunction( '_createCombo', {
                                    id              : 'poNumber' + ledgerModule
                                    ,fieldLabel     : 'PO #'
                                    ,allowBlank     : true
                                    ,store          : poStore
                                    ,displayField   : 'name'
                                    ,valueField     : 'id'
                                } )
                                ,standards.callFunction( '_createCombo', {
                                    id              : 'idItem' + ledgerModule
                                    ,fieldLabel     : 'Item Name'
                                    ,allowBlank     : true
                                    ,store          : itemStore
                                    ,displayField   : 'name'
                                    ,valueField     : 'id'
                                    ,listeners      : {
                                        beforeQuery : function(){
                                            /* Dependent on: PO, Item Name,  */
                                        }
                                    }
                                } )
                                ,standards.callFunction( '_createCombo', {
                                    id              : 'idItemClass' + ledgerModule
                                    ,fieldLabel     : 'Classification'
                                    ,allowBlank     : true
                                    ,store          : classificationStore
                                    ,displayField   : 'name'
                                    ,valueField     : 'id'
                                } )
                                ,standards.callFunction( '_createCombo', {
                                    id          : 'idSupplier' + ledgerModule
                                    ,fieldLabel : 'Supplier'
                                    ,store      : supplierStore
                                } )
                                ,standards.callFunction( '_createDateRange', {
                                    fieldLabel  : 'Date:'
                                    ,module     : ledgerModule
                                    ,fromWidth  : 230
                                    ,width      : 115
                                } )
                            ]
                        }
                        
                    ]
                    ,moduleGrids    : ledgerGrid()
                });

                function ledgerGrid(){
                    var store = standards.callFunction(  '_createRemoteStore' ,{
                        fields:[
                            'date'
                            ,'referenceNum'
                            ,{
                                name    : 'expectedQty'
                                ,type   : 'number'
                            }
                            ,{
                                name    : 'receivedQty'
                                ,type   : 'number'
                            }
                            , {
                                name    : 'balance'
                                ,type   : 'number'
                            }
                        ], 
                        url: route + "getPOList/ledger"
                    });
    
                    return standards.callFunction( '_gridPanel',{
                        id              : 'poGrid' + ledgerModule
                        ,module         : ledgerModule
                        ,store          : store
                        ,tbar           : {}
                        ,noDefaultRow   : true
                        ,noPage         : true
                        ,style          :'margin-top:10px;'
                        ,columns    :[
                            {	header : 'Date'
                                ,dataIndex  : 'date'
                                ,minWidth   : 90
                                ,flex       : 1
                                ,sortable   : false
                                ,xtype      : 'datecolumn'
                                ,format     : Ext.getConstant('DATE_FORMAT') + ' h:m A'
                            }
                            ,{	header : 'Reference'
                                ,dataIndex  : 'referenceNum'
                                ,minWidth   : 90
                                ,flex       : 1
                                ,sortable   : false
                            }
                            ,{	header      : 'Expected Qty'
                                ,dataIndex  : 'expectedQty'
                                ,width      : 90
                                ,hasTotal   : 1
                                ,sortable   : false
                            }
                            ,{	header      : 'Received Qty'
                                ,dataIndex  : 'receivedQty'
                                ,width      : 90
                                ,hasTotal   : 1
                                ,sortable   : false
                            }
                            ,{	header      : 'Balance'
                                ,dataIndex  : 'balance'
                                ,width      : 90
                                ,xtype      : 'numbercolumn'
                                ,format     : '0,000.00'
                                ,hasTotal   : 1
                                ,hasTotalType : 'running'
                                ,sortable   : false
                            }
                        ]
                        
                    });
                }
            }

            function _excelHandler(){
                var isMonitoring        = Ext.getCmp( 'btnMonitoring' + monitoringModule ).cls == 'menuActive' ? 1 : 0;
                var _module             = isMonitoring ? monitoringModule : ledgerModule;
                var _grid               = Ext.getCmp( 'poGrid' + _module );

                standards.callFunction( '_listExcel', {
                    grid                    : _grid
                    ,customListExcelHandler   : function(){
                        var par = standards.callFunction( 'getFormDetailsAsObject', {
                            module          : _module
                            ,getSubmitValue : true
                        } );
                        par.title           = pageTitle + ' - ' + ( isMonitoring ? 'Monitoring' : 'Ledger' ) ;
                        par.isMonitoring    = isMonitoring;
                        
                        Ext.Ajax.request( {
                            url         : route + 'printExcel/' + ( isMonitoring ? 'monitoring' : 'ledger' )
                            ,params     : par
                            ,success    : function(res){
                                window.open( route + "download/" + par.title + '/inventory');
                            }
                        } );
                    }
                } );
            }

            function _pdfHandler(){
                var isMonitoring        = Ext.getCmp( 'btnMonitoring' + monitoringModule ).cls == 'menuActive' ? 1 : 0;
                var _module             = isMonitoring ? monitoringModule : ledgerModule;
                var _grid               = Ext.getCmp( 'poGrid' + _module );

                standards.callFunction( '_listPDF', {
                    grid                    : _grid
                    ,customListPDFHandler   : function(){
                        var par = standards.callFunction( 'getFormDetailsAsObject', {
                            module          : _module
                            ,getSubmitValue : true
                        } );
                        par.title           = 'PO ' + ( isMonitoring ? 'Monitoring' : 'Ledger' ) ;
                        par.isMonitoring    = isMonitoring;
                        
                        Ext.Ajax.request( {
                            url         : route + 'printPDF/' + ( isMonitoring ? 'monitoring' : 'ledger' )
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
                monitoringModule = module
                ledgerModule = module + 'Ledger'
				
				return _mainPanel( config );
			}
		}
    }
}