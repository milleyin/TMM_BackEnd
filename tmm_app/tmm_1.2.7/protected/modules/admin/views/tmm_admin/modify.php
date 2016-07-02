<?php
/* @var $this ZuserController */
/* @var $model Admin */

$this->breadcrumbs=array(
	$model->username.' ['.$model->name.']'=>array('own'),
	'更新我的账户',
);
?>
<h1>更新我的账户 <?php echo CHtml::encode($model->username.' ['.$model->name.']'); ?></h1>
<div class="wide form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'admin-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
			'validateOnSubmit'=>true,
	),
)); ?>
	<p class="note">这些字段<span class="required">*</span>是必须的。</p>
	<?php echo $form->errorSummary($model); ?>
	<div class="row value">
			<?php echo  $form->labelEx($model,'username');?>
			<?php echo $model->username?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>20,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone',array('size'=>20,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'phone'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'d_id'); ?>
		<?php echo $form->dropDownList($model,'d_id',array(''=>'--请选择--')+$model::$_d_id); ?>
		<?php echo $form->error($model,'d_id'); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton('保存'); ?>
	</div>
<?php $this->endWidget(); ?>
</div><!-- form -->