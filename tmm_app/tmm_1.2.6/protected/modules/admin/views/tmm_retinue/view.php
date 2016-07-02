<?php
/* @var $this Tmm_retinueController */
/* @var $model Retinue */

$this->breadcrumbs=array(
	'随行人员管理页'=>array('admin'),
	$model->name,
);
?>

<h1>查看 随行人员 <font color='#eb6100'><?php echo CHtml::encode($model->name); ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'id',
		),
		array(
				'name'=>'user_id',
				'value'=>$model->Retinue_User->phone,
		),
		array(
				'name'=>'name',
		),
		array(
				'name'=>'gender',
				'value'=>$model::$_gender[$model->gender],
		),
		array(
				'name'=>'identity',
		),
		array(
				'name'=>'phone',
		),
		array(
				'name'=>'email',
		),
		array(
				'name'=>'is_main',
				'value'=>$model::$_is_main[$model->is_main],
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