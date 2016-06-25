<?php
/* @var $this Tmm_organizerController */
/* @var $model Organizer */

$this->breadcrumbs=array(
    '运营商管理页'=>array('admin'),
    $model->phone=>array('view','id'=>$model->id),
    '更新运营商银行信息',
);
?>
<h1>更新运营商银行信息<font color='#eb6100'><?php echo $model->phone; ?></font></h1>

<div class="form wide">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'agent-form',
        'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    )); ?>
    <p class="note">这些字段 <span class="required">*</span>是必须的.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'bank_id'); ?>
        <?php echo $form->dropDownList($model,'bank_id',array(''=>'--请选择--')+Bank::data()); ?>
        <?php echo $form->error($model,'bank_id'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model,'bank_branch'); ?>
        <?php echo $form->textField($model,'bank_branch',array('size'=>40,'maxlength'=>40)); ?>
        <?php echo $form->error($model,'bank_branch'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model,'bank_name'); ?>
        <?php echo $form->textField($model,'bank_name',array('size'=>10,'maxlength'=>10)); ?>
        <?php echo $form->error($model,'bank_name'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model,'bank_code'); ?>
        <?php echo $form->textField($model,'bank_code',array('size'=>40,'maxlength'=>40)); ?>
        <?php echo $form->error($model,'bank_code'); ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton('保存'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->