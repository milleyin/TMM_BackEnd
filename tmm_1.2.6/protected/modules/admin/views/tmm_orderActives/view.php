<?php
/* @var $this Tmm_orderActivesController */
/* @var $model OrderActives */

$this->breadcrumbs=array(
	'订单管理页'=>array('/admin/tmm_orderActives/admin'),
	'活动管理页'=>array('admin'),
	$model->actives_no,
);
?>

<h1>查看 活动管理页</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'id',
		),
		array(
				'name'=>'actives_no',
		),
		array(
				'name'=>'organizer_id',
		),
		array(
				'name'=>'actives_id',
		),
		array(
				'name'=>'actives_type',
		),
		array(
				'name'=>'user_order_count',
		),
		array(
				'name'=>'user_pay_count',
		),
		array(
				'name'=>'user_submit_count',
		),
		array(
				'name'=>'user_price',
		),
		array(
				'name'=>'user_go_count',
		),
		array(
				'name'=>'user_price_count',
		),
		array(
				'name'=>'total',
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
				'name'=>'status',
				'value'=>$model::$_status[$model->status],
		),
	),
));
if(isset($model->OrderActives_Actives)){
	if($model->OrderActives_Actives->tour_type==Actives::tour_type_thrand){
		//一条线
		$this->renderPartial('actives_items_view_thrand', array(
			'model' => $model,
		));
	}elseif($model->OrderActives_Actives->tour_type==Actives::tour_type_dot){
		//多个点
		$this->renderPartial('actives_items_view_thrand', array(
			'model' => $model,
		));
	}elseif($model->OrderActives_Actives->tour_type==Actives::tour_type_farm){
		//农产品
	}
}