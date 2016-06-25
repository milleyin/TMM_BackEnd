<?php
/* @var $this AreaController */
/* @var $model Area */

$this->breadcrumbs = array(
	'管理页',
);
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#area-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>管理 Areas</h1>

<div>
	<span>
		<?php echo CHtml::link('高级搜索','#',array('class'=>'search-button')); ?>	
	</span>
	<span>
		<?php echo CHtml::link('创建Area', array('create')); ?>	
	</span>
</div>

<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php 
	$Confirmation = "你确定执行此项操作？";
	if (Yii::app()->request->enableCsrfValidation)
	{
		$csrfTokenName = Yii::app()->request->csrfTokenName;
		$csrfToken = Yii::app()->request->csrfToken;
		$csrf = "\n\t\tdata:{ '$csrfTokenName':'$csrfToken'},";
	}
	else
		$csrf = '';
		
$click_alert = <<<"EOD"
function() { 
	if ( !confirm("$Confirmation")) return false; 
	var th = this;  
	var afterDelete = function(link, success, data){ if (success) alert(data);};  
	$.fn.yiiGridView.update('area-grid', {  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data) {
	$.fn.yiiGridView.update('area-grid');  
	   afterDelete(th,true,data);  
	},
	error:function(XHR) {
	   return afterDelete(th,false,XHR);
	}
  });
    return false;
}
EOD;

$click = <<<"EOD"
function() {  
	if ( !confirm("$Confirmation")) return false;
	var th = this,
	afterDelete = function () {};
	jQuery('#area-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#area-grid').yiiGridView('update');
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
}
");

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'area-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'id',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
						return $data->getAttributeLabel('id') . '：' . $data->id;
					}
				),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
						return $data->getAttributeLabel('name') . '：' . $data->name;
					}
				),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'spell',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
						return $data->getAttributeLabel('spell') . '：' . $data->spell;
					}
				),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'pid',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
						return $data->getAttributeLabel('pid') . '：' . $data->pid;
					}
				),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'sort',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
						return $data->getAttributeLabel('sort') . '：' . $data->sort;
					}
				),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'status',
				'value'=>function ($data, $row) {
					return $data::$_status[$data->status];
				},
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
						return $data->getAttributeLabel('status') . '：' . $data::$_status[$data->status];
					},
				),
		),
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}{update}{delete}{disable}{start}{restore}',
			'buttons'=>array(
					'view'=>array(
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
					'update'=>array(
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
					'delete'=>array(
						'visible'=>function ($row, $data){
							return $data->status == $data::_STATUS_DISABLE;
						},
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
					'disable'=>array(
						'label'=>'禁用',
						'visible'=>function ($row, $data) {
							return $data->status == $data::_STATUS_NORMAL;
						},
						'url'=>function ($data, $row) {
							return  CHtml::normalizeUrl(array('disable', 'id'=>$data->id));
							//return Yii::app()->createUrl('/admin/area/disable', array('id'=>$data->id));
						},
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
					'start'=>array(
						'label'=>'激活',
						'visible'=>function ($row, $data) {
							return $data->status == $data::_STATUS_DISABLE;
						},
						'url'=>function ($data, $row) {
							return  CHtml::normalizeUrl(array('start', 'id'=>$data->id));
							//return Yii::app()->createUrl('/admin/area/start', array('id'=>$data->id));
						},
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
					'restore'=>array(
						'label'=>'还原',
						'visible'=>function ($row, $data) {
							return $data->status == $data::_STATUS_DELETED;
						},
						'url'=>function ($data, $row) {
							return  CHtml::normalizeUrl(array('restore', 'id'=>$data->id));
							//return Yii::app()->createUrl('/admin/area/restore', array('id'=>$data->id));
						},
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
		),
	),
)); ?>
