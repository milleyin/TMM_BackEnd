<?php
/* @var $this Tmm_orderActivesController */
/* @var $model OrderActives */

$this->breadcrumbs=array(
	'订单管理页'=>array('/admin/tmm_order/admin'),
	'觅趣管理页'=>array('/admin/tmm_order/actives'),
	'觅趣总订单管理页',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#order-actives-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>管理页 觅趣总订单</h1>
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
	$.fn.yiiGridView.update('order-actives-grid',{  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data){
	$.fn.yiiGridView.update('order-actives-grid');  
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
	jQuery('#order-actives-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#order-actives-grid').yiiGridView('update');
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
	'id'=>'order-actives-grid',
	'dataProvider'=>$model->search(),
	'enableHistory'=>true,
	'afterAjaxUpdate' => 'reinstallDatePicker',
	'filter'=>$model,
	'columns'=>array(
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'id',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
						"觅趣商品". $data->getAttributeLabel("OrderActives_Actives.id")."：".$data->OrderActives_Actives->id							
				'),
		),
		array(
				//'class'=>'DataColumn',
				//'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'actives_no',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
    		    'class'=>'DataColumn',
    		    'evaluateHtmlOptions'=>true,
				'filter'=>CHtml::activeDropDownList($model->OrderActives_Actives, 'is_organizer',array(''=>'')+Actives::$_is_organizer, array('id'=>false)),
				'name'=>'OrderActives_Actives.is_organizer',
				'value'=>'Actives::$_is_organizer[$data->OrderActives_Actives->is_organizer]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:55px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
						$data->getAttributeLabel("OrderActives_Actives.is_organizer")."：".Actives::$_is_organizer[$data->OrderActives_Actives->is_organizer]
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'organizer_id',
				'value'=>'$data->OrderActives_User->phone',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:75px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("OrderActives_Actives.is_organizer")."：".Actives::$_is_organizer[$data->OrderActives_Actives->is_organizer] . "\n" .
					$data->getAttributeLabel("OrderActives_User.nickname")."：".$data->OrderActives_User->nickname . "\n" .
					(
						$data->OrderActives_Actives->is_organizer == Actives::is_organizer_yes
						?
						(
							$data->getAttributeLabel("OrderActives_User.User_Organizer.firm_name")."：".$data->OrderActives_User->User_Organizer->firm_name . "\n" .
							$data->getAttributeLabel("OrderActives_User.User_Organizer.firm_phone")."：".$data->OrderActives_User->User_Organizer->firm_phone ."\n" .
							$data->getAttributeLabel("OrderActives_User.User_Organizer.law_name")."：".$data->OrderActives_User->User_Organizer->law_name . "\n" .
							$data->getAttributeLabel("OrderActives_User.User_Organizer.manage_name")."：".$data->OrderActives_User->User_Organizer->manage_name
						)
						:
						""
					)
			'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>CHtml::activeTextField($model->OrderActives_Shops, 'name', array('id'=>false)),
				'name'=>'OrderActives_Shops.name',
				'value'=>'mb_substr($data->OrderActives_Shops->name,0,8,"utf-8") . "..."',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:140px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					"觅趣商品". $data->getAttributeLabel("OrderActives_Actives.id")."：".$data->OrderActives_Actives->id . "\n" .
					$data->getAttributeLabel("OrderActives_Shops.name")."：".$data->OrderActives_Shops->name . "\n" .
					$data->getAttributeLabel("OrderActives_Shops.list_info")."：".$data->OrderActives_Shops->list_info . "\n" .
					$data->getAttributeLabel("OrderActives_Shops.page_info")."：".$data->OrderActives_Shops->page_info . "\n" .
					$data->getAttributeLabel("OrderActives_Shops.brow")."：".$data->OrderActives_Shops->brow . "\n" .
					$data->getAttributeLabel("OrderActives_Shops.share")."：".$data->OrderActives_Shops->share . "\n" .
					$data->getAttributeLabel("OrderActives_Shops.tags_ids")."：".$data->OrderActives_Shops->tags_ids . "\n" .
					$data->getAttributeLabel("OrderActives_Actives.number")."：".$data->OrderActives_Actives->number . "\n" .
					$data->getAttributeLabel("OrderActives_Actives.order_number")."：".$data->OrderActives_Actives->order_number . "\n" .
					$data->getAttributeLabel("OrderActives_Actives.tour_count")."：".$data->OrderActives_Actives->tour_count . "\n" .
					$data->getAttributeLabel("OrderActives_Actives.order_count")."：".$data->OrderActives_Actives->order_count  . "\n" .
					$data->getAttributeLabel("OrderActives_Actives.tour_price")."：".$data->OrderActives_Actives->tour_price
			'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>CHtml::activeDropDownList($model->OrderActives_Actives, 'is_open', array(''=>'')+Actives::$_is_open,array('id'=>false)),
				'name'=>'OrderActives_Actives.is_open',
				'value'=>'Actives::$_is_open[$data->OrderActives_Actives->is_open]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:45px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("OrderActives_Actives.is_open") ."：". Actives::$_is_open[$data->OrderActives_Actives->is_open] . "\n" .
					$data->getAttributeLabel("OrderActives_Actives.barcode") ."：". $data->OrderActives_Actives->barcode . "\n" .
					$data->getAttributeLabel("OrderActives_Actives.barcode_num") ."：". $data->OrderActives_Actives->barcode_num
			'),
		),
		array(
				'filter'=>CHtml::activeDropDownList($model->OrderActives_Actives, 'pay_type', array(''=>'')+Actives::$_pay_type,array('id'=>false)),			
				'name'=>'OrderActives_Actives.pay_type',
				'value'=>'Actives::$_pay_type[$data->OrderActives_Actives->pay_type]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>CHtml::activeTextField($model->OrderActives_Actives, 'order_number', array('id'=>false)),
				'name'=>'OrderActives_Actives.order_number',
				'value'=>'$data->OrderActives_Actives->order_number . " / " .$data->OrderActives_Actives->number',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
						$data->getAttributeLabel("OrderActives_Actives.number")."：".$data->OrderActives_Actives->number . "\n" .
						$data->getAttributeLabel("OrderActives_Actives.order_number")."：".$data->OrderActives_Actives->order_number . "\n" .
						$data->getAttributeLabel("OrderActives_Actives.tour_price")."：".$data->OrderActives_Actives->tour_price . "\n" .
						$data->getAttributeLabel("user_price")."：".$data->user_price . "\n" .	
						$data->getAttributeLabel("OrderActives_Actives.tour_count")."：".$data->OrderActives_Actives->tour_count . "\n" .
						$data->getAttributeLabel("OrderActives_Actives.order_count")."：".$data->OrderActives_Actives->order_count
				'),
		),
		array(
				//'class'=>'DataColumn',
				//'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'user_go_count',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:85px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'user_order_count',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
						$data->getAttributeLabel("OrderActives_Actives.tour_count")."：".$data->OrderActives_Actives->tour_count . "\n" .
						$data->getAttributeLabel("OrderActives_Actives.order_count")."：".$data->OrderActives_Actives->order_count 	. "\n" .
						$data->getAttributeLabel("user_order_count")."：".$data->user_order_count . "\n" .	
						$data->getAttributeLabel("user_submit_count")."：".$data->user_submit_count 
						
								
				'),
		),
		array(
				//'class'=>'DataColumn',
				//'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'user_pay_count',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				//'class'=>'DataColumn',
				//'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'user_price_count',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				//'class'=>'DataColumn',
				//'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'total',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:85px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				//'class'=>'DataColumn',
				//'evaluateHtmlOptions'=>true,
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
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				//'class'=>'DataColumn',
				//'evaluateHtmlOptions'=>true,
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
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				//'class'=>'DataColumn',
				//'evaluateHtmlOptions'=>true,
				'filter'=>OrderActives::$_status,
				'name'=>'status',
				'value'=>'$data::$_status[$data->status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}{son}{refunds}',
			'buttons'=>array(
					'view'=>array(
							'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
					'son'=>array(
							'label'=>'订单列表',
							'url'=>'Yii::app()->createUrl("/admin/tmm_order/actives",array("order_actives_id"=>$data->id))',
							'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
					'refunds'=>array(
							'label'=>'退款（全）',
							'visible'=>'$data->OrderActives_Actives->pay_type == Actives::pay_type_AA && $data->isRefund($data->OrderActives_OrderItems) == true',
							'url'=>'Yii::app()->createUrl("/admin/tmm_orderActives/refunds",array("id"=>$data->id))',
							'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
		),
	),
	'htmlOptions'=>array('style'=>'text-align:center;min-width:1500px;'),
)); 
?>