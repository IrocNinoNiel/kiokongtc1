/**
 * Developer: Christian P. Daohog
 * Module: Truck Maintenance
 * Date: Dec 04, 2021
 * Finished:
 * Description: This module allows authorized users to record a maintenance transaction in the system.
 * DB Tables: 
 * */ 

function Truckmaintenance() {

    return function () {

        var baseurl, route, module, canDelete, canPrint, pageTitle, isGae, canEdit, idModule, onEdit = 0,
        idAffiliate ='', idInvoice = '', idTruckMaintenance = '', filterParts;

        function _init() {
            filterParts = {
                'filtersGrid'   : { 
                     'Oil Filter'            : '{}'
                    ,'Fuel Filter'           : '{}'
                    ,'Fuel Separator Filter' : '{}'
                    ,'Air Cleaner Filter'    : '{}'
                    ,'Aircon Filter'         : '{}'
                }
               ,'partsGrid'     : { 
                    'Brake Pad'      : '{}'
                    ,'Grease'        : '{}'
                    ,'Gear Oil'      : '{}'
                    ,'Engine Oil'    : '{}'
                    ,'Battery'       : '{}'
                    ,'Aircon'        : '{}'
                    ,'Water Coolant' : '{}' 
                }
            };
        }
            
        function _mainPanel(config) {

            _init();

            return standards2.callFunction('_mainPanelTransactions', {
                 config       : config
                ,module       : module
                ,moduleType   : 'form'
                ,hasApproved  : false
                ,tbar         : {
                     saveFunc               : _saveForm
                    ,resetFunc              : _resetForm
                    ,customListExcelHandler	: _printExcel 
					,customListPDFHandler   : _printPDF
                    ,formPDFHandler         : _printPDFForm
					,hasFormPDF     		: true
					,hasFormExcel			: false
                    ,filter                 : {
                        searchURL  : route + 'viewHistorySearch'
                        ,emptyText : 'Search reference here...'
                        ,module    : module
                    }
                },
                formItems   : [
                    _transactionForm( config )
                    ,_truckProfileForm( config )
                    ,_filterPartsField( config )
                    ,_tireField( config )
                    ,_othersField( config )  
                ],
                listItems   : _gridHistory()
            });
        }

        function _transactionForm( config ) {
            return standards2.callFunction( '_transactionHeader', {
                 module			: module
                ,containerWidth	: 1000
                ,idModule		: idModule
                ,idAffiliate	: idAffiliate
                ,config			: config
            });
        }

        function _truckProfileForm( config ) {

            let truckTypeStore = standards.callFunction( '_createRemoteStore', {
                fields    : [ { name: 'idTruckType', type: 'number' } ,'truckType' ]
                ,url      : route + 'getTruckType'
                ,autoLoad : false
            } )
            ,plateNumberStore = standards.callFunction( '_createRemoteStore', {
                fields    : [ { name: 'idTruckProfile', type: 'number' }, { name: 'idTruckType', type: 'number' }, 'plateNumber' ]
                ,url      : route + 'getPlateNumber'
                ,autoLoad : false
            });

            return {  
                xtype       : 'fieldset'
                ,layout     : 'column'
                ,padding    : 10
                ,items      : [
                    {
                        xtype        : 'container'
                        ,columnWidth : .5
                        ,items       : [
                            standards.callFunction( '_createCombo', {
                                id            : 'idTruckType' + module
                                ,fieldLabel   : 'Type'
                                ,store        : truckTypeStore
                                ,displayField : 'truckType'
                                ,valueField   : 'idTruckType'
                                ,listeners    : {
                                    beforeQuery : function (qe) {
                                        truckTypeStore.load({
                                            callback : function () {
                                                if (this.getCount() < 1) {
                                                    let msg = 'No truck type was created.';
                                                    standards.callFunction('_createMessageBox', {
                                                        msg: msg
                                                    })
                                                }
                                            }
                                        });
                                    }
                                    ,select : function (me) {
                                        Ext.getCmp('plateNumber' + module).getStore().proxy.extraParams.filterValue = me.value;
                                        Ext.getCmp('plateNumber' + module).getStore().load({});
                                    }
                                }
                            } )
                            ,standards.callFunction( '_createCombo', {
                                id            : 'plateNumber' + module
                                ,fieldLabel   : 'Plate Number'
                                ,store        : plateNumberStore
                                ,valueField   : 'idTruckProfile'
                                ,displayField : 'plateNumber'
                                ,listeners    : {
                                    beforeQuery :  function() {
                                        plateNumberStore.load({
                                            callback : function () {
                                                if (this.getCount() < 1) {
                                                    let msg = 'No Plate Number was registered for this type.';
                                                    standards.callFunction('_createMessageBox', {
                                                        msg: msg
                                                    })
                                                }
                                            }
                                        });
                                    }
                                    ,select     : function (me) {
                                        if(me.valueModels != null && me.valueModels.length) {
                                            let value           = me.valueModels[0].data.idTruckType
                                                ,cmp            = Ext.getCmp('idTruckType' + module)
                                                ,store          = cmp.getStore()
                                                ,recordNumber   = store.findExact('idTruckType', value, 0)
                                                ,displayValue   = store.getAt(recordNumber).data['truckType']
                                                ,idTruckProfile = me.valueModels[0].data.idTruckType;
 
                                            cmp.setValue(value);
                                            cmp.setRawValue(displayValue);
                                            cmp.selectedIndex = recordNumber;

                                            Ext.Ajax.request({
                                                url			: route + 'getOdometer'
                                                ,method		:'post'
                                                ,params		: {
                                                    filterValue : me.valueModels[0].data.idTruckProfile
                                                }
                                                ,success	: function(response, action){
                                                    var resp = Ext.decode(response.responseText);
                                                    Ext.getCmp('odometer' + module).reset();
                                                    if(resp.view.length > 0) {
                                                        Ext.getCmp('odometer' + module).setValue(resp.view[0].odometer);
                                                    }
                                                }
                                            });
                                        }
                                    }
                                }
                            } )
                            ,{
                                xtype   : 'container'
                                ,layout : 'column'
								,items  : [										
									standards.callFunction( '_createTextField', {
                                        id          : 'odometer' + module
                                        ,fieldLabel : 'Odometer'
                                        ,style      : 'margin-right: 5px;'
                                        ,width      : 290
                                        ,isNumber   : true
                                        ,isDecimal  : true
                                        ,readOnly   : true
                                    } )
									,{
                                        xtype   :'label'
                                        ,text   :'kilometers'
                                    }
								]
							}
                        ]
                    }
                    ,{
                        xtype        : 'container'
                        ,columnWidth : .5
                        ,items       : [
                            standards.callFunction( '_createTextArea', {
                                id          : 'remarks' + module
                                ,fieldLabel : 'Remarks'
                            } )
                        ]
                    }
                ]
            }
        }

        function _filterPartsField( config ) {
            return {
                xtype   : 'container'
                ,layout : 'column'
                ,items  : [
                    {
                        xtype        : 'panel'
                        ,id          : 'filtersPanel' + module
                        ,title       : 'Filters'
                        ,layout      : 'column'
                        ,columnWidth : 1
                        ,collapsible : true
                        ,style       : 'margin-bottom:5px;'
                        ,items       : [
                             _createFilterPartsButton( {filterPartsKey: 'filtersGrid', key: 'Oil Filter'           } )
                            ,_createFilterPartsButton( {filterPartsKey: 'filtersGrid', key: 'Fuel Filter'          } )
                            ,_createFilterPartsButton( {filterPartsKey: 'filtersGrid', key: 'Fuel Separator Filter'} )
                            ,_createFilterPartsButton( {filterPartsKey: 'filtersGrid', key: 'Air Cleaner Filter'   } )
                            ,_createFilterPartsButton( {filterPartsKey: 'filtersGrid', key: 'Aircon Filter'        } )
                        ]
                    }
                    ,{
                        xtype        : 'panel'
                        ,id          : 'partsPanel' + module
                        ,title       : 'Parts'
                        ,layout      : 'column'
                        ,columnWidth : 1
                        ,collapsible : true
                        ,style       : 'margin-bottom:5px;'
                        ,items       : [
                             _createFilterPartsButton( {filterPartsKey: 'partsGrid', key: 'Brake Pad'    } )
                            ,_createFilterPartsButton( {filterPartsKey: 'partsGrid', key: 'Grease'       } )
                            ,_createFilterPartsButton( {filterPartsKey: 'partsGrid', key: 'Gear Oil'     } )
                            ,_createFilterPartsButton( {filterPartsKey: 'partsGrid', key: 'Engine Oil'   } )
                            ,_createFilterPartsButton( {filterPartsKey: 'partsGrid', key: 'Battery'      } )
                            ,_createFilterPartsButton( {filterPartsKey: 'partsGrid', key: 'Aircon'       } )
                            ,_createFilterPartsButton( {filterPartsKey: 'partsGrid', key: 'Water Coolant'} )
                        ]
                    } 
                ]
            }
        }

        function _createFilterPartsButton(params){
            let icon;

            switch(params.filterPartsKey) {
                case 'partsGrid':
                    icon = 'part';
                    break;
                case 'filtersGrid':
                    icon = 'filter';
                    break;
                default:
                    icon = '';
            }

            return {
                xtype        : 'container' 
                ,style       : 'margin:5px; padding-top: 10px; padding-bottom: 10px;'
                ,columnWidth : .25
                ,layout      : {
                     align : 'middle'
                    ,pack  : 'center'
                    ,type  : 'hbox'
                }
                ,items       : [
                    {
						xtype     : 'button'
						,text     : params.key
                        ,id       : 'button_' + params.key + module
                        ,width    : 140
                        ,height   : 30
						,iconCls  : icon
                        ,style    : 'padding:5px'
                        ,disabled : false
                        ,formBind : false
						,handler  : function () {
							var data = Ext.decode(filterParts[params.filterPartsKey][params.key]);  
                            var win = Ext.create('Ext.window.Window', {
                                id           : 'window_' + params.key + module
                                ,layout      : 'fit'
                                ,title       : params.key
                                ,width       : 700
                                ,modal       : true
                                ,resizable   : false
                                ,closable    : false
                                ,items       : [
                                    _gridFilterParts({
                                        id    : params.key + '_grd'
                                        ,text : params.key
                                        ,data : Ext.Object.isEmpty(data)? null : data
                                    })
                                ]
                                ,buttonAlign : 'center'
                                ,buttons     : [
                                    {
                                        xtype    : 'button'
                                        ,text    : 'OK'
                                        ,iconCls : 'glyphicon glyphicon-ok-sign'
                                        ,handler : function () {
                                            var datareplace = Ext.encode(Ext.pluck(Ext.getCmp(params.key + '_grd' + module).store.data.items,'data'));
                                            filterParts[params.filterPartsKey][params.key] = datareplace;
                                            win.destroy(false);
                                        }
                                    }
                                    ,{
                                        xtype    : 'button'
                                        ,text    : 'Close'
                                        ,iconCls : 'glyphicon glyphicon-remove-sign'
                                        ,handler : function () {
                                           win.destroy(true);
                                        }
                                    }
                                ]
                            }).show();
						}
					}
                ]
            }
        }

        function _gridFilterParts(params){
            var store = standards.callFunction( '_createLocalStore', {
                fields    : ['idTruckMaintenanceFilters', 'dateInstalled','dueDate','mileage','remarks', {name: 'damage', type: 'boolean'}]
                ,data     : params.data
                ,autoLoad : false
            });

            var columns = [
                {
                    header      : 'Date Installed'
                    ,dataIndex  : 'dateInstalled'
                    ,xtype      : 'datecolumn'
                    ,editor		: 'date'
                    ,renderer   : function (val, meta, rec, rowIndex, colIndex, store) {
                        val = Ext.isEmpty(val)? new Date() : new Date(val);
                        return Ext.Date.format(val, 'm-d-Y');
                    }
                } 
                ,{
                    header      : 'Mileage'
                    ,dataIndex  : 'mileage'
                    ,editor		: 'float'
                    ,renderer   : function(val, params, record){
                        return '<div style="text-align:right">'+Ext.util.Format.number(parseFloat(val),'0,000.00')+'</div>';
                    }
                }
                ,{
                    header      : 'Due Date'
                    ,dataIndex  : 'dueDate'
                    ,xtype      : 'datecolumn'
                    ,editor     : 'date' 
                    ,renderer    : function (val, meta, rec, rowIndex, colIndex, store) {
                        val = Ext.isEmpty(val)? new Date() : new Date(val);
                        return Ext.Date.format(val, 'm-d-Y');
                    } 
                }
                ,{
                    header      : 'Remarks'
                    ,dataIndex  : 'remarks' 
                    ,editor	    : 'text'
                    ,flex        : 1
                }
                ,{
                    header      : 'Damaged'
                    ,xtype      : 'checkcolumn'
                    ,dataIndex  : 'damage'
                    ,width      : 80
                }
            ];

            return Ext.create('Ext.form.Panel',{
                id : 'gridWindow' + params.id + module
                ,border : false
                ,items:[
                    standards.callFunction( '_gridPanel',{
                        id		      : params.id + module
                        ,module	      : module
                        ,store	      : store
                        ,noDefaultRow : true
                        ,noPage       : true
                        ,plugins      : true
                        ,tbar         : { content : 'add' }
                        ,columns      : columns
                    })
                ]
            });
        }

        function _tireField( config ) {
 
            var store	= standards.callFunction('_createRemoteStore', {
                fields : ['idTruckMaintenanceTires', 'original', 'recap', 'number', 'serialNumber', 'dateInstalled', 'mileage', 'thickness', 'remarks', {name: 'damage', type: 'boolean'}]
                ,url   : route + 'getTire'
            });

            var columns = [
                {
                    header      : 'Original'
                    ,dataIndex  : 'original'
                    ,editor		: 'text'
                }
                ,{
                    header      : 'Recap'
                    ,dataIndex  : 'recap'
                    ,editor		: 'float'
                    ,renderer   : function(val, params, record){
                        return '<div style="text-align:right">'+Ext.util.Format.number(parseFloat(val),'0,000.00')+'</div>';
                    }
                }
                ,{ 
                    header      : 'Number'
                    ,dataIndex  : 'number'
                    ,editor		: 'number'
                }
                ,{
                    header      : 'Serial Number'
                    ,dataIndex  : 'serialNumber'
                    ,editor		: 'text'
                    ,maskRe     : /[0-9]/
                }
                ,{
                    header      : 'Date Installed'
                    ,dataIndex  : 'dateInstalled'
                    ,xtype      : 'datecolumn'
                    ,editor		: 'date'
                    ,renderer   : function (val, meta, rec, rowIndex, colIndex, store) {
                        val = Ext.isEmpty(val)? new Date() : new Date(val);
                        return Ext.Date.format(val, 'm-d-Y');
                    }
                }
                ,{
                    header      : 'Mileage'
                    ,dataIndex  : 'mileage'
                    ,editor		: 'float'
                    ,renderer   : function(val, params, record){
                        return '<div style="text-align:right">'+Ext.util.Format.number(parseFloat(val),'0,000.00')+'</div>';
                    }
                }
                ,{
                    header      : 'Thickness'
                    ,dataIndex  : 'thickness'
                    ,editor		: 'float'
                    ,renderer   : function(val, params, record){
                        return '<div style="text-align:right">'+Ext.util.Format.number(parseFloat(val),'0,000.00')+'</div>';
                    }
                }
                ,{
                    header      : 'Remarks'
                    ,dataIndex  : 'remarks'
                    ,editor		: 'text'
                    ,flex       : 1
                }
                ,{
                    header      : 'Damaged'
                    ,xtype      : 'checkcolumn'
                    ,dataIndex  : 'damage'
                    ,width      : 80
                }
            ];

            return {
                xtype    : 'fieldset'
                ,title   : 'Tires'
                ,padding : 10
                ,items   : [{
                    xtype   : 'container'
                    ,items  : [
                        standards.callFunction( '_gridPanel',{
                            id		        : 'tiresGrid' + module
                            ,module	        : module
                            ,store	        : store
                            ,noDefaultRow   : true
                            ,noPage         : true
                            ,plugins        : true
                            ,tbar           : { content : 'add' }
                            ,columns        : columns
                        })
                    ]
                }]
            }
        }

        function _othersField( config ) {
 
            var store	= standards.callFunction('_createRemoteStore', {
                fields : ['idTruckMaintenanceOthers', 'maintenanceType', 'description', 'dateChangeInstalled', 'mileage', 'remarks', {name: 'damage', type: 'boolean'}]
                ,url   : route + 'getOthers'
            });

            var columns = [
                {
                    header      : 'Type of Maintenance'
                    ,dataIndex  : 'maintenanceType'
                    ,editor		: 'text'
                    ,flex       : 1
                }
                ,{
                    header      : 'Description'
                    ,dataIndex  : 'description'
                    ,editor		: 'text'
                }
                ,{
                    header      : 'Date Change/Date Installed'
                    ,dataIndex  : 'dateChangeInstalled'
                    ,xtype      : 'datecolumn'
                    ,editor		: 'date'
                    ,flex       : 1
                    ,renderer    : function (val, meta, rec, rowIndex, colIndex, store) {
                        val = Ext.isEmpty(val)? new Date() : new Date(val);
                        return Ext.Date.format(val, 'm-d-Y');
                    }
                }
                ,{
                    header      : 'Mileage'
                    ,dataIndex  : 'mileage'
                    ,editor		: 'float'
                    ,renderer   : function(val, params, record){
                        return '<div style="text-align:right">'+Ext.util.Format.number(parseFloat(val),'0,000.00')+'</div>';
                    }
                }
                ,{
                    header      : 'Remarks'
                    ,dataIndex  : 'remarks'
                    ,editor		: 'text'
                    ,flex       : 1
                }
                ,{
                    header      : 'Damaged'
                    ,xtype      : 'checkcolumn'
                    ,dataIndex  : 'damage'
                    ,width      : 80
                }
            ];

            return {
                xtype    : 'fieldset'
                ,title   : 'Others'
                ,padding : 10
                ,items   : [{
                    xtype   : 'container'
                    ,items  : [
                        standards.callFunction( '_gridPanel',{
                            id		        : 'othersGrid' + module
                            ,module	        : module
                            ,store	        : store
                            ,noDefaultRow   : true
                            ,noPage         : true
                            ,plugins        : true
                            ,tbar           : { content : 'add' }
                            ,columns        : columns
                        })
                    ]
                }]
            }
        }

        function _gridHistory() {

            var store = standards.callFunction('_createRemoteStore', {
                fields    : [ {name: 'idTruckMaintenance', type: 'number'}, 'date', 'referenceNum', 'type', 'plateNumber', 'model', 'odometer', 'remarks' ]
                ,url      : route + 'getTruckMaintenance'
                ,autoLoad : false
            });
            
            var columns = [
                {
                    header      : 'Date'
                    ,dataIndex  : 'date'
                    ,sortable   : true
                },
                {
                    header      : 'Reference Number'
                    ,dataIndex  : 'referenceNum'
                    ,sortable   : true
                    ,flex       : 1
                },
                {
                    header      : 'Type'
                    ,dataIndex  : 'type'
                    ,sortable   : true
                    ,flex       : 1
                },
                {
                    header      : 'Plate Number'
                    ,dataIndex  : 'plateNumber'
                    ,sortable   : true
                    ,flex       : 1
                },
                {
                    header      : 'Model'
                    ,dataIndex  : 'model'
                    ,sortable   : true
                    ,flex       : 1
                },
                {
                    header      : 'Odometer'
                    ,dataIndex  : 'odometer'
                    ,sortable   : true
                },
                {
                    header      : 'Remarks'
                    ,dataIndex  : 'remarks'
                    ,sortable   : true
                    ,flex       : 1
                }, 
                standards.callFunction('_createActionColumn', {
                    canEdit     : canEdit
                    ,icon       : 'pencil'
                    ,tooltip    : 'Edit'
                    ,width      : 30
                    ,Func       : _editRecord
                }), 
                standards.callFunction('_createActionColumn', {
                    canEdit     : canEdit
                    ,icon       : 'remove'
                    ,tooltip    : 'Delete'
                    ,width      : 30
                    ,Func       : _deleteRecord
                })
            ];

            function _editRecord(data) {
                module.getForm().retrieveData({
                    url         : route + 'getTruckMaintenance'
                    ,method     : 'post'
                    ,hasFormPDF	: true
                    ,params     : { 
                        idTruckMaintenance  : data.idTruckMaintenance,
                    }
                    ,success    : function (response) {
                        onEdit = 1;
                        idTruckMaintenance  = response.idTruckMaintenance;
                        idInvoice           = response.idInvoice;
                        
                        var idTruckProfile = response.idTruckProfile;

                        // FILTER AND PARTS
                        Ext.Ajax.request({
                            url			: route + 'getFilterParts'
                            ,method		:'post'
                            ,params		: {
                                idTruckMaintenance : idTruckMaintenance
                                ,filterParts       : Ext.encode(filterParts)
                            }
                            ,success	: function(response, action){
                                var resp = Ext.decode(response.responseText);

                                filterParts.partsGrid   = resp.parts;
                                filterParts.filtersGrid = resp.filters;
                                
                                Ext.getCmp('plateNumber' + module).reset();
                                Ext.getCmp('plateNumber' + module).setValue(parseInt(idTruckProfile));
                            }
                        });
                        
                        // TIRES GRID
                        Ext.resetGrid('tiresGrid' + module);
                        Ext.getCmp('tiresGrid' + module).getStore().load({
                            params : {
                                idTruckMaintenance: idTruckMaintenance
                            }
                        });

                        // OTHERS GRID
                        Ext.resetGrid('othersGrid' + module);
                        Ext.getCmp('othersGrid' + module).getStore().load({
                            params : {
                                idTruckMaintenance: idTruckMaintenance
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
                                url     : route + 'deleteTruckMaintenance',
                                params  : {
                                    idTruckMaintenance  : data.idTruckMaintenance,
                                    idModule            : idModule
                                },
                                success : function (response) {
                                    var resp = Ext.decode(response.responseText);

                                    standards.callFunction('_createMessageBox', {
                                        msg : (resp.match == 1) ? 'DELETE_USED' : 'DELETE_SUCCESS',
                                        fn  : function () {
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
                id            : 'gridHistory' + module
                ,module       : module
                ,store        : store
                ,height       : 265
                ,columns      : columns
                ,noDefaultRow : true
            })         
        }

        function _saveForm( form ) {

            var tireStore	= Ext.getCmp('tiresGrid' + module).getStore();
            var tires       = new Array();
            for(var x=0; x < tireStore.getCount(); x++){

                var Data = tireStore.getAt(x).data;

                tires.push(Ext.encode({	
                    idTruckMaintenanceTires : Data.idTruckMaintenanceTires
                    ,moriginal              : Data.original
                    ,mrecap                 : Data.recap    
                    ,mnumber                : Data.number      
                    ,mserialNumber          : Data.serialNumber              
                    ,mdateInstalled         : Ext.isEmpty(Data.dateInstalled)? new Date() : Data.dateInstalled            
                    ,mmileage               : Data.mileage
                    ,mthickness             : Data.thickness  
                    ,mremarks               : Data.remarks
                    ,mdamage                : Data.damage
                }));
            }

            var otherStore	 = Ext.getCmp('othersGrid' + module).getStore();
            var others       = new Array();
            for(var x=0; x < otherStore.getCount(); x++){

                var Data = otherStore.getAt(x).data;

                others.push(Ext.encode({	
                    idTruckMaintenanceOthers  : Data.idTruckMaintenanceOthers
                    ,maintenanceType          : Data.maintenanceType
                    ,description              : Data.description
                    ,dateChangeInstalled      : Ext.isEmpty(Data.dateChangeInstalled)? new Date() : Data.dateChangeInstalled
                    ,mileage                  : Data.mileage
                    ,remarks                  : Data.remarks
                    ,damage                   : Data.damage
                }));
            }

            var params = {
                 onEdit           : onEdit
                ,filterParts      : Ext.encode(filterParts)
                ,tires            : Ext.encode(tires)
                ,others           : Ext.encode(others)
                ,truckMaintenance : Ext.encode({
                     idTruckMaintenance  : idTruckMaintenance
                    ,idTruckProfile      : Ext.getCmp('plateNumber' + module).getValue()
                    ,remarks             : Ext.getCmp('remarks' + module).getValue()
                    ,date                : Ext.Date.format(new Date(), 'Y-m-d')
                })
                ,invoices         : Ext.encode ({
                     idAffiliate       : idAffiliate
                    ,idCostCenter      : Ext.getCmp('idCostCenter' + module).getValue()
                    ,idReference       : Ext.getCmp('idReference' + module).getValue()
                    ,idReferenceSeries : Ext.getCmp('idReferenceSeries' + module).getValue()
                    ,date			   : Ext.getCmp('tdate' + module).getValue()
                    ,time			   : Ext.Date.format(Ext.getCmp( 'ttime' + module).getValue(), 'h:i:s A')
                    ,referenceNum      : Ext.getCmp('referenceNum' + module).getValue()
                    ,cancelTag         : 0
                    ,dateModified      : new Date()
                    ,hasJournal        : 0
                    ,archived          : 0
                    ,cancelledBy       : 0
                    ,idModule          : idModule 
                    ,idInvoice         : idInvoice
                })
            };

            form.submit({
                url      : route + 'saveTruckMaintenance'
                ,params  : params
                ,success : function( action, response ){
                    var resp 	= Ext.decode( response.response.responseText ),
                    msg			= ( resp.match == 0 ) ? 'SAVE_SUCCESS' : 'SAVE_FAILURE';

                    standards.callFunction( '_createMessageBox', {
                        msg     : msg
                        ,action : ''
                        ,fn     : function(){
                            if( resp.match == 0 ) _resetForm( form );
                        }
                    } );
                }
            });
        }

        function _resetForm( form ) {

            _init();
            Ext.getCmp('plateNumber' + module).reset();
            Ext.resetGrid('tiresGrid'  + module);
            Ext.resetGrid('othersGrid' + module);

            form.reset();

            idAffiliate         = '';
            idInvoice           = '';
            idTruckMaintenance  = '';
        }

        function _printPDFForm() {
            var tires       = new Array();
            var tireStore	= Ext.getCmp('tiresGrid' + module).getStore();
            for(var x=0; x < tireStore.getCount(); x++){

                var Data = tireStore.getAt(x).data;

                tires.push(Ext.encode({	
                    idTruckMaintenanceTires : Data.idTruckMaintenanceTires
                    ,original               : Data.original
                    ,recap                  : Data.recap
                    ,number                 : Data.number
                    ,serialNumber           : Data.serialNumber
                    ,dateInstalled          : Ext.isEmpty(Data.dateInstalled)? new Date() : Data.dateInstalled
                    ,mileage                : Data.mileage
                    ,thickness              : Data.thickness
                    ,remarks                : Data.remarks
                    ,damage                 : Data.damage
                }));
            }

            var others       = new Array();
            var otherStore	 = Ext.getCmp('othersGrid' + module).getStore();
            for(var x=0; x < otherStore.getCount(); x++){

                var Data = otherStore.getAt(x).data;

                others.push(Ext.encode({	
                     idTruckMaintenanceOthers : Data.idTruckMaintenanceOthers
                    ,maintenanceType          : Data.maintenanceType 
                    ,description              : Data.description
                    ,dateChangeInstalled      : Ext.isEmpty(Data.dateChangeInstalled)? new Date() : Data.dateChangeInstalled
                    ,mileage                  : Data.mileage
                    ,remarks                  : Data.remarks
                    ,damage                   : Data.damage
                }));
            }

            var par  = standards.callFunction('getFormDetailsAsObject',{ module : module });
            Ext.Ajax.request({
                url     : route + 'printPDFForm'
                ,method : 'post'
                ,params : {
                    moduleID       : idModule
                    ,title         : pageTitle
                    ,limit         : 50
                    ,start         : 0
					,printPDF      : 1
					,form	       : Ext.encode( par )
                    ,filterParts   : Ext.encode(filterParts)
                    ,tires         : Ext.encode(tires)
                    ,others        : Ext.encode(others)
                }
                ,success : function (res) {
                    if (isGae) {
                        window.open(route + 'viewPDF/' + pageTitle, '_blank');
                    } else {
                        window.open(baseurl + 'pdf/trucking/' + pageTitle + ' Form.pdf');
                    }
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
            var _grid = Ext.getCmp('gridHistory' + module);

            standards.callFunction('_listExcel', {
                grid                    : _grid,
                customListExcelHandler  : function () {
                    
                    var par = standards.callFunction('getFormDetailsAsObject', {
                        module          : module,
                        getSubmitValue  : true
                    });
                    par.title   = pageTitle;
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
                idAffiliate = config.idAffiliate

                return _mainPanel(config);
            }
        }
    }
}
