<?php
/* @var $this Tmm_shopsController */
/* @var $model Shops */

$this->breadcrumbs=array(
	'线路管理页',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#shops-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>管理页 线路</h1>

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
	$.fn.yiiGridView.update('shops-grid',{  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data){
	$.fn.yiiGridView.update('shops-grid');  
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
	jQuery('#shops-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#shops-grid').yiiGridView('update');
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
	'id'=>'shops-grid',
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
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'c_id',
				'filter'=>ShopsClassliy::data(true,array()),
				'value'=>'$data->Shops_ShopsClassliy->name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Shops_ShopsClassliy.info").":".$data->Shops_ShopsClassliy->info'),
		),
		array(
				'name'=>'name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'agent_id',
				'value'=>'isset($data->Shops_Agent->phone)?$data->Shops_Agent->phone:"无运营商"',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'isset($data->Shops_Agent->firm_name)?($data->getAttributeLabel("Shops_Agent.firm_name").":".$data->Shops_Agent->firm_name):"没有运营商"'),
		),
		array(
				'name'=>'brow',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'share',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'praise',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
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
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
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
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'filter'=>$model::$_audit,
				'name'=>'audit',
				'value'=>'$data::$_audit[$data->audit]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'filter'=>$model::$_status,
				'name'=>'status',
				'value'=>'$data::$_status[$data->status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>$model::$_is_sale,
			'name'=>'is_sale',
			'value'=>'$data::$_is_sale[$data->is_sale]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>$model::$_selected,
			'name'=>'selected',
			'value'=>'$data::$_selected[$data->selected]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>$model::$_tops,
			'name'=>'tops',
			'value'=>'$data::$_tops[$data->tops]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>$model::$_selected_tops,
			'name'=>'selected_tops',
			'value'=>'$data::$_selected_tops[$data->selected_tops]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}{update}{selected}{remove}{tops}{tops_no}{tags}{audit}{confirm}{pack}{delete}{disable}{start}{sale_yes}{sale_no}',
			'buttons'=>array(
					'view'=>array(
						'label'=>'查看',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'url'=>'Yii::app()->createUrl("/admin/tmm_".$data->Shops_ShopsClassliy->admin."/view",array("id"=>$data->id))',
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/view.png',
					),
					'selected'=>array(
						'label'=>'推荐',
						'visible'=>'$data->status==1 && $data->audit==Shops::audit_pass && $data->selected !=Shops::selected',
						'url'=>'Yii::app()->createUrl("/admin/tmm_shops/selected",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/online.png',
					),
					'remove'=>array(
						'label'=>'取消推荐',
						'visible'=>'$data->status==1 && $data->audit==Shops::audit_pass && $data->selected == Shops::selected',
						'url'=>'Yii::app()->createUrl("/admin/tmm_shops/remove",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/offline.png',
					),
					'tops'=>array(
						'label'=>'置顶',
						'visible'=>'$data->status==1 && $data->audit==Shops::audit_pass && $data->tops ==Shops::tops_no',
						'url'=>'Yii::app()->createUrl("/admin/tmm_shops/tops",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						//'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/online.png',
					),
					'tops_no'=>array(
						'label'=>'取消置顶',
						'visible'=>'$data->status==1 && $data->audit==Shops::audit_pass && $data->tops == Shops::tops_yes',
						'url'=>'Yii::app()->createUrl("/admin/tmm_shops/tops_no",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						//'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/offline.png',
					),
//					'selected_tops'=>array(
//						'label'=>'推荐置顶',
//						'visible'=>'$data->status==1 && $data->audit==Shops::audit_pass && $data->selected_tops ==Shops::selected_tops_no',
//						'url'=>'Yii::app()->createUrl("/admin/tmm_shops/selected_tops",array("id"=>$data->id))',
//						'click'=>$click,
//						'options'=>array('style'=>'padding:0 8px 0 0;'),
//						//'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/online.png',
//					),
//					'selected_tops_no'=>array(
//						'label'=>'取消推荐置顶',
//						'visible'=>'$data->status==1 && $data->audit==Shops::audit_pass && $data->selected_tops == Shops::selected_tops_yes',
//						'url'=>'Yii::app()->createUrl("/admin/tmm_shops/selected_tops_no",array("id"=>$data->id))',
//						'click'=>$click,
//						'options'=>array('style'=>'padding:0 8px 0 0;'),
//						//'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/offline.png',
//					),
					'update'=>array(
						'label'=>'更新',
						'visible'=>'$data->status==0 && $data->audit != Shops::audit_pending && $data->Shops_ShopsClassliy->append=="Dot"',
						'url'=>'Yii::app()->createUrl("/admin/tmm_".$data->Shops_ShopsClassliy->admin."/update",array("id"=>$data->id))',			
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/update.png',
					),
					'tags'=>array(
						'label'=>'标签',
						'visible'=>'$data->status==0 && $data->audit != Shops::audit_pending',
						'url'=>'Yii::app()->createUrl("/admin/tmm_".$data->Shops_ShopsClassliy->admin."/select",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/tag.png',
					),
					'audit'=>array(
						'label'=>'审核',
						'visible'=>'$data->status==0 && $data->audit==Shops::audit_pending',
						'url'=>'Yii::app()->createUrl("/admin/tmm_".$data->Shops_ShopsClassliy->admin."/view",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/audit.png',
					),
					'confirm'=>array(
						'label'=>'提交',
						'visible'=>'$data->status==0 && $data->audit==Shops::audit_draft',
						'url'=>'Yii::app()->createUrl("/admin/tmm_".$data->Shops_ShopsClassliy->admin."/confirm",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/submit.png',
					),
					'pack'=>array(
						'label'=>'包装',
						'visible'=>'$data->status==0 && $data->audit==Shops::audit_pass',
						'url'=>'Yii::app()->createUrl("/admin/tmm_".$data->Shops_ShopsClassliy->admin."/pack",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/pack.png',
					),
				  'delete'=>array(
					    'label'=>'删除',
						'visible'=>'$data->status==0',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
				  		'url'=>'Yii::app()->createUrl("/admin/tmm_".$data->Shops_ShopsClassliy->admin."/delete",array("id"=>$data->id))',
					   'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/delete.png',
					),
					'disable'=>array(
						'label'=>'下线',
						'visible'=>'$data->status==1',
						'url'=>'Yii::app()->createUrl("/admin/tmm_".$data->Shops_ShopsClassliy->admin."/disable",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/disable.png',
					),
					'start'=>array(
						'label'=>'上线',
						'visible'=>'$data->status==0 && $data->audit==Shops::audit_pass',
						'url'=>'Yii::app()->createUrl("/admin/tmm_".$data->Shops_ShopsClassliy->admin."/start",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/enable.png',
					),
					'sale_yes'=>array(
						'label'=>'可卖',
						'visible'=>'$data->is_sale==0',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'url'=>'Yii::app()->createUrl("/admin/tmm_shops/sale_yes",array("id"=>$data->id))',
						//'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/delete.png',
					),
					'sale_no'=>array(
						'label'=>'非卖',
						'visible'=>'$data->is_sale==1',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'url'=>'Yii::app()->createUrl("/admin/tmm_shops/sale_no",array("id"=>$data->id))',
						//'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/delete.png',
					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
		),
	),
)); 
?>
