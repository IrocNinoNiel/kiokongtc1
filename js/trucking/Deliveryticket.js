/**
 * Developer: Hazel Alegbeleye
 * Module: Delivery Ticket
 * Date: Nov 09, 2021
 * Finished: Dec 01, 2021
 * Description: This module allows the authorized user to set (add, edit and delete) a delivery ticket
 * DB Tables: 
 * */ 

 function Deliveryticket() {
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
            ,customerStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name']
                ,url        : route + 'getCustomers'
                ,startAt    :  0
                ,autoLoad   : true
            })
            ,itemClassificationStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name']
                ,url        : route + 'getClassifications'
                ,startAt    :  0
                ,autoLoad   : true
            })
            ,itemStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name']
                ,url        : route + 'getItems'
                ,startAt    :  0
                ,autoLoad   : true
            })
            ,typeStore = standards.callFunction( '_createLocalStore' , {
                data    : [
                    'Per Load'
                    ,'Per Day'
                ]
                ,startAt : 1
            } )
            ,projectStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name']
                ,url        : route + 'getProjects'
                ,startAt    :  0
                // ,autoLoad   : true
            });
;
            
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
                                        id              : 'isConstruction' + module
                                        ,fieldLabel     : 'For Construction'
                                        ,listeners      : {
                                            change  : function( me ) {
                                            }
                                        }
                                    }),
                                    standards.callFunction( '_createCombo', {
                                        id              : 'idProject' + module
                                        ,fieldLabel     : 'Project Name'
                                        ,store          : projectStore
                                        ,displayField   : 'name'
                                        ,valueField     : 'id'
                                        ,listeners      : {
                                            beforeQuery :  function() {
                                                projectStore.proxy.extraParams.isConstruction = Ext.getCmp( 'isConstruction' + module ).getValue();
											}
											,select	: function( me, record ){
											}
                                        }
                                    } )
                                    ,standards.callFunction( '_createCombo', {
                                        id              : 'idDriver' + module
                                        ,fieldLabel     : "Driver's Name"
                                        ,store          : employeeStore
                                        ,displayField   : 'name'
                                        ,valueField     : 'id'
                                        ,listeners      : {
                                            select     : function( me , record ){
                                            }
                                        }
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
                                        }
                                    } )
                                    ,standards.callFunction( '_createCombo', {
                                        id              : 'plateNumber' + module
                                        ,fieldLabel     : 'Plate Number'
                                        ,store          : plateNumberStore
                                        ,displayField   : 'name'
                                        ,valueField     : 'id'
                                        ,listeners      : {
                                            select     : function( me , record ){
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
                                        id              : 'pCode' + module
                                        ,fieldLabel     : "Customer Name"
                                        ,store          : customerStore
                                        ,displayField   : 'name'
                                        ,valueField     : 'id'
                                        ,listeners      : {
                                            select     : function( me , record ){
                                            }
                                        }
                                    } )
                                    ,standards.callFunction( '_createTextArea', {
                                        id			    : 'remarks' + module
                                        ,fieldLabel	    : 'Remarks'
                                        ,allowBlank	    : true
                                    } )
                                    ,standards.callFunction( '_createCombo', {
                                        id              : 'deliveryTicketType' + module
                                        ,fieldLabel     : "Type"
                                        ,store          : typeStore
                                        ,displayField   : 'name'
                                        ,valueField     : 'id'
                                        ,listeners      : {
                                            select     : function( me , record ){
                                                if( me.value == 1 ){
                                                    Ext.getCmp('gridDayActivity' + module ).setVisible( false );
                                                    Ext.resetGrid( 'gridDayActivity' + module );

                                                    Ext.getCmp('gridLoadActivity' + module ).setVisible( true );
                                                } else {
                                                    Ext.getCmp('gridLoadActivity' + module ).setVisible( false );
                                                    Ext.resetGrid( 'gridLoadActivity' + module );

                                                    Ext.getCmp('gridDayActivity' + module ).setVisible( true );
                                                }

                                                Ext.getCmp('totalAmount' + module ).setVisible( true );
                                            }
                                        }
                                    } )
                                    ,standards.callFunction('_createNumberField',{
                                        id			: 'odometer' + module
                                        ,module		: module
                                        ,fieldLabel	: 'Odometer'
                                        ,allowBlank	: true
                                    })
                                ]
                            }
                        ]
                    }
                    ,loadActivityGrid()
                    ,dailyActivityGrid()
                    ,standards.callFunction('_createNumberField',{
                        id              : 'totalAmount' + module
                        ,fieldLabel     : 'Total Amount'
                        ,readOnly       : true
                        ,style          : 'float:right; padding:8px; margin-top:5px; margin-right:5px;'
                        ,hidden         : true
                    })
                ]
                ,listItems: _gridHistory()
            } );
        }

        function _setItemDetails( me, grdStore ) {
            var { 0 : store }   = Ext.getCmp('otherDeductions' + module).selModel.getSelection()
                ,row            = me.findRecord(me.valueField, me.getValue())
                ,msg            = 'The selected item already exists in the list. You may edit the existing item or remove it.';

            if( Ext.isUnique(me.valueField, grdStore, me, msg ) ) {
                Ext.setGridData(['idItem', 'price'] , store, row)
            }
        }

        function loadActivityGrid(){

            let loadActivities = standards.callFunction( '_createRemoteStore', {
                fields  : [ 
                        'activityName', 
                        'area',
                        'lubricant',
                        { name: 'noOfLoads', type: 'number' }, 
                        { name: 'fuelConsumed', type: 'number' }, 
                        { name: 'rate', type: 'number' },
                        { name: 'totalForLoads', type: 'number' },
                        { name: 'idLocation', type: 'number' }
                    ]			
                ,url    : route + 'getActivity'
            } )
            ,locationStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'idLocation', type : 'number' }, 'locationName']
                ,url        : route + 'getLocation'
                ,startAt    :  0
                ,autoLoad   : true
            });

            let columns = [
                {	header          : 'Activity Name'
                    ,dataIndex      : 'activityName'
                    ,width          : 200
                    ,columnWidth    : 30
                    ,flex           : 1 
                    ,editor		    : 'text'
                }
                ,{	header          : 'Area'
                    ,dataIndex      : 'area'
                    ,width          : 200
                    ,columnWidth    : 30
                    ,flex           : 1 
                    ,editor         : standards.callFunction( '_createCombo', {
                        fieldLabel      : ''
                        ,id             : 'idLocation' + module
                        ,store			: locationStore
                        ,emptyText		: 'Select location...'
                        ,displayField   : 'locationName'
                        ,valueField     : 'locationName'
                        ,listeners      : {
                            beforeQuery : function( me ){
                                // _checkAffiliate( searchItemStore, me )
                            }
                            ,select  : function( me, recordDetails, returnedData ){	
                                // _setItemDetails(this, otherDeductions);

                                var { 0 : store }   = Ext.getCmp('gridLoadActivity' + module).selModel.getSelection()
                                    ,row            = me.findRecord(me.valueField, me.getValue())
                                    ,msg            = 'The selected location already exists in the list. You may edit the existing record or remove it.';

                                Ext.setGridData(['idLocation'] , store, row);
                            }
                        }
                    })
                }
                ,{	header          : 'No. of Loads'
                    ,dataIndex      : 'noOfLoads'
                    ,width          : 100
                    ,columnWidth    : 30
                    ,xtype          : 'numbercolumn'
                    ,editor         : 'float'
                }
                ,{	header          : 'Fuel Consumed'
                    ,dataIndex      : 'fuelConsumed'
                    ,width          : 120
                    ,columnWidth    : 30
                    ,xtype          : 'numbercolumn'
                    ,editor         : 'float'
                }
                ,{	header          : 'Lubricant'
                    ,dataIndex      : 'lubricant'
                    ,width          : 200
                    ,columnWidth    : 30
                    ,editor		    : 'text'
                }
                ,{	header          : 'Rate'
                    ,dataIndex      : 'rate'
                    ,width          : 100
                    ,columnWidth    : 30
                    ,xtype          : 'numbercolumn'
                    ,editor         : 'float'
                }
                ,{	header          : 'Total'
                    ,dataIndex      : 'totalForLoads'
                    ,width          : 100
                    ,columnWidth    : 30
                    ,xtype          : 'numbercolumn'
                    ,summaryType    : 'sum'
                    ,summaryRenderer: function(value, summaryData, dataIndex){
                        Ext.getCmp( 'totalAmount' + module ).setValue( Ext.util.Format.number( value, '0,000.00' ) );
                        return value;
                    }
                }
            ];

            function _deleteLoadActivityRecord() {
                var selRecord = Ext.getCmp('gridLoadActivity' + module ).selModel.getSelection()[0];
                loadActivities.remove( selRecord );
            }
            
            return standards.callFunction( '_gridPanel',{
                id		        : 'gridLoadActivity' + module
                ,module	        : module
                ,store	        : loadActivities
                ,style          : 'margin-bottom:10px;'
                ,noDefaultRow   : true
                ,noPage         : true
                ,plugins        : true
                ,tbar : {
                    canPrint        : false
                    ,noExcel        : true
                    ,content        : 'add'
                    ,deleteRowFunc  : _deleteLoadActivityRecord
                }
                ,features       : {
                    ftype   : 'summary'
                }
                ,plugins        : true
                ,columns        : columns
                ,listeners	    : {
                    afterrender : function() {
                        loadActivities.load({});
                        Ext.getCmp('gridLoadActivity' + module ).setVisible( false );
                    }
                    ,edit       : function( me, rowData ) {
                        var index = rowData.rowIdx
                        ,store = this.getStore().getRange();

                        var totalAmount = 0;

                        switch( rowData.field ) {
                            case 'noOfLoads':
                                if( rowData.value == 0 ) {
                                    standards.callFunction('_createMessageBox', { 
                                        msg : 'Invalid input. Value must be greater than 0.'
                                        ,fn: function(){ 
                                            store[index].set('noOfLoads', rowData.originalValue );
                                        }
                                    });
                                }
                                totalAmount = rowData.value * store[index].data.rate;
                                break;
                            case 'rate':
                                if( rowData.value == 0 ) {
                                    standards.callFunction('_createMessageBox', { 
                                        msg : 'Invalid input. Value must be greater than 0.'
                                        ,fn: function(){ 
                                            store[index].set('rate', rowData.originalValue );
                                        }
                                    });
                                }
                                totalAmount = store[index].data.noOfLoads * rowData.value
                                break;
                        }

                        store[index].set('totalForLoads', totalAmount );
                    }
                }
            });
        }

        function dailyActivityGrid(){

            let dailyActivities = standards.callFunction( '_createRemoteStore', {
                fields  : [  
                        'activityName', 
                        'description', 
                        'area', 
                        'lubricant',
                        { name: 'noOfDays', type: 'number' }, 
                        { name: 'fuelConsumed', type: 'number' }, 
                        { name: 'rate', type: 'number' },
                        { name: 'totalForDays', type: 'number' },
                        { name: 'idLocation', type: 'number' }
                    ]			
                ,url    : route + 'getActivity'
            } )
            ,locationStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'idLocation', type : 'number' }, 'locationName']
                ,url        : route + 'getLocation'
                ,startAt    :  0
                ,autoLoad   : true
            });

            let columns = [
                {	header          : 'Activity Name'
                    ,dataIndex      : 'activityName'
                    ,width          : 200
                    ,columnWidth    : 30
                    ,editor		    : 'text'
                }
                ,{	header          : 'No. of Days'
                    ,dataIndex      : 'noOfDays'
                    ,width          : 100
                    ,columnWidth    : 30
                    ,xtype          : 'numbercolumn'
                    ,editor         : 'float'
                }
                ,{	header          : 'Description'
                    ,dataIndex      : 'description'
                    ,width          : 200
                    ,columnWidth    : 30
                    ,flex           : 1 
                    ,editor		    : 'text'
                }
                ,{	header          : 'Area'
                    ,dataIndex      : 'area'
                    ,width          : 200
                    ,columnWidth    : 30
                    ,flex           : 1 
                    ,editor         : standards.callFunction( '_createCombo', {
                        fieldLabel      : ''
                        ,id             : 'idLocation' + module
                        ,store			: locationStore
                        ,emptyText		: 'Select location...'
                        ,displayField   : 'locationName'
                        ,valueField     : 'locationName'
                        ,listeners      : {
                            beforeQuery : function( me ){
                                // _checkAffiliate( searchItemStore, me )
                            }
                            ,select  : function( me, recordDetails, returnedData ){	
                                // _setItemDetails(this, otherDeductions);

                                var { 0 : store }   = Ext.getCmp('gridDayActivity' + module).selModel.getSelection()
                                    ,row            = me.findRecord(me.valueField, me.getValue())
                                    ,msg            = 'The selected location already exists in the list. You may edit the existing record or remove it.';

                                Ext.setGridData(['idLocation'] , store, row)
                            }
                        }
                    })
                }
                ,{	header          : 'Fuel Consumed'
                    ,dataIndex      : 'fuelConsumed'
                    ,width          : 120
                    ,columnWidth    : 30
                    ,xtype          : 'numbercolumn'
                    ,editor         : 'float'
                }
                ,{	header          : 'Lubricant'
                    ,dataIndex      : 'lubricant'
                    ,width          : 200
                    ,columnWidth    : 30
                    ,editor		    : 'text'
                }
                ,{	header          : 'Rate'
                    ,dataIndex      : 'rate'
                    ,width          : 100
                    ,columnWidth    : 30
                    ,xtype          : 'numbercolumn'
                    ,editor         : 'float'
                }
                ,{	header          : 'Amount'
                    ,dataIndex      : 'totalForDays'
                    ,width          : 100
                    ,columnWidth    : 30
                    ,xtype          : 'numbercolumn'
                    ,summaryType    : 'sum'
                    ,summaryRenderer: function(value, summaryData, dataIndex){
                        Ext.getCmp( 'totalAmount' + module ).setValue( Ext.util.Format.number( value, '0,000.00' ) );
                        return value;
                    }
                }
            ];

            function _deleteDayActivityRecord() {
                var selRecord = Ext.getCmp('gridDayActivity' + module ).selModel.getSelection()[0];
                dailyActivities.remove( selRecord );
            }
            
            return standards.callFunction( '_gridPanel',{
                id		        : 'gridDayActivity' + module
                ,module	        : module
                ,store	        : dailyActivities
                ,style          : 'margin-bottom:10px;'
                ,noDefaultRow   : true
                ,noPage         : true
                ,plugins        : true
                ,tbar : {
                    canPrint        : false
                    ,noExcel        : true
                    ,content        : 'add'
                    ,deleteRowFunc  : _deleteDayActivityRecord
                }
                ,features       : {
                    ftype   : 'summary'
                }
                ,plugins        : true
                ,columns        : columns
                ,listeners	    : {
                    afterrender : function() {
                        dailyActivities.load({});
                        Ext.getCmp('gridDayActivity' + module ).setVisible( false );
                    }
                    ,edit       : function( me, rowData ) {
                        var index = rowData.rowIdx
                        ,store = this.getStore().getRange();

                        var totalAmount = 0;

                        switch( rowData.field ) {
                            case 'noOfDays':
                                if( rowData.value == 0 ) {
                                    standards.callFunction('_createMessageBox', { 
                                        msg : 'Invalid input. Value must be greater than 0.'
                                        ,fn: function(){ 
                                            store[index].set('noOfDays', rowData.originalValue );
                                        }
                                    });
                                }
                                totalAmount = rowData.value * store[index].data.rate;
                                break;
                            case 'rate':
                                if( rowData.value == 0 ) {
                                    standards.callFunction('_createMessageBox', { 
                                        msg : 'Invalid input. Value must be greater than 0.'
                                        ,fn: function(){ 
                                            store[index].set('rate', rowData.originalValue );
                                        }
                                    });
                                }
                                totalAmount = store[index].data.noOfDays * rowData.value
                                break;
                        }

                        store[index].set('totalForDays', totalAmount );
                    }
                }
            });
        }
    
        function _saveForm( form ){

                var params = {
                    onEdit                  : onEdit
                    ,loadActivityGrid       : Ext.encode ( Ext.getCmp('gridLoadActivity'+module).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0) )
                    ,dailyActivityGrid      : Ext.encode ( Ext.getCmp('gridDayActivity'+module).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0) )
                    ,invoices : Ext.encode ({
                        idAffiliate         : idAffiliate
                        ,idCostCenter       : Ext.getCmp('idCostCenter'+module).getValue()
                        ,idReference        : Ext.getCmp('idReference'+module).getValue()
                        ,idReferenceSeries  : Ext.getCmp('idReferenceSeries'+module).getValue()
                        ,date			    : Ext.getCmp( 'tdate' + module).getValue()
					    ,time			    : Ext.Date.format(Ext.getCmp( 'ttime' + module).getValue(), 'h:i:s A')
                        // ,idDriver           : Ext.getCmp( 'idDriver' + module).getValue()
                        // ,plateNumber        : Ext.getCmp( 'plateNumber' + module).getValue()
                        ,pType              : 1 //CUSTOMER
                        ,pCode              : Ext.getCmp( 'pCode' + module).getValue()
                        ,referenceNum       : Ext.getCmp( 'referenceNum' + module).getValue()
                        ,cancelTag          : 0
                        ,dateModified       : new Date()
                        ,hasJournal         : 0
                        ,status             : 2 //APPROVED
                        ,archived           : 0
                        ,cancelledBy        : 0
                        ,idModule           : idModule 
                        ,amount             : Ext.getCmp( 'totalAmount' + module).getValue()
                        ,idInvoice          : ( typeof dataHolder['idInvoice'] != 'undefined' ) ? dataHolder['idInvoice'] : null
                    })
                    ,deliveryTickets : Ext.encode ({
                        idProject               : Ext.getCmp( 'idProject' + module).getValue()
                        ,isConstruction         : Ext.getCmp( 'isConstruction' + module).getValue()
                        ,idTruckType            : Ext.getCmp( 'idTruckType' + module).getValue()
                        ,remarks                : Ext.getCmp( 'remarks' + module).getValue()
                        ,deliveryTicketType     : Ext.getCmp( 'deliveryTicketType' + module).getValue()
                        ,odometer               : Ext.getCmp( 'odometer' + module).getValue()
                        ,idDeliveryTicket       : ( typeof dataHolder['idDeliveryTicket'] != 'undefined' ) ? dataHolder['idDeliveryTicket'] : null
                        ,idDriver               : Ext.getCmp( 'idDriver' + module).getValue()
                        ,idTruckProfile         : Ext.getCmp( 'plateNumber' + module).getValue()
                    })
                }

                _submitForm( form, params );
        }

        function _resetForm( form ){
			form.reset();
			Ext.resetGrid( 'gridLoadActivity' + module );
            Ext.resetGrid( 'gridDayActivity' + module );
            Ext.getCmp('totalAmount' + module ).reset();
            Ext.getCmp('totalAmount' + module ).setVisible( false );
            Ext.getCmp('gridLoadActivity' + module).setVisible( false );
            Ext.getCmp('gridDayActivity' + module).setVisible( false );
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
                    ,'date'
                    ,'referenceNum'
                    ,'customerName'
                    ,'driverName'
                    ,'affiliateName'
                    ,'projectName'
                    ,{ name: 'totalAmount'  ,type: 'number' }
                    ,'idDeliveryTicket'
                    ,'referenceNum'
                    ,'plateNumber'
                    ,'truckType'
                ]
                ,url        : route + 'viewAll'
            } );

            return standards.callFunction('_gridPanel', {
                id 					: 'gridHistory' + module
                ,module     		: module
                ,store      		: store
				,height     		: 265
				,noDefaultRow 		: true
                ,columns            : [
                    {  header          : 'Affiliate'
                        ,dataIndex      : 'affiliateName'
                        ,flex           : 1
                        ,minWidth       : 150
                        ,columnWidth    : 40
                    }
                    ,{  header          : 'Date'
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
                    ,{  header          : "Driver's Name"
                        ,dataIndex      : 'driverName'
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
                    ,{  header          : 'Truck Type'
                        ,dataIndex      : 'truckType'
                        ,flex           : 1
                        ,minWidth       : 150
                        ,columnWidth    : 40
                    }
                    ,{  header          : 'Customer Name'
                        ,dataIndex      : 'customerName'
                        ,flex           : 1
                        ,minWidth       : 150
                        ,columnWidth    : 40
                    }
                    ,{  header          : 'Total Amount'
                        ,dataIndex      : 'totalAmount'
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
			onEdit = 1;

			module.getForm().retrieveData({
				url				: route + 'retrieveData'
				,params			: {
					id : data.idDeliveryTicket
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

                    Ext.resetGrid( 'gridLoadActivity' + module );
                    Ext.resetGrid( 'gridDayActivity' + module );

                    if( dataHolder.deliveryTicketType == 1 ){
                        Ext.getCmp('gridLoadActivity' + module).setVisible( true );
                        Ext.getCmp('gridDayActivity' + module).setVisible( false );
                        
                        Ext.getCmp('gridLoadActivity' + module).getStore().proxy.extraParams.id = data.idDeliveryTicket;
                        Ext.getCmp('gridLoadActivity' + module).getStore().load();
                    } else {
                        Ext.getCmp('gridDayActivity' + module).setVisible( true );
                        Ext.getCmp('gridLoadActivity' + module).setVisible( false );
                        
                        Ext.getCmp('gridDayActivity' + module).getStore().proxy.extraParams.id = data.idDeliveryTicket;
                        Ext.getCmp('gridDayActivity' + module).getStore().load();
                    }
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
						window.open(route+'viewPDF/Delivery Ticket List.pdf','_blank');
					}else{
						window.open('pdf/trucking/Delivery Ticket List.pdf');
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
			var par  = standards.callFunction('getFormDetailsAsObject',{ module : module });

			Ext.Ajax.request({
                url			: route + 'generatePDF'
                ,method		:'post'
                ,params		: {
                    moduleID    	    : idModule
                    ,title  	        : pageTitle
                    ,limit      	    : 50
                    ,start      	    : 0
					,printPDF   	    : 1
					,form			    : Ext.encode( par )
                    ,loadActivityGrid   : Ext.encode ( Ext.getCmp('gridLoadActivity'+module).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0) )
                    ,dailyActivityGrid  : Ext.encode ( Ext.getCmp('gridDayActivity'+module).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0) )
                    ,idInvoice		    : dataHolder.idInvoice
                    ,idAffiliate	    : dataHolder.idAffiliate
                }
                ,success	: function(response, action){
                    if( isGae == 1 ){
						window.open(route+'viewPDF/Delivery Ticket.pdf','_blank');
					}else{
						window.open('pdf/trucking/Delivery Ticket.pdf');
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