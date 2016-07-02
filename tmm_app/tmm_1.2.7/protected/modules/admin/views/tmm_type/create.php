<?php
/* @var $this Tmm_typeController */
/* @var $model Type */

$this->breadcrumbs=array(
	'类型值管理页'=>array('admin'),
	'类型值创建页',
);
?>

<h1>创建 类型值</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>