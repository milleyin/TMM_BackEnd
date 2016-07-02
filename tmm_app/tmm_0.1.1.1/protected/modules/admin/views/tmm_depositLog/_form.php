<?php
/* @var $this Tmm_depositLogController */
/* @var $model DepositLog */
/* @var $form CActiveForm */
?>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'deposit-log-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">这些字段 <span class="required">*</span>是必须的.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'deposit_who'); ?>
		<?php echo $form->textField($model,'deposit_who'); ?>
		<?php echo $form->error($model,'deposit_who'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'deposit'); ?>
		<?php echo $form->textField($model,'deposit',array('size'=>13,'maxlength'=>13)); ?>
		<?php echo $form->error($model,'deposit'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'deposit_status'); ?>
		<?php echo $form->textField($model,'deposit_status'); ?>
		<?php echo $form->error($model,'deposit_status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'reason'); ?>
		<?php echo $form->textField($model,'reason',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'reason'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->