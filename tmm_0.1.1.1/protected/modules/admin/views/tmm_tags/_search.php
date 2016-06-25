<?php
/* @var $this Tmm_tagsController */
/* @var $model Tags */
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
			<?php echo $form->label($model,'admin_id'); ?>
			<?php echo $form->textField($model,'admin_id',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'name'); ?>
			<?php echo $form->textField($model,'name',array('size'=>20,'maxlength'=>20)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'weight'); ?>
			<?php echo $form->textField($model,'weight',array('size'=>10,'maxlength'=>10)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'sort'); ?>
			<?php echo $form->textField($model,'sort'); ?>
		</div>

	<div class="row">
			<?php echo $form->label($model,'status'); ?>
			<?php echo $form->dropDownList($model,'status',array(''=>'--请选择--')+$model::$_status); ?>
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
			<?php echo $form->label($model,'up_time'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>Yii::app()->language,
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
						),
						'htmlOptions'=>array(
								//'maxlength'=>10,
								//'size'=>10,
						),
					));
			?>	
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