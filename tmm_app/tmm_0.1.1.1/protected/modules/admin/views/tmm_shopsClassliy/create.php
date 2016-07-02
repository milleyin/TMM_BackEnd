<?php
/* @var $this Tmm_shopsClassliyController */
/* @var $model ShopsClassliy */

$this->breadcrumbs=array(
	'商品分类管理页'=>array('admin'),
	'创建页',
);
?>

<h1>创建 商品分类</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>