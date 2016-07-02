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
        <?php echo $form->labelEx($model, 'number'); ?>
        <?php echo $form->textField($model, 'number',array('size'=>11, 'maxlength'=>11)); ?>
        <span class="hint">抽奖次数（每天可以获取的抽奖次数）</span>
        <?php echo $form->error($model, 'number'); ?>
    </div>
    
        
    <div class="row">
        <?php echo $form->labelEx($model->Config_Upload, 'path'); ?>
        <?php echo $form->fileField($model->Config_Upload, 'path'); ?>
        <span class="hint">抽奖转盘图片：仅支持2M以内，jpg, png, jpeg, gif等格式，图片规格：664*664，图片平均分8份，指针右边第一个为奖品位置一（顺时针）。</span>
        <?php echo $form->error($model->Config_Upload, 'path'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model->Config_Upload, 'info'); ?>
        <?php echo $form->textField($model->Config_Upload, 'info', array('size'=>50, 'maxlength'=>100, 'placeholder'=>'抽奖转盘图片')); ?>
        <?php echo $form->error($model->Config_Upload, 'info'); ?>
    </div>
    
    <?php 
        if (file_exists($model->Config_Upload->getAbsolutePath()))
        {
    ?>
        <div class="row">
            <?php echo $form->label($model->Config_Upload, '_old_path'); ?>
            <?php echo CHtml::image($model->Config_Upload->getUrlPath(), CHtml::encode($model->Config_Upload->info), array('style'=>'widht:150px;height:150px;')); ?>        
        </div>
    <?php 
        }
    ?>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'info'); ?>
        <?php echo $form->textArea($model, 'info',array('style'=>'width:400px;height:100px;', 'maxlength'=>128)); ?>
        <?php echo $form->error($model, 'info'); ?>
    </div>
    

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->