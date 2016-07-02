<?php
/* @var $this SelectController */
/* @var $model Select */

$this->breadcrumbs = array(
	'选择关系管理页'=>array('admin'),
	$model->Select_Pad->name,
);
?>

<h1>查看选择关系 <font color='#eb6100'><?php echo CHtml::encode($model->Select_Pad->name); ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'id',
		),
		array(
				'name'=>'ad_id',
		),
        array(
                'name'=>'Select_Ad.name',
        ),
		array(
				'name'=>'ad_type',
				'value'=>Ad::$_type[$model->ad_type],
		),
        array(
                'name'=>'Select_Ad.Ad_Upload.path',
                'type'=>'raw',
                'value'=>$model->Select_Ad->Ad_Upload->upload_type == Upload::UPLOAD_UPLOAD_TYPE_IMAGE ?
                CHtml::image($model->Select_Ad->Ad_Upload->getUrlPath(), CHtml::encode($model->Select_Ad->Ad_Upload->info), array('style'=>'widht:150px;height:150px;'))
                : CHtml::link(CHtml::encode($model->Select_Ad->name), $model->Select_Ad->Ad_Upload->getUrlPath(), array('target'=>'_blank')),
        ),
        array(
                'name'=>'Select_Ad.Ad_Upload.size',
                'value'=>$model->Select_Ad->Ad_Upload->getSize(),
        ),
        array(
                'name'=>'Select_Ad.Ad_Upload.info',
        ),
        array(
                'name'=>'pad_id',
        ),
        array(
                'name'=>'Select_Pad.name',
        ),
        array(
                'name'=>'Select_Pad.number',
        ),
		array(
				'name'=>'store_id',
		),
        array(
                'name'=>'Select_Store.store_name',
        ),
        array(
                'name'=>'Select_Store.phone',
        ),
        array(
                'name'=>'Select_Store.name',
        ),
        array(
                'name'=>'Select_Store.telephone',
        ),
        array(
                'name'=>'Select_Store.province',
                'value'=>$model->Select_Store->Store_Area_district->name,
        ),
        array(
                'name'=>'Select_Store.city',
                'value'=>$model->Select_Store->Store_Area_district->name,
        ),
        array(
                'name'=>'Select_Store.district',
                'value'=>$model->Select_Store->Store_Area_district->name,
        ),
        array(
                'name'=>'Select_Store.address',
        ),
		array(
				'name'=>'manager_id',
		),
		array(
				'name'=>'add_time',
				'type'=>'datetime',
		),
	),
)); 
?>