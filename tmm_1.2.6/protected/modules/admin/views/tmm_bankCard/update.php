<?php
/* @var $this Tmm_bankCardController */
/* @var $model BankCard */

$this->breadcrumbs=array(
	'银行卡管理页'=>array('/admin/tmm_bankCard/admin'),
	$model->id=>array('view','id'=>$model->id),
	'更新页',
);

?>

<h1>更新页银行卡 <font color='#eb6100'><?php echo $model->id; ?></font></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>