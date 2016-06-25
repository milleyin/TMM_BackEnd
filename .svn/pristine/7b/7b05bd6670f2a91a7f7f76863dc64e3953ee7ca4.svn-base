<style type="text/css">
	.content img{width:45% !important;height:45% !important;}
</style>
<?php
/* @var $this Tmm_dotController */
/* @var $model Dot */

$this->breadcrumbs=array(
	'线路管理页'=>array('/admin/tmm_shops/admin'),
	'线路(点)管理页'=>array('admin'),
	$model->Dot_Shops->name,
);
?>
<div style="min-width:1400px;">
<h1>查看 线路(点) <font color='#eb6100'><?php echo CHtml::encode($model->Dot_Shops->name); ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'id',
		),
		array(
				'name'=>'Dot_Shops.name',
		),
		array(
				'name'=>'Dot_ShopsClassliy.name',			
		),
		array(
				'name'=>'Dot_Shops.agent_id',
				'value'=>$model->Dot_Shops->Shops_Agent->firm_name.' ['.$model->Dot_Shops->Shops_Agent->phone.']'
		),
		array(
				'name'=>'Dot_Shops.brow',
		),
		array(
				'name'=>'Dot_Shops.share',
		),
		array(
				'name'=>'Dot_Shops.praise',
		),
		array(
				'name'=>'Dot_Shops.selected',
				'value'=>Shops::$_selected[$model->Dot_Shops->selected],
		),
		array(
				'name'=>'Dot_Shops.selected_info',
		),
		array(
				'name'=>'Dot_Shops.selected_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'Dot_Shops.audit',
				'value'=>Shops::$_audit[$model->Dot_Shops->audit],
		),

		array(
				'name'=>'Dot_Shops.status',
				'value'=>Shops::$_status[$model->Dot_Shops->status],
		),
		array(
			'name'=>'Dot_Shops.is_sale',
			'value'=>Shops::$_is_sale[$model->Dot_Shops->is_sale],
		),
		array(
			'label'=>'标签',
			'type'=>'raw',
			'value'=>TagsElement::show_tags($model->Dot_TagsElement),
		),
		array(
				'name'=>'Dot_Shops.list_img',
				'type'=>'raw',
				'value'=>$this->show_img($model->Dot_Shops->list_img),
		),
		array(
				'name'=>'Dot_Shops.list_info',
		),
		array(
				'name'=>'Dot_Shops.page_img',
				'type'=>'raw',
				'value'=>$this->show_img($model->Dot_Shops->page_img),
		),
		array(
				'name'=>'Dot_Shops.page_info',
		),
		array(
			'name'=>'Dot_Shops.cost_info',
			'type'=>'raw',
			'template'=>"<tr class=\"{class}\"><th>{label}</th><td class=\"content\">{value}</td></tr>\n"
		),
		array(
			'name'=>'Dot_Shops.book_info',
			'type'=>'raw',
			'template'=>"<tr class=\"{class}\"><th>{label}</th><td class=\"content\">{value}</td></tr>\n"
		),
		array(
				'name'=>'Dot_Shops.pub_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'Dot_Shops.add_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'Dot_Shops.up_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'Dot_Shops.tops',
				'value'=>Shops::$_tops[$model->Dot_Shops->tops],
		),
		array(
				'name'=>'Dot_Shops.tops_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'Dot_Shops.selected_tops',
				'value'=>Shops::$_selected_tops[$model->Dot_Shops->selected_tops],
		),
		array(
				'name'=>'Dot_Shops.selected_tops_time',
				'type'=>'datetime',
		),
	),
)); 

if(isset($model->Dot_Pro))
	$this->renderPartial('_items_view', array(
			'model'=>$model,
	));


	if($model->Dot_Shops->audit==Shops::audit_pending && $model->Dot_Shops->status==0)
	{
		Yii::app()->clientScript->registerScript('search', "
jQuery('body').on('click','#pass',function(){
	if(!confirm('该项目是否审核通过！')) return false;
	jQuery.ajax({
		'cache':true,
		'url':'".Yii::app()->createUrl('/admin/tmm_dot/pass',array('id'=>$model->id))."',
		'success':function(html){
			if(html==1)
				location.href = '".Yii::app()->createUrl('/admin/tmm_dot/view',array('id'=>$model->id))."';
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
			<?php		
				echo CHtml::Button('审核通过',array('id'=>'pass'));?>
				</span>
				<span class="buttons">
			<?php		
				echo CHtml::ajaxButton('审核不通过',array('/admin/tmm_dot/nopass','id'=>$model->id),
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
							'width'=>'800px',//宽度
							'height'=>'auto',//高度
							'modal' => true,
							'buttons'=>array(
								//	'关闭'=>'js:function(){$(this).dialog("close");}',//关闭按钮
							),
					),
			));
			$this->endWidget();
	}
?>
</div>