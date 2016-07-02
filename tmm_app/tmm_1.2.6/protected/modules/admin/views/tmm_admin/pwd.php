<?php
$this->breadcrumbs=array(
	$model->username.' ['.$model->name.']'=>array('own'),
	'修改密码',
);
?>
<h1>修改我的密码 <?php echo CHtml::encode($model->username).' ['.CHtml::encode($model->name).']'; ?></h1>
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
			<?php echo $form->labelEx($model,'username'); ?>
			<?php echo $model->username?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'old_pwd'); ?>
		<?php echo $form->passwordField($model,'old_pwd',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'old_pwd',array(),false); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'new_pwd'); ?>
		<?php echo $form->passwordField($model,'new_pwd',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'new_pwd'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'confirm_pwd'); ?>
		<?php echo $form->passwordField($model,'confirm_pwd',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'confirm_pwd'); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton('保存'); ?>
	</div>
<?php $this->endWidget(); ?>
</div><!-- form -->