<?php
/* @var $this Tmm_shopsController */
/* @var $model Shops */

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
	$('#shops-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>选择 觅境</h1>

<div>
	<?php echo CHtml::link('高级搜索','#',array('class'=>'search-button')); ?>
</div>

<div class="search-form" style="display:none">
<?php $this->renderPartial('/tmm_shops/_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->
<?php
Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
	jQuery('#pub_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#add_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#up_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#tops_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#selected_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#selected_tops_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#hot_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));

}
");

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'shops-grid',
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
				'checked'=>'Select::checkedSelected($data->id, ' . $select->id . ', ' . Select::type_nearby . ')',
				'htmlOptions'=>array('style'=>'text-align:center;width:50px;', 'title'=>'点击选中添加'),
				'headerHtmlOptions' => array('style'=>'text-align:center;width:50px;','class'=>'select', 'title'=>'点击全选'),
				'checkBoxHtmlOptions' => array('name' => 'select[]'),
				'headerTemplate'=>'{item}',
		),
		array(
				'name'=>'id',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'agent_id',
				'value'=>'isset($data->Shops_Agent->phone) ? $data->Shops_Agent->phone : "无运营商"',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:120px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					isset($data->Shops_Agent->firm_name) ? ($data->getAttributeLabel("Shops_Agent.firm_name")."：".$data->Shops_Agent->firm_name) : "没有运营商"
			'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'c_id',
				'filter'=>array_slice(Shops::$_shops, 0, 2, true),
				'value'=>'$data->Shops_ShopsClassliy->name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'					
					$data->getAttributeLabel("Shops_ShopsClassliy.info")."：".$data->Shops_ShopsClassliy->info				
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:300px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
						$data->getAttributeLabel("list_info")."：".$data->list_info . "\n" .
						$data->getAttributeLabel("page_info")."：".$data->page_info . "\n" .
						$data->getAttributeLabel("brow")."：".$data->brow . "\n" .	
						$data->getAttributeLabel("share")."：".$data->share . "\n" .
						$data->getAttributeLabel("tags_ids")."：".$data->tags_ids
				'),
		),
		array(
				'filter'=>$model::$_is_sale,
				'name'=>'is_sale',
				'value'=>'$data::$_is_sale[$data->is_sale]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>$model::$_tops,
				'name'=>'tops',
				'value'=>'$data::$_tops[$data->tops]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'		
						$data->getAttributeLabel("tops_time").":".
						($data->tops_time != 0 ? Yii::app()->format->FormatDateTime($data->tops_time) : "------")
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>$model::$_selected,
				'name'=>'selected',
				'value'=>'$data::$_selected[$data->selected]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
						$data->getAttributeLabel("selected_time")."：".
						($data->selected == $data::selected ? Yii::app()->format->FormatDateTime($data->selected_time) : "------") . "\n" .
						$data->getAttributeLabel("selected_info")."：".$data->selected_info
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>$model::$_selected_tops,
				'name'=>'selected_tops',
				'value'=>'$data::$_selected_tops[$data->selected_tops]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
						$data->getAttributeLabel("selected_tops_time").":".
						($data->selected_tops_time != 0 ? Yii::app()->format->FormatDateTime($data->selected_tops_time)	: "------")						
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>$model::$_hot,
				'name'=>'hot',
				'value'=>'$data::$_hot[$data->hot]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					$data->getAttributeLabel("hot_time").":".
					($data->hot_time != 0 ? Yii::app()->format->FormatDateTime($data->hot_time)	: "------")
			'),
		),
		array(
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>'zh-CN',
						'model'=>$model,
						'attribute'=>'pub_time',
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
								'id' =>'pub_time_date',
						),
					),true),
				'name'=>'pub_time',
				'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>'zh-CN',
						'model'=>$model,
						'attribute'=>'add_time',
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
								'id' =>'add_time_date',
						),
					),true),
				'name'=>'add_time',
				'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>'zh-CN',
						'model'=>$model,
						'attribute'=>'up_time',
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
								'id' =>'up_time_date',
						),
					),true),
				'name'=>'up_time',
				'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>$model::$_audit,
				'name'=>'audit',
				'value'=>'$data::$_audit[$data->audit]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
						$data->getAttributeLabel("audit")."：".
						AuditLog::get_audit_log(Shops::$__audit[$data->c_id],$data->id)->info
				'),
		),
		array(
				'filter'=>$model::$_status,
				'name'=>'status',
				'value'=>'$data::$_status[$data->status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
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
$urlSave = Yii::app()->createUrl('/admin/tmm_select/create', array('id'=>$select->id, 'type'=>Select::type_nearby));
$urlDelete = Yii::app()->createUrl('/admin/tmm_select/update', array('id'=>$select->id, 'type'=>Select::type_nearby));

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
