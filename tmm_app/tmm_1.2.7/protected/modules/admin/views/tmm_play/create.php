<?php
/* @var $this Tmm_playController */
/* @var $model Play */

$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	'创建项目(玩)',
);
?>

<h1>创建项目(玩)</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>