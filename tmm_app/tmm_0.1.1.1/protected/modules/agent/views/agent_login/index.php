<div class="login">
<!--logo-->
	<div class="login_logo">
		<div class="row-fluid logo">
		<?php echo CHtml::image(Yii::app()->request->baseUrl.'/css/agent/images/logo.png')?>
		</div>
		<div class="row-fluid title">
			<span><?php echo $this->name; ?></span> 
		</div>
	</div>
<!--输入框-->
	<div class="row-fluid login_content">
		<div class="span1 content_left">
			<div class="row-fluid">
				<a href="#" role="button" class="active" id="password_login">密<br/>码<br/>登<br/>录<br/></a>
			</div>
			<div class="row-fluid">
				<a href="#" class="noActive" id="mess_login">信<br/>息<br/>登<br/>录</a>
			</div> <!--登录方式切换-->			
		</div>
		<div class="span1"></div>

		<div class="span10 content_right">
			<!--信息登录的表单-->
			<?php
			$form = $this->beginWidget('CActiveForm', array(
			    'id'=>'login-form-sms',
			    //'enableAjaxValidation'=>true,
			    'enableClientValidation'=>true,
			    'focus'=>array($model_sms,'phone'),
			    'clientOptions'=>array(
			        'validateOnSubmit'=>true
			    ),
				'htmlOptions'=>array('class'=>'messlogin_form'),
			));
			?>
				<div class="row-fluid content_phone error">
					<?php   
						echo $form->textField($model_sms, 'phone',array('placeholder'=>'手机号码','class'=>'phone'));
					?>
				 <span class="check_icon"></span>		
					 <?php echo $form->error($model_sms, 'phone',array(),false);?>
				 </div>
				<div class="row-fluid content_checkcode">
					<?php   
						echo $form->textField($model_sms,'verifyCode',array('placeholder'=>'验证码','class'=>'code'));
					?>
				 <?php
				    $this->widget('CCaptcha',array(
						'showRefreshButton'=>false,
				        'clickableImage'=>true,
				        'imageOptions'=>array('alt'=>'加载中……','title'=>'点击换图','class'=>'checkcodeimg','style'=>'cursor:pointer'),
				    ));
				    ?>
					<?php echo $form->error($model_sms, 'verifyCode',array(),false);?>
				</div>
				<div class="row-fluid content_messcode">  
					<?php   
						echo $form->textField($model_sms,'sms',array('placeholder'=>'短信验证码','class'=>'messcode'));
					?>
					<span id="getcheckcod"><a href="#" class="getcheckcod">获取验证码</a></span>	
					<?php echo $form->error($model_sms, 'sms',array(),false);?>
				</div>
				<div class="row-fluid enter">
				        <?php echo CHtml::submitButton('进入',array('style'=>'cursor:pointer'));?>   
				</div>
			  <?php $this->endWidget();?>
			<!--密码登录的表单-->
				
			<?php
			$form = $this->beginWidget('CActiveForm', array(
			    'id'=>'login-form-pwd',
			    //'enableAjaxValidation'=>true,
			    'enableClientValidation'=>true,
			    'focus'=>array($model_pwd,'phone'),
			    'clientOptions'=>array(
			        'validateOnSubmit'=>true
			    ),
				'htmlOptions'=>array('class'=>'passwordlogin_form'),
			));
			?>
				<div class="row-fluid  content_phone error">
					<?php   
						echo $form->textField($model_pwd, 'phone',array('placeholder'=>'手机号码','class'=>'phone'));
					?>
	 	 			<div class="check_icon"></div>
	 	 			 <?php echo $form->error($model_pwd, 'phone',array(),false);?>
				</div>
				<div class="row-fluid has-feedback content_password" >  
					<?php   
						echo $form->passwordField($model_pwd, 'password',array('placeholder'=>'密码','class'=>'upassword'));
					?>
					<span class="form-control-feedback" aria-hidden="true">
	 	 			   <div class="check_icon"></div>
	  				 </span>
	  				<?php echo $form->error($model_pwd, 'password',array(),false);?>
				</div>
				<div class="row-fluid content_checkcode">
				<?php   
						echo $form->textField($model_pwd,'verifyCode',array('placeholder'=>'请输入验证码','class'=>'code'));
				?>
				<?php
				    $this->widget('CCaptcha',array(
						'showRefreshButton'=>false,
				        'clickableImage'=>true,
				        'imageOptions'=>array('alt'=>'加载中……','title'=>'点击换图','class'=>'checkcodeimg','style'=>'cursor:pointer'),
				    ));
				    ?>
					<?php echo $form->error($model_pwd, 'verifyCode',array(),false);?>
				</div>
				<div class="row-fluid enter">
					<?php echo CHtml::submitButton('进入',array('style'=>'cursor:pointer'));?>   
				</div>
			 <?php $this->endWidget();?>

		</div>  <!--.content_right-->
	</div> <!--.login_content-->
	<div class="row-fluid copyright">
		<span>Copyright © TMM365.com All Rights Reserved</span>
	</div>
</div>	<!--.login-->
<?php
Yii::app()->clientScript->registerScript('select_login', '	
function mess_login(){
	$(".passwordlogin_form").css("display","none");
	$(".messlogin_form").css("display","block");		
	$("#mess_login").addClass("active").removeClass("noActive");
	$("#password_login").removeClass("active").addClass("noActive");	
}
function password_login(){
	$(".messlogin_form").css("display","none");
	$(".passwordlogin_form").css("display","block");
	$("#password_login").addClass("active").removeClass("noActive");
	$("#mess_login").addClass("noActive").removeClass("active");
}		
//信息登录
$("#mess_login").click(function(){
	mess_login();
	return false;
})
//密码登录
$("#password_login").click(function(){	
	password_login();
	return false;
})
'.(isset($_POST['AgentSmsLoginForm'])?"mess_login();":"").'
');

if(Yii::app()->request->enableCsrfValidation)
{
	$csrfTokenName = Yii::app()->request->csrfTokenName;
	$csrfToken = Yii::app()->request->csrfToken;
	$csrf = "{'phone':phone,'verifyCode':verifyCode,'$csrfTokenName':'$csrfToken'}";
}else
	$csrf = "{'phone':phone,'verifyCode':verifyCode}";

Yii::app()->clientScript->registerScript('getcheckcod', '
	var getcheckcod=false;
jQuery(document).on("click", "#getcheckcod", function(){
	if(getcheckcod)
		return false;
	var phone    = $("#'.CHtml::activeId($model_sms, 'phone').'").val();
	var verifyCode   = $("#'.CHtml::activeId($model_sms, 'verifyCode').'").val();
	if(jQuery.trim(phone)=="") {
		alert("\u624b\u673a\u53f7 \u4e0d\u53ef\u4e3a\u7a7a\u767d.");
		return false;
	}
	if(jQuery.trim(verifyCode)=="") {
		alert("\u9a8c\u8bc1\u7801 \u4e0d\u53ef\u4e3a\u7a7a\u767d.");
		return false;
	}
	var hash = jQuery("body").data("captcha.hash");
	if (hash != null)
	{
		hash = hash[1];
		for(var i=verifyCode.length-1, h=0; i >= 0; --i) h+=verifyCode.toLowerCase().charCodeAt(i);
		if(h != hash) {
			alert("\u9a8c\u8bc1\u7801\u4e0d\u6b63\u786e.");
			return false;
		}
	}
	if(jQuery.trim(phone)!="" && !phone.match(/^1[34578][0-9]{9}$/)) {
		alert("\u624b\u673a\u53f7 \u65e0\u6548.");
		return false;
	}
	
	jQuery.ajax({
		url: "'.Yii::app()->createUrl("/agent/agent_login/captcha_sms").'",
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
		var count = '.Yii::app()->params['sms']['agent_login']['time'].';
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
