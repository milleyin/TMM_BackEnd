<?php
/* @var $this Tmm_bankCardController */
/* @var $model BankCard */

$this->breadcrumbs=array(
	'银行卡管理页'=>array('/admin/tmm_bankCard/admin'),
	'银行卡日志',
);
?>
<h1>银行卡日志</h1>

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
	$.fn.yiiGridView.update('bank-card-grid',{  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data){
	$.fn.yiiGridView.update('bank-card-grid');  
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
	jQuery('#bank-card-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#bank-card-grid').yiiGridView('update');
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
	'id'=>'bank-card-grid',
	'dataProvider'=>$model,
	'enableHistory'=>true,
	'columns'=>array(
		array(
				'name'=>'id',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'manage_who',
				'value'=>'$data::$_manage_who[$data->manage_who]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'manage_id',
				'value'=>'$data::getManageRoleName($data,$data->manage_who)',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'card_type',
				'value'=>'$data::$_card_type[$data->card_type]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'card_id',
				'value'=>'$data::getRoleName($data,$data->manage_who)',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'bank_id',
				'value'=>'$data->BankCard_Bank->name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				//'class'=>'DataColumn',
				//'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'bank_name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				//'class'=>'DataColumn',
				//'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'bank_branch',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'bank_code',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
//		array(
//				'filter'=>$model::$_bank_type,
//				'name'=>'bank_type',
//				'value'=>'$data::$_bank_type[$data->bank_type]',
//				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
//				'htmlOptions'=>array('style'=>'text-align:center;'),
//		),
//		array(
//				'filter'=>$model::$_is_default,
//				'name'=>'is_default',
//				'value'=>'$data::$_is_default[$data->is_default]',
//				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
//				'htmlOptions'=>array('style'=>'text-align:center;'),
//		),
//		array(
//				'filter'=>$model::$_is_verify,
//				'name'=>'is_verify',
//				'value'=>'$data::$_is_verify[$data->is_verify]',
//				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
//				'htmlOptions'=>array('style'=>'text-align:center;'),
//		),
		array(
				'name'=>'add_time',
				'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'up_time',
				'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'status',
				'value'=>'$data::$_status[$data->status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}',
			'buttons'=>array(
					'view'=>array(
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
		),
	),
)); 
?>
