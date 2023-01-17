/**
 * Developer: Christian P. Daohog
 * Module: Driver Performance Summary
 * Date: Jan 19, 2022
 * Finished:
 * Description: This module allows authorized users to generate a monitoring of fuels of the trucks used.
 * DB Tables: 
 * */ 
 
function Driverperformancesummary() {
    return function() {
        var baseurl, route, module, canDelete, canPrint, pageTitle, isGae, canEdit, idModule, onEdit = 0;

        function _init(){  
			Ext.getCmp( 'idAffiliate' + module).fireEvent( 'afterrender' );
            Ext.getCmp( 'projectName' + module).fireEvent( 'afterrender' );
            Ext.getCmp( 'projectType' + module).fireEvent( 'afterrender' );
            Ext.getCmp( 'driversName' + module).fireEvent( 'afterrender' );
            Ext.getCmp( 'truckType'   + module).fireEvent( 'afterrender' );
		}

        function _mainPanel(config) {
            return standards.callFunction('_mainPanel', {
                 config            : config
                ,moduleType        : 'report'
                ,afterResetHandler : _init
                ,tbar              : {
                     noFormButton     : true
                    ,noListButton     : true
                    ,noPDFButton      : false
                    ,PDFHidden        : false
                    ,formPDFHandler   : _printPDF
                    ,formExcelHandler : _printExcel
                }
                ,formItems         : [ _reportForm(config) ]
                ,moduleGrids       : [ _gridList(config) ]
            });
        }

        function _reportForm(config) {
            
            var projectTypeStore = standards.callFunction('_createLocalStore', {
                     data     : ['Construction', 'Trucking']
                    ,startAt  :  0
                })
                ,truckTypeStore = standards.callFunction('_createRemoteStore', {
                    fields    : [{name:'id', type:'number'}, 'name']
                   ,url       : route + 'getTruckTypes'
                   ,startAt   :  0
                   ,autoload  : true
               })
                ,projectNameStore = standards.callFunction('_createRemoteStore', {
                     fields   : [{name:'id', type:'number'}, 'name']
                    ,url      : route + 'getProjectNames'
                    ,startAt  :  0
                    ,autoload : true 
                })
                ,plateNumberStore = standards.callFunction('_createRemoteStore', {
                     fields   : [{name:'id', type:'number'}, 'name']
                    ,url      : route + 'getPlateNumbers'
                    ,startAt  :  0
                    ,autoload : true
                })
                ,driverStore = standards.callFunction('_createRemoteStore', {
                     fields   : [{name:'id', type:'number'}, 'name']
                    ,url      : route + 'getDrivers'
                    ,startAt  :  0
                    ,autoload : true
                });

                return {
                    xtype    : 'fieldset'
                    ,layout  : 'column'
                    ,padding : 10
                    ,items   :[
                        {
                             xtype       : 'container'
                            ,columnWidth : .5
                            ,items       : [
                                standards2.callFunction( '_createAffiliateCombo', {
                                     hasAll     : 1
                                    ,module     : module
                                    ,allowBlank : true
                                    ,listeners  : {
                                        afterrender : function(){
                                            var me  = this;
                                            me.store.load( {
                                                callback : function(){
                                                    if( me.store.data.length > 1 ) {
                                                        me.setValue( 0 );
                                                    } else {
                                                        me.setValue( parseInt(Ext.getConstant('AFFILIATEID'),10) );
                                                    }
                                                }
                                            } )
                                        } 
                                        ,select     : function(){
                                            var me  = this;
                                            Ext.getCmp( 'supplierCmb' + module ).store.proxy.extraParams.idAffiliate = me.getValue();
                                            Ext.getCmp( 'supplierCmb' + module ).store.load( {
                                                callback : function() {
                                                    Ext.getCmp( 'idItem' + module ).setValue( 0 );
                                                }
                                            } )
                                        }
                                    }
                                } ),
                                standards.callFunction( '_createCombo', {
                                     id             : 'projectType' + module
                                    ,fieldLabel     : 'Project Type'
                                    ,store          : projectTypeStore
                                    ,valueField     : 'id'
                                    ,displayField   : 'name'
                                    ,value          : 0
                                    ,listeners      : {
                                        afterrender : function(){
                                            this.setValue( 0 );
                                            projectNameStore.proxy.extraParams.isConstruction = this.getValue() == 0 ? 1 : 0;
                                            projectNameStore.load({
                                                callback    : function(){
                                                    Ext.getCmp( 'projectName' + module ).setValue( 0 );
                                                }
                                            });
                                        }
                                        ,select     : function( me , record ){
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
                                        beforeQuery :  function() {
                                            projectNameStore.proxy.extraParams.isConstruction = Ext.getCmp('projectType' + module).getValue() == 0 ? 1 : 0;
                                            projectNameStore.load({
                                                callback    : function(){
                                                    Ext.getCmp('projectName' + module).setValue( 0 );
                                                }
                                            });
                                        }
                                        ,select     : function( me , record ){
                                        }
                                    }
                                } ),
                                standards.callFunction( '_createCombo', {
                                    id              : 'truckType' + module
                                    ,fieldLabel     : 'Truck Type'
                                    ,store          : truckTypeStore
                                    ,valueField     : 'id'
                                    ,displayField   : 'name'
                                    ,listeners      : {
                                        afterrender : function(){
                                            truckTypeStore.load({
                                                callback    : function(){
                                                    Ext.getCmp('truckType' + module).setValue( 0 );
                                                }
                                            });
                                        }
                                        ,select     : function( me , record ){
                                        }
                                    }
                                } ),
                                
                                
                                standards.callFunction( '_createCombo', {
                                    id              : 'plateNumber' + module
                                    ,fieldLabel     : 'Plate Number'
                                    ,style          : 'margin-bottom:10px;'
                                    ,store          : plateNumberStore
                                    ,valueField     : 'id'
                                    ,displayField   : 'name'
                                    ,value          : 0
                                    ,listeners      : {
                                        afterrender : function(){
                                            plateNumberStore.load({
                                                callback    : function(){
                                                    Ext.getCmp('plateNumber' + module).setValue( 0 );
                                                }
                                            });
                                        }
                                        ,select     : function( me , record ){
                                        }
                                    }
                                } )
                            ]
                        }, {
                            xtype: 'container'
                            ,columnWidth :.5
                            ,items : [
                                standards.callFunction( '_createCombo', {
                                    id              : 'driversName' + module
                                    ,fieldLabel     : 'Driver'
                                    ,store          : driverStore 
                                    ,width      	: 381
                                    ,valueField     : 'id'
                                    ,displayField   : 'name'
                                    ,listeners      : {
                                        afterrender : function(){
                                            driverStore.load({
                                                callback    : function(){
                                                    Ext.getCmp('driversName' + module).setValue( 0 );
                                                }
                                            });
                                        }
                                        ,select     : function( me , record ){
                                        }
                                    }
                                } ),
                                ,standards.callFunction( '_createDateRange',{
                                    sdateID			: 'sdate' + module
                                    ,edateID		: 'edate' + module
                                    ,id				: 'fromTo' + module
                                    ,noTime			: true
                                    ,fromFieldLabel	: 'Date'
                                }),
                            ]
                        }
                    ]
                }
        }

        function _gridList( config ) {

            var store = standards.callFunction(  '_createRemoteStore' ,{
                fields:[
                     'date'
                    ,'area'
                    ,'lubricant'
                    ,{ name: 'trip',            type: 'number' }
                    ,{ name: 'noOfLoads',       type: 'number' }
                    ,{ name: 'fuelConsumed',    type: 'number' }
                    ,{ name: 'total',           type: 'number' } 
                ], 
                url: route + 'getPerformance'
            });

            let columns = [{
                 header      : 'Date'
                ,dataIndex   : 'date'
                ,xtype       : 'datecolumn'
                ,format      : 'm/d/Y'
            }, {
                 header      : 'Destination'
                ,dataIndex   : 'area'
                ,flex        : 1
            }, {
                 header      : 'No. of Trips'
                ,dataIndex   : 'trip'
                ,xtype       : 'numbercolumn'
                ,format      : '0,000'
                ,hasTotal    : true
            }, {
                 header      : 'No. of Loads'
                ,dataIndex   : 'noOfLoads'
                ,xtype       : 'numbercolumn'
                ,format      : '0,000'
                ,hasTotal    : true
            }, {
                 header      : 'Fuel Consumed'
                ,dataIndex   : 'fuelConsumed'
                ,xtype       : 'numbercolumn'
                ,hasTotal    : true
            }, {
                 header      : 'Lubricants'
                ,dataIndex   : 'lubricant'
                ,xtype       : 'numbercolumn'
                ,hasTotal    : true
            }];

            return {
                 xtype       : 'container'
                ,columnWidth : 1
                ,items       : [
                    standards.callFunction('_gridPanel', {
                         id              : 'gridItem' + module
                        ,module          : module
                        ,style           : 'margin-top:15px'
                        ,store           : store
                        ,noDefaultRow    : true
                        ,noPage          : true
                        ,plugins         : true
                        ,tbar            : {}
                        ,columns         : columns
                    })
                ]
            }
        }

        function _printPDF() {
            var _grid = Ext.getCmp('gridItem' + module);

            standards.callFunction('_listPDF', {
                grid                 : _grid,
                customListPDFHandler : function () {
                    
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
                                window.open(baseurl + 'pdf/trucking/' + par.title + '.pdf');
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
                         module          : module
                        ,getSubmitValue  : true
                    });
                    par.title    = pageTitle;
                    par.idModule = idModule;
                    
                    Ext.Ajax.request({
                        url     : route + 'printExcel'
                        ,params  : par
                        ,success : function (res) {
                            window.open(route + "download/" + par.title + '/trucking');
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


