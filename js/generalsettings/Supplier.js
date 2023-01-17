/**
 * Developer: Mark Reynor D. Magriña
 * Module: Supplier Settings
 * Date: Dec 03, 2019
 * Finished: 
 * Description: 
 * DB Tables: 
 * */ 
var Supplier = function(){
    return function(){
        var route, module, canDelete, canPrint, pageTitle, isGae, canEdit, idModule,onEdit = 0,invalidDate = 0,onEditContributionQuickSettings=0;
		var employmentChange = 0,idAffiliate;
		
        function _init(){
			_resetForm( );
        }
		
        function _mainPanel( config ){
			/* store area */
            var sm = new Ext.selection.CheckboxModel({ 
				checkOnly   : true   
				,listeners: {
					select: function( val, rec ){
						var record = rec.data;
						record.chk = 1;
						rec.set('chk',1);
					}
					,deselect: function( val, record ){
						record.set( 'chk', 0 );
					}
				}
			})
			var affiliateStore = standards.callFunction( '_createRemoteStore', {
				fields      : [ 'idAffiliate', 'affiliateName',{name:'chk',type:'bool'} ]			
				,url        : route + 'getAffiliate'
			} )
			var paymentMethodStore = standards.callFunction( '_createLocalStore', {
				data        : [ 'Cash', 'Charge' ]
			} );
            var termsStore = standards.callFunction( '_createLocalStore', {
				data        : [ '30 Days', '60 Days','90 Days','120 Days' ]
				,startAt    : 0
			} );
            var vatTypeStore = standards.callFunction( '_createLocalStore', {
				data        : [ 'Inclusive', 'Exclusive' ]
				,startAt    : 1
			} );
			var coaStore = standards.callFunction( '_createRemoteStore', {
				fields  : [ {name:'idCoa',type:'number'}, 'acod_c15', 'aname_c30' ]
				,url    : route + 'getCOADetails'
			} )
            return standards.callFunction( '_mainPanel', {
                config      : config
                ,moduleType : 'form'
				,id			: 'mainFormID' + module
                ,tbar       : {
                    listLabel       : 'List'
                    ,saveFunc       : _saveForm
                    ,resetFunc      : _resetForm
                    ,noPDFButton    : true
                    ,noExcelButton  : true
                    ,filter         : {
                        searchURL       : route + 'getSupplierList'
						,emptyText      : 'Search here...'
						,module         : module
                    }
                }
                ,formItems  : [
                    {   xtype   : 'container'
                        ,layout : 'column'
                        ,items  : [
                            {   xtype           : 'container'
                                ,columnWidth    : .4
                                ,items          : [									
                                    standards.callFunction( '_createTextField', {
                                        id          : 'idSupplier' + module
                                        ,fieldLabel : 'ID Number'
                                        ,allowBlank : true
										,value 		: 0
										,hidden		: true
                                        
                                    } )                                   
                                    ,standards.callFunction( '_createTextField', {
                                        id          : 'name' + module
                                        ,fieldLabel : 'Name'
                                        ,allowBlank : false
                                        ,maxLength  : 50
                                    } )
									,standards.callFunction( '_createTextField', {
                                        id          : 'email'  + module
                                        ,fieldLabel : 'Email'
                                        ,allowBlank : false
                                        ,maxLength  : 50
										,vtype : 'email'
                                    } )
									,standards.callFunction( '_createTextField', {
                                        id          : 'contactNumber' + module
                                        ,fieldLabel : 'Contact Number'
                                        ,allowBlank : false
										,maskRe     : /[^a-z,A-Z]/
                                        ,maxLength  : 12
                                    } )
                                    ,standards.callFunction( '_createTextArea', {
                                        id          : 'address' + module
                                        ,fieldLabel : 'Address'
                                        ,allowBlank : false
                                    } )
									,standards.callFunction( '_createTextField', {
                                        id          : 'tin' + module
                                        ,fieldLabel : 'TIN'
                                        ,allowBlank : false
										,maxLength  : 15
                                    } )                                   
                                    ,standards.callFunction( '_createCombo', {
                                        id              : 'paymentMethod' + module
                                        ,fieldLabel     : 'Payment Method'
                                        ,allowBlank     : false
                                        ,store          : paymentMethodStore
                                        ,displayField   : 'name'
                                        ,valueField     : 'id'
                                        ,value          : 0
										,listeners		: {
											select: function( me, result, record, noDuplicate ){
												var enableTerm = Ext.getCmp( 'terms' + module );
												if( parseInt(me.value) == 2 ){
													enableTerm.setDisabled(false);
													enableTerm.allowBlank = false;
													enableTerm.validate();
												}else{
													enableTerm.setDisabled(true);
													enableTerm.allowBlank = true;
													enableTerm.validate();
												}
											}
										}
                                    } )
									// ,standards.callFunction( '_createCombo', {
                                    //     id              : 'terms' + module
                                    //     ,fieldLabel     : 'Terms'
                                    //     ,allowBlank     : true
                                    //     ,store          : termsStore
									// 	,disabled		: true
                                    //     ,displayField   : 'name'
                                    //     ,valueField     : 'id'
                                    //     ,value          : 0
									// } )
									,standards.callFunction( '_createTextField', {
										id              : 'terms' + module
										,fieldLabel     : 'Terms'
										,isNumber		: true
										,isDecimal		: false
										,maskRe         : /[^a-z,A-Z]/
										,value		    : 0
										,listeners		: {
											afterrender : function( ) {
												if( Ext.getCmp('paymentMethod'+module).getValue() != 2 ) {
													this.setDisabled(true);
												}
											}
										}
									} )
                                    ,standards.callFunction( '_createCheckField', {
                                        id              : 'withCreditLimit' + module
										/* // to put label at the right place + CSS Setting
                                        ,boxLabel       : 'Check if User'
                                        ,style          : 'margin-left : 135px' */
										// ,checked		: true
										,fieldLabel     : 'With Credit Limit'
										,listeners		: {
											change: function( checkbox, newValue, oldValue, eOpts ){
												var toEnable = Ext.getCmp( 'creditLimit' + module );
												if(newValue){
													toEnable.allowBlank = false;
													toEnable.setDisabled(false);
													toEnable.validate();
												}else{
													toEnable.allowBlank = true;
													toEnable.setDisabled(true);
													toEnable.validate();
												}
											}	
										}
                                    } )
									,standards.callFunction( '_createTextField', {
                                        id          : 'creditLimit' + module
                                        ,fieldLabel : 'Credit Limit'
                                        ,allowBlank : true
                                        ,disabled	: true
										,isNumber   : true
										,isDecimal  : true
                                    } )
									,standards.callFunction( '_createCheckField', {
                                        id              : 'withVat' + module
										,fieldLabel     : 'VAT'
										,listeners		: {
											change: function( checkbox, newValue, oldValue, eOpts ){
												var toEnableVatType = Ext.getCmp( 'vatType' + module );
												var toEnableVatPercent = Ext.getCmp( 'vatPercent' + module );
												if(newValue){
													toEnableVatType.allowBlank = false;
													toEnableVatType.setDisabled(false);
													toEnableVatType.validate();
													
													toEnableVatPercent.allowBlank = false;
													toEnableVatPercent.setDisabled(false);
													toEnableVatPercent.validate();
												}else{
													toEnableVatType.allowBlank = true;
													toEnableVatType.setDisabled(true);
													toEnableVatType.validate();
													
													toEnableVatPercent.allowBlank = true;
													toEnableVatPercent.setDisabled(true);
													toEnableVatPercent.validate();
												}
												
											}
										}
                                    } )
									,{  xtype   : 'container'
                                        ,layout : 'hbox'
                                        ,style  : 'margin-bottom: 5px;'
                                        ,items  : [
                                            standards.callFunction( '_createCombo', {
												id              : 'vatType' + module
												,fieldLabel     : 'Vat Type'
												,allowBlank     : true
												,store          : vatTypeStore
												,disabled		: true
												,displayField   : 'name'
												,editable		: false
												,valueField     : 'id'
												,width      	: 290
												,style			: 'margin-right: 5px;'
											} )											
											,{  xtype   : 'container'
												,layout : 'hbox'
												,items  : [
													standards.callFunction( '_createTextField', {
														id          : 'vatPercent' + module
														,fieldLabel : ''
														,disabled	: true
														,allowBlank : true
														,width      : 40
														,isNumber   : true
														,isDecimal  : true
													} )													
													,{ 
														xtype	:'label'
														,text	:' % '
														,style	: 'margin-top: 2px;'
													}
												]
											}
                                        ]
                                    }
                                ]
                            }
                            ,{  xtype           : 'container'
                                ,columnWidth    : .4
                                ,layout         : 'column'
                                ,items          : [
                                    {   xtype   : 'container'
                                        ,html   : 'Affiliate<span style="color:red;">*</span>:'
                                        ,width  : 140
                                    }
                                    ,standards.callFunction( '_gridPanel', {
                                        id          : 'grdAffiliate' + module
                                        ,module     : module
                                        ,store      : affiliateStore
                                        ,height     : 185
										,width      : 250
                                        ,selModel   : sm
                                        ,plugins    : true
                                        ,noPage     : true
                                        ,columns    : [
                                            {   header      : 'Affiliate'
                                                ,dataIndex  : 'affiliateName'
                                                ,flex       : 1
                                                ,minWidth   : 100
												,renderer : function( val, params, record, row_index ){
													if( record.data.chk ){
														sm.select( row_index, true );
														record.set('selected', 1);
													}
													return val;
												}
                                            }
                                        ]
                                    } )
									,{  xtype   : 'container'
                                        ,layout : 'hbox'
										,width	: 430
                                        ,style  : 'margin-bottom: 5px;'
                                        ,items  : [
											standards.callFunction( '_createTextField', {
												id          : 'discount' + module
												,fieldLabel : 'Discount'
												,allowBlank : true
												,style		: 'margin-top: 5px;'
												,width		: 380
												,isNumber   : true
                                                ,isDecimal  : true
											} )
											,{ 
												xtype	:'label'
												,text	:' % '
												,style	: 'margin-top: 7px;'
											}
                                        ]
                                    }
									,standards.callFunction( '_createCheckField', {
                                        id              : 'withholdingTax' + module
										,fieldLabel     : 'Withholding Tax'
										,listeners:{
											change: function( checkbox, newValue, oldValue, eOpts ){
												var toEnableWHTaxRate = Ext.getCmp( 'withholdingTaxRate' + module );
												if(newValue){
													toEnableWHTaxRate.allowBlank = false;
													toEnableWHTaxRate.setDisabled(false);
													toEnableWHTaxRate.validate();
												}else{
													toEnableWHTaxRate.allowBlank = true;
													toEnableWHTaxRate.setDisabled(true);
													toEnableWHTaxRate.validate();
												}
											}	
										}
                                    } )									
									,{  xtype   : 'container'
                                        ,layout : 'hbox'
										,width	: 430
                                        ,style  : 'margin-bottom: 5px;'
                                        ,items  : [
											standards.callFunction( '_createTextField', {
												id          : 'withholdingTaxRate' + module
												,fieldLabel : 'Withholding Tax Rate'
												,allowBlank : true
												,disabled	: true
												,width		: 380
												,isNumber   : true
                                                ,isDecimal  : true
											} )
											,{ 
												xtype	:'label'
												,text	:' % '
												,style	: 'margin-top: 2px;'
											}
                                        ]
                                    }
									,{  xtype   : 'container'
                                        ,layout : 'hbox'
										,width	: 430
                                        ,style  : 'margin-bottom: 5px;'
                                        ,items  : [
                                            standards.callFunction( '_createCombo', {
												id              : 'expenseGlAcc' + module
												,fieldLabel     : 'GL Expense Account'
												,allowBlank     : true
												,store          : coaStore
												,emptyText		: 'Select gl account code...'
												,displayField   : 'acod_c15'
												,valueField     : 'acod_c15'
												,width          : 235
												,style			: 'margin-top: 5px; margin-right: 5px;'
												,listeners      : {
													beforeQuery : function(){
														var selected = Ext.getCmp('grdAffiliate' + module).getSelectionModel().getSelection()
														if(selected.length > 0){
															var ids = selected.map(affiliate => parseInt(affiliate.data.idAffiliate))
															coaStore.proxy.extraParams.affiliates = JSON.stringify(ids)
															coaStore.load({})
														}
													},select : function( me, result, record, noDuplicate ){
														var expenseGlAccName = Ext.getCmp( 'expenseGlAccName' + module );
														expenseGlAccName.store.load({
															callback: function(){
																expenseGlAccName.setValue(result[0].data.aname_c30);
																// expenseGlAccName.fireEvent('select');
															}
														});
													}
													,change: function( me, newValue, oldValue, eOpts ){
														if( newValue == null){ 
															this.reset(); 
															Ext.getCmp( 'expenseGlAccName' + module ).reset();
														}
													}
												}
											} )
											,standards.callFunction( '_createCombo', {
												id              : 'expenseGlAccName' + module
												,fieldLabel     : ''
												,allowBlank     : true
												,store          : coaStore
												,emptyText		: 'Select...'
												,displayField   : 'aname_c30'
												,valueField     : 'aname_c30'
												,width          : 150
												,style			: 'margin-top: 5px;'
												,listeners      : {
													beforeQuery : function(){
														var selected = Ext.getCmp('grdAffiliate' + module).getSelectionModel().getSelection()
														if(selected.length > 0){
															var ids = selected.map(affiliate => parseInt(affiliate.data.idAffiliate))
															coaStore.proxy.extraParams.affiliates = JSON.stringify(ids)
															coaStore.load({})
														}
													},select		: function( me, result, record, noDuplicate ){
														var expenseGlAcc = Ext.getCmp( 'expenseGlAcc' + module );
														expenseGlAcc.store.load({
															callback: function(){
																expenseGlAcc.setValue(result[0].data.acod_c15);
																// expenseGlAcc.fireEvent('select');
															}
														});
													}
													,change: function( me, newValue, oldValue, eOpts ){
														if( newValue == null){ 
															this.reset(); 
															Ext.getCmp( 'expenseGlAcc' + module ).reset();
														}
													}
												}
											} )
                                        ]
                                    }
									,{  xtype   : 'container'
                                        ,layout : 'hbox'
										,width	: 430
                                        ,style  : 'margin-bottom: 5px;'
                                        ,items  : [
                                            standards.callFunction( '_createCombo', {
												id              : 'discountGlAcc' + module
												,fieldLabel     : 'Discount GL Account'
												,allowBlank     : true
												,store          : coaStore
												,emptyText		: 'Select discount gl account code...'
												,displayField   : 'acod_c15'
												,valueField     : 'acod_c15'
												,width          : 235
												,style			: 'margin-top: 5px; margin-right: 5px;'
												,listeners      : {
													select		: function( me, result, record, noDuplicate ){
														var discountGlAccName = Ext.getCmp( 'discountGlAccName' + module );
														discountGlAccName.store.load({
															callback: function(){
																discountGlAccName.setValue(result[0].data.aname_c30);
																// discountGlAccName.fireEvent('select');
															}
														});
													}
													,change: function( me, newValue, oldValue, eOpts ){
														if( newValue == null){ 
															this.reset(); 
															Ext.getCmp( 'discountGlAccName' + module ).reset();
														}
													}
												}
											} )
											,standards.callFunction( '_createCombo', {
												id              : 'discountGlAccName' + module
												,fieldLabel     : ''
												,allowBlank     : true
												,store          : coaStore
												,emptyText		: 'Select...'
												,displayField   : 'aname_c30'
												,valueField     : 'aname_c30'
												,width          : 150
												,style			: 'margin-top: 5px;'
												,listeners      : {
													select		: function( me, result, record, noDuplicate ){
														var discountGlAcc = Ext.getCmp( 'discountGlAcc' + module );
														discountGlAcc.store.load({
															callback: function(){
																discountGlAcc.setValue(result[0].data.acod_c15);
																// discountGlAcc.fireEvent('select');
															}
														});
													}
													,change: function( me, newValue, oldValue, eOpts ){
														if( newValue == null){ 
															this.reset(); 
															Ext.getCmp( 'discountGlAcc' + module ).reset();
														}
													}
												}
											} )
                                        ]
                                    }								
									
									
                                ]
                            }
                        ]  
                    }
                    ,_supplierTabPanel()
                ]
                ,listItems  : _gridHistory()
                ,listeners  : {
                    afterrender : _init
                }
            } )
        }
		
		function _supplierTabPanel(){
            var classificationStore     = standards.callFunction( '_createRemoteStore', {
                    fields      : [
						{ name: 'idEmpClass', type:'float' }
						,'empClassName'
					]
                    ,url        : route + 'getClassification'
                } )
                ,userTypeStore          = standards.callFunction( '_createLocalStore', {
                    data        : [ 'Administrator', 'Supervisor', 'User' ]
                    ,startAt    : 1
                } );
            
			return {  
				xtype   : 'tabpanel'
				,id		: 'tabPanelID' + module
                // ,width  : 1000
				,style	: 'margin-top:10px;'
                ,items  : [
                    {   title   : 'Item(s)'
						,disabled   : false
                        ,padding    : 0
                        ,items  : [ _grdItems() ]
                    }
                ]
				/* ,listeners  : {
                    afterrender : _init
                    afterrender : function (){
						Ext.getCmp( 'tabPanelID' + module ).items.getAt(2).setDisabled(false);
						console.log('ni sulod pod dre kay');
					}
                } */
            }
        }
		
		function _grdItems(){
            var scheduleStore   = standards.callFunction( '_createLocalStore', {
                    data        : [ 'Daily', 'Monthly (1st Half)', 'Monthly (2nd Half)', 'Semi-Monthly' ]
                    ,startAt    : 1
                } )
                ,gridItemStore  = standards.callFunction( '_createRemoteStore', {
                    fields      : [ 
									{ name:'idItem', type:'int' }
									,{ name:'idItemClass', type:'int' }
									,'barcode','itemName', 'itemClassification'
									]
                    ,url        : route + 'getSupplierItems'
                } )
				,searchItemStore = standards.callFunction('_createRemoteStore',{
					fields	: [ {name: 'idItem', type:'int'},'barcode', 'itemName','itemClassification']
					,url 	: route + 'getSearchItemDetails'
				})
					
            return standards.callFunction( '_gridPanel', {
                id          : 'grdItems' + module
                ,store      : gridItemStore
                ,noPage     : true
                ,module     : module
                ,height     : 250
                ,plugins    : true
                ,noPage     : true
                ,tbar       : {
                    content     : 'add'
                    ,addLabel   : 'Add'
                }
                ,columns    : [
					{
						dataIndex	:'idItem'
						,hidden		: true
					}
                    ,{   header     : 'Code'
                        ,dataIndex  : 'barcode'
                        ,minWidth   : 200
                        ,editor     : standards.callFunction( '_createCombo', {
                            fieldLabel      : ''
                            ,id             : 'searchItemCodeCmb' + module
							,store			: searchItemStore
							,emptyText		: 'Select item code...'
                            ,displayField   : 'barcode'
                            ,valueField     : 'barcode'
                            ,listeners      : {
								beforeQuery : function( me ){
									_checkAffiliate( searchItemStore, me )
								}
                                ,select  : function( me, recordDetails, returnedData ){
									/* 
										Note: Sir Mark, gibilin lang nako imong original code just incase gamiton pa nimo :D -Hazel
										
										var validateDuplicateAtMainGrid = Ext.getCmp('grdItems' + module).getStore();
										var row  = validateDuplicateAtMainGrid.findExact('barcode',this.getValue());
										if(row > -1){
											msgs='Duplicate code found, please select another item code.';
											this.setValue( null );
											standards.callFunction('_createMessageBox',{ msg: msgs })
											return false;
										}
										var gridMain = Ext.getCmp('grdItems' + module);	
										var recordMain = gridMain.getSelectionModel().getSelection()[0];
										recordMain.set('idItem',recordDetails[0].data.idItem);
										recordMain.set('itemName',recordDetails[0].data.itemName);
										recordMain.set('className',recordDetails[0].data.className);
									*/

									let { 0 : store } = Ext.getCmp('grdItems' + module).selModel.getSelection();
									_setItemDetails(this, gridItemStore );
									
									
									// Ext.setGridData(['idItem','itemName','itemClassification'], store,this.findRecord(this.valueField, this.getValue() ))
                                }
                            }
                        })						
                    }
                    ,{   header      : 'Item Name'
                        ,dataIndex  : 'itemName'
                        ,flex       : 1
                        ,minWidth   : 150
                        ,editor     : standards.callFunction( '_createCombo', {
                            fieldLabel      : ''
                            ,id             : 'searchItemNameCmb' + module
							,store			: searchItemStore
							,emptyText		: 'Select item name...'
                            ,displayField   : 'itemName'
                            ,valueField     : 'itemName'
                            ,listeners      : {
								beforeQuery : function( me ){
									_checkAffiliate( searchItemStore, me )
								}
                                ,select  : function( me, recordDetails, returnedData ){	
									_setItemDetails(this, gridItemStore );								
									// let { 0 : store } = Ext.getCmp('grdItems' + module).selModel.getSelection();
									// Ext.setGridData(['idItem','barcode','itemName','itemClassification'], store,this.findRecord(this.valueField, this.getValue() ))
                                }
                            }
                        })
                    }
					,{   header     : 'Classification'
                        ,dataIndex  : 'itemClassification'
                        ,minWidth   : 150
                    }
                ]
            } )
        }
		
		function _checkAffiliate( searchItemStore, thisValue ){
			var selectedAffiliate = Ext.getCmp('grdAffiliate' + module).getSelectionModel().getSelection();
			if( selectedAffiliate.length > 0){
				var idAffiliates = selectedAffiliate.map(affiliate => parseInt(affiliate.data.idAffiliate))
				searchItemStore.proxy.extraParams.affiliates = JSON.stringify(idAffiliates)
				searchItemStore.load({})
				// delete me.combo.lastQuery;
			} else {
				standards.callFunction('_createMessageBox', { msg : 'You must select an affiliate first before choosing an item.'});
			}
			
		}
		
		
		function _getCOADetails( params ){
			Ext.Ajax.request({
				url : standardRoute + 'getCOADetails'
				,params:{
					coaID : ( typeof params.coaID != 'undefined' ? params.coaID : 0 )
				}
				,success:function(response){
					var ret = Ext.decode(response.responseText);
					if( typeof params.success != 'undefined' ){
						params.success(ret);
					}
				}
			});
		}

        function _gridHistory(){
            var store = standards.callFunction( '_createRemoteStore', {
				fields : [
                    { name	: 'idSupplier' ,type : 'int' },{ name	: 'contactNumber' ,type : 'int' }
                    ,'tin','name' ,'address'
				]
                ,url : route + 'getSupplierList'
            } );

            return standards.callFunction( '_gridPanel', {
                id              : 'gridHistory' + module
                ,module         : module
                ,store          : store
                ,width          : 350
                ,noDefaultRow   : true
                ,columns        : [
                    {  header      : 'Supplier Name'
                        ,dataIndex  : 'name'
                        ,width      : 200
						,flex		: 1
                    }
                    ,{  header      : 'TIN'
                        ,dataIndex  : 'tin'
                        ,width      : 200
                    }
                    ,{  header      : 'Address'
                        ,dataIndex  : 'address'
                        ,width      : 200
						,flex		: 1
                    }
                    ,{  header      : 'Contact Number'
                        ,dataIndex  : 'contactNumber'
                        ,width      : 150
                    }
                    ,standards.callFunction( '_createActionColumn', {
						canEdit     : canEdit
						,icon       : 'pencil'
						,tooltip    : 'Edit employee details'
						,Func       : _editRecord
                    } )               
                    ,standards.callFunction( '_createActionColumn', {
						canDelete   : canDelete
						,icon       : 'remove'
						,tooltip    : 'Delete reference'
						,Func       : _deleteRecord
					} )
                ]
            } )
        }

        function _saveForm( form ){
				
				/* Grid Affiliate Details */
				var affiliateListContainer 	= new Array();
				Ext.getCmp( "grdAffiliate" + module ).store.each( function( item ) { if(parseInt(item.data.selected) == 1){ affiliateListContainer.push( item.data ) } }  )	
				var grdAffilicateCount=affiliateListContainer.length;
								
				if(grdAffilicateCount == 0){
					standards.callFunction('_createMessageBox',{ msg: 'No Affiliate selected, please select at least one affiliate.' })
					return false;
				}
				
				var itemListContainer 	= new Array();
				Ext.getCmp( 'grdItems' + module ).store.each( function( item ) {  itemListContainer.push( item.data )  } )	
				
				form.submit({
					waitTitle	: "Please wait"
					,waitMsg	: "Submitting data..."
					,url		: route + 'saveSupplierForm'
					,params		: {
						affiliateList : Ext.encode( affiliateListContainer )
						,itemList : Ext.encode( itemListContainer )
					}
					,success:function( action, response ){
						var text = parseInt(response.result.match);
						var msgs = '';				 
						if( parseInt(text) == 1 ){ 
							msgs = 'Supplier name already exist, please choose another name.'; 
						}
						else if( parseInt(text) == 2 ){ msgs = 'Supplier does not exist, please choose another supplier.'; }
						else{ 
							msgs = 'Record has been successfully saved.'; 
							_resetForm();
						}				 
						standards.callFunction('_createMessageBox',{ msg: msgs })
					}
				})
        }

        function _editRecord( data ){
				    module.getForm().retrieveData({
						url: route + 'retrieveData'
						,params: data
						,success:function( response, data ){
							
							Ext.getCmp( 'grdAffiliate' + module ).store.load( { params: { idSupplier:parseInt(response.idSupplier) }
								,callback : function(){
									Ext.getCmp( 'grdAffiliate' + module ).getView().refresh();
								}
							})
							
							var paymentMethodValue = Ext.getCmp( 'paymentMethod' + module ).value
							var termObject = Ext.getCmp( 'terms' + module )
							if( parseInt(paymentMethodValue) == 2 ){
								termObject.setDisabled(false);
								termObject.allowBlank = false;
								termObject.validate();
							}else{
								termObject.setDisabled(true);
								termObject.allowBlank = true;
								termObject.validate();
							}
							
							/* Expense GL Account Group */
							var glExpenseAccount = Ext.getCmp( 'expenseGlAcc' + module );
							glExpenseAccount.store.load({
								callback: function(){
									glExpenseAccount.setValue( response.expenseGlAcc );
									// glExpenseAccount.fireEvent('select');
								}
							});
							var glExpenseAccountName = Ext.getCmp( 'expenseGlAccName' + module );
							glExpenseAccountName.store.load({
								callback: function(){
									glExpenseAccountName.setValue( response.expenseGlAccName );
									// glExpenseAccountName.fireEvent('select');
								}
							});
							
							/* Discount GL Account Group */
							var discountGLAccount = Ext.getCmp( 'discountGlAcc' + module );
							discountGLAccount.store.load({
								callback: function(){
									discountGLAccount.setValue( response.discountGlAcc );
									// discountGLAccount.fireEvent('select');
								}
							});
							var discountGLAccountName = Ext.getCmp( 'discountGlAccName' + module );
							discountGLAccountName.store.load({
								callback: function(){
									discountGLAccountName.setValue( response.discountGlAccName );
									// discountGLAccountName.fireEvent('select');
								}
							});
							
							Ext.getCmp( 'grdItems' + module ).store.load( { params: { idSupplier:parseInt(response.idSupplier) } })
							Ext.getCmp('searchItemCodeCmb' + module ).store.load({ params:{idAffiliate:idAffiliate}})
							Ext.getCmp('searchItemNameCmb' + module ).store.load({ params:{idAffiliate:idAffiliate}})
						}
					})
        }
		
		function _deleteRecord( data ){
			
			standards.callFunction('_createMessageBox',{
				msg		: 'DELETE_CONFIRM'
				,action	: 'confirm'
				,fn 	: function ( btn ){
					if( btn == 'yes' ){						
						Ext.Ajax.request({
							url: route + 'deleteSupplierRecord'
							,params:{ idSupplier: data.idSupplier, supplierName: data.name }
							,method:'post'
							,success: function(response, option){
								// var grdAffiliate = Ext.getCmp( 'gridHistory' + module ).store.load(); 
								// standards.callFunction('_createMessageBox',{ msg: 'DELETE_SUCCESS' })
								var msgs = '';
								
								
								
								var responseValue = Ext.decode( response.responseText );
								if ( parseInt( responseValue.match ) == 2 ){
									msgs = 'The selected supplier has been deleted already. <br> Please select another supplier.';
									// standards.callFunction('_createMessageBox',{
										// msg: 'The selected supplier has been deleted already. <br> Please select another supplier.'
									// })
								}
								else if( parseInt( responseValue.match ) == 3 ){
									msgs = 'The supplier can no longer be deleted. The supplier is connected with other transactions.'
									// standards.callFunction('_createMessageBox',{
										// msg: 'The supplier can no longer be deleted. The supplier is connected with other transactions.'
									// })
								}else{ msgs = 'DELETE_SUCCESS'; }
								
								// var grdAffiliate = Ext.getCmp( 'gridHistory' + module ).store.load(); 
								standards.callFunction('_createMessageBox',{ msg: msgs })
								Ext.getCmp( 'gridHistory' + module ).store.load(); 
								
							}
							,failure: function(){											
								Ext.MessageBox.alert('Status','There was an error while sending reset password link. Please try again later.');
							}
						});
						
					}
				}
			})
        }
       
        function _resetForm( form ){
			Ext.getCmp( 'grdAffiliate' + module ).store.load();
			Ext.getCmp('mainFormID' + module).getForm().reset();
			onEdit = 0;
			var grdAffiliate = Ext.getCmp( 'grdAffiliate' + module ); 
			grdAffiliate.store.removeAll()
			grdAffiliate.store.load()
			
			Ext.getCmp( 'grdItems' + module ).store.removeAll() //Reset Item Grid
			
		}

		function _setItemDetails( me, _store ) {
            if( Ext.isUnique(me.valueField, _store, me) ) {
                var { 0 : store } = Ext.getCmp('grdItems' + module).selModel.getSelection()
				,row = me.findRecord(me.valueField, me.getValue());
                Ext.setGridData([ 'idItem', 'idItemClass', 'itemName', 'barcode', 'itemClassification' ], store, row);
            }
        }
	
        return{
			initMethod:function( config ){
				route 		= config.route;
				module 		= config.module;
				canDelete 	= config.canDelete;
				canPrint 	= config.canPrint;
				pageTitle 	= config.pageTitle;
				isGae 		= config.isGae;
				canEdit 	= config.canEdit;
				idModule 	= config.idmodule;
				idAffiliate = config.idAffiliate
				
				return _mainPanel( config );
			}
		}
    }
}