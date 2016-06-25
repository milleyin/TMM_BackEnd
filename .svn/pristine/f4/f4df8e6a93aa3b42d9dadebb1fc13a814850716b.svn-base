<?php
/* @var $this Tmm_orderRetinueController */
/* @var $model OrderRetinue */
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
			<?php echo $form->label($model,'order_id'); ?>
			<?php echo $form->textField($model,'order_id',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'order_no'); ?>
			<?php echo $form->textField($model,'order_no',array('size'=>60,'maxlength'=>128)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'son_order_id'); ?>
			<?php echo $form->textField($model,'son_order_id',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'son_order_no'); ?>
			<?php echo $form->textField($model,'son_order_no',array('size'=>60,'maxlength'=>128)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'user_id'); ?>
			<?php echo $form->textField($model,'user_id',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'retinue_id'); ?>
			<?php echo $form->textField($model,'retinue_id',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'retinue_name'); ?>
			<?php echo $form->textField($model,'retinue_name',array('size'=>20,'maxlength'=>20)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'retinue_gender'); ?>
			<?php echo $form->textField($model,'retinue_gender'); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'retinue_identity'); ?>
			<?php echo $form->textField($model,'retinue_identity',array('size'=>18,'maxlength'=>18)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'retinue_phone'); ?>
			<?php echo $form->textField($model,'retinue_phone',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'insure_name'); ?>
			<?php echo $form->textField($model,'insure_name',array('size'=>20,'maxlength'=>20)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'insure_gender'); ?>
			<?php echo $form->textField($model,'insure_gender'); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'insure_identity'); ?>
			<?php echo $form->textField($model,'insure_identity',array('size'=>18,'maxlength'=>18)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'insure_phone'); ?>
			<?php echo $form->textField($model,'insure_phone',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'insure_age'); ?>
			<?php echo $form->textField($model,'insure_age',array('size'=>3,'maxlength'=>3)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'is_main'); ?>
			<?php echo $form->textField($model,'is_main'); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'insure_no'); ?>
			<?php echo $form->textField($model,'insure_no',array('size'=>60,'maxlength'=>128)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'insure_verify'); ?>
			<?php echo $form->textField($model,'insure_verify',array('size'=>60,'maxlength'=>128)); ?>
		</div>

	<div class="row">
			<?php echo $form->label($model,'start_time'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>Yii::app()->language,
						'model'=>$model,
						'attribute'=>'start_time',
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
			<?php echo $form->label($model,'insure_price'); ?>
			<?php echo $form->textField($model,'insure_price',array('size'=>13,'maxlength'=>13)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'fact_price'); ?>
			<?php echo $form->textField($model,'fact_price',array('size'=>13,'maxlength'=>13)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'insure_number'); ?>
			<?php echo $form->textField($model,'insure_number',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'beneficiary'); ?>
			<?php echo $form->textField($model,'beneficiary',array('size'=>15,'maxlength'=>15)); ?>
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