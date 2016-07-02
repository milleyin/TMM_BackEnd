<?php
/* @var $this Tmm_cashController */
/* @var $model Cash */

$this->breadcrumbs=array(
	'结算申请管理页'=>array('admin'),
	$model->bank_name,
	$model::$_audit_status[$model->audit_status]
);
?>

<h1>查看 结算申请 <font color='#eb6100'><?php echo $model->bank_name; echo $model::$_audit_status[$model->audit_status];?></font></h1>

<?php $this->renderPartial('_view',array(
	'model'=>$model,
)); ?>

<?php

if($model->audit_status==Cash::audit_status_double) {
	?>
	<div class="row">
		<span class="buttons">
	<?php echo CHtml::ajaxButton('审核通过', array('/admin/tmm_cash/doubles_pass', 'id' => $model->id),
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
	<?php echo CHtml::ajaxButton('审核不通过', array('/admin/tmm_cash/doubles_nopass', 'id' => $model->id),
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

