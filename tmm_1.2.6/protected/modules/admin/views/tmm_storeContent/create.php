<?php
/* @var $this Tmm_storeContentController */
/* @var $model StoreContent */

$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	'创建页',
);
?>

<h1>创建 StoreContent</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>