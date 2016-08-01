<?php
/* @var $this ConfigController */
/* @var $model Config */

$this->breadcrumbs = array(
    '抽奖配置管理页'=>array('admin'),
    $model->Config_Pad->name,
);
?>

<h1>查看 抽奖配置 <font color='#eb6100'><?php echo CHtml::encode($model->Config_Pad->name); ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        array(
                'name'=>'id',
        ),
        array(
            'name'=>'type',
            'value' => $model::$_type[$model->type],
        ),
        array(
            'name'=>'money',
            'value' => $model->viewMoney($model->money),
        ),
        array(
            'name'=>'chance_number',
        ),
        array(
                'name'=>'number',
        ),
        array(
                'name'=>'info',
                'type'=>'raw',
                'value'=>nl2br(CHtml::encode($model->info)),
        ),
        array(
            'name'=>'ad_url',
        ),
        array(
            'name'=>'pad_id',
            'value'=>$model->Config_Pad->name
        ),
        array(
                'name'=>'Config_Pad.number',
        ),      
        array(
                'name'=>'store_id',
                'value'=>$model->Config_Store->store_name
        ),
        array(
                'name'=>'Config_Store.phone',
        ),
        array(
                'name'=>'Config_Store.name',
        ),
        array(
                'name'=>'Config_Store.telephone',
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