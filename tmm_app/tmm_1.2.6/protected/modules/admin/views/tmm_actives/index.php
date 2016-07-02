<?php
/* @var $this Tmm_activesController */
/* @var $model Actives */

$this->breadcrumbs=array(
	'垃圾回收页',
);
?>
<h1>垃圾回收页 线路(旅游活动)</h1>

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

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'actives-grid',
	'dataProvider'=>$model,
	'enableHistory'=>true,
	'columns'=>array(
		array(
			//'class'=>'DataColumn',
			//'evaluateHtmlOptions'=>true,
			//'filter'=>,
			'name'=>'id',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			//'class'=>'DataColumn',
			//'evaluateHtmlOptions'=>true,
			'name'=>'actives_type',
			'value'=>'$data::$_actives_type[$data->actives_type]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'name'=>'organizer_id',
			'value'=>'$data->Actives_User->phone',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'name'=>'is_organizer',
			'value'=>'$data::$_is_organizer[$data->is_organizer]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
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
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),

		array(
			//'class'=>'DataColumn',
			//'evaluateHtmlOptions'=>true,
			//'filter'=>,
			'name'=>'order_count',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			//'class'=>'DataColumn',
			//'evaluateHtmlOptions'=>true,
			//'filter'=>,
			'name'=>'push',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			//'class'=>'DataColumn',
			//'evaluateHtmlOptions'=>true,
			//'filter'=>,
			'name'=>'push_orgainzer',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			//'class'=>'DataColumn',
			//'evaluateHtmlOptions'=>true,
			//'filter'=>,
			'name'=>'push_store',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			//'class'=>'DataColumn',
			//'evaluateHtmlOptions'=>true,
			//'filter'=>,
			'name'=>'push_agent',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
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
			//'class'=>'DataColumn',
			//'evaluateHtmlOptions'=>true,
			//'filter'=>,
			'name'=>'number',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
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
			'name'=>'start_time',
			'type'=>'datetime',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			//'class'=>'DataColumn',
			//'evaluateHtmlOptions'=>true,
			'name'=>'end_time',
			'type'=>'datetime',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			//'class'=>'DataColumn',
			//'evaluateHtmlOptions'=>true,
			'name'=>'pub_time',
			'type'=>'datetime',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			//'class'=>'DataColumn',
			//'evaluateHtmlOptions'=>true,
			'name'=>'go_time',
			'type'=>'datetime',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			//'class'=>'DataColumn',
			//'evaluateHtmlOptions'=>true,
			//'filter'=>,
			'name'=>'actives_status',
			'value'=>'$data::$_actives_status[$data->actives_status]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'name'=>'Actives_Shops.audit',
			'value'=>'Shops::$_audit[$data->Actives_Shops->audit]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			//'class'=>'DataColumn',
			//'evaluateHtmlOptions'=>true,
			//'filter'=>,
			'name'=>'status',
			'value'=>'$data::$_status[$data->status]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		/**/
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}{restore}',
			'buttons'=>array(
					'view'=>array(
						'label'=>'查看',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
					'restore'=>array(
						'label'=>'还原',
						//'visible'=>'$data->status==1',
						'url'=>'Yii::app()->createUrl("/admin/tmm_actives/restore",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
		),
	),
)); 
?>
