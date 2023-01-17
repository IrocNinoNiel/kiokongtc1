/**
 * Developer: Mark Reynor D. Magriña
 * Module: Cashreceipts Module
 * Date: Jan 14, 2020
 * Finished: 
 * Description: 
 * DB Tables: 
 * */ 
var Disbursements = function(){
    return function(){
        var route, module, canDelete, idAffiliate, baseurl, canPrint, canCancel, pageTitle, isGae, canEdit, idModule,onEdit = 0,invalidDate = 0,onEditContributionQuickSettings=0, selRec, componentCalling;
		var employmentChange = 0, dataHolder;
		var deletedItems = [];
		var selectedItem = [];
		var idEMPLOYEE = 0;
		var allowSave = 0;
		var totalCollection = 0;
		var totalPaid = 0;
		var totalPayment = 0;
		
        function _init(){
			if ( selRec ) {
				_editRecord( { id: selRec.idInvoice } );
			}
        }
		
		function _transactionHandler( status ){
			standards.callFunction( '_createMessageBox', {
				msg		: 'Are you sure you want to change the status of this Disbursement?'
				,action	: 'confirm'
				,fn		: function( btn ){
					if( btn == 'yes' ){
						_changeTransactionStatus( status );
					}
				}
			} );
		}
		
		function _changeTransactionStatus( status ) {
			Ext.Ajax.request({
				url : Ext.getConstant( 'STANDARD_ROUTE2' ) + 'updateTransactionStatus'
				,params : {
					status 			: status
					,idInvoice 		: dataHolder.idInvoice
					,notedBy 		: Ext.getConstant('USERID')
					,username		: Ext.getConstant('USERFULLNAME')
					// ,checkOnTable   : '' // not sure
					,tableName      : ''
				}
				,success : function( response ){
					var stats = ( status == 2 ) ? 'Approved' : 'Cancelled', resp = Ext.decode( response.responseText );
						if( resp.match == 1 ) {
							standards.callFunction('_createMessageBox',{ msg: 'EDIT_USED' });
						} else {
							standards.callFunction('_createMessageBox',{ 
								msg: 'Disbursement has been ' + stats
								,fn : function() {
									standards2.callFunction('_setTransaction', { module	: module ,data : { status : status }});
								}
							});
						}
				}
			});
		}

		function _printPDF(){
            standards.callFunction('_listPDF',{
				grid 		: Ext.getCmp('gridListPayables'+module)
				,customListPDFHandler : function(){
					var par  = standards.callFunction('getFormDetailsAsObject',{
						module : module
						,getSubmitValue : true
					});
					par.title		= 'Disbursement Form'
					par.idInvoice	= Ext.getCmp( 'idInvoice' + module ).getValue();
					par.details		= Ext.encode( Ext.getCmp('gridListPayables'+module).store.data.items.map((item)=>item.data).filter((item)=>item.salesIDInvoice !==0) );
					par.collection	= Ext.encode( Ext.getCmp('gridPaymentDetails'+module).store.data.items.map((item)=>item.data).filter((item)=>item.typeID !==0) );
					
					Ext.Ajax.request({
						url: route + 'generatePDF'
						,params:par
						,success: function(res){
							if( isGae ){
								window.open( route + 'viewPDF/' + par.title , '_blank' );
							}
							else{
								window.open( baseurl + 'pdf/accounting/' + par.title + '.pdf');
							}
						}
					});
                }
            })
		}
		
        function _mainPanel( config ){
			supplierCmbStore = standards.callFunction('_createRemoteStore',{
				fields 	: [ { name:'id', type: 'number' },'name', 'creditLimit' ]
				,url		: route + 'getSupplier'
				,autoLoad	: true
			})
			
			return standards2.callFunction(	'_mainPanelTransactions' ,{
                config				: config
				,moduleType			: 'form'
				,transactionHandler	: _transactionHandler
				,hasCancelTransaction	: canCancel
				,tbar       		: {
					saveFunc        : _saveForm
					,resetFunc      : _resetForm
					,formPDFHandler : _printPDF
					,hasFormPDF     : true
					,hasFormExcel	: false
                    ,customListExcelHandler	: _printExcel
					,customListPDFHandler	: _customListPDF
					,filter:{
						searchURL           : route + 'getDisbursementDetails'
                        ,emptyText          : 'Search reference number here...'
                        ,module             : module
					}
					// ,filter:{
					// 	filterByData	: [
					// 		{	'name'				: 'Reference number'
					// 			,'tableNameColumn'	: 'idReferenceSeries'
					// 			,'tableIDColumn'	: 'idInvoice'
					// 			,'tableName'		: 'invoices'
					// 			,'isDateRange'		: 0
					// 			,'defaultValue'		: 'All'
					// 		}
					// 	]
					// }
                }
                ,formItems  : [
					standards2.callFunction( '_transactionHeader', {
						module			: module
						,idModule		: idModule
						,idAffiliate	: idAffiliate
						,config			: config
						,moduleCompareFrom	:{}
					} )
					,{
						xtype      	: 'fieldset'
						,title      : ''
						,layout     : 'column'
						,padding    : 10
						,items      : [
							{  	
								xtype   : 'container'
								,layout : 'vbox'
								// ,style  : "width:705px;margin-bottom: 5px;"
								,style  : "margin-bottom: 5px;"
								,columnWidth    : .5
								,items  : [
									standards.callFunction('_createTextField',{
										id			: 'idInvoice' + module
										,allowBlank	: true
										,hidden		: true
										,value		: 0
									})
									,standards.callFunction('_createSupplierCombo',{
										module		: module
										,id			: 'supplierCmb' + module
										,store      : supplierCmbStore
										,fieldLabel : 'Supplier'
										,listeners	: { 
											select		: function( me, record ){
												Ext.getCmp( 'creditLimit' + module ).setValue( record[0].data.creditLimit );
												if( module.getForm().onEdit == 0 ){
													Ext.getCmp( 'gridListPayables' + module ).store.load({
														params: {
															idSupplier	: parseInt( me.value )
															,tDate		: Ext.Date.format( Ext.getCmp( 'tdate' + module ).getValue(), 'Y-m-d')
															,idInvoice	: 0
														}
													})
												}
											}
										}
									})
									
									,standards.callFunction( '_createTextField', {
										id          : 'creditLimit' + module
										,fieldLabel : 'Credit Limit'
										,allowBlank : true
										,readOnly	: true
										,maxLength  : 50
										,width      : 350
										,isNumber   : true
										,isDecimal  : true
										,listeners	: {
											change : function(){ }
										}
									} )
									,{  xtype   : 'container'
                                        ,layout : 'hbox'
                                        ,items  : [
											standards.callFunction( '_createCheckField', {
												id              : 'otherTag' + module
												,fieldLabel     : 'Sales'
												,width      	: 157
												,listeners:{
													change: function( checkbox, newValue, oldValue, eOpts ){ 
														if ( onEdit == 0 ){
															Ext.getCmp( 'gridListPayables' + module ).store.load({
																params: {
																	idSupplier	: parseInt( Ext.getCmp( 'supplierCmb' + module ).getValue() )
																	,tDate		: Ext.Date.format( Ext.getCmp( 'tdate' + module ).getValue(), 'Y-m-d')
																	,otherTag	: newValue
																	,idInvoice	: 0
																}
															})
															allowSave = 1;
														}
													}
												}
											})
											,{
												xtype	: 'label'
												,forId	: 'myFieldId'
												,text	: 'Check for other collections'
												,margin	: '3 0 0 0'
												,width  : 170
												,style	: 'color:red;'
											}
                                        ]
                                    }
								]
							}
							,{  	
								xtype   : 'container'
								,layout : 'vbox'
								,layout : 'column'
								,columnWidth    : .5
								,items  : [
									 standards.callFunction( '_createTextArea', {
                                        id          : 'remarks' + module
                                        ,fieldLabel : 'Remarks'
										,labelWidth	: 135
										,width     	: 350
										,height		: 50
                                        ,allowBlank : true
                                    } )
									
								]
							}

						]
					}		
                    ,_payableGrid( config )	
					,{
						xtype      	: 'fieldset'
						,title      : 'Payment Details'
						,layout     : 'fit'
						,padding    : 15
						,columnWidth: "100%"
						,items      : [
							_paymentGrid()
						]
						
					}
					,{
						xtype		: 'container'
						,layout		: 'column'
						,style		: 'margin-top: 10px;'
						,items		: [
							{   xtype       : 'fieldset'
								,title      : ''
								,layout     : 'fit'
								,style		: "float: right; border: 0px; margin-right: 45px;"
								,width		: 340
								,items      : [ 
									standards.callFunction( '_createTextField', {
										id          : 'totalCheck' + module
										,fieldLabel : 'Total Check'
										,allowBlank : true
										,width      : 340
										,labelWidth	: 130
										,readOnly	: true
										,style  	: 'margin-top: 5px;float: right;'
										,isNumber   : true
										,isDecimal  : true
									} )		
									,standards.callFunction( '_createTextField', {
										id          : 'totalCash' + module
										,fieldLabel : 'Total Cash'
										,allowBlank : true
										,width      : 340
										,labelWidth	: 130
										,readOnly	: true
										,style  	: 'margin-top: 5px;float: right;'
										,isNumber   : true
										,isDecimal  : true
									} )		
									,standards.callFunction( '_createTextField', {
										id          : 'discount' + module
										,fieldLabel : 'Discount'
										,allowBlank : true
										,width      : 340
										,labelWidth	: 130
										,readOnly	: false
										,style  	: 'margin-top: 5px;float: right;'
										,isNumber   : true
										,isDecimal  : true
										,listeners	: {
											change: function() {
												computeTotalDetails();
											}
										}
									} )		
									,standards.callFunction( '_createTextField', {
										id          : 'totalAmountDisbursed' + module
										,fieldLabel : 'Total Amount Disbursed'
										,allowBlank : true
										,width      : 340
										,labelWidth	: 130
										,readOnly	: true
										,style  	: 'margin-top: 5px;float: right;'
										,isNumber   : true
										,isDecimal  : true
									} )
								]
							}
							
						]
					}
				]
                ,listItems  : _gridHistory()
                ,listeners  : {
                    afterrender : _init
                }
            })
		}
		
        function _gridHistory(){
            var store = standards.callFunction( '_createRemoteStore', {
				fields : [
                    { name	: 'idInvoice' ,type : 'int' },{ name	: 'amount' ,type : 'float' }
                    ,'affiliateName','costCenterName','date','referenceNum','supplierName','preparedByName','notedByName','status'
				]
                ,url : route + 'getDisbursementDetails'
            } );

            return standards.callFunction( '_gridPanel', {
                id              : 'gridHistory' + module
                ,module         : module
                ,store          : store
                ,width          : 350
                ,noDefaultRow   : true
                ,columns        : [
					{  header      : 'Date'
                        ,dataIndex  : 'date'
                        ,width      : 80
                    }
                    ,{  header      : 'Reference Number'
                        ,dataIndex  : 'referenceNum'
                        ,width      : 105
                    }
                    ,{  header      : 'Supplier Name'
                        ,dataIndex  : 'supplierName'
                        ,width      : 150
						,minWidth	: 150
						,flex		: 1
					}
					,{  header      : 'Amount'
                        ,dataIndex  : 'amount'
                        ,width      : 150
						,xtype      : 'numbercolumn'
                        ,format     : '0,000.00'
                    }
                    ,standards.callFunction( '_createActionColumn', {
						canEdit     : canEdit
						,icon       : 'pencil'
						,tooltip    : 'Edit Cash Receipt details'
						,Func       : _editRecord
                    } )               
                    ,standards.callFunction( '_createActionColumn', {
						canDelete   : canDelete
						,icon       : 'remove'
						,tooltip    : 'Delete Cash Receipt'
						,Func       : _deleteRecord
					} )
                ]
            } )
        }

        function _saveForm( form ){
				
				// console.log(totalCollection);
				// console.log(totalPayment);
				// return false;
				
				var questionablePaymentCounter = 0;
				var cashRequirement = 0;
				var chequeRequirement = 0;
				// if ( totalCollection == 0 ){
				if ( totalPaid == 0 ){
					standards.callFunction('_createMessageBox',{
						msg: 'No payment made, please update your payments'
						,icon: 'error'
					})
					return false;
				}
				// if ( totalCollection !== totalPayment){
				if ( totalPaid !== totalCollection){
					standards.callFunction('_createMessageBox',{
						msg: 'Your total paid amount is not equal to the total payments.'
						,icon: 'error'
					})
					return false;
				}
				//Container for all paid payables
				var paidDetailsContainer 	= new Array();
				Ext.getCmp( 'gridListPayables' + module ).store.each( function( item ) { 
					if ( parseInt(item.data.paid) > 0){
						paidDetailsContainer.push( item.data ) 
					}
				} )	
				//Container for all Payments collections
				var paymentListContainer 	= new Array();
				Ext.getCmp( 'gridPaymentDetails' + module ).store.each( function( item ) { 
					if (  item.data.amount != 0 ){ paymentListContainer.push( item.data ) }
					if ( item.data.type == "Cash" ) {
						if( item.data.bankName == null || item.data.amount == 0 || item.data.amount == null || item.data.bankName == '' ) { cashRequirement += 1; }
					} else {
						if( (item.data.bankName == null || item.data.bankName == '' || item.data.chequeNo == "" || item.data.chequeNo == null || item.data.date == null ) || item.data.amount == 0 && item.data.type == "Cheque" ){ chequeRequirement += 1; }
					}
				} )	
				
				//Container for all Journal entries
				var journalDetailsContainer 	= new Array();
				Ext.getCmp( 'gridJournalEntry' + module ).store.each( function( item ) { journalDetailsContainer.push( item.data ) } )	
				
				if( chequeRequirement > 0 ){
					standards.callFunction('_createMessageBox',{
						msg: 'You have made an invalid Check payment, please update your payments. </br> Bank, effectivity date and check # are very important details for Check type payments.'
					})
					return false;
				}
				if ( cashRequirement > 0 ) {
					standards.callFunction('_createMessageBox',{
						msg: 'Invalid cash payment, please update your bank detail.'
					})
					return false;
				}
				
				form.submit({
					waitTitle	: "Please wait"
					,waitMsg	: "Submitting data..."
					,url		: route + 'saveDisbursements'
					,params		: {
						paidDetails : Ext.encode( paidDetailsContainer )
						,paymentList 	: Ext.encode( paymentListContainer )
						,journalDetails : Ext.encode( journalDetailsContainer )
						,date			: Ext.getCmp( 'tdate' + module ).value
						,idModule		: idModule
						,time			: Ext.getCmp( 'ttime' + module).getValue()
						,jeLength		: journalDetailsContainer.length
					}
					,success:function( action, response ){
						
						// console.log('Success...');
						// console.log(response);
						// console.log(response.result.match);
						
						var matchValue = parseInt(response.result.match);
						var msgs = '';
						if( matchValue == 2 ){  msgs = 'Cash Receipt ID do not exist, please select another reference.';  }
						else{ msgs = 'Record has been successfully saved.'; }
						standards.callFunction('_createMessageBox',{ msg: msgs })
						_resetForm();
					}
				})
        }

        function _editRecord( data ){
			var otherTag = Ext.getCmp( 'otherTag' + module );
			otherTag.readOnly = true;
			if ( Ext.getCmp( 'otherTag' + module ).getValue() == 'true' ) otherTag = 'true';
			else otherTag = 'false'; 

			module.getForm().retrieveData({
				url: route + 'retrieveData'
				,hasFormPDF	: true //Mao ni atong ge add.
				,params: data
				,success:function( response, data ){						
					dataHolder = response;
				
					var supplierData = Ext.getCmp( 'supplierCmb' + module );
					supplierData.store.load({
						callback: function (){
							supplierData.setValue( parseInt(response.pCode) );
						}
					})

					Ext.getCmp('idInvoice' + module).setValue( response.idInvoice )
					Ext.getCmp( 'creditLimit' + module ).setValue( response.creditLimit );

					/* Load the collectins grid thru the Invoice ID of the cashreceipt */
					Ext.getCmp( 'gridListPayables' + module ).store.load({
						params: { 
							idInvoice: parseInt( parseInt(response.idInvoice) )
							,idSupplier	: parseInt( response.pCode )
						}
						,callback: function( me, otherData ){ totalCollection = Ext.getCmp( 'gridListPayables' + module ).store.sum('paid'); }
					}) 
					
					/* Load all payment details thru the Invoice ID of the cashreceipt */
					Ext.getCmp(  'gridPaymentDetails' + module ).store.load({
						params: { idInvoice: parseInt( response.idInvoice ) }
						,callback: function(){
							computeTotalDetails();
							totalPayment = Ext.getCmp(  'gridPaymentDetails' + module ).store.sum('amount');
						}
					})
					
					// Ext.resetGrid( 'gridJournalEntry' + module );
					
					/* Load all journal entries thru the Invoice ID of the cashreceipts */
					/* Ext.getCmp( 'gridJournalEntry' + module ).store.load({
						params: { idInvoice: parseInt( response.idInvoice ) }
						,callback: function(){}
					}); */
				}
			})
        }
		
		function _deleteRecord( data ){
			standards.callFunction('_createMessageBox',{
				msg		: 'DELETE_CONFIRM'
				,action	: 'confirm'
				,fn		: function( btn ){
					if ( btn == 'yes' ){
						Ext.Ajax.request({
							url		: route + 'deleteDisbursementRecord'
							,params	: { 
								idInvoice: parseInt(data.idInvoice)
							}
							,method	: 'post'
							,success: function(response, option){
								standards.callFunction('_createMessageBox',{ msg: 'DELETE_SUCCESS' })
								_resetForm();
							}
							,failure: function(){}
						})
					}
				}
			})
		}
		
		function _payableGrid( config )
		{
			var gridListPaymentStore = standards.callFunction( '_createRemoteStore' , {
				fields: [
					{ name: 'recIdInvoice', 	type: 'number' }
					,{ name: 'fIDModule', 		type: 'number' }
					,{ name: 'balance', 		type: 'float' }
					,{ name: 'receiving',		type: 'float' }
					,{ name: 'paid',	 		type: 'float' }
					,{ name: 'down_payment',	type: 'float' }
					,{ name: 'payables', 		type: 'float' }
					,{ name: 'discount',		type: 'float' }
					,{ name: 'vatType',			type: 'float' }
					,{ name: 'vat',				type: 'float' }
					,{ name: 'ewtAmount',		type: 'float' }
					,{ name: 'ewtRate',			type: 'float' }
					,'reference' 
					,'date'
				]
				,url: route + 'getPayablesList'
			})
					
			return {
				xtype : 'tabpanel'
				,items: [
					{
						title	: 'Details'
						,layout	: { type: 'card' }
						,items  : [
							{	xtype : 'container'
								,width : 390
								,items : [
									standards.callFunction('_gridPanel',{
										id				: 'gridListPayables' + module
										,moduele		: module
										,store			: gridListPaymentStore
										,height			: 300
										,noDefaultRow	: true
										,noPage			: true	
										,plugins		: true
										,tbar			: {
											canPrint		: false
											,noExcel		: true
											,route			: route
										}
										,columns : [
											{
												header		: 'Reference'
												,dataIndex	: 'reference'
												,columnWidth: 15
												,minWidth	: 70
												,flex		: 1
											}
											,{
												header		: 'Date'
												,dataIndex	: 'date'
												,xtype		: 'datecolumn'
												,format		: 'm/d/Y'
												,width		: 80
												,columnWidth: 25
											}
											,{
												header		: 'Receiving'
												,dataIndex	: 'receiving'		
												,xtype      : 'numbercolumn'
												,format     : '0,000.00'												
												,width		: 100
												,hasTotal	: 1											
											},{
												header		: 'VAT'
												,dataIndex	: 'vat'		
												,xtype      : 'numbercolumn'
												,format     : '0,000.00'												
												,width		: 100
												,hasTotal	: 1											
											}
											,{
												header		: 'Discount'
												,dataIndex	: 'discount'	
												,xtype      : 'numbercolumn'
												,format     : '0,000.00'												
												,width		: 150
												,hasTotal	: 1
											}
											,{
												header		: 'Down Payment'
												,dataIndex	: 'down_payment'
												,width		: 100
												,xtype      : 'numbercolumn'
												,format     : '0,000.00'
												,hasTotal	: 1
											}
											,{
												header		: 'Payables'
												,dataIndex	: 'payables'
												,width		: 100
												,columnWidth: 25
												,xtype      : 'numbercolumn'
												,format     : '0,000.00'
												,hasTotal	: 1
											}
											,{  header          : 'EWT'
												,width          : 200
												,columns        : [
													{  
														header          : '%'
														,dataIndex      : 'ewtRate'
														,width          : 60
														,xtype          : 'numbercolumn'
														,format         : '0,000'
														,editor			: 'float'
														,sortable       : false
													}
													,{  header          : 'EWT'
														,dataIndex      : 'ewtAmount'
														,width          : 90
														,xtype          : 'numbercolumn'
														,format         : '0,000.00'
														,editor		: 'float'
														,sortable       : false
													}
												]
											}
											,{
												header		: 'Paid'
												,dataIndex	: 'paid'
												,width		: 150
												,columnWidth: 25												
												,xtype      : 'numbercolumn'
												,format     : '0,000.00'
												,editor		: 'float'
												,hasTotal	: 1
											}
											,{
												header		: 'Balance'
												,dataIndex	: 'balance'
												,width		: 150
												,columnWidth: 25
												,xtype      : 'numbercolumn'
												,format     : '0,000.00'
												,hasTotal	: 1
											}
										]
										,listeners: {
											edit : function ( me, rowData ) {
												var ewt			= parseFloat( rowData.record.get( 'ewtAmount'		) ); 
												var paid		= parseFloat( rowData.record.get( 'paid'	) );
												var ewtRate		= parseFloat( rowData.record.get( 'ewtRate' ) );
												var discount	= parseFloat( rowData.record.get( 'discount' ) );
												var payables	= parseFloat( rowData.record.get( 'payables' ) ); 
												var vat			= rowData.record.get( 'vat' ); 
												var vatType		= parseFloat( rowData.record.get( 'vatType' ) ); 
												
												switch ( rowData.field ) {
													case 'ewtRate':
														ewtRate		= parseFloat( rowData.value );
														ewt			= parseFloat( ewtRate / 100 ) * payables;
														rowData.record.set( 'ewtAmount', ewt );
														break;
													case 'ewtAmount':
														ewt			= parseFloat( rowData.value );
														ewtRate		= parseFloat( ewt / payables ) * 100;
														rowData.record.set( 'ewtRate', ewtRate );
														break;
													case 'paid':
														let totalBal = ( payables - ewt );
														if( vatType == 2 ) totalBal = ( payables - ewt );

														if( parseFloat( rowData.value ) > totalBal ){													
															standards.callFunction( '_createMessageBox',{
																msg		: 'Payment should not be greater than the payables.'
																,icon	: 'error'
															})
															rowData.record.set('paid', 0.00 );
															rowData.record.set('balance', 0.00 );
														} else {
															paid = parseFloat( rowData.value );
														}
														break;
													default: break;
												}

												var balance =  ( payables - ewt );
												if ( vatType == 2 ) balance = ( payables - ewt );
												rowData.record.set( 'balance' , balance - paid );
												totalPaid = gridListPaymentStore.sum('paid');														
											}
										}
									})
								]
							}
						]
					}
					,{
						title	: 'Journal Entries'
						,layout	: { type: 'card' }
						,items  :   [
							standards.callFunction( '_gridJournalEntry' , {
								module	        : module
								,hasPrintOption	: 1
								,config         : config
								,supplier       : 'supplierCmb'
							})
						]
					}
				]
			}
		}
		
		function _paymentGrid(){
			
			var storePaymentGrid = standards.callFunction('_createRemoteStore',{
				fields : [ 'type' ,'typeID' ,'idBankAccount' ,'bankName' ,'chequeNo' ,'date' ,{ name: 'amount', type: 'number'} ]
				,url: route + 'getCollectionDetails'
			})
			
			var storePaymentType = standards.callFunction('_createLocalStore',{
				data 		: [ 'Cash', 'Cheque' ]
				,startAt	: 1
			})
			
			var storeBank = standards.callFunction('_createRemoteStore',{
				fields		: [	{ name: 'idBank', type: 'number' },'bankName' ]
				,url		: route + 'getBankDetails'
			})
			
			return standards.callFunction('_gridPanel',{
				id				: 'gridPaymentDetails' + module
				,module			: module
				,store			: storePaymentGrid
				,height			: 300
				,noPage			: true
				,plugins		: true
				,noDefaultRow	: true
				,tbar			: {
					canPrint			: false
					,noExcel			: true
					,content			: 'add'
					,customAddHandler 	: function( ) {
						var mainGrid = Ext.getCmp( 'gridPaymentDetails' + module )
						mainGrid.store.insert(0,{ type : 'Cash', typeID: 0 })
					}
				}
				,columns	: [
						{
							header		: 'Type'
							,dataIndex 	: 'type'
							,width		: 200
							,editor		: standards.callFunction('_createCombo',{
										fieldLabel		: ''
										,id				: 'typeId' + module
										,store			: storePaymentType
										,emptyText		: 'Select type...'
										,displayField	: 'name'
										,valueField		: 'name'
										,listeners		: {
											select: function( me, recordDetails, returnedData ){
												var gridMain = Ext.getCmp('gridPaymentDetails' + module);	
												var recordMain = gridMain.getSelectionModel().getSelection()[0];
												recordMain.set('typeID',recordDetails[0].data.id);
											}
										}
										
									})
							
						}
						,{
							header		: 'Bank Account'
							,dataIndex	: 'bankName'
							,flex		: 1
							,editor		: standards.callFunction('_createCombo',{
									fieldLabel		: ''
									,id				: 'bankID' + module
									,store			: storeBank
									,emptyText		: 'Select bank account...'
									,displayField	: 'bankName'
									,valueField		: 'bankName'
									,listeners		: {
										select	: function( me, recordDetails, returnedData ){
											var gridMain = Ext.getCmp('gridPaymentDetails' + module);
											var recordMain = gridMain.getSelectionModel().getSelection()[0];
											recordMain.set('idBankAccount',recordDetails[0].data.idBank);
										}
									}
								})
							,minWidth	: 150
						}
						,{  header      : 'Effectivity Date'
							,dataIndex  : 'date'
							,width      : 200
							,xtype      : 'datecolumn'
							// ,format     : 'm/d/Y'
							,editor     : 'date'
						}
						,{
							header		: 'Check #'
							,dataIndex	: 'chequeNo'
							,width		: 200
							,editor		: 'number'
						}
						,{
							header		: 'Amount'
							,dataIndex	: 'amount'
							,width		: 250
							,xtype		: 'numbercolumn'
							,format		: '0,000.00'
							,editor		: 'float'
							,hasTotal	: 1
						}
				]
				,listeners :{
					edit : function( me, rowData ){ if( rowData.field == 'amount' || rowData.field == 'type' ){ 
						computeTotalDetails(); 
						totalCollection = storePaymentGrid.sum('amount');
					} }
				}
			})			
		}
		
		function computeTotalDetails(){
			
			try{
				var totalCheque = 0;
				var totalCash = 0;
				Ext.getCmp( "gridPaymentDetails" + module ).store.each( 
					function( object ) { 
						if( object.data.type == "Cash" ) totalCash += parseInt(object.data.amount)
						if( object.data.type == "Cheque" ) totalCheque += parseInt(object.data.amount)
					}  
				)
				Ext.getCmp( 'totalCash' + module ).setValue(totalCash);
				Ext.getCmp( 'totalCheck' + module ).setValue(totalCheque);
				var totalCashCheque = totalCash + totalCheque;
				var discountValue = parseInt(Ext.getCmp( 'discount' + module ).value);
				var totalCollectedValue = totalCashCheque - discountValue;
				Ext.getCmp( 'totalAmountDisbursed' + module ).setValue( totalCollectedValue );
			}catch(er){ console.error(er); }
		}
		
        function _resetForm( form ){
			// deletedItems = [];
			Ext.getCmp('mainFormPanel' + module).getForm().reset();
			onEdit = 0;
			totalCollection = 0;
			totalCollection = 0;
			
			Ext.resetGrid( 'gridListPayables'+module );
			Ext.resetGrid( 'gridPaymentDetails'+module );
			Ext.resetGrid( 'gridJournalEntry' + module );
			Ext.getCmp( 'gridHistory' + module ).store.load(); 
			
			var otherTag = Ext.getCmp( 'otherTag' + module );
			otherTag.readOnly = false;
			otherTag = 'false'; 
        }
		
		function _customListPDF() {
			Ext.Ajax.request({
				url  		: route + 'customListPDF'
				,params 	: { 
					items : Ext.encode( Ext.getCmp('gridHistory'+module).store.data.items.map((item)=>item.data) )
				}
				,success 	: function(response){
					if( isGae == 1 ){
						window.open(route+'viewPDF/Disbursements List','_blank');
					}else{
						window.open('pdf/accounting/Disbursements List.pdf');
					}
				}
			});
		}

		function _printExcel(){
            Ext.Ajax.request({
                url: route + 'printExcel'
                ,params: {
                    idmodule    : idModule
                    ,pageTitle  : pageTitle
                    ,limit      : 50
                    ,start      : 0
                }
                ,success: function(res){
                    var path  = route.replace( baseurl, '' );
                    window.open(baseurl + path + 'download' + '/' + pageTitle);
                }
            });
		}
		
        return{
			initMethod:function( config ){
				route 		= config.route;
				baseurl		= config.baseurl;
				module 		= config.module;
				canDelete 	= config.canDelete;
				canPrint 	= config.canPrint;
				canCancel	= config.canCancel;
				pageTitle 	= config.pageTitle;
				isGae 		= config.isGae;
				canEdit 	= config.canEdit;
				idModule 	= config.idmodule;
				idUserValue = config.idUserValue
				idEMPLOYEE	= config.idEMPLOYEE
				idAffiliate = config.idAffiliate
				selRec		= config.selRec
				componentCalling = config.componentCalling
				
				return _mainPanel( config );
			}
		}
    }
}