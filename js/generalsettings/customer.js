/**
 * Developer: Marie Danilene Bulosan
 * Module: Customer Settings
 * Date: 
 * Finished: 
 * Description: 
 * DB Tables: 
 * */ 

var customer = function(){
    return function(){
		var route, module, canDelete, canPrint, pageTitle, isGae, canEdit, idModule, onEdit = 0, invalidDate = 0, onEditContributionQuickSettings = 0
            ,deletedItems = [], selectedItem = [], selectedContact = [];
		
        function _mainPanel( config ){
            return standards.callFunction( '_mainPanel', {
                config      : config
                ,tbar       : {
                    listLabel       : 'List'
                    ,saveFunc       : _saveForm
                    ,resetFunc      : _resetForm
                    ,noPDFButton    : true
                    ,noExcelButton  : true
                    ,filter         : {
                        searchURL       : route + 'getCustomers'
						,emptyText      : 'Search here...'
						,module         : module
                    }
                }
                ,formItems  : [
                    __customerForm(),
                    __customerItem()
                ]
                ,listItems: gridHistory()
            } )
        }
        
        function __customerForm(){
            var sm = new Ext.selection.CheckboxModel({ 
				checkOnly   : true   
				,listeners: {
                    select: function( val, rec ){
                            var record = rec.data
                            record.chk = 1;
                            rec.set('chk',1);
                    }
                    ,deselect: function( val, record ){
                        record.set( 'chk', 0 );
                    }
                }
					
			});
			var affiliateStore = standards.callFunction( '_createRemoteStore', {
				fields      : [ 'idAffiliate', 'affiliateName',{name:'chk',type:'bool'} ]			
				,url        : route + 'getAffiliates'
            });
            var coaStore = standards.callFunction( '_createRemoteStore', {
				fields      : [ {name: 'id', type: 'number'}, 'code', 'name',{name:'chk',type:'bool'} ]			
				,url        : route + 'getCoa'
            });
			var paymentStore = standards.callFunction( '_createLocalStore', {
				data        : [ 'Cash', 'Charge' ]
				,startAt    : 1
            });
            var termStore = standards.callFunction( '_createLocalStore', {
				data        : [ '30 Days', '60 Days', '90 Days', '120 Days',  ]
				,startAt    : 1
            });
            var vattypeStore = standards.callFunction( '_createLocalStore', {
				data        : [ 'Inclusive', 'Exclusive' ]
				,startAt    : 1
            });
            
            return{   
                xtype   : 'container'
                ,layout : 'column'
                ,items  : [
                    {   xtype           : 'container'
                        ,columnWidth    : .4
                        ,items          : [
                            {
                                xtype       : 'hiddenfield'
                                ,id         : 'idCustomer' + module
                                ,value      : 0
                                ,allowBlank : true
                            }
                            ,standards.callFunction( '_createTextField', {
                                id          : 'name' + module
                                ,fieldLabel : 'Customer Name'
                                ,allowBlank : false
                            })
                            , standards.callFunction( '_createTextField', {
                                id          : 'email' + module
                                ,fieldLabel : 'Email'
                                ,allowBlank : true
                                ,maxLength  : 50
                                ,vtype : 'email'
                            }) 
                            , standards.callFunction( '_createTextField', {
                                id          : 'contactNumber' + module
                                ,fieldLabel : 'Contact Number'
                                ,allowBlank : false
                                ,maskRe     : /[^a-z,A-Z]/
                                ,maxLength  : 20
                            })         
                            ,standards.callFunction( '_createTextArea', {
                                id          : 'address' + module
                                ,fieldLabel : 'Address'
                                ,allowBlank : false
                            })          
                            , standards.callFunction( '_createTextField', {
                                id          : 'tin' + module
                                ,fieldLabel : 'TIN'
                                ,maskRe     : /[^a-z,A-Z]/
                                ,allowBlank : false
                                ,maxLength  : 10
                            }) 
                            ,standards.callFunction( '_createCombo', {
                                id              : 'paymentMethod' + module
                                ,fieldLabel     : 'Payment Method'
                                ,allowBlank     : false
                                ,store          : paymentStore
                                ,displayField   : 'name'
                                ,valueField     : 'id'
                                ,listeners      : {
                                    select : function( val, rec ) {
                                        var terms = Ext.getCmp('terms'+module);
                                        terms.reset();

                                        var value = ( val.rawValue == 'Charge' ) ? 0 : 1
                                        ,label = ( value === 1 ) ? 'Terms:' : 'Terms:' + Ext.getConstant('REQ');

                                        terms.setDisabled( value );
                                        terms.allowBlank = value;
                                        terms.labelEl.update(label);
                                       
                                    }
                                }
                            } )
                            // ,standards.callFunction( '_createCombo', {
                            //     id              : 'terms' + module
                            //     ,fieldLabel     : 'Terms'
                            //     ,allowBlank     : true
                            //     ,store          : termStore
                            //     ,displayField   : 'name'
                            //     ,valueField     : 'id'
                            //     ,listeners          : {
                            //         afterrender : function( ) {
                            //             if( Ext.getCmp('paymentMethod'+module).getValue() != 2 ) {
                            //                 this.setDisabled(true);
                            //             }
                            //         }
                            //     }
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
                                ,checked		: false
                                ,fieldLabel     : 'With Credit Limit'
                                ,listeners:{
                                    change: function( checkbox, newValue, oldValue, eOpts ){
                                        var creditLimit = Ext.getCmp('creditLimit'+module);
                                        creditLimit.setDisabled(!newValue);
                                        creditLimit.allowBlank = !newValue;
                                    }	
                                }
                            } )
                            ,standards.callFunction( '_createTextField', {
                                id                  : 'creditLimit' + module
                                ,fieldLabel         : 'Credit Limit'
                                ,allowBlank         : true
                                ,isNumber           : true
                                ,isDecimal          : true
                                ,listeners          : {
                                    afterrender : function( ) {
                                        if( Ext.getCmp('withCreditLimit'+module).getValue() == false ) {
                                            this.setDisabled(true);
                                        }
                                    }
                                }
                            })
                            ,standards.callFunction( '_createCheckField', {
                                id              : 'withVAT' + module
                                ,checked		: false
                                ,fieldLabel     : 'VAT'
                                ,listeners      : {
                                    change  : function( checkbox, newValue, oldValue, eOpts ) {
                                        // IF CHECKED THEN--, setDisabled and allowBlank = true.
                                        var vatType = Ext.getCmp('vatType'+module);

                                        vatType.setDisabled(!newValue);
                                        vatType.allowBlank = !newValue;

                                        Ext.getCmp('vatPercent'+module).setDisabled(!newValue);
                                    }
                                }
                            } )
                            ,{
                                xtype   : 'container'
                                ,layout : 'hbox'
                                ,style  : 'margin-bottom: 5px;'
                                ,items  : [
                                    standards.callFunction( '_createCombo', {
                                        id              : 'vatType' + module
                                        ,fieldLabel     : 'VAT Type'
                                        ,width          : 250
                                        ,store          : vattypeStore
                                        ,displayField   : 'name'
                                        ,valueField     : 'id'
                                        ,allowBlank     : true
                                        ,listeners : {
                                            afterrender : function() {
                                                if( Ext.getCmp('withVAT'+module).getValue() == false ) {
                                                    this.setDisabled(true);
                                                }
                                            }
                                        }
                                    } )
                                    ,standards.callFunction( '_createTextField', {
                                        id          : 'vatPercent' + module
                                        ,style      : 'margin-left: 5px;'
                                        ,width      : 80
                                        ,allowBlank : true
                                        ,isNumber   : true
                                        ,isDecimal  : true
                                        ,listeners : {
                                            afterrender : function() {
                                                if( Ext.getCmp('withVAT'+module).getValue() == false ) {
                                                    this.setDisabled(true);
                                                }
                                            }
                                        }
                                    } ),
                                    {
                                        xtype   : 'label'
                                        ,text   : '%'
                                        ,margin : 2
                                    }
                                ]
                            }
                        ]
                    }
                    ,{  xtype           : 'container'
                        ,columnWidth    : .4
                        ,layout         : 'hbox'
                        ,style          : 'margin-bottom: 5px;'
                        ,items          : [
                            {   xtype   : 'container'
                                ,html   : 'Affiliate' + Ext.getConstant('REQ') + ':'
                                ,width  : 140
                            }
                            ,standards.callFunction( '_gridPanel', {
                                id          : 'gridAffiliate' + module
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
                                ,listeners  : {
                                    afterrender : function() {
                                        affiliateStore.load({});
                                    }
                                }
                            }),
                        ]
                    }
                    ,{
                        xtype           : 'container'
                        ,columnWidth    : .4
                        ,style          : 'margin-bottom: 5px;'
                        ,items: [
                            {
                                xtype   : 'container'
                                ,layout : 'hbox'
                                ,items  : [
                                    ,standards.callFunction( '_createTextField', {
                                        id          : 'penalty' + module
                                        ,fieldLabel : 'Penalty'
                                        ,width		: 377
                                        ,allowBlank : true
                                        ,isNumber   : true
                                        ,isDecimal  : true
                                    } ),
                                    {
                                        xtype   : 'label'
                                        ,text   : '%'
                                        ,margin : 2
                                    }
                                ]
                            }
                        ]
                    }
                    ,{
                        xtype           : 'container'
                        ,columnWidth    : .4
                        ,style          : 'margin-bottom: 5px;'
                        ,items: [
                            {
                                xtype   : 'container'
                                ,layout : 'hbox'
                                ,items  : [
                                    ,standards.callFunction( '_createTextField', {
                                        id          : 'discount' + module
                                        ,fieldLabel : 'Discount'
                                        ,width		: 377
                                        ,allowBlank : true
                                        ,isNumber   : true
                                        ,isDecimal  : true
                                    } ),
                                    {
                                        xtype   : 'label'
                                        ,text   : '%'
                                        ,margin : 2
                                    }
                                ]
                            }
                        ]
                    }
                    ,{
                        xtype           : 'container'
                        ,columnWidth    : .4
                        ,style      : 'margin-bottom: 5px;'
                        ,items: [
                            {
                                xtype   : 'container'
                                ,layout : 'hbox'
                                ,items  : [
                                    ,standards.callFunction( '_createCheckField', {
                                        id          : 'withHoldingTax' + module
                                        ,checked    : false
                                        ,fieldLabel : 'Withholding Tax'
                                        ,listeners      : {
                                            change  : function( checkbox, newValue, oldValue, eOpts ) {
                                                Ext.getCmp('withHoldingTaxRate'+module).setDisabled(!newValue);
                                            }
                                        }
                                    } )
                                ]
                            }
                        ]
                    }
                    ,{
                        xtype           : 'container'
                        ,columnWidth    : .4
                        ,style          : 'margin-bottom: 5px;'
                        ,items: [
                            {
                                xtype   : 'container'
                                ,layout : 'hbox'
                                ,items  : [
                                    ,standards.callFunction( '_createTextField', {
                                        id          : 'withHoldingTaxRate' + module
                                        ,fieldLabel : 'Withholding Tax Rate'
                                        ,allowBlank : true
                                        ,isNumber   : true
                                        ,isDecimal  : true
                                        ,width		: 377
                                        ,listeners : {
                                            afterrender : function() {
                                                if( Ext.getCmp('withHoldingTax'+module).getValue() == false ) {
                                                    this.setDisabled(true);
                                                }
                                            }
                                        }
                                    } ),
                                    {
                                        xtype   : 'label'
                                        ,text   : '%'
                                        ,margin : 2
                                    }
                                ]
                            }
                        ]
                    }
                    ,{
                        xtype           : 'container'
                        ,columnWidth    : .4
                        ,style          : 'margin-bottom: 5px;'
                        ,items: [
                            {
                                xtype   : 'container'
                                ,layout : 'hbox'
                                ,style  : 'margin-bottom: 5px;'
                                ,items  : [
                                    standards.callFunction( '_createCombo', {
                                        id              : 'discountGLAcc' + module
                                        ,fieldLabel     : 'Discount GL Account'
                                        ,allowBlank     : true
                                        ,width          : 235
                                        ,store          : coaStore
                                        ,displayField   : 'code'
                                        ,valueField     : 'id'
                                        ,listeners      : {
                                            beforeQuery : function(){
                                                var selected = Ext.getCmp('gridAffiliate' + module).getSelectionModel().getSelection()
                                                if(selected.length > 0){
                                                    var ids = selected.map(affiliate => parseInt(affiliate.data.idAffiliate))
                                                    coaStore.proxy.extraParams.affiliates = JSON.stringify(ids)
                                                    coaStore.load({})
                                                }
                                            },
                                            change : function(){
                                                var val = Ext.getCmp('discountGLAcc' + module).getValue()
                                                Ext.getCmp('discountGLAccountName' + module).setValue(val)
                                            }
                                        }
                                    } )
                                    ,standards.callFunction( '_createCombo', {
                                        id              : 'discountGLAccountName' + module
                                        ,fieldLabel     : ''
                                        ,allowBlank     : true
                                        ,width          : 150
                                        ,store          : coaStore
                                        ,displayField   : 'name'
                                        ,valueField     : 'id'
                                        ,style          : 'margin-left: 5px;'
                                        ,listeners      : {
                                            beforeQuery : function(){
                                                var selected = Ext.getCmp('gridAffiliate' + module).getSelectionModel().getSelection()
                                                if(selected.length > 0){
                                                    var ids = selected.map(affiliate => parseInt(affiliate.data.idAffiliate))
                                                    coaStore.proxy.extraParams.affiliates = JSON.stringify(ids)
                                                    coaStore.load({})
                                                }
                                            },
                                            change : function(){
                                                var val = Ext.getCmp('discountGLAccountName' + module).getValue()
                                                Ext.getCmp('discountGLAcc' + module).setValue(val)
                                            }
                                        }
                                    } )
                                ]
                            }
                            ,{
                                xtype   : 'container'
                                ,layout : 'hbox'
                                ,items  : [
                                    standards.callFunction( '_createCombo', {
                                        id              : 'salesGLAcc' + module
                                        ,fieldLabel     : 'GL Sale Account'
                                        ,allowBlank     : true
                                        ,width          : 235
                                        ,store          : coaStore
                                        ,displayField   : 'code'
                                        ,valueField     : 'id'
                                        ,listeners      : {
                                            beforeQuery : function(){
                                                var selected = Ext.getCmp('gridAffiliate' + module).getSelectionModel().getSelection()

                                                if(selected.length > 0){
                                                    var ids = selected.map(affiliate => parseInt(affiliate.data.idAffiliate))
                                                    coaStore.proxy.extraParams.affiliates = JSON.stringify(ids)
                                                    coaStore.load({})
                                                }
                                            }
                                            ,change : function(){
                                                var val = Ext.getCmp('salesGLAcc' + module).getValue()
                                                Ext.getCmp('salesGLAccountName' + module).setValue(val)
                                            }
                                        }
                                    } )
                                    ,standards.callFunction( '_createCombo', {
                                        id              : 'salesGLAccountName' + module
                                        ,fieldLabel     : ''
                                        ,allowBlank     : true
                                        ,width          : 150
                                        ,store          : coaStore
                                        ,displayField   : 'name'
                                        ,valueField     : 'id'
                                        ,style          : 'margin-left: 5px;'
                                        ,listeners      : {
                                            beforeQuery : function(){
                                                var selected = Ext.getCmp('gridAffiliate' + module).getSelectionModel().getSelection()
                                                if(selected.length > 0){
                                                    var ids = selected.map(affiliate => parseInt(affiliate.data.idAffiliate))
                                                    coaStore.proxy.extraParams.affiliates = JSON.stringify(ids)
                                                    coaStore.load({});
                                                }
                                            }
                                            ,change : function(){
                                                var val = Ext.getCmp('salesGLAccountName' + module).getValue()
                                                Ext.getCmp('salesGLAcc' + module).setValue(val)
                                            }
                                        }
                                    } )
                                ]
                            }
                        ]
                    }
                    ,{
                        xtype           : 'container'
                        ,columnWidth    : .4
                        ,style          : 'margin-bottom: 10px;'
                        ,items: [
                            
                        ]
                    }
                ]  
            }
        }

        function __customerItem(){

            var store = standards.callFunction(  '_createRemoteStore' ,{
                fields:[
                    {	name	: 'idItem'
                        ,type	: 'number'
                    }
                    ,'barcode'
                    ,'itemName'
                    ,'className'
                ]
                ,url: route + 'getCustomerItem'
            });
            var itemStore = standards.callFunction( '_createRemoteStore', {
				fields      : [ 'idItem', 'barcode', 'itemName','className' ]			
				,url        : route + 'getItems'
            });
            var contactStore = standards.callFunction( '_createRemoteStore', {
				fields      : [ 'contactPersonName', 'department','main', 'contactNumber' ]			
				,url        : route + 'getContacts'
            });

            var checkListeners = {
				checkchange: function( me, rowIndex, checked ){
                    if( checked ) {
                        var cmp = Ext.getCmp('gridContact' + module)
                        ,store = cmp.getStore().data.items;

                        store.map( (col, i) => {
                            selectedContact = cmp.getStore().getAt(i)
                            if( i !== rowIndex ) selectedContact.set('main', 0);
                        });
                    }
				}
            };

            function _deleteItem( data ){
                deletedItems.push( data );
                selectedItem.splice( selectedItem.indexOf(data.idItem), 1);
                store.remove(store.findRecord('idItem', data.idItem));
            }
    
            return {
                xtype : 'tabpanel'
                ,items: [
                    {
                        title: 'Item(s)'
                        ,layout:{
                            type: 'card'
                        }
                        ,items  :   [
                            standards.callFunction( '_gridPanel',{
                                id		        : 'gridItem' + module
                                ,module	        : module
                                ,store	        : store
                                ,noDefaultRow   : true
                                ,noPage         : true
                                ,plugins        : true
                                ,tbar : {
                                    canPrint        : false
                                    ,noExcel        : true
                                    ,route          : route
                                    ,pageTitle      : pageTitle
                                    ,content        : 'add'
                                    ,deleteRowFunc  : _deleteItem
                                }
                                ,columns: [
                                    {	header          : 'Code'
                                        ,dataIndex      : 'barcode'
                                        ,width          : 150
                                        ,columnWidth    : 30
                                        ,editor         : standards.callFunction( '_createCombo', {
                                            id              : 'custitemcode' + module
                                            ,allowBlank     : true
                                            ,store          : itemStore
                                            ,width          : 150
                                            ,displayField   : 'barcode'
                                            ,valueField     : 'barcode'
                                            ,listeners      : {
                                                beforeQuery : function(){
                                                    var selected = Ext.getCmp('gridAffiliate' + module).getSelectionModel().getSelection();

                                                    if(selected.length > 0){
                                                        var idAffiliates = selected.map(affiliate => parseInt(affiliate.data.idAffiliate))
                                                        itemStore.proxy.extraParams.affiliates = JSON.stringify(idAffiliates)
                                                        itemStore.load({})
                                                    } else {
                                                        standards.callFunction('_createMessageBox', { msg : 'You must select an affiliate first before choosing an item.'});
                                                    }
                                                }
                                                ,select : function( me, record ){
                                                    if( Ext.isUnique(me.valueField, store, this) ) {
                                                        var row = this.findRecord(this.valueField, this.getValue())
                                                        var { 0 : selStore }   = Ext.getCmp('gridItem' + module).selModel.getSelection();

                                                        Ext.setGridData(['idItem', 'barcode', 'itemName', 'className'], selStore, row)
                                                        selectedItem.push( parseInt( row.data.idItem,10) );
                                                    }
                                                }
                                            }
                                        })
                                        
                                    }
                                    ,{	header : 'Item Name'
                                        ,dataIndex : 'itemName'
                                        ,width : 250
                                        ,flex : 1
                                        ,columnWidth : 30
                                        ,editor : standards.callFunction( '_createCombo', {
                                            id              : 'custitemname' + module
                                            ,allowBlank     : true
                                            ,store          : itemStore
                                            ,width          : 150
                                            ,displayField   : 'itemName'
                                            ,valueField     : 'itemName'
                                            ,listeners      : {
                                                beforeQuery : function(){
                                                    var selected = Ext.getCmp('gridAffiliate' + module).getSelectionModel().getSelection();
                                                    if(selected.length > 0){
                                                        var idAffiliates = selected.map(affiliate => parseInt(affiliate.data.idAffiliate))
                                                        itemStore.proxy.extraParams.affiliates = JSON.stringify(idAffiliates)
                                                        itemStore.load({})
                                                    } else {
                                                        standards.callFunction('_createMessageBox', { msg : 'You must select an affiliate first before choosing an item.'});
                                                    }
                                                }
                                                ,select : function(me){
                                                    if( Ext.isUnique(me.valueField, store, this) ) {
                                                        var row = this.findRecord(this.valueField, this.getValue())
                                                        var { 0 : selStore }   = Ext.getCmp('gridItem' + module).selModel.getSelection();

                                                        Ext.setGridData(['idItem', 'barcode', 'itemName', 'className'], selStore, row)
                                                        selectedItem.push( parseInt( row.data.idItem,10) );
                                                    }
                                                }
                                            }
                                        })
                                    }
                                    ,{	header : 'Classification'
                                        ,dataIndex : 'className'
                                        ,width : 250
                                        ,columnWidth : 30
                                    }
                                ]
                                ,listeners	: {
                                    afterrender : function() {
                                        store.load({});
                                    }
                                }
                            })
                        ]
                    }
                    ,{
                        title: 'Contact Person(s)'
                        ,layout:{
                            type: 'card'
                        }
                        ,items  :   [
                            standards.callFunction( '_gridPanel',{
                                id		        : 'gridContact' + module
                                ,module	        : module
                                ,store	        : contactStore
                                ,noDefaultRow   : true
                                ,noPage         : true
                                ,plugins        : true
                                ,tbar : {
                                    canPrint        : false
                                    ,noExcel        : true
                                    ,route          : route
                                    ,pageTitle      : pageTitle
                                    ,content        : 'add'
                                }
                                ,columns: [
                                    {	header          : 'Contact Name'
                                        ,dataIndex      : 'contactPersonName'
                                        ,minWidth       : 250
                                        ,flex           : 1
                                        ,sortable       : false
                                        ,editor         : 'text'
                                    }
                                    ,{	header          : 'Department'
                                        ,dataIndex      : 'department'
                                        ,width          : 300
                                        ,sortable       : false
                                        ,editor         : 'text'
                                    }
                                    ,{	header          : 'Contact Number'
                                        ,dataIndex      : 'contactNumber'
                                        ,width          : 300
                                        ,sortable       : false
                                        ,editor         : 'text'
                                        ,maskRe         : /[^\/?!.,><;:'"|}{_*&%$@~`=,^a-z,A-Z]/
                                    }
                                    ,{
                                        header          : 'Main'
                                        ,dataIndex      : 'main'
                                        ,xtype          : 'checkcolumn'
                                        ,sortable       : false
                                        ,width          : 55
                                        ,listeners      : checkListeners
                                    }
                                ]
                                ,listeners	: {
                                    afterrender : function() {
                                        store.proxy.extraParams = {};
                                        store.load({});
                                    }
                                }
                            })
                        ]
                    }
                ]
            }
        }

        function _saveForm( form ){

            var selectedAffiliates  = _getGridItems('gridAffiliate', 'check')
                ,selectedItems      = _getGridItems('gridItem', 'data' )
                ,selContacts        = _getGridItems('gridContact', 'data' ); 

            switch( true ){
                case selectedAffiliates.length <= 0 :
                    standards.callFunction('_createMessageBox', { msg : 'Invalid action. Please select atleast one affiliate.'});
                    return false;
                // case selectedItems.length <= 0 :
                //     standards.callFunction('_createMessageBox', { msg : 'Please select atleast one item.'});
                //     return false;
                case selContacts.length > 0 :
                    /* Checks if Customer Contacts has a main tag. */
                    var checkMain = selContacts.some( contact => contact.main === true || contact.main == 1 );
                    if( !checkMain ) {
                        standards.callFunction('_createMessageBox', { msg : 'Please select a main contact person.'});
                        return false;
                    } 
                    break;
            }

            form.submit({ 
                url : route + 'saveForm'
                ,params : {
                    affiliates  : Ext.encode( selectedAffiliates )
                    ,items      : Ext.encode( selectedItems )
                    ,contacts   : Ext.encode( selContacts )
                }
                ,success : function( me, response ){
                    var resp = Ext.decode( response.response.responseText )
                        ,result = {
                            action  : ( resp.match == 0 ) ? '' : 'confirm'
                            ,msg    : ( resp.match == 0 ) ? 'SAVE_SUCCESS' : 'SAVE_FAILURE'
                        }

                    standards.callFunction( '_createMessageBox', {
                        msg	: result.msg
                        ,action : result.action
                        ,fn	: function(){
                            _resetForm( form );
                        }
                    } )
                }
            });
        }
        
        function _resetForm( form ){
            form.reset();
            deletedItems = [];
            let grds = ['gridAffiliate', 'gridItem', 'gridContact']
            ,cmbs = ['custitemcode', 'custitemname', 'discountGLAcc', 'salesGLAcc', 'discountGLAccountName', 'salesGLAccountName'];

            grds.map( item => Ext.resetGrid( item + module) );
            cmbs.map( item => _resetCombo( item ) );
        }

        function _resetCombo( id ){
            var cmp = Ext.getCmp( id + module );
            cmp.getStore().proxy.extraParams = {};
            cmp.getStore().load({});
        }

        function gridHistory(){
            var customerStore = standards.callFunction( '_createRemoteStore' , {
                fields:[ 'idCustomer', 'name', 'tin', 'address', 'contactNumber' ]
                ,url: route + 'getCustomers'
            });

            function _editRecord( data ) {
                module.getForm().retrieveData({
                    url : route + 'retrieveData'
                    ,params : {
                        idCustomer : data.idCustomer
                    }
                    ,success : function( response ) {


                        if( response.idCustAffiliates != null ) {
                            var affiliateStore = Ext.getCmp( 'gridAffiliate' + module ).store;
                            var affiliates = response.idCustAffiliates.split(",", response.idCustAffiliates.length );
                            
                            affiliateStore.load({
                                params	: {
                                    idAffiliates : Ext.encode( affiliates )
                                }
                                ,callback	: function(){
                                    Ext.getCmp( 'gridAffiliate' + module ).getView().refresh();
                                }
                            });
                        }

                        var grdItem = Ext.getCmp( 'gridItem' + module).store;
                        grdItem.proxy.extraParams.idCustomer = data.idCustomer;
                        grdItem.load({
                            callback: function() {
                                if( this.data.items.length > 0 ){
                                    this.data.items.map((col, i) => {
                                        selectedItem.push( col.data.idItem  )
                                    });
                                }
                            }
                        });

                        Ext.getCmp('salesGLAcc' + module ).store.load({
                            callback: function() {
                                Ext.getCmp('salesGLAcc' + module ).setValue( parseInt( response.salesGLAcc , 10) )
                            }
                        });

                        var grdContact = Ext.getCmp('gridContact' + module);
                        grdContact.getStore().proxy.extraParams.idCustomer = parseInt( response.idCustomer, 10 );
                        grdContact.getStore().load({
                            callback: function(){
                                if( this.data.items.length > 0 ){
                                    // console.log( this.data.items );
                                    this.data.items.map( (col, i ) => {
                                        // console.log( col.data.main );
                                        if( col.data.main != 1 ) this.getAt(i).set('main', 0 );
                                    })
                                }
                            }
                        });
                    }
                });
            }

            function _deleteCustomer( data ){
                standards.callFunction( '_createMessageBox', {
					msg	    : 'DELETE_CONFIRM'
					,action : 'confirm'
					,fn	    : function( btn ){
						if( btn == 'yes' ) {
							Ext.Ajax.request({
								url	    : route + 'deleteCustomer'
								,params	: {
									idCustomer : data.idCustomer
								}
								,success : function( response ) {
									var resp = Ext.decode( response.responseText );

									standards.callFunction( '_createMessageBox', {
										msg	: ( resp.view == 1 ) ? 'DELETE_USED' : 'DELETE_SUCCESS'
										,fn	: function(){
											Ext.getCmp( 'gridHistory' + module ).store.load({});
										}
									} );
									
								}
							});
						}
					}
				} );
            }
            
            return standards.callFunction('_gridPanel', {
                id          : 'gridHistory' + module
                ,module     : module
                ,store      : customerStore
                ,height     : 265
                ,columns: [
                    {	header      : 'Customer Code'
                        ,dataIndex  : 'idCustomer'
                        ,width      : 180
                        ,sortable   : true
                    }
                    ,{	header      : 'Customer Name'
                        ,dataIndex  : 'name'
                        ,flex       : 1
                        ,minWidth   : 80
                        ,sortable   : true
                    }
                    ,{	header      : 'TIN'
                        ,dataIndex  : 'tin'
                        ,width      : 120
                        ,sortable   : true
                    }
                    ,{	header      : 'Address'
                        ,dataIndex  : 'address'
                        ,flex       : 1
                        ,minWidth   : 80
                        ,sortable   : true
                    }
                    ,{	header      : 'Contact Number'
                        ,dataIndex  : 'contactNumber'
                        ,width      : 180
                        ,sortable   : true
                    }
                    ,standards.callFunction( '_createActionColumn', {
                        canEdit     : canEdit
                        ,icon       : 'pencil'
						,tooltip    : 'Edit'
                        ,width      : 30
                        ,Func       : _editRecord
                    })
                    ,standards.callFunction( '_createActionColumn', {
                        canEdit     : canEdit
                        ,icon       : 'remove'
						,tooltip    : 'Delete'
						,width      : 30
                        ,Func       : _deleteCustomer
                    })
                ]
                ,listeners: {
                    afterrender: function(){
                        customerStore.load({})
                    }
                }
            })
        }

        function _getGridItems( id, type ){
            let items = Ext.getCmp(id+module).store.data.items.map((item)=>item.data);
            return ( type === 'check' ) ?  items.filter((item)=>item.chk == true ) : items;
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
				
				return _mainPanel( config );
			}
		}
    }
}