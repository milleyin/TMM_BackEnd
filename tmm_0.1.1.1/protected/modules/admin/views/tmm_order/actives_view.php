<?php
/* @var $this Tmm_orderController */
/* @var $model Order */

$this->breadcrumbs=array(
	'订单管理页'=>array('/admin/tmm_order/actives'),
	'活动管理页'=>array('admin'),
	$model->order_no,
);
?>

<h1>查看 自助游订单 <font color='#eb6100'><?php echo $model->order_no; ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'id',
		),
		array(
				'name'=>'order_organizer_id',
		),
		array(
				'name'=>'son_order_count',
		),
		array(
				'name'=>'order_no',
		),
		array(
				'name'=>'order_type',
		),
		array(
				'name'=>'user_id',
		),
		array(
				'name'=>'order_price',
		),
		array(
				'name'=>'pay_price',
		),
		array(
				'name'=>'price',
		),
		array(
				'name'=>'trade_no',
		),
		array(
				'name'=>'trade_name',
		),
		array(
				'name'=>'service_price',
		),
		array(
				'name'=>'service_fee',
		),
		array(
				'name'=>'pay_type',
				'value'=>$model::$_pay_type[$model->pay_type],
		),
		array(
				'name'=>'user_go_count',
		),
		array(
				'name'=>'user_price',
		),
		array(
				'name'=>'user_price_fact',
		),
		array(
				'name'=>'pay_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'status_go',
				'value'=>$model::$_status_go[$model->status_go],
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
				'name'=>'pay_status',
				'value'=>$model::$_pay_status[$model->pay_status],
		),
		array(
				'name'=>'order_status',
				'value'=>$model::$_order_status[$model->order_status],
		),
		array(
				'name'=>'centre_status',
				'value'=>$model::$_centre_status[$model->centre_status],
		),
		array(
				'name'=>'status',
				'value'=>$model::$_status[$model->status],
		),
	),
));

if(isset($model->Order_OrderActives->OrderActives_Actives)){
	if($model->Order_OrderActives->OrderActives_Actives->tour_type==Actives::tour_type_thrand){
		//一条线
		$this->renderPartial('actives_items_view_thrand', array(
 			'model' => $model,
 		));
	}elseif($model->Order_OrderActives->OrderActives_Actives->tour_type==Actives::tour_type_dot){
		//多个点
		$this->renderPartial('actives_items_view_thrand', array(
			'model' => $model,
		));
	}elseif($model->Order_OrderActives->OrderActives_Actives->tour_type==Actives::tour_type_farm){
		//农产品
	}
}

//随行人员
// if(isset($model->Order_OrderRetinue))
// 	$this->renderPartial('_retinue_view', array(
// 		'model'=>$model,
// 	));
//if(isset($model->Order_OrderShops))
//{
// 	if($model->order_type == Order::order_type_dot) {
// 		$this->renderPartial('_items_view_dot', array(
// 			'model' => $model,
// 		));
// 	}elseif($model->order_type == Order::order_type_thrand){
// 		$this->renderPartial('_items_view_thrand', array(
// 			'model' => $model,
// 		));
// 	}
//}



