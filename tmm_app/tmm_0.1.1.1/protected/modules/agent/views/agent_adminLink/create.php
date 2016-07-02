<?php
/* @var $this ZadmintypeController */
/* @var $model AdminType */

$this->breadcrumbs=array(
	'管理页'=>array('index'),
	'创建导航',
);
?>
<h1>创建导航</h1>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>