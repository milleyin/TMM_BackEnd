<?php
/* @var $this Tmm_retinueController */
/* @var $model Retinue */

$this->breadcrumbs=array(
	'随行人员管理页'=>array('admin'),
	$model->Retinue_User->phone=>array('/admin/tmm_user/view','id'=>$model->Retinue_User->id),
	$model->name=>array('view','id'=>$model->id),
	'随行人员更新页',
);

?>

<h1>随行人员更新页 <font color='#eb6100'><?php echo CHtml::encode($model->name); ?></font></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>