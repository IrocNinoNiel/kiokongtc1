/**
 * Developer: Mark Reynor D. Magriña
 * Module: Payable Transaction
 * Date: Feb 18, 2020
 * Finished: 
 * Description: 
 * DB Tables: 
 * */ 
  
// function userlog(){
var Payabletransaction = function (){
	return function(){
		var baseurl, route, module, canDelete, pageTitle, canPrint, isGae, idAffiliate, idModule;
		
		function _init(){  
			Ext.getCmp( 'supplierCmb' + module ).fireEvent( 'afterrender' );
			Ext.getCmp( 'idAffiliate' + module ).fireEvent( 'afterrender' );
		}
		
		function _mainPanel( config ){
			return standards.callFunction(	'_mainPanel' ,{
				config				: config
				,moduleType			: 'report'
				,afterResetHandler 	: _init
				,tbar           	: {
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
						,style : 'margin-bottom:10px;'
						,items:[
							standards2.callFunction( '_createAffiliateCombo', {
								module     : module
								,width      : 381
								,allowBlank : true
								,hasAll		: 1
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
								}
							} )						
							,standards.callFunction('_createSupplierCombo',{
								module			: module
								,hasAll      	: 1
								,width      	: 381
								,allowBlank		: true
								,id				: 'supplierCmb' + module
								,fieldLabel 	: 'Supplier'
								,listeners		: { 
									afterrender	: function(){ 
										var me  = this;
										me.store.load( {
											callback    : function(){
												me.setValue( 0 );
											}
										} )
									}
								}
							})
							,standards.callFunction( '_createDateRange',{
								sdateID			: 'sdate' + module
								,edateID		: 'edate' + module
								,id				: 'payabletransaction' + module
								,noTime			: true
								,fromFieldLabel	: 'Date'
								,date           : Ext.date().subtract(1, 'month').toDate()
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
						{name:'amount',type:'float'} ,{name:'balance',type:'float'}
						,'affiliateName' ,'date' ,'duedate' ,'reference' ,'supplier'
						,'idInvoice' ,'idModule'
					]
					,url: route + "getHistory"
			});

			return standards.callFunction( '_gridPanel',{
				id		: 'gridHistory' + module
				,module	: module
				,store	: store
				,style	: 'margin-top:10px;'
				,tbar	: 'empty'
				,height	: 530
				,noPage	: true	
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
						,width 		: 200 
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
						,width 		: 200
						,minWidth	: 200
						,flex		: 1
					}
					,{	header 		: 'Amount'
						,dataIndex 	: 'amount'
						,width 		: 110
						,xtype      : 'numbercolumn'
                        ,format     : '0,000.00'
					}
					,{	header 		: 'Balance'
						,dataIndex 	: 'balance'
						,width 		: 110
						,xtype      : 'numbercolumn'
                        ,format     : '0,000.00'
					}
				]
			});
		}
		
		function _printPDF(){
			var _grid               = Ext.getCmp( 'gridHistory' + module );
            standards.callFunction( '_listPDF', {
                grid                    : _grid
                ,customListPDFHandler   : function(){
                    var par = standards.callFunction( 'getFormDetailsAsObject', {
                        module          : module
						,getSubmitValue : true
						,idAffiliate	: idAffiliate
						,idModule		: idModule
                    } );
                    par.title               = pageTitle;
                    Ext.Ajax.request( {
                        url         : route + 'printPDF'
                        ,params     : par
                        ,success    : function(res){
                            if( isGae ){
                                window.open( route + 'viewPDF/' + par.title , '_blank' );
                            }
                            else{
                                window.open( baseurl + 'pdf/inventory/' + par.title + '.pdf');
                            }
                        }
                    } );
                }
            } );
		}
		function _printExcel(){
			var _grid = Ext.getCmp( 'gridHistory' + module );

            standards.callFunction( '_listExcel', {
                grid                    : _grid
                ,customListExcelHandler : function(){
                    var par = standards.callFunction( 'getFormDetailsAsObject', {
                        module          : module
                        ,getSubmitValue : true
                        ,idAffiliate	: idAffiliate
                        ,idModule		: idModule
                    } );
                    par.title = pageTitle;
                    
                    Ext.Ajax.request( {
                        url         : route + 'printExcel'
                        ,params     : par
                        ,success    : function(res){
                            window.open( route + "download/" + par.title + '/inventory');
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