<style type="text/css">
.content img{width:45% !important;height:45% !important;}
</style>
<?php
/* @var $this Tmm_playController */
/* @var $model Play */

$this->breadcrumbs=array(
	'项目管理页'=>array('/operator/items/admin'),
	$model->Play_Items->name,
);
?>

<h1>
	查看 项目(玩) 
	<font color='#eb6100'><?php echo CHtml::encode($model->Play_Items->name.
		($model->Play_Items->audit == Items::audit_nopass ? "（审核未通过原因：".AuditLog::get_audit_log(Items::$__audit[$model->Play_Items->c_id],$model->Play_Items->id)->info.'）' : '')
	); ?>
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
				'value'=>$model->Play_ItemsClassliy->name,
			),
		array(
				'name'=>'Play_Items.name',
		),
		array(
				'name'=>'Play_Items.agent_id',
				'value'=>($model->Play_Items->Items_agent->id == Yii::app()->operator->id ?
						$model->Play_Items->Items_agent->phone : Yii::app()->controller->setHideKey($model->Play_Items->Items_agent->phone)
				) . ' [ ' . $model->Play_Items->Items_agent->firm_name . ' ]' ,
		),
		array(
				'name'=>'Play_Items.store_id',
				'value'=>($model->Play_Items->Items_agent->id == Yii::app()->operator->id ? $model->Play_Items->Items_StoreContent->Content_Store->phone:
						Yii::app()->controller->setHideKey($model->Play_Items->Items_StoreContent->Content_Store->phone)
				). ' [ ' . $model->Play_Items->Items_StoreContent->name . ' ]' ,
		),
		array(
				'name'=>'Play_Items.area_id_p',
				'value'=>$model->Play_Items->Items_area_id_p_Area_id->name,
		),
		array(
				'name'=>'Play_Items.area_id_m',
				'value'=>$model->Play_Items->Items_area_id_m_Area_id->name,
		),
		array(
				'name'=>'Play_Items.area_id_c',
				'value'=>$model->Play_Items->Items_area_id_c_Area_id->name,
		),
		array(
				'name'=>'Play_Items.address',
		),
		array(
				'name'=>'Play_Items.audit',
				'value'=>Items::$_audit[$model->Play_Items->audit],
		),
		array(
				'label'=>'标签',
				'type'=>'raw',
				'value'=>TagsElement::show_tags($model->Play_TagsElement),
		),
		array(
				'label'=>'商品信息',
				'type'=>'raw',
				'value'=>Fare::show_fare($model->Play_Fare,false),
		),
		array(
				'name'=>'Play_Items.map',
				'type'=>'raw',
				'value'=>$this->show_img($model->Play_Items->map),					
		),	
		array(
				'name'=>'Play_Items.lng',
		),		
		array(
			'name'=>'Play_Items.lat',
		),
		array(
				'label'=>'概况图',
				'type'=>'raw',
				'value'=>ItemsImg::show_img($model->Play_ItemsImg),
		),
		array(
				'name'=>'Play_Items.content',
				'type'=>'raw',
				'template'=>"<tr class=\"{class}\"><th>{label}</th><td class=\"content\">{value}</td></tr>\n"	
		),
		array(
				'name'=>'Play_Items.phone',
		),
		array(
				'name'=>'Play_Items.weixin',
		),
		array(
				'name'=>'Play_Items.start_work',
		),
		array(
				'name'=>'Play_Items.end_work',
		),
		array(
				'name'=>'Play_Items.pub_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'Play_Items.add_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'Play_Items.up_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'Play_Items.free_status',
				'value'=>Items::$_free_status[$model->Play_Items->free_status],
		),
		array(
				'name'=>'Play_Items.status',
				'value'=>Items::$_status[$model->Play_Items->status],
		),
	),
));
?>
