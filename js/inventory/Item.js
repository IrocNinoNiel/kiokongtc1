/**
 * Developer: Mark Reynor D. Magriña
 * Module: Item Settings
 * Date: Dec 05, 2019
 * Finished:
 * Description:
 * DB Tables:
 * */
var Item = function () {
	return function () {
		var route, module, canDelete, canPrint, pageTitle, isGae, canEdit, idModule, onEdit = 0,
			invalidDate = 0,
			onEditContributionQuickSettings = 0,
			selRec, componentCalling;
		var employmentChange = 0;

		function _init() {
			Ext.getCmp('mainTbar' + module).add(2, {
				xtype: 'button',
				id: 'import_button_tbar' + module,
				text: 'Import Items from Excel',
				iconCls: 'glyphicon glyphicon-upload',
				handler: function () {
					IMPORT();
				}
			})
		}

		function IMPORT() {
			Ext.create('Ext.window.Window', {
				title: 'Import Items from Excel',
				id: 'import_window' + module,
				modal: true,
				autoHeight: true,
				autoWidth: true,
				resizable: false,
				bodyPadding: 10,
				layout: 'fit',
				items: [{
					xtype: 'form',
					id: 'import_form' + module,
					baseCls: 'x-plain',
					frame: false,
					border: false,
					buttonAlign: 'center',
					items: [{
						xtype: 'label',
						html: 'Excel file must follow a specific format. You may download the file <a href="#" id="download_format_excel">here</a>.',
						listeners: {
							afterrender: function () {
								document.getElementById("download_format_excel").onclick = function () {
									window.open(route + 'download_format');
								}
							}
						},
					}, {
						xtype: 'fileuploadfield',
						width: 400,
						fieldLabel: 'File',
						labelWidth: 20,
						name: 'file_import' + module,
						id: 'file_import' + module,
						style: 'margin-top:3px',
						buttonConfig: {
							text: '',
							iconCls: 'glyphicon glyphicon-folder-open',
						},
						msgTarget: 'under',
						validator: function (value) {
							try {
								if (value) {
									var file = this.getEl().down('input[type=file]').dom.files[0];
									var exp = /^.*\.(xlsx|XLSX|xls|XLS|csv)$/;
									if (exp.test(value)) {
										if (parseInt(file.size) > (2 * (1024 * 1000))) return 'Exceed file upload limit.';
										else return true;
									} else return 'Invalid file format.';
								} else return false;
							} catch (er) {
								console.log(er);
							}
						}
					}],
					buttons: [{
						text: 'Import',
						formBind: true,
						disabled: true,
						handler: function () {
							var form = Ext.getCmp('import_form' + module).getForm();
							fileName = Ext.getCmp('file_import' + module).getValue();
							form.submit({
								waitTitle: "Please wait",
								waitMsg: "Submitting data...",
								method: 'post',
								url: route + 'IMPORT',
								params: {
									module: module
								},
								success: function (res, response) {
									var resp = Ext.decode(response.response.responseText);
									if (resp.view.match == 2) {
										standards.callFunction('_createMessageBox', {
											msg: resp.view.msg,
											action: 'confirm'
										})
									} else {
										standards.callFunction('_createMessageBox', {
											msg: resp.view.msg,
											fn: function () {
												_resetForm(form);
											}
										})
									}
								},
								failure: function () {
									standards.callFunction('_createMessageBox', {
										msg: 'Database connectivity error: Failure during restoration of record.',
										icon: 'Error'
									});
								}
							});
						}
					}]
				}]
			}).show();
		}

		function _mainPanel(config) {
			// CHECKS IF THE MAIN PANEL IS ALREADY RENDERED
			if (typeof Ext.getCmp('mainFormID' + module) != 'undefined' ) {
				if(selRec != null) _editRecord(selRec);
				return false;
			}

			/* store area */
			var sm = new Ext.selection.CheckboxModel({
				checkOnly: true,
				listeners: {
					select: function (val, rec) {
						var record = rec.data
						record.chk = 1;
						rec.set('chk', 1);
					},
					deselect: function (val, record) {
						record.set('chk', 0);
					}
				}
			})

			var affiliateStore = standards.callFunction('_createRemoteStore', {
				fields: ['idAffiliate', 'affiliateName', {
					name: 'chk',
					type: 'bool'
				}],
				url: route + 'getAffiliates'
			})
			var itemClassificationStore = standards.callFunction('_createRemoteStore', {
				fields: [{
					name: 'idItemClass',
					type: 'number'
				}, {
					name: 'classCode',
					type: 'number'
				}, 'className'],
				url: route + 'getItemClassification'
			})
			var itemUnitStore = standards.callFunction('_createRemoteStore', {
				fields: [{
					name: 'idUnit',
					type: 'number'
				}, {
					name: 'unitCode',
					type: 'number'
				}, 'unitName'],
				url: route + 'getItemUnit'
			})
			var coaStore = standards.callFunction('_createRemoteStore', {
				fields: [{
					name: 'idCoa',
					type: 'number'
				}, 'acod_c15', 'aname_c30'],
				url: route + 'getCOADetails'
			})

			return standards.callFunction('_mainPanel', {
				config: config,
				moduleType: 'form',
				id: 'mainFormID' + module,
				module: module,
				tbar: {
					// listLabel       : 'List'
					noListButton: true,
					saveFunc: _saveForm,
					resetFunc: _resetForm,
					noPDFButton: true,
					noExcelButton: true,
					noFormButton: true
				},
				formItems: [{
							xtype: 'container',
							layout: 'column',
							items: [{
								xtype: 'container',
								columnWidth: .5,
								items: [
									standards.callFunction('_createTextField', {
										id: 'idItem' + module,
										fieldLabel: 'ID Number',
										allowBlank: true,
										value: 0,
										hidden: true

									}), standards.callFunction('_createTextField', {
										id: 'barcode' + module,
										fieldLabel: 'Code',
										allowBlank: false,
										maxLength: 50,
										width: 450
									}), standards.callFunction('_createTextField', {
										id: 'itemName' + module,
										fieldLabel: 'Item Name',
										allowBlank: false,
										maxLength: 50,
										width: 450
									}), standards.callFunction('_createCombo', {
										id: 'idItemClass' + module,
										fieldLabel: 'Item Classification',
										allowBlank: false,
										store: itemClassificationStore,
										displayField: 'className',
										valueField: 'idItemClass',
										width: 450
										// ,value          : 0
									}), {
										xtype: 'container',
										layout: 'hbox',
										style: 'margin-bottom: 5px;',
										items: [
											standards.callFunction('_createTextField', {
												id: 'itemPrice' + module,
												fieldLabel: 'Item Price'
													// ,allowBlank : false
													,
												allowBlank: true,
												maxLength: 50,
												width: 245,
												style: 'margin-right: 5px;',
												isNumber: true,
												isDecimal: true
											}), standards.callFunction('_createDateField', {
												id: 'effectivityDate' + module,
												fieldLabel: 'Effectivity Date',
												labelWidth: 90
													// ,allowBlank : false
													,
												allowBlank: true,
												width: 200
											})
										]
									},
									standards.callFunction('_createCombo', {
										id: 'idUnit' + module,
										fieldLabel: 'Item Unit',
										allowBlank: false,
										store: itemUnitStore,
										displayField: 'unitName',
										valueField: 'idUnit',
										width: 450
										// ,value          : 0
									}), standards.callFunction('_createTextField', {
										id: 'reorderLevel' + module,
										fieldLabel: 'Reorder Level',
										maskRe: /[^a-z,A-Z]/
											// ,allowBlank : false
											,
										allowBlank: true,
										width: 450,
										isnumber: true,
										fieldStyle: "text-align:right;",
										value: 0,
										listeners: {
											change(me, newValue, oldValue, eOpts) {
												if (parseInt(me.value) < 0) {
													standards.callFunction('_createMessageBox', {
														msg: 'Negative and less than zero value is not allowed.'
													})
													this.setValue(0);
													return false;
												}
											}
										}
									})
								]
							}, {
								xtype: 'container',
								columnWidth: .4
									// ,layout         : 'hbox'
									,
								layout: 'column',
								items: [{
									xtype: 'container',
									html: 'Affiliate<span style="color:red;">*</span>:',
									width: 140
									// ,labelWidth : 50
								}, standards.callFunction('_gridPanel', {
									id: 'grdAffiliate' + module,
									module: module,
									store: affiliateStore
										// ,height     : 185
										,
									height: 140,
									width: 285,
									selModel: sm,
									plugins: true,
									noPage: true,
									columns: [{
										header: 'Affiliate',
										dataIndex: 'affiliateName',
										flex: 1,
										minWidth: 100,
										renderer: function (val, params, record, row_index) {
											if (record.data.chk) {
												sm.select(row_index, true);
												record.set('selected', 1);
											}
											return val;
										}
									}],
									listeners: {
										afterrender: function () {
											affiliateStore.load()
										}
									}
								}), standards.callFunction('_createCheckField', {
									id: 'releaseWithoutQty' + module
										/* to put label at the right place + CSS Setting */
										,
									boxLabel: 'Sell/release item without remaining quantity',
									style: 'margin-left : 140px',
									listeners: {
										change: function (checkbox, newValue, oldValue, eOpts) {}
									}
								})]
							}]
						}, {
							xtype: 'container',
							layout: 'column',
							items: [{
								xtype: 'fieldset',
								title: 'Journal Entries',
								layout: 'vbox',
								padding: 10,
								style: "width:100%",
								items: [{
									xtype: 'container',
									layout: 'hbox',
									width: 430,
									style: 'margin-bottom: 5px;',
									items: [
										standards.callFunction('_createCombo', {
											id: 'salesGlAcc' + module,
											fieldLabel: 'GL Sales Account',
											allowBlank: true,
											store: coaStore,
											emptyText: 'Account code...',
											displayField: 'acod_c15',
											valueField: 'acod_c15',
											labelWidth: 140,
											width: 230,
											style: 'margin-top: 5px; margin-right: 5px;',
											listeners: {
												beforeQuery: function (me) {
													var grdAffiliate = Ext.getCmp('grdAffiliate' + module).selModel.getSelection(),
														affRec = new Array();
													grdAffiliate.forEach(function (data) {
														affRec.push(data.get('idAffiliate'));
													});
													coaStore.proxy.extraParams.affiliates = Ext.encode(affRec);
													delete me.lastQuery;
												},
												select: function (me, result, record, noDuplicate) {
													var salesGlAccName = Ext.getCmp('salesGlAccName' + module);
													salesGlAccName.setValue(result[0].data.aname_c30);
												},
												change: function (me, newValue, oldValue, eOpts) {
													if (newValue == null) {
														this.reset();
														Ext.getCmp('salesGlAccName' + module).reset();
													}
												}
											}
										}), standards.callFunction('_createCombo', {
											id: 'salesGlAccName' + module,
											fieldLabel: '',
											allowBlank: true,
											store: coaStore,
											emptyText: 'Select account name...',
											displayField: 'aname_c30',
											valueField: 'aname_c30',
											width: 190,
											style: 'margin-top: 5px;',
											listeners: {
												beforeQuery: function (me) {
													var grdAffiliate = Ext.getCmp('grdAffiliate' + module).selModel.getSelection(),
														affRec = new Array();
													grdAffiliate.forEach(function (data) {
														affRec.push(data.get('idAffiliate'));
													});
													coaStore.proxy.extraParams.affiliates = Ext.encode(affRec);
													delete me.lastQuery;
												},
												select: function (me, result, record, noDuplicate) {
													var salesGlAcc = Ext.getCmp('salesGlAcc' + module);
													salesGlAcc.setValue(result[0].data.acod_c15);
												},
												change: function (me, newValue, oldValue, eOpts) {
													if (newValue == null) {
														this.reset();
														Ext.getCmp('salesGlAcc' + module).reset();
													}
												}
											}
										})
									]
								}, {
									xtype: 'container',
									layout: 'hbox',
									width: 430,
									style: 'margin-bottom: 5px;',
									items: [
										standards.callFunction('_createCombo', {
											id: 'inventoryGlAcc' + module,
											fieldLabel: 'GL Inventory Account',
											allowBlank: true,
											store: coaStore,
											emptyText: 'Account code...',
											displayField: 'acod_c15',
											valueField: 'acod_c15',
											labelWidth: 140,
											width: 230,
											style: 'margin-top: 5px; margin-right: 5px;',
											listeners: {
												beforeQuery: function (me) {
													var grdAffiliate = Ext.getCmp('grdAffiliate' + module).selModel.getSelection(),
														affRec = new Array();
													grdAffiliate.forEach(function (data) {
														affRec.push(data.get('idAffiliate'));
													});
													coaStore.proxy.extraParams.affiliates = Ext.encode(affRec);
													delete me.lastQuery;
												},
												select: function (me, result, record, noDuplicate) {
													var inventoryGlAccName = Ext.getCmp('inventoryGlAccName' + module);
													inventoryGlAccName.setValue(result[0].data.aname_c30);
												},
												change: function (me, newValue, oldValue, eOpts) {
													if (newValue == null) {
														this.reset();
														Ext.getCmp('inventoryGlAccName' + module).reset();
													}
												}
											}
										}), standards.callFunction('_createCombo', {
											id: 'inventoryGlAccName' + module,
											fieldLabel: '',
											allowBlank: true,
											store: coaStore,
											emptyText: 'Select account name...',
											displayField: 'aname_c30',
											valueField: 'aname_c30',
											width: 190,
											style: 'margin-top: 5px;'
												// ,labelWidth : 50
												,
											listeners: {
												beforeQuery: function (me) {
													var grdAffiliate = Ext.getCmp('grdAffiliate' + module).selModel.getSelection(),
														affRec = new Array();
													grdAffiliate.forEach(function (data) {
														affRec.push(data.get('idAffiliate'));
													});
													coaStore.proxy.extraParams.affiliates = Ext.encode(affRec);
													delete me.lastQuery;
												},
												select: function (me, result, record, noDuplicate) {
													var inventoryGlAcc = Ext.getCmp('inventoryGlAcc' + module);
													inventoryGlAcc.setValue(result[0].data.acod_c15);
												},
												change: function (me, newValue, oldValue, eOpts) {
													if (newValue == null) {
														this.reset();
														Ext.getCmp('inventoryGlAcc' + module).reset();
													}
												}
											}
										})
									]
								}, {
									xtype: 'container',
									layout: 'hbox',
									width: 430,
									style: 'margin-bottom: 5px;',
									items: [
										standards.callFunction('_createCombo', {
											id: 'costofsalesGlAcc' + module,
											fieldLabel: 'GL Cost of Good Sold',
											allowBlank: true,
											store: coaStore,
											emptyText: 'Account code...',
											displayField: 'acod_c15',
											valueField: 'acod_c15',
											labelWidth: 140,
											width: 230,
											style: 'margin-top: 5px; margin-right: 5px;',
											listeners: {
												beforeQuery: function (me) {
													var grdAffiliate = Ext.getCmp('grdAffiliate' + module).selModel.getSelection(),
														affRec = new Array();
													grdAffiliate.forEach(function (data) {
														affRec.push(data.get('idAffiliate'));
													});
													coaStore.proxy.extraParams.affiliates = Ext.encode(affRec);
													delete me.lastQuery;
												},
												select: function (me, result, record, noDuplicate) {
													var costofsalesGlAccName = Ext.getCmp('costofsalesGlAccName' + module);
													costofsalesGlAccName.setValue(result[0].data.aname_c30);
												},
												change: function (me, newValue, oldValue, eOpts) {
													if (newValue == null) {
														this.reset();
														Ext.getCmp('costofsalesGlAccName' + module).reset();
													}
												}
											}
										}), standards.callFunction('_createCombo', {
											// module			: module
											id: 'costofsalesGlAccName' + module,
											fieldLabel: '',
											allowBlank: true,
											store: coaStore,
											emptyText: 'Select account name...',
											displayField: 'aname_c30',
											valueField: 'aname_c30',
											width: 190,
											style: 'margin-top: 5px;'
												// ,labelWidth : 50
												,
											listeners: {
												beforeQuery: function (me) {
													var grdAffiliate = Ext.getCmp('grdAffiliate' + module).selModel.getSelection(),
														affRec = new Array();
													grdAffiliate.forEach(function (data) {
														affRec.push(data.get('idAffiliate'));
													});
													coaStore.proxy.extraParams.affiliates = Ext.encode(affRec);
													delete me.lastQuery;
												},
												select: function (me, result, record, noDuplicate) {
													var costofsalesGlAcc = Ext.getCmp('costofsalesGlAcc' + module);
													costofsalesGlAcc.setValue(result[0].data.acod_c15);
												},
												change: function (me, newValue, oldValue, eOpts) {
													if (newValue == null) {
														this.reset();
														Ext.getCmp('costofsalesGlAcc' + module).reset();
													}
												}
											}
										})
									]
								}]
							}]
						}
						// ,_itemHistoryTabPanel()
						,
						_grdItemHistory()
					]
					// ,listItems  : _gridHistory()
					,
				listeners: {
					afterrender: function () {
						_init();
						if (selRec) _editRecord(selRec);
					}
				}
			})
		}

		function _printPDF() {

			Ext.Ajax.request({
				url: route + 'generateItemSettingPDF',
				method: 'post',
				params: {
					idmodule: 16,
					pageTitle: 'Item List',
					limit: 50,
					start: 0,
					printPDF: 1
				},
				success: function (response, action) {
					if (isGae == 1) {
						window.open(route + 'viewPDF/Item List', '_blank')
					} else {
						window.open('pdf/inventory/Item List.pdf');
					}
				}
			})
		}

		function _printExcel() {

			if (canPrint) {
				Ext.Ajax.request({
					url: route + 'generateItemSettingExcel',
					method: 'post',
					params: {
						idmodule: 16,
						pageTitle: pageTitle,
						limit: 50,
						start: 0,
						printPDF: 1
					},
					success: function (response, action) {
						var path = route.replace(baseurl, '');
						window.open(baseurl + path + 'download' + '/' + pageTitle);
					}
				})
			} else {
				standards.callFunction('_createMessageBox', {
					msg: 'You are currently not authorized to print, please contact the administrator.'
				});
			}
		}

		function _itemHistoryTabPanel() {
			var classificationStore = standards.callFunction('_createRemoteStore', {
					fields: [{
						name: 'idEmpClass',
						type: 'float'
					}, 'empClassName'],
					url: route + 'getClassification'
				}),
				userTypeStore = standards.callFunction('_createLocalStore', {
					data: ['Administrator', 'Supervisor', 'User'],
					startAt: 1
				});

			return {
				xtype: 'tabpanel',
				id: 'tabPanelID' + module
					// ,width  : 1000
					,
				style: 'margin-top:10px;',
				items: [{
					// title   : 'Items'
					title: '',
					disabled: false,
					padding: 0,
					items: [_grdItemHistory()]
				}]
				/* ,listeners  : {
                    afterrender : _init
                    afterrender : function (){
						Ext.getCmp( 'tabPanelID' + module ).items.getAt(2).setDisabled(false);
						console.log('ni sulod pod dre kay');
					}
                } */
			}
		}

		function _grdItemHistory() {
			var scheduleStore = standards.callFunction('_createLocalStore', {
				data: ['Daily', 'Monthly (1st Half)', 'Monthly (2nd Half)', 'Semi-Monthly'],
				startAt: 1
			})
			var gridItemStore = standards.callFunction('_createRemoteStore', {
				fields: [{
					name: 'idItem',
					type: 'int'
				}, {
					name: 'idItemClass',
					type: 'int'
				}, {
					name: 'idUnit',
					type: 'int'
				}, {
					name: 'itemPrice',
					type: 'int'
				}, {
					name: 'reorderLevel',
					type: 'int'
				}, 'itemName', 'barcode', 'className', 'unitName', 'effectivityDate', 'costofsalesGlAccName', 'inventoryGlAccName', 'salesGlAccName'],
				url: route + 'getItemListDetails'
			})
			var searchItemStore = standards.callFunction('_createRemoteStore', {
				fields: [{
					name: 'idItem',
					type: 'int'
				}, 'itemName'],
				url: route + 'getSearchedItems'
			})

			return standards.callFunction('_gridPanel', {
				id: 'gridHistory' + module,
				store: gridItemStore,
				module: module
					// ,noDefaultRow   : true
					,
				height: 300,
				tbar: {
					content: [
						standards.callFunction('_createCombo', {
							id: 'searchItemCbm' + module,
							fieldLabel: '',
							allowBlank: true,
							store: searchItemStore,
							emptyText: 'Select item name...',
							displayField: 'itemName',
							valueField: 'idItem',
							hideTrigger: true,
							width: 230,
							listeners: {
								select: function (me, result, record, noDuplicate) {
									Ext.getCmp('gridHistory' + module).store.load({
										params: {
											itemNameSearch: result[0].data.itemName
										},
										callback: function () {
											Ext.getCmp('grdAffiliate' + module).getView().refresh();
										}
									})
								}
							}
						}), {
							xtype: 'button',
							iconCls: 'glyphicon glyphicon-refresh',
							handler: function () {
								Ext.getCmp('searchItemCbm' + module).reset();
								Ext.getCmp('gridHistory' + module).store.load()
							}
						}, '->'
						// ,{	 xtype		: 'button'
						// 	,iconCls	: 'pdf-icon'
						// 	,tooltip	: 'Export to PDF'
						// 	,handler	: function(){
						// 		_printPDF();
						// 	}
						// }
						// ,{	xtype		: 'button'
						// 	,iconCls	: 'excel'
						// 	,tooltip	: 'Export to CSV'
						// 	,handler	: function(){
						// 		_printExcel();
						// 	}
						// }
					],
					canPrint: canPrint,
					customListPDFHandler: _printPDF,
					customListExcelHandler: _printExcel
						// ,route                  : route
						,
					pageTitle: pageTitle
				},
				columns: [{
					header: 'Code',
					dataIndex: 'barcode',
					minWidth: 140
				}, {
					header: 'Item Name',
					dataIndex: 'itemName',
					flex: 1,
					minWidth: 150
				}, {
					header: 'Classification',
					dataIndex: 'className',
					minWidth: 150
				}, {
					header: 'Price',
					dataIndex: 'itemPrice',
					width: 150,
					xtype: 'numbercolumn',
					format: '0,000.00'
				}, {
					header: 'Effectivity Date',
					dataIndex: 'effectivityDate',
					xtype: 'datecolumn',
					format: 'm/d/Y',
					width: 150
				}, {
					header: 'Unit',
					dataIndex: 'unitName',
					minWidth: 150
				}, {
					header: 'Reorder level',
					dataIndex: 'reorderLevel',
					width: 100,
					xtype: 'numbercolumn',
					format: '0,000'
				}, standards.callFunction('_createActionColumn', {
					canEdit: canEdit,
					icon: 'th-list',
					tooltip: 'Price History',
					Func: _priceHistory
				}), standards.callFunction('_createActionColumn', {
					canEdit: canEdit,
					icon: 'pencil',
					tooltip: 'Edit Item Details',
					Func: _editRecord
				}), standards.callFunction('_createActionColumn', {
					canDelete: canDelete,
					icon: 'remove',
					tooltip: 'Delete Item',
					Func: _deleteRecord
				})],
				listeners: {
					afterrender: function () {
						gridItemStore.load()
					}
				}
			})
		}

		function _getCOADetails(params) {
			Ext.Ajax.request({
				url: standardRoute + 'getCOADetails',
				params: {
					coaID: (typeof params.coaID != 'undefined' ? params.coaID : 0)
				},
				success: function (response) {
					var ret = Ext.decode(response.responseText);
					if (typeof params.success != 'undefined') {
						params.success(ret);
					}
				}
			});
		}

		function _saveForm(form) {

			/* Grid Affiliate Details */
			var affiliateListContainer = new Array();
			Ext.getCmp("grdAffiliate" + module).store.each(function (item) {
				if (parseInt(item.data.selected) == 1) {
					affiliateListContainer.push(item.data)
				}
			})
			var grdAffilicateCount = affiliateListContainer.length;

			if (grdAffilicateCount == 0) {
				standards.callFunction('_createMessageBox', {
					msg: 'No Affiliate selected, please select at least one affiliate.'
				})
				return false;
			}

			if (Ext.getCmp('reorderLevel' + module).getValue() < 0) {
				standards.callFunction('_createMessageBox', {
					msg: 'Negative and less than zero value is not allowed.'
				})
				return false;
			}

			form.submit({
				waitTitle: "Please wait",
				waitMsg: "Submitting data...",
				url: route + 'saveItemForm',
				params: {
					affiliateList: Ext.encode(affiliateListContainer)
				},
				success: function (action, response) {
					var text = parseInt(response.result.match);
					var msgs = '';
					if (parseInt(text) == 1) {
						msgs = 'Item Code already exist, please choose another code.';
					} else if (parseInt(text) == 2) {
						msgs = 'Item does not exist, please choose another item.';
					} else {
						msgs = 'Record has been successfully saved.';
						_resetForm(form);
					}
					standards.callFunction('_createMessageBox', {
						msg: msgs
					})
				}
			})
		}

		function _priceHistory(data) {

			var searchItemPriceHistoryStore = standards.callFunction('_createRemoteStore', {
				fields: [{
					name: 'idItem',
					type: 'int'
				}, 'itemName'],
				url: route + 'getSearchedItems'
			})
			Ext.create('Ext.window.Window', {
				id: 'winViewPriceHistory' + module,
				width: 550,
				title: '<span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;Price History',
				height: 430,
				modal: true,
				closable: true,
				resizable: false,
				items: [
					Ext.create('Ext.form.Panel', {
						id: 'viewSettingsForm' + module,
						border: false,
						bodyPadding: 5,
						items: [{
							xtype: 'container',
							columnWidth: 0.40,
							style: 'margin: 10px 0 5px 0',
							items: [
								standards.callFunction('_createTextField', {
									id: 'idEmpClass' + module,
									hidden: true,
									allowBlank: true,
									value: 0
								}), standards.callFunction('_createCombo', {
									id: 'searchItemPriceHistoryCmb' + module,
									fieldLabel: 'Item Name',
									labelWidth: 115,
									allowBlank: true,
									store: searchItemPriceHistoryStore,
									emptyText: 'Select item name...',
									displayField: 'itemName',
									valueField: 'idItem',
									editable: true
										// ,hideTrigger	: true
										,
									width: 400,
									maxLength: 50,
									style: 'margin-left: 5px;',
									listeners: {
										select: function (me, result, record, noDuplicate) {
											Ext.getCmp('grdItemPriceHistory' + module).store.load({
												params: {
													idItem: Ext.getCmp('searchItemPriceHistoryCmb' + module).value,
													sdate: Ext.Date.format(Ext.getCmp('sdate' + module).value, 'Y/m/d'),
													edate: Ext.Date.format(Ext.getCmp('edate' + module).value, 'Y/m/d')
												},
												callback: function () {
													// console.warn( Ext.getCmp( 'grdAffiliate' + module ).selModel.getSelection() );
													// Ext.getCmp( 'grdItemPriceHistory' + module ).getView().refresh();
												}
											})

										}
									}
								})

							]
						}, {
							xtype: 'container',
							style: 'padding:5px;margin-top:10px;',
							items: [
								_gridItemPriceHisotyDetails(data)
							]
						}]
						// ,buttons: [
						// 		{  text: 'Save'
						// 			,xtype: 'button'
						// 			,iconCls: 'glyphicon glyphicon-floppy-disk'
						// 			,style: 'margin-left:5px;margin-right:5px;'
						// 			,handler: function(){
						// 				saveItemPriceHistory()
						// 			}
						// 			,width: 85
						// 		}
						// 		,{	text: 'Reset'
						// 			,xtype: 'button'
						// 			,iconCls: 'glyphicon glyphicon-refresh'
						// 			// ,style: 'margin-right:20px;'
						// 			,handler: function(){
						// 				var searchItemPriceHistoryCmb = Ext.getCmp( 'searchItemPriceHistoryCmb' + module );
						// 				searchItemPriceHistoryCmb.store.load({
						// 					callback: function(){
						// 						searchItemPriceHistoryCmb.setValue( parseInt(data.idItem) );
						// 						searchItemPriceHistoryCmb.fireEvent('select');
						// 					}
						// 				});
						// 			}
						// 			,width: 85
						// 		}
						// 		,{	text: 'Close'
						// 		,iconCls: 'glyphicon glyphicon-remove'
						// 		,handler: function(){
						// 			Ext.getCmp( 'winViewPriceHistory'+module ).destroy( true );
						// 		}
						// 	}
						// ]
					})
				]
			}).show();



		}

		function _gridItemPriceHisotyDetails(data) {
			var searchItemPriceHistoryCmbHolder = Ext.getCmp('searchItemPriceHistoryCmb' + module);
			searchItemPriceHistoryCmbHolder.store.load({
				callback: function () {
					searchItemPriceHistoryCmbHolder.setValue(parseInt(data.idItem));
					searchItemPriceHistoryCmbHolder.fireEvent('select');
				}
			});
			var gridItemPriceHistoryStore = standards.callFunction('_createRemoteStore', {
				fields: [{
					name: 'idItem',
					type: 'number'
				}, {
					name: 'itemPrice',
					type: 'number'
				}, 'effectivityDate'],
				url: route + 'getItemPriceHistoryDetails'
			})

			var endDateValue = new Date();
			return standards.callFunction('_gridPanel', {
				id: 'grdItemPriceHistory' + module,
				store: gridItemPriceHistoryStore,
				noPage: true,
				module: module,
				noDefaultRow: true,
				height: 300,
				plugins: true,
				tbar: {
					content: [
						standards.callFunction('_createDateField', {
							id: 'sdate' + module,
							fieldLabel: 'View from',
							labelWidth: 60,
							allowBlank: false,
							width: 170,
							style: 'margin-right : 5px;margin-left : 15px;',
							listeners: {
								change: function () {
									var edate = Ext.getCmp('edate' + module);
									var startDateValue = new Date(this.value);
									if (this.isValid()) {
										startDateValue.setDate(startDateValue.getDate() + 59);
										edate.setValue(startDateValue);
									}
								}
							}
						}), standards.callFunction('_createDateField', {
							id: 'edate' + module,
							fieldLabel: 'to',
							labelWidth: 20,
							allowBlank: false,
							width: 140,
							value: new Date(endDateValue.setMonth(endDateValue.getMonth() + 1)),
							style: 'margin-right : 5px;',
							listeners: {

							}
						}), {
							xtype: 'button',
							text: 'View',
							border: false,
							handler: function () {
								Ext.getCmp('grdItemPriceHistory' + module).store.load({
									params: {
										idItem: Ext.getCmp('searchItemPriceHistoryCmb' + module).value,
										sdate: Ext.Date.format(Ext.getCmp('sdate' + module).value, 'Y/m/d'),
										edate: Ext.Date.format(Ext.getCmp('edate' + module).value, 'Y/m/d')
									},
									callback: function () {}
								})
							}

						}, {
							xtype: 'button',
							text: 'Reset',
							border: false,
							handler: function () {
								// var searchItemPriceHistoryCmb = Ext.getCmp( 'searchItemPriceHistoryCmb' + module );
								// searchItemPriceHistoryCmb.store.load({
								// callback: function(){
								// searchItemPriceHistoryCmb.setValue( parseInt(data.idItem) );
								// searchItemPriceHistoryCmb.fireEvent('select');
								// }
								// });
								Ext.getCmp('sdate' + module).reset();
								Ext.getCmp('edate' + module).reset();
							}
						},
						'->',
						// {
						// 	xtype: 'button',
						// 	text:'<span class="glyphicon glyphicon-plus" style="color:#3498db;"></span>&nbsp;&nbsp;Add',
						// 	border: false,
						// 	handler: function(){
						// 		addedStore = new Array();
						// 		addedStore.push({
						// 			'itemPrice': 0.00
						// 			,'effectivityDate': new Date()
						// 		});
						// 		Ext.getCmp('grdItemPriceHistory'+module).getStore().add(addedStore);
						// 	}
						// }
					]
				},
				columns: [{
						header: '',
						dataIndex: 'idItem',
						xtype: 'numbercolumn',
						hidden: true
					}, {
						header: 'Price',
						dataIndex: 'itemPrice',
						flex: 1,
						xtype: 'numbercolumn',
						minWidth: 150,
						format: '0,000.00',
						align: 'right'
						// ,value		: 0.00
						// ,editor     : 'float'
					}, {
						header: 'Effectivity Date',
						dataIndex: 'effectivityDate',
						flex: 1,
						minWidth: 150,
						xtype: 'datecolumn',
						format: 'm/d/Y'
						// ,editor     : 'date'
					}
					// ,standards.callFunction( '_createActionColumn', {
					// 	canDelete   : canDelete
					// 	,icon       : 'remove'
					// 	,tooltip    : 'Delete Item'
					// 	,Func       : _deleteItemPriceHistory
					// } )
				],
				listeners: {
					afterrender: function () {
						this.store.load({
							params: {
								idItem: parseInt(data.idItem),
								sdate: Ext.Date.format(Ext.getCmp('sdate' + module).value, 'Y/m/d'),
								edate: Ext.Date.format(Ext.getCmp('edate' + module).value, 'Y/m/d')
							}
						})
					}
				}
			})

		}

		function saveItemPriceHistory() {

			/* Grid Item History Details */
			var itemPriceHistoryListContainer = new Array();
			Ext.getCmp('grdItemPriceHistory' + module).store.each(function (item) {
				if (item.data.itemPrice != 0) itemPriceHistoryListContainer.push(item.data)
			})

			var grdItemHistoryCount = itemPriceHistoryListContainer.length;
			if (grdItemHistoryCount == 0) {
				standards.callFunction('_createMessageBox', {
					msg: 'No Record to save, please enter valid price and effectivity date to save.'
				})
				return false;
			}

			Ext.Ajax.request({
				url: route + 'saveItemPriceHistory',
				params: {
					idItem: Ext.getCmp('searchItemPriceHistoryCmb' + module).getValue(),
					itemPriceHistoryList: Ext.encode(itemPriceHistoryListContainer)
				},
				success: function () {
					standards.callFunction('_createMessageBox', {
						msg: 'SAVE_SUCCESS'
					})
					Ext.getCmp('grdItemPriceHistory' + module).store.load({
						params: {
							idItem: Ext.getCmp('searchItemPriceHistoryCmb' + module).value,
							sdate: Ext.Date.format(Ext.getCmp('sdate' + module).value, 'Y/m/d'),
							edate: Ext.Date.format(Ext.getCmp('edate' + module).value, 'Y/m/d')
						},
						callback: function () {}
					})

					Ext.getCmp('gridHistory' + module).store.load();
				},
				failure: function () {}
			})
		}

		function _editRecord(data) {

			module.getForm().retrieveData({
				url: route + 'retrieveData',
				params: data,
				success: function (response, data) {
					Ext.getCmp('grdAffiliate' + module).store.load({
						params: {
							idItem: parseInt(response.idItem)
						},
						callback: function () {
							Ext.getCmp('grdAffiliate' + module).getView().refresh();
						}
					});

					/* GL Sales Account Group */
					var salesGlAcc = Ext.getCmp('salesGlAcc' + module);
					salesGlAcc.store.load({
						callback: function () {
							salesGlAcc.setValue(response.salesGlAcc);
							// salesGlAcc.fireEvent('select');
						}
					});
					var salesGlAccName = Ext.getCmp('salesGlAccName' + module);
					salesGlAccName.store.load({
						callback: function () {
							salesGlAccName.setValue(response.salesGlAccName);
							// salesGlAccName.fireEvent('select');
						}
					});

					/* GL Inventory Account */
					var inventoryGlAcc = Ext.getCmp('inventoryGlAcc' + module);
					inventoryGlAcc.store.load({
						callback: function () {
							inventoryGlAcc.setValue(response.inventoryGlAcc);
							// inventoryGlAcc.fireEvent('select');
						}
					});
					var inventoryGlAccName = Ext.getCmp('inventoryGlAccName' + module);
					inventoryGlAccName.store.load({
						callback: function () {
							inventoryGlAccName.setValue(response.inventoryGlAccName);
							// inventoryGlAccName.fireEvent('select');
						}
					});

					/* GL Cost of Sales Account */
					var costofsalesGlAcc = Ext.getCmp('costofsalesGlAcc' + module);
					costofsalesGlAcc.store.load({
						callback: function () {
							costofsalesGlAcc.setValue(response.costofsalesGlAcc);
							// costofsalesGlAcc.fireEvent('select');
						}
					});
					var costofsalesGlAccName = Ext.getCmp('costofsalesGlAccName' + module);
					costofsalesGlAccName.store.load({
						callback: function () {
							costofsalesGlAccName.setValue(response.costofsalesGlAccName);
							// costofsalesGlAccName.fireEvent('select');
						}
					});

					/**Affiliates**/
					if (response.affiliates != null) {
						var affiliates = response.affiliates.split(",", response.affiliates.length);

						var gridAffiliate = Ext.getCmp('grdAffiliate' + module),
							store = gridAffiliate.getStore(),
							grdSM = gridAffiliate.getSelectionModel();

						store.proxy.extraParams = {};
						store.load({
							callback: function () {
								var items = store.data.items;

								items.map((col, i) => {
									affiliates.map((idAffiliate) => {
										if (idAffiliate == col.data.idAffiliate) {
											grdSM.select(i, true);
										}
										// grdSM.setLocked( true );
									})
								})
							}
						});
					}
				}
			})
		}

		function _deleteRecord(data) {

			var itemIDCurrent = Ext.getCmp('idItem' + module).getValue();
			if (parseInt(itemIDCurrent) == parseInt(data.idItem)) {

				standards.callFunction('_createMessageBox', {
					msg: 'The item you want to delete is currently being edited. Do you want to procceed instead?',
					action: 'confirm',
					fn: function (btn) {
						if (btn == 'yes') {
							_confirmedDeleteFunction(data);
						} else {
							_resetForm(module.getForm())
						}
					}
				})
			} else {
				standards.callFunction('_createMessageBox', {
					msg: 'DELETE_CONFIRM',
					action: 'confirm',
					fn: function (btn) {
						if (btn == 'yes') {
							_confirmedDeleteFunction(data);
							/* Ext.Ajax.request({

								url: route + 'deleteItemRecord'
								,params:{ idItem: data.idItem, itemName: data.itemName }
								,method:'post'
								,success: function(response, option){
									var msgs = '';
									var responseValue = Ext.decode( response.responseText );
									if ( parseInt( responseValue.match ) != 0 ){
										msgs = 'The item can no longer be deleted. The item is connected with other transactions.';
									}else{
										msgs = 'DELETE_SUCCESS';
									}
									standards.callFunction('_createMessageBox',{ msg: msgs })
									_resetForm( module.getForm() )
									var grdAffiliate = Ext.getCmp( 'gridHistory' + module ).store.load();
								}
								,failure: function(){
									Ext.MessageBox.alert('Status','There was an error while sending reset password link. Please try again later.');
								}

							}); */

						}
					}
				})
			}

			function _confirmedDeleteFunction(data, row) {
				Ext.Ajax.request({
					url: route + 'deleteItemRecord',
					params: {
						idItem: data.idItem,
						itemName: data.itemName
					},
					method: 'post',
					success: function (response, option) {
						var msgs = '';
						var responseValue = Ext.decode(response.responseText);

						// console.log ( responseValue );
						// console.log ( responseValue.match );

						if (parseInt(responseValue.match) == 0) {
							msgs = 'The item can no longer be deleted. The item is connected with other transactions.';
						} else {
							msgs = 'DELETE_SUCCESS';
						}
						standards.callFunction('_createMessageBox', {
							msg: msgs
						})
						_resetForm(module.getForm())
						var grdAffiliate = Ext.getCmp('gridHistory' + module).store.load();
					},
					failure: function () {
						Ext.MessageBox.alert('Status', 'There was an error while sending reset password link. Please try again later.');
					}

				});
			}
		}

		function _deleteItemPriceHistory(data, row) {
			var storePriceHistoryGrid = Ext.getCmp('grdItemPriceHistory' + module).store
			if (data.idItem != 0) {
				standards.callFunction('_createMessageBox', {
					msg: 'Saved price is not allowed to be deleted.'
				})
				return false;
			} else storePriceHistoryGrid.removeAt(row)
		}


		function _resetForm(form) {

			// console.log( form)
			form.reset(form);

			// 'mainFormID' + module

			onEdit = 0;
			var grdAffiliate = Ext.getCmp('grdAffiliate' + module);
			grdAffiliate.store.removeAll()
			grdAffiliate.store.load()

			Ext.getCmp('gridHistory' + module).store.load()
			var searchItemPriceHistoryCmb = Ext.getCmp('searchItemPriceHistoryCmb' + module);
			if (typeof (searchItemPriceHistoryCmb) !== 'undefined') {
				searchItemPriceHistoryCmb.reset();
				searchItemPriceHistoryCmb.store.load({});
			}
			var grdItemPriceHistoryClearer = Ext.getCmp('grdItemPriceHistory' + module);
			if (typeof (grdItemPriceHistoryClearer) !== 'undefined') {
				grdItemPriceHistoryClearer.store.removeAll();
				grdItemPriceHistoryClearer.store.load({});
				Ext.getCmp('sdate' + module).reset();
				Ext.getCmp('edate' + module).reset();
				Ext.getCmp('mainFormID' + module).reset();
			}

		}

		return {
			initMethod: function (config) {
				route = config.route;
				baseurl = config.baseurl;
				module = config.module;
				canDelete = config.canDelete;
				canPrint = config.canPrint;
				pageTitle = config.pageTitle;
				isGae = config.isGae;
				canEdit = config.canEdit;
				idModule = config.idmodule;
				selRec = config.selRec;
				componentCalling = config.componentCalling;
				return _mainPanel(config);
			}
		}
	}
}