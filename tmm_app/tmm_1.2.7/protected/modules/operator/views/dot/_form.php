<?php
/** @var $this DotController */
/** @var $model Dot */
/** @var $form CActiveForm */
?>
<div class="form wide">

<?php $form = $this->beginWidget('CActiveForm', array(
	'id'=>'dot-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">这些字段 <span class="required">*</span>是必须的.</p>

	<?php echo $form->errorSummary(array_merge(array($model->Dot_Shops), $model->Dot_Pro)); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model->Dot_Shops,'name'); ?>
		<?php echo $form->textField($model->Dot_Shops,'name',array('size'=>24,'maxlength'=>24)); ?>
		<?php echo $form->error($model->Dot_Shops,'name'); ?>	
	</div>
	
	<?php
		$this->widget('system.web.widgets.CTabView',array(
				'tabs'=>array(
						'tab1'=>array(
								'title'=>'添加项目',
								'view'=>'_form/name',
						),
						'tab2'=>array(
								'title'=>'内容设置',
								'view'=>'_form/content',
						),
				),
				'viewData'=>array('model'=>$model, 'form'=>$form, 'itemsModel'=>$itemsModel),
				'htmlOptions'=>array('id'=>'tmm_tabs'),
		));
	?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
