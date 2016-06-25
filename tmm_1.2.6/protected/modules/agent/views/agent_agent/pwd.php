<div class="content_box">
    <?php
    echo $this->breadcrumbs(array(
        '安全信息'=>array('safe'),
        '修改密码'
    ));
    ?>
    <div class="cantent">
        <!--获取验证码的表单-->
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
        ?>
        请先输入安全手机验证码来验证身份
        <div class="row-fluid">
            <label>图形验证码:</label>
            <?php echo $form->textField($model,'verify',array('class'=>'code')); ?>
            <?php
            $this->widget('CCaptcha',array(
                'showRefreshButton'=>false,
                'clickableImage'=>true,
                'imageOptions'=>array('alt'=>'加载中……','title'=>'点击换图','class'=>'checkcodeimg','style'=>'cursor:pointer'),
            ));
            ?>
            <?php echo $form->error($model,'verify'); ?>
        </div>

        <div class="row-fluid">
            <label>手机验证码:</label>
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
<?php

if(Yii::app()->request->enableCsrfValidation)
{
    $csrfTokenName = Yii::app()->request->csrfTokenName;
    $csrfToken = Yii::app()->request->csrfToken;
    $csrf = "{'verifyCode':verifyCode,'$csrfTokenName':'$csrfToken'}";
}else
    $csrf = "{'verifyCode':verifyCode}";


Yii::app()->clientScript->registerScript('getcheckcod', '
	var getcheckcod=false;
jQuery(document).on("click", "#getcheckcod", function(){
	if(getcheckcod)
		return false;
	var verifyCode   = $("#'.CHtml::activeId($model, 'verify').'").val();

	if(jQuery.trim(verifyCode)=="") {
		alert("\u9a8c\u8bc1\u7801 \u4e0d\u53ef\u4e3a\u7a7a\u767d");
		return false;
	}
	var hash = jQuery("body").data("captcha.hash");
	if (hash != null)
	{
		hash = hash[1];
		for(var i=verifyCode.length-1, h=0; i >= 0; --i) h+=verifyCode.toLowerCase().charCodeAt(i);
		if(h != hash) {
			alert("\u9a8c\u8bc1\u7801\u4e0d\u6b63\u786e");
			return false;
		}
	}

	jQuery.ajax({
		url: "'.Yii::app()->createUrl("/agent/agent_agent/captcha_pwd_sms").'",
		dataType: "json",
		type: "POST",
		data:'.$csrf.',
		success: function(data) {
				if(typeof(data.verifyCode) !=="undefined")
				{
					alert(data.verifyCode);
					return false;
				}
				else if(typeof(data.phone) !== "undefined")
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
		var count = '.Yii::app()->params['sms']['agent_update_pwd_phone']['time'].';
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