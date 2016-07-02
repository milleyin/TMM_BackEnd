<?php
/* @var $this Tmm_orderRetinueController */
/* @var $model OrderRetinue */
/* @var $form CActiveForm */
?>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'order-retinue-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">这些字段 <span class="required">*</span>是必须的.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'order_no'); ?>
		<?php echo $form->textField($model,'order_no',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'order_no'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'son_order_no'); ?>
		<?php echo $form->textField($model,'son_order_no',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'son_order_no'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'retinue_name'); ?>
		<?php echo $form->textField($model,'retinue_name',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'retinue_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'retinue_gender'); ?>
		<?php echo $form->textField($model,'retinue_gender'); ?>
		<?php echo $form->error($model,'retinue_gender'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'retinue_phone'); ?>
		<?php echo $form->textField($model,'retinue_phone',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'retinue_phone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'insure_name'); ?>
		<?php echo $form->textField($model,'insure_name',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'insure_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'insure_gender'); ?>
		<?php echo $form->textField($model,'insure_gender'); ?>
		<?php echo $form->error($model,'insure_gender'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'insure_phone'); ?>
		<?php echo $form->textField($model,'insure_phone',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'insure_phone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'insure_age'); ?>
		<?php echo $form->textField($model,'insure_age',array('size'=>3,'maxlength'=>3)); ?>
		<?php echo $form->error($model,'insure_age'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'is_main'); ?>
		<?php echo $form->textField($model,'is_main'); ?>
		<?php echo $form->error($model,'is_main'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'insure_no'); ?>
		<?php echo $form->textField($model,'insure_no',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'insure_no'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'insure_verify'); ?>
		<?php echo $form->textField($model,'insure_verify',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'insure_verify'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'insure_price'); ?>
		<?php echo $form->textField($model,'insure_price',array('size'=>13,'maxlength'=>13)); ?>
		<?php echo $form->error($model,'insure_price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fact_price'); ?>
		<?php echo $form->textField($model,'fact_price',array('size'=>13,'maxlength'=>13)); ?>
		<?php echo $form->error($model,'fact_price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'insure_number'); ?>
		<?php echo $form->textField($model,'insure_number',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'insure_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'beneficiary'); ?>
		<?php echo $form->textField($model,'beneficiary',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model,'beneficiary'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->