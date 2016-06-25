<?php
/* @var $this Tmm_itemsClassliyController */
/* @var $model ItemsClassliy */

$this->breadcrumbs=array(
	'项目分类管理页'=>array('admin'),
	$model->name=>array('view','id'=>$model->id),
	'更新页',
);

?>

<h1>更新页项目分类 <font color='#eb6100'><?php echo CHtml::encode($model->name); ?></font></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>