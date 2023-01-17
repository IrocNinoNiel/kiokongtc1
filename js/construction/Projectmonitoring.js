/**
 * Developer: Christian P. Daohog
 * Module: Project Monitoring
 * Date: Jan 31, 2022
 * Finished:
 * Description: This module allows authorized users to generate and monitor the projects registered in the system.
 * DB Tables:
 * */

function Projectmonitoring() {
    return function() {
        var baseurl, route, module, canDelete, canPrint, pageTitle, isGae, canEdit, idModule, onEdit = 0;

        function _init(){
			Ext.getCmp( 'idAffiliate' + module).fireEvent( 'afterrender' );
            Ext.getCmp( 'projectName' + module).fireEvent( 'afterrender' );
		}

        function _mainPanel(config) {
            return standards.callFunction('_mainPanel', {
                config              : config,
                moduleType          : 'report',
                afterResetHandler 	: _init,
                tbar                : {
                    noFormButton        : true
                    ,noListButton       : true
                    ,noPDFButton        : false
                    ,PDFHidden          : false
                    ,formPDFHandler     : _printPDF
                    ,formExcelHandler   : _printExcel
                },
                formItems   : [ _reportForm(config) ],
                moduleGrids : [ _gridList(config) ]
            });
        }

        function _reportForm(config) {
            var projectNameStore = standards.callFunction('_createRemoteStore', {
                    fields    : [{name:'id', type:'number'}, 'name']
                    ,url      : route + 'getProjectNames'
                    ,startAt  :  0
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
                                id              : 'projectName' + module
                                ,fieldLabel     : 'Project Name'
                                ,store          : projectNameStore
                                ,valueField     : 'id'
                                ,displayField   : 'name'
                                ,value          : 0
                                ,listeners      : {
                                    afterrender : function(){
                                        projectNameStore.load({
                                            callback    : function(){
                                                Ext.getCmp('projectName' + module).setValue( 0 );
                                            }
                                        });
                                    }
                                    ,select     : function( me , record ){
                                    }
                                }
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

        function _gridList( config ) {
            var store = standards.callFunction(  '_createRemoteStore' ,{
                fields:[
                    'dateStart',
                    'affiliateName',
                    'idContract',
                    'projectName',
                    { name: 'contractDuration', type: 'number' },
                    { name: 'contractAmount',   type: 'number' },
                    'contractEffectivity',
                    'contractExpiry',
                    'licenseUsed',
                    'licenseType',
                    'status',
                    'statusType',
                    'percentAccomplished',
                    'warrantyDateFrom',
                    'warrantyDateTo',
                ],
                url: route + 'getProjectActivity'
            });



            let columns = [{
                header      : 'Affiliate',
                dataIndex   : 'affiliateName'
            },{
                header      : 'Contract ID',
                dataIndex   : 'idContract'
            }, {
                header      : 'Project Name',
                dataIndex   : 'projectName',
                width       : 230,
                columnWidth : 10
            }, {
                header      : 'Contract Duration',
                xtype       : 'numbercolumn',
                format      : '0,000',
                dataIndex   : 'contractDuration',
                width       : 150,
                columnWidth : 10
            }, {
                header      : 'Contract Amount',
                xtype       : 'numbercolumn',
                dataIndex   : 'contractAmount',
                width       : 150,
                columnWidth : 10
            }, {
                header      : 'Contract Effectivity',
                dataIndex   : 'contractEffectivity',
                width       : 150,
                columnWidth : 10
            }, {
                header      : 'Contract Expiry',
                dataIndex   : 'contractExpiry',
                width       : 150,
                columnWidth : 10
            }, {
                header      : 'License Used',
                dataIndex   : 'licenseUsed'
            }, {
                header      : 'License Type',
                dataIndex   : 'licenseType'
            }, {
                header      : 'Status',
                dataIndex   : 'status'
            }, {
                header      : 'Status Type',
                dataIndex   : 'statusType'
            }, {
                header      : '% Accomplished',
                dataIndex   : 'percentAccomplished'
                ,renderer   : function(val, meta, rec, rowIndex, colIndex, store) {
                    var duration, startDate, endDate, percentage  = 0;
                        duration    = rec.get('contractDuration');
                        startDate   = new Date(rec.get('dateStart'));
                        endDate     = new Date();

                        if( duration != 0 && startDate <= endDate ) {
                            days        = Math.round((endDate - startDate) / (1000 * 60 * 60 * 24));
                            percentage  = ((days / duration) * 100).toFixed(2);
                        }
                        percentage = percentage + '%';
                        rec.data.percentAccomplished   = percentage ;

                    return percentage;
                }
            }, {
                text      : 'Warranty Date',
                columns   : [
                    {
                        header      : 'From'
                        ,width      : 80
                        ,dataIndex  : 'warrantyDateFrom'
                    }, {
                        header      : 'To'
                        ,width      : 80
                        ,dataIndex  : 'warrantyDateTo'
                    }
                ]
            }];

            return {
                xtype       : 'container',
                columnWidth : 1,
                items       : [
                    standards.callFunction('_gridPanel', {
                        id              : 'gridItem' + module,
                        module          : module,
                        style           : 'margin-top:15px',
                        store           : store,
                        noDefaultRow    : true,
                        noPage          : true,
                        plugins         : true,
                        tbar            : {},
                        columns         : columns
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
                            console.log(route + "download/" + par.title + '/construction');
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


