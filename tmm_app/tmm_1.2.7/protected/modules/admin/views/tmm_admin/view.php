<?php
/* @var $this AdminController */
/* @var $model Admin */

$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	$model->name,
);
?>

<h1>查看 管理员 <font color='#eb6100'><?php echo CHtml::encode($model->name); ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'id',
		),
		array(
				'name'=>'username',
		),
		array(
				'name'=>'d_id',
				'value'=>$model::$_d_id[$model->d_id]
		),
		array(
				'name'=>'admin_id',
				'value'=>$model->Admin_Admin->username.' ['.$model->Admin_Admin->name.']'
		),
		array(
				'name'=>'name',
			),
		array(
				'name'=>'phone',
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
				'name'=>'last_ip',
			),
		array(
				'name'=>'last_time',
				'type'=>'datetime',
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
				'name'=>'status',
				'value'=>$model::$_status[$model->status],
			),
	),
)); ?>
