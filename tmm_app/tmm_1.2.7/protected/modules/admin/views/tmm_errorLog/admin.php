<?php
/* @var $this Tmm_errorLogController */
/* @var $model ErrorLog */

$this->breadcrumbs=array(
	'错误日志管理页',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#error-log-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>错误日志管理页</h1>
<div>
	<span>
		<?php echo CHtml::link('高级搜索','#',array('class'=>'search-button')); ?>	</span>
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
	$.fn.yiiGridView.update('error-log-grid',{  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data){
	$.fn.yiiGridView.update('error-log-grid');  
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
	jQuery('#error-log-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#error-log-grid').yiiGridView('update');
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
	jQuery('#add_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#up_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	
}
");

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'error-log-grid',
	'dataProvider'=>$model->search(),
	'enableHistory'=>true,
	'afterAjaxUpdate' => 'reinstallDatePicker',
	'filter'=>$model,
	'columns'=>array(
		array(
				'name'=>'id',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
        array(
                'filter'=>$model::$_manage_who,
                'name'=>'manage_who',
                'value'=>'$data::$_manage_who[$data->manage_who]',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
                'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
		array(
				'name'=>'error_id',
                 'value'=>'$data::error_type($data->manage_who, $data)',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
        array(
                'filter'=>$model::$_manage_type,
                'name'=>'manage_type',
                'value'=>'$data::$_manage_type[$data->manage_type]',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
                'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
		array(
                'filter'=>$model::$_error_type,
				'name'=>'error_type',
                'value'=>'$data::$_error_type[$data->error_type]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
        array(
            'name'=>'info',
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'name'=>'url',
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'name'=>'ip',
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
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
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
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
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),/**/
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}',
			'buttons'=>array(
					'view'=>array(
						'label'=>'查看',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/view.png',
					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
		),
	),
)); 
?>
