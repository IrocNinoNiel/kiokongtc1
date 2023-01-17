function Adjustmentsaccsummary(){
    return function(){
        var baseurl, route, module, canDelete, pageTitle, canPrint, balanceModule, ledgerModule, isGae, idAffiliate;
    
        function _mainPanel( config ){
            var viewByStore       = standards.callFunction( '_createLocalStore', {
                    data        : [ 'All' , 'Not Yet Approved' , 'Approved' , 'Cancelled Adjustments' ]
                    ,startAt    : 0
                } )
                ,filterByStore       = standards.callFunction( '_createLocalStore', {
                    data        : [ 'All' , 'Voucher' , 'Customer' , 'Supplier' ]
                    ,startAt    : 0
                } )
                ,referenceStore  = standards.callFunction(  '_createRemoteStore' ,{
                    fields      :[ {	name : 'id', type : 'number' }, 'name' ]
                    ,url        : route + 'getReferences'
                    ,startAt    :  0
                    ,autoLoad   : true
                });

            return standards.callFunction( '_mainPanel', {
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
                ,listeners          : {
                    afterrender     : function(){
                        Ext.getCmp( 'sdate' + module ).setValue( Ext.date().subtract(1, 'month').toDate() )
                    }
                }
                ,formItems          : [
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
                            ,select : function(){
                                var me  = this;
                                Ext.getCmp( 'idReference' + module ).store.proxy.extraParams.idAffiliate = me.getValue();
                                Ext.getCmp( 'idReference' + module ).store.load({
                                    callback    : function(){
                                        Ext.getCmp( 'idReference' + module ).setValue( 0 )
                                    }
                                })
                            }
                        }
                    } )

                    
                    ,standards.callFunction( '_createCombo', {
                        id              : 'viewBy' + module
                        ,fieldLabel     : 'View By'
                        ,store          : viewByStore
                        ,valueField     : 'id'
                        ,displayField   : 'name'
                        ,listeners      : {
                            afterrender : function(){
                                var me  = this;
                                me.store.load( {
                                    callback    : function(){
                                        me.setValue( 0 )
                                    }
                                } )
                            }
                        }
                    } )

                    ,standards.callFunction( '_createCombo', {
                        id              : 'idReference' + module
                        ,fieldLabel     : 'Reference'
                        ,store          : referenceStore
                        ,valueField     : 'id'
                        ,displayField   : 'name'
                        ,listeners      : {
                            afterrender : function(){
                                var me  = this;
                                me.store.load( {
                                    callback    : function(){
                                        me.setValue( 0 )
                                    }
                                } )
                            }
                        }
                    } )

                    
                    ,standards.callFunction( '_createCombo', {
                        id              : 'filterBy' + module
                        ,fieldLabel     : 'Filter By'
                        ,store          : filterByStore
                        ,valueField     : 'id'
                        ,displayField   : 'name'
                        ,listeners      : {
                            afterrender : function(){
                                var me  = this;
                                me.store.load( {
                                    callback    : function(){
                                        me.setValue( 0 )
                                    }
                                } )
                            }
                        }
                    } )

                    ,standards.callFunction( '_createDateRange', {
                        module          : module
                        ,width          : 111
                        ,fromWidth      : 235
                    } )
                ]
                ,moduleGrids        : _gridDisbursement()
            } );
        }

        function _gridDisbursement(){
            var store   = standards.callFunction( '_createRemoteStore', {
                fields      : [
                    'date'
                    ,'affiliateName'
                    ,'reference'
                    ,'name'
                    ,'description'
                    ,{  name    : 'amount'
                        ,type   : 'number'
                    }
                    ,'idInvoice'
                    ,'idModule'
                ]
                ,url        : route + 'getAdjustmentsaccSummary'
            } );
            
            return standards.callFunction( '_gridPanel', {
                id          : 'gridAdjustmentsaccSummary' + module
                ,module     : module
                ,store      : store
                ,tbar       : {
                    content     : ''
                }
                ,noDefaultRow   : true
                ,noPage         : true
                ,plugins        : true
                ,viewConfig: {
                    listeners: {
                        itemdblclick: function(dataview, record, item, index, e) {
                            mainView.openModule( record.data.idModule , record.data, this );
                        }
                    }
                }
                ,columns    : [
                    {  header          : 'Date'
                        ,dataIndex      : 'date'
                        ,xtype          : 'datecolumn'
                        ,format         : 'm/d/Y'
                        ,width          : 100
                        ,columnWidth    : 10
                    }
                    ,{  header          : 'Affiliate'
                        ,dataIndex      : 'affiliateName'
                        ,width          : 100
                        ,columnWidth    : 10
                    }
                    ,{  header          : 'Reference'
                        ,dataIndex      : 'reference'
                        ,width          : 100
                        ,columnWidth    : 10
                    }
                    ,{  header          : 'Name'
                        ,dataIndex      : 'name'
                        ,minWidth       : 150
                        ,flex           : 1
                        ,columnWidth    : 20
                    }
                    ,{  header          : 'Description'
                        ,dataIndex      : 'description'
                        ,width          : 100
                        ,columnWidth    : 15
                    }
                    ,{  header          : 'Amount'
                        ,dataIndex      : 'amount'
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,width          : 100
                        ,hasTotal       : true
                        ,columnWidth    : 15
                    }
                ]
            } )
        }

        function _resetForm() {
            Ext.getCmp( 'sdate' + module ).setValue( Ext.date().subtract(1, 'month').toDate() )
            Ext.getCmp( 'idAffiliate' + module ).fireEvent( 'afterrender' );
            Ext.getCmp( 'idReference' + module ).store.proxy.extraParams.idAffiliate = null;
            Ext.getCmp( 'idReference' + module ).fireEvent( 'afterrender' );
            Ext.getCmp( 'viewBy' + module ).fireEvent( 'afterrender' );
            Ext.getCmp( 'filterBy' + module ).fireEvent( 'afterrender' );
        }

        function _printPDF(){
            var _grid               = Ext.getCmp( 'gridAdjustmentsaccSummary' + module );

            standards.callFunction( '_listPDF', {
                grid                    : _grid
                ,customListPDFHandler   : function(){
                    var par = standards.callFunction( 'getFormDetailsAsObject', {
                        module          : module
                        ,getSubmitValue : true
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
                                window.open( baseurl + 'pdf/accounting/' + par.title + '.pdf');
                            }
                        }
                    } );
                }
            } );
        }

        function _printExcel(){
            var _grid = Ext.getCmp( 'gridAdjustmentsaccSummary' + module );

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
                            window.open( route + "download/" + par.title + '/accounting');
                        }
                    } );
                }
            } );
        }

        return{
			initMethod  : function( config ){
				route		    = config.route;
				baseurl		    = config.baseurl;
				module		    = config.module;
				canPrint	    = config.canPrint;
				canDelete	    = config.canDelete;
				canEdit		    = config.canEdit;
				pageTitle       = config.pageTitle;
				idModule	    = config.idmodule;
				
				return _mainPanel( config );
			}
		}
    }
}