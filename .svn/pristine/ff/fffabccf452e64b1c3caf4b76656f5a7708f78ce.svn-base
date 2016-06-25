<div class="content_box">
    <?php 
	echo $this->breadcrumbs(array(
		'安全手机号'=>array('safe'),
		'更换手机号',
		$model->phone
	));
    ?>
  	<div class="cantent">
  	    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id'=>'agent-form',
        'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
        'focus'=>array($model,'phone'),
        'clientOptions'=>array(
            'validateOnSubmit'=>true
        ),
        'htmlOptions'=>array('class'=>'messlogin_form'),
    ));
    $model->phone='';//清空默认值
    ?>
  			<div class="row-fluid">
	  	    	<span class="hao">
	  	    	<label>新绑定手机号:</label>
	  	    	<?php echo $form->textField($model,'phone',array('class'=>'messcode')); ?>	  		
	  			<?php echo $form->error($model,'phone',array(),false); ?>
  			</div>
	  		<div class="row-fluid">
		  		手机验证码:
		  		<?php echo $form->textField($model,'sms',array('class'=>'messcode')); ?>		  		
				 <span id="getcheckcod">
		            	<a href="#" class="getcheckcod" >获取验证码</a>
		        </span> 
  				<?php echo $form->error($model,'sms'); ?>
		  	</div>  	
		  	<div class="xia">
		  		<?php echo CHtml::submitButton('下一步'); ?>	
		  	</div>
  		<?php $this->endWidget(); ?>
  	</div>
  	</div>
  	<!--cantent-->

  <?php
Yii::app()->clientScript->registerScript('search', "
	var int_alert=true;
	$('.cantent form').submit(function(){
			if(int_alert)
			{
				int_alert=false;
				 if(!confirm('确定更换安全手机号码吗？')) return false;
			}			
	});
			
");
if(Yii::app()->request->enableCsrfValidation)
{
    $csrfTokenName = Yii::app()->request->csrfTokenName;
    $csrfToken = Yii::app()->request->csrfToken;
    $csrf = "{'phone':phone,'$csrfTokenName':'$csrfToken'}";
}else
    $csrf = "{'phone':phone}";


Yii::app()->clientScript->registerScript('getcheckcod', '
	var getcheckcod=false;
jQuery(document).on("click", "#getcheckcod", function(){
	if(getcheckcod)
		return false;
	var phone    = $("#'.CHtml::activeId($model, 'phone').'").val();
	if(jQuery.trim(phone)=="") {
		alert("\u624b\u673a\u53f7 \u4e0d\u53ef\u4e3a\u7a7a\u767d");
		return false;
	}
	if(jQuery.trim(phone)!="" && !phone.match(/^1[34578][0-9]{9}$/)) {
		alert("\u624b\u673a\u53f7 \u65e0\u6548");
		return false;
	}
	jQuery.ajax({
		url: "'.Yii::app()->createUrl("/agent/agent_agent/captcha_new_sms").'",
		dataType: "json",
		type: "POST",
		data:'.$csrf.',
		success: function(data) {
				if(typeof(data.phone) !== "undefined")
				{
					alert(data.phone);
					return false;
				}else	{
					phone_countdown();
					getcheckcod=true;
				}
			}
	});
	return false;
});
   function phone_countdown(){
		var count = '.Yii::app()->params['sms']['agent_update_new_phone']['time'].';
		var countdown = setInterval(CountDown, 1000);
		function CountDown() {
			$("#getcheckcod").html("<a href=\"#\" class=\"getcheckcod\" style=\"cursor:wait;\">已发送,<font style=\"color:#9f002d;\">"+count+"</font>秒</a>");
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