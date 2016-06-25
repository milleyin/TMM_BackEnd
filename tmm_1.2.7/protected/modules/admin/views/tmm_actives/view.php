<?php
/* @var $this Tmm_activesController */
/* @var $model Actives */

$this->breadcrumbs=array(
	'商品管理页'=>array('/admin/tmm_shops/admin'),
	'觅趣管理页'=>array('admin'),
	$model->Actives_Shops->name=>array('view','id'=>$model->id),
);
?>

<h1>查看 活动 <font color='#eb6100'><?php echo CHtml::encode($model->Actives_Shops->name); ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'id',
		),
		array(
			'name'=>'Actives_Shops.name',
			'value'=>$model->Actives_Shops->name,
		),
		array(
			'name'=>'Actives_ShopsClassliy.name',
		),
		array(
				'name'=>'actives_type',
				'value'=>$model::$_actives_type[$model->actives_type],
		),
		array(
				'name'=>'organizer_id',
				'value'=>$model->Actives_User->phone."[".$model->Actives_User->nickname."]",
		),
		array(
			'name'=>'is_organizer',
			'value'=>$model::$_is_organizer[$model->is_organizer],
		),
		array(
				'name'=>'tour_type',
				'value'=>$model::$_tour_type[$model->tour_type],
		),
		array(
				'name'=>'tour_count',
		),
		array(
				'name'=>'order_count',
		),
		array(
				'name'=>'push',
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
				'name'=>'price',
		),
		array(
				'name'=>'number',
		),
		array(
				'name'=>'tour_price',
		),
		array(
				'name'=>'barcode',
		),
		array(
				'name'=>'remark',
				'type'=>'raw',
		),
		array(
				'name'=>'start_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'end_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'pub_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'go_time',
				'type'=>'datetime',
		),
		array(
			'name'=>'is_open',
			'value'=>$model::$_is_open[$model->is_open],
		),
		array(
			'name'=>'pay_type',
			'value'=>$model::$_pay_type[$model->pay_type],
		),
		array(
				'name'=>'actives_status',
				'value'=>$model::$_actives_status[$model->actives_status],
		),
		array(
				'name'=>'status',
				'value'=>$model::$_status[$model->status],
		),
		array(
				'name'=>'Actives_Shops.brow',
		),
		array(
				'name'=>'Actives_Shops.share',
		),
		array(
				'name'=>'Actives_Shops.praise',
		),
		array(
				'name'=>'Actives_Shops.selected',
				'value'=>Shops::$_selected[$model->Actives_Shops->selected],
		),
		array(
				'name'=>'Actives_Shops.selected_info',
		),
		array(
				'name'=>'Actives_Shops.selected_time',
				'type'=>'datetime',
		),
		array(
				'label'=>'标签',
				'type'=>'raw',
				'value'=>TagsElement::show_tags($model->Actives_TagsElement),
		),
		array(
				'name'=>'Actives_Shops.list_img',
				'type'=>'raw',
				'value'=>$this->show_img($model->Actives_Shops->list_img),
		),
		array(
				'name'=>'Actives_Shops.list_info',
		),
		array(
				'name'=>'Actives_Shops.page_img',
				'type'=>'raw',
				'value'=>$this->show_img($model->Actives_Shops->page_img),
		),
		array(
				'name'=>'Actives_Shops.page_info',
		),
		array(
			'name'=>'Actives_Shops.cost_info',
			'type'=>'raw',
			'template'=>"<tr class=\"{class}\"><th>{label}</th><td class=\"content\">{value}</td></tr>\n"
		),
		array(
			'name'=>'Actives_Shops.book_info',
			'type'=>'raw',
			'template'=>"<tr class=\"{class}\"><th>{label}</th><td class=\"content\">{value}</td></tr>\n"
		),
		array(
				'name'=>'Actives_Shops.audit',
				'value'=>Shops::$_audit[$model->Actives_Shops->audit],
		),
		array(
				'name'=>'Actives_Shops.is_sale',
				'value'=>Shops::$_is_sale[$model->Actives_Shops->is_sale],
		),
		array(
				'name'=>'Actives_Shops.hot',
				'value'=>Shops::$_hot[$model->Actives_Shops->hot],
		),
		array(
				'name'=>'Actives_Shops.hot_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'Actives_Shops.status',
				'value'=>Shops::$_status[$model->Actives_Shops->status],
		),
		array(
				'name'=>'Actives_Shops.tops',
				'value'=>Shops::$_tops[$model->Actives_Shops->tops],
		),
		array(
				'name'=>'Actives_Shops.tops_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'Actives_Shops.selected_tops',
				'value'=>Shops::$_selected_tops[$model->Actives_Shops->selected_tops],
		),
		array(
				'name'=>'Actives_Shops.selected_tops_time',
				'type'=>'datetime',
		),
	),
));
$this->renderPartial('_items_view', array(
	'model'=>$model,
));

if($model->Actives_Shops->audit==Shops::audit_pending && $model->Actives_Shops->status==Shops::status_offline) {
	Yii::app()->clientScript->registerScript('search', "
jQuery('body').on('click','#pass',function(){
	if(!confirm('该项目是否审核通过！')) return false;
	jQuery.ajax({
		'cache':true,
		'url':'" . Yii::app()->createUrl('/admin/tmm_actives/pass', array('id' => $model->id)) . "',
		'success':function(html){
			if(html==1)
				location.href = '" . Yii::app()->createUrl('/admin/tmm_actives/view', array('id' => $model->id)) . "';
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
				<?php		echo CHtml::ajaxButton('审核通过',array('/admin/tmm_actives/pass','id'=>$model->id),
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
	<?php echo CHtml::ajaxButton('审核不通过', array('/admin/tmm_actives/nopass', 'id' => $model->id),
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
