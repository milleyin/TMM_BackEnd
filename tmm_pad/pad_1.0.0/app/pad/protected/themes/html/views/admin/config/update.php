<?php
/* @var $this ConfigController */
/* @var $model Config */

$this->breadcrumbs = array(
	'抽奖配置管理页'=>array('admin'),
	$model->Config_Pad->name=>array('view', 'id'=>$model->id),
	'更新抽奖配置页',
);
?>

<h1>更新抽奖配置 <font color='#eb6100'><?php echo CHtml::encode($model->Config_Pad->name); ?></font></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>