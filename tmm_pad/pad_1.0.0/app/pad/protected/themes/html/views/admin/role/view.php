<?php
/* @var $this RoleController */
/* @var $model Role */

$this->breadcrumbs = array(
	'管理页'=>array('admin'),
	$model->id,
);
?>

<h1>查看 Role <font color='#eb6100'><?php echo CHtml::encode($model->id); ?></font></h1>

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
				'name'=>'manager_id',
		),
		array(
				'name'=>'count',
		),
		array(
				'name'=>'error_count',
		),
		array(
				'name'=>'login_error',
		),
		array(
				'name'=>'login_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'login_ip',
		),
		array(
				'name'=>'last_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'last_ip',
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