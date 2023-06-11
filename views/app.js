
Ext.onReady(function(){

	Ext.tip.QuickTipManager.init();
	Ext.apply(Ext.QuickTips.getQuickTip(), {
        dismissDelay: 0,
        //showDelay: 100,
        trackMouse: true,
        hideDelay: 200
    });

	
/*function showFilters(btn) {
//console.log('showFilters:',Ext.get(btn).dom.src);
	var img_src = Ext.get(btn).dom.src;

	if ( img_src.search("plus") >= 0 ) {
		Ext.get(btn).dom.src = img_src.replace("plus","minus");//'.../funnel--minus.png'	
	} else {
		Ext.get(btn).dom.src = img_src.replace("minus","plus");//'.../funnel--plus.png'				
	}

	var filters;
	me = Ext.ComponentQuery.query("#myGrid")[0];//itemId: 'myGrid',
	
	filters = me.getFilterBar();
	if (!filters) {
		console.warn('Cant find filter plugin for this grid');
	}
	filters.setVisible.call(me, !me._filterBarPluginData.visible);
}*/

function renderDateFormat(date,format) {	
    if (Ext.isIE) { 	
    	if (Ext.isDate(date)) { date = Ext.Date.toString(date); }
    	date = Ext.Date.parse(date,"c"); //The "c" is the format type for ISO 8601 formatted dates										
    }
	return Ext.Date.format(date, format);
};
function renderTooltip(value, meta, record) {
        //var max = 200;
        var str = value;
	//	var str= str.replaceAll('"', '&quot');  // doesn't work in IE !!!
		var str = str.replace(/"/g, '&quot');
    
        meta.tdAttr = 'data-qtip="' + str + '"';
        return value;
        //return value.length < max ? value : value.substring(0, max - 3) + '...';
};	

function UsersBbar() {
		users_bbar = Ext.create('Ext.PagingToolbar', { //Global
							store: users_store,
							displayInfo: true,
							displayMsg: 'Записи {0} - {1} из {2}',
							emptyMsg: 'Нет записей для отображения',
							
							doRefresh : function(button){		
								var filter_name = button.up('panel').down('#filter_name'); filter_name.setValue(''); filter_name.getTrigger('clear').hide();
								var filter_education = button.up('panel').down('#filter_education'); filter_education.setValue(''); filter_education.getTrigger('clear').hide();
								var filter_city = button.up('panel').down('#filter_city'); filter_city.setValue(''); filter_city.getTrigger('clear').hide();
								
								grid = this.up('panel');//myGrid;
								plugin = grid.getFilterBar();
								plugin.clearFilters.call(grid);
								grid.getStore().reload();

								users_store.getProxy().extraParams = {
									req: 'session',
									aj_action: 'getUsers',
									filter_name: '',
									filter_education: '',
									filter_city: '',

								};			
								users_bbar.moveFirst();//goto 1 page
															 
							},
							
							onPagingKeyDown : function(page){

								users_bbar.moveFirst();//goto 1 page

							},
						

						});	
						
		return users_bbar;						
	};
	
	var users_store = Ext.create('Ext.data.Store', {		
		fields: [
					{name: 'idUser'},
					{name: 'userName', type: 'string'},//пользователь
					{name: 'education', type: 'string'},//образование
					{name: 'city', type: 'string'},//город					
					{name: 'email', type: 'string'},
					{name: 'phone', type: 'string'},
					{name: 'comments', type: 'string'},

		],
		//autoDestroy: true,
		autoLoad: true,
		proxy: {
			type: 'ajax', actionMethods: {create: 'POST',read: 'POST',update: 'POST',destroy: 'POST'},
			url: 'ajaxrequest.php',
			extraParams: {req: 'session',aj_action: 'getUsers'},
			reader: {
				type: 'json',
				rootProperty: 'ajaxResponse',
				idProperty: 'idUser'
			}
		},
		listeners: {
			load: function(store, records, success, operation) {
console.log('328----users_store--load---------success-------',success);					
				if (success) {
					var response = operation.getResponse();
					var json = Ext.util.JSON.decode(response.responseText);		
console.log('328----users_store--load---------success-------',json);											
					//if (json.loginStatus != "ok") {
					//	ArchMain.main.m_logout(json.errCode,json.errText);
					//}										
				} else {
					//ArchMain.main.m_logout(1001,'ajax failure');
				}
		
			}
		}
	});		
		
	function EditUser(record) {
console.log('EditUser:',record.data.userName);	
//alert('EditUser: '+record.data.userName);
		//idUser = record.data.idUser;
		//userName = record.data.userName;
		var form = form_user.getForm();
		form.findField('idUser').setValue(record.data.idUser);
		form.findField('userName').setValue(record.data.userName);
		form.findField('education').setValue(record.data.education);
		form.findField('city').setValue(record.data.city);
		form.findField('email').setValue(record.data.email);
		form.findField('phone').setValue(record.data.phone);
		form.findField('comments').setValue(record.data.comments);
		win_user.show();
	
	};
	
	function UpdateUsersGrid() {	
	
		var filter_name = Ext.ComponentQuery.query('#filter_name')[0].getValue();
		var filter_education = Ext.ComponentQuery.query("#filter_education")[0].getValue();
		var filter_city = Ext.ComponentQuery.query('#filter_city')[0].getValue();

		users_store.getProxy().extraParams = {
			req: 'session',
			aj_action: 'getUsers',
			filter_name: filter_name,
			filter_education: filter_education,
			filter_city: filter_city,
		};								

		users_bbar.moveFirst();//goto 1 page		
	};	
	
	//var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>'; //afterLabelTextTpl
	var regexemail = /^([a-zA-Z0-9_\-\.]{1,64})@([a-zA-Z0-9\-\.]+)\.([a-zA-Z]{2,18})$/; //vtype:'email'
	var form_user = new Ext.form.Panel({
		//labelWidth: 155,
		id: "id_form_user",
		bodyPadding: 10,
		frame:true,
		labelAlign: 'left',
		waitMsgTarget: true,
		layout: 'anchor',
		defaults: {
			xtype: 'textfield',
			anchor: '100%',
			//width: 410,
			labelWidth: 120,					
			maxLength: 255,
			//labelAlign: 'right',
			bodyStyle: "background-color:transparent;",
			msgTarget: 'side',
		},
		//defaultType: 'textfield',
		
		items: [
		
			{fieldLabel: 'Пользователь', name: 'userName', allowBlank:false,
				readOnly:true,
				fieldStyle: "background-color:transparent;background-image:none;",			
			},

			{fieldLabel: 'Образование',
				name: 'education',	xtype: 'combo',
				store: new Ext.data.SimpleStore({
					fields: ['education'],
					data : [['высшее'],['среднее'],['бакалавр'],['магистр']]
				}),
				displayField: 'education',	valueField: 'education',
				editable: false,
				queryMode: 'local',
				allowBlank:false,

			},
			{fieldLabel: 'Город', name: 'city', allowBlank:true,
				readOnly:true,
				fieldStyle: "background-color:transparent;background-image:none;",	
			},			
			{fieldLabel: 'Email', name: 'email', allowBlank:true,
				readOnly:true,
				fieldStyle: "background-color:transparent;background-image:none;",	
				regex: regexemail, regexText: 'username@test.ru',			
			},
			{fieldLabel: 'Телефон', name: 'phone', allowBlank:true,
				readOnly:true,
				fieldStyle: "background-color:transparent;background-image:none;",	
			},	
			{xtype: 'textareafield',
				fieldLabel: 'Комментарий', name: 'comments',         
				allowBlank:true,
				readOnly:true,
				height: 40,
				fieldStyle: "background-color:transparent;background-image:none;",	
			},			
			{name: 'idUser',xtype: 'hidden',value:0 },			
						
		]			
	});	


	var	win_user = new Ext.Window({
		layout:'fit',closeAction: 'hide',plain: true,border:false,
		//iconCls:'arch_ico_user_edit',
		title: 'Редактирование',
		modal: true,
		//height:460,
		autoHeight: true,
		width: 480,
		items: form_user,
		buttons:[
					
			{	xtype: 'button',
				minWidth:85,
				text: 'Сохранить',
				handler: function(){	


console.log('626--win user: Сохранить');							

					//p_Submit(p_winObj.actionName);

		form_user.getForm().submit({
			clientValidation:true,
			url: 'ajaxrequest.php',
			params: { 
				req: "session",
				aj_action: 'editUser'
			},
			success: function(form, action) {
console.log('--323--p_form_user.getForm().submit--');							
				if (action.result.errors) {

				}
				else {
console.log('--328--p_form_user.getForm().submit--');							
					win_user.hide();

					UpdateUsersGrid();
					
				}
							
			},
			failure: function(form, action) {
							
			},
			waitMsg : 'please_wait'
		});									

				
				}
			},
			{	xtype: 'button',minWidth: 85,
				text: 'Закрыть',	
				handler: function() {
					win_user.hide();
				}		
			}
		],
		listeners: {
			show: function() {
				
console.log('--win_user.show--');
				//form_user.getForm().reset();
				//form_user.getForm().findField('userName').setValue(userName);

				/*form_user.getForm().load({
						url: 'ajaxrequest.php',
						params: { 
							req: "session", 
							aj_action: "getUsers",
							idUser: idUser,
						},
						success: function(form, action){
						},
						failure: function(form, action){
						},

				});*/
			}

		}		
		
	});	
	
	var optionStoreEducation = Ext.create('Ext.data.Store', {
		fields: ['education'],
		autoLoad: true,
		proxy: {
			type: 'ajax', actionMethods: {create: 'POST',read: 'POST',update: 'POST',destroy: 'POST'},
			url: 'ajaxrequest.php',
			extraParams: {req: 'session',aj_action: 'getEducations'},
			reader: {
				type: 'json',
				rootProperty: 'ajaxResponse'
			}
		},
	});	
	var optionStoreCity = Ext.create('Ext.data.Store', {
		fields: ['city'],
		autoLoad: true,
		proxy: {
			type: 'ajax', actionMethods: {create: 'POST',read: 'POST',update: 'POST',destroy: 'POST'},
			url: 'ajaxrequest.php',
			extraParams: {req: 'session',aj_action: 'getCities'},
			reader: {
				type: 'json',
				rootProperty: 'ajaxResponse'
			}
		},
	});		
						

	myGrid = Ext.create('Ext.grid.Panel', {
			renderTo: 'container',

			title: 'Пользователи',
			itemId: 'myGrid',
			store: users_store,//myStore,
			emptyText: 'Нет записей для отображения',
			viewConfig: {
				stripeRows: true,
				enableTextSelection: true,
				getRowClass: function (record) {
					return 'normal-row';
				}
			},		
			tbar: {
				items: [
				
									{
										xtype: 'textfield',
										itemId: 'filter_name',
										name: 'filter_name',
										fieldLabel: 'Поиск',
										emptyText: 'пользователь...',
										minChars: 3,
										labelWidth: 40,
										labelAlign: 'right',
										width: 300,
										
										triggers: {
											clear: {
												cls: Ext.baseCSSPrefix + 'form-clear-trigger',
												hidden: true,
												handler: function(me) {
													me.setValue('');
													me.getTrigger('clear').hide();
													me.updateLayout();													
													UpdateUsersGrid();
												}
											},
										},

										listeners: {									
											change: function(me) {
												var filter_name_value=this.up('panel').down('#filter_name').getValue();
												if (filter_name_value.length >= 3) {	
													me.getTrigger('clear').show();
													me.updateLayout();													
													UpdateUsersGrid();																						
												} else if (filter_name_value.length == 0) {													
													UpdateUsersGrid();													
												}
											}
										}
									},
//----------------------------------------------------------------------------------------------------------------------------------------									
									{
										xtype: 'combobox',
										itemId: 'filter_education',
										name: 'filter_education',
										fieldLabel: 'Образование',
										emptyText: 'образование...',
										editable: true,
										hideTrigger: false,
										labelWidth: 120,
										labelAlign: 'right',
										width: 270,
										queryMode: 'local',   
										store: optionStoreEducation,
										typeAhead: false,
										displayField: 'education',
										valueField: 'education',
										
										triggers: {
											clear: {
												cls: Ext.baseCSSPrefix + 'form-clear-trigger',
												hidden: true,
												weight: -1, // negative to place before default triggers

												handler: function(me) {
													me.setValue('');
													me.getTrigger('clear').hide();
													me.updateLayout();
													
													UpdateUsersGrid();
													

												}
											},
										},

										listeners: {
											select: function(me) {	
console.log('374--filter_education',me.value);											
												me.getTrigger('clear').show();
												me.updateLayout();
												
												UpdateUsersGrid();
																							
											}
										},										
									},
//----------------------------------------------------------------------------------------------------------------------------------------									
									{
										xtype: 'combobox',
										itemId: 'filter_city',
										name: 'filter_city',
										fieldLabel: 'Город',
										emptyText: 'город...',
										editable: true,
										hideTrigger: false,
										labelWidth: 70,
										labelAlign: 'right',
										width: 250,
										queryMode: 'local',   
										store: optionStoreCity,
										typeAhead: false,
										displayField: 'city',
										valueField: 'city',
										
										triggers: {
											clear: {
												cls: Ext.baseCSSPrefix + 'form-clear-trigger',
												weight: -1, // negative to place before default triggers
												hidden: true,
												handler: function(me) {
													me.setValue('');
													me.getTrigger('clear').hide();
													me.updateLayout();
													
													UpdateUsersGrid();
												}
											},
										},

										listeners: {
											select: function(me) {	
												me.getTrigger('clear').show();
												me.updateLayout();											

												UpdateUsersGrid();
												
											}
										},

									},									
				
					/*'->',
					{
						xtype: 'button',
						text: 'Новый пользователь',
						tooltip: 'Новый пользователь',
						iconCls:'icon-menu-add',

						handler: function() {

						}
										
					},*/
				],
														
			},	
			columns: [
				{
					header: '',
					width: 29, fixed: true,hideable: false, sortable: false, menuDisabled: true,
				
					renderer: function(value, meta, record) {										
						var my_tip = 'Редактировать';										
						meta.tdAttr = 'data-qtip="'+my_tip+'"';
						return '<img style="cursor: pointer;" width="16" height="16" src="img/user_info_16.png" />'
										
					}			
				
				},
				{
					text: 'Пользователь',
					dataIndex: 'userName',
					renderer: renderTooltip,
					filter: {
						type: 'string'
					},
					flex: 2
				},
				{
					text: 'Образование',
					dataIndex: 'education',
					renderer: renderTooltip,

					filter: {
						type: 'string'
					},
					flex: 1
				},
				{
					text: 'Город',
					dataIndex: 'city',
					renderer: renderTooltip,
					filter: {
						type: 'string'
					},
					flex: 1
				},				
				{
					text: 'Телефон',
					dataIndex: 'phone',
					hidden: true,
					renderer: renderTooltip,
					filter: {
						type: 'string'
					},
					flex: 1
				},
				{
					text: 'Email',
					dataIndex: 'email',
					hidden: true,
					renderer: renderTooltip,
					filter: {
						type: 'string'
					},
					flex: 1.5
				},
				{
					text: 'Комментарий',
					dataIndex: 'comments',
					hidden: true,
					renderer: renderTooltip,
					filter: {
						type: 'string'
					},
					flex: 2
				},				
				
			],
	
			listeners: {	
				cellclick: function(grid, cell, columnIndex, record,row, rowIndex, e) {
console.log('cellclick: columnIndex=', columnIndex);
					if (columnIndex == 0) {
						EditUser(record);
					}	
				},	
			},

			plugins: [
				{
					ptype: 'filterbar',
					pluginId: 'filters',
					autoStoresRemoteProperty: 'filterData',
					autoStoresNullValue: '###NULL###',
					// autoStoresNullText: __('[empty]'),
					autoUpdateAutoStores: false,

					renderHidden: true,
					showShowHideButton: true,//false,
					showClearButton: true,
					showClearAllButton: true,

					showTool: true,
					dock: 'top'
				}
			],
			bbar: UsersBbar()

		});
		
		//var filter_name = Ext.ComponentQuery.query('#filter_name'); filter_name.getTrigger('clear').hide();
		var filter_education = Ext.ComponentQuery.query("#filter_education")[0];filter_education.getTrigger('clear').hide();
		var filter_city = Ext.ComponentQuery.query('#filter_city')[0]; filter_city.getTrigger('clear').hide();	
	
	//}
	
	//startApp();
	
});//Ext.onReady