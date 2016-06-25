<?php
/* @var $this PasswordController */
/* @var $model Password */

$this->breadcrumbs = array(
	'管理页'=>array('admin'),
	$model->id=>array('view', 'id'=>$model->id),
	'更新页',
);
?>

<h1>更新页 Password<font color='#eb6100'><?php echo CHtml::encode($model->id); ?></font></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>