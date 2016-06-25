<?php
/* @var $this Tmm_accountController */
/* @var $model Account */

$this->breadcrumbs=array(
	'钱包列表管理页'=>array('admin'),
	$model::getAccountType($model,$model->account_type),
);
?>

<h1>查看 钱包列表 <font color='#eb6100'><?php echo $model::getAccountType($model,$model->account_type); ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'id',
		),
		array(
				'name'=>'account_type',
				'value'=>$model::$_account_type[$model->account_type],
		),
		array(
				'name'=>'account_id',
				'value'=>$model::getAccountType($model,$model->account_type),
		),
		array(
				'name'=>'money_type',
				'value'=>$model::$_money_type[$model->money_type],
		),
		array(
				'name'=>'count',
		),
		array(
				'name'=>'total',
		),
		array(
				'name'=>'money',
		),
		array(
				'name'=>'no_money',
		),
		array(
				'name'=>'cash_count',
				
				
		),
		array(
				'name'=>'pay_count',
		),
		array(
				'name'=>'refund_count',
		),
		array(
				'name'=>'consume_count',
		),
		array(
				'name'=>'not_consume_count',
		),
		array(
				'name'=>'add_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'up_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'up_count',
		),
		array(
				'name'=>'status',
				'value'=>$model::$_status[$model->status],
		),
	),
)); ?>