/**
 * Developer    : Jayson Dagulo
 * Module       : Expiry Monitoring
 * Date         : Jan. 31, 2020
 * Finished     : 
 * Description  : This module allows authorized user to generate and monitor the expiration date of each item based on the specified date.
 * DB Tables    : 
 * */ 
function Expirymonitoring(){
    return function(){
        var baseurl, route, module, canDelete, pageTitle, canPrint, balanceModule, ledgerModule, isGae, idAffiliate;
        function _mainPanel( config ){
            return standards.callFunction(	'_mainPanel' ,{
                config		        : config
                ,moduleType	        : 'form'
                ,noHeader           : true
                ,layout             : 'fit'
                ,bodyPadding        : '0px'
                ,formItems          : _gridExpiryMonitoring()
                ,listeners          : {
                    afterrender     : function(){
                        _viewRecords();
                    }
                }
            } )
        }

        function _gridExpiryMonitoring(){
            var store       = standards.callFunction( '_createRemoteStore', {
                    fields      : [
                        'affiliateName'
                        ,'dateReceived'
                        ,'reference'
                        ,'barcode'
                        ,'itemName'
                        ,'itemName'
                        ,'className'
                        ,'unitName'
                        ,{  name    : 'qtyLeft'
                            ,type   : 'number'
                        }
                        ,'expiryDate'
                        ,'remainingDays'
                        ,'idModule'
                    ]
                    ,url        : route + 'getExpiryMonitoring'
                } )
                ,itemStore  = standards.callFunction( '_createRemoteStore', {
                    fields      : [
                        {   name    : 'idItem'
                            ,type   : 'number'
                        }
                        ,'itemName'
                    ]
                    ,url        : route + 'getItemName'
                } );
            return standards.callFunction( '_gridPanel', {
                id              : 'gridExpiryMonitoring' + module
				,module         : module
                ,store          : store
                ,noDefaultRow   : true
                ,tbar           : {
                    content     : [
                        standards.callFunction( '_createCombo', {
                            id              : 'idItem' + module
                            ,store          : itemStore
                            ,fieldLabel     : 'Search'
                            ,valueField     : 'idItem'
                            ,displayField   : 'itemName'
                            ,labelWidth     : 40
                            ,style          : 'margin-left  : 5px;'
                            ,emptyText      : 'Search item name...'
                        } )
                        ,{  xtype       : 'button'
                            ,iconCls    : 'glyphicon glyphicon-refresh'
                            ,tooltip    : 'Reset filter'
                            ,handler    : function(){
                                Ext.getCmp( 'idItem' + module ).reset();
                            }
                        }
                        ,standards.callFunction( '_createDateField', {
                            id          : 'date' + module
                            ,fieldLabel : 'View received items from'
                        } )
                        ,{  xtype       : 'button'
                            ,text       : 'View'
                            ,handler    : function(){
                                _viewRecords();
                            }
                        }
                        ,{  xtype       : 'button'
                            ,text       : 'Reset'
                            ,handler    : function(){
                                Ext.getCmp( 'idItem' + module ).reset();
                                Ext.getCmp( 'date' + module ).reset();
                                _viewRecords();
                            }
                        }
                        ,'->'
                        ,{  xtype       : 'button'
                            ,iconCls    : 'excel'
                            ,tooltip    : 'Export to excel'
                            ,handler    : function(){
                                _excelHandler();
                            }
                        }
                        ,{  xtype       : 'button'
                            ,iconCls    : 'pdf-icon'
                            ,tooltip    : 'Export to pdf'
                            ,handler    : function(){
                                _pdfHandler();
                            }
                        }
                    ]
                }
                ,listeners      : {
                    itemdblclick: function(dataview, record, item, index, e) {
                        if( parseInt( record.data.idModule, 10 ) > 0 ) mainView.openModule( record.data.idModule , record.data, this );
                    }
                }
                ,plugins        : true
                ,columns        : [
                    {   header      : 'Affiliate'
                        ,dataIndex  : 'affiliateName'
                        ,width      : 150
                    }
                    ,{  header      : 'Date Received'
                        ,dataIndex  : 'dateReceived'
                        ,width      : 120
                        ,xtype      : 'datecolumn'
                        ,format     : 'm/d/Y'
                    }
                    ,{  header      : 'Reference'
                        ,dataIndex  : 'reference'
                        ,width      : 120
                    }
                    ,{  header      : 'Code'
                        ,dataIndex  : 'barcode'
                        ,width      : 100
                    }
                    ,{  header      : 'Item Name'
                        ,flex       : 1
                        ,minWidth   : 150
                        ,dataIndex  : 'itemName'
                    }
                    ,{  header      : 'Classification'
                        ,dataIndex  : 'className'
                        ,width      : 120
                    }
                    ,{  header      : 'Unit'
                        ,dataIndex  : 'unitName'
                        ,width      : 100
                    }
                    ,{  header      : 'Qty'
                        ,dataIndex  : 'qtyLeft'
                        ,xtype      : 'numbercolumn'
                        ,format     : '0,000.00'
                        ,width      : 100
                    }
                    ,{  header      : 'Expiry Date'
                        ,dataIndex  : 'expiryDate'
                        ,width      : 120
                        ,xtype      : 'datecolumn'
                        ,format     : 'm/d/Y'
                    }
                    ,{  header      : 'Remaining Days'
                        ,dataIndex  : 'remainingDays'
                        ,width      : 120
                        ,align      : 'right'
                        ,renderer   : function( value, metaData, rowData, rowIndex ){
                            var val = parseInt( value, 10 );
                            if( val < 0 ) return '<span style="color: red">(' + ( val * -1 ) + ')</span>';
                            else if( isNaN( val ) ) return '';
                            else return val;
                        }
                    }
                ]
            } )
        }

        function _viewRecords(){
            var grd     = Ext.getCmp( 'gridExpiryMonitoring' + module )
                ,idItem = Ext.getCmp( 'idItem' + module ).getValue()
                ,date   = Ext.getCmp( 'date' + module ).getValue();
            grd.store.proxy.extraParams = {
                idItem  : idItem
                ,date   : date
            };
            grd.store.currentPage = 1;
            grd.store.load( {
                callback    : function(){
                    if( grd.getStore().getCount() == 0 ){
                        standards.callFunction( '_createMessageBox', {
                            msg     : 'No record found.'
                        } )
                    }
                }
            } );
        }

        
        function _excelHandler(){
            var grid = Ext.getCmp( 'gridExpiryMonitoring' + module );
            
			standards.callFunction( '_listExcel', {
				grid                    : grid
				,customListExcelHandler : function(){
					var par = standards.callFunction( 'getFormDetailsAsObject', {
						module              : module
						,getSubmitValue     : true
					} );
					par.title = pageTitle;
					
					Ext.Ajax.request( {
						url         : route + 'printExcel'
						,params     : par
						,success    : function(){
							window.open( route + "download/" + par.title + '/inventory');
						}
					} );
				}
			});
        }

        function _pdfHandler(){
            var grid = Ext.getCmp( 'gridExpiryMonitoring' + module );
            
            standards.callFunction( '_listPDF', {
				grid                    : grid
				,customListPDFHandler   : function(){
					var par = standards.callFunction( 'getFormDetailsAsObject', {
						module          : module
						,getSubmitValue : true
					} );
					par.title       = pageTitle;
					
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

        return{
			initMethod:function( config ){
				route		    = config.route;
				baseurl		    = config.baseurl;
				module		    = config.module;
				canPrint	    = config.canPrint;
				canDelete	    = config.canDelete;
				canEdit		    = config.canEdit;
				pageTitle       = config.pageTitle;
				idModule	    = config.idmodule
				isGae		    = config.isGae;
                idAffiliate     = config.idAffiliate
				
				return _mainPanel( config );
			}
		}
    }
}
