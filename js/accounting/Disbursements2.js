/**
 * Developer: Mark Reynor D. Magriña
 * Module: Disbursements Module
 * Date: Feb 10, 2020
 * Finished: 
 * Description: 
 * DB Tables: 
 * */ 
var Disbursements = function(){
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
			
			// console.log( idModule );
			
			supplierCmbStore = standards.callFunction('_createRemoteStore',{
				fieldLabel 	: [ { name:'id', type: 'number' },'name' ]
				,url		: route + 'getSupplier'
			})
			
			   // ,supplierStore = standards.callFunction( '_createRemoteStore', {
				// fields		: [ { name: 'id', type: 'number' }, 'name' ]
				// ,url		: route + 'getSupplier'
				// ,autoLoad	: true
			// } );
			
			return standards2.callFunction(	'_mainPanelTransactions' ,{
                config		: config
				,moduleType	: 'form'
				,tbar       : {
					saveFunc        : _saveForm
					,resetFunc      : _resetForm
					,formPDFHandler : _printPDF
					,hasFormPDF     : true
					,filter:{
						filterByData	: [
							{	'name'				: 'Reference number'
								,'tableNameColumn'	: 'idReferenceSeries'
								,'tableIDColumn'	: 'idInvoice'
								,'tableName'		: 'invoices'
								,'isDateRange'		: 0
								,'defaultValue'		: 'All'
							}
						]
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
								,style  : "width:705px;margin-bottom: 5px;"
								,items  : [
									standards.callFunction('_createTextField',{
										id			: 'idInvoice' + module
										,allowBlank	: true
										,hidden		: true
										,value		: 0
									})
									,standards.callFunction('_createSupplierCombo',{
										module		: module
										,store      : supplierCmbStore
										,fieldLabel : 'Supplier'
										,listeners	: {
											beforeQuery: function(){
												supplierCmbStore.proxy.extraParams.idAffiliate = Ext.getCmp( 'idAffiliate' + module ).getValue();
											}
											
										}
									})
								]
							}
							,{  	
								xtype   : 'container'
								,layout : 'vbox'
								,layout : 'column'
								,width	: 430
								,items  : [
									 standards.callFunction( '_createTextArea', {
                                        id          : 'remarks' + module
                                        ,fieldLabel : 'Remarks'
										,labelWidth	: 117
										,width     	: 350
										,height		: 50
                                        ,allowBlank : true
                                    } )
								]
							}
						]
					}
					,_itemListGrid()
					,_tabPanelGrids()
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
                    ,'reference','date','sourceAffiliate','receiverAffiliate','preparedByName','notedByName','status'
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
						,width      : 100
					}
                    ,{  header      : 'Date'
                        ,dataIndex  : 'date'
                        ,width      : 80
                    }
					,{  header      : 'Source Affiliate'
                        ,dataIndex  : 'sourceAffiliate'
                        ,width      : 130
						,minWidth	: 100
						,flex		: 1
                    }
                    ,{  header      : 'Receiving Affiliate'
                        ,dataIndex  : 'receiverAffiliate'
                        ,width      : 130
						,minWidth	: 100
						,flex		: 1
                    }
                    ,{  header      : 'Prepared By'
                        ,dataIndex  : 'preparedByName'
                        ,width      : 100
						,minWidth	: 100
						,flex		: 1
                    }
                    ,{  header      : 'Noted By'
                        ,dataIndex  : 'notedByName'
                        ,width      : 100
						,minWidth	: 100
						,flex		: 1
                    }
                    ,{  header      : 'Status'
                        ,dataIndex  : 'status'
                        ,width      : 80
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

		function _itemListGrid(){
			
			var storeGridItemList = standards.callFunction('_createRemoteStore',{
				fields : [
					'itemName' , 'unitName' ,'date' ,'lotnumber' ,{ name: 'barcode', type: 'number'},{ name: 'qtyTransferred', type: 'number'}
					,{ name: 'qtyReceived', type: 'number'},{ name: 'idStockTransfer', type: 'number'} ,{ name: 'idItem', type: 'number'} 
				]
				,url: route + 'getItemListDetails'
			})
			var itemStore = standards.callFunction( '_createRemoteStore', {
				fields		: [ { name: 'idItem', type: 'number' },{ name: 'barcode', type: 'number'}, 'itemName', 'unitName', 'qty', 'expiryDate' ]
				,url		: route + 'getItems'
            } );
			return standards.callFunction('_gridPanel',{
				id				: 'gridItemList' + module
				,module			: module
				,style			: 'margin-top: 20px;'
				,store			: storeGridItemList
				,height			: 300
				,noPage			: true
				,plugins		: true
				,noDefaultRow	: true
				,tbar			: {
					canPrint			: false
					,noExcel			: true
					,content			: 'add'
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
										itemStore.proxy.extraParams.idAffiliate = Ext.getCmp( 'idAffiliate' + module ).getValue();
									}
									,select : function( me, recordDetails, returnedData ){
										var validateDuplicateAtMainGrid = Ext.getCmp( 'gridItemList' + module ).getStore();
										var row = validateDuplicateAtMainGrid.findExact( 'barcode', this.getValue());
										if ( row > -1 ){
											msgs='Duplicate item code found, please select another item code.';
											this.setValue( null );
											standards.callFunction('_createMessageBox',{ msg: msgs })
											return false
										}
										var gridMain = Ext.getCmp( 'gridItemList' + module );
										var recordMain = gridMain.getSelectionModel().getSelection()[0];
										recordMain.set( 'itemName', recordDetails[0].data.itemName );
										recordMain.set( 'unitName', recordDetails[0].data.unitName );
										recordMain.set( 'idItem', recordDetails[0].data.idItem );
										
										// console.log(recordDetails[0].data)
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
												itemStore.proxy.extraParams.idAffiliate = Ext.getCmp( 'idAffiliate' + module ).getValue();
											}
											,select: function( me, recordDetails, returnedData ){
												var validateDuplicateAtMainGrid = Ext.getCmp( 'gridItemList' + module ).getStore();
												var row = validateDuplicateAtMainGrid.findExact( 'itemName', this.getValue());
												if ( row > -1 ){
													msgs='Duplicate item name found, please select another item name.';
													this.setValue( null );
													standards.callFunction('_createMessageBox',{ msg: msgs })
													return false
												}
												var gridMain = Ext.getCmp( 'gridItemList' + module );
												var recordMain = gridMain.getSelectionModel().getSelection()[0];
												recordMain.set( 'barcode', recordDetails[0].data.barcode );
												recordMain.set( 'unitName', recordDetails[0].data.unitName );
												recordMain.set( 'idItem', recordDetails[0].data.idItem );
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
				]
				,listeners :{
					beforeedit : function( editor, e ){
						var canEdit = 0;
						// console.log(canEdit)
						
						/* Edit this line of commands here once edit record is available */
						if ( (canEdit == 1 && e.colIdx == 0) || (canEdit == 1 && e.colIdx == 1) || (canEdit == 1 && e.colIdx == 3) || (canEdit == 1 && e.colIdx == 5) ){ return false; }
						if( canEdit == 0 && e.colIdx == 6 ) { return false; }
						
						// if ( canEdit == 1 && e.colIdx == 1 ){ return false; }
						// if ( canEdit == 1 && e.colIdx == 5 ){ return false; }
                    }
				}
			})			
		}
		
		function _tabPanelGrids(){
			
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
                        title	: 'Journal Entries'
                        ,layout	:{
                            type: 'card'
                        }
                        ,items  :   [
                            standards.callFunction( '_gridJournalEntry',{
                                moduleID		: 'gridJournal' + module
                                ,module	        : module
								,hasPrintOption	: 1
                            })
                        ]
                    }
                ]
            }
		}

        function _saveForm( form ){
				
				// console.log(form);
				// console.log(idModule);
				// return false;
				
				var idAffiliateFromCmb = Ext.getCmp( 'idAffiliate' + module ).getValue();
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
					if( item.data.barcode > 0 && item.data.qtyTransferred > 0 ){ 
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
				Ext.getCmp( 'grid_gridJournalEntry' + module ).store.each( function( item ) { journalDetailsContainer.push( item.data ) } )	
				
				
				
				
				// return false;


				
				form.submit({
					waitTitle	: "Please wait"
					,waitMsg	: "Submitting data..."
					,url		: route + 'saveStockTransferForm'
					,params		: {
						gridItemList : Ext.encode( gridItemListContainer )
						,journalDetails 	: Ext.encode( journalDetailsContainer )
						,date				: Ext.getCmp( 'tdate' + module ).value
						,idModule			: idModule
						// ,transfererAffiliate 	: Ext.getCmp( 'idAffiliate' + module ).getRawValue 
						,receiverAffiliate 	: Ext.getCmp( 'pCode' + module ).getRawValue() 
						// ,time			: Ext.getCmp( 'ttime' + module).getValue()
					}
					,success:function( action, response ){						
						// console.log('Success...');
						// console.log(response);
						// console.log(response.result.match);
						
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
					// console.log(data);
					// return false;
			
				    module.getForm().retrieveData({
						url: route + 'retrieveData'
						,params: data
						,success:function( response, data ){
							// console.log( response );
							
							//Call this function to manipulate the visibility of transaction buttons aka [Approve/Cancel]
							standards2.callFunction('_setTransaction', {
								data 	: response
								,module	: module
							});
							
							/* Load the Item list grid thru the Invoice ID of the stock transfer */
							Ext.getCmp( 'gridItemList' + module ).store.load({
								params: { idInvoice: parseInt(response.idInvoice) }
								,callback: function( me, otherData ){ }
							}) 
							
							/* Load all journal entries thru the Invoice ID of the cashreceipts */
							Ext.getCmp( 'grid_gridJournalEntry' + module ).store.load({
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
			Ext.resetGrid( 'grid_gridJournalEntry' + module );
			Ext.getCmp( 'gridHistory' + module ).store.load(); 
        }
		
		function _printPDF(){
			
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