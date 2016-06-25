<?php
/* @var $this Tmm_cashController */
/* @var $model Cash */

$this->breadcrumbs=array(
	'结算申请管理页'=>array('admin'),
	$model::getRoleName($model, $model->cash_type),
);
?>

<h1>查看 结算申请 <font color='#eb6100'><?php echo $model::getRoleName($model, $model->cash_type); ?></font></h1>

<?php $this->renderPartial('_view',array(
	'model'=>$model,
)); ?>

