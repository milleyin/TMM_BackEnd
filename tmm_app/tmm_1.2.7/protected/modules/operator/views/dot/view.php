<style type="text/css">
	.content img{width:45% !important;height:45% !important;}
</style>
<?php
/* @var $this DotController */
/* @var $model Dot */

$this->breadcrumbs = array(
	'觅境管理页'=>array('/operator/shops/admin'),
	$model->Dot_Shops->name
);
?>
<h1>查看景点 <font color='#eb6100'><?php echo CHtml::encode($model->Dot_Shops->name) ?></font></h1>
<?php 
	if ($model->Dot_Shops->audit == Shops::audit_nopass)
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
				'name'=>'Dot_Shops.name',
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
				'name'=>'Dot_Shops.is_sale',
				'value'=>Shops::$_is_sale[$model->Dot_Shops->is_sale],
		),
		array(
				'name'=>'Dot_Shops.audit',
				'value'=>Shops::$_audit[$model->Dot_Shops->audit] ,
		),
		array(
				'name'=>'Dot_Shops.status',
				'value'=>Shops::$_status[$model->Dot_Shops->status],
		),
	),
)); 
if (isset($model->Dot_Pro) && $model->Dot_Pro)
	$this->renderPartial('_view/_items', array(
			'model'=>$model,
	));
?>
