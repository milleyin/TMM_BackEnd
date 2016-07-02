<?php
/* @var $this Tmm_thrandController */
/* @var $model Thrand */
$this->breadcrumbs=array(
	'运营商管理页'=>array('/admin/tmm_agent/admin'),
	$model->Thrand_Shops->Shops_Agent->phone=>array('/admin/tmm_agent/view','id'=>$model->Thrand_Shops->Shops_Agent->id),
	'线路创建页',
);
?>
<h1>创建线路</h1>
<?php $this->renderPartial('_form', array('model'=>$model, 'dotModel'=>$dotModel)); ?>