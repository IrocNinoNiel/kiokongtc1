/**
 * Developer: Niño Niel B. Iroc
 * Module: Cash Advance Summary
 * Date: Jan 16, 2023
 * Finished:
 * Description: 
 * DB Tables:
 * */

function Cashadvancesummary(){

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
                _cashAdvanceSummaryForm(config)
                ,
            ]
            ,moduleGrids    : _gridCashAdvanceSummary()
        });
    }

    function _gridCashAdvanceSummary() {

        var cashAdvanceSummaryStore   = standards.callFunction( '_createRemoteStore', {
            fields      : [
                'affiliate'
                ,'date'
                ,'reference'
                ,'type'
                ,'name'
                ,'totalAmount'
            ]
            ,url        : route + 'getCashAdvanceSummary'
        } );

        return standards.callFunction( '_gridPanel', {
            id          : 'gridLogsSummary' + module
            ,module     : module
            ,store      : cashAdvanceSummaryStore
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
                ,{  header          : 'Reference'
                    ,dataIndex      : 'reference'
                    ,width          : 100
                    ,columnWidth    : 8
                }
                ,{  header          : 'Type'
                    ,dataIndex      : 'type'
                    ,width          : 150
                    ,columnWidth    : 15
                }
                ,{  header          : 'Name'
                    ,dataIndex      : 'name'
                    ,minWidth       : 150
                    ,flex           : 1
                    ,columnWidth    : 15
                }
                ,{  header          : 'Total Amount'
                    ,dataIndex      : 'totalAmount'
                    ,width          : 100
                    ,columnWidth    : 7
                }
            ]
        } )

    }

    function _cashAdvanceSummaryForm( config ) {
        let typeStore = [];
        let employeeStore = [];

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

                        , standards.callFunction( '_createCombo', {
                            id              : 'idEmployeeCombo' + module
                            ,store          : employeeStore
                            ,editable       : false
                            ,value          : 1
                            ,fieldLabel     : 'Employee'
                            ,listeners      : {
                                select  : function( me, record ){
                                    console.log(me);
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

    function _printExcel(){

    }

    function _printPDF(){

    }

    return function(){ 


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

}