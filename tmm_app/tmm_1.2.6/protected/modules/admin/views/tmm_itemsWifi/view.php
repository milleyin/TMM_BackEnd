<?php
/* @var $this Tmm_itemsWifiController */
/* @var $model ItemsWifi */

$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	$model->id,
);
?>

<h1>查看 ItemsWifi <font color='#eb6100'><?php echo $model->id; ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'id',
			),
		array(
				'name'=>'agent_id',
			),
		array(
				'name'=>'item_id',
			),
		array(
				'name'=>'wifi_id',
			),
		array(
				'name'=>'sort',
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
<div class="row">
		<span class="buttons">
	<?php		echo CHtml::ajaxButton('审核通过',array('/admin/tmm_itemsWifi/pass'),array(),array(
			'onclick'=>'$("#pass").dialog("open"); return false;'
		))?>
		</span>
		<span class="buttons">
	<?php		echo CHtml::ajaxButton('审核不通过',array('/admin/tmm_itemsWifi/nopass'),array(),array(
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
