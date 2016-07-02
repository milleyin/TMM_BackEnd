<?php
/* @var $this Tmm_areaController */
/* @var $model Area */

$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	'热门城市设置'
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#area-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>热门城市设置</h1>
<div>
		<?php echo CHtml::link('高级搜索','#',array('class'=>'search-button')); ?>
</div>
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
	$.fn.yiiGridView.update('area-grid',{  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data){
	$.fn.yiiGridView.update('area-grid');  
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
	jQuery('#area-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#area-grid').yiiGridView('update');
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
	jQuery('#admin_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh_cn'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	
}
");

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'area-grid',
	'dataProvider'=>$model->search_select(),
	'enableHistory'=>true,
	'afterAjaxUpdate' => 'reinstallDatePicker',
	'filter'=>$model,
	'columns'=>array(
		array(
				'selectableRows' => 2,
				'class' => 'CCheckBoxColumn',
				'name'=>'id',
				'id'=>'hot',
				'checked'=>'$data->status_hot==Area::status_hot_yes',
				'htmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'headerHtmlOptions' => array('style'=>'text-align:center;width:50px;','class'=>'All'),
				'headerTemplate'=>'全选{item}',
		),
		array(
				'name'=>'id',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:20px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'name'=>'nid',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'admin_id',
				'value'=>'isset($data->Area_Admin->username)?$data->Area_Admin->username:"热门未设置"',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					isset($data->Area_Admin->username)?(
						$data->getAttributeLabel("Area_Admin.username").":".$data->Area_Admin->username."\n".		
						$data->getAttributeLabel("Area_Admin.name").":".$data->Area_Admin->name."\n"
					):"热门未设置"
				'),
		),
		array(
				'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>Yii::app()->language,
						'model'=>$model,
						'attribute'=>'admin_time',
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
								'id' =>'admin_time_date',
						),
					),true),
				'name'=>'admin_time',
				'value'=>'$data->admin_time==0?"--":Yii::app()->format->datetime($data->admin_time)',
				//'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'filter'=>Area::$_status_hot,
				'name'=>'status_hot',
				'value'=>'Area::$_status_hot[$data->status_hot]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
	),
));

if(Yii::app()->request->enableCsrfValidation)
{
	$csrfTokenName = Yii::app()->request->csrfTokenName;
	$csrfToken = Yii::app()->request->csrfToken;
	$csrf = "\n\t\tdata:{'$csrfTokenName':'$csrfToken','ids':ids,'type':type},\n";
}else
	$csrf = "\n\t\tdata:{'ids':ids,'type':type},\n";
$url=Yii::app()->createUrl('/admin/tmm_area/hot');

$ajax=<<<EOD
	function all_hot(){//获取当前页的所有的
		var data=new Array();
		$("input:checkbox[name='hot[]']").each(function (){
				data.push($(this).val());
		});
		return data;
	}
	jQuery(document).on('change','#hot_all',function() {
		if($(this).is(':checked')){
			Checkbox(all_hot(),'yes');
		}else
			Checkbox(all_hot(),'no');
	});
	jQuery(document).on('change',"input:checkbox[name='hot[]']",function(){
		if($(this).is(':checked'))
			Checkbox($(this).val(),'yes');
		else
			Checkbox($(this).val(),'no');
	 });
	function Checkbox(ids,type)
	{
		$.ajax({
		type:"POST",$csrf 		url:'$url',
			success:function(data){
				jQuery('#area-grid').yiiGridView('update');
			}
		});
		return false;
	}
EOD;
Yii::app()->clientScript->registerScript('tags_select',
$ajax
);
?>
