/**
 * Developer: Mark Reynor D. Magriña
 * Module: Sales Module
 * Date: Dec 12, 2019
 * Finished: 
 * Description: 
 * DB Tables: 
 * */ 
var Sales = function(){
    return function(){
        var route, module, canDelete, canCancel, baseurl, idAffiliate, canPrint, pageTitle, isGae, canEdit, idModule,onEdit = 0,invalidDate = 0,onEditContributionQuickSettings=0, dataHolder , selRec , componentCalling;
		var employmentChange = 0;
		var deletedItems = [];
		var selectedItem = [];
		var idEMPLOYEE = 0;
		
        function _init(){
			_requireCostCenter( Ext.getConstant('AFFILIATEREFTAG') );
			if ( selRec ) {
				_editCol( { data: selRec , id:selRec.idInvoice } );
			}
        }
		
        function _mainPanel( config ){
			Ext.requestPost(route+'affiliateInfo', {id: idAffiliate}).then(res=>{
				let {view : {0:response}} = JSON.parse(res.responseText)
				Ext.valued('vattype'+module,parseInt(response.vatType))
				Ext.valued('vatper'+module, Number(response.vatPercent).toLocaleString('en-GB',{minimumFractionDigits: 2, maximumFractionDigits: 2}))
			})

			var items = standards.callFunction(  '_createRemoteStore' ,{
                fields:[
                    {	name	: 'id'
                        ,type	: 'number'
                    }
                    ,'barcode'
					,'name'
					,'class'
					,'unit'
					,'cost'
					,'qty'
                ]
                ,url: route + 'getCustomerItem'
			});

			var customerStore	= standards.callFunction(  '_createRemoteStore' ,{
				fields		: [ {name: 'id', type: 'number'} , 'name', 'withCreditLimit' ]
				,url		: route + 'getCustomer'
				,autoLoad	: true
			} )

			var driverStore	= standards.callFunction(  '_createRemoteStore' ,{
				fields		: [ {name: 'id', type: 'number'} , 'name' ]
				,url		: route + 'get_drivers'
				,autoLoad	: true
			} )

			

			var refSO = standards.callFunction('_createRemoteStore',{ fields : [ 'id', 'name', 'customer' ] ,url : route + 'soList' })
			
			return standards2.callFunction(	'_mainPanelTransactions' ,{
				config					: config
				,module					: module
				,hasApproved			: false
				,transactionHandler		: _transactionHandler
				,moduleType				: 'form'
				,orientation 			: 'L'
				,hasCancelTransaction	: canCancel
				,tbar       : {
					saveFunc        		: _saveForm
					,resetFunc      		: _resetForm
					,formPDFHandler 		: _printPDF
					,customListExcelHandler	: _printExcel
					,customListPDFHandler	: _customListPDF
					,hasFormPDF     		: true
					,hasFormExcel			: false
					,filter : {
                        searchURL       : route + 'searchHistoryGrid'
						,emptyText      : 'Search reference here...'
						,module         : module
                    }
				}
				
                ,formItems  : [

					{
						xtype 	: 'fieldset'
						,title	: ''
						,layout	: 'column'
						,padding: 10
						,columnWidth: "100%"
						,items	: [
							{   xtype           : 'container'
                                ,columnWidth    : .5
                                ,items          : [									
                                    standards.callFunction( '_createTextField', {
                                        id          : 'idInvoice' + module
                                        ,allowBlank : true
										,value 		: 0
										,hidden		: true
                                        
									} )  
									,standards.callFunction( '_createTextField', {
                                        id          : 'withCreditLimit' + module
                                        ,allowBlank : false
										,value 		: 0
										,hidden		: true
									} )  
									,standards.callFunction( '_createTextField', {
                                        id          : 'vatType' + module
                                        ,allowBlank : true
										,value 		: 0
										,hidden		: true
									} ) 
									,standards.callFunction( '_createTextField', {
                                        id          : 'withVat' + module
                                        ,allowBlank : true
										,value 		: null
										,hidden		: true
									} )
									,standards2.callFunction( '_createCostCenter', {
										module			: module
										,idAffiliate	: parseInt( Ext.getConstant( 'AFFILIATEID' ), 10 )
										,allowBlank		: true
									})
									,standards2.callFunction( '_transactionReference', {
										module		: module
										,idAffiliate: parseInt( Ext.getConstant( 'AFFILIATEID' ), 10 )
										,idModule	: idModule
										,style  : 'margin-bottom: 5px;'
									})
									,{  xtype   : 'container'
                                        ,layout : 'hbox'
                                        ,style  : 'margin-bottom: 5px;'
                                        ,items  : [
											standards.callFunction( '_createCheckField', {
												id              : 'deliveryReceiptTag' + module
												,fieldLabel     : 'With Delivery Receipt'
												,width			: 160
												,listeners:{
													change: function( checkbox, newValue, oldValue, eOpts ){
														Ext.resetInputValidation('deliveryReceipt' + module, !newValue, 'With Delivery Receipt', 'deliveryReceiptTag' + module)
													}	
												}
											})
											,standards.callFunction( '_createTextField', {
												id          : 'deliveryReceipt' + module
												,fieldLabel : ''
												,allowBlank : true
												,disabled	: true
												,width		: 190
											} )
                                        ]
                                    }
									,standards.callFunction( '_createDateTime', {
										dId				: 'tdate'+module
										,tId			: 'ttime'+module
										,dFieldLabel	: 'Date'
										,tstyle 		: 'margin-left: 5px;'
										,tWidth 		: 105
										,minValue		: Ext.getConstant('AFFILIATEDATESTART')
										,dlistener		: { 
											afterrender	: function(){
												standards2.callFunction( '_checkIf_journal_isClosed', { 
													idAffiliate	: idAffiliate
													, tdate		: this.value
													, module	: module 
												} )
											}

											,change : function(){
												standards2.callFunction( '_checkIf_journal_isClosed', { 
													idAffiliate	: idAffiliate
													, tdate		: this.value
													, module	: module 
												} )

												/* Reset reference and reference num fields to re-validate 
												the references made on or before the selected date. */
												var reference = Ext.getCmp('idReference' + module)
												,referenceNum = Ext.getCmp('referenceNum' + module);
			
												reference.reset();
												referenceNum.reset();
												_setDueDateValidation();
											}
										}
									})
                                ]
                            }							
                            ,{  xtype           : 'container'
                                ,columnWidth    : .5
                                ,layout         : 'column'
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
					
					,{
						xtype      	: 'fieldset'
						,title      : 'Sales Details'
						,layout     : 'column'
						,padding    : 10
						,columnWidth: "100%"
						,items      : [
							{  	
								xtype   : 'container'
								,layout : 'column'
								,columnWidth    : .5
								,items  : [
									standards.callFunction( '_createTextField', {
										id              : 'fident' + module
										,hidden			: true
									})
									,standards.callFunction( '_createCheckField', {
										id              : 'withSalesOrderTag' + module
										,fieldLabel     : 'With Sales Order'
										// ,style  	: 'margin-top: 5px;'
										,listeners:{
											change: function( checkbox, newValue, oldValue, eOpts ){
												Ext.resetInputValidation('refSONumber' + module, !newValue, 'SO Number')
												Ext.getCmp('gridItem'+module).store.removeAll()
											}	
										}
									})
									,standards.callFunction( '_createCombo', {
										id              : 'refSONumber' + module
										,store			:  refSO
										,fieldLabel     : 'SO Number'
										,style			: 'margin-top: 5px;'
										,allowBlank     : true
										,disabled		: true
										,emptyText		: 'Select so number...'
										,listeners		:	{
											beforeQuery: function(){
												this.store.proxy.extraParams = { 
													idAffiliate: idAffiliate,
													transaction_date : `${Ext.dateParse(Ext.valued('tdate'+module)).format('YYYY-MM-DD')} ${Ext.dateParse(Ext.valued('ttime'+module)).format('HH:mm:00')}`,
													with_id : Ext.valued('fident'+module)
												}
											}
											,select: function(){
												let  {data : {customer}} = this.store.getById(this.getValue())	
												Ext.getCmp( 'customer' + module ).store.proxy.extraParams.idCustomer = {customer}
												Ext.getCmp( 'customer' + module ).store.proxy.extraParams.idAffiliate = idAffiliate
												Ext.getCmp( 'customer' + module ).store.load({})											
												
												if(onEdit != 1) Ext.valued('customer' + module, parseInt(customer, 10))
												Ext.getCmp('gridItem'+module).store.proxy.extraParams.id = this.getValue()
												Ext.getCmp('gridItem'+module).store.proxy.extraParams.idAffiliate = idAffiliate
												Ext.getCmp('gridItem'+module).store.load({})
											}
										}
									} )
									,standards.callFunction( '_createCombo', {
										id              : 'customer' + module
										,style  		: 'margin-top: 5px;'
										,fieldLabel		: 'Customer Name'
										,allowBlank		: false
										,idAffiliate	: idAffiliate
										,store			: customerStore
										,module			: module
										,listeners		: {
											change		: function(){
												if(Ext.valued('refSONumber' + module) == null && onEdit != 1){
													Ext.requestPost(route+'customerItem',{id: this.getValue(), affiliate: Ext.valued('idAffiliate'+module)}).then(res=>{
														let response = JSON.parse(res.responseText)
														let store = Ext.getCmp('gridItem' + module).store
														store.removeAll()
														store.add(response.view.filter(item  => item.id != null))
														__setTotals()
													})
												}
												if(Ext.valued('customer' + module) != null){
													Ext.requestPost(route+'customerInfo',{id: this.getValue()}).then(res=>{
														let {view : {0:response}} = JSON.parse(res.responseText)
														if(onEdit != 1) Ext.valued('paymentType' + module, parseInt(response.paymentMethod))
														if(onEdit != 1) Ext.valued('terms' + module, parseInt(response.terms))
														Ext.valued('creditLimit' + module, Number(response.creditLimit).toLocaleString('en-GB',{minimumFractionDigits: 2, maximumFractionDigits: 2}))
														Ext.valued('arbal' + module, Number(response.balLeft).toLocaleString('en-GB',{minimumFractionDigits: 2, maximumFractionDigits: 2}))
														Ext.valued('variance' + module, Number(Ext.valued('creditLimit' + module) - Ext.valued('arbal' + module)).toLocaleString('en-GB',{minimumFractionDigits: 2, maximumFractionDigits: 2}))
														Ext.valued('r_vatPercent' + module, Number(response.vatPercent).toLocaleString('en-GB',{minimumFractionDigits: 2, maximumFractionDigits: 2}))
														Ext.valued('r_vatType' + module, parseInt(response.vatType))
														Ext.valued('ewtRate' + module, parseInt(response.ewtRate))
														Ext.valued('penalty' + module, parseInt(response.penalty))
														__setTotals()
													})
												}
												
											}
										}
									})
									,standards.callFunction( '_createCombo', {
										id              : 'paymentType' + module
										,fieldLabel     : 'Payment Method'
										,allowBlank     : false
										,emptyText		: 'Select payment method...'
										,store			: standards.callFunction( '_createLocalStore' , { data : [ 'Cash' ,'Charge' ] } )
										,style  	: 'margin-top: 5px;'
										,valueField     : 'id'	
										,displayField   : 'name'
										,listeners		:{
											change	: function(){
												Ext.resetInputValidation('terms' + module, !(this.getValue() === 2), 'Terms')
											}
										}

									} )
									,standards.callFunction( '_createTextField', {
										id              : 'terms' + module
										,fieldLabel     : 'Terms'
										,style			: 'margin-top: 5px;'
										,isNumber		: true
										,isDecimal		: false
										,maskRe     : /[^a-z,A-Z]/
										,value		: 0
										,listeners		: {
											afterrender : function(){ _setDueDateValidation(); }
											,change : function(){ _setDueDateValidation(); }
										}
									} )
									,standards.callFunction( '_createDateField', {
										id          : 'duedate' + module
										,fieldLabel : 'Due Date'
										,style  	: 'margin-top: 5px;'
										,allowBlank : false
										,listeners: {
											change: function(field, date, ov) { }
										} 
									} )
								]
							}
							,{  	
								xtype   : 'container'
								,layout : 'column'
								,columnWidth    : .5
								,items  : [
									,standards.callFunction( '_createTextField', {
										id          : 'creditLimit' + module
										,fieldLabel : 'Credit Limit'
										,allowBlank : true
										,readOnly	: true
										,maxLength  : 50
										,style  	: 'margin-top: 10px;'
										,isNumber   : true
										,isDecimal  : true
										,listeners	: {
											change : function(){
												var is_limited = Ext.getCmp('withCreditLimit'+module).getValue() == '1'
												if(is_limited){
													var limit = parseFloat(Ext.getCmp('creditLimit'+module).getValue())
													var bal = parseFloat(Ext.getCmp('arbal'+module).getValue())
													var variance = limit - bal
													Ext.getCmp('variance'+module).setValue(variance)
												}
											}
										}
									} )	
									,standards.callFunction( '_createTextField', {
										id          : 'arbal' + module
										,fieldLabel : 'AR Balance'
										,allowBlank : true
										,readOnly	: true
										,maxLength  : 50
										,style  	: 'margin-top: 5px;'
										,isNumber   : true
										,isDecimal  : true
										,listeners	: {
											change : function(){
												var is_limited = Ext.getCmp('withCreditLimit'+module).getValue() == '1'
												if(is_limited){
													var limit = parseFloat(Ext.getCmp('creditLimit'+module).getValue())
													var bal = parseFloat(Ext.getCmp('arbal'+module).getValue())
													var variance = limit - bal
													Ext.getCmp('variance'+module).setValue(variance)
												}
											}
										}
									} )	
									
									,standards.callFunction( '_createTextField', {
										id          : 'variance' + module
										,fieldLabel : 'Variance'
										,allowBlank : true
										,readOnly	: true
										,maxLength  : 50
										,style  	: 'margin-top: 5px;'
										,isNumber   : true
										,isDecimal  : true
									} )	

									,standards.callFunction( '_createCombo', {
										id              : 'idDriver' + module
										,fieldLabel     : 'Driver'
										,emptyText		: 'Select driver...'
										,style			: 'margin-top: 15px;'
										,store			: driverStore
										,valueField     : 'id'	
										,displayField   : 'name'
										,allowBlank 	: false
									} )

									,standards.callFunction( '_createTextField', {
										id          : 'plateNumber' + module
										,fieldLabel : 'Plate Number'
										,allowBlank : false
										,maxLength  : 20
										,style  	: 'margin-top: 5px;'
									} )	

									,standards.callFunction( '_createTextField', {
										id          : 'r_vatPercent' + module
										,hidden		: true
										,allowBlank : true
										,isNumber   : true
										,isDecimal  : true
									} )	

									,standards.callFunction( '_createTextField', {
										id          : 'r_vatType' + module
										,hidden		: true
										,allowBlank : true
										,isNumber   : true
										,isDecimal  : true
									} )	

									,standards.callFunction( '_createTextField', {
										id          : 'ewtRate' + module
										,hidden		: true
										,allowBlank : true
										,isNumber   : true
										,isDecimal  : true
									} )	

									,standards.callFunction( '_createTextField', {
										id          : 'penalty' + module
										,hidden		: true
										,allowBlank : true
										,isNumber   : true
										,isDecimal  : true
									} )	
									
								]
							}
							
							

						]
					}		
                    ,__items( config )				   
					,{   
						xtype   : 'container'
						,layout : 'column'
						,style	: 'margin-top: 10px;'
						,items  : [ 
							{   xtype       : 'fieldset'
                                ,title      : 'Total'
                                ,layout     : 'fit'
                                ,padding    : 10
								,style		: "float: right;"
								,width		: 340
                                ,items      : [ 
										standards.callFunction( '_createTextField', {
											id          : 'sales' + module
											,fieldLabel : 'Sales'
											,allowBlank : true
											,maxLength  : 50
											,readOnly	: true
											,style  	: 'margin-top: 5px;'
											,isNumber   : true
											,isDecimal  : true
										} )	
										,standards.callFunction( '_createTextField', {
											id          : 'vattype' + module
											,allowBlank : true
											,readOnly	: true
											,hidden		: true
										} )	
										,standards.callFunction( '_createTextField', {
											id          : 'vatper' + module
											,allowBlank : true
											,readOnly	: true
											,hidden		: true
										} )	
										,standards.callFunction( '_createTextField', {
											id          : 'vatamount' + module
											,fieldLabel : 'Add: VAT Amount'
											,allowBlank : true
											,readOnly	: true
											,maxLength  : 50
											,style  	: 'margin-top: 5px;'
											,isNumber   : true
											,isDecimal  : true
										} )	
										,standards.callFunction( '_createTextField', {
											id          : 'total' + module
											,fieldLabel : 'Total'
											,allowBlank : true
											,readOnly	: true
											,maxLength  : 50
											,style  	: 'margin-top: 5px;'
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
															blur: function( me, The, eOpts ){
																let total = Ext.valued('total'+module)
																let amount = this.getValue()
																if(total > 0){
																	let value = parseFloat(total * (amount/100))
																	Ext.valued('discount'+module, value)
																	Ext.valued('totalamnt'+module, total - value)
																}
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
														,width      : 223
														,labelWidth	: 40
														,isNumber   : true
														,style  	: 'margin-top: 5px;'
														,listeners	:{
															blur: function( me, The, eOpts ){
																let total = Ext.valued('total'+module)
																let amount = this.getValue()
																if(total > 0){
																	let percetage = parseFloat(amount/total)
																	Ext.valued('discountper'+module, (percetage*100))
																	Ext.valued('totalamnt'+module, total - amount)
																}
															}
														}
													} )	
													
												]
											}
											,standards.callFunction( '_createTextField', {
												id          : 'totalamnt' + module
												,fieldLabel : 'Total Amount Due'
												,allowBlank : true
												,readOnly	: true
												,maxLength  : 50
												,style  	: 'margin-top: 5px;'
												,isNumber   : true
												,isDecimal  : true
												,listeners  : {
													blur: function(){
														let total = parseFloat(this.getValue()) - parseFloat(Ext.valued('downpayment'+module))
														if(total < 0){
															standards.callFunction('_createMessageBox',{ msg: 'Your downpayment exceed the total amount.' })
															Ext.valued('downpayment'+module, parseFloat(this.getValue()))
															Ext.valued('balance'+module, 0)
														}else{
															Ext.valued('balance'+module, total)
														}
														
													}
												}
											} )	
											,standards.callFunction( '_createTextField', {
												id          : 'downpayment' + module
												,fieldLabel : 'Down Payment'
												,allowBlank : true
												,maxLength  : 50
												,style  	: 'margin-top: 5px;'
												,isNumber   : true
												,isDecimal  : true
												,listeners  : {
													blur: function(){
														let total = parseFloat(Ext.valued('totalamnt'+module)) - parseFloat(this.getValue())
														if(total < 0){
															standards.callFunction('_createMessageBox',{ msg: 'Your downpayment exceed the total amount.' })
															this.setValue(parseFloat(Ext.valued('totalamnt'+module)))
															Ext.valued('balance'+module, 0)
														}else{
															Ext.valued('balance'+module, total)
														}
														
													}
												}
											} )	
											,standards.callFunction( '_createTextField', {
												id          : 'balance' + module
												,fieldLabel : 'Balance'
												,allowBlank : true
												,readOnly	: true
												,maxLength  : 50
												,style  	: 'margin-top: 5px;'
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
            } )
		}

        function _gridHistory(){
            var items = standards.callFunction( '_createRemoteStore' , {
                fields:[ 
					{	name	: 'id'
                        ,type	: 'number'
                    }
					,'reference'
					,'date'
					,'customer'
					,{
						name: 'sales' 
						,type: 'number'
					}
					,'notedby'

					,'idReference'
					,'referenceNum'
					,'idModule'
					]
                ,url: route + 'getListInvoices'
			});
			
			return standards.callFunction('_gridPanel', {
                id 			: 'gridHistory' + module
                ,module     : module
                ,store      : items
				,height     : 265
				,noDefaultRow : true
                ,columns: [
                    {	header      : 'Reference Number'
                        ,dataIndex  : 'reference'
                        ,width      : 120
						,sortable   : false
						,columnWidth: '20%'
					}
					,{	header      : 'Date'
						,dataIndex  : 'date'
						,xtype		: 'datecolumn'
						,format		: 'm/d/Y'
						,width      : 120
						,sortable   : false
						,columnWidth: '20%'
					}
					,{	header      : 'Customer Name'
						,dataIndex  : 'customer'
						,minWidth      : 240
						,sortable   : false
						,columnWidth: '40%'
						,flex:1
					}
					,{	header      : 'Net Sales'
						,dataIndex  : 'sales'
						,width      : 120
						,sortable   : false
						,columnWidth: '20%'
						,xtype : 'numbercolumn'
						,format : '0,000.00'
					}
					,standards.callFunction( '_createActionColumn', {
                        canEdit     : canEdit
                        ,icon       : 'pencil'
						,tooltip    : 'Edit'
                        ,width      : 30
                        ,Func       : _editCol
                    })
                    ,standards.callFunction( '_createActionColumn', {
                        canDelete     : canDelete
                        ,icon       : 'remove'
						,tooltip    : 'Delete'
						,width      : 30
                        ,Func       : _deleteCol
                    })
				]
				,listeners: {
                    afterrender: function(){
                    }
                }
			});
		}
		
		function _editCol(data, row){
			module.getForm().retrieveData({
				url : route + 'retrieve'
				,params : {
					id				: data.id
				}
				,hasFormPDF	: true
				,success : function( response, match , full) {
					onEdit = 1
					let timed = Ext.dateParse(response.ttime, 'HH:mm A')
					let dated = Ext.dateParse(response.tdate, 'YYYY-MM-DD')

					// check cancel tag if trans is cancelled 
					if ( response.cancelTag == 1 ) { Ext.getCmp( 'cancelTag' + module ).setValue( true ); }

					Ext.valued('ttime'+module, timed.toDate())
					Ext.valued('tdate'+module, dated.toDate())
					Ext.valued('withSalesOrderTag'+module, response.fident != null && response.fident != '')
					Ext.getCmp('refSONumber'+module).store.load({
						params:{
							idAffiliate: idAffiliate,
							transaction_date : `${dated.format('YYYY-MM-DD')} ${timed.format("HH:mm:00")}`,
							with_id: response.fident,
							date: response.tdate
						},
						callback:function(){
							Ext.valued('refSONumber'+module, response.fident)
						}
					})
					Ext.getCmp('customer'+module).store.load({
						callback: function(){

							Ext.valued('customer'+module, parseInt(response.customer, 10))
						}
					})
					Ext.valued('paymentType'+module, parseInt(response.paymentType,10))
					
					
					
					let store = Ext.getCmp('gridItem' + module).store
					store.removeAll()
					store.add(full.released)
					__setTotals()
					
                    //Call this function to manipulate the visibility of transaction buttons aka [Approve/Cancel]
                    // standards2.callFunction('_setTransaction', {
                    //     data : response
                    //     ,module	: module
					// });
					
					/*Set a global variable for the retrieved data. Added by Hazel */
					dataHolder = response;
				}
			})
			Ext.resetGrid('gridHistory'+module)

        }
        
        function _deleteCol(data, row){
			standards.callFunction( '_createMessageBox', {
				msg	    : 'DELETE_CONFIRM'
				,action : 'confirm'
				,fn	    : function( btn ){
					if( btn == 'yes' ) {
						Ext.Ajax.request({
							url	    : route + 'delete'
							,params	: {
								id : data.id
								,idReference	: data.idReference
								,idmodule		: data.idModule
								,referenceNum	: data.referenceNum
							}
							,success : function( response ) {
                                let {match} = Ext.decode( response.responseText );
                                if(match === 2){
                                    standards.callFunction( '_createMessageBox', {
                                        msg : 'DELETE_USED'
                                    });
                                    return false;
                                }
                                standards.callFunction( '_createMessageBox', {
                                    msg : 'DELETE_SUCCESS'
                                });
                                Ext.getCmp( 'gridHistory' + module ).store.load({});							
							}
						});
					}
				}
			})
		}

		function _requireCostCenter( refTag ) {
			if ( refTag != 1 ) { 
				Ext.getCmp( 'idCostCenter' + module ).reset()
				Ext.getCmp( 'idCostCenter' + module ).allowBlank = true
				Ext.getCmp( 'idCostCenter' + module ).labelEl.update('Cost Center:');
			} else {
				Ext.getCmp( 'idCostCenter' + module ).reset()
				Ext.getCmp( 'idCostCenter' + module ).allowBlank = false
				Ext.getCmp( 'idCostCenter' + module ).labelEl.update('Cost Center' + Ext.getConstant('REQ') + ':');
			}
		}

        function _saveForm( form ){
			if(Ext.valued('paymentType'+module) == 2){
				let variance = parseFloat(Ext.valued('variance'+module))
				let balance = parseFloat(Ext.valued('balance'+module))
				if( variance < balance){
					standards.callFunction('_createMessageBox',{ msg: 'You exceed the variance amount.' })
					return false;
				}
			}
			if(!(moment(Ext.valued('tdate'+module)).isSameOrBefore(moment(Ext.valued('duedate'+module))))){
				standards.callFunction('_createMessageBox',{ msg: 'Due date must greater than date.' })
				return false;
			}
			let items = Ext.getCmp('gridItem'+module).store.data.items
			let journals = Ext.getCmp('gridJournalEntry'+module).store.data.items
			if(items.length <= 0){
				standards.callFunction('_createMessageBox',{ msg: 'Please add atleast one item.' })
				return false;
			}
			items = items.map(item=>item.data)
			journals = journals.map(journal=>journal.data)

			if(!items.map(item=>Number(item.qty) > 0).reduce((a,b) => a && b, true)){
                standards.callFunction('_createMessageBox',{ msg: 'Please add qty.' })
				return false;
            }

            if(!items.map(item=>Number(item.price) > 0).reduce((a,b) => a && b, true)){
                standards.callFunction('_createMessageBox',{ msg: 'Please add price.' })
				return false;
            }
				
			form.submit({
				waitTitle	: "Please wait"
				,waitMsg	: "Submitting data..."
				,url		: route + 'save'
				,params		: { 
					items : Ext.encode( items )
					,journals : Ext.encode( journals )
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
                                        ,idAffiliate    : idAffiliate
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
			})
        }

        function _editRecord( data ){
				    module.getForm().retrieveData({
						url: route + 'retrieveData'
						,params: data
						,success:function( response, data ){

						}
					})
        }	

		function __items( config ){
			var itemlist = standards.callFunction( '_createRemoteStore', {
				fields:[
                    {	name	: 'id'
                        ,type	: 'number'
					}
					,'reference'
                    ,'barcode'
					,'classification'
					,'name'
					,'unit'
					,'price'
					,'expiration'
					,'lot'
					,'qtySO'
					,'qty'
					,'remaining'
					,'amount'
					,'salesorder'
					,'cost'
				]
				,url	: route + 'getSOItem'
			});
            return {
                xtype : 'tabpanel'
                ,items: [
                    {
                        title: 'Item Details'
                        ,layout:{
                            type: 'card'
                        }
                        ,items  :   [
							{	xtype : 'container'
								,width : 390
								,items : [
									standards.callFunction( '_gridPanel',{
										id		        : 'gridItem' + module
										,module	        : module
										,store	        : itemlist
										,noDefaultRow   : true
										,noPage         : true
										,plugins        : true
										,tbar : {
											canPrint        : false
											,noExcel        : true
											,route          : route
											,pageTitle      : pageTitle
											,content        : 'add'
											,deleteRowFunc  : _deleteItem
											,extraTbar2:[
												__searchbar('barcode', 'barcode', true)
												,standards.callFunction('_createNumberField',{
													id			: 'qtynum' + module
													,module		: module
													,fieldLabel	: ''
													,allowBlank	: true
													,width		: 75
													,value		: 1
												})
											]
										}
										
										,columns:[
											{	header          : 'SO Number'
												,dataIndex      : 'reference'
												,width          : 100
												,columnWidth    : 20
											}
											,{	header          : 'Code'
												,dataIndex      : 'barcode'
												,width          : 100
												,columnWidth    : 25
												,editor         : __searchbar('barcodecol', 'barcode')
											}
											,{	header          : 'Item Name'
												,dataIndex      : 'name'
												,width          : 150
												,columnWidth    : 60
												,editor         : __searchbar('itemname', 'name')
											}
											,{	header          : 'Classification'
												,dataIndex      : 'classification'
												,width          : 120
												,columnWidth    : 30
											}
											,{	header          : 'Unit'
												,dataIndex      : 'unit'
												,width          : 50
												,columnWidth    : 25
											}
											,{	header          : 'Lot Number'
												,dataIndex      : 'lot'
												,width          : 90
												,columnWidth    : 20
												,editor			: 'text'
												,renderer		: function(val){
													return `<div style="text-align:right">${Number(val) != NaN? Number(val): null}</div>`
												}
											}
											,{	header          : 'Expiry Date'
												,dataIndex      : 'expiration'
												,width          : 100
												,columnWidth    : 25
											}
											,{	header          : 'Price'
												,dataIndex      : 'price'
												,width          : 90
												,columnWidth    : 20
												,editor			: 'float'
												,renderer		: function(val){
													return __number(val? val : 0, 2)
												}
												
											}
											,{	header          : 'Qty'
												,dataIndex      : 'qty'
												,width          : 90
												,columnWidth    : 20
												,editor			: 'text'
												,renderer		: function(val){
													return __number(val? val : 0, 0)
												}
											}
											,{	header          : 'Qty Left'
												,dataIndex      : 'remaining'
												,width          : 90
												,columnWidth    : 20
											}
											,{	header          : 'Amount '
												,dataIndex      : 'amount'
												,width          : 100
												,columnWidth    : 25
												,renderer		: function(val){
													return __number(val? val : 0, 2)
												}
											}
										]
										,listeners :{
											edit : function( me, rowData ) {
												let index = rowData.rowIdx
												,store = this.getStore().getRange();
												let { qty ,remaining, price , lot, salesorder} = rowData.record.data
												remaining = parseFloat(remaining)
												price = parseFloat(price)
												qty = parseFloat(qty)
												lot = parseInt(lot)
												salesorder = parseInt(salesorder)
												if(remaining ==  0){
													standards.callFunction('_createMessageBox',{ msg: 'This item is out of stock.' })	
													store[index].set('qty', 0 )
													store[index].set('amount',  0 )
												}else if(qty > remaining && salesorder > 0){
													standards.callFunction('_createMessageBox',{ msg: 'You\'ve exceeded the maximum stock available for this item.' })	
													store[index].set('qty', 0 )
													store[index].set('amount',  0 )
												}
												else if(qty > remaining){
													standards.callFunction('_createMessageBox',{ msg: 'You\'ve exceeded the maximum stock available for this item.' })	
													store[index].set('qty', 0 )
													store[index].set('amount',  0 )
												}else{
													store[index].set('amount', isNaN(qty)? 0 : qty * price )
												}
												
												store[index].set('lot', lot > 0 ?lot : null )
												__setTotals()
											}
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
                                ,hasPrintOption : 1
                                ,config         : config                                
                                ,idModule		: idModule                                
								,items          : Ext.getCmp('gridItem' + module)                                
								,customer       : 'customer'                            
							})
                        ]
                    }
                ]
            }
		}
		
		function __number(value, decimals){
            return `<div style="text-align:right">${Number(value).toLocaleString('en-GB',{minimumFractionDigits: decimals, maximumFractionDigits: decimals})}</div>`
        }
       
        function _resetForm( form ){
			onEdit = 0
			deletedItems = [];
			form.reset();
			_setDueDateValidation();
			Ext.resetGrid('gridItem'+module)
			Ext.resetGrid('gridJournalEntry'+module)
			Ext.getCmp('tdate'+module).fireEvent( 'afterrender' );
			// document.getElementById( 'transactionStatus' + module ).innerHTML = '<span style="color:red; font-weight: bold;">Not Yet Confirmed</span>';
        }
		
		/* This is for Form PDF Printing. Added by: Hazel */
		function _printPDF(){
			var par  = standards.callFunction('getFormDetailsAsObject',{ module : module })
			,itemStore = Ext.getCmp('gridItem'+module).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0);

			Ext.Ajax.request({
                url			: route + 'printPDF'
                ,method		:'post'
                ,params		: {
                    moduleID    	: 7
                    ,title  		: pageTitle
                    ,limit      	: 50
                    ,start      	: 0
					,printPDF   	: 1
					,form			: Ext.encode( par )
					,items			: Ext.encode( itemStore )
					// ,hasPrintOption : Ext.getCmp('printStatusJEgridJournalEntry' + module).getValue()
                    ,idInvoice		: dataHolder.idInvoice
                    ,idAffiliate	: dataHolder.idAffiliate
                }
                ,success	: function(response, action){
                    if( isGae == 1 ){
						window.open(route+'viewPDF/' + pageTitle ,'_blank');
					}else{
						window.open('pdf/inventory/'+ pageTitle +'.pdf');
					}
                }
			});
		}
		
  		function __searchbar(name, displayed, hide){
			var itemlist = standards.callFunction( '_createRemoteStore', {
				fields:[
                    {	name	: 'id'
                        ,type	: 'number'
					}
                    ,'barcode'
					,'classification'
					,'name'
					,'unit'
					,'price'
					,'expiration'
					,'lot'
					,'remaining'
					,'qty'
					,'amount'
					,'salesorder'
				]
				,url	: route + 'getItems'
			});
			return standards.callFunction( '_createCombo', {
                id				: name + module
                ,store          : itemlist
                ,module			: module
                ,allowBlank		: true
                ,width			: 300
                ,displayField   : displayed || 'barcode'
                ,valueField     : displayed || 'barcode'
                ,emptyText		: `Search ${displayed || 'barcode'}...`
                ,hideTrigger	: hide
                ,labelWidth     : 100
                ,listeners		: {
                    beforeQuery	: function(){
						let  {0 : selected} = Ext.getCmp('gridItem' + module).getSelectionModel().getSelection();
						let items = Ext.getCmp('gridItem' + module).store.data.items.map(item=> item.data.id)
						if(selected){
							let selectedid = parseInt(selected.data.id, 10)
							items = items.filter(function(val){return val != selectedid})
						}
                        this.store.proxy.extraParams = {
							idAffiliate	: idAffiliate
							,tdate		: Ext.Date.format( Ext.valued( 'tdate' + module ) , 'Y-m-d' )
							,qty		: name == 'barcode' ? Ext.valued('qtynum'+module): 1
							,items		: JSON.stringify(items)
							,from		: displayed
						}
					}
					,select : function(){
						let row = this.findRecord(this.valueField, this.getValue())
						let {store} = Ext.getCmp('gridItem'+module)
						if(name == 'barcode'){
							store.add(row)
							this.setValue(null)
						}
						else{
							let  {0 : store} = Ext.getCmp('gridItem' + module).selModel.getSelection()
                            Ext.setGridData(['id' ,'barcode' ,'classification' ,'name' ,'unit' ,'price' ,'expiration' ,'lot' ,'remaining' ,'amount'],store, row)
						}
						__setTotals()
					}
					,focus: function(){
						let  {0 : selected} = Ext.getCmp('gridItem' + module).getSelectionModel().getSelection();
						if(name !== 'barcode'){
							if(selected) {
								if(Ext.getCmp(name + module).store.getById(selected.data.id) == null){
									Ext.getCmp(name + module).store.add(selected.data)
								}
								this.setValue(selected.data[this.displayField])
							}
						}
						
					}
					,blur: function(){
						var  {0 : selected} = Ext.getCmp('gridItem' + module).getSelectionModel().getSelection();
						if(selected){
							if(Ext.getCmp(name + module).store.getById(selected.data.id) != null){
								var indexmap = Ext.getCmp(name + module).store.data.indexMap
								Ext.getCmp(name + module).store.removeAt(indexmap[selected.data.id])
							}
						}
					}
                }
            })
			
		}
		           
		function _deleteItem( data ){
			deletedItems.push( data );
			selectedItem.splice( selectedItem.indexOf(data.idItem), 1);
			Ext.getCmp('gridItem'+module).store.remove(Ext.getCmp('gridItem'+module).store.findRecord('id', data.id));
			__setTotals()
		}

		function __setTotals(){
			let items = Ext.getCmp('gridItem'+module).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0)
			total = items.length ?  items.reduce((sum, item) =>( sum + (Number(item.amount))), 0) : 0
			Ext.valued('sales'+module, total)

			var vatPercent	= Ext.valued( 'r_vatPercent' + module );
			var vatType		= Ext.valued( 'r_vatType' + module );

			let vatValue = parseFloat(total) * parseFloat(vatPercent / 100)
			Ext.valued('vatamount'+module, vatValue)
			let totalWithVat = vatType == 2? total+vatValue : total
			Ext.valued('total'+module, totalWithVat)
			let discountPercent = parseFloat(Ext.valued('discountper'+module))
			let discountValue = parseFloat(totalWithVat * (discountPercent/100))
			Ext.valued('discount'+module, discountValue)
			let overAllTotal = totalWithVat - discountValue
			Ext.valued('totalamnt'+module, overAllTotal)
			Ext.valued('balance'+module, parseFloat(Ext.valued('totalamnt'+module)) - parseFloat(Ext.valued('downpayment'+module)))
		}

		function _transactionHandler( status ){
			standards.callFunction( '_createMessageBox', {
				msg		: `Are you sure you want to ${status === 2 ? 'approve': 'cancel'} this transaction?`
				,action	: 'confirm'
				,fn		: function( btn ){
					if( btn == 'yes' ){
                        Ext.Ajax.request({
                            url : route + 'updateTransaction'
                            ,params : {
                                status 		: status
                                ,idInvoice 	: Ext.getCmp('idInvoice'+module).value
                            }
                            ,success : function( response ){
        
                                if( Ext.decode( response.responseText ).match == 1 ) {
                                    standards.callFunction('_createMessageBox',{ msg: 'EDIT_USED' });
                                } else {
                                    standards.callFunction('_createMessageBox',{ 
                                        msg: `Transaction has been ${status === 2  ? 'approved' : 'cancelled'}` 
                                        ,fn : function() {
                                            standards2.callFunction('_setTransaction', { module	: module ,data : { status : status }});
                                        }
                                    });
                                }
                            }
                        });
					}
				}
			} );
		}

		function _setDueDateValidation() {
            var terms = Ext.getCmp( 'terms' + module );
            var tdate = Ext.getCmp( 'tdate' + module );
			tdate.setMaxValue( new Date() );
            if( terms ) {
                dueDate = Ext.Date.add( tdate.value , Ext.Date.DAY , terms.value );
                Ext.getCmp( 'duedate' + module ).setMinValue( dueDate );
                Ext.getCmp( 'duedate' + module ).setMaxValue( dueDate );
                Ext.getCmp( 'duedate' + module ).setValue( dueDate );
            } else {
                Ext.getCmp( 'duedate' + module ).setMinValue( tdate.value )
                Ext.getCmp( 'duedate' + module ).setMaxValue( tdate.value )
                Ext.getCmp( 'duedate' + module ).setValue( tdate.value );
            }
        }

		function _customListPDF() {
			Ext.Ajax.request({
				url  		: route + 'customListPDF'
				,params 	: { 
					items : Ext.encode( Ext.getCmp('gridHistory'+module).store.data.items.map((item)=>item.data) )
				}
				,success 	: function(response){
					if( isGae == 1 ){
						window.open(route+'viewPDF/Sales List','_blank');
					}else{
						window.open('pdf/inventory/Sales List.pdf');
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
				pageTitle 	= config.pageTitle;
				isGae 		= config.isGae;
				canEdit 	= config.canEdit;
				canCancel	= config.canCancel;
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