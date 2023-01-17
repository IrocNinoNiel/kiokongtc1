var bandr = function(){
	var edited = 0,module='_bandr';
	var add_access = 0, edit_access = 0, del_access = 0, print_access = 0,excel_access = 0,pdf_access = 0,import_access = 0;
	
	var user;
	var column_list, server_succes =true,statement = 'create_table';
	var Controller  = baseurl+'admin/bandr/';
	var dayStore   	= standards.LStore(new Array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'));
	var historyStore= standards.RStore(new Array('ident','bdate','btime', 'filename', 'selected'), Controller+'Retrieve', false, 15);
	var backuptime =  Ext.Date.format( new Date(),'H_i_s' );
		function INIT(){
			Ext.Ajax.request({
				url:	 Controller+'GetUserNo',
				method:	 'post',
				success: function(res){	
						user = res.responseText;
						
						historyStore.load()
						
						Ext.Ajax.request({
							url:	 Controller+'GetSetting',
							params:{ user: user},
							method:	 'post',
							success: function(res){
									var results = Ext.JSON.decode(res.responseText);
									var view    = results.view;

									if(view.length > 0){
										var time = new Date (new Date().toDateString() + ' ' + view[0].autoBackUpTime);
										var day  = view[0].autoBackupDay;
										Ext.getCmp('time'+module).setValue(time);
										Ext.getCmp('day'+module).store.load({
											callback: function(){
												Ext.getCmp('day'+module).setValue(parseInt(day));
											}
										});
									}
									else{
										var time = new Date (new Date().toDateString() + ' ' + "06:00 PM");
										Ext.getCmp('day'+module).setValue(0);
										Ext.getCmp('time'+module).setValue(time);
									}
							}
						});
				}
			});
		}
		
	
		function MAINS(){
	
			return Ext.create('Ext.form.Panel',{
				id:'mainForm'+module,
				name:'mainForm'+module,
				layout: 'fit',
				border:false,
				fileUpload:true,
				bodyPadding:	'20',
				items:[	{xtype: 'fieldset',
						 title:	'Backup Data',
						 layout: 'column',
						 items:[				//********************* LEFT *********************
							{xtype: 'container',
							columnWidth: '.5',
							 baseCls:'xtype',
							 style:	  'margin-top:5px',
							 items:[
							       
									{xtype: 'label', text: 'Weekly Auto-Back Up Schedule:', style: 'margin-top:10px'},
									{xtype: 'container',
									 baseCls:'xtype',
									 layout: 'column',
									 style:	  'margin-top:5px',
									 items:[ComboBoxs('day', 200, dayStore),
											TimeField('time',  100),
											{xtype:   'button',
											 iconCls: 'save',
											 style:	  'margin-right:5px',
											 handler: function(){SAVE();}},
											{xtype:   'button',
											 iconCls: 'refresh',
											 handler: function(){Ext.getCmp('day'+module).reset();
																 Ext.getCmp('time'+module).reset();}},
										   ]
									},
									{xtype: 'container',
									 baseCls:'xtype',
									 style:	  'margin-top:20px',
									 items:[{xtype: 'label', text: 'Manual Back-up:'},
										   ]
									},
									{xtype: 'container',
									 baseCls:'xtype',
									 items:[{xtype: 'button',
											 text:  'Backup',
											 style:'margin-top:10px',
											 handler: function(){
												//  fncBackup();
												getList();
													backupDB();
												}
											}
										   ]
									}
								]
							},					//********************* RIGHT *********************
							{xtype: 'container',
							 baseCls:'xtype',
							 columnWidth: '.5',
							 style:	  'margin-top:5px',
							 items:[
							       
									{xtype: 'grid',
									 id:    'grid'+module,
									 store: historyStore,
									 height:371,
									 style:	  'margin-top:5px',
									 columns:[
											{header: 'Date',     width: 100, dataIndex: 'bdate',align:'right'},
											{header: 'Time',     width: 100, dataIndex: 'btime',align:'right'},
											{header: 'Filename', flex:  1  , dataIndex: 'filename'},
											standards.ActionColumn('download', DOWNLOAD),
											standards.ActionColumn('delete', DELETE),
										     ],
									 dockedItems:[standards.PagingToolbar(historyStore)],
									 listeners:{
										cellclick: function(grid, a, b, c, d, row) {
											SELECTED = row;
											grid.getStore().getAt(row).set('selected',1);
										},
										selectionchange: function( grid, selected){
											try{grid.getStore().getAt(SELECTED).set('selected',0);}
											catch(err){}
										}
									  },
									}
								]
							}
						 ]
						}
						
						],
				listeners: {afterrender:		 function(){INIT();}}
			});
		}
		
		function ComboBoxs(id, width, store){
			return Ext.create('Ext.form.field.ComboBox',{
					id:				id+module,
					name:			id+module,
					displayField:	'name',
					valueField:		'id',
					width:			width,
					labelWidth:		0,
					store:			store,
					style:			'margin-right:5px',
					editable: 		false});
		}
		
		function TimeField(id, width){
			return Ext.create('Ext.form.field.Time',{
					id:				id+module,
					name:			id+module,
					width:			width,
					labelWidth:		0,
					increment: 		60,
					style:			'margin-right:5px',
					editable: 		false});
		}
		
		
		
		
		
		function SAVE(){
			var form  = Ext.getCmp('mainForm'+module).getForm();
			form.submit({
				waitTitle: "Please wait",
				waitMsg:   "Saving data...",
				url:	Controller+'Save',
				params:{user: user,
						time: Ext.getCmp('time'+module).value},
				success:function(){
						var description ='Backup Setting : ' + userfullname + ' with ' +  usertypeName + ' rights updated Backup settings.';
						standards.setLogs({logUserID:userid,logDescription:description,moduleID:14});},
				failure:function(){alert('Failed to save data');}
			});
		}
		
		function fncBackup(){
			var lMask = standards.strdLoadMask('Processing backup .... ');
			lMask.show();
			Ext.Ajax.request({
				url: Controller+"backup", 
				method:'post',
				success: function(res){
					lMask.destroy();
					var results = Ext.JSON.decode(res.responseText);
					Ext.getCmp('grid'+module).getStore().load();
					console.warn( baseurl+'index.php/admin/bandr/download/'+results.filename);
					// window.open(baseurl+'index.php/admin/bandr/download/'+results.filename);
					var description = 'Backup Setting : ' + userfullname + ' with ' +  usertypeName + ' rights manually backup file.';
					standards.setLogs({logUserID:userid,logDescription:description,moduleID:14});				
				}
				,failure: function(){
					standards.setMessageBox({
						msg:'Database connectivity error: Failed to create backup.'
					});
					lMask.destroy();
				}
			});				
		}
		
		function DOWNLOAD(grid, row, column){
			dbFileName = grid.getStore().getAt(row).data.filename;
			// var fileNamesSplit = dbFileName.split( '.' );
			window.open(Controller+'download/'+dbFileName);
		}
		
		function DELETE(grid, row, column){
			var ident = grid.store.getAt(row).data.ident;
			Ext.Ajax.request({
				url: Controller+"MaxID",
				method:'post',
				success: function(res){
					var results = Ext.JSON.decode(res.responseText);
					max = results.max;
					
					if(parseInt(ident) == parseInt(max)){
						Ext.MessageBox.alert('SYSTEM MESSAGE', 'Cannot delete this record.');
					}
					else{
						Ext.MessageBox.confirm('SYSTEM MESSAGE', 'Are you sure you want to delete this record?', function(btn){
							if(btn === 'yes'){
								var file  = grid.store.getAt(row).data.filename;
								Ext.Ajax.request({
									url: Controller+"Delete",
									params:{file:file,
											id: ident},
									method:'post',
									success: function(res){
										grid.store.load();
										Ext.MessageBox.alert('SYSTEM MESSAGE', 'Record has been successfully deleted.');
										var description = 'Backup Setting : ' + userfullname + ' with ' +  usertypeName + ' rights delete '+file+'.';
										standards.setLogs({logUserID:userid,logDescription:description,moduleID:14});
									}	
								});
							}
						});
					}
				}	
			});
		}

		function backupDB(  ){
			var lMask = standards.strdLoadMask('Processing backup .... ');
			lMask.show();
			cnt = 0;
			time ='';
			var task = {
				run: function(){
					if( column_list.length > 0 ){
	
						if(  column_list.length == cnt ){
							Ext.TaskManager.stop(task);
							Ext.Ajax.request({
								url:Controller+'ziparchive'
								,method:'post'
								,params:{
									dateTime:backuptime
								}
								,success:function(){
									lMask.destroy();
									Ext.MessageBox.alert('SYSTEM MESSAGE', 'Database successfully backup.');
									Ext.getCmp('grid'+module).getStore().load({
										callback:function(){
											backuptime =  Ext.Date.format( new Date(),'H_i_s' );
										}
									});
									
								}
							});
							
						}
						
						else if( server_succes ){
							server_succes = false;
							Ext.Ajax.request({
								url:Controller+'backup'
								,method:'post'
								,params:{
									table_name:column_list[cnt]['table_name']
									,statement:statement
									,dateTime:backuptime
								},success:function(response){
									var obj = Ext.decode( response.responseText );
									if( obj.backup_success ){
										statement = obj.statement;
										server_succes =true;
										if( statement == 'Done' && typeof column_list[cnt] !='undefined' ){
											Ext.Ajax.request({
												url:Controller+'mergedFiles'
												,method:'post'
												,params:{
													table_name:column_list[cnt]['table_name']
													,dateTime:backuptime
												},success:function( response ){cnt++;statement='create_table';}
											})
										}
									}
									
								}
							});
		
						}
						
					}
					
					
				}
				,interval:500
				,fireOnStart :true
			}
			Ext.TaskManager.start(task);
		
		}
	
		function getList(){
			column_list =[];
			Ext.Ajax.request({
				url:Controller+'getList'
				,method:'post'
				,success:function( response ){
					var obj = Ext.decode( response.responseText );
					column_list = obj.columns; 
				}
			});
		}
	
	return {	
		TEST: function(){
			Ext.Ajax.request({
				url: Controller+"TEST2",
				method:'post',
				success: function(res){
				}	
			});
		},
		
		initMethod:function(add,edit,del,excel,pdf,import_){
			add_access = add;
			edit_access = edit;
			del_access = del;
			excel_access = excel;
			pdf_access = pdf;
			import_access = import_;
			
			return MAINS()
		}
		
	}
}();