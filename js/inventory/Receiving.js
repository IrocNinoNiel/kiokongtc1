/**
 * Developer: Hazel Alegbeleye
 * Module: Receiving
 * Date: 
 * Finished: 
 * Description: 
 * DB Tables: 
 * */ 
function Receiving(){
    return function(){
        var baseurl, route, module, canDelete, canCancel, pageTitle, idModule, isGae, isSaved = 0, idAffiliate, canPrint, canEdit, dataHolder, onEdit = 0, selRec, componentCalling;

		function _transactionHandler( status ){
			standards.callFunction( '_createMessageBox', {
				msg		: 'Are you sure you want to change the status of this Receiving?'
				,action	: 'confirm'
				,fn		: function( btn ){
					if( btn == 'yes' ){
						_changeTransactionStatus( status );
					}
				}
			} );
		}

		function _changeTransactionStatus( status ) {
            var gridItems = Ext.getCmp('grdItems'+module).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0);

			Ext.Ajax.request({
				url : Ext.getConstant( 'STANDARD_ROUTE2' ) + 'updateTransactionStatus'
				,params : {
					status 		    : status
					,idInvoice 	    : dataHolder.idInvoice
                    ,checkOnTable   : 'releasing'
                    ,tableName      : 'receiving'
                    ,items          : Ext.encode( gridItems )
				}
				,success : function( response ){

					var stats = ( status == 2 ) ? 'Approved' : 'Cancelled', resp = Ext.decode( response.responseText );

						if( resp.match == 1 ) {
							standards.callFunction('_createMessageBox',{ msg: 'EDIT_USED' });
						} else {
							standards.callFunction('_createMessageBox',{ 
								msg: 'Receiving has been ' + stats
								,fn : function() {
                                    standards2.callFunction('_setTransaction', { module	: module ,data : { status : status }});
                                    // if( status == 3 ) _updateQtyLeft( gridItems, dataHolder );
								}
							});
						}
				}
			});
        }
        
        function _updateQtyLeft( gridItems, data ) {

            var params = {
                items	    : Ext.encode( gridItems )
                ,fident     : data.fident
                ,idInvoice  : data.idInvoice
                ,status     : 3
            };

            Ext.Ajax.request({
                url: route + 'updateQty'
                ,params : params
                ,success: function( response ) {
                    Ext.getCmp( 'grdItems' + module ).getStore().proxy.extraParams.idSupplier = dataHolder.idInvoice;
                    Ext.getCmp( 'grdItems' + module ).getStore().load({});
                }
            });
        }


        function _mainPanel( config ){
            return standards2.callFunction(	'_mainPanelTransactions' ,{
                config						: config
                ,hasApproved				: false
                ,module                     : module
                ,hasCancelTransaction       : canCancel
				,tbar       : {
					saveFunc        		: _saveForm
					,resetFunc      		: _resetForm
                    ,customListExcelHandler	: _printExcel
                    ,customListPDFHandler   : _customListPDF
					,formPDFHandler			: _printPDF
					,hasFormPDF     		: true
					,hasFormExcel			: false
					,filter	:{
						searchURL           : route + 'viewAll'
                        ,emptyText          : 'Search reference number here...'
                        ,module             : module
					}
                }
                ,formItems  :[
					standards2.callFunction( '_transactionHeader', {
						module			: module
						,containerWidth	: 1000
						,idModule		: idModule
						,idAffiliate	: idAffiliate
						,config			: config
						,moduleCompareFrom	:{
							date: 'dueDate'+module
                        }
                    } )
                    ,_formItems()
                    ,_transactionGrid( config )
                ]
                ,listItems  : [ _gridHistory() ]
                ,listeners  : {
                    afterrender : function () {
                        if ( selRec ) {
                            _editRecord( { id: selRec.idInvoice } );
                        }
                    }
                }
            } );
        }

        function _formItems() {
            var poStore = standards.callFunction( '_createRemoteStore', {
				fields		: [ { name: 'idInvoice', type: 'number' }, 'name', 'pCode', 'downPayment' ]
				,url		: route + 'getPO'
				,autoLoad	: true
            } )
            ,paymentStore = standards.callFunction( '_createLocalStore', {
                data        : [ 'Cash', 'Charge' ]
                ,startAt    : 1
            } )
            ,supplierStore = standards.callFunction( '_createRemoteStore', {
				fields		: [ { name: 'id', type: 'number' }, 'name' ]
				,url		: route + 'getSupplier'
				,autoLoad	: true
            } )
            ,termStore = standards.callFunction( '_createLocalStore', {
				data        : [ '30 Days', '60 Days', '90 Days', '120 Days',  ]
				,startAt    : 1
            });

            return {	
                xtype		: 'fieldset'
                ,layout		: 'column'
                // ,width		: 1000
                ,padding	: 10
                ,items		: [
                    ,{	xtype			: 'container'
                        ,columnWidth	: .5
                        ,items			: [
                            ,standards.callFunction( '_createCheckField', {
                                id              : 'withPO' + module
                                ,fieldLabel     : 'Receive with PO'
                                ,listeners:{
                                    change: function( checkbox, newValue, oldValue, eOpts ){
                                        var poNumber = Ext.getCmp( 'poNumber' + module );
                                        _validateField('poNumber', !newValue );
                                        poNumber.setDisabled(!newValue);
                                        
                                        if( !newValue ) poNumber.reset();
                                    }	
                                }
                            } )
                            ,standards.callFunction( '_createCombo', {
                                id              : 'poNumber' + module
                                ,fieldLabel     : 'PO Number'
                                ,store          : poStore
                                ,displayField   : 'name'
                                ,valueField     : 'idInvoice'
                                ,listeners      : {
                                    beforeQuery : function() {
                                        var transactionDate     = Ext.getCmp( 'tdate' + module )
                                            ,costcenter         = Ext.getCmp( 'idCostCenter' + module )
                                            ,supplier           = Ext.getCmp(  'pCode' + module )
                                            
                                        if( typeof transactionDate != 'undefined' && typeof costcenter != 'undefined'){
                                            let params = { 
                                                idAffiliate     : idAffiliate
                                                ,date           : transactionDate.getValue()
                                                ,idCostCenter   : costcenter.getValue()
                                                ,onEdit         : onEdit
                                            };

                                            if( typeof supplier != 'undefined' ) {
                                                if( supplier.getValue() !== '' && supplier.getValue() !== null ) params['pCode'] = supplier.getValue();
                                            }
                                            
                                            _setExtraParams( 
                                                this.getStore()
                                                ,params
                                                ,'No PO found for this Supplier.'
                                            );
                                        }
                                    }
                                    ,select  : function( ) {
                                        var gridItems = Ext.getCmp( 'grdItems' + module );
                                        var record = this.findRecord( this.valueField, this.getValue() );
                                        // if( onEdit == 0 ) _setExtraParams( gridItems.getStore(), { idInvoice : record.data.idInvoice});
                                        _getItems( record.raw, 1 );
                                        _setTotal( record.raw ); // PO DETAILS
                                    }
                                    ,afterrender: function( me ) {
                                        me.setDisabled(true);
                                        me.allowBlank = true;
                                    }
                                    ,change : function(){
                                        if( this.getValue() != '') _setReadOnly( 'pCode', true );
                                    }
                                }
                            })
                            ,standards.callFunction( '_createSupplierCombo', {
                                module		: module
                                ,store      : supplierStore
                                ,fieldLabel : 'Supplier'
                                ,listeners	: {
                                    beforeQuery :  function() {
                                        let supplierStore   = this.getStore()
                                            ,msg            = 'No supplier was added for this Affiliate.';

                                        _setExtraParams( supplierStore, { idAffiliate : idAffiliate }, msg );

                                    }
                                    ,select : function(){
                                        if( this.getValue() != null ) {
                                            let record = this.findRecord(this.valueField, this.getValue());
                                            _getItems( record.raw, 0 );
                                        }
                                    }
                                }
                            } )
                            ,standards.callFunction( '_createCombo', {
                                id              : 'paymentType' + module
                                ,fieldLabel     : 'Payment Type'
                                ,allowBlank     : false
                                ,store          : paymentStore
                                ,displayField   : 'name'
                                ,valueField     : 'id'
                                ,listeners      : {
                                    select : function(){
                                        let value = ( this.getValue() == 2 ) ? false : true;
                                        _validateField('dueDate', value);
                                        Ext.getCmp('terms' + module ).reset();
                                        _validateField('terms', value);
                                    }
                                }
                            } )
                            ,standards.callFunction( '_createTextField', {
                                id              : 'terms' + module
                                ,fieldLabel     : 'Terms'
                                ,isNumber		: true
                                ,isDecimal		: false
                                ,maskRe         : /[^a-z,A-Z]/
                                ,value		    : 0
                                ,listeners		: {
                                    afterrender : function( ) {
                                        if( Ext.getCmp('paymentType'+module).getValue() != 2 ) {
                                            this.setDisabled(true);
                                        }
                                    }
                                    ,blur: function(){
                                        var transactionDate = Ext.getCmp('tdate'+module).getValue();
                                        if( parseInt(this.getValue(), 10) > 0 ){
                                            let dueDate = Ext.toMoment(transactionDate).add( parseInt(this.getValue(), 10),'days');
                                            Ext.getCmp('dueDate'+module).setMinValue(  dueDate.toDate() );
                                            Ext.getCmp('dueDate'+module).setValue(  dueDate.toDate() );
                                        }
                                    }
                                }
                            } )
                            // ,standards.callFunction( '_createCombo', {
                            //     id              : 'terms' + module
                            //     ,fieldLabel     : 'Terms'
                            //     ,store          : termStore
                            //     ,displayField   : 'name'
                            //     ,valueField     : 'id'
                            //     ,listeners      : {
                            //         change : function(){
                            //             let dueDate = Ext.getCmp('dueDate' + module )
                            //             ,days = ( this.getValue() == 1 ) ? 30 : ( this.getValue() == 2 ) ? 60 : ( this.getValue() == 3 ) ? 90 : 120;
                            //             dueDate.setValue( Ext.date().add(days, 'day').toDate() );
                            //         }
                            //     }
                            // } )
                            ,standards.callFunction( '_createTextArea', {
                                id			: 'remarks' + module
                                ,fieldLabel	: 'Remarks'
                                ,allowBlank	: true
                            } )
                            
                        ]
                    }
                    ,{	xtype			: 'container'
                        ,columnWidth	: .5
                        ,items			: [
                            standards.callFunction( '_createDateField', {
                                id			: 'dueDate' + module
                                ,fieldLabel	: 'Due Date'
                                ,allowBlank	: true
                            } )
                            ,standards.callFunction( '_createTextField', {
                                id          : 'creditLimit' + module
                                ,fieldLabel : 'Credit Limit'
                                ,allowBlank : true
                                ,readOnly	: true
                                ,maxLength  : 50
                                ,isNumber   : true
                                ,isDecimal  : true
                            } )	
                            ,standards.callFunction( '_createTextField', {
                                id          : 'apBalance' + module
                                ,fieldLabel : 'AP Balance'
                                ,allowBlank : true
                                ,readOnly	: true
                                ,maxLength  : 50
                                ,isNumber   : true
                                ,isDecimal  : true
                            } )	
                            ,standards.callFunction( '_createTextField', {
                                id          : 'variance' + module
                                ,fieldLabel : 'Variance'
                                ,allowBlank : true
                                ,readOnly	: true
                                ,maxLength  : 50
                                ,isNumber  : true
                                ,fieldStyle	: 'text-align: right;'
                                // ,format     : '0,000.00'
                            } )	
                        ]
                    }
                ]
            }
        }

        function _transactionGrid( config ) {

            var receivingItems = standards.callFunction( '_createRemoteStore', {
				fields		: [ 
                    'referenceNum' 
                    , 'barcode'
                    , 'itemName'
                    , 'className'
                    , 'unitName'
                    , 'cost'
                    , 'qty'
                    , 'qtyLeft'
                    , 'idItemClass'
                    , 'expiryDate'
                    , { name: 'amount', type: 'number' }
                    , { name: 'idItem', type: 'number'}
                    , { name: 'lotNumber', type: 'number'}
                    ]
				,url		: route + 'getPOItems'
				,autoLoad	: true
            } )
            ,paymentStore = standards.callFunction( '_createLocalStore', {
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
                     'barcode'
                    , 'itemName'
                    , 'className'
                    , 'unitName'
                    , 'cost'
                    , 'qty'
                    , 'expiryDate' 
                    , { name: 'amount', type: 'number' }
                    , { name: 'idItem', type: 'number' }
                    , { name: 'lotNumber', type: 'number' }
                ]
				,url		: route + 'getItems'
				,autoLoad	: true
            } );


            return {
                xtype   : 'tabpanel'
                ,items  : [
                    {
                        title   : 'Item Details'
                        ,items  : [
                            standards.callFunction( '_gridPanel', {
                                id          : 'grdItems' + module
                                ,module     : module
                                ,store      : receivingItems
                                ,plugins    : true
                                ,noPage     : true
                                ,noDefaultRow  : true
                                ,features       : {
                                    ftype   : 'summary'
                                }
                                ,tbar       : {
                                    canPrint        : false
                                    ,noExcel        : true
                                    ,route          : route
                                    ,pageTitle      : pageTitle
                                    ,content        : 'add'
                                    // ,deleteRowFunc  : _deleteItem
                                    ,extraTbar2     : [
                                        standards.callFunction( '_createCombo', {
                                            id				: 'searchbarcode' + module
                                            ,store          : itemStore
                                            ,module			: module
                                            ,fieldLabel		: ''
                                            ,allowBlank		: true
                                            ,width			: 250
                                            ,displayField   : 'barcode'
                                            ,valueField     : 'idItem'
                                            ,emptyText		: 'Search barcode...'
                                            ,hideTrigger	: true
                                            ,listeners		: {
                                                beforeQuery	: function(){
                                                    if( idAffiliate != null ) {
                                                        _setExtraParams( 
                                                                itemStore, 
                                                                { 
                                                                    qty             : Ext.getCmp('unitnum' + module).value
                                                                    ,idAffiliate    : idAffiliate 
                                                                    ,idSupplier     : Ext.getCmp('pCode' + module).value
                                                                } );
                                                    } else {
                                                        standards.callFunction('_createMessageBox', { msg : 'Please select a Supplier a first.'});
                                                    }
                                                    
                                                }
                                                ,select: function( me ){
                                                    var row = this.findRecord(this.valueField, this.getValue())
                                                    var msg = 'The item is already selected. If you still want to select the item, you can just add quantity to the row.';
                                                    
                                                    if( Ext.isUnique(this.valueField, receivingItems, this, msg ) ) {
                                                        let unit = Ext.getCmp('unitnum' + module);
                                                        row.set('qtyLeft', 0);
                                                        row.set('qty', unit.getValue() );
                                                        row.set('referenceNum', 'None');
                                                        receivingItems.add(row);
                                                        
                                                        Ext.getCmp('searchbarcode' + module).reset();
                                                        unit.reset();
                                                    }
                                                    
                                                }
                                            }
                                        })
                                        ,standards.callFunction('_createNumberField',{
                                            id			: 'unitnum' + module
                                            ,module		: module
                                            ,fieldLabel	: ''
                                            ,allowBlank	: true
                                            ,width		: 75
                                            ,value		: 1
                                        })
                                    ]
                                }
                                ,columns    : [
                                    {	header          : 'PO Number'
                                        ,dataIndex      : 'referenceNum'
                                        ,flex           : 1
                                        ,minWidth       : 80
                                        ,sortable       : false
                                        ,editor         : standards.callFunction( '_createCombo', {
                                            id              : '_cbPONumber' + module
                                            ,fieldLabel     : ''
                                            ,allowBlank     : true
                                            ,store          : poNumberStore
                                            ,displayField   : 'name'
                                            ,valueField     : 'name'
                                            ,listeners      : {
                                                beforeQuery : function() {
                                                    var selectedPO = Ext.getCmp( 'poNumber' + module);

                                                    if( selectedPO.getValue() != null ) {
                                                        var record = selectedPO.getStore().findRecord('name', selectedPO.getDisplayValue() )
                                                        _setExtraParams( this.getStore(), record.data );
                                                    } else {
                                                        _setExtraParams( this.getStore(), {} );
                                                    }
                                                }
                                            }
                                        } )
                                    }
                                    ,{	header          : 'Code'
                                        ,dataIndex      : 'barcode'
                                        ,width          : 80
                                        ,sortable       : false
                                        ,editor         : standards.callFunction( '_createCombo', {
                                            id              : '_cbBarcode' + module
                                            ,fieldLabel     : ''
                                            ,allowBlank     : true
                                            ,store          : itemStore
                                            ,displayField   : 'barcode'
                                            ,valueField     : 'barcode'
                                            ,listeners      : {
                                                beforeQuery : function() {
                                                    if( idAffiliate != null ) {
                                                        console.log( 'asd' )
                                                        _setExtraParams( 
                                                            itemStore, 
                                                            { 
                                                                idAffiliate : idAffiliate 
                                                                ,idSupplier : Ext.getCmp('pCode' + module ).value
                                                            });
                                                    } else {
                                                        standards.callFunction('_createMessageBox', { msg : 'Please select a Supplier a first.'});
                                                    }

                                                    // this.getStore().load({});
                                                }
                                                ,select : function( me, record ) {
                                                    _setItemDetails( this, receivingItems );
                                                }
                                            }
                                        } )
                                    }
                                    ,{	header          : 'Item Name'
                                        ,dataIndex      : 'itemName'
                                        ,flex           : 1
                                        ,minWidth       : 100
                                        ,sortable       : false
                                        ,editor         : standards.callFunction( '_createCombo', {
                                            id              : '_cbItemName' + module
                                            ,fieldLabel     : ''
                                            ,allowBlank     : true
                                            ,store          : itemStore
                                            ,displayField   : 'itemName'
                                            ,valueField     : 'itemName'
                                            ,emptyText      : 'Select item name...'
                                            ,listeners      : {
                                                beforeQuery : function() {
                                                    if( idAffiliate != null ) {
                                                        _setExtraParams( 
                                                            itemStore, 
                                                            { 
                                                                idAffiliate : idAffiliate 
                                                                ,idSupplier : Ext.getCmp('pCode' + module ).value
                                                            });
                                                    } else {
                                                        standards.callFunction('_createMessageBox', { msg : 'Please select a Supplier a first.'});
                                                    }
                                                }
                                                ,select : function( me, record ) {
                                                    _setItemDetails( this, receivingItems );
                                                }
                                            }
                                        } )
                                    }
                                    ,{	header          : 'Classification'
                                        ,dataIndex      : 'className'
                                        ,width          : 120
                                        ,sortable       : false
                                    }
                                    ,{	header          : 'Unit'
                                        ,dataIndex      : 'unitName'
                                        ,width          : 100
                                        ,sortable       : false
                                    }
                                    ,{	header          : 'Cost'
                                        ,dataIndex      : 'cost'
                                        ,width          : 100
                                        ,xtype          : 'numbercolumn'
                                        ,editor         : 'float'
                                        ,sortable       : false
                                    }
                                    ,{	header          : 'Lot Number'
                                        ,dataIndex      : 'lotNumber'
                                        ,width          : 100
                                        ,columnWidth    : 10
                                        ,xtype          : 'numbercolumn'
                                        ,editor         : 'float'
                                        ,sortable       : false
                                    }
                                    ,{	header          : 'Expiry Date'
                                        ,dataIndex      : 'expiryDate'
                                        ,width          : 100
                                        ,columnWidth    : 10
                                        ,editor         : 'justNowAndForever'
                                        ,sortable       : false
                                    }
                                    ,{	header          : 'Qty Left in PO'
                                        ,dataIndex      : 'qtyLeft'
                                        ,width          : 100
                                        ,columnWidth    : 10
                                        ,xtype          : 'numbercolumn'
                                        ,sortable       : false
                                    }
                                    ,{	header          : 'Quantity'
                                        ,dataIndex      : 'qty'
                                        ,width          : 100
                                        ,columnWidth    : 10
                                        ,xtype          : 'numbercolumn'
                                        ,editor         : 'float'
                                        ,sortable       : false
                                    }
                                    ,{	header          : 'Amount'
                                        ,dataIndex      : 'amount'
                                        ,width          : 100
                                        ,columnWidth    : 10
                                        ,xtype          : 'numbercolumn'
                                        ,summaryType    : 'sum'
                                        ,sortable       : false
                                        ,summaryRenderer: function( value, summaryData, dataIndex ) {
                                            if( typeof value == 'number' && value >= 0 ) _customFireEvent( 'purchases', 'change', Ext.util.Format.number( value, '0,000.00' ) );
                                            return value;
                                        }
                                    }
                                ]
                                ,listeners	: {
                                    afterrender : function() {
                                        receivingItems.load({});
                                    }
                                    ,edit : function( me, rowData ) {
                                        var index = rowData.rowIdx
                                        ,store = this.getStore().getRange();
    
                                        var totalAmount = ( store[index].data.amount != null ) ? store[index].data.amount : 0 ;

                                        switch( rowData.field ) {
                                            case 'cost':
                                                if( rowData.value == 0 ) {
                                                    standards.callFunction('_createMessageBox', { 
                                                        msg : 'Please input a value greater than 0.'
                                                        ,fn: function(){ 
                                                            let costVal = ( rowData.originalValue == 0 ) ? 1 : rowData.originalValue;
                                                            store[index].set('cost', costVal );
                                                        }
                                                    });
                                                }
                                                _computation();
                                                totalAmount = ( store[index].data.qty > 0 ? rowData.value * store[index].data.qty : rowData.value);
                                                break;
                                            case 'qty':
                                                    /* Does not allow 0 input for Qty */

                                                    // if( rowData.value == 0 ){
                                                    //     standards.callFunction('_createMessageBox', { 
                                                    //         msg :'Please input a value greater than 0.'
                                                    //         ,fn: function(){ 
                                                    //             let qtyVal = ( rowData.originalValue == 0 ) ? 1 : rowData.originalValue;
                                                    //             store[index].set('qty', qtyVal );
                                                    //         }
                                                    //     });
                                                    // }
                                                _computation();
                                                totalAmount = store[index].data.cost * rowData.value;
                                                break;
                                        }
    
                                        store[index].set('amount', totalAmount );
                                    }
                                }
                            } )
                            ,_totalFields()
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

        function _getDiscountedAmount( totalAmt, discountPercentage ){
            return parseFloat( parseFloat( totalPurchase ) * ( parseFloat( discountPercentage ) / 100 ) );
        }

        function _getVATAmount( totalAmt, vatPercent, vatType ){
             /* Note: VAT Type [ Supplier Settings ]
                    1 is Inclusive | Current = 0 
                    2 is Exclusive | Current = 1
             */
           let VATAmount    = parseFloat( parseFloat( totalAmt ) * ( parseFloat( vatPercent ) / 100 ) )
            ,amtAfterVAT    = ( vatType != 2 ) ? totalAmt : parseFloat( parseFloat(totalAmt) + parseFloat(VATAmount) );

            return {
                    vatAmount   : VATAmount
                    ,totalAmt   : amtAfterVAT
            };
        }

        function _computation(){
            let vatType = 0, vatPercent = 0, vatAmt = 0, totalAmtAfterVAT = 0, 
                discountPercent = 0, discountAmt = 0, totalAmtDue = 0
                ,downPaymentAmt = 0, balanceAmt = 0, totalPurchases = 0;

            /* Get total field values*/
            let purchases           = Ext.getCmp( 'purchases' + module )
                ,vatAmount          = Ext.getCmp( 'vatAmount' + module )
                ,total              = Ext.getCmp( 'totalAmount' + module )
                ,totalAmountDue     = Ext.getCmp( 'totalAmountDue' + module )
                ,discountPercentage = Ext.getCmp( 'discountPercentage' + module)
                ,discountAmount     = Ext.getCmp( 'discountAmount' + module)
                ,downpayment        = Ext.getCmp( 'downPayment' + module )
                ,balance            = Ext.getCmp( 'balance' + module )


            //Get values based on the selected Supplier
            let supplier    = Ext.getCmp( 'pCode' + module )
            ,supplierRecord = supplier.findRecord( supplier.valueField, supplier.getValue() );
            
            if( typeof supplierRecord != 'undefined' ) {
                // totalPurchases = parseFloat( purchases.getValue() );

                supplierRecord = typeof supplierRecord.raw == 'undefined' ? [] : supplierRecord.raw;
                vatPercent = ( typeof supplierRecord.vatPercent != 'undefined' ) ? supplierRecord.vatPercent : vatPercent;
                vatType    = ( typeof supplierRecord.vatType != 'undefined' ) ? supplierRecord.vatType : vatType;

                //Calculate total amount after VAT
                totalPurchases          = parseFloat( purchases.getValue() )
                    ,vatAmt             = parseFloat( totalPurchases * ( parseFloat( vatPercent ) / 100 ) )
                    ,totalAmtAfterVAT   = ( vatType != 2 ) ? totalPurchases : parseFloat( totalPurchases + vatAmt );
                
                //Set values to VAT Amount + Total
                vatAmount.setValue( vatAmt );
                total.setValue( totalAmtAfterVAT );

                //Calculate total amount after Discount
                discountPercent = discountPercentage.getValue();
                discountAmt     = discountAmount.getValue();

                discountAmt = ( discountAmt == 0 && discountPercent > 0 ) ? totalAmtAfterVAT * ( discountPercent / 100 ) : discountAmt;

                totalAmtDue     = totalAmtAfterVAT - discountAmt;
                
                //Set values to Discount Fields + Total Amount Due
                discountPercentage.setValue( discountPercent );
                discountAmount.setValue( discountAmt );
                totalAmountDue.setValue( totalAmtDue );

                //Set Balance value. Note: This may change when the downpayment value will be changed.
                downPaymentAmt  = (typeof downpayment.getValue() != 'undefined' ) ? parseFloat(downpayment.getValue()) : downPaymentAmt; 
                balanceAmt      = parseFloat( totalAmtDue  - downPaymentAmt );
                balance.setValue( balanceAmt );

                //Set values to Additional Fields
                Ext.getCmp( 'ewtRate' + module ).setValue( supplierRecord.ewtRate )
                Ext.getCmp( 'vatType' + module ).setValue( vatType )
                Ext.getCmp( 'vatPercent' + module ).setValue( vatPercent )
            }
        }

        function _totalFields( ) {
            return {	
                xtype   : 'container'
                ,style  : 'float:right;padding:8px;margin-top:5px;margin-right:5px;'
                ,width  : 390
                ,items  : [
                    standards.callFunction('_createNumberField',{
                        id              : 'purchases' + module
                        ,fieldLabel     : 'Purchases'
                        ,readOnly       : true
                        ,listeners      : {
                            change : function( me ){
                                _computation();
                            }
                        }
                    })
                    ,standards.callFunction('_createNumberField',{
                        id          : 'vatAmount' + module
                        ,fieldLabel : 'VAT Amount:'
                        ,readOnly   : true
                        ,listeners      : {
                            change : function( me ){
                                _computation();
                            }
                        }
                    })
                    ,standards.callFunction('_createNumberField',{
                        id          : 'totalAmount' + module
                        ,fieldLabel : 'Total:'
                        ,readOnly   : true
                        ,listeners      : {
                            change : function( me ){
                                let dPercent = Ext.valued('discountPercentage' + module)
                                    ,dAmount    = Ext.getCmp('discountAmount' + module );

                                if( dPercent > 0 ) dAmount.setValue( me.getValue() * ( dPercent / 100 ) );
                            }
                        }
                    })
                    ,{
                        xtype       :'container'
                        ,layout     :'column'
                        ,style      : 'margin-bottom: 5px;'
                        ,items      :   [
                            ,standards.callFunction('_createTextField',{
                                id          : 'discountPercentage' + module
                                ,fieldLabel : 'Discount:'
                                ,labelWidth : 50
                                ,width      : 100
                                ,isNumber   : true
                                ,isDecimal  : true
                                ,listeners  : {
                                    blur : function( me ){
                                        let discountAmount      = Ext.getCmp('discountAmount' + module)
                                            ,total              = Ext.valued('totalAmount' + module)
                                            dAmount             = parseFloat( total *  ( this.getValue() / 100 ) );

                                        discountAmount.setValue( dAmount );
                                        _computation();
                                    }
                                }
                            })
                            ,{	xtype: 'label'
                                ,text: '%'
                            }
                            ,standards.callFunction( '_createTextField', {
                                id          : 'discountAmount' + module
                                ,style      : 'margin-left: 28px;'
                                ,labelWidth : 5
                                ,width      : 210
                                ,isNumber   : true
                                ,isDecimal  : true
                                ,listeners  : {
                                    blur : function(){
                                        let discountPercentage = Ext.getCmp('discountPercentage' + module)
                                            ,total             = Ext.valued('totalAmount' + module)
                                            dPercent           = parseFloat( this.getValue() / total ) * 100;

                                        discountPercentage.setValue( dPercent );
                                        _computation();
                                    }
                                }
                            } )
                            
                        ]
                    }
                    ,standards.callFunction('_createNumberField',{
                        id          : 'totalAmountDue' + module
                        ,fieldLabel : 'Total Amount Due:'
                        ,readOnly   : true
                        ,listeners  : {
                            change : function( me ) {
                                _computation();
                            }
                        }
                    })
                    ,standards.callFunction('_createNumberField',{
                        id          : 'downPayment' + module
                        ,fieldLabel : 'Down Payment:'
                        ,listeners  : {
                            blur : function( ){
                                _computation();
                            }
                        }
                    })
                    ,standards.callFunction('_createNumberField',{
                        id          : 'balance' + module
                        ,fieldLabel : 'Balance:'
                        ,readOnly   : true
                    })
                    ,standards.callFunction('_createNumberField',{
                        id          : 'ewtRate' + module
                        ,fieldLabel : 'ewtRate:'
                        ,readOnly   : true
                        ,hidden     : true
                    })
                    ,standards.callFunction('_createNumberField',{
                        id          : 'vatType' + module
                        ,fieldLabel : 'vatType:'
                        ,hidden     : true
                        ,readOnly   : true
                    })
                    ,standards.callFunction('_createNumberField',{
                        id          : 'vatPercent' + module
                        ,fieldLabel : 'vatPercent:'
                        ,hidden     : true
                        ,readOnly   : true
                    })
                ]
            };
            
        }

        function _saveForm( form ){

			if( _checkDate() ){
                var { data : { items : { 0 : { raw } } } } = Ext.getCmp('pCode' + module ).getStore()
                ,cancelTag          = Ext.getCmp('cancelTag' + module )
				,gridItem			= Ext.getCmp('grdItems'+module).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0)
				,params 			= {
					idModule 		: idModule
					,date			: Ext.getCmp( 'tdate' + module).getValue()
					,time			: Ext.Date.format(Ext.getCmp( 'ttime' + module).getValue(), 'h:i:s A')
					,pType  		: 2 //SUPPLIER
					,payMode 		: Ext.getCmp('paymentType' + module).getValue()
					,amount			: Ext.getCmp('balance' + module).getValue()
					,bal			: Ext.getCmp('balance' + module).getValue()
					,balLeft 		: Ext.getCmp('balance' + module).getValue()
					,discount		: Ext.getCmp('discountAmount' + module).getValue()
					,discountRate 	: Ext.getCmp('discountPercentage' + module).getValue()
					,vatType 		: Ext.getCmp('vatType' + module).getValue()
					,hasJournal 	: 1
					,referenceNum	: Ext.getCmp( 'referenceNum' + module).getValue()
					,idSupplier		: raw.id
					,items			: Ext.encode( gridItem )
                    ,withPO         : Ext.getCmp( 'withPO' + module ).getValue()
                    ,status         : 2 //APPROVED
                    ,cancelTag		: ( typeof cancelTag != 'undefined' && cancelTag.getValue() ) ? 1 : 0
                    ,creditLimit	: Ext.getCmp('creditLimit' + module).getValue()
					,apBalance		: Ext.getCmp('apBalance' + module).getValue()
                    ,variance		: Ext.getCmp('variance' + module).getValue()
                    ,idAffiliate    : idAffiliate
                };

                if( onEdit ) params['idInvoice'] = dataHolder.idInvoice;

                var isBalanced =  standards.callFunction('_gridJournalEntryValidation',{
                    module : module
                    ,totalAmountDue : Ext.getCmp('balance' + module).getValue()
                    ,totalAmountText : 'Balance'
                });


                if( gridItem.length > 0 ) {
                    let gridData = gridItem.map( item => Object.keys(item).filter( field => ( _checkField( field, params.withPO ) ) ).map( field => item[field]) ).map( item => item.some( checkEmpty ));
                    
                    /* Check if some items has 0 values */
					switch( true ){
						case !isBalanced:
							return false;
						case gridData.some( function( res ) { return res == true }):
                            standards.callFunction('_createMessageBox', { msg : 'Invalid action. Please fill in all important details.'});
                            return false;
                        case parseFloat(params.variance) < 0:
							standards.callFunction('_createMessageBox', { msg : 'Credit limit exceeded.'});
							break;
						default:
                            if( typeof isBalanced != 'boolean' ) params['journalEntries'] = Ext.encode( isBalanced );
                            _submitForm( params, form );
							break;
					}

                    

				} else {
					standards.callFunction('_createMessageBox', { msg : 'Invalid action. Please select atleast (1) Item'});
				}

			} else {
				standards.callFunction('_createMessageBox', { msg : 'Please select a valid due date.'});
            }
        }

        function _checkField( field, withPO ){
            if( field == 'itemCode' || field == 'itemName' || field == 'qty' ){
                return false;
            }
        }
        
        function _submitForm( params, form ){
            form.submit({
                url : route + 'saveForm'
                ,params : params
                ,success : function( action, response ){
                    var resp 	= Ext.decode( response.response.responseText ),
                    msg			= ( resp.match == 0 ) ? 'SAVE_SUCCESS' : 'FAILED'
                    ,action 	= ( resp.match == 0 ) ? '' : 'confirm'
                    ,match      = parseInt( resp.match, 10 );

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
                                    _resetForm( form );
                                }
                            } )
                            break;
                    }
                }
            });
        }

        function _generateRefNum(){
			Ext.Ajax.request( { 
				url     : Ext.getConstant( 'STANDARD_ROUTE2' ) + 'getReferenceNum'
				,msg    : 'Retrieving reference number, Please wait...'
				,params : {
                    idReference 	: Ext.getCmp( 'idReference' + module ).getValue()
					,idmodule   	: idModule
					,idAffiliate 	: idAffiliate
                }
				,success    : function( response ){
					let resp = Ext.decode( response.responseText )
						,msg = ''
						,referenceNum       = Ext.getCmp('referenceNum' + module)
						,idReferenceSeries  = Ext.getCmp('idReferenceSeries' + module);
				
					switch( true ){
						case resp.view.referenceNum <= resp.view.seriesTo:
							referenceNum.setValue(resp.view.referenceNum);
							idReferenceSeries.setValue(resp.view.idReferenceSeries);
							break;
						case resp.view.referenceNum > resp.view.seriesTo:
							msg = 'Maximum reference number exceeded. Set a new series for this reference code to create new transactions.';
							break;
						case resp.view.referenceNum == null:
							msg = 'No reference series was created for the selected cost center.';
							break;
					}
	
					if( msg != '' ){
						standards.callFunction( '_createMessageBox', { 
							msg : msg
							,fn : function() {
								referenceNum.reset();
								idReferenceSeries.reset();
							}
						} )
					}
				}
			} );
		}

        function checkEmpty( item ){
			return item === '' || item == null;
		}
		
		function _checkDate(){
			var date 		= Ext.getCmp( 'tdate' + module )
				,dueDate 	= Ext.getCmp( 'dueDate' + module )
				,pDateTime 	= Ext.dateParse(date.getValue())
				,pDueDate 	= Ext.dateParse(dueDate.getValue());

                return ( pDateTime != null ) ? pDueDate.isSameOrAfter(pDateTime) : false;
		}

        function _resetForm( form ){
			form.reset();
			Ext.resetGrid( 'grdItems' + module );
            Ext.resetGrid( 'gridJournalEntry' + module );
            _setReadOnly( 'pCode', false );
            _setReadOnly('poNumber', false);

            /* Clear global values */
            dataHolder = {}
            onEdit = 0
        }

        function _setReadOnly( id, behavior ){
            let cmp = Ext.getCmp( id + module );

            cmp.readOnly = behavior;

            if( behavior ){
                cmp.triggerEl.hide();
            } else {
                cmp.triggerEl.show();
            }

            cmp.setWidth( 350 );
        }

        function _customListPDF(){
			var gridHistory = Ext.getCmp('gridHistory'+module).store.data.items.map((item)=>item.data);

			Ext.Ajax.request({
                url			: route + 'customListPDF'
                ,method		:'post'
                ,params		: {
                    moduleID    	: 7
                    ,title  	    : 'Receiving List'
                    ,limit      	: 50
                    ,start      	: 0
					,printPDF   	: 1
                    ,gridHistory		: Ext.encode( gridHistory )
                }
                ,success	: function(response, action){
                    if( isGae == 1 ){
						window.open(route+'viewPDF/Receiving List','_blank');
					}else{
						window.open('pdf/inventory/Receiving List.pdf');
					}
                }
			});			
		}

        function _printPDF(){
			var par  = standards.callFunction('getFormDetailsAsObject',{ module : module })
            ,receivingItems = Ext.getCmp('grdItems'+module).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0)
            ,journalEntries = Ext.getCmp('gridJournalEntry'+module).store.data.items.map((item)=>item.data);

			Ext.Ajax.request({
                url			: route + 'generatePDF'
                ,method		:'post'
                ,params		: {
                    moduleID    	: 7
                    ,title  	    : pageTitle
                    ,limit      	: 50
                    ,start      	: 0
					,printPDF   	: 1
					,form			: Ext.encode( par )
                    ,receivingItems		: Ext.encode( receivingItems )
                    ,journalEntries     : Ext.encode( journalEntries )
					,hasPrintOption     : Ext.getCmp('printStatusJEgridJournalEntry' + module).getValue()
                    ,idInvoice		    : dataHolder.idInvoice
                    ,idAffiliate	    : dataHolder.idAffiliate
                }
                ,success	: function(response, action){
                    if( isGae == 1 ){
						window.open(route+'viewPDF/Receiving','_blank');
					}else{
						window.open('pdf/inventory/Receiving.pdf');
					}
                }
			});			
		}

		function _printExcel(){
			if( canPrint ) {
				Ext.Ajax.request({
					url: route + 'printExcel'
					,params: {
						idmodule    : 7	
						,pageTitle  : pageTitle
						,limit      : 50
						,start      : 0
					}
					,success: function(res){
						var path  = route.replace( baseurl, '' );
                        window.open(baseurl + path + 'download' + '/' + pageTitle);
					}
				});
				
			} else {
				standards.callFunction( '_createMessageBox', {
					msg : 'You are currently not authorized to print, please contact the administrator.'
				});
			}
		}

            
        function _editRecord( data ){
            _resetForm( module.getForm() );
            onEdit = 1;

            module.getForm().retrieveData({
                url		    : route + 'getData'
                ,params	    : {
                    idInvoice : data.id
                }
                ,hasFormPDF	: true
                ,excludes   : [ 'pCode' ]
                ,success    : function( response, responseText ){
                    dataHolder = response;

                    _setComboValues( 
                        'idCostCenter'
                        ,{ idAffiliate : parseInt( response.idAffiliate, 10 ) }
                        ,parseInt( response.idCostCenter, 10 )
                    );

                    var refParams = {
                        idAffiliate     : parseInt( response.idAffiliate, 10 )
                        ,idCostCenter   : parseInt( response.idCostCenter, 10 )
                        ,idmodule       : idModule
                    }

                    _setComboValues(
                        'idReference'
                        ,refParams
                        ,parseInt( response.idReference, 10)
                    );

                    Ext.getCmp( 'referenceNum' + module ).setValue( response.referenceNum );

                    _setComboValues(
                        'pCode'
                        ,{ idAffiliate : parseInt( response.idAffiliate , 10) }
                        ,parseInt( response.pCode , 10)
                    );

                    _setComboValues(
                        'poNumber'
                        ,{ date : response.tdate }
                        ,parseInt( response.fident )
                    );

                    _setReadOnly('poNumber', true);

                    // //Call this function to manipulate the visibility of transaction buttons aka [Approve/Cancel]
                    // standards2.callFunction('_setTransaction', {
                    // 	data : response
                    // 	,module	: module
                    // });

                    if( response.fident != null ) {
                        Ext.getCmp('withPO' + module).setValue( true );
                        var poCmp = Ext.getCmp( 'poNumber' + module );
                        poCmp.setDisabled( false );
                        poCmp.getStore().proxy.extraParams.idAffiliate = response.idAffiliate
                        poCmp.getStore().load({
                            callback: function() {
                                poCmp.setValue( parseInt( response.fident, 10 ) );
                            }
                        });
                    }

                    _getItems( response, 0 );

                    var journalEntries = Ext.getCmp( 'gridJournalEntry' + module );
                    _setExtraParams( journalEntries.getStore(), { idInvoice : parseInt( response.idInvoice, 10 ) });
                }
            });
            
            
        }
        
		function _gridHistory() {

			var receivingItems = standards.callFunction( '_createRemoteStore', {
				fields		: [ 
					'referenceNumber', 
					'date', 
					'affiliateName', 
					'costCenterName',
					'supplierName', //name
					'notedBy', 
					'status', 
					'amount', 
                    'receivedBy',
                    'approvedBy',
					'id', //idInvoice
					'name' //referenceNum
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

									receivingItems.load({});
								}
							});
						}
					}
				} );
			}

			return standards.callFunction('_gridPanel', {
                id 					: 'gridHistory' + module
                ,module     		: module
                ,store      		: receivingItems
				,height     		: 265
				,noDefaultRow 		: true
                ,columns: [
                    {	header          : 'Reference Number'
                        ,dataIndex      : 'name'
                        ,width          : 130
                        ,sortable       : false
                        ,columnWidth    : 14
					}
					,{	header          : 'Date'
                        ,dataIndex      : 'date'
                        ,width          : 80
                        ,sortable       : false
                        ,xtype          : 'datecolumn'
                        ,format         : Ext.getConstant('DATE_FORMAT')
                        ,columnWidth    : 14
                    }
					,{	header          : 'Affiliate'
                        ,dataIndex      : 'affiliateName'
                        ,flex           : 1
                        ,minWidth       : 150
                        ,sortable       : false
                        ,columnWidth    : 16
                    }
					,{	header          : 'Cost Center'
                        ,dataIndex      : 'costCenterName'
                        ,flex           : 1
                        ,minWidth       : 80
                        ,sortable       : false
                        ,columnWidth    : 14
					}
					,{	header          : 'Supplier Name'
                        ,dataIndex      : 'supplierName'
                        ,flex           : 1
                        ,minWidth       : 80
                        ,sortable       : false
                        ,columnWidth    : 14
                    }
                    ,{	header          : 'Received By'
                        ,dataIndex      : 'receivedBy'
                        ,flex           : 1
                        ,minWidth       : 80
                        ,sortable       : false
                        ,columnWidth    : 14
					}
					,{	header          : 'Total Amount'
                        ,dataIndex      : 'amount'
						,width          : 100
						,xtype		    : 'numbercolumn'
                        ,sortable       : false
                        ,columnWidth    : 14
						,renderer	    : function (val){
							return Number(val).toLocaleString('en-GB',{minimumFractionDigits: 2, maximumFractionDigits: 2})
						}
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
                        receivingItems.proxy.extraParams.idAffiliate = idAffiliate;
                        receivingItems.load({});
                    }
                }
			});
        }

        function _getPOItemDetails( item, referenceNum ){
            return new Promise( function(resolve, reject) {
                Ext.Ajax.request({
                    url : route + 'getPOItemDetails'
                    ,params : {
                        item : Ext.encode( item.data )
                        ,referenceNum : referenceNum
                    }
                    ,success : function( response ){
                        resolve( Ext.decode( response.responseText).view );
                    }
                });
            });
        }
        
        function _setItemDetails( me, poStore ) {
            var { 0 : store }   = Ext.getCmp('grdItems' + module).selModel.getSelection()
                ,row            = me.findRecord(me.valueField, me.getValue())
                ,referenceNum   = Ext.getCmp('_cbPONumber' + module).getValue()
                ,poNumber       = row.raw.referenceNum
                , msg = 'The item is already selected. If you still want to select the item, you can just add quantity to the row.';

            if( Ext.isUnique(me.valueField, poStore, me, msg ) ) {
                Ext.setGridData(['barcode', 'itemName', 'className', 'cost', 'unitName', 'amount', 'idItem','idItemClass'], store, row)

                if( referenceNum != 'None' && referenceNum != '' && referenceNum != null ) {
                    _getPOItemDetails( row, referenceNum ).then(function(res){
                        if( res.length > 0 ) store.set( 'qtyLeft', res[0].qtyLeft )
                    })
                } else {
                    store.set( 'qtyLeft', 0 )
                    store.set( 'qty', 0 )
                }
            }
        }

        function _setExtraParams( store, value, msg = 0 ) {
            store.proxy.extraParams = value
            store.load({
                callback: function() {
                    if( msg != 0 ) {
                        if( store.getCount() <= 0 ) standards.callFunction('_createMessageBox', { msg :  msg });
                    }
                }
            });
        }

        function _setComboValues( id, extraParams, value ){
            var cmp = Ext.getCmp( id + module );

            cmp.getStore().proxy.extraParams = extraParams;
            cmp.getStore().load({
                callback: function(){
                    cmp.setValue( value )
                }
            });

        }

        function _customFireEvent( id, event, value ) {
            var component = Ext.getCmp( id + module );
            component.setValue( value );
            component.fireEvent(event);
        }

        function _setTotal( data ) {
            // console.log( data );
            // console.log( Ext.getCmp('discountPercentage' + module ) )
            var totalFields = ['discountPercentage', 'downPayment', 'discountAmount'];
            totalFields.map( item => {
                Ext.getCmp( item + module ).setValue( data[item]);
            })
        }

        function __supplyPDetails( fields, data, value, onEdit ){

			fields.map( col =>  Ext.getCmp( col + module ).setValue( data[col] ) );
			var itemGrid = Ext.getCmp('grdItems' + module);

			var extraParams = ( onEdit ) ? { 
				tableName		: 'receiving'
				,fieldValue 	: Ext.encode( { 0 : value['idReferenceSeries'], 1 : value['referenceNum'] } )
				,fieldName		: Ext.encode( { 0 : 'idReferenceSeries', 1 : 'referenceNum' } )
			} : { 
				fieldValue 	: parseInt( value, 10 )
				,fieldName	: 'idSupplier'
				,tableName	: 'supplieritems'
			};

			itemGrid.store.proxy.extraParams = extraParams;
			itemGrid.store.load({});
        }

        function _getItems( data, withPo ){
            // console.log( data );
            
            let gridItems   = Ext.getCmp( 'grdItems' + module )
                ,poNumber   = Ext.getCmp( 'poNumber' + module )
                ,params     = [];

            /* Load store for PO Number, Item Name, Barcode combos in the grid. */
            let cbPONumber  = Ext.getCmp('_cbPONumber' + module)
                ,cbBarcode  = Ext.getCmp('_cbBarcode' + module)
                ,cbItemName = Ext.getCmp('_cbItemName' + module);

            _setExtraParams( cbPONumber.getStore(), { idInvoice : data.idInvoice, name: data.name } );

            let itemParams = { idAffiliate : idAffiliate, idSupplier: data.pCode };
            _setExtraParams( cbBarcode.getStore(), itemParams );
            _setExtraParams( cbItemName.getStore(), itemParams );

            if( withPo ) {
                if( typeof poNumber != 'undefined' ) params['idInvoice'] = poNumber.getValue();
                _setComboValues('pCode', {}, parseInt( data.pCode, 10 ) );
            } else {
                if( typeof data.idInvoice ) params['idInvoice'] = data.idInvoice;
            }

            
            _setComboValues('paymentType', {}, parseInt( data.paymentType, 10 ) );
            _customFireEvent('terms', 'blur', data.terms);

            let value = ( data.paymentType == 2 ) ? false : true;
            _validateField('dueDate', value);
            _validateField('terms', value);

            if( onEdit ) params['onEdit'] = onEdit;
            if( typeof data.pCode != 'undefined' ) params['idSupplier'] = data.pCode;
            if( typeof data.idSupplier != 'undefined' ) params['idSupplier'] = data.idSupplier;
            _setExtraParams(gridItems.getStore(), params );

            if( !onEdit ) _getSupplierDetails( params['idSupplier'] );
        }

        function _getSupplierDetails( idSupplier ){
			Ext.Ajax.request({
				url 	: route + 'getSupplierDetails'
				,params : {
					idSupplier	: idSupplier
				}
				,success : function( response ){
					let resp 	= Ext.decode( response.responseText )
						,record = resp.view;

                        // console.log( record );
						/* Set values to supplier dependent fields: credit limit, ap balance, and variance. */
                        if( record !== null ) {
                            Object.keys( record ).map( key => { 
                                if( key == 'paymentType' ) record[key] = parseInt( record[key], 10 );
                                 Ext.getCmp( key + module ).setValue( record[key] )  
                            } );
                        }
                        
				}
			});
		}

        function _validateField(id, value, msg){
            let cmp = Ext.getCmp( id + module );
            msg = ( typeof msg == 'undefined' ) ? cmp.fieldLabel + Ext.getConstant('REQ') + ':' : msg;
            cmp.allowBlank = value;
            cmp.labelEl.update((value) ? cmp.fieldLabel + ':' : msg );
            cmp.validate();

            if( id === 'terms' ) cmp.setDisabled( value );
        }

        return{
			initMethod:function( config ){
				route		= config.route;
				baseurl		= config.baseurl;
				module		= config.module;
				canPrint	= config.canPrint;
                canDelete	= config.canDelete;
                canCancel   = config.canCancel;
				canEdit		= config.canEdit;
				pageTitle   = config.pageTitle;
				idModule	= config.idmodule
				isGae		= config.isGae;
				idAffiliate = config.idAffiliate
                selRec              = config.selRec;
                componentCalling    = config.componentCalling;
                
				return _mainPanel( config );
			}
		}
    }
}