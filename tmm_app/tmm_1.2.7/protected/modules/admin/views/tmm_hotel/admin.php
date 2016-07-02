<?php
/* @var $this Tmm_hotelController */
/* @var $model Hotel */

$this->breadcrumbs=array(
	'项目管理页'=>array('/admin/tmm_items/admin'),
	'项目(住)管理页',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#hotel-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>管理页 项目(住)</h1>

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
	$.fn.yiiGridView.update('hotel-grid',{  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data){
	$.fn.yiiGridView.update('hotel-grid');  
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
	jQuery('#hotel-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#hotel-grid').yiiGridView('update');
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
	'id'=>'hotel-grid',
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
				'name'=>'Hotel_Items.name',
				'filter'=>CHtml::activeTextField($model->Hotel_Items, 'name', array('id'=>false)),
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>CHtml::activeTextField($model->Hotel_Items, 'agent_id', array('id'=>false)),
				'name'=>'Hotel_Items.agent_id',
				'value'=>'$data->Hotel_Items->Items_agent->phone',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Hotel_Items.Items_agent.firm_name").":".$data->Hotel_Items->Items_agent->firm_name'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>CHtml::activeTextField($model->Hotel_Items, 'store_id', array('id'=>false)),
				'name'=>'Hotel_Items.store_id',
				'value'=>'$data->Hotel_Items->Items_StoreContent->Content_Store->phone',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Hotel_Items.Items_StoreContent.name").":".$data->Hotel_Items->Items_StoreContent->name'),
		),
		array(
				'filter'=>CHtml::activeTextField($model->Hotel_Items, 'manager_id', array('id'=>false)),
				'name'=>'Hotel_Items.manager_id',
				'value'=>'$data->Hotel_Items->Items_Store_Manager->phone',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>CHtml::activeDropDownList($model->Hotel_Items, 'area_id_p',Area::data_array_name('',array(''=>'')), array('id'=>false)),
				'name'=>'Hotel_Items.area_id_p',
				'value'=>'(isset($data->Hotel_Items->area_id_p) && $data->Hotel_Items->area_id_p != 0 )? $data->Hotel_Items->Items_area_id_p_Area_id->name:"未选择"',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>CHtml::activeDropDownList($model->Hotel_Items, 'area_id_m',Area::data_array_name($model->Hotel_Items->area_id_p,array(''=>''),false), array('id'=>false)),
				'name'=>'Hotel_Items.area_id_m',
				'value'=>'(isset($data->Hotel_Items->area_id_m) && $data->Hotel_Items->area_id_m != 0 )? $data->Hotel_Items->Items_area_id_m_Area_id->name:"未选择"',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>CHtml::activeDropDownList($model->Hotel_Items, 'area_id_c',Area::data_array_name($model->Hotel_Items->area_id_m,array(''=>''),false), array('id'=>false)),
				'name'=>'Hotel_Items.area_id_c',
				'value'=>'(isset($data->Hotel_Items->area_id_c) && $data->Hotel_Items->area_id_c != 0 )? $data->Hotel_Items->Items_area_id_c_Area_id->name:"未选择"',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Hotel_Items.address").":".$data->Hotel_Items->address'),
		),
		array(
				'filter'=>CHtml::activeTextField($model->Hotel_Items, 'down', array('id'=>false)),
				'name'=>'Hotel_Items.down',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>CHtml::activeTextField($model->Hotel_Items, 'push', array('id'=>false)),
				'name'=>'Hotel_Items.push',
				'value'=>'Push::executed($data->id,"push",$data->Hotel_Items->push)."%"',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("Hotel_Items.push").":".Push::executed($data->id,"push",$data->Hotel_Items->push)."%\n".
          			$data->getAttributeLabel("Hotel_Items.push_agent").":".Push::executed($data->id,"push_agent",$data->Hotel_Items->push_agent)."%\n".
            		$data->getAttributeLabel("Hotel_Items.push_store").":".Push::executed($data->id,"push_store",$data->Hotel_Items->push_store)."%\n".
            		$data->getAttributeLabel("Hotel_Items.push_orgainzer").":".Push::executed($data->id,"push_orgainzer",$data->Hotel_Items->push_orgainzer)."%\n"
				'),
		),
		array(
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>Yii::app()->language,
						'model'=>$model->Hotel_Items,
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
				'name'=>'Hotel_Items.pub_time',
				'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>Yii::app()->language,
						'model'=>$model->Hotel_Items,
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
				'name'=>'Hotel_Items.add_time',
				'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Hotel_Items.up_time").":".Yii::app()->format->datetime($data->Hotel_Items->up_time)'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>CHtml::activeDropDownList($model->Hotel_Items, 'audit',array(''=>'')+Items::$_audit, array('id'=>false)),
				'name'=>'Hotel_Items.audit',
				'value'=>'Items::$_audit[$data->Hotel_Items->audit]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->Hotel_Items->audit == Items::audit_nopass ? "未通过原因：".AuditLog::get_audit_log(Items::$__audit[$data->Hotel_Items->c_id],$data->Hotel_Items->id)->info : ""
				'),
		),
		array(
				'filter'=>CHtml::activeDropDownList($model->Hotel_Items, 'free_status',array(''=>'')+Items::$_free_status, array('id'=>false)),
				'name'=>'Hotel_Items.free_status',
				'value'=>'Items::$_free_status[$data->Hotel_Items->free_status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'filter'=>CHtml::activeDropDownList($model->Hotel_Items, 'status',array(''=>'')+Items::$_status, array('id'=>false)),
				'name'=>'Hotel_Items.status',
				'value'=>'Items::$_status[$data->Hotel_Items->status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}{tags}{wifi}{update}{push}{confirm}{audit}{delete}{disable}{start}',
			'buttons'=>array(
					'view'=>array(
						'label'=>'查看',
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/view.png',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
					'push'=>array(
							'label'=>'设置定时分成',
							'visible'=>'$data->Hotel_Items->is_push==Items::push_init && $data->Hotel_Items->audit != Items::audit_pending',
							'url'=>'Yii::app()->createUrl("/admin/tmm_items/push",array("id"=>$data->id))',
							'options'=>array('style'=>'padding:0 8px 0 0;'),
							'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/scale.png',
					),
					'tags'=>array(
						'label'=>'标签设置',
						'visible'=>'$data->Hotel_Items->status==0 && $data->Hotel_Items->audit != Items::audit_pending',
						'url'=>'Yii::app()->createUrl("/admin/tmm_hotel/select",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/tag.png',
					),
					'wifi'=>array(
							'label'=>'酒店服务',
							'visible'=>'$data->Hotel_Items->status==0 && $data->Hotel_Items->audit != Items::audit_pending',
							'url'=>'Yii::app()->createUrl("/admin/tmm_hotel/wifi",array("id"=>$data->id))',
							'options'=>array('style'=>'padding:0 8px 0 0;'),
							'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/wifi.png',								
					),		
					'update'=>array(
						'label'=>'更新',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/update.png',
						'visible'=>'$data->Hotel_Items->status==0 && $data->Hotel_Items->audit != Items::audit_pending',							
					),
					'confirm'=>array(
							'label'=>'提交审核',
							'visible'=>'$data->Hotel_Items->status==0 && $data->Hotel_Items->audit==Items::audit_draft',
							'url'=>'Yii::app()->createUrl("/admin/tmm_hotel/confirm",array("id"=>$data->id))',
							'click'=>$click,
							'options'=>array('style'=>'padding:0 8px 0 0;'),
							'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/submit.png',
					),
					'audit'=>array(
							'label'=>'审核',
							'visible'=>'$data->Hotel_Items->status==0 && $data->Hotel_Items->audit==Items::audit_pending',
							'url'=>'Yii::app()->createUrl("/admin/tmm_hotel/view",array("id"=>$data->id))',
							'options'=>array('style'=>'padding:0 8px 0 0;'),
							'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/audit.png',
					),
					'delete'=>array(
						'label'=>'删除',
						'visible'=>'$data->Hotel_Items->status==0',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/delete.png',
					),
					'disable'=>array(
						'label'=>'下线',
						'visible'=>'$data->Hotel_Items->status==1',
						'url'=>'Yii::app()->createUrl("/admin/tmm_hotel/disable",array("id"=>$data->id))',
						'click'=>$click,
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/offline.png',
					),
					'start'=>array(
						'label'=>'上线',
						'visible'=>'$data->Hotel_Items->status==0 && $data->Hotel_Items->audit==Items::audit_pass',
						'url'=>'Yii::app()->createUrl("/admin/tmm_hotel/start",array("id"=>$data->id))',
						'click'=>$click,
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/online.png',
					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
		),
	),
)); 
?>

