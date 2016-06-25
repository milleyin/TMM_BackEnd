<?php
/* @var $this Tmm_smsLogController */
/* @var $model SmsLog */

$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	$model->id,
);
?>

<h1>查看 SmsLog <font color='#eb6100'><?php echo $model->id; ?></font></h1>

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
				'name'=>'sms_id',
		),
		array(
				'name'=>'sms_type',
		),
		array(
				'name'=>'role_id',
		),
		array(
				'name'=>'role_type',
		),
		array(
				'name'=>'sms_use',
		),
		array(
				'name'=>'code',
		),
		array(
				'name'=>'sms_content',
		),
		array(
				'name'=>'sms_source',
		),
		array(
				'name'=>'sms_ip',
		),
		array(
				'name'=>'login_address',
		),
		array(
				'name'=>'error_count',
		),
		array(
				'name'=>'sms_error',
		),
		array(
				'name'=>'end_time',
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
)); ?>
<div class="row">
		<span class="buttons">
	<?php		echo CHtml::ajaxButton('审核通过',array('/admin/tmm_smsLog/pass','id'=>$model->id),
		array(
				'cache'=>true,
				'success'=>'function(html){
					jQuery("#audit").html(html);
					$("#audit").dialog("open");
					$("#audit").dialog({"title":"审核通过"});
				}',
		))?>
		</span>
		<span class="buttons">
	<?php		echo CHtml::ajaxButton('审核不通过',array('/admin/tmm_smsLog/nopass','id'=>$model->id),
		array(
				'cache'=>true,
				'success'=>'function(html){
					jQuery("#audit").html(html);
					$("#audit").dialog("open");
					$("#audit").dialog({"title":"审核不通过"});
				}',	
		))?>
		</span>
</div>
<?php	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
			'id'=>'audit',//弹窗ID
			'options'=>array(//传递给JUI插件的参数
					'title'=>'审核',
					'autoOpen'=>false,//是否自动打开
					'width'=>'550px',//宽度
					'height'=>'auto',//高度
					'buttons'=>array(
						//	'关闭'=>'js:function(){$(this).dialog("close");}',//关闭按钮
					),
			),
	));
	$this->endWidget();
?>
