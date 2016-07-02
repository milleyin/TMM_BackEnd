<?php
/* @var $this ThrandController */
/* @var $model Thrand */

$this->breadcrumbs = array(
	'觅境管理页'=>array('/operator/shops/admin'),
	$model->Thrand_Shops->name=>array('view', 'id'=>$model->id),
	'线路更新页',
);

?>

<h1>更新线路<font color='#eb6100'> <?php echo CHtml::encode($model->Thrand_Shops->name); ?></font></h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'dotModel'=>$dotModel)); ?>