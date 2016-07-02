<?php
/* @var $this AdminController */
/* @var $model Admin */

$this->breadcrumbs=array(
		'管理页'=>array('admin'),
	'垃圾回收页',
);
?>
<h1>垃圾回收页 Admins</h1>

<?php
  $Confirmation= "你确定执行此项操作？";
	if(Yii::app()->request->enableCsrfValidation)
	{
		$csrfTokenName = Yii::app()->request->csrfTokenName;
		$csrfToken = Yii::app()->request->csrfToken;
		$csrf = "\n\t\tdata:{ '$csrfTokenName':'$csrfToken'},";
	}else
		$csrf = '';
$click_alert=<<<EOD
function(){ 
	if(!confirm("$Confirmation")) return false; 
	var th=this;  
	var afterDelete=function(link,success,data){ if(success) alert(data);};  
	$.fn.yiiGridView.update('admin-grid',{  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data){
	$.fn.yiiGridView.update('admin-grid');  
	   afterDelete(th,true,data);  
	},
	error:function(XHR){
	   return afterDelete(th,false,XHR);
	}
  });
    return false;
}
EOD;

$click=<<<EOD
function() {  
	if(!confirm("$Confirmation")) return false;
	var th = this,
	afterDelete = function(){};
	jQuery('#admin-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#admin-grid').yiiGridView('update');
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
	'id'=>'admin-grid',
	'dataProvider'=>$model,
	'enableHistory'=>true,
	'columns'=>array(
		array(
				'name'=>'id',
				'htmlOptions'=>array('style'=>'text-align:center;width:20px;'),
		),
		array(
				'name'=>'username',
				'htmlOptions'=>array('style'=>'text-align:center;width:100px;'),
		),
		array(
				'name'=>'name',
				'htmlOptions'=>array('style'=>'text-align:center;width:50px;'),
		),
		array(
				'name'=>'d_id',
				'value'=>'$data::$_d_id[$data->d_id]',
				'htmlOptions'=>array('style'=>'text-align:center;width:50px;'),
		),
		array(
				'name'=>'admin_id',
				'value'=>'$data->Admin_Admin->username."[ ".$data->Admin_Admin->name." ]"',
				'htmlOptions'=>array('style'=>'text-align:center;width:120px;'),
		),
		array(
				'name'=>'phone',
				'htmlOptions'=>array('style'=>'text-align:center;width:80px;'),
		),
		array(
				'name'=>'count',
				'htmlOptions'=>array('style'=>'text-align:center;width:50px;'),
		),
		array(
				'name'=>'error_count',
				'htmlOptions'=>array('style'=>'text-align:center;width:50px;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'login_ip',
				'htmlOptions'=>array('style'=>'text-align:center;width:80px;','title'=>'$data->last_ip'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'login_time',
				'type'=>'datetime',
				'htmlOptions'=>array('style'=>'text-align:center;width:80px;','title'=>'Yii::app()->format->datetime($data->last_time)'),
		),
		array(
				'name'=>'add_time',
				'type'=>'datetime',
				'htmlOptions'=>array('style'=>'text-align:center;width:80px;'),
		),
		array(
				'name'=>'up_time',
				'type'=>'datetime',
				'htmlOptions'=>array('style'=>'text-align:center;width:80px;'),
		),
		array(
				'name'=>'status',
				'value'=>'$data::$_status[$data->status]',
				'htmlOptions'=>array('style'=>'text-align:center;width:50px;'),
		),
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}{restore}{delete}',
			'buttons'=>array(
					'view'=>array(
						'options'=>array('style'=>'padding:0 20px 0 0;'),
					),
					'restore'=>array(
						'label'=>'还原',
						'url'=>'Yii::app()->createUrl("/admin/tmm_admin/restore",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 20px 0 0;'),
					),
					'delete'=>array(
						'label'=>'彻底删除',
						'options'=>array('style'=>'padding:0 10px 0 0;'),
						'url'=>'Yii::app()->createUrl("/admin/tmm_admin/clear",array("id"=>$data->id))',			
					),
			),
			'htmlOptions'=>array('style'=>'text-align:center;width:100px;'),
		),
	),
)); 
?>
