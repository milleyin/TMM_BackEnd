<?php
/* @var $this PasswordController */
/* @var $model Password */

$this->breadcrumbs = array(
	'管理页'=>array('admin'),
	$model->id,
);
?>

<h1>查看 Password <font color='#eb6100'><?php echo CHtml::encode($model->id); ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'id',
		),
		array(
				'name'=>'type',
				'value'=>$model::$_type[$model->type],
		),
		array(
				'name'=>'role_id',
		),
		array(
				'name'=>'manager_id',
		),
		array(
				'name'=>'password',
		),
		array(
				'name'=>'salt',
		),
		array(
				'name'=>'pass_count',
		),
		array(
				'name'=>'error_count',
		),
		array(
				'name'=>'use_error',
		),
		array(
				'name'=>'use_ip',
		),
		array(
				'name'=>'use_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'last_ip',
		),
		array(
				'name'=>'last_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'up_count',
		),
		array(
				'name'=>'up_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'add_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'status',
				'value'=>$model::$_status[$model->status],
		),
	),
)); 
?>