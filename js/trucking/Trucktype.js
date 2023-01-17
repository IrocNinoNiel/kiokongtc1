function Trucktype() {

    return function () {

        var baseurl, route, module, canDelete, canPrint, pageTitle, isGae, canEdit, idModule, onEdit = 0, idTruckType = '';

        function _init() {
            
            Ext.Ajax.request({
                url: route + 'getCode',
                success: function (response) {
                    var resp = Ext.decode(response.responseText);
                    Ext.getCmp('idTruckType' + module).setValue(resp.view[0].idTruckType);
                }
            });
        }
 
        function _mainPanel(config) {
            return standards.callFunction('_mainPanel', {
                config      : config,
                module      : module,
                showOnForm  : true,
                tbar        : {
                    saveFunc         : _saveForm,
                    resetFunc        : _resetForm,
                    noPDFButton      : false, 
                    noExcelButton    : false,
                    PDFHidden        : false,
                    noFormButton     : true,
                    noListButton     : true,
                    hasFormExcel     : true,
                    formPDFHandler   : _printPDF,
                    formExcelHandler : _printExcel
                },
                formItems   : [
                    _TruckForm(config),
                    _gridList(config)
                ]
            });
        }

        function _TruckForm(config) {

            return {
                xtype   : 'container',
                layout  : 'column',
                items   : [{
                    xtype       : 'container',
                    columnWidth : 1,
                    items       : [

                        standards.callFunction('_createTextField', {
                            id          : 'idTruckType' + module,
                            fieldLabel  : 'Truck Type Code:',
                            readOnly    : true,
                            listeners   : {
                                afterrender : function () {
                                    _init();
                                }
                            }
                        }),
                        standards.callFunction('_createTextField', {
                            id          : 'truckType' + module,
                            fieldLabel  : 'Truck Type Name',
                            maxLength   : 20,
                            allowBlank  : false
                        })
                    ]
                }]
            }
        }

        function _gridList(config) {

            var truckTypeStore = standards.callFunction('_createRemoteStore', {
                fields: ['idTruckType', 'truckType'],
                url: route + 'getTruckTypeItems'
            });

            let columns = [{
                header      : 'Truck Type Code',
                minwidth    : 50,
                flex        : 1 / 6,
                dataIndex   : 'idTruckType'
            }, {
                header      : 'Truck Type',
                flex        : 1,
                minwidth    : 80,
                columnWidth : 100,
                dataIndex   : 'truckType'
            }, standards.callFunction('_createActionColumn', {
                icon        : 'pencil',
                tooltip     : 'Edit record',
                width       : 30,
                canEdit     : canEdit,
                Func        : _editRecord
            }), standards.callFunction('_createActionColumn', {
                canDelete   : canDelete,
                icon        : 'remove',
                tooltip     : 'Remove record',
                width       : 30,
                Func        : _deleteRecord
            })];

            let tbar = {
                canPrint    : false,
                noExcel     : true,
                route       : route,
                pageTitle   : pageTitle,
                content     : [
                    standards.callFunction('_createTextField', {
                        id          : 'searchTruckType' + module,
                        allowBlank  : true,
                        width       : 200,
                        emptyText   : 'Search truck type...',
                        listeners   : {
                            change : function (me, newVal, oldVal) {
                                truckTypeStore.proxy.extraParams = {
                                    filterValue: newVal
                                };
                                truckTypeStore.load({
                                    callback: function () {
                                        truckTypeStore.currentPage = 1;
                                    }
                                });
                            }
                        }
                    }), {
                        xtype   : 'button',
                        iconCls : 'glyphicon glyphicon-refresh',
                        handler : function () {
                            Ext.getCmp('searchTruckType' + module).reset();
                            truckTypeStore.proxy.extraParams = {};
                            truckTypeStore.load({});
                        }
                    }
                ]
            }

            function _editRecord(data) {
                module.getForm().retrieveData({
                    url     : route + 'getTruckType',
                    method  : 'post',
                    params  : {
                        idTruckType: data.idTruckType
                    },
                    success: function (response) {
                        onEdit = 1;
                        idTruckType = response.idTruckType;
                    }
                });
            }

            function _deleteRecord(data) {
                standards.callFunction('_createMessageBox', {
                    msg     : 'DELETE_CONFIRM',
                    action  : 'confirm',
                    fn      : function (btn) {
                        if (btn == 'yes') {
                            Ext.Ajax.request({
                                url     : route + 'deleteTruckType',
                                params  : {
                                    idTruckType : data.idTruckType,
                                    idModule    : idModule
                                },
                                success: function (response) {
                                    var resp = Ext.decode(response.responseText);
                                    standards.callFunction('_createMessageBox', {
                                        msg : (resp.match == 1) ? 'DELETE_USED' : 'DELETE_SUCCESS',
                                        fn  : function () {
                                            _init();
                                            Ext.resetGrid('gridItem' + module);
                                        }
                                    });
                                }
                            });
                        }
                    }
                });
            }

            return {
                xtype       : 'container',
                columnWidth : 1,
                items       : [
                    standards.callFunction('_gridPanel', {
                        id          : 'gridItem' + module,
                        module      : module,
                        style       : 'margin-top:15px',
                        store       : truckTypeStore,
                        tbar        : tbar,
                        columns     : columns,
                        listeners   : {
                            afterrender: function () {
                                truckTypeStore.load({});
                            }
                        }
                    })
                ]
            }
        }

        function _saveForm(form) {

            var params = {
                idTruckType : Ext.getCmp('idTruckType' + module).getValue().replace(/^0+/, ''),
                truckType   : Ext.getCmp('truckType' + module).getValue(),
                isEdit      : onEdit,
                idModule    : idModule
            }
            form.submit({
                url     : route + 'saveTruckType',
                params  : params,
                success : function (action, response) {
                    var resp = Ext.decode(response.response.responseText),
                        msg = (resp.match == 0) ? 'SAVE_SUCCESS' : 'SAVE_FAILURE';

                    standards.callFunction('_createMessageBox', {
                        msg     : msg,
                        action  : '',
                        fn: function () {
                            if (resp.match == 0) _resetForm(form);
                        }
                    });
                }
            });
        }

        function _resetForm(form) {
            _init();

            idTruckType = '';
            onEdit = 0;
            Ext.getCmp('truckType' + module).reset();
            Ext.resetGrid('gridItem' + module);
        }

        function _printExcel() {
            var _grid = Ext.getCmp('gridItem' + module);

            standards.callFunction('_listExcel', {
                grid                    : _grid,
                customListExcelHandler  : function () {
                    
                    var par = standards.callFunction('getFormDetailsAsObject', {
                        module          : module,
                        getSubmitValue  : true
                    });
                    par.title   = pageTitle;
                    par.idModule = idmodule;
                    
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

        function _printPDF() {
            var _grid = Ext.getCmp('gridItem' + module);

            standards.callFunction('_listPDF', {
                grid                    : _grid,
                customListPDFHandler    : function () {
                    
                    var par = standards.callFunction('getFormDetailsAsObject', {
                        module          : module,
                        getSubmitValue  : true
                    });
                    par.title = pageTitle;
                    par.idModule = idmodule;

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
