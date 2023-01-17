/**
 * Developer: Niño Niel B. Iroc
 * Module: Cash Advance
 * Date: Jan 10, 2023
 * Finished:
 * Description: 
 * DB Tables:
 * */

function Cashadvance(){ 

    return function () { 

        var idAffiliate, baseurl, route, module, canDelete, canPrint, pageTitle, isGae, canEdit, idModule, onEdit = 0, totalApprovedAmnt = 0,
        idInvoice = '', idAccomplishment  = '', boqData = {}, boqDataHolder = [], dataHolder = {};

        function _mainPanel(config) {
            return standards2.callFunction('_mainPanelTransactions', {
                 config      : config
                ,module      : module
                ,moduleType  : 'form'
                ,hasApproved : false
                ,tbar        : {
                     saveFunc               : _saveForm
                    ,resetFunc              : _resetForm
                    ,customListExcelHandler	: _printExcel
					,customListPDFHandler   : _printPDF
                    ,formPDFHandler         : _printPDFForm
					,hasFormPDF     		: true
					,hasFormExcel			: false
                    ,filter                 : {
                         searchURL  : route + 'viewHistorySearch'
                        ,emptyText  : 'Search reference here...'
                        ,module     : module
                    }
                },
                formItems   : [
                     _transactionForm( config )
                    ,_cashAdvnceForm( config )
                    ,_transactionGrid( config )
                ],
                listItems   : _gridHistory()
            });
        }

        function _transactionForm( config ) {
            return standards2.callFunction( '_transactionHeader', {
                module			: module
                ,containerWidth	: 1000
                ,idModule		: idModule
                ,idAffiliate	: idAffiliate
                ,config			: config

            });
        }

        function _cashAdvnceForm(){

            typeStore = standards.callFunction( '_createLocalStore' , {
                data        : [ 'Construction', 'Trucking' ,'Employee' ]
                ,startAt    : 1
            });

            let projectStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      : [ { name : 'id', type : 'number' }, 'name' ]
                ,url        : route + 'getProjectNames'
                ,autoLoad   : false
            });

            let payrollDeductionStore = standards.callFunction( '_createLocalStore' , {
                data        : [ 'Weekly', 'Quincina' ,'Monthly' ]
                ,startAt    : 1
            });

            let employeeStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name']
                ,url        : route + 'getDrivers'
                ,startAt    :  0
                ,autoLoad   : true
            });

            return {
                xtype       : 'fieldset'
                ,layout     : 'column'
                ,padding    : 10
                ,items      : [
                    {
                        xtype           : 'container'
                        ,columnWidth    : .5
                        ,items          : [
                            standards.callFunction( '_createCombo', {
                                id              : 'typeCombo' + module
                                ,store          : typeStore
                                ,editable       : false
                                ,value          : 1
                                ,fieldLabel     : 'Type'
                                ,listeners      : {
                                    select  : function( me, record ){
                                        var selectedValue = me.getValue();

                                        if(selectedValue == 1) {
                                            hideAllCashAdvanceForm();

                                            Ext.getCmp( 'idProject' + module ).show();
                                            Ext.getCmp( 'idInterestRate' + module ).show();
                                            Ext.getCmp( 'idParticulars' + module ).show();
                                            Ext.getCmp( 'idAmount' + module ).show();


                                        }else if(selectedValue == 2) {

                                            hideAllCashAdvanceForm();

                                            Ext.getCmp( 'idStriker' + module ).show();
                                            Ext.getCmp( 'idInterestRate' + module ).show();
                                            Ext.getCmp( 'idParticulars' + module ).show();
                                            Ext.getCmp( 'idAmount' + module ).show();
                                            Ext.getCmp( 'idReferenceNum' + module ).show();
                                            
                                        }else {

                                            hideAllCashAdvanceForm();

                                            // dateRangeContainerundefined
                                            Ext.getCmp('dateRangeContainerundefined').show();
                                            Ext.getCmp( 'idEmployeeCombo' + module ).show();
                                            Ext.getCmp( 'idInterestRate' + module ).show();
                                            Ext.getCmp( 'idParticulars' + module ).show();
                                            Ext.getCmp( 'idAmount' + module ).show();
                                            Ext.getCmp( 'idPayrollDeductionCombo' + module ).show();
                                            Ext.getCmp( 'idSdate' + module ).show();
                                            Ext.getCmp( 'idEdate' + module ).show();
                                            Ext.getCmp( 'idDeduction' + module).show();

                                        }
                                    }
                                }
                            } )

                            ,standards.callFunction( '_createCombo', {
                                id              : 'idProject' + module
                                ,fieldLabel     : 'Project Name'
                                ,store          : projectStore
                                ,allowBlank     : true
                                ,displayField   : 'name'
                                ,valueField     : 'id'
                                ,listeners      : {
                                    beforeQuery :  function() {
                                        
                                    }
                                }
                            } )

                            , standards.callFunction( '_createCombo', {
                                id              : 'idEmployeeCombo' + module
                                ,store          : employeeStore
                                ,editable       : false
                                ,hidden         : true
                                ,value          : 1
                                ,fieldLabel     : 'Employee'
                                ,listeners      : {
                                    select  : function( me, record ){
                                        console.log(me);
                                    }
                                }
                            } )

                            ,standards.callFunction( '_createTextField', {
                                id              : 'idInterestRate' + module
                                ,fieldLabel     : 'Interest Rate'
                                ,allowBlank     : true
                                ,isNumber       : true
                            } )

                            ,standards.callFunction( '_createTextField', {
                                id              : 'idReferenceNum' + module
                                ,fieldLabel     : 'Reference #'
                                ,allowBlank     : true
                                ,hidden         : true
                            } )

                            ,standards.callFunction( '_createCheckField', {
                                boxLabel        : '<span style="color:red;font-style:italic;"><strong>(Check if Striker)</strong></span>'
                                ,id             : 'idStriker' + module
                                ,listeners      : {
                                    change      : function(){
                                       
                                    }
                                }
                                ,hidden         : true
                            } )

                            , standards.callFunction( '_createCombo', {
                                id              : 'idPayrollDeductionCombo' + module
                                ,store          : payrollDeductionStore
                                ,editable       : false
                                ,value          : 1
                                ,fieldLabel     : 'Payroll Deduction'
                                ,listeners      : {
                                    select  : function( me, record ){
                                        console.log(me);
                                    }
                                }
                                ,hidden         : true
                            } )
                          
                        ]
                    }
                    ,{
                        xtype           : 'container'
                        ,columnWidth    : .5
                        ,items          : [
                            standards.callFunction( '_createTextField', {
                                id              : 'idDeduction' + module
                                ,fieldLabel     : 'Deduction Amount'
                                ,allowBlank     : true
                                ,isNumber       : true
                                ,hidden         : true
                            } )
                            ,standards.callFunction( '_createTextField', {
                                id              : 'idParticulars' + module
                                ,fieldLabel     : 'Particulars'
                                ,allowBlank     : true
                            } )
                            ,standards.callFunction( '_createTextField', {
                                id              : 'idAmount' + module
                                ,fieldLabel     : 'Amount'
                                ,allowBlank     : true
                                ,isNumber       : true
                            } )

                            ,standards.callFunction( '_createDateRange',{
                                sdateID			: 'idSdate' + module
                                ,edateID		: 'idEdate' + module
                                ,noTime			: true
                                ,fromFieldLabel	: 'Date Range'
                                ,hidden         : true
                            })
                        ]
                    }
                ]
            }
        }

        function hideAllCashAdvanceForm() {
            Ext.getCmp( 'idProject' + module ).hide();
            Ext.getCmp( 'idInterestRate' + module ).hide();
            Ext.getCmp( 'idReferenceNum' + module ).hide();
            Ext.getCmp( 'idStriker' + module ).hide();
            Ext.getCmp( 'idEmployeeCombo' + module ).hide();
            Ext.getCmp( 'idPayrollDeductionCombo' + module ).hide();
            Ext.getCmp( 'idParticulars' + module ).hide();
            Ext.getCmp( 'idAmount' + module ).hide();
            Ext.getCmp( 'idSdate' + module ).hide();
            Ext.getCmp( 'idEdate' + module ).hide();
            Ext.getCmp( 'idDeduction' + module).hide();
        }

        function _transactionGrid( config ) {

            var cashAdvanceItems = standards.callFunction( '_createRemoteStore', {
				fields		: [ 
                    'referenceNum' 
                    , 'barcode'
                    , 'itemName'
                    , 'className'
                    , 'unitName'
                    , 'cost'
                    , 'qty'
                    , 'qtyLeft'
                    , 'idItemClass'
                    , 'expiryDate'
                    , { name: 'amount', type: 'number' }
                    , { name: 'idItem', type: 'number'}
                    , { name: 'lotNumber', type: 'number'}
                    ]
				,url		: route + 'getPOItems'
				,autoLoad	: true
            } )
            ,paymentStore = standards.callFunction( '_createLocalStore', {
				data        : ['Asset', 'Supplies' ]
				,startAt    : 0
            } )
            ,poNumberStore = standards.callFunction( '_createRemoteStore', {
				fields		: [ 'idInvoice', 'name' ]
				,url		: route + 'getPONumber'
				,autoLoad	: true
            } )
            ,itemStore = standards.callFunction( '_createRemoteStore', {
				fields		: [ 
                     'date'
                    , 'particulars'
                    , 'orNumber'
                    , 'amount'
                ]
				,url		: route + 'getItems'
				,autoLoad	: true
            } );


            return {
                xtype   : 'tabpanel'
                ,items  : [
                    {
                        title   : 'Liquidation'
                        ,layout : { type: 'card' }
                        ,items  : [
                            standards.callFunction( '_gridPanel', {
                                id          : 'grdItems' + module
                                ,module     : module
                                ,store      : cashAdvanceItems
                                ,plugins    : true
                                ,noPage     : true
                                ,noDefaultRow  : true
                                ,features       : {
                                    ftype   : 'summary'
                                }
                                ,tbar       : {
                                    canPrint        : false
                                    ,noExcel        : true
                                    ,route          : route
                                    ,pageTitle      : pageTitle
                                    ,content        : 'add'
                                    // ,deleteRowFunc  : _deleteItem
                                }
                                ,columns    : [
                                    {   header          : 'Date'
                                        ,dataIndex      : 'date'
                                        ,width          : 100
                                        ,columnWidth    : 10
                                        ,sortable       : false
                                        ,draggable      : false
                                        ,menuDisabled   : true
                                    }
                                    ,{  header          : 'OR Number'
                                        ,dataIndex      : 'orNumber'
                                        ,width          : 100
                                        ,columnWidth    : 10
                                        ,sortable       : false
                                        ,draggable      : false
                                        ,menuDisabled   : true
                                    }
                                    
                                    ,{  header          : 'Particulars'
                                        ,dataIndex      : 'particulars'
                                        ,minWidth       : 200
                                        ,flex           : 1
                                        ,columnWidth    : 25
                                        ,sortable       : false
                                        ,draggable      : false
                                        ,menuDisabled   : true
                                    }
                                    ,{	header          : 'Amount'
                                        ,dataIndex      : 'amount'
                                        ,width          : 100
                                        ,columnWidth    : 10
                                        ,xtype          : 'numbercolumn'
                                        ,summaryType    : 'sum'
                                        ,sortable       : false
                                        ,summaryRenderer: function( value, summaryData, dataIndex ) {
                                           
                                        }
                                    }
                                ]
                                ,listeners	: {
                                    afterrender : function() {
                                        cashAdvanceItems.load({});
                                    }
                                    ,edit : function( me, rowData ) {
                                        var index = rowData.rowIdx
                                        ,store = this.getStore().getRange();
    
                                        var totalAmount = ( store[index].data.amount != null ) ? store[index].data.amount : 0 ;

                                        switch( rowData.field ) {
                                            case 'cost':
                                                if( rowData.value == 0 ) {
                                                    standards.callFunction('_createMessageBox', { 
                                                        msg : 'Please input a value greater than 0.'
                                                        ,fn: function(){ 
                                                            let costVal = ( rowData.originalValue == 0 ) ? 1 : rowData.originalValue;
                                                            store[index].set('cost', costVal );
                                                        }
                                                    });
                                                }
                                                _computation();
                                                totalAmount = ( store[index].data.qty > 0 ? rowData.value * store[index].data.qty : rowData.value);
                                                break;
                                            case 'qty':
                                                    /* Does not allow 0 input for Qty */

                                                    // if( rowData.value == 0 ){
                                                    //     standards.callFunction('_createMessageBox', { 
                                                    //         msg :'Please input a value greater than 0.'
                                                    //         ,fn: function(){ 
                                                    //             let qtyVal = ( rowData.originalValue == 0 ) ? 1 : rowData.originalValue;
                                                    //             store[index].set('qty', qtyVal );
                                                    //         }
                                                    //     });
                                                    // }
                                                _computation();
                                                totalAmount = store[index].data.cost * rowData.value;
                                                break;
                                        }
    
                                        store[index].set('amount', totalAmount );
                                    }
                                }
                            } )
                            ,_totalFields()
                        ]
                    }
                    ,{
                        title   : 'Journal Entries'
                        ,layout : { type: 'card' }
                        ,items  :   [
                            standards.callFunction( '_gridJournalEntry',{
                                module	        : module
                                ,hasPrintOption : 1
                                ,config         : config 
                                ,items          : Ext.getCmp('grdItems' + module)
                                ,supplier       : 'pCode'
                            })
                        ]
                    }
                ]
            }
        }

        function _totalFields( ) {
            return {	
                xtype   : 'container'
                ,style  : 'float:right;padding:8px;margin-top:5px;margin-right:5px;'
                ,width  : 390
                ,items  : [
                    standards.callFunction('_createNumberField',{
                        id              : 'totalAmount' + module
                        ,fieldLabel     : 'Total Amount'
                        ,readOnly       : true
                        ,listeners      : {
                            change : function( me ){
                                
                            }
                        }
                    })
                    ,standards.callFunction('_createNumberField',{
                        id          : 'totalAmountDisbursed' + module
                        ,fieldLabel : 'Total Amount Disbursed:'
                        ,readOnly   : true
                        ,listeners      : {
                            change : function( me ){
                               
                            }
                        }
                    })
                    ,standards.callFunction('_createNumberField',{
                        id          : 'unusedCash' + module
                        ,fieldLabel : 'Unused Cash:'
                        ,readOnly   : true
                        ,listeners      : {
                            change : function( me ){
                                
                            }
                        }
                    })
                    ,standards.callFunction('_createNumberField',{
                        id          : 'totalShortOver' + module
                        ,fieldLabel : 'Total Short/Over:'
                        ,readOnly   : true
                        ,listeners  : {
                            change : function( me ) {
                                _computation();
                            }
                        }
                    })
                ]
            };
            
        }

        function _saveForm( form ){
            let typeInput = Ext.getCmp( 'typeCombo'+ module ).getValue();

            let project             = Ext.getCmp( 'idProject' + module ).getValue();
            let interestRate        = Ext.getCmp( 'idInterestRate' + module ).getValue();
            let referenceNum        = Ext.getCmp( 'idReferenceNum' + module ).getValue();
            let striker             = Ext.getCmp( 'idStriker' + module ).getValue();
            let employee            = Ext.getCmp( 'idEmployeeCombo' + module ).getValue();
            let payroll             = Ext.getCmp( 'idPayrollDeductionCombo' + module ).getValue();
            let particulars         = Ext.getCmp( 'idParticulars' + module ).getValue();
            let amount              = Ext.getCmp( 'idAmount' + module ).getValue();
            let sdate               = Ext.getCmp( 'idSdate' + module ).getValue();
            let edate               = Ext.getCmp( 'idEdate' + module ).getValue();
            let affiliateID         = idAffiliate;
            let costCenterID        = Ext.getCmp('idCostCenter' + module).getValue();
            let date                = new Date();
            let referenceSeriesID   = Ext.getCmp('idReferenceSeries' + module).getValue();
            let referenceID         = Ext.getCmp('idReference' + module).getValue();
            let params              = {};
            let invoices            = Ext.encode ({
                idAffiliate         : idAffiliate
               ,idCostCenter        : Ext.getCmp('idCostCenter' + module).getValue()
               ,idReference         : Ext.getCmp('idReference' + module).getValue()
               ,idReferenceSeries   : Ext.getCmp('idReferenceSeries' + module).getValue()
               ,date			    : Ext.getCmp('tdate' + module).getValue()
               ,time			    : Ext.Date.format(Ext.getCmp( 'ttime' + module).getValue(), 'h:i:s A')
               ,referenceNum        : Ext.getCmp('referenceNum' + module).getValue()
               ,cancelTag           : 0
               ,dateModified        : new Date()
               ,hasJournal          : 0
               ,archived            : 0
               ,cancelledBy         : 0
               ,idModule            : idModule
               ,idInvoice           : idInvoice
           });
           let liquidation      = Ext.encode ({
                date            : new Date()
                ,particular     : particulars
                ,amount         : amount
           });

            if(typeInput == 1) {

                if(project == null || interestRate == 0 || particulars == '' || amount == 0 ) {
                    standards.callFunction('_createMessageBox',{ msg: 'FORM_INVALID_INPUT' })
                }

                params = {
                    type                : 'construction'
                    ,project            : project
                    ,interestRate       : interestRate
                    ,particulars        : particulars
                    ,amount             : amount
                    ,invoices           : invoices
                    ,liquidation        : liquidation
                    ,affiliateID        : affiliateID
                    ,costCenterID       : costCenterID
                    ,date               : date
                    ,referenceSeriesID  : referenceSeriesID
                    ,referenceID        : referenceID
                }

                
            } else if(typeInput == 2) {

                if(referenceNum == '' || interestRate == 0 || particulars == '' || amount == 0 ) {
                    standards.callFunction('_createMessageBox',{ msg: 'FORM_INVALID_INPUT' })
                }

                params = {
                    type                : 'trucking'
                    ,interestRate       : interestRate
                    ,referenceID        : referenceNum
                    ,particulars        : particulars
                    ,amount             : amount
                    ,striker            : striker
                    ,invoices           : invoices
                    ,liquidation        : liquidation
                    ,affiliateID        : affiliateID
                    ,costCenterID       : costCenterID
                    ,date               : date
                    ,referenceSeriesID  : referenceSeriesID
                    ,referenceID        : referenceID
                }

            } else {

                if(payroll == null ||employee == null || interestRate == 0 || particulars == '' || amount == 0 ) {
                    standards.callFunction('_createMessageBox',{ msg: 'FORM_INVALID_INPUT' })
                }

                params = {
                    type                : 'employee'
                    ,interestRate       : interestRate
                    ,employeeID         : employee
                    ,particulars        : particulars
                    ,amount             : amount
                    ,payrollDeduction   : payroll
                    ,dateTo             : sdate
                    ,dateFrom           : edate
                    ,invoices           : invoices
                    ,liquidation        : liquidation
                    ,affiliateID        : affiliateID
                    ,costCenterID       : costCenterID
                    ,date               : date
                    ,referenceSeriesID  : referenceSeriesID
                    ,referenceID        : referenceID
                }

            }


            _submitForm( params, form );

        }

        function _submitForm( params, form ){
            form.submit({
				url 		: route + 'saveCashAdvance'
				,params 	: params
				,success 	: function( action, response ){
					var resp    = Ext.decode( response.response.responseText )
						,match  = parseInt( resp.match, 10 );
						
                    switch( match ){
                        case 1: /* check if reference already exists */
                            standards.callFunction( '_createMessageBox', {
                                msg     : 'Server Error.'
                                ,fn     : function(){}
                            } );
                            break;
                        case 0: /* saving successful */
                            standards.callFunction( '_createMessageBox', {
                                msg     : 'SAVE_SUCCESS'
                                ,fn     : function(){
                                    _resetForm( form );
                                }
                            } )
                            break;
                    }
				}
			});
        }

        function _resetForm( form ){
            form.reset();
        }

        function _gridHistory() {

			var cashAdvanceItems = standards.callFunction( '_createRemoteStore', {
				fields		: [ 
					'referenceNumber', 
					'date', 
					'affiliateName', 
					'costCenterName',
					'supplierName', //name
					'notedBy', 
					'status', 
					'amount', 
                    'receivedBy',
                    'approvedBy',
					'id', //idInvoice
					'name' //referenceNum
				]
				,url		: route + 'viewAll'
				,autoLoad	: true
            } )

			function _deleteRecord( data ) {
				standards.callFunction( '_createMessageBox', {
					msg		: 'DELETE_CONFIRM'
					,action	: 'confirm'
					,fn		: function( btn ){
						if( btn == 'yes' ){
							Ext.Ajax.request({
								url 	: route + 'deleteRecord'
								,params : { idInvoice: data.id }
								,success : function( response ){
									var resp = Ext.decode( response.responseText );
									if( resp.match == 1 ) {
										standards.callFunction( '_createMessageBox', {
											msg : 'DELETE_USED'
										} );
									} 

									cashAdvanceItems.load({});
								}
							});
						}
					}
				} );
			}

			return standards.callFunction('_gridPanel', {
                id 					: 'gridHistory' + module
                ,module     		: module
                ,store      		: cashAdvanceItems
				,height     		: 265
				,noDefaultRow 		: true
                ,columns: [
					{	header          : 'Date'
                        ,dataIndex      : 'date'
                        ,width          : 80
                        ,sortable       : false
                        ,xtype          : 'datecolumn'
                        ,format         : Ext.getConstant('DATE_FORMAT')
                        ,columnWidth    : 14
                    }
                    ,{	header          : 'Reference'
                        ,dataIndex      : 'referenceNum'
                        ,width          : 130
                        ,sortable       : false
                        ,columnWidth    : 14
                    }
                    ,{	header          : 'Type'
                        ,dataIndex      : 'type'
                        ,width          : 80
                        ,sortable       : false
                        ,columnWidth    : 14
                    }
					,{	header          : 'Project Name'
                        ,dataIndex      : 'projectName'
                        ,flex           : 1
                        ,minWidth       : 150
                        ,sortable       : false
                        ,columnWidth    : 16
                    }
					,{	header          : 'Employee Name'
                        ,dataIndex      : 'employeeName'
                        ,flex           : 1
                        ,minWidth       : 80
                        ,sortable       : false
                        ,columnWidth    : 14
					}
					,{	header          : 'Payroll Deduction'
                        ,dataIndex      : 'payrollDeduction'
                        ,flex           : 1
                        ,minWidth       : 80
                        ,sortable       : false
                        ,columnWidth    : 14
                    }
					,{	header          : 'Total Amount'
                        ,dataIndex      : 'totalAmount'
						,width          : 100
						,xtype		    : 'numbercolumn'
                        ,sortable       : false
                        ,columnWidth    : 14
						,renderer	    : function (val){
							return Number(val).toLocaleString('en-GB',{minimumFractionDigits: 2, maximumFractionDigits: 2})
						}
					}
					,standards.callFunction( '_createActionColumn', {
                        canEdit     : canEdit
                        ,icon       : 'pencil'
						,tooltip    : 'Edit'
                        ,width      : 30
                        ,Func       : _editRecord
                    })
                    ,standards.callFunction( '_createActionColumn', {
                        canDelete     : canDelete
                        ,icon       : 'remove'
						,tooltip    : 'Delete'
						,width      : 30
                        ,Func       : _deleteRecord
                    })
				]
				,listeners: {
                    afterrender: function(){
                        cashAdvanceItems.proxy.extraParams.idAffiliate = idAffiliate;
                        cashAdvanceItems.load({});
                    }
                }
			});
        }

        function _editRecord(){
            
        }

        function _printPDFForm() {

        }

        function _printExcel() {

        }

        function _printPDF() {

        }

        return {
            initMethod: function (config) {
                route       = config.route;
                module      = config.module;
                canDelete   = config.canDelete;
                canPrint    = config.canPrint;
                pageTitle   = config.pageTitle;
                isGae       = config.isGae;
                canEdit     = config.canEdit;
                idModule    = config.idmodule;
                baseurl     = config.baseurl;
                idAffiliate = config.idAffiliate

                return _mainPanel(config);
            }
        }

    }
};