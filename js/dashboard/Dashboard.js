function Dashboard(){
    return function(){
		var baseurl, route, module, canDelete, pageTitle, canPrint, isGae;

		function _init(){

		}

		function _mainPanel( config ){
			var data1, data2;

			if( config.hasReceivable ){
				data1 = {	
					title			: 'Schedule of Receivable'
					,style			: 'margin-top: 20px; margin-left: 10px; margin-right: 10px;'
					,items			: [
						scheduleReceivable( config )
					]
				}
			}
			else{
				var dataStore = standards.callFunction( '_createLocalStore', {
					data: [ 'name', 'name1' ]
				} )
				data1 = {	title	: 'Schedule of Receivable'
					,style	: 'margin-top: 20px; margin-left: 10px; margin-right: 10px; margin-bottom: 370px;'
					,items	: [
						{	xtype	: 'label'
							,text	: 'Sorry, you are not allowed to access this module.Please contact your system administrator.'
							,style	: 'fontSize: 40px;'
						}
					]
				}
			}
			
			if( config.hasPayable ){
				data2 = {	
					title			: 'Schedule of Payable'
					,style			: 'margin-top: 20px; margin-left: 10px; margin-right: 10px;'
					,items			: [
						schedulePayable( config )
					]
				}
			}
			else{
				data2 = {	title : 'Schedule of Payable'
					,style	: 'margin-top: 20px; margin-left: 10px; margin-right: 10px; margin-bottom: 370px;'
					,items	: [
						{	xtype	: 'label'
							,text	: 'Sorry, you are not allowed to access this module.Please contact your system administrator.'
							,style	: 'fontSize: 40px;'
						}
					]
				}
			}
			
			return standards.callFunction(	'_mainPanel' ,{
				config			: config
				,moduleType		: 'form'
				,noHeader		: true
				,bodyPadding	: '0px'
				,formItems		: [
					{	xtype	: 'tabpanel'
						,id		: 'tabDashboard' + module
						,layout	: 'fit'
						,items	: [
							data1
							,data2
						]
					}
				]
				,listeners		: {
					afterrender	: _init
				}
			} );
		}

		function scheduleReceivable( config ){
			var _module = '_Scheduleofreceivable' + module
				,_route =  baseurl + 'generalreports/Scheduleofreceivable/';

			var customerStore = standards.callFunction(  '_createRemoteStore' ,{
				fields      : [ {	name : 'id', type : 'number' }, 'name']
				,url        : _route + 'getCustomers'
				,startAt    :  0
				,autoLoad   : true
			});

			function _setDefaultValue( store, id ) {
				store.load({
					callback: function(){
						Ext.getCmp( id + _module ).setValue(0);
					}
				});
			}

			function receivableGrid(){
				var store = standards.callFunction(  '_createRemoteStore' ,{
					fields:[
						'referenceNum'
						,'transactionDate'
						,'dueDate'
						,'customerName'
						,'description'
						,'affiliateName'
						,{ name: 'amount', type: 'number' }
						,{ name: 'balance', type: 'number' } 
					], 
					url: _route + "getReceivable"
				});

				return standards.callFunction( '_gridPanel',{
					id          : 'gridReceivable' + _module
					,module     : _module
					,store      : store
					,tbar       : {
						canPrint                : canPrint
                        ,customListPDFHandler   : _printPDF
                        ,customListExcelHandler : _printExcel
					}
					,noDefaultRow : true
					,noPage     : true
					,plugins    : true
					,style      :'margin-top:10px;'
					,columns    : [
						{	header      : 'Affiliate'
							,dataIndex  : 'affiliateName'
							,minWidth   : 60
							,flex       : 1
						},
						{	header      : 'Reference'
							,dataIndex  : 'referenceNum'
							,minWidth   : 60
							,flex       : 1
						}
						,{	header      : 'Transaction Date'
							,dataIndex  : 'transactionDate'
							,xtype      : 'datecolumn'
							,format     : Ext.getConstant('DATE_FORMAT')
							,minWidth   : 60
							,flex       : 1
						}
						,{	header      : 'Due Date'
							,dataIndex  : 'dueDate'
							,minWidth   : 60
							,flex       : 1
							,xtype      : 'datecolumn'
							,format     : Ext.getConstant('DATE_FORMAT')
						}
						,{	header      : 'Customer Name'
							,dataIndex  : 'customerName'
							,minWidth   : 60
							,flex       : 1
						}
						,{	header      : 'Description'
							,dataIndex  : 'description'
							,minWidth   : 60
							,flex       : 1
						}
						,{	header      : 'Amount'
							,dataIndex  : 'amount'
							,minWidth   : 60
							,flex       : 1
							,xtype      : 'numbercolumn'
							,format     : '0,000.00'
							,hasTotal   : true
						}
						,{	header      : 'Balance'
							,dataIndex  : 'balance'
							,minWidth   : 60
							,flex       : 1
							,xtype      : 'numbercolumn'
							,format     : '0,000.00'
							,hasTotal   : true
						}
					]
					
				});
			}
	
			function _printPDF(){
				var _grid               = Ext.getCmp( 'gridReceivable' + _module );
	
				standards.callFunction( '_listPDF', {
					grid                    : _grid
					,customListPDFHandler   : function(){
						var par = standards.callFunction( 'getFormDetailsAsObject', {
							module          : _module
							,getSubmitValue : true
						} );
						par.title           = 'Schedule of Receivable';
						
						Ext.Ajax.request( {
							url         : _route + 'printPDF'
							,params     : par
							,success    : function(res){
								if( isGae ){
									window.open( _route + 'viewPDF/' + par.title , '_blank' );
								}
								else{
									window.open( baseurl + 'pdf/generalreports/' + par.title + '.pdf');
								}
							}
						} );
					}
				} );
			}
	
			function _printExcel(){
				var _grid = Ext.getCmp( 'gridReceivable' + _module );
	
				standards.callFunction( '_listExcel', {
					grid                    : _grid
					,customListExcelHandler : function(){
						var par = standards.callFunction( 'getFormDetailsAsObject', {
							module          : _module
							,getSubmitValue : true
						} );
						par.title = 'Schedule of Receivable';
						
						Ext.Ajax.request( {
							url         : _route + 'printExcel'
							,params     : par
							,success    : function(res){
								window.open( _route + "download/" + par.title + '/generalreports');
							}
						} );
					}
				} );
			}

			return standards.callFunction(	'_formPanel' ,{
				config		: config
				,module		: _module
				,moduleType	: 'report'
				,asContainer : true
				// ,isTabChild : true
				,tbar       : {
					noFormButton        : true
					,noListButton       : true
					,noPDFButton        : false
					,PDFHidden          : false
					,formPDFHandler     : _printPDF
					,formExcelHandler   : _printExcel
				}
				,formItems      : [
					standards2.callFunction('_createAffiliateCombo', {
                        module      : _module
                        ,value      : 0
                        ,hasAll     : true
						,allowBlank : true
						,listeners : {
							afterrender : function(){
								var me  = this;
								me.store.load( {
									callback    : function(){
										if( me.store.data.length > 1 ) {
											me.setValue( 0 );
										} else {
											me.setValue( parseInt(Ext.getConstant('AFFILIATEID'),10) );
										}
									}
								} )
							}
						}
                    })
					,standards.callFunction( '_createCombo', {
						id          : 'idCustomer' + _module
						,fieldLabel : 'Customer Name'
						,hasAll     : 1
						,store      : customerStore
						,value      : 0
						,listeners  : {
                            beforeQuery : function(){
                                let affiliate = Ext.getCmp('idAffiliate' + _module ),
                                { data : { items : items } }  = affiliate.getStore();

                                let params = { hasAll : 1 };
                                params['affiliates'] = ( affiliate.getValue() > 0 ) ? affiliate.getValue() : Ext.encode(items.map( item => item.data.id ));

                                this.getStore().proxy.extraParams = params;
                                this.getStore().load({});
                            }
                        }
					} )
					,standards.callFunction( '_createDateRange', {
						id              : 'date' + _module
						,module         : _module
						,fieldLabel     : 'Date'
						,fromWidth      : 230
						,width          : 115
						,date           : Ext.date().subtract(1, 'month').toDate()
					} )
				]
				,moduleGrids    : [ receivableGrid() ]
				,listeners  : {
					afterrender: function(){
						customerStore.proxy.extraParams.hasAll = 1;
						_setDefaultValue( customerStore, 'idCustomer' );
					}
				}
			})
		}

		function schedulePayable( config ){
			var _module = '_Payableschedule' + module
				,_route =  baseurl + 'generalreports/Payableschedule/';

			function _init(){ 
				let supplier = Ext.getCmp('supplierCmb' + _module);
				supplier.getStore().proxy.extraParams.hasAll = 1;
				supplier.getStore().load({
					callback: function(){
						supplier.setValue(0);
					}
				});
			}

			function _beforeViewHandler(){
				// console.log('walay sulod ang Affiliate');
			}

			function _gridListing(){
				var store = standards.callFunction(  '_createRemoteStore' ,{
						fields:[
							{name:'amount',type:'number'} 
							,{name:'balance',type:'number'}
							,'affiliateName' 
							,'date' 
							,'duedate' 
							,'reference' 
							,'supplier'
						]
						,url: _route + "getHistory"
				});
	
				return standards.callFunction( '_gridPanel',{
					id		: 'gridHistory' + _module
					,module	: _module
					,store	: store
					,style	: 'margin-top:10px;'
					,noPage	: true	
					,noDefaultRow : true
					,tbar	: { 
						canPrint                : canPrint
                        ,customListPDFHandler   : _printPDF
                        ,customListExcelHandler : _printExcel
					}
					,columns: [
						{	header 		: 'Affiliate'
							,dataIndex 	: 'affiliateName'
							,minWidth	: 200
							,flex		: 1
						}
						,{	header 		: 'Transaction Date'
							,dataIndex 	: 'date'
							,width 		: 110 
							,xtype		: 'datecolumn'
							,format		: 'm/d/Y'
						}
						,{	header 		: 'Due Date'
							,dataIndex 	: 'duedate'
							,width 		: 110 
							,xtype		: 'datecolumn'
							,format		: 'm/d/Y'
						}
						,{	header 		: 'Reference'
							,dataIndex 	: 'reference'
							,width 		: 100
						}
						,{	header 		: 'Supplier'
							,dataIndex 	: 'supplier'
							,minWidth	: 200
							,flex		: 1
						}
						,{	header 		: 'Amount'
							,dataIndex 	: 'amount'
							,width 		: 110
							,xtype      : 'numbercolumn'
							,format     : '0,000.00'
							,hasTotal   : true
						}
						,{	header 		: 'Balance'
							,dataIndex 	: 'balance'
							,width 		: 110
							,xtype      : 'numbercolumn'
							,format     : '0,000.00'
							,hasTotal   : true
						}
					]
				});
			}
	
			function _printPDF(){
				var _grid = Ext.getCmp('gridHistory' + _module);
	
				standards.callFunction( '_listPDF', {
					grid                    : _grid
					,customListPDFHandler   : function(){
						var par = standards.callFunction( 'getFormDetailsAsObject', {
							module          : _module
							,getSubmitValue : true
						} );
						par.title           = pageTitle;
						
						Ext.Ajax.request( {
							url         : _route + 'printPDF'
							,params     : par
							,success    : function(res){
								if( isGae ){
									window.open( _route + 'viewPDF/' + par.title , '_blank' );
								}
								else{
									window.open( baseurl + 'pdf/generalreports/' + par.title + '.pdf');
								}
							}
						} );
					}
				} );
			}
			
			function _printExcel(){
				var _grid = Ext.getCmp('gridHistory' + _module);
	
				standards.callFunction( '_listExcel', {
					grid                    : _grid
					,customListExcelHandler : function(){
						var par = standards.callFunction( 'getFormDetailsAsObject', {
							module          : _module
							,getSubmitValue : true
						} );
						par.title = pageTitle;
						
						Ext.Ajax.request( {
							url         : _route + 'printExcel'
							,params     : par
							,success    : function(res){
								window.open( _route + "download/" + par.title + '/generalreports');
							}
						} );
					}
				} );
			}

			return standards.callFunction(	'_formPanel' ,{
				config				: config
				,module				: _module
				,moduleType			: 'report'
				,afterResetHandler 	: _init
				,tbar : { 
					noFormButton        : true
					,noListButton       : true
					,noPDFButton        : false
					,PDFHidden          : false
					,formPDFHandler     : _printPDF
					,formExcelHandler   : _printExcel
				}
				,formItems:[
					{
						 xtype : 'container'
						,items:[
							standards2.callFunction( '_createAffiliateCombo', {
								module     	: _module
								,value      : 0
								,hasAll     : true
								,allowBlank : true
								,listeners	: {
									afterrender : function(){
										var me  = this;
										me.store.load( {
											callback    : function(){
												if( me.store.data.length > 1 ) {
													me.setValue( 0 );
												} else {
													me.setValue( parseInt(Ext.getConstant('AFFILIATEID'),10) );
												}
											}
										} )
									}
								}
							} )						
							,standards.callFunction('_createSupplierCombo',{
								module			: _module
								,hasAll      	: 1
								,id				: 'supplierCmb' + _module
								,value			: 0
								,fieldLabel 	: 'Supplier'
								,allowBlank 	: true
								,listeners		: { 
									select		: function( me, record ){ 
										Ext.getCmp( 'gridHistory' + _module ).store.load({
											params: {
												idSupplier	: parseInt( me.value )
												,tDate		: Ext.Date.format( Ext.getCmp( 'tdate' + _module ).getValue(), 'Y-m-d')
											}
										});
									}
								}
							})
							,standards.callFunction( '_createDateRange',{
								sdateID			: 'sdate' + _module
								,edateID		: 'edate' + _module
								,id				: 'payabletransaction' + _module
								,noTime			: true
								,fromFieldLabel	: 'Date'
								,fromWidth      : 230
                        		,width          : 115
							})
						]
					}
				]
				,beforeViewHandler	: _beforeViewHandler
				,moduleGrids 		: _gridListing()
				,listeners			: {
					afterrender : _init
				}
			});
		}

        return{
			initMethod:function( config ){
				route = config.route;
				baseurl = config.baseurl;
				module = config.module;
				canDelete = config.canDelete;
				canPrint = config.canPrint;
				pageTitle = config.pageTitle;
				isGae = config.isGae;
				canEdit = config.canEdit;
				moduleID = config.idmodule;
				
				return _mainPanel( config );
			}
		}
    }
}