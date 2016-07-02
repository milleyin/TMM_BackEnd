<?php
/* @var $this AdController */
/* @var $model Ad */

$this->breadcrumbs = array(
	'广告管理页'=>array('admin'),
	$model->name,
);
?>

<h1>查看广告 <font color='#eb6100'><?php echo CHtml::encode($model->name); ?></font></h1>

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
				'name'=>'name',
		),
        array(
                'name'=>'Ad_Upload.path',
                'type'=>'raw',
                'value'=>$model->Ad_Upload->upload_type == Upload::UPLOAD_UPLOAD_TYPE_IMAGE ?
                CHtml::image($model->Ad_Upload->getUrlPath(), CHtml::encode($model->Ad_Upload->info), array('style'=>'widht:150px;height:150px;'))
                : CHtml::link(CHtml::encode($model->name), $model->Ad_Upload->getUrlPath(), array('target'=>'_blank')),
        ),
        array(
                'name'=>'Ad_Upload.size',
                'value'=>$model->Ad_Upload->getSize(),
        ),
        array(
                'name'=>'Ad_Upload.info',
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
                'name'=>'manager_id',
        ),
		array(
				'name'=>'status',
				'value'=>$model::$_status[$model->status],
		),
	),
)); 
?>