<?php
/* @var $this Tmm_passwordController */
/* @var $model Password */
/* @var $form CActiveForm */
?>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'password-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">这些字段 <span class="required">*</span>是必须的.</p>

	<?php echo $form->errorSummary($model); ?>
		
	<div class="row">
		<?php echo $form->labelEx($model,'_pwd'); ?>
		<?php echo $form->passwordField($model,'_pwd',array('size'=>18,'maxlength'=>6)); ?>
		<?php echo $form->error($model,'_pwd'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'_confirm_pwd'); ?>
		<?php echo $form->passwordField($model,'_confirm_pwd',array('size'=>18,'maxlength'=>6)); ?>
		<?php echo $form->error($model,'_confirm_pwd'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->