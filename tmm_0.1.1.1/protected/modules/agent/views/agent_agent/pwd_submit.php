<div class="content_box">
    <?php
    echo $this->breadcrumbs(array(
        '安全信息'=>array('safe'),
        '修改密码'
    ));
    ?>
    <div class="cantent1">
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
            <div class="row-fluid">
  	    	<span class="hao1">
                <label>新密码:</label>
                <?php echo $form->passwordField($model,'_pwd',array('placeholder'=>'6-15位字母、数字组合','class'=>'messcode')); ?>
            </span>
                <?php echo $form->error($model,'_pwd'); ?>
            </div>
            <div class="row-fluid">
  			<span class="hao2">
                <label>确认密码:</label>
                <?php echo $form->passwordField($model,'confirm_pwd',array('placeholder'=>'再次输入密码','class'=>'messcode')); ?>
            </span>
                <?php echo $form->error($model,'confirm_pwd'); ?>
            </div>
            <div class="xia1">
                <?php echo CHtml::submitButton('保存'); ?>
            </div>
        <?php $this->endWidget(); ?>
    </div><!--cantent-->
</div>