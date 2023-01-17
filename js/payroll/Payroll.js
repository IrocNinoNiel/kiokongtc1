/**
 * Developer: Niño Niel B. Iroc
 * Module: Payroll
 * Date: Jan 10, 2023
 * Finished:
 * Description: 
 * DB Tables:
 * */

function Payroll(){ 

    return function () { 

        var route, module, canDelete, canPrint, pageTitle, isGae, canEdit, idModule, onEdit = 0;

        function _mainPanel(config) {
            return standards2.callFunction('_mainPanelTransactions', {
                 config      : config
                ,module      : module
                ,moduleType  : 'form'
                ,hasApproved : false
                ,tbar        : {
                     saveFunc               : _saveForm
                    ,resetFunc              : _resetForm
                    ,customListExcelHandler	: _printExcel
					,customListPDFHandler   : _printPDF
                    ,formPDFHandler         : _printPDFForm
					,hasFormPDF     		: true
					,hasFormExcel			: false
                    ,filter                 : {
                         searchURL  : route + 'viewHistorySearch'
                        ,emptyText  : 'Search reference here...'
                        ,module     : module
                    }
                },
                formItems   : [
                     _transactionForm( config )
                    ,_payrollForm( config )
                    ,_transactionGrid( config )
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

        function _payrollForm(){

            let positionStore = standards.callFunction( '_createLocalStore' , {
                data        : [ 'Driver', 'Others' ]
                ,startAt    : 0
            });

            var payRollItemsOther = standards.callFunction( '_createRemoteStore', {
				fields		: [ 
                    "empName"
                    ,"rate"
                    ,"noOfDays"
                    ,"grossPay"
                    ,'others'
                    ,'cashAdvances'
                    ,"sss"
                    ,"philhealth"
                    ,"hdmf"
                    ,"otherDeduction"
                    ,"over"
                    ,"netPay"
                ]
				,url		: route + 'getPOItems'
				,autoLoad	: true
            } )

            let columnsOther  = [
                {   header      : 'Employee Name'
                    ,dataIndex  : 'empName'
                    ,width       : 250
                    ,columnWidth : 30
                }
                ,{   header      : 'Rate'
                    ,dataIndex   : 'rate'
                }
                ,{   header      : 'No. of days'
                    ,dataIndex   : 'noOfDays'
                
                }
                ,
                {   header      : 'Gross Pay'
                    ,dataIndex   : 'grossPay'
                }
                ,{   header      : 'Others (Add on)'
                    ,dataIndex   : 'others'
                
                }
                ,{  text        : 'Deduction'
                    ,columns    : [
                        {   header      : 'Cash Advances'
                            ,width      : 80
                            ,dataIndex  : 'cashAdvances'
                            ,align      : 'right'
                        }
                        ,{   header      : 'SSS'
                            ,width      : 80
                            ,dataIndex  : 'sss'
                            ,align      : 'right'
                        }
                        ,{   header      : 'Philhealth'
                            ,width      : 80
                            ,align      : 'right'
                            ,dataIndex  : 'philhealth'
                        }
                        , {   header      : 'HDMF'
                            ,width      : 80
                            ,dataIndex  : 'hdmf'
                            ,align      : 'right'
                        },
                        {   header      : 'Other Deductions'
                            ,width      : 80
                            ,dataIndex  : 'otherDeduction'
                            ,align      : 'right'
                        },
                        {   header      : 'Over'
                            ,width      : 80
                            ,align      : 'right'
                            ,dataIndex  : 'over'
                        }
                    ]
                }
                ,{   
                    header      : 'Net Pay'
                    ,dataIndex  : 'netPay'
                }
            ];

            var payRollItemsDriver = standards.callFunction( '_createRemoteStore', {
				fields		: [ 
                    "empName"
                    ,"grossPay"
                    ,"others"
                    ,"cashAdvances"
                    ,"sss"
                    ,"philhealth"
                    ,"hdmf"
                    ,"otherDeduction"
                    ,"over"
                    ,"netPay"
                ]
				,url		: route + 'getPOItems'
				,autoLoad	: true
            } )

            let columnsDriver  = [
                {   header      : 'Employee Name'
                    ,dataIndex  : 'empName'
                    ,width       : 250
                    ,columnWidth : 30
                }
                ,{   header      : 'Gross Pay'
                    ,dataIndex   : 'grossPay'
                }
                ,{   header      : 'Others (Add on)'
                    ,dataIndex   : 'others'
                
                }
                ,{   header     : 'Unit'
                    ,dataIndex  : 'unitCode'
                }
                ,{  text        : 'Deduction'
                    ,columns    : [
                        {   header      : 'Cash Advances'
                            ,width      : 80
                            ,dataIndex  : 'cashAdvances'
                            ,align      : 'right'
                        }
                        ,{   header      : 'SSS'
                            ,width      : 80
                            ,dataIndex  : 'sss'
                            ,align      : 'right'
                        }
                        ,{   header      : 'Philhealth'
                            ,width      : 80
                            ,align      : 'right'
                            ,dataIndex  : 'philhealth'
                        }
                        , {   header      : 'HDMF'
                            ,width      : 80
                            ,dataIndex  : 'hdmf'
                            ,align      : 'right'
                        },
                        {   header      : 'Other Deductions'
                            ,width      : 80
                            ,dataIndex  : 'otherDeduction'
                            ,align      : 'right'
                        },
                        {   header      : 'Over'
                            ,width      : 80
                            ,align      : 'right'
                            ,dataIndex  : 'over'
                        }
                    ]
                }
                ,{   
                    header      : 'Net Pay'
                    ,dataIndex  : 'netPay'
                }
            ];


            return {
                xtype       : 'fieldset'
                ,layout     : 'column'
                ,padding    : 10
                ,items      : [
                    {
                        xtype           : 'container'
                        ,columnWidth    : .5
                        ,items          : [
                            ,standards.callFunction( '_createDateRange', {
                                module          : module
                                ,width          : 111
                                ,fromWidth      : 235
                                ,sdateID		: 'idSdate' + module
                                ,edateID		: 'idEdate' + module
                                ,fromFieldLabel	: 'Pay Period'
                            } )
                            ,standards.callFunction( '_createCombo', {
                                id              : 'idPosition' + module
                                ,store          : positionStore
                                ,editable       : false
                                ,value          : 0
                                ,fieldLabel     : 'Position'
                                ,listeners      : {
                                    select  : function( me, record ){
                                        var selectedValue = me.getValue();
                                        Ext.getCmp( 'detailsDriver' + module )
                                        if(selectedValue == 0){
                                            Ext.getCmp( 'detailsDriver' + module ).reconfigure(payRollItemsDriver, columnsDriver);
                                        }else {
                                            Ext.getCmp( 'detailsDriver' + module ).reconfigure(payRollItemsOther, columnsOther);
                                        }
                                    }
                                }
                            } )
                        ]
                    },
                    {
                        xtype           : 'container'
                        ,columnWidth    : .5
                        ,items          : [
                            ,standards.callFunction( '_createTextArea', {
                                id			: 'remarks' + module
                                ,fieldLabel	: 'Remarks'
                                ,allowBlank	: true
                            } )
                        ]
                    }
                ]
            }
        }

        function _transactionGrid( config ) {

            paymentStore = standards.callFunction( '_createLocalStore', {
				data        : ['Asset', 'Supplies' ]
				,startAt    : 0
            } )
            ,poNumberStore = standards.callFunction( '_createRemoteStore', {
				fields		: [ 'idInvoice', 'name' ]
				,url		: route + 'getPONumber'
				,autoLoad	: true
            } )
            ,itemStore = standards.callFunction( '_createRemoteStore', {
				fields		: [ 
                     'date'
                    , 'particulars'
                    , 'orNumber'
                    , 'amount'
                ]
				,url		: route + 'getItems'
				,autoLoad	: true
            } );


            return {
                xtype   : 'tabpanel'
                ,items  : [
                    {
                        title   : 'Details'
                        ,layout : { type: 'card' }
                        ,items  : [
                            _detailsDriver()
                           
                        ]
                    }
                    ,{
                        title   : 'Journal Entries'
                        ,layout : { type: 'card' }
                        ,items  :   [
                            standards.callFunction( '_gridJournalEntry',{
                                module	        : module
                                ,hasPrintOption : 1
                                ,config         : config 
                                ,items          : Ext.getCmp('grdItems' + module)
                                ,supplier       : 'pCode'
                            })
                        ]
                    }
                ]
            }
        }

        function _detailsDriver() {
            var payRollItems = standards.callFunction( '_createRemoteStore', {
				fields		: [ 
                    "empName"
                    ,"grossPay"
                    ,"others"
                    ,"cashAdvances"
                    ,"sss"
                    ,"philhealth"
                    ,"hdmf"
                    ,"otherDeduction"
                    ,"over"
                    ,"netPay"
                ]
				,url		: route + 'getPOItems'
				,autoLoad	: true
            } )

            let columns  = [
                {   header      : 'Employee Name'
                    ,dataIndex  : 'empName'
                    ,width       : 250
                    ,columnWidth : 30
                }
                ,{   header      : 'Gross Pay'
                    ,dataIndex   : 'grossPay'
                }
                ,{   header      : 'Others (Add on)'
                    ,dataIndex   : 'others'
                
                }
                ,{   header     : 'Unit'
                    ,dataIndex  : 'unitCode'
                }
                ,{  text        : 'Deduction'
                    ,columns    : [
                        {   header      : 'Cash Advances'
                            ,width      : 80
                            ,dataIndex  : 'cashAdvances'
                            ,align      : 'right'
                        }
                        ,{   header      : 'SSS'
                            ,width      : 80
                            ,dataIndex  : 'sss'
                            ,align      : 'right'
                        }
                        ,{   header      : 'Philhealth'
                            ,width      : 80
                            ,align      : 'right'
                            ,dataIndex  : 'philhealth'
                        }
                        , {   header      : 'HDMF'
                            ,width      : 80
                            ,dataIndex  : 'hdmf'
                            ,align      : 'right'
                        },
                        {   header      : 'Other Deductions'
                            ,width      : 80
                            ,dataIndex  : 'otherDeduction'
                            ,align      : 'right'
                        },
                        {   header      : 'Over'
                            ,width      : 80
                            ,align      : 'right'
                            ,dataIndex  : 'over'
                        }
                    ]
                }
                ,{   
                    header      : 'Net Pay'
                    ,dataIndex  : 'netPay'
                }
            ];

            return {
                xtype       : 'container',
                columnWidth : 1,
                items       : [
                    standards.callFunction('_gridPanel', {
                        id              : 'detailsDriver' + module
                        ,module          : module
                        ,store           : payRollItems
                        ,noDefaultRow    : true
                        ,noPage          : true
                        ,plugins         : true
                        ,sortable        : false
                        ,tbar            : {}
                        ,columns         : columns
                        ,listeners       : {
                           
                        }
                    })
                ]
            }
        }

        function _saveForm( form ){
            let idSdate = Ext.getCmp( 'idSdate' + module ).getValue()
            ,idEdate    = Ext.getCmp( 'idEdate' + module ).getValue()
            ,idPosition = Ext.getCmp( 'idPosition' + module ).getValue()
            ,remarks    = Ext.getCmp( 'remarks' + module ).getValue();

            let params = {
                idSdate     : idSdate
                ,idEdate    : idEdate
                ,idPosition : idPosition
                ,remarks    : remarks
            };

            console.log(params);
        }

        function _resetForm(){

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
                    ,{	header          : 'Position'
                        ,dataIndex      : 'position'
                        ,sortable       : false
                        ,flex           : 1
                    }
					,{  text            : 'Pay Period'
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
                    ,{	header          : 'Amount'
                        ,dataIndex      : 'amount'
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

        function _editRecord(){
            
        }

        function _printPDFForm() {

        }

        function _printExcel() {

        }

        function _printPDF() {
            
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