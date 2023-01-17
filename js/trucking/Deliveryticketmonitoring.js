/**
 * Developer: Hazel Joy Alegbeleye
 * Module: Delivery Ticket Monitoring
 * Date: Dec 16, 2021
 * Finished: 
 * Description: This module allows the authorized user to monitor Delivery Ticket transactions.
 * DB Tables: 
 * */ 

var Deliveryticketmonitoring = function (){
	return function(){
		var baseurl, route, module, canDelete, pageTitle, canPrint, isGae, componentCalling;
		
		function _mainPanel( config ){
			var customerStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name']
                ,url        : route + 'getCustomers'
                ,autoLoad   : true
            })
			,projectStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name']
                ,url        : route + 'getProjects'
            })
			,truckTypeStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name']
                ,url        : route + 'getTruckTypes'
                ,startAt    :  0
                ,autoLoad   : true
            })
			,plateNumberStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {	name : 'id', type : 'number' }, 'name' ]
                ,url        : route + 'getPlateNumber'
                ,startAt    :  0
                ,autoLoad   : true
            })
			,typeStore = standards.callFunction( '_createLocalStore' , {
                data    : [
					'Per Load'
                    ,'Per Day'
                ]
                ,startAt 	: 1
				,autoLoad 	: true
            } )
			,projectTypeStore = standards.callFunction( '_createLocalStore' , {
                data    : [
					'Construction'
                    ,'Truck'
                ]
                ,startAt 	: 0
				,autoLoad 	: true
            } )
			,locationStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {name : 'id', type : 'number'}, 'name' ]
                ,url        : route + 'getLocation'
                ,startAt    :  0
                ,autoLoad   : true
            });
			
			return standards.callFunction(	'_mainPanel' ,{
				config				: config
				,moduleType			: 'report'
				,tbar : {
					noPDFButton        	: false
                    ,noExcelButton      : false
                    ,PDFHidden          : false
                    ,formPDFHandler     : _printPDF
                    ,formExcelHandler   : _printExcel
				}
				,formItems:[
					{
						xtype       : 'fieldset'
                        ,layout     : 'column'
                        ,padding    : 10
						,items:[
							{
                                xtype			: 'container'
                                ,columnWidth	: .5
                                ,items			: [
									standards2.callFunction( '_createAffiliateCombo', {
										hasAll      : 1
										,module     : module
										,width      : 381
										,allowBlank : true
										,listeners  : {
											afterrender : function(){
												var me  = this;
												me.store.load( {
													callback    : function(){
														if( me.store.data.length > 1 ) {
															me.setValue( 0 );
														} else {
															me.setValue( parseInt(Ext.getConstant('AFFILIATEID'),10) );
														}
													}
												} )
											}
											,select     : function(){
												var me  = this;
												Ext.getCmp( 'supplierCmb' + module ).store.proxy.extraParams.idAffiliate = me.getValue();
												Ext.getCmp( 'supplierCmb' + module ).store.load( {
													callback   : function() {
														Ext.getCmp( 'idItem' + module ).setValue( 0 );
													}
												} )
											}
										}
									} )
									,standards.callFunction( '_createCombo', {
                                        id              : 'isConstruction' + module
                                        ,fieldLabel     : "Project Type"
                                        ,store          : projectTypeStore
                                        ,displayField   : 'name'
                                        ,valueField     : 'id'
										,width      	: 381
                                        ,listeners      : {
											afterrender : function(){
												this.setValue( 0 );
												projectStore.proxy.extraParams.isConstruction = (Ext.getCmp( 'isConstruction' + module ).getValue() == 0 ) ? 1 : 0;
												projectStore.load({
													callback    : function(){
														Ext.getCmp( 'idProject' + module ).setValue( 0 );
													}
												});
											}
                                            ,select     : function( me , record ){
                                            }
                                        }
                                    } )
									,standards.callFunction( '_createCombo', {
                                        id              : 'idProject' + module
                                        ,fieldLabel     : 'Project Name'
                                        ,store          : projectStore
                                        ,displayField   : 'name'
                                        ,valueField     : 'id'
										,width      	: 381
                                        ,listeners      : {
											beforeQuery :  function() {
                                                projectStore.proxy.extraParams.isConstruction = (Ext.getCmp( 'isConstruction' + module ).getValue() == 0) ? 1 : 0;
												projectStore.load({
													callback    : function(){
														Ext.getCmp( 'idProject' + module ).setValue( 0 );
													}
												})
											}
											,select	: function( me, record ){
											}
                                        }
                                    } )
									,standards.callFunction( '_createCombo', {
                                        id              : 'deliveryTicketType' + module
                                        ,fieldLabel     : "Type"
                                        ,store          : typeStore
                                        ,displayField   : 'name'
                                        ,valueField     : 'id'
										,width      	: 381
                                        ,listeners      : {
											afterrender : function(){
												this.setValue( 1 );
											}
                                            ,select     : function( me , record ){
                                            }
                                        }
                                    } )
									,standards.callFunction( '_createCombo', {
                                        id              : 'idTruckType' + module
                                        ,fieldLabel     : "Truck Type"
                                        ,store          : truckTypeStore
                                        ,displayField   : 'name'
                                        ,valueField     : 'id'
										,width      	: 381
                                        ,listeners      : {
											afterrender : function(){
												truckTypeStore.load({
													callback    : function(){
														Ext.getCmp('idTruckType' + module).setValue( 0 );
													}
												});
											}
                                            ,select     : function( me , record ){
                                            }
                                        }
                                    } )
                                    ,standards.callFunction( '_createCombo', {
                                        id              : 'plateNumber' + module
                                        ,fieldLabel     : 'Plate Number'
                                        ,store          : plateNumberStore
                                        ,displayField   : 'name'
                                        ,valueField     : 'id'
										,width      	: 381
                                        ,listeners      : {
											afterrender : function(){
												plateNumberStore.load({
													callback    : function(){
														Ext.getCmp('plateNumber' + module).setValue( 0 );
													}
												});
											}
                                            ,select     : function( me , record ){
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
										,width      	: 381
										,value			: -1
										,listeners      : {
											afterrender : function( me ){
												customerStore.load({
													callback    : function(){
														Ext.getCmp('pCode' + module).setValue( -1 );
													}
												});
											}
											,select     : function( me , record ){
											}
										}
									} )
									,standards.callFunction( '_createCombo', {
										id              : 'idLocation' + module
										,fieldLabel     : "Area Source"
										,store          : locationStore
										,displayField   : 'name'
										,valueField     : 'id'
										,width      	: 381
										,value			: -1
										,listeners      : {
											afterrender : function( me ){
												locationStore.load({
													callback    : function(){
														Ext.getCmp('idLocation' + module).setValue( 0 );
													}
												});
											}
											,select     : function( me , record ){
											}
										}
									} )
									,standards.callFunction( '_createDateRange',{
										sdateID			: 'sdate' + module
										,edateID		: 'edate' + module
										,id				: 'payabletransaction' + module
										,noTime			: true
										,fromFieldLabel	: 'Date'
									})
								]
							}
							
						]
					}
				]
				// ,beforeViewHandler	: _beforeViewHandler
				,moduleGrids 		: _gridListing()
				// ,customResetHandler	: _resetGrid
				,afterResetHandler 	: _resetGrid,
				// ,listeners			: {
				// 	afterrender : _init
				// }
			});
		}
		

		function _resetGrid(){
			Ext.getCmp( 'idAffiliate' + module ).fireEvent( 'afterrender' );
			Ext.getCmp( 'isConstruction' + module ).fireEvent( 'afterrender' );
			Ext.getCmp( 'idProject' + module ).fireEvent( 'afterrender' );
			Ext.getCmp( 'deliveryTicketType' + module ).fireEvent( 'afterrender' );
			Ext.getCmp( 'idTruckType' + module ).fireEvent( 'afterrender' );
			Ext.getCmp( 'plateNumber' + module ).fireEvent( 'afterrender' );
			Ext.getCmp( 'pCode' + module ).fireEvent( 'afterrender' );
			Ext.getCmp( 'idLocation' + module ).fireEvent( 'afterrender' );

			// Ext.getCmp( 'gridHistory' + module ).getStore().proxy.extraParams = {
				
			// 	idAffiliate			: Ext.getCmp( 'idAffiliate' + module ).getValue()
			// 	,isConstruction		: Ext.getCmp( 'isConstruction' + module ).getValue()
			// 	,idProject			: Ext.getCmp( 'idProject' + module ).getValue()
			// 	,deliveryTicketType	: Ext.getCmp( 'deliveryTicketType' + module ).getValue()
			// 	,idTruckType		: Ext.getCmp( 'idTruckType' + module ).getValue()
			// 	,plateNumber		: Ext.getCmp( 'plateNumber' + module ).getValue()
			// 	,pCode				: Ext.getCmp( 'pCode' + module ).getValue()
			// 	,idLocation			: Ext.getCmp( 'idLocation' + module ).getValue()
			// 	,sdate				: Ext.getCmp( 'sdate' + module ).getValue()
			// 	,edate				: Ext.getCmp( 'edate' + module ).getValue()
				
			// }
			// Ext.getCmp( 'gridHistory' + module ).getStore().load();
		}
		
		function _gridListing(){
			var store = standards.callFunction(  '_createRemoteStore' ,{
					fields:[
						'date'
						,'referenceNum'
						,'plateNumber'
						,'truckType'
						,'areaSource'
						,'customerName'
						,'number'
						,'deliveryType'
						,{ name:'rate', type:'number'} 
						,{ name:'total', type:'number'} 
					]
					,url: route + "getHistory"
			});

			return standards.callFunction( '_gridPanel',{
				id				: 'gridHistory' + module
				,module			: module
				,store			: store
				,style			: 'margin-top:10px;'
				,noPage			: true	
				,noDefaultRow 	: true
				,tbar			: { }
				,viewConfig		: {
                    listeners	: {
                        itemdblclick: function(dataview, record, item, index, e) {
                        }
                    }
                }
				,columns: [
					{	header 		: 'Date'
						,dataIndex 	: 'date'
						,width 		: 110 
						,xtype		: 'datecolumn'
						,format		: 'm/d/Y'
					}
					,{	header 		: 'Reference No.'
						,dataIndex 	: 'referenceNum'
						,width 		: 100
					}
					,{	header 		: 'Plate Number'
						,dataIndex 	: 'plateNumber'
						,width 		: 100
					}
					,{	header 		: 'Truck Type'
						,dataIndex 	: 'truckType'
						,minWidth	: 200
						,flex		: 1
					}
					,{	header 		: 'Area Source'
						,dataIndex 	: 'areaSource'
						,minWidth	: 200
						,flex		: 1
					}
					,{	header 		: 'Customer Name'
						,dataIndex 	: 'customerName'
						,minWidth	: 200
						,flex		: 1
					}
					,{	header 		: 'Number'
						,dataIndex 	: 'number'
						,width 		: 100
					}
					,{	header 		: 'Type'
						,dataIndex 	: 'deliveryType'
						,width 		: 100
					}
					,{	header 		: 'Rate'
						,dataIndex 	: 'rate'
						,width 		: 100
						,xtype      : 'numbercolumn'
						,format     : '0,000.00'
						// ,hasTotal   : true
					}
					,{	header 		: 'Total'
						,dataIndex 	: 'total'
						,width 		: 100
						,xtype      : 'numbercolumn'
						,format     : '0,000.00'
						,hasTotal   : true
					}
				]
			});
		}
		
		function _printPDF(){
            var _grid = Ext.getCmp('gridHistory' + module);

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
                                window.open( baseurl + 'pdf/trucking/' + par.title + '.pdf');
                            }
                        }
                    } );
                }
            } );
		}
		
		function _printExcel(){
            var _grid = Ext.getCmp('gridHistory' + module);

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
                            window.open( route + "download/" + par.title + '/trucking');
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
				canDelete	= config.canDelete;
				canPrint	= config.canPrint;
				pageTitle   = config.pageTitle;
				isGae		= config.isGae;
				idModule 	= config.idmodule;
				
				return _mainPanel( config );
			}
		}
	}
}