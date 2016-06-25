<?php
/* @var $this Tmm_depositLogController */
/* @var $model DepositLog */

$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	$model->id,
);
?>

<h1>查看 保证金记录 <font color='#eb6100'><?php echo $model->id; ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'id',
			),
		array(
				'name'=>'admin_id',
				'value'=>$model->DepositLog_Admin->username."[".$model->DepositLog_Admin->name."]",
			),
		array(
				'name'=>'deposit_who',
				'value'=>$model::$_deposit_who[$model->deposit_who],
			),
		array(
			'name'=>'deposit_id',
			'value'=>$model::show_type($model, $model->deposit_who),
		),
		array(
			'name'=>'deposit_status',
			'value'=>$model::$_deposit_status[$model->deposit_status],
		),
		array(
				'name'=>'deposit',
			),

		array(
				'name'=>'reason',
			),
		array(
				'name'=>'add_time',
				'type'=>'datetime',
			),
		array(
				'name'=>'up_time',
				'type'=>'datetime',
			),
	),
)); ?>