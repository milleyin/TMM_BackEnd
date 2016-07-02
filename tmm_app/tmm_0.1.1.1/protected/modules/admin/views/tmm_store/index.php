<?php
/* @var $this Tmm_storeController */
/* @var $model StoreUser */

$this->breadcrumbs=array(
	'供应商账号垃圾回收页',
);
?>
<h1>供应商账号垃圾回收页</h1>

<?php
  $Confirmation= "你确定执行此项操作？";
	if(Yii::app()->request->enableCsrfValidation)
	{
		$csrfTokenName = Yii::app()->request->csrfTokenName;
		$csrfToken = Yii::app()->request->csrfToken;
		$csrf = "\n\t\tdata:{ '$csrfTokenName':'$csrfToken'},";
	}else
		$csrf = '';
$click_alert=<<<EOD
function(){ 
	if(!confirm("$Confirmation")) return false; 
	var th=this;  
	var afterDelete=function(link,success,data){ if(success) alert(data);};  
	$.fn.yiiGridView.update('store-user-grid',{  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data){
	$.fn.yiiGridView.update('store-user-grid');  
	   afterDelete(th,true,data);  
	},
	error:function(XHR){
	   return afterDelete(th,false,XHR);
	}
  });
    return false;
}
EOD;

$click=<<<EOD
function() {  
	if(!confirm("$Confirmation")) return false;
	var th = this,
	afterDelete = function(){};
	jQuery('#store-user-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#store-user-grid').yiiGridView('update');
			afterDelete(th, true, data);
		},
		error: function(XHR) {
			return afterDelete(th, false, XHR);
		}
});
    return false;
}
EOD;

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'store-user-grid',
	'dataProvider'=>$model,
	'enableHistory'=>true,
	'columns'=>array(
		array(
			'name'=>'id',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:20px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'name'=>'phone',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'name'=>'Store_Content.name',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'name'=>'Store_Content.area_id_p',
			'value'=>'$data->Store_Content->Content_area_id_p_Area_id->name',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'name'=>'Store_Content.area_id_m',
			'value'=>'$data->Store_Content->Content_area_id_m_Area_id->name',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'DataColumn',
			'evaluateHtmlOptions'=>true,
			'name'=>'Store_Content.area_id_c',
			'value'=>'$data->Store_Content->Content_area_id_c_Area_id->name',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
			'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Store_Content.address").":".$data->Store_Content->address'),
		),
		array(
			'class'=>'DataColumn',
			'evaluateHtmlOptions'=>true,
			'name'=>'agent_id',
			'value'=>'$data->Store_Agent->phone',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Store_Agent.firm_name").":".$data->Store_Agent->firm_name'),
		),
		array(
			'class'=>'DataColumn',
			'evaluateHtmlOptions'=>true,
			'name'=>'Store_Content.son_count',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
			'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Store_Content.son_limit").":".$data->Store_Content->son_limit'),
		),
		array(
			'name'=>'Store_Content.deposit',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:20px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'DataColumn',
			'evaluateHtmlOptions'=>true,
			'name'=>'login_time',
			'type'=>'datetime',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
			'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("login_ip").":".$data->login_ip'),
		),
		array(
			'class'=>'DataColumn',
			'evaluateHtmlOptions'=>true,
			'name'=>'last_time',
			'type'=>'datetime',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
			'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("last_ip").":".$data->last_ip'),
		),
		array(
			'name'=>'add_time',
			'type'=>'datetime',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'name'=>'up_time',
			'type'=>'datetime',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}{restore}{delete}',
			'buttons'=>array(
					'view'=>array(
						'label'=>'查看',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/view.png',
					),
					'restore'=>array(
						'label'=>'还原',
						'url'=>'Yii::app()->createUrl("/admin/tmm_store/restore",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/restore.png',
					),
					'delete'=>array(
						'label'=>'彻底删除',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'url'=>'Yii::app()->createUrl("/admin/tmm_store/clear",array("id"=>$data->id))',
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/delete.png',
					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
		),
	),
)); 
?>
