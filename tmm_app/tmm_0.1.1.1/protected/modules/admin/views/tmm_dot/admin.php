<?php
/* @var $this Tmm_dotController */
/* @var $model Dot */

$this->breadcrumbs=array(
	'线路管理页'=>array('/admin/tmm_shops/admin'),
	'线路(点)管理页',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#dot-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>管理页 线路(点)</h1>

<?php echo CHtml::link('高级搜索','#',array('class'=>'search-button')); ?>

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
	$.fn.yiiGridView.update('dot-grid',{  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data){
	$.fn.yiiGridView.update('dot-grid');  
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
	jQuery('#dot-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#dot-grid').yiiGridView('update');
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
	'id'=>'dot-grid',
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
				'name'=>'Dot_Shops.name',
				'filter'=>CHtml::activeTextField($model->Dot_Shops, 'name', array('id'=>false)),
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'DataColumn',
			'evaluateHtmlOptions'=>true,
			'filter'=>CHtml::activeTextField($model->Dot_Shops, 'agent_id', array('id'=>false)),
			'name'=>'Dot_Shops.agent_id',
			'value'=>'$data->Dot_Shops->Shops_Agent->phone',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Dot_Shops.Shops_Agent.firm_name").":".$data->Dot_Shops->Shops_Agent->firm_name'),
		),
		array(
			'filter'=>CHtml::activeTextField($model->Dot_Shops, 'brow', array('id'=>false)),
			'name'=>'Dot_Shops.brow',
			'value'=>'$data->Dot_Shops->brow',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeTextField($model->Dot_Shops, 'share', array('id'=>false)),
			'name'=>'Dot_Shops.share',
			'value'=>'$data->Dot_Shops->share',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeTextField($model->Dot_Shops, 'praise', array('id'=>false)),
			'name'=>'Dot_Shops.praise',
			'value'=>'$data->Dot_Shops->praise',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
				'language'=>Yii::app()->language,
				'model'=>$model->Dot_Shops,
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
			'name'=>'Dot_Shops.pub_time',
			'type'=>'datetime',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
				'language'=>Yii::app()->language,
				'model'=>$model->Dot_Shops,
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
			'name'=>'Dot_Shops.add_time',
			'type'=>'datetime',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>Yii::app()->language,
						'model'=>$model->Dot_Shops,
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
				'name'=>'Dot_Shops.up_time',
				'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeDropDownList($model->Dot_Shops, 'audit',array(''=>'')+Shops::$_audit, array('id'=>false)),
			'name'=>'Dot_Shops.audit',
			'value'=>'Shops::$_audit[$data->Dot_Shops->audit]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'filter'=>CHtml::activeDropDownList($model->Dot_Shops, 'status',array(''=>'')+Shops::$_status, array('id'=>false)),
				'name'=>'Dot_Shops.status',
				'value'=>'Shops::$_status[$data->Dot_Shops->status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeDropDownList($model->Dot_Shops, 'is_sale',array(''=>'')+Shops::$_is_sale, array('id'=>false)),
			'name'=>'Dot_Shops.is_sale',
			'value'=>'Shops::$_is_sale[$data->Dot_Shops->is_sale]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeDropDownList($model->Dot_Shops, 'selected',array(''=>'')+Shops::$_selected, array('id'=>false)),
			'name'=>'Dot_Shops.selected',
			'value'=>'Shops::$_selected[$data->Dot_Shops->selected]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeDropDownList($model->Dot_Shops, 'tops',array(''=>'')+Shops::$_tops, array('id'=>false)),
			'name'=>'Dot_Shops.tops',
			'value'=>'Shops::$_tops[$data->Dot_Shops->tops]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeDropDownList($model->Dot_Shops, 'selected_tops',array(''=>'')+Shops::$_selected_tops, array('id'=>false)),
			'name'=>'Dot_Shops.selected_tops',
			'value'=>'Shops::$_selected_tops[$data->Dot_Shops->selected_tops]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}{farm}{update}{tags}{audit}{confirm}{pack}{delete}{disable}{start}',
			'buttons'=>array(
					'view'=>array(
						'label'=>'查看',								
						'options'=>array('style'=>'padding:0 15px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/view.png',
					),
					'farm'=>array(
						'label'=>'添加农产品外链',
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/shop.png',
						'visible'=>'$data->Dot_Shops->status!=-1',
						'url'=>'Yii::app()->createUrl("/admin/tmm_farmOuter/create",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 15px 0 0;'),
					),
					'update'=>array(
						'label'=>'更新',
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/update.png',
						'visible'=>'$data->Dot_Shops->status==0 && $data->Dot_Shops->audit != Shops::audit_pending',
						'options'=>array('style'=>'padding:0 15px 0 0;'),
					),
//					'tops'=>array(
//						'label'=>'置顶',
//						'visible'=>'$data->Dot_Shops->status==1 && $data->Dot_Shops->audit==Shops::audit_pass && $data->Dot_Shops->tops ==Shops::tops_no',
//						'url'=>'Yii::app()->createUrl("/admin/tmm_shops/tops",array("id"=>$data->id))',
//						'click'=>$click,
//						'options'=>array('style'=>'padding:0 8px 0 0;'),
//						//'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/online.png',
//					),
//					'tops_no'=>array(
//						'label'=>'取消置顶',
//						'visible'=>'$data->Dot_Shops->status==1 && $data->Dot_Shops->audit==Shops::audit_pass && $data->Dot_Shops->tops == Shops::tops_yes',
//						'url'=>'Yii::app()->createUrl("/admin/tmm_shops/tops_no",array("id"=>$data->id))',
//						'click'=>$click,
//						'options'=>array('style'=>'padding:0 8px 0 0;'),
//						//'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/offline.png',
//					),
//					'selected_tops'=>array(
//						'label'=>'推荐置顶',
//						'visible'=>'$data->Dot_Shops->status==1 && $data->Dot_Shops->audit==Shops::audit_pass && $data->Dot_Shops->selected_tops ==Shops::selected_tops_no',
//						'url'=>'Yii::app()->createUrl("/admin/tmm_shops/selected_tops",array("id"=>$data->id))',
//						'click'=>$click,
//						'options'=>array('style'=>'padding:0 8px 0 0;'),
//						//'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/online.png',
//					),
//					'selected_tops_no'=>array(
//						'label'=>'取消推荐置顶',
//						'visible'=>'$data->Dot_Shops->status==1 && $data->Dot_Shops->audit==Shops::audit_pass && $data->Dot_Shops->selected_tops == Shops::selected_tops_yes',
//						'url'=>'Yii::app()->createUrl("/admin/tmm_shops/selected_tops_no",array("id"=>$data->id))',
//						'click'=>$click,
//						'options'=>array('style'=>'padding:0 8px 0 0;'),
//						//'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/offline.png',
//					),
					'tags'=>array(
						'label'=>'标签管理',
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/tag.png',
						'visible'=>'$data->Dot_Shops->status==0 && $data->Dot_Shops->audit != Shops::audit_pending',
						'url'=>'Yii::app()->createUrl("/admin/tmm_dot/select",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 15px 0 0;'),
					),
					'audit'=>array(
						'label'=>'审核管理',
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/audit.png',
						'visible'=>'$data->Dot_Shops->status==0 && $data->Dot_Shops->audit==Shops::audit_pending',
						'url'=>'Yii::app()->createUrl("/admin/tmm_dot/view",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 15px 0 0;'),
					),
					'confirm'=>array(
						'label'=>'提交审核',
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/submit.png',
						'visible'=>'$data->Dot_Shops->status==0 && $data->Dot_Shops->audit==Shops::audit_draft',
						'url'=>'Yii::app()->createUrl("/admin/tmm_dot/confirm",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 15px 0 0;'),
					),
					'pack'=>array(
						'label'=>'线路包装',
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/pack.png',
						'visible'=>'$data->Dot_Shops->status==0 && $data->Dot_Shops->audit==Shops::audit_pass',
						'url'=>'Yii::app()->createUrl("/admin/tmm_dot/pack",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
				  'delete'=>array(
				  		'label'=>'删除',
				  		'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/delete.png',
						'visible'=>'$data->Dot_Shops->status==0',
						'options'=>array('style'=>'padding:0 15px 0 0;'),
					),
					'disable'=>array(
						'label'=>'下线',
						'visible'=>'$data->Dot_Shops->status==1',
						'url'=>'Yii::app()->createUrl("/admin/tmm_dot/disable",array("id"=>$data->id))',
						'click'=>$click,
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/offline.png',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
					'start'=>array(
						'label'=>'上线',
						'visible'=>'$data->Dot_Shops->list_info != "" && $data->Dot_Shops->status==0 && $data->Dot_Shops->audit==Shops::audit_pass',
						'url'=>'Yii::app()->createUrl("/admin/tmm_dot/start",array("id"=>$data->id))',
						'click'=>$click,
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/online.png',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
		),
	),
)); 
?>
