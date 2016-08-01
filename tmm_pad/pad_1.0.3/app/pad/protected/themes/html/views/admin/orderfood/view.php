<?php
/* @var $this OrderfoodController */
/* @var $model OrderFood */

$this->breadcrumbs = array(
    '管理页'=>array('admin'),
    $model->id,
);
?>

<h1>查看 OrderFood <font color='#eb6100'><?php echo CHtml::encode($model->id); ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        array(
                'name'=>'id',
        ),
        array(
                'name'=>'user_id',
        ),
        array(
                'name'=>'store_id',
        ),
        array(
                'name'=>'pad_id',
        ),
        array(
                'name'=>'money',
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
                'name'=>'order_status',
                'value'=>$model::$_order_status[$model->order_status],
        ),
        array(
                'name'=>'status',
                'value'=>$model::$_status[$model->status],
        ),
    ),
)); 
?>