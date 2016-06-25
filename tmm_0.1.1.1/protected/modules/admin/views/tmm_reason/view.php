<?php
/* @var $this Tmm_reasonController */
/* @var $model Reason */

$this->breadcrumbs=array(
	'售后原因管理页'=>array('admin'),
	$model->name,
);
?>

<h1>查看售后原因 <font color='#eb6100'><?php echo CHtml::encode($model->name) ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'id',
		),
		array(
			'name'=>'admin_id',
			'value'=>$model->Reason_Admin->username . ' [' .$model->Reason_Admin->name . ']',
		),
		array(
			'name'=>'role_type',
			'value'=>$model::$_role_type[$model->role_type],
		),
		array(
			'name'=>'order_type',
			'value'=>$model::$_order_type[$model->order_type],
		),
		array(
			'name'=>'sale_type',
			'value'=>$model::$_sale_type[$model->sale_type],
		),
		array(
			'name'=>'cargo_type',
			'value'=>$model::$_cargo_type[$model->cargo_type],
		),
		array(
			'name'=>'name',
		),
		array(
			'name'=>'reason',
		),
		array(
			'name'=>'remark',
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