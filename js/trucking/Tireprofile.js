function Tireprofile() {
    return function () {
        var route, module, canDelete, canPrint, pageTitle, isGae, canEdit, idModule, onEdit = 0,
            idTireProfile = '', wField = 500;

        function _mainPanel(config) {
            return standards.callFunction('_mainPanel', {
                config: config,
                tbar: {
                    saveFunc: _saveForm,
                    resetFunc: _resetForm,
                    noPDFButton: true,
                    noExcelButton: true,
                    noFormButton: true,
                    noListButton: true
                },
                formItems: [
                    _TireForm(config),
                    _gridList(config)
                ]
            });
        }

        function _TireForm(config) {

            var storeTruck = standards.callFunction('_createRemoteStore', { 
                fields: [
                    'name',
                    'axle',
                    {
                        name: 'code',
                        type: 'number'
                    }
                ],
                url: route + 'getTruckProfile'
            });

            return {
                xtype: 'container',
                items: [{
                    xtype: 'container',
                    layout: 'column',
                    items: [{
                            xtype: 'container',
                            columnWidth: .5,
                            items: [
                                standards.callFunction('_createDateField', {
                                    id: 'dateAcquired' + module,
                                    fieldLabel: 'Date Acquired',
                                    width: wField,
                                }), standards.callFunction('_createTextField', {
                                    id: 'serialNumber' + module,
                                    fieldLabel: 'Serial Number',
                                    width: wField,
                                    isNumber: true,
                                    isDecimal: false,
                                    hasComma: false,
                                    maxLength: 11,
                                    allowBlank: false,
                                }), {
                                    xtype: 'container',
                                    layout: 'column',
                                    style: 'margin:5px 0px 5px 0px',
                                    items: [
                                        standards.callFunction('_createCombo', {
                                            id: 'code' + module,
                                            store: storeTruck,
                                            displayField: 'name',
                                            valueField: 'code',
                                            fieldLabel: 'Assigned Plate #',
                                            emptyText: 'Select Plate # ...',
                                            allowBlank: false,
                                            width: wField - 195,
                                            listeners: {
                                                change: function (me, newVal, oldVal) {
                                                    if(me.valueModels.length) Ext.getCmp('axle' + module).setValue(me.valueModels[0].data.axle);
                                                }
                                            }
                                        }), 
                                        standards.callFunction('_createTextField', {
                                            id: 'axle' + module,
                                            fieldLabel: 'Axle',
                                            labelWidth: 40,
                                            style: 'margin:0px 0px 0px 5px',
                                            width: wField - 353,
                                            readOnly: true
                                        })
                                    ]
                                },
                                standards.callFunction('_createTextField', {
                                    id: 'brandName' + module,
                                    fieldLabel: 'Brand Name',
                                    width: wField,
                                    allowBlank: false
                                }), standards.callFunction('_createTextField', {
                                    id: 'tireSize' + module,
                                    fieldLabel: 'Tire Size',
                                    width: wField,
                                    isNumber: true,
                                    isDecimal: true,
                                    allowBlank: false
                                }), standards.callFunction('_createTextArea', {
                                    id: 'remarks' + module,
                                    fieldLabel: 'Remarks',
                                    width: wField,
                                    allowBlank: false
                                })
                            ]
                        },
                        _tireAdditional()
                    ]
                }]
            }
        }

        function _tireAdditional() {

            var storeRecap = standards.callFunction('_createRemoteStore', {
                autoLoad:false,
                fields: [
                    'recapDate',
                    'recapValue',
                ],
                url: route + 'gridRecap'
            });

            return {
                xtype: 'container',
                columnWidth: .5,
                style: 'margin-top:5px; margin-left: 50px;',
                items: [
                    standards.callFunction('_gridPanel', {
                        id: 'recapGrid' + module,
                        module: module,
                        store: storeRecap,
                        noDefaultRow: true,
                        noPage: true,
                        plugins: true,
                        height: 220,
                        plugins: true,
                        tbar: {
                            canPrint: false,
                            noExcel: true,
                            route: route,
                            pageTitle: pageTitle,
                            content: 'add'
                        },
                        columns: [{
                                header: 'Date',
                                dataIndex: 'recapDate',
                                flex: 1,
                                editor: 'date'
                            },
                            {
                                header: 'Recap Value',
                                dataIndex: 'recapValue',
                                flex: 1,
                                editor: standards.callFunction('_createTextField', {
                                    id: 'recapValue' + module,
                                    submitValue: false

                                })
                            }
                        ]

                    })
                ]
            }
        }

        function _gridList(config) {
            let tireProfileStore = standards.callFunction('_createRemoteStore', {
                fields: [
                    'idTireProfile',
                    'dateAcquired',
                    'serialNumber',
                    'plateNumber',
                    'tireSize',
                    'remarks',
                    'recapValue'
                ],
                url: route + 'getTireProfiles'
            });

            let tbar = {
                canPrint: false,
                noExcel: true,
                route: route,
                pageTitle: pageTitle,
                content: [
                    standards.callFunction('_createCombo', {
                        id: 'filterSerialNumber' + module,
                        emptyText: 'Select Serial Number...',
                        module: module,
                        store  	: tireProfileStore,
                        valueField: 'serialNumber',
                        displayField: 'serialNumber',
                        value: 1,
                        width: 300,
                        listeners: {
                            change: function (me, newVal, oldVal) {
                                tireProfileStore.proxy.extraParams = {
                                    filterValue: newVal
                                };
                                tireProfileStore.load({
                                    callback: function () {
                                        tireProfileStore.currentPage = 1;
                                    }
                                });
                            }
                        }
                    }),
                    {
                        xtype: 'button',
                        iconCls: 'glyphicon glyphicon-refresh',
                        handler: function () {
                            Ext.getCmp('filterSerialNumber' + module).reset();
                            tireProfileStore.proxy.extraParams = {};
                            tireProfileStore.load({});
                        }
                    }
                ]
            };

            let columns = [{
                header: 'Date Acquired',
                dataIndex: 'dateAcquired',
                flex: 1,
                sortable: false
            }, {
                header: 'Serial Number',
                dataIndex: 'serialNumber',
                flex: 1,
                sortable: false
            }, {
                header: 'Assigned Plate Number',
                dataIndex: 'plateNumber',
                flex: 1,
                sortable: false
            }, {
                header: 'Latest Recap Value',
                dataIndex: 'recapValue',
                flex: 1,
                sortable: false
            }, {
                header: 'Remarks',
                dataIndex: 'remarks',
                flex: 1,
                sortable: false
            }, standards.callFunction('_createActionColumn', {
                icon: 'pencil',
                tooltip: 'Edit record',
                width: 30,
                canEdit: canEdit,
                Func: _editRecord
            }), standards.callFunction('_createActionColumn', {
                canDelete: canDelete,
                icon: 'remove',
                tooltip: 'Remove record',
                width: 30,
                Func: _deleteRecord
            })];

            function _editRecord(data) {    
                module.getForm().retrieveData({
                    url: route + 'getTireProfile',
                    method: 'post',
                    params: {
                        idTireProfile: data.id
                    },
                    success: function (response) {
                        onEdit = 1;
                        idTireProfile = response.idTireProfile;
                    }
                });
                Ext.getCmp('recapGrid' + module).store.load({
                    params:{
                        id: data.id
                    }
                });
            }

            function _deleteRecord( data ) {
                standards.callFunction('_createMessageBox', {
                    msg: 'DELETE_CONFIRM',
                    action: 'confirm',
                    fn: function (btn) {
                        if (btn == 'yes') {
                            Ext.Ajax.request({
                                url: route + 'deleteTireProfile',
                                params: {
                                    idTireProfile: data.id
                                },
                                success: function (response) {
                                    var resp = Ext.decode(response.responseText);
                                    standards.callFunction('_createMessageBox', {
                                        msg: (resp.match == 1) ? 'DELETE_USED' : 'DELETE_SUCCESS',
                                        fn: function () {
                                            Ext.resetGrid('tireProfileGrid' + module);
                                        }
                                    });
                                }
                            });
                        }
                    }
                });
            }
                
            return {
                xtype: 'container',
                items: [
                    standards.callFunction('_gridPanel', {
                        id: 'tireProfileGrid' + module,
                        module: module,
                        store: tireProfileStore,
                        noDefaultRow: true,
                        noPage: true,
                        plugins: true,
                        style: 'margin-top: 10px;',
                        tbar: tbar,
                        columns: columns,
                        listeners: {
                            afterrender: function () {
                                tireProfileStore.load({});
                            }
                        }
                    })
                ]
            }
        }

        function _saveForm(form) {
            var gridRecap = new Array();
            [].forEach.call(Ext.getCmp('recapGrid' + module).store.getRange(), function (col) {
                var newData = col.data;
                if (newData.recapValue && newData.recapDate) {
                    gridRecap.push({
                        recapValue: newData.recapValue,
                        recapDate: newData.recapDate
                    });
                }
            });
            var params = {
                dateAcquired: Ext.getCmp('dateAcquired' + module).getValue(),
                serialNumber: Ext.getCmp('serialNumber' + module).getValue(),
                idTruckProfile: Ext.getCmp('code' + module).getValue(),
                brandName: Ext.getCmp('brandName' + module, ).getValue(),
                tireSize: Ext.getCmp('tireSize' + module).getValue(),
                remarks: Ext.getCmp('remarks' + module).getValue(),
                module: module,
                onEdit: onEdit,
                gridRecap: JSON.stringify(gridRecap),
                idTireProfile: idTireProfile
            }

            form.submit({
                url: route + 'saveRecord',
                params: params,
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
            });
        }

        function _resetForm(form) {
            onEdit = 0;
            idTireProfile = '';
            form.reset();

            Ext.resetGrid('tireProfileGrid' + module);
            Ext.getCmp('recapGrid' + module).store.removeAll();
        }

        return {
            initMethod: function (config) {
                route = config.route;
                module = config.module;
                canDelete = config.canDelete;
                canPrint = config.canPrint;
                pageTitle = config.pageTitle;
                isGae = config.isGae;
                canEdit = config.canEdit;
                idModule = config.idmodule;

                return _mainPanel(config);
            }
        }
    }
}