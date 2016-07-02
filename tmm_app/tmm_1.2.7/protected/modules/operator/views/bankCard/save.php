<?php
/* @var $this BankCardController */
/* @var $model BankCard */

$this->breadcrumbs = array(
	Yii::app()->operator->name=>array('/operator/agent/own'),
	'我的银行卡'=>array('view'),
	'绑定银行卡短信验证',
);

?>

<h1>绑定银行卡短信验证 <font color='#eb6100'> <?php echo CHtml::encode($model->bank_name); ?></font></h1>

<div class="form wide">
	<?php $form = $this->beginWidget('CActiveForm', array(
		'id'=>'bank-card-form',
		'enableAjaxValidation'=>false,
		'enableClientValidation'=>true,
		'clientOptions'=>array(
			'validateOnSubmit'=>true,
		),
	)); ?>
	
	<p class="note">这些字段 <span class="required">*</span>是必须的.</p>
		
	<?php echo $form->errorSummary($model); ?>
	
	<div class="row value">
		<?php echo $form->labelEx($model,'bank_id'); ?>
		<?php echo $model->BankCard_Bank->name; ?>
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
	
	<div class="row">
		<?php echo $form->labelEx($model, 'sms'); ?>
		<?php echo $form->textField($model, 'sms', array('size'=>8,'maxlength'=>6)); ?>
		<span class="hint">短信验证码已发送至 <?php echo Yii::app()->controller->setHideKey($model->BankCard_Agent->phone); ?></span>
		<?php echo $form->error($model, 'sms', array(), false); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton('提交验证'); ?>
	</div>
	
	<?php $this->endWidget(); ?>

</div><!-- form -->