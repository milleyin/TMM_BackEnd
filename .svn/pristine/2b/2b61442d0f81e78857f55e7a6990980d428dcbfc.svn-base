<?php
/* @var $this Tmm_adController */
/* @var $model Ad */

$this->breadcrumbs=array(
	'广告专题管理页'=>array('admin'),
	'广告直接创建页',
);
?>

<h1>创建 广告</h1>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ad-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="note">这些字段 <span class="required">*</span>是必须的.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'type'); ?>
		<?php echo $form->dropDownList($model, 'type', array(''=>'--请选择--') + array_slice($model::$_type, 0, 2, true), array(
							'ajax'=>array(
								'type'=>'POST',
								'dataType'=>'json',
								'data'=>array('type'=>'js:this.value', Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),
								'success'=>'function(data){
										jQuery("#' . CHtml::activeId($model, 'p_id') . '").html(data[0]);
										jQuery("#' . CHtml::activeId($model, 'link_type') . '").html(data[1]);
										if (jQuery("#' . CHtml::activeId($model, 'type') . '").val() =="' . Ad::type_hot . '"){
											jQuery("#' . CHtml::activeId($model, 'info') . '").attr("placeholder","请输入原产地");
										}else{
											jQuery("#' . CHtml::activeId($model, 'info') . '").attr("placeholder","");
										}
								}',

			           		 ),
						));?>
		<?php echo $form->error($model, 'type'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'p_id'); ?>
		<?php echo $form->dropDownList($model, 'p_id', array(''=>'--请选择--'));?>
		<?php echo $form->error($model, 'p_id'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'link_type'); ?>
		<?php echo $form->dropDownList($model, 'link_type', array(''=>'--请选择--'));?>
		<?php echo $form->error($model, 'link_type'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'info'); ?>
		<?php echo $form->textArea($model,'info',array('style'=>'width:500px;height:100px;')); ?>
		<?php echo $form->error($model,'info'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'link'); ?>
		<?php echo $form->textField($model,'link',array('style'=>'width:500px;','maxlength'=>128)); ?>
		<?php echo $form->error($model,'link'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'img'); ?>
		<?php echo $form->fileField($model,'img',array('style'=>'width:505px;')); ?>
		<?php echo $form->error($model,'img'); ?>
	</div>
	
	<?php 
		if(file_exists($model->img))
		{
			echo '<div class="row"><label>'.$model->getAttributeLabel('img').'</label>';
			echo $this->show_img($model->img);
			echo '</div>';
		}
	?>

	<div class="row">
		<?php echo $form->labelEx($model,'sort'); ?>
		<?php echo $form->textField($model,'sort'); ?>
		<?php echo $form->error($model,'sort'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->