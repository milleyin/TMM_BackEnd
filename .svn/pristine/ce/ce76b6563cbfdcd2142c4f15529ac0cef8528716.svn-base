<?php
/* @var $this WifiController */
/* @var $model Wifi */

$this->breadcrumbs = array(
	'酒店服务管理页'=>array('admin'),
	$model->name,
);
?>

<h1>查看酒店服务 <font color='#eb6100'><?php echo CHtml::encode($model->name) ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'id',
		),
		array(
				'name'=>'name',
		),
		array(
				'name'=>'info',
		),
		array(
				'name'=>'icon',
				'type'=>'raw',
				'value'=>$this->show_img($model->icon),
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
));
?>
