<?php
/* @var $this Tmm_accountLogController */
/* @var $model AccountLog */

$this->breadcrumbs=array(
	'资金流水管理页',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#account-log-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>管理页 资金流水</h1>
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
	$.fn.yiiGridView.update('account-log-grid',{  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data){
	$.fn.yiiGridView.update('account-log-grid');  
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
	jQuery('#account-log-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#account-log-grid').yiiGridView('update');
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
	jQuery('#add_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	jQuery('#up_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
	
}
");

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'account-log-grid',
	'dataProvider'=>$model->search(),
	'enableHistory'=>true,
	'pager'=>array(
			'class'=>'CLinkPager',
			//'header'=>$model->getEntryRmbCount(),
	),
	'summaryText'=>'<div style="text-align:left;line-height:20px;">'.
		implode('<br>', array(
			'<strong>统计：￥'.$model->getRmbCount().'元</strong>',
			'<strong>收入：￥'.$model->getEntryRmbCount().'元</strong>',
			'<strong>支出：￥'.$model->getDeductRmbCount().'元</strong>',
			'<strong>冻结：￥'.$model->getPendingRmbCount().'元</strong>',			
			'<strong>记录：￥'.$model->getRecordRmbCount().'元</strong>',
		)).'</div> 第 {start}-{end} 条, 共 {count} 条.',
	'afterAjaxUpdate' => 'reinstallDatePicker',
	'filter'=>$model,
	'columns'=>array(
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'id',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
						$data->getAttributeLabel("id").":".$data->id
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'account_no',
				'value'=>'substr($data->account_no,0,5)."...".substr($data->account_no,-4,4)',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
						$data->getAttributeLabel("account_no").":".$data->account_no."\n".	
						$data->getAttributeLabel("manage_account_id").":".$data::getRoleName($data,$data->manage_account_type,"manage")."\n".	
						$data->getAttributeLabel("manage_account_type").":".$data::$_manage_account_type[$data->manage_account_type]			
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>AccountLog::$_account_type,
				'name'=>'account_type',
				'value'=>'$data::$_account_type[$data->account_type]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;','title'=>'当前资金的角色类型'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
						$data->getAttributeLabel("account_type").":".$data::$_account_type[$data->account_type]
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'account_id',
				'value'=>'$data::getRoleName($data,$data->account_type)',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;','title'=>'当前作资金的账号'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
						$data->getAttributeLabel("account_id").":".$data::getRoleName($data,$data->account_type)."\n".	
						"账户详情："."\n".
						(isset($data->AccountLog_Account->id)?(
							" ".$data->getAttributeLabel("AccountLog_Account.count").":".$data->AccountLog_Account->count."\n".
							" ".$data->getAttributeLabel("AccountLog_Account.total").":".$data->AccountLog_Account->total."\n".
							" ".$data->getAttributeLabel("AccountLog_Account.money").":".$data->AccountLog_Account->money."\n"	.
							" ".$data->getAttributeLabel("AccountLog_Account.no_money").":".$data->AccountLog_Account->no_money."\n".
							" ".$data->getAttributeLabel("AccountLog_Account.not_consume_count").":".$data->AccountLog_Account->not_consume_count."\n".
							" ".$data->getAttributeLabel("AccountLog_Account.cash_count").":".$data->AccountLog_Account->cash_count."\n".
							" ".$data->getAttributeLabel("AccountLog_Account.pay_count").":".$data->AccountLog_Account->pay_count."\n".
							" ".$data->getAttributeLabel("AccountLog_Account.refund_count").":".$data->AccountLog_Account->refund_count."\n".
							" ".$data->getAttributeLabel("AccountLog_Account.consume_count").":".$data->AccountLog_Account->consume_count
						):"")
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>AccountLog::$_to_account_type,
				'name'=>'to_account_type',
				'value'=>'$data::$_to_account_type[$data->to_account_type]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;','title'=>'to/from 接受或来自资金的角色类型'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
						$data->getAttributeLabel("to_account_type").":".$data::$_to_account_type[$data->to_account_type]
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'to_account_id',
				'value'=>'$data::getRoleName($data,$data->to_account_type,"to")',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;','title'=>'to/from 接受或来自资金的账号'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
						$data->getAttributeLabel("to_account_id").":".$data::getRoleName($data,$data->to_account_type,"to")
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>CHtml::activeDropDownList($model, 'funds_type',array(''=>'')+AccountLog::$_funds_type, array('id'=>false)),
				//'filter'=>AccountLog::$_funds_type,
				'name'=>'funds_type_name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:85px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
						$data->getAttributeLabel("funds_type_name").":".$data->funds_type_name."\n".
						$data->getAttributeLabel("funds_type").":".$data->funds_type
			'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'use_money',
				'value'=>'($data->centre_status == $data::centre_status_entry ? "+" : 
								($data->centre_status == $data::centre_status_deduct ? "-" : 
								($data->centre_status == $data::centre_status_pending ? 
								($data->log_status !=$data::log_status_success ? "-":"") 
								: "")))
								.$data->use_money',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:75px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
						($data->centre_status == $data::centre_status_entry ? "+" : 
								($data->centre_status == $data::centre_status_deduct ? "-" : 
								($data->centre_status == $data::centre_status_pending ? 
								($data->log_status !=$data::log_status_success ? "-":"") 
								: "")))
								.$data->use_money
				'),
		),		
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>AccountLog::$_money_type,
				'name'=>'money_type',
				'value'=>'$data::$_money_type[$data->money_type]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
						$data->getAttributeLabel("money_type").":".$data::$_money_type[$data->money_type]
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'money',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:75px;','title'=>'当前资金前的账号原可用余额'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
						$data->getAttributeLabel("count").":".$data->count."\n".
						$data->getAttributeLabel("total").":".$data->total."\n".
						$data->getAttributeLabel("money").":".$data->money."\n"	.
						$data->getAttributeLabel("no_money").":".$data->no_money."\n".
						$data->getAttributeLabel("not_consume_count").":".$data->not_consume_count."\n".
						$data->getAttributeLabel("cash_count").":".$data->cash_count."\n".
						$data->getAttributeLabel("pay_count").":".$data->pay_count."\n".
						$data->getAttributeLabel("refund_count").":".$data->refund_count."\n".
						$data->getAttributeLabel("consume_count").":".$data->consume_count
					'),
		),
		array(
				//'class'=>'DataColumn',
				//'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'no_money',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;','title'=>'当前资金前的账号原冻结金额'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'after_money',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;','title'=>'after 当前资金后的账号可用余额'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
						$data->getAttributeLabel("after_count").":".$data->after_count."\n".
						$data->getAttributeLabel("after_total").":".$data->after_total."\n".
						$data->getAttributeLabel("after_money").":".$data->after_money."\n".
						$data->getAttributeLabel("after_no_money").":".$data->after_no_money."\n".
						$data->getAttributeLabel("after_not_consume_count").":".$data->after_not_consume_count."\n".
						$data->getAttributeLabel("after_cash_count").":".$data->after_cash_count."\n".
						$data->getAttributeLabel("after_pay_count").":".$data->after_pay_count."\n".
						$data->getAttributeLabel("after_refund_count").":".$data->after_refund_count."\n".
						$data->getAttributeLabel("after_consume_count").":".$data->after_consume_count					
				'),
		),
		array(
				//'class'=>'DataColumn',
				//'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'after_no_money',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;','title'=>'after 当前资金后的账号冻结金额'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>AccountLog::$_info_type,
				'name'=>'info_type',
				'value'=>'$data::$_info_type[$data->info_type]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
						$data->getAttributeLabel("info_type").":".$data::$_info_type[$data->info_type]
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'info_id',
				'value'=>'$data::getInfoName($data,$data->info_type,true)',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:55px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("info_id").":".$data::getInfoName($data,$data->info_type)."\n".
					$data->getAttributeLabel("name").":".$data->name."\n".
					$data->getAttributeLabel("ip").":".$data->ip."\n".
					$data->getAttributeLabel("address").":".$data->address."\n".
					$data->getAttributeLabel("info").":\n".$data->info."\n"
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
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
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
						$data->getAttributeLabel("add_time").":".Yii::app()->format->FormatDate($data->add_time)
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
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
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
						$data->getAttributeLabel("up_time").":".Yii::app()->format->FormatDate($data->up_time)."\n".
						$data->getAttributeLabel("up_count").":".$data->up_count
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>AccountLog::$_log_status,
				'name'=>'log_status',				
				'value'=>'$data::$_log_status[$data->log_status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
						$data->getAttributeLabel("log_status").":".$data::$_log_status[$data->log_status]
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>AccountLog::$_centre_status,
				'name'=>'centre_status',
				'value'=>'$data::$_centre_status[$data->centre_status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
						$data->getAttributeLabel("centre_status").":".$data::$_centre_status[$data->centre_status]
				'),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>AccountLog::$_status,
				'name'=>'status',
				'value'=>'$data::$_status[$data->status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:45px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					$data->getAttributeLabel("status").":".$data::$_centre_status[$data->status]
				'),
		),
		array(
			'class'=>'CButtonColumn',
			'header'=>'操作',
			'template'=>'{view}',
			'buttons'=>array(
					'view',
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
		),
	),
	'htmlOptions'=>array('style'=>'text-align:center;min-width:1500px;'),
)); 
?>
