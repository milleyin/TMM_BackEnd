<?php
/* @var $this OrderController */
/* @var $model Order */

$this->breadcrumbs = array(
    '订单管理（全）页'=>array('admin'),
    $model->order_no,
);
?>

<h1>查看订单（全） <font color='#eb6100'><?php echo CHtml::encode($model->order_no); ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        array(
                'name'=>'id',
        ),
        array(
                'name'=>'p_id',
        ),
        array(
                'name'=>'type',
                'value'=>$model::$_type[$model->type],
        ),
        array(
                'name'=>'order_no',
        ),
        array(
                'name'=>'role_id',
        ),
        array(
                'name'=>'money',
                'value'=>$model->viewMoney($model->money),
        ),
        array(
                'name'=>'trade_money',
                'value'=>$model->viewMoney($model->trade_money),
        ),
        array(
                'name'=>'trade_no',
        ),
        array(
                'name'=>'trade_id',
        ),
        array(
                'name'=>'trade_name',
        ),
        array(
                'name'=>'trade_type',
                'value'=>$model::$_trade_type[$model->trade_type],
        ),
        array(
                'name'=>'trade_time',
                'type'=>'datetime',
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
                'name'=>'pay_status',
                'value'=>$model::$_pay_status[$model->pay_status],
        ),
        array(
                'name'=>'status',
                'value'=>$model::$_status[$model->status],
        ),
    ),
)); 
?>