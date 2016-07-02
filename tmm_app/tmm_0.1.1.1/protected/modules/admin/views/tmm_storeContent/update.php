<?php
/* @var $this Tmm_storeContentController */
/* @var $model StoreContent */

$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	$model->name=>array('view','id'=>$model->id),
	'更新页',
);

?>

<h1>更新页StoreContent <font color='#eb6100'><?php echo CHtml::encode($model->name); ?></font></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>