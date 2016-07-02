<?php
/* @var $this Tmm_groupController */
/* @var $model Group */

$this->breadcrumbs=array(
	'线路管理页'=>array('/admin/tmm_shops/admin'),
	'结伴游管理页',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#group-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>管理页 结伴游</h1>
<div>
	</span>
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
	$.fn.yiiGridView.update('group-grid',{  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data){
	$.fn.yiiGridView.update('group-grid');  
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
	jQuery('#group-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#group-grid').yiiGridView('update');
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
	jQuery('#start_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#end_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#pub_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#group_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	
}
");

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'group-grid',
	'dataProvider'=>$model->search(),
	'enableHistory'=>true,
	'afterAjaxUpdate' => 'reinstallDatePicker',
	'filter'=>$model,
	'columns'=>array(
		array(
				//'filter'=>,
				'name'=>'id',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:20px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeTelField($model->Group_Shops, 'name', array('id'=>false)),
			'name'=>'Group_Shops.name',
			'value'=>'$data->Group_Shops->name',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				//'filter'=>,
				'name'=>'user_id',
				'value'=>'$data->Group_User->phone."[".$data->Group_User->nickname."]"',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeTelField($model->Group_Shops, 'brow', array('id'=>false)),
			'name'=>'Group_Shops.brow',
			'value'=>'$data->Group_Shops->brow',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:30px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeTelField($model->Group_Shops, 'share', array('id'=>false)),
			'name'=>'Group_Shops.share',
			'value'=>'$data->Group_Shops->share',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:30px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeTelField($model->Group_Shops, 'praise', array('id'=>false)),
			'name'=>'Group_Shops.praise',
			'value'=>'$data->Group_Shops->praise',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:30px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>Chtml::activeDropDownList($model->Group_Shops, 'audit', array(''=>'')+Shops::$_audit, array('id'=>false)),
			'name'=>'Group_Shops.audit',
			'value'=>'Shops::$_audit[$data->Group_Shops->audit]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeDropDownList($model, 'group', array(''=>'')+$model::$_group, array('id'=>false)),
			'name'=>'group',
			'value'=>'$data::$_group[$data->group]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeDropDownList($model->Group_Shops, 'status', array(''=>'')+Shops::$_status, array('id'=>false)),
			'name'=>'status',
			'value'=>'Shops::$_status[$data->Group_Shops->status]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				//'class'=>'DataColumn',
				//'evaluateHtmlOptions'=>true,
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>Yii::app()->language,
						'model'=>$model,
						'attribute'=>'pub_time',
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
								'id' =>'pub_time_date',
						),
					),true),
				'name'=>'pub_time',
				'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			//'class'=>'DataColumn',
			//'evaluateHtmlOptions'=>true,
			'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
					'language'=>Yii::app()->language,
					'model'=>$model,
					'attribute'=>'group_time',
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
							'id' =>'group_time_date',
					),
				),true),
			'name'=>'group_time',
			'type'=>'datetime',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),

		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}{audit}{pack}',
			'buttons'=>array(
					'view'=>array(
						'label'=>'查看',
						'options'=>array('style'=>'padding:0 15px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/view.png',
					),
					'audit'=>array(
						'label'=>'审核',
						'visible'=>'$data->Group_Shops->status==0 && $data->Group_Shops->audit==Shops::audit_pending',
						'url'=>'Yii::app()->createUrl("/admin/tmm_group/view",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 15px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/audit.png',
					),
					'pack'=>array(
						'label'=>'包装',
						'visible'=>'$data->Group_Shops->status==0 && $data->Group_Shops->page_info=="" && $data->Group_Shops->audit==Shops::audit_pass',
						'url'=>'Yii::app()->createUrl("/admin/tmm_group/pack",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 15px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/pack.png',
					),
//					'disable'=>array(
//						'label'=>'下线',
//						'visible'=>'$data->Group_Shops->status==1',
//						'url'=>'Yii::app()->createUrl("/admin/tmm_group/disable",array("id"=>$data->id))',
//						'click'=>$click,
//						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/offline.png',
//					),
//					'start'=>array(
//						'label'=>'上线',
//						'visible'=>'$data->Group_Shops->list_info != "" && $data->Group_Shops->status==0 && $data->Group_Shops->audit==Shops::audit_pass',
//						'url'=>'Yii::app()->createUrl("/admin/tmm_group/start",array("id"=>$data->id))',
//						'click'=>$click,
//						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/online.png',
//					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
		),
	),
)); 
?>
