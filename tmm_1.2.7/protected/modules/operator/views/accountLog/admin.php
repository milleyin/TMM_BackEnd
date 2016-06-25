<?php
/* @var $this AccountLogController */
/* @var $model AccountLog */

$this->breadcrumbs = array(
	'资金流水管理页',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#account-log-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>管理资金流水</h1>
<?php
Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
	jQuery('#add_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
}
");

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'account-log-grid',
	'dataProvider'=>$model->operatorSearch(),
	'enableHistory'=>true,
	'afterAjaxUpdate' => 'reinstallDatePicker',
	'filter'=>$model,
	'columns'=>array(
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'account_no',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("account_no").":".$data->account_no
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>AccountLog::$_to_account_type,
				'name'=>'to_account_type',
				'value'=>'$data::$_to_account_type[$data->to_account_type]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:120px;', 'title'=>'来自/去向账户类型'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("to_account_type")."：".$data::$_to_account_type[$data->to_account_type]
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'to_account_id',
				'value'=>'$data::getRoleName($data,$data->to_account_type,"to")',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;', 'title'=>'来自/去向账户'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("to_account_id") . "：" . $data::getRoleName($data,$data->to_account_type,"to")
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>$model::$_funds_type,
				'name'=>'funds_type',
				'value'=>'$data::$_funds_type[$data->funds_type]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("funds_type").":".$data::$_funds_type[$data->funds_type]
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
				$data->getAttributeLabel("name").":".$data->name
			'),
		),
// 		array(
// 				'class'=>'DataColumn',
// 				'evaluateHtmlOptions'=>true,
// 				//'filter'=>,
// 				'name'=>'money_type',
// 				'value'=>'$data::$_money_type[$data->money_type]',
// 				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
// 				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
// 					$data->getAttributeLabel("money_type").":".$data::$_money_type[$data->money_type]
// 				'),
// 		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'use_money',
				'value'=>'($data->centre_status == $data::centre_status_entry ? "+" :
								($data->centre_status == $data::centre_status_deduct ? "-" :
								($data->centre_status == $data::centre_status_pending ?
								($data->log_status !=$data::log_status_success ? "-":"")
								: "")))
								.$data->use_money',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:120px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
						$data->getAttributeLabel("use_money")."：".($data->centre_status == $data::centre_status_entry ? "+" :
								($data->centre_status == $data::centre_status_deduct ? "-" :
								($data->centre_status == $data::centre_status_pending ?
								($data->log_status !=$data::log_status_success ? "-":"")
								: "")))
								.$data->use_money
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'money',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:120px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("money").":".$data->money
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'after_money',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:120px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("after_money").":".$data->after_money
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>$model::$_log_status,
				'name'=>'log_status',
				'value'=>'$data::$_log_status[$data->log_status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("log_status").":".$data::$_log_status[$data->log_status]
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>$model::$_log_status,
				'name'=>'centre_status',
				'value'=>'$data::$_centre_status[$data->centre_status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("centre_status").":".$data::$_centre_status[$data->centre_status]
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>'zh-CN',
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
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
				$data->getAttributeLabel("add_time").":".Yii::app()->format->FormatDate($data->add_time)
			'),
		),
	),
)); 
?>
