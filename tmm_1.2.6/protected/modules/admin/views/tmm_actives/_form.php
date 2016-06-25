<?php
/* @var $this Tmm_activesController */
/* @var $model Actives */
/* @var $form CActiveForm */
?>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'actives-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">这些字段 <span class="required">*</span>是必须的.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'id'); ?>
		<?php echo $form->textField($model,'id',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'actives_type'); ?>
		<?php echo $form->textField($model,'actives_type'); ?>
		<?php echo $form->error($model,'actives_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tour_type'); ?>
		<?php echo $form->textField($model,'tour_type'); ?>
		<?php echo $form->error($model,'tour_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'push'); ?>
		<?php echo $form->textField($model,'push'); ?>
		<?php echo $form->error($model,'push'); ?>
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
		<?php echo $form->labelEx($model,'price'); ?>
		<?php echo $form->textField($model,'price',array('size'=>13,'maxlength'=>13)); ?>
		<?php echo $form->error($model,'price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'number'); ?>
		<?php echo $form->textField($model,'number',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tour_price'); ?>
		<?php echo $form->textField($model,'tour_price',array('size'=>13,'maxlength'=>13)); ?>
		<?php echo $form->error($model,'tour_price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'remark'); ?>
		<?php echo $form->textArea($model,'remark',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'remark'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'actives_status'); ?>
		<?php echo $form->textField($model,'actives_status'); ?>
		<?php echo $form->error($model,'actives_status'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->