/**
 * Developer    : Marie Danie
 * Module       : Adjustment
 * Continued by : Jays
 * Date         : Jan. 23, 2020
 * Finished     : 
 * Description  : This module allows authorized users to record an item adjustment in inventory.
 * DB Tables    : 
 * */ 
function Adjustments(){
    return function(){
        var baseurl, route, module, canDelete, pageTitle, idModule, isGae,isSaved = 0,deletedItems = [],selectedItem = [], idAffiliate, selRec , componentCalling
            ,canSave ,isPending=1;

        function _mainPanel( config ){
console.log(typeof Ext.getCmp('mainFormID' + module));
			// CHECKS IF THE MAIN PANEL IS ALREADY RENDERED
			if (typeof Ext.getCmp('mainFormID' + module) != 'undefined' ) {

				if(selRec != null) _editRecord(selRec);
				return false;
			}

            return standards2.callFunction( '_mainPanelTransactions', {
                config              : config
                ,moduleType         : 'form'
                ,hasApproved        : true
                ,id                 : 'mainFormID' + module
                ,transactionHandler :   _transactionHandler
                ,tbar               : {
                    saveFunc            : _saveForm
                    ,resetFunc          : _resetForm
                    ,formPDFHandler     : _printPDF
                    ,hasFormPDF         : true
                    ,filter : {
                        searchURL       : route + 'getAdjustmentRef'
                        ,emptyText      : 'Search reference here...'
                    }
                }
                ,formItems          : [
                    {   xtype       : 'container'
                        ,layout     : 'column'
                        ,width      : 1000
                        ,padding    : 10
                        ,items      : [
                            {   xtype   : 'hiddenfield'
                                ,id     : 'idInvoice' + module
                                ,value  : 0
                            }
                            ,{   xtype           : 'container'
                                ,columnWidth    : .5
                                ,items          : [
                                    standards2.callFunction( '_transactionReference', {
                                        module          : module
                                        ,idModule       : idModule
                                        ,idAffiliate    : Ext.getConstant('AFFILIATEID')
                                        ,style          : 'margin-bottom : 5px;'
                                    } )
                                    ,standards.callFunction( '_createDateTime', {
                                        dId             : 'tdate' + module
                                        ,tId            : 'ttime' + module
                                        ,dFieldLabel    : 'Date'
                                        ,tstyle         : 'margin-left: 5px;'
                                        ,tWidth         : 105
                                    } )
                                ]
                            }
                            ,{  xtype           : 'container'
                                ,columnWidth    : .5
                                ,items          : [
                                    standards.callFunction( '_createTextArea', {
                                        id          : 'remarks' + module
                                        ,fieldLabel : 'Remarks'
                                        ,allowBlank : true
                                    } )
                                ]
                            }
                        ]
                    }
                    ,{  xtype       : 'container'
                        ,layout     : 'fit'
                        ,style      : 'margin-bottom : 10px;'
                        ,items      : _gridAdjustment()
                    }
                    ,{
                        xtype : 'tabpanel'
                        ,items: [
                            {
                                title: 'Journal Entries'
                                ,layout:{
                                    type: 'card'
                                }
                                ,items  :   [
                                    standards.callFunction( '_gridJournalEntry',{
                                        module	        : module
                                        ,hasPrintOption : 1
                                        ,config         : config
                                        ,items          : Ext.getCmp('grdAdjustment' + module)
                                        ,listeners      : {
                                            beforeedit  : function( me ){

                                            }
                                        }
                                    })
                                ]
                            }
                        ]

                    }
                ]
                ,listItems  : _gridHistory()
                ,listeners  : {
                    afterrender : function(){
                        if ( selRec ) {
                            _editRecord( selRec )
                        }
                    }
                }
            } )
        }

        function _gridHistory(){
            var poItems = standards.callFunction( '_createRemoteStore', {
				fields		: [ 
                    'idInvoice'
					,'date'
					,'reference'
					,'remarks'
					,'preparedByName'
					,'notedbyName'
                    ,'statusText'
				]
				,url		: route + 'viewAll'
				,autoLoad	: true
			} )

			return standards.callFunction('_gridPanel', {
                id 					: 'gridHistory' + module
                ,module     		: module
                ,store      		: poItems
				,height     		: 265
				,noDefaultRow 		: true
                ,columns: [
                    {	header          : 'Date'
                        ,dataIndex      : 'date'
                        ,xtype          : 'datecolumn'
                        ,format         : 'm/d/Y'
                        ,minWidth       : 150
                        ,columnWidth    : 10
					}
					,{	header          : 'Reference'
                        ,dataIndex      : 'reference'
                        ,flex           : 1
                        ,minWidth       : 80
                        ,columnWidth    : 10
					}
					,{	header          : 'Remarks'
                        ,dataIndex      : 'remarks'
                        ,flex           : 1
                        ,minWidth       : 80
                        ,columnWidth    : 30
					}
					,{	header          : 'Prepared By'
                        ,dataIndex      : 'preparedByName'
                        ,flex           : 1
                        ,minWidth       : 80
                        ,columnWidth    : 15
					}
					,{	header          : 'Noted By'
                        ,dataIndex      : 'notedbyName'
                        ,flex           : 1
                        ,minWidth       : 80
                        ,columnWidth    : 15
					}
					,{	header          : 'Status'
                        ,dataIndex      : 'statusText'
                        ,width          : 100
                        ,columnWidth    : 10
					}
					,standards.callFunction( '_createActionColumn', {
                        canEdit     : canEdit
                        ,icon       : 'pencil'
						,tooltip    : 'Edit'
                        ,width      : 30
                        ,Func       : _editRecord
                    } )
				]
				,listeners  : {
                    afterrender : function(){
                        poItems.load( { } )
                    }
                }
			} );
        }

        function _gridAdjustment(){
            var store   = standards.callFunction( '_createRemoteStore', {
                fields  : [
                    {	name    : 'idItem'
                        ,type   : 'number'
                    }
                    ,'barcode'
                    ,'itemName'
                    ,'className'
                    ,'unitName'
                    ,'expiryDate'
                    ,{  name    : 'qty'
                        ,type   : 'number'
                    }
                    ,{  name    : 'actualQty'
                        ,type   : 'number'
                    }
                    ,{  name    : 'cost'
                        ,type   : 'number'
                    }
                    ,{  name    : 'shortQty'
                        ,type   : 'number'
                    }
                    ,{  name    : 'overQty'
                        ,type   : 'number'
                    }
                ]
                ,url    : route + 'getAdjustment'
            } )
            return standards.callFunction( '_gridPanel', {
                id              : 'grdAdjustment' + module
                ,module         : module
                ,height         : 250
                ,store          : store
                ,noPage         : true
                ,plugins        : true
                ,tbar           : {
                    content         : 'add'
                    ,canPrint       : false
                    ,noExcel        : true
                    ,route          : route
                    ,pageTitle      : pageTitle
                    ,deleteRowFunc  : _deleteRowItem
                    ,extraTbar2     : [
                        __searchbar('columnsAdj', '', 'barcode', true, true)
                        ,__qty('columnsAdjItem')
                    ]
                }
                ,columns        : [
                    {	
                        header          : 'Code'
                        ,dataIndex      : 'barcode'
                        ,width          : 100
                        ,editor         : __searchbar( 'grdItemCode', '', 'barcode', false, false )
                    }
                    ,{	
                        header          : 'Item Name'
                        ,minWidth       : 150
                        ,flex           : 1
                        ,dataIndex      : 'itemName'
                        ,editor         : __searchbar( 'grdItemCodeName', '', 'itemName', false, false )
                    }
                    ,{	
                        header          : 'Unit'
                        ,width          : 100
                        ,dataIndex      : 'unitName'
                    }
                    ,{	
                        header          : 'Classification'
                        ,dataIndex      : 'className'
                        ,width          : 100
                    }
                    ,{	
                        header          : 'Expiry Date'
                        ,dataIndex      : 'expiryDate'
                        ,width          : 100
                        ,xtype          : 'datecolumn'
                        ,format         : 'm/d/Y'
                    }
                    ,{	
                        header          : 'Balance Qty'
                        ,dataIndex      : 'qty'
                        ,width          : 100
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000'
                    }
                    ,{	
                        header          : 'Actual Qty'
                        ,width          : 100
                        ,editor         : 'number'
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000'
                        ,dataIndex      : 'actualQty'
                    }
                    ,{	
                        header          : 'Cost'
                        ,width          : 100
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,editor         : 'number'
                        ,dataIndex      : 'cost'
                    }
                    ,{	
                        text          : 'Variance'
                        ,columns      :[
                            {
                                header          : 'Short'
                                ,width          : 75
                                ,xtype          : 'numbercolumn'
                                ,format         : '0,000'
                                ,dataIndex      : 'shortQty'
                            }
                            ,{
                                header          : 'Over'
                                ,width          : 75
                                ,xtype          : 'numbercolumn'
                                ,format         : '0,000'
                                ,dataIndex      : 'overQty'
                            }
                        ]
                    }
                ]
                ,listeners  : {
                    edit    : function( me, row ){
                        if( row.field == 'barcode' || row.field == 'itemName' ){
                            if( row.record.get( 'barcode' ) == null || row.record.get( 'barcode' ).length < 1 || row.record.get( 'itemName' ) == null || row.record.get( 'itemName' ).length < 1 ){
                                var  {0 : store} = Ext.getCmp( 'grdAdjustment' + module ).selModel.getSelection()
                                store.set( row.field, null )
                            }
                        }
                        if( row.field == 'actualQty' ){
                            var variance = ( row.record.get( 'qty' ) - row.record.get( 'actualQty' ) );
                            
                            if( variance > 0 ){
                                row.record.set( 'shortQty', variance );
                                row.record.set( 'overQty', 0 );
                            }
                            else if( variance < 0 ){
                                row.record.set( 'shortQty', 0 );
                                row.record.set( 'overQty', ( variance * -1 ) );
                            }
                            else{
                                row.record.set( 'shortQty', 0 );
                                row.record.set( 'overQty', 0 );
                            }
                        }
                    }
                    ,beforeedit : function( me ){
                        if( isPending != 1 ){
                            return false;
                        }
                    }
                }
            } )
        }
        
        function _saveForm( form ){
            var itemAdjusted    = new Array()
                ,journalEntry   = new Array()
                ,grdItems       = Ext.getCmp( 'grdAdjustment' + module ).getStore().getRange()
                ,gridJournal    = Ext.getCmp( 'gridJournalEntry' + module ).getStore().getRange()
                ,totalDebit     = 0
                ,totalCredit    = 0
                ,errGridInput   = 0;
            grdItems.forEach( function( data, index ){
                if( parseInt( data.get( 'idItem' ), 10 ) > 0 ){
                    itemAdjusted.push( {
                        idItem      : data.get( 'idItem' )
                        ,qtyBal     : data.get( 'qty' )
                        ,qtyActual  : data.get( 'actualQty' )
                        ,short      : data.get( 'shortQty' )
                        ,over       : data.get( 'overQty' )
                        ,cost       : data.get( 'cost' )
                        ,itemName   : data.get( 'itemName' )
                        ,expiryDate : data.get( 'expiryDate' )
                    } )
                }
                else if( data.get( 'shortQty' ) == 0 && data.get( 'overQty' ) == 0 ){
                    errGridInput++;
                }
            } );
            gridJournal.forEach( function( data, index ){
                if( data.get( 'idCoa' ) && ( parseFloat( data.get( 'debit' ) ) > 0 || parseFloat( data.get( 'credit' ) ) > 0 ) ){
                    journalEntry.push( {
                        idCoa           : data.get( 'idCoa' )
                        ,debit          : data.get( 'debit' )
                        ,credit         : data.get( 'credit' )
                        ,explanation    : data.get( 'explanation' )
                    } );
                    totalDebit += parseFloat( data.get( 'debit' ) );
                    totalCredit += parseFloat( data.get( 'credit' ) );
                }
            } );
            if( grdItems.length <= 0 ){
                standards.callFunction( '_createMessageBox', {
                    msg     : 'Transaction must have at least one item on the grid to be saved.'
                } );
                return false;
            }
            if( gridJournal.length <= 0 ){
                standards.callFunction( '_createMessageBox', {
                    msg     : 'This transaction requires a journal entry record.'
                } );
                return false;
            }
            if( totalDebit != totalCredit ){
                standards.callFunction( '_createMessageBox', {
                    msg     : 'Total debit and total credit of recorded journal entry must be equal.'
                } );
                return false;
            }
            if( errGridInput > 0 ){
                standards.callFunction( '_createMessageBox', {
                    msg     : 'There are items added in the grid where no adjustment was made, please make sure that over or short columns has values greater than zero.'
                } );
                return false;
            }

            form.submit( {
                url         : route + 'saveAdjustment'
                ,params     : {
                    itemAdjusted    : Ext.encode( itemAdjusted )
                    ,journalEntry   : Ext.encode( journalEntry )
                    ,idModule       : idModule
                    ,date           : Ext.Date.format( Ext.getCmp( 'tdate' + module ).getValue(), 'Y-m-d' ) + ' ' + Ext.Date.format( Ext.getCmp( 'ttime' + module ).getValue(), 'H:i:s' )
                }
                ,success    : function( action, response ){
                    var resp    = Ext.decode( response.response.responseText )
                        ,match  = parseInt( resp.match, 10 );
                    switch( match ){
                        case 1: /* check if reference already exists */
                            standards.callFunction( '_createMessageBox', {
                                msg     : 'Reference number already exists. System will generate new reference number.'
                                ,fn     : function(){
                                    _generateRefNum();
                                }
                            } );
                            break;
                        case 0: /* saving successful */
                            standards.callFunction( '_createMessageBox', {
                                msg     : 'SAVE_SUCCESS'
                                ,fn     : function(){
                                    _resetForm();
                                }
                            } )
                            break;
                    }
                }
            } )
        }

        function _generateRefNum(){
            Ext.Ajax.request( { 
                url     : Ext.getConstant( 'STANDARD_ROUTE2' ) + 'getReferenceNum'
                ,msg    : 'Retrieving reference number, Please wait...'
                ,params : {
                    idReference : Ext.getCmp( 'idReference' + module ).getValue()
                    ,idmodule   : idModule
                }
                ,success    : function( response ){
                    var resp = Ext.decode( response.responseText )

                    if( typeof resp.view == 'number' ){
                        standards.callFunction( '_createMessageBox', { 
                            msg : 'No reference series was created for this Affiliate.' 
                            ,fn : function() {
                                me.reset();
                            }
                        } )
                    }
                    else{
                        Ext.getCmp( 'referenceNum' + params.module ).setValue( resp.view.refnum );
                        Ext.getCmp( 'idReferenceSeries' + params.module ).setValue( resp.view.idRef );
                    }
                   
                }
            } );
        }

        function _resetForm(){
            isPending = 1
            module.getForm().reset();
            Ext.getCmp( 'grdAdjustment' + module ).store.proxy.extraParams.idInvoice = 0;
            Ext.getCmp( 'grdAdjustment' + module ).store.load( {} );
            Ext.getCmp( 'gridJournalEntry' + module ).store.proxy.extraParams.idInvoice = 0;
            Ext.getCmp( 'gridJournalEntry' + module ).store.load( {} );
            Ext.getCmp( 'saveButton' + module ).setVisible( canSave );

            //Transaction
			Ext.getCmp('approveTransButton' + module).setVisible( false );
            Ext.getCmp('cancelTransButton' + module).setVisible( false );
            document.getElementById( 'transactionStatus' + module ).innerHTML = '<span style="color:red; font-weight: bold;">Not Yet Confirmed</span>';
            
            
            Ext.getCmp( 'idReference' + module ).setReadOnly( false );
            Ext.getCmp( 'referenceNum' + module ).setReadOnly( false );
            Ext.getCmp( 'tdate' + module ).setReadOnly( false );
            Ext.getCmp( 'ttime' + module ).setReadOnly( false );
            Ext.getCmp( 'remarks' + module ).setReadOnly( false );
            Ext.getCmp( 'addButton_grdAdjustment' + module ).setDisabled( false );
            Ext.getCmp( 'columnsAdj' + module ).setDisabled( false );
            Ext.getCmp( 'columnsAdjItem' + module ).setReadOnly( false );
            Ext.getCmp( 'addButton_gridJournalEntry' + module ).setDisabled( false );
            Ext.getCmp( 'defaultAccountgridJournalEntry' + module ).setDisabled( false );
        }

        function _printPDF(){
            var par  = standards.callFunction('getFormDetailsAsObject',{ module : module });
            
            par['hasJournalEntry']  = Ext.getCmp('printStatusJEgridJournalEntry' + module).getValue();
            par['idInvoice']        = Ext.getCmp( 'idInvoice' + module ).getValue();

			Ext.Ajax.request({
                url			: route + 'generatePDF'
                ,method		:'post'
                ,params		: par
                ,success	: function(response, action){
                    if( isGae == 1 ){
						window.open(route+'viewPDF/Adjustment Form','_blank');
					}else{
						window.open('pdf/inventory/Adjustment Form.pdf');
					}
                }
			});
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
                                    _resetForm();
                                }
                            } )
                            break;
                        case 1:
                            standards.callFunction( '_createMessageBox', {
                                msg : 'EDIT_UNABLE'
                                ,fn : function(){
                                    _resetForm();
                                }
                            } )
                            break;
                        case 2:
                            standards.callFunction( '_createMessageBox', {
                                msg : 'Transaction status has been modified by other users.'
                                ,fn : function(){
                                    _resetForm();
                                }
                            } )
                            break;
                    }
                }
            } )
        }

        function __searchbar(name, fieldname, displayField, triggerHidden = true, forceSelection = true){
            var items   = standards.callFunction( '_createRemoteStore', {
				fields      : [
                    {	name	: 'idItem'
                        ,type	: 'number'
                    }
                    ,'barcode'
					,'itemName'
                    ,'className'
                    ,'unitName'
                    ,'expiryDate'
                    ,{  name    : 'cost'
                        ,type   : 'number'
                    }
                    ,{  name    : 'qtyLeft'
                        ,type   : 'number'
                    }
                ]
				,url        : route + 'getItems'
            } );

            return standards2.callFunction( '_createItemCombo', {
                id				: name + module
                ,module			: module
                ,store          : items
                ,allowBlank		: true
                ,forceSelection : false
                ,width			: 300
                ,displayField   : displayField || 'barcode'
                ,valueField     : displayField || 'idItem'
                ,emptyText		: 'Search ' + ( displayField == 'itemName'? 'item name' : displayField ) + '...'
                ,hideTrigger	: triggerHidden
                ,labelWidth     : 100
                ,listeners		: {
                    beforeQuery	: function( me ){
                        items.proxy.extraParams.idAffiliate = Ext.getConstant('AFFILIATEID');
                        items.proxy.extraParams.field       = displayField;
                        items.proxy.extraParams.date        = Ext.getCmp( 'tdate' + module ).getValue();
                        items.proxy.extraParams.time        = Ext.getCmp( 'ttime' + module ).getValue();
                        items.load({});
                        delete me.combo.lastQuery;
                    }
                    ,focus      : function(){
                        items.proxy.extraParams.idAffiliate = Ext.getConstant('AFFILIATEID');
                        items.proxy.extraParams.field       = displayField;
                        items.proxy.extraParams.date        = Ext.getCmp( 'tdate' + module ).getValue();
                        items.proxy.extraParams.time        = Ext.getCmp( 'ttime' + module ).getValue();
                        items.load({});
                    }
                    ,blur			: function( me ){
                        var record		= me.findRecord( displayField, me.getValue() )
                            ,index		= me.store.indexOf( record );
                        if( index < 0 ){
                            me.reset();
                            var  {0 : store} = Ext.getCmp( 'grdAdjustment' + module ).selModel.getSelection()
                            if( store ){
                                store.set( 'idItem', null );
                                store.set( 'barcode', null );
                                store.set( 'itemName', null );
                                store.set( 'className', null );
                                store.set( 'unitName', null );
                                store.set( 'qty', 0 );
                                store.set( 'actualQty', 1 );
                                store.set( 'cost', 0.00 );
                                store.set( 'shortQty', 0 );
                                store.set( 'overQty', 1 );
                            }
                        }
                    }
                    ,select     : function( me, record ){
                        var qty         = record[0].get('qtyLeft')
                            ,grd        = Ext.getCmp( 'grdAdjustment' + module )
                            ,grdStore   = grd.getStore()
                            ,expiryDate = record[0].get( 'expiryDate' )
                            ,variance   = ( qty - 1 );
                        if( Ext.isUnique( me.valueField, grdStore, me ) ){
                            if( name != 'columnsAdj' ){
                                if( displayField == 'barcode' ) grd.selModel.getSelection()[0].set( 'itemName', record[0].get( 'itemName' ) );
                                else if( displayField == 'itemName' ) grd.selModel.getSelection()[0].set( 'barcode', record[0].get( 'barcode' ) );
                                grd.selModel.getSelection()[0].set( 'idItem', record[0].get( 'idItem' ) );
                                grd.selModel.getSelection()[0].set( 'className', record[0].get( 'className' ) );
                                grd.selModel.getSelection()[0].set( 'unitName', record[0].get( 'unitName' ) );
                                grd.selModel.getSelection()[0].set( 'cost', record[0].get( 'cost' ) );
                                grd.selModel.getSelection()[0].set( 'expiryDate', expiryDate );
                                grd.selModel.getSelection()[0].set( 'qty', qty );
                                grd.selModel.getSelection()[0].set( 'actualQty', 1 );
                                grd.selModel.getSelection()[0].set( 'shortQty', ( variance > 0? variance : 0 ) );
                                grd.selModel.getSelection()[0].set( 'overQty', ( variance < 0? ( variance * -1 ) : 0 ) );
                            }
                            else{
                                if( grd.getStore().getCount() == 1 ){
                                    if( parseInt( grd.getStore().getRange()[0].get( 'idItem' ) ) <= 0 ){
                                        grd.store.removeAll();
                                    }
                                }
                                grd.store.add( {
                                    idItem      : record[0].get( 'idItem' )
                                    ,barcode    : record[0].get( 'barcode' )
                                    ,itemName   : record[0].get( 'itemName' )
                                    ,className  : record[0].get( 'className' )
                                    ,unitName   : record[0].get( 'unitName' )
                                    ,cost       : record[0].get( 'cost' )
                                    ,expiryDate : expiryDate
                                    ,qty        : qty
                                    ,actualQty  : 1
                                    ,shortQty   : ( variance > 0? variance : 0 )
                                    ,overQty    : ( variance < 0? ( variance * -1 ) : 0 )
                                } )
                                me.reset();
                                Ext.getCmp( 'columnsAdjItem' + module ).setValue( 1 );
                            }
                        }
                        else{
                            if( name != 'columnsAdj' ){
                                me.reset();
                                grd.selModel.getSelection()[0].set( 'idItem', null );
                                grd.selModel.getSelection()[0].set( 'className', null );
                                grd.selModel.getSelection()[0].set( 'unitName', null );
                                grd.selModel.getSelection()[0].set( 'cost', 0 );
                                grd.selModel.getSelection()[0].set( 'expiryDate', null );
                                grd.selModel.getSelection()[0].set( 'qty', 0 );
                                grd.selModel.getSelection()[0].set( 'actualQty', 0 );
                                grd.selModel.getSelection()[0].set( 'shortQty', 0 );
                                grd.selModel.getSelection()[0].set( 'overQty', 0 );
                            }
                        }
                    }
                }
            } )
        }

        function __qty(name){
            return standards.callFunction('_createNumberField',{
                id			: name + module
                ,module		: module
                ,fieldLabel	: ''
                ,allowBlank	: true
                ,width		: 75
                ,value		: 1
            })
        }
        
        function _editRecord( data ){
            module.getForm().retrieveData( {
                url		: route + 'retrieveData'
                ,params	: {
                    idInvoice : data.idInvoice
                }
                ,excludes: [ 'pCode' ]
                ,hasFormPDF	: true
                ,success : function( data ){
                    /** 
                    **	0 = ok
                    **	1 = record not found
                    **	2 = record used -- could be used if record is tag as locked
                    **/
                    standards2.callFunction( '_setTransaction', {
                        data    : data
                        ,module : module
                    } );
                    
                    isPending = parseInt( data.status )

                    Ext.getCmp( 'saveButton' + module ).setVisible( false );
                    Ext.getCmp( 'grdAdjustment' + module ).store.proxy.extraParams.idInvoice = data.idInvoice;
                    Ext.getCmp( 'grdAdjustment' + module ).store.load({});
                    Ext.getCmp( 'gridJournalEntry' + module ).store.proxy.extraParams.idInvoice = data.idInvoice;
                    Ext.getCmp( 'gridJournalEntry' + module ).store.load( {} );
                    Ext.getCmp( 'idReference' + module ).setReadOnly( true );
                    Ext.getCmp( 'referenceNum' + module ).setReadOnly( true );
                    Ext.getCmp( 'tdate' + module ).setReadOnly( true );
                    Ext.getCmp( 'ttime' + module ).setReadOnly( true );
                    Ext.getCmp( 'remarks' + module ).setReadOnly( true );
                    Ext.getCmp( 'addButton_grdAdjustment' + module ).setDisabled( true );
                    Ext.getCmp( 'columnsAdj' + module ).setDisabled( true );
                    Ext.getCmp( 'columnsAdjItem' + module ).setReadOnly( true );
                    Ext.getCmp( 'addButton_gridJournalEntry' + module ).setDisabled( true );
                    Ext.getCmp( 'defaultAccountgridJournalEntry' + module ).setDisabled( true );
                }
            } );
        }

        function _deleteRowItem( data, row ){
            standards.callFunction( '_createMessageBox', {
                msg     : 'Are you sure you want to delete this record?'
                ,action : 'confirm'
                ,fn     : function( btn ){
                    if(  btn == 'yes' ){
                        Ext.getCmp( 'grdAdjustment' + module ).store.removeAt( row );
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
                idAffiliate = config.idAffiliate;
                canSave     = config.canSave;
                selRec      = config.selRec;
                componentCalling    = config.componentCalling;
				
				return _mainPanel( config );
			}
		}
    }
}