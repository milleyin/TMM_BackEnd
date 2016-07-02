<?php
/* @var $this Tmm_hotelController */
/* @var $model Hotel */

$this->breadcrumbs=array(
	'项目管理页'=>array('/admin/tmm_items/admin'),
	'项目(住)管理页'=>array('admin'),
	'创建页',
);
?>

<h1>创建 项目(住)</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>