<?php
/* @var $this Tmm_storeController */
/* @var $model StoreUser */

$this->breadcrumbs=array(
	'供应商账号管理页'=>array('admin'),
	$model->phone=>array('view','id'=>$model->phone),
	'更新供应商帐号',
);
?>

<h1>更新供应商帐号信息 <font color='#eb6100'><?php echo CHtml::encode($model->phone); ?></font></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>