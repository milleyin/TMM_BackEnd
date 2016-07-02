<?php
/* @var $this Tmm_storeController */
/* @var $model StoreUser */

$this->breadcrumbs=array(
	'供应商账号管理页'=>array('admin'),
	$model->Store_Store->phone=>array('view','id'=>$model->Store_Store->id),
	'创建供应商子账号信息',
);
?>

<h1>创建供应商子账号信息</h1>

<?php $this->renderPartial('_formson', array('model'=>$model)); ?>