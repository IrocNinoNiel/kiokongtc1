/**
 * Developer: Mark Reynor D. Magriña
 * Module: User Settings
 * Date: Oct 24, 2019
 * Finished: 
 * Description: The User Setting module allows authorized users to add, edit or delete users in the system.
 * DB Tables: eu, amodules
 * */ 
var Usersettings = function(){
    return function(){
        var route, module, canDelete, canPrint, pageTitle, isGae, canEdit, idModule,onEdit = 0,invalidDate = 0,onEditContributionQuickSettings=0;
		var employmentChange = 0;
		
        function _init(){
			_resetForm( );
			Ext.Ajax.request({
				url: route + 'getMinDate'
				,method: 'post'
				,success: function( response ){
					var resp = Ext.decode(response.responseText);
					Ext.getCmp( 'age' + module ).setValue( resp.age )
					Ext.getCmp( 'birthdate' + module ).setValue( resp.birthdate )
					Ext.getCmp( 'birthdate' + module ).setMaxValue( resp.birthdate )
				}
			})
        }
		
        function _mainPanel( config ){
			/* store area */
            var sm = new Ext.selection.CheckboxModel({ 
				checkOnly   : true   
				,listeners: {
						select: function( val, rec ){
								var record = rec.data
								record.chk = 1;
								rec.set('chk',1);
								rec.set('selected',1);
						}
						,deselect: function( val, record ){
							record.set( 'chk', 0 );
							record.set('selected',0);
						}
					}
			})
			var affiliateStore = standards.callFunction( '_createRemoteStore', {
				fields      : [ 'idAffiliate', 'affiliateName',{name:'chk',type:'bool'} ]			
				,url        : route + 'getAffiliate'
			} )
			var statusStore = standards.callFunction( '_createLocalStore', {
				data        : [ 'Active', 'Inactive' ]
				,startAt    : 0
			} );
            return standards.callFunction( '_mainPanel', {
                config      : config
                ,moduleType : 'form'
				,id			: 'mainFormID' + module
                ,tbar       : {
                    listLabel       : 'List'
                    ,saveFunc       : _saveForm
                    ,resetFunc      : _resetForm
                    ,noPDFButton    : true
                    ,noExcelButton  : true
                    ,filter         : {
                        filterByData    : [
                            {   'name'             : 'ID Number'
                                ,'tableNameColumn'  : 'idNumber'
                                ,'tableIDColumn'    : 'idEmployee'
                                ,'tableName'        : 'employee'
                                ,'isDateRange'      : 0
                                ,'defaultValue'     : 0
                            }
                            ,{   'name'             : 'Name'
                                ,'tableNameColumn'  : 'name'
                                ,'tableIDColumn'    : 'name'
                                ,'tableName'        : 'employee'
                                ,'isDateRange'      : 0
                                ,'defaultValue'     : 0
                            }
                            ,{   'name'             : 'Classification'
                                ,'tableNameColumn'  : 'empClassName'
                                ,'tableIDColumn'    : 'idEmpClass'
                                ,'tableName'        : 'employeeclass'
                                ,'isDateRange'      : 0
                                ,'defaultValue'     : 0
                            }
                        ]
                    }
                }
                ,formItems  : [
                    {   xtype   : 'container'
                        ,layout : 'column'
                        ,items  : [
                            {   xtype           : 'container'
                                ,columnWidth    : .4
                                ,items          : [
									 standards.callFunction( '_createTextField', {
										id          : 'idEu' + module
										,fieldLabel : ''
										,allowBlank : true
										,value		: 0
										,hidden		: true
									} )
                                    ,standards.callFunction( '_createTextField', {
                                        id          : 'idEmployee' + module
                                        ,fieldLabel : 'ID Number'
                                        ,allowBlank : true
										,value 		: 0
										,hidden		: true
                                        
                                    } )
                                    ,standards.callFunction( '_createTextField', {
                                        id          : 'idNumber' + module
                                        ,fieldLabel : 'ID Number'
										,allowBlank : false
										// ,maskRe     : /[^0-9]/
                                        ,maskRe     : /[^a-z,A-Z,-]/
										// ,maxLength  : 9
										// ,isDecimal	: true
										// ,isNumber	: true
                                    } )
                                    ,standards.callFunction( '_createTextField', {
                                        id          : 'name' + module
                                        ,fieldLabel : 'Name'
                                        ,allowBlank : false
                                        ,maxLength  : 50
                                    } )
                                    ,standards.callFunction( '_createTextArea', {
                                        id          : 'address' + module
                                        ,fieldLabel : 'Address'
                                        ,allowBlank : false
                                    } )
                                    ,standards.callFunction( '_createTextField', {
                                        id          : 'contactNumber' + module
                                        ,fieldLabel : 'Contact Number'
                                        ,allowBlank : false
                                        // ,maskRe     : /[^0-9]/
										,maskRe     : /[^a-z,A-Z]/
                                        ,maxLength  : 13
                                    } )
                                    ,standards.callFunction( '_createTextField', {
                                        id          : 'email'  + module
                                        ,fieldLabel : 'Email'
                                        ,allowBlank : false
                                        ,maxLength  : 50
										,vtype : 'email'
                                    } )
                                    ,{  xtype   : 'container'
                                        ,layout : 'hbox'
                                        ,style  : 'margin-bottom: 5px;'
                                        ,items  : [
                                            standards.callFunction( '_createDateField', {
                                                id          : 'birthdate' + module
                                                ,fieldLabel : 'Birthdate'
                                                ,allowBlank : false
                                                ,width      : 250
												,style      : 'margin-right : 5px;'
												,maxValue	: new Date()
												,listeners: {
													blur: function() {
														Ext.Ajax.request({
															url: route + 'computeAge'
															,params: { birthdate : this.value }
															,method: 'post'
															,success: function( response ){
																Ext.getCmp( 'age' + module ).setValue( response.responseText )
															}
														})
													}
												} 
                                            } )
                                            ,standards.callFunction( '_createTextField', {
                                                id          : 'age' + module
                                                ,fieldLabel : 'Age'
                                                ,allowBlank : true
                                                ,value      : 0
                                                ,labelWidth : 50
                                                ,width      : 95
												,readOnly	: true
                                            } )
                                        ]
                                    }
                                    ,standards.callFunction( '_createCombo', {
                                        id              : 'status' + module
                                        ,fieldLabel     : 'Status'
                                        ,allowBlank     : true
                                        ,store          : statusStore
                                        ,displayField   : 'name'
                                        ,valueField     : 'id'
                                        ,value          : 0
                                    } )
                                    ,standards.callFunction( '_createCheckField', {
                                        id              : 'user' + module
										/* // to put label at the right place + CSS Setting
                                        ,style          : 'margin-left : 135px' */
										// ,checked		: true
										,fieldLabel     : 'Check if user'
										
										,listeners:{
											change: function( checkbox, newValue, oldValue, eOpts ){
												if(newValue){
													Ext.getCmp( 'tabPanelID' + module ).items.getAt(2).setDisabled(false);
													
													Ext.getCmp('username'+module).allowBlank = false;
													Ext.getCmp('userType'+module).allowBlank = false;
													Ext.getCmp('password'+module).allowBlank = false;
													Ext.getCmp('confirm'+module).allowBlank = false;
													
													if( Ext.getCmp( 'idEu' + module ).getValue() == 0 ){
														Ext.getCmp('password'+module).setVisible(1);
														Ext.getCmp('confirm'+module).setVisible(1);	
													}
													
													Ext.getCmp('username'+module).validate();
													Ext.getCmp('userType'+module).validate();
													Ext.getCmp('password'+module).validate();
													Ext.getCmp('confirm'+module).validate();
												}
												else{
													Ext.getCmp( 'tabPanelID' + module ).items.getAt(2).setDisabled(true);
													
													Ext.getCmp('username'+module).reset();
													Ext.getCmp('username'+module).allowBlank = true;
													Ext.getCmp('userType'+module).reset();
													Ext.getCmp('userType'+module).allowBlank = true;
													Ext.getCmp('password'+module).reset();
													Ext.getCmp('password'+module).allowBlank = true;
													Ext.getCmp('confirm'+module).reset();
													Ext.getCmp('confirm'+module).allowBlank = true;
													Ext.getCmp('username'+module).validate();
													Ext.getCmp('userType'+module).validate();
													Ext.getCmp('password'+module).validate();
													Ext.getCmp('confirm'+module).validate();
												}
												
											}	
										}
                                    } )
                                ]
                            }
                            ,{  xtype           : 'container'
                                ,columnWidth    : .4
                                ,layout         : 'hbox'
                                ,items          : [
                                    {   xtype   : 'container'
                                        ,html   : 'Affiliate<span style="color:red;">*</span>:'
                                        ,width  : 120
                                    }
                                    ,standards.callFunction( '_gridPanel', {
                                        id          : 'grdAffiliate' + module
                                        ,module     : module
                                        ,store      : affiliateStore
                                        ,height     : 210
                                        ,width      : 300
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
                                    } )
                                ]
                            }
                        ]  
                    }
                    ,_employeeTabPanel()
                ]
                ,listItems  : _gridHistory()
                ,listeners  : {
                    afterrender : _init
                }
            } )
        }

        function _employeeTabPanel(){
            var classificationStore     = standards.callFunction( '_createRemoteStore', {
                    fields      : [
						{ name: 'idEmpClass', type:'float' }
						,'empClassName'
					]
                    ,url        : route + 'getClassification'
                } )
                ,userTypeStore          = standards.callFunction( '_createLocalStore', {
                    data        : [ 'Administrator', 'Supervisor', 'User' ]
                    ,startAt    : 1
                } );
            
			return {  
				xtype   : 'tabpanel'
				,id		: 'tabPanelID' + module
                // ,width  : 1000
				// ,style	: 'width:100%;'
				,columnWidth	: 1
                ,items  : [
                    {   title   : 'Employment'
						,disabled   : false
                        ,padding    : 10
                        ,items  : [
                            {   xtype       : 'fieldset'
                                ,title      : 'Employment Details'
                                ,layout     : 'hbox'
                                ,padding    : 10
                                // ,width      : 800
								,columnWidth : 1
                                ,items      : [
                                    {   xtype   : 'container'
                                        ,width  : 395
                                        ,items  : [
                                            standards.callFunction( '_createDateField', {
                                                id          : 'dateEmployed' + module
                                                ,fieldLabel : 'Date Employed'
												,allowBlank : false
												,listeners	: {
													change	: function(){
														_dateValidation( this , Ext.getCmp( 'dateEffective' + module ) , "Employment effectivity date should not be earlier than the employment date." )
														_dateValidation( this , Ext.getCmp( 'endOfContract' + module ) , "End of contract date should not be earlier than the employment effectivity date." )
													}
												}
                                            } )
											,standards.callFunction( '_createDateField', {
                                                id          : 'dateEmployedFromDatabase' + module
                                                ,fieldLabel : ''
                                                ,allowBlank : true
												,hidden		: true
                                            })
                                            ,standards.callFunction( '_createDateField', {
                                                id          : 'dateEffective' + module
                                                ,fieldLabel : 'Date Effective'
                                                ,allowBlank : false
												,value		: new Date(Date.now() + (3600 * 1000 * 24))
												,listeners: {
													change: function() {
														let dateEmployed = Ext.getCmp( 'dateEmployed' + module )
														let dateCompared = this
														let msg = "Employment effectivity date should not be earlier than the employment date."
														_dateValidation( dateEmployed , dateCompared , msg )
													}
												}
                                            } )
											,standards.callFunction( '_createDateField', {
                                                id          : 'dateEffectiveFromDatabase' + module
                                                ,fieldLabel : ''
                                                ,allowBlank : true
												,hidden		: true
                                            })
                                            ,standards.callFunction( '_createDateField', {
                                                id          : 'endOfContract' + module
                                                ,fieldLabel : 'End of Contract'
												,allowBlank : false
												,value		: new Date(Date.now() + (3600 * 1000 * 24))
												,listeners: {
													change: function(field, date, ov) {
														let dateEmployed = Ext.getCmp( 'dateEmployed' + module )
														let dateCompared = this
														let msg = 'End of contract date should not be earlier than the employment effectivity date.' 
														_dateValidation( dateEmployed , dateCompared , msg )
													}
												}
                                            } )
											,standards.callFunction( '_createDateField', {
                                                id          : 'endOfContractFromDatabase' + module
                                                ,fieldLabel : ''
                                                ,allowBlank : true
												,hidden		: true
                                            })
                                        ]
                                    }
                                    ,{  xtype   : 'container'
                                        ,width  : 395
                                        ,items  : [
                                            standards.callFunction( '_createCombo', {
                                                id              : 'classification' + module
                                                ,store          : classificationStore
                                                ,fieldLabel     : 'Classification'
                                                ,valueField     : 'idEmpClass'
                                                ,displayField   : 'empClassName'
                                                ,allowBlank     : false
												,listeners:{
													select : function(){ }
												}
                                            })											
											,standards.callFunction( '_createTextField', {
                                                id          : 'classificationFromDatabase' + module
                                                ,fieldLabel : ''
												,allowBlank : true
												,hidden		: true
												,isNumber	: true
                                            })
                                            ,standards.callFunction( '_createTextField', {
                                                id              : 'monthRate' + module
                                                ,fieldLabel     : 'Monthly Rate'
                                                ,allowBlank     : false
                                                ,isNumber       : true
                                                ,isDecimal  	: true
                                            })
											,standards.callFunction( '_createTextField', {
                                                id          : 'monthRateFromDatabase' + module
                                                ,fieldLabel : ''
												,allowBlank : true
												,hidden		: true
                                            })
                                        ]
                                    }
                                ]
                            }
                            ,{  xtype       : 'fieldset'
                                ,title      : 'Benefits'
                                // ,layout     : 'fit'
                                ,padding    : 10
								,columnWidth: 1
                                ,items      : _grdBenefits()
                            }
                            ,{  xtype       : 'fieldset'
                                ,title      : 'History for Modifying Employment Dates'
                                // ,layout     : 'fit'
                                ,padding    : 10
								,columnWidth: 1
                                ,items      : _grdEmpHistoryDates()
                            } 
							,{  xtype       : 'fieldset'
                                ,title      : 'History for Changing Employee Position and Rate'
                                // ,layout     : 'fit'
								,columnWidth: 1
                                ,padding    : 10
                                ,items      : _grdEmpHistoryCR()
                            }
                        ]
                    }
                    ,{  title   : 'Contribution and Tax'
						,disabled   : false
                        ,layout : 'fit'
                        ,padding    : 10
                        ,items  : [
                            {   xtype       : 'fieldset'
                                ,padding    : 10
                                ,layout     : 'fit'
                                ,items      : _grdContributionTax()
                            }
                        ]
                    }
                    ,{  title       : 'User Details'
                        ,disabled   : true
                        ,padding    : 10
                        ,items      : [ {
							xtype		: 'fieldset'
							,layout		: 'column'
							,padding    : 10
							,items		: [
								{
									xtype			: 'container'
									,columnWidth	: .4
									,items			: [
										standards.callFunction( '_createTextField', {
											id          : 'username' + module
											,fieldLabel : "Username<span style='color:red;'>*</span>"
											,allowBlank : true
											,maxLength  : 150
											,listeners: { blur: function( The, eOpts ){ if(this.value === ""){ this.validate() } } }
										} )
										,standards.callFunction( '_createCombo', {
											id              : 'userType' + module
											,fieldLabel     : "User Type<span style='color:red;'>*</span>"
											,emptyText		: 'Select user type...'
											,store			: userTypeStore
											,allowBlank 	: true
											,valueField     : 'id'
											,displayField   : 'name'							
										} )
									]
								} ,{
									xtype			: 'container'
									,columnWidth	: .6
									,items			: [
										standards.callFunction( '_createTextField', {
											id              : 'password' + module
											,fieldLabel     : "Password<span style='color:red;'>*</span>"
											,inputType      : 'password'
											,maxLength		:50
											,enforceMaxLength:true
											,minLength:4
											,minLengthText:'Password length should not be lesser than 4'
											,msgTarget	:'under'
											,allowBlank 	: true
											,enableKeyEvents: true
											,listeners: {
												blur: function( The, eOpts ){
													// var thisValue = this.value
													if(this.value === ""){
														this.validate()
													}else{
														Ext.getCmp('confirm'+module).allowBlank = false;
														Ext.getCmp('confirm'+module).validate();
													}
												}
												
											}							
										} )
										,standards.callFunction( '_createTextField', {
											id              : 'confirm' + module
											,fieldLabel     : "Repeat Password<span style='color:red;'>*</span>"
											,inputType      : 'password'
											,submitValue    : false
											,allowBlank 	: true
											,msgTarget  	: 'under'
											,validator: function( value ){
												if( Ext.getCmp( 'password' + module ).isVisible() ){ return ( value === Ext.getCmp( 'password' + module ).value ) ? true : 'Passwords do not match.'; }
												else{ return true; }
											}
										} )	
									]
								}
							]
						} ]
                    }
                ]
            }
        }

        function _grdBenefits(){
            var scheduleStore   = standards.callFunction( '_createLocalStore', {
                    data        : [ 'Daily', 'Monthly (1st Half)', 'Monthly (2nd Half)', 'Semi-Monthly' ]
                    ,startAt    : 1
                } )
                ,benefitsStore  = standards.callFunction( '_createRemoteStore', {
                    fields      : [ 'idEmpBenefits', 'description', 'amount', 'scheduleName', 'schedule' ]
                    ,url        : route + 'getEmployeeBenefits'
                } )
            return standards.callFunction( '_gridPanel', {
                id          	: 'grdEmpBenefits' + module
                ,store      	: benefitsStore
                ,noPage     	: true
                ,module     	: module
                ,height     	: 150
                ,plugins    	: true
				,noPage     	: true
				,noDefaultRow   : true
                ,tbar       : {
                    content     : 'add'
                    ,addLabel   : 'Add Benefits'
                }
                ,columns    : [
                    {   header      : 'Description'
                        ,dataIndex  : 'description'
                        ,flex       : 1
                        ,minWidth   : 150
                        ,editor     : 'text'
                    }
                    ,{  header      : 'Amount'
                        ,dataIndex  : 'amount'
                        ,width      : 100
                        ,xtype      : 'numbercolumn'
                        ,editor     : 'float'
                        ,format     : '0,000.00'
                    }
                    ,{  header      : 'Schedule'
                        ,dataIndex  : 'scheduleName'
                        ,width      : 150
                        ,editor     : standards.callFunction( '_createCombo', {
                            store           : scheduleStore
                            ,fieldLabel     : ''
                            ,id             : 'schedCombo' + module
                            ,displayField   : 'name'
                            ,valueField     : 'name'
							,emptyText		: 'Select schedule...'
                            ,listeners      : {
                                select  : function( me, record ){
                                    var grdSelection = Ext.getCmp( 'grdEmpBenefits' + module ).selModel.getSelection();
                                    if( grdSelection[0] ){
                                        grdSelection[0].set( 'schedule', record[0].get( 'id' ) );
                                    }
                                }
                            }
                        } )
                    }
                ]
            } )
        }

        function _grdEmpHistoryDates(){
            var empHistoryStore = standards.callFunction( '_createRemoteStore', {
                fields  : [
					{ name : 'idEmpHistory', type:'number' }
					,{ name : 'idEmployee', type:'number' }
                    ,'dateEmployed'
                    ,'dateEffective'
                    ,'endOfContract'
					,{ name : 'classification', type:'number' }
                    ,'empClassName'
                    ,'monthRate'
					
                ]					
                ,url    : route + 'getEmploymentHistoryDates'
            } )
            return standards.callFunction( '_gridPanel', {
                id              : 'grdEmploymentHistoryDates' + module
                ,module         : module
                ,store          : empHistoryStore
                ,height         : 250
                ,noDefaultRow   : true
                ,tbar           : {
                    content     : ''
                }
                ,columns        : [
                    {   header      : 'Date Employed'
                        ,dataIndex  : 'dateEmployed'
                        ,xtype      : 'datecolumn'
                        ,format     : 'm/d/Y'
                        ,width      : 150
                    }
                    ,{  header      : 'Date Effective'
                        ,dataIndex  : 'dateEffective'
                        ,xtype      : 'datecolumn'
                        ,format     : 'm/d/Y'
                        ,width      : 150
                    }
                    ,{  header      : 'End of Contract'
                        ,dataIndex  : 'endOfContract'
                        ,xtype      : 'datecolumn'
                        ,format     : 'm/d/Y'
                        ,width      : 150
                    }
                    ,{  header      : 'Classification'
                        ,dataIndex  : 'empClassName'
                        ,flex       : 1
                        ,minWidth   : 150
                    }
                    ,{  header      : 'Rate'
                        ,dataIndex  : 'monthRate'
                        ,xtype      : 'numbercolumn'
                        ,format     : '0,000.00'
                        ,width      : 150
                    }
                ]
            } )
        }

        function _grdEmpHistoryCR(){
            var empHistoryStore = standards.callFunction( '_createRemoteStore', {
                fields  : [
					{ name : 'idEmployment', type:'number' }
					,{ name : 'idEmployee', type:'number' }
                    ,'dateEmployed'
                    ,'dateEffective'
                    ,'endOfContract'
					,{ name : 'classification', type:'number' }
                    ,'empClassName'
                    ,'monthRate'
                ]
                // ,url    : route + 'getEmpHistory'
                ,url    : route + 'getEmployeeHistoryCR'
            } )
            return standards.callFunction( '_gridPanel', {
                id              : 'grdEmploymentHistoryCR' + module
                ,module         : module
                ,store          : empHistoryStore
                ,height         : 250
                ,noDefaultRow   : true
                ,tbar           : {
                    content     : ''
                }
                ,columns        : [
                    {   header      : 'Date Employed'
                        ,dataIndex  : 'dateEmployed'
                        ,xtype      : 'datecolumn'
                        ,format     : 'm/d/Y'
                        ,width      : 150
                    }
                    ,{  header      : 'Date Effective'
                        ,dataIndex  : 'dateEffective'
                        ,xtype      : 'datecolumn'
                        ,format     : 'm/d/Y'
                        ,width      : 150
                    }
					,{  header      : 'End of Contract'
                        ,dataIndex  : 'endOfContract'
                        ,xtype      : 'datecolumn'
                        ,format     : 'm/d/Y'
                        ,width      : 150
                    }
                    ,{  header      : 'Classification'
                        ,dataIndex  : 'empClassName'
                        ,flex       : 1
                        ,minWidth   : 150
                    }
                    ,{  header      : 'Rate'
                        ,dataIndex  : 'monthRate'
                        ,xtype      : 'numbercolumn'
                        ,format     : '0,000.00'
                        ,width      : 150
                    }
                ]
            } )
        }

		function _getCOADetails( params ){
			Ext.Ajax.request({
				url : standardRoute + 'getCOADetails'
				,params:{
					idCoa : ( typeof params.idCoa != 'undefined' ? params.idCoa : 0 )
				}
				,success:function(response){
					var ret = Ext.decode(response.responseText);
					if( typeof params.success != 'undefined' ){
						params.success(ret);
					}
				}
			});
		}

        function _grdContributionTax(){
            var contributionTaxStore  = standards.callFunction( '_createRemoteStore', {
                    fields  : [ 'idEmployee','idEmpContribution','idcontribution','contributionName', 'amount','origAmount', 'effectivityDate', 'origEffectivityDate', 'idCoa', 'accountName' ]
                    ,url    : route + 'getEmployeeContributionTax'
                } )
                ,coaStore        = standards.callFunction( '_createRemoteStore', {
                    fields  : [ 'idCoa', 'acod_c15', 'aname_c30' ]
                    ,url    : route + 'getCOADetails'
                } )
				,contributionStore = standards.callFunction('_createRemoteStore',{
					fields	: [ {name: 'idContribution', type:'number'},'contributionName']
					,url 	: route + 'getContributionSettings'
					
				});
				
            return standards.callFunction( '_gridPanel', {
                id              : 'gridContributionAndTax' + module
                ,module         : module
                ,store          : contributionTaxStore
                ,width          : 350
                ,noDefaultRow   : true
                ,plugins        : true
                ,noPage         : true
                ,tbar           : {
                    content     : 'add'
                    ,addLabel   : 'Add Contribution'
                }
                ,columns        : [
					{  
						header     	: 'idcontribution'
						,hidden		: true
                        ,dataIndex  : 'idcontribution'
                        ,width      : 100
                    }
					,{   header      : 'Contribution'
                        ,dataIndex  : 'contributionName'
                        ,flex       : 1
                        ,minWidth   : 150
						,editor     : standards.callFunction( '_createCombo', {
                            fieldLabel     : ''
                            ,id             : 'contributionID' + module
							,store			: contributionStore
							,emptyText		: 'Select contribution...'
                            ,displayField   : 'contributionName'
                            ,valueField     : 'contributionName'
                            ,listeners      : {
                                select  : function( me, recordDetails, returnedData ){
									
									// console.log(recordDetails[0].data.idContribution);
									// console.log(recordDetails[0].data);
									
									// idcontribution
									
									// return false;
									
									if( parseInt(recordDetails[0].data.idContribution) == 0 ){
										_contributionQuickSeting();
										this.reset();
										return false;
									}else{
										var validateDuplicateAtMainGrid = Ext.getCmp('gridContributionAndTax' + module).getStore();
										var row  = validateDuplicateAtMainGrid.findExact('contributionName',this.getValue());
										if(row > -1){
											msgs='Duplicate contribution found, please select another contribution.';
											this.setValue( null );
											standards.callFunction('_createMessageBox',{ msg: msgs })
											return false;
										}
										var gridMain = Ext.getCmp('gridContributionAndTax' + module);	
										var recordMain = gridMain.getSelectionModel().getSelection()[0];
										recordMain.set('idcontribution',recordDetails[0].data.idContribution);
									}
									// console.log(gridMain.getStore());
                                }
                            }
                        })
                    }
                    ,{  header      : 'Amount'
                        ,dataIndex  : 'amount'
                        ,width      : 100
                        ,xtype      : 'numbercolumn'
                        ,format     : '0,000.00'
                        ,editor     : 'float'
						// listeners : {
							// change : function(field, newVal, oldVal) {
								// field.up('grid').fireEvent("gridcellchanging", field, newVal, oldVal);
							// }
						// }
                    }
                    ,{  header      : 'Effectivity Date'
                        ,dataIndex  : 'effectivityDate'
                        ,width      : 150
                        ,xtype      : 'datecolumn'
                        ,format     : 'm/d/Y'
                        ,editor     : 'date'
                    }
                    ,{  header      : 'Account Code'
                        ,dataIndex  : 'idCoa'
                        ,width      : 100
                        ,editor     : standards.callFunction( '_createCombo', {
                            store           : coaStore
                            ,fieldLabel     : ''
							,id              : 'cmbCOADetails_coadID' + module
                            ,displayField   : 'acod_c15'
							,emptyText		: 'Account Code...'
                            ,valueField     : 'acod_c15'
							,listeners      : {
								select	: function( me, result, record, noDuplicate ){
									var gridMain = Ext.getCmp('gridContributionAndTax' + module);	
									var recordMain = gridMain.getSelectionModel().getSelection()[0];
									recordMain.set('accountName',result[0].data.aname_c30);
								}
							}
                        } )
                    }
                    ,{  header      : 'Account Name'
                        ,dataIndex  : 'accountName'
                        ,width      : 100
                        ,editor     : standards.callFunction( '_createCombo', {
                            store           : coaStore
							,id             : 'cmbCOADetails_name' + module
                            ,fieldLabel     : ''
							,emptyText		: 'Account Name...'
                            ,displayField   : 'aname_c30'
                            ,valueField     : 'aname_c30'
							,listeners      : {
								beforeQuery	: function(){
									var grdAffiliate    = Ext.getCmp( 'grdAffiliate' + module ).selModel.getSelection()
										,affRec         = new Array();
									grdAffiliate.forEach( function( data ){
										affRec.push( data.get( 'idAffiliate' ) );
									} );
									coaStore.proxy.extraParams.affiliates = Ext.encode( affRec );
								}
                                ,select		: function( me, result, record, noDuplicate ){
									var gridMain = Ext.getCmp('gridContributionAndTax' + module);	
									var recordMain = gridMain.getSelectionModel().getSelection()[0];
									recordMain.set('idCoa',result[0].data.acod_c15);
								}
							}
                        } )
						// [ 'idEmpContibution','idEmployee', 'contribution', 'amount', 'effectivityDate', 'idCoa', 'accountName' ]
                    }
                    ,standards.callFunction( '_createActionColumn', {
						canEdit     : canEdit
						,icon       : 'th-list'
						,tooltip    : 'Show History'
						// ,Func       : _contributionHistory
						,Func       : _contributionHistory
                    } )
                ]
            } )
        }

        function _gridHistory(){
            var store = standards.callFunction( '_createRemoteStore', {
				fields : [
                    { name	: 'idNumber' ,type : 'int' }
                    ,{ name	: 'idEu' ,type : 'int' }
                    ,{ name	: 'idEmployee' ,type : 'int' }
                    ,{ name	: 'userTypeNum' ,type : 'int' }
                    ,'name'
                    ,'empClassName'
					,'username'
					,'userType'
					,'status'
				]
                ,url : route + 'getEmployeeUserList'
            } );

            return standards.callFunction( '_gridPanel', {
                id              : 'gridHistory' + module
                ,module         : module
                ,store          : store
                ,width          : 350
                ,noDefaultRow   : true
                ,columns        : [
					{  header      : 'ID Number'
                        ,dataIndex  : 'idNumber'
                        ,width      : 150
                    }
                    ,{  header      : 'Name'
                        ,dataIndex  : 'name'
                        ,width      : 100
						,flex		: 1
                    }
                    ,{  header      : 'Classification'
                        ,dataIndex  : 'empClassName'
                        ,width      : 200
                    }
                    ,{  header      : 'Username'
                        ,dataIndex  : 'username'
                        ,width      : 200
                    }
                    ,{  header      : 'User Type'
                        ,dataIndex  : 'userType'
                        ,width      : 150
                    }
                    ,{  header      : 'Status'
                        ,dataIndex  : 'status'
                        ,width      : 110
                    }
                    ,standards.callFunction( '_createActionColumn', {
						canEdit     : canEdit
						,icon       : 'pencil'
						,tooltip    : 'Edit employee details'
						,Func       : _editRecord
                    } )
                    ,standards.callFunction( '_createActionColumn', {
						canEdit     : canEdit
						,icon       : 'lock'
						,tooltip    : 'Change password'
						,Func       : _editPassword
                    } )
                    ,standards.callFunction( '_createActionColumn', {
						canEdit     : canEdit
						,icon       : 'th-list'
						,tooltip    : 'Module Access'
						,Func       : _editModuleAccess
                    } )
                    ,standards.callFunction( '_createActionColumn', {
						canEdit     : canEdit
						,icon       : 'home'
						,tooltip    : 'View affiliate'
						,Func       : _viewAffiliate
                    } )
                    ,standards.callFunction( '_createActionColumn', {
						canDelete   : canDelete
						,icon       : 'remove'
						,tooltip    : 'Delete reference'
						,Func       : _deleteRecord
					} )
                ]
            } )
        }

		function _contributionQuickSeting(data){
			Ext.create('Ext.window.Window',{
				id 			: 'winViewContributionQuickSettings'+module
				,title      : 'Contribution Settings'
                ,width      : 480
                ,height     : 340
                ,modal      : true
                ,closable   : true
                ,resizable  : false
				,items : [
					Ext.create('Ext.form.Panel',{
						id 				: 'viewContributionQuickSettingsForm'+module
						,border         : false
                        ,bodyPadding    : 5
						,items:[
                            {
                                xtype			: 'container'
                                ,columnWidth    : 0.40
                                ,style          : 'padding: 10px'
                                ,items: [
									standards.callFunction( '_createTextField', {
                                        id : 'idcontributionQuickSettings' + module
										,hidden: true
                                        ,allowBlank : true
										,value: 0
                                    })		
									,standards.callFunction( '_createTextField', {
                                        id          : 'contributionQuickSettingsName' + module
                                        ,fieldLabel : 'Contribution Name'
                                        ,allowBlank : false
										,style		: 'margin-left:5px;'
                                    })		
									,{  text: 'Save'
										,xtype: 'button'
										,iconCls: 'glyphicon glyphicon-floppy-disk'
										,style: 'margin-left:5px;'
										,handler: function(){
											// saveSettings(id)
											saveContributionQuickSettingsDetails()
										}
									}
									,{  text: 'Reset'
										,xtype: 'button'
										,iconCls: 'glyphicon glyphicon-refresh'
										,style: 'margin-left:10px;'
										,handler: function(){ resetDetails() }
									}
									,_gridContributionQuickSettingDetails( data )
                                ]
                            }
						]
					} )
				]
			}).show();
			
		}		
	
		function resetContributionQuickSettings(){
			Ext.getCmp( 'idcontributionQuickSettings'+module ).reset();
			Ext.getCmp( 'contributionQuickSettingsName'+module ).reset();
			Ext.getCmp( 'grdContributionQuickSettingDetails' + module ).store.load();
			onEditContributionQuickSettings = 0;
		}	
		
		function _editContributionQuickSettingsDetails(data){
			onEditContributionQuickSettings = 1; 
			Ext.Ajax.request({
				url: route + 'editContributionQuickSettingsDetails'
				,params: { idcontribution : data.idcontribution }
				,method: 'post'
				,success: function( response ){
					var resp = Ext.decode( response.responseText );
					Ext.getCmp( 'idcontributionQuickSettings' + module ).setValue(resp.view[0].idcontribution)
					Ext.getCmp( 'contributionQuickSettingsName' + module ).setValue(resp.view[0].contributionName)
				}
			})
		}
		
		function _deleteContributionQuickSettingsDetails(data){
			var contributionQuickSettingsName = Ext.getCmp('contributionQuickSettingsName' + module).getValue();
			standards.callFunction('_createMessageBox',{ 
				msg		: 'DELETE_CONFIRM' 
				,action	: 'confirm' 
				,fn		: function( btn ) {
					if ( btn == 'yes'){
						Ext.Ajax.request({
							url: route + 'deleteEmployeeClassificationDetails'
							,params: { 
								idcontribution : data.idcontribution 
								,contributionName : data.contributionName 
							}
							,method: 'post'
							,success: function( response ){ 
								standards.callFunction('_createMessageBox',{ msg:'DELETE_SUCCESS' })
								resetContributionQuickSettings() 
							}
						})
					}else{ resetContributionQuickSettings(); }
				}
			})
		}
		
		function saveContributionQuickSettingsDetails(id) {
            var idcontributionQuickSettings = Ext.getCmp('idcontributionQuickSettings' + module).getValue();
            var contributionQuickSettingsName = Ext.getCmp('contributionQuickSettingsName' + module).getValue();
            
			if(contributionQuickSettingsName == ''){
				standards.callFunction('_createMessageBox',{ msg:'No contribution name to save, please provide the appropriate contribution name.' })
				return false;
			}
			
			Ext.Ajax.request({
				url: route + 'saveContributionQuickSettings',
				params:{
					idcontribution : idcontributionQuickSettings
					,contributionName : contributionQuickSettingsName
					,onEditContributionQuickSettings : onEditContributionQuickSettings
				}
				,success:function(response){
					var text = Ext.decode(response.responseText);				 
					var msgs = '';				 
					if( parseInt(text.match) == 1 ){ msgs = 'Contribution name already exist, please choose another name.'; }
					else if( parseInt(text.match) == 2 ){ msgs = 'Contribution name does not exist, please choose another name.'; }
					else{ msgs = 'Record has been successfully saved.'; }				 
					standards.callFunction('_createMessageBox',{ msg: msgs })
					resetContributionQuickSettings();
				}
				,faliure:function(){}
			})
        }
		
		function _gridContributionQuickSettingDetails( data ) {
            var storeContributionDetails = standards.callFunction( '_createRemoteStore' , {
                fields:[ { name : 'idcontribution', type:'number' } ,'contributionName' ]
                ,url: route + 'retrieveContributionQuickSettingDetails'
            })
            return standards.callFunction('_gridPanel', {
                id 			: 'grdContributionQuickSettingDetails' + module
                ,module 	: module
                ,store 		: storeContributionDetails
				,noPage		: false
				,height     : 200
				,style      : 'margin-top:10px;'
				/* ,tbar:{
					content:'add'
					,noUndoButton: false
					,noDeleteColumn: true					
				}	 */			
                ,columns: [
                    {	header: 'Contribution Name'
                        ,dataIndex: 'contributionName'
                        ,width: 100
                        ,sortable: false
						,flex: 1
                    }
                    ,standards.callFunction( '_createActionColumn', {
                        canEdit: canEdit
                        ,icon : 'pencil'
                        ,tooltip : 'Edit'
                        ,Func : _editContributionQuickSettingsDetails
                    })					
					,standards.callFunction( '_createActionColumn', {
						canEdit: canDelete
						,icon : 'remove'
						,tooltip : 'Delete'
						,Func : _deleteContributionQuickSettingsDetails
                    })
                ]
                ,listeners: {
                    afterrender: function(){
                        storeContributionDetails.load( {
                            params: {
                                // id: 'static'
                            }
                        } )
                    }
                }
            })
        }
	
        function _saveForm( form ){
				
				/* Grid Affiliate Details */
				var affiliateListContainer 	= new Array();
				Ext.getCmp( "grdAffiliate" + module ).store.each( function( item ) { if(parseInt(item.data.selected) == 1){ affiliateListContainer.push( item.data ) } }  )	
				var grdAffilicateCount=affiliateListContainer.length;

				if(grdAffilicateCount == 0){
					standards.callFunction('_createMessageBox',{ msg: 'No Affiliate selected, please select at least one affiliate.' })
					return false;
				}
				
				/* Grid Benefits */
				var benefitsListContainer = new Array();
				var countBenError = 0;
				Ext.getCmp( 'grdEmpBenefits' + module ).store.each( 
					function( item){ 
					
						let itemDesc = item.data.description
						let itemAmnt = item.data.description
						let itemSchd = item.data.schedule

						if ( itemDesc != "" && itemAmnt != "" && itemSchd != "" ){
							benefitsListContainer.push( item.data )
						}else{
							standards.callFunction('_createMessageBox',{ msg: 'You created an entry for benefits with lacking information, </br> Please update the record or delete the entire benefit row.' })
							countBenError = 1;
							return false;
						}
					} 
				)
				/* To stop the saving if one of the benefits details were not provided properly */
				if( countBenError > 0 ) return false;
				
				/* Grid Contribution and Taxes */
				var contributionsAndTaxContainer = new Array();
				Ext.getCmp( 'gridContributionAndTax' + module ).store.each( function ( item) { contributionsAndTaxContainer.push( item.data ) } )			
				
				var classificationAndMonthRate = 0;
				var employmentDataHolderChange = 0;
				/* Classification and Month Rate Holders */
				var classificationHolder = Ext.getCmp( 'classification' + module ).getValue();
				var classificationFromDatabaseHolder = Ext.getCmp( 'classificationFromDatabase' + module ).getValue();
				var monthRateHolder = Ext.getCmp( 'monthRate' + module ).getValue();
				var monthRateFromDatabaseHolder = parseInt(Ext.getCmp( 'monthRateFromDatabase' + module ).getValue());
				
				/* Employment Dates Holder */
				var dateEmployedHolder = Ext.Date.format( Ext.getCmp( 'dateEmployed' + module ).getValue(), 'Y-m-d');
				var dateEmployedFromDatabaseHolder = Ext.Date.format( Ext.getCmp( 'dateEmployedFromDatabase' + module ).getValue(), 'Y-m-d');
				var dateEffectiveHolder = Ext.Date.format( Ext.getCmp( 'dateEffective' + module ).getValue(), 'Y-m-d');
				var dateEffectiveFromDatabaseHolder = Ext.Date.format( Ext.getCmp( 'dateEffectiveFromDatabase' + module ).getValue(), 'Y-m-d');
				var endOfContractHolder = Ext.Date.format( Ext.getCmp( 'endOfContract' + module ).getValue(), 'Y-m-d'); 
				var endOfContractFromDatabaseHolder = Ext.Date.format( Ext.getCmp( 'endOfContractFromDatabase' + module ).getValue(), 'Y-m-d');
							
				//Check if classification and month rate has changed
				if( classificationHolder !== classificationFromDatabaseHolder ){ classificationAndMonthRate = 1; }
				if( monthRateHolder !== monthRateFromDatabaseHolder ){ classificationAndMonthRate = 1; }
				
				/* Check if employments date were changed */
				var dateEmployedComparison = dateEmployedHolder.localeCompare(dateEmployedFromDatabaseHolder);
				var dateEffectiveComparison = dateEffectiveHolder.localeCompare(dateEffectiveFromDatabaseHolder);
				var endOfContractComparison = endOfContractHolder.localeCompare(endOfContractFromDatabaseHolder);
				if ( dateEmployedComparison !== 0 || dateEffectiveComparison !== 0 || endOfContractComparison !== 0 ){
					employmentDataHolderChange = 1;
				}				
				
				form.submit({
					waitTitle	: "Please wait"
					,waitMsg	: "Submitting data..."
					,url		: route + 'saveUserForm'
					,params		: {
						affiliateList : Ext.encode( affiliateListContainer )
						,benefitsList : Ext.encode( benefitsListContainer )
						,contributionsAndTaxContainerList : Ext.encode( contributionsAndTaxContainer )
						,classificationAndMonthRateState: classificationAndMonthRate
						,employmentDataHolderChangeState: employmentDataHolderChange
					}
					,success:function( action, response ){
						
						var text = parseInt(response.result.match);
						var msgs = '';				 
						if( parseInt(text) == 1 ){ 
							msgs = 'Employee ID already exist, please choose another ID.'; 
							
						}
						else if( parseInt(text) == 2 ){ msgs = 'Employee does not exist, please choose another employee.'; }
						else if( parseInt(text) == 3 ){ msgs = "Employee user credentials cannot be removed due to the user's existing user action logs."; }
						else{ 
							msgs = 'Record has been successfully saved.'; 
							_resetForm();
						}				 
						standards.callFunction('_createMessageBox',{ msg: msgs })
					}
				})
        }

        function _editRecord( data ){
				    module.getForm().retrieveData({
						url: route + 'retrieveData'
						,params: data
						,success:function( response, data ){
							Ext.getCmp( 'grdAffiliate' + module ).store.load( { params: { idEmployee:parseInt(response.idEmployee) }
								,callback : function(){
									// console.warn( Ext.getCmp( 'grdAffiliate' + module ).selModel.getSelection() );
									Ext.getCmp( 'grdAffiliate' + module ).getView().refresh();
								}
							})
							
							var classification = Ext.getCmp( 'classification' + module );
							classification.store.load({
								callback: function(){
									classification.setValue(parseInt(response.classification));
									classification.fireEvent('select');
								}
							});
							Ext.getCmp( 'classificationFromDatabase' + module ).setValue( parseInt(response.classification) )//for classification change filtering
							Ext.getCmp( 'monthRateFromDatabase' + module ).setValue( parseInt(response.monthRate) )//for month rate change filtering
							Ext.getCmp( 'dateEmployedFromDatabase' + module ).setValue( response.dateEmployed )//for dateEmployed rate change filtering
							Ext.getCmp( 'dateEffectiveFromDatabase' + module ).setValue( response.dateEffective )//for dateEffective rate change filtering
							Ext.getCmp( 'endOfContractFromDatabase' + module ).setValue( response.endOfContract )//for endOfContract rate change filtering
							
							Ext.getCmp( 'grdEmpBenefits' + module ).store.load( { params: { idEmployee:parseInt(response.idEmployee) } })
							Ext.getCmp( 'gridContributionAndTax' + module ).store.load( { params: { idEmployee:parseInt(response.idEmployee) } })
							
							Ext.getCmp( 'grdEmploymentHistoryDates' + module ).store.load( { params: { idEmployee:parseInt(response.idEmployee) }
								,callback : function(){ }
							})
							Ext.getCmp( 'grdEmploymentHistoryCR' + module ).store.load( { params: { idEmployee:parseInt(response.idEmployee) }
								,callback : function(){ }
							})
							
							
							Ext.getCmp('password'+module).allowBlank  = true;
							Ext.getCmp('confirm'+module).allowBlank  = true;
							Ext.getCmp('password'+module).validate();
							Ext.getCmp('confirm'+module).validate();
							Ext.getCmp('password'+module).hide();
							Ext.getCmp('confirm'+module).hide();
							
							
						}
					})
        }
		
		function _editPassword ( data ){
			
			// console.log(data);
			// return false
			
			if ( data.username === null ||data.username == 'undefine' || data.username == ''){
				standards.callFunction('_createMessageBox',{ msg: 'This action is not permitted for employees that are not system users.' })
				return false;
			}
			
			
			var formChangePassPanel = Ext.widget('form', {
				id:'formWindowPass'+module,
				labelWidth:120,
				width: 500,
				border: false,
				frame : true,
				defaults: {
					anchor: '100%'
				},
				items:[
					{xtype:'form',border:false,buttonAlign:'right',bodyPadding:5
						,items:[
							{
								xtype	: 'displayfield'
								,value	:	data.idEmployee
								,hidden : true
								,style	: 'margin-right: 29px !important;'
							}							
							,{
								xtype: 'displayfield'
								,fieldLabel: 'User Name'
								,labelWidth:110
								,value:	data.username
							}
							,{
								xtype:'textfield'
								,fieldLabel:'New Password',
								name:'changePassword'+module
								,id:'changePassword'+module
								,anchor:'100%'
								,inputType:'password'
								,labelWidth:110
								,maxLength:50
								,enforceMaxLength:true
								,allowBlank:false
								,minLength:4
								,minLengthText:'Password length should not be lesser than 4'
								, msgTarget:'under'
							},{
								xtype:'textfield'
								,fieldLabel:'Confirm Password'
								,name:'confirmedPassword'+module
								,id:'confirmedPassword'+module
								,anchor:'100%'
								,inputType:'password'
								,allowBlank:false
								,labelWidth:110
								,msgTarget:'under'
								,validator: function(value) {
									return (value === Ext.getCmp('changePassword'+module).value) ? true : 'Password do not match.'
								}
							}
				
						],
						buttons:[
							{
								text:'Update',
								id:'updatePasswordBtn'+module,
								formBind:true,
								handler:function(){
									Ext.Ajax.request({
										url:route+'changPassword'
										,params:{ idEmployee: data.idEmployee, password: Ext.getCmp('changePassword'+module).value  }
										,method:'post'
										,success: function(response, option){
											// console.log('done change password');
											Ext.getCmp('formWindowPass'+module).getForm().reset()										
											Ext.getCmp('windowChangePass'+module).close() 												
											standards.callFunction('_createMessageBox',{ msg: 'Password has been successfully changed.' })
										}
										,failure: function(){											
											Ext.MessageBox.alert('Status','There was an error while sending reset password link. Please try again later.');
										}
									});
								}
							},{
								text:'Close',
								handler:function(){ 
									Ext.getCmp('formWindowPass'+module).getForm().reset();
									Ext.getCmp('windowChangePass'+module).close(); 
								}
							}
						]
					}
				]
			});
			var winPassword =  Ext.create('Ext.Window',{
				id:'windowChangePass'+module,
				title:'Change Password',
				width:515,
				autoHeight:true,
				modal:true,
				closable:false,
				resizable:false,
				items: formChangePassPanel		
			});
			winPassword.show();			
		}
		
		function _editModuleAccess( data ){
			
			if ( data.username === null ||data.username == 'undefine' || data.username == ''){
				standards.callFunction('_createMessageBox',{ msg: 'This action is not permitted for employees that are not system users.' })
				return false;
			}	
			
			function showModuleForm(){	
				var gridLoading = 0;
				if( typeof Ext.getCmp( 'sucmsg' + module ) != 'undefined')
				{
					Ext.getCmp( 'sucmsg' + module ).setVisible( false )
				}
				var userStore = standards.callFunction( '_createRemoteStore',{
					fields:[
						'username'
						,{	name : 'idEu' ,type: 'number' }
						,{	name : 'idEmployee' ,type: 'number' }
						,{	name : 'userType' ,type: 'number' }
						,'name'
						,'userTypeName'
					]
					,url: route + "getUsers"
				});
				
				
				Ext.create( 'Ext.Window', {
					id:'winMod'+module
					,title:'User Modules'
					,width:515
					,modal:true
					,closable:false
					,resizable:false
					,items: [
						{
							xtype:'form'
							,width: 505
							,height:400
							,border:false
							,items:[
								standards.callFunction( '_createCombo', {
									id:'employeeUserNameMA_winMod' + module
									,fieldLabel:'Username'
									,store:userStore
									,displayField:'username'
									,valueField:'idEu'
									,editable:false
									,style:'margin-top:3px; margin-left:5px'
									,listeners:{
										afterrender:function(){
											userStore.load({ callback:function(){ Ext.getCmp( 'employeeUserNameMA_winMod' + module ).setValue( parseInt(data.idEu,10) ); } });
										}
										,select:function(){
											var that = this
											var moduleType = Ext.getCmp( 'menu_winMod' + module )
											var grd = Ext.getCmp( 'gridModules' + module );
											
											// var varUserTypes = Ext.getCmp( 'employeeUserNameMA_winMod' + module ).store.findRecord('idEu',this.value ).data.userType; 
											var varUserTypes = this.store.findRecord('idEu',this.value ).data.userType; 
											var adminType = [
															{ 'id': 0, 'name' : 'Dashboard', moduleType: 0 } 
															,{ 'id': 1, 'name' : 'Construction', moduleType: 1 }
															,{ 'id': 2, 'name' : 'Trucking', moduleType: 2 }
															,{ 'id': 3, 'name' : 'Payroll', moduleType: 3 }
															,{ 'id': 4, 'name' : 'Inventory', moduleType: 4 }
															,{ 'id': 5, 'name' : 'Accounting', moduleType: 5 }
															,{ 'id': 6, 'name' : 'General Reports', moduleType: 6 }
															,{ 'id': 7, 'name' : 'General Settings', moduleType: 7 }
															,{ 'id': 8, 'name' : 'Admin', moduleType: 8 }
											]
											var notAdminType = [
															{ 'id': 0, 'name' : 'Dashboard', moduleType: 0 }
															,{ 'id': 1, 'name' : 'Inventory', moduleType: 1 }
															,{ 'id': 2, 'name' : 'Accounting', moduleType: 2 }
															,{ 'id': 3, 'name' : 'General Reports', moduleType: 3 }
															,{ 'id': 4, 'name' : 'General Settings', moduleType: 4 }
											]
											Ext.getCmp("menu_winMod" + module).bindStore(
												parseInt( varUserTypes , 10 ) <= 1 ?
													standards.callFunction( '_createLocalStore', { fields : [ 'id' ,'name' ] ,data : adminType } ) 
												: 
													standards.callFunction( '_createLocalStore', { fields : [ 'id' ,'name' ] ,data : notAdminType } ) 
											)
											var indexCmbUser = this.getStore().findExact('idEu', this.getValue())
											var recordCmbuser = this.getStore().getAt(indexCmbUser);
											var userTypeCmbUser = recordCmbuser.data.userType
												
											/* if( userTypeCmbUser <= 1 ){ 
												moduleType.setReadOnly( false );
											}
											else{
												moduleType.setReadOnly( true );
												moduleType.setValue( 1 );
											} */
											gridLoading = 1;
											grd.store.load({
												params: { idEu: that.getValue() ,userType: userTypeCmbUser ,moduleType: moduleType.getValue() }
												,callback: function(){
													gridLoading = 0;
													grd.getView().refresh()
												}
											})

										}
									}
								})
								,grid_winMod( )
							]
							,buttons:[
								{
									xtype: 'box',
									autoEl: { cn: '<div style="color: #3a947c">Record has been successfully saved.</div>' },
									hidden: true,
									id: 'sucmsg' + module
								}
								,{
									xtype: 'button'
									,text: 'Save'
									,id:'savemod' + module
									,width: 100
									,hidden: (canEdit) ? false : true
									,handler: function(){ saveModules( data ); }
								}
								,{
									xtype: 'button'
									,text: 'Close'
									,width: 100
									,handler: function(){ Ext.getCmp( 'winMod' + module ).close(); }
								}
							]
						}
					]
				}).show();
				
			}
			
				var sm = new Ext.selection.CheckboxModel({
					checkOnly:true
					,listeners: {
						select: function( val, rec ){
							if( gridLoading == 0 ){ /* prevent after store load to not alter values from the database */
								var record = rec.data
									,moduleID = parseInt( rec.get( 'moduleID' ), 10 );
								record.chk = true;
								
								// console.log(record);
								// console.log(rec);								
								/* filter here the per module settings for save, delete, edit and print if necessary */								
								rec.set( 'canSave', rec.raw.canSave );
								rec.set( 'canDelete', rec.raw.canDelete );
								rec.set( 'canEdit', rec.raw.canEdit );
								rec.set( 'canPrint', rec.raw.canPrint );
								
								/* switch( moduleID ){
									case 1:
										rec.set( 'canSave', true );
										rec.set( 'canDelete', true );
										rec.set( 'canEdit', false );
										rec.set( 'canPrint', true );
										break;
									case 2:
										rec.set( 'canSave', true );
										rec.set( 'canEdit', true );
										rec.set( 'canDelete', true );
										rec.set( 'canPrint', true );
										break;
									case 3:
										rec.set( 'canSave', true );
										rec.set( 'canDelete', false );
										rec.set( 'canEdit', true );
										rec.set( 'canPrint', true );
										break;
									case 4:
										rec.set( 'canSave', true );
										rec.set( 'canDelete', true );
										rec.set( 'canEdit', true );
										rec.set( 'canPrint', true );
										break;
									case 5:
										rec.set( 'canSave', true );
										rec.set( 'canDelete', true );
										rec.set( 'canEdit', true );
										rec.set( 'canPrint', true );
										break;
									case 6:
										rec.set( 'canSave', true );
										rec.set( 'canDelete', true );
										rec.set( 'canEdit', true );
										rec.set( 'canPrint', true );
										break;
									case 7:
										rec.set( 'canSave', true );
										rec.set( 'canDelete', true );
										rec.set( 'canEdit', true );
										rec.set( 'canPrint', true );
										break;
									case 8:
										rec.set( 'canSave', false );
										rec.set( 'canDelete', false );
										rec.set( 'canEdit', false );
										rec.set( 'canPrint', true );
										break;
									case 9:
										rec.set( 'canSave', true );
										rec.set( 'canDelete', false );
										rec.set( 'canEdit', true );
										rec.set( 'canPrint', true );
										break;
								} */
							}
						}
						,deselect: function( val, record ){
							if( gridLoading == 0 ){ /* prevent after store load to not alter values from the database */
								record.set( 'chk', 0 );
								record.set( 'canSave', false );
								record.set( 'canEdit', false );
								record.set( 'canDelete', false );
								record.set( 'canPrint', false );
							}
						}
					}
				});
				function grid_winMod( ){

					var moduleStore = standards.callFunction( '_createRemoteStore',{
								fields:[
									'moduleName'
									,{	name : 'moduleType',type: 'number'}
									,{	name : 'idModule',type: 'number'}
									,{	name : 'idEu',type: 'number'}
									,{	name: 'canSave',type: 'bool'}
									,{	name: 'canEdit',type: 'bool'}
									,{	name: 'canDelete',type: 'bool'}
									,{	name: 'canPrint',type: 'bool'}
									,{	name: 'canCancel',type: 'bool'}
									,{	name: 'chk',type: 'bool'}
								]
								,url: route + "getModules"
								,autoLoad: false
							});
				
				if( parseInt(data.userTypeNum) <= 1){

					var menuStore = standards.callFunction( '_createLocalStore',{
						startAt:0
						,fields : [ 'id' ,'name' ]
						,data: [
							{ 'id': 0, 'name' : 'Dashboard', moduleType: 0 }
							,{ 'id': 1, 'name' : 'Construction', moduleType: 1 }
							,{ 'id': 2, 'name' : 'Trucking', moduleType: 2 }
							,{ 'id': 3, 'name' : 'Payroll', moduleType: 3 }
							,{ 'id': 4, 'name' : 'Inventory', moduleType: 4 }
							,{ 'id': 5, 'name' : 'Accounting', moduleType: 5 }
							,{ 'id': 6, 'name' : 'General Reports', moduleType: 6 }
							,{ 'id': 7, 'name' : 'General Settings', moduleType: 7 }
							,{ 'id': 8, 'name' : 'Admin', moduleType: 8 }
						]
					});
				}else{
					var menuStore = standards.callFunction( '_createLocalStore',{
						startAt:0
						,fields : [ 'id' ,'name' ]
						,data: [
							{ 'id': 0, 'name' : 'Dashboard', moduleType: 0 }
							,{ 'id': 1, 'name' : 'Construction', moduleType: 1 }
							,{ 'id': 2, 'name' : 'Trucking', moduleType: 2 }
							,{ 'id': 3, 'name' : 'Payroll', moduleType: 3 }
							,{ 'id': 4, 'name' : 'Inventory', moduleType: 4 }
							,{ 'id': 5, 'name' : 'Accounting', moduleType: 5 }
							,{ 'id': 6, 'name' : 'General Reports', moduleType: 6 }
							,{ 'id': 7, 'name' : 'General Settings', moduleType: 7 }
							// ,{ 'id': 8, 'name' : 'Admin', moduleType: 8 }
						]
					});
					
				}
					var checkListeners = {
						checkchange: function( me, rowIndex, checked ){
							var grd = moduleStore.getAt( rowIndex );
							/* if row has no check selected  then remove the sm selection */
							if( checked ){
								sm.select( rowIndex, true ); 
								Ext.getCmp( 'gridModules'+module ).getView().refresh();
							}
							else{
								if( !grd.data.canSave && !grd.data.canEdit && !grd.data.canDelete && !grd.data.canPrint && !grd.data.canCancel){
									// sm.deselect( rowIndex, false );
								}
							}
						}
					};
					
					
					var columnRenderer = function( value, metaData, record, rowIndex, columnIndex ){
						// var idModule = parseInt( record.get( 'idModule' ), 10)
						// ,adminModules = [3, 4, 5, 6, 7, 9]
						// ,settingsModules = [8,10,11,12]
						// ,reportModules = [50,55,56,60,64,65];
						
						// if( adminModules.includes( idModule ) && columnIndex == 5 ) return null;
						// if( settingsModules.includes( idModule ) && columnIndex == 5 ) return null;
						// if( (idModule == 7 && columnIndex == 3) || (idModule == 7 && columnIndex == 6) ) return null; //backup and restore
						// if( [1,2,3,4].includes( columnIndex) && idModule == 9) return null; //user action logs
						// if( [1,2,3,4,5].includes( columnIndex) && reportModules.includes(idModule)) return null; //reports 
						// if( [1,2,3,4,5].includes( columnIndex) && idModule == 1) return null; //dashboard 

						// return ( new Ext.ux.CheckColumn() ).renderer( value, metaData );

						var idModule 		= parseInt( record.get( 'idModule' ), 10)
						,adminModules 		= [3, 9, 7, 6, 5, 4]
						,settingsModules 	= [10, 8, 12, 11, 77]
						,reportModules 		= [64, 65, 50, 55, 56, 60];
						
						if( adminModules.includes( idModule ) && columnIndex == 5 ) return null;
						if( settingsModules.includes( idModule ) && columnIndex == 5 ) return null;
						if( (idModule == 7 && columnIndex == 3) || (idModule == 7 && columnIndex == 6) ) return null; //backup and restore
						if( [1,2,3,4].includes( columnIndex) && idModule == 9) return null; //user action logs
						if( [1,2,3,4,5].includes( columnIndex) && reportModules.includes(idModule)) return null; //reports 
						if( [1,2,3,4,5].includes( columnIndex) && idModule == 1) return null; //dashboard 

						return ( new Ext.ux.CheckColumn() ).renderer( value, metaData );
					};
				
					return standards.callFunction( '_gridPanel',{
						id:'gridModules' + module
						,module	: module
						,store: moduleStore
						,height : 341
						,selModel: sm
						,plugins: true
						,noPage : true
						,tbar:{
							content : [
								standards.callFunction( '_createCombo', {
									id:'menu_winMod' + module
									,fieldLabel:'Module Type'
									,store:menuStore
									,value:1
									,width: 360
									,editable:false
									,style:'margin-left:3px'
									,listeners:{
										select:function(){
											var that = this
											var grd = Ext.getCmp( 'gridModules' + module )
											var cmbEmployeeUserNameMA = Ext.getCmp( 'employeeUserNameMA_winMod' + module )
											var udIDHolder = Ext.getCmp( 'employeeUserNameMA_winMod' + module ).value;
											var indexCmbUser = cmbEmployeeUserNameMA.getStore().findExact('idEu', udIDHolder)
											var recordCmbuser = cmbEmployeeUserNameMA.getStore().getAt(indexCmbUser);
											var userTypeCmbUser = recordCmbuser.data.userType
											gridLoading = 1;
											grd.store.load( {
												params: {
													idEu: udIDHolder
													,userTypeCmbUser: userTypeCmbUser
													,moduleType: Ext.getCmp( 'menu_winMod' + module ).value
												}
												,callback: function(){
													gridLoading = 0;
													grd.getView().refresh();
												}
											} );
										}
										,afterrender: function(data){
											// var userType = parseInt( data.userType, 10 )
											// var me = Ext.getCmp( 'menu_winMod' + module );
											
											// if( userType <= 1  ){
												// me.setReadOnly( false );
												// me.setValue( 1 );
											// }
											// else{
												// me.setReadOnly( true );
												// me.setValue( 1 )
											// }
										}
									}
								})
							]
						}
						,columns:[
							{	xtype: 'gridcolumn'
								,header: 'Modules'
								,dataIndex: 'moduleName'
								,flex:1
								,renderer: function( val, params, record, row_index ){
									if( record.data.chk ){
										sm.select( row_index, true );
									}
									return val;
								}				
							}
							,{
								header: 'Save'
								,dataIndex: 'canSave'
								,xtype: 'checkcolumn'
								,sortable : false
								,width: 57
								,listeners:checkListeners
								,renderer: columnRenderer
							}
							,{
								header: 'Edit'
								,dataIndex: 'canEdit'
								,xtype: 'checkcolumn'
								,sortable : false
								,width: 57
								,listeners:checkListeners
								,renderer: columnRenderer
							}
							,{
								header: 'Delete'
								,dataIndex: 'canDelete'
								,xtype: 'checkcolumn'
								,sortable : false
								,width: 57
								,listeners:checkListeners
								,renderer: columnRenderer
							}
							,{
								header: 'Cancel'
								,dataIndex: 'canCancel'
								,xtype: 'checkcolumn'
								,sortable : false
								,width: 57
								,listeners:checkListeners
								,renderer: columnRenderer
							}
							,{
								header: 'Print'
								,dataIndex: 'canPrint'
								,xtype: 'checkcolumn'
								,sortable : false
								,width: 57
								,listeners:checkListeners
								,renderer: columnRenderer
							}
						]
						,listeners:{
							afterrender:function(){
								var grd = Ext.getCmp( 'gridModules' + module );
								gridLoading = 1;
								grd.store.load({
									params: {
										idEu: data.idEu
										,userType: data.userType
										,moduleType: Ext.getCmp( 'menu_winMod' + module ).value
									}
									,callback: function(){
										gridLoading = 0;
										grd.getView().refresh()
									}
								})
							}
						}
					});
				}
			
			
			
			showModuleForm();
			
			
			
			var employeeUserNameMA_winModHolder = Ext.getCmp('employeeUserNameMA_winMod'+module)
			employeeUserNameMA_winModHolder.store.load({
				callback: function() {
					Ext.getCmp('employeeUserNameMA_winMod'+module).setValue(data.idEu);
					Ext.getCmp('employeeUserNameMA_winMod'+module).fireEvent('select');;
				}
			}); 
			
			// Ext.getCmp( 'grdEmpBenefits' + module ).store.load( { params: { idEmployee:parseInt(response.idEmployee) } })
			
			// 'menu_winMod' + module
			
			// var menuCmbList = Ext.getCmp( 'menu_winMod' + module );
			// menuCmbList.store.load({
				// callback: function(){
					/* var employeeUserNameMA_winModHolderConsole= menuCmbList.setValue(parseInt(employeeUserNameMA_winModHolder.value)); */
					// menuCmbList.setValue(parseInt(employeeUserNameMA_winModHolder.value));
					// menuCmbList.fireEvent('select');
					
					// consonle.log(employeeUserNameMA_winModHolder.value);
					
				// } 
			// });
			

        }
		
		function saveModules( data ){
			// { if(parseInt(item.data.selected) == 1){ affiliateListContainer.push( item.data ) } }
			
			// console.log(data);
			
			// return false;
			
			var moduleListContainer 	= new Array();
			Ext.getCmp( "gridModules" + module ).store.each(
				function( item ) { 
				
					// console.log(item);
					// console.log(item.data.chk);
					if( item.data.chk ){ moduleListContainer.push( item.data ) } 
				} 
			)
			
			// return false;
			
			var gridEmployeeUserNameMA_winMod = Ext.getCmp( 'employeeUserNameMA_winMod' +module )
			var idEuHolder = gridEmployeeUserNameMA_winMod.getValue()
			Ext.Ajax.request({
				url: route + 'saveModules'
				,params:{
					moduleList : Ext.encode( moduleListContainer )
					,idEu 			: idEuHolder
					,fullName 		: data.name
					,userName 		: data.username
					,userType 		: data.userTypeNum
					,userTypeName 	: data.userType
					,moduleType		: Ext.getCmp( 'menu_winMod' + module ).getValue()
					,idmodule	: 3
				}
				,success: function(){
					
				}
			})			
		}

        function _viewAffiliate( data ){
			
			Ext.create('Ext.window.Window',{
				id : 'windowViewEmployeeAffiliate'+module
				,width : 480
				,height : 420
				,modal : true
				,closable : false
				,resizable : false
				,items : [
					Ext.create('Ext.form.Panel',{
						id : 'viewEmployeeAffiliateForm'+module
						,border : false
						,bodyPadding: 5
						,items:[
							{  xtype: 'container'
                                ,style: 'padding:5px;margin-top:10px;'
                                ,items: [
                                    _gridViewEmployeeAffiliateDetails( data )
                                ]
                            }
						]
						,buttons: [
								{	text: 'Close'
								,iconCls: 'glyphicon glyphicon-remove'
								,handler: function(){
									Ext.getCmp( 'windowViewEmployeeAffiliate'+module ).destroy( true );
								}
							}
						]
					} )
				]
			}).show();
			
        }
		
		function _deleteRecord( data ){
			
			standards.callFunction('_createMessageBox',{
				msg		: 'DELETE_CONFIRM'
				,action	: 'confirm'
				,fn 	: function ( btn ){
					if( btn == 'yes' ){

						if(Ext.getConstant('EMPLOYEEID') == data.idEmployee ) {
							standards.callFunction('_createMessageBox',{
								msg: 'User cannot be deleted. You are currently logged in to this account.'
							});
						} else {
							Ext.Ajax.request({
								url: route + 'deleteEmployeeRecord'
								,params:{ idEu: data.idEu, idEmployee: data.idEmployee, employeeName: data.name, employeeUserName: data.username }
								,method:'post'
								,success: function(response, option){
									// console.log (response.responseText);
									
									var responseValue = Ext.decode(response.responseText);		
									
									
									var responseValue = Ext.decode( response.responseText );
									if ( parseInt( responseValue.match ) == 1 ){
										standards.callFunction('_createMessageBox',{
											msg: 'This user can no longer be deleted. This user is connected with other transaction/s.'
										})
									}else if( parseInt( responseValue.match ) == 2 ){
										standards.callFunction('_createMessageBox',{
											msg: 'This user is already deleted. Please select another user.'
										})
									}else{
										var grdAffiliate = Ext.getCmp( 'gridHistory' + module ).store.load(); 
										standards.callFunction('_createMessageBox',{ msg: 'DELETE_SUCCESS' })
									}
									
									
								}
								,failure: function(){ }
							});
						}
						
					}
				}
			})
        }
		
		function _gridViewEmployeeAffiliateDetails( data ) {
            var storeViewEmployeeAffiliateDetails = standards.callFunction( '_createRemoteStore' , {
                fields:[
                    { name    : 'idEmployee' ,type   : 'number' }
                    ,'affiliateName'
                ]
                ,url: route + 'retrieveViewEmployeeAffiliateDetails'
            })
			
			storeViewEmployeeAffiliateDetails.proxy.extraParams.idEmployee = data.idEmployee
			
			// supplierCmbStore.proxy.extraParams.idAffiliate = Ext.getCmp( 'idAffiliate' + module ).getValue();

            return standards.callFunction('_gridPanel', {
                id : 'grdViewEmployeeAffiliateDetails' + module
                ,module : module
                ,store : storeViewEmployeeAffiliateDetails
                ,height: 320
                ,noPage: false
				,tbar:{ }				
                ,columns: [
                    {	header: 'Affiliate Name'
                        ,dataIndex: 'affiliateName'
                        ,width: 100
                        ,sortable: false
						,flex: 1
                    }
                ]
                ,listeners: {
                    afterrender: function(){
                        storeViewEmployeeAffiliateDetails.load( {
                            params: { idEmployee: data.idEmployee }
                        } )
                    }
                }
            })
        }
        
        function _resetForm( form ){
			Ext.getCmp('mainFormID' + module).getForm().reset();
			onEdit = 0;
			var grdAffiliate = Ext.getCmp( 'grdAffiliate' + module ); 
			grdAffiliate.store.removeAll()
			grdAffiliate.store.load()
			
			Ext.getCmp( 'grdEmpBenefits' + module ).store.removeAll() //Reset Benefits Grid
			Ext.getCmp( 'grdEmploymentHistoryDates' + module ).store.removeAll() //Reset Employment Dates Grid
			Ext.getCmp( 'grdEmploymentHistoryCR' + module ).store.removeAll() //Reset Position and Rate Grid
			Ext.getCmp( 'gridContributionAndTax' + module ).store.removeAll(); //Reset Contribution and Tax Grid
			
			// Ext.getCmp('password'+module).allowBlank  = false;
			// Ext.getCmp('confirm'+module).allowBlank  = false;
			Ext.getCmp('password'+module).allowBlank  = true;
			Ext.getCmp('confirm'+module).allowBlank  = true;
			Ext.getCmp('password'+module).validate();
			Ext.getCmp('confirm'+module).validate();
			Ext.getCmp('password'+module).setVisible(1);
			Ext.getCmp('confirm'+module).setVisible(1);			
			Ext.getCmp('password'+module).validate();
			Ext.getCmp('confirm'+module).validate();
			Ext.getCmp('tabPanelID' + module).setActiveTab(0);
			
			// Ext.getCmp('password'+module).allowBlank  = true;
			// Ext.getCmp('confirm'+module).allowBlank  = true;
			// Ext.getCmp('password'+module).validate();
			// Ext.getCmp('confirm'+module).validate();
			// Ext.getCmp('password'+module).hide();
			// Ext.getCmp('confirm'+module).hide();
			
        }
		
        function _contributionHistory( data ){
			Ext.create('Ext.window.Window',{
				id : 'windowViewContributionHistory'+module
				,width : 480
				,title: 'Contribution History '
				,height : 420
				,modal : true
				,closable : false
				,resizable : false
				,items : [
					Ext.create('Ext.form.Panel',{
						id : 'viewEmployeeAffiliateForm'+module
						,border : false
						,bodyPadding: 5
						,items:[
							{  xtype: 'container'
                                ,style: 'padding:5px;margin-top:10px;'
                                ,items: [
                                    _gridViewContributionHistoryDetails( data )
                                ]
                            }
						]
						,buttons: [
							{
								text: 'Close'
								,iconCls: 'glyphicon glyphicon-remove'
								,handler: function(){
									Ext.getCmp( 'windowViewContributionHistory'+module ).destroy( true );
								}
							}
						]
					} )
				]
			}).show();	
        }
		
		function _gridViewContributionHistoryDetails ( data ){
			var storeViewContributionHistoryDetails = standards.callFunction( '_createRemoteStore' , {
                fields:[
                    { name    : 'idEmpContributionHistory' ,type   : 'number' }
                    ,{ name    : 'idEmployee' ,type   : 'number' }
                    ,{ name    : 'idcontribution' ,type   : 'number' }
                    ,'contributionName'
                    ,'amount'
                    ,'effectivityDate'
                ]
                ,url: route + 'retrieveViewContributionHistory'
            })

            return standards.callFunction('_gridPanel', {
                id : 'grdViewContributionHistoryDetails' + module
                ,module : module
                ,store : storeViewContributionHistoryDetails
                ,height: 320
                ,noPage: false
				,tbar:{ }				
                ,columns: [
                    {	header: 'Contribution'
                        ,dataIndex: 'contributionName'
                        ,width: 100
                        ,sortable: false
						,flex: 1
                    }
                    ,{  header      : 'Effectivity Date'
                        ,dataIndex  : 'effectivityDate'
                        ,width      : 150
                        ,xtype      : 'datecolumn'
                        ,format     : 'm/d/Y'
                        ,editor     : 'date'
                    }
					,{  header      : 'Amount'
                        ,dataIndex  : 'amount'
                        ,width      : 100
                        ,xtype      : 'numbercolumn'
                        ,format     : '0,000.00'
                        ,editor     : 'float'
                    }
					/* ,standards.callFunction( '_createActionColumn', {
						canEdit: canDelete
						,icon : 'remove'
						,tooltip : 'Delete'
						,Func : _deleteRecord
                    }) */
                ]
                ,listeners: {
                    afterrender: function(){
                        storeViewContributionHistoryDetails.load( {
                            params: { idEmployee: data.idEmployee,idcontribution: data.idcontribution }
                        } )
                    }
                }
            })
		}

		function _dateValidation( dateEmployed, dateCompared, msg ) {
			
			if ( dateEmployed.value >= dateCompared.value ){
				standards.callFunction('_createMessageBox',{ msg: msg })
				dateCompared.setValue( dateEmployed );
			}
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