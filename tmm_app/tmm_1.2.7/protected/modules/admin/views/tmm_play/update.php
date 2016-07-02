<?php
/* @var $this Tmm_playController */
/* @var $model Play */

$this->breadcrumbs=array(
	'项目(玩)管理页'=>array('admin'),
	$model->Play_Items->name=>array('view','id'=>$model->id),
	'更新页',
);

?>

<h1>更新页项目(玩) <font color='#eb6100'><?php echo CHtml::encode($model->Play_Items->name); ?></font></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>