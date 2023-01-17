/**
 * Developer: Hazel Alegbeleye
 * Module: Bank Settings
 * Date: Dec 3, 2019
 * Finished: December 3, 2019
 * Description: This module allows the authorized user to set (add, edit and delete) a bank
 * DB Tables: bank, bankaccount
 * */ 

function Banksettings() {
     return function() {
        var baseurl, route, module, canDelete, pageTitle, canPrint, isGae, selRec, componentCalling , onEdit = 1; 

        function _mainPanel( config ) {

            var bankSettingsWindow = Ext.create('Ext.window.Window',{
                id          : 'bankSettingsWindow' + module
                ,title      : 'Bank Settings'
                ,width      : 480
                ,height     : 340
                ,modal      : true
                ,closable   : true
                ,resizable  : false
                ,items : [
                    Ext.create('Ext.form.Panel',{
                        id              : 'bankSettingsForm' + module
                        ,border         : false
                        ,bodyPadding    : 5
                        ,items          : [
                            {
                                xtype           : 'container'
                                ,columnWidth    : 0.40
                                ,style          : 'padding: 10px'
                                ,items          : [
                                    {
                                        xtype   : 'hiddenfield'
                                        ,id     : 'idBank' + module
                                        ,value  : 0
                                    }
                                    ,standards.callFunction( '_createTextField' ,{
                                        id          : 'bankName' + module
                                        ,fieldLabel : 'Bank Name'
                                        ,allowBlank : false
										,maxLength	: 50
                                    })
                                    ,{  text        : 'Save'
										,xtype      : 'button'
                                        ,iconCls    : 'glyphicon glyphicon-floppy-disk'
                                        ,formBind   : true
										,handler    : function() {
											_saveBank( Ext.getCmp( 'bankSettingsForm' + module ).form )
										}
										,width      : 60
									}
									,{  text        : 'Reset'
										,xtype      : 'button'
										,iconCls    : 'glyphicon glyphicon-refresh'
										,style      : 'margin-left:5px;'
										,handler    : function(){
                                            _resetForm( Ext.getCmp( 'bankSettingsForm' + module ).form ) 
                                        }
										,width      : 60
                                    }
                                    ,gridHistory()
                                ]
                            }
                        ]
                    } )
                ]
            }); 

            function gridHistory() {

                var bankStore = standards.callFunction( '_createRemoteStore', {
                    fields : [ { type: 'number', name: 'idBank' }, 'bankName']
                    ,url   : route + 'getBanks'
                } );

                function _editRecord( data ) {
                    onEdit = 0;

                    Ext.Ajax.request({
                        url     : route + 'retrieveData'
                        ,method : 'post'
                        ,params : {
                            idBank  : data.idBank
                        }
                        ,success : function( response ) {
                            var resp = Ext.decode( response.responseText );
                            Ext.getCmp( 'idBank' + module ).setValue(resp.view[0].idBank);
                            Ext.getCmp( 'bankName' + module ).setValue(resp.view[0].bankName);
                        }
                    });
                }
    
                function _deleteRecord( data ) {

                    standards.callFunction( '_createMessageBox', {
                        msg	    : 'DELETE_CONFIRM'
                        ,action : 'confirm'
                        ,fn	    : function( btn ){
                            if( btn == 'yes' ) {

                                Ext.Ajax.request({
                                    url: route + 'deleteBank'
                                    ,params : {
                                        idBank : data.idBank
                                    }
                                    ,method : 'post'
                                    ,success: function( response ) {
                                        var resp = Ext.decode( response.responseText );

                                        standards.callFunction( '_createMessageBox', {
                                            msg	: ( resp.match == 1 ) ? 'DELETE_USED' : "DELETE_SUCCESS"
                                            ,fn	: function(){
                                                _resetForm( Ext.getCmp( 'bankSettingsForm' + module ).form );
                                            }
                                        } )
                                    }
                                });

                            }
                        }
                    } )


                    
                }

                return standards.callFunction( '_gridPanel', {
                    id          : 'grdBanks' + module
                    ,module     : module
                    ,store      : bankStore
                    ,height     : 200
                    ,style      : 'margin-top:10px;'
                    // ,noPage     : true
                    ,columns    : [
                        {   header      : 'Bank Name'
                            ,dataIndex  : 'bankName'
                            ,flex       : 1
                            ,minWidth   : 80
                        }
                        ,standards.callFunction( '_createActionColumn', {
                            icon        : 'pencil'
                            ,tooltip    : 'Edit record'
                            ,Func       : _editRecord
                        })
                        ,standards.callFunction( '_createActionColumn' ,{
                            canDelete   : canDelete
                            ,icon       : 'remove'
                            ,tooltip    : 'Remove record'
                            ,Func       : _deleteRecord
                        })
                    ]
                    ,listeners  : {
                        afterrender : function() {
                            bankStore.load({});
                        }
                    }
                } )
            }

            function _resetForm( form ) {
                onEdit = 1;

                form.reset();
                Ext.getCmp('grdBanks' + module).store.load({});
            }

            function _saveBank( form ) {
                var params = {
                    bankName    : Ext.getCmp('bankName' + module).getValue()
                    ,idBank     : Ext.getCmp('idBank' + module).getValue()
                    ,onEdit     : onEdit
                }

                Ext.Ajax.request({
                    url         : route + 'saveBank'
                    ,params     : params
                    ,method     : 'post'
                    ,success    : function( response ) {
                        var resp = Ext.decode( response.responseText );
                        standards.callFunction( '_createMessageBox', {
                            msg	: resp.view.msg
                            ,fn	: function(){
                                if( componentCalling ){
                                    componentCalling.store.load( {
                                        callback: function(){
                                            componentCalling.setValue( resp.view.idBank );
                                        }
                                    } );
                                    bankSettingsWindow.destroy( true );
                                }
                                else{
                                    _resetForm( form );
                                }
                            }
                        } )
                    }
                });
            }
            
            return bankSettingsWindow.show();
        }

        return{
			initMethod:function( config ){
				route = config.route;
				baseurl = config.baseurl;
				module = config.module;
				canDelete = config.canDelete;
				canPrint = config.canPrint;
				pageTitle = config.pageTitle;
				isGae = config.isGae;
                canEdit = config.canEdit;
                config['canSave'] = config.canEdit;
                idModule = config.idmodule;
                selRec              = config.selRec;
				componentCalling    = config.componentCalling;
				
				return _mainPanel( config );
			}
		}
     }
 }