<?php
/* @var $this Tmm_loginLogController */
/* @var $model LoginLog */

$this->breadcrumbs=array(
	'登录日志管理页'=>array('admin'),
	$model::getRoleName($model, $model->type),
);
?>

<h1>查看 登录日志 <font color='#eb6100'><?php echo CHtml::encode($model::getRoleName($model, $model->type)); ?></font></h1>

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
				'name'=>'login_id',
				'value'=>$model::getRoleName($model, $model->type)
		),
		array(
				'name'=>'login_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'login_ip',
		),
		array(
				'name'=>'login_address',
		),
		array(
				'name'=>'login_source',
				'value'=>$model::$_login_source[$model->login_source]
		),
		array(
				'name'=>'login_type',
				'value'=>$model::$_login_type[$model->login_type]
		),
		array(
				'name'=>'login_error',
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
				'value'=>$model::$_status[$model->status]
		),
	),
)); ?>