<?php
/* @var $this Tmm_wifiController */
/* @var $model Wifi */

$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	'创建页',
);
?>

<h1>创建 酒店服务</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>