<?php
/* @var $this Tmm_auditLogController */
/* @var $model AuditLog */
/* @var $form CActiveForm */
?>
<div class="form wide">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'audit-log-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>
	<p class="note">这些字段 <span class="required">*</span>是必须的.</p>	
	<?php echo $form->errorSummary($model); ?>
	<div class="row">
		<?php echo $form->labelEx($model,'info'); ?>
		<?php echo $form->textArea($model,'info',array('style'=>'width:250px;height:100px;')); ?>
		<?php echo $form->error($model,'info'); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '确定' : '保存'); ?>
	</div>
<?php $this->endWidget(); ?>

</div><!-- form -->