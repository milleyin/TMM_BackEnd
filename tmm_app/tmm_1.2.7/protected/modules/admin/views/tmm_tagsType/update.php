<?php
/* @var $this Tmm_tagsTypeController */
/* @var $model TagsType */

$this->breadcrumbs=array(
	'树形管理页'=>array('tree'),
	'管理页'=>array('admin'),
	$model->name=>array('view','id'=>$model->id),
	'更新页',
);

?>
<h1>更新页标签分类 <font color='#eb6100'><?php echo CHtml::encode($model->name); ?></font></h1>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>