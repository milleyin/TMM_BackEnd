<?php
/* @var $this Tmm_refundLogController */
/* @var $model RefundLog */
/* @var $form CActiveForm */
?>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'refund-log-form',
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
		<?php echo $form->labelEx($model,'reason'); ?>
		<?php echo $form->textField($model,'reason',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'reason'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'refund_price'); ?>
		<?php echo $form->textField($model,'refund_price',array('size'=>13,'maxlength'=>13)); ?>
		<?php echo $form->error($model,'refund_price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'refund_type'); ?>
		<?php echo $form->textField($model,'refund_type',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'refund_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'refund_courier'); ?>
		<?php echo $form->textField($model,'refund_courier',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'refund_courier'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'refund_courier_num'); ?>
		<?php echo $form->textField($model,'refund_courier_num',array('size'=>32,'maxlength'=>32)); ?>
		<?php echo $form->error($model,'refund_courier_num'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'remark_first'); ?>
		<?php echo $form->textField($model,'remark_first',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'remark_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'remark_double'); ?>
		<?php echo $form->textField($model,'remark_double',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'remark_double'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'remark_submit'); ?>
		<?php echo $form->textField($model,'remark_submit',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'remark_submit'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'refund_img1'); ?>
		<?php echo $form->textField($model,'refund_img1',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'refund_img1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'refund_img2'); ?>
		<?php echo $form->textField($model,'refund_img2',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'refund_img2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'refund_img3'); ?>
		<?php echo $form->textField($model,'refund_img3',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'refund_img3'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'refund_img4'); ?>
		<?php echo $form->textField($model,'refund_img4',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'refund_img4'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'refund_img5'); ?>
		<?php echo $form->textField($model,'refund_img5',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'refund_img5'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'is_organizer'); ?>
		<?php echo $form->textField($model,'is_organizer'); ?>
		<?php echo $form->error($model,'is_organizer'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'push'); ?>
		<?php echo $form->textField($model,'push'); ?>
		<?php echo $form->error($model,'push'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user_push'); ?>
		<?php echo $form->textField($model,'user_push'); ?>
		<?php echo $form->error($model,'user_push'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'push_orgainzer'); ?>
		<?php echo $form->textField($model,'push_orgainzer'); ?>
		<?php echo $form->error($model,'push_orgainzer'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'push_store'); ?>
		<?php echo $form->textField($model,'push_store'); ?>
		<?php echo $form->error($model,'push_store'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'push_agent'); ?>
		<?php echo $form->textField($model,'push_agent'); ?>
		<?php echo $form->error($model,'push_agent'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->