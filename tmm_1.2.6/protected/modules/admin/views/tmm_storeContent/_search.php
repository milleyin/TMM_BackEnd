<?php
/* @var $this Tmm_storeContentController */
/* @var $model StoreContent */
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
			<?php echo $form->label($model,'name'); ?>
			<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>128)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'push'); ?>
			<?php echo $form->textField($model,'push'); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'income_count'); ?>
			<?php echo $form->textField($model,'income_count',array('size'=>13,'maxlength'=>13)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'cash'); ?>
			<?php echo $form->textField($model,'cash',array('size'=>13,'maxlength'=>13)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'money'); ?>
			<?php echo $form->textField($model,'money',array('size'=>13,'maxlength'=>13)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'deposit'); ?>
			<?php echo $form->textField($model,'deposit',array('size'=>13,'maxlength'=>13)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'area_id_p'); ?>
			<?php echo $form->textField($model,'area_id_p',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'area_id_m'); ?>
			<?php echo $form->textField($model,'area_id_m',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'area_id_c'); ?>
			<?php echo $form->textField($model,'area_id_c',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'address'); ?>
			<?php echo $form->textField($model,'address',array('size'=>60,'maxlength'=>100)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'store_tel'); ?>
			<?php echo $form->textField($model,'store_tel',array('size'=>15,'maxlength'=>15)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'store_postcode'); ?>
			<?php echo $form->textField($model,'store_postcode'); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'lx_contacts'); ?>
			<?php echo $form->textField($model,'lx_contacts',array('size'=>15,'maxlength'=>15)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'lx_identity_code'); ?>
			<?php echo $form->textField($model,'lx_identity_code',array('size'=>32,'maxlength'=>32)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'lx_phone'); ?>
			<?php echo $form->textField($model,'lx_phone',array('size'=>15,'maxlength'=>15)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'identity_before'); ?>
			<?php echo $form->textField($model,'identity_before',array('size'=>60,'maxlength'=>100)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'identity_after'); ?>
			<?php echo $form->textField($model,'identity_after',array('size'=>60,'maxlength'=>100)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'identity_hand'); ?>
			<?php echo $form->textField($model,'identity_hand',array('size'=>60,'maxlength'=>100)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'com_contacts'); ?>
			<?php echo $form->textField($model,'com_contacts',array('size'=>15,'maxlength'=>15)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'com_phone'); ?>
			<?php echo $form->textField($model,'com_phone',array('size'=>15,'maxlength'=>15)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'com_identity'); ?>
			<?php echo $form->textField($model,'com_identity',array('size'=>32,'maxlength'=>32)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'bl_code'); ?>
			<?php echo $form->textField($model,'bl_code',array('size'=>60,'maxlength'=>100)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'bl_img'); ?>
			<?php echo $form->textField($model,'bl_img',array('size'=>60,'maxlength'=>100)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'bank_id'); ?>
			<?php echo $form->textField($model,'bank_id',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'bank_name'); ?>
			<?php echo $form->textField($model,'bank_name',array('size'=>20,'maxlength'=>20)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'bank_branch'); ?>
			<?php echo $form->textField($model,'bank_branch',array('size'=>60,'maxlength'=>100)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'bank_code'); ?>
			<?php echo $form->textField($model,'bank_code',array('size'=>50,'maxlength'=>50)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'son_count'); ?>
			<?php echo $form->textField($model,'son_count',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'son_limit'); ?>
			<?php echo $form->textField($model,'son_limit',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'audit'); ?>
			<?php echo $form->textField($model,'audit'); ?>
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
			<?php echo $form->label($model,'pass_time'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>Yii::app()->language,
						'model'=>$model,
						'attribute'=>'pass_time',
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