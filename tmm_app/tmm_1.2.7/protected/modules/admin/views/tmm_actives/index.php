<?php
/* @var $this Tmm_activesController */
/* @var $model Actives */

$this->breadcrumbs=array(
	'垃圾回收页',
);
?>
<h1>垃圾回收页 线路(旅游活动)</h1>

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
	$.fn.yiiGridView.update('actives-grid',{  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data){
	$.fn.yiiGridView.update('actives-grid');  
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
	jQuery('#actives-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#actives-grid').yiiGridView('update');
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
	'id'=>'actives-grid',
	'dataProvider'=>$model,
	'enableHistory'=>true,
	'columns'=>array(
		array(
				'name'=>'id',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:35px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'is_organizer',
				'value'=>'$data::$_is_organizer[$data->is_organizer]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:55px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
						$data->getAttributeLabel("Actives_User.phone")."：".$data->Actives_User->phone
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'organizer_id',
				'value'=>'$data->Actives_User->phone',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:75px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
						$data->getAttributeLabel("Actives_User.nickname").":".$data->Actives_User->nickname . "\n" .
						(
							$data->is_organizer == $data::is_organizer_yes 
							?
							(
								$data->getAttributeLabel("Actives_User.User_Organizer.firm_name")."：".$data->Actives_User->User_Organizer->firm_name . "\n" .
								$data->getAttributeLabel("Actives_User.User_Organizer.firm_phone")."：".$data->Actives_User->User_Organizer->firm_phone ."\n" . 
								$data->getAttributeLabel("Actives_User.User_Organizer.law_name")."：".$data->Actives_User->User_Organizer->law_name . "\n" .
								$data->getAttributeLabel("Actives_User.User_Organizer.manage_name")."：".$data->Actives_User->User_Organizer->manage_name
							) 
							: 
							""
						)
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'Actives_Shops.name',				
				'value'=>'mb_substr($data->Actives_Shops->name,0,8,"utf-8") . "..."',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:140px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
						$data->getAttributeLabel("Actives_Shops.name")."：".$data->Actives_Shops->name . "\n" .
						$data->getAttributeLabel("Actives_Shops.list_info")."：".$data->Actives_Shops->list_info . "\n" .
						$data->getAttributeLabel("Actives_Shops.page_info")."：".$data->Actives_Shops->page_info . "\n" .
						$data->getAttributeLabel("Actives_Shops.brow")."：".$data->Actives_Shops->brow . "\n" .	
						$data->getAttributeLabel("Actives_Shops.share")."：".$data->Actives_Shops->share . "\n" .
						$data->getAttributeLabel("Actives_Shops.tags_ids")."：".$data->Actives_Shops->tags_ids . "\n" .
						$data->getAttributeLabel("number")."：".$data->number . "\n" .
						$data->getAttributeLabel("order_number")."：".$data->order_number . "\n" .
						$data->getAttributeLabel("tour_count")."：".$data->tour_count . "\n" .
						$data->getAttributeLabel("order_count")."：".$data->order_count  . "\n" .
						$data->getAttributeLabel("tour_price")."：".$data->tour_price
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'tour_type',
				'value'=>'$data::$_tour_type[$data->tour_type]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:45px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
						$data->getAttributeLabel("tour_type")."：". $data::$_tour_type[$data->tour_type] . "\n" .
						$data->getAttributeLabel("actives_type")."：". $data::$_actives_type[$data->actives_type]						
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'is_open',
				'value'=>'$data::$_is_open[$data->is_open]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:45px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
						$data->getAttributeLabel("is_open") ."：". $data::$_is_open[$data->is_open] . "\n" .
						$data->getAttributeLabel("barcode") ."：". $data->barcode . "\n" .
						$data->getAttributeLabel("barcode_num") ."：". $data->barcode_num
				'),
		),
		array(
				'name'=>'pay_type',
				'value'=>'$data::$_pay_type[$data->pay_type]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
 				'class'=>'DataColumn',
 				'evaluateHtmlOptions'=>true,
				'name'=>'order_number',
				'value'=>'$data->order_number . " / " .$data->number',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'			
						$data->getAttributeLabel("number")."：".$data->number . "\n" .
						$data->getAttributeLabel("order_number")."：".$data->order_number . "\n" .
						$data->getAttributeLabel("tour_price")."：".$data->tour_price . "\n" .			
						$data->getAttributeLabel("tour_count")."：".$data->tour_count . "\n" .
						$data->getAttributeLabel("order_count")."：".$data->order_count 
				'),
		),
		array(
				'name'=>'start_time',
				'type'=>'date',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				//'class'=>'DataColumn',
				//'evaluateHtmlOptions'=>true,
				'name'=>'end_time',
				'type'=>'date',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				//'class'=>'DataColumn',
				//'evaluateHtmlOptions'=>true,
				'name'=>'go_time',
				'value'=>'$data->go_time==0?"--------------":date("Y/m/d",$data->go_time)',
				//'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'actives_status',
				'value'=>'$data::$_actives_status[$data->actives_status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'Actives_Shops.audit',
				'value'=>'Shops::$_audit[$data->Actives_Shops->audit]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
						$data->getAttributeLabel("Actives_Shops.audit")."：".
						AuditLog::get_audit_log(Shops::$__audit[$data->Actives_Shops->c_id],$data->id)->info
				'),
		),
		array(
				'name'=>'Actives_Shops.is_sale',
				'value'=>'Shops::$_is_sale[$data->Actives_Shops->is_sale]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'Actives_Shops.tops',
				'value'=>'Shops::$_tops[$data->Actives_Shops->tops]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
						$data->getAttributeLabel("Actives_Shops.tops_time")."：".
						($data->Actives_Shops->tops_time != 0 ? Yii::app()->format->FormatDateTime($data->Actives_Shops->tops_time) : "--------")
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'Actives_Shops.selected',
				'value'=>'Shops::$_selected[$data->Actives_Shops->selected]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'						
						$data->getAttributeLabel("Actives_Shops.selected_time")."：".
						($data->Actives_Shops->selected == Shops::selected ? Yii::app()->format->FormatDateTime($data->Actives_Shops->selected_time) : "--------") . "\n" .
						$data->getAttributeLabel("Actives_Shops.selected_info")."：".$data->Actives_Shops->selected_info				
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'Actives_Shops.selected_tops',
				'value'=>'Shops::$_selected_tops[$data->Actives_Shops->selected_tops]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:75px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
							$data->getAttributeLabel("Actives_Shops.selected_tops_time")."：".
							($data->Actives_Shops->selected_tops_time != 0 ? Yii::app()->format->FormatDateTime($data->Actives_Shops->selected_tops_time)	: "------")				
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'Actives_Shops.hot',
				'value'=>'Shops::$_hot[$data->Actives_Shops->hot]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
				$data->getAttributeLabel("Actives_Shops.hot_time")."：".
						($data->Actives_Shops->hot_time != 0 ? Yii::app()->format->FormatDateTime($data->Actives_Shops->hot_time)	: "--------")
				'),
		),
		array(
				'name'=>'status',
				'value'=>'$data::$_status[$data->status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:45px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'Actives_Shops.status',
				'value'=>'Shops::$_status[$data->Actives_Shops->status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:45px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}',
			'buttons'=>array(
					'view'=>array(
						'label'=>'查看',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					)
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
		),
	),
)); 
?>
