<?php
/* @var $this Tmm_manageLogController */
/* @var $model ManageLog */

$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	$model->id,
);
?>

<h1>查看 操作日志 <font color='#eb6100'><?php echo $model->id; ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'id',
			),
		array(
				'name'=>'manage_id',
				'value'=>$model::show_type($model, $model->manage_who),
			),
		array(
				'name'=>'manage_who',
				'value'=>$model::$_manage_who[$model->manage_who],
			),
		array(
				'name'=>'manage_type',
			),
		array(
				'name'=>'info',
			),
		array(
				'name'=>'url',
			),
		array(
				'name'=>'ip',
			),
		array(
				'name'=>'address',
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
