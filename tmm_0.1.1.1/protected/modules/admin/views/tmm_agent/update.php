<?php
/* @var $this Tmm_agentController */
/* @var $model Agent */

$this->breadcrumbs=array(
	'运营商管理页'=>array('admin'),
	$model->phone=>array('view','id'=>$model->id),
	'更新运营商页',
);

?>

<h1>更新运营商 <font color='#eb6100'><?php echo $model->phone; ?></font></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>