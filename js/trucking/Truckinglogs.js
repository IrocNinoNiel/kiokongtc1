/**
 * Developer: Christian P. Daohog
 * Module: Logsheet Monitoring
 * Date: Dec 29, 2021
 * Finished: 
 * Description: This module allows the authorized user to generate a Monitoring of drivers activity
 * DB Tables: 
 * */ 

function Truckinglogs() {
    return function() {
        var baseurl, route, module, canDelete, canPrint, pageTitle, isGae, canEdit, idModule, onEdit = 0, idAffiliate;

        function _init(){  
			Ext.getCmp( 'idAffiliate' + module).fireEvent( 'afterrender' );
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
                moduleGrids : [ _gridList(config)   ]
            });
        } 

        function _reportForm(config) {

            var staff = standards.callFunction(  '_createRemoteStore' ,{
				fields	: [ { name: 'idEu', type: 'number'} ,'name' ]
				,url 	: route + 'getUsers'
			})


            return {
                xtype       : 'fieldset'
                ,layout     : 'column'
                ,padding    : 10
                ,items:[
                    {
                        xtype   : 'container',
                        layout  : 'column',
                        items   : [{
                            xtype       : 'container',
                            columnWidth : .5,
                            items       : [
                                standards2.callFunction( '_createAffiliateCombo', {
                                    hasAll      : 1
                                    ,module     : module
                                    ,width      : 381
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
                                    id			    :'idUser'+module
                                    ,store		    : staff
                                    ,width      	: 381
                                    ,fieldLabel	    :'Select User'
                                    ,valueField	    : 'id'
                                    ,displayField   : 'name'
                                    ,value		    : 0
                                    ,listeners      : {
                                        afterrender : function(){
                                            var me  = this;
                                            me.store.load( {
                                                callback    : function(){
                                                    me.setValue( 0 );
                                                }
                                            } )
                                        }
                                    }
                                }),
                                standards.callFunction( '_createDateRange',{
                                    sdateID			: 'sdate' + module
                                    ,edateID		: 'edate' + module
                                    ,id				: 'fromTo' + module
                                    ,noTime			:  true
                                    ,fromFieldLabel	: 'Date'
                                })
                            ]
                        }]
                    }
                ]
            } 
        }

        function _gridList() {

            var store = standards.callFunction(  '_createRemoteStore' ,{
                fields:[
                    {name:'datelog',type:'date'}
                    ,{name:'time',type:'time'}
                    ,'affiliateName'
                    ,'fullName'
                    ,'euName'
                    ,'euTypeName'
                    ,'ref'
                    ,'referenceNum'
                    ,'code'
                    ,'actionLogDescription'
                ], 
                url: route + 'getHistory'
            });

            var columns = [{	
                header 		: 'Date'
                ,dataIndex 	: 'datelog'
                ,xtype		: 'datecolumn'
                ,format		: 'm/d/Y'
            }
            ,{	
                header 		: 'Time'
                ,dataIndex 	: 'time'
            }
            ,{	
                header 		: 'Affiliate'
                ,dataIndex 	: 'affiliateName'
            }
            ,{	
                header 		: 'User Full Name'
                ,dataIndex 	: 'fullName'
            }
            ,{	
                header 		: 'User Name'
                ,dataIndex 	: 'euName'
            }
            ,{	
                header 		: 'User Type'
                ,dataIndex 	: 'euTypeName'
            }
            ,{	
                header 		: 'Ref'
                ,dataIndex 	: 'code'
            }
            ,{	
                header 		: 'Number'
                ,dataIndex 	: 'referenceNum'
            }
            ,{	
                header		: 'Description'
                ,dataIndex	: 'actionLogDescription'
                ,flex		: 1
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
                        module          : module,
                        getSubmitValue  : true,
                    });
                    par.title    = pageTitle;
                    par.idModule = idModule;
                    
                    Ext.Ajax.request({
                        url     : route + 'printExcel',
                        params  : par,
                        success : function (res) {
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