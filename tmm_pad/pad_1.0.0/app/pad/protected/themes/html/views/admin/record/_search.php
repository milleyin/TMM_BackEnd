<?php
/* @var $this RecordController */
/* @var $model Record */
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
		<?php echo $form->label($model,'user_id'); ?>
		<?php echo $form->textField($model,'user_id',array('size'=>20,'maxlength'=>20)); ?>
	</div>
 
	<div class="row">
		<?php echo $form->label($model,'store_id'); ?>
		<?php echo $form->textField($model,'store_id',array('size'=>20,'maxlength'=>20)); ?>
	</div>
 
	<div class="row">
		<?php echo $form->label($model,'pad_id'); ?>
		<?php echo $form->textField($model,'pad_id',array('size'=>20,'maxlength'=>20)); ?>
	</div>
 
	<div class="row">
		<?php echo $form->label($model,'config_id'); ?>
		<?php echo $form->textField($model,'config_id',array('size'=>20,'maxlength'=>20)); ?>
	</div>
 
	<div class="row">
		<?php echo $form->label($model,'prize_id'); ?>
		<?php echo $form->textField($model,'prize_id',array('size'=>20,'maxlength'=>20)); ?>
	</div>
 
	<div class="row">
		<?php echo $form->label($model,'chance_id'); ?>
		<?php echo $form->textField($model,'chance_id',array('size'=>20,'maxlength'=>20)); ?>
	</div>
 
	<div class="row">
		<?php echo $form->label($model,'manager_id'); ?>
		<?php echo $form->textField($model,'manager_id',array('size'=>20,'maxlength'=>20)); ?>
	</div>
 
	<div class="row">
		<?php echo $form->label($model,'receive_type'); ?>
		<?php echo $form->textField($model,'receive_type'); ?>
	</div>
 
	<div class="row">
		<?php echo $form->label($model,'prize_name'); ?>
		<?php echo $form->textField($model,'prize_name',array('size'=>32,'maxlength'=>32)); ?>
	</div>
 
	<div class="row">
		<?php echo $form->label($model,'prize_info'); ?>
		<?php echo $form->textField($model,'prize_info',array('size'=>60,'maxlength'=>128)); ?>
	</div>
 
	<div class="row">
		<?php echo $form->label($model,'url'); ?>
		<?php echo $form->textField($model,'url',array('size'=>60,'maxlength'=>128)); ?>
	</div>
 
	<div class="row">
		<?php echo $form->label($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'exchange_time'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'language'=>'zh-CN',
				'model'=>$model,
				'attribute'=>'exchange_time',
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
		<?php echo $form->label($model,'exchange_status'); ?>
		<?php echo $form->textField($model,'exchange_status'); ?>
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