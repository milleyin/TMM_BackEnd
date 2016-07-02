<?php
/* @var $this Tmm_thrandController */
/* @var $model Thrand */

$this->breadcrumbs=array(
	'线路管理页'=>array('/admin/tmm_shops/admin'),
	'线路(线)管理页',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#thrand-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>管理页 线路(线)</h1>
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
	$.fn.yiiGridView.update('thrand-grid',{  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data){
	$.fn.yiiGridView.update('thrand-grid');  
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
	jQuery('#thrand-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#thrand-grid').yiiGridView('update');
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
		jQuery('#pub_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#add_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#up_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));

}
");

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'thrand-grid',
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
			'name'=>'Thrand_Shops.name',
			'filter'=>CHtml::activeTextField($model->Thrand_Shops, 'name', array('id'=>false)),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'DataColumn',
			'evaluateHtmlOptions'=>true,
			'filter'=>CHtml::activeTextField($model->Thrand_Shops, 'agent_id', array('id'=>false)),
			'name'=>'Thrand_Shops.agent_id',
			'value'=>'$data->Thrand_Shops->Shops_Agent->phone',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Thrand_Shops.Shops_Agent.firm_name").":".$data->Thrand_Shops->Shops_Agent->firm_name'),
		),
		array(
			'filter'=>CHtml::activeTextField($model->Thrand_Shops, 'brow', array('id'=>false)),
			'name'=>'Thrand_Shops.brow',
			'value'=>'$data->Thrand_Shops->brow',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeTextField($model->Thrand_Shops, 'share', array('id'=>false)),
			'name'=>'Thrand_Shops.share',
			'value'=>'$data->Thrand_Shops->share',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeTextField($model->Thrand_Shops, 'praise', array('id'=>false)),
			'name'=>'Thrand_Shops.praise',
			'value'=>'$data->Thrand_Shops->praise',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
				'language'=>Yii::app()->language,
				'model'=>$model->Thrand_Shops,
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
			'name'=>'Thrand_Shops.pub_time',
			'type'=>'datetime',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'DataColumn',
			'evaluateHtmlOptions'=>true,
			'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
				'language'=>Yii::app()->language,
				'model'=>$model->Thrand_Shops,
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
			'name'=>'Thrand_Shops.add_time',
			'type'=>'datetime',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>Yii::app()->language,
						'model'=>$model->Thrand_Shops,
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
				'name'=>'Thrand_Shops.up_time',
				'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeDropDownList($model->Thrand_Shops, 'audit',array(''=>'')+Shops::$_audit, array('id'=>false)),
			'name'=>'Thrand_Shops.audit',
			'value'=>'Shops::$_audit[$data->Thrand_Shops->audit]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeDropDownList($model->Thrand_Shops, 'status',array(''=>'')+Shops::$_status, array('id'=>false)),
			'name'=>'Thrand_Shops.status',
			'value'=>'Shops::$_status[$data->Thrand_Shops->status]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeDropDownList($model->Thrand_Shops, 'is_sale',array(''=>'')+Shops::$_is_sale, array('id'=>false)),
			'name'=>'Thrand_Shops.is_sale',
			'value'=>'Shops::$_is_sale[$data->Thrand_Shops->is_sale]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeDropDownList($model->Thrand_Shops, 'selected',array(''=>'')+Shops::$_selected, array('id'=>false)),
			'name'=>'Thrand_Shops.selected',
			'value'=>'Shops::$_selected[$data->Thrand_Shops->selected]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeDropDownList($model->Thrand_Shops, 'tops',array(''=>'')+Shops::$_tops, array('id'=>false)),
			'name'=>'Thrand_Shops.tops',
			'value'=>'Shops::$_tops[$data->Thrand_Shops->tops]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeDropDownList($model->Thrand_Shops, 'selected_tops',array(''=>'')+Shops::$_selected_tops, array('id'=>false)),
			'name'=>'Thrand_Shops.selected_tops',
			'value'=>'Shops::$_selected_tops[$data->Thrand_Shops->selected_tops]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}{update}{tags_type}{audit}{confirm}{pack}{delete}{disable}{start}',
			'buttons'=>array(
				'view'=>array(
					'label'=>'查看',
					'options'=>array('style'=>'padding:0 15px 0 0;'),
					'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/view.png',
				),
				'update'=>array(
					'label'=>'更新',
					'visible'=>'$data->Thrand_Shops->status==0',
					'options'=>array('style'=>'padding:0 8px 0 0;'),
					'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/update.png',
				),
//				'tops'=>array(
//					'label'=>'置顶',
//					'visible'=>'$data->Thrand_Shops->status==1 && $data->Thrand_Shops->audit==Shops::audit_pass && $data->Thrand_Shops->tops ==Shops::tops_no',
//					'url'=>'Yii::app()->createUrl("/admin/tmm_shops/tops",array("id"=>$data->id))',
//					'click'=>$click,
//					'options'=>array('style'=>'padding:0 8px 0 0;'),
//					//'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/online.png',
//				),
//				'tops_no'=>array(
//					'label'=>'取消置顶',
//					'visible'=>'$data->Thrand_Shops->status==1 && $data->Thrand_Shops->audit==Shops::audit_pass && $data->Thrand_Shops->tops == Shops::tops_yes',
//					'url'=>'Yii::app()->createUrl("/admin/tmm_shops/tops_no",array("id"=>$data->id))',
//					'click'=>$click,
//					'options'=>array('style'=>'padding:0 8px 0 0;'),
//					//'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/offline.png',
//				),
//				'selected_tops'=>array(
//					'label'=>'推荐置顶',
//					'visible'=>'$data->Thrand_Shops->status==1 && $data->Thrand_Shops->audit==Shops::audit_pass && $data->Thrand_Shops->selected_tops ==Shops::selected_tops_no',
//					'url'=>'Yii::app()->createUrl("/admin/tmm_shops/selected_tops",array("id"=>$data->id))',
//					'click'=>$click,
//					'options'=>array('style'=>'padding:0 8px 0 0;'),
//					//'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/online.png',
//				),
//				'selected_tops_no'=>array(
//					'label'=>'取消推荐置顶',
//					'visible'=>'$data->Thrand_Shops->status==1 && $data->Thrand_Shops->audit==Shops::audit_pass && $data->Thrand_Shops->selected_tops == Shops::selected_tops_yes',
//					'url'=>'Yii::app()->createUrl("/admin/tmm_shops/selected_tops_no",array("id"=>$data->id))',
//					'click'=>$click,
//					'options'=>array('style'=>'padding:0 8px 0 0;'),
//					//'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/offline.png',
//				),
				'tags_type'=>array(
					'label'=>'标签',
					'visible'=>'$data->Thrand_Shops->status==0 && $data->Thrand_Shops->audit != Shops::audit_pending',
					'url'=>'Yii::app()->createUrl("admin/tmm_thrand/select",array("id"=>$data->id))',
					'options'=>array('style'=>'padding:0 8px 0 0;'),
					'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/tag.png',
				),
				'audit'=>array(
					'label'=>'审核',
					'visible'=>'$data->Thrand_Shops->status==0 && $data->Thrand_Shops->audit==Shops::audit_pending',
					'url'=>'Yii::app()->createUrl("/admin/tmm_thrand/view",array("id"=>$data->id))',
					'options'=>array('style'=>'padding:0 8px 0 0;'),
					'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/audit.png',
				),
				'confirm'=>array(
					'label'=>'提交',
					'visible'=>'$data->Thrand_Shops->status==0 && $data->Thrand_Shops->audit==Shops::audit_draft',
					'url'=>'Yii::app()->createUrl("/admin/tmm_thrand/confirm",array("id"=>$data->id))',
					'click'=>$click,
					'options'=>array('style'=>'padding:0 8px 0 0;'),
					'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/submit.png',
				),
				'pack'=>array(
					'label'=>'包装',
					'visible'=>'$data->Thrand_Shops->status==0 && $data->Thrand_Shops->audit==Shops::audit_pass',
					'url'=>'Yii::app()->createUrl("/admin/tmm_thrand/pack",array("id"=>$data->id))',
					'options'=>array('style'=>'padding:0 8px 0 0;'),
					'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/pack.png',
				),
				'delete'=>array(
					'label'=>'删除',
					'visible'=>'$data->Thrand_Shops->status==0',
					'options'=>array('style'=>'padding:0 8px 0 0;'),
					'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/delete.png',
				),
				'disable'=>array(
					'label'=>'下线',
					'visible'=>'$data->Thrand_Shops->status==1',
					'url'=>'Yii::app()->createUrl("/admin/tmm_thrand/disable",array("id"=>$data->id))',
					'click'=>$click,
					'options'=>array('style'=>'padding:0 8px 0 0;'),
					'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/disable.png',
				),
				'start'=>array(
					'label'=>'上线',
					'visible'=>'$data->Thrand_Shops->status==0 && $data->Thrand_Shops->audit==Shops::audit_pass',
					'url'=>'Yii::app()->createUrl("/admin/tmm_thrand/start",array("id"=>$data->id))',
					'click'=>$click,
					'options'=>array('style'=>'padding:0 8px 0 0;'),
					'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/enable.png',
				),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
		),
	),
)); 
?>
