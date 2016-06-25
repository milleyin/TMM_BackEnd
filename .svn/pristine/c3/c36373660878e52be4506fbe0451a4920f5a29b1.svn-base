<?php
/* @var $this ItemsController */
/* @var $model Items */

$this->breadcrumbs = array(
	'项目管理页',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#items-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>管理 项目</h1>
<div>
	<span>
		<?php echo CHtml::link('高级搜索', '#', array('class'=>'search-button')); ?>	
	</span>
	<span>
		<?php echo CHtml::link('创建项目', array('/operator/store/select')); ?>	
	</span>
</div>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search', array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->
<?php
  $Confirmation = "你确定执行此项操作？";
	if(Yii::app()->request->enableCsrfValidation)
	{
		$csrfTokenName = Yii::app()->request->csrfTokenName;
		$csrfToken = Yii::app()->request->csrfToken;
		$csrf = "\n\t\tdata:{ '$csrfTokenName':'$csrfToken'},";
	}else
		$csrf = '';
$click_alert=<<<"EOD"
function(){ 
	if(!confirm("$Confirmation")) return false; 
	var th = this;  
	var afterDelete = function(link, success, data){ if(success) alert(data);};  
	$.fn.yiiGridView.update('items-grid', {  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data){
	$.fn.yiiGridView.update('items-grid');  
	   afterDelete(th,true,data);  
	},
	error:function(XHR){
	   return afterDelete(th,false,XHR);
	}
  });
    return false;
}
EOD;

$click=<<<"EOD"
function() {  
	if(!confirm("$Confirmation")) return false;
	var th = this,
	afterDelete = function(){};
	jQuery('#items-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#items-grid').yiiGridView('update');
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
	jQuery('#pub_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#add_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#up_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	
}
");

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'items-grid',
	'dataProvider'=>$model->operatorSearch(),
	'enableHistory'=>true,
	'afterAjaxUpdate' => 'reinstallDatePicker',
	'filter'=>$model,
	'columns'=>array(
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'id',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:35px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("id").":".$data->id
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>ItemsClassliy::data('name',array()),
				'name'=>'c_id',
				'value'=>'$data->Items_ItemsClassliy->name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:45px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
						$data->getAttributeLabel("Items_ItemsClassliy.info")."：".$data->Items_ItemsClassliy->info					
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:200px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("name")."：".$data->name
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'store_id',
				'value'=>'$data->Items_StoreContent->Content_Store->phone',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("Items_StoreContent.name")."：".$data->Items_StoreContent->name
			'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>Area::data_array_name('',array()),
				'name'=>'area_id_p',
				'value'=>'(isset($data->area_id_p) && $data->area_id_p != 0 )? $data->Items_area_id_p_Area_id->name:"未选择"',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("area_id_p")."：".(isset($data->area_id_p) && $data->area_id_p != 0 )? $data->Items_area_id_p_Area_id->name:"未选择"
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>Area::data_array_name($model->area_id_p,array(),false),
				'name'=>'area_id_m',
				'value'=>'(isset($data->area_id_m) && $data->area_id_m != 0 )? $data->Items_area_id_m_Area_id->name:"未选择"',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("area_id_m")."：".(isset($data->area_id_m) && $data->area_id_m != 0 )? $data->Items_area_id_m_Area_id->name:"未选择"
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>Area::data_array_name($model->area_id_m,array(),false),
				'name'=>'area_id_c',
				'value'=>'(isset($data->area_id_c) && $data->area_id_c != 0 )? $data->Items_area_id_c_Area_id->name:"未选择"',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:75px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
						$data->getAttributeLabel("address")."：". ((isset($data->area_id_p) && $data->area_id_p != 0 ) ? $data->Items_area_id_p_Area_id->name ." " : "未选择") .
						((isset($data->area_id_m) && $data->area_id_m != 0 )? $data->Items_area_id_m_Area_id->name ." " :"未选择") .
						((isset($data->area_id_c) && $data->area_id_c != 0 )? $data->Items_area_id_c_Area_id->name ." " :"未选择") .
						$data->address 
				'),
		),
// 		array(
// 				'filter'=>false,
// 				'name'=>'map',
// 				'type'=>'raw',
// 				'value'=>'Yii::app()->controller->show_img($data->map)',
// 				'headerHtmlOptions'=>array('style'=>'text-align:center;width:170px;'),
// 				'htmlOptions'=>array('style'=>'text-align:center;'),
// 		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'down',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("down").":".$data->down
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'start_work',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;', 'title'=>'工作开始时间'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("start_work").":".$data->start_work
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'end_work',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;', 'title'=>'工作结束时间'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("end_work").":".$data->end_work
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>'zh-CN',
						'model'=>$model,
						'attribute'=>'pub_time',
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
								'id' =>'pub_time_date',
						),
					),true),
				'name'=>'pub_time',
				'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("pub_time").":".Yii::app()->format->FormatDate($data->pub_time)
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>'zh-CN',
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
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("add_time").":".Yii::app()->format->FormatDate($data->add_time)
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>'zh-CN',
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
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("up_time").":".Yii::app()->format->FormatDate($data->up_time)
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>$model::$_free_status,
				'name'=>'free_status',
				'value'=>'$data::$_free_status[$data->free_status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("free_status").":".$data::$_free_status[$data->free_status]
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>$model::$_audit,
				'name'=>'audit',
				'value'=>'$data::$_audit[$data->audit]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
				$data->getAttributeLabel("audit").":".$data::$_audit[$data->audit]
			'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>$model::$_status,
				'name'=>'status',
				'value'=>'$data::$_status[$data->status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("status").":".$data::$_status[$data->status]
				'),
		),
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}{update}{wifi}{confirm}{disable}{start}',
			'buttons'=>array(
					'view'=>array(
						'label'=>'查看',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'url'=>'Yii::app()->createUrl("/operator/".$data->Items_ItemsClassliy->admin."/view", array("id"=>$data->id))',
					),
					'update'=>array(
						'label'=>'更新',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'visible'=>'$data->status==0 && $data->audit != Items::audit_pending',							
						'url'=>'Yii::app()->createUrl("/operator/".$data->Items_ItemsClassliy->admin."/update", array("id"=>$data->id))',
					),
					'wifi'=>array(
							'label'=>'酒店服务',
							'visible'=>'$data->Items_ItemsClassliy->append=="Hotel" && $data->status==0 && $data->audit != Items::audit_pending',
							'url'=>'Yii::app()->createUrl("/operator/wifi/select", array("id"=>$data->id))',
							'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
					'confirm'=>array(
						'label'=>'提交审核',
						'visible'=>'$data->status==0 && $data->audit==Items::audit_draft',
						'url'=>'Yii::app()->createUrl("/operator/items/confirm",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
					'disable'=>array(
						'label'=>'下线',
						'visible'=>'$data->status==1',
						'url'=>'Yii::app()->createUrl("/operator/items/disable", array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
					'start'=>array(
						'label'=>'上线',
						'visible'=>'$data->status==0 && $data->audit==Items::audit_pass',
						'url'=>'Yii::app()->createUrl("/operator/items/start",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:160px;'),
		),
	),
	'htmlOptions'=>array('style'=>'text-align:center;min-width:1500px;'),
)); 
?>
