<?php
/* @var $this Tmm_auditLogController */
/* @var $model AuditLog */

$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	$model->id,
);
?>

<h1>查看 审核日志 <font color='#eb6100'><?php echo $model->id; ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'id',
			),
		array(
			'name'=>'audit_who',
			'value'=>$model::$_audit_who[$model->audit_who],
		),
		array(
				'name'=>'audit_id',
				'value'=>$model::show_who_type($model, $model->audit_who),
			),
		array(
				'name'=>'audit_element',
				'value'=>$model::$_audit_element[$model->audit_element],
			),
		array(
				'name'=>'element_id',
				'value'=>$model::show_element_type($model, $model->audit_element),
			),
		array(
				'name'=>'audit',
				'value'=>$model::$_audit[$model->audit],
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
	),
)); ?>
