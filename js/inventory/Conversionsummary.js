/**
 * Developer    : Makmak
 * Module       : Conversion Summary
 * Date         : Feb. 19, 2020
 * Finished     : 
 * Description  : 
 * DB Tables    : 
 * */

function Conversionsummary() {
    return function(){
        var baseurl, route, module, canDelete, pageTitle, canPrint, monitoringModule, module, isGae, idAffiliate;
        
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
                ,moduleGrids    : _moduleGrid()
            } )
        } 

        function _filters() {
            // STORE
            let referenceStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name']
                ,url        : route + 'getReferences'
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
                            Ext.getCmp( 'idReference' + module ).store.proxy.extraParams.idAffiliate = me.getValue();
                            Ext.getCmp( 'idItem' + module ).store.proxy.extraParams.idAffiliate = me.getValue();

                            Ext.getCmp( 'idReference' + module ).store.load( {
                                callback   : function() {
                                    Ext.getCmp( 'idReference' + module ).setValue( 0 );
                                }
                            } )
                            
                            Ext.getCmp( 'idItem' + module ).store.load( {
                                callback   : function() {
                                    Ext.getCmp( 'idItem' + module ).setValue( 0 );
                                }
                            } )
                        }
                    }
                } )

                //REFERENCE COMBOBOX
                ,standards.callFunction( '_createCombo', {
                    id              : 'idReference' + module
                    ,hasAll         : 1
                    ,fieldLabel     : 'Reference'
                    ,valueField     : 'id'
                    ,displayField   : 'name'
                    ,store          : referenceStore
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

                // DATE AND TIME FIELD FOR "FROM"
                ,{
                    xtype : 'container'
                    ,layout: 'column'
                    ,width  : 550
                    ,items: [
                        {
                            xtype : 'container'
                            ,columnWidth : .4
                            ,items : [
                                standards.callFunction( '_createDateField', {
                                    id              : 'datefrom' + module
                                    ,fieldLabel     : 'Date and Time From'
                                    ,allowBlank     : true
                                    ,width          : 240
                                    ,value          : Ext.date().subtract(1, 'month').toDate()
                                    ,maxValue       : new Date()
                                    ,listeners      : {
                                        change: function() {
                                            var from = this;
                                            var to = Ext.getCmp( 'dateto' + module );
                                            if (from.value > to.value) {
                                                Ext.getCmp( 'dateto' + module ).setValue( from.value );
                                            }
                                        }
                                    }
                                })
                            ]
                        },
                        {
                            xtype : 'container'
                            ,columnWidth : .6
                            ,items : [
                                standards.callFunction( '_createTimeField', {
                                    id              : 'timefrom' + module
                                    ,fieldLabel     : 'to'
                                    ,allowBlank     : true
                                    ,labelWidth     : 20
                                    ,width          : 131
                                    ,value          : '12:00 AM'
                                })
                            ]
                        }
                    ]
                }

                // DATE AND TIME FIELD FOR "TO"
                ,{
                    xtype : 'container'
                    ,layout: 'column'
                    ,width: 550
                    ,items: [
                        {
                            xtype : 'container'
                            ,columnWidth : .4
                            ,items : [
                                standards.callFunction( '_createDateField', {
                                    id              : 'dateto' + module
                                    ,fieldLabel     : 'Date and Time To'
                                    ,allowBlank     : true
                                    ,maxValue       : new Date()
                                    ,width          : 240
                                    ,listeners      : {
                                        change: function() {
                                            var to = this;
                                            var from = Ext.getCmp( 'datefrom' + module );
                                            if (from.value > to.value) {
                                                Ext.getCmp( 'datefrom' + module ).setValue( to.value );
                                            }
                                        }
                                    }
                                })
                            ]
                        },
                        {
                            xtype : 'container'
                            ,columnWidth : .6
                            ,items : [
                                standards.callFunction( '_createTimeField', {
                                    id              : 'timeto' + module
                                    ,fieldLabel     : 'to'
                                    ,allowBlank     : true
                                    ,labelWidth     : 20
                                    ,width          : 131
                                    ,value          : '11:45 PM'
                            })
                            ]
                        }
                    ]
                }
            ]
        }

        function _moduleGrid() {
            let store   = standards.callFunction( '_createRemoteStore', {
                url     : route + 'getInvConversions'
                ,fields : [
                    'affiliateName'
                    ,'date'
                    ,'code'
                    ,'barcode'
                    ,'name'
                    ,'itemName'
                    ,'unitCode'
                    ,'idInvoice'
                    ,'idModule'
                    ,{ name: 'cost'     ,type: 'number' }
                    ,{ name: 'received'  ,type: 'number' }
                    ,{ name: 'released'  ,type: 'number' }
                    ,{ name: 'amount'   ,type: 'number' }
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
                            mainView.openModule( record.data.idModule , record.data, this );
                        }
                    }
                }
                ,noPage         : true
                ,plugins        : true
                ,columns        : [
                    {   header          : 'Affiliate'
                        ,dataIndex      : 'affiliateName'
                        ,width          : 150
                    }
                    ,{  header          : 'Date'
                        ,dataIndex      : 'date'
                        ,width          : 150
                        ,xtype          : 'datecolumn'
                        ,format         : 'm/d/Y h:i A'
                    }
                    ,{  header          : 'Reference'
                        ,dataIndex      : 'code'
                        ,width          : 80
                    }
                    ,{  header          : 'Code'
                        ,dataIndex      : 'barcode'
                        ,width          : 100
                    }
                    ,{  header          : 'Item'
                        ,dataIndex      : 'itemName'
                        ,width          : 168
                        ,minWidth       : 168
                        ,flex           : 1
                    }
                    ,{  header          : 'Unit'
                        ,dataIndex      : 'unitCode'
                        ,width          : 47
                    }
                    ,{  header          : 'Cost'
                        ,dataIndex      : 'cost'
                        ,width          : 105
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,sortable       : false
                        ,hasTotal       : true
                    }
                    ,{  header          : 'Received'
                        ,dataIndex      : 'received'
                        ,width          : 105
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000'
                        ,sortable       : false
                        ,hasTotal       : true
                    }
                    ,{  header          : 'Released'
                        ,dataIndex      : 'released'
                        ,width          : 105
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000'
                        ,sortable       : false
                        ,hasTotal       : true
                    }
                    ,{  header          : 'Amount'
                        ,dataIndex      : 'amount'
                        ,width          : 100
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,sortable       : false
                        ,hasTotal       : true
                    }
                ]
            });
        }

        function _resetForm(){
            Ext.getCmp( 'idAffiliate' + module ).store.load( {
                callback    : function(){
                    Ext.getCmp( 'idAffiliate' + module ).setValue( 0 );
                }
            } );
            Ext.getCmp( 'idReference' + module ).store.proxy.extraParams.idAffiliate = null
            Ext.getCmp( 'idReference' + module ).store.load( {
                callback    : function(){
                    Ext.getCmp( 'idReference' + module ).setValue( 0 );
                }
            } )
            Ext.getCmp( 'idItem' + module ).store.proxy.extraParams.idAffiliate = null
            Ext.getCmp( 'idItem' + module ).store.load( {
                callback    : function(){
                    Ext.getCmp( 'idItem' + module ).setValue( 0 );
                }
            } )
        }

        function _printPDF(){
			var par  = standards.callFunction('getFormDetailsAsObject',{ module : module })
			,poItems = Ext.getCmp('gridReport'+module).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0);

			Ext.Ajax.request({
            url			: route + 'generatePDF' //Ext.getConstant( 'STANDARD_ROUTE2' ) + 'generateFormPDF' //
            ,method		:'post'
            ,params		: {
               moduleID    	: 7
               ,title  		: pageTitle
               ,limit      	: 50
               ,start      	: 0
               ,printPDF   	: 1
               ,form	    : Ext.encode( par )
               ,poItems     : Ext.encode( poItems )
               ,idAffiliate	: idAffiliate
            }
            ,success	: function(response, action){
                if( isGae == 1 ){
                    window.open(route+'viewPDF/Conversion Summary','_blank');
                }else{
                    window.open('pdf/inventory/Conversion Summary.pdf');
                }
            }
         });			
        }
        
        // function _printPDF(){
        //     var _grid               = Ext.getCmp( 'gridReport' + module );

        //     standards.callFunction( '_listPDF', {
        //         grid                    : _grid
        //         ,customListPDFHandler   : function(){
        //             var par = standards.callFunction( 'getFormDetailsAsObject', {
        //                 module          : module
        //                 ,getSubmitValue : true
        //             } );
        //             par.title               = pageTitle;
        //             Ext.Ajax.request( {
        //                 url         : route + 'printPDF'
        //                 ,params     : par
        //                 ,success    : function(res){
        //                     if( isGae ){
        //                         window.open( route + 'viewPDF/' + par.title , '_blank' );
        //                     }
        //                     else{
        //                         window.open( baseurl + 'pdf/inventory/' + par.title + '.pdf');
        //                     }
        //                 }
        //             } );
        //         }
        //     } );
        // }

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