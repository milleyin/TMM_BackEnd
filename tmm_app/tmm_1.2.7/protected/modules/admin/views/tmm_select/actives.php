<?php
/* @var $this Tmm_activesController */
/* @var $model Actives */

$this->breadcrumbs=array(
	'广告专题管理页'=>array('/admin/tmm_ad/admin'),
	$select->name=>array('/admin/tmm_ad/view','id'=>$select->id),
	'内部广告管理页'=>array('admin', 'id'=>$select->id),
	'内部广告选择页',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#actives-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>选择 觅趣</h1>
<div>
		<?php echo CHtml::link('高级搜索','#',array('class'=>'search-button')); ?>
</div>
<div class="search-form" style="display:none">
<?php $this->renderPartial('/tmm_actives/_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->
<?php
Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
	jQuery('#start_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#end_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#pub_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#go_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	
}
");

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'actives-grid',
	'dataProvider'=>$model->selectSearch(),
	'enableHistory'=>true,
	'afterAjaxUpdate' => 'reinstallDatePicker',
	'filter'=>$model,
	'columns'=>array(
		array(
				'selectableRows' => 2,
				'class' => 'CCheckBoxColumn',
				'name'=>'id',
				'id'=>'select',
				'checked'=>'Select::checkedSelected($data->id, ' . $select->id . ', ' . Select::type_actives . ')',
				'htmlOptions'=>array('style'=>'text-align:center;width:50px;', 'title'=>'点击选中添加'),
				'headerHtmlOptions' => array('style'=>'text-align:center;width:50px;','class'=>'select', 'title'=>'点击全选'),
				'checkBoxHtmlOptions' => array('name' => 'select[]'),
				'headerTemplate'=>'{item}',
		),
		array(
				'name'=>'id',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:35px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'filter'=>CHtml::activeDropDownList($model, 'is_organizer',array(''=>'')+Actives::$_is_organizer, array('id'=>false)),
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
				'filter'=>CHtml::activeTextField($model->Actives_Shops, 'name', array('id'=>false)),
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
				'filter'=>Actives::$_tour_type,
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
				'filter'=>Actives::$_is_open,
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
				'filter'=>Actives::$_pay_type,
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
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>Yii::app()->language,
						'model'=>$model,
						'attribute'=>'start_time',
						'value'=>date('Y-m-d'),
						'options'=>array(
								'maxDate'=>'new date()',
								'dateFormat'=>'yy-mm-dd',
								'showOn' => 'focus',
								'showOtherMonths' => true,
								'selectOtherMonths' => true,
								'changeMonth' => true,
								'changeYear' => true,
								'showButtonPanel' => true,
						),
						'htmlOptions'=>array(
								'id' =>'start_time_date',
						),
					),true),
				'name'=>'start_time',
				'type'=>'date',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				//'class'=>'DataColumn',
				//'evaluateHtmlOptions'=>true,
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>Yii::app()->language,
						'model'=>$model,
						'attribute'=>'end_time',
						'value'=>date('Y-m-d'),
						'options'=>array(
								'maxDate'=>'new date()',
								'dateFormat'=>'yy-mm-dd',
								'showOn' => 'focus',
								'showOtherMonths' => true,
								'selectOtherMonths' => true,
								'changeMonth' => true,
								'changeYear' => true,
								'showButtonPanel' => true,
						),
						'htmlOptions'=>array(
								'id' =>'end_time_date',
						),
					),true),
				'name'=>'end_time',
				'type'=>'date',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				//'class'=>'DataColumn',
				//'evaluateHtmlOptions'=>true,
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>Yii::app()->language,
						'model'=>$model,
						'attribute'=>'go_time',
						'value'=>date('Y-m-d'),
						'options'=>array(
								'maxDate'=>'new date()',
								'dateFormat'=>'yy-mm-dd',
								'showOn' => 'focus',
								'showOtherMonths' => true,
								'selectOtherMonths' => true,
								'changeMonth' => true,
								'changeYear' => true,
								'showButtonPanel' => true,
						),
						'htmlOptions'=>array(
								'id' =>'go_time_date',
						),
					),true),
				'name'=>'go_time',
				'value'=>'$data->go_time==0?"--------------":date("Y/m/d",$data->go_time)',
				//'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'filter'=>Actives::$_actives_status,
				'name'=>'actives_status',
				'value'=>'$data::$_actives_status[$data->actives_status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>CHtml::activeDropDownList($model->Actives_Shops, 'audit',array(''=>'')+Shops::$_audit, array('id'=>false)),
				'name'=>'Actives_Shops.audit',
				'value'=>'Shops::$_audit[$data->Actives_Shops->audit]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
						$data->getAttributeLabel("Actives_Shops.audit")."：".
						AuditLog::get_audit_log(Shops::$__audit[$data->Actives_Shops->c_id],$data->id)->info
				'),
		),
		array(
				'filter'=>CHtml::activeDropDownList($model->Actives_Shops, 'is_sale',array(''=>'')+Shops::$_is_sale, array('id'=>false)),
				'name'=>'Actives_Shops.is_sale',
				'value'=>'Shops::$_is_sale[$data->Actives_Shops->is_sale]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>CHtml::activeDropDownList($model->Actives_Shops, 'tops',array(''=>'')+Shops::$_tops, array('id'=>false)),
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
				'filter'=>CHtml::activeDropDownList($model->Actives_Shops, 'selected',array(''=>'')+Shops::$_selected, array('id'=>false)),
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
				'filter'=>CHtml::activeDropDownList($model->Actives_Shops, 'selected_tops',array(''=>'')+Shops::$_selected_tops, array('id'=>false)),
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
				'filter'=>CHtml::activeDropDownList($model->Actives_Shops, 'hot',array(''=>'')+Shops::$_hot, array('id'=>false)),
				'name'=>'Actives_Shops.hot',
				'value'=>'Shops::$_hot[$data->Actives_Shops->hot]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
				$data->getAttributeLabel("Actives_Shops.hot_time")."：".
						($data->Actives_Shops->hot_time != 0 ? Yii::app()->format->FormatDateTime($data->Actives_Shops->hot_time)	: "--------")
				'),
		),
		array(
				'filter'=>Actives::$_status,
				'name'=>'status',
				'value'=>'$data::$_status[$data->status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:45px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'filter'=>CHtml::activeDropDownList($model->Actives_Shops, 'status',array(''=>'')+Shops::$_status, array('id'=>false)),
				'name'=>'Actives_Shops.status',
				'value'=>'Shops::$_status[$data->Actives_Shops->status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:45px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
	),
	'htmlOptions'=>array('style'=>'text-align:center;min-width:1500px;'),
)); 

if (Yii::app()->request->enableCsrfValidation)
{
	$csrfTokenName = Yii::app()->request->csrfTokenName;
	$csrfToken = Yii::app()->request->csrfToken;
	$csrf = "\n\t\tdata:{'$csrfTokenName':'$csrfToken', 'Select':select_ids},\n";
}
else
	$csrf = "\n\t\tdata:{'Select':select_ids},\n";
$urlSave = Yii::app()->createUrl('/admin/tmm_select/create', array('id'=>$select->id, 'type'=>Select::type_actives));
$urlDelete = Yii::app()->createUrl('/admin/tmm_select/update', array('id'=>$select->id, 'type'=>Select::type_actives));

$ajax=<<<EOD
jQuery(function($) {
	//获取当前页的所有的
	function getAll()
	{
		var data=new Array();
		$("input:checkbox[name='select[]']").each(function (){
				data.push($(this).val());
		});
		return data;
	}
	jQuery(document).on('change','#select_all',function() {
		if($(this).is(':checked')){
			Checkbox(getAll(),'yes');
		}else
			Checkbox(getAll(),'no');
	});
	jQuery(document).on('change',"input:checkbox[name='select[]']",function(){
		if($(this).is(':checked'))
			Checkbox($(this).val(),'yes');
		else
			Checkbox($(this).val(),'no');
	 });
	function Checkbox(select_ids, type)
	{
		var urlSave='$urlSave', urlDelete='$urlDelete';
		$.ajax({
			type:"POST",$csrf 		url:type=='yes' ? urlSave : urlDelete,
			success:function(data){
					if(data == 1)
						jQuery('#actives-grid').yiiGridView('update');
					else
						alert(data ? data : '非法操作');
			},
			error:function(data)
			{
				alert(data ? data : '非法操作');
			}
		});
		return false;
	}
});
EOD;
Yii::app()->clientScript->registerScript('tags_select',
		$ajax
);
?>
