<?php
/* @var $this Tmm_smsLogController */
/* @var $model SmsLog */
/* @var $form CActiveForm */
?>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'sms-log-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">这些字段 <span class="required">*</span>是必须的.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'phone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sms_type'); ?>
		<?php echo $form->textField($model,'sms_type',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'sms_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'role_type'); ?>
		<?php echo $form->textField($model,'role_type',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'role_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sms_use'); ?>
		<?php echo $form->textField($model,'sms_use',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'sms_use'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>6,'maxlength'=>6)); ?>
		<?php echo $form->error($model,'code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sms_content'); ?>
		<?php echo $form->textField($model,'sms_content',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'sms_content'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sms_source'); ?>
		<?php echo $form->textField($model,'sms_source',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'sms_source'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'login_address'); ?>
		<?php echo $form->textField($model,'login_address',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'login_address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sms_error'); ?>
		<?php echo $form->textField($model,'sms_error',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'sms_error'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->