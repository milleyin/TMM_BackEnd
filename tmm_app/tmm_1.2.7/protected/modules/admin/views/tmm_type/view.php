<?php
/* @var $this Tmm_typeController */
/* @var $model Type */

$this->breadcrumbs=array(
	'类型值管理页'=>array('admin'),
	$model->name,
);
?>

<h1>查看 类型值 <font color='#eb6100'><?php echo CHtml::encode($model->name) ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'id',
		),
		array(
			'name'=>'role_type',
			'value'=>$model::$_role_type[$model->role_type]
		),
		array(
			'name'=>'role_id',
			'value'=>$model->Type_Admin->username . '[' . $model->Type_Admin->name . ']'
		),
		array(
			'name'=>'type',
			'value'=>$model::$_type[$model->type]
		),
		array(
			'name'=>'name',
		),
		array(
			'name'=>'value',
		),
		array(
			'name'=>'info',
		),
		array(
			'name'=>'options',
		),
		array(
			'name'=>'sort',
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
