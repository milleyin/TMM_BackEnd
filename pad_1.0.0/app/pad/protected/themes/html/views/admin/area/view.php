<?php
/* @var $this AreaController */
/* @var $model Area */

$this->breadcrumbs = array(
	'管理页'=>array('admin'),
	$model->name,
);
?>

<h1>查看 Area <font color='#eb6100'><?php echo CHtml::encode($model->id); ?></font></h1>

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
				'name'=>'spell',
		),
		array(
				'name'=>'pid',
		),
		array(
				'name'=>'sort',
		),
		array(
				'name'=>'status',
				'value'=>$model::$_status[$model->status],
		),
	),
)); 
?>