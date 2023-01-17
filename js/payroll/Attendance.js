function Attendance() {
    return function() {
        var route, module, canDelete, canPrint, pageTitle, isGae, canEdit, idModule, onEdit = 0;

        function _mainPanel( config ) {
            return standards2.callFunction('_mainPanelTransactions', {
                config      : config
               ,module      : module
               ,moduleType  : 'form'
               ,hasApproved : false
               ,tbar        : {
                    saveFunc                : _saveForm
                   ,resetFunc               : _resetForm
                //    ,customListExcelHandler  : _printExcel
                //    ,customListPDFHandler    : _printPDF
                //    ,formPDFHandler          : _printPDFForm
                   ,hasFormPDF     		    : true
                   ,hasFormExcel			: false
                   ,filter                  : {
                        searchURL  : route + 'viewHistorySearch'
                       ,emptyText  : 'Search reference here...'
                       ,module     : module
                   }
               },
               formItems   : [
                    _attendanceForm(config)
                    ,_tabPanel()
               ],
               listItems   : _gridHistory()
           });
        }

        function _attendanceForm(config) {
            let typeStore = standards.callFunction('_createLocalStore', {
                data     : ['Trucking', 'Construction', 'Admin']
                ,startAt : 1
            });

            let projectStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      : [ { name : 'id', type : 'number' }, 'name' ]
                ,url        : route + 'getProjectNames'
                ,autoLoad   : false
            });

            let addedByStore = standards.callFunction('_createLocalStore', {
                data     : ['Group', 'Name']
                ,startAt : 1
            });

            return {
                xtype       : 'fieldset'
                ,layout     : 'column'
                ,padding    : 10
                ,items      : [{
                    xtype           : 'container'
                    ,columnWidth    : .5
                    ,items          : [
                        standards.callFunction( '_createDateTime', {
                            module          : 'date' + module
                            ,tstyle         : 'margin-left: 5px;'
                            ,tWidth         : 105
                            ,dFieldLabel    : 'Date'
                            ,tId            : 'ttime' + module
                            ,dId            : 'tdate' + module
                            ,minValue       : Ext.getConstant('AFFILIATEDATESTART')
                            ,dlistener      : {
                                afterrender    : function () {
                                    standards2.callFunction('_checkIf_journal_isClosed', {
                                        idAffiliate	: idAffiliate
                                        , tdate		: this.value
                                        , module	: module
                                    });
                                }
                                ,change : function(){
                                    standards2.callFunction('_checkIf_journal_isClosed', {
                                        idAffiliate	: idAffiliate
                                        , tdate		: this.value
                                        , module	: module
                                    });

                                    if( typeof dSelectHandler == 'undefined' ){
                                        var reference = Ext.getCmp('idReference' + module)
                                        ,referenceNum = Ext.getCmp('referenceNum' + module);

                                        reference.reset();
                                        referenceNum.reset();
                                    } else {
                                        dSelectHandler();
                                    }
                                }
                            }
                        } )
                        ,standards2.callFunction('_transactionReference', {
                            idModule        : idModule
                            ,module         : module
                            ,idAffiliate    : idAffiliate
                            ,width          : 1000
                            ,config			: config
                            ,refCodeWidth   : 240
                            ,style          : 'margin-bottom: 5px;'
                        })
                        ,standards.callFunction( '_createCombo', {
                            id              : 'type' + module
                            ,fieldLabel     : 'Type'
                            ,store          : typeStore
                            ,displayField   : 'name'
                            ,valueField     : 'id'
                            ,value          : 1
                            ,listeners      : {
                                select  : function(me, record){
                                    if(me.getValue() == 3) {
                                        Ext.getCmp('idProject' + module).setDisabled(true);
                                        _requireField('idProject', false);
                                    } else {
                                        Ext.getCmp('idProject' + module).setDisabled(false);
                                        _requireField('idProject', true);
                                    }
                                }
                            }
                        } )
                        ,standards.callFunction( '_createCombo', {
                            id              : 'idProject' + module
                            ,fieldLabel     : 'Project Name'
                            ,store          : projectStore
                            ,allowBlank     : false
                            ,displayField   : 'name'
                            ,valueField     : 'id'
                            ,listeners      : {
                                beforeQuery :  function() {
                                    projectStore.proxy.extraParams.type = Ext.getCmp('type' + module).getValue();
                                }
                            }
                        } )
                    ]
                }, {
                    xtype           : 'container'
                    ,columnWidth    : .5
                    ,items          : [
                        standards.callFunction( '_createDateRange',{
                            id				: 'periodCovered' + module
                            ,sdateID	    : 'sdate' + module
                            ,edateID		: 'edate' + module
                            ,noTime			:  true
                            ,fromFieldLabel	: 'Period Covered'
                        })
                        ,standards.callFunction('_createTextField', {
                            id				: 'position' + module
                            ,fieldLabel     : 'Position'
                            ,width          : 380
                        })
                        ,standards.callFunction( '_createCombo', {
                            id              : 'addedBy' + module
                            ,fieldLabel     : 'Added By'
                            ,store          : addedByStore
                            ,displayField   : 'name'
                            ,valueField     : 'id'
                            ,width          : 380
                        } )
                    ]
                }]
            }
        }

        function _tabPanel() {
            return {
                xtype: 'tabpanel'
                ,items: [{
                    title   : 'Normal Employees'
                    ,layout : { type    : 'card' }
                    ,items  :   [
                        _employeeList()
                    ]
                }
                ,{
                    title   : 'Drivers'
                    ,layout : { type    : 'card' }
                    ,items  :   [
                        _driversList()
                    ]
                }]
            }
        }

        function _employeeList() {
            let store = standards.callFunction( '_createRemoteStore', {
				fields  : [
                    'date'
                    ,'name'
                    ,'timeStart'
                    // ,{ name: 'timeStart',           type: 'time' }
                    ,{ name: 'timeEnd',             type: 'time' }
                    ,{ name: 'numberRegularOfHrs',  type: 'number' }
                    ,{ name: 'overtimeHrs',         type: 'number' }
                    ,{ name: 'totalHrs',            type: 'number' }
                ] 
				,url    : route + 'getEmployeeList'
            } );

            let employeeStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {name : 'idEmployee', type : 'number' }, 'employeeName' ]
                ,url        : route + 'getEmployees'
                ,autoLoad   : true
            });

            let columns = [
                {
                    header      : 'Date'
                    ,xtype      : 'datecolumn'
                    ,dataIndex  : 'date'
                    ,editor     : 'datefield'
                }
                ,{
                    header      : 'Name'
                    ,dataIndex  : 'name'
                    ,flex       : 1
                    ,editor     : standards.callFunction( '_createCombo', {
                        fieldLabel      : ''
                        ,id             : 'employeeName' + module
                        ,store			: employeeStore
                        ,emptyText		: 'Select employee name...'
                        ,displayField   : 'employeeName'
                        ,valueField     : 'employeeName'
                        ,listeners      : {
                            select  : function( me, recordDetails, returnedData ){
                                // var { 0 : store }   = Ext.getCmp('grdProjectTeam' + module).selModel.getSelection()
                                    // ,row            = me.findRecord(me.valueField, me.getValue())
                                    // ,msg            = 'The selected employee already exists in the list. You may edit the existing employee or remove it.';

                                // if( Ext.isUnique(me.valueField, projectTeamStore, me, msg ) ) {
                                    // Ext.setGridData(['idEmployee', 'status', 'idClassification', 'classification'] , store, row)
                                // }
                            }
                        }
                    })
                }
                ,{
                    header      : 'Time Start'
                    ,dataIndex  : 'timeStart'
                    ,editor     : 'timefield'
                    ,renderer   : function(value, metaData, rec, rowIndex, colIndex, store, view) {
                        return Ext.Date.format(value, 'g:i A');
                    }
                }
                ,{
                    header      : 'Time End'
                    ,dataIndex  : 'timeEnd'
                    ,editor     : 'timefield'
                    ,renderer   : function(value, metaData, rec, rowIndex, colIndex, store, view) {
                        return Ext.Date.format(value, 'g:i A');
                    }
                }
                ,{
                    header      : 'Number Regular of Hours'
                    ,dataIndex  : 'numberRegularOfHrs'
                    ,align      : 'right'
                    ,width      : 150
                    ,renderer   : function(value, metaData, rec, rowIndex, colIndex, store, view) {
                        let timeStart   = new Date(rec.get('timeStart')).getTime();
                        let timeEnd     = new Date(rec.get('timeEnd')).getTime();

                        if(isNaN(timeStart) || isNaN(timeEnd)) return 0;

                        let timeDifference = (timeEnd - timeStart) / (3600000);
                        rec.data.numberRegularOfHrs = timeDifference;
                        return timeDifference;
                    }
                }
                ,{
                    header      : 'Overtime Hours'
                    ,dataIndex  : 'overtimeHrs'
                    ,xtype      : 'numbercolumn'
                    ,editor     : 'float'
                }
                ,{
                    header      : 'Total Hours'
                    ,dataIndex  : 'totalHrs'
                    ,align      : 'right'
                    ,renderer   : function(value, metaData, rec, rowIndex, colIndex, store, view) {
                        let total = (rec.get('numberRegularOfHrs') + rec.get('overtimeHrs')).toFixed(2);
                        rec.data.totalHrs = total;
                        return total;
                    }
                }
            ];

            return {
                xtype       : 'container',
                columnWidth : 1,
                items       : [
                    standards.callFunction('_gridPanel', {
                        id              : 'employeeList' + module
                        ,module         : module
                        ,store          : store
                        ,noDefaultRow   : true
                        ,noPage         : true
                        ,plugins        : true
                        ,tbar           : {
                            canPrint    : false
                            ,noExcel    : true
                            ,content    : 'add'
                            // ,deleteRowFunc  : _deleteMaterial
                            ,extraTbar2 : [
                                '-'
                                ,{
                                    xtype   : 'button',
                                    id      : 'import_button_tbar' + module,
                                    text    : 'Import Attendance from Excel',
                                    iconCls : 'glyphicon glyphicon-upload',
                                    handler : function(){
                                        IMPORT();
                                    }
                                }
                            ]
                        }
                        ,columns        : columns
                    })
                ]
            }
        }

        /*FUNCTION IMPORT RECORD FORM EXCEL*/
        function IMPORT(){
            Ext.create('Ext.window.Window', {
                title        : 'Import Items from Excel'
                ,id          : 'import_window' + module
                ,modal       : true
                ,autoHeight  : true
                ,autoWidth   : true
                ,resizable   : false
                ,bodyPadding : 10
                ,layout      : 'fit'
                ,items       : [
                    {
                        xtype        : 'form'
                        ,id          : 'import_form' + module
                        ,baseCls     : 'x-plain'
                        ,frame       : false
                        ,border      : false
                        ,buttonAlign : 'center'
                        ,items       : [
                            {
                                xtype       : 'label'
                                ,html       : 'Excel file must follow a specific format. You may download the file <a href="#" id="download_format_excel">here</a>.'
                                ,listeners  : {
                                    afterrender : function(){
                                        document.getElementById("download_format_excel").onclick = function(){
                                            window.open(route + 'download_format');
                                        }
                                    }
                                }
                            }
                            ,{
                                xtype            : 'fileuploadfield'
                                ,width           : 400
                                ,fieldLabel      : 'File'
                                ,labelWidth      : 20
                                ,name            : 'file_import' + module
                                ,id              : 'file_import' + module
                                ,style           : 'margin-top:3px'
                                ,buttonConfig    : {
                                    text     : ''
                                    ,iconCls : 'glyphicon glyphicon-folder-open'
                                }
                                ,msgTarget       : 'under'
                                ,validator       : function(value){
                                    try {
                                        if(value) {
                                            var file = this.getEl().down('input[type=file]').dom.files[0];
                                            var exp  = /^.*\.(xlsx|XLSX|xls|XLS|csv)$/;
                                            if(exp.test(value)){
                                                if(parseInt(file.size) > (2 * (1024 * 1000))){
                                                    return 'Exceed file upload limit.';
                                                }
                                                else{
                                                    return true;
                                                }
                                            }
                                            else{
                                                return 'Invalid file format.';
                                            }
                                        }
                                        else return false;
                                    }catch(er){	console.log(er);}
                                }
                            }
                        ],
                        buttons:[
                            {	text        : 'Import',
                                formBind    : true,
                                disabled    : true,
                                handler     : function(){
                                    var form    = Ext.getCmp('import_form' + module).getForm();
                                    fileName    = Ext.getCmp('file_import'+ module).getValue();
                                    form.submit({
                                        waitTitle   : "Please wait",
                                        waitMsg     : "Submitting data...",
                                        method      : 'post',
                                        url         : route + 'importAttendance',
                                        params      : { module : module },
                                        success     : function(res, response){
                                            var resp =  Ext.decode( response.response.responseText );
                                            
                                            if( resp.view.match == 2 ) {
                                                standards.callFunction( '_createMessageBox', {
                                                    msg	    : 'Import successful'
                                                    ,action : 'confirm'
                                                } );
                                            } else {
                                                standards.callFunction( '_createMessageBox', {
                                                    msg	    : resp.view.msg
                                                    ,style  : 'height:max-content'
                                                    ,fn	    : function(){
                                                        _resetForm( form );
                                                    }
                                                } );

                                                Ext.getCmp('employeeList' + module).getStore().loadData(Ext.decode(resp.view.viewData), true);
                                            }
                                        },
                                        failure : function(){
                                            standards.callFunction( '_createMessageBox', {
                                                msg		: 'Database connectivity error: Failure during restoration of record.'
                                                ,icon	: 'Error'
                                            });
                                        }
                                    });
                                }
                            }
                        ]
                    }
                ]
            }).show();
        }

        function _driversList() {
            let store = standards.callFunction( '_createRemoteStore', {
				fields  : []
				,url    : route + 'getDriversList'
            } );

            let columns = [
                {
                    header      : 'Date'
                    ,xtype      : 'datecolumn'
                    ,dataIndex  : 'Date'
                }
                ,{
                    header      : 'Number of Loads'
                    ,dataIndex  : 'NumberOfLoads'
                    ,flex       : 1
                }
                ,{
                    header      : 'Unit'
                    ,dataIndex  : 'unit'
                }
                ,{
                    header      : 'Amount'
                    ,dataIndex  : 'amount'
                }
                ,{
                    header      : 'Total'
                    ,dataIndex  : 'total'
                    ,width      : 150
                }
            ];

            return {
                xtype       : 'container',
                columnWidth : 1,
                items       : [
                    standards.callFunction('_gridPanel', {
                        id              : 'driverList' + module
                        ,module         : module
                        ,store          : store
                        ,noDefaultRow   : true
                        ,noPage         : true
                        ,tbar           : {
                            canPrint    : false
                            ,noExcel    : true
                        }
                        ,columns        : columns
                    })
                ]
            }
        }

        function _saveForm() {
            let ttime = Ext.getCmp('ttime' + module).getValue()
            ,tdate = Ext.getCmp('tdate' + module).getValue()
            ,idReference = Ext.getCmp('idReference' + module).getValue()
            ,referenceNum = Ext.getCmp('referenceNum' + module).getValue()
            ,idProject = Ext.getCmp('idProject' + module).getValue()
            ,sdate = Ext.getCmp('sdate' + module).getValue()
            ,edate = Ext.getCmp('edate' + module).getValue()
            ,position = Ext.getCmp('position' + module).getValue()
            ,addedBy = Ext.getCmp('addedBy' + module).getValue();

            let params = {
                ttime           : ttime
                ,tdate          : tdate
                // ,idReference    : idReference
                ,idProject      : idProject
                // ,referenceNum   : referenceNum
                ,sdate          : sdate
                ,edate          : edate
                ,position       : position
                ,addedBy        : addedBy
            };


            console.log(params);
        }

        function _resetForm( form ) {
            form.reset();
        }

        function _gridHistory() {

			var payRollItems = standards.callFunction( '_createRemoteStore', {
				fields		: [ 
					'referenceNumber', 
					'date', 
					'position', 
					'dateFrom',
					'dateTo', 
					'amount', 
				]
				,url		: route + 'viewAll'
				,autoLoad	: true
            } )

			function _deleteRecord( data ) {
				standards.callFunction( '_createMessageBox', {
					msg		: 'DELETE_CONFIRM'
					,action	: 'confirm'
					,fn		: function( btn ){
						if( btn == 'yes' ){
							Ext.Ajax.request({
								url 	: route + 'deleteRecord'
								,params : { idInvoice: data.id }
								,success : function( response ){
									var resp = Ext.decode( response.responseText );
									if( resp.match == 1 ) {
										standards.callFunction( '_createMessageBox', {
											msg : 'DELETE_USED'
										} );
									} 

									payRollItems.load({});
								}
							});
						}
					}
				} );
			}

            function _editRecord(){
            
            }

			return standards.callFunction('_gridPanel', {
                id 					: 'gridHistory' + module
                ,module     		: module
                ,store      		: payRollItems
				,height     		: 265
				,noDefaultRow 		: true
                ,columns: [
					{	header          : 'Date'
                        ,dataIndex      : 'date'
                        ,sortable       : false
                        ,xtype          : 'datecolumn'
                        ,format         : Ext.getConstant('DATE_FORMAT')
                      
                    }
                    ,{	header          : 'Reference'
                        ,dataIndex      : 'referenceNum'
                        ,sortable       : false
                       ,flex            : 1
                    }
                    ,{	header          : 'Type'
                        ,dataIndex      : 'type'
                        ,sortable       : false
                        ,flex           : 1
                    }
					,{  text            : 'Period Covered'
                        ,columns        : [
                            {   header      : 'Date From'
                                ,dataIndex  : 'dateFrom'
                                ,align      : 'right'
                            }
                            ,{   header      : 'Date To'
                                ,dataIndex  : 'dateTo'
                                ,align      : 'right'
                            }
                            
                        ]
                        ,flex            : 1
                    }
                    ,{	header          : 'Total Hours'
                        ,dataIndex      : 'totalHours'
                        ,sortable       : false
                       
                    }
					,standards.callFunction( '_createActionColumn', {
                        canEdit     : canEdit
                        ,icon       : 'pencil'
						,tooltip    : 'Edit'
                        ,width      : 30
                        ,Func       : _editRecord
                    })
                    ,standards.callFunction( '_createActionColumn', {
                        canDelete     : canDelete
                        ,icon       : 'remove'
						,tooltip    : 'Delete'
						,width      : 30
                        ,Func       : _deleteRecord
                    })
				]
				,listeners: {
                    afterrender: function(){
                        payRollItems.proxy.extraParams.idAffiliate = idAffiliate;
                        payRollItems.load({});
                    }
                }
			});
        }

        function _requireField( id, value ){
            var cmp = Ext.getCmp( id + module )

			if( typeof cmp != 'undefined' ){
				cmp.reset();
				cmp.allowBlank = value;
			}
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

