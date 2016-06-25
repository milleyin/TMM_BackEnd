<?php
/* @var $this Tmm_areaController */
/* @var $model Area */

$this->breadcrumbs=array(
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
<h1>管理地址页</h1>
<div>
		<span><?php echo CHtml::link('高级搜索','#',array('class'=>'search-button')); ?></span>
		<span><?php echo CHtml::link('热门城市设置',array('/admin/tmm_area/select')); ?></span>
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
	$.fn.yiiGridView.update('area-grid',{  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data){
	$.fn.yiiGridView.update('area-grid');  
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
	jQuery('#admin_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	
}
");

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'area-grid',
	'dataProvider'=>$model->search(),
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
				'name'=>'name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'nid',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>Area::$_pid,
				'name'=>'pid',
				'value'=>'isset($data->Area_Area_P->Area_Area_M->name)?"区(县)":(isset($data->Area_Area_P->name)?"市":"省")',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
						isset($data->Area_Area_P->Area_Area_M->name)?(
							"省".":".$data->name." [".$data->nid."]"."\n".
							"市".":".$data->Area_Area_P->name." [".$data->Area_Area_P->nid."]"."\n".			
							"区(县)".":".$data->Area_Area_P->Area_Area_M->name." [".$data->Area_Area_P->Area_Area_M->nid."]"."\n"
						):(	isset($data->Area_Area_P->name)?(
							"省".":".$data->name." [".$data->nid."]"."\n".
							"市".":".$data->Area_Area_P->name." [".$data->Area_Area_P->nid."]"."\n"			
						):"省".":".$data->name." [".$data->nid."]")
				'),
		),
		array(
				'name'=>'sort',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'agent_id',
				'value'=>'isset($data->Area_Agent->phone)?$data->Area_Agent->phone:"未分配运营商"',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
						isset($data->Area_Agent->phone)?(
							$data->getAttributeLabel("Area_Agent.phone").":".$data->Area_Agent->phone."\n".		
							$data->getAttributeLabel("Area_Agent.firm_name").":".$data->Area_Agent->firm_name."\n".
							$data->getAttributeLabel("Area_Agent.merchant_count").":".$data->Area_Agent->merchant_count."\n"				
						):"未分配运营商"
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'admin_id',
				'value'=>'isset($data->Area_Admin->username)?$data->Area_Admin->username:"热门未设置"',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					isset($data->Area_Admin->username)?(
						$data->getAttributeLabel("Area_Admin.username").":".$data->Area_Admin->username."\n".		
						$data->getAttributeLabel("Area_Admin.name").":".$data->Area_Admin->name."\n"
					):"热门未设置"
				'),
		),
		array(
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>Yii::app()->language,
						'model'=>$model,
						'attribute'=>'admin_time',
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
								'id' =>'admin_time_date',
						),
					),true),
				'name'=>'admin_time',
				'value'=>'$data->admin_time==0?"--":Yii::app()->format->datetime($data->admin_time)',
				//'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'filter'=>Area::$_status_hot,
				'name'=>'status_hot',
				'value'=>'Area::$_status_hot[$data->status_hot]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}',
			'buttons'=>array(
					'view'=>array(
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
		),
	),
));
?>
