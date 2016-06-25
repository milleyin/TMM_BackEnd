<?php
/* @var $this Agent_adminController */
/* @var $model Agent */

$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	$model->phone,
);
?>

<h1>查看 Agent <font color='#eb6100'><?php echo $model->phone; ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'id',
			),
		array(
				'name'=>'admin_id',
			),
		array(
				'name'=>'phone',
			),
		array(
				'name'=>'password',
			),
		array(
				'name'=>'merchant_count',
			),
		array(
				'name'=>'push',
			),
		array(
				'name'=>'firm_name',
			),
		array(
				'name'=>'count',
			),
		array(
				'name'=>'login_error',
			),
		array(
				'name'=>'error_count',
			),
		array(
				'name'=>'login_time',
				'type'=>'datetime',
			),
		array(
				'name'=>'login_ip',
			),
		array(
				'name'=>'last_time',
				'type'=>'datetime',
			),
		array(
				'name'=>'last_ip',
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
)); ?>