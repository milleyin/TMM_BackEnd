<?php
/* @var $this Tmm_dotController */
/* @var $model Dot */

$this->breadcrumbs=array(
	'内容管理页'=>array('/admin/tmm_shops/admin'),
	'景点管理页'=>array('admin'),
	$model->Dot_Shops->name=>array('view','id'=>$model->id),
	'景点更新页',
);
?>
<h1>更新景点 <font color='#eb6100'><?php echo CHtml::encode($model->Dot_Shops->name); ?></font></h1>
<?php $this->renderPartial('_form', array('model'=>$model, 'itemsModel'=>$itemsModel)); ?>