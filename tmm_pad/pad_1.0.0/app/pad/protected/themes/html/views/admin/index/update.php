<?php
/* @var $this AdminController */
/* @var $model Admin */

$this->breadcrumbs = array(
	'我的信息' => array('index'),
	'更新我的信息',
);
?>

<h1>更新我的信息 <font color='#eb6100'><?php echo CHtml::encode($model->username); ?></font></h1>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'admin-form',
	'focus'=>array($model, 'name'),
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">这些字段<span class="required">*</span>是必须的。</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model, 'name'); ?>
		<?php echo $form->textField($model, 'name',array('size'=>32, 'maxlength'=>32)); ?>
		<?php echo $form->error($model, 'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'phone'); ?>
		<?php echo $form->textField($model, 'phone', array('size'=>11, 'maxlength'=>11)); ?>
		<?php echo $form->error($model, 'phone'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->