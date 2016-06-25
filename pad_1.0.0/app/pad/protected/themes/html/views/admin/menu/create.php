<?php
/* @var $this MenuController */
/* @var $model Menu */

$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	'创建页',
);
?>

<h1>创建 Menu</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>