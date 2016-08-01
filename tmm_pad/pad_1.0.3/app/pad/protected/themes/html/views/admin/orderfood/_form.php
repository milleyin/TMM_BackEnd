<?php
/* @var $this OrderfoodController */
/* @var $model OrderFood */
/* @var $form CActiveForm */
?>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'order-food-form',
    //'focus'=>array($model, 'columnName'),
    'enableAjaxValidation'=>false,
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
    //'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>

    <p class="note">这些字段<span class="required">*</span>是必须的。</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'money'); ?>
        <?php echo $form->textField($model, 'money', array('size'=>20, 'maxlength'=>20)); ?>
        <?php echo $form->error($model,'money'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->