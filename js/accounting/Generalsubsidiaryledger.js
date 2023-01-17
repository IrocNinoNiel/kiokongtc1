/**
 * Developer: Jayson Dagulo
 * Module: General and Subsidiary Ledger
 * Date: Jan. 30, 2020
 * Finished: 
 * Description: This module allows authorized user to generate and print the payable balances and its ledger for every supplier.
 * */ 
function Generalsubsidiaryledger(){
    return function(){
        var baseurl, route, module, canDelete, pageTitle, canPrint, generalModule, subsidiaryModule, isGae, idAffiliate;
        
        function _mainPanel( config ){
            return standards.callFunction(	'_mainPanel' ,{
                config		        : config
                ,moduleType	        : 'report'
                ,customExcelHandler : _excelHandler
                ,customPDFHandler   : _pdfHandler
                ,tbar               : {
                    noPDFButton    : false
                    ,noExcelButton  : false
                    ,PDFHidden      : false
                }
                ,extraFormTab       : [
					{	xtype           : 'button'
						,buttonId       : 'btnGeneralLedger' + module
                        ,activeButton   : true
                        ,buttonIconCls  : 'modu'
						,buttonLabel    : 'General Ledger'
						,items          : _generalLedger()
					}
					,{	xtype           : 'button'
						,buttonId       : 'btnSubsidiaryLedger' + module
						,buttonIconCls  : 'list'
						,buttonLabel    : 'Subsidiary Ledger'
						,items          : _subsidiaryLedger()
					}
				]
            } );
        }

        function _generalLedger(){
            var coaStore        = standards.callFunction( '_createRemoteStore', {
                fields      : [
                    {   name    : 'id'
                        ,type   : 'number'
                    }
                    ,'name'
                ]
                ,url        : route + 'getCoaHeaders'
                ,pageSize   : 20
            } )
            return standards.callFunction( '_formPanel', {
                moduleType          : 'report'
                ,panelID            : 'balancesForm' + generalModule
                ,noHeader           : true
                ,module             : generalModule
                // ,afterResetHandler  : _resetGeneralForm
                ,customViewHandler  : _viewGeneralLedger
                ,config             : {
                    module  : generalModule
                }
                ,formItems          : [
                    {   xtype   : 'container'
                        ,layout : 'column'
                        ,style  : 'margin-bottom: 5px;'
                        ,items  : [
                            {   xtype           : 'container'
                                ,columnWidth    : .4
                                ,minWidth       : 365
                                ,items          : [
                                    standards2.callFunction( '_createAffiliateCombo', {
                                        module     : generalModule
                                        ,allowBlank : true
                                        ,listeners  : {
                                            afterrender : function(){
                                                var me  = this;
                                                me.store.load( {
                                                    callback    : function(){
                                                        me.setValue( parseInt( Ext.getConstant( 'AFFILIATEID' ), 10 ) );
                                                    }
                                                } )
                                            }
                                        }
                                    } )
                                    ,standards2.callFunction( '_createCOACombo', {
                                        id              : 'idCoa' + generalModule
                                        ,fieldLabel     : 'Account Title'
                                        ,valueField     : 'idCoa'
                                        ,displayField   : 'aname_c30'
                                        ,pageSize       : 20
                                        ,hasAll         : 1
                                        ,listeners      : {
                                            afterrender : function(){
                                                var me = this;
                                                me.store.load( {
                                                    callback    : function(){
                                                        me.setValue( 0 );
                                                    }
                                                } )
                                            }
                                        }
                                        ,createPicker   : function(){
                                            return standards.callFunction( 'createLoadMorePlugin', {
                                                id          : 'idCoa' + generalModule
                                            } )
                                        }
                                    } )
                                ]
                            }
                            ,{  xtype           : 'container'
                                ,columnWidth    : .5
                                ,items          : [
                                    {  xtype    : 'container'
                                        ,style  : 'margin-bottom : 5px;'
                                        ,layout : 'column'
                                        ,items  : [
                                            standards.callFunction( '_cmbMonth', {
                                                fieldLabel  : 'Month'
                                                ,id         : 'month' + generalModule
                                                ,allowBlank : false
                                                ,width      : 260
                                                ,style      : 'margin-right : 5px;'
                                                ,module     : generalModule
                                            } )
                                            ,standards.callFunction( '_createNumberField', {
                                                id                      : 'year' + generalModule
                                                ,fieldLabel             : ''
                                                ,withREQ                : false
                                                ,allowBlank             : false
                                                ,decimalPrecision       : 0
                                                ,width                  : 85
                                                ,hideTrigger            : false
                                                ,minValue               : 1980
                                                ,maxValue               : ( ( new Date() ).getFullYear() + 1 )
                                                ,value                  : ( new Date() ).getFullYear()
                                                ,useThousandSeparator   : false
                                            } )
                                        ]
                                    }
                                ]
                            }
                        ]
                    }
                    ,standards.callFunction( '_createCheckField', {
                        id          : 'hideZero' + generalModule
                        ,boxLabel   : 'Hide accounts whose value is 0.00'
                    } )
                ]
                ,moduleGrids    : _generalGrid()
            } )
        }

        function _generalGrid(){
            var store   = standards.callFunction( '_createRemoteStore', {
                fields  : [
                    'idCoa'
                    ,'idCoaHeader'
                    ,'acod_c15'
                    ,'aname_c30'
                    ,'aname_c30Header'
                    ,{  name    : 'yearBegBal'
                        ,type   : 'number'
                    }
                    ,{  name    : 'monthDR'
                        ,type   : 'number'
                    }
                    ,{  name    : 'monthCR'
                        ,type   : 'number'
                    }
                    ,{  name    : 'yearDR'
                        ,type   : 'number'
                    }
                    ,{  name    : 'yearCR'
                        ,type   : 'number'
                    }
                    ,{  name    : 'balance'
                        ,type   : 'number'
                    }
                ]
                ,url    : route + 'getGeneralLedger'
            } );
            return standards.callFunction( '_gridPanel', {
                id              : 'gridGeneral' + generalModule
				,module         : module
                ,store          : store
                ,noDefaultRow   : true
                ,tbar           : {
                    content     : ''
                }
                ,features       : {
                    ftype   : 'summary'
                }
                ,noPage         : true
                ,plugins        : true
                ,columns        : [
                    {   header          : 'Account Code'
                        ,dataIndex      : 'acod_c15'
                        ,width          : 100
                        ,columnWidth    : 10
                    }
                    ,{  header          : 'Account Name'
                        ,dataIndex      : 'aname_c30'
                        ,minWidth       : 150
                        ,flex           : 1
                    }
                    ,{  header          : 'Year Beg.'
                        ,dataIndex      : 'yearBegBal'
                        ,width          : 100
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,hasTotal       : true
                    }
                    ,{  header          : 'Month DR'
                        ,dataIndex      : 'monthDR'
                        ,width          : 100
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,hasTotal      : true
                    }
                    ,{  header          : 'Month CR'
                        ,dataIndex      : 'monthCR'
                        ,width          : 100
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,hasTotal       : true
                    }
                    ,{  header         : 'Year DR'
                        ,dataIndex      : 'yearDR'
                        ,width          : 100
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,hasTotal       : true
                    }
                    ,{  header         : 'Year CR'
                        ,dataIndex      : 'yearCR'
                        ,width          : 100
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,hasTotal       : true
                    }
                    ,{  header         : 'Balance'
                        ,dataIndex      : 'balance'
                        ,width          : 100
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,hasTotal       : true
                    }
                    ,standards.callFunction( '_createActionColumn', {
						icon        : 'th-list'
						,tooltip    : 'View Subsidiary Ledger'
						,Func       : _viewLedger
                    } )
                ]
            } )
        }

        function _viewGeneralLedger(){
            var form			= Ext.getCmp( 'mainFormPanel' + generalModule ).getForm()
                ,fields			= form.getFields()
                ,grids			= Ext.ComponentQuery.query( '#mainFormPanel' + generalModule + ' grid' )
                ,extraparams	= {};
            
            /** collect and contain fields' value to a single array  **/
            fields.each( function( field ) {
                if( field.getValue() != null && ( field.getValue().toString() != field.getRawValue().toString() ) ){
                    extraparams['raw'+field.getName().toString().replace( generalModule, '' )] = field.getRawValue();
                }
                extraparams[ field.getName().toString().replace( generalModule, '' ) ] = field.getSubmitValue();
            });
            extraparams['module'] = generalModule;

            /** store array to the form as property **/
            form.used = extraparams;
            
            for( var x = 0; x < grids.length; x++ ){
                var store						= grids[x].getStore();
                extraparams['grid']				= x+1;
                store.getProxy().extraParams	= extraparams;
                store.currentPage				= 1;
                
                if( x == grids.length - 1 ){
                    store.load( {
                        callback    : function( response, operation ){
                            var resp    = Ext.decode( operation.response.responseText )
                                ,match  = parseInt( resp.match, 10 );
                            switch( match ){
                                case 1:
                                    standards.callFunction( '_createMessageBox', {
                                        msg		: 'Selected month is not yet closed. If you want to proceed with generating this report, please create a closing entry.'
                                    } );
                                    break;
                                case 2:
                                    standards.callFunction( '_createMessageBox', {
                                        msg     : 'Previous closing entry not yet tagged as "Final"'
                                    } )
                                    break;
                                case 3:
                                    standards.callFunction( '_createMessageBox', {
                                        msg     : 'Month selected is earlier than the affiliate date start.'
                                    } )
                                    break;
                            }
                        }
                    } );
                }
                else{
                    store.load();
                }
            }
        }

        function _viewLedger( data ){
            var ledgerButton = Ext.getCmp( 'btnSubsidiaryLedger' + module );
            ledgerButton.handler.call( ledgerButton );
            var y   = Ext.getCmp( 'year' + generalModule ).value
                ,m  = Ext.getCmp( 'month' + generalModule ).value;
            Ext.getCmp( 'sdate' + subsidiaryModule ).setValue( new Date( y, m - 1, 1 ) );
            Ext.getCmp( 'edate' + subsidiaryModule ).setValue( new Date( y, m, 0 ) );
            Ext.getCmp( 'idAffiliate' + subsidiaryModule ).store.load( {
                callback    : function(){
                    Ext.getCmp( 'idAffiliate' + subsidiaryModule ).setValue( parseInt( Ext.getCmp( 'idAffiliate' + generalModule ).getValue(), 10 ) );
                    var idCoaHeader = Ext.getCmp( 'idCoaHeader' + subsidiaryModule );
                    idCoaHeader.store.proxy.extraParams.idAffiliate = data.idAffiliate;
                    idCoaHeader.store.load( {
                        params      : {
                            query    : data.aname_c30Header
                        }
                        ,callback   : function(){
                            idCoaHeader.setValue( parseInt( data.idCoaHeader, 10 ) );
                            var record = idCoaHeader.findRecord( 'idCoa', parseInt( data.idCoaHeader, 10 ) );
                            var subsidyID   = Ext.getCmp( 'idCoaSubsidiary' + subsidiaryModule );
                            if( record ){
                                subsidyID.store.proxy.extraParams.mocod_c1  = record.get( 'mocod_c1' );
                                subsidyID.store.proxy.extraParams.chcod_c1  = record.get( 'chcod_c1' );
                                subsidyID.store.proxy.extraParams.accod_c2  = record.get( 'accod_c2' );
                                subsidyID.store.load( {
                                    params      : {
                                        query   : data.aname_c30
                                    }
                                    ,callback   : function(){
                                        subsidyID.setValue( parseInt( data.idCoa, 10 ) );
                                        var viewButton  = Ext.getCmp( 'viewButton' + subsidiaryModule )
                                        viewButton.handler.call( viewButton.scope, viewButton );
                                    }
                                } );
                            }
                            else{
                                subsidyID.store.proxy.extraParams.mocod_c1  = '';
                                subsidyID.store.proxy.extraParams.chcod_c1  = '';
                                subsidyID.store.proxy.extraParams.accod_c2  = '';
                                subsidyID.reset();
                                idCoaHeader.reset();
                            }
                        }
                    } )
                }
            } )
        }

        function _subsidiaryLedger(){
            return standards.callFunction( '_formPanel', {
                moduleType          : 'report'
                ,panelID            : 'subsidiaryForm' + subsidiaryModule
                ,noHeader           : true
                ,module             : subsidiaryModule
                // ,afterResetHandler  : _resetGeneralForm
                ,config             : {
                    module  : subsidiaryModule
                }
                ,formItems          : [
                    {   xtype       : 'container'
                        ,layout     : 'column'
                        ,items      : [
                            {   xtype           : 'container'
                                ,columnWidth    : .4
                                ,minWidth       : 365
                                ,items          : [
                                    standards2.callFunction( '_createAffiliateCombo', {
                                        module     : subsidiaryModule
                                        ,allowBlank : true
                                        ,listeners  : {
                                            afterrender : function(){
                                                var me  = this;
                                                me.store.load( {
                                                    callback    : function(){
                                                        me.setValue( parseInt( Ext.getConstant( 'AFFILIATEID' ), 10 ) );
                                                    }
                                                } )
                                            }
                                        }
                                    } )
                                    ,standards2.callFunction( '_createCOACombo', {
                                        id              : 'idCoaHeader' + subsidiaryModule
                                        ,fieldLabel     : 'Account Title'
                                        ,valueField     : 'idCoa'
                                        ,displayField   : 'aname_c30'
                                        ,pageSize       : 20
                                        ,isHeader       : 1
                                        ,createPicker   : function(){
                                            return standards.callFunction( 'createLoadMorePlugin', {
                                                id          : 'idCoaHeader' + subsidiaryModule
                                            } )
                                        }
                                        ,listeners      : {
                                            select          : function( me, record ){
                                                if( typeof record[0] != 'undefined' ){
                                                    var subsidyID   = Ext.getCmp( 'idCoaSubsidiary' + subsidiaryModule );
                                                    subsidyID.store.proxy.extraParams.mocod_c1 = record[0].get( 'mocod_c1' );
                                                    subsidyID.store.proxy.extraParams.chcod_c1 = record[0].get( 'chcod_c1' );
                                                    subsidyID.store.proxy.extraParams.accod_c2 = record[0].get( 'accod_C2' );
                                                    subsidyID.store.load( {
                                                        callback    : function(){
                                                            subsidyID.setValue( 0 );
                                                        }
                                                    } )
                                                }
                                            }
                                            ,afterrender    : function(){
                                                var me = this;
                                                me.store.proxy.extraParams.idAffiliate = Ext.getConstant( 'AFFILIATEID' )
                                            }
                                        }
                                    } )
                                ]
                            }
                            ,{  xtype           : 'container'
                                ,columnWidth    : .5
                                ,items          : [
                                    standards.callFunction( '_createDateRange', {
                                        module          : subsidiaryModule
                                        ,width          : 111
                                        ,fromWidth      : 235
                                    } )
                                    ,standards2.callFunction( '_createCOACombo', {
                                        id              : 'idCoaSubsidiary' + subsidiaryModule
                                        ,fieldLabel     : 'Subsidiary Account'
                                        ,valueField     : 'idCoa'
                                        ,displayField   : 'aname_c30'
                                        ,pageSize       : 20
                                        ,isHeader       : 2
                                        ,hasAll         : 1
                                        ,createPicker   : function(){
                                            return standards.callFunction( 'createLoadMorePlugin', {
                                                id          : 'idCoaSubsidiary' + subsidiaryModule
                                            } )
                                        }
                                    } )
                                ]
                            }
                        ]
                    }
                ]
                ,moduleGrids    : _subsidiaryGrid()
            } );
        }

        function _subsidiaryGrid(){
            var store   = standards.callFunction( '_createRemoteStore', {
                fields      : [
                    'date'
                    ,'reference'
                    ,'acod_c15'
                    ,'aname_c30'
                    ,'description'
                    ,'idModule'
                    ,'idInvoice'
                    ,'idBankRecon'
                    ,'idAccBegBal'
                    ,{  name    : 'debit'
                        ,type   : 'number'
                    }
                    ,{  name    : 'credit'
                        ,type   : 'number'
                    }
                    ,{  name    : 'runningBalance'
                        ,type   : 'number'
                    }
                ]
                ,url        : route + 'getSubsidiaryLedger'
            } )
            return standards.callFunction( '_gridPanel', {
                id              : 'gridSubsidiary' + subsidiaryModule
				,module         : module
                ,store          : store
                ,noDefaultRow   : true
                ,tbar           : {
                    content     : ''
                }
                ,features       : {
                    ftype   : 'summary'
                }
                ,noPage         : true
                ,plugins        : true
                ,listeners      : {
                    itemdblclick: function(dataview, record, item, index, e) {
                        if( parseInt( record.data.idModule, 10 ) > 0 ) mainView.openModule( record.data.idModule , record.data, this );
                    }
                }
                ,columns        : [
                    {   header          : 'Date'
                        ,xtype          : 'datecolumn'
                        ,format         : 'm/d/Y'
                        ,dataIndex      : 'date'
                        ,width          : 100
                        ,sortable       : false
                        ,draggable      : false
                        ,menuDisabled   : true
                    }
                    ,{  header          : 'Ref'
                        ,dataIndex      : 'reference'
                        ,width          : 100
                        ,sortable       : false
                        ,draggable      : false
                        ,menuDisabled   : true
                    }
                    ,{  header          : 'Account Code'
                        ,dataIndex      : 'acod_c15'
                        ,width          : 120
                        ,sortable       : false
                        ,draggable      : false
                        ,menuDisabled   : true
                    }
                    ,{  header          : 'Account Name'
                        ,dataIndex      : 'aname_c30'
                        ,width          : 150
                        ,sortable       : false
                        ,draggable      : false
                        ,menuDisabled   : true
                    }
                    ,{  header          : 'Description'
                        ,dataIndex      : 'description'
                        ,minWidth       : 150
                        ,flex           : 1
                        ,sortable       : false
                        ,draggable      : false
                        ,menuDisabled   : true
                    }
                    ,{  header          : 'DR'
                        ,dataIndex      : 'debit'
                        ,width          : 150
                        ,sortable       : false
                        ,draggable      : false
                        ,menuDisabled   : true
                        ,hasTotal       : true
                        ,xtype          : 'numbercolumn'
                        ,
                    }
                    ,{  header          : 'CR'
                        ,dataIndex      : 'credit'
                        ,width          : 150
                        ,sortable       : false
                        ,draggable      : false
                        ,menuDisabled   : true
                        ,hasTotal       : true
                        ,xtype          : 'numbercolumn'
                        ,
                    }
                    ,{  header          : 'Running Balance'
                        ,dataIndex      : 'runningBalance'
                        ,width          : 150
                        ,sortable       : false
                        ,draggable      : false
                        ,menuDisabled   : true
                        ,xtype          : 'numbercolumn'
                    }
                ]
            } );
        }

        function _excelHandler(){

        }

        function _pdfHandler(){

        }

        return{
			initMethod:function( config ){
				route		        = config.route;
				baseurl		        = config.baseurl;
				module		        = config.module;
				canPrint	        = config.canPrint;
				canDelete	        = config.canDelete;
				canEdit		        = config.canEdit;
				pageTitle           = config.pageTitle;
				idModule	        = config.idmodule
				isGae		        = config.isGae;
                idAffiliate         = config.idAffiliate;
                generalModule       = module + 'general';
                subsidiaryModule    = module + 'subsidiary';
				
				return _mainPanel( config );
			}
		}
    }
}