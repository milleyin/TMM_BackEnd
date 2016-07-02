<?php
/* @var $this Tmm_hotelController */
/* @var $model Hotel */

$this->breadcrumbs=array(
	'项目管理页'=>array('/operator/items/admin'),
	$model->Hotel_Items->name=>array('view','id'=>$model->id),
	'更新项目（吃）',
);

?>
<h1>更新项目（吃） <font color='#eb6100'><?php echo CHtml::encode($model->Hotel_Items->name); ?></font></h1>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>