<style type="text/css">
.content img{width:45% !important;height:45% !important;}
</style>
<?php
/* @var $this HotelController */
/* @var $model Hotel */

$this->breadcrumbs=array(
	'项目管理页'=>array('/operator/items/admin'),
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
				'name'=>'Hotel_Items.agent_id',
				'value'=>($model->Hotel_Items->Items_agent->id == Yii::app()->operator->id ?
						$model->Hotel_Items->Items_agent->phone : Yii::app()->controller->setHideKey($model->Hotel_Items->Items_agent->phone)
				) . ' [ ' . $model->Hotel_Items->Items_agent->firm_name . ' ]' ,
		),
		array(
				'name'=>'Hotel_Items.store_id',
				'value'=>($model->Hotel_Items->Items_agent->id == Yii::app()->operator->id ? $model->Hotel_Items->Items_StoreContent->Content_Store->phone:
						Yii::app()->controller->setHideKey($model->Hotel_Items->Items_StoreContent->Content_Store->phone)
				). ' [ ' . $model->Hotel_Items->Items_StoreContent->name . ' ]' ,
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
?>
