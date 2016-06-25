<?php
/* @var $this Tmm_storeController */
/* @var $model StoreUser */

$this->breadcrumbs=array(
	'供应商子账号垃圾回收页',
);
?>
<h1>供应商子账号垃圾回收页</h1>
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
			'name'=>'count',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'name'=>'error_count',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'name'=>'Store_Store.Store_Content.lx_phone',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'DataColumn',
			'evaluateHtmlOptions'=>true,
			'name'=>'Store_Store.Store_Content.lx_contacts',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'DataColumn',
			'evaluateHtmlOptions'=>true,
			'name'=>'Store_Store.phone',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'name'=>'Store_Store.Store_Content.name',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'name'=>'last_time',
			'type'=>'datetime',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
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
			'name'=>'status',
			'value'=>'$data::$_status[$data->status]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
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
                        'url'=>'Yii::app()->createUrl("/admin/tmm_store/viewson",array("id"=>$data->id))',
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/view.png',
					),
					'restore'=>array(
						'label'=>'还原',
						//'visible'=>'$data->status==1',
						'url'=>'Yii::app()->createUrl("/admin/tmm_store/restoreSon",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/restore.png',
					),
					'delete'=>array(
						'label'=>'彻底删除',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'url'=>'Yii::app()->createUrl("/admin/tmm_store/clearSon",array("id"=>$data->id))',
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/delete.png',
					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
		),
	),
)); 
?>
