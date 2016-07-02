<?php
/* @var $this AreaController */
/* @var $model Area */

$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	'创建页',
);
?>

<h1>创建 Area</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>