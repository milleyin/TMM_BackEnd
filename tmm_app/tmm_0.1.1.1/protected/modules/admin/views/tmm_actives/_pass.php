<?php
/* @var $this Tmm_auditLogController */
/* @var $model AuditLog */
/* @var $form CActiveForm */
?>
<div class="form wide">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'actives-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>
	<p class="note">这些字段 <span class="required">*</span>是必须的.</p>
	<?php echo $form->errorSummary($model); ?>
	<div class="row">
		<?php echo $form->labelEx($model,'push'); ?>
		<?php echo $form->textField($model,'push'); ?>%
		<?php echo $form->error($model,'push'); ?>
	</div>
		<div class="row">
		<?php echo $form->labelEx($model,'push_orgainzer'); ?>
		<?php echo $form->textField($model,'push_orgainzer'); ?>%
		<?php echo $form->error($model,'push_orgainzer'); ?>
	</div>
		<div class="row">
		<?php echo $form->labelEx($model,'push_store'); ?>
		<?php echo $form->textField($model,'push_store'); ?>%
		<?php echo $form->error($model,'push_store'); ?>
	</div>
		<div class="row">
		<?php echo $form->labelEx($model,'push_agent'); ?>
		<?php echo $form->textField($model,'push_agent'); ?>%
		<?php echo $form->error($model,'push_agent'); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '确定' : '保存'); ?>
	</div>
<?php $this->endWidget(); ?>

</div><!-- form -->