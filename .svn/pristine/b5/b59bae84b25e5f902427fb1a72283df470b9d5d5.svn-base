<div class="content_box">
	<div class="title">
		<?php
		echo $this->breadcrumbs(array(
				'商家子账号'=>array('adminSon'), 
				'编辑',
				$model->phone,
		));
		?>
	</div>  <!--.title-->
	<div class="sub_business_edit">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'store-user-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	'htmlOptions' => array('enctype'=>'multipart/form-data','class'=>'accont_info_form'),
)); ?>
			<div class="row sub_id">
				<?php echo $form->labelEx($model,'id'); ?>
				<span><?php echo $model->id; ?></span>
			</div>
			<div class="row content_phone">
				<?php echo $form->label($model,'phone'); ?>
				<?php echo $form->textField($model,'phone',array('size'=>11,'class'=>'phone')); ?>
				<?php echo $form->error($model,'phone'); ?>
			</div>
			<div class="row content_messcode">
				<?php echo $form->label($model,'sms'); ?>
				<?php echo $form->textField($model,'sms',array('size'=>6,'class'=>'messcode')); ?>
				<span id="getcheckcod"><a href="#" class="getcheckcod">获取验证码</a></span>
				<?php echo $form->error($model,'sms',array(),false); ?>
			</div>
			<div class="row affiliation" >
				<label>归属商家</label>
				<span><?php echo CHtml::encode($model->Store_Store->Store_Content->name).'︱'.CHtml::encode($model->Store_Store->phone);?></span>
			</div>
			<div class="row enter">

				<input type="submit" value="保存" id="account_info">
				<a class="cancle" href="javascript:window.history.back();">取消</a>
			</div>
	</div>
</div>  <!--.content_box-->
<?php $this->endWidget(); ?>
</div><!-- form -->
<?php

if(Yii::app()->request->enableCsrfValidation)
{
	$csrfTokenName = Yii::app()->request->csrfTokenName;
	$csrfToken = Yii::app()->request->csrfToken;
	$csrf = "{'old_phone':old_phone,'phone':phone,'$csrfTokenName':'$csrfToken'}";
}else
	$csrf = "{'old_phone':old_phone,'phone':phone}";

Yii::app()->clientScript->registerScript('getcheckcod', '
	var getcheckcod=false;
jQuery(document).on("click", "#getcheckcod", function(){
	if(getcheckcod)
		return false;
	var phone    = $("#'.CHtml::activeId($model, 'phone').'").val();
	var old_phone = "'.$model->phone.'";
	if(jQuery.trim(phone)=="") {
		alert("\u624b\u673a\u53f7 \u4e0d\u53ef\u4e3a\u7a7a\u767d.");
		return false;
	}
	if(jQuery.trim(phone)!="" && !phone.match(/^1[34578][0-9]{9}$/)) {
		alert("\u624b\u673a\u53f7 \u65e0\u6548.");
		return false;
	}
	if(jQuery.trim(old_phone)=="") {
		alert("\u624b\u673a\u53f7 \u4e0d\u53ef\u4e3a\u7a7a\u767d.");
		return false;
	}
	if(jQuery.trim(old_phone)!="" && !phone.match(/^1[34578][0-9]{9}$/)) {
		alert("\u624b\u673a\u53f7 \u65e0\u6548.");
		return false;
	}
	jQuery.ajax({
		url: "'.Yii::app()->createUrl("/agent/agent_store/store_update_son_sms").'",
		dataType: "json",
		type: "POST",
		data:'.$csrf.',
		success: function(data) {
				if(typeof(data.verifyCode) !== "undefined")
				{
					alert(data.verifyCode);
					return false;
				}else{
					phone_countdown();
					getcheckcod=true;
        		}
			}
	});
	return false;
});
   function phone_countdown(){
		var count = '.Yii::app()->params['sms']['agent_create_store_son']['time'].';
		var countdown = setInterval(CountDown, 1000);
		function CountDown() {
			$("#getcheckcod").html("<a href=\"#\" class=\"getcheckcod\" style=\"cursor:wait;\">已发送,<font style=\"color:#fff;\">"+count+"</font>秒</a>");
			if (count == 0) {
				$("#getcheckcod").html("<a href=\"#\" class=\"getcheckcod\">获取验证码</a>");
				clearInterval(countdown);
				getcheckcod=false;
			}
			count--;
		}
	}
');
?>
