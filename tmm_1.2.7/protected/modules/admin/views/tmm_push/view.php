<?php
/* @var $this Tmm_pushController */
/* @var $model Push */

$this->breadcrumbs=array(
	'分成管理页'=>array('admin'),
	$model->Push_Items->name,
);
?>

<h1>查看 分成 <font color='#eb6100'><?php echo CHtml::encode($model->Push_Items->name); ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'id',
			),
		array(
				'name'=>'manage_id',
				'value'=>$model->Push_Admin->name,
			),
		array(
				'name'=>'manage_who',
				'value'=>$model::$_manage_who[$model->manage_who],
			),
		array(
				'name'=>'push_id',
				'value'=>$model->Push_Items->name,
			),
		array(
				'name'=>'push_element',
				'value'=>$model::$_push_element[$model->push_element],
			),
		array(
				'name'=>'push',
			),
		array(
				'name'=>'push_orgainzer',
		),
		array(
				'name'=>'push_store',
		),
		array(
				'name'=>'push_agent',
		),
		array(
				'name'=>'start_time',
				'type'=>'datetime',
			),
		array(
				'name'=>'info',
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