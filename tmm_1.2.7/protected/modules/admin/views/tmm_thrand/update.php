<?php
/* @var $this Tmm_thrandController */
/* @var $model Thrand */

$this->breadcrumbs=array(
	'内容管理页'=>array('/admin/tmm_shops/admin'),
	'线路管理页'=>array('admin'),
	$model->Thrand_Shops->name=>array('view', 'id'=>$model->Thrand_Shops->id),
	'线路更新页',
);
?>

<h1>更新线路 <font color='#eb6100'><?php echo CHtml::encode($model->Thrand_Shops->name); ?></font></h1>
<?php $this->renderPartial('_form', array('model'=>$model, 'dotModel'=>$dotModel)); ?>