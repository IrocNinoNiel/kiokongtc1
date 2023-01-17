/**
 * Developer: Hazel Alegbeleye
 * Module: Purchase Order
 * Date: 
 * Finished: 
 * Description: 
 * DB Tables: 
 * */ 
function Purchaseorder(){
    return function(){
        var baseurl, route, module, canDelete, canCancel, pageTitle, idModule, isGae, isSaved = 0, idAffiliate, canPrint, canEdit, dataHolder, onEdit = false , selRec , componentCalling;

		/* Functions used for Approve/Cancel [Start] */
		function _transactionHandler( status ){
			standards.callFunction( '_createMessageBox', {
				msg		: 'Are you sure you want to change the status of this Purchase Order?'
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
					,notedBy 		: Ext.getConstant('EMPLOYEEID')
					,checkOnTable   : 'receiving'
					,tableName      : 'po'
				}
				,success : function( response ){

					var stats = ( status == 2 ) ? 'Approved' : 'Cancelled', resp = Ext.decode( response.responseText );

						if( resp.match == 1 ) {
							standards.callFunction('_createMessageBox',{ msg: 'EDIT_USED' });
						} else {
							standards.callFunction('_createMessageBox',{ 
								msg: 'Purchase Order has been ' + stats
								,fn : function() {
									standards2.callFunction('_setTransaction', { module	: module ,data : { status : status }});
								}
							});
						}
				}
			});
		}

		/* [End] here. */


        function _mainPanel( config ){
			

			var supplierStore = standards.callFunction( '_createRemoteStore', {
				fields		: [ { name: 'id', type: 'number' }, 'name', 'tin', 'address', 'vatType', 'vatPercent', 'discountPercentage', 'payMode', 'terms', 'downPayment']
				,url		: route + 'getSupplier'
				,autoLoad	: true
			} )

            return standards2.callFunction(	'_mainPanelTransactions' ,{
                config						: config
				,transactionHandler			: _transactionHandler
				,hasCancelTransaction		: canCancel
				,module						: module
				
				/* Added by Makmak for No JE Transaction module [Start] */
				,listeners  : {
                    afterrender : function () {
                        if ( selRec ) {
                            _editRecord( { data: selRec , id:selRec.idInvoice } );
                        }
                    }
				}
				/* [End] here. */
			
				,tbar       : {
					saveFunc        		: _saveForm
					,resetFunc      		: _resetForm
					,customListExcelHandler	: _printExcel
					,formPDFHandler			: _printPDF
					,customListPDFHandler	: _customListPDF
					,hasFormPDF     		: true
					,hasFormExcel			: false
					,filter : {
                        searchURL       : route + 'viewAll'
						,emptyText      : 'Search po number here...'
						,module         : module
                    }
                }
                ,formItems  :[
					standards2.callFunction( '_transactionHeader', {
						module					: module
						,containerWidth			: 1000
						,idModule				: idModule
						,idAffiliate			: idAffiliate
						,config					: config
					} )
					,{	xtype		: 'fieldset'
						,layout		: 'column'
						,padding	: 10
						,items		: [
							,{	xtype			: 'container'
								,columnWidth	: .5
								,items			: [
									standards.callFunction( '_createSupplierCombo', {
										module		: module
										,store		: supplierStore
										,listeners	: {
											beforeQuery :  function() {
												supplierStore.proxy.extraParams.idAffiliate = parseInt( idAffiliate, 10 );
												supplierStore.load({
													callback: function() {
														if( supplierStore.getCount() < 1 ) {
															standards.callFunction('_createMessageBox',{ msg: 'No supplier was added for this Affiliate.' })
														}
													}
												})

											}
											,select	: function( me, record ){
												_supplyPDetails( me.getValue(), onEdit );
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
									,standards.callFunction( '_createTextField', {
										id			: 'tin' + module
										,module		: module
										,fieldLabel	: 'TIN'
										,allowBlank	: true
										,readOnly	: true
									} )
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
										// ,minValue	: new Date()
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
										// ,isDecimal  : true
										,fieldStyle	: 'text-align: right;'
										,isNumber	: true
									} )	
								]
							}
						]
					}
					,standards2.callFunction(	'_poTabPanel' ,{
						module			: module
						,containerWidth	: 1000
						,isPO			: true
						,config			: config
					})
                ]
                ,listItems  : [ _gridHistory() ]
            } );
        }

        function _saveForm( form ){
			var msg = 'SAVE_SUCCESS', action = '' ; //DEFAULT VALUE

			if( _checkDate() ){
				var supplier 		= Ext.getCmp( 'pCode' + module ).store.data.items[0].data 
				,cancelTag			= Ext.getCmp('cancelTag' + module)
				,gridItem			= Ext.getCmp( 'gridItem'+module).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0)
				,params 			= {
					idModule 		: idModule
					,date			: Ext.getCmp( 'tdate' + module).getValue()
					,time			: Ext.Date.format(Ext.getCmp( 'ttime' + module).getValue(), 'h:i:s A')
					,pType  		: 2 //SUPPLIER
					,payMode 		: supplier.payMode
					,amount			: Ext.getCmp('totalPayable' + module).getValue()
					,bal			: Ext.getCmp('totalPayable' + module).getValue()
					,balLeft		: Ext.getCmp('totalPayable' + module).getValue()
					,terms 			: supplier.terms
					,vatType 		: supplier.vatType
					,hasJournal 	: 1
					,referenceNum	: Ext.getCmp( 'referenceNum' + module).getValue()
					,idSupplier		: supplier.id
					,items			: Ext.encode( gridItem )
					// ,journalEntries : Ext.encode( journalEntries )
					,status			: 2 // APPROVED
					,cancelTag		: ( typeof cancelTag != 'undefined' && cancelTag.getValue() ) ? 1 : 0
					,creditLimit	: Ext.getCmp('creditLimit' + module).getValue()
					,apBalance		: Ext.getCmp('apBalance' + module).getValue()
					,variance		: Ext.getCmp('variance' + module).getValue()
					,idAffiliate	: idAffiliate
				};

				if( typeof dataHolder != 'undefined') params['idInvoice']  = dataHolder.idInvoice;

				var isBalanced =  standards.callFunction('_gridJournalEntryValidation',{
					module : module
                    ,totalAmountDue : Ext.getCmp('totalPayable' + module).getValue()
                    ,totalAmountText : 'Total Amount'
				});

				if( gridItem.length > 0 ) {
					switch( true ){
						case !isBalanced:
							return false;
						case gridItem.map( field => field.idItem ).some( checkEmpty ):
							standards.callFunction('_createMessageBox', { msg : 'Please select at least one item.'});
							return false;
						case parseFloat(params.variance) < 0:
							standards.callFunction('_createMessageBox', { msg : 'Credit limit exceeded.'});
							break;
						default:
							if(typeof isBalanced != 'boolean') params['journalEntries'] = Ext.encode( isBalanced );
							_submitForm( params, form );
							break;
					}
					
				} else {
					standards.callFunction('_createMessageBox', { msg : 'Please select at least one item.'});
				}

			} else {
				standards.callFunction('_createMessageBox', { msg : 'Please select a valid due date.'});
			}
		}

		function checkEmpty( item ){
			return item === '' || item == null;
		}

		function _submitForm( params, form ){
			form.submit({
				url 		: route + 'saveForm'
				,params 	: params
				,success 	: function( action, response ){
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
		
		function _checkDate(){
			var date 		= Ext.getCmp( 'tdate' + module )
				,dueDate 	= Ext.getCmp( 'dueDate' + module )
				,pDateTime 	= Ext.dateParse(date.getValue())
				,pDueDate 	= Ext.dateParse(dueDate.getValue());

			return ( pDateTime != null ) ? pDueDate.isSameOrAfter(pDateTime) : false;
		}

        function _resetForm( form ){
			form.reset();
			Ext.resetGrid( 'gridItem' + module );
			Ext.resetGrid( 'gridJournalEntry' + module );
        }

        function _printPDF(){
			var par  = standards.callFunction('getFormDetailsAsObject',{ module : module })
			,poItems = Ext.getCmp('gridItem'+module).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0);

			Ext.Ajax.request({
                url			: route + 'generatePDF' //Ext.getConstant( 'STANDARD_ROUTE2' ) + 'generateFormPDF' //
                ,method		:'post'
                ,params		: {
                    moduleID    	: 7
                    ,title  		: pageTitle
                    ,limit      	: 50
                    ,start      	: 0
					,printPDF   	: 1
					,form			: Ext.encode( par )
					,poItems		: Ext.encode( poItems )
					,hasPrintOption : Ext.getCmp('printStatusJEgridJournalEntry' + module).getValue()
					,idInvoice		: dataHolder.idInvoice
					,idAffiliate	: dataHolder.idAffiliate
                }
                ,success	: function(response, action){
                    if( isGae == 1 ){
						window.open(route+'viewPDF/Purchase Order','_blank');
					}else{
						window.open('pdf/inventory/Purchase Order.pdf');
					}
                }
			});			
		}

		function _customListPDF() {
			Ext.Ajax.request({
				url  		: route + 'customListPDF'
				,params 	: { 
					items : Ext.encode( Ext.getCmp('gridHistory'+module).store.data.items.map((item)=>item.data) )
				}
				,success 	: function(response){
					if( isGae == 1 ){
						window.open(route+'viewPDF/Purchase Order List','_blank');
					}else{
						window.open('pdf/inventory/Purchase Order List.pdf');
					}
				}
			});
		}

		function _formToPDF(){
			var par  = standards.callFunction('getFormDetailsAsObject',{
				module : module
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

		function _gridHistory() {
			var poItems = standards.callFunction( '_createRemoteStore', {
				fields		: [ 
					'name',//'PONumber', 
					'date', 
					'affiliateName', 
					'costCenterName',
					'supplierName', //name
					'amount', 
					'orderedBy',
					'id', //idInvoice
					'idReferenceSeries',
					'referenceNum'
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
                    {	header      	: 'PO Number'
                        ,dataIndex  	: 'name'
                        ,width      	: 80
						,sortable   	: false
						,columnWidth 	: 15
					}
					,{	header      	: 'Date'
                        ,dataIndex  	: 'date'
                        ,width      	: 80
						,sortable   	: false
						,columnWidth 	: 15
						,xtype			: 'datecolumn'
					}
					,{	header      	: 'Affiliate'
                        ,dataIndex  	: 'affiliateName'
                        ,flex       	: 1
                        ,minWidth   	: 150
						,sortable   	: false
						,columnWidth 	: 20
					}
					,{	header      	: 'Cost Center'
                        ,dataIndex  	: 'costCenterName'
                        ,flex       	: 1
                        ,minWidth   	: 80
						,sortable   	: false
						,columnWidth 	: 15
					}
					,{	header      	: 'Supplier Name'
                        ,dataIndex  	: 'supplierName'
                        ,flex       	: 1
                        ,minWidth   	: 80
						,sortable   	: false
						,columnWidth 	: 10
					}
					,{	header      	: 'Ordered By'
                        ,dataIndex  	: 'orderedBy'
                        ,flex       	: 1
                        ,minWidth   	: 80
						,sortable   	: false
						,columnWidth 	: 10
					}
					,{	header      	: 'Total Amount'
                        ,dataIndex  	: 'amount'
						,width      	: 100
						,xtype			: 'numbercolumn'
						,sortable   	: false
						,columnWidth 	: 15
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
                        poItems.load({})
                    }
                }
			});

			function _deleteRecord( data ) {
				standards.callFunction( '_createMessageBox', {
					msg		: 'DELETE_CONFIRM'
					,action	: 'confirm'
					,fn		: function( btn ){
						if( btn == 'yes' ){
							Ext.Ajax.request({
								url 	: route + 'deleteRecord'
								,params : { idInvoice: data.id, idReferenceSeries : data.idReferenceSeries }
								,success : function( response ){
									var resp = Ext.decode( response.responseText );
									if( resp.match == 1 ) {
										standards.callFunction( '_createMessageBox', {
											msg : 'DELETE_USED'
										} );
									} 

									poItems.load({});
								}
							});
						}
					}
				} );
			}
		}

		function _editRecord( data ){
			onEdit = true;

			module.getForm().retrieveData({
				url				: route + 'getData'
				,params			: {
					idInvoice 	: data.id
				}
				,excludes		: [ 'pCode' ]
				,hasFormPDF		: true
				,success 		: function( response, responseText ){
					dataHolder = response;
					
					var supplier = Ext.getCmp('pCode' + module );
					supplier.store.proxy.extraParams.idAffiliate = parseInt( response.idAffiliate , 10);
					supplier.store.load({
						callback: function() {
							supplier.setValue(parseInt( response.pCode , 10));
						}
					});

					_supplyPDetails( data.id, onEdit );
					_getSupplierDetails( response.pCode );

					var journalEntries = Ext.getCmp( 'gridJournalEntry' + module );
					journalEntries.store.proxy.extraParams.idInvoice = parseInt( response.idInvoice, 10 );
					journalEntries.store.load({});

					
				}
			});
		}

		function _supplyPDetails( id, onEdit ){
			/* Auto-populate fields based on selected supplier's details on Supplier Settings  */
			let itemGrid 		= Ext.getCmp('gridItem' + module)
				,itemCodeStore	= Ext.getCmp( 'itemCode' + module ).getStore()
				,itemNameStore	= Ext.getCmp( 'itemName' + module ).getStore()
				,params 	= {};

				itemCodeStore.proxy.extraParams = { idAffiliate : idAffiliate };
				itemNameStore.proxy.extraParams = { idAffiliate : idAffiliate };

				itemCodeStore.load({});
				itemNameStore.load({});

			if( !onEdit ) _getSupplierDetails( id );

			if( idAffiliate != null ) {
				params = ( onEdit ) ? {
					idInvoice 		: id
					,idAffiliate 	: idAffiliate
				} : {
					pCode 			: id
					,idAffiliate 	: idAffiliate
					,onGrid			: 1
				}
			}

			itemGrid.store.proxy.extraParams = params;
			itemGrid.store.load({});
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

						/** Set due date validation **/
						var transactionDate = Ext.getCmp('tdate'+module).getValue();
						if( parseInt(record.terms,10) > 0 ){
							let dueDate = Ext.toMoment(transactionDate).add( parseInt(record.terms, 10),'days');
							Ext.getCmp('dueDate'+module).setMinValue(  dueDate.toDate() );
							Ext.getCmp('dueDate'+module).setValue(  dueDate.toDate() );
						}
						/* Set values to supplier dependent fields: address, tin, credit limit, ap balance, and variance. */
						if( record !== null ) Object.keys( record ).map( key => { if(key != 'terms' ) Ext.getCmp( key + module ).setValue( record[key] ) });
				}
			});
		}

        return{
			initMethod:function( config ){
				route		= config.route;
				baseurl		= config.baseurl;
				module		= config.module;
				canPrint	= config.canPrint;
				canDelete	= config.canDelete;
				canEdit		= config.canEdit;
				canCancel	= config.canCancel
				pageTitle   = config.pageTitle;
				idModule	= config.idmodule
				isGae		= config.isGae;
				idAffiliate = config.idAffiliate;
				selRec 		= config.selRec;
				componentCalling = config.componentCalling;

				return _mainPanel( config );
			}
		}
    }
}