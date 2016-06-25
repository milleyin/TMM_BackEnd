<?php
/* @var $this Tmm_wifiController */
/* @var $model Wifi */

$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	$model->name,
);
?>
<h1>查看 酒店服务 <font color='#eb6100'><?php echo CHtml::encode($model->name); ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'id',
		),
		array(
				'name'=>'admin_id',
				'value'=>$model->Wifi_Admin->username.' ['.$model->Wifi_Admin->name.']',
		),
		array(
				'name'=>'name',
		),
		array(
				'name'=>'info',
		),
		array(
				'name'=>'icon',
				'type'=>'raw',
				'value'=>$this->show_img($model->icon),		
		),
		array(
				'name'=>'add_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'up_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'status',
				'value'=>$model::$_status[$model->status],
		),
	),
)); ?>