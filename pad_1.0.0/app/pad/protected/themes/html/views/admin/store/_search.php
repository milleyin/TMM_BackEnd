<?php
/* @var $this StoreController */
/* @var $model Store */
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
		<?php echo $form->label($model,'phone'); ?>
		<?php echo $form->textField($model,'phone',array('size'=>11,'maxlength'=>11)); ?>
	</div>
 
	<div class="row">
		<?php echo $form->label($model,'store_name'); ?>
		<?php echo $form->textField($model,'store_name',array('size'=>16,'maxlength'=>16)); ?>
	</div>
 
	<div class="row">
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>16,'maxlength'=>16)); ?>
	</div>
 
	<div class="row">
		<?php echo $form->label($model,'telephone'); ?>
		<?php echo $form->textField($model,'telephone',array('size'=>11,'maxlength'=>11)); ?>
	</div>
 
	<div class="row">
		<?php echo $form->label($model,'pad_count'); ?>
		<?php echo $form->textField($model,'pad_count',array('size'=>11,'maxlength'=>11)); ?>
	</div>
 
	<div class="row">
		<?php echo $form->labelEx($model, 'province'); ?>
		<?php echo $form->dropDownList($model, 'province', array(''=>'--请选择--') + Area::model()->getAreaArray(), array(
		        'ajax'=>array(
	                'type'=>'POST',
	                'url'=>CHtml::normalizeUrl(array('area/drop')),
	                'data'=>array('pid'=>'js:this.value', Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),
	                'success'=>'function(data){
							jQuery("#' . CHtml::activeId($model, 'city') . '").html(data);
							jQuery("#' . CHtml::activeId($model, 'district') . '").html("<option value=\"\" selected=\"selected\">--请选择--</option>");
					 }',
		        ),
		)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'city'); ?>
		<?php echo $form->dropDownList($model, 'city', array(''=>'--请选择--') + Area::model()->getAreaArray($model->province), array(
		        'ajax'=>array(
	                'type'=>'POST',
	                'url'=>CHtml::normalizeUrl(array('area/drop')),
	                'data'=>array('pid'=>'js:this.value', Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),
	                'update'=>'#' . CHtml::activeId($model, 'district'),
		        ),
		)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'district'); ?>
		<?php echo $form->dropDownList($model, 'district', array(''=>'--请选择--') + Area::model()->getAreaArray($model->city)); ?>
	</div>
 
	<div class="row">
		<?php echo $form->label($model,'address'); ?>
		<?php echo $form->textField($model,'address',array('size'=>60,'maxlength'=>128)); ?>
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