<?php
/* @var $this ZadmintypeController */
/* @var $model AdminType */

$this->breadcrumbs=array(
	'运营商管理页'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'修改运营商导航',
);
?>
<h1>修改导航：<strong><?php echo $model->name; ?></strong></h1>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>