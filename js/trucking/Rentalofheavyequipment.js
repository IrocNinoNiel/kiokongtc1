/**
 * Developer: Hazel Alegbeleye
 * Module: Rental of Heavy Equipment
 * Date: Nov 23, 2021
 * Finished:
 * Description: This module allows the authorized user to set (add, edit and delete) a Rental of Heavy Equipment
 * DB Tables:
 * */

 function Rentalofheavyequipment() {
    return function(){
        var baseurl, route, module, canDelete, pageTitle, idModule, isGae, isSaved = 0, deletedItems = [], selectedItem = [], idAffiliate, selRec, componentCalling
        ,canSave, idAffiliate, canCancel, dataHolder = {} , onEdit = 0;

        function _mainPanel( config ){

            let plateNumberStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name' ]
                ,url        : route + 'getPlateNumber'
                ,startAt    :  0
                ,autoLoad   : true
            })
            ,employeeStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name']
                ,url        : route + 'getDrivers'
                ,startAt    :  0
                ,autoLoad   : true
            })
            ,truckTypeStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name']
                ,url        : route + 'getTruckTypes'
                ,startAt    :  0
                ,autoLoad   : true
            })
            ,supplierStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[
                    { name : 'id', type : 'number' }
                    ,'name'
                    ,'address'
                    ,'contactNumber'
                ]
                ,url        : route + 'getSuppliers'
                ,startAt    :  0
                ,autoLoad   : true
            })
            ,customerStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[
                    { name : 'id', type : 'number' }
                    ,'name'
                    ,'address'
                    ,'contactNumber'
                ]
                ,url        : route + 'getCustomers'
                ,startAt    :  0
                ,autoLoad   : true
            })
            ,rateTypeStore = standards.callFunction( '_createLocalStore' , {
                data    : [
                    'Per Hour'
                    ,'Per Trip'
                    ,'Per Kilometer'
                ]
                ,startAt : 1
            } )
            ,statusStore = standards.callFunction( '_createLocalStore' , {
                data    : [
                    'Rented'
                    ,'Returned'
                ]
                ,startAt : 1
            } )
            ,projectStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name']
                ,url        : route + 'getProjects'
                ,startAt    :  0
            });

            return standards2.callFunction(	'_mainPanelTransactions' ,{
                config		            : config
                ,module		            : module
                ,moduleType             : 'form'
                ,hasApproved            : false
                ,tbar : {
                    saveFunc                : _saveForm
                    ,resetFunc              : _resetForm
                    ,customListExcelHandler	: _printExcel
					,customListPDFHandler	: _customListPDF
					,formPDFHandler         : _printPDF
					,hasFormPDF     		: true
					,hasFormExcel			: false
                    ,filter: {
                        searchURL       : route + 'viewHistorySearch'
                        ,emptyText      : 'Search reference here...'
                        ,module         : module
                    }
                }
                ,formItems: [
                    standards2.callFunction( '_transactionHeader', {
						module					: module
						,containerWidth			: 1000
						,idModule				: idModule
						,idAffiliate			: idAffiliate
						,config					: config
					} )
                    ,{  xtype       : 'fieldset'
                        ,layout     : 'column'
                        ,padding    : 10
                        ,items		: [
                            {
                                xtype			: 'container'
                                ,columnWidth	: .5
                                ,items			: [
                                    standards.callFunction( '_createCheckField', {
                                        id              : 'striker' + module
                                        ,fieldLabel     : 'Striker'
                                        ,listeners      : {
                                            change  : function( me ) {
                                                if( me.value ){
                                                    Ext.getCmp('idSupplier' + module ).setVisible( true );
                                                    Ext.getCmp('fuelUsage' + module ).setVisible( true );

                                                    Ext.getCmp('idCustomer' + module ).setVisible( false );

                                                    _requireField( 'idCustomer', true );
                                                    _requireField( 'idTruckType', true );

                                                    _requireField( 'idSupplier', false );
                                                    _requireField( 'fuelUsage', false );

                                                } else {
                                                    Ext.getCmp('idSupplier' + module ).setVisible( false );
                                                    Ext.getCmp('fuelUsage' + module ).setVisible( false );

                                                    Ext.getCmp('idCustomer' + module ).setVisible( true );

                                                    _requireField( 'idCustomer', false );
                                                    _requireField( 'idTruckType', false );

                                                    _requireField( 'idSupplier', true );
                                                    _requireField( 'fuelUsage', true );
                                                }

                                                Ext.getCmp('address' + module).setValue(" ");
                                                Ext.getCmp('contactNumber' + module).setValue(" ");
                                            }
                                        }
                                    })
                                    ,standards.callFunction( '_createCombo', {
                                        id              : 'idCustomer' + module
                                        ,fieldLabel     : "Customer Name"
                                        ,store          : customerStore
                                        ,displayField   : 'name'
                                        ,valueField     : 'id'
                                        ,listeners      : {
                                            select     : function( me , record ){
                                                if(me.value == 0){
                                                    Ext.getCmp('address' + module).setReadOnly( false );
                                                    Ext.getCmp('contactNumber' + module).setReadOnly( false );
                                                } else {
                                                    Ext.getCmp('address' + module).setReadOnly( true );
                                                    Ext.getCmp('contactNumber' + module).setReadOnly( true );
                                                    Ext.getCmp('address' + module).setValue( record[0].data.address );
                                                    Ext.getCmp('contactNumber' + module).setValue( record[0].data.contactNumber );
                                                }
                                            }
                                        }
                                    } )
                                    ,standards.callFunction( '_createCombo', {
                                        id              : 'idSupplier' + module
                                        ,fieldLabel     : "Supplier Name"
                                        ,store          : supplierStore
                                        ,displayField   : 'name'
                                        ,valueField     : 'id'
                                        ,hidden         : true
                                        ,listeners      : {
                                            select    : function( me , record ){
                                                Ext.getCmp('address' + module).setValue( record[0].data.address );
                                                Ext.getCmp('contactNumber' + module).setValue( record[0].data.contactNumber );
                                            }
                                        }
                                    } )
                                    ,standards.callFunction( '_createTextField', {
                                        fieldLabel  : 'Contact Number'
                                        ,id         : 'contactNumber' + module
                                        ,isDecimal  : true
                                        ,maskRe     : /[0-9.]/
                                        ,maxLength  : 20
                                        ,readOnly   : true
                                    } )
                                    ,standards.callFunction( '_createTextArea', {
                                        id			    : 'address' + module
                                        ,fieldLabel	    : 'Address'
                                        ,allowBlank	    : true
                                        ,readOnly       : true
                                    } )
                                ]
                            }
                            ,{
                                xtype			: 'container'
                                ,columnWidth	: .5
                                ,items			: [
                                    standards.callFunction( '_createCheckField', {
                                        id              : 'isConstruction' + module
                                        ,fieldLabel     : 'For Construction'
                                        ,listeners      : {
                                            change  : function( me ) {
                                            }
                                        }
                                    })
                                    ,standards.callFunction( '_createCombo', {
                                        id              : 'idProject' + module
                                        ,fieldLabel     : 'Project Name'
                                        ,store          : projectStore
                                        ,displayField   : 'name'
                                        ,valueField     : 'id'
                                        ,allowBlank     : false
                                        ,listeners      : {
                                            beforeQuery :  function() {
                                                projectStore.proxy.extraParams.isConstruction = Ext.getCmp( 'isConstruction' + module ).getValue();
											}
                                        }
                                    } )
                                    ,standards.callFunction( '_createTextArea', {
                                        id			    : 'remarks' + module
                                        ,fieldLabel	    : 'Remarks'
                                        ,allowBlank     : false
                                    } )
                                ]
                            }
                        ]
                    }
                    ,{  xtype       : 'fieldset'
                        ,layout     : 'column'
                        ,title      : 'Rental Details'
                        ,padding    : 10
                        ,items		: [
                            {
                                xtype			: 'container'
                                ,columnWidth	: .5
                                ,items			: [
                                    standards.callFunction( '_createCombo', {
                                        id              : 'idDriver' + module
                                        ,fieldLabel     : "Driver's Name"
                                        ,store          : employeeStore
                                        ,displayField   : 'name'
                                        ,valueField     : 'id'
                                        ,allowBlank     : false
                                        ,listeners      : {
                                            select      : function( me , record ){
                                            }
                                        }
                                    } )
                                    ,standards.callFunction( '_createDateRange', {
                                        fromFieldLabel  : "Rental Date"
                                        ,module         : module
                                        ,width          : 111
                                        ,fromWidth      : 235
                                    } )
                                    ,standards.callFunction( '_createCombo', {
                                        id              : 'idTruckType' + module
                                        ,fieldLabel     : "Truck Type"
                                        ,store          : truckTypeStore
                                        ,displayField   : 'name'
                                        ,valueField     : 'id'
                                        ,listeners      : {
                                            select     : function( me , record ){
                                            }
                                            ,afterrender : function( me ){
                                                _requireField( 'idTruckType', false );
                                            }
                                        }
                                    } )
                                    ,standards.callFunction( '_createCombo', {
                                        id              : 'plateNumber' + module
                                        ,fieldLabel     : 'Plate Number'
                                        ,store          : plateNumberStore
                                        ,displayField   : 'name'
                                        ,valueField     : 'id'
                                        ,allowBlank     : true
                                        ,listeners      : {
                                            select     : function( me , record ){
                                            }
                                            ,afterrender : function( me ){
                                                // _requireField( 'plateNumber', false );
                                            }
                                        }
                                    } )
                                    ,standards.callFunction( '_createTextField', {
                                        fieldLabel  : 'Model'
                                        ,id         : 'model' + module
                                    } )
                                    ,standards.callFunction( '_createCombo', {
                                        id              : 'status' + module
                                        ,fieldLabel     : "Status"
                                        ,store          : statusStore
                                        ,displayField   : 'name'
                                        ,valueField     : 'id'
                                        ,allowBlank     : false
                                        ,listeners      : {
                                            select     : function( me , record ){
                                                if( me.value == 2 ){
                                                    Ext.getCmp('returnDetailsContainer' + module ).setVisible( true );

                                                    _requireField( 'returnDate', false );
                                                    _requireField( 'returnMileage', false );
                                                    _requireField( 'returnFuelLevel', false );
                                                    _requireField( 'penalty', false );

                                                } else {
                                                    Ext.getCmp('returnDetailsContainer' + module ).setVisible( false );

                                                    _requireField( 'returnDate', true );
                                                    _requireField( 'returnMileage', true );
                                                    _requireField( 'returnFuelLevel', true );
                                                    _requireField( 'penalty', true );
                                                }
                                            }
                                        }
                                    } )
                                ]
                            }
                            ,{
                                xtype			: 'container'
                                ,columnWidth	: .5
                                ,items			: [
                                    standards.callFunction( '_createCombo', {
                                        id              : 'idRateType' + module
                                        ,fieldLabel     : "Rate Type"
                                        ,store          : rateTypeStore
                                        ,displayField   : 'name'
                                        ,valueField     : 'id'
                                        ,allowBlank     : false
                                        ,defaultValue   : 1
                                        ,listeners      : {
                                            select     : function( me , record ){
                                                var rateType = Ext.getCmp( 'rateType' + module ), label = "";

                                                switch( me.value ){
                                                    case 1:
                                                        label = "Hours";
                                                        break;
                                                    case 2:
                                                        label = "Trip";
                                                        break;
                                                    case 3:
                                                        label = "Kilometer";
                                                        break;
                                                }

                                                if( typeof rateType != 'undefined' ){
                                                    rateType.reset();
                                                    if( typeof rateType.labelEl != 'undefined' ) rateType.labelEl.update( label + Ext.getConstant('REQ') + ':');
                                                }
                                            }
                                        }
                                    } )
                                    ,standards.callFunction('_createNumberField',{
                                        id			    : 'rate' + module
                                        ,module		    : module
                                        ,fieldLabel	    : 'Rate'
                                        ,allowBlank     : false
                                        ,listeners      : {
                                            blur      : function( me ){
                                                let rateType = Ext.getCmp( 'rateType' + module ), totalRate = Ext.getCmp( 'totalRate' + module );
                                                totalRate.setValue( me.value * rateType.value );
                                            }
                                        }
                                    })
                                    ,standards.callFunction('_createNumberField',{
                                        id			    : 'rateType' + module
                                        ,module		    : module
                                        ,fieldLabel	    : 'Hours'
                                        ,allowBlank     : false
                                        ,listeners      : {
                                            blur      : function( me ){
                                                let rate = Ext.getCmp( 'rate' + module ), totalRate = Ext.getCmp( 'totalRate' + module );
                                                totalRate.setValue( me.value * rate.value );
                                            }
                                        }
                                    })
                                    ,standards.callFunction('_createNumberField',{
                                        id			    : 'totalRate' + module
                                        ,module		    : module
                                        ,fieldLabel	    : 'Total Rate'
                                        ,allowBlank     : false
                                        ,readOnly       : true
                                    })
                                    ,standards.callFunction('_createNumberField',{
                                        id			    : 'mileage' + module
                                        ,module		    : module
                                        ,fieldLabel	    : 'Mileage'
                                        ,allowBlank     : false
                                    })
                                    ,standards.callFunction('_createNumberField',{
                                        id			    : 'fuelLevel' + module
                                        ,module		    : module
                                        ,fieldLabel	    : 'Fuel Level'
                                        ,allowBlank     : false
                                    })
                                    ,standards.callFunction('_createNumberField',{
                                        id			    : 'fuelUsage' + module
                                        ,module		    : module
                                        ,fieldLabel	    : 'Fuel Usage'
                                        ,hidden         : true
                                    })
                                ]
                            }
                        ]
                    }
                    ,{  xtype       : 'fieldset'
                        ,layout     : 'column'
                        ,title      : 'Return of Truck/Equipment'
                        ,id         : 'returnDetailsContainer' + module   // id of the fieldset
                        ,padding    : 10
                        ,items      : [
                            {
                                xtype			: 'container'
                                ,columnWidth	: .5
                                ,items          : [
                                    standards.callFunction( '_createDateField', {
                                        id              : 'returnDate' + module
                                        ,fieldLabel     : 'Return Date'
                                        ,maxValue	    : new Date()
                                        ,listeners      : {
                                            change: function() {
                                                // _computeAge( this.value );
                                            }
                                        }
                                    } )
                                    ,standards.callFunction('_createNumberField',{
                                        id			    : 'returnMileage' + module
                                        ,module		    : module
                                        ,fieldLabel	    : 'Mileage'
                                    })
                                    ,standards.callFunction('_createNumberField',{
                                        id			    : 'returnFuelLevel' + module
                                        ,module		    : module
                                        ,fieldLabel	    : 'Fuel Level'
                                    })
                                ]
                            }
                            ,{
                                xtype			: 'container'
                                ,columnWidth	: .5
                                ,items          : [
                                    standards.callFunction( '_createTextArea', {
                                        id			    : 'penalty' + module
                                        ,fieldLabel	    : 'Penalty'
                                    } )
                                ]
                            }
                        ]
                        ,listeners  : {
                            afterrender: function( me ){
                                if( true ){
                                    me.hide();
                                }
                            }
                        }
                    }
                    ,tabPanel(config)
                ]
                ,listItems: _gridHistory()
            } );
        }

        function _requireField( id, value ){
            var cmp = Ext.getCmp( id + module )
			,label = (!value) ? cmp.fieldLabel + Ext.getConstant('REQ') + ':' : cmp.fieldLabel + ':';

			if( typeof cmp != 'undefined' ){
				cmp.reset();
				cmp.allowBlank = value;
				if( typeof cmp.labelEl != 'undefined' ) cmp.labelEl.update(label);
			}
        }

        function tabPanel( config ){
            let otherDeductions = standards.callFunction( '_createRemoteStore', {
                fields  : [
                        'refNum',
                        'itemName',
                        'idItem',
                        'description',
                        { name: 'fident', type: 'number' },
                        { name: 'qty', type: 'number' },
                        { name: 'price', type: 'number' },
                        { name: 'amount', type: 'number' }
                    ]
                ,url    : route + 'getOtherDeductions'
            } )
            ,itemStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'idItem', type : 'number' }, 'itemName', {	name : 'price', type : 'number' }]
                ,url        : route + 'getItems'
                ,startAt    :  0
                ,autoLoad   : true
            })
            ,refStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'fident', type : 'number' }, 'refNum', 'description', {	name : 'amount', type : 'number' }]
                ,url        : route + 'getReferences'
                ,startAt    :  1
                ,autoLoad   : true
            })

            let columns = [
                {   header         : 'Reference Number'
                    ,dataIndex      : 'refNum'
                    ,minWidth       : 150
                    ,editor         : standards.callFunction( '_createCombo', {
                        fieldLabel      : ''
                        ,id             : 'cmbFIdent' + module
                        ,store			: refStore
                        ,emptyText		: 'Select reference...'
                        ,displayField   : 'refNum'
                        ,valueField     : 'refNum'
                        ,listeners      : {
                            beforeQuery : function( me ){
                                // _checkAffiliate( searchItemStore, me )
                            }
                            ,select  : function( me, recordDetails, returnedData ){
                                var { 0 : store }   = Ext.getCmp('otherDeductions' + module).selModel.getSelection()
                                    ,row            = this.findRecord(this.valueField, this.getValue())
                                    ,msg            = 'The selected reference already exists in the list. You may edit the existing record or remove it.';

                                if( Ext.isUnique(this.valueField, otherDeductions, this, msg ) ) {
                                    Ext.setGridData(['fident', 'description', 'amount'], store, row)
                                }
                            }
                        }
                    })
                }
                ,{   header         : 'Item Name'
                    ,dataIndex      : 'itemName'
                    ,flex           : 1
                    ,minWidth       : 150
                    ,editor         : standards.callFunction( '_createCombo', {
                        fieldLabel      : ''
                        ,id             : 'searchItemNameCmb' + module
                        ,store			: itemStore
                        ,emptyText		: 'Select item name...'
                        ,displayField   : 'itemName'
                        ,valueField     : 'itemName'
                        ,listeners      : {
                            beforeQuery : function( me ){
                                // _checkAffiliate( searchItemStore, me )
                            }
                            ,select  : function( me, recordDetails, returnedData ){
                                _setItemDetails(this, otherDeductions);
                            }
                        }
                    })
                }
                ,{	header          : 'Description'
                    ,dataIndex      : 'description'
                    ,width          : 200
                    ,columnWidth    : 30
                    ,flex           : 1
                    ,editor		    : 'text'
                }
                ,{	header          : 'Qty'
                    ,dataIndex      : 'qty'
                    ,width          : 120
                    ,columnWidth    : 30
                    ,xtype          : 'numbercolumn'
                    ,editor         : 'float'
                }
                ,{	header          : 'Price'
                    ,dataIndex      : 'price'
                    ,width          : 120
                    ,columnWidth    : 30
                    ,xtype          : 'numbercolumn'
                    ,editor         : 'float'
                }
                ,{	header          : 'Amount'
                    ,dataIndex      : 'amount'
                    ,width          : 100
                    ,columnWidth    : 30
                    ,xtype          : 'numbercolumn'
                    ,editor         : 'float'
                    ,summaryType    : 'sum'
                    ,summaryRenderer: function(value, summaryData, dataIndex){
                        // Ext.getCmp( 'totalAmount' + module ).setValue( Ext.util.Format.number( value, '0,000.00' ) );
                        // return value;
                    }
                }
            ];

            function _setItemDetails( me, grdStore ) {
                var { 0 : store }   = Ext.getCmp('otherDeductions' + module).selModel.getSelection()
                    ,row            = me.findRecord(me.valueField, me.getValue())
                    ,msg            = 'The selected item already exists in the list. You may edit the existing item or remove it.';

                if( Ext.isUnique(me.valueField, grdStore, me, msg ) ) {
                    Ext.setGridData(['idItem', 'price'] , store, row)
                }
            }

            function _deleteDeduction(){
                var selRecord = Ext.getCmp('otherDeductions' + module ).selModel.getSelection()[0];
                otherDeductions.remove( selRecord );
            }

            return {
                xtype : 'tabpanel'
                ,items: [
                    {
                        title: 'Other Deduction(s)'
                        ,layout:{
                            type: 'card'
                        }
                        ,items  :   [
                            {	xtype : 'container'
                                ,width : 390
                                ,items : [
                                    standards.callFunction( '_gridPanel',{
                                        id		        : 'otherDeductions' + module
                                        ,module	        : module
                                        ,store	        : otherDeductions
                                        ,style          : 'margin-bottom:10px;'
                                        ,noDefaultRow   : true
                                        ,noPage         : true
                                        ,plugins        : true
                                        ,tbar : {
                                            canPrint        : false
                                            ,noExcel        : true
                                            ,content        : 'add'
                                            ,deleteRowFunc  : _deleteDeduction
                                        }
                                        ,features       : {
                                            ftype   : 'summary'
                                        }
                                        ,plugins        : true
                                        ,columns        : columns
                                        ,listeners	    : {
                                            afterrender : function() {
                                                otherDeductions.load({});
                                            }
                                            ,edit       : function( me, rowData ) {
                                                var index = rowData.rowIdx
                                                ,store = this.getStore().getRange();

                                                var totalAmount = 0;

                                                switch( rowData.field ) {
                                                    case 'qty':
                                                        if( rowData.value == 0 ) {
                                                            standards.callFunction('_createMessageBox', {
                                                                msg : 'Invalid input. Value must be greater than 0.'
                                                                ,fn: function(){
                                                                    store[index].set('qty', rowData.originalValue );
                                                                }
                                                            });
                                                        }
                                                        totalAmount = rowData.value * store[index].data.price;
                                                        store[index].set('amount', totalAmount );
                                                        break;
                                                    // case 'price':
                                                    //     if( rowData.value == 0 ) {
                                                    //         standards.callFunction('_createMessageBox', {
                                                    //             msg : 'Invalid input. Value must be greater than 0.'
                                                    //             ,fn: function(){
                                                    //                 store[index].set('price', rowData.originalValue );
                                                    //             }
                                                    //         });
                                                    //     }
                                                    //     totalAmount = store[index].data.qty * rowData.value
                                                    //     break;
                                                }

                                                // store[index].set('amount', totalAmount );
                                            }
                                        }
                                    })
                                ]
                            }
                        ]
                    }
                    ,{
                        title: 'Journal Entries'
                        ,layout:{
                            type: 'card'
                        }
                        ,items  :   [
                            standards.callFunction( '_gridJournalEntry',{
                                module	        : module
                                ,hasPrintOption : 1
                                ,config         : config
                                ,items          : Ext.getCmp('otherDeductions' + module)
                                ,supplier       : 'pCode'
                            })
                        ]
                    }
                ]
            }
        }

        function _saveForm( form ){
                var params = {
                    onEdit                  : onEdit
                    ,otherDeductions       : Ext.encode ( Ext.getCmp('otherDeductions'+module).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0) )
                    ,gridJournalEntry      : Ext.encode ( Ext.getCmp('gridJournalEntry'+module).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0) )
                    ,invoices : Ext.encode ({
                        idAffiliate         : idAffiliate
                        ,idCostCenter       : Ext.getCmp('idCostCenter'+module).getValue()
                        ,idReference        : Ext.getCmp('idReference'+module).getValue()
                        ,idReferenceSeries  : Ext.getCmp('idReferenceSeries'+module).getValue()
                        ,date			    : Ext.getCmp( 'tdate' + module).getValue()
					    ,time			    : Ext.Date.format(Ext.getCmp( 'ttime' + module).getValue(), 'h:i:s A')
                        // ,idDriver           : Ext.getCmp( 'idDriver' + module).getValue()
                        // ,plateNumber        : Ext.getCmp('plateNumber' + module).getValue()
                        ,pType              : ( ( Ext.getCmp('striker' + module).getValue() ) ? 2 : 1 )
                        ,pCode              : ( ( Ext.getCmp('striker' + module).getValue() ) ? Ext.getCmp('idSupplier' + module).getValue() : Ext.getCmp('idCustomer' + module).getValue() )
                        ,referenceNum       : Ext.getCmp( 'referenceNum' + module).getValue()
                        ,cancelTag          : 0
                        ,dateModified       : new Date()
                        ,hasJournal         : 1
                        ,status             : 2 //APPROVED
                        ,archived           : 0
                        ,cancelledBy        : 0
                        ,idModule           : idModule
                        ,idInvoice          : ( typeof dataHolder['idInvoice'] != 'undefined' ) ? dataHolder['idInvoice'] : null
                    })
                    ,rental : Ext.encode ({
                        idProject               : Ext.getCmp( 'idProject' + module).getValue()
                        ,isConstruction         : Ext.getCmp( 'isConstruction' + module).getValue()
                        ,striker                : Ext.getCmp( 'striker' + module).getValue()
                        ,idTruckType            : Ext.getCmp( 'idTruckType' + module).getValue()
                        ,remarks                : Ext.getCmp( 'remarks' + module).getValue()
                        ,dateFrom               : Ext.getCmp( 'sdate' + module).getValue()
                        ,dateTo                 : Ext.getCmp( 'edate' + module).getValue()
                        ,model                  : Ext.getCmp( 'model' + module).getValue()
                        ,rateType               : Ext.getCmp( 'idRateType' + module).getValue()
                        ,rate                   : Ext.getCmp( 'rateType' + module).getValue()
                        ,totalRate              : Ext.getCmp( 'totalRate' + module).getValue()
                        ,hours                  : ( ( Ext.getCmp( 'idRateType' + module).getValue() == 1 ? Ext.getCmp( 'rate' + module).getValue() : null ) )
                        ,trip                   : ( ( Ext.getCmp( 'idRateType' + module).getValue() == 2 ? Ext.getCmp( 'rate' + module).getValue() : null ) )
                        ,kilometer              : ( ( Ext.getCmp( 'idRateType' + module).getValue() == 3 ? Ext.getCmp( 'rate' + module).getValue() : null ) )
                        ,mileage                : Ext.getCmp( 'mileage' + module).getValue()
                        ,fuelLevel              : Ext.getCmp( 'fuelLevel' + module).getValue()
                        ,fuelUsage              : Ext.getCmp( 'fuelUsage' + module).getValue()
                        ,status                 : Ext.getCmp( 'status' + module).getValue()
                        ,returnDate             : ( Ext.getCmp( 'status' + module).getValue() == 2 ) ? Ext.getCmp( 'returnDate' + module).getValue() : null
                        ,returnMileage          : ( Ext.getCmp( 'status' + module).getValue() == 2 ) ? Ext.getCmp( 'returnMileage' + module).getValue() : null
                        ,returnFuelLevel        : ( Ext.getCmp( 'status' + module).getValue() == 2 ) ? Ext.getCmp( 'returnFuelLevel' + module).getValue() : null
                        ,penalty                : ( Ext.getCmp( 'status' + module).getValue() == 2 ) ? Ext.getCmp( 'penalty' + module).getValue() : null
                        ,idRental               : ( typeof dataHolder['idRental'] != 'undefined' ) ? dataHolder['idRental'] : null
                        ,idDriver               : Ext.getCmp( 'idDriver' + module).getValue()
                        ,idTruckProfile         : Ext.getCmp('plateNumber' + module).getValue()
                    })
                }

                _submitForm( form, params );
        }

        function _resetForm( form ){
			form.reset();
			Ext.resetGrid( 'otherDeductions' + module );
            Ext.resetGrid( 'gridJournalEntry' + module );

            Ext.getCmp('returnDetailsContainer'+module).hide();
            _requireField( 'returnDate', true );
            _requireField( 'returnMileage', true );
            _requireField( 'returnFuelLevel', true );
            _requireField( 'penalty', true );

            /* Clear global values */
            dataHolder = {}
            onEdit = 0
        }

        function _submitForm( form, params ){
            form.submit({
                url : route + 'saveForm'
                ,params : params
                ,success : function( action, response ){
                    var resp 	= Ext.decode( response.response.responseText ),
                    msg			= ( resp.match == 0 ) ? 'SAVE_SUCCESS' : 'SAVE_FAILURE'
                    ,match      = parseInt( resp.match, 10 );

                    standards.callFunction( '_createMessageBox', {
                        msg     : msg
                        ,action : ''
                        ,fn     : function(){
                            if( resp.match == 0 ) _resetForm( form );
                        }
                    } );
                }
            });
        }

        function _gridHistory() {
            var store   = standards.callFunction( '_createRemoteStore', {
                fields      : [
                    'idInvoice'
                    ,'idRental'
                    ,'date'
                    ,'referenceNum'
                    ,'customerContactNumber'
                    ,'customerName'
                    ,'driverName'
                    ,'projectName'
                    ,'plateNumber'
                    ,'status'
                ]
                ,url        : route + 'viewAll'
            } );

            function saveReturn( data ){
                var params = {
                    rental : Ext.encode ({
                        status              : 2
                        ,returnDate         : Ext.getCmp( 'returnDateModal' + module).getValue()
                        ,returnMileage      : Ext.getCmp( 'returnMileageModal' + module).getValue()
                        ,returnFuelLevel    : Ext.getCmp( 'returnFuelLevelModal' + module).getValue()
                        ,penalty            : Ext.getCmp( 'penaltyModal' + module).getValue()
                        ,idRental           : data['idRental']
                    })
                };

                _submitReturnForm( params );
            }

            function _submitReturnForm( params ){
                Ext.Ajax.request({
                    url 	: route + 'saveReturn'
                    ,params : params
                    ,success : function( response ){
                        var resp 	= Ext.decode( response.responseText ),
                        msg			= ( resp.match == 0 ) ? 'SAVE_SUCCESS' : 'SAVE_FAILURE'
                        ,match      = parseInt( resp.match, 10 );

                        standards.callFunction( '_createMessageBox', {
                            msg     : msg
                            ,action : ''
                            ,fn     : function(){
                                if( resp.match == 0 ) Ext.getCmp( 'winMod' + module ).close();
                                Ext.resetGrid( 'gridHistory' + module );
                            }
                        } );
                    }
                });
            }

            function returnRecord( data ){
                Ext.Ajax.request({
                    url: route + 'getReturnDetails'
                    ,params: {
                        id    : data.idRental
                    }
                    ,success: function(response){
                        var resp = Ext.decode( response.responseText );
                        showModuleForm( resp.view[0] );
                    }
                });
            }

            function showModuleForm( resp ){
				Ext.create( 'Ext.Window', {
					id          : 'winMod'+module
					,title      : 'Return of Truck/Equipment'
					// ,width      : 800
					,modal      : true
					,closable   : false
					,resizable  : false
					,items      : [
						{
							xtype   : 'form'
							,width  : 800
							,border : false
							,items  : [
                                {
                                    xtype       : 'fieldset'
                                    ,layout     : 'column'
                                    ,padding    : 10
                                    ,border     : false
                                    ,items		: [
                                        {
                                            xtype			: 'container'
                                            ,columnWidth	: .5
                                            ,items          : [
                                                standards.callFunction( '_createDateField', {
                                                    id              : 'returnDateModal' + module
                                                    ,fieldLabel     : 'Return Date'
                                                    ,maxValue	    : new Date()
                                                    ,value          : resp.returnDate
                                                    ,listeners      : {
                                                        change: function() {
                                                            // _computeAge( this.value );
                                                        }
                                                    }
                                                } )
                                                ,standards.callFunction('_createNumberField',{
                                                    id			    : 'returnMileageModal' + module
                                                    ,module		    : module
                                                    ,value          : resp.returnMileage
                                                    ,fieldLabel	    : 'Mileage'
                                                })
                                                ,standards.callFunction('_createNumberField',{
                                                    id			    : 'returnFuelLevelModal' + module
                                                    ,module		    : module
                                                    ,value          : resp.returnFuelLevel
                                                    ,fieldLabel	    : 'Fuel Level'
                                                })
                                            ]
                                        }
                                        ,{
                                            xtype			: 'container'
                                            ,columnWidth	: .5
                                            ,items          : [
                                                standards.callFunction( '_createTextArea', {
                                                    id			    : 'penaltyModal' + module
                                                    ,fieldLabel	    : 'Penalty'
                                                    ,value          : resp.penalty
                                                } )
                                            ]
                                        }
                                    ]
                                }
							]
							,buttons:[
								{
									xtype   : 'box',
									autoEl  : { cn: '<div style="color: #3a947c">Record has been successfully saved.</div>' },
									hidden  : true,
									id      : 'sucmsg' + module
								}
								,{
									xtype       : 'button'
									,text       : 'Save'
									,id         : 'saveReturn' + module
									// ,width      : 100
									,hidden     : (canEdit) ? false : true
									,handler    : function(){ saveReturn( resp ); }
								}
								,{
									xtype       : 'button'
									,text       : 'Close'
									// ,width      : 100
									,handler    : function(){ Ext.getCmp( 'winMod' + module ).close(); }
								}
							]
						}
					]
				}).show();

			}

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
                        ,format         : 'm/d/Y'
                    }
                    ,{   header         : 'Reference'
                        ,dataIndex      : 'referenceNum'
                        ,width          : 100
                        ,columnWidth    : 20
                    }
                    ,{  header          : "Project Name"
                        ,dataIndex      : 'projectName'
                        ,flex           : 1
                        ,minWidth       : 150
                        ,columnWidth    : 40
                    }
                    ,{  header          : "Customer Name"
                        ,dataIndex      : 'customerName'
                        ,flex           : 1
                        ,minWidth       : 150
                        ,columnWidth    : 40
                    }
                    ,{  header          : "Contact Number"
                        ,dataIndex      : 'customerContactNumber'
                        ,flex           : 1
                        ,minWidth       : 150
                        ,columnWidth    : 40
                    }
                    ,{  header          : 'Plate Number'
                        ,dataIndex      : 'plateNumber'
                        ,flex           : 1
                        ,minWidth       : 150
                        ,columnWidth    : 40
                    }
                    ,{  header          : "Driver's Name"
                        ,dataIndex      : 'driverName'
                        ,flex           : 1
                        ,minWidth       : 150
                        ,columnWidth    : 40
                    }
                    ,{  header          : "Status"
                        ,dataIndex      : 'status'
                        ,flex           : 1
                        ,minWidth       : 150
                        ,columnWidth    : 40
                    }
                    ,standards.callFunction( '_createActionColumn', {
						canEdit     : canEdit
						,icon       : 'pencil'
						,tooltip    : 'Edit record'
						,Func       : _editRecord
                    } )
                    ,standards.callFunction( '_createActionColumn' ,{
						icon		: 'import'
						,tooltip	: 'Return'
						,Func		: returnRecord
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
			onEdit = 1;
			module.getForm().retrieveData({
				url				: route + 'retrieveData'
				,params			: {
					id : data.idRental
				}
				,excludes		: [ 'idProject' ]
				,hasFormPDF		: true
				,success 		: function( response, responseText ){
					dataHolder = response;

                    Ext.getCmp('idProject' + module).getStore().proxy.extraParams.isConstruction = dataHolder.isConstruction;
                    Ext.getCmp('idProject' + module).getStore().load({
                        callback: function() {
                            Ext.getCmp('idProject' + module).setValue( parseInt(dataHolder.idProject) );
                        }
                    });

                    Ext.getCmp('otherDeductions' + module).getStore().proxy.extraParams.idRental = data.idRental;
                    Ext.getCmp('otherDeductions' + module).getStore().load({});

                    Ext.getCmp('idRateType' + module).setValue( parseInt(dataHolder.rateType) );
                    Ext.getCmp('referenceNum' + module).setValue( parseInt(dataHolder.referenceNum) );

                    if( parseInt(dataHolder.status) == 2 ){
                        Ext.getCmp('returnDetailsContainer' + module ).setVisible( true );

                        _requireField( 'returnDate', false );
                        _requireField( 'returnMileage', false );
                        _requireField( 'returnFuelLevel', false );
                        _requireField( 'penalty', false );

                    } else {
                        Ext.getCmp('returnDetailsContainer' + module ).setVisible( false );

                        _requireField( 'returnDate', true );
                        _requireField( 'returnMileage', true );
                        _requireField( 'returnFuelLevel', true );
                        _requireField( 'penalty', true );
                    }

                    Ext.getCmp('returnDate' + module).setValue( dataHolder.returnDate );
                    Ext.getCmp('returnMileage' + module).setValue( dataHolder.returnMileage );
                    Ext.getCmp('returnFuelLevel' + module).setValue( dataHolder.returnFuelLevel );
                    Ext.getCmp('penalty' + module).setValue( dataHolder.penalty );

				}
			});
		}

        function _deleteRecord( data ) {
            standards.callFunction( '_createMessageBox', {
                msg		: 'DELETE_CONFIRM'
                ,action	: 'confirm'
                ,fn		: function( btn ){
                    if( btn == 'yes' ){
                        Ext.Ajax.request({
                            url 	: route + 'deleteRecord'
                            ,params : { id: data.idInvoice }
                            ,success : function( response ){
                                var resp = Ext.decode( response.responseText );
                                if( resp.match == 1 ) {
                                    standards.callFunction( '_createMessageBox', {
                                        msg : 'DELETE_USED'
                                    } );
                                } else {
                                    Ext.getCmp('gridHistory' + module).getStore().load();
                                }
                            }
                        });
                    }
                }
            } );
        }

		function _customListPDF() {
            Ext.Ajax.request({
				url  		: route + 'customListPDF'
				,params 	: {
					items : Ext.encode( Ext.getCmp('gridHistory'+module).store.data.items.map((item)=>item.data) )
				}
				,success 	: function(response){
					if( isGae == 1 ){
						window.open(route+'viewPDF/Rental of Heavy Equipment List.pdf','_blank');
					}else{
						window.open('pdf/trucking/Rental of Heavy Equipment List.pdf');
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
                    ,items      : Ext.encode( Ext.getCmp('gridHistory'+module).store.data.items.map((item)=>item.data) )
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
                    ,idRental       : dataHolder['idRental']
					,form			: Ext.encode( par )
                    ,journalEntries     : Ext.encode( journalEntries )
					,hasPrintOption     : Ext.getCmp('printStatusJEgridJournalEntry' + module).getValue()
                    ,idInvoice		    : dataHolder.idInvoice
                    ,idAffiliate	    : dataHolder.idAffiliate
                }
                ,success	: function(response, action){
                    if( isGae == 1 ){
						window.open(route+'viewPDF/Rental of Heavy Equipment','_blank');
					}else{
						window.open('pdf/trucking/Rental of Heavy Equipment.pdf');
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