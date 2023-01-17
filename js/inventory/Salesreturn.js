function Salesreturn(){
    return function(){
        var baseurl, route, module, canDelete, canCancel, pageTitle, idModule, isGae,isSaved = 0,deletedItems = [],selectedItem = [], idAffiliate, dataHolder;

        function _init(){

        }

        function _mainPanel( config ){
			var items = standards.callFunction(  '_createRemoteStore' ,{
                fields:[
                    {	name	: 'id'
                        ,type	: 'number'
                    }
                    ,'code'
					,'name'
					,'class'
					,'unit'
					,{	name	: 'cost'
                        ,type	: 'number'
                    }
					,{	name	: 'price'
                        ,type	: 'number'
                    }
					,{	name	: 'remaining'
                        ,type	: 'number'
                    }
					,{	name	: 'qty'
                        ,type	: 'number'
                    }
					,{	name	: 'amount'
                        ,type	: 'number'
                    }
					,'releasedID'
                ]
                ,url: route + 'getCustomerItem'
			});

            var invoicesStore = standards.callFunction(  '_createRemoteStore' ,{
                fields:[
                    {	name	: 'id'
                        ,type	: 'number'
                    }
                    ,'refcode'
                ]
                ,url: route + 'getCustomerInvoice'
			});

            return standards2.callFunction(	'_mainPanelTransactions' ,{
				config					: config
				,module					: module
				,transactionHandler		: _transactionHandler
				,moduleType				: 'form'
				,orientation			: 'L'
				,hasCancelTransaction	: canCancel
				,hasApproved			: false
				,tbar       			: {
					saveFunc        		: _saveForm
					,resetFunc      		: _resetForm
					,formPDFHandler			: _printPDF
					,customListExcelHandler	: _printExcel
					,customListPDFHandler	: _customListPDF
					,hasFormPDF     		: true
					,hasFormExcel			: false
					,filter             : {
						searchURL       : route + 'searchHistoryGrid'
						,emptyText      : 'Search reference here...'
						,module         : module
					}
                }
                ,formItems  :[
					standards2.callFunction( '_transactionHeader', {
						module					: module
						,idModule  				: idModule
						,dFieldLabel			: 'Date'
						,idAffiliate			: idAffiliate
						,moduleCompareFrom		: {
							date: 'pickupdate'+module
							,time: 'pickuptime'+module
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
							,{
								xtype	: 'hidden'
								,id	: 'fident'+module
								,name	: 'fident'
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
											}
											,select : function(me, record){
												Ext.getCmp('invoices' + module).setValue(null)
											}
										}
									} )
									,standards.callFunction( '_createCombo', {
										id				: 'invoices' + module
										,store          : invoicesStore
										,module			: module
										,fieldLabel		: 'Invoice'
										,allowBlank		: false
										,displayField   : 'refcode'
										,valueField     : 'id'
										,emptyText		: 'Select invoice...'
										,listeners		: {
											beforeQuery	: function(me, record){
												var customer = Ext.valued('pCode'+module)
												if(customer == null){
													standards.callFunction('_createMessageBox',{ msg: 'Please select customer first.' })
													return false
												}
												this.store.proxy.extraParams = {
													customer : customer
													,idAffiliate: idAffiliate
													,transaction_date : `${Ext.dateParse(Ext.getCmp('tdate'+module).value).format('YYYY-MM-DD')} ${Ext.dateParse(Ext.getCmp('ttime'+module).value).format('HH:mm:00')}`,
												}
											}
											,select : function(me, record){
												var {0: {raw: {id}}} = record
												items.proxy.extraParams.idInvoice = id
												items.load({
													callback: function(){
														__setTotal()
													}
												})
											}
										}
									})
									
								]
							}
							,{	xtype			: 'container'
								,columnWidth	: .5
								,items			: [
									standards.callFunction( '_createTextArea', {
										id			: 'remark' + module 
										,fieldLabel	: 'Remarks'
										,module		: module
										,allowBlank	: true
									} )
								]
							}
						]
					}
					,__items(items, config)
					
                ]
                ,listItems: gridHistory()
                ,listeners  : {
					afterrender : _init
				}
            } );
		}

		function __items(items, config){
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
										,store	        : items
										,noDefaultRow   : true
										,noPage         : true
										,plugins        : true
										,tbar : {
											canPrint        : false
											,noExcel        : true
											,route          : route
											,pageTitle      : pageTitle
										}
										
										,columns:[
											{	header          : 'Code'
												,dataIndex      : 'code'
												,width          : 100
												,columnWidth    : 25
											}
											,{	header          : 'Item Name'
												,dataIndex      : 'name'
												,width          : 250
												,columnWidth    : 25
												,flex       : 1
											}
											,{	header          : 'Classification'
												,dataIndex      : 'class'
												,width          : 150
												,columnWidth    : 25
											}
											,{	header          : 'Unit'
												,dataIndex      : 'unit'
												,width          : 100
												,columnWidth    : 25
											}
											,{	header          : 'Cost'
												,dataIndex      : 'cost'
												,width          : 100
												,columnWidth    : 25
												,xtype			: 'numbercolumn'
												,hasTotal		: true
												// ,renderer		: function(val){
												// 	if(val === undefined) return 0.00
												// 	return (Number(val)).toLocaleString('en-GB',{minimumFractionDigits: 2, maximumFractionDigits: 2})
												// }
											}
											,{	header          : 'Price'
												,dataIndex      : 'price'
												,width          : 100
												,columnWidth    : 25
												,xtype			: 'numbercolumn'
												,hasTotal		: true
												// ,renderer		: function(val){
												// 	if(val === undefined) return 0.00
												// 	return (Number(val)).toLocaleString('en-GB',{minimumFractionDigits: 2, maximumFractionDigits: 2})
												// }
											}
											,{	header          : 'Qty to Return'
												,dataIndex      : 'remaining'
												,width          : 100
												,columnWidth    : 25
												,xtype			: 'numbercolumn'
												,hasTotal		: true
												// ,renderer		: function(val){
												// 	if(val === undefined) return 0.00
												// 	return (Number(val)).toLocaleString('en-GB',{minimumFractionDigits: 2, maximumFractionDigits: 2})
												// }
											}
											,{	header          : 'Quantity'
												,dataIndex      : 'qty'
												,width          : 100
												,columnWidth    : 25
												,xtype			: 'numbercolumn'
												,editor			: 'number'
												,hasTotal		: true
												// ,renderer		: function(val){
												// 	if(val === undefined) return 0.00
												// 	return (Number(val)).toLocaleString('en-GB',{minimumFractionDigits: 2, maximumFractionDigits: 2})
												// }
											}
											,{	header          : 'Amount'
												,dataIndex      : 'amount'
												,width          : 115
												,columnWidth    : 45
												,xtype			: 'numbercolumn'
												,hasTotal		: true
												// ,renderer		: function(val){
												// 	if(val === undefined) return 0.00
												// 	return (Number(val)).toLocaleString('en-GB',{minimumFractionDigits: 2, maximumFractionDigits: 2})
												// }
											}
										]
										,listeners	: {
											afterrender : function() {
												// items.load({});
											}
											,edit: function(me, value, grid){
												var  {0 : store} = Ext.getCmp('gridItem' + module).selModel.getSelection()
												if(value.record.data.id == 0){
													
													store.set(value.field, null)
													standards.callFunction('_createMessageBox',{ msg: 'No item selected, please provide an item first.' })
												}else{
													var { qty ,remaining,price } = value.record.data
													remaining = parseFloat(remaining)
													price = parseFloat(price)
													qty = parseFloat(qty)
													if(qty > remaining){
														standards.callFunction('_createMessageBox',{ msg: 'You must excced the quantity remaining.' })
														store.set('qty', 0 )
														store.set('amount', 0 )
													}else{
														store.set('amount', isNaN(qty)? 0 : qty * price )
													}
													__setTotal()
												}
											}
										}
									})
									,standards.callFunction('_createNumberField',{
										id : 'totalamt' + module
										,style : 'float:right;padding:8px;margin-top:5px;margin-right:5px;'
										,fieldLabel : 'Total Amount'
										,hidden :	true
									})
								]
							}
                            
                        ]
					}
					,{
                        title: 'Journal Entry'
                        ,layout:{
                            type: 'card'
                        }
                        ,items  :   [
                            standards.callFunction( '_gridJournalEntry',{
                                module	        : module
                                ,hasPrintOption : 1
                                ,config         : config
                                ,items          : Ext.getCmp('gridItem' + module)
                                ,customer       : 'pCode'
                            })
                        ]
                    }
                ]
            }
		}

		function __setTotal(){
			var items = Ext.getCmp('gridItem'+module).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0)
			if(items.length > 0){
				var total  = items.reduce((sum, item) =>( sum + (Number(item.qty) * Number(item.price))), 0)
				Ext.getCmp('totalamt'+module).setValue(total)
			}else{
				Ext.getCmp('totalamt'+module).setValue(0)
			}
			
		}
		
        function _saveForm( form ){
			var items = Ext.getCmp('gridItem'+module).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0)
			var qty = items.map(item => parseInt(item.qty));
			var totalqty = qty.reduce((base, now)=> base + now, 0)
			if(totalqty == 0){
				standards.callFunction('_createMessageBox',{ msg: 'Please add quantity to return first.' })
				return false;
			}
			var items = Ext.getCmp('gridItem'+module).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0)
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
                                    _resetForm( form );
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

        function _resetForm( form ){
			onEdit = 0
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
			var salesReturn = standards.callFunction( '_createRemoteStore' , {
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
                ,url: route + 'getInvoices'
			});
			
			return standards.callFunction('_gridPanel', {
                id : 'gridHistory' + module
                ,module     : module
                ,store      : salesReturn
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
                        ,width      : 120
						,sortable   : false
						,columnWidth: '20%'
					}
					,{	header      : 'Customer Name'
                        ,dataIndex  : 'customer'
                        ,flex       : 1
                        ,minWidth   : 120
						,sortable   : false
						,columnWidth: '40%'
					}
					,{	header      : 'Total Amount'
                        ,dataIndex  : 'total'
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
                        salesReturn.load({})
                    }
                }
			});
		}

		function _edit(data, row){
			module.getForm().retrieveData({
				url : route + 'retrieve'
				,params : {
					id : data.id
				}
				,hasFormPDF	: true
				,success : function( response, match , full) {
					onEdit = 1

					let timed = Ext.dateParse(response.ttime, 'HH:mm A')
					let dated = Ext.dateParse(response.tdate, 'YYYY-MM-DD')
					if ( response.cancelTag == 1 ) { Ext.getCmp( 'cancelTag' + module ).setValue( true ); }
					Ext.valued('ttime'+module, timed.toDate())
					Ext.valued('tdate'+module, dated.toDate())
					Ext.getCmp('invoices'+module).store.load({
						params:{
							idAffiliate: idAffiliate,
							transaction_date : `${dated.format('YYYY-MM-DD')} ${timed.format("HH:mm:00")}`,
							with_id: response.fident,
							customer: response.pCode
						},
						callback:function(){
							console.log(response.fident)
							Ext.valued('invoices'+module, parseInt(response.fident, 10))
						}
					})
					let store = Ext.getCmp('gridItem' + module).store
					store.removeAll()
					store.add(full.released)
					__setTotal()
					//Call this function to manipulate the visibility of transaction buttons aka [Approve/Cancel]
                    // standards2.callFunction('_setTransaction', {
                    //     data : response
                    //     ,module	: module
					// });
					
					/*Set a global variable for the retrieved data. Added by Hazel */
					dataHolder = response;
				}
			}) 	
			

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

		function _customListPDF() {
			Ext.Ajax.request({
				url  		: route + 'customListPDF'
				,params 	: { 
					items : Ext.encode( Ext.getCmp('gridHistory'+module).store.data.items.map((item)=>item.data) )
				}
				,success 	: function(response){
					if( isGae == 1 ){
						window.open(route+'viewPDF/Sales Return List','_blank');
					}else{
						window.open('pdf/inventory/Sales Return List.pdf');
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
				canCancel	= config.canCancel;
				pageTitle   = config.pageTitle;
				idModule	= config.idmodule
				isGae		= config.isGae;
				idAffiliate = config.idAffiliate
				
				return _mainPanel( config );
			}
		}
    }
}