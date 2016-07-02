<?php
/* @var $this UploadController */
/* @var $model Upload */

$this->breadcrumbs = array(
	'上传资源管理页'=>array('admin'),
	$model->info ? $model->info : $model->id=>array('view', 'id'=>$model->id),
	'上传资源更新页',
);
?>

<h1>更新上传资源管 <font color='#eb6100'><?php echo CHtml::encode($model->info ? $model->info : $model->id); ?></font></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>