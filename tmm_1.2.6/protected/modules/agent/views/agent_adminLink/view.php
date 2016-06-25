<?php
/* @var $this Tmm_adminLinkController */
/* @var $model AdminLink */

$this->breadcrumbs=array(
	'管理页'=>array('index'),
	$model->name,
);
?>
<h1>后台管理链接: <font color='#eb6100'><?php echo $model->name; ?></font></h1>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'p_id',
		'name',
		'title',
		'info',
		'url',
		'params',
		array(
			'name'=>'target',
			'value'=>$model::$_target[$model->target]
		),
		'sort',
		array(
			'name'=>'add_time',
			'type'=>'datetime',
		),
		array(
				'name'=>'up_time',
				'type'=>'datetime',
		),
		'show',
		array(
				'name'=>'status',
				'value'=>$model::$_status[$model->status]
		),
	),
)); ?>
