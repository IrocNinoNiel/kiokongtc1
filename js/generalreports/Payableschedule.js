/**
 * Developer: Mark Reynor D. Magriña
 * Module: Payable Schedule
 * Date: Feb 18, 2020
 * Finished: 
 * Description: 
 * DB Tables: 
 * */ 

var Payableschedule = function (){
	return function(){
		var baseurl, route, module, canDelete, pageTitle, canPrint, isGae, componentCalling;
		
		function _init(){  
			Ext.getCmp( 'supplierCmb' + module).fireEvent( 'afterrender' );
			Ext.getCmp( 'idAffiliate' + module).fireEvent( 'afterrender' );
		}
		
		function _mainPanel( config ){
			// var supplierCmbStore = standards.callFunction('_createRemoteStore',{
				// fields 	: [ { name:'id', type: 'number' },'name' ]
				// ,url		: route + 'getSupplier'
				// ,autoLoad	: true
			// })
			
			return standards.callFunction(	'_mainPanel' ,{
				config				: config
				,moduleType			: 'report'
				,afterResetHandler 	: _init
				,tbar : {
					noPDFButton        : false
                    ,noExcelButton      : false
                    ,PDFHidden          : false
                    ,formPDFHandler     : _printPDF
                    ,formExcelHandler   : _printExcel
				}
				,formItems:[
					{
						 xtype : 'container'
						,style : 'margin-bottom:10px;'
						,items:[
							
							standards2.callFunction( '_createAffiliateCombo', {
								hasAll      : 1
								,module     : module
								,width      : 381
								,allowBlank : true
								,listeners  : {
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
									,select     : function(){
										var me  = this;
										Ext.getCmp( 'supplierCmb' + module ).store.proxy.extraParams.idAffiliate = me.getValue();
										Ext.getCmp( 'supplierCmb' + module ).store.load( {
											callback   : function() {
												Ext.getCmp( 'idItem' + module ).setValue( 0 );
											}
										} )
									}
								}
							} )						
							,standards.callFunction('_createSupplierCombo',{
								module			: module
								,hasAll      	: 1
								,allowBlank 	: true
								,width      	: 381
								,id				: 'supplierCmb' + module
								// ,store      	: supplierCmbStore
								,fieldLabel 	: 'Supplier'
								,listeners		: { 
									beforeQuery	: function(){ }
									,afterrender : function(){
										var me  = this;
										me.store.load( {
											callback    : function(){
												me.store.proxy.extraParams.idAffiliate = null;
												me.setValue( 0 );
											}
										} )
									}
									,select		: function( me, record ){ 
										Ext.getCmp( 'gridHistory' + module ).store.load({
											params: {
												idSupplier	: parseInt( me.value )
												,tDate		: Ext.Date.format( Ext.getCmp( 'tdate' + module ).getValue(), 'Y-m-d')
											}
										});
									}
								}
							})
							,standards.callFunction( '_createDateRange',{
								sdateID			: 'sdate' + module
								,edateID		: 'edate' + module
								,id				: 'payabletransaction' + module
								,noTime			: true
								,fromFieldLabel	: 'Date'
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
						,'idInvoice'
						,'supplier'
						, 'idModule'
					]
					,url: route + "getHistory"
			});

			return standards.callFunction( '_gridPanel',{
				id		: 'gridHistory' + module
				,module	: module
				,store	: store
				,style	: 'margin-top:10px;'
				,noPage	: true	
				,noDefaultRow : true
				,tbar	: { }
				,viewConfig: {
                    listeners: {
                        itemdblclick: function(dataview, record, item, index, e) {
                            mainView.openModule( record.data.idModule , record.data, this );
                        }
                    }
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
            var _grid = Ext.getCmp('gridHistory' + module);

            standards.callFunction( '_listPDF', {
                grid                    : _grid
                ,customListPDFHandler   : function(){
                    var par = standards.callFunction( 'getFormDetailsAsObject', {
                        module          : module
                        ,getSubmitValue : true
                    } );
                    par.title           = pageTitle;
                    
                    Ext.Ajax.request( {
                        url         : route + 'printPDF'
                        ,params     : par
                        ,success    : function(res){
                            if( isGae ){
                                window.open( route + 'viewPDF/' + par.title , '_blank' );
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
            var _grid = Ext.getCmp('gridHistory' + module);

            standards.callFunction( '_listExcel', {
                grid                    : _grid
                ,customListExcelHandler : function(){
                    var par = standards.callFunction( 'getFormDetailsAsObject', {
                        module          : module
                        ,getSubmitValue : true
                    } );
                    par.title = pageTitle;
                    
                    Ext.Ajax.request( {
                        url         : route + 'printExcel'
                        ,params     : par
                        ,success    : function(res){
                            window.open( route + "download/" + par.title + '/generalreports');
                        }
                    } );
                }
            } );
        }
		
		return{
			initMethod:function( config ){
				route		= config.route;
				baseurl		= config.baseurl;
				module		= config.module;
				canDelete	= config.canDelete;
				canPrint	= config.canPrint;
				pageTitle   = config.pageTitle;
				isGae		= config.isGae;
				idModule 	= config.idmodule;
				
				return _mainPanel( config );
			}
		}
	}
}