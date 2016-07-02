<?php
/* @var $this Tmm_softwareController */
/* @var $model Software */

$this->breadcrumbs=array(
	'软件更新包管理页'=>array('admin'),
	'创建页',
);
?>

<h1>创建 软件更新包</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>