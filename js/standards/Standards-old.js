var standards = function(){
	/* Create text field component
		return : Component
		parameters[
			
			PROPERTIES __________________
			isNumber		: [bool] 
								IF TRUE 
									field behaves as numberfield.
									sets initial value either '0'(if isDecimal = true) or '0.00' (if isDecimal = false; default).
									sets text alignment to right.
									mask inputs for non-numeric characters.
								IF FALSE( default )
									the opposite on whats stated above :D
			isDecimal		: [bool] assigns value/input as either float or integer. applicable only if isNumber = true. Default = false
			withREQ			: [bool] concatenates required field's label with * if true. Default = true.
			noEnterListeners: [bool] enables custom keypress when hitting enter key (behaves like tab). Default = false
			
			see property details for other parameters @ ExtDocs
		]
	*/
	
	function _createTextField( params ){
		var isNumber  		= ( typeof params.isNumber != 'undefined' )? params.isNumber : false;
		var isDecimal 		= ( typeof params.isDecimal != 'undefined' )? params.isDecimal : true;
		var hasComma 		= ( typeof params.hasComma != 'undefined' )? params.hasComma : true;
		var allowBlank		= ( typeof params.allowBlank != 'undefined' )? params.allowBlank : true
		var withREQ    		= ( typeof params.withREQ != 'undefined' )? params.withREQ : true;
		var fieldLabel		= ( typeof params.fieldLabel != 'undefined' )? params.fieldLabel + ( !allowBlank? ( withREQ? Ext.getConstant( 'REQ' ) : '' ) : '' ) : null;
		
		return Ext.create( 'Ext.form.field.Text', {
			id					: params.id
			,afterBodyEl		: params.afterBodyEl
			,name				: params.id
			,fieldLabel			: fieldLabel
			,width				: ( typeof params.width != 'undefined' )? params.width : Ext.getConstant( 'DEF_WIDTH' )
			,labelWidth			: ( typeof params.labelWidth != 'undefined' )? params.labelWidth : Ext.getConstant( 'DEF_LABEL_WIDTH' )
			,maxLength			: ( typeof params.maxLength != 'undefined' )? params.maxLength : ( isNumber? 15 : 'undefined' )
			,enforceMaxLength	: true
			,value				: ( isNumber ? ( ( typeof params.value != 'undefined' )? params.value : ( !isDecimal ? '0':'0.00' ) ) : params.value )
			,hidden				: params.hidden
			,style				: params.style
			,labelSeparator 	: ( typeof params.labelSeparator != 'undefined' )? params.labelSeparator : ':'
			,vtype				: params.vtype
			,fieldStyle			: ( typeof params.fieldStyle != 'undefined' )? params.fieldStyle : ( isNumber ? "text-align:right;":"text-align:left;" )
			,maskRe				: ( isNumber ?	/[0-9.]/ : (typeof params.maskRe != 'undefined' ? params.maskRe : /[^^]/)  )
			,regex				: ( typeof params.regex != 'undefined'? params.regex :/\S/)
			,stripCharsRe		: ( typeof params.stripCharsRe != 'undefined'? params.stripCharsRe :null)
			,emptyText			: params.emptyText
			,beforeSubTpl		: params.beforeSubTpl
			,afterSubTpl		: params.afterSubTpl
			,hasfocus			: params.hasfocus
			,enforceMinLength	: params.enforceMinLength
			,minLength			: params.minLength
			,minLengthText		: params.minLengthText
			,msgTarget			: params.msgTarget
			,allowBlank			: ( typeof params.allowBlank != 'undefined' )? params.allowBlank : true
			,readOnly			: ( typeof params.readOnly != 'undefined' )? params.readOnly : false
			,submitValue		: ( typeof params.submitValue != 'undefined' )? params.submitValue : true
			,disabled			: ( typeof params.disabled != 'undefined' )? params.disabled : false
			,inputType			: ( typeof params.inputType != 'undefined' )? params.inputType : 'text'
			,validator			: ( typeof params.validator != 'undefined' )? params.validator : null
			,labelAlign			: ( typeof params.labelAlign != 'undefined' )? params.labelAlign : 'left'
			,enableKeyEvents	: ( typeof params.enableKeyEvents != 'undefined' )? params.enableKeyEvents : false
			,listeners			: ( typeof params.listeners != 'undefined' )? params.listeners : null
			,noEnterListeners 	: ( typeof params.noEnterListeners != 'undefined' )? params.noEnterListeners : false
			,hasCurrency		: ( typeof params.hasCurrency != 'undefined' )? params.hasCurrency : false
			,isNumber			: isNumber
			,isDecimal			: isDecimal
			,hasComma			: hasComma
			,regexText			: params.regexText
			,cls  				: params.cls
			,plugins			: params.plugins
			,tabIndex			: params.tabIndex
		} );
	}
	
	/* Create date field component
		return : Component
		parameters[
			
			PROPERTIES __________________
			value 			: [date] preset value. Default = just now.
			withREQ			: [bool] concatenates required field's label with * if true. Default = true.
			submitFormat 	: [string] value format passed upon form submission. Default = 'Y-m-d'.
			
			see property details for other parameters @ ExtDocs
		]
	*/
	
	function _createDateField( params ){
		var allowBlank = ( typeof params.allowBlank != 'undefined' )? params.allowBlank : true
		var withREQ    = ( typeof params.withREQ != 'undefined' )? params.withREQ : true;
		var fieldLabel = ( typeof params.fieldLabel != 'undefined' )? params.fieldLabel + ( !allowBlank? ( withREQ? Ext.getConstant( 'REQ' ) : '' ) : '' ) : null;
		
		return {
			xtype			: 'datefield'
			,id				: params.id
			,name			: params.id
			,value			: ( typeof params.value != 'undefined' )? params.value : Ext.Date.format(new Date(),'Y-m-d')
			,style			: params.style
			,fieldLabel		: fieldLabel
			,width			: ( typeof params.width != 'undefined' )? params.width : Ext.getConstant( 'DEF_WIDTH' )
			,labelWidth		: ( typeof params.labelWidth != 'undefined' )? params.labelWidth : Ext.getConstant( 'DEF_LABEL_WIDTH' )
			,hidden			: params.hidden
			,maxValue		: params.maxValue
			,minValue		: params.minValue
			,format			: Ext.getConstant( 'DATE_FORMAT' )
			,readOnly		: ( typeof params.readOnly != 'undefined' )? params.readOnly : false
			,submitValue	: ( typeof params.submitValue != 'undefined' )? params.submitValue : true
			,submitFormat	: ( typeof params.submitFormat != 'undefined' )? params.submitFormat : 'Y-m-d'
			,allowBlank 	: allowBlank
			,listeners		: params.listeners
			,fieldStyle 	: ( typeof params.fieldStyle != 'undefined'? params.fieldStyle : '' ) +  "text-align:right;"
			,cls			: params.cls
		}
	}
	
	/* Create combo field component
		return : Component
		parameters[
			
			PROPERTIES __________________
			withREQ			: [bool] concatenates required field's label with * if true. Default = true.
			reQuery			: [bool] deletes last query automatically if set to true. Default = false. 
			
			see property details for other parameters @ ExtDocs
		]
	*/
	
	function _createCombo( params ){
		var reQuery		= ( typeof params.reQuery != 'undefined' )? params.reQuery : true
			,allowBlank = ( typeof params.allowBlank != 'undefined' )? params.allowBlank : true
			,listeners  = ( typeof params.listeners != 'undefined' )? params.listeners : new Array()
			,withREQ    = ( typeof params.withREQ != 'undefined' )? params.withREQ : true
			,fieldLabel	= ( typeof params.fieldLabel != 'undefined' )? params.fieldLabel + ( !allowBlank? ( withREQ? Ext.getConstant( 'REQ' ) : '' ) : '' ) : null;

		if( reQuery ){
			listeners['beforequery'] = function( me ){
				delete me.combo.lastQuery;
			}
		}

		function createPicker(){
			var me = this,
				picker,
				pickerCfg = Ext.apply({
					xtype: 'boundlist',
					pickerField: me,
					selModel: {
						mode: me.multiSelect ? 'SIMPLE' : 'SINGLE'
					},
					floating: true,
					hidden: true,
					store: me.store,
					displayField: me.displayField,
					focusOnToFront: false,
					pageSize: me.pageSize,
					tpl: me.tpl
				}, me.listConfig, me.defaultListConfig);

			picker = me.picker = Ext.widget(pickerCfg);
			if (me.pageSize) {
				picker.pagingToolbar.on('beforechange', me.onPageChange, me);
			}

			me.mon(picker, {
				itemclick: me.onItemClick,
				refresh: me.onListRefresh,
				scope: me
			});

			me.mon(picker.getSelectionModel(), {
				beforeselect: me.onBeforeSelect,
				beforedeselect: me.onBeforeDeselect,
				selectionchange: me.onListSelectionChange,
				scope: me
			});

			return picker;
		}
		
		return Ext.create( 'Ext.form.ComboBox', {
			id					: params.id
			,name				: params.id
			,fieldLabel			: fieldLabel
			,emptyText			: ( typeof params.emptyText != 'undefined' )? params.emptyText : (typeof params.fieldLabel != 'undefined'? 'Select '+params.fieldLabel.toLowerCase() + '...': '')
			,store				: params.store
			,valueField			: ( typeof params.valueField != 'undefined' )? params.valueField : 'id'
			,displayField		: ( typeof params.displayField != 'undefined' )? params.displayField : 'name'
			,style				: params.style
			,minChars			: 1
			,value				: params.value
			,hidden				: params.hidden
			,cls				: params.cls
			,allowBlank			: allowBlank
			,readOnly			: ( typeof params.readOnly != 'undefined' )? params.readOnly : false
			,width				: ( typeof params.width != 'undefined' )? params.width : Ext.getConstant( 'DEF_WIDTH' )
			,labelWidth			: ( typeof params.labelWidth != 'undefined' )? params.labelWidth : Ext.getConstant( 'DEF_LABEL_WIDTH' )
			,editable			: true
			,typeAhead			: true
			,submitValue		: ( typeof params.submitValue != 'undefined' )? params.submitValue : true
			,forceSelection 	: ( typeof params.forceSelection != 'undefined' )?params.forceSelection : true
			,disabled			: ( typeof params.disabled != 'undefined' )? true : false
			,hideTrigger		: ( typeof params.hideTrigger != 'undefined' )? params.hideTrigger : false
			,enableKeyEvents	: ( typeof params.enableKeyEvents != 'undefined' )? params.enableKeyEvents : false
			,matchFieldWidth	: ( typeof params.matchFieldWidth != 'undefined' )? params.matchFieldWidth : true
			,pageSize			: ( typeof params.pageSize != 'undefined' )? params.pageSize : 0
			,listeners			: listeners
			,multiSelect		: ( typeof params.multiSelect != 'undefined' )? params.multiSelect : false
			,tpl				: ( typeof params.multiSelect != 'undefined' )? new Ext.XTemplate('<tpl for=".">', '<div class="x-boundlist-item">', '<input type="checkbox" /> ', '{'+ (typeof params.displayField != 'undefined' ? params.displayField : 'name') +'}', '</div>', '</tpl>') : false
			,stringValue		: params.stringValue
			,tabIndex			: params.tabIndex
			,reQuery			: reQuery
			,createPicker		: (typeof params.createPicker != 'undefined' ? params.createPicker : createPicker)
		} );
		
	}
	
	/* Create text area component
		return : Component
		parameters[
			
			PROPERTIES __________________
			withREQ			: [bool] concatenates required field's label with * if true. Default = true.
			
			see property details for other parameters @ ExtDocs
		]
	*/
	
	function _createTextArea( params ){
		var allowBlank = ( typeof params.allowBlank != 'undefined' )? params.allowBlank : true
		var withREQ    = ( typeof params.withREQ != 'undefined' )? params.withREQ : true;
		var fieldLabel = ( typeof params.fieldLabel != 'undefined' )? params.fieldLabel + ( !allowBlank? ( withREQ? Ext.getConstant( 'REQ' ) : '' ) : '' ) : null;
		return {
			xtype			: 'textarea'
			,id				: params.id
			,name			: params.id
			,fieldLabel		: fieldLabel
			,width			: ( typeof params.width != 'undefined' )? params.width : Ext.getConstant( 'DEF_WIDTH' )
			,labelWidth		: ( typeof params.labelWidth != 'undefined' )? params.labelWidth : Ext.getConstant( 'DEF_LABEL_WIDTH' )
			,height			: ( typeof params.height != 'undefined' )? params.height : 50
			,minWidth		: params.minWidth
			,maxWidth		: params.maxWidth
			,style			: params.style
			,minHeight		: params.minHeight
			,maxHeight		: params.maxHeight
			,hidden			: params.hidden
			,value			: params.value
			,allowBlank		: allowBlank
			,readOnly		: ( typeof params.readOnly != 'undefined' )? params.readOnly : false
			,submitValue	: ( typeof params.submitValue != 'undefined' )? params.submitValue : true
			,labelAlign		: ( typeof params.labelAlign != 'undefined' )? params.labelAlign: 'left'
			,cls			: params.cls
		}
	}
	
	/* Create check box component
		return : Component
		parameters[
			
			PROPERTIES __________________
			
			see property details for other parameters @ ExtDocs
		]
	*/
	
	function _createCheckField( params ){
		return {
			xtype			: 'checkboxfield'
			,boxLabel		: params.boxLabel
			,fieldLabel		: params.fieldLabel
			,value			: params.value
			,id				: params.id
			,name			: params.id
			,style			: params.style
			,width			: params.width || Ext.getConstant( 'DEF_WIDTH' )
			,handler		: params.handler
			,inputValue		: '1'
			,hidden			: params.hidden
			,listeners		: params.listeners
			,checked		: params.checked
			,submitValue	: ( typeof params.submitValue != 'undefined' ? params.submitValue : true )
			,readOnly		: ( typeof params.readOnly != 'undefined' )? params.readOnly : false
			,labelWidth		: ( typeof params.labelWidth != 'undefined' )? params.labelWidth : Ext.getConstant( 'DEF_LABEL_WIDTH' )
		}
	}
	
	/* Create time field component
		return : Component
		parameters[
			
			PROPERTIES __________________
			withREQ			: [bool] concatenates required field's label with * if true. Default = true.
			
			see property details for other parameters @ ExtDocs
		]
	*/
	
	function _createTimeField( params ){
		var allowBlank	= ( typeof params.allowBlank != 'undefined' )? params.allowBlank : true
			,withREQ	= ( typeof params.withREQ != 'undefined' )? params.withREQ : true
			,fieldLabel	= ( typeof params.fieldLabel != 'undefined' )? params.fieldLabel + ( !allowBlank? ( withREQ? Ext.getConstant( 'REQ' ) : '' ) : '' ) : null;
		
		return {
			xtype				: 'timefield'
			,id					: params.id
			,name				: params.id
			,fieldLabel			: fieldLabel
			,width				: ( typeof params.width != 'undefined' )? params.width : Ext.getConstant( 'DEF_WIDTH' )
			,labelWidth			: ( typeof params.labelWidth != 'undefined' )? params.labelWidth : Ext.getConstant( 'DEF_LABEL_WIDTH' )
			,increment			: ( typeof params.increment != 'undefined' )? params.increment : 15
			,editable			: ( typeof params.editable != 'undefined' )? params.editable : true
			,submitValue		: ( typeof params.submitValue != 'undefined' )? params.submitValue : true
			,allowBlank			: allowBlank
			,maxLength			: params.maxLength
			,minValue			: params.minValue
			,mask				: params.mask
			,enforceMaxLength	: true
			,readOnly			: params.readOnly
			,value				: params.value
			,hidden				: params.hidden
			,listeners			: params.listeners
			,fieldStyle			: 'text-align:right'
			,style				: params.style
			,value				: (typeof params.value != 'undefined' ? params.value : new Date())
			,emptyText			: ( typeof params.emptyText != 'undefined' ? params.emptyText : 'HH:MM PERIOD' )
			,format				: ( typeof params.format != 'undefined' ? params.format : 'h:i A' )
			,altFormats 		: ( typeof params.altFormats != 'undefined'? params.altFormats : 'h:i A' )
		}
	}
	
	/* Create date range
		return : container
		parameters[
			
			PROPERTIES __________________
			sdateID			: [string] id for start date field. Default = 'sdate'+module.
			edateID			: [string] id for end date field. Default = 'edate'+module.
			stimeID			: [string] id for start time field. Default = 'stime'+module.
			etimeID			: [string] id for end time field. Default = 'etime'+module.
			noTime			: [bool] inclusion of time range. Default = false.
			fromFieldLabel	: [string] field label for start date field.
			fromLabelWidth	: [int] label width for start date/time field.
			fromWidth		: [int] width for start date/time field.
			labelWidth		: [int] label width for end date/time field.
			
		]
		import functions[
			
			_createDatefield()
			_createTimefield()
		]
	*/
	
	function _createDateRange( params ){
		var date		= ( typeof params.date != 'undefined' )? params.date : new Date()
			,sdateID	= ( typeof params.sdateID != 'undefined' )? params.sdateID : 'sdate'+params.module
			,edateID	= ( typeof params.edateID != 'undefined' )? params.edateID : 'edate'+params.module
			,items		= new Array()
			,affiliateDate = new Date(Ext.getConstant('AFFILIATEDATESTART'));
		
		/** sets a month behind from current date **/
		if( typeof params.date == 'undefined' ){
			date.setMonth( date.getMonth() - 1 );
			date = Ext.Date.format(date,'Y-m-d');
		}
		
		var oldDate = new Date(date);
		if(affiliateDate > oldDate){
			date = affiliateDate;
		}
		
		var dateItems = [
			/** date field (from) **/
			_createDateField({
				id 			: sdateID
				,fieldLabel	: ( params.noFieldLabel? '' : ( typeof params.fromFieldLabel  !=  'undefined' ) ? params.fromFieldLabel : 'Date' )
				,labelWidth	: ( typeof params.fromLabelWidth  !=  'undefined' ) ? params.fromLabelWidth : Ext.getConstant( 'DEF_LABEL_WIDTH' )
				,width		: ( typeof params.fromWidth       !=  'undefined' ) ? params.fromWidth      : 240
				,value		: date
				,style		: params.styleFrom
				,listeners: {
					change: function(){
						var edate = Ext.getCmp( edateID );
						edate.setMinValue( this.value );
						edate.validate();
						
						if( this.isValid() ){
							if( this.value > edate.value ){
								// edate.setValue( Ext.Date.add(this.value, Ext.Date.MONTH, 1) )
								var d = new Date( this.value );
								d.setDate( d.getDate() + 59 );
								edate.setValue( d );
							}
						}
						
						if( typeof params.listeners != 'undefined' ){
							if( typeof params.listeners.afterChange1 != 'undefined' ){
								if( this.isValid() ){
									params.listeners.afterChange1( this.getValue() );
								}
							}
						}
					}
				}
			})
			
			/** date field (to) **/
			,_createDateField( {
				id 				: edateID
				,style			: 'margin-left:5px'
				,fieldLabel		: 'to'
				,labelWidth		: ( typeof params.labelWidth != 'undefined' )? params.labelWidth : 15
				,width			: ( typeof params.width != 'undefined' )? params.width : 135
				,minValue		: date
				,listeners		: {
					change	: function(){
						var sdate = Ext.getCmp( sdateID );
						
						if( typeof params.listeners != 'undefined' ){
							if( typeof params.listeners.afterChange2 != 'undefined' ){
								if( this.isValid() ){
									params.listeners.afterChange2( this.getValue() );
								}
							}
						}
					}
				}
			} )
		]
		
		if( typeof params.extraComponents != 'undefined' ){
			for( var x in extra = params.extraComponents ){
				dateItems.push( extra[x] );
			}
		}
		
		/** push date range to an array **/
		items.push( {
			xtype	: 'container'
			,layout	: 'column'
			,items  : dateItems
		} );
		
		return {
			xtype	: 'container'
			,id		: ( typeof params.id != 'undefined' )? params.id : 'dateRangeContainer' + params.module
			,hidden	: ( typeof params.hidden != 'undefined' )? params.hidden : false
			,style  : ( typeof params.style != 'undefined' )? params.style : 'margin-bottom:5px'
			,height	: 24
			,items	: items
		}
	}
	
	/* Create date and time fields
		return : container
		parameters[
			
			PROPERTIES __________________
			
			dId			: [string] id for date field. Default = 'tdate'+module.
			dFieldLabel	: [string] field label for date field. Default = 'As of'.
			dLabelWidth	: [int] width label for date field. Default = Ext.getConstant( 'DEF_LABEL_WIDTH' ).
			dWidth		: [int] width for date field. Default = 240.
			dAllowBlank	: [bool] specify if field is required upon submission. Default = false.
			
			tId			: [string] id for time field. Default = 'ttime'+module.
			tstyle		: [string] style applied to time field. Default = margin-left:25px.
			tLabelWidth	: [int] width label for time field. Default = 0.
			tWidth		: [int] width for time field. Default = 115.
			tValue		: [date] preset value for time field. Default = just now.
			
			see property details for other parameters @ ExtDocs
			
		]
		import functions[
			
			_createDatefield()
			_createTimefield()
		]
	*/
	
	function _createDateTime( params ){
		return {
			xtype:'container'
			,layout:'column'
			,style:	( typeof params.style != 'undefined' )? params.style : 'margin-bottom:5px'
			,items:[
				/** create date field **/
				_createDateField( {
					id					: ( typeof params.dId != 'undefined' )? params.dId : 'tdate' + params.module
					,style				: params.dStyle
					,fieldLabel			: ( typeof params.dFieldLabel != 'undefined' )? params.dFieldLabel : 'As of'
					,labelWidth			: ( typeof params.dLabelWidth !=  'undefined' ) ? params.dLabelWidth : Ext.getConstant( 'DEF_LABEL_WIDTH' )
					,width				: ( typeof params.dWidth      !=  'undefined' ) ? params.dWidth      : 240
					,allowBlank			: params.dAllowBlank
					,listeners			: (	typeof params.dlistener  != 'undefined' )? params.dlistener : {}
					,maxValue			: params.maxValue
					,minValue			: params.minValue
					,value				: ( typeof params.dValue != 'undefined' ) ? params.dValue : new Date()
				} )
				/** create time field **/
				,_createTimeField( {
					id					: ( typeof params.tId != 'undefined' )? params.tId : 'ttime' + params.module
					,style				: ( typeof params.tstyle != 'undefined' )? params.tstyle : 'margin-left:25px'
					,labelWidth			: ( typeof params.tLabelWidth != 'undefined' )? params.tLabelWidth : 0
					,width				: ( typeof params.tWidth != 'undefined' )? params.tWidth : 105
					,value				: ( typeof params.tValue != 'undefined' )? params.tValue : new Date()
					,listeners			: (	typeof params.tlistener  != 'undefined' )? params.tlistener : {}
				} )
			]
		};
	}
	
	/* Create action column ( !params.module )
		return : component
		parameters[
			
			PROPERTIES __________________
			icon			: [string] icon displayed.
			icon2			: [string] alternative icon displayed whenever condition is defined and met.
			tooltip			: [string] tooltip displayed.
			tooltip2		: [string] alternative tooltip displayed whenever condition is defined and met.
			canDelete		: [bool] flag in which determines whether the action column is clickable or not. Default = true
			
		]
	*/
	
	function _createActionColumn( params ){
		var active	= ( typeof params.canDelete != 'undefined' ? params.canDelete : true );
		
		return Ext.create( 'Ext.grid.column.Column', {
			width					: ( typeof params.width != 'undefined'? params.width : 40 )
			,xtype					: 'actioncolumn'
			,menuDisabled			: true
			,text					: ( typeof params.text !== 'undefined'? params.text : '' )
			,actionColumnFunction	: params.Func || null
			,actionColumnIcon		: params.icon || ''
			,renderer				: function( value, metaData, rec ){
				metaData.style = 'cursor: pointer';
				
				/** set color to blue if its selected and active, otherwise black **/
				var color = ( parseInt( rec.get( 'selected' ) ) == 1 )? ( active? '#3498db' : '#ecf0f1' ) : '#ecf0f1';
				/* disable Action if admin */
				if(
					typeof params.hideAdmin !== 'undefined'
						&& params.hideAdmin
							&& typeof rec.data.euID !== 'undefined'
								&& rec.data.euID == 1
				){
					return '<span class="glyphicon glyphicon-' + params.icon + '" style="color:#ecf0f1" title="' + params.tooltip + '"> ' + ( typeof params.textDisplay !== 'undefined'? params.textDisplay : '' ) + '</span>';
				}
				/** check if condition is defined **/
				if( typeof params.condition != 'undefined' ){
					/** change icon and tooltip if condition is met **/
					if( eval( params.condition ) ){
						return '<span class="glyphicon glyphicon-' + params.icon2 + '" style="color:' + color + '" title="' + params.tooltip2 + '"> ' + ( typeof params.textDisplay !== 'undefined'? params.textDisplay : '' ) + '</span>';
					}
				}
				
				return '<span class="glyphicon glyphicon-' + params.icon + '" style="color:' + color + '" title="' + params.tooltip + '"> ' + ( typeof params.textDisplay !== 'undefined'? params.textDisplay : '' ) + '</span>';
				
			}
			,listeners	: {
				click	: function( grid, params1, row, params2, params3, rec ){
					/** only clickable when row is selected and active **/
					if( parseInt( rec.data.selected, 10 ) == 1 && active ){
						SELECTED = null;
						
						/** executes another function if condition is met **/
						/** otherwise, run custom function **/
						if( typeof params.condition != 'undefined' ){
							if( eval( params.condition ) ){
								params.Func2( rec.data, row );
							}
						}
						else{
							/** Ext.objectProtoType( object ) contains prototype function
								that prompts a confirmation message when deleting a record.
								Only works for delete action columns
							**/
							params.Func( ( typeof params.canDelete != 'undefined'? new Ext.objectProtoType( rec.data ) : rec.data ), row );
						}
					}
				}
			}
		} );
	}
	
	/* Create masking component that would mask on-progress functions
		return : Component
		parameters[
			
			PROPERTIES __________________
			target	: [object] container to be masked. Default = Ext.getBody()
			msg   	: [string] message shown during masking. Default = Please wait... 
		]
	*/
	
	function _createMask( params ){
		return new Ext.LoadMask(
			( typeof params.target != 'undefined' )? params.target : Ext.getBody()
			,{ 
				msg: (typeof params.msg != 'undefined' )? params.msg : 'Please wait...'
			}
		);
	}
	
	/* Create message box or confirmation box
		return : Message Box
		parameters[
			
			PROPERTIES __________________
			action	: [string] appropriate description for the fixed message. no default value
			msg		: [string] custom message. only applicable if action = custom or confirm. Default = empty string
			icon	: [string] icon for message box. valid values [error, question]. Default = Ext.MessageBox.INFO
			buttons	: [string] buttons for message box. valid values [okcancel, yesno, yesnocancel]. Default = Ext.MessageBox.OK
			title 	: [string] title for message box. Default = SYSTEM MESSAGE
			
			see property details for other parameters @ ExtDocs
			
			FUNCTIONS __________________
			fn		: [func] function which executes after confirmation
		]
	*/
	
	function _createMessageBox( params ){
		var title	= ( typeof params.title != 'undefined' )? params.title : Ext.getConstant( 'MSGBOX_TITLE' )
			,action	= ( typeof params.action != 'undefined' )? params.action.toLowerCase() : ''
			,msg	= Ext.ifUndefined( Ext.getConstant( params.msg ), params.msg );
		
		/** confirmation box **/
		if( action == 'confirm' ){
			Ext.MessageBox.confirm(
				title
				,msg
				,params.fn
			);
		}
		/** message box **/
		else{
			var icon		= Ext.MessageBox.INFO
				,buttons	= Ext.MessageBox.OK;
			
			/** icon for message box **/
			if( typeof params.icon != 'undefined' ){
				if( params.icon == 'error' ) icon = Ext.MessageBox.ERROR;
				else if( params.icon == 'question' ) icon = Ext.MessageBox.QUESTION;
			}
			
			/** button(s) for message box **/
			if( typeof params.buttons != 'undefined' ){
				if( params.buttons == 'okcancel' ) buttons  = Ext.MessageBox.OKCANCEL;
				else if( params.buttons == 'yesno' ) buttons  = Ext.MessageBox.YESNO;
				else if( params.buttons == 'yesnocancel' ) buttons  = Ext.MessageBox.YESNOCANCEL;
			}

			Ext.MessageBox.show( {
				msg				: msg
				,icon			: icon
				,title			: title
				,buttons		: buttons
				,closeAction	:'destroy'
				,fn				: ( typeof params.fn != 'undefined' )? params.fn : function(){}
				,closable		: false
			} );
		}
	}
	
	/* Create message box or confirmation box
		return : Message Box
		parameters[
			
			PROPERTIES __________________
			element	: [string] component's id on which prompt message will be applied.
			msg		: [string] custom message. Default = empty string
			duration: [int] desired duration (millisecond) for the message to be shown before fading away. Default = 200
			
			see property details for other parameters @ ExtDocs
		]
	*/
	
	function _promptMsg( params ){
		var element		= Ext.get( params.varId )
			,msgColor	= ( typeof params.msgColor != 'undefined'? params.msgColor : 'red' )
			,msg		= ( typeof params.msg != 'undefined'? '<em style="color:' + msgColor + ';">' + params.msg + '</em>' : '' )
			,duration	= ( typeof params.duration != 'undefined'? params.duration : 200 );
		
		if( element ){
			element.update( msg );
			if( !element.isVisible() ){
				element.slideIn( 't', {
					duration	: duration
					,easing		: 'easeIn'
					,listeners	: {
						afteranimate	: function() {
							element.highlight();
							element.setWidth( null );
						}
					}
				} );
			}
		}
	}
	
	/* Create local store
		return : Store
		parameters[
			
			PROPERTIES __________________
			fields	: [Array of strings] store fields. If not defined, local store uses numreric values for valueField. Default = [name,id] - displayField and valueField respectively.
			data	: [JSON] custom json object which consist both values for displayFields and valueFields. only applicable if 'fields' parameter is defined.
			startAt : [int] desired starting point for numeric valueFields. Default = 1
			
		]
	*/
	
	function _createLocalStore( params ){
		var data, fields;
		
		if( typeof params.fields != 'undefined' ){
			data   = params.data;
			fields = params.fields;
		}
		else{
			data   = [];
			fields = [ 'name', 'id' ];
			
			var startAt = ( typeof params.startAt != 'undefined' )? params.startAt : 1;
			
			for( var x = startAt; x < params.data.length + startAt; x++ ){ 
				data.push( {
					id		: x
					,name	: params.data[ x - startAt ]
				} );
			}
		}
		
		return Ext.create( 'Ext.data.Store', {
			fields	: fields
			,data	: data
		} );
	}
	
	/* Create remote store
		return : Store
		parameters[
			
			PROPERTIES __________________
			fields	: [Array of strings] store fields.
			url		: [string] controller path.
			pageSize: [int] row number per page. Default = 50
			
		]
	*/
	
	function _createRemoteStore( params ){
		var hasSortFunction	= ( typeof params.hasSortFunction != 'undefined' ? params.hasSortFunction : true ); 
		if( hasSortFunction ){
			for( var x in fields = params.fields ){
				if( typeof fields[x].type != 'undefined' ){
					if( fields[x].type == 'number' ) fields[x].sortType = 'asInt';
					else if( fields[x].type == 'float' ) fields[x].sortType = 'asFloat';
					else if( fields[x].type == 'date' ) fields[x].sortType = 'asDate';
				}
				else{
					
					fields[x] = {
						name      : fields[x]
						,sortType : 'asUCText'
					};
				}
				
			}
		}
		var modelId = ( typeof params.modelId != 'undefined' ? params.modelId : params.url );
		var model 	= Ext.define( modelId, {
			extend	: 'Ext.data.Model'
			,fields	: params.fields
		} );
		
		return Ext.create( 'Ext.data.Store', {
			model		: modelId
			,storeId	: modelId
			,autoLoad	: ( typeof params.autoLoad != 'undefined' ? params.autoLoad : false )
			,pageSize	: ( typeof params.pageSize != 'undefined' ? params.pageSize : Ext.getConstant( 'DEF_PAGE_SIZE' ) )
			,groupField	: ( typeof params.groupField != 'undefined' ? params.groupField : null )
			,listeners	: params.listeners
			,proxy		: {		
				type			: 'ajax'
				,actionMethods	: 'post'
				,url			: params.url
				,noCache		: false
				,reader			: {		
					type			: 'json'
					,root			: 'view'
					,totalProperty	:	'total'
				}
			}
		} );
		
	}
	
	/* Create module's main panel
		return : Tab child
		parameters[
			
			PROPERTIES __________________
			moduleType		: [string] specify if module is form or report. Default = 'form'
			formItems		: [array of components] items for form or report
			moduleGrids		: [array of components || single grid] grid(s) for under report form
			tbar			: [object || string] header toolbar config. Set tbar as 'empty' for empty header toolbar
			noFormButton 	: [bool] (under tbar) specify availability for form button. Default = false
			noListButton 	: [bool] (under tbar) specify availability for list/history button. Default = false
			noExcelButton 	: [bool] (under tbar) specify availability for excel button. Default = false
			noPDFButton 	: [bool] (under tbar) specify availability for pdf button. Default = false
			noHelpButton 	: [bool] (under tbar) specify availability for help button. Default = false
			formLabel		: [string] (under tbar) form button label. Default = 'Form'
			listLabel		: [string] (under tbar) list/history button label. Default = 'History'
			saveLabel		: [string] (under tbar) save button label. Default = 'Save'
			resetLabel		: [string] (under tbar) reset button label. Default = 'Reset'
			PDFHidden 		: [bool] (under tbar) pfd button invisibility. Default = true
			extraTbar1		: [array of components] (under tbar) extra components rendered after save/reset button
			extraTbar2		: [array of components] (under tbar) extra components rendered before excel button
			extraTbar3		: [array of components] (under tbar) extra components rendered after help button
			filter			: [object] (under tbar) used as preference for standard tbar combo search
			displayField	: [string] (under filter) database column
			table			: [string] (under filter) database selected schema
			idmodule		: [int] (under filter) if specified, automatically adds additional combo filter (reference code) for searching.
			
			FUNCTIONS __________________
			customGoToFormHandler:  [func] (under tbar) custom function for switching tab from list/history to form. If not specified, standard goToForm() executes
			beforeGoToFormHandler:  [func] (under tbar) custom function that triggers before goToForm()
			afterGoToFormHandler:   [func] (under tbar) custom function that triggers after goToForm()
			customGoToListHandler:  [func] (under tbar) custom function for switching tab from form to list/history
			beforeGoToListHandler:  [func] (under tbar) custom function that triggers before go to list
			afterGoToListHandler:   [func] (under tbar) custom function that triggers after go to list
			customExcelHandler:   	[func] (under tbar) custom function for printing excel
			formPDFHandler:   		[func] (under tbar) custom function for printing pdf
			beforeHelpHandler:   	[func] (under tbar) custom function that triggers before viewing help window
			afterHelpHandler:   	[func] (under tbar) custom function that triggers after viewing help window
			
		]
		import functions[
			_changeCls()
			_createComboSearch()
			_formPanel()
		]
	*/
	
	function _mainPanel( params ){

		var mainTab			= Ext.getCmp( 'mainTabPanel_mainView' )
			,tbar			= null
			,bbar			= null
			,tbarCardItems	= new Array()
			,mainPanelItems	= new Array()
			,moduleType		= ( typeof params.moduleType != 'undefined' ) ? params.moduleType : 'form'
			,isTabChild		= ( typeof params.isTabChild != 'undefined' ) ? params.isTabChild : false
			,asContainer	= ( typeof params.asContainer != 'undefined' ) ? params.asContainer : false
			,config			= params.config
			,showOnForm		= ( typeof params.showOnForm != 'undefined' ) ? params.showOnForm : false
			,moduleID		=  params.moduleID;

		/** TOP TOOLBAR **/
		if( typeof params.tbar != 'undefined' ){
			var tbarItems	= new Array()
				,me			= params.tbar;
			
			if( params.tbar instanceof Array ){
				tbarItems = params.tbar;
			}
			else{
				if( me.toString().toLowerCase() === 'empty' ){
					tbarItems = null;
				}
				else{
					var noFormButton	= ( typeof me.noFormButton != 'undefined' )? me.noFormButton : false
						,noListButton	= ( typeof me.noListButton != 'undefined' )? me.noListButton : false
						,noExcelButton	= ( typeof me.noExcelButton != 'undefined' )? me.noExcelButton : false
						,noPDFButton	= ( typeof me.noPDFButton != 'undefined' )? me.noPDFButton : false;

					
					/** FORM BUTTON **/
					if( moduleType == 'form' ){
						var tbarCardItems1	= new Array();

						
						/** insert button for form **/
						if( !noFormButton ){
							tbarItems.push( {
								xtype		: 'button'
								,cls		: 'menuActive'
								,text		: ( typeof me.formLabel != 'undefined' )? me.formLabel : 'Form'
								,id			: 'btnForm' + config.module
								,iconCls	: 'modu'
								,handler	: function(){
									/** standard function upon clicking form button **/
									_goToForm( {
										scope			: this
										,module			: config.module
										,otherFormID 	: params.otherFormID
										,hasFormPDF		: ( typeof me.hasFormPDF != 'undefined' )? me.hasFormPDF : false
										,hasFormExcel 	: ( typeof me.hasFormExcel != 'undefined' )? me.hasFormExcel : false
									} );
									
									/** custom function after gotoForm function **/
									if( typeof me.afterGoToFormHandler != 'undefined' ){
										me.afterGoToFormHandler();
									}
								}
							} );
						}
						
						/** LIST/HISTORY BUTTON **/
						/** insert button for list/history **/
						if( !noListButton ){
							tbarItems.push( {
								xtype			: 'button'
								,cls			: 'menuInactive'
								,text			: ( typeof me.listLabel != 'undefined' )? me.listLabel : 'History'
								,id				: 'btnList' + config.module
								,iconCls		: 'list' 
								,noActionBtn	: ( typeof me.noActionBtn != 'undefined' )? me.noActionBtn : false
								,handler		: function(){
									/** check then change button's cls and main panel's active tab **/
									
									if( _changeCls( {
											scope	: this
											,module	: config.module
										} ) 
									){
										/** reset filters **/
										if( filterContainer = Ext.getCmp( 'searchFilterContainer' + config.module ) ){
											for( x in field = filterContainer.items.items ){
												if( field[ x ].isFormField ){
													field[ x ].reset();
												}
											}
										}
										
										/** sets store's page to 1 and load **/
										if( grid = Ext.getCmp( 'gridHistory' + config.module ) ){
											grid.store.sorters.clear();
											grid.store.currentPage = 1;
											grid.store.proxy.extraParams = {
												moduleID 		: config.idmodule
												,idAffiliate 	: config.idAffiliate
											}

											grid.store.load();
											
										}

										/** hide both buttons if any **/
										if( typeof me.hasExtraButton != "undefined" ){
											if( exportFile = params.module.getButton( 'exportFile' ) ){
												exportFile.setVisible( true );
											}
										}
										if( btnExcel = params.module.getButton( 'excel' ) ){
											btnExcel.setVisible( true );
										}
										if( btnPDF = params.module.getButton( 'pdf' ) ){
											btnPDF.setVisible( true );
										}
										
										/** custom function after gotoList function **/
										if( typeof me.afterGoToListHandler != 'undefined' ){
											me.afterGoToListHandler();
										}
									}
								}
							} );
						}
						
						/** SAVE BUTTON **/
						/** insert button for save button **/
						if( typeof me.saveFunc != 'undefined' ){
							tbarCardItems1.push( {
								xtype		: 'button'
								,id			: 'saveButton' + config.module
								,iconCls	: 'glyphicon glyphicon-floppy-disk'
								,text		: ( typeof me.saveLabel != 'undefined' )? me.saveLabel : 'Save'
								,disabled	: ( typeof params.saveButtonDisable != 'undefined') ? params.saveButtonDisable : true
								,cls		: 'menuActive'
								,hidden		: !config.canSave
								,handler	: function(){
									me.saveFunc( config.module.getForm() );
								}
							} );
						}
						
						/** RESET BUTTON **/
						/** insert button for reset button **/
						if( typeof me.resetFunc != 'undefined' ){
							tbarCardItems1.push( {
								xtype		: 'button'
								,id			: 'resetButton'+config.module
								,iconCls	: 'glyphicon glyphicon-refresh'
								,text		: ( typeof me.resetLabel != 'undefined' )? me.resetLabel : 'Reset'
								,style		: 'margin-left:5px; margin-bottom:5px;'
								,cls		: 'menuActive'
								,hidden		: ( typeof me.noReset != 'undefined' )? me.noReset : false
								,handler	: function(){
									me.resetFunc( config.module.getForm() );
								}
							} );
						}
						
						/** EXTRA BUTTONS **/
						/** insert extra action button **/
						if( typeof params.extraFormButton != 'undefined' ){
							var extraFormButton = params.extraFormButton;
							
							/** handler for extra button **/
							function extraButtonHandler( x ){
								return function(){
									if( typeof extraFormButton[x].handler != 'undefined' ){
										extraFormButton[x].handler();
									}
								}
							}
							
							for( var x in extraFormButton ){
								/** if index of button is not specified, default index would be equal to container's current number of items **/
								extraFormButton[x].index = ( typeof extraFormButton[x].index != 'undefined' )? extraFormButton[x].index : tbarCardItems1.length;
								extraFormButton[x].label = ( typeof extraFormButton[x].label != 'undefined' )? extraFormButton[x].label : 'new button'+extraFormButton[x].index;
								
								/** insert extra button to specified index **/
								if( typeof extraFormButton[x].xtype != 'undefined' ){
									if( extraFormButton[x].xtype == 'container' ){
										tbarCardItems1.splice( extraFormButton[x].index, 0, {
											xtype	: extraFormButton[x].xtype
											,id		: ( typeof extraFormButton[x].id != 'undefined' )? extraFormButton[x].id : 'extraFormButton'+extraFormButton[x].label.replace(/ /g,'_')+''+config.module
											,html	: ( typeof extraFormButton[x].html != 'undefined' )? extraFormButton[x].html : ''
											,items	: ( typeof extraFormButton[x].items != 'undefined' )? extraFormButton[x].items : ''
											,style	: ( typeof extraFormButton[x].style != 'undefined' )? extraFormButton[x].style : ''
										} )
									}
								}
								else{
									tbarCardItems1.splice( extraFormButton[x].index, 0, {
										xtype		: ( typeof extraFormButton[x].xtype != 'undefined' )? extraFormButton[x].xtype : 'button'
										,cls		: 'menuActive'
										,iconCls	: ( typeof extraFormButton[x].iconCls != 'undefined' )? extraFormButton[x].iconCls : 'glyphicon glyphicon-floppy-disk'
										,style		: ( typeof extraFormButton[x].style != 'undefined' )? extraFormButton[x].style : 'margin-left:5px'
										,hidden		: ( typeof extraFormButton[x].hidden != 'undefined' )? extraFormButton[x].hidden : false
										,disabled	: ( typeof extraFormButton[x].disabled != 'undefined' )? extraFormButton[x].disabled : false
										,id			: ( typeof extraFormButton[x].id != 'undefined' )? extraFormButton[x].id : 'extraFormButton'+extraFormButton[x].label.replace(/ /g,'_')+''+config.module
										,text		: extraFormButton[x].label
										,handler	: extraButtonHandler( x )
									} )
								}
							}
						}
						
						tbarCardItems.push( {
							xtype		: 'container'
							,layout		: 'column'
							,style		: 'top: 3px !important;'
							,items		: tbarCardItems1
						} );
						
						/** FILTER  **/
						/** insert filter field for list/history **/
						if( typeof me.filter != 'undefined' ){
							if( typeof me.filter.customFilter != 'undefined' ){
								tbarCardItems.push( me.filter.customFilter )
							}
							else{
								me.filter.config = config;
								
								// console.log(Ext.Object.getValues(me.filter)+'mao ni mga filter');
								tbarCardItems.push( _createComboSearch( me.filter ) ); //me.filter
							}
						}
						else{
							tbarCardItems.push( {xtype	: 'container'} );
						}
					}
					
					/** EXTRA FORM TAB BUTTON **/
					/** insert extra button for extra tab **/
					if( typeof params.extraFormTab != 'undefined' ){
						var extraFormTab = params.extraFormTab;
						
						/** handler for extra button **/
						function tabButtonHandler( x ){
							return function(){
								/** check then change button's cls and main panel's active tab **/
							
								// console.log('mao ni nga button');
							
								if( _changeCls({
										scope	: this
										,module	: config.module
									}) 
								){
									if( typeof extraFormTab[x].buttonHandler != 'undefined' ){
										extraFormTab[x].buttonHandler();
									}
								}
							}
						}
						
						for( var x in extraFormTab ){
							/** if index of button is not specified, default index would be equal to tbar's current number of items **/
							extraFormTab[x].buttonIndex   = ( typeof extraFormTab[x].buttonIndex != 'undefined' )? extraFormTab[x].buttonIndex : tbarItems.length;
							extraFormTab[x].buttonLabel   = ( typeof extraFormTab[x].buttonLabel != 'undefined' )? extraFormTab[x].buttonLabel : 'new tab'+extraFormTab[x].buttonIndex;
							
							/** insert extra button to specified index **/
							tbarItems.splice( extraFormTab[x].buttonIndex, 0, {
								xtype			: 'button'
								,cls			: ( typeof extraFormTab[x].activeButton != 'undefined' ? 'menuActive' : 'menuInactive' )
								,id				: ( typeof extraFormTab[x].buttonId != 'undefined' )? extraFormTab[x].buttonId : 'btn'+extraFormTab[x].buttonLabel.replace(/ /g,'_')+''+config.module
								,iconCls		: ( typeof extraFormTab[x].buttonIconCls != 'undefined' )? extraFormTab[x].buttonIconCls : 'details'
								,noActionBtn	: ( typeof extraFormTab[x].actionButtons != 'undefined' )? false : true
								,text			: extraFormTab[x].buttonLabel
								,handler		: tabButtonHandler( x )
							} );
							
							/** EXTRA TBAR CARD **/
							/** insert extra card for tbar **/
							tbarCardItems.splice( extraFormTab[x].buttonIndex, 0, {
								xtype		: 'container'
								,layout		: 'column'
								,style		: 'margin-top:5px'
								,items		: ( typeof extraFormTab[x].actionButtons != 'undefined' )? ( extraFormTab[x].actionButtons == 'empty'? null : extraFormTab[x].actionButtons )  : null
							} );
						}
					}
					
					/** lazy rendering container for save and filter containers **/
					if( tbarCardItems.length > 0 ){
						if( !params.noSeparator ){
							if( moduleType == 'form' ){
								tbarItems.push( '-' );
							}
						}
						tbarItems.push(
							{	xtype		: 'container'
								,id			: 'tbarCardPanel'+config.module
								,layout		: {
									type			: "card"
									,deferredRender	: true
								}
								,items		: tbarCardItems
							}
						);
					}
					
					/** add custom items **/
					if( typeof me.extraTbar1 != 'undefined' ){
						for( var x in me.extraTbar1 ){
							tbarItems.push( me.extraTbar1[x] );
						}
					}

					tbarItems.push('->');

					/** add custom items before excel button **/
					if( typeof me.extraTbar2 != 'undefined' ){
						for( var x in me.extraTbar1 ){
							tbarItems.push( me.extraTbar2[x] );
						}
					}

					/** EXPORT BUTTON **/
					/** insert pdf button **/
					if( !noPDFButton && config.canPrint && typeof me.hasExtraButton != "undefined" ){
						tbarItems.push( me.hasExtraButton )
					}

					/** EXCEL BUTTON **/
					/** insert excel button **/
					if( !noExcelButton && config.canPrint ){
						tbarItems.push( {
							xtype		: 'button'
							,id			: 'btnExcel' + config.module
							,iconCls	: 'excel'
							,cls		: 'excelBtn'
							,hidden		: ( moduleType == 'form' && !showOnForm )? true : false
							,tooltip	: 'convert to Excel'
							,handler	: function(){
								if( Ext.getCmp( 'mainPanel'+config.module ).getLayout().getActiveItem() == config.module.getForm( true ) ){
									me.formExcelHandler();
								}
								else{
									if( typeof params.customExcelHandler != 'undefined' ){
										params.customExcelHandler();
									}
									else{
										_listExcel( {
											grid					: Ext.getCmp( 'mainListPanel'+config.module ).down( 'grid' )
											,route					: config.route
											,module					: config.module
											,idModule				: config.idModule
											,pageTitle				: config.pageTitle
											,customListExcelHandler	: ( typeof me.customListExcelHandler != 'undefined' )? me.customListExcelHandler : false
											,listExcelHandler		: ( typeof me.listExcelHandler != 'undefined' )? me.listExcelHandler : false
											,extraParams			: me.extraParams
										} );

									}
								}
							}
						} );
					}
					/** PDF BUTTON **/
					/** insert pdf button **/
					if( !noPDFButton && config.canPrint ){
						
						tbarItems.push( {
							xtype		: 'button'
							,id			: 'btnPDF' + config.module
							,iconCls	: 'pdf-icon'
							,hidden		: ( typeof me.PDFHidden != 'undefined' )? me.PDFHidden : true
							,tooltip	: 'convert to PDF'
							,handler	: function(){

								if( Ext.getCmp( 'mainPanel'+config.module ).getLayout().getActiveItem() == config.module.getForm( true ) ){
									me.formPDFHandler();
								}
								else{
									if( typeof params.customPDFHandler != 'undefined' ){
										params.customPDFHandler();
									}
									else{
										console.warn( 'mainListPanel'+config.module, Ext.getCmp( 'mainListPanel'+config.module ) );
										_listPDF( {
											grid 					: Ext.getCmp( 'mainListPanel'+config.module ).down( 'grid' )
											,route 					: config.route
											,module 				: config.module
											,idModule				: config.idModule
											,pageTitle 				: config.pageTitle
											,orientation			: ( typeof params.orientation != 'undefined' )? params.orientation : 'P'
											,customListPDFHandler	: ( typeof me.customListPDFHandler != 'undefined' )? me.customListPDFHandler : false
											,listPDFHandler			: ( typeof me.listPDFHandler != 'undefined' )? me.listPDFHandler : false
											,extraParams			: me.extraParams
											,isGae					: Ext.getConstant('ISGAE')
										} );
									}
								}
							}
						} );
					}
					
					/** add custom items after help button **/
					if( typeof me.extraTbar3 != 'undefined' ){
						for( var x in me.extraTbar3 ){
							tbarItems.push( me.extraTbar3[x] );
						}
					}
				}
			}
			
			tbar = 	Ext.create( 'Ext.toolbar.Toolbar', {
				cls			: 'toolBButton'
				,height		: 33
				,id			: 'mainTbar' + config.module
				,items		: tbarItems
			} );
		
		}
		
		// console.log( 'BBAR', params.bbar)

		if( params.bbar != 'undefined' ){
			bbar = params.bbar;
		}
		
		var listeners = ( typeof params.listeners != 'undefined' )? params.listeners : {};
		delete params.listeners;
		
		/** FORM **/
		/** form items **/
		
		if ( typeof params.formItems  !=  'undefined' ){
			/** normal form panel with fields centered **/
			
			if( !isTabChild ){
				var parameter = params;
				
				for( key in config ){
					parameter[key] = config[key];
				}
				mainPanelItems.push( _formPanel( parameter ) );
			}
			/** tab as child **/
			else{
				for( var x in params.formItems ){
					mainPanelItems.push( params.formItems[x] );
				}
			}
		}
		
		
		/** LIST|HISTORY **/
		/** form items **/
		if( typeof params.listItems != 'undefined' ){
			mainPanelItems.push( {
				xtype	: 'container'
				,id		: 'mainListPanel'+config.module
				,items	: params.listItems
				,layout	: 'fit'
			} );
		}
		
		
		if( typeof params.extraFormTab != 'undefined' ){
			for( var x in extraFormTab ){
				mainPanelItems.splice( extraFormTab[x].buttonIndex, 0,{
					xtype	: 'container'
					,layout	: 'fit'
					,items	: extraFormTab[x].items
				});
			}
		}

		// console.log ( 'MODULEID', moduleID );
		
		var tabID = 'mainPanel' + config.module;
		/** holder of the module **/
		var mPanel = {	
			title							: config.pageTitle
			,border							: false
			,closable						: mainTab.items.getCount() > 0 ? true : false //( moduleID != 1 ? true : false ) //
			,id								: tabID
			,overFlowX						: 'scroll'
			,autoScroll						: true
			,config							: config
			,moduleType						: moduleType
			,tbar							: tbar
			,items							: mainPanelItems
			,activeItem						: 0
			,editFunction					: params.editFunction
			,invoiceIDFromOtherTransaction	: config.invoiceIDFromOtherTransaction
			,layout							: ( typeof params.layout != 'undefined'? params.layout : {
				type				: "card"
				,deferredRender		: true
			} )
			,bbar							: bbar
			,listeners						: listeners
		}
		
		if( asContainer ){
			delete mPanel.title;
			delete mPanel.closable;
			return mPanel;
		}
		else{
			if( mainTab.getComponent( tabID ) ) {
				mainTab.setActiveTab( tabID );
			}
			else{
				/** add module to main tab **/
				mainTab.add(
					mPanel
				);
				
				mainTab.setActiveTab( tabID );
				mainTab.doLayout();
			}
		}
	}
	
	/* Create form or report panel
		return : Form panel
		parameters[
			
			PROPERTIES __________________
			moduleType		: [string] specify if module is form or report. Default = 'form'
			formItems		: [array of components] items for form or report
			moduleGrids		: [array of components || single grid] grid(s) for under report form
			
			panelID			: [strings] id for form panel. Default = 'mainFormPanel'+module
			statLabelID		: [strings] id for status label. Default = 'FormStatus'+module
			saveBtnID		: [strings] id for save|view button. Default = 'saveButton'+module
			filterID		: [strings] id for hidden field storage for filters. Default = 'reportFilter'+module
			btn_view_width	: Change width view of view button. Default = 75
			
			***Search Button
			btn_text		: [strings] text label for View Button. Default ='View'
			btn_style		: declare your style: Default = 'margin-bottom:5px'
			
			FUNCTIONS __________________
			customViewHandler	:  [func] custom function for viewing report.
			beforeViewHandler	:  [func] custom function that triggers before hitting view button
			afterViewHandler	:  [func] custom function that triggers after hitting view button
			customResetHandler	:  [func] custom function for reseting report form.
			beforeResetHandler	:  [func] custom function that triggers before hitting reset button
			afterResetHandler	:  [func] custom function that triggers after hitting reset button
			
			
		]
	*/
	
	function _formPanel( params ){
		var moduleType		= ( typeof params.moduleType != 'undefined' )? params.moduleType : 'form'
			,panelID		= ( typeof params.id != 'undefined' )? params.id : 'mainFormPanel'+params.module
			,noHeader		= ( typeof params.noHeader != 'undefined' )? params.noHeader : false
			,statLabelID
			,moduleItems	= [];
		
		
		/** for form module type **/
		if( moduleType == 'form' ){
			var isCenter  = ( typeof params.isCenter != 'undefined' )? params.isCenter : Ext.getConstant( 'FORM_ISCENTER' );
			statLabelID   = ( typeof params.statLabelID != 'undefined' )? params.statLabelID : 'FormStatus'+params.module;
			
			/** add label for form validity **/
			if( !noHeader ){
				params.formItems.unshift(
					{	html				: '<div id="' + statLabelID + '" style="color:red;">Fields with * are required. </div>'
						,border				: false
						,style				: 'margin-top:3px; margin-bottom:20px;'
						,width				: 200
						,isFormStatusLabel	: true
					}
				);
			}
			
			if( isCenter ){
				moduleItems = [
					{	xtype		: 'panel'
						,minWidth	: 100
						,border		: ( typeof params.border != 'undefined' )? params.border : Ext.getConstant( 'FORM_HASBORDER' )
						,style		:  ( typeof params.border != 'undefined' )? 'margin: 0 auto;' : 'margin-bottom:10px'
						,layout		: {
							type	: 'hbox'
							,align	: 'center'
							,pack	: 'center'
						}
						,items:[
							{	xtype	: 'container'
								,items	: params.formItems
							}
						]
					}
				];
			}
			else{
				moduleItems = params.formItems;
			}
		}
		/** for report module type **/
		else if( moduleType == 'report' ){
			/** change default value for vertical @ other projects **/
			var vertical      = ( typeof params.vertical != 'undefined' )? params.vertical : Ext.getConstant( 'REPORTBTN_ISVERTICAL' );
			var viewButtonID  = ( typeof params.viewButtonID != 'undefined' )? params.viewButtonID : 'viewButton' + params.module;
			
			var viewContainer = {	xtype	: 'container'
				,layout		: 'column'
				,id			: 'report-form-container' +  params.module
				,style		: ( vertical? 'margin-left:5px' : null )
				,items		: [
					/** view button for report modules **/
					{	xtype		: 'button'
						// ,text		: '<span style="color: #39a68d !important;">' + ( typeof params.btn_text != 'undefined' ? params.btn_text : 'View' ) + '</span>'
						,text		: ( typeof params.btn_text != 'undefined' ? params.btn_text : 'View' )
						,id			: viewButtonID
						,iconCls	: 'glyphicon glyphicon-list-alt'
						,style		: ( typeof params.btn_style != 'undefined' ? params.btn_style : 'margin-bottom:5px' ) 
						,width		: ( typeof params.btn_view_width != 'undefined' ? params.btn_view_width : 75 )
						// ,disabled:	true
						/*Added By Stephen*/
						,cls		: 'myButton'
						,hidden		: ( typeof params.hideViewBtn != 'undefined' ?  params.hideViewBtn : false )
						,handler	: function(){
							/** custom view handler **/
							if( typeof params.customViewHandler != 'undefined' ){
								params.customViewHandler();
							}
							else{
								if( typeof params.beforeViewHandler != 'undefined' ){
									if( params.beforeViewHandler() ) return false;
								}
								
								var form			= Ext.getCmp( panelID ).getForm()
									,fields			= form.getFields()
									,grids			= Ext.ComponentQuery.query( '#'+panelID+' grid' )
									,extraparams	= {};
								
								/** collect and contain fields' value to a single array  **/
								fields.each( function( field ) {
									if( field.getValue() != null && ( field.getValue().toString() != field.getRawValue().toString() ) ){
										extraparams['raw'+field.getName().toString().replace( params.module, '' )] = field.getRawValue();
									}
									extraparams[ field.getName().toString().replace( params.module, '' ) ] = field.getSubmitValue();
								});
								extraparams['module'] = params.module;
								
								/** store array to the form as property **/
								form.used = extraparams;
								
								for( var x = 0; x < grids.length; x++ ){
									var store						= grids[x].getStore();
									extraparams['grid']				= x+1;
									store.getProxy().extraParams	= extraparams;
									store.currentPage				= 1;
									
									if( x == grids.length - 1 ){
										store.load({
											callback: function( response, operation ){
												if( typeof params.afterViewHandler != 'undefined' ) params.afterViewHandler();
											}
										} );
									}
									else{
										store.load();
									}
								}
							}
						}
					}
					/** reset button for report modules **/
					,{	xtype		: 'button'
						// ,text		: '<span style="color: #39a68d !important;">Reset</span>'
						,text		: 'Reset'
						,iconCls	: 'glyphicon glyphicon-refresh'
						,style		: 'margin-left:5px; margin-bottom:5px'
						,width		: 75
						,cls		: 'myButton'
						,id			: 'mainPanelFormReset' + params.module
						,hidden		: ( typeof params.hideResetBtn != 'undefined' ?  params.hideResetBtn : false )
						,handler	: function(){
							/** custom view handler **/
							if( typeof params.customResetHandler != 'undefined' ){
								params.customResetHandler();
							}
							else{
								if( typeof params.beforeResetHandler != 'undefined' ) params.beforeResetHandler();
								var form = Ext.getCmp( panelID ).getForm();
								
								form.reset();
								form.used = false;
								
								var grids = Ext.ComponentQuery.query( '#'+panelID+' grid' );
								for( var x=0; x<grids.length; x++ ){
									grids[x].store.removeAll();
									grids[x].store.getProxy().extraParams = {};
									grids[x].store.currentPage = 1;
								}


								
								if( typeof params.afterResetHandler != 'undefined' ){
									params.afterResetHandler();
								}
							}
						}
					}
				]
			};
			
			if( vertical ){
				params.formItems = {	
					xtype	: 'container'
					,layout	: {
						type	: 'hbox'
						,align	: 'stretch'
					}
					,items	:[
						{	xtype	: 'container'
							,items	: params.formItems
						}
						,{	xtype	: 'container'
							,layout	: 'vbox'
							,items	: [
								{	xtype	: 'container'
									,flex	: 1
									,items	: [
										{	xtype	: 'label'
											,hidden	: true
										}
									]
								}
								,viewContainer
							]
						}
					]
				};
				
				moduleItems = [params.formItems];
			}
			else{
				params.formItems.push( viewContainer );
				moduleItems = params.formItems;
			}
		}
		
		var gridChildren = new Array();
		if( typeof params.moduleGrids != 'undefined' ){
			var moduleGrids_allowNonGrids = ( typeof params.moduleGrids_allowNonGrids != 'undefined' )? params.moduleGrids_allowNonGrids : false;
			
			if( params.moduleGrids instanceof Array ){
				for( var x in params.moduleGrids ){
					moduleItems.push( params.moduleGrids[x] );
					if( !moduleGrids_allowNonGrids ){
						if( typeof params.moduleGrids[x].requiredFields !== 'undefined' && params.moduleGrids[x].requiredFields.length > 0 ){
							gridChildren.push( params.moduleGrids[x].id );
						}
					}
				}
			}
			else{
				moduleItems.push( params.moduleGrids );
				if( !moduleGrids_allowNonGrids ){
					if( params.moduleGrids.requiredFields.length > 0 ){
						gridChildren.push( params.moduleGrids.id );
					}
				}
			}
		}
		
		return {
			xtype							: 'form'
			,overFlowX						: 'scroll'
			,overFlowY						: 'scroll'
			,baseCls						: 'x-plain'
			,id								: panelID
			,items							: moduleItems
			,bodyPadding					: ( typeof params.bodyPadding != 'undefined' ) ? params.bodyPadding : '10px'
			,autoScroll						: true
			,noHeader						: noHeader
			,layout							: params.layout
			,statLabelID					: statLabelID
			,minWidth						: ( typeof params.minWidth != 'undefined' )? params.minWidth : 450
			,route							: params.route
			,gridChildren					: gridChildren
			,module							: params.module
			,buttons						: params.formButtons || null
			,buttonAlign					: params.formButtonAlign || null
			,autoGridPush					: params.autoGridPush
			,includedFormOnSubmit			: params.includedFormOnSubmit
			,extraFormRetrieveFunction		: params.extraFormRetrieveFunction
			,overrideParams					: Ext.ifUndefined( params.overrideParams, true )
			,viewButtonID					: ( moduleType == 'report' )? viewButtonID : 'undefined'
			,useStandardAttachmentSaving	: ( typeof params.useStandardAttachmentSaving != 'undefined' )? params.useStandardAttachmentSaving : true
			,listeners						: {
				fieldvaliditychange	: function(){
					if( typeof params.listeners != 'undefined' && typeof params.listeners.fieldvaliditychange != 'undefined' ){
						params.listeners.fieldvaliditychange( this );
					}
					else{
						/** prevents showing global error when form first loads **/
						if ( this.hasBeenDirty || this.getForm().isDirty() ) {
							
							var btnID	= ( moduleType == 'form' )? 'saveButton'+params.module : ''
								,fields	= this.getForm().getFields()
								,valid	= true;
							
							/** check invalid fields by looping **/
							fields.each( function( field ) {
								if( typeof field.unBoundFromForm == 'undefined' ){
									var errors = (field.xtype == 'numberfield' ? field.getErrors(field.getRawValue()) : field.getErrors());
									Ext.Array.forEach( errors, function( error ) {
										/** tag valid as false whenever error exists **/
										valid = false;
										/** break the loop. break cannot be applied for foreach, use return instead **/
										return false;
									} );
								}
							} );
							
							if( typeof params.requireGrid != "undefined" ){
								if( Ext.getCmp( params.requireGrid + params.module ).store.getCount() <= 0 ){
									valid = false
								}
								else{
									Ext.getCmp( params.requireGrid + params.module ).store.each( function( record ){
										if( record.get( "acctcode" ) == null || (record.get( "acctcode" )).length == 0 ){
											valid = false
										}
									} )
								}
							}
							
							/** for form module type **/
							if( moduleType == 'form' && !this.noHeader ){
								/** show status label for valid forms otherwise, show invalid **/
								if( document.getElementById( statLabelID ) ) document.getElementById( statLabelID ).innerHTML = Ext.getConstant( !valid? 'FORM_INVALID' : 'FORM_VALID' );
								
							}
							/** disable button if form is not valid **/
							if( Ext.getCmp( btnID ) ){
								Ext.getCmp( btnID ).setDisabled( !valid );
							}
							
							this.hasBeenDirty = true;
							// Ext.resumeLayouts( true );
						}
					}
				}
				,afterrender: function(){
					if( typeof params.listeners != 'undefined' && typeof params.listeners.afterrender != 'undefined' ){
						params.listeners.afterrender( this );
					}
				}
			}
		};
	}
	
	/* Function for switching tab (form to list. vice versa)
		return : N/A
		parameters[
			
			PROPERTIES __________________
			mode	: [int] flag for form(0) and list/history(1). No defaults
			
		]
	*/
	
	function _changeCls( params ){
		if( typeof params.scope == 'undefined' ) return false
		
		if( params.scope.cls == 'menuActive' ){
			return false;
		}
		
		var main	= Ext.getCmp( 'mainPanel' + params.module )
			,tbar	= params.scope.up('toolbar').items.items;
		
		Ext.suspendLayouts();
		for( var x=0; x<tbar.length; x++ ){
			if( params.scope.id == tbar[x].id ){
				var noActionBtn = ( typeof params.scope.noActionBtn != 'undefined' )? params.scope.noActionBtn : false;
				
				tbar[x].removeCls( 'menuInactive' );
				tbar[x].addCls( 'menuActive' );
				tbar[x].cls = 'menuActive';
				try{
					main.getLayout().setActiveItem( x );
					Ext.getCmp('tbarCardPanel'+params.module).getLayout().setActiveItem( ( noActionBtn ? 0 : x ) );				
				}
				catch( err ){
					console.log( err) ;
				}
			}
			else{
				if( tbar[x].cls == 'menuActive' ){
					tbar[x].removeCls( 'menuActive' );
					tbar[x].addCls( 'menuInactive' );
					tbar[x].cls = 'menuInactive';
				}
			}
		}
		Ext.resumeLayouts( true );
		
		return true;
	}
	
	/* Function for switching from list/history to form
		return : N/A
		parameters[
			
			PROPERTIES __________________
			mode	: [int] flag for form(0) and list/history(1). No defaults
			
		]
		import functions[
			
			_changeCls()
		]
	*/
	
	function _goToForm( params ){
		/** check then change button's cls and main panel's active tab **/
		if( _changeCls( {
				scope	: ( typeof params.scope != 'undefined' )? params.scope : params.module.getButton( 'form' )
				,module	: params.module
			} )
		){
			var formID		= ( typeof params.otherFormID != 'undefined' )? params.otherFormID : 'mainFormPanel' + params.module
				,form		= ( !Ext.getCmp( formID )? null : Ext.getCmp( formID ).getForm() )
				,main		= Ext.getCmp( 'mainPanel' + params.module )
				,visible	= false;

			// if( !form ) return false;

			if( !form.used ){ 
				visible = ( form.onEdit )? main.config.canEdit : main.config.canSave;
			}
			if( typeof params.module.getButton( 'exportFile' ) != "undefined" && params.module.getButton( 'exportFile' ) != null )
			{
				if( exportFile = params.module.getButton( 'exportFile' ) ) exportFile.setVisible( ( form.onEdit && main.config.canPrint && params.hasFormPDF ) );
			}
			if( saveButton = params.module.getButton( 'save' ) )saveButton.setVisible( visible );
			
			if( btnExcel = params.module.getButton( 'excel' ) ) btnExcel.setVisible( ( form.onEdit && main.config.canPrint && params.hasFormExcel ) );
				
			if( btnPDF = params.module.getButton( 'pdf' ) ) btnPDF.setVisible( ( form.onEdit && main.config.canPrint && params.hasFormPDF ) );
			
		}
	}
	
	/* Create grid
		return : grid panel
		parameters[
			
			PROPERTIES __________________
			store			: [store] grid's store.
			grouping		: [object] group data by categories.
			features		: [object] usually used when grid requires total field as summary. Default = null
			tbar			: [object || string] grid toolbar config. Set tbar as 'empty' for empty toolbar
			content			: [object || string] (under tbar) if array, set of components for grid's toolbar items. simply set 'add' for setting-type-grid otherwise 'list' to list/history-type-grid
			label			: [string] (under tbar) string label before add button
			noDeleteColumn	: [bool] (under tbar) does not generate delete column for the grid if set true. only applicable for 'add'-type grid. Default = false
			noUndoButton	: [bool] (under tbar) does not generate undo button if set true. only applicable for 'add'-type grid. Default = false
			labelSeparator	: [bool] (under tbar) visibly displays a separator between label and add button. Default = true
			addLabel		: [string] (under tbar) label for add button
			extraTbar1		: [array of components] (under tbar) extra components rendered before add button
			extraTbar2		: [array of components] (under tbar) extra components rendered after add/reset button
			extraTbar3		: [array of components] (under tbar) extra components rendered before excel button
			extraTbar4		: [array of components] (under tbar) extra components rendered after pdf button
			canPrint		: [bool] (under tbar) determines access for printing
			noExcel			: [bool] (under tbar) determines visibility for the excel button
			noPDF			: [bool] (under tbar) determines visibility for the pdf button
			tbarHeight		: [int] (under tbar) top toolbar's height. Default = 33
			bbarHeight		: [int] bottom toolbar's height. Default = 33
			hasTotal		: [bool] (under grid column) automatically adds field for total. Default = false
			allowBlank		: [bool] (under grid column) needed for form validation. Default = true
			editor			: [object || string] (under grid column) column's editor. values for non-object : text, float, number
			bbarTotalLabel	: [string] bbar total label. Default = 'Total'
			
			FUNCTIONS __________________
			customAddHandler: [func] (under tbar) custom function for adding new record
			beforeAddHandler: [func] (under tbar) custom function that triggers before adding new record
			afterAddHandler : [func] (under tbar) custom function that triggers after adding new record
			deleteRowFunc   : [func] (under tbar) custom function for default delete column. only applicable for 'add'-type grid
			PDFHandler		: [func] (under tbar) custom function for printing pdf
			excelHandler	: [func] (under tbar) custom function for printing excel
			
		]
		import functions[
			
			_createComboSearch()
		]
	*/
	
	function _gridPanel( params ){
		function __gridAddItem( _params ){
			return {
				xtype		: 'button'
				,text		: _params.addLabel
				,id			: 'addButton_'+_params.id
				,iconCls	: 'glyphicon glyphicon-plus'
				,cls		: 'toolBarItems'
				,border		: false
				,style		: 'margin-left:5px;'
				,handler	: function(){
					/** executes custom handler function **/
					if( typeof _params.customAddHandler != 'undefined' ){
						_params.customAddHandler();
					}
					else{
						/** executes extra handler before inserting new row **/
						if( typeof _params.beforeAddHandler != 'undefined' ){
							_params.beforeAddHandler();
						}
						
						/** inserts new row **/
						
						var _grid = Ext.getCmp( _params.id );
						_grid.store.insert( _grid.store.getCount(), { selected : 0 } );
						_grid.getPlugin().startEdit( _grid.store.getCount(), ( _params.editColumn )? _params.editColumn : 0 );
						if( form = _grid.up( 'form' ) ){
							form.fireEvent( 'fieldvaliditychange' );
						}
						
						/** executes extra handler after inserting new row **/
						if( typeof _params.afterAddHandler != 'undefined' ){
							_params.afterAddHandler();
						}
					}
				}
			};
			
		}
		
		function _summaryRenderer( id ) {
			return function( value ){ 
				Ext.getCmp( id ).setValue( Ext.util.Format.number( value, '0,000.00' ) );
			};
		}
		
		function _currencyRenderer( isDecimal ){
			return function( value ){ 
				return currSym + ' ' + Ext.util.Format.number( parseFloat( value, 10 ) , ( isDecimal? '0,000.00' : '0,000' ) );
			}
		}
		
		function _timeRenderer(){
			return function( value ){ 
				if( value ){
					try{
						var hour24	= parseInt( value.getHours(), 10 )
							,hour12	= ( hour24 > 12 )? hour24 - 12 : hour24_createCombo
							,APM	= ( hour24 >= 12 )? 'PM' : 'AM'
							,hour	= ( hour12 < 10 )? ( hour12 == 0? 12 : '0'+hour12 ) : hour12
							,mins	= ( parseInt( value.getMinutes(), 10 ) < 10 )? '0'+value.getMinutes() : value.getMinutes();
						return hour + ':' + mins + ' ' + APM;
					}catch( err ){
						return value;
					}
				}
				else{
					return '';
				}
			}
		}
		
		function _numberColumnRendererfunction( value, metaData, record, rowIndx, colIndx ){
			var colmns			= Ext.getCmp( params.id ).columns[colIndx]
				,format			= colmns.format
				,displayIfZero	= typeof colmns.displayIfZero != 'undefined' ? colmns.displayIfZero : true
				,color			= typeof colmns.negativeColor != 'undefined' ? colmns.negativeColor : 'black';
			if(value < 0){
				return '<span style="color: ' + color + ';">(' + Ext.util.Format.number( Math.abs( value ), format ) + ')</span>';
			}
			else{
				if( !displayIfZero && value == 0 ) return '';
				else return Ext.util.Format.number( value, format );
			}
		}
		
		function _summaryNumberColumnRenderer( value, summaryData, dataIndex, a, colIndex ){
			var extFilters	= Ext.getCmp( params.id ).columns[colIndex]
				,format		= Ext.getCmp( params.id ).columns[colIndex].format ? Ext.getCmp( params.id ).columns[colIndex].format : '0,000.00'
				,grdName	= Ext.getCmp( params.id ).store
				,color		= typeof Ext.getCmp( params.id ).columns[colIndex].negativeColor != 'undefined' ? Ext.getCmp( params.id ).columns[colIndex].negativeColor : 'black';
			
			if( value < 0 ){
				return '<span style="color:' + color + ';">(' + ( typeof extFilters.hasLastRow != 'undefined' ? Ext.util.Format.number( Math.abs( ( typeof grdName.getAt( grdName.getCount() -1 ).data[extFilters.dataIndex] ? grdName.getAt( grdName.getCount() -1 ).data[extFilters.dataIndex] : 0 ) ), format ) : Ext.util.Format.number( Math.abs( value ), format ) ) + ')</span>';
			}
			else{
				return  ( typeof extFilters.hasLastRow != 'undefined' ? Ext.util.Format.number( ( typeof grdName.getAt( grdName.getCount() -1 ).data[extFilters.dataIndex] ? grdName.getAt( grdName.getCount() -1 ).data[extFilters.dataIndex] : 0 ), format ) : Ext.util.Format.number( value, format ) );
			}
		}

		var features		= null
			,countHasTotal	= 0
			,dockedItems	= null
			,tbar			= null
			,bbar			= null
			,store			= params.store
			,toolbarHeight	= 33
			,noDefaultRow	= ( typeof params.noDefaultRow != 'undefined' )? params.noDefaultRow : false;
		
		/** grid features **/
		if( typeof params.grouping != 'undefined' ){
			features = params.grouping;
		}
		else if( typeof params.features != 'undefined' ){
			features = 	params.features;
		}
		
		/** provide top toolbar if tbar is defined **/
		if( typeof params.tbar != 'undefined' ){
			var me			= params.tbar
				,tbarItems	= new Array()
				,canPrint	= ( typeof me.canPrint != 'undefined' )? me.canPrint : false
				,noPDF		= ( typeof me.noPDF != 'undefined' )? me.noPDF : false
				,noExcel	= ( typeof me.noExcel != 'undefined' )? me.noExcel : false;

			if( typeof params.tbar == 'string' ){
				if( me.toString().toLowerCase() === 'empty' ) tbarItems = null;
			}
			else{
				/** if params.tbar.content is an array **/
				if( me.content instanceof Array ){
					tbarItems = me.content; //dari siya nga part nisulod
				}
				else{ //dili dari.. hahaha
					/** add label to toolbar **/
					if( typeof me.label != 'undefined' ){
						var labelSeparator = ( typeof me.labelSeparator != 'undefined' )? me.labelSeparator : true;
						
						tbarItems.push( {
							xtype	: 'label'
							,style	: 'margin-left:5px;margin-right:10px;'
							,text	: me.label
						} );
						
						/** add label separator after the label **/
						if( labelSeparator ){
							tbarItems.push( '-' );
						}
					}
					
					/** add custom items after tbar label **/
					if( typeof me.extraTbar1 != 'undefined' ){
						for( var x in me.extraTbar1 ){
							tbarItems.push( me.extraTbar1[x] );
						}
					}

					/** add add button **/
					if( typeof me.content != 'undefined' ){
						if( me.content.toString().toLowerCase() === 'add' ){
							var noDeleteColumn	= ( typeof me.noDeleteColumn != 'undefined' )? me.noDeleteColumn : false
								,noUndoButton	= ( typeof me.noUndoButton != 'undefined' )? me.noUndoButton : true
								,undoStore;
							
							tbarItems.push(
								__gridAddItem( {
									addLabel			: ( typeof me.addLabel != 'undefined' ? me.addLabel : 'Add' )
									,editColumn			: me.editColumn
									,customAddHandler	: me.customAddHandler
									,beforeAddHandler	: me.beforeAddHandler
									,afterAddHandler	: me.afterAddHandler
									,module				: params.module
									,id					: params.id
								} )
							);
							
							/** automatically adds undo button **/
							if( !noUndoButton ){
								var model	= store.getProxy().getModel().modelName
									,fields	= Ext.ModelMgr.types[model].prototype.fields;
								fields.add( {	
									name	: 'numberRow'
									,type	: 'number'
								} );
								
								/** temporary storage for deleted rows **/
								undoStore = _createRemoteStore( {
									fields	: fields.items
									,url	: 'undoStore_' + params.id
								} );
								
								tbarItems.push({
									xtype		: 'button'
									,text		: 'Restore Deleted Items'
									,style		: 'margin-left:5px;'
									,cls		: 'toolBarItems'
									,border		: false
									,id			: 'undoButton_' + params.id
									,disabled	: true
									,handler	: function(){
										if( undoStore.getCount() > 0 ){
											var index	= undoStore.getCount()-1
												,latest	= undoStore.getAt( index ).data
												,data	= store.getAt( latest.numberRow )
												,valid	= false;
											
											if( data ){
												/** check if row contains valid data **/
												for( var key in data.data ){
													if(key != 'selected'){
														if( data.data[key] != null && data.data[key].toString() != '' && parseFloat( data.data[key], 10) != 0 ){
															valid = true;
															break;
														}
													}
												}
											}
											
											if( !valid ) store.removeAt( latest.numberRow );
												
											store.insert( latest.numberRow, latest );
											undoStore.removeAt( index );
											
											this.setDisabled( ( undoStore.getCount() > 0? false : true ) );
										}
									}
								} );
							}
							
							/** automatically adds delete column **/
							if( !noDeleteColumn ){
								
								params.columns.push(
									_createActionColumn( {
										icon	: 'remove',
										tooltip	: 'Remove record',
										Func	: ( typeof me.deleteRowFunc != 'undefined' )?
											me.deleteRowFunc : 
											function( data, row ){
												var valid = false;
												
												/** check if row contains valid data **/
												for( var key in data){
													if(key != 'selected'){
														if( data[key] != null && data[key].toString() != '' && parseFloat( data[key], 10) != 0 ){
															valid = true;
															break;
														}
													}
												}
												
												/** store to temp storage if row contains valid data **/
												if( !noUndoButton && valid ){
													data['numberRow'] = row;
													undoStore.add( data );
													Ext.getCmp( 'undoButton_' + params.id ).setDisabled( undoStore.getCount() > 0? false : true );
												}

												/** executes extra handler before removing row **/
												if( typeof me.beforeRemoveHandler != 'undefined' ){
													me.beforeRemoveHandler();
												}
												
												/** remove row if and only if row contains valid data **/
												if( store.getCount() == 1 ){

													if( valid ){
														store.removeAt( row );
														
														if( store.getCount() == 0 ) store.add( {} );
													}
													else{
														if( noDefaultRow ) store.removeAt( row );
													}
												}
												else store.removeAt( row );

												/** executes extra handler after removing row **/
												if( typeof me.afterRemoveHandler != 'undefined' ) me.afterRemoveHandler();
											}
									} )
								);
							}
						}

						/* Additional Content */
						if( typeof me.otherContent != 'undefined' ) tbarItems.push(me.otherContent)
					}
					
					if( typeof me.filter != 'undefined' ){
						me.filter.grid_id = params.id;
						me.filter.module  = params.module;
						tbarItems.push( _createComboSearch( me.filter )	);
					}
					
					/** add custom items after add button or combo field **/
					if( typeof me.extraTbar2 != 'undefined' ){
						for( var x in me.extraTbar2 ){
							tbarItems.push( me.extraTbar2[x] );
						}
					}
					
					tbarItems.push( '->' );
					
					/** add custom items before export buttons **/
					if( typeof me.extraTbar3 != 'undefined' ){
						for( var x in me.extraTbar3 ){
							tbarItems.push( me.extraTbar3[x] );
						}
					}
					
					/** add pdf and excel buttons **/
					if( canPrint ){
						if( !noExcel ){
							tbarItems.push( 
								{	xtype		: 'button'
									,iconCls	: 'excel'
									,handler	: function(){
										_listExcel( {
											grid					: Ext.getCmp( params.id )
											,module					: params.module
											,idModule				: params.idModule
											,route					: me.route
											,pageTitle				: me.pageTitle
											,customListExcelHandler	: ( typeof me.customListExcelHandler != 'undefined' )? me.customListExcelHandler : false
											,listExcelHandler		: ( typeof me.listExcelHandler != 'undefined' )? me.listExcelHandler : false
											,extraParams			: me.extraParams
										} );
									}
								}
							);
						}
						
						if( !noPDF ){
							tbarItems.push( 
								{	xtype		: 'button'
									,iconCls	: 'pdf-icon'
									,handler	: function(){
										_listPDF( {
											grid					: Ext.getCmp( params.id )
											,module					: params.module
											,idModule				: params.idModule
											,route					: me.route
											,pageTitle				: me.pageTitle
											,orientation			: ( typeof me.orientation != 'undefined' )? me.orientation : 'P'
											,customListPDFHandler	: ( typeof me.customListPDFHandler != 'undefined' )? me.customListPDFHandler : false
											,listPDFHandler			: ( typeof me.listPDFHandler != 'undefined' )? me.listPDFHandler : false
											,extraParams			: me.extraParams
											,isGae					: Ext.getConstant('ISGAE')
										} );
									}
								}
							);
						}
					}
					
					/** add custom items after export buttons **/
					if( typeof me.extraTbar4 != 'undefined' ){
						for( var x in me.extraTbar4 ){
							tbarItems.push( me.extraTbar4[x] );
						}
					}
				}
			}
			
			/** create toolbar component **/
			tbar = 	Ext.create( 'Ext.toolbar.Toolbar', {
				cls		: 'toolBButton'
				,height	: ( typeof me.tbarHeight != 'undefined'? me.tbarHeight : toolbarHeight )
				,items	: tbarItems
			} );
		}
		
		/** provide top toolbar if bbar is defined **/
		if( typeof params.bbar != 'undefined' ){
			bbar = Ext.create( 'Ext.toolbar.Toolbar', {
				cls		: 'toolBButton'
				,height	: ( typeof params.bbarHeight != 'undefined'? params.bbarHeight : toolbarHeight )
				,items	: ( params.bbar.toString() === 'empty' )? null : params.bbar
			} );
		}
		
		/** grid's plugin editor **/
		var plugins				= null
			,condition			= ''
			,conditionConnect	= false
			,requiredFields		= new Array();
		if( typeof params.plugins != 'undefined' ){
			var bbarItems	= new Array();
			
			if( typeof params.plugins == 'boolean'){
				if( params.plugins ){
					plugins = _cellEdit( params );
				}
			}
			else{
				plugins = params.plugins;
			}
			
			function _column(col){
				/** column editor and format **/
				if( typeof col.editor == 'string' ){
					var editor = col.editor;
					if( editor.toString() == 'float' || editor.toString() == 'number' || editor.toString() == 'text' ){
						if( editor.toString() != 'text' ){
							col.align = 'right';
							col.xtype = 'numbercolumn';
							col.format= ( editor.toString() == 'number' )? '0,000' : '0,000.00';
							
							/** for columns with currency  **/
							if( typeof col.hasCurrency != 'undefined' ){
								if( col.hasCurrency ){
									col.renderer = _currencyRenderer( editor == 'float' );
								}
							}
						}
						
						if(editor.toString() == 'text'){
							col.editor	= _createTextField( {
								isNumber		: ( editor.toString() == 'text' )? false : true
								,isDecimal		: ( editor.toString() == 'number' )? false : true
								,maxLength		: col.maxLength
								,submitValue	: false
								,fieldLabel		: ''
								,maskRe			: col.maskRe
							} );
						}
						else{
							col.editor	= _createNumberField( {
								submitValue				: false
								,allowDecimals			: ( typeof col.allowDecimals != 'undefined' ) ? col.allowDecimals : ( editor.toString() == 'number' ? false : true )
								,useThousandSeparator	: ( typeof col.useThousandSeparator != 'undefined' ) ? col.useThousandSeparator : true
							} );
						}
						
					}
					else if( editor.toString() == 'date' || editor.toString() == 'justNowAndForever' || editor.toString() == 'everSinceTheWorldBegun' ){
						col.editor	= _createDateField( {
							submitValue		: false
							,fieldLabel		: ''
							,minValue		: ( editor.toString() == 'justNowAndForever' ) ? new Date() : null
							,maxValue		: ( editor.toString() == 'everSinceTheWorldBegun' ) ? new Date() : null
						} );
						
						col.xtype  	= 'datecolumn';
					}
					else if( editor.toString() == 'time' ){
						col.editor	= _createTimeField( {
							submitValue		: false
							,fieldLabel		: ''
						} );
						
						col.renderer = _timeRenderer();
					}
				}
				
				if( column[x].xtype == 'numbercolumn' ) column[x].renderer 	= _numberColumnRendererfunction;
				
				if(column[x].xtype == 'datecolumn'){
					column[x].format 	= ( typeof column[x].format != 'undefined'? column[x].format : Ext.getConstant( 'DATEFORMAT' ) );
					column[x].align 	= 'right';
				}
				
				/** check has total columns **/
				if( !bbar ){
					if( typeof col.hasTotal != 'undefined' ){
						if( col.hasTotal ){
							countHasTotal++;
							col.summaryType		= 'sum';
							col.summaryRenderer	= ( typeof col.sumRenderer != 'undefined'? col.sumRenderer : _summaryNumberColumnRenderer )
						}
					}
				}
				
				/** set required fields. for non-list/history grids only **/
				if( typeof col.allowBlank != 'undefined' ){
					if( !col.allowBlank ){
						requiredFields.push( col.dataIndex );
						/** set condition as property **/
						if( col.xtype == 'numbercolumn' ){
							condition += ( conditionConnect? " && " : "" ) + "parseFloat( dataStore['" + col.dataIndex + "'], 10 ) != 0 ";
						}
						else{
							condition += ( conditionConnect? " && " : "" ) + "dataStore['" + col.dataIndex + "'].toString() != '' ";
						}
						if( !conditionConnect ){
							conditionConnect = true;
						}
					}
				}
				
				return col;
			}
			
			for( var x in column = params.columns ){
				if( column[x].columns && column[x].columns.length > 0 ){
					for( var y in columnitems = column[x].columns ){
						column[x].columns[y] =  _column( columnitems[y] );
					}
				}
				else{
					column[x] = _column( column[x] );
				}
			}
			
			/** insert has-total-fields to toolbar **/
			if( !bbar && bbarItems.length > 0 ){
				var marginRightBBar = 0;
				bbarItems.unshift( '->', {
					xtype	: 'label'
					,text	: ( typeof params.bbarTotalLabel != 'undefined' )? params.bbarTotalLabel : 'Total : '
				} );
				
				/** calculate with of all action column **/
				for( var x = params.columns.length-1; x>=0; x-- ){
					if( params.columns[x].xtype.toString() == 'gridcolumn' ){
						marginRightBBar += parseInt( params.columns[x].width, 10 );
					}
					else{
						break;
					}
				}
				
				/** push a component that serves as a margin to the right. width value depends on the above calculation **/
				bbarItems.push( {
					xtype	: 'box'
					,width	: marginRightBBar
				} );
				
				bbar = Ext.create( 'Ext.toolbar.Toolbar', {
					cls		: 'toolBButton'
					,height	: ( typeof params.bbarHeight != 'undefined'? params.bbarHeight : toolbarHeight )
					,items	: bbarItems
				} );
			}
		}
		else{
			for( var x in column = params.columns ){
				if( typeof column[x].hasCurrency != 'undefined' ){
					if( column[x].hasCurrency ){
						column[x].align		= 'right';
						column[x].xtype		= 'numbercolumn';
						column[x].format	= '0,000.00';
						column[x].renderer	= _currencyRenderer( true );
					}
				}
				
				if(column[x].xtype == 'datecolumn'){
					column[x].format 	= ( typeof column[x].format != 'undefined'? column[x].format : Ext.getConstant( 'DATEFORMAT' ) );
					column[x].align 	= 'right';
				}
				else if(column[x].xtype == 'numbercolumn'){
					column[x].align 	= 'right';
					column[x].renderer 	= _numberColumnRendererfunction;
				}
				
				if( typeof column[x].hasTotal != 'undefined' ){
					if( column[x].hasTotal ){
						var hasTotalType = ( typeof column[x].hasTotalType != 'undefined' ? 'running' : 'sum' );
						
						countHasTotal++;
						if(hasTotalType == 'running'){
							column[x].summaryType = function(record,index){
								if(record.length > 0){
									/** get last record **/
									value = record[record.length-1].get(index);
									return value;
								}
								return 0;
							};
						}
						else{
							column[x].summaryType = 'sum';
						}
						column[x].summaryRenderer = ( typeof column[x].sumRenderer != 'undefined' ? column[x].sumRenderer : _summaryNumberColumnRenderer );
					}
				}
			}
		}
		
		/** check if has total in columns **/
		if(countHasTotal > 0){
			var sumFeature = {
				ftype	: 'summary'
				,dock	: 'bottom'
			};
			if( Ext.isArray( features ) ){
				features.push( sumFeature );
			}
			else{
				features = sumFeature
			}
			
			params.noPage = true;
		}
		
		/** automatically insert paging toolbar if bbar is null **/
		if( !bbar ){
			var noPage = ( typeof params.noPage != 'undefined' )? params.noPage : false;
			if( !noPage ){
				dockedItems = Ext.create( 'define_pagingTbar', {
					store	: store
					,id		: 'pagingToolBar_' + params.id
				} );
			}
			else{
				if( typeof params.customBbar != 'undefined' ){
					dockedItems = params.customBbar 
				}
			}
		}
		
		var defaults = ( typeof params.defaults != 'undefined' )? params.defaults : {};
		defaults['menuDisabled'] = true;
		defaults['sortable']     = ( typeof params.sortable != 'undefined' )? params.sortable : true;
		
		/** store has default single row **/
		if( !noDefaultRow ){
			store.add({});
			store.commitChanges();
		}
		
		/** custom grid listeners **/
		var listeners			= ( typeof params.listeners != 'undefined' )? params.listeners : {}
			,noDefaultListeners	= ( typeof params.noDefaultListeners != 'undefined' )? params.noDefaultListeners : false;
		
		if( !noDefaultListeners ){
			listeners['select']	= function( grid, rec, row ){
				try{
					if( this.getStore().getAt( row ) ) this.getStore().getAt( row ).set( 'selected', 1 );
					if( typeof params.customSelectListener != 'undefined' ){
						params.customSelectListener( rec.data );
					}
				}
				catch( err ){
					console.log( err );
				}
			};
			
			listeners['selectionchange'] = function(){
				try{
					if( typeof SELECTED != 'undefined' ){
						if( this.getStore() ){
							if( this.getStore().getCount() > 1 ){
								if( this.getStore().getAt( SELECTED ) ) this.getStore().getAt( SELECTED ).set( 'selected', 0 );
							}
						}
					}
				}
				catch( err ){
					console.log( err );
				}
			};
			
			listeners['beforedeselect'] = function( grid, record, index ){
				SELECTED = index;
			};
			
			listeners['sortchange'] = function(){
				var select 	= this.getSelectionModel().getSelection()[0];
				SELECTED	= this.store.indexOf( select );
			};
		}
		return Ext.create( 'Ext.grid.Panel', {
			id				: params.id
			,store			: store
			,hidden			: params.hidden
			,height			: ( typeof params.height != 'undefined' )? params.height : 401
			,width			: params.width
			,columnWidth	: params.columnWidth
			,style			: params.style
			,plugins		: plugins
			,selModel		: params.selModel
			,requiredFields : requiredFields
			,condition		: condition
			,cls			: params.cls || ''
			,columns		: {
				defaults	: defaults
				,items		: params.columns
			}
			,features		: features
			,dockedItems	: dockedItems
			,tbar			: tbar
			,bbar			: bbar
			,hideHeaders	: params.hideHeaders
			,viewConfig		: ( typeof params.viewConfig != 'undefined' )? params.viewConfig : { markDirty:false }
			,listeners		: listeners
		} );
	}
	
	/* Create plugin editor for grid
		return : plugin
		parameters[
			
			PROPERTIES __________________
			sdateID			: [string] id for start date field.
			edateID			: [string] id for end date field.
			stimeID			: [string] id for start time field.
			etimeID			: [string] id for end time field.
			noTime			: [bool] id for end time field.
			fromFieldLabel	: [string] field label for start date field.
			fromLabelWidth	: [int] label width for start date/time field.
			fromWidth		: [int] width for start date/time field.
			labelWidth		: [int] label width for end date/time field.
			
		]
	*/
	
	function _cellEdit( params ){
		
		// console.log('abot pod ko dre');
		
		return Ext.create( 'Ext.grid.plugin.CellEditing', {
			clicksToEdit	: 1
			,pluginId		: params.pluginId
			,listeners		: {
				edit			: function( editor, e ){
					e.view.refreshNode( e.rowIdx );
				}
				,beforeedit		: function( me, parent ){
					if(params.extraBeforeEdit){
						var object	= {
							field	: parent.field
							,data	: parent.record.data
							,record	: parent.record
							,value	: parent.value
						}
						return params.extraBeforeEdit( me, parent, object );
					}
				}
				,afteredit		: function( me, parent ){
					if( params.extraAfterEdit ){
						var object	= {
							field	: parent.field
							,data	: parent.record.data
							,record	: parent.record
							,value	: parent.value
						}
						params.extraAfterEdit( me, parent, object );
					}
					
					try{
						parent.grid.up( 'form' ).fireEvent( 'fieldvaliditychange' );
					}
					catch( err ){
						console.log( err );
					}
					
					try{
						/* 
						commented efren : reason : ga cause og bug..
						var editor = b.column.getEditor();
					
						if(editor.xtype.toString() == 'numberfield' && (!editor.value)){
							b.record.set(b.column.dataIndex,0);	
						} */
						if( typeof params.noRefresh == 'undefined' || !params.noRefresh ) Ext.getCmp(params.id).getView().refresh();
					}catch(err){}
					
					if( typeof params.afteredit != 'undefined' ) params.afteredit( me, parent );
					
					
					
					/*THIS CODE IS FOR CMBITEMS STANDARDS CHECKING IF ITEM NAME DOES NOT EXISTS*/
						var field	= parent.column.field
							,data	= parent.record;
						if( typeof field.cls != 'undefined' && field.cls.contains( 'cmbItemIdentifier' ) ){
							var found	= field.store.findExact( 'code', field.getRawValue() )
								,found1	= field.store.findExact( 'name', field.getRawValue() );
							if( found == -1 && found1 == -1 ){
								data.reject();
							}
						}
					/*END HERE CHECKING*/
				}
			}
		} );
	}
	
	/* Create file upload field
		return : container
		parameters[
			
			PROPERTIES __________________
			id				: [string] serves as an ID and name for the component.
			buttonOnly		: [bool] restrict button's view whether to display label or not. Default = false
			xCor			: [int] x-axis coordinate.
			yCor			: [int] y-axis coordinate.
			buttonText		: [string] label for upload button.
			iconCls			: [string] icon cls for upload button.
			format			: [expression] file format to be upload. Default = /^.*\.(jpg|JPG|png|PNG)$/
			
			see property details for other parameters @ ExtDocs
			
		]
	*/
	
	function _createFileUpload( params ){
		var allowBlank		= ( typeof params.allowBlank != 'undefined' )? params.allowBlank : true
		var withREQ    		= ( typeof params.withREQ != 'undefined' )? params.withREQ : true;
		var fieldLabel		= ( typeof params.fieldLabel != 'undefined' )? params.fieldLabel + ( !allowBlank? ( withREQ? Ext.getConstant( 'REQ' ) : '' ) : '' ) : '';
		return Ext.create('Ext.form.field.File',{
			name			: params.id
			,id				: params.id
			,x				: params.xCor
			,y				: params.yCor
			,hidden			: params.hidden
			,style			: ( typeof params.style != 'undefined' )? params.style : 'margin-top:3px'
			,msgTarget		: 'under'
			,buttonConfig	: {
				text		: params.buttonText
				,iconCls	: params.iconCls
			}
			,width			: ( typeof params.width != 'undefined' )? params.width : Ext.getConstant( 'DEF_WIDTH' )
			,labelWidth		: ( typeof params.labelWidth != 'undefined' )? params.labelWidth : Ext.getConstant( 'DEF_LABEL_WIDTH' )
			,buttonOnly		: ( typeof params.buttonOnly != 'undefined' )? params.buttonOnly : false
			,allowBlank		: ( typeof params.allowBlank != 'undefined' )? params.allowBlank : true
			,disabled		: ( typeof params.disabled != 'undefined' )? params.disabled : false
			,fieldLabel		: fieldLabel
			,validator		: function( value ){
				/** validations **/
				if( value ){
					var file	= this.getEl().down( 'input[type=file]' ).dom.files[0]
						,format	= (typeof params.format != 'undefined' )? params.format : /^.*\.(jpg|JPG|png|PNG|gif|GIF|xlsx|xls)$/;
					if( format.test( value ) ){
						/** if file size exceeds to limit **/
						if( !params.hasNoLimit ){
							if( parseInt( file.size ) > 5000000 ){
								_createMessageBox( {
									msg : 'EXCEED5'
								} );
								return false;
							}
							else return true;
						}
						else return true;
					}
					else{
						/** for invalid file extentions **/
						if( params.showMessageBox ){
							_createMessageBox( { msg: ( params.customMessage? params.customMessage : 'Invalid file format.' ), icon: 'error' } );
						}
						return 'Invalid file format.';
					}
				}
				else return true;
			}
			,listeners		: {
				change	: function(){
					var file = this.getEl().down('input[type=file]').dom.files[0];
					/** checks validity of the format **/
					if( file && this.isValid() ){
						if( !params.submitOnSelect ){
							var reader = new FileReader();
							reader.onload = function( e ){
								if( typeof params.listChange != 'undefined' ){
									params.listChange();
								}
								if( Ext.getDom( params.imageBox ) ){
									Ext.getDom( params.imageBox ).src = e.target.result;
								}
							}
							reader.readAsDataURL( file );
						}
						else{
							params.changeFunction( params.module.getForm() )
						}
					}
					else{
						var noReset = (typeof params.noReset != 'undefined')? params.noReset : false;
						if( !noReset ){
							this.reset();
						}
					}
				}
			}
		});
	}
	
	function _createImageUpload( params ){
		var uploadID	= ( typeof params.uploadID != 'undefined' )? params.uploadID : 'fieldUpload' + params.module
			,resetID	= ( typeof params.resetID != 'undefined' )? params.resetID : 'resetUpload' + params.module
			,boxID		= ( typeof params.boxID != 'undefined' )? params.boxID : 'boxUpload'+params.module
			,boxWidth	= ( typeof params.boxWidth != 'undefined' )? params.boxWidth : 350
			,boxHeight	= ( typeof params.boxHeight != 'undefined' )? params.boxHeight : 180
			,uploadX	= ( typeof params.uploadX != 'undefined' )? params.uploadX : 295
			,uploadY	= ( typeof params.uploadY != 'undefined' )? params.uploadY : 147
			,resetX		= ( typeof params.resetX != 'undefined' )? params.resetX : 320
			,resetY		= ( typeof params.resetY != 'undefined' )? params.resetY : 150
			,logoPath	= ( typeof params.defImage != 'undefined' )? params.defImage : Ext.getConstant( 'BASEURL' )+'images/empty.jpg';
		
		return {
			xtype	: 'container'
			,height	: boxHeight + 10
			,width	: boxWidth + 2
			,items	: [
				{	xtype		: 'container'
					,layout		: 'absolute'
					,style		: params.style
					,listeners	: {
						afterrender	: function( me ){
							me.el.on('mouseover', function() {
								Ext.getCmp( uploadID ).setVisible( true );
								Ext.getCmp( resetID ).setVisible( true );
							} );
							me.el.on( 'mouseout', function() {
								Ext.getCmp( uploadID ).setVisible( false );
								Ext.getCmp( resetID ).setVisible( false );
							} );
						}
					}
					,items		: [
						Ext.create(	'Ext.form.Label', {
							x		: 0
							,y		: 0
							,html	: '<img id="' + boxID + '" width="' + boxWidth + '" height="' + boxHeight + '" src="' + logoPath + '" style="border:1px solid black;" /> '
						})
						,_createFileUpload( {
							id			: uploadID
							,imageBox	: boxID
							,text		: 'Browse'
							,buttonOnly	: true
							,hidden		: true
							,iconCls	: 'glyphicon glyphicon-folder-open'
							,xCor		: uploadX
							,yCor		: uploadY
						} )
						,{	xtype		: 'button'
							,iconCls	: 'glyphicon glyphicon-remove-sign'
							,id			: resetID
							,x			: resetX
							,y			: resetY
							,hidden		: true
							,handler	: function(){
								if( typeof params.customReset != 'undefined' ){
									params.customReset( {
										uploadCmp	: Ext.getCmp( uploadID )
										,boxCmp		: Ext.getDom( boxID )
									} );
								}
								else{
									Ext.getCmp( uploadID ).reset();
									Ext.getDom( boxID ).src = logoPath;
								}
							}
						}
					]
				}
			]
		};
	}
	
	function _listExcel( params ){
		if( params.grid.store.getCount() == 0 ){
			_createMessageBox({
				msg : 'NOREC_PRINT'
			});
			return;
		}
		
		if( params.customListExcelHandler ){
			params.customListExcelHandler();
		}
		else if( params.listExcelHandler ){

			var headerArray = {};
			var dIndexArray = {};
			
			for( var x in column = params.grid.columns ){
				if( column[x].dataIndex ){
					var col = parseInt( x, 10 )+1;
					headerArray['col'+col] = column[x].text;
					dIndexArray['col'+col] = column[x].dataIndex;
				}
			}

			Ext.Ajax.request( {
				url			: params.route+'printEXCELList'
				,params		: {
					title			: params.pageTitle,
					headerArray		: Ext.encode( [headerArray] ),
					dIndexArray		: Ext.encode( [dIndexArray] )
				}
				,success	: function(){
					window.open( params.route + "download/" + params.pageTitle +  ' List' );
				}
			} );
		}
		else{
			var headerArray		= {}
				,dIndexArray	= {}
				,filters		= []
				,form			= params.module.getForm( true );
			
			if( filter = Ext.getCmp( 'searchFilterContainer' + params.module ) ){
				for( x in field = filter.items.items ){
					if( field[x].isFormField ){
						filters.push( { v1:field[x].getRawValue(), v2:field[x].getValue() } );
					}
				}
			}
			
			for( var x in column = params.grid.columns ){
				if( column[x].dataIndex ){
					var col = parseInt( x, 10 ) + 1;
					headerArray['col'+col] = column[x].text;
					dIndexArray['col'+col] = column[x].dataIndex;
				}
			}
			
			var path  = params.route.replace( Ext.getConstant( 'BASEURL' ), '' );
			var index = path.indexOf( '/' );
			
			var extraParams = params.extraParams || {};
			extraParams.isTransaction = typeof filter != 'undefined' && filter.isTransaction ? 1 : 0;
			var arrExtraParams = {};
			if( Ext.Object.getSize( extraParams ) > 0 ){
				for( var key in extraParams ){
					if( extraParams.hasOwnProperty( key ) ){
						if( typeof extraParams[key] == 'function' ){
							arrExtraParams[key] = eval( extraParams[key]() );
						}
						else{
							arrExtraParams[key] = extraParams[key];
						}
					}
				}
			}
			
			Ext.Ajax.request( {
				url		: Ext.getConstant( 'STANDARD_ROUTE' ) + 'listExcel'
				,params	: {
					title 		 	: params.pageTitle
					,idModule		: params.idModule
					,headerArray 	: Ext.encode( [headerArray] )
					,dIndexArray 	: Ext.encode( [dIndexArray] )
					,folder		 	: path.substring( 0, index )
					,moduleName  	: path.substring( index + 1, index + 2 ).toUpperCase() + "" + path.substring( index + 2, path.length - 1 )
					,filters     	: Ext.encode( filters )
					,extraParams 	: Ext.encode(arrExtraParams)
				}
				,success: function(){
					window.open( Ext.getConstant( 'STANDARD_ROUTE' ) + "download/" + params.pageTitle + ' List' + '/' + path.substring( 0, index ) );
				}
			});
		}
	}
	
	function _listPDF( params ){
		if( params.grid.store.getCount() == 0 ){
			_createMessageBox( {
				msg : 'NOREC_PRINT'
			} );
			return;
		}
		
		if( !params.customListPDFHandler ){
			var path1  = params.route.replace( Ext.getConstant( 'BASEURL' ), Ext.getConstant( 'BASEURL' ) + 'pdf/' );
			var module = params.module.toString().substring( 1, params.module.toString().length );
			var path2  = path1.substring( 0,( path1.length - ( module.length + 1 ) ) ) + '/';
			var title  = path2 + '' + params.pageTitle + ' List.pdf';
		}
		
		if( params.customListPDFHandler ){
			params.customListPDFHandler();
		}
		else if( params.listPDFHandler ){
			var columnArray = [];

			for( var x in column = params.grid.columns ){
				
				if( column[x].dataIndex ){
					columnArray.push( {
						header 		: column[x].text
						,dataIndex 	: column[x].dataIndex
						,type 		: column[x].xtype
						,hasTotal   : ( typeof column[x].summaryType != 'undefined' )
						,width		: ( typeof column[x].columnWidth != 'undefined' )? column[x].columnWidth : 15
						,format 	: column[x].format
					} );
				}
			}

			Ext.Ajax.request( {
				url			: params.route+'printPDFList'
				,params		: {
					title		: params.pageTitle,
					columnArray	: Ext.encode( columnArray ),
					idModule	: params.idModule
				}
				,success	: function(){
				
					if( Ext.getConstant('ISGAE') ){
						window.open( params.route + 'viewPDF/' + title, '_blank' );
					}
					else{
						window.open( title+'?_dc='+( new Date() ).getTime() );
					}
				}
			});
		}
		else{
			var columnArray = [];
			var filters     = [];
			var form 		= params.module.getForm( true );
			
			if( filter = Ext.getCmp( 'searchFilterContainer' + params.module ) ){
				for( x in field = filter.items.items ){
					if( field[x].isFormField ){
						filters.push({ v1:field[x].getRawValue(), v2:field[x].getValue() });
					}
				}
			}

			for( var x in column = params.grid.columns ){
				
				if( column[x].dataIndex ){
					columnArray.push( {
						header 		: column[x].text
						,dataIndex 	: column[x].dataIndex
						,type 		: column[x].xtype
						,hasTotal   : ( typeof column[x].summaryType != 'undefined' )
						,width		: ( typeof column[x].columnWidth != 'undefined' )? column[x].columnWidth : 15
						,format 	: column[x].format
					} );
				}
			}
			
			var path  = params.route.replace( Ext.getConstant( 'BASEURL' ), '' );
			var index = path.indexOf( '/' );
			
			var extraParams = params.extraParams || {};
			extraParams.isTransaction = typeof filter != 'undefined' && filter.isTransaction ? 1 : 0;
			var arrExtraParams = {};
			if( Ext.Object.getSize( extraParams ) > 0 ){
				for( var key in extraParams ){
					if( extraParams.hasOwnProperty( key ) ){
						if( typeof extraParams[key] == 'function' ){
							arrExtraParams[key] = eval( extraParams[key]() );
						}
						else{
							arrExtraParams[key] = extraParams[key];
						}
					}
				}
			}
			
			Ext.Ajax.request( {
				url			: Ext.getConstant( 'STANDARD_ROUTE' )+'listPDF'
				,params		: {
					title 		 	: params.pageTitle
					,idModule		: params.idModule
					,columnArray 	: Ext.encode( columnArray )
					,folder		 	: path.substring( 0, index )
					,moduleName  	: path.substring( index + 1, index + 2 ).toUpperCase() + "" + path.substring( index + 2, path.length - 1 )
					,filters     	: Ext.encode( filters )
					,orientation 	: params.orientation
					,extraParams	: Ext.encode( arrExtraParams )
				}
				,success	: function(){
					if( Ext.getConstant( 'ISGAE' ) ){
						window.open( params.route + 'viewPDF/' + params.pageTitle + ' List', '_blank' );
					}
					else{
						window.open( title + '?_dc=' + ( new Date() ).getTime() );
					}
				}
			} );
		}
	}
	
	function _consoleDebuger_developmentOnly( functionName, params ){
		var wpar = ' : wrong parameter for';
		var xpar = ' : missing parameter for';
		
		if( functionName === '_mainPanel' ){
			if( typeof params.moduleType != 'undefined' ){
				if( params.moduleType != 'form' && params.moduleType != 'report' ){
					console.info( functionName + wpar + ' moduleType' );
				}
			}
			
			if( typeof params.tbar != 'object' ){
				if( params.tbar.toString() != 'empty'){
					console.info( functionName + wpar + ' tbar' );
				}
			}
			if( params.moduleType.toString() == 'report'){
				if( typeof params.moduleGrids == 'function' ){
					console.info( functionName + wpar + ' moduleGrids' );
				}
			}
			
			if( typeof params.formItems != 'undefined' ){
				for( var x in params.formItems ){
					if( typeof params.formItems[x] == 'function' ){
						console.info( functionName + wpar + ' formItems' );
					}
				}
			}
		}
		else if( functionName === '_gridPanel' ){
			if( typeof params.store == 'undefined' ){
				console.info( functionName + wpar + ' store' );
			}
			if( typeof params.tbar == 'string' ){
				if( params.tbar != 'empty' ){
					console.info( functionName + xpar + ' tbar' );
				}
			}
		}
		else if( functionName === '_createFileUpload' ){
			if( typeof params.id == 'undefined' ){
				console.info( functionName + xpar + 'id' );
			}
		}
		
		return false;
	}
	
	function _createNumberField( params ){
		var allowBlank				= ( typeof params.allowBlank != 'undefined' )? params.allowBlank : true
			,withREQ				= ( typeof params.withREQ != 'undefined' )? params.withREQ : true
			,fieldLabel				= ( typeof params.fieldLabel != 'undefined' )? params.fieldLabel + ( !allowBlank? ( withREQ? Ext.getConstant( 'REQ' ) : '' ) : '' ) : ''
			,useThousandSeparator	= ( typeof params.useThousandSeparator != 'undefined' )? params.useThousandSeparator : true;
		
		var field = Ext.create( 'widget.numericfield', {
			id						: params.id
			,name					: params.id
			,fieldLabel				: fieldLabel
			,width					: ( typeof params.width != 'undefined' )? params.width : Ext.getConstant( 'DEF_WIDTH' )
			,labelWidth				: ( typeof params.labelWidth != 'undefined' )? params.labelWidth : Ext.getConstant( 'DEF_LABEL_WIDTH' )
			,maxLength				: ( typeof params.maxLength != 'undefined' )? params.maxLength : 24
			,value					: params.value || 0
			,hideTrigger			: (typeof params.hideTrigger != 'undefined' ? params.hideTrigger : true)
			,allowBlank				: allowBlank
			,style					: params.style
			,hidden					: params.hidden
			,readOnly				: params.readOnly
			,submitValue			: (typeof params.submitValue != 'undefined' ? params.submitValue:true)
			,decimalPrecision		:(typeof params.decimalPrecision != 'undefined' ? params.decimalPrecision : 2)
			,minValue				:  (params.minValue ? params.minValue : 0 )
			,maxValue				: (params.maxValue ? params.maxValue : Number.MAX_VALUE )
			,enforceMaxLength		: true
			,allowDecimals			: (typeof params.allowDecimals != 'undefined' ? params.allowDecimals:true)
			,allowExponential		: ((params.allowExponential)? params.allowExponential : true)
			,labelSeparator 		: (params.labelSeparator)? '':':'
			,labelAlign 			: ((params.labelAlign)? params.labelAlign : 'left')
			,cls					: params.cls
			,disabled				: ( typeof params.disabled != 'undefined' )? params.disabled : false
			,emptyText 				: params.emptyText
			,useThousandSeparator	: useThousandSeparator
			,allowPureDecimal		: params.allowPureDecimal || true
		} );
		
		field.addListener( params.listeners );
		return field;
	}
	
	/*record = store record, except=exclude the index*/
	function _rejectRecord(record,except){
		except = (except ? except : []);
		var p = record.data;
		for(var key in p) {
			var found = except.indexOf(key);
			if(found == -1){
				try{
					if(p.hasOwnProperty(key)) {
						record.set(key,null);
					}
				}catch(err){
					record.set(key,'');
				}
			}
		}
	}

	/* standard for month drop down
	*/
	function _cmbMonth( params ){
		var id		= ( typeof params.id != 'undefined' ? params.id : 'month' + params.module );
		var store	= _createLocalStore( {
			data: [
				'January'
				,'February'
				,'March'
				,'April'
				,'May'
				,'June'
				,'July'
				,'August'
				,'September'
				,'October'
				,'November'
				,'December'
			]
			,startAt    : 1
		} );
		
		return _createCombo( {
			id				: id
			,fieldLabel		: params.fieldLabel || ''
			,labelWidth		: params.labelWidth
			,module			: params.module
			,store			: store
			,editable		: false
			,allowBlank		: params.allowBlank
			,width			: params.width
			,style			: params.style
			,readOnly		: ( typeof params.readOnly != 'undefined' )? params.readOnly : false
			,submitValue	: params.submitValue
			,listeners		: params.listeners
			,value			: ( typeof params.value != 'undefined'? params.value : ( ( new Date() ).getMonth() + 1 ) )
			,emptyText		: ( typeof params.emptyText != 'undefined' ? params.emptyText : 'Select month...' )
		} );
	}

	function setAllowBlankComponent( params ){
		var ccComp = params.component;
		
		ccComp.allowBlank = true;
		ccComp.setFieldLabel( ccComp.getFieldLabel().replace( Ext.getConstant( 'REQ' ), "" ) );
		
		if( !params.allowBlank ){
			ccComp.allowBlank = false;
			ccComp.setFieldLabel( ccComp.getFieldLabel() + Ext.getConstant( 'REQ' ) );
		}
	}
	
	function getFormDetailsAsObject( params ){
		var obj = {};
		console.warn( 'mainFormPanel' + params.module );
		Ext.getCmp( 'mainFormPanel' + params.module ).getForm().getFields().items.forEach( function( item ){
			var realID = item.id.replace( new RegExp( params.module, 'g' ), '' );
			var id = ( typeof params.type != 'undefined' ? params.type : 'pdf_' )  + realID;
			if( item.xtype == 'combobox' ){
				obj[id] = item.getDisplayValue();
			}
			else if( item.xtype == 'checkboxfield' ){
				obj[id] = item.getValue() ? 1 : 0;
			}
			else if( item.xtype == 'numberfield' ){
				obj[id] = item.getRawValue();
			}
			else if( item.xtype == 'datefield' ){
				obj[id] = Ext.Date.format(item.getValue(),Ext.getConstant('DATE_FORMAT')) ;
			}
			else{
				obj[id] = item.getValue();
			}
			
			if( params.getSubmitValue == true ){
				obj[realID] = item.getSubmitValue();
			}
		} );
		return obj;
	}
	
	function goToTransaction( params ){
		if( params.text ){
			params.invoiceID = params.invoiceID || 0;
			params.bankreconID = params.bankreconID || 0;
			return '<span class="gotoformlink" onclick="mainView.gotoTrans(' + params.invoiceID + ',' + params.bankreconID + ',1)">' + params.text + '</span>'; 
		}
		else{
			return '';
		}
	}

	function _createRadioFields( params ) {
		return {
			xtype			: 'fieldcontainer'
			,fieldLabel		: ( typeof params.fieldLabel != 'undefined' ? params.fieldLabel : '' )
			,defaultType	: 'radiofield'
			,style			: ( typeof params.style != 'undefined' ) ? params.style : ''
			,defaults		: {
				flex	: 1
			}
			,layout			: ( typeof params.layout != 'undefined' ) ? params.layout : 'hbox'
			,items			: params.radioFields
		}
	}

	function _createRadioGroup( params ) {

		return {
			xtype		: 'radiogroup'
			,id			: params.id
			,allowBlank	: (typeof params.width != 'undefined' ) ? params.allowBlank: true
			,width		: (typeof params.width != 'undefined' ) ? params.width: 100
			,fieldLabel	: ( typeof params.fieldLabel != 'undefined' ? params.fieldLabel : '')
			,labelWidth	: ( typeof params.labelWidth != 'undefined' ) ? params.labelWidth : Ext.getConstant( 'DEF_LABEL_WIDTH' )
			,style		: ( typeof params.style != 'undefined' ) ? params.style : ''
			,defaults	: {
				flex	: 1
			}
			,items		: params.radioFields
		}
		
	}
	
	function _createComboSearch( params ){
		var customeIndicator = 0;
		var items 			= new Array()
			,hasSubFilter	= ( typeof params.filterByData != 'undefined' )? true : false
			,hasDateRange	= ( typeof params.hasDateRange != 'undefined' )? params.hasDateRange : false
			,mainStore		= _createRemoteStore( {
				fields	: [ 'id', 'name' ]
				,url	: ( typeof params.searchURL != 'undefined'? params.searchURL : Ext.getConstant( 'STANDARD_ROUTE' ) + 'getComboSearch' )
			} );
		if( hasSubFilter ){
			var subFilterStore = _createLocalStore( {
				fields	: [
					'name'
					,'tableNameColumn'
					,'tableIDColumn'
					,'tableName'
					,{ 'name'	: 'isDateRange'
						,'type'	: 'number'
					}
					,'defaultValue'
					,'dataURL' // NEW
					,'hasAll' // NEW
				]
				,data	: params.filterByData
			} )

			if( typeof params.module == 'undefined' ) params.module = params.config.module

			items.push(
				_createCombo( {
					fieldLabel		: 'Search by'
					,id				: 'filterBy' + params.module
					,store			: subFilterStore
					,valueField		: 'tableIDColumn'
					,labelWidth		: 60
					,displayField	: 'name'
					,width			: 200
					,value			: params.subFilterDefValue
					,editable		: params.editableSearchBy
					,listeners		: {
						select	: function( me, record ){
							
							var filterValue	= Ext.getCmp( 'filterValue' + params.module )
								,dateRangeCon	= Ext.getCmp( 'filterDateRange' + params.module );
							if( Ext.getCmp( 'sdate' + params.module ) ) Ext.getCmp( 'sdate' + params.module ).reset();
							if( Ext.getCmp( 'edate' + params.module ) ) Ext.getCmp( 'edate' + params.module ).reset();
							if( parseInt( record[0].get( 'isDateRange' ), 10 ) == 1 ){
								if( dateRangeCon ) dateRangeCon.setVisible( true );
								filterValue.setVisible( false );
								filterValue.reset();
							}
							else{
								if( dateRangeCon ) dateRangeCon.setVisible( false );
								filterValue.setVisible( true );
								filterValue.reset();

								/* CODE SNIPPET FROM KAO MAS STANDARDS */
								if( record[0].get( 'dataURL' ) ){
									filterValue.store.proxy.url = record[0].get( 'dataURL' );
									filterValue.emptyText	= 'Search ' + this.getRawValue().toLowerCase() + ' here...';
									filterValue.applyEmptyText();
									filterValue.store.load( {
										callback	: function(){
											if( record[0].get( 'defaultValue' ) ) filterValue.setValue( record[0].get( 'defaultValue' ) );
											else filterValue.reset();
										}
									} );
								}
								else{
									filterValue.store.proxy.url = Ext.getConstant( 'STANDARD_ROUTE' ) + 'getComboSearch';
									filterValue.store.proxy.extraParams = {
										tableName			: record[0].get( 'tableName' )
										,tableNameColumn	: record[0].get( 'tableNameColumn' )
										,tableIDColumn		: record[0].get( 'tableIDColumn' )
										,statusColumn		: record[0].get( 'statusColumn' )
										,statusValue		: record[0].get( 'statusValue' )
										,hasAll				: ( record[0].get( 'hasAll' ) || 0 )
									};
									filterValue.emptyText	= 'Search ' + this.getRawValue().toLowerCase() + ' here...';
									filterValue.applyEmptyText();
									filterValue.store.load( {
										callback: function(){
											if( record[0].get( 'defaultValue' ) ) filterValue.setValue( record[0].get( 'defaultValue' ) );
											else filterValue.reset();
										}
									} );
								}
								

								/* ORIGINAL CODING FOR KIOKONG [ STARTS HERE ] */ 

								// filterValue.store.proxy.extraParams = {
								// 	tableName			: record[0].get( 'tableName' )
								// 	,tableNameColumn	: record[0].get( 'tableNameColumn' )
								// 	,tableIDColumn		: record[0].get( 'tableIDColumn' )
								// };
								// filterValue.emptyText	= 'Search ' + this.getRawValue() + ' here...';
								// filterValue.applyEmptyText();
								// filterValue.store.load( {
								// 	callback: function(){
								// 		if( record[0].defaultValue ) {
								// 			filterValue.setValue( record[0].defaultValue );
								// 			filterValue.reset();
								// 		}
								// 		else filterValue.reset();
								// 	}
								// } );

								/* ORIGINAL CODING FOR KIOKONG [ ENDS HERE ] */ 
							}
						}
						,afterrender : function() {
							if( typeof params.subFilterDefValue != 'undefined' ){
								var filterValue	= Ext.getCmp( 'filterValue' + params.module )
									,filterBy	= Ext.getCmp( 'filterBy' + params.module );
								rec = filterBy.store.findRecord( 'tableNameColumn', params.subFilterDefValue );
								if( rec.get( 'dataURL' ) ) filterValue.store.proxy.url = rec.get( 'dataURL' );
								else filterValue.store.proxy.url = Ext.getConstant( 'STANDARD_ROUTE' ) + 'getComboSearch';
								filterValue.store.proxy.extraParams	= {
									tableName			: rec.get( 'tableName' )
									,tableNameColumn	: rec.get( 'tableNameColumn' )
									,tableIDColumn		: rec.get( 'tableIDColumn' )
									,statusColumn		: rec.get( 'statusColumn' )
									,statusValue		: rec.get( 'statusValue' )
									,hasAll				: ( rec.get( 'hasAll' ) || 0 )
								}
								
								filterValue.store.load( {
									callback	: function(){
										if( rec.get( 'defaultValue' ) ) filterValue.setValue( rec.get( 'defaultValue' ) );
										else filterValue.reset();
									}
								} )

							}
							
						}
					}
				} )
			);
		}
		
		items.push(
			_createCombo( {
				fieldLabel		: ( hasSubFilter )? '' : 'Search'
				,store			: mainStore
				,id				: 'filterValue' + params.module
				,displayField	: 'name'
				,valueField		: 'id'
				,value			: 0
				,width			: 250
				,labelWidth		: ( hasSubFilter )? 0 : 40
				,emptyText		: ( typeof params.emptyText != 'undefined' ? params.emptyText : 'Search here...')
				,hideTrigger	: true
				,style			: ( hasSubFilter )? 'margin-left: 5px;' : ''
			} )
		);

		if( hasDateRange ){
			items.push(
				_createDateRange( {
					noFromLabel		: true
					,fromLabelWidth	: 0
					,id				: 'filterDateRange' + params.module
					,module			: params.module
					,hidden			: params.dateRangeHidden
					,noFieldLabel	: true
					,fromWidth		: 120
					,styleFrom		: 'margin-left: 5px;'
				} )
			)
		}

		items.push( {
			xtype		: 'button'
			,iconCls	: 'glyphicon glyphicon-search'
			,text		: 'Search'
			,style		: 'margin-left: 5px;'
			,handler	: function(){
				
				var filterParams = new Array()
					,store;
				if( hasDateRange ){
					filterParams['sdate'] = Ext.getCmp( 'sdate' + params.module ).value
					filterParams['edate'] = Ext.getCmp( 'edate' + params.module ).value
				}
				if( hasSubFilter ) filterParams['filterBy'] = Ext.getCmp( 'filterBy' + params.module ).value
				filterParams['filterValue'] = Ext.getCmp( 'filterValue' + params.module ).value
				
				if( typeof params.grid_id != 'undefined' ){
					store = Ext.getCmp( params.grid_id ).store;
				}
				else{
					if( Ext.getCmp( 'mainListPanel' + params.config.module ) )
					{
						store = Ext.getCmp( 'mainListPanel' + params.config.module ).down( 'grid' ).store;
						// console.log(store)
						// console.log('nisulod dre nga store');
					}
				}
				
				// console.log('mao ni nga button kay')
				// console.log( params.config.module )
				// console.log(store )
				
				
				if( store ){
					store.proxy.extraParams = filterParams
					store.currentPage = 1;
					store.load();
				}
			}
		} )

		items.push( {
			xtype		: 'button'
			,iconCls	: 'glyphicon glyphicon-refresh'
			,text		: 'Reset'
			,style		: 'margin-left: 5px;'
			,handler	: function(){
				var store = '';
				if( hasDateRange ){
					if( Ext.getCmp( 'filterDateRange' + params.module ).isVisible() ){
						Ext.getCmp( 'sdate' + params.module ).reset()
						Ext.getCmp( 'edate' + params.module ).reset();
					}
				}
				
				
				var filterByValueHolder = ( Ext.getCmp( 'filterValue' + params.module ) ? Ext.getCmp( 'filterValue' + params.module ).id : 'undefined' );
				var filterByHolder = ( Ext.getCmp( 'filterBy' + params.module ) ? Ext.getCmp( 'filterBy' + params.module ).id : 'undefined' );

				

				if(filterByValueHolder !== null && filterByValueHolder != 'undefined' ){

					Ext.getCmp( filterByValueHolder ).reset();
					Ext.getCmp( filterByValueHolder ).emptyText	= 'Search here...';
					if( Ext.getCmp( filterByHolder ) ) Ext.getCmp( filterByHolder ).reset();
					Ext.getCmp( 'gridHistory' + params.config.module ).store.removeAll()
					Ext.getCmp( 'gridHistory' + params.config.module ).store.load( { 
						params: { filterValue: '' ,filterBy: '' }
						,callback : function(){ Ext.getCmp( 'gridHistory' +  params.config.module ).getView().refresh(); }
					})
					var customeIndicator = 1;
				}else{
					Ext.getCmp( 'filterValue' + params.module ).reset();
					Ext.getCmp( 'filterBy' + params.module ).reset();
				}
				
				if( typeof params.grid_id != 'undefined' ){
					store = Ext.getCmp( params.grid_id ).store;
				}
				else{
					if( Ext.getCmp( 'mainListPanel' + params.module ) ) store = Ext.getCmp( 'mainListPanel' + params.module ).down( 'grid' ).store;
				}
				if( store ){
					if( typeof params.config != 'undefined'){
						store.proxy.extraParams = {};

						if( typeof params.config.idAffiliate != 'undefined' ) store.proxy.extraParams['idAffiliate'] = params.config.idAffiliate;
						
						store.currentPage = 1;
						store.load();
					}

					
				}
			}
		} )
		
		return {
			xtype			: 'container'
			,layout			: 'hbox'
			,style			: 'margin-left:5px'
			,id				: 'searchFilterContainer' + params.module
			,isTransaction	: params.isTransaction || 0
			,items			: items
		}
	}

	function _createSupplierCombo( params ){
		var supplierStore = _createRemoteStore( {
			fields		: [ { name: 'id', type: 'number' }, 'name' ]
			,url		: Ext.getConstant( 'STANDARD_ROUTE' ) + 'getSupplier'
			,autoLoad	: true
		} )

		supplierStore.proxy.extraParams.nameField = params.nameField;
		supplierStore.proxy.extraParams.idField = params.idField;
		supplierStore.proxy.extraParams.tableFrom = params.tableFrom;
		if( typeof params.hasAll != 'undefined' ) supplierStore.proxy.extraParams.hasAll = params.hasAll;

		return _createCombo( {
			id				: ( typeof params.id != 'undefined' )? params.id : 'pCode' + params.module
			,fieldLabel		: ( typeof params.fieldLabel != 'undefined' )? params.fieldLabel : 'Supplier Name'
			,store			: ( typeof params.store != 'undefined' ) ? params.store : supplierStore
			,valueField		: 'id'
			,displayField	: 'name'
			,labelWidth		: params.labelWidth
			,width			: params.width
			,allowBlank		: ( typeof params.allowBlank != 'undefined' )? params.allowBlank : false
			,listeners		: params.listeners
		} )
	}

	function _createCustomerCombo( params ){
		var customerStore	= _createRemoteStore( {
			fields		: [ {name: 'id', type: 'number'}
							, 'name', 'withCreditLimit' ]
			,url		: Ext.getConstant( 'STANDARD_ROUTE' ) + 'getCustomer'
			,autoLoad	: true
		} )

		customerStore.proxy.extraParams = {
			nameField	: params.nameField
			,idField	: params.idField
			,tableFrom	: params.tableFrom
			,idAffiliate: params.idAffiliate
		};
		customerStore.load({})
		return _createCombo( {
			id				: ( typeof params.id != 'undefined' )? params.id : 'pCode' + params.module
			,fieldLabel		: ( typeof params.fieldLabel != 'undefined' )? params.fieldLabel : 'Customer Name'
			,style			: ( typeof params.style != 'undefined' )? params.style : ''
			,store			: customerStore
			,valueField		: 'id'
			,displayField	: 'name'
			,labelWidth		: params.labelWidth
			,width			: params.width
			,allowBlank		: ( typeof params.allowBlank != 'undefined' )? params.allowBlank : false
			,listeners		: params.listeners
		} )
	}

	/* Grid Journal Entry with Automatic Journal Entry
		return : _gridPanel
		parameters[
			
			PROPERTIES __________________
			id				: [string] serves as an ID and name for the component.
			idAffiliate		: [int]	used as a unique identifier for [TBC]....
			
		]
	*/

	function _gridJournalEntry( params ){
		var id = ( typeof params.id != 'undefined' ) ? params.id : 'gridJournalEntry' + params.module;
		params['pageSize']	= ( typeof params.pageSize != 'undefined'? params.pageSize : 20 );

		var store = _createRemoteStore( {
			fields	: [
				{	name	: 'idCoa'
					,type	: 'number'
				}
				,{	name	: 'debit'
					,type	: 'float'
				}
				,{	name	: 'credit'
					,type	: 'float'
				}
				,{	name	: 'idCostCenter'
					,type	: 'float'
				}
				,'name'
				,'code'
				,'explanation'
				,'costcenterName'
			]
			,url	: Ext.getConstant( 'STANDARD_ROUTE' ) + 'gridJournalEntry'
		} )
		,defaultStore = _createRemoteStore( {
			fields	: [
				{	name	: 'defaultentryID'
					,type	: 'number'
				}
				,'purpose'
			]
			,url	: Ext.getConstant( 'STANDARD_ROUTE' ) + 'getDefaultEntries'
		} );

		defaultStore.proxy.extraParams.idModule = params.idModule;

		function getDefaultAccounts( d ){
			var mask = new Ext.LoadMask( Ext.getCmp(id), { msg : "Retrieving default accounts entry. Please wait..." } );
			mask.show();
			var params1 = {};
			if(typeof d.idDefaultEntry != 'undefined'){
				params1 = {
					idDefaultEntry	: d.idDefaultEntry
					,idModule		: params.idModule
				}
				Ext.getCmp(id).store.removeAll();
			}
			
			Ext.Ajax.request( {
				url			: Ext.getConstant( 'STANDARD_ROUTE' ) + 'getDefaultAccounts'
				,noMask		: true
				,params		:params1
				,success	:function(response){
					var ret = Ext.decode( response.responseText );
					
					if(d.isReset == false){
						Ext.getCmp( id ).store.removeAll();
					}
					ret.view.forEach( function( data ){
						Ext.getCmp( id ).store.add( {
							idCoa	: data.idCoa
							,code	: data.code
							,name	: data.name
							,credit	: data.credit
							,debit	: data.debit
						} );
					} );
					
					if(typeof params.setAccountsDefaults != 'undefined'){
						params.setAccountsDefaults();
					}
					
					mask.destroy();
				}
			} );
		}

		function getAutomatedEntries( args ){
			Ext.Ajax.request( {
				url			: Ext.getConstant( 'STANDARD_ROUTE2' ) + 'getAutomatedEntries'
				,params		: args
				,success	: function( response ){
					var resp = Ext.decode( response.responseText );

					resp.view.map( item => {
						if( item.idCoa != null ){
							if( params.config.idmodule != 43 ){
								if( item.debit == 1 ) item.debit = __getValue( __getEntryValue( item.account ) );
								if( item.credit == 1 ) item.credit = __getValue( __getEntryValue( item.account ) );
							}

							Ext.getCmp( id ).store.add( {
								idCoa	: item.idCoa
								,code	: item.code
								,name	: item.name
								,credit	: item.credit
								,debit	: item.debit
							} );
						}
					});
				}
			} );
		}

		function __getValue( id ){
			return Ext.getCmp( id + params.config.module ).value;
		}

		function __getEntryValue( account ){
			let field = '', idModule = parseInt( params.config.idmodule, 10);

			switch( account ){
				/* Accounting Defaults */
				case 'accRec':
					let _ACCREC = [ 18, 29, 28, 58 ];
					
					if( _ACCREC.includes( idModule )) {
						switch( idModule  ){
							case 18:
								field = 'balance';
								break;
							case 29:
								field = 'totalAmount';
								break;
							case 28:
								field = 'totalAmountCollected';
								break;
							case 58:
								field = 'amount';
								break;
						}
					}
				break;
				case 'accPay':
					let _ACCPAY = [ 25, 21, 45, 57 ];

					if( _ACCPAY.includes( idModule )) {
						switch( idModule  ){
							case 25:
								field = 'balance';
								break;
							case 21:
								field = 'totalamt';
								break;
							case 45:
								field = 'totalAmountDisbursed';
								break;
							case 57:
								field = 'amount';
								break;
						}
					}
				break;
				case 'debitMemo':
					if(idModule == 48 ) field = 'amount';
				break;
				case 'creditMemo':
					if(idModule == 48 ) field = 'amount';
				break;
				case 'inputTax':
					if( idModule == 25 ) field = 'vatAmount';
				break;
				case 'outputTax':
					if( idModule == 25 ) field = 'vatAmount';
				break;
				case 'salesAccount':
					if( idModule == 25 ) field = 'totalAmountDue';
				break;
				case 'salesDiscount':
					if( idModule == 25 ) field = 'discountAmount';
				break;
				/* Customer */
				case 'discountGLAcc':
					if( idModule == 18 || idModule == 28 ) field = 'discountAmount';
				break;
				case 'salesGLAcc':
					field = ( idModule == 18  ) ? 'totalAmountDue' : 'totalAmountCollected';
				break;
				/* Supplier */
				case 'expenseGlAcc':
					field = ( idModule == 25 ) ? 'totalAmountDue' : 'totalAmountDisbursed';
				break;
				case 'discountGlAcc':
					if( idModule == 25 || idModule == 45 ) field = 'discountAmount';
				break;
				/*Item */
				case 'salesGlAcc':
					if( idModule == 18 ) field = 'sales';
				break;
				case 'inventoryGlAcc':
					let _INVGLACC = [ 25, 18, 29, 22, 43];

					if( _INVGLACC.includes( idModule )) {
						switch( idModule  ){
							case 25:
								field = 'purchases';
								break;
							case 18:
								field = 'sales';
								break;
							case 29:
								field = 'totalAmount';
								break;
							case 22:
								field = 'totalamt';
								break;
							case 43:
								field = '';
								break;
						}
					}
				break;
			}

			return field;
		}

		var costCenterStore = standards.callFunction( '_createRemoteStore', {
            fields  : [ 
                {	name	: 'id'
                    ,type	: 'number'
            }, 'name' ]
            ,url    : Ext.getConstant( 'STANDARD_ROUTE2' ) + 'getCostCenter'
		} )

		var listeners	= {};
		if( typeof params.listeners != 'undefined' ){
			listeners = params.listeners;
		}

		listeners['edit']	= function( me, value, grid ){
			if(value.record.data.code == null || value.record.data.code.length < 1 || value.record.data.idCoa == null || value.record.data.idCoa.length < 1){
				var  {0 : store} = Ext.getCmp(id).selModel.getSelection()
				store.set(value.field, null)
			}
		}
		
		return _gridPanel( {
			id				: id
			,module			: params.module
			,style			: ( typeof params.style != 'undefined' )? params.style : ''
			,store			: store
			,cls			: 'gridJournalEntryClass' + params.module
			,noDefaultRow	: true
			,tbar			: {
				content		: 'add'
				,extraTbar2	: [
					'-'
					,_createCombo( {
						id				: 'defaultAccount' + id
						,store			: defaultStore
						,fieldLabel		: ''
						,emptyText		: 'Select default entries...'
						,valueField		: 'defaultentryID'
						,displayField	: 'purpose'
						,style			: 'margin-left: 3px;'
						,width			: 250
						,listeners		: {
							select	: function( isReset, record  ){
								getDefaultAccounts( {
									idDefaultEntry : record[0].data.defaultentryID
									, isReset : true
								} );
							}
						}
					} )
					,{	xtype		: 'button'
						,text		: 'Automate Journal Entries'
						,id			: 'autoJE' + id
						,handler	: function(){
							var args = {};

							if( typeof params.config == 'undefined' ) return false;
							if(typeof params.config.idmodule != 'undefined') args['idModule'] = params.config.idmodule;
							if(typeof params.supplier != 'undefined') args['idSupplier'] = __getValue( params.supplier );
							if(typeof params.customer != 'undefined') args['idCustomer'] = __getValue( params.customer );
							if(typeof params.items != 'undefined') args['items'] = params.items;
							if(typeof params.itemsToConvert != 'undefined') args['itemsToConvert'] = params.itemsToConvert;
							if(typeof params.outputItems != 'undefined') args['outputItems'] = params.outputItems;

							if( typeof args['items'] != 'undefined' ){
								let items = args['items'].store.data.items.map( item => {
									if( typeof item.data.idItem != 'undefined' ){
										return ( typeof item.data.shortQty != 'undefined' 
												|| typeof item.data.overQty != 'undefined'
												|| typeof item.data.qtyTransferred != 'undefined'
												|| typeof item.data.qtyReceived != 'undefined' ) ? item.data : item.data.idItem ;
									}
									else if(typeof item.data.id != 'undefined'){
										return ( typeof item.data.shortQty != 'undefined' 
												|| typeof item.data.overQty != 'undefined'
												|| typeof item.data.qtyTransferred != 'undefined'
												|| typeof item.data.qtyReceived != 'undefined' ) ? item.data : item.data.id;
									}
								});

								args['items'] = Ext.encode( items );
							}

							if( typeof args['itemsToConvert'] != 'undefined' ){
								let itemsToConvert = args['itemsToConvert'].store.data.items.map( item => {
									if( typeof item.data.idItem != 'undefined' ){
										return item.data.idItem;
									}
									else if(typeof item.data.id != 'undefined'){
										return item.data.id;
									}
								});

								args['itemsToConvert'] = Ext.encode( itemsToConvert );
							}

							if( typeof args['outputItems'] != 'undefined' ){
								let outputItems = args['outputItems'].store.data.items.map( item => {
									if( typeof item.data.idItem != 'undefined' ){
										return item.data.idItem;
									}
									else if(typeof item.data.id != 'undefined'){
										return item.data.id;
									}
								});

								args['outputItems'] = Ext.encode( outputItems );
							}

							getAutomatedEntries( args );
						}
					}
				]
				,extraTbar4 : [
					(	typeof params.hasPrintOption != 'undefined' && params.hasPrintOption == 1 ? 
						_createCombo({
							id : 'printStatusJE' + id
							,fieldLabel: ''
							,labelWidth: 0
							,module	: params.module
							,store  : _createLocalStore({
								data: [
									'Print with NO Journal Entry'
									,'Print with Journal Entry'
									,'Journal Entry Only'
								]
							})
							,editable : false
							,submitValue : false
							,width : 190
							,value : 2
							,cls : 'notIncludeAutoReadOnly'
						}) : null
					)
					,{	xtype : 'button'
						,iconCls : 'glyphicon glyphicon-refresh'
						,handler : function(){
							Ext.getCmp('defaultAccount' + id).reset();
							Ext.getCmp(id).store.removeAll();
						}
					}
				]
			}
			,plugins		: _cellEdit( {
				extraAfterEdit : function( me, parent, object ){
					
					if( parent.field == 'debit' && parent.record.get( 'debit' ) > 0 ){
						parent.record.set( 'credit', 0 );
					}
					else if( parent.field == 'credit' && parent.record.get( 'credit' ) > 0 ){
						parent.record.set( 'debit', 0 );
					}
				}
			} )
			,bbarTotalLabel	: ''
			,columns		: [
				{	header		: 'Code'
					,dataIndex	: 'code'
					,width		: 130
					,editor		: _createComboCoa( {
						module			: params.module
						,autoLoad		: true
						,fieldLabel		: ''
						,isGrid			: true
						,id				: 'idCoaJEGridID' + params.module
						,displayField	: 'code'
						,pageSize		: ( typeof params.pageSize != 'undefined' ) ? params.pageSize : ''
						,forceSelection	: false
						,listeners		: {
							beforeQuery: function(){
								this.store.proxy.extraParams.idAffiliate = Ext.getConstant('AFFILIATEID');
								this.store.load({});
							}
							,extraSelect: function( me, result, record, noDuplicate ){
								result = result[0];
								
								_getCOADetails( {
									idCoa		: result.get( 'idCoa' )
									,success	: function( ret ){
										record.set( 'idCoa', ret.view.idCoa );
										record.set( 'code', ret.view.acod_c15 );
										record.set( 'name', ret.view.aname_c30 );
									}
								} );
							}
							,focus	: function( me ){
								me.store.proxy.extraParams.idAffiliate =  Ext.getConstant('AFFILIATEID');
								me.store.load( {} );
							}
							,blur	: function( me ){
								var record		= me.findRecord( me.valueField, me.getValue() )
									,index		= me.store.indexOf( record );
								if( index < 0 ){
									me.reset();
									var  {0 : store} = Ext.getCmp(id).selModel.getSelection()
									store.set( 'idCoa', null );
									store.set( 'code', null );
									store.set( 'name', null );
									store.set( 'explanation', null );
									store.set( 'costcenterName', null );
									store.set( 'debit', 0.00 );
									store.set( 'credit', 0.00 );
								}
							}
							,select : function (me, result, record){
								var {0 : data} = result
								var {0 : store} = Ext.getCmp(id).selModel.getSelection()
								let items = Ext.getCmp(id).getStore()
								if(Ext.isUnique('code', items, this)) Ext.setGridData([{name: 'idCoa', format: 'int'}, 'code', 'name'], store, data)
							}
						}
						,createPicker   : function(){
							return createLoadMorePlugin( {
								id			: 'idCoaJEGridID' + params.module
							} );
						}
					})
				}
				,{	header		: 'Name'
					,dataIndex	: 'name' 
					,flex		: 1
					,minWidth	: 150
					,editor		: _createComboCoa( {
						module			: params.module
						,autoLoad		: true
						,fieldLabel		: ''
						,pageSize		: 10
						,id				: 'idCoaJEGridName' + params.module
						,isGrid			: true
						,displayField	: 'name' 
						,forceSelection	: false
						,pageSize		: ( typeof params.pageSize != 'undefined' ) ? params.pageSize : ''
						,listeners		: {
							beforeQuery: function(){
								this.store.proxy.extraParams.idAffiliate =  Ext.getConstant('AFFILIATEID');
								this.store.load({});
							}
							,extraSelect	: function( me, result, record, noDuplicate ){
								result = result[0];
								
								_getCOADetails( {
									idCoa		: result.get('idCoa')
									,success	: function( ret ){
										record.set( 'idCoa', ret.view.idCoa );
										record.set( 'code', ret.view.acod_c15 );
										record.set( 'name', ret.view.aname_c30 );
									}
								} );
							}
							,focus			: function( me ){
								me.store.proxy.extraParams.idAffiliate =  Ext.getConstant('AFFILIATEID');
								me.store.load( {} );
							}
							,blur			: function( me ){
								var record		= me.findRecord( me.valueField, me.getValue() )
									,index		= me.store.indexOf( record );
								if( index < 0 ){
									me.reset();
									var  {0 : store} = Ext.getCmp(id).selModel.getSelection()
									store.set( 'idCoa', null );
									store.set( 'code', null );
									store.set( 'name', null );
									store.set( 'explanation', null );
									store.set( 'costcenterName', null );
									store.set( 'debit', 0.00 );
									store.set( 'credit', 0.00 );
								}
							}
							,select : function (me, result, record){
								let {0 : data} = result
								let {0 : store} = Ext.getCmp(id).selModel.getSelection()
								let items = Ext.getCmp(id).getStore()
								if(Ext.isUnique('name', items, this)) Ext.setGridData([{name: 'idCoa', format: 'int'}, 'code', 'name'],store, data)
							}
						}
						,createPicker   : function(){
							return createLoadMorePlugin( {
								id			: 'idCoaJEGridName' + params.module
							} );
						}
					})
				}
				,{	header		: 'Explanation'
					,dataIndex	: 'explanation'
					,minWidth	: 150
					,flex		: 1
					,editor		: 'text'
				}
				,{	header		: 'Cost Center'
					,dataIndex	: 'costcenterName'
					,width		: 170
					,editor		: _createCombo( {
						type						: 'costcenter'
						,module						: params.module
						,fieldLabel					: ''
						,store						: costCenterStore
						,valueField					: 'name'
						,autoLoad					: true
						,isDefaultFilterByAffiliate	: true
						,id							: null
						,listeners					: {
							beforeQuery	: function(){
								var idAffiliate =  Ext.getConstant('AFFILIATEID');
                    
								costCenterStore.proxy.extraParams.idAffiliate = parseInt( idAffiliate, 10 );
								costCenterStore.load({
									callback: function() {
										if( costCenterStore.getCount() < 1 ) {
											standards.callFunction('_createMessageBox',{ msg: 'No cost center was assigned for this Affiliate.' })
										}
									}
								})
							}
							,extraSelect	: function( me, result, record, noDuplicate ){
								result = result[0];
								record.set( 'idCostCenter', result.get( 'id' ) );
							}
							,select : function (me, result, record){
								let {0 : data} = result
								let {0 : store} = Ext.getCmp(id).selModel.getSelection()
								store.set( 'idCostCenter', data.get( 'id' ) );
							}
						}
					} )
				}
				,{	header		: 'Debit'
					,dataIndex	: 'debit'
					,width		: 120
					,xtype		: 'numbercolumn'
					,editor		: 'float'
					,hasTotal	: true
				}
				,{	header		: 'Credit'
					,dataIndex	: 'credit'
					,width		: 120
					,xtype		: 'numbercolumn'
					,editor		: 'float'
					,hasTotal	: true
				}
			]
			,listeners: listeners
		} )
	}

	function _gridJournalEntryValidation( params ){
		var grid = Ext.dom.Query.select('.gridJournalEntryClass'+params.module)
			,requireJE = ( typeof params.requireJE != 'undefined'? params.requireJE : false );
		if(grid.length > 0){
			var gridID = grid[0].id;
			grid = Ext.getCmp(gridID);
			var debit = 0;
			var credit = 0;
			
			var gridAccounts = [];
			grid.store.getRange().forEach(function(data){
				if(data.get('idCoa') && (data.get('debit') > 0 || data.get('credit') > 0)){
					gridAccounts.push({
						idCoa : data.get('idCoa')
						,debit : data.get('debit')
						,credit : data.get('credit')
						,idCostCenter : data.get('idCostCenter')
						,explanation : data.get('explanation')
					});
					debit = debit + data.get('debit');
					credit = credit + data.get('credit');
				}
			});
			
			if( requireJE && gridAccounts.length <= 0 ){
				_createMessageBox( {
					msg : 'Journal entries are required.'
				} );
				
				return false;
			}
			
			/**
			removed validation: refer to story PBI #319
			if(gridAccounts.length == 1){
				_createMessageBox({
					msg : 'Please add atleast 2 or more default journal entry accounts.'
				});
				
				return false;
			}
			
			if(gridAccounts.length != grid.store.count()){
				_createMessageBox({
					msg : 'Amount of the journal entry is required.'
				});
				
				return false;
			}
			**/

			
			credit = credit.toFixed( 3 )
			debit = debit.toFixed( 3 )
			debit = Math.ceil(debit * 100)/100
			credit = Math.ceil(credit * 100)/100

			if(parseFloat( debit.toFixed( 2 ) ) != parseFloat( credit.toFixed( 2 ) )){
				_createMessageBox({
					msg : 'Invalid Journal Entry. Total Debit must be equal with the Total Credit.'
				});
				
				return false;
			}
			
			if(typeof params.totalAmountDue != 'undefined'){
				if(gridAccounts.length > 0 && parseFloat( debit ).toFixed( 2 ) != parseFloat( params.totalAmountDue ).toFixed( 2 )){
					return gridAccounts;
				}
			}
			
			return gridAccounts;
		}
		return !requireJE;
	}

	function _createComboCoa( params ){
		var coaStore = _createRemoteStore( {
			fields		: [ 'idCoa', 'name', 'code' ]
			,url		: Ext.getConstant( 'STANDARD_ROUTE' ) + 'getCoa'
			,autoLoad	: true
			,pageSize	: params.pageSize
		} )

		coaStore.proxy.extraParams.displayField = params.displayField

		return _createCombo( {
			id				: ( typeof params.id != 'undefined' )? params.id : 'idCoa' + params.module
			,store			: coaStore
			,displayField	: ( typeof params.displayField != 'undefined' )? params.displayField : 'name'
			,valueField		: ( ( params.isGrid )? ( ( typeof params.displayField != 'undefined' )? params.displayField : 'name' ) : 'id' )
			,listeners		: params.listeners
			,forceSelection	: params.forceSelection
			,pageSize		: params.pageSize
			,autoLoad		: params.autoLoad
			,module			: params.module
			,createPicker	: params.createPicker
		} )
	}

	function _getCOADetails( params ){
		Ext.Ajax.request( {
			url			: Ext.getConstant( 'STANDARD_ROUTE' ) + 'getCOADetails'
			,params		: {
				idCoa	: ( typeof params.idCoa != 'undefined' )? params.idCoa : 0
			}
			,success	: function( response ){
				var ret = Ext.decode( response.responseText );
				if( typeof params.success != 'undefined' ) params.success( ret )
			}
		} );
	}

	function createLoadMorePlugin(params){
		var id = params.id;
				var toolbarID = id+'-boundlist-paging-toolbar';
				var me = Ext.getCmp(id),
					picker,
					pickerCfg = Ext.apply({
						xtype: 'boundlist',
						id:id + '-boundlist',
						cls:'clsBoundListIdentifier',
						pickerField: me,
						selModel: {
							mode: me.multiSelect ? 'SIMPLE' : 'SINGLE'
						},
						floating: true,
						hidden: true,
						store: me.store,
						displayField: me.displayField,
						focusOnToFront: false,
						preserveScrollOnRefresh:true,
						pageSize: me.pageSize,
						tpl: me.tpl,
						listeners:{
							show:function(){
								// console.log( "show" );
								/*this function will focus on the selected record in the combobox*/
								var node = Ext.getCmp(id).picker.getSelectedNodes()[0];
								if(Ext.get(node)){
									var regionValue = Ext.get(node).getRegion().top - Ext.get(this.id).getRegion().top;
									if(regionValue < 0)  regionValue = 0;
									Ext.getCmp(id).picker.scrollBy(0,regionValue,true); 
								}
								this.fireEvent( 'refresh' );
								delete me.store.proxy.extraParams.query;
							},
							refresh:function(){
								// console.log( "refresh" );
								var cnt = Ext.getCmp(id).getStore().getCount();
								var rec = Ext.getCmp(id).getStore().getRange()[cnt-me.pageSize];
								var node = Ext.getCmp(id).picker.getNode(rec);
								
								setTimeout(function(){ 
									if(Ext.getCmp(id)) Ext.getCmp(id).picker.setHighlightedItem(node);
									
									var comp = Ext.dom.Query.select('#'+toolbarID+' .x-toolbar-item');
									if(typeof comp[7] != 'undefined' && Ext.getCmp(comp[7].id)){
										Ext.getCmp(id).picker.minWidth = ( Ext.getCmp(id).picker.getWidth() - Ext.getCmp(id).getLabelWidth() );
										Ext.get(toolbarID+'-innerCt').setWidth( Ext.getCmp(id).picker.getWidth() );
										var left = (Ext.getCmp(id).picker.getWidth()/2)-(Ext.getCmp(comp[7].id).getWidth()/2) - 16;
										Ext.getCmp(comp[7].id).el.dom.style.marginLeft = left+'px';
									}
								}, 50);
								
								if(Ext.get(node)){
									var regionValue = Ext.get(node).getRegion().top - Ext.get(this.id).getRegion().top;
									Ext.getCmp(id).picker.scrollBy(0,regionValue,true);
								}
							},
							highlightitem:function(view,node){
							
								// console.log( "higlight" );
								
								if(typeof params.highlightitem != 'undefined'){
									var currentSelected = node.innerHTML;
									Ext.getCmp(id).setRawValue(currentSelected);
								}
							}
						}
					}, me.listConfig, me.defaultListConfig);
				
				picker = me.picker = Ext.widget(pickerCfg);
				
				if (me.pageSize) {
					/*CHANGE THE PAGING TOOLBAR OF COMBOBOX*/
					picker.pagingToolbar = Ext.widget('pagingtoolbar', {
											id: toolbarID,
											cls:'clsboundlist',
											pageSize: me.pageSize,
											store: me.store,
											border: false,
											ownerCt: me,
											ownerLayout: me.getComponentLayout(),
											nextText:'Load more (Ctrl + <span style="font-size:10px;" class="glyphicon glyphicon-arrow-down"></span>)',
											listeners:{
												afterrender:function(){
													try{
														Ext.getCmp(id).picker.minWidth = ( Ext.getCmp(id).getWidth() - Ext.getCmp(id).getLabelWidth );
														// Ext.getCmp(id).picker.setWidth(Ext.getCmp(id).getWidth());
														var comp = Ext.dom.Query.select('#'+this.id+' .x-toolbar-item');
														Ext.getCmp(comp[0].id).hide();
														Ext.getCmp(comp[1].id).hide();
														Ext.getCmp(comp[2].id).hide();
														Ext.getCmp(comp[3].id).hide();
														Ext.getCmp(comp[4].id).hide();
														Ext.getCmp(comp[5].id).hide();
														Ext.getCmp(comp[6].id).hide();
														Ext.getCmp(comp[7].id).setText(this.nextText);
														Ext.getCmp(comp[8].id).hide();
														Ext.getCmp(comp[9].id).hide();
														Ext.getCmp(comp[10].id).hide();
													}catch(err){
														console.error(err);
													}
												}
											}
										});
										
					picker.pagingToolbar.on('beforechange', me.onPageChange, me);
				}

				me.mon(picker, {
					itemclick: me.onItemClick,
					refresh: me.onListRefresh,
					scope: me
				});

				me.mon(picker.getSelectionModel(), {
					beforeselect: me.onBeforeSelect,
					beforedeselect: me.onBeforeDeselect,
					selectionchange: me.onListSelectionChange,
					scope: me
				});
				
				/*
					keypress listeners for loadmore
				*/
				me.addListener({
					keydown:function(view,e){
						if(e.ctrlKey && e.getKey() == e.DOWN){
							if(typeof me.store.lastOptions.params != 'undefined'){
								me.store.proxy.extraParams.query = me.store.lastOptions.params.query;	
							}
							var comp = Ext.dom.Query.select('#'+toolbarID+' .x-toolbar-item');
							if(!Ext.getCmp(comp[7].id).isDisabled()) me.store.nextPage();
						}
					}
				});
				
				return picker;
		
	}

	return {
		callFunction	: function( functionName, parameters ){
			try{
				var func = eval( functionName );
				if( typeof parameters == 'undefined' ){
					parameters = {};
				}
				return func( parameters );
			}
			catch( err ){
				console.info( err,functionName );
			}
		}
	}
}();