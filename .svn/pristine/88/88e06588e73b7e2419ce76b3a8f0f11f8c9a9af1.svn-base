<?php
/* @var $this Tmm_proController */
/* @var $model Pro */

$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	$model->id,
);
?>

<h1>查看 Pro <font color='#eb6100'><?php echo $model->id; ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'id',
		),
		array(
				'name'=>'shops_id',
		),
		array(
				'name'=>'agent_id',
		),
		array(
				'name'=>'store_id',
		),
		array(
				'name'=>'shops_c_id',
		),
		array(
				'name'=>'c_id',
		),
		array(
				'name'=>'sort',
		),
		array(
				'name'=>'day_sort',
		),
		array(
				'name'=>'half_sort',
		),
		array(
				'name'=>'items_id',
		),
		array(
				'name'=>'dot_id',
		),
		array(
				'name'=>'thrand_id',
		),
		array(
				'name'=>'info',
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
				'name'=>'audit',
		),
		array(
				'name'=>'status',
				'value'=>$model::$_status[$model->status],
		),
	),
)); ?>
<div class="row">
		<span class="buttons">
	<?php		echo CHtml::ajaxButton('审核通过',array('/admin/tmm_pro/pass','id'=>$model->id),
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
	<?php		echo CHtml::ajaxButton('审核不通过',array('/admin/tmm_pro/nopass','id'=>$model->id),
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
