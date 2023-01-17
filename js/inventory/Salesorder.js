function Salesorder(){
    return function(){
        var baseurl, config, route, module, canDelete, canCancel, pageTitle, idModule, isGae,isSaved = 0,deletedItems = [],selectedItem = [], idAffiliate, dataHolder , selRec , canPrint, componentCalling;

        function _init(){
			if ( selRec ) {
				_edit( { data: selRec , id:selRec.idInvoice } );
			}
        }

        function _mainPanel( config ){
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

            return standards2.callFunction(	'_mainPanelTransactions' ,{
                config		: config
				,module		: module
				,moduleType	: 'form'
				,orientation : 'L'
				,hasApproved: false
				,transactionHandler : _transactionHandler
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
                        searchURL	: route + 'searchHistoryGrid'
						,emptyText	: 'Search reference here...'
						,module 	: module
                    }
                }
                // ,minWidth   : 1350
                ,formItems  :[
					standards2.callFunction( '_transactionHeader', {
						module					: module
						,containerWidth			: '100%'
						,idModule				: idModule
						,dFieldLabel			: 'Date'
						,idAffiliate			: idAffiliate
						,moduleCompareFrom		: {
							date	: 'pickupdate'+module
							,time	: 'pickuptime'+module
						}
					} )
					,{	xtype		: 'fieldset'
						,layout		: 'column'
						,padding	: 10
						,items		: [
							,{
								xtype	: 'hidden'
								,id	: 'idInvoice'+module
								,name	: 'idInvoice'
							}
							,{	xtype			: 'container'
								,columnWidth	: .5
								,items			: [
									standards.callFunction( '_createCustomerCombo', {
										module		: module
										,idAffiliate: idAffiliate
										,defaultValue : 38
										,listeners	: {
											beforeQuery: function(me, record){
												this.store.proxy.extraParams.idAffiliate = idAffiliate
											}, 
											select	: function( me, record ){
												var row = this.findRecord(this.valueField, this.getValue())
												items.proxy.extraParams = {
													customerid	: row.data.id,
													idAffiliate	: idAffiliate
													,tdate		: Ext.Date.format( Ext.valued( 'tdate' + module ) , 'Y-m-d' )
												}
												items.load({
													callback: function(){
														__setTotal()
													}
												})
												Ext.requestPost( route + 'getCustomerDetails', {
													id : parseInt(me.getValue(), 10)
												}).then(rest =>{
													var value = JSON.parse(rest.responseText)
													var data = value.view
													Ext.getCmp('address' + module).setValue(data.address)
													Ext.getCmp('tin' + module).setValue(data.tin)
												})
											}
										}
									} )
									,standards.callFunction( '_createTextArea', {
										id			: 'address' + module 
										,fieldLabel	: 'Address'
										,module		: module
										,allowBlank	: true
										,readOnly	: true
									} )
									
								]
							}
							,{	xtype			: 'container'
								,columnWidth	: .5
								,items			: [
									standards.callFunction( '_createTextField', {
										id			: 'tin' + module
										,module		: module
										,fieldLabel	: 'TIN'
										,allowBlank	: true
										,readOnly	: true
									} )
									,standards.callFunction( '_createTextField', {
										id			: 'remarks' + module
										,fieldLabel	: 'Remarks'
										,allowBlank	: true
									} )
									,standards.callFunction( '_createDateTime', {
										dId			: 'pickupdate'+module
										,tId			: 'pickuptime'+module
										,dFieldLabel	: 'Pick-up Date'
										,tstyle : 'margin-left: 5px;'
										,dAllowBlank	: false
										,tWidth : 105
									})
								]
							}
						]
					}
					,__items(items)
					
                ]
                ,listItems: gridHistory()
                ,listeners  : {
					afterrender : _init
				}
            } );
        }

		function __items(items){
            var itemlist = standards.callFunction( '_createRemoteStore', {
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
				,url        : route + 'getItems'
            });
            function _deleteItem( data ){
                deletedItems.push( data );
                selectedItem.splice( selectedItem.indexOf(data.idItem), 1);
				items.remove(items.findRecord('id', data.id));
				__setTotal()
			}
			
            return {
                xtype : 'tabpanel'
                ,items: [
                    {
                        title: 'SO Item(s)'
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
										,store	        : items
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
												standards.callFunction( '_createCombo', {
													id				: 'searchbarcode' + module
													,store          : itemlist
													,module			: module
													,fieldLabel		: ''
													,allowBlank		: true
													,width			: 250
													,displayField   : 'barcode'
													,valueField     : 'id'
													,emptyText		: 'Search barcode...'
													,hideTrigger	: true
													,listeners		: {
														beforeQuery	: function(){
															var ids = items.data.items.map(item => item.data.id)
															itemlist.proxy.extraParams.from			= "barcode"
															itemlist.proxy.extraParams.items		= JSON.stringify(ids)
															itemlist.proxy.extraParams.idAffiliate	= idAffiliate
															itemlist.proxy.extraParams.tdate		= Ext.Date.format( Ext.valued( 'tdate' + module ) , 'Y-m-d' )
															itemlist.proxy.extraParams.qty = Ext.getCmp('unitnum' + module).value
														}
														,select: function(){
															var row = this.findRecord(this.valueField, this.getValue())
															items.add(row)
															Ext.getCmp('searchbarcode' + module).reset()
															Ext.getCmp('unitnum' + module).reset()
															__setTotal()
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
										
										,columns:[
											{	header          : 'Code'
												,dataIndex      : 'barcode'
												,width          : 100
												,columnWidth    : 25
												,editor         : standards.callFunction( '_createCombo', {
													id              : 'itemcode' + module
													,allowBlank     : true
													,store          : itemlist
													,width          : 150
													,displayField   : 'barcode'
													,valueField     : 'barcode'
													,listeners      : {
														beforeQuery	: function(){
															var {0 : selected} = Ext.getCmp('gridItem' + module).getSelectionModel().getSelection();
															if(selected){
																var selectedid = selected.data.id
																var ids = items.data.items.map(item => item.data.id)
																ids = ids.filter(function(val){return val != selectedid})
																itemlist.proxy.extraParams.items = JSON.stringify(ids)
																itemlist.proxy.extraParams.from = "barcode"
																itemlist.proxy.extraParams.qty = Ext.getCmp('unitnum' + module).value
																itemlist.proxy.extraParams.idAffiliate	= idAffiliate
																itemlist.proxy.extraParams.tdate		= Ext.Date.format( Ext.valued( 'tdate' + module ) , 'Y-m-d' )
															}
															itemlist.load({})
															Ext.getCmp('itemcode' + module).setValue(selected.data.barcode)
														}
														,select: function(){
															var  {0 : store} = Ext.getCmp('gridItem' + module).selModel.getSelection()
															var row = this.findRecord(this.valueField, this.getValue())
															Ext.setGridData(['barcode', 'name', 'class', 'id', 'unit', 'cost', 'qty'],store, row)
														}
														,focus: function(){
															var  {0 : selected} = Ext.getCmp('gridItem' + module).getSelectionModel().getSelection();
															
															if(itemlist.getById(selected.data.id) == null){
																itemlist.add(selected.data)
															}
															Ext.getCmp('itemcode' + module).setValue(selected.data.barcode)
															
														}
														,blur: function(){
															var  {0 : selected} = Ext.getCmp('gridItem' + module).getSelectionModel().getSelection();
															
															if(itemlist.getById(selected.data.id) != null){
																var indexmap = itemlist.data.indexMap
																itemlist.removeAt(indexmap[selected.data.id])
															}
														}
													}
												})
											}
											,{	header          : 'Item Name'
												,dataIndex      : 'name'
												,width          : 200
												,columnWidth    : 45
												,editor         : standards.callFunction( '_createCombo', {
													id              : 'itemname' + module
													,allowBlank     : true
													,store          : itemlist
													,width          : 150
													,displayField   : 'name'
													,valueField     : 'name'
													,listeners      : {
														beforeQuery	: function(){
															var  {0 : selected} = Ext.getCmp('gridItem' + module).getSelectionModel().getSelection();
															
															if(selected){
																var selectedid = selected.data.id
																var ids = items.data.items.map(item => item.data.id)
																ids = ids.filter(function(val){return val != selectedid})
																itemlist.proxy.extraParams.items = JSON.stringify(ids)
																itemlist.proxy.extraParams.from = "itemName"
																itemlist.proxy.extraParams.qty = Ext.getCmp('unitnum' + module).value
																itemlist.proxy.extraParams.idAffiliate = idAffiliate
																itemlist.proxy.extraParams.tdate		= Ext.Date.format( Ext.valued( 'tdate' + module ) , 'Y-m-d' )
															}
															itemlist.load({})
															Ext.getCmp('itemname' + module).setValue(selected.data.name)
														}
														,select: function(){
															var  {0 : store} = Ext.getCmp('gridItem' + module).selModel.getSelection()
															var row = this.findRecord(this.valueField, this.getValue())
															Ext.setGridData(['barcode', 'name', 'class', 'id', 'unit', 'cost', 'qty'],store, row)
															selectedItem.push( parseInt( row.data.id,10) );
															__setTotal()															
														}
														,focus: function(){
															var  {0 : selected} = Ext.getCmp('gridItem' + module).getSelectionModel().getSelection();
															if(itemlist.getById(selected.data.id) == null){
																itemlist.add(selected.data)
															}
															Ext.getCmp('itemname' + module).setValue(selected.data.name)
															
														}
														,blur: function(){
															var  {0 : selected} = Ext.getCmp('gridItem' + module).getSelectionModel().getSelection();
															
															if(itemlist.getById(selected.data.id) != null){
																var indexmap = itemlist.data.indexMap
																itemlist.removeAt(indexmap[selected.data.id])
															}
														}
													}
												})
											}
											,{	header          : 'Classification'
												,dataIndex      : 'class'
												,width          : 250
												,columnWidth    : 50
											}
											,{	header          : 'Unit'
												,dataIndex      : 'unit'
												,width          : 75
												,columnWidth    : 20
											}
											,{	header          : 'Cost'
												,dataIndex      : 'cost'
												,width          : 100
												,columnWidth    : 25
												,editor			: 'float'
												,renderer		: function(val){
													return (Number(val)).toLocaleString('en-GB',{minimumFractionDigits: 2, maximumFractionDigits: 2})
												}
											}
											,{	header          : 'Quantity'
												,dataIndex      : 'qty'
												,width          : 150
												,columnWidth    : 30
												,editor			: 'float'
												,renderer		: function(val){
													return (Number(val)).toLocaleString('en-GB',{minimumFractionDigits: 2, maximumFractionDigits: 2})
												}
											}
											,{	header          : 'Amount'
												,dataIndex      : 'id'
												,width          : 200
												,columnWidth    : 45
												,renderer		: function(val){
													var selected = items.getById(val)
													var cost = Number(selected.get('cost'))
													var qty = Number(selected.get('qty'))
													var total = cost * qty
													return total.toLocaleString('en-GB',{minimumFractionDigits: 2, maximumFractionDigits: 2})
												}
											}
										]
										,listeners	: {
											afterrender : function() {
												items.load({});
											}
											,edit: function(me, value, grid){
												if(value.record.data.id != 0) __setTotal()
												else{
													var  {0 : store} = Ext.getCmp('gridItem' + module).selModel.getSelection()
													store.set(value.field, null)
													standards.callFunction('_createMessageBox',{ msg: 'No item selected, please provide an item first.' })
												}
											}
										}
									})
									,standards.callFunction('_createNumberField',{
										id : 'totalamt' + module
										,style : 'float:right;padding:8px;margin-top:5px;margin-right:5px;'
										,fieldLabel : 'Total Amount'
										,readOnly : true
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
								,items          : Ext.getCmp('gridItem' + module)                                
                            })
                        ]
                    }
                ]
            }
		}
		
        function _saveForm( form ){
			let items = Ext.getCmp('gridItem'+module).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0)
			if(items.length == 0){
				standards.callFunction('_createMessageBox',{ msg: 'No item selected, add at least one item first.' })
				return false;
			}
			
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
            if( totalCR != totalDR ){
                standards.callFunction( '_createMessageBox', {
                    msg     : 'Invalid transaction details. Make sure that total Debit and total Credit is balance.'
                    ,icon   : Ext.MessageBox.ERROR
                } );
                return false;
            }

			form.submit({
				waitTitle	: "Please wait"
                ,waitMsg	: "Submitting data..."
                ,url		: route + 'save'
                ,params		: {
					items       : Ext.encode( items )
					,journals   : Ext.encode( jeRecords )
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
			})
			
		}
		
		function __setTotal(){
			var items = Ext.getCmp('gridItem'+module).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0)
			if(items.length > 0){
				var total  = items.reduce((sum, item) =>( sum + (Number(item.qty) * Number(item.cost))), 0)
				Ext.getCmp('totalamt'+module).setValue(total)
			}else{
				Ext.getCmp('totalamt'+module).setValue(0)
			}
			
		}

        function _resetForm( form ){
			deletedItems = [];
			form.reset();
			Ext.resetGrid('gridItem'+module)
			Ext.resetGrid('gridJournalEntry'+module)
        }

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
		
		function gridHistory(){
			var soItems = standards.callFunction( '_createRemoteStore' , {
                fields:[ 
					{	name	: 'id'
                        ,type	: 'number'
                    }
					,{
						name: 'total'
						,type: 'number'
					}
					,'customer'
					,'date'
					,'reference' 

					,'idReference'
					,'referenceNum'
					,'idModule'
					]
                ,url: route + 'getSoItems'
			});
			
			return standards.callFunction('_gridPanel', {
                id : 'gridHistory' + module
                ,module     : module
                ,store      : soItems
				,height     : 265
				,noDefaultRow : true
				,filter	:{
					
				}
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
                        ,width      : 80
						,sortable   : false
						,columnWidth: '20%'
					}
					,{	header      : 'Customer Name'
                        ,dataIndex  : 'customer'
                        ,flex       : 1
                        ,minWidth   : 80
						,sortable   : false
						,columnWidth: '40%'
					}
					,{	header      : 'Total Amount'
                        ,dataIndex  : 'total'
                        ,width      : 100
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
                        ,Func       : _edit
                    })
                    ,standards.callFunction( '_createActionColumn', {
                        canDelete     : canDelete
                        ,icon       : 'remove'
						,tooltip    : 'Delete'
						,width      : 30
                        ,Func       : _delete
                    })
				]
				,listeners: {
                    afterrender: function(){
                        soItems.load({})
                    }
                }
			});
		}

		function _edit(data){
			module.getForm().retrieveData({
				url : route + 'retrieve'
				,params : {
					id : data.id
				}
				,hasFormPDF	: true
				,success : function( response, match , full) {
					let data = Ext.decode(full.view)
					__addExtraParam(['idCostCenter'+module, 'idReference'+module, 'pCode'+module], 'idAffiliate', data.invoice.affiliate)
					__resetAndSet('idCostCenter'+module, parseInt(data.invoice.cost_center, 10))
					__resetAndSet('idReference'+module, parseInt(data.invoice.reference, 10))
					__resetAndSet('pCode'+module, parseInt(data.invoice.customer,10))
					__setDateTime('tdate'+module, 'ttime'+module, data.invoice.date)
					__setDateTime('pickupdate'+module, 'pickuptime'+module, data.invoice.pickup)
					Ext.getCmp('referenceNum'+module).setValue(data.invoice.seriesnum)
					Ext.getCmp('address'+module).setValue(data.invoice.customer_address)
					Ext.getCmp('tin'+module).setValue(data.invoice.customer_tin)
					Ext.getCmp('idInvoice'+module).setValue(data.invoice.id)
					Ext.getCmp('remarks'+module).setValue(data.invoice.remarks)
					Ext.getCmp('totalamt'+module).setValue(Number(data.invoice.amount))
					__setGrid('gridItem'+module, data.items)
					// __setGrid('gridJournalEntry'+module, data.journals)

					// check cancel tag if trans is cancelled 
					if ( data.invoice.cancelTag == 1 ) Ext.getCmp( 'cancelTag' + module ).setValue( true )

					var gridJournal = Ext.getCmp( 'gridJournalEntry' + module );
					gridJournal.getStore().proxy.extraParams.idInvoice = data.invoice.id;
					gridJournal.getStore().load({});

					dataHolder = data.invoice;

					//Call this function to manipulate the visibility of transaction buttons aka [Approve/Cancel]
					// standards2.callFunction('_setTransaction', {
					// 	data : {status : data.invoice.status }
					// 	,module	: module
					// });

					/*Set a global variable for the retrieved data. Added by Hazel */
					dataHolder = response;
				}
			})
			Ext.resetGrid('gridHistory'+module)

		}

		function __resetAndSet(name, value){
			let component = Ext.getCmp(name)
			component.store.load({
				callback: function(){
					component.setValue(value)
				}
			})
		}
		
		function __addExtraParam(cpmname, field, value){
			cpmname.map(name=>{
				let data = Ext.getCmp(name)
				data.store.proxy.extraParams[field] = value
			})
		}

		function __setDateTime(ndate, ntime, datetime){
			let cmpDate = Ext.getCmp(ndate)
			let cmpTime = Ext.getCmp(ntime)
			let {date, time} = Ext.separateDateTime(datetime, 'YYYY-MM-DD HH:mm:ss')
			cmpDate.setValue(date)
			cmpTime.setValue(time)
		}

		function __setGrid(cmpname, lists){
			
			let store = Ext.getCmp(cmpname).store
			store.removeAll()
			lists.map(list=>{
				store.add(list)
			})
		}

		function _delete(data, row){
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

		function _transactionHandler( status ){
			standards.callFunction( '_createMessageBox', {
				msg		: 'Are you sure you want to change the status of this Sales Order?'
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
				url :  Ext.getConstant( 'STANDARD_ROUTE2' ) + 'updateTransactionStatus'
				,params : {
					status 			: status
					,idInvoice 		: dataHolder.id
					,notedBy 		: Ext.getConstant('USERID')
					,checkOnTable   : 'sales' // not sure
					,tableName      : 'so'
				}
				,success : function( response ){

					var stats = ( status == 2 ) ? 'Approved' : 'Cancelled', resp = Ext.decode( response.responseText );

						if( resp.match == 1 ) {
							standards.callFunction('_createMessageBox',{ msg: 'EDIT_USED' });
						} else {
							standards.callFunction('_createMessageBox',{ 
								msg: 'Sales Order has been ' + stats
								,fn : function() {
									standards2.callFunction('_setTransaction', { module	: module ,data : { status : status }});
								}
							});
						}
				}
			});
		}

		function _customListPDF() {
			Ext.Ajax.request({
				url  		: route + 'customListPDF'
				,params 	: { 
					items : Ext.encode( Ext.getCmp('gridHistory' + module).store.data.items.map((item)=>item.data) )
				}
				,success 	: function(response){
					if( isGae == 1 ){
						window.open(route+'viewPDF/Sales Order List','_blank');
					}else{
						window.open('pdf/inventory/Sales Order List.pdf');
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
				route		= config.route;
				baseurl		= config.baseurl;
				module		= config.module;
				canDelete	= config.canDelete;
				pageTitle   = config.pageTitle;
				canCancel	= config.canCancel;
				idModule	= config.idmodule
				isGae		= config.isGae;
				idAffiliate = config.idAffiliate
				selRec		= config.selRec;
				componentCalling = config.componentCalling
				
				return _mainPanel( config );
			}
		}
    }
}