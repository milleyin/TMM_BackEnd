<?php
/* @var $this Tmm_passwordController */
/* @var $model Password */

$this->breadcrumbs=array(
	'密码管理页'=>array('admin'),
	$model::getRoleName($model,$model->role_type)=>array('view','id'=>$model->id),
	'更新页',
);

?>

<h1>更新页 <font color='#eb6100'><?php echo CHtml::encode($model::getRoleName($model,$model->role_type)); ?></font></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>