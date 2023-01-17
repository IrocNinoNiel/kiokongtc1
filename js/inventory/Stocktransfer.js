/**
 * Developer: Mark Reynor D. Magriña
 * Module: Stocktransfer Module
 * Date: Feb 04, 2020
 * Finished:
 * Description:
 * DB Tables:
 * */
var Stocktransfer = function(){
    return function(){
        var route, module, canDelete, idAffiliate, canPrint, pageTitle, isGae, canEdit = 0, idModule = 0,onEdit = 0,invalidDate = 0,onEditContributionQuickSettings=0;
		var employmentChange = 0,_userName,idUserValue;
		var deletedItems = [];
		var selectedItem = [];
		var idEMPLOYEE = 0;
		var allowSave = 0;
		var totalCollection = 0;
		var totalPayment = 0;

        function _init(){
        }

        function _mainPanel( config ){

			return standards2.callFunction(	'_mainPanelTransactions' ,{
                config		: config
				,moduleType	: 'form'
				,tbar       : {
					saveFunc        : _saveForm
					,resetFunc      : _resetForm
					,formPDFHandler : _printPDF
					,hasFormPDF     : true
					,filter : {
                        searchURL       : route + 'searchHistoryGrid'
						,emptyText      : 'Search reference here...'
						,module         : module
                    }
                }
                ,formItems  : [
					standards2.callFunction( '_transactionHeader', {
						module			: module
						,idModule		: idModule
						,idAffiliate	: idAffiliate
						,config			: config
						,fieldLabelST	: 'Transferred from'
						,moduleCompareFrom	:{
							// date: 'dueDate'+module
						}
					} )

					/* Editing started here  */
					,{
						xtype      	: 'fieldset'
						,title      : ''
						,layout     : 'column'
						,padding    : 10
						,items      : [
							{
								xtype   : 'container'
								,layout : 'vbox'
								,style  : "margin-bottom: 5px;"
								,columnWidth    : .5
								,items  : [
									standards.callFunction('_createTextField',{
										id			: 'idInvoice' + module
										,allowBlank	: true
										,hidden		: true
										,value		: 0
									})
									,standards2.callFunction('_createAffiliateCombo',{
										id			: 'pCode' + module
										,fieldLabel	: 'Transferred to'
										,width		: 350
									})
									,standards.callFunction('_createTextField',{
										id			: 'transferredBy' + module
										,fieldLabel	: 'Transferred by'
										,allowBlank	: false
										,width      : 350
										,readOnly	: true
										,value		: _userName
									})
								]
							}
							,{
								xtype   	: 'container'
								,layout 	: 'vbox'
								,layout 	: 'column'
								,columnWidth: .5
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
					,_tabPanelGrids( config )
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
                    { name	: 'idInvoice' ,type : 'int' }
                    ,'reference','date','sourceAffiliate','receiverAffiliate','notedByName'
				]
                ,url : route + 'getStockTransferDetails'
            } );

            return standards.callFunction( '_gridPanel', {
                id              : 'gridHistory' + module
                ,module         : module
                ,store          : store
                ,width          : 350
                ,noDefaultRow   : true
                ,columns        : [
					{ 	header: 'Reference'
						,dataIndex: 'reference'
						,width      : 150
						,columnWidth : 15
					}
                    ,{  header      : 'Date'
						,dataIndex  : 'date'
						,xtype		: 'datecolumn'
						,format		: 'm/d/Y'
                        ,width      : 150
						,columnWidth : 15
                    }
					,{  header      : 'Source Affiliate'
                        ,dataIndex  : 'sourceAffiliate'
                        ,width      : 130
						,minWidth	: 100
						,flex		: 1
						,columnWidth : 35
                    }
                    ,{  header      : 'Receiving Affiliate'
                        ,dataIndex  : 'receiverAffiliate'
                        ,width      : 130
						,minWidth	: 100
						,flex		: 1
						,columnWidth : 35
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

		function _tabPanelGrids( config ){

			var gridListPaymentStore = standards.callFunction('_createRemoteStore',{
				fields: [
					{ name: 'salesIDInvoice', type: 'number' }
					,{ name: 'balance', type: 'number' }
					,{ name: 'collections', type: 'number' }
					,{ name: 'receivables', type: 'number' }
					,'reference' ,'date'
				]
				,url: route + 'getGridListForPayments'
			})

            return {
                xtype 	: 'tabpanel'
				,style	: 'margin-top: 20px;margin-bottom: 20px;'
                ,items	: [
					{
						title	: 'Item details'
						,layout	: {
							type: 'card'
						}
						,items: [
							_itemListGrid()
						]
					}
					,{
                        title	: 'Journal Entries'
                        ,layout	:{
                            type: 'card'
                        }
                        ,items  :   [
                            standards.callFunction( '_gridJournalEntry',{
								module	        : module
                                ,hasPrintOption : 1
                                ,config         : config
                                ,items          : Ext.getCmp('gridItemList' + module)                            })
                        ]
                    }
                ]
            }
		}

		function _itemListGrid(){

			var storeGridItemList = standards.callFunction('_createRemoteStore',{
				fields : [
					'itemName'
					,'unitName'
					,'date'
					,'lotnumber'
					,'barcode'
					,'releaseWithoutQty'
					, { name: 'qtyTransferred',		type: 'number' }
					, { name: 'qtyReceived', 		type: 'number' }
					, { name: 'idStockTransfer',	type: 'number' }
					, { name: 'idReceiving',		type: 'number' }
					, { name: 'idItem', 			type: 'number' }
					, { name: 'availQty', 			type: 'number' }
					, { name: 'itemPrice' , 		type: 'number' }
					, { name: 'cost', 				type: 'number' }
					, { name: 'totalAmount', 		type: 'number' }
				]
				,url: route + 'getItemListDetails'
			})
			var itemStore = standards.callFunction( '_createRemoteStore', {
				fields		: [
					'itemName', 'unitName', 'qty', 'expiryDate', 'barcode', 'releaseWithoutQty'
					, { name: 'availQty'	, type: 'number' }
					, { name: 'idReceiving'	, type: 'number' }
					, { name: 'itemPrice'	, type: 'number' }
					, { name: 'cost'		, type: 'number' }
					, { name: 'idItem'		, type: 'number' }
					, { name: 'totalAmount' , type: 'number' }
				]
				,url		: route + 'getItems'
            } );
			return standards.callFunction('_gridPanel',{
				id				: 'gridItemList' + module
				,module			: module
				,store			: storeGridItemList
				,height			: 450
				,noPage			: true
				,plugins		: true
				,noDefaultRow	: true
				,tbar			: {
					canPrint	: false
					,noExcel	: true
					,content	: 'add'
					,extraTbar2 : [
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
									// var idAffiliate = Ext.getCmp('idAffiliate' + module).getValue();

									// if( idAffiliate != null ) {
										// _setExtraParams(
												// itemStore,
												// {
													// qty             : Ext.getCmp('unitnum' + module).value
													// ,idAffiliate    : idAffiliate
													// ,idSupplier     : Ext.getCmp('pCode' + module).value
												// } );
									// } else {
										// standards.callFunction('_createMessageBox', { msg : 'Please select a Supplier a first.'});
									// }
									// itemStore.proxy.extraParams.date = Ext.getCmp( 'tdate' + module ).getValue();

									// computeTotalDetails


									itemStore.proxy.extraParams.idAffiliate = idAffiliate
									_beforeQueryFuntion(itemStore)

								}
								,select: function( combo, records, eOpts ){
									var row = this.findRecord(this.valueField, this.getValue())
									var msg = 'The item is already selected. If you still want to select the item, you can just add quantity to the row.';

									_filterAvailQty( parseInt(records[0].data.availQty) );

									if( Ext.isUnique(this.valueField, storeGridItemList, this, msg ) ) {
										let unitNumber = Ext.getCmp('unitnum' + module);
										row.set('qtyTransferred', unitNumber.getValue());
										row.set('qtyReceived', 0 );
										// row.set('referenceNum', 'None');
										storeGridItemList.add(row);

										Ext.getCmp('searchbarcode' + module).reset();
										unitNumber.reset();
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
				,columns	: [
						{
							header		: 'Code'
							,dataIndex	: 'barcode'
							,editor		: 'text'
							,minWidth	: 100
							,editor		: standards.callFunction('_createCombo',{
								fieldLabel		: ''
								,id				: 'itemCode' + module
								,store			: itemStore
								,emptyText		: 'Select Item...'
								,displayField	: 'barcode'
								,valueField		: 'barcode'
								,listeners		: {
									beforeQuery: function( me ){
										_beforeQueryFuntion(itemStore)
									}
									,select : function( me, recordDetails, returnedData ){
										if( _filterAvailQty( me, recordDetails[0].data.availQty, 0  ) ){
											let { 0 : store } = Ext.getCmp('gridItemList' + module).selModel.getSelection()
											,row	= me.findRecord(me.valueField, me.getValue()) ;
											Ext.setGridData(['idItem','availQty','barcode','itemName','unitName', 'cost', 'itemPrice', 'idReceiving', 'totalAmount'], store, row );
										}
									}
								}
							})
						}
						,{  header      : 'Item Name'
							,dataIndex  : 'itemName'
							,minWidth	: 150
							,flex		: 1
							// ,xtype      : 'datecolumn'
							,editor		: standards.callFunction('_createCombo',{
										fieldLabel		: ''
										,id				: 'typeId' + module
										,store			: itemStore
										,emptyText		: 'Select Item...'
										,displayField	: 'itemName'
										,valueField		: 'itemName'
										,listeners		: {
											beforeQuery: function( me ){
												_beforeQueryFuntion(itemStore)
												itemStore.proxy.extraParams.itemNameIndicator = true;
											}
											,select: function( me, recordDetails, returnedData ){
												if( _filterAvailQty( me, recordDetails[0].data.availQty, 1  ) ){
													let { 0 : store } = Ext.getCmp('gridItemList' + module).selModel.getSelection();
													store.set('qtyTransferred', 0 )

													Ext.setGridData(
													[
														'idItem'
														, 'availQty'
														, 'barcode'
														, 'itemName'
														, 'unitName'
														, 'cost'
														, 'itemPrice'
														, 'idReceiving'
														, 'releaseWithoutQty'
													], store,this.findRecord(this.valueField, this.getValue() ))
												}
											}
										}
									})
						}
						,{
							header		: 'Unit of Measure'
							,dataIndex	: 'unitName'
							,width		: 100
						}
						,{
							header		: 'Lot Number'
							,dataIndex	: 'lotnumber'
							,width		: 100
							,editor		: 'text'
						}
						,{
							header		: 'Expiry Date'
							,dataIndex	: 'date'
							,width		: 100
							,xtype      : 'datecolumn'
							,format     : 'Y-m-d'
							,editor     : 'date'
						}
						,{
							header		: 'Qty Transferred'
							,dataIndex	: 'qtyTransferred'
							,width		: 150
							,xtype		: 'numbercolumn'
							,format		: '0,000'
							,editor		: 'number'
							,hasTotal	: 1
						}
						,{
							header		: 'Qty Received'
							,dataIndex	: 'qtyReceived'
							,width		: 150
							,xtype		: 'numbercolumn'
							,format		: '0,000'
							,editor		: 'number'
							,hasTotal	: 1
						}
						,{
							header		: 'Cost'
							,dataIndex	: 'cost'
							,width		: 100
							,xtype		: 'numbercolumn'
							,hasTotal	: 1
						}
						,{
							header			: 'Total Amount'
							,dataIndex		: 'totalAmount'
							,width			: 100
							,xtype			: 'numbercolumn'
							,summaryType    : 'sum'
						}
						// ,{
						// 	header		: 'Item Price'
						// 	,dataIndex	: 'itemPrice'
						// }
						// ,{
						// 	header		: 'idReceiving'
						// 	,dataIndex	: 'idReceiving'
						// }
				]
				,listeners :{
					beforeedit : function( editor, e ){
						var canEdit = 0;

						/* This is the setting for releasing column available for editing */
						if( ( canEdit == 0 && e.colIdx == 3 ) || ( canEdit == 0 && e.colIdx == 4 ) || ( canEdit == 0 && e.colIdx == 6 ) ) { return false; }


						/* Edit this line of commands here once edit record is available */
						if ( (canEdit == 1 && e.colIdx == 0) || (canEdit == 1 && e.colIdx == 1) || (canEdit == 1 && e.colIdx == 3) || (canEdit == 1 && e.colIdx == 5) ){
							return true;
						}

						if (e.field == "qtyTransferred" || e.colIdx == 5){
							let row = e.record.data;
							if(row.releaseWithoutQty == 1 && row.availQty == 0) {
								standards.callFunction('_createMessageBox',{ msg: 'This item does not have quantity left.' });
								return false;
							}
						}

						// if ( canEdit == 1 && e.colIdx == 1 ){ return false; }
						// if ( canEdit == 1 && e.colIdx == 5 ){ return false; }
                    }
					,edit : function( me, rowData ){
						var index = rowData.rowIdx
						,store = this.getStore().getRange();
						var totalAmount = 0;
						if( rowData.field == 'qtyTransferred' ){
							totalAmount = ( store[index].data.qtyTransferred > 0 ? rowData.value * store[index].data.cost : 0)
							store[index].set('totalAmount', totalAmount );
						}
					}
				}
			})
		}

		function _filterAvailQty( me, params, type ){
			let row = me.findRecord(me.valueField, me.getValue());
			if ( params <= 0 && row.data.releaseWithoutQty == 0){
				standards.callFunction('_createMessageBox',{ msg: 'This item does not have enough quantity left, please select another item.' });
				(type == 1) ? me.setValue('') : me.setValue(0) ;
				return false;
			} else {
				return true;
			}
		}

		function _beforeQueryFuntion(itemStore){
			itemStore.proxy.extraParams.idAffiliate = idAffiliate
			itemStore.proxy.extraParams.itemNameIndicator = "";
			itemStore.proxy.extraParams.date = Ext.Date.format( Ext.getCmp( 'tdate' + module ).getValue(), 'Y-m-d') +' '+ Ext.Date.format( Ext.getCmp( 'ttime' + module ).getValue(), 'H:i:s' )

			// itemStore.proxy.extraParams.date = Ext.getCmp( 'tdate' + module ).getValue();
			// itemStore.proxy.extraParams.time = Ext.getCmp( 'ttime' + module ).getValue();
		}

        function _saveForm( form ){

			var idAffiliateFromCmb = idAffiliate
			var idAffiliateToCmb = Ext.getCmp( 'pCode' + module ).getValue();
			if( idAffiliateFromCmb == idAffiliateToCmb ){
				standards.callFunction('_createMessageBox',{
					msg: 'Transferred from affiliate cannot be the same with the trasnferred to affiliate.'
				})
				return false;
			}

			/* Container for all transferred items */
			var gridItemListContainer = new Array();
			var validItemCounter = 0;
			Ext.getCmp( 'gridItemList' + module ).store.each( function( item ){
				if( (item.data.barcode != '' && item.data.barcode != null && item.data.qtyTransferred > 0) || item.data.releaseWithoutQty == 1){
					gridItemListContainer.push( item.data )
					validItemCounter += 1;
				}
			} )
			if( gridItemListContainer.length == 0 || validItemCounter == 0 ){
				standards.callFunction('_createMessageBox',{ msg: 'No stock transfer made, please transfer at least one item.' })
				return false;
			}

			//Container for all Journal entries
			var journalDetailsContainer 	= new Array();
			Ext.getCmp( 'gridJournalEntry' + module ).store.each( function( item ) { journalDetailsContainer.push( item.data ) } )

			form.submit({
				waitTitle	: "Please wait"
				,waitMsg	: "Submitting data..."
				,url		: route + 'saveStockTransferForm'
				,params		: {
					gridItemList 		: Ext.encode( gridItemListContainer )
					,journalDetails 	: Ext.encode( journalDetailsContainer )
					,date				: Ext.getCmp( 'tdate' + module ).value
					,idModule			: idModule
					// ,transfererAffiliate 	: Ext.getCmp( 'idAffiliate' + module ).getRawValue
					,receiverAffiliate 	: Ext.getCmp( 'pCode' + module ).getRawValue()
					// ,time			: Ext.getCmp( 'ttime' + module).getValue()
				}
				,success:function( action, response ){
					var matchValue = parseInt(response.result.match);
					var msgs = '';
					if( matchValue == 2 ){  msgs = 'Stock transfer ID do not exist, please select another reference.';  }
					else{ msgs = 'Record has been successfully saved.'; }
					standards.callFunction('_createMessageBox',{ msg: msgs })
					_resetForm();
				}
			})
        }

        function _editRecord( data ){

			module.getForm().retrieveData({
				url			: route + 'retrieveData'
				,params		: data
				,hasFormPDF	: true
				,success	:function( response, data ){
					dataHolder = response;
					// Comment for now
					//Call this function to manipulate the visibility of transaction buttons aka [Approve/Cancel]
					// standards2.callFunction('_setTransaction', {
					// 	data 	: response
					// 	,module	: module
					// });
					/* Load the Item list grid thru the Invoice ID of the stock transfer */
					Ext.getCmp( 'gridItemList' + module ).store.load({
						params: { idInvoice: parseInt(response.idInvoice) }
						,callback: function( me, otherData ){ }
					})
					/* Load all journal entries thru the Invoice ID of the cashreceipts */
					Ext.getCmp( 'gridJournalEntry' + module ).store.load({
						params: { idInvoice: parseInt( response.idInvoice ) }
						,callback: function(){}
					});
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
							url		: route + 'deleteStockTransferRecord'
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

        function _resetForm( form ){
			// deletedItems = [];
			Ext.getCmp('mainFormPanel' + module).getForm().reset();
			onEdit = 0;
			totalCollection = 0;
			totalPayment = 0;



			Ext.resetGrid( 'gridItemList'+module );
			// Ext.resetGrid( 'gridPaymentDetails'+module );
			Ext.resetGrid( 'gridJournalEntry' + module );
			Ext.getCmp( 'gridHistory' + module ).store.load();
        }

		function _printPDF(){
			var par  = standards.callFunction('getFormDetailsAsObject',{ module : module })
            ,receivingItems = Ext.getCmp('gridItemList'+module).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0)
            ,journalEntries = Ext.getCmp('gridJournalEntry'+module).store.data.items.map((item)=>item.data);

			Ext.Ajax.request({
                url			: route + 'generatePDF'
                ,method		:'post'
                ,params		: {
                    moduleID    	: idModule
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
						window.open(route+'viewPDF/Stock Transfer','_blank');
					}else{
						window.open('pdf/inventory/Stock Transfer.pdf');
					}
                }
			});
		}


        return{
			initMethod:function( config ){
				route 		= config.route;
				module 		= config.module;
				canDelete 	= config.canDelete;
				canPrint 	= config.canPrint;
				pageTitle 	= config.pageTitle;
				isGae 		= config.isGae;
				canEdit 	= config.canEdit;
				idModule 	= config.idmodule;
				idUserValue = config.idUserValue
				idEMPLOYEE	= config.idEMPLOYEE
				idAffiliate = config.idAffiliate
				_userName	= config._userName

				return _mainPanel( config );
			}
		}
    }
}