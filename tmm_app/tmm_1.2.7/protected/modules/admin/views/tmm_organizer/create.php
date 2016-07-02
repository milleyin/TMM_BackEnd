<?php
/* @var $this Tmm_organizerController */
/* @var $model Organizer */

$this->breadcrumbs=array(
	'用户管理页'=>array('/admin/tmm_user/admin'),
	$model->Organizer_User->phone=>array('/admin/tmm_user/view','id'=>$model->Organizer_User->id),
	'申请代理商',
);
?>
<h1>申请代理商 <font color='#eb6100'><?php echo CHtml::encode($model->Organizer_User->phone); ?></font></h1>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>