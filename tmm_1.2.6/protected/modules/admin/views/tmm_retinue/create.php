<?php
/* @var $this Tmm_retinueController */
/* @var $model Retinue */

$this->breadcrumbs=array(
	'用户管理页'=>array('/admin/tmm_user/admin'),
	'随行人员管理页'=>array('admin'),
	$model->Retinue_User->phone=>array('/admin/tmm_user/view','id'=>$model->Retinue_User->id),
	'创建随行人员页',
);
?>

<h1>创建随行人员页</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>