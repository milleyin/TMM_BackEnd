<?php
/* @var $this Tmm_activesController */
/* @var $model Actives */

$this->breadcrumbs=array(
	'商品管理页'=>array('/admin/tmm_shops/admin'),
	'觅趣管理页'
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
<h1>管理 觅趣</h1>
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
	jQuery('#start_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#end_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#pub_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#go_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	
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
				'name'=>'id',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:35px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'filter'=>CHtml::activeDropDownList($model, 'is_organizer',array(''=>'')+Actives::$_is_organizer, array('id'=>false)),
				'name'=>'is_organizer',
				'value'=>'$data::$_is_organizer[$data->is_organizer]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:55px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
						$data->getAttributeLabel("Actives_User.phone")."：".$data->Actives_User->phone
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'organizer_id',
				'value'=>'$data->Actives_User->phone',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:75px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
						$data->getAttributeLabel("Actives_User.nickname").":".$data->Actives_User->nickname . "\n" .
						(
							$data->is_organizer == $data::is_organizer_yes 
							?
							(
								$data->getAttributeLabel("Actives_User.User_Organizer.firm_name")."：".$data->Actives_User->User_Organizer->firm_name . "\n" .
								$data->getAttributeLabel("Actives_User.User_Organizer.firm_phone")."：".$data->Actives_User->User_Organizer->firm_phone ."\n" . 
								$data->getAttributeLabel("Actives_User.User_Organizer.law_name")."：".$data->Actives_User->User_Organizer->law_name . "\n" .
								$data->getAttributeLabel("Actives_User.User_Organizer.manage_name")."：".$data->Actives_User->User_Organizer->manage_name
							) 
							: 
							""
						)
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>CHtml::activeTextField($model->Actives_Shops, 'name', array('id'=>false)),
				'name'=>'Actives_Shops.name',				
				'value'=>'mb_substr($data->Actives_Shops->name,0,8,"utf-8") . "..."',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:140px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
						$data->getAttributeLabel("Actives_Shops.name")."：".$data->Actives_Shops->name . "\n" .
						$data->getAttributeLabel("Actives_Shops.list_info")."：".$data->Actives_Shops->list_info . "\n" .
						$data->getAttributeLabel("Actives_Shops.page_info")."：".$data->Actives_Shops->page_info . "\n" .
						$data->getAttributeLabel("Actives_Shops.brow")."：".$data->Actives_Shops->brow . "\n" .	
						$data->getAttributeLabel("Actives_Shops.share")."：".$data->Actives_Shops->share . "\n" .
						$data->getAttributeLabel("Actives_Shops.tags_ids")."：".$data->Actives_Shops->tags_ids . "\n" .
						$data->getAttributeLabel("number")."：".$data->number . "\n" .
						$data->getAttributeLabel("order_number")."：".$data->order_number . "\n" .
						$data->getAttributeLabel("tour_count")."：".$data->tour_count . "\n" .
						$data->getAttributeLabel("order_count")."：".$data->order_count  . "\n" .
						$data->getAttributeLabel("tour_price")."：".$data->tour_price
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>Actives::$_tour_type,
				'name'=>'tour_type',
				'value'=>'$data::$_tour_type[$data->tour_type]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:45px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
						$data->getAttributeLabel("tour_type")."：". $data::$_tour_type[$data->tour_type] . "\n" .
						$data->getAttributeLabel("actives_type")."：". $data::$_actives_type[$data->actives_type]						
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>Actives::$_is_open,
				'name'=>'is_open',
				'value'=>'$data::$_is_open[$data->is_open]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:45px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
						$data->getAttributeLabel("is_open") ."：". $data::$_is_open[$data->is_open] . "\n" .
						$data->getAttributeLabel("barcode") ."：". $data->barcode . "\n" .
						$data->getAttributeLabel("barcode_num") ."：". $data->barcode_num
				'),
		),
		array(
				'filter'=>Actives::$_pay_type,
				'name'=>'pay_type',
				'value'=>'$data::$_pay_type[$data->pay_type]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
 				'class'=>'DataColumn',
 				'evaluateHtmlOptions'=>true,
				'name'=>'order_number',
				'value'=>'$data->order_number . " / " .$data->number',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'			
						$data->getAttributeLabel("number")."：".$data->number . "\n" .
						$data->getAttributeLabel("order_number")."：".$data->order_number . "\n" .
						$data->getAttributeLabel("tour_price")."：".$data->tour_price . "\n" .			
						$data->getAttributeLabel("tour_count")."：".$data->tour_count . "\n" .
						$data->getAttributeLabel("order_count")."：".$data->order_count 
				'),
		),
		array(
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>'zh-CN',
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
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				//'class'=>'DataColumn',
				//'evaluateHtmlOptions'=>true,
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>'zh-CN',
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
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				//'class'=>'DataColumn',
				//'evaluateHtmlOptions'=>true,
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>'zh-CN',
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
				'value'=>'$data->go_time==0?"--------------":date("Y/m/d",$data->go_time)',
				//'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'filter'=>Actives::$_actives_status,
				'name'=>'actives_status',
				'value'=>'$data::$_actives_status[$data->actives_status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>CHtml::activeDropDownList($model->Actives_Shops, 'audit',array(''=>'')+Shops::$_audit, array('id'=>false)),
				'name'=>'Actives_Shops.audit',
				'value'=>'Shops::$_audit[$data->Actives_Shops->audit]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
						$data->getAttributeLabel("Actives_Shops.audit")."：".
						AuditLog::get_audit_log(Shops::$__audit[$data->Actives_Shops->c_id],$data->id)->info
				'),
		),
		array(
				'filter'=>CHtml::activeDropDownList($model->Actives_Shops, 'is_sale',array(''=>'')+Shops::$_is_sale, array('id'=>false)),
				'name'=>'Actives_Shops.is_sale',
				'value'=>'Shops::$_is_sale[$data->Actives_Shops->is_sale]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>CHtml::activeDropDownList($model->Actives_Shops, 'tops',array(''=>'')+Shops::$_tops, array('id'=>false)),
				'name'=>'Actives_Shops.tops',
				'value'=>'Shops::$_tops[$data->Actives_Shops->tops]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
						$data->getAttributeLabel("Actives_Shops.tops_time")."：".
						($data->Actives_Shops->tops_time != 0 ? Yii::app()->format->FormatDateTime($data->Actives_Shops->tops_time) : "--------")
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>CHtml::activeDropDownList($model->Actives_Shops, 'selected',array(''=>'')+Shops::$_selected, array('id'=>false)),
				'name'=>'Actives_Shops.selected',
				'value'=>'Shops::$_selected[$data->Actives_Shops->selected]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'						
						$data->getAttributeLabel("Actives_Shops.selected_time")."：".
						($data->Actives_Shops->selected == Shops::selected ? Yii::app()->format->FormatDateTime($data->Actives_Shops->selected_time) : "--------") . "\n" .
						$data->getAttributeLabel("Actives_Shops.selected_info")."：".$data->Actives_Shops->selected_info				
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>CHtml::activeDropDownList($model->Actives_Shops, 'selected_tops',array(''=>'')+Shops::$_selected_tops, array('id'=>false)),
				'name'=>'Actives_Shops.selected_tops',
				'value'=>'Shops::$_selected_tops[$data->Actives_Shops->selected_tops]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:75px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
							$data->getAttributeLabel("Actives_Shops.selected_tops_time")."：".
							($data->Actives_Shops->selected_tops_time != 0 ? Yii::app()->format->FormatDateTime($data->Actives_Shops->selected_tops_time)	: "------")				
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>CHtml::activeDropDownList($model->Actives_Shops, 'hot',array(''=>'')+Shops::$_hot, array('id'=>false)),
				'name'=>'Actives_Shops.hot',
				'value'=>'Shops::$_hot[$data->Actives_Shops->hot]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
				$data->getAttributeLabel("Actives_Shops.hot_time")."：".
						($data->Actives_Shops->hot_time != 0 ? Yii::app()->format->FormatDateTime($data->Actives_Shops->hot_time)	: "--------")
				'),
		),
		array(
				'filter'=>Actives::$_status,
				'name'=>'status',
				'value'=>'$data::$_status[$data->status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:45px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'filter'=>CHtml::activeDropDownList($model->Actives_Shops, 'status',array(''=>'')+Shops::$_status, array('id'=>false)),
				'name'=>'Actives_Shops.status',
				'value'=>'Shops::$_status[$data->Actives_Shops->status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:45px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作', //{selected}{remove}{tops}{tops_no}{selected_tops}{selected_tops_no}
			'template'=>'{view}{update}{hot}{hot_no}{tags}{audit}{pack}{disable}{start}{delete}', 
			'buttons'=>array(
					'view'=>array(
						'label'=>'查看',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
					'update'=>array(
						'label'=>'更新',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'visible'=>'$data->Actives_Shops->status==Shops::status_offline && $data->Actives_Shops->audit != Shops::audit_pass',
						'url'=>'Yii::app()->createUrl("/admin/tmm_actives/".Actives::$__tour_type[$data->tour_type]."_update",array("id"=>$data->id))',
					),
					'hot'=>array(
							'label'=>'热门',
							'visible'=>'$data->Actives_Shops->status==1 && $data->Actives_Shops->audit==Shops::audit_pass && $data->Actives_Shops->hot == Shops::hot_no',
							'url'=>'Yii::app()->createUrl("/admin/tmm_shops/hot",array("id"=>$data->id))',
							'click'=>$click,
							'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
					'hot_no'=>array(
							'label'=>'取消热门',
							'visible'=>'$data->Actives_Shops->hot == Shops::hot_yes',
							'url'=>'Yii::app()->createUrl("/admin/tmm_shops/hot_no",array("id"=>$data->id))',
							'click'=>$click,
							'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
					'tags'=>array(
							'label'=>'标签',
							'visible'=>'$data->Actives_Shops->status==0 && $data->Actives_Shops->audit != Shops::audit_pending',
							'url'=>'Yii::app()->createUrl("/admin/tmm_actives/select",array("id"=>$data->id))',
							'options'=>array('style'=>'padding:0 8px 0 0;'),
							'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/tag.png',
					),
					'audit'=>array(
							'label'=>'审核',
							'visible'=>'$data->Actives_Shops->status==0 && $data->Actives_Shops->audit==Shops::audit_pending',
							'url'=>'Yii::app()->createUrl("/admin/tmm_actives/view",array("id"=>$data->id))',
							'options'=>array('style'=>'padding:0 8px 0 0;'),
							'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/audit.png',
					),
					'pack'=>array(
							'label'=>'包装',
							'visible'=>'$data->Actives_Shops->status==0 && $data->Actives_Shops->audit==Shops::audit_pass',
							'url'=>'Yii::app()->createUrl("/admin/tmm_actives/pack",array("id"=>$data->id))',
							'options'=>array('style'=>'padding:0 8px 0 0;'),
							'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/pack.png',
					),
					'disable'=>array(
							'label'=>'下线',
							'visible'=>' $data->Actives_Shops->status==1',
							'url'=>'Yii::app()->createUrl("/admin/tmm_actives/disable",array("id"=>$data->id))',
							'click'=>$click,
							'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/disable.png',
					),
					'start'=>array(
							'label'=>'上线',
							'visible'=>'$data->Actives_Shops->list_info != "" &&  $data->Actives_Shops->status==0 && $data->Actives_Shops->audit==Shops::audit_pass',
							'url'=>'Yii::app()->createUrl("/admin/tmm_actives/start",array("id"=>$data->id))',
							'click'=>$click,
							'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/enable.png',
					),
					'delete'=>array(
							'label'=>'删除',
							'options'=>array('style'=>'padding:0 0 0 8px;'),
							'visible'=>'$data->status == 0 && $data->Actives_Shops->status==Shops::status_offline',
							'click'=>$click,
					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:200px;'),
		),
	),
	'htmlOptions'=>array('style'=>'text-align:center;min-width:1500px;'),
)); 
?>
