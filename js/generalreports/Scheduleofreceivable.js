/**
 * Developer: Hazel Alegbeleye
 * Module: Receiving Summary
 * Date: March 2, 2020
 * Finished: 
 * Description: 
 * DB Tables: 
 * */ 
function Scheduleofreceivable(){
    return function(){
        var baseurl, route, module, canDelete, pageTitle, canPrint, module, module, isGae, componentCalling;

        function _mainPanel(config){

            var customerStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      : [ {	name : 'id', type : 'number' }, 'name']
                ,url        : route + 'getCustomers'
                ,startAt    :  0
                ,autoLoad   : true
            });

            return standards.callFunction(	'_mainPanel' ,{
                config		    : config
                ,moduleType	    : 'report'
                ,asContainer    : ( idModule == 1 ) ? true : false
                ,tbar           : {
                    noPDFButton        : false
                    ,noExcelButton      : false
                    ,PDFHidden          : false
                    ,formPDFHandler     : _printPDF
                    ,formExcelHandler   : _printExcel
                }
                ,formItems      : [
                    standards2.callFunction('_createAffiliateCombo', {
                        module      : module
                        ,value      : 0
                        ,hasAll     : true
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
                        }
                    })
                    ,standards.callFunction( '_createCombo', {
                        id          : 'idCustomer' + module
                        ,fieldLabel : 'Customer Name'
                        ,hasAll     : 1
                        ,store      : customerStore
                        ,value      : 0
                        ,listeners  : {
                            beforeQuery : function(){
                                let affiliate = Ext.getCmp('idAffiliate' + module ),
                                { data : { items : items } }  = affiliate.getStore();

                                let params = { hasAll : 1 };
                                params['affiliates'] = ( affiliate.getValue() > 0 ) ? affiliate.getValue() : Ext.encode(items.map( item => item.data.id ));

                                this.getStore().proxy.extraParams = params;
                                this.getStore().load({});
                            }
                        }
                    } )
                    ,standards.callFunction( '_createDateRange', {
                        id              : 'date' + module
                        ,module         : module
                        ,fieldLabel     : 'Date'
                        ,fromWidth      : 230
                        ,width          : 115
                        ,date           : Ext.date().subtract(1, 'month').toDate()
                    } )
                ]
                ,moduleGrids    : [ grdItems() ]
                ,listeners  : {
                    afterrender: function(){
                        customerStore.proxy.extraParams.hasAll = 1;
                        _setDefaultValue( customerStore, 'idCustomer' );
                    }
                }
            })

            function _setDefaultValue( store, id ) {
                store.load({
                    callback: function(){
                        Ext.getCmp( id + module ).setValue(0);
                    }
                });
            }

            function grdItems(){
                var store = standards.callFunction(  '_createRemoteStore' ,{
                    fields:[
                        'referenceNum'
                        ,'transactionDate'
                        ,'dueDate'
                        ,'customerName'
                        ,'description'
                        ,'affiliateName'
                        ,{ name: 'amount', type: 'number' }
                        ,{ name: 'balance', type: 'number' } 
                    ], 
                    url: route + "getReceivable"
                });

                return standards.callFunction( '_gridPanel',{
                    id          : 'gridReceivable' + module
                    ,module     : module
                    ,store      : store
                    ,tbar       : {
                        // canPrint                : canPrint
                        // ,customListPDFHandler   : _printPDF
                        // ,customListExcelHandler : _printExcel
                    }
                    ,noDefaultRow : true
                    ,noPage     : true
                    ,plugins    : true
                    ,style      :'margin-top:10px;'
                    ,columns    : [
                        {	header      : 'Affiliate'
                            ,dataIndex  : 'affiliateName'
                            ,minWidth   : 60
                            ,flex       : 1
                        },
                        {	header      : 'Reference'
                            ,dataIndex  : 'referenceNum'
                            ,minWidth   : 60
                            ,flex       : 1
                        }
                        ,{	header      : 'Transaction Date'
                            ,dataIndex  : 'transactionDate'
                            ,xtype      : 'datecolumn'
                            ,format     : Ext.getConstant('DATE_FORMAT')
                            ,minWidth   : 60
                            ,flex       : 1
                        }
                        ,{	header      : 'Due Date'
                            ,dataIndex  : 'dueDate'
                            ,minWidth   : 60
                            ,flex       : 1
                            ,xtype      : 'datecolumn'
                            ,format     : Ext.getConstant('DATE_FORMAT')
                        }
                        ,{	header      : 'Customer Name'
                            ,dataIndex  : 'customerName'
                            ,minWidth   : 60
                            ,flex       : 1
                        }
                        ,{	header      : 'Description'
                            ,dataIndex  : 'description'
                            ,minWidth   : 60
                            ,flex       : 1
                        }
                        ,{	header      : 'Amount'
                            ,dataIndex  : 'amount'
                            ,minWidth   : 60
                            ,flex       : 1
                            ,xtype      : 'numbercolumn'
                            ,format     : '0,000.00'
                            ,hasTotal   : true
                        }
                        ,{	header      : 'Balance'
                            ,dataIndex  : 'balance'
                            ,minWidth   : 60
                            ,flex       : 1
                            ,xtype      : 'numbercolumn'
                            ,format     : '0,000.00'
                            ,hasTotal   : true
                        }
                    ]
                    
                });
            }
        }

        function _printPDF(){
            var _grid               = Ext.getCmp( 'gridReceivable' + module );

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
            var _grid = Ext.getCmp( 'gridReceivable' + module );

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
                idAffiliate = config.idAffiliate;

                console.log( idAffiliate );

				return _mainPanel( config );
			}
		}
    }
}