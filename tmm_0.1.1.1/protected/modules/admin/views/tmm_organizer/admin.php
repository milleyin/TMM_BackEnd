<?php
/* @var $this Tmm_organizerController */
/* @var $model Organizer */

$this->breadcrumbs=array(
	'代理商管理页',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#organizer-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>管理页 代理商</h1>

<?php echo CHtml::link('高级搜索','#',array('class'=>'search-button')); ?>

<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->
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
	$.fn.yiiGridView.update('organizer-grid',{  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data){
	$.fn.yiiGridView.update('organizer-grid');  
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
	jQuery('#organizer-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#organizer-grid').yiiGridView('update');
			afterDelete(th, true, data);
		},
		error: function(XHR) {
			return afterDelete(th, false, XHR);
		}
});
    return false;
}
EOD;

Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
	jQuery('#add_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#up_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#pass_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	
}
");

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'organizer-grid',
	'dataProvider'=>$model->search(),
	'enableHistory'=>true,
	'afterAjaxUpdate' => 'reinstallDatePicker',
	'filter'=>$model,
	'columns'=>array(
		array(
				'name'=>'id',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:20px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,			
				'filter'=>CHtml::activeTextField($model->Organizer_User, 'phone', array('id'=>false)),
				'name'=>'Organizer_User.phone',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("Organizer_User.nickname").":".$data->Organizer_User->nickname'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'firm_name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:120px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("firm_phone").":".$data->firm_phone'),
		),
		array(
				'filter'=>Area::data_array_name('',array()),
				'name'=>'area_id_p',
				'value'=>'$data->Organizer_area_id_p_Area_id->name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'filter'=>Area::data_array_name($model->area_id_p,array(),false),
				'name'=>'area_id_m',
				'value'=>'$data->Organizer_area_id_m_Area_id->name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>Area::data_array_name($model->area_id_m,array(),false),
				'name'=>'area_id_c',
				'value'=>'$data->Organizer_area_id_c_Area_id->name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("address").":".$data->address'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'manage_name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("manage_phone").":".$data->manage_phone'),
		),
// 		array(
// 				//'class'=>'DataColumn',
// 				//'evaluateHtmlOptions'=>true,
// 				'name'=>'push',
// 				'value'=>'Push::executed($data->id,Push::push_organizer)."%"',
// 				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
// 				'htmlOptions'=>array('style'=>'text-align:center;'),//,'title'=>'$data->push'),
// 		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'income_count',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'$data->getAttributeLabel("money").":".$data->money'),
		),
		array(
				'name'=>'deposit',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
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
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
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
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>Yii::app()->language,
						'model'=>$model,
						'attribute'=>'pass_time',
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
								'id' =>'pass_time_date',
						),
					),true),
				'name'=>'pass_time',
				'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'filter'=>$model::$_status,
				'name'=>'status',
				'value'=>'$data::$_status[$data->status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}{update}{actives}{deposit}{delete}{disable}{start}',
			'buttons'=>array(
					'view'=>array(
						'label'=>'查看',
						'options'=>array('style'=>'padding:0 15px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/view.png',
					),
					'update'=>array(
						'label'=>'更新',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'visible'=>'$data->Organizer_User->status==1',
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/update.png',
					),
					'actives'=>array(
						'label'=>'添加觅趣',
						'visible'=>'$data->Organizer_User->status==1 && $data->status==1',
						'url'=>'Yii::app()->createUrl("/admin/tmm_organizer/actives",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 15px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/shop.png',
					),
//					'bank'=>array(
//						'label'=>'银行',
//						'visible'=>'$data->Organizer_User->status==1 && $data->Organizer_User->is_organizer==User::organizer && $data->status==1',
//						'url'=>'Yii::app()->createUrl("/admin/tmm_organizer/bank",array("id"=>$data->id))',
//						'options'=>array('style'=>'padding:0 8px 0 0;'),
//						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/bank.png',
//					),
					'deposit'=>array(
						'label'=>'保证金',
						'visible'=>'$data->Organizer_User->status==1 && $data->Organizer_User->is_organizer==User::organizer && $data->status==1',
						'url'=>'Yii::app()->createUrl("/admin/tmm_organizer/deposit",array("id"=>$data->id))',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/deposit.png',
					),
					'delete'=>array(
						'label'=>'删除',
						'visible'=>'$data->Organizer_User->status==1 && $data->status==0',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/delete.png',
					),
					'disable'=>array(
						'label'=>'禁用',
						'visible'=>'$data->Organizer_User->status==1 && $data->status==1',
						'url'=>'Yii::app()->createUrl("/admin/tmm_organizer/disable",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/disable.png',
					),
					'start'=>array(
						'label'=>'激活',
						'visible'=>'$data->Organizer_User->status==1 && $data->status==0 && $data->Organizer_User->is_organizer==User::organizer',
						'url'=>'Yii::app()->createUrl("/admin/tmm_organizer/start",array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/css/admin/main/right/images/enable.png',
					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:200px;'),
		),
	),
)); 
?>
