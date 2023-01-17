/**
 * Developer    : Jayson Dagulo
 * Module       : Closing Journal Entry
 * Date         : Jan 28, 2019
 * Finished     : 
 * Description  : This module allows authorized user to manually closes the journal entries.
 * DB Tables    : 
 * */ 
function Closingentry(){
    return function(){
        var baseurl, route, module, canSave, canEdit, canDelete, canPrint, pageTitle, idModule, isGae,isSaved = 0, idAffiliate;
        
        function _mainPanel( config ){
            return standards.callFunction( '_mainPanel', {
                config          : config
                ,idModule       : idModule
                ,module         : module
                ,moduleType     : 'form'
                ,tbar           : {
                    saveFunc            : _saveForm
                    ,resetFunc          : _resetForm
                    ,formPDFHandler     : _printPDF
                    ,hasFormPDF         : true
                    ,filter : {
                        searchURL       : route + 'getClosingEntryRef'
                        ,emptyText      : 'Search reference here...'
                    }
                }
                ,formItems      : [
                    {   xtype       : 'fieldset'
                        ,layout     : 'column'
                        ,padding    : 10
                        ,style      : '-moz-border-radius:8px; -webkit-border-radius:8px; border-radius:8px;'
                        ,items      : [
                            {   xtype           : 'container'
                                ,columnWidth    : .5
                                ,items          : [
                                    {   xtype   : 'hidden'
                                        ,id     : 'idInvoice' + module
                                        ,value  : 0
                                    }
                                    ,standards2.callFunction( '_createAffiliateCombo', {
                                        module      : module
                                        ,listeners  : {
                                            afterrender : function(){
                                                var me = this;
                                                me.setVisible( false );
                                                me.store.load( {
                                                    callback    : function(){
                                                        me.setValue( parseInt( Ext.getConstant( 'AFFILIATEID' ), 10 ) )
                                                        _reloadGrid();
                                                    }
                                                } )
                                            }
                                        }
                                    } )
                                    ,standards2.callFunction( '_transactionReference', {
                                        module          : module
                                        ,idModule       : idModule
                                        ,idAffiliate    : parseInt( Ext.getConstant( 'AFFILIATEID' ), 10 )
                                        ,dId            : 'date' + module
                                        ,style          : 'margin-bottom : 5px;'
                                        ,readOnly       : true
                                    } )
                                    ,standards.callFunction( '_createTextField', {
                                        id              : 'description' +    module
                                        ,fieldLabel     : 'Description'
                                    } )
                                    ,{  xtype   : 'container'
										,style  : 'margin-bottom : 5px;'
										,layout : 'column'
										,items  : [
											standards.callFunction( '_cmbMonth', {
												fieldLabel  : 'Month'
												,id         : 'month' + module
												,allowBlank : false
												,width      : 260
												,style      : 'margin-right : 5px;'
												,module     : module
												,listeners  : {
													select  : function(){
														_reloadGrid();
													}
												}
											} )
											,standards.callFunction( '_createNumberField', {
												id                      : 'year' + module
												,fieldLabel             : ''
												,withREQ                : false
												,allowBlank             : false
												,decimalPrecision       : 0
												,width                  : 85
												,hideTrigger            : false
												,minValue               : 1980
												,maxValue               : ( ( new Date() ).getFullYear() + 1 )
												,value                  : ( new Date() ).getFullYear()
												,useThousandSeparator   : false
												,listeners              : {
													change  : function(){
														_reloadGrid();
													}
												}
											} )
										]
									}
                                ]
                            }
                            ,{  xtype           : 'container'
                                ,columnWidth    : .5
                                ,items          : [
                                    standards.callFunction( '_createDateField', {
                                        id          : 'date' + module
                                        ,fieldLabel : 'Date'
                                        ,allowBlank : false
                                    } )
                                    ,standards.callFunction( '_createTextArea', {
                                        id          : 'remarks' + module
                                        ,fieldLabel : 'Remarks'
                                        ,height     : 50
                                    } )
                                ]
                            }
                        ]
                    }
                    ,{   title      : 'Journal Entries'
                        ,xtype      : 'fieldset'
                        ,layout     : 'fit'
                        ,padding    : 10
                        ,style      : '-moz-border-radius:8px; -webkit-border-radius:8px; border-radius:8px;'
                        ,items      : _gridJournalEntries()
                    }
                ]
                ,listItems  : _gridHistory()
            } );
        }

        function _gridHistory(){
            var store   = standards.callFunction( '_createRemoteStore', {
                fields      : [
                    'idInvoice'
                    ,'date'
                    ,'affiliateName'
                    ,'reference'
                    ,'description'
                    ,'monthDis'
                    ,'year'
                    ,'month'
                    ,'statusDis'
                    ,'idAffiliate'
                ]
                ,url        : route + 'getHistory'
            } );
            
            return standards.callFunction( '_gridPanel', {
                id              : 'gridHistory' + module
                ,store          : store
                ,noDefaultRow   : true
                ,module         : module
                ,idModule       : idModule
                ,columns        : [
                    {   header          : 'Date'
                        ,dataIndex      : 'date'
                        ,xtype          : 'datecolumn'
                        ,format         : 'm/d/Y'
                        ,width          : 100
                        ,columnWidth    : 10
                    }
                    ,{  header          : 'Reference'
                        ,dataIndex      : 'reference'
                        ,width          : 100
                        ,columnWidth    : 15
                    }
                    ,{  header          : 'Description'
                        ,dataIndex      : 'description'
                        ,flex           : 1
                        ,minWidth       : 150
                        ,columnWidth    : 35
                    }
                    ,{  header          : 'Month'
                        ,dataIndex      : 'monthDis'
                        ,width          : 100
                        ,columnWidth    : 15
                    }
                    ,{  header          : 'Year'
                        ,dataIndex      : 'year'
                        ,width          : 100
                        ,columnWidth    : 15
                    }
                    ,{  header          : 'Status'
                        ,dataIndex      : 'statusDis'
                        ,width          : 100
                        ,columnWidth    : 10
                    }
                    ,standards.callFunction( '_createActionColumn', {
						icon            : 'pencil'
						,tooltip        : 'Edit record'
						,Func           : _editRecord
					} )
					,standards.callFunction( '_createActionColumn' ,{
						canDelete       : canDelete
						,icon           : 'remove'
						,tooltip        : 'Remove record'
						,Func           : _deleteRecord
					} )
                ]
            } )
        }

        function _gridJournalEntries(){
            var store   = standards.callFunction( '_createRemoteStore', {
                fields      : [
                    {	name	: 'idCoa'
                        ,type	: 'number'
                    }
                    ,{	name	: 'debit'
                        ,type	: 'float'
                    }
                    ,{	name	: 'credit'
                        ,type	: 'float'
                    }
                    ,'name'
                    ,'code'
                ]
                ,url        : route + 'getClosingEntries'
            } );
            
            return standards.callFunction( '_gridPanel', {
                id				: 'gridJournal' + module
                ,module			: module
                ,store			: store
                ,height         : 450
                ,noDefaultRow	: true
                ,tbar			: {
                    content		: ''
                }
                ,plugins		: true
                ,noPage         : true
                ,columns		: [
                    {	header		: 'Code'
                        ,dataIndex	: 'code'
                        ,width		: 130
                    }
                    ,{	header		: 'Name'
                        ,dataIndex	: 'name' 
                        ,flex		: 1
                        ,minWidth	: 150
                    }
                    ,{	header		: 'Debit'
                        ,dataIndex	: 'debit'
                        ,width		: 120
                        ,xtype		: 'numbercolumn'
                        ,hasTotal	: true
                    }
                    ,{	header		: 'Credit'
                        ,dataIndex	: 'credit'
                        ,width		: 120
                        ,xtype		: 'numbercolumn'
                        ,hasTotal	: true
                    }
                ]
                ,listeners      : {
                    afterrender : function(){
                        _reloadGrid();
                    }
                }
            } )
        }
        
        function _saveForm( form ){
            Ext.Msg.show( {
                title       : Ext.getConstant( 'MSGBOX_TITLE' )
                ,msg        : 'How would you like to save this record? Click "Draft" if you want to save the record in draft. Click "Final" if you want to save the record in final status.'
                ,icon       : Ext.MessageBox.QUESTION
                ,buttons    : Ext.MessageBox.YESNO
                ,buttonText : {
                    yes : 'Draft'
                    ,no : 'Final'
                }
                ,closable   : false
                ,fn : function( btn ){
                    var status  = 1;
                    if( btn == 'no' ) status = 2;
                    /* process saving */
                    var closingEntries  = new Array()
                        ,grdJournal     = Ext.getCmp( 'gridJournal' + module ).getStore().getRange();
                    grdJournal.forEach( function( data ){
                        closingEntries.push( data.data )
                    } );
                    form.submit( {
                        waitTitle	: "Please wait"
                        ,waitMsg	: "Submitting data..."
                        ,url		: route + 'saveClosingEntry'
                        ,params		: {
                            closingEntries  : Ext.encode( closingEntries )
                            ,idModule       : idModule
                            ,status         : status
                            ,monthDis       : Ext.getCmp( 'month' + module ).getRawValue()
                        }
                        ,success    : function( action, response ){
                            var resp    = Ext.decode( response.response.responseText )
                                ,match  = parseInt( resp.match, 10 );
                            switch( match ){
                                case 1:  /* reference already exists */
                                    standards.callFunction( '_createMessageBox', {
                                        msg     : 'Reference number already exists. System will generate new reference number.'
                                        ,fn     : function(){
                                            standards2.callFunction( '_getReferenceNum', {
                                                idReference     : Ext.getCmp( 'idReference' + module ).getValue()
                                                ,idModule       : idModule
                                                ,idAffiliate    : Ext.getConstant( 'AFFILIATEID' )
                                            } );
                                        }
                                    } );
                                    break;
                                case 2: /* cannot find record to edit */
                                    standards.callFunction( '_createMessageBox', {
                                        msg : 'EDIT_UNABLE'
                                        ,fn : function(){
                                            _rsetForm( form );
                                        }
                                    } )
                                    break;
                                case 3: /* modified by other users */
                                    standards.callFunction( '_createMessageBox', {
                                        msg		: 'SAVE_MODIFIED'
                                        ,action	: 'confirm'
                                        ,fn		: function( btn ){
                                            if( btn == 'yes' ){
                                                form.modify = true;
                                                _saveForm( form );
                                            }
                                        }
                                    } );
                                    break;
                                case 4: /* month and year already closed */
                                    standards.callFunction( '_createMessageBox', {
                                        msg : 'Unable to save this record. ' + Ext.getCmp( 'month' + module ).getRawValue() + ' - ' + Ext.getCmp( 'year' + module ).getValue() + ' is already closed.'
                                    } )
                                    break;
                                case 5: /* succeeding month already closed */
                                    standards.callFunction( '_createMessageBox', {
                                        msg : 'Unable to save this record. Succeeding month is already closed.'
                                    } )
                                    break;
                                case 6: /* previous closing entry not yet tagged as final */
                                    standards.callFunction( '_createMessageBox', {
                                        msg : 'Tag first previous closing entries as final before you can proceed with saving.'
                                    } )
                                    break;
                                case 7: /* previous month not yet closed */
                                    standards.callFunction( '_createMessageBox', {
                                        msg : resp.month + ' ' + resp.year +' was not yet closed. Please close ' + resp.month + ' ' + resp.year + ' first before you can proceed with saving.'
                                    } );
                                    break;
                                case 8:
                                    standards.callFunction( '_createMessageBox', {
                                        msg : 'Chart of Accounts Beginning Balance(' + resp.month + ' ' + resp.year + ') period was not yet closed. Please close it first before you can proceed with saving.'
                                    } );
                                    break;
                                default:
                                    standards.callFunction( '_createMessageBox', {
                                        msg : 'SAVE_SUCCESS'
                                        ,fn : function(){
                                            _resetForm( form );
                                        }
                                    } );
                                    break;
                            }
                        }
                    } )
                }
            } );
        }

        function _editRecord( data ){
            module.getForm().retrieveData( {
                url         : route + 'retrieveData'
                ,params     : data
                ,hasFormPDF	: true
                ,success    : function( view, match ){
                    /* match value
                     * 0 = OK
                     * 1 = record not found
                     * 2 = record used
                    */
                    standards2.callFunction( '_setTransaction', {
                        data    : view
                        ,module : module
                    } );
                }
            } );
        }

        function _deleteRecord( data ){
            data['idModule']    = idModule
            data.confirmDelete( {
				url         : route + 'deleteRecord'
				,params     : data
				,success    : function( response ){
					var resp = Ext.decode( response.responseText )
						,match = parseInt( resp.match, 10 );
					if( match == 1 ){
						standards.callFunction( '_createMessageBox', {
							msg : 'EDIT_UNABLE'
						} )
					}
					else if( match == 2 ){
						standards.callFunction( '_createMessageBox', {
							msg : 'DELETE_USED'
						} )
					}
					else{
						standards.callFunction( '_createMessageBox', {
							msg : 'DELETE_SUCCESS'
							,fn : function(){
								var store = Ext.getCmp( 'gridHistory' + module ).getStore();
								if( store.getCount() == 1 && store.currentPage != 1 ){
									store.currentPage--;
								}
								store.load();
								
								if( Ext.getCmp( 'idInvoice' + module ).value == data.idInvoice ){
									_resetForm( module.getForm() );
								}
							}
						} )
					}
				}
			} );
        }

        function _resetForm( form ){
            form.reset();
            Ext.getCmp( 'idAffiliate' + module ).fireEvent( 'afterrender' );
            Ext.getCmp( 'idReference' + module ).fireEvent( 'afterrender' );
        }

        function _reloadGrid(){
            var month          = Ext.getCmp( 'month' + module ).getValue()
                ,year           = Ext.getCmp( 'year' + module ).getValue()
                ,store          = Ext.getCmp( 'gridJournal' + module ).getStore();
            if( idAffiliate && month && year ){
                store.proxy.extraParams = {
                    month      : month
                    ,year       : year
                }
                store.load( {} )
            }
            else store.removeAll();
        }

        function _printPDF(){
            var par  = standards.callFunction('getFormDetailsAsObject',{ module : module });
            par['title']            = 'Closing Journal Entry Form'
            par['idInvoice']        = Ext.getCmp( 'idInvoice' + module ).getValue();
            par['month']            = Ext.getCmp( 'month' + module ).getValue();
            par['year']             = Ext.getCmp( 'year' + module ).getValue();

            Ext.Ajax.request( {
                url			: route + 'generatePDF'
                ,method		:'post'
                ,params		: par
                ,success	: function(response, action){
                    if( isGae == 1 ){
						window.open(route+'viewPDF/Closing Journal Entry Form','_blank');
					}else{
						window.open('pdf/accounting/Closing Journal Entry Form.pdf');
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
                canSave     = config.canSave;
                canEdit     = config.canEdit;
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