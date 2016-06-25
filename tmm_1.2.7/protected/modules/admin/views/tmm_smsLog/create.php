<?php
/* @var $this Tmm_smsLogController */
/* @var $model SmsLog */

$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	'创建页',
);
?>

<h1>创建 SmsLog</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>