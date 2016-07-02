<?php
/* @var $this UploadController */
/* @var $model Upload */
/* @var $form CActiveForm */
?>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'upload-form',
	'focus'=>array($model, 'info'),
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="note">这些字段<span class="required">*</span>是必须的。</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model, 'info'); ?>
		<?php echo $form->textField($model, 'info',array('size'=>30,'maxlength'=>128)); ?>
		<?php echo $form->error($model, 'info'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'path'); ?>
		<?php echo $form->fileField($model, 'path'); ?>
		<?php echo $form->error($model, 'path'); ?>
	</div>

	<?php 
    	if (file_exists($model->getAbsolutePath()))
        {
	?>
	    <div class="row <?php echo $model->upload_type != Upload::UPLOAD_UPLOAD_TYPE_IMAGE ? 'value' : '';?>">
    		<?php echo $form->labelEx($model, '_old_path'); ?>
    		<?php 
    		if ($model->upload_type == Upload::UPLOAD_UPLOAD_TYPE_IMAGE)
    		      echo CHtml::image($model->getUrlPath(), CHtml::encode($model->info), array('style'=>'widht:150px;height:150px;')); 
    		else
    		      echo CHtml::link($model::$_upload_type[$model->upload_type], $model->getUrlPath(), array('target'=>'_blank'));
    		?>		
		</div>
	<?php 
        }
	?>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->