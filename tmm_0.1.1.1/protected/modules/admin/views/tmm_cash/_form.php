<?php
/* @var $this Tmm_cashController */
/* @var $model Cash */
/* @var $form CActiveForm */
?>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cash-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">这些字段 <span class="required">*</span>是必须的.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'cash_type'); ?>
		<?php echo $form->textField($model,'cash_type',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'cash_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'remark_first'); ?>
		<?php echo $form->textField($model,'remark_first',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'remark_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'remark_double'); ?>
		<?php echo $form->textField($model,'remark_double',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'remark_double'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'remark_submit'); ?>
		<?php echo $form->textField($model,'remark_submit',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'remark_submit'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'money'); ?>
		<?php echo $form->textField($model,'money',array('size'=>13,'maxlength'=>13)); ?>
		<?php echo $form->error($model,'money'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'price'); ?>
		<?php echo $form->textField($model,'price',array('size'=>13,'maxlength'=>13)); ?>
		<?php echo $form->error($model,'price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fee_price'); ?>
		<?php echo $form->textField($model,'fee_price',array('size'=>13,'maxlength'=>13)); ?>
		<?php echo $form->error($model,'fee_price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fact_price'); ?>
		<?php echo $form->textField($model,'fact_price',array('size'=>13,'maxlength'=>13)); ?>
		<?php echo $form->error($model,'fact_price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bank_name'); ?>
		<?php echo $form->textField($model,'bank_name',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'bank_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bank_branch'); ?>
		<?php echo $form->textField($model,'bank_branch',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'bank_branch'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bank_code'); ?>
		<?php echo $form->textField($model,'bank_code',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'bank_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bank_type'); ?>
		<?php echo $form->textField($model,'bank_type',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'bank_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cash_status'); ?>
		<?php echo $form->textField($model,'cash_status'); ?>
		<?php echo $form->error($model,'cash_status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'audit_status'); ?>
		<?php echo $form->textField($model,'audit_status'); ?>
		<?php echo $form->error($model,'audit_status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pay_type'); ?>
		<?php echo $form->textField($model,'pay_type'); ?>
		<?php echo $form->error($model,'pay_type'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->