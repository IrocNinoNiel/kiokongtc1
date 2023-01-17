/**
 * Developer: Christian P. Daohog
 * Module: Project Accomplishment
 * Date: Jan 26, 2021
 * Finished:
 * Description: This allows the authorized users to record an accomplishment report of a project given to them by the contractor.
 * DB Tables:
 * */

 function Projectaccomplishment() {

    return function () {
        var idAffiliate, baseurl, route, module, canDelete, canPrint, pageTitle, isGae, canEdit, idModule, onEdit = 0, totalApprovedAmnt = 0,
        idInvoice = '', idAccomplishment  = '', boqData = {}, boqDataHolder = [], dataHolder = {};

        function toRoman(num) {
            var lookup = {M:1000,CM:900,D:500,CD:400,C:100,XC:90,L:50,XL:40,X:10,IX:9,V:5,IV:4,I:1},
            roman = '',
            i;
            for ( i in lookup ) {
                while ( num >= lookup[i] ) {
                    roman += i;
                    num -= lookup[i];
                }
            }
            return roman;
        }

        function _getPercentAccomplished() {
            idConstructionProject = Ext.getCmp('projectName' + module).getValue();

            if(typeof idConstructionProject === 'undefined' || idConstructionProject === null) return false;

            Ext.Ajax.request({
                url     : route + 'getTotalReleasedAmnt',
                params  : {
                    idConstructionProject : idConstructionProject
                },
                success : function (response) {
                    var resp = Ext.decode( response.responseText );
                    let totalReleasedAmnt = resp.view[0].totalReleasedAmnt
                    let percentage = totalReleasedAmnt != 0 && totalApprovedAmnt != 0?  
                                        (totalReleasedAmnt / totalApprovedAmnt) * 100 : 0;

                    Ext.getCmp('projectPercentAccomplished' + module).setValue(percentage.toFixed(2) + '%');
                }
            });

            // Ext.Ajax.request({
            //     url     : route + 'getConsContract',
            //     params  : {
            //         idConstructionProject : idConstructionProject
            //     },
            //     success : function (response) {
            //         var resp, duration, startDate, endDate, percentage  = 0;
            //         resp        = Ext.decode(response.responseText);
            //         duration    = resp.view[0].contractDuration;
            //         startDate   = Ext.getCmp('sdate' + module).getValue();
            //         endDate     = Ext.getCmp('edate' + module).getValue();

            //         if(!isDate) {
            //             startDate   = new Date(resp.view[0].dateStart);
            //             Ext.getCmp('sdate' + module).setValue(startDate);
            //             Ext.getCmp('edate' + module).setValue(new Date());
            //         }

            //         if( duration != 0 && startDate <= endDate ) {
            //             days        = Math.round((endDate - startDate) / (1000 * 60 * 60 * 24));
            //             percentage  = (days / duration) * 100;
            //         }

            //         Ext.getCmp('projectPercentAccomplished' + module).setValue(percentage.toFixed(2) + '%');
            //     }
            // });
        }

        function activityGridData(activityDetails) {
            let actDetailsGrd = [];
            // MAIN ACTIVITY
            let ctr = 0;
            let roman = '';

            for(var activityDetailsKey in activityDetails) {
                ctr++;
                roman = toRoman(ctr);
                actDetailsGrd.push({
                    itemNo          : `<b>${roman}</b>`,
                    categoryType    : 'activity',
                    type            : `<b>${activityDetails[activityDetailsKey]['activityName'].toUpperCase()}</b>`,
                    hasActionColumn : false
                });
                
                // SUBACTIVITY
                for(var subActivityKey in activityDetails[activityDetailsKey]['subactivity']) {
                    let totalReleasedAmount = 0, releasedAmount = 0;
                    let subActivityDetails  = activityDetails[activityDetailsKey]['subactivity'][subActivityKey]
                        ,totalMaterialCost  = subActivityDetails['materialDetails'].reduce((partialSum, a) => partialSum + parseFloat(a.approvedAmount), 0)
                        ,totalLaborCost     = subActivityDetails['laborDetails'].reduce((partialSum, a) => partialSum + parseFloat(a.approvedAmount), 0)
                        ,totalEquipCost     = subActivityDetails['equipDetails'].reduce((partialSum, a) => partialSum + parseFloat(a.approvedAmount), 0)
                        ,totalDirectCost    = totalMaterialCost + totalLaborCost + totalEquipCost;

                    let indirectDetails = subActivityDetails['indirectDetails'][0]
                        ,ocmAmount      = (indirectDetails['ocm']/100) * totalDirectCost
                        ,profitAmount   = (indirectDetails['contractorsProfit']/100) * totalDirectCost
                        ,vatAmount      = (indirectDetails['vat']/100) * totalDirectCost
                        ,totalIndirectCost = ocmAmount + profitAmount + vatAmount;

                    activityDetails[activityDetailsKey]['subactivity'][subActivityKey]['totalMaterialCost'] = totalMaterialCost;
                    activityDetails[activityDetailsKey]['subactivity'][subActivityKey]['totalEquipCost'] = totalEquipCost;
                    activityDetails[activityDetailsKey]['subactivity'][subActivityKey]['totalLaborCost'] = totalLaborCost;

                    subactivityItemNo = parseInt(subActivityKey) + 1;

                    totalApprovedAmnt = totalIndirectCost + totalDirectCost;

                    let idConsSubActivity = subActivityDetails['indirectDetails'][0]['idConsSubActivity']
                        ,idActivity = subActivityDetails['indirectDetails'][0]['idConsSubActivity'];

                    actDetailsGrd.push({
                        itemNo          : `<b>${roman}.${subactivityItemNo}</b>`,
                        categoryType    : 'subActivity',
                        type            : `<b>${subActivityDetails['subActivityName']}</b>`,
                        approvedQty     : (0).toFixed(2),
                        approvedAmount  : `<b>${totalApprovedAmnt.toFixed(2)}</b>`,
                        key             : idConsSubActivity,
                        idActivity      : activityDetailsKey,
                        hasActionColumn : true
                    });

                    // MATERIALS
                    actDetailsGrd.push({
                        itemNo          : `<b>${roman}.${subactivityItemNo}.a</b>`,
                        type            : `<b>Materials</b>`,
                        hasActionColumn : false
                    });

                    let materialDetailsGrid = subActivityDetails['materialDetails'];
                    boqData['materials'] = boqData['materials'] || [];
                    for(var materialKey in materialDetailsGrid) {
                        let materialDetails = materialDetailsGrid[materialKey];
                        releasedAmount = materialDetails['approvedCost'] * materialDetails['releasedQty'];
                        totalReleasedAmount += releasedAmount;

                        boqData['materials'].push({
                            'id'            : materialDetails['idConsActivityDetailsItem'],
                            'releasedQty'   : materialDetails['releasedQty'].toFixed(2),
                            'releasedCost'  : materialDetails['approvedCost'].toFixed(2)
                        });

                        boqDataHolder.push({
                            'fIdent'    : materialDetails['idConsActivityDetailsItem'],
                            'qty'       : materialDetails['releasedQty'],
                            'unitCost'  : materialDetails['approvedCost'],
                            'type'      : 1
                        });

                        actDetailsGrd.push({
                            categoryType    : 'materials',
                            id              : materialDetails['idConsActivityDetailsItem'],
                            workDescription : materialDetails['itemName'],
                            unitCode        : materialDetails['unit'],
                            approvedQty     : materialDetails['approvedQty'].toFixed(2),
                            approvedCost    : materialDetails['approvedCost'].toFixed(2),
                            approvedAmount  : materialDetails['approvedAmount'].toFixed(2),
                            releasedCost    : materialDetails['approvedCost'].toFixed(2),
                            releasedQty     : materialDetails['releasedQty'].toFixed(2),
                            remainingBalance: materialDetails['remainingBalance'].toFixed(2),  //modified 5/19/22
                            releasedAmount  : releasedAmount.toFixed(2),
                            key             : idConsSubActivity,
                            idActivity      : activityDetailsKey,
                            hasActionColumn : false
                        });
                    }
                    actDetailsGrd.push({
                        categoryType    : 'materialCost',
                        workDescription : '<b>Material Cost</b>',
                        approvedAmount  : `<b>${totalMaterialCost.toFixed(2)}</b>`,
                        releasedAmount  : `<b>${totalReleasedAmount.toFixed(2)}</b>`,
                        key             : idConsSubActivity,
                        idActivity      : activityDetailsKey,
                        hasActionColumn : false
                    });

                    // LABOR
                    actDetailsGrd.push({
                        itemNo          : `<b>${roman}.${subactivityItemNo}.b</b>`,
                        type            : `<b>Labor</b>`,
                        hasActionColumn : false
                    });

                    let laborDetailsGrid = subActivityDetails['laborDetails'];
                    totalReleasedAmount = 0;

                    boqData['labors'] = boqData['labors'] || [];
                    for(var laborKey in laborDetailsGrid) {
                        let laborDetails = laborDetailsGrid[laborKey];
                        releasedAmount = laborDetails['approvedCost'] * laborDetails['releasedQty'];
                        totalReleasedAmount += releasedAmount;

                        boqData['labors'].push({
                            'id'            : laborDetails['idConsActivityDetailsLabor'],
                            'releasedQty'   : laborDetails['releasedQty'].toFixed(2),
                            'releasedCost'  : laborDetails['approvedCost'].toFixed(2)
                        });

                        boqDataHolder.push({
                            'fIdent'    : laborDetails['idConsActivityDetailsLabor'],
                            'qty'       : laborDetails['releasedQty'],
                            'unitCost'  : laborDetails['approvedCost'],
                            'type'      : 2
                        });

                        actDetailsGrd.push({
                            categoryType    : 'labors',
                            unitCode        : 'days',
                            id              : laborDetails['idConsActivityDetailsLabor'],
                            workDescription : laborDetails['employeeName'],
                            approvedQty     : laborDetails['approvedQty'].toFixed(2),
                            approvedCost    : laborDetails['approvedCost'].toFixed(2),
                            approvedAmount  : laborDetails['approvedAmount'].toFixed(2),
                            releasedCost    : laborDetails['approvedCost'].toFixed(2),
                            releasedQty     : laborDetails['releasedQty'].toFixed(2),
                            releasedAmount  : releasedAmount.toFixed(2),
                            key             : idConsSubActivity,
                            idActivity      : activityDetailsKey,
                            hasActionColumn : false
                        });
                    }
                    actDetailsGrd.push({
                        categoryType    : 'laborCost',
                        workDescription : '<b>Labor Cost</b>',
                        approvedAmount  : `<b>${totalLaborCost.toFixed(2)}</b>`,
                        releasedAmount  : `<b>${totalReleasedAmount.toFixed(2)}</b>`,
                        key             : idConsSubActivity,
                        idActivity      : activityDetailsKey,
                        hasActionColumn : false
                    });

                    // Equipment
                    actDetailsGrd.push({
                        itemNo          : `<b>${roman}.${subactivityItemNo}.c</b>`,
                        type            : `<b>Equipment</b>`,
                        hasActionColumn : false
                    });

                    let equipDetailsGrid = subActivityDetails['equipDetails'];
                    totalReleasedAmount = 0;

                    boqData['equipments'] = boqData['equipments'] || [];
                    for(var equipKey in equipDetailsGrid) {
                        let equipDetails = equipDetailsGrid[equipKey];
                        releasedAmount = equipDetails['approvedCost'] * equipDetails['releasedQty'];
                        totalReleasedAmount += releasedAmount;
                        
                        boqData['equipments'].push({
                            'id'            : equipDetails['idConsActivityDetailsEquip'],
                            'releasedQty'   : equipDetails['releasedQty'].toFixed(2),
                            'releasedCost'  : equipDetails['approvedCost'].toFixed(2)
                        });

                        boqDataHolder.push({
                            'fIdent'    : equipDetails['idConsActivityDetailsEquip'],
                            'qty'       : equipDetails['releasedQty'],
                            'unitCost'  : equipDetails['approvedCost'],
                            'type'      : 3
                        });

                        actDetailsGrd.push({
                            categoryType    : 'equipments',
                            unitCode        : 'hrs',
                            id              : equipDetails['idConsActivityDetailsEquip'],
                            workDescription : equipDetails['truckType'],
                            approvedQty     : equipDetails['approvedQty'].toFixed(2),
                            approvedCost    : equipDetails['approvedCost'].toFixed(2),
                            approvedAmount  : equipDetails['approvedAmount'].toFixed(2),
                            releasedCost    : equipDetails['approvedCost'].toFixed(2),
                            releasedQty     : equipDetails['releasedQty'].toFixed(2),
                            releasedAmount  : releasedAmount.toFixed(2),
                            key             : idConsSubActivity,
                            hasActionColumn : false
                        });
                    }

                    actDetailsGrd.push(
                        {   categoryType    : 'equipmentCost',
                            workDescription : '<b>Equipment Cost</b>',
                            approvedAmount  : `<b>${totalEquipCost.toFixed(2)}</b>`,
                            releasedAmount  : `<b>${totalReleasedAmount.toFixed(2)}</b>`,
                            key             : idConsSubActivity,
                            hasActionColumn : false
                        },
                        {   workDescription : '<b>Total Direct Cost</b>',
                            approvedAmount  : `<b>${totalDirectCost.toFixed(2)}</b>`,
                            hasActionColumn : false
                        }
                    );

                    // INDIRECT COST
                    actDetailsGrd.push(
                        {   type            : `<b>Indirect Cost</b>`,
                            hasActionColumn : false
                        }
                        ,{  workDescription : 'OCM',
                            approvedQty     : indirectDetails['ocm'] + '%',
                            approvedAmount  : ocmAmount.toFixed(2),
                            hasActionColumn : false
                        },
                        {   workDescription : 'Contractor\'s Profit',
                            approvedQty     : indirectDetails['contractorsProfit'] + '%',
                            approvedAmount  : profitAmount.toFixed(2),
                            hasActionColumn : false
                        },
                        {   workDescription : 'VAT',
                            approvedQty     : indirectDetails['vat'] + '%',
                            approvedAmount  : vatAmount.toFixed(2),
                            hasActionColumn : false
                        },
                        {   workDescription : '<b>Total Indirect Cost</b>',
                            approvedAmount  : `<b>${totalIndirectCost.toFixed(2)}</b>`,
                            hasActionColumn : false
                        },
                        {   workDescription : '<b>Total Item Cost</b>',
                            approvedAmount  : `<b>${totalApprovedAmnt.toFixed(2)}</b>`,
                            hasActionColumn : false
                        },
                        {   workDescription : '<b>Unit Cost</b>',
                            hasActionColumn : false,
                            approvedAmount  : `<b>${(0).toFixed(2)}</b>`,
                            idActivity      : activityDetailsKey,
                            idActivity      : activityDetailsKey,
                            subActivityKey : subActivityKey
                        }
                    );
                }
            }
            Ext.getCmp('boqList' + module).getStore().loadData(actDetailsGrd);
        }

        function _mainPanel(config) {
            return standards2.callFunction('_mainPanelTransactions', {
                 config      : config
                ,module      : module
                ,moduleType  : 'form'
                ,hasApproved : false
                ,tbar        : {
                     saveFunc               : _saveForm
                    ,resetFunc              : _resetForm
                    ,customListExcelHandler	: _printExcel
					,customListPDFHandler   : _printPDF
                    ,formPDFHandler         : _printPDFForm
					,hasFormPDF     		: true
					,hasFormExcel			: false
                    ,filter                 : {
                         searchURL  : route + 'viewHistorySearch'
                        ,emptyText  : 'Search reference here...'
                        ,module     : module
                    }
                },
                formItems   : [
                     _transactionForm( config )
                    ,_constructionForm( config )
                    ,_tabPanel()
                ],
                listItems   : _gridHistory()
            });
        }

        function _transactionForm( config ) {
            return standards2.callFunction( '_transactionHeader', {
                module			: module
                ,containerWidth	: 1000
                ,idModule		: idModule
                ,idAffiliate	: idAffiliate
                ,config			: config

            });
        }

        function _constructionForm( config ) {
            let projectNameStore = standards.callFunction( '_createRemoteStore', {
                fields      : [ { name: 'id', type: 'number' } ,'name' ]
                ,url        : route + 'getProjectName'
                ,autoLoad   : true
            })
            ,contractorStore = standards.callFunction( '_createRemoteStore', {
                fields      : [ { name: 'id', type: 'number' } ,'employeeName' ]
                ,url        : route + 'getContractor'
                ,autoLoad   : false
            });

            return {
                xtype       : 'fieldset'
                ,layout     : 'column'
                ,padding    : 10
                ,items      : [
                    {
                        xtype           : 'container'
                        ,columnWidth    : .5
                        ,items          : [
                            standards.callFunction( '_createCombo', {
                                id              : 'projectName' + module
                                ,fieldLabel     : 'Project Name'
                                ,store          : projectNameStore
                                ,displayField   : 'name'
                                ,valueField     : 'id'
                                ,listeners      : {
                                    select : function(me) {
                                        let val = Ext.getCmp('projectName' + module).getValue();
                                        
                                   
                                        Ext.Ajax.request({
                                            url         : route + 'getActivityDetails'
                                            ,params     : {
                                                idConstructionProject : val,
                                                onEdit : onEdit,
                                                idAccomplishment : Object.keys(dataHolder).length === 0 ? null : dataHolder.idAccomplishment
                                            }
                                            ,method     : 'post'
                                            ,success    : function( response ) {
                                                boqDataHolder = [];
                                                var resp = Ext.decode( response.responseText );
                                                let activityDetails = Ext.decode( resp.view );
                                                activityGridData(activityDetails);
                                                _getPercentAccomplished();
                                            }
                                        });

                                        let contractorStore = Ext.getCmp('contractor' + module).getStore();
                                        contractorStore.proxy.extraParams.idConstructionProject = val;
                                        contractorStore.load({
                                            callback: function(data) {
                                                if(data.length > 0) {
                                                    Ext.getCmp('contractor' + module).setValue( parseInt(data[0].data.id));
                                                    Ext.getCmp('contractor' + module).fireEvent('select');
                                                }
                                            }
                                        });
                                    }
                                }
                            } )
                            ,standards.callFunction( '_createCombo', {
                                id              : 'contractor' + module
                                ,fieldLabel     : 'Contractor'
                                ,store          : contractorStore
                                ,displayField   : 'employeeName'
                                ,readOnly       : true
                                ,hideTrigger    : true
                                ,valueField     : 'id'
                                ,emptyText      : ''
                            } )
                        ]
                    }
                    ,{
                        xtype           : 'container'
                        ,columnWidth    : .5
                        ,items          : [
                            standards.callFunction( '_createDateRange',{
                                sdateID			: 'sdate' + module
                                ,edateID		: 'edate' + module
                                ,noTime			: true
                                ,fromFieldLabel	: 'Date Range'
                                // ,listeners      : {
                                //     afterChange1    : function() {
                                //         _getPercentAccomplished(true);
                                //     }
                                //     ,afterChange2   : function() {
                                //         _getPercentAccomplished(true);
                                //     }
                                // }
                            })
                            ,standards.callFunction( '_createTextField', {
                                id              : 'projectPercentAccomplished' + module
                                ,width           : 381
                                ,readOnly       : true
                                ,fieldLabel     : 'Project Percent Accomplished'
                            } )
                        ]
                    }
                ]
            }
        }

        function _tabPanel() {
            return {
                xtype: 'tabpanel'
                ,items: [
                    {   title   : 'BOQ'
                        ,layout : {
                            type    : 'card'
                        }
                        ,items  :   [
                            _boq()
                        ]
                    }
                    ,{  title   : 'Disbursement'
                        ,layout : {
                            type    : 'card'
                        }
                        ,items  :   [
                            _disbursement()
                        ]
                    }
                ]
            }
        }

        function _boq() {
            let store = standards.callFunction( '_createRemoteStore', {
				fields		: [
                    'itemNo'
                    ,'type'
                    ,'workDescription'
                    ,'unitCode'
                    ,'categoryType'
                    ,'approvedAmount'
                    ,'approvedQty'
                    ,'approvedCost'
                    ,'releasedAmount'
                    ,'releasedQty'
                    ,'releasedCost'
                    ,'key'
                    ,'idActivity'
                    ,'remainingBalance'
                    ,{ name: 'id',  type: 'number' }
                ]
				,url		: route + 'getBOQ'
            } );

            let columns = [
                {   header      : 'Item No.'
                    ,dataIndex  : 'itemNo'
                }
                ,{   header      : 'Type'
                    ,dataIndex   : 'type'
                    ,width       : 150
                    ,columnWidth : 30
                }
                ,{   header      : 'Work Description'
                    ,dataIndex   : 'workDescription'
                    ,width       : 200
                    ,flex        : 1
                }
                ,{   header     : 'Unit'
                    ,dataIndex  : 'unitCode'
                }
                ,{  text        : 'Approved'
                    ,columns    : [
                        {   header      : 'Quantity'
                            ,width      : 80
                            ,dataIndex  : 'approvedQty'
                            ,align      : 'right'
                        },
                        {   header      : 'Unit Cost'
                            ,width      : 80
                            ,dataIndex  : 'approvedCost'
                            ,align      : 'right'
                            ,renderer   : function (val, params, record, row_index) {
                                return numberWithCommas(val);
                            }
                        },
                        {   header      : 'Amount'
                            ,width      : 80
                            ,align      : 'right'
                            ,dataIndex  : 'approvedAmount'
                            ,renderer   : function (val, params, record, row_index) {
                                let data = typeof val == 'number'? val.toFixed(2) : val;
                                return numberWithCommas(data);
                            }
                        }
                    ]
                },
                {   text        : 'Released'
                    ,columns    : [
                        {   header      : 'Quantity'
                            ,width      : 80
                            ,align      : 'right'
                            ,dataIndex  : 'releasedQty'
                            ,editor     : 'text'
                            ,maskRe     : /[0-9.]/
                            ,renderer   : function (val, params, record, row_index) {
                                return val == ''? '' : parseFloat(val).toFixed(2);
                            }

                        },
                        {   header      : 'Unit Cost'
                            ,width      : 80
                            ,dataIndex  : 'releasedCost'
                            ,renderer   : function (val, params, record, row_index) {
                                return numberWithCommas(val);
                            }
                        },
                        {   header      : 'Amount'
                            ,width      : 80
                            ,align      : 'right'
                            ,dataIndex  : 'releasedAmount'
                            ,renderer   : function (val, params, record, row_index) {
                                return numberWithCommas(val);
                            }
                        }
                    ]
                }
            ];

            function getIndex(store, categoryType, key) {
                let index = store.findIndex(function(item, index){
                    return item.data.categoryType == categoryType && item.data.key == key;
                });
                return index;
            }

            function getReleasedAmount(store, categoryType, key) {
                let totalCost = 0;
                let items = store.filter(function(record){
                    return record.data.categoryType == categoryType && record.data.key == key;
                });

                let releasedAmount = 0;
                items.forEach(function(record){
                    releasedAmount = parseFloat(record.data.releasedAmount);
                    if(releasedAmount) {
                        totalCost += releasedAmount;
                    }
                });
                return totalCost.toFixed(2);
            }

            return {
                xtype       : 'container',
                columnWidth : 1,
                items       : [
                    standards.callFunction('_gridPanel', {
                        id              : 'boqList' + module,
                        module          : module,
                        store           : store,
                        noDefaultRow    : true,
                        noPage          : true,
                        plugins         : true,
                        sortable        : false,
                        tbar            : {},
                        columns         : columns,
                        listeners       : {
                            beforeEdit  : function (me, rowData) {
                                var index = rowData.rowIdx
                                ,store = this.getStore().getRange();

                                let categoryType = store[index].data.categoryType;
                                switch( rowData.field ) {
                                    case 'approvedQty':
                                        if( categoryType != 'subActivity' ) {
                                            return false;
                                        }
                                        break;
                                    case 'releasedQty':
                                        if( categoryType != 'materials' && categoryType != 'labors' && categoryType != 'equipments' ) {
                                            return false;
                                        }
                                        break;
                                }
                            }
                            ,edit       : function( me, rowData ) {
                                var index = rowData.rowIdx
                                ,store = this.getStore().getRange();

                                var unitCost = 0;
                                switch( rowData.field ) {
                                    case 'approvedQty':
                                        if( rowData.value == 0 ) {
                                            standards.callFunction('_createMessageBox', {
                                                msg : 'Invalid input. Value must be greater than 0.'
                                                ,fn: function(){
                                                    store[index].set('approvedQty', rowData.originalValue );
                                                }
                                            });
                                        } else {
                                            unitCost = store[index].data.approvedAmount/rowData.value;
                                            store[index].set('approvedCost', unitCost );
                                        }
                                        break;

                                    case 'releasedQty':
                                        if( rowData.value == 0 ) {
                                            standards.callFunction('_createMessageBox', {
                                                msg : 'Invalid input. Value must be greater than 0.'
                                                ,fn: function(){
                                                    store[index].set(rowData.field, rowData.originalValue );
                                                }
                                            });
                                        }

                                        let releasedCost = store[index].data.releasedCost,
                                            remainingBalance = store[index].data.remainingBalance,  //modified 5/19/22
                                            releasedAmount = parseFloat(releasedCost*rowData.value).toFixed(2);
                                        store[index].set('releasedAmount', releasedAmount );

                                        let id = rowData.record.data.id;
                                        let categoryType = rowData.record.data.categoryType;

                                        let type;
                                        switch( categoryType ) {
                                            case 'materials':
                                                type = 1;
                                                break;
                                            case 'labors':
                                                type = 2;
                                                break;
                                            case 'equipments':
                                                type = 3;
                                                break;
                                            default:
                                                break;
                                        }

                                        boqDataHolder.forEach((item, key) => {
                                            if(item.fIdent == id && item.type == type) {
                                                boqDataHolder[key].qty = parseFloat(rowData.value);
                                                boqDataHolder[key].unitCost = parseFloat(releasedCost);
                                                boqDataHolder[key].unitCost = parseFloat(releasedCost);
                                                boqDataHolder[key].unitCost = parseFloat(releasedCost);

                                                return false;
                                            }
                                        });

                                        boqData[categoryType].forEach(function(item, index){
                                            if( item.id == id ) {
                                                boqData[categoryType][index].releasedQty = parseFloat(rowData.value);
                                                boqData[categoryType][index].releasedCost = parseFloat(releasedCost);
                                                boqData[categoryType][index].remainingBalance = parseFloat(remainingBalance - rowData.value);  //modified 5/19/22
                                            }
                                        });

                                        // Total Cost
                                        var costIndex = '', key = rowData.record.data.key;
                                        costIndex = getIndex(store, 'materialCost', key);
                                        store[costIndex].set('releasedAmount', `<b>${getReleasedAmount(store, 'materials', key)}</b>` );

                                        costIndex = getIndex(store, 'laborCost', key);
                                        store[costIndex].set('releasedAmount', `<b>${getReleasedAmount(store, 'labors', key)}</b>` );

                                        costIndex = getIndex(store, 'equipmentCost', key);
                                        store[costIndex].set('releasedAmount', `<b>${getReleasedAmount(store, 'equipments', key)}</b>` );
                                        break;
                                }
                            }
                        }
                    })
                ]
            }
        }

        function _disbursement() {
            let store = standards.callFunction( '_createRemoteStore', {
                fields  : ['date', 'referenceNumber', 'amount']
                ,url    : route + 'getDisbursement'
            } );

            let columns = [{
                header      : 'Date'
                ,dataIndex  : 'date'
                ,xtype      : 'datecolumn'
                ,flex       : 1
            }, {
                header      : 'Reference Number'
                ,dataIndex  : 'referenceNumber'
                ,flex       : 1
            }, {
                header      : 'Amount'
                ,dataIndex  : 'amount'
                ,xtype      : 'numbercolumn'
                ,flex       : 1
                ,hasTotal   : true
            }];

            return {
                xtype       : 'container',
                columnWidth : 1,
                items       : [
                    standards.callFunction('_gridPanel', {
                        id              : 'disbursementList' + module,
                        module          : module,
                        store           : store,
                        noDefaultRow    : true,
                        noPage          : true,
                        plugins         : true,
                        tbar            : {},
                        columns         : columns
                    })
                ]
            }
        }

        function _gridHistory() {
            var store = standards.callFunction('_createRemoteStore', {
                fields  : [ { name:'idAccomplishment', type: 'number' }, 'affiliateName', 'date', 'referenceNum', 'idContract', 'projectName', 'contractorName', 'dateFrom', 'dateTo' ],
                url     : route + 'getAccomplishment',
            });

            let columns = [
                {
                     header     : 'Affiliate'
                    ,dataIndex  : 'affiliateName'
                    ,sortable   : true
                    ,flex       : 1
                },
                {
                     header     : 'Date'
                    ,dataIndex  : 'date'
                    ,sortable   : true
                    ,flex       : 1
                },
                {
                     header     : 'Reference'
                    ,dataIndex  : 'referenceNum'
                    ,sortable   : true
                    ,flex       : 1
                },
                {
                     header     : 'Contract ID'
                    ,dataIndex  : 'idContract'
                    ,sortable   : true
                    ,flex       : 1
                },
                {
                     header     : 'Project Name'
                    ,dataIndex  : 'projectName'
                    ,sortable   : true
                    ,flex       : 1
                },
                {
                     header     : 'Contractor'
                    ,dataIndex  : 'contractorName'
                    ,sortable   : true
                    ,flex       : 1
                },
                {
                     header     : 'Date From'
                    ,dataIndex  : 'dateFrom'
                    ,sortable   : true
                    ,flex       : 1
                },
                {
                     header     : 'Date To'
                    ,dataIndex  : 'dateTo'
                    ,sortable   : true
                    ,flex       : 1
                },
                standards.callFunction('_createActionColumn', {
                     canEdit    : canEdit
                    ,icon       : 'pencil'
                    ,tooltip    : 'Edit'
                    ,width      : 30
                    ,Func       : _editRecord
                }),
                standards.callFunction('_createActionColumn', {
                     canEdit    : canEdit
                    ,icon       : 'remove'
                    ,tooltip    : 'Delete'
                    ,width      : 30
                    ,Func       : _deleteRecord
                })
            ];

            function _editRecord(data) {
                module.getForm().retrieveData({
                    url         : route + 'retrieveAccomplishment'
                    ,method     : 'post'
                    ,hasFormPDF : true
                    ,params     : {
                        idAccomplishment : data.idAccomplishment
                    }
                    ,success    : function (response) {
                        dataHolder          = response;
                        onEdit              = 1;
                        idAccomplishment    = response.idAccomplishment;
                        idInvoice           = response.idInvoice;

                        Ext.getCmp('referenceNum' + module).setValue(response.referenceNum);
                        Ext.getCmp('projectName' + module).getStore().load({
                            callback: function() {
                                Ext.getCmp('projectName' + module).setValue( parseInt(response.idConstructionProject) );
                                Ext.getCmp('projectName' + module).fireEvent( 'select' );
                            }
                        });
                        // set readonly
                        Ext.getCmp('projectName' + module).setReadOnly(true);

                        _getPercentAccomplished();
                    }
                });
            }

            function _deleteRecord(data) {
                standards.callFunction('_createMessageBox', {
                    msg     : 'DELETE_CONFIRM',
                    action  : 'confirm',
                    fn      : function (btn) {
                        if (btn == 'yes') {
                            Ext.Ajax.request({
                                url     : route + 'deleteProjectAccomplishment',
                                params  : {
                                    idAccomplishment    : data.idAccomplishment,
                                    idModule            : idModule
                                },
                                success : function (response) {
                                    var resp = Ext.decode(response.responseText);

                                    standards.callFunction('_createMessageBox', {
                                        msg : (resp.match == 1) ? 'DELETE_USED' : 'DELETE_SUCCESS',
                                        fn  : function () {
                                            Ext.resetGrid('gridHistory' + module);
                                        }
                                    });
                                }
                            });
                        }
                    }
                });
            }

            return standards.callFunction('_gridPanel', {
                 id           : 'gridHistory' + module
                ,module       : module
                ,store        : store
                ,height       : 265
                ,columns      : columns
                ,noDefaultRow : true
                ,listeners    : {
                    afterrender  : function () {
                        store.load({})
                    }
                }
            })
        }

        function _saveForm( form ) {
            var params = {
                 onEdit                         : onEdit
                 ,boqData                       : Ext.encode( boqData )
                 ,boqDataHolder                 : Ext.encode( boqDataHolder )
                 ,idConsProjectAccomplishment   : idAccomplishment
                 ,idConstructionProject         : Ext.getCmp('projectName' + module).getValue()
                 ,dateFrom                      : Ext.getCmp('sdate' + module).getValue()
                 ,dateTo                        : Ext.getCmp('edate' + module).getValue()
                 ,invoices                      : Ext.encode ({
                    idAffiliate         : idAffiliate
                   ,idCostCenter        : Ext.getCmp('idCostCenter' + module).getValue()
                   ,idReference         : Ext.getCmp('idReference' + module).getValue()
                   ,idReferenceSeries   : Ext.getCmp('idReferenceSeries' + module).getValue()
                   ,date			    : Ext.getCmp('tdate' + module).getValue()
                   ,time			    : Ext.Date.format(Ext.getCmp( 'ttime' + module).getValue(), 'h:i:s A')
                   ,referenceNum        : Ext.getCmp('referenceNum' + module).getValue()
                   ,cancelTag           : 0
                   ,dateModified        : new Date()
                   ,hasJournal          : 0
                   ,archived            : 0
                   ,cancelledBy         : 0
                   ,idModule            : idModule
                   ,idInvoice           : idInvoice
               })
            };

            form.submit({
                 url     : route + 'saveProjectAccomplishment'
                ,params  : params
                ,success : function( action, response ){
                    var resp 	= Ext.decode( response.response.responseText ),
                    msg			= ( resp.match == 0 ) ? 'SAVE_SUCCESS' : 'SAVE_FAILURE';

                    standards.callFunction( '_createMessageBox', {
                        msg     : msg
                        ,action : ''
                        ,fn     : function(){
                            if( resp.match == 0 ) _resetForm( form );
                        }
                    } );
                }
            });
        }

        function _resetForm( form ) {
            form.reset();

            onEdit = 0;
            boqData = {};
            idAccomplishment = '';
            dataHolder = {};
            boqDataHolder = [];
            Ext.resetGrid('boqList' + module);
            Ext.resetGrid('disbursementList' + module);
            Ext.getCmp('projectName' + module).setReadOnly(false);
        }

        function _printPDFForm() {
            var par  = standards.callFunction('getFormDetailsAsObject', { module : module });
            Ext.Ajax.request({
                url     : route + 'printPDFForm'
                ,method : 'post'
                ,params : {
                    moduleID        : idModule
                    ,title          : pageTitle
                    ,limit          : 50
                    ,start          : 0
					,printPDF       : 1
                    ,form	        : Ext.encode(par)
					,boq	        : Ext.encode ( Ext.getCmp('boqList' + module).store.data.items.map((item)=>item.data))
                    ,disbursement   : Ext.encode ( Ext.getCmp('disbursementList' + module).store.data.items.map((item)=>item.data))
                }
                ,success : function (res) {
                    if (isGae) {
                        window.open(route + 'viewPDF/' + pageTitle, '_blank');
                    } else {
                        window.open(baseurl + 'pdf/construction/' + pageTitle + ' Form.pdf');
                    }
                }
            });
        }

        function _printPDF() {
            var _grid = Ext.getCmp('gridHistory' + module);

            standards.callFunction('_listPDF', {
                grid                    : _grid,
                customListPDFHandler    : function () {

                    var par = standards.callFunction('getFormDetailsAsObject', {
                        module          : module,
                        getSubmitValue  : true
                    });
                    par.title = pageTitle;
                    par.idModule = idModule;

                    Ext.Ajax.request({
                        url     : route + 'printPDF',
                        params  : par,
                        success : function (res) {
                            if (isGae) {
                                window.open(route + 'viewPDF/' + par.title, '_blank');
                            } else {
                                window.open(baseurl + 'pdf/construction/' + par.title + '.pdf');
                            }
                        }
                    });
                }
            });
        }

        function _printExcel() {
            var _grid = Ext.getCmp('gridHistory' + module);

            standards.callFunction('_listExcel', {
                grid                    : _grid,
                customListExcelHandler  : function () {

                    var par = standards.callFunction('getFormDetailsAsObject', {
                        module          : module,
                        getSubmitValue  : true
                    });
                    par.title   = pageTitle;
                    par.idModule = idModule;

                    Ext.Ajax.request({
                        url     : route + 'printExcel',
                        params  : par,
                        success : function (res) {
                            window.open(route + "download/" + par.title + '/construction');
                        }
                    });
                }
            });
        }

        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        return {
            initMethod: function (config) {
                route       = config.route;
                module      = config.module;
                canDelete   = config.canDelete;
                canPrint    = config.canPrint;
                pageTitle   = config.pageTitle;
                isGae       = config.isGae;
                canEdit     = config.canEdit;
                idModule    = config.idmodule;
                baseurl     = config.baseurl;
                idAffiliate = config.idAffiliate

                return _mainPanel(config);
            }
        }
    }
}
