<?php
/* @var $this ZadmintypeController */
/* @var $model AdminType */

$this->breadcrumbs=array(
	'管理页'=>array('index'),
	$group->name=>array('view','id'=>$group->id),
	$title=(isset($nav) ? '移动分组' : ($model->isNewRecord ? '创建分组' : '修改分组')),
);
?>
<h1><?php echo $title?></h1>
<?php 
	if(isset($nav))
		$this->renderPartial('_form', array('model'=>$model,'nav'=>$nav));
	else
		$this->renderPartial('_form', array('model'=>$model));
?>