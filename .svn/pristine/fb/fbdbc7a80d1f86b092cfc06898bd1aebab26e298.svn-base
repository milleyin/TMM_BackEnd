<?php
/* @var $this PasswordController */
/* @var $model Password */
/* @var $form CActiveForm */
?>
<p>
你可以输入比较运算符,这是可选的 (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) 开始你的每一个搜索的值来指定应该如何做比较。
</p>
<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
 
	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id',array('size'=>20,'maxlength'=>20)); ?>
	</div>
 
	<div class="row">
		<?php echo $form->label($model,'type'); ?>
		<?php echo $form->textField($model,'type'); ?>
	</div>
 
	<div class="row">
		<?php echo $form->label($model,'role_id'); ?>
		<?php echo $form->textField($model,'role_id',array('size'=>20,'maxlength'=>20)); ?>
	</div>
 
	<div class="row">
		<?php echo $form->label($model,'manager_id'); ?>
		<?php echo $form->textField($model,'manager_id',array('size'=>20,'maxlength'=>20)); ?>
	</div>
 
	<div class="row">
		<?php echo $form->label($model,'salt'); ?>
		<?php echo $form->textField($model,'salt',array('size'=>60,'maxlength'=>128)); ?>
	</div>
 
	<div class="row">
		<?php echo $form->label($model,'pass_count'); ?>
		<?php echo $form->textField($model,'pass_count',array('size'=>11,'maxlength'=>11)); ?>
	</div>
 
	<div class="row">
		<?php echo $form->label($model,'error_count'); ?>
		<?php echo $form->textField($model,'error_count',array('size'=>11,'maxlength'=>11)); ?>
	</div>
 
	<div class="row">
		<?php echo $form->label($model,'use_error'); ?>
		<?php echo $form->textField($model,'use_error',array('size'=>11,'maxlength'=>11)); ?>
	</div>
 
	<div class="row">
		<?php echo $form->label($model,'use_ip'); ?>
		<?php echo $form->textField($model,'use_ip',array('size'=>15,'maxlength'=>15)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'use_time'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'language'=>'zh-CN',
				'model'=>$model,
				'attribute'=>'use_time',
				'value'=>date('Y-m-d'),
				'options'=>array(
						'maxDate'=>'new date()',
						'dateFormat'=>'yy-mm-dd',
						'showOn' => 'focus',
						'showOtherMonths' => true,
						'selectOtherMonths' => true,
						'changeMonth' => true,
						'changeYear' => true,
						'showButtonPanel' => true,
				)
			));
		?>	
	</div>
 
	<div class="row">
		<?php echo $form->label($model,'last_ip'); ?>
		<?php echo $form->textField($model,'last_ip',array('size'=>15,'maxlength'=>15)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'last_time'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'language'=>'zh-CN',
				'model'=>$model,
				'attribute'=>'last_time',
				'value'=>date('Y-m-d'),
				'options'=>array(
						'maxDate'=>'new date()',
						'dateFormat'=>'yy-mm-dd',
						'showOn' => 'focus',
						'showOtherMonths' => true,
						'selectOtherMonths' => true,
						'changeMonth' => true,
						'changeYear' => true,
						'showButtonPanel' => true,
				)
			));
		?>	
	</div>
 
	<div class="row">
		<?php echo $form->label($model,'up_count'); ?>
		<?php echo $form->textField($model,'up_count',array('size'=>11,'maxlength'=>11)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'up_time'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'language'=>'zh-CN',
				'model'=>$model,
				'attribute'=>'up_time',
				'value'=>date('Y-m-d'),
				'options'=>array(
						'maxDate'=>'new date()',
						'dateFormat'=>'yy-mm-dd',
						'showOn' => 'focus',
						'showOtherMonths' => true,
						'selectOtherMonths' => true,
						'changeMonth' => true,
						'changeYear' => true,
						'showButtonPanel' => true,
				)
			));
		?>	
	</div>

	<div class="row">
		<?php echo $form->label($model, 'add_time'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'language'=>'zh-CN',
				'model'=>$model,
				'attribute'=>'add_time',
				'value'=>date('Y-m-d'),
				'options'=>array(
						'maxDate'=>'new date()',
						'dateFormat'=>'yy-mm-dd',
						'showOn' => 'focus',
						'showOtherMonths' => true,
						'selectOtherMonths' => true,
						'changeMonth' => true,
						'changeYear' => true,
						'showButtonPanel' => true,
				)
			));
		?>	
	</div>

	<div class="row">
			<?php echo $form->label($model, 'status'); ?>
			<?php echo $form->dropDownList($model, 'status', array(''=>'--请选择--')+$model::$_status); ?>
	</div>
 
	<div class="row buttons">
		<?php echo CHtml::submitButton('搜索'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->