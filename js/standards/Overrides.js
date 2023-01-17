/** Overrides
  * [Developer]
  * In Memory: Salrio T. Salcedo
  * Date Created: September 2015
  
  * [Description]
	Overrides Ext native behavior
	Contains : Overrides, Defines, Applies and Prototypes
  
  * [Modification]
    Almost each and every single day :D
	Except when Im gone :(

	*[ MODIFED ]
	  * [Developer]
	  * : Roj Zim Jamil A. Janubas
	  * Date Modified: 2017

	  * [Modification]
		  continues because I now exist. :D HAHAHA!

	*[ MODIFED ]
	  * [Developer]
	  * Name: Marie Danilene Bulosan
	  * Date Modified: Dec 13 2019

	  * [Modification]
		  Add dateTimeParse and dateParse function that return moment parse date
		  Add resetGrid to reset grid data
		  Add setGridData set data to row
		  Add resetInputValidation reset input validation
 **/

var overrides = function(){
	var baseurl;
	var moduleArr = Array();
	
	function _override_formField(){
		/** Added customize property( origReadOnly ) to all ext fields.
			The property flags readOnly attribute after disabling all fields upon editing used records.
		**/
		Ext.override( Ext.form.field.Base, {
			origReadOnly	: null
			,initComponent	: function() {
				this.callParent( arguments );
				this.origReadOnly = this.readOnly;
			}
		} );
	}
	
	function _override_textField(){
		Ext.override( Ext.form.field.Text, {
			/** Sets trimmed numeric values **/
			setValue		: function( value ){
				if( this.isNumber ){
					var trim	= ( Number( String( this.removeComma( value ) ) ) >= 0 )? this.removeComma( value ) : 0;
					value		= this.getCurrency() + this.numberFormat( trim );
				}
				Ext.form.field.Text.superclass.setValue.call( this, value );
			}
			
			/** Removes all characters except numeric and period(.) **/
			,onFocus		: function() {
				if( this.isNumber ){
					if( !this.readOnly ){
						var trim 	= this.removeCurrency( this.removeComma( this.getValue() ) )
							,value	= ( parseFloat( trim, 10 ) == 0 )? '' : trim;
						Ext.form.field.Text.superclass.setValue.call( this, value );
					}
				}
				
				this.callParent( arguments );
			}
			
			/** Returns currency symbol( if any ) and commas **/
			,onBlur			: function(){
				if( this.isNumber && !this.readOnly ){
					var value = this.removeComma( this.getValue() );
					this.setValue( this.numberFormat( this.removeCurrency( value ) ) );
				}
				this.callParent( arguments );
			}
			
			/** Returns 0 if value is undefined **/
			,getValue		: function() {
				var value = this.callParent( arguments );
				if( this.isNumber ){
					if( value == '' ) value = 0;
					else value = this.removeCurrency( this.removeComma( value ) );
					
					value = parseFloat( value );
				}
				return value;
			}
			
			/** Trims currency symbol and comma before submitting **/
			,getSubmitValue	: function(){
				var value;
				if( this.isNumber ){
					if( this.hasCurrency ) value = this.removeCurrency( this.removeComma( this.getValue() ) );
					else value = this.removeComma( this.getValue() );
				}
				else value = this.getValue();

				return value;
			}
			
			/** Returns currency symbol( if any ) **/
			,getCurrency	: function(){
				return ( this.hasCurrency )? Ext.getConstant( 'DEF_CURRENCY' ) + ' ' : '';
			}
			
			/** Removes currency symbol( if any ) **/
			,removeCurrency	: function( value ){
				return value.toString().replace( this.getCurrency(), '' );
			}
			
			/** Removes comma **/
			,removeComma	: function( value ){
				return ( value )? value.toString().replace( /,/g, '' ) : value;
			}
			
			/** Sets numeric format **/
			,numberFormat	: function( value ){
				if(this.hasComma) return Ext.util.Format.number( parseFloat( value, 10 ), ( this.isDecimal? '0,000.00' : '0,000' ) );
				else return Ext.util.Format.number( parseFloat( value, 10 ), ( this.isDecimal? '0.00' : '0' ) );
			}
			
			/** Marks invalid on required numeric fields with values equal to 0 **/
			,getErrors		: function( value ) {
				if( this.isNumber ){
					if( !this.allowBlank ){
						if( parseFloat( this.getSubmitValue(), 10 ) == 0 ){
							return ['must be greater than 0'];
						}
					}
				}
				return this.callParent( arguments );
			}
		} );
	}
	
	function _override_checkBox(){
		
		/** Submits either 1 or 0( checked and unchecked respectively ).
			Original config does not submit anything for unchecked values.
		**/
		Ext.override( Ext.form.field.Checkbox, {
			getSubmitValue	: function(){
				return +this.value;
			}
		} );
	}
	
	function _override_gridColumn(){
	
		/** Sorts data in respect to the selected column **/
		Ext.override( Ext.grid.column.Column, {
			doSort:function( order ){
				var grid			= this.up( 'grid' )
					,store			= grid.store
					,toolbarItems	= grid.dockedItems.keys
					,hasPaging		= false;
				
				/** Search for any paging toolbar on a grid **/
				for( var x in toolbarItems ){
					if( toolbarItems[x].indexOf( 'pagingToolBar_' ) != -1 ){
						var limit = Ext.getCmp( toolbarItems[x] ).items.items[0].getValue();
						if( limit < store.getTotalCount() ){
							hasPaging = true;
						}
					}
				}
				
				/** Requery if total number of pages exceeds 1 **/
				if( hasPaging && store.getCount() > 0 ){
					store.sort( {
						property	: this.dataIndex
						,direction	: order
						,sorterFn	: function( val ) {
							return val;
						}
					} );
					
					store.load( {
						params	:{
							limit	: limit
						}
					} );
				}
				else{
					this.callParent( arguments );
				}
			}
		} );
		
		/** xtype : 'numbercolumn' **/
		Ext.override( Ext.grid.column.Number, {
			align	: 'right'
		} );
	}
	
	function _override_cellEdit(){
		
		/** Locks all grid editor if record is already used  **/
		Ext.override( Ext.grid.plugin.CellEditing, {
			beforeEdit	: function() {
				if( this.grid.up( 'form' ) ){
					return !this.grid.up( 'form' ).form.used;
				}
			}
		} );
	}
	
	function _override_formPanel(){
		Ext.override( Ext.form.Basic, {
			submitEmptyText	: false
			,modify			: false
			,onEdit			: false
			,used			: false	 /** also used as datafilter for report panels **/
			,wasUsed		: false
			,dirty			: false
			,dateModified	: null
			,reset			: function(){
				var module        = this.owner.module;
				this.modify 	  = false;
				this.onEdit 	  = false;
				this.used 	  	  = false;
				this.dirty 	  	  = false;
				this.dateModified = null;
				
				if( mainPanel = Ext.getCmp( 'mainPanel' + module ) ){
					if( saveButton = module.getButton( 'save' ) ){
						saveButton.setVisible( mainPanel.config.canSave );
					}
					
					if( mainPanel.moduleType == 'form' ){
						if( btnExcel = module.getButton( 'excel' ) ){
							btnExcel.setVisible( false );
						}
						if( btnPDF = module.getButton( 'pdf' ) ){
							btnPDF.setVisible( false );
						}
					}
				}
				this.setReadOnlyWhenUsed( module );
				
				/** check if header reference fields are available 
					if yes then perform reset. using afterrender listener
				**/
				var ref = Ext.dom.Query.select( '.container_reference' + module );
				if( ref.length > 0 ){
					var refID = ref[0].id;
					Ext.getCmp( refID ).invoiceID = 0;
					Ext.getCmp( refID ).dup_save = 0;
					Ext.getCmp( refID ).isOtherTransaction = 0;
					Ext.getCmp( refID ).fireEvent( 'afterrender' );
				
					if( Ext.getCmp( refID ).ctype == 'transactionHeaderOnly' ){
						Ext.getCmp( 'costcenterID' + module ).setReadOnly( false );
					}
				}
				
				this.callParent( arguments );
			}
			,retrieveData	: function( data ){
				var me		 		= this
					,module   		= me.owner.module
					,formID   		= me.owner.id
					,onEdit   		= ( typeof data.onEdit   != 'undefined' )? data.onEdit   : true
					,goToForm 		= ( typeof data.goToForm != 'undefined' )? data.goToForm : true
					,excludes 		= ( typeof data.excludes != 'undefined' )? data.excludes : []
					,returnAll 		= ( typeof data.returnAll != 'undefined' )? data.returnAll : false
					,match    		= 0
					,isAutoRetrieve	= ( typeof data.isAutoRetrieve != 'undefined' )? data.isAutoRetrieve : true;
				Ext.Ajax.request( {
					url				: data.url
					,params			: data.params
					,method			:'post'
					,success		: function ( response ){
						var resp = Ext.decode( response.responseText )
							,view;
					
						if( typeof resp.view != 'undefined' ) view = ( typeof resp.view[0] != 'undefined' )? resp.view[0] : null;
						else view = null;
						
						/** this piece of code will identify if the form will be reseted 
						and perform afterrenders of refnumber standard and grid journal entry **/
						if( Ext.getCmp( 'mainPanel' + module ) ) Ext.getCmp( 'mainPanel' + module ).invoiceIDFromOtherTransaction = 0;
						if( onEdit ){
							if( typeof resp.match != 'undefined' ){
								/** 
								**	0 = ok
								**	1 = record not found
								**	2 = record used
								**	3 = record cancelled
								**/
								match   = parseInt( resp.match, 10 );
								me.used = false;
								if( match == 1 ){
									standards.callFunction( '_createMessageBox', {
										msg : 'EDIT_UNABLE'
									} );
									
									if( typeof data.successNotFound != 'undefined' ) data.successNotFound();
									
									return;
								}
								else if( match == 2 ){
									standards.callFunction( '_createMessageBox', {
										msg : 'EDIT_USED'
									} );
									
									me.used = me.wasUsed = true;
								} else if( match == 3 ) {
									standards.callFunction( '_createMessageBox', {
										msg : 'The record is already cancelled and cannot be modified.'
									} );
									
									me.used = me.wasUsed = true;
								}
								
								me.setReadOnlyWhenUsed( module );
							}
							me.onEdit       = true;
							me.dateModified = ( view )? view.dateModified : null;
						}
						
						if( view ){
							if(isAutoRetrieve)
								me.setData( view, excludes, me.owner, resp );
							
							if( goToForm ){
								standards.callFunction( '_goToForm', {
									module			: module
									,otherFormID	: formID
									,hasFormPDF 	: ( typeof data.hasFormPDF != 'undefined' )? data.hasFormPDF : false
									,hasFormExcel 	: ( typeof data.hasFormExcel != 'undefined' )? data.hasFormExcel : false
								} );
							}
							else{
								if( saveButton    = module.getButton( 'save' ) ){
									var canEdit   = false;
									if( mainPanel = Ext.getCmp( 'mainPanel' + module ) ){
										canEdit   = mainPanel.config.canEdit;
									}
									saveButton.setVisible( ( me.used )? false : canEdit );
								}
							}
							
							if( typeof data.success != 'undefined' ){
								data.success( view, match, resp );
							}
							
							/** perform auto validate after retrieve all data **/
							Ext.getCmp(formID).fireEvent('fieldvaliditychange');
							if( typeof me.owner.extraFormRetrieveFunction === 'function' ){
								me.owner.extraFormRetrieveFunction( view );
							}
						}
					}
				} );
			}
			,setData 				: function( view, excludes, form, result ){
				var keys 		= Object.keys( view );
				var module  	= this.owner.module;
				
				/** get unique value **/
				var keys = Ext.Array.difference( keys, excludes );
				
				for( var x in keys ){
					if( Ext.getCmp( keys[x] + module ) ){
						var field = Ext.getCmp( keys[x] + module );
						if( field.isFormField ){
							if( field.xtype == 'combobox' ){
								field.store.load( {
									callback: this.setValueCombobox( field, view[keys[x]] )
								} );
							}
							else{
								field.setValue( view[keys[x]] );
							}
						}
					}
				}
			}
			,setValueCombobox		: function( combo, value ){
				return function() {
					
					if( combo.stringValue ) {
						combo.setValue( value ); 
					} else {
						combo.setValue( parseInt( value, 10 ) );
					}
				};
			}
			,setReadOnlyWhenUsed	: function( module ){
				if( this.wasUsed ){
					/** all fields **/
					for( x in field = module.getForm().getFields().items ){
						if( field[x].cls != 'notIncludeAutoReadOnly' ){
							if( !field[x].origReadOnly ){
								if( typeof field[x].setReadOnly === "function" ) field[x].setReadOnly( this.used );
								else console.warn( "Process abused" );
							}
						}
					}
					
					/** all action columns **/
					/** check first grid history is in the form 
						if yes dont hide action column
						else hide action column **/
					
					if( Ext.getCmp( 'btnList' + module ) ){
						for( x in grid = Ext.ComponentQuery.query( '#' + module.getForm( true ).id + ' grid' ) ){
							for( y in column = grid[x].columns ){
								if( column[y].xtype == 'actioncolumn' ){
									column[y].setVisible( !this.used );
								}
							}
						}
					}
					
					/** all buttons **/
					for( x in button = Ext.ComponentQuery.query( '#' + module.getForm( true ).id + ' button') ){
						
						if( button[x] != module.getButton( 'pdf' ) && button[x].cls == 'notIncludeAutoReadOnly' ){
							if(button[x].xtype == 'button')
								button[x].setVisible( !this.used );
						}
					}
					
					if( !this.used ){
						this.wasUsed = this.used;
					}
				}
			}
		} );
	}
	
	function _override_formSubmit(){
		Ext.override( Ext.form.action.Submit, {
			waitTitle				: "Please wait"
			,waitMsg				: "Saving data..."
			,submitEmptyText		: false
			,hasConfirmedCanceled	: false
			,doSubmit				: function() {
				/** start
					check if header reference fields are available 
					if yes then perform confirm if saved as canceled **/
				var thisForm	= this
					,ref		= Ext.dom.Query.select( '.container_reference' + thisForm.form.owner.module );
				if( ref.length > 0 && Ext.getCmp( 'status' + thisForm.form.owner.module ) ){
					if( Ext.getCmp( 'status' + thisForm.form.owner.module ).getValue() && !thisForm.hasConfirmedCanceled ){
						standards.callFunction( '_createMessageBox', {
							msg		: 'CANCEL_RECORD'
							,action	: 'confirm'
							,fn		: function( btn ){
								if( btn == 'yes' ){
									thisForm.hasConfirmedCanceled = true;
									Ext.getBody().mask( thisForm.waitMsg );
									thisForm.doSubmit();
								}
							}
						} );
						return false;			
					}
				}
				thisForm.hasConfirmedCanceled = false;
				/** end check if header reference fields are available **/
				
				
				var ajaxOptions = 	Ext.apply( this.createCallback(), {
					url			: this.getUrl()
					,method		: this.getMethod()
					,headers	: this.headers
				} );
				
				var jsonSubmit	= this.jsonSubmit || this.form.jsonSubmit
					,paramsProp	= jsonSubmit ? 'jsonData' : 'params'
					,formInfo;

				if( this.form.hasUpload() ){
					formInfo				= this.buildForm();
					ajaxOptions.form		= formInfo.formEl;
					ajaxOptions.isUpload	= true;
				} 
				else{
					ajaxOptions[paramsProp] = this.getParams( jsonSubmit );
				}
				
				ajaxOptions['noMask'] = true;
				Ext.Ajax.request( ajaxOptions );
				
				if( formInfo ){
					this.cleanup( formInfo );
				}
			}
			,getParams				: function(){
				var overrideParams 		= Ext.ifUndefined( this.form.owner.overrideParams, true );
				
				if( overrideParams ){
					var params 				= this.getGridParams( this, this.callParent( arguments ) )
						,useStandard 		= this.form.owner.useStandardAttachmentSaving;
					
					params['modify'] 		= ( this.form.modify )? 1 : 0;
					params['onEdit'] 		= ( this.form.onEdit )? 1 : 0;
					params['dateModified'] 	= this.form.dateModified;
					params['module']		= this.form.owner.module;
					params['idmodule']		= ( ( Ext.getCmp( 'mainPanel'+params['module'] ) )? Ext.getCmp( 'mainPanel'+params['module'] ).config.idmodule : null );
					
					/** included forms **/
					for( var x in includedFormIDS = this.form.owner.includedFormOnSubmit ){
						var includedForm   = Ext.getCmp( includedFormIDS[x] );
						var fieldParams    = includedForm.form.getValues( false, false, includedForm.submitEmptyText !== false, includedForm.jsonSubmit, true );
						var includedFields = Ext.apply( {}, fieldParams, includedForm.callParent() );
						
						for( var y in includedFields ){
							params[y] = includedFields[y];
						}
						
						params = this.getGridParams( includedForm, params );
					}
					
					return params;
				}
				else return this.callParent( arguments );
			}
			,getGridParams			: function( form, params ){
				var owner			= form.form.owner
					,autoGridPush	= ( typeof owner.autoGridPush != 'undefined' )? owner.autoGridPush : false;
				
				if( autoGridPush ){
					var grids	= Ext.ComponentQuery.query( '#' + owner.id + ' grid[condition!=""]' );
					for( var x in grids ){
						var store		= grids[x].store
							,container	= new Array();
						
						for( var y = 0; y < store.getCount(); y++ ){
							var dataStore = store.getAt(y).data;
							if( eval( grids[x].condition ) ){
								delete dataStore.selected;
								container.push( dataStore );
							}
						}
						params['container_' + owner.id + '_' + x] = Ext.encode( container );
					}
				}
				
				return params;
			}
			,onSuccess				: function( response ){
				var res = Ext.decode( response.responseText );
				if( res.match == 13 ){
					standards.callFunction( '_createMessageBox' ,{
						msg	: 'Unable to save this record. ' + res.month + ' is already closed.'
					} );
					return false;
				}
				this.callParent( arguments );
			}
			,failure				:function(){
				standards.callFunction( '_createMessageBox' ,{
					msg	: 'AJAX_FAILURE'
				} );
			}
		} );
	}
	
	function _override_AJAX( initHeader ){
		Ext.override( Ext.Ajax, {
			isSynchronous		: false
			,promptOnFaluire	: true
			,request			: function( config ){
				this.promptOnFaluire = typeof config.promptOnFaluire != 'undefined' ? config.promptOnFaluire : this.promptOnFaluire;
				
				this.defaultHeaders = {'initHeader' : initHeader};
				
				if( typeof config.action == 'undefined' && typeof config.form == 'undefined' ){
					var noMask  = ( typeof config.noMask != 'undefined' )? config.noMask : false;
					if( !noMask ){
						var msg = ( typeof config.msg != 'undefined' )? config.msg : "Please wait";
						Ext.getBody().mask( msg );
					}
				}
				this.callParent( arguments );
			}
			,onComplete			: function( request ){
				var result		= ( !request.timedout && request.xhr.status )? this.parseStatus( request.xhr.status ) : null
					,success	= ( !request.timedout ) ? result.success : null;
				
				if( !success && this.promptOnFaluire ){
					if( typeof request.options != 'undefined' ){
						if( typeof request.options.failure == 'undefined' ){
							standards.callFunction( '_createMessageBox', {
								msg	: 'AJAX_FAILURE'
							} );
							
							if( typeof request.options.afterFailure != 'undefined' ){
								request.options.afterFailure();
							}
						}
					}
				}
				Ext.getBody().unmask();
				
				
				this.callParent( arguments );
			}
		} );
	}
	
	function _override_ajaxTimeOut(){
		Ext.override( Ext.data.proxy.Ajax, {
			timeout	: 100000
		} );
		
		Ext.override( Ext.form.action.Action, {
			timeout	: 100
		} );
	}
	
	function _override_store(){
		Ext.override( Ext.data.Store, {
			retain1 : function(){
				this.removeAll();
				this.add( {} );
			}
		} );
	}
	
	function _override_pagingTbar(){
		Ext.override( Ext.toolbar.Paging, {
			doRefresh	: function(){
				var store		= this.store
					,limit		= this.items.items[0]
					,pageSize	= Ext.getConstant( 'DEF_PAGE_SIZE' );
				
				store.currentPage = 1;
				store.proxy.extraParams.limit = pageSize;
				limit.setValue( pageSize );
				return true;
			}
		} );
	}
	
	function _override_timeField(){
		Ext.override( Ext.form.field.Time, {
			getSubmitValue	: function(){
				if( value = this.getValue() ){
					var hour	= value.getHours()
						,mins	= value.getMinutes()
						,zero1	= ( parseInt( hour, 10 ) < 10 )? '0' : ''
						,zero2	= ( parseInt( mins, 10 ) < 10 )? '0' : '';
					
					return zero1 + parseInt( hour, 10 ) + ':' + zero2 + parseInt( mins, 10 ) + ':00';
				}
			}
		} );
	}
	
	function _define_pagingComboPicker(){
		Ext.define( 'define_pagingComboPicker', {
			extend			: 'Ext.toolbar.Paging'
			,getPagingItems	: function() {
				return [
					{	itemId		: 'first'
						,hidden		: true
					}
					,{  itemId		: 'prev'
						,hidden		: true
					}
					,{  xtype		: 'numberfield'
						,itemId		: 'inputItem'
						,hidden		: true             
					}
					,{  xtype		: 'label'
						,itemId		: 'afterTextItem'
						,hidden		: true
					}
					,{  itemId		: 'next'
						,disabled	: false
						,scope		: this
						,handler	: this.moveNext
						,text		: 'Load more'
						,width		: '100%'
					}
					,{  itemId  	: 'last'
						,hidden		: true
					}
					,{  itemId		: 'refresh'
						,hidden		: true
					}
				];
			}
		} );
	}
	
	function _define_pagingTbar(){
		Ext.define( 'define_pagingTbar', {
			extend			: 'Ext.toolbar.Paging'
			,dock			: 'bottom'
			,displayInfo	: true
			,getPagingItems	: function(){
				var items = this.callParent( arguments );
				var store = this.store;
				
				/** change iconCls for buttons to bootstap **/
				items[0].iconCls	= 'glyphicon glyphicon-step-backward';
				items[1].iconCls	= 'glyphicon glyphicon-chevron-left';
				items[7].iconCls	= 'glyphicon glyphicon-chevron-right';
				items[8].iconCls	= 'glyphicon glyphicon-step-forward';
				items[10].iconCls	= 'glyphicon glyphicon-refresh';
				
				items.unshift(
					standards.callFunction(	'_createTextField', {
						fieldLabel			: 'Number of rows per page'
						,labelSeparator		: ''
						,number				: true
						,notDeci			: true
						,submitValue		: false
						,value				: Ext.getConstant( 'DEF_PAGE_SIZE' )
						,labelWidth			: 180
						,width				: 225
						,style				: 'margin-left:5px'
						,noEnterListeners	: true
						,listeners			: {
							change	: function(){
								var size;
								/** get field value **/
								size = parseInt( this.value, 10 );
								/** sets store page to 1 **/
								if( size ){
									store.currentPage	= 1;
									/** sets store size to field value **/
									store.pageSize		= size;
									/** loads the store **/
									store.load( {
										params	: {
											limit	: size
										}
									} );
								}
							}
							,blur	: function( me ){
								/** resets limit field **/
								if( me.value == '' || parseInt( me.value, 10 ) == 0 ){
									me.reset();
								}
							}
						}
					} )
				);
				
				return items;
			}
		} );
	}
	
	function _define_numberField(){
		Ext.define( 'widget.numericfield', {
			extend					: 'Ext.form.field.Number'
			,currencySymbol			: Ext.getConstant('DEF_CURRENCY')
			,thousandSeparator		: ','
			,alwaysDisplayDecimals	: true
			,fieldStyle				: 'text-align: right;'
			,initComponent			: function(){
				if(this.useThousandSeparator && this.decimalSeparator == ',' && this.thousandSeparator == ',') this.thousandSeparator = '.';
				else if(this.allowDecimals && this.thousandSeparator == '.' && this.decimalSeparator == '.') this.decimalSeparator = ',';
				
				this.callParent(arguments);
			}
			,setValue				: function( value ){
				widget.numericfield.superclass.setValue.call(this, value != null ? value.toString().replace('.', this.decimalSeparator) : value);
				
				this.setRawValue( this.getFormattedValue( this.getValue() ) );
			}
			,getFormattedValue		: function( value ){
				if( Ext.isEmpty( value ) || !this.hasFormat() ) return value;
				else{
					var neg = null;
					
					value = (neg = value < 0) ? value * -1 : value;
					value = this.allowDecimals && this.alwaysDisplayDecimals ? value.toFixed( this.decimalPrecision ) : value;
					
					if( this.useThousandSeparator ){
						if(this.useThousandSeparator && Ext.isEmpty( this.thousandSeparator ) ) throw('NumberFormatException: invalid thousandSeparator, property must has a valid character.');
						
						if( this.thousandSeparator == this.decimalSeparator ) throw ('NumberFormatException: invalid thousandSeparator, thousand separator must be different from decimalSeparator.');
						
						value = value.toString();
						
						var ps = value.split( '.' );
						ps[1] = ps[1] ? ps[1] : null;
						
						var whole = ps[0];
						
						var r = /(\d+)(\d{3})/;
						
						var ts = this.thousandSeparator;
						
						while( r.test( whole ) )
							whole = whole.replace( r, '$1' + ts + '$2' );
						
						value = whole + ( ps[1] ? this.decimalSeparator + ps[1] : '' );
					}
					
					return Ext.String.format( '{0}{1}{2}', ( neg ? '-' : '' ), ( Ext.isEmpty( this.currencySymbol )? '' : this.currencySymbol + ' ' ), value );
				}
			}
			/**
			 * overrides parseValue to remove the format applied by this class
			 */
			,parseValue			: function( value ){
				//Replace the currency symbol and thousand separator
				return widget.numericfield.superclass.parseValue.call( this, this.removeFormat( value ) );
			}
			/**
			 * Remove only the format added by this class to let the superclass validate with it's rules.
			 * @param {Object} value
			 */
			,removeFormat		: function( value ){
				if ( Ext.isEmpty(value) || !this.hasFormat() ) 
					return value;
				else{
					value = value.toString().replace( this.currencySymbol + ' ', '' );
					
					value = this.useThousandSeparator ? value.replace( new RegExp( '[' + this.thousandSeparator + ']', 'g' ), '' ) : value;
					
					return value;
				}
			}
			/**
			 * Remove the format before validating the the value.
			 * @param {Number} value
			 */
			,getErrors			: function( value ){
				return widget.numericfield.superclass.getErrors.call( this, this.removeFormat( value ) );
			}
			,hasFormat			: function(){
				return this.decimalSeparator != '.' || ( this.useThousandSeparator == true && this.getRawValue() != null ) || !Ext.isEmpty( this.currencySymbol ) || this.alwaysDisplayDecimals;
			}
			/**
			 * Display the numeric value with the fixed decimal precision and without the format using the setRawValue, don't need to do a setValue because we don't want a double
			 * formatting and process of the value because beforeBlur perform a getRawValue and then a setValue.
			 */
			,onFocus			: function(){
				if( !this.getValue() && !this.readOnly ){
					this.setRawValue( '' );
				}
				
				if( !this.readOnly ){
					this.setRawValue( this.removeFormat( this.getRawValue() ) );
				}
				
				this.callParent( arguments );
			}
			,onBlur				: function(){
				if( !this.getValue() && !this.readOnly ){
					this.setRawValue( 0 );
				}
				
				if( !this.readOnly ){
					this.setRawValue( this.removeFormat( this.getRawValue() ) );
				}
				
				this.callParent( arguments );
			}
		} );
	}
	
	function _define_negativeFormat(){
		Ext.define( 'RevRec.util.Format', {
			override					: 'Ext.util.Format'
			,originalNumberFormatter	: Ext.util.Format.number
			,number						: function( v, formatString ) {
				if( v < 0 ) return '-' + this.originalNumberFormatter( v * -1, formatString );
				else return this.originalNumberFormatter( v, formatString );
			}
		} );
	}
	
	function _applies( params ){
		var constantVariables = constants.getConstants( params );
		
		Ext.apply( Ext, {
			getCurrentRow 		: function( id ){
				if( grid = Ext.getCmp( id ) ){
					if( grid.xtype == 'grid' ){
						var store	= grid.store
							,select	= grid.getSelectionModel().getSelection()[0]
							,index  = store.indexOf( select );
						return {
							grid	: grid
							,store	: store
							,index	: index
							,data	: ( index != -1 )? store.getAt( index ).data : {}
						};
					}
				}
				
				return false;
			}
			,ifUndefined 		: function( option1, option2 ){
				return ( typeof option1 != 'undefined' )? option1 : option2;
			}
			,getConstant 		: function( index ){
				return constantVariables[index];
			}
			,objectProtoType	: function( params ){
				for( x in params ){
					this[x] = params[x];
				}
				this.confirmDelete	= function( ajaxParams ){
					standards.callFunction( '_createMessageBox', {
						msg			: 'DELETE_CONFIRM'
						,action		: 'confirm'
						,fn			: function( answer ){
							if( answer == 'yes' ){
								if( Ext.ifUndefined( ajaxParams.autoParams, false ) ){
									var parameters = params;
									for( x in ajaxParams.params ){
										parameters[x] = ajaxParams.params[x];
									}
									delete ajaxParams.params;
									ajaxParams['params'] = parameters;
								}
								
								Ext.Ajax.request( ajaxParams );
							}
						}
					} );
				}
			}
			,date		: function(){
				return moment()
			}
			,toMoment: function(date){
				return moment(date)
			}
			,dateParse			: function(date, from){
				return moment(moment(date, from).format("YYYY-MM-DD HH:mm:ss"))
			}
			,dateTimetoMoment: function(date, time){
				return moment(`${moment(date).format('YYYY-MM-DD')} ${moment(time).format('HH:mm:ss')}`)
			}
			,dateTimeParse : function({date, dformat}, {time, tformat}){
				let dateMoment = Ext.dateParse(date, dformat).format("YYYY-MM-DD")
				let timeMoment = Ext.dateParse(time, tformat).format("HH:mm:ss")
				return moment(`${dateMoment} ${timeMoment}`)
			}
			,resetGrid 			: function(name){
				Ext.getCmp(name).store.removeAll()
				Ext.getCmp(name).store.proxy.extraParams = {}
				Ext.getCmp(name).store.load({})
			}
			,separateDateTime	: function(datetime, format){
				let object = Ext.dateParse(datetime, format)
				let date = Ext.dateParse(object.format("YYYY-MM-DD")).toDate()
				let time = Ext.dateParse(object.format("YYYY-MM-DD HH:mm:ss")).toDate()

				return {
					date,
					time
				}
			}
			,setGridData : function(fields, store, data){
				fields.map(field=>{
					if(typeof field === 'object'){
						if(field.format == 'int'){
							store.set(field.name, parseInt(data.get(field.name), 10))
						}else store.set(field, data.get(field))
					}else store.set(field, data.get(field))
				});
			}
			,isUnique : function (find, items, data, msg){
				let message = typeof msg === 'string' ? msg : 'This item is already selected. Please select another item.'
				if(items.findExact(find,data.getValue()) >= 0){
					data.setValue( null );
					standards.callFunction('_createMessageBox',{ msg: message })
					return false
				}
				return true
			}
			// name - unsa name sa imong input na e required (string: component name)
			// is_notrequired - if e dili required or reqired ang component (boolean)
			// fieldLabel - pangalan sa imong field na re rename (string : field name)
			// from - kung dili sa components <params: name> ang field name (string: companent name)
			// enable - kung gusto nimo i dili i disabled ang input (boolean)
			,resetInputValidation: function(name, is_notrequired, fieldLabel, from, enable){
				let toEnable = Ext.getCmp( name );
				let elem = typeof from === 'undefined'? name : from
				Ext.getCmp( elem ).setFieldLabel(`${fieldLabel}${!is_notrequired? '<span style="color:red;">*</span>': ''}`)
				toEnable.allowBlank = is_notrequired;
				toEnable.setDisabled(enable? false : is_notrequired);
				toEnable.validate();
				toEnable.reset()
			}
			,requestPost(url,params){
				return new Promise((resolve, reject) =>{
					Ext.Ajax.request({
						url : url
						,params : params
						,success : function( response ){
							resolve(response)
						}
						,error : function(error){
							reject(error)
						}
					});
				})
			}
			,valued(name, value){
				if(Ext.getCmp(name)){
					if(typeof value === 'undefined'){
						return Ext.getCmp(name).getValue()
					}else{
						Ext.getCmp(name).setValue(value)
					}
				}
			}
		} );
	}
	
	function _prototypes(){
		String.prototype.getForm = function( getCmpOnly ){
			var myForm  = false;
			if( form   = Ext.getCmp( 'mainFormPanel' + this ) ) myForm = form;
			else myForm = Ext.getCmp( 'mainPanel' + this ).down( 'form' );
			
			if( myForm ) return ( Ext.ifUndefined( getCmpOnly, false ) )? myForm : myForm.getForm();
		};
		
		String.prototype.getButton = function( button ){
			var id = '';
			
			if( button == 'form' ) id	= 'btnForm';
			else if( button == 'list' ) id	= 'btnList';
			else if( button == 'save' ) id	= 'saveButton';
			else if( button == 'reset' ) id	= 'resetButton';
			else if( button == 'excel' ) id	= 'btnExcel';
			else if( button == 'pdf' ) id	= 'btnPDF';
			else id = button;

			if( cmp = Ext.getCmp( id + '' + this ) ) return cmp;
		};
	}
	
	function _define_spinnerTime(){
		
		function valueChange( field, add ){
			var parent = field.up( "container" );
			var isAPM  = ( typeof parent.isAPM != 'undefined' )? parent.isAPM : false;
			
			/** non-APM fields in/decrements digits **/
			if( !field.isAMP ){
				var value = parseInt( field.getValue(), 10 ) + ( add? 1 : -1 );
				
				/** sets limit depending on APM mode **/
				var limit = 99;
				if( isAPM ){
					limit = ( field.myIndex == 1 )? 12 : 59;
				}
				
				if( ( add )? value <= limit : value >= 0 ){
					/** automatically fills zero for 10-less values **/
					if( value < 10 ){
						value = "0" + value;
					}
					field.setValue( value );
				}
			}
			/** APM fields has only 2 valid values **/
			else{
				field.setValue( add? 'PM' : 'AM' );
			}
			field.focus();
		}
		
		
		/** defined textfield as timefield **/
		Ext.define( "define_textField_time", {
			extend				: "Ext.form.field.Text"
			,xtype				: "define_textField_time"
			,selectOnFocus		: true
			,maxLength			: 2
			,maskRe				: /[0-9.]/
			,unBoundFromForm	: true
			,enforceMaxLength	: true
			,onFocus 			: function(){
				if( !this.readOnly ){
					this.up( "container" ).focusedItem = this.myIndex;
					this.selectText();
				}
			}
			,onBlur 			: function(){
				if( !this.isAMP ){
					var parent = this.up( "container" );
					var isAPM  = ( typeof parent.isAPM != 'undefined' )? parent.isAPM : false;
					var value  = parseInt( this.getValue(), 10 );
					
					if( value >= 0 && value <= 9 ){
						this.setValue( "0" + value );
					}
					else if( isAPM ){
						var limit = ( this.myIndex == 1 )? 12 : 59;
						if( value > limit ){
							this.setValue( limit );
						}
					}
				}
			}
			,selectText			: function () {
				/** function for highlighting whole selection **/
				var field = this.inputEl.dom;
				var end   = this.getValue().length;
				if( field.createTextRange ){
					var selRange = field.createTextRange();
					selRange.collapse( true );
					selRange.moveStart( "character", 0 );
					selRange.moveEnd( "character", end );
					selRange.select();
				} 
				else if( field.setSelectionRange ){
					field.setSelectionRange( 0, end );
				}
				else if( field.selectionStart ) {
					field.selectionStart = 0;
					field.selectionEnd   = end;
				}
			}
			,initEditor 		: function() {
				this.callOverridden();
				var doc = this.getDoc();
				Ext.EventManager.on( doc, 'keydown', this.filterKeys, this );
			}
			,filterKeys 		: function( e ) {
				if( parseInt( e.getKey() ) == 38 ){
					valueChange( this, true );
					e.stopEvent();
				}
				else if( parseInt( e.getKey() ) == 40 ){
					valueChange( this, false );
					e.stopEvent();
				}
				else if( ( e.getKey() > 47 && e.getKey() < 58 && !e.shiftKey ) 
					|| e.getKey() == 37
					|| e.getKey() == 39
					|| e.getKey() == 9
					|| e.getKey() == 8
					|| e.getKey() == 46
				){
					/** valid keys **/
					if( this.isAMP ){
						e.stopEvent();
					}
				}
				else{
					e.stopEvent();
				}
			}
			,setEditable		: function( editable ){
				this.setReadOnly(!editable);
			}
			,setValue			: function( value ){
				Ext.form.field.Text.superclass.setValue.call( this, ( parseInt( value, 10 ) < 10? "0" : 0 ) + parseInt( value, 10 ) );
			}
		});
		
		/** defined textfield as separator **/
		Ext.define( "define_textField_separator", {
			extend				: "Ext.form.field.Base"
			,xtype				: "define_textField_separator"
			,fieldStyle			: "border-right:none 0px black; border-left:none 0px black"
			,width				: 14
			,value				: ":"
			,disabled			: true
			,submitValue		: false
			,unBoundFromForm	: true
			,onRender			: function(){
				this.callParent( arguments );
				this.removeCls('x-item-disabled');
			}
		});
		
		/** defined spinner for spinner time **/
		Ext.define( "define_textField_trigger", {
			extend				: "Ext.form.field.Spinner"
			,xtype				: "define_textField_trigger"
			,submitValue		: false
			,repeatTriggerClick	: false
			,unBoundFromForm	: true
			,getSubTplMarkup	: function( values ) {
				var field = Ext.form.field.Base.prototype.getSubTplMarkup.apply( this, arguments );
				
				return '<table id="' + this.id + '-triggerWrap" class="' + Ext.baseCSSPrefix + 'form-trigger-wrap' + values.childElCls + '" cellpadding="0" cellspacing="0" role="presentation">' +
					'<tbody role="presentation">' +
						'<tr role="presentation"><td id="' + this.id + '-inputCell">' + field + '</td>' +
						this.getTriggerMarkup() +
					'</tbody></table>';
			}
			,onRender			: function(){
				this.callParent( arguments );
				Ext.getDom( this.id+'-inputCell' ).hidden = true;
			}
			,onTrigger1Click	: function() {
				if( this.editable ){
					var parent = this.up( "container" );
					var field  = parent.items.items[ parent.focusedItem ];
					valueChange( field, true );
				}
			}
			,onTrigger2Click	: function() {
				if( this.editable ){
					var parent = this.up( "container" );
					var field  = parent.items.items[ parent.focusedItem ];
					valueChange( field, false );
				}
			}
			,setEditable		: function( editable ){
				this.editable = editable;
			}
		} );
		
		
		Ext.define("_define_spinnerTime", {
			extend			: "Ext.Container"
			,xtype			: "_define_spinnerTime"
			,layout			: "column"
			,style			: "margin-bottom:5px"
			,focusedItem	: 1
			,items			: []
			,initItems		: function() {
				var allowBlank = ( typeof this.allowBlank != 'undefined' )? this.allowBlank : true;
				var isAPM	   = ( typeof this.isAPM != 'undefined' )? this.isAPM : false;
				var cmpID 	   = this.id.replace( this.module, '' );
				
				var items = [
					{	html		: ( typeof this.fieldLabel != 'undefined'? this.fieldLabel : 'Label' ) + ( allowBlank? '' : REQ ) + ':'
						,width		: ( typeof this.labelWidth != 'undefined' )? this.labelWidth : 140
						,border		: false
						,style		: 'margin-top:5px'
					}
					,{	xtype		: "define_textField_time"
						,id			: cmpID+"Hours"+this.module
						,name		: cmpID+"Hours"+this.module
						,fieldStyle	: "border-right:none 0px black; text-align: right"
						,value		: "00"
						,myIndex 	: 1
						,width		: ( typeof this.fWidth != 'undefined'? this.fWidth : 28 )
						,readOnly	: ( typeof this.editable != 'undefined' )? !this.editable : false
					}
					,{	xtype		: "define_textField_separator" }
					,{	xtype		: "define_textField_time"
						,id			: cmpID+"Mins"+this.module
						,name		: cmpID+"Mins"+this.module
						,fieldStyle	: "border-left:none 0px black; border-right:none 0px black"
						,value		: "00"
						,myIndex 	: 3
						,width		: isAPM? 29 : ( typeof this.spinnerWidth != 'undefined' )? this.spinnerWidth : 144
						,readOnly	: ( typeof this.editable != 'undefined' )? !this.editable : false
					}
				];
				
				if( isAPM ){
					items.push( {
						xtype		: "define_textField_time"
						,fieldStyle	: "border-left:none 0px black; border-right:none 0px black"
						,value		: "AM"
						,myIndex 	: 4
						,width		: ( typeof this.spinnerWidth != 'undefined' )? this.spinnerWidth : 115
						,editable	: ( typeof this.editable != 'undefined' )? this.editable : true
						,isAMP		: true
					} );
				}
				
				items.push( {
					xtype			: "define_textField_trigger"
					,editable		: ( typeof this.editable != 'undefined' )? this.editable : true
				} );
				
				this.items 			= new Ext.util.AbstractMixedCollection( false, this.getComponentId );
				this.floatingItems 	= new Ext.util.MixedCollection( false, this.getComponentId );
				this.add( items );
				
			}
			,setDisabled	: function( disabled ){
				for( var x in items = this.items.items ){
					items[x].setDisabled( disabled );
				}
				
				if( disabled ){
					items[2].addCls( 'x-item-disabled' );
				}
				else{
					items[2].removeCls( 'x-item-disabled' );
				}
			}
			,setEditable	: function( editable ){
				for( var x in items = this.items.items ){
					try{
						items[x].setEditable( editable );
					}catch( err ){}
				}
			}
			,setValue		: function( hours, mins ){
				var items = this.items.items;
				var isAPM = ( typeof this.isAPM != 'undefined' )? this.isAPM : false;
				
				if( isAPM ){
					if( parseInt( hours, 10 ) > 12 ){
						items[4].setValue( 'PM' );
						hours -= 12;
					}
					else{
						items[4].setValue( 'AM' );
					}
				}
				
				items[1].setValue( ( hours >= 0 && hours <= 9? "0" : "" ) + hours );
				items[3].setValue( ( mins  >= 0 && mins  <= 9? "0" : "" ) + mins );
			}
		} );
		
	}
	
	return {
		applied: function( params ){
			baseurl       	 = params.baseurl;
			Ext.Ajax.timeout = 180000000;
			delete Ext.tip.Tip.prototype.minWidth;
			
			/** APPLIES **/
			_applies( params );
			
			/** PROTOTYPES **/
			_prototypes();
			
			/** OVERRIDES **/
			_override_formField();
			_override_textField();
			_override_checkBox();
			_override_gridColumn();
			_override_cellEdit();
			_override_formPanel();
			_override_formSubmit();
			_override_AJAX( params.initHeader );
			_override_store();
			_override_timeField();
			_override_pagingTbar();
			_override_ajaxTimeOut();
			
			/** DEFINES **/
			_define_pagingComboPicker();
			_define_pagingTbar();
			_define_numberField();
			_define_negativeFormat();
			_define_spinnerTime();
			
		}
	}
}();