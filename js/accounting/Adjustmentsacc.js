/**
 * Developer    : Jays
 * Date         : Feb. 18, 2020
 * Module       : Adjustment(Accounting)
 * Finished     : Mar. 03, 2020
 * Description  : This module allows authorized user to set (add, edit, and delete) an adjustment transactions.
 * DB Tables    : 
 * */ 
function Adjustmentsacc(){
    return function(){
        var baseurl, route, module, canDelete, pageTitle, idModule, isGae,isSaved = 0,deletedItems = [],selectedItem = [], idAffiliate
            ,canSave, fieldWidth = 406, fieldLabelWidth = 185, canEdit, componentCalling, selRec;

        function _mainPanel( config ){
			// CHECKS IF THE MAIN PANEL IS ALREADY RENDERED
			if (typeof Ext.getCmp('mainFormID' + module) != 'undefined' ) {
				if(selRec != null) _editRecord(selRec);
				return false;
			}
            
            var purchaseReturnStore     = standards.callFunction( '_createRemoteStore', {
                fields          : [
                    {   name    : 'id'
                        ,type   : 'number'
                    }
                    ,'name'
                    ,{  name    : 'amt'
                        ,type   : 'number'
                    }
                ]
                ,url            : route + 'getPRReferences'
            } );
            purchaseReturnStore.proxy.extraParams.idModule  = idModule;
            return standards2.callFunction( '_mainPanelTransactions', {
                config              : config
                ,moduleType         : 'form'
                ,hasApproved        : true
                ,id                 : 'mainFormID' + module
                ,transactionHandler :   _transactionHandler
                ,tbar               : {
                    saveFunc                : _saveForm
                    ,resetFunc              : _resetForm
                    ,formPDFHandler         : _printPDF
                    ,hasFormPDF             : true
                    ,filter : {
                        searchURL       : route + 'getAdjustmentRef'
                        ,emptyText      : 'Search reference here...'
                    }
                }
                ,formItems          : [
                    {   xtype       : 'hidden'
                        ,id         : 'idInvoice' + module
                        ,value      : 0
                    }
                    ,standards.callFunction( '_createCheckField', {
                        boxLabel        : '<span style="color:red;font-style:italic;"><strong>(Check if Negative Adjustment)</strong></span>'
                        ,id             : 'otherTag' + module
                        ,listeners      : {
                            change      : function(){
                                _setPRInvoice();
                            }
                        }
                    } )
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
                        ,afterAffiliateSelect   : _setPRInvoice
                    } )
                    ,{  xtype       : 'fieldset'
                        ,layout     : 'column'
                        ,padding    : 10
                        ,items      : [
                            {   xtype           : 'container'
                                ,columnWidth    : .5
                                ,minWidth       : 406
                                ,items          : [
                                    standards.callFunction( '_createTextField', {
                                        id          : 'description' + module
                                        ,fieldLabel : 'Description'
                                        ,width      : fieldWidth
                                        ,labelWidth : fieldLabelWidth
                                    } )
                                    ,{  xtype       : 'container'
                                        ,layout     : 'column'
                                        ,width      : fieldWidth
                                        ,style      : 'margin-bottom : 5px;'
                                        ,items      : [
                                            {  xtype            : 'hidden'
                                                ,id             : 'pType' + module
                                                ,value          : 0
                                            }
                                            ,{  xtype           : 'hidden'
                                                ,id             : 'pCode' + module
                                                ,value          : 0
                                            }
                                            ,standards.callFunction( '_createTextField', {
                                                id              : 'name' + module
                                                ,submitValue    : false
                                                ,fieldLabel     : 'Name'
                                                ,width          : 352
                                                ,labelWidth     : fieldLabelWidth
                                                ,submitValue    : false
                                                ,style          : 'margin-right : 5px;'
                                            } )
                                            ,{  xtype           : 'button'
                                                ,iconCls        : 'glyphicon glyphicon-user'
                                                ,id             : 'cmdPCode' + module
                                                ,style          : 'margin-right : 5px;'
                                                ,handler        : function(){
                                                    _customerSelector();
                                                }
                                            }
                                            ,{  xtype           : 'button'
                                                ,iconCls        : 'glyphicon glyphicon-refresh'
                                                ,id             : 'cmdRefPCode' + module
                                                ,handler        : function(){
                                                    Ext.getCmp( 'pType' + module ).reset();
                                                    Ext.getCmp( 'pCode' + module ).reset();
                                                    Ext.getCmp( 'name' + module ).reset();
                                                }
                                            }
                                        ]
                                    }
                                    ,standards.callFunction( '_createTextField', {
                                        id              : 'amount' + module
                                        ,fieldLabel     : 'Amount'
                                        ,width          : fieldWidth
                                        ,labelWidth     : fieldLabelWidth
                                        ,isNumber       : true
                                        ,isDecimal      : true
                                    } )
                                ]
                            }
                            ,{  xtype           : 'container'
                                ,columnWidth    : .5
                                ,minWidth       : 406
                                ,items          : [
                                    standards.callFunction( '_createTextField', {
                                        id          : 'remarks' + module
                                        ,fieldLabel : 'Remarks'
                                        ,width      : fieldWidth
                                        ,labelWidth : fieldLabelWidth
                                    } )
                                    ,standards.callFunction( '_createCombo', {
                                        id              : 'fident' + module
                                        ,fieldLabel     : 'Invoice'
                                        ,store          : purchaseReturnStore
                                        ,valueField     : 'id'
                                        ,displayField   : 'name'
                                        ,disabled       : true
                                        ,width          : fieldWidth
                                        ,labelWidth     : fieldLabelWidth
                                        ,listeners      : {
                                            select      : function( me, record ){
                                                Ext.getCmp( 'amountPR' + module ).setValue( record[0].get( 'amt' ) );
                                            }
                                        }
                                    } )
                                    ,standards.callFunction( '_createTextField', {
                                        id              : 'amountPR' + module
                                        ,fieldLabel     : 'Invoice Amount'
                                        ,width          : fieldWidth
                                        ,labelWidth     : fieldLabelWidth
                                        ,isNumber       : true
                                        ,isDecimal      : true
                                        ,disabled       : true
                                        ,readOnly       : true
                                        ,submitValue    : false
                                    } )
                                ]
                            }
                        ]
                    }
                    ,{
                        xtype   : 'tabpanel'
                        ,items  : [
                            {
                                title       : 'Journal Entries'
                                ,layout     : {
                                    type    : 'card'
                                }
                                ,items      : [
                                    standards.callFunction( '_gridJournalEntry', {
                                        module	        : module
                                        ,hasPrintOption : 1
                                        ,config         : config
                                        ,listeners      : {
                                            beforeedit  : function( me ){

                                            }
                                        }
                                    } )
                                ]
                            }
                        ]

                    }
                ]
                ,listItems          : _gridHistory()
                ,listeners  : {
                    afterrender : function(){
                        if ( selRec ) {
                            _editRecord( selRec )
                        }
                    }
                }
            } );
        }

        function _gridHistory(){
            var store   = standards.callFunction( '_createRemoteStore', {
                fields      : [
                    'idInvoice'
                    ,'reference'
                    ,'tdate'
                    ,'affiliateName'
                    ,'costCenterName'
                    ,'description'
                    ,'preparedByName'
                    ,'notedByName'
                    ,'statusText'
                    ,'idAffiliate'
                    ,'idReference'
                    ,'referenceNum'
                ]
                ,url        : route + 'getHistory'
            } );
            return standards.callFunction('_gridPanel', {
                id 					: 'gridHistory' + module
                ,module     		: module
                ,store      		: store
				,height     		: 265
				,noDefaultRow 		: true
                ,columns            : [
                    {   header          : 'Reference Number'
                        ,dataIndex      : 'reference'
                        ,width          : 150
                        ,columnWidth    : 10
                    }
                    ,{  header          : 'Date'
                        ,dataIndex      : 'tdate'
                        ,width          : 120
                        ,columnWidth    : 10
                        ,xtype          : 'datecolumn'
                        ,format         : 'm/d/Y h:i A'
                    }
                    ,{  header          : 'Cost Center'
                        ,dataIndex      : 'costCenterName'
                        ,width          : 150
                        ,columnWidth    : 10
                    }
                    ,{  header          : 'Description'
                        ,dataIndex      : 'description'
                        ,flex           : 1
                        ,minWidth       : 150
                        ,columnWidth    : 20
                    }
                    ,{  header          : 'Prepared By'
                        ,dataIndex      : 'preparedByName'
                        ,width          : 150
                        ,columnWidth    : 15
                    }
                    ,{  header          : 'Noted By'
                        ,dataIndex      : 'notedByName'
                        ,width          : 150
                        ,columnWidth    : 15
                    }
                    ,{  header          : 'Status'
                        ,dataIndex      : 'statusText'
                        ,width          : 100
                        ,columnWidth    : 10
                    }
                    ,standards.callFunction( '_createActionColumn', {
						canEdit     : canEdit
						,icon       : 'pencil'
						,tooltip    : 'Edit record'
						,Func       : _editRecord
                    } )
                    ,standards.callFunction( '_createActionColumn', {
						canDelete   : canDelete
						,icon       : 'remove'
						,tooltip    : 'Delete record'
						,Func       : _deleteRecord
					} )
                ]
            } )
        }

        function _saveForm( form ){
            var grdStore    = Ext.getCmp( 'gridJournalEntry' + module ).getStore()
                ,gridJEData = grdStore.getRange()
                ,jeRecords  = new Array()
                ,totalCR    = 0
                ,totalDR    = 0;
            gridJEData.forEach( function( data, index ){
                if( parseInt( data.get( 'idCoa' ), 10 ) > 0
                    && (
                        parseFloat( data.get( 'debit' ) ) > 0
                        || parseFloat( data.get( 'credit' ) ) > 0
                    )
                ){
                    jeRecords.push( data.data );
                    totalCR += parseFloat( data.get( 'credit' ) );
                    totalDR += parseFloat( data.get( 'debit' ) );
                }
            } );
            if( jeRecords.length <= 1 ){
                standards.callFunction( '_createMessageBox', {
                    msg     : 'Please add at least two journal entry record to proceed.'
                    ,icon   : Ext.MessageBox.ERROR
                } );
                return false;
            }
            if( totalCR != totalDR ){
                standards.callFunction( '_createMessageBox', {
                    msg     : 'Invalid transaction details. Make sure that total Debit and total Credit is balance.'
                    ,icon   : Ext.MessageBox.ERROR
                } );
                return false;
            }
            form.submit( {
                waitTitle	: "Please wait"
                ,waitMsg	: "Submitting data..."
                ,url		: route + 'saveAccountingAdjustment'
                ,params		: {
                    jeRecords   : Ext.encode( jeRecords )
                    ,date       : Ext.Date.format( Ext.getCmp( 'tdate' + module ).getValue(), 'Y-m-d' ) + ' ' + Ext.Date.format( Ext.getCmp( 'ttime' + module ).getValue(), 'H:i:s' )
                    ,idModule   : idModule
                }
                ,success:function( action, response ){
                    var resp    = Ext.decode( response.response.responseText )
                        ,match  = parseInt( resp.match, 10 );
                    switch( match ){
                        case 1: /* reference number already exists */
                            standards.callFunction( '_createMessageBox', {
                                msg     : 'Reference number already exists. System will generate new reference number.'
                                ,fn     : function(){
                                    standards2.callFunction( '_getReferenceNum', {
                                        idReference     : Ext.getCmp( 'idReference' + module ).getValue()
                                        ,idModule       : idModule
                                        ,idAffiliate    : Ext.getConstant('AFFILIATEID')
                                    } );
                                }
                            } );
                            break;
                        case 2: /* record already modified by other users */
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
                        case 3: /* record to save not found */
                            standards.callFunction( '_createMessageBox', {
                                msg : 'EDIT_UNABLE'
                                ,fn : function(){
                                    _rsetForm( form );
                                }
                            } )
                            break;
                        case 4: /* record is already approved by other user and is not allowed to be edited */
                            standards.callFunction( '_createMessageBox', {
                                msg : 'Record has already been ' + resp.curStatus + ' by other user.'
                                ,fn : function(){
                                    _resetForm( form );
                                }
                            } )
                            break;
                        default: /* record has been successfully saved */
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
        
        function _editRecord( data ){
            module.getForm().retrieveData( {
                url         : route + 'retrieveData'
                ,params     : data
                ,hasFormPDF	: true
                ,excludes   : ['referenceNum']
                ,success    : function( view, match ){
                    /* match value
                     * 0 = OK
                     * 1 = record not found
                     * 2 = record used
                    */
                    standards2.callFunction( '_setTransaction', {
                        data    : view
                        ,module : module
                    } );

                    Ext.getCmp('referenceNum'+module).setValue( parseInt(view.referenceNum, 10) );

                    var grdStore = Ext.getCmp( 'gridJournalEntry' + module ).getStore();
                    grdStore.load( {
                        params   : {
                            idInvoice    : view.idInvoice
                        }
                    } );
                    var fident   = Ext.getCmp( 'fident' + module );
                    fident.store.proxy.extraParams.fident    = view.fident;
                    fident.store.load( {
                        callback : function(){
                            fident.setValue( parseInt( view.fident, 10 ) );
                        }
                    } );
                    if( match > 1 || parseInt( view.status, 10 ) > 1 ){
                        if( match == 3 ){
                            standards.callFunction( '_createMessageBox', {
                                msg : 'Record has already been ' + ( parseInt( view.status, 10 ) == 2? 'approved' : 'cancelled' ) + '.'
                            } )
                        }
                        Ext.getCmp( 'saveButton' + module ).setVisible( false );
                        Ext.getCmp( 'addButton_gridJournalEntry' + module ).setDisabled( true );
                        Ext.getCmp( 'defaultAccountgridJournalEntry' + module ).setDisabled( true );
                    }
                }
            } )
        }

        function _deleteRecord( data ){
            data['idModule']        = idModule;
            data.confirmDelete( {
                url         : route + 'deleteRecord'
                ,params     : data
                ,success    : function( action, response ){
					var res = Ext.decode( action.responseText );
					var match = parseInt( res.match, 10 );
                    
                    switch( match ){
                        case 1: /* unable to find record to delete */
                            standards.callFunction( '_createMessageBox', {
                                msg		: 'EDIT_UNABLE'
                            } );
                            break;
                        case 2: /* record already used */
                            standards.callFunction( '_createMessageBox', {
                                msg		: 'DELETE_USED'
                            } );
                            break;
                        default: /* record successfully saved */
                            standards.callFunction( '_createMessageBox', {
                                msg		: 'DELETE_SUCCESS'
                            } );
                            if( parseInt( data.idInvoice, 10 ) == parseInt( Ext.getCmp( 'idInvoice' + module ).getValue(), 10 ) )
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

        function _transactionHandler( status ){
            standards.callFunction( '_createMessageBox', {
				msg		: 'Are you sure you want to ' + ( status == 2? 'approve' : ( status == 3? 'cancel' : '' ) ) + ' this transaction?'
				,action	: 'confirm'
				,fn		: function( btn ){
					if( btn == 'yes' ){
						_changeTransactionStatus( status );
					}
				}
			} );
        }

        function _customerSelector( params ){
            Ext.create('Ext.window.Window',{
				id          : 'pCodeSelectorWindow' + module
				,width      : 330
				,title      :'<span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;Select records'
				,height     : 375
				,modal      : true
				,closable   : false
                ,resizable  : false
                ,layout     : 'fit'
                ,items      : _gridSearch()
                ,buttons    : [
                    {   text        : 'Close'
                        ,handler    : function(){
                            Ext.getCmp( 'pCodeSelectorWindow' + module ).destroy( true );
                        }
                    }
                ]
            } ).show();
        }

        function _gridSearch(){
            var storePType      = standards.callFunction( '_createLocalStore', {
                    data        : [
                        'Customer'
                        ,'Supplier'
                    ]
                    ,startAt    : 1
                } )
                ,grdStore       = standards.callFunction( '_createRemoteStore', {
                    fields      : [
                        {   name    : 'id'
                            ,type   : 'number'
                        }
                        ,'name'
                    ]
                    ,url        : route + 'getPCodes'
                } );
            return standards.callFunction( '_gridPanel', {
                id 					: 'grdPCode' + module
                ,module     		: module
                ,store      		: grdStore
				,height     		: 265
                ,noDefaultRow 		: true
                ,noPage             : true
                ,tbar               : {
                    content         : [
                        standards.callFunction( '_createCombo', {
                            store           : storePType
                            ,fieldLabel     : ''
                            ,width          : 90
                            ,id             : 'pTypeSel' + module
                            ,valueField     : 'id'
                            ,displayField   : 'name'
                            ,value          : 1
                            ,listeners      : {
                                select      : function(){
                                    var me  = this;
                                    grdStore.clearFilter( true );
                                    grdStore.proxy.extraParams.pType    = me.getValue();
                                    grdStore.load( {} );
                                    Ext.getCmp( 'pCodeSrch' + module ).reset();
                                }
                            }
                        } )
                        ,standards.callFunction( '_createTextField', {
                            id              : 'pCodeSrch' + module
                            ,fieldLabel     : ''
                            ,width          : 220
                            ,emptyText      : 'Search customer name...'
                            ,listeners      : {
                                change      : function(){
                                    if( this.value ){
                                        grdStore.clearFilter( true );
                                        grdStore.filter( {
                                            property        : 'name'
                                            ,value          : this.value
                                            ,anyMatch       : true
                                            ,caseSensitive  : false
                                        } );
                                    }
                                    else{
                                        grdStore.clearFilter();
                                    }
                                }
                            }
                        } )
                    ]
                }
                ,columns            : [
                    {   header      : 'Name'
                        ,dataIndex  : 'name'
                        ,flex       : 1
                        ,minWidth   : 120
                    }
                    ,standards.callFunction( '_createActionColumn', {
						icon        : 'ok'
						,tooltip    : 'Select record'
						,Func       : _selectPCode
                    } ) 
                ]
                ,listeners          : {
                    afterrender     : function(){
                        grdStore.proxy.extraParams.pType    = 1;
                        grdStore.load( {} );
                    }
                }
            } );
        }

        function _selectPCode( data ){
            Ext.getCmp( 'fident' + module ).store.proxy.extraParams.pType = Ext.getCmp( 'pTypeSel' + module ).getValue();
            Ext.getCmp( 'fident' + module ).store.proxy.extraParams.pCode = data.id;
            Ext.getCmp( 'fident' + module ).store.load( {
                callback   : function() {
                    Ext.getCmp( 'fident' + module ).setValue( 0 );
                    Ext.getCmp( 'amountPR' + module ).setValue( 0 );
                }
            } )
            
            Ext.getCmp( 'pType' + module ).setValue( Ext.getCmp( 'pTypeSel' + module ).getValue() );
            Ext.getCmp( 'pCode' + module ).setValue( data.id );
            Ext.getCmp( 'name' + module ).setValue( data.name );
            Ext.getCmp( 'pCodeSelectorWindow' + module ).destroy( true );
        }

        function _changeTransactionStatus( status ){
            Ext.Ajax.request( {
                url     : route + 'changeTransactionStatus'
                ,params : {
                    status          : status
                    ,idInvoice      : Ext.getCmp( 'idInvoice' + module ).getValue()
                    ,notedBy        : Ext.getConstant( 'USERID' )
                    ,dateModified   : module.getForm().dateModified
                }
                ,success    : function( response ){
                    var resp    = Ext.decode( response.responseText )
                        match   = parseInt( resp.match, 10 );
                    switch( match ){
                        case 0:
                            standards.callFunction( '_createMessageBox', {
                                msg : 'Transaction has been successfully ' + ( status == 2? 'approve' : ( status == 3? 'cancel' : '' ) ) + '.'
                                ,fn : function(){
                                    _resetForm( Ext.getCmp( 'mainFormPanel' + module ).getForm( ) );
                                }
                            } );
                            break;
                        case 1:
                            standards.callFunction( '_createMessageBox', {
                                msg : 'EDIT_UNABLE'
                                ,fn : function(){
                                    _resetForm( Ext.getCmp( 'mainFormPanel' + module ).getForm( ) );
                                }
                            } );
                            break;
                        case 2:
                            standards.callFunction( '_createMessageBox', {
                                msg : 'Transaction status has been modified by other users.'
                                ,fn : function(){
                                    _resetForm( Ext.getCmp( 'mainFormPanel' + module ).getForm( ) );
                                }
                            } );
                            break;
                    }
                }
            } )
        }

        function _resetForm( form ){
            form.reset();
            // Ext.getCmp( 'idAffiliate' + module ).fireEvent( 'afterrender' );
            Ext.getCmp( 'gridJournalEntry' + module ).store.load( {
                params  : {
                    idModule    : idModule
                }
            } );

            //Transaction
			Ext.getCmp('approveTransButton' + module).setVisible( false );
            Ext.getCmp('cancelTransButton' + module).setVisible( false );
            document.getElementById( 'transactionStatus' + module ).innerHTML = '<span style="color:red; font-weight: bold;">Not Yet Confirmed</span>';
        }

        function _setPRInvoice(){
            var idAffiliate = parseInt( Ext.getConstant('AFFILIATEID'), 10 )
                ,otherTag   = Ext.getCmp( 'otherTag' + module ).getValue()
                ,fident     = Ext.getCmp( 'fident' + module )
                ,amountPR   = Ext.getCmp( 'amountPR' + module );
            fident.store.proxy.extraParams.idAffiliate = idAffiliate;
            fident.reset();
            if( otherTag ){
                fident.store.load();
            }
            
            amountPR.reset();
            fident.setDisabled( !otherTag );
            amountPR.setDisabled( !otherTag );
        }

        function _printPDF(){
            var par  = standards.callFunction('getFormDetailsAsObject',{ module : module });
            par['title']            = 'Adjustment Form'
            par['idInvoice']        = Ext.getCmp( 'idInvoice' + module ).getValue();

            Ext.Ajax.request( {
                url			: route + 'generatePDF'
                ,method		:'post'
                ,params		: par
                ,success	: function(response, action){
                    if( isGae == 1 ){
						window.open(route+'viewPDF/Adjustment Form','_blank');
					}else{
						window.open('pdf/accounting/Adjustment Form.pdf');
					}
                }
			} );
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
                idAffiliate = config.idAffiliate;
                canSave     = config.canSave;
                canEdit     = config.canEdit;
                selRec      = config.selRec;
                componentCalling    = config.componentCalling;
                
				return _mainPanel( config );
			}
		}
    }
}