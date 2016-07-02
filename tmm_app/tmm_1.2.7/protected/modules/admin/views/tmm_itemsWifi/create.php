<?php
/* @var $this Tmm_itemsWifiController */
/* @var $model ItemsWifi */

$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	'创建页',
);
?>

<h1>创建 ItemsWifi</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>