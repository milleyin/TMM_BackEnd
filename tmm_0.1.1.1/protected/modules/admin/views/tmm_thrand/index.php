<?php
/* @var $this Tmm_thrandController */
/* @var $model Thrand */

$this->breadcrumbs=array(
	'线路管理页'=>array('/admin/tmm_thrand/admin'),
	'线路(线)垃圾回收页',
);
?>
<h1>垃圾回收页 线路(线)</h1>

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
	$.fn.yiiGridView.update('thrand-grid',{  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data){
	$.fn.yiiGridView.update('thrand-grid');  
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
	jQuery('#thrand-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#thrand-grid').yiiGridView('update');
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
	'id'=>'thrand-grid',
	'dataProvider'=>$model,
	'enableHistory'=>true,
	'columns'=>array(
		array(
				'name'=>'id',
	   			'headerHtmlOptions'=>array('style'=>'text-align:center;width:20px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'name'=>'Thrand_Shops.name',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),array(
			'class'=>'DataColumn',
			'evaluateHtmlOptions'=>true,
			'name'=>'Thrand_Shops.agent_id',
			'value'=>'$data->Thrand_Shops->Shops_Agent->phone',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Thrand_Shops.Shops_Agent.firm_name").":".$data->Thrand_Shops->Shops_Agent->firm_name'),
		),
		array(
			'name'=>'Thrand_Shops.brow',
			'value'=>'$data->Thrand_Shops->brow',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'name'=>'Thrand_Shops.share',
			'value'=>'$data->Thrand_Shops->share',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'name'=>'Thrand_Shops.praise',
			'value'=>'$data->Thrand_Shops->praise',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'name'=>'Thrand_Shops.pub_time',
			'type'=>'datetime',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'DataColumn',
			'evaluateHtmlOptions'=>true,
			'name'=>'Thrand_Shops.add_time',
			'type'=>'datetime',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;','$data->getAttributeLabel("Thrand_Shops.up_time").":".Yii::app()->format->datetime($data->Thrand_Shops->up_time)'),
		),
		array(
			'name'=>'Thrand_Shops.audit',
			'value'=>'Items::$_audit[$data->Thrand_Shops->audit]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'name'=>'Thrand_Shops.status',
			'value'=>'Items::$_status[$data->Thrand_Shops->status]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}{restore}',
			'buttons'=>array(
					'view'=>array(
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
					'restore'=>array(
						'label'=>'还原',
						//'visible'=>'$data->status==1',
						'url'=>'Yii::app()->createUrl("/admin/tmm_thrand/restore",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/delete.png',
					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
		),
	),
)); 
?>
