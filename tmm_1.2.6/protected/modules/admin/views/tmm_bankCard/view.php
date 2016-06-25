<?php
/* @var $this Tmm_bankCardController */
/* @var $model BankCard */
$this->breadcrumbs=array(
	'银行卡管理页'=>array('/admin/tmm_bankCard/admin'),
	$model->id,
);
?>

<h1>查看 银行卡管理详情 <font color='#eb6100'><?php echo $model->id; ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'id',
		),
		array(
			'name'=>'manage_who',
			'value'=>$model::$_manage_who[$model->manage_who],
		),
		array(
			'name'=>'manage_id',
			'value'=>$model::getManageRoleName($model,$model->card_type)
		),
		array(
			'name'=>'card_type',
			'value'=>$model::$_card_type[$model->card_type],
		),
		array(
			'name'=>'card_id',
			'value'=>$model::getRoleName($model,$model->card_type)
		),
		array(
			'name'=>'bank_id',
			'value'=>$model->BankCard_Bank->name,
		),
		array(
			'name'=>'bank_name',
		),
		array(
			'name'=>'bank_branch',
		),
		array(
			'name'=>'bank_code',
		),
		array(
			'name'=>'bank_identity',
		),
		array(
			'name'=>'bank_type',
			'value'=>$model::$_bank_type[$model->bank_type],
		),
		array(
			'name'=>'is_default',
			'value'=>$model::$_is_default[$model->is_default],
		),
		array(
			'name'=>'is_verify',
			'value'=>$model::$_is_verify[$model->is_verify],
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
/*
?>
<div class="row">
		<span class="buttons">
	<?php		echo CHtml::ajaxButton('审核通过',array('/admin/tmm_bankCard/pass','id'=>$model->id),
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
	<?php		echo CHtml::ajaxButton('审核不通过',array('/admin/tmm_bankCard/nopass','id'=>$model->id),
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
					'modal' => true,
					'buttons'=>array(
						//	'关闭'=>'js:function(){$(this).dialog("close");}',//关闭按钮
					),
			),
	));
	$this->endWidget();
 */
?>
