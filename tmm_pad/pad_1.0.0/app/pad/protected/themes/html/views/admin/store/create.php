<?php
/* @var $this StoreController */
/* @var $model Store */

$this->breadcrumbs=array(
	'体验店管理页'=>array('admin'),
	'体验店创建页',
);
?>

<h1>创建体验店</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>