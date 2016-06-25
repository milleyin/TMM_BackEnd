<style type="text/css">
	.content img{width:45% !important;height:45% !important;}
</style>
<?php
/* @var $this ThrandController */
/* @var $model Thrand */

$this->breadcrumbs = array(
	'觅境管理页'=>array('/operator/shops/admin'),
	$model->Thrand_Shops->name,
);
?>

<h1>查看线路 <font color='#eb6100'><?php echo CHtml::encode($model->Thrand_Shops->name) ?></font></h1>
<?php 
	if ($model->Thrand_Shops->audit == Shops::audit_nopass)
	{
?>
<div style="background: #E5F1F4;padding:20px;margin:30px;font-size: 18px;border-radius: 5px;">
	<strong>
			审核未通过原因：
	</strong>
	<span  style="font-size: 16px;letter-spacing:2px;color:#ED1C24;">
		<?php echo AuditLog::get_audit_log(Shops::$__audit[$model->c_id],$model->id)->info;?>
	</span>
</div>
<?php 
	}
?>
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
				'name'=>'Thrand_Shops.audit',
				'value'=>Shops::$_audit[$model->Thrand_Shops->audit],
		),
		array(
				'name'=>'Thrand_Shops.is_sale',
				'value'=>Shops::$_is_sale[$model->Thrand_Shops->is_sale],
		),
		array(
				'name'=>'Thrand_Shops.status',
				'value'=>Shops::$_status[$model->Thrand_Shops->status],
		),
	),
));
if (isset($model->Thrand_Pro) && $model->Thrand_Pro)
	$this->renderPartial('_view/_dots', array(
			'model'=>$model,
	));
?>