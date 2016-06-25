<?php
/* @var $this Tmm_adController */
/* @var $model Ad */

if (isset($model->Ad_Ad) && $model->Ad_Ad)
{
	$this->breadcrumbs=array(
			'广告专题管理页'=>array('admin'),
			$model->Ad_Ad->name=>array('view', 'id'=>$model->Ad_Ad->id),
			'广告管理页',
	);
}
else
{
	$this->breadcrumbs=array(
		'广告专题管理页'=>array('admin'),
		'广告管理页',
	);
}

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#ad-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>管理页 广告</h1>
<div>
	<span>
		<?php echo CHtml::link('高级搜索','#',array('class'=>'search-button')); ?>	
	</span>
	<?php 
		if (isset($model->Ad_Ad) && $model->Ad_Ad && $model->Ad_Ad->status == Ad::status_suc)
		{
			if ($model->Ad_Ad->type == Ad::type_banner || $model->Ad_Ad->type == Ad::type_hot)
				echo '<span>', CHtml::link('添加广告', array('add', 'id'=>$model->Ad_Ad->id)), '</span>';
		}
		else
		{
			echo '<span>', CHtml::link('直接创建广告', array('set')), '</span>';
		}
	?>
		<span>
	 		<?php echo CHtml::link('垃圾回收页', array('index')); ?>
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
	$.fn.yiiGridView.update('ad-grid',{  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data){
	$.fn.yiiGridView.update('ad-grid');  
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
	jQuery('#ad-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#ad-grid').yiiGridView('update');
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
echo CHtml::beginForm(Yii::app()->createUrl('/admin/tmm_ad/sort'), 'post');

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'ad-grid',
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
					$data->getAttributeLabel("id")."：".$data->id
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>array_slice($model::$_type, 0, 2, true),
				'name'=>'type',
				'value'=>'$data::$_type[$data->type]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:75px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("type")."：".$data::$_type[$data->type]
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>CHtml::listData(Type::model()->getTypeModels($model->type), 'id', 'name'),
				'name'=>'link_type',
				'value'=>'$data->Ad_Type->name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:75px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("Ad_Type.name")."：".$data->Ad_Type->name . "\n" .
					$data->getAttributeLabel("Ad_Type.info")."：".$data->Ad_Type->info  . "\n" .
					$data->getAttributeLabel("Ad_Type.value")."：".$data->Ad_Type->value
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'p_id',
				'value'=>'$data->Ad_Ad->name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					"专题" . $data->getAttributeLabel("Ad_Ad.status")."：".$data::$_status[$data->Ad_Ad->status] . "\n" .
					$data->getAttributeLabel("p_id")."：".$data->Ad_Ad->name . "\n" .
					$data->getAttributeLabel("Ad_Ad.info")."：".$data->Ad_Ad->info
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:200px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("name")."：".$data->name . "\n" .
					$data->getAttributeLabel("info")."：".$data->info
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'link',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:200px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("link")."：".$data->link . "\n" .
					$data->getAttributeLabel("options")."：".$data->options
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'img',
				'type'=>'raw',
				'value'=>'Yii::app()->controller->show_img($data->img,"","",array("width"=>50,"height"=>50))',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("name")."：".$data->name
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
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("sort")."：".$data->sort
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'admin_id',
				'value'=>'$data->Ad_Admin->username . "[" . $data->Ad_Admin->name . "]"',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("admin_id")."：".$data->Ad_Admin->username . "[" . $data->Ad_Admin->name . "]"
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
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
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
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("status")."：".$data::$_status[$data->status]
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
						'url'=>'Yii::app()->createUrl("/admin/tmm_ad/modify", array("id"=>$data->id))',
					),								
					'delete'=>array(
						'visible'=>'$data->status == Ad::status_dis',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
					'disable'=>array(
						'label'=>'下线',
						'visible'=>'$data->status == Ad::status_suc',
						'url'=>'Yii::app()->createUrl("/admin/tmm_ad/disable",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
					'start'=>array(
						'label'=>'上线',
						'visible'=>'$data->status == Ad::status_dis',
						'url'=>'Yii::app()->createUrl("/admin/tmm_ad/start",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:120px;'),
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
