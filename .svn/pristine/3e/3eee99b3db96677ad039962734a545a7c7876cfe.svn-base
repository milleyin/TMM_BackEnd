<?php
/* @var $this Tmm_wifiController */
/* @var $model Wifi */

$this->breadcrumbs=array(
	'项目管理页'=>array('/admin/tmm_items/admin'),
	'项目(住)管理页'=>array('admin'),
	$select->Hotel_Items->name=>array('view','id'=>$select->id),
	'选择酒店服务'
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#wifi-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>管理页 酒店服务</h1>

<?php echo CHtml::link('高级搜索','#',array('class'=>'search-button')); ?>	

<div class="search-form" style="display:none">
<?php $this->renderPartial('/tmm_wifi/_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->
<?php
Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
	jQuery('#add_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#up_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	
}
");

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'wifi-grid',
	'dataProvider'=>$model->search_wifi(true),
	'enableHistory'=>true,
	'afterAjaxUpdate' => 'reinstallDatePicker',
	'filter'=>$model,
	'columns'=>array(
		array(
				'selectableRows' => 2,
				'class' => 'CCheckBoxColumn',
				'name'=>'id',
				'id'=>'wifis',
				'checked'=>'ItemsWifi::checked($data->id,'.$select->id.')',
				'htmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'headerHtmlOptions' => array('style'=>'text-align:center;width:50px;','class'=>'All'),
				//'checkBoxHtmlOptions' => array('name' => 'tags[]'),
				'headerTemplate'=>'全选{item}',
		),
		array(
				'name'=>'id',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:20px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'admin_id',
				'value'=>'$data->Wifi_Admin->username." [".$data->Wifi_Admin->name."] "',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'info',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:200px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'icon',
				'type'=>'image',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:200px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				//'class'=>'DataColumn',
				//'evaluateHtmlOptions'=>true,
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>Yii::app()->language,
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
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				//'class'=>'DataColumn',
				//'evaluateHtmlOptions'=>true,
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>Yii::app()->language,
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
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'filter'=>$model::$_status,
				'name'=>'status',
				'value'=>'$data::$_status[$data->status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
	),
)); 
if(Yii::app()->request->enableCsrfValidation)
{
	$csrfTokenName = Yii::app()->request->csrfTokenName;
	$csrfToken = Yii::app()->request->csrfToken;
	$csrf = "\n\t\tdata:{'$csrfTokenName':'$csrfToken','wifi_ids':wifi_ids,'type':type},\n";
}else
	$csrf = "\n\t\tdata:{'wifi_ids':wifi_ids,'type':type},\n";
$url=Yii::app()->createUrl('/admin/tmm_hotel/upwifi',array('id'=>$select->id));
$ajax=<<<EOD
jQuery(function($) {
	//获取当前页的所有的
	function all_tags()
	{
		var data=new Array();
		$("input:checkbox[name='wifis[]']").each(function (){
				data.push($(this).val());
		});
		return data;
	}
	jQuery(document).on('change','#wifis_all',function() {
		if($(this).is(':checked')){
			Checkbox(all_tags(),'yes');
		}else
			Checkbox(all_tags(),'no');
	});
	jQuery(document).on('change',"input:checkbox[name='wifis[]']",function(){
		if($(this).is(':checked'))
			Checkbox($(this).val(),'yes');
		else
			Checkbox($(this).val(),'no');
	 });

	function Checkbox(wifi_ids,type)
	{
		$.ajax({
		type:"POST",$csrf 		url:'$url',
			success:function(data){
				if(data != 1)
					alert(data);
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
