<?php
/* @var $this Tmm_agentController */
/* @var $model Agent */

$this->breadcrumbs=array(
	'运营商管理页'=>array('admin'),
	'创建运营商页',
);
?>

<h1>创建运营商</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>