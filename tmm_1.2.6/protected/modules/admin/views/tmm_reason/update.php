<?php
/* @var $this Tmm_reasonController */
/* @var $model Reason */

$this->breadcrumbs=array(
	'售后原因管理页'=>array('admin'),
	$model->name=>array('view','id'=>$model->id),
	'售后原因更新页',
);

?>

<h1>更新页售后原因 <font color='#eb6100'><?php echo CHtml::encode($model->name); ?></font></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>