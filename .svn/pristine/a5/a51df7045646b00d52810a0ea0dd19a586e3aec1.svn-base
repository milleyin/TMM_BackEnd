<?php
/* @var $this AdminController */
/* @var $model Admin */

$this->breadcrumbs = array(
	'管理页'=>array('admin'),
	$model->name,
);
?>

<h1>查看 Admin <font color='#eb6100'><?php echo CHtml::encode($model->id); ?></font></h1>

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
				'name'=>'phone',
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