/**
 * Developer: Jayson Dagulo
 * Module: Payable Balances and Ledger
 * Date: Jan. 17, 2020
 * Finished: 
 * Description: This module allows authorized user to generate and print the payable balances and its ledger for every supplier.
 * */ 
function Payablebalanceledger(){
    return function(){
        var baseurl, route, module, canDelete, pageTitle, canPrint, balanceModule, ledgerModule, isGae, idAffiliate;

        function _mainPanel( config ){
            return standards.callFunction(	'_mainPanel' ,{
                config		        : config
                ,moduleType	        : 'report'
                ,customExcelHandler : _excelHandler
                ,customPDFHandler   : _pdfHandler
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
						,items          : _payableBalance()
					}
					,{	xtype           : 'button'
						,buttonId       : 'btnLedger' + module
						,buttonIconCls  : 'list'
						,buttonLabel    : 'Ledger'
						,items          : _payableLedger()
					}
				]
            } );
        }

        function _payableBalance(){
            return standards.callFunction( '_formPanel', {
                moduleType          : 'report'
                ,panelID            : 'balancesForm' + balanceModule
                ,noHeader           : true
                ,module             : balanceModule
                ,afterResetHandler  : _resetBalancesForm
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
                                        if( me.store.data.length > 1 ) {
                                            me.setValue( 0 );
                                        } else {
                                            me.setValue( parseInt(Ext.getConstant('AFFILIATEID'),10) );
                                        }
                                    }
                                } )
                            }
                        }
                    } )
                    ,standards2.callFunction( '_createSupplier', {
                        hasAll      : 1
                        ,module     : balanceModule
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
                        }
                    } )
                    ,standards.callFunction( '_createDateField', {
                        id          : 'date' + balanceModule
                        ,fieldLabel : 'As of'
                    } )
                    ,standards.callFunction( '_createCheckField', {
                        id          : 'hideNoBalances' + balanceModule
                        ,boxLabel   : 'Hide suppliers with no balances'
                    } )
                ]
                ,moduleGrids    : _balancesGrid()
                ,listeners      : {
                    afterrender : function(){
                    }
                }
            } );
        }

        function _balancesGrid(){
            var store   = standards.callFunction( '_createRemoteStore', {
                fields      : [
                    'affiliateName'
                    ,'idAffiliate'
                    ,'supplierName'
                    ,'idSupplier'
                    ,{  name    : 'chargesAmt'
                        ,type   : 'number'
                    }
                    ,{  name    : 'paymentsAmt'
                        ,type   : 'number'
                    }
                    ,{  name    : 'balanceAmt'
                        ,type   : 'number'
                    }
                ]
                ,url        : route + 'getBalances'
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
                    {   header          : 'Affiliate'
                        ,dataIndex      : 'affiliateName'
                        ,width          : 150
                        ,columnWidth    : 15
                    }
                    ,{  header          : 'Supplier'
                        ,dataIndex      : 'supplierName'
                        ,minWidth       : 200
                        ,flex           : 1
                        ,columnWidth    : 25
                    }
                    ,{  header          : 'Charges'
                        ,dataIndex      : 'chargesAmt'
                        ,width          : 100
                        ,columnWidth    : 10
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,hasTotal       : true
                    }
                    ,{  header          : 'Payments'
                        ,dataIndex      : 'paymentsAmt'
                        ,width          : 100
                        ,columnWidth    : 10
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,hasTotal       : true
                    }
                    ,{  header          : 'Balance'
                        ,dataIndex      : 'balanceAmt'
                        ,width          : 100
                        ,columnWidth    : 10
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,hasTotal       : true
                    }
                    ,standards.callFunction( '_createActionColumn', {
						icon        : 'th-list'
						,tooltip    : 'View Ledger'
						,Func       : _viewLedger
                    } )
                ]
            } )
        }

        function _resetBalancesForm(){
            Ext.getCmp( 'idAffiliate' + balanceModule ).fireEvent( 'afterrender' );
            Ext.getCmp( 'idSupplier' + balanceModule ).fireEvent( 'afterrender' );
        }

        function _payableLedger(){
            return standards.callFunction( '_formPanel', {
                moduleType          : 'report'
                ,panelID            : 'ledgerForm' + ledgerModule
                ,noHeader           : true
                ,module             : ledgerModule
                ,beforeViewHandler  : _checkLedgerParams
                ,config             : {
                    module  : ledgerModule
                }
                ,formItems      : [
                    standards2.callFunction( '_createAffiliateCombo', {
                        module     : ledgerModule
                        ,width      : 381
                        ,allowBlank : true
                    } )
                    ,standards2.callFunction( '_createSupplier', {
                        module     : ledgerModule
                        ,width      : 381
                        ,allowBlank : true
                    } )
                    ,standards.callFunction( '_createDateRange', {
                        module      : ledgerModule
                    } )
                ]
                ,moduleGrids    : _ledgerGrid()
                ,listeners      : {
                    afterrender : function(){

                    }
                }
            } );
        }

        function _ledgerGrid(){
            var store   = standards.callFunction( '_createRemoteStore', {
                fields      : [
                    'date'
                    ,'reference'
                    ,'paymentType'
                    ,'particulars'
                    ,{  name    : 'amt'
                        ,type   : 'number'
                    }
                    ,{  name    : 'payments'
                        ,type   : 'number'
                    }
                    ,{  name    : 'balance'
                        ,type   : 'number'
                    }
                    ,'idModule'
                    ,'idInvoice'
                ]
                ,url        : route + 'getLedger'
            } );

            return standards.callFunction( '_gridPanel', {
                id              : 'grdLedger' + ledgerModule
				,module         : ledgerModule
                ,store          : store
                ,noDefaultRow   : true
                ,tbar           : {
                    content     : ''
                }
                ,features       : {
                    ftype   : 'summary'
                }
                ,noPage         : true
                ,listeners      : {
                    itemdblclick: function(dataview, record, item, index, e) {
                        if( parseInt( record.data.idModule, 10 ) > 0 ) mainView.openModule( record.data.idModule , record.data, this );
                    }
                }
                ,columns        : [
                    {  header          : 'Date'
                        ,dataIndex      : 'date'
                        ,width          : 100
                        ,columnWidth    : 10
                        ,sortable       : false
                        ,draggable      : false
                        ,menuDisabled   : true
                    }
                    ,{  header          : 'Ref.'
                        ,dataIndex      : 'reference'
                        ,width          : 100
                        ,columnWidth    : 10
                        ,sortable       : false
                        ,draggable      : false
                        ,menuDisabled   : true
                    }
                    ,{  header          : 'Payment Type'
                        ,dataIndex      : 'paymentType'
                        ,width          : 200
                        ,columnWidth    : 20
                        ,sortable       : false
                        ,draggable      : false
                        ,menuDisabled   : true
                    }
                    ,{  header          : 'Particulars'
                        ,dataIndex      : 'particulars'
                        ,minWidth       : 200
                        ,flex           : 1
                        ,columnWidth    : 25
                        ,sortable       : false
                        ,draggable      : false
                        ,menuDisabled   : true
                    }
                    ,{  header          : 'Amount'
                        ,dataIndex      : 'amt'
                        ,width          : 100
                        ,columnWidth    : 10
                        ,sortable       : false
                        ,draggable      : false
                        ,menuDisabled   : true
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,hasTotal       : true
                    }
                    ,{  header          : 'Payments'
                        ,dataIndex      : 'payments'
                        ,width          : 100
                        ,columnWidth    : 10
                        ,sortable       : false
                        ,draggable      : false
                        ,menuDisabled   : true
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,hasTotal       : true
                    }
                    ,{  header          : 'Balance'
                        ,dataIndex      : 'balance'
                        ,width          : 100
                        ,columnWidth    : 10
                        ,sortable       : false
                        ,draggable      : false
                        ,menuDisabled   : true
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,hasTotal       : true
                        ,hasTotalType   : 'running'
                    }
                ]
            } )
        }

        function _viewLedger( data ){
            var ledgerButton = Ext.getCmp( 'btnLedger' + module );
            ledgerButton.handler.call( ledgerButton );
            var sdate   = new Date( Ext.getCmp( 'date' + balanceModule ).getValue() );
            sdate.setMonth( sdate.getMonth() - 1 );
            Ext.getCmp( 'sdate' + ledgerModule ).setValue( sdate );
            Ext.getCmp( 'edate' + ledgerModule ).setValue( Ext.getCmp( 'date' + balanceModule ).getValue() );
            Ext.getCmp( 'idAffiliate' + ledgerModule ).store.load( {
                callback    : function(){
                    Ext.getCmp( 'idAffiliate' + ledgerModule ).setValue( parseInt( data.idAffiliate, 10 ) );
                    Ext.getCmp( 'idSupplier' + ledgerModule ).store.load( {
                        callback    : function(){
                            Ext.getCmp( 'idSupplier' + ledgerModule ).setValue( parseInt( data.idSupplier, 10 ) );
                            var viewButton  = Ext.getCmp( 'viewButton' + ledgerModule )
                            viewButton.handler.call( viewButton.scope, viewButton );
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
					par.title = pageTitle + ' - ' + ( isBalance ? 'Balance' : 'Ledger' ) ;
					par.isBalance = isBalance;
					
					Ext.Ajax.request( {
						url         : route+'printExcel/' + ( isBalance ? 'Balance' : 'Ledger' )
						,params     : par
						,success    : function(){
							window.open( route + "download/" + par.title + '/inventory');
						}
					} );
				}
			});
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

        function _checkLedgerParams(){
            if( !Ext.getCmp( 'idAffiliate' + ledgerModule ).getValue()
                || !Ext.getCmp( 'idSupplier' + ledgerModule ).getValue()
                || !Ext.getCmp( 'sdate' + ledgerModule ).getValue()
                || !Ext.getCmp( 'edate' + ledgerModule ).getValue() ){
                standards.callFunction( '_createMessageBox', {
                    msg : 'All fields are required when generating this report.'
                } )
                return true;
            }
        }

        return{
			initMethod:function( config ){
				route		    = config.route;
				baseurl		    = config.baseurl;
				module		    = config.module;
				canPrint	    = config.canPrint;
				canDelete	    = config.canDelete;
				canEdit		    = config.canEdit;
				pageTitle       = config.pageTitle;
				idModule	    = config.idmodule
				isGae		    = config.isGae;
                idAffiliate     = config.idAffiliate
                balanceModule   = module
                ledgerModule    = module + 'Ledger'
				
				return _mainPanel( config );
			}
		}
    }
}