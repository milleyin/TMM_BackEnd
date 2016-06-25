<?php
/* @var $this Tmm_storeController */
/* @var $model StoreUser */

$this->breadcrumbs=array(
	'运营商管理页'=>array('/admin/tmm_agent/admin'),
	$model->Store_Agent->phone=>array('/admin/tmm_agent/view','id'=>$model->Store_Agent->id),
	'创建供应商账号',
);
?>

<h1>创建供应商账号信息<font color='#eb6100'>运营商：<?php echo CHtml::encode($model->Store_Agent->phone); ?></font></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>