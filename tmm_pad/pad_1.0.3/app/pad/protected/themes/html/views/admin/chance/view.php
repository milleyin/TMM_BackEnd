<?php
/* @var $this ChanceController */
/* @var $model Chance */

$this->breadcrumbs = array(
    '抽奖机会管理页'=>array('admin'),
    $model->Chance_User->name,
);
?>

<h1>查看抽奖机会 <font color='#eb6100'><?php echo CHtml::encode($model->Chance_User->name); ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        array(
                'name'=>'id',
        ),
        array(
                'name'=>'user_id',
                'value'=>$model->Chance_User->name
        ),
        array(
                'name'=>'count',
        ),
        array(
                'name'=>'number',
        ),
        array(
                'name'=>'pad_id',
                 'value'=>$model->Chance_Pad->name,
        ),
        array(
                'name'=>'Chance_Pad.number',
        ),
        array(
                'name'=>'Chance_Pad.mac',
        ),
        array(
                'name'=>'store_id',
                'value'=>$model->Chance_Store->store_name,
        ),
        array(
                'name'=>'Chance_Store.phone',
        ),
        array(
                'name'=>'Chance_Store.name',
        ),
        array(
                'name'=>'Chance_Store.telephone',
        ),
        array(
                'name'=>'Chance_Store.province',
                'value'=>$model->Chance_Store->Store_Area_province->name
        ),
        array(
                'name'=>'Chance_Store.city',
                'value'=>$model->Chance_Store->Store_Area_city->name
        ),
        array(
            'name'=>'Chance_Store.district',
            'value'=>$model->Chance_Store->Store_Area_district->name
        ),
        array(
            'name'=>'Chance_Store.address',
        ),
        array(
            'name'=>'config_id',
        ),
        array(
            'name'=>'type',
            'value' => Config::$_type[$model->type],
        ),
        array(
                'name'=>'date_time',
                'type'=>'date',
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