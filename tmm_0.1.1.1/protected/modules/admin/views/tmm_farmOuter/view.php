<?php
/* @var $this Tmm_farmOuterController */
/* @var $model FarmOuter */

$this->breadcrumbs=array(
	'农产品外部链接管理页'=>array('admin'),
	$model->name,
);
?>

<h1>查看 农产品外部链接 <font color='#eb6100'><?php echo $model->name; ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'id',
		),
		array(
				'name'=>'dot_id',
				'value'=>CHtml::link($model->FarmOuter_Shops->name,array("/admin/tmm_dot/view","id"=>$model->FarmOuter_Shops->id)),
				'type'=>'raw'
		),
		array(
				'name'=>'name',
		),
		array(
				'name'=>'info',
		),
		array(
				'name'=>'img',
				'value'=>$this->show_img($model->img),
				'type'=>'raw',
		),
		array(
				'name'=>'link',
				'value'=>CHtml::link($model->link,$model->link,array("target"=>"_blank")),
				'type'=>'raw'
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
)); 

