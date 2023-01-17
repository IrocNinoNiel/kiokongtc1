/**
 * Developer    : Jays
 * Module       : Batch Reconciliation
 * Date         : Mar. 03, 2020
 * Finished     : 
 * Description  : This module allows authorized user to reconcile banks for the cheques to be tagged as cleared.
 * DB Tables    : 
 * */ 
function Bankrecon(){
    
    return function(){
        var baseurl, route, module, canDelete, pageTitle, idModule, isGae,isSaved = 0,deletedItems = [],selectedItem = [], idAffiliate
            ,canSave, fieldWidth = 406, fieldLabelWidth = 185, grdWindowAdjustment = new Array(), totalAdjustment = 0, canCancel;

        function _mainPanel( config ){
            var bankStore       = standards.callFunction( '_createRemoteStore', {
                    fields      : [
                        {   name    : 'id'
                            ,type   : 'number'
                        }
                        ,'name'
                    ]
                    ,url        : route + 'getBank'
                } )
                ,bankAccStore   = standards.callFunction( '_createRemoteStore', {
                    fields      : [
                        {   name    : 'id'
                            ,type   : 'number'
                        }
                        ,'name'
                        ,'idCoa'
                        ,'aname_c30'
                        ,'acod_c15'
                    ]
                    ,url        : route + 'getBankAccount'
                } );
            return standards2.callFunction( '_mainPanelTransactions', {
                config                  : config
                ,moduleType             : 'form'
                ,module                 : module
                ,hasApproved            : false
                ,transactionHandler     :   _transactionHandler
				,hasCancelTransaction   : canCancel
                ,tbar                   : {
                    saveFunc                : _saveForm
                    ,resetFunc              : _resetForm
                    ,formPDFHandler         : _printPDF
                    ,hasFormPDF             : true
					,afterGoToFormHandler   : function(){
						Ext.getCmp( 'bbarPanel' + module ).setVisible( true );
					}
					,afterGoToListHandler   : function(){
						Ext.getCmp( 'bbarPanel' + module ).setVisible( false );
                    }
                    ,filter             : {
                        searchURL       : route + 'getReferences'
                        ,emptyText      : 'Search reference here...'
                    }
                }
                ,formItems              : [
                    {   xtype       : 'hidden'
                        ,id         : 'idBankRecon' + module
                        ,value      : 0
                    }
                    ,{  xtype       : 'hidden'
                        ,id         : 'idCoa' + module
                        ,value      : 0
                    }
                    ,{  xtype       : 'hidden'
                        ,id         : 'norm_c2' + module
                    }
                    ,standards2.callFunction( '_transactionHeader', {
						module			        : module
						,containerWidth	        : 1000
						,idModule		        : idModule
						,idAffiliate	        : idAffiliate
                        ,config			        : config
                        ,conMainWidth           : 406
                        ,width                  : fieldWidth
                        ,labelWidth             : fieldLabelWidth
                        ,refCodeWidth           : 280
                        ,refNumWidth            : 121
                        ,dLabelWidth            : fieldLabelWidth
                        ,dWidth                 : 280
                        ,tWidth                 : 121
                    } )
                    ,{  xtype   : 'fieldset'
                        ,layout : 'column'
                        ,items  : [
                            {   xtype           : 'container'
                                ,columnWidth    : .5
                                ,minWidth       : 406
                                ,items          : [
                                    {   xtype   : 'container'
                                        ,layout : 'column'
                                        ,style  : 'margin-bottom : 5px; margin-top: 10px;'
                                        ,items  : [
                                            standards.callFunction( '_cmbMonth', {
												fieldLabel  : 'Reconciliation For the Month of'
												,id         : 'reconMonth' + module
                                                ,allowBlank : false
                                                ,labelWidth : 185
												,width      : 280
												,style      : 'margin-right : 5px;'
												,module     : module
												,listeners  : {
													select  : function(){
														_reloadGrid();
													}
												}
											} )
											,standards.callFunction( '_createNumberField', {
												id                      : 'reconYear' + module
												,fieldLabel             : 'Year'
												,allowBlank             : false
												,decimalPrecision       : 0
                                                ,width                  : 120
                                                ,labelWidth             : 40
												,hideTrigger            : false
												,minValue               : 1980
												,maxValue               : ( ( new Date() ).getFullYear() + 1 )
												,value                  : ( new Date() ).getFullYear()
												,useThousandSeparator   : false
												,listeners              : {
													change  : function(){
														_reloadGrid();
													}
												}
											} )
                                        ]
                                    }
                                    ,standards.callFunction( '_createCombo', {
                                        id              : 'idBank' + module
                                        ,fieldLabel     : 'Bank'
                                        ,allowBlank     : false
                                        ,store          : bankStore
                                        ,displayField   : 'name'
                                        ,valueField     : 'id'
                                        ,labelWidth     : fieldLabelWidth
                                        ,width          : fieldWidth
                                        ,listeners      : {
                                            select      : function(){
                                                var me  = this;
                                                bankAccStore.proxy.extraParams.idBank   = me.getValue();
                                                Ext.getCmp( 'idBankAccount' + module ).reset();
                                                Ext.getCmp( 'idBankAccount' + module ).store.load( {} );
                                                Ext.getCmp( 'bankAccountCode' + module ).reset();
                                                Ext.getCmp( 'bankAccountDescription' + module ).reset();
                                                _reloadGrid();
                                            }
                                        }
                                    } )
                                    ,standards.callFunction( '_createCombo', {
                                        id              : 'idBankAccount' + module
                                        ,fieldLabel     : 'Bank Account'
                                        ,allowBlank     : false
                                        ,store          : bankAccStore
                                        ,displayField   : 'name'
                                        ,valueField     : 'id'
                                        ,labelWidth     : fieldLabelWidth
                                        ,width          : fieldWidth
                                        ,listeners      : {
                                            select      : function(){
                                                _reloadGrid();
                                            }
                                            ,afterrender    : function(){
                                                bankAccStore.load( {} );
                                            }
                                        }
                                    } )
                                    ,standards.callFunction( '_createTextField', {
                                        id              : 'bankAccountCode' + module
                                        ,fieldLabel     : 'Account code'
                                        ,allowBlank     : true
                                        ,submitValue    : false
                                        ,readOnly       : true
                                        ,labelWidth     : fieldLabelWidth
                                        ,width          : fieldWidth
                                    } )
                                ]
                            }
                            ,{  xtype           : 'container'
                                ,columnWidth    : .5
                                ,minWidth       : 406
                                ,style          : 'margin-top: 10px;'
                                ,items          : [
                                    standards.callFunction( '_createTextField', {
                                        id              : 'bankAccountDescription' + module
                                        ,fieldLabel     : 'Account Description'
                                        ,allowBlank     : true
                                        ,submitValue    : false
                                        ,readOnly       : true
                                        ,labelWidth     : fieldLabelWidth
                                        ,width          : fieldWidth
                                    } )
                                    ,standards.callFunction( '_createTextField', {
                                        id              : 'description' + module
                                        ,fieldLabel     : 'Description'
                                        ,allowBlank     : true
                                        ,labelWidth     : fieldLabelWidth
                                        ,width          : fieldWidth
                                    } )
                                    ,standards.callFunction( '_createTextField', {
                                        id              : 'bankAccountBalance' + module
                                        ,fieldLabel     : 'Account Balance'
                                        ,isNumber       : true
                                        ,isDecimal      : true
                                        ,labelWidth     : fieldLabelWidth
                                        ,width          : fieldWidth
                                        ,readOnly       : true
                                    } )
                                    ,standards.callFunction( '_createTextField', {
                                        id              : 'remark' + module
                                        ,fieldLabel     : 'Remarks'
                                        ,labelWidth     : fieldLabelWidth
                                        ,width          : fieldWidth
                                    } )
                                ]
                            }
                        ]
                    }
                    ,{  xtype               : 'tabpanel'
                        ,deferredRender     : false
                        ,defaults           : {
                            hideMode        : 'offsets'
                        }
                        ,items              : [
                            {   title       : 'Bank Recon Details'
                                ,layout     : 'column'
                                ,padding    : 5
                                ,items      : [
                                    {   title           : 'Bank Statement Adjustment'
                                        ,xtype          : 'fieldset'
                                        ,columnWidth    : .49
                                        ,style          : 'margin-right: 5px;'
                                        ,items          : [
                                            standards.callFunction( '_createTextField', {
                                                fieldLabel      : 'Unadjusted Bank Balance'
                                                ,labelWidth     : fieldLabelWidth
                                                ,width          : fieldWidth - 5
                                                ,id             : 'unAdjustedBankBalance' + module
                                                ,isNumber       : true
                                                ,isDecimal      : true
                                            } )
                                            ,{  xtype   : 'box'
                                                ,html   : '<span style="font-weight:bold;">Add:</span>'
                                                ,style  : 'margin-bottom: 2px;'
                                            }
                                            ,{  xtype   : 'container'
                                                ,layout : 'fit'
                                                ,style  : 'margin-bottom: 10px;'
                                                ,items  : _gridUnadjustedBankBalReceipts()
                                            }
                                            ,{  xtype   : 'box'
                                                ,html   : '<span style="font-weight:bold;">Less:</span>'
                                                ,style  : 'margin-bottom: 2px;'
                                            }
                                            ,{  xtype   : 'container'
                                                ,layout : 'fit'
                                                ,style  : 'margin-bottom: 5px;'
                                                ,items  : _gridUnadjustedBankBalDisbursements()
                                            }
                                            ,{  xtype   : 'container'
                                                ,style  : 'margin: 5px 0;'
                                                ,html   : '<table style="font-weight:bold; width:100%;"><tr><td style = "width : 50%;">Total:</td><td style = "width : 50%; text-align : right;" id="totalBank' + module + '">0.00</td></tr></table>'
                                            }
                                        ]
                                    }
                                    ,{  title           : 'Book Statement Adjustment'
                                        ,xtype          : 'fieldset'
                                        ,columnWidth    : .5
                                        ,items          : [
                                            standards.callFunction( '_createTextField', {
                                                fieldLabel      : 'Unadjusted Book Balance'
                                                ,labelWidth     : fieldLabelWidth
                                                ,width          : fieldWidth
                                                ,id             : 'unadjustedBookBalance' + module
                                                ,isNumber       : true
                                                ,readOnly       : true
                                            } )
                                            ,{  xtype   : 'box'
                                                ,html   : '<span style="font-weight:bold;">Unrecorded Receipts (Add):</span>'
                                                ,style  : 'margin-bottom: 2px;'
                                            }
                                            ,{  xtype   : 'container'
                                                ,layout : 'fit'
                                                ,style  : 'margin-bottom: 10px;'
                                                ,items  : _gridUnrecordedReceipts()
                                            }
                                            ,{  xtype   : 'box'
                                                ,html   : '<span style="font-weight:bold;">Unrecorded Disbursements (Less):</span>'
                                                ,style  : 'margin-bottom: 2px;'
                                            }
                                            ,{  xtype   : 'container'
                                                ,layout : 'fit'
                                                ,style  : 'margin-bottom: 5px;'
                                                ,items  : _gridUnrecordedDisbursements()
                                            }
                                            ,{  xtype   : 'container'
                                                ,style  : 'margin: 5px 0;'
                                                ,html   : '<table style="font-weight:bold; width:100%;"><tr><td style = "width : 50%;">Total:</td><td style = "width : 50%; text-align : right;" id="totalBook' + module + '">0.00</td></tr></table>'
                                            }
                                        ]
                                    }
                                ]
                            }
                            ,{  title   : 'Journal Entries'
                                ,layout : 'fit'
                                ,items  : [
                                    standards.callFunction( '_gridJournalEntry', {
                                        module          : module
                                        ,hasPrintOption : 1
                                        ,idModule       : idModule
                                    } )
                                ]
                            }
                        ]
                    }
                ]
                ,listItems          : _gridHistory()
                ,bbar               : [
                    {   xtype           : 'panel'
                        ,bodyPadding    : 10
                        ,frame          : false
                        ,id             : 'bbarPanel' + module
                        ,border         : false
                        ,height         : 190
                        ,flex           : 1
                        ,items          : [
                            {	xtype : 'fieldset'
								,items : [
                                    {	xtype : 'container'
										,layout : 'column'
										,items : [
											{   xtype       : 'container'	
                                                ,width      : 535
                                                ,layout     : 'column'
												,items      : [
                                                    standards.callFunction( '_createNumberField', {
                                                        id          : 'totalAdjustment' + module
                                                        ,fieldLabel : '<strong>Adjustment</strong>'
                                                        ,style      : 'margin: 5px 10px 5px 0'
                                                        ,width      : 490
                                                        ,labelWidth : 190
                                                        ,minValue   : -999999999999
                                                        ,readOnly   : true
                                                    } )
                                                    ,{  xtype       : 'button'
														,text       : "<span><i class='glyphicon glyphicon-list'></i></span>"
														,style      : "top: 5px"
														,handler    : function(){
                                                            _windowAdjustment();
														}
                                                    }
                                                    ,standards.callFunction( '_createNumberField', {
                                                        id          : 'depositInTransit' + module
                                                        ,fieldLabel : '<strong>Deposit in Transit</strong>'
                                                        ,style      : 'margin: 5px 10px 5px 0'
                                                        ,width      : 490
                                                        ,labelWidth : 190
                                                        ,readOnly   : true
                                                    } )
                                                    ,standards.callFunction( '_createNumberField', {
                                                        id          : 'outstandingCheques' + module
                                                        ,fieldLabel : '<strong>Outstanding Cheques</strong>'
                                                        ,style      : 'margin: 5px 10px 5px 0'
                                                        ,width      : 490
                                                        ,labelWidth : 190
                                                        ,readOnly   : true
                                                    } )
                                                    ,standards.callFunction( '_createNumberField', {
                                                        id          : 'adjustedBankBalance' + module
                                                        ,fieldLabel : '<strong>Adjusted Bank Balance</strong>'
                                                        ,style      : 'margin: 5px 10px 5px 0'
                                                        ,width      : 490
                                                        ,labelWidth : 190
                                                        ,readOnly   : true
                                                    } )
                                                ]
                                            }
                                            ,{  xtype       : 'container'	
												,style      : "margin-top: 95px; margin-left: 12px"
												,items      : [
                                                    ,standards.callFunction( '_createNumberField', {
														id              : 'adjustedbookbal' + module
														,fieldLabel     : '<strong>Adjusted Book Balance</strong>'
														,style          : 'margin : 5px 10px 5px 0;'
                                                        ,width          : 530
                                                        ,labelWidth     : 185
														,readOnly       : true
														,minValue       : -999999999999
												   	} )
													,standards.callFunction( '_createNumberField', {
														id              : 'difference' + module
														,fieldLabel     : 'Difference'
														,submitValue    : false
														,cls            : 'difCls'
														,style          : 'margin : 5px 10px 5px 0; text-color : red;'
                                                        ,width          : 530
                                                        ,labelWidth     : 185
														,readOnly       : true
													} )
                                                ]
                                            }
                                        ]
                                    }
                                ]
                            }
                        ]
                    }
                ]
            } )
        }

        function _gridUnadjustedBankBalReceipts(){
            var store   = standards.callFunction( '_createRemoteStore', {
                fields  : [
                    'idPostdated'
                    ,'reference'
                    ,'remarks'
                    ,'date'
                    ,{  name    : 'amount'
                        ,type   : 'number'
                    }
                    ,{  name    : 'chk'
                        ,type   : 'boolean'
                    }
                ]
                ,url    : route + 'getReceipts'
            } );

            return standards.callFunction( '_gridPanel', {
                id				: 'gridUnadjustedBankBalReceipts' + module
                ,module			: module
                ,store			: store
                ,height         : 450
                ,noDefaultRow	: true
                ,tbar			: {
                    content		: ''
                }
                ,plugins		: true
                ,noPage         : true
                ,columns		: [
                    {   header      : 'Ref #'
                        ,dataIndex  : 'reference'
                        ,width      : 100
                    }
                    ,{  header      : 'Description'
                        ,dataIndex  : 'description'
                        ,minWidth   : 100
                        ,flex       : 1
                    }
                    ,{  header      : 'Date'
                        ,dataIndex  : 'date'
                        ,xtype      : 'datecolumn'
                        ,format     : 'm/d/Y'
                    }
                    ,{  header      : 'Amount'
                        ,dataIndex  : 'amount'
                        ,xtype      : 'numbercolumn'
                        ,format     : '0,000.00'
                    }
                    ,{	header : ''
						,dataIndex : 'chk'
						,width : 26
						,xtype : 'checkcolumn'
						,listeners : {
							checkchange : function(){
								_processComputation();
							}
						}
					}
                ]
            } );
        }

        function _gridUnrecordedReceipts(){
            var store   = standards.callFunction( '_createRemoteStore', {
                fields      : [
                    'description'
                    ,{  name    : 'amount'
                        ,type   : 'number'
                    }
                ]
                ,url        : route + 'getAdjusted'
            } );
            return standards.callFunction( '_gridPanel', {
                id				: 'gridUnrecordedReceipts' + module
                ,module			: module
                ,store			: store
                ,height         : 450
                ,tbar			: {
                    content		: 'add'
                }
                ,plugins		: true
                ,noPage         : true
                ,columns		: [
                    {   header      : 'Description'
                        ,dataIndex  : 'description'
                        ,flex       : 1
                        ,minWidth   : 150
                        ,editor     : 'text'
                        ,maxLength  : 250
                    }
                    ,{  header      : 'Amount'
                        ,dataIndex  : 'amount'
                        ,xtype      : 'numbercolumn'
                        ,format     : '0,000.00'
                        ,hasTotal   : true
                        ,editor     : 'number'
                    }
                ]
                ,listeners      : {
                    edit        : function(){
                        _processComputation();
                    }
                }
            } )
        }

        function _gridUnadjustedBankBalDisbursements(){
            var store   = standards.callFunction( '_createRemoteStore', {
                fields  : [
                    'idPosting'
                    ,'reference'
                    ,'description'
                    ,'date'
                    ,{  name    : 'amount'
                        ,type   : 'number'
                    }
                    ,{  name    : 'chk'
                        ,type   : 'boolean'
                    }
                ]
                ,url    : route + 'getDisbursements'
            } );

            return standards.callFunction( '_gridPanel', {
                id				: 'gridUnadjustedBankBalDisbursements' + module
                ,module			: module
                ,store			: store
                ,height         : 450
                ,noDefaultRow	: true
                ,tbar			: {
                    content		: ''
                }
                ,plugins		: true
                ,noPage         : true
                ,columns		: [
                    {   header      : 'Ref #'
                        ,dataIndex  : 'reference'
                        ,width      : 100
                    }
                    ,{  header      : 'Description'
                        ,dataIndex  : 'description'
                        ,minWidth   : 100
                        ,flex       : 1
                    }
                    ,{  header      : 'Date'
                        ,dataIndex  : 'date'
                        ,xtype      : 'datecolumn'
                        ,format     : 'm/d/Y'
                    }
                    ,{  header      : 'Amount'
                        ,dataIndex  : 'amount'
                        ,xtype      : 'numbercolumn'
                        ,format     : '0,000.00'
                    }
                    ,{	header : ''
						,dataIndex : 'chk'
						,width : 26
						,xtype : 'checkcolumn'
						,listeners : {
							checkchange : function(){
								_processComputation();
							}
						}
					}
                ]
            } );
        }

        function _gridUnrecordedDisbursements(){
            var store   = standards.callFunction( '_createRemoteStore', {
                fields      : [
                    'description'
                    ,{  name    : 'amount'
                        ,type   : 'number'
                    }
                ]
                ,url        : route + 'getAdjusted2'
            } );
            return standards.callFunction( '_gridPanel', {
                id				: 'gridUnrecordedDisbursements' + module
                ,module			: module
                ,store			: store
                ,height         : 450
                ,tbar			: {
                    content		: 'add'
                }
                ,plugins		: true
                ,noPage         : true
                ,columns		: [
                    {   header      : 'Description'
                        ,dataIndex  : 'description'
                        ,flex       : 1
                        ,minWidth   : 150
                        ,editor     : 'text'
                        ,maxLength  : 250
                    }
                    ,{  header      : 'Amount'
                        ,dataIndex  : 'amount'
                        ,xtype      : 'numbercolumn'
                        ,format     : '0,000.00'
                        ,hasTotal   : true
                        ,editor     : 'number'
                    }
                ]
                ,listeners      : {
                    edit        : function(){
                        _processComputation();
                    }
                }
            } )
        }

        function _gridHistory(){
            var store   = standards.callFunction( '_createRemoteStore', {
                fields      : [
                    'reference'
                    ,'reconDate'
                    ,'reconMonth'
                    ,'reconMonthDis'
                    ,'reconYear'
                    ,'idBankRecon'
                    ,'description'
                    ,'bankName'
                    ,'bankAccount'
                    ,'idBankAccount'
                    ,'idReference'
                    ,'referenceNum'
                    ,{  name    : 'unAdjustedBankBalance'
                        ,type   : 'number'
                    }
                ]
                ,url        : route + 'getHistory'
            } );
            return standards.callFunction( '_gridPanel', {
                id              : 'gridHistory' + module
                ,store          : store
                ,noDefaultRow   : true
                ,module         : module
                ,columns        : [
                    {   header          : 'Reference Number'
                        ,dataIndex      : 'reference'
                        ,width          : 100
                        ,columnWidth    : 10
                    }
                    ,{  header          : 'Date'
                        ,dataIndex      : 'reconDate'
                        ,width          : 100
                        ,xtype          : 'datecolumn'
                        ,format         : 'm/d/Y h:i A'
                        ,columnWidth    : 10
                    }
                    ,{  header          : 'Month'
                        ,dataIndex      : 'reconMonthDis'
                        ,width          : 100
                        ,columnWidth    : 10
                    }
                    ,{  header          : 'Year'
                        ,dataIndex      : 'reconYear'
                        ,width          : 100
                        ,columnWidth    : 10
                    }
                    ,{  header          : 'Description'
                        ,dataIndex      : 'description'
                        ,flex           : 1
                        ,minWidth       : 150
                        ,columnWidth    : 20
                    }
                    ,{  header          : 'Bank'
                        ,dataIndex      : 'bankName'
                        ,width          : 100
                        ,columnWidth    : 10
                    }
                    ,{  header          : 'Bank Account'
                        ,dataIndex      : 'bankAccount'
                        ,width          : 100
                        ,columnWidth    : 10
                    }
                    ,{  header          : 'Bank Balance of the Month'
                        ,dataIndex      : 'unAdjustedBankBalance'
                        ,width          : 150
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,columnWidth    : 10
                    }
                    ,standards.callFunction( '_createActionColumn', {
						canEdit         : canEdit
						,icon           : 'pencil'
						,tooltip        : 'Edit record'
						,Func           : _editRecord
                    } )               
                    ,standards.callFunction( '_createActionColumn', {
						canDelete       : canDelete
						,icon           : 'remove'
						,tooltip        : 'Delete record'
						,Func           : _deleteRecord
					} )
                ]
            } );
        }

        function _editRecord( data ){
            module.getForm().retrieveData( {
                url         : route + 'retrieveData'
                ,params     : data
                ,hasFormPDF	: true
                ,success    : function( view, match ){
                    /* match value
                     * 0 = OK
                     * 1 = record not found
                     * 2 = record used
                    */
                    var grdStore = Ext.getCmp( 'gridJournalEntry' + module ).getStore();
                    grdStore.load( {
                        params   : {
                            idBankRecon    : view.idBankRecon
                        }
                    } );
                    var idBank          = Ext.getCmp( 'idBank' + module )
                        ,idBankAccount  = Ext.getCmp( 'idBankAccount' + module );
                    idBank.store.load( {
                        callback    : function(){
                            idBank.setValue( parseInt( view.idBank, 10 ) );
                            idBankAccount.store.proxy.extraParams.idBank    = view.idBank;
                            idBankAccount.store.load( {
                                callback    : function(){
                                    idBankAccount.setValue( parseInt( view.idBank, 10 ) );
                                    _reloadGrid( true );
                                }
                            } );
                        }
                    } );

                    var gridUnrecordedReceipts          = Ext.getCmp( 'gridUnrecordedReceipts' + module )
                        ,gridUnrecordedDisbursements    = Ext.getCmp( 'gridUnrecordedDisbursements' + module );
                    
                    gridUnrecordedReceipts.store.load( {
                        params  : {
                            idBankRecon     : view.idBankRecon
                            ,adjustedTag    : 1
                        }
                    } )

                    gridUnrecordedDisbursements.store.load( {
                        params  : {
                            idBankRecon     : view.idBankRecon
                            ,adjustedTag    : 2
                        }
                    } )

                    grdWindowAdjustment     = view.bankreconadjustment;
                    Ext.getCmp( 'bbarPanel' + module ).setVisible( true );
                }
            } );
        }

        function _deleteRecord( data ){
            data['idModule']    = idModule;
            data.confirmDelete( {
                url         : route + 'deleteRecord'
                ,params     : data
                ,success    : function( action, response ){
					var res = Ext.decode( action.responseText );
                    var match = parseInt( res.match, 10 );
                    switch( match ){
                        case 1: /* record not found */
                            standards.callFunction( '_createMessageBox', {
                                msg		: 'EDIT_UNABLE'
                            } );
                            break;
                        case 2: /* record already has closing entry and cannot be deleted */
                            standards.callFunction( '_createMessageBox', {
                                msg     : 'A closing entry has already been made for the record period and therefore cannot be deleted.'
                            } )
                            break;
                        case 3: /* record has succeeding record and cannot be deleted  */
                            standards.callFunction( '_createMessageBox', {
                                msg     : 'Record already have succeeding record and therefore cannot be deleted.'
                            } )
                            break;
                        default: /* record has been successfully deleted */
                            standards.callFunction( '_createMessageBox', {
                                msg		: 'DELETE_SUCCESS'
                            } );
                            if( parseInt( data.idBankRecon, 10 ) == parseInt( Ext.getCmp( 'idBankRecon' + module ).getValue(), 10 ) )
                                _resetForm( Ext.getCmp( 'mainFormPanel' + module ).getForm( ) );

                            var grd = Ext.getCmp( 'gridHistory' + module );
                            grd.store.load( {
                                callback    : function(){
                                    if( grd.getStore().getCount() <= 0 ){
                                        grd.getStore().currentPage--;
                                        grd.store.load();
                                    }
                                }
                            } )
                            break;
                    }
                }
            } )
        }

        function _windowAdjustment(){
            var store   = standards.callFunction( '_createLocalStore', {
                fields  : [
                    'date'
                    ,'description'
                    ,{  name    : 'addAmount'
                        ,type   : 'number'
                    }
                    ,{  name    : 'lessAmount'
                        ,type   : 'number'
                    }
                ]
                ,data   : grdWindowAdjustment
            } );
            Ext.create( 'Ext.Window', {
				id              : 'winAdjustments' + module
				,title          : 'Adjustments'
				,width          : 640
				,modal          : true
				,closable       : false
				,resizable      : false
				,frame          : true
				,defaults       : {
					anchor  : '100%'
				}
				,items: [
					{   xtype           : 'form'
						,id             : 'formAdjustment' + module
						,module         : module
						,border         : false
						,buttonAlign    : 'right'
						,items          : [
                            standards.callFunction( '_gridPanel', {
                                id				: 'gridWinAdjustments' + module
                                ,module			: module
                                ,store			: store
                                ,height         : 450
                                ,tbar			: {
                                    content		: 'add'
                                }
                                ,plugins		: true
                                ,noPage         : true
                                ,noDefaultRow   : grdWindowAdjustment.length > 0
                                ,columns		: [
                                    {   header      : 'Date'
                                        ,dataIndex  : 'date'
                                        ,xtype      : 'datecolumn'
                                        ,format     : 'm/d/Y'
                                        ,editor     : 'date'
                                    }
                                    ,{   header      : 'Description'
                                        ,dataIndex  : 'description'
                                        ,flex       : 1
                                        ,minWidth   : 150
                                        ,editor     : 'text'
                                    }
                                    ,{  header      : 'Add'
                                        ,dataIndex  : 'addAmount'
                                        ,xtype      : 'numbercolumn'
                                        ,format     : '0,000.00'
                                        ,hasTotal   : true
                                        ,editor     : 'float'
                                    }
                                    ,{  header      : 'Less'
                                        ,dataIndex  : 'lessAmount'
                                        ,xtype      : 'numbercolumn'
                                        ,format     : '0,000.00'
                                        ,hasTotal   : true
                                        ,editor     : 'float'
                                    }
                                ]
                                ,listeners      : {
                                    edit    : function( grid, rowData ){
                                        if( rowData.field == 'addAmount' ){
                                            if( rowData.record.get( 'addAmount' ) > 0 ) rowData.record.set( 'lessAmount', 0 );
                                        }
                                        if( rowData.field == 'lessAmount' ){
                                            if( rowData.record.get( 'lessAmount' ) > 0 ) rowData.record.set( 'addAmount', 0 );
                                        }
                                        _computeWindowTotal();
                                    }
                                }
                            } )
                            ,{
								xtype   : 'label'
								,text   : 'Total:'
								,style  : 'float: left; margin-left: 20px; font-weight: bold'
							}
							,{
								xtype   : 'label'
								,id     : 'winAdjustmentTotal'+module
								,text   : '0.00'
								,style  : "float: right; margin-right: 20px; font-weight: bold"
							}
                        ]
                        ,buttons    : [
                            {   text        : 'OK'
                                ,handler    : function(){
                                    grdWindowAdjustment = new Array();
                                    Ext.getCmp( 'gridWinAdjustments' + module ).store.each( function( item ){
                                        if( item.data.addAmount > 0 || item.data.lessAmount ) grdWindowAdjustment.push( item.data );
                                    } );
                                    Ext.getCmp( 'totalAdjustment' + module ).setValue( totalAdjustment );
                                    Ext.getCmp( 'winAdjustments' + module ).destroy( true );
                                }
                            }
                            ,{  text        : 'Close'
                                ,handler    : function(){
                                    Ext.getCmp( 'winAdjustments' + module ).destroy( true );
                                }
                            }
                        ]
                    }
                ]
                ,listeners      : {
                    destroy     : function(){
                        _processComputation();
                    }
                }
            } ).show();
        }

        function _computeWindowTotal(){
            totalAdjustment = 0;
            Ext.getCmp( 'gridWinAdjustments' + module ).store.each( function( item ){
                if( item.data.addAmount > 0 ) totalAdjustment += item.data.addAmount;
                else if( item.data.lessAmount > 0 ) totalAdjustment -= item.data.lessAmount;
            } );
            Ext.getCmp( 'winAdjustmentTotal'+module ).setText( Ext.util.Format.number( totalAdjustment, '0,000.00' ) );
        }

        function _saveForm( form ){
            var jeRecords           = standards.callFunction( '_gridJournalEntryValidation', {
                    module      : module
                } )
                ,postdated          = new Array()
                ,grdReceipts        = Ext.getCmp( 'gridUnadjustedBankBalReceipts' + module )
                ,grdDisbursements   = Ext.getCmp( 'gridUnadjustedBankBalDisbursements' + module )
                ,adjusted           = new Array()
                ,grdAdd             = Ext.getCmp( 'gridUnrecordedReceipts' + module )
                ,grdLess            = Ext.getCmp( 'gridUnrecordedDisbursements' + module )
                ,cntErrAdjusted     = 0;
            grdReceipts.getStore().getRange().forEach( function( data, key ){
                if( data.get( 'chk' ) ){
                    postdated.push( {
                        idPostdated     : data.get( 'idPostdated' )
                        ,status         : 2
                        ,statusDate     : Ext.getCmp( 'tdate' + module ).getValue()
                    } )
                }
            } );
            grdDisbursements.getStore().getRange().forEach( function( data, key ){
                if( data.get( 'chk' ) ){
                    postdated.push( {
                        idPostdated     : data.get( 'idPostdated' )
                        ,status         : 2
                        ,statusDate     : Ext.getCmp( 'tdate' + module ).getValue()
                    } )
                }
            } )
            grdAdd.getStore().getRange().forEach( function( data, key ){
                if( data.get( 'description' ) && parseFloat( data.get( 'amount' ) ) > 0 ){
                    adjusted.push( {
                        amount          : data.get( 'amount' )
                        ,description    : data.get( 'description' )
                        ,adjustedTag    : 1  
                    } );
                }
                else if( !data.get( 'description' ) && parseFloat( data.get( 'amount' ) ) > 0 ){
                    cntErrAdjusted++;
                }
            } );
            grdLess.getStore().getRange().forEach( function( data, key ){
                if( data.get( 'description' ) && parseFloat( data.get( 'amount' ) ) > 0 ){
                    adjusted.push( {
                        amount          : data.get( 'amount' )
                        ,description    : data.get( 'description' )
                        ,adjustedTag    : 2
                    } );
                }
                else if( !data.get( 'description' ) && parseFloat( data.get( 'amount' ) ) > 0  ){
                    cntErrAdjusted++;
                }
            } );

            if( cntErrAdjusted > 0 ){
                standards.callFunction( '_createMessageBox', {
                    msg     : 'Description for unrecorded receipts(Add)/disbursements(Less) is required.'
                } );
                return false;
            }
            
            if( !jeRecords ){
                return false;
            }

            if( parseFloat( Ext.getCmp( 'difference' + module ).getValue() ) != 0 ){
                standards.callFunction( '_createMessageBox', {
                    msg     : 'Difference between adjusted bank balance and adjusted book balance must be equal to zero.'
                } );
                return false;
            }

            form.submit( {
                url         : route + 'saveRecord'
                ,params     : {
                    bankreconadjustment     : Ext.encode( grdWindowAdjustment )
                    ,adjusted               : Ext.encode( adjusted )
                    ,postdated              : Ext.encode( postdated )
                    ,posting                : Ext.encode( jeRecords )
                    ,idModule               : idModule
                    ,status                 : 2
                }
                ,success    : function( action, response ){
                    var resp    = Ext.decode( response.response.responseText )
                        ,match  = parseInt( resp.match, 10 );
                    switch( match ){
                        case 1: /* reference number already exists */
                            standards.callFunction( '_createMessageBox', {
                                msg     : 'Reference number already exists.'
                                ,fn     : function(){
                                    standards2.callFunction( '_getReferenceNum', {
                                        idReference     : Ext.getCmp( 'idReference' + module ).getValue()
                                        ,idModule       : idModule
                                        ,idAffiliate    : Ext.getConstant( 'AFFILIATEID' )
                                    } );
                                }
                            } );
                            break;
                        case 2: /* record to edit cannot be found */
                            standards.callFunction( '_createMessageBox', {
                                msg : 'EDIT_UNABLE'
                                ,fn : function(){
                                    _rsetForm( form );
                                }
                            } )
                            break;
                        case 3: /* record already edited by other user */
                            standards.callFunction( '_createMessageBox', {
                                msg		: 'SAVE_MODIFIED'
                                ,action	: 'confirm'
                                ,fn		: function( btn ){
                                    if( btn == 'yes' ){
                                        form.modify = true;
                                        _saveForm( form );
                                    }
                                }
                            } );
                            break;
                        case 4: /* has future bank recon */
                            standards.callFunction( '_createMessageBox', {
                                msg : 'You cannot add a bank recon for ' + Ext.getCmp( 'reconMonth' + module ).getRawValue() + ' - ' + Ext.getCmp( 'reconYear' + module ).getValue() + '. There is already a bank recon record for the month of ' + resp.monDis + '. You cannot create a bank recon record once there is a bank recon record for the succeeeding month.'
                                ,fn : function(){
                                    Ext.getCmp( 'bankAccountID' + module ).reset();
                                }
                            } );
                            break;
                        case 5: /* already has an existing record */
                            standards.callFunction( '_createMessageBox', {
                                msg : 'This account has already been reconciled. You can only reconcile this account once.'
                                ,fn : function(){
                                    Ext.getCmp( 'idBankAccount' + module ).reset();
                                    Ext.getCmp( 'idBankAccount' + module ).fireEvent( 'select' );
                                }
                            } );
                            break;
                        default: /* successfully saved */
                            standards.callFunction( '_createMessageBox', {
                                msg : 'SAVE_SUCCESS'
                                ,fn : function(){
                                    _resetForm( form );
                                }
                            } );
                            break;
                    }
                }
            } )
        }

        function _resetForm( form ){
            form.reset();
            Ext.getCmp( 'gridUnrecordedReceipts' + module ).store.removeAll();
            Ext.getCmp( 'gridUnrecordedDisbursements' + module ).store.removeAll();
            Ext.getCmp( 'gridUnadjustedBankBalReceipts' + module ).store.removeAll();
            Ext.getCmp( 'gridUnadjustedBankBalDisbursements' + module ).store.removeAll();
            Ext.getCmp( 'gridJournalEntry' + module ).store.removeAll();
            Ext.getCmp( 'totalAdjustment' + module ).reset();
            _processComputation();
            grdWindowAdjustment     = new Array();
        }

        function _transactionHandler( status ){

        }

        function _printPDF( ){
            var par  = standards.callFunction('getFormDetailsAsObject',{ module : module });
            par['title']            = 'Bank Reconciliation Form'
            par['idBankRecon']      = Ext.getCmp( 'idBankRecon' + module ).getValue();
            par['printJE']          = Ext.getCmp( 'printStatusJEgridJournalEntry' + module ).getValue();
            par['reconMonth']       = Ext.getCmp( 'reconMonth' + module ).getValue();
            par['reconYear']        = Ext.getCmp( 'reconYear' + module ).getValue();
            par['idBankRecon']      = Ext.getCmp( 'idBankRecon' + module ).getValue();
            par['idBankAccount']    = Ext.getCmp( 'idBankAccount' + module ).getValue();
            Ext.Ajax.request( {
                url			: route + 'generatePDF'
                ,method		:'post'
                ,params		: par
                ,success	: function(response, action){
                    if( isGae == 1 ){
						window.open(route+'viewPDF/Bank Reconciliation Form','_blank');
					}else{
						window.open('pdf/accounting/Bank Reconciliation Form.pdf');
					}
                }
			} );
        }

        function _processComputation(){
            var totalUnadjustedBankBalReceipts          = 0
                ,totalUnadjustedBankBalReceiptsNot      = 0
                ,totalUnadjustedBankBalDisbursements    = 0
                ,totalUnadjustedBankBalDisbursementsNot = 0
                ,totalUnrecordedReceipts                = Ext.getCmp( 'gridUnrecordedReceipts' + module ).store.sum( 'amount' )
                ,totalUnrecordedDisbursements           = Ext.getCmp( 'gridUnrecordedDisbursements' + module ).store.sum( 'amount' )
                
                ,grdBankReceiptBankBalRec               = Ext.getCmp( 'gridUnadjustedBankBalReceipts' + module ).getStore().getRange()
                ,grdBankDisbursementBankBalRec          = Ext.getCmp( 'gridUnadjustedBankBalDisbursements' + module ).getStore().getRange()
                ,unAdjustedBankBalance                  = Ext.getCmp( 'unAdjustedBankBalance' + module ).getValue()
                ,unAdjustedBookBalance                  = Ext.getCmp( 'unadjustedBookBalance' + module ).getValue()
                ,totalBank                              = document.getElementById( 'totalBank' + module )
                ,totalBook                              = document.getElementById( 'totalBook' + module );

            grdBankReceiptBankBalRec.forEach( function( data, key ){
                if( data.get( 'chk' ) ) totalUnadjustedBankBalReceipts      += data.get( 'amount' );
                else totalUnadjustedBankBalReceiptsNot   += data.get( 'amount' );
            } )

            grdBankDisbursementBankBalRec.forEach( function( data, key ){
                if( data.get( 'chk' ) ) totalUnadjustedBankBalDisbursements += data.get( 'amount' );
                else totalUnadjustedBankBalDisbursementsNot += data.get( 'amount' );
            } )

            if( totalBank ){
                totalBank.innerHTML = Ext.util.Format.number( ( totalUnadjustedBankBalReceipts - totalUnadjustedBankBalDisbursements ), '0,000.00' );
            }

            if( totalBook ){
                totalBook.innerHTML = Ext.util.Format.number( ( totalUnrecordedReceipts - totalUnrecordedDisbursements ), '0,000.00' );
            }

            Ext.getCmp( 'depositInTransit' + module ).setValue( totalUnadjustedBankBalReceiptsNot );
            var adjustedBankBalance = ( ( unAdjustedBankBalance + totalUnadjustedBankBalReceiptsNot ) - ( totalUnadjustedBankBalDisbursementsNot - Ext.getCmp( 'totalAdjustment' + module ).getValue() ) );
            Ext.getCmp( 'adjustedBankBalance' + module ).setValue( adjustedBankBalance );
            var adjustedBookBalance = ( ( unAdjustedBookBalance + totalUnrecordedReceipts ) - totalUnrecordedDisbursements );
            Ext.getCmp( 'adjustedbookbal' + module ).setValue( adjustedBookBalance );
            Ext.getCmp( 'difference' + module ).setValue( Math.abs( adjustedBankBalance - adjustedBookBalance ) );
            Ext.getCmp( 'outstandingCheques' + module ).setValue( totalUnadjustedBankBalDisbursementsNot );
        }

        function _reloadGrid( fromEdit = false ){
            var reconMonth                          = parseInt( Ext.getCmp( 'reconMonth' + module ).getValue(), 10 )
                ,reconYear                          = parseInt( Ext.getCmp( 'reconYear' + module ).getValue(), 10 )
                ,idBankAccount                      = parseInt( Ext.getCmp( 'idBankAccount' + module ).getValue(), 10 )
                ,gridUnadjustedBankBalReceipts      = Ext.getCmp( 'gridUnadjustedBankBalReceipts' + module )
                ,gridUnadjustedBankBalDisbursements = Ext.getCmp( 'gridUnadjustedBankBalDisbursements' + module )
                ,gridUnrecordedReceipts             = Ext.getCmp( 'gridUnrecordedReceipts' + module )
                ,gridUnrecordedDisbursements        = Ext.getCmp( 'gridUnrecordedDisbursements' + module )
                ,gridJE                             = Ext.getCmp( 'gridJournalEntry' + module )
                ,idBankRecon                        = Ext.getCmp( 'idBankRecon' + module ).getValue()
                ,me                                 = Ext.getCmp( "idBankAccount" + module )
                ,record                             = me.findRecord( me.valueField, me.getValue() )
                ,index                              = me.store.indexOf( record )
                ,varField                           = me.store.getAt( index );
            if( typeof varField != 'undefined' ){
                Ext.getCmp( 'idCoa' + module ).setValue( varField.data.idCoa );
                Ext.getCmp( 'bankAccountCode' + module ).setValue( varField.data.acod_c15 );
                Ext.getCmp( 'bankAccountDescription' + module ).setValue( varField.data.aname_c30 );
                var rec = gridJE.store.find( 'idCoa', varField.data.idCoa )
                if( rec < 0 ){
                    gridJE.store.add( {
                        idCoa       : varField.data.idCoa
                        ,code       : varField.data.acod_c15
                        ,name       : varField.data.aname_c30
                    } );
                }
                else{
                    gridJE.store.getAt( rec ).set( 'idCoa', varField.data.idCoa );
                    gridJE.store.getAt( rec ).set( 'code', varField.data.acod_c15 );
                    gridJE.store.getAt( rec ).set( 'name', varField.data.aname_c30 );
                }
            }

            if( reconMonth > 0 && reconYear > 0 && idBankAccount > 0 ){
                gridUnadjustedBankBalReceipts.store.load( {
                    params      : {
                        reconMonth      : reconMonth
                        ,reconYear      : reconYear
                        ,idBankAccount  : idBankAccount
                        ,idBankRecon    : idBankRecon
                    }
                    ,callback   : function(){
                        gridUnadjustedBankBalDisbursements.store.load( {
                            params      : {
                                reconMonth      : reconMonth
                                ,reconYear      : reconYear
                                ,idBankAccount  : idBankAccount
                                ,idBankRecon    : idBankRecon
                            }
                            ,callback   : function(){
                                Ext.Ajax.request( {
                                    url     : route + 'getAccBookBalance'
                                    ,msg    : 'Retrieving account and book balance, please wait...'
                                    ,params : {
                                        reconMonth      : reconMonth
                                        ,reconYear      : reconYear
                                        ,idBankAccount  : idBankAccount
                                        ,idBankRecon    : idBankRecon
                                        ,idCoa          : parseInt( Ext.getCmp( 'idCoa' + module ).getValue(), 10 )
                                    }
                                    ,success        : function( response ){
                                        var resp = Ext.decode( response.responseText );
                                        Ext.getCmp( 'bankAccountBalance' + module ).setValue( resp.bankAccountBalance );
                                        Ext.getCmp( 'unadjustedBookBalance' + module ).setValue( resp.unadjustedBookBalance );
                                        _processComputation();
                                    }
                                } );
                            }
                        } );
                    }
                } );
            }
            else{
                gridUnadjustedBankBalReceipts.store.removeAll();
                gridUnadjustedBankBalDisbursements.store.removeAll();
                _processComputation();
            }
            if( !fromEdit ){
                gridUnrecordedReceipts.store.removeAll();
                gridUnrecordedDisbursements.store.removeAll();
            }
        }

        return{
			initMethod:function( config ){
				route		= config.route;
				baseurl		= config.baseurl;
				module		= config.module;
                canDelete	= config.canDelete;
                canCancel   = config.canCancel;
				pageTitle   = config.pageTitle;
				idModule	= config.idmodule
				isGae		= config.isGae;
                idAffiliate = config.idAffiliate;
                canSave     = config.canSave;
				
				return _mainPanel( config );
			}
		}
    }
}