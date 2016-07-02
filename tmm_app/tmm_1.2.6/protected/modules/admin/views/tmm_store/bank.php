<?php
/* @var $this Tmm_organizerController */
/* @var $model Organizer */

$this->breadcrumbs=array(
	'供应商账号管理页'=>array('admin'),
	$model->phone,
	'更新供应商账号银行信息',
);
?>
<h1>更新供应商账号银行信息<font color='#eb6100'><?php echo CHtml::encode($model->phone); ?></font></h1>

<div class="form wide">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'store-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>
	<p class="note">这些字段 <span class="required">*</span>是必须的.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model->Store_Content,'bank_id'); ?>
		<?php echo $form->dropDownList($model->Store_Content,'bank_id',array(''=>'--请选择--')+Bank::data()); ?>
		<?php echo $form->error($model->Store_Content,'bank_id'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model->Store_Content,'bank_branch'); ?>
		<?php echo $form->textField($model->Store_Content,'bank_branch',array('size'=>40,'maxlength'=>40)); ?>
		<?php echo $form->error($model->Store_Content,'bank_branch'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model->Store_Content,'bank_name'); ?>
		<?php echo $form->textField($model->Store_Content,'bank_name',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model->Store_Content,'bank_name'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model->Store_Content,'bank_code'); ?>
		<?php echo $form->textField($model->Store_Content,'bank_code',array('size'=>40,'maxlength'=>40)); ?>
		<?php echo $form->error($model->Store_Content,'bank_code'); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton('保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->