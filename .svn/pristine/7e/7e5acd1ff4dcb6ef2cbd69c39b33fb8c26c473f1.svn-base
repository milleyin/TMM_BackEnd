<?php
/* @var $this Tmm_storeController */
/* @var $model StoreUser */

$this->breadcrumbs=array(
	'供应商子账号管理页'=>array('son'),
	$model->phone,
);
?>

<h1>查看 供应商子账号 <font color='#eb6100'><?php echo CHtml::encode($model->phone); ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'id',
		),
		array(
				'name'=>'phone',
		),
		array(
				'name'=>'p_id',
				'value'=>$model->Store_Store->phone,
		),
		array(
			'name'=>'Store_Store.Store_Content.name',
		),
		array(
				'name'=>'agent_id',
				'value'=>$model->Store_Agent->phone,
		),
		array(
				'name'=>'count',
		),
		array(
				'name'=>'login_error',
		),
		array(
				'name'=>'error_count',
		),
		array(
				'name'=>'login_ip',
		),
		array(
				'name'=>'login_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'login_address',
		),
		array(
				'name'=>'last_ip',
		),
		array(
				'name'=>'last_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'last_address',
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
				'name'=>'out_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'status',
				'value'=>$model::$_status[$model->status],
			),
	),
)); ?>

