<?php
/* @var $this Tmm_itemsImgController */
/* @var $model ItemsImg */

$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	'创建页',
);
?>

<h1>创建 ItemsImg</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>