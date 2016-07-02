<?php
/* @var $this Tmm_billsController */
/* @var $model Bills */
/* @var $form CActiveForm */
?>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'bills-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">这些字段 <span class="required">*</span>是必须的.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'order_no'); ?>
		<?php echo $form->textField($model,'order_no',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'order_no'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'group_no'); ?>
		<?php echo $form->textField($model,'group_no',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'group_no'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'shops_name'); ?>
		<?php echo $form->textField($model,'shops_name',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'shops_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'shops_c_name'); ?>
		<?php echo $form->textField($model,'shops_c_name',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'shops_c_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'items_c_name'); ?>
		<?php echo $form->textField($model,'items_c_name',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'items_c_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'items_name'); ?>
		<?php echo $form->textField($model,'items_name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'items_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'group_price'); ?>
		<?php echo $form->textField($model,'group_price',array('size'=>13,'maxlength'=>13)); ?>
		<?php echo $form->error($model,'group_price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user_price'); ?>
		<?php echo $form->textField($model,'user_price',array('size'=>13,'maxlength'=>13)); ?>
		<?php echo $form->error($model,'user_price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'items_push'); ?>
		<?php echo $form->textField($model,'items_push'); ?>
		<?php echo $form->error($model,'items_push'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'items_push_orgainzer'); ?>
		<?php echo $form->textField($model,'items_push_orgainzer'); ?>
		<?php echo $form->error($model,'items_push_orgainzer'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'items_push_store'); ?>
		<?php echo $form->textField($model,'items_push_store'); ?>
		<?php echo $form->error($model,'items_push_store'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'items_push_agent'); ?>
		<?php echo $form->textField($model,'items_push_agent'); ?>
		<?php echo $form->error($model,'items_push_agent'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'items_money'); ?>
		<?php echo $form->textField($model,'items_money',array('size'=>13,'maxlength'=>13)); ?>
		<?php echo $form->error($model,'items_money'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'items_money_orgainzer'); ?>
		<?php echo $form->textField($model,'items_money_orgainzer',array('size'=>13,'maxlength'=>13)); ?>
		<?php echo $form->error($model,'items_money_orgainzer'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'items_money_store'); ?>
		<?php echo $form->textField($model,'items_money_store',array('size'=>13,'maxlength'=>13)); ?>
		<?php echo $form->error($model,'items_money_store'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'items_money_agent'); ?>
		<?php echo $form->textField($model,'items_money_agent',array('size'=>13,'maxlength'=>13)); ?>
		<?php echo $form->error($model,'items_money_agent'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'price'); ?>
		<?php echo $form->textField($model,'price',array('size'=>13,'maxlength'=>13)); ?>
		<?php echo $form->error($model,'price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'number'); ?>
		<?php echo $form->textField($model,'number',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'total'); ?>
		<?php echo $form->textField($model,'total',array('size'=>13,'maxlength'=>13)); ?>
		<?php echo $form->error($model,'total'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cash_status'); ?>
		<?php echo $form->textField($model,'cash_status'); ?>
		<?php echo $form->error($model,'cash_status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'son_status'); ?>
		<?php echo $form->textField($model,'son_status'); ?>
		<?php echo $form->error($model,'son_status'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->