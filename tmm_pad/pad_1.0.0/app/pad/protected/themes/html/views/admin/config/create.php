<?php
/* @var $this ConfigController */
/* @var $model Config */

$this->breadcrumbs=array(
	'抽奖配置管理页'=>array('admin'),
	'创建抽奖配置页',
);
?>

<h1>创建抽奖配置</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>