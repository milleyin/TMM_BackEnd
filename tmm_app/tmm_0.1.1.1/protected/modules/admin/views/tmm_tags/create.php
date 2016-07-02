<?php
/* @var $this Tmm_tagsController */
/* @var $model Tags */

$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	'创建页',
);
?>

<h1>创建标签</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>