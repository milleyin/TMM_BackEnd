<?php
/* @var $this Tmm_attendController */
/* @var $model Attend */

$this->breadcrumbs=array(
	'活动（代付）报名管理页',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#attend-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>活动（代付）报名管理页 </h1>
<div>
	<?php echo CHtml::link('高级搜索','#',array('class'=>'search-button')); ?>
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
	$.fn.yiiGridView.update('attend-grid',{  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data){
	$.fn.yiiGridView.update('attend-grid');  
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
	jQuery('#attend-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#attend-grid').yiiGridView('update');
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
	'id'=>'attend-grid',
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
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:30px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("id").":".$data->id
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'actives_id',
				'value'=>'$data->Attend_Shops->name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("actives_id").":".$data->Attend_Shops->name."\n".
					$data->getAttributeLabel("actives_id").":".$data->Attend_Shops->id
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'founder_id',
				'value'=>'$data->Attend_User_Founder->phone',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("founder_id").":".$data->Attend_User_Founder->phone
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'user_id',
				'value'=>'$data->Attend_User->phone',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("user_id").":".$data->Attend_User->phone
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'p_id',
				'type'=>'raw',
				'value'=>'$data->p_id == 0 ? "报名人" : CHtml::link($data->Attend_Attend_P->name,array("/admin/tmm_attend/view","id"=>$data->p_id))',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("p_id").":".($data->p_id == 0 ? "报名人" : $data->p_id)
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'number',
				'value'=>'$data->p_id != 0 ? "--" : $data->number',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("number").":".($data->p_id != 0 ? "--" : $data->number)
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'people',
				'value'=>'$data->p_id != 0 ? "--" : $data->people',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("people").":".($data->p_id != 0 ? "--" : $data->people)
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'children',
				'value'=>'$data->p_id != 0 ? "--" : $data->children',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("children").":".($data->p_id != 0 ? "--" : $data->children)
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("name").":".$data->name
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'phone',
				'value'=>'$data->is_people == $data::is_people_yes ? $data->phone : "--"',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("phone").":".($data->is_people == $data::is_people_yes ? $data->phone : "--")
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>$model::$_is_people,
				'name'=>'is_people',
				'value'=>'$data::$_is_people[$data->is_people]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("is_people").":".$data::$_is_people[$data->is_people]
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>$model::$_gender,
				'name'=>'gender',
				'value'=>'$data::$_gender[$data->gender]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("gender").":".$data::$_gender[$data->gender]
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
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("status").":".$data::$_status[$data->status]
				'),
		),
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}',
			'buttons'=>array(
					'view'=>array(
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
// 					'update'=>array(
// 						'options'=>array('style'=>'padding:0 8px 0 0;'),
// 					),
									
// 					'delete'=>array(
// 						'visible'=>'$data->status==0',
// 						'options'=>array('style'=>'padding:0 8px 0 0;'),
// 					),
// 					'disable'=>array(
// 						'label'=>'禁用',
// 						'visible'=>'$data->status==1',
// 						'url'=>'Yii::app()->createUrl("/admin/tmm_attend/disable",array("id"=>$data->id))',
// 						'click'=>$click,
// 						'options'=>array('style'=>'padding:0 8px 0 0;'),
// 					),
// 					'start'=>array(
// 						'label'=>'激活',
// 						'visible'=>'$data->status==0',
// 						'url'=>'Yii::app()->createUrl("/admin/tmm_attend/start",array("id"=>$data->id))',
// 						'click'=>$click,
// 						'options'=>array('style'=>'padding:0 8px 0 0;'),
// 					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
		),
	),
)); 
?>
