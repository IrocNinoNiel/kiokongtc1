/**
 * Developer: Christian P. Daohog
 * Module: Budget vs. Actual
 * Date: May 23, 2022
 * Finished:
 * Description: This module allows authorized users to generate a budget vs. actual monitoring of a tagged project.
 * DB Tables:
 * */

 function Budgetvsactual() {
    return function() {
        var baseurl, route, module, canDelete, canPrint, pageTitle, isGae, canEdit, idModule, totalDirectCost = 0;

        function _mainPanel(config) {
            return standards.callFunction('_mainPanel', {
                config          : config
                ,moduleType     : 'report'
                ,tbar           : {
                    noFormButton        : true
                    ,noListButton       : true
                    ,noPDFButton        : false
                    ,PDFHidden          : false
                    ,formPDFHandler     : _printPDF
                    ,formExcelHandler   : _printExcel
                }
                ,formItems      : [ _reportForm(config) ]
                ,moduleGrids    : [ _gridList(config) ]
            });
        }

        function _reportForm(config) {
            var projectNameStore = standards.callFunction('_createRemoteStore', {
                    fields    : [{name:'id', type:'number'}, 'name']
                    ,url      : route + 'getProjectNames'
                    ,startAt  :  0
                    ,autoload : false
                })
                ,projectStatusStore = standards.callFunction( '_createLocalStore' , {
                    data        : [ 'All', 'Suspended' ,'Ongoing', 'Completed' ]
                    ,startAt    : 0
                } );

            return {
                xtype       : 'fieldset'
                ,layout     : 'column'
                ,padding    : 10
                ,items      : [
                    {
                        xtype           : 'container'
                        ,columnWidth    : .5
                        ,items          : [
                            standards2.callFunction( '_createAffiliateCombo', {
                                hasAll      : 1
                                ,module     : module
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
                            } ),
                            standards.callFunction( '_createCombo', {
                                id              : 'projectStatus' + module
                                ,fieldLabel     : 'Project Status'
                                ,store          : projectStatusStore
                                ,valueField     : 'id'
                                ,displayField   : 'name'
                                ,value          : 0
                                ,listeners      : {
                                    select : function( me , record ){
                                        var projectNameStore = Ext.getCmp('projectName' + module).getStore();
                                        projectNameStore.proxy.extraParams.status = me.value;
                                        projectNameStore.load({});
                                    }
                                }
                            } ),
                            standards.callFunction( '_createCombo', {
                                id              : 'projectName' + module
                                ,fieldLabel     : 'Project Name'
                                ,store          : projectNameStore
                                ,valueField     : 'id'
                                ,displayField   : 'name'
                                ,value          : 0
                            } )
                        ]
                    }, {
                        xtype        : 'container'
                        ,columnWidth : .5
                        ,items       : [
                            standards.callFunction( '_createDateField', {
                                id			: 'asOfDate' + module
                                ,fieldLabel	: 'As of Date'
                                ,allowBlank	: true
                            } )
                        ]
                    }
                ]
            }
        }

        function getTotalDirectCost( ) {
            if(totalDirectCost == 0){
                let store = Ext.getCmp('gridItem' + module).getStore();
                let totalDataHolder = store.proxy.reader.jsonData.totalDirectCost;
                totalDirectCost = 0;
                totalDataHolder.forEach(element => {
                    totalDirectCost += parseFloat(element.total != null ? element.total : 0);
                });
            }
            return totalDirectCost;
        }

        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        function _gridList( config ) {
            var store = standards.callFunction(  '_createRemoteStore' ,{
                fields:[
                    'affiliateName'
                    ,'date'
                    ,'projectName'
                    ,'contractEffectivity'
                    ,'contractExpiry'
                    ,'projectStatus'
                    ,'profitLoss'
                    ,'subactivityName'
                    ,'ocm'
                    ,'contractorsProfit'
                    ,'vat'
                    ,'idConsActivityDetails'
                    ,{ name: 'contractDuration',   type: 'number' }
                    ,{ name: 'contractAmount',     type: 'number' }
                    ,{ name: 'actualAmountSpent',  type: 'number' }
                    ,{ name: 'totalAmount',        type: 'number' }
                    ,{ name: 'itemReleasedTotal',  type: 'number' } 
                    ,{ name: 'equipReleasedTotal', type: 'number' } 
                    ,{ name: 'laborReleasedTotal', type: 'number' } 
                    ,{ name: 'profitLoss',         type: 'number' }
                ]
                ,url: route + 'getBudgetVsActual'
            });

            let columns = [{
                header          : 'Affiliate'
                ,dataIndex      : 'affiliateName'
            }, {
                header          : 'Date'
                ,dataIndex      : 'date'
            }, {
                header          : 'Project Name'
                ,dataIndex      : 'projectName'
                ,minWidth       : 230
                ,flex           : 1
                ,columnWidth    : 10
            }, {
                header          : 'Contract Duration'
                ,dataIndex      : 'contractDuration'
                ,width          : 120
                ,columnWidth    : 10
            }, {
                header          : 'Contract Effectivity'
                ,dataIndex      : 'contractEffectivity'
                ,width          : 120
                ,columnWidth    : 10
            }, {
                header          : 'Contract Expiry'
                ,dataIndex      : 'contractExpiry'
                ,width          : 120
                ,columnWidth    : 10

            }, {
                header          : 'Contract Amount'
                ,xtype          : 'numbercolumn'
                ,dataIndex      : 'contractAmount'
                ,width          : 120
                ,columnWidth    : 10
            }, {
                header          : 'Actual Amount Spent'
                ,dataIndex      : 'actualAmountSpent'
                ,align          : 'right'
                ,format         : '0,000.00'
                ,width          : 130
                ,renderer   : function(val, meta, rec, rowIndex, colIndex, store){
                    let data = rec.data
                        ,totalDirectCost = getTotalDirectCost()
                        ,vat = parseInt(data.vat)/100 * totalDirectCost
                        ,ocm = parseInt(data.ocm)/100 * totalDirectCost
                        ,contractorsProfit = parseInt(data.contractorsProfit)/100 * totalDirectCost
                        ,totalIndirectCost = vat + ocm + contractorsProfit;

                    let totalAmountSpent = parseFloat((data.totalAmount + totalIndirectCost).toFixed(2));
                    rec.data.actualAmountSpent  = totalAmountSpent;
                    
                    return numberWithCommas(totalAmountSpent);
                }
            }, {
                header          : 'Project Status'
                ,dataIndex      : 'projectStatus'
            }, {
                header          : 'Profit/Loss'
                ,dataIndex      : 'profitLoss'
                ,align          : 'right'
                ,format         : '0,000.00'
                ,renderer   : function(val, meta, rec, rowIndex, colIndex, store){
                    let profitLoss = parseFloat(rec.data.contractAmount - rec.data.actualAmountSpent).toFixed(2);
                    rec.data.profitLoss   = profitLoss;

                    return numberWithCommas(profitLoss);
                }
            }];
            return {
                xtype        : 'container'
                ,columnWidth : 1
                ,items       : [
                    standards.callFunction('_gridPanel', {
                        id               : 'gridItem' + module
                        ,module          : module
                        ,style           : 'margin-top:15px'
                        ,store           : store
                        ,noDefaultRow    : true
                        ,noPage          : true
                        ,plugins         : false
                        ,tbar            : {}
                        ,columns         : columns
                    })
                ]
            }
        }

        function _printPDF() {
            var _grid = Ext.getCmp('gridItem' + module);

            standards.callFunction('_listPDF', {
                grid                    : _grid,
                customListPDFHandler    : function () {

                    var par = standards.callFunction('getFormDetailsAsObject', {
                        module          : module,
                        getSubmitValue  : true,
                    });
                    par.title    = pageTitle;
                    par.idModule = idModule;
                    par.grid     = Ext.encode( _grid.store.data.items.map((item)=>item.data) )

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

        function _printExcel() {
            var _grid = Ext.getCmp('gridItem' + module);

            standards.callFunction('_listExcel', {
                grid                    : _grid,
                customListExcelHandler  : function () {

                    var par = standards.callFunction('getFormDetailsAsObject', {
                        module          : module,
                        getSubmitValue  : true,
                    });
                    par.title    = pageTitle;
                    par.idModule = idModule;
                    par.grid     = Ext.encode( _grid.store.data.items.map((item)=>item.data) )

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

        return {
            initMethod: function (config) {
                route       = config.route;
                module      = config.module;
                canDelete   = config.canDelete;
                canPrint    = config.canPrint;
                pageTitle   = config.pageTitle;
                isGae       = config.isGae;
                canEdit     = config.canEdit;
                idModule    = config.idmodule;
                baseurl     = config.baseurl;
                idAffiliate = config.idAffiliate;

                return _mainPanel(config);
            }
        }
    }
}