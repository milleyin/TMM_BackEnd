<?php
/* @var $this ConfigController */
/* @var $model Config */
/* @var $form CActiveForm */
?>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'config-form',
    'focus'=>array($model, 'number'),
    'enableAjaxValidation'=>true,
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
    'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>

    <p class="note">这些字段<span class="required">*</span>是必须的。</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row value">
        <?php echo $form->label($model->Config_Pad->Pad_Store, 'store_name'); ?>
        <?php echo CHtml::encode($model->Config_Pad->Pad_Store->store_name); ?>
    </div>
    
    <div class="row value">
        <?php echo $form->label($model->Config_Pad->Pad_Store, 'phone'); ?>
        <?php echo CHtml::encode($model->Config_Pad->Pad_Store->phone); ?>
    </div>
    
    <div class="row value">
        <?php echo $form->label($model->Config_Pad, 'name'); ?>
        <?php echo CHtml::encode($model->Config_Pad->name); ?>
    </div>
    
    <div class="row value">
        <?php echo $form->label($model->Config_Pad, 'number'); ?>
        <?php echo CHtml::encode($model->Config_Pad->number); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'type'); ?>
        <?php echo $form->dropDownList($model, 'type', $model::$_type); ?>
        <span class="hint">（请勿频繁修改！！！）</span>
        <?php echo $form->error($model, 'type'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'money'); ?>
        <?php echo $form->textField($model, 'money',array('size'=>11, 'maxlength'=>11)); ?>
        <span class="hint">元（每次获取抽奖机会需要的付费金额，免费必须等于零，付费必须大于0。请勿频繁修改！！！）</span>
        <?php echo $form->error($model, 'money'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'chance_number'); ?>
        <?php echo $form->textField($model, 'chance_number',array('size'=>11, 'maxlength'=>11)); ?>
        <span class="hint">抽奖机会次数（每天可以获取的抽奖机会次数，默认每天1次，-1表示不限制）</span>
        <?php echo $form->error($model, 'chance_number'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'number'); ?>
        <?php echo $form->textField($model, 'number',array('size'=>11, 'maxlength'=>11)); ?>
        <span class="hint">抽奖次数（每次抽奖机会可以获取的抽奖次数）</span>
        <?php echo $form->error($model, 'number'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'info'); ?>
        <?php echo $form->textArea($model, 'info',array('style'=>'width:600px;height:150px;', 'maxlength'=>128)); ?>
        <?php echo $form->error($model, 'info'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'ad_url'); ?>
        <?php echo $form->textField($model, 'ad_url',array('size'=>70, 'maxlength'=>128)); ?>
        <?php echo $form->error($model, 'ad_url'); ?>
    </div>
    
    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->