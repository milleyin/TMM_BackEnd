<?php
/* @var $this Tmm_itemsClassliyController */
/* @var $model ItemsClassliy */

$this->breadcrumbs=array(
	'项目分类管理页'=>array('admin'),
	'创建项目分类页',
);
?>

<h1>创建 项目分类</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>