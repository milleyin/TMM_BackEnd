<?php
/* @var $this Tmm_storeContentController */
/* @var $model StoreContent */

$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	$model->name,
);
?>

<h1>查看 StoreContent <font color='#eb6100'><?php echo CHtml::encode($model->name); ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'id',
			),
		array(
				'name'=>'name',
			),
		array(
				'name'=>'push',
			),
		array(
				'name'=>'income_count',
			),
		array(
				'name'=>'cash',
			),
		array(
				'name'=>'money',
			),
		array(
				'name'=>'deposit',
			),
		array(
				'name'=>'area_id_p',
			),
		array(
				'name'=>'area_id_m',
			),
		array(
				'name'=>'area_id_c',
			),
		array(
				'name'=>'address',
			),
		array(
				'name'=>'store_tel',
			),
		array(
				'name'=>'store_postcode',
			),
		array(
				'name'=>'lx_contacts',
			),
		array(
				'name'=>'lx_identity_code',
			),
		array(
				'name'=>'lx_phone',
			),
		array(
				'name'=>'identity_before',
			),
		array(
				'name'=>'identity_after',
			),
		array(
				'name'=>'identity_hand',
			),
		array(
				'name'=>'com_contacts',
			),
		array(
				'name'=>'com_phone',
			),
		array(
				'name'=>'com_identity',
			),
		array(
				'name'=>'bl_code',
			),
		array(
				'name'=>'bl_img',
			),
		array(
				'name'=>'bank_id',
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
				'name'=>'son_count',
			),
		array(
				'name'=>'son_limit',
			),
		array(
				'name'=>'audit',
			),
		array(
				'name'=>'up_time',
				'type'=>'datetime',
			),
		array(
				'name'=>'pass_time',
				'type'=>'datetime',
			),
		array(
				'name'=>'add_time',
				'type'=>'datetime',
			),
	),
)); ?>
<div class="row">
		<span class="buttons">
	<?php		echo CHtml::ajaxButton('审核通过',array('/admin/tmm_storeContent/pass'),array(),array(
			'onclick'=>'$("#pass").dialog("open"); return false;'
		))?>
		</span>
		<span class="buttons">
	<?php		echo CHtml::ajaxButton('审核不通过',array('/admin/tmm_storeContent/nopass'),array(),array(
			'onclick'=>'$("#nopass").dialog("open"); return false;'
		))?>
		</span>
</div>
<?php	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
		'id'=>'pass',//弹窗ID
		'options'=>array(//传递给JUI插件的参数
				'title'=>'审核详情',
				'autoOpen'=>false,//是否自动打开
				'width'=>'auto',//宽度
				'height'=>'auto',//高度
				'buttons'=>array(
						'关闭'=>'js:function(){$(this).dialog("close");}',//关闭按钮
				),
		),
));
?>
<?php	//$this->renderPartial('_form',array(
	//	'model'=>new Admin				
//));
?>
<?php	$this->endWidget();
?>
<?php	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
		'id'=>'nopass',//弹窗ID
		'options'=>array(//传递给JUI插件的参数
				'title'=>'审核详情',
				'autoOpen'=>false,//是否自动打开
				'width'=>'auto',//宽度
				'height'=>'auto',//高度
				'buttons'=>array(
						'关闭'=>'js:function(){$(this).dialog("close");}',//关闭按钮
				),
		),
));
?>
<?php	//$this->renderPartial('_form',array(
	//	'model'=>new Admin				
//));
?>
<?php	$this->endWidget();
?>
