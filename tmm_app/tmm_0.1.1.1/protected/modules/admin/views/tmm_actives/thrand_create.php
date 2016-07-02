<?php
/* @var $this Tmm_actionController */
/* @var $model Action */
$this->breadcrumbs=array(
	'代理商管理页'=>array('/admin/tmm_organizer/admin'),
	$model->Actives_User->phone=>array('/admin/tmm_organizer/view','id'=>$model->Actives_User->id),
	'觅趣(一条线)创建页',
);
?>
	<h1>创建 觅趣(一条线)</h1>
<?php $this->renderPartial('thrand_form', array('model'=>$model,'search_model'=>$search_model)); ?>