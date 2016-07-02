<?php
/* @var $this Tmm_organizerController */
/* @var $model Organizer */

$this->breadcrumbs=array(
    '运营商管理页'=>array('admin'),
    $model->phone=>array('view','id'=>$model->id),
    '设置分成比例',
);
?>
<h1>设置分成比例<font color='#eb6100'><?php echo $model->phone; ?></font></h1>
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
    <div class="row">
        <?php echo $form->labelEx($model,'push'); ?>
        <?php echo $form->textField($model,'push'); ?>%
        <?php echo $form->error($model,'push'); ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton('保存'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->