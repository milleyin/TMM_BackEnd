<?php
/* @var $this Tmm_userController */
/* @var $model User */

$this->breadcrumbs=array(
	'用户管理页'=>array('admin'),
	'用户创建页',
);
?>

<h1>创建 用户</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>