<?php
/* @var $this Tmm_tagsController */
/* @var $model Tags */

$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	$model->name=>array('view','id'=>$model->id),
	'更新页',
);

?>

<h1>更新标签 <font color='#eb6100'><?php echo CHtml::encode($model->name); ?></font></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>