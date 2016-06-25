<?php
/* @var $this Tmm_shopsController */
/* @var $model Shops */

$this->breadcrumbs=array(
	'内容管理页',
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
<h1>管理内容</h1>

<div>
	<span><?php echo CHtml::link('高级搜索','#',array('class'=>'search-button')); ?></span>
	<span>
		<?php echo CHtml::link('创建内容', array('/admin/tmm_agent/admin')); ?>
	</span>
		<?php 
			foreach ($this->admin_views as $key=>$value)
			{
		?>
	<span><?php echo CHtml::link($model->getAttributeLabel($key), array('admin','view'=>$key)); ?></span>
		<?php 
			}
		?>
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
	jQuery('#pub_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#add_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#up_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#tops_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#selected_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#selected_tops_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#hot_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));

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
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'agent_id',
				'value'=>'isset($data->Shops_Agent->phone) ? $data->Shops_Agent->phone : "无运营商"',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:120px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					isset($data->Shops_Agent->firm_name) ? ($data->getAttributeLabel("Shops_Agent.firm_name")."：".$data->Shops_Agent->firm_name) : "没有运营商"
			'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'c_id',
				'filter'=>Shops::$_shops,
				'value'=>'$data->Shops_ShopsClassliy->name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'					
					$data->getAttributeLabel("Shops_ShopsClassliy.info")."：".$data->Shops_ShopsClassliy->info				
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:300px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
						$data->getAttributeLabel("list_info")."：".$data->list_info . "\n" .
						$data->getAttributeLabel("page_info")."：".$data->page_info . "\n" .
						$data->getAttributeLabel("brow")."：".$data->brow . "\n" .	
						$data->getAttributeLabel("share")."：".$data->share . "\n" .
						$data->getAttributeLabel("tags_ids")."：".$data->tags_ids
				'),
		),
		array(
				'filter'=>$model::$_is_sale,
				'name'=>'is_sale',
				'value'=>'$data::$_is_sale[$data->is_sale]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>$model::$_tops,
				'name'=>'tops',
				'value'=>'$data::$_tops[$data->tops]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'		
						$data->getAttributeLabel("tops_time").":".
						($data->tops_time != 0 ? Yii::app()->format->FormatDateTime($data->tops_time) : "------")
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>$model::$_selected,
				'name'=>'selected',
				'value'=>'$data::$_selected[$data->selected]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
						$data->getAttributeLabel("selected_time")."：".
						($data->selected == $data::selected ? Yii::app()->format->FormatDateTime($data->selected_time) : "------") . "\n" .
						$data->getAttributeLabel("selected_info")."：".$data->selected_info
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>$model::$_selected_tops,
				'name'=>'selected_tops',
				'value'=>'$data::$_selected_tops[$data->selected_tops]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
						$data->getAttributeLabel("selected_tops_time").":".
						($data->selected_tops_time != 0 ? Yii::app()->format->FormatDateTime($data->selected_tops_time)	: "------")						
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>$model::$_hot,
				'name'=>'hot',
				'value'=>'$data::$_hot[$data->hot]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("hot_time").":".
					($data->hot_time != 0 ? Yii::app()->format->FormatDateTime($data->hot_time)	: "------")
			'),
		),
		array(
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>'zh-CN',
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
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
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
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>$model::$_audit,
				'name'=>'audit',
				'value'=>'$data::$_audit[$data->audit]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
						$data->getAttributeLabel("audit")."：".
						AuditLog::get_audit_log(Shops::$__audit[$data->c_id],$data->id)->info
				'),
		),
		array(
				'filter'=>$model::$_status,
				'name'=>'status',
				'value'=>'$data::$_status[$data->status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}{update}{selected}{hot}{hot_no}{remove}{tops}{tops_no}{selected_tops}{selected_tops_no}{tags}{audit}{confirm}{pack}{delete}{disable}{start}{sale_yes}{sale_no}',
			'buttons'=>array(
					'view'=>array(
						'label'=>'查看',
						'options'=>array('style'=>'padding:0 10px 0 0;'),
						'url'=>'Yii::app()->createUrl("/admin/tmm_".$data->Shops_ShopsClassliy->admin."/view",array("id"=>$data->id))',
					),
					'update'=>array(
							'label'=>'更新',
							'visible'=>'$data->status==0 && $data->audit != Shops::audit_pending',
							'url'=>'Yii::app()->createUrl("/admin/tmm_".$data->Shops_ShopsClassliy->admin."/update",array("id"=>$data->id))',
							'options'=>array('style'=>'padding:0 10px 0 0;'),
					),
					'hot'=>array(
						'label'=>'热门',
						'visible'=>'$data->status==1 && $data->c_id == Shops::shops_actives && $data->audit==Shops::audit_pass && $data->hot == Shops::hot_no',
						'url'=>'Yii::app()->createUrl("/admin/tmm_shops/hot",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 10px 0 0;'),
					),
					'hot_no'=>array(
							'label'=>'取消热门',
							'visible'=>'$data->c_id == Shops::shops_actives && $data->hot == Shops::hot_yes',
							'url'=>'Yii::app()->createUrl("/admin/tmm_shops/hot_no",array("id"=>$data->id))',
							'click'=>$click,
							'options'=>array('style'=>'padding:0 10px 0 0;'),
					),
					'selected'=>array(
						'label'=>'推荐',
						'visible'=>'$data->status==1 && $data->audit==Shops::audit_pass && $data->selected !=Shops::selected',
						'url'=>'Yii::app()->createUrl("/admin/tmm_shops/selected",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 10px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/online.png',
					),
					'remove'=>array(
						'label'=>'取消推荐',
						'visible'=>'$data->selected == Shops::selected',
						'url'=>'Yii::app()->createUrl("/admin/tmm_shops/remove",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 10px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/offline.png',
					),
					'tops'=>array(
						'label'=>'置顶',
						'visible'=>'$data->status==1 && $data->audit==Shops::audit_pass && $data->tops == Shops::tops_no',
						'url'=>'Yii::app()->createUrl("/admin/tmm_shops/tops",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 10px 0 0;'),
					),
					'tops_no'=>array(
						'label'=>'取消置顶',
						'visible'=>'$data->tops == Shops::tops_yes',
						'url'=>'Yii::app()->createUrl("/admin/tmm_shops/tops_no",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 10px 0 0;'),				
					),
					'selected_tops'=>array(
						'label'=>'推荐置顶',
						'visible'=>'$data->status==1 && $data->audit==Shops::audit_pass && $data->selected == Shops::selected && $data->selected_tops==Shops::selected_tops_no',
						'url'=>'Yii::app()->createUrl("/admin/tmm_shops/selected_tops",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 10px 0 0;'),		
					),
					'selected_tops_no'=>array(
						'label'=>'取消推荐置顶',
						'visible'=>'$data->selected_tops == Shops::selected_tops_yes',
						'url'=>'Yii::app()->createUrl("/admin/tmm_shops/selected_tops_no",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 10px 0 0;'),
					),
					'tags'=>array(
						'label'=>'标签',
						'visible'=>'$data->status==0 && $data->audit != Shops::audit_pending',
						'url'=>'Yii::app()->createUrl("/admin/tmm_".$data->Shops_ShopsClassliy->admin."/select",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 10px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/tag.png',
					),
					'audit'=>array(
						'label'=>'审核',
						'visible'=>'$data->status==0 && $data->audit==Shops::audit_pending',
						'url'=>'Yii::app()->createUrl("/admin/tmm_".$data->Shops_ShopsClassliy->admin."/view",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 10px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/audit.png',
					),
					'confirm'=>array(
						'label'=>'提交',
						'visible'=>'$data->status==0 && $data->audit==Shops::audit_draft',
						'url'=>'Yii::app()->createUrl("/admin/tmm_".$data->Shops_ShopsClassliy->admin."/confirm",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 10px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/submit.png',
					),
					'pack'=>array(
						'label'=>'包装',
						'visible'=>'$data->status==0 && $data->audit==Shops::audit_pass',
						'url'=>'Yii::app()->createUrl("/admin/tmm_".$data->Shops_ShopsClassliy->admin."/pack",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 10px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/pack.png',
					),
				  'delete'=>array(
					    'label'=>'删除',
						'visible'=>'$data->status==0 && $data->c_id != Shops::shops_actives',
						'options'=>array('style'=>'padding:0 10px 0 0;'),
				  		'url'=>'Yii::app()->createUrl("/admin/tmm_".$data->Shops_ShopsClassliy->admin."/delete",array("id"=>$data->id))',
					    'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/delete.png',
					),
					'disable'=>array(
						'label'=>'下线',
						'visible'=>'$data->status==1',
						'url'=>'Yii::app()->createUrl("/admin/tmm_".$data->Shops_ShopsClassliy->admin."/disable",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 10px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/disable.png',
					),
					'start'=>array(
						'label'=>'上线',
						'visible'=>'$data->status==0 && $data->audit==Shops::audit_pass',
						'url'=>'Yii::app()->createUrl("/admin/tmm_".$data->Shops_ShopsClassliy->admin."/start",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 10px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/enable.png',
					),
					'sale_yes'=>array(
						'label'=>'可卖',
						'visible'=>'$data->is_sale==0',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 10px 0 0;'),
						'url'=>'Yii::app()->createUrl("/admin/tmm_shops/sale_yes",array("id"=>$data->id))',
					),
					'sale_no'=>array(
						'label'=>'非卖',
						'visible'=>'$data->is_sale==1',
						'click'=>$click,
						'url'=>'Yii::app()->createUrl("/admin/tmm_shops/sale_no",array("id"=>$data->id))',
					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:450px;'),
		),
	),
	'htmlOptions'=>array('style'=>'text-align:center;min-width:1500px;'),
)); 
?>
