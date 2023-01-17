function Beginningbalance() {
    return function(){
        var baseurl, route, module, canDelete, canCancel, pageTitle, idModule, isGae,isSaved = 0,deletedItems = [],selectedItem = [], idAffiliate, selRec, componentCalling
        ,canSave, fieldWidth = 406, fieldLabelWidth = 185;

        function _mainPanel( config ){
            //MAIN PANEL
            return standards2.callFunction(	'_mainPanelTransactions' ,{
                config		: config
                ,module		: module
                ,moduleType : 'form'
                ,hasApproved: false
                ,hasCancelTransaction: canCancel
                ,listeners  : {
                    afterrender : function () {
                        if ( selRec ) {
                            _editRecord( selRec );
                        }
                    }
                }
                ,tbar       : {
                    saveFunc                : _saveForm
					,formPDFHandler         : _printPDF
                    ,resetFunc              : _resetForm
                    ,filter : {
                        searchURL       : route + 'searchHistoryGrid'
                        ,emptyText      : 'Search reference here...'
                        ,module         : module
                    }
                }
                ,formItems      : [{
                    xtype		: 'fieldset'
                    ,layout		: 'column'
                    ,padding    : 10
                    ,items		: [
                        {
                            xtype			: 'container'
                            ,columnWidth	: .4
                            ,items			: __filterLeft()
                        }
                        ,{
                            xtype			: 'container'
                            ,columnWidth	: .6
                            ,items			: [
                                standards.callFunction( '_createTextArea', {
                                    id          : 'remarks' + module
                                    ,fieldLabel : 'Remarks'
                                    ,width      : fieldWidth
                                    ,height     : fieldWidth/4
                                    ,labelWidth : 75
                                    ,allowBlank : true
                                } )
                            ]
                        }
                    ]
                },{
                    xtype   : 'tabpanel'
                    ,items  : [
                        {
                            title       : 'Journal Entries'
                            ,layout     : {
                                type    : 'card'
                            }
                            ,items      : [
                                standards.callFunction( '_gridJournalEntry', {
                                    module          : module
                                    ,hasPrintOption : 1
                                    ,idModule       : idModule
                                } )
                            ]
                        }
                    ]
                }]
                ,listItems: _gridHistory()
            } )
        }

        function _gridHistory() {
            var store   = standards.callFunction( '_createRemoteStore', {
                fields      : [
                    'reference'
                    ,'idInvoice'
                    ,'date'
                    ,'name'
                    ,{ name: 'amount'  ,type: 'number' }
                    
                    ,'idReference'
                    ,'referenceNum'
                    ,'idModule'
                ]
                ,url        : route + 'getBeginningBalance'
            } );

            return standards.callFunction('_gridPanel', {
                id 					: 'gridHistory' + module
                ,module     		: module
                ,store      		: store
				,height     		: 265
				,noDefaultRow 		: true
                ,columns            : [
                    {  header          : 'Reference Number'
                        ,dataIndex      : 'reference'
                        ,width          : 110
                        ,columnWidth    : 10
                    }
                    ,{  header          : 'Date'
                        ,dataIndex      : 'date'
                        ,width          : 130
                        ,columnWidth    : 13
                        ,xtype          : 'datecolumn'
                        ,format         : 'm/d/Y h:i A'
                    }
                    ,{  header          : 'Name'
                        ,dataIndex      : 'name'
                        ,flex           : 1
                        ,minWidth       : 150
                        ,columnWidth    : 15                    
                    }
                    ,{  header          : 'Amount'
                        ,dataIndex      : 'amount'
                        ,width          : 150
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,sortable       : false
                        ,columnWidth    : 12.5
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
            } )
        }

        function _saveForm( form ){ 
            //SAVE JOURNAL ENTRY
            var grdStore    = Ext.getCmp( 'gridJournalEntry' + module ).getStore()
                ,gridJEData = grdStore.getRange()
                ,jeRecords  = new Array()
                ,totalCR    = 0
                ,totalDR    = 0

            gridJEData.forEach( function( data, index ){
                if( parseInt( data.get( 'idCoa' ), 10 ) > 0
                    && (
                        parseFloat( data.get( 'debit' ) ) > 0
                        || parseFloat( data.get( 'credit' ) ) > 0
                    )
                ){
                    jeRecords.push( data.data );
                    totalCR += parseFloat( data.get( 'credit' ) );
                    totalDR += parseFloat( data.get( 'debit' ) );
                }
            } );

            if( jeRecords.length < 2 && jeRecords.length != 0 ){
                standards.callFunction( '_createMessageBox', {
                    msg     : 'Please add at least two journal entry record to proceed.'
                    ,icon   : Ext.MessageBox.ERROR
                } );
                return false;
            }

            if( totalCR != totalDR ){
                standards.callFunction( '_createMessageBox', {
                    msg     : 'Invalid transaction details. Make sure that total Debit and total Credit is balance.'
                    ,icon   : Ext.MessageBox.ERROR
                } );
                return false;
            }

            form.submit( {
                waitTitle	: "Please wait"
                ,waitMsg	: "Submitting data..."
                ,url		: route + 'saveBeginningBalance'
                ,params		: {
                    jeRecords   : Ext.encode( jeRecords )
                    ,jeLength   : jeRecords.length
                }
                ,success:function( action, resp ){
                    var response = Ext.decode( resp.response.responseText )
                        ,match   = response.match;
                        switch( match ){
                            case 1: /* reference number already exists */
                                standards.callFunction( '_createMessageBox', {
                                    msg     : 'Reference number already exists.'
                                    ,fn     : function(){
                                        standards2.callFunction( '_getReferenceNum', {
                                            idReference     : Ext.getCmp( 'idReference' + module ).getValue()
                                            ,idModule       : idModule
                                            ,idAffiliate    : Ext.getCmp( 'idAffiliate' + module ).getValue()
                                        } );
                                    }
                                } );
                                break;
                            case 2: /* record already modified by other users */
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
                            case 3: /* record to save not found */
                                standards.callFunction( '_createMessageBox', {
                                    msg : 'EDIT_UNABLE'
                                    ,fn : function(){
                                        _resetForm( form );
                                    }
                                } )
                                break;
                            case 4: /* record is already approved by other user and is not allowed to be edited */
                                break;
                            default: /* record has been successfully saved */
                                standards.callFunction( '_createMessageBox', {
                                    msg : 'SAVE_SUCCESS'
                                    ,fn : function(){
                                        _resetForm( form );
                                    }
                                } )
                                break;
                        }
                }
            } )
        }

        function __filterLeft() {
            return [
                //COST CENTER COMBOBOX
                ,standards2.callFunction( '_createCostCenter', {
                    module		: module
                    ,idAffiliate : idAffiliate
                    ,width      : fieldWidth
                    ,allowBlank : true
                })
                
                // REFERENCE
                ,standards2.callFunction( '_transactionReference', {
                    module		    : module
                    ,dId            : 'tdate' + module
                    ,refNumWidth   : 121
                    ,refCodeWidth   : 280
                    ,idAffiliate: idAffiliate
                    ,idModule	: idModule
                    ,style  : 'margin-bottom: 5px;'
                })

                // NAME
                ,{   xtype       : 'container'
                    ,layout     : 'column'
                    ,width      : fieldWidth
                    ,style      : 'margin-bottom : 5px;'
                    ,items   : [
                        {  xtype            : 'hidden'
                            ,id             : 'pType' + module
                            ,value          : 0
                        }
                        ,{  xtype           : 'hidden'
                            ,id             : 'pCode' + module
                            ,value          : 0
                        }
                        ,{  xtype           : 'hidden'
                            ,id             : 'idInvoice' + module
                            ,value          : 0
                        }
                        ,standards.callFunction( '_createTextField', {
                            id              : 'name' + module
                            ,submitValue    : false
                            ,fieldLabel     : 'Name'
                            ,width          : 352
                            ,submitValue    : false
                            ,style          : 'margin-right : 5px;'
                        } )
                        ,{  xtype           : 'button'
                            ,iconCls        : 'glyphicon glyphicon-user'
                            ,id             : 'cmdPCode' + module
                            ,style          : 'margin-right : 5px;'
                            ,handler        : function(){
                                _customerSelector();
                            }
                        }
                        ,{  xtype           : 'button'
                            ,iconCls        : 'glyphicon glyphicon-refresh'
                            ,id             : 'cmdRefPCode' + module
                            ,handler        : function(){
        
                            }
                        }
                    ]
                }

                // DATE AND TIME 
                ,{
                    xtype : 'container'
                    ,layout: 'column'
                    ,width  : 650
                    ,items: [
                        {
                            xtype : 'container'
                            ,columnWidth : .4
                            ,items : [
                                standards.callFunction( '_createDateField', {
                                    id              : 'tdate' + module
                                    ,fieldLabel     : 'Date'
                                    ,allowBlank     : true
                                    ,width          : 280
                                    ,maxValue       : new Date()
                                    ,listeners : {
                                        afterrender	: function(){
                                            standards2.callFunction( '_checkIf_journal_isClosed', { 
                                                idAffiliate	: idAffiliate
                                                , tdate		: this.value
                                                , module	: module 
                                            } )
                                        }
                                        ,change     : function() {
                                            standards2.callFunction( '_checkIf_journal_isClosed', { 
                                                idAffiliate	: idAffiliate
                                                , tdate		: this.value
                                                , module	: module 
                                            } )
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
                                    id              : 'ttime' + module
                                    ,fieldLabel     : 'to'
                                    ,allowBlank     : true
                                    ,labelWidth     : 20
                                    ,width          : 146
                                })
                            ]
                        }
                    ]
                }

                // AMOUNT
                ,standards.callFunction( '_createTextField', {
                    id          : 'amount' + module
                    ,fieldLabel : 'Amount'
                    ,width      : fieldWidth
                    ,isNumber   : true
                    ,isDecimal  : true
                })	
            ]
        }

        function _customerSelector( params ){
            Ext.create('Ext.window.Window',{
				id          : 'pCodeSelectorWindow' + module
				,width      : 330
				,title      :'<span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;Select records'
				,height     : 375
				,modal      : true
				,closable   : false
                ,resizable  : false
                ,layout     : 'fit'
                ,items      : _gridSearch()
                ,buttons    : [
                    {   text        : 'Close'
                        ,handler    : function(){
                            Ext.getCmp( 'pCodeSelectorWindow' + module ).destroy( true );
                        }
                    }
                ]
            } ).show();
        }

        function _gridSearch(){
            var storePType      = standards.callFunction( '_createLocalStore', {
                    data        : [
                        'Customer'
                        ,'Supplier'
                    ]
                    ,startAt    : 1
                } )
                ,grdStore       = standards.callFunction( '_createRemoteStore', {
                    fields      : [
                        {   name    : 'id'
                            ,type   : 'number'
                        }
                        ,'name'
                    ]
                    ,url        : route + 'getPCodes'
                } );
            return standards.callFunction( '_gridPanel', {
                id 					: 'grdPCode' + module
                ,module     		: module
                ,store      		: grdStore
				,height     		: 265
                ,noDefaultRow 		: true
                ,noPage             : true
                ,tbar               : {
                    content         : [
                        standards.callFunction( '_createCombo', {
                            store           : storePType
                            ,fieldLabel     : ''
                            ,width          : 90
                            ,id             : 'pTypeSel' + module
                            ,valueField     : 'id'
                            ,displayField   : 'name'
                            ,value          : 1
                            ,listeners      : {
                                select      : function(){
                                    var me  = this;
                                    grdStore.clearFilter( true );
                                    grdStore.proxy.extraParams.pType    = me.getValue();
                                    grdStore.load( {} );
                                }
                            }
                        } )
                        ,standards.callFunction( '_createTextField', {
                            id              : 'pCodeSrch' + module
                            ,fieldLabel     : ''
                            ,width          : 220
                            ,emptyText      : 'Search customer name...'
                            ,listeners      : {
                                change      : function(){
                                    if( this.value ){
                                        grdStore.clearFilter( true );
                                        grdStore.filter( {
                                            property        : 'name'
                                            ,value          : this.value
                                            ,anyMatch       : true
                                            ,caseSensitive  : false
                                        } );
                                    }
                                    else{
                                        grdStore.clearFilter();
                                    }
                                }
                            }
                        } )
                    ]
                }
                ,columns            : [
                    {   header      : 'Name'
                        ,dataIndex  : 'name'
                        ,flex       : 1
                        ,minWidth   : 120
                    }
                    ,standards.callFunction( '_createActionColumn', {
						icon        : 'ok'
						,tooltip    : 'Select record'
						,Func       : _selectPCode
                    } ) 
                ]
                ,listeners          : {
                    afterrender     : function(){
                        grdStore.proxy.extraParams.pType    = 1;
                        grdStore.load( {} );
                    }
                }
            } );
        }

        function _selectPCode( data ){
            Ext.getCmp( 'pType' + module ).setValue( Ext.getCmp( 'pTypeSel' + module ).getValue() );
            Ext.getCmp( 'pCode' + module ).setValue( data.id );
            Ext.getCmp( 'name' + module ).setValue( data.name );
            Ext.getCmp( 'pCodeSelectorWindow' + module ).destroy( true );
        }

        function _resetForm( form ){
            form.reset();
            Ext.getCmp( 'tdate' + module ).fireEvent( 'afterrender' );
            Ext.getCmp( 'gridJournalEntry' + module ).store.load({
                params  : {
                    idModule    : idModule
                }
            });
        }

        function _editRecord( data ){
            module.getForm().retrieveData( {
                url         : route + 'retrieveData'
                ,params     : data
                ,hasFormPDF : true
                ,success    : function( view , match ){
                    dataHolder = view;
                    Ext.getCmp( 'pType' + module ).setValue( view.pType );
                    Ext.getCmp( 'pCode' + module ).setValue( view.pCode );
                    Ext.getCmp( 'name' + module ).setValue( view.name );
                    Ext.getCmp( 'ttime' + module ).setValue( view.ttime );
                    Ext.getCmp( 'amount' + module ).setValue( view.amount );
                    Ext.getCmp( 'tdate' + module ).setMaxValue( new Date() );
                    Ext.getCmp( 'remarks' + module ).setValue( view.remarks );
                    Ext.getCmp( 'idInvoice' + module ).setValue( view.idInvoice );

                    var grdStore = Ext.getCmp( 'gridJournalEntry' + module ).getStore();

                    grdStore.load( {
                        params   : {
                            idInvoice    : view.idInvoice
                        }
                    });
                }
            } )
        }

        function _deleteRecord( data ){
			standards.callFunction('_createMessageBox',{
				msg		: 'DELETE_CONFIRM'
				,action	: 'confirm'
				,fn		: function( btn ){
					if ( btn == 'yes' ){
						Ext.Ajax.request({
							url		: route + 'archiveInvoice'
							,params	: { 
								idInvoice: parseInt(data.idInvoice)
								,idReference	: data.idReference
								,idmodule		: data.idModule
								,referenceNum	: data.referenceNum
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
                                        } );
                                        
                                        _resetForm( Ext.getCmp( 'mainFormPanel' + module ).getForm( ) );
                                        Ext.getCmp( 'gridHistory' + module ).store.load({});
                                        break;
                                }
							}
							,failure: function(){}
						})
					}
				}
			})
        }

        function _printPDF(){
			var par  = standards.callFunction('getFormDetailsAsObject',{ module : module })
            ,journalEntries = Ext.getCmp('gridJournalEntry'+module).store.data.items.map((item)=>item.data);

			Ext.Ajax.request({
                url			: route + 'generatePDF'
                ,method		:'post'
                ,params		: {
                    moduleID    	: idModule
                    ,title  	    : pageTitle
                    ,limit      	: 50
                    ,start      	: 0
					,printPDF   	: 1
					,form			: Ext.encode( par )
                    ,journalEntries     : Ext.encode( journalEntries )
					,hasPrintOption     : Ext.getCmp('printStatusJEgridJournalEntry' + module).getValue()
                    ,idInvoice		    : dataHolder.idInvoice
                    ,idAffiliate	    : dataHolder.idAffiliate
                }
                ,success	: function(response, action){
                    if( isGae == 1 ){
						window.open(route+'viewPDF/Beginning Balance','_blank');
					}else{
						window.open('pdf/accounting/Beginning Balance.pdf');
					}
                }
			});			
		}

        function _requireCostCenter( refTag ) {
			if ( refTag == 2 ) { 
				Ext.getCmp( 'idCostCenter' + module ).reset()
				Ext.getCmp( 'idCostCenter' + module ).allowBlank = true
				Ext.getCmp( 'idCostCenter' + module ).labelEl.update('Cost Center:');
			} else {
				Ext.getCmp( 'idCostCenter' + module ).reset()
				Ext.getCmp( 'idCostCenter' + module ).allowBlank = false
				Ext.getCmp( 'idCostCenter' + module ).labelEl.update('Cost Center' + Ext.getConstant('REQ') + ':');
			}
        }

        return {
            initMethod:function( config ){
                route		        = config.route;
                baseurl		        = config.baseurl;
                module		        = config.module;
                canPrint	        = config.canPrint;
                canDelete	        = config.canDelete;
                canEdit		        = config.canEdit;
                canCancel           = config.canCancel;
                pageTitle           = config.pageTitle;
                idModule	        = config.idmodule
                isGae		        = config.isGae;
                idAffiliate         = config.idAffiliate
                selRec              = config.selRec;
				componentCalling    = config.componentCalling;
                
                return _mainPanel( config );
            }
        }
    }
}