<?php
/* @var $this AdminController */
/* @var $model Admin */
/* @var $form CActiveForm */
?>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'admin-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">这些字段 <span class="required">*</span>是必须的.</p>

	<?php echo $form->errorSummary($model); ?>
	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'name'); ?>
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
	<div class="row">
		<?php echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone',array('size'=>16,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'phone'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'d_id'); ?>
		<?php echo $form->dropDownList($model,'d_id',array(''=>'--请选择--')+$model::$_d_id); ?>
		<?php echo $form->error($model,'d_id'); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
	</div>
<?php $this->endWidget(); ?>

</div><!-- form -->