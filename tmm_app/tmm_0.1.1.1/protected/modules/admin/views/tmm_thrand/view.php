<style type="text/css">
	.content img{width:45% !important;height:45% !important;}
</style>
<?php
/* @var $this Tmm_thrandController */
/* @var $model Thrand */

$this->breadcrumbs=array(
	'线路管理页'=>array('/admin/tmm_thrand/admin'),
	'线路(线)管理页'=>array('admin'),
	$model->Thrand_Shops->name,

);
?>
<div style="min-width:1400px;">
<h1>查看 线路(线) <font color='#eb6100'><?php echo CHtml::encode($model->Thrand_Shops->name); ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'id',
		),
		array(
			'name'=>'Thrand_Shops.name',
		),
		array(
				'name'=>'Thrand_ShopsClassliy.name',			
		),
		array(
				'name'=>'Thrand_Shops.agent_id',
				'value'=>$model->Thrand_Shops->Shops_Agent->firm_name.' ['.$model->Thrand_Shops->Shops_Agent->phone.']'
		),
		array(
			'name'=>'Thrand_Shops.brow',
		),
		array(
			'name'=>'Thrand_Shops.share',
		),
		array(
			'name'=>'Thrand_Shops.praise',
		),
		array(
				'name'=>'Thrand_Shops.selected',
				'value'=>Shops::$_selected[$model->Thrand_Shops->selected],
		),
		array(
				'name'=>'Thrand_Shops.selected_info',
		),
		array(
				'name'=>'Thrand_Shops.selected_time',
				'type'=>'datetime',
		),
		array(
			'name'=>'Thrand_Shops.audit',
			'value'=>Shops::$_audit[$model->Thrand_Shops->audit],
		),
		array(
			'name'=>'Thrand_Shops.status',
			'value'=>Shops::$_status[$model->Thrand_Shops->status],
		),
		array(
			'name'=>'Dot_Shops.is_sale',
			'value'=>Shops::$_is_sale[$model->Thrand_Shops->is_sale],
		),
		array(
			'label'=>'标签',
			'type'=>'raw',
			'value'=>TagsElement::show_tags($model->Thrand_TagsElement),
		),
		array(
			'name'=>'Thrand_Shops.list_img',
			'type'=>'raw',
			'value'=>$this->show_img($model->Thrand_Shops->list_img),
		),
		array(
			'name'=>'Thrand_Shops.list_info',
		),
		array(
			'name'=>'Thrand_Shops.page_img',
			'type'=>'raw',
			'value'=>$this->show_img($model->Thrand_Shops->page_img),
		),
		array(
			'name'=>'Thrand_Shops.page_info',
		),
		array(
			'name'=>'Thrand_Shops.cost_info',
			'type'=>'raw',
			'template'=>"<tr class=\"{class}\"><th>{label}</th><td class=\"content\">{value}</td></tr>\n"
		),
		array(
			'name'=>'Thrand_Shops.book_info',
			'type'=>'raw',
			'template'=>"<tr class=\"{class}\"><th>{label}</th><td class=\"content\">{value}</td></tr>\n"
		),
		array(
				'name'=>'Thrand_Shops.pub_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'Thrand_Shops.add_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'Thrand_Shops.up_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'Thrand_Shops.tops',
				'value'=>Shops::$_tops[$model->Thrand_Shops->tops],
		),
		array(
				'name'=>'Thrand_Shops.tops_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'Thrand_Shops.selected_tops',
				'value'=>Shops::$_selected_tops[$model->Thrand_Shops->selected_tops],
		),
		array(
				'name'=>'Thrand_Shops.selected_tops_time',
				'type'=>'datetime',
		),
	),
)); 
if(isset($model->Thrand_Pro))
	$this->renderPartial('_items_view', array(
			'model'=>$model,
	));
	if($model->Thrand_Shops->audit==Shops::audit_pending && $model->Thrand_Shops->status==0)
	{
		Yii::app()->clientScript->registerScript('search', "
jQuery('body').on('click','#pass',function(){
	if(!confirm('该项目是否审核通过！')) return false;
	jQuery.ajax({
		'cache':true,
		'url':'".Yii::app()->createUrl('/admin/tmm_thrand/pass',array('id'=>$model->id))."',
		'success':function(html){
			if(html==1)
				location.href = '".Yii::app()->createUrl('/admin/tmm_thrand/view',array('id'=>$model->id))."';
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
				echo CHtml::ajaxButton('审核不通过',array('/admin/tmm_thrand/nopass','id'=>$model->id),
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
									'关闭'=>'js:function(){$(this).dialog("close");}',//关闭按钮
							),
					),
			));
			$this->endWidget();
	}
?>
</div>
