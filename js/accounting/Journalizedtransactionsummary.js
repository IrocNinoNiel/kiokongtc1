function Journalizedtransactionsummary(){
    return function(){
        var baseurl, route, module, canDelete, pageTitle, canPrint, balanceModule, ledgerModule, isGae, idAffiliate;
        
        function _mainPanel( config ){
            var moduleStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name']
                ,url        : route + 'getModules'
                ,startAt    :  0
                ,autoLoad   : true
            })

            var referenceStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name']
                ,url        : route + 'getReference'
                ,startAt    :  0
                ,autoLoad   : true
            })

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
                ,formItems      : [
                    {   xtype   : 'container'
                        ,layout : 'column'
                        ,style  : 'margin-bottom : 5px;'
                        ,items  : [
                            {   xtype           : 'container'
                                ,columnWidth    : .5
                                ,minWidth       : 365
                                ,items          : [

                                    // AFFILIATE 
                                    standards2.callFunction( '_createAffiliateCombo', {
                                        module     : module
                                        ,allowBlank : true
                                        ,value      : parseInt( Ext.getConstant( 'AFFILIATEID' ), 10 )
                                        ,listeners  : {
                                            afterrender : function(){
                                                var me  = this;
                                                Ext.getCmp( 'idModule' + module ).store.proxy.extraParams.idAffiliate = me.getValue();
                                                Ext.getCmp( 'idReference' + module ).store.proxy.extraParams.idAffiliate = me.getValue();

                                                Ext.getCmp( 'idModule' + module ).store.load({
                                                    callback    : function(){
                                                        Ext.getCmp( 'idModule' + module ).setValue( 0 )
                                                    }
                                                })
                                                Ext.getCmp( 'idReference' + module ).store.load({
                                                    callback    : function(){
                                                        Ext.getCmp( 'idReference' + module ).setValue( 0 )
                                                    }
                                                })
                                            }
                                        }
                                    } )

                                    //MODULE COMBOBOX
                                    ,standards.callFunction( '_createCombo', {
                                        id              : 'idModule' + module
                                        ,fieldLabel     : 'Module'
                                        ,valueField     : 'id'
                                        ,displayField   : 'name'
                                        ,store          : moduleStore
                                        ,hasAll         : 1
                                        ,listeners      : {
                                            select : function(){
                                                var me  = this;
                                                Ext.getCmp( 'idReference' + module ).store.proxy.extraParams.idModule = me.getValue();
                                                Ext.getCmp( 'idReference' + module ).store.load({
                                                    callback    : function(){
                                                        Ext.getCmp( 'idReference' + module ).setValue( 0 )
                                                    }
                                                })
                                            }
                                        }
                                    } )

                                    //REFERENCE COMBOBOX
                                    ,standards.callFunction( '_createCombo', {
                                        id              : 'idReference' + module
                                        ,fieldLabel     : 'Reference'
                                        ,valueField     : 'id'
                                        ,displayField   : 'name'
                                        ,store          : referenceStore
                                        ,hasAll         : 1
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

                                    //DATE
                                    ,standards.callFunction( '_createDateRange', {
                                        module          : module
                                        ,width          : 111
                                        ,fromWidth      : 235
                                    } )
                                ]
                            }
                        ]
                    }
                ]
                ,moduleGrids    : _moduleGrid()
            } );
        }

        function _moduleGrid() {
            let store   = standards.callFunction( '_createRemoteStore', {
                url     : route + 'getJournalizedtransactionsummary'
                ,fields : [ 
                    'idModule'
                    ,'idInvoice'        
                    ,'date' 
                    ,'reference' 
                    ,'accountCode' 
                    ,'accountName' 
                    ,{ name: 'debit'  ,type: 'number' }
                    ,{ name: 'credit' ,type: 'number' }
                ]
            } );

            return standards.callFunction( '_gridPanel', {
                id              : 'gridReport' + module
				,module         : module
                ,store          : store
                ,noDefaultRow   : true
                ,plugins        : true
                ,tbar           : {
                    content     : ''
                }
                ,viewConfig: {
                    listeners: {
                        itemdblclick: function(dataview, record, item, index, e) {
                            mainView.openModule( record.data.idModule , record.data, this );
                        }
                    }
                }
                ,features       : {
                    ftype   : 'summary'
                }
                ,noPage         : true
                ,columns        : [
                    {  header           : 'Date'
                        ,dataIndex      : 'date'
                        ,width          : 112
                        ,xtype          : 'datecolumn'
                        ,format         : 'm/d/Y'
                    }
                    ,{  header          : 'Ref'
                        ,dataIndex      : 'reference'
                        ,width          : 112
                    }
                    ,{  header          : 'Account Code'
                        ,dataIndex      : 'accountCode'
                        ,width          : 224
                    }
                    ,{  header          : 'Account Name'
                        ,dataIndex      : 'accountName'
                        ,width          : 445
                        ,minWidth       : 425
                        ,flex           : 1
                    }
                    ,{  header          : 'DB'
                        ,dataIndex      : 'debit'
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,sortable       : false
                        ,hasTotal       : true
                        ,width          : 112
                        ,hasTotal       : true
                    }
                    ,{  header          : 'CR'
                        ,dataIndex      : 'credit'
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,sortable       : false
                        ,hasTotal       : true
                        ,width          : 112
                    }
                ]
            });
        }

        function _resetForm() {
            Ext.getCmp( 'idReference' + module ).store.proxy.extraParams.idModule = null;
            Ext.getCmp( 'idAffiliate' + module ).fireEvent( 'afterrender' );
        }

        function _printPDF(){
            var _grid               = Ext.getCmp( 'gridReport' + module );

            standards.callFunction( '_listPDF', {
                grid                    : _grid
                ,customListPDFHandler   : function(){
                    var par = standards.callFunction( 'getFormDetailsAsObject', {
                        module          : module
                        ,getSubmitValue : true
                        ,idAffiliate    : idAffiliate
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
                            window.open( route + "download/" + par.title + '/accounting');
                        }
                    } );
                }
            } );
        }

        return{
			initMethod  : function( config ){
				route		        = config.route;
				baseurl		        = config.baseurl;
				module		        = config.module;
				canPrint	        = config.canPrint;
				canDelete	        = config.canDelete;
				canEdit		        = config.canEdit;
				pageTitle           = config.pageTitle;
                idModule	        = config.idmodule;
				
				return _mainPanel( config );
			}
		}
    }
}