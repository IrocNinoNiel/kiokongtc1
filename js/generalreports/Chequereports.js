function Chequereports() {
    return function() {
        var baseurl, route, module, canDelete, pageTitle, idModule, isGae,isSaved = 0, idAffiliate , fieldWidth = 406
            ,canAdd , change = false;

        function _mainPanel( config ){
            return standards.callFunction( '_mainPanel', {
                config              : config
                ,moduleType         : 'report'
                ,tbar               : {
					noFormButton        : true
                    ,noListButton       : true
                    ,noPDFButton        : false
                    ,PDFHidden          : false
                    ,formPDFHandler     : _printPDF
                    ,formExcelHandler   : _printExcel
                }
                ,formItems          : [
                    {
                        xtype		: 'fieldset'
                        ,layout		: 'column'
                        ,padding    : 10
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
                    }
                ]
                ,moduleGrids        : _moduleGrid()
            } );
        }

        function __filterLeft() {
            let chequeStore = standards.callFunction( '_createLocalStore', {
				data        : [ 'All', 'Released', 'Received' ]
                ,startAt    :  0
            });

            return [
                //AFFIALTE COMBOBOX
                standards2.callFunction( '_createAffiliateCombo', {
                    module      : module
                    ,width      : fieldWidth
                    ,allowBlank : true
                    ,hasAll     : 1
                    ,value      : 0
                })

                // cheques
                ,standards.callFunction( '_createCombo', {
                    id              : 'cheque' + module
                    ,fieldLabel     : 'Cheques'
                    ,valueField     : 'id'
                    ,displayField   : 'name'
                    ,width          : fieldWidth
                    ,store          : chequeStore
                    ,value          : 0
                } )
            ]
        }

        function __filterRight() {
            let chequeStatusStore = standards.callFunction( '_createLocalStore', {
				data        : [ 'All Cheques', 'Outstanding', 'Cleared', 'Cancelled', 'Bounced' ]
                ,startAt    :  0
            });

            return [

                // DATERANGE COMBOBOX
                {
                    xtype   : 'container'
                    ,layout : 'column'
                    ,width  : fieldWidth
                    ,items  : [
                        standards.callFunction( '_createDateField', {
                            id              : 'sdate' + module
                            ,fieldLabel     : 'Date From'
                            ,allowBlank     : true
                            ,width          : 230
                            ,labelWidth     : 100
                            ,value          : Ext.date().subtract(1, 'month').toDate()
                            ,maxValue       : new Date()
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
                            ,width          : 144
                            ,maxValue       : new Date()
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

                // cheques status
                ,standards.callFunction( '_createCombo', {
                    id              : 'chequeStatus' + module
                    ,fieldLabel     : 'Cheque Status'
                    ,valueField     : 'id'
                    ,labelWidth     : 100
                    ,displayField   : 'name'
                    ,width          : fieldWidth - 25
                    ,store          : chequeStatusStore
                    ,value          : 0
                } )
            ]
        }

        function _moduleGrid() {
            var store       = standards.callFunction( '_createRemoteStore', {
                url        : route + 'getChequesList'
                ,fields      : [
                    'affiliateName'
                    ,'date'
                    ,'reference'
                    ,'description'
                    ,'bankAccount'
                    ,'chequeNo'
                    ,{  name    : 'amount'
                        ,type   : 'number'
                    }
                    ,'status'
                    ,'statusDate'
                    ,'depositTo'
                    ,'idModule'
                    ,'idInvoice'
                ]
            } )

            return standards.callFunction( '_gridPanel', {
                id              : 'chequeListGrid' + module
				,module         : module
                ,store          : store
                ,noDefaultRow   : true
                ,style          : 'margin-top:15px'
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
                        ,width          : 120
                        ,sortable       : false
                    }
                    ,{  header          : 'Cheque Date'
                        ,dataIndex      : 'date'
                        ,xtype          : 'datecolumn'
                        ,format         : 'm/d/Y'
                        ,width          : 120
                        ,sortable       : false
                    }
                    ,{  header          : 'Reference'
                        ,dataIndex      : 'reference'
                        ,width          : 90
                        ,sortable       : false
                    }
                    ,{  header          : 'Description'
                        ,dataIndex      : 'description'
                        ,width          : 120
                        ,sortable       : false
                    }
                    ,{  header          : 'Bank Account'
                        ,dataIndex      : 'bankAccount'
                        ,width          : 120
                        ,sortable       : false
                    }
                    ,{  header          : 'Cheque Number'
                        ,dataIndex      : 'chequeNo'
                        ,sortable       : false
                        ,width          : 110
                    }
                    ,{  header          : 'Amount'
                        ,dataIndex      : 'amount'
                        ,width          : 120
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,sortable       : false
                        ,hasTotal       : true
                    }
                    ,{  header          : 'Status'
                        ,dataIndex      : 'status'
                        ,sortable       : false
                        ,width          : 100
                    }
                    ,{  header          : 'Status Date'
                        ,dataIndex      : 'statusDate'
                        ,width          : 110
                        ,sortable       : false
                        ,xtype          : 'datecolumn'
                        ,format         : 'm/d/Y'
                    }
                    ,{  header          : 'Deposit To'
                        ,dataIndex      : 'depositTo'
                        ,width          : 120
                        ,sortable       : false
                    }
                ]
            } );
        }

        function _printPDF(){
            var _grid               = Ext.getCmp( 'chequeListGrid' + module );

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
                                window.open( baseurl + 'pdf/generalreports/' + par.title + '.pdf');
                            }
                        }
                    } );
                }
            } );
        }

        function _printExcel(){
            var _grid = Ext.getCmp( 'chequeListGrid' + module );

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
                canDelete	= config.canDelete;
                canPrint    = config.canPrint;
                pageTitle   = config.pageTitle;
                idModule	= config.idmodule
                isGae		= config.isGae;
                idAffiliate = config.idAffiliate
                
                return _mainPanel( config );
            }
        }
    }
}
    