/**
 * Developer: Mark Reynor Magriña
 * Module: Employee Classification Settings
 * Date: October 31, 2019
 * Finished: 
 *  Description: Classification for Employees
 * */ 

function Empclassificationsettings(){
    return function() {
        var baseurl, route, module, canDelete, pageTitle, isGae,
        canPrint, userIDSelected = 0, canEdit, onEdit = 0, canSave, moduleID;

        function _init() {
        }
		
        function _mainPanel( config ) {
			showWindow();
			_init();
        }
		
		function showWindow(config){
			
			Ext.create('Ext.window.Window',{
				id 			: 'winViewSettings'+module
				,title		:'Employee Classification Settings'
				,width      : 480
                ,height     : 340
				,modal      : true
                ,closable   : true
                ,resizable  : false
				,items : [
					Ext.create('Ext.form.Panel',{
						id : 'viewSettingsForm'+module
						,border : false
						,bodyPadding: 5
						,items:[
                            {
                                xtype			: 'container'
                                ,columnWidth    : 0.40
                                ,style          : 'padding: 10px'
                                ,items: [
									standards.callFunction( '_createTextField', {
                                        id          : 'idEmpClass' + module                                        
										,hidden: true
                                        ,allowBlank : true
										,value: 0
                                    })		
									,standards.callFunction( '_createTextField', {
                                        id          : 'empclassname' + module
                                        ,fieldLabel : 'Classification Name'
                                        ,allowBlank : false
                                    })		
									,{  text: 'Save'
										,xtype: 'button'
										,iconCls: 'glyphicon glyphicon-floppy-disk'
										,handler: function(){
											saveEmployeeClassificationName()
										}
									}
									,{  text: 'Reset'
										,xtype: 'button'
										,iconCls: 'glyphicon glyphicon-refresh'
										,style  : 'margin-left:5px;'
										,handler: function(){ resetDetails() }
									}
									,_gridEmployeeClassificationDetails( config )
                                ]
                            }
						]
					} )
				]
			}).show();
			
		}
      
		function _gridEmployeeClassificationDetails( config ) {
            var storeEmployeeClassificationDetails = standards.callFunction( '_createRemoteStore' , {
                fields:[ { name : 'idEmpClass' , type : 'number' } ,'empClassName' ]
                ,url: route + 'retrieveEmployeeClassificationDetails'
            })

            return standards.callFunction('_gridPanel', {
                id : 'grdEmployeeClassificationDetails' + module
                ,module	: module
				,store 	: storeEmployeeClassificationDetails
				,style  : 'margin-top:10px;'
                ,height : 200
                ,noPage	: false
                ,columns: [
                    {	header: 'Name'
                        ,dataIndex: 'empClassName'
                        ,width: 100
                        ,sortable: false
						,flex: 1
                    }
                    ,standards.callFunction( '_createActionColumn', {
                        canEdit		: canEdit
                        ,icon 		: 'pencil'
                        ,tooltip 	: 'Edit'
                        ,Func 		: editEmployeeClassificationDetails
                    })					
					,standards.callFunction( '_createActionColumn', {
						canEdit		: canDelete
						,icon 		: 'remove'
						,tooltip 	: 'Delete'
						,Func 		: _deleteRecord
                    })
                ]
                ,listeners: {
                    afterrender: function(){
                        storeEmployeeClassificationDetails.load( {
                            params: {
                                // id: 'static'
                            }
                        } )
                    }
                }
            })
        }
		
		function resetDetails(){
			Ext.getCmp( 'idEmpClass'+module ).reset();
			Ext.getCmp( 'empclassname'+module ).reset();
			Ext.getCmp( 'grdEmployeeClassificationDetails' + module ).store.load();
			onEdit = 0;
		}
		
		function saveEmployeeClassificationName(id) {
            var idEmpClass = Ext.getCmp('idEmpClass' + module).getValue();
            var empClassName = Ext.getCmp('empclassname' + module).getValue();
            
			if(empClassName == ''){
				standards.callFunction('_createMessageBox',{ msg:'No classification name to save, please provide the appropriate classification name.' })
				return false;
			}
			
			Ext.Ajax.request({
				url: route + 'saveEmployeeClassificationName',
				params:{
					idEmpClass : idEmpClass
					,empClassName : empClassName
					,onEdit : onEdit
				}
				,success:function(response){
					var text = Ext.decode(response.responseText);				 
					var msgs = '';				

					msgs = ( parseInt( text.match,10 ) == 1 ) ? 'Classification name already exist, please choose another name.' : 'Record has been successfully saved.';
					standards.callFunction('_createMessageBox',{
						 msg: msgs
						 ,fn : function(){
							if( parseInt( text.match,10 ) == 0 ){
								resetDetails();
							}
						 }
					});
				}
				,faliure:function(){}
			})
        }

		function editEmployeeClassificationDetails(data){
			onEdit = 1; 
			Ext.Ajax.request({
				url: route + 'editEmployeeClassificationDetails'
				,params: { idEmpClass : data.idEmpClass }
				,method: 'post'
				,success: function( response ){
					var resp = Ext.decode( response.responseText );
					Ext.getCmp( 'idEmpClass' + module ).setValue(resp.view[0].idEmpClass)
					Ext.getCmp( 'empclassname' + module ).setValue(resp.view[0].empClassName)
				}
			})
		}
		
		function _deleteRecord(id){
			var empClassName = Ext.getCmp('empclassname' + module).getValue();
			standards.callFunction('_createMessageBox',{ 
				msg		: 'DELETE_CONFIRM' 
				,action	: 'confirm' 
				,fn		: function( btn ) {
					if ( btn == 'yes'){
						Ext.Ajax.request({
							url: route + 'deleteEmployeeClassificationDetails'
							,params: { 
								idEmpClass : id.idEmpClass 
								,empClassName : id.empClassName 
							}
							,method: 'post'
							,success: function( response ){ 
								var responseValue = Ext.decode( response.responseText );
								if ( parseInt( responseValue.match ) == 2 ){
									standards.callFunction('_createMessageBox',{
										msg: 'The selected employee classification has been deleted already. <br> Please select another employee classification.'
									})
								}
								else if( parseInt( responseValue.match ) == 3 ){
									standards.callFunction('_createMessageBox',{
										msg: 'The selected employee classification is currently used and cannot be deleted. <br> Please select another employee clasiffication.'
									})
								}else{ standards.callFunction('_createMessageBox',{ msg:'DELETE_SUCCESS' }) }
								resetDetails() 
							}
						})
					}else{ resetDetails(); }
				}
			})
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
                canEdit		= config.canEdit;
                canSave     = config.canSave;
                moduleID    = config.idmodule;
				
				return _mainPanel( config );
			}
		}
    }
}