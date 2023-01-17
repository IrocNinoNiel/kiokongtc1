/**
 * Developer    : Jayson Dagulo
 * Module       : Collection Summary
 * Date         : Jan 30, 2019
 * Finished     : 
 * Description  : This module allows authorized user to manually closes the journal entries.
 * DB Tables    : 
 * */ 
function Disbursementsummary(){
    return function(){
        var baseurl, route, module, canDelete, pageTitle, canPrint, balanceModule, ledgerModule, isGae, idAffiliate;
    
        function _mainPanel( config ){
            var viewByStore       = standards.callFunction( '_createLocalStore', {
                    data        : [ 'Cash', 'Charge' ]
                    ,startAt    : 1
                } )
                ,supplierStore  = standards.callFunction(  '_createRemoteStore' ,{
                    fields      :[ {	name : 'id', type : 'number' }, 'name' ]
                    ,url        : route + 'getSuppliers'
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
                                Ext.getCmp( 'idSupplier' + module ).store.proxy.extraParams.idAffiliate = me.getValue();
                                Ext.getCmp( 'idSupplier' + module ).store.load({
                                    callback    : function(){
                                        Ext.getCmp( 'idSupplier' + module ).setValue( 0 )
                                    }
                                })
                            }
                        }
                    } )
                    ,standards.callFunction( '_createCombo', {
                        id              : 'idSupplier' + module
                        ,fieldLabel     : 'Supplier'
                        ,store          : supplierStore
                        ,valueField     : 'id'
                        ,displayField   : 'name'
                        ,listeners      : {
                            afterrender : function( me ){
                                me.store.load( {
                                    callback    : function(){
                                        me.setValue( 0 )
                                    }
                                } )
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
                            afterrender : function( me ){
                                me.store.load( {
                                    callback    : function(){
                                        me.setValue( 1 )
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
                ,moduleGrids    : _gridDisbursement()
            } );
        }

        function _gridDisbursement(){
            var store   = standards.callFunction( '_createRemoteStore', {
                fields      : [
                    'affiliateName'
                    ,'date'
                    ,'reference'
                    ,'supplierName'
                    ,'chequeNo'
                    ,'chequeDate'
                    ,'remarks'
                    ,'chequeNo'
                    ,{  name    : 'amount'
                        ,type   : 'number'
                    }
                    ,'idInvoice'
                    ,'idModule'
                ]
                ,url        : route + 'getDisbursementSummary'
            } );
            
            return standards.callFunction( '_gridPanel', {
                id          : 'gridDisbursementSummary' + module
                ,module     : module
                ,store      : store
                ,tbar       : {
                    content     : ''
                }
                ,viewConfig: {
                    listeners: {
                        itemdblclick: function(dataview, record, item, index, e) {
                            mainView.openModule( record.data.idModule , record.data, this );
                        }
                    }
                }
                ,noDefaultRow : true
                ,noPage     : true
                ,plugins    : true
                ,columns    : [
                    {  header          : 'Affiliate'
                        ,dataIndex      : 'affiliateName'
                        ,width          : 100
                        ,columnWidth    : 10
                    }
                    ,{  header          : 'Date'
                        ,dataIndex      : 'date'
                        ,xtype          : 'datecolumn'
                        ,format         : 'm/d/Y'
                        ,width          : 100
                        ,columnWidth    : 10
                    }
                    ,{  header          : 'Reference'
                        ,dataIndex      : 'reference'
                        ,width          : 100
                        ,columnWidth    : 10
                    }
                    ,{  header          : 'Name'
                        ,dataIndex      : 'supplierName'
                        ,minWidth       : 150
                        ,flex           : 1
                        ,columnWidth    : 20
                    }
                    ,{  header          : 'Cheque Details'
                        ,dataIndex      : 'chequeNo'
                        ,width          : 150
                        ,columnWidth    : 15
                    }
                    ,{  header          : 'Check Date'
                        ,dataIndex      : 'chequeDate'
                        ,width          : 100
                        ,columnWidth    : 15
                    }
                    ,{  header          : 'Remarks'
                        ,dataIndex      : 'remarks'
                        ,width          : 100
                        ,columnWidth    : 15
                    }
                    ,{  header          : 'Total Payment'
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
            Ext.getCmp( 'idAffiliate' + module ).fireEvent( 'afterrender' );
            Ext.getCmp( 'idSupplier' + module ).store.proxy.extraParams.idAffiliate = null;
            Ext.getCmp( 'idSupplier' + module ).store.load({
                callback    : function(){
                    Ext.getCmp( 'idSupplier' + module ).setValue( 0 )
                }
            })
            Ext.getCmp( 'viewBy' + module ).setValue( 1 );
        }

        function _printPDF(){
            var _grid               = Ext.getCmp( 'gridDisbursementSummary' + module );

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
            var _grid = Ext.getCmp( 'gridDisbursementSummary' + module );

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