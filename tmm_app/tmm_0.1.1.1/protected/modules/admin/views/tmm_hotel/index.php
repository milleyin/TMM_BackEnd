<?php
/* @var $this Tmm_hotelController */
/* @var $model Hotel */

$this->breadcrumbs=array(
	'垃圾回收页',
);
?>
<h1>垃圾回收页 项目(住)</h1>

<?php
  $Confirmation= "你确定执行此项操作？";
	if(Yii::app()->request->enableCsrfValidation)
	{
		$csrfTokenName = Yii::app()->request->csrfTokenName;
		$csrfToken = Yii::app()->request->csrfToken;
		$csrf = "\n\t\tdata:{ '$csrfTokenName':'$csrfToken'},";
	}else
		$csrf = '';
$click_alert=<<<"EOD"
function(){ 
	if(!confirm("$Confirmation")) return false; 
	var th=this;  
	var afterDelete=function(link,success,data){ if(success) alert(data);};  
	$.fn.yiiGridView.update('hotel-grid',{  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data){
	$.fn.yiiGridView.update('hotel-grid');  
	   afterDelete(th,true,data);  
	},
	error:function(XHR){
	   return afterDelete(th,false,XHR);
	}
  });
    return false;
}
EOD;

$click=<<<"EOD"
function() {  
	if(!confirm("$Confirmation")) return false;
	var th = this,
	afterDelete = function(){};
	jQuery('#hotel-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#hotel-grid').yiiGridView('update');
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
	'id'=>'hotel-grid',
	'dataProvider'=>$model,
	'enableHistory'=>true,
	'columns'=>array(
		array(
				'name'=>'id',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:20px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'Hotel_Items.name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'Hotel_Items.agent_id',
				'value'=>'$data->Hotel_Items->Items_agent->phone',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Hotel_Items.Items_agent.firm_name").":".$data->Hotel_Items->Items_agent->firm_name'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'Hotel_Items.store_id',
				'value'=>'$data->Hotel_Items->Items_StoreContent->Content_Store->phone',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Hotel_Items.Items_StoreContent.name").":".$data->Hotel_Items->Items_StoreContent->name'),
		),
		array(
				'name'=>'Hotel_Items.manager_id',
				'value'=>'$data->Hotel_Items->Items_Store_Manager->phone',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'Hotel_Items.area_id_p',
				'value'=>'(isset($data->Hotel_Items->area_id_p) && $data->Hotel_Items->area_id_p != 0 )? $data->Hotel_Items->Items_area_id_p_Area_id->name:"未选择"',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'Hotel_Items.area_id_m',
				'value'=>'(isset($data->Hotel_Items->area_id_m) && $data->Hotel_Items->area_id_m != 0 )? $data->Hotel_Items->Items_area_id_m_Area_id->name:"未选择"',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'Hotel_Items.area_id_c',
				'value'=>'(isset($data->Hotel_Items->area_id_c) && $data->Hotel_Items->area_id_c != 0 )? $data->Hotel_Items->Items_area_id_c_Area_id->name:"未选择"',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Hotel_Items.address").":".$data->Hotel_Items->address'),
		),
		array(
				'name'=>'Hotel_Items.down',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'Hotel_Items.push',
				'value'=>'Push::executed($data->id,"push",$data->Hotel_Items->push)."%"',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("Hotel_Items.push").":".Push::executed($data->id,"push",$data->Hotel_Items->push)."%\n".
          			$data->getAttributeLabel("Hotel_Items.push_agent").":".Push::executed($data->id,"push_agent",$data->Hotel_Items->push_agent)."%\n".
            		$data->getAttributeLabel("Hotel_Items.push_store").":".Push::executed($data->id,"push_store",$data->Hotel_Items->push_store)."%\n".
            		$data->getAttributeLabel("Hotel_Items.push_orgainzer").":".Push::executed($data->id,"push_orgainzer",$data->Hotel_Items->push_orgainzer)."%\n"
				'),
		),
		array(
				'name'=>'Hotel_Items.pub_time',
				'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'Hotel_Items.add_time',
				'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Hotel_Items.up_time").":".Yii::app()->format->datetime($data->Hotel_Items->up_time)'),
		),
		array(
				'name'=>'Hotel_Items.audit',
				'value'=>'Items::$_audit[$data->Hotel_Items->audit]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'Hotel_Items.free_status',
				'value'=>'Items::$_free_status[$data->Hotel_Items->free_status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'Hotel_Items.status',
				'value'=>'Items::$_status[$data->Hotel_Items->status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}{restore}',
			'buttons'=>array(
					'view'=>array(
						'label'=>'查看',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/view.png',
					),
					'restore'=>array(
						'label'=>'还原',
						'url'=>'Yii::app()->createUrl("/admin/tmm_hotel/restore",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/restore.png',
					),
// 					'delete'=>array(
// 						'label'=>'彻底删除',
// 						'options'=>array('style'=>'padding:0 8px 0 0;'),
// 						'url'=>'Yii::app()->createUrl("/admin/tmm_hotel/clear",array("id"=>$data->id))',	
// 						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/delete.png',
// 					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
		),
	),
)); 
?>
