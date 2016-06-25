<div class="content_box">
    <?php
    echo $this->breadcrumbs(array(
        '账号信息'=>array('account'),
        '财务信息'=>array('/agent/agent_agent/bank'),
        '绑定银行账户'
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
        ?>
        <div class="row-fluid">

  	    	<span class="hao">
                <div class="select">
                        <label>开户银行:</label>
                        <?php echo $form->dropDownList($model,'bank_id',array(''=>'--请选择--')+Bank::data()); ?>
                    </div>
            </span>
            <?php echo $form->error($model,'bank_id'); ?>
        </div>
        <div class="row-fluid">
  	    	<span class="hao">
                <label>开户支行:</label>
                <?php echo $form->textField($model,'bank_branch',array('size'=>40,'maxlength'=>40)); ?>
            </span>
            <?php echo $form->error($model,'bank_branch'); ?>
        </div>
        <div class="row-fluid">
  	    	<span class="hao">
  	    	    <label>开户姓名:</label>
                <?php echo $form->textField($model,'bank_name',array('size'=>10,'maxlength'=>10)); ?>
            </span>
            <?php echo $form->error($model,'bank_name'); ?>
        </div>
        <div class="row-fluid">
  	    	<span class="hao">
                <label>银行账号:</label>
                <?php echo $form->textField($model,'bank_code',array('size'=>40,'maxlength'=>40)); ?>
            </span>
            <?php echo $form->error($model,'bank_code'); ?>
        </div>
    <div class="xia">
        <?php echo CHtml::submitButton('保存'); ?>
    </div>

    <?php $this->endWidget(); ?>
</div><!--cantent-->


</div>  