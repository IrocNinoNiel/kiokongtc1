/**
 * Developer: Mark Reynor D. Magriña
 * Module: User Action Logs
 * Date: Feb 2, 2020
 * Finished: 
 * Description: 
 * DB Tables: 
 * */ 
  
// function userlog(){
var Userlog = function (){
	return function(){
		var baseurl, route, module, canDelete, pageTitle, canPrint, isGae;
		
		function _init(){  }
		
		function _mainPanel( config ){
			
			var staff = standards.callFunction(  '_createRemoteStore' ,{
				fields	: [ { name: 'idEu', type: 'number'} ,'name' ]
				,url 	: route + "getUsers"
			})
			var affiliateStore = standards.callFunction('_createRemoteStore',{
				fields	: [ 'idAffiliate' ,'affiliateName' ]
				,url	: route + 'getAffiliate'
			})
			
			return standards.callFunction(	'_mainPanel' ,{
				config				: config
				,moduleType			: 'report'
				,afterResetHandler 	: _init
				,tbar : {
					noExcelButton	: true
					,PDFHidden		: false
					,formPDFHandler	: _printPDF
				}
				,formItems:[
					{
						xtype : 'container'
						,style : 'margin-bottom:10px;'
						,items:[
							
							,standards2.callFunction( '_createAffiliateCombo', {
								hasAll      : 1
								,module     : module
								,allowBlank : true
								,value		: 0
								,listeners  : {
									afterrender : function(){
										var me  = this;
										me.store.load( {
											callback    : function(){
												me.setValue( 0 );
											}
										} )
									}
								}
							} )
							,standards.callFunction( '_createCombo', {
								id			:'searchBy'+module
								,store		: staff
								,fieldLabel	:'Select User'
								,emptyText	: 'Select user...'
								,valueField	: 'idEu'
								,displayField: 'name'
								,value		: 0
								,listeners  : {
									afterrender : function(){
										var me  = this;
										me.store.load( {
											callback    : function(){
												me.setValue( 0 );
											}
										} )
									}
								}
							})
							// ,standards.callFunction( '_createDateRange',{
								// sdateID:'sdate' + module
								// ,edateID:'edate' + module
								// ,noTime:true
								// ,date: new Date()
								// ,fromFieldLabel:'Date'
							// })
							
							,standards.callFunction( '_createDateRange', {
								id              : 'date' + module
								,module         : module
								,fieldLabel     : 'Date'
								,fromWidth      : 230
								,width          : 115
								,date           : Ext.date().subtract(1, 'month').toDate()
							} )
							
							
						]
					}
				]
				,beforeViewHandler	: _beforeViewHandler
				,moduleGrids 		: myGrid()
				,listeners			: {
					afterrender : _init
				}
			});
		}
		
		function _beforeViewHandler(){
			console.log('walay sulod ang Affiliate');
		}
		
		function myGrid(){
			var store = standards.callFunction(  '_createRemoteStore' ,{
					fields:[
						{name:'datelog',type:'date'}
						,{name:'time',type:'time'}
						,'affiliateName'
						,'fullname'
						,'euName'
						,'euTypeName'
						,'ref'
						,'referenceNum'
						,'code'
						,'actionLogDescription'
					], 
					url: route + "getHistory"
			});

			return standards.callFunction( '_gridPanel',{
				id		: 'gridHistory' + module
				,module	: module
				,store	: store
				,style	: 'margin-top:10px;'
				,tbar	: 'empty'
				,height	: 530
				,columns: [
					{	header 		: 'Date'
						,dataIndex 	: 'datelog'
						,width 		: 90 
						,xtype		: 'datecolumn'
						,format		: 'm/d/Y'
					}
					,{	header 		: 'Time'
						,dataIndex 	: 'time'
						,width 		: 90 
					}
					,{	header 		: 'Affiliate'
						,dataIndex 	: 'affiliateName'
						,width 		: 130
					}
					,{	header 		: 'User Full Name'
						,dataIndex 	: 'fullname'
						,width 		: 110
					}
					,{	header 		: 'User Name'
						,dataIndex 	: 'euName'
						,width 		: 110
					}
					,{	header 		: 'User Type'
						,dataIndex 	: 'euTypeName'
						,width 		: 110
					}
					,{	header 		: 'Ref'
						,dataIndex 	: 'code'
						,width 		: 50
					}
					,{	header 		: 'Number'
						,dataIndex 	: 'referenceNum'
						,width 		: 55
					}
					,{	header		: 'Description'
						,dataIndex	: 'actionLogDescription'
						,minWidth	: 150
						,flex		: 1
					}
				]
			});
		}
		
		function _printPDF(){
			var par = Ext.getCmp('gridHistory' + module).store.proxy.extraParams;
			par.title = pageTitle;
			Ext.Ajax.request({
				url: route + 'printPDF'
				,params:par
				,success: function(res){
					if( isGae ){
						window.open( route + 'viewPDF/' + pageTitle, '_blank' );
					}
					else{
						window.open( baseurl + 'pdf/admin/' + pageTitle + '.pdf');
					}
				}
			});
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