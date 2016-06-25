<?php
/* @var $this Tmm_orderController */
/* @var $model Order */

$this->breadcrumbs=array(
	'订单管理页'=>array('/admin/tmm_order/admin'),
	'自助游管理页',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#order-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>管理页 自助游</h1>
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
	$.fn.yiiGridView.update('order-grid',{  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data){
	$.fn.yiiGridView.update('order-grid');  
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
	jQuery('#order-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#order-grid').yiiGridView('update');
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
	jQuery('#pay_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#go_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#add_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#up_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	
}
");

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'order-grid',
	'dataProvider'=>$model->search_admin(),
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
				'name'=>'order_no',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'filter'=>CHtml::activeDropDownList($model, 'order_type',array(''=>'')+Order::$_order_type, array('id'=>false)),
				'name'=>'order_type',
				'value'=>'$data::$_order_type[$data->order_type]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'user_id',
				'value'=>'$data->Order_User->phone',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeDropDownList($model, 'pay_type',array(''=>'')+Order::$_pay_type, array('id'=>false)),
			'name'=>'pay_type',
			'value'=>'$data::$_pay_type[$data->pay_type]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'name'=>'order_price',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'name'=>'price',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'name'=>'user_go_count',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeDropDownList($model, 'status_go',array(''=>'')+Order::$_status_go, array('id'=>false)),
			'name'=>'status_go',
			'value'=>'$data::$_status_go[$data->status_go]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeDropDownList($model, 'pay_status',array(''=>'')+Order::$_pay_status, array('id'=>false)),
			'name'=>'pay_status',
			'value'=>'$data::$_pay_status[$data->pay_status]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeDropDownList($model, 'order_status',array(''=>'')+Order::$_order_status, array('id'=>false)),
			'name'=>'order_status',
			'value'=>'$data::$_order_status[$data->order_status]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeDropDownList($model, 'centre_status',array(''=>'')+Order::$_centre_status, array('id'=>false)),
			'name'=>'centre_status',
			'value'=>'$data::$_centre_status[$data->centre_status]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeDropDownList($model, 'status',array(''=>'')+Order::$_status, array('id'=>false)),
			'name'=>'status',
			'value'=>'$data::$_status[$data->status]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				//'class'=>'DataColumn',
				//'evaluateHtmlOptions'=>true,
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>Yii::app()->language,
						'model'=>$model,
						'attribute'=>'pay_time',
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
								'id' =>'pay_time_date',
						),
					),true),
				'name'=>'pay_time',
				'value'=>'$data->pay_time==0? "--": Yii::app()->format->formatDateTime($data->pay_time)',
				//'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),

		array(
				//'class'=>'DataColumn',
				//'evaluateHtmlOptions'=>true,
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>Yii::app()->language,
						'model'=>$model,
						'attribute'=>'go_time',
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
								'id' =>'go_time_date',
						),
					),true),
				'name'=>'go_time',
				'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				//'class'=>'DataColumn',
				//'evaluateHtmlOptions'=>true,
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
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				//'class'=>'DataColumn',
				//'evaluateHtmlOptions'=>true,
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
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),

		/**/
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}{retinue}',
			'buttons'=>array(
					'view'=>array(
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
					'retinue'=>array(
						'label'=>'随行',
						'url'=>'Yii::app()->createUrl("/admin/tmm_orderRetinue/retinues",array("order_id"=>$data->id))',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
// 					'refund'=>array(
// 						'label'=>'申请退款',
// 					//	'visible'=>'$data->status==Order::status_yes && $data->order_status== Order::order_status_user_pay ',
// 						'visible'=>'$data->status==Order::status_yes && $data->order_status== Order::order_status_user_pay && OrderItems::Apply_order($data->id)',
// 						'click'=>$click,
// 						'url'=>'Yii::app()->createUrl("/admin/tmm_order/refund",array("id"=>$data->id))',
// 						'options'=>array('style'=>'padding:0 8px 0 0;'),
// 					),
//					'disable'=>array(
//						'label'=>'禁用',
//						'visible'=>'$data->status==1',
//						'url'=>'Yii::app()->createUrl("/admin/tmm_order/disable",array("id"=>$data->id))',
//						'click'=>$click,
//						'options'=>array('style'=>'padding:0 8px 0 0;'),
//					),
//					'start'=>array(
//						'label'=>'激活',
//						'visible'=>'$data->status==0',
//						'url'=>'Yii::app()->createUrl("/admin/tmm_order/start",array("id"=>$data->id))',
//						'click'=>$click,
//						'options'=>array('style'=>'padding:0 8px 0 0;'),
//					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
		),
	),
)); 
?>
