<?php
/* @var $this Tmm_selectController */
/* @var $model Select */

if (isset($model->Select_Ad) && $model->Select_Ad)
{
	$this->breadcrumbs=array(
			'广告专题管理页'=>array('/admin/tmm_ad/admin'),
			$model->Select_Ad->name=>array('/admin/tmm_ad/view', 'id'=>$model->Select_Ad->id),
			'内部广告管理页',
	);
}
else
{
	$this->breadcrumbs=array(
			'广告专题管理页'=>array('/admin/tmm_ad/admin'),
			'内部广告管理页',
	);
}

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#select-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>管理页 内部广告</h1>
<div>
	<span>
		<?php echo CHtml::link('高级搜索', '#', array('class'=>'search-button')); ?>	</span>
	<?php 
		if (isset($model->Select_Ad) && $model->Select_Ad && $model->Select_Ad->status == Ad::status_suc)
		{
			if ($model->Select_Ad->type == Ad::type_actives)
				echo '<span>', CHtml::link('添加觅趣', array('actives', 'id'=>$model->Select_Ad->id)), '</span>';
			else if ($model->Select_Ad->type == Ad::type_nearby)
				echo '<span>', CHtml::link('添加觅境', array('nearby', 'id'=>$model->Select_Ad->id)), '</span>';
		}
	?>
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
	$.fn.yiiGridView.update('select-grid', {  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data){
	$.fn.yiiGridView.update('select-grid');  
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
	jQuery('#select-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#select-grid').yiiGridView('update');
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
	$('.name').onlyNum();
	$('.name').change(function(){
 		var name = $(this).attr('name');
 		if(!name.match(/^namename\[[\d]\]$/))
			$(this).attr('name','name'+$(this).attr('name'));
	});
}
");
Yii::app()->clientScript->registerScript('sort', "
	$.fn.onlyNum = function () {
    $(this).keypress(function (event) {
        var eventObj = event || e;
        var keyCode = eventObj.keyCode || eventObj.which;
        if ((keyCode >= 48 && keyCode <= 57))
            return true;
        else
            return false;
    }).focus(function () {
        this.style.imeMode = 'disabled';
    }).bind('paste', function () {
	        var clipboard = window.clipboardData.getData('Text');
	        if (/^\d+$/.test(clipboard))
	            return true;
	        else
	            return false;
	    });
	};
	$('.name').onlyNum();
	$('.name').change(function(){
 		var name = $(this).attr('name');
 		if(!name.match(/^namename\[[\d]\]$/))
			$(this).attr('name','name'+$(this).attr('name'));
	});
");
echo CHtml::beginForm(Yii::app()->createUrl('/admin/tmm_select/sort'), 'post');

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'select-grid',
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
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("id")."：".$data->id
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>Select::$_type,
				'name'=>'type',
				'value'=>'$data::$_type[$data->type]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("type")."：".$data::$_type[$data->type]
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'to_id',
				'value'=>'$data->getToName($data)',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:250px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("to_id") . "：" . $data->to_id . "\n" .
					$data->getAttributeLabel("to_id") . "：" . $data->getToName($data)

				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'select_id',
				'value'=>'$data->getSelectName($data)',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:350px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("select_id")."：".$data->select_id . "\n" . 
					$data->getAttributeLabel("select_id")."：".$data->getSelectName($data)
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'sort',
				'type'=>'raw',
				'value'=>function ($data){
					return CHtml::textField('name[' . $data->id . ']', $data->sort, array('class'=>'name', 'maxlength'=>8, 'style'=>'
							height: 24px;font-size: 16px;padding: 0 0 0 5px;border-radius: 5px;background-color: #EDE6E6;width:80px;
					'));
				},
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("sort")."：".$data->sort
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'admin_id',
				'value'=>'$data->Select_Admin->username . "[" . $data->Select_Admin->name . "]"',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:120px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("admin_id").":".$data->Select_Admin->username . "[" . $data->Select_Admin->name . "]"
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
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:75px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("add_time")."：".Yii::app()->format->FormatDate($data->add_time)
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
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:75px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("up_time")."：".Yii::app()->format->FormatDate($data->up_time)
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>$model::$_status,
				'name'=>'status',
				'value'=>'$data::$_status[$data->status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("status")."：".$data::$_status[$data->status]
				'),
		),
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}{delete}',
			'buttons'=>array(
					'view'=>array(
						'options'=>array('style'=>'padding:0 15px 0 0;'),
					),
					'delete'=>array(
						'options'=>array('style'=>'padding:0 15px 0 0;'),
					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
		),
	),
)); 
?>
<div class="row">
	<span class="buttons">
	<?php 
		echo CHtml::submitButton('更新排序');
	?>
	</span>
</div>
<?php 
echo CHtml::endForm();
?>
