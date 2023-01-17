/**
 * Developer    : Jayson Dagulo
 * Module       : Standards2(Additional file for standards to be applied only on this Project)
 * Date         : Oct 21, 2019
 * Finished     : 
 * Description  : This contains the functions and components that are considered a standard only for this project
 * */ 
var standards2 = function(){

    /**
     * Adopt function from the original _mainPanel standard with the same parameters
     * we added 2 buttons and a Status field here for this project only
     * NOTE: This is not yet completed as i dont have any way to test its full functionality
     * @param {*} params Addtiaional 
     */
    function _mainPanelTransactions( params ){
        if(typeof params['hasApproved'] != 'undefined' && params['hasApproved']) {
            params['extraFormButton'] = [
                {   label       : 'Approve Transaction'
                    ,id         : 'approveTransButton' + params.config.module
                    ,hidden     : true
                    ,iconCls    : 'glyphicon glyphicon-hand-right'
                    ,handler    : function(){
                        params.transactionHandler(2);
                    }
                }
                ,{  label       : 'Cancel Transaction'
                    ,id         : 'cancelTransButton' + params.config.module
                    ,iconCls    : 'glyphicon glyphicon-thumbs-down'
                    ,hidden     : true
                    ,handler    : function(){
                        params.transactionHandler(3);
                    }
                }
                ,{  xtype       : 'container'
                    ,id         : 'transactionStatus' + params.config.module
                    ,html       : '<span style="color:red; font-weight: bold;">Not Yet Confirmed</span>'
                    ,style      : 'margin: 3px 0px 0px 10px;'
                }
            ];
        }
        
        if( typeof params['formItems'] != 'undefined' ){
            
            let cancelTag = standards.callFunction( '_createCheckField', {
                id              : (typeof params.cancelTagId != 'undefined' ) ? params.cancelTagId : 'cancelTag' + params.config.module
                ,boxLabel       : '<span style="color: red; font-style: italic;"><strong>(Cancelled Transaction)</strong></span>'
                ,width          : 200
                ,maxLength      : 50
                ,listeners      : {
                    afterrender : function(){
                        this.setVisible( ( typeof params.hasCancelTransaction != 'undefined' ) ? params.hasCancelTransaction : false );
                    }
                }
            } );

            params['formItems'].unshift(cancelTag);
        }

        return standards.callFunction( '_mainPanel', params );
    }

    function _transactionHeader( params ){
        /* Remember to use idModule instead of idmodule :D  */
        return {
            xtype: 'container'
            ,items: [
                Ext.create( 'Ext.form.FieldSet' ,{
                    layout      : 'column'
                    ,padding    : 10
                    ,items      : [
                        {   xtype           : 'container'
                            ,columnWidth    : .5
                            ,minWidth       : params.conMainWidth
                            ,items          : [
                                _createCostCenter( {
                                    module          : params.module
                                    ,idAffiliate    : params.idAffiliate
                                    ,allowBlank     : true
                                    ,width          : params.width
                                    ,labelWidth     : params.labelWidth
                                    ,listeners      : {
                                        afterrender: function(){
                                            if( typeof params.costCenterHandler == 'undefined' ){
                                                _chckRefTag( this, params );
                                            } else {
                                                params.costCenterHandler();
                                            }
                                        }
                                    }
                                } )
                                ,_transactionReference( {
                                    module              : params.module
                                    ,idModule           : params.idModule
                                    ,tableName          : params.tableName
                                    ,idAffiliate        : params.idAffiliate
                                    ,width              : params.width
                                    ,refCodeLabelWidth  : params.labelWidth
                                    ,refCodeWidth       : params.refCodeWidth
                                    ,refNumWidth        : params.refNumWidth
                                } )
                            ]
                        }
                        ,{  xtype           : 'container'
                            ,columnWidth    : .5
                            ,minWidth       : params.conMainWidth
                            ,items          : [
                                    standards.callFunction( '_createDateTime', {
                                    module          : params.module
                                    ,dFieldLabel    : params.dFieldLabel
                                    ,tstyle         : 'margin-left: 5px;'
                                    ,tWidth         : 105
                                    ,dFieldLabel    : 'Date'
                                    ,dLabelWidth    : params.dLabelWidth
                                    ,dWidth         : params.dWidth
                                    ,tWidth         : params.tWidth
                                    ,tId            : ( typeof params.tId != 'undefined' ) ? params.tId : 'ttime' + params.module
                                    ,dId            : ( typeof params.dId != 'undefined' ) ? params.dId : 'tdate' + params.module
                                    ,minValue       : Ext.getConstant('AFFILIATEDATESTART')
                                    ,dlistener      : {
                                        afterrender    : function () {
                                            _checkIf_journal_isClosed( { 
                                                idAffiliate	: params.idAffiliate
                                                , tdate		: this.value
                                                , module	: params.module 
                                            } )
                                        }
                                        ,change : function(){
                                            _checkIf_journal_isClosed( { 
                                                idAffiliate	: params.idAffiliate
                                                , tdate		: this.value
                                                , module	: params.module 
                                            } )
                                            
                                            if( typeof params.dSelectHandler == 'undefined' ){
                                                /* Reset reference and reference num fields to re-validate 
                                                the references made on or before the selected date. */

                                                var reference = Ext.getCmp('idReference' + params.module)
                                                ,referenceNum = Ext.getCmp('referenceNum' + params.module);
            
                                                reference.reset();
                                                referenceNum.reset();
                                            } else {
                                                params.dSelectHandler();
                                            }
                                        }
                                    }
                                } )
                            ]
                        }
                    ]
                } )
            ]
        };
    }

    function _createAffiliateCombo( params ){
        var fieldLabel = ( typeof params.fieldLabel != 'undefined' )? params.fieldLabel : 'Affiliate';
        fieldLabel += ( ( typeof params.allowBlank != 'undefined' )? ( !params.allowBlank? Ext.getConstant( 'REQ' ) : '' ) : '' );
        var affiliateStore  = standards.callFunction( '_createRemoteStore', {
            fields  : [
                 {	name	: 'id'
                    ,type	: 'number'
                }, 'name', 'refTag', 'approvers', 'dateStart' ]
            ,url    : Ext.getConstant( 'STANDARD_ROUTE2' ) + 'getAffiliate'
        } );

        affiliateStore.proxy.extraParams = {
            fieldName   : params.fieldName
            ,fieldID    : params.fieldID
            ,tableName  : params.tableName
        }

        if( typeof params.hasAll != 'undefined' ) affiliateStore.proxy.extraParams['hasAll'] = params.hasAll
        if( typeof params.allValue != 'undefined' ) affiliateStore.proxy.extraParams['allValue'] = params.allValue
		if( parseInt(params.idModule) == 43 ){ /* custom validation to change affiliate name specifically for stock transfer */
			fieldLabel = params.fieldLabelST
		}
        affiliateStore.load({})
        return standards.callFunction( '_createCombo', {
            id              : ( typeof params.id != 'undefined' )? params.id : 'idAffiliate' + params.module
            ,fieldLabel     : fieldLabel
            ,valueField     : 'id'
            ,displayField   : 'name'
            ,module         : params.module
            ,store          : affiliateStore
            ,listeners      : params.listeners
            ,value          : params.value
            ,width          : ( typeof params.width != 'undefined' )? params.width : Ext.getConstant( 'DEF_WIDTH' )
            ,labelWidth     : ( typeof params.labelWidth != 'undefined' )? params.labelWidth : Ext.getConstant( 'DEF_LABEL_WIDTH' )
            ,allowBlank     : ( typeof params.allowBlank != 'undefined' )? params.allowBlank : false
            ,style          : params.style
            ,readOnly       : ( typeof params.readOnly != 'undefined'? params.readOnly : false )
        } )
    }

    function _transactionReference( params ){
        var fieldLabel  = ( typeof params.fieldLabel != 'undefined' )? params.fieldLabel : 'Reference';
        fieldLabel      += ( ( typeof params.allowBlank != 'undefined' )? ( !params.allowBlank? Ext.getConstant( 'REQ' ) : '' ) : '' );
        var refNumWidth = ( ( typeof params.refNumWidth != 'undefined' )? params.refNumWidth : Ext.getConstant( 'DEF_WIDTH' ) - ( ( ( typeof params.refCodeWidth != 'undefined' )? params.refCodeWidth : 230 ) + 5 ) );
        var referenceStore  = standards.callFunction( '_createRemoteStore', {
            fields      : [ { name	: 'id', type	: 'number' }, 'name' ]
            ,url        : Ext.getConstant( 'STANDARD_ROUTE2' ) + 'getReference'
        } );

        referenceStore.proxy.extraParams = {
            idModule        : params.idModule
            ,fieldName      : params.fieldName
            ,fieldID        : params.fieldID
            ,tableName      : params.tableName
            ,idAffiliate    : params.idAffiliate
        };

        referenceStore.load({});

        return {    
            xtype       : 'container'
            ,layout     : 'column'
            ,items      : [
                standards.callFunction( '_createCombo', {
                    id              : ( typeof params.id != 'undefined' )? params.id : 'idReference' + params.module
                    ,fieldLabel     : fieldLabel
                    ,store          : referenceStore
                    ,valueField     : 'id'
                    ,displayField   : 'name'
                    ,module         : params.module
                    ,labelWidth     : ( typeof params.refCodeLabelWidth != 'undefined' )? params.refCodeLabelWidth : Ext.getConstant( 'DEF_LABEL_WIDTH' )
                    ,width          : ( typeof params.refCodeWidth != 'undefined' )? params.refCodeWidth : 230
                    ,allowBlank     : false
                    ,style          : (typeof params.style != 'undefined')? params.style+';margin-right: 5px;': 'margin-right: 5px;'
                    ,readOnly       : ( typeof params.readOnly != 'undefined' ) ? params.readOnly : false
                    ,listeners      : {
                        beforeQuery : function( qe ) {
                            if( typeof params.beforeQueryHandler != 'undefined' ){
                                params.beforeQueryHandler();
                            } else {
                                _refBeforeQuery( params );
                            }
                        }
                        ,select : function( me, record ){
                            if( typeof params.selectHandler != 'undefined' ){
                                params.selectHandler();
                            } else {
                                let costCenter = ( typeof params.costcenterId != 'undefined' ) ? Ext.getCmp( costcenterId + params.module) : Ext.getCmp('idCostCenter' + params.module);
                                
                                var args = {
                                    idReference : record[0].get( 'id' )
                                    ,idModule   : params.idModule
                                    ,idAffiliate : params.idAffiliate
                                }
    
                                if( typeof costCenter != 'undefined' ) args.idCostCenter = costCenter.getValue();
                                _getReferenceNum( params, args, this );
                            }
                        }
                        ,afterrender : function(){
                            if( typeof params.afterrenderHandler == 'undefined' ){
                                if( params.idModule == 35 ) { 
                                    this.setValue( 1 );
                                    _getReferenceNum( params, 
                                        { 
                                            idReference: this.getValue()
                                            ,idModule: params.idModule
                                            ,idAffiliate: params.idAffiliate 
                                        }, this);
                                }
                            }
                        }
                    }
                } )
                ,standards.callFunction( '_createTextField', {
                    id              : ( typeof params.id != 'undefined' )? params.id : 'referenceNum' + params.module
                    ,allowBlank     : false
                    ,readOnly       : true
                    ,width          : refNumWidth
                } )
                ,{
                    xtype   : 'hiddenfield'
                    ,id     : 'idReferenceSeries' + params.module
                    ,value  : 0
                }
            ]
        }
    }

    function _chckRefTag( me, params ){
        let args = {
            id          : 'idAffiliate'
            ,value      : params.idAffiliate
            ,tableName  : 'affiliate'
        }

        Ext.Ajax.request({
            url     : Ext.getConstant('STANDARD_ROUTE2') + 'getRecord'
            ,params : args
            ,success: function( response ){
                let resp = Ext.decode( response.responseText );

                if( typeof resp.view != undefined && resp.view != null ){
                    let value = ( resp.view.refTag == 1 ) ? false : true;

                    me.allowBlank = value;

                    if( value ) 
                        me.labelEl.update( me.fieldLabel + ':') 
                    else
                        me.labelEl.update( me.fieldLabel + Ext.getConstant('REQ') + ':')
                }
            }
        });
    }

    function _refBeforeQuery( params ){
        let dateID      = ( typeof params.dId != 'undefined' ) ? params.dId : 'tdate' + params.module
            ,costCenter = ( typeof params.costcenterId != 'undefined' ) ? Ext.getCmp( costcenterId + params.module) : Ext.getCmp('idCostCenter' + params.module)
            ,tdate      = Ext.getCmp( dateID )
            ,date       = Ext.Date.format( tdate.getValue(), Ext.getConstant('DATE_FORMAT'))
            ,reference  = ( typeof params.referenceId != 'undefined' ) ? Ext.getCmp( referenceId + params.module) : Ext.getCmp('idReference' + params.module);

            var refArgs = {
                idAffiliate : params.idAffiliate
                ,idModule   : params.idModule
                ,date       : date
            }

            if( typeof costCenter != 'undefined' ){

                if( !costCenter.allowBlank && costCenter.getValue() == null ){
                    standards.callFunction('_createMessageBox',{ 
                        msg: 'A cost center is required for this affiliate, please select one.' 
                    });
                    return false;
                } else {
                    refArgs['idCostCenter'] = costCenter.getValue();
                }
            }

            reference.getStore().proxy.extraParams = refArgs;
            reference.getStore().load({
                callback: function(){
                    if( this.getCount() < 1 ){
                        let msg = (typeof costCenter != 'undefined' && parseInt(costCenter.getValue(),10) > 0) ? 'No reference was created for this cost center.' : 'No reference was created for this affiliate.'
                        standards.callFunction('_createMessageBox',{ msg: msg })
                    }
                }
            });
            
    }

    function _createCostCenter( params ){
        var fieldLabel  = ( typeof params.fieldLabel != 'undefined' )? params.fieldLabel : 'Cost Center'
            ,listeners  = {};
        fieldLabel += ( ( typeof params.allowBlank != 'undefined' )? ( !params.allowBlank? Ext.getConstant( 'REQ' ) : '' ) : '' );
        var costCenterStore = standards.callFunction( '_createRemoteStore', {
            fields  : [ 
                {	name	: 'id'
                    ,type	: 'number'
            }, 'name' ]
            ,url    : Ext.getConstant( 'STANDARD_ROUTE2' ) + 'getCostCenter'
            ,startAt : 0
        } )
        costCenterStore.proxy.extraParams = {
            fieldName       : params.fieldName
            ,fieldID        : params.fieldID
            ,tableName      : params.tableName
            ,idAffiliate    : params.idAffiliate
        };

        if( typeof params.hasAll != 'undefined' ) costCenterStore.proxy.extraParams['hasAll'] = params.hasAll
        if( typeof params.listeners != 'undefined' ) listeners = params.listeners;
        listeners['beforeQuery'] = function(){

            var selectedAffiliate = Ext.getCmp( 'idAffiliate' + params.module );
            if( typeof selectedAffiliate != 'undefined' ){
                var idAffiliate = ( selectedAffiliate !== null ) ? selectedAffiliate.getValue() : ( typeof params.idAffiliate != 'undefined' ? params.idAffiliate : '' );
                costCenterStore.proxy.extraParams.idAffiliate = parseInt( idAffiliate, 10 );
                costCenterStore.load({
                    callback: function() {
                        if( costCenterStore.getCount() < 1 ) {
                            standards.callFunction('_createMessageBox',{ msg: 'No cost center was assigned for this Affiliate.' })
                        }
                    }
                })
            }
        }

        costCenterStore.load({});
        
        return standards.callFunction( '_createCombo', {
            id              : ( typeof params.id != 'undefined' )? params.id : 'idCostCenter' + params.module
            ,fieldLabel     : fieldLabel
            ,store          : costCenterStore
            ,width          : ( typeof params.width != 'undefined' )? params.width : Ext.getConstant( 'DEF_WITH' )
            ,labelWidth     : ( typeof params.labelWidth != 'undefined' )? params.labelWidth : Ext.getConstant( 'DEF_LABEL_WIDTH' )
            ,valueField     : 'id'
            ,value          : params.value
            ,displayField   : 'name'
            ,allowBlank     : ( typeof params.allowBlank != 'undefined' )? params.allowBlank : false
            ,module         : params.module
            ,style          : ( typeof params.style != 'undefined' ) ? params.style : ''
            ,listeners      : listeners
        } )
    }

    function _createLocation( params ){
        var fieldLabel = ( typeof params.fieldLabel != 'undefined' )? params.fieldLabel : 'Location';
        fieldLabel += ( ( typeof params.allowBlank != 'undefined' )? ( !params.allowBlank? Ext.getConstant( 'REQ' ) : '' ) : '' );
        var store   = standards.callFunction( '_createRemoteStore', {
            fields  : [ 
                {	name	: 'id'
                    ,type	: 'number'
            }, 'name' ]
            ,url    : Ext.getConstant( 'STANDARD_ROUTE2' ) + 'getLocationCmb'
        } )

        store.proxy.extraParams.hasAll = ( typeof params.hasAll != 'undefined'? params.hasAll : 0 );
        
        return standards.callFunction( '_createCombo', {
            id              : ( typeof params.id != 'undefined' )? params.id : 'idLocation' + params.module
            ,fieldLabel     : fieldLabel
            ,store          : store
            ,width          : ( typeof params.width != 'undefined' )? params.width : Ext.getConstant( 'DEF_WITH' )
            ,labelWidth     : ( typeof params.labelWidth != 'undefined' )? params.labelWidth : Ext.getConstant( 'DEF_LABEL_WIDTH' )
            ,valueField     : 'id'
            ,value          : params.value
            ,displayField   : 'name'
            ,allowBlank     : ( typeof params.allowBlank != 'undefined' )? params.allowBlank : false
            ,module         : params.module
            ,listeners      : params.listeners
        } )
    }

    function _createSupplier( params ){
        var fieldLabel = ( typeof params.fieldLabel != 'undefined' )? params.fieldLabel : 'Supplier';
        fieldLabel += ( ( typeof params.allowBlank != 'undefined' )? ( !params.allowBlank? Ext.getConstant( 'REQ' ) : '' ) : '' );
        var store   = standards.callFunction( '_createRemoteStore', {
            fields  : [ 
                {	name	: 'id'
                    ,type	: 'number'
            }, 'name' ]
            ,url    : Ext.getConstant( 'STANDARD_ROUTE2' ) + 'getSupplierCmb'
        } )

        store.proxy.extraParams.hasAll = ( typeof params.hasAll != 'undefined'? params.hasAll : 0 );
        
        return standards.callFunction( '_createCombo', {
            id              : ( typeof params.id != 'undefined' )? params.id : 'idSupplier' + params.module
            ,fieldLabel     : fieldLabel
            ,store          : store
            ,width          : ( typeof params.width != 'undefined' )? params.width : Ext.getConstant( 'DEF_WITH' )
            ,labelWidth     : ( typeof params.labelWidth != 'undefined' )? params.labelWidth : Ext.getConstant( 'DEF_LABEL_WIDTH' )
            ,valueField     : 'id'
            ,value          : params.value
            ,displayField   : 'name'
            ,allowBlank     : ( typeof params.allowBlank != 'undefined' )? params.allowBlank : false
            ,module         : params.module
            ,listeners      : params.listeners
        } )
    }

    //Dynamic Transaction Tab Panel
    function _poTabPanel( params ){
        var items = standards.callFunction( '_createRemoteStore', {
            fields      : [ 
                            'idItem', 
                            'barcode', 
                            'itemName', 
                            'className', 
                            'idItemClass',
                            { name: 'cost', type: 'number' }, 
                            { name: 'qty', type: 'number' }, 
                            { name: 'amount', type: 'number' } 
                        ]			
            ,url        : Ext.getConstant( 'STANDARD_ROUTE2' ) + 'getItems'
        } )
        , itemStore = standards.callFunction( '_createRemoteStore', {
            fields      : [ 
                            'idItem', 
                            'barcode', 
                            'itemName',
                            'className',
                            'idItemClass'
                        ]			
            ,url        : Ext.getConstant( 'STANDARD_ROUTE2' ) + 'getItem/1'
        }), iStore = standards.callFunction( '_createRemoteStore', {
            fields      : [ 
                            'idItem', 
                            'barcode', 
                            'itemName',
                            'className',
                            'idItemClass'
                        ]			
            ,url        : Ext.getConstant( 'STANDARD_ROUTE2' ) + 'getItems'
        });

        function _getItems( store ){
            /* Insert dynamic id later on  */
            let pCode = Ext.getCmp( 'pCode' + params.module );
            let args = { idAffiliate : Ext.getConstant('AFFILIATEID') };

            // if( pCode.getValue() != null ) args['pCode'] = pCode.getValue();
            store.proxy.extraParams = args;
            store.load({});
        }

        function _selectItem( me, record, store ){
            let row     = Ext.getCmp('gridItem' + params.module).selModel.getSelection()[0]
                ,data   = ['barcode', 'itemName', 'className', 'idItem', 'idItemClass','cost'];
                                        
            if( Ext.isUnique(me.valueField, store, me) ) {
                data.map( col => { 
                    let value = record[0].raw[col];
                    row.set( col, value ) 
                    if( col == 'cost' ) row.set( 'amount', ( value * 0 ) );
                });
                row.set( 'qty', 0)
            }
        }


        var columns = [
            {  header       : 'Item Code'
                ,dataIndex  : 'barcode'
                ,flex       : 1
                ,minWidth   : 80
                ,allowBlank : false
                ,editor     : standards.callFunction( '_createCombo', {
                    id              : 'itemCode' + params.config.module
                    ,allowBlank     : true
                    ,store          : iStore
                    ,width          : 150
                    ,displayField   : 'barcode'
                    ,valueField     : 'barcode'
                    ,listeners      : {
                        beforeQuery : function() {
                            _getItems( this.getStore() );
                        }
                        ,select : function( me, record ){
                            _selectItem(this, record, items );
                        }
                    }
                })
            }
            ,{	header          : 'Item Name'
                ,dataIndex      : 'itemName'
                ,flex           : 1
                ,midWidth       : 80
                ,editor         : standards.callFunction( '_createCombo', {
                    id              : 'itemName' + params.config.module
                    ,allowBlank     : true
                    ,store          : iStore
                    ,width          : 150
                    ,displayField   : 'itemName'
                    ,valueField     : 'itemName'
                    ,listeners      : {
                        beforeQuery : function() {
                            _getItems( this.getStore() );
                        }
                        ,select : function( me, record ){
                            _selectItem(this, record, items );
                        }
                    }
                })
            }
            ,{	header          : 'Classification'
                ,dataIndex      : 'className'
                ,width          : 150
                ,columnWidth    : 30
            }
            ,{	header          : 'Cost'
                ,dataIndex      : 'cost'
                ,width          : 150
                ,columnWidth    : 30
                ,xtype          : 'numbercolumn'
                ,editor         : 'float'
            }
            ,{	header          : 'Quantity'
                ,dataIndex      : 'qty'
                ,width          : 150
                ,columnWidth    : 30
                ,xtype          : 'numbercolumn'
                ,editor         : 'float'
            }
            ,{	header          : 'Amount'
                ,dataIndex      : 'amount'
                ,width          : 150
                ,columnWidth    : 30
                ,xtype          : 'numbercolumn'
                ,summaryType    : 'sum'
                ,summaryRenderer: function(value, summaryData, dataIndex){
                    Ext.getCmp( 'totalPayable' + params.config.module ).setValue( Ext.util.Format.number( value, '0,000.00' ) );
                    return value;
                }
            }
        ];

        if( !params.isPO ) {
            var item  = {	
                header          : 'Unit'
                ,dataIndex      : 'unit'
                ,width          : 150
                ,columnWidth    : 30
            }

            columns.splice( 3, 0, item );
        }

        function _deleteItem() {
            var selRecord = Ext.getCmp('gridItem' + params.config.module ).selModel.getSelection()[0];
            items.remove( selRecord );
        }

        return {
            xtype : 'tabpanel'
            ,items: [
                {
                    title: ( params.isPO ? 'PO Item(s)' : 'SO Item(s)')
                    ,layout:{
                        type: 'card'
                    }
                    ,items  :   [
                        {	xtype : 'container'
                            ,width : 390
                            ,items : [
                                standards.callFunction( '_gridPanel',{
                                    id		        : 'gridItem' + params.config.module
                                    ,module	        : params.config.module
                                    ,store	        : items
                                    ,noDefaultRow   : true
                                    ,noPage         : true
                                    ,plugins        : true
                                    ,tbar : {
                                        canPrint        : false
                                        ,noExcel        : true
                                        ,content        : 'add'
                                        ,deleteRowFunc  : _deleteItem
                                        ,extraTbar2     : [
                                            standards.callFunction( '_createCombo', {
                                                id				: 'searchbarcode' + params.config.module
                                                ,store          : iStore
                                                ,module			: params.config.module
                                                ,fieldLabel		: ''
                                                ,allowBlank		: true
                                                ,width			: 250
                                                ,displayField   : 'barcode'
                                                ,valueField     : 'idItem'
                                                ,emptyText		: 'Search barcode...'
                                                ,hideTrigger	: true
                                                ,listeners		: {
                                                    beforeQuery	: function(){
                                                        _getItems(this.getStore());
                                                    }
                                                    ,select: function( me, record ){
                                                        let unitNum = Ext.getCmp('unitnum' + params.config.module)
                                                            ,row    = this.findRecord(this.valueField, this.getValue())
                                                            ,data   = record[0].raw
                                                            ,qty    = unitNum.getValue();
                                                        
                                                        if( Ext.isUnique(this.valueField, items, this) ) {
                                                            row.set( 'cost', data.cost );
                                                            row.set( 'amount', (data.cost * qty) );
                                                            row.set( 'qty', qty );
                                                            items.add(row)
                                                            
                                                            Ext.getCmp('searchbarcode' + params.config.module).reset()
                                                            unitNum.reset()
                                                        }
                                                    }
                                                }
                                            })
                                            ,standards.callFunction('_createNumberField',{
                                                id			: 'unitnum' + params.config.module
                                                ,module		: params.config.module
                                                ,fieldLabel	: ''
                                                ,allowBlank	: true
                                                ,width		: 75
                                                ,value		: 1
                                            })
                                        ]
                                    }
                                    ,features       : {
                                        ftype   : 'summary'
                                    }
                                    ,plugins        : true
                                    ,columns: columns
                                    ,listeners	: {
                                        afterrender : function() {
                                            items.load({});
                                        }
                                        ,edit : function( me, rowData ) {
                                            var index = rowData.rowIdx
                                            ,store = this.getStore().getRange();
        
                                            var totalAmount = 0;
        
                                            switch( rowData.field ) {
                                                case 'cost':
                                                    if( rowData.value == 0 ) {
                                                        standards.callFunction('_createMessageBox', { 
                                                            msg : 'Invalid input. Value must be greater than 0.'
                                                            ,fn: function(){ 
                                                                store[index].set('cost', rowData.originalValue );
                                                            }
                                                        });
                                                    }
                                                    totalAmount = rowData.value * store[index].data.qty;
                                                    break;
                                                case 'qty':
                                                    if( rowData.value == 0 ) {
                                                        standards.callFunction('_createMessageBox', { 
                                                            msg : 'Invalid input. Value must be greater than 0.'
                                                            ,fn: function(){ 
                                                                store[index].set('qty', rowData.originalValue );
                                                            }
                                                        });
                                                    }
                                                    totalAmount = store[index].data.cost * rowData.value
                                                    break;
                                            }
        
                                            store[index].set('amount', totalAmount );
                                        }
                                    }
                                })
                                ,standards.callFunction('_createNumberField',{
                                    id              : 'totalPayable' + params.config.module
                                    ,fieldLabel     : 'Total Amount'
                                    ,readOnly       : true
                                    ,style          : 'float:right; padding:8px; margin-top:5px; margin-right:5px;'
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
                            module	        : params.config.module
                            ,hasPrintOption : 1
                            ,config         : params.config
                            ,items          : Ext.getCmp('gridItem' + params.config.module)
                            ,supplier       : 'pCode'
                        })
                    ]
                }
            ]
        }
    }
    
    function __getDataGrid(name){
        return Ext.getCmp(name).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0)
    }

    /** 
    * This function requires:
    *       - module
    *       - data : status value [ 1 = Pending, 2 = Approved, 3 = Cancelled ]
    **/

    function _setTransaction( params ){
        var userId = Ext.getConstant('EMPLOYEEID');
        
        /**Get current affiliate approvers**/
       
        _getApprovers().then(function(resolve){
            let _approvers = resolve, currentDate = Ext.dateParse( Ext.getCmp('tdate' + params.module).getValue() );
            // MODIFIED BY CHRISTIAN
            // Revision:
            // from -> isSameOrBefore()
            // to   -> isSameOrAfter()
            if( params.module.getForm().onEdit && _approvers.some(approver => approver.idEmployee == userId && currentDate.isSameOrBefore( Ext.dateParse( approver.dateEffectivity ) ) ) ) {
                _transactionVisibility({status : params.data.status });
            } else {
                _transactionVisibility( false )
            }
        });
            
        function _transactionVisibility( value ) {
            if( typeof value == 'boolean' ) {
                _transactionButton( false, false, 'Not Yet Confirmed');
            } else {
                switch( parseInt( value.status, 10 ) ) {
                    case 1:
                        _transactionButton( true, true, 'Pending');
                        break;
                    case 2:
                        _transactionButton( false, false, 'Approved');
                        break;
                    case 3:
                        _transactionButton( false, false, 'Cancelled');
                        break;
                }
            }
        }

        function _transactionButton( approve, cancel, msg ) {
            Ext.getCmp('approveTransButton' + params.module).setVisible( approve );
            Ext.getCmp('cancelTransButton' + params.module).setVisible( cancel );
            style = ( msg == 'Approved' ? 'color: green;' : 'color: red;' );

            document.getElementById( 'transactionStatus' + params.module ).innerHTML = '<span style="'+ style +'; font-weight: bold;">' + msg + '</span>';
        }

        function _getApprovers(){
            return new Promise( function(resolve, reject){
                Ext.Ajax.request({
                    url : Ext.getConstant('STANDARD_ROUTE2') + 'getApprovers'
                    ,success : function( response ){
                        var resp = Ext.decode( response.responseText );
                        if( typeof resp != 'undefined' && resp.view != null ) resolve( resp.view );
                    }
                });
            });
        }
    }

    /* this is usually used to generate updated reference number for the transaction
     * required parameters:
     * @idReference     : this is the selected reference for the transaction
     * @idModule        : this is the id of the module
     * @idAffiliate     : this is the transaction selected affiliate
     * @idCostCenter    : this is the transaction selected cost center(optional)
     */
    function _getReferenceNum( params, eParams, me ){
        Ext.Ajax.request( { 
            url     : Ext.getConstant( 'STANDARD_ROUTE2' ) + 'getReferenceNum'
            ,msg    : 'Retrieving reference number, Please wait...'
            ,params : eParams
            ,success    : function( response ){
                let resp = Ext.decode( response.responseText )
                    ,msg = ''
                    ,referenceNum       = Ext.getCmp('referenceNum' + params.module)
                    ,idReferenceSeries  = Ext.getCmp('idReferenceSeries' + params.module);

                    let refNum = parseInt( resp.view.referenceNum, 10 ), seriesTo = parseInt( resp.view.seriesTo, 10 );
            
                switch( true ){
                    case refNum <= seriesTo:
                        referenceNum.setValue(refNum);
                        idReferenceSeries.setValue(resp.view.idReferenceSeries);
                        break;
                    case refNum > seriesTo || resp.view.match == 1:
                        msg = 'Maximum reference number exceeded. Set a new series for this reference code to create new transactions.';
                        break;
                    case refNum == null:
                        msg = 'No reference series was created for the selected cost center.';
                        break;
                }

                // switch( parseInt(resp.view.match, 10 ) ){
                //     case 0:
                //         referenceNum.setValue(resp.view.refnum);
                //         idReferenceSeries.setValue(resp.view.idReferenceSeries);
                //         break;
                //     case 1:
                //         msg = 'No reference series was created for the selected cost center.';
                //         break;
                //     case 2:
                //         msg = 'Maximum reference number exceeded. Set a new series for this reference code to create new transactions.';
                //         break;
                // }

                if( msg != '' ){
                    standards.callFunction( '_createMessageBox', { 
                        msg : msg
                        ,fn : function() {
                            me.reset();
                            referenceNum.reset();
                            idReferenceSeries.reset();
                        }
                    } )
                }
            }
        } );
    }

    function _createItemCombo( params ){
        var pageSize    = ( typeof params.pageSize != 'undefined'? params.pageSize : Ext.getConstant( 'DEF_PAGE_SIZE' ) );
        var store       = standards.callFunction( '_createRemoteStore', {
            fields          : [
                {   name    : 'idItem'
                    ,type   : 'number'
                }
                ,'barcode'
                ,'itemName'
                ,'className'
                ,'idItemClass'
                ,'unitName'
                ,'idUnit'
            ]
            ,url            : Ext.getConstant( 'STANDARD_ROUTE2' ) + 'getItemsCombo'
            ,pageSize       : pageSize
        } );
        params['store']             = ( typeof params.store != 'undefined'? params.store : store );
        params['valueField']        = ( typeof params.valueField != 'undefined'? params.valueField : 'idItem' );
        params['displayField']      = ( typeof params.displayField != 'undefined'? params.displayField : 'itemName' );
        params['pageSize']          = pageSize
        params['forceSelection']    = ( typeof params.forceSelection != 'undefined'? params.forceSelection : false );
        params['hasAll']            = ( typeof params.hasAll != 'undefined'? params.hasAll : 0 );
        params['createPicker']  = function(){
            return standards.callFunction( 'createLoadMorePlugin', params );
        }

        params.store.proxy.extraParams.displayField = params.displayField;

        return standards.callFunction( '_createCombo', params );
    }

    function _createCOACombo( params ){
        var pageSize    = ( typeof params.pageSize != 'undefined'? params.pageSize : Ext.getConstant( 'DEF_PAGE_SIZE' ) );
        var store       = standards.callFunction( '_createRemoteStore', {
            fields          : [
                {   name    : 'idCoa'
                    ,type   : 'number'
                }
                ,'acod_c15'
                ,'aname_c30'
                ,'mocod_c1'
                ,'chcod_c1'
                ,'accod_c2'
            ]
            ,url            : Ext.getConstant( 'STANDARD_ROUTE2' ) + 'getCOACombo'
            ,pageSize       : pageSize
        } );
        params['store']             = ( typeof params.store != 'undefined'? params.store : store );
        params['valueField']        = ( typeof params.valueField != 'undefined'? params.valueField : 'idCoa' );
        params['displayField']      = ( typeof params.displayField != 'undefined'? params.displayField : 'aname_c30' );
        params['pageSize']          = pageSize
        params['forceSelection']    = ( typeof params.forceSelection != 'undefined'? params.forceSelection : false );
        params['createPicker']  = function(){
            return standards.callFunction( 'createLoadMorePlugin', params );
        }

        params.store.proxy.extraParams.displayField = params.displayField;
        params.store.proxy.extraParams.hasAll       = params.hasAll;
        params.store.proxy.extraParams.isHeader     = params.isHeader;

        return standards.callFunction( '_createCombo', params );
    }

    /*
     * Added by makmak
     * Checks if journal is closed. if closed then transaction cannot be saved
     * */
    function _checkIf_journal_isClosed( params ) {
        let args = {
            idAffiliate : params.idAffiliate
            ,tdate      : params.tdate
        }

        Ext.Ajax.request({
            url     : Ext.getConstant('STANDARD_ROUTE2') + 'checkIf_journal_isClosed'
            ,params : args
            ,success: function( response ){
                var module  = params.module;
                var resp    = Ext.decode( response.responseText )
                if( resp.view ) {
                    standards.callFunction( '_createMessageBox', { msg: 'IS_CLOSEDJE' } );
                    if( saveButton = module.getButton( 'save' ) ){
                        saveButton.setVisible( false );
                    }
                } else {
                    if( saveButton = module.getButton( 'save' ) ){
                        saveButton.setVisible( true );
                    }
                }
            }
        });
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