function Salesreturnsummary(){
    return function(){
        var baseurl, route, module, canDelete, pageTitle, idModule, isGae,isSaved = 0,deletedItems = [],selectedItem = [], idAffiliate;

        function _init(){

        }

        function _mainPanel(config){
            return standards.callFunction(	'_mainPanel' ,{
                config		: config
                ,moduleType	: 'report'
                ,tbar       : {
					noFormButton : true
                    ,noListButton : true
                    ,noPDFButton : false
                    ,PDFHidden : false
                    ,formPDFHandler     : _printPDF
                    ,formExcelHandler   : _printExcel
                }
                ,formItems  :[
                    {	
                        xtype		: 'container'
                        ,layout		: 'column'
                        ,style      : 'margin-bottom : 5px;'
                        ,items		: [
                            {
                                xtype			: 'container'
								,columnWidth	: .5
								,items			: __filterLeft()
                            }
                        ]
                    }
                    
                ]
                ,moduleGrids:[
                    __salesReturnSummary()
                ]
            })
        }


        function __filterLeft(){
            
            var reference = standards.callFunction(  '_createRemoteStore' ,{
                fields:[
                    {	name	: 'id'
                        ,type	: 'number'
                    }
                    ,'name'
                ]
                ,url: route + 'getReference'
            });
            var item = standards.callFunction(  '_createRemoteStore' ,{
                fields:[
                    {	name	: 'id'
                        ,type	: 'number'
                    }
                    ,'name'
                ]
                ,url: route + 'getItem'
            });
            return [
                standards2.callFunction( '_createAffiliateCombo', {
                    module		: module
                    ,allowBlank : true
                    ,hasAll     : true
                    ,value      : 0
                })

                ,standards.callFunction( '_createCombo', {
                    id              : 'reference' + module
                    ,fieldLabel     : 'Reference'
                    ,allowBlank     : true
                    ,store          : reference
                    ,emptyText		: 'Select reference ...'
                    ,displayField   : 'name'
                    ,valueField     : 'id'
                    ,value          : 0
                    ,listeners      :{
                        beforeQuery :function (){
                            this.store.proxy.extraParams = {
                                affiliate: Ext.valued('idAffiliate'+module)
                            }
                        }
                        ,afterrender: function(){
                            this.store.load({
                                params:{
                                    affiliate: Ext.valued('idAffiliate'+module)
                                },
                                callback: function(){
                                    Ext.valued('reference'+module, 0)
                                }
                            })
                        }
                    }
                } )
                ,standards.callFunction( '_createCombo', {
                    id              : 'item' + module
                    ,fieldLabel     : 'Item Name'
                    ,allowBlank     : true
                    ,store          : item
                    ,emptyText		: 'Select item name...'
                    ,displayField   : 'name'
                    ,valueField     : 'id'
                    ,value          : 0
                    ,listeners      :{
                        beforeQuery :function (){
                            this.store.proxy.extraParams = {
                                affiliate: Ext.valued('idAffiliate'+module)
                                ,costcenter: Ext.valued('idCostCenter'+module)
                                ,reference : Ext.valued('reference'+module)
                            }
                        }
                        ,afterrender: function(){
                            this.store.load({
                                params:{
                                    affiliate: Ext.valued('idAffiliate'+module)
                                    ,costcenter: Ext.valued('idCostCenter'+module)
                                    ,reference : Ext.valued('reference'+module)
                                },
                                callback: function(){
                                    Ext.valued('item'+module, 0)
                                }
                            })
                        }
                    }
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
                                     ,labelWidth     : 20
                                     ,width          : 105
                                     ,style          : 'margin-left:5px;'
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
                                     ,labelWidth     : 20
                                     ,width          : 105
                                     ,style          : 'margin-left:5px;'
                                 })
                             ]
                         }
                     ]
                 }
            ]
        }

        function __salesReturnSummary(){
            let salesReturn = standards.callFunction(  '_createRemoteStore' ,{
                fields:[
                    {	name	: 'id'
                        ,type	: 'number'
                    }
                    ,'idInvoice'
                    ,'idModule'
                    ,'affiliate'
                    ,'date'
                    ,'reference'
                    ,'customer'
                    ,'code'
                    ,'item'
                    ,'class'
                    ,'cost'
                    ,'qty'
                    ,{
                        name: 'amount',
                        type: 'number'
                    }
                ]
                ,url: route + 'getSalesReturn'
            });
            return standards.callFunction( '_gridPanel',{
                id		        : 'salesReturnGrid' + module
                ,module	        : module
                ,style          : 'margin-bottom:10px;'
                ,store	        : salesReturn
                ,noDefaultRow   : true
                ,noPage         : true
                ,tbar           :'empty'
                ,viewConfig: {
                    listeners: {
                        itemdblclick: function(dataview, record, item, index, e) {
                            mainView.openModule( record.data.idModule , record.data, this );
                        }
                    }
                }
                ,columns: [
                    {
                        header : 'Affiliate'
                        ,dataIndex: 'affiliate'
                        ,width      : 100
                    }
                    ,{
                        header : 'Date'
                        ,dataIndex: 'date'
                        ,xtype  : 'datecolumn'
                        ,format : 'm/d/Y'
                        ,width      : 100
                    }
                    ,{
                        header : 'Reference'
                        ,dataIndex: 'reference'
                        ,width      : 105
                    }
                    ,{
                        header : 'Customer'
                        ,dataIndex: 'customer'
                        ,width      : 180
                    }
                    ,{
                        header : 'Code'
                        ,dataIndex: 'code'
                        ,width      : 90
                    }
                    ,{
                        header : 'Item'
                        ,dataIndex: 'item'
                        ,width      : 180
                    }
                    ,{
                        header : 'Classification'
                        ,dataIndex: 'class'
                        ,width      : 100
                    }
                    ,{
                        header : 'Cost'
                        ,dataIndex: 'cost'
                        ,width      : 80
                    }
                    ,{
                        header : 'Qty Returned'
                        ,dataIndex: 'qty'
                        ,width      : 95
                    }
                    ,{
                        header : 'Amount'
                        ,dataIndex: 'amount'
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,sortable       : false
                        ,hasTotal       : true
                        ,width      : 80
                    }
                ]
            })
        }

        function _printPDF(){
            standards.callFunction('_listPDF',{
				grid 		: Ext.getCmp('salesReturnGrid'+module)
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
        function _printExcel(){
            standards.callFunction('_listExcel',{
				grid 		: Ext.getCmp('salesReturnGrid'+module)
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
				idAffiliate = config.idAffiliate
				
				return _mainPanel( config );
			}
		}
    }
}