<?php
/* @var $this Tmm_storeController */
/* @var $model StoreUser */
/* @var $form CActiveForm */
?>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'store-user-form',
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
		<?php echo $form->labelEx($model,'_pwd'); ?>
		<?php echo $form->passwordField($model,'_pwd',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'_pwd'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'confirm_pwd'); ?>
		<?php echo $form->passwordField($model,'confirm_pwd',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'confirm_pwd'); ?>
	</div>




        

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->