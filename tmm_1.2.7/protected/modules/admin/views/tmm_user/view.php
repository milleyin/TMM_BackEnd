<?php
/* @var $this Tmm_userController */
/* @var $model User */

$this->breadcrumbs=array(
	'用户管理页'=>array('admin'),
	$model->phone,
);
?>

<h1>查看 用户 <font color='#eb6100'><?php echo $model->phone; ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'id',
		),
		array(
				'name'=>'phone',
		),
		array(
				'name'=>'nickname',
		),
		array(
				'name'=>'gender',
				'value'=>$model::$_gender[$model->gender],
		),
		array(
				'name'=>'is_organizer',
				'value'=>$model::$_is_organizer[$model->is_organizer],
		),
		array(
				'name'=>'audit',
				'value'=>$model::$_audit[$model->audit] .(
						$model->audit == User::audit_nopass ? 
						'  (原因：' . AuditLog::get_audit_log(AuditLog::organizer,$model->id)->info.')' : ''
				),
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
				'name'=>'login_address',
		),
		array(
				'name'=>'last_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'last_ip',
		),
		array(
				'name'=>'last_address',
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
		array(
			'label'=>'银行卡',
			'type'=>'raw',
			'value'=>CHtml::link('点击查看我的银行卡',Yii::app()->createUrl("admin/tmm_bankCard/admin",array("card_id"=>$model->phone,"card_type"=>BankCard::user)),array('color'=>'red')),
		),
	),
)); ?>

