/**
 * Developer    : Jayson Dagulo
 * Module       : Financial Report
 * Date         : Jan 30, 2019
 * Finished     : 
 * Description  : This module allows authorized user to manually closes the journal entries.
 * DB Tables    : 
 * */ 
function Financialreport(){
    return function(){
        var baseurl, route, module, canDelete, pageTitle, canPrint, balanceModule, ledgerModule, isGae, idAffiliate
            ,paramsView;
    
        function _mainPanel( config ){
            var reportTypeStore     = standards.callFunction( '_createLocalStore', {
                    data : [
                        'Trial Balance'
                        ,'Income Statement'
                        ,'Balance Sheet'
                        ,'Cash Flow'
                    ]
                } )
                ,displayTypeStore   = standards.callFunction( '_createLocalStore', {
                    data : [
                        'Long'
                        ,'Short'
                    ]
                } );
            return standards.callFunction( '_mainPanel', {
                config		        : config
                ,moduleType	        : 'form'
                ,noHeader           : true
                ,formItems          : [
                    {   xtype       : 'container'
                        ,layout     : 'column'
                        ,items      : [
                            {   xtype           : 'container'
                                ,columnWidth    : .4
                                ,minWidth       : 365
                                ,items          : [
                                    standards2.callFunction( '_createAffiliateCombo', {
                                        hasAll      : Ext.getConstant( 'ISMAIN' )
                                        ,allValue   : 'All(Consolidated)'
                                        ,module     : module
                                        ,readOnly   : ( parseInt( Ext.getConstant( 'ISMAIN' ), 10 ) == 0 )
                                        ,allowBlank : true
                                        ,listeners  : {
                                            afterrender : function(){
                                                var me  = this;
                                                me.store.load( {
                                                    callback    : function(){
                                                        if( parseInt( Ext.getConstant( 'ISMAIN' ), 10 ) == 1 ){
                                                            if( me.store.getCount() > 1 ) me.setValue( 0 );
                                                            else{
                                                                if( me.store.getCount() > 0 ) me.setValue( me.store.getAt( 0 ).get( 'id' ) );
                                                            }
                                                        }
                                                        else me.setValue( parseInt( Ext.getConstant( 'AFFILIATEID' ), 10 ) );
                                                    }
                                                } )
                                            }
                                        }
                                    } )
                                    ,standards.callFunction( '_createCombo', {
                                        id              : 'reportType' + module
                                        ,fieldLabel     : 'Reporting Type'
                                        ,valueField     : 'id'
                                        ,displayField   : 'name'
                                        ,value          : 1
                                        ,store          : reportTypeStore
                                    } )
                                ]
                            }
                            ,{  xtype           : 'container'
                                ,columnWidth    : .5
                                ,minWidth       : 365
                                ,items          : [
                                    {  xtype    : 'container'
                                        ,style  : 'margin-bottom : 5px;'
                                        ,layout : 'column'
                                        ,items  : [
                                            standards.callFunction( '_cmbMonth', {
                                                fieldLabel  : 'Month'
                                                ,id         : 'month' + module
                                                ,allowBlank : false
                                                ,width      : 260
                                                ,style      : 'margin-right : 5px;'
                                                ,module     : module
                                                ,listeners  : {
                                                    select  : function(){
                                                        _reloadGrid();
                                                    }
                                                }
                                            } )
                                            ,standards.callFunction( '_createNumberField', {
                                                id                      : 'year' + module
                                                ,fieldLabel             : ''
                                                ,withREQ                : false
                                                ,allowBlank             : false
                                                ,decimalPrecision       : 0
                                                ,width                  : 85
                                                ,hideTrigger            : false
                                                ,minValue               : 1980
                                                ,maxValue               : ( ( new Date() ).getFullYear() + 1 )
                                                ,value                  : ( new Date() ).getFullYear()
                                                ,useThousandSeparator   : false
                                                ,listeners              : {
                                                    change  : function(){
                                                        _reloadGrid();
                                                    }
                                                }
                                            } )
                                        ]
                                    }
                                    ,standards.callFunction( '_createCombo', {
                                        id              : 'displayType' + module
                                        ,fieldLabel     : 'Display Type'
                                        ,valueField     : 'id'
                                        ,displayField   : 'name'
                                        ,value          : 1
                                        ,store          : displayTypeStore
                                    } )
                                ]
                            }
                        ]
                    }
                    ,{	xtype       : 'button'
						,iconCls    : 'glyphicon glyphicon-search'
						,text       : 'Generate Report'
						,style      : 'margin-right : 5px;'
						,handler    : function(){
							_viewReport( false );
						}
					}
					,{	xtype       : 'button'
						,iconCls    : 'glyphicon glyphicon-refresh'
						,text       : 'Reset'
						,style      : 'margin-right : 5px;'
						,handler    : function(){
							_resetAll();
						}
					}
					,{	xtype       : 'button'
						,id         : 'pdfBTN' + module
						,iconCls    : 'pdf-icon'
						,text       : 'Print PDF'
						,style      : 'margin-right : 5px;'
						,hidden     : true
						,handler    : function(){
							_printPDF();
						}
					}
					,{	xtype       : 'button'
						,id         : 'excelBTN' + module
						,iconCls    : 'excel'
						,text       : 'Print Excel'
						,hidden     : true
						,handler    : function(){
							_printExcel();
						}
                    }
					,{	xtype       : 'container'
						,html       : '<div id = "displayReport' + module + '"> </div>'
					}
                ]
            } )
        }

        function _viewReport( forPDF = false ){
            paramsView  = {
                idAffiliate         : Ext.getCmp( 'idAffiliate' + module ).getValue()
                ,affiliateName      : Ext.getCmp( 'idAffiliate' + module ).getRawValue()
                ,reportType         : Ext.getCmp( 'reportType' + module ).getValue()
                ,reportTypeName     : Ext.getCmp( 'reportType' + module ).getRawValue()
                ,month              : Ext.getCmp( 'month' + module ).getValue()
                ,monthName          : Ext.getCmp( 'month' + module ).getRawValue()
                ,year               : Ext.getCmp( 'year' + module ).getValue()
                ,displayType        : Ext.getCmp( 'displayType' + module ).getValue()
            };
            Ext.Ajax.request( {
                url         : route + 'generateReport'
                ,params     : paramsView
                ,success    : function( response ){
                    var resp    = Ext.decode( response.responseText )
                        ,match  = parseInt( resp.match, 10 );
                    switch( match ){
                        case 1: /* Month selected not yet closed */
                            standards.callFunction( '_createMessageBox', {
                                msg		: 'Selected month is not yet closed. If you want to proceed with generating this report, please create a closing entry.'
                            } );
                            break;
                        case 2: /* Selected month is lesser than the affiliate start date */
                            standards.callFunction( '_createMessageBox', {
                                msg		: 'Selected month and year is lesser than the ' + ( parseInt( Ext.getCmp( 'idAffiliate' + module ).getValue(), 10 ) > 0? 'affiliate start date' : 'main affiliate start date' ) + '.'
                            } );
                            break;
                        case 3: /* Previous month(s) closing entry not yet tagged as "Final" */
                            standards.callFunction( '_createMessageBox', {
                                msg : 'Previous month(s) closing journal entry is not yet tagged as "Final".'
                            } );
                            break;
                        default:
							document.getElementById( 'displayReport' + module ).innerHTML = resp.view;
							Ext.getCmp( 'mainFormPanel' + module ).doLayout();
							Ext.getCmp( 'pdfBTN' + module ).show();
							Ext.getCmp( 'excelBTN' + module ).show();
                            break;
                    }
                }
            } );
        }
        
        function _resetAll(){
            module.getForm().reset();
            Ext.getCmp( 'idAffiliate' + module ).fireEvent( 'afterrender' );
			document.getElementById( 'displayReport' + module ).innerHTML = null;
			Ext.getCmp( 'pdfBTN' + module ).hide();
			Ext.getCmp( 'excelBTN' + module ).hide();
        }

        function _printPDF(){
            paramsView['title']     = paramsView['reportTypeName'];
            paramsView['pdf']       = 1;
            paramsView['excel']     = null;
            Ext.Ajax.request( {
                url         : route + 'generateReport'
                ,params     : paramsView
                ,success    : function(res){
                    if( isGae ){
                        window.open( route + 'viewPDF/' + paramsView.title , '_blank' );
                    }
                    else{
                        window.open( baseurl + 'pdf/accounting/' + paramsView.title + '.pdf');
                    }
                }
            } );
        }

        function _printExcel(){
            paramsView['title']     = paramsView['reportTypeName'];
            paramsView['pdf']       = null;
            paramsView['excel']     = 1;
            Ext.Ajax.request( {
                url         : route + 'generateReport'
                ,params     : paramsView
                ,success    : function(res){
                    window.open( route + "download/" + paramsView.title + '/accounting');
                }
            } );
        }
        
        return{
			initMethod  : function( config ){
				route		    = config.route;
				baseurl		    = config.baseurl;
				module		    = config.module;
				canPrint	    = config.canPrint;
				canDelete	    = config.canDelete;
				canEdit		    = config.canEdit;
				pageTitle       = config.pageTitle;
				idModule	    = config.idmodule;
				
				return _mainPanel( config );
			}
		}
    }
}