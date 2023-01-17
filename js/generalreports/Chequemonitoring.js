function Chequemonitoring() {
    return function() {
        var baseurl, route, module, canDelete, pageTitle, idModule, isGae,isSaved = 0, idAffiliate , fieldWidth = 406
            ,canAdd , change = false;

        function _mainPanel( config ){
            return standards.callFunction( '_mainPanel', {
                config          : config
                ,moduleType     : 'form'
                ,showOnForm     : true
                ,hasApproved    : false
                ,tbar           : {
                    saveFunc            : _saveForm
                    ,resetFunc          : _resetForm
                    ,noFormButton       : true
                    ,noListButton       : true
                    ,noPDFButton        : false
                    ,PDFHidden          : false
                    ,formPDFHandler     : _printPDF
                    ,formExcelHandler   : _printExcel
                }
                ,formItems      : [
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
                ,moduleGrids    : _moduleGrid()
            } );
        }

        function __filterLeft() {
            let chequeStore = standards.callFunction( '_createLocalStore', {
				data        : [ 'All', 'Released', 'Received' ]
            });

            return [
                //AFFIALTE COMBOBOX
                standards2.callFunction( '_createAffiliateCombo', {
                    module      : module
                    ,width      : fieldWidth
		            ,allowBlank : true
                    ,value      : parseInt( Ext.getConstant( 'AFFILIATEID' ), 10 )
                })

                // cheques
                ,standards.callFunction( '_createCombo', {
                    id              : 'cheque' + module
                    ,fieldLabel     : 'Cheques'
                    ,valueField     : 'id'
                    ,displayField   : 'name'
                    ,width          : fieldWidth
                    ,store          : chequeStore
                    ,value          : 1
                } )
            ]
        }

        function __filterRight() {
            let chequeStatusStore = standards.callFunction( '_createLocalStore', {
				data        : [ 'All Cheques', 'Outstanding', 'Cleared', 'Cancelled', 'Bounced' ]
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
                        ,{
                            xtype       : 'button'
                            ,style      : { 'margin-left' : '4.5px' }
                            ,text       : ''
                            ,iconCls    : 'glyphicon glyphicon-search'
                            ,listeners  : {
                                click   : function(){
                                    aff = Ext.getCmp( 'idAffiliate' + module ).getValue(); 
                                    if ( !aff ) {
                                        standards.callFunction('_createMessageBox',{
                                            msg		: 'You need to select an affiliate.'
                                        })
                                    } else {
                                        Ext.getCmp( 'chequeListGrid' + module ).store.load({
                                            params  : {
                                                idAffiliate : Ext.getCmp( 'idAffiliate' + module ).getValue()
                                                ,chequeMod  : Ext.getCmp( 'cheque' + module ).getValue()
                                                ,sdate      : Ext.Date.format( Ext.getCmp( 'sdate' + module ).getValue() ,'Y-m-d')
                                                ,edate      : Ext.Date.format( Ext.getCmp( 'edate' + module ).getValue() ,'Y-m-d')
                                                ,chequeStat : Ext.getCmp( 'chequeStatus' + module ).getValue()
                                            }
                                        })
                                        Ext.getCmp( 'chequeListGrid' + module ).fireEvent( 'afterrender' );
                                    }
                                }
                            }
                        }
                    ]
                }

                // cheques status
                ,standards.callFunction( '_createCombo', {
                    id              : 'chequeStatus' + module
                    ,fieldLabel     : 'Cheque Status'
                    ,valueField     : 'id'
                    ,labelWidth     : 100
                    ,displayField   : 'name'
                    ,width          : fieldWidth
                    ,store          : chequeStatusStore
                    ,value          : 1
                } )

                //  check if there's any changes
                ,standards.callFunction( '_createTextField', {
                    id              : 'idChange' + module
                    ,allowBlank     : false
                    ,hidden		    : true
                    ,submitValue    : false
                })
            ]
        }

        function _moduleGrid() {
            var store       = standards.callFunction( '_createRemoteStore', {
                url        : route + 'getChequesList'
                ,fields      : [
                    // NEEDED FOR OTHER FUNCTION
                    'idAffiliate'
                    ,'oldStatus'
                    ,'idPostdated'
                    ,'idInvoice'
                    ,'idModule'
                    
                    //GRID STORE
                    ,'affiliateName'
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
                ]
            } )

            return standards.callFunction( '_gridPanel', {
                id              : 'chequeListGrid' + module
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
                ,listeners      : {
                    beforeedit  : function( val , rowdata ){
                        if ( rowdata.record.get( 'oldStatus' ) != 1 ) {
                            return false
                        }
                    }
                    ,edit       : function( val , rowdata ){
                        switch( rowdata.field ){
                            case "status": 
                                if ( rowdata.record.raw.status != rowdata.record.get( "status" ) ) {
                                    change = true;
                                    Ext.getCmp( 'idChange' + module ).setValue( rowdata.value );
                                }
                                break;
                            case "statusDate": 
                                if ( rowdata.record.raw.statusDate != rowdata.record.get( "statusDate" ) ) {
                                    Ext.getCmp( 'idChange' + module ).setValue( rowdata.value );
                                }
                                break;
                            case "depositTo": 
                                if ( rowdata.record.raw.depositTo != rowdata.record.get( "depositTo" ) ) {
                                    Ext.getCmp( 'idChange' + module ).setValue( rowdata.value );
                                }
                                break;
                            default: break;
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
                        ,width          : 110
                        ,sortable       : false
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
                        ,editor         : _chequeStatus()
                    }
                    ,{  header          : 'Status Date'
                        ,dataIndex      : 'statusDate'
                        ,width          : 110
                        ,sortable       : false
                        ,editor         : 'date'
                        ,xtype          : 'datecolumn'
                        ,format         : 'm/d/Y'
                    }
                    ,{  header          : 'Deposit To'
                        ,dataIndex      : 'depositTo'
                        ,width          : 120
                        ,sortable       : false
                        ,editor         : _depositedTo()
                    }
                ]
            } );
        }

        function _chequeStatus() {
            let chequeStatusStore = standards.callFunction( '_createLocalStore', {
			    data        : [ 'Outstanding', 'Cancelled', 'Bounced' ]
            });

            return standards.callFunction( '_createCombo', {
                fieldLabel      : ''
                ,valueField     : 'name'
                ,width          : fieldWidth
                ,store          : chequeStatusStore
                ,value          : 1
            } )
        }

        function _depositedTo( ) {
            var itemlist = standards.callFunction( '_createRemoteStore', {
				fields  : [ {	name: 'id' , type: 'number' } , 'name' ]
				,url    : route + 'getBankAccounts'
            });   
            
            return standards.callFunction( '_createCombo', {
                fieldLabel      : ''
                ,valueField     : 'name'
                ,width          : fieldWidth
                ,store          : itemlist
                ,value          : 1
            } )
        }

        function _resetForm( form ) {
            form.reset();
            Ext.getCmp( 'chequeListGrid' + module ).store.load({
                params  : {
                    idAffiliate : parseInt( Ext.getConstant( 'AFFILIATEID' ), 10 )
                    ,chequeMod  : null
                    ,sdate      : null 
                    ,edate      : null 
                    ,chequeStat : null
                }
            })
        }

        function _saveForm( form ) {
            var chequeArray = new Array()
            Ext.getCmp( 'chequeListGrid' + module ).store.data.items.map( function name( cheque ){ chequeArray.push( cheque.data ) } );

            form.submit({
				waitTitle	: "Please wait"
				,waitMsg	: "Submitting data..."
				,url		: route + 'saveChequesChanges'
				,params		: {
					chequeArray : Ext.encode( chequeArray )
				}                    
                ,success    : function( action, response ){
                    var resp            = Ext.decode( response.response.responseText )
                        ,match          = parseInt( resp.match, 10 )
                    switch( match ){
                        case 1: /* already existing record */
                            standards.callFunction( '_createMessageBox', {
                                msg     : 'A bank account has already been created for this affiliate.'
                            } )
                            break;
                        case 0: /* successfully saved */
                            standards.callFunction( '_createMessageBox', {
                                msg     : 'SAVE_SUCCESS'
                                ,fn : function(){
                                    _resetForm( form );
                                }
                            } );
                            break;
                    }
                }
			})
        }

        function _printPDF(){
            var _grid               = Ext.getCmp( 'chequeListGrid' + module );

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
    