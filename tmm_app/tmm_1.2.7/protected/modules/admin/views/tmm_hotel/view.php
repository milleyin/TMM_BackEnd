<style type="text/css">
.content img{width:45% !important;height:45% !important;}
</style>
<?php
/* @var $this Tmm_hotelController */
/* @var $model Hotel */

$this->breadcrumbs=array(
	'项目管理页'=>array('/admin/tmm_items/admin'),
	'项目(住)管理页'=>array('admin'),
	$model->Hotel_Items->name,
);
?>
<h1>查看 项目(住) <font color='#eb6100'><?php echo CHtml::encode($model->Hotel_Items->name.
(
	$model->Hotel_Items->audit == Items::audit_nopass ? "（审核未通过原因：".AuditLog::get_audit_log(Items::$__audit[$model->Hotel_Items->c_id],$model->Hotel_Items->id)->info.'）' : ''	
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
				'value'=>$model->Hotel_ItemsClassliy->name,
		),
		array(
				'name'=>'Hotel_Items.name',
		),
		array(
				'label'=>'运营商公司名称',
				'name'=>'Hotel_Items.Items_agent.firm_name',				
				'value'=>$model->Hotel_Items->Items_agent->firm_name.' [ '.$model->Hotel_Items->Items_agent->phone.' ]',
		),
		array(
				'label'=>'归属供应商手机',
				'name'=>'Hotel_Items.Items_StoreContent.Content_Store.phone',				
				'value'=>$model->Hotel_Items->Items_StoreContent->Content_Store->phone,
		),
		array(
				'name'=>'Hotel_Items.manager_id',
				'value'=>$model->Hotel_Items->Items_Store_Manager->phone,
		),
		array(
				'name'=>'Hotel_Items.area_id_p',
				'value'=>$model->Hotel_Items->Items_area_id_p_Area_id->name,
		),
		array(
				'name'=>'Hotel_Items.area_id_m',
				'value'=>$model->Hotel_Items->Items_area_id_m_Area_id->name,
		),
		array(
				'name'=>'Hotel_Items.area_id_c',
				'value'=>$model->Hotel_Items->Items_area_id_c_Area_id->name,
		),
		array(
				'name'=>'Hotel_Items.address',
		),
		array(
				'name'=>'Hotel_Items.push',
				'type'=>'raw',
				'value'=>Push::executed($model->id,'push',$model->Hotel_Items->push).
				'% （默认分成：'.$model->Hotel_Items->push.'%）'.
				CHtml::link('查看分成日志',array('/admin/tmm_push/admin','push_id'=>$model->id)),
		),
		array(
				'name'=>'Hotel_Items.push_agent',
				'type'=>'raw',
				'value'=>Push::executed($model->id,'push_agent',$model->Hotel_Items->push_agent).
				'% （默认分成：'.$model->Hotel_Items->push_agent.'%）'.
				CHtml::link('查看分成日志',array('/admin/tmm_push/admin','push_id'=>$model->id)),
		),
		array(
				'name'=>'Hotel_Items.push_store',
				'type'=>'raw',
				'value'=>Push::executed($model->id,'push_store',$model->Hotel_Items->push_store).
				'% （默认分成：'.$model->Hotel_Items->push_store.'%）'.
				CHtml::link('查看分成日志',array('/admin/tmm_push/admin','push_id'=>$model->id)),
		),
		array(
				'name'=>'Hotel_Items.push_orgainzer',
				'type'=>'raw',
				'value'=>Push::executed($model->id,'push_orgainzer',$model->Hotel_Items->push_orgainzer).
				'% （默认分成：'.$model->Hotel_Items->push_orgainzer.'%）'.
				CHtml::link('查看分成日志',array('/admin/tmm_push/admin','push_id'=>$model->id)),
		),
		array(
				'name'=>'Hotel_Items.audit',
				'value'=>Items::$_audit[$model->Hotel_Items->audit],
		),
		array(
				'label'=>'标签',
				'type'=>'raw',
				'value'=>TagsElement::show_tags($model->Hotel_TagsElement),
		),
		array(
				'label'=>'商品信息',
				'type'=>'raw',
				'value'=>Fare::show_fare($model->Hotel_Fare,true),
		),
		array(
				'label'=>'酒店服务',
				'type'=>'raw',
				'value'=>ItemsWifi::show_wifi($model->Hotel_ItemsWifi),
		),
		array(
				'name'=>'Hotel_Items.map',
				'type'=>'raw',
				'value'=>$this->show_img($model->Hotel_Items->map),					
		),	
		array(
				'name'=>'Hotel_Items.lng',
		),
		array(
				'name'=>'Hotel_Items.lat',
		),
		array(
				'label'=>'概况图',
				'type'=>'raw',
				'value'=>ItemsImg::show_img($model->Hotel_ItemsImg),
		),
		array(
				'name'=>'Hotel_Items.content',
				'type'=>'raw',
				'template'=>"<tr class=\"{class}\"><th>{label}</th><td class=\"content\">{value}</td></tr>\n"
		),
		array(
				'name'=>'Hotel_Items.phone',
		),
		array(
				'name'=>'Hotel_Items.weixin',
		),
		array(
				'name'=>'Hotel_Items.down',
		),
		array(
				'name'=>'Hotel_Items.start_work',
		),
		array(
				'name'=>'Hotel_Items.end_work',
		),
		array(
				'name'=>'Hotel_Items.pub_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'Hotel_Items.add_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'Hotel_Items.up_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'Hotel_Items.free_status',
				'value'=>Items::$_free_status[$model->Hotel_Items->free_status],
		),
		array(
				'name'=>'Hotel_Items.status',
				'value'=>Items::$_status[$model->Hotel_Items->status],
		),   
	),
));
	if($model->Hotel_Items->audit==0){
?>
<div class="row">
		<span class="buttons">
	<?php		echo CHtml::ajaxButton('审核通过',array('/admin/tmm_hotel/pass','id'=>$model->id),
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
	<?php		echo CHtml::ajaxButton('审核不通过',array('/admin/tmm_hotel/nopass','id'=>$model->id),
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
	}
?>
