<?php
/* @var $this Tmm_tagsController */
/* @var $model Tags */

$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	$model->name,
);
?>
<h1>查看 标签 <font color='#eb6100'><?php echo CHtml::encode($model->name); ?></font></h1>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'id',
		),
		array(
				'name'=>'admin_id',
				'value'=>$model->Tags_Admin->username.' ['.$model->Tags_Admin->name.']',
		),
		array(
				'name'=>'name',
		),
		array(
				'name'=>'weight',
		),
		array(
				'name'=>'sort',
		),
		array(
				'name'=>'status',
				'value'=>$model::$_status[$model->status],
		),
		array(
				'name'=>'add_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'up_time',
				'type'=>'datetime',
		),
	),
)); ?>
