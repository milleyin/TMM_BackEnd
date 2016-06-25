<?php
/* @var $this Tmm_playController */
/* @var $model Play */

$this->breadcrumbs=array(
	'项目管理页'=>array('/admin/tmm_items/admin'),
	'项目(玩)管理页',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#play-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>管理页 项目(玩)</h1>

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
	$.fn.yiiGridView.update('play-grid',{
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data){
	$.fn.yiiGridView.update('play-grid');
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
	jQuery('#play-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#play-grid').yiiGridView('update');
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
	'id'=>'play-grid',
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
			'filter'=>CHtml::activeTextField($model->Play_Items, 'name', array('id'=>false)),
			'name'=>'Play_Items.name',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'DataColumn',
			'evaluateHtmlOptions'=>true,
			'filter'=>CHtml::activeTextField($model->Play_Items, 'agent_id', array('id'=>false)),
			'name'=>'Play_Items.agent_id',
			'value'=>'$data->Play_Items->Items_agent->phone',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Play_Items.Items_agent.firm_name").":".$data->Play_Items->Items_agent->firm_name'),
		),
		array(
			'class'=>'DataColumn',
			'evaluateHtmlOptions'=>true,
			'filter'=>CHtml::activeTextField($model->Play_Items, 'store_id', array('id'=>false)),
			'name'=>'Play_Items.store_id',
			'value'=>'$data->Play_Items->Items_StoreContent->Content_Store->phone',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Play_Items.Items_StoreContent.name").":".$data->Play_Items->Items_StoreContent->name'),
		),
		array(
			'filter'=>CHtml::activeTextField($model->Play_Items, 'manager_id', array('id'=>false)),
			'name'=>'Play_Items.manager_id',
			'value'=>'$data->Play_Items->Items_Store_Manager->phone',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'DataColumn',
			'evaluateHtmlOptions'=>true,
			'filter'=>CHtml::activeDropDownList($model->Play_Items, 'area_id_p', Area::data_array_name('',array(''=>'')),array('id'=>false)),
			'name'=>'Play_Items.area_id_p',
			'value'=>'(isset($data->Play_Items->area_id_p) && $data->Play_Items->area_id_p != 0 )? $data->Play_Items->Items_area_id_p_Area_id->name:"未选择"',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'DataColumn',
			'evaluateHtmlOptions'=>true,
			'filter'=>CHtml::activeDropDownList($model->Play_Items, 'area_id_m',Area::data_array_name($model->Play_Items->area_id_p,array(''=>''),false),array('id'=>false)),
			'name'=>'Play_Items.area_id_m',
			'value'=>'(isset($data->Play_Items->area_id_m) && $data->Play_Items->area_id_m != 0 )? $data->Play_Items->Items_area_id_m_Area_id->name:"未选择"',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'DataColumn',
			'evaluateHtmlOptions'=>true,
			'filter'=>CHtml::activeDropDownList($model->Play_Items, 'area_id_c',Area::data_array_name($model->Play_Items->area_id_m,array(''=>''),false),array('id'=>false)),
			'name'=>'Play_Items.area_id_c',
			'value'=>'(isset($data->Play_Items->area_id_c) && $data->Play_Items->area_id_c != 0 )? $data->Play_Items->Items_area_id_c_Area_id->name:"未选择"',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Play_Items.address").":".$data->Play_Items->address'),
		),
		array(
			'filter'=>CHtml::activeTextField($model->Play_Items, 'down', array('id'=>false)),
			'name'=>'Play_Items.down',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
			'filter'=>CHtml::activeTextField($model->Play_Items, 'push', array('id'=>false)),
			'name'=>'Play_Items.push',
				'value'=>'Push::executed($data->id,"push",$data->Play_Items->push)."%"',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("Play_Items.push").":".Push::executed($data->id,"push",$data->Play_Items->push)."%\n".
          			$data->getAttributeLabel("Play_Items.push_agent").":".Push::executed($data->id,"push_agent",$data->Play_Items->push_agent)."%\n".
            		$data->getAttributeLabel("Play_Items.push_store").":".Push::executed($data->id,"push_store",$data->Play_Items->push_store)."%\n".
            		$data->getAttributeLabel("Play_Items.push_orgainzer").":".Push::executed($data->id,"push_orgainzer",$data->Play_Items->push_orgainzer)."%\n"
				'),
		),
		array(
			'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
				'language'=>Yii::app()->language,
				'model'=>$model->Play_Items,
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
			'name'=>'Play_Items.pub_time',
			'type'=>'datetime',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'DataColumn',
			'evaluateHtmlOptions'=>true,
			'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
				'language'=>Yii::app()->language,
				'model'=>$model->Play_Items,
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
			'name'=>'Play_Items.add_time',
			'type'=>'datetime',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Play_Items.up_time").":".Yii::app()->format->datetime($data->Play_Items->up_time)'),
		),
		/*
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
        */
		array(
			'class'=>'DataColumn',
			'evaluateHtmlOptions'=>true,
			'filter'=>CHtml::activeDropDownList($model->Play_Items, 'audit',array(''=>'')+Items::$_audit, array('id'=>false)),
			'name'=>'Play_Items.audit',
			'value'=>'Items::$_audit[$data->Play_Items->audit]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->Play_Items->audit == Items::audit_nopass ? "未通过原因：".AuditLog::get_audit_log(Items::$__audit[$data->Play_Items->c_id],$data->Play_Items->id)->info : ""
			'),
		),
		array(
				'filter'=>CHtml::activeDropDownList($model->Play_Items, 'free_status',array(''=>'')+Items::$_free_status, array('id'=>false)),
				'name'=>'Play_Items.free_status',
				'value'=>'Items::$_free_status[$data->Play_Items->free_status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'filter'=>CHtml::activeDropDownList($model->Play_Items, 'status',array(''=>'')+Items::$_status, array('id'=>false)),
			'name'=>'Play_Items.status',
			'value'=>'Items::$_status[$data->Play_Items->status]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}{tags_type}{update}{push}{confirm}{audit}{delete}{disable}{start}',
			'buttons'=>array(
					'view'=>array(
						'label'=>'查看',
						'options'=>array('style'=>'padding:0 15px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/view.png',
					),
					'push'=>array(
						'label'=>'分成',
						'visible'=>'$data->Play_Items->is_push==Items::push_init && $data->Play_Items->audit != Items::audit_pending',
						'url'=>'Yii::app()->createUrl("/admin/tmm_items/push",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/scale.png',
					),
					'tags_type'=>array(
						'label'=>'标签',
						'visible'=>'$data->Play_Items->status==0 && $data->Play_Items->audit != Items::audit_pending',
						'url'=>'Yii::app()->createUrl("/admin/tmm_play/select",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/tag.png',
					),
					'update'=>array(
						'label'=>'更新',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'visible'=>'$data->Play_Items->status==0 && $data->Play_Items->audit != Items::audit_pending',
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/update.png',
					),
					'confirm'=>array(
						'label'=>'提交',
						'visible'=>'$data->Play_Items->status==0 && $data->Play_Items->audit==Items::audit_draft',
						'url'=>'Yii::app()->createUrl("/admin/tmm_play/confirm",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/submit.png',
					),
					'audit'=>array(
						'label'=>'审核',
						'visible'=>'$data->Play_Items->status==0 && $data->Play_Items->audit==Items::audit_pending',
						'url'=>'Yii::app()->createUrl("/admin/tmm_play/view",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/audit.png',
					),
					'delete'=>array(
						'label'=>'删除',
						'visible'=>'$data->Play_Items->status==0',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/delete.png',
					),
					'disable'=>array(
						'label'=>'下线',
						'visible'=>'$data->Play_Items->status==1',
						'url'=>'Yii::app()->createUrl("/admin/tmm_play/disable",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/disable.png',
					),
					'start'=>array(
						'label'=>'上线',
						'visible'=>'$data->Play_Items->status==0 && $data->Play_Items->audit==Items::audit_pass',
						'url'=>'Yii::app()->createUrl("/admin/tmm_play/start",array("id"=>$data->id))',
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