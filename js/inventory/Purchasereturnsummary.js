/**
 * Developer: Hazel Alegbeleye
 * Module: Purchase Return Summary
 * Date: January 31, 2020
 * Finished: 
 * Description: 
 * DB Tables: 
 * */ 
function Purchasereturnsummary(){
    return function(){
        var baseurl, route, module, canDelete, pageTitle, canPrint, monitoringModule, module, isGae, idAffiliate;

        function _mainPanel(config){

            var itemStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name']
                ,url        : route + 'getItems'
                ,startAt    :  0
                ,autoLoad   : true
            }), pReturnsStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name']
                ,url        : route + 'getPurchaseReturns'
                ,startAt    :  0
                ,autoLoad   : true
            }), classificationStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name']
                ,url        : route + 'getItemClassifications'
                ,startAt    :  0
                ,autoLoad   : true
            });

            return standards.callFunction(	'_mainPanel' ,{
                config		: config
                ,moduleType	: 'report'
                ,tbar       : {
					noFormButton        : true
                    ,noListButton       : true
                    ,noPDFButton        : false
                    ,PDFHidden          : false
                    ,formPDFHandler     : _printPDF
                    ,formExcelHandler   : _printExcel
                }
                ,formItems      : [
                    {
                        xtype   : 'container'
                        ,layout : 'column'
                        ,items  : [
                            {
                                xtype           : 'container'
                                ,columnWidth    : .5
                                ,items          : [
                                    standards2.callFunction( '_createAffiliateCombo', {
                                        module      : module
                                        ,id         : 'Affiliate' + module
                                        ,allowBlank : true
                                        ,hasAll     : 1
                                        ,value      : 0
                                    })
                                    // ,standards2.callFunction( '_createCostCenter', {
                                    //     module          : module
                                    //     ,idAffiliate    : parseInt( idAffiliate , 10 )
                                    //     ,allowBlank     : true
                                    //     ,hasAll         : 1
                                    //     ,value          : 0
                                    // } )
                                    ,standards.callFunction( '_createCombo', {
                                        id              : 'referenceNum' + module
                                        ,fieldLabel     : 'Reference'
                                        ,allowBlank     : true
                                        ,store          : pReturnsStore
                                        ,displayField   : 'name'
                                        ,valueField     : 'id'
                                        ,value          : 0
                                        ,listeners      : {
                                            beforeQuery : function(){
                                                let affiliate = Ext.getCmp("Affiliate" + module )
                                                    // ,costCenter = Ext.getCmp("idCostCenter" + module )
                                                    ,params = [];
                                                
                                                if( affiliate.getValue() != 0 ) params['idAffiliate'] = affiliate.getValue();
                                                // if( costCenter.getValue() != 0 ) params['idCostCenter'] = costCenter.getValue();
                                                
                                                if( params != [] ) this.getStore().proxy.extraParams = params;
                                                this.getStore().load({});
                                            }
                                        }
                                    } )
                                    ,standards.callFunction( '_createDateTime', {
                                        module          : module
                                        ,dFieldLabel    : 'Date From'
                                        ,tstyle         : 'margin-left: 5px;'
                                        ,tWidth         : 105
                                        ,tId            : 'timefrom' + module
                                        ,dId            : 'sdate' + module
                                        ,dValue         : Ext.date().subtract(1, 'month').toDate()
                                        ,tValue         : new Date(new Date().setHours(0,0,0,0))
                                    })
                                    ,standards.callFunction( '_createDateTime', {
                                        module          : module
                                        ,dFieldLabel    : 'Date To'
                                        ,tstyle         : 'margin-left: 5px;'
                                        ,tWidth         : 105
                                        ,tId            : 'timeto' + module
                                        ,dId            : 'edate' + module
                                    })
                                ]
                            }
                            ,{
                                xtype           : 'container'
                                ,columnWidth    : .5
                                ,items          : [
                                    standards.callFunction( '_createCombo', {
                                        id              : 'idItemClass' + module
                                        ,fieldLabel     : 'Classification'
                                        ,allowBlank     : true
                                        ,store          : classificationStore
                                        ,displayField   : 'name'
                                        ,valueField     : 'id'
                                        ,value          : 0
                                    } )
                                    ,standards.callFunction( '_createCombo', {
                                        id              : 'idItem' + module
                                        ,fieldLabel     : 'Item Name'
                                        ,allowBlank     : true
                                        ,store          : itemStore
                                        ,displayField   : 'name'
                                        ,valueField     : 'id'
                                        ,value          : 0
                                    } )
                                ]
                            }
                        ]
                    }
                ]
                ,moduleGrids    : [ gridItems() ]
                ,listeners  : {
                    afterrender: function(){
                        _setDefaultValue( pReturnsStore, 'referenceNum' );

                        classificationStore.proxy.extraParams.hasAll = 1;
                        _setDefaultValue( classificationStore, 'idItemClass' );

                        itemStore.proxy.extraParams.hasAll = 1;
                        _setDefaultValue( itemStore, 'idItem' );
                    }
                }
            })

            function _setDefaultValue( store, id ) {
                store.load({
                    callback: function(){
                        Ext.getCmp( id + module ).setValue(0);
                    }
                });
            }

            function gridItems(){
                var store = standards.callFunction(  '_createRemoteStore' ,{
                    fields:[
                        'affiliateName'
                        ,'costCenterName'
                        ,'date'
                        ,'supplierName'
                        ,'className'
                        ,'itemName'
                        ,'barcode'
                        , { name: 'cost', type: 'number' }
                        , { name: 'qty', type: 'number' }
                        , { name: 'amount', type: 'number' }
                        ,'referenceNum'
                    ], 
                    url: route + "getReturnList"
                });

                return standards.callFunction( '_gridPanel',{
                    id          : 'grdItems' + module
                    ,module     : module
                    ,store      : store
                    ,tbar       : {}
                    ,noDefaultRow : true
                    ,noPage     : true
                    ,plugins    : true
                    ,style      :'margin-top:10px;'
                    ,columns    : [
                        {	header : 'Affiliate'
                            ,dataIndex : 'affiliateName'
                            ,minWidth : 110
                            ,flex : 1
                        }
                        // ,{	header : 'Cost Center'
                        //     ,dataIndex : 'costCenterName'
                        //     ,minWidth : 110
                        //     ,flex : 1
                        // }
                        ,{	header : 'Date Returned'
                            ,dataIndex : 'date'
                            ,width : 90
                        }
                        ,{	header : 'Reference'
                            ,dataIndex : 'referenceNum'
                            ,width : 90
                        }
                        ,{	header : 'Supplier'
                            ,dataIndex : 'supplierName'
                            ,width : 90
                        }
                        ,{	header : 'Code'
                            ,dataIndex : 'barcode'
                            ,width : 90
                        }
                        ,{	header : 'Item Name'
                            ,dataIndex : 'itemName'
                            ,width : 90
                        }
                        ,{	header : 'Classification'
                            ,dataIndex : 'className'
                            ,width : 90
                        }
                        ,{	header : 'Cost'
                            ,dataIndex : 'cost'
                            ,xtype : 'numbercolumn'
                            ,width : 90
                        }
                        ,{	header : 'Qty Returned'
                            ,dataIndex : 'qty'
                            ,xtype : 'numbercolumn'
                            ,width : 90
                        }
                        ,{	header : 'Amount'
                            ,dataIndex : 'amount'
                            ,width : 90
                            ,xtype : 'numbercolumn'
                            ,format : '0,000.00'
                            ,hasTotal : true
                        }
                    ]
                    
                });
            }
        }

        function _printPDF(){
            var _grid               = Ext.getCmp( 'grdItems' + module );

            standards.callFunction( '_listPDF', {
                grid                    : _grid
                ,customListPDFHandler   : function(){
                    var par = standards.callFunction( 'getFormDetailsAsObject', {
                        module          : module
                        ,getSubmitValue : true
                    } );
                    par.title           = pageTitle;
                    
                    Ext.Ajax.request( {
                        url         : route + 'printPDF'
                        ,params     : par
                        ,success    : function(res){
                            if( isGae ){
                                window.open( route + 'viewPDF/' + par.title , '_blank' );
                            }
                            else{
                                window.open( baseurl + 'pdf/inventory/' + par.title + '.pdf');
                            }
                        }
                    } );
                }
            } );
        }

        function _printExcel(){
            var _grid = Ext.getCmp( 'grdItems' + module );

            standards.callFunction( '_listExcel', {
                grid                    : _grid
                ,customListExcelHandler : function(){
                    var par = standards.callFunction( 'getFormDetailsAsObject', {
                        module          : module
                        ,getSubmitValue : true
                    } );
                    par.title = pageTitle;
                    
                    Ext.Ajax.request( {
                        url         : route + 'printExcel'
                        ,params     : par
                        ,success    : function(res){
                            window.open( route + "download/" + par.title + '/inventory');
                        }
                    } );
                }
            } );
        }


        return{
			initMethod:function( config ){
				route		= config.route;
				baseurl		= config.baseurl;
				module		= config.module;
				canPrint	= config.canPrint;
				canDelete	= config.canDelete;
				canEdit		= config.canEdit;
				pageTitle   = config.pageTitle;
				idModule	= config.idmodule
				isGae		= config.isGae;
                idAffiliate = config.idAffiliate
				
				return _mainPanel( config );
			}
		}
    }
}