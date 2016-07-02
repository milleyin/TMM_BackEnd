<?php
/* @var $this Tmm_userController */
/* @var $model User */

$this->breadcrumbs=array(
	'用户管理页'=>array('admin'),
	$model->phone=>array('view','id'=>$model->id),
	'用户更新页',
);
?>
<h1>更新 用户<font color='#eb6100'><?php echo $model->phone; ?></font></h1>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>