<?php
/* @var $this Tmm_shopsController */
/* @var $model Shops */

$this->breadcrumbs=array(
	'内容管理页'=>array('admin'),
	'垃圾回收页',
);
?>
<h1>内容垃圾回收</h1>

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

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'shops-grid',
	'dataProvider'=>$model,
	'enableHistory'=>true,
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
				'name'=>'is_sale',
				'value'=>'$data::$_is_sale[$data->is_sale]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
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
				'name'=>'hot',
				'value'=>'$data::$_hot[$data->hot]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("hot_time").":".
					($data->hot_time != 0 ? Yii::app()->format->FormatDateTime($data->hot_time)	: "------")
			'),
		),
		array(
				'name'=>'pub_time',
				'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'add_time',
				'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'up_time',
				'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'audit',
				'value'=>'$data::$_audit[$data->audit]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
						$data->getAttributeLabel("audit")."：".
						AuditLog::get_audit_log(Shops::$__audit[$data->c_id],$data->id)->info
				'),
		),
		array(
				'name'=>'status',
				'value'=>'$data::$_status[$data->status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}{restore}',
			'buttons'=>array(
					'view'=>array(
						'label'=>'查看',
						'options'=>array('style'=>'padding:0 15px 0 0;'),
						'url'=>'Yii::app()->createUrl("/admin/tmm_".$data->Shops_ShopsClassliy->admin."/view",array("id"=>$data->id))',
					),
					'restore'=>array(
						'label'=>'还原',
						'url'=>'Yii::app()->createUrl("/admin/tmm_".$data->Shops_ShopsClassliy->admin."/restore",array("id"=>$data->id))',
						'click'=>$click,
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/restore.png',
					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
		),
	),
)); 
?>
