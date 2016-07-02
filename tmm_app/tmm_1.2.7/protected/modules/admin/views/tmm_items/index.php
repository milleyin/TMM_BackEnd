<?php
/* @var $this Tmm_itemsController */
/* @var $model Items */

$this->breadcrumbs=array(
	'项目垃圾回收页',
);
?>
<h1>垃圾回收页 项目</h1>

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
	$.fn.yiiGridView.update('items-grid',{  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data){
	$.fn.yiiGridView.update('items-grid');  
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
	jQuery('#items-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#items-grid').yiiGridView('update');
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
	'id'=>'items-grid',
	'dataProvider'=>$model,
	'enableHistory'=>true,
	'columns'=>array(
		array(
				'name'=>'id',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:20px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'c_id',
				'value'=>'$data->Items_ItemsClassliy->name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:30px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Items_ItemsClassliy.info").":".$data->Items_ItemsClassliy->info'),
		),
		array(
				'name'=>'name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'agent_id',
				'value'=>'$data->Items_agent->phone',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Items_agent.firm_name").":".$data->Items_agent->firm_name'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'store_id',
                'value'=>'$data->Items_StoreContent->Content_Store->phone',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Items_StoreContent.name").":".$data->Items_StoreContent->name'),
		),
		array(
				'name'=>'manager_id',
				'value'=>'$data->Items_Store_Manager->phone',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'name'=>'area_id_p',
                'value'=>'(isset($data->area_id_p) && $data->area_id_p != 0 )? $data->Items_area_id_p_Area_id->name:"未选择"',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
                'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'name'=>'area_id_m',
                'value'=>'(isset($data->area_id_m) && $data->area_id_m != 0 )? $data->Items_area_id_m_Area_id->name:"未选择"',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
                'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
                'name'=>'area_id_c',
                'value'=>'(isset($data->area_id_c) && $data->area_id_c != 0 )? $data->Items_area_id_c_Area_id->name:"未选择"',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
                'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("address").":".$data->address'),
		),
        array(
            'name'=>'down',
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
        	'class'=>'DataColumn',
        	'evaluateHtmlOptions'=>true,
            'name'=>'push',
        	'value'=>'Push::executed($data->id,"push",$data->push)."%"',
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
            'htmlOptions'=>array('style'=>'text-align:center;','title'=>'	
						$data->getAttributeLabel("push").":".Push::executed($data->id,"push",$data->push)."%\n".
          				$data->getAttributeLabel("push_agent").":".Push::executed($data->id,"push_agent",$data->push_agent)."%\n".
            			$data->getAttributeLabel("push_store").":".Push::executed($data->id,"push_store",$data->push_store)."%\n".
            			$data->getAttributeLabel("push_orgainzer").":".Push::executed($data->id,"push_orgainzer",$data->push_orgainzer)."%\n"
				'),
        ),
		array(
				'name'=>'pub_time',
				'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
        array(     		
	        	'class'=>'DataColumn',
	        	'evaluateHtmlOptions'=>true,
	            'name'=>'add_time',
	            'type'=>'datetime',
	            'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
	            'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("up_time").":".Yii::app()->format->datetime($data->up_time)'),
        ),
        array(
                'name'=>'audit',
                'value'=>'$data::$_audit[$data->audit]',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
                'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'free_status',
				'value'=>'$data::$_free_status[$data->free_status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'status',
				'value'=>'$data::$_status[$data->status]',
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
						'url'=>'Yii::app()->createUrl("/admin/tmm_".$data->Items_ItemsClassliy->admin."/view",array("id"=>$data->id))',
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/view.png',
					),
					'restore'=>array(
						'label'=>'还原',
						//'visible'=>'$data->status==1',
						'url'=>'Yii::app()->createUrl("/admin/tmm_".$data->Items_ItemsClassliy->admin."/restore",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/restore.png',
					),
// 					'delete'=>array(
// 						'label'=>'彻底删除',
// 						'options'=>array('style'=>'padding:0 8px 0 0;'),
// 						'url'=>'Yii::app()->createUrl("/admin/tmm_".$data->Items_ItemsClassliy->admin."/clear",array("id"=>$data->id))',
// 						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/delete.png',
// 					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
		),
	),
)); 
?>
