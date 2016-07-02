<?php
/* @var $this Tmm_storeController */
/* @var $model StoreUser */

$this->breadcrumbs=array(
	'供应商账号管理页',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#store-user-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>管理页 供应商账号</h1>
<div>
	<span>
		<?php echo CHtml::link('高级搜索','#',array('class'=>'search-button')); ?>
	</span>
	 <span>
	 	<?php //echo CHtml::link('创建供应商账号',array('create')); ?>
	</span>
         <span>
	 	<?php // echo CHtml::link('垃圾回收页',array('index')); ?>
	</span>
</div>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->
<?php
  $Confirmation= "你确定执行此项操作？";
	if(Yii::app()->request->enableCsrfValidation)
	{
		$csrfTokenName = Yii::app()->request->csrfTokenName;
		$csrfToken = Yii::app()->request->csrfToken;
		$csrf = "\n\t\tdata:{ '$csrfTokenName':'$csrfToken'},";
	}else
		$csrf = '';
$click_alert=<<<EOD
function(){ 
	if(!confirm("$Confirmation")) return false; 
	var th=this;  
	var afterDelete=function(link,success,data){ if(success) alert(data);};  
	$.fn.yiiGridView.update('store-user-grid',{  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data){
	$.fn.yiiGridView.update('store-user-grid');  
	   afterDelete(th,true,data);  
	},
	error:function(XHR){
	   return afterDelete(th,false,XHR);
	}
  });
    return false;
}
EOD;

$click=<<<EOD
function() {  
	if(!confirm("$Confirmation")) return false;
	var th = this,
	afterDelete = function(){};
	jQuery('#store-user-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#store-user-grid').yiiGridView('update');
			afterDelete(th, true, data);
		},
		error: function(XHR) {
			return afterDelete(th, false, XHR);
		}
});
    return false;
}
EOD;

Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
	jQuery('#login_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#last_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#add_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#up_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#out_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	
}
");

$select_status = array();

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'store-user-grid',
	'dataProvider'=>$model->search(),
	'enableHistory'=>true,
	'afterAjaxUpdate' => 'reinstallDatePicker',
	'filter'=>$model,
	'columns'=>array(
		array(
				'name'=>'id',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:20px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'phone',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'filter'=>CHtml::activeTextField($model->Store_Content, 'name', array('id'=>false)),
				'name'=>'Store_Content.name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'filter'=>CHtml::activeDropDownList($model->Store_Content, 'area_id_p', Area::data_array_name('',array(''=>'')),array('id'=>false)),
				'name'=>'Store_Content.area_id_p',
				'value'=>'$data->Store_Content->Content_area_id_p_Area_id->name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			    'filter'=>CHtml::activeDropDownList($model->Store_Content, 'area_id_m',Area::data_array_name($model->Store_Content->area_id_p,array(''=>''),false),array('id'=>false)),
			    'name'=>'Store_Content.area_id_m',
				'value'=>'$data->Store_Content->Content_area_id_m_Area_id->name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
			    'filter'=>CHtml::activeDropDownList($model->Store_Content, 'area_id_c',Area::data_array_name($model->Store_Content->area_id_m,array(''=>''),false),array('id'=>false)),
				'name'=>'Store_Content.area_id_c',
				'value'=>'$data->Store_Content->Content_area_id_c_Area_id->name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Store_Content.address").":".$data->Store_Content->address'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'agent_id',
                 'value'=>'$data->Store_Agent->phone',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Store_Agent.firm_name").":".$data->Store_Agent->firm_name'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>CHtml::activeTextField($model->Store_Content, 'son_count', array('id'=>false)),
				'name'=>'Store_Content.son_count',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Store_Content.son_limit").":".$data->Store_Content->son_limit'),
		),
		array(
				'filter'=>CHtml::activeTextField($model->Store_Content, 'deposit', array('id'=>false)),
				'name'=>'Store_Content.deposit',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:20px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>Yii::app()->language,
						'model'=>$model,
						'attribute'=>'login_time',
						'value'=>date('Y-m-d'),
						'options'=>array(
								'maxDate'=>'new date()',
								'dateFormat'=>'yy-mm-dd',
								'showOn' => 'focus',
								'showOtherMonths' => true,
								'selectOtherMonths' => true,
								'changeMonth' => true,
								'changeYear' => true,
								'showButtonPanel' => true,
						),
						'htmlOptions'=>array(
								'id' =>'login_time_date',
						),
					),true),
				'name'=>'login_time',
				'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("login_ip").":".$data->login_ip'),
		),
         array(
			 	'class'=>'DataColumn',
			 	'evaluateHtmlOptions'=>true,
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>Yii::app()->language,
						'model'=>$model,
						'attribute'=>'last_time',
						'value'=>date('Y-m-d'),
						'options'=>array(
								'maxDate'=>'new date()',
								'dateFormat'=>'yy-mm-dd',
								'showOn' => 'focus',
								'showOtherMonths' => true,
								'selectOtherMonths' => true,
								'changeMonth' => true,
								'changeYear' => true,
								'showButtonPanel' => true,
						),
						'htmlOptions'=>array(
								'id' =>'last_time_date',
						),
					),true),
				'name'=>'last_time',
				'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("last_ip").":".$data->last_ip'),
		),
         array(
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>Yii::app()->language,
						'model'=>$model,
						'attribute'=>'add_time',
						'value'=>date('Y-m-d'),
						'options'=>array(
								'maxDate'=>'new date()',
								'dateFormat'=>'yy-mm-dd',
								'showOn' => 'focus',
								'showOtherMonths' => true,
								'selectOtherMonths' => true,
								'changeMonth' => true,
								'changeYear' => true,
								'showButtonPanel' => true,
						),
						'htmlOptions'=>array(
								'id' =>'add_time_date',
						),
					),true),
				'name'=>'add_time',
				'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
				'language'=>Yii::app()->language,
				'model'=>$model,
				'attribute'=>'up_time',
				'value'=>date('Y-m-d'),
				'options'=>array(
					'maxDate'=>'new date()',
					'dateFormat'=>'yy-mm-dd',
					'showOn' => 'focus',
					'showOtherMonths' => true,
					'selectOtherMonths' => true,
					'changeMonth' => true,
					'changeYear' => true,
					'showButtonPanel' => true,
				),
				'htmlOptions'=>array(
					'id' =>'up_time_date',
				),
			),true),
			'name'=>'up_time',
			'type'=>'datetime',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>$model::$_status,
			'name'=>'status',
			'value'=>'$data::$_status[$data->status]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}{update}{items}{bank}{deposit}{tags_type}{delete}{disable}{start}{store}{son}',
			'buttons'=>array(
					'view'=>array(
						'label'=>'查看',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/view.png',
					),
					'update'=>array(
						'label'=>'更新',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/update.png',
					),
					'items'=>array(
						'label'=>'项目',
						'visible'=>'$data->p_id==0 && $data->status==1',
						'url'=>'Yii::app()->createUrl("/admin/tmm_items/select",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/item.png',
					),
					'bank'=>array(
						'label'=>'银行',
						'visible'=>'$data->p_id==0 && $data->status==1',
						'url'=>'Yii::app()->createUrl("admin/Tmm_bankCard/create",array("id"=>$data->id,"type"=>BankCard::store))',
						//'url'=>'Yii::app()->createUrl("/admin/tmm_store/bank",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/bank.png',
					),
					'deposit'=>array(
						'label'=>'保证金',
						'visible'=>'$data->p_id==0 && $data->status==1',
						'url'=>'Yii::app()->createUrl("/admin/tmm_store/deposit",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/deposit.png',
					),
					'tags_type'=>array(
						'label'=>'标签',
						'visible'=>'$data->p_id==0 && $data->status==1',			
						'url'=>'Yii::app()->createUrl("admin/tmm_store/select",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/tag.png',
					),
					'delete'=>array(
						'label'=>'删除',
						'visible'=>'$data->status==0',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/delete.png',
					),
					'store'=>array(
						'label'=>'添加子',
						'visible'=>'$data->p_id==0 && $data->status==1',
						'url'=>'Yii::app()->createUrl("/admin/tmm_store/createson",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/store.png',
					),
					'son'=>array(
						'label'=>'查看子',
						'visible'=>'$data->p_id==0',
						'url'=>'Yii::app()->createUrl("/admin/tmm_store/son",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/view.png',
					),
					'disable'=>array(
						'label'=>'禁用',
						'visible'=>'$data->status==1',
						'url'=>'Yii::app()->createUrl("/admin/tmm_store/disable",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/disable.png',
					),
					'start'=>array(
						'label'=>'激活',
						'visible'=>'$data->status==0',
						'url'=>'Yii::app()->createUrl("/admin/tmm_store/start",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/enable.png',
					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:200px;'),
		),
	),
)); 
?>
