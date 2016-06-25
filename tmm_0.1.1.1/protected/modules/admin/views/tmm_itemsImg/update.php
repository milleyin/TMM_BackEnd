<?php
/* @var $this Tmm_itemsImgController */
/* @var $model ItemsImg */

$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	$model->title=>array('view','id'=>$model->id),
	'更新页',
);

?>

<h1>更新页ItemsImg <font color='#eb6100'><?php echo CHtml::encode($model->title); ?></font></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>