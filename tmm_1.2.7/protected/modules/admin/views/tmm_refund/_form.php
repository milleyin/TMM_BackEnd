<?php
/* @var $this Tmm_refundController */
/* @var $model Refund */
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
		<?php echo $form->textField($model,'reason',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'reason'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'is_organizer'); ?>
		<?php echo $form->textField($model,'is_organizer'); ?>
		<?php echo $form->error($model,'is_organizer'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'push'); ?>
		<?php echo $form->textField($model,'push'); ?>
		<?php echo $form->error($model,'push'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user_push'); ?>
		<?php echo $form->textField($model,'user_push'); ?>
		<?php echo $form->error($model,'user_push'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'push_orgainzer'); ?>
		<?php echo $form->textField($model,'push_orgainzer'); ?>
		<?php echo $form->error($model,'push_orgainzer'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'push_store'); ?>
		<?php echo $form->textField($model,'push_store'); ?>
		<?php echo $form->error($model,'push_store'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'push_agent'); ?>
		<?php echo $form->textField($model,'push_agent'); ?>
		<?php echo $form->error($model,'push_agent'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'count'); ?>
		<?php echo $form->textField($model,'count',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'count'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->