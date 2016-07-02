<?php
/* @var $this Tmm_organizerController */
/* @var $model Organizer */

$this->breadcrumbs=array(
    '运营商管理页'=>array('admin'),
    $model->DepositLog_Agent->phone=>array('view','id'=>$model->DepositLog_Agent->id),
    '设置运营商保证金',
);
?>
<h1>设置运营商保证金<font color='#eb6100'><?php echo $model->DepositLog_Agent->phone; ?></font></h1>
<div class="form wide">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'agent-form',
        'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    )); ?>
    <p class="note">这些字段 <span class="required">*</span>是必须的</p>
    <?php echo $form->errorSummary($model); ?>
    <div class="row value">
        <?php echo $form->label($model->DepositLog_Agent,'deposit'); ?>
        <?php echo $model->DepositLog_Agent->deposit;?> 元
    </div>
    <div class="row">
        <?php echo $form->labelEx($model,'deposit_status'); ?>
        <?php echo $form->dropDownList($model,'deposit_status',array(''=>'--请选择--')+$model::$_deposit_status); ?>
        <?php echo $form->error($model,'deposit_status'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model,'deposit'); ?>
        <?php echo $form->textField($model,'deposit',array('size'=>15,'maxlength'=>10)); ?>
        <?php echo $form->error($model,'deposit'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model,'reason'); ?>
        <?php echo $form->textArea($model,'reason',array('style'=>'width:300px;height:100px;')); ?>
        <?php echo $form->error($model,'reason'); ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton('保存'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->