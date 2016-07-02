<?php
/* @var $this Tmm_tagsTypeController */
/* @var $model TagsType */

$this->breadcrumbs=array(
	'树形管理页'=>array('tree'),
	'管理页'=>array('admin'),
	'创建页',
);
?>

<h1>创建标签分类</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>