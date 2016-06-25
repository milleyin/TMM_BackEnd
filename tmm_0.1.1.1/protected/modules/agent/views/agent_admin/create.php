<?php
/* @var $this Agent_adminController */
/* @var $model Agent */

$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	'创建页',
);
?>

<h1>创建 Agent</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>