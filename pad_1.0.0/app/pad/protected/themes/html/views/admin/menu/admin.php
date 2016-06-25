<?php
/* @var $this MenuController */
/* @var $model Menu */

$this->breadcrumbs = array(
	'管理页',
);
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#menu-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>管理 Menus</h1>

<div>
	<span>
		<?php echo CHtml::link('高级搜索','#',array('class'=>'search-button')); ?>	
	</span>
	<span>
		<?php echo CHtml::link('创建Menu', array('create')); ?>	
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
	$.fn.yiiGridView.update('menu-grid', {  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data) {
	$.fn.yiiGridView.update('menu-grid');  
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
	jQuery('#menu-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#menu-grid').yiiGridView('update');
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
	jQuery('#up_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#add_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
}
");

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'menu-grid',
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
				'name'=>'type',
				'value'=>function ($data, $row) {
					return $data::$_type[$data->type];
				},
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
						return $data->getAttributeLabel('type') . '：' . $data::$_type[$data->type];
					},
				),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'p_id',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
						return $data->getAttributeLabel('p_id') . '：' . $data->p_id;
					}
				),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'manager_id',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
						return $data->getAttributeLabel('manager_id') . '：' . $data->manager_id;
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
				'name'=>'title',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
						return $data->getAttributeLabel('title') . '：' . $data->title;
					}
				),
		),
		/*
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'info',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
						return $data->getAttributeLabel('info') . '：' . $data->info;
					}
				),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'route',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
						return $data->getAttributeLabel('route') . '：' . $data->route;
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
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker', array(
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
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
						return $data->getAttributeLabel('up_time') . '：' . Yii::app()->format->FormatDate($data->up_time);
					},
				),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker', array(
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
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
						return $data->getAttributeLabel('add_time') . '：' . Yii::app()->format->FormatDate($data->add_time);
					},
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
		*/
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
							//return Yii::app()->createUrl('/admin/menu/disable', array('id'=>$data->id));
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
							//return Yii::app()->createUrl('/admin/menu/start', array('id'=>$data->id));
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
							//return Yii::app()->createUrl('/admin/menu/restore', array('id'=>$data->id));
						},
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
		),
	),
)); ?>
