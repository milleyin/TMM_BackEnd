<?php
/* @var $this OrderController */
/* @var $model Order */

$this->breadcrumbs = array(
	'觅境订单管理页'=>array('admin'),
	$model->order_no,
);
?>

<h1>查看觅境订单 <font color='#eb6100'><?php echo CHtml::encode($model->order_no) ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'order_no',
		),
		array(
				'name'=>'order_type',
				'value'=>$model::$_order_type[$model->order_type],
		),
		array(
				'name'=>'user_id',
				'value'=>$model->Order_User->phone,
		),
		array(
				'name'=>'order_price',
		),
		array(
				'name'=>'price',
		),
		array(
				'name'=>'pay_type',
				'value'=>$model::$_pay_type[$model->pay_type],
		),
		array(
				'name'=>'user_go_count',
		),
		array(
				'name'=>'pay_time', 
				//'type'=>'datetime',
				'value'=>$model->pay_time == 0 ? '--------' : Yii::app()->format->formatDatetime($model->pay_time),
		),
		array(
				'name'=>'status_go',
				'value'=>$model::$_status_go[$model->status_go]
		),
		array(
				'name'=>'go_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'add_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'up_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'order_status',
				'value'=>$model::$_order_status[$model->order_status]
		),
	),
));

	if ($model->order_type == Order::order_type_dot)
	{
		$this->renderPartial('_view/_dot', array(
				'model'=>$model,
		));
	}
	else if ($model->order_type == Order::order_type_thrand)
	{
		$this->renderPartial('_view/_thrand', array(
				'model'=>$model,
		));
	}
?>