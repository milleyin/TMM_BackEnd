<?php
/* @var $this Tmm_orderActivesController */
/* @var $model OrderActives */
/* @var $form CActiveForm */
?>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'order-actives-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">这些字段 <span class="required">*</span>是必须的.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'actives_no'); ?>
		<?php echo $form->textField($model,'actives_no',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'actives_no'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'actives_type'); ?>
		<?php echo $form->textField($model,'actives_type'); ?>
		<?php echo $form->error($model,'actives_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user_price'); ?>
		<?php echo $form->textField($model,'user_price',array('size'=>13,'maxlength'=>13)); ?>
		<?php echo $form->error($model,'user_price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'total'); ?>
		<?php echo $form->textField($model,'total',array('size'=>13,'maxlength'=>13)); ?>
		<?php echo $form->error($model,'total'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->