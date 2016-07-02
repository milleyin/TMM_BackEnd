<?php
/* @var $this ShopController */
/* @var $model Shop */

$this->breadcrumbs=array(
    '体验店管理页'=>array('store/admin'),
    $model->Shop_Pad->Pad_Store->store_name =>array('store/view', 'id'=>$model->Shop_Pad->store_id),
	'展示屏管理页'=>array('pad/admin', 'Pad[store_id]'=> '=' . $model->Shop_Pad->store_id),
    $model->Shop_Pad->name=>array('pad/view', 'id'=>$model->Shop_Pad->id),
    '商品管理页'=>array('admin', 'Shop[pad_id]'=> '=' . $model->Shop_Pad->id),
	'创建商品页',
);
?>

<h1>创建商品</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>