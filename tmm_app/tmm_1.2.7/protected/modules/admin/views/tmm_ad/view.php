<?php
/* @var $this Tmm_adController */
/* @var $model Ad */
if (isset($model->Ad_Ad) && $model->Ad_Ad)
{
	$this->breadcrumbs=array(
			'广告专题管理页'=>array('admin'),
			$model->Ad_Ad->name=>array('view', 'id'=>$model->Ad_Ad->id),
			'广告管理页'=>array('manage'),
			$model->name,
	);
}
else
{
	$this->breadcrumbs=array(
		'广告专题管理页'=>array('admin'),	
		$model->name,
	);
}
?>

<h1>查看 <?php isset($model->Ad_Ad) && $model->Ad_Ad ? '广告' : '广告专题'; ?>
		<font color='#eb6100'>
			<?php echo CHtml::encode($model->name) ?>
		</font>
</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'id',
		),
		array(
				'name'=>'p_id',
				'value'=>isset($model->Ad_Ad) && $model->Ad_Ad ? $model->Ad_Ad->name : '----广告专题----',
		),
		array(
			'name'=>'admin_id',
			'value'=>$model->Ad_Admin->username . '[' . $model->Ad_Admin->name . ']'
		),
		array(
			'name'=>'type',
			'value'=>$model::$_type[$model->type]
		),
		array(
			'name'=>'link_type',
			'value'=>isset($model->Ad_Type->name) ? $model->Ad_Type->name : '------',
		),
		array(
			'name'=>'name',
		),
		array(
			'name'=>'info',
		),
		array(
				'name'=>'link',
		),
		array(
				'name'=>'options',
		),
		array(
			'name'=>'img',
			'type'=>'raw',
			'value'=>$this->show_img($model->img),
		),
		array(
			'name'=>'sort',
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
