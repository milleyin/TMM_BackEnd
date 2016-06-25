<?php
/* @var $this Tmm_smsLogController */
/* @var $model SmsLog */
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
			<?php echo $form->textField($model,'id',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'phone'); ?>
			<?php echo $form->textField($model,'phone',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'sms_id'); ?>
			<?php echo $form->textField($model,'sms_id',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'sms_type'); ?>
			<?php echo $form->textField($model,'sms_type',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'role_id'); ?>
			<?php echo $form->textField($model,'role_id',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'role_type'); ?>
			<?php echo $form->textField($model,'role_type',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'sms_use'); ?>
			<?php echo $form->textField($model,'sms_use',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'code'); ?>
			<?php echo $form->textField($model,'code',array('size'=>6,'maxlength'=>6)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'sms_content'); ?>
			<?php echo $form->textField($model,'sms_content',array('size'=>60,'maxlength'=>255)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'sms_source'); ?>
			<?php echo $form->textField($model,'sms_source',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'sms_ip'); ?>
			<?php echo $form->textField($model,'sms_ip',array('size'=>15,'maxlength'=>15)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'login_address'); ?>
			<?php echo $form->textField($model,'login_address',array('size'=>60,'maxlength'=>100)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'error_count'); ?>
			<?php echo $form->textField($model,'error_count',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'sms_error'); ?>
			<?php echo $form->textField($model,'sms_error',array('size'=>11,'maxlength'=>11)); ?>
		</div>

	<div class="row">
			<?php echo $form->label($model,'end_time'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>Yii::app()->language,
						'model'=>$model,
						'attribute'=>'end_time',
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
						),
						'htmlOptions'=>array(
								//'maxlength'=>10,
								//'size'=>10,
						),
					));
			?>	
		</div>
			<div class="row">
			<?php echo $form->label($model,'add_time'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>Yii::app()->language,
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
						),
						'htmlOptions'=>array(
								//'maxlength'=>10,
								//'size'=>10,
						),
					));
			?>	
		</div>
			<div class="row">
			<?php echo $form->label($model,'status'); ?>
			<?php echo $form->dropDownList($model,'status',array(''=>'--请选择--')+$model::$_status); ?>
		</div>
		
	<div class="row">
		<?php echo $form->label($model,'search_time_type'); ?>
		<?php echo $form->dropDownList($model,'search_time_type',array(''=>'--请选择--')+$model::$_search_time_type);?> 
	</div>
	<div class="row">
		<?php echo $form->label($model,'search_start_time'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
					'language'=>Yii::app()->language,
					'model'=>$model,
					'attribute'=>'search_start_time',
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
					),
					'htmlOptions'=>array(
							//'maxlength'=>10,
							//'size'=>10,
					),
				));
		?>		</div>
		<div class="row">
		<?php echo $form->label($model,'search_end_time'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
					'language'=>Yii::app()->language,
					'model'=>$model,
					'attribute'=>'search_end_time',
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
					),
					'htmlOptions'=>array(
							//'maxlength'=>10,
							//'size'=>10,
					),
				));
		?>	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton('搜索'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->