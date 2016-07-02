<?php
/* @var $this Tmm_reasonController */
/* @var $model Reason */
/* @var $form CActiveForm */
?>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'reason-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">这些字段 <span class="required">*</span>是必须的.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'role_type'); ?>
		<?php echo $form->dropDownList($model,'role_type', array(''=>'--请选择--')+$model::$_role_type); ?>
		<?php echo $form->error($model,'role_type'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'order_type'); ?>
		<?php echo $form->dropDownList($model,'order_type', array(''=>'--请选择--')+$model::$_order_type); ?>
		<?php echo $form->error($model,'order_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sale_type'); ?>
		<?php echo $form->dropDownList($model,'sale_type', array(''=>'--请选择--')+$model::$_sale_type); ?>
		<?php echo $form->error($model,'sale_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cargo_type'); ?>
		<?php echo $form->dropDownList($model,'cargo_type', array(''=>'--请选择--')+$model::$_cargo_type); ?>
		<?php echo $form->error($model,'cargo_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>33,'maxlength'=>16)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'reason'); ?>
		<?php echo $form->textField($model,'reason',array('size'=>33,'maxlength'=>28)); ?>
		<?php echo $form->error($model,'reason'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'remark'); ?>
		<?php echo $form->textArea($model,'remark',array('style'=>'width:300px;height:100px;')); ?>
		<?php echo $form->error($model,'remark'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sort'); ?>
		<?php echo $form->textField($model,'sort',array('size'=>20,'maxlength'=>5)); ?>
		<?php echo $form->error($model,'sort'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->