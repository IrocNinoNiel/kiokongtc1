/**
 * Developer    : Jayson Dagulo
 * Module       : Accounting Defaults
 * Date         : Dec 26, 2019
 * Finished     : Mar 11, 2020
 * Description  : This module allows authorized users to set the accounting defaults of every transaction module.
 * DB Tables    : 
 * */ 
function Accountingdefaults(){
    return function(){
        var baseurl, route, module, canDelete, pageTitle, idModule, isGae,isSaved = 0, idAffiliate
            ,canAdd, configJournal, configAccount;
        
        function _mainPanel( config ){
            configJournal       = Ext.encode( config );
            configAccount      = Ext.encode( config );
            configJournal      = Ext.decode( configJournal );
            configAccount      = Ext.decode( configAccount );
            configJournal.module    = '_defaultjournalentry';
            configAccount.module    = '_defaultaccount';
            var moduleStore         = standards.callFunction( '_createRemoteStore', {
                    fields  : [ { name : 'idModule', type : 'number' }, 'module' ]
                    ,url    : route + 'getModules'
                } )
                ,refStore           = standards.callFunction( '_createRemoteStore', {
                    fields  : [ { name : 'id', type : 'number' }, 'name' ]
                    ,url    : route + 'getReference'
                } )
                ,affiliateStore     = standards.callFunction( '_createRemoteStore', {
                    fields  : [ 'idAffiliate', 'affiliateName', 'accSchedule', 'month' ]
                    ,url    : route + 'getAffiliates'
                } );
            return standards.callFunction(	'_mainPanel' ,{
				config		: config
				,isTabChild : true
				,listeners: {
					// afterrender : _init
				}
				,formItems:[
                    {
						xtype : 'tabpanel'
						,items : [
							{ 	title : 'Default Journal Entry'
								,layout : {
									type: "card"
									,deferredRender: true
								}
								,items:[
									standards.callFunction(	'_mainPanel' ,{
										formType        : 'form'
                                        ,config         : configJournal
                                        ,statLabelID 	: 'headerLabel_configJournal'
										,asContainer    : true
										,tbar           : {
                                            saveFunc        : _saveFormDefault
                                            ,resetFunc      : _resetFormDefault
											,listLabel      : 'List'
											,noExcelButton  : true
                                            ,hasFormPDF     : true
                                            ,formPDFHandler : _printFormDefaultJE
											,extraParams    : {
												mode : 'default'
											}
											,filter         : {
												filterByData    : [
                                                    {   'name'             	: 'Purpose'
                                                        ,'tableNameColumn'  : 'purpose'
                                                        ,'tableIDColumn'    : 'idDefaultEntry'
                                                        ,'tableName'        : 'defaultentry'
                                                        ,'isDateRange'      : 0
                                                        ,'defaultValue'     : 0
                                                    }
                                                ]
                                            }
                                            ,module : configJournal.module
                                        }
                                        ,formItems      : [
                                            {   xtype       : 'hiddenfield'
                                                ,id         : 'idDefaultEntry' + configJournal.module
                                                ,value      : 0
                                            }
                                            ,{   xtype      : 'container'
                                                ,layout     : 'column'
                                                ,items      : [
                                                    {   xtype           : 'container'
                                                        ,columnWidth    : 0.38
                                                        ,minWidth       : 420
                                                        ,items          : [
                                                            standards.callFunction( '_createTextField', {
                                                                id          : 'purpose' + configJournal.module
                                                                ,fieldLabel : 'Purpose'
                                                                ,allowBlank : false
                                                                ,maxLength  : 250
                                                            } )
                                                            ,standards.callFunction( '_createCombo', {
                                                                id              : 'idModule' + configJournal.module
                                                                ,fieldLabel     : 'Module'
                                                                ,store          : moduleStore
                                                                ,allowBlank     : false
                                                                ,displayField   : 'module'
                                                                ,valueField     : 'idModule'
                                                                ,listeners      : {
                                                                    select      : function( me, record){
                                                                        var cmbRef  = Ext.getCmp( 'idReference' + configJournal.module );
                                                                        cmbRef.store.proxy.extraParams.idModule = record[0].get( 'idModule' );
                                                                        cmbRef.store.load( {} );
                                                                    }
                                                                }
                                                            } )
                                                            ,standards.callFunction( '_createCombo', {
                                                                id              : 'idReference' + configJournal.module
                                                                ,fieldLabel     : 'Reference'
                                                                ,store          : refStore
                                                                ,allowBlank     : false
                                                                ,displayField   : 'name'
                                                                ,valueField     : 'id'
                                                            } )
                                                            ,standards.callFunction( '_createTextArea', {
                                                                id              : 'remarks' + configJournal.module
                                                                ,fieldLabel     : 'Remarks'
                                                                ,height         : 175
                                                            } )
                                                        ]
                                                    }
                                                    ,{  xtype           : 'container'
                                                        ,columnWidth    : 0.38
                                                        ,minWidth       : 380
                                                        ,layout         : 'fit'
                                                        ,items          : _grdAffiliate()
                                                    }
                                                ]
                                            }
                                            ,{  xtype   : 'container'
                                                ,layout : 'fit'
                                                ,style  : 'margin-top:10px;'
                                                ,items  : _grdCoa( configJournal )
                                            }
                                        ]
                                        ,listItems  : _grdHistory()
                                    } )
                                ]
                            }
                            ,{ 	title : 'Default Account Settings'
								,layout : {
									type: "card"
									,deferredRender: true
								}
								,items:[
									standards.callFunction(	'_mainPanel' ,{
										formType        : 'form'
                                        ,config         : configAccount
                                        ,statLabelID 	: 'headerLabel_configJournal'
										,asContainer    : true
										,tbar           : {
                                             saveFunc       : _saveFormAccount
											,resetFunc      : _resetFormAccount
											,noFormButton   : true
											,noListButton   : true
                                        }
                                        ,formItems      : [
                                            standards.callFunction( '_createCombo', {
                                                id              : 'idAffiliate' + module
                                                ,fieldLabel     : 'Affiliate'
                                                ,allowBlank     : false
                                                ,store          : affiliateStore
                                                ,labelWidth     : 260
                                                ,width          : 725
                                                ,style          : 'margin-left: 10px;'
                                                ,valueField     : 'idAffiliate'
                                                ,displayField   : 'affiliateName'
                                                ,listeners      : {
                                                    afterrender : function(){
                                                        configAccount.module.getForm().reset();
                                                        Ext.getCmp( 'idAffiliate' + module ).store.load( {
                                                            callback    : function(){
                                                                Ext.getCmp( 'idAffiliate' + module ).setValue( Ext.getConstant( 'AFFILIATEID' ) );
                                                                var recIndex    = affiliateStore.find( 'idAffiliate', Ext.getConstant( 'AFFILIATEID' ) )
                                                                    ,recs       = affiliateStore.getAt( recIndex );
                                                                Ext.getCmp( 'scheduleField' + configAccount.module ).setValue( recs.get( 'accSchedule' ) );
                                                                Ext.getCmp( 'monthField' + configAccount.module ).setValue( recs.get( 'month' ) );
                                                                _retrieveDefaultAccount( {
                                                                    idAffiliate : recs.get( 'idAffiliate' )
                                                                } )
                                                            }
                                                        } );
                                                    }
                                                    ,select : function( me, record ){
                                                        configAccount.module.getForm().reset();
                                                        me.setValue( record[0].get( 'idAffiliate' ) );
                                                        Ext.getCmp( 'scheduleField' + configAccount.module ).setValue( record[0].get( 'accSchedule' ) );
                                                        Ext.getCmp( 'monthField' + configAccount.module ).setValue( record[0].get( 'month' ) );
                                                        _retrieveDefaultAccount( {
                                                            idAffiliate : me.getValue()
                                                        } )
                                                    }
                                                }
                                            } )
                                            ,{  xtype   : 'container'
                                                ,layout : 'column'
                                                ,style  : 'margin-left: 10px;'
                                                ,items  : [
                                                    standards.callFunction( '_createTextField', {
                                                        id              : 'scheduleField' + configAccount.module
                                                        ,fieldLabel     : 'Accounting Schedule'
                                                        ,submitValue    : false
                                                        ,labelWidth     : 260
                                                        ,width          : 490
                                                        ,style          : 'margin-right : 5px;'
                                                        ,readOnly       : true
                                                    } )
                                                    ,standards.callFunction( '_createTextField', {
                                                        id              : 'monthField' + configAccount.module
                                                        ,fieldLabel     : ''
                                                        ,submitValue    : false
                                                        ,width          : 230
                                                        ,readOnly       : true
                                                    } )
                                                ]
                                            }
                                            ,{  xtype   : 'tabpanel'
                                                ,style  : 'margin-top: 10px;'
                                                ,items  : [
                                                    ,{   title          : 'Accounting'
                                                        ,bodyPadding    : 10
                                                        ,items          : [
                                                            {   xtype   : 'container'
                                                                ,items  : [
                                                                    ,{  xtype   : 'hiddenfield'
                                                                        ,id     : 'idDefaultAcc' + configAccount.module
                                                                        ,name   : 'idDefaultAcc' + configAccount.module
                                                                        ,value  : 0
                                                                    }
                                                                    ,_accountField( {
                                                                        idField         : 'debitRec' + configAccount.module
                                                                        ,idCodeField    : 'debitRecCode' + configAccount.module
                                                                        ,codeFieldLabel : 'Cash Account Debited in Receivable Payment'
                                                                        ,style          : ''
                                                                        ,idNameField    : 'debitRecName' + configAccount.module
                                                                    } )
                                                                    ,_accountField( {
                                                                        idField         : 'creditPay' + configAccount.module
                                                                        ,idCodeField    : 'creditPayCode' + configAccount.module
                                                                        ,codeFieldLabel : 'Cash Account Credited in Payable Payment'
                                                                        ,idNameField    : 'creditPayName' + configAccount.module
                                                                    } )
                                                                    ,_accountField( {
                                                                        idField         : 'accRec' + configAccount.module
                                                                        ,idCodeField    : 'accRecCode' + configAccount.module
                                                                        ,codeFieldLabel : 'Accounts Receivable'
                                                                        ,idNameField    : 'accRecName' + configAccount.module
                                                                    } )
                                                                    ,_accountField( {
                                                                        idField         : 'accPay' + configAccount.module
                                                                        ,idCodeField    : 'accPayCode' + configAccount.module
                                                                        ,codeFieldLabel : 'Accounts Payable'
                                                                        ,idNameField    : 'accPayName' + configAccount.module
                                                                    } )
                                                                    ,_accountField( {
                                                                        idField         : 'debitMemo' + configAccount.module
                                                                        ,idCodeField    : 'debitMemoCode' + configAccount.module
                                                                        ,codeFieldLabel : 'Debit Memo'
                                                                        ,idNameField    : 'debitMemoName' + configAccount.module
                                                                    } )
                                                                    ,_accountField( {
                                                                        idField         : 'creditMemo' + configAccount.module
                                                                        ,idCodeField    : 'creditMemoCode' + configAccount.module
                                                                        ,codeFieldLabel : 'Credit Memo'
                                                                        ,idNameField    : 'creditMemoName' + configAccount.module
                                                                    } )
                                                                    ,_accountField( {
                                                                        idField         : 'inputTax' + configAccount.module
                                                                        ,idCodeField    : 'inputTaxCode' + configAccount.module
                                                                        ,codeFieldLabel : 'Input Tax'
                                                                        ,idNameField    : 'inputTaxName' + configAccount.module
                                                                    } )
                                                                    ,_accountField( {
                                                                        idField         : 'outputTax' + configAccount.module
                                                                        ,idCodeField    : 'outputTaxCode' + configAccount.module
                                                                        ,codeFieldLabel : 'Output Tax'
                                                                        ,idNameField    : 'outputTaxName' + configAccount.module
                                                                    } )
                                                                    ,_accountField( {
                                                                        idField         : 'salesAccount' + configAccount.module
                                                                        ,idCodeField    : 'salesAccountCode' + configAccount.module
                                                                        ,codeFieldLabel : 'Sales Account'
                                                                        ,idNameField    : 'salesAccountName' + configAccount.module
                                                                    } )
                                                                    ,_accountField( {
                                                                        idField         : 'salesDiscount' + configAccount.module
                                                                        ,idCodeField    : 'salesDiscountCode' + configAccount.module
                                                                        ,codeFieldLabel : 'Sales Discount'
                                                                        ,idNameField    : 'salesDiscountName' + configAccount.module
                                                                    } )
                                                                    ,_accountField( {
                                                                        idField         : 'otherIncome' + configAccount.module
                                                                        ,idCodeField    : 'otherIncomeCode' + configAccount.module
                                                                        ,codeFieldLabel : 'Other Income'
                                                                        ,idNameField    : 'otherIncomeName' + configAccount.module
                                                                    } )
                                                                    ,_accountField( {
                                                                        idField         : 'retainedEarnings' + configAccount.module
                                                                        ,idCodeField    : 'retainedEarningsCode' + configAccount.module
                                                                        ,codeFieldLabel : 'Retained Earnings'
                                                                        ,idNameField    : 'retainedEarningsName' + configAccount.module
                                                                    } )
                                                                    ,_accountField( {
                                                                        idField         : 'incomeTaxProvision' + configAccount.module
                                                                        ,idCodeField    : 'incomeTaxProvisionCode' + configAccount.module
                                                                        ,codeFieldLabel : 'Provisions For Income Tax'
                                                                        ,idNameField    : 'incomeTaxProvisionName' + configAccount.module
                                                                    } )
                                                                    ,_accountField( {
                                                                        idField         : 'cashEquivalents' + configAccount.module
                                                                        ,idCodeField    : 'cashEquivalentsCode' + configAccount.module
                                                                        ,codeFieldLabel : 'Cash for Cash Equivalents'
                                                                        ,idNameField    : 'cashEquivalentsName' + configAccount.module
                                                                    } )
                                                                ]
                                                            }
                                                        ]
                                                    }
                                                    ,{   title          : 'Payroll'
                                                        ,bodyPadding    : 10
                                                        ,items          : []
                                                        ,disabled       : true
                                                    }
                                                ]
                                            }
                                        ]
                                    } )
                                ]
                            }
                        ]
                    }
                ]
            } );
        }

        function _grdHistory(){
            var store   = standards.callFunction( '_createRemoteStore', {
                fields  : [
                    'idDefaultEntry'
                    ,'purpose'
                    ,'moduleName'
                    ,'referenceName'
                ]
                ,url    : route + 'getHistory'
            } );

            return standards.callFunction( '_gridPanel', {
				id		        : 'gridHistory' + configJournal.module
				,module	        : module
				,store	        : store
                ,noPage         : true
                ,noDefaultRow   : true
				,columns        : [
					{	header          : 'Purpose'
						,dataIndex      : 'purpose'
						,flex           : 1
						,minWidth       : 150
						,columnWidth    : 50
					}
					,{	header          : 'Module Name'
						,dataIndex      : 'moduleName'
						,width          : 200
						,columnWidth    : 25
					}
					,{	header          : 'Reference'
						,dataIndex      : 'referenceName'
						,width          : 200
						,columnWidth    : 25
					}
					,standards.callFunction( '_createActionColumn', {
						icon            : 'pencil'
						,tooltip        : 'Edit record'
						,Func           : _editRecord
					} )
					,standards.callFunction( '_createActionColumn' ,{
						canDelete       : canDelete
						,icon           : 'remove'
						,tooltip        : 'Remove record'
						,Func           : _deleteRecord
					} )
				]
			} );
        }

        function _grdAffiliate(){
            var store   = standards.callFunction( '_createRemoteStore', {
                fields      : [
                    {   name    : 'chk'
                        ,type   : 'bool'
                    }
                    ,'idAffiliate'
                    ,'affiliateName'
                ]
                ,url        : route + 'getAffiliates'
                ,autoLoad   : true
            } )
            ,sm     = new Ext.selection.CheckboxModel( {
                checkOnly   : true
                ,listeners  : {
                    select          : function( val, rec ){
                        var record = rec.data;
                        record.chk = true;
                    }
                    ,deselect       : function( val, record ){
                        record.set( 'chk', false );
                        Ext.getCmp( 'grdJournalEntry' + module ).store.removeAll();
                    }
                }
            } );

            return {
                xtype       : 'container'
                ,layout     : 'column'
                ,items      : [
                    {	xtype   : 'label'
                        ,html   : 'Affiliate' + Ext.getConstant('REQ') + ':' 
                    }
                    ,standards.callFunction( '_gridPanel', {
                        id          : 'gridAffiliate' + module
                        ,module     : module
                        ,store      : store
                        ,height     : 260
                        ,width      : 280
                        ,selModel   : sm
                        ,plugins    : true
                        ,noPage     : true
                        ,tbar       : 'empty'
                        ,style      : 'margin-left:20px;'
                        ,viewConfig : {
                            markDirty       : false
                            ,getRowClass    : function(record, index) {
                                var classs = '';
                                if( record.get( 'def' ) ){
                                    classs += ' gridDefaultCheck ';
                                }
                                return classs;
                            }
                        }
                        ,columns    : [
                            {	header      : 'Affiliate'
                                ,dataIndex  : 'affiliateName'
                                ,flex       : 1
                                ,renderer   : function( val, params, record, row_index ){
                                    if( record.data.chk ){
                                        sm.select( row_index, true );
                                    }
                                    return val;
                                }				
                            }
                        ]
                        ,listeners  : {
                            afterrender : function(){
                                store.load( {
                                    params  : {
                                        idDefaultEntry   : null
                                    }
                                } )
                            }
                        }
                    } )
                ]
            };
        }

        function _grdCoa( params ){
            var store       = standards.callFunction( '_createRemoteStore', {
                    fields  : [
                        'idCoa'
                        ,'acod_c15'
                        ,'aname_c30'
                        ,{  name    : 'credit'
                            ,type   : 'float'
                        }
                        ,{  name    : 'debit'
                            ,type   : 'float'
                        }
                    ]
                    ,url    : route + 'getDefaultEntryAccounts'
                } )
                ,params1    = Ext.encode( params )
                ,params2    = Ext.encode( params );
            
            params1                 = Ext.decode( params1 );
            params1['cmbID']        = 'cmbCoaCode' + module;
            params1['displayField'] = 'code';
            params2                 = Ext.decode( params2 );
            params2['cmbID']        = 'cmbCoaName' + module;
            params2['displayField'] = 'name';

            cmbCoaCode  = _comboCoa( params1 );
            cmbCoaName  = _comboCoa( params2 );

            return standards.callFunction( '_gridPanel', {
                id				    : 'grdJournalEntry' + module
                ,module			    : params.module
                ,style			    : ( typeof params.style != 'undefined' )? params.style : ''
                ,store			    : store
                ,cls			    : 'gridJournalEntryClass' + params.module
                ,noDefaultRow	    : true
                ,tbar			    : {
                    content		: 'add'
                }
                ,plugins        : true
                ,bbarTotalLabel	: ''
                ,columns        : [
                    {	header		: 'Code'
                        ,dataIndex	: 'acod_c15'
                        ,width		: 130
                        ,editor		: cmbCoaCode
                    }
                    ,{	header		: 'Name'
                        ,dataIndex	: 'aname_c30'
                        ,flex		: 1
                        ,minWidth	: 150
                        ,editor		: cmbCoaName
                    }
                    ,{	header		: 'Debit'
                        ,dataIndex	: 'debit'
                        ,width		: 120
                        ,xtype		: 'numbercolumn'
                        ,editor		: 'float'
                        ,hasTotal	: true
                    }
                    ,{	header		: 'Credit'
                        ,dataIndex	: 'credit'
                        ,width		: 120
                        ,xtype		: 'numbercolumn'
                        ,editor		: 'float'
                        ,hasTotal	: true
                    }
                ]
            } )
        }

        function _comboCoa( params ){
            var store   = standards.callFunction( '_createRemoteStore', {
                fields  : [ 'id', 'code', 'name' ]
                ,url    : route + 'getCoa'
            } );
            store.proxy.extraParams.field = ( typeof params.displayField != 'undefined'? params.displayField : 'name' );
            return standards.callFunction( '_createCombo', {
                id              : params.cmbID
                ,store          : store
                ,displayField   : ( typeof params.displayField != 'undefined'? params.displayField : 'name' )
                ,valueField     : ( typeof params.displayField != 'undefined'? params.displayField : 'name' )
                ,forceSelection	: params.forceSelection
                ,pageSize		: params.pageSize
                ,autoLoad		: params.autoLoad
                ,module			: params.module
                ,reQuery        : false
                ,listeners      : {
                    select          : function( me, record ){
                        var grdRec = Ext.getCmp( 'grdJournalEntry' + module ).selModel.getSelection()[0];
                        grdRec.set( 'idCoa', record[0].get( 'id' ) );
                        if( params.cmbID == 'cmbCoaCode' + module ){
                            grdRec.set( 'aname_c30', record[0].get( 'name' ) );
                            if( Ext.getCmp( 'cmbCoaName' + module ).getStore().getTotalCount() <= 0 ){
                                Ext.getCmp( 'cmbCoaName' + module ).fireEvent( 'beforequery' );
                                Ext.getCmp( 'cmbCoaName' + module ).store.load( {} );
                            }
                        }
                        else{
                            grdRec.set( 'acod_c15', record[0].get( 'code' ) );
                            if( Ext.getCmp( 'cmbCoaCode' + module ).getStore().getTotalCount() <= 0 ){
                                Ext.getCmp( 'cmbCoaCode' + module ).fireEvent( 'beforequery' );
                                Ext.getCmp( 'cmbCoaCode' + module ).store.load( {} );
                            }
                        }
                    }
                    ,beforequery    : function( me ){
                        var grdAffiliate    = Ext.getCmp( 'gridAffiliate' + module ).selModel.getSelection()
                            ,affRec         = new Array();
                        grdAffiliate.forEach( function( data ){
                            affRec.push( data.get( 'idAffiliate' ) );
                        } );
                        store.proxy.extraParams.affiliates = Ext.encode( affRec );
                        delete me.lastQuery;
                    }
                }
            } )
        }

        function _accountField( params ){
            return {    xtype   : 'container'
                ,layout         : 'column'
                ,style          : ( typeof params.style != 'undefined'? params.style : 'margin-top: 5px;' )
                ,items          : [
                    {   xtype           : 'hiddenfield'
                        ,id             : params.idField
                        ,name           : params.idField
                        ,value          : 0
                    }
                    ,standards.callFunction( '_createTextField', {
                        id              : params.idCodeField
                        ,labelWidth     : 260
                        ,width          : 490
                        ,fieldLabel     : params.codeFieldLabel
                        ,style          : 'margin-right: 5px'
                        ,submitValue    : false
                        ,readOnly       : true
                    } )
                    ,standards.callFunction( '_createTextField', {
                        id              : params.idNameField
                        ,fieldLabel     : ''
                        ,width          : 230
                        ,style          : 'margin-right: 5px;'
                        ,submitValue    : false
                        ,readOnly       : true
                    } )
                    ,{  xtype       : 'button'
                        ,iconCls    : 'glyphicon glyphicon-list-alt'
                        ,style      : 'margin-right: 5px;'
                        ,handler    : function(){
                            _accountPopOut( params );
                        }
                    }
                    ,{  xtype       : 'button'
                        ,iconCls    : 'glyphicon glyphicon-refresh'
                        ,handler    : function(){
                            Ext.getCmp( params.idCodeField ).reset();
                            Ext.getCmp( params.idNameField ).reset();
                            Ext.getCmp( params.idField ).reset();
                        }
                    }
                ]
            }
        }
        
        function _accountPopOut( params ){
            var store       = standards.callFunction('_createRemoteStore', {
                    fields  : [ 'idCoa', 'acod_c15', 'aname_c30' ]
                    ,url    : route + 'getAccountListing'
                } )
                sByStore    = standards.callFunction( '_createLocalStore', {
                    data    : [ 'Account Name', 'Account Code' ]
                } );
                store.proxy.extraParams = {
                    idAffiliate : Ext.getCmp( 'idAffiliate' + module ).getValue()
                    ,sBy        : 1
                };

            Ext.create( 'Ext.Window', {
                title           : 'Accounts'
                ,id             : 'accountsPopOut' + module
                ,height         : 310
                ,width          : 500
                ,modal          : true
                ,items          : [
                    {   xtype   : 'container'
                        ,layout : 'fit'
                        ,items  : [
                            standards.callFunction( '_gridPanel', {
                                id              : 'grdAccountsPopOut' + module
                                ,module         : module
                                ,store          : store
                                ,height         : 280
                                ,noPage         : true
                                ,noDefaultRow   : true
                                ,tbar			    : {
                                    content		    : [
                                        standards.callFunction( '_createCombo', {
                                            id              : 'cmbSrchBy' + module
                                            ,store          : sByStore
                                            ,editable       : false
                                            ,value          : 1
                                            ,fieldLabel     : ''
                                            ,width          : 120
                                            ,listeners      : {
                                                select  : function( me, record ){
                                                    store.proxy.extraParams.sBy = me.value;
                                                }
                                            }
                                        } )
                                        ,standards.callFunction( '_createTextField', {
                                            id              : 'srchVal' + module
                                            ,fieldLabel     : ''
                                            ,width          : 360
                                            ,listeners      : {
                                                change      : function( me ){
                                                    store.load( {
                                                        params  : {
                                                            query   : me.value
                                                        }
                                                    } )
                                                }
                                            }
                                        } )
                                    ]
                                }
                                ,plugins        : true
                                ,columns        : [
                                    {   header      : 'Account Code'
                                        ,dataIndex  : 'acod_c15'
                                        ,width      : 100
                                    }
                                    ,{   header      : 'Account Name'
                                        ,dataIndex  : 'aname_c30'
                                        ,flex       : 1
                                    }
                                    ,standards.callFunction( '_createActionColumn', {
                                        icon            : 'ok'
                                        ,tooltip        : 'Select record'
                                        ,Func           : _selectRecord
                                    } )
                                ]
                                ,listeners      : {
                                    afterrender : function(){
                                        store.load( {} );
                                    }
                                }
                            } )
                        ]
                    }
                ]

            } ).show()

            function _selectRecord( data ){
                Ext.getCmp( params.idField ).setValue( data.idCoa );
                Ext.getCmp( params.idCodeField ).setValue( data.acod_c15 );
                Ext.getCmp( params.idNameField ).setValue( data.aname_c30 );
                Ext.getCmp( 'accountsPopOut' + module ).destroy( true );
            }
        }

        function _saveFormDefault( form ){
            var selAffiliate            = Ext.getCmp( 'gridAffiliate' + module ).selModel.getSelection()
                ,grdJournalEntry        = Ext.getCmp( 'grdJournalEntry' + module ).getStore().getRange()
                ,affiliateRecords       = new Array()
                ,journalEntryRecords    = new Array()
                ,totalDebit             = 0
                ,totalCredit            = 0;
            selAffiliate.forEach( function( data, index ){
                affiliateRecords.push( {
                    idAffiliate : data.get( 'idAffiliate' )
                } );
            } );
            grdJournalEntry.forEach( function( data, index ){
                if( data.get( 'idCoa' ) ){
                    journalEntryRecords.push( {
                        idCoa   : data.get( 'idCoa' )
                        ,credit : data.get( 'credit' )
                        ,debit  : data.get( 'debit' )
                    } );
                    totalDebit  += data.get( 'debit' );
                    totalCredit += data.get( 'credit' );
                }
            } );
            if( totalDebit != totalCredit ){
                standards.callFunction( '_createMessageBox', {
                    msg : 'Invalid Journal Entry. Total Debit must be equal with the Total Credit.'
                } )
                return false;
            }
            if( affiliateRecords.length <= 0 ){
                standards.callFunction( '_createMessageBox', {
                    msg     : 'Please select atleast one affiliate.'
                } );
                return false;
            }
            if( journalEntryRecords.length <= 1 ){
                standards.callFunction( '_createMessageBox', {
                    msg     : 'Please add atleast 2 or more default journal entry accounts.'
                } );
                return false;
            }
            form.submit( {
                url         : route + 'saveFormDefault'
                ,params     : {
                    affiliateRecords        : Ext.encode( affiliateRecords )
                    ,journalEntryRecords    : Ext.encode( journalEntryRecords )
                }
                ,success    : function( action, response ){
                    var resp    = Ext.decode( response.response.responseText )
                        ,match  = parseInt( resp.match, 10 );
                    switch( match ){
                        case 1: /* purpose already exists */
                            standards.callFunction( '_createMessageBox', {
                                msg : 'Purpose already exists.'
                            } );
                            break;
                        case 2: /* record to edit not found */
                            standards.callFunction( '_createMessageBox', {
                                msg : 'EDIT_UNABLE'
                            } )
                            break;
                        default: /* successfully saved */
                            standards.callFunction( '_createMessageBox', {
                                msg : 'SAVE_SUCCESS'
                                ,fn : function(){
                                    _resetFormDefault( form )
                                }
                            } )
                            break;
                    }
                }
            } )
        } 
 
        function _resetFormDefault( form ){
            form.reset();
            Ext.getCmp( 'gridAffiliate' + module ).store.proxy.extraParams.idDefaultEntry = 0;
            Ext.getCmp( 'gridAffiliate' + module ).store.load( {} );
            Ext.getCmp( 'grdJournalEntry' + module ).store.load( {
                params  : {
                    idDefaultEntry : 0
                }
            } );
        }

        function _saveFormAccount( form ){
            form.submit( {
                url         : route + 'saveFormAccount'
                ,params     : {
                    affiliateName   : Ext.getCmp( 'idAffiliate' + module ).getRawValue()
                    ,idAffiliate    : Ext.getCmp( 'idAffiliate' + module ).getValue()
                }
                ,success    : function( action, response ){
                    standards.callFunction( '_createMessageBox', {
                        msg : 'SAVE_SUCCESS'
                        ,fn : function(){
                            _resetFormAccount( form );
                        }
                    } )
                }
            } );
        }

        function _resetFormAccount( form ){
            _retrieveDefaultAccount( { idAffiliate : Ext.getCmp( 'idAffiliate' + module ).getValue() } )
        }

        function _editRecord( data ){
            configJournal.module.getForm().retrieveData( {
                url         : route + 'retrieveData'
                ,params     : data
                ,hasFormPDF : true
                ,success    : function( view, match ){
                    /* match value
                     * 0 = OK
                     * 1 = record not found
                     * 2 = record used
                    */
                   Ext.getCmp( 'idReference' + configJournal.module ).store.proxy.extraParams.idModule = view.idModule;
                   Ext.getCmp( 'idReference' + configJournal.module ).store.load( {
                       callback : function(){
                        Ext.getCmp( 'idReference' + configJournal.module ).setValue( parseInt( view.idReference, 10 ) );
                       }
                   } )
                   Ext.getCmp( 'grdJournalEntry' + module ).store.load( {
                       params   : {
                           idDefaultEntry   : view.idDefaultEntry
                       }
                   } );
                   Ext.getCmp( 'gridAffiliate' + module ).store.load( {
                       params   : {
                            idDefaultEntry  : view.idDefaultEntry
                       }
                       ,callback    : function(){
                            Ext.getCmp( 'gridAffiliate' + module ).getView().refresh();
                       }
                   } )
                }
            } );
        }
        
        function _deleteRecord( data ){
            data.confirmDelete( {
                url         : route + 'deleteRecord'
                ,params     : data
                ,success    : function( action, response ){
                    var resp    = Ext.decode( action.responseText )
                        ,match  = parseInt( resp.match, 10 );
                    if( match == 1 ){
                        standards.callFunction( '_createMessageBox', {
                            msg     : 'EDIT_UNABLE'
                        } );
                    }
                    else{
                        standards.callFunction( '_createMessageBox', {
                            msg     : 'DELETE_SUCCESS'
                        } );
                    } 
                    Ext.getCmp( 'gridHistory' + configJournal.module ).currentPage = 1;
                    Ext.getCmp( 'gridHistory' + configJournal.module ).store.load()
                }
            } )
        }

        function _retrieveDefaultAccount( params ){
            configAccount.module.getForm().retrieveData( {
                url         : route + 'getDefaultAccount'
                ,params     : params
                ,hasFormPDF : true
                ,success    : function( view, match ){
                }
            } );
        }

        function _printFormDefaultJE(){
            var affiliateSelected   = new Array()
                ,journalEntry       = new Array()
                ,affGridRec         = Ext.getCmp( 'gridAffiliate' + module ).selModel.getSelection()
                ,jeGridRec          = Ext.getCmp( 'grdJournalEntry' + module ).getStore().getRange();
            if( Ext.getCmp( 'idDefaultEntry' + configJournal.module ).getValue() == 0 ){
                standards.callFunction( '_createMessageBox', {
                    msg     : 'NOREC_PRINT'
                } );
                return false;
            }

            for( var i = 0; i < affGridRec.length; i++ ){
                affiliateSelected.push( {
                    affiliateName   : affGridRec[i].get( 'affiliateName' )
                } );
            }

            for( var i = 0; i < jeGridRec.length; i++ ){
                console.warn( jeGridRec[i] );
                journalEntry.push( {
                    acod_c15    : jeGridRec[i].get( 'acod_c15' )
                    ,aname_c30  : jeGridRec[i].get( 'aname_c30' )
                    ,debit      : jeGridRec[i].get( 'debit' )
                    ,credit     : jeGridRec[i].get( 'credit' )
                } )
            }

            Ext.Ajax.request( {
                url         : route + 'printPDF'
                ,params     : {
                    pageTitle           : pageTitle
                    ,idDefaultAcc       : Ext.getCmp( 'idDefaultEntry' + configJournal.module ).getValue()
                    ,purpose            : Ext.getCmp( 'purpose' + configJournal.module ).getValue()
                    ,moduleName         : Ext.getCmp( 'idModule' + configJournal.module ).getRawValue()
                    ,referenceName      : Ext.getCmp( 'idReference' + configJournal.module ).getRawValue()
                    ,remarks            : Ext.getCmp( 'remarks' + configJournal.module ).getValue()
                    ,affiliateSelected  : Ext.encode( affiliateSelected )
                    ,journalEntry       : Ext.encode( journalEntry )
                }
                ,success    : function( res ){
                    if( Ext.getConstant('ISGAE') ){
						window.open( route + 'viewPDF/' + pageTitle, '_blank' );
					}
					else{
						window.open( baseurl + 'pdf/accounting/' + pageTitle + '.pdf');
					}
                }
            } )
        }

        return{
            initMethod:function( config ){
                route		= config.route;
                baseurl		= config.baseurl;
                module		= config.module;
                canDelete	= config.canDelete;
                pageTitle   = config.pageTitle;
                idModule	= config.idmodule
                isGae		= config.isGae;
                idAffiliate = config.idAffiliate
                
                return _mainPanel( config );
            }
        }
    }
}