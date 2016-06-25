<?php
/* @var $this Tmm_playController */
/* @var $model Play */

$this->breadcrumbs=array(
	'垃圾回收页',
);
?>
<h1>垃圾回收页 项目(玩)</h1>

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
	$.fn.yiiGridView.update('play-grid',{  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data){
	$.fn.yiiGridView.update('play-grid');  
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
	jQuery('#play-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#play-grid').yiiGridView('update');
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
	'id'=>'play-grid',
	'dataProvider'=>$model,
	'enableHistory'=>true,
	'columns'=>array(
		array(
			'name'=>'id',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:20px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'name'=>'Play_Items.name',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'DataColumn',
			'evaluateHtmlOptions'=>true,
			'name'=>'Play_Items.agent_id',
			'value'=>'$data->Play_Items->Items_agent->phone',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Play_Items.Items_agent.firm_name").":".$data->Play_Items->Items_agent->firm_name'),
		),
		array(
			'class'=>'DataColumn',
			'evaluateHtmlOptions'=>true,
			'name'=>'Play_Items.store_id',
			'value'=>'$data->Play_Items->Items_StoreContent->Content_Store->phone',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Play_Items.Items_StoreContent.name").":".$data->Play_Items->Items_StoreContent->name'),
		),
		array(
			'name'=>'Play_Items.manager_id',
			'value'=>'$data->Play_Items->Items_Store_Manager->phone',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'DataColumn',
			'evaluateHtmlOptions'=>true,
			'name'=>'Play_Items.area_id_p',
			'value'=>'(isset($data->Play_Items->area_id_p) && $data->Play_Items->area_id_p != 0 )? $data->Play_Items->Items_area_id_p_Area_id->name:"未选择"',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'DataColumn',
			'evaluateHtmlOptions'=>true,
			'name'=>'Play_Items.area_id_m',
			'value'=>'(isset($data->Play_Items->area_id_m) && $data->Play_Items->area_id_m != 0 )? $data->Play_Items->Items_area_id_m_Area_id->name:"未选择"',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'DataColumn',
			'evaluateHtmlOptions'=>true,
			'name'=>'Play_Items.area_id_c',
			'value'=>'(isset($data->Play_Items->area_id_c) && $data->Play_Items->area_id_c != 0 )? $data->Play_Items->Items_area_id_c_Area_id->name:"未选择"',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Play_Items.address").":".$data->Play_Items->address'),
		),
		array(
			'name'=>'Play_Items.down',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'Play_Items.push',
				'value'=>'Push::executed($data->id,"push",$data->Play_Items->push)."%"',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("Play_Items.push").":".Push::executed($data->id,"push",$data->Play_Items->push)."%\n".
          			$data->getAttributeLabel("Play_Items.push_agent").":".Push::executed($data->id,"push_agent",$data->Play_Items->push_agent)."%\n".
            		$data->getAttributeLabel("Play_Items.push_store").":".Push::executed($data->id,"push_store",$data->Play_Items->push_store)."%\n".
            		$data->getAttributeLabel("Play_Items.push_orgainzer").":".Push::executed($data->id,"push_orgainzer",$data->Play_Items->push_orgainzer)."%\n"
				'),
		),
		array(
			'name'=>'Play_Items.pub_time',
			'type'=>'datetime',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'DataColumn',
			'evaluateHtmlOptions'=>true,
			'name'=>'Play_Items.add_time',
			'type'=>'datetime',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
			'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Play_Items.up_time").":".Yii::app()->format->datetime($data->Play_Items->up_time)'),
		),
		array(
			'name'=>'Play_Items.audit',
			'value'=>'Items::$_audit[$data->Play_Items->audit]',
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'Play_Items.free_status',
				'value'=>'Items::$_free_status[$data->Play_Items->free_status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'name'=>'Play_Items.status',
			'value'=>'Items::$_status[$data->Play_Items->status]',
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
						//'visible'=>'$data->status==1',
						'url'=>'Yii::app()->createUrl("/admin/tmm_play/restore",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/restore.png',
					),
// 					'delete'=>array(
// 						'label'=>'彻底删除',
// 						'options'=>array('style'=>'padding:0 8px 0 0;'),
// 						'url'=>'Yii::app()->createUrl("/admin/tmm_play/clear",array("id"=>$data->id))',
// 						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/delete.png',
// 					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
		),
	),
)); 
?>
