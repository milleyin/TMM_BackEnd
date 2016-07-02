<?php
/* @var $this Tmm_attendController */
/* @var $model Attend */

$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	'创建页',
);
?>

<h1>创建 Attend</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>