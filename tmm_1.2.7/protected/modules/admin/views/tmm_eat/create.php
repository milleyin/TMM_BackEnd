<?php
/* @var $this Tmm_eatController */
/* @var $model Eat */

$this->breadcrumbs=array(
	'项目(吃)管理页'=>array('admin'),
	'创建页',
);
?>

<h1>创建 项目(吃)</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>