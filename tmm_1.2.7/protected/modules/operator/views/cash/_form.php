<?php
/* @var $this CashController */
/* @var $model Cash */
/* @var $form CActiveForm */
?>

<div class="form wide">

<?php $form = $this->beginWidget('CActiveForm', array(
	'id'=>'cash-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">这些字段 <span class="required">*</span>是必须的.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row value">
		<?php echo $form->labelEx($model->Cash_Bank,'name'); ?>
		<?php echo $model->Cash_Bank->name; ?>
	</div>
	
	<div class="row value">
		<?php echo $form->labelEx($model,'bank_branch'); ?>
		<?php echo $model->bank_branch; ?>
	</div>
	
	<div class="row value">
		<?php echo $form->labelEx($model,'bank_name'); ?>
		<?php echo $model->bank_name; ?>
	</div>

	<div class="row value">
		<?php echo $form->labelEx($model,'bank_code'); ?>
		<?php echo $model->bank_code; ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'price'); ?>
		<?php echo $form->textField($model, 'price',array('size'=>20, 'maxlength'=>10, 'placeholder'=>'本次最多提现 ' . $model->accountModel->money . ' 元')); ?>
		<?php echo $form->error($model, 'price'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('下一步'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->