<style type="text/css">
.content img{width:45% !important;height:45% !important;}
</style>
<?php
/* @var $this Tmm_eatController */
/* @var $model Eat */

$this->breadcrumbs=array(
	'项目管理页'=>array('/admin/tmm_items/admin'),
	'项目(吃)管理页'=>array('admin'),
	$model->Eat_Items->name,
);
?>

<h1>查看 项目(吃) <font color='#eb6100'><?php echo CHtml::encode($model->Eat_Items->name.
		(
			$model->Eat_Items->audit == Items::audit_nopass ? "（审核未通过原因：".AuditLog::get_audit_log(Items::$__audit[$model->Eat_Items->c_id],$model->Eat_Items->id)->info.'）' : ''
		)
); ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'id',
			),
		array(
				'name'=>'c_id',
				'value'=>$model->Eat_ItemsClassliy->name,
			),
		array(
				'name'=>'Eat_Items.name',
		),
		array(
				'label'=>'运营商公司名称',
				'name'=>'Eat_Items.Items_agent.firm_name',				
				'value'=>$model->Eat_Items->Items_agent->firm_name.' [ '.$model->Eat_Items->Items_agent->phone.' ]',
		),
		array(
				'label'=>'归属供应商手机',
				'name'=>'Eat_Items.Items_StoreContent.Content_Store.phone',				
				'value'=>$model->Eat_Items->Items_StoreContent->Content_Store->phone,
		),
		array(
				'name'=>'Eat_Items.manager_id',
				'value'=>$model->Eat_Items->Items_Store_Manager->phone,
		),
		array(
				'name'=>'Eat_Items.area_id_p',
				'value'=>$model->Eat_Items->Items_area_id_p_Area_id->name,
		),
		array(
				'name'=>'Eat_Items.area_id_m',
				'value'=>$model->Eat_Items->Items_area_id_m_Area_id->name,
		),
		array(
				'name'=>'Eat_Items.area_id_c',
				'value'=>$model->Eat_Items->Items_area_id_c_Area_id->name,
		),
		array(
				'name'=>'Eat_Items.address',
		),
		array(
				'name'=>'Eat_Items.push',
				'type'=>'raw',
				'value'=>Push::executed($model->id,'push',$model->Eat_Items->push).
				'% （默认分成：'.$model->Eat_Items->push.'%）'.
				CHtml::link('查看分成日志',array('/admin/tmm_push/admin','push_id'=>$model->id)),
		),
		array(
				'name'=>'Eat_Items.push_agent',
				'type'=>'raw',
				'value'=>Push::executed($model->id,'push_agent',$model->Eat_Items->push_agent).
				'% （默认分成：'.$model->Eat_Items->push_agent.'%）'.
				CHtml::link('查看分成日志',array('/admin/tmm_push/admin','push_id'=>$model->id)),
		),
		array(
				'name'=>'Eat_Items.push_store',
				'type'=>'raw',
				'value'=>Push::executed($model->id,'push_store',$model->Eat_Items->push_store).
				'% （默认分成：'.$model->Eat_Items->push_store.'%）'.
				CHtml::link('查看分成日志',array('/admin/tmm_push/admin','push_id'=>$model->id)),
		),
		array(
				'name'=>'Eat_Items.push_orgainzer',
				'type'=>'raw',
				'value'=>Push::executed($model->id,'push_orgainzer',$model->Eat_Items->push_orgainzer).
				'% （默认分成：'.$model->Eat_Items->push_orgainzer.'%）'.
				CHtml::link('查看分成日志',array('/admin/tmm_push/admin','push_id'=>$model->id)),
		),
		array(
				'name'=>'Eat_Items.audit',
				'value'=>Items::$_audit[$model->Eat_Items->audit],
		),
		array(
				'label'=>'标签',
				'type'=>'raw',
				'value'=>TagsElement::show_tags($model->Eat_TagsElement),
		),
		array(
				'label'=>'商品信息',
				'type'=>'raw',
				'value'=>Fare::show_fare($model->Eat_Fare,false),
		),
		array(
				'name'=>'Eat_Items.map',
				'type'=>'raw',
				'value'=>$this->show_img($model->Eat_Items->map),					
		),	
		array(
				'name'=>'Eat_Items.lng',
		),
		array(
				'name'=>'Eat_Items.lat',
		),
		array(
				'label'=>'概况图',
				'type'=>'raw',
				'value'=>ItemsImg::show_img($model->Eat_ItemsImg),
		),
		array(
				'name'=>'Eat_Items.content',
				'type'=>'raw',
				'template'=>"<tr class=\"{class}\"><th>{label}</th><td class=\"content\">{value}</td></tr>\n",			
		),
		array(
				'name'=>'Eat_Items.phone',
		),
		array(
				'name'=>'Eat_Items.weixin',
		),
		array(
				'name'=>'Eat_Items.down',
		),
		array(
				'name'=>'Eat_Items.start_work',
		),
		array(
				'name'=>'Eat_Items.end_work',
		),
		array(
				'name'=>'Eat_Items.pub_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'Eat_Items.add_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'Eat_Items.up_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'Eat_Items.free_status',
				'value'=>Items::$_free_status[$model->Eat_Items->free_status],
		),
		array(
				'name'=>'Eat_Items.status',
				'value'=>Items::$_status[$model->Eat_Items->status],
		),   
	),
));
	if($model->Eat_Items->audit==0){
?>
<div class="row">
		<span class="buttons">
	<?php		echo CHtml::ajaxButton('审核通过',array('/admin/tmm_eat/pass','id'=>$model->id),
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
	<?php		echo CHtml::ajaxButton('审核不通过',array('/admin/tmm_eat/nopass','id'=>$model->id),
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
	}
?>
