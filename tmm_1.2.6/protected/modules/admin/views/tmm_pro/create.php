<?php
/* @var $this Tmm_proController */
/* @var $model Pro */

$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	'创建页',
);
?>

<h1>创建 Pro</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>