<?php
/* @var $this Tmm_selectController */
/* @var $model Select */

 $toModel = $model->getToName($model, true);

$this->breadcrumbs=array(
		'广告专题管理页'=>array('/admin/tmm_ad/admin'),
		$model->getToName($model)=>array('/admin/tmm_' . lcfirst(CHtml::modelName($toModel)) . '/view', 'id'=>$model->to_id),
		'内部广告管理页'=>array('admin'),
		$model->getSelectName($model),
);
?>

<h1>查看选中的 <font color='#eb6100'><?php echo CHtml::encode($model->getSelectName($model)) ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'id',
		),
		array(
				'name'=>'admin_id',
				'value'=>$model->Select_Admin->username . '[' . $model->Select_Admin->name . ']'
		),
		array(
				'name'=>'type',
				'value'=>$model::$_type[$model->type],
		),
		array(
				'name'=>'to_id',
				'value'=>$model->getToName($model)
		),
		array(
				'name'=>'select_id',
				'value'=>$model->getSelectName($model)
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