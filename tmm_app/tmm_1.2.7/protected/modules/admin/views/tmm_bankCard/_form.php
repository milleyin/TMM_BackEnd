<?php
/* @var $this Tmm_bankCardController */
/* @var $model BankCard */
/* @var $form CActiveForm */
?>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'bank-card-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">这些字段 <span class="required">*</span>是必须的.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'bank_id'); ?>
		<?php echo $form->dropDownList($model,'bank_id',array(''=>'--请选择--')+Bank::data()); ?>
		<?php echo $form->error($model,'bank_id'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'bank_branch'); ?>
		<?php echo $form->textField($model,'bank_branch',array('size'=>40,'maxlength'=>40)); ?>
		<?php echo $form->error($model,'bank_branch'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'bank_name'); ?>
		<?php echo $form->textField($model,'bank_name',array('size'=>40,'maxlength'=>40)); ?>
		<?php echo $form->error($model,'bank_name'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'bank_code'); ?>
		<?php echo $form->textField($model,'bank_code',array('size'=>40,'maxlength'=>40)); ?>
		<?php echo $form->error($model,'bank_code'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'bank_identity'); ?>
		<?php echo $form->textField($model,'bank_identity',array('size'=>40,'maxlength'=>40)); ?>
		<?php echo $form->error($model,'bank_identity'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'bank_type'); ?>
		<?php echo $form->dropDownList($model,'bank_type',array(''=>'--请选择--')+BankCard::$_bank_type); ?>
		<?php echo $form->error($model,'bank_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'is_default'); ?>
		<?php echo $form->dropDownList($model,'is_default',array(''=>'--请选择--')+BankCard::$_is_default); ?>
		<?php echo $form->error($model,'is_default'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'is_verify'); ?>
		<?php echo $form->dropDownList($model,'is_verify',array(''=>'--请选择--')+BankCard::$_is_verify); ?>
		<?php echo $form->error($model,'is_verify'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->