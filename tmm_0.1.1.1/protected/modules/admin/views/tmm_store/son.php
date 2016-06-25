<?php
/* @var $this Tmm_storeController */
/* @var $model StoreUser */

$this->breadcrumbs=array(
	'供应商子账号管理页',
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

<h1>供应商子账号管理页</h1>
<div>
	<span>
		<?php echo CHtml::link('高级搜索','#',array('class'=>'search-button')); ?>
	</span>
        <span>
		<?php // echo CHtml::link('垃圾回收页',array('indexson')); ?>
	</span>
</div>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_searchson',array(
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
	'dataProvider'=>$model->search_son(),
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
			'name'=>'count',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'name'=>'error_count',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeTextField($model->Store_Store->Store_Content, 'lx_phone', array('id'=>false)),
			'name'=>'Store_Store.Store_Content.lx_phone',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'DataColumn',
			'evaluateHtmlOptions'=>true,
			'filter'=>CHtml::activeTextField($model->Store_Store->Store_Content, 'lx_contacts', array('id'=>false)),
			'name'=>'Store_Store.Store_Content.lx_contacts',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'DataColumn',
			'evaluateHtmlOptions'=>true,
			'filter'=>CHtml::activeTextField($model,'sms', array('id'=>false)),
			'name'=>'Store_Store.phone',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeTextField($model->Store_Store->Store_Content, 'name', array('id'=>false)),
			'name'=>'Store_Store.Store_Content.name',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
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
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
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
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}{store}{agent}{update}{delete}{disable}{start}',
			'buttons'=>array(
					'view'=>array(
						'label'=>'查看',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'url'=>'Yii::app()->createUrl("/admin/tmm_store/viewSon",array("id"=>$data->id))',
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/view.png',
					),
					'store'=>array(
						'label'=>'主账号',
						'visible'=>'$data->p_id>0',
						'url'=>'Yii::app()->createUrl("/admin/tmm_store/view",array("id"=>$data->p_id))',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/accounts.png',
					),
					'agent'=>array(
						'label'=>'运营商',
						'url'=>'Yii::app()->createUrl("/admin/tmm_agent/view",array("id"=>$data->agent_id))',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/agent.png',
					),
					'update'=>array(
						'label'=>'更新',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'url'=>'Yii::app()->createUrl("/admin/tmm_store/updateSon",array("id"=>$data->id))',
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/update.png',
					),
					'delete'=>array(
						'label'=>'删除',
						'visible'=>'$data->status==0',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'url'=>'Yii::app()->createUrl("/admin/tmm_store/deleteSon",array("id"=>$data->id))',
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/delete.png',
					),
					'disable'=>array(
						'label'=>'禁用',
						'visible'=>'$data->status==1',
						'url'=>'Yii::app()->createUrl("/admin/tmm_store/disableSon",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/disable.png',
					),
					'start'=>array(
						'label'=>'激活',
						'visible'=>'$data->status==0',
						'url'=>'Yii::app()->createUrl("/admin/tmm_store/startSon",array("id"=>$data->id))',
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
