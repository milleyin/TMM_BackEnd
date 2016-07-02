<?php
/* @var $this Tmm_loginLogController */
/* @var $model LoginLog */

$this->breadcrumbs=array(
	'登录日志管理页'=>array('admin'),
	'登录日志垃圾回收页',
);
?>
<h1>垃圾回收页 登录日志</h1>

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
	$.fn.yiiGridView.update('login-log-grid',{  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data){
	$.fn.yiiGridView.update('login-log-grid');  
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
	jQuery('#login-log-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#login-log-grid').yiiGridView('update');
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
	'id'=>'login-log-grid',
	'dataProvider'=>$model,
	'enableHistory'=>true,
	'columns'=>array(
			array(
					//'class'=>'DataColumn',
					//'evaluateHtmlOptions'=>true,
					//'filter'=>,
					'name'=>'id',
					'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
					'htmlOptions'=>array('style'=>'text-align:center;'),
			),
			array(
					//'class'=>'DataColumn',
					//'evaluateHtmlOptions'=>true,
					'name'=>'type',
					'value'=>'$data::$_type[$data->type]',
					'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
					'htmlOptions'=>array('style'=>'text-align:center;'),
			),
			array(
					//'class'=>'DataColumn',
					//'evaluateHtmlOptions'=>true,
					//'filter'=>,
					'name'=>'login_id',
					'value'=>'$data::getRoleName($data,$data->type)',
					'headerHtmlOptions'=>array('style'=>'text-align:center;width:200px;'),
					'htmlOptions'=>array('style'=>'text-align:center;'),
			),
			array(
					//'class'=>'DataColumn',
					//'evaluateHtmlOptions'=>true,
					'name'=>'login_time',
					'type'=>'datetime',
					'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
					'htmlOptions'=>array('style'=>'text-align:center;'),
			),
			array(
					//'class'=>'DataColumn',
					//'evaluateHtmlOptions'=>true,
					//'filter'=>,
					'name'=>'login_ip',
					'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
					'htmlOptions'=>array('style'=>'text-align:center;'),
			),
			array(
					'name'=>'login_source',
					'value' => '$data::$_login_source[$data->login_source]',
					'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
					'htmlOptions'=>array('style'=>'text-align:center;'),
			),
			array(
					//'class'=>'DataColumn',
					//'evaluateHtmlOptions'=>true,
					'name'=>'login_type',
					'value' => '$data::$_login_type[$data->login_type]',
					'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
					'htmlOptions'=>array('style'=>'text-align:center;'),
			),
			array(
					//'class'=>'DataColumn',
					//'evaluateHtmlOptions'=>true,
					//'filter'=>,
					'name'=>'login_error',
					'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
					'htmlOptions'=>array('style'=>'text-align:center;'),
			),
			array(
					//'class'=>'DataColumn',
					//'evaluateHtmlOptions'=>true,
					//'filter'=>,
					'name'=>'login_address',
					'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
					'htmlOptions'=>array('style'=>'text-align:center;'),
			),
			array(
					//'class'=>'DataColumn',
					//'evaluateHtmlOptions'=>true,
					'name'=>'add_time',
					'type'=>'datetime',
					'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
					'htmlOptions'=>array('style'=>'text-align:center;'),
			),
			array(
					//'class'=>'DataColumn',
					//'evaluateHtmlOptions'=>true,
					'name'=>'up_time',
					'type'=>'datetime',
					'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
					'htmlOptions'=>array('style'=>'text-align:center;'),
			),
			array(
					//'class'=>'DataColumn',
					//'evaluateHtmlOptions'=>true,
					'name'=>'status',
					'value'=>'$data::$_status[$data->status]',
					'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
					'htmlOptions'=>array('style'=>'text-align:center;'),
			),
			array(
					'class'=>'CButtonColumn',
					'header'=>'操 作',
					'template'=>'{view}',
					'buttons'=>array(
							'view'=>array(
									'label'=>'查看',
									'options'=>array('style'=>'padding:0 8px 0 0;'),
									'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/view.png',
							),
					),
					'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			),
	),
)); 
?>
