function Truckprofile() {
    return function () {

        var baseurl, route, module, pageTitle, canDelete, canPrint, canEdit, isGae, idModule, onEdit = 0, idTruckProfile = '';
        var defaultImg = {
            'Front'  : 'front_view.jpg'
            ,'Back'  : 'back_view.jpg'
            ,'OR'    : 'or_view.jpg'
            ,'CR'    : 'cr_view.jpg'
            ,'LTFRB' : 'ltfrb_view.jpg'
        };
        
        function _init() {
            _clearTemp();
            // _partsDetails();
        }

        function _mainPanel(config) {
            
            return standards.callFunction('_mainPanel', {
                config       : config
                ,moduleType  : 'form'
                ,tbar        : {
                    saveFunc                : _saveForm
                    ,resetFunc              : _resetForm
                    ,noExcelButton          : false
                    ,listLabel              : 'List'
                    ,canPrint               : canPrint
                    ,route                  : route 
                    ,pageTitle              : pageTitle
                    ,customListPDFHandler   : _printPDF
                    ,customListExcelHandler : _printExcel
                    ,hasFormPDF     	    : true
                    ,filter                 : {
                        searchURL   : route + 'getTruckProfiles'
						,emptyText  : 'Search here...'
						,module     : module
                    }
                }
                ,formItems  : [
                    _TruckProfileForm(config)
                    ,_partDetailsList(config)
                ]
                ,listeners  : {
					afterrender: function(){
						_init();
					}
				}
                ,listItems  : _gridHistory()
            });
        }

        function _TruckProfileForm(config) {
            var truckTypeStore = standards.callFunction('_createRemoteStore', {
                fields  : [{ name: 'idTruckType', type: 'number' }, 'truckType']
                ,url    : route + 'getTruckTypeItems'
            });

            var statusStore = standards.callFunction('_createLocalStore', {
                data     : ['Leased', 'Owned']
                ,startAt : 1
            });

            return {
                xtype   : 'container',
                items   : [{
                    xtype   : 'container',
                    layout  : 'column',
                    items   : [{
                        xtype       : 'container',
                        columnWidth : 0.5,
                        items       : [
                            standards.callFunction('_createTextField', {
                                id           : 'plateNumber' + module,
                                fieldLabel   : 'Plate Number',
                                allowBlank   : false,
                                maxLength    : 20,
                                fieldStyle   : 'text-transform: uppercase'
                            }), standards.callFunction('_createCombo', {
                                id           : 'idTruckType' + module,
                                fieldLabel   : 'Type',
                                store        : truckTypeStore,
                                displayField : 'truckType',
                                valueField   : 'idTruckType',
                                allowBlank   : false,
                                listeners    : {
                                    beforeQuery : function (qe) {
                                        truckTypeStore.load({
                                            callback: function () {
                                                if (this.getCount() < 1) {
                                                    let msg = 'No truck type was created.';
                                                    standards.callFunction('_createMessageBox', {
                                                        msg: msg
                                                    })
                                                }
                                            }
                                        });
                                    }
                                }
                            }), standards.callFunction('_createTextField', {
                                id          : 'axle' + module,
                                fieldLabel  : 'Axle',
                                isNumber    : true,
                                isDecimal   : false
                            }), {
                                xtype       : 'container',
                                layout      : 'column',
                                style       : 'margin-bottom:5px',
                                items       : [{
                                    xtype : 'button',
                                    text  : 'Choose Color',
                                    width : 100,
                                    menu  : {
                                        xtype   : 'colormenu',
                                        value   : '000000',
                                        handler : function (obj, rgb) {
                                            Ext.getCmp('color' + module).setValue('#' + rgb.toString());
                                        }
                                    }
                                }, {
                                    xtype            : 'textfield',
                                    fieldLabel       : '',
                                    id               : 'color' + module,
                                    name             : 'color' + module,
                                    style            : 'margin-left:40px',
                                    allowBlank       : false,
                                    value            : '#FFFFFF',
                                    enforceMaxLength : true,
                                    maxLength        : 7,
                                    width            : 210,
                                    maskRe           : /^[#0-9a-f]/,
                                    listeners        : {
                                        change  : function () {
                                            this.setFieldStyle('background-color: ' + this.value + '; background-image: none;');
                                            this.setValue(this.value.toString().toUpperCase());
                                        }
                                    }
                                }]
                            },
                            standards.callFunction('_createDateField', {
                                id           : 'dateAcquired' + module,
                                fieldLabel   : 'Date Acquired',
                                fieldStyle   : 'text-align:left !important;'
                            })
                            ,standards.callFunction('_createDateField', {
                                id           : 'dateDeployment' + module,
                                fieldLabel   : 'Date Deployment',
                                fieldStyle   : 'text-align:left !important;'
                            })
                            ,standards.callFunction('_createCombo', {
                                id           : 'status' + module,
                                fieldLabel   : 'Status',
                                store        : statusStore,
                                displayField : 'name',
                                valueField   : 'id',
                                value        : 1
                            })
                        ]
                    }, {
                        xtype       : 'container',
                        columnWidth : 0.5,
                        items       : [
                            standards.callFunction('_createTextField', {
                                id          : 'capacity' + module,
                                fieldLabel  : 'Capacity',
                                allowBlank  : true,
                                maxLength   : 50,
                                isNumber    : true,
                                isDecimal   : true
                            })
                            ,standards.callFunction('_createTextField', {
                                id          : 'currentmileage' + module,
                                fieldLabel  : 'Current Mileage',
                                allowBlank  : true,
                                maxLength   : 50,
                                isNumber    : true,
                                isDecimal   : true
                            })
                            ,standards.callFunction('_createTextField', {
                                id          : 'totalWorkingHours' + module,
                                fieldLabel  : 'Total Working Hours',
                                allowBlank  : true,
                                maxLength   : 50,
                                isNumber    : true,
                                isDecimal   : true
                            })
                            ,standards.callFunction('_createTextField', {
                                id          : 'model' + module,
                                fieldLabel  : 'Model'
                            })
                            ,standards.callFunction('_createTextField', {
                                id          : 'make' + module,
                                fieldLabel  : 'Make'
                            })
                            ,standards.callFunction('_createCheckField', {
                                id          : 'inactive' + module,
                                fieldLabel  : 'Inactive'
                            })
                        ]
                    }]
                }, {
                    xtype   : 'container',
                    style   : 'margin-top: 30px; margin-bottom: 30px;',
                    cls     : 'image-upload',
                    items   : [{
                        xtype   : 'container',
                            layout  : {
                                pack    : 'center',
                                type    : 'hbox'
                            },
                        items   : [
                            _imageContainer({
                                name        : 'Front',
                                default_img : defaultImg['Front']
                            }),
                            _imageContainer({
                                name        : 'Back',
                                default_img : defaultImg['Back']
                            }),
                            _imageContainer({
                                name        : 'OR',
                                default_img : defaultImg['OR']
                            }),
                        ]
                    }, {
                        xtype   : 'container',
                        layout  : {
                            pack    : 'center',
                            type    : 'hbox'
                        },
                        items   : [
                            _imageContainer({
                                name        : 'CR',
                                default_img : defaultImg['CR']
                            }),
                            _imageContainer({
                                name        : 'LTFRB',
                                default_img : defaultImg['LTFRB']
                            }),
                            {
                                xtype   : 'container',
                                style   : 'margin: 10px; padding: 0;',
                                items   : [
                                    Ext.create('Ext.form.Label', {
                                        html    : '<img width="300" height="250" src=" ' + Ext.getConstant('BASEURL') + 'images/truck/default/album_view.jpg' + ' " style="border:1px solid black;" /> '
                                    })
                                ]
                                ,listeners  : {
                                    afterrender: function (container) {
                                        container.el.on('click', function () {
                                            _album( config );
                                        });
                                    }
                                },
                            }
                        ]
                    }]
                }]
            }
        }

        function _imageContainer(params) {
            return {
                xtype   : 'container',
                style   : 'margin: 10px;',
                items   : [
                    standards.callFunction('_createImageUpload', {
                        id          : params.name + module,
                        uploadID    : 'upload' + params.name + module,
                        resetID     : 'reset' + params.name + module,
                        boxID       : 'box' + params.name + module,
                        defImage    : Ext.getConstant('BASEURL') + 'images/truck/default/' + params.default_img,
                        boxWidth    : 300,
                        boxHeight   : 250,
                        uploadX     : 245,
                        uploadY     : 217,
                        resetX      : 270,
                        resetY      : 220
                    })
                ]
            }
        }

        function _album( config ) {
            var id = (idTruckProfile != '') ? idTruckProfile : 'temp';
            var albumStore = standards.callFunction('_createRemoteStore', {
                fields  : ['filename'],
                url     : route + 'getAlbum'
            });
            
            albumStore.load({
                params  : { idTruckProfile  : idTruckProfile }
            });

            Ext.create('Ext.window.Window', {
                title   : 'Photo Album',
                modal   : true,
                height  : 400,
                width   : 515,
                layout  : 'fit', 
                items   : [{
                        xtype   : 'form',
                        id      : 'formAlbum' + module,
                        items   : [
                            Ext.create('Ext.view.View', {
                                store        : albumStore,
                                height       : 340,
                                itemSelector : 'div.divInfo',
                                overItemCls  : 'v-hover',
                                autoScroll   : true,
                                tpl          : Ext.create('Ext.XTemplate',
                                    '<tpl for=".">', '<div class="divInfo" style="float:left;width:150px;height:150px; margin-top:5px;">', '<center>', (!Ext.isIE6 ? '<img style="width:120px; height:120px; border: 1px solid #000000; margin-left:5px;" alt="{filename}" src="' + Ext.getConstant('BASEURL')+ 'images/truck/' + id + '/{filename}" onload="if(this.src==\'\')this.src=\'' + Ext.getConstant('BASEURL')+ 'images/truck/' + id + '/{filename}\'" onerror="this.src=\'' + Ext.getConstant('BASEURL')+ 'images/truck/temp/{filename}\'" />' :
                                        '<div style="width:100px;height:100px;filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'' + Ext.getConstant('BASEURL')+ 'images/truck/' + id + '/{filename}\',sizingMethod=\'scale\')"></div>'), '<p style="font-weight:bold; margin-left: 5px;"><font color="#606060"><center>{filename}</center></font></p>', '</center>', '</div>', '</tpl>'
                                )
                            })
                        ],
                        bbar    : [
                            Ext.create('Ext.form.field.File', {
                                name         : 'fileAlbum' + module,
                                id           : 'fileAlbum' + module,
                                buttonOnly   : true,
                                buttonConfig : {
                                    text    : 'Add photo to Album',
                                    iconCls : 'glyphicon glyphicon-plus-sign',
                                },
                                msgTarget    : 'side',
                                validator    : function (value) {
                                    try {
                                        if (value) {
                                            var file = this.getEl().down('input[type=file]').dom.files[0];
                                            var exp = /^.*\.(jpg|JPG|png|PNG)$/;
                                            if (exp.test(value)) {
                                                if (parseInt(file.size) > (2 * (1024 * 1000))) return 'Exceed file upload limit.';
                                                else return true;
                                            } else return 'Invalid image file format.';
                                        } else return true;
                                    } catch (er) {
                                        console.log(er);
                                    }
                                },
                                listeners   : {
                                    change  : function () {

                                        if (this.isValid()) {
                                            var form = Ext.getCmp('formAlbum' + module).getForm();
                                            form.submit({
                                                url     : route + 'uploadTempPIC',
                                                params  : {
                                                    idTruckProfile  : idTruckProfile,
                                                    moduleParams    : module,
                                                },
                                                success : function (a, res) {
                                                    albumStore.load({
                                                        params  : {
                                                            idTruckProfile  : idTruckProfile
                                                        }
                                                    });
                                                },
                                                failure : function () {
                                                    Ext.MessageBox.alert('Error', 'Database connectivity error: Failure during submission of record.');
                                                }
                                            });
                                        } else {
                                            Ext.MessageBox.alert('Error', 'Invalid Format');
                                        }
                                    }
                                }
                            }),
                        ]
                    }
                ]
            }).show();
        }

        function _partDetailsList(config) {

            var store	= standards.callFunction('_createRemoteStore', {
                fields   : ['truckPartName', 'dueDate', 'dateInstalled']
                ,url     : route + 'getTruckPartDetails' 
            });

            let columns = [{
                header       : 'Part Name'
                ,dataIndex   : 'truckPartName'
                ,flex        : 1
                ,sortable    : false
                ,editor		 : 'text'

            }, {
                header       : 'Due Date'
                ,dataIndex   : 'dueDate'
                ,flex        : 1
                ,sortable    : false
                ,editor		 : 'date'
                ,renderer    : function (val, meta, rec, rowIndex, colIndex, store) {
                    if(Ext.isEmpty(val)) {
                        val = new Date();
                        // let models = store.getRange()[rowIndex];
                        // models.set('dueDate', val);
                    }
                    return Ext.Date.format(val, 'm-d-Y');
                }
            }, {
                header       : 'Date Installed'
                ,dataIndex   : 'dateInstalled'
                ,flex        : 1
                ,sortable    : false
                ,editor		 : 'date'
                ,renderer    : function (val, meta, rec, rowIndex, colIndex, store) {
                    if(Ext.isEmpty(val)) {
                        val = new Date();
                        // let models = store.getRange()[rowIndex];
                        // models.set('dateInstalled', new Date());
                    }
                    return Ext.Date.format(val, 'm-d-Y');
                }
            }];

            return {
                xtype    : 'fieldset'
                ,title   : 'Part Details'
                ,padding : 10
                ,items   : [{
                    xtype   : 'container'
                    ,items  : [
                        standards.callFunction('_gridPanel', {
                            id              : 'partDetailsGrid' + module
                            ,module         : module
                            ,store          : store
                            ,noDefaultRow   : true
                            ,noPage         : true
                            ,plugins        : true
                            ,tbar           : {
                                canPrint     : false
                                ,noExcel     : true
                                ,route       : route
                                ,pageTitle   : pageTitle
                                ,content     : 'add'
                            }
                            ,columns        : columns
                        })
                    ]
                }]
            }
        }

        function _gridHistory() {
            var truckProfileStore = standards.callFunction('_createRemoteStore', {
                fields  : [
                    {name : 'idTruckProfile', type : 'number'}
                    ,'plateNumber'
                    ,'type'
                    ,'axle'
                    ,'color'
                    ,'dateAcquired'
                    ,'dateDeployment'
                    ,'capacity'
                    ,'listStatus'
                ],
                url: route + 'getTruckProfiles'
            });

            let columns = [{
                header      : 'ID',
                dataIndex   : 'idTruckProfile',
                flex        : 1,
                minWidth    : 80,
                sortable    : true
            }, {
                header      : 'Plate Number',
                dataIndex   : 'plateNumber',
                flex        : 1,
                minWidth    : 80,
                sortable    : true
            }, {
                header      : 'Type',
                dataIndex   : 'type',
                flex        : 1,
                minWidth    : 80,
                sortable    : true
            }, {
                header      : 'Axle',
                dataIndex   : 'axle',
                width       : 120,
                sortable    : true
            }, {
                header      : 'Color',
                dataIndex   : 'color',
                width       : 120,
                sortable    : true
            }, {
                header      : 'Date Acquired',
                dataIndex   : 'dateAcquired',
                width       : 120,
                sortable    : true
            }, {
                header      : 'Date Deployed',
                dataIndex   : 'dateDeployment',
                width       : 120,
                sortable    : true
            }, {
                header      : 'Capacity',
                dataIndex   : 'capacity',
                width       : 100,
                sortable    : true
            }, {
                header      : 'Status',
                dataIndex   : 'listStatus',
                sortable    : true,
                width       : 55
            }, standards.callFunction('_createActionColumn', {
                canEdit : canEdit,
                icon    : 'pencil',
                tooltip : 'Edit',
                width   : 30,
                Func    : _editRecord
            }), standards.callFunction('_createActionColumn', {
                canEdit : canEdit,
                icon    : 'remove',
                tooltip : 'Delete',
                width   : 30,
                Func    : _deleteRecord
            })];

            function _editRecord(data) {
                module.getForm().retrieveData({
                    url     : route + 'getTruckProfiles',
                    method  : 'post',
                    params  : { 
                        idTruckProfile  : data.idTruckProfile
                    },
                    success : function (response) {
                        onEdit = 1;
                        idTruckProfile = response.idTruckProfile;

                        for (const key in defaultImg) {
                            if(response['truck' + key]) {
                                let photo = Ext.getConstant('BASEURL') + 'images/truck/' + idTruckProfile + '/' + response['truck' + key];
                                Ext.getDom( 'box' + key + module ).src = photo;
                            }
                        } 
                        Ext.resetGrid('partDetailsGrid' + module);
                        Ext.getCmp('partDetailsGrid' + module).getStore().load({
                            params : {
                                id  : idTruckProfile
                            }
                        });
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
                                url : route + 'deleteTruckProfile',
                                params: {
                                    idTruckProfile  : data.idTruckProfile,
                                    idModule        : idModule
                                },
                                success: function (response) {
                                    var resp = Ext.decode(response.responseText);

                                    standards.callFunction('_createMessageBox', {
                                        msg: (resp.match == 1) ? 'DELETE_USED' : 'DELETE_SUCCESS',
                                        fn: function () {
                                            Ext.resetGrid('gridHistory' + module);
                                        }
                                    });
                                }
                            });
                        }
                    }
                });
            }

            return standards.callFunction('_gridPanel', {
                id              : 'gridHistory' + module,
                module          : module,
                store           : truckProfileStore,
                height          : 265,
                columns         : columns,
                noDefaultRow    : true,
                listeners       : {
                    afterrender : function () {
                        truckProfileStore.load({})
                    }
                }
            })
        }

        function _saveForm(form) {
            var partDetailsStore		= Ext.getCmp('partDetailsGrid' + module).getStore();
            var partDetails = new Array();
            for(var x=0; x < partDetailsStore.getCount(); x++){
                var Data = partDetailsStore.getAt(x).data;
                partDetails.push(Ext.encode({	
                    truckPartName   : Data.truckPartName,
                    dueDate         : Ext.isEmpty(Data.dueDate)? new Date() : Data.dueDate,
                    dateInstalled   : Ext.isEmpty(Data.dateInstalled)? new Date() : Data.dateInstalled
                }));
            }

            var params = {
                onEdit              : onEdit,
                idTruckProfile      : idTruckProfile,
                idModule            : idModule,
                plateNumber         : Ext.getCmp('plateNumber' + module).getValue(),
                type                : Ext.getCmp('idTruckType' + module).getValue(),
                axle                : Ext.getCmp('axle' + module).getValue(),
                color               : Ext.getCmp('color' + module, ).getValue(),
                dateAcquired        : Ext.getCmp('dateAcquired' + module).getValue(),
                dateDeployment      : Ext.getCmp('dateDeployment' + module).getValue(),
                status              : Ext.getCmp('status' + module).getValue(),
                capacity            : Ext.getCmp('capacity' + module).getValue(),
                currentmileage      : Ext.getCmp('currentmileage' + module).getValue(),
                totalWorkingHours   : Ext.getCmp('totalWorkingHours' + module).getValue(),
                model               : Ext.getCmp('model' + module).getValue(),
                make                : Ext.getCmp('make' + module).getValue(),
                inactive            : Ext.getCmp('inactive' + module).getValue(),
                fileView            : JSON.stringify(Object.keys(defaultImg)),
                partDetails         : Ext.encode(partDetails), 
            }

            for (const [key, value] of Object.entries(defaultImg)) {
                let filename = Ext.getCmp('upload' + key + module).getValue().replace(/^.*\\/, "");
                if(filename !== '') {
                    params['truck' + key] = Math.floor(100000000 + Math.random() * 900000000) + '.' + filename.split('.').pop();
                }
            }

            form.submit({
                url     : route + 'saveProfile',
                params  : params,
                success : function (action, response) {
                    var resp = Ext.decode(response.response.responseText),
                        msg  = (resp.match == 0) ? 'SAVE_SUCCESS' : 'SAVE_FAILURE';
                    standards.callFunction('_createMessageBox', {
                        msg     : msg,
                        action  : '',
                        fn      : function () {
                            if (resp.match == 0) _resetForm(form);
                        }
                    });
                }
            });
        }

        function _partsDetails(){
            var partStore = Ext.getCmp('partDetailsGrid' + module).getStore();
            /**
                1 - parts
                2 - brakepads   
                3 - others
            **/

            partStore.add({truckPartName:'Grease',                  dueDate:new Date(), dateInstalled:new Date()});
            partStore.add({truckPartName:'Gear Oil',                dueDate:new Date(), dateInstalled:new Date()});
            partStore.add({truckPartName:'Engine Oil',	            dueDate:new Date(), dateInstalled:new Date()});
            partStore.add({truckPartName:'Battery',		            dueDate:new Date(), dateInstalled:new Date()});
            partStore.add({truckPartName:'Aircon',		            dueDate:new Date(), dateInstalled:new Date()});
            partStore.add({truckPartName:'Water Coolant',           dueDate:new Date(), dateInstalled:new Date()});
            
            partStore.add({truckPartName:'Brake Pad 1',		        dueDate:new Date(),	dateInstalled:new Date()});
            partStore.add({truckPartName:'Brake Pad 2',		        dueDate:new Date(),	dateInstalled:new Date()});
            partStore.add({truckPartName:'Brake Pad 3',		        dueDate:new Date(),	dateInstalled:new Date()});
            partStore.add({truckPartName:'Brake Pad 4',		        dueDate:new Date(),	dateInstalled:new Date()});
            partStore.add({truckPartName:'Brake Pad 5',		        dueDate:new Date(),	dateInstalled:new Date()});
            partStore.add({truckPartName:'Brake Pad 6',		        dueDate:new Date(),	dateInstalled:new Date()});
            
            partStore.add({truckPartName:'Oil Filter',		        dueDate:new Date(),	dateInstalled:new Date()});
            partStore.add({truckPartName:'Fuel Filter',		        dueDate:new Date(),	dateInstalled:new Date()});
            partStore.add({truckPartName:'Aircon Filter',	        dueDate:new Date(),	dateInstalled:new Date()});
            partStore.add({truckPartName:'Fuel Separator Filter',	dueDate:new Date(),	dateInstalled:new Date()});
            partStore.add({truckPartName:'Air Cleaner Filter',		dueDate:new Date(),	dateInstalled:new Date()});
        }

        function _resetForm(form) {
            _init();
            onEdit          = 0;
            idTruckProfile  = '';
            form.reset();
            Ext.resetGrid('partDetailsGrid' + module);

            for (const key in defaultImg) {
                Ext.getCmp( 'upload' + key + module ).reset();
                Ext.getDom( 'box' + key + module ).src = Ext.getConstant('BASEURL') + 'images/truck/default/' + defaultImg[key];
            }
        }

        function _clearTemp(){   
            Ext.Ajax.request({
                url     : route + 'clearTemp',
                method  : 'post',
                failure :function(form, action){
                    Ext.MessageBox.alert('Error', 'Database connectivity error: Failure during submission of record.');
                }
            });
        }

        function _printPDF() {
            var _grid = Ext.getCmp('gridHistory' + module);

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
            var _grid = Ext.getCmp('gridHistory' + module);

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
                baseurl     = config.baseurl;
                module      = config.module;
                canDelete   = config.canDelete;
                canPrint    = config.canPrint;
                pageTitle   = config.pageTitle;
                isGae       = config.isGae;
                canEdit     = config.canEdit;
                idModule    = config.idmodule;

                return _mainPanel(config);
            }
        }
    }
}