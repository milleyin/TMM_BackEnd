<?php
/* @var $this Tmm_shopsController */
/* @var $model Shops */
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
			<?php echo $form->label($model,'tags_ids'); ?>
			<?php echo $form->textField($model,'tags_ids',array('size'=>11,'maxlength'=>11)); ?>
		</div>
		
		<div class="row">
			<?php echo $form->label($model,'agent_id'); ?>
			<?php echo $form->textField($model,'agent_id',array('size'=>20)); ?>
		</div>
		
		<div class="row">
			<?php echo $form->label($model,'c_id'); ?>
			<?php echo $form->dropDownList($model,'c_id',ShopsClassliy::data()); ?>	
		</div>
		
		<div class="row">
			<?php echo $form->label($model,'name'); ?>
			<?php echo $form->textField($model,'name',array('size'=>24,'maxlength'=>24)); ?>
		</div>
		
		<div class="row">
			<?php echo $form->label($model,'list_info'); ?>
			<?php echo $form->textArea($model,'list_info',array('style'=>'width:300px;height:40px;')); ?>
		</div>
		<div class="row">
			<?php echo $form->label($model,'page_info'); ?>
			<?php echo $form->textArea($model,'page_info',array('style'=>'width:300px;height:40px;')); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'brow'); ?>
			<?php echo $form->textField($model,'brow',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'share'); ?>
			<?php echo $form->textField($model,'share',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'praise'); ?>
			<?php echo $form->textField($model,'praise',array('size'=>11,'maxlength'=>11)); ?>
		</div>
		
		<div class="row">
			<?php echo $form->label($model,'is_sale'); ?>
			<?php echo $form->dropDownList($model,'is_sale',array(''=>'--请选择--')+$model::$_is_sale); ?>
		</div>
		
		<div class="row">
			<?php echo $form->label($model,'selected'); ?>
			<?php echo $form->dropDownList($model,'selected',array(''=>'--请选择--')+$model::$_selected); ?>
		</div>
		
		<div class="row">
			<?php echo $form->label($model,'selected_time'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>'zh-CN',
						'model'=>$model,
						'attribute'=>'selected_time',
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
			<?php echo $form->label($model,'tops'); ?>
			<?php echo $form->dropDownList($model,'tops',array(''=>'--请选择--')+$model::$_tops); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'tops_time'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>'zh-CN',
						'model'=>$model,
						'attribute'=>'tops_time',
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
			<?php echo $form->label($model,'selected_tops'); ?>
			<?php echo $form->dropDownList($model,'selected_tops',array(''=>'--请选择--')+$model::$_selected_tops); ?>
		</div>
		
		<div class="row">
			<?php echo $form->label($model,'selected_tops_time'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>'zh-CN',
						'model'=>$model,
						'attribute'=>'selected_tops_time',
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
			<?php echo $form->label($model,'pub_time'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>'zh-CN',
						'model'=>$model,
						'attribute'=>'pub_time',
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
		<?php echo $form->label($model,'up_time'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
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
		<?php echo $form->label($model,'audit'); ?>
		<?php echo $form->dropDownList($model,'audit',array(''=>'--请选择--')+$model::$_audit); ?>
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
		?>		
	</div>
		
	<div class="row">
	<?php echo $form->label($model,'search_end_time'); ?>
	<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
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
	?>	
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton('搜索'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->