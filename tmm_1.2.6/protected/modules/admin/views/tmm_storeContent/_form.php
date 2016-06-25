<?php
/* @var $this Tmm_storeContentController */
/* @var $model_c StoreContent */
/* @var $form CActiveForm */
?>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'store-content-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">这些字段 <span class="required">*</span>是必须的.</p>

	<?php echo $form->errorSummary($model_c); ?>

	<div class="row">
		<?php echo $form->labelEx($model_c,'id'); ?>
		<?php echo $form->textField($model_c,'id',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model_c,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model_c,'name'); ?>
		<?php echo $form->textField($model_c,'name',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model_c,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model_c,'push'); ?>
		<?php echo $form->textField($model_c,'push'); ?>
		<?php echo $form->error($model_c,'push'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model_c,'cash'); ?>
		<?php echo $form->textField($model_c,'cash',array('size'=>13,'maxlength'=>13)); ?>
		<?php echo $form->error($model_c,'cash'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model_c,'money'); ?>
		<?php echo $form->textField($model_c,'money',array('size'=>13,'maxlength'=>13)); ?>
		<?php echo $form->error($model_c,'money'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model_c,'deposit'); ?>
		<?php echo $form->textField($model_c,'deposit',array('size'=>13,'maxlength'=>13)); ?>
		<?php echo $form->error($model_c,'deposit'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model_c,'address'); ?>
		<?php echo $form->textField($model_c,'address',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model_c,'address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model_c,'store_tel'); ?>
		<?php echo $form->textField($model_c,'store_tel',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model_c,'store_tel'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model_c,'store_postcode'); ?>
		<?php echo $form->textField($model_c,'store_postcode'); ?>
		<?php echo $form->error($model_c,'store_postcode'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model_c,'lx_contacts'); ?>
		<?php echo $form->textField($model_c,'lx_contacts',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model_c,'lx_contacts'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model_c,'lx_phone'); ?>
		<?php echo $form->textField($model_c,'lx_phone',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model_c,'lx_phone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model_c,'identity_before'); ?>
		<?php echo $form->textField($model_c,'identity_before',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model_c,'identity_before'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model_c,'identity_after'); ?>
		<?php echo $form->textField($model_c,'identity_after',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model_c,'identity_after'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model_c,'identity_hand'); ?>
		<?php echo $form->textField($model_c,'identity_hand',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model_c,'identity_hand'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model_c,'com_contacts'); ?>
		<?php echo $form->textField($model_c,'com_contacts',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model_c,'com_contacts'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model_c,'com_phone'); ?>
		<?php echo $form->textField($model_c,'com_phone',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model_c,'com_phone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model_c,'bl_code'); ?>
		<?php echo $form->textField($model_c,'bl_code',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model_c,'bl_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model_c,'bl_img'); ?>
		<?php echo $form->textField($model_c,'bl_img',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model_c,'bl_img'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model_c,'bank_name'); ?>
		<?php echo $form->textField($model_c,'bank_name',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model_c,'bank_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model_c,'bank_branch'); ?>
		<?php echo $form->textField($model_c,'bank_branch',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model_c,'bank_branch'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model_c,'bank_code'); ?>
		<?php echo $form->textField($model_c,'bank_code',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model_c,'bank_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model_c,'son_limit'); ?>
		<?php echo $form->textField($model_c,'son_limit',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model_c,'son_limit'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model_c,'audit'); ?>
		<?php echo $form->textField($model_c,'audit'); ?>
		<?php echo $form->error($model_c,'audit'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model_c->isNewRecord ? '创建' : '保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->