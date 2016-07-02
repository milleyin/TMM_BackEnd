<?php
/* @var $this Agent_adminController */
/* @var $model Agent */

$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	$model->id=>array('view','id'=>$model->id),
	'更新页',
);

?>

<h1>更新页Agent <font color='#eb6100'><?php echo $model->id; ?></font></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>