<?php
/* @var $this Tmm_itemsController */
/* @var $model Items */

$this->breadcrumbs=array(
	'项目管理页',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#items-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>项目管理页</h1>

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
	$.fn.yiiGridView.update('items-grid',{  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data){
	$.fn.yiiGridView.update('items-grid');  
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
	jQuery('#items-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#items-grid').yiiGridView('update');
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
	
}
");

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'items-grid',
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
				'filter'=>ItemsClassliy::data('name',array()),
				'name'=>'c_id',
				'value'=>'$data->Items_ItemsClassliy->name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:30px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Items_ItemsClassliy.info").":".$data->Items_ItemsClassliy->info'),
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
				'value'=>'$data->Items_agent->phone',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Items_agent.firm_name").":".$data->Items_agent->firm_name'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'store_id',
                'value'=>'$data->Items_StoreContent->Content_Store->phone',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Items_StoreContent.name").":".$data->Items_StoreContent->name'),
		),
		array(
				'name'=>'manager_id',
				'value'=>'$data->Items_Store_Manager->phone',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>Area::data_array_name('',array()),
                'name'=>'area_id_p',
                'value'=>'(isset($data->area_id_p) && $data->area_id_p != 0 )? $data->Items_area_id_p_Area_id->name:"未选择"',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
                'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>Area::data_array_name($model->area_id_p,array(),false),
                'name'=>'area_id_m',
                'value'=>'(isset($data->area_id_m) && $data->area_id_m != 0 )? $data->Items_area_id_m_Area_id->name:"未选择"',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
                'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
                'filter'=>Area::data_array_name($model->area_id_m,array(),false),
                'name'=>'area_id_c',
                'value'=>'(isset($data->area_id_c) && $data->area_id_c != 0 )? $data->Items_area_id_c_Area_id->name:"未选择"',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
                'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("address").":".$data->address'),
		),
        array(
            'name'=>'down',
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:30px;'),
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
        	'class'=>'DataColumn',
        	'evaluateHtmlOptions'=>true,
            'name'=>'push',
        	'value'=>'Push::executed($data->id,"push",$data->push)."%"',
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
            'htmlOptions'=>array('style'=>'text-align:center;','title'=>'	
						$data->getAttributeLabel("push").":".Push::executed($data->id,"push",$data->push)."%\n".
          				$data->getAttributeLabel("push_agent").":".Push::executed($data->id,"push_agent",$data->push_agent)."%\n".
            			$data->getAttributeLabel("push_store").":".Push::executed($data->id,"push_store",$data->push_store)."%\n".
            			$data->getAttributeLabel("push_orgainzer").":".Push::executed($data->id,"push_orgainzer",$data->push_orgainzer)."%\n"
				'),
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
	        	'class'=>'DataColumn',
	        	'evaluateHtmlOptions'=>true,
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
	            'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("up_time").":".Yii::app()->format->datetime($data->up_time)'),
        ),
        array(
        		'class'=>'DataColumn',
        		'evaluateHtmlOptions'=>true,
                'filter'=>$model::$_audit,
                'name'=>'audit',
                'value'=>'$data::$_audit[$data->audit]',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
                'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
                	$data->audit == $data::audit_nopass ? "未通过原因：".AuditLog::get_audit_log($data::$__audit[$data->c_id],$data->id)->info : ""
	             '),
		),
		array(
				'filter'=>$model::$_free_status,
				'name'=>'free_status',
				'value'=>'$data::$_free_status[$data->free_status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
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
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}{push}{tags}{wifi}{update}{confirm}{audit}{delete}{disable}{start}',
			'buttons'=>array(
					'view'=>array(
						'label'=>'查看',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'url'=>'Yii::app()->createUrl("/admin/tmm_".$data->Items_ItemsClassliy->admin."/view",array("id"=>$data->id))',
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/view.png',
					),
					'push'=>array(
						'label'=>'分成',
						'visible'=>'$data->audit != Items::audit_pending && $data->is_push==Items::push_init',
						'url'=>'Yii::app()->createUrl("/admin/tmm_items/push",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/scale.png',
					),
					'tags'=>array(
						'label'=>'标签',
						'visible'=>'$data->status==0 && $data->audit != Items::audit_pending',
						'url'=>'Yii::app()->createUrl("/admin/tmm_".$data->Items_ItemsClassliy->admin."/select",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/tag.png',
					),
					'wifi'=>array(
						'label'=>'酒店服务',
						'visible'=>'$data->Items_ItemsClassliy->append=="Hotel" && $data->status==0 && $data->audit != Items::audit_pending',
						'url'=>'Yii::app()->createUrl("/admin/tmm_hotel/wifi",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/wifi.png',
					),		
					'update'=>array(
						'label'=>'更新',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'visible'=>'$data->status==0 && $data->audit != Items::audit_pending',							
						'url'=>'Yii::app()->createUrl("/admin/tmm_".$data->Items_ItemsClassliy->admin."/update",array("id"=>$data->id))',
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/update.png',
					),
					'confirm'=>array(
						'label'=>'提交',
						'visible'=>'$data->status==0 && $data->audit==Items::audit_draft',
						'url'=>'Yii::app()->createUrl("/admin/tmm_".$data->Items_ItemsClassliy->admin."/confirm",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/submit.png',
					),
					'audit'=>array(
						'label'=>'审核',
						'visible'=>'$data->status==0 && $data->audit==Items::audit_pending',
						'url'=>'Yii::app()->createUrl("/admin/tmm_".$data->Items_ItemsClassliy->admin."/view",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/audit.png',
					),
					'delete'=>array(
						'label'=>'删除',
						'visible'=>'$data->status==0',
						'url'=>'Yii::app()->createUrl("/admin/tmm_".$data->Items_ItemsClassliy->admin."/delete",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/delete.png',
					),
					'disable'=>array(
						'label'=>'下线',
						'visible'=>'$data->status==1',
						'url'=>'Yii::app()->createUrl("/admin/tmm_".$data->Items_ItemsClassliy->admin."/disable",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/disable.png',
					),
					'start'=>array(
						'label'=>'上线',
						'visible'=>'$data->status==0 && $data->audit==Items::audit_pass',
						'url'=>'Yii::app()->createUrl("/admin/tmm_".$data->Items_ItemsClassliy->admin."/start",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/enable.png',
					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:140px;'),
		),
	),
)); 
?>
