<?php
/* @var $this Tmm_groupController */
/* @var $model Group */

$this->breadcrumbs=array(
	'线路管理页'=>array('/admin/tmm_shops/admin'),
	'结伴游管理页'=>array('admin'),
	$model->Group_Shops->name=>array('view','id'=>$model->id),
);
?>

<h1>查看 结伴游 <font color='#eb6100'><?php echo CHtml::encode($model->Group_Shops->name); ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'id',
		),
		array(
			'name'=>'Group_Shops.name',
			'value'=>$model->Group_Shops->name,
		),
		array(
			'name'=>'Group_ShopsClassliy.name',
		),
		array(
				'name'=>'user_id',
				'value'=>$model->Group_User->phone."[".$model->Group_User->nickname."]",
		),
		array(
			'name'=>'end_time',
			'type'=>'datetime',
		),
		array(
			'name'=>'group_time',
			'type'=>'datetime',
		),
		array(
			'name'=>'price',
		),
		array(
			'name'=>'Group_Shops.brow',
		),
		array(
			'name'=>'Group_Shops.share',
		),
		array(
			'name'=>'Group_Shops.praise',
		),
		array(
				'name'=>'Group_Shops.selected',
				'value'=>Shops::$_selected[$model->Group_Shops->selected],
		),
		array(
				'name'=>'Group_Shops.selected_info',
		),
		array(
				'name'=>'Group_Shops.selected_time',
				'type'=>'datetime',
		),
		array(
			'name'=>'Group_Shops.audit',
			'value'=>Shops::$_audit[$model->Group_Shops->audit],
		),
		array(
			'name'=>'group',
			'value'=>$model::$_group[$model->group],
		),
		array(
			'name'=>'status',
			'value'=>$model::$_status[$model->status],
		),
		array(
			'label'=>'标签',
			'type'=>'raw',
			'value'=>TagsElement::show_tags($model->Group_TagsElement),
		),
		array(
			'name'=>'Group_Shops.list_img',
			'type'=>'raw',
			'value'=>$this->show_img($model->Group_Shops->list_img),
		),
		array(
			'name'=>'Group_Shops.list_info',
		),
		array(
			'name'=>'Group_Shops.page_img',
			'type'=>'raw',
			'value'=>$this->show_img($model->Group_Shops->page_img),
		),
		array(
			'name'=>'Group_Shops.page_info',
		),
		array(
			'name'=>'remark',
		),
		array(
			'name'=>'pub_time',
			'type'=>'datetime',
		),
		array(
				'name'=>'start_time',
				'type'=>'datetime',
		),

	),
));


	$this->renderPartial('_items_view', array(
		'model'=>$model,
	));
if($model->Group_Shops->audit==Shops::audit_pending && $model->Group_Shops->status==0) {
	Yii::app()->clientScript->registerScript('search', "
jQuery('body').on('click','#pass',function(){
	if(!confirm('该项目是否审核通过！')) return false;
	jQuery.ajax({
		'cache':true,
		'url':'" . Yii::app()->createUrl('/admin/tmm_group/pass', array('id' => $model->id)) . "',
		'success':function(html){
			if(html==1)
				location.href = '" . Yii::app()->createUrl('/admin/tmm_group/view', array('id' => $model->id)) . "';
			else
				alert(html);
		},
	});
	return false;
});
");

	?>
	<div class="row">
		<span class="buttons">
			<?php echo CHtml::Button('审核通过', array('id' => 'pass')); ?>
		</span>
		<span class="buttons">
	<?php echo CHtml::ajaxButton('审核不通过', array('/admin/tmm_group/nopass', 'id' => $model->id),
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
