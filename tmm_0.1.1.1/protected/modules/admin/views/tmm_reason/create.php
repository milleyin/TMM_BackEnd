<?php
/* @var $this Tmm_reasonController */
/* @var $model Reason */

$this->breadcrumbs=array(
	'售后原因管理页'=>array('admin'),
	'售后原因创建页',
);
?>

<h1>创建售后原因</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>