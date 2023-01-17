function Agingofreceivables() {
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
            let customerStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name']
                ,url        : route + 'getCustomers'
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
                            Ext.getCmp( 'idCustomer' + module ).store.proxy.extraParams.idAffiliate = me.getValue();

                            Ext.getCmp( 'idCustomer' + module ).store.load( {
                                callback   : function() {
                                    Ext.getCmp( 'idCustomer' + module ).setValue( 0 );
                                }
                            } )
                        }
                    }
                } )

                // CUSTOMER COMBOBOX
                ,standards.callFunction( '_createCombo', {
                    id              : 'idCustomer' + module
                    ,fieldLabel     : 'Customer'
                    ,allowBlank     : true
                    ,store          : customerStore
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

                // AS OF DATE COMBOBOX
                ,standards.callFunction( '_createDateField', {
                    id              : 'dateto' + module
                    ,fieldLabel     : 'View records as of'
                    ,allowBlank     : true
                    ,maxValue       : new Date()
                })

                // CHECKBOX
                ,standards.callFunction( '_createCheckField', {
                    id          : 'hideZero' + module
                    ,boxLabel   : 'Hide customers with no balances'
                    ,checked    : 'true'
                })
            ]
        }

        function _moduleGrid() {
            let store   = standards.callFunction( '_createRemoteStore', {
                url     : route + 'getAgingofReceivables'
                ,fields : [
                    'affiliateName'
                    ,'customerName'
                    ,{ name: 'current_bal'  ,type: 'number' }
                    ,{ name: 'days'         ,type: 'number' }
                    ,{ name: 'dayss'        ,type: 'number' }
                    ,{ name: 'above'        ,type: 'number' }
                    ,{ name: 'total'        ,type: 'number' }
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
                    {   header          : 'Affiliate'
                        ,dataIndex      : 'affiliateName'
                        ,width          : 171   
                    }
                    ,{  header          : 'Customer'
                        ,dataIndex      : 'customerName'
                        ,width          : 171
                    }
                    ,{  header          : 'Current'
                        ,dataIndex      : 'current_bal'
                        ,width          : 155
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,sortable       : false
                        ,hasTotal       : true
                    }
                    ,{  header          : '30-59 Days'
                        ,dataIndex      : 'days'
                        ,width          : 155
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,sortable       : false
                        ,hasTotal       : true
                    }
                    ,{  header          : '60-89 Days'
                        ,dataIndex      : 'dayss'
                        ,width          : 155
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,sortable       : false
                        ,hasTotal       : true
                    }
                    ,{  header          : '90 Days and Above'
                        ,dataIndex      : 'above'
                        ,width          : 155
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,sortable       : false
                        ,hasTotal       : true
                    }
                    ,{  header          : 'Total'
                        ,dataIndex      : 'total'
                        ,width          : 155
                        ,minWidth       : 130
                        ,flex           : 1
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

                    me = Ext.getCmp( 'idAffiliate' + module ).setValue( 0 );

                    Ext.getCmp( 'idCustomer' + module ).store.proxy.extraParams.idAffiliate = me.getValue();
                    Ext.getCmp( 'idCustomer' + module ).store.load( {
                        callback    : function(){
                            Ext.getCmp( 'idCustomer' + module ).setValue( 0 );
                        }
                    } );
                }
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
                                window.open( baseurl + 'pdf/generalreports/' + par.title + '.pdf');
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