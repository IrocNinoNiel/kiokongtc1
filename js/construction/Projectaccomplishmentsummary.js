/**
 * Developer: Niño Niel B. Iroc
 * Module: Logssummary
 * Date: Jan 11, 2023
 * Finished:
 * Description: 
 * DB Tables:
 * */

function Projectaccomplishmentsummary(){

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
                    _projectAccomplishmentSummaryForm(config)
                    ,
                ]
                ,moduleGrids    : _gridProjectAccomplishmentSummary()
            });
        }

        function _projectAccomplishmentSummaryForm(config) {

            let projectStore =standards.callFunction(  '_createRemoteStore' ,{
                fields      : [ { name : 'id', type : 'number' }, 'name' ]
                ,url        : route + 'getProjectNames'
                ,autoLoad   : false
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
                                id              : 'idProject' + module
                                ,fieldLabel     : 'Project Name'
                                ,store          : projectStore
                                ,allowBlank     : true
                                ,displayField   : 'name'
                                ,valueField     : 'id'
                                ,listeners      : {
                                    afterrender : function(){
                                        var me  = this;
                                        me.store.load( {
                                            callback    : function(){
                                                me.setValue( 0 );
                                            }
                                        } )
                                    }
                                    ,select  : function( me, record ){
                                       
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
                                    standards.callFunction( '_createDateField', {
                                        id			: 'asOfDate' + module
                                        ,fieldLabel	: 'As of Date'
                                        ,allowBlank	: true
                                    } )
                                ]
                            }
                           
                        ]
                    }
                ]
            }
        }

        function _gridProjectAccomplishmentSummary(){
            var logSummaryStore   = standards.callFunction( '_createRemoteStore', {
                fields      : [
                    'affiliateName'
                    ,'date'
                    ,'referenceNum'
                    ,'projectName'
                    ,'dateFrom'
                    ,'dateTo'
                    ,'percentAccomplished'
                    ,'projectStatus'
                    ,'statusType'
                ]
                ,url        : route + 'getProjectAccomplishmentSummary'
            } );
            
            return standards.callFunction( '_gridPanel', {
                id          : 'gridProjectAccomplishmentSummary' + module
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
                        ,dataIndex      : 'referenceNum'
                        ,width          : 100
                        ,columnWidth    : 8
                    }
                    ,{  header          : 'Project Name'
                        ,dataIndex      : 'projectName'
                        ,width          : 150
                        ,columnWidth    : 15
                    }
                    ,{  text            : 'Accomplishment Date'
                            ,columns    : [
                                {   
                                    header          : 'Date From'
                                    ,dataIndex      : 'dateFrom'
                                    ,xtype          : 'datecolumn'
                                    ,format         : 'm/d/Y'
                                    ,width          : 135
                                }
                                ,{   
                                    header          : 'Date To'
                                    ,dataIndex      : 'dateTo'
                                    ,xtype          : 'datecolumn'
                                    ,format         : 'm/d/Y'
                                    ,width          : 135
                                }
                            ]
                    }
                    ,{  header          : '% Accomplished'
                        ,dataIndex      : 'percentAccomplished'
                        ,width          : 100
                        ,columnWidth    : 7
                    }
                    ,{  header          : 'Project Status'
                        ,dataIndex      : 'projectStatus'
                        ,width          : 150
                        ,columnWidth    : 8
                    }
                    ,{  header          : 'Status Type'
                        ,dataIndex      : 'statusType'
                        ,width          : 100
                        ,columnWidth    : 8
                    }
                ]
            } )
        }


        function _printExcel() {
            var _grid = Ext.getCmp('gridProjectAccomplishmentSummary' + module);

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
                            console.log(route + "download/" + par.title + '/construction');
                            window.open(route + "download/" + par.title + '/construction');
                        }
                    });
                }
            });
        }

        function _printPDF(){
            var _grid = Ext.getCmp('gridProjectAccomplishmentSummary' + module);

            standards.callFunction('_listPDF', {
                grid                    : _grid,
                customListPDFHandler    : function () {
                    
                    var par = standards.callFunction('getFormDetailsAsObject', {
                        module          : module,
                        getSubmitValue  : true,
                        
                    });
                    par.title    = pageTitle;
                    par.idModule = idModule;
                    par.grid     = Ext.encode( _grid.store.data.items.map((item)=>item.data) );

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