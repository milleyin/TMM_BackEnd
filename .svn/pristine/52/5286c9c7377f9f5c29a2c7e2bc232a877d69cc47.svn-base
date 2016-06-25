<?php
/* @var $this MenuController */
/* @var $model Menu */

$this->breadcrumbs = array(
	'管理页'=>array('admin'),
	$model->name,
);
?>

<h1>查看 Menu <font color='#eb6100'><?php echo CHtml::encode($model->id); ?></font></h1>

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
				'name'=>'p_id',
		),
		array(
				'name'=>'manager_id',
		),
		array(
				'name'=>'name',
		),
		array(
				'name'=>'title',
		),
		array(
				'name'=>'info',
		),
		array(
				'name'=>'route',
		),
		array(
				'name'=>'sort',
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