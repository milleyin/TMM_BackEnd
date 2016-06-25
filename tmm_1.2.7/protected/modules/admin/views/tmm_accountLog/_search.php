<?php
/* @var $this Tmm_accountLogController */
/* @var $model AccountLog */
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
			<?php echo $form->label($model,'account_no'); ?>
			<?php echo $form->textField($model,'account_no',array('size'=>60,'maxlength'=>128)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'account_id'); ?>
			<?php echo $form->textField($model,'account_id',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'account_type'); ?>
			<?php echo $form->dropDownList($model,'account_type',array(''=>'--请选择--')+$model::$_account_type); ?>					
		</div>

		<div class="row">
			<?php echo $form->label($model,'to_account_id'); ?>
			<?php echo $form->textField($model,'to_account_id',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'to_account_type'); ?>
			<?php echo $form->dropDownList($model,'to_account_type',array(''=>'--请选择--')+$model::$_to_account_type); ?>		
		</div>

		<div class="row">
			<?php echo $form->label($model,'manage_account_id'); ?>
			<?php echo $form->textField($model,'manage_account_id',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'manage_account_type'); ?>
			<?php echo $form->dropDownList($model,'manage_account_type',array(''=>'--请选择--')+$model::$_manage_account_type); ?>				
		</div>

		<div class="row">
			<?php echo $form->label($model,'funds_type'); ?>
			<?php echo $form->dropDownList($model,'funds_type',array(''=>'--请选择--')+$model::$_funds_type); ?>		
		</div>

		<div class="row">
			<?php echo $form->label($model,'funds_type_name'); ?>
			<?php echo $form->textField($model,'funds_type_name',array('size'=>60,'maxlength'=>128)); ?>			
		</div>

		<div class="row">
			<?php echo $form->label($model,'money_type'); ?>
			<?php echo $form->dropDownList($model,'money_type',array(''=>'--请选择--')+$model::$_money_type); ?>		
		</div>

		<div class="row">
			<?php echo $form->label($model,'use_money'); ?>
			<?php echo $form->textField($model,'use_money',array('size'=>13,'maxlength'=>13)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'count'); ?>
			<?php echo $form->textField($model,'count',array('size'=>13,'maxlength'=>13)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'total'); ?>
			<?php echo $form->textField($model,'total',array('size'=>13,'maxlength'=>13)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'money'); ?>
			<?php echo $form->textField($model,'money',array('size'=>13,'maxlength'=>13)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'no_money'); ?>
			<?php echo $form->textField($model,'no_money',array('size'=>13,'maxlength'=>13)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'cash_count'); ?>
			<?php echo $form->textField($model,'cash_count',array('size'=>13,'maxlength'=>13)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'pay_count'); ?>
			<?php echo $form->textField($model,'pay_count',array('size'=>13,'maxlength'=>13)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'refund_count'); ?>
			<?php echo $form->textField($model,'refund_count',array('size'=>13,'maxlength'=>13)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'consume_count'); ?>
			<?php echo $form->textField($model,'consume_count',array('size'=>13,'maxlength'=>13)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'not_consume_count'); ?>
			<?php echo $form->textField($model,'not_consume_count',array('size'=>13,'maxlength'=>13)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'after_count'); ?>
			<?php echo $form->textField($model,'after_count',array('size'=>13,'maxlength'=>13)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'after_total'); ?>
			<?php echo $form->textField($model,'after_total',array('size'=>13,'maxlength'=>13)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'after_money'); ?>
			<?php echo $form->textField($model,'after_money',array('size'=>13,'maxlength'=>13)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'after_no_money'); ?>
			<?php echo $form->textField($model,'after_no_money',array('size'=>13,'maxlength'=>13)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'after_cash_count'); ?>
			<?php echo $form->textField($model,'after_cash_count',array('size'=>13,'maxlength'=>13)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'after_pay_count'); ?>
			<?php echo $form->textField($model,'after_pay_count',array('size'=>13,'maxlength'=>13)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'after_refund_count'); ?>
			<?php echo $form->textField($model,'after_refund_count',array('size'=>13,'maxlength'=>13)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'after_consume_count'); ?>
			<?php echo $form->textField($model,'after_consume_count',array('size'=>13,'maxlength'=>13)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'after_not_consume_count'); ?>
			<?php echo $form->textField($model,'after_not_consume_count',array('size'=>13,'maxlength'=>13)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'info_id'); ?>
			<?php echo $form->textField($model,'info_id',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'info_type'); ?>
			<?php echo $form->textField($model,'info_type'); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'info'); ?>
			<?php echo $form->textArea($model,'info',array('rows'=>6, 'cols'=>50)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'name'); ?>
			<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>128)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'ip'); ?>
			<?php echo $form->textField($model,'ip',array('size'=>15,'maxlength'=>15)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'address'); ?>
			<?php echo $form->textField($model,'address',array('size'=>60,'maxlength'=>100)); ?>
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
			<?php echo $form->label($model,'up_count'); ?>
			<?php echo $form->textField($model,'up_count',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'log_status'); ?>
			<?php echo $form->dropDownList($model,'log_status',array(''=>'--请选择--')+$model::$_log_status); ?>			
		</div>

		<div class="row">
			<?php echo $form->label($model,'centre_status'); ?>
			<?php echo $form->dropDownList($model,'centre_status',array(''=>'--请选择--')+$model::$_centre_status); ?>
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