/**
 * Developer: Hazel Alegbeleye
 * Module: Construction Project
 * Date: Dec 22, 2021
 * Finished:
 * Description: This module allows the authorized user to set (add, edit and delete) a Construction Project.
 * DB Tables:
 * */

 function Constructionproject() {
    return function(){
        var baseurl, route, module, canDelete, pageTitle, idModule, isGae, isSaved = 0, deletedItems = [], selectedItem = [], idAffiliate, selRec, componentCalling
        ,canSave, idAffiliate, canCancel, dataHolder = {} , onEdit = 0, activityDetails = {};

        function _mainPanel( config ){

            let projectStatusStore = standards.callFunction( '_createLocalStore' , {
                data        : [ 'Suspended' ,'Ongoing', 'Completed' ]
                ,startAt    : 1
            } )
            ,statusTypeStore = standards.callFunction( '_createLocalStore' , {
                data        : [ 'Slippage' ,'Advance', 'On-Time' ]
                ,startAt    : 1
            } )
            ,licenseTypeStore = standards.callFunction( '_createLocalStore' , {
                data        : [ 'In Royalty' ,'Out Royalty', 'In and Out Royalty', 'Admin' ]
                ,startAt    : 1
            } );

            return standards2.callFunction(	'_mainPanelTransactions' ,{
                config		            : config
                ,module		            : module
                ,moduleType             : 'form'
                ,hasApproved            : false
                ,tbar : {
                    saveFunc                : _saveForm
                    ,resetFunc              : _resetForm
                    ,customListExcelHandler	: _printExcel
					,customListPDFHandler	: _customListPDF
                    ,formPDFHandler         : _printPDFForm
					,hasFormPDF     		: true
					,hasFormExcel			: false
                    ,filter: {
                        searchURL       : route + 'viewHistorySearch'
                        ,emptyText      : 'Search reference here...'
                        ,module         : module
                    }
                }
                ,formItems: [
                    standards2.callFunction( '_transactionHeader', {
						module					: module
						,containerWidth			: 1000
						,idModule				: idModule
						,idAffiliate			: idAffiliate
						,config					: config
					} )
                    ,{  xtype       : 'fieldset'
                        ,layout     : 'column'
                        ,padding    : 10
                        ,items		: [
                            {
                                xtype			: 'container'
                                ,columnWidth	: .5
                                ,items			: [
                                    standards.callFunction( '_createCheckField', {
                                        id              : 'isManual' + module
                                        ,fieldLabel     : 'Manual ID'
                                        ,allowBlank     : false
                                        ,listeners      : {
                                            change  : function( me ) {
                                                if( me.value ){
                                                    Ext.getCmp('idConstructionProject' + module ).setReadOnly( false );
                                                    Ext.getCmp('idConstructionProject' + module).setValue("");
                                                } else {
                                                    Ext.getCmp('idConstructionProject' + module ).setReadOnly( true );
                                                    _generateProjectID();
                                                }
                                            }
                                        }
                                    })
                                    ,standards.callFunction( '_createTextField', {
                                        fieldLabel  : 'Project ID'
                                        ,id         : 'idConstructionProject' + module
                                        ,isDecimal  : true
                                        ,maskRe     : /[0-9.]/
                                        ,maxLength  : 20
                                        ,allowBlank : false
                                        ,listeners  : {
                                            afterrender: function( me ) {
                                                this.setReadOnly( true );
                                                _generateProjectID();
                                            }
                                        }
                                    } )
                                    ,standards.callFunction( '_createTextField', {
                                        fieldLabel      : 'Project Name'
                                        ,id             : 'projectName' + module
                                        ,allowBlank     : false
                                    } )
                                    ,standards.callFunction( '_createCombo', {
                                        id              : 'status' + module
                                        ,fieldLabel     : 'Project Status'
                                        ,store          : projectStatusStore
                                        ,displayField   : 'name'
                                        ,valueField     : 'id'
                                        ,allowBlank     : false
                                        ,listeners      : {
											select	: function( me, record ){
											}
                                        }
                                    } )
                                    ,standards.callFunction( '_createCombo', {
                                        id              : 'statusType' + module
                                        ,fieldLabel     : 'Status Type'
                                        ,store          : statusTypeStore
                                        ,displayField   : 'name'
                                        ,valueField     : 'id'
                                        ,allowBlank     : false
                                        ,listeners      : {
											select	: function( me, record ){
											}
                                        }
                                    } )
                                    ,standards.callFunction( '_createTextField', {
                                        fieldLabel  : 'Contract ID'
                                        ,id         : 'idContract' + module
                                        ,maxLength  : 20
                                    } )
                                    ,standards.callFunction( '_createTextField', {
                                        fieldLabel  : 'Contract Duration'
                                        ,id         : 'contractDuration' + module
                                        ,isDecimal  : true
                                        ,maskRe     : /[0-9.]/
                                        ,maxLength  : 20
                                        ,allowBlank : false
                                    } )
                                    ,standards.callFunction('_createNumberField',{
                                        id			: 'contractAmount' + module
                                        ,module		: module
                                        ,fieldLabel	: 'Contract Amount'
                                        ,allowBlank	: false
                                    })
                                    ,standards.callFunction( '_createDateField', {
                                        id              : 'dateAwarded' + module
                                        ,fieldLabel     : 'Date Awarded'
                                        ,maxValue	    : new Date()
                                        ,allowBlank     : false
                                    } )
                                    ,standards.callFunction( '_createDateField', {
                                        id              : 'dateStart' + module
                                        ,fieldLabel     : 'Date Started'
                                        ,maxValue	    : new Date()
                                        ,allowBlank     : false
                                    } )
                                    ,standards.callFunction( '_createDateField', {
                                        id              : 'dateCompleted' + module
                                        ,fieldLabel     : 'Date Completed (as per contract)'
                                        // ,maxValue	    : new Date()
                                        ,allowBlank     : false
                                    } )
                                    ,standards.callFunction( '_createTextField', {
                                        fieldLabel      : 'License Name'
                                        ,id             : 'licenseName' + module
                                        ,allowBlank     : false
                                    } )
                                    ,standards.callFunction( '_createCombo', {
                                        id              : 'licenseType' + module
                                        ,fieldLabel     : 'License Type'
                                        ,store          : licenseTypeStore
                                        ,displayField   : 'name'
                                        ,valueField     : 'id'
                                        ,allowBlank     : false
                                        ,listeners      : {
                                            select	: function( me, record ){
                                                if( this.getValue() == 4 ){
                                                    Ext.getCmp('royaltyPercentage' + module).setVisible( false );
                                                } else {
                                                    Ext.getCmp('royaltyPercentage' + module).setVisible( true );
                                                }
											}
                                        }
                                    } )
                                    ,standards.callFunction( '_createTextField', {
                                        fieldLabel  : 'License Number'
                                        ,id         : 'licenseNumber' + module
                                        ,isDecimal  : true
                                        ,maskRe     : /[0-9.]/
                                        ,maxLength  : 20
                                        ,allowBlank : false
                                    } )
                                    ,standards.callFunction('_createNumberField',{
                                        id			: 'royaltyPercentage' + module
                                        ,module		: module
                                        ,fieldLabel	: 'Royalty Percentage'
                                        ,allowBlank	: false
                                        ,listeners  : {
                                            afterrender: function(){
                                                this.setVisible( false );
                                            }
                                        }
                                    })
                                ]
                            }
                            ,{
                                xtype			: 'container'
                                ,columnWidth	: .5
                                ,layout         : 'hbox'
                                ,style          : 'margin-bottom: 5px;'
                                ,items			: [
                                    {
                                        xtype   : 'label',
                                        html    : 'Location' + Ext.getConstant('REQ') + ':',
                                        width   : 140
                                    }
                                    , _locationGrid()
                                ]
                            }
                            ,{
                                xtype           : 'container'
                                ,columnWidth    : .5
                                ,style          : 'margin-bottom: 5px;'
                                ,items          : [
                                    standards.callFunction( '_createDateRange', {
                                        fromFieldLabel  : "Warranty Date"
                                        ,module         : module
                                        ,width          : 111
                                        ,fromWidth      : 235
                                        ,allowBlank     : false
                                    } )
                                    ,standards.callFunction( '_createTextArea', {
                                        id			    : 'remarks' + module
                                        ,fieldLabel	    : 'Remarks'
                                        ,allowBlank	    : true
                                        ,allowBlank     : false
                                    } )
                                ]
                            }
                        ]
                    }
                    ,{  xtype       : 'fieldset'
                        ,layout     : 'column'
                        ,title      : 'Revised Details'
                        ,id         : 'revisedDetailsContainer' + module   // id of the fieldset
                        ,padding    : 10
                        ,items		: [
                            {
                                xtype			: 'container'
                                ,columnWidth	: .5
                                ,items			: [
                                    standards.callFunction( '_createTextField', {
                                        fieldLabel  : 'Time Extension'
                                        ,id         : 'timeExtension' + module
                                        ,isDecimal  : true
                                        ,maskRe     : /[0-9.]/
                                        ,maxLength  : 20
                                    } )
                                    ,standards.callFunction('_createNumberField',{
                                        id			: 'revisedContractAmount' + module
                                        ,module		: module
                                        ,fieldLabel	: 'Revised Contract Amount'
                                        ,allowBlank	: true
                                    })
                                ]
                            }
                        ]
                    }
                    ,tabPanel(config)
                ]
                ,listItems: _gridHistory()
            } );
        }

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
        
        function _generateProjectID() {
            var idVal = typeof dataHolder['id'] != 'undefined' ? dataHolder['id'] : null;
            if( idVal == null ){
                Ext.Ajax.request({
                    url     : route + 'getProjectID',
                    success : function (response) {
                        var resp        = Ext.decode(response.responseText);
                            idVal       = resp.view[0].id;
                            Ext.getCmp('idConstructionProject' + module).setValue(idVal);
                    }
                });
            }
        }

 
        function _locationGrid() {

            var locationStore = standards.callFunction('_createRemoteStore', {
                fields  : [{name: 'chk', type: 'bool' }, {name: 'idLocation', type: 'number' },'locationName']
                ,url    : route + 'getLocation'
            })
            ,sm = new Ext.selection.CheckboxModel({
                checkOnly   : true
            });

            return standards.callFunction('_gridPanel', {
                id          : 'grdLocation' + module,
                module      : module,
                store       : locationStore,
                height      : 185,
                width       : 208,
                selModel    : sm,
                plugins     : true,
                noPage      : true,
                columns         : [{
                    header      : 'Location Name',
                    dataIndex   : 'locationName',
                    flex        : 1,
                    minWidth    : 80,
                    renderer    : function (val, params, record, row_index) {
                        if (record.data.chk) {
                            sm.select( row_index, true );
                        }
                        return val;
                    }
                }],
                listeners   : {
                    afterrender: function () {
                        locationStore.load({});
                    }
                }
            })
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
                    type            : `<b>${activityDetails[activityDetailsKey]['activityName'].toUpperCase()}</b>`,
                    hasActionColumn : false
                });

                // SUBACTIVITY
                for(var subActivityKey in activityDetails[activityDetailsKey]['subactivity']) {
                    let subActivityDetails  = activityDetails[activityDetailsKey]['subactivity'][subActivityKey]
                        ,totalMaterialCost  = subActivityDetails['materialDetails'].reduce((partialSum, a) => partialSum + parseFloat(a.amount), 0)
                        ,totalLaborCost     = subActivityDetails['laborDetails'].reduce((partialSum, a) => partialSum + parseFloat(a.amount), 0)
                        ,totalEquipCost     = subActivityDetails['equipDetails'].reduce((partialSum, a) => partialSum + parseFloat(a.amount), 0)
                        ,totalDirectCost    = totalMaterialCost + totalLaborCost + totalEquipCost;

                    let indirectDetails = 0, ocmAmount = 0, profitAmount = 0, vatAmount = 0, totalIndirectCost = 0;
                        indirectDetails = subActivityDetails['indirectDetails'][0];
                        ocmAmount       = (indirectDetails['ocm']/100) * totalDirectCost;
                        profitAmount    = (indirectDetails['contractorsProfit']/100) * totalDirectCost;
                        vatAmount       = (indirectDetails['vat']/100) * totalDirectCost;
                        totalIndirectCost = ocmAmount + profitAmount + vatAmount;

                    activityDetails[activityDetailsKey]['subactivity'][subActivityKey]['totalMaterialCost'] = totalMaterialCost;
                    activityDetails[activityDetailsKey]['subactivity'][subActivityKey]['totalEquipCost'] = totalEquipCost;
                    activityDetails[activityDetailsKey]['subactivity'][subActivityKey]['totalLaborCost'] = totalLaborCost;
                    
                    subactivityItemNo = parseInt(subActivityKey) + 1;
                    actDetailsGrd.push({
                        itemNo          : `<b>${roman}.${subactivityItemNo}</b>`, 
                        type            : `<b>${subActivityDetails['subActivityName']}</b>`,
                        key             : subActivityKey,
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
                    for(var materialKey in materialDetailsGrid) {
                        let materialDetails = materialDetailsGrid[materialKey];
                        actDetailsGrd.push({
                            workDescription : materialDetails['itemName'],
                            unitCode        : materialDetails['unit'],
                            qty             : materialDetails['qty'].toFixed(2),
                            unitCost        : materialDetails['unitCost'].toFixed(2),
                            amount          : materialDetails['amount'].toFixed(2),
                            hasActionColumn : false
                        });
                    }
                    actDetailsGrd.push({
                        workDescription : '<b>Material Cost</b>',
                        amount          : `<b>${totalMaterialCost.toFixed(2)}</b>`,
                        hasActionColumn : false
                    });

                    // LABOR
                    actDetailsGrd.push({
                        itemNo          : `<b>${roman}.${subactivityItemNo}.b</b>`, 
                        type            : `<b>Labor</b>`,
                        hasActionColumn : false
                    });

                    let laborDetailsGrid = subActivityDetails['laborDetails'];
                    for(var laborKey in laborDetailsGrid) {
                        let laborDetails = laborDetailsGrid[laborKey];
                        actDetailsGrd.push({
                            workDescription : laborDetails['employeeName'],
                            unitCode        : 'days',
                            qty             : laborDetails['qty'].toFixed(2),
                            unitCost        : laborDetails['unitCost'].toFixed(2),
                            amount          : laborDetails['amount'].toFixed(2),
                            hasActionColumn : false
                        });
                    }
                    actDetailsGrd.push({
                        workDescription : '<b>Labor Cost</b>',
                        amount          : `<b>${totalLaborCost.toFixed(2)}</b>`,
                        hasActionColumn : false
                    });

                    // Equipment
                    actDetailsGrd.push({
                        itemNo          : `<b>${roman}.${subactivityItemNo}.c</b>`, 
                        type            : `<b>Equipment</b>`,
                        hasActionColumn : false
                    });

                    let equipDetailsGrid = subActivityDetails['equipDetails'];
                    for(var equipKey in equipDetailsGrid) {
                        let equipDetails = equipDetailsGrid[equipKey];
                        actDetailsGrd.push({
                            workDescription : equipDetails['truckType'],
                            unitCode        : 'hrs',
                            qty             : equipDetails['qty'].toFixed(2),
                            unitCost        : equipDetails['unitCost'].toFixed(2),
                            amount          : equipDetails['amount'].toFixed(2),
                            hasActionColumn : false
                        });
                    }

                    actDetailsGrd.push(
                        {   workDescription : '<b>Equipment Cost</b>',
                            amount          : `<b>${totalEquipCost.toFixed(2)}</b>`,
                            hasActionColumn : false
                        }
                        ,{   workDescription : '<b>Total Direct Cost</b>',
                            amount          : `<b>${totalDirectCost.toFixed(2)}</b>`,
                            hasActionColumn : false
                        }
                    );
                         
                    actDetailsGrd.push(
                        {   type            : `<b>Indirect Cost</b>`,
                            hasActionColumn : false
                        }
                        ,{  workDescription : 'OCM',
                            qty             : indirectDetails['ocm'] + '%',
                            amount          : ocmAmount.toFixed(2),
                            hasActionColumn : false
                        }
                        ,{  workDescription : 'Contractor\'s Profit',
                            qty             : indirectDetails['contractorsProfit'] + '%',
                            amount          : profitAmount.toFixed(2),
                            hasActionColumn : false
                        }
                        ,{  workDescription : 'VAT',
                            qty             : indirectDetails['vat'] + '%',
                            amount          : vatAmount.toFixed(2),
                            hasActionColumn : false
                        }
                        ,{  workDescription : '<b>Total Indirect Cost</b>',
                            amount          : `<b>${totalIndirectCost.toFixed(2)}</b>`,
                            hasActionColumn : false
                        }
                        ,{  workDescription : '<b>Total Item Cost</b>',
                            amount          : `<b>${(totalIndirectCost + totalDirectCost).toFixed(2)}</b>`,
                            hasActionColumn : false
                        }
                        ,{  workDescription : '<b>Unit Cost</b>',
                            hasActionColumn : false,
                            idActivity      : `<b>${activityDetailsKey}</b>`,
                            amount          : `<b>${(0).toFixed(2)}</b>`,
                            subActivityKey : subActivityKey
                        }
                    );
                }
            }
            Ext.getCmp('grdMaterials' + module).getStore().loadData(actDetailsGrd);
        }

        /* TABPANEL */
        function tabPanel( config ){
            let materialStore = standards.callFunction( '_createRemoteStore', {
                fields  : [
                        'description',
                        'unit',
                        { name: 'itemPrice', type: 'number' },
                        { name: 'approvedQty', type: 'number' },
                        { name: 'approvedAmount', type: 'number' },
                        { name: 'revisedQty', type: 'number' },
                        { name: 'revisedAmount', type: 'number' },
                        { name: 'idItem', type: 'number' },
                        'itemName',
                        'barcode'
                    ]
                ,url    : route + 'getMaterials'
            } )
            ,otherDeductionStore = standards.callFunction( '_createRemoteStore', {
                fields  : ['description', { name: 'amount', type: 'number' }]
                ,url    : route + 'getOtherDeductions'
            } )
            ,projectTeamStore = standards.callFunction( '_createRemoteStore', {
                fields  : [
                        'employeeName',
                        'idEmployee',
                        'classification',
                        'status',
                        'idClassification'
                    ]
                ,url    : route + 'getProjectTeam'
            } )
            ,vatStore = standards.callFunction( '_createRemoteStore', {
                fields  : [
                        'name',
                        'vatName',
                        { name: 'vatPercent', type: 'number' },
                        { name: 'totalVATInclusive', type: 'number' },
                        { name: 'totalVATExclusive', type: 'number' },
                        { name: 'id', type: 'number' },
                        'vatType'
                    ]
                ,url    : route + 'getConstructionProjectVAT'
            } )
            ,vatTypeStore = standards.callFunction( '_createLocalStore' , {
                data        : [ 'Inclusive' ,'Exclusive']
                ,startAt    : 1
            } )
            ,activityGrdStore = standards.callFunction( '_createRemoteStore', {
                fields  : [
                        'itemNo',
                        'type',
                        'workDescription',
                        'unitCode',
                        'qty',
                        'unitCost',
                        'amount',
                        'key',
                        { name: 'subActivityKey', type: 'number' },
                        { name: 'idActivity', type: 'number' },
                        { name: 'hasActionColumn', type: 'bool' },
                    ]
                ,url    : route + 'activityGrd'
                ,autoLoad: false
            } )
            ,employeeTeamStore = standards.callFunction(  '_createRemoteStore' ,{
                fields      :[ {name : 'idEmployee', type : 'number' }, 'employeeName', {name : 'idClassification', type : 'number' }, 'status', 'classification']
                ,url        : route + 'getEmployees'
                ,startAt    :  0
                ,autoLoad   : true
            });

            let activityColumns = [
                {   header       : 'Item No.'
                    ,dataIndex   : 'itemNo'
                }
                ,{   header      : 'Type'
                    ,dataIndex   : 'type'
                    ,width       : 200
                    ,columnWidth : 30
                    ,flex        : 1
                }
                ,{   header      : 'Work Description'
                    ,dataIndex   : 'workDescription'
                    ,width       : 200
                    ,columnWidth : 30
                    ,flex        : 1
                }
                ,{   header     : 'Unit'
                    ,dataIndex  : 'unitCode'
                }
                ,{   header     : 'Quantity'
                    ,dataIndex  : 'qty'
                    ,align      : 'right'
                }
                ,{   header     : 'Unit Cost'
                    ,dataIndex  : 'unitCost'
                    ,align      : 'right'
                }
                ,{   header     : 'Amount'
                    ,dataIndex  : 'amount'
                    ,align      : 'right'
                }
                ,standards.callFunction( '_createActionColumn', {
                    icon        : 'pencil'
                    ,tooltip    : 'Edit record'
                    ,Func       : _activityDetailsWindow
                })
            ]
            ,otherDeductionColumns = [
                {	header          : 'Description'
                    ,dataIndex      : 'description'
                    ,width          : 200
                    ,columnWidth    : 30
                    ,flex           : 1
                    ,editor		    : 'text'
                }
                ,{	header          : 'Amount'
                    ,dataIndex      : 'amount'
                    ,width          : 100
                    ,columnWidth    : 30
                    ,xtype          : 'numbercolumn'
                    ,editor         : 'float'
                    ,summaryType    : 'sum'
                }
            ]
            ,projectTeamColumns = [
                {	header          : 'Employee Name'
                    ,dataIndex      : 'employeeName'
                    ,width          : 200
                    ,columnWidth    : 30
                    ,flex           : 1
                    ,editor         : standards.callFunction( '_createCombo', {
                        fieldLabel      : ''
                        ,id             : 'employeeName' + module
                        ,store			: employeeTeamStore
                        ,emptyText		: 'Select employee name...'
                        ,displayField   : 'employeeName'
                        ,valueField     : 'employeeName'
                        ,listeners      : {
                            select  : function( me, recordDetails, returnedData ){
                                var { 0 : store }   = Ext.getCmp('grdProjectTeam' + module).selModel.getSelection()
                                    ,row            = me.findRecord(me.valueField, me.getValue())
                                    ,msg            = 'The selected employee already exists in the list. You may edit the existing employee or remove it.';

                                if( Ext.isUnique(me.valueField, projectTeamStore, me, msg ) ) {
                                    Ext.setGridData(['idEmployee', 'status', 'idClassification', 'classification'] , store, row)
                                }
                            }
                        }
                    })
                }
                ,{	header          : 'Position'
                    ,dataIndex      : 'classification'
                    ,width          : 200
                    ,columnWidth    : 30
                    ,flex           : 1
                }
                ,{	header          : 'Status'
                    ,dataIndex      : 'status'
                    ,width          : 200
                    ,columnWidth    : 30
                    ,flex           : 1
                }
            ]
            ,vatColumns = [
                {	header          : 'VAT Type'
                    ,dataIndex      : 'vatType'
                    ,width          : 120
                    ,editor         : standards.callFunction( '_createCombo', {
                        fieldLabel      : ''
                        ,id             : 'vatType' + module
                        ,store			: vatTypeStore
                        ,emptyText		: 'Select VAT type...'
                        ,displayField   : 'name'
                        ,valueField     : 'name'
                        ,listeners      : {
                            select  : function( me, recordDetails, returnedData ){
                                var { 0 : store }   = Ext.getCmp('grdVAT' + module).selModel.getSelection()
                                    ,row            = me.findRecord(me.valueField, me.getValue());

                                Ext.setGridData(['id'] , store, row);
                            }
                        }
                    })
                }
                ,{	header          : 'VAT Name'
                    ,dataIndex      : 'vatName'
                    ,width          : 200
                    ,columnWidth    : 30
                    ,flex           : 1
                    ,editor		    : 'text'
                }
                ,{	header          : 'VAT Percent'
                    ,dataIndex      : 'vatPercent'
                    ,width          : 200
                    ,columnWidth    : 30
                    ,flex           : 1
                    ,editor		    : 'text'
                }
                ,{	header          : 'Total Inclusive'
                    ,dataIndex      : 'totalVATInclusive'
                    ,width          : 100
                    ,columnWidth    : 30
                    ,xtype          : 'numbercolumn'
                    ,summaryType    : 'sum'
                }
                ,{	header          : 'Total Exclusive'
                    ,dataIndex      : 'totalVATExclusive'
                    ,width          : 100
                    ,columnWidth    : 30
                    ,xtype          : 'numbercolumn'
                    ,summaryType    : 'sum'
                }
            ];

            function _materialHistory(){
                console.log('test');
            }

            function _setItemDetails( me, grdStore ) {
                var { 0 : store }   = Ext.getCmp('grdMaterials' + module).selModel.getSelection()
                    ,row            = me.findRecord(me.valueField, me.getValue())
                    ,msg            = 'The selected item already exists in the list. You may edit the existing item or remove it.';

                if( Ext.isUnique(me.valueField, grdStore, me, msg ) ) {
                    Ext.setGridData(['idItem', 'itemPrice', 'barcode', 'itemName', 'unit'] , store, row)
                }
            }

            function _deleteDeduction(){
                var selRecord = Ext.getCmp('grdOtherDeductions' + module ).selModel.getSelection()[0];
                otherDeductionStore.remove( selRecord );
            }

            function _deleteMaterial(){
                var selRecord = Ext.getCmp('grdMaterials' + module ).selModel.getSelection()[0];

                activityDetails[selRecord.data.idActivity]['subactivity'].forEach((item, index)=>{
                    if(index == selRecord.data.subActivityKey) activityDetails[selRecord.data.idActivity]['subactivity'].splice(index, 1);
                });

                if(activityDetails[selRecord.data.idActivity]['subactivity'].length == 0) {
                    delete activityDetails[selRecord.data.idActivity];
                }
                
                activityGridData(activityDetails)
            }

            function _deleteTeamMember(){
                var selRecord = Ext.getCmp('grdProjectTeam' + module ).selModel.getSelection()[0];
                projectTeamStore.remove( selRecord );
            }

            function _deleteVAT(){
                var selRecord = Ext.getCmp('grdVAT' + module ).selModel.getSelection()[0];
                vatStore.remove( selRecord );
            }

            // ACTIVITY SETTINGS
            // - ADD ACTIVITY NAME
            function _activitySettingWindow(activityStore) {
                let activityNameOnEdit = 0;

                function _saveActivityName(form) {
                    var params = {
                        onEdit          : activityNameOnEdit
                        ,idActivity     : Ext.getCmp('idActivityName' + module).getValue()
                        ,activityName   : Ext.getCmp('activityName' + module).getValue()
                    }

                    Ext.Ajax.request({
                        url         : route + 'saveActivitySetting'
                        ,params     : params
                        ,method     : 'post'
                        ,success    : function( response ) {
                            var resp    = Ext.decode( response.responseText )
                            ,msg		= ( resp.match == 0 ) ? 'SAVE_SUCCESS' : 'SAVE_FAILURE'
                            ,match      = parseInt( resp.match, 10 );

                            standards.callFunction( '_createMessageBox', {
                                msg     : msg
                                ,action : ''
                                ,fn     : function(){
                                    Ext.getCmp( 'activitySettingForm' + module ).form.reset();
                                }
                            } );
                        }
                    });
                }

                function _deleteActivityName( data ) {
                    standards.callFunction( '_createMessageBox', {
                        msg		: 'DELETE_CONFIRM'
                        ,action	: 'confirm'
                        ,fn		: function( btn ){
                            if( btn == 'yes' ){
                                Ext.Ajax.request({
                                    url 	: route + 'deleteActivityName'
                                    ,params : {
                                        idActivity    : data.id
                                        ,activityName : data.name
                                    }
                                    ,success : function( response ){
                                        var resp = Ext.decode( response.responseText );
                                        if( resp.match == 1 ) {
                                            standards.callFunction( '_createMessageBox', {
                                                msg : 'DELETE_USED'
                                            } );
                                        } else {
                                            Ext.getCmp('activityNameGrid' + module).getStore().load();
                                        }
                                    }
                                });
                            }
                        }
                    } );
                }

                function _editActivityName(data) {
                    activityNameOnEdit = 1;

                    Ext.Ajax.request({
                        url     : route + 'getActivityName'
                        ,method : 'post'
                        ,params : {
                            idActivity  : data.id
                        }
                        ,success : function( response ) {
                            var resp = Ext.decode( response.responseText );
                            Ext.getCmp('idActivityName' + module).setValue(resp.view[1].id);
                            Ext.getCmp('activityName' + module).setValue(resp.view[1].name);
                        }
                    });
                }

                var activitySettingWindow = Ext.create('Ext.window.Window', {
                    id          : 'activitySettingWindow' + module
                    ,title      : 'Activity Setting'
                    ,width      : 600
                    ,modal      : true
                    ,closable   : true
                    ,resizable  : false
                    ,buttonAlign : 'center'
                    ,items : [
                        Ext.create('Ext.form.Panel', {
                            id              : 'activitySettingForm' + module
                            ,border         : false
                            ,bodyPadding    : 5
                            ,items          : [{
                                xtype           : 'container'
                                ,columnWidth    : 0.40
                                ,style          : 'padding: 10px'
                                ,items          : [
                                    {
                                        xtype   : 'hiddenfield'
                                        ,id     : 'idActivityName' + module
                                        ,value  : 0
                                    }
                                    ,standards.callFunction( '_createTextField' ,{
                                        id          : 'activityName' + module
                                        ,fieldLabel : 'Activity Name'
                                        ,allowBlank : false
                                        ,maxLength	: 50
                                    })
                                    ,{  text        : 'Save'
										,xtype      : 'button'
                                        ,iconCls    : 'glyphicon glyphicon-floppy-disk'
                                        ,formBind   : true
										,handler    : function() {
											_saveActivityName( Ext.getCmp( 'activitySettingForm' + module ).form )
										}
										,width      : 60
									}
									,{  text        : 'Reset'
										,xtype      : 'button'
										,iconCls    : 'glyphicon glyphicon-refresh'
										,style      : 'margin-left:5px;'
										,handler    : function(){
                                             Ext.getCmp( 'activitySettingForm' + module ).form.reset();
                                             activityStore.proxy.extraParams.isSelect = false;
                                             activityStore.load();
                                        }
										,width      : 60
                                    }
                                    ,standards.callFunction( '_gridPanel',{
                                        id		        : 'activityNameGrid' + module
                                        ,module	        : module
                                        ,store	        : activityStore
                                        ,style          : 'margin-top:15px;'
                                        ,noDefaultRow   : true
                                        ,height         : 200
                                        ,tbar : {
                                            canPrint        : false
                                            ,noExcel        : true
                                        }
                                        ,columns        : [
                                            {
                                                header          : 'Activity Name'
                                                ,dataIndex      : 'name'
                                                ,width          : 200
                                                ,columnWidth    : 30
                                                ,flex           : 1
                                            }
                                            ,standards.callFunction( '_createActionColumn', {
                                                icon        : 'pencil'
                                                ,tooltip    : 'Edit record'
                                                ,Func       : _editActivityName
                                            })
                                            ,standards.callFunction( '_createActionColumn' ,{
                                                canDelete   : canDelete
                                                ,icon       : 'remove'
                                                ,tooltip    : 'Remove record'
                                                ,Func       : _deleteActivityName
                                            })
                                        ]
                                        ,listeners  : {
                                            afterrender : function( grid ) {
                                                activityStore.removeAt(0);
                                            }
                                        }
                                    })
                                ]
                            }]
                        } )
                    ]
                    ,buttonAlign : 'center'
                    ,buttons     : [
                        {
                            xtype    : 'button'
                            ,text    : 'Done'
                            ,iconCls : 'glyphicon glyphicon-ok-sign'
                            ,handler : function () {
                                activitySettingWindow.destroy(false);
                            }
                        }
                    ]
                });

                return activitySettingWindow;
            }

            // ADD ACTIVITY DETAILS
            // - SELECT ACTIVITY NAME
            // - ADD SUB ACTIVITY NAME
            // - ADD MATERIALS, LABOR, EQUIPMENT, and INDIRECT COST
            function _activityDetailsWindow(data = null) {
                let subactivityOnEdit = 0;

                let activityStore = standards.callFunction( '_createRemoteStore' , {
                    fields      : [ { name: 'id', type: 'number' }, 'name' ]
                    ,url        : route + 'getActivityName'
                })
                ,activityMaterialStore = standards.callFunction( '_createRemoteStore' , {
                    fields      : [
                        'idConsActivityDetailsItem',
                        'idConsActivityDetails',
                        'idItem',
                        'itemName',
                        'unit',
                        { name: 'qty', type: 'float' },
                        { name: 'unitCost', type: 'float' },
                        { name: 'amount', type: 'float' },
                    ]
                    ,url        : route + 'getActivityMaterials'
                })
                ,activityLaborStore = standards.callFunction( '_createRemoteStore' , {
                    fields      : [
                        'name'
                        ,'employeeName'
                        ,'unit'
                        ,{ name: 'idConsActivityDetailsLabor', type: 'number' }
                        ,{ name: 'idConsActivityDetails', type: 'number' }
                        ,{ name: 'idEmployee', type: 'number' }
                        ,{ name: 'unitCost', type: 'float' }
                        ,{ name: 'qty', type: 'float' }
                        ,{ name: 'amount', type: 'float' }
                    ]
                    ,url        : route + 'getActivityLabor'
                })
                ,activityEquipStore = standards.callFunction( '_createRemoteStore' , {
                    fields      : [
                        'truckType',
                        'unit',
                        { name: 'idConsActivityDetailsEquip', type: 'number' },
                        { name: 'idConsActivityDetails', type: 'number' },
                        { name: 'idTruckType', type: 'number' },
                        { name: 'idTruck', type: 'number' },
                        { name: 'unitCost', type: 'float' },
                        { name: 'qty', type: 'float' },
                        { name: 'amount', type: 'float' }
                    ]
                    ,url        : route + 'getActivityEquip'
                })
                ,itemStore = standards.callFunction(  '_createRemoteStore' ,{
                    fields      :[ {name : 'idItem', type : 'number' }, 'itemName', {name : 'itemPrice', type : 'float' }, 'barcode', 'unit']
                    ,url        : route + 'getItems'
                    ,startAt    :  0
                    ,autoLoad   : true
                })
                ,employeeStore = standards.callFunction(  '_createRemoteStore' ,{
                    fields      :[
                        'employeeName',
                        {name : 'monthRate', type : 'float' },
                        {name : 'idEmployee', type : 'number' },
                    ]
                    ,url        : route + 'getEmployees'
                    ,startAt    :  0
                    ,autoLoad   : true
                })
                ,equipStore = standards.callFunction(  '_createRemoteStore' ,{
                    fields      :[
                        'idTruckType',
                        'truckType',
                    ]
                    ,url        : route + 'getTrucks'
                    ,startAt    :  0
                    ,autoLoad   : true
                });

                function _addActivity(form) {
                    let materialDetails  = Ext.getCmp('materialDetails' + module).store.data.items.map((item)=>item.data).filter((item)=>item.idItem !==0 && item.idItem !=="")
                        ,laborDetails    = Ext.getCmp('laborDetails' + module).store.data.items.map((item)=>item.data).filter((item)=>item.idItem !==0 && item.idItem !=="")
                        ,equipDetails    = Ext.getCmp('equipmentDetails' + module).store.data.items.map((item)=>item.data).filter((item)=>item.idItem !==0 && item.idItem !=="")
                        ,indirectDetails = Ext.getCmp('indirectCostDetails' + module).store.data.items.map((item)=>item.data)
                        ,idActivity      = Ext.getCmp('idActivity' + module).getValue()
                        ,subActivityName = Ext.getCmp('subActivityName' + module).getValue()
                        ,idConsSubActivity = Ext.getCmp('idConsSubActivity' + module).getValue();

                        let indirectDetailsData = {};
                    indirectDetails.forEach(element => { indirectDetailsData[element.type] = element.percentage; });
                    indirectDetailsData['idConsSubActivity'] = parseInt(idConsSubActivity);

                    if(!(idActivity in activityDetails)) activityDetails[idActivity] = {};

                    // If edit sub activity
                    if(subactivityOnEdit) {
                        activityDetails[idActivity]['subactivity'].forEach((item, index)=>{
                            if(index == data.key) activityDetails[idActivity]['subactivity'].splice(index, 1);
                        });
                    } 

                    if(typeof activityDetails[idActivity]['subactivity'] == 'undefined') {
                        activityDetails[idActivity]['subactivity'] = [];
                    }

                    let activityName = Ext.getCmp('idActivity' + module).getStore().data.items.map((item)=>item.data).filter((item)=>item.id == idActivity);
                    activityDetails[idActivity]['activityName'] = activityName[0].name;
                    


                    activityDetails[idActivity]['subactivity'].push({
                        subActivityName : subActivityName,
                        materialDetails : materialDetails,
                        laborDetails    : laborDetails,
                        equipDetails    : equipDetails,
                        indirectDetails : [indirectDetailsData],
                    });
                    activityGridData(activityDetails);
                }

                function activateButton() {
                    let idActivity = Ext.getCmp('idActivity' + module).getValue();
                    let subActivityName = Ext.getCmp('subActivityName' + module).getValue();
                    
                    if( (idActivity != null || idActivity != 0) && subActivityName != '') {
                        Ext.getCmp('addBtn' + module).enable();
                    } else {
                        Ext.getCmp('addBtn' + module).disable();
                    }
                }

                function getActivityDetailsColumn(idPrefix, title, gridStore, itemStore) {
                    let id = idPrefix + 'Details' + module
                        ,valueField = 'itemName';

                    if(idPrefix == 'material') {
                        valueField = 'itemName'
                    }
                    if(idPrefix == 'equipment') {
                        valueField = 'truckType'
                    }
                    if(idPrefix == 'labor') {
                        valueField = 'employeeName'
                    }   

                    let col = [
                        standards.callFunction( '_gridPanel', {
                            id		        : id
                            ,module	        : module
                            ,store	        : gridStore
                            ,style          : 'margin-bottom:10px;'
                            ,noDefaultRow   : true
                            ,noPage         : true
                            ,plugins        : true
                            ,height         : 200
                            ,tbar : {
                                canPrint        : false
                                ,noExcel        : true
                                ,content        : 'add'
                            }
                            ,features       : {
                                ftype   : 'summary'
                            }
                            ,columns        : [
                                {   header          : 'Work Description'
                                    ,dataIndex      : valueField
                                    ,width          : 200
                                    ,columnWidth    : 30
                                    ,flex           : 1
                                    ,editor		    : standards.callFunction( '_createCombo', {
                                        fieldLabel      : ''
                                        ,id             : id + 'idItem' + module
                                        ,store			: itemStore
                                        ,emptyText		: 'Select item...'
                                        ,displayField   : valueField
                                        ,valueField     : valueField
                                        ,listeners      : {
                                            select  : function( me, recordDetails, returnedData ){
                                                var { 0 : store }   = Ext.getCmp(id).selModel.getSelection()
                                                ,row                = me.findRecord(me.valueField, me.getValue())
                                                ,msg                = 'The selected item already exists in the list. You may edit the existing item or remove it.';

                                                if( Ext.isUnique(me.valueField, gridStore, me, msg ) ) {
                                                    if(idPrefix == 'material') {
                                                        row.data.unitCost = row.data.itemPrice;
                                                        Ext.setGridData(['idItem', 'unitCost', 'barcode', 'itemName', 'unit'] , store, row)
                                                    }
                                                    if(idPrefix == 'labor') {
                                                        row.data.unitCost = (row.data.monthRate*12)/313;
                                                        row.data.unit = 'days';
                                                        Ext.setGridData(['idEmployee', 'unitCost', 'employeeName', 'unit'] , store, row)
                                                    }
                                                    if(idPrefix == 'equipment') {
                                                        row.data.unitCost = 0;
                                                        row.data.unit = 'hrs';
                                                        Ext.setGridData(['idTruckType', 'unitCost', 'truckType', 'unit'] , store, row)
                                                    }
                                                }
                                            }
                                        }
                                    })
                                }
                                ,{  header          : 'Unit'
                                    ,dataIndex      : 'unit'
                                    ,width          : 60
                                }
                                ,{  header          : 'Quantity'
                                    ,xtype          : 'numbercolumn'
                                    ,editor         : 'float'
                                    ,format         : '0,000'
                                    ,dataIndex      : 'qty'
                                }
                                ,{  header          : 'Unit Cost'
                                    ,xtype          : 'numbercolumn'
                                    ,format         : '0,000.00'
                                    ,dataIndex      : 'unitCost'
                                    ,editor         : 'float'
                                }
                                ,{  header          : 'Amount'
                                    ,xtype          : 'numbercolumn'
                                    ,format         : '0,000.00'
                                    ,dataIndex      : 'amount'
                                    ,summaryType    : 'sum'
                                    ,summaryRenderer: function(value, summaryData, dataIndex){
                                        Ext.getCmp( idPrefix + 'Cost' + module ).setValue( Ext.util.Format.number( value, '0,000.00' ) );
                                        return value;
                                    }
                                }
                            ]
                            ,listeners	    : {
                                beforeedit : function( me, rowData ) {
                                    let workDescCol = rowData.record.data.workDescription;

                                    // VERIFIES IF THE CELL CAN BE MODIFIED
                                    switch( rowData.field ) {
                                        case 'unitCost':
                                            if(idPrefix != 'equipment') {
                                                return false;
                                            }
                                            break;
                                    }
                                }
                                ,edit       : function( me, rowData ) {
                                    var index = rowData.rowIdx
                                    ,store = this.getStore().getRange();
                                    var amount = store[index].data.amount;
                                    switch( rowData.field ) {
                                        case 'qty':
                                            if( rowData.value == 0 ) {
                                                standards.callFunction('_createMessageBox', {
                                                    msg : 'Invalid input. Value must be greater than 0.'
                                                    ,fn: function(){
                                                        store[index].set('qty', rowData.originalValue );
                                                    }
                                                });
                                            }
                                            amount = rowData.value * store[index].data.unitCost;
                                            store[index].set('amount', amount );
                                            break;
                                        case 'unitCost':
                                            if( rowData.value == 0 ) {
                                                standards.callFunction('_createMessageBox', {
                                                    msg : 'Invalid input. Value must be greater than 0.'
                                                    ,fn: function(){
                                                        store[index].set('unitCost', rowData.originalValue );
                                                    }
                                                });
                                            }
                                            amount = rowData.value * store[index].data.qty;
                                            store[index].set('amount', amount );
                                            break;
                                    }
                                    store[index].set('amount', amount );
                                }
                            }
                        })
                        ,standards.callFunction('_createNumberField',{
                            id          : idPrefix + 'Cost' + module
                            ,style  : 'float:right;padding:8px;margin-top:5px;margin-right:5px;'
                            ,fieldLabel : title + ' Cost'
                            ,readOnly   : true
                        })
                    ];

                    return col;
                }

                function getIndirectCostGrid() {
                    let gridStore = standards.callFunction( '_createRemoteStore' , {
                        fields      : [ 'workDescription', 'percentage', 'type' ]
                        ,url        : route + 'getIndirectCost'
                    })

                    let col = [
                        standards.callFunction( '_gridPanel', {
                            id		        : 'indirectCostDetails' + module
                            ,module	        : module
                            ,store	        : gridStore
                            ,style          : 'margin-bottom:10px;'
                            ,noDefaultRow   : true
                            ,noPage         : true
                            ,plugins        : true
                            ,height         : 200
                            ,tbar : {
                                canPrint        : false
                                ,noExcel        : true
                            }
                            ,columns        : [
                                {
                                    header          : 'Work Description'
                                    ,dataIndex      : 'workDescription'
                                    ,flex           : 1
                                }, {
                                    header          : 'Percentage'
                                    ,xtype          : 'numbercolumn'
                                    ,editor         : 'float'
                                    ,format         : '0,000'
                                    ,dataIndex      : 'percentage'
                                }
                            ]
                        })
                    ];

                    let grdMaterials = [
                        { workDescription : 'OCM', type: 'ocm', percentage : 0, },
                        { workDescription : 'Contractor\'s Profit', type: 'contractorsProfit', percentage : 0, },
                        { workDescription : 'VAT', type: 'vat', percentage : 0, }
                    ];

                    Ext.getCmp('indirectCostDetails' + module).getStore().loadData(grdMaterials);
                    return col;
                }

                var addActivityWindow = Ext.create('Ext.window.Window', {
                    id          : 'addActivityWindow' + module
                    ,title      : 'Add Activity Details'
                    ,width      : 800
                    ,modal      : true
                    ,closable   : true
                    ,resizable  : false
                    ,buttonAlign : 'center'
                    ,items : [
                        Ext.create('Ext.form.Panel', {
                            id              : 'addActivityForm' + module
                            ,border         : false
                            ,bodyPadding    : 5
                            ,items          : [
                                {
                                    xtype           : 'container'
                                    ,columnWidth    : 0.40
                                    ,style          : 'padding: 10px'
                                    ,items          : [
                                        standards.callFunction( '_createCombo', {
                                            id              : 'idActivity' + module
                                            ,hasAll         : 1
                                            ,fieldLabel     : 'Activity Name'
                                            ,valueField     : 'id'
                                            ,displayField   : 'name'
                                            ,store          : activityStore
                                            ,allowBlank     : false
                                            ,listeners      : {
                                                beforeQuery  : function( combo ) {
                                                    activityStore.proxy.extraParams.isSelect = true;
                                                }
                                                ,select	     : function( me, record ){

                                                    if ( this.value == 0 ) {
                                                        Ext.getCmp('idActivity' + module).reset();
                                                        _activitySettingWindow(activityStore).show();
                                                    } else {
                                                        activateButton();
                                                    }
                                                },
                                                afterrender: function(me) {
                                                    activityStore.load({});
                                                }
                                            }
                                        } )
                                        ,standards.callFunction( '_createTextField' ,{
                                            id          : 'subActivityName' + module
                                            ,fieldLabel : 'Sub Activity Name'
                                            ,allowBlank : false
                                            ,maxLength	: 50
                                            ,listeners  : {
                                                change : function( me, newValue, oldValue, eOpts ) {
                                                    activateButton();
                                                }
                                            }
                                        })
                                        ,standards.callFunction( '_createTextField' ,{
                                            id          : 'idConsSubActivity' + module
                                            ,fieldLabel : 'ID Construction Sub Activity'
                                            ,maxLength	: 50
                                            ,hidden     : true
                                            ,readOnly   : true
                                        })
                                        ,{
                                            xtype   : 'tabpanel'
                                            ,style  : 'margin-top: 20px;'
                                            ,id     : 'activityPanel' + module   // id of the fieldset
                                            ,items  : [
                                                {
                                                    title   : 'Materials'
                                                    ,items  : getActivityDetailsColumn('material', 'Materials', activityMaterialStore, itemStore)
                                                }
                                                ,{
                                                    title   : 'Labor'
                                                    ,items  : getActivityDetailsColumn('labor', 'Labor', activityLaborStore, employeeStore)
                                                }
                                                ,{
                                                    title   : 'Equipment'
                                                    ,items  : getActivityDetailsColumn('equipment', 'Equipment', activityEquipStore, equipStore)
                                                }
                                                ,{
                                                    title   : 'Indirect Cost'
                                                    ,items  : getIndirectCostGrid()
                                                }
                                            ]
                                        }
                                    ]
                                }
                            ]
                        } )
                    ]
                    ,buttons     : [
                        {
                            xtype     : 'button'
                            ,text     : 'Add'
                            ,id       : 'addBtn' + module
                            ,disabled : true
                            ,iconCls  : 'glyphicon glyphicon-plus'
                            ,handler  : function () {

                                _addActivity( Ext.getCmp( 'addActivityForm' + module ).form );
                                addActivityWindow.destroy(true);
                            }
                        }
                        ,{
                            xtype    : 'button'
                            ,text    : 'Cancel'
                            ,iconCls : 'glyphicon glyphicon-remove-sign'
                            ,handler : function () {
                                addActivityWindow.destroy(true);
                            }
                        }
                    ]
                    ,listeners : {

                    }
                }).show();

                // SUB ACTIVITY EDIT
                if(data != null) {
                    subactivityOnEdit = 1;
                    subActivityData = activityDetails[data.idActivity]['subactivity'][data.key];
                    Ext.getCmp('idConsSubActivity' + module).setValue(subActivityData.indirectDetails[0].idConsSubActivity);
                    Ext.getCmp('idActivity' + module).setValue(parseInt(data.idActivity));
                    Ext.getCmp('idActivity' + module).fireEvent( 'select' );
                    Ext.getCmp('subActivityName' + module).setValue(subActivityData.subActivityName);

                    Ext.getCmp('materialDetails' + module).getStore().loadData(subActivityData.materialDetails);
                    Ext.getCmp('laborDetails' + module).getStore().loadData(subActivityData.laborDetails);
                    Ext.getCmp('equipmentDetails' + module).getStore().loadData(subActivityData.equipDetails);

                    Ext.getCmp('indirectCostDetails' + module).getStore().loadData([
                        { workDescription : 'OCM', type: 'ocm', percentage : subActivityData.indirectDetails[0].ocm, }, 
                        { workDescription : 'Contractor\'s Profit', type: 'contractorsProfit', percentage : subActivityData.indirectDetails[0].contractorsProfit, }, 
                        { workDescription : 'VAT', type: 'vat', percentage : subActivityData.indirectDetails[0].vat, }
                    ]);
                }
            }

            return {
                xtype : 'tabpanel'
                ,items: [
                    {
                        title: 'Activity'
                        ,layout:{
                            type: 'card'
                        }
                        ,items  :   [
                            {	xtype : 'container'
                                ,width : 390
                                ,items : [
                                    standards.callFunction( '_gridPanel',{
                                        id		        : 'grdMaterials' + module
                                        ,module	        : module
                                        ,store	        : activityGrdStore
                                        ,noDefaultRow   : true
                                        ,noPage         : true
                                        ,plugins        : true
                                        ,tbar : {
                                            canPrint        : false
                                            ,noExcel        : true
                                            ,content        : 'add'
                                            ,customAddHandler : _activityDetailsWindow
                                            ,deleteRowFunc    : _deleteMaterial
                                        }
                                        ,features       : {
                                            ftype   : 'summary'
                                        }
                                        ,columns        : activityColumns
                                        ,listeners	    : {
                                           beforeedit : function( me, rowData ) {
                                                let workDescCol = rowData.record.data.workDescription;

                                                // VERIFIES IF THE CELL CAN BE MODIFIED
                                                switch( rowData.field ) {
                                                    case 'amount':
                                                        if(workDescCol != '<b>Unit Cost</b>') {
                                                            return false;
                                                        }
                                                        break;
                                                }
                                            }
                                        }
                                    })
                                ]
                            }
                        ]
                    }
                    ,{
                        title: 'Other Deduction'
                        ,layout:{
                            type: 'card'
                        }
                        ,items  :   [
                            {	xtype : 'container'
                                ,width : 390
                                ,items : [
                                    standards.callFunction( '_gridPanel',{
                                        id		        : 'grdOtherDeductions' + module
                                        ,module	        : module
                                        ,store	        : otherDeductionStore
                                        ,style          : 'margin-bottom:10px;'
                                        ,noDefaultRow   : true
                                        ,noPage         : true
                                        ,plugins        : true
                                        ,tbar : {
                                            canPrint        : false
                                            ,noExcel        : true
                                            ,content        : 'add'
                                            ,deleteRowFunc  : _deleteDeduction
                                        }
                                        ,features       : {
                                            ftype   : 'summary'
                                        }
                                        ,plugins        : true
                                        ,columns        : otherDeductionColumns
                                        ,listeners	    : {
                                            afterrender : function() {
                                                materialStore.load({});
                                            }
                                            ,edit       : function( me, rowData ) {
                                            }
                                        }
                                    })
                                ]
                            }
                        ]
                    }
                    ,{
                        title: 'Project Team'
                        ,layout:{
                            type: 'card'
                        }
                        ,items  :   [
                            {	xtype : 'container'
                                ,width : 390
                                ,items : [
                                    standards.callFunction( '_gridPanel',{
                                        id		        : 'grdProjectTeam' + module
                                        ,module	        : module
                                        ,store	        : projectTeamStore
                                        ,style          : 'margin-bottom:10px;'
                                        ,noDefaultRow   : true
                                        ,noPage         : true
                                        ,plugins        : true
                                        ,tbar : {
                                            canPrint        : false
                                            ,noExcel        : true
                                            ,content        : 'add'
                                            ,deleteRowFunc  : _deleteTeamMember
                                        }
                                        ,features       : {
                                            ftype   : 'summary'
                                        }
                                        ,plugins        : true
                                        ,columns        : projectTeamColumns
                                        ,listeners	    : {
                                            afterrender : function() {
                                                projectTeamStore.load({});
                                            }
                                            ,edit       : function( me, rowData ) {
                                            }
                                        }
                                    })
                                ]
                            }
                        ]
                    }
                    ,{
                        title: 'VAT'
                        ,layout:{
                            type: 'card'
                        }
                        ,items  :   [
                            {	xtype : 'container'
                                ,width : 390
                                ,items : [
                                    standards.callFunction( '_gridPanel',{
                                        id		        : 'grdVAT' + module
                                        ,module	        : module
                                        ,store	        : vatStore
                                        ,style          : 'margin-bottom:10px;'
                                        ,noDefaultRow   : true
                                        ,noPage         : true
                                        ,plugins        : true
                                        ,tbar : {
                                            canPrint        : false
                                            ,noExcel        : true
                                            ,content        : 'add'
                                            ,deleteRowFunc  : _deleteVAT
                                        }
                                        ,features       : {
                                            ftype   : 'summary'
                                        }
                                        ,plugins        : true
                                        ,columns        : vatColumns
                                        ,listeners	    : {
                                            afterrender : function() {
                                                vatStore.load({});
                                            }
                                            ,edit       : function( me, rowData ) {
                                                var index = rowData.rowIdx
                                                ,store = this.getStore().getRange();

                                                var
                                                    totalVATInclusive   = 0
                                                    ,totalVATExclusive  = 0
                                                    ,contractAmount     = Ext.getCmp('contractAmount' + module).getValue();

                                                switch( rowData.field ) {
                                                    case 'vatPercent':
                                                        if( rowData.value == 0 ) {
                                                            standards.callFunction('_createMessageBox', {
                                                                msg : 'Invalid input. Value must be greater than 0.'
                                                                ,fn: function(){
                                                                    store[index].set('vatPercent', rowData.originalValue );
                                                                }
                                                            });
                                                        }

                                                        if( store[index].data.id == 1 ) totalVATInclusive = 0;
                                                        else totalVATExclusive = ( rowData.value / 100 ) * contractAmount;

                                                        store[index].set('totalVATInclusive', totalVATInclusive );
                                                        store[index].set('totalVATExclusive', totalVATExclusive );
                                                        break;
                                                }
                                            }
                                        }
                                    })
                                ]
                            }
                        ]
                    }
                ]
            }
        }
        /* END OF TABPANEL */

        function _saveForm( form ){
            var selectedRows = Ext.getCmp('grdLocation' + module).getSelectionModel().getSelection()
				,locations = [];
			if( typeof selectedRows != 'undefined' && selectedRows.length > 0  ){
				selectedRows.map( (col, i) => {
					locations.push( {idLocation: col.data.idLocation} );
				});

                var params = {
                    onEdit                  : onEdit
                    ,activityDetails        : Ext.encode(activityDetails)
                    ,locationGrid           : Ext.encode(locations)
                    ,materialGrid           : Ext.encode ( Ext.getCmp('grdMaterials'+module).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0) )
                    ,otherDeductionGrid     : Ext.encode ( Ext.getCmp('grdOtherDeductions'+module).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0) )
                    ,projectTeamGrid        : Ext.encode ( Ext.getCmp('grdProjectTeam'+module).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0) )
                    ,VATGrid                : Ext.encode ( Ext.getCmp('grdVAT'+module).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0) )
                    ,invoices : Ext.encode ({
                        idAffiliate         : idAffiliate
                        ,idCostCenter       : Ext.getCmp('idCostCenter'+module).getValue()
                        ,idReference        : Ext.getCmp('idReference'+module).getValue()
                        ,idReferenceSeries  : Ext.getCmp('idReferenceSeries'+module).getValue()
                        ,date			    : Ext.getCmp( 'tdate' + module).getValue()
					    ,time			    : Ext.Date.format(Ext.getCmp( 'ttime' + module).getValue(), 'h:i:s A')
                        ,referenceNum       : Ext.getCmp( 'referenceNum' + module).getValue()
                        ,cancelTag          : 0
                        ,dateModified       : new Date()
                        ,hasJournal         : 0
                        ,status             : 2 //APPROVED
                        ,archived           : 0
                        ,cancelledBy        : 0
                        ,idModule           : idModule
                        ,amount             : Ext.getCmp( 'contractAmount' + module).getValue()
                        ,idInvoice          : ( typeof dataHolder['idInvoice'] != 'undefined' ) ? dataHolder['idInvoice'] : null
                    })
                    ,constructionproject : Ext.encode ({
                        isManual                        : Ext.getCmp( 'isManual' + module).getValue()
                        ,manualIDConstructionProject    : ( Ext.getCmp( 'isManual' + module).getValue() == true ) ? Ext.getCmp( 'idConstructionProject' + module).getValue() : null
                        ,remarks                        : Ext.getCmp( 'remarks' + module).getValue()
                        ,projectName                    : Ext.getCmp( 'projectName' + module).getValue()
                        ,idContract                     : Ext.getCmp( 'idContract' + module).getValue()
                        ,contractDuration               : Ext.getCmp( 'contractDuration' + module).getValue()
                        ,dateAwarded			        : Ext.getCmp( 'dateAwarded' + module).getValue()
                        ,dateStart			            : Ext.getCmp( 'dateStart' + module).getValue()
                        ,dateCompleted			        : Ext.getCmp( 'dateCompleted' + module).getValue()
                        ,warrantyDateFrom			    : Ext.getCmp( 'sdate' + module).getValue()
                        ,warrantyDateTo			        : Ext.getCmp( 'edate' + module).getValue()
                        ,idConstructionProject          : ( typeof dataHolder['id'] != 'undefined' ) ? dataHolder['id'] : null
                        ,timeExtension                  : Ext.getCmp( 'timeExtension' + module).getValue()
                        ,revisedContractAmount          : Ext.getCmp( 'revisedContractAmount' + module).getValue()
                        ,licenseNumber                  : Ext.getCmp( 'licenseNumber' + module).getValue()
                        ,licenseType                    : Ext.getCmp( 'licenseType' + module).getValue()
                        ,licenseName                    : Ext.getCmp( 'licenseName' + module).getValue()
                        ,royaltyPercentage              : Ext.getCmp( 'royaltyPercentage' + module).getValue()
                        ,status                         : Ext.getCmp( 'status' + module).getValue()
                        ,statusType                     : Ext.getCmp( 'statusType' + module).getValue()
                    })
                }
                _submitForm( form, params );
            } else {
                standards.callFunction( '_createMessageBox', { msg	: 'Please select atleast one location.' } );
            }
        }

        function _resetForm( form ){
			form.reset();
			Ext.resetGrid( 'grdMaterials' + module );
            Ext.resetGrid( 'grdOtherDeductions' + module );
            Ext.resetGrid( 'grdProjectTeam' + module );
            Ext.resetGrid( 'grdVAT' + module );
            Ext.resetGrid( 'grdLocation' + module );

            Ext.getCmp('idConstructionProject' + module).fireEvent('afterrender');

            /* Clear global values */
            dataHolder = {};
            onEdit = 0;
            activityDetails = {};
            
            _generateProjectID();
        }

        function _submitForm( form, params ){
            form.submit({
                url : route + 'saveForm'
                ,params : params
                ,success : function( action, response ){
                    var resp 	= Ext.decode( response.response.responseText ),
                    msg			= ( resp.match == 0 ) ? 'SAVE_SUCCESS' : 'SAVE_FAILURE'
                    ,match      = parseInt( resp.match, 10 );

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

        function _gridHistory() {
            var store   = standards.callFunction( '_createRemoteStore', {
                fields      : [
                    'affiliateName'
                    ,'date'
                    ,'referenceNum'
                    ,'idConstructionProject'
                    ,'projectName'
                    ,'contractDuration'
                    ,'projectName'
                    ,{ name: 'contractAmount'  ,type: 'number' }
                    ,'id'
                    ,'contractEffectivity'
                    ,'contractExpiry'
                    ,'licenseNumber'
                    ,'status'
                    ,'idInvoice'
                ]
                ,url        : route + 'viewAll'
            } );

            return standards.callFunction('_gridPanel', {
                id 					: 'gridHistory' + module
                ,module     		: module
                ,store      		: store
				,height     		: 265
				,noDefaultRow 		: true
                ,columns            : [
                    {  header          : 'Affiliate'
                        ,dataIndex      : 'affiliateName'
                        ,flex           : 1
                        ,minWidth       : 150
                        ,columnWidth    : 40
                    }
                    ,{  header          : 'Date'
                        ,dataIndex      : 'date'
                        ,width          : 100
                        ,columnWidth    : 20
                        ,xtype          : 'datecolumn'
                        ,format         : 'm/d/Y'
                    }
                    ,{   header         : 'Reference'
                        ,dataIndex      : 'referenceNum'
                        ,width          : 100
                        ,columnWidth    : 20
                    }
                    ,{  header          : "Project ID"
                        ,dataIndex      : 'idConstructionProject'
                        ,minWidth       : 150
                        ,columnWidth    : 40
                    }
                    ,{  header          : "Project Name"
                        ,dataIndex      : 'projectName'
                        ,flex           : 1
                        ,minWidth       : 150
                        ,columnWidth    : 40
                    }
                    ,{  header          : "Contract Duration"
                        ,dataIndex      : 'contractDuration'
                        ,minWidth       : 150
                        ,columnWidth    : 40
                    }
                    ,{  header          : 'Contract Amount'
                        ,dataIndex      : 'contractAmount'
                        ,width          : 155
                        ,xtype          : 'numbercolumn'
                        ,format         : '0,000.00'
                        ,columnWidth    : 20
                        ,sortable       : false
                    }
                    ,{  header          : 'Contract Effectivity'
                        ,dataIndex      : 'contractEffectivity'
                        ,width          : 120
                        ,columnWidth    : 20
                        ,xtype          : 'datecolumn'
                        ,format         : 'm/d/Y'
                    }
                    ,{  header          : 'Contract Expiry'
                        ,dataIndex      : 'contractExpiry'
                        ,width          : 120
                        ,columnWidth    : 20
                        ,xtype          : 'datecolumn'
                        ,format         : 'm/d/Y'
                    }
                    ,{  header          : 'License Used'
                        ,dataIndex      : 'licenseNumber'
                        ,flex           : 1
                        ,minWidth       : 150
                        ,columnWidth    : 40
                    }
                    ,{  header          : 'Status'
                        ,dataIndex      : 'status'
                        ,minWidth       : 100
                        ,columnWidth    : 40
                    }
                    ,standards.callFunction( '_createActionColumn', {
                        canEdit     : canEdit
                        ,icon       : 'th-list'
                        ,tooltip    : 'History'
                        ,Func       : _recordHistory
                    } )
                    ,standards.callFunction( '_createActionColumn', {
						canEdit     : canEdit
						,icon       : 'pencil'
						,tooltip    : 'Edit record'
						,Func       : _editRecord
                    } )
                    ,standards.callFunction( '_createActionColumn', {
						canDelete   : canDelete
						,icon       : 'remove'
						,tooltip    : 'Delete record'
						,Func       : _deleteRecord
					} )
                ]
            } )
        }

        function _recordHistory( data ){
            console.log( data );
        }

        function _editRecord( data ){
			onEdit = 1;
			module.getForm().retrieveData({
				url				: route + 'retrieveData'
				,params			: {
					id : data.id
				}
				// ,excludes		: [ 'idProject' ]
				,hasFormPDF		: true
				,success 		: function( response, match ){
					dataHolder = response;
                    
                    Ext.getCmp('referenceNum' + module).setValue( parseInt(dataHolder.referenceNum) );
                    if( dataHolder.locations != null ) {
                        var grdLocationStore = Ext.getCmp( 'grdLocation' + module ).store;
                        var locations = dataHolder.locations.split(",", dataHolder.locations.length );
                        grdLocationStore.load({
                            params	: {
                                locations : Ext.encode( locations )
                            }
                            ,callback	: function(){
                                Ext.getCmp( 'grdLocation' + module ).getView().refresh();
                            }
                        });
                    }

                    Ext.getCmp('grdOtherDeductions' + module).getStore().proxy.extraParams.idConstructionProject = dataHolder.idConstructionProject;
                    Ext.getCmp('grdOtherDeductions' + module).getStore().load({});

                    Ext.getCmp('grdProjectTeam' + module).getStore().proxy.extraParams.idConstructionProject = dataHolder.idConstructionProject;
                    Ext.getCmp('grdProjectTeam' + module).getStore().load({});

                    Ext.getCmp('grdVAT' + module).getStore().proxy.extraParams.idConstructionProject = dataHolder.idConstructionProject;
                    Ext.getCmp('grdVAT' + module).getStore().load({});

                    Ext.Ajax.request({
                        url 	 : route + 'getConSubactivity'
                        ,params  : { idConstructionProject: parseInt(dataHolder.id) }
                        ,success : function( response ){
                            var resp = Ext.decode( response.responseText );
     
                            activityDetails = resp.view.length != 0 ? resp.view : {};
                            activityGridData(activityDetails);
                        }
                    });
				}
			});
		}

        function _deleteRecord( data ) {
            standards.callFunction( '_createMessageBox', {
                msg		: 'DELETE_CONFIRM'
                ,action	: 'confirm'
                ,fn		: function( btn ){
                    if( btn == 'yes' ){
                        Ext.Ajax.request({
                            url 	: route + 'deleteRecord'
                            ,params : { idInvoice: data.idInvoice, id: data.id }
                            ,success : function( response ){
                                var resp = Ext.decode( response.responseText );
                                if( resp.match == 1 ) {
                                    standards.callFunction( '_createMessageBox', {
                                        msg : 'DELETE_USED'
                                    } );
                                } else {
                                    Ext.getCmp('gridHistory' + module).getStore().load();
                                }
                            }
                        });
                    }
                }
            } );
        }

		function _customListPDF() {
			Ext.Ajax.request({
				url  		: route + 'customListPDF'
				,params 	: {
					items : Ext.encode( Ext.getCmp('gridHistory'+module).store.data.items.map((item)=>item.data) )
				}
				,success 	: function(response){
					if( isGae == 1 ){
						window.open(route+'viewPDF/Construction Project List.pdf','_blank');
					}else{
						window.open('pdf/construction/Construction Project List.pdf');
					}
				}
			});
		}

		function _printExcel(){
            Ext.Ajax.request({
                url: route + 'printExcel'
                ,params: {
                    idmodule    : idModule
                    ,pageTitle  : pageTitle
                    ,limit      : 50
                    ,start      : 0
                    ,items      : Ext.encode( Ext.getCmp('gridHistory'+module).store.data.items.map((item)=>item.data) )
                }
                ,success: function(res){
                    var path  = route.replace( baseurl, '' );
                    window.open(baseurl + path + 'download' + '/' + pageTitle);
                }
            });
        }

        function _printPDFForm() {
			var par  = standards.callFunction('getFormDetailsAsObject',{ module : module });
			Ext.Ajax.request({
                url			: route + 'generatePDF'
                ,method		:'post'
                ,params		: {
                    moduleID    	    : idModule
                    ,title  	        : pageTitle
                    ,limit      	    : 50
                    ,start      	    : 0
					,printPDF   	    : 1
					,form			    : Ext.encode ( par )
                    ,grdLocation        : Ext.encode ( Ext.getCmp('grdLocation'+module).store.data.items.map((item)=>item.data).filter((item)=>item.chk) )
                    ,materialGrid       : Ext.encode ( Ext.getCmp('grdMaterials' + module).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0) )
                    ,otherDeductionGrid : Ext.encode ( Ext.getCmp('grdOtherDeductions' + module).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0) )
                    ,projectTeamGrid    : Ext.encode ( Ext.getCmp('grdProjectTeam'+module).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0) )
                    ,VATGrid            : Ext.encode ( Ext.getCmp('grdVAT'+module).store.data.items.map((item)=>item.data).filter((item)=>item.id !==0) )
                    ,idInvoice		    : dataHolder.idInvoice
                    ,idAffiliate	    : dataHolder.idAffiliate
                    ,affiliateName	    : dataHolder.affiliateName
                }
                ,success	: function(response, action){
                    if( isGae == 1 ){
						window.open(route+'viewPDF/Construction Project Form.pdf','_blank');
					}else{
						window.open('pdf/construction/Construction Project Form.pdf');
					}
                }
			});
		}

        return {
            initMethod:function( config ){
                route		        = config.route;
                baseurl		        = config.baseurl;
                module		        = config.module;
                canPrint	        = config.canPrint;
                canDelete	        = config.canDelete;
                canEdit		        = config.canEdit;
                canCancel           = config.canCancel;
                pageTitle           = config.pageTitle;
                idModule	        = config.idmodule
                isGae		        = config.isGae;
                idAffiliate         = config.idAffiliate
                selRec              = config.selRec;
                componentCalling    = config.componentCalling;

                return _mainPanel( config );
            }
        }
    }
}