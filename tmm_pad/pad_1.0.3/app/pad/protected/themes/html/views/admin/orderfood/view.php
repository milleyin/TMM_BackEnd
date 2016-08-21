<?php
/* @var $this OrderfoodController */
/* @var $model OrderFood */

$this->breadcrumbs = array(
    '抢菜订单管理页'=>array('admin'),
    $model->OrderFood_Order->order_no,
);
?>

<h1>查看 抢菜订单 <font color='#eb6100'><?php echo CHtml::encode($model->OrderFood_Order->order_no); ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        array(
            'name'=>'id',
        ),
        array(
            'name'=>'OrderFood_Order.order_no',
        ),
        array(
            'name'=>'user_id',
            'value' => $model->OrderFood_User->name
        ),
        array(
            'name'=>'store_id',
            'value' => $model->OrderFood_Store->store_name
        ),
        array(
            'name'=>'OrderFood_Store.phone',
        ),
        array(
            'name'=>'OrderFood_Store.province',
            'value'=>$model->OrderFood_Store->Store_Area_province->name
        ),
        array(
            'name'=>'OrderFood_Store.city',
            'value'=>$model->OrderFood_Store->Store_Area_city->name
        ),
        array(
            'name'=>'OrderFood_Store.district',
            'value'=>$model->OrderFood_Store->Store_Area_district->name
        ),
        array(
            'name'=>'OrderFood_Store.address',
        ),
        array(
            'name'=>'pad_id',
            'value'=>$model->OrderFood_Pad->name
        ),
        array(
            'name'=>'OrderFood_Pad.number',
        ),
        array(
            'name'=>'money',
            'value' => $model->viewMoney($model->money),
        ),
        array(
            'name'=>'OrderFood_Order.money',
            'value' => $model->viewMoney($model->OrderFood_Order->money),
        ),
        array(
            'name'=>'OrderFood_Order.trade_money',
            'value'=>$model->viewMoney($model->OrderFood_Order->trade_money),
        ),
        array(
            'name'=>'OrderFood_Order.trade_no',
        ),
        array(
            'name'=>'OrderFood_Order.trade_id',
        ),
        array(
            'name'=>'OrderFood_Order.trade_name',
        ),
        array(
            'name'=>'OrderFood_Order.trade_type',
            'value'=>Order::$_trade_type[$model->OrderFood_Order->trade_type],
        ),
        array(
            'name'=>'OrderFood_Order.trade_time',
            'type'=>'datetime',
        ),
        array(
            'name'=>'OrderFood_Order.pay_status',
            'value'=>Order::$_pay_status[$model->OrderFood_Order->pay_status],
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