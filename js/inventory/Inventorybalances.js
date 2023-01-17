function Inventorybalances() {
   return function(){
      var baseurl, route, module, canDelete, pageTitle, canPrint, monitoringModule, module, isGae, idAffiliate ,dataHolder;
      
      function _mainPanel(config){
         //MAIN PANEL
         return standards.callFunction(	'_mainPanel' ,{
            config		: config
            ,moduleType	: 'report'
            ,afterResetHandler : _resetForm
            ,tbar       : {
            noFormButton        : true
               ,noListButton       : true
               ,noPDFButton        : false
               ,PDFHidden          : false
               ,formPDFHandler     : _printPDF
               ,formExcelHandler   : _printExcel
            }
            ,formItems      : _filters()
            ,moduleGrids    : __moduleGrid()
         } )
      }
      
      function _filters() {
         let itemClassStore = standards.callFunction(  '_createRemoteStore' ,{
            fields      :[ {	name : 'id', type : 'number' }, 'name']
            ,url        : route + 'getItemClass'
            ,startAt    :  0
            ,autoLoad   : true
         })

         let itemStore = standards.callFunction(  '_createRemoteStore' ,{
            fields      :[ {	name : 'id', type : 'number' }, 'name']
            ,url        : route + 'getItems'
            ,startAt    :  0
            ,autoLoad   : true
         })

         return [
            //AFFIALTE COMBOBOX
            standards2.callFunction( '_createAffiliateCombo', {
               hasAll      : 1
               ,module     : module
               ,allowBlank : true
               ,listeners  : {
                  afterrender : function(){
                     var me  = this;
                     me.store.load( {
                        callback    : function(){
                              me.setValue( 0 );
                        }
                     } )
                  }
                  ,select     : function(){
                     var me  = this;
                     Ext.getCmp( 'idItem' + module ).store.proxy.extraParams.idAffiliate = me.getValue();

                     Ext.getCmp( 'idItem' + module ).store.load( {
                        callback   : function() {
                              Ext.getCmp( 'idItem' + module ).setValue( 0 );
                        }
                     } )
                  }
               }
            } )

            //ITEM CLASSIFICATION
            ,standards.callFunction( '_createCombo', {
               id              : 'idItemClass' + module
               ,hasAll         : 1
               ,fieldLabel     : 'Classification'
               ,allowBlank     : true
               ,store          : itemClassStore
               ,displayField   : 'name'
               ,valueField     : 'id'
               ,value          : 0
               ,listeners      : {
                   afterrender : function(){
                       var me  = this;
                       me.store.load( {
                           callback    : function(){
                               me.setValue( 0 );
                           }
                       } )
                   }
                   ,select     : function() {
                       var me  = this;
                       Ext.getCmp( 'idItem' + module ).store.proxy.extraParams.idItemClass = me.getValue();
                       Ext.getCmp( 'idItem' + module ).store.load( {
                           callback   : function( ){
                               Ext.getCmp( 'idItem' + module ).setValue( 0 );
                           }
                       } )
                   }
               }
            } )

            // ITEM COMBOBOX
            ,standards.callFunction( '_createCombo', {
               id              : 'idItem' + module
               ,fieldLabel     : 'Item Name'
               ,allowBlank     : true
               ,store          : itemStore
               ,displayField   : 'name'
               ,valueField     : 'id'
               ,value          : 0
               ,listeners      : {
                  afterrender : function(){
                     var me  = this;
                     me.store.load( {
                        callback    : function(){
                           me.setValue( 0 );
                        }
                     } )
                  }
               }
            } )

            // AS OF
            ,standards.callFunction( '_createDateField', {
               id              : 'datefrom' + module
               ,fieldLabel     : 'View records as of'
               ,allowBlank     : true
               ,maxValue       : new Date()
            })
         ]
      }

      function _resetForm(){
         Ext.getCmp( 'idAffiliate' + module ).fireEvent( 'afterrender' );
         Ext.getCmp( 'idItemClass' + module ).fireEvent( 'afterrender' );
         Ext.getCmp( 'idItem' + module ).store.proxy.extraParams.idAffiliate = null
         Ext.getCmp( 'idItem' + module ).fireEvent( 'afterrender' );
      }

      function __moduleGrid() {
         let store   = standards.callFunction( '_createRemoteStore', {
             url     : route + 'getInventoryBalances'
             ,fields : [
                 'affiliateName'
                 ,'itemName'
                 ,'className'
                 ,'unitCode'
                 ,{ name: 'cost'          ,type: 'number' }
                 ,{ name: 'reorderLevel'  ,type: 'number' }
                 ,{ name: 'balance'       ,type: 'number' }
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
            ,noPage         : true
            ,plugins        : true
            ,columns        : [
               {   header         : 'Affiliate'
                  ,dataIndex      : 'affiliateName'
                  ,width          : 160
               }
               ,{  header        : 'Item Name'
                  ,dataIndex     : 'itemName'
                  ,width         : 160
                  ,flex          : 1
               }
               ,{  header         : 'Classification'
                  ,dataIndex      : 'className'
                  ,width          : 160
               }
               ,{  header          : 'Unit'
                  ,dataIndex      : 'unitCode'
                  ,width          : 150
               }
               ,{  header         : 'Cost'
                  ,dataIndex      : 'cost'
                  ,width          : 160
                  ,xtype          : 'numbercolumn'
                  ,format         : '0,000.00'
                  ,sortable       : false
               }
               ,{  header         : 'Reorder Point'
                  ,dataIndex      : 'reorderLevel'
                  ,width          : 160
                  ,xtype          : 'numbercolumn'
                  ,format         : '0,000'
                  ,sortable       : false
               }
               ,{  header         : 'Balance'
                  ,dataIndex      : 'balance'
                  ,width          : 160
                  ,xtype          : 'numbercolumn'
                  ,format         : '0,000'
                  ,sortable       : false
               }
            ]
         });
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
                  window.open(route+'viewPDF/Inventory Balances','_blank');
               }else{
                  window.open('pdf/inventory/Inventory Balances.pdf');
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

      return{
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