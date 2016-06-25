<?php
/* @var $this Tmm_organizerController */
/* @var $model Organizer */

$this->breadcrumbs=array(
   	'密码管理页'=>array('admin'),
	'选择 ' . Password::$_role_type[$model->role_type] . '密码类型',
);
?>
<h1><?php CHtml::encode('选择 ' . Password::$_role_type[$model->role_type] . '密码类型'); ?></h1>
<div class="form wide">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'password-form',
        'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    )); ?>
    <?php echo $form->errorSummary($model); ?>
    <div class="row">
        <?php echo $form->labelEx($model,'password_type'); ?>
        <?php echo $form->dropDownList($model,'password_type',array(''=>'--请选择--')+$model::$_password_type); ?>
        <?php echo $form->error($model,'password_type'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('选择'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->