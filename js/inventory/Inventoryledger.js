function Inventoryledger() {
   return function(){
      var baseurl, route, module, canDelete, pageTitle, canPrint, monitoringModule, module, isGae, idAffiliate;

      function _mainPanel(config){
         //MAIN PANEL
         return standards.callFunction(	'_mainPanel' , {
            config		         : config
            ,moduleType	         : 'report'
            ,beforeViewHandler   : _checkLedgerParams
            ,afterResetHandler   : _resetForm
            ,tbar                : {
               noFormButton      : true
               ,noListButton     : true
               ,noPDFButton      : false
               ,PDFHidden        : false
               ,formPDFHandler   : _printPDF
               ,formExcelHandler : _printExcel
            }
            ,formItems           : _filters()
            ,moduleGrids         : _moduleGrid()
         } )
      }

      function _checkLedgerParams() {
         item = Ext.getCmp( 'idItem' + module );
         affl = Ext.getCmp( 'idAffiliate' + module );
         if( !item.value ) {
            standards.callFunction( '_createMessageBox', {
               msg : 'Item field is required!'
            } )
            return false
         } else if ( !affl.value ) {
            standards.callFunction( '_createMessageBox', {
               msg : 'Affiliate field is required!'
            } )
            return false
         }
      }

      function _filters() {
         // STORE
         var itemStore = standards.callFunction(  '_createRemoteStore' ,{
            fields      :[ {	name : 'id', type : 'number' }, 'name', 'className', 'unitName', 'itemPrice', 'reorderLevel']
            ,url        : route + 'getItems'
            ,startAt    :  0
            ,autoLoad   : true
         })

         return [
            //AFFIALTE COMBOBOX
            standards2.callFunction( '_createAffiliateCombo', {
               module     : module
               ,listeners  : {
                  select     : function(){
                        var me  = this;
                        Ext.getCmp( 'idItem' + module ).store.proxy.extraParams.idAffiliate = me.getValue();
                        Ext.getCmp( 'idItem' + module ).store.load( {} )
                  }
               }
            } )

            //ITEM COMBOBOX
            ,standards.callFunction( '_createCombo', {
               id              : 'idItem' + module
               ,fieldLabel     : 'Item Name'
               ,allowBlank     : false
               ,store          : itemStore
               ,displayField   : 'name'
               ,valueField     : 'id'
               ,listeners      : {
                  select      : function( me , record ) {
                        data = record[0].data;
                        Ext.getCmp( 'itemClass' + module ).setValue(data.className);
                        Ext.getCmp( 'unit' + module ).setValue(data.unitName);
                        Ext.getCmp( 'price' + module ).setValue(data.itemPrice);
                        Ext.getCmp( 'reorderPoint' + module ).setValue(data.reorderLevel);
                  }
                  ,change     : function(){
                        if( !this.value ) {
                           Ext.getCmp( 'itemClass' + module ).setValue(null);
                           Ext.getCmp( 'unit' + module ).setValue(null);
                           Ext.getCmp( 'price' + module ).setValue(null);
                           Ext.getCmp( 'reorderPoint' + module ).setValue(null);
                        }
                  }
               }
            } )

            //CLASSIFICATION TEXTFIELD
            ,standards.callFunction( '_createTextField', {
               id			: 'itemClass' + module
               ,module		: module
               ,fieldLabel	: 'Classification'
               ,allowBlank	: true
               ,readOnly	: true
            } )

            //UNIT OF MEASUREMENT TEXTFIELD
            ,standards.callFunction( '_createTextField', {
               id			: 'unit' + module
               ,module		: module
               ,fieldLabel	: 'Unit of Measurement'
               ,allowBlank	: true
               ,readOnly	: true
            } )

            //PRICE TEXTFIELD
            ,standards.callFunction( '_createTextField', {
               id			: 'price' + module
               ,module		: module
               ,fieldLabel	: 'Price'
               ,isNumber   : true
               ,isDecimal  : true
               ,allowBlank	: true
               ,readOnly	: true
            } )

            //REORDER POINT TEXTFIELD
            ,standards.callFunction( '_createTextField', {
               id			: 'reorderPoint' + module
               ,module		: module
               ,fieldLabel	: 'Reorder Point'
               ,isNumber   : true
               ,isDecimal  : false
               ,allowBlank	: true
               ,readOnly	: true
            } )

            // DATERANGE COMBOBOX
            ,{
               xtype : 'container'
               ,layout: 'column'
               ,width  : 470
               ,items: [
                  standards.callFunction( '_createDateField', {
                        id              : 'sdate' + module
                        ,fieldLabel     : 'Date'
                        ,allowBlank     : true
                        ,width          : 230
                        ,value          : Ext.date().subtract(1, 'month').toDate()
                        ,listeners      : {
                           change: function() {
                              var from = this;
                              var to = Ext.getCmp( 'edate' + module );
                              if (from.value > to.value) {
                                    Ext.getCmp( 'edate' + module ).setValue( from.value );
                              }
                           }
                        }
                  })
                  ,standards.callFunction( '_createDateField', {
                        id              : 'edate' + module
                        ,fieldLabel     : 'to'
                        ,allowBlank     : true
                        ,style          : { margin: '0 0 8px 5px' }
                        ,labelWidth     : 15
                        ,width          : 116
                        ,listeners      : {
                           change: function() {
                              var to = this;
                              var from = Ext.getCmp( 'sdate' + module );
                              if (from.value > to.value) {
                                    Ext.getCmp( 'sdate' + module ).setValue( to.value );
                              }
                           }
                        }
                  })
               ]
            }
         ]
      }

      function _moduleGrid() {
         var store   = standards.callFunction( '_createRemoteStore', {
               url     : route + 'getInventoryLedger'
               ,fields : [
                  'date'
                  ,'code'
                  ,'name'
                  ,{ name: 'price'     ,type: 'number' }
                  ,{ name: 'cost'      ,type: 'number' }
                  ,{ name: 'received'  ,type: 'number' }
                  ,{ name: 'released'  ,type: 'number' }
                  ,{ name: 'balance'   ,type: 'number' }
                  ,'idInvoice'
                  ,'idModule'
               ]
         } );

         return standards.callFunction( '_gridPanel', {
            id              : 'gridReport' + module
            ,module         : module
            ,store          : store
            ,noDefaultRow   : true
            ,tbar           : {
               content     : ''
            }
            ,features       : {
               ftype   : 'summary'
            }
            ,viewConfig: {
               listeners: {
                     itemdblclick: function(dataview, record, item, index, e) {
                        if ( record.data.idModule != 0 ) {
                           mainView.openModule( record.data.idModule , record.data, this );
                        }
                     }
               }
            }  
            ,noPage         : true
            ,plugins        : true
            ,columns        : [
               {  header          : 'Date'
                  ,dataIndex      : 'date'
                  ,width          : 100
                  ,sortable       : false
                  ,xtype          : 'datecolumn'
                  ,format         : 'm/d/Y'
               }
               ,{  header         : 'Reference'
                  ,dataIndex      : 'code'
                  ,width          : 107
                  ,sortable       : false
               }
               ,{  header         : 'Supplier/Customer'
                  ,dataIndex      : 'name'
                  ,width          : 300
                  ,sortable       : false
                  ,flex           : 1
               }
               ,{  header         : 'Price'
                  ,dataIndex      : 'price'
                  ,width          : 110
                  ,xtype          : 'numbercolumn'
                  ,format         : '0,000.00'
                  ,sortable       : false
               }
               ,{  header         : 'Cost'
                  ,dataIndex      : 'cost'
                  ,width          : 110
                  ,xtype          : 'numbercolumn'
                  ,format         : '0,000.00'
                  ,sortable       : false
               }
               ,{  header         : 'Received'
                  ,dataIndex      : 'received'
                  ,width          : 110
                  ,xtype          : 'numbercolumn'
                  ,format         : '0,000'
                  ,sortable       : false
               }
               ,{  header         : 'Released'
                  ,dataIndex      : 'released'
                  ,width          : 110
                  ,xtype          : 'numbercolumn'
                  ,format         : '0,000'
                  ,sortable       : false
               }
               ,{  header         : 'Balance'
                  ,dataIndex      : 'balance'
                  ,width          : 110
                  ,xtype          : 'numbercolumn'
                  ,format         : '0,000'
                  ,sortable       : false
               }
            ]
         } );
      }

      function _printPDF(){
			var par  = standards.callFunction('getFormDetailsAsObject',{ module : module })
			,poItems = Ext.getCmp('gridReport'+module).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0);

			Ext.Ajax.request({
            url			: route + 'generatePDF' //Ext.getConstant( 'STANDARD_ROUTE2' ) + 'generateFormPDF' //
            ,method		:'post'
            ,params		: {
               moduleID    	: 7
               ,title  		   : pageTitle
               ,limit      	: 50
               ,start      	: 0
               ,printPDF   	: 1
               ,form			   : Ext.encode( par )
               ,poItems		   : Ext.encode( poItems )
               ,idAffiliate	: idAffiliate
            }
            ,success	: function(response, action){
               if( isGae == 1 ){
                  window.open(route+'viewPDF/Inventory Ledger','_blank');
               }else{
                  window.open('pdf/inventory/Inventory Ledger.pdf');
               }
            }
         });			
		}

      function _printExcel(){
         var _grid = Ext.getCmp( 'gridReport' + module );
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
                     window.open( route + "download/" + par.title + '/inventory');
                  }
               } );
            }
         } );
      }

      function _resetForm(){
         Ext.getCmp( 'idItem' + module ).store.proxy.extraParams.idAffiliate = null;
      }

      return {
         initMethod:function( config ){
               route		= config.route;
               baseurl		= config.baseurl;
               module		= config.module;
               canPrint	= config.canPrint;
               canDelete	= config.canDelete;
               canEdit		= config.canEdit;
               pageTitle   = config.pageTitle;
               idModule	= config.idmodule
               isGae		= config.isGae;
               idAffiliate = config.idAffiliate
               
               return _mainPanel( config );
         }
      }
   }
}
