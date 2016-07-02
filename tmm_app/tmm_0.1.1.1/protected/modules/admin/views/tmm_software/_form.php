<?php
/* @var $this Tmm_softwareController */
/* @var $model Software */
/* @var $form CActiveForm */
?>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'software-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="note">这些字段 <span class="required">*</span>是必须的.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php echo $form->dropDownList($model,'type',Software::$_type); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'use'); ?>
		<?php echo $form->dropDownList($model,'use',Software::$_use); ?>
		<?php echo $form->error($model,'use'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'version'); ?>
		<?php echo $form->textField($model,'version'); ?>
		<?php echo $form->error($model,'version'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'version_name'); ?>
		<?php echo $form->textField($model,'version_name'); ?>
		<?php echo $form->error($model,'version_name'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'file_path'); ?>
		<?php echo $form->fileField($model,'file_path',array('style'=>'width:300px;')); ?>		
		<?php echo $form->error($model,'file_path'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->