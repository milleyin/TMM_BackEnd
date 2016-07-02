<?php
/* @var $this Tmm_bankCardController */
/* @var $model BankCard */

$this->breadcrumbs=array(
	'银行卡管理页'=>array('/admin/tmm_bankCard/admin'),
	'创建页',
);
?>

<h1>创建 银行卡</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>