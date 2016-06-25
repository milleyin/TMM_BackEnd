<?php
/* @var $this Tmm_organizerController */
/* @var $model Organizer */

$this->breadcrumbs=array(
	'代理商管理页'=>array('admin'),
	$model->Organizer_User->phone=>array('view','id'=>$model->id),
	'代理商更新页',
);
?>
<h1>更新页代理商<font color='#eb6100'><?php echo CHtml::encode($model->Organizer_User->phone); ?></font></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>