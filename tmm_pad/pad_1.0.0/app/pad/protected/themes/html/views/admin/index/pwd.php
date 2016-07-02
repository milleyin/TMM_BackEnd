<?php
/* @var $this AdminController */
/* @var $model Admin */

$this->breadcrumbs = array(
	'我的信息' => array('index'),
	'修改密码',
);
?>

<h1>修改密码 <font color='#eb6100'><?php echo CHtml::encode($model->Password_Admin->username); ?></font></h1>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'password-form',
	'focus'=>array($model, '_pwd'),
	'enableAjaxValidation'=>false,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">这些字段<span class="required">*</span>是必须的。</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model, '_old_pwd'); ?>
		<?php echo $form->passwordField($model,'_old_pwd',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model, '_old_pwd'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model, '_pwd'); ?>
		<?php echo $form->passwordField($model,'_pwd',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model, '_pwd'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model, '_confirm_pwd'); ?>
		<?php echo $form->passwordField($model, '_confirm_pwd',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model, '_confirm_pwd'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->