/**
 * Developer: Hazel Alegbeleye
 * Module: Affiliate Settings
 * Date: Oct 25, 2019
 * Finished: 
 * Description: The Affiliate Settings module allows only authorized users to setup (add, edit, or delete) affiliate details.
 * DB Tables: affiliate, employee
 * */ 
function Affiliatesettings(){
    return function(){
        var baseurl, route, module, canDelete, pageTitle, canPrint, isGae, onEdit, idAffiliate = "", affiliate, dateStart = new Date();

        var sm = new Ext.selection.CheckboxModel( {
            checkOnly   : true
        } );

		function _init(){

		}

		function _mainPanel( config ){

            var acctngSched   = standards.callFunction( '_createLocalStore', {
                data        : [ 'Calendar Year', 'Fiscal Year' ]
                ,startAt    : 1
            }), approver1Store = standards.callFunction( '_createRemoteStore', {
                fields      : [ 'name', { name: 'idEmployee', type: 'number'} ]
                ,url        : route + 'getApprovers'
            } ), approver2Store = standards.callFunction( '_createRemoteStore', {
                fields      : [ 'name', { name: 'idEmployee', type: 'number'} ]
                ,url        : route + 'getApprovers' })
            ,statusStore   = standards.callFunction( '_createLocalStore', {
                data        : [ 'Active', 'Inactive' ]
                ,startAt    : 1
            }), fiscalStore = standards.callFunction( '_createLocalStore', {
                data    : [
                    'January'
                    ,'February'
                    ,'March'
                    ,'April'
                    ,'May'
                    ,'June'
                    ,'July'
                    ,'August'
                    ,'September'
                    ,'October'
                    ,'November'
                ]
                ,startAt    : 1
            });
            
            return standards.callFunction(	'_mainPanel' ,{
                config		: config
                ,moduleType	: 'form'
                ,tbar : {
                    saveFunc        : _saveForm
                    ,resetFunc      : _resetForm
                    ,noExcelButton  : true
                    ,listLabel      : 'List'
                    ,customListPDFHandler	: _printPDF
                    ,filter : {
                        searchURL       : route + 'getAffiliates'
                        ,emptyText      : 'Search affiliate name...'
                    }
                    ,canPrint               : canPrint
                    ,customListPDFHandler   : _printPDF
                    ,route                  : route
                    ,pageTitle              : pageTitle
                }
                ,formItems:[
                    {   xtype       : 'fieldset'
                        ,title      : 'Details'
                        ,layout     : 'fit'
                        ,padding    : 10
                        ,items      : [
                            {
                                xtype       :'container'
								,layout     :'column'
								,style      : 'margin-bottom: 10px;'
								,items      :[   
                                    {
                                        xtype   : 'container'
                                        ,items  :[
                                            {
                                                xtype   :'container'
                                                ,layout :'column'
                                                ,style  : 'margin-bottom: 10px;'
                                                ,items  :[
                                                    standards.callFunction( '_createTextField', {
                                                        fieldLabel  : 'Affiliate Name'
                                                        ,id         : 'affiliateName' + module
                                                        ,allowBlank : false
                                                        ,width      : 380
                                                        ,maxLength  : 50
                                                        ,labelWidth : 115
                                                    } )
                                                    ,standards.callFunction( '_createCheckField', {
                                                        id              : 'mainTag' + module
                                                        ,boxLabel       : '(Main Affiliate)'
                                                        ,style          : 'margin-left : 10px; color:red;'
                                                        ,width          : 150
                                                        ,maxLength      : 50
                                                        ,listeners      : {
                                                            afterrender : function(){
                                                                this.setVisible( false );
                                                            }
                                                        }
                                                    } )
                                                ]
                                            }
                                            ,standards.callFunction( '_createTextArea', {
                                                id          : 'tagLine' + module
                                                ,fieldLabel : 'Tag Line'
                                                ,width      : 380
                                                ,maxLength  : 50
                                                ,labelWidth : 115
                                            } )
                                            ,standards.callFunction( '_createTextArea', {
                                                id          : 'address' + module
                                                ,fieldLabel : 'Address'
                                                ,width      : 380
                                                ,maxLength  : 50
                                                ,labelWidth : 115
                                            } )
                                            ,standards.callFunction( '_createTextField', {
                                                fieldLabel  : 'Contact Person'
                                                ,id         : 'contactPerson' + module
                                                ,width      : 380
                                                ,maxLength  : 50
                                                ,labelWidth : 115
                                            } )
                                            ,standards.callFunction( '_createTextField', {
                                                fieldLabel  : 'Contact Number'
                                                ,id         : 'contactNumber' + module
                                                ,isDecimal  : true	
                                                ,maskRe     : /[0-9.]/	
                                                ,width      : 380
                                                ,maxLength  : 20
                                                ,labelWidth : 115
                                            } )
                                            ,standards.callFunction( '_createTextField', {
                                                fieldLabel  : 'Email'
                                                ,id         : 'email' + module
                                                ,vtype      : 'email'
                                                ,width      : 380
                                                ,maxLength  : 50
                                                ,labelWidth : 115
                                            } )
                                            ,
                                            ,standards.callFunction( '_createTextField', {
                                                fieldLabel  : 'TIN'
                                                ,id         : 'tin' + module
                                                ,allowBlank : false
                                                ,width      : 380
                                                ,maxLength  : 20
                                                ,labelWidth : 115
                                                ,isDecimal  : true
                                                ,maskRe     : /[0-9.]/	
                                            } )
                                            ,{
                                                xtype   :'container'
                                                ,layout :'column'
                                                ,style  : 'margin-bottom: 10px;'
                                                ,items  :[
                                                    {	xtype: 'label'
                                                        ,text: 'Vat Percent:'
                                                    }
                                                    ,standards.callFunction( '_createCheckField', {
                                                        id              : 'vatType' + module
                                                        ,boxLabel       : '(Check if inclusive)'
                                                        ,style          : 'margin-left : 55px'
                                                        ,width          : 150
                                                        ,maxLength      : 50
                                                    } )
                                                    ,standards.callFunction( '_createTextField', {
                                                        id          : 'vatPercent' + module
                                                        ,isNumber : true
                                                        ,width      : 50
                                                        ,style      : 'margin-left : 50px'
                                                    } )
                                                    ,{	xtype: 'label'
                                                        ,text: '%'
                                                    }
                                                ]
                                            }
                                            ,standards.callFunction( '_createCombo', {
                                                id              : 'status' + module 
                                                ,fieldLabel     : 'Status'
                                                ,store          : statusStore
                                                ,displayField   : 'name'
                                                ,valueField     : 'id'
                                                ,value          : 1
                                                ,width          : 380
                                                ,labelWidth     : 115
                                                ,hidden         : true
                                            } )
                                            ,{
                                                xtype       : 'fieldset'
                                                ,title      : 'Signatories'
                                                ,padding    : 10
                                                ,items  :[
                                                    ,standards.callFunction( '_createTextField', {
                                                        fieldLabel: 'Checked By:'
                                                        ,id             : 'checkedBy' + module
                                                        ,width          : 380
                                                        ,maxLength      : 50
                                                        ,labelWidth     : 115
                                                        ,style          : 'margin-bottom: 5px;'
                                                    } )
                                                    ,standards.callFunction( '_createTextField', {
                                                        fieldLabel      : 'Reviewed By'
                                                        ,id             : 'reviewedBy' + module
                                                        ,width          : 380
                                                        ,maxLength      : 50
                                                        ,labelWidth     : 115
                                                        ,style          : 'margin-bottom: 5px;'
                                                    } )
                                                    // ,standards.callFunction( '_createCombo', {
                                                    //     id              : 'approvedBy1' + module
                                                    //     ,fieldLabel     : 'Approved By (1)'
                                                    //     ,store          : approver1Store
                                                    //     ,displayField   : 'name'
                                                    //     ,valueField     : 'idEmployee'
                                                    //     ,labelWidth     : 115
                                                    //     ,width          : 380
                                                    //     ,style          : 'margin-bottom: 5px;'
                                                    //     ,emptyText      : 'Select name...'
                                                    //     ,listeners      : {
                                                    //         beforeQuery : function( qe ) {
                                                    //             var approver2 = Ext.getCmp( 'approvedBy2' + module ).getValue();
                                                    //             approver1Store.proxy.extraParams.idEmployee = parseInt( approver2, 10 );
                                                    //         }
                                                    //     }
                                                    // } )
                                                    // ,standards.callFunction( '_createCombo', {
                                                    //     id              : 'approvedBy2' + module
                                                    //     ,fieldLabel     : 'Approved By (2)'
                                                    //     ,store          : approver2Store
                                                    //     ,displayField   : 'name'
                                                    //     ,valueField     : 'idEmployee'
                                                    //     ,labelWidth     : 115
                                                    //     ,width          : 380
                                                    //     ,style          : 'margin-bottom: 5px;'
                                                    //     ,emptyText      : 'Select name...'
                                                    //     ,listeners      : {
                                                    //         beforeQuery : function( qe ) {
                                                    //             var approver1 = Ext.getCmp( 'approvedBy1' + module ).getValue();
                                                    //             approver2Store.proxy.extraParams.idEmployee = parseInt( approver1, 10 );
                                                    //         }
                                                    //     }
                                                    // } )
                                                ]
                                            }
                                        ]
                                    }
                                    ,{
                                        xtype   : 'container'
                                        ,id     : 'two-col' + module
                                        ,style  : 'margin-left: 100px;'
                                        ,items: [
                                            ,{
                                                xtype       : 'container'
                                                ,style      : 'margin-left : 130px'
                                                ,items      : [
                                                    {
                                                        xtype   : 'image'
                                                        ,mode   : 'image'
                                                        ,style  : 'border: 1px solid black; marginBottom: 10px; margin-left:10px '
                                                        ,src    : ( isGae ? Ext.getConstant( 'DEFAULT_IMAGE_BIN' ) : Ext.getConstant( 'LOGOPATH' ) + Ext.getConstant( 'DEFAULT_EMPTY_IMG' ) )
                                                        ,width  : 300
                                                        ,height : 125
                                                        ,id     : 'imageBox' + module       
                                                    }
                                                   ,{
                                                        xtype   :'container'
                                                        ,layout :'column'
                                                        ,style  : 'margin-bottom: 10px;'
                                                        ,items  :[
                                                            ,standards.callFunction( '_createFileUpload', {
                                                                id          : 'logo' + module
                                                                ,buttonOnly : false
                                                                ,width      : 280
                                                                ,iconCls    : 'glyphicon glyphicon-folder-open'
                                                                ,style      : 'background: gradient(to right, #007fe5, #007fe5);'
                                                                ,imageBox   : 'imageBox' + module
                                                                ,style      :'margin-left:10px; margin-right:2px'
                                                            })
                                                            ,{
                                                                xtype       : 'button'
                                                                ,iconCls    : 'glyphicon glyphicon-refresh'
                                                                ,style      : 'background:gradient(to right, #007fe5, #007fe5)'
                                                                ,handler: function(){
                                                                    console.warn( Ext.getConstant( 'imageBin' ) );
                                                                    console.warn( Ext.getConstant( 'DEFAULT_EMPTY_IMG' ) );
                                                                    Ext.getCmp( 'imageBox' + module ).setSrc( Ext.getConstant( 'LOGOPATH' ) + Ext.getConstant( 'DEFAULT_EMPTY_IMG' ) );
                                                                    Ext.getCmp( 'logo' + module ).reset();
                                                                }          
                                                            }
                                                        ]
                                                    }
                                                ]
                                            }
                                            ,{
                                                xtype       : 'container'
                                                // ,style      : 'margin-left : 100px'
                                                ,items: [
                                                    ,{
                                                        xtype       : 'fieldset'
                                                        ,layout     : 'fit'
                                                        ,padding    : 5
                                                        ,style      : 'margin-left : 140px'
                                                        ,items  :[
                                                            ,standards.callFunction( '_createCheckField', {
                                                                id              : 'refTag' + module 
                                                                ,boxLabel       : 'Distribute document reference to cost centers.'
                                                                ,width          : 290
                                                                ,maxLength      : 50
                                                            } )
                                                        ]
                                                    }
                                                    ,{  xtype:'container'
                                                        ,layout:'column'
                                                        ,style: 'margin-bottom: 10px;'
                                                        ,items  : [
                                                            ,standards.callFunction( '_createCombo', {
                                                                id              : 'accSchedule' + module
                                                                ,fieldLabel     : 'Accounting Schedule'
                                                                ,allowBlank     : false
                                                                ,store          : acctngSched
                                                                ,displayField   : 'name'
                                                                ,valueField     : 'id'
                                                                ,value          : 1
                                                                ,width          : 300
                                                                ,emptyText      : 'Select accounting schedule...'
                                                                ,listeners      : {
                                                                    select  : function() {
                                                                        _checkAccountingSched();
                                                                    }
                                                                    ,afterrender : function() {
                                                                        _checkAccountingSched();
                                                                    }
                                                                }
                                                            } )
                                                            ,standards.callFunction( '_createCombo', {
                                                                id              : 'month' + module
                                                                ,fieldLabel     : ''
                                                                ,value          : new Date().getMonth() + 1
                                                                ,width          : 135
                                                                ,store          : fiscalStore
                                                                ,labelWidth     : 1
                                                                ,style          : 'margin-left : 8px'
                                                                ,emptyText      : 'Select month...'
                                                            } )
                                                            ,standards.callFunction( '_createTextField', {
                                                                fieldLabel      : ''
                                                                ,id             : 'calendarSched' + module
                                                                ,width          : 135
                                                                ,maxLength      : 50
                                                                ,labelWidth     : 115
                                                                ,style          : 'margin-left : 8px'
                                                                ,labelWidth     : 1
                                                                ,setReadOnly    : true
                                                                ,value          : 'December'
                                                            } )
                                                        ]
                                                    }
                                                    ,standards.callFunction( '_createDateField', {
                                                        id			: 'dateStart' + module
                                                        ,fieldLabel	: 'Date Start'
                                                        ,allowBlank	: false
                                                        ,width      : 445
                                                    } )
                                                    ,standards.callFunction( '_createTextArea', {
                                                        id          : 'remarks' + module
                                                        ,fieldLabel : 'Remarks'
                                                        ,width      : 445
                                                        ,maxLength  : 50
                                                        ,labelWidth : 135
                                                    } )

                                                ]
                                            }
                                        ]
                                    }
                                ]
                                
                            }
                        ]
                    }
                    ,__approverGrid()
                ]
                ,listItems: gridHistory()
                ,listeners:{
                    afterrender : function(){}
                }
			} );
        }

        function gridHistory(){
            var affiliateStore = standards.callFunction( '_createRemoteStore' , {
                fields:[ 'idAffiliate'
                , 'affiliateName'
                , 'address'
                , 'contactPerson'
                , 'contactNumber'
                , 'email'
                , 'tin'
                , 'status'
                , {
                    name: 'mainTag'
                    , type: 'boolean'
                } ]
                ,url: route + 'getAffiliates'
            });

            var checkListeners = {
				checkchange: function( me, rowIndex, checked ){
                    if( checked ) {
                        var store = Ext.getCmp('gridHistory' + module).getStore().data.items;
                        var mainTag = 0;

                        store.map( (col, i) => {
                            selectedAffiliate = Ext.getCmp('gridHistory' + module).getStore().getAt(i)

                            if( i !== rowIndex ) {
                                selectedAffiliate.set('mainTag', 0);
                                mainTag = 0;
                            } else {
                                mainTag = 1;
                            }

                            Ext.Ajax.request({
                                url     : route + 'setMainTag'
                                ,params : {
                                    idAffiliate : selectedAffiliate.data.idAffiliate
                                    ,mainTag    : mainTag
                                }
                                ,method     : 'post'
                                ,success    : function( response ){}
                            });

                        });
                    }
				}
            };
            
            return standards.callFunction('_gridPanel', {
                id : 'gridHistory' + module
                ,module     : module
                ,store      : affiliateStore
                ,height     : 265
                ,columns: [
                    {	header      : 'Affiliate Name'
                        ,dataIndex  : 'affiliateName'
                        ,flex       : 1
                        ,minWidth   : 80
                        ,sortable   : true
                    }
                    ,{	header      : 'Address'
                        ,dataIndex  : 'address'
                        ,flex       : 1
                        ,minWidth   : 80
                        ,sortable   : true
                    }
                    ,{	header      : 'Contact Person'
                        ,dataIndex  : 'contactPerson'
                        ,width      : 120
                        ,sortable   : true
                    }
                    ,{	header      : 'Contact Number'
                        ,dataIndex  : 'contactNumber'
                        ,width      : 120
                        ,sortable   : true
                    }
                    ,{	header      : 'Email'
                        ,dataIndex  : 'email'
                        ,width      : 120
                        ,sortable   : true
                    }
                    ,{	header      : 'TIN'
                        ,dataIndex  : 'tin'
                        ,width      : 120
                        ,sortable   : true
                    }
                    ,{	header      : 'Status'
                        ,dataIndex  : 'status'
                        ,width      : 100
                        ,sortable   : true
					}
                    ,{
						header      : 'Main'
						,dataIndex  : 'mainTag'
						,xtype      : 'checkcolumn'
                        ,sortable   : false
                        ,width      : 55
						,listeners  :checkListeners
					}
                    ,standards.callFunction( '_createActionColumn', {
                        canEdit     : canEdit
                        ,icon       : 'pencil'
						,tooltip    : 'Edit'
                        ,width      : 30
                        ,Func       : updateAffiliate
                    })
                    ,standards.callFunction( '_createActionColumn', {
                        canEdit     : canEdit
                        ,icon       : 'remove'
						,tooltip    : 'Delete'
						,width      : 30
                        ,Func       : deleteAffiliate
                    })
                ]
                ,listeners: {
                    afterrender: function(){
                        affiliateStore.load({})
                    }
                }
            })
        }
        
        function _saveForm( form ) {

            let path = Ext.getCmp( 'logo' + module ).getValue();
            let ind = path.lastIndexOf("\\") + 1;
			let fileName = path.substr(ind);

            var params = {
                affiliateName           : Ext.getCmp('affiliateName' + module).getValue()
                ,tagLine                : Ext.getCmp('tagLine' + module).getValue()
                ,address                : Ext.getCmp('address' + module).getValue()
                ,contactPerson          : Ext.getCmp('contactPerson' + module).getValue()
                ,contactNumber          : Ext.getCmp('contactNumber' + module).getValue()
                ,email                  : Ext.getCmp('email' + module).getValue()
                ,tin                    : Ext.getCmp('tin' + module).getValue()
                ,vatPercent             : Ext.getCmp('vatPercent' + module).getValue()
                ,vatType                : ( Ext.getCmp('vatType' + module).getValue() == true ) ? 1  : 2
                ,checkedBy              : Ext.getCmp('checkedBy' + module).getValue()
                ,reviewedBy             : Ext.getCmp('reviewedBy' + module).getValue()
                ,accSchedule            : Ext.getCmp('accSchedule' + module).getValue()
                ,month                  : ( Ext.getCmp('accSchedule' + module).getValue() == 1 ) ? 12 : Ext.getCmp('month' + module).getValue()
                ,remarks                : Ext.getCmp('remarks' + module).getValue()
                ,refTag                 : ( Ext.getCmp('refTag' + module).getValue() == true ) ? 1 : 2
                ,logo                   : fileName
                ,status                 : Ext.getCmp('status' + module).getValue()
                ,mainTag                : ( Ext.getCmp('mainTag' + module).getValue() == true ) ? 1 : 0
                ,idAffiliate            : idAffiliate
                ,onEdit                 : ( onEdit ? 0 : 1)
                ,dateStart              : Ext.getCmp('dateStart' + module).getValue()
                ,module                 : module
                ,approvers              : Ext.encode( Ext.getCmp('gridApprovers'+module).store.data.items.map((item)=>item.data) )
            }

            if( onEdit ) params.idAffiliate = idAffiliate;
            
            var notEmpty = 0;
            Ext.getCmp( 'gridApprovers' + module ).store.each( function( item ){  
                if( item.data.name != "" ){ notEmpty++; } 
            })

            switch( true ){
                case !emailIsValid( params.email ):
                    standards.callFunction( '_createMessageBox', {
                        msg: 'Please input a valid email address.'
                    } )
                    break;
                case !(notEmpty>0):
                    standards.callFunction( '_createMessageBox', {
                        msg: 'Please input atleast 1 approver.'
                    } )
                    break;
                default:
                    form.submit({
                        url         : route + 'saveForm'
                        ,params     : params
                        ,success    : function(action, response){
                            var resp    = Ext.decode( response.response.responseText );

                             /* MATCH
                                0 - SUCCESS
                                1 - FAILED
                                2 - EXISTS
                            */

                            if( resp.match == 0 ){
                                if( resp.mainTag > 0 ){
                                    standards.callFunction( '_createMessageBox', {
                                        msg : 'Record has been successfully saved.'
                                        ,fn : function(){
                                            _resetForm( form );
                                        }
                                    } );
                                } else {
                                    standards.callFunction( '_createMessageBox', {
                                        msg         : 'Record has been successfully saved. Would you like to set this as Main Affiliate?'
                                        ,buttons    : 'yesno'
                                        ,fn: function( btn ){
                                            
                                            if( btn == 'yes' ) {
                                                Ext.Ajax.request({
                                                    url: route + 'setMainTag'
                                                    ,params: { 
                                                        idAffiliate : resp.idAffiliate
                                                        ,mainTag    : 1
                                                    }
                                                    ,method : 'post'
                                                    ,success: function( response ){}
                                                })
                                            }
                                            _resetForm( form );
                                        }
                                    } );
                                } 
                            } else {
                                standards.callFunction( '_createMessageBox', {
                                    msg : 'Affiliate name already exists. Please select a different name.'
                                } );
                            }
                        }
                    });
                    break;
            }
            
        }

        function emailIsValid( email ){
            let re = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
            if( email === '' ) return true;
            return ( !re.test( email ) ) ? false : true;
        }

        function _resetForm( form ) {
            form.reset();
            Ext.getCmp('gridHistory' + module).store.load();
            Ext.getCmp('gridApprovers'+module).store.proxy.extraParams = {};
            Ext.getCmp('gridApprovers'+module).store.load();
            Ext.getCmp( 'logo' + module ).reset();
            Ext.getCmp( 'mainTag' + module ).setVisible( false );
            setImage( null );
            onEdit = false;
            idAffiliate = "";
        }
        
        function deleteAffiliate( data ) {
            if( data.idAffiliate != affiliate ){
                standards.callFunction( '_createMessageBox', {
                    msg		: 'DELETE_CONFIRM'
                    ,action	: 'confirm'
                    ,fn		: function( btn ){
                        if( btn == 'yes' ){
                            Ext.Ajax.request({
                                url: route + 'deleteAffiliate'
                                ,params: {
                                    idAffiliate : data.idAffiliate
                                }
                                ,method : 'post'
                                ,success: function( response ){
                                    let resp = Ext.decode( response.responseText)
                                    ,msg = '', gridHistory = Ext.getCmp('gridHistory' + module);

                                    switch( resp.view ) {
                                        case 1:
                                            msg = 'Invalid action. Main Affiliate cannot be deleted.';
                                            break;
                                        case 2:
                                            msg = 'DELETE_USED';
                                            break;
                                        case 0:
                                            msg = 'DELETE_SUCCESS';
                                            break;
                                    }

                                    standards.callFunction( '_createMessageBox', { 
                                        msg : msg
                                        ,fn: function(){
                                            _resetForm( module.getForm() );
                                        }
                                     });

                                    gridHistory.getStore().currentPage = 1;
                                    gridHistory.getStore().load({});

                                }
                            })
                        }
                    }
                })
            } else {
                standards.callFunction( '_createMessageBox', {
                    msg : 'Record cannot be deleted. You are currently logged in to this affiliate.'
                } );
            }
            
        }

        function updateAffiliate( data ) {
            module.getForm().retrieveData( {
				url     : route + 'getAffiliate'
				,method : 'post'
				,params : {
                    idAffiliate : data.idAffiliate
                }
				,success:function( response, match, params ){
                    onEdit = true;
                    idAffiliate = data.idAffiliate;
                    dateStart = data.dateStart;

                    setImage(response.logo);

                    // Ext.getCmp('approvedBy1'+module).getStore().load();
                    // Ext.getCmp('approvedBy1'+module).setValue(1);
                    Ext.getCmp('status' + module).setVisible( true );

                    if( params.mainTag > 0 ) Ext.getCmp( 'mainTag' + module ).setVisible( true );
                    if( response.mainTag == 1 ) Ext.getCmp( 'mainTag' + module ).setValue( response.mainTag );
                    if( params.rec_match == 1 ) setReadOnly();

                    _checkAccountingSched();

                    var mainTag = Ext.getCmp( 'mainTag' + module );
                    /* Check if there has been an assigned Main Affiliate */
                    if( params.mainTag > 0 ) { 
                        if( response.mainTag == 1 ) {
                            mainTag.setValue( response.mainTag );
                        } else {
                            mainTag.setVisible( false );
                        }
                    } else {
                        mainTag.setVisible( true );
                    }

                    /** Load Approvers grid **/
                    Ext.getCmp('gridApprovers'+module).store.proxy.extraParams.idAffiliate = idAffiliate;
                    Ext.getCmp('gridApprovers'+module).store.load();

				}
			} )
        }

        function _setNavChanges(){
            //Mainview.js
        }

        function setImage( fileName ){
            let logo = ( fileName == null ) ? 'default-no-img.jpg' : fileName;
            var imageVal = Ext.getConstant('LOGOPATH') + logo;
            Ext.getCmp( 'imageBox' + module ).setSrc( imageVal );
        }

        function setReadOnly(){
            Ext.getCmp( 'accSchedule' + module ).setReadOnly(true);
            Ext.getCmp( 'dateStart' + module ).setReadOnly(true);
        }

        function _checkAccountingSched() {
            let cmp         = Ext.getCmp('month' + module)
                calendarCmp = Ext.getCmp('calendarSched' + module);

            if( Ext.getCmp('accSchedule' + module).getValue() == 1 ) {
                calendarCmp.setVisible(true);
                calendarCmp.setReadOnly(true);
                cmp.setVisible(false);
            } else {
                cmp.setVisible(true);
                calendarCmp.setVisible(false);
            }
        }

        function _printPDF(){
            Ext.Ajax.request({
                url     : route + 'printPDF'
                ,method :'post'
                ,params :{
                    idmodule    : 7
                    ,pageTitle  : pageTitle
                    ,limit      : 50
                    ,start      : 0
                    ,printPDF   : 1
                }
                ,success:function(response, action){
                    if( isGae == 1 ){
						window.open(route+'viewPDF/Affiliate List','_blank');
					}else{
						window.open('pdf/admin/Affiliate List.pdf');
					}
                }
            });
        }

        function __approverGrid(){

            var approverStore = standards.callFunction( '_createRemoteStore', {
				fields      : [ 'name', 'dateEffectivity', 'idEmployee']			
				,url        : route + 'getAffiliateApprovers'
            }), approver1Store = standards.callFunction( '_createRemoteStore', {
                fields      : [ 'name', { name: 'idEmployee', type: 'number'} ]
                ,url        : route + 'getApprovers'
            } );

            return {
                xtype : 'tabpanel'
                ,items: [
                    {
                        title: 'Approver(s)'
                        ,layout:{
                            type: 'card'
                        }
                        ,items  :   [
                            standards.callFunction( '_gridPanel',{
                                id		        : 'gridApprovers' + module
                                ,module	        : module
                                ,store	        : approverStore
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
                                    {	header          : 'Approver Name'
                                        ,dataIndex      : 'name'
                                        ,minWidth       : 250
                                        ,flex           : 1
                                        ,sortable       : false
                                        ,editor         : standards.callFunction( '_createCombo', {
                                            id              : 'approvedBy' + module
                                            ,fieldLabel     : ''
                                            ,store          : approver1Store
                                            ,displayField   : 'name'
                                            ,valueField     : 'name'
                                            ,emptyText      : 'Select name...'
                                            ,listeners      : {
                                                select: function( me, record ){
                                                    if( Ext.isUnique(me.valueField, approverStore, this, 'This employee is already selected. Please select another employee.') ) {
                                                        var row = this.findRecord(this.valueField, this.getValue())
                                                        var { 0 : selStore }   = Ext.getCmp('gridApprovers' + module).selModel.getSelection();

                                                        Ext.setGridData(['idEmployee', 'dateEffective', 'name'], selStore, row);
                                                    }
                                                }
                                            }
                                        } )
                                    }
                                    ,{	header          : 'Date Effective'
                                        ,dataIndex      : 'dateEffectivity'
                                        ,width          : 300
                                        ,sortable       : false
                                        ,editor         : 'date'
                                        // ,standards.callFunction( '_createDateField', {
                                        //     id			: 'dateEffectivity' + module
                                        //     ,fieldLabel	: ''
                                        //     ,listeners  : {
                                        //         afterrender: function(){
                                        //             // console.log( Ext.getCmp('dateStart' + module).getValue() );
                                        //         }
                                        //     }
                                        // } )
                                    }
                                ]
                                ,listeners	: {
                                    afterrender : function() {
                                        approverStore.proxy.extraParams = {};
                                        approverStore.load({});
                                    }
                                    ,edit : function( me, rowData ) {
                                        var index = rowData.rowIdx
                                        ,store = this.getStore().getRange();

                                        if( rowData.field === 'dateEffectivity' ){
                                            let dateStart = Ext.getCmp('dateStart'+module);
                                            if( Ext.dateParse( rowData.value ).isBefore( Ext.dateParse( dateStart.getValue() ) ) ){
                                                standards.callFunction( '_createMessageBox', {
                                                    msg : 'Invalid date. Date effectivity must be same or after affiliate date start.'
                                                    ,fn : function(){
                                                        store[index].set('dateEffectivity', dateStart.getValue() );
                                                    }
                                                } )
                                            }
                                        }
                                    }
                                }
                            })
                        ]
                    }
                ]
            }
        }

        return{
			initMethod:function( config ){
				route = config.route;
				baseurl = config.baseurl;
				module = config.module;
				canDelete = config.canDelete;
				canPrint = config.canPrint;
				pageTitle = config.pageTitle;
				isGae = config.isGae;
                canEdit = config.canEdit;
                config['canSave'] = config.canEdit;
                moduleID = config.idmodule;
                affiliate = config.idAffiliate;

				return _mainPanel( config );
			}
		}
    }
}