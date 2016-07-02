<?php
	$this->breadcrumbs = array(
		$model->firm_name=>array('own'),
		'修改密码',
	);
?>

<h1>修改密码 <font color='#eb6100'> <?php echo CHtml::encode($model->firm_name); ?></font></h1>

<div class="form wide">

<?php $form = $this->beginWidget('CActiveForm', array(
	'id'=>'agent-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">这些字段 <span class="required">*</span>是必须的.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'old_pwd'); ?>
		<?php echo $form->passwordField($model,'old_pwd',array('size'=>25, 'maxlength'=>20)); ?>
		<?php echo $form->error($model, 'old_pwd'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'new_pwd'); ?>
		<?php echo $form->passwordField($model,'new_pwd', array('size'=>25, 'maxlength'=>20)); ?>
		<?php echo $form->error($model, 'new_pwd'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'confirm_pwd'); ?>
		<?php echo $form->passwordField($model, 'confirm_pwd', array('size'=>25, 'maxlength'=>20)); ?>
		<?php echo $form->error($model, 'confirm_pwd'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->