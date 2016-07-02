<?php
/* @var $this PrizeController */
/* @var $model Prize */

$this->breadcrumbs = array(
	'奖品管理页'=>array('admin'),
	$model->name=>array('view', 'id'=>$model->id),
	'更新奖品页',
);
?>

<h1>更新奖品 <font color='#eb6100'><?php echo CHtml::encode($model->name); ?></font></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>