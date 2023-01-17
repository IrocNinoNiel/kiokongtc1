/**
 * Developer    : Makmak
 * Module       : Adjustment Summary
 * Date         : Feb. 18, 2020
 * Finished     : 
 * Description  : 
 * DB Tables    : 
 * */ 
function Adjustmentsummary(){
    return function(){
        var baseurl, route, module, canDelete, pageTitle, canPrint, monitoringModule, module, isGae, idAffiliate;
        
        function _mainPanel(config){
            // STORE
            var referenceStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name']
                ,url        : route + 'getReferences'
                ,startAt    :  0
                ,autoLoad   : true
            })

            var classificationStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name']
                ,url        : route + 'getItemClassifications'
                ,startAt    :  0
                ,autoLoad   : true
            })

            var itemStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name']
                ,url        : route + 'getItems'
                ,startAt    :  0
                ,autoLoad   : true
            })

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
                ,formItems      : [
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
                                Ext.getCmp( 'idReference' + module ).store.proxy.extraParams.idAffiliate = me.getValue();
                                Ext.getCmp( 'idItem' + module ).store.load( {
                                    callback   : function() {
                                        Ext.getCmp( 'idItem' + module ).setValue( 0 );
                                    }
                                } )
                                Ext.getCmp( 'idReference' + module ).store.load( {
                                    callback   : function( ){
                                        Ext.getCmp( 'idReference' + module ).setValue( 0 );
                                    }
                                } )

                            }
                        }
                    } )

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

                    ,standards.callFunction( '_createCombo', {
                        id              : 'idItemClass' + module
                        ,hasAll         : 1
                        ,fieldLabel     : 'Classification'
                        ,allowBlank     : true
                        ,store          : classificationStore
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
                ,moduleGrids    : _moduleGrid()
            } )
        }
        
        function _moduleGrid(){
            var store   = standards.callFunction( '_createRemoteStore', {
                url     : route + 'getAdjustmentsummary'
                ,fields : [
                    'idInvoice'
                    ,'idModule'
                    ,'affiliateName'
                    ,'date'
                    ,'code'
                    ,'itemName'
                    ,'unitCode'
                    ,'itemClass'
                    ,{  name: 'qtyBal'       
                        ,type: 'number' 
                    }
                    ,{  name: 'qtyActual'    
                        ,type: 'number' 
                    }
                    ,{  name: 'cost'         
                        ,type: 'number' 
                    }
                    ,{  name: 'short'        
                        ,type: 'number' 
                    }
                    ,{  name: 'over'         
                        ,type: 'number' 
                    }
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
                        ,xtype          : 'datecolumn'
                        ,format         : 'm/d/Y h:i A'
                        ,width          : 150
                    }
                    ,{  header          : 'Reference'
                        ,dataIndex      : 'code'
                        ,width          : 70
                    }
                    ,{  header          : 'Item'
                        ,dataIndex      : 'itemName'
                        ,width          : 150
                        ,flex           : 1
                        ,minWidth       : 150
                    }
                    ,{  header          : 'Unit'
                        ,dataIndex      : 'unitCode'
                        ,width          : 70
                    }
                    ,{  header          : 'Balance Qty'
                        ,dataIndex      : 'qtyBal'
                        ,width          : 100
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000'
                        ,sortable       : false
                    }
                    ,{  header          : 'Actual Qty'
                        ,dataIndex      : 'qtyActual'
                        ,width          : 100
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000'
                        ,sortable       : false
                    }
                    ,{  header          : 'Cost'
                        ,dataIndex      : 'cost'
                        ,width          : 100
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,sortable       : false
                    }
                    ,{  header          : 'Variance'
                        ,width          : 200
                        ,columns        : [
                            {  
                                header          : 'Short'
                                ,dataIndex      : 'short'
                                ,width          : 100
                                ,xtype          : 'numbercolumn'
                                ,format         : '0,000'
                                ,sortable       : false
                            }
                            ,{  header          : 'Over'
                                ,dataIndex      : 'over'
                                ,width          : 100
                                ,xtype          : 'numbercolumn'
                                ,format         : '0,000'
                                ,sortable       : false
                            }
                        ]
                    }
                ]
            } );
        }

        function _printPDF(){
            var _grid               = Ext.getCmp( 'gridReport' + module );

            standards.callFunction( '_listPDF', {
                grid                    : _grid
                ,customListPDFHandler   : function(){
                    var par = standards.callFunction( 'getFormDetailsAsObject', {
                        module          : module
                        ,getSubmitValue : true
                        ,idAffiliate	: idAffiliate
                    } );
                    par.title               = pageTitle;
                    console.log(par);
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
            Ext.getCmp( 'idItem' + module ).store.proxy.extraParams.idItemClass = null
            Ext.getCmp( 'idItem' + module ).store.load( {
                callback    : function(){
                    Ext.getCmp( 'idItem' + module ).setValue( 0 );
                }
            } )
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