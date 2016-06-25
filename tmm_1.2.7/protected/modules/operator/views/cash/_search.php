<?php
/* @var $this CashController */
/* @var $model Cash */
/* @var $form CActiveForm */
?>
<p>
你可以输入比较运算符,这是可选的 (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) 开始你的每一个搜索的值来指定应该如何做比较。
</p>
<div class="wide form">

<?php $form = $this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

		<div class="row">
			<?php echo $form->label($model, 'id'); ?>
			<?php echo $form->textField($model,'id',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'cash_type'); ?>
			<?php echo $form->textField($model,'cash_type',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'cash_id'); ?>
			<?php echo $form->textField($model,'cash_id',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'admin_id_first'); ?>
			<?php echo $form->textField($model,'admin_id_first',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'remark_first'); ?>
			<?php echo $form->textField($model,'remark_first',array('size'=>60,'maxlength'=>100)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'first_time'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'language'=>'zh-CN',
						'model'=>$model,
						'attribute'=>'first_time',
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
			<?php echo $form->label($model, 'admin_id_double'); ?>
			<?php echo $form->textField($model,'admin_id_double',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'remark_double'); ?>
			<?php echo $form->textField($model,'remark_double',array('size'=>60,'maxlength'=>100)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'double_time'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'language'=>'zh-CN',
						'model'=>$model,
						'attribute'=>'double_time',
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
			<?php echo $form->label($model, 'admin_id_submit'); ?>
			<?php echo $form->textField($model,'admin_id_submit',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'remark_submit'); ?>
			<?php echo $form->textField($model,'remark_submit',array('size'=>60,'maxlength'=>100)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'submit_time'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'language'=>'zh-CN',
						'model'=>$model,
						'attribute'=>'submit_time',
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
			<?php echo $form->label($model, 'money'); ?>
			<?php echo $form->textField($model,'money',array('size'=>13,'maxlength'=>13)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'price'); ?>
			<?php echo $form->textField($model,'price',array('size'=>13,'maxlength'=>13)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'fee_price'); ?>
			<?php echo $form->textField($model,'fee_price',array('size'=>13,'maxlength'=>13)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'fact_price'); ?>
			<?php echo $form->textField($model,'fact_price',array('size'=>13,'maxlength'=>13)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'bank_card_id'); ?>
			<?php echo $form->textField($model,'bank_card_id',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'bank_id'); ?>
			<?php echo $form->textField($model,'bank_id',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'bank_name'); ?>
			<?php echo $form->textField($model,'bank_name',array('size'=>20,'maxlength'=>20)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'bank_branch'); ?>
			<?php echo $form->textField($model,'bank_branch',array('size'=>60,'maxlength'=>100)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'bank_code'); ?>
			<?php echo $form->textField($model,'bank_code',array('size'=>50,'maxlength'=>50)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'bank_type'); ?>
			<?php echo $form->textField($model,'bank_type',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'bank_identity'); ?>
			<?php echo $form->textField($model,'bank_identity',array('size'=>18,'maxlength'=>18)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'cash_status'); ?>
			<?php echo $form->textField($model,'cash_status'); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'audit_status'); ?>
			<?php echo $form->textField($model,'audit_status'); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'pay_type'); ?>
			<?php echo $form->textField($model,'pay_type'); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'pay_time'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'language'=>'zh-CN',
						'model'=>$model,
						'attribute'=>'pay_time',
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
						),
						'htmlOptions'=>array(
								//'maxlength'=>10,
								//'size'=>10,
						),
					));
			?>	
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
						),
						'htmlOptions'=>array(
								//'maxlength'=>10,
								//'size'=>10,
						),
					));
			?>	
		</div>
			<div class="row">
			<?php echo $form->label($model, 'status'); ?>
			<?php echo $form->dropDownList($model, 'status', array(''=>'--请选择--')+$model::$_status); ?>
		</div>
		
	<div class="row">
		<?php echo $form->label($model,'search_time_type'); ?>
		<?php echo $form->dropDownList($model, 'search_time_type', array(''=>'--请选择--')+$model::$_search_time_type);?> 
	</div>
	<div class="row">
		<?php echo $form->label($model, 'search_start_time'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
					'language'=>'zh-CN',
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
		<?php echo $form->label($model, 'search_end_time'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
					'language'=>'zh-CN',
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