function Inventoryconversion(){
    return function(){
        var baseurl, route, module, canDelete, pageTitle, idModule, isGae,isSaved = 0,deletedItems = [],selectedItem = [], idAffiliate, dataHolder, selRec, componentCalling, canCancel;

        function _init(){
            if ( selRec ) {
                _edit( { data:selRec , id:selRec.idInvoice } );
            }
        }

        function _mainPanel(config){
            var convertedItems = standards.callFunction(  '_createRemoteStore' ,{
                fields:[
                    {	name	: 'id'
                        ,type	: 'number'
                    }
                    ,'category'
                    ,'barcode'
                    ,'name'
                    ,'unit'
                    ,'qty'
                    ,'price'
                    ,'cost'
                    ,'expirydate'
                    ,'idItem'
                    ,'total'
                ]
                ,url: route + 'getConvertedItems'
            });
            var bulkItems = standards.callFunction(  '_createRemoteStore' ,{
                fields:[
                    {	name	: 'id'
                        ,type	: 'number'
                    }
                    ,'category'
                    ,'barcode'
                    ,'name'
                    ,'unit'
                    ,'qty'
                    ,'convertqty'
                    ,'cost'
                    ,'expirydate'
                    ,'idItem'
                    ,'total'
                    ,'remaining'
                ]
                ,url: route + 'getBulkItems'
            });
            return standards2.callFunction(	'_mainPanelTransactions' ,{
                config		            : config
                ,module		            : module
                ,transactionHandler     : _transactionHandler
                ,moduleType	            : 'form'
                ,orientation            : 'L'
                ,hasApproved            : false
                ,hasCancelTransaction	: canCancel
                ,listeners  : {
                    afterrender : _init
                }
				,tbar       : {
					saveFunc        : _saveForm
					,resetFunc      : _resetForm
					,formPDFHandler : _printPDF
					,hasFormPDF     : true
					,filter             : {
						searchURL       : route + 'searchHistoryGrid'
						,emptyText      : 'Search reference here...'
						,module         : module
					}
                }
                ,formItems  :[
                    {	
                        xtype		: 'container'
                        ,layout		: 'column'
                        ,width		: 1000
                        ,padding	: 10
                        ,items		: [
                            {
                                xtype			: 'container'
								,columnWidth	: .5
								,items			: [
                                    standards.callFunction( '_createTextField', {
                                        id          : 'idInvoice' + module
                                        ,allowBlank : true
										,value 		: 0
										,hidden		: true
                                        
                                    } )
                                    ,standards2.callFunction( '_createCostCenter', {
                                        module          : module
                                        ,idAffiliate    : Ext.getConstant('AFFILIATEID')
                                        ,allowBlank     : true
                                        ,listeners      : {
                                            afterrender: function( me ){
                                                let value = ( Ext.getConstant('AFFILIATEREFTAG') == 1 ) ? false : true;
                                                me.allowBlank = value;

                                                if( value ) 
                                                    me.labelEl.update( me.fieldLabel + ':') 
                                                else
                                                    me.labelEl.update( me.fieldLabel + Ext.getConstant('REQ') + ':')
                                            }
                                        }
                                    } )  
                                    ,standards2.callFunction( '_transactionReference', {
                                        module		: module
                                        ,idModule       : idModule
                                        ,idAffiliate    : idAffiliate
                                        ,style          : 'margin-bottom:5px;'
                                    } )
                                ]
                            }
                            ,{
                                xtype			: 'container'
								,columnWidth	: .5
								,items			: [
                                    standards.callFunction( '_createDateTime', {
                                        dId			: 'tdate'+module
                                        ,tId			: 'ttime'+module
                                        ,dFieldLabel	: 'Date'
                                        ,tstyle : 'margin-left: 5px;'
                                        ,tWidth : 105
                                        ,dlistener:{
                                            afterrender	: function(){
												standards2.callFunction( '_checkIf_journal_isClosed', { 
													idAffiliate	: idAffiliate
													, tdate		: this.value
													, module	: module 
												} )
											}

											,select : function(){
												standards2.callFunction( '_checkIf_journal_isClosed', { 
													idAffiliate	: idAffiliate
													, tdate		: this.value
													, module	: module 
												} )
												/* Reset reference and reference num fields to re-validate 
												the references made on or before the selected date. */
			
												var reference = Ext.getCmp('idReference' + module)
												,referenceNum = Ext.getCmp('referenceNum' + module);
			
												reference.reset();
												referenceNum.reset();
											}
                                        }
                                    })
                                    ,standards.callFunction( '_createTextArea', {
                                        id			: 'remarks' + module
                                        ,fieldLabel	: 'Remarks'
                                        ,allowBlank	: true
                                    } )
                                ]
                            }
                        ]
                    }
                    ,{
                        xtype		: 'container'
						,items		: [
                            {
                                xtype			: 'fieldset'
                                ,title          : 'Item to be Converted'
								,columnWidth	: 1
                                ,items			: [
                                    {
                                        xtype		: 'container'
                                        ,items      :[
                                            standards.callFunction( '_gridPanel',{
                                                id		        : 'gridItem' + module
                                                ,module	        : module
                                                ,height         : 150
                                                ,style          : 'margin-bottom:10px;'
                                                ,store	        : bulkItems
                                                ,noDefaultRow   : true
                                                ,noPage         : true
                                                ,plugins        : true
                                                ,tbar           : {
                                                    canPrint        : false
                                                    ,noExcel        : true
                                                    ,route          : route
                                                    ,pageTitle      : pageTitle
                                                    ,extraTbar2:[
                                                        __searchbar('bulkitem', 'Items Details')
                                                        ,__qty('bulkitemqty')
                                                    ]
                                                    
                                                }
                                                ,columns: ___itemsInput()
                                                ,listeners :{
                                                    edit : function( me, rowData ) {
                                                        var index = rowData.rowIdx
                                                        ,store = this.getStore().getRange();
                                                        var { qty ,remaining,cost } = rowData.record.data
                                                        remaining = parseFloat(remaining)
                                                        cost = parseFloat(cost)
                                                        qty = parseFloat(qty)
                                                        if(qty > remaining){
                                                            standards.callFunction('_createMessageBox',{ msg: 'You excced the quantity remaining.' })
                                                            store[index].set('qty', remaining )
                                                        }
                                                        store[index].set('total', isNaN(qty)? 0 : qty * cost )
                                                        __setTotal()
                                                    }
                                                }
                                            })
                                        ]
                                    }
                                ]
                            }
                            ,{
                                xtype			: 'fieldset'
                                ,title          : 'Conversion Output'
								,columnWidth	: 1
                                ,items			: [
                                    {
                                        xtype		: 'container'
                                        ,items      :[
                                            standards.callFunction( '_gridPanel',{
                                                id		        : 'outputGrid' + module
                                                ,module	        : module
                                                ,store	        : convertedItems
                                                ,noDefaultRow   : true
                                                ,noPage         : true
                                                ,plugins        : true
                                                ,style          : 'margin-bottom:10px;'
                                                ,tbar           : {
                                                    canPrint        : false
                                                    ,noExcel        : true
                                                    ,route          : route
                                                    ,pageTitle      : pageTitle
                                                    ,content        : 'add'
                                                    ,deleteRowFunc  : _deleteItem
                                                    ,extraTbar2:[
                                                        __searchbar('items')
                                                        ,__qty('itemqty')
                                                    ]
                                                }
                                                ,columns: ___itemsOutput()
                                                ,listeners :{
                                                    edit : function( me, rowData ) {
                                                        
                                                        var index = rowData.rowIdx
                                                        ,store = this.getStore().getRange();
                                                        if(rowData.record.data.barcode == ""){
                                                            
                                                            store[index].set(rowData.field, null)
                                                            standards.callFunction('_createMessageBox',{ msg: 'No item selected, please provide an item first.' })
                                                        }else{
                                                            var { qty ,cost } = rowData.record.data
                                                            cost = parseFloat(cost)
                                                            qty = parseFloat(qty)
                                                            store[index].set('total', isNaN(qty)? 0 : qty * cost )
                                                        }
                                                        __setTotal()
                                                    }
                                                }
                                            })
                                        ]
                                    }
                                    ,standards.callFunction('_createNumberField',{
                                        id : 'totalamt' + module
                                        ,style : 'float:right;padding:8px;margin-top:5px;margin-right:5px;'
                                        ,fieldLabel : 'Total Amount'
                                        ,readOnly : true
                                    })
                                ]
                            }
                            
                           
                        ]
                    }
                    ,{
                        xtype			: 'container'
                        ,columnWidth	: 1
                        ,items			: [
                            {
                                xtype : 'tabpanel'
                                ,items: [
                                    {
                                        title: 'Journal Entries'
                                        ,layout:{
                                            type: 'card'
                                        }
                                        ,items  :   [
                                            standards.callFunction( '_gridJournalEntry',{
                                                module	        : module
                                                ,hasPrintOption : 1
                                                ,config         : config
                                                ,itemsToConvert : Ext.getCmp('gridItem' + module)
								                ,outputItems    : Ext.getCmp('outputGrid' + module)
                                            })
                                        ]
                                    }
                                ]

                            }
                            
                        ]
                    }
                ]
                ,listItems: gridHistory()
            })
        }

        function _saveForm(form){
            var items = Ext.getCmp('gridItem'+module).store.data.items.map((item)=>item.data).filter((item)=>item.barcode !== "")
            var outitem = Ext.getCmp('outputGrid'+module).store.data.items.map((item)=>{
                if(item.data.expirydate != '' && item.data.expirydate != null) item.data.expirydate = Ext.dateParse(item.data.expirydate).isValid()? Ext.dateParse(item.data.expirydate).format('YYYY-MM-DD'): null
                return item.data
            }).filter((item)=>item.barcode !== "")

			if(items.length == 0){
				standards.callFunction('_createMessageBox',{ msg: 'No item selected, add at least one item first.' })
				return false;
            }
            if(outitem.length == 0){
				standards.callFunction('_createMessageBox',{ msg: 'No converted item, add at least one item first.' })
				return false;
            }

            if(!outitem.map(item=>Number(item.qty) > 0).reduce((a,b) => a && b, true)){
                standards.callFunction('_createMessageBox',{ msg: 'Please add qty to convert.' })
				return false;
            }

            if(!outitem.map(item=>Number(item.cost) > 0).reduce((a,b) => a && b, true)){
                standards.callFunction('_createMessageBox',{ msg: 'Please select cost.' })
				return false;
            }

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
            if( totalCR != totalDR ){
                standards.callFunction( '_createMessageBox', {
                    msg     : 'Invalid transaction details. Make sure that total Debit and total Credit is balance.'
                    ,icon   : Ext.MessageBox.ERROR
                } );
                return false;
            }

            form.submit({
                waitTitle	: "Please wait"
                ,waitMsg	: "Submitting data..."
                ,url		: route + 'save'
                ,params		: {
                    grid :Ext.encode(
                        {
                            items       : Ext.encode( items ),
                            outitem     : Ext.encode( outitem ),
                            journals   : Ext.encode( jeRecords )
                        }
                    )
                }

                ,success:function( action, response ){
                    var resp    = Ext.decode( response.response.responseText )
                        ,match  = parseInt( resp.match, 10 );
                    switch( match ){
                        case 1: /* reference number already exists */
                            standards.callFunction( '_createMessageBox', {
                                msg     : 'Reference number already exists. System will generate new reference number.'
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
                            standards.callFunction( '_createMessageBox', {
                                msg : 'Record has already been ' + resp.curStatus + ' by other user.'
                                ,fn : function(){
                                    _resetForm( form );
                                }
                            } )
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
            })
        }

        function _resetForm(form){

            deletedItems = [];
			form.reset();
            Ext.resetGrid('gridItem'+module)
            Ext.resetGrid('outputGrid'+module)
            Ext.resetGrid('gridJournalEntry'+module)
			Ext.getCmp('tdate'+module).fireEvent( 'afterrender' );
            
            // Ext.getCmp('approveTransButton' + module).setVisible( false );
            // Ext.getCmp('cancelTransButton' + module).setVisible( false );
			// document.getElementById( 'transactionStatus' + module ).innerHTML = '<span style="color:red; font-weight: bold;">Not Yet Confirmed</span>';
        

        }

        /* This is for Form PDF Printing. Added by: Hazel */
		function _printPDF(){
			var par     = standards.callFunction('getFormDetailsAsObject',{ module : module })
            ,itemGrid   = Ext.getCmp('gridItem'+module).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0)
            ,outputGrd  = Ext.getCmp('outputGrid'+module).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0);

			Ext.Ajax.request({
                url			: route + 'printPDF'
                ,method		:'post'
                ,params		: {
                    moduleID    	: 7
                    ,title  		: pageTitle
                    ,limit      	: 50
                    ,start      	: 0
					,printPDF   	: 1
					,form			: Ext.encode( par )
                    ,items			: Ext.encode( itemGrid )
                    ,outputGrd      : Ext.encode( outputGrd )
                    ,idInvoice		: dataHolder.idInvoice
                    ,idAffiliate	: dataHolder.idAffiliate
                }
                ,success	: function(response, action){
                    if( isGae == 1 ){
						window.open(route+'viewPDF/' + pageTitle ,'_blank');
					}else{
						window.open('pdf/inventory/'+ pageTitle +'.pdf');
					}
                }
			});
		}

        function _transactionHandler( status ){
			standards.callFunction( '_createMessageBox', {
				msg		: `Are you sure you want to ${status === 2 ? 'approve': 'cancel'} this transaction?`
				,action	: 'confirm'
				,fn		: function( btn ){
					if( btn == 'yes' ){
                        Ext.Ajax.request({
                            url : route + 'updateTransaction'
                            ,params : {
                                status 		: status
                                ,idInvoice 	: Ext.getCmp('idInvoice'+module).value
                            }
                            ,success : function( response ){
        
                                if( Ext.decode( response.responseText ).match == 1 ) {
                                    standards.callFunction('_createMessageBox',{ msg: 'EDIT_USED' });
                                } else {
                                    standards.callFunction('_createMessageBox',{ 
                                        msg: `Transaction has been ${status === 2  ? 'approved' : 'cancelled'}` 
                                        ,fn : function() {
                                            standards2.callFunction('_setTransaction', { module	: module ,data : { status : status }});
                                        }
                                    });
                                }
                            }
                        });
					}
				}
			} );
        }
        

        function _deleteItem(data, inx){
            deletedItems.push( data );
            selectedItem.splice( inx, 1);
            Ext.getCmp( 'outputGrid' + module).store.removeAt(inx);
        }

        function __searchbar(name, fieldname, item, from, hide){
            from = typeof from === 'undefined'? 'itemName' : from
            hide = typeof hide === 'undefined'? true : hide
            var items = standards.callFunction( '_createRemoteStore', {
				fields:[
                    {	name	: 'id'
                        ,type	: 'number'
                    }
                    ,'barcode'
					,'name'
					,'class'
					,'unit'
					,'cost'
                    ,'qty'
                    ,'price'
                    ,'remaining'
                    ,'total'
                    ,'expirydate'
                    ,'idItem'
                ]
                ,url        : route + 'getItems'
                ,listeners: {
                    load: function(store) {
                        // using a map of already used names
                        const hits = {}
                        store.filterBy(record => {
                            const name = record.get('name')
                            if (hits[name]) {
                                return false
                            } else {
                                hits[name] = true
                                return true
                            }
                        })
            
                        // delete the filtered out records
                        delete store.snapshot
                    },
                }
            });

            return standards.callFunction( '_createCombo', {
                id				: name + module
                ,store          : items
                ,module			: module
                ,fieldLabel		: fieldname || ''
                ,allowBlank		: true
                ,width			: name === 'items'? 195 : 300
                ,displayField   : item || 'name'
                ,valueField     : item || 'name'
                ,emptyText		: `Search ${item || 'name'}...`
                ,style          : typeof fieldname === 'undefined' || fieldname === ''? (name === 'items')? 'margin-left:56px' : '' :'margin-left:5px'
                ,hideTrigger	: hide
                ,labelWidth     : 100
                ,listeners		: {
                    beforeQuery	: function(){
                        this.store.proxy.extraParams = {
                            'idAffiliate' : idAffiliate,
                            'qty' : name == 'bulkitem'? Ext.getCmp('bulkitemqty'+module).getValue() : Ext.getCmp('itemqty'+module).getValue(), 
                            'from': from,
                            'has_receiving' : name == 'bulkitem'? 'yes' : 'no',
                            'idInvoice': Ext.getCmp('idInvoice'+module).value,
                            'transaction_date' : `${Ext.dateParse(Ext.getCmp('tdate'+module).value).format('YYYY-MM-DD')} ${Ext.dateParse(Ext.getCmp('ttime'+module).value).format('HH:mm:00')}`
                        }

                        if(name != 'bulkitem'){
                            if(Ext.getCmp('gridItem'+module).store.data.items.length == 0){
                                standards.callFunction('_createMessageBox',{ msg: 'Please select item to be converted first.' })
                                return false;
                            }else{
                                this.store.proxy.extraParams['name'] = Ext.getCmp('gridItem'+module).store.data.items[0].data.barcode
                            }
                        }
                    },
                    select : function(){
                        var row = this.findRecord(this.valueField, this.getValue())
                        if(name == 'bulkitem'){
                            var {store} = Ext.getCmp('gridItem'+module)
                            var {data: {items: list}} = store
                            
                            if(!list.length){
                                store.add(row)
                            }else{
                                var  {0 : store} = Ext.getCmp('gridItem' + module).store.data.items
                                Ext.setGridData(['id', 'category', 'barcode', 'name', 'unit', 'qty', 'cost', 'remaining', 'cost','idItem'],store, row)
                            }
                            var  {0 : store} = Ext.getCmp('gridItem' + module).store.data.items
                            var { qty ,remaining,cost } = row.data
                            remaining = parseFloat(remaining)
                            cost = parseFloat(cost)
                            qty = parseFloat(qty)
                            if(qty > remaining){
                                standards.callFunction('_createMessageBox',{ msg: 'You excced the quantity remaining.' })
                                store.set('qty', remaining )
                            }
                            store.set('total', isNaN(qty)? 0 : qty * cost )
                            
                            Ext.getCmp('bulkitemqty'+module).setValue(1)
                            Ext.getCmp('itemqty'+module).setValue(1)
                            Ext.getCmp('outputGrid'+module).store.removeAll()
                            Ext.getCmp(name + module).setValue(null)
                        }else if(name === 'items'){
                           
                            var {store} = Ext.getCmp('outputGrid'+module)
                            store.add(row)
                            Ext.getCmp('bulkitemqty'+module).setValue(1)
                            Ext.getCmp('itemqty'+module).setValue(1)
                            Ext.getCmp(name + module).setValue(null)
                            var items = Ext.getCmp('outputGrid' + module).store.data.items
                            var  {[items.length - 1] : store} = items
                            var { qty ,remaining,cost } = row.data
                            cost = parseFloat(cost)
                            qty = parseFloat(qty)
                            
                            store.set('total', isNaN(qty)? 0 : qty * cost )
                        }else{
                            var  {0 : store} = Ext.getCmp('outputGrid' + module).selModel.getSelection()
                            Ext.setGridData(['category', 'barcode', 'name', 'unit', 'qty', 'convertqty', 'cost','cost', 'remaining','idItem'],store, row)
                            store.set('cost', 0)
                            Ext.getCmp(name + module).setValue(this.getValue())
                        }

                        __setTotal()
                    }
                    ,focus: function(){
                        if( name !== 'bulkitem' && name !== 'items'){
                            var  {0 : selected} = Ext.getCmp('outputGrid' + module).getSelectionModel().getSelection();
                            this.store.proxy.extraParams = {
                                'idAffiliate' : idAffiliate,
                                'qty' : name == 'bulkitem'? Ext.getCmp('bulkitemqty'+module).getValue() : Ext.getCmp('itemqty'+module).getValue(), 
                                'from': from,
                                'has_receiving' : name == 'bulkitem'? 'yes' : 'no',
                                'idInvoice': Ext.getCmp('idInvoice'+module).value,
                                'transaction_date' : `${Ext.dateParse(Ext.getCmp('tdate'+module).value).format('YYYY-MM-DD')} ${Ext.dateParse(Ext.getCmp('ttime'+module).value).format('HH:mm:00')}`
                            }
                            this.store.load({
                                callback:()=>{
                                    this.setValue(selected.data[this.displayField])
                                }
                            })
                            
                        }
                        
                    }
                }
            })

        }

        function __qty(name){
            return standards.callFunction('_createNumberField',{
                id			: name + module
                ,module		: module
                ,fieldLabel	: ''
                ,allowBlank	: true
                ,width		: 75
                ,value		: 1
            })
        }

        function ___itemsOutput(){
            
            return [
                {	
                    header          : 'Code'
                    ,dataIndex      : 'barcode'
                    ,width          : 150
                    ,editor         : __searchbar('codeitem', '','barcode','barcode',false)
                }
                ,{	
                    header          : 'Item Name'
                    ,dataIndex      : 'name'
                    ,width          : 225
                    ,editor         : __searchbar('nameitem', '','name', 'itemName', false)
                }
                ,{	
                    header          : 'Unit of Measurement'
                    ,dataIndex      : 'unit'
                    ,width          : 150
                },{	
                    xtype           : 'date'
                    ,format         : 'Y-m-d'
                    ,header          : 'Expiry Date'
                    ,dataIndex      : 'expirydate'
                    ,width          : 125
                    ,editor         : 'date'
                    ,renderer       : function(val){
                        return val !== null && val !== "" && val !== undefined && Ext.dateParse(val).isValid()?  Ext.dateParse(val).format('YYYY-MM-DD') : null
                    }
                },{	
                    header          : 'Qty'
                    ,dataIndex      : 'qty'
                    ,width          : 105
                    ,editor         : 'number'
                    ,renderer		: function(val,full){
                        let valued = val === undefined || isNaN(val)? 0 : val
                        return __number(valued, 0)
                    }
                },{	
                    header          : 'Cost'
                    ,dataIndex      : 'cost'
                    ,width          : 150
                    ,editor         : 'number'
                    ,renderer		: function(val){
                        let valued = val === undefined || isNaN(val)? 0 : val
                        return __number(valued, 2)
                    }
                },{	
                    header          : 'Amount'
                    ,dataIndex      : 'total'
                    ,width          : 150
                    ,renderer		: function(val){
                        let valued = val === undefined || isNaN(val)? 0 : val
                        return __number(valued, 2)
                    }
                }
            ]
        }
        
        function __setTotal(){
            let {data:{items}} = Ext.getCmp( 'outputGrid' + module).store
            let list = items.map(item => item.data.total)
            let overall = list.reduce((first, current)=> first+current,0)
            Ext.getCmp('totalamt' + module).setValue(overall)
        }

        function ___itemsInput(){
            var cost = standards.callFunction( '_createRemoteStore', {
				fields:[
                    {	name	: 'qtyLeft'
                        ,type	: 'number'
                    }
                    ,'cost'
                ]
				,url        : route + 'getItemsCost'
            });
            return [
                {	
                    header          : 'Code'
                    ,dataIndex      : 'barcode'
                    ,width      : 120
                }
                ,{	
                    header          : 'Item Name'
                    ,dataIndex      : 'name'
                    ,width      : 225
                }
                ,{	
                    header          : 'Unit of Measurement'
                    ,dataIndex      : 'unit'
                    ,width      : 150
                }
                ,{	
                    header          : 'Available Qty'
                    ,dataIndex      : 'remaining'
                    ,width      : 150
                    ,renderer		: function(val){
                        let valued = val === undefined || isNaN(val)? 0 : val
                        return __number(valued, 0)
                    }
                }
                ,{	
                    header          : 'Qty to be Converted'
                    ,dataIndex      : 'qty'
                    ,width      : 150
                    ,editor         : 'number'
                    ,renderer		: function(val){
                        let valued = val === undefined || isNaN(val)? 0 : val
                        return __number(valued, 0)
                    }
                }
                ,{	
                    header          : 'Cost'
                    ,dataIndex      : 'cost'
                    ,width      : 150
                    ,renderer		: function(val){
                        let valued = val === undefined || isNaN(val)? 0 : val
                        return __number(valued, 2)
                    }
                }
                ,{	
                    header          : 'Total'
                    ,dataIndex      : 'total'
                    ,width      : 150
                    ,renderer		: function(val){
                        let valued = val === undefined || isNaN(val)? 0 : val
                        return __number(valued, 2)
                    }
                }
            ]
        }

        function gridHistory(){
			var items = standards.callFunction( '_createRemoteStore' , {
                fields:[ 
					{	name	: 'id'
                        ,type	: 'number'
                    }
					,'date'
					,'reference'
					,'remarks'
                    ,'item'
                    ,{	name	: 'cost'
                        ,type	: 'number'
                    }
					,'unit'
                    ,{	name	: 'amount'
                        ,type	: 'number'
                    }
                    ,{	name	: 'qty'
                        ,type	: 'number'
                    }
					]
                ,url: route + 'getListItems'
			});
			
			return standards.callFunction('_gridPanel', {
                id : 'gridHistory' + module
                ,module     : module
                ,store      : items
				,height     : 265
				,noDefaultRow : true
                ,columns: [
					{	header      : 'Date'
                        ,dataIndex  : 'date'
                        ,xtype      : 'datecolumn'
                        ,format     : 'm/d/Y'
                        ,flex       : 1
                        ,minWidth   : 80
                        ,sortable   : false
                        ,columnWidth: '9%'
					}
					,{	header      : 'Reference'
                        ,dataIndex  : 'reference'
                        ,flex       : 1
                        ,minWidth   : 80
                        ,sortable   : false
                        ,columnWidth: '9%'
					}
					,{	header      : 'Item Converted'
                        ,dataIndex  : 'item'
                        ,flex       : 1
                        ,minWidth   : 80
                        ,sortable   : false
                        ,columnWidth: '15%'
					}
					,{	header      : 'Remarks'
                        ,dataIndex  : 'remarks'
                        ,flex       : 1
                        ,minWidth   : 80
                        ,sortable   : false
                        ,columnWidth: '15%'
					}
					,{	header      : 'Unit'
                        ,dataIndex  : 'unit'
                        ,flex       : 1
                        ,minWidth   : 100
                        ,sortable   : false
                        ,columnWidth: '9%'
					}
					,{	header      : 'Cost'
                        ,dataIndex  : 'cost'
                        ,width      : 100
                        ,sortable   : false
                        ,columnWidth: '15%'
                        ,xtype : 'numbercolumn'
						,format : '0,000.00'
                    }
                    ,{	header      : 'Qty'
                        ,dataIndex  : 'qty'
                        ,width      : 100
                        ,columnWidth: '15%'
                        ,sortable   : false
                        ,xtype : 'numbercolumn'
						,format : '0,000'
					}
					,{	header      : 'Amount'
                        ,dataIndex  : 'amount'
                        ,width      : 100
                        ,columnWidth: '15%'
						,sortable   : false
						,xtype : 'numbercolumn'
						,format : '0,000.00'
					}
					,standards.callFunction( '_createActionColumn', {
                        canEdit     : canEdit
                        ,icon       : 'pencil'
						,tooltip    : 'Edit'
                        ,width      : 30
                        ,Func       : _edit
                    })
                    ,standards.callFunction( '_createActionColumn', {
                        canDelete     : canDelete
                        ,icon       : 'remove'
						,tooltip    : 'Delete'
						,width      : 30
                        ,Func       : _delete
                    })
				]
				,listeners: {
                    afterrender: function(){
                        items.load({})
                    }
                }
			});
        }

        function __number(value, decimals){
            return `<div style="text-align:right">${Number(value).toLocaleString('en-GB',{minimumFractionDigits: decimals, maximumFractionDigits: decimals})}</div>`
        }
        
        function _edit(data, row){
			module.getForm().retrieveData({
				url : route + 'retrieve'
				,params : {
					id : data.id
                }
                ,hasFormPDF : true
				,success : function( response, match , full) {
                    dataHolder = response;

                    Ext.getCmp('gridItem'+module).store.proxy.extraParams = {
                        'idInvoice' : response.idInvoice
                    }
                    Ext.getCmp('outputGrid'+module).store.proxy.extraParams = {
                        'idInvoice' : response.idInvoice
                    }
                    Ext.getCmp('gridItem'+module).store.load({})
                    console.log( response.idInvoice );
                    Ext.getCmp('totalamt'+module).setValue( response.amount );
                    
                    Ext.getCmp('outputGrid'+module).store.load({})
                    //Call this function to manipulate the visibility of transaction buttons aka [Approve/Cancel]
                    // standards2.callFunction('_setTransaction', {
                    //     data : response
                    //     ,module	: module
                    // });
				}
			})
			Ext.resetGrid('gridHistory'+module)

        }
        
        function _delete(data, row){
			standards.callFunction( '_createMessageBox', {
				msg	    : 'DELETE_CONFIRM'
				,action : 'confirm'
				,fn	    : function( btn ){
					if( btn == 'yes' ) {
						Ext.Ajax.request({
							url	    : route + 'delete'
							,params	: {
								id : data.id
							}
							,success : function( response ) {
                                let {match} = Ext.decode( response.responseText );
                                if(match === 2){
                                    standards.callFunction( '_createMessageBox', {
                                        msg : 'DELETE_USED'
                                    });
                                    return false;
                                }
                                standards.callFunction( '_createMessageBox', {
                                    msg : 'DELETE_SUCCESS'
                                });
                                Ext.getCmp( 'gridHistory' + module ).store.load({});							
							}
						});
					}
				}
			})
		}

        return{
			initMethod:function( config ){
				route		= config.route;
				baseurl		= config.baseurl;
				module		= config.module;
                canDelete	= config.canDelete;
                canCancel   = config.canCancel;
				pageTitle   = config.pageTitle;
				idModule	= config.idmodule
				isGae		= config.isGae;
				idAffiliate = config.idAffiliate;
				selRec      = config.selRec;
				componentCalling = config.componentCalling;
				
				return _mainPanel( config );
			}
		}
    }
}