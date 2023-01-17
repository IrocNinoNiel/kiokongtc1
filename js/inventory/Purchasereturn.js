/**
 * Developer: Hazel Alegbeleye
 * Module: Purchase Return
 * Date: January 15, 2020
 * Finished: 
 * Description: 
 * DB Tables: 
 * */ 
function Purchasereturn(){
    return function(){
        var baseurl, route, module, canDelete, canCancel, pageTitle, idModule, isGae, isSaved = 0, idAffiliate, canPrint, canEdit, dataHolder, onEdit;

        function _init( form ){
		}

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
					status 		: status
					,idInvoice 	: dataHolder.idInvoice
					,notedBy 	: Ext.getConstant('EMPLOYEEID')
					,checkOnTable   : 'receiving' // not sure
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


        function _mainPanel( config ){

			var supplierStore = standards.callFunction( '_createRemoteStore', {
				fields		: [ { name: 'id', type: 'number' }, 'name' ]
				,url		: Ext.getConstant( 'STANDARD_ROUTE2' ) + 'getSupplier'
				,autoLoad	: true
            } )
            , invoiceStore = standards.callFunction( '_createRemoteStore', {
				fields		: [ { name: 'id', type: 'number' }, 'name' ]
				,url		: route + 'getReceivingInvoice'
				,autoLoad	: true
            } )

            return standards2.callFunction(	'_mainPanelTransactions' ,{
                config						: config
                ,module                     : module
                ,hasCancelTransaction       : canCancel
                ,hasApproved			    : false
				,tbar       : {
					saveFunc        		: _saveForm
                    ,resetFunc      		: _resetForm
                    ,formPDFHandler			: _printPDF
                    ,customListPDFHandler	: _customListPDF
					,hasFormPDF     		: true
					,hasFormExcel			: false
					,filter : {
                        searchURL       : route + 'viewAll'
						,emptyText      : 'Search reference number here...'
						,module         : module
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
					,{	xtype		: 'fieldset'
						,layout		: 'column'
						,padding	: 10
						,items		: [
							,{	xtype			: 'container'
								,columnWidth	: .5
								,items			: [
									standards.callFunction( '_createSupplierCombo', {
                                        module		: module
                                        ,fieldLabel : 'Supplier'
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
                                            ,select : function(){
                                                var refInvoice = Ext.getCmp( 'fident' + module );
                                                if( refInvoice.getValue() != '' ) refInvoice.reset();

                                                Ext.resetGrid( 'grdItems' + module );
                                                Ext.resetGrid( 'gridJournalEntry' + module );
                                            }
										}
                                    } )
                                    ,standards.callFunction( '_createCombo', {
                                        id              : 'fident' + module
                                        ,fieldLabel     : 'Invoice'
                                        ,allowBlank     : false
                                        ,store          : invoiceStore
                                        ,displayField   : 'name'
                                        ,valueField     : 'id'
                                        ,listeners      : {
                                            beforeQuery : function( ) {
                                                var supplier = Ext.getCmp( 'pCode' + module ).getValue()
                                                    ,date    = Ext.getCmp( 'tdate' + module ).getValue();

                                                var params = [];

                                                if( supplier == '' || supplier== null ) {
                                                    standards.callFunction('_createMessageBox',{ msg: 'Please select a Supplier first.' })
                                                } else {
                                                    var idCostCenter   = Ext.getCmp( 'idCostCenter' + module).getValue();
                                                    if( typeof idCostCenter != 'undefined' || idCostCenter != '' ) params['idCostCenter'] = idCostCenter;
                                                    params['idSupplier']    = supplier;
                                                    params['date']          = date;
                                                    params['onEdit']        = onEdit

                                                    invoiceStore.proxy.extraParams = params;
                                                    invoiceStore.load({});
                                                }

                                                
                                            }
                                            ,select : function( ) {
                                                var grdItems = Ext.getCmp( 'grdItems' + module ).getStore();

                                                grdItems.proxy.extraParams.idInvoice = this.getValue();
                                                grdItems.load({});
                                            }
                                        }
                                    } )
								]
							}
							,{	xtype			: 'container'
								,columnWidth	: .5
								,items			: [
									standards.callFunction( '_createTextArea', {
										id			: 'remarks' + module
										,fieldLabel	: 'Remarks'
										,allowBlank	: true
									} )
								]
							}
						]
					}
					,_transactionGrid( config )
                ]
                ,listItems  : [ _gridHistory() ]
                ,listeners  : {
					afterrender : _init
				}
            } );
        }

        function _saveForm( form ){

			var msg = 'SAVE_SUCCESS', action = '' ; //DEFAULT VALUE

			var supplier 		= Ext.getCmp( 'pCode' + module ).store.data.items[0].data 
            ,gridItem			= Ext.getCmp('grdItems'+module).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0)
            ,params 			= {
                idModule 		: idModule
                ,date			: Ext.getCmp( 'tdate' + module).getValue()
                ,time			: Ext.Date.format(Ext.getCmp( 'ttime' + module).getValue(), 'h:i:s A')
                ,pType  		: 2 // SUPPLIER
                ,hasJournal 	: 1
                ,referenceNum	: Ext.getCmp( 'referenceNum' + module).getValue()
                ,idSupplier		: supplier.id
                ,items			: Ext.encode( gridItem )
                ,idInvoice      : (onEdit) ? dataHolder.idInvoice : 0
                ,cancelTag		: ( typeof cancelTag != 'undefined' && cancelTag.getValue() ) ? 1 : 0
                ,idAffiliate    : idAffiliate
                ,amount         : Ext.getCmp('totalAmount' + module ).getValue()
                ,bal            : Ext.getCmp('totalAmount' + module ).getValue()
                ,balLeft        : Ext.getCmp('totalAmount' + module ).getValue()
            };

            var isBalanced =  standards.callFunction('_gridJournalEntryValidation',{
                module : module
                ,totalAmountDue : Ext.getCmp('totalAmount' + module).getValue()
                ,totalAmountText : 'Total Amount'
            });

            if( gridItem.length > 0 ) {

                if( typeof isBalanced != 'boolean' ) {
                    params['journalEntries'] = Ext.encode( isBalanced );
                } else {
                    if( !isBalanced ) return false;
                }
                
                form.submit({
                    url : route + 'saveForm'
                    ,params : params
                    ,success : function( action, response ){
                        var resp 	= Ext.decode( response.response.responseText )
                        ,msg			= ( resp.match == 0 ) ? 'SAVE_SUCCESS' : 'FAILED'
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
            } else {
                standards.callFunction('_createMessageBox', { msg : 'Invalid action. Please select atleast one PO Item'});
            }
		}

        function _resetForm( form ){
            onEdit = 0;
			form.reset();
			Ext.resetGrid( 'grdItems' + module );
            Ext.resetGrid( 'gridJournalEntry' + module );

            _setReadOnly('fident', false);
            
            var refInvoice = Ext.getCmp( 'fident' + module ).getStore();
            refInvoice.proxy.extraParams = {};
            refInvoice.load({});
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

        function _printPDF(){
			var par  = standards.callFunction('getFormDetailsAsObject',{ module : module })
            ,journalEntries = Ext.getCmp('gridJournalEntry'+module).store.data.items.map((item)=>item.data)
			,itemStore = Ext.getCmp('grdItems'+module).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0);

			Ext.Ajax.request({
                url			: route + 'printPDF'
                ,method		:'post'
                ,params		: {
                    moduleID    	: 7
                    ,title  	: pageTitle
                    ,limit      	: 50
                    ,start      	: 0
					,printPDF   	: 1
					,form			: Ext.encode( par )
					,itemStore		: Ext.encode( itemStore )
                    ,journalEntries : Ext.encode( journalEntries )
					,hasPrintOption : Ext.getCmp('printStatusJEgridJournalEntry' + module).getValue()
                    ,idInvoice		: dataHolder.idInvoice
                    ,idAffiliate	: dataHolder.idAffiliate
                }
                ,success	: function(response, action){
                    if( isGae == 1 ){
						window.open(route+'viewPDF/Purchase Return','_blank');
					}else{
						window.open('pdf/inventory/Purchase Return.pdf');
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
						window.open(route+'viewPDF/Purchase Return List','_blank');
					}else{
						window.open('pdf/inventory/Purchase Return List.pdf');
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
			var itemStore = standards.callFunction( '_createRemoteStore', {
				fields		: [ 
					'referenceNumber', 
					'date', 
					'affiliateName', 
					'costCenterName', 
					'supplierName', 
					'approvedBy', 
					'status', 
					'amount', 
					'preparedBy',
					'id',
					'idReferenceSeries',
					'name'
				]
				,url		: route + 'viewAll'
				,autoLoad	: true
            } )
            
            function _editRecord( data ){
                onEdit = 1;

				module.getForm().retrieveData({
					url		: route + 'getData'
					,params	: {
						idInvoice : data.id
                    }
                    ,hasFormPDF	: true
					,excludes   : [ 'pCode', 'idInvoice', 'fident' ]
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

                        _setComboValues(
                            'pCode'
                            ,{ idAffiliate : parseInt( response.idAffiliate , 10) }
                            ,parseInt( response.pCode , 10)
                        );

                        _setComboValues(
                            'fident'
                            ,{ idSupplier : parseInt( response.pCode, 10), onEdit : 1 }
                            ,parseInt( response.fident, 10 )
                        );

						var journalEntries = Ext.getCmp( 'gridJournalEntry' + module );
						journalEntries.store.proxy.extraParams.idInvoice = parseInt( response.idInvoice, 10 );
                        journalEntries.store.load({});
                        
                        var poItemGrid = Ext.getCmp('grdItems' + module);
                        poItemGrid.store.proxy.extraParams = { idInvoice : response.idInvoice, onEdit : 1 };
                        poItemGrid.store.load({});

                        _setReadOnly('fident', true);
					}
				});
			}

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

									itemStore.load({});
								}
							});
						}
					}
				} );
			}

			return standards.callFunction('_gridPanel', {
                id 					: 'gridHistory' + module
                ,module     		: module
                ,store      		: itemStore
				,height     		: 265
				,noDefaultRow 		: true
                ,columns: [
                    {	header      : 'Affiliate'
                        ,dataIndex  : 'affiliateName'
                        ,flex       : 1
                        ,minWidth   : 150
                        ,sortable   : false
                        ,columnWidth: 15
					}
					,{	header      : 'Cost Center'
                        ,dataIndex  : 'costCenterName'
                        ,flex       : 1
                        ,minWidth   : 80
                        ,sortable   : false
                        ,columnWidth: 15
					}
					,{	header      : 'Date'
                        ,dataIndex  : 'date'
                        ,width      : 80
                        ,sortable   : false
                        ,columnWidth: 10
                        ,xtype      : 'datecolumn'
                        ,format     : Ext.getConstant('DATE_FORMAT')
                    }
                    ,{	header      : 'Reference Number'
                        ,dataIndex  : 'name'
                        ,width      : 125
                        ,sortable   : false
                        ,columnWidth: 15
                    }
					,{	header      : 'Supplier Name'
                        ,dataIndex  : 'supplierName'
                        ,flex       : 1
                        ,minWidth   : 80
                        ,sortable   : false
                        ,columnWidth: 15
					}
					,{	header      : 'Prepared By'
                        ,dataIndex  : 'preparedBy'
                        ,flex       : 1
                        ,minWidth   : 80
                        ,sortable   : false
                        ,columnWidth: 15
					}
					,{	header      : 'Total Amount'
                        ,dataIndex  : 'amount'
						,width      : 100
						,xtype		: 'numbercolumn'
                        ,sortable   : false
                        ,columnWidth: 15
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
                        itemStore.load({})
                    }
                }
			});
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

		function __supplyPDetails( fields, data, value, onEdit ){

			fields.map( col =>  Ext.getCmp( col + module ).setValue( data[col] ) );
			var poItemGrid = Ext.getCmp('gridItem' + module);

			var extraParams = ( onEdit ) ? { 
				tableName		: 'po'
				,fieldValue 	: Ext.encode( { 0 : value['idReferenceSeries'], 1 : value['referenceNum'] } )
				,fieldName		: Ext.encode( { 0 : 'idReferenceSeries', 1 : 'referenceNum' } )
			} : { 
				fieldValue 	: parseInt( value, 10 )
				,fieldName	: 'idSupplier'
				,tableName	: 'supplieritems'
			};

			poItemGrid.store.proxy.extraParams = extraParams;
			poItemGrid.store.load({});
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
        
        function _transactionGrid( config ) {

            var itemStore = standards.callFunction( '_createRemoteStore', {
				fields		: [ 
                                'idReceiving'
                                ,'idReleasing'
                                ,'referenceNum'
                                ,'barcode'
                                ,'itemName'
                                ,'className'
                                ,'unitName'
                                ,'cost'
                                ,'qty'
                                ,'qtyBalance'
                                ,{ name: 'amount', type: 'number' }
                                ,'expiryDate'
                                ,'idItem'
                                ,'idItemClass'
                                ,'refNum'
                                ,'idReference'
                                ,'fident'
                            ]
				,url		: route + 'getItems'
				,autoLoad	: true
            } )


            return {
                xtype   : 'tabpanel'
                ,items  : [
                    {
                        title   : 'PO Details'
                        ,items  : [
                            standards.callFunction( '_gridPanel', {
                                id          : 'grdItems' + module
                                ,module     : module
                                ,store      : itemStore
                                ,plugins    : true
                                ,noPage     : true
                                ,features       : {
                                    ftype   : 'summary'
                                }
                                ,tbar       : {
                                    canPrint        : false
                                    ,noExcel        : true
                                    ,route          : route
                                    ,pageTitle      : pageTitle
                                }
                                ,columns    : [
                                    {	header          : 'PO Number'
                                        ,dataIndex      : 'referenceNum'
                                        ,flex           : 1
                                        ,minWidth       : 80
                                        ,sortable       : false
                                    }
                                    ,{	header          : 'Code'
                                        ,dataIndex      : 'barcode'
                                        ,width          : 80
                                        ,sortable       : false
                                    }
                                    ,{	header          : 'Item Name'
                                        ,dataIndex      : 'itemName'
                                        ,flex           : 1
                                        ,minWidth       : 100
                                        ,sortable       : false
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
                                        ,sortable       : false
                                    }
                                    ,{	header          : 'Expiry Date'
                                        ,dataIndex      : 'expiryDate'
                                        ,width          : 100
                                        ,columnWidth    : 10
                                        ,sortable       : false
                                    }
                                    ,{	header          : 'Qty Balance'
                                        ,dataIndex      : 'qtyBalance'
                                        ,width          : 100
                                        ,columnWidth    : 10
                                        ,xtype          : 'numbercolumn'
                                        ,sortable       : false
                                    }
                                    ,{	header          : 'Quantity'
                                        ,dataIndex      : 'qty'
                                        ,width          : 100
                                        ,columnWidth    : 10
                                        ,editor         : 'float'
                                        ,xtype          : 'numbercolumn'
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
                                            Ext.getCmp( 'totalAmount' + module ).setValue( Ext.util.Format.number( value, '0,000.00' ) );
                                            return value;
                                        }
                                    }
                                ]
                                ,listeners	: {
                                    afterrender : function() {
                                        // itemStore.load({});
                                    }
                                    ,edit : function( me, rowData ) {
                                        var index = rowData.rowIdx
                                        ,store = this.getStore().getRange();
    
                                        var totalAmount = ( store[index].data.amount != null ) ? store[index].data.amount : 0 ;
    
                                        switch( rowData.field ) {
                                            case 'cost':
                                                totalAmount = ( store[index].data.qty > 0 ? rowData.value * store[index].data.qty : rowData.value)
                                                break;
                                            case 'qty':
                                                if( rowData.value > store[index].data.qtyBalance ) {
                                                    standards.callFunction('_createMessageBox',{ 
                                                        msg: 'Quantity must be equal or more than the quantity balance.' 
                                                        ,fn: function( btn ){
                                                            if( btn == 'ok') {
                                                                store[index].set('qty', rowData.originalValue );
                                                            }
                                                        }
                                                    });
                                                } else {
                                                    totalAmount = store[index].data.cost * rowData.value;
                                                }
                                                
                                        }
    
                                        store[index].set('amount', totalAmount );
                                    }
                                }
                            } )
                            ,standards.callFunction('_createNumberField',{
                                id          : 'totalAmount' + module
                                ,style      : 'float:right;padding:8px;margin-top:5px;margin-right:5px;'
                                ,fieldLabel : 'Total Amount'
                                ,readOnly   : true
                            })
                        ]
                    }
                    ,{
                        title   : 'Journal Entries'
                        // ,layout : { type: 'card' }
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

        return{
			initMethod:function( config ){
				route		= config.route;
				baseurl		= config.baseurl;
				module		= config.module;
				canPrint	= config.canPrint;
				canDelete	= config.canDelete;
                canEdit		= config.canEdit;
                canCancel   = config.canCancel;
				pageTitle   = config.pageTitle;
				idModule	= config.idmodule
				isGae		= config.isGae;
				idAffiliate = config.idAffiliate
				
				return _mainPanel( config );
			}
		}
    }
}