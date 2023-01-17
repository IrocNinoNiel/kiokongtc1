function Truckproject() {

    return function () {

        var route, module, canDelete, canPrint, pageTitle, isGae, canEdit, idModule, onEdit = 0, 
        idTruckProject = '', idManual = null, baseurl ;

        function getCode() {
            
            Ext.Ajax.request({
                url     : route + 'getCode',
                success : function (response) {
                    var resp = Ext.decode(response.responseText);
                    Ext.getCmp('idProject' + module).setValue(resp.view[0].idTruckProject);
                }
            });
        }

        function getCode() {
            Ext.Ajax.request({
                url     : route + 'getCode',
                success : function (response) {
                    var resp        = Ext.decode(response.responseText);
                    idTruckProject  = resp.view[0].idTruckProject;

                    Ext.getCmp('idProject' + module).setValue(idTruckProject);
                }
            });
        }

        function _mainPanel( config ) {

            return standards.callFunction('_mainPanel', {
                config      : config
                ,formItems  : [
                    ,ProjectForm( config )
                    ,ProjectList( config )
                ]
                ,showOnForm  : true
                ,tbar       : {
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
            console.log(_grid);
            standards.callFunction('_listExcel', {
                grid                    : _grid,
                customListExcelHandler  : function () {
                    
                    var par = standards.callFunction('getFormDetailsAsObject', {
                        module          : module,
                        getSubmitValue  : true
                    });
                    par.title   = pageTitle;

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


        function ProjectForm(config) {

            let customerStore = standards.callFunction('_createRemoteStore', {
                fields: [ {name: 'idCustomer', type: 'number'}, 'name' ],
                url: route + 'getCustomers'
            });

            return {
                xtype   : 'container',
                items   : [{
                    xtype   : 'container',
                    layout  : 'column',
                    items   : [{
                        xtype       : 'container',
                        columnWidth : .5,
                        items       : [
                            standards.callFunction('_createCheckField', {
                                id          : 'isManual' + module,
                                fieldLabel  : 'Manual ID',
                                listeners   : {
                                    change  : function (me) {
                                        Ext.getCmp('idProject' + module).reset();
                                        if (me.getValue()) {
                                            Ext.getCmp('idProject' + module).isNumber   = true;
                                            Ext.getCmp('idProject' + module).isDecimal  = false;
                                            Ext.getCmp('idProject' + module).hasComma   = false;
                                            Ext.getCmp('idProject' + module).maskRe     = /[0-9.]/;
                                            Ext.getCmp('idProject' + module).setReadOnly(false);
                                            Ext.getCmp('idProject' + module).setValue(idManual); 
                                        } else {
                                            Ext.getCmp('idProject' + module).isNumber   = false;
                                            Ext.getCmp('idProject' + module).isDecimal  = true;
                                            Ext.getCmp('idProject' + module).maskRe     = /[^^]/;
                                            Ext.getCmp('idProject' + module).setReadOnly(true);
                                            Ext.getCmp('idProject' + module).setValue(idTruckProject); 
                                        }
                                    }
                                }
                            }),
                            standards.callFunction('_createTextField', {
                                id           : 'idProject' + module,
                                fieldLabel   : 'Project ID',
                                maxLength    : 11,
                                readOnly     : true,
                                allowBlank   : false,
                                listeners    : {
                                    afterrender : function () {
                                        getCode();
                                    }
                                }
                            }),
                            standards.callFunction('_createTextField', {
                                id           : 'projectName' + module,
                                fieldLabel   : 'Project Name',
                                allowBlank   : false,
                            }),
                            standards.callFunction('_createCombo', {
                                id           : 'idCustomer' + module,
                                fieldLabel   : 'Customer Name',
                                store        : customerStore,
                                allowBlank   : false,
                                displayField : 'name',
                                valueField   : 'idCustomer',
                            }),
                            standards.callFunction('_createTextArea', {
                                id           : 'remarks' + module,
                                fieldLabel   : 'Remarks',
                                maxLength    : 255,
                            }),
                            standards.callFunction('_createCheckField', {
                                id           : 'inactive' + module,
                                fieldLabel   : 'Inactive',
                                hidden       : true
                            })
                        ]
                    }, {
                        xtype        : 'container',
                        columnWidth  : .5,
                        layout       : 'hbox',
                        items        : [{
                            xtype   : 'label',
                            html    : 'Affiliate' + Ext.getConstant('REQ') + ':',
                            width   : 120
                        }, _affiliateGrid()]
                    }]
                }]
            }
        }

        function _affiliateGrid() {
            var affiliateStore = standards.callFunction('_createRemoteStore', {
                fields      : ['idAffiliate', 'affiliateName', { name    : 'chk', type    : 'bool' }],
                url : Ext.getConstant('STANDARD_ROUTE2') + 'getAffiliates'
            });


            var sm = new Ext.selection.CheckboxModel({
                checkOnly   : true
            });

            return standards.callFunction('_gridPanel', {
                id          : 'grdAffiliates' + module,
                module      : module,
                store       : affiliateStore,
                height      : 200,
                width       : 250,
                selModel    : sm,
                plugins     : true,
                noPage      : true,
                tbar        : {},
                columns         : [{
                    header      : 'Affiliate Name',
                    dataIndex   : 'affiliateName',
                    flex        : 1,
                    minWidth    : 80,
                    renderer    : function (val, params, record, row_index) {
                        if (record.data.chk) {
                            sm.select(row_index, true);
                        }
                        return val;
                    }
                }],
                listeners   : {
                    afterrender: function () {
                        affiliateStore.load({});
                    }
                }
            })
        }

        function ProjectList(config) {
            var truckProjectStore = standards.callFunction('_createRemoteStore', {
                fields  : ['idTruckProject', 'projectName', 'name', 'remarks', 'idManual', {name: 'isManual', type: 'boolean'}],
                url     : route + 'getTruckProjects'
            });
            let columns = [{
                header      : 'Project ID',
                dataIndex   : 'idTruckProject',
                renderer: function (value, record) {
                    if(record.record.data.isManual) {
                        return record.record.data.idManual;
                    }
                    return value;
                }
            }, {
                header      : 'Project Name',
                flex        : 1,
                minwidth    : 80,
                columnWidth : 100,
                dataIndex   : 'projectName',
            }, {
                header      : 'Customer',
                flex        : 1,
                minwidth    : 80,
                columnWidth : 100,
                dataIndex   : 'name'
            }, {
                header      : 'Remarks',
                flex        : 1,
                minwidth    : 80,
                columnWidth : 100,
                dataIndex   : 'remarks'
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

            function _editRecord(data) {
                module.getForm().retrieveData({
                    url     : route + 'getTruckProject', 
                    method  : 'post',
                    params  : {
                        idTruckProject : data.idTruckProject
                    },
                    success : function (response) {
                        onEdit = 1;
                        idTruckProject = response.idTruckProject;  
                        idManual = response.idManual;
                        isManual = Boolean(parseInt(response.isManual));
                        let id = isManual? idManual : idTruckProject

                        Ext.getCmp('isManual' + module).setValue(isManual);
                        Ext.getCmp('idProject' + module).setValue(id); 
                        Ext.getCmp('inactive' + module).setVisible(true);

                        if( response.idAffiliate != null ) {
                            var affiliates = response.idAffiliate.split(",", response.idAffiliate.length );
                            
                            var gridAffiliate   = Ext.getCmp('grdAffiliates' + module)
                                ,store          = gridAffiliate.getStore()
                                ,grdSM          = gridAffiliate.getSelectionModel();
                    
                            store.proxy.extraParams.affiliates = Ext.encode( affiliates );
                            store.load({
                                callback: function(){
                                    var items = store.data.items;
    
                                    items.map( (col, i) => {
                                        affiliates.map( (idAffiliate) => {
                                            if( idAffiliate == col.data.idAffiliate ){
                                                grdSM.select( i, true );
                                            }
                                        } )
                                    })
                                }
                            });
                        }
                    }
                });
            }

            function _deleteRecord(data) {
                standards.callFunction('_createMessageBox', {
                    msg     : 'DELETE_CONFIRM',
                    action  : 'confirm',
                    fn: function (btn) {
                        if (btn == 'yes') {
                            Ext.Ajax.request({
                                url     : route + 'deleteTruckProject',
                                params  : {
                                    idTruckProject : data.idTruckProject
                                },
                                success : function (response) {
                                    var resp = Ext.decode(response.responseText);
                                    standards.callFunction('_createMessageBox', {
                                        msg : (resp.match == 1) ? 'DELETE_USED' : 'DELETE_SUCCESS',
                                        fn  : function () {
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
                        id      : 'gridItem' + module,
                        module  : module,
                        style   : 'margin-top:15px',
                        plugins : true,
                        store   : truckProjectStore,
                        tbar    : {
                            canPrint    : false,
                            noExcel     : true,
                            route       : route,
                            pageTitle   : pageTitle,
                            content     : [
                                standards.callFunction('_createTextField', {
                                    id          : 'searchTruckType' + module,
                                    allowBlank  : true,
                                    width       : 200,
                                    emptyText   : 'Search project name...',
                                    listeners   : {
                                        change : function (me, newVal, oldVal) {
                                            truckProjectStore.proxy.extraParams = {
                                                filterValue: newVal
                                            };
                                            truckProjectStore.load({
                                                callback: function () {
                                                    truckProjectStore.currentPage = 1;
                                                }
                                            });
                                        }
                                    }
                                }), {
                                    xtype   : 'button',
                                    iconCls : 'glyphicon glyphicon-refresh',
                                    handler : function () {
                                        Ext.getCmp('searchTruckType' + module).reset();
                                        Ext.resetGrid('gridItem' + module);
                                        truckProjectStore.proxy.extraParams = {};
                                        truckProjectStore.load({});
                                    }
                                },
                                ,'->',
                                 {	
                                    xtype		: 'button'
                                    ,iconCls	: 'excel'
                                    ,tooltip	: 'Export to Excel'
                                    ,handler	: function(){
                                        _printExcel();
                                    }
                                },
                                {	
                                    xtype		: 'button'
                                    ,iconCls	: 'pdf-icon'
                                    ,tooltip	: 'Export to PDF'
                                    ,handler	: function(){
                                        _printPDF();
                                    }
                                }
                            ]
                        },
                        columns     : columns,
                        listeners   : {
                            afterrender : function () {
                                truckProjectStore.load({});
                            }
                        }
                    })
                ]
            }
        }

        function _saveForm(form) {

            var selectedRows    = Ext.getCmp('grdAffiliates' + module).getSelectionModel().getSelection(),
                refAffiliates   = [], params;

            if (typeof selectedRows != 'undefined' && selectedRows.length > 0) {
                selectedRows.map((col, i) => {
                    refAffiliates.push(col.data.idAffiliate);
                });

                let isManual = Ext.getCmp('isManual' + module).getValue();

                if(isManual) idManual = Ext.getCmp('idProject' + module).getValue();
                let idProject = Ext.getCmp('idProject' + module).getValue();

                params = {
                    isManual        : isManual,
                    idProject       : isManual? idProject : idProject.replace(/^0+/, ''),
                    projectName     : Ext.getCmp('projectName' + module).getValue(),
                    idCustomer      : Ext.getCmp('idCustomer' + module).getValue(),
                    remarks         : Ext.getCmp('remarks' + module).getValue(),
                    inactive        : Ext.getCmp('inactive' + module).getValue(),
                    onEdit          : onEdit,
                    affiliates      : Ext.encode(refAffiliates)
                    ,idTruckProject : idTruckProject
                    ,idManual       : idManual
                }

                form.submit({
                    url     : route + 'saveTruckProject',
                    params  : params,
                    success : function (me, response) {
                        var resp    = Ext.decode(response.response.responseText),
                            msg     = (resp.match == 0 ? 'SAVE_SUCCESS' : 'REF_EXISTS');

                        standards.callFunction('_createMessageBox', {
                            msg     : msg,
                            action  : (resp.match == 2 ? 'confirm' : ''),
                            fn      : function (btn) {
                                if (btn == 'no' || btn == 'ok') {
                                    if(resp.match == 0) _resetForm(form);
                                }
                            }
                        })
                    }
                })
            } else {
                standards.callFunction('_createMessageBox', {
                    msg : 'Please select atleast one affiliate.'
                });
            }
        }

        function _resetForm( form ) {
            getCode();
            form.reset();
            
            var affiliateGrd = Ext.getCmp('grdAffiliates' + module);
            var selection   = affiliateGrd.getView().getSelectionModel().getSelection();

            affiliateGrd.store.remove(selection); // all selections
            affiliateGrd.store.load({});

            idManual = null;
            isManual = false;
            onEdit   = 0;
            
            Ext.resetGrid('gridItem' + module);
            Ext.getCmp('inactive' + module).setVisible(false);
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
                        getSubmitValue  : true
                    });
                    par.title   = pageTitle;

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

                return _mainPanel(config);
            }
        }
    }
}