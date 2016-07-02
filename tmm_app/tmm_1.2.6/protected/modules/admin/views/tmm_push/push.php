<?php
/* @var $this Tmm_organizerController */
/* @var $model Organizer */

$this->breadcrumbs=array(
	'项目管理页'=>array('/admin/tmm_items/admin'),
	'项目('.$model->Push_Items->Items_ItemsClassliy->name.')管理页'=>array('/admin/tmm_'.$model->Push_Items->Items_ItemsClassliy->admin.'/admin'),
	$model->Push_Items->name=>array('/admin/tmm_'.$model->Push_Items->Items_ItemsClassliy->admin.'/view','id'=>$model->Push_Items->id),
	'更新组织银行信息',
);
?>
<h1>设置项目 定时分成<font color='#eb6100'><?php echo CHtml::encode($model->Push_Items->name); ?></font></h1>
<div class="form wide">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'push-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>
	<p class="note">这些字段 <span class="required">*</span>是必须的</p>
	<?php echo $form->errorSummary($model); ?>
	<div class="row value">
		<?php echo $form->label($model,'executed_push'); ?>
		<?php echo Push::executed($model->Push_Items->id,'push'); ?>%
	</div>
	<div class="row value">
		<?php echo $form->label($model,'executed_push_agent'); ?>
		<?php echo Push::executed($model->Push_Items->id,'push_agent'); ?>%
	</div>
	<div class="row value">
		<?php echo $form->label($model,'executed_push_store'); ?>
		<?php echo Push::executed($model->Push_Items->id,'push_store'); ?>%
	</div>
	<div class="row value">
		<?php echo $form->label($model,'executed_push_orgainzer'); ?>
		<?php echo Push::executed($model->Push_Items->id,'push_orgainzer'); ?>%
	</div>
	<div class="row">
			<?php echo $form->labelEx($model,'_start_time'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>Yii::app()->language,
						'model'=>$model,
						'attribute'=>'_start_time',
						'value'=>date('Y-m-d'),
						'options'=>array(
								//'maxDate'=>'new date()',
								'minDate'=>'new date()',
								'dateFormat'=>'yy-mm-dd',
								'showOn' => 'focus',
								'showOtherMonths' => true,
								'selectOtherMonths' => true,
								'changeMonth' => true,
								'changeYear' => true,
								'showButtonPanel' => true,
						),
						'htmlOptions'=>array(
						),
					));
			?>
			<?php echo $form->error($model,'_start_time'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'push'); ?>
		<?php echo $form->textField($model,'push'); ?>%
		<?php echo $form->error($model,'push'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'push_agent'); ?>
		<?php echo $form->textField($model,'push_agent'); ?>%
		<?php echo $form->error($model,'push_agent'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'push_store'); ?>
		<?php echo $form->textField($model,'push_store'); ?>%
		<?php echo $form->error($model,'push_store'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'push_orgainzer'); ?>
		<?php echo $form->textField($model,'push_orgainzer'); ?>%
		<?php echo $form->error($model,'push_orgainzer'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'info'); ?>
		<?php echo $form->textArea($model,'info',array('style'=>'width:300px;height:100px;')); ?>
		<?php echo $form->error($model,'info'); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton('保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->