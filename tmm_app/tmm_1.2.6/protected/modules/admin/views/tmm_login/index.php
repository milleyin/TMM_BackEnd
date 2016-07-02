<?php
   $form = $this->beginWidget('CActiveForm', array(
     			'id'=>'login-form',
     			//'enableAjaxValidation'=>true,
     			'enableClientValidation'=>true,
     			'focus'=>array($model,'username'),			 
     			'clientOptions'=>array(
     					'validateOnSubmit'=>true
     			)
     	));
     ?>
    <h1>登 录</h1>
    <div class="row">
    	<?php 
    	//echo $form->labelEx($model, 'Username');
    	echo $form->textField($model, 'username',array('placeholder'=>'用户名'));
    	echo $form->error($model, 'username',array(),false);
    	?>
        
    </div>
   <div class="row">
        <?php 
        //echo $form->labelEx($model,'Password');
        echo $form->passwordField($model,'password',array('placeholder'=>'密码'));
        echo $form->error($model,'password',array(), false);
        ?>
   </div>
   
   	<div class="hint">
   	<?php
   		$this->widget('CCaptcha',array(
			'showRefreshButton'=>true,
			'buttonLabel'=>'看不清？',
			'clickableImage'=>true,
			'imageOptions'=>array('alt'=>'加载中……','title'=>'点击换图','style'=>'margin:0 5px 0 0;cursor:pointer;'),
		));
   	?>
   	 </div>
   	<div class="row">
   		<?php 
   		//echo $form->labelEx($model,'verifyCode');
   		echo $form->textField($model,'verifyCode',array('placeholder'=>'验证码'));
   		echo $form->error($model,'verifyCode',array(),false);	
   		echo $form->error($model,'alert');
   		?>
   	</div>
    <div class="hint">请输入验证码，不区分大小写。</div>
   <div class="buttons">
        <?php echo CHtml::submitButton('登录',array('style'=>'cursor:pointer'));?>   
	</div>
   <?php $this->endWidget();?>
	<?php
		if(!! $info = Yii::app()->admin->getFlash(Admin::login_unique_info))
		{
			Yii::app()->clientScript->registerScript(Admin::login_unique_info, '
	alert(\''.$info.'\');
			');//CClientScript::POS_LOAD);
		}
	?>
