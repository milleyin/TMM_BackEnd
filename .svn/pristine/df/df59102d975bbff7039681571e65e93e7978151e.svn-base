<?php
/* @var $this PrizeController */
/* @var $model Prize */

$this->breadcrumbs = array(
	'奖品管理页'=>array('admin'),
	$model->name,
);
?>

<h1>查看奖品 <font color='#eb6100'><?php echo CHtml::encode($model->name); ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'id',
		),
        array(
                'name'=>'position',
        ),
        array(
                'name'=>'name',
        ),
        array(
                'name'=>'Prize_Upload.path',
                'type'=>'raw',
                'label'=>'奖品图片',
                'value'=> empty($model->Prize_Upload) ? '暂无图片': CHtml::image($model->Prize_Upload->getUrlPath(), CHtml::encode($model->Prize_Upload->info), array('style'=>'widht:150px;height:150px;'))
        ),
        array(
                'name'=>'info',
        ),
        array(
                'name'=>'count',
        ),
        array(
                'name'=>'number',
        ),
        array(
                'name'=>'odds',
        ),
        array(
                'name'=>'receive_type',
                'value'=>$model::$_receive_type[$model->receive_type],
        ),
        array(
                'name'=>'url',
        ),
        array(
                'name'=>'pad_id',
                'value'=>$model->Prize_Pad->name
        ),
        array(
                'name'=>'Prize_Pad.number',
        ),
		array(
				'name'=>'store_id',
				'value'=>$model->Prize_Store->store_name
		),
        array(
                'name'=>'Prize_Store.phone',
        ),
        array(
                'name'=>'Prize_Store.name',
        ),
        array(
                'name'=>'Prize_Store.telephone',
        ),
		array(
				'name'=>'manager_id',
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