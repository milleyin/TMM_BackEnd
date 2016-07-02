<?php
/* @var $this Tmm_errorLogController */
/* @var $model ErrorLog */

$this->breadcrumbs=array(
	'错误日志管理查看页'=>array('admin'),
    $model::show_type($model, $model->manage_who),
);
?>

<h1>错误日志管理页<font color='#eb6100'><?php echo CHtml::encode($model::show_type($model, $model->manage_who)); ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'id',
		),
		array(
				'name'=>'error_id',
                'value'=>$model::show_type($model, $model->manage_who),
		),
		array(
				'name'=>'manage_who',
                'value'=>$model::$_manage_who[$model->manage_who],
		),
		array(
				'name'=>'manage_type',
                'value'=>$model::$_manage_type[$model->manage_type],
		),
		array(
				'name'=>'error_type',
                'value'=>$model::$_error_type[$model->error_type],
		),
		array(
				'name'=>'manage_method',
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