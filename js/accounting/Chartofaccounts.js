/**
 * Developer    : Jayson Dagulo
 * Module       : Chart of account Settings
 * Date         : Dec 19, 2019
 * Finished     : Feb 03, 2020
 * Description  : This module allows authorized users to set (add, edit and delete) an account that will be used in journal entries.
 * DB Tables    : coa, coahistory, coaaffiliate, coaaffiliatehistory and logs
 * */ 
function Chartofaccounts(){
    return function(){
        var baseurl, route, module, canDelete, pageTitle, idModule, isGae,isSaved = 0, idAffiliate
            ,canAdd;

        var majorAccountAsset           = standards.callFunction( '_createLocalStore' , {
                data    : [
                    'Current Assets'
                    ,'Land Property & Equipment'
                    ,'Other Assets'
                ]
            } )
            ,majorAccountLiabilities    = standards.callFunction( '_createLocalStore' , {
                data    : [
                    'Current Liabilities'
                    ,'Long Term Liabilities'
                    ,'Other Liabilities'
                ]
            } )
            ,majorAccountCaptial        = standards.callFunction( '_createLocalStore' , {
                data    : [
                    'Capital'
                ]
            } )
            ,majorAccountRevenue        = standards.callFunction( '_createLocalStore' , {
                data    : [
                    'Revenue'
                ]
            } )
            ,majorAccountExpenses       = standards.callFunction( '_createLocalStore' , {
                data    : [
                    'Expenses'
                ]
            } );

        function _init(){

        }

        function _mainPanel( config ){
            var typeAccountStore        = standards.callFunction( '_createLocalStore' , {
                    data    : [
                        'Header Account'
                        ,'Subsidiary Account'
                    ]
                } )
                ,classificationStore    = standards.callFunction( '_createLocalStore' , {
                    data    : [
                        'Assets'
                        ,'Liabilities'
                        ,'Capital'
                        ,'Revenue'
                        ,'Expenses'
                    ]
                } )
                ,headerAccountStore     = standards.callFunction(  '_createRemoteStore' , {
                    fields  : [
                        {   name    : 'id'
                            ,type   : 'number'
                        }
                        ,'name'
                    ]
                    ,url    : route + "headerAccountStore"
                } )
                ,majorAccountStore      = standards.callFunction( '_createLocalStore' , {
                    data    : []
                } )
                ,categoryStore          = standards.callFunction( '_createLocalStore', {
                    data    : [
                        'Regular Account'
                        ,'Cash Account'
                        ,'Receivable Account'
                        ,'Allowance for Bad Debits'
                        ,'Inventories'
                        ,'Raw Materials'
                        ,'Work in Progress'
                        ,'Finished Goods'
                        ,'Properties and Equipments'
                        ,'Accumulated Depreciation'
                        ,'Accumulated Amortization'
                        ,'Payable Account'
                        ,'Cost of Sales'
                        ,'Sales'
                        ,'Sales Debits'
                        ,'Other Income'
                        ,'Operating Expenses'
                        ,'Cost of Goods Manufactured'
                        ,'Purchase Credits'
                        ,'Direct Labor'
                        ,'Manufacturing Overhead'
                        ,'Applied Factory Overhead'
                        ,'Other Expenses'
                        ,'Retained Earnings'
                        ,'Creditable Income Tax'
                        ,'Capital'
                        ,'Withdrawals'
                        ,'Other Assets'
                    ]
                } )
                ,normalBalanceStore = standards.callFunction( '_createLocalStore' , {
                    data    : [ 'Debit', 'Credit' ]
                } )
                ,cashFlowStore      = standards.callFunction( '_createLocalStore', {
                    data    : [ 'Financing', 'Investing', 'Operating' ]
                } );
            return standards.callFunction( '_mainPanel', {
                config      : config
                ,moduleType : 'form'
                ,extraFormButton : [
                    {   label       : 'Import from Excel'
                        ,id         : 'importBtn' + module
                        ,iconCls    : 'glyphicon glyphicon-upload'
                        ,handler    : function(){
                            importCOAHandler();
                        }
                    }
                ]
                ,tbar       : {
                    listLabel       : 'List'
                    ,saveFunc       : _saveForm
                    ,resetFunc      : _resetForm
                    ,noPDFButton    : true
                    ,noExcelButton  : true
                    ,filter         : {
                        filterByData    : [
                            {   'name'             	: 'Account name'
                                ,'tableNameColumn'  : 'aname_c30'
                                ,'tableIDColumn'    : 'idCoa'
                                ,'tableName'        : 'coa'
                                ,'isDateRange'      : 0
                                ,'defaultValue'     : 0
                            }
                            ,{  'name'              : 'Account Code'
                                ,'tableNameColumn'  : 'idCoa'
                                ,'tableIDColumn'    : 'idCoa'
                                ,'tableName'        : 'coa'
                                ,'isDateRange'      : 0
                                ,'defaultValue'     : -1
                                // ,'dataURL'          : route + 'getCategoryRecords'
                                // ,'hasAll'           : 1
                            }
                        ]
                    }
                }
                ,formItems  : [
                    {   xtype       : 'hiddenfield'
                        ,id         : 'idCoaOld' + module
                        ,value      : 0
                    }
                    ,{  xtype       : 'hiddenfield'
                        ,id         : 'accod_c2' + module
                        ,value      : 0
                    }
                    ,{  xtype       : 'hiddenfield'
                        ,id         : 'sucod_c3' + module
                        ,value      : 0
                    }
                    ,{  xtype       : 'hiddenfield'
                        ,id         : 'recordedBy' + module
                        ,value      : Ext.getConstant( 'USERID' )
                    }
                    // ,{  xtype       : 'button'
                    //     ,text       : 'Assign All COA to Affiliate'
                    //     ,handler    : function(){
                    //         Ext.Ajax.request( {
                    //             url         : route + 'processScript'
                    //             ,success    : function(){
                    //                 alert( 'Done' )
                    //             }
                    //         } )
                    //     }
                    // }
                    ,{   xtype      : 'container'
                        ,layout     : 'column'
                        ,items      : [
                            {   xtype           : 'container'
                                ,columnWidth    : 0.38
                                ,minWidth       : 420
                                ,items          : [
                                    standards.callFunction( '_createCombo', {
                                        id              : 'accountType' + module
                                        ,store          : typeAccountStore
                                        ,fieldLabel     : 'Account Type'
                                        ,allowBlank     : false
                                        ,editable       : false
                                        ,listeners      : {
                                            select      : function( me, records ){
                                                Ext.getCmp( 'acod_c15' + module ).reset();
                                                Ext.getCmp( 'accod_c2' + module ).reset();
                                                Ext.getCmp( 'sucod_c3' + module ).reset();
                                                if( parseInt( me.value, 10 ) == 1 ){
                                                    Ext.getCmp('cmbMain'+module).allowBlank  = true;
													Ext.getCmp('cmbMain'+module).disable();
													_ACCCODE();
                                                }
                                                else{
                                                    Ext.getCmp( 'cmbMain' + module ).allowBlank  = false;
													Ext.getCmp( 'cmbMain' + module ).enable();
													Ext.getCmp( 'cmbMain' + module ).reset();
													Ext.getCmp( 'cmbMain' + module ).store.proxy.extraParams = {
														mocod_c1    : Ext.getCmp( 'mocod_c1' + module ).value
														,chcod_c1   : Ext.getCmp( 'chcod_c1' + module ).value
													}
													Ext.getCmp('cmbMain'+module).store.load();
                                                }
												Ext.getCmp('cmbMain'+module).validate();
												Ext.getCmp('cmbMain'+module).clearInvalid();
                                            }
                                        }
                                    } )
                                    ,standards.callFunction( '_createCombo', {
                                        id              : 'mocod_c1' + module
                                        ,store          : classificationStore
                                        ,fieldLabel     : 'Classification'
                                        ,allowBlank     : false
                                        ,editable       : false
                                        ,listeners      : {
                                            select      : function( me, records ){
                                                var chcod_c1 = Ext.getCmp( 'chcod_c1' + module );
                                                chcod_c1.reset();
                                                if( me.getValue() == 1 )
                                                    chcod_c1.bindStore( majorAccountAsset );
												else if (me.getValue() == 2 )
                                                    chcod_c1.bindStore( majorAccountLiabilities );
                                                else if (me.getValue() == 3 )
                                                    chcod_c1.bindStore( majorAccountCaptial );
                                                else if( me.getValue() == 4 )
                                                    chcod_c1.bindStore( majorAccountRevenue );
                                                else if( me.getValue() == 5 )
                                                    chcod_c1.bindStore( majorAccountExpenses );
                                                    
												chcod_c1.setValue( 1 );
												chcod_c1.fireEvent( 'select' );
												
												if( me.value == 1 || me.value == 5 ) Ext.getCmp( 'norm_c2' + module) .setValue( 1 );
												else Ext.getCmp( 'norm_c2' + module ).setValue( 2 );
                                            }
                                        }
                                    } )
                                    ,standards.callFunction( '_createCombo', {
                                        id              : 'chcod_c1' + module
                                        ,store          : majorAccountStore
                                        ,fieldLabel     : 'Major Account'
                                        ,allowBlank     : false
                                        ,editable       : false
                                        ,listeners      : {
                                            select      : function( me, record ){
												if( Ext.getCmp( 'accountType' + module ).value == 1 ){
													_ACCCODE();
												}else{
													Ext.getCmp( 'acod_c15' + module ).reset();
													Ext.getCmp( 'accod_c2' + module ).reset();
                                                    Ext.getCmp( 'sucod_c3' + module).reset();
                                                    var cmbMain = Ext.getCmp( 'cmbMain' + module );
													cmbMain.reset();
													cmbMain.store.proxy.extraParams = {
														mocod_c1    : Ext.getCmp( 'mocod_c1' + module ).value,
														chcod_c1    : this.value
													}
													cmbMain.store.load();		
												}
                                            }
                                        }
                                    } )
                                    ,standards.callFunction( '_createCombo', {
                                        id              : 'cmbMain' + module
                                        ,store          : headerAccountStore
                                        ,fieldLabel     : 'Header Account'
                                        ,allowBlank     : false
                                        ,listeners      : {
                                            select      : function( me, record ){
                                                _ACCCODE();
                                            }
                                        }
                                    } )
                                    ,standards.callFunction( '_createTextField' ,{
                                        id          : 'acod_c15' + module
                                        ,fieldLabel : 'Account Code'
                                        ,allowBlank : false
                                        ,maxLength  : 15
                                        ,emptyText  : 'Type account code'
                                     } )
                                     ,standards.callFunction( '_createTextField' ,{
                                        id          : 'aname_c30' + module
                                        ,fieldLabel : 'Account Name'
                                        ,allowBlank : false
                                        ,maxLength  : 100
                                        ,emptyText  : 'Type account name'
                                    } )
                                    ,standards.callFunction( '_createCombo', {
										id              : 'norm_c2' + module
										,store          : normalBalanceStore
										,fieldLabel     : 'Normal Balance'
										,allowBlank     : false
										,editable       : false
                                    } )
                                    ,standards.callFunction( '_createCombo', {
										id              : 'accID' + module
										,store          : categoryStore
										,fieldLabel     : 'Category'
										,allowBlank     : false
                                    } )
                                    ,standards.callFunction( '_createCombo', {
										id              : 'cashflow_classification' + module
										,store          : cashFlowStore
										,fieldLabel     : 'Cash Flow Classification'
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
                ]
                ,listItems  : _gridHistory()
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
                        ,{  name    : 'def'
                            ,type   : 'bool'
                        }
                    ]
                    ,url        : route + 'getCoaAffiliate'
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
                        }
                        ,beforedeselect : function( _this, record, index ){
                            if( record.get( 'def' ) ){
                                return false;
                            }
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
                        ,height     : 240
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
                                        idCoa   : null
                                    }
                                } )
                            }
                        }
                    } )
                ]
            };
        }

        function _gridHistory(){
            var store   = standards.callFunction( '_createRemoteStore', {
                fields  : [
                    {   name    : 'idCoa'
                        ,type   : 'number'
                    }
                    ,'acod_c15'
                    ,'aname_c30'
                    ,'normalBalance'
                    ,'categoryName'
                    ,'accountType'
                    ,'mocod_c1'
                    ,'chcod_c1'
                    ,'accod_c2'
                ]
                ,url    : route + 'getHistory'
            } );

            return standards.callFunction( '_gridPanel', {
				id		: 'gridHistory' + module
				,module	: module
				,store	: store
				,noPage : true
				,columns: [
					{	header          : 'Account Code'
						,dataIndex      : 'acod_c15'
						,width          : 200
						,columnWidth    : 15
                        ,sortable       : false
					}
					,{	header          : 'Account Name'
						,dataIndex      : 'aname_c30'
						,flex           : 1
						,minWidth       : 150
						,columnWidth    : 50
                        ,sortable       : false
					}
					,{	header          : 'Normal Balance'
						,dataIndex      : 'normalBalance'
						,width          : 200
						,columnWidth    : 20
                        ,sortable       : false
					}
					,{	header          : 'Category'
						,dataIndex      : 'categoryName'
						,width          : 300
						,columnWidth    : 15
                        ,sortable       : false
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

        function _saveForm( form ){
            var coaAffiliate    = new Array()
                ,_grdAffiliate  = Ext.getCmp( 'gridAffiliate' + module ).selModel.getSelection();
            _grdAffiliate.forEach( function( data ){
                coaAffiliate.push( {
                    idAffiliate : data.get( 'idAffiliate' )
                } )
            } );
            if( coaAffiliate.length <= 0 ){
                standards.callFunction( '_createMessageBox', {
                    msg : 'Please select atleast one affiliate.'
                } );
                return false;
            }
            form.submit( {
                url         : route + 'saveForm'
                ,params     : {
                    coaAffiliate    : Ext.encode( coaAffiliate )
                }
                ,success    : function( action, response ){
                    var resp    = Ext.decode( response.response.responseText )
                        ,match  = parseInt( resp.match, 10 );
                    switch( match ){
                        case 1: /* account code already exists */
                            standards.callFunction( '_createMessageBox', {
                                msg : 'Account code: ' + Ext.getCmp( 'acod_c15' + module ).getValue() + ' already exists.'
                            } );
                            break;
                        case 2: /* account name already exists */
                            standards.callFunction( '_createMessageBox', {
                                msg : 'Account name: ' + Ext.getCmp( 'aname_c30' + module ).getValue() + ' already exists.'
                            } );
                            break;
                        case 3: /* record to edit not found */
                            standards.callFunction( '_createMessageBox', {
                                msg : 'EDIT_UNABLE'
                                ,fn : function(){
                                    _resetForm( form );
                                }
                            } )
                            break;
                        case 4: /* edited by other employee */
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
                        default: /* successfully saved */
                            standards.callFunction( '_createMessageBox', {
                                msg : 'SAVE_SUCCESS'
                                ,fn : function(){
                                    _resetForm( form );
                                }
                            } )
                            break;
                    }
                }
            } )
        }

        function _resetForm( form ){
            form.reset();
            Ext.getCmp( 'gridAffiliate' + module ).store.load( {
                params      : {
                    idCoa   : null
                }
                ,callback   : function(){
                    Ext.getCmp( 'gridAffiliate' + module ).getView().refresh();
                }
            } );
			Ext.getCmp( 'cmbMain' + module ).allowBlank = false;
            Ext.getCmp( 'cmbMain' + module ).setDisabled( false );
            Ext.getCmp( 'chcod_c1' + module ).bindStore( majorAccountAsset );
        }

        function _editRecord( data ){
            module.getForm().retrieveData( {
                url         : route + 'retrieveData'
                ,params     : data
                ,success    : function( view, match ){
                    /* match value
                     * 0 = O K
                     * 1 = record not found
                     * 2 = record used
                    */
                    Ext.getCmp( 'idCoaOld' + module ).setValue( view.idCoa );
                    Ext.getCmp( 'accountType' + module ).store.load( {
                        callback : function(){
                           Ext.getCmp( 'accountType' + module ).setValue( parseInt( view.accountType, 10 ) );
                           if( parseInt( view.accountType, 10 ) == 1){
                                Ext.getCmp( 'cmbMain' + module ).allowBlank  = true;
                                Ext.getCmp( 'cmbMain' + module ).setDisabled( true );
                            }
                            else{
                                Ext.getCmp( 'cmbMain' + module ).allowBlank  = false;
                                Ext.getCmp( 'cmbMain' + module ).setDisabled( false );
                            }
                        }
                    } );

                    Ext.getCmp( 'mocod_c1' + module ).store.load( {
                        callback : function(){
                            Ext.getCmp( 'mocod_c1' + module ).setValue( parseInt( view.mocod_c1 ) );
                        }
                    } );

                    var chcod_c1 = Ext.getCmp('chcod_c1' + module);
                    chcod_c1.reset();
                    switch( parseInt( view.mocod_c1, 10 ) ){
                        case 1:
                            chcod_c1.bindStore( majorAccountAsset );
                            break;
                        case 2:
                            chcod_c1.bindStore( majorAccountLiabilities );
                            break;
                        case 3:
                            chcod_c1.bindStore( majorAccountCaptial );
                            break;
                        case 4:
                            chcod_c1.bindStore( majorAccountRevenue );
                            break;
                        case 5:
                            chcod_c1.bindStore( majorAccountExpenses );
                            break;
                    }
                    chcod_c1.setValue( parseInt( view.chcod_c1, 10 ) );

                    var cmbMain = Ext.getCmp( 'cmbMain' + module )
                    cmbMain.getStore().load( {
                        params      : {
                            mocod_c1    : view.mocod_c1
                            ,chcod_c1   : view.chcod_c1
                        }
                        ,callback   : function(){
                            if( parseInt( view.accountType, 10 ) == 2)
                                cmbMain.setValue( parseInt( view.accod_c2 ) );
                            else
                                cmbMain.reset();
                        }
                    } );
                    
                    Ext.getCmp( 'gridAffiliate' + module ).store.load( {
                        params  : {
                            idCoa   : view.idCoa
                        }
                        ,callback   : function(){
                            Ext.getCmp( 'gridAffiliate' + module ).getView().refresh();
                        }
                    } );

                    Ext.getCmp( 'cashflow_classification' + module ).setValue( parseInt( view.cashflow_classification, 10 ) );
                }
            } )
        }

        function _deleteRecord( data ){
            data.confirmDelete( {
                url         : route + 'deleteRecord'
                ,params     : data
                ,success    : function( action, response ){
					var res = Ext.decode( action.responseText );
					var match = parseInt( res.match, 10 );
					
					if( match == 1 )
						standards.callFunction( '_createMessageBox', {
							msg		: 'EDIT_UNABLE'
						} );
					else if( match == 2 )
						standards.callFunction( '_createMessageBox', {
							msg		: 'DELETE_USED'
                        } );
                    else if( match == 3 )
                        standards.callFunction( '_createMessageBox', {
                            msg     : 'Record cannot be deleted. Only records with no subsidiary accounts can be deleted.'
                        } )
					else{
						standards.callFunction( '_createMessageBox', {
							msg		: 'DELETE_SUCCESS'
						} );
						
                        if( parseInt( data.idCoa, 10 ) == parseInt( Ext.getCmp( 'idCoaOld' + module ).getValue(), 10 ) ) _resetForm( Ext.getCmp( 'mainFormPanel' + module ).getForm( ) );
                        var grd = Ext.getCmp( 'gridHistory' + module );
                        grd.store.load( {
                            callback    : function(){
                                grd.store.currentPage--;
                                grd.store.load( {} );
                            }
                        } )

                        Ext.getCmp( 'gridHistory' + module ).store.load();
					}
				}
            } )
        }

        function _ACCCODE(){
            Ext.Ajax.request( {
				url         : route + "getAccCode"
				,method     : 'post'
				,params     : {
					accountType : Ext.getCmp( 'accountType' + module ).value
					,mocod_c1   : Ext.getCmp( 'mocod_c1' + module ).value
					,chcod_c1   : Ext.getCmp( 'chcod_c1'+module ).value
					,accod_c2   : Ext.getCmp( 'cmbMain' + module ).value
				,}
				,success : function( response, options ){
                    var getdetails  = response.responseText
                        ,details    =  Ext.decode( getdetails );
					Ext.getCmp( 'accod_c2' + module ).setValue( details.view[0].accod_c2 );
					Ext.getCmp( 'sucod_c3' + module ).setValue(details.view[0].sucod_c3 );
					Ext.getCmp( 'acod_c15' + module ).setValue( details.view[0].code_c7 );
				}
			} );	
        }

        function importCOAHandler(){
            return Ext.create('Ext.window.Window',{
                id          : 'import_window' + module
                ,title      : 'Import from Excel'
                ,width      : 450
                ,modal      : true
                ,resizable  : false
                ,items : [
                    Ext.create('Ext.form.Panel',{
                        id              : 'import_form' + module
                        ,border         : false
                        ,bodyPadding    : 10
                        ,buttonAlign    :'center'
                        ,module         : module
                        ,items          : [
                            {	xtype: 'label'
                                ,html: 'Excel file must follow a specific format. You may download the file <a href="#" id="download_format_excel" style="font-family: Helvetica Neue, Helvetica, Arial, sans-serif !important;">here</a>.'
                                ,listeners:{
                                    afterrender: function(){
                                        document.getElementById("download_format_excel").onclick 	= function(){
                                            Ext.Ajax.request({
                                                url		: route + 'generateSampleCSV'
                                                ,method	: 'post'
                                                ,success: function(response, action){
                                                    var path = route.replace( baseurl, '');
                                                    window.open( baseurl + path + 'download' + '/' + 'Sample COA List' );
                                                }
                                            })

                                        }
                                    }
                                },
                            }
                            ,{	xtype       : 'fileuploadfield',
                                width       :  400,
                                fieldLabel  : 'File',
                                labelWidth  : 20,
                                name        : 'file_import'+module,
                                id          : 'file_import'+module,
                                style       : 'margin-top:3px',
                                buttonConfig: {
                                    text: '',
                                    iconCls: 'glyphicon glyphicon-folder-open',
                                },
                                msgTarget:'under',
                                validator: function(value){
                                    try{
                                        if(value){
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
                        ]
                        ,buttons: [
                            ,{  text: 'Import'
                                ,id: 'importFileBtn' + module
                                ,formBind : true
                                ,disabled : true
                                ,handler: function(){
                                    var form   = Ext.getCmp('import_form'+module).getForm();
                                    var fileName = Ext.getCmp('file_import'+module).getValue();

                                    form.submit({
                                        waitTitle   : "Please wait",
                                        waitMsg     : "Submitting data...", 
                                        method      : 'post',
                                        url         : route + 'importCOA',
                                        params      :{ module: module },
                                        success:function(res,response){
                                            var ret = Ext.decode(response.response.responseText);
                                            console.log( ret );

                                            if( ret.invalid_rec.length > 0 ){
                                                Ext.getCmp('import_window'+module).destroy(true);
                                                standards.callFunction( '_createMessageBox', {
                                                    title   : 'Import completed'
                                                    ,msg    : 'There are records that are invalid, would you like to view the invalid records?'
                                                    ,action : 'confirm'
                                                    ,fn	    : function( btn ){
                                                        if( btn == 'yes' ){
                                                            invalidImportedCOA( ret.invalid_rec );
                                                        }
                                                    }
                                                } );
                                            } else {
                                                Ext.getCmp('import_window'+module).destroy(true);
                                                standards.callFunction( '_createMessageBox', {
                                                    msg : 'Record(s) successfully imported.'
                                                } );
                                            }
                                        },
                                        failure:function(){
                                            standards.callFunction( '_createMessageBox', {
                                                msg     : 'SAVE_FAILURE'
                                                ,icon   : 'error'
                                            } );
                                        }
                                    });
                                }
                            }
                        ]
                    } )
                ]
                
            }).show(); 
        }

        function invalidImportedCOA( invalidRecords ){
            var recStore = Ext.create( 'Ext.data.Store', {
                fields	: [ 'accountType', 'classification', 'majorAccount','headerAccount','accountName','normalBalance','category', 'cashFlow', 'reason']
                ,data	: invalidRecords
            } );

            return Ext.create('Ext.window.Window',{
                id          : 'import_window' + module
                ,title      : 'Invalid Records'
                ,width      : 550
                ,modal      : true
                ,resizable  : false
                ,items : [
                    Ext.create('Ext.form.Panel',{
                        id              : 'import_form' + module
                        ,border         : false
                        ,bodyPadding    : 10
                        ,buttonAlign    :'center'
                        ,module         : module
                        ,items          : [
                            {	xtype: 'label'
                                ,html: 'The following accounts have invalid inputs:'
                            }
                            ,standards.callFunction( '_gridPanel', {
                                id          : 'grdImportedCOA' + module
                                ,module     : module
                                ,store      : recStore
                                ,height     : 200
                                ,style      : 'margin-top:10px;'
                                // ,noPage     : true
                                ,columns    : [
                                    {   header      : 'Reason'
                                        ,dataIndex  : 'reason'
                                        ,width      : 280
                                    }
                                    ,{   header      : 'Account Type'
                                        ,dataIndex  : 'accountType'
                                        ,width      : 120
                                    }
                                    ,{   header      : 'Classification'
                                        ,dataIndex  : 'classification'
                                        ,width      : 120
                                    }
                                    ,{   header      : 'Major Account'
                                        ,dataIndex  : 'majorAccount'
                                        ,width      : 120
                                    }
                                    ,{   header      : 'Header Account'
                                        ,dataIndex  : 'headerAccount'
                                        ,width      : 120
                                    }
                                    ,{   header      : 'Account Name'
                                        ,dataIndex  : 'accountName'
                                        ,width      : 120
                                    }
                                    ,{   header      : 'Normal Balance'
                                        ,dataIndex  : 'normalBalance'
                                        ,width      : 120
                                    }
                                    ,{   header      : 'Category'
                                        ,dataIndex  : 'category'
                                        ,width      : 120
                                    }
                                    ,{   header      : 'Cash Flow Classification'
                                        ,dataIndex  : 'cashFlow'
                                        ,width      : 150
                                    }
                                ]
                                ,listeners  : {
                                    afterrender : function() {
                                        recStore.load({});
                                    }
                                }
                            } )
                        ]
                    } )
                ]
                
            }).show(); 
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