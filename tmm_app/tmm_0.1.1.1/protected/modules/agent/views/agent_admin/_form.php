<?php
/* @var $this Agent_adminController */
/* @var $model Agent */
/* @var $form CActiveForm */
?>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'agent-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="note">这些字段 <span class="required">*</span>是必须的.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model,'phone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'firm_name'); ?>
		<?php echo $form->textField($model,'firm_name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'firm_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address'); ?>
		<?php echo $form->textField($model,'address',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'firm_tel'); ?>
		<?php echo $form->textField($model,'firm_tel',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'firm_tel'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'firm_postcode'); ?>
		<?php echo $form->textField($model,'firm_postcode',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'firm_postcode'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'com_contacts'); ?>
		<?php echo $form->textField($model,'com_contacts',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'com_contacts'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'com_identity'); ?>
		<?php echo $form->textField($model,'com_identity',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model,'com_identity'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'com_phone'); ?>
		<?php echo $form->textField($model,'com_phone',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model,'com_phone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bl_code'); ?>
		<?php echo $form->textField($model,'bl_code',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'bl_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bl_img'); ?>
		<?php echo $form->fileField($model,'bl_img',array('style'=>'width:300px;')); ?>
		<?php echo $form->error($model,'bl_img'); ?>
	</div>


	<div class="row">
		<?php echo $form->labelEx($model,'manage_name'); ?>
		<?php echo $form->textField($model,'manage_name',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'manage_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'manage_phone'); ?>
		<?php echo $form->textField($model,'manage_phone',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model,'manage_phone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'manage_identity'); ?>
		<?php echo $form->textField($model,'manage_identity',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model,'manage_identity'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'identity_before'); ?>
		<?php echo $form->textField($model,'identity_before',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'identity_before'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'identity_after'); ?>
		<?php echo $form->textField($model,'identity_after',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'identity_after'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'identity_hand'); ?>
		<?php echo $form->textField($model,'identity_hand',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'identity_hand'); ?>
	</div>


	<div class="row">
		<?php echo $form->labelEx($model,'bank_branch'); ?>
		<?php echo $form->textField($model,'bank_branch',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'bank_branch'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bank_name'); ?>
		<?php echo $form->textField($model,'bank_name',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'bank_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bank_code'); ?>
		<?php echo $form->textField($model,'bank_code',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'bank_code'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->