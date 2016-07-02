<?php
/* @var $this ZadmintypeController */
/* @var $model AdminType */

$this->breadcrumbs=array(
	'管理页'=>array('index'),
	$menu->name=>array('view','id'=>$menu->id),
	$title=isset($nav) ? '移动链接' : ($model->isNewRecord ? '创建链接' : '修改链接'),
);
?>
<h1><?php echo $title?></h1>
<?php 
	if(isset($nav))
		$this->renderPartial('_form', array('model'=>$model,'nav'=>$nav,'group'=>$group)); 
	else
		$this->renderPartial('_form', array('model'=>$model));
	?>