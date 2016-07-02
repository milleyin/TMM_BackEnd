<?php
/* @var $this Tmm_tagsTypeController */
/* @var $model TagsType */
/* @var $form CActiveForm */
?>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tags-type-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">这些字段 <span class="required">*</span>是必须的.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'p_id'); ?>
		<?php echo $form->dropDownList($model,'p_id',array('0'=>'顶级分类')+$model->_p_id); ?>
		<?php echo $form->error($model,'p_id'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'is_user'); ?>
		<?php echo $form->dropDownList($model,'is_user',array(''=>'--请选择--')+$model::$_is_user); ?>
		<?php echo $form->error($model,'is_user'); ?>
	</div>
		<div class="row">
		<?php echo $form->labelEx($model,'is_search'); ?>
		<?php echo $form->dropDownList($model,'is_search',array(''=>'--请选择--')+$model::$_is_search); ?>
		<?php echo $form->error($model,'is_search'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'is_left'); ?>
		<?php echo $form->dropDownList($model,'is_left',array(''=>'--请选择--')+$model::$_is_left); ?>
		<?php echo $form->error($model,'is_left'); ?>
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