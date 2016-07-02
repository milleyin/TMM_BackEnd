<?php
/* @var $this UploadController */
/* @var $model Upload */

$this->breadcrumbs = array(
	'上传资源管理页'=>array('admin'),
	$model->info ? $model->info : $model->id,
);
?>
<h1>查看上传资源 <font color='#eb6100'><?php echo CHtml::encode($model->info ? $model->info : $model->id); ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'id',
		),
		array(
				'name'=>'type',
				'value'=>$model::$_type[$model->type],
		),
		array(
				'name'=>'upload_id',
		),
		array(
				'name'=>'manager_id',
		),
		array(
				'name'=>'upload_type',
				'value'=>$model::$_upload_type[$model->upload_type],
		),
        array(
                'name'=>'path',
                'type'=>'raw',
                'value'=>$model->upload_type == Upload::UPLOAD_UPLOAD_TYPE_IMAGE ?
                CHtml::image($model->getUrlPath(), CHtml::encode($model->info), array('style'=>'widht:150px;height:150px;'))
                : CHtml::link($model::$_upload_type[$model->upload_type], $model->getUrlPath(), array('target'=>'_blank')),
        ),
		array(
				'name'=>'path',
		),
		array(
				'name'=>'info',
		),
		array(
				'name'=>'size',
                'value'=>$model->getSize(),
		),
		array(
				'name'=>'up_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'add_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'status',
				'value'=>$model::$_status[$model->status],
		),
	),
)); 
?>