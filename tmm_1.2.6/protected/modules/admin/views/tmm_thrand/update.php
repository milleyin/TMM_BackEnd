<?php
/* @var $this Tmm_thrandController */
/* @var $model Thrand */

$this->breadcrumbs=array(
	'运营商管理页'=>array('/admin/tmm_agent/admin'),
	$model->Thrand_Shops->Shops_Agent->phone=>array('/admin/tmm_agent/view','id'=>$model->Thrand_Shops->Shops_Agent->id),
	'商品(线)更新页',
);
?>

<h1>更新页 商品(线) <font color='#eb6100'><?php echo $model->id; ?></font></h1>
<?php $this->renderPartial('_form', array('model'=>$model,'search_model'=>$search_model)); ?>