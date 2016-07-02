<?php
/* @var $this Tmm_hotelController */
/* @var $model Hotel */

$this->breadcrumbs=array(
	'项目管理页'=>array('/operator/items/admin'),
	'创建项目（住）',
);
?>

<h1>创建项目（住）</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>