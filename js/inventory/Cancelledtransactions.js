function Cancelledtransactions() {
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
                ,formItems      : [{
                    xtype		: 'container'
                    ,layout		: 'column'
                    ,style      : 'margin-bottom : 5px;'
                    ,items		: [
                        {
                            xtype			: 'container'
                            ,columnWidth	: .5
                            ,items			: __filterLeft()
                        }
                        ,{
                            xtype			: 'container'
                            ,columnWidth	: .5
                            ,items			: __filterRight()
                        }
                    ]
                }]
                ,moduleGrids    : _moduleGrid()
            } )
        }

        function __filterLeft() {
            let referenceStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name']
                ,url        : route + 'getReferences'
                ,startAt    :  0
                ,autoLoad   : true
            })

            return[
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
                            Ext.getCmp( 'idModule' + module ).store.proxy.extraParams.idAffiliate = me.getValue();
                            
                            Ext.getCmp( 'idReference' + module ).store.load( {
                                callback   : function() {
                                    Ext.getCmp( 'idReference' + module ).setValue( 0 );
                                }
                            } )

                            Ext.getCmp( 'idModule' + module ).store.load( {
                                callback   : function() {
                                    Ext.getCmp( 'idModule' + module ).setValue( 0 );
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
                                    ,width          : 240
                                    ,maxValue       : new Date()
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
                            })
                            ]
                        }
                    ]
                }
            ]
        }

        function __filterRight() {
            let typeOfStore = standards.callFunction( '_createLocalStore', {
                data        : [ 'All', 'Customer', 'Supplier' ]
                ,startAt    : 0
            } );

            let nameStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name' , 'pType']
                ,url        : route + 'getPNames'
                ,startAt    :  0
                ,autoLoad   : true
            })

            let moduleStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name']
                ,url        : route + 'getModules'
                ,startAt    :  0
                ,autoLoad   : true
            })

            return[
                // MODULE COMBOBOX
                ,standards.callFunction( '_createCombo', {
                    id              : 'idModule' + module
                    ,fieldLabel     : 'Module'
                    ,allowBlank     : true
                    ,store          : moduleStore
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
                    xtype   : 'container'
                    ,layout : 'column'
                    ,width  : 556
                    ,items: [
                        {
                            xtype : 'container'
                            ,columnWidth : .4
                            ,items : [
                                // CUSTOMER COMBOBOX
                                ,standards.callFunction( '_createCombo', {
                                    id              : 'pType' + module
                                    ,fieldLabel     : 'Name'
                                    ,allowBlank     : true
                                    ,store          : typeOfStore
                                    ,displayField   : 'name'
                                    ,valueField     : 'id'
                                    ,width          : 220
                                    ,value          : 0
                                    ,listeners      : {
                                        select     : function(){
                                            var me  = this;
                                            Ext.getCmp( 'pCode' + module ).store.proxy.extraParams.pType = me.getValue();
                                            Ext.getCmp( 'pCode' + module ).store.load( {
                                                callback   : function() {
                                                    Ext.getCmp( 'pCode' + module ).setValue( 0 );
                                                }
                                            } )
                                        }
                                    }
                                } )
                            ]
                        },
                        {
                            xtype : 'container'
                            ,columnWidth : .6
                            ,items : [
                                // CUSTOMER COMBOBOX
                                ,standards.callFunction( '_createCombo', {
                                    id              : 'pCode' + module
                                    ,allowBlank     : true
                                    ,store          : nameStore
                                    ,displayField   : 'name'
                                    ,valueField     : 'id'
                                    ,labelWidth     : 1
                                    ,width          : 128
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
                            ]
                        }
                    ]
                }
                
            ]
        }

        function _moduleGrid() {
            let store   = standards.callFunction( '_createRemoteStore', {
                url     : route + 'getCancelledtransactions'
                ,fields : [
                    'affiliateName'
                    ,'reference'
                    ,'date'
                    ,'name'
                    ,'remarks'
                    ,'cancelledBy'
                    ,{ name: 'amount' ,type: 'number' }
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
                        ,width          : 150
                    }
                    ,{  header          : 'Reference'
                        ,dataIndex      : 'reference'
                        ,width          : 100
                    }
                    ,{  header          : 'Date'
                        ,dataIndex      : 'date'
                        ,xtype          : 'datecolumn'
                        ,format         : 'm/d/Y h:i A'
                        ,width          : 150
                    }
                    ,{  header          : 'Name'
                        ,dataIndex      : 'name'
                        ,width          : 150
                        ,minWidth       : 130
                        ,flex           : 1
                    }
                    ,{  header          : 'Remarks'
                        ,dataIndex      : 'remarks'
                        ,width          : 150
                    }
                    ,{  header          : 'Cancelled By'
                        ,dataIndex      : 'cancelledBy'
                        ,width          : 150
                    }
                    ,{  header          : 'Amount'
                        ,dataIndex      : 'amount'
                        ,width          : 130
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,sortable       : false
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

            Ext.getCmp( 'idModule' + module ).store.proxy.extraParams.idAffiliate = null;
            Ext.getCmp( 'idModule' + module ).store.load( {
                callback   : function( ){
                    Ext.getCmp( 'idModule' + module ).setValue( 0 );
                }
            } )

            Ext.getCmp( 'pCode' + module ).store.proxy.extraParams.pType = null;
            Ext.getCmp( 'pCode' + module ).store.load( {
                callback   : function( ){
                    Ext.getCmp( 'pCode' + module ).setValue( 0 );
                }
            } )
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