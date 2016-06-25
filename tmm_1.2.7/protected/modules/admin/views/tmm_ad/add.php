<?php
/* @var $this Tmm_adController */
/* @var $model Ad */

$this->breadcrumbs=array(
	'广告专题管理页'=>array('admin'),
	$model->Ad_Ad->name=>array('view','id'=>$model->Ad_Ad->id),
	'广告管理页'=>array('manage', 'id'=>$model->Ad_Ad->id),	
	'广告创建页',
);
?>

<h1>创建 <font color='#eb6100'><?php echo CHtml::encode($model->Ad_Ad->name); ?></font> 广告</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>