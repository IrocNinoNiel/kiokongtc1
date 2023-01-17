/**
 * Developer: Christian P. Daohog
 * Module: Material Monitoring
 * Date: Jan 31, 2022
 * Finished:
 * Description: This module allows authorized users to generate and monitor the materials released per project in construction.
 * DB Tables:
 * */

 function Materialmonitoring() {
    return function() {
        var baseurl, route, module, canDelete, canPrint, pageTitle, isGae, canEdit, idModule, onEdit = 0,
        balanceModule, ledgerModule;

        function _mainPanel(config) {
            return standards.callFunction('_mainPanel', {
                config              : config
                ,moduleType         : 'report'
                ,customExcelHandler : _printExcelHandler
                ,customPDFHandler   : _printPDFHandler
                ,tbar               : {
                    noPDFButton         : false
                    ,noExcelButton      : false
                    ,PDFHidden          : false
                }
                ,extraFormTab       : [
					{	xtype           : 'button'
						,buttonId       : 'btnBalance' + module
                        ,activeButton   : true
                        ,buttonIconCls  : 'modu'
						,buttonLabel    : 'Balance'
						,items          : _balance()
					}
					,{	xtype           : 'button'
						,buttonId       : 'btnLedger' + module
						,buttonIconCls  : 'list'
						,buttonLabel    : 'Ledger'
						,items          : _ledger()
					}
				]
            });
        }

        function _balance(){

            function _checkBalance(){
                if( !Ext.getCmp( 'projectName' + balanceModule ).getValue()){
                    standards.callFunction( '_createMessageBox', {
                        msg : 'All fields are required when generating this report.'
                    } )
                    return true;
                }
            }

            return standards.callFunction( '_formPanel', {
                moduleType          : 'report'
                ,panelID            : 'balancesForm' + balanceModule
                ,noHeader           : true
                ,module             : balanceModule
                ,formItems          : [ _balanceForm() ]
                ,moduleGrids        : [ _balancesGrid() ]
                ,config             : { module  : balanceModule }
                ,beforeViewHandler  : _checkBalance
            } );
        }

        function _balanceForm() {
            let projectNameStore = standards.callFunction('_createRemoteStore', {
                    fields    : [{name:'id', type:'number'}, 'name']
                    ,url      : route + 'getProjectNames'
                    ,autoload : true
                });

            return {
                xtype       : 'fieldset'
                ,layout     : 'column'
                ,padding    : 10
                ,items      : [
                    {
                        xtype       : 'container',
                        columnWidth : .5,
                        items       : [
                            standards2.callFunction( '_createAffiliateCombo', {
                                module      : balanceModule
                                ,allowBlank : true
                                ,listeners  : {
                                    afterrender : function(){
                                        var me  = this;
                                        me.store.load( {
                                            callback    : function(){
                                                me.setValue( parseInt(Ext.getConstant('AFFILIATEID'),10) );
                                            }
                                        } )
                                    }
                                }
                            } ),
                            standards.callFunction( '_createCombo', {
                                id              : 'projectName' + balanceModule
                                ,fieldLabel     : 'Project Name'
                                ,store          : projectNameStore
                                ,allowBlank     : false
                                ,valueField     : 'id'
                                ,displayField   : 'name'
                            } )
                        ]
                    }, {
                        xtype           : 'container'
                        ,columnWidth    : .5
                        ,items          : [
                            standards.callFunction( '_createDateField', {
                                id		    : 'asOfDateBalance' + balanceModule
                                ,fieldLabel : 'As of Date'
                                ,allowBlank : true
                            } )
                        ]
                    }
                ]
            }
        }

        function _balancesGrid() {
            var store = standards.callFunction(  '_createRemoteStore' ,{
                fields:[
                    'itemNo'
                    ,'itemName'
                    ,'approvedUnit'
                    ,'unitCode'
                    ,{ name: 'approvedQty',      type: 'number' }
                    ,{ name: 'approvedCost',     type: 'number' }
                    ,{ name: 'balanceQty',       type: 'number' }
                    ,{ name: 'approvedAmount',   type: 'number' } 
                    ,{ name: 'totalReleaseQty', type: 'number' } 
                ],
                url: route + 'getBalance'
            });

            let columns = [{
                header      : 'Item No.'
                ,dataIndex  : 'itemNo'
                ,width      : 80
                ,renderer   : function (val, meta, rec, rowIndex, colIndex, store) {
                    let itemNo = rowIndex + 1;
                    rec.data.itemNo   = itemNo;
                    return itemNo;
                }
            }, {
                header      : 'Material'
                ,dataIndex  : 'itemName'
                ,flex       : 1
            }, {
                text        : 'Approved Items'
                ,columns    : [
                    {
                        header      : 'Quantity'
                        ,width      : 80
                        ,xtype      : 'numbercolumn'
                        ,format     : '0,000.00'
                        ,dataIndex  : 'approvedQty'
                    }, {
                        header      : 'Unit'
                        ,width      : 80
                        ,dataIndex  : 'unitCode'
                    }, {
                        header      : 'Unit Cost'
                        ,width      : 80
                        ,xtype      : 'numbercolumn'
                        ,format     : '0,000.00'
                        ,dataIndex  : 'approvedCost'
                    }, {
                        header      : 'Amount'
                        ,width      : 80
                        ,dataIndex  : 'approvedAmount'
                        ,format     : '0,000.00'
                        ,xtype      : 'numbercolumn'
                    }
                ]
            }, {
                text        : 'Balance Items'
                ,columns    : [
                    {
                        header      : 'Quantity'
                        ,width      : 80
                        ,xtype      : 'numbercolumn'
                        ,format     : '0,000.00'
                        ,dataIndex  : 'balanceQty'
                        ,renderer   : function(val, meta, rec, rowIndex, colIndex, store){
                            let totalReleaseQty = rec.get('totalReleaseQty');
                                approvedQty     = rec.get('approvedQty');
                                total           = (approvedQty - totalReleaseQty).toFixed(2);
                                rec.data.balanceQty   = total;

                            return total;
                        }
                    }, {
                        header      : 'Unit'
                        ,width      : 80
                        ,dataIndex  : 'unitCode'
                    }, {
                        header      : 'Unit Cost'
                        ,width      : 80
                        ,xtype      : 'numbercolumn'
                        ,format     : '0,000.00'
                        ,dataIndex  : 'approvedCost'
                    }, {
                        header      : 'Amount'
                        ,width      : 80
                        ,dataIndex  : 'balanceAmount'
                        ,format     : '0,000.00'
                        ,xtype      : 'numbercolumn'
                        ,renderer   : function(val, meta, rec, rowIndex, colIndex, store){
                            let balanceQty      = rec.get('balanceQty');
                                approvedCost    = rec.get('approvedCost');
                                total           = (balanceQty * approvedCost).toFixed(2);
                                rec.data.balanceAmount   = total;
                            return numberWithCommas(total);
                        }
                    }
                ]
            }];

            return {
                xtype           : 'container'
                ,columnWidth    : 1
                ,items          : [
                    standards.callFunction('_gridPanel', {
                        id              : 'gridItem' + balanceModule
                        ,module         : balanceModule
                        ,style          : 'margin-top:15px'
                        ,store          : store
                        ,noDefaultRow   : true
                        ,noPage         : true
                        ,plugins        : true
                        ,tbar           : {}
                        ,columns        : columns
                    })
                ]
            }
        }

        function _ledger(){

            function _checkLedger(){
                if( !Ext.getCmp( 'projectName' + ledgerModule ).getValue() || !Ext.getCmp( 'itemName' + ledgerModule ).getValue() ){
                    standards.callFunction( '_createMessageBox', {
                        msg : 'All fields are required when generating this report.'
                    } )
                    return true;
                }
            }

            return standards.callFunction( '_formPanel', {
                moduleType          : 'report'
                ,panelID            : 'ledgerForm' + ledgerModule
                ,noHeader           : true
                ,module             : ledgerModule
                ,formItems          : [ _ledgerForm() ]
                ,moduleGrids        : [ _ledgersGrid() ]
                ,config             : { module  : ledgerModule }
                ,beforeViewHandler  : _checkLedger
            } );
        }

        function _ledgerForm() {
            var projectNameStore = standards.callFunction('_createRemoteStore', {
                    fields      : [ {name:'id', type:'number'}, 'name' ]
                    ,url        : route + 'getProjectNames'
                    ,autoload   : true
                })
                ,itemNameStore = standards.callFunction('_createRemoteStore', {
                    fields      :[ {name : 'id', type : 'number' }, 'name']
                    ,url        : route + 'getItems'
                    ,autoload   : true
                });

            return {
                xtype       : 'fieldset'
                ,layout     : 'column'
                ,padding    : 10
                ,items      : [
                    {
                        xtype       : 'container',
                        columnWidth : .5,
                        items       : [
                            standards2.callFunction( '_createAffiliateCombo', {
                                hasAll      : 1
                                ,module     : ledgerModule
                                ,allowBlank : true
                                ,listeners  : {
                                    afterrender : function(){
                                        var me  = this;
                                        me.store.load( {
                                            callback    : function(){
                                                if( me.store.data.length > 1 ) {
                                                    me.setValue( 0 );
                                                } else {
                                                    me.setValue( parseInt(Ext.getConstant('AFFILIATEID'), 10) );
                                                }
                                            }
                                        } )
                                    }
                                }
                            } ),
                            standards.callFunction( '_createCombo', {
                                id              : 'projectName' + ledgerModule
                                ,fieldLabel     : 'Project Name'
                                ,store          : projectNameStore
                                ,allowBlank     : false
                                ,valueField     : 'id'
                                ,displayField   : 'name'
                            } )
                        ]
                    }, {
                        xtype           : 'container'
                        ,columnWidth    : .5
                        ,items          : [
                            standards.callFunction( '_createCombo', {
                                id              : 'itemName' + ledgerModule
                                ,fieldLabel     : 'Item'
                                ,store          : itemNameStore
                                ,allowBlank     : false
                                ,valueField     : 'id'
                                ,displayField   : 'name'
                            } )
                            ,standards.callFunction( '_createDateField', {
                                id		        : 'asOfDateLedger' + ledgerModule
                                ,fieldLabel     : 'As of Date'
                                ,allowBlank     : true
                            } )
                        ]
                    }
                ]
            }
        }

        function _ledgersGrid() {
            var store = standards.callFunction(  '_createRemoteStore' ,{
                fields:[
                    'affiliateName'
                    ,'date'
                    ,'referenceNum'
                    ,'ledgerUnit'
                    ,{ name: 'ledgerQty',       type: 'number' }
                    ,{ name: 'ledgerUnitCost',  type: 'number' }
                    ,{ name: 'ledgerAmount',    type: 'number' }
                ],
                url: route + 'getLedger'
            });

            let columns = [{
                header      : 'Affiliate'
                ,dataIndex  : 'affiliateName'
                ,flex       : 1
            }, {
                header      : 'Date'
                ,dataIndex  : 'date'
                ,flex       : 1
            }, {
                header      : 'Reference'
                ,dataIndex  : 'referenceNum'
                ,flex       : 1
            }, {
                header      : 'Quantity'
                ,xtype      : 'numbercolumn'
                ,format     : '0,000'
                ,dataIndex  : 'ledgerQty'
            }, {
                header      : 'Unit'
                ,dataIndex  : 'ledgerUnit'
            }, {
                header      : 'Unit Price'
                ,xtype      : 'numbercolumn'
                ,format     : '0,000.00'
                ,dataIndex  : 'ledgerUnitCost'
            }, {
                header      : 'Amount'
                ,width      : 80
                ,format     : '0,000.00'
                ,align      : 'right'
                ,dataIndex  : 'ledgerAmount'
                ,renderer   : function (val, meta, rec, rowIndex, colIndex, store) {
                    let ledgerQty               = rec.get('ledgerQty');
                        approvedCost            = rec.get('ledgerUnitCost');
                        total                   = (ledgerQty * approvedCost).toFixed(2);
                        rec.data.ledgerAmount   = total;
                    return total;
                }
            }];

            return {
                xtype           : 'container'
                ,columnWidth    : 1
                ,items          : [
                    standards.callFunction('_gridPanel', {
                        id              : 'gridItem' + ledgerModule
                        ,module         : ledgerModule
                        ,style          : 'margin-top:15px'
                        ,store          : store
                        ,noDefaultRow   : true
                        ,noPage         : true
                        ,plugins        : true
                        ,tbar           : {}
                        ,columns        : columns
                    })
                ]
            }
        }

        function _printPDFHandler() {
            var isBalance           = Ext.getCmp('btnBalance' + module).cls == 'menuActive' ? 1 : 0;
            var moduleIdentifier    = isBalance ? balanceModule : ledgerModule;
            var _grid               = Ext.getCmp( 'gridItem' + moduleIdentifier );

            standards.callFunction('_listPDF', {
                grid                    : _grid,
                customListPDFHandler    : function () {
                    var par = standards.callFunction('getFormDetailsAsObject', {
                        module          : moduleIdentifier,
                        getSubmitValue  : true,
                    });

                    par.title       = pageTitle + ' - ' + ( isBalance ? 'Balance' : 'Ledger' ) ;
                    par.isBalance   = isBalance;
                    par.idModule    = idModule;
                    par.grid        = Ext.encode( _grid.store.data.items.map((item)=>item.data) )

                    Ext.Ajax.request({
                        url     : route + 'printPDF',
                        params  : par,
                        success : function (res) {
                            if (isGae) {
                                window.open(route + 'viewPDF/' + par.title, '_blank');
                            } else {
                                window.open(baseurl + 'pdf/construction/' + par.title + '.pdf');
                            }
                        }
                    });
                }
            });
        }

        function _printExcelHandler() {
            var isBalance           = Ext.getCmp('btnBalance' + module).cls == 'menuActive' ? 1 : 0;
            var moduleIdentifier    = isBalance ? balanceModule : ledgerModule;
            var _grid               = Ext.getCmp( 'gridItem' + moduleIdentifier );

            standards.callFunction('_listExcel', {
                grid                    : _grid,
                customListExcelHandler  : function () {
                    var par = standards.callFunction('getFormDetailsAsObject', {
                        module          : moduleIdentifier,
                        getSubmitValue  : true,
                    });

                    par.title       = pageTitle + ' - ' + ( isBalance ? 'Balance' : 'Ledger' ) ;
                    par.isBalance   = isBalance;
                    par.idModule    = idModule;
                    par.grid        = Ext.encode( _grid.store.data.items.map((item)=>item.data) )
                    
                    Ext.Ajax.request({
                        url     : route + 'printExcel',
                        params  : par,
                        success : function (res) {
                            window.open(route + "download/" + par.title + '/construction');
                        }
                    });
                }
            });
        }

        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        return {
            initMethod: function (config) {
                route           = config.route;
                module          = config.module;
                canDelete       = config.canDelete;
                canPrint        = config.canPrint;
                pageTitle       = config.pageTitle;
                isGae           = config.isGae;
                canEdit         = config.canEdit;
                idModule        = config.idmodule;
                baseurl         = config.baseurl;
                idAffiliate     = config.idAffiliate;
                balanceModule   = module
                ledgerModule    = module + 'Ledger'

                return _mainPanel(config);
            }
        }
    }
}



