<div class="form wide">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'admin-link-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	'focus' => array($model,'name'),
)); ?>
	<p class="note">这些字段 <span class="required">*</span>是必须的。</p>
	<?php echo $form->errorSummary($model); ?>
	<?php if(isset($group)){?>
		<div class="row">
		<?php echo $form->labelEx($model,'nav'); ?>
		<?php echo $form->dropDownList($model,'nav',CHtml::listData($nav,'id','name'),array(
				'ajax'=>array(
					'type'=>'POST',
					'data'=>array('p_id'=>'js:this.value',Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),
                	'update'=>'#AdminLink_group',
           		 ),
			)); 
		?>
		<?php echo $form->error($model,'nav'); ?>
		</div>
		<div class="row">
		<?php echo $form->labelEx($model,'group'); ?>
		<?php echo $form->dropDownList($model,'group',CHtml::listData($group,'id','name')); ?>
		<?php echo $form->error($model,'group'); ?>
		</div>
	<?php }elseif(isset($nav)){?>
		<div class="row">
		<?php echo $form->labelEx($model,'nav'); ?>
		<?php echo $form->dropDownList($model,'nav',CHtml::listData($nav,'id','name')); ?>
		<?php echo $form->error($model,'nav'); ?>
		</div>
	<?php }?>
	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>20,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>40,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'info'); ?>
		<?php echo $form->textArea($model,'info',array('style'=>'width:350px;height:60px')); ?>
		<?php echo $form->error($model,'info'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'url'); ?>
		<?php echo $form->textField($model,'url',array('size'=>60,'maxlength'=>300)); ?>
		<?php echo $form->error($model,'url'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'params'); ?>
		<?php echo $form->textField($model,'params',array('size'=>60,'maxlength'=>300)); ?>
		<?php echo $form->error($model,'params'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'target'); ?>
		<?php echo $form->dropDownList($model,'target',$model::$_target); ?>
		<?php echo $form->error($model,'target'); ?>
	</div>

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