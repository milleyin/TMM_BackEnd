
<div class="content_box" id="stepone">
<?php
	echo $this->breadcrumbs(array(
				'商家账号管理'=>array('admin'),
				'新增商家',
			));
?>
<div class="create_nav create_business_nav">
<div class="create_steap_one">
		<a class="done">1</a>
		<span class="text_done">账号信息</span>
		<div class="line line_first done"></div>
      </div>
      <div class="create_steap_two">
        <a class="undone">2</a>
        <span class="text_undone">公司信息</span>
        		<div class="line undone"></div>
      </div>
      <div class="create_steap_three">
        <a class="undone">3</a>
        <span class="text_undone">证照上传</span>
        		<div class="line undone"></div>
      </div>
      <div class="create_steap_four">
        <a class="undone">4</a>
        <span class="text_undone">添加标签</span>
        		<div class="line undone"></div>
        				</div>
        				<div class="create_steap_five">
        <a class="undone">5</a>
        		<span class="text_undone">注册完成</span>
        		<div class="line line_last undone"></div>
        				</div>
        				</div>    <!-- .create_nav -->
        				<div class="content create_business_content">
        						<div class="content_one">
       <?php
			$form = $this->beginWidget('CActiveForm', array(
			    'id'=>'store-user-form',
				'enableAjaxValidation'=>true,
			    'enableClientValidation'=>true,
			    'focus'=>array($model,'phone'),
			    'clientOptions'=>array(
			        'validateOnSubmit'=>true
			    ),
				'htmlOptions'=>array('class'=>'accont_info_form'),
			));
			?>		    						
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
        
        <div class="row content_password" >
	        <?php echo $form->label($model,'_pwd'); ?>
	        <?php 	echo $form->passwordField($model, '_pwd',array('class'=>'upassword'));?>
    		<?php echo $form->error($model,'_pwd'); ?>     
        </div>
      
        <div class="row content_confirm_passw" >
        	     <?php echo $form->label($model,'confirm_pwd'); ?>
       			<?php 	echo $form->passwordField($model, 'confirm_pwd',array('class'=>'upassword'));?>
    			<?php echo $form->error($model,'_pwd'); ?>    
        </div>
        
        <div class="row enter">
        		<?php echo CHtml::submitButton('下一步'); ?>
        </div>
		<?php $this->endWidget(); ?>
        
        
        </div>  <!-- .content_one -->
        </div>   <!--  .content -->

        <div class="copyright">
        <span>Copyright &copy; TMM365.com All Rights Reserved</span>
        </div>  <!--.copyright-->
</div>  <!--.content_box-->
<?php 

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
		alert("\u624b\u673a\u53f7 \u4e0d\u53ef\u4e3a\u7a7a\u767d.");
		return false;
	}
	if(jQuery.trim(phone)!="" && !phone.match(/^1[34578][0-9]{9}$/)) {
		alert("\u624b\u673a\u53f7 \u65e0\u6548.");
		return false;
	}
	jQuery.ajax({
		url: "'.Yii::app()->createUrl("/agent/agent_store/captcha_sms").'",
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
		var count = '.Yii::app()->params['sms']['agent_create_store']['time'].';
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