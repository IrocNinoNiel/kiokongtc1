/**
 * Developer    : Jayson Dagulo
 * Module       : Chart of Accounts Beginning Balance
 * Date         : Jan 28, 2019
 * Finished     : 
 * Description  : This module allows authorized user to manually closes the journal entries.
 * DB Tables    : 
 * */ 
function Coabegbalance(){
    return function(){
        var baseurl, route, module, canDelete, pageTitle, idModule, isGae,isSaved = 0, idAffiliate
            ,canAdd;

        function _mainPanel( config ){
            return standards.callFunction( '_mainPanel', {
                config          : config
                ,moduleType     : 'form'
				,id			    : 'mainFormID' + module
                ,tbar           : {
                    saveFunc            : _saveForm
                    ,resetFunc          : _resetForm
                    ,hasFormPDF         : true
                    ,noFormButton       : true
                    ,noListButton       : true
                }
                ,formItems      : [
                    standards2.callFunction( '_createAffiliateCombo', {
                        module      : module
                        ,allowBlank : true
                        ,style      : 'margin-bottom: 20px;'
                        ,readOnly   : true
                        ,listeners  : {
                            afterrender : function( ){
                                var me  = this;
                                me.store.load( {
                                    callback    : function(){
                                        me.setValue( parseInt( Ext.getConstant( 'AFFILIATEID' ), 10 ) );
                                        _getCOABeginningBalance();
                                    }
                                } )
                            }
                            ,select     : function( combo, rowData ){
                                Ext.getCmp( 'date' + module ).setValue( rowData[0].data.dateStart );
                                _getCOABeginningBalance();
                            }
                        }
                    } )
                    ,standards.callFunction( '_createDateField', {
                        id      : 'date' + module
                        ,value  : new Date( Ext.getConstant( 'AFFILIATEDATESTART' ) )
                        ,hidden : true
                    } )
                    ,{  xtype   : 'hidden'
                        ,id     : 'idAccBegBal' + module
                        ,value  : 0
                    }
                    ,{  xtype   : 'container'
                        ,layout : 'fit'
                        ,items  : _grdJournalEntry()
                    }
                ]
            } );
        }

        function _grdJournalEntry(){
            var store   = standards.callFunction( '_createRemoteStore', {
                fields      : [
                    'idCoa'
                    ,'acod_c15'
                    ,'aname_c30'
                    ,'accountType'
                    ,{  name    : 'debit'
                        ,type   : 'float'
                    }
                    ,{  name    : 'credit'
                        ,type   : 'float'
                    }
                ]
                ,url        : route + 'getBeginningJournalEntries'
            } );
            return standards.callFunction( '_gridPanel', {
                id				: 'gridJournal' + module
                ,module			: module
                ,store			: store
                ,height         : 450
                ,noDefaultRow	: true
                ,tbar			: {
                    content		: [
                        '->'
                        ,{  xtype       : 'button'
                            ,iconCls    : 'pdf-icon'
                            ,id         : 'pdfPrint' + module
                            ,hidden     : !canPrint
                            ,handler    : function(){
                                _printPDF();
                            }
                        }
                    ]
                }
                ,plugins		: true
                ,noPage         : true
                ,columns		: [
                    {	header		: 'Account Code'
                        ,dataIndex	: 'acod_c15'
                        ,width		: 130
                    }
                    ,{	header		: 'Account Name'
                        ,dataIndex	: 'aname_c30' 
                        ,flex		: 1
                        ,minWidth	: 150
                        ,renderer   : function( value, metaData, rec ){
                            if( parseInt( metaData.record.data.accountType, 10 ) == 1 ) return '<span style="font-weight: bold;">' + value + '</span>';
                            else return value;
                        }
                    }
                    ,{	header		: 'Beginning DR'
                        ,dataIndex	: 'debit'
                        ,width		: 120
                        ,xtype		: 'numbercolumn'
                        ,editor     : 'float'
                        ,hasTotal	: true
                    }
                    ,{	header		: 'Beginning CR'
                        ,dataIndex	: 'credit'
                        ,width		: 120
                        ,xtype		: 'numbercolumn'
                        ,editor     : 'float'
                        ,hasTotal	: true
                    }
                ]
            } );
        }

        function _getCOABeginningBalance(){
            var idAffiliate = Ext.getCmp( 'idAffiliate' + module ).getValue();
            Ext.Ajax.request( {
                url         : route + 'retrieveData'
                ,params     : {
                    idAffiliate : idAffiliate
                }
                ,success    : function( response, option ){
                    var resp            = Ext.decode( response.responseText )
                        ,view           = resp.view;
                    if( view.length > 0 ){
                        Ext.getCmp( 'idAccBegBal' + module ).setValue( view[0].idAccBegBal );
                        module.getForm().dateModified   = view[0].dateModified;
                        module.getForm().onEdit         = true;
                    }
                    else{
                        Ext.getCmp( 'idAccBegBal' + module ).reset();
                        module.getForm().dateModified   = null;
                        module.getForm().onEdit         = false;
                    }

                    Ext.getCmp( 'pdfPrint' + module ).setVisible( canPrint && Ext.getCmp( 'idAccBegBal' + module ).getValue() > 0 )
                    Ext.getCmp( 'gridJournal' + module ).store.load( {
                        params  : {
                            idAccBegBal     : Ext.getCmp( 'idAccBegBal' + module ).getValue()
                            ,idAffiliate    : Ext.getCmp( 'idAffiliate' + module ).getValue()
                        }
                    } )
                }
            } );
        }

        function _saveForm( form ){
            var jerecords       = new Array()
                ,gridData       = Ext.getCmp( 'gridJournal' + module ).getStore().getRange()
                ,totalCredit    = 0
                ,totalDebit     = 0;
            
            for( i = 0;i < gridData.length; i++ ){
                if( gridData[i].get('debit') > 0 || gridData[i].get('credit') > 0 ){
                    jerecords.push( gridData[i].data );
                    totalDebit += gridData[i].get('debit');
                    totalCredit += gridData[i].get('credit');
                }
            }

            if( totalCredit != totalDebit || ( totalCredit == 0 && totalDebit == 0 ) ){
                standards.callFunction( '_createMessageBox', {
                    msg : 'Total debit and credit amounts must have an equal value, or debit and credit amounts must have a value greater than zero.'
                } )
                return false;
            }
            
            form.submit( {
                url     : route + 'saveForm'
                ,params : {
                    jerecords   : Ext.encode( jerecords )
                }
                ,success    : function( action, response ){
                    var resp            = Ext.decode( response.response.responseText )
                        ,match          = parseInt( resp.match, 10 )
                        ,idAccBegBal    = resp.idAccBegBal;
                    switch( match ){
                        case 1: /* already existing record */
                            standards.callFunction( '_createMessageBox', {
                                msg     : 'An account beginning balance has already been created for this affiliate.'
                            } )
                            break;
                        case 2: /* modified by other users */
                            standards.callFunction( '_createMessageBox', {
                                msg     : 'SAVE_MODIFIED'
                                ,action : 'confirm'
                                ,fn     : function( btn ){
                                    if( btn == 'yes' ){
                                        form.modify = true;
                                        _saveForm( form );
                                    }
                                }
                            } )
                            break;
                        case 0: /* successfully saved */
                            standards.callFunction( '_createMessageBox', {
                                msg     : 'SAVE_SUCCESS'
                            } );
                            Ext.getCmp( 'idAccBegBal' + module ).setValue( idAccBegBal );
                            _getCOABeginningBalance();
                            break;
                    }
                }
            } )
        }

        function _resetForm( form ){
            form.reset();
            Ext.getCmp( 'idAffiliate' + module ).fireEvent( 'afterrender' );
        }

        function _printPDF(){
            Ext.Ajax.request( {
                url         : route + 'printPDF'
                ,params     : {
                    title           : pageTitle
                    ,idAccBegBal    : Ext.getCmp( 'idAccBegBal' + module ).getValue()
                    ,idAffiliate    : Ext.getCmp( 'idAffiliate' + module ).getValue()
                    ,affiliateName  : Ext.getCmp( 'idAffiliate' + module ).getRawValue()
                }
                ,success    : function(){
                    if( isGae ){
                        window.open( route + 'viewPDF/' + pageTitle , '_blank' );
                    }
                    else{
                        window.open( baseurl + 'pdf/accounting/' + pageTitle + '.pdf');
                    }
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
