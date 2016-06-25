<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs = array(
	'管理页'=>array('admin'),
	$model->name,
);
?>

<h1>查看 User <font color='#eb6100'><?php echo CHtml::encode($model->id); ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'id',
		),
		array(
				'name'=>'openid',
		),
		array(
				'name'=>'name',
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