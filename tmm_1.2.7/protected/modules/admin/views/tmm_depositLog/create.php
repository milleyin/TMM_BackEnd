<?php
/* @var $this Tmm_depositLogController */
/* @var $model DepositLog */

$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	'创建页',
);
?>

<h1>创建 DepositLog</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>