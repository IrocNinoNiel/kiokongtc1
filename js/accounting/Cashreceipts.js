/**
 * Developer: Mark Reynor D. Magriña
 * Module: Cashreceipts Module
 * Date: Jan 14, 2020
 * Finished: 
 * Description: 
 * DB Tables: 
 * */ 
var Cashreceipts = function(){
    return function(){
        var route, baseurl, module, canDelete, idAffiliate, canPrint, pageTitle, isGae, canEdit, idModule,onEdit = 0,invalidDate = 0,onEditContributionQuickSettings=0, dataHolder, canCancel, selRec, componentCalling;
		var employmentChange = 0;
		var deletedItems = [];
		var selectedItem = [];
		var idEMPLOYEE = 0;
		var allowSave = 0;
		var totalCollection = 0;
		var totalPayment = 0;
		
        function _init(){
			if ( selRec ) {
				_editRecord( { id: selRec.idInvoice } );
			}
        }
		
		function _transactionHandler( status ){
			standards.callFunction( '_createMessageBox', {
				msg		: 'Are you sure you want to change the status of this Cash Receipt?'
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
								msg: 'Cash Receipt has been ' + stats
								,fn : function() {
									standards2.callFunction('_setTransaction', { module	: module ,data : { status : status }});
								}
							});
						}
				}
			});
		}
		
        function _mainPanel( config ){
			
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
						searchURL           : route + 'getCashReceiptDetails'
                        ,emptyText          : 'Search reference number here...'
                        ,module             : module
					}
                }
                ,formItems  : [
					
					standards2.callFunction( '_transactionHeader', {
						module			: module
						,idModule		: idModule
						,idAffiliate	: idAffiliate
						,config			: config
						,moduleCompareFrom	:{ }
					} )
					,{
						xtype      	: 'fieldset'
						,title      : ''
						,layout     : 'column'
						,padding    : 10
						,items      : [
							{  	
								xtype   		: 'container'
								,layout 		: 'vbox'
								,style  		: "margin-bottom: 5px;"
								,columnWidth    : .5
								,items  		: [
									standards.callFunction('_createTextField',{
										id			: 'idInvoice' + module
										,allowBlank	: true
										,hidden		: true
										,value		: 0
									})
									,standards.callFunction( '_createCustomerCombo', {
										id              : 'customer' + module
										,module			: module
										,idAffiliate 	: idAffiliate
										,width      	: 350
										,listeners		: {
											select	: function( me, record ){
												if ( module.getForm().onEdit == 0 ){ 
													if ( typeof(record) != "undefined" ){
														Ext.getCmp( 'creditLimit' + module ).setValue(record[0].raw.creditLimit)
													}
													Ext.getCmp( 'gridListPayment' + module ).store.load({
														params: { 
															idCustomer	: parseInt( me.value )
															,tDate		: Ext.Date.format( Ext.getCmp( 'tdate'+ module ).getValue(), 'Y-m-d' )
															,idInvoice	: 0
														}
														,callback: function(){ }
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
															if(newValue){
																Ext.getCmp( 'gridListPayment' + module ).store.removeAll();
																Ext.getCmp( 'gridListPayment' + module ).store.load({
																	params:{
																		idCustomer	: parseInt( Ext.getCmp( 'customer' + module ).value )
																		,tDate		: Ext.Date.format( Ext.getCmp( 'tdate'+ module ).getValue(), 'Y-m-d' )
																		,otherTag	: newValue
																		,idInvoice	: 0
																	}
																});
																allowSave = 1;
															}else{
																// console.log(newValue);
																Ext.getCmp( 'gridListPayment' + module ).store.load({
																	params: {
																		idCustomer: parseInt( Ext.getCmp( 'customer' + module ).value )
																		,tDate		: Ext.Date.format( Ext.getCmp( 'tdate'+ module ).getValue(), 'Y-m-d' )
																		,otherTag	: newValue
																		,idInvoice	: 0
																	}
																	,callback: function(){ }
																}) 
																allowSave = 0;
															}
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
								xtype   		: 'container'
								,layout 		: 'vbox'
								,layout 		: 'column'
								,columnWidth    : .5
								,items  		: [
									 standards.callFunction( '_createTextArea', {
                                        id          : 'remarks' + module
                                        ,fieldLabel : 'Remarks'
										,labelWidth	: 135
										,width     	: 350
										,height		: 60
                                        ,allowBlank : true
                                    } )
								]
							}
						]
					}		
                    ,_receivableGrid( config )	
					,{
						xtype      	: 'fieldset'
						,title      : 'Collection Details'
						,layout     : 'fit'
						,padding    : 15
						,columnWidth: "100%"
						,items      : [ _paymentGrid() ]
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
									,{  	
										xtype   : 'container'
										,layout : 'hbox'
										,width	: 340
										,items  : [
											standards.callFunction( '_createTextField', {
												id          : 'discountper' + module
												,fieldLabel : 'Discount'
												,allowBlank : true
												,maxLength  : 50
												,maxLength  : 50
												,width      : 95
												,labelWidth	: 50
												,isNumber   : true
												,style  	: 'margin-top: 5px;'
												,listeners	:{
													blur: function() {
														var discountPercent = this.value;
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
														var discount = ( discountPercent / 100 ) * totalCashCheque ;
														Ext.getCmp( 'discount' + module ).setValue(discount);
													}
												}
											} )	
											,standards.callFunction( '_createTextField', {
												id          : 'discount' + module
												,fieldLabel : '%'
												,allowBlank : true
												,labelSeparator : ''
												,maxLength  : 50
												,maxLength  : 50
												,width      : 225
												,labelWidth	: 35
												,isNumber   : true
												,isDecimal  : true
												,style  	: 'margin-top: 5px;'
												,listeners	:{
													change: function() {
														computeTotalDetails();
													}
												}
											} )	
											
										]
									}		
									// ,standards.callFunction( '_createTextField', {
									// 	id          : 'discount' + module
									// 	,fieldLabel : 'Discount'
									// 	,allowBlank : true
									// 	,width      : 340
									// 	,labelWidth	: 130
									// 	,readOnly	: false
									// 	,style  	: 'margin-top: 5px;float: right;'
									// 	,isNumber   : true
									// 	,isDecimal  : true
									// 	,listeners	: {
											// change: function() {
											// 	computeTotalDetails();
											// }
									// 	}
									// } )		
									,standards.callFunction( '_createTextField', {
										id          : 'totalAmountCollected' + module
										,fieldLabel : 'Total Amount Collected'
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
                    { name	: 'idInvoice' ,type : 'int' },{ name	: 'amount' ,type : 'int' }
					, 'date'
					, 'referenceNum'
					, 'customerName'
					, 'id'
					, 'name'
				]
                ,url : route + 'getCashReceiptDetails'
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
                        ,xtype		: 'datecolumn'
                        ,format		: 'm/d/Y'
                        ,width      : 125
                    }
                    ,{  header      : 'Reference Number'
                        ,dataIndex  : 'referenceNum'
                        ,width      : 125
					}
                    ,{  header      : 'Customer Name'
                        ,dataIndex  : 'customerName'
                        ,width      : 500
						,minWidth	: 150
						,flex		: 1
					}
					,{  header      : 'Amount'
                        ,dataIndex  : 'amount'
                        ,width      : 250
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
				var otherTagDetail = Ext.getCmp('otherTag' + module).value;
				var cashRequirement = 0;
				var chequeRequirement = 0;
				if ( !otherTagDetail && totalCollection == 0 ){
					standards.callFunction('_createMessageBox',{
						msg: 'No collections made, please update your collections'
						,icon: 'error'
					})
					return false;
				}
				if ( otherTagDetail == 0 && (totalCollection !== totalPayment) ){
					standards.callFunction('_createMessageBox',{
						msg: 'Your total collections is not equal to the total payments.'
						,icon: 'error'
					})
					return false;
				}
				
				//Container for all collections
				var collectionDetailsContainer 	= new Array();
				Ext.getCmp( 'gridListPayment' + module ).store.each( function( item ) { 
					if ( parseInt(item.data.collections) > 0){
						collectionDetailsContainer.push( item.data ) 
					}
				} )	
				
				//Container for all Payments
				var paymentListContainer 	= new Array();
				Ext.getCmp( 'gridPaymentDetails' + module ).store.each( function( item ) { 
					if ( item.data.amount != 0 ){ paymentListContainer.push( item.data ) }
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
						console.log( "cheque payment here ");
						standards.callFunction('_createMessageBox',{
						msg: 'You have made an invalid Check payment, please update your payments. </br> Bank, effectivity date and check # are very important details for Check type payments.'
					})
					return false;
				}
				if ( cashRequirement > 0 ) {
						console.log( "cash payment here ");
						standards.callFunction('_createMessageBox',{
						msg: 'Invalid cash payment, please update your bank detail.'
					})
					return false;
				}
				var totalDebit = Ext.getCmp( 'gridJournalEntry' + module ).store.sum('debit');
				var totalCredit = Ext.getCmp( 'gridJournalEntry' + module ).store.sum('credit');
				
				if ( totalDebit !== totalCredit){
					standards.callFunction('_createMessageBox',{
						msg: 'Total debit is not equal to total credit, please correct your input/s'
					})
					return false;
				}
				
				form.submit({
					waitTitle	: "Please wait"
					,waitMsg	: "Submitting data..."
					,url		: route + 'saveCashReceiptsForm'
					,params		: {
						collectionDetails : Ext.encode( collectionDetailsContainer )
						,paymentList 	: Ext.encode( paymentListContainer )
						,journalDetails : Ext.encode( journalDetailsContainer )
						,date			: Ext.getCmp( 'tdate' + module ).value
						,idModule		: idModule
						,time			: Ext.getCmp( 'ttime' + module).getValue()
						,jeLength		: journalDetailsContainer.length
					}
					,success:function( action, response ){
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
				,params: data
				,hasFormPDF	: true //Mao ni atong ge add.
				,success:function( response, data ){						
					dataHolder = response;
					// console.log( response );
					// console.log( response.pCode );
				
					var customerData = Ext.getCmp( 'customer' + module );
					customerData.store.load({
						callback: function (){
							customerData.setValue( parseInt(response.pCode) );
							customerData.fireEvent('select');
						}
					})
					
					/* Load the collectins grid thru the Invoice ID of the cashreceipt */
					Ext.getCmp( 'gridListPayment' + module ).store.load({
						params: { 
							idInvoice: parseInt( parseInt(response.idInvoice) ) 
							,idCustomer: parseInt( parseInt(response.pCode) ) 
							,otherTag: otherTag
						}
						,callback: function( me, otherData ){ totalCollection = Ext.getCmp( 'gridListPayment' + module ).store.sum('collections'); }
					}) 
					
					/* Load all payment details thru the Invoice ID of the cashreceipt */
					Ext.getCmp(  'gridPaymentDetails' + module ).store.load({
						params: { idInvoice: parseInt( response.idInvoice ) }
						,callback: function(){
							computeTotalDetails();
							totalPayment = Ext.getCmp(  'gridPaymentDetails' + module ).store.sum('amount');
						}
					})
					// Ext.resetGrid( 'grid_gridJournalEntry' + module );
					
					/* Load all journal entries thru the Invoice ID of the cashreceipts */
					// Ext.getCmp( 'grid_gridJournalEntry' + module ).store.load({
						// params: { idInvoice: parseInt( response.idInvoice ) }
						// ,callback: function(){}
					// });
				}
			})
        }
		
		function _deleteRecord( data ){
			
			// console.log(Ext.encode(data));
			// console.log(data.idInvoice);
			// console.log(data+' <-- mao ni sulod sa data');
			// console.log(Ext.getCmp( 'idInvoice' + module ).getValue());
			// return false;
			
			standards.callFunction('_createMessageBox',{
				msg		: 'DELETE_CONFIRM'
				,action	: 'confirm'
				,fn		: function( btn ){
					if ( btn == 'yes' ){
						Ext.Ajax.request({
							url		: route + 'deleteCashReceiptRecord'
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
		
		function _receivableGrid( config ){
			
			var gridListPaymentStore = standards.callFunction('_createRemoteStore',{
				fields: [
					{ name: 'salesIDInvoice',	type: 'number' }
					,{ name: 'fIDModule', 		type: 'number' }
					,{ name: 'balance', 		type: 'float' }
					,{ name: 'collections', 	type: 'float' }
					,{ name: 'sales',		 	type: 'float' }
					,{ name: 'down_payment', 	type: 'float' }
					,{ name: 'receivables', 	type: 'float' }
					,{ name: 'discount',		type: 'float' }
					,{ name: 'vatType',			type: 'float' }
					,{ name: 'vatAmount',		type: 'float' }
					,{ name: 'ewtAmount',		type: 'float' }
					,{ name: 'ewtRate',			type: 'float' }
					,{ name: 'penaltyRate',		type: 'float' }
					,{ name: 'penaltyAmount',	type: 'float' }
					,'reference' ,'date'
				]
				,url: route + 'getGridListForPayments'
			})
			
            return {
                xtype : 'tabpanel'
                ,items: [
                    {
                        title: 'Details'
                        ,layout:{
                            type: 'card'
                        }
                        ,items  :   [
							{	xtype : 'container'
								,width : 390
								,items : [
								
									standards.callFunction('_gridPanel',{
										id				: 'gridListPayment' + module
										,moduele		: module
										,store			: gridListPaymentStore
										,height			: 300
										,noDefaultRow	: true
										,noPage			: true	
										,plugins		: true
										,listeners  : { 
											edit: function(value, rowdata){ 
												console.log( rowdata );
											} 
										}
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
												header		: 'Sales'
												,dataIndex	: 'sales'		
												,xtype      : 'numbercolumn'
												,format     : '0,000.00'												
												,hasTotal	: 1											
											}
											,{
												header		: 'VAT'
												,dataIndex	: 'vatAmount'		
												,xtype      : 'numbercolumn'
												,format     : '0,000.00'												
												,hasTotal	: 1											
											}
											,{
												header		: 'Discount'
												,dataIndex	: 'discount'	
												,xtype      : 'numbercolumn'
												,format     : '0,000.00'												
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
												header		: 'Receivables'
												,dataIndex	: 'receivables'
												,width		: 150
												,columnWidth: 25
												,xtype      : 'numbercolumn'
												,format     : '0,000.00'
												,hasTotal	: 1
											}
											,{  header          : 'Penalty'
												,width          : 150
												,columns        : [
													{  
														header          : '%'
														,dataIndex      : 'penaltyRate'
														,width          : 40
														,xtype          : 'numbercolumn'
														,format         : '0,000'
														,editor			: 'float'
														,sortable       : false
													}
													,{  header          : 'Amount of Penalty'
														,dataIndex      : 'penaltyAmount'
														,width          : 110
														,xtype          : 'numbercolumn'
														,format         : '0,000.00'
														,editor			: 'float'
														,sortable       : false
													}
												]
											}
											,{  header          : 'EWT'
												,width          : 150
												,columns        : [
													{  
														header          : '%'
														,dataIndex      : 'ewtRate'
														,width          : 75
														,xtype          : 'numbercolumn'
														,format         : '0,000.00'
														,editor			: 'float'
														,sortable       : false
													}
													,{  header          : 'EWT'
														,dataIndex      : 'ewtAmount'
														,width          : 75
														,xtype          : 'numbercolumn'
														,format         : '0,000.00'
														,editor		: 'float'
														,sortable       : false
													}
												]
											}
											,{
												header		: 'Collections'
												,dataIndex	: 'collections'
												,width		: 100
												,columnWidth: 25												
												,xtype      : 'numbercolumn'
												,format     : '0,000.00'
												,editor		: 'float'
												,hasTotal	: 1
											}
											,{
												header		: 'Balance'
												,dataIndex	: 'balance'
												,width		: 100
												,columnWidth: 25
												,xtype      : 'numbercolumn'
												,format     : '0,000.00'
												,hasTotal	: 1
											}
										]
										,listeners: {
											edit : function ( me, rowData ) {
												var vat			= rowData.record.get( 'vatAmount' ); 
												var discount	= parseFloat( rowData.record.get( 'discount' ) ); 
												var receivables	= parseFloat( rowData.record.get( 'receivables' ) );
												var penaltyRate	= parseFloat( rowData.record.get( 'penaltyRate' ) ); 
												var penalty		= parseFloat( rowData.record.get( 'penaltyAmount' ) );
												var ewtRate		= parseFloat( rowData.record.get( 'ewtRate' ) );
												var ewt			= parseFloat( rowData.record.get( 'ewtAmount' ) ); 
												var vatType		= parseFloat( rowData.record.get( 'vatType' ) ); 
												var collection	= parseFloat( rowData.record.get( 'collections' ) ); 

												switch ( rowData.field ) {
													case 'penaltyRate':
														penaltyRate	= parseFloat( rowData.value );
														penalty		= parseFloat( penaltyRate / 100 ) * receivables;
														rowData.record.set( 'penaltyAmount', penalty );
														break;
													case 'penaltyAmount':
														penalty		= parseFloat( rowData.value );
														penaltyRate	= parseFloat( penalty / receivables ) * 100;
														rowData.record.set( 'penaltyRate', penaltyRate );
														break;
													case 'ewtRate':
														ewtRate		= parseFloat( rowData.value );
														ewt			= parseFloat( ewtRate / 100 ) * receivables;
														rowData.record.set( 'ewtAmount', ewt );
														break;
													case 'ewtAmount':
														ewt			= parseFloat( rowData.value );
														ewtRate		= parseFloat( ewt / receivables ) * 100;
														rowData.record.set( 'ewtRate', ewtRate );
														break;
													case 'collections':
														let temp = ( receivables + penalty ) - ewt;
														if ( vatType == 2 ) temp = ( receivables + penalty ) - ewt;
														if( parseFloat( rowData.value ) > temp ){													
															standards.callFunction( '_createMessageBox',{
																msg		: 'Collection should not be greater than the receivable.'
																,icon	: 'error'
															})
															rowData.record.set('collections', 0.00 );
															rowData.record.set('balance', 0.00 );
														} else {
															collection = parseFloat( rowData.value );
														}
														break;
													default:
														break;
												}
												console.log( "penalty : " + penalty );
												console.log( "ewt : " + ewt );
												console.log( "collection : " + collection );
												var balance = ( receivables + penalty - ewt ) - collection;
												rowData.record.set( 'balance' , balance );
												totalCollection = gridListPaymentStore.sum('collections');														
											}
												
											// ,afterrender: function ( me, eOpts ){
												// if ( module.getForm().onEdit != 0 ) totalCollection = gridListPaymentStore.sum('collections');
											// }
										}
									})
									
								]
							}
                            
                        ]
					}
					,{
                        title: 'Journal Entries'
                        ,layout:{
                            type: 'card'
                        }
                        ,items  :   [
                            standards.callFunction( '_gridJournalEntry',{
                                module	        : module
								,hasPrintOption	: 1
								,config         : config								
								,customer       : 'customer'                            
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
			
			var storeBankAccount = standards.callFunction('_createRemoteStore',{
				fields		: [	{ name: 'idBankAccount', type: 'number' },'bankAccount' ]
				,url		: route + 'getBankAccountDetails'
			})
			
			var chequeNumberHolder = '';

			return standards.callFunction('_gridPanel',{
				id				: 'gridPaymentDetails' + module
				,module			: module
				,store			: storePaymentGrid
				// ,width			: 1000
				// ,style			: 'width: 100%;'
				,height			: 300
				,noPage			: true
				,plugins		: true
				,noDefaultRow	: true
				,extraAfterEdit : function( editor, e, eOpts ){
					if(e.field == 'type' && e.record.get( 'type' ) == 'Cash') {
						e.record.set( 'chequeNo', '' );
					}
				}
				,extraBeforeEdit : function( editor, e, eOpts ){
					if(e.field == 'chequeNo' && e.record.get( 'type' ) == 'Cash') {
						return false;
					}
				}
				,tbar			: {
					canPrint			: false
					,noExcel			: true
					,content			: 'add'
					,customAddHandler 	: function( ) {
						var mainGrid = Ext.getCmp( 'gridPaymentDetails' + module )
						mainGrid.store.insert(0,{ type : 'Cash', typeID: 1 })
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
							header		: 'Bank'
							,dataIndex	: 'bankName'
							,flex		: 1
							,editor		: standards.callFunction('_createCombo',{
									fieldLabel		: ''
									,id				: 'bankID' + module
									,store			: storeBankAccount
									,emptyText		: 'Select account...'
									,displayField	: 'bankAccount'
									,valueField		: 'bankAccount'
									,listeners		: {
										select	: function( me, recordDetails, returnedData ){
											var gridMain = Ext.getCmp('gridPaymentDetails' + module);
											var recordMain = gridMain.getSelectionModel().getSelection()[0];
											recordMain.set('idBankAccount',recordDetails[0].data.idBankAccount);
										}
									}
								})
							,minWidth	: 150
						}
						,{
							header		: 'Check #'
							,dataIndex	: 'chequeNo'
							,width		: 200
							,editor		: 'text'
							,maskRe     : /[0-9]/
							
						}
						,{  header      : 'Effectivity Date'
							,dataIndex  : 'date'
							,width      : 200
							,xtype      : 'datecolumn'
							// ,format     : 'm/d/Y'
							,editor     : 'date'
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
						totalPayment = storePaymentGrid.sum('amount');
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
				var discountPercent = ( discountValue / totalCashCheque ) * 100 ;
				var totalCollectedValue = totalCashCheque - discountValue;
				Ext.getCmp( 'totalAmountCollected' + module ).setValue( totalCollectedValue );
				Ext.getCmp( 'discountper' + module ).setValue( discountPercent );

			}catch(er){ console.error(er); }
		}
		
        function _resetForm( form ){
			// deletedItems = [];
			Ext.getCmp('mainFormPanel' + module).getForm().reset();
			onEdit = 0;
			totalCollection = 0;
			totalPayment = 0;
			
			Ext.resetGrid( 'gridListPayment'+module );
			Ext.resetGrid( 'gridPaymentDetails'+module );
			Ext.resetGrid( 'gridJournalEntry' + module );
			Ext.getCmp( 'gridHistory' + module ).store.load(); 

			var otherTag = Ext.getCmp( 'otherTag' + module );
			otherTag.readOnly = false;
			otherTag = 'false'; 
        }
		
		function _printPDF(){
            standards.callFunction('_listPDF',{
				grid 		: Ext.getCmp('gridListPayment'+module)
				,customListPDFHandler : function(){
					var par  = standards.callFunction('getFormDetailsAsObject',{
						module : module
						,getSubmitValue : true
					});
					par.title		= 'Cash Receipt Form'
					par.idInvoice	= Ext.getCmp( 'idInvoice' + module ).getValue();
					par.details		= Ext.encode( Ext.getCmp('gridListPayment'+module).store.data.items.map((item)=>item.data).filter((item)=>item.salesIDInvoice !==0) );
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
		
		// function _printPDF(){
		// 	var par  = standards.callFunction('getFormDetailsAsObject',{ module : module });
        //     par['title']		= 'Cash Receipt Form'
		// 	par['idInvoice']	= Ext.getCmp( 'idInvoice' + module ).getValue();
		// 	par['details'] 		= Ext.encode( Ext.getCmp('gridListPayment'+module).store.data.items.map((item)=>item.data).filter((item)=>item.salesIDInvoice !==0) );
		// 	par['collection']	= Ext.encode( Ext.getCmp('gridPaymentDetails'+module).store.data.items.map((item)=>item.data).filter((item)=>item.typeID !==0) );
			

        //     Ext.Ajax.request( {
        //         url			: route + 'generatePDF'
        //         ,method		:'post'
        //         ,params		: par
        //         ,success	: function(response, action){
        //             if( isGae == 1 ){
		// 				window.open(route+'viewPDF/Cash Receipt Form','_blank');
		// 			}else{
		// 				window.open('pdf/accounting/Cash Receipt Form.pdf');
		// 			}
        //         }
		// 	} );
		// }

		function _customListPDF() {
			Ext.Ajax.request({
				url  		: route + 'customListPDF'
				,params 	: { 
					items : Ext.encode( Ext.getCmp('gridHistory'+module).store.data.items.map((item)=>item.data) )
				}
				,success 	: function(response){
					if( isGae == 1 ){
						window.open(route+'viewPDF/Cash Receipts List','_blank');
					}else{
						window.open('pdf/accounting/Cash Receipts List.pdf');
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