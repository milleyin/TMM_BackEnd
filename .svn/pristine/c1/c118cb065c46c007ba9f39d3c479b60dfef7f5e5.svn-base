<?php
/* @var $this Tmm_dotController */
/* @var $model Dot */
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
		<?php echo $form->label($model->Dot_Shops,'name'); ?>
		<?php echo $form->textField($model->Dot_Shops,'name',array('size'=>40,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model->Dot_Shops,'agent_id'); ?>
		<?php echo $form->textField($model->Dot_Shops,'agent_id',array('size'=>40,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model->Dot_Shops,'brow'); ?>
		<?php echo $form->textField($model->Dot_Shops,'brow',array('size'=>40,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model->Dot_Shops,'share'); ?>
		<?php echo $form->textField($model->Dot_Shops,'share',array('size'=>30,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model->Dot_Shops,'praise'); ?>
		<?php echo $form->textField($model->Dot_Shops,'praise',array('size'=>30,'maxlength'=>100)); ?>
	</div>


	<div class="row">
		<?php echo $form->label($model->Dot_Shops,'pub_time'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
			'language'=>Yii::app()->language,
			'model'=>$model->Dot_Shops,
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
		<?php echo $form->label($model->Dot_Shops,'add_time'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
			'language'=>Yii::app()->language,
			'model'=>$model->Dot_Shops,
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
		<?php echo $form->label($model->Dot_Shops,'up_time'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
			'language'=>Yii::app()->language,
			'model'=>$model->Dot_Shops,
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
		<?php echo $form->label($model->Dot_Shops,'audit'); ?>
		<?php echo $form->dropDownList($model->Dot_Shops,'audit',array(''=>'--请选择--')+Shops::$_audit); ?>
	</div>
	<div class="row">
		<?php echo $form->label($model->Dot_Shops,'status'); ?>
		<?php echo $form->dropDownList($model->Dot_Shops,'status',array(''=>'--请选择--')+Shops::$_status); ?>
	</div>
	<div class="row">
		<?php echo $form->label($model->Dot_Shops,'is_sale'); ?>
		<?php echo $form->dropDownList($model->Dot_Shops,'is_sale',array(''=>'--请选择--')+Shops::$_is_sale); ?>
	</div>
	<div class="row">
		<?php echo $form->label($model->Dot_Shops,'selected'); ?>
		<?php echo $form->dropDownList($model->Dot_Shops,'selected',array(''=>'--请选择--')+Shops::$_selected); ?>
	</div>
	<div class="row">
		<?php echo $form->label($model->Dot_Shops,'tops'); ?>
		<?php echo $form->dropDownList($model->Dot_Shops,'tops',array(''=>'--请选择--')+Shops::$_tops); ?>
	</div>
	<div class="row">
		<?php echo $form->label($model->Dot_Shops,'selected_tops'); ?>
		<?php echo $form->dropDownList($model->Dot_Shops,'selected_tops',array(''=>'--请选择--')+Shops::$_selected_tops); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model->Dot_Shops,'search_time_type'); ?>
		<?php echo $form->dropDownList($model->Dot_Shops,'search_time_type',array(''=>'--请选择--')+Shops::$_search_time_type);?>
	</div>
	<div class="row">
		<?php echo $form->label($model->Dot_Shops,'search_start_time'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
			'language'=>Yii::app()->language,
			'model'=>$model->Dot_Shops,
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
		<?php echo $form->label($model->Dot_Shops,'search_end_time'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
			'language'=>Yii::app()->language,
			'model'=>$model->Dot_Shops,
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