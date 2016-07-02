<?php
/* @var $this UploadController */
/* @var $model Upload */

$this->breadcrumbs = array(
	'上传资源管理页',
);
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#upload-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>管理上传资源</h1>

<div>
	<span>
		<?php echo CHtml::link('高级搜索','#',array('class'=>'search-button')); ?>	
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
	$.fn.yiiGridView.update('upload-grid', {  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data) {
	$.fn.yiiGridView.update('upload-grid');  
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
	jQuery('#upload-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#upload-grid').yiiGridView('update');
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
	'id'=>'upload-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'enableHistory'=>true,
    'afterAjaxUpdate' => 'reinstallDatePicker',
	'columns'=>array(
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'id',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:30px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
						return $data->getAttributeLabel('id') . '：' . $data->id;
					}
				),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>$model::$_type,
				'name'=>'type',
				'value'=>function ($data, $row) {
					return $data::$_type[$data->type];
				},
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:55px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
						return $data->getAttributeLabel('type') . '：' . $data::$_type[$data->type];
					},
				),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'upload_id',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
						return $data->getAttributeLabel('upload_id') . '：' . $data->upload_id;
					}
				),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'manager_id',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
						return $data->getAttributeLabel('manager_id') . '：' . $data->manager_id;
					}
				),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>$model::$_upload_type,
				'name'=>'upload_type',
				'value'=>function ($data, $row) {
					return $data::$_upload_type[$data->upload_type];
				},
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
						return $data->getAttributeLabel('upload_type') . '：' . $data::$_upload_type[$data->upload_type];
					},
				),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>false,
				'name'=>'path',
		        'type'=>'raw',
		        'value'=>function ($data, $row) {
    		        if ($data->upload_type == $data::UPLOAD_UPLOAD_TYPE_IMAGE)
    		            return CHtml::image($data->getUrlPath(), CHtml::encode($data->info), array('style'=>'width:80px;height:60px;'));
    		        return CHtml::link($data::$_upload_type[$data->upload_type], $data->getUrlPath(), array('target'=>'_blank'));
		        },
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
						return $data->getAttributeLabel('path') . '：' . $data->path;
					}
				),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'info',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
						return $data->getAttributeLabel('info') . '：' . $data->info;
					}
				),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'size',
		        'value'=>function ($data, $row){
		          return $data->getSize();
				},
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
						return $data->getAttributeLabel('size') . '：' . $data->getSize();
					}
				),
		),
		array(
		        'class'=>'DataColumn',
		        'evaluateHtmlOptions'=>true,
		        //'filter'=>,
		        'name'=>'path',
		        'headerHtmlOptions'=>array('style'=>'text-align:center;'),
		        'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
		            return $data->getAttributeLabel('path') . '：' . $data->path;
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
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
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
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
						return $data->getAttributeLabel('add_time') . '：' . Yii::app()->format->FormatDate($data->add_time);
					},
				),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>$model::$_status,
				'name'=>'status',
				'value'=>function ($data, $row) {
					return $data::$_status[$data->status];
				},
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:55px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
						return $data->getAttributeLabel('status') . '：' . $data::$_status[$data->status];
					},
				),
		),
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}{update}',
			'buttons'=>array(
					'view'=>array(
						'options'=>array('style'=>'padding:0 10px 0 0;'),
					),
					'update',
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
		),
	),
)); ?>
