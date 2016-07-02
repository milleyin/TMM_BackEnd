<?php
/* @var $this Tmm_userController */
/* @var $model User */

$this->breadcrumbs=array(
    '用户管理页'=>array('admin'),
    '用户更新页',
);
?>
    <h1>更新 用户<font color='#eb6100'><?php echo $model->phone; ?></font></h1>

<?php
/* @var $this Tmm_userController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="form wide">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'refund-form',
        'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    )); ?>

    <p class="note">这些字段 <span class="required">*</span>是必须的.</p>

    <?php echo $form->errorSummary($model); ?>
    <div class="row">
        <?php echo $form->labelEx($model,'reason'); ?>
        <?php echo $form->textField($model,'reason',array('size'=>16,'maxlength'=>11)); ?>
        <?php echo $form->error($model,'reason'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->