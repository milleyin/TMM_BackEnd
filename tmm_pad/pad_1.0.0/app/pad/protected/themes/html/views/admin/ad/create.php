<?php
/* @var $this AdController */
/* @var $model Ad */

$this->breadcrumbs=array(
	'广告管理页'=>array('admin'),
	'创建广告页',
);
?>

<h1>创建广告</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>