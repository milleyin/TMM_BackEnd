<style type="text/css">
.content img{width:45% !important;height:45% !important;}
</style>
<?php
/* @var $this EatController */
/* @var $model Eat */

$this->breadcrumbs=array(
	'项目管理页'=>array('/operator/items/admin'),
	$model->Eat_Items->name,
);
?>

<h1>查看 项目（吃） 
	<font color='#eb6100'>
	<?php 
			echo CHtml::encode($model->Eat_Items->name.
			( $model->Eat_Items->audit == Items::audit_nopass ? 
					"（审核未通过原因：".AuditLog::get_audit_log(Items::$__audit[$model->Eat_Items->c_id],$model->Eat_Items->id)->info.'）' 
					: '')
			);
	?>
	</font>
</h1>

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
				'name'=>'Eat_Items.agent_id',
				'value'=>($model->Eat_Items->Items_agent->id == Yii::app()->operator->id ?
						 $model->Eat_Items->Items_agent->phone : Yii::app()->controller->setHideKey($model->Eat_Items->Items_agent->phone)
						) . ' [ ' . $model->Eat_Items->Items_agent->firm_name . ' ]' ,
		),
		array(
				'name'=>'Eat_Items.store_id',
				'value'=>($model->Eat_Items->Items_agent->id == Yii::app()->operator->id ? $model->Eat_Items->Items_StoreContent->Content_Store->phone:
							Yii::app()->controller->setHideKey($model->Eat_Items->Items_StoreContent->Content_Store->phone)
						). ' [ ' . $model->Eat_Items->Items_StoreContent->name . ' ]' ,
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
?>
