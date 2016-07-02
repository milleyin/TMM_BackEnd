<?php
/* @var $this Tmm_eatController */
/* @var $model Eat */

$this->breadcrumbs=array(
	'项目(吃)管理页'=>array('admin'),
	$model->Eat_Items->name=>array('view','id'=>$model->id),
	'更新页',
);

?>

<h1>更新页项目(吃)  <font color='#eb6100'><?php echo CHtml::encode($model->Eat_Items->name); ?></font></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>