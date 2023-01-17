function Bankaccountsettings(){
    return function() {
        var baseurl, route, module, canDelete, pageTitle, idModule, isGae,isSaved = 0, idAffiliate , fieldWidth = 406
            ,canAdd;

        function _mainPanel( config ){
            return standards2.callFunction( '_mainPanelTransactions', {
                config          : config
                ,moduleType     : 'form'
                ,module         : module
                ,hasApproved    : false
                ,tbar           : {
                    saveFunc            : _saveForm
                    ,resetFunc          : _resetForm
                    ,hasFormPDF         : true
                    ,noFormButton       : true
                    ,noListButton       : true
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
                                ,columnWidth	: .4
                                ,items			: __filterRight()
                            }
                            ,{
                                xtype   : 'hiddenfield'
                                ,id     : 'idBankAccount' + module
                                ,value  : 0
                            }
                        ]
                    }
                ]
                ,moduleGrids    : _moduleGrid(config)
            } );
        }

        function __filterLeft() {
            let bankStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name']
                ,url        : route + 'getBanks'
                ,startAt    :  0
                ,autoLoad   : true
            })

            return [
                //AFFIALTE COMBOBOX
                standards2.callFunction( '_createAffiliateCombo', {
                    module      : module
                    ,width      : fieldWidth
                    ,listeners  : {
                        select  : function() {
                            var me = this;
                            Ext.getCmp( 'idCoa' + module ).store.proxy.extraParams.idAffiliate = me.getValue();
                            Ext.getCmp( 'idCoa' + module ).store.load({});
                        }
                        ,afterrender : function(){
                            var me = this;
                            me.store.load( {
                                callback    : function(){
                                    me.setValue( parseInt( Ext.getConstant( 'AFFILIATEID' ), 10 ) )
                                    Ext.getCmp( 'idCoa' + module ).store.proxy.extraParams.idAffiliate = me.getValue();
                                    Ext.getCmp( 'idCoa' + module ).store.load({});
                                }
                            } )
                        }
                    }
                })

                // BANK
                ,standards.callFunction( '_createCombo', {
                    id              : 'idBank' + module
                    ,hasAll         : 1
                    ,fieldLabel     : 'Bank Name'
                    ,valueField     : 'id'
                    ,displayField   : 'name'
                    ,width          : fieldWidth
                    ,store          : bankStore
                    ,allowBlank : false
                    ,listeners      : {
                        select      : function(){
                            if ( this.value == 0 ) {
                                Ext.getCmp('idBank' + module).reset();
                                mainView.openModule( 10 , null, this );
                            }
                        }
                    }
                } )

                // BANK ACCOUNT NAME
                ,standards.callFunction( '_createTextField', {
                    id              : 'bankAccount' + module
                    ,fieldLabel     : 'Bank Account Name'
                    ,width          : fieldWidth
                    ,allowBlank : false
                } )


                // BANK ACCOUNT NO
                ,standards.callFunction( '_createTextField', {
                    id          : 'bankAccountNumber' + module
                    ,fieldLabel : 'Bank Account No.'
                    ,width      : fieldWidth
                    ,maskRe     : /^[0-9]*$/ 
                    ,regex      : /[0-9.]/
                    ,allowBlank : false
                })	
            ]
        }

        function __filterRight() {
            let coaStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name']
                ,url        : route + 'getCoa'
                ,startAt    :  0
                ,autoLoad   : true
            })

            return [
                // BEGINNING BALANCE
                standards.callFunction( '_createTextField', {
                    id          : 'begBal' + module
                    ,fieldLabel : 'Beginning Balance'
                    ,width      : fieldWidth
                    ,isNumber   : true
                    ,isDecimal  : true
                })	

                // ACCOUNT CODE : COA
                ,standards.callFunction( '_createCombo', {
                    id              : 'idCoa' + module
                    ,fieldLabel     : 'Account Code'
                    ,valueField     : 'id'
                    ,displayField   : 'name'
                    ,allowBlank     : false
                    ,width          : fieldWidth
                    ,store          : coaStore
                    ,listeners      : {
                        select      : function( me, rec ){
                            Ext.getCmp( 'coaName' + module ).setValue( rec[0].get('name') );
                        }
                    }
                } )

                ,standards.callFunction( '_createTextField', {
                    id      : 'coaName' + module
                    ,hidden : true
                })	

                // REMARKS
                ,standards.callFunction( '_createTextArea', {
                    id          : 'remarks' + module
                    ,fieldLabel : 'Remarks'
                    ,width      : fieldWidth
                    ,allowBlank : true
                } )
            ]
        }

        function _moduleGrid(config) {
            var bankAccountStore = standards.callFunction( '_createRemoteStore', {
                url        : route + 'getBankAccounts'
                ,fields      : [
                    'idBankAccount'
                    ,'affiliateName'
                    ,'bankName'
                    ,'bankAccount'
                    ,'bankAccountNumber'
                    ,'aname_c30'
                    ,{  name    : 'begBal'
                        ,type   : 'number'
                    }
                ]
            } )

            return standards.callFunction( '_gridPanel', {
                id              : 'gridHistory' + module
				,module         : module
                ,store          : bankAccountStore
                ,noDefaultRow   : true
                ,tbar           : {
                    filter : {
                        searchURL       : route + 'searchGrid'
                        ,emptyText      : 'Search Bank Account Name here...'
                        ,module         : module
                        ,config         : config
                    }
                }
                ,features       : {
                    ftype   : 'summary'
                }
                ,listeners      :{
                    afterrender: function () {
                        bankAccountStore.load({})
                    }
                }
                ,noPage         : false
                ,plugins        : true
                ,columns        : [
                    {   header          : 'Affiliate'
                        ,dataIndex      : 'affiliateName'
                        ,width          : 170
                        ,sortable       : false
                    }
                    ,{  header          : 'Bank Name'
                        ,dataIndex      : 'bankName'
                        ,width          : 170
                        ,sortable       : false
                        ,flex           : 1
                    }
                    ,{  header          : 'Bank Account Name'
                        ,dataIndex      : 'bankAccount'
                        ,width          : 170
                        ,sortable       : false
                    }
                    ,{  header          : 'Bank Account No'
                        ,dataIndex      : 'bankAccountNumber'
                        ,width          : 170
                        ,sortable       : false
                    }
                    ,{  header          : 'Beginning Bal'
                        ,dataIndex      : 'begBal'
                        ,width          : 170
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,sortable       : false
                    }
                    ,{  header          : 'Account Code'
                        ,dataIndex      : 'aname_c30'
                        ,width          : 170
                        ,sortable       : false
                    }
                    ,standards.callFunction( '_createActionColumn', {
						canEdit     : canEdit
						,icon       : 'pencil'
						,tooltip    : 'Edit record'
						,Func       : _editRecord
                    } )               
                    ,standards.callFunction( '_createActionColumn', {
						canDelete   : canDelete
						,icon       : 'remove'
						,tooltip    : 'Delete record'
						,Func       : _deleteRecord
					} )
                ] 
            } );
        }

        function _editRecord( data ) {
            _resetForm( Ext.getCmp( 'mainFormPanel' + module ).getForm() );
            module.getForm().retrieveData( {
                url         : route + 'retrieveData'
                ,params     : data
                ,success    : function( view , match ){
                    Ext.getCmp( 'idCoa' + module ).setValue( view.coaName );
                }
            } )
        }

        function _resetForm( form ){
            form.reset();
            Ext.getCmp( 'idAffiliate' + module ).fireEvent( 'afterrender' );
            Ext.getCmp( 'gridHistory' + module ).store.proxy.extraParams.filterValue = null
            Ext.getCmp( 'gridHistory' + module ).store.load( {} );
        }

        function _saveForm( form ){
            form.submit( {
                url     : route + 'saveBankAccount'
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

        function _deleteRecord( data ){
			standards.callFunction('_createMessageBox',{
				msg		: 'DELETE_CONFIRM'
				,action	: 'confirm'
				,fn		: function( btn ){
					if ( btn == 'yes' ){
						Ext.Ajax.request({
							url		: route + 'archiveBankAccount'
							,params	: { 
								idBankAccount   : parseInt( data.idBankAccount )
								,coaName        : data.aname_c30
							}
							,method	: 'post'
							,success: function(response){
                                standards.callFunction('_createMessageBox',{ msg: 'DELETE_SUCCESS' })
                                var resp    = Ext.decode( response.responseText );
                                var match   = resp.match;
                                switch( match ){
                                    case 1: /* unable to find record to delete */
                                        standards.callFunction( '_createMessageBox', {
                                            msg		: 'EDIT_UNABLE'
                                        } );
                                        break;
                                    case 2: /* record already used */
                                        standards.callFunction( '_createMessageBox', {
                                            msg		: 'DELETE_USED'
                                        } );
                                        break;
                                    default: /* record successfully saved */
                                        standards.callFunction( '_createMessageBox', {
                                            msg		: 'DELETE_SUCCESS'
                                            ,fn : function(){
                                                _resetForm( Ext.getCmp( 'mainFormPanel' + module ).getForm() );
                                            }
                                        } );
                                        break;
                                }
							}
							,failure: function(){}
						})
					}
				}
			})
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
    