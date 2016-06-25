<?php
/* @var $this Tmm_typeController */
/* @var $model Type */

$this->breadcrumbs=array(
	'类型值管理页',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#type-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>管理页 类型值</h1>
<div>
	<span>
		<?php echo CHtml::link('高级搜索','#',array('class'=>'search-button')); ?>	
	</span>
	 <span>
	 	<?php echo CHtml::link('创建类型值',array('create')); ?>	
	</span>
	<span>
	 	<?php echo CHtml::link('垃圾回收页',array('index')); ?>	
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
$click_alert=<<<"EOD"
function(){ 
	if(!confirm("$Confirmation")) return false; 
	var th=this;  
	var afterDelete=function(link,success,data){ if(success) alert(data);};  
	$.fn.yiiGridView.update('type-grid',{  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data){
	$.fn.yiiGridView.update('type-grid');  
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
	jQuery('#type-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#type-grid').yiiGridView('update');
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
	jQuery('#add_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#up_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	
}
");

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'type-grid',
	'dataProvider'=>$model->search(),
	'enableHistory'=>true,
	'afterAjaxUpdate' => 'reinstallDatePicker',
	'filter'=>$model,
	'columns'=>array(
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'id',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("id").":".$data->id
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>$model::$_role_type,
				'name'=>'role_type',
				'value'=>'$data::$_role_type[$data->role_type]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("role_type").":".$data::$_role_type[$data->role_type]
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'role_id',
				'value'=>'$data->Type_Admin->username . "[" . $data->Type_Admin->name . "]"',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("role_id").":".$data->Type_Admin->username . "[" . $data->Type_Admin->name . "]"
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>$model::$_type,
				'name'=>'type',
				'value'=>'$data::$_type[$data->type]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("type").":".$data::$_type[$data->type]
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("name").":".$data->name . "\n" .
					$data->getAttributeLabel("info").":".$data->info
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'value',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("value").":".$data->value
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'options',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("options").":".$data->options
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'sort',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("sort").":".$data->sort
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
				'filter'=>$model::$_status,
				'name'=>'status',
				'value'=>'$data::$_status[$data->status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("status").":".$data::$_status[$data->status]
				'),
		),
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}{update}{delete}{disable}{start}',
			'buttons'=>array(
					'view'=>array(
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
					'update'=>array(
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
					'delete'=>array(
						'visible'=>'$data->status == $data::status_dis',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
					'disable'=>array(
						'label'=>'禁用',
						'visible'=>'$data->status == $data::status_suc',
						'url'=>'Yii::app()->createUrl("/admin/tmm_type/disable",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
					'start'=>array(
						'label'=>'激活',
						'visible'=>'$data->status == $data::status_dis',
						'url'=>'Yii::app()->createUrl("/admin/tmm_type/start",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
		),
	),
)); 
?>
