<?php
/* @var $this Tmm_orderActivesController */
/* @var $model OrderActives */

$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	'创建页',
);
?>

<h1>创建 OrderActives</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>