<?php
/* @var $this Tmm_cashController */
/* @var $model Cash */

$this->breadcrumbs=array(
	'退款管理日志'=>array('admin'),
	$model->order_no,
	$model::$_audit_status[$model->audit_status]
);
?>

<h1>查看 退款申请 <font color='#eb6100'><?php echo $model->order_no; echo $model::$_audit_status[$model->audit_status];?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'id',
		),
		array(
			'name'=>'admin_id',
			'value'=>isset($model->RefundLog_Admin->name) ?$model->RefundLog_Admin->name:"-",
		),
		array(
			'name'=>'user_id',
			'value'=>isset($model->RefundLog_User->phone) ?$model->RefundLog_User->phone:"-" ,
		),
		array(
			'name'=>'is_organizer',
			'value'=>$model::$_is_organizer[$model->is_organizer],
		),
		array(
			'name'=>'order_id',
		),
		array(
			'name'=>'order_no',
		),
		array(
			'name'=>'refund_id',
		),
		array(
			'name'=>'reason',
		),
		array(
			'name'=>'refund_price',
		),
		array(
			'name'=>'refund_courier',
		),
		array(
			'name'=>'refund_courier_num',
		),
		array(
			'name'=>'admin_id_first',
			'value'=>isset($model->RefundLogFirst_Admin->name) ?$model->RefundLogFirst_Admin->name:"-" ,
		),
		array(
			'name'=>'remark_first',
		),
		array(
			'name'=>'first_time',
			'type'=>'datetime',
		),
		array(
			'name'=>'admin_id_double',
			'value'=>isset($model->RefundLogDouble_Admin->name) ?$model->RefundLogDouble_Admin->name:"-" ,
		),
		array(
			'name'=>'remark_double',
		),
		array(
			'name'=>'double_time',
			'type'=>'datetime',
		),
		array(
			'name'=>'admin_id_submit',
			'value'=>isset($model->RefundLogSubmit_Admin->name) ?$model->RefundLogSubmit_Admin->name:"-" ,
		),
		array(
			'name'=>'remark_submit',
		),
		array(
			'name'=>'submit_time',
			'type'=>'datetime',
		),
		array(
			'name'=>'refund_img1',
			'type'=>'raw',
			'value'=>$this->show_img($model->refund_img1),
		),
		array(
			'name'=>'refund_img2',
			'type'=>'raw',
			'value'=>$this->show_img($model->refund_img2),
		),
		array(
			'name'=>'refund_img3',
			'type'=>'raw',
			'value'=>$this->show_img($model->refund_img3),
		),
		array(
			'name'=>'refund_img4',
			'type'=>'raw',
			'value'=>$this->show_img($model->refund_img4),
		),
		array(
			'name'=>'refund_img5',
			'type'=>'raw',
			'value'=>$this->show_img($model->refund_img5),
		),
		array(
			'name'=>'is_organizer',
		),
		array(
			'name'=>'push',
		),
		array(
			'name'=>'user_push',
		),
		array(
			'name'=>'push_orgainzer',
		),
		array(
			'name'=>'push_store',
		),
		array(
			'name'=>'push_agent',
		),
		array(
			'name'=>'refund_time',
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
			'name'=>'refund_type',
			'value'=>$model::$_refund_type[$model->refund_type],
		),
		array(
			'name'=>'refund_status',
			'value'=>$model::$_refund_status[$model->refund_status],
		),
		array(
			'name'=>'audit_status',
			'value'=>$model::$_audit_status[$model->audit_status],
		),
		array(
			'name'=>'pay_type',
			'value'=>$model::$_pay_type[$model->pay_type],
		),
		array(
			'name'=>'status',
			'value'=>$model::$_status[$model->status],
		),
	),
));

if($model->audit_status==Cash::audit_status_double) {
	?>
	<div class="row">
		<span class="buttons">
	<?php echo CHtml::ajaxButton('审核通过', array('/admin/tmm_refundLog/doubles_pass', 'id' => $model->id),
		array(
			'cache' => true,
			'success' => 'function(html){
					jQuery("#audit").html(html);
					$("#audit").dialog("open");
					$("#audit").dialog({"title":"审核通过"});
				}',
		)) ?>
		</span>
		<span class="buttons">
	<?php echo CHtml::ajaxButton('审核不通过', array('/admin/tmm_refundLog/doubles_nopass', 'id' => $model->id),
		array(
			'cache' => true,
			'success' => 'function(html){
					jQuery("#audit").html(html);
					$("#audit").dialog("open");
					$("#audit").dialog({"title":"审核不通过"});
				}',
		)) ?>
		</span>
	</div>
	<?php $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
		'id' => 'audit',//弹窗ID
		'options' => array(//传递给JUI插件的参数
			'title' => '审核',
			'autoOpen' => false,//是否自动打开
			'width' => '550px',//宽度
			'height' => 'auto',//高度
			'buttons' => array(//	'关闭'=>'js:function(){$(this).dialog("close");}',//关闭按钮
			),
		),
	));
	$this->endWidget();
}
?>

