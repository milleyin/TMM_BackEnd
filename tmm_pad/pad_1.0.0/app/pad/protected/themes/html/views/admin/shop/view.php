<?php
/* @var $this ShopController */
/* @var $model Shop */

$this->breadcrumbs = array(
    '体验店管理页'=>array('store/admin'),
    $model->Shop_Store->store_name =>array('store/view', 'id'=>$model->store_id),
	'展示屏管理页'=>array('pad/admin', 'Pad[store_id]'=> '=' . $model->store_id),
    $model->Shop_Pad->name=>array('pad/view', 'id'=>$model->pad_id),
    '商品管理页'=>array('admin', 'Shop[pad_id]'=> '=' . $model->pad_id),
	$model->name,
);
?>

<h1>查看商品 <font color='#eb6100'><?php echo CHtml::encode($model->name); ?></font></h1>

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
                'name'=>'Shop_Upload.path',
                'type'=>'raw',
                'value'=>CHtml::image($model->Shop_Upload->getUrlPath(), CHtml::encode($model->Shop_Upload->info), array('style'=>'widht:150px;height:150px;'))
        ),
		array(
				'name'=>'store_id',
                'value'=>$model->Shop_Store->store_name,
		),
        array(
                'name'=>'Shop_Store.phone',           
        ),
        array(
                'name'=>'Shop_Store.name',
        ),
        array(
                'name'=>'Shop_Store.telephone',
        ),
        array(
                'name'=>'Shop_Store.province',
                 'value'=>$model->Shop_Store->Store_Area_province->name
        ),
        array(
                'name'=>'Shop_Store.city',
                'value'=>$model->Shop_Store->Store_Area_city->name
        ),
        array(
                'name'=>'Shop_Store.district',
                'value'=>$model->Shop_Store->Store_Area_district->name
        ),
        array(
                'name'=>'Shop_Store.address',
        ),
		array(
				'name'=>'pad_id',
                 'value'=>$model->Shop_Pad->name,
		),
        array(
                'name'=>'Shop_Pad.number',
        ),
        array(
                'name'=>'Shop_Pad.mac',
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