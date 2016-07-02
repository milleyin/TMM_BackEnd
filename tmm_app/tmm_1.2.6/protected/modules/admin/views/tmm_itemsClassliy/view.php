<?php
/* @var $this Tmm_itemsClassliyController */
/* @var $model ItemsClassliy */

$this->breadcrumbs=array(
	'项目分类管理页'=>array('admin'),
	$model->name,
);
?>

<h1>查看 项目分类 <font color='#eb6100'><?php echo CHtml::encode($model->name); ?></font></h1>

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
				'name'=>'admin',
		),
		array(
				'name'=>'main',
		),
		array(
				'name'=>'append',
		),
		array(
				'name'=>'nexus',
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

