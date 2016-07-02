<?php
/* @var $this Tmm_actionController */
/* @var $model Action */
$this->breadcrumbs=array(
	'代理商管理页'=>array('/admin/tmm_organizer/admin'),
	$model->Actives_User->phone=>array('/admin/tmm_organizer/view','id'=>$model->Actives_User->id),
	'觅趣(多个点)更新页',
);
?>
	<h1>更新 觅趣(多个点)</h1>
<?php $this->renderPartial('dot_form', array('model'=>$model,'search_model'=>$search_model)); ?>