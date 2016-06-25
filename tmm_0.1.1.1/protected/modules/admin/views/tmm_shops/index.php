<?php
/* @var $this Tmm_shopsController */
/* @var $model Shops */

$this->breadcrumbs=array(
	'垃圾回收页',
);
?>
<h1>垃圾回收页 线路</h1>

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
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:20px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'c_id',
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
				'name'=>'pub_time',
				'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'add_time',
				'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'up_time',
				'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'audit',
				'value'=>'$data::$_audit[$data->audit]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'status',
				'value'=>'$data::$_status[$data->status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}{restore}',
			'buttons'=>array(
					'view'=>array(
						'label'=>'查看',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'url'=>'Yii::app()->createUrl("/admin/tmm_".$data->Shops_ShopsClassliy->admin."/view",array("id"=>$data->id))',
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/view.png',
					),
					'restore'=>array(
						'label'=>'还原',
						'url'=>'Yii::app()->createUrl("/admin/tmm_".$data->Shops_ShopsClassliy->admin."/restore",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/restore.png',
					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
		),
	),
)); 
?>
