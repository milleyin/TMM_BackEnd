<?php
/* @var $this CashController */
/* @var $model Cash */

$this->breadcrumbs = array(
	'申请提现'=>array('admin'),
	'申请提现短信验证页',
);
?>

<h1>申请提现短信验证</h1>

<div class="form wide">

<?php $form = $this->beginWidget('CActiveForm', array(
	'id'=>'cash-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">这些字段 <span class="required">*</span>是必须的.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row value">
		<?php echo $form->labelEx($model->Cash_Bank,'name'); ?>
		<?php echo $model->Cash_Bank->name; ?>
	</div>

	<div class="row value">
		<?php echo $form->labelEx($model,'bank_branch'); ?>
		<?php echo $model->bank_branch; ?>
	</div>
	
	<div class="row value">
		<?php echo $form->labelEx($model,'bank_name'); ?>
		<?php echo $model->bank_name; ?>
	</div>

	<div class="row value">
		<?php echo $form->labelEx($model,'bank_code'); ?>
		<?php echo $model->bank_code; ?>
	</div>
	
	<div class="row value">
		<?php echo $form->labelEx($model, 'price'); ?>
		<?php echo Yii::app()->controller->money_floor($model->price);?> （元）
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'sms'); ?>
		<?php echo $form->textField($model, 'sms', array('size'=>8,'maxlength'=>6)); ?>
		<span class="hint">短信验证码已发送至 <?php echo Yii::app()->controller->setHideKey($model->Cash_Agent->phone); ?></span>
		<?php echo $form->error($model, 'sms', array(), false); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton('验证保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->