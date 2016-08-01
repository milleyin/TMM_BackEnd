<?php
/* @var $this PadController */
/* @var $model Pad */

$this->breadcrumbs = array(
    '体验店管理页'=>array('admin'),
    $model->Pad_Store->name => array('store/view', 'id'=>$model->Pad_Store->id),
    '展示屏管理页'=>array('admin', 'Pad[store_id]'=>'=' . $model->Pad_Store->id),
    $model->name,
);
?>

<h1>查看展示屏 <font color='#eb6100'><?php echo CHtml::encode($model->name); ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'id',
		),
        array(
                'name'=>'name',
        ),
        array(
                'name'=>'number',
        ),
		array(
				'name'=>'store_id',
                'value'=>$model->Pad_Store->store_name
		),
        array(
                'name'=>'Pad_Store.phone',
        ),
        array(
                'name'=>'Pad_Store.name',
        ),
        array(
                'name'=>'Pad_Store.telephone',
        ),
        array(
                'name'=>'Pad_Store.province',
                'value'=>$model->Pad_Store->Store_Area_province->name
        ),
        array(
                'name'=>'Pad_Store.city',
                'value'=>$model->Pad_Store->Store_Area_city->name
        ),
        array(
                'name'=>'Pad_Store.district',
                'value'=>$model->Pad_Store->Store_Area_district->name
        ),
        array(
                'name'=>'Pad_Store.address',
        ),
		array(
				'name'=>'mac',
		),
		array(
				'name'=>'state',
                'value'=>$model::$_state[$model->state]
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