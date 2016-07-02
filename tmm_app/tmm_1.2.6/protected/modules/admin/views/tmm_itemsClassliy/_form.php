<?php
/* @var $this Tmm_itemsClassliyController */
/* @var $model ItemsClassliy */
/* @var $form CActiveForm */
?>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'items-classliy-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">这些字段 <span class="required">*</span>是必须的.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'info'); ?>
		<?php echo $form->textArea($model,'info',array('style'=>'width:300px;height:100px;')); ?>	
		<?php echo $form->error($model,'info'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'admin'); ?>
		<?php echo $form->textField($model,'admin',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'admin'); ?>
	</div>
		<div class="row">
		<?php echo $form->labelEx($model,'main'); ?>
		<?php echo $form->textField($model,'main',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'main'); ?>
	</div>
		<div class="row">
		<?php echo $form->labelEx($model,'append'); ?>
		<?php echo $form->textField($model,'append',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'append'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'nexus'); ?>
		<?php echo $form->textField($model,'nexus',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'nexus'); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->