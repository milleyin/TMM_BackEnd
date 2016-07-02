<?php
/* @var $this StoreController */
/* @var $model Store */

$this->breadcrumbs = array(
	'体验店管理页'=>array('admin'),
	$model->store_name=>array('view', 'id'=>$model->id),
	'体验店更新页',
);
?>

<h1>更新体验店 <font color='#eb6100'><?php echo CHtml::encode($model->store_name); ?></font></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>