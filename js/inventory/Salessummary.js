function Salessummary(){
    return function(){
        var baseurl, route, module, canDelete, canPrint,  pageTitle, idModule, isGae,isSaved = 0,deletedItems = [],selectedItem = [], idAffiliate;

        function _init(){

        }

        function _mainPanel(config){
            return standards.callFunction(	'_mainPanel' ,{
                config		        : config
                ,moduleType	        : 'report'
                ,tbar               : {
					noFormButton        : true
                    ,noListButton       : true
                    ,noPDFButton        : false
                    ,PDFHidden          : false
                    ,formPDFHandler     : _printPDF
                    ,formExcelHandler   : _printExcel
                }

                ,formItems  :[
                    {	
                        xtype		: 'container'
                        ,layout		: 'column'
                        ,padding	: 10
                        ,items		: [
                            {
                                xtype			: 'container'
								,columnWidth	: .5
								,items			: __filterLeft()
                            }
                            ,{
                                xtype			: 'container'
								,columnWidth	: .5
								,items			: __filterRight()
                            }
                        ]
                    }
                    
                ]
                ,moduleGrids:[
                    {
                        xtype			: 'fieldset'
                        ,title          : 'Sales'
                        ,columnWidth	: 1
                        ,items			: [
                            {
                                xtype		: 'container'
                                ,items      :[
                                    __salesSummary()
                                ]
                            }
                        ]
                    },
                    {
                        xtype			: 'fieldset'
                        ,title          : 'Sales Return'
                        ,columnWidth	: 1
                        ,items			: [
                            {
                                xtype		: 'container'
                                ,items      :[
                                    __salesReturnSummary()
                                ]
                            }
                        ]
                    }
                ]
            })
        }

        function __filterLeft(){      
            let soref = standards.callFunction('_createRemoteStore',{
                fields:[
                    {	name	: 'id'
                        ,type	: 'number'
                    }
                    ,'name'
                ]
                ,url: route + 'getSalesReference'
            })      
            let payment = standards.callFunction( '_createLocalStore' , {
                data    : [
                    'All'
                    ,'Cash'
                    ,'Charge'
                ]
            } )
            
            return [
                standards2.callFunction( '_createAffiliateCombo', {
                    module		: module
                    ,allowBlank : true
                    ,hasAll     : true
                    ,value      : 0
                })
                ,standards.callFunction( '_createCombo', {
                    id              : 'reference' + module
                    ,fieldLabel     : 'Sales Reference'
                    ,allowBlank     : true
                    ,emptyText		: 'Select Sales Reference'
                    ,displayField   : 'name'
                    ,valueField     : 'id'
                    ,store          : soref
                    ,value          : 0
                    ,listeners      :{
                        beforeQuery: function(me, record){
                            this.store.proxy.extraParams = {
                                affiliate: Ext.valued('idAffiliate'+module)
                                ,costcenter: Ext.valued('idCostCenter'+module)
                            }
                        },
                        afterrender: function(){
                            this.store.proxy.extraParams = {
                                affiliate: Ext.valued('idAffiliate'+module)
                                ,costcenter: Ext.valued('idCostCenter'+module)
                            }
                            this.store.load({
                                callback:()=>{
                                    Ext.valued('reference' + module, 0)
                                }
                            })
                        }
                    }
                } )
                
                ,standards.callFunction( '_createCombo', {
                    id              : 'payment' + module
                    ,fieldLabel     : 'Payment Method'
                    ,allowBlank     : true
                    ,store          : payment
                    ,emptyText		: 'Select Payment Method'
                    ,displayField   : 'name'
                    ,valueField     : 'id'
                    ,value          : 1
                } )
               ,{
                   xtype : 'container'
                   ,layout: 'column'
                   ,items: [
                        {
                            xtype : 'container'
                            ,items : [
                                standards.callFunction( '_createDateField', {
                                    id              : 'datefrom' + module
                                    ,fieldLabel     : 'Date and Time From'
                                    ,allowBlank     : true
                                    ,width          : 240
                                    ,value          : Ext.date().subtract(1, 'month').toDate()
                                })
                             ]
                        },
                        {
                            xtype : 'container'
                            ,columnWidth : .6
                            ,items : [
                                standards.callFunction( '_createTimeField', {
                                    id              : 'timeFrom' + module
                                    ,allowBlank     : true
                                    ,width          : 105
                                    ,style          : 'margin-left:5px;'
                                    ,value          : '12:15 AM'
                                })
                             ]
                        }
                   ]
               }
               ,{
                    xtype : 'container'
                    ,layout: 'column'
                    ,items: [
                        {
                            xtype : 'container'
                            ,items : [
                                standards.callFunction( '_createDateField', {
                                    id              : 'dateto' + module
                                    ,fieldLabel     : 'Date and Time To'
                                    ,allowBlank     : true
                                    ,width          : 240
                                })
                            ]
                        },
                        {
                            xtype : 'container'
                            ,columnWidth : .6
                            ,items : [
                                standards.callFunction( '_createTimeField', {
                                    id              : 'timeto' + module
                                    ,allowBlank     : true
                                    ,width          : 105
                                    ,style          : 'margin-left:5px;'
                                    ,value          : '11:00 PM'
                                })
                            ]
                        }
                    ]
                }
            ]
        }

        function __filterRight(){
            let customer = standards.callFunction(  '_createRemoteStore' ,{
                fields:[
                    {	name	: 'id'
                        ,type	: 'number'
                    }
                    ,'name'
                ]
                ,url: route + 'getCustomer'
            });
            let vat = standards.callFunction( '_createLocalStore' , {
                data    : [
                    'All'
                    ,'Inclusive'
                    ,'Exclusive'
                ]
            } )
            return [
                standards.callFunction( '_createCombo', {
                    id              : 'vat' + module
                    ,fieldLabel     : 'VAT Types'
                    ,allowBlank     : true
                    ,store          : vat
                    ,emptyText		: 'Select VAT Types'
                    ,displayField   : 'name'
                    ,valueField     : 'id'
                    ,value          : 1
                } )
                ,standards.callFunction( '_createCombo', {
                    id              : 'customer' + module
                    ,fieldLabel     : 'Customer'
                    ,allowBlank     : true
                    ,store          : customer
                    ,emptyText		: 'Select Customer'
                    ,displayField   : 'name'
                    ,valueField     : 'id'
                    ,value          : 0
                    ,listeners      :{
                        beforeQuery: function(me, record){
                            this.store.proxy.extraParams.idAffiliate = Ext.valued('idAffiliate'+module)
                        },
                        afterrender: function(){
                            this.store.proxy.extraParams.idAffiliate = Ext.valued('idAffiliate'+module)
                            this.store.load({
                                callback:()=>{
                                    Ext.valued('customer' + module, 0)
                                }
                            })
                        }
                    }
                } )
               
            ]
        }

        function __salesSummary(){
            var sales = standards.callFunction(  '_createRemoteStore' ,{
                fields:[
                    'affiliateName'
                    ,'date'
                    ,'idInvoice'
                    ,'idModule'
                    ,'reference'
                    ,'customer'
                    ,{	name	: 'sales'
                        ,type	: 'number'
                    }
                    ,{	name	: 'vat'
                        ,type	: 'number'
                    }
                    ,{	name	: 'withvat'
                        ,type	: 'number'
                    }
                    ,{	name	: 'discount'
                        ,type	: 'number'
                    }
                    ,{	name	: 'amount'
                        ,type	: 'number'
                    }
                    ,'status'
                    ,'salesman'
                    ,'vattype'
                ]
                ,url: route + 'getSales'
            });
            return standards.callFunction( '_gridPanel',{
                id		        : 'salesGrid' + module
                ,module	        : module
				,tbar           :'empty'
                ,height         : 200
                ,style          : 'margin-bottom:10px;'
                ,store	        : sales
                ,noDefaultRow   : true
                ,noPage         : true
                ,sortable       : false
                ,viewConfig: {
                    listeners: {
                        itemdblclick: function(dataview, record, item, index, e) {
                            mainView.openModule( record.data.idModule , record.data, this );
                        }
                    }
                }
                ,columns: [
                    {
                        header: 'Affiliate'
                        ,dataIndex: 'affiliateName'
                    }
                    ,{
                        header  : 'Date'
                        ,dataIndex : 'date'
                        ,xtype  : 'datecolumn'
                        ,format : 'm/d/Y h:i A'
                    }
                    ,{
                        header : 'Reference'
                        ,dataIndex : 'reference'
                    }
                    ,{
                        header : 'Customer'
                        ,dataIndex : 'customer'
                    }
                    ,{
                        header : 'Sales Amount'
                        ,hasTotal: true
                        ,dataIndex : 'sales'
                        ,xtype : 'numbercolumn'
						,format : '0,000.00'
                    }
                    ,{
                        header : 'Vat Amount'
                        ,hasTotal: true
                        ,dataIndex : 'vat'
                        ,xtype : 'numbercolumn'
						,format : '0,000.00'
                    }
                    ,{
                        header : 'Sales with VAT'
                        ,hasTotal: true
                        ,dataIndex : 'withvat'
                        ,xtype : 'numbercolumn'
						,format : '0,000.00'
                    }
                    ,{
                        header : 'Discount'
                        ,hasTotal: true
                        ,dataIndex : 'discount'
                        ,xtype : 'numbercolumn'
						,format : '0,000.00'
                    }
                    ,{
                        header : 'Net Sales'
                        ,hasTotal: true
                        ,dataIndex : 'amount'
                        ,xtype : 'numbercolumn'
						,format : '0,000.00'
                    }
                    ,{
                        header : 'Salesman'
                        ,dataIndex : 'salesman'
                    }
                    ,{
                        header : 'VAT Type'
                        ,dataIndex : 'vattype'
                    }
                ]
            })
        }

        function __salesReturnSummary(){
            var salesReturn = standards.callFunction(  '_createRemoteStore' ,{
                fields:[
                    'affiliateName'
                    ,'date'
                    ,'id'
                    ,'idModule'
                    ,'salereference'
                    ,'salereturnreference'
                    ,'customer'
                    ,{
                        name: 'amount'
                        ,type: 'number'
                    }
                    ,'salesman'
                ]
                ,url: route + 'getSalesReturn'
            });
            return standards.callFunction( '_gridPanel',{
                id		        : 'salesReturnGrid' + module    
                ,module	        : module
                ,height         : 200
                ,style          : 'margin-bottom:10px;'
                ,store	        : salesReturn
				,tbar           :'empty'
                ,noDefaultRow   : true
                ,noPage         : true
                ,viewConfig: {
                    listeners: {
                        itemdblclick: function(dataview, record, item, index, e) {
                            mainView.openModule( record.data.idModule , record.data, this );
                        }
                    }
                }
                ,columns: [
                    {
                        header: 'Affiliate'
                        ,dataIndex: 'affiliateName'
                        ,width: 150
                    }
                    ,{
                        header  : 'Date'
                        ,dataIndex : 'date'
                        ,xtype  : 'datecolumn'
                        ,format : 'm/d/Y h:i A'
                        ,width: 120
                    }
                    ,{
                        header : 'Sales Return Reference'
                        ,dataIndex : 'salereturnreference'
                        ,width: 150
                    }
                    ,{
                        header : 'Sales Reference'
                        ,dataIndex : 'salereference'
                        ,width: 100
                    }
                    ,{
                        header : 'Customer'
                        ,dataIndex : 'customer'
                        ,minWidth: 100
                        ,flex:1
                    }
                    ,{
                        header : 'Amount'
                        ,hasTotal: true
                        ,xtype : 'numbercolumn'
                        ,format : '0,000.00'
                        ,dataIndex : 'amount'
                        ,width: 150
                    }
                    ,{
                        header : 'Salesman'
                        ,dataIndex : 'salesman'
                        ,width: 150
                    }
                ]
            })
        }

        function _printPDF(){
            standards.callFunction('_listPDF',{
				grid 		: Ext.getCmp('salesGrid'+module)
				,customListPDFHandler : function(){
					var par  = standards.callFunction('getFormDetailsAsObject',{
						module : module
						,getSubmitValue : true
					});
					par.title = pageTitle ;
					
					Ext.Ajax.request({
						url: route + 'printPDF'
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
            })
        }

        function _printExcel (){
            standards.callFunction('_listExcel',{
				grid 		: Ext.getCmp('salesGrid'+module)
				,customListExcelHandler : function(){
					var par  = standards.callFunction('getFormDetailsAsObject',{
						module : module
						,getSubmitValue : true
					});
					par.title = pageTitle
					
					Ext.Ajax.request({
						url: route+'printExcel'
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
				pageTitle   = config.pageTitle;
				idModule	= config.idmodule
				isGae		= config.isGae;
                idAffiliate = config.idAffiliate;
                canPrint    = config.canPrint
				
				return _mainPanel( config );
			}
		}
    }
}