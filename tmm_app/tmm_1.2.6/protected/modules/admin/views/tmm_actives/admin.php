<?php
/* @var $this Tmm_activesController */
/* @var $model Actives */

$this->breadcrumbs=array(
	'线路管理页'=>array('/admin/tmm_shops/admin'),
	'活动管理页'
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#actives-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>活动管理页</h1>
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
	$.fn.yiiGridView.update('actives-grid',{  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data){
	$.fn.yiiGridView.update('actives-grid');  
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
	jQuery('#actives-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#actives-grid').yiiGridView('update');
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
	jQuery('#go_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	
}
");

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'actives-grid',
	'dataProvider'=>$model->search(),
	'enableHistory'=>true,
	'afterAjaxUpdate' => 'reinstallDatePicker',
	'filter'=>$model,
	'columns'=>array(
		array(
				//'class'=>'DataColumn',
				//'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'id',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:20px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'filter'=>CHtml::activeTextField($model->Actives_Shops, 'name', array('id'=>false)),
				'name'=>'Actives_Shops.name',
				'value'=>'$data->Actives_Shops->name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:120px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
//		array(
//				'class'=>'DataColumn',
//				'evaluateHtmlOptions'=>true,
//				'name'=>'organizer_id',
//				'value'=>'$data->Actives_Organizer->Organizer_User->phone',
//				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
//				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Actives_Organizer.firm_phone").":".$data->Actives_Organizer->firm_phone'),
//		),
		array(
				//'class'=>'DataColumn',
				//'evaluateHtmlOptions'=>true,
				'name'=>'organizer_id',
				'value'=>'$data->Actives_User->phone',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				//'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("User_Organizer.firm_phone").":".$data->Actives_User->User_Organizer->firm_phone'),
		),
		array(
			'filter'=>CHtml::activeDropDownList($model, 'is_organizer',array(''=>'')+Actives::$_is_organizer, array('id'=>false)),
			'name'=>'is_organizer',
			'value'=>'$data::$_is_organizer[$data->is_organizer]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'filter'=>CHtml::activeDropDownList($model, 'actives_type',array(''=>'')+Actives::$_actives_type, array('id'=>false)),
				'name'=>'actives_type',
				'value'=>'$data::$_actives_type[$data->actives_type]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'filter'=>CHtml::activeDropDownList($model, 'tour_type',array(''=>'')+Actives::$_tour_type, array('id'=>false)),
				'name'=>'tour_type',
				'value'=>'$data::$_tour_type[$data->tour_type]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				//'class'=>'DataColumn',
				//'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'tour_count',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				//'class'=>'DataColumn',
				//'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'order_count',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'push',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
						$data->getAttributeLabel("push").":".$data->push."%\n".
						$data->getAttributeLabel("push_agent").":".$data->push_agent."%\n".
						$data->getAttributeLabel("push_store").":".$data->push_store."%\n".
						$data->getAttributeLabel("push_orgainzer").":".$data->push_orgainzer."%\n"		
					'),
		),
		array(
				//'class'=>'DataColumn',
				//'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'price',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'number',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("order_number").":".$data->order_number'),
		),
		array(
				//'class'=>'DataColumn',
				//'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'tour_price',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				//'class'=>'DataColumn',
				//'evaluateHtmlOptions'=>true,
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>Yii::app()->language,
						'model'=>$model,
						'attribute'=>'start_time',
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
								'id' =>'start_time_date',
						),
					),true),
				'name'=>'start_time',
				'type'=>'date',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				//'class'=>'DataColumn',
				//'evaluateHtmlOptions'=>true,
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>Yii::app()->language,
						'model'=>$model,
						'attribute'=>'end_time',
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
								'id' =>'end_time_date',
						),
					),true),
				'name'=>'end_time',
				'type'=>'date',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
// 		array(
// 				//'class'=>'DataColumn',
// 				//'evaluateHtmlOptions'=>true,
// 				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
// 						'language'=>Yii::app()->language,
// 						'model'=>$model,
// 						'attribute'=>'pub_time',
// 						'value'=>date('Y-m-d'),
// 						'options'=>array(
// 								'maxDate'=>'new date()',
// 								'dateFormat'=>'yy-mm-dd',
// 								'showOn' => 'focus',
// 								'showOtherMonths' => true,
// 								'selectOtherMonths' => true,
// 								'changeMonth' => true,
// 								'changeYear' => true,
// 								'showButtonPanel' => true,
// 						),
// 						'htmlOptions'=>array(
// 								'id' =>'pub_time_date',
// 						),
// 					),true),
// 				'name'=>'pub_time',
// 				'type'=>'datetime',
// 				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
// 				'htmlOptions'=>array('style'=>'text-align:center;'),
// 		),
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
				'value'=>'$data->go_time==0?"--":date("Y-m-d",$data->go_time)',
				//'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeDropDownList($model, 'is_open',array(''=>'')+Actives::$_is_open, array('id'=>false)),
			'name'=>'is_open',
			'value'=>'$data::$_is_open[$data->is_open]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeDropDownList($model, 'pay_type',array(''=>'')+Actives::$_pay_type, array('id'=>false)),
			'name'=>'pay_type',
			'value'=>'$data::$_pay_type[$data->pay_type]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'filter'=>CHtml::activeDropDownList($model, 'actives_status',array(''=>'')+Actives::$_actives_status, array('id'=>false)),
				'name'=>'actives_status',
				'value'=>'$data::$_actives_status[$data->actives_status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeDropDownList($model->Actives_Shops, 'audit',array(''=>'')+Shops::$_audit, array('id'=>false)),
			'name'=>'Actives_Shops.audit',
			'value'=>'Shops::$_audit[$data->Actives_Shops->audit]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeDropDownList($model, 'status',array(''=>'')+Actives::$_status, array('id'=>false)),
			'name'=>'status',
			'value'=>'$data::$_status[$data->status]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeDropDownList($model->Actives_Shops, 'is_sale',array(''=>'')+Shops::$_is_sale, array('id'=>false)),
			'name'=>'Actives_Shops.is_sale',
			'value'=>'Shops::$_is_sale[$data->Actives_Shops->is_sale]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeDropDownList($model->Actives_Shops, 'selected',array(''=>'')+Shops::$_selected, array('id'=>false)),
			'name'=>'Actives_Shops.selected',
			'value'=>'Shops::$_selected[$data->Actives_Shops->selected]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeDropDownList($model->Actives_Shops, 'tops',array(''=>'')+Shops::$_tops, array('id'=>false)),
			'name'=>'Actives_Shops.tops',
			'value'=>'Shops::$_tops[$data->Actives_Shops->tops]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeDropDownList($model->Actives_Shops, 'selected_tops',array(''=>'')+Shops::$_selected_tops, array('id'=>false)),
			'name'=>'Actives_Shops.selected_tops',
			'value'=>'Shops::$_selected_tops[$data->Actives_Shops->selected_tops]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		/**/
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}{update}{delete}{audit}{pack}{tags_type}{disable}{start}',
			'buttons'=>array(
					'view'=>array(
						'label'=>'查看',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
					'update'=>array(
						'label'=>'更新',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'visible'=>'$data->Actives_Shops->status==Shops::status_offline && $data->Actives_Shops->audit != Shops::audit_pass',
						//array('/admin/'.$this->prefix.'actives'.'/'.Actives::$__tour_type[$model->tour_type].'_create'
						'url'=>'Yii::app()->createUrl("/admin/tmm_actives/".Actives::$__tour_type[$data->tour_type]."_update",array("id"=>$data->id))',
					),
				    'delete'=>array(
						'visible'=>'$data->status==0 && $data->Actives_Shops->status==Shops::status_offline',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
//					'tops'=>array(
//						'label'=>'置顶',
//						'visible'=>'$data->Actives_Shops->status==1 && $data->Actives_Shops->audit==Shops::audit_pass && $data->Actives_Shops->tops ==Shops::tops_no',
//						'url'=>'Yii::app()->createUrl("/admin/tmm_shops/tops",array("id"=>$data->id))',
//						'click'=>$click,
//						'options'=>array('style'=>'padding:0 8px 0 0;'),
//						//'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/online.png',
//					),
//					'tops_no'=>array(
//						'label'=>'取消置顶',
//						'visible'=>'$data->Actives_Shops->status==1 && $data->Actives_Shops->audit==Shops::audit_pass && $data->Actives_Shops->tops == Shops::tops_yes',
//						'url'=>'Yii::app()->createUrl("/admin/tmm_shops/tops_no",array("id"=>$data->id))',
//						'click'=>$click,
//						'options'=>array('style'=>'padding:0 8px 0 0;'),
//						//'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/offline.png',
//					),
//					'selected_tops'=>array(
//						'label'=>'推荐置顶',
//						'visible'=>'$data->Actives_Shops->status==1 && $data->Actives_Shops->audit==Shops::audit_pass && $data->Actives_Shops->selected_tops ==Shops::selected_tops_no',
//						'url'=>'Yii::app()->createUrl("/admin/tmm_shops/selected_tops",array("id"=>$data->id))',
//						'click'=>$click,
//						'options'=>array('style'=>'padding:0 8px 0 0;'),
//						//'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/online.png',
//					),
//					'selected_tops_no'=>array(
//						'label'=>'取消推荐置顶',
//						'visible'=>'$data->Actives_Shops->status==1 && $data->Actives_Shops->audit==Shops::audit_pass && $data->Actives_Shops->selected_tops == Shops::selected_tops_yes',
//						'url'=>'Yii::app()->createUrl("/admin/tmm_shops/selected_tops_no",array("id"=>$data->id))',
//						'click'=>$click,
//						'options'=>array('style'=>'padding:0 8px 0 0;'),
//						//'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/offline.png',
//					),
					'audit'=>array(
						'label'=>'审核',
						'visible'=>'$data->Actives_Shops->status==Shops::status_offline && $data->Actives_Shops->audit==Shops::audit_pending',
						'url'=>'Yii::app()->createUrl("/admin/tmm_actives/view",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 15px 0 0;'),
						//'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/audit.png',
					),
					'pack'=>array(
						'label'=>'包装',
						'visible'=>'$data->Actives_Shops->list_info == "" && $data->Actives_Shops->status==Shops::status_offline && $data->Actives_Shops->audit==Shops::audit_pass',
						'url'=>'Yii::app()->createUrl("/admin/tmm_actives/pack",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 15px 0 0;'),
					//	'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/pack.png',
					),
					'tags_type'=>array(
						'label'=>'标签',
						'visible'=>'$data->Actives_Shops->status==0 && $data->Actives_Shops->audit==Shops::audit_pass ',
						'url'=>'Yii::app()->createUrl("admin/tmm_actives/select",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					//	'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/tag.png',
					),
					'disable'=>array(
						'label'=>'下线',
						'visible'=>' $data->Actives_Shops->status==1',
						'url'=>'Yii::app()->createUrl("/admin/tmm_actives/disable",array("id"=>$data->id))',
						'click'=>$click,
					),
					'start'=>array(
						'label'=>'上线',
						'visible'=>'$data->Actives_Shops->list_info != "" &&  $data->Actives_Shops->status==0 && $data->Actives_Shops->audit==Shops::audit_pass',
						'url'=>'Yii::app()->createUrl("/admin/tmm_actives/start",array("id"=>$data->id))',
						'click'=>$click,
					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
		),
	),
)); 
?>
