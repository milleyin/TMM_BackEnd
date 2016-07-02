<?php
/* @var $this ShopController */
/* @var $model Shop */

$this->breadcrumbs = array(
    '体验店管理页'=>array('store/admin'),
    $model->Shop_Pad->Pad_Store->store_name =>array('store/view', 'id'=>$model->store_id),
	'展示屏管理页'=>array('pad/admin', 'Pad[store_id]'=> '=' . $model->store_id),
    $model->Shop_Pad->name=>array('pad/view', 'id'=>$model->pad_id),
    '商品管理页'=>array('admin', 'Shop[pad_id]'=> '=' . $model->pad_id),
	$model->name=>array('view', 'id'=>$model->id),
	'更新商品页',
);
?>

<h1>更新商品 <font color='#eb6100'><?php echo CHtml::encode($model->name); ?></font></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>