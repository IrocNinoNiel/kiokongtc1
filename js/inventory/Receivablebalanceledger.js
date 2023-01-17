/**
 * Developer    : Jays
 * Module       : Receivable Balances, Ledger and SOA
 * Date         : Feb. 12, 2020
 * Finished     : 
 * Description  : his module allows authorized user to generate and print the balances, 
 *                ledger and statement of account per customer on a specified date range.
 * DB Tables    : 
 * */ 
function Receivablebalanceledger(){
    return function(){
        var baseurl, route, module, canDelete, pageTitle, idModule, isGae,isSaved = 0,deletedItems = [],selectedItem = [], idAffiliate
            ,canSave, balanceModule, ledgerModule, canPrint;

        function _mainPanel( config ){
            return standards.callFunction(	'_mainPanel' ,{
                config		        : config
                ,moduleType	        : 'report'
                ,customPDFHandler   : _pdfHandler
                ,customExcelHandler : _excelHandler
                ,tbar               : {
                    noPDFButton    : false
                    ,noExcelButton  : false
                    ,PDFHidden      : false
                }
                ,extraFormTab       : [
					{	xtype           : 'button'
						,buttonId       : 'btnBalance' + module
                        ,activeButton   : true
                        ,buttonIconCls  : 'modu'
						,buttonLabel    : 'Balance'
						,items          : _receivableBalance()
					}
					,{	xtype           : 'button'
						,buttonId       : 'btnLedger' + module
						,buttonIconCls  : 'list'
						,buttonLabel    : 'Ledger'
						,items          : _receivableLedger()
					}
				]
            } );
        }

        function _receivableBalance(){
            var customerStore   = standards.callFunction( '_createRemoteStore', {
                    fields      : [
                        {   name    : 'id'
                            ,type   : 'number'
                        }
                        ,'name'
                    ]
                    ,url        : route + 'getCustomers'
                } )
                ,salesRefStore   = standards.callFunction( '_createRemoteStore', {
                    fields      : [
                        {   name    : 'id'
                            ,type   : 'number'
                        }
                        ,'name'
                    ]
                    ,url        : route + 'getSalesReference'
                } );
            return standards.callFunction( '_formPanel', {
                moduleType          : 'report'
                ,panelID            : 'balancesForm' + balanceModule
                ,noHeader           : true
                ,module             : balanceModule
                ,afterResetHandler  : _resetBalancesForm
                ,beforeViewHandler  : _checkBalancesParams
                ,config             : {
                    module  : balanceModule
                }
                ,formItems          : [
                    standards2.callFunction( '_createAffiliateCombo', {
                        hasAll      : 1
                        ,module     : balanceModule
                        ,allowBlank : true
                        ,listeners  : {
                            afterrender : function(){
                                var me  = this;
                                me.store.load( {
                                    callback    : function(){
                                        if( me.store.getCount() > 1 ) me.setValue( 0 );
                                        else me.setValue( me.store.getAt(0).get( 'id' ) );
                                    }
                                } )
                            }
                            ,select     : function(){
                                var me      = this
                                    ,refCmb = Ext.getCmp( 'idReference' + balanceModule )
                                    ,empCmb = Ext.getCmp( 'idCustomer' + balanceModule );
                                refCmb.store.proxy.extraParams.idAffiliate  = me.getValue();
                                refCmb.store.load();
                                empCmb.store.proxy.extraParams.idAffilaite  = me.getValue();
                                empCmb.store.load();
                            }
                        }
                    } )
                    ,standards.callFunction( '_createCombo', {
                        id              : 'idReference' + balanceModule
                        ,fieldLabel     : 'Sales Reference'
                        ,store          : salesRefStore
                        ,valueField     : 'id'
                        ,displayField   : 'name'
                        ,listeners      : {
                            afterrender     : function(){
                                var me  = this;
                                salesRefStore.proxy.extraParams.hasAll = 1;
                                salesRefStore.load( {
                                    callback    : function(){
                                        me.setValue( 0 );
                                    }
                                } )
                            }
                        }
                    } )
                    ,standards.callFunction( '_createCombo', {
                        id              : 'idCustomer' + balanceModule
                        ,fieldLabel     : 'Customer Name'
                        ,store          : customerStore
                        ,valueField     : 'id'
                        ,displayField   : 'name'
                        ,listeners      : {
                            afterrender     : function(){
                                var me  = this;
                                customerStore.proxy.extraParams.hasAll = 1;
                                customerStore.load( {
                                    callback    : function(){
                                        me.setValue( 0 );
                                    }
                                } )
                            }
                        }
                    } )
                    ,standards.callFunction( '_createDateField', {
                        id              : 'date' + balanceModule
                        ,fieldLabel     : 'As of'
                    } )
                    ,standards.callFunction( '_createCheckField', {
                        id              : 'hideNoBalance' + balanceModule
                        ,fieldLabel     : 'Do not show customer with zero balances'
                        ,labelWidth     : 330
                    } )
                ]
                ,moduleGrids    : _balancesGrid()
            } );
        }

        function _balancesGrid(){
            var store       = standards.callFunction( '_createRemoteStore', {
                fields      : [
                    'affiliateName'
                    ,'idAffiliate'
                    ,'customerName'
                    ,'idCustomer'
                    ,{  name    : 'chargesAmt'
                        ,type   : 'number'
                    }
                    ,{  name    : 'paymentAmt'
                        ,type   : 'number'
                    }
                    ,{  name    : 'balanceAmt'
                        ,type   : 'number'
                    }
                    ,'email'
                ]
                ,url        : route + 'getReceivableBalances'
            } );
            return standards.callFunction( '_gridPanel', {
                id              : 'grdBalances' + balanceModule
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
                ,columns        : [
                    {   header      : 'Affiliate'
                        ,dataIndex  : 'affiliateName'
                        ,width      : 120
                    }
                    ,{  header      : 'Customer'
                        ,dataIndex  : 'customerName'
                        ,flex       : 1
                        ,minWidth   : 150
                    }
                    ,{  header      : 'Charges'
                        ,dataIndex  : 'chargesAmt'
                        ,xtype      : 'numbercolumn'
                        ,format     : '0,000.00'
                        ,width      : 120
                    }
                    ,{  header      : 'Payments'
                        ,dataIndex  : 'paymentAmt'
                        ,xtype      : 'numbercolumn'
                        ,format     : '0,000.00'
                        ,width      : 120
                    }
                    ,{  header      : 'Balance'
                        ,dataIndex  : 'balanceAmt'
                        ,xtype      : 'numbercolumn'
                        ,format     : '0,000.00'
                        ,width      : 120
                        ,hasTotal   : true
                    }
                    ,standards.callFunction( '_createActionColumn', {
						icon        : 'th-list'
						,tooltip    : 'View Ledger'
						,Func       : _viewLedger
                    } )
                    ,standards.callFunction( '_createActionColumn', {
						icon        : 'file'
                        ,tooltip    : 'View SOA(PDF)'
						,Func       : _viewSOAPDF
                    } )
                    ,standards.callFunction( '_createActionColumn', {
						icon        : 'th'
                        ,tooltip    : 'View SOA(Excel)'
						,Func       : _viewSOAExcel
                    } )
                    ,standards.callFunction( '_createActionColumn', {
						icon        : 'envelope'
                        ,tooltip    : 'Send SOA'
						,Func       : _viewSOAEmail
                    } )
                ]
            } )
        }

        function _viewLedger( data ){
            var ledgerButton = Ext.getCmp( 'btnLedger' + module );
            ledgerButton.handler.call( ledgerButton );
            Ext.getCmp( 'idAffiliate' + ledgerModule ).store.load( {
                callback    : function(){
                    Ext.getCmp( 'idAffiliate' + ledgerModule ).setValue( parseInt( data.idAffiliate, 10 ) );
                    Ext.getCmp( 'idReference' + ledgerModule ).store.proxy.extraParams.idAffiliate = data.idAffiliate;
                    Ext.getCmp( 'idReference' + ledgerModule ).store.load( {
                        callback    : function(){
                            Ext.getCmp( 'idReference' + ledgerModule ).setValue( 0 );
                            Ext.getCmp( 'idCustomer' + ledgerModule ).store.proxy.extraParams.idAffiliate = data.idAffiliate;
                            Ext.getCmp( 'idCustomer' + ledgerModule ).store.load( {
                                callback    : function(){
                                    Ext.getCmp( 'idCustomer' + ledgerModule ).setValue( parseInt( data.idCustomer, 10 ) );
                                    var viewButton  = Ext.getCmp( 'viewButton' + ledgerModule )
                                    viewButton.handler.call( viewButton.scope, viewButton );
                                }
                            } )
                        }
                    } )
                }
            } )
        }

        function _viewSOAPDF( data ){
            data['type']    = 1;
            _showDateRange( data );
        }

        function _viewSOAExcel( data ){
            data['type']    = 2;
            _showDateRange( data );
        }

        function _viewSOAEmail( data ){
            data['type']    = 3;
            _showDateRange( data );
        }

        function _showDateRange( data ){
            Ext.create( 'Ext.window.Window', {
                title           : 'Select period cover'
                ,closable       : false
                ,id             : 'windowView' + module
                ,bodyPadding    : 10
                ,modal          : true
                ,items          : [
                    standards.callFunction( '_createDateRange', {
                        module          : module
                        ,noFieldLabel   : true
                        ,fromWidth      : 180
                        ,width          : 195
                    } )
                ]
                ,listeners      : {
                    afterrender : function(){
                        var date    = Ext.getCmp( 'date' + balanceModule ).getValue()
                            ,edate  = Ext.getCmp( 'date' + balanceModule ).getValue()
                            ,sdate  = new Date( date.setMonth( date.getMonth() - 1 ) );
                        
                            Ext.getCmp( 'edate' + module ).setReadOnly( true );
                        if( data.edate && data.sdate ){
                            sdate   = data.sdate;
                            edate   = data.edate;
                        }
                        Ext.getCmp( 'edate' + module ).setValue( edate );
                        Ext.getCmp( 'sdate' + module ).setValue( sdate );
                        Ext.getCmp( 'sdate' + module ).setMaxValue( edate );
                    }
                }
                ,buttons        : [
                    {   text        : 'View'
                        ,handler    : function(){
                            data['sdate']   = Ext.getCmp( 'sdate' + module ).getValue();
                            data['edate']   = Ext.getCmp( 'edate' + module ).getValue();
                            Ext.getCmp( 'windowView' + module ).destroy( true );
                            data['title']   = data['customerName'].replace( /\s/g, '_' ) + '_SOA';
                            _processSOA( data );
                        }
                    }
                    ,{  text        : 'Close'
                        ,handler    : function(){
                            Ext.getCmp( 'windowView' + module ).destroy( true );
                        }
                    }
                ]
            } ).show();
        }

        function _processSOA( data ){
            Ext.Ajax.request( {
                url         : route + 'processSOA'
                ,params     : data
                ,success    : function( response ){
                    if( data.type == 1 ){
                        window.open( baseurl + 'pdf/inventory/' + data.title + '.pdf' , '_blank' );
                    }
                    else if( data.type == 2 ){
                        window.open( route + "download/" + data.title + '/inventory');
                    }
                    else{
                        var resp    = Ext.decode( response.responseText )
                            ,match  = parseInt( resp.match, 10 );
                        standards.callFunction( '_createMessageBox', {
                            msg     : ( match == 1? 'Customer statement of account has been successfully sent.' : ( match == 2? 'An error has occured while processing your request. Please contact your administrator.' : 'Customer email address does not exists.' ) )
                        } )
                    }
                }
            } );
        }

        function _checkBalancesParams(){
            if( !Ext.getCmp( 'date' + balanceModule ).getValue() ){
                standards.callFunction( '_createMessageBox', {
                    msg    : 'All filters are required to generate this report.'
                } )
                return true;
            }
        }

        function _resetBalancesForm(){
            Ext.getCmp( 'idAffiliate' + balanceModule ).fireEvent( 'afterrender' );
            Ext.getCmp( 'idReference' + balanceModule ).fireEvent( 'afterrender' );
            Ext.getCmp( 'idCustomer' + balanceModule ).fireEvent( 'afterrender' );
        }

        function _receivableLedger(){
            var customerStore   = standards.callFunction( '_createRemoteStore', {
                    fields      : [
                        {   name    : 'id'
                            ,type   : 'number'
                        }
                        ,'name'
                    ]
                    ,url        : route + 'getCustomers'
                } )
                ,salesRefStore   = standards.callFunction( '_createRemoteStore', {
                    fields      : [
                        {   name    : 'id'
                            ,type   : 'number'
                        }
                        ,'name'
                    ]
                    ,url        : route + 'getSalesReference'
                } );
            return standards.callFunction( '_formPanel', {
                moduleType          : 'report'
                ,panelID            : 'ledgerForm' + ledgerModule
                ,noHeader           : true
                ,module             : ledgerModule
                ,beforeViewHandler  : _checkLedgerParams
                ,config             : {
                    module  : ledgerModule
                }
                ,formItems          : [
                    standards2.callFunction( '_createAffiliateCombo', {
                        module      : ledgerModule
                        ,allowBlank : true
                        ,listeners  : {
                            select  : function(){
                                var me  = this;
                                Ext.getCmp( 'idReference' + module ).store.proxy.extraParams.idAffiliate = me.getValue();
                                Ext.getCmp( 'idReference' + module ).store.load( {} );
                                Ext.getCmp( 'idCustomer' + module ).store.proxy.extraParams.idAffiliate = me.getValue();
                                Ext.getCmp( 'idCustomer' + module ).store.load( {} );
                                Ext.getCmp( 'idReference' + module ).store.proxy.extraParams.idAffiliate = me.getValue();
                                Ext.getCmp( 'idReference' + module ).store.load( {
                                    callback    : function(){
                                        Ext.getCmp( 'idReference' + module ).setValue( 0 )
                                    }
                                } )
                            }
                        }
                    } )
                    ,standards.callFunction( '_createCombo', {
                        id              : 'idReference' + ledgerModule
                        ,fieldLabel     : 'Sales Reference'
                        ,store          : salesRefStore
                        ,valueField     : 'id'
                        ,displayField   : 'name'
                        ,listeners      : {
                            afterrender     : function(){
                                var me  = this;
                                salesRefStore.proxy.extraParams.hasAll = 1;
                                salesRefStore.load( {
                                    callback    : function(){
                                        me.setValue( 0 );
                                    }
                                } )
                            }
                        }
                    } )
                    ,standards.callFunction( '_createCombo', {
                        id              : 'idCustomer' + ledgerModule
                        ,fieldLabel     : 'Customer Name'
                        ,store          : customerStore
                        ,valueField     : 'id'
                        ,displayField   : 'name'
                    } )
                    ,standards.callFunction( '_createDateRange', {
                        module          : ledgerModule
                        ,fromWidth      : 230
                        ,width          : 115
                    } )
                ]
                ,moduleGrids    : _ledgerGrid()
            } );
        }

        function _ledgerGrid(){
            var store           = standards.callFunction( '_createRemoteStore', {
                fields      : [
                    'date'
                    ,'reference'
                    ,'remarks'
                    ,{  name    : 'chargesAmt'
                        ,type   : 'number'
                    }
                    ,{  name    : 'paymentAmt'
                        ,type   : 'number'
                    }
                    ,{  name    : 'balanceAmt'
                        ,type   : 'number'
                    }
                    ,'idModule'
                ]
                ,url        : route + 'getReceivableLedger'
            } )
            return standards.callFunction( '_gridPanel', {
                id              : 'grdLedger' + ledgerModule
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
                // ,plugins        : true
                ,listeners      : {
                    itemdblclick: function(dataview, record, item, index, e) {
                        if( parseInt( record.data.idModule, 10 ) > 0 ) mainView.openModule( record.data.idModule , record.data, this );
                    }
                }
                ,columns        : [
                    {   header          : 'Date'
                        ,dataIndex      : 'date'
                        ,xtype          : 'datecolumn'
                        ,format         : 'm/d/Y'
                        ,width          : 120
                        ,sortable       : false
                    }
                    ,{  header          : 'Invoice No.'
                        ,dataIndex      : 'reference'
                        ,width          : 150
                        ,sortable       : false
                    }
                    ,{  header          : 'Remarks'
                        ,dataIndex      : 'remarks'
                        ,flex           : 1
                        ,minWidth       : 150
                        ,sortable       : false
                    }
                    ,{  header          : 'Charges'
                        ,dataIndex      : 'chargesAmt'
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,width          : 120
                        ,hasTotal       : true
                        ,sortable       : false
                    }
                    ,{  header          : 'Payments'
                        ,dataIndex      : 'paymentAmt'
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,width          : 120
                        ,hasTotal       : true
                        ,sortable       : false
                    }
                    ,{  header          : 'Balance'
                        ,dataIndex      : 'balanceAmt'
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,width          : 120
                        ,hasTotal       : true
                        ,hasTotalType   : 'running'
                        ,sortable       : false
                    }
                ]
            } )
        }

        function _checkLedgerParams(){
            if( !Ext.getCmp( 'idAffiliate' + ledgerModule ).getValue() || !Ext.getCmp( 'idCustomer' + ledgerModule ).getValue() || !Ext.getCmp( 'sdate' + ledgerModule ).getValue() || !Ext.getCmp( 'edate' + ledgerModule ).getValue() ){
                standards.callFunction( '_createMessageBox', {
                    msg     : 'All filters are required to generate report.'
                } )
                return true;
            }
        }

        function _pdfHandler(){
            var isBalance           = Ext.getCmp('btnBalance' + module).cls == 'menuActive' ? 1 : 0;
			var moduleIdentifier    = isBalance ? balanceModule : ledgerModule;
            var grid                = isBalance ? Ext.getCmp( 'grdBalances' + moduleIdentifier ) : Ext.getCmp( 'grdLedger' + moduleIdentifier );
            
            standards.callFunction( '_listPDF', {
				grid                    : grid
				,customListPDFHandler   : function(){
					var par = standards.callFunction( 'getFormDetailsAsObject', {
						module          : moduleIdentifier
						,getSubmitValue : true
					} );
					par.title       = pageTitle + ' - ' + ( isBalance ? 'Balance' : 'Ledger' ) ;
					par.isBalance   = isBalance;
					
					Ext.Ajax.request( {
						url         : route + 'printPDF/' + ( isBalance ? 'Balance' : 'Ledger' )
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

        function _excelHandler(){
            var isBalance = Ext.getCmp('btnBalance' + module).cls == 'menuActive' ? 1 : 0;
			var moduleIdentifier    = isBalance ? balanceModule : ledgerModule;
            var grid                = isBalance ? Ext.getCmp( 'grdBalances' + moduleIdentifier ) : Ext.getCmp( 'grdLedger' + moduleIdentifier );
            
            
			standards.callFunction( '_listExcel', {
				grid                    : grid
				,customListExcelHandler : function(){
					var par = standards.callFunction( 'getFormDetailsAsObject', {
						module              : moduleIdentifier
						,getSubmitValue     : true
					} );
					par.title = pageTitle.replace( ',', '' ) + ' - ' + ( isBalance ? 'Balance' : 'Ledger' ) ;
					par.isBalance = isBalance;
					
					Ext.Ajax.request( {
						url         : route+'printExcel/' + ( isBalance ? 'Balance' : 'Ledger' )
						,params     : par
						,success    : function(){
							window.open( route + "download/" + par.title + '/inventory');
						}
					} );
				}
			} );
        }

        return{
            initMethod:function( config ){
                route		    = config.route;
                baseurl		    = config.baseurl;
                module		    = config.module;
                canDelete	    = config.canDelete;
                pageTitle       = config.pageTitle;
                idModule	    = config.idmodule
                isGae		    = config.isGae;
                idAffiliate     = config.idAffiliate;
                canSave         = config.canSave;
                canPrint        = config.canPrint;
                balanceModule   = module + 'balance';
                ledgerModule    = module + 'Ledger';
                
                return _mainPanel( config );
            }
        }
    }
}