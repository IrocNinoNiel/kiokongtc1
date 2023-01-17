
function Salesordermonitoring(){
	return function(){
		var baseurl, route, module, canDelete, pageTitle, canPrint, moduleLedger, isGae, idAffiliate;
		
		function _init(){
		
		}
		
		function _mainPanel( config ){
			
			return standards.callFunction(	'_mainPanel' ,{
				config		: config
				,moduleType	: 'report'
				,afterResetHandler : _init
				,customExcelHandler : _printEXCEL
				,customPDFHandler : _printPDF
				,tbar : {
					noPDFButton    : false
                    ,noExcelButton  : false
                    ,PDFHidden      : false
				}
				,extraFormTab : [
					{	xtype : 'button'
						,buttonId : 'btnMonitoring' + module
						,activeButton : true
						,buttonLabel : 'SO Monitoring'
						,items : monitoring()
					}
					,{	xtype : 'button'
						,buttonId : 'btnLedger' + module
						,buttonIconCls : 'list'
						,buttonLabel : 'Ledger'
						,items : ledger()
					}
				]
				,listeners: {
					afterrender : _init
				}
			});
		}
		
		function monitoring(){
            var search = standards.callFunction( '_createLocalStore' , {
                data    : [
                    'All'
                    ,'Customer'
                    ,'Item'
                ]
			} )
            var status = standards.callFunction( '_createLocalStore' , {
                data    : [
                    'All'
                    ,'Complete'
                    ,'Incomplete'
                    ,'Not Served'
                ]
            } )
			return standards.callFunction('_formPanel',{
				moduleType : 'report'
				,panelID : 'balancePanel' + module
				,noHeader : true
				,module : module
				,formItems  : [
					{
						xtype : 'container'
						,style : 'margin-bottom:10px;'
						,items : [
                            standards2.callFunction( '_createAffiliateCombo', {
								module		: module
								,hasAll		: 'false'
								,allowBlank	: 'true'
                                ,value 		: 0
                            })
                            ,{
                                xtype : 'container'
                                ,layout : 'column'
                                ,items		: [
                                    {
										xtype: 'container'
										
                                        ,items : [
                                            standards.callFunction( '_createCombo', {
                                                id              : 'search' + module
                                                ,fieldLabel     : 'Search By'
                                                ,store          : search
                                                ,emptyText		: 'Search By'
                                                ,displayField   : 'name'
                                                ,valueField     : 'id'
                                                ,value          : 1
                                                ,width          : 210
												,listeners 		: {
													select : function( field, newValue, oldValue ){
														Ext.getCmp('filter' + module).reset();
														Ext.getCmp('filter' + module).fireEvent('beforeQuery');
													}
												}
                                            } )
                                        ]
                                    }
                                    ,{
                                        xtype: 'container'
                                        ,columnWidth : .5
                                        ,items : [
											standards.callFunction( '_createCombo', {
                                                id              : 'filter' + module
                                                ,fieldLabel     : ''
                                                ,store          : getFilterStore()
                                                ,emptyText		: 'Search filter...'
                                                ,displayField   : 'name'
                                                ,valueField     : 'id'
												,width          : 135
												,style          : 'margin-left: 5px;'
												,hideTrigger	: true
												,listeners		:{
													beforeQuery: function(){
														this.store.proxy.extraParams = {
															search_by : Ext.valued('search' + module), 
															affiliate : Ext.valued('affiliate'+module)
														}
													}
													,select: function (){
														let {data:{type}} = this.findRecord(this.valueField, this.getValue())
														Ext.valued('typedSearched' + module, type)
													}
												}
                                            } )
                                        ]
                                    }
                                ]
							}
							,standards.callFunction( '_createTextField', {
								id          : 'typedSearched' + module
								,allowBlank : true
								,value 		: 0
								,hidden		: true
								
							} )  
                            ,standards.callFunction( '_createCombo', {
                                id              : 'status' + module
                                ,fieldLabel     : 'Select Status'
                                ,store          : status
                                ,emptyText		: 'Select status...'
                                ,displayField   : 'name'
                                ,valueField     : 'id'
                                ,value          : 1
                            } )
                            ,standards.callFunction( '_createDateField', {
                                id              : 'dateto' + module
                                ,fieldLabel     : 'As of:'
                            } )
						]
					}
				]
				,moduleGrids : balanceGrid()
			});
		}
		
		function ledger(){
            var sonumber = standards.callFunction(  '_createRemoteStore' ,{
                fields:[
                    {	name	: 'id'
                        ,type	: 'number'
                    }
                    ,'name'
                ]
                ,url: route + 'getSOList'
            });
            
            
            
			return standards.callFunction('_formPanel',{
				moduleType : 'report'
				,panelID : 'ledgerPanel' + moduleLedger
				,noHeader : true
				,module : moduleLedger
				,sortable : false
				,beforeViewHandler : function(){
					if(!Ext.valued('idAffiliate'+moduleLedger)){
						standards.callFunction('_createMessageBox',{
							msg : 'Please select affiliate.'
						});
						return true;
					}
					if(!Ext.valued('sonumber'+moduleLedger)){
						standards.callFunction('_createMessageBox',{
							msg : 'Please select so number.'
						});
						return true;
					}
					if(!Ext.valued('itemname'+moduleLedger)){
						standards.callFunction('_createMessageBox',{
							msg : 'Please select item.'
						});
						return true;
					}
					return false;
				}
				,formItems  : [
					{
						xtype : 'container'
						,style : 'margin-bottom:10px;'
						,items : [
							standards2.callFunction( '_createAffiliateCombo', {
                                module		: moduleLedger
                                ,allowBlank: true
                                ,value : parseInt(idAffiliate, 10)
                            })
							,standards.callFunction( '_createCombo', {
                                id              : 'customer' + moduleLedger
                                ,fieldLabel     : 'Customer'
                                ,allowBlank     : true
                                ,store          : getFilterStore()
                                ,emptyText		: 'Select customer...'
                                ,displayField   : 'name'
                                ,valueField     : 'id'
                                ,listeners		: {
									beforeQuery: function(){
										this.store.proxy.extraParams={
											search_by : 2,
											affiliate : Ext.valued('idAffiliate'+moduleLedger)
										}
									}
									,select : function(){
										Ext.valued('sonumber'+moduleLedger,null)
										Ext.valued('itemname'+moduleLedger,null)
									}
								}
							} )
							
                            ,standards.callFunction( '_createCombo', {
                                id              : 'sonumber' + moduleLedger
                                ,fieldLabel     : 'SO Number'
                                ,allowBlank     : true
                                ,store          : sonumber
                                ,emptyText		: 'Select so number...'
                                ,displayField   : 'name'
                                ,valueField     : 'id'
								,value          : 0
								,listeners		:{
									beforeQuery: function(){
										this.store.proxy.extraParams={
											affiliate : Ext.valued('idAffiliate'+moduleLedger),
											customer : Ext.valued('customer'+moduleLedger)
										}
									}
								}
                            } )
							,standards.callFunction( '_createCombo', {
                                id              : 'itemname' + moduleLedger
                                ,fieldLabel     : 'Item Name'
                                ,allowBlank     : true
                                ,store          : getFilterStore()
                                ,emptyText		: 'Select item name...'
                                ,displayField   : 'name'
                                ,valueField     : 'id'
                                ,listeners		: {
									beforeQuery: function(){
										this.store.proxy.extraParams={
											search_by : 3,
											affiliate : Ext.valued('idAffiliate'+moduleLedger),
											sonumber: Ext.valued( 'sonumber' + moduleLedger)
										}
									}
								}
                            } )
                            ,{
                                xtype : 'container'
                                ,layout : 'column'
                                ,items		: [
                                    {
                                        xtype: 'container'
                                        ,items : [
                                            standards.callFunction( '_createDateField', {
                                                id              : 'datefrom' + moduleLedger
                                                ,fieldLabel     : 'Date:'
												,width          : 230
												,value			:Ext.date().subtract(1,'month').toDate()
                                            } )
                                        ]
                                    }
                                    ,{
                                        xtype: 'container'
                                        ,columnWidth : .5
                                        ,items : [
                                            standards.callFunction( '_createDateField', {
                                                id              : 'dateto' + moduleLedger
                                                ,fieldLabel     : 'to:'
                                                ,labelWidth     : 12
                                                ,width          : 115
                                                ,style          : 'margin-left:5px;'
                                            } )
                                        ]
                                    }
                                ]
                            }
						]
					}
				]
				,moduleGrids : ledgerGrid()
			});
		}
		
		function balanceGrid(){
			var groupingFeature = Ext.create('Ext.grid.feature.Grouping',{
				groupHeaderTpl: 'SO: {name} ({rows.length} Item{[values.rows.length > 1 ? "s" : ""]})'
			});
			var store = standards.callFunction(  '_createRemoteStore' ,{
					fields:[
						'affiliate'
						,'date'
						,'sonumber'
						,'customer'
						,'item'
						,'unit'
						,'expectedqty'
						,'actualqty'
						,{
							type: 'number'
							,name: 'balance'
						}
						,'status'
						,{
							type: 'number'
							,name : 'idInvoice'
						}
						,{
							type: 'number'
							,name : 'idModule'
						}
						,{
							type: 'number'
							,name: 'idAffiliate'
						}
						,{
							type: 'number'
							,name: 'pCode'
						}
						,{
							type: 'number'
							,name: 'idItem'
						}
					]
					,groupField: 'sonumber'
					,url: route + "getReleasable"
			});

			return standards.callFunction( '_gridPanel',{
				id: 'balanceGrid' + module
				,module: module
				,store: store
				,style:'margin-top:10px;'
				,tbar:'empty'
				,noDefaultRow : true
				,grouping : [groupingFeature]
				,sortable : false
				,viewConfig: {
                    listeners: {
                        itemdblclick: function(dataview, record, item, index, e) {
                            mainView.openModule( record.data.idModule , record.data, this );
                        }
                    }
                }
				,columns:[
					{	header : 'Affiliate'
						,dataIndex : 'affiliate'
						,width : 100
					}
					,{	header : 'Date'
						,dataIndex : 'date'
						,width : 100
					}
					,{	header : 'SO Number'
						,dataIndex : 'sonumber'
						,width : 80
					}
					,{	header : 'Customer'
						,dataIndex : 'customer'
						,flex : 1
					}
					,{	header : 'Item Name'
						,dataIndex : 'item'
					}
					,{	header : 'Unit'
						,dataIndex : 'unit'
						,width : 80
					}
					,{	header : 'Expected Qty'
						,dataIndex : 'expectedqty'
						,xtype : 'numbercolumn'
						,format : '0,000'
						,width : 80
					}
					,{	header : 'Actual Qty'
						,dataIndex : 'actualqty'
						,xtype : 'numbercolumn'
						,format : '0,000'
						,width : 80
					}
					,{	header : 'Balance'
						,dataIndex : 'balance'
						,xtype : 'numbercolumn'
						,format : '0,000'
						,width : 80
						,hasTotal : true
						,summaryType: 'sum'
					}
					,{	header : 'Status'
						,dataIndex : 'status'
					}
					,standards.callFunction( '_createActionColumn', {
						icon : 'th-list'
						,tooltip : 'View Ledger'
						,Func : function(rec){
							console.log(rec)
							___storeLoad('idAffiliate'+moduleLedger).then(()=>{
							// 	Ext.valued('idAffiliate'+moduleLedger,rec.idAffiliate)
							// 	return ___storeLoad('idCostCenter'+moduleLedger, {idAffiliate: rec.idAffiliate, hasAll: true})
							// }).then(()=>{
								Ext.valued('idCostCenter'+moduleLedger,rec.idCostCenter)
								return ___storeLoad('customer'+moduleLedger, {search_by: 2, query: '', affiliate: rec.idAffiliate})
							}).then(()=>{
								Ext.valued('customer'+moduleLedger,rec.pCode)
								return ___storeLoad('sonumber'+moduleLedger, {affiliate: rec.idAffiliate,  customer: rec.pCode})
							}).then(()=>{
								Ext.valued('sonumber'+moduleLedger,rec.idInvoice)
								return ___storeLoad('itemname'+moduleLedger, {search_by: 3, query: '', affiliate: rec.idAffiliate, sonumber: rec.idInvoice})
							}).then(()=>{
								Ext.valued('itemname'+moduleLedger,rec.idItem)
								let dated = Ext.toMoment(Ext.valued('dateto'+module))
								Ext.getCmp('btnLedger'+module).handler();
								Ext.valued('dateto'+moduleLedger, dated.toDate())
								Ext.valued('datefrom'+moduleLedger, dated.subtract(1, 'month').toDate())
								Ext.getCmp('viewButton' + moduleLedger).handler();
								
							})
						}
					})
				]
				
			});
		}

		function ___storeLoad(name, params = {}){
			return new Promise((resolve, reject)=>{
				Ext.getCmp(name).store.load({
					params,
					callback(){
						resolve(true)
					}
				})
			})
		}
		
		function getFilterStore(){
			return standards.callFunction( '_createRemoteStore' , {
                fields:[
                    {	name	: 'id'
                        ,type	: 'number'
                    }
					,'name'
					,'type'
                ]
                ,url: route + 'getFilters'
            } )
		}

		function ledgerGrid(){
			var store = standards.callFunction(  '_createRemoteStore' ,{
					fields:[
						'id'
						,'date'
						,'reference'
						,'expectedqty'
						,'deliveredqty'
						,'balance'
						,'idInvoice'
						,'idModule'
					], 
					url: route + "getLedger"
			});
			return standards.callFunction( '_gridPanel',{
				id: 'ledgerGrid' + moduleLedger
				,module: moduleLedger
				,store: store
				,style:'margin-top:10px;'
				,tbar:'empty'
				,sortable : false
				,noDefaultRow : true
				,tbar : { }
				,viewConfig: {
                    listeners: {
                        itemdblclick: function(dataview, record, item, index, e) {
                            mainView.openModule( record.data.idModule , record.data, this );
                        }
                    }
                }
				,columns:[
					{	header : 'Date'
						,dataIndex : 'date'
						,xtype : 'datecolumn'
						,width : 150
						,sortable : false
					}
					,{	header : 'Reference No.'
						,dataIndex : 'reference'
						,minWidth : 150
						,flex	:1
						,sortable : false
						// ,renderer:function(val,meta,record){
						// 	return standards.callFunction('goToTransaction',{
						// 		text:val
						// 		,invoiceID:record.get('id')
						// 	});
						// }
					}
					,{	header : 'Expected Qty'
						,dataIndex : 'expectedqty'
						,xtype : 'numbercolumn'
						,format : '0,000'
						,width : 150
						,sortable : false
					}
					,{	header : 'Delivered Qty'
						,dataIndex : 'deliveredqty'
						,xtype : 'numbercolumn'
						,format : '0,000'
						,width : 150
						,sortable : false
					}
					,{	header : 'Balance'
						,dataIndex : 'balance'
						,xtype : 'numbercolumn'
						,format : '0,000'
						,width : 150
						,hasTotal : true
						,hasTotalType : 'running'
						,sortable : false
					}
				]
				
			});
		}
		
		function _printPDF(){
			var isBalance = Ext.getCmp('btnMonitoring' + module).cls == 'menuActive' ? 1 : 0;
			var m = isBalance ? module : moduleLedger;
			var grid = isBalance ? Ext.getCmp( 'balanceGrid' + m) : Ext.getCmp( 'ledgerGrid' + m);
			
			standards.callFunction('_listPDF',{
				grid 		: grid
				,customListPDFHandler : function(){
					var par  = standards.callFunction('getFormDetailsAsObject',{
						module : m
						,getSubmitValue : true
					});
					par.title = pageTitle + ' - ' + ( isBalance ? 'Balance' : 'Ledger' ) ;
					par.isBalance = isBalance;
					
					Ext.Ajax.request({
						url: route + 'printPDF/' + ( isBalance ? 'Balance' : 'Ledger' )
						,params:par
						,success: function(res){
							if( isGae ){
								window.open( route + 'viewPDF/' + par.title , '_blank' );
							}
							else{
								window.open( baseurl + 'pdf/inventory/' + par.title + '.pdf');
							}
						}
					});
				}
			});
		}
		
		function _printEXCEL(){
			let isBalance = Ext.getCmp('btnMonitoring' + module).cls == 'menuActive' ? 1 : 0;
			let m = isBalance ? module : moduleLedger;
			let grid = isBalance ? Ext.getCmp( 'balanceGrid' + m) : Ext.getCmp( 'ledgerGrid' + m);
			
			standards.callFunction('_listExcel',{
				grid 		: grid
				,customListExcelHandler : function(){
					var par  = standards.callFunction('getFormDetailsAsObject',{
						module : m
						,getSubmitValue : true
					});
					par.title = pageTitle + ' - ' + ( isBalance ? 'Balance' : 'Ledger' ) ;
					par.isBalance = isBalance;
					
					Ext.Ajax.request({
						url: route+'printExcel/' + ( isBalance ? 'Balance' : 'Ledger' )
						,params:par
						,success: function(){
							window.open( route + "download/" + par.title + '/inventory');
						}
					});
				}
			});
		}
		
		return{
			initMethod:function( config ){
				route		= config.route;
				baseurl		= config.baseurl;
				module		= config.module;
				canDelete	= config.canDelete;
				canPrint	= config.canPrint;
				pageTitle   = config.pageTitle;
                isGae   = config.isGae;
                idAffiliate = config.idAffiliate;
				moduleLedger = module + '_Ledger';
				return _mainPanel( config );
			}
		}
	}
}