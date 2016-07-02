<?php
/* @var $this DotController */
/* @var $model Dot */

$this->breadcrumbs = array(
	'觅境管理页'=>array('/operator/shops/admin'),
	'创建景点页',
);
?>

<h1>创建景点</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'itemsModel'=>$itemsModel)); ?>