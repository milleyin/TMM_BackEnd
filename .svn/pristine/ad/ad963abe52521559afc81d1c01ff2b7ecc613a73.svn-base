<?php
/* @var $this Tmm_groupController */
/* @var $model Group */

$this->breadcrumbs=array(
	'线路管理页'=>array('/admin/tmm_shops/admin'),
	'结伴游(订单)管理页'=>array('admin'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#order-organizer-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>管理页 结伴游(订单)管理页</h1>
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
	$.fn.yiiGridView.update('order-organizer-grid',{  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data){
	$.fn.yiiGridView.update('order-organizer-grid');  
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
	jQuery('#order-organizer-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#order-organizer-grid').yiiGridView('update');
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
	jQuery('#shops_pub_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#shops_up_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#shops_add_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#group_start_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#group_end_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#group_group_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#add_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#up_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	
}
");

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'order-organizer-grid',
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
				'name'=>'group_no',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'organizer_id',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'group_id',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'shops_name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeDropDownList($model, 'group_group', array(''=>'')+OrderOrganizer::$_group_group),
			'name'=>'group_group',
			'value'=>'$data::$_group_group[$data->group_group]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			//'class'=>'DataColumn',
			//'evaluateHtmlOptions'=>true,
			//'filter'=>,
			'name'=>'user_order_count',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			//'class'=>'DataColumn',
			//'evaluateHtmlOptions'=>true,
			//'filter'=>,
			'name'=>'user_pay_count',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			//'class'=>'DataColumn',
			//'evaluateHtmlOptions'=>true,
			//'filter'=>,
			'name'=>'user_submit_count',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			//'class'=>'DataColumn',
			//'evaluateHtmlOptions'=>true,
			//'filter'=>,
			'name'=>'user_price',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			//'class'=>'DataColumn',
			//'evaluateHtmlOptions'=>true,
			//'filter'=>,
			'name'=>'user_go_count',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			//'class'=>'DataColumn',
			//'evaluateHtmlOptions'=>true,
			//'filter'=>,
			'name'=>'user_price_count',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			//'class'=>'DataColumn',
			//'evaluateHtmlOptions'=>true,
			//'filter'=>,
			'name'=>'total',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			//'class'=>'DataColumn',
			//'evaluateHtmlOptions'=>true,
			//'filter'=>,
			'filter'=>CHtml::activeDropDownList($model, 'is_organizer', array(''=>'')+OrderOrganizer::$_is_organizer),
			'name'=>'is_organizer',
			'value'=>'$data::$_is_organizer[$data->is_organizer]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			//'class'=>'DataColumn',
			//'evaluateHtmlOptions'=>true,
			//'filter'=>,
			'filter'=>CHtml::activeDropDownList($model, 'status', array(''=>'')+OrderOrganizer::$_status),
			'name'=>'status',
			'value'=>'$data::$_status[$data->status]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		/*
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
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		*/
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}{disable}{start}{cancel}',
			'buttons'=>array(
					'view'=>array(
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
				    'disable'=>array(
						'label'=>'下线',
						'visible'=>'$data->status==1 && ($data->group_group==OrderOrganizer::group_group_cancel || $data->group_group==OrderOrganizer::group_group_already_peer)',
						'url'=>'Yii::app()->createUrl("/admin/tmm_orderOrganizer/disable",array("id"=>$data->id))',
						'click'=>$click,
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/offline.png',
					),
					'start'=>array(
						'label'=>'上线',
						'visible'=>'$data->shops_list_info != "" && $data->status==0 && ($data->group_group==OrderOrganizer::group_group_cancel || $data->group_group==OrderOrganizer::group_group_already_peer) ',
						'url'=>'Yii::app()->createUrl("/admin/tmm_orderOrganizer/start",array("id"=>$data->id))',
						'click'=>$click,
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/online.png',
					),
					'cancel'=>array(
						'label'=>'取消',
						'visible'=>'($data->group_group==$data::group_group_peering || $data->group_group==$data::group_group_already_peer)',
						'url'=>'Yii::app()->createUrl("/admin/tmm_orderOrganizer/cancel",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/delete.png',
					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
		),
	),
)); 
?>
