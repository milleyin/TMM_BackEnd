<?php
    $this->addCss($this->getAssets() . '/css/login/index.css');
    $form = $this->beginWidget('CActiveForm', array(
             'id'=>'login-form',
               'focus'=>array($model, 'username'),
               'enableAjaxValidation'=>false,
               'enableClientValidation'=>true,
               'clientOptions'=>array(
                   'validateOnSubmit'=>true,
               ),
         ));
     ?>
    <h1>登 录</h1>
    <div class="row">
        <?php 
        echo $form->textField($model, 'username', array('placeholder'=>$model->getAttributeLabel('username')));
        echo $form->error($model, 'username', array(), false);
        ?>
        
    </div>
   <div class="row">
        <?php 
        echo $form->passwordField($model, 'password', array('placeholder'=>$model->getAttributeLabel('password')));
        echo $form->error($model,'password', array(), false);
        ?>
   </div>
       <div class="hint">
       <?php
           $this->widget('CCaptcha',array(
            'showRefreshButton'=>true,
            'buttonLabel'=>'看不清？',
            'clickableImage'=>true,
            'imageOptions'=>array('alt'=>'加载中……', 'title'=>'点击换图', 'style'=>'margin:0 5px 0 0;cursor:pointer;'),
        ));
       ?>
        </div>
       <div class="row">
           <?php 
               echo $form->textField($model, 'verifycode', array('placeholder'=>$model->getAttributeLabel('verifycode')));
               echo $form->error($model, 'verifycode', array(), false);    
           ?>
       </div>
    <div class="hint">请输入验证码，不区分大小写。</div>
   <div class="buttons">
        <?php echo CHtml::submitButton('登录', array('style'=>'cursor:pointer'));?>   
    </div>
   <?php $this->endWidget();?>