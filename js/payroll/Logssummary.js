/**
 * Developer: Niño Niel B. Iroc
 * Module: Logssummary
 * Date: Jan 11, 2023
 * Finished:
 * Description: 
 * DB Tables:
 * */

function Logssummary(){

    return function(){

        var idAffiliate, baseurl, route, module, canDelete, canPrint, pageTitle, isGae, canEdit, idModule, onEdit = 0, totalApprovedAmnt = 0,
        idInvoice = '', idAccomplishment  = '', boqData = {}, boqDataHolder = [], dataHolder = {};

        function _mainPanel(config) {
            return standards2.callFunction('_mainPanelTransactions', {
                 config      : config
                ,module      : module
                ,moduleType  : 'report'
                ,hasApproved : false
                ,tbar        : {
                    noFormButton        : true
                    ,noListButton       : true
                    ,noPDFButton        : false
                    ,PDFHidden          : false
                    ,formPDFHandler     : _printPDF
                    ,formExcelHandler   : _printExcel
                },
                formItems   : [
                    _logsSummaryForm(config)
                    ,
                ]
                ,moduleGrids    : _gridLogsSummary()
            });
        }

        function _logsSummaryForm(config) {

            let typeStore = [];
            let positionStore = [];

            let nameStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {name : 'idEmployee', type : 'number' }, 'employeeName', {name : 'idClassification', type : 'number' }, 'status', 'classification']
                ,url        : route + 'getEmployees'
                ,startAt    :  0
                ,autoLoad   : true
            });

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
                                                me.setValue( 0 );
                                            }
                                        } )
                                    }
                                    ,select : function(){
                                        var me  = this;  
                                    }
                                }
                            } )
                            ,standards.callFunction( '_createCombo', {
                                id              : 'idType' + module
                                ,store          : typeStore
                                ,editable       : false
                                ,value          : 0
                                ,fieldLabel     : 'Type'
                                ,listeners      : {
                                    select  : function( me, record ){
                                       
                                    }
                                }
                            } )
                            ,standards.callFunction( '_createCombo', {
                                id              : 'idPosition' + module
                                ,store          : positionStore
                                ,editable       : false
                                ,value          : 0
                                ,fieldLabel     : 'Position'
                                ,listeners      : {
                                    select  : function( me, record ){
                                       
                                    }
                                }
                            } )
                            ,standards.callFunction( '_createCombo', {
                                id              : 'idName' + module
                                ,fieldLabel     : 'Name'
                                ,store			: nameStore
                                ,emptyText		: 'Select employee name...'
                                ,displayField   : 'employeeName'
                                ,valueField     : 'employeeName'
                                ,listeners      : {
                                    select  : function( me, record ){
                                       
                                    }
                                }
                            } )
                        ]
                    },
                    {
                        xtype           : 'container'
                        ,columnWidth    : .5
                        ,items          : [
                            {
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
                ]
            }
        }

        function _gridLogsSummary(){
            var logSummaryStore   = standards.callFunction( '_createRemoteStore', {
                fields      : [
                    'affiliate'
                    ,'date'
                    ,'name'
                    ,'position'
                    ,'timeStart'
                    ,'timeEnd'
                    ,'totalRegularHours'
                    ,'overTimeHours'
                    ,'totalHours'
                ]
                ,url        : route + 'getLogsSummary'
            } );
            
            return standards.callFunction( '_gridPanel', {
                id          : 'gridLogsSummary' + module
                ,module     : module
                ,store      : logSummaryStore
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
                        ,dataIndex      : 'affiliate'
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
                    ,{  header          : 'Name'
                        ,dataIndex      : 'name'
                        ,width          : 100
                        ,columnWidth    : 8
                    }
                    ,{  header          : 'Position'
                        ,dataIndex      : 'position'
                        ,width          : 150
                        ,columnWidth    : 15
                    }
                    ,{  header          : 'Time Start'
                        ,dataIndex      : 'timeStart'
                        ,minWidth       : 150
                        ,flex           : 1
                        ,columnWidth    : 15
                    }
                    ,{  header          : 'Time End'
                        ,dataIndex      : 'timeEnd'
                        ,width          : 100
                        ,columnWidth    : 7
                    }
                    ,{  header          : 'Total Regular Hours'
                        ,dataIndex      : 'totalRegularHours'
                        ,width          : 150
                        ,columnWidth    : 8
                    }
                    ,{  header          : 'Overtime Hours'
                        ,dataIndex      : 'overtimeHours'
                        ,width          : 100
                        ,columnWidth    : 8
                    }
                    ,{  header          : 'Total Hours'
                        ,dataIndex      : 'totalHours'
                        ,width          : 100
                        ,columnWidth    : 8
                    }
                ]
            } )
        }


        function _printExcel(){
            var _grid = Ext.getCmp('gridLogsSummary' + module);

            standards.callFunction('_listExcel', {
                grid                    : _grid,
                customListExcelHandler  : function () {
                    
                    var par = standards.callFunction('getFormDetailsAsObject', {
                        module          : module,
                        getSubmitValue  : true,
                    });
                    par.title    = pageTitle;
                    par.idModule = idModule;
                    
                    Ext.Ajax.request({
                        url     : route + 'printExcel',
                        params  : par,
                        success : function (res) {
                            window.open(route + "download/" + par.title + '/payroll');
                        }
                    });
                }
            });
        }

        function _printPDF(){
            var _grid = Ext.getCmp('gridLogsSummary' + module);

            standards.callFunction('_listPDF', {
                grid                    : _grid,
                customListPDFHandler    : function () {
                    
                    var par = standards.callFunction('getFormDetailsAsObject', {
                        module          : module,
                        getSubmitValue  : true,
                        
                    });
                    par.title    = pageTitle;
                    par.idModule = idModule;

                    Ext.Ajax.request({
                        url     : route + 'printPDF',
                        params  : par,
                        success : function (res) {
                            if (isGae) {
                                window.open(route + 'viewPDF/' + par.title, '_blank');
                            } else {
                                window.open(baseurl + 'pdf/payroll/' + par.title + '.pdf');
                            }
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
                idAffiliate = config.idAffiliate

                return _mainPanel(config);
            }
        }
        
    }
};