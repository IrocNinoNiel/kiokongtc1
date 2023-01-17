function Vouchersreceivable() {
    return function(){
        var baseurl, route, module, canDelete, pageTitle, idModule, isGae,isSaved = 0,deletedItems = [],selectedItem = [], idAffiliate, selRec, componentCalling
        ,canSave, fieldWidth = 406, fieldLabelWidth = 185, idAffiliate, canCancel;
        
        function _mainPanel( config ){
            //MAIN PANEL
            return standards2.callFunction(	'_mainPanelTransactions' ,{
                config		            : config
                ,module		            : module
                ,moduleType             : 'form'
                ,hasApproved            : false
                ,hasCancelTransaction   : canCancel
                ,listeners  : {
                    afterrender : function () {
                        if ( selRec ) {
                            _editRecord( selRec );
                        }
                    }
                }
                ,tbar                   : {
                    saveFunc    : _saveForm
                    ,resetFunc  : _resetForm
                    ,customListExcelHandler	: _printExcel
					,customListPDFHandler	: _customListPDF
					,formPDFHandler         : _printPDF
					,hasFormPDF     		: true
					,hasFormExcel			: false
                    ,filter     : {
                        searchURL       : route + 'searchHistoryGrid'
                        ,emptyText      : 'Search reference here...'
                        ,module         : module
                    }
                }
                ,formItems: [
                    {   xtype       : 'hidden'
                        ,id         : 'idInvoice' + module
                        ,value      : 0
                    }
                    ,standards2.callFunction( '_transactionHeader', {
                        module			        : module
                        ,containerWidth	        : 1000
                        ,idModule		        : idModule
                        ,idAffiliate	        : idAffiliate
                        ,config			        : config
                        ,conMainWidth           : 406
                        ,width                  : fieldWidth
                        ,labelWidth             : fieldLabelWidth
                        ,refCodeWidth           : 280
                        ,refNumWidth            : 121
                        ,dLabelWidth            : fieldLabelWidth
                        ,dWidth                 : 280
                        ,tWidth                 : 121
                        ,dSelectHandler         : _setDueDateValidation
                    } )
                    ,{  xtype       : 'fieldset'
                        ,layout     : 'column'
                        ,padding    : 10
                        ,items		: [
                            {
                                xtype			: 'container'
                                ,columnWidth	: .5
                                ,items			: _leftForm()
                            }
                            ,{
                                xtype			: 'container'
                                ,columnWidth	: .5
                                ,items			: _rightForm()
                            }
                        ]
                    }
                    ,{
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
                                        ,config         : config
                                        ,customer       : 'idCustomer'                                    } )
                                ]
                            }
                        ]
                    }  
                ]
                ,listItems: _gridHistory()
            } );
        }
    
        function _saveForm( form ){ 
            //SAVE JOURNAL ENTRY
            var grdStore    = Ext.getCmp( 'gridJournalEntry' + module ).getStore()
                ,gridJEData = grdStore.getRange()
                ,jeRecords  = new Array()
                ,totalCR    = 0
                ,totalDR    = 0;

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
                ,url		: route + 'save_VouchersReceivable'
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
                                            ,idAffiliate    : idAffiliate
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

        function _resetForm( form ){
            form.reset();
            Ext.getCmp( 'gridJournalEntry' + module ).store.load({
                params  : {
                    idModule    : idModule
                }
            });
        }

        function _leftForm() {
            let customerStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name' , 'paymentMethod']
                ,url        : route + 'getCustomers'
                ,startAt    :  0
                ,autoLoad   : true
            })
            
            let invOfCustStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name', 'amount' ]
                ,url        : route + 'getCustomers_inv'
                ,startAt    :  0
                ,autoLoad   : true
            })

            var termsStore = standards.callFunction( '_createLocalStore', {
				data        : [ '30 Days' , '60 Days' , '90 Days' ]
			});

            return [
                // SUPPLIER COMBOBOX - id:'idSupplier'+module
                standards.callFunction( '_createCombo', {
                    id              : 'idCustomer' + module
                    ,fieldLabel     : 'Customer'
                    ,allowBlank     : false
                    ,store          : customerStore
                    ,displayField   : 'name'
                    ,valueField     : 'id'
                    ,width          : fieldWidth
                    ,labelWidth     : fieldLabelWidth
                    ,listeners      : {
                        select     : function( me , record ){
                            var pm = parseInt( record[0].data.paymentMethod );

                            Ext.getCmp( 'idInvoicesOfCustomer' + module ).store.proxy.extraParams.idCustomer = me.getValue();
                            Ext.getCmp( 'idInvoicesOfCustomer' + module ).store.load( {
                                callback   : function() {
                                    Ext.getCmp( 'idInvoicesOfCustomer' + module ).setValue( 0 );
                                    Ext.getCmp( 'amount' + module ).setValue( 0 );
                                }
                            } )

                            Ext.getCmp( 'paymentMethod' + module ).store.load({
                                callback: function (){
                                    Ext.getCmp( 'paymentMethod' + module ).setValue( parseInt( pm ) );
                                    Ext.getCmp( 'paymentMethod' + module ).fireEvent( 'select' );
                                }
                            });

                            _setDueDateValidation();
                        }
                    }
                } )

                ,standards.callFunction( '_createCheckField', {
                    id              : 'isNegative' + module
                    ,fieldLabel     : 'Check if Negative Adjustment'
                    ,width          : fieldWidth
                    ,labelWidth     : fieldLabelWidth
                    ,listeners      : {
                        change  : function( me ) {
                            if ( me.getValue() ) {
                                Ext.getCmp( 'idInvoicesOfCustomer'+module ).setDisabled( false )
                            } else {
                                Ext.getCmp( 'idInvoicesOfCustomer'+module ).setDisabled( true )
                            }
                        }
                    }
                })
                
                ,standards.callFunction( '_createCombo', {
                    id              : 'idInvoicesOfCustomer' + module
                    ,fieldLabel     : 'Invoice'
                    ,allowBlank     : false
                    ,displayField   : 'name'
                    ,valueField     : 'id'
                    ,width          : fieldWidth
                    ,labelWidth     : fieldLabelWidth
                    ,store          : invOfCustStore
                    ,disabled       : true 
                    ,listeners      : {
                        select: function( me, row ){
                            Ext.getCmp( 'amount'+module ).setValue( row[0].data.amount )
                        }
                    }
                } )

                // AMOUNT TYPE COMBOBOX id:'amount'+module
                ,standards.callFunction( '_createTextField', {
                    id              : 'amount' + module
                    ,fieldLabel     : 'Amount'
                    ,width          : fieldWidth
                    ,labelWidth     : fieldLabelWidth
                    ,allowBlank     : false
                    ,isNumber       : true
                    ,isDecimal      : true
                } )
            ]
        }

        function _rightForm() {

            var paymentMethodStore = standards.callFunction( '_createLocalStore', {
				data        : [ 'Cash', 'Charge' ]
            });
            

            return [
                // PAYMENT TYPE COMBOBOX id:'paymentMethod'+module
                standards.callFunction( '_createCombo', {
                    id              : 'paymentMethod' + module
                    ,fieldLabel     : 'Payment Method'
                    ,store          : paymentMethodStore
                    ,displayField   : 'name'
                    ,valueField     : 'id'
                    ,allowBlank     : false
                    ,value          : 0
                    ,width          : fieldWidth
                    ,labelWidth     : fieldLabelWidth
                    ,listeners		: {
                        select : function( me, result, record, noDuplicate ){
                            var enableTerm = Ext.getCmp( 'terms' + module );
                            if ( parseInt(this.value) == 2 ){
                                enableTerm.setDisabled(false);
                                enableTerm.allowBlank = false;
                                enableTerm.validate();
                            } else {
                                enableTerm.setDisabled(true);
                                enableTerm.allowBlank = true;
                                enableTerm.setValue( null );
                                enableTerm.validate();
                            }

                            _setDueDateValidation();
                        }
                    }
                } )

                // TERMS COMBOBOX id:'terms'+module
                ,standards.callFunction( '_createTextField', {
                    id              : 'terms' + module
                    ,fieldLabel     : 'Terms'
                    ,allowBlank     : true
                    ,isNumber       : true
                    ,isDecimal      : false
                    ,value          : 0
                    ,width          : fieldWidth
                    ,labelWidth     : fieldLabelWidth
                    ,listeners		: {
                        change : function(){
                            _setDueDateValidation();
                        }
                    }
                } )

                // AS OF DATE COMBOBOX id:'duedate'+module
                ,standards.callFunction( '_createDateField', {
                    id              : 'duedate' + module
                    ,fieldLabel     : 'Due Date'
                    ,allowBlank     : true
                    ,width          : fieldWidth
                    ,labelWidth     : fieldLabelWidth
                })

                // AS OF DATE COMBOBOX id:'remarks'+module
                ,standards.callFunction( '_createTextField', {
                    id              : 'remarks' + module
                    ,fieldLabel     : 'Remarks'
                    ,width          : fieldWidth
                    ,labelWidth     : fieldLabelWidth
                    ,allowBlank     : true
                })
            ]
        }

        function _setDueDateValidation() {
            terms = Ext.getCmp( 'terms' + module );
            tdate = Ext.getCmp( 'tdate' + module );
            tdate.setMaxValue( new Date() );
            if( terms ) {
                dueDate = Ext.Date.add( tdate.value , Ext.Date.DAY , terms.value );
                Ext.getCmp( 'duedate' + module ).setMinValue( dueDate );
                Ext.getCmp( 'duedate' + module ).setMaxValue( dueDate );
                Ext.getCmp( 'duedate' + module ).setValue( dueDate );
            } else {
                Ext.getCmp( 'duedate' + module ).setMinValue( tdate.value )
                Ext.getCmp( 'duedate' + module ).setMaxValue( tdate.value )
                Ext.getCmp( 'duedate' + module ).setValue( tdate.value );
            }
        }

        function _gridHistory() {
            var store   = standards.callFunction( '_createRemoteStore', {
                fields      : [
                    'idInvoice'
                    ,'date'
                    ,'reference'
                    ,'customerName'
                    ,{ name: 'amount'  ,type: 'number' }
                    ,'idReference'
                    ,'referenceNum'
                    ,'idModule'
                ]
                ,url        : route + 'getVouchersReceivable'
            } );

            return standards.callFunction('_gridPanel', {
                id 					: 'gridHistory' + module
                ,module     		: module
                ,store      		: store
				,height     		: 265
				,noDefaultRow 		: true
                ,columns            : [
                    {  header          : 'Date'
                        ,dataIndex      : 'date'
                        ,width          : 100
                        ,columnWidth    : 20
                        ,xtype          : 'datecolumn'
                        ,format         : 'm/d/Y h:i A'
                    }
                    ,{   header          : 'Reference'
                        ,dataIndex      : 'reference'
                        ,width          : 100
                        ,columnWidth    : 20
                    }
                    ,{  header          : 'Customer Name'
                        ,dataIndex      : 'customerName'
                        ,flex           : 1
                        ,minWidth       : 150
                        ,columnWidth    : 40
                    }
                    ,{  header          : 'Amount'
                        ,dataIndex      : 'amount'
                        ,width          : 155
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,columnWidth    : 20
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
            } )
        }

        function _editRecord( data ){
            module.getForm().retrieveData( {
                url         : route + 'retrieveData'
                ,params     : data
                ,hasFormPDF : true
                ,success    : function( view , match ){
                    dataHolder = view;

                    if ( parseInt( view.fident ) > 0 ) {
                        Ext.getCmp( 'isNegative'+module ).setValue( true ); 
                        Ext.getCmp( 'idInvoicesOfCustomer'+module ).setDisabled( false );
                        Ext.getCmp( 'idInvoicesOfCustomer'+module ).store.proxy.extraParams.idCustomer = view.pCode;
                        Ext.getCmp( 'idInvoicesOfCustomer' + module ).store.load( {
                            callback   : function() {
                                Ext.getCmp( 'idInvoicesOfCustomer' + module ).setValue( parseInt( view.fident ) );
                            }
                        } )
                    }

                    Ext.getCmp( 'idCustomer' + module ).store.load({
                        callback: function (){
                            Ext.getCmp( 'idCustomer' + module ).setValue( parseInt( view.pCode ) );
                        }
                    });

                    Ext.getCmp( 'paymentMethod' + module ).store.load({
                        callback: function (){
                            Ext.getCmp( 'paymentMethod' + module ).setValue( parseInt( view.payMode ) );
                            Ext.getCmp( 'paymentMethod' + module ).fireEvent( 'select' );
                        }
                    });

                    Ext.getCmp( 'ttime' + module ).setValue( view.ttime );
                    Ext.getCmp( 'amount' + module ).setValue( view.amount );

                    Ext.getCmp( 'tdate' + module ).setMaxValue( new Date() );
                    
                    Ext.getCmp( 'duedate' + module ).setMinValue( view.tdate );
                    Ext.getCmp( 'duedate' + module ).setMaxValue( view.dueDate );
                    Ext.getCmp( 'duedate' + module ).setValue( view.dueDate );

                    Ext.getCmp( 'remarks' + module ).setValue( view.remarks );
                    Ext.getCmp( 'idInvoice' + module ).setValue( view.idInvoice );

                    var grdStore = Ext.getCmp( 'gridJournalEntry' + module ).getStore();
                    grdStore.load( {
                        params   : {
                            idInvoice    : view.idInvoice
                        }
                    } );
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

        
		function _customListPDF() {
			Ext.Ajax.request({
				url  		: route + 'customListPDF'
				,params 	: { 
					items : Ext.encode( Ext.getCmp('gridHistory'+module).store.data.items.map((item)=>item.data) )
				}
				,success 	: function(response){
					if( isGae == 1 ){
						window.open(route+'viewPDF/Vouchers Receivable List','_blank');
					}else{
						window.open('pdf/accounting/Vouchers Receivable List.pdf');
					}
				}
			});
		}

		function _printExcel(){
            Ext.Ajax.request({
                url: route + 'printExcel'
                ,params: {
                    idmodule    : idModule
                    ,pageTitle  : pageTitle
                    ,limit      : 50
                    ,start      : 0
                }
                ,success: function(res){
                    var path  = route.replace( baseurl, '' );
                    window.open(baseurl + path + 'download' + '/' + pageTitle);
                }
            });
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
						window.open(route+'viewPDF/Vouchers Receivable','_blank');
					}else{
						window.open('pdf/accounting/Vouchers Receivable.pdf');
					}
                }
			});			
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