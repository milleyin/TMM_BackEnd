<?php
/* @var $this Tmm_refundController */
/* @var $model Refund */

$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	'创建页',
);
?>

<h1>创建 Refund</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>