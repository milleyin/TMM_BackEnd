<?php
/* @var $this ZadmintypeController */
/* @var $model AdminType */

$this->breadcrumbs=array(
	'运营商管理页'=>array('index'),
	'运营商创建导航',
);
?>
<h1>运营商创建导航</h1>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>