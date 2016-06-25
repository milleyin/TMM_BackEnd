<?php
/* @var $this Tmm_wifiController */
/* @var $model Wifi */

$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	$model->name=>array('view','id'=>$model->id),
	'更新页',
);

?>

<h1>更新页 酒店服务<font color='#eb6100'><?php echo CHtml::encode($model->name); ?></font></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>