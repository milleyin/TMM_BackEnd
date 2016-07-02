<?php
/* @var $this Tmm_tagsElementController */
/* @var $model TagsElement */
/* @var $form CActiveForm */
?>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tags-element-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">这些字段 <span class="required">*</span>是必须的.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'select_type'); ?>
		<?php echo $form->textField($model,'select_type',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'select_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'element_type'); ?>
		<?php echo $form->textField($model,'element_type',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'element_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sort'); ?>
		<?php echo $form->textField($model,'sort'); ?>
		<?php echo $form->error($model,'sort'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->