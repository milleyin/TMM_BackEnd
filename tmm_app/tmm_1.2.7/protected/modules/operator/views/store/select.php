<?php
/* @var $this StoreController */
/* @var $model StoreContent */

$this->breadcrumbs = array(
	'供应商管理页'=>array('admin'),
	'选择供应商'
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#store-content-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>选择供应商</h1>
<div>
	<span>
		<?php echo CHtml::link('高级搜索', '#', array('class'=>'search-button')); ?>
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
	$.fn.yiiGridView.update('store-content-grid', {  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data){
	$.fn.yiiGridView.update('store-content-grid');  
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
	jQuery('#store-content-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#store-content-grid').yiiGridView('update');
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
	jQuery('#login_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#add_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#last_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
}
");

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'store-content-grid',
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
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:30px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("id")."：".$data->id
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>CHtml::activeTextField($model->Content_Store, 'phone', array('id'=>false)),
				'name'=>'Content_Store.phone',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("Content_Store.phone")."：".$data->Content_Store->phone
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:180px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("name")."：".$data->name . "\n" . 
					"上线项目统计：" . $data->Content_Store->Store_Items_Count
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>Area::data_array_name('',array()),
				'name'=>'area_id_p',
				'value'=>'(isset($data->area_id_p) && $data->area_id_p != 0 )? $data->Content_area_id_p_Area_id->name:"未选择"',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("area_id_p")."：".(isset($data->area_id_p) && $data->area_id_p != 0 )? $data->Content_area_id_p_Area_id->name:"未选择"
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>Area::data_array_name($model->area_id_p,array(),false),
				'name'=>'area_id_m',
				'value'=>'(isset($data->area_id_m) && $data->area_id_m != 0 )? $data->Content_area_id_m_Area_id->name:"未选择"',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("area_id_m")."：".(isset($data->area_id_m) && $data->area_id_m != 0 )? $data->Content_area_id_m_Area_id->name:"未选择"
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>Area::data_array_name($model->area_id_m,array(),false),
				'name'=>'area_id_c',
				'value'=>'(isset($data->area_id_c) && $data->area_id_c != 0 )? $data->Content_area_id_c_Area_id->name:"未选择"',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
						$data->getAttributeLabel("address")."：". ((isset($data->area_id_p) && $data->area_id_p != 0 ) ? $data->Content_area_id_p_Area_id->name ." " : "未选择") .
						((isset($data->area_id_m) && $data->area_id_m != 0 )? $data->Content_area_id_m_Area_id->name ." " :"未选择") .
						((isset($data->area_id_c) && $data->area_id_c != 0 )? $data->Content_area_id_c_Area_id->name ." " :"未选择") .
						$data->address 
				'),
		),	
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'deposit',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("deposit")."：".$data->deposit
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'lx_contacts',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("lx_contacts")."：".$data->lx_contacts . "\n" .
					$data->getAttributeLabel("lx_phone")."：".$data->lx_phone
				'),
		),
		array(
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>'zh-CN',
						'model'=>$model->Content_Store,
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
				'name'=>'Content_Store.add_time',
				'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>'zh-CN',
						'model'=>$model->Content_Store,
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
				'name'=>'Content_Store.up_time',
				'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'filter'=>CHtml::activeDropDownList($model->Content_Store, 'status', array(''=>'')+StoreUser::$_status, array('id'=>false)),
				'name'=>'Content_Store.status',
				'value'=>'StoreUser::$_status[$data->Content_Store->status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}{eat}{hotel}{play}',
			'buttons'=>array(
					'view'=>array(
						'options'=>array('style'=>'padding:0 10px 0 0;'),
					),
					'eat'=>array(
							'label'=>'创建项目（吃）',
							'url'=>'Yii::app()->createUrl("/operator/eat/create", array("id"=>$data->id))',
							'options'=>array('style'=>'padding:0 15px 0 0;'),
					),
					'hotel'=>array(
							'label'=>'创建项目（住）',
							'url'=>'Yii::app()->createUrl("/operator/hotel/create", array("id"=>$data->id))',
							'options'=>array('style'=>'padding:0 15px 0 0;'),
					),
					'play'=>array(
							'label'=>'创建项目（玩）',
							'url'=>'Yii::app()->createUrl("/operator/play/create", array("id"=>$data->id))',
					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:450px;'),
		),
	),
)); 
?>
